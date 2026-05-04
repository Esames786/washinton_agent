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

    function rating2($total,$id,$pstatus,$from,$to,$count)
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
        $ptype = check_user_setting(Auth::user()->id);
        if(isset($user->id))
        {
            if($user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent' || $user->userRole->name == 'Order Taker')
            {
                $total_order = \App\AutoOrder::where('order_taker_id',$id)->whereBetween('created_at',[$from,$to])->where('pstatus',$eq,$pstatus)->where('paneltype', '=', $ptype)->count();
                $rate = ($total_order * $count) / $total;
            }
            else if($user->userRole->name == 'Dispatcher')
            {
                $total_order = \App\AutoOrder::where('dispatcher_id',$id)->where('pstatus',$eq,$pstatus)->whereBetween('created_at',[$from,$to])->where('paneltype', '=', $ptype)->count();
                $rate = ($total_order * $count) / $total;
            }
            else if($user->userRole->name == 'Delivery Boy')
            {
                $total_order = \App\AutoOrder::where('delivery_boy_id',$id)->where('pstatus',$eq,$pstatus)->whereBetween('created_at',[$from,$to])->where('paneltype', '=', $ptype)->count();
                $rate = ($total_order * $count) / $total;
            }
            else if($user->userRole->name == 'Manager')
            {
                $total_order = \App\AutoOrder::where(function ($q) use ($id)
                {
                  $q->where('manager_id',$id)->orWhere('order_taker_id',$id);  
                })->whereBetween('created_at',[$from,$to])->where('pstatus',$eq,$pstatus)->where('paneltype', '=', $ptype)->count();
                $rate = ($total_order * $count) / $total;
            }
            else
            {
                $total_order = \App\AutoOrder::where('pstatus',$eq,$pstatus)->whereBetween('created_at',[$from,$to])->where('paneltype', '=', $ptype)->count();
                $rate = ($total_order * $count) / $total;
            }
        }
        else
        {
            $total_order = \App\AutoOrder::where('pstatus',$eq,$pstatus)->whereBetween('created_at',[$from,$to])->where('paneltype', '=', $ptype)->count();
            $rate = ($total_order * $count) / $total;
        }
        $rate = round($rate,1);
        return $rate;
    }
    
    
    function pstatusRole2($id,$pstatus,$from,$to)
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
        $ptype = check_user_setting(Auth::user()->id);
        if(isset($user->id))
        {
            if($user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent' || $user->userRole->name == 'Order Taker')
            {
                $total_order = \App\AutoOrder::where('order_taker_id',$id)->whereBetween('created_at',[$from,$to])->where('pstatus',$eq,$pstatus)->where('paneltype', '=', $ptype)->count();
            }
            else if($user->userRole->name == 'Dispatcher')
            {
                $total_order = \App\AutoOrder::where('dispatcher_id',$id)->whereBetween('created_at',[$from,$to])->where('pstatus',$eq,$pstatus)->where('paneltype', '=', $ptype)->count();
            }
            else if($user->userRole->name == 'Delivery Boy')
            {
                $total_order = \App\AutoOrder::where('delivery_boy_id',$id)->whereBetween('created_at',[$from,$to])->where('pstatus',$eq,$pstatus)->where('paneltype', '=', $ptype)->count();
            }
            else if($user->userRole->name == 'Manager')
            {
                $total_order = \App\AutoOrder::where(function ($q) use ($id)
                {
                  $q->where('manager_id',$id)->orWhere('order_taker_id',$id);  
                })->whereBetween('created_at',[$from,$to])->where('pstatus',$eq,$pstatus)->where('paneltype', '=', $ptype)->count();
            }
            else
            {
                $total_order = \App\AutoOrder::where('pstatus',$eq,$pstatus)->whereBetween('created_at',[$from,$to])->where('paneltype', '=', $ptype)->count();
            }
        }
        else
        {
            $total_order = \App\AutoOrder::where('pstatus',$eq,$pstatus)->whereBetween('created_at',[$from,$to])->where('paneltype', '=', $ptype)->count();
        }
        return $total_order;
    }
?>

