@extends('layouts.innerpages')

@section('template_title')
    {{ ucfirst(trim('Add Role', '/')) }}
@endsection
@section('content')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    @include('partials.mainsite_pages.return_function')
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
            color: rgb(0 0 0);
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .table-bordered,
        .text-wrap table,
        .table-bordered th,
        .text-wrap table th,
        .table-bordered td,
        .text-wrap table td {
            border: 1px solid rgb(0 0 0);
        }

        .table>tbody>tr>td,
        .table>thead>tr>th {
            font-weight: 500;
            -webkit-transition: all .3s ease;
            font-size: 18px;
            color: rgb(0 0 0);
            text-align: center;
        }

        /* Style the tab */
        .tab {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
        }

        /* Style the buttons that are used to open the tab content */
        .tab button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
        }

        /* Change background color of buttons on hover */
        .tab button:hover {
            background-color: #ddd;
        }

        /* Create an active/current tablink class */
        .tab button.active {
            background-color: #ccc;
        }

        /* Style the tab content */
        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
        }

        .tabcontent {
            animation: fadeEffect 1s;
            /* Fading effect takes 1 second */
        }

        /* Go from zero to full opacity */
        @keyframes fadeEffect {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
    <!--/app header--> <!--Page header-->
    <div class="page-header">
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Add New Role</b></h1>
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
    <div class="row">
        <div class="col-12">
            <!--div-->
            <div class="card">
                <div class="card-header">
                    Add New Role
                </div>
                <form action="{{ url('/role/store') }}" method="POST">
                    <div class="card-body">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label" for="role_name">Role Name</label>
                                    <input type="text" class="form-control" id="role_name" name="role_name"
                                        placeholder="Enter Role Name" value="{{ old('role_name') }}" />
                                    @if ($errors->has('role_name'))
                                        <div class="text-danger">{{ $errors->first('role_name') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Status</label>
                                    <select id="status" name="status" class="form-control">
                                        <option value="" selected disabled>SELECT</option>
                                        <option value="1" @if (old('status') == 1) selected @endif>Active
                                        </option>
                                        <option value="0" @if (old('status') == 0) selected @endif>Disabled
                                        </option>
                                    </select>
                                    @if ($errors->has('status'))
                                        <div class="text-danger">{{ $errors->first('status') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">Role Access</label>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#exampleModal1">Phone Quotes</button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#exampleModal2">Website Quotes</button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#exampleModal20">Testing Quotes</button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#exampleModa4">Panel Type 4</button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#exampleModa5">Panel Type 5</button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#exampleModa6">Panel Type 6</button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#exampleModal3">Show Data</button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#exampleModal4">Shipment Status</button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#exampleModal5">Profile Access</button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#exampleModal6">Action Access</button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#exampleModal7">Employee Report</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel1" aria-hidden="true">
                            <div class="modal-dialog" role="document" style="max-width: 60%;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Role Access (Phone Qoutes)</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <input type="checkbox" id="emp_access_ship_all1"
                                                            class="emp_access_ship_all"><label class="ml-2"
                                                            for="emp_access_ship_all1">All Options</label>
                                                    </div>
                                                    <br>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone0" value="0"><label class="ml-2"
                                                            for="emp_access_phone0">New</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone1" value="1"><label class="ml-2"
                                                            for="emp_access_phone1">Interested</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone2" value="2"><label class="ml-2"
                                                            for="emp_access_phone2">Follow More</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone3" value="3"><label class="ml-2"
                                                            for="emp_access_phone3">Asking Low</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone4" value="4"><label class="ml-2"
                                                            for="emp_access_phone4">Not Interested</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone5" value="5"><label class="ml-2"
                                                            for="emp_access_phone5">No Response</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone6" value="6"><label class="ml-2"
                                                            for="emp_access_phone6">Time Quote</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone7" value="7"><label class="ml-2"
                                                            for="emp_access_phone7">Paymen tMissing</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone8" value="8"><label class="ml-2"
                                                            for="emp_access_phone8">Booked</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone66" value="66"><label class="ml-2"
                                                            for="emp_access_phone66">Double Booking</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone9" value="9"><label class="ml-2"
                                                            for="emp_access_phone9">Listed</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone10" value="10"><label class="ml-2"
                                                            for="emp_access_phone10">Schedule</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone11" value="11"><label class="ml-2"
                                                            for="emp_access_phone11">Pickup</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone12" value="12"><label class="ml-2"
                                                            for="emp_access_phone12">Delivered</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone13" value="13"><label class="ml-2"
                                                            for="emp_access_phone13">Completed</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone14" value="14"><label class="ml-2"
                                                            for="emp_access_phone14">Cancel</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone15" value="15"><label class="ml-2"
                                                            for="emp_access_phone15">Deleted</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone16" value="16"><label class="ml-2"
                                                            for="emp_access_phone16">Owes Money</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone17" value="17"><label class="ml-2"
                                                            for="emp_access_phone17">Carrier Update</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone18" value="18"><label class="ml-2"
                                                            for="emp_access_phone18">Car Quote</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone19" value="19"><label class="ml-2"
                                                            for="emp_access_phone19">Heavy Quote</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone20" value="20"><label class="ml-2"
                                                            for="emp_access_phone20">Add/Edit Employee</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone21" value="21"><label class="ml-2"
                                                            for="emp_access_phone21">Admin Issues</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone22" value="22"><label class="ml-2"
                                                            for="emp_access_phone22">Old Quotes</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone23" value="23"><label class="ml-2"
                                                            for="emp_access_phone23">Transportation Invoice</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone73" value="73"><label class="ml-2"
                                                            for="emp_access_phone73">Roro Invoice</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone24" value="24"><label class="ml-2"
                                                            for="emp_access_phone24">Carriers</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone25" value="25"><label class="ml-2"
                                                            for="emp_access_phone25">View Emails</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone26" value="26"><label class="ml-2"
                                                            for="emp_access_phone26">Show Data</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone27" value="27"><label class="ml-2"
                                                            for="emp_access_phone27">Sheets</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone28" value="28"><label class="ml-2"
                                                            for="emp_access_phone28">On Approval</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone29" value="29"><label class="ml-2"
                                                            for="emp_access_phone29">Delivered Approval</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone30" value="30"><label class="ml-2"
                                                            for="emp_access_phone30">Approaching</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone31" value="31"><label class="ml-2"
                                                            for="emp_access_phone31">Payment System</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone32" value="32"><label class="ml-2"
                                                            for="emp_access_phone32">Employee Reports</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone33" value="33"><label class="ml-2"
                                                            for="emp_access_phone33">Price Per Mile</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone34" value="34"><label class="ml-2"
                                                            for="emp_access_phone34">Filtered Data</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone35" value="35"><label class="ml-2"
                                                            for="emp_access_phone35">Group</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone36" value="36"><label class="ml-2"
                                                            for="emp_access_phone36">Questions/Answers</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone37" value="37"><label class="ml-2"
                                                            for="emp_access_phone37">New Show Data</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone38" value="38"><label class="ml-2"
                                                            for="emp_access_phone38">Customer</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_phone[]" id="emp_access_phone39" value="39"><label class="ml-2" for="emp_access_phone39">Messages Center</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_phone[]" id="emp_access_phone40" value="40"><label class="ml-2" for="emp_access_phone40">Call Logs Center</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone41" value="41"><label class="ml-2"
                                                            for="emp_access_phone41">Update Phone Digits</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone42" value="42"><label class="ml-2"
                                                            for="emp_access_phone42">Show Customer Number</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_phone[]" id="emp_access_phone67" value="67"><label class="ml-2" for="emp_access_phone67">Customer Full Number</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone60" value="60"><label class="ml-2"
                                                            for="emp_access_phone60">Show Driver Number</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_phone[]" id="emp_access_phone61" value="61"><label class="ml-2" for="emp_access_phone61">Driver Full Number</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone43" value="43"><label class="ml-2"
                                                            for="emp_access_phone43">Flag Employees</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone44" value="44"><label class="ml-2"
                                                            for="emp_access_phone44">Transfer Quotes</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone46" value="46"><label class="ml-2"
                                                            for="emp_access_phone46">Revenue</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone47" value="47"><label class="ml-2"
                                                            for="emp_access_phone47">Coupons</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone48" value="48"><label class="ml-2"
                                                            for="emp_access_phone48">Website Links</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone49" value="49"><label class="ml-2"
                                                            for="emp_access_phone49">Feedbacks</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_phone[]" id="emp_access_phone45" value="45"><label class="ml-2" for="emp_access_phone45">Add Feedback</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone50" value="50"><label class="ml-2"
                                                            for="emp_access_phone50">Managers Group</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone51" value="51"><label class="ml-2"
                                                            for="emp_access_phone51">Last Activity</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone52" value="52"><label class="ml-2"
                                                            for="emp_access_phone52">Login Ip Address</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone53" value="53"><label class="ml-2"
                                                            for="emp_access_phone53">Storage</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone54" value="54"><label class="ml-2"
                                                            for="emp_access_phone54">Shipment Status</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone55" value="55"><label class="ml-2"
                                                            for="emp_access_phone55">Dispatch Report</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone56" value="56"><label class="ml-2"
                                                            for="emp_access_phone56">Employee Rating</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone57" value="57"><label class="ml-2"
                                                            for="emp_access_phone57">Performance Report</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_phone[]" id="emp_access_phone58" value="58"><label class="ml-2" for="emp_access_phone58">View Sheets</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_phone[]" id="emp_access_phone59" value="59"><label class="ml-2" for="emp_access_phone59">View Cancel History</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone62" value="62"><label class="ml-2"
                                                            for="emp_access_phone62">QA Report</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone63" value="63"><label class="ml-2"
                                                            for="emp_access_phone63">Roles</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone64" value="64"><label class="ml-2"
                                                            for="emp_access_phone64">Update QA History</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone65" value="65"><label class="ml-2"
                                                            for="emp_access_phone65">View QA History</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone68" value="68"><label class="ml-2"
                                                            for="emp_access_phone68">Approaching Number Phone</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone69" value="69"><label class="ml-2"
                                                            for="emp_access_phone69">Approaching Number Website</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone70" value="70"><label class="ml-2"
                                                            for="emp_access_phone70">Approaching Search Filter</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone71" value="71"><label class="ml-2"
                                                            for="emp_access_phone71">Booker Name</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone72" value="72"><label class="ml-2"
                                                            for="emp_access_phone72">Offer Price</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone74" value="74"><label class="ml-2"
                                                            for="emp_access_phone74">Achievement Sheet</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone75" value="75"><label class="ml-2"
                                                            for="emp_access_phone75">Port Price</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone76" value="76"><label class="ml-2"
                                                            for="emp_access_phone76">Assign To Dispatcher</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone77" value="77"><label class="ml-2"
                                                            for="emp_access_phone77">Move OnApprovalCancel To
                                                            Cancel</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_phone[]" id="emp_access_phone78" value="78"><label class="ml-2" for="emp_access_phone78">Payment Confirmation</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone79" value="79"><label class="ml-2"
                                                            for="emp_access_phone79">Profile</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_phone[]" id="emp_access_phone80" value="80"><label class="ml-2" for="emp_access_phone80">Port Sheet</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_phone[]" id="emp_access_phone81" value="81"><label class="ml-2" for="emp_access_phone81">Payment Missing Sheet</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_phone[]" id="emp_access_phone82" value="82"><label class="ml-2" for="emp_access_phone82">Dispatch Sheet</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_phone[]" id="emp_access_phone83" value="83"><label class="ml-2" for="emp_access_phone83">Pickup Sheet</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_phone[]" id="emp_access_phone84" value="84"><label class="ml-2" for="emp_access_phone84">Delivered Sheet</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone85" value="85"><label class="ml-2"
                                                            for="emp_access_phone85">Commission Range</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone86" value="86"><label class="ml-2"
                                                            for="emp_access_phone86">Employee Profile Filter</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone87" value="87"><label class="ml-2"
                                                            for="emp_access_phone87">Break Time</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone88" value="88"><label class="ml-2"
                                                            for="emp_access_phone88">Freeze Time History</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone89" value="89"><label class="ml-2"
                                                            for="emp_access_phone89">Payment System Advance Filter</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone90" value="90"><label class="ml-2"
                                                            for="emp_access_phone90">Demand Order</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_phone[]"
                                                            id="emp_access_phone91" value="91"><label class="ml-2"
                                                            for="emp_access_phone91">Sell Invoice</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel2" aria-hidden="true">
                            <div class="modal-dialog" role="document" style="max-width: 60%;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Role Access (Webiste Qoutes)</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <input type="checkbox" id="emp_access_ship_all2"
                                                            class="emp_access_ship_all"><label class="ml-2"
                                                            for="emp_access_ship_all2">All Options</label>
                                                    </div>
                                                    <br>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web0" value="0"><label class="ml-2"
                                                            for="emp_access_web0">New</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web1" value="1"><label class="ml-2"
                                                            for="emp_access_web1">Interested</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web2" value="2"><label class="ml-2"
                                                            for="emp_access_web2">Follow More</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web3" value="3"><label class="ml-2"
                                                            for="emp_access_web3">Asking Low</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web4" value="4"><label class="ml-2"
                                                            for="emp_access_web4">Not Interested</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web5" value="5"><label class="ml-2"
                                                            for="emp_access_web5">No Response</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web6" value="6"><label class="ml-2"
                                                            for="emp_access_web6">Time Quote</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web7" value="7"><label class="ml-2"
                                                            for="emp_access_web7">Paymen tMissing</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web8" value="8"><label class="ml-2"
                                                            for="emp_access_web8">Booked</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web66" value="66"><label class="ml-2"
                                                            for="emp_access_web66">Double Booking</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web9" value="9"><label class="ml-2"
                                                            for="emp_access_web9">Listed</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web10" value="10"><label class="ml-2"
                                                            for="emp_access_web10">Schedule</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web11" value="11"><label class="ml-2"
                                                            for="emp_access_web11">Pickup</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web12" value="12"><label class="ml-2"
                                                            for="emp_access_web12">Delivered</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web13" value="13"><label class="ml-2"
                                                            for="emp_access_web13">Completed</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web14" value="14"><label class="ml-2"
                                                            for="emp_access_web14">Cancel</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web15" value="15"><label class="ml-2"
                                                            for="emp_access_web15">Deleted</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web16" value="16"><label class="ml-2"
                                                            for="emp_access_web16">Owes Money</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web17" value="17"><label class="ml-2"
                                                            for="emp_access_web17">Carrier Update</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web18" value="18"><label class="ml-2"
                                                            for="emp_access_web18">Car Quote</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web19" value="19"><label class="ml-2"
                                                            for="emp_access_web19">Heavy Quote</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web20" value="20"><label class="ml-2"
                                                            for="emp_access_web20">Add/Edit Employee</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web21" value="21"><label class="ml-2"
                                                            for="emp_access_web21">Admin Issues</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web22" value="22"><label class="ml-2"
                                                            for="emp_access_web22">Old Quotes</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web23" value="23"><label class="ml-2"
                                                            for="emp_access_web23">Transportation Invoice</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web73" value="73"><label class="ml-2"
                                                            for="emp_access_web73">Roro Invoice</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web24" value="24"><label class="ml-2"
                                                            for="emp_access_web24">Carriers</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web25" value="25"><label class="ml-2"
                                                            for="emp_access_web25">View Emails</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web26" value="26"><label class="ml-2"
                                                            for="emp_access_web26">Show Data</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web27" value="27"><label class="ml-2"
                                                            for="emp_access_web27">Sheets</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web28" value="28"><label class="ml-2"
                                                            for="emp_access_web28">On Approval</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web29" value="29"><label class="ml-2"
                                                            for="emp_access_web29">Delivered Approval</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web30" value="30"><label class="ml-2"
                                                            for="emp_access_web30">Approaching</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web31" value="31"><label class="ml-2"
                                                            for="emp_access_web31">Payment System</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web32" value="32"><label class="ml-2"
                                                            for="emp_access_web32">Employee Reports</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web33" value="33"><label class="ml-2"
                                                            for="emp_access_web33">Price Per Mile</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web34" value="34"><label class="ml-2"
                                                            for="emp_access_web34">Filtered Data</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web35" value="35"><label class="ml-2"
                                                            for="emp_access_web35">Group</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web36" value="36"><label class="ml-2"
                                                            for="emp_access_web36">Questions/Answers</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web37" value="37"><label class="ml-2"
                                                            for="emp_access_web37">New Show Data</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web38" value="38"><label class="ml-2"
                                                            for="emp_access_web38">Customer</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_web[]" id="emp_access_web39" value="39"><label class="ml-2" for="emp_access_web39">Messages Center</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_web[]" id="emp_access_web40" value="40"><label class="ml-2" for="emp_access_web40">Call Logs Center</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web41" value="41"><label class="ml-2"
                                                            for="emp_access_web41">Update Phone Digits</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web42" value="42"><label class="ml-2"
                                                            for="emp_access_web42">Show Customer Number</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_web[]" id="emp_access_web67" value="67"><label class="ml-2" for="emp_access_web67">Customer Full Number</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web60" value="60"><label class="ml-2"
                                                            for="emp_access_web60">Show Driver Number</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_web[]" id="emp_access_web61" value="61"><label class="ml-2" for="emp_access_web61">Driver Full Number</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web43" value="43"><label class="ml-2"
                                                            for="emp_access_web43">Flag Employees</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web44" value="44"><label class="ml-2"
                                                            for="emp_access_web44">Transfer Quotes</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web46" value="46"><label class="ml-2"
                                                            for="emp_access_web46">Revenue</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web47" value="47"><label class="ml-2"
                                                            for="emp_access_web47">Coupons</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web48" value="48"><label class="ml-2"
                                                            for="emp_access_web48">Website Links</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web49" value="49"><label class="ml-2"
                                                            for="emp_access_web49">Feedbacks</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_web[]" id="emp_access_web45" value="45"><label class="ml-2" for="emp_access_web45">Add Feedback</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web50" value="50"><label class="ml-2"
                                                            for="emp_access_web50">Managers Group</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web51" value="51"><label class="ml-2"
                                                            for="emp_access_web51">Last Activity</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web52" value="52"><label class="ml-2"
                                                            for="emp_access_web52">Login Ip Address</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web53" value="53"><label class="ml-2"
                                                            for="emp_access_web53">Storage</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web54" value="54"><label class="ml-2"
                                                            for="emp_access_web54">Shipment Status</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web55" value="55"><label class="ml-2"
                                                            for="emp_access_web55">Dispatch Report</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web56" value="56"><label class="ml-2"
                                                            for="emp_access_web56">Employee Rating</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web57" value="57"><label class="ml-2"
                                                            for="emp_access_web57">Performance Report</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_web[]" id="emp_access_web58" value="58"><label class="ml-2" for="emp_access_web58">View Sheets</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_web[]" id="emp_access_web59" value="59"><label class="ml-2" for="emp_access_web59">View Cancel History</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web62" value="62"><label class="ml-2"
                                                            for="emp_access_web62">QA Report</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web63" value="63"><label class="ml-2"
                                                            for="emp_access_web63">Roles</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web64" value="64"><label class="ml-2"
                                                            for="emp_access_web64">Update QA History</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web65" value="65"><label class="ml-2"
                                                            for="emp_access_web65">View QA History</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web68" value="68"><label class="ml-2"
                                                            for="emp_access_web68">Approaching Number Phone</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web69" value="69"><label class="ml-2"
                                                            for="emp_access_web69">Approaching Number Website</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web70" value="70"><label class="ml-2"
                                                            for="emp_access_web70">Approaching Search Filter</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web71" value="71"><label class="ml-2"
                                                            for="emp_access_web71">Booked Name</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web72" value="72"><label class="ml-2"
                                                            for="emp_access_web72">Offer Price</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web74" value="74"><label class="ml-2"
                                                            for="emp_access_web74">Achievement Sheet</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web75" value="75"><label class="ml-2"
                                                            for="emp_access_web75">Port Price</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web76" value="76"><label class="ml-2"
                                                            for="emp_access_web76">Assign To Dispatcher</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web77" value="77"><label class="ml-2"
                                                            for="emp_access_web77">Move OnApprovalCancel To Cancel</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_web[]" id="emp_access_web78" value="78"><label class="ml-2" for="emp_access_web78">Payment Confirmation</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web79" value="79"><label class="ml-2"
                                                            for="emp_access_web79">Profile</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_web[]" id="emp_access_web80" value="80"><label class="ml-2" for="emp_access_web80">Port Sheet</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_web[]" id="emp_access_web81" value="81"><label class="ml-2" for="emp_access_web81">Payment Missing Sheet</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_web[]" id="emp_access_web82" value="82"><label class="ml-2" for="emp_access_web82">Dispatch Sheet</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_web[]" id="emp_access_web83" value="83"><label class="ml-2" for="emp_access_web83">Pickup Sheet</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_web[]" id="emp_access_web84" value="84"><label class="ml-2" for="emp_access_web84">Delivered Sheet</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web85" value="85"><label class="ml-2"
                                                            for="emp_access_web85">Commission Range</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web86" value="86"><label class="ml-2"
                                                            for="emp_access_web86">Employee Profile Filter</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web87" value="87"><label class="ml-2"
                                                            for="emp_access_web87">Break Time</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web88" value="88"><label class="ml-2"
                                                            for="emp_access_web88">Freeze Time History</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web89" value="89"><label class="ml-2"
                                                            for="emp_access_web89">Payment System Advance Filter</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web90" value="90"><label class="ml-2"
                                                            for="emp_access_web90">Demand Order</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web91" value="91"><label class="ml-2"
                                                            for="emp_access_web91">Sell Invoice</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="exampleModal20" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel20" aria-hidden="true">
                            <div class="modal-dialog" role="document" style="max-width: 60%;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel20">Role Access (Webiste Qoutes)
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <input type="checkbox" id="emp_access_ship_all20"
                                                            class="emp_access_ship_all"><label class="ml-2"
                                                            for="emp_access_ship_all20">All Options</label>
                                                    </div>
                                                    <br>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test0" value="0"><label class="ml-2"
                                                            for="emp_access_test0">New</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test1" value="1"><label class="ml-2"
                                                            for="emp_access_test1">Interested</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test2" value="2"><label class="ml-2"
                                                            for="emp_access_test2">Follow More</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test3" value="3"><label class="ml-2"
                                                            for="emp_access_test3">Asking Low</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test4" value="4"><label class="ml-2"
                                                            for="emp_access_test4">Not Interested</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test5" value="5"><label class="ml-2"
                                                            for="emp_access_test5">No Response</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test6" value="6"><label class="ml-2"
                                                            for="emp_access_test6">Time Quote</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test7" value="7"><label class="ml-2"
                                                            for="emp_access_test7">Paymen tMissing</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test8" value="8"><label class="ml-2"
                                                            for="emp_access_test8">Booked</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test66" value="66"><label
                                                            class="ml-2" for="emp_access_test66">Double
                                                            Booking</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test9" value="9"><label class="ml-2"
                                                            for="emp_access_test9">Listed</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test10" value="10"><label
                                                            class="ml-2" for="emp_access_test10">Schedule</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test11" value="11"><label
                                                            class="ml-2" for="emp_access_test11">Pickup</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test12" value="12"><label
                                                            class="ml-2" for="emp_access_test12">Delivered</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test13" value="13"><label
                                                            class="ml-2" for="emp_access_test13">Completed</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test14" value="14"><label
                                                            class="ml-2" for="emp_access_test14">Cancel</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test15" value="15"><label
                                                            class="ml-2" for="emp_access_test15">Deleted</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test16" value="16"><label
                                                            class="ml-2" for="emp_access_test16">Owes Money</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test17" value="17"><label
                                                            class="ml-2" for="emp_access_test17">Carrier
                                                            Update</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test18" value="18"><label
                                                            class="ml-2" for="emp_access_test18">Car Quote</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test19" value="19"><label
                                                            class="ml-2" for="emp_access_test19">Heavy Quote</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test20" value="20"><label
                                                            class="ml-2" for="emp_access_test20">Add/Edit
                                                            Employee</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test21" value="21"><label
                                                            class="ml-2" for="emp_access_test21">Admin Issues</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test22" value="22"><label
                                                            class="ml-2" for="emp_access_test22">Old Quotes</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test23" value="23"><label
                                                            class="ml-2" for="emp_access_test23">Transportation
                                                            Invoice</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test73" value="73"><label
                                                            class="ml-2" for="emp_access_test73">Roro Invoice</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test24" value="24"><label
                                                            class="ml-2" for="emp_access_test24">Carriers</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test25" value="25"><label
                                                            class="ml-2" for="emp_access_test25">View Emails</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test26" value="26"><label
                                                            class="ml-2" for="emp_access_test26">Show Data</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test27" value="27"><label
                                                            class="ml-2" for="emp_access_test27">Sheets</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test28" value="28"><label
                                                            class="ml-2" for="emp_access_test28">On Approval</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test29" value="29"><label
                                                            class="ml-2" for="emp_access_test29">Delivered
                                                            Approval</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test30" value="30"><label
                                                            class="ml-2" for="emp_access_test30">Approaching</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test31" value="31"><label
                                                            class="ml-2" for="emp_access_test31">Payment
                                                            System</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test32" value="32"><label
                                                            class="ml-2" for="emp_access_test32">Employee
                                                            Reports</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test33" value="33"><label
                                                            class="ml-2" for="emp_access_test33">Price Per
                                                            Mile</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test34" value="34"><label
                                                            class="ml-2" for="emp_access_test34">Filtered Data</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test35" value="35"><label
                                                            class="ml-2" for="emp_access_test35">Group</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test36" value="36"><label
                                                            class="ml-2"
                                                            for="emp_access_test36">Questions/Answers</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test37" value="37"><label
                                                            class="ml-2" for="emp_access_test37">New Show Data</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test38" value="38"><label
                                                            class="ml-2" for="emp_access_test38">Customer</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_test[]" id="emp_access_test39" value="39"><label class="ml-2" for="emp_access_test39">Messages Center</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_test[]" id="emp_access_test40" value="40"><label class="ml-2" for="emp_access_test40">Call Logs Center</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test41" value="41"><label
                                                            class="ml-2" for="emp_access_test41">Update Phone
                                                            Digits</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test42" value="42"><label
                                                            class="ml-2" for="emp_access_test42">Show Customer
                                                            Number</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_test[]" id="emp_access_test67" value="67"><label class="ml-2" for="emp_access_test67">Customer Full Number</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test60" value="60"><label
                                                            class="ml-2" for="emp_access_test60">Show Driver
                                                            Number</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_test[]" id="emp_access_test61" value="61"><label class="ml-2" for="emp_access_test61">Driver Full Number</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test43" value="43"><label
                                                            class="ml-2" for="emp_access_test43">Flag
                                                            Employees</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test44" value="44"><label
                                                            class="ml-2" for="emp_access_test44">Transfer
                                                            Quotes</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test46" value="46"><label
                                                            class="ml-2" for="emp_access_test46">Revenue</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test47" value="47"><label
                                                            class="ml-2" for="emp_access_test47">Coupons</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test48" value="48"><label
                                                            class="ml-2" for="emp_access_test48">Website Links</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test49" value="49"><label
                                                            class="ml-2" for="emp_access_test49">Feedbacks</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_test[]" id="emp_access_test45" value="45"><label class="ml-2" for="emp_access_test45">Add Feedback</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test50" value="50"><label
                                                            class="ml-2" for="emp_access_test50">Managers
                                                            Group</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test51" value="51"><label
                                                            class="ml-2" for="emp_access_test51">Last Activity</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test52" value="52"><label
                                                            class="ml-2" for="emp_access_test52">Login Ip
                                                            Address</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test53" value="53"><label
                                                            class="ml-2" for="emp_access_test53">Storage</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test54" value="54"><label
                                                            class="ml-2" for="emp_access_test54">Shipment
                                                            Status</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test55" value="55"><label
                                                            class="ml-2" for="emp_access_test55">Dispatch
                                                            Report</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test56" value="56"><label
                                                            class="ml-2" for="emp_access_test56">Employee
                                                            Rating</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test57" value="57"><label
                                                            class="ml-2" for="emp_access_test57">Performance
                                                            Report</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_test[]" id="emp_access_test58" value="58"><label class="ml-2" for="emp_access_test58">View Sheets</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_test[]" id="emp_access_test59" value="59"><label class="ml-2" for="emp_access_test59">View Cancel History</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test62" value="62"><label
                                                            class="ml-2" for="emp_access_test62">QA Report</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test63" value="63"><label
                                                            class="ml-2" for="emp_access_test63">Roles</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test64" value="64"><label
                                                            class="ml-2" for="emp_access_test64">Update QA
                                                            History</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test65" value="65"><label
                                                            class="ml-2" for="emp_access_test65">View QA
                                                            History</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test68" value="68"><label
                                                            class="ml-2" for="emp_access_test68">Approaching Number
                                                            Phone</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test69" value="69"><label
                                                            class="ml-2" for="emp_access_test69">Approaching Number
                                                            Website</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test70" value="70"><label
                                                            class="ml-2" for="emp_access_test70">Approaching Search
                                                            Filter</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test71" value="71"><label
                                                            class="ml-2" for="emp_access_test71">Booked Name</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test72" value="72"><label
                                                            class="ml-2" for="emp_access_test72">Offer Price</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test74" value="74"><label
                                                            class="ml-2" for="emp_access_test74">Achievement
                                                            Sheet</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test75" value="75"><label
                                                            class="ml-2" for="emp_access_test75">Port Price</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test76" value="76"><label
                                                            class="ml-2" for="emp_access_test76">Assign To
                                                            Dispatcher</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test77" value="77"><label
                                                            class="ml-2" for="emp_access_test77">Move OnApprovalCancel
                                                            To Cancel</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_test[]" id="emp_access_test78" value="78"><label class="ml-2" for="emp_access_test78">Payment Confirmation</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test79" value="79"><label
                                                            class="ml-2" for="emp_access_test79">Profile</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_test[]" id="emp_access_test80" value="80"><label class="ml-2" for="emp_access_test80">Port Sheet</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_test[]" id="emp_access_test81" value="81"><label class="ml-2" for="emp_access_test81">Payment Missing Sheet</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_test[]" id="emp_access_test82" value="82"><label class="ml-2" for="emp_access_test82">Dispatch Sheet</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_test[]" id="emp_access_test83" value="83"><label class="ml-2" for="emp_access_test83">Pickup Sheet</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="emp_access_test[]" id="emp_access_test84" value="84"><label class="ml-2" for="emp_access_test84">Delivered Sheet</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test85" value="85"><label
                                                            class="ml-2" for="emp_access_test85">Commission
                                                            Range</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test86" value="86"><label
                                                            class="ml-2" for="emp_access_test86">Employee Profile
                                                            Filter</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test87" value="87"><label
                                                            class="ml-2" for="emp_access_test87">Break Time</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test88" value="88"><label
                                                            class="ml-2" for="emp_access_test88">Freeze Time
                                                            History</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test89" value="89"><label
                                                            class="ml-2" for="emp_access_test89">Payment System
                                                            Advance Filter</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test90" value="90"><label
                                                            class="ml-2" for="emp_access_test90">Demand Order</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test91" value="91"><label
                                                            class="ml-2" for="emp_access_test91">Sell Invoice</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="exampleModa4" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel20" aria-hidden="true">
                            <div class="modal-dialog" role="document" style="max-width: 60%;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel20">Role Access (Panel Type $)
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <input type="checkbox" id="emp_access_ship_all20"
                                                            class="emp_access_ship_all"><label class="ml-2"
                                                            for="emp_access_ship_all20">All Options</label>
                                                    </div>
                                                    <br>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_40" value="0"><label class="ml-2"
                                                            for="panel_type_40">New</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_41" value="1"><label class="ml-2"
                                                            for="panel_type_41">Interested</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_42" value="2"><label class="ml-2"
                                                            for="panel_type_42">Follow More</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_43" value="3"><label class="ml-2"
                                                            for="panel_type_43">Asking Low</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_44" value="4"><label class="ml-2"
                                                            for="panel_type_44">Not Interested</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_45" value="5"><label class="ml-2"
                                                            for="panel_type_45">No Response</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_46" value="6"><label class="ml-2"
                                                            for="panel_type_46">Time Quote</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_47" value="7"><label class="ml-2"
                                                            for="panel_type_47">Paymen tMissing</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_48" value="8"><label class="ml-2"
                                                            for="panel_type_48">Booked</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_466" value="66"><label
                                                            class="ml-2" for="panel_type_466">Double
                                                            Booking</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_49" value="9"><label class="ml-2"
                                                            for="panel_type_49">Listed</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_410" value="10"><label
                                                            class="ml-2" for="panel_type_410">Schedule</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_411" value="11"><label
                                                            class="ml-2" for="panel_type_411">Pickup</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_412" value="12"><label
                                                            class="ml-2" for="panel_type_412">Delivered</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_413" value="13"><label
                                                            class="ml-2" for="panel_type_413">Completed</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_414" value="14"><label
                                                            class="ml-2" for="panel_type_414">Cancel</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_415" value="15"><label
                                                            class="ml-2" for="panel_type_415">Deleted</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_416" value="16"><label
                                                            class="ml-2" for="panel_type_416">Owes Money</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_417" value="17"><label
                                                            class="ml-2" for="panel_type_417">Carrier
                                                            Update</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_418" value="18"><label
                                                            class="ml-2" for="panel_type_418">Car Quote</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_419" value="19"><label
                                                            class="ml-2" for="panel_type_419">Heavy Quote</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_420" value="20"><label
                                                            class="ml-2" for="panel_type_420">Add/Edit
                                                            Employee</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_421" value="21"><label
                                                            class="ml-2" for="panel_type_421">Admin Issues</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_422" value="22"><label
                                                            class="ml-2" for="panel_type_422">Old Quotes</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_423" value="23"><label
                                                            class="ml-2" for="panel_type_423">Transportation
                                                            Invoice</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_473" value="73"><label
                                                            class="ml-2" for="panel_type_473">Roro Invoice</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_424" value="24"><label
                                                            class="ml-2" for="panel_type_424">Carriers</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_425" value="25"><label
                                                            class="ml-2" for="panel_type_425">View Emails</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_426" value="26"><label
                                                            class="ml-2" for="panel_type_426">Show Data</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_427" value="27"><label
                                                            class="ml-2" for="panel_type_427">Sheets</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_428" value="28"><label
                                                            class="ml-2" for="panel_type_428">On Approval</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_429" value="29"><label
                                                            class="ml-2" for="panel_type_429">Delivered
                                                            Approval</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_430" value="30"><label
                                                            class="ml-2" for="panel_type_430">Approaching</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_431" value="31"><label
                                                            class="ml-2" for="panel_type_431">Payment
                                                            System</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_432" value="32"><label
                                                            class="ml-2" for="panel_type_432">Employee
                                                            Reports</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_433" value="33"><label
                                                            class="ml-2" for="panel_type_433">Price Per
                                                            Mile</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_434" value="34"><label
                                                            class="ml-2" for="panel_type_434">Filtered Data</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_435" value="35"><label
                                                            class="ml-2" for="panel_type_435">Group</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_436" value="36"><label
                                                            class="ml-2"
                                                            for="panel_type_436">Questions/Answers</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_437" value="37"><label
                                                            class="ml-2" for="panel_type_437">New Show Data</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_438" value="38"><label
                                                            class="ml-2" for="panel_type_438">Customer</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_4[]" id="panel_type_439" value="39"><label class="ml-2" for="panel_type_439">Messages Center</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_4[]" id="panel_type_440" value="40"><label class="ml-2" for="panel_type_440">Call Logs Center</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_441" value="41"><label
                                                            class="ml-2" for="panel_type_441">Update Phone
                                                            Digits</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_442" value="42"><label
                                                            class="ml-2" for="panel_type_442">Show Customer
                                                            Number</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_4[]" id="panel_type_467" value="67"><label class="ml-2" for="panel_type_467">Customer Full Number</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_460" value="60"><label
                                                            class="ml-2" for="panel_type_460">Show Driver
                                                            Number</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_4[]" id="panel_type_461" value="61"><label class="ml-2" for="panel_type_461">Driver Full Number</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_443" value="43"><label
                                                            class="ml-2" for="panel_type_443">Flag
                                                            Employees</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_444" value="44"><label
                                                            class="ml-2" for="panel_type_444">Transfer
                                                            Quotes</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_446" value="46"><label
                                                            class="ml-2" for="panel_type_446">Revenue</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_447" value="47"><label
                                                            class="ml-2" for="panel_type_447">Coupons</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_448" value="48"><label
                                                            class="ml-2" for="panel_type_448">Website Links</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_449" value="49"><label
                                                            class="ml-2" for="panel_type_449">Feedbacks</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_4[]" id="panel_type_445" value="45"><label class="ml-2" for="panel_type_445">Add Feedback</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_450" value="50"><label
                                                            class="ml-2" for="panel_type_450">Managers
                                                            Group</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_451" value="51"><label
                                                            class="ml-2" for="panel_type_451">Last Activity</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_452" value="52"><label
                                                            class="ml-2" for="panel_type_452">Login Ip
                                                            Address</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_453" value="53"><label
                                                            class="ml-2" for="panel_type_453">Storage</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_454" value="54"><label
                                                            class="ml-2" for="panel_type_454">Shipment
                                                            Status</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_455" value="55"><label
                                                            class="ml-2" for="panel_type_455">Dispatch
                                                            Report</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_456" value="56"><label
                                                            class="ml-2" for="panel_type_456">Employee
                                                            Rating</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_457" value="57"><label
                                                            class="ml-2" for="panel_type_457">Performance
                                                            Report</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_4[]" id="panel_type_458" value="58"><label class="ml-2" for="panel_type_458">View Sheets</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_4[]" id="panel_type_459" value="59"><label class="ml-2" for="panel_type_459">View Cancel History</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_462" value="62"><label
                                                            class="ml-2" for="panel_type_462">QA Report</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_463" value="63"><label
                                                            class="ml-2" for="panel_type_463">Roles</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_464" value="64"><label
                                                            class="ml-2" for="panel_type_464">Update QA
                                                            History</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_465" value="65"><label
                                                            class="ml-2" for="panel_type_465">View QA
                                                            History</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_468" value="68"><label
                                                            class="ml-2" for="panel_type_468">Approaching Number
                                                            Phone</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_469" value="69"><label
                                                            class="ml-2" for="panel_type_469">Approaching Number
                                                            Website</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_470" value="70"><label
                                                            class="ml-2" for="panel_type_470">Approaching Search
                                                            Filter</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_471" value="71"><label
                                                            class="ml-2" for="panel_type_471">Booked Name</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_472" value="72"><label
                                                            class="ml-2" for="panel_type_472">Offer Price</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_474" value="74"><label
                                                            class="ml-2" for="panel_type_474">Achievement
                                                            Sheet</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_475" value="75"><label
                                                            class="ml-2" for="panel_type_475">Port Price</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_476" value="76"><label
                                                            class="ml-2" for="panel_type_476">Assign To
                                                            Dispatcher</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_477" value="77"><label
                                                            class="ml-2" for="panel_type_477">Move OnApprovalCancel
                                                            To Cancel</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_4[]" id="panel_type_478" value="78"><label class="ml-2" for="panel_type_478">Payment Confirmation</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_479" value="79"><label
                                                            class="ml-2" for="panel_type_479">Profile</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_4[]" id="panel_type_480" value="80"><label class="ml-2" for="panel_type_480">Port Sheet</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_4[]" id="panel_type_481" value="81"><label class="ml-2" for="panel_type_481">Payment Missing Sheet</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_4[]" id="panel_type_482" value="82"><label class="ml-2" for="panel_type_482">Dispatch Sheet</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_4[]" id="panel_type_483" value="83"><label class="ml-2" for="panel_type_483">Pickup Sheet</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_4[]" id="panel_type_484" value="84"><label class="ml-2" for="panel_type_484">Delivered Sheet</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_485" value="85"><label
                                                            class="ml-2" for="panel_type_485">Commission
                                                            Range</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_486" value="86"><label
                                                            class="ml-2" for="panel_type_486">Employee Profile
                                                            Filter</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_487" value="87"><label
                                                            class="ml-2" for="panel_type_487">Break Time</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_488" value="88"><label
                                                            class="ml-2" for="panel_type_488">Freeze Time
                                                            History</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_489" value="89"><label
                                                            class="ml-2" for="panel_type_489">Payment System
                                                            Advance Filter</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_490" value="90"><label
                                                            class="ml-2" for="panel_type_490">Demand Order</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_4[]"
                                                            id="panel_type_491" value="91"><label
                                                            class="ml-2" for="panel_type_491">Sell Invoice</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="exampleModa5" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel20" aria-hidden="true">
                            <div class="modal-dialog" role="document" style="max-width: 60%;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel20">Role Access (Webiste Qoutes)
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <input type="checkbox" id="emp_access_ship_all20"
                                                            class="emp_access_ship_all"><label class="ml-2"
                                                            for="emp_access_ship_all20">All Options</label>
                                                    </div>
                                                    <br>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_50" value="0"><label class="ml-2"
                                                            for="panel_type_50">New</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_51" value="1"><label class="ml-2"
                                                            for="panel_type_51">Interested</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_52" value="2"><label class="ml-2"
                                                            for="panel_type_52">Follow More</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_53" value="3"><label class="ml-2"
                                                            for="panel_type_53">Asking Low</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_54" value="4"><label class="ml-2"
                                                            for="panel_type_54">Not Interested</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_55" value="5"><label class="ml-2"
                                                            for="panel_type_55">No Response</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_56" value="6"><label class="ml-2"
                                                            for="panel_type_56">Time Quote</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_57" value="7"><label class="ml-2"
                                                            for="panel_type_57">Paymen tMissing</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_58" value="8"><label class="ml-2"
                                                            for="panel_type_58">Booked</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_566" value="66"><label
                                                            class="ml-2" for="panel_type_566">Double
                                                            Booking</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_59" value="9"><label class="ml-2"
                                                            for="panel_type_59">Listed</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_510" value="10"><label
                                                            class="ml-2" for="panel_type_510">Schedule</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_511" value="11"><label
                                                            class="ml-2" for="panel_type_511">Pickup</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_512" value="12"><label
                                                            class="ml-2" for="panel_type_512">Delivered</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_513" value="13"><label
                                                            class="ml-2" for="panel_type_513">Completed</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_514" value="14"><label
                                                            class="ml-2" for="panel_type_514">Cancel</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_515" value="15"><label
                                                            class="ml-2" for="panel_type_515">Deleted</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_516" value="16"><label
                                                            class="ml-2" for="panel_type_516">Owes Money</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_517" value="17"><label
                                                            class="ml-2" for="panel_type_517">Carrier
                                                            Update</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_518" value="18"><label
                                                            class="ml-2" for="panel_type_518">Car Quote</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_519" value="19"><label
                                                            class="ml-2" for="panel_type_519">Heavy Quote</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_520" value="20"><label
                                                            class="ml-2" for="panel_type_520">Add/Edit
                                                            Employee</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_521" value="21"><label
                                                            class="ml-2" for="panel_type_521">Admin Issues</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_522" value="22"><label
                                                            class="ml-2" for="panel_type_522">Old Quotes</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_523" value="23"><label
                                                            class="ml-2" for="panel_type_523">Transportation
                                                            Invoice</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_573" value="73"><label
                                                            class="ml-2" for="panel_type_573">Roro Invoice</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_524" value="24"><label
                                                            class="ml-2" for="panel_type_524">Carriers</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_525" value="25"><label
                                                            class="ml-2" for="panel_type_525">View Emails</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_526" value="26"><label
                                                            class="ml-2" for="panel_type_526">Show Data</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_527" value="27"><label
                                                            class="ml-2" for="panel_type_527">Sheets</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_528" value="28"><label
                                                            class="ml-2" for="panel_type_528">On Approval</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_529" value="29"><label
                                                            class="ml-2" for="panel_type_529">Delivered
                                                            Approval</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_530" value="30"><label
                                                            class="ml-2" for="panel_type_530">Approaching</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_531" value="31"><label
                                                            class="ml-2" for="panel_type_531">Payment
                                                            System</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_532" value="32"><label
                                                            class="ml-2" for="panel_type_532">Employee
                                                            Reports</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_533" value="33"><label
                                                            class="ml-2" for="panel_type_533">Price Per
                                                            Mile</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_534" value="34"><label
                                                            class="ml-2" for="panel_type_534">Filtered Data</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_535" value="35"><label
                                                            class="ml-2" for="panel_type_535">Group</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_536" value="36"><label
                                                            class="ml-2"
                                                            for="panel_type_536">Questions/Answers</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_537" value="37"><label
                                                            class="ml-2" for="panel_type_537">New Show Data</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_538" value="38"><label
                                                            class="ml-2" for="panel_type_538">Customer</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_5[]" id="panel_type_539" value="39"><label class="ml-2" for="panel_type_539">Messages Center</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_5[]" id="panel_type_540" value="40"><label class="ml-2" for="panel_type_540">Call Logs Center</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_541" value="41"><label
                                                            class="ml-2" for="panel_type_541">Update Phone
                                                            Digits</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_542" value="42"><label
                                                            class="ml-2" for="panel_type_542">Show Customer
                                                            Number</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_5[]" id="panel_type_567" value="67"><label class="ml-2" for="panel_type_567">Customer Full Number</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_560" value="60"><label
                                                            class="ml-2" for="panel_type_560">Show Driver
                                                            Number</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_5[]" id="panel_type_561" value="61"><label class="ml-2" for="panel_type_561">Driver Full Number</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_543" value="43"><label
                                                            class="ml-2" for="panel_type_543">Flag
                                                            Employees</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_544" value="44"><label
                                                            class="ml-2" for="panel_type_544">Transfer
                                                            Quotes</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_546" value="46"><label
                                                            class="ml-2" for="panel_type_546">Revenue</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_547" value="47"><label
                                                            class="ml-2" for="panel_type_547">Coupons</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_548" value="48"><label
                                                            class="ml-2" for="panel_type_548">Website Links</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_549" value="49"><label
                                                            class="ml-2" for="panel_type_549">Feedbacks</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_5[]" id="panel_type_545" value="45"><label class="ml-2" for="panel_type_545">Add Feedback</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_550" value="50"><label
                                                            class="ml-2" for="panel_type_550">Managers
                                                            Group</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_551" value="51"><label
                                                            class="ml-2" for="panel_type_551">Last Activity</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_552" value="52"><label
                                                            class="ml-2" for="panel_type_552">Login Ip
                                                            Address</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_553" value="53"><label
                                                            class="ml-2" for="panel_type_553">Storage</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_554" value="54"><label
                                                            class="ml-2" for="panel_type_554">Shipment
                                                            Status</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_555" value="55"><label
                                                            class="ml-2" for="panel_type_555">Dispatch
                                                            Report</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_556" value="56"><label
                                                            class="ml-2" for="panel_type_556">Employee
                                                            Rating</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_557" value="57"><label
                                                            class="ml-2" for="panel_type_557">Performance
                                                            Report</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_5[]" id="panel_type_558" value="58"><label class="ml-2" for="panel_type_558">View Sheets</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_5[]" id="panel_type_559" value="59"><label class="ml-2" for="panel_type_559">View Cancel History</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_562" value="62"><label
                                                            class="ml-2" for="panel_type_562">QA Report</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_563" value="63"><label
                                                            class="ml-2" for="panel_type_563">Roles</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_564" value="64"><label
                                                            class="ml-2" for="panel_type_564">Update QA
                                                            History</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_565" value="65"><label
                                                            class="ml-2" for="panel_type_565">View QA
                                                            History</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_568" value="68"><label
                                                            class="ml-2" for="panel_type_568">Approaching Number
                                                            Phone</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_569" value="69"><label
                                                            class="ml-2" for="panel_type_569">Approaching Number
                                                            Website</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_570" value="70"><label
                                                            class="ml-2" for="panel_type_570">Approaching Search
                                                            Filter</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_571" value="71"><label
                                                            class="ml-2" for="panel_type_571">Booked Name</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_572" value="72"><label
                                                            class="ml-2" for="panel_type_572">Offer Price</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_574" value="74"><label
                                                            class="ml-2" for="panel_type_574">Achievement
                                                            Sheet</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_575" value="75"><label
                                                            class="ml-2" for="panel_type_575">Port Price</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_576" value="76"><label
                                                            class="ml-2" for="panel_type_576">Assign To
                                                            Dispatcher</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_577" value="77"><label
                                                            class="ml-2" for="panel_type_577">Move OnApprovalCancel
                                                            To Cancel</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_5[]" id="panel_type_578" value="78"><label class="ml-2" for="panel_type_578">Payment Confirmation</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_579" value="79"><label
                                                            class="ml-2" for="panel_type_579">Profile</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_5[]" id="panel_type_580" value="80"><label class="ml-2" for="panel_type_580">Port Sheet</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_5[]" id="panel_type_581" value="81"><label class="ml-2" for="panel_type_581">Payment Missing Sheet</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_5[]" id="panel_type_582" value="82"><label class="ml-2" for="panel_type_582">Dispatch Sheet</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_5[]" id="panel_type_583" value="83"><label class="ml-2" for="panel_type_583">Pickup Sheet</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_5[]" id="panel_type_584" value="84"><label class="ml-2" for="panel_type_584">Delivered Sheet</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_585" value="85"><label
                                                            class="ml-2" for="panel_type_585">Commission
                                                            Range</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_586" value="86"><label
                                                            class="ml-2" for="panel_type_586">Employee Profile
                                                            Filter</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_587" value="87"><label
                                                            class="ml-2" for="panel_type_587">Break Time</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_588" value="88"><label
                                                            class="ml-2" for="panel_type_588">Freeze Time
                                                            History</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_589" value="89"><label
                                                            class="ml-2" for="panel_type_589">Payment System
                                                            Advance Filter</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_590" value="90"><label
                                                            class="ml-2" for="panel_type_590">Demand Order</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_5[]"
                                                            id="panel_type_591" value="91"><label
                                                            class="ml-2" for="panel_type_591">Sell Invoice</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="exampleModa6" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel20" aria-hidden="true">
                            <div class="modal-dialog" role="document" style="max-width: 60%;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel20">Role Access (Webiste Qoutes)
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <input type="checkbox" id="emp_access_ship_all20"
                                                            class="emp_access_ship_all"><label class="ml-2"
                                                            for="emp_access_ship_all20">All Options</label>
                                                    </div>
                                                    <br>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_60" value="0"><label class="ml-2"
                                                            for="panel_type_60">New</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_61" value="1"><label class="ml-2"
                                                            for="panel_type_61">Interested</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_62" value="2"><label class="ml-2"
                                                            for="panel_type_62">Follow More</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_63" value="3"><label class="ml-2"
                                                            for="panel_type_63">Asking Low</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_64" value="4"><label class="ml-2"
                                                            for="panel_type_64">Not Interested</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_65" value="5"><label class="ml-2"
                                                            for="panel_type_65">No Response</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_66" value="6"><label class="ml-2"
                                                            for="panel_type_66">Time Quote</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_67" value="7"><label class="ml-2"
                                                            for="panel_type_67">Paymen tMissing</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_68" value="8"><label class="ml-2"
                                                            for="panel_type_68">Booked</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_666" value="66"><label
                                                            class="ml-2" for="panel_type_666">Double
                                                            Booking</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_69" value="9"><label class="ml-2"
                                                            for="panel_type_69">Listed</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_610" value="10"><label
                                                            class="ml-2" for="panel_type_610">Schedule</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_611" value="11"><label
                                                            class="ml-2" for="panel_type_611">Pickup</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_612" value="12"><label
                                                            class="ml-2" for="panel_type_612">Delivered</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_613" value="13"><label
                                                            class="ml-2" for="panel_type_613">Completed</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_614" value="14"><label
                                                            class="ml-2" for="panel_type_614">Cancel</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_615" value="15"><label
                                                            class="ml-2" for="panel_type_615">Deleted</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_616" value="16"><label
                                                            class="ml-2" for="panel_type_616">Owes Money</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_617" value="17"><label
                                                            class="ml-2" for="panel_type_617">Carrier
                                                            Update</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_618" value="18"><label
                                                            class="ml-2" for="panel_type_618">Car Quote</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_619" value="19"><label
                                                            class="ml-2" for="panel_type_619">Heavy Quote</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_620" value="20"><label
                                                            class="ml-2" for="panel_type_620">Add/Edit
                                                            Employee</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_621" value="21"><label
                                                            class="ml-2" for="panel_type_621">Admin Issues</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_622" value="22"><label
                                                            class="ml-2" for="panel_type_622">Old Quotes</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_623" value="23"><label
                                                            class="ml-2" for="panel_type_623">Transportation
                                                            Invoice</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_673" value="73"><label
                                                            class="ml-2" for="panel_type_673">Roro Invoice</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_624" value="24"><label
                                                            class="ml-2" for="panel_type_624">Carriers</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_625" value="25"><label
                                                            class="ml-2" for="panel_type_625">View Emails</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_626" value="26"><label
                                                            class="ml-2" for="panel_type_626">Show Data</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_627" value="27"><label
                                                            class="ml-2" for="panel_type_627">Sheets</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_628" value="28"><label
                                                            class="ml-2" for="panel_type_628">On Approval</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_629" value="29"><label
                                                            class="ml-2" for="panel_type_629">Delivered
                                                            Approval</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_630" value="30"><label
                                                            class="ml-2" for="panel_type_630">Approaching</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_631" value="31"><label
                                                            class="ml-2" for="panel_type_631">Payment
                                                            System</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_632" value="32"><label
                                                            class="ml-2" for="panel_type_632">Employee
                                                            Reports</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_633" value="33"><label
                                                            class="ml-2" for="panel_type_633">Price Per
                                                            Mile</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_634" value="34"><label
                                                            class="ml-2" for="panel_type_634">Filtered Data</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_635" value="35"><label
                                                            class="ml-2" for="panel_type_635">Group</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_636" value="36"><label
                                                            class="ml-2"
                                                            for="panel_type_636">Questions/Answers</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_637" value="37"><label
                                                            class="ml-2" for="panel_type_637">New Show Data</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_638" value="38"><label
                                                            class="ml-2" for="panel_type_638">Customer</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_6[]" id="panel_type_639" value="39"><label class="ml-2" for="panel_type_639">Messages Center</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_6[]" id="panel_type_640" value="40"><label class="ml-2" for="panel_type_640">Call Logs Center</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_641" value="41"><label
                                                            class="ml-2" for="panel_type_641">Update Phone
                                                            Digits</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_642" value="42"><label
                                                            class="ml-2" for="panel_type_642">Show Customer
                                                            Number</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_6[]" id="panel_type_667" value="67"><label class="ml-2" for="panel_type_667">Customer Full Number</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_660" value="60"><label
                                                            class="ml-2" for="panel_type_660">Show Driver
                                                            Number</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_6[]" id="panel_type_661" value="61"><label class="ml-2" for="panel_type_661">Driver Full Number</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_643" value="43"><label
                                                            class="ml-2" for="panel_type_643">Flag
                                                            Employees</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_644" value="44"><label
                                                            class="ml-2" for="panel_type_644">Transfer
                                                            Quotes</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_646" value="46"><label
                                                            class="ml-2" for="panel_type_646">Revenue</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_647" value="47"><label
                                                            class="ml-2" for="panel_type_647">Coupons</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_648" value="48"><label
                                                            class="ml-2" for="panel_type_648">Website Links</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_649" value="49"><label
                                                            class="ml-2" for="panel_type_649">Feedbacks</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_6[]" id="panel_type_645" value="45"><label class="ml-2" for="panel_type_645">Add Feedback</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_650" value="50"><label
                                                            class="ml-2" for="panel_type_650">Managers
                                                            Group</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_651" value="51"><label
                                                            class="ml-2" for="panel_type_651">Last Activity</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_652" value="52"><label
                                                            class="ml-2" for="panel_type_652">Login Ip
                                                            Address</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_653" value="53"><label
                                                            class="ml-2" for="panel_type_653">Storage</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_654" value="54"><label
                                                            class="ml-2" for="panel_type_654">Shipment
                                                            Status</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_655" value="55"><label
                                                            class="ml-2" for="panel_type_655">Dispatch
                                                            Report</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_656" value="56"><label
                                                            class="ml-2" for="panel_type_656">Employee
                                                            Rating</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_657" value="57"><label
                                                            class="ml-2" for="panel_type_657">Performance
                                                            Report</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_6[]" id="panel_type_658" value="58"><label class="ml-2" for="panel_type_658">View Sheets</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_6[]" id="panel_type_659" value="59"><label class="ml-2" for="panel_type_659">View Cancel History</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_662" value="62"><label
                                                            class="ml-2" for="panel_type_662">QA Report</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_663" value="63"><label
                                                            class="ml-2" for="panel_type_663">Roles</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_664" value="64"><label
                                                            class="ml-2" for="panel_type_664">Update QA
                                                            History</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_665" value="65"><label
                                                            class="ml-2" for="panel_type_665">View QA
                                                            History</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_668" value="68"><label
                                                            class="ml-2" for="panel_type_668">Approaching Number
                                                            Phone</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_669" value="69"><label
                                                            class="ml-2" for="panel_type_669">Approaching Number
                                                            Website</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_670" value="70"><label
                                                            class="ml-2" for="panel_type_670">Approaching Search
                                                            Filter</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_671" value="71"><label
                                                            class="ml-2" for="panel_type_671">Booked Name</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_672" value="72"><label
                                                            class="ml-2" for="panel_type_672">Offer Price</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_674" value="74"><label
                                                            class="ml-2" for="panel_type_674">Achievement
                                                            Sheet</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_675" value="75"><label
                                                            class="ml-2" for="panel_type_675">Port Price</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_676" value="76"><label
                                                            class="ml-2" for="panel_type_676">Assign To
                                                            Dispatcher</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_677" value="77"><label
                                                            class="ml-2" for="panel_type_677">Move OnApprovalCancel
                                                            To Cancel</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_6[]" id="panel_type_678" value="78"><label class="ml-2" for="panel_type_678">Payment Confirmation</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_679" value="79"><label
                                                            class="ml-2" for="panel_type_679">Profile</label>
                                                    </div>
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_6[]" id="panel_type_680" value="80"><label class="ml-2" for="panel_type_680">Port Sheet</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_6[]" id="panel_type_681" value="81"><label class="ml-2" for="panel_type_681">Payment Missing Sheet</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_6[]" id="panel_type_682" value="82"><label class="ml-2" for="panel_type_682">Dispatch Sheet</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_6[]" id="panel_type_683" value="83"><label class="ml-2" for="panel_type_683">Pickup Sheet</label>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6">-->
                                                    <!--    <input type="checkbox" name="panel_type_6[]" id="panel_type_684" value="84"><label class="ml-2" for="panel_type_684">Delivered Sheet</label>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_685" value="85"><label
                                                            class="ml-2" for="panel_type_685">Commission
                                                            Range</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_686" value="86"><label
                                                            class="ml-2" for="panel_type_686">Employee Profile
                                                            Filter</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_687" value="87"><label
                                                            class="ml-2" for="panel_type_687">Break Time</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_688" value="88"><label
                                                            class="ml-2" for="panel_type_688">Freeze Time
                                                            History</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_689" value="89"><label
                                                            class="ml-2" for="panel_type_689">Payment System
                                                            Advance Filter</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_690" value="90"><label
                                                            class="ml-2" for="panel_type_690">Demand Order</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="panel_type_6[]"
                                                            id="panel_type_691" value="91"><label
                                                            class="ml-2" for="panel_type_691">Sell Invoice</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel3" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel3">Role Access (Show Data)</h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <input type="checkbox" id="emp_access_ship_all3"
                                                            class="emp_access_ship_all"><label class="ml-2"
                                                            for="emp_access_ship_all3">All Options</label>
                                                    </div>
                                                    <br>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_show_data[]"
                                                            id="emp_show_data1" value="1"><label class="ml-2"
                                                            for="emp_show_data1">New</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_show_data[]"
                                                            id="emp_show_data2" value="2"><label class="ml-2"
                                                            for="emp_show_data2">Follow Up</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_show_data[]"
                                                            id="emp_show_data3" value="3"><label class="ml-2"
                                                            for="emp_show_data3">Interested</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_show_data[]"
                                                            id="emp_show_data4" value="4"><label class="ml-2"
                                                            for="emp_show_data4">Not Interested</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_show_data[]"
                                                            id="emp_show_data5" value="5"><label class="ml-2"
                                                            for="emp_show_data5">Asking Low</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_show_data[]"
                                                            id="emp_show_data6" value="6"><label class="ml-2"
                                                            for="emp_show_data6">No Responding</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_show_data[]"
                                                            id="emp_show_data7" value="7"><label class="ml-2"
                                                            for="emp_show_data7">Time Qoute</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_show_data[]"
                                                            id="emp_show_data8" value="8"><label class="ml-2"
                                                            for="emp_show_data8">Payment Missing</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_show_data[]"
                                                            id="emp_show_data9" value="9"><label class="ml-2"
                                                            for="emp_show_data9">On Approval</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_show_data[]"
                                                            id="emp_show_data10" value="10"><label class="ml-2"
                                                            for="emp_show_data10">On Approval Cancel</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_show_data[]"
                                                            id="emp_show_data11" value="11"><label class="ml-2"
                                                            for="emp_show_data11">Booked</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_show_data[]"
                                                            id="emp_show_data12" value="12"><label class="ml-2"
                                                            for="emp_show_data12">Listed</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_show_data[]"
                                                            id="emp_show_data13" value="13"><label class="ml-2"
                                                            for="emp_show_data13">Schedule</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_show_data[]"
                                                            id="emp_show_data14" value="14"><label class="ml-2"
                                                            for="emp_show_data14">Not Picked Up</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_show_data[]"
                                                            id="emp_show_data15" value="15"><label class="ml-2"
                                                            for="emp_show_data15">Picked Up</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_show_data[]"
                                                            id="emp_show_data16" value="16"><label class="ml-2"
                                                            for="emp_show_data16">Not Delivered</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_show_data[]"
                                                            id="emp_show_data23" value="23"><label class="ml-2"
                                                            for="emp_show_data23">Schedule For Delivery</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_show_data[]"
                                                            id="emp_show_data17" value="17"><label class="ml-2"
                                                            for="emp_show_data17">Delivered</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_show_data[]"
                                                            id="emp_show_data18" value="18"><label class="ml-2"
                                                            for="emp_show_data18">Completed</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_show_data[]"
                                                            id="emp_show_data19" value="19"><label class="ml-2"
                                                            for="emp_show_data19">Cancelled</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_show_data[]"
                                                            id="emp_show_data20" value="20"><label class="ml-2"
                                                            for="emp_show_data20">Deleted</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_show_data[]"
                                                            id="emp_show_data21" value="21"><label class="ml-2"
                                                            for="emp_show_data21">Owes Money</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_show_data[]"
                                                            id="emp_show_data22" value="22"><label class="ml-2"
                                                            for="emp_show_data22">No Win Auction</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="exampleModal4" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel4" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel4">Role Access (Shipment Status)
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <input type="checkbox" id="emp_access_ship_all4"
                                                            class="emp_access_ship_all"><label class="ml-2"
                                                            for="emp_access_ship_all4">All Options</label>
                                                    </div>
                                                    <br>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship0" value="0"><label class="ml-2"
                                                            for="emp_access_ship0">New</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship1" value="1"><label class="ml-2"
                                                            for="emp_access_ship1">Interested</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship2" value="2"><label class="ml-2"
                                                            for="emp_access_ship2">Follow More</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship3" value="3"><label class="ml-2"
                                                            for="emp_access_ship3">Asking Low</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship4" value="4"><label class="ml-2"
                                                            for="emp_access_ship4">Not Interested</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship5" value="5"><label class="ml-2"
                                                            for="emp_access_ship5">No Response</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship6" value="6"><label class="ml-2"
                                                            for="emp_access_ship6">Time Quote</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship7" value="7"><label class="ml-2"
                                                            for="emp_access_ship7">Payment Missing</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship8" value="8"><label class="ml-2"
                                                            for="emp_access_ship8">Booked</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship18" value="18"><label
                                                            class="ml-2" for="emp_access_ship18">OnApproval</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship9" value="9"><label class="ml-2"
                                                            for="emp_access_ship9">Listed</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship10" value="10"><label
                                                            class="ml-2" for="emp_access_ship10">Schedule</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship34" value="34"><label
                                                            class="ml-2" for="emp_access_ship34">Schedule Another
                                                            Driver</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship30" value="30"><label
                                                            class="ml-2" for="emp_access_ship30">Pickup
                                                            Approval</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship11" value="11"><label
                                                            class="ml-2" for="emp_access_ship11">Pickup</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship31" value="31"><label
                                                            class="ml-2" for="emp_access_ship31">Delivered
                                                            Approval</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship32" value="32"><label
                                                            class="ml-2" for="emp_access_ship32">Schedule For
                                                            Delivery</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship12" value="12"><label
                                                            class="ml-2" for="emp_access_ship12">Delivered</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship19" value="19"><label
                                                            class="ml-2"
                                                            for="emp_access_ship19">OnApprovalCancelled</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship14" value="14"><label
                                                            class="ml-2" for="emp_access_ship14">Cancel</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship20" value="20"><label
                                                            class="ml-2" for="emp_access_ship20">Relist</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship21" value="21"><label
                                                            class="ml-2" for="emp_access_ship21">Price Raise</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship22" value="22"><label
                                                            class="ml-2" for="emp_access_ship22">Approach Id</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship23" value="23"><label
                                                            class="ml-2" for="emp_access_ship23">Different
                                                            Port</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship24" value="24"><label
                                                            class="ml-2" for="emp_access_ship24">Carrier
                                                            Update</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship25" value="25"><label
                                                            class="ml-2" for="emp_access_ship25">Storage</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship26" value="26"><label
                                                            class="ml-2" for="emp_access_ship26">Approaching</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship27" value="27"><label
                                                            class="ml-2" for="emp_access_ship27">Auction Update
                                                            Request</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship28" value="28"><label
                                                            class="ml-2" for="emp_access_ship28">Move To
                                                            Storage</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship29" value="29"><label
                                                            class="ml-2" for="emp_access_ship29">Double
                                                            Booking</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_ship[]"
                                                            id="emp_access_ship33" value="33"><label
                                                            class="ml-2" for="emp_access_ship33">Auction
                                                            Update</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="exampleModal5" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel5" aria-hidden="true">
                            <div class="modal-dialog" role="document" style="max-width: 55%;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel5">Employee Access (Profile)</h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <input type="checkbox" id="emp_access_ship_all5"
                                                            class="emp_access_ship_all"><label class="ml-2"
                                                            for="emp_access_ship_all5">All Options</label>
                                                    </div>
                                                    <br>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_profile[]"
                                                            id="emp_access_profile0" value="0"><label
                                                            class="ml-2" for="emp_access_profile0">New</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_profile[]"
                                                            id="emp_access_profile1" value="1"><label
                                                            class="ml-2" for="emp_access_profile1">Interested</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_profile[]"
                                                            id="emp_access_profile2" value="2"><label
                                                            class="ml-2" for="emp_access_profile2">Follow More</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_profile[]"
                                                            id="emp_access_profile3" value="3"><label
                                                            class="ml-2" for="emp_access_profile3">Asking Low</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_profile[]"
                                                            id="emp_access_profile4" value="4"><label
                                                            class="ml-2" for="emp_access_profile4">Not
                                                            Interested</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_profile[]"
                                                            id="emp_access_profile5" value="5"><label
                                                            class="ml-2" for="emp_access_profile5">No Response</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_profile[]"
                                                            id="emp_access_profile6" value="6"><label
                                                            class="ml-2" for="emp_access_profile6">Time Quote</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_profile[]"
                                                            id="emp_access_profile7" value="7"><label
                                                            class="ml-2" for="emp_access_profile7">Payment
                                                            Missing</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_profile[]"
                                                            id="emp_access_profile18" value="18"><label
                                                            class="ml-2" for="emp_access_profile18">OnApproval</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_profile[]"
                                                            id="emp_access_profile8" value="8"><label
                                                            class="ml-2" for="emp_access_profile8">Booked</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_profile[]"
                                                            id="emp_access_profile9" value="9"><label
                                                            class="ml-2" for="emp_access_profile9">Listed</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_profile[]"
                                                            id="emp_access_profile10" value="10"><label
                                                            class="ml-2" for="emp_access_profile10">Schedule</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_profile[]"
                                                            id="emp_access_profile30" value="30"><label
                                                            class="ml-2" for="emp_access_profile30">Pickup
                                                            Approval</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_profile[]"
                                                            id="emp_access_profile11" value="11"><label
                                                            class="ml-2" for="emp_access_profile11">Pickup</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_profile[]"
                                                            id="emp_access_profile31" value="31"><label
                                                            class="ml-2" for="emp_access_profile31">Delivered
                                                            Approval</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_profile[]"
                                                            id="emp_access_profile32" value="32"><label
                                                            class="ml-2" for="emp_access_profile32">Schedule For
                                                            Delivery</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_profile[]"
                                                            id="emp_access_profile12" value="12"><label
                                                            class="ml-2" for="emp_access_profile12">Delivered</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_profile[]"
                                                            id="emp_access_profile13" value="13"><label
                                                            class="ml-2" for="emp_access_profile13">Completed</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_profile[]"
                                                            id="emp_access_profile14" value="14"><label
                                                            class="ml-2" for="emp_access_profile14">Cancel</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_profile[]"
                                                            id="emp_access_profile19" value="19"><label
                                                            class="ml-2" for="emp_access_profile19">On Approval
                                                            Cancelled</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_profile[]"
                                                            id="emp_access_profile20" value="20"><label
                                                            class="ml-2" for="emp_access_profile20">Review
                                                            Order</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_profile[]"
                                                            id="emp_access_profile21" value="21"><label
                                                            class="ml-2" for="emp_access_profile21">Cancel Remark By
                                                            (Admin/HOD/TeamLead)</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="exampleModal6" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel6" aria-hidden="true">
                            <div class="modal-dialog" role="document" style="max-width: 55%;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel6">Employee Access (Action)</h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <input type="checkbox" id="emp_access_ship_all6"
                                                            class="emp_access_ship_all"><label class="ml-2"
                                                            for="emp_access_ship_all6">All Options</label>
                                                    </div>
                                                    <br>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_action[]"
                                                            id="emp_access_action1" value="1"><label
                                                            class="ml-2" for="emp_access_action1">Move To
                                                            Pickup</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_action[]"
                                                            id="emp_access_action2" value="2"><label
                                                            class="ml-2" for="emp_access_action2">Move To Schedule For
                                                            Delivery</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_action[]"
                                                            id="emp_access_action3" value="3"><label
                                                            class="ml-2" for="emp_access_action3">Move To
                                                            Delivered</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_action[]"
                                                            id="emp_access_action4" value="4"><label
                                                            class="ml-2" for="emp_access_action4">View/Update</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_action[]"
                                                            id="emp_access_action5" value="5"><label
                                                            class="ml-2" for="emp_access_action5">Edit Data</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_action[]"
                                                            id="emp_access_action6" value="6"><label
                                                            class="ml-2" for="emp_access_action6">Print
                                                            Summary</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_action[]"
                                                            id="emp_access_action7" value="7"><label
                                                            class="ml-2" for="emp_access_action7">Send Payment Link To
                                                            Customer</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_action[]"
                                                            id="emp_access_action8" value="8"><label
                                                            class="ml-2" for="emp_access_action8">View
                                                            Location</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_action[]"
                                                            id="emp_access_action9" value="9"><label
                                                            class="ml-2" for="emp_access_action9">Request</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_action[]"
                                                            id="emp_access_action10" value="10"><label
                                                            class="ml-2" for="emp_access_action10">Pay Now</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_action[]"
                                                            id="emp_access_action11" value="11"><label
                                                            class="ml-2" for="emp_access_action11">Carrier
                                                            Record</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_action[]"
                                                            id="emp_access_action12" value="12"><label
                                                            class="ml-2" for="emp_access_action12">Storage
                                                            Record</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_action[]"
                                                            id="emp_access_action13" value="13"><label
                                                            class="ml-2" for="emp_access_action13">Move to
                                                            Storage</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_action[]"
                                                            id="emp_access_action14" value="14"><label
                                                            class="ml-2" for="emp_access_action14">Payment
                                                            Confirmation</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_action[]"
                                                            id="emp_access_action15" value="15"><label
                                                            class="ml-2" for="emp_access_action15">Message
                                                            Center</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_action[]"
                                                            id="emp_access_action16" value="16"><label
                                                            class="ml-2" for="emp_access_action16">Call Logs
                                                            Center</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_action[]"
                                                            id="emp_access_action17" value="17"><label
                                                            class="ml-2" for="emp_access_action17">Rating</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_action[]"
                                                            id="emp_access_action18" value="18"><label
                                                            class="ml-2" for="emp_access_action18">Delete
                                                            Order</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_action[]"
                                                            id="emp_access_action19" value="19"><label
                                                            class="ml-2" for="emp_access_action19">Feedback</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_action[]"
                                                            id="emp_access_action20" value="20"><label
                                                            class="ml-2" for="emp_access_action20">Sheet</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_action[]"
                                                            id="emp_access_action21" value="21"><label
                                                            class="ml-2" for="emp_access_action21">View Cancel
                                                            History</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="exampleModal7" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel7" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel7">Employee Access (Employee
                                            Report)</h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <input type="checkbox" id="emp_access_ship_all7"
                                                            class="emp_access_ship_all"><label class="ml-2"
                                                            for="emp_access_ship_all7">All Options</label>
                                                    </div>
                                                    <br>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report0" value="0"><label
                                                            class="ml-2" for="emp_access_report0">New</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report1" value="1"><label
                                                            class="ml-2" for="emp_access_report1">Interested</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report2" value="2"><label
                                                            class="ml-2" for="emp_access_report2">Follow More</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report3" value="3"><label
                                                            class="ml-2" for="emp_access_report3">Asking Low</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report4" value="4"><label
                                                            class="ml-2" for="emp_access_report4">Not
                                                            Interested</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report5" value="5"><label
                                                            class="ml-2" for="emp_access_report5">No Response</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report6" value="6"><label
                                                            class="ml-2" for="emp_access_report6">Time Quote</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report7" value="7"><label
                                                            class="ml-2" for="emp_access_report7">Payment
                                                            Missing</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report8" value="8"><label
                                                            class="ml-2" for="emp_access_report8">Booked</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report18" value="18"><label
                                                            class="ml-2" for="emp_access_report18">OnApproval</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report9" value="9"><label
                                                            class="ml-2" for="emp_access_report9">Listed</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report10" value="10"><label
                                                            class="ml-2" for="emp_access_report10">Schedule</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report34" value="34"><label
                                                            class="ml-2" for="emp_access_report34">Schedule Another
                                                            Driver</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report30" value="30"><label
                                                            class="ml-2" for="emp_access_report30">Pickup
                                                            Approval</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report11" value="11"><label
                                                            class="ml-2" for="emp_access_report11">Pickup</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report31" value="31"><label
                                                            class="ml-2" for="emp_access_report31">Delivered
                                                            Approval</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report32" value="32"><label
                                                            class="ml-2" for="emp_access_report32">Schedule For
                                                            Delivery</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report12" value="12"><label
                                                            class="ml-2" for="emp_access_report12">Delivered</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report32" value="13"><label
                                                            class="ml-2" for="emp_access_report13">Completed</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report19" value="19"><label
                                                            class="ml-2"
                                                            for="emp_access_report19">OnApprovalCancelled</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report14" value="14"><label
                                                            class="ml-2" for="emp_access_report14">Cancel</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report20" value="20"><label
                                                            class="ml-2" for="emp_access_report20">Relist</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report21" value="21"><label
                                                            class="ml-2" for="emp_access_report21">Price Raise</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report22" value="22"><label
                                                            class="ml-2" for="emp_access_report22">Approach Id</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report23" value="23"><label
                                                            class="ml-2" for="emp_access_report23">Different
                                                            Port</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report24" value="24"><label
                                                            class="ml-2" for="emp_access_report24">Carrier
                                                            Update</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report25" value="25"><label
                                                            class="ml-2" for="emp_access_report25">Storage</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report26" value="26"><label
                                                            class="ml-2" for="emp_access_report26">Approaching</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report27" value="27"><label
                                                            class="ml-2" for="emp_access_report27">Auction Update
                                                            Request</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report28" value="28"><label
                                                            class="ml-2" for="emp_access_report28">Move To
                                                            Storage</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report29" value="29"><label
                                                            class="ml-2" for="emp_access_report29">Double
                                                            Booking</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report33" value="33"><label
                                                            class="ml-2" for="emp_access_report33">Auction
                                                            Update</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_report[]"
                                                            id="emp_access_report35" value="35"><label
                                                            class="ml-2" for="emp_access_report35">Auction
                                                            Storage</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-success float-right mb-4" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- end app-content-->
@endsection
@section('extraScript')
    <script>
        $(".emp_access_ship_all").on('change', function() {
            if ($(this).is(":checked")) {
                $(this).parent('div').siblings('.col-sm-6').each(function() {
                    $(this).children('input').attr('checked', true);
                })
            } else {
                $(this).parent('div').siblings('.col-sm-6').each(function() {
                    $(this).children('input').attr('checked', false);
                })
            }
        })
    </script>
@endsection
