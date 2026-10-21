<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

    // #11/#12 login-activity capture + IP enforcement moved to the global Login event listener
    // (App\Listeners\RecordLoginActivity) + EnforceUserSecurity middleware — fires for every login
    // path (hello + crazy), so it no longer depends on this per-controller hook.

    /**
     * Sign-in is /loginn: password, active-account check, then the emailed code. This scaffold
     * signed people in with a password alone and skipped all of that, so both its pages now send
     * them there. The route names stay because route('login') is referenced elsewhere.
     */
    public function showLoginForm()
    {
        return redirect('/loginn');
    }

    public function login(\Illuminate\Http\Request $request)
    {
        return redirect('/loginn');
    }
}
