<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\user_setting;

class RingCentralAccess
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user->role == 1) {
            return $next($request);
        }

        // Mirror the same panel-based logic used in the navbar
        $setting = user_setting::where('user_id', $user->id)->first();
        $ptype = $setting ? $setting->penal_type : 1;

        if ($ptype == 1) {
            $access = $user->emp_access_phone;
        } elseif ($ptype == 3) {
            $access = $user->emp_access_test;
        } else {
            $access = $user->emp_access_web;
        }

        $permissions = array_filter(explode(',', (string) $access));

        if (in_array('169', $permissions)) {
            return $next($request);
        }

        return redirect('/dashboard')->with('error', 'You do not have access to R-Dialer.');
    }
}
