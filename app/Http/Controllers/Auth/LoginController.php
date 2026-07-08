<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\UserLoginActivity;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * #11: capture the login IP so admins/managers can see an employee's login activity.
     * #12: if IP restriction is enabled for this user, block logins from non-allowed IPs.
     */
    protected function authenticated(Request $request, $user)
    {
        UserLoginActivity::record($user->id, $request->ip(), 'hello', $request->userAgent());

        if ($resp = \App\Support\IpRestriction::enforce($user, $request->ip())) {
            \Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors(['email' => $resp]);
        }
    }
}
