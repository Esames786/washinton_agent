@include('partials.mainsite_pages.return_function')
<div class="row mb-2">
    <div class="col-lg-12 col-12">
        <div class="card">
            <div class="card-header">
                <?php 
                    $vehicle = '';
                    $vehicleArr = explode("*^-",$order->ymk);
                    if(count($vehicleArr) > 1)
                    {
                        $vehicle = count($vehicleArr).' Vehicles';
                    }
                    else
                    {
                        $vehicle =  $vehicleArr[0];
                    }
                ?>
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
                <h4 style="font-size:16px;" class="m-auto text-center">Vehicle Name: <span class="text-primary ml-1" title="{{$order->ymk}}">{{$vehicle}}</span></h4>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                        <div class="card" style="height:100%;">
                            <h4 class="card-header" style="font-size:16px;">Pickup: </h4>
                            <div class="card-body">
                                <h4 style="font-size:16px;">
                                    <a href="https://www.google.com/maps/dir/{{$order->originzip}},+USA/"
                                       target="_blank">
                                        <i class="fa fa-map-marker" style="color:green;" aria-hidden="true"></i>
                                       <span> {{$order->origincity}}-{{$order->originstate}}-{{$order->originzip}}</span>
                                    </a>
                                    <?php 
                                        if(isset($order->driver_pickup_date))
                                        {
                                            $picking_date = \Carbon\Carbon::parse($order->driver_pickup_date)->format('M,d Y'); 
                                        }
                                        else if(isset($order->est_pick_date)){
                                            $picking_date = \Carbon\Carbon::parse($order->est_pick_date)->format('M,d Y');
                                        } 
                                        else if(isset($order->pickup_date)){
                                            $picking_date = \Carbon\Carbon::parse($order->pickup_date)->format('M,d Y');
                                        }
                                        else
                                        {
                                            $picking_date = \Carbon\Carbon::now()->format('M,d Y');
                                        }
                                    ?>
                                    <p class="mt-1 mb-0">Date: {{$picking_date}}</p>
                                    @if(isset($order->oauction))
                                        <p class="mt-1 mb-0">{{$order->oauction}}</p>
                                    @endif
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                        <div class="card" style="height:100%;">
                            <h4 class="card-header" style="font-size:16px;">Delivery: </h4>
                            <div class="card-body">
                                <h4 style="font-size:16px;">
                                    <a href="https://www.google.com/maps/dir/{{$order->destinationzip}},+USA/"
                                       target="_blank">
                                        <i class="fa fa-map-marker" style="color:red;" aria-hidden="true"></i>
                                       <span> {{$order->destinationcity}}-{{$order->destinationstate}}-{{$order->destinationzip}}</span>
                                    </a>
                                    <?php
                                        if(isset($order->driver_deliver_date))
                                        {
                                            $delivering_date = \Carbon\Carbon::parse($order->driver_deliver_date)->format('M,d Y'); 
                                        }
                                        else if(isset($order->est_delivery_date)){
                                            $delivering_date = \Carbon\Carbon::parse($order->est_delivery_date)->format('M,d Y');
                                        } 
                                        else if(isset($order->delivery_date)){
                                            $delivering_date = \Carbon\Carbon::parse($order->delivery_date)->format('M,d Y');
                                        }
                                        else
                                        {
                                            $delivering_date = \Carbon\Carbon::now()->format('M,d Y');
                                        }
                                    ?>
                                    <p class="mt-1 mb-0">Date: {{$delivering_date}}</p>
                                    @if(isset($order->dauction))
                                        <p class="mt-1 mb-0">{{$order->dauction}}</p>
                                    @endif
                                </h4>
                            </div>
                        </div>
                    </div>
                    @if(isset($order->instruction[0]))
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-2">
                        <div class="card" style="height:100%;">
                            <h4 class="card-header" style="background: #232f3e;color: #fff;font-size: 22px;font-weight: 900;">Special Instruction: </h4>
                            <div class="card-body">
                                @foreach($order->instruction as $key => $instruction)
                                    <h5 class="mt-3 mb-0">{{get_user_name($instruction->user_id)}}:</h5>
                                    <p class="mb-0"><b>{{$key + 1}}.</b> {{$instruction->instruction}}</p>
                                    <span>
                                        <i class="fa fa-clock-o"></i> {{\Carbon\Carbon::parse($instruction->created_at)->format('M,d Y h:i A')}}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                    <?php 
                        $digits = App\PhoneDigit::first();
                    ?>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2">
                        <div class="card" style="height:100%;">
                            <h4 class="card-header" style="font-size:16px;">Customer: </h4>
                            <div class="card-body">
                                <h4 style="font-size:16px;">Name: <span class="text-primary">{{$order->oname}}</span></h4>
                                <?php 
                                    if(isset($order->ophone))
                                    {
                                        if(in_array("42", $phoneaccess))
                                        {
                                            if(in_array("67", $phoneaccess))
                                            {
                                                $ophonenew = $order->ophone;
                                            }
                                            else
                                            {
                                                $ophone = putX($digits->hide_digits,$digits->left_right_status,$order->ophone);
                                                $ophonenew = $ophone;
                                            }
                                        }
                                        else
                                        {
                                            $ophonenew = 'N/A';
                                        }
                                    }
                                    else
                                    {
                                        $ophonenew = 'N/A';
                                    }
                                ?>
                                <h4 style="font-size:16px;">Phone: <span class="text-primary">{{$ophonenew}}</span></h4>
                                @if(isset($order->ophone2))
                                    <?php 
                                        if(isset($order->ophone2))
                                        {
                                            if(in_array("42", $phoneaccess))
                                            {
                                                if(in_array("67", $phoneaccess))
                                                {
                                                    $ophone2new = $order->ophone2;
                                                }
                                                else
                                                {
                                                    $ophone2 = $this->putX($digits->hide_digits,$digits->left_right_status,$order->ophone2);
                                                    $ophone2new = $ophone2;
                                                }
                                            }
                                            else
                                            {
                                                $ophone2new = 'N/A';
                                            }
                                        }
                                        else
                                        {
                                            $ophone2new = 'N/A';
                                        }
                                    ?>
                                    <h4 style="font-size:16px;">Phone2: <span class="text-primary">{{$ophone2new}}</span></h4>
                                @endif
                                <h4 style="font-size:16px;" class="my-auto">Payment: {!! html_entity_decode(pay_status($order->paid_status)) !!}</h4>
                                <h4 style="font-size:16px;" class="my-auto">Payment Method: {{$order->payment_method ?? 'N/A'}}</h4>
                                <div class="m-auto" style="font-size:16px;">
                                    @if($order->storage_charge > 0 || $order->already_auction_storage > 0 || $order->late_pickup_auction_storage > 0)
                                        @if($order->storage_charge > 0)
                                            <p>Storage Charges: <span class="text-primary">${{$order->storage_charge}}</span> </p><br />
                                        @endif
                                        @if($order->already_auction_storage > 0)
                                            <p>Storage Already Auction: <span class="text-primary">${{$order->already_auction_storage}}</span></p><br />
                                        @endif
                                        @if($order->late_pickup_auction_storage > 0)
                                            <p>Storage Late Pickup Auction: <span class="text-primary">${{$order->late_pickup_auction_storage}}</span></p><br />
                                        @endif
                                    @else
                                        <p>No Storage</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2">
                        <div class="card" style="height:100%;">
                            <h4 class="card-header" style="font-size:16px;">Double Book: </h4>
                            <div class="card-body">
                                <h4 style="font-size:16px;">Company Name: <span class="text-primary">{{$order->company_name ?? 'N/A'}}</span></h4>
                                <h4 style="font-size:16px;">Company Price: <span class="text-primary">{{$order->company_price ?? 'N/A'}}</span></h4>
                                <h4 style="font-size:16px;">Company Comments: <span class="text-primary">{{$order->company_comments ?? 'N/A'}}</span></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2">
                        <div class="card" style="height:100%;">
                            <div class="card-header d-flex justify-content-center">
                                <h3 style="font-size:16px;">Last History:</h3>
                            </div>
                            <div class="card-body">
                                <h4>{{get_user_name($order->latestHistory->userId)}}</h4>
                                @if(isset($order->latestHistory->history))
                                    {!! html_entity_decode($order->latestHistory->history) !!}
                                @else
                                    <h5>N/A</h5>
                                @endif
                                <h5>
                                    <i class="fa fa-clock-o"></i> {{\Carbon\Carbon::parse($order->latestHistory->created_at)->format('M,d Y h:i A')}}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
                @if(isset($order->sheet))
                    <div class="row">
                        @foreach($order->sheet as $key => $val)
                            <?php 
                                $driver_no1 = NULL;
                                $driver_no2 = NULL;
                                $driver_no3 = NULL;
                                $driver_no4 = NULL;
                                if(in_array("60", $phoneaccess))
                                {
                                    if(in_array("61", $phoneaccess))
                                    {
                                        if(isset($val->driver_no))
                                        {
                                            $driver_no1 = $val->driver_no;
                                        }
                                        if(isset($val->driver_no2))
                                        {
                                            $driver_no2 = $val->driver_no2;
                                        }
                                        if(isset($val->driver_no3))
                                        {
                                            $driver_no3 = $val->driver_no3;
                                        }
                                        if(isset($val->driver_no4))
                                        {
                                            $driver_no4 = $val->driver_no4;
                                        }
                                    }
                                    else
                                    {
                                        if(isset($val->driver_no))
                                        {
                                            $driverphoneno1 = putX($digits->hide_digits,$digits->left_right_status,$val->driver_no);
                                            $driver_no1 = $driverphoneno1;
                                        }
                                        if(isset($val->driver_no2))
                                        {
                                            $driverphoneno2 = putX($digits->hide_digits,$digits->left_right_status,$val->driver_no2);
                                            $driver_no2 = $driverphoneno2;
                                        }
                                        if(isset($val->driver_no3))
                                        {
                                            $driverphoneno3 = putX($digits->hide_digits,$digits->left_right_status,$val->driver_no3);
                                            $driver_no3 = $driverphoneno3;
                                        }
                                        if(isset($val->driver_no4))
                                        {
                                            $driverphoneno4 = putX($digits->hide_digits,$digits->left_right_status,$val->driver_no4);
                                            $driver_no4 = $driverphoneno4;
                                        }
                                    }
                                }
                            ?>
                            @if($val->pstatus == 9)
                                <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
                                    <div class="card" style="height:100%;">
                                        <div class="card-header d-flex justify-content-between">
                                            <h4 style="font-size:14px;" class="my-auto">Listed Sheet: </h4>
                                            <h4 style="font-size:16px;" class="my-auto"><span class="badge badge-danger">Listed</span></h4>
                                        </div>
                                        <div class="card-body">
                                            @if(isset($val->auth_id)) <h4 class="m-0">Name: {{get_user_name($val->auth_id)}}</h4> @endif
                                            @if(isset($val->paid)) <span>Paid: {{$val->paid}}</span><br> @endif
                                            @if(isset($val->storage)) <span>Storage: {{$val->storage}}</span><br> @endif
                                            @if(isset($val->listed_price)) <span>Listed Price: {{$val->listed_price}}</span><br> @endif
                                            @if(isset($val->auction_update)) <span>Auction Update: {{$val->auction_update}}</span><br> @endif
                                            @if(isset($val->title_keys)) <span>Title: {{$val->title_keys}}</span><br> @endif
                                            @if(isset($val->keys)) <span>Keys: {{$val->keys}}</span><br> @endif
                                            @if(isset($val->vehicle_position)) <span>Vehicle Position: {{$val->vehicle_position}}</span><br> @endif
                                            @if(isset($val->listed_count)) <span>Listed Count: {{$val->listed_count}}</span><br> @endif
                                            @if(isset($val->price)) <span>Price: {{$val->price}}</span><br> @endif
                                            @if(isset($val->additional)) <span>Additional: {{$val->additional}}</span><br> @endif
                                        </div>
                                        <div class="card-footer">
                                            <i class="fa fa-clock-o"></i> {{\Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i A')}}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($val->pstatus == 10)
                                <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
                                    <div class="card" style="height:100%;">
                                        <div class="card-header d-flex justify-content-between">
                                            <h4 style="font-size:14px;" class="my-auto">Schedule Sheet: </h4>
                                            <h4 style="font-size:16px;" class="my-auto"><span class="badge badge-primary">Schedule</span></h4>
                                        </div>
                                        <div class="card-body">
                                            @if(isset($val->auth_id)) <h4 class="m-0">Name: {{get_user_name($val->auth_id)}}</h4> @endif
                                            @if(isset($val->company_name) || isset($val->pickup_date) || isset($val->delivery_date) || isset($val->price))
                                            <div class="card">
                                                <div class="card-body">
                                                    @if(isset($val->company_name)) <span>Company Name: {{$val->company_name}}</span><br> @endif
                                                    @if(isset($val->pickup_date)) <span>Pickup Date: {{\Carbon\Carbon::parse($val->pickup_date)->format('M,d Y h:i A')}}</span><br> @endif
                                                    @if(isset($val->delivery_date)) <span>Delivery Date: {{\Carbon\Carbon::parse($val->delivery_date)->format('M,d Y h:i A')}}</span><br> @endif
                                                    @if(isset($val->price)) <span>Dispatch Price: {{$val->price}}</span><br> @endif
                                                </div>
                                            </div>
                                            @endif
                                            @if(isset($val->auction_update)) <span>Auction Update: {{$val->auction_update}}</span><br> @endif
                                            @if(isset($val->who_pay_storage)) <span>Storage Pay: {{$val->who_pay_storage}}</span><br> @endif
                                            @if(isset($val->vehicle_position)) <span>Vehicle Position: {{$val->vehicle_position}}</span><br> @endif
                                            @if(isset($val->storage)) <span>Storage: {{$val->storage}}</span><br> @endif
                                            @if(isset($val->title_keys)) <span>Title: {{$val->title_keys}}</span><br> @endif
                                            @if(isset($val->keys)) <span>Keys: {{$val->keys}}</span><br> @endif
                                            @if(isset($val->vehicle_condition)) <span>Vehicle Condition: {{$val->vehicle_condition}}</span><br> @endif
                                            @if(isset($val->driver_fmcsa)) <span>Driver FMCSA: {{$val->driver_fmcsa}}</span><br> @endif
                                            @if(isset($val->carrier_rating)) <span>Carrier Rating: {{$val->carrier_rating}}</span><br> @endif
                                            @if(isset($val->fmcsa)) <span>Verify FMCSA?: {{$val->fmcsa}}</span><br> @endif
                                            @if(isset($val->coi_holder)) <span>COI Holder: {{$val->coi_holder}}</span><br> @endif
                                            @if(isset($val->vehicle_luxury)) <span>Vehicle Luxry: {{$val->vehicle_luxury}}</span><br> @endif
                                            @if(isset($val->payment_method)) <span>Payment Method: {{$val->payment_method}}</span><br> @endif
                                            @if(isset($val->aware_driver_delivery_date)) <span>Aware Driver Delivery: {{$val->aware_driver_delivery_date}}</span><br> @endif
                                            @if(isset($val->insurance_date)) <span>Date Of Insurance (FMCSA): {{\Carbon\Carbon::parse($val->insurance_date)->format('M,d Y')}}</span><br> @endif
                                            @if(isset($val->new_old_driver)) <span>New Or Old Driver: {{$val->new_old_driver}}</span><br> @endif
                                            @if(isset($val->is_local)) <span>Is Local?: {{$val->is_local}}</span><br> @endif
                                            @if(isset($val->job_accept)) <span>Job Accept?: {{$val->job_accept}}</span><br> @endif
                                            @if(isset($val->additional)) <span>Additional: {{$val->additional}}</span><br> @endif
                                        </div>
                                        <div class="card-footer">
                                            <i class="fa fa-clock-o"></i> {{\Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i A')}}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($val->pstatus == 11)
                                <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
                                    <div class="card" style="height:100%;">
                                        <div class="card-header d-flex justify-content-between">
                                            <h4 style="font-size:14px;" class="my-auto">Pickup Sheet: </h4>
                                            <h4 style="font-size:16px;" class="my-auto"><span class="badge badge-warning">Pickup</span></h4>
                                        </div>
                                        <div class="card-body">
                                            @if(isset($val->auth_id)) <h4 class="m-0">Name: {{get_user_name($val->auth_id)}}</h4> @endif
                                            @if(isset($val->company_name)) <span>Company Name: {{$val->company_name}}</span><br> @endif
                                            @if(isset($val->auction_status)) <span>Auction Status: {{$val->auction_status}}</span><br> @endif
                                            @if(isset($val->driver_status)) <span>Driver Status: {{$val->driver_status}}</span><br> @endif
                                            @if(isset($val->storage)) <span>Storage: {{$val->storage}}</span><br> @endif
                                            @if(isset($val->delivery_date)) <span>Delivery Date: {{\Carbon\Carbon::parse($val->delivery_date)->format('M,d Y h:i A')}}</span><br> @endif
                                            @if(isset($val->vehicle_condition)) <span>Vehicle Condition: {{$val->vehicle_condition}}</span><br> @endif
                                            @if(isset($val->title_keys)) <span>Title: {{$val->title_keys}}</span><br> @endif
                                            @if(isset($val->keys)) <span>Keys: {{$val->keys}}</span><br> @endif
                                            @if(isset($val->vehicle_position)) <span>Vehicle Position: {{$val->vehicle_position}}</span><br> @endif
                                            @if(isset($val->payment)) <span>Payment: {{$val->payment}}</span><br> @endif
                                            @if(isset($val->payment_charged_or_owes)) <span>Payment Charged Or Owes: {{$val->payment_charged_or_owes}}</span><br> @endif
                                            @if(isset($val->payment_method)) <span>Payment Method: {{$val->payment_method}}</span><br> @endif
                                            @if(isset($val->price)) <span>Total Amount (If Owed): {{$val->price}}</span><br> @endif
                                            @if(isset($val->stamp_dock_receipt)) <span>Stamp Dock Receipt: {{$val->stamp_dock_receipt}}</span><br> @endif
                                            @if(isset($val->carrier_name)) <span>Carrier Name: {{$val->carrier_name}}</span><br> @endif
                                            @if(isset($val->driver_payment)) <span>Driver Payment: {{$val->driver_payment}}</span><br> @endif
                                            @if(isset($driver_no1)) <span>Driver No#1: </span>
                                                @if(Auth::user()->userRole->name == 'Admin' || Auth::user()->userRole->name == 'Delivery Boy')
                                                <span class="text-center pd-2 bd-l">
                                                    <a class="btn btn-outline-info  sms mb-2"
                                                         onclick="call2('{{base64_encode($val->driver_no)}}')"
                                                       style="padding: 3px 5px; font-size: 16px;"><i class="fa fa-phone"></i>&nbsp;{{$driver_no1}}</a>
                                                </span>
                                                @else
                                                {{$driver_no1}}
                                                @endif
                                                <br> 
                                            @endif
                                            @if(isset($driver_no2)) <span>Driver No#2: </span>
                                                @if(Auth::user()->userRole->name == 'Admin' || Auth::user()->userRole->name == 'Delivery Boy')
                                                <span class="text-center pd-2 bd-l">
                                                    <a class="btn btn-outline-info  sms mb-2"
                                                         onclick="call2('{{base64_encode($val->driver_no2)}}')"
                                                       style="padding: 3px 5px; font-size: 16px;"><i class="fa fa-phone"></i>&nbsp;{{$driver_no2}}</a>
                                                </span>
                                                @else
                                                {{$driver_no2}}
                                                @endif
                                                <br> 
                                            @endif
                                            @if(isset($driver_no3)) <span>Driver No#3: </span>
                                                @if(Auth::user()->userRole->name == 'Admin' || Auth::user()->userRole->name == 'Delivery Boy')
                                                <span class="text-center pd-2 bd-l">
                                                    <a class="btn btn-outline-info  sms mb-2"
                                                         onclick="call2('{{base64_encode($val->driver_no3)}}')"
                                                       style="padding: 3px 5px; font-size: 16px;"><i class="fa fa-phone"></i>&nbsp;{{$driver_no3}}</a>
                                                </span>
                                                @else
                                                {{$driver_no3}}
                                                @endif
                                                <br> 
                                            @endif
                                            @if(isset($driver_no4)) <span>Driver No#4: </span>
                                                @if(Auth::user()->userRole->name == 'Admin' || Auth::user()->userRole->name == 'Delivery Boy')
                                                <span class="text-center pd-2 bd-l">
                                                    <a class="btn btn-outline-info  sms mb-2"
                                                         onclick="call2('{{base64_encode($val->driver_no4)}}')"
                                                       style="padding: 3px 5px; font-size: 16px;"><i class="fa fa-phone"></i>&nbsp;{{$driver_no4}}</a>
                                                </span>
                                                @else
                                                {{$driver_no4}}
                                                @endif
                                                <br> 
                                            @endif
                                            @if(isset($val->additional)) <span>Additional: {{$val->additional}}</span><br> @endif
                                        </div>
                                        <div class="card-footer">
                                            <i class="fa fa-clock-o"></i> {{\Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i A')}}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($val->pstatus == 12)
                                <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
                                    <div class="card" style="height:100%;">
                                        <div class="card-header d-flex justify-content-between">
                                            <h4 style="font-size:14px;" class="my-auto">Delivered Sheet: </h4>
                                            <h4 style="font-size:16px;" class="my-auto"><span class="badge badge-success">Delivered</span></h4>
                                        </div>
                                        <div class="card-body">
                                            @if(isset($val->auth_id)) <h4 class="m-0">Name: {{get_user_name($val->auth_id)}}</h4> @endif
                                            @if(isset($driver_no1)) <span>Driver No#1: </span>
                                                @if(Auth::user()->userRole->name == 'Admin' || Auth::user()->userRole->name == 'Delivery Boy')
                                                <span class="text-center pd-2 bd-l">
                                                    <a class="btn btn-outline-info  sms mb-2"
                                                         onclick="call2('{{base64_encode($val->driver_no)}}')"
                                                       style="padding: 3px 5px; font-size: 16px;"><i class="fa fa-phone"></i>&nbsp;{{$driver_no1}}</a>
                                                </span>
                                                @else
                                                {{$driver_no1}}
                                                @endif
                                                <br> 
                                            @endif
                                            @if(isset($driver_no2)) <span>Driver No#2: </span>
                                                @if(Auth::user()->userRole->name == 'Admin' || Auth::user()->userRole->name == 'Delivery Boy')
                                                <span class="text-center pd-2 bd-l">
                                                    <a class="btn btn-outline-info  sms mb-2"
                                                         onclick="call2('{{base64_encode($val->driver_no2)}}')"
                                                       style="padding: 3px 5px; font-size: 16px;"><i class="fa fa-phone"></i>&nbsp;{{$driver_no2}}</a>
                                                </span>
                                                @else
                                                {{$driver_no2}}
                                                @endif
                                                <br> 
                                            @endif
                                            @if(isset($driver_no3)) <span>Driver No#3: </span>
                                                @if(Auth::user()->userRole->name == 'Admin' || Auth::user()->userRole->name == 'Delivery Boy')
                                                <span class="text-center pd-2 bd-l">
                                                    <a class="btn btn-outline-info  sms mb-2"
                                                         onclick="call2('{{base64_encode($val->driver_no3)}}')"
                                                       style="padding: 3px 5px; font-size: 16px;"><i class="fa fa-phone"></i>&nbsp;{{$driver_no3}}</a>
                                                </span>
                                                @else
                                                {{$driver_no3}}
                                                @endif
                                                <br> 
                                            @endif
                                            @if(isset($driver_no4)) <span>Driver No#4: </span>
                                                @if(Auth::user()->userRole->name == 'Admin' || Auth::user()->userRole->name == 'Delivery Boy')
                                                <span class="text-center pd-2 bd-l">
                                                    <a class="btn btn-outline-info  sms mb-2"
                                                         onclick="call2('{{base64_encode($val->driver_no4)}}')"
                                                       style="padding: 3px 5px; font-size: 16px;"><i class="fa fa-phone"></i>&nbsp;{{$driver_no4}}</a>
                                                </span>
                                                @else
                                                {{$driver_no4}}
                                                @endif
                                                <br> 
                                            @endif
                                            @if(isset($val->delivery_date)) <span>Delivery Date: {{\Carbon\Carbon::parse($val->delivery_date)->format('M,d Y h:i A')}}</span><br> @endif
                                            @if(isset($val->vehicle_position)) <span>Vehicle Position: {{$val->vehicle_position}}</span><br> @endif
                                            @if(isset($val->who_pay_storage)) <span>Storage Pay: {{$val->who_pay_storage}}</span><br> @endif
                                            @if(isset($val->title_keys)) <span>Title: {{$val->title_keys}}</span><br> @endif
                                            @if(isset($val->keys)) <span>Keys: {{$val->keys}}</span><br> @endif
                                            @if(isset($val->client_status)) <span>Client Status: {{$val->client_status}}</span><br> @endif
                                            @if(isset($val->driver_status)) <span>Driver Status: {{$val->driver_status}}</span><br> @endif
                                            @if(isset($val->owes_status)) <span>Owes Status: {{$val->owes_status}}</span><br> @endif
                                            @if(isset($val->driver_payment_status)) <span>Driver Payment Status: {{$val->driver_payment_status}}</span><br> @endif
                                            @if(isset($val->vehicle_condition)) <span>Vehicle Condition: {{$val->vehicle_condition}}</span><br> @endif
                                            @if(isset($val->customer_informed)) <span>Customer Informed: {{$val->customer_informed}}</span><br> @endif
                                            @if(isset($val->additional)) <span>Additional: {{$val->additional}}</span><br> @endif
                                        </div>
                                        <div class="card-footer">
                                            <i class="fa fa-clock-o"></i> {{\Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i A')}}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($val->pstatus == 13)
                                <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
                                    <div class="card" style="height:100%;">
                                        <div class="card-header d-flex justify-content-between">
                                            <h4 style="font-size:14px;" class="my-auto">Delivered Sheet: </h4>
                                            <h4 style="font-size:16px;" class="my-auto"><span class="badge badge-success">Delivered</span></h4>
                                        </div>
                                        <div class="card-body">
                                            @if(isset($val->auth_id)) <h4 class="m-0">Name: {{get_user_name($val->auth_id)}}</h4> @endif
                                            @if(isset($value3->comments)) <span>Comments: {{$value3->comments}}</span><br> @endif
                                            @if(isset($value3->satisfied)) <span>>Satisfied: {{$value3->satisfied}}</span><br> @endif
                                            @if(isset($value3->remarks)) <span>Remarks: {{$value3->remarks}}</span><br> @endif
                                            @if(isset($value3->review)) <span>Review: {{$value3->review}}</span><br> @endif
                                            @if(isset($value3->client_rating)) <span>Client Rating: {{$value3->client_rating}}</span><br> @endif
                                            @if(isset($value3->website)) <span>Website: {{$value3->website}}</span><br> @endif
                                            @if(isset($value3->website_other)) <span>Other Website: {{$value3->website_other}}</span><br> @endif
                                            @if(isset($value3->website_link)) <span>Website Link: {{$value3->website_link}}</span><br> @endif
                                            @if(isset($value3->additional)) <span>Additional: {{$value3->additional}}</span><br> @endif
                                        </div>
                                        <div class="card-footer">
                                            <i class="fa fa-clock-o"></i> {{\Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i A')}}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<script>
    var status = "{!! html_entity_decode(get_pstatus2($order->pstatus)) !!}";
    var id = "{{$order->id}}";
    $("#order_detail_status").html(`Current Status: <h4 style="font-size:16px;" class="my-auto">${status}</h4>`);
    $("#order_detail_title").html(`<h3 class="text-center mb-0">Order Id# <a href="/searchData?search=${id}" target="_blank" class="text-primary">${id}</a></h3>`);
     
     function call2(num)
     {
         var num1 = atob(num);
        //  var newNum = num1.replace(/[- )(]/g,'');
        //  console.log(num1);
         window.location.href = 'rcmobile://call/?number='+num1;
        //  window.location.href = 'tel://'+newNum;
     }
</script>