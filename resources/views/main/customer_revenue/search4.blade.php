<?php 
    function rating6($total,$id,$pstatus,$from,$to,$count,$field)
    {
        $total = $total == 0 ? $count : $total; 
        $user = \App\User::find($id);
        $rate = 0;
        
        if(isset($pstatus) && $pstatus >= 0)
        {
            if(isset($user->id))
            {
                if($user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent' || $user->userRole->name == 'Manager')
                {
                    $total_order = \App\QaVerifyHistory::whereHas('order',function ($q) use ($id){
                        $q->where('order_taker_id',$id);
                    })->where($field,1)->whereBetween('created_at',[$from,$to]);
            
                    $total = \App\QaVerifyHistory::whereHas('order',function ($q) use ($id){
                        $q->where('order_taker_id',$id);
                    })->whereBetween('created_at',[$from,$to]);
                }
                else if($user->userRole->name == 'Dispatcher')
                {
                    $total_order = \App\QaVerifyHistory::whereHas('order',function ($q) use ($id){
                        $q->where('dispatcher_id',$id);
                    })->where($field,1)->whereBetween('created_at',[$from,$to]);
            
                    $total = \App\QaVerifyHistory::whereHas('order',function ($q) use ($id){
                        $q->where('dispatcher_id',$id);
                    })->whereBetween('created_at',[$from,$to]);
                }
                else
                {
                    $total_order = \App\QaVerifyHistory::where($field,1)->whereBetween('created_at',[$from,$to]);
                    
                    $total = \App\QaVerifyHistory::whereBetween('created_at',[$from,$to]);
                }
            }
            else
            {
                $total_order = \App\QaVerifyHistory::where($field,1)->whereBetween('created_at',[$from,$to]);
                
                $total = \App\QaVerifyHistory::whereBetween('created_at',[$from,$to]);
            }
            if($pstatus == 20)
            {
                $total_order = $total_order->whereIn('pstatus',['7','8','18'])->count();
                $total = $total->whereIn('pstatus',['7','8','18'])->count();
            }
            else
            {
                $total_order = $total_order->where('pstatus',$pstatus)->count();
                $total = $total->where('pstatus',$pstatus)->count();
            }
        }
        else
        {
            $total_order = \App\QaVerifyHistory::whereBetween('created_at',[$from,$to])->where($field,1)->count();
            $total = \App\QaVerifyHistory::whereBetween('created_at',[$from,$to])->count();
        }
        $total = $total == 0 ? $count : $total; 
        $rate = ($total_order * $count) / $total;
        $rate = round($rate,1);
        return $rate;
    }
    
    
    function pstatusRole6($id,$pstatus,$from,$to)
    {
        $total_order = 0;
        $user = \App\User::find($id);
        if(isset($pstatus) && $pstatus >= 0)
        {
            if(isset($user->id))
            {
                if($user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent' || $user->userRole->name == 'Manager')
                {
                    $total_order = \App\QaVerifyHistory::whereHas('order',function ($q) use ($id){
                        $q->where('order_taker_id',$id);
                    })->whereBetween('created_at',[$from,$to]);
                }
                else if($user->userRole->name == 'Dispatcher')
                {
                    $total_order = \App\QaVerifyHistory::whereHas('order',function ($q) use ($id){
                        $q->where('dispatcher_id',$id);
                    })->whereBetween('created_at',[$from,$to]);
                }
                else
                {
                    $total_order = \App\QaVerifyHistory::whereBetween('created_at',[$from,$to]);
                }
            }
            else
            {
                $total_order = \App\QaVerifyHistory::whereBetween('created_at',[$from,$to]);
            }
            if($pstatus == 20)
            {
                $total_order = $total_order->whereIn('pstatus',['7','8','18'])->count();
            }
            else
            {
                $total_order = $total_order->where('pstatus',$pstatus)->count();
            }
        }
        else
        {
            $total_order = \App\QaVerifyHistory::whereBetween('created_at',[$from,$to])->count();
        }
        
        return $total_order;
    }
    
    function color($total_order,$id,$pstatus,$from,$to,$count,$status)
    {
        if(rating6($total_order,$id,$pstatus,$from,$to,$count,$status) > 80)
        {
            if($status == 'verify')
            {
                $color = 'bg-success';
            }
            else
            {
                $color = 'bg-danger';
            }
        }
        else if(rating6($total_order,$id,$pstatus,$from,$to,$count,$status) <= 80 && rating6($total_order,$id,$pstatus,$from,$to,$count,$status) > 60)
        {
            $color = 'bg-warning';
        }
        else
        {
            if($status == 'verify')
            {
                $color = 'bg-danger';
            }
            else
            {
                $color = 'bg-success';
            }
        }
        return $color;
    }
?>

<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="card bg-white mb-3">
      <div class="card-header d-flex justify-content-center text-capitalize bg-light">Total <span class="rounded badge badge-success ml-2">{{pstatusRole6($id,'-1',$from,$to)}}</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-center align-items-center">Verify Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,'-1',$from,$to,100,'verify')}} text-light">{{rating6($total_order,$id,'-1',$from,$to,100,'verify')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-center align-items-center">Negative Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,'-1',$from,$to,100,'negative')}} text-light">{{rating6($total_order,$id,'-1',$from,$to,100,'negative')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
      </div>
    </div>
</div>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="card bg-white mb-3">
      <div class="card-header d-flex justify-content-center text-capitalize bg-light">New <span class="rounded badge badge-success ml-2">{{pstatusRole6($id,0,$from,$to)}}</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-center align-items-center">Verify Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,0,$from,$to,100,'verify')}} text-light">{{rating6($total_order,$id,0,$from,$to,100,'verify')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-center align-items-center">Negative Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,0,$from,$to,100,'negative')}} text-light">{{rating6($total_order,$id,0,$from,$to,100,'negative')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
      </div>
    </div>
</div>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="card bg-white mb-3">
      <div class="card-header d-flex justify-content-center text-capitalize bg-light">Interested <span class="rounded badge badge-success ml-2">{{pstatusRole6($id,1,$from,$to)}}</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-center align-items-center">Verify Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,1,$from,$to,100,'verify')}} text-light">{{rating6($total_order,$id,1,$from,$to,100,'verify')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-center align-items-center">Negative Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,1,$from,$to,100,'negative')}} text-light">{{rating6($total_order,$id,1,$from,$to,100,'negative')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
      </div>
    </div>
</div>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="card bg-white mb-3">
      <div class="card-header d-flex justify-content-center text-capitalize bg-light">FollowMore <span class="rounded badge badge-success ml-2">{{pstatusRole6($id,2,$from,$to)}}</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-center align-items-center">Verify Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,2,$from,$to,100,'verify')}} text-light">{{rating6($total_order,$id,2,$from,$to,100,'verify')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-center align-items-center">Negative Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,2,$from,$to,100,'negative')}} text-light">{{rating6($total_order,$id,2,$from,$to,100,'negative')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
      </div>
    </div>
</div>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="card bg-white mb-3">
      <div class="card-header d-flex justify-content-center text-capitalize bg-light">AskingLow <span class="rounded badge badge-success ml-2">{{pstatusRole6($id,3,$from,$to)}}</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-center align-items-center">Verify Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,3,$from,$to,100,'verify')}} text-light">{{rating6($total_order,$id,3,$from,$to,100,'verify')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-center align-items-center">Negative Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,3,$from,$to,100,'negative')}} text-light">{{rating6($total_order,$id,3,$from,$to,100,'negative')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
      </div>
    </div>
</div>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="card bg-white mb-3">
      <div class="card-header d-flex justify-content-center text-capitalize bg-light">NotInterested <span class="rounded badge badge-success ml-2">{{pstatusRole6($id,4,$from,$to)}}</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-center align-items-center">Verify Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,4,$from,$to,100,'verify')}} text-light">{{rating6($total_order,$id,4,$from,$to,100,'verify')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-center align-items-center">Negative Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,4,$from,$to,100,'negative')}} text-light">{{rating6($total_order,$id,4,$from,$to,100,'negative')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
      </div>
    </div>
</div>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="card bg-white mb-3">
      <div class="card-header d-flex justify-content-center text-capitalize bg-light">NoResponse <span class="rounded badge badge-success ml-2">{{pstatusRole6($id,5,$from,$to)}}</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-center align-items-center">Verify Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,5,$from,$to,100,'verify')}} text-light">{{rating6($total_order,$id,5,$from,$to,100,'verify')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-center align-items-center">Negative Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,5,$from,$to,100,'negative')}} text-light">{{rating6($total_order,$id,5,$from,$to,100,'negative')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
      </div>
    </div>
</div>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="card bg-white mb-3">
      <div class="card-header d-flex justify-content-center text-capitalize bg-light">TimeQuote <span class="rounded badge badge-success ml-2">{{pstatusRole6($id,6,$from,$to)}}</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-center align-items-center">Verify Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,6,$from,$to,100,'verify')}} text-light">{{rating6($total_order,$id,6,$from,$to,100,'verify')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-center align-items-center">Negative Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,6,$from,$to,100,'negative')}} text-light">{{rating6($total_order,$id,6,$from,$to,100,'negative')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
      </div>
    </div>
</div>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="card bg-white mb-3">
      <div class="card-header d-flex justify-content-center text-capitalize bg-light">Booking <span class="rounded badge badge-success ml-2">{{pstatusRole6($id,20,$from,$to)}}</span>
        <div class="dropdown">
          <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="#">Payment Missing ({{pstatusRole6($id,7,$from,$to)}})</a>
            <a class="dropdown-item" href="#">Booked ({{pstatusRole6($id,8,$from,$to)}})</a>
            <a class="dropdown-item" href="#">On Approval ({{pstatusRole6($id,18,$from,$to)}})</a>
          </div>
        </div>
      </div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-center align-items-center">Verify Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,20,$from,$to,100,'verify')}} text-light">{{rating6($total_order,$id,20,$from,$to,100,'verify')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-center align-items-center">Negative Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,20,$from,$to,100,'negative')}} text-light">{{rating6($total_order,$id,20,$from,$to,100,'negative')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
      </div>
    </div>
</div>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="card bg-white mb-3">
      <div class="card-header d-flex justify-content-center text-capitalize bg-light">Listed <span class="rounded badge badge-success ml-2">{{pstatusRole6($id,9,$from,$to)}}</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-center align-items-center">Verify Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,9,$from,$to,100,'verify')}} text-light">{{rating6($total_order,$id,9,$from,$to,100,'verify')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-center align-items-center">Negative Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,9,$from,$to,100,'negative')}} text-light">{{rating6($total_order,$id,9,$from,$to,100,'negative')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
      </div>
    </div>
</div>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="card bg-white mb-3">
      <div class="card-header d-flex justify-content-center text-capitalize bg-light">Schedule <span class="rounded badge badge-success ml-2">{{pstatusRole6($id,10,$from,$to)}}</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-center align-items-center">Verify Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,10,$from,$to,100,'verify')}} text-light">{{rating6($total_order,$id,10,$from,$to,100,'verify')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-center align-items-center">Negative Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,10,$from,$to,100,'negative')}} text-light">{{rating6($total_order,$id,10,$from,$to,100,'negative')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
      </div>
    </div>
</div>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="card bg-white mb-3">
      <div class="card-header d-flex justify-content-center text-capitalize bg-light">Pickedup <span class="rounded badge badge-success ml-2">{{pstatusRole6($id,11,$from,$to)}}</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-center align-items-center">Verify Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,11,$from,$to,100,'verify')}} text-light">{{rating6($total_order,$id,11,$from,$to,100,'verify')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-center align-items-center">Negative Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,11,$from,$to,100,'negative')}} text-light">{{rating6($total_order,$id,11,$from,$to,100,'negative')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
      </div>
    </div>
</div>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="card bg-white mb-3">
      <div class="card-header d-flex justify-content-center text-capitalize bg-light">Delivered <span class="rounded badge badge-success ml-2">{{pstatusRole6($id,12,$from,$to)}}</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-center align-items-center">Verify Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,12,$from,$to,100,'verify')}} text-light">{{rating6($total_order,$id,12,$from,$to,100,'verify')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-center align-items-center">Negative Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,12,$from,$to,100,'negative')}} text-light">{{rating6($total_order,$id,12,$from,$to,100,'negative')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
      </div>
    </div>
</div>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="card bg-white mb-3">
      <div class="card-header d-flex justify-content-center text-capitalize bg-light">Completed <span class="rounded badge badge-success ml-2">{{pstatusRole6($id,13,$from,$to)}}</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-center align-items-center">Verify Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,13,$from,$to,100,'verify')}} text-light">{{rating6($total_order,$id,13,$from,$to,100,'verify')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-center align-items-center">Negative Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,13,$from,$to,100,'negative')}} text-light">{{rating6($total_order,$id,13,$from,$to,100,'negative')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
      </div>
    </div>
</div>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="card bg-white mb-3">
      <div class="card-header d-flex justify-content-center text-capitalize bg-light">Cancel <span class="rounded badge badge-success ml-2">{{pstatusRole6($id,14,$from,$to)}}</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-center align-items-center">Verify Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,14,$from,$to,100,'verify')}} text-light">{{rating6($total_order,$id,14,$from,$to,100,'verify')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-center align-items-center">Negative Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,14,$from,$to,100,'negative')}} text-light">{{rating6($total_order,$id,14,$from,$to,100,'negative')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
      </div>
    </div>
</div>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="card bg-white mb-3">
      <div class="card-header d-flex justify-content-center text-capitalize bg-light">OnApprovalCancel <span class="rounded badge badge-success ml-2">{{pstatusRole6($id,19,$from,$to)}}</span></div>
      <div class="card-body">
        <h5 class="card-title d-flex justify-content-center align-items-center">Verify Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,19,$from,$to,100,'verify')}} text-light">{{rating6($total_order,$id,19,$from,$to,100,'verify')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
        <h5 class="card-title d-flex justify-content-center align-items-center">Negative Percent: <span style="height:20px;" class="ml-2 rounded badge {{color($total_order,$id,19,$from,$to,100,'negative')}} text-light">{{rating6($total_order,$id,19,$from,$to,100,'negative')}}<i class="fa fa-percent" aria-hidden="true"></i></span></h5>
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
                    @include('main.customer_revenue.total_records')
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
        $.ajax({
            url:"{{ url('/total_records') }}?page="+page,
            type:"GET",
            data:{date_range:date_range,user:user},
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