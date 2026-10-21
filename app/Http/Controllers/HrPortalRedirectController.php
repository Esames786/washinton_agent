<?php

namespace App\Http\Controllers;

use App\Services\HrPortalBridgeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

class HrPortalRedirectController extends Controller
{
    public function __construct(
        protected HrPortalBridgeService $bridge
    ) {}

    /**
     * Admin SSO: washinton_agent admin → HR portal admin view of a user's employee profile.
     * URL: /hr-portal/admin/employee/{userId}
     */
    public function adminRedirect(Request $request, int $userId): RedirectResponse
    {
        // The HR portal signs this person in as its admin, so only CRM Admins, Managers and HR
        // Executives may ask for the link (it was open to every signed-in user).
        abort_unless(in_array((int) Auth::user()->role, [1, 9, 26], true), 403);

        $hrEmployee = \Illuminate\Support\Facades\DB::table('hr_employees')
            ->where('agent_id', $userId)
            ->first();

        if (!$hrEmployee) {
            return back()->with('error', 'This user is not linked to the HR portal yet.');
        }

        $payload = base64_encode(json_encode([
            'employee_id'   => $hrEmployee->id,
            // #8/#9: HR admin URI renamed employees -> subcontractors (deploy both projects together).
            'redirect_path' => '/admin/subcontractors/show/' . $hrEmployee->id,
            'expires_at'    => now()->addMinutes(3)->timestamp,
        ]));

        $sig = hash_hmac('sha256', $payload, (string) config('bridge.hrportal.shared_key'));

        $ssoUrl = rtrim((string) config('bridge.hrportal.base_url'), '/')
            . '/admin/sso/consume?payload=' . urlencode($payload)
            . '&sig=' . $sig;

        return redirect()->away($ssoUrl);
    }

    /**
     * SSO redirect: logged-in agent → HR portal.
     *
     * Optional query param: ?to=profile  → lands on employee profile page
     * Default:             ?to=dashboard → lands on employee dashboard
     *
     * Usage in views:
     *   route('hr.portal.redirect')              → dashboard
     *   route('hr.portal.redirect') . '?to=profile' → profile page
     */
    public function redirect(Request $request, $userId = null): RedirectResponse
    {
        $userId     = (is_numeric($userId)) ? (int) $userId : Auth::id();
        $redirectTo = $request->query('to', 'dashboard');

        // Signs the browser into the HR portal as $userId: anyone may open their own, only Admins
        // and Managers someone else's (any agent could open another agent's HR account by id).
        abort_unless($userId === (int) Auth::id() || in_array((int) Auth::user()->role, [1, 9], true), 403);

        // Check if this user is linked to an HR employee record
        $hrEmployee = \Illuminate\Support\Facades\DB::table('hr_employees')
            ->where('agent_id', $userId)
            ->first();

        if (!$hrEmployee) {
            return back()->with('error', 'Your account is not linked to the HR portal yet. Please contact your administrator.');
        }

        try {
            $response = $this->bridge->login($userId, $redirectTo);
        } catch (RuntimeException $e) {
            return back()->with('error', 'HR portal is not reachable right now. Please try again later.');
        }

        if (!empty($response['redirect_url'])) {
            return redirect()->away($response['redirect_url']);
        }

        return back()->with('error', 'HR portal returned an invalid response. Please try again.');
    }
}
