@extends('layouts.innerpages')

@section('template_title')
    {{ ucfirst(trim("$_SERVER[REQUEST_URI]", '/')) }}
@endsection

@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet"/>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>

    <!--=================================multiselect tag============================== -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>
    <!--=================================multiselect tag============================== -->
    <style>
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

        .table {
            /*color: rgb(0 0 0);*/
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
        }

        .table > thead > tr > td,
        .table > thead > tr > th {
            font-weight: 400;
            -webkit-transition: all .3s ease;
            font-size: 18px;
            color: rgb(0 0 0);
        }

        .table-data-align {
            display: flex;
            align-items: flex-end;
        }

        .tx-white {
            color: white !important;
        }

        .badge-orange {
            color: #212529;
            background-color: #F49917;
        }

        .bg-white th {
            border: 1px solid #000000 !important;
        }

        .bg-white td {
            border: 1px solid #000000 !important;
        }

        .choices__inner {
            height: 50px;
            overflow-y: scroll;
            border: 1px solid #86c8ff;
        }

        .remaining {
            color: red;
        }
    </style>
    <div class="page-header">
        <!--<div class="page-leftheader">-->
        <input type="hidden" value="{{ trim("$_SERVER[REQUEST_URI]", '/') }}" id="titlee">
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Home</a>-->
        <!--        </li>-->
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">New Order</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Dealer Approaching</b></h1>
        </div>
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
                @php
                    $ptype = 1;
                    $query = \App\user_setting::where('user_id', Auth::user()->id)->first();
                    if (!empty($query)) {
                        $ptype = $query['penal_type'];
                    }
                          $datas = \App\ShipperDetailsDealer::select('states', 'type', \DB::raw('COUNT(CASE WHEN user_id = 0 THEN 1 ELSE NULL END) as total_with_user_id_0'), \DB::raw('COUNT(*) as total'), \DB::raw('COUNT(CASE WHEN user_id = 0 THEN 1 ELSE NULL END) as remaining'))->where('states', '!=', '-')
                          ->groupBy('states','type')
                          ->orderBy('states', 'asc')->get();

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
                        $phoneaccess = []; // Default case if $ptype is not within 1-6
                    }
                @endphp
                @if (in_array('141', $phoneaccess) || Auth::user()->role == 1 || Auth::user()->role == 9 || in_array('144', $phoneaccess))
                    @if (Auth::user()->role == 1 || Auth::user()->role == 9 || in_array('144', $phoneaccess))
                        <div class="card-header">
                            <div class="container-fluid">
                                <div class="col-lg-12 p-0">
                                        <?php
                                        $user = \App\User::where('deleted', 0)->where('role', 2)->get();

                                        ?>
                                    <form method="post" action="{{ route('store.autos.approach_new_dealer') }}">
                                        @csrf
                                        <div class="row" style="border:groove;padding: 10px">
                                            <div class="col-lg-2">
                                                <label style="float: left">Type</label>
                                                <select id="type" name="type" class="form-control type" required>
                                                    <option value="">Select Type</option>
                                                    <option value="4">Dealer</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-3">
                                                <label style="float: left">States</label>
                                                <select id="state" name="state[]" multiple
                                                        class="form-control state_assign" required>


                                                </select>
                                            </div>

                                            <div class="col-lg-3">
                                                <label style="float: left">Order Taker</label>
                                                <div class='input-group'>
                                                    <select name="orderTaker" class="form-control" id="orderTaker"
                                                            required>
                                                        <option selected value="">Select</option>
                                                        @foreach ($user as $key => $val)
                                                            <option value="{{ $val->id }}">
                                                                {{ $val->name . ' ' . $val->last_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <label style="float: left">Phone</label>
                                                <select id="w_phone2" name="w_phone2" class="form-control" required>
                                                    <option selected value="">Select</option>
                                                    <option value="1">With Phone</option>
                                                    <option value="0">Without Phone</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-2">
                                                <label style="float: left">Allow No. of Records</label>
                                                <div class='input-group'>
                                                    <input type='number' required name="recordsAllowed"
                                                           id="recordsAllowed"
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 mt-auto">
                                                <button class="btn btn-primary" type="submit">
                                                    <i class="fa fa-search"></i> Save
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row" style="border:groove;padding: 10px">
                                        <div class="col-lg-3">
                                            <label style="float: left">Search AS</label>
                                            <div class='input-group'>
                                                <select name="search_as" class="form-control"
                                                        id="search_as">
                                                        <option value="1">Admin</option>
                                                        <option value="2">Myself</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <label style="float: left">Order Taker</label>
                                            <div class='input-group'>
                                                <select name="orderTakerSearch" class="form-control"
                                                        id="orderTakerSearch"
                                                        required>
                                                    <option selected value="">Select</option>
                                                    @foreach ($user as $key => $val)
                                                        <option value="{{ $val->id }}">
                                                            {{ $val->name . ' ' . $val->last_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <label style="float: left">Email</label>
                                            <select id="emailComp" name="emailComp[]" class="form-control" required>
                                                <option selected value="">Select</option>
                                                <option value="1">With Email</option>
                                                <option value="0">Without Email</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <label style="float: left">Phone</label>
                                            <select id="w_phone" name="w_phone" class="form-control" required>
                                                <option selected value="">Select</option>
                                                <option value="1">With Phone</option>
                                                <option value="0">Without Phone</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <label style="float: left">States</label>
                                            <select id="stateComp" name="stateComp[]" class="form-control" required>
                                                <option selected value="">Select</option>
                                                @foreach ($datas as $key => $val)
                                                    <option value="{{ $val->states }}">
                                                        {{ $val->states }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <label style="float: left">Search</label>
                                            <div class='input-group'>
                                                <input type='text' required name="searchComp" id="searchComp"
                                                       class="form-control searchComp"/>
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <label style="float: left">Daterange</label>
                                            <div class='input-group'>


                                                <div class="row">

                                                    <!--<label style="float: left">Daterange</label>-->
                                                    <div class='input-group date' id='datetimepicker1'>
                                                        <input type='text' name="date_range" id="date_range"
                                                               class="form-control"/>
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
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <label style="float: left">Type</label>
                                            <select id="type2" name="type2" class="form-control type2">
{{--                                                <option value="">Select Type</option>--}}
                                                <option value="4">Dealer</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-4">
                                            <label style="float: left">Template</label>
                                            <select id="template" name="template" class="form-control template" >
                                                @foreach($template as $vl)
                                                    <option value="{{ $vl->id }}">
                                                        {{ $vl->name }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <label style="float: left">Limit</label>
                                            <input type="text" min="0" max="10000"  placeholder="limit" class="form-control" name="limit_data" id="limit_data">
                                        </div>
                                        <div class="col-lg-3 mt-auto">
                                            <button class="btn btn-primary" type="button" id="send_template">
                                                Send Email Template to selected filter
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <a class="btn btn-primary" href="{{ route('all.autos.autos_approach_new_dealer') }}">
                                                <i class="fa fa-search"></i> View Report
                                            </a>
                                        </div>
                                        <div class="col-lg-2">
                                            <button type="button" class="btn btn-primary get-history" data-toggle="modal"
                                                    data-target="#exampleModal7">Add New
                                                <input hidden type="text" class="Company-ID" value="{{ $val->id }}">
                                            </button>
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>
                    @else

                        <div class="card-header">
                            <div class="container-fluid">
                                <div class="col-lg-12 p-0">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <label style="float: left">Search</label>
                                            <div class='input-group'>
                                                <input type='text' required name="searchComp" id="searchComp"
                                                       class="form-control searchComp"/>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <label style="float: left">Select</label>
                                            <div class='input-group'>
                                                <select id="withHistory" name="withHistory" class="form-control"
                                                        required>
                                                    <option value="">Select</option>
                                                    <option value="1">With History</option>
                                                    <option value="0">Without History
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="card-header">
                        <div class="container-fluid">
                            <div class="col-lg-12 p-0">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label style="float: left">Search</label>
                                        <div class='input-group'>
                                            <input type='text' required name="searchComp" id="searchComp"
                                                   class="form-control searchComp"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label style="float: left">Select</label>
                                        <div class='input-group'>
                                            <select id="withHistory" name="withHistory" class="form-control" required>
                                                <option value="">Select</option>
                                                <option value="1">With History</option>
                                                <option value="0">Without History
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (in_array('96', $phoneaccess))
                    <div class="card-header">
                        <div class="container-fluid">
                            <div class="col-lg-12 p-0">
                                    <?php
                                    $state = \App\User::with('assignedDataNew')->has('assignedDataNew')->first();
                                    $state = $state->assignedDataNew->state;

                                    ?>

                                <div class="row">
                                    <div class="col-lg-3">
                                        <label style="float: left">States</label>
                                        <select id="myState" name="state" class="form-control">
                                            <option selected value="">Select</option>
                                            {{--                                           @foreach ($state as $key => $val)--}}
                                            {{--                                                <option value="{{ $val->state }}">--}}
                                            {{--                                                    {{ $val->state }}--}}
                                            {{--                                                </option>--}}
                                            {{--                                            @endforeach--}}
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label style="float: left">Search</label>
                                        <div class='input-group'>
                                            <input type="text" class="form-control" name="search" id="search">
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-lg-2 mt-auto">
                                    <a class="btn btn-primary" href="{{ route('all.autos.approach') }}">
                                        <i class="fa fa-search"></i> Filter
                                    </a>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                @endif
                <div class="card-body">
                    <div id="table_data">

                        @include('main.phone_quote.ShipperDetailsDealer.table')
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end app-content-->
    <div class="modal fade" id="largemodal" tabindex="-1" role="dialog" aria-labelledby="largemodal"
         aria-hidden="true">
        <div class="modal-dialog modal-lg " role="document">
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
                                    <ul class="nav panel-tabs">
                                        <li class=""><a href="#tab1" class="active"
                                                        data-toggle="tab">HISTORY/STATUS</a>
                                        </li>
                                        <li><a href="#tab2" data-toggle="tab">VIEW HISTORY</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body tabs-menu-body">
                                <div class="tab-content">
                                    <div class="tab-pane active " id="tab1">
                                        <form method="post" action="{{ route('call_history_post_2') }}">
                                            @csrf
                                            <div class="row">
                                                <input type="hidden" class="form-control" name="order_id1"
                                                       id='order_id1' placeholder="" readonly>
                                                <input type="hidden" class="form-control" name="approaching"
                                                       id='approaching' value="1">
                                                <div class="col-sm-6 col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">HISTORY</label>
                                                        <textarea required name="history_update" id='history_update'
                                                                  class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </form>
                                    </div>
                                    <div class="tab-pane" id="tab2">
                                        <div class="chat-body-style ChatBody" id="calhistory"
                                             style="overflow:scroll; height:300px;">
                                            <div class="message-feed media">
                                                <div class="media-body">
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
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            onclick="modalClick()">Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal7" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel8"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    {{-- <h5 class="modal-title" id="exampleModalLabel7">Employee Access (Assign
                        Data)</h5> --}}

                    <h5 class="modal-title" id="exampleModalLabel">Add New Company <span class="history_id"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="modal-body history-content">
                                <form action="{{ route('Admin.autos_approach_new.Store_dealer') }}" method="POST"
                                      class="needs-validation" novalidate>
                                    @csrf

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Client Name <span
                                                    class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name"
                                               required maxlength="500" placeholder="Enter Client Name"
                                               autocomplete="off"/>
                                        <span class="error" role="alert" id="name_error" style="display: none;">
                                            <strong>This name already exists.</strong>
                                        </span>
                                        <ul class="suggestions suggestionsName"></ul>
                                    </div>

                                    <div class="mb-3">
                                        <label for="type" class="form-label">Type
                                        </label>
                                        <select id="type" name="type" class="form-control" required>
                                            <option value="">Select Type</option>
                                            <option value="4">Dealer</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone 1 <span
                                                    class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="phone" name="phone"
                                               required maxlength="500" placeholder="Enter Phone Number"
                                               autocomplete="off"/>
                                        <span class="error" role="alert" id="phone_error" style="display: none;">
                                            <strong>This phone number already exists.</strong>
                                        </span>
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone2" class="form-label">Phone2
                                        </label>
                                        <input type="text" class="form-control" id="phone2" name="phone2"
                                               maxlength="500" placeholder="Enter Phone2 Number" autocomplete="off"/>
                                        <div class="invalid-feedback">Phone2 Number</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone3" class="form-label">Phone3
                                        </label>
                                        <input type="text" class="form-control" id="phone3" name="phone3"
                                               maxlength="500" placeholder="Enter Phone3 Number" autocomplete="off"/>
                                        <div class="invalid-feedback">Phone3 Number</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="address" class="form-label">Address
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="address" name="address"
                                               required maxlength="500" placeholder="Enter Address" autocomplete="off"/>
                                        <span class="error" role="alert" id="address_error" style="display: none;">
                                            <strong>This address already exists.</strong>
                                        </span>
                                    </div>

                                    <div class="mb-3">
                                        <label for="state_add" class="form-label">State
                                        </label>
                                        <select name="state_add" class="form-control" id="state_add">
                                            <option selected value="">Select</option>
                                            <!--<option value="SA">SA</option>-->
                                            <!--<option value="WS">WS</option>-->
                                            <option value="AS">AS</option>
                                            @foreach ($datas as $key => $val)
                                                <option value="{{ $val->states }}">
                                                    {{ $val->states . ' ' . '(' . $val->total . ')' }}</option>
                                            @endforeach
                                        </select>
                                        <!--<input type="text" class="form-control" id="state_add" name="state_add"-->
                                        <!--    maxlength="500" placeholder="Enter State" autocomplete="off" />-->
                                        <div class="invalid-feedback">State</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email
                                        </label>
                                        <input type="email" class="form-control" id="email" name="email"
                                               maxlength="500" placeholder="Enter Email" autocomplete="off"/>
                                        <span class="error" role="alert" id="email_error" style="display: none;">
                                            <strong>This email already exists.</strong>
                                        </span>
                                    </div>

                                    <div class="mb-3">
                                        <label for="website" class="form-label">Website
                                        </label>
                                        <input type="text" class="form-control" id="website" name="website"
                                               maxlength="500" placeholder="Enter Website" autocomplete="off"/>
                                        <div class="invalid-feedback">Website</div>
                                    </div>


                                    <div class="mb-3">
                                        <label for="category" class="form-label">Other Details
                                            <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="other_details" class="form-control"></textarea>
                                    </div>

                                    <div class="mt-4">
                                        <button class="btn btn-success w-100" type="submit" id="submit_button">
                                            Create
                                        </button>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Save</button>
            </div> --}}
        </div>
    </div>
@endsection

@section('extraScript')
    <!--==============================multiselect tag====================-->
    <script>
        $(document).ready(function () {
            $('#state').select2({
                placeholder: "Select States",
                allowClear: true,
            });

            var all_states = @json($datas);

            $('#type').change(function () {
                let selectedType = $(this).val(); // Get the selected type
                let filteredStates = all_states.filter(item => item.type == selectedType); // Filter states by type


                $('#state').empty(); // Clear existing options

                if (filteredStates.length > 0) {
                    filteredStates.forEach(state => {
                        $('#state').append(new Option(`${state.states} (${state.total}) (${state.remaining})`, state.states));
                    });
                } else {
                    $('#state').append(new Option("No states available", "", false, false));
                }


                $('#state').trigger('change');
            });


        });

    </script>
    <!--==============================multiselect tag====================-->
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
            var encryptvuserid = btoa({{ Auth::user()->id }});
            var encryptvoderid = btoa(orderId);
            var linkv = "{{ url('/email_order/') }}" + '/' + encryptvoderid + '/' + encryptvuserid;
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


        $(document).ready(function () {
            // Comma-separated string of states
            var statesString = '{{ $state }}';

            // Split the string into an array
            var statesArray = statesString.split(',');

            // Reference to the select element
            var $select = $('#myState');

            // Populate the select with options
            $.each(statesArray, function (index, value) {
                $select.append($('<option>', {
                    value: value,
                    text: value
                }));
            });
        });


    </script>


    <script>


        $(document).ready(function () {

            var startDate;
            var endDate;

            function fetchData(page,send_template = null,template = null,limit_data = null) {
                var stateValue = $('#stateComp').val();
                var search_as = $('#search_as').val();
                if(!search_as){
                    search_as = 2;
                }
                if (!stateValue) {
                    stateValue = $('#myState').val();
                }
                var type2 = $('#type2').val();
                // var searchValue = $('#searchComp').val();
                var searchValue = $('.searchComp').val();
                if (!searchValue) {
                    searchValue = $('#search').val();
                }
                var emailValue = $('#emailComp').val();
                var w_phone = $('#w_phone').val();
                var emailsSent = $('#emailsSent').val();
                var orderTakerSearch = $('#orderTakerSearch').val();

                var formattedStartDate;
                var formattedEndDate;
                if(startDate){
                    formattedStartDate = startDate.format('YYYY-MM-DD');
                    formattedEndDate = endDate.format('YYYY-MM-DD');
                }


                // console.log('stateValue', stateValue);
                // console.log('searchValue', searchValue);

                // Perform AJAX call with filter criteria and page number
                $.ajax({
                    url: '{{ route('autos.approach_new.search_dealer') }}' + '?page=' + page,
                    type: 'GET',
                    data: {
                        state: stateValue,
                        search: searchValue,
                        type: type2,
                        email: emailValue,
                        orderTaker: orderTakerSearch,
                        emailsSent: emailsSent,
                        w_phone: w_phone,
                        startDate: formattedStartDate,
                        endDate: formattedEndDate,
                        search_as: search_as,
                        send_template : send_template,
                        template : template,
                        limit_data : limit_data,
                    },
                    success: function (response) {
                        // Update the UI with the fetched data
                        // console.log('Fetched data:', response);
                        $("#pagination-container").html(response.pagination);
                        $("#table_data").html(response);
                    },
                    error: function (error) {
                        console.error('Error:', error);
                        // Handle errors if needed
                    }
                });
            }

            $('#stateComp').change(function () {
                fetchData(1); // Trigger AJAX call on state change for the first page
            });

            // Listen for change event on #category
            $('#type2').change(function () {
                fetchData(1); // Trigger AJAX call on state change for the first page
            });

            // Listen for keyup event on #searchComp
            // $('#searchComp').keyup(function() {
            //     fetchData(1); // Trigger AJAX call on keyup for the first page
            // });
            $('.searchComp').keyup(function () {
                fetchData(1); // Trigger AJAX call on keyup for the first page
            });

            // Listen for change event on #emailComp
            $('#emailComp').change(function () {
                fetchData(1); // Trigger AJAX call on change for the first page
            });

            // Listen for change event on #orderTakerSearch
            $('#orderTakerSearch').change(function () {
                fetchData(1); // Trigger AJAX call on change for the first page
            });

            // Listen for change event on #orderTakerSearch
            $('#emailsSent').on('change', function (event) {
                fetchData(1); // Trigger AJAX call on change for the first page
            });
            $('#w_phone').on('change', function (event) {
                fetchData(1); // Trigger AJAX call on change for the first page
            });

            // Listen for click events on pagination links
            $('#table_data').on('click', '.pagination a', function (event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                fetchData(page); // Trigger AJAX call for the clicked page number
            });


            $('#date_range').daterangepicker({
                "showDropdowns": true,
                "opens": "center",
                "drops": "auto",
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                }
            }, function (start, end, label) {
                startDate = start;
                endDate = end;
                // Fetch data when date range changes
                fetchData(1);
            });

            // Handle click event for pagination links
            $('#table_data').on('click', '.pagination a', function (event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                fetchData(page);
            });

            $(document).on("change", "#myState", function (e) {
                e.preventDefault();

                var myState = $("#myState").val();

                fetchData(1);
            });
            $(document).on("change", "#search_as", function (e) {
                e.preventDefault();

                fetchData(1);
            });

            $(document).on("keyup", "#search", function (e) {
                e.preventDefault();

                var search = $("#search").val();
                fetchData(1);

            });

            $('#send_template').on('click', function(event) {
                var template = $('#template').val();
                var limit_data = $('#limit_data').val();
                fetchData(1,1,template,limit_data);
                alert('Email Will be Released after 5 minutes');
            });

            fetchData(1);
        });
    </script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        // Initialize global variables for startDate and endDate


        $(document).ready(function () {
            // Function to fetch data based on selected filters
            {{--function fetchData(page) {--}}
            {{--    var stateValue = $('#stateComp').val();--}}
            {{--    var type2 = $('#type2').val();--}}
            {{--    var searchValue = $('.searchComp').val();--}}
            {{--    var emailValue = $('#emailComp').val();--}}
            {{--    var orderTakerSearch = $('#orderTakerSearch').val();--}}

            {{--    // Format dates using Moment.js before sending them in the AJAX request--}}
            {{--    var formattedStartDate = startDate.format('YYYY-MM-DD');--}}
            {{--    var formattedEndDate = endDate.format('YYYY-MM-DD');--}}

            {{--    $.ajax({--}}
            {{--        url: '{{ route('autos.approach_new.search_dealer') }}' + '?page=' + page,--}}
            {{--        type: 'GET',--}}
            {{--        data: {--}}
            {{--            state: stateValue,--}}
            {{--            search: searchValue,--}}
            {{--            type2: type2,--}}
            {{--            email: emailValue,--}}
            {{--            startDate: formattedStartDate,--}}
            {{--            endDate: formattedEndDate,--}}
            {{--            orderTaker: orderTakerSearch,--}}
            {{--        },--}}
            {{--        success: function (response) {--}}
            {{--            // Update pagination--}}
            {{--            // Delayed update after 2 seconds--}}
            {{--            $("#table_data").html('');--}}
            {{--            setTimeout(function () {--}}
            {{--                $("#pagination-container").html(response.pagination);--}}
            {{--                $("#table_data").html(response);--}}
            {{--            }, 1000); // 2000 milliseconds = 2 seconds--}}
            {{--        },--}}
            {{--        error: function (error) {--}}
            {{--            console.error('Error:', error);--}}
            {{--        }--}}
            {{--    });--}}
            {{--}--}}

            // Initialize date range picker

        });
    </script>

    <script>
        $(document).ready(function () {
            $('#name').keyup(function () {
                var inputField = $(this);
                var suggestionsList = inputField.siblings(".suggestionsName");
                suggestionsList.css("display", "block");
                if (inputField.val() === "") {
                    suggestionsList.css("display", "none");
                }
                updateSuggestions(inputField, suggestionsList);
            });

            function updateSuggestions(inputField, suggestionsList) {
                var inputValue = inputField.val();

                $.ajax({
                    url: '{{ route('autos_approach_new.validation.check_dealer') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        field_name: 'name',
                        field_value: inputValue
                    },
                    success: function (response) {
                        suggestionsList.empty();

                        // if (!response.valid) {
                        //     $('#name_error').show();
                        // } else {
                        //     $('#name_error').hide();
                        // }

                        $.each(response.related_names, function (index, suggestion) {
                            var listItem = $("<li>").text(suggestion).click(function () {
                                inputField.val(suggestion);
                                suggestionsList.css("display", "none");
                            });
                            suggestionsList.append(listItem);
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error("Error:", error);
                    }
                });
            }

            $('#address, #email, #phone').on('keyup', function () {
                var fieldName = $(this).attr('name');
                var fieldValue = $(this).val();

                $.ajax({
                    url: '{{ route('autos_approach_new.validation.check_dealer') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        field_name: fieldName,
                        field_value: fieldValue
                    },
                    success: function (response) {
                        if (response.valid) {
                            $('#' + fieldName + '_error').hide();
                        } else {
                            $('#' + fieldName + '_error').show();
                        }

                        if ($('#address_error').is(':visible') || $('#email_error').is(
                            ':visible') || $('#phone_error').is(':visible')) {
                            // $('#submit_button').prop('disabled', true);
                        } else {
                            // $('#submit_button').prop('disabled', false);
                        }
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#withHistory').change(function () {
                var withHistory = $('#withHistory').val();
                var search_as = $('#search_as').val();
                if(!search_as){
                    search_as = 2;
                }
                // Perform AJAX request to validate the field
                $.ajax({
                    url: '{{ route('autos.approach_new.search_dealer') }}',
                    type: 'GET',
                    data: {
                        withHistory: withHistory,
                        search_as: search_as,
                    },
                    success: function (response) {
                        // Update pagination
                        $("#table_data").html('');
                        setTimeout(function () {
                            $("#pagination-container").html(response.pagination);
                            $("#table_data").html(response);
                        }, 1000); // 2000 milliseconds = 2 seconds
                    },
                });
            });
        });

    </script>
    <script>
        $(document).on('click', '.send-email', function (event) {
            event.preventDefault();
            var email = $(this).find('.Email-Address').val();


            $.ajax({
                url: '{{ route('send.user.mail') }}',
                type: 'GET',
                data: {
                    email: email,
                },
                success: function (response) {
                    if (response.message) {
                        alert(response.message);
                    } else if (response.error) {
                        alert(response.error);
                    } else {
                        alert('Email sent');
                    }
                },
                error: function (xhr, status, error) {
                    // Parse the error message from the server
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ?
                        xhr.responseJSON.error :
                        'An error occurred while sending the email.';
                    alert(errorMessage);
                }
            });
        });

        $(document).on('click', '.view_email_history', function (event) {
            event.preventDefault();
            var email = $(this).find('.History-Email-Address').val();

            $.ajax({
                url: '{{ route('get.email.history') }}',
                type: 'GET',
                data: {
                    'email': email,
                },
                success: function (data) {
                    console.log('datas', data);
                    $(".email-history-content").html('');
                    html = "";
                    $.each(data, function (index, val) {
                        var createdAt = new Date(val['created_at']);

                        var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul",
                            "Aug", "Sep", "Oct", "Nov", "Dec"
                        ];
                        var formattedDate = monthNames[createdAt.getMonth()] + "," +
                            ("0" + createdAt.getDate()).slice(-2) + " " +
                            createdAt.getFullYear() + " " +
                            ("0" + createdAt.getHours()).slice(-2) + ":" +
                            ("0" + createdAt.getMinutes()).slice(-2) +
                            (createdAt.getHours() >= 12 ? " PM" : " AM");

                        html += "<h6>Sender " + val['user']['name'] + "</h6>";
                        html += "<h6>Template title " + val['template']['title'] + ".</h6>";
                        html += "<strong class='mf-date'><i class='fa fa-clock-o'></i>" +
                            formattedDate + "</strong> <hr>";
                    });
                    $(".email-history-content").html(html);
                },
                error: function (error) {
                    console.error('Error submitting the form:', error);
                }
            });
        });
    </script>
@endsection
