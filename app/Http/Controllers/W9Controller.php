<?php

namespace App\Http\Controllers;

use App\W9Form;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * IRS Form W-9 — filled online by the agent during onboarding, stored, and available to
 * admins as a rendered PDF. Mirrors the NDA approach: everything needed to rebuild the PDF
 * lives in the database, so a missing file can never lose the submission.
 */
class W9Controller extends Controller
{
    /** Has a W-9 been filed for this agent — from the CRM, or from the HR portal before the account existed? */
    public static function submittedFor(?int $userId, ?int $hrEmployeeId): bool
    {
        if (!$userId && !$hrEmployeeId) {
            return false;
        }

        return W9Form::where(function ($q) use ($userId, $hrEmployeeId) {
            if ($userId)       { $q->orWhere('user_id', $userId); }
            if ($hrEmployeeId) { $q->orWhere('hr_employee_id', $hrEmployeeId); }
        })->exists();
    }

    /**
     * Does this agent still owe a W-9? (US / Hello agents only.)
     *
     * Asked once HR or an admin sends it: user.w9_required (CRM button) or, since 23 Oct 2026,
     * hr_employees.w9_required (HR sends it during onboarding, before the CRM account exists).
     * It is a reminder now, not a lock — see partials/account_verification_gate.
     */
    public static function isRequiredFor($user): bool
    {
        if (!$user) {
            return false;
        }

        // W-9 is a US tax form — only asked of Hello Transport agents, never CrazyRays staff.
        if (method_exists($user, 'isCrazyrays') && $user->isCrazyrays()) {
            return false;
        }

        // Admins don't onboard.
        if ((int) ($user->role ?? 0) === 1) {
            return false;
        }

        static $hasUserFlag = null, $hasHrFlag = null;
        $hasUserFlag ??= \Illuminate\Support\Facades\Schema::hasColumn('user', 'w9_required');
        $hasHrFlag   ??= \Illuminate\Support\Facades\Schema::hasColumn('hr_employees', 'w9_required');

        $hr = DB::table('hr_employees')->where('agent_id', $user->id)
            ->first($hasHrFlag ? ['id', 'w9_required'] : ['id']);

        $assigned = ($hasUserFlag && (int) ($user->w9_required ?? 0) === 1)
            || ($hasHrFlag && $hr && (int) $hr->w9_required === 1);

        return $assigned && !self::submittedFor($user->id, $hr->id ?? null);
    }

    /** Validation shared by the agent portal and the HR portal (via the bridge). */
    public static function rules(): array
    {
        return [
            'legal_name'           => 'required|string|max:255',
            'business_name'        => 'nullable|string|max:255',
            'tax_classification'   => 'required|in:' . implode(',', array_keys(W9Form::CLASSIFICATIONS)),
            'llc_tax_class'        => 'nullable|required_if:tax_classification,llc|in:C,S,P',
            'other_classification' => 'nullable|required_if:tax_classification,other|string|max:255',
            'exempt_payee_code'    => 'nullable|string|max:10',
            'fatca_code'           => 'nullable|string|max:10',
            'address'              => 'required|string|max:255',
            'city'                 => 'required|string|max:100',
            'state'                => 'required|string|max:100',
            'zip'                  => ['required', 'string', 'max:20', 'regex:/^\d{5}(-\d{4})?$/'],
            'account_numbers'      => 'nullable|string|max:255',
            'tin_type'             => 'required|in:ssn,ein',
            // SSN = 9 digits, EIN = 9 digits; accept dashes and normalise below.
            'tin'                  => ['required', 'string', 'max:20', 'regex:/^[\d-]{9,11}$/'],
            'signature_data'       => 'required|string',
            'certified'            => 'required|in:1',
        ];
    }

    public static function messages(): array
    {
        return [
            'zip.regex'            => 'Enter a valid zip code (12345 or 12345-6789).',
            'tin.regex'            => 'Enter a valid 9-digit SSN or EIN.',
            'certified.required'   => 'You must certify the information to submit the form.',
            'llc_tax_class.required_if'        => 'Choose the LLC tax classification (C, S or P).',
            'other_classification.required_if' => 'Describe the "Other" classification.',
        ];
    }

