<?php

namespace App\Http\Controllers;

use App\role;
use App\User;
use App\user_setting;
use App\Services\HrPortalBridgeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
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
            // Optional personal fields
            'father_name'    => 'nullable|string|max:100',
            'dob'            => 'nullable|date|before_or_equal:today',
            'gender'         => 'nullable|in:male,female,other',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'cnic'           => 'nullable|string|max:20',
            'city'           => 'nullable|string|max:100',
            'state'          => 'nullable|string|max:100',
            'country'        => 'nullable|string|max:100',
        ]);

        // Resolve role
        $roleName = $request->signup_type === 'agent' ? 'Order Taker' : 'Dispatcher';
        $role     = role::where('name', $roleName)->first();

        if (!$role) {
            Log::error("PublicSignupController: role '{$roleName}' not found.");
            return back()->withInput()->with('error', 'Registration is temporarily unavailable. Please contact support.');
        }

        $referenceUserId = $request->signup_type === 'agent'
            ? self::AGENT_REFERENCE_USER_ID
            : self::CARRIER_REFERENCE_USER_ID;

        $referenceUser = User::find($referenceUserId);

        if (!$referenceUser) {
            Log::error("PublicSignupController: reference user id={$referenceUserId} not found.");
            return back()->withInput()->with('error', 'Registration is temporarily unavailable. Please contact support.');
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
            $user->status    = 0;  // Inactive until admin activates
            $user->verify    = 1;  // Must be 1 so user appears in employee list

            foreach (self::PERMISSION_COLUMNS as $col) {
                $user->$col = $referenceUser->$col;
            }

            $user->save();

            // user_settings
            $penal_type = $request->signup_type === 'agent'
                ? 1
                : ($this->getReferenceUserPanelType($referenceUserId) ?? 1);

            $setting             = new user_setting();
            $setting->user_id    = $user->id;
            $setting->penal_type = $penal_type;
            $setting->call_type  = 134;
            $setting->save();

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('PublicSignupController store failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Registration failed. Please try again.');
        }

        // ── Mirror to HR portal (non-blocking — failure does NOT stop signup) ──
        try {
            $this->hrBridge->createEmployee([
                'name'            => $request->name . ' ' . $request->last_name,
                'email'           => $request->email,
                'password'        => $request->password,  // plain — HR portal hashes it
                'phone'           => $request->phone,
                'address'         => $request->address,
                'user_type'       => $request->signup_type, // 'agent' or 'carrier'
                'agent_id'        => $user->id,             // user.id = hr_employees.agent_id
                'shift_type_id'   => (int) $request->shift_type_id,
                'account_type_id' => (int) $request->account_type_id,
                // Extra personal fields
                'father_name'     => $request->father_name,
                'dob'             => $request->dob,
                'gender'          => $request->gender,
                'marital_status'  => $request->marital_status,
                'cnic'            => $request->cnic,
                'city'            => $request->city,
                'state'           => $request->state,
                'country'         => $request->country,
            ]);
        } catch (\Throwable $e) {
            // Log but don't fail — HR portal may be temporarily down
            Log::warning('PublicSignupController: HR portal createEmployee failed', [
                'user_id' => $user->id,
                'email'   => $request->email,
                'error'   => $e->getMessage(),
            ]);
        }

        return redirect('/loginn')->with(
            'success',
            'Account created successfully. Your account is pending admin activation. You will be notified once activated.'
        );
    }

    private function getReferenceUserPanelType(int $userId): ?int
    {
        $setting = user_setting::where('user_id', $userId)->first();
        return $setting ? (int) $setting->penal_type : null;
    }
}
