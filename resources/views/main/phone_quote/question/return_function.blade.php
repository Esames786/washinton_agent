<?php
date_default_timezone_set("Asia/Karachi");

function check_user_setting($user_id)
{
    $p_type = 1;
    $usersetting = \App\user_setting::where('user_id', '=', $user_id)->first();
    if (!empty($usersetting)) {
        $p_type = $usersetting['penal_type'];
    }
    return $p_type;
}


function typeCondition($value,$key)
{
    $tc = [];
    foreach($value as $val)
    {
        if($val == '1')
        {
            if($key == 0)
            {
                $val = 'open';
            }
            else{
                $val = 'operable';
            }
        }
        else if($val == '2')
        {
            if($key == 0)
            {
                $val = 'enclosed';
            }
            else{
                $val = 'non-running';
            }
        }
        array_push($tc,$val);
    }
    $all = implode('*^_',$tc);
    return $all;
}


function listedToCancel($id,$val) 
{
    $setting = 	App\general_setting::first();
    $user = \Illuminate\Support\Facades\Auth::user();
    $ptype = check_user_setting(Auth::user()->id);
    $data = '';
    
    if($id == 11 && $val == 0)
    {
        $data = \App\AutoOrder::where('pstatus',$id)
        ->where(function($q){
            $q
            ->where('approve_pickup',0)
            ->orWhere('approve_pickup',NULL);
        })->where('paneltype', '=', $ptype)
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
    elseif($id == 11 && $val == 1)
    {
        $data = \App\AutoOrder::where('pstatus',$id)->where('approve_pickup',1)
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
    elseif($id == 12 && $val == 0)
    {
        $data = \App\AutoOrder::where('pstatus',$id)
        ->where(function($q){
            $q
            ->where('approve_deliver',0)
            ->orWhere('approve_deliver',NULL);
        })->where('paneltype', '=', $ptype)
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
    elseif($id == 12 && $val == 2)
    {
        $data = \App\AutoOrder::where('pstatus',$id)->where('approve_deliver',2)
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
    elseif($id == 12 && $val == 1)
    {
        $data = \App\AutoOrder::where('pstatus',$id)->where('approve_deliver',1)
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
    elseif($id == 16)
    {
        $data = \App\AutoOrder::where('pstatus','<>',0)
        ->where(function ($q){
            $q->where('owes', '>', 0)
            ->orWhere('owes_money', 1);
        })
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
    else if($id == 22)
    {
        $data = \App\AutoOrder::where('pstatus','<',7)
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
    else
    {
        $data = \App\AutoOrder::where('pstatus',$id)
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
    return $data;
}

function pstaus($id)
{
    $ret = "";
    if ($id == 0) {
        $ret = "<span class='badge badge-orange txt-white'>New</span>";
    } elseif ($id == 1) {
        $ret ="<span class='badge badge-warning txt-white'>Interested</span>";
    } elseif ($id == 2) {
        $ret = "<span class='badge badge-primary txt-white'>FollowMore</span>";
    } elseif ($id == 3) {
        $ret =  "<span class='badge badge-pink txt-white'>AskingLow</span>";
    } elseif ($id == 4) {
        $ret = "<span class='badge badge-success '>Not Interested</span>";
    } elseif ($id == 5) {
        $ret = "<span class='badge badge-dark txt-white'>No Response</span>";
    } elseif ($id == 6) {
        $ret = "<span class='badge badge-amber txt-white'>Time Quote</span>";
    } elseif ($id == 7) {
        $ret = "<span class='badge badge-primary  txt-white'>Payment Missing</span>";
    } elseif ($id == 8) {
        $ret = "<span class='badge badge-warning  txt-white'>Booked</span>";
    } elseif ($id == 9) {
        $ret = "<span class='badge badge-pink txt-white'>Listed</span>";
    } elseif ($id == 10) {
        $ret = "<span class='badge badge-success'>Dispatch</span>";
    } elseif ($id == 11) {
        $ret = "<span class='badge badge-dark txt-white'>Pickup</span>";
    } elseif ($id == 12) {
        $ret =  "<span class='badge badge-amber txt-white'>Delivered</span>";
    } elseif ($id == 13) {
        $ret = "<span class='badge badge-teal txt-white'>Completed</span>";
    } elseif ($id == 14) {
        $ret = "<span class='badge badge-danger txt-white'>Cancel</span>";
    } elseif ($id == 15) {
        $ret = "<span class='badge badge-danger txt-white'>Deleted</span>";
    } elseif ($id == 16) {
        $ret = "<span class='badge badge-primary txt-white'>OwesMoney</span>";
    } elseif ($id == 17) {
        $ret = "<span class='badge badge-primary txt-white'>Carrier Update</span>";
    } elseif ($id == 18) {
        $ret = "<span class='badge badge-primary txt-white'>On Approval</span>";
    }elseif ($id == 19) {
        $ret = "<span class='badge badge-dangerget_car_or_heavy txt-white'>On Approval Canceled</span>";
    }
    return $ret;
}