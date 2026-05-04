<?php

namespace App\Http\Controllers\phone_quote\Question;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Question;
use App\AutoOrder;
use App\QuestionAnwser;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\QNAOrder;
use App\PortDetail;
use App\NotRespondOrder;
use Auth;
use App\SheetDetails;
use App\user_setting;
use App\User;
use App\CustomChat;
use App\ChatShowHide;
use App\PublicOrder;
use App\PublicOrderChat;
use App\RunTimeChat;
use App\general_setting;

class QuestionController extends Controller
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

    public function get()
    {
        $ques = Question::orderBy('id','desc')->get();
        if($ques)
        {
            foreach($ques as $key => $value)
            {
                $ques[$key]->date_time = Carbon::parse($value->created_at)->format('M,d Y h:i:s A');
            }
        }
        return response()->json([
            'ques' => $ques,
            'status' => true,
            'status_code' => 200
        ]);
    }

    public function index()
    {
        return view('main.phone_quote.question.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());
        // exit();
        $validator = Validator::make($request->all(),[
            'question'=>'required',
            'anwser.*'=>'required'
        ]);
        if($validator->passes()) {
            $q = new Question;
            $q->question = $request->question;
            $q->save();

            foreach($request->anwser as $anwser) 
            {
                $a = new QuestionAnwser;
                $a->q_id = $q->id;
                $a->anwser = $anwser;
                $a->save();
            }
            return response()->json([
                'message' => 'Question added successfully!',
                'status' => true,
                'status_code' => 200
            ]);
        }
        else{
            return response()->json([
                'message' => $validator->errors(),
                'status' => false,
                'status_code' => 400
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
        $ques = Question::with('ans')
        ->where('id', $request->id)->first();
        return response()->json([
            'ques' => $ques,
            'status' => true,
            'status_code' => 200
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $ques = Question::with('ans')
        ->where('id', $request->id)->first();
        return response()->json([
            'ques' => $ques,
            'status' => true,
            'status_code' => 200
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $ans = [];
        $ans = array_unique($request->anwser);
        // echo "<pre>";
        // print_r($ans);
        // exit();
        $validator = Validator::make($request->all(),[
            'question'=>'required',
            'anwser.*'=>'required'
        ]);
        if($validator->passes()) {
            $q = Question::find($request->id);
            $q->question = $request->question;
            $q->save();
                
            if($ans)
            {
                QuestionAnwser::where('q_id',$request->id)->delete();
                foreach($ans as $anwser) 
                {
                    $a = new QuestionAnwser;
                    $a->q_id = $q->id;
                    $a->anwser = $anwser;
                    $a->save();
                }
            }
            return response()->json([
                'message' => 'Question updated successfully!',
                'status' => true,
                'status_code' => 200
            ]);
        }
        else{
            return response()->json([
                'message' => $validator->errors(),
                'status' => false,
                'status_code' => 400
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $ques = Question::find($request->id);
        if($ques->status == 1)
        {
            $ques->status = 0;
            $msg = 'disabled';
        }
        else{
            $ques->status = 1;
            $msg = 'activated';
        }
        $ques->save();
        return response()->json([
            'message' => 'Question '.$msg.' successfully!',
            'status' => true,
            'status_code' => 200
        ]);

    }

    public function showData()
    {
        $route = \Request::segment(2);
        $data = '';
        return view('main.phone_quote.question.show-data',compact('data','route'));
    }

    public function searchFilter(Request $request)
    {
        $user = Auth::user();
        $ptype = $this->check_user_setting(Auth::user()->id);
        $setting = general_setting::first();
        $global_search = $request->global_search;
        $page = $request->page;
        $limit = $request->limit;
        $searchIn = $request->searchIn;
        $search = $request->search;
        $route = $request->route;
        if($route == 'listed')
        {
            $data = AutoOrder::with('qna','sheet.user')->where('pstatus',9)
            ->where('paneltype', '=', $ptype)
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
            })
            ->where($searchIn,'LIKE','%'.$search.'%')
            ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
            ->orderBy('id', 'DESC')->paginate($limit);
        }
        elseif($route == 'schedule')
        {
            $data = AutoOrder::with('qna','sheet.user')->where('pstatus',10)
            ->where('paneltype', '=', $ptype)
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
            })
            ->where($searchIn,'LIKE','%'.$search.'%')
            ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
            ->orderBy('id', 'DESC')->paginate($limit);
        }
        elseif ($route == 'not-pickedup')
        {
            $data = AutoOrder::with('qna','sheet.user')->where('pstatus',11)
            ->where('paneltype', '=', $ptype)
            ->where(function($q){
                $q
                ->where('approve_pickup',0)
                ->orWhere('approve_pickup',NULL);
            })
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
            })
            ->where($searchIn,'LIKE','%'.$search.'%')
            ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
            ->orderBy('id', 'DESC')->paginate($limit);
        }
        elseif($route == 'pickedup')
        {
            $data = AutoOrder::with('qna','sheet.user')->where('pstatus',11)
            ->where('paneltype', '=', $ptype)
            ->where('approve_pickup',1)
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
            })
            ->where($searchIn,'LIKE','%'.$search.'%')
            ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
            ->orderBy('id', 'DESC')->paginate($limit);
        }
        elseif($route == 'not-delivered')
        {
            $data = AutoOrder::with('qna','sheet.user')->where('pstatus',12)
            ->where('paneltype', '=', $ptype)
            ->where(function($q){
                $q
                ->where('approve_deliver',0)
                ->orWhere('approve_deliver',NULL);
            })
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
            })
            ->where($searchIn,'LIKE','%'.$search.'%')
            ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
            ->orderBy('id', 'DESC')->paginate($limit);
        }
        elseif($route == 'schedule-for-delivery')
        {
            $data = AutoOrder::with('qna','sheet.user')->where('pstatus',12)
            ->where('paneltype', '=', $ptype)
            ->where('approve_deliver',2)
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
            })
            ->where($searchIn,'LIKE','%'.$search.'%')
            ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
            ->orderBy('id', 'DESC')->paginate($limit);
        }
        elseif($route == 'delivered')
        {
            $data = AutoOrder::with('qna','sheet.user')->where('pstatus',12)
            ->where('paneltype', '=', $ptype)
            ->where('approve_deliver',1)
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
            })
            ->where($searchIn,'LIKE','%'.$search.'%')
            ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
            ->orderBy('id', 'DESC')->paginate($limit);
        }
        elseif($route == 'complete')
        {
            $data = AutoOrder::with('qna','sheet.user')->where('pstatus',13)
            ->where('paneltype', '=', $ptype)
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
            })
            ->where($searchIn,'LIKE','%'.$search.'%')
            ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
            ->orderBy('id', 'DESC')->paginate($limit);
        }
        elseif($route == 'cancel')
        {
            $data = AutoOrder::with('qna')->where('pstatus',14)
            ->where('paneltype', '=', $ptype)
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
            })
            ->where($searchIn,'LIKE','%'.$search.'%')
            ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
            ->orderBy('id', 'DESC')->paginate($limit);
        }
        elseif($route == 'new')
        {
            $data = AutoOrder::with('qna')->where('pstatus',0)
            ->where('paneltype', '=', $ptype)
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
            })
            ->where($searchIn,'LIKE','%'.$search.'%')
            ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
            ->orderBy('id', 'DESC')->paginate($limit);
        }
        elseif($route == 'followup')
        {
            $data = AutoOrder::with('qna')->where('pstatus',2)
            ->where('paneltype', '=', $ptype)
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
            })
            ->where($searchIn,'LIKE','%'.$search.'%')
            ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
            ->orderBy('id', 'DESC')->paginate($limit);
        }
        elseif($route == 'interested')
        {
            $data = AutoOrder::with('qna')->where('pstatus',1)
            ->where('paneltype', '=', $ptype)
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
            })
            ->where($searchIn,'LIKE','%'.$search.'%')
            ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
            ->orderBy('id', 'DESC')->paginate($limit);
        }
        elseif($route == 'not_interested')
        {
            $data = AutoOrder::with('qna')->where('pstatus',4)
            ->where('paneltype', '=', $ptype)
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
            })
            ->where($searchIn,'LIKE','%'.$search.'%')
            ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
            ->orderBy('id', 'DESC')->paginate($limit);
        }
        elseif($route == 'asking_low')
        {
            $data = AutoOrder::with('qna')->where('pstatus',3)
            ->where('paneltype', '=', $ptype)
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
            })
            ->where($searchIn,'LIKE','%'.$search.'%')
            ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
            ->orderBy('id', 'DESC')->paginate($limit);
        }
        elseif($route == 'not_responding')
        {
            $data = AutoOrder::with('qna','notRespond.user')->where('pstatus',5)
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
            })
            ->where($searchIn,'LIKE','%'.$search.'%')
            ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
            ->orderBy('id', 'DESC')->paginate($limit);
        }
        elseif($route == 'time_quote')
        {
            $data = AutoOrder::with('qna')->where('pstatus',6)
            ->where('paneltype', '=', $ptype)
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
            })
            ->where($searchIn,'LIKE','%'.$search.'%')
            ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
            ->orderBy('id', 'DESC')->paginate($limit);
        }
        elseif($route == 'payment_missing')
        {
            $data = AutoOrder::with('qna')->where('pstatus',7)
            ->where('paneltype', '=', $ptype)
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
            })
            ->where($searchIn,'LIKE','%'.$search.'%')
            ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
            ->orderBy('id', 'DESC')->paginate($limit);
        }
        elseif($route == 'on_approval')
        {
            $data = AutoOrder::with('qna')->where('pstatus',18)
            ->where('paneltype', '=', $ptype)
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
            })
            ->where($searchIn,'LIKE','%'.$search.'%')
            ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
            ->orderBy('id', 'DESC')->paginate($limit);
        }
        elseif($route == 'on_approval_cancel')
        {
            $data = AutoOrder::with('qna')->where('pstatus',19)
            ->where('paneltype', '=', $ptype)
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
            })
            ->where($searchIn,'LIKE','%'.$search.'%')
            ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
            ->orderBy('id', 'DESC')->paginate($limit);
        }
        elseif($route == 'booked')
        {
            $data = AutoOrder::with('qna')->where('pstatus',8)
            ->where('paneltype', '=', $ptype)
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
            })
            ->where($searchIn,'LIKE','%'.$search.'%')
            ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
            ->orderBy('id', 'DESC')->paginate($limit);
        }
        elseif($route == 'deleted')
        {
            $data = AutoOrder::with('qna')->where('pstatus',15)
            ->where('paneltype', '=', $ptype)
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
            })
            ->where($searchIn,'LIKE','%'.$search.'%')
            ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
            ->orderBy('id', 'DESC')->paginate($limit);
        }
        elseif($route == 'owes_money')
        {
            $data = AutoOrder::with('qna')->where('pstatus','<>',0)
            ->where('paneltype', '=', $ptype)
            ->where(function ($q){
                $q->where('owes', '>', 0)
                ->orWhere('owes_money', 1);
            })
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
            })
            ->where($searchIn,'LIKE','%'.$search.'%')
            ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
            ->orderBy('id', 'DESC')->paginate($limit);
        }
        elseif($route == 'auction_not_win')
        {
            $data = AutoOrder::with('qna')->where('pstatus','<',7)
            ->where(function ($q1){
                $q1->where(function ($q2){
                    $q2->where(function ($q){
                        $q->where('oterminal',2)
                        ->orWhere('oterminal',3)
                        ->orWhere('oterminal',4);
                    })
                    ->where(function ($q){
                        $q->where('oauctiondate','<>',NULL)
                        ->orWhere('oauctiontime','<>',NULL);
                    });
                })
                ->orWhere(function ($q2){
                    $q2->where(function ($q){
                        $q->where('dterminal',2)
                        ->orWhere('dterminal',3)
                        ->orWhere('dterminal',4);
                    })
                    ->where(function ($q){
                        $q->where('dauctiondate','<>',NULL)
                        ->orWhere('dauctiontime','<>',NULL);
                    });
                });
            })
            ->where('paneltype', '=', $ptype)
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
            })
            ->where('created_at','>=',\Carbon\Carbon::today()->subDays($setting->no_days))
            ->where($searchIn,'LIKE','%'.$search.'%')
            ->orderBy('id', 'DESC')->paginate($limit);
        }
        else{
            $data = AutoOrder::with('qna','notRespond.user','sheet.user')
            ->where('paneltype', '=', $ptype)
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
            })
            ->where($searchIn,'LIKE','%'.$search.'%')
            ->where(function ($q) use ($global_search)
            {
                $q->where('id', 'like', '%' . $global_search . '%')
                ->orWhere('created_at', 'like', '%' . $global_search . '%')
                ->orWhere('updated_at', 'like', '%' . $global_search . '%')
                ->orWhere('originzsc', 'like', '%' . $global_search . '%')
                ->orWhere('destinationzsc', 'like', '%' . $global_search . '%')
                ->orWhere('ymk', 'like', '%' . $global_search . '%');
            })
            ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
            ->orderBy('id', 'DESC')->paginate($limit);
        }
        // return $data;
        return view('main.phone_quote.question.data',compact('data','route'));
    }

    public function showData2(Request $request)
    {
        $user = Auth::user();
        $ptype = $this->check_user_setting(Auth::user()->id);
        $setting = general_setting::first();
        $route = '';
        $search = $request->global_search;
        $data = AutoOrder::with('qna','notRespond.user','sheet.user')
        ->where('paneltype', '=', $ptype)
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
        })
        ->where(function ($q) use ($search)
        {
            $q->where('id', 'like', '%' . $search . '%')
            ->orWhere('created_at', 'like', '%' . $search . '%')
            ->orWhere('updated_at', 'like', '%' . $search . '%')
            ->orWhere('originzsc', 'like', '%' . $search . '%')
            ->orWhere('destinationzsc', 'like', '%' . $search . '%')
            ->orWhere('ymk', 'like', '%' . $search . '%');
        })
        ->orderBy('id', 'DESC')->paginate(5);
        // echo "<pre>";
        // print_r($data);
        // exit();
        return view('main.phone_quote.question.show-data',compact('data','route','search'));
    }

    public function qnaModal(Request $request)
    {
        $order = AutoOrder::with(['qna.user'])
        ->where('id',$request->id)
        ->first();

        $ques = Question::where('status',1)->get();

        return view('main.phone_quote.question.modal',compact('order','ques'));
    }

    public function answers(Request $request)
    {
        $ans = QuestionAnwser::where('q_id',$request->id)->get();
        return view('main.phone_quote.question.answer',compact('ans'));
    }

    public function sendMessage(Request $request)
    {
        $qna = new QNAOrder;
        $qna->user_id = auth()->id();
        $qna->order_id = $request->order_id;
        $qna->q_id = $request->q_id;
        $qna->a_id = $request->a_id;
        $qna->message = $request->message;
        $qna->unread = 0;
        $qna->save();
        return response()->json([
            'message' => 'Sent Message!',
            'status' => true,
            'status_code' => 200
        ]);
    }

    public function msg(Request $request)
    {
        $qna = QNAOrder::with('user')->where('order_id', $request->order_id)->get();
        return response()->json([
            'qna' => $qna,
            'status' => true,
            'status_code' => 200
        ]);
    }
    
    public function addPort(Request $request)
    {
        $port = new PortDetail;
        $port->delivery_address = $request->delivery_address;
        $port->port_name = $request->port_name;
        $port->terminal = $request->terminal_name;
        $port->make_sure = ($request->make_sure == 1) ? 1 : 0;
        $port->accident_vehicle = ($request->accident_vehicle == 1) ? 1 : 0;
        $port->address = $request->address;
        $port->zip = $request->zip;
        $port->state = $request->state;
        $port->city = $request->city;
        $port->zsc = $request->city.', '.$request->state.' '.$request->zip;
        $port->tel = $request->telephone;
        $port->twic_card = $request->twic_card;
        $port->save();
        
        return "Save";
    }
    
    public function editPort(Request $request)
    {
        $port = PortDetail::find($request->id);
        
        return view('layouts.update-port',compact('port'));
        
    }
    
    public function updatePort(Request $request)
    {
        $port = PortDetail::find($request->id);
        $port->delivery_address = $request->delivery_address;
        $port->port_name = $request->port_name;
        $port->terminal = $request->terminal_name;
        $port->make_sure = ($request->make_sure == 1) ? 1 : 0;
        $port->accident_vehicle = ($request->accident_vehicle == 1) ? 1 : 0;
        $port->address = $request->address;
        $port->zip = $request->zip;
        $port->state = $request->state;
        $port->city = $request->city;
        $port->zsc = $request->city.', '.$request->state.' '.$request->zip;
        $port->tel = $request->telephone;
        $port->twic_card = $request->twic_card;
        $port->save();
        
        return "Update";
    }
    
    public function deletePort(Request $request)
    {
        $port = PortDetail::find($request->id);
        $port->delete();
        return "Delete";
    }
    
    public function notRes(Request $request)
    {
        $new = new NotRespondOrder;
        $new->order_id = $request->id;
        $new->user_id = Auth::user()->id;
        $new->save();
        
        return "Calling";
    }
    
    public function updateAuction(Request $request)
    {
        $model = new SheetDetails;
        $model->auth_id = Auth::user()->id;
        $model->orderId = $request->orderId;
        if($request->url == 'listed')
        {
            $model->pstatus = 9;
            $model->paid = $request->paid;
            $model->storage = $request->storage;
            $model->listed_price = $request->listed_price;
            $model->auction_update = $request->auction_update;
            $model->title_keys = $request->title_keys;
            $model->keys = $request->keys;
            $model->vehicle_position = $request->port;
            $model->listed_count = $request->listed_count;
            $model->price = $request->price;
            $model->additional = $request->additional;
            $model->save();
        }
        if($request->url == 'schedule')
        {
            $model->pstatus = 10;
            $model->pickup_date = $request->pickedup;
            $model->vehicle_condition = $request->condition;
            $model->title_keys = $request->title_keys;
            $model->keys = $request->keys;
            $model->storage = $request->storage;
            $model->auction_update = $request->auction_update;
            $model->who_pay_storage = $request->who_pay_storage;
            $model->vehicle_position = $request->vehicle_position;
            $model->delivery_date = $request->delivery_date;
            $model->driver_fmcsa = $request->driver_fmcsa;
            $model->carrier_rating = $request->carrier_rating;
            $model->fmcsa = $request->fmcsa;
            $model->coi_holder = $request->coi_holder;
            $model->vehicle_luxury = $request->vehicle_luxury;
            $model->aware_driver_delivery_date = $request->aware_driver_delivery_date;
            $model->price = $request->price;
            $model->insurance_date = $request->insurance_date;
            $model->new_old_driver = $request->new_old_driver;
            $model->payment_method = $request->payment_method;
            $model->is_local = $request->is_local;
            $model->job_accept = $request->job_accept;
            $model->additional = $request->additional;
            $model->company_name = $request->company_name;
            $model->save();
        }
        if($request->url == 'pickedup')
        {
            $model->pstatus = 11;
            $model->driver_status = $request->driver_status;
            $model->storage = $request->storage;
            $model->delivery_date = $request->delivery_date;
            $model->vehicle_condition = $request->condition;
            $model->title_keys = $request->title_keys;
            $model->keys = $request->keys;
            $model->vehicle_position = $request->vehicle_position;
            $model->payment = $request->payment;
            $model->payment_charged_or_owes = $request->payment_charged_or_owes;
            $model->payment_method = $request->payment_method;
            $model->carrier_name = $request->carrier_name;
            $model->driver_payment = $request->driver_payment;
            $model->price = $request->price;
            $model->driver_no = $request->driver_no;
            $model->driver_no2 = $request->driver_no2;
            $model->driver_no3 = $request->driver_no3;
            $model->driver_no4 = $request->driver_no4;
            $model->stamp_dock_receipt = $request->stamp_dock_receipt;
            $model->company_name = $request->company_name;
            $model->additional = $request->additional;
            $model->save();
            
            $model1 = new SheetDetails;
            $model1->auth_id = Auth::user()->id;
            $model1->orderId = $request->orderId;
            $model1->pstatus = 11;
            $model1->auction_status = $request->status1;
            $model1->storage = $request->storage1;
            $model1->vehicle_condition = $request->condition1;
            $model1->title_keys = $request->title_keys1;
            $model1->keys = $request->keys1;
            $model1->vehicle_position = $request->vehicle_position1;
            $model1->additional = $request->additional1;
            $model1->save();
        }
        if($request->url == 'delivered')
        {
            $model->pstatus = 12;
            $model->driver_no = $request->driver_no;
            $model->driver_no2 = $request->driver_no2;
            $model->driver_no3 = $request->driver_no3;
            $model->driver_no4 = $request->driver_no4;
            $model->driver_status = $request->driver_status;
            $model->driver_payment_status = $request->driver_payment_status;
            $model->vehicle_condition = $request->condition;
            $model->customer_informed = $request->customer_informed;
            $model->delivery_date = $request->delivery_date;
            $model->vehicle_position = $request->vehicle_position;
            $model->who_pay_storage = $request->who_pay_storage;
            $model->title_keys = $request->title_keys;
            $model->keys = $request->keys;
            $model->client_status = $request->client_status;
            $model->owes_status = $request->owes_status;
            $model->additional = $request->additional;
            $model->save();
        }
        if($request->url == 'complete')
        {
            $model->pstatus = 13;
            $model->remarks = $request->remarks;
            $model->comments = $request->comments;
            $model->satisfied = $request->satisfied;
            $model->review = $request->review;
            $model->website = $request->website;
            $model->website_other = $request->website_other;
            $model->website_link = $request->website_link;
            $model->client_rating = $request->client_rating;
            $model->additional = $request->additional;
            $model->save();
        }
        $msg = $request->url.' auction added successfully!';
        return back()->with('success',$msg);
    }
    
    public function showHideChat($id,$oid,$uid,$status)
    {
        $data = ChatShowHide::where('order_id',$oid)
        ->where('from_user_id',$id)
        ->where('to_user_id',$uid)
        ->first();
        
        if(empty($data))
        {
            $data = new ChatShowHide;
            $data->order_id = $oid;
            $data->from_user_id = $id;
            $data->to_user_id = $uid;
            $data->status = $status;
            $data->save();
        }
        else{
            $data->status = $status;
            $data->save();
        }
    }
    
    public function runTimeChat($id,$oid,$uid,$status)
    {
        $data = RunTimeChat::where('order_id',$oid)
        ->where('from_user_id',$id)
        ->where('to_user_id',$uid)
        ->first();
        
        if(empty($data))
        {
            $data = new RunTimeChat;
            $data->order_id = $oid;
            $data->from_user_id = $id;
            $data->to_user_id = $uid;
            $data->status = $status;
            $data->save();
        }
        else{
            $data->status = $status;
            $data->save();
        }
    }
    
    public function chatCenterUser(Request $request)
    {
        $id = $request->id;
        $order = AutoOrder::find($id);
        $auth = Auth::id();
        if(Auth::user()->userRole->name == 'Seller Agent' || Auth::user()->userRole->name == 'CSR' || Auth::user()->userRole->name == 'Order Taker' || Auth::user()->userRole->name == 'Manager' || Auth::user()->userRole->name == 'Admin')
        {
            if(Auth::user()->userRole->name == 'Seller Agent' || Auth::user()->userRole->name == 'CSR' || Auth::user()->userRole->name == 'Order Taker' || Auth::user()->userRole->name == 'Manager')
            {
                $user = User::where('deleted',0)
                ->whereHas('userRole',function($q){
                    $q->where('name','Dispatcher');
                })
                ->orderBy('is_login','DESC')->orderBy('updated_at','DESC')->get();
            }
            else{
                $user = User::whereHas('userRole',function($q){
                    $q->where('name','Dispatcher')->orWhere('name','CSR')->orWhere('name','Seller Agent')->orWhere('name','Order Taker')->orWhere('name','Manager');
                })->where('deleted',0)->orderBy('is_login','DESC')->orderBy('updated_at','DESC')->get();
            }
            return view('main.phone_quote.show-data.chat-center-user',compact('id','user'));
        }
        else if(Auth::user()->userRole->name == 'Dispatcher')
        {
            if(isset($order->order_taker_id))
            {
                $user = User::find($order->order_taker_id);
                $chat = CustomChat::with('flag.user')->where(function ($q) use ($order,$auth){
                    $q->where(function ($q1) use ($order,$auth){
                        $q1->where('from_user_id',$auth)->where('to_user_id',$order->order_taker_id);
                    })
                    ->orWhere(function ($q1) use ($order,$auth){
                        $q1->where('from_user_id',$order->order_taker_id)->where('to_user_id',$auth)->where('status','>',0);
                    });
                })->where('order_id',$id)->orderBy('id','ASC')->get();
                
                if(isset($chat)){
                    $arr =[];
                    foreach ($chat as $key => $value) {
                        array_push($arr,$value->message_date);
                    }
                    $arr = array_unique($arr);
                    foreach ($arr as $key => $value) 
                    {
                        $chat[$key]->date = $value;
                    }
                }
                
                $this->showHideChat($auth,$id,$order->order_taker_id,1);
                
                return view('main.phone_quote.show-data.chat-center',compact('id','chat','user'));
            }
            else{
                $user = User::whereHas('userRole',function($q){
                    $q->where('name','CSR')->orWhere('name','Seller Agent')->orWhere('name','Order Taker')->orWhere('name','Manager');
                })->where('deleted',0)->orderBy('is_login','DESC')->orderBy('updated_at','DESC')->get();
                return view('main.phone_quote.show-data.chat-center-user',compact('id','user'));
            }
        }
        
    }
    
    public function showChatCenter(Request $request)
    {
        $user = User::find($request->uId);
        $uid = Auth::id();
        $id = $request->oid22;
        $chat = CustomChat::with('flag.user')->where(function ($q) use ($request,$uid){
            $q->where(function ($q1) use ($request,$uid){
                $q1->where('from_user_id',$uid)->where('to_user_id',$request->uId);
            })
            ->orWhere(function ($q1) use ($request,$uid){
                $q1->where('from_user_id',$request->uId)->where('to_user_id',$uid)->where('status','>',0);
            });
        })->where('order_id',$id)->orderBy('id','ASC')->get();
        
        if(isset($chat)){
            $arr =[];
            foreach ($chat as $key => $value) {
                array_push($arr,$value->message_date);
            }
            $arr = array_unique($arr);
            foreach ($arr as $key => $value) 
            {
                $chat[$key]->date = $value;
            }
        }
        
        return view('main.phone_quote.show-data.chat-center',compact('id','chat','user'));
        
    }
    
    public function showChatCenter2(Request $request)
    {
        $uid = Auth::id();
        $id = $request->oid22;
        $chat = CustomChat::with('flag.user')->where(function ($q) use ($request,$uid){
            $q->where(function ($q1) use ($request,$uid){
                $q1->where('from_user_id',$uid)->where('to_user_id',$request->uId);
            })
            ->orWhere(function ($q1) use ($request,$uid){
                $q1->where('from_user_id',$request->uId)->where('to_user_id',$uid)->where('status','>',0);
            });
        })->where('order_id',$id)->orderBy('id','ASC')->get();
        
        if(isset($chat)){
            $arr =[];
            foreach ($chat as $key => $value) {
                array_push($arr,$value->message_date);
            }
            $arr = array_unique($arr);
            foreach ($arr as $key => $value) 
            {
                $chat[$key]->date = $value;
            }
        }
        
        return view('main.phone_quote.show-data.custom-msg',compact('chat'));
        
    }
    
    public function sendCustomChat(Request $request)
    {
        $data = new CustomChat;
        $data->order_id = $request->oid;
        $data->from_user_id = Auth::id();
        $data->to_user_id = $request->uid;
        $data->message = $request->message;
        $data->message_type = 'text';
        $data->message_date = date('M, d Y');
        $data->message_time = date('h:i A');
        $data->status = 0;
        $data->datetime_for_approver = Carbon::now()->addMinutes(5);
        $data->save();
        
        $this->runTimeChat($request->uid,$request->oid,Auth::id(),1);
        
        return "save";
    }
    
    public function exitChat(Request $request)
    {
        $id = Auth::id();
        $oid = $request->oid;
        $uid = $request->uid;
        $this->showHideChat($id,$oid,$uid,0);
        $this->runTimeChat($id,$oid,$uid,0);
        
        return "CLOSE";
    }
    
    public function openChat(Request $request)
    {
        $id = Auth::id();
        $oid = $request->oid;
        $uid = $request->uid;
        $this->showHideChat($id,$oid,$uid,1);
        
        return "OPEN";
    }
    
    public function getAutoChat()
    {
        $id = Auth::id();
        
        $data = ChatShowHide::where('from_user_id',$id)
        ->where('status',1)->get();
        
        $userChat = [];
        $userChat2 = [];
        if(!empty($data[0]))
        {
            foreach($data as $key => $value)
            {
                if(isset($value->to_user_id))
                {
                    $userChat[$key] = User::find($value->to_user_id);
                    $userChat[$key]->order_id = $value->order_id;
                    $userChat[$key]->chat = CustomChat::with('flag.user')->where(function ($q) use ($value,$id){
                                                $q->where(function ($q1) use ($value,$id){
                                                    $q1->where('from_user_id',$id)->where('to_user_id',$value->to_user_id);
                                                })
                                                ->orWhere(function ($q1) use ($value,$id){
                                                    $q1->where('from_user_id',$value->to_user_id)->where('to_user_id',$id)->where('status','>',0);
                                                });
                                            })->where('order_id',$value->order_id)->orderBy('id','ASC')->get();
                }
                if(isset($value->public_id))
                {
                    $userChat2[$key] = PublicOrder::find($value->public_id);
                    $userChat2[$key]->chat = PublicOrderChat::with('user','flag.user')
                    ->where('public_id',$value->public_id)
                    ->where(function($q) use ($id){
                        $q->where(function($q1) use ($id){
                            $q1->where('user_id',$id);
                        })
                        ->orWhere(function($q1) use ($id){
                            $q1->where('status','>',0)
                            ->whereHas('publicChat',function($q2) use ($id){
                                $q2->whereRaw('FIND_IN_SET(?, members)', [$id]);
                            });
                        });
                    })->orderBy('id','ASC')
                    ->get();
                }
            }
        }
        
        if(!empty($userChat[0]))
        {
            foreach($userChat as $key2 => $val)
            {
                if(isset($val->chat)){
                    $arr =[];
                    foreach ($val->chat as $key => $value) {
                        array_push($arr,$value->message_date);
                    }
                    $arr = array_unique($arr);
                    foreach ($arr as $key => $value) 
                    {
                        $userChat[$key2]->chat[$key]->date = $value;
                    }
                }
            }
        }
        
        if(!empty($userChat2[0]))
        {
            foreach($userChat2 as $key2 => $val)
            {
                if(isset($val->chat)){
                    $arr =[];
                    foreach ($val->chat as $key => $value) {
                        array_push($arr,$value->message_date);
                    }
                    $arr = array_unique($arr);
                    foreach ($arr as $key => $value) 
                    {
                        $userChat2[$key2]->chat[$key]->date = $value;
                    }
                }
            }
        }
        // echo "<pre>";
        // print_r($userChat);
        // exit();
        
        // return response()->json([
        //     'user'=>$userChat,
        //     'status'=>true,
        //     'status_code'=>200
        // ],200);
        return view('main.phone_quote.show-data.chat-center2',compact('userChat','userChat2'));
    }
    
    public function getAutoConvo()
    {
        $id = Auth::id();
        
        $data = ChatShowHide::where('from_user_id',$id)
        ->where('status',1)->get();
        
        $userChat = [];
        $userChat2 = [];
        if(!empty($data[0]))
        {
            foreach($data as $key => $value)
            {
                if(isset($value->to_user_id))
                {
                    $userChat[$key]['user_id'] = $value->to_user_id;
                    $userChat[$key]['auth_id'] = $id;
                    $userChat[$key]['order_id'] = $value->order_id;
                    $userChat[$key]['chat'] = CustomChat::with('flag.user')->where(function ($q) use ($value,$id){
                                                $q->where(function ($q1) use ($value,$id){
                                                    $q1->where('from_user_id',$id)->where('to_user_id',$value->to_user_id);
                                                })
                                                ->orWhere(function ($q1) use ($value,$id){
                                                    $q1->where('from_user_id',$value->to_user_id)->where('to_user_id',$id)->where('status','>',0);
                                                });
                                            })->where('order_id',$value->order_id)->orderBy('id','ASC')->get();
                }
                if(isset($value->public_id))
                {
                    $userChat2[$key]['public_id'] = $value->public_id;
                    $userChat2[$key]['chat'] = PublicOrderChat::with('user','flag.user')
                    ->where('public_id',$value->public_id)
                    ->where(function($q) use ($id){
                        $q->where(function($q1) use ($id){
                            $q1->where('user_id',$id);
                        })
                        ->orWhere(function($q1) use ($id){
                            $q1->where('status','>',0)
                            ->whereHas('publicChat',function($q2) use ($id){
                                $q2->whereRaw('FIND_IN_SET(?, members)', [$id]);
                            });
                        });
                    })->orderBy('id','ASC')
                    ->get();
                }
                
            }
        }
        
        if(!empty($userChat[0]))
        {
            foreach($userChat as $key2 => $val)
            {
                if(isset($val['chat'])){
                    $arr =[];
                    foreach ($val['chat'] as $key => $value) {
                        array_push($arr,$value['message_date']);
                    }
                    $arr = array_unique($arr);
                    foreach ($arr as $key => $value) 
                    {
                        $userChat[$key2]['chat'][$key]['date'] = $value;
                    }
                }
            }
        }
        
        if(!empty($userChat2[0]))
        {
            foreach($userChat2 as $key2 => $val)
            {
                if(isset($val['chat'])){
                    $arr =[];
                    foreach ($val['chat'] as $key => $value) {
                        array_push($arr,$value['message_date']);
                    }
                    $arr = array_unique($arr);
                    foreach ($arr as $key => $value) 
                    {
                        $userChat2[$key2]['chat'][$key]['date'] = $value;
                    }
                }
            }
        }
        // echo "<pre>";
        // print_r($userChat);
        // exit();
        
        return response()->json([
            'chat'=>$userChat,
            'chat2'=>$userChat2,
            'status'=>true,
            'status_code'=>200
        ],200);
    }
    
    public function readChat(Request $request)
    {
        $data = CustomChat::where('order_id',$request->oid)
        ->where('from_user_id',$request->uid)
        ->where('to_user_id',Auth::id())
        ->where('status',1)
        ->get();
        
        if(isset($data))
        {
            foreach($data as $key => $value)
            {
                $read = CustomChat::find($value->id);
                $read->status = 2;
                $read->save();
            }
        }
        
        return "Read Message";
    }
    
    public function showHidePublicChat($id,$oid,$pid,$status)
    {
        $data = ChatShowHide::where('order_id',$oid)
        ->where('from_user_id',$id)
        ->where('public_id',$pid)
        ->first();
        
        if(empty($data))
        {
            $data = new ChatShowHide;
            $data->order_id = $oid;
            $data->from_user_id = $id;
            $data->public_id = $pid;
            $data->status = $status;
            $data->save();
        }
        else{
            $data->status = $status;
            $data->save();
        }
    }
    
    public function runTimePublicChat($id,$oid,$pid,$status)
    {  
        if(empty($id))
        {
            $data = PublicOrder::find($pid);
            
            $arr[] = explode(',',$data->members);
            
            if(isset($arr))
            {
                foreach($arr as $key => $uid)
                {
                    if($uid != Auth::id())
                    {
                        $data = RunTimeChat::where('order_id',$oid)
                        ->where('from_user_id',$uid)
                        ->where('public_id',$pid)
                        ->first();
                        
                        if(empty($data))
                        {
                            $data = new RunTimeChat;
                            $data->order_id = $oid;
                            $data->from_user_id = $uid;
                            $data->public_id = $pid;
                            $data->status = $status;
                            $data->save();
                        }
                        else{
                            $data->status = $status;
                            $data->save();
                        }
                    }
                }
            }
        }
        else{
            $data = RunTimeChat::where('order_id',$oid)
            ->where('from_user_id',$uid)
            ->where('public_id',$pid)
            ->first();
            
            if(empty($data))
            {
                $data = new RunTimeChat;
                $data->order_id = $oid;
                $data->from_user_id = $uid;
                $data->public_id = $pid;
                $data->status = $status;
                $data->save();
            }
            else{
                $data->status = $status;
                $data->save();
            }
        }
    }
    
    public function publicCenterUser(Request $request)
    {
        $data = PublicOrder::where('created_by_user_id',Auth::user()->id)
        ->where('order_id',$request->id)
        ->first();
        
        $id = Auth::id();
        if(!isset($data))
        {  
            if(Auth::user()->userRole->name == 'Seller Agent' || Auth::user()->userRole->name == 'CSR' || Auth::user()->userRole->name == 'Order Taker' || Auth::user()->userRole->name == 'Manager')
            {
                $user = User::select('id')->whereHas('userRole',function($q){
                    $q->where('name','Dispatcher');
                })->where('deleted',0)->get()->toArray();
            }
            else if(Auth::user()->userRole->name == 'Dispatcher')
            {
                $user = User::select('id')->whereHas('userRole',function($q){
                    $q->where('name','Seller Agent')->orWhere('name','CSR')->orWhere('name','Order Taker')->orWhere('name','Manager');
                })->where('deleted',0)->get()->toArray();
            }
            else if(Auth::user()->userRole->name == 'Admin')
            {
                $user = User::select('id')->whereHas('userRole',function($q){
                    $q->where('name','Seller Agent')->orWhere('name','CSR')->orWhere('name','Order Taker')->orWhere('name','Manager')->orWhere('name','Dispatcher');
                })->where('deleted',0)->get()->toArray();
            }
            $arr = [];
            if(isset($user))
            {
                $arr[] = $id;
                foreach($user as $key => $value)
                {
                    $arr[] = $value['id'];
                }
            } 
            $ids = implode(',',$arr);
            // echo "<pre>";
            // print_r($ids);
            // exit();
            $data = new PublicOrder;
            $data->order_id = $request->id;
            $data->created_by_user_id = Auth::id();
            $data->members = $ids;
            $data->save();
        }
        
        $chat = PublicOrderChat::with('user','flag.user')
        ->where('public_id',$data->id)
        ->where(function($q) use ($id){
            $q->where(function($q1) use ($id){
                $q1->where('user_id',$id);
            })
            ->orWhere(function($q1) use ($id){
                $q1->where('status','>',0)
                ->whereHas('publicChat',function($q2) use ($id){
                    $q2->whereRaw('FIND_IN_SET(?, members)', [$id]);
                });
            });
        })->orderBy('id','ASC')
        ->get();
                                        
        if(isset($chat)){
            $arr =[];
            foreach ($chat as $key => $value) {
                array_push($arr,$value->message_date);
            }
            $arr = array_unique($arr);
            foreach ($arr as $key => $value) 
            {
                $chat[$key]->date = $value;
            }
        }
        $this->showHidePublicChat(Auth::id(),$data->order_id,$data->id,1);
        
        return view('main.phone_quote.show-data.public-center',compact('chat','data'));
    }
    
    public function exitPublicChat(Request $request)
    {
        $id = Auth::id();
        $oid = $request->oid;
        $pid = $request->pid;
        $this->showHidePublicChat($id,$oid,$pid,0);
        $this->runTimePublicChat($id,$oid,$pid,0);
        
        return "CLOSE";
    }
    
    public function sendPublicChat(Request $request)
    {
        $data = PublicOrder::find($request->pid);
        if(isset($data))
        {
            $chat = new PublicOrderChat;
            $chat->order_id = $data->order_id;
            $chat->public_id = $data->id;
            $chat->user_id = Auth::id();
            $chat->message = $request->message;
            $chat->message_type = 'text';
            $chat->message_date = date('M, d Y');
            $chat->message_time = date('h:i A');
            $chat->status = 0;
            $chat->seen_by_user_id = Auth::id();
            $chat->datetime_for_approver = Carbon::now()->addMinutes(5);
            $chat->save();
            
            $this->runTimePublicChat('',$data->order_id,$data->id,1);
        }
        
        return "Message Sent";
    }
    
    public function publicCenterUser2(Request $request)
    {
        $data = PublicOrder::find($request->pid);
        
        $id = Auth::id();
        
        $chat = PublicOrderChat::with('user','flag.user')
        ->where('public_id',$data->id)
        ->where(function($q) use ($id){
            $q->where(function($q1) use ($id){
                $q1->where('user_id',$id);
            })
            ->orWhere(function($q1) use ($id){
                $q1->where('status','>',0)
                ->whereHas('publicChat',function($q2) use ($id){
                    $q2->whereRaw('FIND_IN_SET(?, members)', [$id]);
                });
            });
        })->orderBy('id','ASC')
        ->get();
                                        
        if(isset($chat)){
            $arr =[];
            foreach ($chat as $key => $value) {
                array_push($arr,$value->message_date);
            }
            $arr = array_unique($arr);
            foreach ($arr as $key => $value) 
            {
                $chat[$key]->date = $value;
            }
        }
        $this->showHidePublicChat(Auth::id(),$data->order_id,$data->id,1);
        
        return view('main.phone_quote.show-data.public-msg',compact('chat'));
    }
    
    public function readPublicChat(Request $request)
    {
        $id = Auth::id();
        
        $chat = PublicOrderChat::where('public_id',$request->pid)
        ->where(function($q) use ($id){
            $q->where('status','>',0)
            ->whereHas('publicChat',function($q2) use ($id){
                $q2->whereRaw('FIND_IN_SET(?, members)', [$id]);
            });
        })
        ->whereRaw('NOT FIND_IN_SET(?, seen_by_user_id)', [$id])->orderBy('id','ASC')
        ->get();
        
        if(isset($chat))
        {
            foreach($chat as $key => $value)
            {
                $data = PublicOrderChat::find($value->id);
                $data->seen_by_user_id = $data->seen_by_user_id.','.$id;
                $data->save();
            }
        }
        
        return "Read Message";
    }
    
    public function getAutoChat2()
    {
        $id = Auth::id();
        
        $data2 = ChatShowHide::where('from_user_id',$id)
        ->where('status',1)->get();
        
        $userChat = [];
        $userChat2 = [];
        if(empty($data2[0]))
        {
            $data = RunTimeChat::where('from_user_id',$id)
            ->where('status',1)->get();
            if(!empty($data[0]))
            {
                foreach($data as $key => $value)
                {
                    if(isset($value->to_user_id))
                    {
                        $userChat[$key] = User::find($value->to_user_id);
                        $userChat[$key]->order_id = $value->order_id;
                        $userChat[$key]->chat = CustomChat::with('flag.user')->where(function ($q) use ($value,$id){
                                                    $q->where(function ($q1) use ($value,$id){
                                                        $q1->where('from_user_id',$id)->where('to_user_id',$value->to_user_id);
                                                    })
                                                    ->orWhere(function ($q1) use ($value,$id){
                                                        $q1->where('from_user_id',$value->to_user_id)->where('to_user_id',$id)->where('status','>',0);
                                                    });
                                                })->where('order_id',$value->order_id)->orderBy('id','ASC')->get();
                                                
                    $this->showHideChat($id,$value->order_id,$value->to_user_id,1);
                    }
                    if(isset($value->public_id))
                    {
                        $userChat2[$key] = PublicOrder::find($value->public_id);
                        $userChat2[$key]->chat = PublicOrderChat::with('user','flag.user')
                        ->where('public_id',$value->public_id)
                        ->where(function($q) use ($id){
                            $q->where(function($q1) use ($id){
                                $q1->where('user_id',$id);
                            })
                            ->orWhere(function($q1) use ($id){
                                $q1->where('status','>',0)
                                ->whereHas('publicChat',function($q2) use ($id){
                                    $q2->whereRaw('FIND_IN_SET(?, members)', [$id]);
                                });
                            });
                        })->orderBy('id','ASC')
                        ->get();
                        
                        $this->showHidePublicChat($id,$value->order_id,$value->public_id,1);
                    }
                }  
            }
        }
        else{
            $newData = [];
            foreach($data2 as $key => $value)
            {
                if(isset($value->to_user_id))
                {
                    $newData[] = RunTimeChat::where('from_user_id',$id)->where('to_user_id','<>',$value->to_user_id)->where('status',1)->get()->toArray();
                }
                if(isset($value->public_id))
                {
                    $newData[] = RunTimeChat::where('from_user_id',$id)->where('public_id','<>',$value->public_id)->where('status',1)->get()->toArray();
                }
            }
            
            if(isset($newData))
            {
                foreach($newData as $key => $value)
                {
                    if(isset($value['to_user_id']))
                    {
                        $userChat[$key] = User::find($value['to_user_id']);
                        $userChat[$key]->order_id = $value['order_id'];
                        $userChat[$key]->chat = CustomChat::with('flag.user')->where(function ($q) use ($value,$id){
                                                    $q->where(function ($q1) use ($value,$id){
                                                        $q1->where('from_user_id',$id)->where('to_user_id',$value['to_user_id']);
                                                    })
                                                    ->orWhere(function ($q1) use ($value,$id){
                                                        $q1->where('from_user_id',$value['to_user_id'])->where('to_user_id',$id)->where('status','>',0);
                                                    });
                                                })->where('order_id',$value->order_id)->orderBy('id','ASC')->orderBy('id','ASC')->get();
                    $this->showHideChat($id,$value['order_id'],$value['to_user_id'],1);
                    }
                    if(isset($value['public_id']))
                    {
                        $userChat2[$key] = PublicOrder::find($value['public_id']);
                        $userChat2[$key]->chat = PublicOrderChat::with('user','flag.user')
                        ->where('public_id',$value['public_id'])
                        ->where(function($q) use ($id){
                            $q->where(function($q1) use ($id){
                                $q1->where('user_id',$id);
                            })
                            ->orWhere(function($q1) use ($id){
                                $q1->where('status','>',0)
                                ->whereHas('publicChat',function($q2) use ($id){
                                    $q2->whereRaw('FIND_IN_SET(?, members)', [$id]);
                                });
                            });
                        })->orderBy('id','ASC')
                        ->get();
                        $this->showHidePublicChat($id,$value['order_id'],$value['public_id'],1);
                    }
                } 
            }
        }
        
        if(!empty($userChat[0]))
        {
            foreach($userChat as $key2 => $val)
            {
                if(isset($val->chat)){
                    $arr =[];
                    foreach ($val->chat as $key => $value) {
                        array_push($arr,$value->message_date);
                    }
                    $arr = array_unique($arr);
                    foreach ($arr as $key => $value) 
                    {
                        $userChat[$key2]->chat[$key]->date = $value;
                    }
                }
            }
        }
        
        if(!empty($userChat2[0]))
        {
            foreach($userChat2 as $key2 => $val)
            {
                if(isset($val->chat)){
                    $arr =[];
                    foreach ($val->chat as $key => $value) {
                        array_push($arr,$value->message_date);
                    }
                    $arr = array_unique($arr);
                    foreach ($arr as $key => $value) 
                    {
                        $userChat2[$key2]->chat[$key]->date = $value;
                    }
                }
            }
        }
        
        if(!empty($userChat[0]) || !empty($userChat2[0]))
        {
            return view('main.phone_quote.show-data.chat-center2',compact('userChat','userChat2'));
        }
        else{
            return response()->json([
                'message'=>'No Data',
                'status'=>false,
                'status_code'=>400
            ]);
        }
        // echo "<pre>";
        // print_r($userChat);
        // exit();
        
    }
}
