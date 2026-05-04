<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\User;
use Session;

class AllRoles
{
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
}
