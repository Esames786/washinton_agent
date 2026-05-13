<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\User;
use Session;
use Illuminate\Support\Facades\DB;

class AllRoles
{
    // HR statuses that require the employee to complete verification before using the portal
    const PENDING_HR_STATUSES = [7, 8]; // Document Verification, Pending Contract

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check())
        {
            if (now()->diffInMinutes(Auth::user()->is_time) >= (600) ) {  // also you can this value in your config file and use here
                if (Auth::user()->role > 1) {
                   $user = Auth::user();
                   Auth::logout();

                   $user->update(['is_login' => 0,'is_time'=>now()]);

                   Session::flash('flash_message', 'Your time has been expired!');
                   return redirect('/loginn');
                }

            }
            if(Auth::user()->userRole->name <> 'Chat Approver' || Auth::user()->userRole->name <> 'Code Giver')
            {
                if(Auth::user()->status == 0)
                {
                    $user = User::find(Auth::user()->id);
                    $user->is_login = 0;
                    $user->save();
                    Auth::logout();
                   Session::flash('flash_message', 'Your time has been expired!');
                    return redirect('/loginn');
                }

                // Redirect non-admin users with pending HR status to the HR portal
                if (Auth::user()->role > 1 && !$this->isHrRedirectExempt($request)) {
                    $hrStatusId = DB::table('hr_employees')
                        ->where('agent_id', Auth::id())
                        ->value('employee_status_id');
                    if ($hrStatusId && in_array((int) $hrStatusId, self::PENDING_HR_STATUSES)) {
                        return redirect('/hr-portal/' . Auth::id());
                    }
                }

                // return redirect('/employees');
                return $next($request);
            }
            return back();
        }
        else
        {
            return redirect('/loginn');
        }
    }

    private function isHrRedirectExempt($request): bool
    {
        $exempt = ['hr-portal', 'employee-review', 'logout', 'logoutAllAccounts', 'clear_cache', 'scope/exit'];
        foreach ($exempt as $prefix) {
            if ($request->is($prefix) || $request->is($prefix . '/*')) {
                return true;
            }
        }
        return false;
    }
}
