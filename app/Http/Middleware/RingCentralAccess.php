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

        // FIX: resolve EVERY panel through accessForPanel(). The old chain handled only panels
        // 1, 3 and 7+, so panels 2, 4, 5 AND 6 all read emp_access_web — an agent on MULTAN (6)
        // with the dialer granted on Multan was checked against ISLAMABAD's permissions, failed,
        // and got bounced straight back to /dashboard ("clicking it just reloads the dashboard").
        $access = $user->accessForPanel($ptype);

        $permissions = array_filter(explode(',', (string) $access));

        if (in_array('169', $permissions)) {
            return $next($request);
        }

        return redirect('/dashboard')->with('error', 'You do not have access to R-Dialer.');
    }
}
