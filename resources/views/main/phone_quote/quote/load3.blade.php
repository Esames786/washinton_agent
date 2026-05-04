@include('partials.mainsite_pages.return_function')

<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 maincol">
    <div class="table">
        <div class="row">
            <div class="col-md-12 mainheading">
                {{shipment_status($pstatus)}}
                <span class="rounded-circle green delpik ml-2" style="height: 30px;width: 32px;display: flex;align-items: center;justify-content: center;font-size:11px;">{{$count}}</span>
            </div>
        </div>
        <div class="row smheading">
            <div class="col-md-6 col-6 columns">
                Order ID
            </div>
            <div class="col-md-6 col-6 columns">DATE</div>
        </div>
        <div class="row">
            <div class="customers col-sm-12">
            @foreach($data as $val)
                <?php
                $days = \Carbon\Carbon::parse($val->created_at)->diffInDays(\Carbon\Carbon::now());
                $datee = date('Y-m-d h:i:s');
                ?>
                    <div class="row">
                        <div class="col-md-6 col-6">
                            <a target="_blank" href="/searchData?search={{$val->order_id}}">{{$val->order_id}}</a>
                            <select name="" id="" style="width: 75%;">
                                <option value="{{get_user_name($val->user_id)}}">{{get_user_name($val->user_id)}}</option>
                            </select>
                            <div class="mt-1 position-relative">
                                <?php 
                                    $req_count = \App\RequestShipment::where('order_id',$val->order_id)->where('status',$val->status)->count();
                                ?>
                                <span class="yellow delpik mx-auto mt-1" onclick="getData22({{$val->order_id}},{{$val->status}})" style="cursor:pointer;" title="View Detail"  data-toggle="modal" data-target="#exampleModal"><i class="fa fa-eye"></i></span>
                                @if($req_count > 1)
                                <span class="rounded-circle red delpik ml-2" style="position:absolute;top:-15px;right:-25px;height: 23px;width: 20px;display: flex;align-items: center;justify-content: center;font-size:10px;">{{$req_count}}</span>
                                @endif
                            </div>
                        </div>
                        <!--<div class="col-md-4 col-4">-->
                            
                        <!--</div>-->
                        <div class="col-md-6 col-6">
                            <?php
                                if ($val->created_at == $datee) {
                            ?>
                                <span>{{\Carbon\Carbon::parse($val->created_at)->format('M,d Y')}}</span>
                                <span>{{\Carbon\Carbon::parse($val->created_at)->format('h:i A')}}</span>
                            <?php } else {
                            ?>
                                <span>{{\Carbon\Carbon::parse($val->created_at)->format('M,d Y')}}</span>
                                <span>{{\Carbon\Carbon::parse($val->created_at)->format('h:i A')}}</span>
                            <?php }?>
        
                            <?php 
                                if($datee >= $val->created_at )
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
                                    $remaining_days = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($val->created_at));
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
                            ?>
                        </div>
                    </div>
            @endforeach
        </div>
        </div>
    </div>
</div>
<script>
    var getData22 = (id,status) => {
        $.ajax({
           url:"{{url('/get_shipment_status_order_detail3')}}",
           type:"GET",
           data:{id:id,status:status},
           dataType:"HTML",
           success:function(res)
           {
                $("#detail_order").html('');
                $("#detail_order").html(res);
           }
        });
    }
</script>