    /**
     * Checks the field rules cannot express. Returns an error response, or null when the
     * submission may be saved.
     */
    public static function rejectSubmission(array $validated, ?int $userId, ?int $hrEmployeeId): ?JsonResponse
    {
        // The signature must be a real drawing, not an empty canvas.
        $sigBinary = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $validated['signature_data']));
        if (!$sigBinary || strlen($sigBinary) < 100) {
            return response()->json(['success' => false, 'message' => 'Please draw your signature before submitting.'], 422);
        }

        if (strlen(preg_replace('/\D/', '', $validated['tin'])) !== 9) {
            return response()->json(['success' => false, 'errors' => ['tin' => ['A TIN must be exactly 9 digits.']]], 422);
        }

        if (self::submittedFor($userId, $hrEmployeeId)) {
            return response()->json(['success' => false, 'message' => 'Your W-9 has already been submitted.'], 409);
        }

        return null;
    }

    /** Store a validated submission and render its PDF. $user may be null (filed from HR before the CRM account). */
    public static function saveSubmission(array $validated, $user, ?int $hrEmployeeId, ?string $ip): W9Form
    {
        $form = new W9Form();
        $form->fill(array_merge(
            collect($validated)->except(['tin', 'signature_data', 'certified'])->all(),
            [
                'user_id'        => $user->id ?? null,
                'hr_employee_id' => $hrEmployeeId,
                'signature'      => $validated['signature_data'],
                'signed_ip'      => $ip,
                'signed_at'      => now(),
            ]
        ));
        $form->setTin($validated['tin']);
        $form->save();

        // Render the PDF (non-blocking — the submission is already safely stored).
        try {
            $form->document_path = self::buildPdf($form, $user);
            // Absolute URL on the portal that generated it, so the HR panel (a different
            // cPanel account) can link to the file directly.
            if (\Illuminate\Support\Facades\Schema::hasColumn('w9_forms', 'document_url')) {
                $form->document_url = url($form->document_path);
            }
            $form->save();
        } catch (\Throwable $e) {
            Log::error('W9 PDF generation failed (submission still recorded)', [
                'user_id' => $user->id ?? null, 'hr_employee_id' => $hrEmployeeId, 'error' => $e->getMessage(),
            ]);
        }

        return $form;
    }

    /** Admin/manager: send the W-9 to a (Hello) agent — NDA-style assign + notify. */
    public function requireW9(\Illuminate\Http\Request $request): JsonResponse
    {
        $request->validate(['user_id' => 'required|integer', 'w9_required' => 'required|in:0,1']);

        // Client rule: Hello agents (the only ones a W-9 applies to) are handled by ADMIN only.
        if ($deny = EmployeeReviewController::denyIfHelloAndNotAdmin($request->user_id, false)) {
            return $deny;
        }

        $user = \App\User::findOrFail($request->user_id);
        if (method_exists($user, 'isCrazyrays') && $user->isCrazyrays()) {
            return response()->json(['success' => false, 'message' => 'W-9 applies to Hello Transport agents only.'], 422);
        }

        DB::table('user')->where('id', $user->id)->update(['w9_required' => (int) $request->w9_required]);

        if ((int) $request->w9_required === 1 && $user->email) {
            try {
                \Illuminate\Support\Facades\Mail::to($user->email)->send(
                    new \App\Mail\AgentActionRequiredMail($user->name, \App\Support\Brand::for($user), 'w9')
                );
            } catch (\Throwable $e) {
                Log::warning('requireW9: notify email failed', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            }
        }

        return response()->json(['success' => true, 'w9_required' => (int) $request->w9_required]);
    }

    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Not authenticated.'], 401);
        }

        $validated    = $request->validate(self::rules(), self::messages());
        $hrEmployeeId = DB::table('hr_employees')->where('agent_id', $user->id)->value('id');

        if ($reject = self::rejectSubmission($validated, $user->id, $hrEmployeeId)) {
            return $reject;
        }

        self::saveSubmission($validated, $user, $hrEmployeeId, $request->ip());

        return response()->json(['success' => true, 'message' => 'Your W-9 has been submitted.']);
    }

    /** Admin download — regenerates from the database if the stored file is missing. */
    public function download(int $userId)
    {
        // Filed from the HR portal before the account existed: found through the HR record.
        $hrEmployeeId = DB::table('hr_employees')->where('agent_id', $userId)->value('id');
        $form = W9Form::where(function ($q) use ($userId, $hrEmployeeId) {
            $q->where('user_id', $userId);
            if ($hrEmployeeId) { $q->orWhere('hr_employee_id', $hrEmployeeId); }
        })->latest('id')->first();
        if (!$form) {
            abort(404, 'No W-9 on file for this agent.');
        }

        $filename = 'W9_' . $userId . '.pdf';

        if ($form->document_path) {
            foreach ([public_path($form->document_path), storage_path('app/public/' . $form->document_path)] as $full) {
                if ($full && is_file($full)) {
                    return response()->download($full, $filename, ['Content-Type' => 'application/pdf']);
                }
            }
        }

        try {
            $user = \App\User::find($userId);
            $pdf  = self::renderPdf($form, $user);

            return response($pdf, 200, [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        } catch (\Throwable $e) {
            Log::error('W9 on-the-fly PDF failed', ['user_id' => $userId, 'error' => $e->getMessage()]);
            abort(404, 'W-9 document could not be produced.');
        }
    }

    /* ───────────────────────── helpers ───────────────────────── */

    private static function renderPdf(W9Form $form, $user): string
    {
        $html = view('w9.pdf', [
            'form'  => $form,
            'brand' => \App\Support\Brand::for($user),
            'tin'   => $form->tin(),
        ])->render();

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    private static function buildPdf(W9Form $form, $user): string
    {
        $dir  = 'Uploads/w9_forms';
        $full = public_path($dir);
        if (!file_exists($full)) {
            mkdir($full, 0755, true);
        }

        $owner   = $form->user_id ?: ('hr' . $form->hr_employee_id);
        $relPath = $dir . '/w9_' . $owner . '_' . now()->format('YmdHis') . '.pdf';
        file_put_contents(public_path($relPath), self::renderPdf($form, $user));

        return $relPath;
    }
}
