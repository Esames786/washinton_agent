<?php

namespace App\Http\Controllers\phone_quote\user_commission;

use App\call_history;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\role;
use App\AutoOrder;
use App\report;
use App\singlereport;
use App\zipcodes;
use App\count_click;
use App\carrier;
use App\first_bonus;
use App\second_bonus;
use App\cancel_bonus;
use Session;
use Redirect;
use Hash;
use Mail;
use Auth;
use DB;
use App\count_day;
use Carbon\Carbon;
use Vinkla\Hashids\Facades\Hashids;

class UserCommissionController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('userRole')
        ->whereHas('userRole',function($q){
            $q->where('name','Order Taker')
            ->orWhere('name','Seller Agent')
            ->orWhere('name','CSR')
            ->orWhere('name','Manager')
            ->orWhere('name','Dispatcher');
        })->where('deleted',0)->orderBy('role','ASC')->get();
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
        return view('main.phone_quote.user_commission.index',compact('users','display'));
    }

    public function first_bonus(Request $request)
    {
        $data = first_bonus::all();
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
        return view('main.phone_quote.user_commission.first_bonus',compact('data','display'));
    }
    public function save_first_bonus(Request $request)
    {
        $data = first_bonus::truncate();

        $count = count($request->from_first);
        $i=0;
        while ($i<$count){

            $first_bonus = new first_bonus();
            $first_bonus->fromm = $request->from_first[$i];
            $first_bonus->too = $request->too_first[$i];
            $first_bonus->gett = $request->gett_first[$i];
            $first_bonus->save();
            $i++;
        }

        Session::flash('flash_message', 'Successfully Saved');


        return redirect('first_bonus');
    }


    public function second_bonus(Request $request)
    {
        $data = second_bonus::all();
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
        return view('main.phone_quote.user_commission.second_bonus',compact('data','display'));
    }

    public function post_commision(Request $request){

        $fromdate = $request->fromdate;
        $todate = $request->todate;
        $user_idd = $request->user_name;
        
        $from = date('Y-m-d 00:00:00', strtotime($fromdate));
        $too = date('Y-m-d 23:59:59', strtotime($todate));
        
        $user = User::find($user_idd);
        
        if($user->userRole->name == 'Manager' || $user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent' || $user->userRole->name == 'Admin')
        {
            $data = AutoOrder::whereIn('pstatus',[13,14])
                ->where(function ($q) use ($user){
                    $q->where('order_taker_id',$user->id);
                })
                ->whereBetween('updated_at', [$from,$too])
                ->select(['id','pstatus','payment','pay_carrier','created_at','updated_at'])
                ->get();
                
                // echo "<pre>";
                // print_r($data->toArray());
                // exit();
    
            return view('main.phone_quote.user_commission.data',compact('data','fromdate','todate','user_idd'));
        }
        else if($user->userRole->name == 'Dispatcher')
        {
            
            $data = AutoOrder::whereIn('pstatus',['10','11','12'])
                ->where(function ($q) use ($user){
                    $q->where('dispatcher_id',$user->id);
                })
                ->whereBetween('updated_at', [$from,$too])
                ->select(['id','pstatus','payment','pay_carrier','created_at','updated_at'])
                ->get();
                
            return view('main.phone_quote.user_commission.data2',compact('data','fromdate','todate','user_idd'));
        }
        

    }

    public function cancel_orders(Request $request){

        $fromdate = $request->fromdate;
        $todate = $request->todate;
        $user_idd = $request->user_idd;

        return view('main.phone_quote.user_commission.cancel',compact('fromdate','todate','user_idd'));
    }

    public function save_second_bonus(Request $request)
    {
        $data = second_bonus::truncate();

        $count = count($request->from_first);
        $i=0;
        while ($i<$count){

            $second_bonus = new second_bonus();
            $second_bonus->fromm = $request->from_first[$i];
            $second_bonus->too = $request->too_first[$i];
            $second_bonus->gett = $request->gett_first[$i];
            $second_bonus->save();
            $i++;
        }

        Session::flash('flash_message', 'Successfully Saved');


        return redirect('second_bonus');
    }

    public function cancel_bonus()
    {
        $data = cancel_bonus::all();
        return view('main.phone_quote.user_commission.cancel_bonus',compact('data'));
    }

    public function save_cancel_bonus(Request $request)
    {
        $data = cancel_bonus::truncate();

        $count = count($request->from_first);
        $i=0;
        while ($i<$count){

            $cancel_bonus = new cancel_bonus();
            $cancel_bonus->fromm = $request->from_first[$i];
            $cancel_bonus->too = $request->too_first[$i];
            $cancel_bonus->gett = $request->gett_first[$i];
            $cancel_bonus->save();
            $i++;
        }

        Session::flash('flash_message', 'Successfully Saved');


        return redirect('cancel_bonus');
    }

    public function ot_commission()
    {
        $user = Auth::user();
        $from = Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00');
        $too = Carbon::now()->format('Y-m-d 23:59:59');
        $user_idd = $user->id;
        
        if($user->userRole->name == 'Manager' || $user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent' || $user->userRole->name == 'Admin')
        {
            $data = AutoOrder::whereIn('pstatus',[13,14])
            ->where(function ($q) use ($user){
                $q->where('order_taker_id',$user->id);
            })
            ->whereBetween('updated_at', [$from,$too])
            ->select(['id','pstatus','payment','pay_carrier'])
            ->get();
                
                
            return view('main.phone_quote.user_commission.ot_commission',compact('data','user_idd'));
        }
        
    }






}
