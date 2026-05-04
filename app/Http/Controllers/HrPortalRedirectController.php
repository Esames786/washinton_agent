<?php

namespace App\Http\Controllers;

use App\Services\HrPortalBridgeService;
use App\User;
use Illuminate\Http\RedirectResponse;
use RuntimeException;

class HrPortalRedirectController extends Controller
{
    public function __construct(
        protected HrPortalBridgeService $bridge
    ) {}

    /**
     * Redirect the admin to the HR portal via SSO.
     * Washington user.id is passed as agent_id to the HR portal.
     * The HR portal looks up hr_employees.agent_id = user.id.
     */
    public function redirect(int $userId): RedirectResponse
    {
        $user = User::findOrFail($userId);

        try {
            $response = $this->bridge->login($user->id);
        } catch (RuntimeException $e) {
            return back()->with('hr_portal_error', $e->getMessage());
        }

        if (!empty($response['redirect_url'])) {
            return redirect()->away($response['redirect_url']);
        }

        return back()->with('hr_portal_error', 'HR portal returned an invalid response. Please try again.');
    }
}
