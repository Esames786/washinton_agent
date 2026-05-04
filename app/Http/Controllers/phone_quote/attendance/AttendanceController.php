<?php

namespace App\Http\Controllers\phone_quote\attendance;

use App\call_history;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\role;


use Session;
use Redirect;
use Hash;
use Mail;
use Auth;
use DB;
use App\attendance;
use Carbon;
use Vinkla\Hashids\Facades\Hashids;

class AttendanceController extends Controller
{
    public function attendance_report(Request $request)
    {

        $attendancedate = "";

        $attendancedate = date('Y-m-d');

        $users = User::where('deleted',0)->orderBy('role','ASC')->paginate(20);
        $display = 'no';

        if (isset($request->pass)) {
            $check = \App\ReportPassword::first();
            if (!empty($check)) {
                if(Hash::check($request->pass,$check->password))
                {
                    $display = 'yes';
                }
                 else {
                    $display = 'no';
                }
            } else {
                $display = 'no';
            }
        }
        return view('main.phone_quote.attendance.attendance_report', compact('attendancedate', 'users','display'));

    }
    public function attendance_report2(Request $request)
    {

        $attendancedate = ""; 
        $display = "yes";

        if (!empty($request->datev)) {
            $attendancedate = $request->datev;
        } else {
            $attendancedate = date('Y-m-d');
        }


        $users = User::where('deleted',0)->orderBy('role','ASC')->paginate(20);
        return view('main.phone_quote.attendance.attendance_report', compact('attendancedate', 'users','display'));

    }

    public function fetch_attendance_data(Request $request)
    {

        if (Auth::check()) {

              $attendancedate = date('Y-m-d');

            $users = User::where('deleted',0)->orderBy('role','ASC')->paginate(20);
            $attendancedate = "";
            $display = "yes";

            if (isset($request->datev)) {
                $attendancedate = $request->datev;
            } else {
                $attendancedate = date('Y-m-d');
            }


            if ($request->ajax()) {

                return view('main.phone_quote.attendance.attendance_report_load', compact('attendancedate','users','display'))->render();
            } else {
                return view('main.phone_quote.attendance.attendance_report_load', compact('attendancedate','users','display'));
            }

        } else {
            return redirect('/loginn/');
        }
    }
}
