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
        return redirect()->route('login')->withErrors(['email' => $message]);
    }
}
