<?php

namespace App\Http\Controllers;

use App\CrApplication;
use App\Mail\HelloApplicationReceivedEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

        return view('auth.apply');
    }

    public function store(Request $request): JsonResponse
    {
        // W-03: only what is needed to decide whether to talk to someone. ID number, date of
        // birth, family details, address and documents are gathered during HR onboarding,
        // once the applicant has been shortlisted.
        $validator = Validator::make($request->all(), [
            'name'           => ['required', 'string', 'max:50', 'regex:/^[A-Za-z][A-Za-z\s.\'\-]*$/'],
            'last_name'      => ['required', 'string', 'max:50', 'regex:/^[A-Za-z][A-Za-z\s.\'\-]*$/'],
            'email'          => 'required|email|max:50|unique:user,email',

            // Agents are worldwide, so the number is validated by shape, not a US pattern.
            // The form submits it as "<dial> <digits>", e.g. "+92 3001234567".
            'phone'          => ['required', 'string', 'max:25', 'regex:/^\+\d{1,4}\s?\d{6,15}$/'],

            'country'        => 'required|string|max:100',
            'city'           => ['required', 'string', 'max:100', 'regex:/^[A-Za-z][A-Za-z\s.\'\-]*$/'],
            'timezone'       => 'required|string|max:64|timezone',

            'us_experience'  => 'required|in:yes,no',
            'us_hours'       => 'required|in:yes,no,flexible',

            'experience'     => 'nullable|string|max:2000',
            'linkedin_url'   => 'nullable|url|max:255',
            'resume'         => 'nullable|file|mimes:pdf,doc,docx|max:5120',

            'terms_accepted' => 'accepted',
        ], [
            'name.required'           => 'First name is required.',
            'last_name.required'      => 'Last name is required.',
            'phone.regex'             => 'Enter a valid phone number for the country you selected.',
            'city.regex'              => 'Enter a valid city name.',
            'timezone.required'       => 'Please choose your timezone.',
            'timezone.timezone'       => 'Please choose a valid timezone.',
            'us_experience.required'  => 'Please tell us whether you have worked with U.S. customers.',
            'us_hours.required'       => 'Please tell us whether you can work U.S. hours.',
            'linkedin_url.url'        => 'Enter the full LinkedIn URL, starting with https://',
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
                'brand'     => CrApplication::BRAND_HELLO,

                'full_name' => trim($request->name . ' ' . (string) $request->last_name),
                'email'     => $request->email,
                'phone'     => $request->phone,
                'country'   => $request->country,
                'city'      => $request->city,
                'time_zone' => $request->input('timezone'),

                // Item 12: record where the application was submitted from.
                'ip_address' => $request->ip(),

                'additional_info' => $request->experience,
                'us_experience'   => $request->input('us_experience'),
                'us_hours'        => $request->input('us_hours'),
                'linkedin_url'    => $request->input('linkedin_url'),
                'resume_path'     => $resumePath,

                // No password is asked for at application time. One is set when the applicant
                // is shortlisted and receives their HR portal link, so a password is never
                // emailed and never stored before it is needed.
                'password' => null,

                'status'               => 'pending',
                'contract_accepted_at' => $request->boolean('terms_accepted') ? now() : null,
            ]);
        } catch (\Throwable $e) {
            Log::error('HelloApplicationController: could not record application', [
                'email' => $request->email,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['message' => 'We could not submit your application. Please try again.'], 500);
        }

        // Trigger 1 of the six pipeline emails: "application received". Its own email, not the
        // old WelcomeEmail, which promised an account and a login button (client, 16 Oct 2026).
        try {
            Mail::to($application->email)->send(new HelloApplicationReceivedEmail((string) $application->full_name));
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

        // Wording is the client's, verbatim from the 12 Oct email.
        return response()->json([
            'success'     => true,
            'application' => true,
            'message'     => 'Thank you for applying to join our team. '
                           . 'Your application has been successfully received and is now under review. '
                           . 'A confirmation email has been sent to ' . $application->email . '. '
                           . 'Our recruitment team will review your application and contact you if your '
                           . 'profile matches an available opportunity.',
        ]);
    }
}
