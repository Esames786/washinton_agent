<?php

namespace App\Http\Controllers\phone_quote\customer;

use App\call_history;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\role;
use App\AutoOrder;
use App\TransferQuote;
use App\report;
use App\zipcodes;
use App\count_click;
use App\carrier;
use App\creditcard;

use Session;
use Redirect;
use Hash;
use Mail;
use Auth;
use DB;
use App\count_day;
use Carbon\Carbon;
use App\ReportPassword;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Validator;
use App\AutoOrderHistory;
use App\MessageCall;
use App\user_setting;
use App\UserScreenShot;
use App\general_setting;

class CustomerController extends Controller
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

    public function customer_list()
    {
        $data = AutoOrder::whereNotNull('ophone')
        ->groupBy('ophone')
        ->orderBy('oname','ASC')
        ->select('id','oname','ophone','oemail','destinationzsc','paneltype')
        ->paginate(50);
        $display = "no";
        $hide = "yes";
        $value = '';
        return view('main.phone_quote.customer.customerlist', compact('data','display','hide','value'));
    }
    
    public function redirect_customer_list()
    {
        $data = AutoOrder::whereNotNull('ophone')
        ->groupBy('ophone')
        ->orderBy('oname','ASC')
        ->select('id','oname','ophone','oemail','destinationzsc','paneltype')
        ->paginate(50);
        $display = "no";
        $hide = "yes";
        $value = '';
        
        return redirect('/customer_list')->with(['data'=>$data,'display'=>$display,'hide'=>$hide,'value'=>$value]);
        
    }
    
    public function customer_list_2(Request $request)
    {
        $data = AutoOrder::whereNotNull('ophone')
        ->groupBy('ophone')
        ->orderBy('oname','ASC')
        ->select('id','oname','ophone','oemail','destinationzsc','paneltype')
        ->paginate(50);
        $display = 'no';
        $hide = "yes";
        $value = '';

        if (isset($request->pass)) {
            $check = \App\ReportPassword::first();
            if (!empty($check)) {
                if(Hash::check($request->pass,$check->password))
                {
                    $display = "yes";
                    $hide = "no";
                    $value = '';
                }
            }
            
            return view('main.phone_quote.customer.customerlist', compact('data','display','hide','value'));
        } else {
            return redirect('/customer_list')->with('error','Your Password is wrong!');
        }
        
    }
    
    public function customer_data(Request $request)
    {
        $entity = $request->entity ?? 50;
        $value = $request->value ?? '';
        $sort = $request->sort ?? 'ASC';

        $data = AutoOrder::where(function($q) use ($request){
            $q->where($request->search_by,'LIKE',$request->value.'%');
            if(isset($request->from) && isset($request->too)){
                $q->WhereBetween('created_at',[$request->from,$request->too]);
            }
            if(isset($request->panel_type2) && !empty($request->panel_type2)){
                $q->where('paneltype',$request->panel_type2);
            }
        })
        ->whereNotNull('ophone')
        ->groupBy('ophone')
        ->select('id','oname','ophone','oemail','destinationzsc','paneltype')
        ->orderBy('oname',$sort)
        ->paginate($entity);
        $display = "yes";
        $hide = "no";
        
        return view('main.phone_quote.customer.load',compact('data','display','hide','value')); 
    }
    
    public function customerOrderHistoryUpdate(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());
        // exit();
        $validation = Validator::make($request->all(),[
            'history' => 'required'
        ]);
        
        if($validation->passes())
        {
            $data = new AutoOrderHistory;
            $data->order_id = $request->id;
            $data->history = $request->history;
            $data->save();
            
            return response()->json([
                'data' => "Save history",
                'status' => true,
                'status_code' => 200
            ]);
        }
        else
        {
            return response()->json([
                'error' => $validation->errors(),
                'status' => false,
                'status_code' => 400
            ]);
        }
    }
    
    public function customerOrderHistoryShow(Request $request)
    {
        $data = AutoOrderHistory::where('order_id',$request->id)->get();
          
         return view('main.phone_quote.customer.history',compact('data'));
    }
    
    public function addMsgCall(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'date_time'=>'required',
            'description'=>'required'
        ]);
        
        if($validator->passes())
        {
            $data = new MessageCall;
            $data->user_id = Auth::user()->id;
            $data->order_id = $request->order_id;
            $data->date_time = $request->date_time;
            $data->cname = $request->cname;
            $data->cphone = $request->cphone;
            $data->status = $request->status;
            if($request->messageReply == 'Your Message')
            {
                $data->description = $request->description;
            }
            if($request->messageReply == 'Client Reply')
            {
                $data->reply = $request->description;
            }
            $data->save();
            
            return response()->json([
                'data' => "Save history",
                'status' => true,
                'status_code' => 200
            ]);
        }
        else
        {
            return response()->json([
                'error' => $validator->errors(),
                'status' => false,
                'status_code' => 400
            ]);
        }
    }
    
    public function showMsgCall(Request $request)
    {
        $status = $request->status;
        $data = MessageCall::where('order_id',$request->order_id)
        ->where('status',$status)
        ->orderBy('date_time','ASC')
        ->get();
          
         return view('main.phone_quote.new.message-call',compact('data','status'));
    }
    
    public function credit_card_list(Request $request)
    {
        $data = creditcard::where(function ($q){
          $q->where('card_no','<>','^*-')->orWhere('card_no','<>','^*')->orWhere('card_no','<>',NULL);
        })
        ->where(function($q) use ($request){
            $q->where('orderId','LIKE','%'.$request->search.'%')
            ->orWhere('card_first_name','LIKE','%'.$request->search.'%')
            ->orWhere('card_last_name','LIKE','%'.$request->search.'%')
            ->orWhere('billing_address','LIKE','%'.$request->search.'%')
            ->orWhere('b_zip','LIKE','%'.$request->search.'%')
            ->orWhere('card_no','LIKE','%'.$request->search.'%')
            ->orWhere('card_expiry_date','LIKE','%'.$request->search.'%')
            ->orWhere('card_security','LIKE','%'.$request->search.'%');
        })
        ->orderBy('id','DESC')->paginate(50);
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
        if ($request->ajax()) {

            $display = 'yes';
            return view('main.phone_quote.credit_card.load', compact('data','display'))->render();
        } else {
            return view('main.phone_quote.credit_card.creditcardlist', compact('data','display'));
        }
    }
    
    public function getCard(Request $request)
    {
        $data = AutoOrder::where('card_number','<>',NULL)
        ->select('id','card_name','card_last_name','billing_address','b_state','b_city','b_zip','card_type','card_number','card_exp','card_sec')
        ->skip($request->skip)
        ->take($request->take)
        ->get();
        
        if($data)
        {
            foreach($data as $key => $val)
            {
                $cc = new creditcard;
                $cc->orderId = $val->id;
                $cc->card_first_name = $val->card_name;
                $cc->card_last_name = $val->card_last_name;
                $cc->billing_address = $val->billing_address;
                $cc->b_city = $val->b_city;
                $cc->b_state = $val->b_state;
                $cc->b_zip = $val->b_zip;
                $cc->b_zsc = $val->b_zip.', '.$val->b_state.', '.$val->b_city;
                $cc->card_no = $val->card_number;
                $cc->card_expiry_date = $val->card_exp;
                $cc->card_security = $val->card_sec;
                $cc->card_type = $val->card_type;
                $cc->save();
            }
        }
        
        return response()->json([
            'data'=>$data,
            'status'=>true,
            'status_code'=>200
        ]);
    }
    
    public function transferQuotes()
    {
        $user = Auth::user();
        $ptype = $this->check_user_setting($user->id);
        $setting = general_setting::first();
        
        $order_taker = User::where('deleted',0)
        ->whereHas('userRole',function($q){
            $q->where('name','CSR')->orWhere('name','Seller Agent')->orWhere('name','Order Taker')->orWhere('name','Manager');
        })
        ->orderBy('id', 'desc')->get();
        $dispatcher = User::where('deleted',0)
        ->whereHas('userRole',function($q){
            $q->where('name','Dispatcher');
        })
        ->orderBy('id', 'desc')->get();
        
        if(isset($order_taker[0]))
        {
            foreach($order_taker as $key => $val)
            {
                $order_taker[$key]->transfer_count = AutoOrder::where('order_taker_id',$val->id)->where('paneltype', '=', $ptype)->where('pstatus',0)
                ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))->whereHas('transfer')->count();
                $order_taker[$key]->order_count = AutoOrder::where('order_taker_id',$val->id)->where('paneltype', '=', $ptype)
                ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))->where('pstatus',0)->doesntHave('transfer')->count();
            }
        }
        if(isset($dispatcher[0]))
        {
            foreach($dispatcher as $key => $val)
            {
                $dispatcher[$key]->transfer_count = AutoOrder::where('dispatcher_id',$val->id)->where('paneltype', '=', $ptype)->where('pstatus',9)
                ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))->whereHas('transfer')->count();
                $dispatcher[$key]->order_count = AutoOrder::where('dispatcher_id',$val->id)->where('paneltype', '=', $ptype)->where('pstatus',9)
                ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))->doesntHave('transfer')->count();
            }
        }
        if (Auth::check()) {
            return view('main.phone_quote.transfer-quotes.index', compact('dispatcher','order_taker'));
        } else {
            return redirect('/loginn/');
        }
    }
    
    public function searchForRevert(Request $request)
    {
        $user = User::find($request->id);
        $ouser = User::find($request->user_id);
        if(isset($ouser->id))
        {
            $role = 'order_taker_id';
            $original = 'original_user_id';
            $transfer = 'transferred_user_id';
            if($user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent' || $user->userRole->name == 'Order Taker' || $user->userRole->name == 'Manager')
            {
                $role = 'order_taker_id';
                $original = 'original_user_id';
                $transfer = 'transferred_user_id';
            }
            if($user->userRole->name == 'Dispatcher')
            {
                $role = 'dispatcher_id';
                $original = 'original_dispatcher_id';
                $transfer = 'transfer_dispatcher_id';
            }
            
            $data = TransferQuote::with('order')->where($transfer,$request->id)
            ->where($original,$request->user_id);
            if(isset($request->from_date))
            {
                $data = $data->whereDate('created_at','>=',$request->from_date)->whereDate('created_at','>=',$request->to_date);
            }
            else
            {
                $data = $data->whereDate('created_at',$request->to_date);
            }
            $data = $data->whereHas('order',function($q) use ($request,$role){
                $q->where($role,$request->id);
            })->orderBy('id','DESC')->paginate(25);
            
            return view('main.phone_quote.transfer-quotes.revert', compact('data','user','ouser'));
        }
        else
        {
            return back()->with('err','No Revert quotes found!');
        }
        
    }
    
    public function searchOtDis(Request $request)
    {
        $user = User::find($request->id);
        $data = '';
        $role = '';
        if(isset($user->id))
        {
            $role = role::find($user->role);
            
            $data = User::where('id','<>',$user->id)->where('deleted',0);
            if($role->name == 'Dispatcher')
            {
                $data = $data->whereHas('userRole',function ($q){
                    $q->where('name','Dispatcher');
                });
                $original = 'original_dispatcher_id';
                $transfer = 'transfer_dispatcher_id';
            }
            else{
                $data = $data->whereHas('userRole',function ($q){
                    $q->where('name','Order Taker')
                    ->orWhere('name','Seller Agent')
                    ->orWhere('name','CSR')->orWhere('name','Manager');
                });
                $original = 'original_user_id';
                $transfer = 'transferred_user_id';
            }
            $data = $data->get();
        }
        
        if(isset($data[0]))
        {
            foreach($data as $key => $val)
            {
                $data[$key]->revert_count = TransferQuote::where($original,$val->id)->where($transfer,$user->id)->count();
            }
        }
        
        // echo "<pre>";
        // print_r($data);
        // exit();
        
        return response()->json([
            'data'=>$data,
            'role'=>$role,
            'status'=>true,
            'status_code'=>200
        ]);
    }
    
    public function searchOtDis2(Request $request)
    {
        $user = User::find($request->id);
        $data = '';
        $role = '';
        if(isset($user->id))
        {
            $role = role::find($user->role);
            $data = User::where('id','<>',$user->id)->where('deleted',0);
            if($role->name == 'Dispatcher')
            {
                $data = $data->whereHas('userRole',function ($q){
                    $q->where('name','Dispatcher');
                });
                $original = 'original_dispatcher_id';
                $transfer = 'transfer_dispatcher_id';
            }
            else{
                $data = $data->whereHas('userRole',function ($q){
                    $q->where('name','Order Taker')
                    ->orWhere('name','Seller Agent')
                    ->orWhere('name','CSR')->orWhere('name','Manager');
                });
                $original = 'original_user_id';
                $transfer = 'transferred_user_id';
            }
            $data = $data->get();
        }
        
        if(isset($data[0]))
        {
            foreach($data as $key => $val)
            {
                $trans = TransferQuote::where($original,$val->id)->where($transfer,$user->id);
                if(isset($request->from_date))
                {
                    $trans = $trans->whereDate('created_at','>=',$request->from_date)->whereDate('created_at','>=',$request->to_date);
                }
                else
                {
                    $trans = $trans->whereDate('created_at',$request->to_date);
                }
                $trans = $trans->count();
                $data[$key]->revert_count = $trans;
                
            }
        }
        
        // echo "<pre>";
        // print_r($data);
        // exit();
        
        return response()->json([
            'data'=>$data,
            'role'=>$role,
            'status'=>true,
            'status_code'=>200
        ]);
    }
    
    public function transferQuotesStore(Request $request)
    {
        $otdis = '';
        $data = '';
        $oname = '';
        $tname = '';
        $user = Auth::user();
        $ptype = $this->check_user_setting($user->id);
        if(isset($request->id))
        {
            $user = User::find($request->id);
            if(isset($user))
            {
                $data = AutoOrder::where('paneltype', '=', $ptype);
                if($user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent' || $user->userRole->name == 'Order Taker' || $user->userRole->name == 'Manager')
                {
                    $data = $data->where('pstatus',0)->where('order_taker_id',$user->id);
                    $otdis = "OT";
                }
                else if($user->userRole->name == 'Dispatcher')
                {
                    $data = $data->where('pstatus',9)->where('dispatcher_id',$user->id);
                    $otdis = "Dis";
                }
                if(isset($request->from_date))
                {
                    $data = $data->whereDate('updated_at','>=',$request->from_date)->whereDate('updated_at','>=',$request->to_date);
                }
                else
                {
                    $data = $data->whereDate('updated_at',$request->to_date);
                }
                $data = $data->get();
                
                $anotherUser = User::find($request->user_id);
                
                $oname = $user->slug ?? ($user->name ? $user->name.' '.$user->last_name : '');
                $tname = $anotherUser->slug ?? ($anotherUser->name ? $anotherUser->name.' '.$anotherUser->last_name : '');
                
                $msg = 'The '.$oname.' Qoutes has been transferred to the '.$tname.'.';
            }
        }
        
        if(isset($data[0]))
        {
            foreach($data as $key => $val)
            {
                $trans = new TransferQuote;
                $trans->order_id = $val->id;
                if($otdis == "OT")
                {
                    $trans->original_user_id = $val->order_taker_id; 
                    $trans->transferred_user_id = $request->user_id; 
                }
                if($otdis == "Dis")
                {
                    $trans->original_dispatcher_id = $val->order_taker_id; 
                    $trans->transfer_dispatcher_id = $request->user_id; 
                }
                $trans->old_pstatus = $val->pstatus; 
                $trans->save();
                
                $order = AutoOrder::find($val->id);
                if($otdis == "OT")
                {
                    $order->order_taker_id = $request->user_id; 
                }
                if($otdis == "Dis")
                {
                    $order->dispatcher_id = $request->user_id;
                }
                $order->save();
                
            }
        }
        
        if($oname && $tname)
        {
            return back()->with('msg',$msg);
        }
        else
        {
            return back()->with('err','Something went wrong!');
        }
        
    }
    
    
    public function transferSingleQuotesStore(Request $request)
    {
        $order = AutoOrder::find($request->order_id);
                
        $ooname = User::with('userRole')->where('id',$request->id)->first();
        $ttname = User::with('userRole')->where('id',$request->user_id)->first();
        
        $oname = $ooname->slug ?? ($ooname->name ? $ooname->name.' '.$ooname->last_name : '');
        $tname = $ttname->slug ?? ($ttname->name ? $ttname->name.' '.$ttname->last_name : '');
        if(isset($order->id))
        {
            if($ooname->userRole->name == 'Dispatcher')
            {
                if($order->dispatcher_id == $request->id)
                {
                    
                    $trans = new TransferQuote;
                    $trans->order_id = $order->id;
                    $trans->original_dispatcher_id = $order->dispatcher_id; 
                    $trans->transfer_dispatcher_id = $request->user_id; 
                    $trans->old_pstatus = $order->pstatus; 
                    $trans->save();
                    
                    $order->dispatcher_id = $request->user_id;
                    $order->save();
                    
                    $msg = 'The '.$oname.' Qoute has been transferred to the '.$tname.'.';
                    
                    return back()->with('msg',$msg);
                }
                else
                {
                    return back()->with('err','The Order ID#'.$request->order_id.' is not belongs to '.$oname);
                }   
            }
            else
            {
                if($order->order_taker_id == $request->id)
                {
                    
                    $trans = new TransferQuote;
                    $trans->order_id = $order->id;
                    $trans->original_user_id = $order->order_taker_id; 
                    $trans->transferred_user_id = $request->user_id; 
                    $trans->old_pstatus = $order->pstatus; 
                    $trans->save();
                    
                    $order->order_taker_id = $request->user_id;
                    $order->save();
                    
                    $msg = 'The '.$oname.' Qoute has been transferred to the '.$tname.'.';
                    
                    return back()->with('msg',$msg);
                }
                else
                {
                    return back()->with('err','The Order ID#'.$request->order_id.' is not belongs to '.$oname);
                }
            }
        }
        else
        {
            return back()->with('err','Order ID#'.$request->order_id.' is wrong!');
        }
    }
    
    public function revertTheQuotes(Request $request)
    {
        if(isset($request->transfer_quote_id))
        {
            $data = TransferQuote::whereIn('id',$request->transfer_quote_id)->get();
            
            if(isset($data[0]))
            {
                foreach($data as $key => $val)
                {
                    if($val->original_user_id)
                    {
                        $user = User::with('userRole')->where('id',$val->original_user_id)->first();
                    }
                    else
                    {
                        $user = User::with('userRole')->where('id',$val->original_dispatcher_id)->first();
                    }
                    $order = AutoOrder::find($val->order_id);
                    if(isset($user->id))
                    {
                        if($user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent' || $user->userRole->name == 'Order Taker' || $user->userRole->name == 'Manager')
                        {
                            $order->order_taker_id = $val->original_user_id;
                        }
                        if($user->userRole->name == 'Dispatcher')
                        {
                            $order->dispatcher_id = $val->original_dispatcher_id;
                        }
                    }
                    $order->save();
                    
                    TransferQuote::where('id',$val->id)->delete();
                }
                return redirect('/transfer-quotes')->with('msg','The quotes has been revert to the original employee!');
            }
        }
        
        return redirect('/transfer-quotes')->with('err','No revert quotes found!');
        
    }
    
    public function time_user()
    {
        $time = 0;
        $allUsers = User::where('is_login',1)->where('deleted',0)->get();
        if(isset($allUsers[0]))
        {
            foreach($allUsers as $key => $val)
            {
                $mins = now()->diffInMinutes($val->is_time);
                if($mins > 600)
                {
                    $update = User::find($val->id);
                    $update->is_login = 0;
                    $update->save();
                    
                }
            }
        }
        if(Auth::check())
        {
            $user = Auth::user();
        
            $time = now()->diffInSeconds(Auth::user()->ss_time);
        }
        return response()->json([
            'time'=>$time,
            'status'=>true,
            'status_code'=>200
        ]);
    }
    
    public function auto_screenshot(Request $request)
    {
        $time = 0;
        if(Auth::check())
        {
            $user = Auth::user();
            $time = now()->diffInSeconds($user->ss_time);
            if($time >= 270)
            {
                $data = new UserScreenShot;
                $data->user_id = $user->id;
                $data->image_url = $request->image;
                $data->save();
                
                $user->ss_time = now();
                $user->save();
                
                UserScreenShot::whereDate('created_at', '<=', Carbon::now()->subDays(30))->delete();
                
            }
            
        }
        return "Screen Shot";
    }
}
