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
     * SSO redirect: logged-in agent → HR portal.
     * Uses the current user's ID as agent_id.
     * No cookies are shared — HR portal issues its own signed URL.
     */
    public function redirect(Request $request): RedirectResponse
    {
        $userId = Auth::id();

        // Check if this user is linked to an HR employee record
        $hrEmployee = \Illuminate\Support\Facades\DB::table('hr_employees')
            ->where('agent_id', $userId)
            ->first();

        if (!$hrEmployee) {
            return back()->with('error', 'Your account is not linked to the HR portal yet. Please contact your administrator.');
        }

        try {
            $response = $this->bridge->login($userId);
        } catch (RuntimeException $e) {
            return back()->with('error', 'HR portal is not reachable right now. Please try again later.');
        }

        if (!empty($response['redirect_url'])) {
            // redirect()->away() opens the HR portal in the same tab
            // The HR portal session is completely separate — no cookie conflict
            return redirect()->away($response['redirect_url']);
        }

        return back()->with('error', 'HR portal returned an invalid response. Please try again.');
    }
}
