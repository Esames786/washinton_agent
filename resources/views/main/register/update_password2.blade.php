<!DOCTYPE html>
<html lang="en" dir="ltr">

<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<head>
    

@include('partials.mainsite_pages.return_function')
<!-- Meta data -->
<meta charset="UTF-8">
<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
<meta content="SHIPA1" name="description">
<meta content="SHIPA1 IT DEPARTMENT" name="author">
<meta name="csrf-token" content="{{ csrf_token() }}" />

<!-- Title -->
<title>Update Password</title>





<!--Favicon -->
<link rel="icon" href="{{ url('assets/images/brand/favicon.ico')}}" type="image/x-icon"/>

<!--Bootstrap css -->
<link href="{{ url('assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

<link rel="stylesheet" href="{{ url('assets/js/jquery-ui-1.12.1/jquery-ui.min.css')}}">

<!-- Style css -->
<link href="{{ url('assets/css/style.css')}}" rel="stylesheet"/>
<link href="{{ url('assets/css/dark.css')}}" rel="stylesheet"/>
<link href="{{ url('assets/css/skin-modes.css')}}" rel="stylesheet"/>


<!-- Animate css -->
<link href="{{ url('assets/css/animated.css')}}" rel="stylesheet"/>

<!--Sidemenu css -->
<link href="{{ url('assets/css/sidemenu.css')}}" rel="stylesheet">

<!-- P-scroll bar css-->
<link href="{{ url('assets/plugins/p-scrollbar/p-scrollbar.css')}}" rel="stylesheet"/>

<!---Icons css-->
<link href="{{ url('assets/css/icons.css')}}" rel="stylesheet"/>


<link href="{{ url('assets/plugins/notify/css/jquery.growl.css')}}" rel="stylesheet" />
<link href="{{ url('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet" />

<!-- INTERNAl WYSIWYG Editor css -->
<link href="{{ url('assets/plugins/wysiwyag/richtext.css')}}" rel="stylesheet"/>

<!-- Data table css -->
<link href="{{ url('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet"/>
<link href="{{ url('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{ url('assets/plugins/datatable/responsive.bootstrap4.min.css')}}" rel="stylesheet"/>

<!-- INTERNAL Select2 css -->
<link href="{{ url('assets/plugins/select2/select2.min.css')}}" rel="stylesheet"/>

<!-- INTERNAL File Uploads css -->
<link href="{{ url('assets/plugins/fancyuploder/fancy_fileupload.css')}}" rel="stylesheet"/>

<!-- INTERNAL Time picker css -->
<link href="{{ url('assets/plugins/time-picker/jquery.timepicker.css')}}" rel="stylesheet"/>

<!-- INTERNAL Date Picker css -->
<link href="{{ url('assets/plugins/date-picker/date-picker.css')}}" rel="stylesheet"/>

<!-- INTERNAL File Uploads css-->
<link href="{{ url('assets/plugins/fileupload/css/fileupload.css')}}" rel="stylesheet" type="text/css"/>

<!-- INTERNAL Mutipleselect css-->
<link rel="stylesheet" href="{{ url('assets/plugins/multipleselect/multiple-select.css')}}">

<!-- INTERNAL Sumoselect css-->
<link rel="stylesheet" href="{{ url('assets/plugins/sumoselect/sumoselect.css')}}">

<!-- INTERNAL telephoneinput css-->
<link rel="stylesheet" href="{{ url('assets/plugins/telephoneinput/telephoneinput.css')}}">

<!-- INTERNAL Jquerytransfer css-->
<link rel="stylesheet" href="{{ url('assets/plugins/jQuerytransfer/jquery.transfer.css')}}">
<link rel="stylesheet" href="{{ url('assets/plugins/jQuerytransfer/icon_font/icon_font.css')}}">

<!-- INTERNAL multi css-->
<link rel="stylesheet" href="{{ url('assets/plugins/multi/multi.min.css')}}">

<!-- Simplebar css -->
<link rel="stylesheet" href="{{ url('assets/plugins/simplebar/css/simplebar.css')}}">

<!-- Color Skin css -->
<link id="theme" href="{{ url('assets/colors/color1.css" rel="stylesheet')}}" type="text/css"/>

