<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AutoOrder;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Coupon;
use App\Mail\CouponMail;
use Mail;
use App\OrderFeedback;
use App\call_history;
use App\report;
use App\singlereport;
use App\count_day;
use App\OrderWebsiteEmail;
use App\general_setting;
use App\user_setting;
use App\QaVerifyHistory;

class CustomerRevenue extends Controller
{
    public function check_user_setting($user_id)
    {
        $p_type = 1;
        $usersetting = user_setting::where('user_id', '=', $user_id)->first();
        if (!empty($usersetting)) {
            $p_type = $usersetting['penal_type'];
        }
        return $p_type;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::check())
        {
            $id = Auth::user()->id;
            $ptype = $this->check_user_setting(Auth::user()->id);
            $from = Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00');
            $to = Carbon::now()->format('Y-m-d 23:59:59');
            
            $from2 = Carbon::now()->startOfMonth()->format('Y/m/d');
            $to2 = Carbon::now()->format('Y/m/d');
            
            $total_order = AutoOrder::whereBetween('created_at',[$from,$to])->where('paneltype', '=', $ptype);
            
            if(Auth::user()->userRole->name == 'Manager')
            {
                if(Auth::user()->order_taker_quote == 1)
                {
                    $total_order = $total_order->where('manager_id',Auth::user()->id)->orWhere('order_taker_id',Auth::user()->id);
                }
            }
            else if(Auth::user()->userRole->name == 'Dispatcher')
            {
                if(Auth::user()->order_taker_quote == 1)
                {
                    $total_order = $total_order->where('dispatcher_id',Auth::user()->id);
                }
            }
            else if(Auth::user()->userRole->name == 'Delivery Boy')
            {
                if(Auth::user()->order_taker_quote == 1)
                {
                    $total_order = $total_order->where('delivery_boy_id',Auth::user()->id);
                }
            }
            else if(Auth::user()->userRole->name == 'Order Taker' || Auth::user()->userRole->name == 'CSR' || Auth::user()->userRole->name == 'Seller Agent' )
            {
                if(Auth::user()->order_taker_quote == 1)
                {
                    $total_order = $total_order->where('order_taker_id',Auth::user()->id);
                }
                else if(Auth::user()->order_taker_quote == 2)
                {
                    $total_order = $total_order->whereRaw('FIND_IN_SET(?, manager_ot_ids)', [Auth::user()->id]);
                }
            }
            $total_order = $total_order->count();
            $users = User::whereHas('userRole',function($q){
                $q->where('name','CSR')
                ->orWhere('name','Seller Agent')
                ->orWhere('name','Order Taker')
                ->orWhere('name','Dispatcher')
                ->orWhere('name','Delivery Boy')
                ->orWhere('name','Manager');
            })->where('deleted',0)->orderBy('role','ASC')->get();
            return view('main.customer_revenue.index',compact('total_order','users','from2','to2','from','to'));
        }
        else
        {
            return redirect('/login');
        }
    }
    
    public function search(Request $request)
    {
        if(Auth::check())
        {
            $id = $request->user ?? '';
            $ptype = $this->check_user_setting(Auth::user()->id);
            $user = User::find($id);
            
            $from = Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00');
            $to = Carbon::now()->format('Y-m-d 23:59:59');
            
            if(isset($request->date_range) && !empty($request->date_range)) {
                $dates = explode(' - ', $request->date_range);
                $from = date('Y-m-d 00:00:00', strtotime($dates[0]));
                $to = date('Y-m-d 23:59:59', strtotime($dates[1]));
            }
            // echo "<pre>";
            // print_r($from);
            // print_r('<br>');
            // print_r($to);
            // exit();
            $total_order = AutoOrder::whereBetween('created_at',[$from,$to])->where('paneltype', '=', $ptype);
            if(isset($user->id))
            {
                if($user->userRole->name == 'Manager')
                {
                    $total_order = $total_order->where('manager_id',$user->id)->orWhere('order_taker_id',$user->id);
                }
                else if($user->userRole->name == 'Dispatcher')
                {
                    $total_order = $total_order->where('dispatcher_id',$user->id);
                }
                else if($user->userRole->name == 'Delivery Boy')
                {
                    $total_order = $total_order->where('delivery_boy_id',$user->id);
                }
                else if($user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent' )
                {
                    $total_order = $total_order->where('order_taker_id',$user->id);
                }
            }
            $total_order = $total_order->count();
            
            return view('main.customer_revenue.search',compact('total_order','from','to','id'));
        }
        else
        {
            return redirect('/login');
        }
    }
    
    
    public function index2()
    {
        if(Auth::check())
        {
            $id = Auth::user()->id;
            $ptype = $this->check_user_setting(Auth::user()->id);
            $from = Carbon::now()->firstOfMonth()->format('Y-m-d 00:00:00');
            $to = Carbon::now()->format('Y-m-d 23:59:59');
            
            $from2 = Carbon::now()->firstOfMonth()->format('Y/m/d');
            $to2 = Carbon::now()->format('Y/m/d');
            
            if(Auth::user()->userRole->name == 'Dispatcher')
            {
                if(Auth::user()->order_taker_quote == 1)
                {
                    $total_order = AutoOrder::where('dispatcher_id',$id)->whereBetween('created_at',[$from,$to])->where('paneltype', '=', $ptype)->count();
                }
                else
                {
                    $total_order = AutoOrder::where('dispatcher_id','<>',NULL)->whereBetween('created_at',[$from,$to])->where('paneltype', '=', $ptype)->count();
                }
            }
            else
            {
                $total_order = AutoOrder::where('dispatcher_id','<>',NULL)->whereBetween('created_at',[$from,$to])->where('paneltype', '=', $ptype)->count();
            }
            $users = User::whereHas('userRole',function($q){
                $q->where('name','Dispatcher');
            })->where('deleted',0)->orderBy('role','ASC')->get();
            return view('main.customer_revenue.dis_index',compact('total_order','users','from2','to2','from','to'));
        }
        else
        {
            return redirect('/login');
        }
    }
    
    public function search2(Request $request)
    {
        if(Auth::check())
        {
            $id = $request->user ?? '';
            $ptype = $this->check_user_setting(Auth::user()->id);
            $user = User::find($id);
            
            $from = Carbon::now()->firstOfMonth()->format('Y-m-d 00:00:00');
            $to = Carbon::now()->format('Y-m-d 23:59:59');
            
            if(isset($request->date_range) && !empty($request->date_range)) {
                $dates = explode(' - ', $request->date_range);
                $from = date('Y-m-d 00:00:00', strtotime($dates[0]));
                $to = date('Y-m-d 23:59:59', strtotime($dates[1]));
            }
            // echo "<pre>";
            // print_r($from);
            // print_r('<br>');
            // print_r($to);
            // exit();
            if(isset($user->id))
            {
                if($user->userRole->name == 'Dispatcher')
                {
                    $total_order = AutoOrder::where('dispatcher_id',$id)->whereBetween('created_at',[$from,$to])->where('paneltype', '=', $ptype)->count();
                }
                else
                {
                    $total_order = AutoOrder::where('dispatcher_id','<>',NULL)->whereBetween('created_at',[$from,$to])->where('paneltype', '=', $ptype)->count();
                }
            }
            else
            {
                $total_order = AutoOrder::where('dispatcher_id','<>',NULL)->whereBetween('created_at',[$from,$to])->where('paneltype', '=', $ptype)->count();
            }
            
            return view('main.customer_revenue.dis_search',compact('total_order','from','to','id'));
        }
        else
        {
            return redirect('/login');
        }
    }
    
    public function index3()
    {
        if(Auth::check())
        {
            $users = User::whereHas('userRole',function($q){
                $q->where('name','CSR')->orWhere('name','Seller Agent')->orWhere('name','Order Taker')->orWhere('name','Manager');
            })->where('deleted',0)->orderBy('role','ASC')->get();
            return view('main.customer_revenue.ot_index',compact('users'));
        }
        else
        {
            return redirect('/login');
        }
    }
    
    public function search3(Request $request)
    {
        if(Auth::check())
        {
            $id = $request->user ?? '';
            $ptype = $this->check_user_setting(Auth::user()->id);
            $user = User::find($id);
            
            $from = Carbon::now()->firstOfMonth()->format('Y-m-d 00:00:00');
            $to = Carbon::now()->format('Y-m-d 23:59:59');
            
            if(isset($request->date_range) && !empty($request->date_range)) {
                $dates = explode(' - ', $request->date_range);
                $from = date('Y-m-d 00:00:00', strtotime($dates[0]));
                $to = date('Y-m-d 23:59:59', strtotime($dates[1]));
            }
            // echo "<pre>";
            // print_r($from);
            // print_r('<br>');
            // print_r($to);
            // exit();
            $total_order = AutoOrder::whereBetween('created_at',[$from,$to])->where('paneltype', '=', $ptype);
            if(isset($user->id))
            {
                if($user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent' || $user->userRole->name == 'Order Taker')
                {
                    $total_order = $total_order->where('order_taker_id',$id);
                }
                else if($user->userRole->name == 'Manager')
                {
                    $total_order = $total_order->where('order_taker_id',$id)->orWhere('manager_id',$user->id);
                }
            }
            $total_order = $total_order->count();
            
            return view('main.customer_revenue.ot_search',compact('total_order','from','to','id'));
        }
        else
        {
            return redirect('/login');
        }
    }
    
    public function index4()
    {
        if(Auth::check())
        {
            $users = User::whereHas('userRole',function($q){
                $q->where('name','CSR')->orWhere('name','Seller Agent')->orWhere('name','Order Taker')->orWhere('name','Manager')->orWhere('name','Dispatcher');
            })->where('deleted',0)->orderBy('role','ASC')->get();
            return view('main.customer_revenue.index4',compact('users'));
        }
        else
        {
            return redirect('/login');
        }
    }
    
    public function search4(Request $request)
    {
        if(Auth::check())
        {
            $id = $request->user ?? '';
            $user = User::find($id);
            
            $from = Carbon::now()->firstOfMonth()->format('Y-m-d 00:00:00');
            $to = Carbon::now()->format('Y-m-d 23:59:59');
            
            if(isset($request->date_range) && !empty($request->date_range)) {
                $dates = explode(' - ', $request->date_range);
                $from = date('Y-m-d 00:00:00', strtotime($dates[0]));
                $to = date('Y-m-d 23:59:59', strtotime($dates[1]));
            }
            // echo "<pre>";
            // print_r($from);
            // print_r('<br>');
            // print_r($to);
            // exit();
            if(isset($user->id))
            {
                if($user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent' || $user->userRole->name == 'Order Taker' || $user->userRole->name == 'Manager')
                {
                    $total_order = QaVerifyHistory::whereHas('order',function ($q) use ($id){
                        $q->where('order_taker_id',$id);
                    })->whereBetween('created_at',[$from,$to])->count();
                    
                    $data = QaVerifyHistory::whereHas('order',function ($q) use ($id){
                        $q->where('order_taker_id',$id);
                    })->whereBetween('created_at',[$from,$to])->orderBy('created_at','DESC')->paginate(25);
                }
                else if($user->userRole->name == 'Dispatcher')
                {
                    $total_order = QaVerifyHistory::whereHas('order',function ($q) use ($id){
                        $q->where('dispatcher_id',$id);
                    })->whereBetween('created_at',[$from,$to])->count();
                    
                    $data = QaVerifyHistory::whereHas('order',function ($q) use ($id){
                        $q->where('dispatcher_id',$id);
                    })->whereBetween('created_at',[$from,$to])->orderBy('created_at','DESC')->paginate(25);
                }
                else
                {
                    $total_order = QaVerifyHistory::whereBetween('created_at',[$from,$to])->count();
                    
                    $data = QaVerifyHistory::whereBetween('created_at',[$from,$to])->orderBy('created_at','DESC')->paginate(25);
                }
            }
            else
            {
                $total_order = QaVerifyHistory::whereBetween('created_at',[$from,$to])->count();
                    
                $data = QaVerifyHistory::whereBetween('created_at',[$from,$to])->orderBy('created_at','DESC')->paginate(25);
            }
            
            return view('main.customer_revenue.search4',compact('total_order','from','to','id','data'));
        }
        else
        {
            return redirect('/login');
        }
    }
    
    public function total_records(Request $request)
    {
        if(Auth::check())
        {
            $id = $request->user ?? '';
            $user = User::find($id);
            
            $from = Carbon::now()->format('Y-m-d 00:00:00');
            $to = Carbon::now()->format('Y-m-d 23:59:59');
            
            if(isset($request->date_range) && !empty($request->date_range)) {
                $dates = explode(' - ', $request->date_range);
                $from = date('Y-m-d 00:00:00', strtotime($dates[0]));
                $to = date('Y-m-d 23:59:59', strtotime($dates[1]));
            }
            // echo "<pre>";
            // print_r($from);
            // print_r('<br>');
            // print_r($to);
            // exit();
            if(isset($user->id))
            {
                if($user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent' || $user->userRole->name == 'Order Taker' || $user->userRole->name == 'Manager')
                {
                    $data = QaVerifyHistory::whereHas('order',function ($q) use ($id){
                        $q->where('order_taker_id',$id);
                    })->whereBetween('created_at',[$from,$to])->orderBy('created_at','DESC')->paginate(25);
                }
                else if($user->userRole->name == 'Dispatcher')
                {
                    $data = QaVerifyHistory::whereHas('order',function ($q) use ($id){
                        $q->where('dispatcher_id',$id);
                    })->whereBetween('created_at',[$from,$to])->orderBy('created_at','DESC')->paginate(25);
                }
                else
                {
                    $data = QaVerifyHistory::whereBetween('created_at',[$from,$to])->orderBy('created_at','DESC')->paginate(25);
                }
            }
            else
            {
                $data = QaVerifyHistory::whereBetween('created_at',[$from,$to])->orderBy('created_at','DESC')->paginate(25);
            }
            
            return view('main.customer_revenue.total_records',compact('data'));
        }
        else
        {
            return redirect('/login');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function coupon_number(Request $request)
    {
        $coupon = Coupon::where('coupon_number',$request->coupon_number)->first();
        if(isset($coupon->id))
        {
            if($coupon->status == 1)
            {
                return response()->json([
                    'err' => 'This coupon had used!',
                    'status' => false,
                    'status_code' => 400
                ]);
            }
            else
            {
                return response()->json([
                    'msg' => '$'.$coupon->coupon_price.' Coupon applied!',
                    'status' => true,
                    'status_code' => 200
                ]);
            }
        }
        else
        {
            return response()->json([
                'err' => 'Wrong coupon number!',
                'status' => false,
                'status_code' => 400
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'coupon_number' => 'required|unique:coupons,coupon_number|max:10|min:6',
            'coupon_price' => 'required|numeric|between:0,999999.99',
            'coupon_email' => 'required|email',    
        ]);
        
        if($validator->passes())
        {
            $coupon = new Coupon;
            $coupon->coupon_number = $request->coupon_number;
            $coupon->coupon_price = $request->coupon_price;
            $coupon->coupon_email = $request->coupon_email;
            $coupon->status = 0;
            $coupon->save();
            
            $details = [
                'price'=> $request->coupon_price,
                'coupon'=> $request->coupon_number,
                'email'=> $request->coupon_email,
                'title'=> 'You got a coupon. You can use this coupon one time.'
            ];
            
            Mail::to($request->coupon_email)->send(new CouponMail($details));
            
            return response()->json([
                'message'=> "Save",
                'status'=> true,
                'status_code'=> 200
            ]);
            
        }
        else
        {
            return response()->json([
                'error'=> $validator->errors(),
                'status'=> false,
                'status_code'=> 400
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $coupons = Coupon::where(function($q) use ($request){
            $q->where('coupon_number','LIKE','%'.$request->search.'%')
            ->orWhere('coupon_price','LIKE','%'.$request->search.'%')
            ->orWhere('coupon_email','LIKE','%'.$request->search.'%');
        })->orderBy('created_at','DESC')->paginate(10);
        
        if($request->ajax())
        {
            return view('main.coupon.search',compact('coupons'));
        }
        
        return view('main.coupon.index',compact('coupons'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     
    public function expected_date($order_id, $user_id, $pstatus, $expected_date)
    {

        if (!empty($expected_date)) {
            $count_save = count_day::where('order_id', '=', $order_id)
                ->first();
            if ($count_save) {

                $count_save->user_id = $user_id;
                $count_save->expected_date = $expected_date;
                $count_save->pstatus = $pstatus;
                $count_save->save();

            } else {

                $count_save = new count_day();
                $count_save->user_id = $user_id;
                $count_save->order_id = $order_id;
                $count_save->expected_date = $expected_date;
                $count_save->pstatus = $pstatus;
                $count_save->save();
            }
        }

    }
    
    public function feedbackStore(Request $request)
    {
        
        $validator = Validator::make($request->all(),[
            'feedback' => 'required',
            'rate' => 'required',    
        ]);
        
        if($validator->passes())
        {
            $feedback = new OrderFeedback;
            $feedback->order_id = $request->order_id;
            $feedback->feedback = $request->feedback;
            $feedback->rate = $request->rate;
            $feedback->link_click = 0;
            $feedback->user_id = Auth::user()->id;
            $feedback->save();
            
            // $order = AutoOrder::find($request->order_id);
            // $order->pstatus = 13;
            // $order->completer_id = Auth::user()->id;
            // $order->save();
            // $this->expected_date($request->order_id, Auth::user()->id, 13, date('Y-m-d'));
            
            // $callhistory = new call_history();
            // $callhistory->userId = Auth::user()->id;
            // $callhistory->orderId = $request->order_id;
            // $callhistory->pstatus = 13;
            // $callhistory->history = "<h6>LAST STATUS : DELIVERED</h6><h6>Remarks: COMPLETED</h6>";
            // $callhistory->save();

            // $autoorderreport = new report();
            // $autoorderreport->userId = Auth::user()->id;
            // $autoorderreport->orderId = $request->order_id;
            // $autoorderreport->pstatus = 13;
            // $autoorderreport->save();

            // $singlereport = singlereport::where('orderId', '=', $request->order_id)->first();
            // if ($singlereport == '') {
            //     $singlerreportadd = new singlereport();
            //     $singlerreportadd->userId = Auth::user()->id;
            //     $singlerreportadd->orderId = $request->order_id;
            //     $singlerreportadd->pstatus = 13;
            //     $singlerreportadd->save();
            // } else {
            //     $singlereport->pstatus = 13;
            //     $singlereport->userId = Auth::user()->id;
            //     $singlereport->save();
            // }

            return response()->json([
                'message'=> "Save",
                'status'=> true,
                'status_code'=> 200
            ]);
            
        }
        else
        {
            return response()->json([
                'error'=> $validator->errors(),
                'status'=> false,
                'status_code'=> 400
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function feedbackGet(Request $request)
    {
        $feedback = OrderFeedback::where('order_id',$request->id)->first();
        $email = OrderWebsiteEmail::where('order_id',$request->id)->first();
        
        return response()->json([
            'feedback'=>$feedback,
            'email'=>$email,
            'status'=>true,
            'status_code'=>200
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function feedback(Request $request)
    {
        $setting = general_setting::first();
        $order = AutoOrder::with(['feedback','email'])
        ->withCount('feedback')
        ->where(function ($q1) use ($request){
            $q1->where(function($q)use($request){
                if($request->search == "Negative" || $request->search == "negative" || $request->search == "Neg" || $request->search == "neg")
                {
                    $q->whereHas('feedback',function($q2)use($request){
                        $q2->where('rate',0)->orWhere('rate',1);
                    });
                }
                else if($request->search == "Positive" || $request->search == "positive" || $request->search == "Pos" || $request->search == "pos")
                {
                    $q->whereHas('feedback',function($q2)use($request){
                        $q2->where('rate',4)->orWhere('rate',5);
                    });
                }
                else if($request->search == "Neutral" || $request->search == "neutral" || $request->search == "Neu" || $request->search == "neu")
                {
                    $q->whereHas('feedback',function($q2)use($request){
                        $q2->where('rate',2)->orWhere('rate',3);
                    });
                }
                else if($request->search == "No Review" || $request->search == "no review" || $request->search == "No review" || $request->search == "no Review"|| $request->search == "No"|| $request->search == "no")
                {
                    $q->doesntHave('feedback');
                }
            })->orWhere('id','LIKE','%'.$request->search.'%');
        })
        ->where('pstatus',13)
        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
        ->orderBy('feedback_count','DESC')
        ->orderBy('updated_at','DESC');
        
        if($request->has('status')) 
        {
            if($request->status == 'Without Review') 
            {
                $order = $order->doesntHave('feedback');
            }
            elseif($request->status == 'With Review') 
            {
                $order = $order->has('feedback');
            }
        }
        
        $order = $order->paginate(20);
        
        if($request->ajax())
        {
            return view('main.feedback.search',compact('order'));
        }
        
        $avg = OrderFeedback::avg('rate');
        
        // echo "<pre>";print_r($order->toArray());exit();
        return view('main.feedback.index',compact('order','avg'));
    }
    
    public function jd_report()
    {
        if(Auth::user()->userRole->name == 'Admin' || Auth::user()->userRole->name == 'Dispatcher')
        {
            return view('main.phone_quote.jd.index');
        }
        else
        {
            return back();
        }
    }
}
