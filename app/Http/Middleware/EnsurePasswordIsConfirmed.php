<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsurePasswordIsConfirmed
{
    public function handle($request, Closure $next)
    {
        if (!$request->ajax() && $request->user() && !$request->user()->hasVerifiedPassword()) {
            return redirect()->route('password.confirm');
        }

        return $next($request);
    }
}