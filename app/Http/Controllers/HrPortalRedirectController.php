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
        $hrEmployee = \Illuminate\Support\Facades\DB::table('hr_employees')
            ->where('agent_id', $userId)
            ->first();

        if (!$hrEmployee) {
            return back()->with('error', 'This user is not linked to the HR portal yet.');
        }

        try {
            $response = $this->bridge->adminEmployeeView((int) $hrEmployee->id);
        } catch (RuntimeException $e) {
            return back()->with('error', 'HR portal is not reachable right now. Please try again later.');
        }

        if (!empty($response['redirect_url'])) {
            return redirect()->away($response['redirect_url']);
        }

        return back()->with('error', 'HR portal returned an invalid response. Please try again.');
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
    public function redirect(Request $request): RedirectResponse
    {
        $userId     = Auth::id();
        $redirectTo = $request->query('to', 'dashboard');

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
