<?php

namespace App\Http\Controllers\Bridge;

use App\Http\Controllers\Controller;
use App\role;
use App\User;
use App\user_setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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

            foreach (self::PERMISSION_COLUMNS as $col) {
                $user->$col = $referenceUser->$col;
            }

            $user->save();

            // user_settings
            $penal_type = ($referenceUserId === self::AGENT_REFERENCE_USER_ID)
                ? 1
                : ($this->getReferenceUserPanelType($referenceUserId) ?? 1);

            $setting             = new user_setting();
            $setting->user_id    = $user->id;
            $setting->penal_type = $penal_type;
            $setting->save();

            DB::commit();

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

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Invalid email or password.'], 401);
        }

        if ((int) $user->status === 0) {
            return response()->json(['message' => 'Account is pending admin activation.'], 403);
        }

        // Generate a 2-minute signed SSO URL
        $payload = Crypt::encryptString(json_encode([
            'user_id'   => $user->id,
            'email'     => $user->email,
            'issued_at' => now()->timestamp,
        ]));

        $redirectUrl = URL::temporarySignedRoute(
            'bridge.sso.consume',
            now()->addMinutes(2),
            ['payload' => $payload]
        );

        return response()->json([
            'message'      => 'Login successful.',
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

        // Log the user in using Washington's default guard
        Auth::login($user);
        $request->session()->regenerate();

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
