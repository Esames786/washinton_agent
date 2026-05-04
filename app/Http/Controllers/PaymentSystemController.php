<?php

namespace App\Http\Controllers;

use App\Mail\SendCodeMail;
use Illuminate\Http\Request;
use App\PaymentSystem;
use App\payment_log;
use Vinkla\Hashids\Facades\Hashids;
use App\AutoOrder;
use Auth;
use Carbon\Carbon;
use App\general_setting;
use App\User;
use DB;

class PaymentSystemController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            
            $user = Auth::user();
            
            $data = AutoOrder::where('pstatus','>=',7)
                    ->where(function ($q) use ($user){
                        if($user->userRole->name == 'Manager')
                        {
                            if($user->order_taker_quote == 1)
                            {
                                $q->where('manager_id',$user->id)->orWhere('order_taker_id',$user->id);
                            }
                        }
                        else if($user->userRole->name == 'Dispatcher')
                        {
                            if($user->order_taker_quote == 1)
                            {
                                $q->where('dispatcher_id',$user->id);
                            }
                        }
                        else if($user->userRole->name == 'Delivery Boy')
                        {
                            if($user->order_taker_quote == 1)
                            {
                                $q->where('delivery_boy_id',$user->id);
                            }
                        }
                        else if($user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent' )
                        {
                            if($user->order_taker_quote == 1)
                            {
                                $q->where('order_taker_id',$user->id);
                            }
                            else if($user->order_taker_quote == 2)
                            {
                                $q->whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$user->id]);
                            }
                        }
                    })->paginate(50);
            return view('main.payment_system.index',compact('data'));
        } else {
            return redirect('/loginn/');
        }
    }


    public function payment_system2(Request $request){

        $user = Auth::user();

        $setting = general_setting::orderby('id', 'desc')->first();

        $s = "01";
        $t = date('t');

        // if (isset($request->fromdate) && isset($request->todate)) {
        //     $from = $request->fromdate.'-'.$s;
        //     $too = $request->todate.'-'.$t;
        // } else {
        //     $from = date('Y-m').'-'.$s;
        //     $too =date('Y-m').'-'.$t;
        // }
        
        $from = Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00');
        $too = Carbon::now()->format('Y-m-d 23:59:59');
        if(isset($request->date_range) && !empty($request->date_range)) {
            $dates = explode(' - ', $request->date_range);
            $from = date('Y-m-d 00:00:00', strtotime($dates[0]));
            $too = date('Y-m-d 23:59:59', strtotime($dates[1]));
        }
        
        $paytype = '';
        if(isset($request->paytype))
        {
            $paytype = $request->paytype;
        }
        
        $pmethod = '';
        if(isset($request->pmethod))
        {
            $pmethod = $request->pmethod;
        }
        
        $total_amount = 0;
        if(isset($request->total_amount))
        {
            $total_amount = $request->total_amount;
        }


        if (isset($request->paid_status)) {
            $paid_status = $request->paid_status;
        } else {
            $paid_status = "";
        }
        if (isset($request->id)) {
            $id = $request->id;
        } else {
            $id = "";
        }

        if (isset($request->owes)) {
            $owes = $request->owes;
        } else {
            $owes = "";
        }

        if (isset($request->payment_method)) {
            $payment_method = $request->payment_method;
        } else {
            $payment_method = "";
        }
        
        $uid = "";
        $role = '';
        if (isset($request->user)) {
            $uid = $request->user;
            $uuser = User::with('userRole')->where('id',$uid)->first();
            if(isset($uuser->userRole->name))
            {
                $role = $uuser->userRole->name;
            }
        }

        $users = User::whereHas('userRole',function ($q){
            $q->where('name','Order Taker')
            ->orWhere('name','CSR')
            ->orWhere('name','Seller Agent')->orWhere('name','Manager');
        })->where('deleted',0)->get();

        $data = AutoOrder::with('new_status','new_status.user','orderTaker', 'book_status', 'book_status.user', 'lister', 'lister.user', 'dispatcher', 'dispatcher.user', 'completer', 'completer.user','orderpayment','dispatcher_user')
            ->where(function ($query) use ($from, $too, $paid_status, $owes, $payment_method,$id) {
                $query->whereBetween('date_of_booked', [$from, $too]);
                $query->where(function($q){
                    $q->where(function ($q2){
                        $q2->where('pstatus','>=',7)->where('pstatus','<=',14);
                    })->orWhere('pstatus',18);
                });
                if ($paid_status <> '')
                {
                    $query->where('paid_status','=', $paid_status);
                }
                if ($id <> '')
                {
                    $query->where('id','=', $id);
                }
                if (!empty($owes))
                {
                    $query->where('owes_money',1)->where('owes','>',0);
                }
                if (!empty($payment_method))
                {
                    $query->where('vehicle', $payment_method);
                }
            })
            ->where(function ($q) use ($user,$uid,$role){
                if(empty($uid) && empty($role))
                {
                    if($user->userRole->name == 'Manager')
                    {
                        if($user->order_taker_quote == 1)
                        {
                            $q->where('manager_id',$user->id)->orWhere('order_taker_id',$user->id);
                        }
                    }
                    else if($user->userRole->name == 'Dispatcher')
                    {
                        if($user->order_taker_quote == 1)
                        {
                            $q->where('dispatcher_id',$user->id);
                        }
                    }
                    else if($user->userRole->name == 'Delivery Boy')
                    {
                        if($user->order_taker_quote == 1)
                        {
                            $q->where('delivery_boy_id',$user->id);
                        }
                    }
                    else if($user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent' )
                    {
                        if($user->order_taker_quote == 1)
                        {
                            $q->where('u_id','=',$user->id);
                        }
                        else if($user->order_taker_quote == 2)
                        {
                            $q->whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$user->id])->orWhere('u_id','=',$user->id);
                        }
                        else
                        {
                            $q->where('u_id','=',$user->id);
                        }
                    }
                }
                else
                {
                    $q->where(function($q2)use($uid){
                        $q2->where('u_id','=',$uid);
                    });
                }
            })
            ->orderby('id', 'desc')
            ->paginate(50);


        $totaldata = AutoOrder::with('orderpayment2')
            ->where(function ($query) use ($from, $too, $paid_status, $owes, $payment_method,$id) {
                $query->whereBetween('date_of_booked', [$from, $too]);
                $query->where('pstatus','=','13');
                if ($paid_status <> '')
                {
                    $query->where('paid_status','=', $paid_status);
                }
                if ($id <> '')
                {
                    $query->where('id','=', $id);
                }
                if (!empty($owes))
                {
                    $query->where('owes_money',1)->where('owes','>',0);
                }
                if (!empty($payment_method))
                {
                    $query->where('vehicle', $payment_method);
                }
            })
            ->where(function ($q) use ($user,$uid,$role){
                if(empty($uid) && empty($role))
                {
                    if($user->userRole->name == 'Manager')
                    {
                        if($user->order_taker_quote == 1)
                        {
                            $q->where('manager_id',$user->id)->orWhere('order_taker_id',$user->id);
                        }
                    }
                    else if($user->userRole->name == 'Dispatcher')
                    {
                        if($user->order_taker_quote == 1)
                        {
                            $q->where('dispatcher_id',$user->id);
                        }
                    }
                    else if($user->userRole->name == 'Delivery Boy')
                    {
                        if($user->order_taker_quote == 1)
                        {
                            $q->where('delivery_boy_id',$user->id);
                        }
                    }
                    else if($user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent' )
                    {
                        if($user->order_taker_quote == 1)
                        {
                            $q->where('u_id','=',$user->id);
                        }
                        else if($user->order_taker_quote == 2)
                        {
                            $q->whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$user->id])->orWhere('u_id','=',$user->id);
                        }
                        else
                        {
                            $q->where('u_id','=',$user->id);
                        }
                    }
                }
                else
                {
                    $q->where(function($q2)use($uid){
                        $q2->where('u_id','=',$uid);
                    });
                }
            })
            ->select('id','payment','pay_carrier')
            ->get();
            
            $profit = 0;
            foreach($totaldata as $val)
            {
                if(isset($val->orderpayment2->profit))
                {
                    $profit = $profit + $val->orderpayment2->profit;
                }
                else
                {
                    if($val->payment > $val->pay_carrier)
                    {
                        $total = $val->payment - $val->pay_carrier;
                        $profit = $profit + $total;
                    }
                }
            }
            
            $totalorder = count($totaldata);
        if ($request->ajax()) {
            return view('main.payment_system.load',compact('data','profit','totalorder','total_amount','pmethod','paytype'));

        } else {

            return view('main.payment_system.index2',compact('data','users','profit','totalorder','total_amount','pmethod','paytype'));
        }


    }
    
    public function filter_payment()
    {
        return view('main.payment_system.filter');
    }

    public function create(Request $request)
    {
        $order = AutoOrder::find($request->id);
        
        if($request->count == 1)
        {
            $credit_card_data = DB::table('order')
            ->join('creditcards', 'creditcards.orderId', '=', 'order.id')
            ->where('order.main_ph', $order->main_ph)
            ->limit(100)
            ->count();
            
            return $credit_card_data;
        }
        $credit_card_data = DB::table('order')
        ->join('creditcards', 'creditcards.orderId', '=', 'order.id')
        ->where('order.main_ph', $order->main_ph)
        ->limit(100)
        ->get();
        
        return view('main.payment_system.cards',compact('credit_card_data'));
    }

    public function payment_log_amount(Request $request)
    {
        $from = Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00');
        $too = Carbon::now()->format('Y-m-d 23:59:59');
        
        if(isset($request->date_range) && !empty($request->date_range)) {
            $dates = explode(' - ', $request->date_range);
            $from = date('Y-m-d 00:00:00', strtotime($dates[0]));
            $too = date('Y-m-d 23:59:59', strtotime($dates[1]));
        }
        
        $data = payment_log::query();
        if(isset($request->paytype))
        {
            $data = $data->where('pay_type',$request->paytype);
        }
        if(isset($request->pmethod))
        {
            $data = $data->where('pay_method',$request->pmethod);
        }
        $data = $data->whereBetween('created_at',[$from,$too])
        ->sum('amount');
        
        $order = AutoOrder::with('payment_log2')->where(function ($query) use ($from, $too,$request) {
            $query->where(function($q){
                $q->where(function ($q2){
                    $q2->where('pstatus','>=',7)->where('pstatus','<=',14);
                })->orWhere('pstatus',18);
            });
            $query->whereHas('payment_log2',function($q) use ($request,$from,$too) {
                if(isset($request->paytype))
                {
                    $q->where('pay_type',$request->paytype);
                }
                if(isset($request->pmethod))
                {
                    $q->where('pay_method',$request->pmethod);
                }
                $q->whereBetween('created_at',[$from,$too]);
            });
        })
        ->orderby('id', 'desc')
        ->paginate(50);
        
        return view('main.payment_system.load_filter',compact('data','order'));
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $row = AutoOrder::findorfail($id);
        return view('main.payment_system.form',compact('row'));

    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'recived_date' => 'required',
            'recived_by' => 'required',
            'confirm_date' => 'required',
            'confirm_by' => 'required',
            'by_mang' => 'required',
            'additional' => 'required',
        ]);
        $row = AutoOrder::findOrFail($id);
        $row->fill($request->except('_method','_token'));
        $row->save();
        return redirect()->to(route('payment_system.index'));

    }

    public function destroy(Request $request,$id)
    {
        //
    }
}