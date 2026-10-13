<?php

namespace App\Http\Controllers;

use App\CrApplication;
use App\Mail\WelcomeEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

/**
 * Hello Transport — "Apply for an Opportunity" (W-03 / W-04).
 *
 * Replaces PublicSignupController for the Hello public site. The difference that matters:
 * signing up no longer creates a CRM account or an HR profile. It records an APPLICATION.
 *
 *   application  ->  admin reviews in HR (Campaign Leads)
 *                ->  shortlist creates the HR profile
 *                ->  onboarding completes
 *                ->  CRM account created last
 *
 * PublicSignupController is deliberately left in place, unrouted, until this is proven.
 */
class HelloApplicationController extends Controller
{
    public function showForm()
    {
        // The CrazyRays portal recruits through crazyrayssolutions.com.pk, not here.
        if (config('brands.force') === 'crazyrays' || config('app.is_agent_portal')) {
            $crBase = rtrim(config('bridge.crazyrays.base_url', 'https://crazyrayssolutions.com.pk'), '/');

            return redirect()->away($crBase ?: 'https://crazyrayssolutions.com.pk');
        }

        return view('auth.register');
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'           => ['required', 'string', 'max:50', 'regex:/^[A-Za-z][A-Za-z\s.\'\-]*$/'],
            'middle_name'    => ['nullable', 'string', 'max:50', 'regex:/^[A-Za-z][A-Za-z\s.\'\-]*$/'],
            'last_name'      => ['nullable', 'string', 'max:50', 'regex:/^[A-Za-z][A-Za-z\s.\'\-]*$/'],
            // An email may not already hold an account OR an open application.
            'email'          => 'required|email|max:50|unique:user,email',
            'password'       => 'required|string|min:8|confirmed',
            'phone'          => ['required', 'string', 'max:20', 'regex:/^(\+1\s?)?\d{10}$/'],
            'address'        => 'required|string|max:255',
            'signup_type'    => 'required|in:agent',
            'experience'     => 'nullable|string|max:2000',
            'shift_type_id'  => 'required|integer|min:1',
            'account_type_id' => 'required|integer|in:1,2,3',
            'father_name'    => ['nullable', 'string', 'max:100', 'regex:/^[A-Za-z][A-Za-z\s.\'\-]*$/'],
            'dob'            => 'required|date|before_or_equal:' . \Carbon\Carbon::now()->subYears(18)->format('Y-m-d'),
            'gender'         => 'nullable|in:male,female,other',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'cnic'           => 'required|string|max:20',
            'city'           => ['required', 'string', 'max:100', 'regex:/^[A-Za-z][A-Za-z\s.\'\-]*$/'],
            'state'          => ['required', 'string', 'max:100', 'regex:/^[A-Za-z][A-Za-z\s.\'\-]*$/'],
            'country'        => 'required|string|max:100',
            'mother_name'    => ['nullable', 'string', 'max:100', 'regex:/^[A-Za-z][A-Za-z\s.\'\-]*$/'],
            'zip'            => ['required', 'string', 'max:20', 'regex:/^\d{5}(-\d{4})?$/'],
            'timezone'       => 'required|string|max:64|timezone',
            'terms_accepted' => 'accepted',

            // W-03 additions. Optional so the existing form keeps working before it is rebuilt.
            'us_experience'  => 'nullable|in:yes,no',
            'us_hours'       => 'nullable|in:yes,no,flexible',
            'linkedin_url'   => 'nullable|url|max:255',
            'resume'         => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ], [
            'dob.required'            => 'Date of birth is required.',
            'dob.before_or_equal'     => 'You must be at least 18 years old to apply.',
            'cnic.required'           => 'ID number is required.',
            'zip.required'            => 'Zip code is required.',
            'zip.regex'               => 'Enter a valid zip code (12345 or 12345-6789).',
            'phone.regex'             => 'Enter a 10-digit US phone number.',
            'timezone.required'       => 'Please choose your timezone.',
            'timezone.timezone'       => 'Please choose a valid timezone.',
            'terms_accepted.accepted' => 'You must accept the Terms & Conditions to continue.',
            'resume.mimes'            => 'Upload your CV as a PDF or Word document.',
            'resume.max'              => 'Your CV must be under 5 MB.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // A second open application from the same address is a duplicate, not a new lead.
        $openApplication = CrApplication::where('email', $request->email)
            ->where('brand', CrApplication::BRAND_HELLO)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($openApplication) {
            return response()->json([
                'errors' => ['email' => ['We already have an application from this email address.']],
            ], 422);
        }

        try {
            $resumePath = null;
            if ($request->hasFile('resume')) {
                $resumePath = $request->file('resume')->store('applications/resumes', 'public');
            }

            $application = CrApplication::create([
                'brand'          => CrApplication::BRAND_HELLO,

                'full_name'      => trim($request->name . ' ' . (string) $request->middle_name . ' ' . (string) $request->last_name),
                'father_name'    => $request->father_name,
                // State/National ID reuses the column CrazyRays already stores a CNIC in.
                'national_id'    => $request->cnic,
                'dob'            => $request->dob,
                'gender'         => $request->gender,
                'marital_status' => $request->marital_status,

                'email'          => $request->email,
                'phone'          => $request->phone,
                'country'        => $request->country,
                'city'           => $request->city,
                'state'          => $request->state,
                'address'        => $request->address,
                'time_zone'      => $request->input('timezone'),

                // Item 12: record where the application was submitted from.
                'ip_address'     => $request->ip(),

                'additional_info' => $request->experience,
                'us_experience'  => $request->input('us_experience'),
                'us_hours'       => $request->input('us_hours'),
                'linkedin_url'   => $request->input('linkedin_url'),
                'resume_path'    => $resumePath,

                // Held so the CRM account can be created with the applicant's own password
                // at the end of onboarding, rather than emailing them a generated one.
                'password'       => Hash::make($request->password),

                'status'         => 'pending',
                'contract_accepted_at' => $request->boolean('terms_accepted') ? now() : null,
            ]);
        } catch (\Throwable $e) {
            Log::error('HelloApplicationController: could not record application', [
                'email' => $request->email,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['message' => 'We could not submit your application. Please try again.'], 500);
        }

        // Trigger 1 of the six pipeline emails. Retimed from account-creation to
        // application-submitted — same mailable, same template, new moment.
        try {
            Mail::to($application->email)->send(new WelcomeEmail($application->full_name, $application->email));
        } catch (\Throwable $e) {
            // An email failure must never lose the application.
            Log::warning('HelloApplicationController: confirmation email failed', [
                'application_id' => $application->id,
                'error'          => $e->getMessage(),
            ]);
        }

        Log::info('HelloApplicationController: application received', [
            'application_id' => $application->id,
            'email'          => $application->email,
        ]);

        return response()->json([
            'success'     => true,
            'application' => true,
            'message'     => 'Application submitted. Your application has been received and is now under review. '
                           . 'A confirmation email has been sent to ' . $application->email . '. '
                           . 'Our recruitment team will contact you if your profile matches an available opportunity.',
        ]);
    }
}
