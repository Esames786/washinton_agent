<?php 
    function check_user_setting($user_id)
    {
        $p_type = 1;
        $usersetting = \App\user_setting::where('user_id', '=', $user_id)->first();
        if (!empty($usersetting)) {
            $p_type = $usersetting['penal_type'];
        }
        return $p_type;
    }

    function rating6($total,$id,$pstatus,$from,$to,$count,$company)
    {
        $total = $total == 0 ? $count : $total; 
        $user = \App\User::find($id);
        $rate = 0;
        if($pstatus == 6)
        {
            $eq = "<=";
        }
        else
        {
            $eq = "=";
        }
        if(isset($user->id))
        {
            $ptype = check_user_setting($user->id);
            if($user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent' || $user->userRole->name == 'Order Taker' || $user->userRole->name == 'Manager')
            {
                if($user->order_taker_quote == 1 || $user->order_taker_quote == 0)
                {
                    $total_order = \App\AutoOrder::where('order_taker_id',$id)->where('pstatus',$eq,$pstatus)->where('paneltype', '=', $ptype)
                    ->where(function ($q2){ 
                        $q2->where('booking_confirm','may be')
                        ->orWhere('booking_confirm','confirm'); 
                    });
                    if(!empty($company))
                    {
                        $total_order = $total_order->whereHas('carrier',function ($q) use ($company){
                            $q->where('companyname',$company);
                        });
                    }
                    if(!empty($from) && !empty($to))
                    {
                        $total_order = $total_order->where(function ($q) use ($from,$to){
                            $q->whereBetween('created_at',[$from,$to]);
                        });
                    }
                    $total_order = $total_order->count();
                    $rate = ($total_order * $count) / $total;
                }
                else if($user->order_taker_quote == 2)
                {
                    $total_order = \App\AutoOrder::whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$id])->where('pstatus',$eq,$pstatus)->where('paneltype', '=', $ptype)
                    ->where(function ($q2){ 
                        $q2->where('booking_confirm','may be')
                        ->orWhere('booking_confirm','confirm'); 
                    });
                    if(!empty($company))
                    {
                        $total_order = $total_order->whereHas('carrier',function ($q) use ($company){
                            $q->where('companyname',$company);
                        });
                    }
                    if(!empty($from) && !empty($to))
                    {
                        $total_order = $total_order->where(function ($q) use ($from,$to){
                            $q->whereBetween('created_at',[$from,$to]);
                        });
                    }
                    $total_order = $total_order->count();
                    $rate = ($total_order * $count) / $total;
                }
            }
            else if($user->userRole->name == 'Dispatcher')
            {
                $total_order = \App\AutoOrder::where('dispatcher_id',$id)->where('pstatus',$eq,$pstatus)->where('paneltype', '=', $ptype)
                    ->where(function ($q2){ 
                        $q2->where('booking_confirm','may be')
                        ->orWhere('booking_confirm','confirm'); 
                    });
                    if(!empty($company))
                    {
                        $total_order = $total_order->whereHas('carrier',function ($q) use ($company){
                            $q->where('companyname',$company);
                        });
                    }
                    if(!empty($from) && !empty($to))
                    {
                        $total_order = $total_order->where(function ($q) use ($from,$to){
                            $q->whereBetween('created_at',[$from,$to]);
                        });
                    }
                    $total_order = $total_order->count();
                $rate = ($total_order * $count) / $total;
            }
            else if($user->userRole->name == 'Delivery Boy')
            {
                $total_order = \App\AutoOrder::where('delivery_boy_id',$id)->where('pstatus',$eq,$pstatus)->where('paneltype', '=', $ptype)
                    ->where(function ($q2){ 
                        $q2->where('booking_confirm','may be')
                        ->orWhere('booking_confirm','confirm'); 
                    });
                    if(!empty($company))
                    {
                        $total_order = $total_order->whereHas('carrier',function ($q) use ($company){
                            $q->where('companyname',$company);
                        });
                    }
                    if(!empty($from) && !empty($to))
                    {
                        $total_order = $total_order->where(function ($q) use ($from,$to){
                            $q->whereBetween('created_at',[$from,$to]);
                        });
                    }
                    $total_order = $total_order->count();
                $rate = ($total_order * $count) / $total;
            }
            else if($user->userRole->name == 'Manager')
            {
                $total_order = \App\AutoOrder::where(function ($q) use ($id)
                {
                  $q->where('manager_id',$id)->orWhere('order_taker_id',$id);  
                })->where('pstatus',$eq,$pstatus)->where('paneltype', '=', $ptype)
                    ->where(function ($q2){ 
                        $q2->where('booking_confirm','may be')
                        ->orWhere('booking_confirm','confirm'); 
                    });
                    if(!empty($company))
                    {
                        $total_order = $total_order->whereHas('carrier',function ($q) use ($company){
                            $q->where('companyname',$company);
                        });
                    }
                    if(!empty($from) && !empty($to))
                    {
                        $total_order = $total_order->where(function ($q) use ($from,$to){
                            $q->whereBetween('created_at',[$from,$to]);
                        });
                    }
                    $total_order = $total_order->count();
                $rate = ($total_order * $count) / $total;
            }
        }
        else
        {
            $id = Auth::user()->id;
            $ptype = check_user_setting($id);
            if(Auth::user()->userRole->name == 'CSR' || Auth::user()->userRole->name == 'Seller Agent' || Auth::user()->userRole->name == 'Order Taker' || Auth::user()->userRole->name == 'Manager')
            {
                if(Auth::user()->order_taker_quote == 1 || Auth::user()->order_taker_quote == 0)
                {
                    $total_order = \App\AutoOrder::where('order_taker_id',$id)->where('pstatus',$eq,$pstatus)->where('paneltype', '=', $ptype)
                    ->where(function ($q2){ 
                        $q2->where('booking_confirm','may be')
                        ->orWhere('booking_confirm','confirm'); 
                    });
                    if(!empty($company))
                    {
                        $total_order = $total_order->whereHas('carrier',function ($q) use ($company){
                            $q->where('companyname',$company);
                        });
                    }
                    if(!empty($from) && !empty($to))
                    {
                        $total_order = $total_order->where(function ($q) use ($from,$to){
                            $q->whereBetween('created_at',[$from,$to]);
                        });
                    }
                    $total_order = $total_order->count();
                    $rate = ($total_order * $count) / $total;
                }
                else if(Auth::user()->order_taker_quote == 2)
                {
                    $total_order = \App\AutoOrder::whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$id])->where('pstatus',$eq,$pstatus)->where('paneltype', '=', $ptype)
                    ->where(function ($q2){ 
                        $q2->where('booking_confirm','may be')
                        ->orWhere('booking_confirm','confirm'); 
                    });
                    if(!empty($company))
                    {
                        $total_order = $total_order->whereHas('carrier',function ($q) use ($company){
                            $q->where('companyname',$company);
                        });
                    }
                    if(!empty($from) && !empty($to))
                    {
                        $total_order = $total_order->where(function ($q) use ($from,$to){
                            $q->whereBetween('created_at',[$from,$to]);
                        });
                    }
                    $total_order = $total_order->count();
                    $rate = ($total_order * $count) / $total;
                }
            }
            else if(Auth::user()->userRole->name == 'Dispatcher')
            {
                $total_order = \App\AutoOrder::where('dispatcher_id',Auth::user()->id)->where('pstatus',$eq,$pstatus)->where('paneltype', '=', $ptype)
                    ->where(function ($q2){ 
                        $q2->where('booking_confirm','may be')
                        ->orWhere('booking_confirm','confirm'); 
                    });
                    if(!empty($company))
                    {
                        $total_order = $total_order->whereHas('carrier',function ($q) use ($company){
                            $q->where('companyname',$company);
                        });
                    }
                    if(!empty($from) && !empty($to))
                    {
                        $total_order = $total_order->where(function ($q) use ($from,$to){
                            $q->whereBetween('created_at',[$from,$to]);
                        });
                    }
                    $total_order = $total_order->count();
                $rate = ($total_order * $count) / $total;
            }
            else if(Auth::user()->userRole->name == 'Delivery Boy')
            {
                $total_order = \App\AutoOrder::where('delivery_boy_id',Auth::user()->id)->where('pstatus',$eq,$pstatus)->where('paneltype', '=', $ptype)
                    ->where(function ($q2){ 
                        $q2->where('booking_confirm','may be')
                        ->orWhere('booking_confirm','confirm'); 
                    });
                    if(!empty($company))
                    {
                        $total_order = $total_order->whereHas('carrier',function ($q) use ($company){
                            $q->where('companyname',$company);
                        });
                    }
                    if(!empty($from) && !empty($to))
                    {
                        $total_order = $total_order->where(function ($q) use ($from,$to){
                            $q->whereBetween('created_at',[$from,$to]);
                        });
                    }
                    $total_order = $total_order->count();
                $rate = ($total_order * $count) / $total;
            }
            else if(Auth::user()->userRole->name == 'Manager')
            {
                $total_order = \App\AutoOrder::where(function ($q) use ($id)
                {
                  $q->where('manager_id',$id)->orWhere('order_taker_id',$id);  
                })->where('pstatus',$eq,$pstatus)->where('paneltype', '=', $ptype)
                    ->where(function ($q2){ 
                        $q2->where('booking_confirm','may be')
                        ->orWhere('booking_confirm','confirm'); 
                    });
                    if(!empty($company))
                    {
                        $total_order = $total_order->whereHas('carrier',function ($q) use ($company){
                            $q->where('companyname',$company);
                        });
                    }
                    if(!empty($from) && !empty($to))
                    {
                        $total_order = $total_order->where(function ($q) use ($from,$to){
                            $q->whereBetween('created_at',[$from,$to]);
                        });
                    }
                    $total_order = $total_order->count();
                $rate = ($total_order * $count) / $total;
            }
            else
            {
                $total_order = \App\AutoOrder::where('pstatus',$eq,$pstatus)->where('paneltype', '=', $ptype)
                    ->where(function ($q2){ 
                        $q2->where('booking_confirm','may be')
                        ->orWhere('booking_confirm','confirm'); 
                    });
                    if(!empty($company))
                    {
                        $total_order = $total_order->whereHas('carrier',function ($q) use ($company){
                            $q->where('companyname',$company);
                        });
                    }
                    if(!empty($from) && !empty($to))
                    {
                        $total_order = $total_order->where(function ($q) use ($from,$to){
                            $q->whereBetween('created_at',[$from,$to]);
                        });
                    }
                    $total_order = $total_order->count();
                $rate = ($total_order * $count) / $total;
            }
        }
        $rate = round($rate,1);
        return $rate;
    }
    
    
    function pstatusRole6($id,$pstatus,$from,$to,$company)
    {
        $total_order = 0;
        $user = \App\User::find($id);
        if($pstatus == 6)
        {
            $eq = "<=";
        }
        else
        {
            $eq = "=";
        }
        if(isset($user->id))
        {
            $ptype = check_user_setting($user->id);
            if($user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent' || $user->userRole->name == 'Order Taker' || $user->userRole->name == 'Manager')
            {
                if($user->order_taker_quote == 1 || $user->order_taker_quote == 0)
                {
                    $total_order = \App\AutoOrder::where('order_taker_id',$id)->where('pstatus',$eq,$pstatus)->where('paneltype', '=', $ptype)
                    ->where(function ($q2){ 
                        $q2->where('booking_confirm','may be')
                        ->orWhere('booking_confirm','confirm'); 
                    });
                    if(!empty($company))
                    {
                        $total_order = $total_order->whereHas('carrier',function ($q) use ($company){
                            $q->where('companyname',$company);
                        });
                    }
                    if(!empty($from) && !empty($to))
                    {
                        $total_order = $total_order->where(function ($q) use ($from,$to){
                            $q->whereBetween('created_at',[$from,$to]);
                        });
                    }
                    $total_order = $total_order->count();
                }
                else if($user->order_taker_quote == 2)
                {
                    $total_order = \App\AutoOrder::whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$id])->where('pstatus',$eq,$pstatus)->where('paneltype', '=', $ptype)
                    ->where(function ($q2){ 
                        $q2->where('booking_confirm','may be')
                        ->orWhere('booking_confirm','confirm'); 
                    });
                    if(!empty($company))
                    {
                        $total_order = $total_order->whereHas('carrier',function ($q) use ($company){
                            $q->where('companyname',$company);
                        });
                    }
                    if(!empty($from) && !empty($to))
                    {
                        $total_order = $total_order->where(function ($q) use ($from,$to){
                            $q->whereBetween('created_at',[$from,$to]);
                        });
                    }
                    $total_order = $total_order->count();
                }
            }
            else if($user->userRole->name == 'Dispatcher')
            {
                $total_order = \App\AutoOrder::where('dispatcher_id',$id)->where('pstatus',$eq,$pstatus)->where('paneltype', '=', $ptype)
                    ->where(function ($q2){ 
                        $q2->where('booking_confirm','may be')
                        ->orWhere('booking_confirm','confirm'); 
                    });
                    if(!empty($company))
                    {
                        $total_order = $total_order->whereHas('carrier',function ($q) use ($company){
                            $q->where('companyname',$company);
                        });
                    }
                    if(!empty($from) && !empty($to))
                    {
                        $total_order = $total_order->where(function ($q) use ($from,$to){
                            $q->whereBetween('created_at',[$from,$to]);
                        });
                    }
                    $total_order = $total_order->count();
            }
            else if($user->userRole->name == 'Delivery Boy')
            {
                $total_order = \App\AutoOrder::where('delivery_boy_id',$id)->where('pstatus',$eq,$pstatus)->where('paneltype', '=', $ptype)
                    ->where(function ($q2){ 
                        $q2->where('booking_confirm','may be')
                        ->orWhere('booking_confirm','confirm'); 
                    });
                    if(!empty($company))
                    {
                        $total_order = $total_order->whereHas('carrier',function ($q) use ($company){
                            $q->where('companyname',$company);
                        });
                    }
                    if(!empty($from) && !empty($to))
                    {
                        $total_order = $total_order->where(function ($q) use ($from,$to){
                            $q->whereBetween('created_at',[$from,$to]);
                        });
                    }
                    $total_order = $total_order->count();
            }
            else if($user->userRole->name == 'Manager')
            {
                $total_order = \App\AutoOrder::where(function ($q) use ($id)
                {
                  $q->where('manager_id',$id)->orWhere('order_taker_id',$id);  
                })->where('pstatus',$eq,$pstatus)->where('paneltype', '=', $ptype)
                    ->where(function ($q2){ 
                        $q2->where('booking_confirm','may be')
                        ->orWhere('booking_confirm','confirm'); 
                    });
                    if(!empty($company))
                    {
                        $total_order = $total_order->whereHas('carrier',function ($q) use ($company){
                            $q->where('companyname',$company);
                        });
                    }
                    if(!empty($from) && !empty($to))
                    {
                        $total_order = $total_order->where(function ($q) use ($from,$to){
                            $q->whereBetween('created_at',[$from,$to]);
                        });
                    }
                    $total_order = $total_order->count();
            }
        }
        else
        {
            $id = Auth::user()->id;
            $ptype = check_user_setting($id);
            if(Auth::user()->userRole->name == 'CSR' || Auth::user()->userRole->name == 'Seller Agent' || Auth::user()->userRole->name == 'Order Taker' || Auth::user()->userRole->name == 'Manager')
            {
                if(Auth::user()->order_taker_quote == 1 || Auth::user()->order_taker_quote == 0)
                {
                    $total_order = \App\AutoOrder::where('order_taker_id',$id)->where('pstatus',$eq,$pstatus)->where('paneltype', '=', $ptype)
                    ->where(function ($q2){ 
                        $q2->where('booking_confirm','may be')
                        ->orWhere('booking_confirm','confirm'); 
                    });
                    if(!empty($company))
                    {
                        $total_order = $total_order->whereHas('carrier',function ($q) use ($company){
                            $q->where('companyname',$company);
                        });
                    }
                    if(!empty($from) && !empty($to))
                    {
                        $total_order = $total_order->where(function ($q) use ($from,$to){
                            $q->whereBetween('created_at',[$from,$to]);
                        });
                    }
                    $total_order = $total_order->count();
                }
                else if(Auth::user()->order_taker_quote == 2)
                {
                    $total_order = \App\AutoOrder::whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$id])->where('pstatus',$eq,$pstatus)->where('paneltype', '=', $ptype)
                    ->where(function ($q2){ 
                        $q2->where('booking_confirm','may be')
                        ->orWhere('booking_confirm','confirm'); 
                    });
                    if(!empty($company))
                    {
                        $total_order = $total_order->whereHas('carrier',function ($q) use ($company){
                            $q->where('companyname',$company);
                        });
                    }
                    if(!empty($from) && !empty($to))
                    {
                        $total_order = $total_order->where(function ($q) use ($from,$to){
                            $q->whereBetween('created_at',[$from,$to]);
                        });
                    }
                    $total_order = $total_order->count();
                }
            }
            else if(Auth::user()->userRole->name == 'Dispatcher')
            {
                $total_order = \App\AutoOrder::where('dispatcher_id',$id)->where('pstatus',$eq,$pstatus)->where('paneltype', '=', $ptype)
                    ->where(function ($q2){ 
                        $q2->where('booking_confirm','may be')
                        ->orWhere('booking_confirm','confirm'); 
                    });
                    if(!empty($company))
                    {
                        $total_order = $total_order->whereHas('carrier',function ($q) use ($company){
                            $q->where('companyname',$company);
                        });
                    }
                    if(!empty($from) && !empty($to))
                    {
                        $total_order = $total_order->where(function ($q) use ($from,$to){
                            $q->whereBetween('created_at',[$from,$to]);
                        });
                    }
                    $total_order = $total_order->count();
            }
            else if(Auth::user()->userRole->name == 'Delivery Boy')
            {
                $total_order = \App\AutoOrder::where('delivery_boy_id',$id)->where('pstatus',$eq,$pstatus)->where('paneltype', '=', $ptype)
                    ->where(function ($q2){ 
                        $q2->where('booking_confirm','may be')
                        ->orWhere('booking_confirm','confirm'); 
                    });
                    if(!empty($company))
                    {
                        $total_order = $total_order->whereHas('carrier',function ($q) use ($company){
                            $q->where('companyname',$company);
                        });
                    }
                    if(!empty($from) && !empty($to))
                    {
                        $total_order = $total_order->where(function ($q) use ($from,$to){
                            $q->whereBetween('created_at',[$from,$to]);
                        });
                    }
                    $total_order = $total_order->count();
            }
            else if(Auth::user()->userRole->name == 'Manager')
            {
                $total_order = \App\AutoOrder::where(function ($q) use ($id)
                {
                  $q->where('manager_id',$id)->orWhere('order_taker_id',$id);  
                })->where('pstatus',$eq,$pstatus)->where('paneltype', '=', $ptype)
                    ->where(function ($q2){ 
                        $q2->where('booking_confirm','may be')
                        ->orWhere('booking_confirm','confirm'); 
                    });
                    if(!empty($company))
                    {
                        $total_order = $total_order->whereHas('carrier',function ($q) use ($company){
                            $q->where('companyname',$company);
                        });
                    }
                    if(!empty($from) && !empty($to))
                    {
                        $total_order = $total_order->where(function ($q) use ($from,$to){
                            $q->whereBetween('created_at',[$from,$to]);
                        });
                    }
                    $total_order = $total_order->count();
            }
            else
            {
                $total_order = \App\AutoOrder::where('pstatus',$eq,$pstatus)->where('paneltype', '=', $ptype)
                    ->where(function ($q2){ 
                        $q2->where('booking_confirm','may be')
                        ->orWhere('booking_confirm','confirm'); 
                    });
                    if(!empty($company))
                    {
                        $total_order = $total_order->whereHas('carrier',function ($q) use ($company){
                            $q->where('companyname',$company);
                        });
                    }
                    if(!empty($from) && !empty($to))
                    {
                        $total_order = $total_order->where(function ($q) use ($from,$to){
                            $q->whereBetween('created_at',[$from,$to]);
                        });
                    }
                    $total_order = $total_order->count();
            }
        }
        return $total_order;
    }
    
    function color($total,$id,$pstatus,$from,$to,$count,$company)
    {
        if(rating6($total,$id,$pstatus,$from,$to,$count,$company) > 80)
        {
            $color = 'bg-success';
        }
        else if(rating6($total,$id,$pstatus,$from,$to,$count,$company) <= 80 && rating6($total,$id,$pstatus,$from,$to,$count,$company) > 60)
        {
            $color = 'bg-warning';
        }
        else
        {
            $color = 'bg-danger';
        }
        return $color;
    }
