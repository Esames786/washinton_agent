<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScopeController extends Controller
{
    public function enter(int $userId): RedirectResponse
    {
        // Prevent scoping into yourself
        $target = \App\User::findOrFail($userId);
        if ($target->id === Auth::id()) {
            return back()->with('err', 'You are already in your own account.');
        }

        // Store the original admin id so we can return later
        session(['scope_original_id' => Auth::id()]);

        Auth::loginUsingId($userId);

        return redirect('/dashboard')->with('msg', 'You are now viewing as ' . $target->name . '.');
    }

    public function exit(): RedirectResponse
    {
        $originalId = session('scope_original_id');

        if (!$originalId) {
            return redirect('/home');
        }

        Auth::loginUsingId($originalId);
        session()->forget('scope_original_id');

        return redirect('/view_employee')->with('msg', 'You have returned to your admin account.');
    }
}
