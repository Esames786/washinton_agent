<?php

namespace App\Http\Controllers\Bridge;

use App\Http\Controllers\Controller;
use App\role;
use App\User;
use App\user_setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\DailyQoute;
use App\Mail\SendCodeMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

/**
 * Bridge endpoints called by crazyrayssolutions.com.pk (and any other
 * external portal) to register and log users into washinton_agent.
 *
 * Replaces the old daydispatchagent BridgeAuthController.
 * Writes to Washington's `user` table instead of `authorized_users`.
 *
 * Auth: X-Bridge-Key header must match config('bridge.washington.shared_key')
 */
class BridgeAuthController extends Controller
{
    /**
     * Reference user IDs for permission copying (same as PublicSignupController).
     * Agent / Order Taker → user id 130
     * Carrier / Dispatcher → user id 53
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

    // -------------------------------------------------------------------------
    // POST /bridge/register
    // -------------------------------------------------------------------------
    public function register(Request $request): JsonResponse
    {
        $this->assertBridgeKey($request);

        // Accept both new simple fields AND old daydispatch field names from crazyrays
        // crazyrays sends: Company_Email, Company_Password, name, Contact_Phone,
        //                  Company_Address, User_Type, Company_Country, Other_Country
        $validator = Validator::make($request->all(), [
            // New simple fields
            'name'         => ['required_without:Company_Email', 'string', 'max:50'],
            'email'        => ['required_without:Company_Email', 'email', 'max:50'],
            'password'     => ['required_without:Company_Password', 'string', 'min:8'],
            'phone'        => ['nullable', 'string', 'max:50'],
            'address'      => ['nullable', 'string', 'max:255'],
            // Old daydispatch fields (crazyrays)
            'Company_Email'    => ['required_without:email', 'email', 'max:150'],
            'Company_Password' => ['required_without:password', 'string', 'min:8'],
            'signup_type'      => ['nullable', 'string'],
            'User_Type'        => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Normalise fields — support both old and new naming
        $email    = $request->input('Company_Email') ?? $request->input('email');
        $password = $request->input('Company_Password') ?? $request->input('password');
        $name     = $request->input('name', $request->input('Company_Name', 'User'));
        $phone    = $request->input('Contact_Phone') ?? $request->input('phone', '');
        $address  = $request->input('Company_Address') ?? $request->input('address', '');
        $lastName = $request->input('last_name', '');
        $slug     = $request->input('slug', '');

        // Auto-generate slug if not provided (bridge calls from crazyrays won't send it)
        if (blank($slug)) {
            $base = \Illuminate\Support\Str::slug($name . ($lastName ? '-' . $lastName : ''));
            $slug = $base;
            $i = 1;
            while (\App\User::where('slug', $slug)->exists()) {
                $slug = $base . $i++;
            }
        }

        // Check email uniqueness after normalisation
        if (\App\User::where('email', $email)->exists()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors'  => ['email' => ['The email has already been taken.']],
            ], 422);
        }

        // Resolve signup type — accept both 'signup_type' and 'User_Type'
        $signupType = strtolower((string) ($request->input('signup_type') ?? $request->input('User_Type', 'agent')));
        [$roleId, $referenceUserId] = $this->resolveRoleAndReference($signupType);

        if (!$roleId) {
            return response()->json([
                'message' => "Unknown signup_type '{$signupType}'.",
            ], 422);
        }

        $referenceUser = User::find($referenceUserId);
        if (!$referenceUser) {
            Log::error("BridgeAuthController: reference user id={$referenceUserId} not found.");
            return response()->json(['message' => 'Server configuration error.'], 500);
        }

        DB::beginTransaction();
        try {
            $user = new User();
            $user->name     = $name;
            $user->email    = $email;
            $user->password = Hash::make($password);
            $user->phone    = $phone;
            $user->address  = $address;
            $user->role     = $roleId;
            $user->status   = 0; // Inactive until admin activates
            $user->verify   = 1; // Must be 1 so user appears in employee list
            $user->last_name = $lastName;
            $user->slug      = $slug;

            foreach (self::PERMISSION_COLUMNS as $col) {
                $user->$col = $referenceUser->$col;
            }
            $user->order_taker_quote = 1;

            $user->save();

            // user_settings
            $penal_type = ($referenceUserId === self::AGENT_REFERENCE_USER_ID)
                ? 1
                : ($this->getReferenceUserPanelType($referenceUserId) ?? 1);

            $setting             = new user_setting();
            $setting->user_id    = $user->id;
            $setting->penal_type = $penal_type;
            $setting->call_type  = 134; // Default Call App type for navbar
            $setting->save();

            DB::commit();

            // ── Mirror to HR portal (non-blocking) ───────────────────────────
            try {
                app(\App\Services\HrPortalBridgeService::class)->createEmployee([
                    'name'            => $name . ($lastName ? ' ' . $lastName : ''),
                    'email'           => $email,
                    'password'        => $password, // plain — HR portal hashes it
                    'phone'           => $phone,
                    'address'         => $address,
                    'user_type'       => $signupType,
                    'agent_id'        => $user->id,
                    'shift_type_id'   => (int) $request->input('shift_type_id', 1),
                    'account_type_id' => (int) $request->input('account_type_id', 3),
                ]);
            } catch (\Throwable $e) {
                Log::warning('BridgeAuthController: HR portal createEmployee failed', [
                    'user_id' => $user->id,
                    'email'   => $email,
                    'error'   => $e->getMessage(),
                ]);
            }

            return response()->json([
                'message' => 'Registration successful. Account is pending admin activation.',
                'user_id' => $user->id,
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('BridgeAuthController register failed: ' . $e->getMessage());
            return response()->json(['message' => 'Registration failed.', 'errors' => ['server' => [$e->getMessage()]]], 500);
        }
    }

    private const CR_PORTAL_URL = 'https://crazyrayssolutions.com.pk';

    // -------------------------------------------------------------------------
    // POST /bridge/login
    // -------------------------------------------------------------------------
    public function login(Request $request): JsonResponse
    {
        $this->assertBridgeKey($request);

        // Accept both new fields and old daydispatch field names from crazyrays
        $email    = $request->input('Company_Email') ?? $request->input('email');
        $password = $request->input('Company_Password') ?? $request->input('password');

        if (!$email || !$password) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors'  => ['email' => ['Email and password are required.']],
            ], 422);
        }

        // Only CrazyRays-registered users may log in via this bridge
        $isCrUser = \App\CrApplication::where('email', $email)->exists();
        if (!$isCrUser) {
            return response()->json([
                'message' => 'This account is not registered through CrazyRays Solutions. Please log in directly at hellotransport.com.',
            ], 403);
        }

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Invalid email or password.'], 401);
        }

        if ((int) $user->status === 0) {
            return response()->json(['message' => 'Your account is pending admin activation. You will be notified once it is active.'], 403);
        }

        // Set OTP code and send email — same as getlogin2()
        $user->code = 123456;
        $user->save();

        try {
            Mail::to(config('custom.SEND_MAIL'))
                ->cc([$user->email, config('custom.CODE_GIVER')])
                ->send(new SendCodeMail($user->name, $user->code));
        } catch (\Throwable $e) {
            Log::warning('BridgeAuthController: OTP email failed', ['user_id' => $user->id, 'error' => $e->getMessage()]);
        }

        // Return OTP token — SSO URL generated only after code is verified
        $token = Crypt::encryptString(json_encode([
            'user_id'   => $user->id,
            'issued_at' => now()->timestamp,
        ]));

        return response()->json([
            'status' => 'otp_required',
            'token'  => $token,
        ]);
    }

    // -------------------------------------------------------------------------
    // POST /api/bridge/verify-otp
    // -------------------------------------------------------------------------
    public function verifyOtp(Request $request): JsonResponse
    {
        $this->assertBridgeKey($request);

        $token = $request->input('token');
        $code  = $request->input('code');

        if (!$token || !$code) {
            return response()->json(['message' => 'Token and code are required.'], 422);
        }

        try {
            $payload = json_decode(Crypt::decryptString($token), true, 512, JSON_THROW_ON_ERROR);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Invalid or expired token.'], 422);
        }

        // Token expires after 10 minutes
        if (now()->timestamp - ($payload['issued_at'] ?? 0) > 600) {
            return response()->json(['message' => 'Verification code has expired. Please log in again.'], 422);
        }

        $user = User::find($payload['user_id'] ?? null);
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if ((string) $user->code !== (string) $code) {
            return response()->json(['message' => 'Wrong verification code. Please try again.'], 422);
        }

        // Mark user as logged in — same as codeVerify()
        $user->verify   = 1;
        $user->is_login = 1;
        $user->is_time  = now();
        $user->ss_time  = now();
        $user->save();

        // Create DailyQoute if assign_daily_qoute is set — same as codeVerify()
        if ($user->assign_daily_qoute > 0) {
            $daily = DailyQoute::where('user_id', $user->id)->whereDate('date', date('Y-m-d'))->first();
            if (!$daily) {
                $daily = new DailyQoute();
                $daily->user_id     = $user->id;
                $daily->total_qoute = $user->assign_daily_qoute;
                $daily->date        = date('Y-m-d');
                $daily->save();
            }
        }

        // Now generate the signed SSO URL
        $ssoPayload = Crypt::encryptString(json_encode([
            'user_id'   => $user->id,
            'email'     => $user->email,
            'issued_at' => now()->timestamp,
            'cr_origin' => true,
        ]));

        $redirectUrl = URL::temporarySignedRoute(
            'bridge.sso.consume',
            now()->addMinutes(2),
            ['payload' => $ssoPayload]
        );

        return response()->json([
            'message'      => 'Verification successful.',
            'redirect_url' => $redirectUrl,
        ]);
    }

    // -------------------------------------------------------------------------
    // GET /bridge/sso/consume  (signed URL — logs user in)
    // -------------------------------------------------------------------------
    public function consume(Request $request): RedirectResponse
    {
        abort_unless($request->hasValidSignature(), 403);

        $payload = json_decode(
            Crypt::decryptString($request->query('payload')),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $user = User::findOrFail($payload['user_id']);

        Auth::login($user);
        $request->session()->regenerate();

        // Tag session so logout and landing page know this user came from CrazyRays
        if (!empty($payload['cr_origin'])) {
            $request->session()->put('cr_origin', 'crazyrays');
        }

        return redirect('/dashboard');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Map signup_type string to [role_id, reference_user_id].
     * Accepts both new ('agent','carrier') and old daydispatch types for compatibility.
     */
    private function resolveRoleAndReference(string $type): array
    {
        $agentTypes   = ['agent', 'shipper', 'broker', 'broker_dispatcher'];
        $carrierTypes = ['carrier', 'dispatcher'];

        if (in_array($type, $agentTypes, true)) {
            $role = role::where('name', 'Order Taker')->first();
            return [$role?->id, self::AGENT_REFERENCE_USER_ID];
        }

        if (in_array($type, $carrierTypes, true)) {
            $role = role::where('name', 'Dispatcher')->first();
            return [$role?->id, self::CARRIER_REFERENCE_USER_ID];
        }

        // Default to agent for unknown types
        $role = role::where('name', 'Order Taker')->first();
        return [$role?->id, self::AGENT_REFERENCE_USER_ID];
    }

    private function getReferenceUserPanelType(int $userId): ?int
    {
        $setting = user_setting::where('user_id', $userId)->first();
        return $setting ? (int) $setting->penal_type : null;
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
