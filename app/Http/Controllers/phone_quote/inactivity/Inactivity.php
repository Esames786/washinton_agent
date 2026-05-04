<?php

namespace App\Http\Controllers\phone_quote\inactivity;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\role;
use App\user_setting;
use App\general_setting;
use App\singlereport;
use Session;
use Redirect;
use Hash;
use Mail;
use Auth;
use DB;
use App\activity;
use Carbon\Carbon;

class Inactivity extends Controller
{
    public function count_activity(Request $request){

        date_default_timezone_set('Asia/Karachi');
        $user_id  =  Auth::user()->id;
        $url_name = $request->url;
        $date = Carbon::today()->format('Y-m-d');


        $inactivity = activity::where('user_id', '=', $user_id)
            ->where('url_name', '=', $url_name)
            ->where('created_at','like', '%' .$date . '%')
            ->first();

        if(!empty($inactivity)) {


            $start = Carbon::parse($inactivity->inactivity_time_start);
            $end =  Carbon::now();
            $duration = $start->diffInMinutes($end);


            $inactivity->inactivity_time_start = $start;
            $inactivity->inactivity_time_end = $end;
            $inactivity->duration = $duration;
            $inactivity->save();
        }else{

            $start = Carbon::now()->subMinutes(5);
            $end = Carbon::now();
            $duration = $start->diffInMinutes($end);

            $inactivity = new activity();
            $inactivity->user_id =$user_id;
            $inactivity->inactivity_time_start = $start;
            $inactivity->inactivity_time_end = $end;
            $inactivity->duration = $duration;
            $inactivity->url_name ="$url_name";
            $inactivity->save();
        }
        return "SUCCESS";

    }


    public function  total_activity(Request $request){

        $data = activity::orderby('created_at', 'desc')->paginate(50);

        if ($request->ajax()) {

            return view('main.phone_quote.activity.load', compact('data'))->render();
        } else {
            return view('main.phone_quote.activity.index', compact('data'));
        }

    }
}
