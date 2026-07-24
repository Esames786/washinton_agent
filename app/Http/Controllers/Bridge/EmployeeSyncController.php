<?php

namespace App\Http\Controllers\Bridge;

use App\Http\Controllers\Controller;
use App\role;
use App\User;
use App\user_setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * Bridge endpoint called by washinton_hr to sync employees to washinton_agent.
 *
 * Auth: X-Bridge-Key header must match HELLOTRANSPORT_BRIDGE_SHARED_KEY in .env
 */
class EmployeeSyncController extends Controller
{
    /**
     * Reference user IDs for permission copying
     * Agent / Order Taker → user id 130
     */
    private const AGENT_REFERENCE_USER_ID = 130;

    private const PERMISSION_COLUMNS = [
        'emp_access_phone', 'emp_access_web', 'emp_access_test',
        'panel_type_4', 'panel_type_5', 'panel_type_6',
        'emp_panel_access', 'emp_show_data', 'emp_access_ship',
        'emp_access_profile', 'emp_access_action', 'emp_access_report',
        'emp_access_guide', 'order_taker_quote', 'assign_daily_qoute',
        'sheet_access',
    ];

    /**
     * Sync employee from washinton_hr to washinton_agent
     *
     * POST /bridge/employee/sync
     * Auth: X-Bridge-Key header
     *
     * Request body:
     * {
     *   "employee_id": 1,
     *   "first_name": "John",
     *   "last_name": "Doe",
     *   "email": "john@example.com",
     *   "phone": "1234567890",
     *   "role_id": 5,
     *   "role_name": "Agent"
     * }
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function syncEmployee(Request $request): JsonResponse
    {
        $this->assertBridgeKey($request);

        $validator = Validator::make($request->all(), [
            'employee_id' => ['required', 'integer'],
            'first_name'  => ['nullable', 'string', 'max:50'],
            'last_name'   => ['nullable', 'string', 'max:50'],
            'email'       => ['required', 'email', 'max:50'],
            'phone'       => ['nullable', 'string', 'max:20'],
            'role_id'     => ['required', 'integer'],
            'role_name'   => ['required', 'string', 'max:50'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Generate defaults for first_name and last_name if missing
            $email = $request->input('email');
            $firstName = trim($request->input('first_name')) ?: explode('@', $email)[0];
            $lastName = trim($request->input('last_name')) ?: 'Employee';

            // Step 1: Get or create role in washinton_agent
            $roleId = $this->getRoleIdOrCreate(
                (int) $request->input('role_id'),
                $request->input('role_name')
            );

            if (!$roleId) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to resolve or create role.',
                ], 500);
            }

            // Step 2: Create or update user in washinton_agent
            $user = $this->createOrUpdateUser(
                [
                    'hr_employee_id' => $request->input('employee_id'),
                    'first_name'     => $firstName,
                    'last_name'      => $lastName,
                    'email'          => $email,
                    'phone'          => $request->input('phone', ''),
                    'city'           => $request->input('city', ''), // B6: for city-based panel assignment
                ],
                $roleId
            );

            if (!$user) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create or update user.',
                ], 500);
            }

            DB::commit();

            Log::info('EmployeeSyncController: Employee synced successfully', [
                'hr_employee_id' => $request->input('employee_id'),
                'user_id'        => $user->id,
                'email'          => $user->email,
                'role_id'        => $roleId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Employee synced successfully.',
                'user_id' => $user->id,
                'role_id' => $roleId,
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('EmployeeSyncController: Employee sync failed', [
                'employee_id' => $request->input('employee_id'),
                'email'       => $request->input('email'),
                'error'       => $e->getMessage(),
                'trace'       => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to sync employee: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get or create role in washinton_agent
     *
     * Attempts to find role by HR role_id first, then by name.
     * If not found, creates a new role.
     *
     * @param int $hrRoleId
     * @param string $roleName
     * @return int|null Role ID, or null on failure
     */
    private function getRoleIdOrCreate(int $hrRoleId, string $roleName): ?int
    {
        // Try to find existing role by name (safer approach since role_id may differ)
        $existingRole = role::where('name', $roleName)
            ->first();

        if ($existingRole) {
            return $existingRole->id;
        }

        // Create new role if not found
        try {
            $newRole = role::create([
                'name'        => $roleName,
                'slug'        => Str::slug($roleName),
                'description' => 'Synced from HR',
                'level'       => 5, // Default level
            ]);

            Log::info('EmployeeSyncController: New role created', [
                'role_id'   => $newRole->id,
                'role_name' => $roleName,
            ]);

            return $newRole->id;
        } catch (\Throwable $e) {
            Log::error('EmployeeSyncController: Failed to create role', [
                'role_name' => $roleName,
                'error'     => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Create or update user in washinton_agent
     *
     * @param array $data
     * @param int $roleId
     * @return User|null
     */
    private function createOrUpdateUser(array $data, int $roleId): ?User
    {
        // Check if user already exists by email
        $user = User::where('email', $data['email'])->first();

        if ($user) {
            // Update existing user
            $user->update([
                'name'           => $data['first_name'] . ' ' . $data['last_name'],
                'phone'          => $data['phone'] ?? '',
                'role'           => $roleId,
                'hr_employee_id' => $data['hr_employee_id'],
            ]);

            Log::info('EmployeeSyncController: Existing user updated', [
                'user_id'        => $user->id,
                'email'          => $user->email,
                'hr_employee_id' => $data['hr_employee_id'],
            ]);

            return $user;
        }

        // Create new user
        try {
            // Generate a random password and unique slug
            $password = Str::random(12);
            $slug = $this->generateUniqueSlug($data['email']);

            $user = new User();
            $user->name           = $data['first_name'] . ' ' . $data['last_name'];
            $user->last_name      = $data['last_name'];
            $user->slug           = $slug;
            $user->email          = $data['email'];
            $user->password       = Hash::make($password);
            $user->phone          = $data['phone'] ?? '';
            $user->address        = '';
            $user->role           = $roleId;
            $user->hr_employee_id = $data['hr_employee_id'];
            $user->verify         = 1;
            $user->status         = 1;

            // B6: permission/access columns from signup_defaults (fallback = reference user).
            $referenceUser = User::find(self::AGENT_REFERENCE_USER_ID);
            // ── OLD panel logic (kept for record; replaced by #4 no-access default) ──
            // \App\Support\SignupProvisioner::applyDefaults($user, 'order_taker', self::PERMISSION_COLUMNS, $referenceUser);
            // $penal_type = 1;
            // $cityPanel = \App\Support\SignupProvisioner::resolveCityPanelId($data['city'] ?? null, null);
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

            // Create user settings
            $setting             = new user_setting();
            $setting->user_id    = $user->id;
            $setting->penal_type = $penal_type;
            $setting->call_type  = 134;
            $setting->save();

            // ── #4: ProMax + Website Quote grants removed for no-access signups (kept for record) ──
            // // Also grant ProMax (penal_type=2) access so user is eligible for website quote assignment
            // $promax             = new user_setting();
            // $promax->user_id    = $user->id;
            // $promax->penal_type = 2;
            // $promax->call_type  = 134;
            // $promax->save();
            //
            // // Also grant Website Quote (penal_type=4) access so autohaul leads land here
            // $website             = new user_setting();
            // $website->user_id    = $user->id;
            // $website->penal_type = 4;
            // $website->call_type  = 134;
            // $website->save();

            Log::info('EmployeeSyncController: New user created', [
                'user_id'        => $user->id,
                'email'          => $user->email,
                'hr_employee_id' => $data['hr_employee_id'],
            ]);

            return $user;

        } catch (\Throwable $e) {
            Log::error('EmployeeSyncController: Failed to create user', [
                'email' => $data['email'],
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Generate a unique slug from email address
     *
     * @param string $email
     * @return string
     */
    private function generateUniqueSlug(string $email): string
    {
        $baseSlug = Str::slug(explode('@', $email)[0]);
        $slug = $baseSlug;
        $counter = 1;

        // Ensure uniqueness by appending counter if needed
        while (User::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Assert that the bridge key is valid
     *
     * @param Request $request
     * @return void
     * @throws \Illuminate\Auth\AuthenticationException
     */
    private function assertBridgeKey(Request $request): void
    {
        $providedKey = $request->header('X-Bridge-Key');
        $expectedKey = env('HELLOTRANSPORT_BRIDGE_KEY');

        if (!$providedKey || $providedKey !== $expectedKey) {
            Log::warning('EmployeeSyncController: Invalid bridge key', [
                'provided_key' => $providedKey ? substr($providedKey, 0, 10) . '...' : 'missing',
            ]);
            abort(403, 'Invalid bridge key');
        }
    }
}
