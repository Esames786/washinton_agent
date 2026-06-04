<?php

namespace App\Http\Controllers;

use App\Services\RingCentralService;
use App\Models\WebPhoneInstance;
use App\RingCentralUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RingCentralController extends Controller
{
    private $ringCentralService;

    public function __construct(RingCentralService $ringCentralService)
    {
        $this->ringCentralService = $ringCentralService;
    }

    /**
     * Initiate OAuth authentication
     */
    public function authenticate()
    {
        $authUrl = $this->ringCentralService->getAuthenticationUrl();
        return redirect($authUrl);
    }

    /**
     * Handle OAuth callback
     */
    public function callback(Request $request)
    {
        $code = $request->query('code');

        if (!$code) {
            return redirect(route('ringcentral.portal'))
                ->with('error', 'Authorization code not provided');
        }

        $result = $this->ringCentralService->authenticateUser($code, Auth::id());

        if ($result['success']) {
            session()->forget('ringcentral_logged_out');
            return redirect(route('ringcentral.portal'))
                ->with('success', 'Successfully connected to R-Portal!');
        } else {
            return redirect(route('ringcentral.portal'))
                ->with('error', $result['message']);
        }
    }

    /**
     * Show R-Dialer portal
     */
    public function ring_central_portal()
    {
        // Reload RingCentralUser directly from DB to avoid stale relations
        $ringCentralUser = \App\RingCentralUser::where('user_id', Auth::id())->first();

        // If session has extension info (from OAuth), prefer businessPhone as fallback
        $sessionExt = session('ringcentral_extension');
        $chosenPhoneFallback = null;
        if (is_array($sessionExt) || is_object($sessionExt)) {
            $ext = (array) $sessionExt;
            $contact = $ext['contact'] ?? (is_object($sessionExt) && isset($sessionExt->contact) ? (array) $sessionExt->contact : []);
            if (!empty($contact['businessPhone'])) {
                $chosenPhoneFallback = $contact['businessPhone'];
            }
        }

        return view('main.ringcentral.ringcentral', [
            'isConnected' => $ringCentralUser && $ringCentralUser->is_active,
            'ringCentralUser' => $ringCentralUser,
            'chosenPhoneFallback' => $chosenPhoneFallback,
        ]);
    }

    /**
     * Monitor R-Dialer auth and WebPhone instance status
     */
    public function monitor()
    {
        $users = User::query()
            ->with(['ringCentralUser', 'webPhoneInstance'])
            ->orderBy('name')
            ->get();

        $webphoneActiveCutoff = now()->subMinutes(0.25);

        return view('main.ringcentral.monitor', [
            'users' => $users,
            'webphoneTotals' => [
                'total' => WebPhoneInstance::count(),
                'active' => WebPhoneInstance::whereNull('closed_at')
                    ->where('last_seen_at', '>', $webphoneActiveCutoff)
                    ->count(),
                'closed' => WebPhoneInstance::whereNotNull('closed_at')->count(),
            ],
            'ringcentralTotals' => [
                'total' => RingCentralUser::count(),
                'active' => RingCentralUser::where('is_active', true)->count(),
                'tokenExpired' => RingCentralUser::whereNotNull('token_expires_at')
                    ->where('token_expires_at', '<=', now())
                    ->count(),
            ],
        ]);
    }
    public function dialer()
    {        return view('main.dialer.dialer_new');
    }
    // In your controller
    public function checkConnectionStatus()
    {
        if (session()->get('ringcentral_logged_out')) {
            return response()->json([
                'isConnected' => false,
                'chosenPhoneFallback' => null,
                'loggedOut' => true,
            ]);
        }

        // Reload RingCentralUser from DB
        $ringCentralUser = \App\RingCentralUser::where('user_id', Auth::id())->first();

        // Check if the user is active
        $isConnected = $ringCentralUser && $ringCentralUser->is_active;

        if (!$isConnected && $ringCentralUser) {
            try {
                $refreshed = $this->ringCentralService->refreshToken($ringCentralUser, false);
                if ($refreshed) {
                    $ringCentralUser->refresh();
                    $isConnected = (bool) $ringCentralUser->is_active;
                }
            } catch (\Exception $e) {
                Log::warning('Connection status refresh failed', ['error' => $e->getMessage()]);
            }
        }

        // If not connected, check session for R-Dialer extension data
        if (!$isConnected) {
            $sessionExt = session('ringcentral_extension');
            $chosenPhoneFallback = null;

            if (is_array($sessionExt) || is_object($sessionExt)) {
                $ext = (array) $sessionExt;
                if (!empty($ext['contact']->businessPhone)) {
                    $chosenPhoneFallback = $ext['contact']->businessPhone;
                }
            }

            // If fallback phone exists, mark as connected
            $isConnected = $chosenPhoneFallback ? true : false;
        }

        return response()->json([
            'isConnected' => $isConnected,
            'chosenPhoneFallback' => $chosenPhoneFallback ?? null,
        ]);
    }

    /**
     * Logout from RingCentral
     */
    public function logout()
    {
        $this->ringCentralService->logout(Auth::id());
        session()->put('ringcentral_logged_out', true);
        session()->forget('ringcentral_extension');
        session()->forget('ringcentral_auth_data_' . Auth::id());
        // Legacy diagnostic comparison key is no longer written by the refresh flow.
        // session()->forget('ringcentral_login_refresh_token_' . Auth::id());
        return redirect(route('ringcentral.portal'))
            ->with('success', 'Disconnected from RingCentral');
    }

    /**
     * Try to reconnect using stored tokens before forcing OAuth.
     */
    public function reconnect()
    {
        session()->forget('ringcentral_logged_out');

        $ringCentralUser = \App\RingCentralUser::where('user_id', Auth::id())->first();
        if (!$ringCentralUser) {
            $recover = $this->ringCentralService->getReconnectAuthData(Auth::id());

            if (!$recover || empty($recover['authData'])) {
                return response()->json([
                    'success' => false,
                    'connected' => false,
                    'requiresOAuth' => true,
                    'showReconnectModal' => true,
                    'oauthUrl' => route('ringcentral.auth'),
                    'message' => 'No saved R-Dialer session found. Start a new R-Dialer connection?',
                ], 404);
            }

            $authData = $recover['authData'];
            $ringCentralUser = \App\RingCentralUser::updateOrCreate(
                ['user_id' => Auth::id()],
                [
                    'phone_number' => $recover['phoneNumber'] ?? null,
                    'access_token' => encrypt($authData['access_token']),
                    'refresh_token' => encrypt($authData['refresh_token']),
                    'token_expires_at' => now()->addSeconds((int) ($authData['expires_in'] ?? 3600)),
                    'refresh_token_expires_at' => $this->ringCentralService->resolveRefreshTokenExpiresAt($authData),
                    'is_active' => false,
                ]
            );

            try {
                if (!empty($ringCentralUser->phone_number)) {
                    $tokensFile = $this->ringCentralService->getTokensFilePathForUser($ringCentralUser->phone_number);
                    @file_put_contents($tokensFile, json_encode($authData, JSON_PRETTY_PRINT));
                }
            } catch (\Exception $_e) {
                Log::warning('Failed writing recovered reconnect tokens file', [
                    'user_id' => Auth::id(),
                    'error' => $_e->getMessage(),
                ]);
            }
        }

        try {
            $this->ringCentralService->refreshToken($ringCentralUser, true);
            $ringCentralUser->refresh();
            $this->ringCentralService->syncUserProfileFromApi($ringCentralUser);
            $ringCentralUser->refresh();
            $webhook = $this->ringCentralService->ensureWebhookSubscription(Auth::id(), true);

            if ($ringCentralUser->is_active) {
                return response()->json([
                    'success' => true,
                    'connected' => true,
                    'webhook' => $webhook,
                    'message' => !empty($webhook['success'])
                        ? 'Reconnected using saved token.'
                        : 'Reconnected using saved token, but webhook subscription failed: ' . ($webhook['message'] ?? 'unknown error'),
                ]);
            }
        } catch (\Exception $e) {
            Log::warning('Reconnect with saved token failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'success' => false,
            'connected' => false,
            'requiresOAuth' => true,
            'showReconnectModal' => true,
            'oauthUrl' => route('ringcentral.auth'),
            'message' => 'Saved token reconnect failed. Start a new R-Dialer connection?',
        ], 422);
    }

    // WebRTC signaling methods removed - now handled by R-Dialer WebPhone SDK
    // The WebPhone SDK manages all SIP signaling, WebRTC peer connections, and ICE candidates internally

}
