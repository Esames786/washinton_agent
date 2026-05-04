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
    
    function rating4($total,$id,$pstatus,$from,$to,$count)
    {
        $total = $total == 0 ? $count : $total; 
        $user = \App\User::find($id);
        $rate = 0;
        if(isset($user->id))
        {
            $ptype = check_user_setting($user->id);
            if($user->userRole->name == 'Dispatcher')
            {
                $total_order = \App\AutoOrder::where('dispatcher_id',$id)->whereBetween('created_at',[$from,$to])->where('paneltype', '=', $ptype);
            }
            else
            {
                $total_order = \App\AutoOrder::where('dispatcher_id','<>',NULL)->whereBetween('created_at',[$from,$to])->where('paneltype', '=', $ptype);
            }
        }
        else
        {
            $id = Auth::user()->id;
            $ptype = check_user_setting($id);
            $total_order = \App\AutoOrder::where('dispatcher_id','<>',NULL)->whereBetween('created_at',[$from,$to])->where('paneltype', '=', $ptype);
        }
        if(!empty($pstatus))
        {
            $total_order = $total_order->where('pstatus',$pstatus)->count();
        }
        else
        {
            $total_order = $total_order->count();
        }
        $rate = ($total_order * $count) / $total;
        $rate = round($rate,1);
        return $rate;
    }
    
    
    function pstatusRole4($id,$pstatus,$from,$to)
    {
        $total_order = 0;
        $user = \App\User::find($id);
        if(isset($user->id))
        {
            $ptype = check_user_setting($user->id);
            if($user->userRole->name == 'Dispatcher')
            {
                $total_order = \App\AutoOrder::where('dispatcher_id',$id)->whereBetween('created_at',[$from,$to])->where('paneltype', '=', $ptype);
            }
            else
            {
                $total_order = \App\AutoOrder::where('dispatcher_id','<>',NULL)->whereBetween('created_at',[$from,$to])->where('paneltype', '=', $ptype);
            }
        }
        else
        {
            $id = Auth::user()->id;
            $ptype = check_user_setting($id);
            $total_order = \App\AutoOrder::where('dispatcher_id','<>',NULL)->whereBetween('created_at',[$from,$to])->where('paneltype', '=', $ptype);
        }
        if(!empty($pstatus))
        {
            $total_order = $total_order->where('pstatus',$pstatus)->count();
        }
        else
        {
            $total_order = $total_order->count();
        }
        
        return $total_order;
    }
?>

<div class="col-sm-4">
    <div class="card text-white bg-teal mb-3">
      <div class="card-header d-flex justify-content-between">Total Assign <span>({{pstatusRole4($id,'',$from,$to)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating4($total_order,$id,'',$from,$to,5)}}<i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating4($total_order,$id,'',$from,$to,100)}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
      </div>
    </div>
</div>
<div class="col-sm-4">
    <div class="card text-white bg-primary mb-3">
      <div class="card-header d-flex justify-content-between">Listed <span>({{pstatusRole4($id,9,$from,$to)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating4($total_order,$id,9,$from,$to,5)}}<i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating4($total_order,$id,9,$from,$to,100)}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
      </div>
    </div>
</div>
<div class="col-sm-4">
    <div class="card text-white bg-warning mb-3">
      <div class="card-header d-flex justify-content-between">Schedule <span>({{pstatusRole4($id,10,$from,$to)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating4($total_order,$id,10,$from,$to,5)}}<i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating4($total_order,$id,10,$from,$to,100)}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
      </div>
    </div>
</div>
<div class="col-sm-4">
    <div class="card text-white bg-danger mb-3">
      <div class="card-header d-flex justify-content-between">Pickup <span>({{pstatusRole4($id,11,$from,$to)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating4($total_order,$id,11,$from,$to,5)}}<i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating4($total_order,$id,11,$from,$to,100)}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
      </div>
    </div>
</div>
<div class="col-sm-4">
    <div class="card text-white bg-info mb-3">
      <div class="card-header d-flex justify-content-between">Delivered <span>({{pstatusRole4($id,12,$from,$to)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating4($total_order,$id,12,$from,$to,5)}}<i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating4($total_order,$id,12,$from,$to,100)}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
      </div>
    </div>
</div>
<div class="col-sm-4">
    <div class="card text-white bg-success mb-3">
      <div class="card-header d-flex justify-content-between">Completed <span>({{pstatusRole4($id,13,$from,$to)}})</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating4($total_order,$id,13,$from,$to,5)}}<i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating4($total_order,$id,13,$from,$to,100)}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
      </div>
    </div>
</div>