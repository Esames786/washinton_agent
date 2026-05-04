@extends('layouts.innerpages')

@section('template_title')
    Summary
@endsection
@include('partials.mainsite_pages.return_function')
<style>
    @import url('https://fonts.googleapis.com/css?family=Open+Sans|Rock+Salt|Shadows+Into+Light|Cedarville+Cursive');

    .fa-check {
        color: green;
    }

    #table_heading {
        font-size: 15px;
        font-weight: 600;
        color: black;
        background-color: #6cabefd1;
    }

    .table {
        border: 1px solid black;
    }

    .oauc {
        padding: 11px;
        width: 100%;
    }

    .dauc {
        padding: 11px;
        width: 100%;
    }

    .history_content {
        /*background-color: white;*/
    }

    #right_border_radius {
        border-right: 1px solid black;
        border-top-right-radius: 25px;
        border-bottom-right-radius: 25px;
    }

    #left_border_radius {
        border-left: 1px solid black;
        border-top-left-radius: 25px;
        border-bottom-left-radius: 25px;
    }

    .strong_word {
        font-weight: 700;
        font-size: 14px;
        font-family: "Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", "Helvetica Neue", Arial, sans-serif;
        /*color: rgb(0 123 255);*/

    }
    .inner_style{
        color: rgb(0 123 255) !important;
        text-transform: capitalize;
    }

.list-group-item{
    /*color: rgb(0 123 255) !important;*/
}
h6{
    /*color: rgb(0 123 255) !important;*/
}
    td {
        /*color: rgb(0 123 255) !important;*/
        font-weight: 600;
    }

    * {
        font-family: "Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", "Helvetica Neue", Arial, sans-serif;
    }
    select.custom-select.custom-select-sm.form-control.form-control-sm {
        height: 32px;
    }
    .grid.grid_3 > div {
    align-self: stretch;
}

.grid.grid_3 > div .card {
    height: 100%;
}
      .card-header {
            background-color:#077199 !important;
        }
        .wrapping
        {
            width:300px;
        }
        
        
        .not-allowed {
             cursor: not-allowed;
             user-select: none;
        }
        .not-allowed::selection {
             user-select: none;
        }
</style>

