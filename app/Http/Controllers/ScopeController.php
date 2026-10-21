<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ScopeController extends Controller
{
    public function enter(int $userId): RedirectResponse
    {
        $target = User::findOrFail($userId);

        if ($target->id === Auth::id()) {
            return back()->with('err', 'You are already in your own account.');
        }

        // View-as is for Admins and Managers only, and only an Admin may view an Admin. It used to
        // be open to every signed-in user, so an agent could open /scope/enter/1 and be the admin.
        // Judged on the real person (the original account while already viewing as someone).
        $actor = User::find(session('scope_original_id', Auth::id()));
        if (! $actor || ! in_array((int) $actor->role, [1, 9], true)
            || ((int) $target->role === 1 && (int) $actor->role !== 1)) {
            abort(403);
        }

        // Store original admin id (kept if already viewing as someone, so Exit returns to them)
        session(['scope_original_id' => $actor->id]);

        // Mark target as logged in — mirrors what WelcomeController::codeVerify does
        $target->is_login = 1;
        $target->is_time  = now();
        $target->ss_time  = now();
        $target->save();

        Auth::loginUsingId($userId);

        return redirect('/dashboard');
    }

    public function exit(): RedirectResponse
    {
        $originalId = session('scope_original_id');

        if (!$originalId) {
            return redirect('/dashboard');
        }

        // Mark scoped user as logged out
        $scoped = User::find(Auth::id());
        if ($scoped) {
            $scoped->is_login = 0;
            $scoped->save();
        }

        // Switch back to original admin
        Auth::loginUsingId($originalId);
        session()->forget('scope_original_id');

        // loginUsingId() gave this browser a new session id. The login listener skips scope
        // switches, so flag it here: EnforceUserSecurity records it as the admin's single active
        // session on the next request instead of treating it as a stale one and logging out.
        session()->put('b6_fresh_login', true);

        // Refresh admin login timestamps so inactivity checks don't fire
        $admin = User::find($originalId);
        if ($admin) {
            $admin->is_login = 1;
            $admin->is_time  = now();
            $admin->ss_time  = now();
            $admin->save();
        }

        return redirect('/view_employee');
    }
}
