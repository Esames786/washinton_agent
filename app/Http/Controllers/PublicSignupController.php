<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeEmail;
use App\role;
use App\User;
use App\user_setting;
use App\Services\HrPortalBridgeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PublicSignupController extends Controller
{
    /**
     * Reference user IDs for permission copying.
     * Agent (Order Taker) → user id 130
     * Carrier (Dispatcher) → user id 53
     */
    private const AGENT_REFERENCE_USER_ID   = 130;
    private const CARRIER_REFERENCE_USER_ID = 53;

    private const PERMISSION_COLUMNS = [
        'emp_access_phone', 'emp_access_web', 'emp_access_test',
        'panel_type_4', 'panel_type_5', 'panel_type_6',
        'emp_panel_access', 'emp_show_data', 'emp_access_ship',
        'emp_access_profile', 'emp_access_action', 'emp_access_report',
        'emp_access_guide', 'order_taker_quote', 'assign_daily_qoute',
        'sheet_access',
    ];

    public function __construct(protected HrPortalBridgeService $hrBridge) {}

    public function showForm()
    {
        // The CrazyRays portal (florida) has no self-service signup of its own — CrazyRays
        // recruiting happens on crazyrayssolutions.com.pk, so send those visitors there.
        // Hello Transport serves its OWN signup form again (its agents are created directly
        // in the shared `user` table from here).
        if (config('brands.force') === 'crazyrays' || config('app.is_agent_portal')) {
            $crBase = rtrim(config('bridge.crazyrays.base_url', 'https://crazyrayssolutions.com.pk'), '/');
            return redirect()->away($crBase ?: 'https://crazyrayssolutions.com.pk');
        }

        return view('auth.register');
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'           => 'required|string|max:50',
            'last_name'      => 'required|string|max:50',
            'slug'           => 'required|string|max:50|unique:user,slug',
            'email'          => 'required|email|max:50|unique:user,email',
            'password'       => 'required|string|min:8|confirmed',
            'phone'          => 'required|string|max:50',
            'address'        => 'required|string|max:255',
            'signup_type'    => 'required|in:agent,carrier',
            'shift_type_id'  => 'required|integer|min:1',
            'account_type_id'=> 'required|integer|in:1,2,3',
            'father_name'    => 'nullable|string|max:100',
            // #13: must be at least 18 years old
            'dob'            => 'required|date|before_or_equal:' . \Carbon\Carbon::now()->subYears(18)->format('Y-m-d'),
            'gender'         => 'nullable|in:male,female,other',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            // State ID (US) reuses the existing `cnic` column — no new column for it.
            'cnic'           => 'required|string|max:20',
            'city'           => 'required|string|max:100',
            'state'          => 'required|string|max:100',
            'country'        => 'required|string|max:100',
            // Hello onboarding additions — mirrored by the front-end validator in the form.
            'mother_name'    => 'nullable|string|max:100',
            'zip'            => ['required', 'string', 'max:20', 'regex:/^\d{5}(-\d{4})?$/'],
            'timezone'       => 'required|string|max:64|timezone',
            'terms_accepted' => 'accepted',
        ], [
            'dob.required'         => 'Date of birth is required.',
            'dob.before_or_equal'  => 'You must be at least 18 years old to sign up.',
            'cnic.required'        => 'State ID is required.',
            'zip.required'         => 'Zip code is required.',
            'zip.regex'            => 'Enter a valid zip code (12345 or 12345-6789).',
            'timezone.required'    => 'Please choose your timezone.',
            'timezone.timezone'    => 'Please choose a valid timezone.',
            'terms_accepted.accepted' => 'You must accept the Terms & Conditions to continue.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Resolve role
        $roleName = $request->signup_type === 'agent' ? 'Order Taker' : 'Dispatcher';
        $role     = role::where('name', $roleName)->first();

        if (!$role) {
            Log::error("PublicSignupController: role '{$roleName}' not found.");
            return response()->json(['message' => 'Registration is temporarily unavailable. Please contact support.'], 500);
        }

        $referenceUserId = $request->signup_type === 'agent'
            ? self::AGENT_REFERENCE_USER_ID
            : self::CARRIER_REFERENCE_USER_ID;

        $referenceUser = User::find($referenceUserId);

        if (!$referenceUser) {
            Log::error("PublicSignupController: reference user id={$referenceUserId} not found.");
            return response()->json(['message' => 'Registration is temporarily unavailable. Please contact support.'], 500);
        }

        DB::beginTransaction();
        try {
            $user = new User();
            $user->name      = $request->name;
            $user->last_name = $request->last_name;
            $user->slug      = $request->slug;
            $user->email     = $request->email;
            $user->password  = Hash::make($request->password);
            $user->phone     = $request->phone;
            $user->address   = $request->address;
            $user->role      = $role->id;
            $user->status    = 0;
            $user->verify    = 1;

            // Parity with CrApplicationController@approve, which sets is_crazyrays = 1 for
            // CrazyRays campaign hires. This form is the HELLO signup, so the person is
            // explicitly a Hello agent — that flag drives their branding, documents and email.
            $user->is_crazyrays = 0;

            // Per-person timezone (drives their attendance/check-in and displayed times).
            // Falls back to the app default so behaviour is unchanged when not supplied.
            if (\Illuminate\Support\Facades\Schema::hasColumn('user', 'timezone')) {
                $user->timezone = $request->input('timezone') ?: config('app.timezone', 'Asia/Karachi');
            }

            // ── OLD panel logic (kept for record; replaced by #4 no-access default) ──
            // $roleKey = $request->signup_type === 'agent' ? 'order_taker' : 'dispatcher';
            // \App\Support\SignupProvisioner::applyDefaults($user, $roleKey, self::PERMISSION_COLUMNS, $referenceUser);
            // $penal_type = $request->signup_type === 'agent'
            //     ? 1
            //     : ($this->getReferenceUserPanelType($referenceUserId) ?? 1);
            // $cityPanel = \App\Support\SignupProvisioner::resolveCityPanelId($request->input('city'), $request->ip());
            // if ($cityPanel !== null) {
            //     $penal_type = $cityPanel;
            //     \App\Support\SignupProvisioner::grantCityPanel($user, $cityPanel);
            // }

            // #4: new signups get NO access until an admin assigns it — empty permissions + "No Access" panel.
            \App\Support\SignupProvisioner::applyNoAccess($user, self::PERMISSION_COLUMNS);
            $user->order_taker_quote = 1;
            $penal_type = \App\Support\SignupProvisioner::noAccessPanelId() ?? 1;

            $user->save();

            // #18/#5 (2026-07-24): default New->Delivered folder access REMOVED — new agents
            // now start with ZERO access until an admin explicitly grants it.
            // $user->applyDefaultFolderAccess();

            $setting             = new user_setting();
            $setting->user_id    = $user->id;
            $setting->penal_type = $penal_type;
            $setting->call_type  = 134;
            $setting->save();

            // ── #4: extra panel grants (2 & 4) removed for no-access signups (kept for record) ──
            // if ($penal_type !== 2) {
            //     $promax             = new user_setting();
            //     $promax->user_id    = $user->id;
            //     $promax->penal_type = 2;
            //     $promax->call_type  = 134;
            //     $promax->save();
            // }
            //
            // if ($penal_type !== 4) {
            //     $website             = new user_setting();
            //     $website->user_id    = $user->id;
            //     $website->penal_type = 4;
            //     $website->call_type  = 134;
            //     $website->save();
            // }

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('PublicSignupController store failed: ' . $e->getMessage());
            return response()->json(['message' => 'Registration failed. Please try again.'], 500);
        }

        // ── Welcome email (non-blocking) ──
        try {
            Mail::to($user->email)->send(new WelcomeEmail($user->name, $user->email));
        } catch (\Throwable $e) {
            Log::warning('PublicSignupController: welcome email failed', ['error' => $e->getMessage()]);
        }

        // ── Mirror to HR portal (non-blocking) ──
        try {
            $this->hrBridge->createEmployee([
                'name'            => $request->name . ' ' . $request->last_name,
                'email'           => $request->email,
                'password'        => $request->password,
                'phone'           => $request->phone,
                'address'         => $request->address,
                'user_type'       => $request->signup_type,
                'agent_id'        => $user->id,
                'shift_type_id'   => (int) $request->shift_type_id,
                'account_type_id' => (int) $request->account_type_id,
                'father_name'     => $request->father_name,
                'mother_name'     => $request->mother_name,
                'dob'             => $request->dob,
                'gender'          => $request->gender,
                'marital_status'  => $request->marital_status,
                // State ID is stored in the existing `cnic` column.
                'cnic'            => $request->cnic,
                'city'            => $request->city,
                'state'           => $request->state,
                'zip'             => $request->zip,
                'country'         => $request->country,
                'timezone'        => $request->input('timezone') ?: config('app.timezone', 'Asia/Karachi'),
                // Parity with the CrazyRays approve flow: recording acceptance here stops the
                // blocking contract modal from greeting the agent on their very first login.
                'contract_accepted_at' => $request->input('terms_accepted') ? now()->toDateTimeString() : null,
            ]);
        } catch (\Throwable $e) {
            Log::warning('PublicSignupController: HR portal createEmployee failed', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Account created successfully! Your account is pending admin approval. A welcome email has been sent to ' . $user->email . '.',
        ]);
    }

    private function getReferenceUserPanelType(int $userId): ?int
    {
        $setting = user_setting::where('user_id', $userId)->first();
        return $setting ? (int) $setting->penal_type : null;
    }
}
