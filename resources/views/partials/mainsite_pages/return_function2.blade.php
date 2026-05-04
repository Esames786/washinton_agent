<?php
date_default_timezone_set("Asia/Karachi");
//  $setting = 	App\general_setting::first();
///  ->where('created_at','>=',\Carbon\Carbon::today()->subDays($setting->no_days))
///  25 feb 2020 > show
///
///


function get_role($id, $column)
{
    $setting = 	App\general_setting::first();
    $query = \App\role::where('id', $id)->first();
    if(isset($query->$column))
    {
        return $query->$column;
    }
    return 'No Role';
}


function get_user($id, $column)
{
    $setting = 	App\general_setting::first();
    $query = \App\role::where('id', $id)->first();
    if(isset($query->$column))
    {
        return $query->$column;
    }
    return 'No Role';

}

function get_user_name($id)
{
    $setting = 	App\general_setting::first();
    $query = \App\User::where('id', $id)->first();
    if(isset($query->id))
    {
        if($query->slug)
        {
            return $query->slug;
        }
        else{
            return $query->name.' '.$query->last_name;
        }
    }
    else{
        return '';
    }
}

function get_autoorder($id, $column)
{
    $setting = 	App\general_setting::first();
    // echo "<pre>";
    // print_r($ptype);exit();
    
    
    $p_type = 1;
    $usersetting = App\user_setting::where('user_id', '=', $user->id)->first();
    if (!empty($usersetting)) {
        $p_type = $usersetting['penal_type'];
    }
    
    $user = \Illuminate\Support\Facades\Auth::user();
    $query = \App\AutoOrder::where('id', $id)->where('created_at','>=',\Carbon\Carbon::today()->subDays($setting->no_days))
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
        ->first();
    if (!empty($query->$column)) {
        return $query->$column;
    } else {
        return "NOT FOUND";
    }

}

function count_history($user_id, $order_id)
{
    $setting = 	App\general_setting::first();
    $query = \App\call_history::where('orderId', $order_id)
        ->where('userId', $user_id)->where('created_at','>=',\Carbon\Carbon::today()->subDays($setting->no_days))->count();
    return $query;
}


