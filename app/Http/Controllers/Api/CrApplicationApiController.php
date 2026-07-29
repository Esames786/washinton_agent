<?php

namespace App\Http\Controllers\Api;

use App\CrApplication;
use App\Http\Controllers\Controller;
use App\Mail\CrApplicationConfirmationMail;
use App\Mail\CrApplicationReceivedMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CrApplicationApiController extends Controller
{
    private const CR_ADMIN_EMAIL = 'careers@crazyrayssolutions.com.pk';

    private const VALID_CAMPAIGNS = [
        'healthcare', 'home_security', 'real_estate', 'dme', 'logistics', 'software', 'amazon', 'inhouse', 'general',
    ];

    public function store(Request $request): JsonResponse
    {
        $campaign = $request->input('campaign');

        // #13: applicants must be at least 18 years old (dob on/before this date).
        $maxDob = \Carbon\Carbon::now()->subYears(18)->format('Y-m-d');

        $validator = Validator::make($request->all(), [
            // Names: alphabets (and spaces) only, up to 30 characters.
            'full_name'            => ['required', 'string', 'max:30', 'regex:/^[A-Za-z ]+$/'],
            // #5: these are now REQUIRED on the CrazyRays application.
            'father_name'          => ['required', 'string', 'max:30', 'regex:/^[A-Za-z ]+$/'],
            'national_id'          => ['nullable', 'string', 'max:50'],
            'dob'                  => ['required', 'date', 'before_or_equal:' . $maxDob],
            'gender'               => ['required', 'in:male,female,other'],
            'marital_status'       => ['required', 'in:single,married,divorced,widowed'],
            'email'                => [
                'required', 'email', 'max:150',
                Rule::unique('cr_applications')->where(fn ($q) => $q->where('campaign', $campaign)),
            ],
            'phone'                => ['required', 'string', 'max:30'],
            'country'              => ['nullable', 'string', 'max:100'],
            'city'                 => ['required', 'string', 'max:100'],
            'state'                => ['required', 'string', 'max:100'],
            'address'              => ['required', 'string', 'max:255'],
            // Employment-split: employment_type + a valid, active campaign of that category.
            'employment_type'      => ['required', 'in:work_from_home,in_house'],
            'campaign_id'          => ['required', 'integer', 'exists:cr_campaigns,id'],
            'campaign'             => ['nullable', 'string', 'max:60'],
            // "Work From Home" is an employment type, never a shift.
            'shift_type'           => ['required', 'string', 'max:100', 'not_in:Work From Home'],
            'pay_type'             => ['required', 'in:salary_only,commission_only,salary_and_commission'],
            'additional_info'      => ['nullable', 'string'],
            'campaign_experience'  => ['nullable', 'string'],
            'contract_accepted_at' => ['nullable', 'date'],
            'password'             => ['nullable', 'string', 'min:8'],
            'resume'               => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'documents'            => ['nullable', 'array'],
            'documents.*.doc_id'   => ['required_with:documents', 'integer'],
            'documents.*.title'    => ['required_with:documents', 'string'],
        ], [
            'dob.required'         => 'Date of birth is required.',
            'dob.before_or_equal'  => 'You must be at least 18 years old to apply.',
            'shift_type.not_in'    => 'Work From Home is an employment type, not a shift.',
        ]);

        // Cross-field rules: campaign category must match employment_type, and pay
        // type must be valid for the employment type (prevents API manipulation).
        $validator->after(function ($v) use ($request) {
            $campaignRow = \App\CrCampaign::find($request->input('campaign_id'));
            $empType     = $request->input('employment_type');

            if ($campaignRow && $empType && $campaignRow->employment_category !== $empType) {
                $v->errors()->add('campaign_id', 'Selected campaign does not match the chosen employment type.');
            }
            if ($campaignRow && !$campaignRow->status) {
                $v->errors()->add('campaign_id', 'Selected campaign is not active.');
            }
            if ($empType === 'work_from_home' && $request->input('pay_type') !== 'commission_only') {
                $v->errors()->add('pay_type', 'Work From Home applicants are Commission Only.');
            }

            // #1: enforce the 300-word cap on campaign experience server-side too.
            $exp = trim((string) $request->input('campaign_experience', ''));
            if ($exp !== '' && str_word_count($exp) > 300) {
                $v->errors()->add('campaign_experience', 'Relevant experience must be 300 words or fewer.');
            }
        });

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Resolve the campaign key from the id (keeps the legacy string column consistent).
        $campaignRow = \App\CrCampaign::find($request->input('campaign_id'));
        $campaign    = $campaignRow->key ?? $campaign;

        try {
        // Store resume
        $resumePath = null;
        if ($request->hasFile('resume') && $request->file('resume')->isValid()) {
            $resumePath = $request->file('resume')->store('cr_resumes', 'public');
        }

        // Store document files
        $documents = [];
        if ($request->hasFile('document_files')) {
            foreach ($request->file('document_files') as $docId => $file) {
                if ($file->isValid()) {
                    $path    = $file->store('cr_documents', 'public');
                    $docMeta = collect($request->input('documents', []))->firstWhere('doc_id', (int) $docId);

                    // The crazyrays proxy doesn't forward the document title metadata, so
                    // resolve the human title from hr_document_settings (shared DB) by id.
                    // Fall back to any forwarded title, then a generic label.
                    $setting = \Illuminate\Support\Facades\DB::table('hr_document_settings')
                        ->where('id', (int) $docId)
                        ->first(['title', 'is_required']);

                    $documents[] = [
                        'doc_id'      => (int) $docId,
                        'title'       => $setting->title ?? ($docMeta['title'] ?? 'Document'),
                        'path'        => $path,
                        'filename'    => $file->getClientOriginalName(),
                        'is_required' => $setting->is_required ?? ($docMeta['is_required'] ?? false),
                    ];
                }
            }
        }

        $application = CrApplication::create([
            'full_name'            => $request->full_name,
            'father_name'          => $request->father_name,
            'national_id'          => $request->national_id,
            'dob'                  => $request->dob,
            'gender'               => $request->gender,
            'marital_status'       => $request->marital_status,
            'email'                => $request->email,
            'phone'                => $request->phone,
            'country'              => $request->country,
            'city'                 => $request->city,
            'state'                => $request->state,
            'address'              => $request->address,
            'campaign'             => $campaign,
            'employment_type'      => $request->employment_type,
            'campaign_id'          => $request->campaign_id,
            'shift_type'           => $request->shift_type,
            'pay_type'             => $request->pay_type,
            'additional_info'      => $request->additional_info,
            'campaign_experience'  => $request->campaign_experience,
            'resume_path'          => $resumePath,
            'documents'            => $documents ?: null,
            'contract_accepted_at' => $request->contract_accepted_at,
            'password'             => $request->password ? Hash::make($request->password) : null,
            'status'               => 'pending',
        ]);

        // Confirmation email to applicant (non-blocking).
        // When the CrazyRays site handles this itself (it sends from its own crazyrayssolutions.com.pk
        // mail engine, so Gmail doesn't show "via hellotransport.com"), it passes client_confirmation=1
        // and we skip it here to avoid a duplicate email.
        if (! $request->boolean('client_confirmation')) {
            try {
                Mail::to($application->email, $application->full_name)
                    ->send(new CrApplicationConfirmationMail($application));
            } catch (\Throwable $e) {
                Log::warning('CrApplicationApiController: applicant confirmation email failed', [
                    'application_id' => $application->id,
                    'email'          => $application->email,
                    'error'          => $e->getMessage(),
                ]);
            }
        }

        // Notify CrazyRays admin (non-blocking)
        try {
            Mail::to(self::CR_ADMIN_EMAIL)->send(new CrApplicationReceivedMail($application));
        } catch (\Throwable $e) {
            Log::warning('CrApplicationApiController: admin notification email failed', [
                'application_id' => $application->id,
                'error'          => $e->getMessage(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Application received successfully. You will be contacted by CrazyRays Solutions after review.',
            'id'      => $application->id,
            // Data the CrazyRays site uses to send the confirmation email from its own mail engine.
            'applicant' => [
                'full_name'      => $application->full_name,
                'email'          => $application->email,
                'campaign_label' => $application->campaign_label,
            ],
        ], 201);
        } catch (\Throwable $e) {
            // #11: never let this endpoint return a raw 500 HTML page — the CrazyRays apply form
            // only shows a generic "Submission failed" for that. Return the real reason as JSON
            // (and log it) so the actual cause surfaces to the applicant and to us.
            Log::error('CrApplicationApiController@store failed', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Could not save your application. ' . $e->getMessage(),
            ], 500);
        }
    }

    public function contactNotify(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:150'],
            'phone'    => ['nullable', 'string', 'max:30'],
            'position' => ['nullable', 'string', 'max:100'],
            'body'     => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $name     = $request->input('name');
        $email    = $request->input('email');
        $phone    = $request->input('phone') ?: '—';
        $position = $request->input('position') ?: 'General';
        $body     = $request->input('body', '');

        $subject = "Career Enquiry: {$position} — {$name}";
        $text    = implode("\n", [
            'New career enquiry from CrazyRays website',
            str_repeat('─', 40),
            "Name:     {$name}",
            "Email:    {$email}",
            "Phone:    {$phone}",
            "Position: {$position}",
            str_repeat('─', 40),
            $body,
        ]);

        try {
            Mail::raw($text, function ($msg) use ($subject, $email, $name) {
                $msg->to(self::CR_ADMIN_EMAIL)
                    ->replyTo($email, $name)
                    ->subject($subject);
            });

            return response()->json(['success' => true, 'message' => "Your message has been sent! We'll be in touch soon."]);
        } catch (\Throwable $e) {
            Log::warning('CrApplicationApiController: contact notification email failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to send email. Please contact us directly.'], 500);
        }
    }

    private function assertBridgeKey(Request $request): void
    {
        $configuredKey = (string) config('bridge.shared_key');
        $incomingKey   = (string) $request->header('X-Bridge-Key', '');

        abort_unless(
            !blank($configuredKey) && hash_equals($configuredKey, $incomingKey),
            response()->json(['message' => 'Unauthorized.'], 401)
        );
    }
}
