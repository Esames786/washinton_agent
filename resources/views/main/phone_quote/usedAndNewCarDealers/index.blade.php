@extends('layouts.innerpages')

@section('template_title')
    {{ ucfirst(trim("$_SERVER[REQUEST_URI]", '/')) }}
@endsection

@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

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

        .table>thead>tr>td,
        .table>thead>tr>th {
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
            <h1 class="my-4"><b>Autos Approach</b></h1>
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
                    $datas = \App\UsedAndNewCarDealers::select(
                        'state',
                        \DB::raw('MIN(id) as id'),
                        \DB::raw('COUNT(CASE WHEN user_id = 0 THEN 1 ELSE NULL END) as total_with_user_id_0'),
                        \DB::raw('COUNT(*) as total'),
                    )
                        ->where('state', '!=', '-')
                        ->groupBy('state')
                        ->orderBy('state', 'asc')
                        ->get();

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
                @if (in_array('145', $phoneaccess) || Auth::user()->role == 1 || Auth::user()->role == 9)
                    @if (Auth::user()->role == 1 || Auth::user()->role == 9 || in_array('145', $phoneaccess))
                        <div class="card-header">
                            <div class="container-fluid">
                                <div class="col-lg-12 p-0">
                                    <?php
                                    // $datas = \App\UsedAndNewCarDealers::select('state', \DB::raw('MIN(id) as id'), \DB::raw('COUNT(CASE WHEN user_id = 0 THEN 1 ELSE NULL END) as total_with_user_id_0'), \DB::raw('COUNT(*) as total'))->where('state', '!=', '-')->groupBy('state')->orderBy('state', 'asc')->get();
                                    $datas = \App\UsedAndNewCarDealers::select('state', \DB::raw('MIN(id) as id'), \DB::raw('COUNT(CASE WHEN user_id = 0 THEN 1 ELSE NULL END) as total_with_user_id_0'), \DB::raw('COUNT(*) as total'), \DB::raw('COUNT(CASE WHEN user_id = 0 THEN 1 ELSE NULL END) as remaining'))->where('state', '!=', '-')->groupBy('state')->orderBy('state', 'asc')->get();
                                    // dd($Auto, $Automotive, $New, $Used);

                                    $user = \App\User::where('deleted', 0)->where('role', 2)->get();

                                    ?>
                                    <form method="post" action="{{ route('store.autos.approach') }}">
                                        @csrf
                                        <div class="row" style="border:groove;padding: 10px">
                                            <div class="col-lg-3">
                                                <label style="float: left">States</label>
                                                <select id="state" name="state[]" multiple
                                                    class="form-control state_assign" required>
                                                    <!--<option value="SA">SA</option>-->
                                                    <!--<option value="WS">WS</option>-->
                                                    @foreach ($datas as $key => $val)
                                                        <option value="{{ $val->state }}">
                                                            {{ $val->state }} ({{ $val->total }})
                                                            (<span style="color: red">{{ $val->remaining }}</span>)
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3">
                                                <label style="float: left">Categories</label>
                                                <div id="category_assign_select">

                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <label style="float: left">Order Taker</label>
                                                <div class='input-group'>
                                                    <select name="orderTaker" class="form-control" id="orderTaker" required>
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
                                                <label style="float: left">Allow No. of Records</label>
                                                <div class='input-group'>
                                                    <input type='number' required name="recordsAllowed" id="recordsAllowed"
                                                        class="form-control" />
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
                                            <label style="float: left">Order Taker</label>
                                            <div class='input-group'>
                                                <select name="orderTakerSearch" class="form-control" id="orderTakerSearch"
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
                                            <label style="float: left">States</label>
                                            <select id="stateComp" name="stateComp[]" class="form-control" required>
                                                <option selected value="">Select</option>
                                                {{-- @foreach ($datas as $key => $val)
                                                    <option value="{{ $val->state }}">
                                                        {{ $val->state . ' ' . '(' . $val->total . ')' }}</option>
                                                @endforeach --}}
                                                @foreach ($datas as $key => $val)
                                                    <option value="{{ $val->state }}">
                                                        {{ $val->state }} ({{ $val->total }})
                                                        (<span class="remaining">{{ $val->remaining }}</span>)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <label style="float: left">Search</label>
                                            <div class='input-group'>
                                                <input type='text' required name="searchComp" id="searchComp"
                                                    class="form-control searchComp" />
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <label style="float: left">Daterange</label>
                                            <div class='input-group'>


                                                <div class="row">

                                                    <!--<label style="float: left">Daterange</label>-->
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
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <label style="float: left">Categories</label>
                                            <select id="category" name="category" class="form-control" required>
                                                <option value="">Select Category</option>
                                                <option value="Auto Dealership">Auto Dealership</option>
                                                <option value="Automotive Repair Services">Automotive Repair Services
                                                </option>
                                                <option value="New Car Dealer">New Car Dealer</option>
                                                <option value="Used Car Dealer">Used Car Dealer</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-2">
                                            <label style="float: left">Template</label>
                                            <select id="template" name="template" class="form-control template" >
                                                @foreach($template as $vl)
                                                    <option value="{{ $vl->id }}">
                                                        {{ $vl->name }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>
                                        <div class="col-lg-2">
                                            <label style="float: left">Limit</label>
                                            <input type="text" min="0" max="10000" placeholder="limit" class="form-control" name="limit_data" id="limit_data" value="">
                                        </div>
                                        <div class="col-lg-2 mt-auto">
                                            <button class="btn btn-primary" type="button" id="send_template">
                                                Send Email Template
                                            </button>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-lg-2 mt-auto">
                                            <a class="btn btn-primary" href="{{ route('all.autos.approach') }}">
                                                <i class="fa fa-search"></i> View Report
                                            </a>
                                        </div>
                                        <div class="col-lg-2 mt-auto">
                                            <a class="btn btn-primary" href="{{ route('all.autosapproach.whatsapp') }}">
                                                <i class="fa fa-search"></i> Whatsapp Call Counts
                                            </a>
                                        </div>
                                        <div class="col-lg-2 mt-auto">
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
                                                    class="form-control searchComp" />
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
                                        <label style="float: left">Searchs</label>
                                        <div class='input-group'>
                                            <input type='text' required name="searchComp" id="searchComp"
                                                class="form-control searchComp" />
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

                @if (in_array('146', $phoneaccess))
                    <div class="card-header">
                        <div class="container-fluid">
                            <div class="col-lg-12 p-0">
                                    <?php
                                    $state = \App\User::with('assignedData')->has('assignedData')->first();
                                    $state = $state->assignedData->state;
                                    ?>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label style="float: left">States</label>
                                        <select id="myState" name="state" class="form-control">
                                            <option selected value="">Select</option>
                                            {{-- @foreach ($state as $key => $val)
                                                <option value="{{ $val->state }}">
                                                    {{ $val->state }}
                                                </option>
                                            @endforeach --}}
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
                        {{-- dd($data->toArray()) --}}
                        @include('main.phone_quote.usedAndNewCarDealers.table')
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
                                                        <textarea required name="history_update" id='history_update' class="form-control"></textarea>
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
                        onclick="modalClick()">Close</button>
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
                                <form action="{{ route('Admin.Approach.Store') }}" method="POST"
                                    class="needs-validation" novalidate>
                                    @csrf

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Client Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            required maxlength="500" placeholder="Enter Client Name"
                                            autocomplete="off" />
                                        <span class="error" role="alert" id="name_error" style="display: none;">
                                            <strong>This name already exists.</strong>
                                        </span>
                                        <ul class="suggestions suggestionsName"></ul>
                                    </div>

                                    <div class="mb-3">
                                        <label for="person_name" class="form-label">Person Name
                                        </label>
                                        <input type="text" class="form-control" id="person_name" name="person_name"
                                            maxlength="500" placeholder="Enter Person Name" autocomplete="off" />
                                        <div class="invalid-feedback">Person Name</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone 1 <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="phone" name="phone"
                                            required maxlength="500" placeholder="Enter Phone Number"
                                            autocomplete="off" />
                                        <span class="error" role="alert" id="phone_error" style="display: none;">
                                            <strong>This phone number already exists.</strong>
                                        </span>
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone2" class="form-label">Phone2
                                        </label>
                                        <input type="text" class="form-control" id="phone2" name="phone2"
                                            maxlength="500" placeholder="Enter Phone2 Number" autocomplete="off" />
                                        <div class="invalid-feedback">Phone2 Number</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone3" class="form-label">Phone3
                                        </label>
                                        <input type="text" class="form-control" id="phone3" name="phone3"
                                            maxlength="500" placeholder="Enter Phone3 Number" autocomplete="off" />
                                        <div class="invalid-feedback">Phone3 Number</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="address" class="form-label">Address
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="address" name="address"
                                            required maxlength="500" placeholder="Enter Address" autocomplete="off" />
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
                                                <option value="{{ $val->state }}">
                                                    {{ $val->state . ' ' . '(' . $val->total . ')' }}</option>
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
                                            maxlength="500" placeholder="Enter Email" autocomplete="off" />
                                        <span class="error" role="alert" id="email_error" style="display: none;">
                                            <strong>This email already exists.</strong>
                                        </span>
                                    </div>

                                    <div class="mb-3">
                                        <label for="website" class="form-label">Website
                                        </label>
                                        <input type="text" class="form-control" id="website" name="website"
                                            maxlength="500" placeholder="Enter Website" autocomplete="off" />
                                        <div class="invalid-feedback">Website</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="link" class="form-label">Link
                                        </label>
                                        <input type="text" class="form-control" id="link" name="link"
                                            maxlength="500" placeholder="Enter Link" autocomplete="off" />
                                        <div class="invalid-feedback">Link</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="category" class="form-label">Category
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select id="category" name="category" class="form-control" required>
                                            <option value="">Select Category</option>
                                            <option value="Auto Dealership">Auto Dealership</option>
                                            <option value="Automotive Repair Services">Automotive Repair Services
                                            </option>
                                            <option value="New Car Dealer">New Car Dealer</option>
                                            <option value="Used Car Dealer">Used Car Dealer</option>
                                        </select>
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
        $(document).ready(function() {
            var stateSelect = new Choices('#state', {
                removeItemButton: true,
                searchEnabled: true,
                placeholderValue: 'Select States',
            });

            $('.state_assign').change(function() {
                $('#category_assign_select').empty(); // Clear the previous categories
                $('#category_assign_select').prop('disabled', false);
                var statesGet = $('#state').val();

                $.ajax({
                    url: '{{ route('autos.approach.CategoryCount') }}',
                    type: 'GET',
                    data: {
                        state: statesGet,
                    },
                    success: function(response) {
                        console.log('responseresponse', response);

                        // Build the select element
                        var html = `
                    <select id="category_assign" name="category_assign[]" multiple class="form-control" required>
                        <option value="">Select Category</option>
                    `;

                        var categories = {
                            'Auto Dealership': 'Auto',
                            'Automotive Repair Services': 'Automotive',
                            'New Car Dealer': 'New',
                            'Used Car Dealer': 'Used',
                            'Automobile Dealers Electric Cars': 'Automobile_Electric',
                            'Automobile Dealers-electric Cars': 'Automobile_Electric_Alt',
                            'Automobile Dealership': 'Automobile_Dealership',
                            'Automobile Dlrs Custom Designed Replica': 'Automobile_Custom_Replica',
                            'Automobile Dlrs-custom Designed Replica': 'Automobile_Custom_Replica_Alt',
                            'Automobile Sales & Service': 'Automobile_Sales_Service',
                            'Automobile Specialty': 'Automobile_Specialty',
                            'Automobilendealers-used Cars': 'Automobilendealers_Used',
                            'Automobiles': 'Automobiles',
                            'Automobiles Pick-up Trucks & Vans': 'Automobiles_Pickup_Vans',
                            'Automobiles, New And Used': 'Automobiles_New_Used',
                            'Automobiles-fleet Sales': 'Automobiles_Fleet_Sales',
                            'Automotive Dealers & Service Stations': 'Automotive_Dealers_Service',
                            'Four Wheel Drive Vehicles': 'Four_Wheel_Drive',
                            'Limousine-dealers': 'Limousine_Dealers',
                            'Motor Vehicle Dealers (new And Used)': 'Motor_Vehicle_Dealers_New_Used',
                            'New & Used Car Dlrs': 'New_Used_Car_Dlrs',
                            'New And Used Car Dealers': 'New_Used_Car_Dealers',
                            'New And Used Car Dealers, Nec': 'New_Used_Car_Dealers_Nec',
                            'New And Used Car Dealers; Nec': 'New_Used_Car_Dealers_SemiColon',
                            'New And Usedcar Dealers, Nec': 'New_Used_Car_Dealers_Nec_Alt'
                        };

                        $.each(categories, function(categoryName, responseKey) {
                            if (response[responseKey] > 0) {
                                html +=
                                    `<option value="${categoryName}">${categoryName} (${response[responseKey]})</option>`;
                            }
                        });

                        html += `</select>`; // Close the select tag

                        // Insert the new select element into the #category_assign_select div
                        $('#category_assign_select').html(html);

                        // Initialize Choices.js for the new select element
                        var categorySelect = new Choices('#category_assign', {
                            removeItemButton: true,
                            searchEnabled: true,
                            placeholderValue: 'Select Categories',
                        });
                    },
                    error: function(xhr) {
                        console.error(xhr);
                    }
                });
            });
        });

        // $(document).ready(function() {
        //     var stateSelect = new Choices('#state', {
        //         removeItemButton: true,
        //         searchEnabled: true,
        //         placeholderValue: 'Select States',
        //     });

        //     var categorySelect = new Choices('#category_assign', {
        //         removeItemButton: true,
        //         searchEnabled: true,
        //         placeholderValue: 'Select Categories',
        //     });

        //     $('.state_assign').change(function() {
        //         $('#category_assign').prop('disabled', false);
        //         var statesGet = $('#state').val();

        //         $.ajax({
        //             url: '{{ route('autos.approach.CategoryCount') }}',
        //             type: 'GET',
        //             data: {
        //                 state: statesGet,
        //             },
        //             success: function(response) {
        //                 console.log('responseresponse', response);

        //                 var html =
        //                     `<select id="category_assign" name="category_assign[]" multiple
    //                                             class="form-control" required>
    //                                             <option value="">Select Category</option>
    //                                             </select>`;

        //                 var categories = {
        //                     'Auto Dealership': 'Auto',
        //                     'Automotive Repair Services': 'Automotive',
        //                     'New Car Dealer': 'New',
        //                     'Used Car Dealer': 'Used',
        //                     'Automobile Dealers Electric Cars': 'Automobile_Electric',
        //                     'Automobile Dealers-electric Cars': 'Automobile_Electric_Alt',
        //                     'Automobile Dealership': 'Automobile_Dealership',
        //                     'Automobile Dlrs Custom Designed Replica': 'Automobile_Custom_Replica',
        //                     'Automobile Dlrs-custom Designed Replica': 'Automobile_Custom_Replica_Alt',
        //                     'Automobile Sales & Service': 'Automobile_Sales_Service',
        //                     'Automobile Specialty': 'Automobile_Specialty',
        //                     'Automobilendealers-used Cars': 'Automobilendealers_Used',
        //                     'Automobiles': 'Automobiles',
        //                     'Automobiles Pick-up Trucks & Vans': 'Automobiles_Pickup_Vans',
        //                     'Automobiles, New And Used': 'Automobiles_New_Used',
        //                     'Automobiles-fleet Sales': 'Automobiles_Fleet_Sales',
        //                     'Automotive Dealers & Service Stations': 'Automotive_Dealers_Service',
        //                     'Four Wheel Drive Vehicles': 'Four_Wheel_Drive',
        //                     'Limousine-dealers': 'Limousine_Dealers',
        //                     'Motor Vehicle Dealers (new And Used)': 'Motor_Vehicle_Dealers_New_Used',
        //                     'New & Used Car Dlrs': 'New_Used_Car_Dlrs',
        //                     'New And Used Car Dealers': 'New_Used_Car_Dealers',
        //                     'New And Used Car Dealers, Nec': 'New_Used_Car_Dealers_Nec',
        //                     'New And Used Car Dealers; Nec': 'New_Used_Car_Dealers_SemiColon',
        //                     'New And Usedcar Dealers, Nec': 'New_Used_Car_Dealers_Nec_Alt'
        //                 };

        //                 $.each(categories, function(categoryName, responseKey) {
        //                     if (response[responseKey] > 0) {
        //                         html +=
        //                             `<option value="${categoryName}">${categoryName} (${response[responseKey]})</option>`;
        //                     }
        //                 });

        //                 $('#category_assign').html(html);

        //                 console.log($('#category_assign')
        //                     .html());
        //                 categorySelect.destroy();
        //                 categorySelect = new Choices('#category_assign', {
        //                     removeItemButton: true,
        //                     searchEnabled: true,
        //                     placeholderValue: 'Select Categories',
        //                 });
        //             },
        //             error: function(xhr) {
        //                 console.error(xhr);
        //             }
        //         });
        //     });
        // });
    </script>
    <!--==============================multiselect tag====================-->
    <script>
        $(function() {
            $('.status').hide();
            $('.status_update').change(function() {
                $('.status').hide();
                $('.' + $(this).val()).show();
            })
        })
        $('#reportmodal').on('show.bs.modal', function(e) {

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

                        //$('#success').html(data);
                        $('#modaldemo4').modal('show');
                        $('#reportmodal').modal('hide');

                    } else {
                        //$('#not_success').html(data);
                        $('#modaldemo5').modal('show');
                    }
                },
                error: function(e) {
                    $("#err").html(e).fadeIn();
                }
            });
        }));

        // $("#orderTaker").change(function() {
        //     // Get the selected user_id from the orderTaker dropdown
        //     var user_id = $('#orderTaker').val();

        //     // Make an AJAX request to get data based on the selected order taker
        //     $.ajax({
        //         url: '{{ route('edit.allowed.states') }}',
        //         type: 'GET',
        //         data: {
        //             'user_id': user_id,
        //         },
        //         success: function(data) {
        //             console.log('AJAX response data:', data);

        //             // Initialize selectedStates to an empty array
        //             var selectedStates = [];

        //             // Check if the state data exists and split it into an array
        //             if (data.state) {
        //                 selectedStates = data.state.split(',');
        //             }

        //             console.log('selectedStates:', selectedStates);

        //             // Clear existing selections in the #state dropdown
        //             var stateDropdown = document.getElementById('state');
        //             for (var i = 0; i < stateDropdown.options.length; i++) {
        //                 stateDropdown.options[i].selected = false;
        //             }

        //             // Update the #state dropdown with the selected values
        //             for (var i = 0; i < stateDropdown.options.length; i++) {
        //                 if (selectedStates.includes(stateDropdown.options[i].value)) {
        //                     stateDropdown.options[i].selected = true;
        //                 }
        //             }

        //             // Display the recordsAllowed value in the #recordsAllowed input field
        //             $('#recordsAllowed').val(data.recordsAllowed);
        //         },
        //         error: function(error) {
        //             // Handle the error response, log it to the console for debugging
        //             console.error('Error submitting the form:', error);
        //             // Optionally, you can display an error message or take other actions
        //         }
        //     });
        // });

        $(document).ready(function() {
            // Comma-separated string of states
            var statesString = '{{ $state }}';

            // Split the string into an array
            var statesArray = statesString.split(',');

            // Reference to the select element
            var $select = $('#myState');

            // Populate the select with options
            $.each(statesArray, function(index, value) {
                $select.append($('<option>', {
                    value: value,
                    text: value
                }));
            });
        });

        $(document).on("change", "#myState", function(e) {
            e.preventDefault();

            var myState = $("#myState").val();

            $.ajax({
                url: `{{ route('filter.usedAndNew.data') }}`,
                type: 'GET',
                data: {
                    'myState': myState,
                },
                success: function(data) {
                    $("#usedAndNewTableBody").html('');
                    var html = '';
                    $("#usedAndNewTableBody").html(data);

                },
                error: function(error) {
                    console.error('Error submitting the form:', error);
                }
            });
        });

        $(document).on("keyup", "#search", function(e) {
            e.preventDefault();

            var search = $("#search").val();
            // console.log('search', search);

            $.ajax({
                url: '{{ route('filter.usedAndNew.data') }}',
                type: 'GET',
                data: {
                    'search': search,
                },
                success: function(data) {
                    // console.log('data', data);
                    $("#table_data").html('');
                    $("#table_data").html(data);

                },
                error: function(error) {
                    console.error('Error submitting the form:', error);
                }
            });
        });
    </script>

    {{-- <script>
        $(document).ready(function() {
            // Function to handle AJAX calls based on filter criteria
            function fetchData() {
                var stateValue = $('#stateComp').val();
                // var searchValue = $('#searchComp').val();
                var searchValue = $('.searchComp').val();
                var emailValue = $('#emailComp').val();

                console.log('stateValue', stateValue);
                console.log('searchValue', searchValue);

                // Perform AJAX call with filter criteria
                $.ajax({
                    url: '{{ route('autos.approach.search') }}',
                    type: 'GET',
                    data: {
                        state: stateValue,
                        search: searchValue,
                        email: emailValue
                    },
                    success: function(response) {
                        // Update the UI with the fetched data
                        // Replace the following lines with your actual logic
                        console.log('Fetched data:', response);
                        $("#table_data").html('');
                        $("#table_data").html(response);
                        $('#table_data').on('click', '.pagination a', function(event) {
                            event.preventDefault();
                            // Perform your AJAX call for the clicked page number

                        });
                        $("#pagination-container").html(response.pagination);
                    },
                    error: function(error) {
                        console.error('Error:', error);
                        // Handle errors if needed
                    }
                });

                $('#table_data').on('click', '.pagination a', function(event) {
                    event.preventDefault();
                    var page = $(this).attr('href').split('page=')[1];
                    fetchData(page); // Trigger AJAX call for the clicked page number
                });

                // Initial AJAX call to fetch data for the first page
                fetchData(1);
            }

            // Listen for change event on #stateComp
            $('#stateComp').change(function() {
                fetchData(); // Trigger AJAX call on state change
            });

            // Listen for keyup event on #searchComp
            // $('#searchComp').keyup(function() {
            //     fetchData(); // Trigger AJAX call on keyup
            // });
            $('.searchComp').keyup(function() {
                fetchData(); // Trigger AJAX call on keyup
            });

            // Listen for keyup event on #searchComp
            $('#emailComp').change(function() {
                fetchData(); // Trigger AJAX call on keyup
            });
        });
    </script> --}}
    <script>
        // Function to handle AJAX calls based on filter criteria
        function fetchData(page,send_template = null,template = null,limit_data = null) {
            var stateValue = $('#stateComp').val();
            var categoryValue = $('#category').val();
            // var searchValue = $('#searchComp').val();
            var searchValue = $('.searchComp').val();
            var emailValue = $('#emailComp').val();
            var emailsSent = $('#emailsSent').val();
            var orderTakerSearch = $('#orderTakerSearch').val();

            // console.log('stateValue', stateValue);
            // console.log('searchValue', searchValue);

            // Perform AJAX call with filter criteria and page number
            $.ajax({
                url: '{{ route('autos.approach.search') }}' + '?page=' + page,
                type: 'GET',
                data: {
                    state: stateValue,
                    search: searchValue,
                    category: categoryValue,
                    email: emailValue,
                    orderTaker: orderTakerSearch,
                    emailsSent: emailsSent,
                    send_template : send_template,limit_data,
                    template : template,
                    type : 5,
                },
                success: function(response) {
                    // Update the UI with the fetched data
                    // console.log('Fetched data:', response);
                    $("#pagination-container").html(response.pagination);
                    $("#table_data").html(response);
                },
                error: function(error) {
                    console.error('Error:', error);
                    // Handle errors if needed
                }
            });
        }

        $(document).ready(function() {
            // Listen for change event on #stateComp
            $('#stateComp').change(function() {
                fetchData(1); // Trigger AJAX call on state change for the first page
            });

            // Listen for change event on #category
            $('#category').change(function() {
                fetchData(1); // Trigger AJAX call on state change for the first page
            });

            // Listen for keyup event on #searchComp
            // $('#searchComp').keyup(function() {
            //     fetchData(1); // Trigger AJAX call on keyup for the first page
            // });
            $('.searchComp').keyup(function() {
                fetchData(1); // Trigger AJAX call on keyup for the first page
            });

            // Listen for change event on #emailComp
            $('#emailComp').change(function() {
                fetchData(1); // Trigger AJAX call on change for the first page
            });

            // Listen for change event on #orderTakerSearch
            $('#orderTakerSearch').change(function() {
                fetchData(1); // Trigger AJAX call on change for the first page
            });

            // Listen for change event on #orderTakerSearch
            $('#emailsSent').on('change', function(event) {
                fetchData(1); // Trigger AJAX call on change for the first page
            });

            // Listen for click events on pagination links
            $('#table_data').on('click', '.pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                fetchData(page); // Trigger AJAX call for the clicked page number
            });

            $('#send_template').on('click', function(event) {
                var template = $('#template').val();
                var limit_data = $('#limit_data').val();
                fetchData(1,1,template,limit_data);
                alert('Email Will be Released after 5 minutes');
            });

            // Initial AJAX call to fetch data for the first page
            fetchData(1);
        });
    </script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        // Initialize global variables for startDate and endDate
        var startDate;
        var endDate;

        $(document).ready(function() {
            // Function to fetch data based on selected filters
            function fetchData(page) {
                var stateValue = $('#stateComp').val();
                var categoryValue = $('#category').val();
                var searchValue = $('.searchComp').val();
                var emailValue = $('#emailComp').val();
                var orderTakerSearch = $('#orderTakerSearch').val();

                // Format dates using Moment.js before sending them in the AJAX request
                var formattedStartDate = startDate.format('YYYY-MM-DD');
                var formattedEndDate = endDate.format('YYYY-MM-DD');

                $.ajax({
                    url: '{{ route('autos.approach.search') }}' + '?page=' + page,
                    type: 'GET',
                    data: {
                        state: stateValue,
                        search: searchValue,
                        category: categoryValue,
                        email: emailValue,
                        startDate: formattedStartDate,
                        endDate: formattedEndDate,
                        orderTaker: orderTakerSearch,
                    },
                    success: function(response) {
                        // Update pagination
                        // Delayed update after 2 seconds
                        $("#table_data").html('');
                        setTimeout(function() {
                            $("#pagination-container").html(response.pagination);
                            $("#table_data").html(response);
                        }, 1000); // 2000 milliseconds = 2 seconds
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            }

            // Initialize date range picker
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
            }, function(start, end, label) {
                startDate = start;
                endDate = end;
                // Fetch data when date range changes
                fetchData(1);
            });

            // Handle click event for pagination links
            $('#table_data').on('click', '.pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                fetchData(page);
            });

            // Trigger initial data fetch
            fetchData(1);
        });
    </script>
    <!--<script>
        -- >
        <
        !--$(document).ready(function() {
            -- >
            <
            !--$('#name, #address, #email').on('keyup', function() {
                -- >
                <
                !--
                var fieldName = $(this).attr('name');
                -- >
                <
                !--
                var fieldValue = $(this).val();
                -- >

                // Perform AJAX request to validate the field
                <
                !--$.ajax({
                    -- >
                    <
                    !--url: '{{ route('autosApproach.validation.check') }}',
                    -- >
                    method: 'POST', // Correct method to match your route
                    <
                    !--data: {
                        -- >
                        _token: '{{ csrf_token() }}', // Add CSRF token for POST requests
                        <
                        !--field_name: fieldName,
                        -- >
                        <
                        !--field_value: fieldValue-- >
                            <
                            !--
                    },
                    -- >
                    <
                    !--success: function(response) {
                            -- >
                            <
                            !--
                            if (response.valid) {
                                -- >
                                // Field is valid
                                <
                                !--$('#' + fieldName + '_error').hide();
                                -- >
                                <
                                !--
                            } else {
                                -- >
                                // Field is invalid
                                <
                                !--$('#' + fieldName + '_error').show();
                                -- >
                                <
                                !--
                            }-- >

                            // Check if any validation fails
                            <
                            !--
                            if ($('#address_error').is(-- >
                                    <
                                    !--':visible') || $('#email_error').is(':visible')) {
                                -- >
                                <
                                !--$('#submit_button').prop('disabled', -- >
                                    true); // Disable the submit button
                                <
                                !--
                            } else {
                                -- >
                                <
                                !--$('#submit_button').prop('disabled', -- >
                                    false); // Enable the submit button
                                <
                                !--
                            }-- >
                            <
                            !--
                        }-- >
                        <
                        !--
                });
                -- >
                <
                !--
            });
            -- >
            <
            !--
        });
        -- >
        <
        !--
    </script>-->
    <script>
        $(document).ready(function() {
            $('#name').keyup(function() {
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
                    url: '{{ route('autosApproach.validation.check') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        field_name: 'name',
                        field_value: inputValue
                    },
                    success: function(response) {
                        suggestionsList.empty();

                        // if (!response.valid) {
                        //     $('#name_error').show();
                        // } else {
                        //     $('#name_error').hide();
                        // }

                        $.each(response.related_names, function(index, suggestion) {
                            var listItem = $("<li>").text(suggestion).click(function() {
                                inputField.val(suggestion);
                                suggestionsList.css("display", "none");
                            });
                            suggestionsList.append(listItem);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", error);
                    }
                });
            }

            $('#address, #email, #phone').on('keyup', function() {
                var fieldName = $(this).attr('name');
                var fieldValue = $(this).val();

                $.ajax({
                    url: '{{ route('autosApproach.validation.check') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        field_name: fieldName,
                        field_value: fieldValue
                    },
                    success: function(response) {
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
        $(document).ready(function() {
            $('#withHistory').change(function() {
                var withHistory = $('#withHistory').val();

                // Perform AJAX request to validate the field
                $.ajax({
                    url: '{{ route('autos.approach.search') }}',
                    type: 'GET',
                    data: {
                        withHistory: withHistory,
                    },
                    success: function(response) {
                        // Update pagination
                        $("#table_data").html('');
                        setTimeout(function() {
                            $("#pagination-container").html(response.pagination);
                            $("#table_data").html(response);
                        }, 1000); // 2000 milliseconds = 2 seconds
                    },
                });
            });
        });

        // $('.state_assign').change(function() {
        //     $('#category_assign').prop('disabled', false);
        //     var statesGet = $('#state').val();
        //     $.ajax({
        //         url: '{{ route('autos.approach.CategoryCount') }}',
        //         type: 'GET',
        //         data: {
        //             state: statesGet,
        //         },
        //         success: function(response) {
        //             var html = `<option value="">Select Category</option>`;

        //             // List of categories and their respective key in the response
        //             var categories = {
        //                 "Auto Dealership": "Auto",
        //                 "Automobile Dealers Electric Cars": "Automobile_Electric",
        //                 "Automobile Dealers-electric Cars": "Automobile_Electric_Alt",
        //                 "Automobile Dealership": "Automobile_Dealership",
        //                 "Automobile Dlrs Custom Designed Replica": "Automobile_Custom_Replica",
        //                 "Automobile Dlrs-custom Designed Replica": "Automobile_Custom_Replica_Alt",
        //                 "Automobile Sales & Service": "Automobile_Sales_Service",
        //                 "Automobile Specialty": "Automobile_Specialty",
        //                 "Automobile-specialty": "Automobile_Specialty_Alt",
        //                 "Automobilendealers-used Cars": "Automobilendealers_Used",
        //                 "Automobiles": "Automobiles",
        //                 "Automobiles Pick-up Trucks & Vans": "Automobiles_Pickup_Vans",
        //                 "Automobiles, New And Used": "Automobiles_New_Used",
        //                 "Automobiles-fleet Sales": "Automobiles_Fleet_Sales",
        //                 "Automotive Dealers & Service Stations": "Automotive_Dealers_Service",
        //                 "Automotive Repair Services": "Automotive_Repair",
        //                 "Four Wheel Drive Vehicles": "Four_Wheel_Drive",
        //                 "Limousine-dealers": "Limousine_Dealers",
        //                 "Motor Vehicle Dealers (new And Used)": "Motor_Vehicle_Dealers_New_Used",
        //                 "New & Used Car Dlrs": "New_Used_Car_Dlrs",
        //                 "New And Used Car Dealers": "New_Used_Car_Dealers",
        //                 "New And Used Car Dealers, Nec": "New_Used_Car_Dealers_Nec",
        //                 "New And Used Car Dealers; Nec": "New_Used_Car_Dealers_SemiColon",
        //                 "New And Usedcar Dealers, Nec": "New_Used_Car_Dealers_Nec_Alt"
        //             };

        //             // Loop through categories and add to HTML if count is greater than 0
        //             $.each(categories, function(categoryName, responseKey) {
        //                 if (response[responseKey] > 0) {
        //                     html +=
        //                         `<option value="${categoryName}">${categoryName} (${response[responseKey]})</option>`;
        //                 }
        //             });

        //             $('#category_assign').html(html);
        //         },
        //     });
        // });

        // $('.state_assign').change(function() {
        //     $('#category_assign').prop('disabled', false);
        //     var statesGet = $('#state').val();
        //     $.ajax({
        //         url: '{{ route('autos.approach.CategoryCount') }}',
        //         type: 'GET',
        //         data: {
        //             state: statesGet,
        //         },
        //         success: function(response) {
        //             var html = `<option value="">Select Category</option>
    //                 <option value="Auto Dealership">Auto Dealership (${response['Auto']})</option>
    //                 <option value="Automotive Repair Services">Automotive Repair Services (${response['Automotive']})</option>
    //                 <option value="New Car Dealer">New Car Dealer (${response['New']})</option>
    //                 <option value="Used Car Dealer">Used Car Dealer (${response['Used']})</option>`;

        //             $('#category_assign').html(html);


        //             // $("#table_data").html('');
        //             // setTimeout(function() {
        //             //     $("#pagination-container").html(response.pagination);
        //             //     $("#table_data").html(response);
        //             // }, 1000); // 2000 milliseconds = 2 seconds
        //         },
        //     });
        // });
    </script>
    <script>
        $(document).on('click', '.send-email', function(event) {
            event.preventDefault();
            var email = $(this).find('.Email-Address').val();

            console.log('emailemail', email);

            $.ajax({
                url: '{{ route('send.user.mail') }}',
                type: 'GET',
                data: {
                    email: email,
                },
                success: function(response) {
                    if (response.message) {
                        alert(response.message);
                    } else if (response.error) {
                        alert(response.error);
                    } else {
                        alert('Email sent');
                    }
                },
                error: function(xhr, status, error) {
                    // Parse the error message from the server
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ?
                        xhr.responseJSON.error :
                        'An error occurred while sending the email.';
                    alert(errorMessage);
                }
            });
        });

        $(document).on('click', '.view_email_history', function(event) {
            event.preventDefault();
            var email = $(this).find('.History-Email-Address').val();

            $.ajax({
                url: '{{ route('get.email.history') }}',
                type: 'GET',
                data: {
                    'email': email,
                },
                success: function(data) {
                    console.log('datas', data);
                    $(".email-history-content").html('');
                    html = "";
                    $.each(data, function(index, val) {
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

                        // Check if val['template'] exists and has a title
                        var templateTitle = val['template'] && val['template']['title']
                            ? val['template']['title']
                            : "No title available";

                        // Safely build HTML
                        html += "<h6>Sender " + (val['user'] && val['user']['name'] ? val['user']['name'] : "Unknown") + "</h6>";
                        html += "<h6>Template title: " + templateTitle + "</h6>";
                        html += "<strong class='mf-date'><i class='fa fa-clock-o'></i>" +
                            formattedDate + "</strong> <hr>";
                    });
                    $(".email-history-content").html(html);
                },

                error: function(error) {
                    console.error('Error submitting the form:', error);
                }
            });
        });
    </script>
@endsection