<div class="col-sm-3">
    <div class="card text-white bg-brown mb-3">
      <div class="card-header d-flex justify-content-between">New To Time Quote<span>({{pstatusRole2($id,6,$from,$to)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating2($total_order,$id,6,$from,$to,5)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating2($total_order,$id,6,$from,$to,100)}}/100%</span></h5>
      </div>
    </div>
</div>
<div class="col-sm-3">
    <div class="card text-white bg-primary mb-3">
      <div class="card-header d-flex justify-content-between">PaymentMissing<span>({{pstatusRole2($id,7,$from,$to)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating2($total_order,$id,7,$from,$to,5)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating2($total_order,$id,7,$from,$to,100)}}/100%</span></h5>
      </div>
    </div>
</div>
<div class="col-sm-3">
    <div class="card text-white bg-yellow mb-3">
      <div class="card-header d-flex justify-content-between">Booked <span>({{pstatusRole2($id,8,$from,$to)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating2($total_order,$id,8,$from,$to,5)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating2($total_order,$id,8,$from,$to,100)}}/100%</span></h5>
      </div>
    </div>
</div>
<div class="col-sm-3">
    <div class="card text-white bg-teal mb-3">
      <div class="card-header d-flex justify-content-between">OnApproval <span>({{pstatusRole2($id,18,$from,$to)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating2($total_order,$id,18,$from,$to,5)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating2($total_order,$id,18,$from,$to,100)}}/100%</span></h5>
      </div>
    </div>
</div>
<div class="col-sm-3">
    <div class="card text-white bg-purple mb-3">
      <div class="card-header d-flex justify-content-between">Listed <span>({{pstatusRole2($id,9,$from,$to)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating2($total_order,$id,9,$from,$to,5)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating2($total_order,$id,9,$from,$to,100)}}/100%</span></h5>
      </div>
    </div>
</div>
<div class="col-sm-3">
    <div class="card text-white bg-warning mb-3">
      <div class="card-header d-flex justify-content-between">Schedule <span>({{pstatusRole2($id,10,$from,$to)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating2($total_order,$id,10,$from,$to,5)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating2($total_order,$id,10,$from,$to,100)}}/100%</span></h5>
      </div>
    </div>
</div>
<div class="col-sm-3">
    <div class="card text-white bg-info mb-3">
      <div class="card-header d-flex justify-content-between">Pickup <span>({{pstatusRole2($id,11,$from,$to)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating2($total_order,$id,11,$from,$to,5)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating2($total_order,$id,11,$from,$to,100)}}/100%</span></h5>
      </div>
    </div>
</div>
<div class="col-sm-3">
    <div class="card text-white bg-orange mb-3">
      <div class="card-header d-flex justify-content-between">Delivered <span>({{pstatusRole2($id,12,$from,$to)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating2($total_order,$id,12,$from,$to,5)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating2($total_order,$id,12,$from,$to,100)}}/100%</span></h5>
      </div>
    </div>
</div>
<div class="col-sm-3">
    <div class="card text-white bg-success mb-3">
      <div class="card-header d-flex justify-content-between">Completed <span>({{pstatusRole2($id,13,$from,$to)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating2($total_order,$id,13,$from,$to,5)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating2($total_order,$id,13,$from,$to,100)}}/100%</span></h5>
      </div>
    </div>
</div>
<div class="col-sm-3">
    <div class="card text-white bg-danger mb-3">
      <div class="card-header d-flex justify-content-between">Cancel <span>({{pstatusRole2($id,14,$from,$to)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating2($total_order,$id,14,$from,$to,5)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating2($total_order,$id,14,$from,$to,100)}}/100%</span></h5>
      </div>
    </div>
</div>
<div class="col-sm-3">
    <div class="card text-white bg-amber mb-3">
      <div class="card-header d-flex justify-content-between">Deleted <span>({{pstatusRole2($id,15,$from,$to)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating2($total_order,$id,15,$from,$to,5)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating2($total_order,$id,15,$from,$to,100)}}/100%</span></h5>
      </div>
    </div>
</div>
<div class="col-sm-3">
    <div class="card text-white bg-pink mb-3">
      <div class="card-header d-flex justify-content-between">OnApprovalCancelled <span>({{pstatusRole2($id,19,$from,$to)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating2($total_order,$id,19,$from,$to,5)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating2($total_order,$id,19,$from,$to,100)}}/100%</span></h5>
      </div>
    </div>
</div>
<!--<div class="col-sm-3">-->
<!--    <div class="card text-white bg-dark mb-3">-->
<!--      <div class="card-header d-flex justify-content-between">CarrierUpdate <span>({{pstatusRole2($id,17,$from,$to)}})</span></div>-->
<!--      <div class="card-body">-->
<!--        <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating2($total_order,$id,17,$from,$to,5)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>-->
<!--        <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating2($total_order,$id,17,$from,$to,100)}}/100%</span></h5>-->
<!--      </div>-->
<!--    </div>-->
<!--</div>-->