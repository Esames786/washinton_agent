@include('partials.mainsite_pages.return_function')

<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 maincol">
    <div class="table">
        <div class="row">
            <div class="col-md-12 mainheading">
                @if($pstatus == 33)
                Auction Update
                @elseif($pstatus == 30)
                Pickup Approval
                @elseif($pstatus == 31)
                Deliver Approval
                @elseif($pstatus == 32)
                Schedule For Delivery
                @elseif($pstatus == 34)
                Schedule Another Driver
                @else
                {{get_pstatus($pstatus)}}
                @endif
                <span class="rounded-circle green delpik ml-2" style="height: 30px;width: 32px;display: flex;align-items: center;justify-content: center;font-size:11px;">{{$count}}</span>
            </div>
        </div>
        <div class="row smheading">
            <div class="col-md-6 col-6 columns">
                Order ID
            </div>
            <!--<div class="col-md-4 col-4 columns">-->
            <!--    OT/DIS-->
            <!--</div>-->
            <div class="col-md-6 col-6 columns">DATE</div>
        </div>
        <div class="row">
            <div class="customers col-sm-12">
            @foreach($data as $val)
                <?php
                $days = \Carbon\Carbon::parse($val->expected_date)->diffInDays(\Carbon\Carbon::now());
                $datee = date('Y-m-d h:i:s');
                $blink = '';
                $pink = '';
                if($datee >= $val->expected_date)
                { 
                    if($days > 0)
                    {
                        if($val->pstatus == 10 || $val->pstatus == 11 || $val->pstatus == 12)
                        {
                            $pink = 'background:pink !important;';
                            $blink = 'blink';
                        }
                    }
                }
                ?>
                    <div class="row">
                        <div class="col-md-6 col-6">
                            <a target="_blank" href="/searchData?search={{$val->order_id}}">{{$val->order_id}}</a>
                            <select name="" id="" style="width: 75%;">
                                <?php
                                    $order = \App\AutoOrder::select('order_taker_id','dispatcher_id','id')->where('id',$val->order_id)->first();
                                    $ot = 0;
                                    $dis = 0;
                                    if(isset($order->order_taker_id))
                                    {
                                        $ot = $order->order_taker_id;
                                    }
                                    if(isset($order->dispatcher_id))
                                    {
                                        $dis = $order->dispatcher_id;
                                    }
                                ?> 
                                @if($ot > 0)
                                <option value="{{get_user_name($ot)}}">{{get_user_name($ot)}}</option>
                                @else
                                <option value="{{get_user_name($val->user_id)}}">{{get_user_name($val->user_id)}}</option>
                                @endif
                                @if($dis > 0)
                                <option value="{{get_user_name($dis)}}">{{get_user_name($dis)}}</option>
                                @endif
                            </select>
                            <div class="mt-1 position-relative {{$blink}}">
                                <span class="yellow delpik mx-auto" onclick="getData({{$val->order_id}})" style="cursor:pointer;{{$pink}}" title="View Detail"  data-toggle="modal" data-target="#exampleModal"><i class="fa fa-eye"></i></span>
                                @if(count($val->order->sheet) > 0)
                                    @if(($val->pstatus >= 7 && $val->pstatus <= 12) || $val->pstatus == 18)
                                    <span class="rounded-circle green delpik ml-2" style="position:absolute;top:-15px;right:-25px;height: 23px;width: 20px;display: flex;align-items: center;justify-content: center;font-size:10px;">{{count($val->order->sheet)}}</span>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <!--<div class="col-md-4 col-4">-->
                            
                        <!--</div>-->
                        <div class="col-md-6 col-6">
                            <?php
                            if ($val->expected_date == $datee) {
                            ?>
                                <span>{{\Carbon\Carbon::parse($val->expected_date)->format('M,d Y')}}</span>
                                <span>{{\Carbon\Carbon::parse($val->expected_date)->format('h:i A')}}</span>
                            <?php } else {
                            ?>
                                <span>{{\Carbon\Carbon::parse($val->expected_date)->format('M,d Y')}}</span>
                                <span>{{\Carbon\Carbon::parse($val->expected_date)->format('h:i A')}}</span>
                            <?php }?>
        
                            <?php 
                                if(($pstatus <= 13 || $pstatus == 18) || $pstatus == 30 || $pstatus == 31 || $pstatus == 32 || $pstatus == 34)
                                {
                                    if($datee >= $val->expected_date )
                                    { 
                                        if($days == 0)
                                        {
                            ?>
                                            <span class="green delpik mx-auto" style="font-size:11px;">Left <?php echo $days  ?></span>
                            <?php 
                                        } 
                                        else if($days == 1)
                                        {
                            ?>
                                            <span class="yellow delpik mx-auto" style="font-size:11px;">Late <?php echo $days  ?></span>
                            <?php
                                        }
                                        else
                                        {
                            ?>
                                            <span class="red delpik mx-auto" style="font-size:11px;">Late <?php echo $days  ?></span>
                            <?php
                                        }
                                    }
                                    else
                                    { 
                                        $remaining_days = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($val->expected_date));
                                        if($remaining_days > 0)
                                        {
                            ?>                                        
                                            <span class="blue delpik mx-auto" style="font-size:11px;">Left <?php echo $remaining_days;  ?></span>
                            <?php
                                        }
                                        else
                                        {
                            ?>                   
                                            <span class="green delpik mx-auto" style="font-size:11px;">Left <?php echo $days;  ?></span>
                            <?php
                                        }
                                    }
                                }
                            ?>
                        </div>
                    </div>
                    @if(($pstatus >= 10 && $pstatus <= 12) || $pstatus == 30 || $pstatus == 31 || $pstatus == 32 || $pstatus == 34)
                        <?php 
                            $pic_del = \App\AutoOrder::select('pickup_date','delivery_date','driver_pickup_date','driver_deliver_date','est_pick_date','est_delivery_date','id')->where('id',$val->order_id)->first();
                        ?>
                        <div class="row pt-2">
                            <div class="col-md-6 text-center" style="font-size:11px;display:unset;">
                                <span class="green delpik m-auto">Pick up</span> @if(isset($pic_del->driver_pickup_date)) {{\Carbon\Carbon::parse($pic_del->driver_pickup_date)->format('M,d Y')}} @elseif(isset($pic_del->est_pick_date)) {{\Carbon\Carbon::parse($pic_del->est_pick_date)->format('M,d Y')}} @else {{\Carbon\Carbon::parse($pic_del->pickup_date)->format('M,d Y')}} @endif
                            </div>
                            <div class="col-md-6 text-center" style="font-size:11px;display:unset;">
                                <span class="red delpik m-auto">Delivery</span> @if(isset($pic_del->driver_deliver_date)) {{\Carbon\Carbon::parse($pic_del->driver_deliver_date)->format('M,d Y')}} @elseif(isset($pic_del->est_delivery_date)) {{\Carbon\Carbon::parse($pic_del->est_delivery_date)->format('M,d Y')}} @else {{\Carbon\Carbon::parse($pic_del->delivery_date)->format('M,d Y')}} @endif
                            </div>
                        </div>
                    @endif
            @endforeach
        </div>
        </div>
    </div>
</div>
<script>
    var getData = (id) => {
        $.ajax({
           url:"{{url('/get_shipment_status_order_detail')}}",
           type:"GET",
           data:{id:id},
           dataType:"HTML",
           success:function(res)
           {
                $("#detail_order").html('');
                $("#detail_order").html(res);
           }
        });
    }
</script>