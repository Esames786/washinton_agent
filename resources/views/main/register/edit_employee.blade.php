@extends('layouts.innerpages')

@section('template_title')
    Edit Employee
@endsection

@section('content')
    @include('partials.mainsite_pages.return_function')
    <style>
        .lg3-div {

            -ms-flex: 0 0 25%;
            flex: 1 0 30%;
            max-width: 35%;
            margin-bottom: 30px;

        }

        thead.table-dark {
            border: 1px solid black;
        }

        span.select2-selection.select2-selection--multiple {
            height: 50px;
            overflow-y: scroll;
        }
    </style>
    <div class="page-header">
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Edit Employee</b></h1>
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
    <form action="" id="form" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="user_id" value="{{ $data2->id }}">
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Edit Profile</div>
                    </div>
                    <div class="card-body">
                        <div class="card-title font-weight-bold">Basic info:</div>

                        {{-- HR Portal Link Section --}}
                        @if (Auth::user()->role == 1 || in_array("20", explode(',', Auth::user()->emp_access_phone)))
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="alert alert-light border d-flex align-items-center justify-content-between py-2 px-3" style="background:#f8f9fa;">
                                    <div>
                                        <strong>Washington User ID:</strong>
                                        <span class="badge badge-secondary ml-1" style="font-size:14px;">{{ $data2->id }}</span>
                                        <small class="text-muted ml-2">— Use this as <strong>Agent ID</strong> when linking in the HR portal (<code>hr_employees.agent_id</code>)</small>
                                    </div>
                                    <a href="{{ url('/hr-portal/' . $data2->id) }}"
                                       target="_blank"
                                       class="btn btn-sm btn-success ml-3"
                                       style="white-space:nowrap;">
                                        🔗 Open HR Portal
                                    </a>
                                </div>
                                @if (session('hr_portal_error'))
                                    <div class="alert alert-danger py-1 px-3 mt-1">
                                        <small>{{ session('hr_portal_error') }}</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" value="{{ $data2->name }}" required name="first_name"
                                           class="form-control" placeholder="First Name">
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" value="{{ $data2->last_name }}" name="last_name"
                                           class="form-control" placeholder="Last Name" required>
                                </div>
                            </div>

                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Sudo Name</label>
                                    <input type="text" value="{{ $data2->slug }}" name="slug" required
                                           class="form-control" placeholder="Sudo Name">
                                </div>
                            </div>
                            <div class="col-sm-5 col-md-5">
                                <div class="form-group">
                                    <label class="form-label">Email address</label>
                                    <input type="email" value="{{ $data2->email }}" required name="email"
                                           class="form-control" placeholder="Email">
                                </div>
                            </div>
                            <div class="col-sm-5 col-md-5">
                                <div class="form-group">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" required name="phone_number" id="phoneNumber"
                                           class="form-control W-100" placeholder="Phone Number" value="{{ $data2->phone }}"
                                           onfocus="$(this).attr('autocomplete', 'off');">
                                </div>
                            </div>
                            {{-- for order taker --}}
                            {{-- @if ($data2->role == 2)
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Cancellation Amount</label>
                                    <input type="number" required name="cancellation_amount" min="0" id="cancellation_amount"
                                        class="form-control W-100" value="{{ $data2->cancellation_amount }}">
                                </div>
                            </div>
                            @endif --}}
                            {{-- @if ($data2->role == 2)
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Cancellation Amount</label>
                                    <input type="number" required name="cancellation_amount" min="0" id="cancellation_amount"
                                        class="form-control W-100" value="{{ $data2->cancellation_amount }}">
                                </div>
                            </div>
                            @endif --}}
                            {{-- for delivery boy --}}
                            @if ($data2->role == 8)
                                <div class="col-sm-2 col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Commission Per Delivery</label>
                                        <input type="number" required name="commission" min="0" id="commission"
                                               class="form-control W-100" value="{{ $data2->commission }}">
                                    </div>
                                </div>
                                <div class="col-sm-2 col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Per_review</label>
                                        <input type="number" required name="per_review" min="0" id="per_review"
                                               class="form-control W-100" value="{{ $data2->per_review }}">
                                    </div>
                                </div>
                                <div class="col-sm-2 col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Private_pickup</label>
                                        <input type="number" required name="private_pickup" min="0"
                                               id="private_pickup" class="form-control W-100"
                                               value="{{ $data2->private_pickup }}">
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" required>JOB TYPE</label>
                                    <select class="form-control select2" name="job_type">
                                        <option value="" selected disabled="">Select Job Type</option>
                                        @foreach ($data as $val)
                                            <option @if ($data2->role == $val->id) selected @endif
                                            value="{{ $val->id }}">{{ $val->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Updted Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Password">
                                </div>
                            </div>
                            <div class="col-md-4" id="sheet_access">
                                <div class="form-group">
                                    <label class="form-label">Sheet Access</label>
                                    <?php $sd = explode(',', $data2->sheet_access); ?>
                                    <select name="sheet_access[]" class="select2 form-control" multiple>
                                        @foreach ($sheet_data as $key => $val)
                                            <option value="{{ $val->id }}"
                                                    {{ in_array($val->id, $sd) ? 'selected' : '' }}>
                                                {{ date('M-d-Y', strtotime($val->created_at)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            @if ($data2->role == 2)
                                <div class="col-sm-4 my-auto">
                                    <div class="form-group d-flex m-0">
                                        <input type="checkbox" value="1"
                                               @if ($data2->private_OT == 1) checked @endif name="private_OT"
                                               id="private_OT" />
                                        <label class="form-label my-auto mx-1" for="private_OT">Private OT</label>
                                    </div>
                                </div>
                            @endif

                            @php
                                //panel type access
                                $emp_panel_access = $data2->emp_panel_access;
                                $emp_panel_access = explode(',', $emp_panel_access);
                            @endphp

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Employee Access</label>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#exampleModa28">Panel Type Access</button>
                                        @if (in_array('1', $emp_panel_access))
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#exampleModal1">Auction</button>
                                        @endif
                                        @if (in_array('2', $emp_panel_access))
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#exampleModal2">ProMax</button>
                                        @endif
                                        @if (in_array('3', $emp_panel_access))
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#exampleModa20">Testing Quotes</button>
                                        @endif
                                        @if (in_array('4', $emp_panel_access))
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#exampleModa24">Shipa1 Website</button>
                                        @endif
                                        @if (in_array('5', $emp_panel_access))
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#exampleModa25">Panel Type 5 Quotes</button>
                                        @endif
                                        @if (in_array('6', $emp_panel_access))
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#exampleModa26">Panel Type 6 Quotes</button>
                                        @endif
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
                                        {{-- <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#exampleModal8">Assigned Data</button> --}}
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#exampleModal9">Guides</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12"
                                 @if (isset($data2->userRole)) @if (
                                    $data2->userRole->name == 'CSR' ||
                                        $data2->userRole->name == 'Seller Agent' ||
                                        $data2->userRole->name == 'Order Taker') style="display:block;" @else style="display:none;" @endif
                                 @else style="display:none;" @endif id="client_number">
                                <div class="form-group">
                                    <label class="form-label">Phone Numbers Access</label>

                                </div>
                            </div>
                            <div class="col-md-12"
                                 @if (isset($data2->userRole)) @if (
                                    $data2->userRole->name == 'CSR' ||
                                        $data2->userRole->name == 'Seller Agent' ||
                                        $data2->userRole->name == 'Order Taker' ||
                                        $data2->userRole->name == 'Manager' ||
                                        $data2->userRole->name == 'Dispatcher') style="display:block;" @else style="display:none;" @endif
                                 @else style="display:none;" @endif id="assign_daily_qoute">
                                <div class="form-group">
                                    <label class="form-label">Assign Daily Qoutes</label>
                                    <input type="text" class="form-control" name="assign_daily_qoute" maxlength="2"
                                           value="{{ $data2->assign_daily_qoute }}"
                                           placeholder="Enter Assign Daily Qoutes" />
                                </div>
                            </div>
                            <div class="col-md-12"
                                 @if (isset($data2->userRole)) @if ($data2->userRole->name != 'Dispatcher') style="display:none;" @endif
                                 @else style="display:none;" @endif id="auto_assigning">
                                <div class="form-group">
                                    <label class="form-label">Auto Assign</label>
                                    <select name="auto_assign" class="form-control">
                                        <option value="0" @if ($data2->auto_assign == 0) selected @endif>No
                                        </option>
                                        <option value="1" @if ($data2->auto_assign == 1) selected @endif>Yes
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12"
                                 @if (isset($data2->userRole)) @if (
                                    $data2->userRole->name == 'CSR' ||
                                        $data2->userRole->name == 'Seller Agent' ||
                                        $data2->userRole->name == 'Order Taker' ||
                                        $data2->userRole->name == 'Dispatcher' ||
                                        $data2->userRole->name == 'Delivery Boy' ||
                                        $data2->userRole->name == 'Manager') style="display:block;" @else style="display:none;" @endif
                                 @else style="display:none;" @endif id="qoutes">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Qoutes Assign</label>
                                        <div class="row">
                                            <div class="col-sm-4 my-auto">
                                                <div class="form-group d-flex m-0">
                                                    <input type="radio" value="0"
                                                           @if ($data2->order_taker_qoute == 0) checked @endif
                                                           name="order_taker_quote" id="all_qoute" />
                                                    <label class="form-label my-auto mx-1" for="all_qoute">All
                                                        Qoutes</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 my-auto">
                                                <div class="form-group d-flex m-0">
                                                    <input type="radio" value="1"
                                                           @if ($data2->order_taker_qoute == 1) checked @endif
                                                           name="order_taker_quote" id="own_qoute" />
                                                    <label class="form-label my-auto mx-1" for="own_qoute">Own
                                                        Qoutes</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 my-auto"
                                                 @if (isset($data2->userRole)) @if (
                                                    $data2->userRole->name == 'CSR' ||
                                                        $data2->userRole->name == 'Seller Agent' ||
                                                        $data2->userRole->name == 'Order Taker') style="display:block;" @else style="display:none;" @endif
                                                 @else style="display:none;" @endif id="manager">
                                                <div class="form-group d-flex m-0">
                                                    <input type="radio" value="2"
                                                           @if ($data2->order_taker_qoute == 2) checked @endif
                                                           name="order_taker_quote" id="group_qoute" />
                                                    <label class="form-label my-auto mx-1" for="group_qoute">Group
                                                        Qoutes</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6"
                                         @if (isset($data2->userRole)) @if ($data2->userRole->name == 'Dispatcher') style="display:block;" @else style="display:none;" @endif
                                         @else style="display:none;" @endif id="dispatcher_quotes">
                                        <label class="form-label">Qoutes Assign For (Shipment Status Requests)</label>
                                        <div class="row">
                                            <div class="col-sm-6 my-auto">
                                                <div class="form-group d-flex m-0">
                                                    <input type="radio" value="0"
                                                           @if ($data2->shipment_status_quote_assign == 0) checked @endif
                                                           name="shipment_status_quote_assign" id="all_dis_qoute" />
                                                    <label class="form-label my-auto mx-1" for="all_dis_qoute">All
                                                        Qoutes</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 my-auto">
                                                <div class="form-group d-flex m-0">
                                                    <input type="radio" value="1"
                                                           @if ($data2->shipment_status_quote_assign == 1) checked @endif
                                                           name="shipment_status_quote_assign" id="own_dis_qoute" />
                                                    <label class="form-label my-auto mx-1" for="own_dis_qoute">Own
                                                        Qoutes</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6"
                                         @if ($data2->order_taker_qoute == 2) style="display:block;" @else style="display:none;" @endif
                                         id="manager">
                                        <div class="form-group m-0">
                                            <label class="form-label">Managers</label>
                                            <select name="manager" class="select2 form-control">
                                                @foreach ($managers as $key => $val)
                                                    <option value="{{ $val->id }}"
                                                            @if (isset($data2->ot_manager->id)) @if ($data2->ot_manager->manager_id == $val->id)
                                                                selected @endif
                                                            @endif
                                                    >{{ $val->slug }} ({{ $val->userRole->name }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12"
                                 @if (isset($data2->userRole)) @if ($data2->userRole->name == 'Manager') style="display:block;" @else style="display:none;" @endif
                                 @else style="display:none;" @endif id="all_ot">
                                <div class="form-group">
                                    <label class="form-label">CSRs And Seller Agents</label>
                                    <select name="all_ot[]" class="select2 form-control" multiple>
                                        @foreach ($all_ot as $key => $val)
                                            <option value="{{ $val->id }}"
                                                    @if (isset($access[0])) @foreach ($access as $ids)
                                                        @if ($ids->ot_ids == $val->id)
                                                            selected @endif
                                                    @endforeach
                                                    @endif
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
                            <?php
                            //user access phone quote
                            $emp_access_phone = $data2->emp_access_phone;
                            $emp_access_phone = explode(',', $emp_access_phone);
                            //user access website quote
                            $emp_access_web = $data2->emp_access_web;
                            $emp_access_web = explode(',', $emp_access_web);
                            //user access test quote
                            $emp_access_test = $data2->emp_access_test;
                            $emp_access_test = explode(',', $emp_access_test);
                            //user access panel type 4 quote
                            $panel_type_4 = $data2->panel_type_4;
                            $panel_type_4 = explode(',', $panel_type_4);
                            //user access panel type 5 quote
                            $panel_type_5 = $data2->panel_type_5;
                            $panel_type_5 = explode(',', $panel_type_5);
                            //user access panel type 6 quote
                            $panel_type_6 = $data2->panel_type_6;
                            $panel_type_6 = explode(',', $panel_type_6);
                            //user access show data
                            $emp_show_data = $data2->emp_show_data;
                            $emp_show_data = explode(',', $emp_show_data);
                            //user access shipment status
                            $emp_shipment_status = $data2->emp_access_ship;
                            $emp_shipment_status = explode(',', $emp_shipment_status);
                            //user access profile
                            $emp_profile = $data2->emp_access_profile;
                            $emp_profile = explode(',', $emp_profile);
                            //user access action
                            $emp_action = $data2->emp_access_action;
                            $emp_action = explode(',', $emp_action);
                            //user access action
                            $emp_report = $data2->emp_access_report;
                            $emp_report = explode(',', $emp_report);
                            //user access guide
                            $emp_access_guide = $data2->emp_access_guide;
                            $emp_access_guide = explode(',', $emp_access_guide);
                            //panel type access
                            $emp_panel_access = $data2->emp_panel_access;
                            $emp_panel_access = explode(',', $emp_panel_access);
                            ?>
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
                            109=>'Authorization Form List',110=>'Testing Quote',111=>'Port Tracking',112=>'Message Chats',113=>'Allow Vehicle',
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
                            ];


                            // ===== MODALS CONFIG (mapping exactly as you requested) =====
                            $modals = [
                            [
                            'id'=>'exampleModal1',
                            'title'=>'Employee Access (Phone Qoutes)',
                            'name'=>'emp_access_phone',
                            'selected'=>$emp_access_phone,
                            'prefix'=>'emp_access_phone',
                            'all_id'=>'emp_access_ship_all1',
                            'options'=>$options_phone,
                            ],
                            [
                            'id'=>'exampleModal2',
                            'title'=>'Employee Access (Webiste Qoutes)',
                            'name'=>'emp_access_web',
                            'selected'=>$emp_access_web,
                            'prefix'=>'emp_access_web',
                            'all_id'=>'emp_access_ship_all2',
                            'options'=>$options_phone,
                            ],
                            [
                            'id'=>'exampleModa20',
                            'title'=>'Employee Access (Test Qoutes)',
                            'name'=>'emp_access_test',
                            'selected'=>$emp_access_test,
                            'prefix'=>'emp_access_test',
                            'all_id'=>'emp_access_ship_all20',
                            'options'=>$options_phone,
                            ],
                            [
                            'id'=>'exampleModa24',
                            'title'=>'Panel Type 4',
                            'name'=>'panel_type_4',
                            'selected'=>$panel_type_4,
                            'prefix'=>'panel_type_4',
                            'all_id'=>'emp_access_ship_all24',
                            'options'=>$options_phone,
                            ],
                            [
                            'id'=>'exampleModa25',
                            'title'=>'Panel Type 5',
                            'name'=>'panel_type_5',
                            'selected'=>$panel_type_5,
                            'prefix'=>'panel_type_5',
                            'all_id'=>'emp_access_ship_all25',
                            'options'=>$options_phone,
                            ],
                            [
                            'id'=>'exampleModa26',
                            'title'=>'Panel Type 6',
                            'name'=>'panel_type_6',
                            'selected'=>$panel_type_6,
                            'prefix'=>'panel_type_6',
                            'all_id'=>'emp_access_ship_all26',
                            'options'=>$options_phone,
                            ],
                            ];
                            @endphp

                            @foreach ($modals as $m)
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
                                                                @php
                                                                    $id = $m['prefix'] . $val; // e.g., emp_access_phone0
                                                                    $isChecked = in_array((string)$val, $m['selected'] ?? [], true);
                                                                @endphp
                                                                <div class="col-sm-6">
                                                                    <input type="checkbox"
                                                                           name="{{ $m['name'] }}[]"
                                                                           id="{{ $id }}"
                                                                           value="{{ $val }}"
                                                                           @if ($isChecked) checked @endif>
                                                                    <label class="ml-2" for="{{ $id }}">{!! $label !!}</label>
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
                                            <h5 class="modal-title" id="exampleModalLabel3">Employee Access (Show Data)
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
                                                            <input type="checkbox" id="emp_access_ship_all3"
                                                                   class="emp_access_ship_all"><label class="ml-2"
                                                                                                      for="emp_access_ship_all3">All Options</label>
                                                        </div>
                                                        <br>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('1', $emp_show_data)) {{ 'checked' }} @endif
                                                                   name="emp_show_data[]" id="emp_show_data1"
                                                                   value="1"><label class="ml-2"
                                                                                    for="emp_show_data1">New</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('2', $emp_show_data)) {{ 'checked' }} @endif
                                                                   name="emp_show_data[]" id="emp_show_data2"
                                                                   value="2"><label class="ml-2"
                                                                                    for="emp_show_data2">Follow Up</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('3', $emp_show_data)) {{ 'checked' }} @endif
                                                                   name="emp_show_data[]" id="emp_show_data3"
                                                                   value="3"><label class="ml-2"
                                                                                    for="emp_show_data3">Interested</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('4', $emp_show_data)) {{ 'checked' }} @endif
                                                                   name="emp_show_data[]" id="emp_show_data4"
                                                                   value="4"><label class="ml-2"
                                                                                    for="emp_show_data4">Not Interested</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('5', $emp_show_data)) {{ 'checked' }} @endif
                                                                   name="emp_show_data[]" id="emp_show_data5"
                                                                   value="5"><label class="ml-2"
                                                                                    for="emp_show_data5">Asking Low</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('6', $emp_show_data)) {{ 'checked' }} @endif
                                                                   name="emp_show_data[]" id="emp_show_data6"
                                                                   value="6"><label class="ml-2"
                                                                                    for="emp_show_data6">No Responding</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('7', $emp_show_data)) {{ 'checked' }} @endif
                                                                   name="emp_show_data[]" id="emp_show_data7"
                                                                   value="7"><label class="ml-2"
                                                                                    for="emp_show_data7">Time Qoute</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('8', $emp_show_data)) {{ 'checked' }} @endif
                                                                   name="emp_show_data[]" id="emp_show_data8"
                                                                   value="8"><label class="ml-2"
                                                                                    for="emp_show_data8">Payment Missing</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('9', $emp_show_data)) {{ 'checked' }} @endif
                                                                   name="emp_show_data[]" id="emp_show_data9"
                                                                   value="9"><label class="ml-2"
                                                                                    for="emp_show_data9">On Approval</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('10', $emp_show_data)) {{ 'checked' }} @endif
                                                                   name="emp_show_data[]" id="emp_show_data10"
                                                                   value="10"><label class="ml-2"
                                                                                     for="emp_show_data10">On Approval Cancel</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('11', $emp_show_data)) {{ 'checked' }} @endif
                                                                   name="emp_show_data[]" id="emp_show_data11"
                                                                   value="11"><label class="ml-2"
                                                                                     for="emp_show_data11">Booked</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('12', $emp_show_data)) {{ 'checked' }} @endif
                                                                   name="emp_show_data[]" id="emp_show_data12"
                                                                   value="12"><label class="ml-2"
                                                                                     for="emp_show_data12">Listed</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('13', $emp_show_data)) {{ 'checked' }} @endif
                                                                   name="emp_show_data[]" id="emp_show_data13"
                                                                   value="13"><label class="ml-2"
                                                                                     for="emp_show_data13">Schedule</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('14', $emp_show_data)) {{ 'checked' }} @endif
                                                                   name="emp_show_data[]" id="emp_show_data14"
                                                                   value="14"><label class="ml-2"
                                                                                     for="emp_show_data14">Not Picked Up</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('15', $emp_show_data)) {{ 'checked' }} @endif
                                                                   name="emp_show_data[]" id="emp_show_data15"
                                                                   value="15"><label class="ml-2"
                                                                                     for="emp_show_data15">Picked Up</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('16', $emp_show_data)) {{ 'checked' }} @endif
                                                                   name="emp_show_data[]" id="emp_show_data16"
                                                                   value="16"><label class="ml-2"
                                                                                     for="emp_show_data16">Not Delivered</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('23', $emp_show_data)) {{ 'checked' }} @endif
                                                                   name="emp_show_data[]" id="emp_show_data23"
                                                                   value="23"><label class="ml-2"
                                                                                     for="emp_show_data23">Schedule For Delivery</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('17', $emp_show_data)) {{ 'checked' }} @endif
                                                                   name="emp_show_data[]" id="emp_show_data17"
                                                                   value="17"><label class="ml-2"
                                                                                     for="emp_show_data17">Delivered</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('18', $emp_show_data)) {{ 'checked' }} @endif
                                                                   name="emp_show_data[]" id="emp_show_data18"
                                                                   value="18"><label class="ml-2"
                                                                                     for="emp_show_data18">Completed</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('19', $emp_show_data)) {{ 'checked' }} @endif
                                                                   name="emp_show_data[]" id="emp_show_data19"
                                                                   value="19"><label class="ml-2"
                                                                                     for="emp_show_data19">Cancelled</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('20', $emp_show_data)) {{ 'checked' }} @endif
                                                                   name="emp_show_data[]" id="emp_show_data20"
                                                                   value="20"><label class="ml-2"
                                                                                     for="emp_show_data20">Deleted</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('21', $emp_show_data)) {{ 'checked' }} @endif
                                                                   name="emp_show_data[]" id="emp_show_data21"
                                                                   value="21"><label class="ml-2"
                                                                                     for="emp_show_data21">Owes Money</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('22', $emp_show_data)) {{ 'checked' }} @endif
                                                                   name="emp_show_data[]" id="emp_show_data22"
                                                                   value="22"><label class="ml-2"
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
                                                                   @if (in_array('0', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship0" value="0"><label
                                                                    class="ml-2" for="emp_access_ship0">New</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('1', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship1" value="1"><label
                                                                    class="ml-2" for="emp_access_ship1">Interested</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('2', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship2" value="2"><label
                                                                    class="ml-2" for="emp_access_ship2">Follow
                                                                More</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('3', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship3" value="3"><label
                                                                    class="ml-2" for="emp_access_ship3">Asking Low</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('4', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship4" value="4"><label
                                                                    class="ml-2" for="emp_access_ship4">Not
                                                                Interested</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('5', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship5" value="5"><label
                                                                    class="ml-2" for="emp_access_ship5">No
                                                                Response</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('6', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship6" value="6"><label
                                                                    class="ml-2" for="emp_access_ship6">Time Quote</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('7', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship7" value="7"><label
                                                                    class="ml-2" for="emp_access_ship7">Payment
                                                                Missing</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('8', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship8" value="8"><label
                                                                    class="ml-2" for="emp_access_ship8">Booked</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('18', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship18" value="18"><label
                                                                    class="ml-2"
                                                                    for="emp_access_ship18">OnApproval</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('9', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship9" value="9"><label
                                                                    class="ml-2" for="emp_access_ship9">Listed</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('10', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship10" value="10"><label
                                                                    class="ml-2" for="emp_access_ship10">Schedule</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('34', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship34" value="34"><label
                                                                    class="ml-2" for="emp_access_ship34">Schedule Another
                                                                Driver</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('30', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship30" value="30"><label
                                                                    class="ml-2" for="emp_access_ship30">Pickup
                                                                Approval</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('11', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship11" value="11"><label
                                                                    class="ml-2" for="emp_access_ship11">Pickup</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('31', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship31" value="31"><label
                                                                    class="ml-2" for="emp_access_ship31">Delivered
                                                                Approval</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('32', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship32" value="32"><label
                                                                    class="ml-2" for="emp_access_ship32">Schedule For
                                                                Delivery</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('12', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship12" value="12"><label
                                                                    class="ml-2" for="emp_access_ship12">Delivered</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('19', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship19" value="19"><label
                                                                    class="ml-2"
                                                                    for="emp_access_ship19">OnApprovalCancelled</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('14', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship14" value="14"><label
                                                                    class="ml-2" for="emp_access_ship14">Cancel</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('20', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship20" value="20"><label
                                                                    class="ml-2" for="emp_access_ship20">Relist</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('21', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship21" value="21"><label
                                                                    class="ml-2" for="emp_access_ship21">Price
                                                                Raise</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('22', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship22" value="22"><label
                                                                    class="ml-2" for="emp_access_ship22">Approach
                                                                Id</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('23', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship23" value="23"><label
                                                                    class="ml-2" for="emp_access_ship23">Different
                                                                Port</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('24', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship27" value="24"><label
                                                                    class="ml-2" for="emp_access_ship24">Carrier
                                                                Update</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('25', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship25" value="25"><label
                                                                    class="ml-2" for="emp_access_ship25">Storage</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('26', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship26" value="26"><label
                                                                    class="ml-2"
                                                                    for="emp_access_ship26">Approaching</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('27', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship27" value="27"><label
                                                                    class="ml-2" for="emp_access_ship27">Auction Update
                                                                Request</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('28', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship28" value="28"><label
                                                                    class="ml-2" for="emp_access_ship28">Move To
                                                                Storage</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('29', $emp_shipment_status)) {{ 'checked' }} @endif
                                                                   id="emp_access_ship29" value="29"><label
                                                                    class="ml-2" for="emp_access_ship29">Double
                                                                Booking</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="emp_access_ship[]"
                                                                   @if (in_array('33', $emp_shipment_status)) {{ 'checked' }} @endif
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
                                            <h5 class="modal-title" id="exampleModalLabel5">Employee Access (Profile)
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
                                                            <input type="checkbox" id="emp_access_ship_all5"
                                                                   class="emp_access_ship_all"><label class="ml-2"
                                                                                                      for="emp_access_ship_all5">All Options</label>
                                                        </div>
                                                        <br>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('0', $emp_profile)) {{ 'checked' }} @endif
                                                                   name="emp_access_profile[]" id="emp_access_profile0"
                                                                   value="0"><label class="ml-2"
                                                                                    for="emp_access_profile0">New</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('1', $emp_profile)) {{ 'checked' }} @endif
                                                                   name="emp_access_profile[]" id="emp_access_profile1"
                                                                   value="1"><label class="ml-2"
                                                                                    for="emp_access_profile1">Interested</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('2', $emp_profile)) {{ 'checked' }} @endif
                                                                   name="emp_access_profile[]" id="emp_access_profile2"
                                                                   value="2"><label class="ml-2"
                                                                                    for="emp_access_profile2">Follow More</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('3', $emp_profile)) {{ 'checked' }} @endif
                                                                   name="emp_access_profile[]" id="emp_access_profile3"
                                                                   value="3"><label class="ml-2"
                                                                                    for="emp_access_profile3">Asking Low</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('4', $emp_profile)) {{ 'checked' }} @endif
                                                                   name="emp_access_profile[]" id="emp_access_profile4"
                                                                   value="4"><label class="ml-2"
                                                                                    for="emp_access_profile4">Not Interested</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('5', $emp_profile)) {{ 'checked' }} @endif
                                                                   name="emp_access_profile[]" id="emp_access_profile5"
                                                                   value="5"><label class="ml-2"
                                                                                    for="emp_access_profile5">No Response</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('6', $emp_profile)) {{ 'checked' }} @endif
                                                                   name="emp_access_profile[]" id="emp_access_profile6"
                                                                   value="6"><label class="ml-2"
                                                                                    for="emp_access_profile6">Time Quote</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('7', $emp_profile)) {{ 'checked' }} @endif
                                                                   name="emp_access_profile[]" id="emp_access_profile7"
                                                                   value="7"><label class="ml-2"
                                                                                    for="emp_access_profile7">Payment Missing</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('18', $emp_profile)) {{ 'checked' }} @endif
                                                                   name="emp_access_profile[]" id="emp_access_profile18"
                                                                   value="18"><label class="ml-2"
                                                                                     for="emp_access_profile18">OnApproval</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('8', $emp_profile)) {{ 'checked' }} @endif
                                                                   name="emp_access_profile[]" id="emp_access_profile8"
                                                                   value="8"><label class="ml-2"
                                                                                    for="emp_access_profile8">Booked</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('9', $emp_profile)) {{ 'checked' }} @endif
                                                                   name="emp_access_profile[]" id="emp_access_profile9"
                                                                   value="9"><label class="ml-2"
                                                                                    for="emp_access_profile9">Listed</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('10', $emp_profile)) {{ 'checked' }} @endif
                                                                   name="emp_access_profile[]" id="emp_access_profile10"
                                                                   value="10"><label class="ml-2"
                                                                                     for="emp_access_profile10">Schedule</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('30', $emp_profile)) {{ 'checked' }} @endif
                                                                   name="emp_access_profile[]" id="emp_access_profile30"
                                                                   value="30"><label class="ml-2"
                                                                                     for="emp_access_profile30">Pickup Approval</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('11', $emp_profile)) {{ 'checked' }} @endif
                                                                   name="emp_access_profile[]" id="emp_access_profile11"
                                                                   value="11"><label class="ml-2"
                                                                                     for="emp_access_profile11">Pickup</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('31', $emp_profile)) {{ 'checked' }} @endif
                                                                   name="emp_access_profile[]" id="emp_access_profile31"
                                                                   value="31"><label class="ml-2"
                                                                                     for="emp_access_profile31">Delivered Approval</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('32', $emp_profile)) {{ 'checked' }} @endif
                                                                   name="emp_access_profile[]" id="emp_access_profile32"
                                                                   value="32"><label class="ml-2"
                                                                                     for="emp_access_profile32">Schedule For Delivery</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('12', $emp_profile)) {{ 'checked' }} @endif
                                                                   name="emp_access_profile[]" id="emp_access_profile12"
                                                                   value="12"><label class="ml-2"
                                                                                     for="emp_access_profile12">Delivered</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('13', $emp_profile)) {{ 'checked' }} @endif
                                                                   name="emp_access_profile[]" id="emp_access_profile13"
                                                                   value="13"><label class="ml-2"
                                                                                     for="emp_access_profile13">Completed</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('14', $emp_profile)) {{ 'checked' }} @endif
                                                                   name="emp_access_profile[]" id="emp_access_profile14"
                                                                   value="14"><label class="ml-2"
                                                                                     for="emp_access_profile14">Cancel</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('19', $emp_profile)) {{ 'checked' }} @endif
                                                                   name="emp_access_profile[]" id="emp_access_profile19"
                                                                   value="19"><label class="ml-2"
                                                                                     for="emp_access_profile19">On Approval Cancelled</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('20', $emp_profile)) {{ 'checked' }} @endif
                                                                   name="emp_access_profile[]" id="emp_access_profile20"
                                                                   value="20"><label class="ml-2"
                                                                                     for="emp_access_profile20">Review Order</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('21', $emp_profile)) {{ 'checked' }} @endif
                                                                   name="emp_access_profile[]" id="emp_access_profile21"
                                                                   value="21"><label class="ml-2"
                                                                                     for="emp_access_profile21">Cancel Remark By
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
                                            <h5 class="modal-title" id="exampleModalLabel6">Employee Access (Action)
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
                                                            <input type="checkbox" id="emp_access_ship_all6"
                                                                   class="emp_access_ship_all"><label class="ml-2"
                                                                                                      for="emp_access_ship_all6">All Options</label>
                                                        </div>
                                                        <br>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('1', $emp_action)) {{ 'checked' }} @endif
                                                                   name="emp_access_action[]" id="emp_access_action1"
                                                                   value="1"><label class="ml-2"
                                                                                    for="emp_access_action1">Move To Pickup</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('2', $emp_action)) {{ 'checked' }} @endif
                                                                   name="emp_access_action[]" id="emp_access_action2"
                                                                   value="2"><label class="ml-2"
                                                                                    for="emp_access_action2">Move To Schedule For
                                                                Delivery</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('3', $emp_action)) {{ 'checked' }} @endif
                                                                   name="emp_access_action[]" id="emp_access_action3"
                                                                   value="3"><label class="ml-2"
                                                                                    for="emp_access_action3">Move To Delivered</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('4', $emp_action)) {{ 'checked' }} @endif
                                                                   name="emp_access_action[]" id="emp_access_action4"
                                                                   value="4"><label class="ml-2"
                                                                                    for="emp_access_action4">View/Update</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('5', $emp_action)) {{ 'checked' }} @endif
                                                                   name="emp_access_action[]" id="emp_access_action5"
                                                                   value="5"><label class="ml-2"
                                                                                    for="emp_access_action5">Edit Data</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('6', $emp_action)) {{ 'checked' }} @endif
                                                                   name="emp_access_action[]" id="emp_access_action6"
                                                                   value="6"><label class="ml-2"
                                                                                    for="emp_access_action6">Print Summary</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('7', $emp_action)) {{ 'checked' }} @endif
                                                                   name="emp_access_action[]" id="emp_access_action7"
                                                                   value="7"><label class="ml-2"
                                                                                    for="emp_access_action7">Send Payment Link To
                                                                Customer</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('8', $emp_action)) {{ 'checked' }} @endif
                                                                   name="emp_access_action[]" id="emp_access_action8"
                                                                   value="8"><label class="ml-2"
                                                                                    for="emp_access_action8">View Location</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('9', $emp_action)) {{ 'checked' }} @endif
                                                                   name="emp_access_action[]" id="emp_access_action9"
                                                                   value="9"><label class="ml-2"
                                                                                    for="emp_access_action9">Request</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('10', $emp_action)) {{ 'checked' }} @endif
                                                                   name="emp_access_action[]" id="emp_access_action10"
                                                                   value="10"><label class="ml-2"
                                                                                     for="emp_access_action10">Pay Now</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('11', $emp_action)) {{ 'checked' }} @endif
                                                                   name="emp_access_action[]" id="emp_access_action11"
                                                                   value="11"><label class="ml-2"
                                                                                     for="emp_access_action11">Carrier Record</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('12', $emp_action)) {{ 'checked' }} @endif
                                                                   name="emp_access_action[]" id="emp_access_action12"
                                                                   value="12"><label class="ml-2"
                                                                                     for="emp_access_action12">Storage Record</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('13', $emp_action)) {{ 'checked' }} @endif
                                                                   name="emp_access_action[]" id="emp_access_action13"
                                                                   value="13"><label class="ml-2"
                                                                                     for="emp_access_action13">Move to Storage</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('14', $emp_action)) {{ 'checked' }} @endif
                                                                   name="emp_access_action[]" id="emp_access_action14"
                                                                   value="14"><label class="ml-2"
                                                                                     for="emp_access_action14">Payment Confirmation</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('15', $emp_action)) {{ 'checked' }} @endif
                                                                   name="emp_access_action[]" id="emp_access_action15"
                                                                   value="15"><label class="ml-2"
                                                                                     for="emp_access_action15">Message Center</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('16', $emp_action)) {{ 'checked' }} @endif
                                                                   name="emp_access_action[]" id="emp_access_action16"
                                                                   value="16"><label class="ml-2"
                                                                                     for="emp_access_action16">Call Logs Center</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('17', $emp_action)) {{ 'checked' }} @endif
                                                                   name="emp_access_action[]" id="emp_access_action17"
                                                                   value="17"><label class="ml-2"
                                                                                     for="emp_access_action17">Rating</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('18', $emp_action)) {{ 'checked' }} @endif
                                                                   name="emp_access_action[]" id="emp_access_action18"
                                                                   value="18"><label class="ml-2"
                                                                                     for="emp_access_action18">Delete Order</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('19', $emp_action)) {{ 'checked' }} @endif
                                                                   name="emp_access_action[]" id="emp_access_action19"
                                                                   value="19"><label class="ml-2"
                                                                                     for="emp_access_action19">Feedback</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('20', $emp_action)) {{ 'checked' }} @endif
                                                                   name="emp_access_action[]" id="emp_access_action20"
                                                                   value="20"><label class="ml-2"
                                                                                     for="emp_access_action20">Sheet</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('21', $emp_action)) {{ 'checked' }} @endif
                                                                   name="emp_access_action[]" id="emp_access_action21"
                                                                   value="21"><label class="ml-2"
                                                                                     for="emp_access_action21">View Cancel History</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('108', $emp_action)) {{ 'checked' }} @endif
                                                                   name="emp_access_action[]" id="emp_access_action108"
                                                                   value="108"><label class="ml-2"
                                                                                      for="emp_access_action108">Authorization Form</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('109', $emp_action)) {{ 'checked' }} @endif
                                                                   name="emp_access_action[]" id="emp_access_action109"
                                                                   value="109"><label class="ml-2"
                                                                                      for="emp_access_action109">Revert to New</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('110', $emp_action)) {{ 'checked' }} @endif
                                                                   name="emp_access_action[]" id="emp_access_action110"
                                                                   value="110"><label class="ml-2"
                                                                                      for="emp_access_action110">Allow Price Giver</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('111', $emp_action)) {{ 'checked' }} @endif
                                                                   name="emp_access_action[]" id="emp_access_action111"
                                                                   value="111"><label class="ml-2"
                                                                                      for="emp_access_action111">Allow Check Price Btn</label>
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
                            <div class="modal fade" id="exampleModa28" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalLabel6" aria-hidden="true">
                                <div class="modal-dialog" role="document" style="max-width: 55%;">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel6">Panel Type Access
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
                                                        {{-- <div class="col-sm-12">
                                                            <input type="checkbox" id="emp_access_ship_all6"
                                                                class="emp_access_ship_all"><label class="ml-2"
                                                                for="emp_access_ship_all6">All Options</label>
                                                        </div> --}}
                                                        <br>
                                                        <div class="col-sm-4">
                                                            <input type="checkbox"
                                                                   @if (in_array('1', $emp_panel_access)) {{ 'checked' }} @endif
                                                                   name="emp_panel_access[]" id="emp_panel_access1"
                                                                   value="1"><label class="ml-2"
                                                                                    for="emp_panel_access1">Auction</label>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="checkbox"
                                                                   @if (in_array('2', $emp_panel_access)) {{ 'checked' }} @endif
                                                                   name="emp_panel_access[]" id="emp_panel_access2"
                                                                   value="2"><label class="ml-2"
                                                                                    for="emp_panel_access2">ProMAx</label>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="checkbox"
                                                                   @if (in_array('3', $emp_panel_access)) {{ 'checked' }} @endif
                                                                   name="emp_panel_access[]" id="emp_panel_access3"
                                                                   value="3"><label class="ml-2"
                                                                                    for="emp_panel_access3">Testing</label>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="checkbox"
                                                                   @if (in_array('4', $emp_panel_access)) {{ 'checked' }} @endif
                                                                   name="emp_panel_access[]" id="emp_panel_access4"
                                                                   value="4"><label class="ml-2"
                                                                                    for="emp_panel_access4">Shipa1-Website</label>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="checkbox"
                                                                   @if (in_array('5', $emp_panel_access)) {{ 'checked' }} @endif
                                                                   name="emp_panel_access[]" id="emp_panel_access5"
                                                                   value="5"><label class="ml-2"
                                                                                    for="emp_panel_access5">Panel Type 5</label>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="checkbox"
                                                                   @if (in_array('6', $emp_panel_access)) {{ 'checked' }} @endif
                                                                   name="emp_panel_access[]" id="emp_panel_access6"
                                                                   value="6"><label class="ml-2"
                                                                                    for="emp_panel_access6">Panel Type 6</label>
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
                                                            <input type="checkbox"
                                                                   @if (in_array('0', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report0"
                                                                   value="0"><label class="ml-2"
                                                                                    for="emp_access_report0">New</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('1', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report1"
                                                                   value="1"><label class="ml-2"
                                                                                    for="emp_access_report1">Interested</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('2', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report2"
                                                                   value="2"><label class="ml-2"
                                                                                    for="emp_access_report2">Follow More</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('3', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report3"
                                                                   value="3"><label class="ml-2"
                                                                                    for="emp_access_report3">Asking Low</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('4', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report4"
                                                                   value="4"><label class="ml-2"
                                                                                    for="emp_access_report4">Not Interested</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('5', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report5"
                                                                   value="5"><label class="ml-2"
                                                                                    for="emp_access_report5">No Response</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('6', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report6"
                                                                   value="6"><label class="ml-2"
                                                                                    for="emp_access_report6">Time Quote</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('7', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report7"
                                                                   value="7"><label class="ml-2"
                                                                                    for="emp_access_report7">Payment Missing</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('8', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report8"
                                                                   value="8"><label class="ml-2"
                                                                                    for="emp_access_report8">Booked</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('18', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report18"
                                                                   value="18"><label class="ml-2"
                                                                                     for="emp_access_report18">OnApproval</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('9', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report9"
                                                                   value="9"><label class="ml-2"
                                                                                    for="emp_access_report9">Listed</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('10', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report10"
                                                                   value="10"><label class="ml-2"
                                                                                     for="emp_access_report10">Schedule</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('34', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report34"
                                                                   value="34"><label class="ml-2"
                                                                                     for="emp_access_report34">Schedule Another Driver</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('30', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report30"
                                                                   value="30"><label class="ml-2"
                                                                                     for="emp_access_report30">Pickup Approval</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('11', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report11"
                                                                   value="11"><label class="ml-2"
                                                                                     for="emp_access_report11">Pickup</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('31', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report31"
                                                                   value="31"><label class="ml-2"
                                                                                     for="emp_access_report31">Delivered Approval</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('32', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report32"
                                                                   value="32"><label class="ml-2"
                                                                                     for="emp_access_report32">Schedule For Delivery</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('12', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report12"
                                                                   value="12"><label class="ml-2"
                                                                                     for="emp_access_report12">Delivered</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('13', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report32"
                                                                   value="13"><label class="ml-2"
                                                                                     for="emp_access_report13">Completed</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('19', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report19"
                                                                   value="19"><label class="ml-2"
                                                                                     for="emp_access_report19">OnApprovalCancelled</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('14', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report14"
                                                                   value="14"><label class="ml-2"
                                                                                     for="emp_access_report14">Cancel</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('20', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report20"
                                                                   value="20"><label class="ml-2"
                                                                                     for="emp_access_report20">Relist</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('21', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report21"
                                                                   value="21"><label class="ml-2"
                                                                                     for="emp_access_report21">Price Raise</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('22', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report22"
                                                                   value="22"><label class="ml-2"
                                                                                     for="emp_access_report22">Approach Id</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('23', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report23"
                                                                   value="23"><label class="ml-2"
                                                                                     for="emp_access_report23">Different Port</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('24', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report24"
                                                                   value="24"><label class="ml-2"
                                                                                     for="emp_access_report24">Carrier Update</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('25', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report25"
                                                                   value="25"><label class="ml-2"
                                                                                     for="emp_access_report25">Storage</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('26', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report26"
                                                                   value="26"><label class="ml-2"
                                                                                     for="emp_access_report26">Approaching</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('27', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report27"
                                                                   value="27"><label class="ml-2"
                                                                                     for="emp_access_report27">Auction Update Request</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('28', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report28"
                                                                   value="28"><label class="ml-2"
                                                                                     for="emp_access_report28">Move To Storage</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('29', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report29"
                                                                   value="29"><label class="ml-2"
                                                                                     for="emp_access_report29">Double Booking</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('33', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report33"
                                                                   value="33"><label class="ml-2"
                                                                                     for="emp_access_report33">Auction Update</label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox"
                                                                   @if (in_array('35', $emp_report)) {{ 'checked' }} @endif
                                                                   name="emp_access_report[]" id="emp_access_report35"
                                                                   value="35"><label class="ml-2"
                                                                                     for="emp_access_report35">Auction Storage</label>
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
                            <div class="modal fade" id="exampleModal8" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalLabel7" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel7">Employee Access (Assign
                                                Data)</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <?php
                                                    $datas = \App\UsedAndNewCarDealers::select('state', \DB::raw('MIN(id) as id'), \DB::raw('COUNT(CASE WHEN user_id = 0 THEN 1 ELSE NULL END) as total_with_user_id_0'), \DB::raw('COUNT(*) as total'))->where('state', '!=', '-')->groupBy('state')->orderBy('state', 'asc')->get();
                                                    ?>
                                                    <div class="row justify-content displayEdit">
                                                        <div class="col-lg-3 lg3-div">
                                                            <label style="float: left">Order Taker</label>
                                                            <div class='input-group'>
                                                                <span>{{ $data2->name }}</span>
                                                                <input type='hidden' name="orderTaker"
                                                                       value="{{ $data2->id }}" />
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 lg3-div">
                                                            <label style="float: left">States</label>
                                                            <select id="state" name="state[]"
                                                                    class="select2 form-control" multiple
                                                                    class="form-control">
                                                                <!--<option selected value="">Select</option>-->
                                                                @foreach ($datas as $key => $val)
                                                                    <option value="{{ $val->state }}">
                                                                        {{ $val->state . ' ' . '(' . $val->total . ')' }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <label style="float: left">Categories</label>
                                                            <select id="category_assign" name="category_assign"
                                                                    class="form-control">
                                                                <option value="">Select Category</option>
                                                                <option value="Auto Dealership">Auto Dealership</option>
                                                                <option value="Automotive Repair Services">Automotive
                                                                    Repair Services
                                                                </option>
                                                                <option value="New Car Dealer">New Car Dealer</option>
                                                                <option value="Used Car Dealer">Used Car Dealer</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-3 lg3-div">
                                                            <label style="float: left">Allow Records</label>
                                                            <div class='input-group'>
                                                                <input type='number' name="recordsAllowed"
                                                                       id="recordsAllowed" class="form-control height" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- </form> --}}
                                                    <div class="row justify-table">
                                                        <!--class="table table-bordered table-sm col-lg-2 fs-18 text-center pd-2 bd-l"-->
                                                        <table class="table table-bordered   fs-18 text-center pd-2 bd-l"
                                                               role="grid" aria-describedby="">
                                                            <thead class="table-dark">
                                                            <tr>
                                                                <th width="10%">States</th>
                                                                <th width="10%">Category</th>
                                                                <th width="10%">Records Allowed</th>
                                                                <th width="10%">Action</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <td>{{ !is_null($data2->assignedData) ? $data2->assignedData->state : '' }}
                                                            </td>
                                                            <td>{{ !is_null($data2->assignedData) ? $data2->assignedData->category : '' }}
                                                            </td>
                                                            <td>{{ !is_null($data2->assignedData) ? $data2->assignedData->recordsAllowed : '' }}
                                                            </td>
                                                            <td>
                                                                {{-- <a class="btn btn-primary getData">Edit</a> --}}
                                                                <button type="button"
                                                                        class="btn btn-primary getData">Edit
                                                                    <input hidden type="text" class="User-ID"
                                                                           value="{{ !is_null($data2->assignedData) ? $data2->assignedData->orderTaker : '' }}">
                                                                </button>
                                                            </td>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    {{-- @endif --}}
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
                            <div class="modal fade" id="exampleModal9" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalLabel4" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel4">Employee Access (Guides)
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
                                                        @foreach ($guide as $row)
                                                            <div class="col-sm-12">
                                                                <input type="checkbox" name="emp_access_guide[]"
                                                                       @if (in_array($row->id, $emp_access_guide)) {{ 'checked' }} @endif
                                                                       id="emp_access_guide{{ $row->id }}"
                                                                       value="{{ $row->id }}"><label class="ml-2"
                                                                                                     for="emp_access_guide{{ $row->id }}">{{ $row->page_name }}</label>
                                                            </div>
                                                        @endforeach
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
                            <div class="col-sm-6 col-md-6 mt-3">
                                <div class="form-group">
                                    @php
                                        $pt = 1;
                                        if (!empty($penaltype)) {
                                            $pt = $penaltype->penal_type;
                                        }
                                    @endphp

                                    <input type="radio" @if ($pt == 1) checked @endif
                                    name="penalytype" value="1"> Auction
                                    <br>
                                    <input type="radio" @if ($pt == 2) checked @endif
                                    name="penalytype" value="2"> ProMax
                                    <br>
                                    <input type="radio" @if ($pt == 3) checked @endif
                                    name="penalytype" value="3"> Testing Quote
                                    <br>
                                    <input type="radio" @if ($pt == 4) checked @endif
                                    name="penalytype" value="4"> Shipa1 Website
                                    <br>
                                    <input type="radio" @if ($pt == 5) checked @endif
                                    name="penalytype" value="5"> Panel Type 5 Quote
                                    <br>
                                    <input type="radio" @if ($pt == 6) checked @endif
                                    name="penalytype" value="6"> Panel Type 6 Quote
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Address</label>
                                    <input type="text" required name="address" value="{{ $data2->address }}"
                                           class="form-control" placeholder="Home Address">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button id="sv_btn" type="submit" class="btn  btn-info">UPDATE</button>
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
                if ($('#state').val() && $('#state').val().length > 0) {
                    var numberInputValue = $('#recordsAllowed').val();
                    if (numberInputValue !== undefined && numberInputValue.trim() !== '') {} else {
                        console.log('Number input is required.');
                    }
                } else {
                    console.log('At least one option in the multi-select is required.');
                }
                e.preventDefault();
                $.ajax({
                    url: "/update_employee",
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
                            if ("{{ Auth::user()->userRole->name }}" == 'Code Giver') {
                                window.location.href = "/employees";
                            } else {
                                window.location.href = "/view_employee";
                            }
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

        $(document).ready(function() {
            $("#phoneNumber").mask("9999-9999999");
            $("#phoneNumber")[0].setSelectionRange(0, 0);
        })

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
            if (role == 'CSR' || role == 'Seller Agent' || role == 'Order Taker') {
                $("#client_number").show();
                $("#qoutes").show();
                $("#all_ot").hide();
                $("#manager").hide();
                $("#assign_daily_qoute").show();
                $("#group_qoutes").show();
                $("#auto_assigning").hide();
                $("#dispatcher_quotes").hide();
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

        // $('.displayEdit').hide();

        $(".getData").click(function() {
            var user_id = $(this).find('.User-ID').val();

            // $('.displayEdit').show();

            $.ajax({
                url: '{{ route('edit.allowed.states') }}',
                type: 'GET',
                data: {
                    'user_id': user_id,
                },
                success: function(data) {

                    var selectedStates = data.state.split(',');

                    // Unselect all options first
                    $('#state option').prop('selected', false);

                    // Loop through the options in the select element
                    $('#state option').each(function() {
                        // Check if the current option's value is in the selectedStates array
                        if (selectedStates.includes($(this).val())) {
                            // If it is, set the option as selected
                            $(this).prop('selected', true);
                        }
                    });

                    // Display the recordsAllowed value
                    $('#recordsAllowed').val(data.recordsAllowed);
                    // Ensure "Used Car Dealer" option is present and then select it
                    if ($('#category_assign option[value="Used Car Dealer"]').length === 0) {
                        $('#category_assign').append(
                            '<option value="Used Car Dealer">Used Car Dealer</option>');
                    }
                    $('#category_assign').val(data.category);
                },
                error: function(error) {
                    // Handle the error response
                    console.error('Error submitting the form:', error);
                    // Optionally, you can display an error message or take other actions
                }
            });
        });
    </script>
@endsection
