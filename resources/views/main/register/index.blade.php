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
                            @php
                            $options_phone = [
                            0=>'New',1=>'Interested',2=>'Follow More',3=>'Asking Low',4=>'Not Interested',5=>'No Response',6=>'Time Quote',7=>'Paymen tMissing',
                            8=>'Booked',66=>'Double Booking',9=>'Listed',10=>'Schedule',11=>'Pickup',12=>'Delivered',13=>'Completed',14=>'Cancel',15=>'Deleted',
                            16=>'Owes Money',17=>'Carrier Update',18=>'Car Quote',19=>'Heavy Quote',92=>'Freight Quote',20=>'Add/Edit Employee',21=>'Admin Issues',
                            22=>'Old Quotes',23=>'Transportation Invoice',73=>'Roro Invoice',24=>'Carriers',25=>'View Emails',26=>'Show Data',27=>'Sheets',
                            28=>'On Approval',29=>'OnApproval Cancel',30=>'Approaching',31=>'Payment System',32=>'Employee Reports',33=>'Price Per Mile',
                            34=>'Filtered Data',35=>'Group',36=>'Questions/Answers',37=>'New Show Data',38=>'Customer',
                            41=>'Update Phone Digits',42=>'Show Customer Number',60=>'Show Driver Number',43=>'Flag Employees',44=>'Transfer Quotes',46=>'Revenue',
                            47=>'Coupons',48=>'Website Links',49=>'Feedbacks',
                            50=>'Managers Group',51=>'Last Activity',52=>'Login Ip Address',53=>'Storage',54=>'Shipment Status',55=>'Dispatch Report',
                            56=>'Employee Rating',57=>'Performance Report',
                            62=>'QA Report',63=>'Roles',64=>'Update QA History',65=>'View QA History',
                            68=>'Approaching Number Phone',69=>'Approaching Number Website',
                            70=>'Approaching Assign <span class="badge badge-warning">New</span>',
                            142=>'Approaching Filter  <span class="badge badge-warning">New</span>',
                            145=>'Auto Approach Assign <span class="badge badge-warning">New</span>',
                            146=>'Auto Approach  Filter  <span class="badge badge-warning">New</span>',
                            71=>'Booker Name',72=>'Offer Price',74=>'Achievement Sheet View',
                            111=>'Achievement Sheet Add/Edit',107=>'Achievement Sheet View Full Screen ',
                            75=>'Port Price',76=>'Assign To Dispatcher',77=>'Move OnApprovalCancel To Cancel',
                            79=>'Profile',
                            85=>'Commission Range',86=>'Employee Profile Filter',87=>'Break Time',88=>'Freeze Time History',
                            89=>'Payment System Advance Filter',90=>'Demand Order',91=>'Sell Invoice',93=>'Freight Price checker',
                            94=>'Access Auto Approach',100=>'Field Labels',101=>'Carrier Approaching Update',102=>'Carrier Approaching View',
                            103=>'Carrier Blocking',104=>'Whatsapp Access',105=>'Customer Nature (View/Update)',106=>'Customer Nature List/Filter',
                            109=>'Authorization Form List',110=>'Testing Quote',112=>'Message Chats',113=>'Allow Vehicle',
                            114=>'Allow Heavy',115=>'Allow Freight',
                            116=>'Logout Questions (Show Logout Questions)',117=>'Logout Questions Answer View',118=>'Logout Questions Comments',
                            120=>'Logout Questions View & Add',121=>'Show Pickup Phone ',122=>'Show Delivery Phone ',123=>'Request Price Page ',
                            124=>'Block Phone View ',125=>'Block Phone Approve ',
                            128=>'Employee Revenue (OT) ',127=>'Employee Revenue (DB) ',129=>'Employee Revenue (DIS) ',
                            130=>'Employee Revenue (Private OT)',131=>'Cpanel Emails',132=>'Agents Reports',133=>'Customer Reviews',
                            134=>'Call/SMS With App',135=>'Call/SMS Old',
                            143=>'Day Dispatch C|S|B | Assign<span class="badge badge-warning">New</span>',
                            136=>'Day Dispatch C|S|B | Filter <span class="badge badge-warning">New</span>',
                            137=>'Day Dispatch view  | Shipper <span class="badge badge-warning">New</span>',
                            138=>'Day Dispatch view | Carrier <span class="badge badge-warning">New</span>',
                            139=>'Day Dispatch view | Broker <span class="badge badge-warning">New</span>',
                            140=>'Dealer Approaching view <span class="badge badge-warning">New</span>',
                            144=>'Dealer Approaching Assign <span class="badge badge-warning">New</span>',
                            141=>'Dealer Approaching Filter<span class="badge badge-warning">New</span>',
                            147=>'Shipa1 Query<span class="badge badge-warning">New</span>',
                            148=>'Shipa1 Query Assign<span class="badge badge-warning">New</span>',
                            149=>'How Did You Find Us?<span class="badge badge-warning">New</span>',
                            150=>'How Did You Find Us? Phone<span class="badge badge-warning">New</span>',
                            151=>'Chat Support<span class="badge badge-warning">New</span>',
                            152=>'Templates<span class="badge badge-warning">New</span>',
                            153=>'Profile Card<span class="badge badge-warning">New</span>',
                            154=>'Manager Check Price<span class="badge badge-warning">New</span>',
                            155=>'Carrier Approaching view <span class="badge badge-warning">New</span>',
                            156=>'Carrier Approaching Assign <span class="badge badge-warning">New</span>',
                            157=>'Carrier Approaching Filter<span class="badge badge-warning">New</span>',
                            158=>'Zoom App',
                            159=>'Washington Gateway Portal<span class="badge badge-warning">New</span>',
                            160=>'AutoHaul Gateway Portal<span class="badge badge-warning">New</span>',
                            161=>'Commission Report<span class="badge badge-warning">New</span>',
                            162=>'cPanel Email Management<span class="badge badge-danger">cPanel</span>',
                            163=>'View Mailbox<span class="badge badge-danger">cPanel</span>',
                            164=>'Admin Payment System<span class="badge badge-success">Payments</span>',
                            165=>'Agent Payment System<span class="badge badge-success">Payments</span>',
                            ];

                            $modals_dynamic = [
                            [
                            'id'=>'exampleModal1',
                            'title'=>'Employee Access (Phone Qoutes)',
                            'name'=>'emp_access_phone',
                            'selected'=>[],
                            'prefix'=>'emp_access_phone',
                            'all_id'=>'emp_access_ship_all1',
                            'options'=>$options_phone,
                            ],
                            [
                            'id'=>'exampleModal2',
                            'title'=>'Employee Access (Webiste Qoutes)',
                            'name'=>'emp_access_web',
                            'selected'=>[],
                            'prefix'=>'emp_access_web',
                            'all_id'=>'emp_access_ship_all2',
                            'options'=>$options_phone,
                            ],
                            [
                            'id'=>'exampleModa20',
                            'title'=>'Employee Access (Test Qoutes)',
                            'name'=>'emp_access_test',
                            'selected'=>[],
                            'prefix'=>'emp_access_test',
                            'all_id'=>'emp_access_ship_all20',
                            'options'=>$options_phone,
                            ],
                            [
                            'id'=>'exampleModa24',
                            'title'=>'Panel Type 4',
                            'name'=>'panel_type_4',
                            'selected'=>[],
                            'prefix'=>'panel_type_4',
                            'all_id'=>'emp_access_ship_all24',
                            'options'=>$options_phone,
                            ],
                            [
                            'id'=>'exampleModa25',
                            'title'=>'Panel Type 5',
                            'name'=>'panel_type_5',
                            'selected'=>[],
                            'prefix'=>'panel_type_5',
                            'all_id'=>'emp_access_ship_all25',
                            'options'=>$options_phone,
                            ],
                            [
                            'id'=>'exampleModa26',
                            'title'=>'Panel Type 6',
                            'name'=>'panel_type_6',
                            'selected'=>[],
                            'prefix'=>'panel_type_6',
                            'all_id'=>'emp_access_ship_all26',
                            'options'=>$options_phone,
                            ],
                            ];
                            @endphp

                            @foreach ($modals_dynamic as $m)
                            <div class="modal fade" id="{{ $m['id'] }}" tabindex="-1" role="dialog" aria-labelledby="{{ $m['id'] }}Label" aria-hidden="true">
                                <div class="modal-dialog" role="document" style="max-width: 60%;">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="{{ $m['id'] }}Label">{{ $m['title'] }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <input type="checkbox" id="{{ $m['all_id'] }}" class="emp_access_ship_all">
                                                            <label class="ml-2" for="{{ $m['all_id'] }}">All Options</label>
                                                        </div>
                                                        <br>
                                                        @foreach ($m['options'] as $val => $label)
                                                            @php $optId = $m['prefix'] . $val; @endphp
                                                            <div class="col-sm-6">
                                                                <input type="checkbox" name="{{ $m['name'] }}[]" id="{{ $optId }}" value="{{ $val }}">
                                                                <label class="ml-2" for="{{ $optId }}">{!! $label !!}</label>
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
                            @endforeach
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
