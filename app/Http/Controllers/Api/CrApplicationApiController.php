<?php

namespace App\Http\Controllers\Api;

use App\CrApplication;
use App\Http\Controllers\Controller;
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
        'healthcare', 'home_security', 'real_estate', 'dme', 'logistics', 'software', 'amazon', 'general',
    ];

    public function store(Request $request): JsonResponse
    {
        $campaign = $request->input('campaign');

        $validator = Validator::make($request->all(), [
            'full_name'            => ['required', 'string', 'max:100'],
            'father_name'          => ['nullable', 'string', 'max:100'],
            'national_id'          => ['nullable', 'string', 'max:50'],
            'dob'                  => ['nullable', 'date'],
            'gender'               => ['nullable', 'in:male,female,other'],
            'marital_status'       => ['nullable', 'in:single,married,divorced,widowed'],
            'email'                => [
                'required', 'email', 'max:150',
                Rule::unique('cr_applications')->where(fn ($q) => $q->where('campaign', $campaign)),
            ],
            'phone'                => ['required', 'string', 'max:30'],
            'country'              => ['nullable', 'string', 'max:100'],
            'city'                 => ['nullable', 'string', 'max:100'],
            'state'                => ['nullable', 'string', 'max:100'],
            'address'              => ['nullable', 'string', 'max:255'],
            'campaign'             => ['required', 'in:' . implode(',', self::VALID_CAMPAIGNS)],
            'shift_type'           => ['nullable', 'string', 'max:100'],
            'pay_type'             => ['nullable', 'string', 'max:50'],
            'additional_info'      => ['nullable', 'string'],
            'campaign_experience'  => ['nullable', 'string'],
            'contract_accepted_at' => ['nullable', 'date'],
            'password'             => ['nullable', 'string', 'min:8'],
            'resume'               => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'documents'            => ['nullable', 'array'],
            'documents.*.doc_id'   => ['required_with:documents', 'integer'],
            'documents.*.title'    => ['required_with:documents', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

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
                    $path      = $file->store('cr_documents', 'public');
                    $docMeta   = collect($request->input('documents', []))->firstWhere('doc_id', (int) $docId);
                    $documents[] = [
                        'doc_id'      => (int) $docId,
                        'title'       => $docMeta['title'] ?? 'Document',
                        'path'        => $path,
                        'filename'    => $file->getClientOriginalName(),
                        'is_required' => $docMeta['is_required'] ?? false,
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
            'campaign'             => $request->campaign,
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
        ], 201);
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