function get_total_new($id,$ptype)
{
    $setting = 	App\general_setting::first();
    // echo "<pre>";
    // print_r($ptype);exit();
    $user = \Illuminate\Support\Facades\Auth::user();
    if($id==11){
        $data_new = \App\AutoOrder::where('pstatus', '=', $id)->where('approve_pickup','=',1)
            ->where('paneltype', '=', $ptype)
            ->where('created_at','>=',\Carbon\Carbon::today()->subDays($setting->no_days))
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
            })->count();
    }elseif($id==12){
        $data_new = \App\AutoOrder::where('pstatus', '=', $id)->where('approve_deliver','=',1)
                    ->where('paneltype', '=', $ptype)
            ->where('created_at','>=',\Carbon\Carbon::today()->subDays($setting->no_days))
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
            })->count();
    }elseif($id==16){
        $data_new = \App\AutoOrder::where(function($q){
            $q->where(function ($q2){
                $q2->where('pstatus','>=',7)->where('pstatus','<=',14);
            })->orWhere('pstatus',18);
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
        ->where('paneltype', '=', $ptype)
        ->whereBetween('created_at', [\Carbon\Carbon::now()->firstOfMonth()->format('Y-m-d 00:00:00'),\Carbon\Carbon::now()->format('Y-m-d 23:59:59')])
        // ->where('created_at', '>=', \Carbon\Carbon::today()->subDays($setting->no_days))
        ->orderBy('id', 'DESC')->count();
    } 
    elseif($id == 34){
        $data_new = \App\AutoOrder::where(function ($q) use ($user){
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
            ->where('paneltype', '=', $ptype)
            ->where('created_at','>=',\Carbon\Carbon::today()->subDays($setting->no_days))
            ->count();
    } 
    elseif($id == 33){
        $data_new = \App\MilePrice::count();
    } 
    elseif($id == 35){
        $data_new = \App\Group::count();
    }
    elseif($id == 36){
        $data_new = \App\Question::count();
    }
    elseif($id == 37){
        $data_new = \App\AutoOrder::where(function ($q) use ($user){
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
            ->where('paneltype', '=', $ptype)
            ->where('created_at','>=',\Carbon\Carbon::today()->subDays($setting->no_days))
            ->count();
    }
    elseif($id == 17)
    {
        $data_new = \App\carrier::count();
    }
    else{
        $data_new = \App\AutoOrder::where('pstatus', '=', $id)
                    ->where('paneltype', '=', $ptype)
            ->where('created_at','>=',\Carbon\Carbon::today()->subDays($setting->no_days))
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
            })->count();
    }


    if (!empty($data_new)) {
        return $data_new;
    } else {
        return 0;
    }
}

function get_total_pickup_approval($id,$ptype)
{
    $setting = 	App\general_setting::first();
    $uid = '';
    // echo "<pre>";
    // print_r($ptype);exit();
    $user = \Illuminate\Support\Facades\Auth::user();
    $data_new = \App\AutoOrder::where('pstatus', '=', $id)
    ->where('paneltype', '=', $ptype)
    ->where(function($q){
        $q->where('approve_pickup','=',0)
        ->orWhere('approve_pickup', '=', NULL);
    })->where('created_at','>=',\Carbon\Carbon::today()->subDays($setting->no_days))
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
    ->orderBy('id', 'DESC')->count();
    if (!empty($data_new)) {
        return $data_new;
    } else {
        return 0;
    }
}
function get_total_deliver_approval($id,$ptype)
{
    $setting = 	App\general_setting::first();
    $uid = '';
    // echo "<pre>";
    // print_r($ptype);exit();
    $user = \Illuminate\Support\Facades\Auth::user();
    $data_new = \App\AutoOrder::where('pstatus', '=', $id)
    ->where('paneltype', '=', $ptype)
    ->where(function($q){
        $q->where('approve_deliver','=',0)
        ->orWhere('approve_deliver', '=', NULL);
    })->where('created_at','>=',\Carbon\Carbon::today()->subDays($setting->no_days))
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
    ->orderBy('id', 'DESC')->count();
    if (!empty($data_new)) {
        return $data_new;
    } else {
        return 0;
    }
}
function get_total_deliver_schedule($id,$ptype)
{
    $setting = 	App\general_setting::first();
    $uid = '';
    // echo "<pre>";
    // print_r($ptype);exit();
    $user = \Illuminate\Support\Facades\Auth::user();
    $data_new = \App\AutoOrder::where('pstatus', '=', $id)
    ->where('paneltype', '=', $ptype)
    ->where('approve_deliver','=',2)
    ->where('created_at','>=',\Carbon\Carbon::today()->subDays($setting->no_days))
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
    ->orderBy('id', 'DESC')->count();
    if (!empty($data_new)) {
        return $data_new;
    } else {
        return 0;
    }
}
function get_pstatus($id)
{

    $ret = "";
    if ($id == 0) {
        $ret = "NEW";
    } elseif ($id == 1) {
        $ret = "Interested";
    } elseif ($id == 2) {
        $ret = "FollowMore";
    } elseif ($id == 3) {
        $ret = "AskingLow";
    } elseif ($id == 4) {
        $ret = "NotInterested";
    } elseif ($id == 5) {
        $ret = "NoResponse";
    } elseif ($id == 6) {
        $ret = "TimeQuote";
    } elseif ($id == 7) {
        $ret = "PaymentMissing";
    } elseif ($id == 8) {
        $ret = "Booked";
    } elseif ($id == 9) {
        $ret = "Listed";
    } elseif ($id == 10) {
        $ret = "Schedule";
    } elseif ($id == 11) {
        $ret = "Pickup";
    } elseif ($id == 12) {
        $ret = "Delivered";
    } elseif ($id == 13) {
        $ret = "Completed";
    } elseif ($id == 14) {
        $ret = "Cancel";
    } elseif ($id == 15) {
        $ret = "Deleted";
    } elseif ($id == 16) {
        $ret = "OwesMoney";
    } elseif ($id == 17) {
        $ret = "CarrierUpdate";
    } elseif ($id == 18) {
        $ret = "OnApproval";
    }elseif ($id == 19) {
        $ret = "On Approval Cancelled";
    }elseif ($id == 99) {
        $ret = "Approaching";
    }
    return $ret;

}

function get_oterminal_name($id){
    $terminal="";
    if($id==1){
        $terminal='Residence';
    }elseif($id==2){
        $terminal='COPART Auction';
    }elseif($id==3){
        $terminal='Manheim Auction';
    }elseif($id==4){
        $terminal='Auction';
    }elseif($id==5){
        $terminal='Shop';
    }elseif($id==10){
        $terminal='Dealership';
    }elseif($id==7){
        $terminal='Business Location';
    }elseif($id==8){
        $terminal='Auction (Heavy)';
    }elseif($id==6){
        $terminal='Other';
    }
    return $terminal;
}


function get_dterminal_name($id){
    $terminal="";
    if($id==1){
        $terminal='Residence';
    }elseif($id==2){
        $terminal='COPART Auction';
    }elseif($id==3){
        $terminal='Manheim Auction';
    }elseif($id==4){
        $terminal='IAAI Auction';
    }elseif($id==5){
        $terminal='Body Shop';
    }elseif($id==10){
        $terminal='Auction(Heavy)';
    }elseif($id==7){
        $terminal='Port';
    }elseif($id==8){
        $terminal='Other';
    }elseif($id==6){
        $terminal='AirPort';
    }elseif($id==9){
        $terminal='Business Location';
    }elseif($id==11){
        $terminal='Dealership';
    }
    return $terminal;
}
function get_cartype($id)
{
    if ($id == 1) {
        return "Open";
    } else {
        return "Enclosed";
    }

}

function get_car_or_heavy($id)
{
    if ($id == 1) {
        return  '<span class="badge badge-pill badge-warning mt-2 ">'.'CAR/MOTOR'.'</span>';
    } elseif ($id == 2) {
        return  '<span class="badge badge-pill badge-danger mt-2 ">' .'HEAVY'.'</span>';
    }

}



function get_condtion($id)
{

    if ($id == 1) {
        return "Running";
    } else {
        return "Not- Running";
    }
}
//shariq
function get_payment_detail($id,$column)
{
    $setting = 	App\general_setting::first();
    $paid = \App\orderpayment::where('orderId', $id)->where('created_at','>=',\Carbon\Carbon::today()->subDays($setting->no_days))->first();

    if (!empty($paid)) {

        return ucfirst($paid->$column);

    } else {
        return $column;
    }

}



//faisal

function get_paid($id)
{
    $setting = 	App\general_setting::first();
    $value = "Unpaid";
    $paid = \App\orderpayment::where('orderId', $id)->where('created_at','>=',\Carbon\Carbon::today()->subDays($setting->no_days))->first();

    if(!empty($paid)){

        return  ucfirst($paid->payment_status);

    }else{
        return $value;
    }
}

function get_carrier($id,$column)
{
    $setting =	App\general_setting::first();
    $query = 	App\carrier::where('orderId','=',$id)->where('created_at','>=',\Carbon\Carbon::today()->subDays($setting->no_days))->first();
    if (!empty($query->$column)) {
        return $query->$column;
    }

}

function get_total_invoice($ptype)
{
    $setting =	App\general_setting::first();
    $data = 	App\carrier::where('created_at','>=',\Carbon\Carbon::today()->subDays($setting->no_days))
        ->orderBy('id', 'DESC')->count();
    if (!empty($data)) {
        return $data;
    } else {
        return 0;
    }

}
function get_total_storage($ptype)
{
    $setting =	App\general_setting::first();
    // $data = 	App\storage::where('created_at','>=',\Carbon\Carbon::today()->subDays($setting->no_days))
    //     ->orderBy('id', 'DESC')->count();
    
    $ptype = 1;
    $user = Auth::user();
    $usersetting = App\user_setting::where('user_id', '=', $user->id)->first();
    if (!empty($usersetting)) {
        $ptype = $usersetting['penal_type'];
    }
    
    $data = \App\AutoOrder::where('pstatus',11)->where('storage_id','>',0)
    ->where('paneltype', '=', $ptype)->where('created_at','>=',\Carbon\Carbon::today()->subDays($setting->no_days))
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
    })->count();
    if (!empty($data)) {
        return $data;
    } else {
        return 0;
    }

}

function get_total_carrier($ptype)
{
    $setting =	App\general_setting::first();
    $data = 	App\carrier::where('created_at','>=',\Carbon\Carbon::today()->subDays($setting->no_days))
        ->orderBy('id', 'DESC')->count();
    if (!empty($data)) {
        return $data;
    } else {
        return 0;
    }
}

function get_total_customer($ptype)
{
    $setting =	App\general_setting::first();
    $uid = '';
    // echo "<pre>";
    // print_r($ptype);exit();
    $user = \Illuminate\Support\Facades\Auth::user();
    $data = App\AutoOrder::where('created_at','>=',\Carbon\Carbon::today()->subDays($setting->no_days))
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
        ->orderBy('id', 'DESC')->count();
    if (!empty($data)) {
        return $data;
    } else {
        return 0;
    }
}


function get_user_attendance($date,$user_id,$value){

    $data = App\attendance::where('attendance_date','like','%'.$date.'%')
        ->where('user_id','=',$user_id)->first();
    if (!empty($data)) {
        return date("M-d-Y",strtotime($data->$value));
    } else {
        return "NOT FOUND";
    }

}

function issue_comments($issue_id,$user_id){

    $data = App\issue_chats::where('issueId', $issue_id)->where('userId', $user_id)->first();
    if (!empty($data)) {
        return $data->comments;
    } else {
        return "NOT FOUND";
    }

}






?>