?>

<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="card bg-white mb-3">
      <div class="card-header d-flex justify-content-center text-capitalize bg-light">New To Time Quote<span class="rounded badge badge-success ml-2">({{pstatusRole6($id,6,$from,$to,$company)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-center align-items-center">Average Rating: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,6,$from,$to,5,$company)}} text-light">{{rating6($total_order,$id,6,$from,$to,5,$company)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-center align-items-center">Average Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,6,$from,$to,100,$company)}} text-light">{{rating6($total_order,$id,6,$from,$to,100,$company)}}/100%</span></h5>
      </div>
    </div>
</div>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="card bg-white mb-3">
      <div class="card-header d-flex justify-content-center text-capitalize bg-light">PaymentMissing<span class="rounded badge badge-success ml-2">({{pstatusRole6($id,7,$from,$to,$company)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-center align-items-center">Average Rating: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,7,$from,$to,5,$company)}} text-light">{{rating6($total_order,$id,7,$from,$to,5,$company)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-center align-items-center">Average Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,7,$from,$to,100,$company)}} text-light">{{rating6($total_order,$id,7,$from,$to,100,$company)}}/100%</span></h5>
      </div>
    </div>
</div>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="card bg-white mb-3">
      <div class="card-header d-flex justify-content-center text-capitalize bg-light">Booked <span class="rounded badge badge-success ml-2">({{pstatusRole6($id,8,$from,$to,$company)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-center align-items-center">Average Rating: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,8,$from,$to,5,$company)}} text-light">{{rating6($total_order,$id,8,$from,$to,5,$company)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-center align-items-center">Average Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,8,$from,$to,100,$company)}} text-light">{{rating6($total_order,$id,8,$from,$to,100,$company)}}/100%</span></h5>
      </div>
    </div>
</div>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="card bg-white mb-3">
      <div class="card-header d-flex justify-content-center text-capitalize bg-light">OnApproval <span class="rounded badge badge-success ml-2">({{pstatusRole6($id,18,$from,$to,$company)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-center align-items-center">Average Rating: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,18,$from,$to,5,$company)}} text-light">{{rating6($total_order,$id,18,$from,$to,5,$company)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-center align-items-center">Average Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,18,$from,$to,100,$company)}} text-light">{{rating6($total_order,$id,18,$from,$to,100,$company)}}/100%</span></h5>
      </div>
    </div>
</div>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="card bg-white mb-3">
      <div class="card-header d-flex justify-content-center text-capitalize bg-light">Listed <span class="rounded badge badge-success ml-2">({{pstatusRole6($id,9,$from,$to,$company)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-center align-items-center">Average Rating: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,9,$from,$to,5,$company)}} text-light">{{rating6($total_order,$id,9,$from,$to,5,$company)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-center align-items-center">Average Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,9,$from,$to,100,$company)}} text-light">{{rating6($total_order,$id,9,$from,$to,100,$company)}}/100%</span></h5>
      </div>
    </div>
</div>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="card bg-white mb-3">
      <div class="card-header d-flex justify-content-center text-capitalize bg-light">Schedule <span class="rounded badge badge-success ml-2">({{pstatusRole6($id,10,$from,$to,$company)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-center align-items-center">Average Rating: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,10,$from,$to,5,$company)}} text-light">{{rating6($total_order,$id,10,$from,$to,5,$company)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-center align-items-center">Average Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,10,$from,$to,100,$company)}} text-light">{{rating6($total_order,$id,10,$from,$to,100,$company)}}/100%</span></h5>
      </div>
    </div>
</div>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="card bg-white mb-3">
      <div class="card-header d-flex justify-content-center text-capitalize bg-light">Pickup <span class="rounded badge badge-success ml-2">({{pstatusRole6($id,11,$from,$to,$company)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-center align-items-center">Average Rating: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,11,$from,$to,5,$company)}} text-light">{{rating6($total_order,$id,11,$from,$to,5,$company)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-center align-items-center">Average Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,11,$from,$to,100,$company)}} text-light">{{rating6($total_order,$id,11,$from,$to,100,$company)}}/100%</span></h5>
      </div>
    </div>
</div>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="card bg-white mb-3">
      <div class="card-header d-flex justify-content-center text-capitalize bg-light">Delivered <span class="rounded badge badge-success ml-2">({{pstatusRole6($id,12,$from,$to,$company)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-center align-items-center">Average Rating: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,12,$from,$to,5,$company)}} text-light">{{rating6($total_order,$id,12,$from,$to,5,$company)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-center align-items-center">Average Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,12,$from,$to,100,$company)}} text-light">{{rating6($total_order,$id,12,$from,$to,100,$company)}}/100%</span></h5>
      </div>
    </div>
</div>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="card bg-white mb-3">
      <div class="card-header d-flex justify-content-center text-capitalize bg-light">Completed <span class="rounded badge badge-success ml-2">({{pstatusRole6($id,13,$from,$to,$company)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-center align-items-center">Average Rating: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,13,$from,$to,5,$company)}} text-light">{{rating6($total_order,$id,13,$from,$to,5,$company)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-center align-items-center">Average Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,13,$from,$to,100,$company)}} text-light">{{rating6($total_order,$id,13,$from,$to,100,$company)}}/100%</span></h5>
      </div>
    </div>
</div>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="card bg-white mb-3">
      <div class="card-header d-flex justify-content-center text-capitalize bg-light">Cancel <span class="rounded badge badge-success ml-2">({{pstatusRole6($id,14,$from,$to,$company)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-center align-items-center">Average Rating: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,14,$from,$to,5,$company)}} text-light">{{rating6($total_order,$id,14,$from,$to,5,$company)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-center align-items-center">Average Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,14,$from,$to,100,$company)}} text-light">{{rating6($total_order,$id,14,$from,$to,100,$company)}}/100%</span></h5>
      </div>
    </div>
</div>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="card bg-white mb-3">
      <div class="card-header d-flex justify-content-center text-capitalize bg-light">Deleted <span class="rounded badge badge-success ml-2">({{pstatusRole6($id,15,$from,$to,$company)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-center align-items-center">Average Rating: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,15,$from,$to,5,$company)}} text-light">{{rating6($total_order,$id,15,$from,$to,5,$company)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-center align-items-center">Average Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,15,$from,$to,100,$company)}} text-light">{{rating6($total_order,$id,15,$from,$to,100,$company)}}/100%</span></h5>
      </div>
    </div>
</div>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="card bg-white mb-3">
      <div class="card-header d-flex justify-content-center text-capitalize bg-light">OnApprovalCancelled <span class="rounded badge badge-success ml-2">({{pstatusRole6($id,19,$from,$to,$company)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-center align-items-center">Average Rating: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,19,$from,$to,5,$company)}} text-light">{{rating6($total_order,$id,19,$from,$to,5,$company)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-center align-items-center">Average Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,19,$from,$to,100,$company)}} text-light">{{rating6($total_order,$id,19,$from,$to,100,$company)}}/100%</span></h5>
      </div>
    </div>
</div>
<br>
<br>
<div class="col-sm-12">
    <div class="card">
        <h5 class="card-header">Total Records</h5>
        <div class="card-body">
            <div class="row">
                <div class="table-responsive" id="searchData">
                    @include('main.phone_quote.new.double_booking_load_data')
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on('click', '.pagination a', function (event) {
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        var user = $("#user").children('option:selected').val();
        var date_range = $("#date_range").val();
        var company_name = $("#company_name").children('option:selected').val();
        $.ajax({
            url:"{{ url('/double_booking_load_data') }}?page="+page,
            type:"GET",
            data:{date_range:date_range,user:user,company_name:company_name},
            beforeSend: function () {
                $('#searchData').html("");
                $('#searchData').append(`<div class="lds-hourglass" id='ldss'></div>`);
            },
            success:function(res)
            {
                $("#searchData").html("");
                $("#searchData").html(res);
            }
            
        });
    });
</script>