@include('partials.mainsite_pages.return_function')
<div class="col-sm-12">
    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th>Pickup</th>
                <th>Delivery</th>
                <th>Vehicle/Order ID</th>
                <th>Order Price/Phone</th>
                <th>Ship On/Modified</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $value)
                <tr>
                    <td>
                        <a href="https://www.google.com/maps/dir/{{$value->order->originzip}},+USA/"
                           target="_blank" class="table1ancher">
                            <i class="fa fa-map-marker" style="color:green;" aria-hidden="true"></i>
                            <span>  {{$value->order->origincity . "-".$value->order->originstate ."-" .$value->order->originzip  }}</span>
                        </a>
                        @if(!empty($value->order->oaddress))
                            <a data-placement="bottom" class="table1ancher" title="{{ $value->order->oaddress }}">
                                <i class="fa fa-location-arrow" style="color:green;" aria-hidden="true"></i>
                                <span>{{ $value->order->oaddress }} </span>
                            </a>
                        @endif
                        <p class="mt-1 mb-0"><b>{{ $value->order->oauction }}</b></p>
                    </td>
                    <td>
                        <a href="https://www.google.com/maps/dir/{{$value->order->destinationzip }},+USA/"
                           target="_blank" class="table1ancher">
                            <i class="fa fa-map-marker" style="color:red;" aria-hidden="true"></i>
                           <span> {{$value->order->destinationcity . "-".$value->order->destinationstate ."-" .$value->order->destinationzip  }}</span>
                        </a>
                        @if($value->order->daddress)
                            <a data-placement="bottom" title="{{ $value->order->daddress }}" class="table1ancher">
                                <i class="fa fa-location-arrow" style="color:red;" aria-hidden="true"></i>
                                <span>  {{ $value->order->daddress }} </span>
                            </a>
                        @endif
                        <p class="mt-1 mb-0"><b>{{ $value->order->dauction }}</b></p>
                    </td>
                    <td>
                        <?php $ymk = explode('*^-', $value->order->ymk); ?>
                        @foreach($ymk as $val2)
                            @if($val2)
                                {{$val2}} <br>
                            @endif
                        @endforeach
                        <b> Order ID# </b> <a target="_blank" href="/searchData?search={{$value->order->id}}">{{$value->order->id}}</a>
                        <br>
                        <b> Creator:</b> <span>{{get_user_name($value->order->order_taker_id)}}</span>
                        @if(isset($value->order->u_id))
                            <br>
                            <b>Booker:</b> <span>{{get_user_name($value->order->u_id)}}</span>
                        @endif
                        @if(isset($value->order->dispatcher_id))
                            <br>
                            <b>Assign To:</b> <span>{{get_user_name($value->order->dispatcher_id)}}</span>
                        @endif
                        @if($pstatus >= 20 && $pstatus <= 27)
                            <br>
                            <b>Request By:</b> <span>{{get_user_name($value->order->req_ship[0]->user_id)}}</span>
                        @else
                            <br>
                            <b>Moved In 
                            @if($value->pstatus == 30)
                                Pickup Approval
                            @elseif($value->pstatus == 31)
                                Deliver Approval
                            @elseif($value->pstatus == 32)
                                Schedule for Delivery
                            @else
                                {{get_pstatus($value->pstatus)}}
                            @endif :</b> <span>{{get_user_name($value->userId)}}</span>
                        @endif
                        @if($value->pstatus >= 10 && $value->pstatus <= 14)
                            @if($value->order->already_auction_storage == 1)
                                <br>
                                Already Storage: <b>${{$value->order->already_storage}}</b>
                                @if(isset($value->order->already_storage_date) && isset($value->order->already_storage_end_date))
                                    @if(\Carbon\Carbon::parse($value->order->already_storage_date)->format('Y-m-d') < \Carbon\Carbon::parse($value->order->already_storage_end_date)->format('Y-m-d'))
                                        <?php 
                                            $start_time = \Carbon\Carbon::parse($value->order->already_storage_date)->format('Y-m-d');
                                            $end_time = \Carbon\Carbon::parse($value->order->already_storage_end_date)->format('Y-m-d');
                                            $time_difference_in_minutes = \Carbon\Carbon::parse($value->order->already_storage_end_date)->diffForHumans(\Carbon\Carbon::parse($value->order->already_storage_date));
                                        ?>
                                        <br>
                                        <span class="badge badge-danger">{{$time_difference_in_minutes}} delay</span>
                                    @endif
                                @endif
                            @endif
                            @if($value->order->late_pickup_auction_storage == 1)
                                <br>
                                Late Pickup Storage: <b>${{$value->order->late_pickup_storage}}</b>
                                @if(isset($value->order->late_pickup_storage_date) && isset($value->order->late_pickup_storage_end_date))
                                    @if(\Carbon\Carbon::parse($value->order->late_pickup_storage_date)->format('Y-m-d') < \Carbon\Carbon::parse($value->order->late_pickup_storage_end_date)->format('Y-m-d'))
                                        <?php 
                                            $start_time = \Carbon\Carbon::parse($value->order->late_pickup_storage_date)->format('Y-m-d');
                                            $end_time = \Carbon\Carbon::parse($value->order->late_pickup_storage_end_date)->format('Y-m-d');
                                            $time_difference_in_minutes = \Carbon\Carbon::parse($value->order->late_pickup_storage_end_date)->diffInHumans(\Carbon\Carbon::parse($value->order->late_pickup_storage_date));
                                        ?>
                                        <br>
                                        <span class="badge badge-danger">{{$time_difference_in_minutes}} delay</span>
                                    @endif
                                @endif
                            @endif
                        @endif
                        @if($value->pstatus == 11 || $value->pstatus == 12)
                            @if($value->order->storage_id > 0)
                                <?php 
                                    $storage_charge = preg_replace("/[^0-9]/", '', $value->order->storage_charge);
                                    $late1 = \Carbon\Carbon::parse($value->order->storage_date);
                                    if($late1->diffInDays() > 0)
                                    {
                                        if($value->pstatus == 11)
                                        {
                                            $storage_charge = $storage_charge * $late1->diffInDays();
                                        }
                                        else
                                        {
                                            $com_date1 = \Carbon\Carbon::parse($value->order->storage_move_date);
                                            $diff1 = $late1->diffInDays($com_date1);
                                            if($diff1 > 0)
                                            {
                                                $storage_charge = $storage_charge * $diff1;
                                            }
                                        }
                                    }
                                ?>
                                <br>
                                Storage Charge: <b>${{$storage_charge}}</b>
                                <br>
                                <b>Storage Move Date:</b> {{\Carbon\Carbon::parse($value->order->storage_date)->format('M,d Y')}}<br>
                                @if(!empty($value->order->storage_move_date))
                                <b>Storage End Date:</b> {{\Carbon\Carbon::parse($value->order->storage_move_date)->format('M,d Y')}}<br>
                                <span class="badge badge-success">Complete Storage</span>
                                @else
                                    @if($value->pstatus == 11)
                                        <span class="badge badge-danger">Pending Storage</span>
                                    @else
                                        <span class="badge badge-success">Complete Storage</span>
                                    @endif
                                @endif
                                <br>
                                 
                                <?php 
                                    $late22 = \Carbon\Carbon::parse($value->order->storage_date);
                                ?>
                                @if($late22->diffInDays() > 0)
                                    @if(isset($value->order->storage_move_date))
                                        <?php
                                            $com_date22 = \Carbon\Carbon::parse($value->order->storage_move_date);
                                            $diff22 = $late22->diffInDays($com_date22);
                                        ?>
                                        @if($diff22 > 0)
                                            <span class="badge badge-primary">{{$diff22 > 1 ? 'Days '.$diff22 : 'Day '.$diff22}} in storage</span>
                                        @endif
                                    @else
                                        @if($value->pstatus == 11)
                                            <span class="badge badge-danger">{{$late22->diffInDays() > 1 ? 'Days '.$late22->diffInDays() : 'Day '.$late22->diffInDays()}} in storage</span>
                                        @endif
                                    @endif
                                @endif
                            @endif
                        @endif
                    </td>
                    <td>
                        Book Price: @if(!empty($value->order->payment)) ${{$value->order->payment}} @else N/A @endif
                        <br>
                        <?php $ophone = explode('*^', $value->order->ophone); ?>
                        @if(Auth::user()->userRole->name == 'Admin' || Auth::user()->userRole->name == 'Manager' || Auth::user()->userRole->name == 'Order Taker' || Auth::user()->userRole->name == 'CSR' || Auth::user()->userRole->name == 'Seller Agent')
                            @php
                                $check_panel = check_panel();
                            
                                if($check_panel == 1){
                            
                                $phoneaccess=explode(',',Auth::user()->emp_access_phone);
                                }
                                elseif($check_panel == 3)
                                {
                                    $phoneaccess = explode(',',Auth::user()->emp_access_test);
                                }
                                else{
                                $phoneaccess=explode(',',Auth::user()->emp_access_web);
                                }
                            @endphp
                            @if (in_array("42", $phoneaccess))
                                @foreach($ophone as $val3)
                                    <?php
                                        if(in_array("61", $phoneaccess))
                                        {
                                            $new = $val3;
                                        }
                                        else
                                        {
                                            $digits = \App\PhoneDigit::first();
                                            
                                            $new = putX($digits->hide_digits,$digits->left_right_status,$val3);
                                        }
                                        
                                    ?>
                                    @if($val3)
                                        <?php 
                                            $access = \App\OrderTakerQouteAccess::where('ot_ids',Auth::user()->id)
                                            ->where('calling_status',1)
                                            ->whereDate('from_date','>=',date('Y-m-d'))
                                            ->whereDate('to_date','<=',date('Y-m-d'))
                                            ->first();
                                        ?>
                                        <input type="hidden" id="orderId" value="{{$value->order->id}}" />
                                        @if(Auth::user()->order_taker_quote == 2)
                                            @if(($value->pstatus >= 12 && $value->pstatus <= 15) || $value->pstatus == 19)
                                                @if(isset($access->id))
                                                    <span class="text-center pd-2 bd-l">
                                                        <a onclick="call('{{base64_encode($val3)}}')"
                                                           class="btn btn-outline-info  mobile count_user mb-2"
                                                           style="padding: 3px 5px; font-size: 16px;"><i class="fa fa-phone"></i>&nbsp;{{$new}}</a><br>
                                                    </span>
                                                    <span class="text-center pd-2 bd-l">
                                                        <a class="btn btn-outline-info  sms mb-2"
                                                             onclick="msg('{{base64_encode($val3)}}')"
                                                           style="padding: 3px 5px; font-size: 16px;"><i class="fa fa-envelope"></i>&nbsp;{{$new}}</a><br>
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-center pd-2 bd-l">
                                                    <a onclick="call('{{base64_encode($val3)}}')"
                                                       class="btn btn-outline-info  mobile count_user mb-2"
                                                       style="padding: 3px 5px; font-size: 16px;"><i class="fa fa-phone"></i>&nbsp;{{$new}}</a><br>
                                                </span>
                                                <span class="text-center pd-2 bd-l">
                                                    <a class="btn btn-outline-info  sms mb-2"
                                                         onclick="msg('{{base64_encode($val3)}}')"
                                                       style="padding: 3px 5px; font-size: 16px;"><i class="fa fa-envelope"></i>&nbsp;{{$new}}</a><br>
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-center pd-2 bd-l">
                                                <a onclick="call('{{base64_encode($val3)}}')"
                                                   class="btn btn-outline-info  mobile count_user mb-2"
                                                   style="padding: 3px 5px; font-size: 16px;"><i class="fa fa-phone"></i>&nbsp;{{$new}}</a><br>
                                            </span>
                                            <span class="text-center pd-2 bd-l">
                                                <a class="btn btn-outline-info  sms mb-2"
                                                     onclick="msg('{{base64_encode($val3)}}')"
                                                   style="padding: 3px 5px; font-size: 16px;"><i class="fa fa-envelope"></i>&nbsp;{{$new}}</a><br>
                                            </span>
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                        @endif
                        <span class="text-center pd-2 bd-l mt-2">
                            @if($value->pstatus == 30)
                                <span class='badge badge-dark txt-white'>Pickup Approval</span>
                            @elseif($value->pstatus == 31)
                                <span class='badge badge-amber txt-white'>Deliver Approval</span>
                            @elseif($value->pstatus == 32)
                                <span class='badge badge-amber txt-white'>Schedule for Delivery</span>
                            @elseif($value->pstatus == 34)
                                <span class='badge badge-success txt-white'>Schedule to Another Driver</span>
                            @else
                                <?php echo get_pstatus2($value->pstatus) ?>
                            @endif
                            @if(isset($value->order->id))  
                                <br> Current Status: <br>
                                @if($value->order->pstatus == 11 && ($value->order->approve_pickup == 0 || $value->order->approve_pickup == NULL))
                                    <span class='badge badge-dark txt-white'>Pickup Approval</span>
                                @elseif($value->order->pstatus == 12 && ($value->order->approve_deliver == 0 || $value->order->approve_deliver == NULL))
                                    <span class='badge badge-amber txt-white'>Deliver Approval</span>
                                @elseif($value->order->pstatus == 12 && $value->order->approve_deliver == 2)
                                    <span class='badge badge-amber txt-white'>Schedule for Delivery</span>
                                @else
                                    <?php echo get_pstatus2($value->order->pstatus) ?>
                                @endif
                            @endif
                            @if(!empty($value->order->old_code))
                                - Old Quote
                            @endif
                        </span>
                        @if($pstatus >= 20 && $pstatus <= 27)
                            <br />
                            <button class="badge badge-info mt-1" onclick="getData22({{$value->order->id}},{{$pstatus}})" data-toggle="modal" data-target="#exampleModal">Show Detail</button>
                        @endif
                    </td>
                    <td>
                        Order Created Date: {{\Carbon\Carbon::parse($value->order->created_at)->format('M,d Y h:i A')}} <br>
                        @if($pstatus >= 20 && $pstatus <= 27)
                            Requested Date: {{\Carbon\Carbon::parse($value->order->req_ship[0]->created_at)->format('M,d Y h:i A')}} <br>
                        @else
                        Moved Created Date: {{\Carbon\Carbon::parse($value->created_at)->format('M,d Y h:i A')}} <br>
                        Moved Modified Date: {{\Carbon\Carbon::parse($value->updated_at)->format('M,d Y h:i A')}} <br>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="col-sm-12">
    <div class="row">
        <div class="col-sm-6 text-secondary my-auto">
            Showing {{ $data->firstItem() ?? 0 }} to {{ $data->lastItem() ?? 0 }} from total {{$data->total()}} entries
        </div>
        <div class="col-sm-6 d-flex justify-content-end">
            {{  $data->links() }}
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