<!DOCTYPE html>
<html lang="en" dir="ltr">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>


    @include('partials.mainsite_pages.return_function')
    <!-- Meta data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta content="SHIPA1" name="description">
    <meta content="SHIPA1 IT DEPARTMENT" name="author">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Title -->
    <title>View Employees</title>





    <!--Favicon -->
    <link rel="icon" href="{{ url('assets/images/brand/favicon.ico') }}" type="image/x-icon" />

    <!--Bootstrap css -->
    <link href="{{ url('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ url('assets/js/jquery-ui-1.12.1/jquery-ui.min.css') }}">

    <!-- Style css -->
    <link href="{{ url('assets/css/style.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/dark.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/skin-modes.css') }}" rel="stylesheet" />


    <!-- Animate css -->
    <link href="{{ url('assets/css/animated.css') }}" rel="stylesheet" />

    <!--Sidemenu css -->
    <link href="{{ url('assets/css/sidemenu.css') }}" rel="stylesheet">

    <!-- P-scroll bar css-->
    <link href="{{ url('assets/plugins/p-scrollbar/p-scrollbar.css') }}" rel="stylesheet" />

    <!---Icons css-->
    <link href="{{ url('assets/css/icons.css') }}" rel="stylesheet" />


    <link href="{{ url('assets/plugins/notify/css/jquery.growl.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

    <!-- INTERNAl WYSIWYG Editor css -->
    <link href="{{ url('assets/plugins/wysiwyag/richtext.css') }}" rel="stylesheet" />

    <!-- Data table css -->
    <link href="{{ url('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ url('assets/plugins/datatable/responsive.bootstrap4.min.css') }}" rel="stylesheet" />

    <!-- INTERNAL Select2 css -->
    <link href="{{ url('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />

    <!-- INTERNAL File Uploads css -->
    <link href="{{ url('assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet" />

    <!-- INTERNAL Time picker css -->
    <link href="{{ url('assets/plugins/time-picker/jquery.timepicker.css') }}" rel="stylesheet" />

    <!-- INTERNAL Date Picker css -->
    <link href="{{ url('assets/plugins/date-picker/date-picker.css') }}" rel="stylesheet" />

    <!-- INTERNAL File Uploads css-->
    <link href="{{ url('assets/plugins/fileupload/css/fileupload.css') }}" rel="stylesheet" type="text/css" />

    <!-- INTERNAL Mutipleselect css-->
    <link rel="stylesheet" href="{{ url('assets/plugins/multipleselect/multiple-select.css') }}">

    <!-- INTERNAL Sumoselect css-->
    <link rel="stylesheet" href="{{ url('assets/plugins/sumoselect/sumoselect.css') }}">

    <!-- INTERNAL telephoneinput css-->
    <link rel="stylesheet" href="{{ url('assets/plugins/telephoneinput/telephoneinput.css') }}">

    <!-- INTERNAL Jquerytransfer css-->
    <link rel="stylesheet" href="{{ url('assets/plugins/jQuerytransfer/jquery.transfer.css') }}">
    <link rel="stylesheet" href="{{ url('assets/plugins/jQuerytransfer/icon_font/icon_font.css') }}">

    <!-- INTERNAL multi css-->
    <link rel="stylesheet" href="{{ url('assets/plugins/multi/multi.min.css') }}">

    <!-- Simplebar css -->
    <link rel="stylesheet" href="{{ url('assets/plugins/simplebar/css/simplebar.css') }}">

    <!-- Color Skin css -->
    <link id="theme" href="{{ url('assets/colors/color1.css" rel="stylesheet') }}" type="text/css" />

    <!-- Switcher css -->
    <link rel="stylesheet" href="{{ url('assets/switcher/css/switcher.css') }}">
    <link rel="stylesheet" href="{{ url('assets/switcher/demo.css') }}">


    <style>
        .img_border {
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 35px;
        }

        .tabs-menu1 ul li .active {
            background: #705ec8;
            color: #fff !important;
        }

        .tabs-menu1 ul li a {
            padding: 10px 20px 11px 20px;
            display: block;
            border: 1px solid #e3e4e9;
            margin: 3px;
            border-radius: 4px;
        }
    </style>


    <style>
        .fade {
            transition: opacity 0.15s linear;
        }

        .modal_Style {
            background: black;
            opacity: 0.5;
        }

        #btn_center {
            max-width: 720px;
            margin: auto;
        }

        .radio_btn {
            margin: 31px;
        }

        textarea.form-control {
            height: 99px;
        }

        .form-group i {
            color: #705ec8;
        }

        .form-group i {
            margin-right: 10px;
            font-weight: bold;
            font-size: 16px;
            /* padding-top: 10px; */
            line-height: 1;
        }

        #add_btn {
            font-size: 20px;
            color: blue;

        }

        #remove_btn {
            font-size: 20px;
            color: red;
            padding: 9px;
        }

        .margin_lft {
            margin-left: -10px;
        }

        .nav {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: initial;
            padding-left: 0;
            margin-bottom: 0;
            list-style: none;
        }

        .modal_style {
            border: 1px solid;
            padding: 10px;
            box-shadow: 5px 10px #404040;
        }

        .heading_style {

            justify-content: center;
            font-family: fangsong;
            color: black;
            border-bottom: 3px solid black;
            font-size: 25px;
            padding: 2px;


        }

        .form-control {
            color: #38413b;
            opacity: 2;
            font-size: 18px;
            border: 1px solid #318eefc9;
            font-family: inherit;
        }

        .select2 {
            color: #38413b;
            opacity: 2;
            font-size: 18px;
            border: 1px solid #318eefc9;
            font-family: inherit;
        }

        .heading_font {
            font-size: 30px;
            border-bottom: 0px solid black;

        }

        .modal-title {

            font-size: 18px;
            color: black;
            font-family: revert;
            padding: 7px;
        }

        .modal_subtitle {

            font-size: 20px;
            color: black;
            font-family: revert;
            padding: 7px;
        }
    </style>

    <style>
        @keyframes fa-blink {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 0;
            }
        }

        .fa-blink {
            -webkit-animation: fa-blink .75s linear infinite;
            -moz-animation: fa-blink .75s linear infinite;
            -ms-animation: fa-blink .75s linear infinite;
            -o-animation: fa-blink .75s linear infinite;
            animation: fa-blink .75s linear infinite;

        }
    </style>

    <style>
        .ql-picker-label {
            width: 30px;
        }

        .bottompopups a {
            background: none;
            margin: auto;
            position: fixed;
            bottom: 1%;
            width: 50px;
            height: 50px;
            z-index: 999;
            text-decoration: none;
            color: #ffffff;
            border-radius: 5px;
        }

        .bottompopups .bp1 {
            right: 20px;
            /* background-color: #cae01e; */
            background-color: #004992;
            color: #00d0ff;
            border: 1px solid black;
        }

        .bottompopups .bp2 {
            right: 80px;
            background-color: #0052ff;
            border: 1px solid #000000;

            /* background-color: #ff9800; */
        }

        .bottompopups .bp3 {
            right: 140px;
            background-color: #2196f3;
            border: 1px solid black;

        }

        .bottompopups .bp4 {
            right: 200px;
            background-color: #f7ce5b;
            border: 1px solid black;

        }

        .bottompopups .bp5 {
            right: 260px;
            background-color: #f7625b;
            border: 1px solid black;

        }

        .bottompopups i {
            font-size: 40px;
            margin: 5px;
        }

        .bottompopups i img {
            height: 40px;
        }

        #back-to-top {
            bottom: 61px;
            background: #705ec8;
            padding: 15px;
        }

        #back-to-top:hover {
            background: #705ec8;
        }

        .nav-tabs .nav-link.active {
            color: rgb(255, 255, 255);
            background: #007bff;
            size: 50px;
            /* width: 51px; */
            height: 44px;
        }

        .btn-primary {
            color: #fff !important;
            background-color: #17a2b8;
            border-color: #705ec8;
            box-shadow: 0 0px 10px -5px rgba(112, 94, 200, 0.5);
        }

        .nav-tabs .nav-item .nav-link:hover {
            color: black;
            border: 1px solid black;

        }

        select.form-control:not([size]):not([multiple]) {
            height: 3.375rem;
        }

        .lds-hourglass:after {
            border: 26px solid #705ec8;
            border-color: #705ec8 transparent;
        }

        .nav-link.icon .header-icon {
            padding: 3px;
            !important;
        }

        body {
            margin: 0;
            font-family: "Roboto", "Helvetica Neue", Arial, sans-serif;
            font-size: 0.875rem;
            font-weight: 400;
            line-height: 1.5;
            color: #040406;
            text-align: left;
            background-color: #f0f2f7;
        }
    </style>
    @if (Auth::user()->freeze == 1)
        <style>
            body * {
                user-select: none;
            }
        </style>
    @endif
    <style>
        .nav-tabs .nav-link.active:hover {
            color: rgb(255 255 255);
            background: #20223c;
            size: 50px;
            width: 100%;
            height: 44px;
        }

        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            width: 108vw !important;
            height: 108vh !important;
            background-color: #000;
        }

        .app-sidebar__toggle {
            visibility: hidden !important;
        }

        #addPortDetail .form-control {
            border-color: #705ec8;
        }

        #addPortDetail .modal-dialog {
            max-width: 80%;
        }

        .chat-center {
            position: fixed;
            bottom: -15px;
            right: 12px;
            z-index: 1000;
            flex-flow: row-reverse;
            width: 85%;
        }

        .chat-user {
            background-image: url({{ asset('public/images/chat-bg.jpg') }});
            overflow-y: scroll;
            height: 400px;
            background-size: cover;
            background-repeat: no-repeat;
        }

        .chat-user::before,
        .users-dispatchers::before {
            content: '';
            position: absolute;
            inset: 0;
            background: #000000;
            opacity: 0.3;
            top: 68px;
        }

        /* width */
        .chat-user::-webkit-scrollbar,
        .users-dispatchers::-webkit-scrollbar {
            width: 5px;
        }

        /* Track */
        .chat-user::-webkit-scrollbar-track,
        .users-dispatchers::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Handle */
        .chat-user::-webkit-scrollbar-thumb,
        .users-dispatchers::-webkit-scrollbar-thumb {
            background: #00c4ff;
            border-radius: 50px;
            border: #00c4ff;
        }

        .users-dispatchers {
            overflow-y: scroll;
            height: 450px;
        }

        .message-feed.right .mf-content:before {
            border-bottom: 8px solid #705ec8;
        }

        /* Style the tab */
        .table-responsive {
            overflow: unset !important;
        }

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

        .dropdown-menu {
            left: -6rem !important;
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

        .dropdown>.dropdown-menu {
            top: 200%;
            transition: 0.3s all ease-in-out;
        }

        .dropdown:hover>.dropdown-menu {
            display: block;
            top: 100%;
        }

        .dropdown>.dropdown-toggle:active {
            /*Without this, clicking will make it sticky*/
            pointer-events: none;
        }

        .manage_dropdown {
            min-height: auto;
            border-radius: 7px;
            border: 1px solid #009eda;
            font-size: inherit;
        }

        a.dropdown-item {
            border-bottom: 1px solid var(--secondary);
            padding: 5px 14px;
            margin: 0px 0px;

        }

        a.dropdown-item:last-child {
            border-bottom: 0px;
            padding: 5px 14px;
            margin: 0px 0px;

        }

        [class^="ti-"],
        [class*=" ti-"] {
            font-family: 'themify';
            speak: none;
            font-style: normal;
            font-weight: normal;
            font-variant: normal;
            text-transform: none;
            line-height: 3 !important;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    </style>

</head>

<body class="app sidebar-mini">

    @if (Auth::user()->freeze == 1)
        <marquee class="bg-danger text-light"
            style="position:fixed;top:0;width:100%;z-index:99999;height:50px;opacity:1;">
            <h3 class="mt-3">You are freezed by Chat Approver @if (isset(Auth::user()->freeze_reason))
                    because of this<b> "{{ Auth::user()->freeze_reason }}" </b>
                @endif .Kindly contact with admin.</h3>
        </marquee>
    @endif



    <!---Global-loader-->
    <div id="global-loader">
        <img src="{{ url('assets/images/svgs/loader.svg') }}" alt="loader">
    </div>
    <!--- End Global-loader-->
    <!-- Page -->

    <div class="page">
        <div class="page-main">
            <div class="app-content main-content">
                <div class="side-app">
                    <!--nav header-->

                    <div class="app-header header">
                        <div class="container-fluid">
                            <div class="d-flex" style="align-items:center;">
                                <a class="header-brand d-block" href="/dashboard">
                                    <img src="{{ url('assets/images/brand/ship_logo.png') }}"
                                        class="header-brand-img desktop-lgo" alt="Admintro logo">
                                    <img src="{{ url('assets/images/brand/ship_logo.png') }}"
                                        class="header-brand-img dark-logo" alt="Admintro logo">
                                    <img src="{{ url('assets/images/brand/favicon.png') }}"
                                        class="header-brand-img mobile-logo" alt="Admintro logo">
                                    <img src="{{ url('assets/images/brand/favicon1.png') }}"
                                        class="header-brand-img darkmobile-logo" alt="Admintro logo">
                                </a>


                                <div class="app-sidebar__toggle" data-toggle="sidebar" style="display:none">
                                    <a class="open-toggle" href="#">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-align-left header-icon mt-1">
                                            <line x1="17" y1="10" x2="3" y2="10">
                                            </line>
                                            <line x1="21" y1="6" x2="3" y2="6">
                                            </line>
                                            <line x1="21" y1="14" x2="3" y2="14">
                                            </line>
                                            <line x1="17" y1="18" x2="3" y2="18">
                                            </line>
                                        </svg>
                                    </a>
                                </div>

                                <div class="d-flex order-lg-2 ml-auto">
                                    <div class="dropdown profile-dropdown">
                                        <a href="#" class="nav-link pr-0 leading-none" data-toggle="dropdown">
                                            <span
                                                class=" js-search-result-thumbnail responsive-img img_border fa fa-user"
                                                style=" color: #6c757d; ">

                                            </span>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow animated"
                                            style="top: 41px !important;">
                                            <div class="text-center">

                                                <a class="dropdown-item text-center user pb-0 font-weight-bold"
                                                    style="text-transform: uppercase;">
                                                    {{ isset(Auth::user()->name) ? Auth::user()->name : Auth::user()->email }}

                                                </a>
                                                <a class="dropdown-item d-flex"
                                                    href="{{ url('/update_password2') }}">
                                                    <i class="fa fa-key pr-1 mt-1 ml-1"></i>
                                                    <div class="">Change Password</div>
                                                </a>
                                                <a class="dropdown-item d-flex" href="{{ route('logout') }}">
                                                    <svg class="header-icon mr-2" xmlns="http://www.w3.org/2000/svg"
                                                        enable-background="new 0 0 24 24" height="24"
                                                        viewBox="0 0 24 24" width="24">
                                                        <g>
                                                            <rect fill="none" height="24" width="24" />
                                                        </g>
                                                        <g>
                                                            <path
                                                                d="M11,7L9.6,8.4l2.6,2.6H2v2h10.2l-2.6,2.6L11,17l5-5L11,7z M20,19h-8v2h8c1.1,0,2-0.9,2-2V5c0-1.1-0.9-2-2-2h-8v2h8V19z" />
                                                        </g>
                                                    </svg>
                                                    <div class="">Sign Out</div>
                                                </a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--/nav header-->

                <!--/content-->
                <br>

                <div class="page-header mb-0">
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
                        <h1 class="my-4 heading-chat"><b>Custom Chat</b></h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        @if (Session::has('msg'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>{{ Session::get('msg') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if (Session::has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>{{ Session::get('error') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        <!--div-->
                        <div class="card mt-5">
                            <div class="d-flex justify-content-between card-header">
                                <select id="entity" class="form-control" style="width:8%;">
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="250">250</option>
                                    <option value="500">500</option>
                                    <option value="1000">1000</option>
                                </select>
                                <div class="d-flex justify-content-between">
                                    <select id="search" class="form-control" style="width:55%;">
                                        <option value="" selected disabled>Select</option>
                                        <option value="order_id">Order Id</option>
                                        <option value="sender_name">Sender Name</option>
                                        <option value="receiver_name">Receiver Name</option>
                                        <option value="message">Message</option>
                                        <option value="message_date">Date</option>
                                        <option value="message_time">Time</option>
                                        <option value="status">Status</option>
                                    </select>
                                    <input class="form-control" id="value" type="text" placeholder="Search"
                                        style="width:40%;">
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="">
                                    <div class="table-responsive">
                                        <!-- Tab links -->
                                        <div class="tab">
                                            <button class="tablinks" onclick="openCity(event, 'Custom_Chat')"
                                                id="defaultOpen">Custom Chat <span
                                                    class="badge badge-success rounded-circle" id="customCount">
                                                    {{ $countCustom }}</span></button>
                                            {{-- <button class="tablinks" onclick="openCity(event, 'Public_Chat')">Public
                                                Chat <span class="badge badge-success rounded-circle"
                                                    id="publicCount"> {{ $countPublic }}</span></button> --}}
                                            <button class="tablinks" onclick="openCity(event, 'Group_Chat')">Group
                                                Chat <span class="badge badge-success rounded-circle" id="groupCount">
                                                    {{ $countGroup }}</span></button>
                                            <button class="tablinks" onclick="openCity(event, 'Users')">Freeze Users
                                                <span class="badge badge-success rounded-circle" id="userCount">
                                                    {{ $countUser }}</span></button>
                                            <button class="tablinks" onclick="openCity(event, 'Flag')">Flag Users
                                                <span class="badge badge-success rounded-circle" id="flagCount">
                                                    {{ $countFlag }}</span></button>
                                        </div>

                                        <!-- Tab content -->
                                        <div id="custom-chat-data">
                                            <div id="Custom_Chat" class="tabcontent">
                                                <table class="table table-bordered table-striped key-buttons">
                                                    <thead>
                                                        <tr>
                                                            <th class="border-bottom-0">S/No.</th>
                                                            <th class="border-bottom-0">Sender</th>
                                                            <th class="border-bottom-0">To User</th>
                                                            <th class="border-bottom-0">Message</th>
                                                            <th class="border-bottom-0">Date Time</th>
                                                            <th class="border-bottom-0">Status</th>
                                                            <th class="border-bottom-0">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($chat as $key => $val)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                {{-- <td>{{ $val->touserId }}</td> --}}
                                                                <td
                                                                    title="@if (isset($val->sender->is_login)) {{ $val->sender->is_login == 1 ? 'Online' : 'Offline' }} @endif">
                                                                    @if (isset($val->sender->id))
                                                                        <span
                                                                            class="chat-time dot-label bg-{{ $val->sender->is_login == 1 ? 'success' : 'danger' }}"></span>
                                                                        {{ $val->sender->slug ?? $val->sender->name . ' ' . $val->sender->last_name }}
                                                                    @endif
                                                                </td>
                                                                <td
                                                                    title="@if (isset($val->receiver->is_login)) {{ $val->receiver->is_login == 1 ? 'Online' : 'Offline' }} @endif">
                                                                    @if (isset($val->receiver->id))
                                                                        <span
                                                                            class="chat-time dot-label bg-{{ $val->receiver->is_login == 1 ? 'success' : 'danger' }}"></span>
                                                                        {{ $val->receiver->slug ?? $val->receiver->name . ' ' . $val->receiver->last_name }}
                                                                    @endif
                                                                </td>
                                                                <td>{{ $val->description }}</td>
                                                                <td>
                                                                    {{ $val->created_at }}
                                                                </td>
                                                                <td>
                                                                    @if ($val->status == 0)
                                                                        <span class="badge badge-danger">Pending</span>
                                                                    @elseif($val->status == 1)
                                                                        <span class="badge badge-info">Approved</span>
                                                                    @else
                                                                        <span
                                                                            class="badge badge-success text-light">Seen</span>
                                                                    @endif
                                                                    @if (isset($val->flag))
                                                                        @if ($val->flag->user_id == $val->sender->id)
                                                                            <br><br>
                                                                            <span class="badge badge-danger"><i
                                                                                    class="fa fa-flag-o"
                                                                                    aria-hidden="true"></i> Flag</span>
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($val->status == 0)
                                                                        @if (!isset($val->flag))
                                                                            <button type="button"
                                                                                class="btn btn-danger"
                                                                                data-toggle="modal"
                                                                                data-target="#flagChat"
                                                                                onclick="putFlagChatId({{ $val->id }}, {{ $val->fromuserId }})"
                                                                                title="Red Flag">
                                                                                <i class="fa fa-flag-o"
                                                                                    aria-hidden="true"></i>
                                                                            </button>
                                                                        @else
                                                                            @if ($val->flag->user_id != $val->sender->id)
                                                                                <button type="button"
                                                                                    class="btn btn-danger"
                                                                                    data-toggle="modal"
                                                                                    data-target="#flagChat"
                                                                                    onclick="putFlagChatId({{ $val->id }}, {{ $val->fromuserId }})"
                                                                                    title="Red Flag">
                                                                                    <i class="fa fa-flag-o"
                                                                                        aria-hidden="true"></i>
                                                                                </button>
                                                                            @else
                                                                                <button type="button"
                                                                                    class="btn btn-success"
                                                                                    data-toggle="modal"
                                                                                    data-target="#removeflagChat"
                                                                                    title="Remove Flag"
                                                                                    onclick="removeflagChat({{ $val->id }})">
                                                                                    <i class="fa fa-universal-access"
                                                                                        aria-hidden="true"></i>
                                                                                </button>
                                                                            @endif
                                                                        @endif
                                                                        <button type="button" class="btn btn-success"
                                                                            data-toggle="modal"
                                                                            data-target="#approveChat"
                                                                            onclick="putChatId({{ $val->id }})"
                                                                            title="Approve Message">
                                                                            <i class="fa fa-thumbs-o-up"
                                                                                aria-hidden="true"></i>
                                                                        </button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <div class="d-flex justify-content-between">
                                                    <span class="my-auto">Showing {{ $chat->firstItem() }} to
                                                        {{ $chat->lastItem() }} of {{ count($chat) }} entries from
                                                        total {{ $chat->total() }}</span>
                                                    {{ $chat->links() }}
                                                </div>
                                            </div>
                                            {{-- <div id="Public_Chat" class="tabcontent">
                                                <table class="table table-bordered table-striped key-buttons">
                                                    <thead>
                                                        <tr>
                                                            <th class="border-bottom-0">S/No.</th>
                                                            <th class="border-bottom-0">Order Id#</th>
                                                            <th class="border-bottom-0">Sender</th>
                                                            <th class="border-bottom-0">Message</th>
                                                            <th class="border-bottom-0">Members</th>
                                                            <th class="border-bottom-0">Date Time</th>
                                                            <th class="border-bottom-0">Status</th>
                                                            <th class="border-bottom-0">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($public as $key => $val)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td>{{ $val->order_id }}</td>
                                                                <td
                                                                    title="{{ $val->user->is_login == 1 ? 'Online' : 'Offline' }}">
                                                                    <span
                                                                        class="chat-time dot-label bg-{{ $val->user->is_login == 1 ? 'success' : 'danger' }}"></span>
                                                                    {{ $val->user->slug ?? $val->user->name . ' ' . $val->user->last_name }}
                                                                </td>
                                                                <td>{{ $val->message }}</td>
                                                                <td>
                                                                    {{ $val->member }} Members
                                                                    <br>
                                                                    Seen By {{ $val->seen_by }} Members
                                                                </td>
                                                                <td>
                                                                    {{ $val->message_date }}
                                                                    <br>
                                                                    {{ $val->message_time }}
                                                                </td>
                                                                <td>
                                                                    @if ($val->status == 0)
                                                                        <span class="badge badge-danger">Pending</span>
                                                                    @elseif($val->status == 1)
                                                                        <span class="badge badge-info">Approved</span>
                                                                    @else
                                                                        <span
                                                                            class="badge badge-success text-light">Seen</span>
                                                                    @endif
                                                                    @if (isset($val->flag))
                                                                        @if ($val->flag->user_id == $val->user->id)
                                                                            <br><br>
                                                                            <span class="badge badge-danger"><i
                                                                                    class="fa fa-flag-o"
                                                                                    aria-hidden="true"></i> Flag</span>
                                                                        @endif
                                                                    @endif

                                                                </td>
                                                                <td>
                                                                    @if ($val->status == 0)
                                                                        @if (!isset($val->flag))
                                                                            <button type="button"
                                                                                class="btn btn-danger"
                                                                                data-toggle="modal"
                                                                                data-target="#flagPublicChat"
                                                                                onclick="putFlagPublicId({{ $val->id }})"
                                                                                title="Red Flag">
                                                                                <i class="fa fa-flag-o"
                                                                                    aria-hidden="true"></i>
                                                                            </button>
                                                                        @else
                                                                            @if ($val->flag->user_id != $val->user->id)
                                                                                <button type="button"
                                                                                    class="btn btn-danger"
                                                                                    data-toggle="modal"
                                                                                    data-target="#flagPublicChat"
                                                                                    onclick="putFlagPublicId({{ $val->id }})"
                                                                                    title="Red Flag">
                                                                                    <i class="fa fa-flag-o"
                                                                                        aria-hidden="true"></i>
                                                                                </button>
                                                                            @else
                                                                                <button type="button"
                                                                                    class="btn btn-success"
                                                                                    data-toggle="modal"
                                                                                    data-target="#removeflagChatPublic"
                                                                                    title="Remove Flag"
                                                                                    onclick="removeflagChatPublic({{ $val->id }})">
                                                                                    <i class="fa fa-universal-access"
                                                                                        aria-hidden="true"></i>
                                                                                </button>
                                                                            @endif
                                                                        @endif
                                                                        <button type="button" class="btn btn-success"
                                                                            data-toggle="modal"
                                                                            data-target="#approvePublic"
                                                                            onclick="putPublicId({{ $val->id }})"
                                                                            title="Approve Message">
                                                                            <i class="fa fa-thumbs-o-up"
                                                                                aria-hidden="true"></i>
                                                                        </button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <div class="d-flex justify-content-between">
                                                    <span class="my-auto">Showing {{ $public->firstItem() }} to
                                                        {{ $public->lastItem() }} of {{ count($public) }} entries from
                                                        total {{ $public->total() }}</span>
                                                    {{ $public->links() }}
                                                </div>
                                            </div> --}}
                                            <div id="Group_Chat" class="tabcontent">
                                                <table class="table table-bordered table-striped key-buttons">
                                                    <thead>
                                                        <tr>
                                                            <th class="border-bottom-0">S/No.</th>
                                                            {{-- <th class="border-bottom-0">Order Id#</th> --}}
                                                            <th class="border-bottom-0">Sender</th>
                                                            <th class="border-bottom-0">Message</th>
                                                            <th class="border-bottom-0">Members</th>
                                                            <th class="border-bottom-0">Date Time</th>
                                                            <th class="border-bottom-0">Status</th>
                                                            <th class="border-bottom-0">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($group as $key => $val)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                {{-- <td>{{ $val->order_id }}</td> --}}
                                                                <td
                                                                    title="{{ $val->user->is_login == 1 ? 'Online' : 'Offline' }}">
                                                                    <span
                                                                        class="chat-time dot-label bg-{{ $val->user->is_login == 1 ? 'success' : 'danger' }}"></span>
                                                                    {{ $val->user->slug ?? $val->user->name . ' ' . $val->user->last_name }}
                                                                </td>
                                                                <td>{{ $val->message }}</td>
                                                                <td>
                                                                    {{ $val->member }} Members
                                                                    <br>
                                                                    Seen By {{ $val->seen_by }} Members
                                                                </td>
                                                                <td>
                                                                    {{ $val->created_at }}
                                                                </td>
                                                                <td>
                                                                    @if ($val->status == 0)
                                                                        <span class="badge badge-danger">Pending</span>
                                                                    @elseif($val->status == 1)
                                                                        <span class="badge badge-info">Approved</span>
                                                                    @else
                                                                        <span
                                                                            class="badge badge-success text-light">Seen</span>
                                                                    @endif
                                                                    @if (isset($val->flag))
                                                                        @if ($val->flag->user_id == $val->user->id)
                                                                            <br><br>
                                                                            <span class="badge badge-danger"><i
                                                                                    class="fa fa-flag-o"
                                                                                    aria-hidden="true"></i> Flag</span>
                                                                        @endif
                                                                    @endif

                                                                </td>
                                                                <td>
                                                                    @if ($val->status == 0)
                                                                        @if (!isset($val->flag))
                                                                            <button type="button"
                                                                                class="btn btn-danger"
                                                                                data-toggle="modal"
                                                                                data-target="#flagPublicChat"
                                                                                onclick="putFlagPublicId({{ $val->id }})"
                                                                                title="Red Flag">
                                                                                <i class="fa fa-flag-o"
                                                                                    aria-hidden="true"></i>
                                                                            </button>
                                                                        @else
                                                                            @if ($val->flag->user_id != $val->user->id)
                                                                                <button type="button"
                                                                                    class="btn btn-danger"
                                                                                    data-toggle="modal"
                                                                                    data-target="#flagPublicChat"
                                                                                    onclick="putFlagPublicId({{ $val->id }})"
                                                                                    title="Red Flag">
                                                                                    <i class="fa fa-flag-o"
                                                                                        aria-hidden="true"></i>
                                                                                </button>
                                                                            @else
                                                                                <button type="button"
                                                                                    class="btn btn-success"
                                                                                    data-toggle="modal"
                                                                                    data-target="#removeflagChatPublic"
                                                                                    title="Remove Flag"
                                                                                    onclick="removeflagChatPublic({{ $val->id }})">
                                                                                    <i class="fa fa-universal-access"
                                                                                        aria-hidden="true"></i>
                                                                                </button>
                                                                            @endif
                                                                        @endif
                                                                        <button type="button" class="btn btn-success"
                                                                            data-toggle="modal"
                                                                            data-target="#approveGroup"
                                                                            onclick="putGroupId({{ $val->id }})"
                                                                            title="Approve Message">
                                                                            <i class="fa fa-thumbs-o-up"
                                                                                aria-hidden="true"></i>
                                                                        </button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <div class="d-flex justify-content-between">
                                                    <span class="my-auto">Showing {{ $group->firstItem() }} to
                                                        {{ $group->lastItem() }} of {{ count($group) }} entries
                                                        from
                                                        total {{ $group->total() }}</span>
                                                    {{ $group->links() }}
                                                </div>
                                            </div>
                                            <div id="Users" class="tabcontent">
                                                <table class="table table-bordered table-striped key-buttons">
                                                    <thead>
                                                        <tr>
                                                            <th class="border-bottom-0">S/No.</th>
                                                            <th class="border-bottom-0">Name</th>
                                                            <th class="border-bottom-0">Role</th>
                                                            <th class="border-bottom-0">Phone</th>
                                                            <th class="border-bottom-0">Status</th>
                                                            <th class="border-bottom-0">Reason</th>
                                                            <th class="border-bottom-0">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($users as $key => $val)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td
                                                                    title="{{ $val->is_login == 1 ? 'Online' : 'Offline' }}">
                                                                    <span
                                                                        class="chat-time dot-label bg-{{ $val->is_login == 1 ? 'success' : 'danger' }}"></span>
                                                                    {{ $val->slug ?? $val->name . ' ' . $val->last_name }}
                                                                </td>
                                                                <td>{{ $val->userRole->name }}</td>
                                                                <td>
                                                                    @if (isset($val->phone))
                                                                        <span class="text-center pd-2 bd-l">
                                                                            <a href="#"
                                                                                class="btn btn-outline-info"
                                                                                style="padding: 3px 5px; font-size: 20px;">
                                                                                <i class="fa fa-phone"></i>
                                                                                <span
                                                                                    class="">{{ $val->phone }}</span>
                                                                            </a><br>
                                                                        </span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($val->freeze == 0)
                                                                        <span
                                                                            class="badge badge-success text-light">Active</span>
                                                                    @else
                                                                        <span class="badge badge-danger">Freeze</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($val->freeze == 1)
                                                                        @if ($val->freeze_reason)
                                                                            {{ $val->freeze_reason }}
                                                                        @else
                                                                            No Reason
                                                                        @endif
                                                                    @else
                                                                        No Reason
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($val->freeze == 0)
                                                                        <button type="button" class="btn btn-danger"
                                                                            data-toggle="modal"
                                                                            data-target="#freezeAccount"
                                                                            title="Freeze Account"
                                                                            onclick="freezeAcc({{ $val->id }},1)">
                                                                            <i class="fa fa-ban"
                                                                                aria-hidden="true"></i>
                                                                        </button>
                                                                    @else
                                                                        <button type="button" class="btn btn-success"
                                                                            data-toggle="modal"
                                                                            data-target="#activeAccount"
                                                                            title="Active Account"
                                                                            onclick="activeAcc({{ $val->id }},0)">
                                                                            <i class="fa fa-universal-access"
                                                                                aria-hidden="true"></i>
                                                                        </button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <div class="d-flex justify-content-between">
                                                    <span class="my-auto">Showing {{ $users->firstItem() }} to
                                                        {{ $users->lastItem() }} of {{ count($users) }} entries from
                                                        total {{ $users->total() }}</span>
                                                    {{ $users->links() }}
                                                </div>
                                            </div>
                                            <div id="Flag" class="tabcontent">
                                                <table class="table table-bordered table-striped key-buttons">
                                                    <thead>
                                                        <tr>
                                                            <th class="border-bottom-0">S/No.</th>
                                                            <th class="border-bottom-0">Name</th>
                                                            <th class="border-bottom-0">Role</th>
                                                            <th class="border-bottom-0">Phone</th>
                                                            <th class="border-bottom-0">Flags</th>
                                                            <th class="border-bottom-0">Reasons</th>
                                                            <th class="border-bottom-0">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($flag as $key => $val)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td
                                                                    title="{{ $val->is_login == 1 ? 'Online' : 'Offline' }}">
                                                                    <span
                                                                        class="chat-time dot-label bg-{{ $val->is_login == 1 ? 'success' : 'danger' }}"></span>
                                                                    {{ $val->slug ?? $val->name . ' ' . $val->last_name }}
                                                                </td>
                                                                <td>{{ $val->userRole->name }}</td>
                                                                <td>
                                                                    @if (isset($val->phone))
                                                                        <span class="text-center pd-2 bd-l">
                                                                            <a href="#"
                                                                                class="btn btn-outline-info"
                                                                                style="padding: 3px 5px; font-size: 20px;">
                                                                                <i class="fa fa-phone"></i>
                                                                                <span
                                                                                    class="">{{ $val->phone }}</span>
                                                                            </a><br>
                                                                        </span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($val->flag_count > 1)
                                                                        <span
                                                                            class="badge badge-danger">{{ $val->flag_count }}
                                                                            Flags </span>
                                                                    @else
                                                                        <span
                                                                            class="badge badge-danger">{{ $val->flag_count }}
                                                                            Flag</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($val->flag_count < 1)
                                                                        No Reason
                                                                    @elseif(isset($val->flag))
                                                                        @foreach ($val->flag as $key2 => $value2)
                                                                            @if (isset($value2->reason))
                                                                                {{ $key2 + 1 }})
                                                                                {{ $value2->reason }}
                                                                                <br>
                                                                            @else
                                                                                No Reason
                                                                                <br>
                                                                            @endif
                                                                        @endforeach
                                                                    @else
                                                                        No Reason
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($val->flag_count > 0)
                                                                        <button type="button" class="btn btn-success"
                                                                            data-toggle="modal"
                                                                            data-target="#removeFlag"
                                                                            title="Remove Flag"
                                                                            onclick="removeFlag({{ $val->id }})">
                                                                            <i class="fa fa-universal-access"
                                                                                aria-hidden="true"></i>
                                                                        </button>
                                                                    @endif
                                                                    <button type="button" class="btn btn-danger"
                                                                        data-toggle="modal" data-target="#flagRed"
                                                                        onclick="redFlag({{ $val->id }})"
                                                                        title="Red Flag">
                                                                        <i class="fa fa-flag-o"
                                                                            aria-hidden="true"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <div class="d-flex justify-content-between">
                                                    <span class="my-auto">Showing {{ $flag->firstItem() }} to
                                                        {{ $flag->lastItem() }} of {{ count($flag) }} entries from
                                                        total {{ $flag->total() }}</span>
                                                    {{ $flag->links() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/div-->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="row align-items-center flex-row-reverse">
                <div class="col-md-12 col-sm-12 text-center">
                    Copyright © {{ date('Y') }} <a href="{{ url('/dashboard') }}">SHIPA1</a>. Designed By <a
                        href="{{ url('/dashboard') }}">SHIPA1
                        Frontend Team </a> All Rights Reserved ®.
                </div>
            </div>
        </div>
    </footer>
    <input type="hidden" id="time_user" value="{{ Auth::user()->ss_time }}" />

    </div><!-- End Page -->
    <a href="#top" id="back-to-top"><i class="fe fe-chevrons-up"></i></a>

    <!-- /Row -->
    <div class="modal fade" id="approveChat" tabindex="-1" role="dialog" aria-labelledby="approveChatTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveChatLongTitle">Do you really want to approve this message?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('approve-chat') }}" method="POST">
                        @csrf
                        <input type="hidden" id="chat_id" name="id" />
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-primary">Yes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="approvePublic" tabindex="-1" role="dialog" aria-labelledby="approvePublicTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approvePublicLongTitle">Do you really want to approve this message?
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('approve-public-chat') }}" method="POST">
                        @csrf
                        <input type="hidden" id="chat_id2" name="id" />
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-primary">Yes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="approveGroup" tabindex="-1" role="dialog" aria-labelledby="approveGroupTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveGroupLongTitle">Do you really want to approve this message??
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('approve-group-chat') }}" method="POST">
                        @csrf
                        <input type="hidden" id="chat_id6" name="id" />
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-primary">Yes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="flagChat" tabindex="-1" role="dialog" aria-labelledby="flagChatTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="flagChatLongTitle">Do you really want to give flag to this user?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('flag-chat') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="chat_id1" name="id" />
                        <input type="hidden" id="from_user_id1" name="from_user_id" />
                        <label>Reason</label>
                        <textarea class="form-control" name="reason" id="reason" placeholder="Write the reason"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-primary">Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="flagPublicChat" tabindex="-1" role="dialog" aria-labelledby="flagPublicChatTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="flagPublicChatLongTitle">Do you really want to give flag to this user?
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('flag-public-chat') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="chat_id3" name="id" />
                        <label>Reason</label>
                        <textarea class="form-control" name="reason" id="reason" placeholder="Write the reason"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-primary">Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="freezeAccount" tabindex="-1" role="dialog" aria-labelledby="freezeAccountTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="freezeAccountLongTitle">Do you really want to freeze this account?
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('freeze-active') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="userId1" name="id" />
                        <input type="hidden" id="freeze1" name="freeze" />
                        <label>Reason</label>
                        <textarea class="form-control" name="freeze_reason" id="reason" placeholder="Write the reason"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-primary">Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="activeAccount" tabindex="-1" role="dialog" aria-labelledby="activeAccountTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="activeAccountLongTitle">Do you really want to active this account?
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('freeze-active') }}" method="POST">
                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" id="userId2" name="id" />
                        <input type="hidden" id="freeze2" name="freeze" />
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-primary">Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="removeFlag" tabindex="-1" role="dialog" aria-labelledby="removeFlagTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="activeAccountLongTitle">Do you really want to remove flag from this
                        user?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('remove-flag') }}" method="POST">
                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" id="userId3" name="id" />
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-primary">Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="flagRed" tabindex="-1" role="dialog" aria-labelledby="flagRedTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="flagRedLongTitle">Do you really want to give red flag to this user?
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('red-flag') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="userId4" name="id" />
                        <label>Reason</label>
                        <textarea class="form-control" name="reason" id="reason" placeholder="Write the reason"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-primary">Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="removeflagChat" tabindex="-1" role="dialog" aria-labelledby="removeflagChatTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeflagChatLongTitle">Do you really want to remove flag from this
                        user?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('remove-flag-chat') }}" method="POST">
                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" id="chat_id4" name="id" />
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-primary">Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="removeflagChatPublic" tabindex="-1" role="dialog"
        aria-labelledby="removeflagChatPublicTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeflagChatPublicLongTitle">Do you really want to remove flag from
                        this user?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('remove-flag-chat-public') }}" method="POST">
                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" id="chat_id5" name="id" />
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-primary">Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Jquery js-->

    <script src="{{ url('assets/js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ url('assets/js/htmlCanva.min.js') }}"></script>
    <script src="{{ url('assets/js/canva.js') }}"></script>


    <script src="{{ url('assets/js/jquery-ui-1.12.1/jquery-ui.min.js') }}"></script>
    <script src="{{ url('assets/js/jquery-cookie.js') }}"></script>
    <!-- Bootstrap4 js-->
    <script src="{{ url('assets/plugins/bootstrap/popper.min.js') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

    <!--Othercharts js-->
    <script src="{{ url('assets/plugins/othercharts/jquery.sparkline.min.js') }}"></script>

    <!-- Circle-progress js-->
    <script src="{{ url('assets/js/circle-progress.min.js') }}"></script>

    <!-- Jquery-rating js-->
    <script src="{{ url('assets/plugins/rating/jquery.rating-stars.js') }}"></script>

    <!--Sidemenu js-->
    <script src="{{ url('assets/plugins/sidemenu/sidemenu.js') }}"></script>

    <!-- P-scroll js-->
    <script src="{{ url('assets/plugins/p-scrollbar/p-scrollbar.js') }}"></script>
    <script src="{{ url('assets/plugins/p-scrollbar/p-scroll1.js') }}"></script>
    <script src="{{ url('assets/plugins/p-scrollbar/p-scroll.js') }}"></script>



    <!-- INTERNAL Notifications js -->
    <script src="{{ url('assets/plugins/notify/js/rainbow.js') }}"></script>
    <script src="{{ url('assets/plugins/notify/js/sample.js') }}?id=1"></script>
    <script src="{{ url('assets/plugins/notify/js/jquery.growl.js') }}"></script>
    <script src="{{ url('assets/plugins/notify/js/notifIt.js') }}"></script>


    <!-- INTERNAL WYSIWYG Editor js -->
    <script src="{{ url('assets/plugins/wysiwyag/jquery.richtext.js') }}"></script>
    <script src="{{ url('assets/js/form-editor.js') }}"></script>

    <!-- INTERNAL Data tables -->
    <script src="{{ url('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ url('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ url('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ url('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ url('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ url('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ url('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ url('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ url('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ url('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ url('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ url('assets/plugins/datatable/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ url('assets/js/datatables.js') }}"></script>

    <!-- INTERNAL Select2 js -->
    <script src="{{ url('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ url('assets/js/select2.js') }}"></script>

    <!-- INTERNAL Timepicker js -->
    <script src="{{ url('assets/plugins/time-picker/jquery.timepicker.js') }}"></script>
    <script src="{{ url('assets/plugins/time-picker/toggles.min.js') }}"></script>

    <!-- INTERNAL Datepicker js -->
    <script src="{{ url('assets/plugins/date-picker/date-picker.js') }}"></script>
    <script src="{{ url('assets/plugins/date-picker/jquery-ui.js') }}"></script>
    <script src="{{ url('assets/plugins/input-mask/jquery.maskedinput.js') }}"></script>

    <!-- INTERNAL File-Uploads Js-->
    <script src="{{ url('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
    <script src="{{ url('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
    <script src="{{ url('assets/plugins/fancyuploder/jquery.iframe-transport.js') }}"></script>
    <script src="{{ url('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
    <script src="{{ url('assets/plugins/fancyuploder/fancy-uploader.js') }}"></script>

    <!-- INTERNAL File uploads js -->
    <script src="{{ url('assets/plugins/fileupload/js/dropify.js') }}"></script>
    <script src="{{ url('assets/js/filupload.js') }}"></script>

    <!-- INTERNAL Multipleselect js -->
    <script src="{{ url('assets/plugins/multipleselect/multiple-select.js') }}"></script>
    <script src="{{ url('assets/plugins/multipleselect/multi-select.js') }}"></script>

    <!--INTERNAL Sumoselect js-->
    <script src="{{ url('assets/plugins/sumoselect/jquery.sumoselect.js') }}"></script>

    <!--INTERNAL telephoneinput js-->
    <script src="{{ url('assets/plugins/telephoneinput/telephoneinput.js') }}"></script>
    <script src="{{ url('assets/plugins/telephoneinput/inttelephoneinput.js') }}"></script>

    <!--INTERNAL jquery transfer js-->
    <script src="{{ url('assets/plugins/jQuerytransfer/jquery.transfer.js') }}"></script>

    <!--INTERNAL multi js-->
    <script src="{{ url('assets/plugins/multi/multi.min.js') }}"></script>

    <!--INTERNAL Form Advanced Element -->
    <script src="{{ url('assets/js/formelementadvnced.js') }}"></script>
    <script src="{{ url('assets/js/form-elements.js') }}"></script>
    <script src="{{ url('assets/js/file-upload.js') }}"></script>
    <!-- Simplebar JS -->
    <script src="{{ url('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <!-- Custom js-->
    <script src="{{ url('assets/js/custom.js') }}"></script>

    <!-- Switcher js-->
    <script src="{{ url('assets/switcher/js/switcher.js') }}"></script>
    @if (Route::is('add_new'))
    @endif

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var role = "{{ Auth::user()->role }}";

        if (role > 1) {
            function take_ss() {
                var dataURL = {};
                if ($("#time_user").val() >= 270) {
                    html2canvas(document.body).then(canvas => {
                        dataURL = canvas.toDataURL();
                        // console.log(dataURL);  
                        $.ajax({
                            url: "{{ url('/auto_screenshot') }}",
                            type: "POST",
                            data: {
                                image: dataURL
                            },
                            dataType: "html",
                            success: function(res) {}
                        });
                    });
                    $.ajax({
                        url: "{{ url('/time_user') }}",
                        type: "GET",
                        dataType: "json",
                        success: function(res) {
                            $("#time_user").val(res.time);
                        }
                    });
                } else {
                    $.ajax({
                        url: "{{ url('/time_user') }}",
                        type: "GET",
                        dataType: "json",
                        success: function(res) {
                            $("#time_user").val(res.time);
                        }
                    });
                }
            }

            setInterval(function() {
                take_ss();
            }, 1000 * 30);

            setTimeout(function() {
                take_ss();
            }, 1000);
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#example6').DataTable();
            $('#example5').DataTable();
            $('#example4').DataTable();
            $('#example3').DataTable();
        });
    </script>

    <script>
        document.getElementById("defaultOpen").click();

        function openCity(evt, cityName) {

            // console.log('cityName', cityName);

            if (cityName == 'Custom_Chat') {
                $('.heading-chat').children('b').text('Custom Chat');
                $("#search").children().remove();
                $("#search").append(`
                    <option value="" selected disabled>Select</option>
                    <option value="order_id">Order Id</option>
                    <option value="sender_name">Sender Name</option>
                    <option value="receiver_name">Receiver Name</option>
                    <option value="message">Message</option>
                    <option value="message_date">Date</option>
                    <option value="message_time">Time</option>
                    <option value="status">Status</option>
                `);
                $("#value").val('');
            } else if (cityName == 'Public_Chat') {
                $('.heading-chat').children('b').text('Public Chat');
                $("#search").children().remove();
                $("#search").append(`
                    <option value="" selected disabled>Select</option>
                    <option value="order_id">Order Id</option>
                    <option value="sender_name">Sender Name</option>
                    <option value="message">Message</option>
                    <option value="message_date">Date</option>
                    <option value="message_time">Time</option>
                    <option value="status">Status</option>
                `);
                $("#value").val('');
            } else if (cityName == 'Group_Chat') {
                $('.heading-chat').children('b').text('Group Chat');
                $("#search").children().remove();
                $("#search").append(`
                    <option value="" selected disabled>Select</option>
                    <option value="sender_name">Sender Name</option>
                    <option value="message">Message</option>
                    <option value="message_date">Date</option>
                    <option value="status">Status</option>
                `);
                $("#value").val('');
            } else if (cityName == 'Users') {
                $('.heading-chat').children('b').text('Freeze Users');
                $("#search").children().remove();
                $("#search").append(`
                    <option value="" selected disabled>Select</option>
                    <option value="name">Name</option>
                    <option value="phone">Phone</option>
                    <option value="role">Role</option>
                    <option value="status">Status</option>
                `);
                $("#value").val('');
            } else if (cityName == 'Flag') {
                $('.heading-chat').children('b').text('Flag Users');
                $("#search").children().remove();
                $("#search").append(`
                    <option value="" selected disabled>Select</option>
                    <option value="name">Name</option>
                    <option value="phone">Phone</option>
                    <option value="role">Role</option>
                `);
                $("#value").val('');
            }
            // Declare all variables
            var i, tabcontent, tablinks;

            // Get all elements with class="tabcontent" and hide them
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            // Get all elements with class="tablinks" and remove the class "active"
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            // Show the current tab, and add an "active" class to the button that opened the tab
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        let cookie = $.cookie("page");
        if (cookie) {
            $.removeCookie("page");
        }
        $.cookie("page", 1, {
            expires: 1
        });

        function getChat(search, value, entity, page) {
            console.log(search, value, entity, page);
            var heading = $('.heading-chat').children('b').text();
            $.ajax({
                url: "/custom-chat2?page=" + page,
                type: "POST",
                data: {
                    heading: heading,
                    search: search,
                    entity: entity,
                    value: value
                },
                success: function(res) {
                    console.log('resres', res);
                    $("#custom-chat-data").html("");
                    $("#custom-chat-data").html(res);
                }
            });
        }

        $("#entity").change(function() {
            var search = $("#search").children("option:selected").val();
            var value = $("#value").val();
            var entity = $(this).val();

            getChat(search, value, entity, 1);
            $.cookie("page", 1, {
                expires: 1
            });
        });

        $("#value").keypress(function(e) {
            if (e.which == 13) {
                var value = $(this).val();
                var entity = $("#entity").children('option:selected').val();
                var search = $("#search").children("option:selected").val();

                getChat(search, value, entity, 1);
                $.cookie("page", 1, {
                    expires: 1
                });
            }
        })

        $(document).on('click', '.pagination a', function(event) {

            var value = $("#value").val();
            var entity = $("#entity").children('option:selected').val();
            var search = $("#search").children("option:selected").val();
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            getChat(search, value, entity, page);
            $.cookie("page", page, {
                expires: 1
            });

        });

        function getCountOfChat() {
            $.ajax({
                url: "/get-count-of-chat",
                type: "GET",
                dataType: "json",
                success: function(res) {
                    $("#publicCount").html(res.public);
                    $("#customCount").html(res.custom);
                    $("#userCount").html(res.user);
                    $("#flagCount").html(res.flag);
                }
            });
        }

        setInterval(function() {
            var value = $("#value").val();
            var entity = $("#entity").children("option:selected").val();
            var search = $("#search").children("option:selected").val();
            var page = $.cookie("page");
            getChat(search, value, entity, page);
            getCountOfChat();
        }, 10000);

        function putChatId(id) {
            $("#chat_id").val(id);
        }

        function putFlagChatId(id, fromuserId) {
            $("#chat_id1").val(id);
            $("#from_user_id1").val(fromuserId);
        }

        function putPublicId(id) {
            $("#chat_id2").val(id);
        }

        function putFlagPublicId(id) {
            $("#chat_id3").val(id);
        }

        function freezeAcc(id, status) {
            $("#userId1").val(id);
            $("#freeze1").val(status);
        }

        function activeAcc(id, status) {
            $("#userId2").val(id);
            $("#freeze2").val(status);
        }

        function removeFlag(id) {
            $("#userId3").val(id);
        }

        function redFlag(id) {
            $("#userId4").val(id);
        }

        function removeflagChat(id) {
            $("#chat_id4").val(id);
        }

        function removeflagChatPublic(id) {
            $("#chat_id5").val(id);
        }

        function putGroupId(id) {
            $("#chat_id6").val(id);
        }

        function getChatsForApprover() {
            $.ajax({
                url: '/get-chat-approver',
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    $.each(res.data, function(k, v) {
                        if (v.datetime_for_approver < v.currentTime) {
                            var id = v.id;
                            $.ajax({
                                url: "/flag-to-approvers",
                                type: "POST",
                                data: {
                                    id: id
                                },
                                dataType: "json",
                                success: function(res) {

                                }
                            });
                        }
                    })
                    $.each(res.data2, function(k, v) {
                        if (v.datetime_for_approver < v.currentTime) {
                            var pid = v.id;
                            $.ajax({
                                url: "/flag-to-public-approvers",
                                type: "POST",
                                data: {
                                    id: pid
                                },
                                dataType: "json",
                                success: function(res) {

                                }
                            });
                        }
                    })
                }
            });
        }

        setInterval(function() {
            getChatsForApprover();
        }, 5000);
        getChatsForApprover();


        if ({{ Auth::user()->freeze }} == 1) {
            setInterval(function() {
                $("body a").attr("href", "#");
                $("body a").removeAttr("target");
                $("body button").removeAttr("type");
                $("body a").removeAttr("data-target");
                $("body a").removeAttr("data-toggle");
                $("body button").removeAttr("data-target");
                $("body button").removeAttr("data-toggle");
                $("body form").attr("action", "#");

                $("body").children().css("opacity", 0.5);
                $("body marquee").css("opacity", 1);
            }, 5000);
            $("body a").attr("href", "#");
            $("body a").removeAttr("target");
            $("body button").removeAttr("type");
            $("body a").removeAttr("data-target");
            $("body button").removeAttr("data-target");
            $("body form").attr("action", "#");

            $("body").children().css("opacity", 0.5);
            $("body marquee").css("opacity", 1);
        }
    </script>
</body>

</html>