@section('content')


    <div class="modal" id="reportmodal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-header pd-x-20">
                    <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Send Email Link</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <form action="" method="post" id="form">
                        <h5 class=" lh-3 mg-b-20">Order Id <input readonly type="text" id="orderid" name="orderid" value="{{$data->id}}"/></h5>
                        <div class="card">
                            <div class="card-body pd-20">

                                @csrf
                                Email Link
                                </br>
                                 <div style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; z-index: 1;"></div>
                                <!-- <input type="hidden" name="user_id" value="47"> -->
                                <div class="form-group">
                                    <div class="row row-sm">
                                        <div class="col-sm">
                                            <input type="text" readonly name="link" id="link"
                                                   class="form-control not-allowed"
                                                   value=""/>
                                        <div style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; z-index: 1;"></div>
                                        </div><!-- col -->
                                    </div><!-- row -->
                                </div><!-- form-group -->
                                <div class="form-group">
                                    <input type="text" name="email" id="email"
                                           class="form-control"
                                           value="" placeholder="Enter email address..."/>
                                </div><!-- form-group -->
                                <button type="submit" class="btn btn-primary pd-x-20">Submit
                                </button>

                            </div><!-- card-body -->
                        </div>
                    </form>
                </div><!-- modal-body -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-5">
        <h3>ORDER #{{$data->id}} History</h3>

        <div class="history_content ">
            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <div class="grid grid_3 m-2">
                            <div class="">
                                <div class="card">
                                    <div class="card-header bg-primary text-white" style=" background-color:#077199 !important;">
                                        <h4>CUSTOMER INFORMATION</h4>
                                    </div>
                                    @php
                                        $ophones=explode('*^',$data->ophone);
                                        $dphones=explode('*^',$data->dphone);
                                    @endphp
                                    <div class="card-body">
                                        <strong class="strong_word">Name : <span
                                                    class="inner_style"> {{$data->oname}}</span> </strong><br>
                                        <strong class="strong_word">Email : <span
                                                    class="inner_style">{{$data->oemail}}</span> </strong><br>
                                        <strong class="strong_word">Phone-Number : <span class="inner_style">

                                                @foreach($ophones as $ophone)
                                                    @php

                                                        $new = '(xxx) xxx-'.substr( $ophone, -4);
                                                    @endphp


                                                    {{$new}}
                                                @endforeach

                                            </span>
                                        </strong>

                                    </div>
                                     <div class="card-footer">
                                        <strong class="strong_word" style="color: black">SIGNATURE :
                                            @if(isset($data2->signature))
                                            <span class="inner_style" style=" font-family: 'Cedarville Cursive', cursive;font-size: 1.8em;">
                                                {{$data2->signature}}
                                            </span>
                                            @elseif(isset($data->signature))
                                            <span class="inner_style" style=" font-family: 'Cedarville Cursive', cursive;font-size: 1.8em;">
                                                {{$data->signature}}
                                            </span>
                                            @else
                                            <span class="inner_style" style="font-size: 1.2em;">NO SIGNATURE</span>
                                            @endif

                                        </strong>

                                    </div>
                                    <div class="card-footer">
                                        <strong style="color:black">Previous Record {{$count_previous + $old_count_previous}}</strong>
                                        <a href="/searchData?search={{($data->mainPhNum) ? $data->mainPhNum : $data->main_ph }}" class="inner_style">Show
                                            Previous</a>
                                    </div>

                                    <!--<div class="card-header  bg-primary text-white">-->
                                    <!--    <h4>ORIGIN & DEST. INFORMATION</h4>-->
                                    <!--</div>-->

                                  

                                </div>

                            </div>
                            <?php
                            $condition1 = explode('*^', $data->condition);
                            $transport = explode('*^', $data->transport);
                            ?>
                            <div class="">
                                <div class="card">
                                    <div class="card-header bg-primary text-white" style=" background-color:#077199 !important;">
                                        <h4>ORDER DETAILS</h4>

                                    </div>
                                    <div class="card-body">
                                        <strong class="strong_word">Vehicle Name :
                                            <span class="inner_style">
                                                 <?php
                                                $vehiclename = explode('*^-', $data->ymk);
                                                foreach ($vehiclename as $key=>$vehicleymk) {
                                                    if(!empty(trim($vehiclename[$key]))){
                                                        echo " ($vehicleymk)" . '<br>';
                                                    }else{
                                                        echo '(N/A)' . '<br>';
                                                    }
                                                }
                                                ?>
                                            </span>
                                        </strong>


                                        <strong class="strong_word"> Vin:
                                            <span class="inner_style">
                                                <?php
                                                $vin_num = explode('*^', $data->vin_num);
                                                foreach ($vin_num as $key=>$val_vin) {
                                                    if(!empty(trim($val_vin))){
                                                        echo " ($val_vin)" . '<br>';
                                                    }else{
                                                        echo '(N/A)' . '<br>';
                                                    }
                                                }
                                                ?>

                                            </span>
                                        </strong>

                                        <strong class="strong_word">Condition : <span class="inner_style">
                                                 @foreach($condition1 as $val2)
                                                    {{ "(".get_condtion($val2)."),"}}
                                                @endforeach

                                            </span>
                                        </strong><br>
                                        <strong class="strong_word">Trailer Type :
                                            <span class="inner_style">
                                                 @foreach($transport as $val3)
                                                    {{ "(".get_cartype($val3)."),"}}
                                                @endforeach

                                            </span>
                                        </strong><br>

                                        @if($data->car_type == 2)
                                            <strong class="strong_word"> Type:
                                                <span class="inner_style">
                                                    <?php
                                                    $type = explode('*^', $data->type);
                                                    foreach ($type as $key=> $type_data) {
                                                        if(!empty(trim($type[$key]))){
                                                            echo " ($type[$key])" . ',';
                                                        }else{
                                                            echo '(N/A)' . ',';
                                                        }
                                                    }
                                                    ?>

                                                </span>
                                            </strong>
                                            <br>
                                            <strong class="strong_word"> Load method:
                                                <span class="inner_style">
                                                    <?php
                                                    $load_method = explode('*^', $data->load_method);
                                                    foreach ($load_method as $key=> $load_method_data) {
                                                        if(!empty(trim($load_method[$key]))){
                                                            echo " ($load_method[$key])" . ',';
                                                        }else{
                                                            echo '(N/A)' . ',';
                                                        }
                                                    }
                                                    ?>

                                                </span>
                                            </strong>
                                            <br>
                                            <strong class="strong_word"> Unload method:
                                                <span class="inner_style">
                                                    <?php
                                                    $unload_method = explode('*^', $data->unload_method);
                                                    foreach ($unload_method as $key=> $unload_method_data) {
                                                        if(!empty(trim($unload_method[$key]))){
                                                            echo " ($unload_method[$key])" . ',';
                                                        }else{
                                                            echo '(N/A)' . ',';
                                                        }
                                                    }
                                                    ?>

                                                </span>
                                            </strong>
                                            <br>
                                            <strong class="strong_word"> Length Fit:
                                                <span class="inner_style">
                                                    <?php
                                                    $length_ft = explode('*^', $data->length_ft);
                                                    foreach ($length_ft as $key=> $length_ft_data) {
                                                        if(!empty(trim($length_ft[$key]))){
                                                            echo " ($length_ft[$key])" . ',';
                                                        }else{
                                                            echo '(N/A)' . ',';
                                                        }
                                                    }
                                                    ?>

                                                </span>
                                            </strong>
                                            <br>
                                            <strong class="strong_word"> Length Inch:
                                                <span class="inner_style">
                                                    <?php
                                                    $length_in = explode('*^', $data->length_in);
                                                    foreach ($length_in as $key=> $length_in_data) {
                                                        if(!empty(trim($length_in[$key]))){
                                                            echo " ($length_in[$key])" . ',';
                                                        }else{
                                                            echo '(N/A)' . ',';
                                                        }
                                                    }
                                                    ?>

                                                </span>
                                            </strong>
                                            <br>
                                            <strong class="strong_word"> Width Fit:
                                                <span class="inner_style">
                                                    <?php
                                                    $width_ft = explode('*^', $data->width_ft);
                                                    foreach ($width_ft as $key=> $width_ft_data) {
                                                        if(!empty(trim($width_ft[$key]))){
                                                            echo " ($width_ft[$key])" . ',';
                                                        }else{
                                                            echo '(N/A)' . ',';
                                                        }
                                                    }
                                                    ?>

                                                </span>
                                            </strong>
                                            <br>
                                            <strong class="strong_word"> Width Inch:
                                                <span class="inner_style">
                                                    <?php
                                                    $width_in = explode('*^', $data->width_in);
                                                    foreach ($width_in as $key=> $width_in_data) {
                                                        if(!empty(trim($width_in[$key]))){
                                                            echo " ($width_in[$key])" . ',';
                                                        }else{
                                                            echo '(N/A)' . ',';
                                                        }
                                                    }
                                                    ?>

                                                </span>
                                            </strong>
                                            <br>
                                            <strong class="strong_word"> Height Fit:
                                                <span class="inner_style">
                                                    <?php
                                                    $height_ft = explode('*^', $data->height_ft);
                                                    foreach ($height_ft as $key=> $height_ft_data) {
                                                        if(!empty(trim($height_ft[$key]))){
                                                            echo " ($height_ft[$key])" . ',';
                                                        }else{
                                                            echo '(N/A)' . ',';
                                                        }
                                                    }
                                                    ?>

                                                </span>
                                            </strong>
                                            <br>
                                            <strong class="strong_word"> Height Inch:
                                                <span class="inner_style">
                                                    <?php
                                                    $height_in = explode('*^', $data->height_in);
                                                    foreach ($height_in as $key=> $height_in_data) {
                                                        if(!empty(trim($height_in[$key]))){
                                                            echo " ($height_in[$key])" . ',';
                                                        }else{
                                                            echo '(N/A)' . ',';
                                                        }
                                                    }
                                                    ?>

                                                </span>
                                            </strong>
                                            <br>
                                            <strong class="strong_word"> Weight
                                                <span class="inner_style">
                                                    <?php
                                                    $weight = explode('*^', $data->weight);
                                                    foreach ($weight as $key=> $weight_data) {
                                                        if(!empty(trim($weight[$key]))){
                                                            echo " ($weight[$key])" . ',';
                                                        }else{
                                                            echo '(N/A)' . ',';
                                                        }
                                                    }
                                                    ?>

                                                </span>
                                            </strong>
                                            <br>

                                        @endif

                                        <strong class="strong_word">Origin Location :
                                            <span class="inner_style">{{$data->originzsc}}</span>
                                        </strong><br>
                                        <strong class="strong_word">Destination Location :
                                            <span class="inner_style">{{$data->destinationzsc}}</span>
                                        </strong><br>
                                        <strong class="strong_word">Addition Vehicle Information :
                                            <span class="inner_style">{{$data->add_info}}</span>
                                        </strong><br>
                                        <strong class="strong_word">Addition Information :
                                            <span class="inner_style" title="{{$data->additional_2}}">
                                               {{str_limit($data->additional_2, 15)}}
                                            </span>
                                        </strong>
                                        <strong class="strong_word"> :
                                            <span class="inner_style">{{$data->destinationzsc}}</span>
                                        </strong><br>
                                    </div>
                                      <div class="card-body">
                                        <strong class="strong_word">Pickup Phone : <span class="inner_style">

                                                @foreach($ophones as $ophone)
                                                    @php
                                                        $new_ophone = '(xxx) xxx-'.substr( $ophone, -4);
                                                    @endphp

                                                    {{$new_ophone }}

                                                @endforeach

                                            </span></strong><br>
                                        <strong class="strong_word">Delivery Phone :
                                            <span class="inner_style">
                                                @foreach($dphones as $dphone)
                                                    @php
                                                        $new_dphone = '(xxx) xxx-'.substr( $dphone, -4);
                                                    @endphp

                                                    {{$new_dphone}}

                                                @endforeach
                                            </span>
                                        </strong>
                                        <br>
                                        <strong class="strong_word">Pickup Zip :
                                            <span class="inner_style">{{$data->originzsc}}</span>
                                        </strong>
                                        <br>
                                        <strong class="strong_word">Pickup Address :
                                            <span class="inner_style">{{$data->oaddress}}</span>
                                        </strong>
                                        <br>
                                        <strong class="strong_word">Destination Zip :
                                            <span class="inner_style">{{$data->destinationzsc}}</span>
                                        </strong>
                                        <br>
                                        <strong class="strong_word">Destination Address :
                                            <span class="inner_style">{{$data->daddress}}</span>
                                        </strong>
                                        <br>

                                    </div>
                                   
                                    {{--here--}}
                                    @if(!empty($carrier))
                                        <div class="card-header  bg-primary text-white">
                                            <h4>Carrier Details</h4>
                                        </div>
                                        <div class="card-body">
                                            <strong class="strong_word">Company Name : <span class="inner_style">
                                                <a target="_blank"
                                                   href="{{url('/carrier_edit_day_dispatch')}}/{{$carrier->carrier_id}}"><span
                                                            class="fa fa-edit"> {{$carrier->carrier_login->company_name}}</span></a>
                                            </span></strong><br>
                                            <strong class="strong_word">Mc# : <span class="inner_style">
                                                {{$carrier->carrier_login->company_mc_number}}
                                            </span></strong><br>
                                            <strong class="strong_word">Location : <span class="inner_style">
                                                {{$carrier->carrier_login->company_address1}}
                                            </span></strong><br>
                                            <strong class="strong_word">Company Phone : <span class="inner_style">
                                                {{$carrier->carrier_login->company_phone_local}}
                                            </span></strong><br>
                                            <strong class="strong_word">Driver Phone :
                                                <span class="inner_style">
                                                      {{$carrier->carrier_login->contact_phone_cell}}
                                            </span></strong><br>
                                            <strong class="strong_word">Estimate Pickup :
                                                <span class="inner_style">{{date("M-d-Y",strtotime($carrier->carrier_pickup_date)) }}</span></strong><br>
                                            <strong class="strong_word">Estimate Delivery: <span
                                                        class="inner_style">{{date("M-d-Y",strtotime($carrier->carrier_delivery_date)) }}</span></strong><br>
                                            <strong class="strong_word" style="color: black">Ask Price :
                                                <span class="badge badge-warning">{{$carrier->carrier_price}}</span>
                                            </strong><br>
                                            <strong class="strong_word" style="color: black">Previous Orders :
                                                <span class="inner_style" href="javascript:void(0)" id='previous_order'
                                                      style="cursor: pointer;">
                                                      Show Orders
                                            </span>
                                            </strong>


                                        </div>


                                    @endif
                                </div>

                            </div>
                            <div class="">
                                <div class="card" style="overflow: auto; ">
                                    <div class="card-header bg-primary text-white" style=" background-color:#077199 !important;">
                                        <h4>STATUS</h4>
                                    </div>

                                    <div class="card-body" style=" height: 30pc; overflow: scroll; ">
                                        <ul class="list-group">
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus == 0)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                New

                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus == 1)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                Interested

                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus == 2)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                FollowUp/More
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==3)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                AskingLow
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==4)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                NotInterested
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==5)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                NoResponse
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==6)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif

                                                TimeQuote
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==7)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                PaymentMissing
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==8)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                Booked
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==9)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                Listed
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==10)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                Schedule
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==11)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                Pickup
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==12)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                Delivered
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==13)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                Completed
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==14)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                Cancel
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==15)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                Deleted
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==16)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                OwesMoney
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==18)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                On Approval
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==19)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                Cancel Approval
                                            </li>

                                        </ul>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                    @if ($data->car_type == 3)
                            <div class="col-sm-12 border">
                                <div style="margin-left: -16px;margin-right: -16px;
                                    border-width: 0px 0px 1px 0px !important;
                                    background-color:#077199 !important; color:#fff !important;"
                                     class="card-header bg-secondary text-white font-weight-bold">
                                    Freight Detail
                                </div>


                                <div class="row">
                                    <div class="col-sm-6">
                                        <strong>Order Information</strong>
                                        <table class="table customtable">
                                            @php
                                                if ($data->freight) {
                                                    # code...
                                                    $frieght_class = $data->freight->frieght_class;
                                                    $equipment_type = implode(', ', explode('^*', $data->freight->equipment_type));
                                                    $trailer_specification = $data->freight->trailer_specification;
                                                    $ex_pickup_date = $data->freight->ex_pickup_date;
                                                    $ex_pickup_time = $data->freight->ex_pickup_time;
                                                    $ex_delivery_date = $data->freight->ex_delivery_date;
                                                    $ex_delivery_time = $data->freight->ex_delivery_time;
                                                    $commodity_detail = $data->freight->commodity_detail;
                                                    $commodity_unit = $data->freight->commodity_unit;
                                                    $total_weight_lb = $data->freight->total_weight_lbs;
                                                    $pick_up_service = implode(', ', explode('^*', $data->freight->pick_up_services));
                                                    $deliver_service = implode(', ', explode('^*', $data->freight->deliver_services));
                                                    $shipment_prefences = $data->freight->shipment_prefences;
                                                }
                                            @endphp
                                            <tbody>
                                            <tr>
                                                <td>Freight Class</td>
                                                <td class="font-weight-bold">{{ $frieght_class }}</td>
                                            </tr>
                                            <tr>
                                                <td>Equipment Type</td>
                                                <td class="font-weight-bold">{{ $equipment_type }}</td>
                                            </tr>
                                            <tr>
                                                <td>Trailer Specification</td>
                                                <td class="font-weight-bold">{{ $trailer_specification }}</td>
                                            </tr>
                                            <tr>
                                                <td>Expected Pickup Date</td>
                                                <td class="font-weight-bold">{{ $ex_pickup_date }}</td>
                                            </tr>
                                            <tr>
                                                <td>Expected Pickup Time</td>
                                                <td class="font-weight-bold">{{ $ex_pickup_time }}</td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-sm-6">
                                        <strong>Delivery Detail</strong>
                                        <table class="table customtable">
                                            <tbody>
                                            <tr>
                                                <td>Expected Delivery Date</td>
                                                <td class="font-weight-bold">{{ $ex_delivery_date }}</td>
                                            </tr>
                                            <tr>
                                                <td>Expected Delivery Time</td>
                                                <td class="font-weight-bold">{{ $ex_delivery_time }}</td>
                                            </tr>
                                            <tr>
                                                <td>Commodity Detail</td>
                                                <td class="font-weight-bold">{{ $commodity_detail }}</td>
                                            </tr>
                                            <tr>
                                                <td>Commodity Unit</td>
                                                <td class="font-weight-bold">{{ $commodity_unit }}</td>
                                            </tr>
                                            <tr>
                                                <td>Total Weight (lbs)</td>
                                                <td class="font-weight-bold">{{ $total_weight_lb }}</td>
                                            </tr>
                                            <tr>
                                                <td>Pickup Services</td>
                                                <td class="font-weight-bold">{{ $pick_up_service }}</td>
                                            </tr>
                                            <tr>
                                                <td>Delivery Services</td>
                                                <td class="font-weight-bold">{{ $deliver_service }}</td>
                                            </tr>
                                            <tr>
                                                <td>Shipment Preferences</td>
                                                <td class="font-weight-bold">{{ $shipment_prefences }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                    @endif
                    </div>
                    <div class="card-footer " style="text-align: center;">
                        <?php 
                            $actionaccess = explode(',',Auth::user()->emp_access_action);
                        ?>
                        <div class="btn-group" id='order_action'>
                            @if(in_array("7",$actionaccess))
                            <a class="btn btn-secondary text-white w-200 p-2 " data-toggle="modal" data-target="#reportmodal"  id="left_border_radius" target="_blank"> Send Link</a>
                            @endif
                            @if(in_array("5",$actionaccess))
                            <a href="{{url('/new_edit/'. $data->id)}}" class=" btn btn-primary text-white w-200 p-2" target="_blank">Edit
                                Listing</a>
                            @endif
                            <a href="{{url("/print_report/$data->id")}}" target="_blank"
                               class=" btn btn-secondary text-white w-200 p-2">Print</a>
                            <!--<a target="_blank" href="http://classic.mapquest.com/embed?zoom=5&amp;q={{$data->origincity}}+{{$data->originstate}}+{{$data->originzip}}" class=" btn btn-primary text-white w-200 p-2  " id="right_border_radius"> View-->
                            <!--Route</a>-->
                            <!--<a target="_blank" href="https://www.mapquest.com/us/ny/{{$data->originzip}}?query={{$data->origincity}}+{{$data->originstate}}+{{$data->originzip}}&centerOnResults=1&zoom=5" class=" btn btn-primary text-white w-200 p-2  " id="right_border_radius"> View-->
                            <!--Route</a>-->
                            @if(in_array("8",$actionaccess))
                            <a target="_blank" href="https://www.google.com/maps/dir/{{$data->origincity}},{{$data->originstate}},{{$data->originzip}},+USA/{{$data->destinationcity}},{{$data->destinationstate}},{{$data->destinationzip}},+USA/" class=" btn btn-primary text-white w-200 p-2  " id="right_border_radius"> View
                            Route</a>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class=" col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white" style=" background-color:#077199 !important;">
                        <h4 class="m-0">PAYMENT DETAILS</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="list-group-item">ORDER PRICE : <span class="inner_style" >${{$data->payment}}</span></h5>
                        <h5 class="list-group-item">ORDER STATUS : <span class="inner_style">{{ get_pstatus($data->pstatus)}}</span></h5>
                        <h5 class="list-group-item">Pay to Carrier:  <span class="inner_style">${{$data->pay_carrier}}</span></h5>
                        <h5 class="list-group-item">COD/COP:  <span class="inner_style">${{$data->cod_cop}}</span></h5>
                        <h5 class="list-group-item">listed price: <span class="inner_style">${{$data->listed_price}}</span></h5>
                        <h5 class="list-group-item">Balance:  <span class="inner_style">${{$data->balance}}</span></h5>
                        <h5 class="list-group-item">Payment Status:  <span class="inner_style">{{($data2) ? $data2->payment_status : (($data->paid_status == 0) ? 'Unpaid' : 'Paid') }}</span></h5>
                        <h5 class="list-group-item">Customer Name:  <span class="inner_style">{{($data2) ? $data2->your_name : $data->yourname}}</span></h5>
                        <h5 class="list-group-item">Customer Signature: <span class="inner_style" style=" font-family: 'Cedarville Cursive', cursive;
                        font-size: 1.8em;">{{($data2) ? $data2->signature : $data->signature}}</span></h5>
                        <h5 class="list-group-item">Payment Status:
                       <span class="badge badge-pill badge-default mt-2">Payment: <?php echo pay_status($data->paid_status)?></span>
                        </h5>

                        <h5 class="list-group-item">Payment Method: <span class="inner_style">
                        {{ ($data->payment_method2 == "quick_pay") ? 'Quick Pay ' : (($data->payment_method2 == "cod_cop") ? 'COD/COP ' : '') }}
                        {{ ($data->payment_type == 'cop') ? '(COD)' : (($data->payment_type == 'cop') ? '(COP)' : (($data->payment_type == 'card_cop') ? '(CARD+COP)' : (($data->payment_type == 'card_cod') ? '(CARD+COD)' : (($data->payment_type == 'card') ? '(CARD)' : (($data->payment_type == 'Bank') ? '(Bank)' : (($data->payment_type == 'Zell') ? '(Zell)' : (($data->payment_type == 'CashApp') ? '(CashApp)' : (($data->payment_type == 'PayPal') ? '(PayPal)' : (($data->payment_type == 'Cheque') ? '(Cheque)' : ''))))))))) }}
                        </span>
                        </h5>

                        <h5 class="list-group-item">Owes: <span class="inner_style">
                          {{ !empty($data->owes) ? $data->owes : 'N/A' }}  <?php echo ($data->payment_method2 == "cod_cop") ? '<span class="badge badge-warning"   onclick="window.location.href = `'.url('payment_system2').' `" style="font-size: 13px;cursor: pointer"><u>Driver To Us</u></span>' : '<span onclick="window.location.href = `'.url('payment_system2').' `" class="badge badge-info" style="font-size: 13px;cursor: pointer"><u>We to Driver</u></span>' ?>
                        </span>
                        </h5>




                        <h5 class="list-group-item">Has Card : <span class="badge @if(($credit_card + count($old)) > 0) mt-2 badge-success @else badge-warning @endif">@if(($credit_card + count($old)) > 0)  YES @else NO @endif</span></h5>
                    
                    @if($data2 && $data2->payment_status == "Paid")
                        <h5 class="list-group-item">Payment Date: : <span>{{ date("M-d-y h:i:s a",strtotime($data2->updated_at))}}</span></h5>
                    @elseif($data->pay_status != 0)
                        <h5 class="list-group-item">Payment Date: : <span>{{ date("M-d-y h:i:s a",strtotime($data->updated_at))}}</span></h5>
                     @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-white" style=" background-color:#077199 !important;">
                        <h4 class="m-0">CALL/SMS History</h4>
                    </div>
                    <div class="card-body   ">
                        <table class="table table-bordered table-sm">

                            <tbody>
                            @foreach($callhistory as $chistory)
                                <tr>
                                    <td  >
                                        <span >
                                            <?php
                                            echo $chistory->history;
                                            ?>
                                        </span>
                                        <span>
                                            NewStatus:
                                            <span class="inner_style">
                                                <?php
                                                echo get_pstatus($chistory->pstatus);
                                                ?>
                                            </span>
                                        </span>
                                        <br>
                                       <span>
                                           User:
                                           <span class="inner_style">
                                            <?php
                                            echo '  ' . get_user_name($chistory->userId);
                                            ?>
                                               </span>
                                       </span>
                                       <div class="mt-3">
                                        <?php
                                        echo  date("M,d Y h:i A",strtotime($chistory->created_at));
                                        ?>
                                       </div>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <!--
        <div class=" col-md-6">
            <div class="card">
                <div class="card-header  bg-primary text-white">
                    <h4>Status Update</h4></div>
                <div class="card-body">

                    <div class="form-group">
                        <select class="form-control status_update">
                            <option disabled selected><h4>select</h4></option>
                            <option value="inters"><h4>Intersted</h4></option>
                            <option value="ask_low"><h4>Asking Low</h4></option>
                            <option value="m_follow"><h4>More Follow</h4></option>
                        </select>
                    </div>
                    <div class="form-group status inters">
                        <h6>Comments *</h6>
                        <input class="form-control " style="height: 59px;">
                    </div>
                    <div class="form-group status ask_low">
                        <h6>Ask Price *</h6>
                        <input class="form-control " style="height: 59px;">
                    </div>
                    <div class="form-group status m_follow">
                        <h6>Comments *</h6>
                        <input class="form-control " style="height: 59px;">
                    </div>
                    <button class="btn btn bg-dark text-white w-200 float-right"
                            style="height: 50px;border: 1px solid;border-radius: 22px;">Save
                    </button>
                </div>
            </div>
        </div>
    -->
        {{--</div>--}}


        <div class="row">
            <div class="card">
                <div class="card-header bg-primary text-white w-100" style=" background-color:#077199 !important;">
                    <h4 class="m-0">ORDER UPDATE LOGS</h4></div>
                <div class="card-body">
                    <table class="table table-striped table-bordered">

                        <tbody>

                        @foreach($reports as $report)
                            <tr>
                                <td>
                                    <span class="inner_style">
                                    <?php
                                    echo get_user_name($report->userId);
                                    ?>
                                    </span>
                                    <br>
                                    <?php
                                    echo get_pstatus($report->pstatus);
                                    ?>

                                    <br>
                                    {{   date("M-d-y h:i:s a",strtotime($report->created_at)) }}
                                    <br>

                                </td>

                            </tr>
                        @endforeach


                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="card">
                <div class="card-header bg-primary text-white w-100" style=" background-color:#077199 !important;">
                    <h4 class="m-0">ORDER HISTORY</h4></div>

                <div class="card-body">
                    <div class="table-responsive">
                       <table class="table table-bordered text-nowrap dataTable no-footer" id="example1" role="grid"
                              aria-describedby="example1_info" >
                           <thead>
                                <tr>
                                    <th>Column Name</th>
                                    <th class="wrapping text-wrap">Old Value</th>
                                    <th class="wrapping text-wrap">New Value</th>
                                    <th>User Name</th>
                                    <th>Date</th>
                                </tr>
                           </thead>
                           <tbody>
                                @foreach($order_history as $orderhistory)

                                    <tr>
                                        <td>

                                            {{$orderhistory->namee}}
                                        </td>
                                        <td class="wrapping text-wrap">


                                            @if($orderhistory->namee=='Transport')
                                                @if($orderhistory->old_value==1)
                                                    Open
                                                @else
                                                    Enclosed
                                                @endif
                                            @elseif($orderhistory->namee=='Vehicle Condition')
                                                @if($orderhistory->old_value==1)
                                                    Running
                                                @else
                                                    Not Running
                                                @endif
                                             @elseif($orderhistory->namee=='Origin Terminal')
                                                {{get_oterminal_name($orderhistory->old_value)}}
                                             @elseif($orderhistory->namee=='Destination Terminal')
                                                {{get_dterminal_name($orderhistory->old_value)}}
                                             @else
                                                {{$orderhistory->old_value}}
                                             @endif


                                        </td>
                                        <td class="wrapping text-wrap">
                                           @if($orderhistory->namee=='Transport')
                                                @if($orderhistory->new_value==1)
                                                  Open
                                                @else
                                                   Enclosed
                                                @endif
                                            @elseif($orderhistory->namee=='Vehicle Condition')
                                                @if($orderhistory->new_value==1)
                                                    Running
                                                @else
                                                    Not Running
                                                @endif
                                            @elseif($orderhistory->namee=='Origin Terminal')
                                                {{get_oterminal_name($orderhistory->new_value)}}
                                            @elseif($orderhistory->namee=='Destination Terminal')
                                                {{get_dterminal_name($orderhistory->new_value)}}
                                            @else
                                                 {{$orderhistory->new_value}}
                                            @endif


                                        </td>
                                        <td>
                                            <?php
                                            echo get_user_name($orderhistory->user_id);
                                            ?>
                                        </td>
                                        <td>
                                            {{   date("M-d-y h:i:s a",strtotime($orderhistory->created_at)) }}
                                        </td>
                                    </tr>
                                @endforeach
                           </tbody>



                    </table>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <div class="modal" id="modaldemo4">
        <div class="modal-dialog modal-dialog-centered text-center " role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-body text-center p-4">
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                                aria-hidden="true">&times;</span></button>
                    <i class="fe fe-check-circle fs-100 text-success lh-1 mb-5 d-inline-block"></i>
                    <h4 class="text-success tx-semibold" id="success"></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modaldemo5">
        <div class="modal-dialog modal-dialog-centered text-center" role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-body text-center p-4">
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                                aria-hidden="true">&times;</span></button>
                    <i class="fe fe-x-circle fs-100 text-danger lh-1 mb-5 d-inline-block"></i>
                    <h4 class="text-danger" id="not_success"></h4>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('extraScript')
    <script>
        $(function () {
            $('.status').hide();
            $('.status_update').change(function () {
                $('.status').hide();
                $('.' + $(this).val()).show();
            })
        })
        $('#reportmodal').on('show.bs.modal', function (e) {

            //get data-id attribute of the clicked element
            //var orderId = $(e.relatedTarget).data('book-id');
            var orderId = $('#orderid').val();


            //populate the textbox
            var encryptvuserid = btoa({{Auth::user()->id}});
            var encryptvoderid = btoa(orderId);
            var linkv = "{{url('/email_order/')}}" + '/' + encryptvoderid + '/' + encryptvuserid;
            $(e.currentTarget).find('input[name="orderid"]').val(orderId);
            $(e.currentTarget).find('input[name="link"]').val(linkv);
        });


        $("#form").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: "/send_order_link",
                type: "POST",
                data: new FormData(this),

                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {

                },
                success: function (data) {

                    let test = data.toString();

                    let test2 = $.trim(test);
                    let text = "SUCCESS";
                    if (test2 == text) {

                        //$('#success').html(data);
                        $('#modaldemo4').modal('show');
                        $('#reportmodal').modal('hide');

                    } else {
                        //$('#not_success').html(data);
                        $('#modaldemo5').modal('show');
                    }
                },
                error: function (e) {
                    $("#err").html(e).fadeIn();
                }
            });
        }));







    </script>







@endsection

