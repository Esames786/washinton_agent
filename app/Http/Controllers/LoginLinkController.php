<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Opens a link made by `php artisan login:link` (signed, expiring, one use).
 *
 * The browser is signed in the way "view as" does it: the session is marked as a view-as of the
 * account itself, so it does not take over that person's single active session (they are not
 * logged out) and is not recorded as one of their devices. Exit returns to the same account,
 * never to anyone else.
 */
class LoginLinkController extends Controller
{
    public function __invoke(Request $request, string $nonce): RedirectResponse
    {
        $userId = Cache::pull('login_link:' . $nonce);   // one use: gone after the first visit
        $user   = $userId ? User::where('id', $userId)->where('deleted', 0)->first() : null;
        if (!$user) {
            Log::warning('login:link refused (expired, used or unknown)', ['ip' => $request->ip()]);
            abort(403, 'This sign-in link has expired or has already been used.');
        }

        if (Auth::check()) {
            Auth::logout();
        }
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $request->session()->put('scope_original_id', $user->id);
        $user->forceFill(['is_login' => 1, 'is_time' => now(), 'ss_time' => now()])->save();
        Auth::loginUsingId($user->id);

        Log::warning('login:link used', ['user_id' => $user->id, 'email' => $user->email, 'ip' => $request->ip()]);

        return redirect('/dashboard');
    }
}
