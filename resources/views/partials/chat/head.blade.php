<meta name="csrf-token" content="{{ csrf_token() }}" />
<!--Favicon -->
<link rel="icon" href="{{ url('assets/images/brand/favicon.ico" type="image/x-icon') }}"/>

<!--Bootstrap css -->
<link href="{{ url('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

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

<!-- Simplebar css -->
<link rel="stylesheet" href="{{ url('assets/plugins/simplebar/css/simplebar.css') }}">

<!-- Color Skin css -->
<link id="theme" href="{{ url('assets/colors/color1.css') }}" rel="stylesheet" type="text/css"/>

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
        color: #020402;
        opacity: 2;
        border: 1px solid black;
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
