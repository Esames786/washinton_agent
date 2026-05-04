@extends('layouts.print_layout')

@section('template_title')
    PAYMENT
@endsection
@include('partials.mainsite_pages.return_function2')
@section('content')
    <style>
        body {
            scroll-behavior: smooth;
        }

        @import url('https://fonts.googleapis.com/css?family=Open+Sans|Rock+Salt|Shadows+Into+Light|Cedarville+Cursive');

        @import url('https://fonts.googleapis.com/css?family=Open+Sans|Rock+Salt|Shadows+Into+Light|Cedarville+Cursive');

        .border {
            border: 1px solid #d5cece !important;
        }

        .highlight {
            background: black;
        }

        .card {
            position: relative;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid #fff;
            border-radius: .25rem;
        }

        .mainTitle {
            width: 100%;
            float: left;
            padding: 10px;
            margin-bottom: 10px;
        }

        .text-justify {
            text-align: justify !important;
        }

        ul,
        ol {
            margin: 0px;
            padding: 0px;
            list-style-type: none;
        }

        .stepContainer span {
            font-size: 25px;
            width: 40px;
            background: #FF9800;
            padding: 10px;
            border-radius: 50%;
            margin-right: 2%;
            float: left;
            line-height: 20px;
            text-align: center;
            color: white;
            font-weight: 600;
        }

        .header_cover {
            width: 100%;
            height: 250px;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center center;
        }

        .header_heading {

            background-color: rgba(0, 0, 0, 0.4);
            /* Black w/opacity/see-through */
            color: white;
            font-weight: bold;
            position: absolute;
            top: 12pc;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 2;
            width: 100%;
            padding: 20px;
            text-align: center;
        }

        .sign1 {
            padding: 19px 15px 12px 38px;
            font-size: 30px;
            line-height: 30px;
            color: #000;
            background: #fff;
            border: 1px solid #000;
            font-family: 'Shadows Into Light', cursive;
            font-weight: bold;
        }

        .sign2 {
            padding: 19px 15px 12px 38px;
            font-size: 30px;
            line-height: 30px;
            color: #000;
            background: #fff;
            border: 1px solid #000;
            font-family: 'Rock Salt', cursive;
            font-weight: bold;

        }

        .sign3 {
            padding: 19px 15px 12px 38px;
            font-size: 30px;
            line-height: 30px;
            color: #000;
            background: #fff;
            border: 1px solid #000;
            font-family: 'Jazz LET, fantasy';
            font-weight: bold;
        }

        .sign4 {
            padding: 19px 15px 12px 38px;
            font-size: 30px;
            line-height: 30px;
            color: #000;
            background: #fff;
            border: 1px solid #000;
            font-family: 'prestige';
            font-size: 36px;
            font-weight: bold;
        }

        .sign1:hover,
        .sign2:hover,
        .sign3:hover,
        .sign4:hover {
            background-color: black;
            color: white;
        }

        #signShw1,
        #signShw2,
        #signShw3,
        #signShw4 {
            width: 95%;

        }


        .checkedClass {
            background-color: black;
        }

        input[type=radio] {
            display: none;
        }

        .heading {
            line-height: 66px;
            font-size: 36px;
            font-family: math;
            font-weight: 900;
            float: left;
        }

        .subhead {
            float: right;
            margin-top: 15px;
            font-size: 23px;
            font-family: cursive;
        }

        .bg-secondary {
            background-color: #080808 !important;
        }

        .img {
            background-image: url(https://www.Autotransportgo.com/img/roadtransport.jpg);
            filter: drop-shadow(10px 10px 10px grey);
            width: 100%;
            height: 250px;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center center;
            filter: blur(2px);
            -webkit-filter: blur(8px);


        }

        strong,
        h5 {
            font-family: "Luminari", "fantasy";
        }

        input,
        select,
        textarea {
            border: 1px solid #b0a6e0 !important;
        }

        body {
            /*background-image: linear-gradient(to right, rgb(109, 213, 250), rgb(255, 255, 255), rgb(41, 128, 185)) !important;*/
            box-shadow: 2px 2px #9E9E9E !important;
            background-color: white;
        }

        .card-header {
            color: black !important;
            justify-content: center !important;
            font-weight: 800 !important;
            font-size: 25px !important;
            border: 1px solid #ede0e0 !important;
            background-color: #e9e9eb !important;

        }

        .card-body {
            border: 1px solid #ede0e0 !important;
        }

        .stepTitle {
            position: relative;
            top: 12px;
        }

        .app-content .side-app {
            padding: 0px !important;
        }
    </style>
    <?php
    //whether ip is from share internet
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    }
    //whether ip is from proxy
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    //whether ip is from remote address
    else {
        $ip_address = $_SERVER['REMOTE_ADDR'];
    }

    ?>

    <div class="container " style=" margin-top: 0px; ">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"
                        style="    border-bottom-color:transparent;background-color:#8fc445 !important; color:#fff !important;">
                        <div class=" mb-0 w-100"><strong class="heading">Book Order #{{ $data->id }} </strong>

                            <p class="subhead">Your IP address - {{ $ip_address }}</p>

                        </div>
                    </div>
                    <div class="card-body">

                        <form action="/order_payment" method="post" autocomplete="off" class="needs-validation">
                            @csrf
                            <input type="hidden" name="id" value="{{ $data->id }}">
                            <input type="hidden" name="userid" value="{{ $userid }}">
                            <input type="hidden" name="ip" value="{{ $ip_address }}">
                            <input type="hidden" name="ipcity" value="">
                            <input type="hidden" name="ipregion" value="">
                            <input type="hidden" name="ip_details" value="">
                            <input type="hidden" name="ip_details" value="">
                            <input type="hidden" name="ipcountry" value="">
                            <input type="hidden" name="iploc" value="">
                            <input type="hidden" name="ippostal" value="">
                            <input type="hidden" name="browser" value=" ">
                            <input type="hidden" name="platform" value="">
                            {{-- Token-derived fields: pre-selected transport type and price --}}
                            <input type="hidden" name="token_transport_type" value="{{ $tokenType ?? '' }}">
                            <input type="hidden" name="token_amount" value="{{ $tokenAmount ?? '' }}">

                            @if (Auth::check())
                                <input type="hidden" name="pay_by_user" value="1" />
                            @else
                                <input type="hidden" name="pay_by_customer" value="1" />
                            @endif
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card" style="border: 0px">
                                        <div class="card-body">
                                            <div class="col-sm-12 border">
                                                <div style="margin-left: -16px;margin-right: -16px;
                                        border-width: 0px 0px 1px 0px !important;
                                        background-color:#8fc445 !important; color:#fff !important;"
                                                    class="card-header bg-secondary text-white font-weight-bold">
                                                    SUMMARY
                                                </div>


                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <br>
                                                        <strong>Order Information</strong>
                                                        <table class="table customtable">
                                                            @php
                                                                $vehiclearray = explode('*^-', $data->ymk);
                                                                $vin_num = explode('*^', $data->vin_num);
                                                                $condition1 = explode('*^', $data->condition);
                                                                $transport = explode('*^', $data->transport);
                                                            @endphp
                                                            @php
                                                                if ($data->freight) {
                                                                    # code...
                                                                    $frieght_class = $data->freight->frieght_class;
                                                                    $equipment_type = implode(
                                                                        ', ',
                                                                        explode('^*', $data->freight->equipment_type),
                                                                    );
                                                                    $trailer_specification =
                                                                        $data->freight->trailer_specification;
                                                                    $ex_pickup_date = $data->freight->ex_pickup_date;
                                                                    $ex_pickup_time = $data->freight->ex_pickup_time;
                                                                    $ex_delivery_date =
                                                                        $data->freight->ex_delivery_date;
                                                                    $ex_delivery_time =
                                                                        $data->freight->ex_delivery_time;
                                                                    $commodity_detail =
                                                                        $data->freight->commodity_detail;
                                                                    $commodity_unit = $data->freight->commodity_unit;
                                                                    $total_weight_lb = $data->freight->total_weight_lbs;
                                                                    $pick_up_service = implode(
                                                                        ', ',
                                                                        explode('^*', $data->freight->pick_up_services),
                                                                    );
                                                                    $deliver_service = implode(
                                                                        ', ',
                                                                        explode('^*', $data->freight->deliver_services),
                                                                    );
                                                                    $shipment_prefences =
                                                                        $data->freight->shipment_prefences;
                                                                    $protect_from_freezing =
                                                                        $data->freight->protect_from_freezing;
                                                                    $sort_segregate = $data->freight->sort_segregate;
                                                                    $blind_shipment = $data->freight->blind_shipment;
                                                                    $stackable = $data->freight->stackable;
                                                                    $hazardous = $data->freight->hazardous;
                                                                    $handling_unit = $data->freight->handling_unit;
                                                                }
                                                            @endphp
                                                            <tbody>
                                                                <tr>
                                                                    <td>Order#</td>
                                                                    <td class="font-weight-bold">{{ $data->id }}</td>
                                                                </tr>
                                                                @if ($data->car_type != 3)
                                                                    <tr>
                                                                        <td>Vehicle Name</td>
                                                                        <td class="font-weight-bold">
                                                                            @foreach ($vehiclearray as $key => $vhicle)
                                                                                {{ $vhicle }}
                                                                                (Vin:{{ isset($vin_num[$key]) ? (!empty(trim($vin_num[$key])) ? $vin_num[$key] : 'N/A') : 'N/A' }})
                                                                                <br>
                                                                            @endforeach

                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td>Condition</td>
                                                                        <td class="font-weight-bold">
                                                                            @foreach ($condition1 as $val2)
                                                                                {{ '(' . get_condtion($val2) . '),' }}
                                                                            @endforeach
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td>Transport</td>
                                                                        <td class="font-weight-bold">
                                                                            @if(!empty($tokenType))
                                                                                {{ ucfirst($tokenType) }} Transport
                                                                            @else
                                                                                @foreach ($transport as $val3)
                                                                                    {{ '(' . get_cartype($val3) . '),' }}
                                                                                @endforeach
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @else
                                                                    <tr>
                                                                        <td>Commodity details</td>
                                                                        <td class="font-weight-bold">
                                                                            {{ $commodity_detail }}
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td>Equipment type</td>
                                                                        <td class="font-weight-bold">
                                                                            {{ $equipment_type }}
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td>Trailer Specifications</td>
                                                                        <td class="font-weight-bold">
                                                                            {{ $trailer_specification }}
                                                                        </td>
                                                                    </tr>
                                                                @endif

                                                                <tr>
                                                                    <td>Pickup Location</td>
                                                                    <td class="font-weight-bold">{{ $data->originzsc }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Delivery Location</td>
                                                                    <td class="font-weight-bold">
                                                                        {{ $data->destinationzsc }}</td>
                                                                </tr>

                                                            </tbody>
                                                        </table>

                                                    </div>
                                                    <div class="col-sm-6">
                                                        <br>

                                                        <strong>Pricing Information</strong>


                                                        <table class="table customtable">
                                                            <tbody>
                                                                <tr>
                                                                    <td>Booking Price</td>
                                                                    <td class="font-weight-bold text-right">
                                                                        ${{ !empty($tokenAmount) ? number_format($tokenAmount, 2) : $data->payment }}
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                $coupon_price = 0;
                                                                if (isset($data->coupon_id)) {
                                                                    $coupon = \App\Coupon::find($data->coupon_id);
                                                                    if (isset($coupon->id)) {
                                                                        $coupon_price = $coupon->coupon_price ?? 0;
                                                                    }
                                                                }
                                                                ?>
                                                                @if (!empty($data->payment))
                                                                    @if ($coupon_price > 0)
                                                                        <tr>
                                                                            <td>Coupon Price</td>
                                                                            <td class="font-weight-bold text-right"> -
                                                                                ${{ $coupon_price }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Remaining Price</td>
                                                                            <td class="font-weight-bold text-right">
                                                                                ${{ $data->payment - $coupon_price }}</td>
                                                                        </tr>
                                                                    @endif
                                                                @endif
                                                                <tr>
                                                                    <td>Deposit</td>
                                                                    <td class="font-weight-bold text-right">
                                                                        {{ isset($data->deposit_amount) ? '$' . $data->deposit_amount : '$0' }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Balance Amount</td>
                                                                    <td class="font-weight-bold text-right">
                                                                        {{ isset($data->balance) ? '$' . $data->balance : '$0' }}
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if ($data->car_type == 3)
                                    <div class="col-lg-12">
                                        <div class="card" style="border: 0px">
                                            <div class="card-body">
                                                <div class="col-sm-12 border">
                                                    <div style="margin-left: -16px;margin-right: -16px;
                                        border-width: 0px 0px 1px 0px !important;
                                        background-color:#8fc445 !important; color:#fff !important;"
                                                        class="card-header bg-secondary text-white font-weight-bold">
                                                        Freight Detail
                                                    </div>


                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <strong>Order Information</strong>
                                                            <table class="table customtable">

                                                                <tbody>
                                                                    <tr>
                                                                        <td>Freight Class</td>
                                                                        <td class="font-weight-bold">{{ $frieght_class }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Equipment Type</td>
                                                                        <td class="font-weight-bold">{{ $equipment_type }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Trailer Specification</td>
                                                                        <td class="font-weight-bold">
                                                                            {{ $trailer_specification }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Expected Pickup Date</td>
                                                                        <td class="font-weight-bold">{{ $ex_pickup_date }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Expected Pickup Time</td>
                                                                        <td class="font-weight-bold">{{ $ex_pickup_time }}
                                                                        </td>
                                                                    </tr>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <table class="table customtable">
                                                                <tbody>
                                                                    <tr>
                                                                        <td>Expected Delivery Date</td>
                                                                        <td class="font-weight-bold">
                                                                            {{ $ex_delivery_date }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Expected Delivery Time</td>
                                                                        <td class="font-weight-bold">
                                                                            {{ $ex_delivery_time }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Commodity Detail</td>
                                                                        <td class="font-weight-bold">
                                                                            {{ $commodity_detail }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Commodity Unit</td>
                                                                        <td class="font-weight-bold">{{ $commodity_unit }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Total Weight (lbs)</td>
                                                                        <td class="font-weight-bold">{{ $total_weight_lb }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Pickup Services</td>
                                                                        <td class="font-weight-bold">
                                                                            {{ $pick_up_service }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Delivery Services</td>
                                                                        <td class="font-weight-bold">
                                                                            {{ $deliver_service }}</td>
                                                                    </tr>
                                                                    @if ($protect_from_freezing != 0)
                                                                        <tr>
                                                                            <td>Protect From Freezing</td>
                                                                            <td class="font-weight-bold">Yes</td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($sort_segregate != 0)
                                                                        <tr>
                                                                            <td>Sort Segregate</td>
                                                                            <td class="font-weight-bold">Yes</td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($blind_shipment != 0)
                                                                        <tr>
                                                                            <td>Blind Shipment</td>
                                                                            <td class="font-weight-bold">Yes</td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($stackable != 0)
                                                                        <tr>
                                                                            <td>Stackable</td>
                                                                            <td class="font-weight-bold">Yes</td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($hazardous != 0)
                                                                        <tr>
                                                                            <td>Hazardous</td>
                                                                            <td class="font-weight-bold">Yes</td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($handling_unit != 0)
                                                                        <tr>
                                                                            <td>Handling Unit</td>
                                                                            <td class="font-weight-bold">Yes</td>
                                                                        </tr>
                                                                    @endif
                                                                    {{-- <tr>
                                                                <td>Shipment Preferences</td>
                                                                <td class="font-weight-bold">{{ $shipment_prefences }}</td>
                                                            </tr> --}}
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="text-muted text-right">
                                <strong>Note: </strong>Please fill out all the fields that are required (*).
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="mainTitle">
                                        <div class="stepContainer">
                                            <span>1</span>
                                        </div>
                                        <div class="stepTitle">
                                            <h5>Customer information
                                                <a href="javascript:void(0);" data-toggle="tooltip"
                                                    data-placement="right" title=""
                                                    data-original-title="
                                                Customer information is the contact information of the person placing the order. This may not necessarily be the same information as the pickup and delivery location.
                                         ">
                                                    <i class="fa fa-question-circle" aria-hidden="true"></i>
                                                </a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="@if ($data->car_type != 3) col-lg-6 @else col-lg-12 @endif col-sm-12">
                                    <div class="card-header font-weight-bold"
                                        style="background-color:#8fc445 !important; color:#fff !important;">
                                        Customer Information
                                    </div>

                                    <div class="card-body border">

                                        <div class="form-group">
                                            <label for="name"><strong>Full Name</strong><span class="text-danger">
                                                    *</span></label>
                                            <input autocomplete="nope" type="text" class="form-control"
                                                id="name" name="name" placeholder="Enter Name" required=""
                                                value="{{ $data->oname }}">

                                            <div class="invalid-feedback">
                                                This field is required.
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="phone"><strong>Phone #</strong><span class="text-danger">
                                                        *</span></label>
                                                <input autocomplete="nope" type="text" class="form-control"
                                                    maxlength="15" id="phone" name="phone"
                                                    placeholder="Enter Phone #" required=""
                                                    value="{{ $data->main_ph }}">

                                                <div class="invalid-feedback">
                                                    This field is required.
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="phone2"><strong>Second Phone #</strong></label>
                                                <input autocomplete="nope" type="text" value=""
                                                    class="form-control" id="phone2" name="phone2" maxlength="15"
                                                    placeholder="Enter Second Phone #">
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label for="address"><strong>Address</strong><span class="text-danger">
                                                    *</span></label>
                                            <input autocomplete="nope" type="text" class="form-control"
                                                id="address" name="address" placeholder="Enter Address" required=""
                                                value="{{ $data->oaddress }}">

                                            <div class="invalid-feedback">
                                                This field is required.
                                            </div>
                                        </div>



                                        <div id="zip">
                                            <div class="form-group">
                                                <label class="form-label">Zip Code
                                                    <span class="text-danger"> *</span>
                                                </label>
                                                <input type="text" id="o_zip1" class="form-control "
                                                    maxlength="11" name="o_zip1"
                                                    @if ($data->originzsc) readonly @endif
                                                    value="{{ $data->originzsc }}" placeholder="ZIP CODE" required />
                                            </div>
                                        </div>



                                        <div class="form-group">
                                            <label for="email"><strong>Email Address</strong>
                                                <span class="text-danger"> *</span>
                                            </label>
                                            <input autocomplete="nope" type="email" class="form-control"
                                                id="email" name="email" placeholder="Enter Email Address"
                                                required="" value="{{ $data->oemail }}">

                                            <div class="invalid-feedback">
                                                This field is required.
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-lg-6 col-sm-12"
                                    @if ($data->car_type == 3) style="display: none" @endif>
                                    <div class="card-header  font-weight-bold"
                                        style="background-color:#8fc445 !important; color:#fff !important;">
                                        Vehicle Information
                                    </div>

                                    <div class="card-body border">

                                        <div class="form-group">
                                            <label for="carrier"><strong>Carrier Type</strong></label>
                                            <input autocomplete="nope" type="text" class="form-control"
                                                value="@foreach ($transport as $val3){{ '(' . get_cartype($val3) . '),' }} @endforeach"
                                                disabled="">
                                        </div>


                                        <div class="form-group">
                                            <label for="year"><strong>
                                                    Vehicle Name</strong></label>
                                            @php
                                                $vehiclearray2 = explode('*^', $data->ymk);
                                                $vehiclearraycondition = explode('*^', $data->condition);
                                            @endphp
                                            <input type="text" class="form-control"
                                                value="@foreach ($vehiclearray2 as $veh) {{ $veh }} @endforeach"
                                                disabled="">
                                        </div>

                                        <div class="form-group">
                                            <label for="make"><strong>Vehicle Runs</strong></label>
                                            <input type="text" class="form-control"
                                                value="@foreach ($vehiclearraycondition as $condition) @if ($condition == 1) Running @else Not Running @endif @endforeach"
                                                disabled="">
                                        </div>

                                        <div class="form-group">
                                            <label for="auction"><strong>Is it in Auction?</strong><span
                                                    class="text-danger"> *</span></label>
                                            <select name="auction" id="auction" class="form-control select2" required>
                                                <option value="" selected="">Select</option>
                                                <option @if (!empty($data->oauction)) selected @endif value="yes">
                                                    Yes</option>
                                                <option @if (empty($data->oauction)) selected @endif value="no">
                                                    No</option>
                                            </select>

                                            <div class="invalid-feedback">
                                                This field is required.
                                            </div>
                                        </div>

                                        <div id="auctionYes">
                                            @if (!empty($data->oauction))
                                                <div class="form-group">
                                                    <label for="auction_name"><strong>Auction Name</strong>
                                                        <span class="text-danger"> *</span></label>
                                                    </label>
                                                    <div class="controls position-relative has-icon-left">
                                                        <input autocomplete="nope" type="text" name="auction_name"
                                                            required id="auction_name" class="form-control"
                                                            value="{{ $data->oauction }}"
                                                            placeholder="Enter Auction Name">
                                                        <div class="form-control-position">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="buyer_num"><strong>Buyer/Lot/StockNumber</strong>
                                                        <span class="text-danger"> *</span></label>
                                                    </label>
                                                    <div class="controls position-relative has-icon-left">
                                                        <input autocomplete="nope" type="text" name="buyer_num"
                                                            id="buyer_num" required class="form-control"
                                                            value="{{ $data->obuyer_no }}"
                                                            placeholder="Enter Buyer/Lot/Stock Number">
                                                        <div class="form-control-position">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>

                                        <div class="form-group">
                                            <label for="vin"><strong>Last 8 of Vin#</strong>
                                                <span class="text-danger"> *</span></label>

                                            <div class="controls position-relative has-icon-left">
                                                <input autocomplete="nope" type="text" name="vin" id="vin"
                                                    value="{{ $data->vin_no }}" required class="form-control"
                                                    maxlength="8" placeholder="Enter Last 8 of Vin#">

                                                <div class="form-control-position">
                                                    {{-- <i class="la la-edit"></i> --}}
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label for="vkey"><strong>Key</strong>
                                                <span class="text-danger"> *</span>
                                            </label>
                                            <select name="vkey" id="vkey" class="form-control" required="">
                                                <option @if ($data->key_has == 'yes') selected @endif value="yes">
                                                    Yes</option>
                                                <option @if ($data->key_has == 'no') selected @endif value="no">
                                                    No</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="vehicleDate"><strong>Vehicle First Available Date</strong><span
                                                    class="text-danger"> *</span></label>
                                            <input type="date" autocomplete="nope" required=""
                                                data-date-format="dd/mm/yyyy" id="datepicker" class="form-control"
                                                name="vehicledate" placeholder="Vehicle First Available Date"
                                                value="{{ $data->vehicle_available_date }}">

                                            <div class="invalid-feedback">
                                                This field is required.
                                            </div>
                                        </div>


                                    </div>
                                </div>

                            </div>

                            <div class="row mt-5">
                                <div class="col-12">
                                    <div class="mainTitle">
                                        <div class="stepContainer">
                                            <span>2</span>
                                        </div>
                                        <div class="stepTitle">
                                            <h5>Location Details
                                                <a href="javascript:void(0);" data-toggle="tooltip"
                                                    data-placement="right" title=""
                                                    data-original-title="
                                                Complete the following items. Enter as many contact phone number as possible be sure to select the vehicle condition, available date, and any added service.
                                                ">
                                                    <i class="fa fa-question-circle" aria-hidden="true"></i>
                                                </a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="card-header  font-weight-bold"
                                        style="background-color:#8fc445 !important; color:#fff !important;">
                                        Origin Information
                                    </div>

                                    <div class="card-body border">

                                        <div class="form-group">
                                            <label for="oname"><strong>Contact Name</strong><span class="text-danger">
                                                    *</span></label>
                                            <input autocomplete="nope" type="text" class="form-control"
                                                id="oname" name="oname" placeholder="Enter Contact Name"
                                                required="" value="{{ $data->oname }}">

                                            <div class="invalid-feedback">
                                                This field is required.
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="oemail"><strong>Email Address</strong><span class="text-danger">
                                                    *</span></label>
                                            <input autocomplete="nope" type="email" class="form-control"
                                                id="oemail" name="oemail" placeholder="Enter Email Address"
                                                required="" value="{{ $data->oemail }}">

                                            <div class="invalid-feedback">
                                                This field is required.
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="ophone"><strong>Phone #</strong><span class="text-danger">
                                                        *</span></label>
                                                <input autocomplete="nope" type="text" class="form-control"
                                                    id="ophone" name="ophone" placeholder="Enter Phone #"
                                                    required="" value="{{ $data->main_ph }}">

                                                <div class="invalid-feedback">
                                                    This field is required.
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="ophone2"><strong>Second Phone #</strong></label>
                                                <input autocomplete="nope" type="text" class="form-control"
                                                    id="ophone2" name="ophone2" placeholder="Enter Second Phone #"
                                                    value="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="oaddress"><strong>Street Address</strong><span
                                                    class="text-danger"> *</span></label>
                                            <input autocomplete="nope" type="text" required=""
                                                class="form-control" id="oaddress" name="oaddress"
                                                placeholder="Enter Street Address" value="{{ $data->oaddress }}">

                                            <div class="invalid-feedback">
                                                This field is required.
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="state"><strong>City, State, Zip</strong><span
                                                    class="text-danger"> *</span></label>
                                            <input type="text" class="form-control" required=""
                                                value="{{ $data->originzsc }}"
                                                @if ($data->originzsc) disabled="" @endif>

                                            <div class="invalid-feedback">
                                                This field is required.
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="card-header  font-weight-bold"
                                        style="background-color:#8fc445 !important; color:#fff !important;">
                                        Destination Information
                                    </div>

                                    <div class="card-body border">

                                        <div class="form-group">
                                            <label for="dname"><strong>Contact Name</strong><span class="text-danger">
                                                    *</span></label>
                                            <input autocomplete="nope" type="text" class="form-control"
                                                id="dname" name="dname" placeholder="Enter Contact Name"
                                                required="" value="{{ $data->dname }}">

                                            <div class="invalid-feedback">
                                                This field is required.
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="demail"><strong>Email Address</strong>
                                                <span class="text-danger"> *</span>
                                            </label>
                                            <input autocomplete="nope" type="email" class="form-control"
                                                id="demail" name="demail" required placeholder="Enter Email Address"
                                                value="{{ $data->demail }}">
                                        </div>
                                        @php
                                            $dphone1 = '';
                                            $dphone2 = '';
                                            $vehiclearray = explode('*^', $data->dphone);
                                            if (count($vehiclearray) > 0) {
                                                $dphone1 = $vehiclearray[0];
                                            }
                                            if (count($vehiclearray) > 1) {
                                                $dphone2 = $vehiclearray[1];
                                            }
                                        @endphp
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="dphone"><strong>Phone #</strong><span class="text-danger">
                                                        *</span></label>
                                                <input autocomplete="nope" type="text" class="form-control"
                                                    id="dphone" name="dphone" placeholder="Enter Phone #"
                                                    required="" value="{{ $dphone1 }}">

                                                <div class="invalid-feedback">
                                                    This field is required.
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="dphone2"><strong>Second Phone #</strong></label>
                                                <input autocomplete="nope" type="text" class="form-control"
                                                    id="dphone2" name="dphone2" placeholder="Enter Second Phone #"
                                                    value="{{ $dphone2 }}">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="daddress"><strong>Street Address</strong><span
                                                    class="text-danger"> *</span></label>
                                            <input autocomplete="nope" required="" type="text"
                                                class="form-control" id="daddress" name="daddress"
                                                placeholder="Enter Street Address" value="{{ $data->daddress }}">

                                            <div class="invalid-feedback">
                                                This field is required.
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="state"><strong>City, State, Zip</strong><span
                                                    class="text-danger"> *</span></label>
                                            <input type="text" class="form-control" required=""
                                                value="{{ $data->destinationzsc }}"
                                                @if ($data->destinationzsc) disabled="" @endif>

                                            <div class="invalid-feedback">
                                                This field is required.
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="row mt-5">
                                <div class="col-lg-12 col-sm-12">
                                    <div class="card-header  font-weight-bold"
                                        style="background-color:#8fc445 !important; color:#fff !important;">
                                        Additional Vehicle Information (Optional)
                                    </div>

                                    <div class="card-body border">

                                        <div class="form-group">
                                            <textarea name="additional_2" id="additional_2" cols="30" rows="5" class="form-control"> {{ $data->additional_2 }}</textarea>
                                        </div>

                                    </div>

                                </div>
                            </div>

                            <div class="row mt-5">
                                <div class="col-12">

                                    <div class="mainTitle">
                                        <div class="stepContainer">
                                            <span>3</span>
                                        </div>
                                        <div class="stepTitle">
                                            <h5>Confirm Order</h5>
                                        </div>
                                    </div>

                                    <div id="accordion">

                                        <div class="card">
                                            <div class="card-header" id="headingTwo">
                                                <h3 class="mb-0">
                                                    <button type="button" class="btn btn-link" data-toggle="collapse"
                                                        data-target="#collapseTwo" aria-expanded="true"
                                                        aria-controls="collapseTwo">
                                                        [+] Terms &amp; Conditions
                                                    </button>
                                                </h3>
                                            </div>
                                            <div id="collapseTwo" style="display: none" class="collapse "
                                                aria-labelledby="headingTwo" data-parent="#accordion">
                                                <div class="card-body">

                                                    <ol>
                                                        <li class="text-justify">
                                                            <strong>Acceptance of Use:</strong> By using or accessing any of our services, websites, or features, you agree to adhere to the terms and conditions outlined below.
                                                            If you do not accept any of these terms, you will not be permitted to use our services, website, or features.
                                                        </li><br>

                                                        <li class="text-justify">
                                                            <strong>Service Agreement and Pickup/Delivery Schedule:</strong> Ship A1 Transport is licensed and bonded by the FMCSA and agrees to arrange for the shipment of vehicles described in the quotation on or about the estimated dates based on the carrier/transport schedule.
                                                            As a broker, we will designate a reliable carrier/transporter to fulfill the terms of this agreement. Ship A1 Transport does not guarantee specific pickup or delivery dates; these dates are estimates only.
                                                            Ship A1 Transport and the designated carrier are not responsible for delays of any kind. Once a reliable carrier/transporter is assigned, Ship A1 Transport fulfills its service agreement.
                                                        </li><br>

                                                        <li class="text-justify">
                                                            <strong>First-Time Booking Form and Future Orders:</strong> When a customer contacts Ship A1 Transport for the first time to avail of our services, a Booking Form must be completed and signed.
                                                            This form will serve as the official agreement for the initial order. Once this first-time booking form is completed, it will be saved and stored in our records for future reference.
                                                            For any subsequent orders or quotes made by the same customer, Ship A1 Transport will proceed directly with the booking, without requiring a new booking form.
                                                            The terms and conditions outlined in the original first-time booking form will automatically apply to all future orders made by the customer, including but not limited to any new orders placed over the phone, via email, or through any other means of communication.
                                                            After the initial order, all future bookings are governed by the terms set forth in the first-time booking form, and the customer acknowledges and agrees to the continuation of the terms from that original agreement for any future orders.
                                                        </li><br>

                                                        <li class="text-justify">
                                                            <strong>Terms and Conditions of Carrier/Transporter's Bill of Lading:</strong> This order is subject to all terms and conditions outlined in the carrier/transporter’s straight bill of lading.
                                                            Copies of the bill of lading are available from the carrier/transporter's office. After a carrier has been assigned, the bill of lading becomes the primary agreement, and the terms of that bill of lading will supersede all other agreements.
                                                        </li><br>

                                                        <li class="text-justify">
                                                            <strong>Third-Party Links:</strong> Ship A1 Transport’s website may include third-party links. We are not responsible for the privacy policies or terms and conditions of any third-party links.
                                                            We recommend reviewing the privacy policies of third-party websites before providing any personal information.
                                                        </li><br>

                                                        <li class="text-justify">
                                                            <strong>Insurance Responsibility and Claims Process:</strong> The carrier/transporter assumes primary insurance responsibility during the transit of your vehicle.
                                                            Any claims for damage must be directed to the actual carrier/transporter who transported your vehicle.
                                                            The customer agrees that Ship A1 Transport is not responsible for any damage claims. For information on the claim process, refer to the carrier/transporter’s bill of lading.
                                                            No claims or legal actions can be initiated against Ship A1 Transport. Only the carrier’s terms apply once a carrier is assigned to your order.
                                                        </li><br>

                                                        <li class="text-justify">
                                                            <strong>Exclusions from Liability:</strong> The carrier/transporter will not be responsible for damages caused by freezing of engine, leaking fluids, mechanical malfunctions, wear and tear, or items left inside the vehicle.
                                                        </li><br>

                                                        <li class="text-justify">
                                                            <strong>Customer Responsibilities for Vehicle Preparation:</strong> The customer is responsible for preparing the vehicle for transport.
                                                            This includes disarming any alarms and removing any loose parts or accessories. The customer assumes responsibility for any damages caused by parts that fall off the vehicle during transport.
                                                        </li><br>

                                                        <li class="text-justify">
                                                            <strong>Auto Rental Policy:</strong> Ship A1 Transport does not provide auto rental services for delays, damages, accidents, or unforeseen circumstances. Customers should plan accordingly.
                                                        </li><br>

                                                        <li class="text-justify">
                                                            <strong>Reposting Fee for Unavailable Vehicles:</strong> If a carrier is sent to pick up your vehicle and it is not available, a $100 reposting fee will apply to reschedule your transport.
                                                        </li><br>

                                                        <li class="text-justify">
                                                            <strong>Designation of Pickup/Delivery Agent:</strong> If the customer is unavailable, they must designate a person to act as their agent.
                                                            The agent will be notified 3–24 hours in advance for pickup.
                                                        </li><br>

                                                        <li class="text-justify">
                                                            <strong>Payment Terms and Delivery Attempts:</strong> All balances must be paid by cash or cashier’s check to the carrier.
                                                            Failure to pay may result in storage or redelivery fees at the customer’s expense.
                                                        </li><br>

                                                        <li class="text-justify">
                                                            <strong>Non-Operable Vehicle Fee:</strong> If the vehicle becomes inoperative at any point, a $150 non-operable vehicle fee will be added.
                                                        </li><br>

                                                        <li class="text-justify">
                                                            <strong>Liability Limitations and Content Verification:</strong> The carrier is not liable for non-negligent damages or for personal items left inside the vehicle.
                                                        </li><br>

                                                        <li class="text-justify">
                                                            <strong>Claims Process and Damages Exception:</strong> Damages must be noted on the inspection form at delivery.
                                                            Claims not documented will not be honored.
                                                        </li><br>

                                                        <li class="text-justify">
                                                            <strong>Legal Venue for Claims and Litigation:</strong> All legal matters will be handled in Baltimore County, Maryland, in the Superior Court.
                                                        </li><br>

                                                        <li class="text-justify">
                                                            <strong>Cancellation Policy:</strong> Cancellations must be in writing.
                                                            A $99 fee applies before a carrier is assigned; $199 after.
                                                            Orders not scheduled within 30 days qualify for full deposit refund.
                                                        </li><br>

                                                        <li class="text-justify">
                                                            <strong>Right to Refuse Service:</strong> Ship A1 Transport reserves the right to refuse service for violations, threats, or harassment.
                                                        </li><br>

                                                        <li class="text-justify">
                                                            <strong>Disclaimer:</strong> Pickup/delivery dates are estimates.
                                                            Ship A1 Transport is a broker and not responsible for carrier performance or damages.
                                                        </li><br>

                                                        <li class="text-justify">
                                                            <strong>Limitations of Liability:</strong> Ship A1 Transport is not liable for unforeseen issues such as weather or mechanical delays.
                                                        </li><br>

                                                        <li class="text-justify">
                                                            <strong>Changes to Terms &amp; Conditions:</strong> Terms may be updated anytime; continued use implies acceptance.
                                                        </li><br>

                                                        <li class="text-justify">
                                                            <strong>Force Majeure:</strong> Ship A1 Transport is not liable for delays or damages due to acts of God, disasters, strikes, or government actions.
                                                        </li><br>

                                                        <li class="text-justify">
                                                            <strong>Privacy Policy:</strong> We respect your privacy and only share personal information as necessary to complete services.
                                                        </li><br>

                                                        <li class="text-justify">
                                                            <strong>Vehicle Inspection:</strong> Pre- and post-transport inspections must be completed. Damages not recorded at delivery cannot be claimed.
                                                        </li><br>

                                                        <li class="text-justify">
                                                            <strong>Dispute Resolution:</strong> Disputes will first go through mediation, then binding arbitration per AAA rules.
                                                        </li><br>

                                                        <li class="text-justify">
                                                            <strong>Additional Fees for Remote Locations:</strong> Deliveries to remote areas may incur extra fees, disclosed beforehand in writing.
                                                        </li><br>

                                                        <li class="text-justify">
                                                            <strong>Vehicle Eligibility:</strong> Oversized or modified vehicles may be ineligible for transport; such cases will be refunded if service cannot proceed.
                                                        </li><br>

                                                        <li class="text-justify">
                                                            <strong>Customer Ownership and Documentation:</strong> The customer affirms legal ownership and must provide documentation when requested.
                                                        </li><br>

                                                        <li class="text-justify">
                                                            <strong>Shawn – 2024 Tesla Model S:</strong> Booked an Order (3 weeks ago)
                                                        </li>
                                                    </ol>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-lg-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="yourname"><strong>Your Name</strong><span class="text-danger">
                                                *</span></label>
                                        <input autocomplete="nope" type="text" class="form-control" id="yourname"
                                            name="yourname"
                                            value="{{ isset($data->orderpayment->your_name) ? $data->orderpayment->your_name : '' }}"
                                            placeholder="Enter Your Name" required>

                                        <div class="invalid-feedback">
                                            This field is required.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="signature"><strong>Your Signature</strong><span class="text-danger">
                                                *</span></label>
                                        <input autocomplete="nope" type="text" class="form-control" id="signature"
                                            name="signature" placeholder="Enter Your Signature" required>

                                        <div class="invalid-feedback">
                                            This field is required.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="signtures"></div>

                            <div class="row mt-2">
                                <div class="col-lg-12 col-sm-12">

                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="confirm"
                                                id="confirm" required="" value="1">
                                            <label class="form-check-label" for="confirm">
                                                I have read and accept the Terms &amp; Conditions for this transport.
                                                (Click the plus sign above to view.)<span class="text-danger"> *</span>
                                            </label>

                                            <div class="invalid-feedback">
                                                This field is required.
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-12 text-right">
                                    <button class="btn btn-primary btn-lg submit" onclick="window.print()">Print</button>
                                    <button type="submit" class="btn btn-primary btn-lg submit" name="nextStep">Next
                                        Step >>
                                    </button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modaldemo05">
        <div class="modal-dialog modal-dialog-centered text-center" role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-body text-center p-4">
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                    <i class="fe fe-x-circle fs-100 text-danger lh-1 mb-5 d-inline-block"></i>
                    <h4 class="text-danger">Please fill out the required fields!</h4>
                </div>
            </div>
        </div>
    </div>






@endsection

@section('extraScript')
    <script>
        @if (!empty($data->oauction))
            setTimeout(function() {

                //$('#auction').trigger('change');
            }, 500);
        @endif
    </script>

    <script>
        $(".app-sidebar").hide();
        $(".app-header").hide();
        $(".switcher-wrapper").hide();
    </script>

    <script type="text/javascript">
        $(function() {
            $("#o_zip1").autocomplete({
                source: "/get_zip"
            });
        });

        $(document).ready(function() {
            $("#signtures").click(function() {
                if ($("#signature1").is(":checked")) {
                    // do something if the checkbox is NOT checked
                    $("#first_sign").css("background-color", "black");
                    $("#first_sign").css("color", "white");


                }
                if (!$("#signature1").is(":checked")) {
                    // do something if the checkbox is NOT checked
                    $("#first_sign").css("background-color", "white");
                    $("#first_sign").css("color", "black");


                }
                if ($("#signature2").is(":checked")) {
                    // do something if the checkbox is NOT checked
                    $("#second_sign").css("background-color", "black");
                    $("#second_sign").css("color", "white");


                }
                if (!$("#signature2").is(":checked")) {
                    // do something if the checkbox is NOT checked
                    $("#second_sign").css("background-color", "white");
                    $("#second_sign").css("color", "black");


                }
                if ($("#signature3").is(":checked")) {
                    // do something if the checkbox is NOT checked
                    $("#third_sign").css("background-color", "black");
                    $("#third_sign").css("color", "white");

                }
                if (!$("#signature3").is(":checked")) {
                    // do something if the checkbox is NOT checked
                    $("#third_sign").css("background-color", "white");
                    $("#third_sign").css("color", "black");
                }

                if ($("#signature4").is(":checked")) {
                    // do something if the checkbox is NOT checked
                    $("#fourth_sign").css("background-color", "black");
                    $("#fourth_sign").css("color", "white");

                }
                if (!$("#signature4").is(":checked")) {
                    // do something if the checkbox is NOT checked
                    $("#fourth_sign").css("background-color", "white");
                    $("#fourth_sign").css("color", "black");
                }
            });

        });


        $("#s1").click(function() {
            $("#signature1").prop("checked", true);
            var checked = $(this).is(':checked');
            if (checked) {
                alert('checked');
            } else {
                alert('unchecked');
            }

        });
        $(".sign2").click(function() {
            $("#signature2").prop("checked", true);

        });
        $(".sign3").click(function() {
            $("#signature3").prop("checked", true);

        });
        $(".sign4").click(function() {
            $("#signature4").prop("checked", true);

        });

        $('.btn-link').click(function() {
            $('#collapseTwo').toggle();
        });
        $("#phone").mask("(999) 999-9999");
        $("#phone2").mask("(999) 999-9999");
        $("#ophone").mask("(999) 999-9999");
        $("#ophone2").mask("(999) 999-9999");
        $("#dphone").mask("(999) 999-9999");
        $("#dphone2").mask("(999) 999-9999");


        $("#signature").change(function() {
            var valueSign = $(this).val();
            $("#signtures").html('');
            if (valueSign) {
                $("#signtures").html(`
                        <div class="row skin skin-line">

                            <div class="col-md-6 col-sm-12 mt-2 radio_style "  id="s1">
                                <fieldset class="sign1" id="first_sign">
                                    <input required type="radio"  name="signatureShw" value="1" id="signature1">
                                    <label for="signature1"  style="font-weight: bolder;font-style: oblique" id="signShw1">${valueSign}</label>
                                </fieldset>
                            </div>
                            <div class="col-md-6 col-sm-12 mt-2 radio_style" id="s2">
                                <fieldset class="sign2" id="second_sign">
                                    <input required type="radio"  name="signatureShw" value="2" id="signature2">
                                    <label for="signature2" style="font-weight: bolder;font-style: oblique" id="signShw2">${valueSign}</label>
                                </fieldset>
                            </div>
                            <div class="col-md-6 col-sm-12 mt-2 radio_style" id="s3">
                                <fieldset class="sign3" id="third_sign">
                                    <input required type="radio"  name="signatureShw" value="3" id="signature3">
                                    <label for="signature3"  style="font-family:monsieur;font-weight: bolder;font-style: oblique"  id="signShw3">${valueSign}</label>
                                </fieldset>
                            </div>
                            <div class="col-md-6 col-sm-12 mt-2 radio_style" id="s4">
                                <fieldset class="sign4" id="fourth_sign">
                                    <input required type="radio" name="signatureShw" value="4" id="signature4">
                                    <label for="signature4" style="font-family:monospace;font-weight: bolder;font-style: oblique"  id="signShw4">${valueSign}</label>
                                </fieldset>
                            </div>

                        </div>`);
            }
        });

        $("#auction").change(function() {

            var valueAuction = $(this).val();

            $("#auctionYes").html('');

            if (valueAuction == 'yes') {
                $("#auctionYes").html(`

              <div class="form-group">
                <label for="auction_name"><strong>Auction Name</strong>
                   <span class="text-danger"> *</span></label>
                </label>
                <div class="controls position-relative has-icon-left">
                    <input autocomplete="nope" type="text" name="auction_name"
                   required id="auction_name" class="form-control" value="" placeholder="Enter Auction Name">
                    <div class="form-control-position">
                        <!--<i class="la la-newspaper-o"></i>-->
                    </div>
                </div>
              </div>

                <div class="form-group">
                    <label for="buyer_num"><strong>Buyer/Lot/Stock
                        Number</strong>
                           <span class="text-danger"> *</span></label>
                        </label>
                    <div class="controls position-relative has-icon-left">
                        <input autocomplete="nope" type="text" name="buyer_num" id="buyer_num"
                          required  class="form-control" value="" placeholder="Enter Buyer/Lot/Stock Number">
                        <div class="form-control-position">
                            <!--<i class="la la-phone"></i>-->
                        </div>
                    </div>
                </div>
                `);
                $("#auctionYes").show();
            } else {
                $("#auctionYes").hide();
                $("#auctionYes").html('');
            }
        });
    </script>
    <script>
        $(".submit").click(function(e) {
            var allinput = $("input[required]");
            var i = 0;
            $(".text-danger").remove();
            allinput.removeAttr('style');
            allinput.each(function() {
                if ($(this).val() == '') {
                    e.preventDefault();
                    // console.log($(this));
                    // $(".container").children('.alert').remove();
                    $(this).attr("style", "border-color:red!important");
                    $(this).after(`<div class="text-danger">This field is required!</div>`);
                    // $(".container").prepend(`
                //     <div class="alert bg-danger text-white alert-dismissible fade show mt-2" role="alert">
                //         <strong style="font-size:1.6rem;">Please fill out the required fields!</strong>
                //         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                //             <span aria-hidden="true">&times;</span>
                //         </button>
                //     </div>
                // `);
                    // $(".container").animate({ scrollTop: 0 }, 1000);
                    i++;
                }
            })
            if (i > 0) {
                // $('#modaldemo05').modal('show');
                alert('Please fill out the required fields!');
            }

        })
    </script>
@endsection