<!-- Switcher css -->
<link rel="stylesheet" href="{{ url('assets/switcher/css/switcher.css')}}">
<link rel="stylesheet" href="{{ url('assets/switcher/demo.css')}}">


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

    .select2{
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
        padding: 3px;!important;
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
@if(Auth::user()->freeze == 1)
    <style>
        body * {
            user-select:none;
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
        border-color:#705ec8;
    }
    
    #addPortDetail .modal-dialog{
        max-width:80%;
    }
    
    .chat-center{
        position: fixed;
        bottom: -15px;
        right: 12px;
        z-index: 1000;
        flex-flow: row-reverse;
        width:85%;
    }

    .chat-user{
        background-image:url({{asset('public/images/chat-bg.jpg')}});
        overflow-y:scroll; 
        height:400px;
        background-size:cover;
        background-repeat:no-repeat;
    }
    .chat-user::before,.users-dispatchers::before{
        content: '';
        position: absolute;
        inset: 0;
        background: #000000;
        opacity: 0.3;
        top: 68px;
    }

    /* width */
    .chat-user::-webkit-scrollbar,.users-dispatchers::-webkit-scrollbar {
      width: 5px;
    }
    
    /* Track */
    .chat-user::-webkit-scrollbar-track,.users-dispatchers::-webkit-scrollbar-track {
      background: #f1f1f1; 
    }
     
    /* Handle */
    .chat-user::-webkit-scrollbar-thumb,.users-dispatchers::-webkit-scrollbar-thumb {
      background: #00c4ff; 
      border-radius:50px;
      border:#00c4ff;
    }
    
    .users-dispatchers{
        overflow-y:scroll; 
        height:450px;
    }
    
    .message-feed.right .mf-content:before
    {
        border-bottom: 8px solid #705ec8;
    }
    
    /* Style the tab */
    .table-responsive{
        overflow:unset !important;
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
        animation: fadeEffect 1s; /* Fading effect takes 1 second */
    }
    
    .dropdown-menu{
        left:-6rem !important;
    }

    /* Go from zero to full opacity */
    @keyframes fadeEffect {
        from {opacity: 0;}
        to {opacity: 1;}
    }
    
    .dropdown > .dropdown-menu {
        top: 200%;
        transition: 0.3s all ease-in-out;
    }

    .dropdown:hover > .dropdown-menu {
        display: block;
        top: 100%;
    }

    .dropdown > .dropdown-toggle:active {
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
    [class^="ti-"], [class*=" ti-"] {
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
@if(Auth::user()->freeze == 1)
<marquee class="bg-danger text-light" style="position:fixed;top:0;width:100%;z-index:99999;height:50px;opacity:1;"> <h3 class="mt-3">You are freezed by Chat Approver @if(isset(Auth::user()->freeze_reason)) because of this<b> "{{Auth::user()->freeze_reason}}" </b> @endif .Kindly contact with admin.</h3> </marquee>
@endif



<!---Global-loader-->
<div id="global-loader">
    <img src="{{ url('assets/images/svgs/loader.svg')}}" alt="loader">
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
                                <img src="{{ url('assets/images/brand/ship_logo.png')}}" class="header-brand-img desktop-lgo"
                                     alt="Admintro logo">
                                <img src="{{ url('assets/images/brand/ship_logo.png')}}" class="header-brand-img dark-logo"
                                     alt="Admintro logo">
                                <img src="{{ url('assets/images/brand/favicon.png')}}" class="header-brand-img mobile-logo"
                                     alt="Admintro logo">
                                <img src="{{ url('assets/images/brand/favicon1.png')}}" class="header-brand-img darkmobile-logo"
                                     alt="Admintro logo">
                            </a>
                
                
                            <div class="app-sidebar__toggle" data-toggle="sidebar" style="display:none">
                                <a class="open-toggle" href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                         viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round"
                                         class="feather feather-align-left header-icon mt-1">
                                        <line x1="17" y1="10" x2="3" y2="10"></line>
                                        <line x1="21" y1="6" x2="3" y2="6"></line>
                                        <line x1="21" y1="14" x2="3" y2="14"></line>
                                        <line x1="17" y1="18" x2="3" y2="18"></line>
                                    </svg>
                                </a>
                            </div>
                            
                            <div class="d-flex order-lg-2 ml-auto">
                                <div class="dropdown profile-dropdown">
                                    <a href="#" class="nav-link pr-0 leading-none" data-toggle="dropdown">
                												<span class=" js-search-result-thumbnail responsive-img img_border fa fa-user"
                                                                      style=" color: #6c757d; ">
                
                												</span>
                                    </a>
                
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow animated" style="top: 41px !important;">
                                        <div class="text-center">
                
                                            <a
                                                    class="dropdown-item text-center user pb-0 font-weight-bold"
                                                    style="text-transform: uppercase;">
                                                {{ isset(Auth::user()->name) ? Auth::user()->name : Auth::user()->email }}
                
                                            </a>
                                            <a class="dropdown-item d-flex" href="{{url('/update_password2')}}">
                                                <i class="fa fa-key pr-1 mt-1 ml-1"></i>
                                                <div class="">Change Password</div>
                                            </a>
                                            <a class="dropdown-item d-flex" href="{{route('logout')}}">
                                                <svg class="header-icon mr-2" xmlns="http://www.w3.org/2000/svg"
                                                     enable-background="new 0 0 24 24" height="24" viewBox="0 0 24 24"
                                                     width="24">
                                                    <g>
                                                        <rect fill="none" height="24" width="24"/>
                                                    </g>
                                                    <g>
                                                        <path
                                                                d="M11,7L9.6,8.4l2.6,2.6H2v2h10.2l-2.6,2.6L11,17l5-5L11,7z M20,19h-8v2h8c1.1,0,2-0.9,2-2V5c0-1.1-0.9-2-2-2h-8v2h8V19z"/>
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
                <div id="session_msg">
                    @if(Session::has('msg'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                      <strong>{{Session::get('msg')}}</strong>
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    @endif
                    @if(Session::has('err'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                      <strong>{{Session::get('err')}}</strong>
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    @endif
                </div>

                <!-- Row -->
                <div class="row">
                    <div class="col-12">
                        @if(session('flash_message'))
                            <div class="alert alert-success">
                                {{session('flash_message')}}
                            </div>
                        @endif
                        <!--div-->
                        <div class="page-header">
                            <div class="text-secondary text-center text-uppercase w-100">
                                <h1 class="my-4"><b>Update Password</b></h1>
                            </div>
                        </div>
                        <div class="">
                            <form action="/update_password_post2" id="form" method="POST" >
                                <input type="hidden" name="_token" value="{{ csrf_token()}}">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="card-title">Update Password</div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-4 col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Old Password</label>
                                                            <input type="password"  required name="old_password"
                                                                   class="form-control"
                                                                   placeholder="Old Password">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">New Passowrd</label>
                                                            <input type="password"  required name="password" id="password" onkeyup="checkPassword()"
                                                                   class="form-control" placeholder="New Passowrd">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">Confirm Password</label>
                                                            <input type="password" required  name="c_password" id="c_password" onkeyup="checkPassword()"
                                                                   class="form-control"
                                                                   placeholder="Confirm Password">
                                                        </div>
                                                    </div>
                        
                        
                                                </div>
                                            </div>
                                            <div class="card-footer text-center">
                                                <button type="submit" class="btn  btn-info">UPDATE</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Row-->
                            </form>
                            <br>
                            <br>
                        </div>
                <!-- /Row -->
                <div class="container position-relative">
                    <div class="row chat-center">
                    </div>
                </div>
                


            </div>
        </div>
    </div>


    <footer class="footer">
        <div class="container">
            <div class="row align-items-center flex-row-reverse">
                <div class="col-md-12 col-sm-12 text-center">
                    Copyright © {{date('Y')}} <a href="{{url('/dashboard')}}">SHIPA1</a>. Designed By <a
                            href="{{url('/dashboard')}}">SHIPA1
                        Frontend Team </a> All Rights Reserved ®.
                </div>
            </div>
        </div>
    </footer>
    <input type="hidden" id="time_user" value="{{Auth::user()->ss_time}}" />

</div><!-- End Page -->
<a href="#top" id="back-to-top"><i class="fe fe-chevrons-up"></i></a>

<!-- Jquery js-->

<script src="{{ url('assets/js/jquery-3.5.1.min.js')}}"></script>
<script src="{{ url('assets/js/htmlCanva.min.js')}}"></script>
<script src="{{ url('assets/js/canva.js')}}"></script>


<script src="{{ url('assets/js/jquery-ui-1.12.1/jquery-ui.min.js')}}"></script>
<script src="{{ url('assets/js/jquery-cookie.js')}}"></script>
<!-- Bootstrap4 js-->
<script src="{{ url('assets/plugins/bootstrap/popper.min.js')}}"></script>
<script src="{{ url('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

<!--Othercharts js-->
<script src="{{ url('assets/plugins/othercharts/jquery.sparkline.min.js')}}"></script>

<!-- Circle-progress js-->
<script src="{{ url('assets/js/circle-progress.min.js')}}"></script>

<!-- Jquery-rating js-->
<script src="{{ url('assets/plugins/rating/jquery.rating-stars.js')}}"></script>

<!--Sidemenu js-->
<script src="{{ url('assets/plugins/sidemenu/sidemenu.js')}}"></script>

<!-- P-scroll js-->
<script src="{{ url('assets/plugins/p-scrollbar/p-scrollbar.js')}}"></script>
<script src="{{ url('assets/plugins/p-scrollbar/p-scroll1.js')}}"></script>
<script src="{{ url('assets/plugins/p-scrollbar/p-scroll.js')}}"></script>



<!-- INTERNAL Notifications js -->
<script src="{{ url('assets/plugins/notify/js/rainbow.js')}}"></script>
<script src="{{ url('assets/plugins/notify/js/sample.js')}}?id=1"></script>
<script src="{{ url('assets/plugins/notify/js/jquery.growl.js')}}"></script>
<script src="{{ url('assets/plugins/notify/js/notifIt.js')}}"></script>


<!-- INTERNAL WYSIWYG Editor js -->
<script src="{{ url('assets/plugins/wysiwyag/jquery.richtext.js')}}"></script>
<script src="{{ url('assets/js/form-editor.js')}}"></script>

<!-- INTERNAL Data tables -->
<script src="{{ url('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
<script src="{{ url('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{ url('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{ url('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{ url('assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{ url('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
<script src="{{ url('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
<script src="{{ url('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{ url('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{ url('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{ url('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{ url('assets/plugins/datatable/responsive.bootstrap4.min.js')}}"></script>
<script src="{{url('assets/js/datatables.js')}}"></script>

<!-- INTERNAL Select2 js -->
<script src="{{ url('assets/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{ url('assets/js/select2.js')}}"></script>

<!-- INTERNAL Timepicker js -->
<script src="{{ url('assets/plugins/time-picker/jquery.timepicker.js')}}"></script>
<script src="{{ url('assets/plugins/time-picker/toggles.min.js')}}"></script>

<!-- INTERNAL Datepicker js -->
<script src="{{ url('assets/plugins/date-picker/date-picker.js')}}"></script>
<script src="{{ url('assets/plugins/date-picker/jquery-ui.js')}}"></script>
<script src="{{ url('assets/plugins/input-mask/jquery.maskedinput.js')}}"></script>

<!-- INTERNAL File-Uploads Js-->
<script src="{{ url('assets/plugins/fancyuploder/jquery.ui.widget.js')}}"></script>
<script src="{{ url('assets/plugins/fancyuploder/jquery.fileupload.js')}}"></script>
<script src="{{ url('assets/plugins/fancyuploder/jquery.iframe-transport.js')}}"></script>
<script src="{{ url('assets/plugins/fancyuploder/jquery.fancy-fileupload.js')}}"></script>
<script src="{{ url('assets/plugins/fancyuploder/fancy-uploader.js')}}"></script>

<!-- INTERNAL File uploads js -->
<script src="{{ url('assets/plugins/fileupload/js/dropify.js')}}"></script>
<script src="{{ url('assets/js/filupload.js')}}"></script>

<!-- INTERNAL Multipleselect js -->
<script src="{{ url('assets/plugins/multipleselect/multiple-select.js')}}"></script>
<script src="{{ url('assets/plugins/multipleselect/multi-select.js')}}"></script>

<!--INTERNAL Sumoselect js-->
<script src="{{ url('assets/plugins/sumoselect/jquery.sumoselect.js')}}"></script>

<!--INTERNAL telephoneinput js-->
<script src="{{ url('assets/plugins/telephoneinput/telephoneinput.js')}}"></script>
<script src="{{ url('assets/plugins/telephoneinput/inttelephoneinput.js')}}"></script>

<!--INTERNAL jquery transfer js-->
<script src="{{ url('assets/plugins/jQuerytransfer/jquery.transfer.js')}}"></script>

<!--INTERNAL multi js-->
<script src="{{ url('assets/plugins/multi/multi.min.js')}}"></script>

<!--INTERNAL Form Advanced Element -->
<script src="{{ url('assets/js/formelementadvnced.js')}}"></script>
<script src="{{ url('assets/js/form-elements.js')}}"></script>
<script src="{{ url('assets/js/file-upload.js')}}"></script>
<!-- Simplebar JS -->
<script src="{{ url('assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
<!-- Custom js-->
<script src="{{ url('assets/js/custom.js')}}"></script>

<!-- Switcher js-->
<script src="{{ url('assets/switcher/js/switcher.js')}}"></script>
@if(Route::is('add_new'))

@endif

<script type="text/javascript">  
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var role = "{{Auth::user()->role}}";
    
    if(role > 1)
    {
        function take_ss()
        {
            var dataURL = {};
            if($("#time_user").val() >= 270)
            {
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
                         success: function(res) { 
                         }
                     });  
                });  
                $.ajax({
                    url: "{{ url('/time_user') }}",
                    type: "GET",
                    dataType:"json",
                    success:function(res)
                    {
                        $("#time_user").val(res.time);
                    }
                });
            }
            else
            {
                $.ajax({
                    url: "{{ url('/time_user') }}",
                    type: "GET",
                    dataType:"json",
                    success:function(res)
                    {
                        $("#time_user").val(res.time);
                    }
                });
            }
        }
        
        setInterval(function(){
            take_ss();
        },1000 *30);
        
        setTimeout(function(){
            take_ss();
        },1000);
    }
</script>  
<script>
    function checkPassword(){
        var password = $("#password").val();
        var c_password = $("#c_password").val();
        if(c_password != password){
            $("#password").addClass("error");
            $("#c_password").addClass("error");
        }
        else if(c_password == password){
            $("#password").removeClass("error");
            $("#c_password").removeClass("error");


        }
    }

</script>
</body>
</html>
