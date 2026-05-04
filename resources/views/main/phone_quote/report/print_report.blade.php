@extends('layouts.print_layout')

@section('template_title')
    New Quote
@endsection
@include('partials.mainsite_pages.return_function')

<style>
    @import url('https://fonts.googleapis.com/css?family=Open+Sans|Rock+Salt|Shadows+Into+Light|Cedarville+Cursive');


    tbody {
        border: 1px solid #ffffff;
    }

    @page {
        size: 1cm 0cm 1cm 7cm !important;
        margin: 2mm 2mm 0mm 2mm !important;


    }

    @media print {
        .card-body{
            padding-bottom:10px !important;
            margin-bottom:10px !important;
        }
    }



    * {
        font-family: "Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", "Helvetica Neue", Arial, sans-serif;
    }

    .float_right {
        display: flex;
        flex-direction: row-reverse;
        font-size: 18px;
        font-weight: 500;
        margin-top: -12px;
    }

    h3 {
        color: #8cc73e;
    }

    h2, h3 {
        padding-top: 2px;
    }

    .c_name {
        color: #007bff;
    }

    .c_heading {
        color: #009eda;
    }
    .row{
        margin-top: -21px;
    }
    .page {
        /*background-image: linear-gradient(to right, rgb(109, 213, 250), rgb(255, 255, 255), rgb(41, 128, 185));*/

        /*background-color: white;*/
        box-shadow: none !important;
    }

    .app-header , .header{
        display: inline-flex !important;
    }
    .history_content {
        /*background-color: white;*/
        margin-top: 38px;
    }
    .card-header{
        /*justify-content: center;*/
        border-bottom: 1px solid #007bff !important;
    }
    .card{
        border: 1px solid #007bff !important;
    }
    .container{
        box-shadow: none !important;
    }
    td {
        font-weight: 600;
        font-family: 'Roboto';
    }


    td {
        text-transform: capitalize;
    }
</style>

