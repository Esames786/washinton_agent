<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * An agent who still owes an NDA gets the NDA on its own page — not on top of the dashboard.
 *
 * Reported repeatedly (22–24 Sep 2026) by several different agents: "Page Unresponsive" while
 * filling the NDA form, on modest machines and poor connections. The form itself is cheap — pure
 * vanilla JS, no jQuery — but it was rendered as an overlay on the FULL dashboard, which measured
 * 385 KB with 56 script tags and 42 AJAX calls all loading and running behind it while the agent
 * typed. None of that is usable: the overlay blocks the portal until the NDA is signed.
 *
 * So the whole page is skipped and only the NDA is served. Nothing about the form, its validation
 * or its submit changes — it is the same partial, just without a dashboard underneath it.
 *
 * Deliberately narrow, so this can never strand anyone:
 *   - only signed-in users who actually owe an NDA,
 *   - only ordinary page loads (GET, not AJAX/JSON), so no form post or API call is diverted,
 *   - logout, login and the NDA routes themselves are always reachable.
 */
class RequireNdaSignature
{
    /** Paths that must stay reachable while the NDA is outstanding. */
    private const EXEMPT = [
        'nda', 'nda/*',
        'logout', 'logoutAllAccounts',
        'loginn', 'loginn/*', 'login', 'register',
        'scope/exit', 'clear_cache',
        'hr-portal', 'hr-portal/*',
    ];

    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (! $user || empty($user->nda_required)) {
            return $next($request);
        }

        // Only ordinary page navigations are redirected. A background poll or a form post must
        // behave exactly as before — otherwise a redirect turns into a confusing silent failure.
        if (! $request->isMethod('GET') || $request->ajax() || $request->wantsJson()) {
            return $next($request);
        }

        foreach (self::EXEMPT as $pattern) {
            if ($request->is($pattern)) {
                return $next($request);
            }
        }

        return redirect('/nda');
    }
}
