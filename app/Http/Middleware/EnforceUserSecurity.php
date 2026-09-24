<?php

namespace App\Http\Middleware;

use App\Support\IpRestriction;
use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * Batch 6 (D2): runs on authenticated web requests.
 *  - Single session: after a fresh login it records the (post-regenerate) session id; any request
 *    whose session id no longer matches is logged out → the OLDER device is kicked when the user
 *    logs in on a new one.
 *  - IP restriction: if the user has ip_check_enabled, a request from a non-allowed IP is logged out.
 * Fail-open on any error (never lock users out due to a missing column / exception).
 */
class EnforceUserSecurity
{
    public function handle($request, Closure $next)
    {
        try {
            $user = Auth::user();
            if ($user) {
                $sessionId = $request->hasSession() ? $request->session()->getId() : null;

                // Just logged in (flag set by RecordLoginActivity, survives session regeneration):
                // store THIS session as the single active one.
                if ($sessionId && $request->session()->pull('b6_fresh_login')) {
                    $user->current_session_id = $sessionId;
                    $user->saveQuietly();
                } elseif ($sessionId && !empty($user->current_session_id)
                        && $user->current_session_id !== $sessionId) {
                    // A newer login on another device took ownership — kick this (older) session.
                    return $this->kick($request, 'Your account was signed in on another device, so this session was ended.');
                }

                // Per-user IP allowlist.
                if ($msg = IpRestriction::enforce($user, $request->ip())) {
                    return $this->kick($request, $msg);
                }
            }
        } catch (\Throwable $e) {
            // fail-open — security check must never break the app
        }

        return $next($request);
    }

    /**
     * End the session and send the user back to the portal login WITH the reason visible.
     *
     * Reported 24 Sep 2026: an IP-restricted account just "kicked out, no error shows". Two causes,
     * both fixed here:
     *
     *  1. It redirected to route('login'), which resolves to the Laravel auth scaffold on the
     *     hellotransport.com domain — not the portal's own /loginn page, and on CrazyRays a
     *     different host entirely. A flash written for this domain's session cannot be read on
     *     another one, so the reason was thrown away with the redirect. Now it stays on the host
     *     the user is already using.
     *  2. It passed the reason via withErrors(), but login2.blade.php only renders
     *     session('flash_message') — it has no $errors block. So even on the right page nothing
     *     appeared. Both are now set: flash_message for this portal, withErrors for anything else
     *     that reads the standard bag.
     */
    private function kick($request, string $message)
    {
        Auth::logout();
        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 401);
        }

        return redirect('/loginn')
            ->with('flash_message', $message)
            ->withErrors(['email' => $message]);
    }
}
