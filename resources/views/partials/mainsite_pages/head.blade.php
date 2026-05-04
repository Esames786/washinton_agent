
<!-- Meta data -->
<meta charset="UTF-8">
<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
<meta content="SHIPA1" name="description">
<meta content="SHIPA1 IT DEPARTMENT" name="author">
<meta name="csrf-token" content="{{ csrf_token() }}" />

<!-- Title -->
<title>@if (trim($__env->yieldContent('template_title')))@yield('template_title') | @endif {{ config('app.name', Lang::get('titles.app')) }}</title>





<!--Favicon -->
<link rel="icon" href="{{ url('assets/images/brand/favicon.ico')}}" type="image/x-icon"/>

<!--Bootstrap css -->
<link href="{{ url('assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

<link rel="stylesheet" href="{{ url('assets/js/jquery-ui-1.12.1/jquery-ui.min.css')}}">

<!-- Style css -->
<link href="{{ url('assets/css/style.css')}}?id=1" rel="stylesheet"/>
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
        /*padding: 15px;*/
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

<style>
    .ticker-wrapper {
        overflow: hidden;
        position: relative;
        background: #fff3cd;
        border-left: 2px solid #ffc107; /* Thin yellow bar */
        padding: 10px;
        margin: 10px 0;
        height: 40px;
    }

    .ticker-message {
        display: inline-block;
        white-space: nowrap;
        position: absolute;
        will-change: transform;
        animation: scrollTicker 30s linear infinite;
        font-size: 14px;
    }

    @keyframes scrollTicker {
        0% { transform: translateX(100%); }
        100% { transform: translateX(-100%); }
    }
</style>


<style>
    /* container */
    .pcn-container{
        position:fixed; right:18px; top:50%; transform:translateY(-50%);
        z-index:1080; display:flex; flex-direction:column; gap:10px; width:320px; pointer-events:none;
    }

    /* card */
    .pcn-card{
        position:relative;
        pointer-events:auto;
        background:#0f172a;  /* slate-900 */
        color:#e5e7eb;
        border-radius:12px;
        box-shadow:0 12px 30px rgba(0,0,0,.28);
        overflow:hidden;
        opacity:0; transform:translateX(18px);
        animation:pcn-in .25s ease-out forwards;
        font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;
    }
    @keyframes pcn-in{ to{ opacity:1; transform:translateX(0); } }

    .pcn-header{ display:flex; align-items:center; gap:8px; padding:10px 12px 0; }
    .pcn-dot{ width:10px; height:10px; border-radius:50%; background:#60a5fa; }
    .pcn-title{ font-weight:600; font-size:14px; color:#fff; line-height:1.2; flex:1; }
    .pcn-close{ border:0; background:transparent; cursor:pointer; font-size:18px; line-height:1; color:#9ca3af; padding:0 2px; }
    .pcn-close:hover{ color:#e5e7eb; }
    .pcn-body{ padding:8px 12px 0; font-size:13px; color:#e5e7eb; }
    .pcn-meta{ font-size:12px; color:#9ca3af; margin-top:6px; }

    /* actions */
    .pcn-actions{
        display:flex; gap:8px; padding:10px 12px 12px;
    }
    .pcn-btn{
        appearance:none; border:1px solid transparent; border-radius:8px;
        padding:8px 12px; font-size:13px; cursor:pointer; transition:transform .08s ease, opacity .15s ease, background .15s ease;
    }
    .pcn-btn:active{ transform:translateY(1px); }
    .pcn-btn-accept{ background:#22c55e; color:#052e16; border-color:#16a34a; font-weight:600; }
    .pcn-btn-accept:hover{ background:#16a34a; }
    .pcn-btn-decline{ background:#111827; color:#e5e7eb; border-color:#374151; }
    .pcn-btn-decline:hover{ background:#0b1220; }

    /* bottom progress bar (kept) */
    .pcn-progress{ height:3px; background:#1f2937; width:100%; }
    .pcn-progress>div{ height:100%; width:100%; background:#60a5fa; transform-origin:left center; transform:scaleX(1); transition:transform linear; }

    /* precise animated border — normalized path length so timing is exact */
    .pcn-border{
        position:absolute; inset:0; pointer-events:none; border-radius:inherit;
        filter: drop-shadow(0 0 6px rgba(96,165,250,.45));
    }
    .pcn-border-path{
        fill:none; stroke-width:2; vector-effect:non-scaling-stroke;
        stroke-dasharray:100;          /* exact perimeter units (via pathLength) */
        stroke-dashoffset:100;         /* start hidden, count down to 0 */
        animation:pcn-stroke var(--pcn-duration, 10000ms) linear forwards;
    }
    @keyframes pcn-stroke{ to{ stroke-dashoffset:0; } }
</style>



