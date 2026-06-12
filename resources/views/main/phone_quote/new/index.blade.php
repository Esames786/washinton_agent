@extends('layouts.innerpages')

@section('template_title')
    {{ ucfirst(str_replace('_', ' ', Request::segment(1))) }}
@endsection

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha384-..." crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .selected {
            background: lightgray;
            border-radius: 10px;
        }

        .message-feed.right .mf-content {
            background: #705ec8;
        }

        .message-feed.right .mf-content:before {
            border-bottom: 8px solid #705ec8;
        }

        select.form-control:not([size]):not([multiple]) {
            height: 28px;
        }

        input[type='radio']:after {
            width: 15px;
            height: 15px;
            border-radius: 15px;
            top: -4px;
            left: -1px;
            position: relative;
            background-color: #d1d3d1;
            content: '';
            display: inline-block;
            visibility: visible;
            border: 2px solid white;
        }

        input[type='radio']:checked:after {
            width: 20px;
            height: 20px;
            border-radius: 100px;
            top: -2px;
            left: -6px;
            position: relative;
            background-color: rgb(23 162 184);
            content: '';
            display: inline-block;
            visibility: visible;
            border: 2px solid white;
        }

        select.form-control:not([size]):not([multiple]) {
            height: 2.375rem !important;
        }




        /*icon dynimic */
        #overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 2;
        }

        #errorIcon {
            font-size: 17px;
            color: #009eda !important;
            display: flex;
            align-items: center;
        }

        .popoverContent {
            /* display: none; */
            position: absolute;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 3;
            /* width: 178px; */
            /*right: 295px;*/
            /* bottom: 7px; */
        }

        .Terminal-error {
            display: inline-flex;
            column-gap: 14px;
        }

        label#selectedOptionLabel2 {
            display: block;
        }

        .icon-relative {
            position: relative !important;
        }





        /*.table {*/
        /*    !*color: rgb(0 0 0);*!*/
        /*    width: 100%;*/
        /*    max-width: 100%;*/
        /*    margin-bottom: 1rem;*/
        /*}*/

        /*.table-bordered, .text-wrap table, .table-bordered th, .text-wrap table th, .table-bordered td, .text-wrap table td {*/
        /*    border: 1px solid rgb(0 0 0);*/
        /*}*/

        /*.table > thead > tr > td, .table > thead > tr > th {*/
        /*    font-weight: 400;*/
        /*    -webkit-transition: all .3s ease;*/
        /*    font-size: 18px;*/
        /*    color: rgb(0 0 0);*/
        /*}*/
    </style>
    @php
        $ptype = 1;
        $query = \App\user_setting::where('user_id', Auth::user()->id)->first();
        if (!empty($query)) {
            $ptype = $query['penal_type'];
        }

        if ($ptype == 1) {
            $phoneaccess = explode(',', Auth::user()->emp_access_phone);
        } elseif ($ptype == 2) {
            $phoneaccess = explode(',', Auth::user()->emp_access_web);
        } elseif ($ptype == 3) {
            $phoneaccess = explode(',', Auth::user()->emp_access_test);
        } elseif ($ptype == 4) {
            $phoneaccess = explode(',', Auth::user()->panel_type_4);
        } elseif ($ptype == 5) {
            $phoneaccess = explode(',', Auth::user()->panel_type_5);
        } elseif ($ptype == 6) {
            $phoneaccess = explode(',', Auth::user()->panel_type_6);
        } else {
            $phoneaccess = [];
        }

    @endphp
    <div class="page-header">
        <div class="text-secondary text-center text-uppercase w-100">

            <div class="Terminal-error">
                <h1 class="my-4">
                    <b>{{ str_replace('_', ' ', \Request::segment(1)) == 'dispatch' ? 'Schedule' : str_replace('_', ' ', \Request::segment(1)) }}
                        Orders</b>
                </h1>
                <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon" style="cursor: pointer;"></i>
            </div>



            {{-- dd(\Request::segment(1)) --}}
            @if (\Request::segment(1) == 'payment_missing')
                {{--  @if (in_array('81', $phoneaccess)) --}}
                <div class="popoverContent" style="display: none;">
                    <div class="popover-title">{{ $label[365]->name }}</div>
                    <div class="popover-content">{{ $label[365]->display }}</div>
                </div>
                <!--<a href="{{ url('/excelsheet/payment-missing') }}" class="btn btn-primary float-right">Payment Missing Sheet</a>-->
                {{-- @endif --}}
            @elseif(\Request::segment(1) == 'dispatch')
                {{-- @if (in_array('82', $phoneaccess)) --}}
                <div class="popoverContent" style="display: none;">
                    <div class="popover-title">{{ $label[370]->name }}</div>
                    <div class="popover-content">{{ $label[370]->display }}</div>
                </div>
                <!--<a href="{{ url('/excelsheet/dispatch') }}" class="btn btn-primary float-right">Dispatch Sheet</a>-->
                {{--   @endif --}}
            @elseif(\Request::segment(1) == 'picked_up')
                {{-- @if (in_array('83', $phoneaccess)) --}}
                <div class="popoverContent" style="display: none;">
                    <div class="popover-title">{{ $label[495]->name }}</div>
                    <div class="popover-content">{{ $label[495]->display }}</div>
                </div>
                <!--<a href="{{ url('/excelsheet/pickup') }}" class="btn btn-primary float-right">Pickup Up Sheet</a>-->
                {{--  @endif --}}
            @elseif(\Request::segment(1) == 'delivered')
                <div class="popoverContent" style="display: none;">
                    <div class="popover-title">{{ $label[375]->name }}</div>
                    <div class="popover-content">{{ $label[375]->display }}</div>
                </div>
            @elseif(\Request::segment(1) == 'deliver_approval')
                <div class="popoverContent" style="display: none;">
                    <div class="popover-title">{{ $label[374]->name }}</div>
                    <div class="popover-content">{{ $label[374]->display }}</div>
                </div>
            @elseif(\Request::segment(1) == 'followup')
                <div class="popoverContent" style="display: none;">
                    <div class="popover-title">{{ $label[359]->name }}</div>
                    <div class="popover-content">{{ $label[359]->display }}</div>
                </div>
            @elseif(\Request::segment(1) == 'interested')
                <div class="popoverContent" style="display: none;">
                    <div class="popover-title">{{ $label[360]->name }}</div>
                    <div class="popover-content">{{ $label[360]->display }}</div>
                </div>
            @elseif(\Request::segment(1) == 'asking_low')
                <div class="popoverContent" style="display: none;">
                    <div class="popover-title">{{ $label[361]->name }}</div>
                    <div class="popover-content">{{ $label[361]->display }}</div>
                </div>
            @elseif(\Request::segment(1) == 'not_responding')
                <div class="popoverContent" style="display: none;">
                    <div class="popover-title">{{ $label[362]->name }}</div>
                    <div class="popover-content">{{ $label[362]->display }}</div>
                </div>
            @elseif(\Request::segment(1) == 'not_interested')
                <div class="popoverContent" style="display: none;">
                    <div class="popover-title">{{ $label[363]->name }}</div>
                    <div class="popover-content">{{ $label[363]->display }}</div>
                </div>
            @elseif(\Request::segment(1) == 'time_quote')
                <div class="popoverContent" style="display: none;">
                    <div class="popover-title">{{ $label[364]->name }}</div>
                    <div class="popover-content">{{ $label[364]->display }}</div>
                </div>
            @elseif(\Request::segment(1) == 'onapproval')
                <div class="popoverContent" style="display: none;">
                    <div class="popover-title">{{ $label[366]->name }}</div>
                    <div class="popover-content">{{ $label[366]->display }}</div>
                </div>
            @elseif(\Request::segment(1) == 'booked')
                <div class="popoverContent" style="display: none;">
                    <div class="popover-title">{{ $label[367]->name }}</div>
                    <div class="popover-content">{{ $label[367]->display }}</div>
                </div>
            @elseif(\Request::segment(1) == 'listed')
                <div class="popoverContent" style="display: none;">
                    <div class="popover-title">{{ $label[369]->name }}</div>
                    <div class="popover-content">{{ $label[369]->display }}</div>
                </div>
            @elseif(\Request::segment(1) == 'schedule_for_delivery')
                <div class="popoverContent" style="display: none;">
                    <div class="popover-title">{{ $label[373]->name }}</div>
                    <div class="popover-content">{{ $label[373]->display }}</div>
                </div>
            @elseif(\Request::segment(1) == 'completed')
                <div class="popoverContent" style="display: none;">
                    <div class="popover-title">{{ $label[376]->name }}</div>
                    <div class="popover-content">{{ $label[376]->display }}</div>
                </div>
            @elseif(\Request::segment(1) == 'cancel')
                <div class="popoverContent" style="display: none;">
                    <div class="popover-title">{{ $label[377]->name }}</div>
                    <div class="popover-content">{{ $label[377]->display }}</div>
                </div>
            @elseif(\Request::segment(1) == 'onapproval_cancel')
                <div class="popoverContent" style="display: none;">
                    <div class="popover-title">{{ $label[378]->name }}</div>
                    <div class="popover-content">{{ $label[378]->display }}</div>
                </div>
            @elseif(\Request::segment(1) == 'deleted')
                <div class="popoverContent" style="display: none;">
                    <div class="popover-title">{{ $label[379]->name }}</div>
                    <div class="popover-content">{{ $label[379]->display }}</div>
                </div>
            @elseif(\Request::segment(1) == 'picked_up_approval')
                <div class="popoverContent" style="display: none;">
                    <div class="popover-title">{{ $label[371]->name }}</div>
                    <div class="popover-content">{{ $label[371]->display }}</div>
                </div>
            @elseif(\Request::segment(1) == 'new')
                <div class="popoverContent" style="display: none;">
                    <div class="popover-title">{{ $label[500 - 4]->name }}</div>
                    <div class="popover-content">{{ $label[500 - 4]->display }}</div>
                </div>
            @endif

        </div>
        <!--    <div class="page-leftheader">-->
        <!--        {{-- <h4 class="page-title mb-0">{{  ucfirst(trim("$_SERVER[REQUEST_URI]",'/'))}}</h4> --}}-->
        <input type="hidden" value="{{ trim("$_SERVER[REQUEST_URI]", '/') }}" id="titlee">
        <!--        <ol class="breadcrumb">-->
        <!--            <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Home</a>-->
        <!--            </li>-->
        <!--            <li class="breadcrumb-item active" aria-current="page"><a href="#">New Order</a></li>-->
        <!--        </ol>-->
        <!--    </div>-->
        <!--    {{-- <div class="page-rightheader"> --}}-->
        <!--    {{-- <div class="btn btn-list"> --}}-->
        <!--    {{-- <a href="#" class="btn btn-info"><i class="fe fe-settings mr-1"></i> Port Sheet Update</a> --}}-->
        <!--    {{-- <a href="#" class="btn btn-danger"><i class="fe fe-printer mr-1"></i> Print </a> --}}-->
        <!--    {{-- <a href="#" class="btn btn-warning"><i class="fe fe-shopping-cart mr-1"></i> Buy Now </a> --}}-->
        <!--    {{-- </div> --}}-->
        <!--    {{-- </div> --}}-->
    </div>
    <!--End Page header-->
    <!-- Row -->
    <div class="row">
        <div class="col-12">
            @if (session('flash_message'))
                <div class="alert alert-success">
                    {{ session('flash_message') }}
                </div>
            @endif
            <!--div-->
            <div class="card">
                <div class="card-header">
                    <div class="container-fluid">
                        <form id="search_form" onsubmit="return false">
                            <div class="col-lg-12 p-0">
                                <div class="row">
                                    <!--
                                                                                                                                                                           <div class="col-lg-4 text-center pd-10">

                                                                                                                                                                               <div class='input-group date' id='datetimepicker1'>
                                                                                                                                                                                   <input class="form-control fc-datepicker" placeholder="MM/DD/YYYY"
                                                                                                                                                                                          type="text">
                                                                                                                                                                                   <span class="input-group-addon">
                                                                                                                                                                                       <span class="glyphicon glyphicon-calendar"></span>
                                                                                                                                                                                   </span>
                                                                                                                                                                               </div>

                                                                                                                                                                            </div>
                                                                                                                                                                            -->
                                    @if (\Request::segment(1) == 'cancel')
                                        <div class="col-lg-1 text-left pd-10 pl-0" style="margin: auto 0 4px;">
                                            <button class="btn btn-danger w-100 aucDate" type="button"
                                                title="Filter For Order Takers"><i class="fa fa-ban"
                                                    style="font-size: 21px;" aria-hidden="true"></i></button>
                                        </div>
                                        <?php
                                        $order_takers = \App\User::whereHas('userRole', function ($q) {
                                            $q->where('name', 'Order Taker')->orWhere('name', 'Seller Agent')->orWhere('name', 'CSR')->orWhere('name', 'Manager');
                                        })
                                            ->where('deleted', 0)
                                            ->select('id', 'name', 'slug')
                                            ->get();

                                        $ot_dis = \App\User::whereHas('userRole', function ($q) {
                                            $q->where('name', 'Order Taker')->orWhere('name', 'Seller Agent')->orWhere('name', 'CSR')->orWhere('name', 'Manager')->orWhere('name', 'Dispatcher');
                                        })
                                            ->where('deleted', 0)
                                            ->select('id', 'name', 'slug')
                                            ->get();
                                        ?>
                                        <div class="col-lg-3" style="display:none;" id="showAuctionDateRange">
                                            <div class="row">
                                                <div class="col-lg-6 text-center pd-10">
                                                    <label style="float: left">Order Takers</label>
                                                    <select id="order_taker_id" name="order_taker_id"
                                                        style="height: 35px;" class="form-control">
                                                        <option value="" selected>ALL</option>
                                                        @foreach ($order_takers as $key => $val)
                                                            <option value="{{ $val->id }}">
                                                                {{ $val->slug ?? $val->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 text-center pd-10">
                                                    <label style="float: left">Mistakers</label>
                                                    <select id="mistaker2" name="mistaker" style="height: 35px;"
                                                        class="form-control">
                                                        <option value="" selected>ALL</option>
                                                        @foreach ($ot_dis as $key => $val)
                                                            <option value="{{ $val->slug ?? $val->name }}">
                                                                {{ $val->slug ?? $val->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-lg-1 text-left pd-10 pl-0" style="margin: auto 0 4px;">
                                            <button class="btn btn-warning w-100 aucDate" type="button"
                                                title="Auction Filter"><img height="25px" width="25px"
                                                    src="{{ asset('images/hammer.png') }}" alt="hammer" /></button>
                                        </div>
                                        <div class="col-lg-3 text-center pd-10" style="display:none;"
                                            id="showAuctionDateRange">
                                            <label style="float: left">Auction Daterange <button type="button"
                                                    class="btn btn-info btn-sm" onclick="$('#date_range1').val('')"
                                                    style="padding: 3.2px 10px;">Clear</button></label>
                                            <div class='input-group date' id='datetimepicker1'>
                                                <input type='text' name="date_range1" id="date_range1"
                                                    class="form-control" />
                                                <span class="input-group-addon"
                                                    style="
                                                        border: 1px solid #ddd;
                                                        border-left-color: transparent;
                                                        border-radius: 0;
                                                        position: relative;
                                                        left: -1px;
                                                        display: flex;
                                                        align-items: center;
                                                    ">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 text-left pd-10" style="display:none;" id="accounttitle">
                                            <label style="float: left">Has Auction Account?</label>
                                            <select id="acutionaccounttitle" name="acutionaccounttitle"
                                                class="form-control" data-placeholder="50">
                                                <option value="">All</option>
                                                <option value="Yes">Yes</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 text-center pd-10" style="display:none;" id="accountname">
                                            <label style="float: left">Auction Account Name</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control"
                                                    placeholder="Auction Account Name..." id="acutionaccountname"
                                                    name="acutionaccountname">
                                            </div>
                                        </div>
                                    @endif
                                    @if (Auth::user()->userRole->name == 'Admin')
                                        <div class="col-lg-1 text-left pd-10" style="margin: auto 0 4px;">
                                            <button class="btn btn-info w-100 showQaFilter" type="button"
                                                title="QA Verify Filter"><i class="fa fa-check" style="font-size: 21px;"
                                                    aria-hidden="true"></i></button>
                                        </div>
                                        <div class="col-lg-3" style="display:none;" id="verifyNegative">
                                            <div class="row">
                                                <div class="col-lg-6 text-center pd-10">
                                                    <label style="float: left">Verified/Unverified</label>
                                                    <select id="verify2" name="verify" style="height: 35px;"
                                                        class="form-control">
                                                        <option value="" selected>ALL</option>
                                                        <option value="0">Unverified</option>
                                                        <option value="1">Verified</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 text-center pd-10">
                                                    <label style="float: left">Negative</label>
                                                    <select id="negative2" name="negative" style="height: 35px;"
                                                        class="form-control">
                                                        <option value="" selected>ALL</option>
                                                        <option value="1">Negative</option>
                                                        <option value="0">Not Negative</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="@if (Auth::user()->userRole->name == 'Admin') col-lg-10 @else col-lg-11 @endif">
                                        <div class="row">
                                            <div class="col-lg-2 text-left pd-10">
                                                <label style="float: left">Sort By</label>
                                                <select id="sort_by" name="sort_by" style="height: 35px;"
                                                    class="form-control">
                                                    <option value="created_at" selected>Created at</option>
                                                    <option value="updated_at">Updated at</option>
                                                </select>
                                            </div>
                                            @if (\Request::segment(1) == 'completed')
                                                <div class="col-lg-2 text-left pd-10">
                                                    <label style="float: left">Review</label>
                                                    <select id="review" name="review" style="height: 35px;"
                                                        class="form-control">
                                                        <option value="" selected>Select</option>
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>
                                            @endif
                                            <div class="col-lg-4 text-center pd-10">
                                                <label style="float: left">Daterange <button type="button"
                                                        class="btn btn-info btn-sm" onclick="$('#date_range').val('')"
                                                        style="padding: 3.2px 10px;">Clear</button></label>
                                                <div class='input-group date' id='datetimepicker1'>
                                                    <input type='text' name="date_range" id="date_range"
                                                        class="form-control" />
                                                    <span class="input-group-addon"
                                                        style="
                                                            border: 1px solid #ddd;
                                                            border-left-color: transparent;
                                                            border-radius: 0;
                                                            position: relative;
                                                            left: -1px;
                                                            display: flex;
                                                            align-items: center;
                                                        ">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 text-left pd-10">
                                                <label style="float: left">Search By</label>
                                                <select id="search_by" name="search_by" class="form-control"
                                                    data-placeholder="50">
                                                    <option value="id">Order ID</option>
                                                    <option value="oname">Customer Name</option>
                                                    <option value="ymk">Vehicle Name</option>
                                                    <option value="vin_num">VIN Number</option>
                                                    <option value="dauction">Port</option>
                                                    <option value="origincity">Pickup City</option>
                                                    <option value="originstate">Pickup State</option>
                                                    <option value="destinationcity">Delivery City</option>
                                                    <option value="destinationstate">Delivery State</option>
                                                    <option value="ophone">Customer Phone</option>
                                                    <option value="dphone">Destination Phone</option>
                                                    <option value="obuyer_no">Buyer #</option>
                                                    <option value="obuyer_lot_no">Lot #</option>
                                                    <option value="obuyer_stock_no">stock #</option>
                                                    @if (Auth::user()->userRole->name == 'Delivery Boy' || Auth::user()->userRole->name == 'Admin')
                                                        <option value="driverphoneno">Driver Phone</option>
                                                    @endif
                                                    <option value="created_at">Created At</option>
                                                    <option value="updated_at">Updated At</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-4 text-center pd-10 pr-0">
                                                <label style="float: left">Search For</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control"
                                                        placeholder="Search for..." id="keywords" name="keywords">
                                                    <span class="input-group-btn">
                                                        <button class="btn bd bd-l-0 bg-white tx-gray-600"
                                                            onclick="return_data()" type="button">
                                                            <i class="fa fa-search"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div id="table_data">
                        @include('main.phone_quote.new.load')
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end app-content-->

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
                        <h5 class=" lh-3 mg-b-20">Order Id # <input readonly type="text" style=" border: 0px; "
                                name="orderid" value="" /></h5>

                        <div class="card">
                            <div class="card-body pd-20">

                                @csrf
                                Email Link
                                </br>
                                <!-- <input type="hidden" name="user_id" value="47"> -->
                                <div class="form-group">
                                    <div class="row row-sm">
                                        <div class="col-sm">
                                            <div style="position: relative; display: inline-block;width: 100%;">
                                                <input type="text" readonly="" name="link" id="link"
                                                    class="form-control" value="" autocomplete="on">
                                                <div
                                                    style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; z-index: 1;">
                                                </div>
                                            </div>
                                        </div><!-- col -->
                                    </div><!-- row -->
                                </div><!-- form-group -->
                                <div class="form-group">
                                    <input type="text" name="email" id="email" class="form-control"
                                        value="" placeholder="Enter email address..." />
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



    <div class="modal" id="modalPaid">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-header pd-x-20">
                    <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Payment Status</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <form action="{{ url('paid_status') }}" method="post" id="form">
                        @csrf
                        <h5 class=" lh-3 mg-b-20">Order Id # <input readonly type="text" style=" border: 0px; "
                                name="orderid" value="" /></h5>

                        <div class="card">
                            <div class="card-body pd-20">


                                <h5>Payment Status</h5>
                                <br>
                                <!-- <input type="hidden" name="user_id" value="47"> -->
                                <div class="form-group">
                                    <div class="row row-sm">
                                        <div class="col-sm">

                                            <input type="radio" name="status" id="status0" class="rad-input"
                                                value="0" /> Pending &nbsp;&nbsp;


                                            <input type="radio" name="status" id="status1" class="rad-input"
                                                value="1" /> Updated &nbsp;&nbsp;


                                            <input type="radio" name="status" id="status2" class="rad-input"
                                                value="2" /> Received &nbsp;&nbsp;

                                            <input type="checkbox" name="fully_paid" id="fully_paid" class="rad-input"
                                                value="1" />
                                            Fully Paid
                                            <br>
                                            <br>
                                            <h5>Comments</h5>

                                            <textarea name="pay_comments" class="form-control"></textarea>

                                        </div><!-- col -->
                                    </div><!-- row -->
                                </div><!-- form-group -->
                                <div class="form-group">

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

    <div class="modal" id="trashmodal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-header pd-x-20">
                    <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Delete Order</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('trash_order') }}" method="post" id="form">
                    <div class="modal-body pd-20">

                        <h5 class=" lh-3 mg-b-20">Order Id # <input style="border: none" readonly type="text"
                                name="orderid" value="" /></h5>

                        <div class="card">
                            <div class="card-body pd-20">
                                @csrf
                                <div class="form-group" style=" text-align: center; font-size: 24px; ">
                                    Do you want to delete order <strong>?</strong>


                                </div><!-- card-body -->
                            </div>
                        </div>

                    </div><!-- modal-body -->
                    <div class="modal-footer" style=" justify-content: center; ">
                        <button type="submit" class="btn btn-danger pd-x-20 w-25">Yes
                        </button>
                        <button type="button" class="btn btn-info w-25" data-dismiss="modal">No
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal" id="carrier_comment">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content tx-size-sm" style="width: 165%;margin-left: -16pc;">
                <div class="modal-header pd-x-20">
                    <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">History</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('carrier_history_post') }}" method="post" id="form">
                    <div class="modal-body pd-20">

                        <h5 class=" lh-3 mg-b-20">Order Id #
                            <input style="border: none" readonly type="text" name="ca_order_id" id="ca_order_id"
                                value="" />
                        </h5>

                        <h5 class=" lh-3 mg-b-20">Carrier Id #
                            <input style="border: none" readonly type="text" name="ca_carrier_id" id="ca_carrier_id"
                                value="" />
                        </h5>

                        <div class="card">
                            <div class="card-body pd-20">
                                @csrf

                                <div class="form-group">

                                    <div class="chat-body-style ChatBody" style=" height: 198px; overflow: scroll ">
                                        <div class="message-feed media">
                                            <div class="media-body">
                                                <div class="mf-content" id="ca_carrier_comments1"
                                                    style=" font-size: 21px;width: 99% "></div>
                                            </div>
                                        </div>
                                    </div>
                                    <textarea class="form-control" name="ca_carrier_comments" id="ca_carrier_comments" style=" "></textarea>
                                    <br>
                                    <input type="submit" class="btn btn-primary" name="ca_submit">

                                </div><!-- card-body -->
                            </div>
                        </div>

                    </div><!-- modal-body -->
                    <div class="modal-footer" style=" justify-content: center; ">
                        <button type="button" class="btn btn-info w-25" data-dismiss="modal">Cancel
                        </button>
                    </div>
                </form>
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

    <div class="modal fade" id="comparehmodal" tabindex="-1" role="dialog" aria-labelledby="largemodal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content"
                style="width: max-content;right: 215px;min-width: 77pc !important;height: 47pc !important;overflow: scroll;">
                <div class="modal-header">
                    <center>
                        <h5 class="modal-title" id="largemodal1">Carrier Data</h5>
                        <input type="hidden" id="order_id_carr">
                        <input type="hidden" id="olcation_carr">
                        <input type="hidden" id="dlcation_carr">
                    </center>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <table class="table table-responsive table-bordered table-stripe table-condensed"
                            id="table_data_carrier">

                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="storagehmodal" tabindex="-1" role="dialog" aria-labelledby="largemodal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content"
                style="right: 215px;min-width: 77pc !important;height: 47pc !important;overflow: scroll;">
                <div class="modal-header">
                    <center>
                        <h5 class="modal-title" id="largemodal1">Storage Data</h5>
                    </center>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <table class="table table-responsive table-bordered table-stripe table-condensed"
                            id="table_data_storage">

                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="updateCarrierHistory" tabindex="-1" role="dialog" aria-labelledby="largemodal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content"
                style="width: max-content;right: 215px;min-width: 77pc !important;min-height: 23pc !important;">
                <div class="modal-header">
                    <center>
                        <h5 class="modal-title" id="largemodal1">Update Carrier History</h5>
                    </center>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="card-body">
                            <input type="hidden" id="old_order_id" />
                            <input type="hidden" id="new_order_id" />
                            <h3>Order Id#</h3>
                            <div class="form-group">
                                <label for="carrier_history" class="form-label">History</label>
                                <textarea class="form-control" id="carrier_history" placeholder="Write the history"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="btnUpdateHistory">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="viewCarrierHistory" tabindex="-1" role="dialog" aria-labelledby="largemodal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content"
                style="width: max-content;right: 215px;min-width: 77pc !important;height: 47pc !important;">
                <div class="modal-header">
                    <center>
                        <h5 class="modal-title" id="largemodal1">View Carrier History</h5>
                    </center>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body" id="viewHistoryOfCarrier"
                        style="min-height:100px;max-height:567px;overflow:scroll;">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="find_carrier_modal" tabindex="-1" role="dialog" aria-labelledby="largemodal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content"
                style="width: max-content;right: 302px;min-width: 86pc !important;min-height: 36pc !important;">
                <div class="modal-header">
                    <center>
                        <h5 class="modal-title" id="largemodal1">Find Carrier Data <span class="badge badge-warning"
                                style=" font-size: 17px; font-family: emoji; " id="find_o_id"></span></h5>

                    </center>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <table class="table table-responsive table-bordered table-stripe table-condensed"
                            id="table_find_data_carrier">

                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="messageCallCenter" tabindex="-1" role="dialog"
        aria-labelledby="messageCallCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageCallCenterLongTitle">Message Center</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="modalClick()">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <div class="panel panel-primary">
                            <div class=" tab-menu-heading p-0 bg-light">
                                <div class="tabs-menu1 ">
                                    <!-- Tabs -->
                                    <ul class="nav panel-tabs">
                                        <li class=""><a href="#tab4" class="active" data-toggle="tab">Update
                                                Message Center</a>
                                        </li>
                                        <li><a href="#tab5" data-toggle="tab">View Message Center</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body tabs-menu-body">
                                <div class="tab-content">
                                    <div class="tab-pane active " id="tab4">
                                        <form method="post" action="#">
                                            <div class="row">
                                                <input type="hidden" class="form-control" name="id" id='orderId22'
                                                    placeholder="" readonly>
                                                <input type="hidden" class="form-control" name="status" id='status'
                                                    placeholder="" readonly>
                                                <input type="hidden" class="form-control" name="cname" id='cname'
                                                    placeholder="" readonly>
                                                <input type="hidden" class="form-control" name="cphone" id='cphone'
                                                    placeholder="" readonly>
                                                <div class="col-sm-12 col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Date/Time</label>
                                                        <input type="datetime-local" class="form-control"
                                                            name="date_time" id='date_time' placeholder="Date/Time">
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-12">
                                                    <div class="btn-group">
                                                        <button type="button"
                                                            class="btn btn-primary active changeMsg">Your Message</button>
                                                        <button type="button" class="btn btn-primary changeMsg">Client
                                                            Reply</button>
                                                    </div>
                                                </div>
                                                <input type="hidden" value="Message" id="messageReply"
                                                    value="Your Message" />
                                                <br>
                                                <br>
                                                <div class="col-sm-12 col-md-12" id="msgReply">
                                                    <div class="form-group">
                                                        <label class="form-label">Your Message</label>
                                                        <textarea required name="history" id='description' class="form-control" placeholder="Write Your Message"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-primary" id="udpateMessageCall">Save
                                                changes</button>
                                        </form>
                                    </div>
                                    <div class="tab-pane" id="tab5">
                                        <div class="chat-body-style ChatBody viewMessageCall"
                                            style="overflow:scroll; height:300px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"
                        onclick="modalClick()">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="authorizationForm" tabindex="-1" role="dialog"
        aria-labelledby="authorizationFormTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="authorizationFormTitle">Authorization Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="modalClick()">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <div class="panel panel-primary">
                            <div class="panel-body tabs-menu-body">
                                <div class="tab-content">
                                    <div class="tab-pane active " id="tab4">
                                        <form method="post" action="{{ route('authorization.form.email') }}">
                                            @csrf
                                            <div class="row">
                                                <input type="hidden" class="form-control" name="id"
                                                    id='authorization-orderId' placeholder="" readonly>
                                                <input type="hidden" class="form-control" name="status"
                                                    id='authorization-status' placeholder="" readonly>
                                                <input type="hidden" class="form-control" name="cname"
                                                    id='authorization-cname' placeholder="" readonly>
                                                <input type="hidden" class="form-control" name="cphone"
                                                    id='authorization-cphone' placeholder="" readonly>
                                                <input type="hidden" class="form-control" name="origin"
                                                    id='authorization-origin' placeholder="" readonly>
                                                <input type="hidden" class="form-control" name="destination"
                                                    id='authorization-destination' placeholder="" readonly>
                                                <input type="hidden" class="form-control" name="vehicle"
                                                    id='authorization-vehicle' placeholder="" readonly>
                                                <br>
                                                <div class="col-sm-12 col-md-12" id="msgReply">
                                                    <div class="form-group">
                                                        <label class="form-label">Enter Inv. Amount</label>
                                                        <input type="number" value="" name="invoiceAmount"
                                                            class="form-control" id="authorizationAmount"
                                                            placeholder="Enter Amount" required />
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Enter Email</label>
                                                        <input type="email" value="" name="email"
                                                            class="form-control" id="authorizationEmail"
                                                            placeholder="Enter Email" />
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary"
                                                id="authorizationForm">Send</button>
                                        </form>
                                    </div>
                                    <div class="tab-pane" id="tab5">
                                        <div class="chat-body-style ChatBody viewMessageCall"
                                            style="overflow:scroll; height:300px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"
                        onclick="modalClick()">Close</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="largemodal" tabindex="-1" role="dialog" aria-labelledby="largemodal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg " role="document" style="max-width:75%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="largemodal1">HISTORY/STATUS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <div class="panel panel-primary">
                            <div class=" tab-menu-heading p-0 bg-light">
                                <div class="tabs-menu1 ">
                                    <!-- Tabs -->
                                    @php
                                        $check_panel = check_panel();

                                        if ($check_panel == 1) {
                                            $phoneaccess = explode(',', Auth::user()->emp_access_phone);
                                        } elseif ($check_panel == 3) {
                                            $phoneaccess = explode(',', Auth::user()->emp_access_test);
                                        } else {
                                            $phoneaccess = explode(',', Auth::user()->emp_access_web);
                                        }
                                    @endphp
                                    <ul class="nav panel-tabs">
                                        <li class=""><a href="#tab1" class="active"
                                                data-toggle="tab">HISTORY/STATUS</a>
                                        </li>
                                        <li><a href="#tab2" data-toggle="tab">VIEW HISTORY</a></li>
                                        @if (
                                            \Request::is('listed') ||
                                                \Request::is('dispatch') ||
                                                \Request::is('picked_up') ||
                                                \Request::is('picked_up_approval') ||
                                                \Request::is('deliver_approval') ||
                                                \Request::is('onapproval_cancel'))
                                            <li><a href="#tab3" data-toggle="tab">Relist</a></li>
                                        @endif
                                        @if (in_array('64', $phoneaccess))
                                            <li><a href="#tab8" data-toggle="tab">UPDATE QA HISTORY</a></li>
                                        @endif
                                        @if (in_array('65', $phoneaccess))
                                            <li class="position-relative">
                                                <a href="#tab9" data-toggle="tab">VIEW QA HISTORY</a>
                                                <span id="qa_count"
                                                    class="badge badge-success text-light position-absolute"
                                                    style="top: -12px;right: -6px;height: 30px;width: 30px;display: flex;justify-content: center;align-items: center;font-size: 13px;">0</span>
                                            </li>
                                        @endif
                                        @if (in_array('101', $phoneaccess))
                                            <!--=========================new form ==================-->
                                            <li class="position-relative" id="status_checkk">
                                                <a href="#tab11" data-toggle="tab">UPDATE APPROACH</a>
                                            </li>
                                            <!--=========================new form==================-->
                                        @endif
                                        @if (in_array('102', $phoneaccess))
                                            <!--=========================new==================-->
                                            <li class="position-relative">
                                                <a href="#tab10" data-toggle="tab">VIEW APPROACH</a>
                                                <span id="approach_count"
                                                    class="badge badge-success text-light position-absolute"
                                                    style="top: -12px;right: -6px;height: 30px;width: 30px;display: flex;justify-content: center;align-items: center;font-size: 13px;">0</span>
                                            </li>
                                            <!--=========================new==================-->
                                        @endif
                                        <!--=========================Nature of Customer==================-->
                                        <li class="position-relative" id="getCustomerNature">
                                            <a href="#tab12" data-toggle="tab">Nature of Customer</a>
                                        </li>
                                        <!--=========================Nature of Customer==================-->

                                        <!--=========================Nature of Customer==================-->
                                        <li class="position-relative" id="ProfileCard">
                                            <a href="#tab13" data-toggle="tab">Profile Card</a>
                                        </li>
                                        <!--=========================Nature of Customer==================-->

                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body tabs-menu-body">
                                <div class="tab-content">
                                    <div class="tab-pane active " id="tab1">

                                        @if (\Request::is('new'))
                                            <form method="post" action="{{ route('call_history_post') }}"
                                                enctype="multipart/form-data" id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">New HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="1">Interested</option>
                                                                <option value="2">FollowMore</option>
                                                                <option value="3">AskingLow</option>
                                                                <option value="4">NotInterested</option>
                                                                <option value="5">NoResponse</option>
                                                                <option value="6">TimeQuote</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">EXPECTED DATE</label>
                                                            <input type="date" required name="expected_date"
                                                                id='expected_date' class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12" id="ask_low">

                                                    </div>

                                                </div>
                                                <div class="col-md-3">
                                                    @if ($label[512 - 1]->status == 1)
                                                        <div class="Terminal-error">
                                                            <label>Review</label>
                                                            <i id="errorIcon"
                                                                class="fas fa-info-circle fa-lg text-info info-icon"
                                                                style="cursor: pointer;"></i>
                                                        </div>
                                                        <div class="popoverContent" style="display: none;">
                                                            <div class="popover-title">{{ $label[512 - 1]->name }}</div>
                                                            <div class="popover-content">{{ $label[512 - 1]->display }}
                                                            </div>
                                                        </div>
                                                    @else
                                                        <label>Review</label>
                                                    @endif
                                                    <select id="auc_reviewss" name="auc_review"
                                                        class="form-control h-50 auc_review" required>
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 all_rating" id="all_rating" style="display:none;">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            @if ($label[513 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Website</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[513 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[513 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Website</label>
                                                            @endif
                                                            <select id="auc_website" name="auc_website"
                                                                class="form-control h-50">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="BBB">BBB</option>
                                                                <option value="Trust Pilot">Trust Pilot</option>
                                                                <option value="Google">Google</option>
                                                                <option value="Other">Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-6" style="display:none;" id="other_website">
                                                            @if ($label[514 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Other Website</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[514 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[514 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Other Website</label>
                                                            @endif
                                                            <input class="form-control h-50" id="auc_website_other"
                                                                name="auc_website_other" placeholder="Other Website"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-6">
                                                            @if ($label[515 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Rating</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[515 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[515 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Rating</label>
                                                            @endif
                                                            <select id="auc_client_rating" name="auc_client_rating"
                                                                class="form-control h-50">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="Positive">Positive</option>
                                                                <option value="Neutral">Neutral</option>
                                                                <option value="Negative">Negative</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            @if ($label[516 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Website Link</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[516 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[516 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Website Link</label>
                                                            @endif
                                                            <input class="form-control h-50" id="auc_website_link"
                                                                name="auc_website_link" placeholder="Website Link"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label>Reviewer</label>
                                                            <input type="text" class="form-control" id=""
                                                                name="auc_reviewer" placeholder="Reviewer"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label>Attach Screenshot</label>
                                                            <input type="file" class="form-control" id=""
                                                                name="screenshot" placeholder="screenshot"
                                                                value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif
                                        @if (\Request::is('followup'))
                                            <form method="post" enctype="multipart/form-data"
                                                action="{{ route('call_history_post') }}" id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">FollowUp HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="1">Interested</option>
                                                                <option value="2">FollowMore</option>
                                                                <option value="3">AskingLow</option>
                                                                <option value="4">NotInterested</option>
                                                                <option value="5">NoResponse</option>
                                                                <option value="6">TimeQuote</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">EXPECTED DATE</label>
                                                            <input type="date" required name="expected_date"
                                                                id='expected_date' class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12" id="ask_low">

                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    @if ($label[512 - 1]->status == 1)
                                                        <div class="Terminal-error">
                                                            <label>Review</label>
                                                            <i id="errorIcon"
                                                                class="fas fa-info-circle fa-lg text-info info-icon"
                                                                style="cursor: pointer;"></i>
                                                        </div>
                                                        <div class="popoverContent" style="display: none;">
                                                            <div class="popover-title">{{ $label[512 - 1]->name }}</div>
                                                            <div class="popover-content">{{ $label[512 - 1]->display }}
                                                            </div>
                                                        </div>
                                                    @else
                                                        <label>Review</label>
                                                    @endif
                                                    <select id="auc_reviewss" name="auc_review"
                                                        class="form-control h-50 auc_review" required>
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 all_rating" id="all_rating"
                                                    style="display:none;">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            @if ($label[513 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Website</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[513 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[513 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Website</label>
                                                            @endif
                                                            <select id="auc_website" name="auc_website"
                                                                class="form-control h-50">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="BBB">BBB</option>
                                                                <option value="Trust Pilot">Trust Pilot</option>
                                                                <option value="Google">Google</option>
                                                                <option value="Other">Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-6" style="display:none;"
                                                            id="other_website">
                                                            @if ($label[514 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Other Website</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[514 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[514 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Other Website</label>
                                                            @endif
                                                            <input class="form-control h-50" id="auc_website_other"
                                                                name="auc_website_other" placeholder="Other Website"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-6">
                                                            @if ($label[515 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Rating</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[515 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[515 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Rating</label>
                                                            @endif
                                                            <select id="auc_client_rating" name="auc_client_rating"
                                                                class="form-control h-50">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="Positive">Positive</option>
                                                                <option value="Neutral">Neutral</option>
                                                                <option value="Negative">Negative</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            @if ($label[516 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Website Link</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[516 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[516 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Website Link</label>
                                                            @endif
                                                            <input class="form-control h-50" id="auc_website_link"
                                                                name="auc_website_link" placeholder="Website Link"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label>Reviewer</label>
                                                            <input type="text" class="form-control" id=""
                                                                name="auc_reviewer" placeholder="Reviewer"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label>Attach Screenshot</label>
                                                            <input type="file" class="form-control" id=""
                                                                name="screenshot" placeholder="screenshot"
                                                                value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif
                                        @if (\Request::is('interested'))
                                            <form method="post" enctype="multipart/form-data"
                                                action="{{ route('call_history_post') }}" id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">INTERESTED HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="3">AskingLow</option>
                                                                <option value="5">NoResponse</option>
                                                                <option value="6">TimeQuote</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">EXPECTED DATE</label>
                                                            <input type="date" required name="expected_date"
                                                                id='expected_date' class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12" id="ask_low">

                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    @if ($label[512 - 1]->status == 1)
                                                        <div class="Terminal-error">
                                                            <label>Review</label>
                                                            <i id="errorIcon"
                                                                class="fas fa-info-circle fa-lg text-info info-icon"
                                                                style="cursor: pointer;"></i>
                                                        </div>
                                                        <div class="popoverContent" style="display: none;">
                                                            <div class="popover-title">{{ $label[512 - 1]->name }}</div>
                                                            <div class="popover-content">{{ $label[512 - 1]->display }}
                                                            </div>
                                                        </div>
                                                    @else
                                                        <label>Review</label>
                                                    @endif
                                                    <select id="auc_reviewss" name="auc_review"
                                                        class="form-control h-50 auc_review" required>
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 all_rating" id="all_rating"
                                                    style="display:none;">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            @if ($label[513 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Website</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[513 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[513 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Website</label>
                                                            @endif
                                                            <select id="auc_website" name="auc_website"
                                                                class="form-control h-50">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="BBB">BBB</option>
                                                                <option value="Trust Pilot">Trust Pilot</option>
                                                                <option value="Google">Google</option>
                                                                <option value="Other">Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-6" style="display:none;"
                                                            id="other_website">
                                                            @if ($label[514 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Other Website</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[514 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[514 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Other Website</label>
                                                            @endif
                                                            <input class="form-control h-50" id="auc_website_other"
                                                                name="auc_website_other" placeholder="Other Website"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-6">
                                                            @if ($label[515 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Rating</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[515 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[515 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Rating</label>
                                                            @endif
                                                            <select id="auc_client_rating" name="auc_client_rating"
                                                                class="form-control h-50">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="Positive">Positive</option>
                                                                <option value="Neutral">Neutral</option>
                                                                <option value="Negative">Negative</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            @if ($label[516 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Website Link</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[516 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[516 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Website Link</label>
                                                            @endif
                                                            <input class="form-control h-50" id="auc_website_link"
                                                                name="auc_website_link" placeholder="Website Link"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label>Reviewer</label>
                                                            <input type="text" class="form-control" id=""
                                                                name="auc_reviewer" placeholder="Reviewer"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label>Attach Screenshot</label>
                                                            <input type="file" class="form-control" id=""
                                                                name="screenshot" placeholder="screenshot"
                                                                value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif
                                        @if (\Request::is('asking_low'))
                                            <form method="post" enctype="multipart/form-data"
                                                action="{{ route('call_history_post') }}" id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">ASKING LOW HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="1">Interested</option>
                                                                <option value="2">FollowMore</option>
                                                                <option value="4">NotInterested</option>
                                                                <option value="5">NoResponse</option>
                                                                <option value="6">TimeQuote</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">EXPECTED DATE</label>
                                                            <input type="date" required name="expected_date"
                                                                id='expected_date' class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12" id="ask_low">

                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    @if ($label[512 - 1]->status == 1)
                                                        <div class="Terminal-error">
                                                            <label>Review</label>
                                                            <i id="errorIcon"
                                                                class="fas fa-info-circle fa-lg text-info info-icon"
                                                                style="cursor: pointer;"></i>
                                                        </div>
                                                        <div class="popoverContent" style="display: none;">
                                                            <div class="popover-title">{{ $label[512 - 1]->name }}</div>
                                                            <div class="popover-content">{{ $label[512 - 1]->display }}
                                                            </div>
                                                        </div>
                                                    @else
                                                        <label>Review</label>
                                                    @endif
                                                    <select id="auc_reviewss" name="auc_review"
                                                        class="form-control h-50 auc_review" required>
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 all_rating" id="all_rating"
                                                    style="display:none;">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            @if ($label[513 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Website</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[513 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[513 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Website</label>
                                                            @endif
                                                            <select id="auc_website" name="auc_website"
                                                                class="form-control h-50">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="BBB">BBB</option>
                                                                <option value="Trust Pilot">Trust Pilot</option>
                                                                <option value="Google">Google</option>
                                                                <option value="Other">Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-6" style="display:none;"
                                                            id="other_website">
                                                            @if ($label[514 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Other Website</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[514 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[514 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Other Website</label>
                                                            @endif
                                                            <input class="form-control h-50" id="auc_website_other"
                                                                name="auc_website_other" placeholder="Other Website"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-6">
                                                            @if ($label[515 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Rating</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[515 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[515 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Rating</label>
                                                            @endif
                                                            <select id="auc_client_rating" name="auc_client_rating"
                                                                class="form-control h-50">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="Positive">Positive</option>
                                                                <option value="Neutral">Neutral</option>
                                                                <option value="Negative">Negative</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            @if ($label[516 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Website Link</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[516 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[516 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Website Link</label>
                                                            @endif
                                                            <input class="form-control h-50" id="auc_website_link"
                                                                name="auc_website_link" placeholder="Website Link"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label>Reviewer</label>
                                                            <input type="text" class="form-control" id=""
                                                                name="auc_reviewer" placeholder="Reviewer"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label>Attach Screenshot</label>
                                                            <input type="file" class="form-control" id=""
                                                                name="screenshot" placeholder="screenshot"
                                                                value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif

                                        @if (\Request::is('not_interested'))
                                            <form method="post" enctype="multipart/form-data"
                                                action="{{ route('call_history_post') }}" id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">NOT-INTERESTED HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="1">Interested</option>
                                                                <option value="2">FollowMore</option>
                                                                <option value="3">AskingLow</option>
                                                                <option value="5">NoResponse</option>
                                                                <option value="6">TimeQuote</option>
                                                                <option value="4">Not Interested</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">EXPECTED DATE</label>
                                                            <input type="date" required name="expected_date"
                                                                id='expected_date' class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12" id="ask_low">

                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    @if ($label[512 - 1]->status == 1)
                                                        <div class="Terminal-error">
                                                            <label>Review</label>
                                                            <i id="errorIcon"
                                                                class="fas fa-info-circle fa-lg text-info info-icon"
                                                                style="cursor: pointer;"></i>
                                                        </div>
                                                        <div class="popoverContent" style="display: none;">
                                                            <div class="popover-title">{{ $label[512 - 1]->name }}</div>
                                                            <div class="popover-content">{{ $label[512 - 1]->display }}
                                                            </div>
                                                        </div>
                                                    @else
                                                        <label>Review</label>
                                                    @endif
                                                    <select id="auc_reviewss" name="auc_review"
                                                        class="form-control h-50 auc_review" required>
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 all_rating" id="all_rating"
                                                    style="display:none;">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            @if ($label[513 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Website</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[513 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[513 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Website</label>
                                                            @endif
                                                            <select id="auc_website" name="auc_website"
                                                                class="form-control h-50">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="BBB">BBB</option>
                                                                <option value="Trust Pilot">Trust Pilot</option>
                                                                <option value="Google">Google</option>
                                                                <option value="Other">Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-6" style="display:none;"
                                                            id="other_website">
                                                            @if ($label[514 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Other Website</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[514 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[514 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Other Website</label>
                                                            @endif
                                                            <input class="form-control h-50" id="auc_website_other"
                                                                name="auc_website_other" placeholder="Other Website"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-6">
                                                            @if ($label[515 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Rating</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[515 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[515 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Rating</label>
                                                            @endif
                                                            <select id="auc_client_rating" name="auc_client_rating"
                                                                class="form-control h-50">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="Positive">Positive</option>
                                                                <option value="Neutral">Neutral</option>
                                                                <option value="Negative">Negative</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            @if ($label[516 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Website Link</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[516 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[516 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Website Link</label>
                                                            @endif
                                                            <input class="form-control h-50" id="auc_website_link"
                                                                name="auc_website_link" placeholder="Website Link"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label>Reviewer</label>
                                                            <input type="text" class="form-control" id=""
                                                                name="auc_reviewer" placeholder="Reviewer"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label>Attach Screenshot</label>
                                                            <input type="file" class="form-control" id=""
                                                                name="screenshot" placeholder="screenshot"
                                                                value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif

                                        @if (\Request::is('not_responding'))
                                            <form method="post" enctype="multipart/form-data"
                                                action="{{ route('call_history_post') }}" id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">Not Responding HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="1">Interested</option>
                                                                <option value="2">FollowMore</option>
                                                                <option value="3">AskingLow</option>
                                                                <option value="4">NotInterested</option>
                                                                <option value="5">NoResponse</option>
                                                                <option value="6">TimeQuote</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">EXPECTED DATE</label>
                                                            <input type="date" required name="expected_date"
                                                                id='expected_date' class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12" id="ask_low">

                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    @if ($label[512 - 1]->status == 1)
                                                        <div class="Terminal-error">
                                                            <label>Review</label>
                                                            <i id="errorIcon"
                                                                class="fas fa-info-circle fa-lg text-info info-icon"
                                                                style="cursor: pointer;"></i>
                                                        </div>
                                                        <div class="popoverContent" style="display: none;">
                                                            <div class="popover-title">{{ $label[512 - 1]->name }}</div>
                                                            <div class="popover-content">{{ $label[512 - 1]->display }}
                                                            </div>
                                                        </div>
                                                    @else
                                                        <label>Review</label>
                                                    @endif
                                                    <select id="auc_reviewss" name="auc_review"
                                                        class="form-control h-50 auc_review" required>
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 all_rating" id="all_rating"
                                                    style="display:none;">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            @if ($label[513 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Website</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[513 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[513 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Website</label>
                                                            @endif
                                                            <select id="auc_website" name="auc_website"
                                                                class="form-control h-50">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="BBB">BBB</option>
                                                                <option value="Trust Pilot">Trust Pilot</option>
                                                                <option value="Google">Google</option>
                                                                <option value="Other">Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-6" style="display:none;"
                                                            id="other_website">
                                                            @if ($label[514 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Other Website</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[514 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[514 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Other Website</label>
                                                            @endif
                                                            <input class="form-control h-50" id="auc_website_other"
                                                                name="auc_website_other" placeholder="Other Website"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-6">
                                                            @if ($label[515 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Rating</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[515 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[515 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Rating</label>
                                                            @endif
                                                            <select id="auc_client_rating" name="auc_client_rating"
                                                                class="form-control h-50">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="Positive">Positive</option>
                                                                <option value="Neutral">Neutral</option>
                                                                <option value="Negative">Negative</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            @if ($label[516 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Website Link</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[516 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[516 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Website Link</label>
                                                            @endif
                                                            <input class="form-control h-50" id="auc_website_link"
                                                                name="auc_website_link" placeholder="Website Link"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label>Reviewer</label>
                                                            <input type="text" class="form-control" id=""
                                                                name="auc_reviewer" placeholder="Reviewer"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label>Attach Screenshot</label>
                                                            <input type="file" class="form-control" id=""
                                                                name="screenshot" placeholder="screenshot"
                                                                value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif

                                        @if (\Request::is('time_quote'))
                                            <form method="post" enctype="multipart/form-data"
                                                action="{{ route('call_history_post') }}" id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">TimeQuote HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="1">Interested</option>
                                                                <option value="2">FollowMore</option>
                                                                <option value="3">AskingLow</option>
                                                                <option value="4">NotInterested</option>
                                                                <option value="5">NoResponse</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">EXPECTED DATE</label>
                                                            <input type="date" required name="expected_date"
                                                                id='expected_date' class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12" id="ask_low">

                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    @if ($label[512 - 1]->status == 1)
                                                        <div class="Terminal-error">
                                                            <label>Review</label>
                                                            <i id="errorIcon"
                                                                class="fas fa-info-circle fa-lg text-info info-icon"
                                                                style="cursor: pointer;"></i>
                                                        </div>
                                                        <div class="popoverContent" style="display: none;">
                                                            <div class="popover-title">{{ $label[512 - 1]->name }}</div>
                                                            <div class="popover-content">{{ $label[512 - 1]->display }}
                                                            </div>
                                                        </div>
                                                    @else
                                                        <label>Review</label>
                                                    @endif
                                                    <select id="auc_reviewss" name="auc_review"
                                                        class="form-control h-50 auc_review" required>
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 all_rating" id="all_rating"
                                                    style="display:none;">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            @if ($label[513 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Website</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[513 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[513 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Website</label>
                                                            @endif
                                                            <select id="auc_website" name="auc_website"
                                                                class="form-control h-50">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="BBB">BBB</option>
                                                                <option value="Trust Pilot">Trust Pilot</option>
                                                                <option value="Google">Google</option>
                                                                <option value="Other">Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-6" style="display:none;"
                                                            id="other_website">
                                                            @if ($label[514 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Other Website</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[514 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[514 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Other Website</label>
                                                            @endif
                                                            <input class="form-control h-50" id="auc_website_other"
                                                                name="auc_website_other" placeholder="Other Website"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-6">
                                                            @if ($label[515 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Rating</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[515 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[515 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Rating</label>
                                                            @endif
                                                            <select id="auc_client_rating" name="auc_client_rating"
                                                                class="form-control h-50">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="Positive">Positive</option>
                                                                <option value="Neutral">Neutral</option>
                                                                <option value="Negative">Negative</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            @if ($label[516 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Website Link</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[516 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[516 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Website Link</label>
                                                            @endif
                                                            <input class="form-control h-50" id="auc_website_link"
                                                                name="auc_website_link" placeholder="Website Link"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label>Reviewer</label>
                                                            <input type="text" class="form-control" id=""
                                                                name="auc_reviewer" placeholder="Reviewer"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label>Attach Screenshot</label>
                                                            <input type="file" class="form-control" id=""
                                                                name="screenshot" placeholder="screenshot"
                                                                value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif
                                        @if (\Request::is('payment_missing'))
                                            <form method="post" enctype="multipart/form-data"
                                                action="{{ route('call_history_post') }}" id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">Payment Missing HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control statusList">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="7">PAYMENT MISSING</option>
                                                                <option value="9">LISTED</option>
                                                                <option value="19">ONAPPROVAL CANCEL</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">EXPECTED DATE</label>
                                                            <input type="date" required name="expected_date" disabled
                                                                id='expected_date' class="form-control select_cancel">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-5 col-md-5">
                                                        <div class="form-group">
                                                            <label class="form-label">LISTED PRICE</label>
                                                            <input type="number" required name="listed_price"
                                                                id='listed_price' disabled
                                                                class="form-control select_cancel">
                                                        </div>
                                                    </div>
                                                    {{-- <div class="col-sm-4 col-md-4 showDayDispatch">
                                                        <div class="form-group">
                                                            <label class="form-label">List in DayDispatch</label>
                                                            <input type="checkbox" name="list_in_daydispatch"
                                                                id='list_in_daydispatch' class="form-control">
                                                        </div>
                                                    </div> --}}
                                                    @php
                                                        $ptype = 1;
                                                        $query = \App\user_setting::where(
                                                            'user_id',
                                                            Auth::user()->id,
                                                        )->first();
                                                        if (!empty($query)) {
                                                            $ptype = $query['penal_type'];
                                                        }

                                                        if ($ptype == 1) {
                                                            $phoneaccess = explode(',', Auth::user()->emp_access_phone);
                                                        } elseif ($ptype == 2) {
                                                            $phoneaccess = explode(',', Auth::user()->emp_access_web);
                                                        } elseif ($ptype == 3) {
                                                            $phoneaccess = explode(',', Auth::user()->emp_access_test);
                                                        } elseif ($ptype == 4) {
                                                            $phoneaccess = explode(',', Auth::user()->panel_type_4);
                                                        } elseif ($ptype == 5) {
                                                            $phoneaccess = explode(',', Auth::user()->panel_type_5);
                                                        } elseif ($ptype == 6) {
                                                            $phoneaccess = explode(',', Auth::user()->panel_type_6);
                                                        } else {
                                                            $phoneaccess = [];
                                                        }
                                                    @endphp
                                                    @if (in_array('76', $phoneaccess))
                                                        <?php
                                                        $dis = \App\User::with('daily_ass')
                                                            ->whereHas('userRole', function ($q) {
                                                                $q->where('name', 'Dispatcher');
                                                            })
                                                            ->where('deleted', 0)
                                                            ->get();
                                                        ?>
                                                        <div class="col-sm-3 col-md-3 my-auto">
                                                            <button class="btn btn-primary" type="button"
                                                                id="showingDispatchers" disabled>Assign
                                                                Dispatcher</button>
                                                        </div>
                                                        <div class="col-sm-4 col-md-4" id="showDispatchers"
                                                            style="display:none;">
                                                            <div class="form-group">
                                                                <label class="form-label">Dispatchers <span
                                                                        class="text-muted">(Optional)</span></label>
                                                                <select name="dis_id" id='dis_id'
                                                                    class="form-control">
                                                                    <option value="" selected disabled>Select
                                                                    </option>
                                                                    @foreach ($dis as $key => $dispa)
                                                                        <option value="{{ $dispa->id }}">
                                                                            {{ $dispa->slug ?? $dispa->name . ' ' . $dispa->last_name }}
                                                                            ({{ isset($dispa->daily_ass->total_quote) ? $dispa->daily_ass->total_quote . ' Left' : 'Unlimited' }})
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control "></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row auctionupdate mb-2"></div>
                                                <script>
                                                    $("#pstatus").on('change', function() {
                                                        if ($(this).val() == 9) {
                                                            $(".auctionupdate").html(`
                                                                <input type="hidden" value="9" name="pstatus222">
                                                                <input type="hidden" value="sheet_detail" name="sheet_detail">
                                                                <div class="col-md-12 text-center"><h4>Listed Sheet</h4></div>
                                                                <div class="col-md-4">
                                                                 @if ($label[410]->status == 1)
                                                                  <div class="Terminal-error">
                                                                    <label>Paid</label>
                                                                    <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[410]->name }}</div>
                                                                        <div class="popover-content">{{ $label[410]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                     <label>Paid</label>
                                                                    @endif
                                                                    <select name="auc_paid" id="auc_paid" class="form-control h-50">
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    @if ($label[411]->status == 1)
                                                                  <div class="Terminal-error">
                                                                     <label>Storage</label>
                                                                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[411]->name }}</div>
                                                                        <div class="popover-content">{{ $label[411]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                     <label>Storage</label>
                                                                    @endif
                                                                    <input class="form-control" id="auc_storage" name="auc_storage" placeholder="Storage" value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                  @if ($label[412]->status == 1)
                                                                  <div class="Terminal-error">
                                                                    <label>Listed Price</label>
                                                                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[412]->name }}</div>
                                                                        <div class="popover-content">{{ $label[412]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                      <label>Listed Price</label>
                                                                    @endif
                                                                    <input class="form-control" id="auc_listed_price" placeholder="Listed Price" name="auc_listed_price" value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                 @if ($label[413]->status == 1)
                                                                  <div class="Terminal-error">
                                                                    <label>Auction Update</label>
                                                                      <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[413]->name }}</div>
                                                                        <div class="popover-content">{{ $label[413]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                       <label>Auction Update</label>
                                                                    @endif
                                                                    <input class="form-control" id="auc_auction_update" placeholder="Auction Update" name="auc_auction_update"
                                                                           value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                  @if ($label[414]->status == 1)
                                                                  <div class="Terminal-error">
                                                                    <label>Title</label>
                                                                    <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[414]->name }}</div>
                                                                        <div class="popover-content">{{ $label[414]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                       <label>Title</label>
                                                                    @endif
                                                                    <select id="auc_title_keys" name="auc_title_keys" class="form-control h-50">
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                @if ($label[415]->status == 1)
                                                                  <div class="Terminal-error">
                                                                    <label>Key</label>
                                                                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[415]->name }}</div>
                                                                        <div class="popover-content">{{ $label[415]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                     <label>Key</label>
                                                                    @endif
                                                                    <select id="auc_keys" name="auc_keys" class="form-control h-50">
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                 @if ($label[416]->status == 1)
                                                                  <div class="Terminal-error">
                                                                    <label>Listed Count</label>
                                                                    <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[416]->name }}</div>
                                                                        <div class="popover-content">{{ $label[416]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                     <label>Listed Count</label>
                                                                    @endif
                                                                    <input class="form-control" id="auc_listed_count" placeholder="Listed Count" name="auc_listed_count" value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                 @if ($label[417]->status == 1)
                                                                  <div class="Terminal-error">
                                                                    <label>Old/New Price</label>
                                                                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[417]->name }}</div>
                                                                        <div class="popover-content">{{ $label[417]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                      <label>Old/New Price</label>
                                                                    @endif
                                                                    <input class="form-control" id="auc_price" placeholder="Old / New Price" name="auc_price"
                                                                           value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                 @if ($label[418]->status == 1)
                                                                  <div class="Terminal-error">
                                                                    <label>Vehicle Position</label>
                                                                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[418]->name }}</div>
                                                                        <div class="popover-content">{{ $label[418]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                      <label>Vehicle Position</label>
                                                                    @endif
                                                                    <input class="form-control" placeholder="Vehicle Position" id="auc_port" name="auc_port" value="">
                                                                </div>
                                                                <div class="col-md-12">
                                                                 @if ($label[419]->status == 1)
                                                                  <div class="Terminal-error">
                                                                    <label>Additional</label>
                                                                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[419]->name }}</div>
                                                                        <div class="popover-content">{{ $label[419]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                       <label>Additional</label>
                                                                    @endif
                                                                    <input class="form-control" id="auc_additional" placeholder="Additional" name="auc_additional" value="">
                                                                </div>
                                                            `);
                                                            $(document).ready(function() {
                                                                // Select all error icons within the document
                                                                var $errorIcons = $('.Terminal-error i');
                                                                var $openPopoverContent = null;

                                                                // Iterate over each error icon
                                                                $errorIcons.each(function() {
                                                                    var $errorIcon = $(this);
                                                                    var $popoverContent = $errorIcon.closest('.Terminal-error').siblings(
                                                                        '.popoverContent');
                                                                    // Toggle the popover on icon click
                                                                    $errorIcon.on('click', function(event) {
                                                                        event
                                                                            .stopPropagation(); // Prevent the document click event from firing immediately

                                                                        // Close the previously open popover content
                                                                        if ($openPopoverContent && !$openPopoverContent.is(
                                                                                $popoverContent)) {
                                                                            $openPopoverContent.hide();
                                                                        }

                                                                        // Toggle the current popover content
                                                                        $popoverContent.toggle();
                                                                        $openPopoverContent = $popoverContent;
                                                                    });
                                                                });

                                                                // Close the popover if clicked outside
                                                                $(document).on('click', function(event) {
                                                                    if ($openPopoverContent && !$errorIcons.is(event.target) && !
                                                                        $openPopoverContent.is(event.target) && $openPopoverContent
                                                                        .has(event.target).length === 0) {
                                                                        $openPopoverContent.hide();
                                                                        $openPopoverContent = null;
                                                                    }
                                                                });
                                                            });
                                                            $("#expected_date").attr('disabled', false);
                                                            $("#listed_price").attr('disabled', false);
                                                            $("#showingDispatchers").attr('disabled', false);
                                                        } else if ($(this).val() == 7) {
                                                            $("#expected_date").attr('disabled', false);
                                                            $("#listed_price").attr('disabled', true);
                                                            $("#showingDispatchers").attr('disabled', true);
                                                            $("#showDispatchers").hide();
                                                            $("#dis_id").children('option').removeAttr('selected');
                                                            $("#dis_id").children('option').eq(0).attr('selected', true);
                                                        } else {
                                                            $("#expected_date").attr('disabled', true);
                                                            $("#listed_price").attr('disabled', true);
                                                            $("#showingDispatchers").attr('disabled', true);
                                                            $("#showDispatchers").hide();
                                                            $("#dis_id").children('option').removeAttr('selected');
                                                            $("#dis_id").children('option').eq(0).attr('selected', true);
                                                            $(".auctionupdate").html('');
                                                        }
                                                    })
                                                </script>
                                                <div class="col-md-3">
                                                    @if ($label[512 - 1]->status == 1)
                                                        <div class="Terminal-error">
                                                            <label>Review</label>
                                                            <i id="errorIcon"
                                                                class="fas fa-info-circle fa-lg text-info info-icon"
                                                                style="cursor: pointer;"></i>
                                                        </div>
                                                        <div class="popoverContent" style="display: none;">
                                                            <div class="popover-title">{{ $label[512 - 1]->name }}</div>
                                                            <div class="popover-content">{{ $label[512 - 1]->display }}
                                                            </div>
                                                        </div>
                                                    @else
                                                        <label>Review</label>
                                                    @endif
                                                    <select id="auc_reviewss" name="auc_review"
                                                        class="form-control h-50 auc_review" required>
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 all_rating" id="all_rating"
                                                    style="display:none;">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            @if ($label[513 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Website</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[513 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[513 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Website</label>
                                                            @endif
                                                            <select id="auc_website" name="auc_website"
                                                                class="form-control h-50">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="BBB">BBB</option>
                                                                <option value="Trust Pilot">Trust Pilot</option>
                                                                <option value="Google">Google</option>
                                                                <option value="Other">Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-6" style="display:none;"
                                                            id="other_website">
                                                            @if ($label[514 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Other Website</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[514 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[514 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Other Website</label>
                                                            @endif
                                                            <input class="form-control h-50" id="auc_website_other"
                                                                name="auc_website_other" placeholder="Other Website"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-6">
                                                            @if ($label[515 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Rating</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[515 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[515 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Rating</label>
                                                            @endif
                                                            <select id="auc_client_rating" name="auc_client_rating"
                                                                class="form-control h-50">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="Positive">Positive</option>
                                                                <option value="Neutral">Neutral</option>
                                                                <option value="Negative">Negative</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            @if ($label[516 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Website Link</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[516 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[516 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Website Link</label>
                                                            @endif
                                                            <input class="form-control h-50" id="auc_website_link"
                                                                name="auc_website_link" placeholder="Website Link"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label>Reviewer</label>
                                                            <input type="text" class="form-control" id=""
                                                                name="auc_reviewer" placeholder="Reviewer"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label>Attach Screenshot</label>
                                                            <input type="file" class="form-control" id=""
                                                                name="screenshot" placeholder="screenshot"
                                                                value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif
                                        @if (\Request::is('booked'))
                                            <form method="post" enctype="multipart/form-data"
                                                action="{{ route('call_history_post') }}" id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">BOOKED HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="8">BOOKED</option>
                                                                <option value="9">LISTED</option>
                                                                <option value="19">ONAPPROVAL CANCEL</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">EXPECTED DATE</label>
                                                            <input type="date" required name="expected_date" disabled
                                                                id='expected_date' class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-5 col-md-5">
                                                        <div class="form-group">
                                                            <label class="form-label">LISTED PRICE</label>
                                                            <input type="number" required name="listed_price"
                                                                id='listed_price' disabled class="form-control">
                                                        </div>
                                                    </div>
                                                    @php
                                                        $ptype = 1;
                                                        $query = \App\user_setting::where(
                                                            'user_id',
                                                            Auth::user()->id,
                                                        )->first();
                                                        if (!empty($query)) {
                                                            $ptype = $query['penal_type'];
                                                        }

                                                        if ($ptype == 1) {
                                                            $phoneaccess = explode(',', Auth::user()->emp_access_phone);
                                                        } elseif ($ptype == 2) {
                                                            $phoneaccess = explode(',', Auth::user()->emp_access_web);
                                                        } elseif ($ptype == 3) {
                                                            $phoneaccess = explode(',', Auth::user()->emp_access_test);
                                                        } elseif ($ptype == 4) {
                                                            $phoneaccess = explode(',', Auth::user()->panel_type_4);
                                                        } elseif ($ptype == 5) {
                                                            $phoneaccess = explode(',', Auth::user()->panel_type_5);
                                                        } elseif ($ptype == 6) {
                                                            $phoneaccess = explode(',', Auth::user()->panel_type_6);
                                                        } else {
                                                            $phoneaccess = [];
                                                        }
                                                    @endphp
                                                    @if (in_array('76', $phoneaccess))
                                                        <?php
                                                        $dis = \App\User::with('daily_ass')
                                                            ->whereHas('userRole', function ($q) {
                                                                $q->where('name', 'Dispatcher');
                                                            })
                                                            ->where('deleted', 0)
                                                            ->get();
                                                        ?>
                                                        <div class="col-sm-3 col-md-3 my-auto">
                                                            <button class="btn btn-primary" type="button"
                                                                id="showingDispatchers" disabled>Assign
                                                                Dispatcher</button>
                                                        </div>
                                                        <div class="col-sm-4 col-md-4" id="showDispatchers"
                                                            style="display:none;">
                                                            <div class="form-group">
                                                                <label class="form-label">Dispatchers <span
                                                                        class="text-muted">(Optional)</span></label>
                                                                <select name="dis_id" id='dis_id'
                                                                    class="form-control">
                                                                    <option value="" selected disabled>Select
                                                                    </option>
                                                                    @foreach ($dis as $key => $dispa)
                                                                        <option value="{{ $dispa->id }}">
                                                                            {{ $dispa->slug ?? $dispa->name . ' ' . $dispa->last_name }}
                                                                            ({{ isset($dispa->daily_ass->total_quote) ? $dispa->daily_ass->total_quote . ' Left' : 'Unlimited' }})
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row auctionupdate mb-2"></div>
                                                <script>
                                                    $("#pstatus").on('change', function() {
                                                        if ($(this).val() == 9) {
                                                            $(".auctionupdate").html(`
                                                                <input type="hidden" value="9" name="pstatus222">
                                                                <input type="hidden" value="sheet_detail" name="sheet_detail">
                                                                <div class="col-md-12 text-center"><h4>Listed Sheet</h4></div>
                                                                <div class="col-md-4">
                                                                 @if ($label[410]->status == 1)
                                                                  <div class="Terminal-error">
                                                                    <label>Paid</label>
                                                                    <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[410]->name }}</div>
                                                                        <div class="popover-content">{{ $label[410]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                     <label>Paid</label>
                                                                    @endif
                                                                    <select name="auc_paid" id="auc_paid" class="form-control h-50">
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    @if ($label[411]->status == 1)
                                                                  <div class="Terminal-error">
                                                                     <label>Storage</label>
                                                                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[411]->name }}</div>
                                                                        <div class="popover-content">{{ $label[411]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                     <label>Storage</label>
                                                                    @endif
                                                                    <input class="form-control" id="auc_storage" name="auc_storage" placeholder="Storage" value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                  @if ($label[412]->status == 1)
                                                                  <div class="Terminal-error">
                                                                    <label>Listed Price</label>
                                                                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[412]->name }}</div>
                                                                        <div class="popover-content">{{ $label[412]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                      <label>Listed Price</label>
                                                                    @endif
                                                                    <input class="form-control" id="auc_listed_price" placeholder="Listed Price" name="auc_listed_price" value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                 @if ($label[413]->status == 1)
                                                                  <div class="Terminal-error">
                                                                    <label>Auction Update</label>
                                                                      <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[413]->name }}</div>
                                                                        <div class="popover-content">{{ $label[413]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                       <label>Auction Update</label>
                                                                    @endif
                                                                    <input class="form-control" id="auc_auction_update" placeholder="Auction Update" name="auc_auction_update"
                                                                           value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                  @if ($label[414]->status == 1)
                                                                  <div class="Terminal-error">
                                                                    <label>Title</label>
                                                                    <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[414]->name }}</div>
                                                                        <div class="popover-content">{{ $label[414]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                       <label>Title</label>
                                                                    @endif
                                                                    <select id="auc_title_keys" name="auc_title_keys" class="form-control h-50">
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                @if ($label[415]->status == 1)
                                                                  <div class="Terminal-error">
                                                                    <label>Key</label>
                                                                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[415]->name }}</div>
                                                                        <div class="popover-content">{{ $label[415]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                     <label>Key</label>
                                                                    @endif
                                                                    <select id="auc_keys" name="auc_keys" class="form-control h-50">
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                 @if ($label[416]->status == 1)
                                                                  <div class="Terminal-error">
                                                                    <label>Listed Count</label>
                                                                    <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[416]->name }}</div>
                                                                        <div class="popover-content">{{ $label[416]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                     <label>Listed Count</label>
                                                                    @endif
                                                                    <input class="form-control" id="auc_listed_count" placeholder="Listed Count" name="auc_listed_count" value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                 @if ($label[417]->status == 1)
                                                                  <div class="Terminal-error">
                                                                    <label>Old/New Price</label>
                                                                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[417]->name }}</div>
                                                                        <div class="popover-content">{{ $label[417]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                      <label>Old/New Price</label>
                                                                    @endif
                                                                    <input class="form-control" id="auc_price" placeholder="Old / New Price" name="auc_price"
                                                                           value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                 @if ($label[418]->status == 1)
                                                                  <div class="Terminal-error">
                                                                    <label>Vehicle Position</label>
                                                                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[418]->name }}</div>
                                                                        <div class="popover-content">{{ $label[418]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                      <label>Vehicle Position</label>
                                                                    @endif
                                                                    <input class="form-control" placeholder="Vehicle Position" id="auc_port" name="auc_port" value="">
                                                                </div>
                                                                <div class="col-md-12">
                                                                 @if ($label[419]->status == 1)
                                                                  <div class="Terminal-error">
                                                                    <label>Additional</label>
                                                                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[419]->name }}</div>
                                                                        <div class="popover-content">{{ $label[419]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                       <label>Additional</label>
                                                                    @endif
                                                                    <input class="form-control" id="auc_additional" placeholder="Additional" name="auc_additional" value="">
                                                                </div>
                                                            `);
                                                            $(document).ready(function() {
                                                                // Select all error icons within the document
                                                                var $errorIcons = $('.Terminal-error i');
                                                                var $openPopoverContent = null;

                                                                // Iterate over each error icon
                                                                $errorIcons.each(function() {
                                                                    var $errorIcon = $(this);
                                                                    var $popoverContent = $errorIcon.closest('.Terminal-error').siblings(
                                                                        '.popoverContent');
                                                                    // Toggle the popover on icon click
                                                                    $errorIcon.on('click', function(event) {
                                                                        event
                                                                            .stopPropagation(); // Prevent the document click event from firing immediately

                                                                        // Close the previously open popover content
                                                                        if ($openPopoverContent && !$openPopoverContent.is(
                                                                                $popoverContent)) {
                                                                            $openPopoverContent.hide();
                                                                        }

                                                                        // Toggle the current popover content
                                                                        $popoverContent.toggle();
                                                                        $openPopoverContent = $popoverContent;
                                                                    });
                                                                });

                                                                // Close the popover if clicked outside
                                                                $(document).on('click', function(event) {
                                                                    if ($openPopoverContent && !$errorIcons.is(event.target) && !
                                                                        $openPopoverContent.is(event.target) && $openPopoverContent
                                                                        .has(event.target).length === 0) {
                                                                        $openPopoverContent.hide();
                                                                        $openPopoverContent = null;
                                                                    }
                                                                });
                                                            });
                                                            $("#expected_date").attr('disabled', false);
                                                            $("#listed_price").attr('disabled', false);
                                                            $("#showingDispatchers").attr('disabled', false);
                                                        } else if ($(this).val() == 8) {
                                                            $("#expected_date").attr('disabled', false);
                                                            $("#listed_price").attr('disabled', true);
                                                            $("#showingDispatchers").attr('disabled', true);
                                                            $("#showDispatchers").hide();
                                                            $("#dis_id").children('option').removeAttr('selected');
                                                            $("#dis_id").children('option').eq(0).attr('selected', true);
                                                        } else {
                                                            $("#expected_date").attr('disabled', true);
                                                            $("#listed_price").attr('disabled', true);
                                                            $("#showingDispatchers").attr('disabled', true);
                                                            $("#showDispatchers").hide();
                                                            $("#dis_id").children('option').removeAttr('selected');
                                                            $("#dis_id").children('option').eq(0).attr('selected', true);
                                                            $(".auctionupdate").html('');
                                                        }
                                                    })
                                                </script>
                                                <div class="col-md-3">
                                                    @if ($label[512 - 1]->status == 1)
                                                        <div class="Terminal-error">
                                                            <label>Review</label>
                                                            <i id="errorIcon"
                                                                class="fas fa-info-circle fa-lg text-info info-icon"
                                                                style="cursor: pointer;"></i>
                                                        </div>
                                                        <div class="popoverContent" style="display: none;">
                                                            <div class="popover-title">{{ $label[512 - 1]->name }}</div>
                                                            <div class="popover-content">{{ $label[512 - 1]->display }}
                                                            </div>
                                                        </div>
                                                    @else
                                                        <label>Review</label>
                                                    @endif
                                                    <select id="auc_reviewss" name="auc_review"
                                                        class="form-control h-50 auc_review" required>
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 all_rating" id="all_rating"
                                                    style="display:none;">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            @if ($label[513 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Website</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[513 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[513 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Website</label>
                                                            @endif
                                                            <select id="auc_website" name="auc_website"
                                                                class="form-control h-50">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="BBB">BBB</option>
                                                                <option value="Trust Pilot">Trust Pilot</option>
                                                                <option value="Google">Google</option>
                                                                <option value="Other">Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-6" style="display:none;"
                                                            id="other_website">
                                                            @if ($label[514 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Other Website</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[514 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[514 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Other Website</label>
                                                            @endif
                                                            <input class="form-control h-50" id="auc_website_other"
                                                                name="auc_website_other" placeholder="Other Website"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-6">
                                                            @if ($label[515 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Rating</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[515 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[515 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Rating</label>
                                                            @endif
                                                            <select id="auc_client_rating" name="auc_client_rating"
                                                                class="form-control h-50">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="Positive">Positive</option>
                                                                <option value="Neutral">Neutral</option>
                                                                <option value="Negative">Negative</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            @if ($label[516 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Website Link</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[516 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[516 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Website Link</label>
                                                            @endif
                                                            <input class="form-control h-50" id="auc_website_link"
                                                                name="auc_website_link" placeholder="Website Link"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label>Reviewer</label>
                                                            <input type="text" class="form-control" id=""
                                                                name="auc_reviewer" placeholder="Reviewer"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label>Attach Screenshot</label>
                                                            <input type="file" class="form-control" id=""
                                                                name="screenshot" placeholder="screenshot"
                                                                value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif
                                        @if (\Request::is('listed'))
                                            <form id="listedform" method="post"
                                                action="{{ route('call_history_post_relist') }}" id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">LISTED HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row" id="row1">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select required name="pstatus" id='pstatus'
                                                                class="form-control  getcarrier">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="9">Listed</option>
                                                                <option value="10">Schedule</option>
                                                                <option value="19">ONAPPROVAL CANCEL</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Select Carrier
                                                                <a href="javascript:;"
                                                                    onclick="this.href='/carrier_add/'+ document.getElementById('order_id1').value"
                                                                    type="button" target="_blank"
                                                                    class="btn btn-info btn-sm">UPDATE CARRIER</a>

                                                            </label>
                                                            <select id="current_carrier"
                                                                class="form-control select_cancel"
                                                                name="current_carrier" required style=" height: auto; "
                                                                disabled
                                                                data-validation-required-message="This field is required">
                                                                <option value="">Please Add Carrier</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-5 col-md-5">
                                                        <div class="form-group">
                                                            <label class="form-label">EXPECTED DATE</label>
                                                            <input type="date" required name="expected_date" disabled
                                                                id='expected_date' class="form-control select_cancel">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="row">
                                                                    <div class="col-sm-4 my-auto">
                                                                        <div class="form-group d-flex m-auto">
                                                                            <input type="checkbox" disabled
                                                                                class="mr-2 already_late"
                                                                                name="already_late1" value="1">
                                                                            <label class="form-label my-auto">Already
                                                                                Storage Price</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4 col-md-4 auction_already_late"
                                                                        style="display:none;">
                                                                        <div class="form-group">
                                                                            <input type="text" name="already_storage"
                                                                                id='already_storage'
                                                                                class="form-control"
                                                                                placeholder="Enter Already Storage Price">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <div class="row">
                                                                    <div class="col-sm-4 my-auto">
                                                                        <div class="form-group d-flex m-auto">
                                                                            <input type="checkbox" disabled
                                                                                class="mr-2 already_late"
                                                                                name="already_late2" value="1">
                                                                            <label class="form-label my-auto">Late Pickup
                                                                                Storage Price</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4 col-md-4 auction_already_late"
                                                                        style="display:none;">
                                                                        <div class="form-group">
                                                                            <input type="text"
                                                                                name="late_pickup_storage"
                                                                                id='late_pickup_storage'
                                                                                class="form-control"
                                                                                placeholder="Enter Late Pickup Storage Price">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="row auctionupdate mb-2"></div>
                                                        <script>
                                                            $("#pstatus").on('change', function() {
                                                                if ($(this).val() == 10) {
                                                                    $(".auctionupdate").html(`
                                                                        <input type="hidden" value="10" name="pstatus222">
                                                                        <input type="hidden" value="sheet_detail" name="sheet_detail">
                                                                        <div class="col-md-12 text-center"><h4>Scheduling Sheet</h4></div>
                                                                        <div class="col-md-4">
                                                                         @if ($label[425 - 1]->status == 1)
                                                                         <div class="Terminal-error">
                                                                         <label>Pickedup Time</label>
                                                                         <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                            style="cursor: pointer;"></i>
                                                                            </div>
                                                                            <div class="popoverContent" style="display: none;">
                                                                                <div class="popover-title">{{ $label[425 - 1]->name }}</div>
                                                                                <div class="popover-content">{{ $label[425 - 1]->display }}</div>
                                                                            </div>
                                                                            @else
                                                                               <label>Pickedup Time</label>
                                                                            @endif
                                                                            <input class="form-control" type="datetime-local" id="auc_pickedup" name="auc_pickedup" placeholder="=PickedUp time" required>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                         @if ($label[426 - 1]->status == 1)
                                                                         <div class="Terminal-error">
                                                                            <label>Delivery Time</label>
                                                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                            style="cursor: pointer;"></i>
                                                                            </div>
                                                                            <div class="popoverContent" style="display: none;">
                                                                                <div class="popover-title">{{ $label[426 - 1]->name }}</div>
                                                                                <div class="popover-content">{{ $label[426 - 1]->display }}</div>
                                                                            </div>
                                                                            @else
                                                                               <label>Delivery Time</label>
                                                                            @endif
                                                                            <input class="form-control" type="datetime-local" id="auc_delivery_date" name="auc_delivery_date" placeholder="=Delivery time" required>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                          @if ($label[427 - 1]->status == 1)
                                                                         <div class="Terminal-error">
                                                                            <label>Dispatch Price</label>
                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                            style="cursor: pointer;"></i>
                                                                            </div>
                                                                            <div class="popoverContent" style="display: none;">
                                                                                <div class="popover-title">{{ $label[427 - 1]->name }}</div>
                                                                                <div class="popover-content">{{ $label[427 - 1]->display }}</div>
                                                                            </div>
                                                                            @else
                                                                                <label>Dispatch Price</label>
                                                                            @endif
                                                                            <input class="form-control" type="text" id="auc_price" name="auc_price" placeholder="Dispatch Price" required>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                         @if ($label[428 - 1]->status == 1)
                                                                         <div class="Terminal-error">
                                                                            <label>Vehicle Condition</label>
                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                            style="cursor: pointer;"></i>
                                                                            </div>
                                                                            <div class="popoverContent" style="display: none;">
                                                                                <div class="popover-title">{{ $label[428 - 1]->name }}</div>
                                                                                <div class="popover-content">{{ $label[428 - 1]->display }}</div>
                                                                            </div>
                                                                            @else
                                                                                <label>Vehicle Condition</label>
                                                                            @endif
                                                                            <input class="form-control" type="text" id="auc_condition" name="auc_condition" placeholder="Vehicle Condition" required>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                         @if ($label[429 - 1]->status == 1)
                                                                         <div class="Terminal-error">
                                                                            <label>Storage</label>
                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                            style="cursor: pointer;"></i>
                                                                            </div>
                                                                            <div class="popoverContent" style="display: none;">
                                                                                <div class="popover-title">{{ $label[429 - 1]->name }}</div>
                                                                                <div class="popover-content">{{ $label[429 - 1]->display }}</div>
                                                                            </div>
                                                                            @else
                                                                                <label>Storage</label>
                                                                            @endif
                                                                            <input class="form-control" id="auc_storage" name="auc_storage" placeholder="Storage" value="" required>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                          @if ($label[430 - 1]->status == 1)
                                                                         <div class="Terminal-error">
                                                                            <label>Driver FMCSA (Active)?</label>
                                                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                            style="cursor: pointer;"></i>
                                                                            </div>
                                                                            <div class="popoverContent" style="display: none;">
                                                                                <div class="popover-title">{{ $label[430 - 1]->name }}</div>
                                                                                <div class="popover-content">{{ $label[430 - 1]->display }}</div>
                                                                            </div>
                                                                            @else
                                                                                <label>Driver FMCSA (Active)?</label>
                                                                            @endif
                                                                            <select id="auc_driver_fmcsa" name="auc_driver_fmcsa" class="form-control h-50" required>
                                                                                <option value="" selected disabled>Select</option>
                                                                                <option value="Yes">Yes</option>
                                                                                <option value="No">No</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                        @if ($label[431 - 1]->status == 1)
                                                                         <div class="Terminal-error">
                                                                            <label>Carrier Rating</label>
                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                            style="cursor: pointer;"></i>
                                                                            </div>
                                                                            <div class="popoverContent" style="display: none;">
                                                                                <div class="popover-title">{{ $label[431 - 1]->name }}</div>
                                                                                <div class="popover-content">{{ $label[431 - 1]->display }}</div>
                                                                            </div>
                                                                            @else
                                                                               <label>Carrier Rating</label>
                                                                            @endif
                                                                            <input class="form-control" id="auc_carrier_rating" name="auc_carrier_rating" placeholder="Carrier Rating" value="" required>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                         @if ($label[432 - 1]->status == 1)
                                                                         <div class="Terminal-error">
                                                                            <label>Verify FMCSA?</label>
                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                            style="cursor: pointer;"></i>
                                                                            </div>
                                                                            <div class="popoverContent" style="display: none;">
                                                                                <div class="popover-title">{{ $label[432 - 1]->name }}</div>
                                                                                <div class="popover-content">{{ $label[432 - 1]->display }}</div>
                                                                            </div>
                                                                            @else
                                                                               <label>Verify FMCSA?</label>
                                                                            @endif
                                                                            <input class="form-control" id="auc_fmcsa" name="auc_fmcsa" placeholder="FMCSA" value="" required>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                        @if ($label[433 - 1]->status == 1)
                                                                         <div class="Terminal-error">
                                                                            <label>Date Of Insurance (FMCSA)</label>
                                                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                            style="cursor: pointer;"></i>
                                                                            </div>
                                                                            <div class="popoverContent" style="display: none;">
                                                                                <div class="popover-title">{{ $label[433 - 1]->name }}</div>
                                                                                <div class="popover-content">{{ $label[433 - 1]->display }}</div>
                                                                            </div>
                                                                            @else
                                                                               <label>Date Of Insurance (FMCSA)</label>
                                                                            @endif
                                                                            <input class="form-control" type="date" id="auc_insurance_date" name="auc_insurance_date" placeholder="Date Of Insurance (FMCSA)" value="" required>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                         @if ($label[434 - 1]->status == 1)
                                                                         <div class="Terminal-error">
                                                                            <label>COI Holder</label>
                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                            style="cursor: pointer;"></i>
                                                                            </div>
                                                                            <div class="popoverContent" style="display: none;">
                                                                                <div class="popover-title">{{ $label[434 - 1]->name }}</div>
                                                                                <div class="popover-content">{{ $label[434 - 1]->display }}</div>
                                                                            </div>
                                                                            @else
                                                                               <label>Date Of Insurance (FMCSA)</label>
                                                                            @endif
                                                                            <select id="auc_coi_holder" name="auc_coi_holder" class="form-control h-50" required>
                                                                                <option value="" selected disabled>Select</option>
                                                                                <option value="Yes">Yes</option>
                                                                                <option value="No">No</option>
                                                                                <option value="Waiting">Waiting</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                         @if ($label[435 - 1]->status == 1)
                                                                         <div class="Terminal-error">
                                                                            <label>Is Vehicle Luxury?</label>
                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                            style="cursor: pointer;"></i>
                                                                            </div>
                                                                            <div class="popoverContent" style="display: none;">
                                                                                <div class="popover-title">{{ $label[435 - 1]->name }}</div>
                                                                                <div class="popover-content">{{ $label[435 - 1]->display }}</div>
                                                                            </div>
                                                                            @else
                                                                              <label>Is Vehicle Luxury?</label>
                                                                            @endif
                                                                            <select id="auc_vehicle_luxury" name="auc_vehicle_luxury" class="form-control h-50" required>
                                                                                <option value="" selected disabled>Select</option>
                                                                                <option value="Yes">Yes</option>
                                                                                <option value="No">No</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                         @if ($label[436 - 1]->status == 1)
                                                                         <div class="Terminal-error">
                                                                            <label>Aware Driver Delivery</label>
                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                            style="cursor: pointer;"></i>
                                                                            </div>
                                                                            <div class="popoverContent" style="display: none;">
                                                                                <div class="popover-title">{{ $label[436 - 1]->name }}</div>
                                                                                <div class="popover-content">{{ $label[436 - 1]->display }}</div>
                                                                            </div>
                                                                            @else
                                                                               <label>Aware Driver Delivery</label>
                                                                            @endif
                                                                            <input class="form-control" type="text" id="auc_aware_driver_delivery_date" name="auc_aware_driver_delivery_date" required placeholder="Aware Driver Delivery">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                          @if ($label[437 - 1]->status == 1)
                                                                         <div class="Terminal-error">
                                                                            <label>New/Old Driver</label>
                                                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                            style="cursor: pointer;"></i>
                                                                            </div>
                                                                            <div class="popoverContent" style="display: none;">
                                                                                <div class="popover-title">{{ $label[437 - 1]->name }}</div>
                                                                                <div class="popover-content">{{ $label[437 - 1]->display }}</div>
                                                                            </div>
                                                                            @else
                                                                                <label>New/Old Driver</label>
                                                                            @endif
                                                                            <select id="auc_new_old_driver" name="auc_new_old_driver" class="form-control h-50" required>
                                                                                <option value="" selected disabled>Select</option>
                                                                                <option value="Old Driver">Old Driver</option>
                                                                                <option value="New Driver">New Driver</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                         @if ($label[438 - 1]->status == 1)
                                                                         <div class="Terminal-error">
                                                                            <label>Is Local?</label>
                                                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                            style="cursor: pointer;"></i>
                                                                            </div>
                                                                            <div class="popoverContent" style="display: none;">
                                                                                <div class="popover-title">{{ $label[438 - 1]->name }}</div>
                                                                                <div class="popover-content">{{ $label[438 - 1]->display }}</div>
                                                                            </div>
                                                                            @else
                                                                                <label>Is Local?</label>
                                                                            @endif
                                                                            <select id="auc_is_local" name="auc_is_local" class="form-control h-50" required>
                                                                                <option value="" selected disabled>Select</option>
                                                                                <option value="Yes">Yes</option>
                                                                                <option value="No">No</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                         @if ($label[439 - 1]->status == 1)
                                                                         <div class="Terminal-error">
                                                                            <label>Job Accept</label>
                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                            style="cursor: pointer;"></i>
                                                                            </div>
                                                                            <div class="popoverContent" style="display: none;">
                                                                                <div class="popover-title">{{ $label[439 - 1]->name }}</div>
                                                                                <div class="popover-content">{{ $label[439 - 1]->display }}</div>
                                                                            </div>
                                                                            @else
                                                                                  <label>Job Accept</label>
                                                                            @endif
                                                                            <input class="form-control" id="auc_job_accept" name="auc_job_accept" placeholder="Job Accept" value="" required>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                          @if ($label[440 - 1]->status == 1)
                                                                         <div class="Terminal-error">
                                                                            <label>Title</label>
                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                            style="cursor: pointer;"></i>
                                                                            </div>
                                                                            <div class="popoverContent" style="display: none;">
                                                                                <div class="popover-title">{{ $label[440 - 1]->name }}</div>
                                                                                <div class="popover-content">{{ $label[440 - 1]->display }}</div>
                                                                            </div>
                                                                            @else
                                                                                  <label>Title</label>
                                                                            @endif
                                                                            <select id="auc_title_keys" name="auc_title_keys" class="form-control h-50" required>
                                                                                <option value="" selected disabled>Select</option>
                                                                                <option value="Yes">Yes</option>
                                                                                <option value="No">No</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                         @if ($label[441 - 1]->status == 1)
                                                                         <div class="Terminal-error">
                                                                            <label>Key</label>
                                                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                            style="cursor: pointer;"></i>
                                                                            </div>
                                                                            <div class="popoverContent" style="display: none;">
                                                                                <div class="popover-title">{{ $label[441 - 1]->name }}</div>
                                                                                <div class="popover-content">{{ $label[441 - 1]->display }}</div>
                                                                            </div>
                                                                            @else
                                                                                 <label>Key</label>
                                                                            @endif
                                                                            <select id="auc_keys" name="auc_keys" class="form-control h-50" required>
                                                                                <option value="" selected disabled>Select</option>
                                                                                <option value="Yes">Yes</option>
                                                                                <option value="No">No</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                          @if ($label[442 - 1]->status == 1)
                                                                         <div class="Terminal-error">
                                                                            <label>Auction Update</label>
                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                            style="cursor: pointer;"></i>
                                                                            </div>
                                                                            <div class="popoverContent" style="display: none;">
                                                                                <div class="popover-title">{{ $label[442 - 1]->name }}</div>
                                                                                <div class="popover-content">{{ $label[442 - 1]->display }}</div>
                                                                            </div>
                                                                            @else
                                                                                 <label>Auction Update</label>
                                                                            @endif
                                                                            <input id="auc_auction_update" name="auc_auction_update" class="form-control" placeholder="Auction Update" value="" required>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                         @if ($label[443 - 1]->status == 1)
                                                                         <div class="Terminal-error">
                                                                            <label>Storage Pay</label>
                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                            style="cursor: pointer;"></i>
                                                                            </div>
                                                                            <div class="popoverContent" style="display: none;">
                                                                                <div class="popover-title">{{ $label[443 - 1]->name }}</div>
                                                                                <div class="popover-content">{{ $label[443 - 1]->display }}</div>
                                                                            </div>
                                                                            @else
                                                                                  <label>Storage Pay</label>
                                                                            @endif
                                                                            <select id="auc_who_pay_storage" name="auc_who_pay_storage" class="form-control h-50" required>
                                                                                <option value="" selected disabled>Select</option>
                                                                                <option value="Yes">Yes</option>
                                                                                <option value="No">No</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                         @if ($label[444 - 1]->status == 1)
                                                                         <div class="Terminal-error">
                                                                            <label>Vehicle Position</label>
                                                                                <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                            style="cursor: pointer;"></i>
                                                                            </div>
                                                                            <div class="popoverContent" style="display: none;">
                                                                                <div class="popover-title">{{ $label[444 - 1]->name }}</div>
                                                                                <div class="popover-content">{{ $label[444 - 1]->display }}</div>
                                                                            </div>
                                                                            @else
                                                                                  <label>Vehicle Position</label>
                                                                            @endif
                                                                            <input id="auc_vehicle_position" name="auc_vehicle_position" class="form-control" placeholder="Vehicle Position" value="" required>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                         @if ($label[445 - 1]->status == 1)
                                                                         <div class="Terminal-error">
                                                                            <label>Payment Method</label>
                                                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                            style="cursor: pointer;"></i>
                                                                            </div>
                                                                            <div class="popoverContent" style="display: none;">
                                                                                <div class="popover-title">{{ $label[445 - 1]->name }}</div>
                                                                                <div class="popover-content">{{ $label[445 - 1]->display }}</div>
                                                                            </div>
                                                                            @else
                                                                                   <label>Payment Method</label>
                                                                            @endif
                                                                            <input class="form-control" id="auc_payment_method" name="auc_payment_method" placeholder="Payment Method" value="" required>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                         @if ($label[446 - 1]->status == 1)
                                                                         <div class="Terminal-error">
                                                                            <label>Additional</label>
                                                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                            style="cursor: pointer;"></i>
                                                                            </div>
                                                                            <div class="popoverContent" style="display: none;">
                                                                                <div class="popover-title">{{ $label[446 - 1]->name }}</div>
                                                                                <div class="popover-content">{{ $label[446 - 1]->display }}</div>
                                                                            </div>
                                                                            @else
                                                                                    <label>Additional</label>
                                                                            @endif
                                                                            <input class="form-control" id="auc_additional" placeholder="Additional" name="auc_additional" value="" required>
                                                                        </div>
                                                                    `);
                                                                    $(document).ready(function() {
                                                                        // Select all error icons within the document
                                                                        var $errorIcons = $('.Terminal-error i');
                                                                        var $openPopoverContent = null;

                                                                        // Iterate over each error icon
                                                                        $errorIcons.each(function() {
                                                                            var $errorIcon = $(this);
                                                                            var $popoverContent = $errorIcon.closest('.Terminal-error').siblings(
                                                                                '.popoverContent');
                                                                            // Toggle the popover on icon click
                                                                            $errorIcon.on('click', function(event) {
                                                                                event
                                                                                    .stopPropagation(); // Prevent the document click event from firing immediately

                                                                                // Close the previously open popover content
                                                                                if ($openPopoverContent && !$openPopoverContent.is(
                                                                                        $popoverContent)) {
                                                                                    $openPopoverContent.hide();
                                                                                }

                                                                                // Toggle the current popover content
                                                                                $popoverContent.toggle();
                                                                                $openPopoverContent = $popoverContent;
                                                                            });
                                                                        });

                                                                        // Close the popover if clicked outside
                                                                        $(document).on('click', function(event) {
                                                                            if ($openPopoverContent && !$errorIcons.is(event.target) && !
                                                                                $openPopoverContent.is(event.target) && $openPopoverContent
                                                                                .has(event.target).length === 0) {
                                                                                $openPopoverContent.hide();
                                                                                $openPopoverContent = null;
                                                                            }
                                                                        });
                                                                    });
                                                                    $("#expected_date").attr('disabled', false);
                                                                    $("#current_carrier").attr('disabled', false);
                                                                    $("input[name='already_late1']").attr('disabled', false);
                                                                    $("input[name='already_late2']").attr('disabled', false);
                                                                } else if ($(this).val() == 9) {
                                                                    $("#expected_date").attr('disabled', false);
                                                                    $("#current_carrier").attr('disabled', true);
                                                                    $("input[name='already_late1']").attr('disabled', true);
                                                                    $("input[name='already_late1']").removeAttr('checked');
                                                                    $("#already_storage").hide();
                                                                    $("input[name='already_late2']").attr('disabled', true);
                                                                    $("input[name='already_late2']").removeAttr('checked');
                                                                    $("#late_pickup_storage").hide();
                                                                } else {
                                                                    $("#expected_date").attr('disabled', true);
                                                                    $("#current_carrier").attr('disabled', true);
                                                                    $(".auctionupdate").html('');
                                                                    $("input[name='already_late1']").attr('disabled', true);
                                                                    $("input[name='already_late1']").removeAttr('checked');
                                                                    $("#already_storage").hide();
                                                                    $("input[name='already_late2']").attr('disabled', true);
                                                                    $("input[name='already_late2']").removeAttr('checked');
                                                                    $("#late_pickup_storage").hide();
                                                                }
                                                            })
                                                        </script>
                                                        <script>
                                                            $(".already_late").on('change', function() {
                                                                if ($(this).is(":checked")) {
                                                                    $(this).parent('div').parent('div').siblings('.auction_already_late').show();
                                                                } else {
                                                                    $(this).parent('div').parent('div').siblings('.auction_already_late').hide();
                                                                }
                                                            })
                                                        </script>
                                                    </div>
                                                </div>
                                                <div class="row" id="row2" style="display: none">
                                                    <div class="col-sm-1 col-md-1">
                                                        <div class="form-group">
                                                            <label class="form-label">Relist</label>
                                                            <input style="width: 20px;height: 20px"
                                                                onclick="showprice()" type="checkbox"
                                                                class="select_cancel" name="relist" id='relist'>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-6 col-md-6" id="r1"
                                                        style="display: none">
                                                        <div class="form-group">
                                                            <label class="form-label">New Relist Price</label>
                                                            <input type="number" name="listed_price" id='relist_id'
                                                                class="form-control">
                                                        </div>
                                                    </div>


                                                </div>

                                                <div class="col-md-3">
                                                    @if ($label[512 - 1]->status == 1)
                                                        <div class="Terminal-error">
                                                            <label>Review</label>
                                                            <i id="errorIcon"
                                                                class="fas fa-info-circle fa-lg text-info info-icon"
                                                                style="cursor: pointer;"></i>
                                                        </div>
                                                        <div class="popoverContent" style="display: none;">
                                                            <div class="popover-title">{{ $label[512 - 1]->name }}</div>
                                                            <div class="popover-content">{{ $label[512 - 1]->display }}
                                                            </div>
                                                        </div>
                                                    @else
                                                        <label>Review</label>
                                                    @endif
                                                    <select id="auc_reviewss" name="auc_review"
                                                        class="form-control h-50 auc_review" required>
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 all_rating" id="all_rating"
                                                    style="display:none;">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            @if ($label[513 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Website</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[513 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[513 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Website</label>
                                                            @endif
                                                            <select id="auc_website" name="auc_website"
                                                                class="form-control h-50">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="BBB">BBB</option>
                                                                <option value="Trust Pilot">Trust Pilot</option>
                                                                <option value="Google">Google</option>
                                                                <option value="Other">Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-6" style="display:none;"
                                                            id="other_website">
                                                            @if ($label[514 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Other Website</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[514 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[514 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Other Website</label>
                                                            @endif
                                                            <input class="form-control h-50" id="auc_website_other"
                                                                name="auc_website_other" placeholder="Other Website"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-6">
                                                            @if ($label[515 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Rating</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[515 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[515 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Rating</label>
                                                            @endif
                                                            <select id="auc_client_rating" name="auc_client_rating"
                                                                class="form-control h-50">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="Positive">Positive</option>
                                                                <option value="Neutral">Neutral</option>
                                                                <option value="Negative">Negative</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            @if ($label[516 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Website Link</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[516 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[516 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Website Link</label>
                                                            @endif
                                                            <input class="form-control h-50" id="auc_website_link"
                                                                name="auc_website_link" placeholder="Website Link"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label>Reviewer</label>
                                                            <input type="text" class="form-control" id=""
                                                                name="auc_reviewer" placeholder="Reviewer"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label>Attach Screenshot</label>
                                                            <input type="file" class="form-control" id=""
                                                                name="screenshot" placeholder="screenshot"
                                                                value="">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">HISTORY</label>
                                                        <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif
                                        @if (\Request::is('schedule_for_delivery'))
                                            <form id="listedform" method="post"
                                                action="{{ route('call_history_post') }}" id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">
                                                    UPDATE HISTORY
                                                </div>
                                                <div class="row" id="row1">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>
                                                    <input type="hidden" class="form-control" name="pstatus"
                                                        value='12' placeholder="" readonly>
                                                </div>

                                                <div class="col-sm-6 col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">HISTORY</label>
                                                        <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif

                                        @if (\Request::is('dispatch'))
                                            <form method="post" enctype="multipart/form-data"
                                                action="{{ route('call_history_post') }}" id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">Schedule HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row" id="dipatchpickup">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control  getpickupdate">
                                                                {{-- <option value="" selected disabled>Select</option> --}}
                                                                <option value="10" selected>Schedule</option>
                                                                <option value="34">Schedule To Another Driver</option>
                                                                <option value="11">Pickup</option>

                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Select Carrier
                                                                <a href="javascript:;"
                                                                    onclick="this.href='/carrier_add/'+ document.getElementById('order_id1').value"
                                                                    type="button" target="_blank"
                                                                    class="btn btn-info btn-sm" id="carrier_add">UPDATE
                                                                    CARRIER</a>

                                                            </label>
                                                            <select id="current_carrier"
                                                                class="form-control select_cancel"
                                                                name="current_carrier" required style=" height: auto; "
                                                                data-validation-required-message="This field is required">
                                                                <option value="">Please Add Carrier</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!--<div class="col-sm-6 col-md-6">-->
                                                    <!--    <div class="form-group">-->
                                                    <!--        <label class="form-label">EXPECTED DATE / DELIVER-->
                                                    <!--            DATE</label>-->
                                                    <!--        <input type="date" required name="expected_date"-->
                                                    <!--               id='expected_date' -->
                                                    <!--               class="form-control">-->
                                                    <!--    </div>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6 col-md-6 pickupdatediv"></div>-->
                                                    <div class="col-sm-6 col-md-6 pickupdatediv">
                                                    </div>
                                                    <div class="col-sm-12 col-md-12">
                                                        <div id="vehicle_condition" style="display: flex; width: 100%;">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row auctionupdate mb-2"></div>
                                                <script>
                                                    $("#pstatus").on('change', function() {
                                                        if ($(this).val() == 11) {
                                                            $("#carrier_add").attr('disabled', true);
                                                            var order_id = $("#order_id1").val();
                                                            $.ajax({
                                                                url: "{{ url('/get_sheet') }}",
                                                                type: "GET",
                                                                data: {
                                                                    order_id: order_id
                                                                },
                                                                dataType: "JSON",
                                                                success: function(res) {
                                                                    var pickup = '';
                                                                    if (res.pickup_date) {
                                                                        pickup = res.pickup_date;
                                                                    }
                                                                    $(".pickupdatediv").html(`
                                                                        <div class="form-group">
                                                                            <label class="form-label">PICKUP DATE</label>
                                                                            <input type="datetime-local" value="${pickup}" required name="pickup_date"
                                                                            id='pickup_date' class="form-control">
                                                                            <input type="checkbox" name="approvalpickup" value="1"/>MARK AS APPROVED
                                                                        </div>
                                                                    `);
                                                                }
                                                            })
                                                            $.ajax({
                                                                url: "{{ url('/get_carrier2') }}",
                                                                type: "GET",
                                                                data: {
                                                                    order_id: order_id
                                                                },
                                                                dataType: "JSON",
                                                                success: function(res) {
                                                                    var phone1 = '';
                                                                    if (res[0].driverphoneno) {
                                                                        phone1 = res[0].driverphoneno;
                                                                    }
                                                                    var name = '';
                                                                    if (res[0].companyname) {
                                                                        name = res[0].companyname;
                                                                    }
                                                                    $(".auctionupdate").html(`
                                                                        <input type="hidden" value="11" name="pstatus222">
                                                                        <input type="hidden" value="sheet_detail" name="sheet_detail">
                                                                        <div class="col-md-12 text-center"><h2>Pickup Sheet</h2></div>
                                                                        <div class="col-sm-12">
                                                                            <div class="card">
                                                                                <div class="card-header justify-content-center"><h3 class="m-auto">Auction Status</h3></div>
                                                                                <div class="card-body">
                                                                                    <div class="row">
                                                                                        <div class="col-md-6">
                                                                                         @if ($label[469 - 1]->status == 1)
                                                                                         <div class="Terminal-error">
                                                                                            <label>Auction Statue</label>
                                                                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                            style="cursor: pointer;"></i>
                                                                                            </div>
                                                                                            <div class="popoverContent" style="display: none;">
                                                                                                <div class="popover-title">{{ $label[469 - 1]->name }}</div>
                                                                                                <div class="popover-content">{{ $label[469 - 1]->display }}</div>
                                                                                            </div>
                                                                                            @else
                                                                                                    <label>Auction Statue</label>
                                                                                            @endif
                                                                                            <input class="form-control" id="auc_auction_status1" name="auc_auction_status1" placeholder="Auction Status" value="" required>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                        @if ($label[470 - 1]->status == 1)
                                                                                         <div class="Terminal-error">
                                                                                            <label>Storage</label>
                                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                            style="cursor: pointer;"></i>
                                                                                            </div>
                                                                                            <div class="popoverContent" style="display: none;">
                                                                                                <div class="popover-title">{{ $label[470 - 1]->name }}</div>
                                                                                                <div class="popover-content">{{ $label[470 - 1]->display }}</div>
                                                                                            </div>
                                                                                            @else
                                                                                                    <label>Storage</label>
                                                                                            @endif
                                                                                            <input class="form-control" id="auc_storage1" name="auc_storage1" placeholder="Storage" value="" required>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                        @if ($label[471 - 1]->status == 1)
                                                                                         <div class="Terminal-error">
                                                                                            <label>Vehicle Condition</label>
                                                                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                            style="cursor: pointer;"></i>
                                                                                            </div>
                                                                                            <div class="popoverContent" style="display: none;">
                                                                                                <div class="popover-title">{{ $label[471 - 1]->name }}</div>
                                                                                                <div class="popover-content">{{ $label[471 - 1]->display }}</div>
                                                                                            </div>
                                                                                            @else
                                                                                                    <label>Vehicle Condition</label>
                                                                                            @endif
                                                                                            <input class="form-control" id="auc_condition1" placeholder="Vehicle Condition" name="auc_condition1" value="" required>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                         @if ($label[472 - 1]->status == 1)
                                                                                         <div class="Terminal-error">
                                                                                            <label>Title</label>
                                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                            style="cursor: pointer;"></i>
                                                                                            </div>
                                                                                            <div class="popoverContent" style="display: none;">
                                                                                                <div class="popover-title">{{ $label[472 - 1]->name }}</div>
                                                                                                <div class="popover-content">{{ $label[472 - 1]->display }}</div>
                                                                                            </div>
                                                                                            @else
                                                                                                     <label>Title</label>
                                                                                            @endif
                                                                                            <select id="auc_title_keys1" name="auc_title_keys1" class="form-control h-50" required>
                                                                                                <option value="" selected disabled>Select</option>
                                                                                                <option value="Yes">Yes</option>
                                                                                                <option value="No">No</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                        @if ($label[473 - 1]->status == 1)
                                                                                         <div class="Terminal-error">
                                                                                            <label>Key</label>
                                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                            style="cursor: pointer;"></i>
                                                                                            </div>
                                                                                            <div class="popoverContent" style="display: none;">
                                                                                                <div class="popover-title">{{ $label[473 - 1]->name }}</div>
                                                                                                <div class="popover-content">{{ $label[473 - 1]->display }}</div>
                                                                                            </div>
                                                                                            @else
                                                                                                     <label>Key</label>
                                                                                            @endif
                                                                                            <select id="auc_keys1" name="auc_keys1" class="form-control h-50" required>
                                                                                                <option value="" selected disabled>Select</option>
                                                                                                <option value="Yes">Yes</option>
                                                                                                <option value="No">No</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                         @if ($label[474 - 1]->status == 1)
                                                                                         <div class="Terminal-error">
                                                                                            <label>Vehicle Position</label>
                                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                            style="cursor: pointer;"></i>
                                                                                            </div>
                                                                                            <div class="popoverContent" style="display: none;">
                                                                                                <div class="popover-title">{{ $label[474 - 1]->name }}</div>
                                                                                                <div class="popover-content">{{ $label[474 - 1]->display }}</div>
                                                                                            </div>
                                                                                            @else
                                                                                                <label>Vehicle Position</label>
                                                                                            @endif
                                                                                            <input id="auc_vehicle_position1" name="auc_vehicle_position1" class="form-control" placeholder="Vehicle Position" value="" required>
                                                                                        </div>
                                                                                        <div class="col-md-12">
                                                                                             @if ($label[475 - 1]->status == 1)
                                                                                             <div class="Terminal-error">
                                                                                            <label>Additional</label>
                                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                            style="cursor: pointer;"></i>
                                                                                            </div>
                                                                                            <div class="popoverContent" style="display: none;">
                                                                                                <div class="popover-title">{{ $label[475 - 1]->name }}</div>
                                                                                                <div class="popover-content">{{ $label[475 - 1]->display }}</div>
                                                                                            </div>
                                                                                            @else
                                                                                                <label>Vehicle Position</label>
                                                                                            @endif
                                                                                            <input class="form-control" id="auc_additional1" placeholder="Additional" name="auc_additional1" value="" required>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="card">
                                                                                <div class="card-header justify-content-center"><h3 class="m-auto">Driver Status</h3></div>
                                                                                <div class="card-body">
                                                                                    <div class="row">
                                                                                        <div class="col-md-3">
                                                                                         @if ($label[476 - 1]->status == 1)
                                                                                             <div class="Terminal-error">
                                                                                            <label>Driver Status</label>
                                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                            style="cursor: pointer;"></i>
                                                                                            </div>
                                                                                            <div class="popoverContent" style="display: none;">
                                                                                                <div class="popover-title">{{ $label[476 - 1]->name }}</div>
                                                                                                <div class="popover-content">{{ $label[476 - 1]->display }}</div>
                                                                                            </div>
                                                                                            @else
                                                                                                 <label>Driver Status</label>
                                                                                            @endif
                                                                                            <input class="form-control" id="auc_driver_status" name="auc_driver_status" placeholder="Driver Status" value="" required>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                         @if ($label[476]->status == 1)
                                                                                             <div class="Terminal-error">
                                                                                            <label>Company Name</label>
                                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                            style="cursor: pointer;"></i>
                                                                                            </div>
                                                                                            <div class="popoverContent" style="display: none;">
                                                                                                <div class="popover-title">{{ $label[476]->name }}</div>
                                                                                                <div class="popover-content">{{ $label[476]->display }}</div>
                                                                                            </div>
                                                                                            @else
                                                                                                 <label>Company Name</label>
                                                                                            @endif
                                                                                            <input class="form-control" id="auc_company_name" name="auc_company_name" placeholder="Company Name" value="${name}" required>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                         @if ($label[478 - 1]->status == 1)
                                                                                             <div class="Terminal-error">
                                                                                            <label>Driver Name</label>
                                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                            style="cursor: pointer;"></i>
                                                                                            </div>
                                                                                            <div class="popoverContent" style="display: none;">
                                                                                                <div class="popover-title">{{ $label[478 - 1]->name }}</div>
                                                                                                <div class="popover-content">{{ $label[478 - 1]->display }}</div>
                                                                                            </div>
                                                                                            @else
                                                                                                <label>Driver Name</label>
                                                                                            @endif
                                                                                            <input class="form-control" id="auc_carrier_name" name="auc_carrier_name" placeholder="Driver Name" value="" required>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                         @if ($label[479 - 1]->status == 1)
                                                                                             <div class="Terminal-error">
                                                                                            <label>Driver Payment</label>
                                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                            style="cursor: pointer;"></i>
                                                                                            </div>
                                                                                            <div class="popoverContent" style="display: none;">
                                                                                                <div class="popover-title">{{ $label[479 - 1]->name }}</div>
                                                                                                <div class="popover-content">{{ $label[479 - 1]->display }}</div>
                                                                                            </div>
                                                                                            @else
                                                                                                <label>Driver Payment</label>
                                                                                            @endif
                                                                                            <input class="form-control" id="auc_driver_payment" name="auc_driver_payment" placeholder="Driver Payment" value="" required>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                         @if ($label[480 - 1]->status == 1)
                                                                                             <div class="Terminal-error">
                                                                                            <label>Driver No1#</label>
                                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                            style="cursor: pointer;"></i>
                                                                                            </div>
                                                                                            <div class="popoverContent" style="display: none;">
                                                                                                <div class="popover-title">{{ $label[480 - 1]->name }}</div>
                                                                                                <div class="popover-content">{{ $label[480 - 1]->display }}</div>
                                                                                            </div>
                                                                                            @else
                                                                                               <label>Driver No1#</label>
                                                                                            @endif
                                                                                            <input class="form-control driverphoneno" id="auc_driver_no" name="auc_driver_no" placeholder="Driver No1#" value="${phone1}" required>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                         @if ($label[481 - 1]->status == 1)
                                                                                             <div class="Terminal-error">
                                                                                            <label>Driver No2#</label>
                                                                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                            style="cursor: pointer;"></i>
                                                                                            </div>
                                                                                            <div class="popoverContent" style="display: none;">
                                                                                                <div class="popover-title">{{ $label[481 - 1]->name }}</div>
                                                                                                <div class="popover-content">{{ $label[481 - 1]->display }}</div>
                                                                                            </div>
                                                                                            @else
                                                                                               <label>Driver No2#</label>
                                                                                            @endif
                                                                                            <input class="form-control driverphoneno" id="auc_driver_no2" name="auc_driver_no2" placeholder="Driver No2#" value="">
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                         @if ($label[482 - 1]->status == 1)
                                                                                             <div class="Terminal-error">
                                                                                            <label>Driver No3#</label>
                                                                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                            style="cursor: pointer;"></i>
                                                                                            </div>
                                                                                            <div class="popoverContent" style="display: none;">
                                                                                                <div class="popover-title">{{ $label[482 - 1]->name }}</div>
                                                                                                <div class="popover-content">{{ $label[482 - 1]->display }}</div>
                                                                                            </div>
                                                                                            @else
                                                                                                <label>Driver No3#</label>
                                                                                            @endif
                                                                                            <input class="form-control driverphoneno" id="auc_driver_no3" name="auc_driver_no3" placeholder="Driver No3#" value="">
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                         @if ($label[483 - 1]->status == 1)
                                                                                             <div class="Terminal-error">
                                                                                            <label>Driver No4#</label>
                                                                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                            style="cursor: pointer;"></i>
                                                                                            </div>
                                                                                            <div class="popoverContent" style="display: none;">
                                                                                                <div class="popover-title">{{ $label[483 - 1]->name }}</div>
                                                                                                <div class="popover-content">{{ $label[483 - 1]->display }}</div>
                                                                                            </div>
                                                                                            @else
                                                                                                <label>Driver No4#</label>
                                                                                            @endif
                                                                                            <input class="form-control driverphoneno" id="auc_driver_no4" name="auc_driver_no4" placeholder="Driver No4#" value="">
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                         @if ($label[484 - 1]->status == 1)
                                                                                             <div class="Terminal-error">
                                                                                            <label>Storage</label>
                                                                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                            style="cursor: pointer;"></i>
                                                                                            </div>
                                                                                            <div class="popoverContent" style="display: none;">
                                                                                                <div class="popover-title">{{ $label[484 - 1]->name }}</div>
                                                                                                <div class="popover-content">{{ $label[484 - 1]->display }}</div>
                                                                                            </div>
                                                                                            @else
                                                                                                <label>Storage</label>
                                                                                            @endif
                                                                                            <input class="form-control" id="auc_storage" name="auc_storage" placeholder="Storage" value="" required>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                         @if ($label[485 - 1]->status == 1)
                                                                                             <div class="Terminal-error">
                                                                                            <label>Delivery Datetime</label>
                                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                            style="cursor: pointer;"></i>
                                                                                            </div>
                                                                                            <div class="popoverContent" style="display: none;">
                                                                                                <div class="popover-title">{{ $label[485 - 1]->name }}</div>
                                                                                                <div class="popover-content">{{ $label[485 - 1]->display }}</div>
                                                                                            </div>
                                                                                            @else
                                                                                                <label>Delivery Datetime</label>
                                                                                            @endif
                                                                                            <input class="form-control" id="auc_delivery_date" type="datetime-local" required  placeholder="Delivery Datetime" name="auc_delivery_date"
                                                                                                   value="">
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                         @if ($label[486 - 1]->status == 1)
                                                                                             <div class="Terminal-error">
                                                                                            <label>Vehicle Condition</label>
                                                                                               <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                            style="cursor: pointer;"></i>
                                                                                            </div>
                                                                                            <div class="popoverContent" style="display: none;">
                                                                                                <div class="popover-title">{{ $label[486 - 1]->name }}</div>
                                                                                                <div class="popover-content">{{ $label[486 - 1]->display }}</div>
                                                                                            </div>
                                                                                            @else
                                                                                               <label>Vehicle Condition</label>
                                                                                            @endif
                                                                                            <input class="form-control" id="auc_condition" placeholder="Vehicle Condition" name="auc_condition" value="" required>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                         @if ($label[487 - 1]->status == 1)
                                                                                             <div class="Terminal-error">
                                                                                            <label>Vehicle Position</label>
                                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                            style="cursor: pointer;"></i>
                                                                                            </div>
                                                                                            <div class="popoverContent" style="display: none;">
                                                                                                <div class="popover-title">{{ $label[487 - 1]->name }}</div>
                                                                                                <div class="popover-content">{{ $label[487 - 1]->display }}</div>
                                                                                            </div>
                                                                                            @else
                                                                                               <label>Vehicle Position</label>
                                                                                            @endif
                                                                                            <input id="auc_vehicle_position" name="auc_vehicle_position" class="form-control" placeholder="Vehicle Position" value="" required>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                         @if ($label[488 - 1]->status == 1)
                                                                                             <div class="Terminal-error">
                                                                                            <label>Payment</label>
                                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                            style="cursor: pointer;"></i>
                                                                                            </div>
                                                                                            <div class="popoverContent" style="display: none;">
                                                                                                <div class="popover-title">{{ $label[488 - 1]->name }}</div>
                                                                                                <div class="popover-content">{{ $label[488 - 1]->display }}</div>
                                                                                            </div>
                                                                                            @else
                                                                                              <label>Payment</label>
                                                                                            @endif
                                                                                            <select id="auc_payment" class="form-control h-50" name="auc_payment" required>
                                                                                                <option value="" selected disabled>Select</option>
                                                                                                <option value="Yes">Yes</option>
                                                                                                <option value="No">No</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                          @if ($label[489 - 1]->status == 1)
                                                                                             <div class="Terminal-error">
                                                                                            <label>Payment Charged Or Owes</label>
                                                                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                            style="cursor: pointer;"></i>
                                                                                            </div>
                                                                                            <div class="popoverContent" style="display: none;">
                                                                                                <div class="popover-title">{{ $label[489 - 1]->name }}</div>
                                                                                                <div class="popover-content">{{ $label[489 - 1]->display }}</div>
                                                                                            </div>
                                                                                            @else
                                                                                               <label>Payment Charged Or Owes</label>
                                                                                            @endif
                                                                                            <input class="form-control" id="auc_payment_charged_or_owes" name="auc_payment_charged_or_owes" required placeholder="Payment Charged Or Owes" value="">
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                         @if ($label[490 - 1]->status == 1)
                                                                                             <div class="Terminal-error">
                                                                                            <label>Payment Method</label>
                                                                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                            style="cursor: pointer;"></i>
                                                                                            </div>
                                                                                            <div class="popoverContent" style="display: none;">
                                                                                                <div class="popover-title">{{ $label[490 - 1]->name }}</div>
                                                                                                <div class="popover-content">{{ $label[490 - 1]->display }}</div>
                                                                                            </div>
                                                                                            @else
                                                                                               <label>Payment Method</label>
                                                                                            @endif
                                                                                            <input class="form-control" id="auc_payment_method" name="auc_payment_method" required placeholder="Payment Method" value="">
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                         @if ($label[491 - 1]->status == 1)
                                                                                             <div class="Terminal-error">
                                                                                            <label>Total Amount (If Owed)</label>
                                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                            style="cursor: pointer;"></i>
                                                                                            </div>
                                                                                            <div class="popoverContent" style="display: none;">
                                                                                                <div class="popover-title">{{ $label[491 - 1]->name }}</div>
                                                                                                <div class="popover-content">{{ $label[491 - 1]->display }}</div>
                                                                                            </div>
                                                                                            @else
                                                                                              <label>Total Amount (If Owed)</label>
                                                                                            @endif
                                                                                            <input class="form-control" id="auc_price" name="auc_price" required placeholder="Total Amount (If Owed)" value="">
                                                                                        </div>
                                                                                        <div class="col-md-4">
                                                                                         @if ($label[492 - 1]->status == 1)
                                                                                             <div class="Terminal-error">
                                                                                            <label>Title</label>
                                                                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                            style="cursor: pointer;"></i>
                                                                                            </div>
                                                                                            <div class="popoverContent" style="display: none;">
                                                                                                <div class="popover-title">{{ $label[492 - 1]->name }}</div>
                                                                                                <div class="popover-content">{{ $label[492 - 1]->display }}</div>
                                                                                            </div>
                                                                                            @else
                                                                                                <label>Title</label>
                                                                                            @endif
                                                                                            <select id="auc_title_keys" name="auc_title_keys" class="form-control h-50" required>
                                                                                                <option value="" selected disabled>Select</option>
                                                                                                <option value="Yes">Yes</option>
                                                                                                <option value="No">No</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-4">
                                                                                         @if ($label[493 - 1]->status == 1)
                                                                                             <div class="Terminal-error">
                                                                                            <label>Key</label>
                                                                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                            style="cursor: pointer;"></i>
                                                                                            </div>
                                                                                            <div class="popoverContent" style="display: none;">
                                                                                                <div class="popover-title">{{ $label[493 - 1]->name }}</div>
                                                                                                <div class="popover-content">{{ $label[493 - 1]->display }}</div>
                                                                                            </div>
                                                                                            @else
                                                                                                 <label>Key</label>
                                                                                            @endif
                                                                                            <select id="auc_keys" name="auc_keys" class="form-control h-50" required>
                                                                                                <option value="" selected disabled>Select</option>
                                                                                                <option value="Yes">Yes</option>
                                                                                                <option value="No">No</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-4">
                                                                                         @if ($label[494 - 1]->status == 1)
                                                                                             <div class="Terminal-error">
                                                                                            <label>Dock Receipt (If Port)</label>
                                                                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                            style="cursor: pointer;"></i>
                                                                                            </div>
                                                                                            <div class="popoverContent" style="display: none;">
                                                                                                <div class="popover-title">{{ $label[494 - 1]->name }}</div>
                                                                                                <div class="popover-content">{{ $label[494 - 1]->display }}</div>
                                                                                            </div>
                                                                                            @else
                                                                                                  <label>Dock Receipt (If Port)</label>
                                                                                            @endif
                                                                                            <input class="form-control" id="auc_stamp_dock_receipt" name="auc_stamp_dock_receipt" required placeholder="Dock Receipt (If Port)" value="">
                                                                                        </div>
                                                                                        <div class="col-md-12">
                                                                                         @if ($label[495 - 1]->status == 1)
                                                                                             <div class="Terminal-error">
                                                                                            <label>Additional</label>
                                                                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                            style="cursor: pointer;"></i>
                                                                                            </div>
                                                                                            <div class="popoverContent" style="display: none;">
                                                                                                <div class="popover-title">{{ $label[495 - 1]->name }}</div>
                                                                                                <div class="popover-content">{{ $label[495 - 1]->display }}</div>
                                                                                            </div>
                                                                                            @else
                                                                                                  <label>Additional</label>
                                                                                            @endif
                                                                                            <input class="form-control" id="auc_additional" placeholder="Additional" name="auc_additional" value="" required>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    `);
                                                                    $(document).ready(function() {
                                                                        // Select all error icons within the document
                                                                        var $errorIcons = $('.Terminal-error i');
                                                                        var $openPopoverContent = null;

                                                                        // Iterate over each error icon
                                                                        $errorIcons.each(function() {
                                                                            var $errorIcon = $(this);
                                                                            var $popoverContent = $errorIcon.closest(
                                                                                '.Terminal-error').siblings('.popoverContent');
                                                                            // Toggle the popover on icon click
                                                                            $errorIcon.on('click', function(event) {
                                                                                event
                                                                                    .stopPropagation(); // Prevent the document click event from firing immediately

                                                                                // Close the previously open popover content
                                                                                if ($openPopoverContent && !
                                                                                    $openPopoverContent.is($popoverContent)
                                                                                ) {
                                                                                    $openPopoverContent.hide();
                                                                                }

                                                                                // Toggle the current popover content
                                                                                $popoverContent.toggle();
                                                                                $openPopoverContent = $popoverContent;
                                                                            });
                                                                        });

                                                                        // Close the popover if clicked outside
                                                                        $(document).on('click', function(event) {
                                                                            if ($openPopoverContent && !$errorIcons.is(event
                                                                                    .target) && !$openPopoverContent.is(event
                                                                                    .target) &&
                                                                                $openPopoverContent
                                                                                .has(event.target).length === 0) {
                                                                                $openPopoverContent.hide();
                                                                                $openPopoverContent = null;
                                                                            }
                                                                        });
                                                                    });
                                                                    $(".driverphoneno").keypress(function(e) {
                                                                        if ($(this).val() == '') {
                                                                            $(this).mask("(999) 999-9999");
                                                                        }
                                                                        var x = e.which || e.keycode;
                                                                        if ((x >= 48 && x <= 57) || (x >= 35 && x <= 40)) {
                                                                            return true;
                                                                        } else {
                                                                            return false;
                                                                        }
                                                                    })
                                                                }
                                                            });
                                                        } else if ($(this).val() == 34) {
                                                            $("#carrier_add").attr('disabled', false);
                                                            $(".auctionupdate").html(`
                                                                <input type="hidden" value="10" name="pstatus222">
                                                                <input type="hidden" value="sheet_detail" name="sheet_detail">
                                                                <div class="col-md-12 text-center"><h4>Scheduling Sheet</h4></div>
                                                                <div class="col-md-4">
                                                                 @if ($label[447 - 1]->status == 1)
                                                                 <div class="Terminal-error">
                                                                    <label>Pickedup Time</label>
                                                                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                      style="cursor: pointer;"></i>
                                                                 </div>
                                                                 <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[447 - 1]->name }}</div>
                                                                        <div class="popover-content">{{ $label[447 - 1]->display }}</div>
                                                                 </div>
                                                                    @else
                                                                        <label>Pickedup Time</label>
                                                                    @endif
                                                                    <input class="form-control" type="datetime-local" id="auc_pickedup" name="auc_pickedup" placeholder="=PickedUp time" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                 @if ($label[448 - 1]->status == 1)
                                                                 <div class="Terminal-error">
                                                                    <label>Delivery Time</label>
                                                                       <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                      style="cursor: pointer;"></i>
                                                                 </div>
                                                                 <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[448 - 1]->name }}</div>
                                                                        <div class="popover-content">{{ $label[448 - 1]->display }}</div>
                                                                 </div>
                                                                    @else
                                                                        <label>Delivery Time</label>
                                                                    @endif
                                                                    <input class="form-control" type="datetime-local" id="auc_delivery_date" name="auc_delivery_date" placeholder="=Delivery time" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                @if ($label[449 - 1]->status == 1)
                                                                 <div class="Terminal-error">
                                                                    <label>Dispatch Price</label>
                                                                 <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                 style="cursor: pointer;"></i>
                                                                 </div>
                                                                 <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[449 - 1]->name }}</div>
                                                                        <div class="popover-content">{{ $label[449 - 1]->display }}</div>
                                                                 </div>
                                                                    @else
                                                                       <label>Dispatch Price</label>
                                                                    @endif
                                                                    <input class="form-control" type="text" id="auc_price" name="auc_price" placeholder="Dispatch Price" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                 @if ($label[450 - 1]->status == 1)
                                                                 <div class="Terminal-error">
                                                                    <label>Vehicle Condition</label>
                                                                    <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                 style="cursor: pointer;"></i>
                                                                 </div>
                                                                 <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[450 - 1]->name }}</div>
                                                                        <div class="popover-content">{{ $label[450 - 1]->display }}</div>
                                                                 </div>
                                                                    @else
                                                                        <label>Vehicle Condition</label>
                                                                    @endif
                                                                    <input class="form-control" type="text" id="auc_condition" name="auc_condition" placeholder="Vehicle Condition" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                  @if ($label[451 - 1]->status == 1)
                                                                 <div class="Terminal-error">
                                                                    <label>Storage</label>
                                                                    <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                 style="cursor: pointer;"></i>
                                                                 </div>
                                                                 <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[451 - 1]->name }}</div>
                                                                        <div class="popover-content">{{ $label[451 - 1]->display }}</div>
                                                                 </div>
                                                                    @else
                                                                        <label>Storage</label>
                                                                    @endif
                                                                    <input class="form-control" id="auc_storage" name="auc_storage" placeholder="Storage" value="" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                 @if ($label[452 - 1]->status == 1)
                                                                 <div class="Terminal-error">
                                                                    <label>Driver FMCSA (Active)?</label>
                                                                    <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                 style="cursor: pointer;"></i>
                                                                 </div>
                                                                 <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[452 - 1]->name }}</div>
                                                                        <div class="popover-content">{{ $label[452 - 1]->display }}</div>
                                                                 </div>
                                                                    @else
                                                                       <label>Driver FMCSA (Active)?</label>
                                                                    @endif
                                                                    <select id="auc_driver_fmcsa" name="auc_driver_fmcsa" class="form-control h-50" required>
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                 @if ($label[453 - 1]->status == 1)
                                                                 <div class="Terminal-error">
                                                                    <label>Carrier Rating</label>
                                                                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                 style="cursor: pointer;"></i>
                                                                 </div>
                                                                 <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[453 - 1]->name }}</div>
                                                                        <div class="popover-content">{{ $label[453 - 1]->display }}</div>
                                                                 </div>
                                                                    @else
                                                                       <label>Carrier Rating</label>
                                                                    @endif
                                                                    <input class="form-control" id="auc_carrier_rating" name="auc_carrier_rating" placeholder="Carrier Rating" value="" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                 @if ($label[454 - 1]->status == 1)
                                                                 <div class="Terminal-error">
                                                                     <label>Carrier Rating</label>
                                                                        <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                 style="cursor: pointer;"></i>
                                                                 </div>
                                                                 <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[454 - 1]->name }}</div>
                                                                        <div class="popover-content">{{ $label[454 - 1]->display }}</div>
                                                                 </div>
                                                                    @else
                                                                       <label>Carrier Rating</label>
                                                                    @endif
                                                                    <input class="form-control" id="auc_fmcsa" name="auc_fmcsa" placeholder="FMCSA" value="" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                @if ($label[455 - 1]->status == 1)
                                                                 <div class="Terminal-error">
                                                                    <label>Date Of Insurance (FMCSA)</label>
                                                                           <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                 style="cursor: pointer;"></i>
                                                                 </div>
                                                                 <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[455 - 1]->name }}</div>
                                                                        <div class="popover-content">{{ $label[455 - 1]->display }}</div>
                                                                 </div>
                                                                    @else
                                                                       <label>Carrier Rating</label>
                                                                    @endif
                                                                    <input class="form-control" type="date" id="auc_insurance_date" name="auc_insurance_date" placeholder="Date Of Insurance (FMCSA)" value="" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                 @if ($label[456 - 1]->status == 1)
                                                                 <div class="Terminal-error">
                                                                    <label>COI Holder</label>
                                                                    <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                     style="cursor: pointer;"></i>
                                                                 </div>
                                                                 <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[456 - 1]->name }}</div>
                                                                        <div class="popover-content">{{ $label[456 - 1]->display }}</div>
                                                                 </div>
                                                                    @else
                                                                       <label>COI Holder</label>
                                                                    @endif
                                                                    <select id="auc_coi_holder" name="auc_coi_holder" class="form-control h-50" required>
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                        <option value="Waiting">Waiting</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                 @if ($label[457 - 1]->status == 1)
                                                                 <div class="Terminal-error">
                                                                    <label>Is Vehicle Luxury?</label>
                                                                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                     style="cursor: pointer;"></i>
                                                                 </div>
                                                                 <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[457 - 1]->name }}</div>
                                                                        <div class="popover-content">{{ $label[457 - 1]->display }}</div>
                                                                 </div>
                                                                    @else
                                                                       <label>Is Vehicle Luxury?</label>
                                                                    @endif
                                                                    <select id="auc_vehicle_luxury" name="auc_vehicle_luxury" class="form-control h-50" required>
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                 @if ($label[458 - 1]->status == 1)
                                                                 <div class="Terminal-error">
                                                                    <label>Aware Driver Delivery</label>
                                                                    <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                     style="cursor: pointer;"></i>
                                                                 </div>
                                                                 <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[458 - 1]->name }}</div>
                                                                        <div class="popover-content">{{ $label[458 - 1]->display }}</div>
                                                                 </div>
                                                                    @else
                                                                       <label>Aware Driver Delivery</label>
                                                                    @endif
                                                                    <input class="form-control" type="text" id="auc_aware_driver_delivery_date" name="auc_aware_driver_delivery_date" placeholder="Aware Driver Delivery" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                 @if ($label[459 - 1]->status == 1)
                                                                 <div class="Terminal-error">
                                                                    <label>New/Old Driver</label>
                                                                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                     style="cursor: pointer;"></i>
                                                                 </div>
                                                                 <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[459 - 1]->name }}</div>
                                                                        <div class="popover-content">{{ $label[459 - 1]->display }}</div>
                                                                 </div>
                                                                    @else
                                                                      <label>New/Old Driver</label>
                                                                    @endif
                                                                    <select id="auc_new_old_driver" name="auc_new_old_driver" class="form-control h-50" required>
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Old Driver">Old Driver</option>
                                                                        <option value="New Driver">New Driver</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                  @if ($label[460 - 1]->status == 1)
                                                                 <div class="Terminal-error">
                                                                    <label>Is Local?</label>
                                                                    <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                     style="cursor: pointer;"></i>
                                                                 </div>
                                                                 <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[460 - 1]->name }}</div>
                                                                        <div class="popover-content">{{ $label[460 - 1]->display }}</div>
                                                                 </div>
                                                                    @else
                                                                       <label>Is Local?</label>
                                                                    @endif
                                                                    <select id="auc_is_local" name="auc_is_local" class="form-control h-50" required>
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                  @if ($label[461 - 1]->status == 1)
                                                                 <div class="Terminal-error">
                                                                    <label>Job Accept</label>
                                                                    <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                     style="cursor: pointer;"></i>
                                                                 </div>
                                                                 <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[461 - 1]->name }}</div>
                                                                        <div class="popover-content">{{ $label[461 - 1]->display }}</div>
                                                                 </div>
                                                                    @else
                                                                      <label>Job Accept</label>
                                                                    @endif
                                                                    <input class="form-control" id="auc_job_accept" name="auc_job_accept" placeholder="Job Accept" value="" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                 @if ($label[462 - 1]->status == 1)
                                                                 <div class="Terminal-error">
                                                                    <label>Title</label>
                                                                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                     style="cursor: pointer;"></i>
                                                                 </div>
                                                                 <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[462 - 1]->name }}</div>
                                                                        <div class="popover-content">{{ $label[462 - 1]->display }}</div>
                                                                 </div>
                                                                    @else
                                                                       <label>Title</label>
                                                                    @endif
                                                                    <select id="auc_title_keys" name="auc_title_keys" class="form-control h-50" required>
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                 @if ($label[463 - 1]->status == 1)
                                                                 <div class="Terminal-error">
                                                                    <label>Key</label>
                                                                    <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                     style="cursor: pointer;"></i>
                                                                 </div>
                                                                 <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[463 - 1]->name }}</div>
                                                                        <div class="popover-content">{{ $label[463 - 1]->display }}</div>
                                                                 </div>
                                                                    @else
                                                                      <label>Key</label>
                                                                    @endif
                                                                    <select id="auc_keys" name="auc_keys" class="form-control h-50" required>
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                 @if ($label[464 - 1]->status == 1)
                                                                 <div class="Terminal-error">
                                                                    <label>Auction Update</label>
                                                                      <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                     style="cursor: pointer;"></i>
                                                                 </div>
                                                                 <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[464 - 1]->name }}</div>
                                                                        <div class="popover-content">{{ $label[464 - 1]->display }}</div>
                                                                 </div>
                                                                    @else
                                                                       <label>Auction Update</label>
                                                                    @endif
                                                                    <input id="auc_auction_update" name="auc_auction_update" class="form-control" placeholder="Auction Update" value="" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                 @if ($label[465 - 1]->status == 1)
                                                                 <div class="Terminal-error">
                                                                    <label>Storage Pay</label>
                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                     style="cursor: pointer;"></i>
                                                                 </div>
                                                                 <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[465 - 1]->name }}</div>
                                                                        <div class="popover-content">{{ $label[465 - 1]->display }}</div>
                                                                 </div>
                                                                    @else
                                                                       <label>Storage Pay</label>
                                                                    @endif
                                                                    <select id="auc_who_pay_storage" name="auc_who_pay_storage" class="form-control h-50" required>
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                @if ($label[466 - 1]->status == 1)
                                                                 <div class="Terminal-error">
                                                                    <label>Vehicle Position</label>
                                                                                 <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                     style="cursor: pointer;"></i>
                                                                 </div>
                                                                 <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[466 - 1]->name }}</div>
                                                                        <div class="popover-content">{{ $label[466 - 1]->display }}</div>
                                                                 </div>
                                                                    @else
                                                                        <label>Vehicle Position</label>
                                                                    @endif
                                                                    <input id="auc_vehicle_position" name="auc_vehicle_position" class="form-control" placeholder="Vehicle Position" value="" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                 @if ($label[467 - 1]->status == 1)
                                                                 <div class="Terminal-error">
                                                                    <label>Payment Method</label>
                                                                       <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                     style="cursor: pointer;"></i>
                                                                 </div>
                                                                 <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[467 - 1]->name }}</div>
                                                                        <div class="popover-content">{{ $label[467 - 1]->display }}</div>
                                                                 </div>
                                                                    @else
                                                                        <label>Payment Method</label>
                                                                    @endif
                                                                    <input class="form-control" id="auc_payment_method" name="auc_payment_method" placeholder="Payment Method" value="" required>
                                                                </div>
                                                                <div class="col-md-12">
                                                                 @if ($label[468 - 1]->status == 1)
                                                                 <div class="Terminal-error">
                                                                    <label>Additional</label>
                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                     style="cursor: pointer;"></i>
                                                                 </div>
                                                                 <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[468 - 1]->name }}</div>
                                                                        <div class="popover-content">{{ $label[468 - 1]->display }}</div>
                                                                 </div>
                                                                    @else
                                                                        <label>Additional</label>
                                                                    @endif
                                                                    <input class="form-control" id="auc_additional" placeholder="Additional" name="auc_additional" value="" required>
                                                                </div>
                                                            `);
                                                            $(document).ready(function() {
                                                                // Select all error icons within the document
                                                                var $errorIcons = $('.Terminal-error i');
                                                                var $openPopoverContent = null;

                                                                // Iterate over each error icon
                                                                $errorIcons.each(function() {
                                                                    var $errorIcon = $(this);
                                                                    var $popoverContent = $errorIcon.closest('.Terminal-error').siblings(
                                                                        '.popoverContent');
                                                                    // Toggle the popover on icon click
                                                                    $errorIcon.on('click', function(event) {
                                                                        event
                                                                            .stopPropagation(); // Prevent the document click event from firing immediately

                                                                        // Close the previously open popover content
                                                                        if ($openPopoverContent && !$openPopoverContent.is(
                                                                                $popoverContent)) {
                                                                            $openPopoverContent.hide();
                                                                        }

                                                                        // Toggle the current popover content
                                                                        $popoverContent.toggle();
                                                                        $openPopoverContent = $popoverContent;
                                                                    });
                                                                });

                                                                // Close the popover if clicked outside
                                                                $(document).on('click', function(event) {
                                                                    if ($openPopoverContent && !$errorIcons.is(event.target) && !
                                                                        $openPopoverContent.is(event.target) && $openPopoverContent
                                                                        .has(event.target).length === 0) {
                                                                        $openPopoverContent.hide();
                                                                        $openPopoverContent = null;
                                                                    }
                                                                });
                                                            });
                                                            $(".pickupdatediv").html(`
                                                                <div class="form-group">
                                                                    <label class="form-label">EXPECTED DATE</label>
                                                                    <input type="date" required name="expected_date"
                                                                           id='expected_date'
                                                                           class="form-control select_cancel">
                                                                </div>
                                                            `);
                                                        } else {
                                                            $(".pickupdatediv").html('');
                                                            $("#carrier_add").attr('disabled', false);
                                                        }
                                                    })
                                                </script>
                                                <div class="col-md-3">
                                                    @if ($label[512 - 1]->status == 1)
                                                        <div class="Terminal-error">
                                                            <label>Review</label>
                                                            <i id="errorIcon"
                                                                class="fas fa-info-circle fa-lg text-info info-icon"
                                                                style="cursor: pointer;"></i>
                                                        </div>
                                                        <div class="popoverContent" style="display: none;">
                                                            <div class="popover-title">{{ $label[512 - 1]->name }}</div>
                                                            <div class="popover-content">{{ $label[512 - 1]->display }}
                                                            </div>
                                                        </div>
                                                    @else
                                                        <label>Review</label>
                                                    @endif
                                                    <select id="auc_reviewss" name="auc_review"
                                                        class="form-control h-50 auc_review" required>
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 all_rating" id="all_rating"
                                                    style="display:none;">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            @if ($label[513 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Website</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[513 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[513 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Website</label>
                                                            @endif
                                                            <select id="auc_website" name="auc_website"
                                                                class="form-control h-50">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="BBB">BBB</option>
                                                                <option value="Trust Pilot">Trust Pilot</option>
                                                                <option value="Google">Google</option>
                                                                <option value="Other">Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-6" style="display:none;"
                                                            id="other_website">
                                                            @if ($label[514 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Other Website</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[514 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[514 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Other Website</label>
                                                            @endif
                                                            <input class="form-control h-50" id="auc_website_other"
                                                                name="auc_website_other" placeholder="Other Website"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-6">
                                                            @if ($label[515 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Rating</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[515 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[515 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Rating</label>
                                                            @endif
                                                            <select id="auc_client_rating" name="auc_client_rating"
                                                                class="form-control h-50">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="Positive">Positive</option>
                                                                <option value="Neutral">Neutral</option>
                                                                <option value="Negative">Negative</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            @if ($label[516 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Website Link</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[516 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[516 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Website Link</label>
                                                            @endif
                                                            <input class="form-control h-50" id="auc_website_link"
                                                                name="auc_website_link" placeholder="Website Link"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label>Reviewer</label>
                                                            <input type="text" class="form-control" id=""
                                                                name="auc_reviewer" placeholder="Reviewer"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label>Attach Screenshot</label>
                                                            <input type="file" class="form-control" id=""
                                                                name="screenshot" placeholder="screenshot"
                                                                value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif

                                        @if (\Request::is('picked_up'))
                                            <form method="post" enctype="multipart/form-data"
                                                action="{{ route('call_history_post') }}" id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">PickedUp HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="11" selected>Pickup</option>
                                                                <option value="12">Deliver</option>

                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!--<div class="col-sm-6 col-md-6">-->
                                                    <!--    <div class="form-group">-->
                                                    <!--        <label class="form-label">EXPECTED DATE</label>-->
                                                    <!--        <input type="date" required name="expected_date"-->
                                                    <!--               id='expected_date' -->
                                                    <!--               class="form-control">-->
                                                    <!--    </div>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6 col-md-6 pickupdatediv2">-->

                                                    <!--</div>-->
                                                    <div class="col-sm-6 col-md-6 deliverdate">

                                                    </div>
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row auctionupdate mb-2"></div>
                                                <script>
                                                    $("#pstatus").on('change', function() {
                                                        if ($(this).val() == 12) {
                                                            var order_id = $("#order_id1").val();
                                                            $.ajax({
                                                                url: "{{ url('/get_sheet') }}",
                                                                type: "GET",
                                                                data: {
                                                                    order_id: order_id
                                                                },
                                                                dataType: "JSON",
                                                                success: function(res) {
                                                                    var delivery = '';
                                                                    if (res.delivery_date) {
                                                                        delivery = res.delivery_date;
                                                                    }
                                                                    $(".deliverdate").html(`
                                                                        <div class="form-group">
                                                                            <label class="form-label">DELIVER DATE</label>
                                                                            <input required  type="datetime-local" value="${delivery}"  name="deliver_date"
                                                                            id='deliver_date'class="form-control">
                                                                            <input type="checkbox" name="approvaldeliver" value="1"/> &nbsp; Mark AS APPROVED (DELIVER)
                                                                        </div>
                                                                    `);
                                                                }
                                                            })
                                                            $.ajax({
                                                                url: "{{ url('/get_carrier2') }}",
                                                                type: "GET",
                                                                data: {
                                                                    order_id: order_id
                                                                },
                                                                dataType: "JSON",
                                                                success: function(res) {
                                                                    var phone1 = '';
                                                                    if (res[0].driverphoneno) {
                                                                        phone1 = res[0].driverphoneno;
                                                                    }
                                                                    $(".auctionupdate").html(`
                                                                        <input type="hidden" value="12" name="pstatus222">
                                                                        <input type="hidden" value="sheet_detail" name="sheet_detail">
                                                                        <div class="col-md-12 text-center"><h4>Delivery Sheet</h4></div>
                                                                        <div class="col-sm-12">
                                                                            <div class="row">
                                                                              <div class="col-md-6">
                                                                               @if ($label[518 - 1]->status == 1)
                                                                                <div class="Terminal-error">
                                                                                    <label>Driver No1#</label>
                                                                                   <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                     style="cursor: pointer;"></i>
                                                                                </div>
                                                                             <div class="popoverContent" style="display: none;">
                                                                             <div class="popover-title">{{ $label[518 - 1]->name }}</div>
                                                                             <div class="popover-content">{{ $label[518 - 1]->display }}</div>
                                                                             </div>
                                                                                @else
                                                                              <label>Driver No1#</label>
                                                                                @endif
                                                                                    <input class="form-control driverphoneno" id="auc_driver_no" name="auc_driver_no" placeholder="Driver No1#" value="${phone1}" required>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                  @if ($label[519 - 1]->status == 1)
                                                                                 <div class="Terminal-error">
                                                                                    <label>Driver No2#</label>
                                                                               <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                     style="cursor: pointer;"></i>
                                                                                </div>
                                                                             <div class="popoverContent" style="display: none;">
                                                                             <div class="popover-title">{{ $label[519 - 1]->name }}</div>
                                                                             <div class="popover-content">{{ $label[519 - 1]->display }}</div>
                                                                             </div>
                                                                                @else
                                                                              <label>Driver No1#</label>
                                                                                @endif
                                                                                    <input class="form-control driverphoneno" id="auc_driver_no2" name="auc_driver_no2" placeholder="Driver No2#" value="">
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                  @if ($label[520 - 1]->status == 1)
                                                                                 <div class="Terminal-error">
                                                                                    <label>Driver No3#</label>
                                                                                  <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                     style="cursor: pointer;"></i>
                                                                                </div>
                                                                             <div class="popoverContent" style="display: none;">
                                                                             <div class="popover-title">{{ $label[520 - 1]->name }}</div>
                                                                             <div class="popover-content">{{ $label[520 - 1]->display }}</div>
                                                                             </div>
                                                                                @else
                                                                               <label>Driver No3#</label>
                                                                                @endif
                                                                                    <input class="form-control driverphoneno" id="auc_driver_no3" name="auc_driver_no3" placeholder="Driver No3#" value="">
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    @if ($label[521 - 1]->status == 1)
                                                                                <div class="Terminal-error">
                                                                                    <label>Driver No4#</label>
                                                                                   <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                     style="cursor: pointer;"></i>
                                                                                </div>
                                                                             <div class="popoverContent" style="display: none;">
                                                                             <div class="popover-title">{{ $label[521 - 1]->name }}</div>
                                                                             <div class="popover-content">{{ $label[521 - 1]->display }}</div>
                                                                             </div>
                                                                                @else
                                                                             <label>Driver No4#</label>
                                                                                @endif
                                                                                    <input class="form-control driverphoneno" id="auc_driver_no4" name="auc_driver_no4" placeholder="Driver No4#" value="">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                         @if ($label[522 - 1]->status == 1)
                                                                        <div class="Terminal-error">
                                                                            <label>Driver Status</label>
                                                                               <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                     style="cursor: pointer;"></i>
                                                                                </div>
                                                                             <div class="popoverContent" style="display: none;">
                                                                             <div class="popover-title">{{ $label[522 - 1]->name }}</div>
                                                                             <div class="popover-content">{{ $label[522 - 1]->display }}</div>
                                                                             </div>
                                                                                @else
                                                                           <label>Driver Status</label>
                                                                                @endif
                                                                            <input id="auc_driver_status" name="auc_driver_status" class="form-control" placeholder="Driver Status" value="" required>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                          @if ($label[523 - 1]->status == 1)
                                                                        <div class="Terminal-error">
                                                                            <label>Driver Payment Status</label>
                                                                                 <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                     style="cursor: pointer;"></i>
                                                                                </div>
                                                                             <div class="popoverContent" style="display: none;">
                                                                             <div class="popover-title">{{ $label[523 - 1]->name }}</div>
                                                                             <div class="popover-content">{{ $label[523 - 1]->display }}</div>
                                                                             </div>
                                                                                @else
                                                                            <label>Driver Payment Status</label>
                                                                                @endif
                                                                            <input id="auc_driver_payment_status" name="auc_driver_payment_status" class="form-control" placeholder="Driver Payment Status" value="" required>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                          @if ($label[524 - 1]->status == 1)
                                                                         <div class="Terminal-error">
                                                                            <label>Vehicle Condition</label>
                                                                                <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                     style="cursor: pointer;"></i>
                                                                                </div>
                                                                             <div class="popoverContent" style="display: none;">
                                                                             <div class="popover-title">{{ $label[524 - 1]->name }}</div>
                                                                             <div class="popover-content">{{ $label[524 - 1]->display }}</div>
                                                                             </div>
                                                                                @else
                                                                             <label>Vehicle Condition</label>
                                                                                @endif
                                                                            <input class="form-control" id="auc_condition" placeholder="Vehicle Condition" name="auc_condition" value="" required>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                        @if ($label[525 - 1]->status == 1)
                                                                         <div class="Terminal-error">
                                                                            <label>Customer Informed</label>
                                                                                      <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                     style="cursor: pointer;"></i>
                                                                                </div>
                                                                             <div class="popoverContent" style="display: none;">
                                                                             <div class="popover-title">{{ $label[525 - 1]->name }}</div>
                                                                             <div class="popover-content">{{ $label[525 - 1]->display }}</div>
                                                                             </div>
                                                                                @else
                                                                                  <label>Customer Informed</label>
                                                                                @endif
                                                                            <input class="form-control" id="auc_customer_informed" placeholder="Customer Informed" name="auc_customer_informed" value="" required>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                         @if ($label[526 - 1]->status == 1)
                                                                        <div class="Terminal-error">
                                                                            <label>Vehicle Position</label>
                                                                                           <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                     style="cursor: pointer;"></i>
                                                                                </div>
                                                                             <div class="popoverContent" style="display: none;">
                                                                             <div class="popover-title">{{ $label[526 - 1]->name }}</div>
                                                                             <div class="popover-content">{{ $label[526 - 1]->display }}</div>
                                                                             </div>
                                                                                @else
                                                                                 <label>Vehicle Position</label>
                                                                                @endif
                                                                            <input id="auc_vehicle_position" name="auc_vehicle_position" class="form-control" placeholder="Vehicle Position" value="" required>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                         @if ($label[527 - 1]->status == 1)
                                                                        <div class="Terminal-error">
                                                                            <label>Delivery Datetime</label>
                                                                                    <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                     style="cursor: pointer;"></i>
                                                                                </div>
                                                                             <div class="popoverContent" style="display: none;">
                                                                             <div class="popover-title">{{ $label[527 - 1]->name }}</div>
                                                                             <div class="popover-content">{{ $label[527 - 1]->display }}</div>
                                                                             </div>
                                                                                @else
                                                                                 <label>Delivery Datetime</label>
                                                                                @endif
                                                                            <input class="form-control" id="auc_delivery_date" type="datetime-local"  placeholder="Delivery Datetime" name="auc_delivery_date"
                                                                                   value="" required>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                        @if ($label[528 - 1]->status == 1)
                                                                        <div class="Terminal-error">
                                                                            <label>Storage Pay</label>
                                                                                       <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                     style="cursor: pointer;"></i>
                                                                                </div>
                                                                             <div class="popoverContent" style="display: none;">
                                                                             <div class="popover-title">{{ $label[528 - 1]->name }}</div>
                                                                             <div class="popover-content">{{ $label[528 - 1]->display }}</div>
                                                                             </div>
                                                                                @else
                                                                                 <label>Storage Pay</label>
                                                                                @endif
                                                                            <input id="auc_who_pay_storage" name="auc_who_pay_storage" class="form-control" placeholder="Who Pay Storage" value="" required>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                        @if ($label[529 - 1]->status == 1)
                                                                        <div class="Terminal-error">
                                                                            <label>Title</label>
                                                                              <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                     style="cursor: pointer;"></i>
                                                                                </div>
                                                                             <div class="popoverContent" style="display: none;">
                                                                             <div class="popover-title">{{ $label[529 - 1]->name }}</div>
                                                                             <div class="popover-content">{{ $label[529 - 1]->display }}</div>
                                                                             </div>
                                                                                @else
                                                                                  <label>Title</label>
                                                                                @endif
                                                                            <select id="auc_title_keys" name="auc_title_keys" class="form-control h-50" required>
                                                                                <option value="" selected disabled>Select</option>
                                                                                <option value="Yes">Yes</option>
                                                                                <option value="No">No</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                         @if ($label[530 - 1]->status == 1)
                                                                        <div class="Terminal-error">
                                                                            <label>Key</label>
                                                                                <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                     style="cursor: pointer;"></i>
                                                                                </div>
                                                                             <div class="popoverContent" style="display: none;">
                                                                             <div class="popover-title">{{ $label[530 - 1]->name }}</div>
                                                                             <div class="popover-content">{{ $label[530 - 1]->display }}</div>
                                                                             </div>
                                                                                @else
                                                                                   <label>Key</label>
                                                                                @endif
                                                                            <select id="auc_keys" name="auc_keys" class="form-control h-50" required>
                                                                                <option value="" selected disabled>Select</option>
                                                                                <option value="Yes">Yes</option>
                                                                                <option value="No">No</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                           @if ($label[531 - 1]->status == 1)
                                                                        <div class="Terminal-error">
                                                                            <label>Client & Status</label>
                                                                                 <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                     style="cursor: pointer;"></i>
                                                                                </div>
                                                                             <div class="popoverContent" style="display: none;">
                                                                             <div class="popover-title">{{ $label[531 - 1]->name }}</div>
                                                                             <div class="popover-content">{{ $label[531 - 1]->display }}</div>
                                                                             </div>
                                                                                @else
                                                                                    <label>Client & Status</label>
                                                                                @endif
                                                                            <select id="auc_client_status" name="auc_client_status" class="form-control h-50" required>
                                                                                <option value="" selected disabled>Select</option>
                                                                                <option value="Yes">Yes</option>
                                                                                <option value="No">No</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                      @if ($label[532 - 1]->status == 1)
                                                                        <div class="Terminal-error">
                                                                            <label>Owes Status</label>
                                                                                        <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                     style="cursor: pointer;"></i>
                                                                                </div>
                                                                             <div class="popoverContent" style="display: none;">
                                                                             <div class="popover-title">{{ $label[532 - 1]->name }}</div>
                                                                             <div class="popover-content">{{ $label[532 - 1]->display }}</div>
                                                                             </div>
                                                                                @else
                                                                                    <label>Owes Status</label>
                                                                                @endif
                                                                            <select id="auc_owes_status" name="auc_owes_status" class="form-control h-50" required>
                                                                                <option value="" selected disabled>Select</option>
                                                                                <option value="Yes">Yes</option>
                                                                                <option value="No">No</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                         @if ($label[533 - 1]->status == 1)
                                                                        <div class="Terminal-error">
                                                                            <label>Additional</label>
                                                                           <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                     style="cursor: pointer;"></i>
                                                                                </div>
                                                                             <div class="popoverContent" style="display: none;">
                                                                             <div class="popover-title">{{ $label[533 - 1]->name }}</div>
                                                                             <div class="popover-content">{{ $label[533 - 1]->display }}</div>
                                                                             </div>
                                                                                @else
                                                                                   <label>Additional</label>
                                                                                @endif
                                                                            <input class="form-control" id="auc_additional" placeholder="Additional" name="auc_additional" value="" required>
                                                                        </div>
                                                                    `);
                                                                    $(document).ready(function() {
                                                                        // Select all error icons within the document
                                                                        var $errorIcons = $('.Terminal-error i');
                                                                        var $openPopoverContent = null;

                                                                        // Iterate over each error icon
                                                                        $errorIcons.each(function() {
                                                                            var $errorIcon = $(this);
                                                                            var $popoverContent = $errorIcon.closest(
                                                                                '.Terminal-error').siblings('.popoverContent');
                                                                            // Toggle the popover on icon click
                                                                            $errorIcon.on('click', function(event) {
                                                                                event
                                                                                    .stopPropagation(); // Prevent the document click event from firing immediately

                                                                                // Close the previously open popover content
                                                                                if ($openPopoverContent && !
                                                                                    $openPopoverContent.is($popoverContent)
                                                                                ) {
                                                                                    $openPopoverContent.hide();
                                                                                }

                                                                                // Toggle the current popover content
                                                                                $popoverContent.toggle();
                                                                                $openPopoverContent = $popoverContent;
                                                                            });
                                                                        });

                                                                        // Close the popover if clicked outside
                                                                        $(document).on('click', function(event) {
                                                                            if ($openPopoverContent && !$errorIcons.is(event
                                                                                    .target) && !$openPopoverContent.is(event
                                                                                    .target) &&
                                                                                $openPopoverContent
                                                                                .has(event.target).length === 0) {
                                                                                $openPopoverContent.hide();
                                                                                $openPopoverContent = null;
                                                                            }
                                                                        });
                                                                    });
                                                                    $(".driverphoneno").keypress(function(e) {
                                                                        if ($(this).val() == '') {
                                                                            $(this).mask("(999) 999-9999");
                                                                        }
                                                                        var x = e.which || e.keycode;
                                                                        if ((x >= 48 && x <= 57) || (x >= 35 && x <= 40)) {
                                                                            return true;
                                                                        } else {
                                                                            return false;
                                                                        }
                                                                    })
                                                                }
                                                            })
                                                        } else {
                                                            $(".deliverdate").html('');

                                                        }
                                                    })
                                                </script>
                                                <div class="col-md-3">
                                                    @if ($label[512 - 1]->status == 1)
                                                        <div class="Terminal-error">
                                                            <label>Review</label>
                                                            <i id="errorIcon"
                                                                class="fas fa-info-circle fa-lg text-info info-icon"
                                                                style="cursor: pointer;"></i>
                                                        </div>
                                                        <div class="popoverContent" style="display: none;">
                                                            <div class="popover-title">{{ $label[512 - 1]->name }}</div>
                                                            <div class="popover-content">{{ $label[512 - 1]->display }}
                                                            </div>
                                                        </div>
                                                    @else
                                                        <label>Review</label>
                                                    @endif
                                                    <select id="auc_reviewss" name="auc_review"
                                                        class="form-control h-50 auc_review" required>
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 all_rating" id="all_rating"
                                                    style="display:none;">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            @if ($label[513 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Website</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[513 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[513 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Website</label>
                                                            @endif
                                                            <select id="auc_website" name="auc_website"
                                                                class="form-control h-50">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="BBB">BBB</option>
                                                                <option value="Trust Pilot">Trust Pilot</option>
                                                                <option value="Google">Google</option>
                                                                <option value="Other">Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-6" style="display:none;"
                                                            id="other_website">
                                                            @if ($label[514 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Other Website</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[514 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[514 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Other Website</label>
                                                            @endif
                                                            <input class="form-control h-50" id="auc_website_other"
                                                                name="auc_website_other" placeholder="Other Website"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-6">
                                                            @if ($label[515 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Rating</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[515 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[515 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Rating</label>
                                                            @endif
                                                            <select id="auc_client_rating" name="auc_client_rating"
                                                                class="form-control h-50">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="Positive">Positive</option>
                                                                <option value="Neutral">Neutral</option>
                                                                <option value="Negative">Negative</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            @if ($label[516 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Website Link</label>
                                                                    <i id="errorIcon"
                                                                        class="fas fa-info-circle fa-lg text-info info-icon"
                                                                        style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                    <div class="popover-title">
                                                                        {{ $label[516 - 1]->name }}</div>
                                                                    <div class="popover-content">
                                                                        {{ $label[516 - 1]->display }}</div>
                                                                </div>
                                                            @else
                                                                <label>Website Link</label>
                                                            @endif
                                                            <input class="form-control h-50" id="auc_website_link"
                                                                name="auc_website_link" placeholder="Website Link"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label>Reviewer</label>
                                                            <input type="text" class="form-control" id=""
                                                                name="auc_reviewer" placeholder="Reviewer"
                                                                value="">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label>Attach Screenshot</label>
                                                            <input type="file" class="form-control" id=""
                                                                name="screenshot" placeholder="screenshot"
                                                                value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif

                                        @if (\Request::is('delivered'))
                                            <form method="post" enctype="multipart/form-data"
                                                action="{{ route('call_history_post') }}" id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">Delivered HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="12">Deliver</option>
                                                                <option value="13">Completed</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6 expectdate">
                                                    </div>
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row auctionupdate mb-2"></div>
                                                <script>
                                                    $("#pstatus").on('change', function() {
                                                        if ($(this).val() == 13) {
                                                            $(".expectdate").html(`
                                                                <div class="form-group">
                                                                    <label class="form-label">EXPECTED DATE</label>
                                                                    <input type="date" required name="expected_date"
                                                                           id='expected_date'
                                                                           class="form-control">
                                                                </div>
                                                            `);
                                                            $(".auctionupdate").html(`
                                                                <input type="hidden" value="13" name="pstatus222">
                                                                <input type="hidden" value="sheet_detail" name="sheet_detail">
                                                                <div class="col-md-12 text-center"><h4>Completed Sheet</h4></div>
                                                                <div class="col-md-3">
                                                                    @if ($label[509 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Remarks Status</label>
                                                                    <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[509 - 1]->name }}</div>
                                                                        <div class="popover-content">{{ $label[509 - 1]->display }}</div>
                                                                    </div>
                                                                @else
                                                                    <label>Remarks Status</label>
                                                                @endif
                                                                    <input class="form-control h-50" id="auc_remarks" name="auc_remarks" placeholder="Remarks Status" value="" required>
                                                                </div>
                                                                <div class="col-md-3">
                                                                  @if ($label[510 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Comments</label>
                                                                         <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[510 - 1]->name }}</div>
                                                                        <div class="popover-content">{{ $label[510 - 1]->display }}</div>
                                                                    </div>
                                                                @else
                                                                    <label>Comments</label>
                                                                @endif
                                                                    <input class="form-control h-50" id="auc_comments" name="auc_comments" placeholder="Comments" value="" required>
                                                                </div>
                                                                <div class="col-md-3">
                                                                @if ($label[511 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Satisfied?</label>
                                                                    <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[511 - 1]->name }}</div>
                                                                        <div class="popover-content">{{ $label[511 - 1]->display }}</div>
                                                                    </div>
                                                                @else
                                                                    <label>Comments</label>
                                                                @endif
                                                                    <input class="form-control h-50" id="auc_satisfied" name="auc_satisfied" placeholder="How you Satisfied?" value="" required>
                                                                </div>
                                                                <div class="col-md-3">
                                                                 @if ($label[512 - 1]->status == 1)
                                                                <div class="Terminal-error">
                                                                    <label>Review</label>
                                                                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                </div>
                                                                <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[512 - 1]->name }}</div>
                                                                        <div class="popover-content">{{ $label[512 - 1]->display }}</div>
                                                                    </div>
                                                                @else
                                                                   <label>Review</label>
                                                                @endif
                                                                    <select id="auc_review" name="auc_review" class="form-control h-50 auc_review" required>
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-12 all_rating" id="all_rating" style="display:none;">
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                         @if ($label[513 - 1]->status == 1)
                                                                          <div class="Terminal-error">
                                                                            <label>Website</label>
                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                style="cursor: pointer;"></i>
                                                                            </div>
                                                                            <div class="popoverContent" style="display: none;">
                                                                                    <div class="popover-title">{{ $label[513 - 1]->name }}</div>
                                                                                    <div class="popover-content">{{ $label[513 - 1]->display }}</div>
                                                                                </div>
                                                                            @else
                                                                                <label>Website</label>
                                                                            @endif
                                                                            <select id="auc_website" name="auc_website" class="form-control h-50">
                                                                                <option value="" selected disabled>Select</option>
                                                                                <option value="BBB">BBB</option>
                                                                                <option value="Trust Pilot">Trust Pilot</option>
                                                                                <option value="Google">Google</option>
                                                                                <option value="Other">Other</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-sm-6" style="display:none;" id="other_website">
                                                                         @if ($label[514 - 1]->status == 1)
                                                                          <div class="Terminal-error">
                                                                            <label>Other Website</label>
                                                                            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                style="cursor: pointer;"></i>
                                                                            </div>
                                                                            <div class="popoverContent" style="display: none;">
                                                                                    <div class="popover-title">{{ $label[514 - 1]->name }}</div>
                                                                                    <div class="popover-content">{{ $label[514 - 1]->display }}</div>
                                                                                </div>
                                                                            @else
                                                                               <label>Other Website</label>
                                                                            @endif
                                                                            <input class="form-control h-50" id="auc_website_other" name="auc_website_other" placeholder="Other Website" value="">
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                         @if ($label[515 - 1]->status == 1)
                                                                          <div class="Terminal-error">
                                                                            <label>Rating</label>
                                                                              <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                style="cursor: pointer;"></i>
                                                                            </div>
                                                                            <div class="popoverContent" style="display: none;">
                                                                                    <div class="popover-title">{{ $label[515 - 1]->name }}</div>
                                                                                    <div class="popover-content">{{ $label[515 - 1]->display }}</div>
                                                                                </div>
                                                                            @else
                                                                                 <label>Rating</label>
                                                                            @endif
                                                                            <select id="auc_client_rating" name="auc_client_rating" class="form-control h-50">
                                                                                <option value="" selected disabled>Select</option>
                                                                                <option value="Positive">Positive</option>
                                                                                <option value="Neutral">Neutral</option>
                                                                                <option value="Negative">Negative</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                         @if ($label[516 - 1]->status == 1)
                                                                        <div class="Terminal-error">
                                                                                <label>Website Link</label>
                                                                                <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                                style="cursor: pointer;"></i>
                                                                        </div>
                                                                        <div class="popoverContent" style="display: none;">
                                                                                    <div class="popover-title">{{ $label[516 - 1]->name }}</div>
                                                                                    <div class="popover-content">{{ $label[516 - 1]->display }}</div>
                                                                        </div>
                                                                            @else
                                                                              <label>Website Link</label>
                                                                            @endif
                                                                            <input class="form-control h-50" id="auc_website_link" name="auc_website_link" placeholder="Website Link" value="">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                 @if ($label[517 - 1]->status == 1)
                                                                    <div class="Terminal-error">
                                                                    <label>Additional</label>
                                                                    <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>
                                                                    <div class="popoverContent" style="display: none;">
                                                                                <div class="popover-title">{{ $label[517 - 1]->name }}</div>
                                                                                <div class="popover-content">{{ $label[517 - 1]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                      <label>Additional</label>
                                                                    @endif
                                                                    <input class="form-control" id="auc_additional" placeholder="Additional" name="auc_additional" value="" required>
                                                                </div>
                                                            `);
                                                            $(document).ready(function() {
                                                                // Select all error icons within the document
                                                                var $errorIcons = $('.Terminal-error i');
                                                                var $openPopoverContent = null;

                                                                // Iterate over each error icon
                                                                $errorIcons.each(function() {
                                                                    var $errorIcon = $(this);
                                                                    var $popoverContent = $errorIcon.closest('.Terminal-error').siblings(
                                                                        '.popoverContent');
                                                                    // Toggle the popover on icon click
                                                                    $errorIcon.on('click', function(event) {
                                                                        event
                                                                            .stopPropagation(); // Prevent the document click event from firing immediately

                                                                        // Close the previously open popover content
                                                                        if ($openPopoverContent && !$openPopoverContent.is(
                                                                                $popoverContent)) {
                                                                            $openPopoverContent.hide();
                                                                        }

                                                                        // Toggle the current popover content
                                                                        $popoverContent.toggle();
                                                                        $openPopoverContent = $popoverContent;
                                                                    });
                                                                });

                                                                // Close the popover if clicked outside
                                                                $(document).on('click', function(event) {
                                                                    if ($openPopoverContent && !$errorIcons.is(event.target) && !
                                                                        $openPopoverContent.is(event.target) && $openPopoverContent
                                                                        .has(event.target).length === 0) {
                                                                        $openPopoverContent.hide();
                                                                        $openPopoverContent = null;
                                                                    }
                                                                });
                                                            });
                                                            $(document).on("change", "#auc_website", function() {
                                                                if ($(this).val() == 'Other') {
                                                                    $("#other_website").show();
                                                                } else {
                                                                    $("#other_website").hide();
                                                                }
                                                            })
                                                            $(document).on("change", "#auc_review", function() {
                                                                if ($(this).val() == 'Yes') {
                                                                    $("#all_rating").show();
                                                                } else {
                                                                    $("#all_rating").hide();
                                                                }
                                                            })
                                                        } else {
                                                            $(".expectdate").html('');

                                                        }
                                                    })
                                                </script>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif

                                        @if (\Request::is('completed'))
                                            <form method="post" enctype="multipart/form-data"
                                                action="{{ route('call_history_post') }}" id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">Completed HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="13">Completed</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!--<div class="col-sm-6 col-md-6">-->
                                                    <!--    <div class="form-group">-->
                                                    <!--        <label class="form-label">EXPECTED DATE</label>-->
                                                    <!--        <input type="date" required name="expected_date"-->
                                                    <!--               id='expected_date' -->
                                                    <!--               class="form-control">-->
                                                    <!--    </div>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif

                                        @if (\Request::is('owns_money'))
                                            <form method="post" enctype="multipart/form-data"
                                                action="{{ route('call_history_post') }}" id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">OWES MONEY HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="16">OWES MONEY</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">EXPECTED DATE</label>
                                                            <input type="date" required name="expected_date"
                                                                id='expected_date' class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif
                                        @if (\Request::is('cancel'))
                                            <form method="post" enctype="multipart/form-data"
                                                action="{{ route('call_history_post') }}" id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">Cancel HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="14">Cancel</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!--<div class="col-sm-6 col-md-6">-->
                                                    <!--    <div class="form-group">-->
                                                    <!--        <label class="form-label">EXPECTED DATE</label>-->
                                                    <!--        <input type="date" required name="expected_date"-->
                                                    <!--               id='expected_date' -->
                                                    <!--               class="form-control">-->
                                                    <!--    </div>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif

                                        @if (\Request::is('onapproval'))
                                            <form method="post" enctype="multipart/form-data"
                                                action="{{ route('call_history_post') }}" id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">On Approval HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>
                                                    <input type="hidden" value="0" name="cancelDirectOnApproval"
                                                        id="cancelDirectOnApproval">

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control cancelOnApproval">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="18">ON APPROVAL</option>
                                                                <option value="19">ONAPPROVAL CANCEL</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">EXPECTED DATE</label>
                                                            <input type="date" required name="expected_date" disabled
                                                                id='expected_date' class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-5 col-md-5">
                                                        <div class="form-group">
                                                            <label class="form-label">LISTED PRICE</label>
                                                            <input type="number" required name="listed_price"
                                                                id='listed_price' disabled class="form-control">
                                                        </div>
                                                    </div>
                                                    @php
                                                        $ptype = 1;
                                                        $query = \App\user_setting::where(
                                                            'user_id',
                                                            Auth::user()->id,
                                                        )->first();
                                                        if (!empty($query)) {
                                                            $ptype = $query['penal_type'];
                                                        }

                                                        if ($ptype == 1) {
                                                            $phoneaccess = explode(',', Auth::user()->emp_access_phone);
                                                        } elseif ($ptype == 2) {
                                                            $phoneaccess = explode(',', Auth::user()->emp_access_web);
                                                        } elseif ($ptype == 3) {
                                                            $phoneaccess = explode(',', Auth::user()->emp_access_test);
                                                        } elseif ($ptype == 4) {
                                                            $phoneaccess = explode(',', Auth::user()->panel_type_4);
                                                        } elseif ($ptype == 5) {
                                                            $phoneaccess = explode(',', Auth::user()->panel_type_5);
                                                        } elseif ($ptype == 6) {
                                                            $phoneaccess = explode(',', Auth::user()->panel_type_6);
                                                        } else {
                                                            $phoneaccess = [];
                                                        }
                                                    @endphp
                                                    @if (in_array('76', $phoneaccess))
                                                        <?php
                                                        $dis = \App\User::with('daily_ass')
                                                            ->whereHas('userRole', function ($q) {
                                                                $q->where('name', 'Dispatcher');
                                                            })
                                                            ->where('deleted', 0)
                                                            ->get();
                                                        ?>
                                                        <div class="col-sm-3 col-md-3 my-auto">
                                                            <button class="btn btn-primary" type="button"
                                                                id="showingDispatchers" disabled>Assign
                                                                Dispatcher</button>
                                                        </div>
                                                        <div class="col-sm-4 col-md-4" id="showDispatchers"
                                                            style="display:none;">
                                                            <div class="form-group">
                                                                <label class="form-label">Dispatchers <span
                                                                        class="text-muted">(Optional)</span></label>
                                                                <select name="dis_id" id='dis_id'
                                                                    class="form-control">
                                                                    <option value="" selected disabled>Select
                                                                    </option>
                                                                    @foreach ($dis as $key => $dispa)
                                                                        <option value="{{ $dispa->id }}">
                                                                            {{ $dispa->slug ?? $dispa->name . ' ' . $dispa->last_name }}
                                                                            ({{ isset($dispa->daily_ass->total_quote) ? $dispa->daily_ass->total_quote . ' Left' : 'Unlimited' }})
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row auctionupdate mb-2"></div>
                                                <script>
                                                    $("#pstatus").on('change', function() {
                                                        if ($(this).val() == 9) {
                                                            $(".auctionupdate").html(`
                                                                <input type="hidden" value="9" name="pstatus222">
                                                                <input type="hidden" value="sheet_detail" name="sheet_detail">
                                                                <div class="col-md-12 text-center"><h4>Listed Sheet</h4></div>
                                                                <div class="col-md-4">
                                                                 @if ($label[410]->status == 1)
                                                                  <div class="Terminal-error">
                                                                    <label>Paid</label>
                                                                    <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[410]->name }}</div>
                                                                        <div class="popover-content">{{ $label[410]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                     <label>Paid</label>
                                                                    @endif
                                                                    <select name="auc_paid" id="auc_paid" class="form-control h-50">
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    @if ($label[411]->status == 1)
                                                                  <div class="Terminal-error">
                                                                     <label>Storage</label>
                                                                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[411]->name }}</div>
                                                                        <div class="popover-content">{{ $label[411]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                     <label>Storage</label>
                                                                    @endif
                                                                    <input class="form-control" id="auc_storage" name="auc_storage" placeholder="Storage" value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                  @if ($label[412]->status == 1)
                                                                  <div class="Terminal-error">
                                                                    <label>Listed Price</label>
                                                                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[412]->name }}</div>
                                                                        <div class="popover-content">{{ $label[412]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                      <label>Listed Price</label>
                                                                    @endif
                                                                    <input class="form-control" id="auc_listed_price" placeholder="Listed Price" name="auc_listed_price" value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                 @if ($label[413]->status == 1)
                                                                  <div class="Terminal-error">
                                                                    <label>Auction Update</label>
                                                                      <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[413]->name }}</div>
                                                                        <div class="popover-content">{{ $label[413]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                       <label>Auction Update</label>
                                                                    @endif
                                                                    <input class="form-control" id="auc_auction_update" placeholder="Auction Update" name="auc_auction_update"
                                                                           value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                  @if ($label[414]->status == 1)
                                                                  <div class="Terminal-error">
                                                                    <label>Title</label>
                                                                    <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[414]->name }}</div>
                                                                        <div class="popover-content">{{ $label[414]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                       <label>Title</label>
                                                                    @endif
                                                                    <select id="auc_title_keys" name="auc_title_keys" class="form-control h-50">
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                @if ($label[415]->status == 1)
                                                                  <div class="Terminal-error">
                                                                    <label>Key</label>
                                                                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[415]->name }}</div>
                                                                        <div class="popover-content">{{ $label[415]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                     <label>Key</label>
                                                                    @endif
                                                                    <select id="auc_keys" name="auc_keys" class="form-control h-50">
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                 @if ($label[416]->status == 1)
                                                                  <div class="Terminal-error">
                                                                    <label>Listed Count</label>
                                                                    <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[416]->name }}</div>
                                                                        <div class="popover-content">{{ $label[416]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                     <label>Listed Count</label>
                                                                    @endif
                                                                    <input class="form-control" id="auc_listed_count" placeholder="Listed Count" name="auc_listed_count" value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                 @if ($label[417]->status == 1)
                                                                  <div class="Terminal-error">
                                                                    <label>Old/New Price</label>
                                                                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[417]->name }}</div>
                                                                        <div class="popover-content">{{ $label[417]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                      <label>Old/New Price</label>
                                                                    @endif
                                                                    <input class="form-control" id="auc_price" placeholder="Old / New Price" name="auc_price"
                                                                           value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                 @if ($label[418]->status == 1)
                                                                  <div class="Terminal-error">
                                                                    <label>Vehicle Position</label>
                                                                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[418]->name }}</div>
                                                                        <div class="popover-content">{{ $label[418]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                      <label>Vehicle Position</label>
                                                                    @endif
                                                                    <input class="form-control" placeholder="Vehicle Position" id="auc_port" name="auc_port" value="">
                                                                </div>
                                                                <div class="col-md-12">
                                                                 @if ($label[419]->status == 1)
                                                                  <div class="Terminal-error">
                                                                    <label>Additional</label>
                                                                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                                    style="cursor: pointer;"></i>
                                                                    </div>

                                                                    <div class="popoverContent" style="display: none;">
                                                                        <div class="popover-title">{{ $label[419]->name }}</div>
                                                                        <div class="popover-content">{{ $label[419]->display }}</div>
                                                                    </div>
                                                                    @else
                                                                       <label>Additional</label>
                                                                    @endif
                                                                    <input class="form-control" id="auc_additional" placeholder="Additional" name="auc_additional" value="">
                                                                </div>
                                                            `);
                                                            $(document).ready(function() {
                                                                // Select all error icons within the document
                                                                var $errorIcons = $('.Terminal-error i');
                                                                var $openPopoverContent = null;

                                                                // Iterate over each error icon
                                                                $errorIcons.each(function() {
                                                                    var $errorIcon = $(this);
                                                                    var $popoverContent = $errorIcon.closest('.Terminal-error').siblings(
                                                                        '.popoverContent');
                                                                    // Toggle the popover on icon click
                                                                    $errorIcon.on('click', function(event) {
                                                                        event
                                                                            .stopPropagation(); // Prevent the document click event from firing immediately

                                                                        // Close the previously open popover content
                                                                        if ($openPopoverContent && !$openPopoverContent.is(
                                                                                $popoverContent)) {
                                                                            $openPopoverContent.hide();
                                                                        }

                                                                        // Toggle the current popover content
                                                                        $popoverContent.toggle();
                                                                        $openPopoverContent = $popoverContent;
                                                                    });
                                                                });

                                                                // Close the popover if clicked outside
                                                                $(document).on('click', function(event) {
                                                                    if ($openPopoverContent && !$errorIcons.is(event.target) && !
                                                                        $openPopoverContent.is(event.target) && $openPopoverContent
                                                                        .has(event.target).length === 0) {
                                                                        $openPopoverContent.hide();
                                                                        $openPopoverContent = null;
                                                                    }
                                                                });
                                                            });
                                                            $("#expected_date").attr('disabled', false);
                                                            $("#listed_price").attr('disabled', false);
                                                            $("#showingDispatchers").attr('disabled', false);
                                                        } else if ($(this).val() == 18) {
                                                            $("#expected_date").attr('disabled', false);
                                                            $("#listed_price").attr('disabled', true);
                                                            $("#showingDispatchers").attr('disabled', true);
                                                            $("#showDispatchers").hide();
                                                            $("#dis_id").children('option').removeAttr('selected');
                                                            $("#dis_id").children('option').eq(0).attr('selected', true);
                                                        } else {
                                                            $("#expected_date").attr('disabled', true);
                                                            $("#listed_price").attr('disabled', true);
                                                            $("#showingDispatchers").attr('disabled', true);
                                                            $("#showDispatchers").hide();
                                                            $("#dis_id").children('option').removeAttr('selected');
                                                            $("#dis_id").children('option').eq(0).attr('selected', true);
                                                            $(".auctionupdate").html('');
                                                        }
                                                    })
                                                </script>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif

                                        @if (\Request::is('onapproval_cancel'))
                                            <form method="post" enctype="multipart/form-data"
                                                action="{{ route('call_history_post') }}" id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">On Approval Cancel HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-12 col-md-12" id="last_history"></div>

                                                    @php
                                                        $ptype = 1;
                                                        $query = \App\user_setting::where(
                                                            'user_id',
                                                            Auth::user()->id,
                                                        )->first();
                                                        if (!empty($query)) {
                                                            $ptype = $query['penal_type'];
                                                        }

                                                        if ($ptype == 1) {
                                                            $phoneaccess = explode(',', Auth::user()->emp_access_phone);
                                                        } elseif ($ptype == 2) {
                                                            $phoneaccess = explode(',', Auth::user()->emp_access_web);
                                                        } elseif ($ptype == 3) {
                                                            $phoneaccess = explode(',', Auth::user()->emp_access_test);
                                                        } elseif ($ptype == 4) {
                                                            $phoneaccess = explode(',', Auth::user()->panel_type_4);
                                                        } elseif ($ptype == 5) {
                                                            $phoneaccess = explode(',', Auth::user()->panel_type_5);
                                                        } elseif ($ptype == 6) {
                                                            $phoneaccess = explode(',', Auth::user()->panel_type_6);
                                                        } else {
                                                            $phoneaccess = [];
                                                        }
                                                    @endphp
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="" selected disabled>Select</option>
                                                                @if (in_array('77', $phoneaccess))
                                                                    <option value="14">CANCEL ORDER</option>
                                                                @else
                                                                    <option value="19">ONAPPROVAL CANCEL</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!--<div class="col-sm-6 col-md-6">-->
                                                    <!--    <div class="form-group">-->
                                                    <!--        <label class="form-label">EXPECTED DATE</label>-->
                                                    <!--        <input type="date" required name="expected_date"-->
                                                    <!--               id='expected_date' -->
                                                    <!--               class="form-control">-->
                                                    <!--    </div>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6 col-md-6" id="mistakers"></div>
                                                    <div class="col-sm-6 col-md-6" id="calls"></div>
                                                    <div class="col-sm-6 col-md-6" id="decisions"></div>

                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">
                                                                @if (in_array('77', $phoneaccess))
                                                                    Admin Remarks
                                                                @else
                                                                    HISTORY
                                                                @endif
                                                            </label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <script>
                                                    $("#pstatus").on('change', function() {
                                                        if ($(this).val() == 14) {
                                                            var order_id = $("#order_id1").val();
                                                            $.ajax({
                                                                url: "{{ url('/order_users') }}",
                                                                type: "GET",
                                                                data: {
                                                                    id: order_id
                                                                },
                                                                dataType: "JSON",
                                                                success: function(res) {
                                                                    var ot = 'No Order Taker';
                                                                    var dis = 'No Dispatcher Assigned';
                                                                    var ot_id = '';
                                                                    var dis_id = '';
                                                                    var both = '';
                                                                    if (res.ot) {
                                                                        ot = res.ot;
                                                                    }
                                                                    if (res.dis) {
                                                                        dis = res.dis;
                                                                    }
                                                                    if (res.ot_id) {
                                                                        ot_id = res.ot_id;
                                                                        both = res.ot_id;
                                                                    }
                                                                    if (res.dis_id) {
                                                                        dis_id = res.dis_id;
                                                                        both = both + ',' + res.dis_id
                                                                    }
                                                                    $("#mistakers").html(`
                                                                        <input type="hidden" name="mistaker_id" id="mistaker_id" />
                                                                        <div class="form-group">
                                                                            <label class="form-label">Mistaker</label>
                                                                            <select name="mistaker" id='mistaker' required class="form-control ">
                                                                                <option value="" selected disabled>Select</option>
                                                                                <option value="Customer" data-value="">Customer</option>
                                                                                <option value="${ot}" data-value="${ot_id}">${ot}</option>
                                                                                <option value="${dis}" data-value="${dis_id}">${dis}</option>
                                                                                <option value="Both" data-value="${both}">Both</option>
                                                                            </select>
                                                                        </div>
                                                                    `);
                                                                    $("#calls").html(`
                                                                        <div class="form-group">
                                                                            <label class="form-label">No Of Calls</label>
                                                                            <input type="number" required name="no_of_calls" id="no_of_calls" class="form-control" />
                                                                        </div>
                                                                    `);
                                                                    $("#decisions").html(`
                                                                        <div class="form-group">
                                                                            <label class="form-label">Decision</label>
                                                                            <input type="text" required name="decision" id="decision" class="form-control" />
                                                                        </div>
                                                                    `);

                                                                    $(document).on("change", "#mistaker", function() {
                                                                        $("#mistaker_id").val($(this).children('option:selected').data(
                                                                            'value'));
                                                                    })

                                                                    if (res.last_history) {
                                                                        $("#last_history").html(`
                                                                            <div class="message-feed media m-0 p-0">
                                                                                <div class="media-body">
                                                                                    <div class="mf-content w-100">
                                                                                        <h6>User: ${res.last_history.username}</h6>
                                                                                        ${res.last_history.history}
                                                                                        <h6>
                                                                                            <strong class="mf-date"><i class="fa fa-clock-o"></i>  ${res.last_history.created}</strong>
                                                                                        </h6>
                                                                                        <div class="row">
                                                                                            <div class="col-sm-6">
                                                                                                <input type="radio" value="Agree" name="agree_disagree" id="agree" /> <label class="ml-2" for="agree">Agree</label>
                                                                                            </div>
                                                                                            <div class="col-sm-6">
                                                                                                <input type="radio" checked value="Disagree" name="agree_disagree" id="disagree" /> <label class="ml-2" for="disagree">Disagree</label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        `);
                                                                    } else {
                                                                        $("#last_history").html(`
                                                                            <div class="message-feed media m-0 p-0">
                                                                                <div class="media-body">
                                                                                    <div class="mf-content w-100">
                                                                                        <h6>No Last History</h6>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        `);
                                                                    }
                                                                }
                                                            })
                                                        }
                                                    })
                                                </script>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif
                                        <script>
                                            $("#saveChangesForm").on('submit', function() {
                                                $(this).children('button[type="submit"]').attr('disabled', true);
                                            })
                                        </script>
                                    </div>
                                    <div class="tab-pane" id="tab2">
                                        <div class="chat-body-style ChatBody" id="calhistory"
                                            style="overflow:scroll; height:300px;">
                                            <div class="message-feed media">
                                                <div class="media-body" id="histories">
                                                    <div class="mf-content">
                                                        hi
                                                    </div>
                                                    <small class="mf-date"><i class="fa fa-clock-o"></i>2021-01-19
                                                        15:53:42
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--=============new==================-->
                                    <!--=========================Nature of Customer==================-->
                                    <div class="tab-pane" id="tab12">
                                        <form method="post" action="{{ route('updateNature') }}"
                                            id="updateCustomerNature">
                                            @csrf
                                            <div class="card-title font-weight-bold">
                                                Nature of Customer:
                                            </div>
                                            <div class="row">
                                                <input type="hidden" class="form-control" name="order_id"
                                                    id='nature_orderID' placeholder="" readonly>

                                                <div class="col-sm-6 col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">CHANGE STATUS</label>
                                                        <select name="status" id='nature_status' required
                                                            class="form-control ">
                                                            <option value="" selected disabled>Select</option>
                                                            <option value="Verified">Agree</option>
                                                            <option value="Unverified">Disagree</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">UPDATED BY</label>
                                                        <input type="text" class="form-control" name="user"
                                                            id='nature_user' placeholder="" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">DESCRIPTION</label>
                                                        <textarea required readonly name="description" id='nature_description' class="form-control"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">REMARKS</label>
                                                        <textarea name="remarks" id='nature_remarks' class="form-control"></textarea>
                                                    </div>
                                                </div>

                                            </div>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </form>
                                    </div>
                                    <!--=========================Nature of Customer==================-->
                                    @if (in_array('102', $phoneaccess))
                                        <div class="tab-pane" id="tab10">
                                            <div class="chat-body-style ChatBody" id="calhistory"
                                                style="overflow:scroll; height:300px;">
                                                <div class="message-feed media">
                                                    <div class="media-body" id="history-content">
                                                        {{-- {{ dd($allHistory->toArray()) }} --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <!--=============new==================-->
                                    <!--=============new form==================-->
                                    @if (in_array('101', $phoneaccess))
                                        <div class="tab-pane" id="tab11">
                                            <form id="yourFormId" class="form-horizontal text-center" method="POST" action="{{ route('store.carrier_approachings') }}" novalidate="novalidate" enctype="multipart/form-data">
                                                <div class="modal-body">
                                                    @csrf
                                                    <input type="hidden" class="val_order_id" name="order_id" />

                                                    <div class="form-group row">
                                                        <label for="extension"
                                                            class="col-sm-4 col-form-label">Extension</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="extension" id="extension"
                                                                class="form-control col-12" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="comp_name" class="col-sm-4 col-form-label">Company
                                                            Name</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="comp_name" id="comp_name"
                                                                class="form-control col-12" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="comp_phone" class="col-sm-4 col-form-label">Company
                                                            Phone</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="comp_phone" id="comp_phone"
                                                                class="form-control col-12" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="status"
                                                            class="col-sm-4 col-form-label">Status</label>
                                                        <div class="col-sm-8">
                                                            <select name="status" id="status"
                                                                class="form-control col-12" required>
                                                                <option value="1">Interested</option>
                                                                <option value="0">Not Interested</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="comp_response"
                                                            class="col-sm-4 col-form-label">Company
                                                            Response</label>
                                                        <div class="col-sm-8">
                                                            <textarea class="form-control" name="comp_response" id="comp_response" placeholder="Company's Response"
                                                                rows="12" cols="12" required></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success" id="save-disabled"
                                                        >Save changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    <!--=============new==================-->
                                    <div class="tab-pane" id="tab3">
                                        <form id="listedform2" method="post"
                                            action="{{ route('call_history_post_relist') }}">
                                            @csrf
                                            <div class="card-title font-weight-bold">Relist</div>
                                            <div class="row" id="row1">
                                                <input type="hidden" name="order_id1" id='order_id2'>
                                                <input type="hidden" id="pstatus2" name="pstatus" value="9">
                                                <input type="hidden" name="relist" value="1">


                                                <div class="col-sm-6 col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Lister Price</label>
                                                        <input type="number" required name="listed_price"
                                                            class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">EXPECTED DATE</label>
                                                        <input type="date" required name="expected_date"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-md-6" id="status_carrier_deleted">
                                                    <div class="form-group">
                                                        <label class="form-label">STATUS</label>
                                                        <select name="carrier_deleted" id="carrier_deleted"
                                                            class="form-control" required>
                                                            <option value="" selected disabled>SELECT</option>
                                                            <option value="CARRIER CANCEL">CARRIER CANCEL</option>
                                                            <option value="COMPANY CANCEL">COMPANY CANCEL</option>
                                                            <option value="CARRIER CANCEL AND RELIST">CARRIER CANCEL AND
                                                                RELIST</option>
                                                            <option value="COMPANY CANCEL AND RELIST">COMPANY CANCEL AND
                                                                RELIST</option>
                                                            <option value="OTHER">OTHER</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <script>
                                                    $(document).on('change', "#carrier_deleted", function() {
                                                        $("#status_other_reason").remove();
                                                        if ($(this).val() == 'OTHER') {
                                                            $("#status_carrier_deleted").after(`
                                                                <div class="col-sm-6 col-md-6" id="status_other_reason">
                                                                    <div class="form-group">
                                                                        <label class="form-label">OTHER REASON</label>
                                                                        <input type="text" required name="other_reason" id="other_reason" class="form-control">
                                                                    </div>
                                                                </div>
                                                            `);
                                                        } else if ($(this).val() == 'COMPANY CANCEL' || $(this).val() == 'COMPANY CANCEL AND RELIST') {
                                                            $("#status_carrier_deleted").after(`
                                                                <div class="col-sm-6 col-md-6" id="status_other_reason">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Select Company

                                                                        </label>
                                                                        <select id="company_cancel" class="form-control select_cancel"
                                                                                name="company_cancel" required
                                                                                style=" height: auto; "
                                                                                data-validation-required-message="This field is required">
                                                                            <option value="">Select Company</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            `);
                                                            var options = '';
                                                            var order_id2 = document.getElementById('order_id2').value;
                                                            $.ajax({

                                                                type: "GET",
                                                                url: "/get_carrier",
                                                                data: {
                                                                    'order_id': order_id2
                                                                },
                                                                dataType: "json",

                                                                success: function(data) {
                                                                    $.each(data, function(i, item) {

                                                                        if (item.id) {
                                                                            options = options + `<option value='` + item.id + `'>` + item
                                                                                .companyname + `</option>`;

                                                                        }
                                                                    });
                                                                    $("#company_cancel").append(options);
                                                                },
                                                                error: function(e) {
                                                                    alert("error");
                                                                }

                                                            });
                                                        }
                                                    })
                                                </script>

                                                <div class="col-sm-12 col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">HISTORY</label>
                                                        <textarea required name="history_update" class="form-control"></textarea>
                                                    </div>
                                                </div>

                                            </div>

                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </form>

                                    </div>
                                    <div class="tab-pane" id="tab8">
                                    </div>
                                    <div class="tab-pane" id="tab9">
                                        <div class="chat-body-style ChatBody" id="calhistory9"
                                            style="overflow:scroll; height:400px;">
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="tab13">
                                        <form id="updateProfileCardForm" class="form-horizontal text-center" method="POST" action="{{ route('updateProfileCard') }}" novalidate="novalidate" enctype="multipart/form-data">

                                            @csrf
                                            <div class="card-title font-weight-bold">
                                                Profile Card:
                                            </div>

                                            <input type="hidden" class="form-control" name="pc_order_id" id="pc_order_id" readonly>

                                            <div class="row">
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">First Name</label>
                                                        <input type="text" class="form-control" name="pc_first_name" id="pc_first_name">
                                                    </div>
                                                </div>

                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Last Name</label>
                                                        <input type="text" class="form-control" name="pc_last_name" id="pc_last_name">
                                                    </div>
                                                </div>

                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Email</label>
                                                        <input type="email" class="form-control" name="pc_oemail" id="pc_oemail">
                                                    </div>
                                                </div>

                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Phone</label>
                                                        <input type="text" class="form-control" name="pc_ophone" id="pc_ophone">
                                                    </div>
                                                </div>

                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Phone Number 2</label>
                                                        <input type="text" class="form-control" name="pc_ophone2" id="pc_ophone2">
                                                    </div>
                                                </div>

                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Business Address</label>
                                                        <input type="text" class="form-control" name="pc_address" id="pc_address">
                                                    </div>
                                                </div>

                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">State / Zip</label>
                                                        <input type="text" class="form-control" name="pc_originzsc" id="pc_originzsc">
                                                    </div>
                                                    <ul class="nav flex-column border scrollul"
                                                        style="max-height:200px;overflow:scroll;display:none;">
                                                    </ul>
                                                </div>

                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Payment Method</label>
                                                        <select id="pc_payment_method" name="pc_payment_method" class="form-control">
                                                            <option value="Cash/Certified Funds">Cash/Certified Funds</option>
                                                            <option value="Check">Check</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Nature of Customer</label>
                                                        <textarea type="text" maxlength="100" class="form-control" name="pc_nature_of_customer" id="pc_nature_of_customer"></textarea>
                                                    </div>
                                                </div>


                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Business Name</label>
                                                        <input type="text" class="form-control" name="pc_business_name" id="pc_business_name">
                                                    </div>
                                                </div>

                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label d-block">Customer Type</label>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="pc_customer_type" id="pc_customerTypeCustomer" value="Customer">
                                                            <label class="form-check-label" for="customerTypeCustomer">Customer</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="pc_customer_type" id="pc_customerTypeDealer" value="Dealer">
                                                            <label class="form-check-label" for="customerTypeDealer">Dealer</label>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>

                                            <div class="row" style="border: groove;padding: 5px">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label font-boldd ">Terminal, Dealer, Auction</label>
                                                        <select class="form-control parsley-error" name="pc_1oterminal"
                                                                id="pc_1oterminal"
                                                                onkeypress="$(this).css('border-color', 'rgb(92 166 242)');">

                                                            <option value="">Select an Option</option>
                                                            <option value="1">Residence</option>
                                                            <option value="2">COPART Auction</option>
                                                            <option value="3">Manheim Auction</option>
                                                            <option value="4">IAAI Auction</option>
                                                            <option value="5">Body Shop</option>
                                                            <option value="10">Dealership</option>
                                                            <option value="7">Business Location</option>
                                                            <option value="8">Auction (Heavy)</option>
                                                            <option value="6">Other</option>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="pc_1oauc" style="display: contents">

                                                </div>
                                            </div>

                                            <div class="row" style="border: groove;padding: 5px">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label font-boldd ">Terminal, Dealer, Auction</label>
                                                        <select class="form-control parsley-error" name="pc_2oterminal"
                                                                id="pc_2oterminal"
                                                                onkeypress="$(this).css('border-color', 'rgb(92 166 242)');">

                                                            <option value="">Select an Option</option>
                                                            <option value="1">Residence</option>
                                                            <option value="2">COPART Auction</option>
                                                            <option value="3">Manheim Auction</option>
                                                            <option value="4">IAAI Auction</option>
                                                            <option value="5">Body Shop</option>
                                                            <option value="10">Dealership</option>
                                                            <option value="7">Business Location</option>
                                                            <option value="8">Auction (Heavy)</option>
                                                            <option value="6">Other</option>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="pc_2oauc" style="display: contents">

                                                </div>
                                            </div>


                                            <div class="row" style="border: groove;padding: 5px">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label font-boldd ">Terminal, Dealer, Auction</label>
                                                        <select class="form-control parsley-error" name="pc_3oterminal"
                                                                id="pc_3oterminal"
                                                                onkeypress="$(this).css('border-color', 'rgb(92 166 242)');">

                                                            <option value="">Select an Option</option>
                                                            <option value="1">Residence</option>
                                                            <option value="2">COPART Auction</option>
                                                            <option value="3">Manheim Auction</option>
                                                            <option value="4">IAAI Auction</option>
                                                            <option value="5">Body Shop</option>
                                                            <option value="10">Dealership</option>
                                                            <option value="7">Business Location</option>
                                                            <option value="8">Auction (Heavy)</option>
                                                            <option value="6">Other</option>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="pc_3oauc" style="display: contents">

                                                </div>
                                            </div>

                                            <div class="row">
                                                <!-- First Quote Date -->
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">First Quote Date</label>
                                                        <input type="text" class="form-control" name="pc_first_quote_date" id="pc_first_quote_date" readonly>
                                                    </div>
                                                </div>

                                                <!-- First Quote Status -->
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">First Quote Status</label>
                                                        <input type="text" class="form-control" readonly name="pc_first_quote_status" id="pc_first_quote_status">
                                                    </div>
                                                </div>

                                                <!-- First Quote Agent -->
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">First Quote Agent</label>
                                                        <input type="text" class="form-control" readonly name="pc_first_quote_agent" id="pc_first_quote_agent">
                                                    </div>
                                                </div>

                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Recent Follow-up</label>
                                                        <input type="text" class="form-control" readonly name="pc_recent_follow_up" id="pc_recent_follow_up">
                                                    </div>
                                                </div>
                                                <!-- Recent Quote Date -->
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Recent Follow-up Date</label>
                                                        <input type="text" class="form-control" name="pc_recent_quote_date" id="pc_recent_quote_date" readonly>
                                                    </div>
                                                </div>

                                                <!-- Recent Quote Status -->
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Recent Follow-up Status</label>
                                                        <input type="text" class="form-control" name="pc_recent_quote_status" id="pc_recent_quote_status" readonly>
                                                    </div>
                                                </div>

                                                <!-- Recent Quote Agent -->
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Recent Follow-up Agent</label>
                                                        <input type="text" class="form-control" name="pc_recent_quote_agent" id="pc_recent_quote_agent" readonly>
                                                    </div>
                                                </div>

                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">History</label>
                                                        <a class="btn btn-primary btn-sm my-2" onclick="history('','')" id="pc_history" target="_blank">History</a>
                                                    </div>
                                                </div>

                                                <!-- Average Margin -->
                                                <div class="col-sm-4 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Average Margin ($)</label>
                                                        <input type="text" class="form-control" name="pc_average_margin" id="pc_average_margin" >
                                                    </div>
                                                </div>

                                                <!-- Customer Rating -->
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Customer Rating (1–5)</label>
                                                        <input type="number" min="1" max="5" class="form-control" name="pc_customer_rating" id="pc_customer_rating">
                                                    </div>
                                                </div>

                                                <!-- Social Profile Name -->
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Social Profile Name</label>
                                                        <input type="text" class="form-control" name="pc_social_profile_name" id="pc_social_profile_name">
                                                    </div>
                                                </div>

                                                <!-- Customer Review -->
                                                <div class="col-sm-12 col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Customer Reviews</label>
                                                        <textarea class="form-control" name="pc_customer_review" id="pc_customer_review" rows="3"></textarea>
                                                    </div>
                                                </div>


                                            </div>


                                            <button type="submit" class="btn btn-primary mt-3" id="save_profile_Card">Save changes</button>
                                        </form>

                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="schedule_delivery" tabindex="-1" role="dialog"
        aria-labelledby="schedule_delivery" aria-hidden="true">
        <div class="modal-dialog modal-lg " role="document" style="max-width:45%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="schedule_delivery1">Schedule For Delivery</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('/schedule_delivery') }}" method="POST">
                    @csrf
                    <input type="hidden" id="sd_order_id" name="id" />
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="payment_status" class="form-label">Payment Status</label>
                                    <input type="text" class="form-control" placeholder="Payment Status"
                                        name="payment_status" id="payment_status" required />
                                </div>
                            </div>
                            <!--<div class="col-sm-12">-->
                            <!--    <div class="form-group">-->
                            <!--        <label for="payment_status" class="form-label">Payment Status</label>-->
                            <!--        <input type="text" class="form-control" placeholder="Payment Status" name="payment_status" id="payment_status" required />-->
                            <!--    </div>-->
                            <!--</div>-->
                            <!--<div class="col-sm-12">-->
                            <!--    <div class="form-group">-->
                            <!--        <label for="payment_status" class="form-label">Payment Status</label>-->
                            <!--        <input type="text" class="form-control" placeholder="Payment Status" name="payment_status" id="payment_status" required />-->
                            <!--    </div>-->
                            <!--</div>-->
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="additional" class="form-label">Additional</label>
                                    <textarea rows="12" cols="12" class="form-control" name="additional" placeholder="Additional"
                                        id="additional" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="feedback" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="feedbackLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width:75% !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="feedbackLabel">Feedback</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">OrderId#</th>
                                <th class="text-center">Pickup</th>
                                <th class="text-center">Delivery</th>
                                <th class="text-center">Vehicle Name</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="id" class="text-center"></td>
                                <td id="pickup" class="text-center"></td>
                                <td id="delivery" class="text-center"></td>
                                <td id="vehicle_name" class="text-center"></td>
                                <td id="date" class="text-center"></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary" title="Review Link"
                                            data-toggle="modal" data-target="#staticBackdrop1"
                                            onclick="sendLink()">Send Link</button>
                                        <button type="button" class="btn btn-info" title="Coupon"
                                            data-toggle="modal" data-target="#staticBackdrop">Send Coupon</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Feedback</label>
                        <textarea placeholder="Feedback..." class="form-control mb-2" name="feedback" id="feedbackDetail"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="d-flex justify-content-center mb-2">
                            <div class="form-group mx-2 mb-0">
                                <label class="form-label px-2 py-1 checkRate" style="cursor:pointer;" for="positive">
                                    <i class="fa fa-smile-o m-0" aria-hidden="true"></i>
                                    Positive
                                </label>
                                <input type="radio" name="rate" id="positive" value="5"
                                    style="display:none;" />
                            </div>
                            <div class="form-group mx-2 mb-0">
                                <label class="form-label px-2 py-1 checkRate" style="cursor:pointer;" for="neutral">
                                    <i class="fa fa-meh-o m-0" aria-hidden="true"></i>
                                    Neutral
                                </label>
                                <input type="radio" name="rate" id="neutral" value="3"
                                    style="display:none;" />
                            </div>
                            <div class="form-group mx-2 mb-0">
                                <label class="form-label px-2 py-1 checkRate" style="cursor:pointer;" for="negative">
                                    <i class="fa fa-frown-o m-0" aria-hidden="true"></i>
                                    Negative
                                </label>
                                <input type="radio" name="rate" id="negative" value="1"
                                    style="display:none;" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submit2">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ratingPopup" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="ratingPopupLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width:75% !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ratingPopupLabel">Rate</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <input type="hidden" id="ord_id" name="ord_id" />
                <div id="ratingPopupContent">

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Create Coupon</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('/coupons/create') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Coupon Number</label>
                            <input type="text" maxlength="10" name="coupon_number" id="coupon_number"
                                class="form-control mb-2" placeholder="Enter Coupon Number" />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Coupon Price</label>
                            <input type="text" name="coupon_price" id="coupon_price" class="form-control mb-2"
                                placeholder="Enter Coupon Price" />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Coupon Email</label>
                            <input type="email" name="coupon_email" id="coupon_email" class="form-control mb-2"
                                placeholder="Enter Coupon Email" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="submit">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="staticBackdrop1" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdrop1Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdrop1Label">Send Link</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="order_id" name="order_id" />
                    <div class="form-group">
                        <label class="form-label">Review Website</label>
                        <select name="website" id="website" class="form-control mb-2">
                            <option value="" selected disabled>Select Website</option>
                            @foreach ($link as $key => $value)
                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Customer Email</label>
                        <input type="email" name="customer_email" id="customer_email" class="form-control mb-2"
                            placeholder="Enter Customer Email" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="send">Send</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="feedback2" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="feedback2Label" aria-hidden="true">
        <div class="modal-dialog" style="max-width:75% !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="feedback2Label">Feedback</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">OrderId#</th>
                                <th class="text-center">Pickup</th>
                                <th class="text-center">Delivery</th>
                                <th class="text-center">Vehicle Name</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Review Email Clicked</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="id2" class="text-center"></td>
                                <td id="pickup2" class="text-center"></td>
                                <td id="delivery2" class="text-center"></td>
                                <td id="vehicle_name2" class="text-center"></td>
                                <td id="date2" class="text-center"></td>
                                <td class="text-center clicked">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <h2>Feedback:</h2>
                        <div class="row">
                            <div class="col-sm-2 m-auto d-inline-block text-center rate">
                            </div>
                            <div class="col-sm-10 feedback">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" style="max-width:85%;">
            <div class="modal-content">
                <div class="d-flex justify-content-between mt-5">
                    <h5 class="text-center my-auto ml-4" id="order_detail_status">Order Status</h5>
                    <h5 class="text-center my-auto" id="order_detail_title">Order Detail</h5>
                    <button type="button" class="close bg-danger text-white mr-4" data-dismiss="modal"
                        aria-label="Close" style="height: 25px;width: 25px;border-radius: 50%;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="detail_order">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewCancelHistory" tabindex="-1" aria-labelledby="viewCancelHistoryLabel"
        aria-hidden="true">
        <div class="modal-dialog" style="max-width:55%;">
            <div class="modal-content">
                <div class="d-flex justify-content-between mt-5">
                    <h5 class="text-center my-auto ml-4" id="viewCancelHistoryTitle">View Cancel History</h5>
                    <button type="button" class="close bg-danger text-white mr-4" data-dismiss="modal"
                        aria-label="Close" style="height: 25px;width: 25px;border-radius: 50%;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="cancel_history">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="requestShipment" tabindex="-1" aria-labelledby="requestShipmentLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="d-flex justify-content-between mt-5">
                    <h5 class="text-center my-auto ml-4" id="requestShipmentTitle">Request</h5>
                    <button type="button" class="close bg-danger text-white mr-4" data-dismiss="modal"
                        aria-label="Close" style="height: 25px;width: 25px;border-radius: 50%;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('request_shipment') }}" method="POST">
                    @csrf
                    <input type="hidden" name="order_id_request" id="order_id_request" />
                    <input type="hidden" name="status_request" id="status_request" />
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="request_name" class="form-label">Request Name</label>
                                    <select required name="request_name" id="request_name" class="form-control">
                                        <option value="" selected disbaled data-value="">Select</option>
                                        <option value="Relist" data-value="20">Relist</option>
                                        <option value="Price Raise" data-value="21">Price Raise</option>
                                        <option value="Approach Id" data-value="22">Approach Id</option>
                                        <option value="Different Port" data-value="23">Different Port</option>
                                        <option value="Carrier Update" data-value="24">Carrier Update</option>
                                        <option value="Storage" data-value="25">Storage</option>
                                        <option value="Approaching" data-value="26">Approaching</option>
                                        <option value="Auction Update" data-value="27">Auction Update</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12" id="keyvalue"></div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="additional_request" class="form-label">Additional</label>
                                    <textarea rows="12" cols="12" class="form-control" id="additional_request"
                                        name="additional_request"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('extraScript')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>



    <script>
        //=================onchange-values=============================
        $(document).ready(function() {

            $('#yourFormId').on('submit', function (e) {
                e.preventDefault(); // Prevent default form submission

                $('#save-disabled').prop('disabled',true);
                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    url: "{{ route('store.carrier_approachings') }}",
                    type: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        // Handle success (show message, reset form, etc.)
                        alert('Carrier approaching saved successfully!');
                        form[0].reset();

                        $('#save-disabled').prop('disabled',false);
                    },
                    error: function (xhr) {
                        // Handle error
                        alert('An error occurred. Please check your input.');
                        console.error(xhr.responseText);
                    }
                });
            });

            // Select all error icons within the document
            var $errorIcons = $('.Terminal-error i');
            var $openPopoverContent = null;

            // Iterate over each error icon
            $errorIcons.each(function() {
                var $errorIcon = $(this);
                var $popoverContent = $errorIcon.closest('.Terminal-error').siblings('.popoverContent');

                // Toggle the popover on icon click
                $errorIcon.on('click', function(event) {
                    event
                        .stopPropagation(); // Prevent the document click event from firing immediately

                    // Close the previously open popover content
                    if ($openPopoverContent && !$openPopoverContent.is($popoverContent)) {
                        $openPopoverContent.hide();
                    }

                    // Toggle the current popover content
                    $popoverContent.toggle();
                    $openPopoverContent = $popoverContent;
                });
            });

            // Close the popover if clicked outside
            $(document).on('click', function(event) {
                if ($openPopoverContent && !$errorIcons.is(event.target) && !$openPopoverContent.is(event
                        .target) && $openPopoverContent
                    .has(event.target).length === 0) {
                    $openPopoverContent.hide();
                    $openPopoverContent = null;
                }
            });
        });

        //=================onchange-values=============================




        $(document).on('click', '.updateHistoryCarrier', function(event) {
            $("#old_order_id").siblings('h3').text(`Order Id# ${$(this).attr('data-old-id')}`);
            $("#old_order_id").val($(this).attr('data-old-id'));
            $("#new_order_id").val($(this).attr('data-new-id'));
        })

        $(document).on('click', '#btnUpdateHistory', function(event) {
            var old_order_id = $("#old_order_id").val();
            var new_order_id = $("#new_order_id").val();
            var history = $("#carrier_history").val();
            $.ajax({
                url: "{{ url('/update-carrier-history') }}",
                type: "GET",
                dataType: "JSON",
                data: {
                    old_order_id: old_order_id,
                    new_order_id: new_order_id,
                    history: history
                },
                success: function(res) {
                    $("#updateCarrierHistory").modal('hide');
                    $("#carrier_history").val('');
                    $(".modal-backdrop").eq(0).remove();
                    $(".modal-backdrop").eq(1).remove();
                },
                complete: function(data) {}
            });
            // console.log(old_order_id);
            // console.log(new_order_id);
        })

        $(document).on('click', '.viewHistoryCarrier', function(event) {
            var old_order_id = $(this).attr('data-old-id');
            var new_order_id = $(this).attr('data-new-id');
            $("#viewHistoryOfCarrier").html('');
            $.ajax({
                url: "{{ url('/view-carrier-history') }}",
                type: "GET",
                dataType: "html",
                data: {
                    old_order_id: old_order_id,
                    new_order_id: new_order_id
                },
                success: function(res) {
                    $("#viewHistoryOfCarrier").html('');
                    $("#viewHistoryOfCarrier").html(res);
                    $("#viewHistoryOfCarrier").animate({
                        scrollTop: 20000000000
                    }, "slow");
                },
                complete: function() {}
            });
            // console.log(old_order_id);
            // console.log(new_order_id);
        })

        function feedbackDetail(id, origin, pickup, destination, delivery, vehicle, date) {
            $("textarea[name='feedback']").val('');
            $("input[name='rate']").attr('checked', false);
            $('.checkRate').removeClass('selected');
            $("#id").text('');
            $("#pickup").text('');
            $("#delivery").text('');
            $("#vehicle_name").text('');
            $("#date").text('');

            $("#id").append(`${id}`);
            $("#pickup").append(`${origin}<br>${pickup}`);
            $("#delivery").append(`${destination}<br>${delivery}`);
            $("#vehicle_name").append(`${vehicle}`);
            $("#date").append(`${date}`);
        }

        function feedbackDetail2(id, origin, pickup, destination, delivery, vehicle, date) {
            $("#id2").text('');
            $("#pickup2").text('');
            $("#delivery2").text('');
            $("#vehicle_name2").text('');
            $("#date2").text('');
            $(".rate").html('');
            $(".feedback").html('');
            $(".clicked").html('');

            $("#id2").append(`${id}`);
            $("#pickup2").append(`${origin}<br>${pickup}`);
            $("#delivery2").append(`${destination}<br>${delivery}`);
            $("#vehicle_name2").append(`${vehicle}`);
            $("#date2").append(`${date}`);

            $.ajax({
                url: "{{ url('/feedback/show') }}",
                type: "GET",
                data: {
                    id: id
                },
                dataType: "JSON",
                success: function(res) {
                    if (res.feedback) {
                        if (res.feedback.rate == 1 || res.feedback.rate == 0) {
                            $(".rate").append(`
                                <i class="fa fa-frown-o m-0" style="font-size:2rem !important;" aria-hidden="true"></i>
                                <p style="font-size:2rem !important;">Negative</p>
                            `);
                        } else if (res.feedback.rate == 3 || res.feedback.rate == 2) {
                            $(".rate").append(`
                                <i class="fa fa-meh-o m-0" style="font-size:2rem !important;" aria-hidden="true"></i>
                                <p style="font-size:2rem !important;">Neutral</p>
                            `);

                        } else if (res.feedback.rate == 5 || res.feedback.rate == 4) {
                            $(".rate").append(`
                                <i class="fa fa-smile-o m-0" style="font-size:2rem !important;" aria-hidden="true"></i>
                                <p style="font-size:2rem !important;">Positive</p>
                            `);
                        }

                        $(".feedback").append(`
                            <span>${res.feedback.feedback}</span>
                        `);
                    } else {
                        $(".feedback").append(`No Feedback!`);
                    }
                    if (res.email) {
                        $(".clicked").append(`
                            <span class="badge badge-success">${res.email.link_click} click</span>
                        `);
                    } else {
                        $(".clicked").append(`
                            <span class="badge badge-danger">0 click</span>
                        `);
                    }
                }
            });
        }

        function ratingDetail(id) {
            $("#ord_id").val(id);

            $.ajax({
                url: "{{ url('/ratingdetail') }}",
                type: "GET",
                dataType: "html",
                data: {
                    order_id: id
                },
                success: function(res) {
                    $("#ratingPopupContent").html('');
                    $("#ratingPopupContent").html(res);
                }
            })
        }

        $('.checkRate').on('click', function() {
            $('.checkRate').removeClass('selected');
            $(this).addClass('selected');
            $("input[name='rate']").attr('checked', false);
            $(this).siblings('input').attr('checked', true);
        })

        $("#coupon_price").on("input", function(evt) {
            var self = $(this);
            self.val(self.val().replace(/[^0-9\.]/g, ''));
            if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) {
                evt.preventDefault();
            }
        });

        function sendLink() {
            var id = $("#id").text();
            $("#order_id").val(id);
        }

        $("#send").click(function(e) {
            e.preventDefault();
            var order_id = $("#order_id").val();
            var website = $("#website");
            var customer_email = $("#customer_email");
            website.parent('.form-group').children('.alert').remove();
            customer_email.parent('.form-group').children('.alert').remove();

            var btn = $(this);
            btn.attr('disabled', true);
            $.ajax({
                url: "{{ url('/send-website-link') }}",
                type: "GET",
                data: {
                    website: website.val(),
                    customer_email: customer_email.val(),
                    order_id: order_id
                },
                dataType: "json",
                success: function(res) {
                    if (res.status_code === 400) {
                        if (res.error.customer_email) {
                            customer_email.parent('.form-group').append(`
                                <div class="alert bg-danger text-light alert-dismissible fade show" role="alert">
                                  <strong>${res.error.customer_email[0]}</strong>
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                            `);
                        }
                        if (res.error.website) {
                            website.parent('.form-group').append(`
                                <div class="alert bg-danger text-light alert-dismissible fade show" role="alert">
                                  <strong>${res.error.website[0]}</strong>
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                            `);
                        }
                    } else {
                        $('#staticBackdrop1').modal('hide');
                        $(".modal-backdrop").eq(0).remove();
                        $(".modal-backdrop").eq(1).remove();
                        $(".modal-backdrop").eq(2).remove();
                    }
                    btn.removeAttr('disabled');
                }
            });
        });

        $("#submit2").click(function(e) {
            e.preventDefault();
            var id = $("#id").text();
            var feedback = $("textarea[name='feedback']");
            var rate = $("input[name='rate']");
            feedback.parent('.form-group').children('.alert').remove();
            rate.parent('.form-group').parent('.d-flex').parent('.form-group').children('.alert').remove();

            var btn = $(this);
            btn.attr('disabled', true);
            $.ajax({
                url: "{{ url('/feedback/create') }}",
                type: "GET",
                data: {
                    feedback: feedback.val(),
                    rate: $("input[name='rate']:checked").val(),
                    order_id: id
                },
                dataType: "json",
                success: function(res) {
                    if (res.status_code === 400) {
                        if (res.error.feedback) {
                            feedback.parent('.form-group').append(`
                                <div class="alert bg-danger text-light alert-dismissible fade show" role="alert">
                                  <strong>${res.error.feedback[0]}</strong>
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                            `);
                        }
                        if (res.error.rate) {
                            rate.parent('.form-group').parent('.d-flex').parent('.form-group').append(`
                                <div class="alert bg-danger text-light alert-dismissible fade show" role="alert">
                                  <strong>${res.error.rate[0]}</strong>
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                            `);
                        }
                    } else {
                        $('#feedback').modal('hide');
                        location.reload(true);
                    }
                    btn.removeAttr('disabled');
                }
            });
            // console.log(rate.val());
            // console.log(feedback.val());
            // console.log(id);
        })

        $("#submit").click(function(e) {
            e.preventDefault();
            var coupon_number = $("#coupon_number");
            var coupon_price = $("#coupon_price");
            var coupon_email = $("#coupon_email");
            coupon_number.parent('.form-group').children('.alert').remove();
            coupon_price.parent('.form-group').children('.alert').remove();
            coupon_email.parent('.form-group').children('.alert').remove();

            var btn = $(this);
            btn.attr('disabled', true);
            $.ajax({
                url: "{{ url('/coupons/create') }}",
                type: "GET",
                data: {
                    coupon_number: coupon_number.val(),
                    coupon_price: coupon_price.val(),
                    coupon_email: coupon_email.val()
                },
                dataType: "json",
                success: function(res) {
                    if (res.status_code === 400) {
                        if (res.error.coupon_number) {
                            coupon_number.parent('.form-group').append(`
                                <div class="alert bg-danger text-light alert-dismissible fade show" role="alert">
                                  <strong>${res.error.coupon_number[0]}</strong>
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                            `);
                        }
                        if (res.error.coupon_price) {
                            coupon_price.parent('.form-group').append(`
                                <div class="alert bg-danger text-light alert-dismissible fade show" role="alert">
                                  <strong>${res.error.coupon_price[0]}</strong>
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                            `);
                        }
                        if (res.error.coupon_email) {
                            coupon_email.parent('.form-group').append(`
                                <div class="alert bg-danger text-light alert-dismissible fade show" role="alert">
                                  <strong>${res.error.coupon_email[0]}</strong>
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                            `);
                        }
                    } else {
                        $('#staticBackdrop').modal('hide');
                        $(".modal-backdrop").eq(0).remove();
                        $(".modal-backdrop").eq(1).remove();
                        $(".modal-backdrop").eq(2).remove();
                    }
                    btn.removeAttr('disabled');
                }
            });
        });

        $(function() {
            var date = new Date();
            $('#date_range').daterangepicker({
                "showDropdowns": true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                },
                "alwaysShowCalendars": true,
                "startDate": new Date(date.getFullYear(), date.getMonth(), 1),
                "endDate": new Date(date.getFullYear(), date.getMonth() + 1, 0),
                "opens": "center",
                "drops": "auto"
            }, function(start, end, label) {
                console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format(
                    'YYYY-MM-DD') + ' (predefined range: ' + label + ')');
                // $('#date_range').val(start + ' - ' + end);
                $('#date_range').val('');
            });
            $('#date_range').val('');
            $('#date_range1').daterangepicker({
                "showDropdowns": true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                },
                "alwaysShowCalendars": true,
                "startDate": new Date(date.getFullYear(), date.getMonth(), 1),
                "endDate": new Date(date.getFullYear(), date.getMonth() + 1, 0),
                "opens": "center",
                "drops": "auto"
            }, function(start, end, label) {
                console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format(
                    'YYYY-MM-DD') + ' (predefined range: ' + label + ')');
                // $('#date_range1').val(start + ' - ' + end);
                $('#date_range1').val('');
            });
            $('#date_range1').val('');
        });

        $(".aucDate").on('click', function() {
            $("#showAuctionDateRange").toggle();
            $("#accounttitle").toggle();
            $('#date_range1').val('');
            $('#order_taker_id').children('option').removeAttr('selected');
            $('#order_taker_id').children('option').eq(0).attr('selected', true);
            $('#mistaker2').children('option').removeAttr('selected');
            $('#mistaker2').children('option').eq(0).attr('selected', true);
        })
        $("#acutionaccounttitle").on('change', function() {
            $("#acutionaccountname").val('');
            if ($(this).val() == 'Yes') {
                $("#accountname").show();
            } else {
                $("#accountname").hide();
            }
        })

        $("body").delegate(".BundleExpand", "click", function() {
            var className = $(this).closest('tr').attr('class');
            var number = parseFloat(className.match(/-*[0-9]+/));
            $(".BundleExpand").toggleClass('fa-chevron-down');
            if ($('.child' + number + ':visible').length)
                $('.child' + number).hide().removeClass("shown");
            else
                $('.child' + number).show().addClass("shown");
        });

        function showprice() {

            if ($('#relist').is(":checked")) {

                $('#row1').hide();
                $('#r1').show();
                $('#r2').show();
                $('#r3').show();
                $(".getcarrier").removeAttr("required");
                $("#r1").attr("required", true);
                $("#relist_id").attr("required", true);
                $("#expected_date").removeAttr("required");
                $("#current_carrier").removeAttr("required");


            } else {
                $('#row1').show();
                $('#r1').hide();
                $('#r2').hide();
                $('#r3').hide();
                $(".getcarrier").attr("required", true);
                $("#r1").removeAttr("required");
                $("#expected_date").attr("required", true);
                $("#current_carrier").attr("required", true);
                $("#relist_id").removeAttr("required");
            }
        }

        $(document).ready(function() {

            $('input').attr('autocomplete', 'onn');

        });
    </script>

    <script>
        $('#reportmodal').on('show.bs.modal', function(e) {

            //get data-id attribute of the clicked element
            var orderId = $(e.relatedTarget).data('book-id');

            //populate the textbox
            var encryptvuserid = btoa({{ Auth::user()->id }});
            var encryptvoderid = btoa(orderId);
            var linkv = "{{ url('/email_order/') }}" + '/' + encryptvoderid + '/' + encryptvuserid;
            $(e.currentTarget).find('input[name="orderid"]').val(orderId);
            $(e.currentTarget).find('input[name="link"]').val(linkv);
        });

        $('#trashmodal').on('show.bs.modal', function(e) {

            var orderId = $(e.relatedTarget).data('book-id');
            $(e.currentTarget).find('input[name="orderid"]').val(orderId);

        });

        $('#modalPaid').on('show.bs.modal', function(e) {
            var orderId = $(e.relatedTarget).data('book-id');
            $(e.currentTarget).find('input[name="orderid"]').val(orderId);

            var comments = $(e.relatedTarget).data('comments');
            //  alert(comments);
            $(e.currentTarget).find('textarea[name="pay_comments"]').val(comments);


            var paid_status = $(e.relatedTarget).data('paid_status');
            if (paid_status == 0) {
                $("#status0").prop("checked", true);
            } else if (paid_status == 1) {
                $("#status1").prop("checked", true);
            } else if (paid_status == 2) {
                $("#status2").prop("checked", true);
            }

        });


        $("#form").on('submit', (function(e) {
            e.preventDefault();
            $.ajax({
                url: "/send_order_link",
                type: "POST",
                data: new FormData(this),

                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {

                },
                success: function(data) {

                    let test = data.toString();

                    let test2 = $.trim(test);
                    let text = "SUCCESS";
                    if (test2 == text) {

                        $('#success').html(data);
                        $('#modaldemo4').modal('show');
                        $('#reportmodal').modal('hide');

                    } else {
                        $('#not_success').html(data);
                        $('#modaldemo5').modal('show');
                    }
                },
                error: function(e) {
                    $("#err").html(e).fadeIn();
                }
            });
        }));

        $(".close").click(function() {
            $(".modal-backdrop").remove();
        })
    </script>


    <script>
        function regain_report_modal() {

            $('#reportmodal').on('show.bs.modal', function(e) {

                //get data-id attribute of the clicked element
                var orderId = $(e.relatedTarget).data('book-id');

                //populate the textbox
                var encryptvuserid = btoa({{ Auth::user()->id }});
                var encryptvoderid = btoa(orderId);
                var linkv = "{{ url('/email_order/') }}" + '/' + encryptvoderid + '/' + encryptvuserid;
                $(e.currentTarget).find('input[name="orderid"]').val(orderId);
                $(e.currentTarget).find('input[name="link"]').val(linkv);
            });


            $('#trashmodal').on('show.bs.modal', function(e) {

                var orderId = $(e.relatedTarget).data('book-id');
                $(e.currentTarget).find('input[name="orderid"]').val(orderId);

            });

            $('#modalPaid').on('show.bs.modal', function(e) {
                var orderId = $(e.relatedTarget).data('book-id');
                $(e.currentTarget).find('input[name="orderid"]').val(orderId);

                var comments = $(e.relatedTarget).data('comments');
                //  alert(comments);
                $(e.currentTarget).find('textarea[name="pay_comments"]').val(comments);


                var paid_status = $(e.relatedTarget).data('paid_status');
                if (paid_status == 0) {
                    $("#status0").prop("checked", true);
                } else if (paid_status == 1) {
                    $("#status1").prop("checked", true);
                } else if (paid_status == 2) {
                    $("#status2").prop("checked", true);
                }

            });

        }



        $("body").delegate(".compare", "click", function() {

            var order_id = $(this).closest('tr').find('.order_id').val();
            var olcation = $(this).closest('tr').find('.location1').val();
            var dlcation = $(this).closest('tr').find('.location2').val();
            $('#order_id_carr').val(order_id);
            $('#olcation_carr').val(olcation);
            $('#dlcation_carr').val(dlcation);

            $.ajax({

                url: "/get_carrier_by_location",
                type: "GET",
                //dataType: "json",
                data: {
                    olcation: olcation,
                    dlcation: dlcation,
                    order_id: order_id
                },
                beforeSend: function() {
                    $('#table_data_carrier').html("");
                    $('#table_data_carrier').append(`<div class="lds-hourglass" id='ldss'></div>`);
                },

                success: function(data) {
                    //success
                    $('#table_data_carrier').html("");
                    $('#table_data_carrier').html(data);
                    if (data == "") {
                        $('#table_data_carrier').append(
                            `<div ><center> <h3 style="margin-top: 15px;">No Data Found</h3> </center></div>`
                        );
                    }
                },


            });

        });

        $("body").delegate('#carrierPagination2 a', 'click', function(event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];

            var order_id =  $('#order_id_carr').val();
            var olcation =  $('#olcation_carr').val();
            var dlcation =  $('#dlcation_carr').val();
            $.ajax({

                url: "/get_carrier_by_location?page=" + page,
                type: "GET",
                //dataType: "json",
                data: {
                    olcation: olcation,
                    dlcation: dlcation,
                    order_id: order_id
                },
                beforeSend: function() {
                    $('#table_data_carrier').html("");
                    $('#table_data_carrier').append(
                        `<div class="lds-hourglass" id='ldss'></div>`
                    );
                },

                success: function(data) {
                    //success
                    $('#table_data_carrier').html("");
                    $('#table_data_carrier').html(data);
                    if (data == "") {
                        $('#table_data_carrier').append(
                            `<div ><center> <h3 style="margin-top: 15px;">No Data Found</h3> </center></div>`
                        );
                    }

                },


            });
        });

        $("body").delegate(".storageModal", "click", function() {

            var olcation = $(this).closest('tr').find('.location1').val();
            var dlcation = $(this).closest('tr').find('.location2').val();
            $.ajax({

                url: "/get_storage_by_location",
                type: "GET",
                //dataType: "json",
                data: {
                    olcation: olcation,
                    dlcation: dlcation
                },
                beforeSend: function() {
                    $('#table_data_storage').html("");
                    $('#table_data_storage').append(`<div class="lds-hourglass" id='ldss'></div>`);
                },

                success: function(data) {
                    //success
                    $('#table_data_storage').html("");
                    $('#table_data_storage').html(data);
                    if (data == "") {
                        $('#table_data_storage').append(
                            `<div ><center> <h3 style="margin-top: 15px;">No Data Found</h3> </center></div>`
                        );
                    }

                    $(document).on('click', '#carrierPagination a', function(event) {
                        event.preventDefault();
                        var page = $(this).attr('href').split('page=')[1];

                        var olcation = $('#origin').val();
                        var dlcation = $('#destination').val();
                        $.ajax({

                            url: "/get_storage_by_location?page=" + page,
                            type: "GET",
                            //dataType: "json",
                            data: {
                                olcation: olcation,
                                dlcation: dlcation
                            },
                            beforeSend: function() {
                                $('#table_data_storage').html("");
                                $('#table_data_storage').append(
                                    `<div class="lds-hourglass" id='ldss'></div>`
                                );
                            },

                            success: function(data) {
                                //success
                                $('#table_data_storage').html("");
                                $('#table_data_storage').html(data);
                                if (data == "") {
                                    $('#table_data_storage').append(
                                        `<div ><center> <h3 style="margin-top: 15px;">No Data Found</h3> </center></div>`
                                    );
                                }

                            },


                        });
                    });

                },


            });

        });


        $("body").delegate(".find_carrier", "click", function() {
            var order_id = $(this).closest('tr').find('.order_id').val();
            var originstate = $(this).closest('tr').find('.origincity').val();
            var destinationstate = $(this).closest('tr').find('.destinationcity').val();
            $('#find_o_id').html("Order-Id: " + order_id);

            $.ajax({

                url: "/find_carrier",
                type: "GET",
                //dataType: "json",
                data: {
                    originstate: originstate,
                    destinationstate: destinationstate,
                    order_id: order_id
                },

                beforeSend: function() {
                    $('#table_find_data_carrier').html("");
                    $('#table_find_data_carrier').append(`<div class="lds-hourglass" id='ldss'></div>`);
                },

                success: function(data) {
                    //success
                    $('#table_find_data_carrier').html("");
                    $('#table_find_data_carrier').html(data);

                },


            });

        });


        function find_select(select_id, order_id) {

            $.ajax({

                url: "/assign_find_carrier",
                type: "GET",
                //dataType: "json",
                data: {
                    select_id: select_id,
                    order_id: order_id
                },

                success: function(data) {
                    $('#find_carrier_modal').modal('hide');
                    not1();

                }

            });


        }


        $('#pc_originzsc').keyup(function() {
            var o_zip1 = $(this);
            if (o_zip1.val().length >= 4) {

                var nav = $(this).parents('.form-group').siblings('.nav');
                if (o_zip1.val() == '') {
                    nav.children().remove();
                    nav.hide();
                } else {
                    $.ajax({
                        url: "{{ url('/get_zip') }}",
                        type: "GET",
                        dataType: "json",
                        data: {
                            d_zip1: o_zip1.val()
                        },
                        success: function(res) {
                            nav.show();
                            nav.children().remove();
                            $.each(res, function() {
                                nav.append(`
                                <li class="nav-item selectAdd">
                                    <a class="nav-link" href="javascript:void(0)">${this}</a>
                                </li>
                            `);
                            });
                            $('.selectAdd').click(function() {
                                $(this).parent('.nav').children().remove();
                                $(this).parent('.nav').hide();
                                $('#pc_originzsc').val($(this).children('a').text());

                            })

                        }
                    });
                }
            }
            // console.log(d_zip1);
        })


        function updateProfileCard(order_id,ophone) {

            $.ajax({

                url: "{{ route('getProfileCard') }}",
                type: "GET",
                data: {
                    id: order_id,
                    ophone: ophone,
                },
                success: function (response) {
                    let data = response.data;
                    let write = response.write; // 0 = placeholder, 1 = set value

                    if (write == 0) {
                        $('#ProfileCard').modal('show');
                    }

                    function setField(selector, value) {
                        if (write == 1) {
                            $(selector).val(value || '');
                        } else {
                            $(selector).attr('placeholder', value || '');
                        }
                    }

                    $('#pc_order_id').attr('placeholder', order_id);

                    // Basic Profile Fields
                    setField('#pc_first_name', data.first_name);
                    setField('#pc_last_name', data.last_name);
                    setField('#pc_oemail', data.oemail);
                    setField('#pc_ophone', data.ophone);
                    setField('#pc_ophone2', data.ophone2);
                    setField('#pc_address', data.oaddress);
                    setField('#pc_originzsc', data.originzsc);
                    setField('#pc_payment_method', data.payment_method);
                    setField('#pc_business_name', data.business_name);
                    setField('#pc_nature_of_customer', data.nature_of_customer);
                    setField('#pc_customer_review', data.customer_review);
                    setField('#pc_average_margin', data.average_margin);
                    setField('#pc_customer_rating', data.customer_rating);
                    setField('#pc_social_profile_name', data.social_profile_name);

                    // Auction 1
                    $('#pc_1oterminal').val(data.auction_1_terminal || '').trigger('change');
                    $('#pc_2oterminal').val(data.auction_2_terminal || '').trigger('change');
                    $('#pc_3oterminal').val(data.auction_3_terminal || '').trigger('change');
                    setTimeout(function() {
                        setField('#pc_1oacution_name', data.auction_1_title);
                        setField('#pc_1oacutionphoNo', data.auction_1_phone);
                        setField('#pc_1oacutionaccounttitle', data.auction_1_account);
                        setField('#pc_1oacutionaccountname', data.auction_1_account_name);
                        setField('#pc_1obuyer_no', data.auction_1_buyer_no);
                        setField('#pc_1gate_pass_pin', data.auction_1_gate_pass_pin);
                        setField('#pc_1obuyer_lot_no', data.auction_1_lot_no);

                        // Auction 2

                        setField('#pc_2oacution_name', data.auction_2_title);
                        setField('#pc_2oacutionphoNo', data.auction_2_phone);
                        setField('#pc_2oacutionaccounttitle', data.auction_2_account);
                        setField('#pc_2oacutionaccountname', data.auction_2_account_name);
                        setField('#pc_2obuyer_no', data.auction_2_buyer_no);
                        setField('#pc_2gate_pass_pin', data.auction_2_gate_pass_pin);
                        setField('#pc_2obuyer_lot_no', data.auction_2_lot_no);

                        // Auction 3

                        setField('#pc_3oacution_name', data.auction_3_title);
                        setField('#pc_3oacutionphoNo', data.auction_3_phone);
                        setField('#pc_3oacutionaccounttitle', data.auction_3_account);
                        setField('#pc_3oacutionaccountname', data.auction_3_account_name);
                        setField('#pc_3obuyer_no', data.auction_3_buyer_no);
                        setField('#pc_3gate_pass_pin', data.auction_3_gate_pass_pin);
                        setField('#pc_3obuyer_lot_no', data.auction_3_lot_no);
                    },500);


                    // Quote Info
                    if (write == 1) {
                        $('#pc_recent_follow_up').val(data.recent_follow_up || '');
                        $('#pc_first_quote_date').val(data.first_quote_date || '');
                        $('#pc_first_quote_status').val(data.first_quote_status || '');
                        $('#pc_first_quote_agent').val(data.first_quote_agent || '');
                        $('#pc_recent_quote_date').val(data.recent_quote_date || '');
                        $('#pc_recent_quote_status').val(data.recent_quote_status || '');
                        $('#pc_recent_quote_agent').val(data.recent_quote_agent || '');
                    } else {
                        $('#pc_recent_follow_up').attr('placeholder', data.recent_follow_up || '');
                        $('#pc_first_quote_date').attr('placeholder', data.first_quote_date || '');
                        $('#pc_first_quote_status').attr('placeholder', data.first_quote_status || '');
                        $('#pc_first_quote_agent').attr('placeholder', data.first_quote_agent || '');
                        $('#pc_recent_quote_date').attr('placeholder', data.recent_quote_date || '');
                        $('#pc_recent_quote_status').attr('placeholder', data.recent_quote_status || '');
                        $('#pc_recent_quote_agent').attr('placeholder', data.recent_quote_agent || '');
                    }

                    // Set radio button for customer type
                    $('input[name="pc_customer_type"][value="' + data.customer_type + '"]').prop('checked', true).trigger('change');

                    // History button onclick
                    let history = `history('${order_id}','${data.ophone}')`;
                    $('#pc_history').attr('onclick', history);
                }


            });

        }

        @php
            $terminalLabels = [
                1 => ['name' => $label[105]->name, 'display' => $label[105]->display],
                2 => ['name' => $label[106]->name, 'display' => $label[106]->display],
                3 => ['name' => $label[107]->name, 'display' => $label[107]->display],
                4 => ['name' => $label[108]->name, 'display' => $label[108]->display],
                5 => ['name' => $label[109]->name, 'display' => $label[109]->display],
                6 => ['name' => $label[113]->name, 'display' => $label[113]->display],
                7 => ['name' => $label[111]->name, 'display' => $label[111]->display],
                8 => ['name' => $label[112]->name, 'display' => $label[112]->display],
                10 => ['name' => $label[110]->name, 'display' => $label[110]->display],
            ];
        @endphp

        const terminalLabels = @json($terminalLabels);


        for (let loop = 1; loop <= 3; loop++) {

            $("#pc_"+loop+"oterminal").change(function() {
                $(".pc_"+loop+"buyer_number").hide();
                var oterminal = $("#pc_"+loop+"oterminal").val();
                if (oterminal == 1) {
                    $("#pc_"+loop+"buyerPanel").hide();
                    $(".pc_"+loop+"buyer_number").hide();
                } else {
                    $("#pc_"+loop+"buyerPanel").show();
                }
                if (oterminal == 2 || oterminal == 3 || oterminal == 4 || oterminal == 10) {
                    $("#pc_"+loop+"oacution_name").val('');
                    $("#pc_"+loop+"oacutionphoNo").val('');
                    $("#pc_"+loop+"oaddress").val('');
                    $(".pc_"+loop+"buyer_number").hide();
                }
            });


            $("#pc_"+loop+"oterminal").change(function() {
                var id = $(this).val();
                $(".pc_"+loop+"stock_number").html('');
                if (id == 1) {
                    $(".pc_"+loop+"oauc").html('');
                } else if (id == 3 || id == 2 || id == 8) {
                    $(".pc_"+loop+"oauc").html('');
                    $(".pc_"+loop+"oauc").html(`
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label id="pc_${loop}oacution" class="label font-boldd tx-black"></label>
                            <input class="form-control" autocomplete="off"  type="text" name="pc_${loop}oacution" id="pc_${loop}oacution_name" value="">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label id="pc_${loop}oacutionpho" class="label parsley-error font-boldd tx-black"></label>
                            <input class="form-control ophone" autocomplete="off" type="text"  name="pc_${loop}oacutionpho" id="pc_${loop}oacutionphoNo" value="">
                        </div>
                    </div>


                    <div class="col-sm-4">
                        <div class="form-group">
                                 <div class="Terminal-error">
                            <label for="oacutionaccounttitle" class="label parsley-error font-boldd tx-black">Has Auction Account?</label>
                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[11 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[11 - 1]->display }}</div>
                                            </div>
                            <select class="form-control " autocomplete="off" name="pc_${loop}oacutionaccounttitle" id="pc_${loop}oacutionaccounttitle">
                                <option value="" selected disabled>Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4" style="display:none;" id="aucAccName">
                        <div class="form-group">
                            <label for="pc_${loop}oacutionaccountname" class="label parsley-error font-boldd tx-black">Auction Account Name </label>
                            <input class="form-control " autocomplete="off" type="text"  name="pc_${loop}oacutionaccountname" id="pc_${loop}oacutionaccountname" value="">
                        </div>
                    </div>
                     <div class="col-sm-4">
                        <div class="form-group">
                                 <div class="Terminal-error">
                            <label class="form-label font-boldd">Buyer #</label>
                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[70 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[70 - 1]->display }}</div>
                                            </div>
                            <input type="text" name="pc_${loop}obuyer_no" id="pc_${loop}obuyer_no" class="form-control " autocomplete="off"
                            placeholder="Buyer">
                        </div>
                     </div>
                     <div class="col-sm-4">
                        <div class="form-group">
                                 <div class="Terminal-error">
                            <label class="form-label font-boldd">Gate Pass Pin</label>
                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[70 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[70 - 1]->display }}</div>
                                            </div>
                            <input type="text" name="pc_${loop}gate_pass_pin" id="pc_${loop}gate_pass_pin" class="form-control " autocomplete="off"
                            placeholder="Gate Pass Pin">
                        </div>
                     </div>
                     <div class="col-sm-4">
                        <div class="form-group">
                                 <div class="Terminal-error">
                            <label class="form-label font-boldd">Lot #</label>
                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[70 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[70 - 1]->display }}</div>
                                            </div>
                            <input type="text" name="pc_${loop}obuyer_lot_no" id="pc_${loop}obuyer_lot_no" class="form-control " autocomplete="off"
                            placeholder="Lot">
                        </div>
                     </div>
                `);

                } else if (id == 4) {
                    $(".pc_"+loop+"oauc").html('');
                    $(".pc_"+loop+"oauc").html(`
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label id="pc_${loop}oacution" class="label font-boldd tx-black"></label>
                            <input class="form-control" autocomplete="off"  type="text" name="pc_${loop}oacution" id="pc_${loop}oacution_name" value="">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label id="pc_${loop}oacutionpho" class="label parsley-error font-boldd tx-black"></label>
                            <input class="form-control ophone" autocomplete="off" type="text"  name="pc_${loop}oacutionpho" id="pc_${loop}oacutionphoNo" value="">
                        </div>
                    </div>


                    <div class="col-sm-4">
                        <div class="form-group">
                                 <div class="Terminal-error">
                            <label for="pc_${loop}oacutionaccounttitle" class="label parsley-error font-boldd tx-black">Has Auction Account?</label>
                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[11 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[11 - 1]->display }}</div>
                                            </div>
                            <select class="form-control" autocomplete="off" name="pc_${loop}oacutionaccounttitle" id="pc_${loop}oacutionaccounttitle">
                                <option value="" selected disabled>Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4" style="display:none;" id="pc_${loop}aucAccName">
                        <div class="form-group">
                            <label for="pc_${loop}oacutionaccountname" class="label parsley-error font-boldd tx-black">Auction Account Name </label>
                            <input class="form-control" autocomplete="off" type="text"  name="pc_${loop}oacutionaccountname" id="pc_${loop}oacutionaccountname" value="">
                        </div>
                    </div>
                     <div class="col-sm-4">
                        <div class="form-group">
                                 <div class="Terminal-error">
                            <label class="form-label font-boldd">Buyer #</label>
                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[70 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[70 - 1]->display }}</div>
                                            </div>
                            <input type="text" name="pc_${loop}obuyer_no" id="pc_${loop}obuyer_no" class="form-control " autocomplete="off"
                            placeholder="Buyer">
                        </div>
                     </div>
                     <div class="col-sm-4">
                        <div class="form-group">
                                 <div class="Terminal-error">
                            <label class="form-label font-boldd">Stock #</label>
                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[70 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[70 - 1]->display }}</div>
                                            </div>
                            <input type="text" name="pc_${loop}obuyer_stock_no" id="pc_${loop}obuyer_stock_no" class="form-control " autocomplete="off"
                            placeholder="Lot">
                        </div>
                     </div>
                `);

                } else {
                    $(".pc_"+loop+"oauc").html('');
                    $(".pc_"+loop+"oauc").html(`
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label id="pc_${loop}oacution" class="label font-boldd tx-black"></label>
                            <input class="form-control" autocomplete="off"  type="text" name="pc_${loop}oacution" id="pc_${loop}oacution_name" value="">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label id="pc_${loop}oacutionpho" class="label parsley-error font-boldd tx-black"></label>
                            <input class="form-control  ophone" autocomplete="off" type="text"  name="pc_${loop}oacutionpho" id="pc_${loop}oacutionphoNo" value="">
                        </div>
                    </div>
                     <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label font-boldd">Buyer/Lot/Stock Number</label>
                            <input type="text" name="pc_${loop}obuyer_no" id="pc_${loop}obuyer_no" class="form-control" autocomplete="off"
                            placeholder="Buyer/Lot/Stock Number">
                        </div>
                     </div>
                    `);
                    if (id == 5) {
                        $("#pc_"+loop+"oacution").html('Shop Name');
                        $("#pc_"+loop+"oacutionpho").html('Shop Phone');
                    } else if (id == 7 || id == 6) {
                        $(".pc_"+loop+"oauc").html(``);
                        $(".pc_"+loop+"buyer_number").hide();
                        $(".pc_"+loop+"stock_number").html(`
                       <div class="">
                            <div class="form-group">
                            <label class="form-label font-boldd">Buyer/Lot/Stock Number</label>
                            <input type="text" name="pc_${loop}obuyer_no" id="pc_${loop}obuyer_no" class="form-control " autocomplete="off"
                            placeholder="Buyer/Lot/Stock Number">
                       </div>
                     </div>`);
                    } else if (id == 10) {
                        $("#pc_"+loop+"oacution").html('Dealership / Contact Person');
                        $("#pc_"+loop+"oacutionpho").html('Dealership Phone');
                        $(".pc_"+loop+"buyer_number").hide();

                    } else {
                        $("#pc_"+loop+"oacution").html('Auction Name');
                        $("#pc_"+loop+"oacutionpho").html('Auction Phone');
                        $(".pc_"+loop+"buyer_number").hide();
                    }
                }


                var oterminal = $("#pc_"+loop+"oterminal").val();
                if (oterminal == 2 || oterminal == 3 || oterminal == 4 || oterminal == 8) {
                    if (oterminal == 2) {
                        $("#pc_"+loop+"oacution_name").val("COPART Auto Auction");
                    } else if (oterminal == 3) {
                        $("#pc_"+loop+"oacution_name").val("Manhein Auto Auction");

                    } else if (oterminal == 4) {
                        $("#pc_"+loop+"oacution_name").val("IAAI Auto Auction");
                    } else if (oterminal == 8) {
                        $("#pc_"+loop+"oacution_name").val("Auction (Heavy)");
                    }
                }
                $(".ophone").keypress(function(e) {
                    if ($(this).val() == '') {
                        $(this).mask("(999) 999-9999");
                    }
                    var x = e.which || e.keycode;
                    if ((x >= 48 && x <= 57) || (x >= 35 && x <= 40)) {
                        return true;
                    } else {
                        return false;
                    }
                })


            });

            $('#pc_' + loop + 'oterminal').on('change', function() {
                $(".pc_"+loop+"oterminal-none").css("display", "block");

                const oterminalselectedOption = $(this).val();
                const selectedOptionText = $('#pc_' + loop + 'oterminal option:selected').text();

                // Update the label with the selected option text
                $('#pc_' + loop + 'selectedOptionLabel').text(selectedOptionText);

                // If label exists in terminalLabels, update fields
                if (terminalLabels[oterminalselectedOption]) {
                    $('#pc_' + loop + 'change_oterminal_name').html(terminalLabels[oterminalselectedOption].name);
                    $('#pc_' + loop + 'change_oterminal_display').html(terminalLabels[oterminalselectedOption].display);
                }
            });
        }

        function call_history(id) {

            $.ajax({

                url: "show_call_history",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {
                    if (data.length > 0) {
                        $('#calhistory').html('');
                        $('#calhistory').html(data);
                        setTimeout(function() {
                            $("#calhistory").animate({
                                scrollTop: 20000
                            }, "slow");

                        }, 200);
                    } else {
                        $('#calhistory').html('NO HISTORY FOUND');
                    }

                }

            });

        }

        function qa_show_history(id) {
            $.ajax({
                url: "{{ url('/qa_show_history') }}",
                type: "GET",
                data: {
                    id: id
                },
                dataType: "html",
                success: function(res) {
                    $("#calhistory9").html(res);
                }
            })
        }

        function qa_update_history(id, pstatus) {
            $.ajax({
                url: "{{ url('/qa_update_history') }}",
                type: "GET",
                data: {
                    id: id,
                    pstatus: pstatus
                },
                dataType: "html",
                success: function(res) {
                    $("#tab8").html(res);
                }
            })
        }


        $("body").delegate(".updatingToSchedule", "click", function() {
            $('#schedule_delivery').modal('show');
            $("#sd_order_id").val($(this).attr('data-order-id'));
        })

        $("body").delegate("#pc_ophone", "focus", function() {
            $("#pc_ophone").mask("(999) 999-9999");
            $("#pc_ophone")[0].setSelectionRange(0, 0);
        });

        $("body").delegate("#pc_ophone2", "focus", function() {
            $("#pc_ophone2").mask("(999) 999-9999");
            $("#pc_ophone2")[0].setSelectionRange(0, 0);
        });

        $("body").delegate(".updatee", "click", function() {

            $(this).prop("disabled", true);
            var order_id = $(this).closest('tr').find('.order_id').val();
            var pstatus = $(this).closest('tr').find('.pstatus').val();
            var pickup_date = $(this).closest('tr').find('.pickup_date').val();
            var deliver_date = $(this).closest('tr').find('.deliver_date').val();
            var client_phone = $(this).closest('tr').find('.client_phone').val();
            var profile_card_update = $(this).closest('tr').find('.profile_card_update').val();
            $("#order_id1").attr("value", order_id);
            $("#order_id2").attr("value", order_id);
            $('#ask_low').html('');
            var id = order_id;

            call_history(id);
            if(pstatus == 8 || pstatus == 18 || pstatus == 7) {
                $('#ProfileCard').show();
                updateProfileCard(id,client_phone);
            }else{
                $('#ProfileCard').hide();
            }
            pop_update(pstatus, pickup_date, deliver_date, order_id);
            qa_show_history(id);
            qa_update_history(id, pstatus);
            setTimeout(() => {
                $('#largemodal').modal('show');
                $(this).prop("disabled", false);
            }, 2000);
            $("#pstatus").children('option').removeAttr('selected');
            $("#pstatus").children('option').eq(0).attr('selected', true);
            $("#mistakers").html('');
            $("#last_history").html('');

            $.ajax({
                url: "{{ url('/qa_count') }}",
                type: "GET",
                data: {
                    id: id
                },
                dataType: "JSON",
                success: function(res) {
                    $("#qa_count").html(res);
                }
            })

            $.ajax({
                url: "{{ url('/approach_count') }}",
                type: "GET",
                data: {
                    id: id
                },
                dataType: "JSON",
                success: function(res) {
                    $("#approach_count").html(res.data);
                    if (res.statusCheck == 11 || res.statusCheck == 12 || res.statusCheck == 13) {
                        $("#status_checkk").hide();

                    }
                    console.log('statusCheck', res.statusCheck);
                }
            })

            $("#nature_orderID").val($('#order_id1').val());
            var single = 'single';
            $.ajax({


                url: "{{ url('/get/CustomerNature') }}",
                type: "GET",
                // dataType: "json",
                data: {
                    order_id: order_id,
                    single: single,
                },
                success: function(data) {
                    if (data.length == 0) {
                        $("#getCustomerNature").hide();
                    } else {
                        $("#getCustomerNature").show();
                        $("#nature_description").val(data.description);
                        $("#nature_user").val(data.user['name']);

                        // Status
                        var status = data.status;

                        if (status !== null) {
                            // Map 'data.status' to the corresponding option value
                            var optionValue = mapStatusToOptionValue(status);

                            // Set the selected attribute based on the mapped option value
                            $('#nature_status').val(optionValue);
                        } else {
                            // If data.status is null, select the "Select" option
                            $('#nature_status').val('');
                        }
                    }
                }
            });

            // Function to map status to option value
            function mapStatusToOptionValue(status) {
                switch (status) {
                    case 'Verified':
                        return 'Verified';
                    case 'Unverified':
                        return 'Unverified';
                    default:
                        return ''; // Default value or handle other cases
                }
            }

        });



        function pop_update(titlee, pickup, deliver, order_id) {

            var id = order_id;

            if (titlee == 9 || titlee == 10 || titlee == 34) {

                $("#current_carrier").empty();
                var order_id = document.getElementById('order_id1').value;
                var options = "<option selected value=''>Select Carrier</option>";
                $.ajax({

                    type: "GET",
                    url: "/get_carrier",
                    data: {
                        'order_id': order_id
                    },
                    dataType: "json",

                    success: function(data) {
                        $.each(data, function(i, item) {

                            if (item.id) {
                                options = options + `<option value='` + item.id + `'>` + item
                                    .companyname + `</option>`;

                            }
                        });
                        //$("#current_carrier").remove();
                        $("#current_carrier").append(options);
                    },
                    error: function(e) {
                        alert("error");
                    }

                });
            }


            // if (titlee == 10) {

            //     $(".pickupdatediv").html('');

            //     $(".pickupdatediv").append(`<div class="form-group"><label class="form-label">PICKUP DATE</label>
        //                 <input type="date" value="${pickup}" required name="pickup_date"
        //                 id='pickup_date' class="form-control"><input type="checkbox" name="approvalpickup" value="1"/>MARK AS APPROVED</div>`);

            // }



            // if (titlee == 11) {

            //     $(".pickupdatediv2").html('');
            //     $(".deliverdate").html('');


            //     $(".pickupdatediv2").append(`<div class="form-group"><label class="form-label">PICKUP DATE</label>
        //                 <input readonly type="text" value="${pickup}"  name="pickup_date1"
        //                 id='pickup_date1' class="form-control"></div>`);

            //     $(".deliverdate").append(`<div class="form-group"><label class="form-label">Expected/DELIVER DATE</label>
        //                 <input required  type="date" value="${deliver}"  name="deliver_date"
        //                 id='deliver_date'class="form-control"></br>
        //                 <input type="checkbox" name="approvaldeliver" value="1"/> &nbsp; Mark AS APPROVED (DELIVER)</div>`);


            // }

        }

        $(document).on("change", "#current_carrier", function() {
            var id = $(this).val();
            $.ajax({
                url: "{{ url('/getonecarrier') }}",
                type: "GET",
                data: {
                    id: id
                },
                dataType: "JSON",
                success: function(data) {
                    $("#auc_driver_no").val('');
                    $("#auc_company_name").val('');
                    $("#auc_company_name").val(data.companyname);
                    if (data.driverphoneno) {
                        $("#auc_driver_no").val(data.driverphoneno);
                    } else {
                        if (data.companyphoneno) {
                            $("#auc_driver_no").val(data.companyphoneno);
                        }
                    }
                }
            });
        })

        $("body").delegate("#keywords", "click", function() {
            setTimeout(function() {
                $('input[name="keywords"]').focus()
            }, 100);
        });


        $(document.body).delegate("#keywords", "keyup", function(e) {

            if (e.which == 13) {
                return_data();
            }

        });


        $("body").delegate("#search_by", "change", function() {

            $('#keywords').removeAttr('readonly');
            var search_by = $('#search_by').val();
            if (search_by == "ophone" || search_by == "driverphoneno") {

                $('#keywords').val('');
                $('#keywords').attr('type', 'text');
                $("#keywords").mask("(999) 999-9999");

                setTimeout(function() {
                    $('input[name="keywords"]').focus()
                }, 100);

            } else if (search_by == "created_at" || search_by == "updated_at") {

                $('#keywords').val('');
                $('#keywords').attr('type', 'date');

            } else if (search_by == "dauction") {
                $('#keywords').val('');
                $('#keywords').attr('type', 'text');
                $('#keywords').val('Port');
                $('#keywords').attr('readonly', true);
            } else {
                $('#keywords').attr('type', 'text');
                $("#keywords").unmask();
                setTimeout(function() {
                    $('input[name="keywords"]').focus()
                }, 100);
                $('#keywords').val('');

            }
        });


        $("body").delegate("#pstatus", "change", function() {
            var p_status = $('#pstatus').val();
            if (p_status == 3) {

                $('#ask_low').html(`
                    <div class="form-group">
                        <label class="form-label">Asking Low Price</label>
                        <input required type="number" min="0" step="0.01" name="asking_low"
                                  id='asking_low' class="form-control">
                    </div>`)
            }

            // if(p_status == 19){

            //     $('.select_cancel').prop("disabled", true);
            // }else{

            //     $('.select_cancel').prop("disabled", false);
            // }



        });

        function return_data() {

            var titlee = $('#titlee').val();

            var data = $('#search_form').serialize();
            data = data + "&titlee=" + titlee;

            $('#table_data').html('');
            $('#table_data').append(`<div class="lds-hourglass" id='ldss'></div>`);
            $.ajax({

                url: "/fetch_data",
                type: "GET",
                data: data,
                success: function(data) {
                    $('#table_data').html('');
                    $('#table_data').html(data);
                },
                complete: function(data) {
                    $('#ldss').hide();
                    //regain();
                }

            });
        }
        $("body").delegate(".count_user", "click", function() {

            var order_id = $(this).closest('tr').find('.order_id').val();
            var pstatus = $(this).closest('tr').find('.pstatus').val();
            var client_email = $(this).closest('tr').find('.client_email').val();
            var client_name = $(this).closest('tr').find('.client_name').val();
            var client_phone = $(this).closest('tr').find('.client_phone').val();

            //alert(order_id + " " + pstatus + " " + client_email);

            var data = {
                order_id: order_id,
                pstatus: pstatus,
                client_email: client_email,
                client_name: client_name
            };
            $.ajax({
                type: "GET",
                url: '/count_user',
                dataType: "json",
                data: data,
                beforeSend: function() {

                },
                complete: function() {

                },
                success: function(response) {
                    if (response) {
                        window.location.href = "rcmobile://call?number=" + client_phone;
                    }

                }
            });


        });
    </script>


    <script src="{{ url('assets/js/jquery-cookie.js') }}"></script>
    <script>
        $(document).ready(function() {


            $(document).on('click', '.pagination a', function(event) {

                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                fetch_data3(page);
                $.cookie("page", page, {
                    expires: 1
                });
            });


            function fetch_data3(page) {


                var titlee = $('#titlee').val();

                var data = $('#search_form').serialize();
                data = data + "&titlee=" + titlee + "&page=" + page;


                $('#table_data').html('');
                $('#table_data').append(`<div class="lds-hourglass" id='ldss'></div>`);

                $.ajax({

                    url: "/fetch_data",
                    data: data,
                    type: "GET",
                    success: function(data) {
                        $('#table_data').html('');
                        $('#table_data').html(data);

                    },
                    complete: function(data) {
                        $('#ldss').hide();
                        //regain();
                    }

                })

            }
            // let cookie = $.cookie("page");
            // if(cookie)
            // {
            //     fetch_data3(cookie);
            //     $.removeCookie("page");
            // }

        });

        function getMsgCall(id, status) {
            $.ajax({
                url: "{{ url('/show-message-call') }}",
                type: "GET",
                data: {
                    order_id: id,
                    status: status
                },
                success: function(res) {
                    $('.viewMessageCall').html('');
                    $('.viewMessageCall').html(res);
                }
            })
        }

        function getIdPhone(id, status, name, phone, origin, destination, vehicle) {
            $("#authorization-orderId").val(id);
            $("#authorization-status").val(status);
            $("#authorization-cname").val(name);
            $("#authorization-cphone").val(phone);
            $("#authorization-origin").val(origin);
            $("#authorization-destination").val(destination);
            $("#authorization-vehicle").val(vehicle);

            // console.log('id', 'status', 'name', 'phone', id, status, name, phone, origin, destination);
        }

        function msgCall(id, status, name, phone, msgVal) {
            $("#orderId22").val(id);
            $("#status").val(status);
            $("#cname").val(name);
            $("#cphone").val(phone);
            $("#messageReply").val(msgVal);
            var messageCallCenterLongTitle = '';
            var tab4 = '';
            var tab5 = '';
            if (status == 0) {
                messageCallCenterLongTitle = 'Message Center';
                tab4 = 'Update Message Center';
                tab5 = 'View Message Center';
            }
            if (status == 1) {
                messageCallCenterLongTitle = 'Call Log Center';
                tab4 = 'Update Call Log Center';
                tab5 = 'View Call Log Center';
            }
            $("#messageCallCenterLongTitle").text(messageCallCenterLongTitle);
            $("a[href='#tab4']").text(tab4);
            $("a[href='#tab5']").text(tab5);

            getMsgCall(id, status);

        }

        function modalClick() {
            $("#description").val('');
            $("#date_time").val('');
            // $("#orderId22").val('');
            // $("#status").val('');
            // $("#cname").val('');
            // $("#cphone").val('');
        }

        $("#udpateMessageCall").click(function() {
            var order_id = $("#orderId22").val();
            var status = $("#status").val();
            var cname = $("#cname").val();
            var cphone = $("#cphone").val();
            var date_time = $("#date_time");
            var description = $("#description");
            var messageReply = $("#messageReply").val();
            date_time.parents('.form-group').children('.alert').remove();
            description.parents('.form-group').children('.alert').remove();
            $.ajax({
                url: "{{ url('/add-message-call') }}",
                type: "POST",
                dataType: "json",
                data: {
                    order_id: order_id,
                    status: status,
                    cname: cname,
                    cphone: cphone,
                    date_time: date_time.val(),
                    description: description.val(),
                    messageReply: messageReply
                },
                success: function(res) {
                    if (res.status_code === 200) {
                        getMsgCall(order_id, status);
                        $('#modal').modal('hide');
                        modalClick();
                    }
                    if (res.error.description) {
                        description.parents('.form-group').append(`
                            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                ${res.error.description[0]}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>`);
                    }
                    if (res.error.date_time) {
                        date_time.parents('.form-group').append(`
                            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                ${res.error.date_time[0]}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>`);
                    }
                }
            });
        });

        $(".changeMsg").click(function() {
            $("#msgReply").children('.form-group').children('.form-label').text('');
            $("#msgReply").children('.form-group').children('.form-label').text($(this).text());
            $('#messageReply').val($(this).text());
            $('.changeMsg').removeClass('active');

            if ($(this).text() == 'Your Message') {
                $("#msgReply").children('.form-group').children('#description').attr('placeholder',
                    'Write Your Message');
                $(this).addClass('active');
            }
            if ($(this).text() == 'Client Reply') {
                $("#msgReply").children('.form-group').children('#description').attr('placeholder',
                    'Write Client Message');
                $(this).addClass('active');
            }
        })
    </script>
    <script>
        $("#showingDispatchers").on('click', function() {
            $("#showDispatchers").toggle();
        });
        // $("#auction_storage").on('change',function () {
        //     if($(this).val() == 1)
        //     {
        //         $("#already").show();
        //         $("#late").hide();
        //     }
        //     else if($(this).val() == 2)
        //     {
        //         $("#late").show();
        //         $("#already").hide();
        //     }
        //     else
        //     {
        //         $("#late").hide();
        //         $("#already").hide();
        //     }
        // })
    </script>

    <script>
        $(".already_late").on('change', function() {
            if ($(this).is(":checked")) {
                $(this).parent('div').parent('div').siblings('.auction_already_late').show();
            } else {
                $(this).parent('div').parent('div').siblings('.auction_already_late').hide();
            }
        })
    </script>
    <script>
        $(".driverphoneno").keypress(function(e) {
            if ($(this).val() == '') {
                $(this).mask("(999) 999-9999");
            }
            var x = e.which || e.keycode;
            if ((x >= 48 && x <= 57) || (x >= 35 && x <= 40)) {
                return true;
            } else {
                return false;
            }
        })

        $(".showQaFilter").on('click', function() {
            $("#verifyNegative").toggle();
            $('#verify2').children('option').removeAttr('selected');
            $('#verify2').children('option').eq(0).attr('selected', true);
            $('#negative2').children('option').removeAttr('selected');
            $('#negative2').children('option').eq(0).attr('selected', true);
        })

        $("#request_name").change(function() {
            var datavalue = $(this).children('option:selected').attr('data-value');
            $("#status_request").val(datavalue);
            if (datavalue == 23) {
                $("#keyvalue").html(`
                    <div class="row mb-2">
                        <input type="hidden" name="key[]" value="Port" />
                        <div class="col-10">
                            <div class="form-group mb-0">
                                <label for="port1" class="form-label">Port 1</label>
                                <input type="text" placeholder="Port 1" name="value[]" id="port1" class="form-control" />
                            </div>
                        </div>
                        <div class="col-2 mt-auto">
                            <button type="button" class="btn btn-success addPort"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                `);
                var i = 1;
                $(document).on('click', ".addPort", function() {
                    i++;
                    $("#keyvalue").append(`
                        <div class="row mb-2">
                            <input type="hidden" name="key[]" value="Port" />
                            <div class="col-10">
                                <div class="form-group mb-0">
                                    <label for="port${i}" class="form-label">Port ${i}</label>
                                    <input type="text" placeholder="Port ${i}" name="value[]" id="port${i}" class="form-control" />
                                </div>
                            </div>
                            <div class="col-2 mt-auto">
                                <button type="button" class="btn btn-danger subPort"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                    `);
                })

                $(document).on('click', '.subPort', function() {
                    i--;
                    $(this).parent('div').parent('.row').remove();
                })
            } else {
                $("#keyvalue").html('');
            }
        })
    </script>

    <script>
        $("body").delegate(".add-approaching", "click", function() {
            var order_id = $(this).find('.Get-Order-ID').val();

            $(".show_order_id").html(order_id);
            $(".val_order_id").val(order_id);

            $.ajax({
                url: '{{ route('get.carrier_approachings') }}',
                type: 'GET',
                data: {
                    'order_id': order_id,
                },
                success: function(data) {
                    // Handle the success response
                    // console.log('datas', data);

                    var html = "";

                    $("#history-content").html('');

                    $.each(data, function(index, val) {
                        // Assuming val['created_at'] is a string representation of the date
                        var createdAt = new Date(val['created_at']);

                        // Format the date
                        var monthNames = ["Jan", "Feb", "Mar", "Apr", "May",
                            "Jun", "Jul",
                            "Aug", "Sep", "Oct", "Nov", "Dec"
                        ];
                        var formattedDate = monthNames[createdAt
                                .getMonth()] + "," +
                            ("0" + createdAt.getDate()).slice(-2) + " " +
                            createdAt.getFullYear() + " " +
                            ("0" + createdAt.getHours()).slice(-2) + ":" +
                            ("0" + createdAt.getMinutes()).slice(-2) +
                            (createdAt.getHours() >= 12 ? " PM" : " AM");

                        // Append formatted date to HTML
                        html += "<h6>User: " + val['user']['name'] + "</h6>";
                        html += "<h6>Company: " + val['comp_name'] + "</h6>";
                        html += "<h6>Company Phone: " + val['comp_phone'] + "</h6>";

                        // Handle status
                        html += "<h6>";

                        if (val['status'] == 1) {
                            html += "Interested";
                        } else if (val['status'] == 0) {
                            html += "Not Interested";
                        } else {
                            // Handle other cases if needed
                            html += "Unknown Status";
                        }

                        html += "</h6>";

                        html += "<h6>Response: " + val['comp_response'] + ".</h6>";
                        html +=
                            "<strong class='mf-date'><i class='fa fa-clock-o'></i>" +
                            formattedDate + "</strong> <hr>";
                    });

                    $("#history-content").html(html);
                },
                error: function(error) {
                    // Handle the error response
                    console.error('Error submitting the form:', error);
                    // Optionally, you can display an error message or take other actions
                }
            });
        });
    </script>
    <!--Scrolling Modal-->

    <script>
        document.getElementById('link').addEventListener('selectstart', function(e) {
            e.preventDefault();
            return false;
        });
    </script>

    <script>
        function disableButton() {
            // Submit the form (replace 'yourFormId' with the actual ID of your form)
            // document.getElementById('yourFormId').submit();
            // Disable the button
            // document.getElementById("save-disabled").disabled = true;
        }
    </script>

    <script>
        // $(document).ready(function() {
        //     $(".showDayDispatch").hide();
        //     $(document).on("change", ".statusList", function() {
        //         if ($(".statusList").val() == 9) {
        //             $(".showDayDispatch").show();
        //         } else {
        //             $(".showDayDispatch").hide();
        //         }
        //     });
        // });

        $(document).on("change", ".auc_review", function() {
            if ($(this).val() == 'Yes') {
                $(".all_rating").show();
            } else {
                $(".all_rating").hide();
            }
        })

        $(document).on("change", ".cancelOnApproval", function() {
            if ($(this).val() == 19) {
                $("#cancelDirectOnApproval").val(1);
            } else {
                $("#cancelDirectOnApproval").hide(0);
            }
        })
    </script>
@endsection
