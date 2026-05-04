@extends('layouts.print_layout')

@section('template_title')
    PAYMENT
@endsection
@include('partials.mainsite_pages.return_function2')
@section('content')
    <style>

        @import url('https://fonts.googleapis.com/css?family=Open+Sans|Rock+Salt|Shadows+Into+Light|Cedarville+Cursive');

        @import url('https://fonts.googleapis.com/css?family=Open+Sans|Rock+Salt|Shadows+Into+Light|Cedarville+Cursive');

        .border {
            border: 1px solid #000000 !important;
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
            border: 1px solid black;
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

        ul, ol {
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

            background-color: rgba(0, 0, 0, 0.4); /* Black w/opacity/see-through */
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

        .sign1:hover, .sign2:hover, .sign3:hover, .sign4:hover {
            background-color: black;
            color: white;
        }

        #signShw1, #signShw2, #signShw3, #signShw4 {
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

        strong, h5 {
            font-family: "Luminari", "fantasy";
        }
        input, select, textarea {
            border: 1px solid #b0a6e0 !important;
        }
        body {
            /*background-image: linear-gradient(to right, rgb(109, 213, 250), rgb(255, 255, 255), rgb(41, 128, 185)) !important;*/
            box-shadow: 2px 2px #9E9E9E !important;
            background-color: white;
        }
        .card-header{
            color: black !important;
            justify-content: center !important;
            font-weight: 800 !important;
            font-size: 25px !important;
            border: 2px solid black !important;
            background-color: #6c757d47 !important;

        }
        .card-body{
            border:2px solid black !important;
        }
        .stepTitle{
            position: relative;
            top: 12px;
        }
        .app-content .side-app {
            padding: 0px !important;
        }

    </style>
    <?php
    //whether ip is from share internet
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
    {
        $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    }
//whether ip is from proxy
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
        $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
//whether ip is from remote address
    else
    {
        $ip_address = $_SERVER['REMOTE_ADDR'];
    }

    ?>

    <div class="container " style=" margin-top: 0px; ">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header" style="    border-bottom-color:transparent;background-color:#077199 !important; color:#fff !important;">
                        <div class=" mb-0 w-100"><strong class="heading">Book Order #{{ $data->id  }} </strong>

                            <p class="subhead">Your IP address - {{ $ip_address }}</p>

                        </div>
                    </div>
                    <div class="card-body">

                        <form action="/order_payment" method="post" autocomplete="off" class="needs-validation">
                            @csrf
                            <input type="hidden" name="id" value="{{ $data->id  }}">
                            <input type="hidden" name="userid" value="{{ $userid  }}">
                            <input type="hidden" name="ip" value="{{$ip_address}}">
                            <input type="hidden" name="ipcity" value="">
                            <input type="hidden" name="ipregion" value="">
                            <input type="hidden" name="ip_details" value="">
                            <input type="hidden" name="ipcountry" value="">
                            <input type="hidden" name="iploc" value="">
                            <input type="hidden" name="ippostal" value="">
                            <input type="hidden" name="browser" value=" ">
                            <input type="hidden" name="platform" value="">

                            @if(Auth::check())
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
                                        background-color:#077199 !important; color:#fff !important;"
                                                     class="card-header bg-secondary text-white font-weight-bold">
                                                    SUMMARY
                                                </div>


                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <br>
                                                        <strong>Order Information</strong>
                                                        <table class="table customtable">
                                                            @php
                                                                $vehiclearray=explode('*^-',$data->ymk);
                                                                $vin_num=explode('*^',$data->vin_num);
                                                               $condition1 = explode('*^', $data->condition);
                                                                $transport = explode('*^', $data->transport);
                                                            @endphp
                                                            <tbody>
                                                            <tr>
                                                                <td>Order#</td>
                                                                <td class="font-weight-bold">{{ $data->id  }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Vehicle Name</td>
                                                                <td class="font-weight-bold">
                                                                    @foreach($vehiclearray as $key=>$vhicle)
                                                                        {{$vhicle}} (Vin:{{ isset($vin_num[$key]) ? (!empty(trim($vin_num[$key])) ? $vin_num[$key] : 'N/A') : 'N/A' }} ) <br>
                                                                    @endforeach

                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>Condition</td>
                                                                <td class="font-weight-bold">
                                                                    @foreach($condition1 as $val2)
                                                                        {{ "(".get_condtion($val2)."),"}}
                                                                    @endforeach
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>Transport</td>
                                                                <td class="font-weight-bold">
                                                                    @foreach($transport as $val3)
                                                                        {{ "(".get_cartype($val3)."),"}}
                                                                    @endforeach
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>Pickup Location</td>
                                                                <td class="font-weight-bold">{{ $data->originzsc  }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Delivery Location</td>
                                                                <td class="font-weight-bold">{{ $data->destinationzsc  }}</td>
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
                                                                <td class="font-weight-bold text-right">${{ $data->payment  }}</td>
                                                            </tr>
                                                            <?php
                                                                $coupon_price = 0;
                                                                if(isset($data->coupon_id))
                                                                {
                                                                    $coupon = \App\Coupon::find($data->coupon_id);
                                                                    if(isset($coupon->id))
                                                                    {
                                                                        $coupon_price = $coupon->coupon_price ?? 0;
                                                                    }
                                                                }
                                                            ?>
                                                            @if(!empty($data->payment))
                                                                @if($coupon_price > 0)
                                                                    <tr>
                                                                        <td>Coupon Price</td>
                                                                        <td class="font-weight-bold text-right"> - ${{ $coupon_price  }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Remaining Price</td>
                                                                        <td class="font-weight-bold text-right">${{$data->payment - $coupon_price}}</td>
                                                                    </tr>
                                                                @endif
                                                            @endif
                                                            <tr>
                                                                <td>Deposit</td>
                                                                <td class="font-weight-bold text-right">{{ isset($data->deposit_amount) ? '$'.$data->deposit_amount : '$0'  }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Balance Amount</td>
                                                                <td class="font-weight-bold text-right">{{ isset($data->balance) ? '$'.$data->balance : '$0'  }}</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                                   data-placement="right" title="" data-original-title="
                                                Customer information is the contact information of the person placing the order. This may not necessarily be the same information as the pickup and delivery location.
                                         "> <i class="fa fa-question-circle" aria-hidden="true"></i>
                                                </a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-lg-6 col-sm-12">
                                    <div class="card-header font-weight-bold" style="background-color:#077199 !important; color:#fff !important;">
                                        Customer Information
                                    </div>

                                    <div class="card-body border">

                                        <div class="form-group">
                                            <label for="name"><strong>Full Name</strong><span
                                                        class="text-danger"> *</span></label>
                                            <input autocomplete="nope" type="text" class="form-control" id="name"
                                                   name="name" placeholder="Enter Name" required=""
                                                   value="{{ $data->oname }}">

                                            <div class="invalid-feedback">
                                                This field is required.
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="phone"><strong>Phone #</strong><span
                                                            class="text-danger"> *</span></label>
                                                <input autocomplete="nope" type="text" class="form-control"
                                                       maxlength="15"
                                                       id="phone" name="phone" placeholder="Enter Phone #" required=""
                                                       value="{{ $data->main_ph }}">

                                                <div class="invalid-feedback">
                                                    This field is required.
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="phone2"><strong>Second Phone #</strong></label>
                                                <input autocomplete="nope" type="text" value=""
                                                       class="form-control" id="phone2" name="phone2"
                                                       maxlength="15"
                                                       placeholder="Enter Second Phone #">
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label for="address"><strong>Address</strong><span
                                                        class="text-danger"> *</span></label>
                                            <input autocomplete="nope" type="text" class="form-control" id="address"
                                                   name="address" placeholder="Enter Address" required=""
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
                                                       maxlength="11" name="o_zip1" @if($data->originzsc) readonly @endif
                                                       value="{{ $data->originzsc   }}" placeholder="ZIP CODE" required/>
                                            </div>
                                        </div>



                                        <div class="form-group">
                                            <label for="email"><strong>Email Address</strong>
                                                <span class="text-danger"> *</span>
                                            </label>
                                            <input autocomplete="nope" type="email" class="form-control" id="email"
                                                   name="email" placeholder="Enter Email Address" required=""
                                                   value="{{ $data->oemail }}">

                                            <div class="invalid-feedback">
                                                This field is required.
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-lg-6 col-sm-12">
                                    <div class="card-header  font-weight-bold" style="background-color:#077199 !important; color:#fff !important;">
                                        Vehicle Information
                                    </div>

                                    <div class="card-body border">

                                        <div class="form-group">
                                            <label for="carrier"><strong>Carrier Type</strong></label>
                                            <input autocomplete="nope" type="text" class="form-control" value="@foreach($transport as $val3){{ "(".get_cartype($val3)."),"}}@endforeach"
                                                   disabled="">
                                        </div>


                                        <div class="form-group">
                                            <label for="year"><strong>
                                                    Vehicle Name</strong></label>
                                            @php
                                                $vehiclearray2=explode('*^',$data->ymk);
                                                $vehiclearraycondition=explode('*^',$data->condition);
                                            @endphp
                                            <input type="text" class="form-control"
                                                   value="@foreach($vehiclearray2 as $veh) {{ $veh }} @endforeach"
                                                   disabled="">
                                        </div>

                                        <div class="form-group">
                                            <label for="make"><strong>Vehicle Runs</strong></label>
                                            <input type="text" class="form-control"
                                                   value="@foreach($vehiclearraycondition as $condition) @if($condition==1) Running @else Not Running @endif @endforeach"
                                                   disabled="">
                                        </div>

                                        <div class="form-group">
                                            <label for="auction"><strong>Is it in Auction?</strong><span
                                                        class="text-danger"> *</span></label>
                                            <select name="auction" id="auction" class="form-control select2" required>
                                                <option value="" selected="">Select</option>
                                                <option @if(!empty($data->oauction)) selected @endif value="yes">Yes</option>
                                                <option @if(empty($data->oauction)) selected @endif value="no">No</option>
                                            </select>

                                            <div class="invalid-feedback">
                                                This field is required.
                                            </div>
                                        </div>

                                        <div id="auctionYes"   >
                                          @if(!empty($data->oauction))

                                            <div class="form-group">
                                                <label for="auction_name"><strong>Auction Name</strong>
                                                    <span class="text-danger"> *</span></label>
                                                </label>
                                                <div class="controls position-relative has-icon-left">
                                                    <input autocomplete="nope" type="text" name="auction_name"
                                                           required id="auction_name" class="form-control" value="{{$data->oauction}}" placeholder="Enter Auction Name">
                                                    <div class="form-control-position">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="buyer_num"><strong>Buyer/Lot/StockNumber</strong>
                                                    <span class="text-danger"> *</span></label>
                                                </label>
                                                <div class="controls position-relative has-icon-left">
                                                    <input autocomplete="nope" type="text" name="buyer_num" id="buyer_num"
                                                           required  class="form-control" value="{{$data->obuyer_no}}" placeholder="Enter Buyer/Lot/Stock Number">
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
                                                <input autocomplete="nope" type="text" name="vin" id="vin" value="{{$data->vin}}"
                                                       required class="form-control"
                                                       maxlength="8"
                                                       placeholder="Enter Last 8 of Vin#">

                                                <div class="form-control-position">
                                                    {{--<i class="la la-edit"></i>--}}
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label for="vkey"><strong>Key</strong>
                                                <span class="text-danger"> *</span>
                                            </label>
                                            <select name="vkey" id="vkey" class="form-control" required="">
                                                <option @if($data->key_has=='yes') selected @endif  value="yes">Yes</option>
                                                <option @if($data->key_has=='no') selected @endif value="no">No</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="vehicleDate"><strong>Vehicle First Available Date</strong><span
                                                        class="text-danger"> *</span></label>
                                            <input type="date" autocomplete="nope" required=""
                                                   data-date-format="dd/mm/yyyy"
                                                   id="datepicker" class="form-control"
                                                   name="vehicledate" placeholder="Vehicle First Available Date"
                                                   value="{{$data->vehicle_available_date}}">

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
                                                   data-placement="right" title="" data-original-title="
                                                Complete the following items. Enter as many contact phone number as possible be sure to select the vehicle condition, available date, and any added service.
                                                "> <i class="fa fa-question-circle" aria-hidden="true"></i>
                                                </a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="card-header  font-weight-bold" style="background-color:#077199 !important; color:#fff !important;">
                                        Origin Information
                                    </div>

                                    <div class="card-body border">

                                        <div class="form-group">
                                            <label for="oname"><strong>Contact Name</strong><span
                                                        class="text-danger"> *</span></label>
                                            <input autocomplete="nope" type="text" class="form-control" id="oname"
                                                   name="oname" placeholder="Enter Contact Name" required=""
                                                   value="{{ $data->oname }}">

                                            <div class="invalid-feedback">
                                                This field is required.
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="oemail"><strong>Email Address</strong><span class="text-danger"> *</span></label>
                                            <input autocomplete="nope" type="email" class="form-control"
                                                   id="oemail" name="oemail"
                                                   placeholder="Enter Email Address" required=""
                                                   value="{{ $data->oemail }}">

                                            <div class="invalid-feedback">
                                                This field is required.
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="ophone"><strong>Phone #</strong><span
                                                            class="text-danger"> *</span></label>
                                                <input autocomplete="nope" type="text" class="form-control"
                                                       id="ophone" name="ophone"
                                                       placeholder="Enter Phone #" required=""
                                                       value="{{ $data->main_ph }}">

                                                <div class="invalid-feedback">
                                                    This field is required.
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="ophone2"><strong>Second Phone #</strong></label>
                                                <input autocomplete="nope" type="text" class="form-control" id="ophone2"
                                                       name="ophone2" placeholder="Enter Second Phone #" value="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="oaddress"><strong>Street Address</strong><span
                                                        class="text-danger"> *</span></label>
                                            <input autocomplete="nope" type="text" required=""
                                                   class="form-control" id="oaddress"
                                                   name="oaddress" placeholder="Enter Street Address"
                                                   value="{{ $data->oaddress }}">

                                            <div class="invalid-feedback">
                                                This field is required.
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="state"><strong>City, State, Zip</strong><span
                                                        class="text-danger"> *</span></label>
                                            <input type="text" class="form-control" required=""
                                                   value="{{ $data->originzsc }}" @if($data->originzsc) disabled="" @endif>

                                            <div class="invalid-feedback">
                                                This field is required.
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="card-header  font-weight-bold" style="background-color:#077199 !important; color:#fff !important;">
                                        Destination Information
                                    </div>

                                    <div class="card-body border">

                                        <div class="form-group">
                                            <label for="dname"><strong>Contact Name</strong><span
                                                        class="text-danger"> *</span></label>
                                            <input autocomplete="nope" type="text" class="form-control" id="dname"
                                                   name="dname" placeholder="Enter Contact Name" required=""
                                                   value="{{ $data->dname }}">

                                            <div class="invalid-feedback">
                                                This field is required.
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="demail"><strong>Email Address</strong>
                                                <span class="text-danger"> *</span>
                                            </label>
                                            <input autocomplete="nope" type="email" class="form-control" id="demail"
                                                   name="demail" required
                                                   placeholder="Enter Email Address" value="{{ $data->demail }}">
                                        </div>
                                        @php
                                        $dphone1="";
                                        $dphone2="";
                                        $vehiclearray=explode('*^',$data->dphone);
                                        if(count($vehiclearray)>0)
                                        {
                                        $dphone1=$vehiclearray[0];
                                        }
                                        if(count($vehiclearray)>1)
                                        {
                                        $dphone2=$vehiclearray[1];
                                        }
                                        @endphp
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="dphone"><strong>Phone #</strong><span
                                                            class="text-danger"> *</span></label>
                                                <input autocomplete="nope" type="text" class="form-control"
                                                       id="dphone" name="dphone"
                                                       placeholder="Enter Phone #" required="" value="{{ $dphone1  }}">

                                                <div class="invalid-feedback">
                                                    This field is required.
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="dphone2"><strong>Second Phone #</strong></label>
                                                <input autocomplete="nope" type="text" class="form-control" id="dphone2"
                                                       name="dphone2" placeholder="Enter Second Phone #"
                                                       value="{{ $dphone2  }}">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="daddress"><strong>Street Address</strong><span
                                                        class="text-danger"> *</span></label>
                                            <input autocomplete="nope" required="" type="text" class="form-control"
                                                   id="daddress" name="daddress" placeholder="Enter Street Address"
                                                   value="{{ $data->daddress }}">

                                            <div class="invalid-feedback">
                                                This field is required.
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="state"><strong>City, State, Zip</strong><span
                                                        class="text-danger"> *</span></label>
                                            <input type="text" class="form-control" required=""
                                                   value="{{ $data->destinationzsc }}" @if($data->destinationzsc) disabled="" @endif>

                                            <div class="invalid-feedback">
                                                This field is required.
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="row mt-5">
                                <div class="col-lg-12 col-sm-12">
                                    <div class="card-header  font-weight-bold" style="background-color:#077199 !important; color:#fff !important;">
                                        Additional Vehicle Information (Optional)
                                    </div>

                                    <div class="card-body border">

                                        <div class="form-group">
                                            <textarea name="additional_2" id="additional_2" cols="30" rows="5"
                                                      class="form-control"> {{ $data->additional_2 }}</textarea>
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
                                                 aria-labelledby="headingTwo" data-parent="#accordion" >
                                                <div class="card-body">

                                                    <ol>
                                                        <li class="text-justify">
                                                            ShipA1 Transport is licensed and bonded by the FMCSA and
                                                            does agree to arrange to have vehicle(s) described in
                                                            quotation shipped on or about the dates available depending
                                                            on the carrier/transports schedule. ShipA1 Transport will
                                                            designate a reliable carrier/transporter to fill the terms
                                                            and conditions of the agreement. ShipA1 Transport is a
                                                            broker and does not guarantee specific pickup or delivery
                                                            dates. Pickup and delivery dates are only estimating and
                                                            there are no guarantees. The carrier/transporter or ShipA1
                                                            Transport will not be held responsible for delays for any
                                                            reason. After ShipA1 Transport has confirmed scheduling with
                                                            a reliable carrier/transporter, ShipA1 Transport has
                                                            fulfilled our service agreement.
                                                        </li>
                                                        <br>
                                                        <li class="text-justify">
                                                            This order is subject to all terms and conditions of the
                                                            carrier/transporter's straight bill of lading. Copies of
                                                            which are available at the office of the
                                                            carrier/transporter. Once a carrier/transporter has been
                                                            assigned to your order, the bill of lading will then be the
                                                            only agreement in effect along with the terms and conditions
                                                            of the carrier/transporter assigned to your order.
                                                        </li>
                                                        <br>
                                                        <li class="text-justify">
                                                            The carrier/transporter has primary insurance responsibility
                                                            during transit of your vehicle. All claims will be settled
                                                            at actual cost. All claims are to be made to the actual
                                                            carrier/transporter who transported your vehicle(s). Refer
                                                            to the carrier/transporter's bill of lading for information
                                                            regarding the claim process. The customer agrees that this
                                                            is the only contract between the parties covering the
                                                            arrangement of transport and no other agreements or
                                                            contracts are in effect until arrangement of scheduling has
                                                            been made with an authorized carrier, at this time the
                                                            carrier/transporter's contract and bill of lading will be in
                                                            effect immediately. No claims or legal action of any kind
                                                            may be initiated against the transport broker. All claims
                                                            for damage must be made to the carrier/transporter.
                                                        </li>
                                                        <br>
                                                        <li class="text-justify">
                                                            The carrier/transporter will not be responsible for any
                                                            damages caused by freezing of engine, cooling system,
                                                            batteries, or due to leaking fluids, etc. The
                                                            carrier/transporter will not be responsible for any exhaust
                                                            system, mufflers, tail pipes, or any mechanical function
                                                            damage to include engine, transmission, rear end, drive
                                                            trains, wiring systems, air bags, clutches, computerized
                                                            components (anything that is mechanical or electrical). The
                                                            carrier/transporter will not be responsible for any
                                                            convertible tops that are loose, torn, or have visible wear.
                                                            This includes any canvas or material coverings.
                                                        </li>
                                                        <br>
                                                        <li class="text-justify">
                                                            The customer is responsible for preparing the vehicle for
                                                            transport, Including: disarming any alarms, removing any
                                                            loose parts, accessories, hanging spoilers, etc. Any part of
                                                            the vehicle that falls off during transport is the
                                                            customer's responsibility including damages caused to any
                                                            and all other vehicles involved.
                                                        </li>
                                                        <br>
                                                        <li class="text-justify">
                                                            No auto rental will be honored for delays, damage,
                                                            accidents, acts of God, or for any other reason.
                                                        </li>
                                                        <br>
                                                        <li class="text-justify">
                                                            If a carrier/transporter is sent to acquire the vehicle and
                                                            it is not there, is unavailable, has been moved or cannot be
                                                            picked up for any other reason, the customer authorizes
                                                            ShipA1 Transport to charge an additional $100 reposting fee
                                                            that will be added to your transport order for the reposting
                                                            of your order to our dispatching department for
                                                            rescheduling, depending on the first available date given at
                                                            the time the service order was placed.
                                                        </li>
                                                        <br>
                                                        <li class="text-justify">
                                                            The vehicle owner or customer must in their absence,
                                                            designate a person to act as their agent at the point of
                                                            pickup or deliver. In which will be noted on the order form.
                                                            Customer/Shipper can be notified for pickup a minimum of
                                                            3-24 hours.
                                                        </li>
                                                        <br>
                                                        <li class="text-justify">
                                                            All vehicles to be delivered with a balance owing shall be
                                                            paid by CASH or CASHIER'S CHECK ONLY (U.S. Dollars) payable
                                                            to the carrier/transporter. Should delivery be attempted
                                                            after proper notification (3-24 hours voice notification to
                                                            phone numbers provided by customer/shipper) and
                                                            customer/shipper or his designated agent does not have
                                                            proper funds or is unavailable to receive delivery,
                                                            vehicle(s) will be taken to and left at the nearest
                                                            terminal, where shipper is responsible and will have to
                                                            retrieve, pay for storage or redelivery fees. It is the
                                                            customer's responsibility to have payment in full when
                                                            carrier/transporter arrives. If carrier/transporter notices
                                                            that he is unable to drive to the address at the time of
                                                            delivery the customer agrees to meet the carrier/transporter
                                                            at a nearby location. The customer agrees that if the
                                                            payment cannot be made by cash or cashier's check, the
                                                            vehicle will be stored at the customer's expense. Should the
                                                            customer be unable to accept delivery for any reason, the
                                                            vehicle will be stored at the customer's expense.
                                                        </li>
                                                        <br>
                                                        <li class="text-justify">
                                                            The customer agrees that should this vehicle become
                                                            inoperative for any reason at pickup or during transport, a
                                                            $150 non-operable fee will be assessed to the customer at
                                                            the time of delivery.
                                                        </li>
                                                        <br>
                                                        <li class="text-justify">
                                                            The carrier/transporter will not be responsible for any
                                                            damages not resulting from carrier/transporter negligence.
                                                            The customer verifies the vehicle is free of contents, to
                                                            and including trunk, therefore ShipA1 Transport and assigned
                                                            carrier/transporter do not take any responsibility for
                                                            personal items left inside vehicle.
                                                        </li>
                                                        <br>
                                                        <li class="text-justify">
                                                            Exceptions for damages must be noted on the post transport
                                                            inspection form at the time of delivery, any claim for
                                                            damages not documented on the post-trip inspection form will
                                                            not be honored.
                                                        </li>
                                                        <br>
                                                        <li class="text-justify">
                                                            All claims, litigation or legal action must have a right of
                                                            venue in that state of Maryland, County of Baltimore, in the
                                                            Superior Court.
                                                        </li>
                                                        <br>
                                                        <li class="text-justify">
                                                            You may cancel your order at anytime; cancellations must be
                                                            made in writing by fax or email. ShipA1 Transport has $99.00
                                                            cancellations fee. If you cancel your transport prior to you
                                                            vehicle(S) are assigned to carrier. If booked order is
                                                            cancelled after assigned to carrier there is a $199.00
                                                            charge which you agree to be charged. However, if in fact
                                                            your vehicle is not scheduled for pickup within 30 days from
                                                            your first available date, you are entitled to a full refund
                                                            of your deposit.
                                                        </li>
                                                        <br>
                                                        <li class="text-justify">
                                                            We reserve the right to refuse service to anyone who
                                                            violates any of the terms and conditions written above. Or
                                                            for any other reason we feel necessary. Threats, harassment,
                                                            etc. will result in immediate cancellation of your service
                                                            order and the administrative fee will not be refunded as we
                                                            have actively worked on your order, the remainder of your
                                                            deposit will be refunded.
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
                                        <label for="yourname"><strong>Your Name</strong><span
                                                    class="text-danger"> *</span></label>
                                        <input autocomplete="nope" type="text"
                                               class="form-control" id="yourname" name="yourname"
                                               value="{{isset($data->orderpayment->your_name) ? $data->orderpayment->your_name : ''}}"
                                               placeholder="Enter Your Name" required>

                                        <div class="invalid-feedback">
                                            This field is required.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="signature"><strong>Your Signature</strong><span class="text-danger"> *</span></label>
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
                                            <input class="form-check-input" type="checkbox" name="confirm" id="confirm"
                                                   required="" value="1">
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
                                    <button type="submit" class="btn btn-primary btn-lg submit" name="nextStep">Next Step >>
                                    </button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>







@endsection

@section('extraScript')


    <script>
        @if(!empty($data->oauction))
        setTimeout(function(){

           //$('#auction').trigger('change');
        },500);

        @endif

    </script>

    <script>

        $(".app-sidebar").hide();
        $(".app-header").hide();
        $(".switcher-wrapper").hide();
    </script>

    <script type="text/javascript">

        $(function () {
            $("#o_zip1").autocomplete({
                source: "/get_zip"
            });
        });

        $(document).ready(function () {
            $("#signtures").click(function () {
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


        $("#s1").click(function () {
            $("#signature1").prop("checked", true);
            var checked = $(this).is(':checked');
            if (checked) {
                alert('checked');
            } else {
                alert('unchecked');
            }

        });
        $(".sign2").click(function () {
            $("#signature2").prop("checked", true);

        });
        $(".sign3").click(function () {
            $("#signature3").prop("checked", true);

        });
        $(".sign4").click(function () {
            $("#signature4").prop("checked", true);

        });

        $('.btn-link').click(function () {
            $('#collapseTwo').toggle();
        });
        // $("#phone").mask("(999) 999-9999");
        // $("#phone2").mask("(999) 999-9999");
        // $("#ophone").mask("(999) 999-9999");
        // $("#ophone2").mask("(999) 999-9999");
        // $("#dphone").mask("(999) 999-9999");
        // $("#dphone2").mask("(999) 999-9999");


        $("#signature").change(function () {
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

        $("#auction").change(function () {

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
            }else{
                $("#auctionYes").hide();
                $("#auctionYes").html('');
            }
        });


    </script>
    <script>
        $(".submit").click(function(e){
            var allinput = $("input[required]");
            allinput.each(function(){
                if($(this).val() == '')
                {
                    e.preventDefault();
                    // console.log($(this));
                    $(this).attr("style","border-color:red!important")
                }
            })

        })
    </script>


@endsection