@section('content')
    <?php
    $condition2 = explode('*^', $data->condition);



    ?>
    <div class="container card  p-5" style=" background-color: transparent; border: 0px !important; ">
        <ol class="breadcrumb" style=" margin-top: -13px !important; padding: 0px !important; ">
            <li class="breadcrumb-item"><a href="/dashboard"><i class="fe fe-layout mr-2 fs-14"></i>Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#">Report</a></li>
        </ol>
        <div class="row" style=" margin-top: 10px; ">
            <div class="card" >
                <div class="card-header">

                    <h3 class="c_name">ShipA1-Transportation Company</h3>

                    {{--<img src="/assets/images/brand/ship_logo.png" style="margin-left: 45%;" alt="Admintro logo"--}}
                         {{--style="cursor: default;">--}}
                    <h3 class="float_right " style="color: #ff0048;height: 36px;float: right;width: 66%;top: 17px;position: relative;" >ORDER# {{$data->id}}</h3>
                </div>
                <div class="card-body">
                    <div >
                    <h5>201 International Cir STE 230, Hunt Valley, MD, 21030</h5>
                    <h5>Tel No: (240) 489-2730</h5>
                    <h5>Email: support@shipa1.com</h5>
                    </div>
                    <h4 class="c_heading" style=" margin-top: 21px; ">ORDER INFORMATION</h4>
                    <ul class="list-group">
                        <li class="list-group-item" style=" height: 50px; ">
                            <h5>Order No #<label class="float_right "> {{$data->id}}</label></h5>
                        </li>
                        <li class="list-group-item" style=" height: 50px; ">
                            <h5>Pickup Date : <span class="float_right">{{$data->pickup_date}}</span></h5>
                        </li>
                        <li class="list-group-item" style=" height: 50px; ">
                            <h5>Delivery Date : <span class="float_right">{{$data->delivery_date}}</span></h5>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="card col-md-6">
                <div class="card-header ">
                    <h4 class="c_heading">ORIGIN INFORMATION</h4>
                </div>
                <div class="card-body">
                    <h5>Terminal, Dealer, Auction :
                        &nbsp;
                        <?php
                        echo get_oterminal_name($data->oterminal);
                        ?>

                        {{$data->oauction}}</h5>
                    <h5>Name: &nbsp;{{$data->oname}}</h5>
                    <h5>Buyer/Lot/Stock Number : &nbsp;{{$data->obuyer_no}}</h5>
                    <h5>Auction Name :&nbsp; {{$data->oauction}}</h5>
                    <h5>Auction Phone : &nbsp;{{$data->oauctionpho}} </h5>
                    <h5>Email Address :&nbsp;{{$data->oemail}}</h5>

                    <h4>Phone :
                        <?php
                        $condition1 = explode('*^', $data->ophone);
                        foreach ($condition1 as $v) {
                            $new = '(xxx) xxx-'.substr( $v, -4);
                            echo "&nbsp;(" . $new . "),";
                        }

                        ?>

                    </h4>
                    <h4>Address : &nbsp;{{$data->oaddress}} </h4>
                    <h4>City or Zip : &nbsp;{{$data->originzsc}}</h4>
                </div>
            </div>
            <div class="card col-md-6">
                <div class="card-header">
                    <h4 class="c_heading">DESTINATION INFORMATION</h4>
                </div>
                <div class="card-body">
                    <h5>Terminal, Dealer, Auction : &nbsp;
                        <?php
                          echo get_dterminal_name($data->dterminal);
                        ?>

                        {{ $data->dauction }}</h5>
                    <h5>Name :&nbsp;{{$data->dname}}</h5>
                    <h5>Phone :
                        <?php
                        $dphone = explode('*^', $data->dphone);
                        foreach ($dphone as $v) {
                            $new = '(xxx) xxx-'.substr( $v, -4);
                            echo "&nbsp;(" . $new . "),";
                        }

                        ?>


                    </h5>
                    <h5>Address : &nbsp;{{$data->daddress}}</h5>
                    <h5>City or Zip : &nbsp;{{$data->destinationzsc}} </h5>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="card col-md-6">
                <div class="card-header">
                    <h4 class="c_heading">VEHICLE</h4>
                </div>
                <div class="card-body">
                    <h5>Vehicle Num : &nbsp;
                        <?php
                        $vnums = explode('*^', $data->vin_num);
                        foreach ($vnums as $vnum){
                            if(!empty($vnum) && $vnum!=' ' ){
                            echo " ($vnum) ";
                             }
                        }
                        ?>



                    </h5>
                    <h5>Vehicle Name :
                        <?php
                        $vehiclename = explode('*^', $data->ymk);
                        foreach ($vehiclename as $vehicleymk){
                            echo " ($vehicleymk) ";
                        }
                        ?>


                    </h5>
                    <h5>Vehicle Type :
                        <?php
                        $condition3 = explode('*^', $data->type);
                        foreach ($condition3 as $type_val){
                            echo " ($type_val) ";
                        }
                        ?>

                    </h5>
                    <h5>Vehicle Condition : &nbsp;
                        @foreach($condition2 as $val2)
                            {{ "(".get_condtion($val2).")"}}
                        @endforeach
                    </h5>
                </div>

            </div>
            <div class="card col-md-6">
                <div class="card-header">
                    <h4 class="c_heading">ADDITIONAL INFORMATION</h4>
                </div>
                <div class="card-body">
                    <h5>Trailer Type :
                        <?php
                        $condition3 = explode('*^', $data->type);
                        foreach ($condition3 as $type_val){
                            echo " ($type_val) ";
                        }
                        ?>

                    </h5>
                    <h5>Need Title? : -- </h5>
                    <h5>Comments : &nbsp;{{$data->add_info}}</h5>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="card col-md-12">
                <div class="card-header">
                    <h4 class="c_heading">PRICING & PAYMENT</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <td>Order Booking Price :</td>
                            <td></td>
                            <td></td>

                            <td><h5 style="color: blue;font-weight: 600;float: right;font-size: 20px">
                                    $ {{$data->payment}}</h5></td>

                        </tr>
                        <tr>
                            <td>Price to Pay Carrier: {{$data->pay_carrier}}</td>
                            <td>COD/COP Amount: &nbsp;{{$data->company_price}}</td>
                            <td>COD/COP Payment Method: &nbsp; {{$data->payment_method}}</td>
                            <td>COD/COP Location: &nbsp;{{$data->cod_cop_loc}}</td>
                        </tr>
                        <tr>
                            <td>Balance Amount: &nbsp;{{$data->balance}}</td>
                            <td>Balance Payment Method :&nbsp; {{$data->balance_method}}</td>
                            <td>Balance Payment Time:&nbsp;{{$data->balance_time}}</td>
                            <td>Balance Terms: &nbsp;{{$data->terms}}</td>
                            {{--<td> &nbsp;{{$data->paid_status}}</td>--}}
                        </tr>
                        <tr>
                            <td  colspan="4" >listed price: &nbsp;{{$data->listed_price}}
                            </td>
                        </tr>
                        <tr>
                            <td  colspan="4" >Paid Status: : {{$data->payment_status}}
                            </td>
                        </tr>
                        <tr colspan="4" >
                            <td colspan="4" >Payment Status::
                                <span class="badge badge-pill badge-default mt-2">Payment: {{$data->paid_status}}</span> </h5>
                                @if($data->payment_status == "Paid")
                                    <h5 class="list-group-item">Payment Date: : <span>{{ date("M-d-y h:i:s a",strtotime($val->created_at))}}</span></h5>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4" rowspan="4">

                                Additional Information :&nbsp;{{$data->add_info}}<br>

                            </td>
                        </tr>
                        </tbody>
                        <tbody>
                        <tr>
                            <td colspan="4" rowspan="4">
                                CONFIRMATION SIGNATURE :
                                <span style=" font-family: 'Cedarville Cursive', cursive; font-size: 1.8em;" > &nbsp; {{  get_payment_detail($data->id,"signature") }} </span>

                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>


@endsection

@section('extraScript')








@endsection

