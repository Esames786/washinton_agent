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

        // Store original admin id
        session(['scope_original_id' => Auth::id()]);

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
