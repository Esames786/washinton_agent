@extends('layouts.innerpages')

@section('template_title')
    Register
@endsection

@section('content')
    @include('partials.mainsite_pages.return_function')
    <style>
        .select2 li {
            margin: 5px !important;
        }

        .select2 input {
            margin: 0 !important;
        }
    </style>
    <div class="page-header">
        <!--<div class="page-leftheader">-->
        <!--    {{-- <h4 class="page-title mb-0">Add Employee</h4> --}}-->
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layers mr-2 fs-14"></i>Home</a>-->
        <!--        </li>-->
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">Add Employee</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <!--{{-- <div class="page-rightheader"> --}}-->
        <!--    {{-- <div class="btn btn-list"> --}}-->
        <!--        {{-- <a href="#" class="btn btn-info"><i class="fe fe-settings mr-1"></i> General Settings </a> --}}-->
        <!--        {{-- <a href="#" class="btn btn-danger"><i class="fe fe-printer mr-1"></i> Print </a> --}}-->
        <!--        {{-- <a href="#" class="btn btn-warning"><i class="fe fe-shopping-cart mr-1"></i> Buy Now </a> --}}-->
        <!--    {{-- </div> --}}-->
        <!--{{-- </div> --}}-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Add Employee</b></h1>
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
    <form action="" id="form" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Add Employee</div>
                    </div>
                    <div class="card-body">
                        <div class="card-title font-weight-bold">Basic info:</div>
                        <div class="row">
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="form-label">First Name</label>
                                    <input type="text" required name="first_name" class="form-control"
                                        placeholder="First Name">
                                </div>
                            </div>

                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="last_name" class="form-control" placeholder="Last Name"
                                        required>
                                </div>
                            </div>

                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Sudo Name</label>
                                    <input type="text" name="slug" class="form-control" placeholder="Sudo Name"
                                        required>
                                </div>
                            </div>

                            <div class="col-sm-5 col-md-5">
                                <div class="form-group">
                                    <label class="form-label">Email address</label>
                                    <input type="email" required name="email" class="form-control" placeholder="Email">
                                </div>
                            </div>
                            <div class="col-sm-5 col-md-5">
                                <div class="form-group">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" required name="phone_number" id="phoneNumber"
                                        class="form-control W-100" placeholder="Phone Number"
                                        onfocus="$(this).attr('autocomplete', 'off');">
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Commission</label>
                                    <input type="number" required name="commission" min="0" id="commission"
                                        class="form-control W-100" value="0">
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2" id="per_review_field" style="display:none;">
                                <div class="form-group">
                                    <label class="form-label">Per Review</label>
                                    <input type="number" name="per_review" min="0" id="per_review"
                                        class="form-control W-100" value="0">
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2" id="private_pickup_field" style="display:none;">
                                <div class="form-group">
                                    <label class="form-label">Private Pickup</label>
                                    <input type="number" name="private_pickup" min="0" id="private_pickup"
                                        class="form-control W-100" value="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" required>JOB TYPE</label>
                                    <select class="form-control select2" name="job_type">
                                        {{-- <optgroup label="Select Job Type"> --}}
                                        <option value="" selected="" disabled="">Select Job Type</option>
                                        @foreach ($data as $val) ?>
                                            <option value="{{ $val->id }}">{{ $val->name }}</option>
                                        @endforeach
                                        {{-- </optgroup> --}}
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Password</label>
                                    <input type="password" required name="password" class="form-control"
                                        placeholder="Password">
                                </div>
                            </div>
                            <div class="col-md-4" id="sheet_access">
                                <div class="form-group">
                                    <label class="form-label">Sheet Access</label>
                                    <select name="sheet_access[]" class="select2 form-control" multiple>
                                        @foreach ($sheet_data as $key => $val)
                                            <option value="{{ $val->id }}">
                                                {{ date('M-d-Y', strtotime($val->created_at)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4 my-auto" id="private_ot_field" style="display:none;">
                                <div class="form-group d-flex m-0">
                                    <input type="checkbox" value="1" name="private_OT" id="private_OT" />
                                    <label class="form-label my-auto mx-1" for="private_OT">Private OT</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Employee Access</label>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#exampleModa28">Panel Type Access</button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#exampleModal1">Phone Quotes</button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#exampleModal2">Website Quotes</button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#exampleModa20">Testing Quotes</button>
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
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#exampleModal9">Guides</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" style="display:none;" id="client_number">
                                <div class="form-group">
                                    <label class="form-label">Phone Numbers Access</label>
                                    <select name="client_number[]" class="select2 form-control" multiple>
                                        @foreach ($no as $key => $val)
                                            <option value="{{ $val }}"
                                                @if (isset($disableNo[0])) @foreach ($disableNo as $key2 => $val2)
                                                    @if ($val2->client_number == $val)
                                                        disabled @endif
                                                @endforeach
                                        @endif
                                        >{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12" style="display:none;" id="assign_daily_qoute">
                                <div class="form-group">
                                    <label class="form-label">Assign Daily Qoutes</label>
                                    <input type="text" class="form-control" name="assign_daily_qoute" maxlength="2"
                                        placeholder="Enter Assign Daily Qoutes" />
                                </div>
                            </div>
                            <div class="col-md-12" style="display:none;" id="auto_assigning">
                                <div class="form-group">
                                    <label class="form-label">Auto Assign</label>
                                    <select name="auto_assign" class="form-control">
                                        <option value="0" selected>No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12" style="display:none;" id="qoutes">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Qoutes Assign</label>
                                        <div class="row">
                                            <div class="col-sm-4 my-auto">
                                                <div class="form-group d-flex m-0">
                                                    <input type="radio" value="0" checked name="order_taker_quote"
                                                        id="all_qoute" />
                                                    <label class="form-label my-auto mx-1" for="all_qoute">All
                                                        Qoutes</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 my-auto">
                                                <div class="form-group d-flex m-0">
                                                    <input type="radio" value="1" name="order_taker_quote"
                                                        id="own_qoute" />
                                                    <label class="form-label my-auto mx-1" for="own_qoute">Own
                                                        Qoutes</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 my-auto" id="group_qoutes" style="display:none;">
                                                <div class="form-group d-flex m-0">
                                                    <input type="radio" value="2" name="order_taker_quote"
                                                        id="group_qoute" />
                                                    <label class="form-label my-auto mx-1" for="group_qoute">Group
                                                        Qoutes</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="display:none;" id="dispatcher_quotes">
                                        <label class="form-label">Qoutes Assign For (Shipment Status Requests)</label>
                                        <div class="row">
                                            <div class="col-sm-6 my-auto">
                                                <div class="form-group d-flex m-0">
                                                    <input type="radio" value="0" checked
                                                        name="shipment_status_quote_assign" id="all_dis_qoute" />
                                                    <label class="form-label my-auto mx-1" for="all_dis_qoute">All
                                                        Qoutes</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 my-auto">
                                                <div class="form-group d-flex m-0">
                                                    <input type="radio" value="1"
                                                        name="shipment_status_quote_assign" id="own_dis_qoute" />
                                                    <label class="form-label my-auto mx-1" for="own_dis_qoute">Own
                                                        Qoutes</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="display:none;" id="manager">
                                        <div class="form-group m-0">
                                            <label class="form-label">Managers</label>
                                            <select name="manager" class="select2 form-control">
                                                @foreach ($managers as $key => $val)
                                                    <option value="{{ $val->id }}">{{ $val->slug }}
                                                        ({{ $val->userRole->name }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" style="display:none;" id="all_ot">
                                <div class="form-group">
                                    <label class="form-label">CSRs And Seller Agents</label>
                                    <select name="all_ot[]" class="select2 form-control" multiple>
                                        @foreach ($all_ot as $key => $val)
                                            <option value="{{ $val->id }}"
                                                @if (isset($diabledAccess[0])) @foreach ($diabledAccess as $ids)
                                                    @if ($ids->ot_ids == $val->id)
                                                        disabled @endif
                                                @endforeach
                                        @endif
                                        >{{ $val->slug }} ({{ $val->userRole->name }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel1" aria-hidden="true">
                                <div class="modal-dialog" role="document" style="max-width: 60%;">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Employee Access (Phone Qoutes)
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
                                                            <input type="checkbox" id="emp_access_ship_all1"
                                                                class="emp_access_ship_all"><label class="ml-2"
                                                                for="emp_access_ship_all1">All Options</label>
                                                        </div>
                                                        <br>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone0" value="0"><label
                                                                class="ml-2" for="emp_access_phone0">New</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone1" value="1"><label
                                                                class="ml-2" for="emp_access_phone1">Interested</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone2" value="2"><label
                                                                class="ml-2" for="emp_access_phone2">Follow More</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone3" value="3"><label
                                                                class="ml-2" for="emp_access_phone3">Asking Low</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone4" value="4"><label
                                                                class="ml-2" for="emp_access_phone4">Not
                                                                Interested</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone5" value="5"><label
                                                                class="ml-2" for="emp_access_phone5">No Response</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone6" value="6"><label
                                                                class="ml-2" for="emp_access_phone6">Time Quote</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone7" value="7"><label
                                                                class="ml-2" for="emp_access_phone7">Paymen
                                                                tMissing</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone8" value="8"><label
                                                                class="ml-2" for="emp_access_phone8">Booked</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone66" value="66"><label
                                                                class="ml-2" for="emp_access_phone66">Double
                                                                Booking</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone9" value="9"><label
                                                                class="ml-2" for="emp_access_phone9">Listed</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone10" value="10"><label
                                                                class="ml-2" for="emp_access_phone10">Schedule</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone11" value="11"><label
                                                                class="ml-2" for="emp_access_phone11">Pickup</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone12" value="12"><label
                                                                class="ml-2" for="emp_access_phone12">Delivered</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone13" value="13"><label
                                                                class="ml-2" for="emp_access_phone13">Completed</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone14" value="14"><label
                                                                class="ml-2" for="emp_access_phone14">Cancel</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone15" value="15"><label
                                                                class="ml-2" for="emp_access_phone15">Deleted</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone16" value="16"><label
                                                                class="ml-2" for="emp_access_phone16">Owes Money</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone17" value="17"><label
                                                                class="ml-2" for="emp_access_phone17">Carrier
                                                                Update</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone18" value="18"><label
                                                                class="ml-2" for="emp_access_phone18">Car Quote</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone19" value="19"><label
                                                                class="ml-2" for="emp_access_phone19">Heavy
                                                                Quote</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone92" value="92"><label
                                                                class="ml-2" for="emp_access_phone92">Freight
                                                                Quote</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone20" value="20"><label
                                                                class="ml-2" for="emp_access_phone20">Add/Edit
                                                                Employee</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone21" value="21"><label
                                                                class="ml-2" for="emp_access_phone21">Admin
                                                                Issues</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone22" value="22"><label
                                                                class="ml-2" for="emp_access_phone22">Old Quotes</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone23" value="23"><label
                                                                class="ml-2" for="emp_access_phone23">Transportation
                                                                Invoice</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone73" value="73"><label
                                                                class="ml-2" for="emp_access_phone73">Roro
                                                                Invoice</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone24" value="24"><label
                                                                class="ml-2" for="emp_access_phone24">Carriers</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone25" value="25"><label
                                                                class="ml-2" for="emp_access_phone25">View
                                                                Emails</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone26" value="26"><label
                                                                class="ml-2" for="emp_access_phone26">Show Data</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone27" value="27"><label
                                                                class="ml-2" for="emp_access_phone27">Sheets</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone28" value="28"><label
                                                                class="ml-2" for="emp_access_phone28">On
                                                                Approval</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone29" value="29"><label
                                                                class="ml-2" for="emp_access_phone29">Delivered
                                                                Approval</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone30" value="30"><label
                                                                class="ml-2"
                                                                for="emp_access_phone30">Approaching</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone31" value="31"><label
                                                                class="ml-2" for="emp_access_phone31">Payment
                                                                System</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone32" value="32"><label
                                                                class="ml-2" for="emp_access_phone32">Employee
                                                                Reports</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone33" value="33"><label
                                                                class="ml-2" for="emp_access_phone33">Price Per
                                                                Mile</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone34" value="34"><label
                                                                class="ml-2" for="emp_access_phone34">Filtered
                                                                Data</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone35" value="35"><label
                                                                class="ml-2" for="emp_access_phone35">Group</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone36" value="36"><label
                                                                class="ml-2"
                                                                for="emp_access_phone36">Questions/Answers</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone37" value="37"><label
                                                                class="ml-2" for="emp_access_phone37">New Show
                                                                Data</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone38" value="38"><label
                                                                class="ml-2" for="emp_access_phone38">Customer</label>
                                                        </div>
                                                        <!--<div class="col-sm-6">-->
                                                        <!--    <input type="checkbox" name="emp_access_phone[]" id="emp_access_phone39" value="39"><label class="ml-2" for="emp_access_phone39">Messages Center</label>-->
                                                        <!--</div>-->
                                                        <!--<div class="col-sm-6">-->
                                                        <!--    <input type="checkbox" name="emp_access_phone[]" id="emp_access_phone40" value="40"><label class="ml-2" for="emp_access_phone40">Call Logs Center</label>-->
                                                        <!--</div>-->
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone41" value="41"><label
                                                                class="ml-2" for="emp_access_phone41">Update Phone
                                                                Digits</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone42" value="42"><label
                                                                class="ml-2" for="emp_access_phone42">Show Customer
                                                                Number</label>
                                                        </div>
                                                        <!--<div class="col-sm-6">-->
                                                        <!--    <input type="checkbox" name="emp_access_phone[]" id="emp_access_phone67" value="67"><label class="ml-2" for="emp_access_phone67">Customer Full Number</label>-->
                                                        <!--</div>-->
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone60" value="60"><label
                                                                class="ml-2" for="emp_access_phone60">Show Driver
                                                                Number</label>
                                                        </div>
                                                        <!--<div class="col-sm-6">-->
                                                        <!--    <input type="checkbox" name="emp_access_phone[]" id="emp_access_phone61" value="61"><label class="ml-2" for="emp_access_phone61">Driver Full Number</label>-->
                                                        <!--</div>-->
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone43" value="43"><label
                                                                class="ml-2" for="emp_access_phone43">Flag
                                                                Employees</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone44" value="44"><label
                                                                class="ml-2" for="emp_access_phone44">Transfer
                                                                Quotes</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone46" value="46"><label
                                                                class="ml-2" for="emp_access_phone46">Revenue</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone47" value="47"><label
                                                                class="ml-2" for="emp_access_phone47">Coupons</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone48" value="48"><label
                                                                class="ml-2" for="emp_access_phone48">Website
                                                                Links</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone49" value="49"><label
                                                                class="ml-2" for="emp_access_phone49">Feedbacks</label>
                                                        </div>
                                                        <!--<div class="col-sm-6">-->
                                                        <!--    <input type="checkbox" name="emp_access_phone[]" id="emp_access_phone45" value="45"><label class="ml-2" for="emp_access_phone45">Add Feedback</label>-->
                                                        <!--</div>-->
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone50" value="50"><label
                                                                class="ml-2" for="emp_access_phone50">Managers
                                                                Group</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone51" value="51"><label
                                                                class="ml-2" for="emp_access_phone51">Last
                                                                Activity</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone52" value="52"><label
                                                                class="ml-2" for="emp_access_phone52">Login Ip
                                                                Address</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone53" value="53"><label
                                                                class="ml-2" for="emp_access_phone53">Storage</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone54" value="54"><label
                                                                class="ml-2" for="emp_access_phone54">Shipment
                                                                Status</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone55" value="55"><label
                                                                class="ml-2" for="emp_access_phone55">Dispatch
                                                                Report</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone56" value="56"><label
                                                                class="ml-2" for="emp_access_phone56">Employee
                                                                Rating</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone57" value="57"><label
                                                                class="ml-2" for="emp_access_phone57">Performance
                                                                Report</label>
                                                        </div>
                                                        <!--<div class="col-sm-6">-->
                                                        <!--    <input type="checkbox" name="emp_access_phone[]" id="emp_access_phone58" value="58"><label class="ml-2" for="emp_access_phone58">View Sheets</label>-->
                                                        <!--</div>-->
                                                        <!--<div class="col-sm-6">-->
                                                        <!--    <input type="checkbox" name="emp_access_phone[]" id="emp_access_phone59" value="59"><label class="ml-2" for="emp_access_phone59">View Cancel History</label>-->
                                                        <!--</div>-->
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone62" value="62"><label
                                                                class="ml-2" for="emp_access_phone62">QA Report</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone63" value="63"><label
                                                                class="ml-2" for="emp_access_phone63">Roles</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone64" value="64"><label
                                                                class="ml-2" for="emp_access_phone64">Update QA
                                                                History</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone65" value="65"><label
                                                                class="ml-2" for="emp_access_phone65">View QA
                                                                History</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone68" value="68"><label
                                                                class="ml-2" for="emp_access_phone68">Approaching Number
                                                                Phone</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone69" value="69"><label
                                                                class="ml-2" for="emp_access_phone69">Approaching Number
                                                                Website</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone70" value="70"><label
                                                                class="ml-2" for="emp_access_phone70">Approaching Search
                                                                Filter</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone71" value="71"><label
                                                                class="ml-2" for="emp_access_phone71">Booker
                                                                Name</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone72" value="72"><label
                                                                class="ml-2" for="emp_access_phone72">Offer
                                                                Price</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone74" value="74"><label
                                                                class="ml-2" for="emp_access_phone74">Achievement
                                                                Sheet</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone75" value="75"><label
                                                                class="ml-2" for="emp_access_phone75">Port Price</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone76" value="76"><label
                                                                class="ml-2" for="emp_access_phone76">Assign To
                                                                Dispatcher</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone77" value="77"><label
                                                                class="ml-2" for="emp_access_phone77">Move
                                                                OnApprovalCancel To Cancel</label>
                                                        </div>
                                                        <!--<div class="col-sm-6">-->
                                                        <!--    <input type="checkbox" name="emp_access_phone[]" id="emp_access_phone78" value="78"><label class="ml-2" for="emp_access_phone78">Payment Confirmation</label>-->
                                                        <!--</div>-->
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone79" value="79"><label
                                                                class="ml-2" for="emp_access_phone79">Profile</label>
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
                                                                id="emp_access_phone85" value="85"><label
                                                                class="ml-2" for="emp_access_phone85">Commission
                                                                Range</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone86" value="86"><label
                                                                class="ml-2" for="emp_access_phone86">Employee Profile
                                                                Filter</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone87" value="87"><label
                                                                class="ml-2" for="emp_access_phone87">Break Time</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone88" value="88"><label
                                                                class="ml-2" for="emp_access_phone88">Freeze Time
                                                                History</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone89" value="89"><label
                                                                class="ml-2" for="emp_access_phone89">Payment System
                                                                Advance Filter</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone90" value="90"><label
                                                                class="ml-2" for="emp_access_phone90">Demand
                                                                Order</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone91" value="91"><label
                                                                class="ml-2" for="emp_access_phone91">Sell
                                                                Invoice</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone112" value="112"><label
                                                                class="ml-2" for="emp_access_phone112">Message
                                                                Chats</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone116" value="116"><label
                                                                class="ml-2" for="emp_access_phone116">Logout
                                                                Questions</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone117" value="117"><label
                                                                class="ml-2" for="emp_access_phone117">Logout Questions
                                                                Answer View</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone118" value="118"><label
                                                                class="ml-2" for="emp_access_phone118">Logout Questions
                                                                Comments</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone119" value="119"><label
                                                                class="ml-2" for="emp_access_phone119">Logout Questions
                                                                Answer</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone121" value="121"><label
                                                                class="ml-2" for="emp_access_phone121">Show Pickup
                                                                Phone</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone122" value="122"><label
                                                                class="ml-2" for="emp_access_phone122">Show Delivery
                                                                Phone</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone123" value="123"><label
                                                                class="ml-2" for="emp_access_phone123">Request Price
                                                                Page</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone124" value="124"><label
                                                                class="ml-2" for="emp_access_phone124">Block Phone
                                                                View</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone125" value="125"><label
                                                                class="ml-2" for="emp_access_phone125">Block Phone
                                                                Approve</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone126" value="126"><label
                                                                class="ml-2" for="emp_access_phone126">Allow Price
                                                                Giver</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone128" value="128"><label
                                                                class="ml-2" for="emp_access_phone128">Employee Revenue
                                                                (OT)</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone127" value="127"><label
                                                                class="ml-2" for="emp_access_phone127">Employee Revenue
                                                                (DB)</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone129" value="129"><label
                                                                class="ml-2" for="emp_access_phone129">Employee
                                                                Revenue (DIS)</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone130" value="130"><label
                                                                class="ml-2" for="emp_access_phone130">Employee
                                                                Revenue (Private OT)</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone131" value="131"><label
                                                                class="ml-2" for="emp_access_phone131">Cpanel
                                                                Emails</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone132" value="132"><label
                                                                class="ml-2" for="emp_access_phone132">Agents
                                                                Reports</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone133" value="133"><label
                                                                class="ml-2" for="emp_access_phone133">Customer
                                                                Reviews</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone134" value="134"><label
                                                                class="ml-2" for="emp_access_phone134">Call/SMS With App</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone135" value="135"><label
                                                                class="ml-2" for="emp_access_phone135">Call/SMS Old</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone149" value="149"><label
                                                                class="ml-2" for="emp_access_phone149">How Did You Find Us?</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_phone[]"
                                                                id="emp_access_phone150" value="150"><label
                                                                class="ml-2" for="emp_access_phone150">How Did You Find Us? Phone</label>
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
                                            <h5 class="modal-title" id="exampleModalLabel">Employee Access (Webiste
                                                Qoutes)</h5>
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
                                                            <input type="checkbox" id="emp_access_ship_all2"
                                                                class="emp_access_ship_all"><label class="ml-2"
                                                                for="emp_access_ship_all2">All Options</label>
                                                        </div>
                                                        <br>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_web[]"
                                                                id="emp_access_web0" value="0"><label
                                                                class="ml-2" for="emp_access_web0">New</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_web[]"
                                                                id="emp_access_web1" value="1"><label
                                                                class="ml-2" for="emp_access_web1">Interested</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_web[]"
                                                                id="emp_access_web2" value="2"><label
                                                                class="ml-2" for="emp_access_web2">Follow More</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_web[]"
                                                                id="emp_access_web3" value="3"><label
                                                                class="ml-2" for="emp_access_web3">Asking Low</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_web[]"
                                                                id="emp_access_web4" value="4"><label
                                                                class="ml-2" for="emp_access_web4">Not
                                                                Interested</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_web[]"
                                                                id="emp_access_web5" value="5"><label
                                                                class="ml-2" for="emp_access_web5">No Response</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_web[]"
                                                                id="emp_access_web6" value="6"><label
                                                                class="ml-2" for="emp_access_web6">Time Quote</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_web[]"
                                                                id="emp_access_web7" value="7"><label
                                                                class="ml-2" for="emp_access_web7">Paymen
                                                                tMissing</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_web[]"
                                                                id="emp_access_web8" value="8"><label
                                                                class="ml-2" for="emp_access_web8">Booked</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_web[]"
                                                                id="emp_access_web66" value="66"><label
                                                                class="ml-2" for="emp_access_web66">Double
                                                                Booking</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_web[]"
                                                                id="emp_access_web9" value="9"><label
                                                                class="ml-2" for="emp_access_web9">Listed</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_web[]"
                                                                id="emp_access_web10" value="10"><label
                                                                class="ml-2" for="emp_access_web10">Schedule</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_web[]"
                                                                id="emp_access_web11" value="11"><label
                                                                class="ml-2" for="emp_access_web11">Pickup</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_web[]"
                                                                id="emp_access_web12" value="12"><label
                                                                class="ml-2" for="emp_access_web12">Delivered</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_web[]"
                                                                id="emp_access_web13" value="13"><label
                                                                class="ml-2" for="emp_access_web13">Completed</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_web[]"
                                                                id="emp_access_web14" value="14"><label
                                                                class="ml-2" for="emp_access_web14">Cancel</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_web[]"
                                                                id="emp_access_web15" value="15"><label
                                                                class="ml-2" for="emp_access_web15">Deleted</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_web[]"
                                                                id="emp_access_web16" value="16"><label
                                                                class="ml-2" for="emp_access_web16">Owes Money</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_web[]"
                                                                id="emp_access_web17" value="17"><label
                                                                class="ml-2" for="emp_access_web17">Carrier
                                                                Update</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_web[]"
                                                                id="emp_access_web18" value="18"><label
                                                                class="ml-2" for="emp_access_web18">Car Quote</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_web[]"
                                                                id="emp_access_web19" value="19"><label
                                                                class="ml-2" for="emp_access_web19">Heavy
                                                                Quote</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_web[]"
                                                                id="emp_access_web92" value="92"><label
                                                                class="ml-2" for="emp_access_web92">Freight
                                                                Quote</label>
                                                        </div <div class="col-sm-6">
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
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web112" value="112"><label
                                                            class="ml-2" for="emp_access_web112">Message Chats</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web116" value="116"><label
                                                            class="ml-2" for="emp_access_web116">Logout
                                                            Questions</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web117" value="117"><label
                                                            class="ml-2" for="emp_access_web117">Logout Questions
                                                            Answer View</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web118" value="118"><label
                                                            class="ml-2" for="emp_access_web118">Logout Questions
                                                            Comments</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web119" value="119"><label
                                                            class="ml-2" for="emp_access_web119">Logout Questions
                                                            Answer</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web121" value="121"><label
                                                            class="ml-2" for="emp_access_web121">Show Pickup
                                                            Phone</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web122" value="122"><label
                                                            class="ml-2" for="emp_access_web122">Show Delivery
                                                            Phone</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web123" value="123"><label
                                                            class="ml-2" for="emp_access_web123">Request Price
                                                            Page</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web124" value="124"><label
                                                            class="ml-2" for="emp_access_web124">Block Phone
                                                            View</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web125" value="125"><label
                                                            class="ml-2" for="emp_access_web125">Block Phone
                                                            Approve</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web126" value="126"><label
                                                            class="ml-2" for="emp_access_web126">Allow Price
                                                            Giver</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web128" value="128"><label
                                                            class="ml-2" for="emp_access_web128">Employee Revenue
                                                            (OT)</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web127" value="127"><label
                                                            class="ml-2" for="emp_access_web127">Employee Revenue
                                                            (DB)</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web130" value="130"><label
                                                            class="ml-2" for="emp_access_web130">Employee Revenue
                                                            (Private OT)</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web131" value="131"><label
                                                            class="ml-2" for="emp_access_web131">Cpanel Emails</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web132" value="132"><label
                                                            class="ml-2" for="emp_access_web132">Agents
                                                            Reports</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web133" value="133"><label
                                                            class="ml-2" for="emp_access_web133">Customer
                                                            Reviews</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web134" value="134"><label
                                                            class="ml-2" for="emp_access_web134">Call/SMS With App</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web135" value="135"><label
                                                            class="ml-2" for="emp_access_web135">Call/SMS Old</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web149" value="149"><label
                                                            class="ml-2" for="emp_access_web149">How Did You Find Us?</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_web[]"
                                                            id="emp_access_web150" value="150"><label
                                                            class="ml-2" for="emp_access_web150">How Did You Find Us? Phone</label>
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
                            <div class="modal fade" id="exampleModa20" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel20" aria-hidden="true">
                                <div class="modal-dialog" role="document" style="max-width: 60%;">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabe20">Employee Access (Testing
                                                Qoutes)</h5>
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
                                                                for="emp_access_ship_all2">All Options</label>
                                                        </div>
                                                        <br>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_test[]"
                                                                id="emp_access_test0" value="0"><label
                                                                class="ml-2" for="emp_access_test0">New</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_test[]"
                                                                id="emp_access_test1" value="1"><label
                                                                class="ml-2" for="emp_access_test1">Interested</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_test[]"
                                                                id="emp_access_test2" value="2"><label
                                                                class="ml-2" for="emp_access_test2">Follow
                                                                More</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_test[]"
                                                                id="emp_access_test3" value="3"><label
                                                                class="ml-2" for="emp_access_test3">Asking Low</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_test[]"
                                                                id="emp_access_test4" value="4"><label
                                                                class="ml-2" for="emp_access_test4">Not
                                                                Interested</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_test[]"
                                                                id="emp_access_test5" value="5"><label
                                                                class="ml-2" for="emp_access_test5">No
                                                                Response</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_test[]"
                                                                id="emp_access_test6" value="6"><label
                                                                class="ml-2" for="emp_access_test6">Time Quote</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_test[]"
                                                                id="emp_access_test7" value="7"><label
                                                                class="ml-2" for="emp_access_test7">Paymen
                                                                tMissing</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_test[]"
                                                                id="emp_access_test8" value="8"><label
                                                                class="ml-2" for="emp_access_test8">Booked</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_test[]"
                                                                id="emp_access_test66" value="66"><label
                                                                class="ml-2" for="emp_access_test66">Double
                                                                Booking</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_test[]"
                                                                id="emp_access_test9" value="9"><label
                                                                class="ml-2" for="emp_access_test9">Listed</label>
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
                                                                class="ml-2" for="emp_access_test16">Owes
                                                                Money</label>
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
                                                                class="ml-2" for="emp_access_test19">Heavy
                                                                Quote</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_test[]"
                                                                id="emp_access_test92" value="92"><label
                                                                class="ml-2" for="emp_access_test92">Freight
                                                                Quote</label>
                                                        </div <div class="col-sm-6">
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
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test112" value="112"><label
                                                            class="ml-2" for="emp_access_test112">Message
                                                            Chats</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test116" value="116"><label
                                                            class="ml-2" for="emp_access_test116">Logout
                                                            Questions</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test117" value="117"><label
                                                            class="ml-2" for="emp_access_test117">Logout Questions
                                                            Answer View</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test118" value="118"><label
                                                            class="ml-2" for="emp_access_test118">Logout Questions
                                                            Comments</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test119" value="119"><label
                                                            class="ml-2" for="emp_access_test119">Logout Questions
                                                            Answer</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test121" value="121"><label
                                                            class="ml-2" for="emp_access_test121">Show Pickup
                                                            Phone</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test122" value="122"><label
                                                            class="ml-2" for="emp_access_test122">Show Delivery
                                                            Phone</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test123" value="123"><label
                                                            class="ml-2" for="emp_access_test123">Request Price
                                                            Page</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test124" value="124"><label
                                                            class="ml-2" for="emp_access_test124">Request Price
                                                            Page</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test125" value="125"><label
                                                            class="ml-2" for="emp_access_test125">Request Price
                                                            Page</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test126" value="126"><label
                                                            class="ml-2" for="emp_access_test126">Allow Price
                                                            Giver</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test128" value="128"><label
                                                            class="ml-2" for="emp_access_test128">Employee Revenue
                                                            (OT)</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test127" value="127"><label
                                                            class="ml-2" for="emp_access_test127">Employee Revenue
                                                            (DB)</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test129" value="129"><label
                                                            class="ml-2" for="emp_access_test129">Employee Revenue
                                                            (DIS)</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test130" value="130"><label
                                                            class="ml-2" for="emp_access_test130">Employee Revenue
                                                            (Private OT)</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test131" value="131"><label
                                                            class="ml-2" for="emp_access_test131">Cpanel
                                                            Emails</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test132" value="132"><label
                                                            class="ml-2" for="emp_access_test132">Agents
                                                            Reports</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test133" value="133"><label
                                                            class="ml-2" for="emp_access_test133">Customer
                                                            Reviews</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test134" value="134"><label
                                                            class="ml-2" for="emp_access_test134">Call/SMS With App</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test135" value="135"><label
                                                            class="ml-2" for="emp_access_test135">Call/SMS Old</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test149" value="149"><label
                                                            class="ml-2" for="emp_access_test149">How Did You Find Us?</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_test[]"
                                                            id="emp_access_test150" value="150"><label
                                                            class="ml-2" for="emp_access_test150">How Did You Find Us? Phone</label>
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
                                        <h5 class="modal-title" id="exampleModalLabel3">Employee Access (Show Data)</h5>
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
                                        <h5 class="modal-title" id="exampleModalLabel4">Employee Access (Shipment
                                            Status)</h5>
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
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_action[]"
                                                            id="emp_access_action109" value="109"><label
                                                            class="ml-2" for="emp_access_action109">View Cancel
                                                            History</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="checkbox" name="emp_access_action[]"
                                                            id="emp_access_action111" value="111"><label
                                                            class="ml-2" for="emp_access_action111">Allow Check Price Btn</label>
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
                            {{-- Panel Type Access Modal --}}
                            <div class="modal fade" id="exampleModa28" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel28" aria-hidden="true">
                                <div class="modal-dialog" role="document" style="max-width: 55%;">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel28">Panel Type Access</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <input type="checkbox" name="emp_panel_access[]" id="emp_panel_access1" value="1">
                                                            <label class="ml-2" for="emp_panel_access1">Auction</label>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="checkbox" name="emp_panel_access[]" id="emp_panel_access2" value="2">
                                                            <label class="ml-2" for="emp_panel_access2">ProMAx</label>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="checkbox" name="emp_panel_access[]" id="emp_panel_access3" value="3">
                                                            <label class="ml-2" for="emp_panel_access3">Testing</label>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="checkbox" name="emp_panel_access[]" id="emp_panel_access4" value="4">
                                                            <label class="ml-2" for="emp_panel_access4">Shipa1-Website</label>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="checkbox" name="emp_panel_access[]" id="emp_panel_access5" value="5">
                                                            <label class="ml-2" for="emp_panel_access5">Panel Type 5</label>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="checkbox" name="emp_panel_access[]" id="emp_panel_access6" value="6">
                                                            <label class="ml-2" for="emp_panel_access6">Panel Type 6</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Guides Modal --}}
                            <div class="modal fade" id="exampleModal9" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel9" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel9">Employee Access (Guides)</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="row">
                                                        @foreach ($guide as $row)
                                                            <div class="col-sm-12">
                                                                <input type="checkbox" name="emp_access_guide[]"
                                                                    id="emp_access_guide{{ $row->id }}"
                                                                    value="{{ $row->id }}">
                                                                <label class="ml-2" for="emp_access_guide{{ $row->id }}">{{ $row->page_name }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div class="col-sm-12 col-md-12 mt-3">
                            <div class="form-group">
                                <input type="radio" checked name="penalytype" value="1"> Phone Quotes
                                <br>
                                <input type="radio" name="penalytype" value="2"> Website Quotes
                                <br>
                                <input type="radio" name="penalytype" value="3"> Test Quotes
                                <br>
                                <input type="radio" name="penalytype" value="4"> Panel Type 4
                                <br>
                                <input type="radio" name="penalytype" value="5"> Panel Type 5
                                <br>
                                <input type="radio" name="penalytype" value="6"> Panel Type 6
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Address</label>
                                <input type="text" required name="address" class="form-control"
                                    placeholder="Home Address">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-footer text-center">
                    <button id="sv_btn" type="submit" class="btn  btn-primary">SAVE</button>
                </div>
            </div>
        </div>
        </div>
        <!-- End Row-->
    </form>

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
        $(document).ready(function(e) {
            $("#form").on('submit', (function(e) {
                e.preventDefault();
                $.ajax({
                    url: "/save_employee",
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function() {

                    },
                    success: function(data) {

                        // view uploaded file.
                        //$("#preview").html(data).fadeIn();

                        let test = data.toString();
                        let test2 = $.trim(test);
                        let text = "SUCCESS";
                        if (test2 == text) {
                            $('#success').html(data);
                            $('#modaldemo4').modal('show');
                            window.location.href = "/view_employee";
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
        });

        $("body").delegate("#phoneNumber", "focus", function() {
            $("#phoneNumber").mask("9999-9999999");
            $("#phoneNumber")[0].setSelectionRange(0, 0);
        });

        $("input[name='phone_number']").keypress(function(e) {

            var x = e.which || e.keycode;
            if ((x >= 48 && x <= 57) || x == 8 ||
                (x >= 35 && x <= 40) || x == 46)
                return true;
            else
                return false;
        })
        $("input[name='assign_daily_qoute']").keypress(function(e) {

            var x = e.which || e.keycode;
            if ((x >= 48 && x <= 57) || x == 8 ||
                (x >= 35 && x <= 40) || x == 46)
                return true;
            else
                return false;
        })
    </script>
    <script>
        $('select[name="job_type"]').change(function() {
            var role_id = $(this).val();
            var role = $(this).children('option:selected').text();

            $('input:checkbox').removeAttr('checked');

            $.ajax({
                url: "/role-access",
                type: "POST",
                dataType: "json",
                data: {
                    role_id: role_id
                },
                success: function(res) {
                    if (res.data.phone) {
                        $.each(res.data.phone, function() {
                            if ($(`#emp_access_phone${this}`).val() == this) {
                                $(`#emp_access_phone${this}`).attr("checked", "checked");
                            }
                        });
                    }
                    if (res.data.web) {

                        $.each(res.data.web, function() {
                            if ($(`#emp_access_web${this}`).val() == this) {
                                $(`#emp_access_web${this}`).attr("checked", "checked");
                            }
                        });
                    }
                    if (res.data.show) {

                        $.each(res.data.show, function() {
                            if ($(`#emp_show_data${this}`).val() == this) {
                                $(`#emp_show_data${this}`).attr("checked", "checked");
                            }
                        });
                    }
                    if (res.data.ship) {

                        $.each(res.data.ship, function() {
                            if ($(`#emp_access_ship${this}`).val() == this) {
                                $(`#emp_access_ship${this}`).attr("checked", "checked");
                            }
                        });
                    }
                    if (res.data.profile) {

                        $.each(res.data.profile, function() {
                            if ($(`#emp_access_profile${this}`).val() == this) {
                                $(`#emp_access_profile${this}`).attr("checked", "checked");
                            }
                        });
                    }
                    if (res.data.action) {

                        $.each(res.data.action, function() {
                            if ($(`#emp_access_action${this}`).val() == this) {
                                $(`#emp_access_action${this}`).attr("checked", "checked");
                            }
                        });
                    }
                    if (res.data.report) {

                        $.each(res.data.report, function() {
                            if ($(`#emp_access_report${this}`).val() == this) {
                                $(`#emp_access_report${this}`).attr("checked", "checked");
                            }
                        });
                    }
                }
            });
            // hide role-specific fields by default
            $("#private_ot_field").hide();
            $("#per_review_field").hide();
            $("#private_pickup_field").hide();

            if (role == 'CSR' || role == 'Seller Agent' || role == 'Order Taker') {
                $("#client_number").show();
                $("#qoutes").show();
                $("#all_ot").hide();
                $("#manager").hide();
                $("#assign_daily_qoute").show();
                $("#group_qoutes").show();
                $("#auto_assigning").hide();
                $("#dispatcher_quotes").hide();
                if (role == 'Order Taker') {
                    $("#private_ot_field").show();
                }
            } else if (role == 'Manager') {
                $("#client_number").hide();
                $("#qoutes").show();
                $("#all_ot").show();
                $("#manager").hide();
                $("#assign_daily_qoute").show();
                $("#group_qoutes").hide();
                $("#auto_assigning").hide();
                $("#dispatcher_quotes").hide();
            } else if (role == 'Dispatcher' || role == 'Delivery Boy') {
                if (role == 'Dispatcher') {
                    $("#auto_assigning").show();
                    $("#dispatcher_quotes").show();
                } else {
                    $("#auto_assigning").hide();
                    $("#dispatcher_quotes").hide();
                    $("#per_review_field").show();
                    $("#private_pickup_field").show();
                }
                $("#client_number").hide();
                $("#qoutes").show();
                $("#all_ot").hide();
                $("#manager").hide();
                $("#assign_daily_qoute").show();
                $("#group_qoutes").hide();
            } else {
                $("#client_number").hide();
                $("#qoutes").hide();
                $("#all_ot").hide();
                $("#manager").hide();
                $("#assign_daily_qoute").hide();
                $("#group_qoutes").hide();
                $("#auto_assigning").hide();
                $("#dispatcher_quotes").hide();
            }
        });

        $("input[name='order_taker_quote']").change(function() {
            if ($(this).val() == 2) {
                $("#manager").show();
            } else {
                $("#manager").hide();
            }
        })

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
