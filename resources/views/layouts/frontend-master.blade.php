<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Hello Tranport | The Premier Auto Shipping Company – Nationwide Service</title>
        <meta name="description" content="Experience the top transportation services throughout the continental United States with Premier Auto Shipping Company.">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google-site-verification" content="dwlNH_KoCtphxr8_X75_OXA-nxdZWfmnrCrJssvnPO4" />
        <!-- Place favicon.ico in the root directory -->
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('frontend/img/logo/smalllogo.png') }}">
        <!-- CSS here -->
        <link rel="stylesheet" href="{{ asset('frontend/css/preloader.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/css/meanmenu.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/css/animate.min.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/css/swiper-bundle.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/css/backToTop.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/css/magnific-popup.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/css/ui-range-slider.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/css/nice-select.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/css/fontAwesome5Pro.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/css/flaticon.css') }}">
{{--        <link rel="stylesheet" href="{{ asset('frontend/css/icomoon.css') }}">--}}
        <link rel="stylesheet" href="{{ asset('frontend/css/default.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-N4GGWBB0YZ"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'G-N4GGWBB0YZ');
        </script>
        <!-- Google tag (gtag.js) -->
    </head>
    <style>
        ul.dropdown-menu.bg-danger.show li {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0px 8px;
            width: 100%;
        }

        .btn-font {
        font-size: 16px;
        color: var(--clr-common-white);
        padding: 17px 0;
        display: block;
        text-transform: uppercase;
        font-weight: 600;
        font-family: oswald, sans-serif;
        }
        .column-gap {
                column-gap: 40px;
        }
        .hover:hover {
            color: #db1c29;
        }
        h4.hover {
            cursor: pointer;
        }
    </style>

{{--    chat--}}

    <style>
        /* Hide the "Chat Us Now" text on all screens */
        .css-e4pgre {
            display: none !important;

            z-index: 10000;
        }

        /* Adjust the chat container to be a small floating circular button */
        .css-138p0k2 {
            border: none !important;
            width: 90px !important;
            height: 80px !important;
            border-radius: 50% !important;
            padding: 0 !important;
            /* background-color: #6a9c2f !important; */
            box-shadow: none !important;
            position: fixed !important;
            bottom: 8px !important;
            right: 66px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: #fff !important;
            cursor: pointer !important;
            z-index: 9999 !important;

        }
        /* .chat-icon-img {
          width: 50px;
          height: 50px;
          display: block;
          margin: auto;
        } */
        /* Make the button fill the container */
        .css-1iovl8i {
            border: none !important;
            width: 100% !important;
            height: 100% !important;
            padding: 0 !important;
            border-radius: 50% !important;
            background: transparent !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            z-index: 100000;

        }
        .chat-icon-img {
            width: 50px;
            height: 50px;
            display: block;
            margin: auto;
            z-index: 10000;
            position: relative;
            background-color: #ffffff; /* Fills inside with white */
            border-radius: 50%; /* Optional: makes it circular if image is square */

        }
        /* Make the button fill the container
        .css-1iovl8i {
          border: none !important;
          width: 100% !important;
          height: 100% !important;
          padding: 0 !important;
          border-radius: 50% !important;
          background: transparent !important;
          display: flex !important;
          align-items: center !important;
          justify-content: center !important;
          margin-left: -116px;
          margin-bottom: -141px;
          z-index: 100000;
        } */
        @media screen and (min-width:480px) {
            .css-1iovl8i {
                border: none !important;
                width: 100% !important;
                height: 100% !important;
                padding: 0 !important;
                border-radius: 50% !important;
                background: transparent !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;

                position: relative; /* or absolute/fixed depending on context */
                z-index: 10000; /* Higher number = more in front */

            }
        }

        /* SVG icon sizing and centering */
        .css-1usdo54 {
            stroke: none !important;
            width: 23px !important;
            height: 23px !important;
            margin: auto !important;
            display: block !important;
        }
        .css-1usdo54 path {
            fill: #ffffff !important;
        }


        /* Optional: hide any extra div inside the button */
        .css-anyrkw {

            z-index: 10000;
        }

    </style>
    <style>
        #chat-widget {
            display: none;
            transform-origin: top center;
        }

        /* Flip and bounce animation */
        @keyframes flipInAirBounce {
            0% {
                transform: perspective(600px) rotateX(90deg);
                opacity: 0;
            }

            60% {
                transform: perspective(600px) rotateX(0deg);
                opacity: 1;
            }

            80% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0);
            }
        }

        .flip-bounce {
            animation: flipInAirBounce 0.8s ease-out;
        }
    </style>
    <style>
        .inactivee {
            pointer-events: none;
            /* Disable clicks only when the iframe is hidden */
        }

        .activee {
            pointer-events: auto;
            /* Disable clicks only when the iframe is hidden */
        }

        /* Blinking animation */
        @keyframes blink {
            0% {
                color: red;
            }

            50% {
                color: blue;
            }

            100% {
                color: red;
            }
        }

        /* Apply the animation to the element */
        .blink {
            animation: blink 1s infinite;
            /* 1s duration, infinite loop */
        }
    </style>

    <style type="text/css">
        /** [AIV]  Build version: 3.1.13 - Monday, June 29th, 2020, 2:15:05 PM  **/
        #_71A63tRBiHHb3tRBnQfK_1,
        #_71A63tRBiHHb3tRBnQfK_1 :after,
        #_71A63tRBiHHb3tRBnQfK_1 :before,
        .proofNotificationWrapper img,
        .proofNotificationWrapper svg {
            all: initial;
        }

        #_71A63tRBiHHb3tRBnQfK_1 {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 990; /* Ensure it's behind everything */

            /* Typography styles */
            font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica, Arial, sans-serif;
            font-style: normal;
            font-weight: 400;
            line-height: 1.5;
            letter-spacing: normal;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
            -webkit-font-feature-settings: "kern" 1, "dlig" 1, "opbd" 1, "ss01" 1;
            font-feature-settings: "kern" 1, "dlig" 1, "opbd" 1, "ss01" 1;
            text-shadow: rgba(0, 0, 0, 0.01) 0 0 1px;
        }


        #_71A63tRBiHHb3tRBnQfK_1._71A6x4GfiHHbx4GfnQfK_1 {
            bottom: auto;
            top: 0;
            width: 250px
            z-index: 9990;
        }

        #_71A63tRBiHHb3tRBnQfK_1 ._71A63x22iHHb3x22nQfK_1 {
            overflow: hidden;
            padding-top: 5px;
            z-index: 9990;
        }


        @media screen and (min-width:480px) {
            #_71A63tRBiHHb3tRBnQfK_1._71A62TNmiHHb2TNmnQfK_1 {
                left: auto;
                right: 10px;
                width: 250px;
                z-index: 9990;
            }
        }

        #_71A63tRBiHHb3tRBnQfK_1 button,
        #_71A63tRBiHHb3tRBnQfK_1 input,
        #_71A63tRBiHHb3tRBnQfK_1 optgroup,
        #_71A63tRBiHHb3tRBnQfK_1 select,
        #_71A63tRBiHHb3tRBnQfK_1 textarea {
            margin: 0;
            font-family: inherit;
            font-size: inherit;
            line-height: inherit;
            z-index: 9990;
        }

        #_71A63tRBiHHb3tRBnQfK_1 ._71A6egA4iHHbegA4nQfK_1,
        #_71A63tRBiHHb3tRBnQfK_1 ._71A6zUl0iHHbzUl0nQfK_1,
        #_71A63tRBiHHb3tRBnQfK_1 ._71A61g-SiHHb1g-SnQfK_1,
        #_71A63tRBiHHb3tRBnQfK_1 ._71A61HO2iHHb1HO2nQfK_1,
        #_71A63tRBiHHb3tRBnQfK_1 ._71A62D4AiHHb2D4AnQfK_1,
        #_71A63tRBiHHb3tRBnQfK_1 ._71A62eORiHHb2eORnQfK_1,
        #_71A63tRBiHHb3tRBnQfK_1 h1,
        #_71A63tRBiHHb3tRBnQfK_1 h2,
        #_71A63tRBiHHb3tRBnQfK_1 h3,
        #_71A63tRBiHHb3tRBnQfK_1 h4,
        #_71A63tRBiHHb3tRBnQfK_1 h5,
        #_71A63tRBiHHb3tRBnQfK_1 h6 {
            font-family: inherit;
            z-index: 9990;
        }

        .bounceBottom-enter-active {
            animation: bounceBottomUp 1.1s linear both;
        }

        @keyframes bounceBottomUp {
            0% {
                transform: matrix(1, 0, 0, 1, 0, 100);
            }

            4.1% {
                transform: matrix(1, 0, 0, 1, 0, 41.971);
            }

            8.11% {
                transform: matrix(1, 0, 0, 1, 0, 10.549);
            }

            12.11% {
                transform: matrix(1, 0, 0, 1, 0, -1.843);
            }

            16.12% {
                transform: matrix(1, 0, 0, 1, 0, -4.336);
            }

            27.23% {
                transform: matrix(1, 0, 0, 1, 0, -.784);
            }

            38.34% {
                transform: matrix(1, 0, 0, 1, 0, .104);
            }

            60.56% {
                transform: matrix(1, 0, 0, 1, 0, -.002);
            }

            82.78% {
                transform: matrix(1, 0, 0, 1, 0, 0);
            }

            to {
                transform: matrix(1, 0, 0, 1, 0, 0);
            }
        }

        .bounceBottom-leave-active {
            animation: bounceBottomDown 2s linear both;
        }

        @keyframes bounceBottomDown {
            0% {
                transform: matrix(1, 0, 0, 1, 0, 0);
            }

            4.2% {
                transform: matrix(1, 0, 0, 1, 0, 54.927);
            }

            8.31% {
                transform: matrix(1, 0, 0, 1, 0, 88.411);
            }

            12.51% {
                transform: matrix(1, 0, 0, 1, 0, 103.215);
            }

            16.62% {
                transform: matrix(1, 0, 0, 1, 0, 106.331);
            }

            27.73% {
                transform: matrix(1, 0, 0, 1, 0, 101.285);
            }

            38.84% {
                transform: matrix(1, 0, 0, 1, 0, 99.747);
            }

            61.06% {
                transform: matrix(1, 0, 0, 1, 0, 100.01);
            }

            83.28% {
                transform: matrix(1, 0, 0, 1, 0, 100);
            }

            to {
                transform: matrix(1, 0, 0, 1, 0, 100);
            }
        }

        .bounceTop-enter-active {
            animation: bounceTopDown 1100down linear both;
        }

        @keyframes bounceTopDown {
            0% {
                transform: matrix(1, 0, 0, 1, 0, -100);
            }

            4.1% {
                transform: matrix(1, 0, 0, 1, 0, -41.971);
            }

            8.11% {
                transform: matrix(1, 0, 0, 1, 0, -10.549);
            }

            12.11% {
                transform: matrix(1, 0, 0, 1, 0, 1.843);
            }

            16.12% {
                transform: matrix(1, 0, 0, 1, 0, 4.336);
            }

            27.23% {
                transform: matrix(1, 0, 0, 1, 0, .784);
            }

            38.34% {
                transform: matrix(1, 0, 0, 1, 0, -.104);
            }

            60.56% {
                transform: matrix(1, 0, 0, 1, 0, .002);
            }

            82.78% {
                transform: matrix(1, 0, 0, 1, 0, 0);
            }

            to {
                transform: matrix(1, 0, 0, 1, 0, 0);
            }
        }

        .bounceTop-leave-active {
            animation: bounceTopUp 2s linear both;
        }

        @keyframes bounceTopUp {
            0% {
                transform: matrix(1, 0, 0, 1, 0, 0);
            }

            4.1% {
                transform: matrix(1, 0, 0, 1, 0, -58.029);
            }

            8.11% {
                transform: matrix(1, 0, 0, 1, 0, -89.451);
            }

            12.11% {
                transform: matrix(1, 0, 0, 1, 0, -101.843);
            }

            16.12% {
                transform: matrix(1, 0, 0, 1, 0, -104.336);
            }

            27.23% {
                transform: matrix(1, 0, 0, 1, 0, -100.784);
            }

            38.34% {
                transform: matrix(1, 0, 0, 1, 0, -99.896);
            }

            60.56% {
                transform: matrix(1, 0, 0, 1, 0, -100.002);
            }

            82.78% {
                transform: matrix(1, 0, 0, 1, 0, -100);
            }

            to {
                transform: matrix(1, 0, 0, 1, 0, -100);
            }
        }

        ._71A61j23iHHb1j23nQfK_0 {
            display: block;
            position: absolute;
            float: left;
            left: 33px;
            top: 33px;
            width: 26px;
            height: 26px;
        }

        ._71A61j23iHHb1j23nQfK_0 ._71A62k0eiHHb2k0enQfK_0,
        ._71A61j23iHHb1j23nQfK_0 ._71A63crPiHHb3crPnQfK_0,
        ._71A61j23iHHb1j23nQfK_0 ._71A63g5uiHHb3g5unQfK_0,
        ._71A61j23iHHb1j23nQfK_0 ._71A63SB2iHHb3SB2nQfK_0 {
            border-radius: 10px;
            width: 22px;
            height: 22px;
            background: #0095f7;
            box-shadow: 0 0 0 rgba(0, 149, 247, .4);
            font-size: 10px;
        }

        @media screen and (min-width:480px) {

            ._71A61j23iHHb1j23nQfK_0 ._71A62k0eiHHb2k0enQfK_0,
            ._71A61j23iHHb1j23nQfK_0 ._71A63crPiHHb3crPnQfK_0,
            ._71A61j23iHHb1j23nQfK_0 ._71A63g5uiHHb3g5unQfK_0,
            ._71A61j23iHHb1j23nQfK_0 ._71A63SB2iHHb3SB2nQfK_0 {
                border-radius: 50%;
                font-size: 10px;
            }
        }

        ._71A61j23iHHb1j23nQfK_0 ._71A62k0eiHHb2k0enQfK_0 {
            animation: _71A62k0eiHHb2k0enQfK_0 3s linear infinite;
        }

        ._71A61j23iHHb1j23nQfK_0 ._71A63g5uiHHb3g5unQfK_0 {
            animation: _71A62k0eiHHb2k0enQfK_0 3s linear infinite 1.5s;
        }

        @keyframes _71A62k0eiHHb2k0enQfK_0 {
            0% {
                box-shadow: 0 0 0 0 rgba(0, 149, 247, .4);
            }

            to {
                box-shadow: 0 0 0 19px rgba(0, 149, 247, 0);
            }
        }

        ._71A62qKhiHHb2qKhnQfK_0 {
            -ms-flex-align: normal;
            align-items: normal;
            display: grid;
            grid-template-columns: 56px 1fr;
            grid-gap: 16px;
            -ms-flex-align: center;
            align-items: center;
            position: relative;
            box-sizing: border-box;
            border: 1px solid rgba(216, 217, 226, .5);
            box-shadow: 10px 20px 40px 0 rgba(36, 35, 40, .1);
            padding: 16px;
            height: 88px;
            pointer-events: auto;
            overflow: hidden;
            background-color: #fff;
            line-height: 1em;
        }

        ._71A62qKhiHHb2qKhnQfK_0._71A6OW-4iHHbOW-4nQfK_0 {
            cursor: pointer;
            transition: all .1s ease-in-out;
        }

        ._71A62qKhiHHb2qKhnQfK_0._71A6OW-4iHHbOW-4nQfK_0:hover {
            box-shadow: 0 0 0 4px rgba(144, 164, 174, .2);
        }

        @media screen and (min-width:480px) {
            ._71A62qKhiHHb2qKhnQfK_0 {
                width: 352px;
                margin: 0 0 10px 10px;
                border-radius: 50px;
            }


            ._71A63tRBiHHb3tRBnQfK_1 {
                width: 271px;
                height: 80px;
            }
            ._71A62qKhiHHb2qKhnQfK_0._71A6OW-4iHHbOW-4nQfK_0 {
                cursor: pointer;
                transition: all .1s ease-in-out;
            }

            ._71A62qKhiHHb2qKhnQfK_0._71A6OW-4iHHbOW-4nQfK_0:hover {
                box-shadow: 0 0 0 4px rgba(144, 164, 174, .2);
            }

            ._71A62qKhiHHb2qKhnQfK_0._71A63PFfiHHb3PFfnQfK_0 {
                border-radius: 5px;
            }
        }

        @media screen and (min-width:480px) and (min-width:480px) {

            ._71A62qKhiHHb2qKhnQfK_0._71A63PFfiHHb3PFfnQfK_0 ._71A6iq4LiHHbiq4LnQfK_0 img,
            ._71A62qKhiHHb2qKhnQfK_0._71A63PFfiHHb3PFfnQfK_0 ._71A6iq4LiHHbiq4LnQfK_0 svg {
                border-radius: 5px;
            }
        }

        ._71A62qKhiHHb2qKhnQfK_0 ._71A6iq4LiHHbiq4LnQfK_0 img,
        ._71A62qKhiHHb2qKhnQfK_0 ._71A6iq4LiHHbiq4LnQfK_0 svg {
            display: block;
            width: 56px;
            height: 56px;
        }

        @media screen and (min-width:480px) {

            ._71A62qKhiHHb2qKhnQfK_0 ._71A6iq4LiHHbiq4LnQfK_0 img,
            ._71A62qKhiHHb2qKhnQfK_0 ._71A6iq4LiHHbiq4LnQfK_0 svg {
                border-radius: 50%;
            }
        }

        ._71A62qKhiHHb2qKhnQfK_0 ._71A61iAyiHHb1iAynQfK_0 ._71A62XMSiHHb2XMSnQfK_0>span {
            display: inline-block;
            margin-left: -4px;
            margin-bottom: 2px;
            padding: 2px 4px;
            background-color: #f0f1f7;
            line-height: 14px;
            font-size: 14px;
            font-weight: 500;
            color: #242328;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow-x: hidden;
            max-width: 220px;
            vertical-align: middle;
        }

        ._71A62qKhiHHb2qKhnQfK_0 ._71A61iAyiHHb1iAynQfK_0 ._71A66Nl7iHHb6Nl7nQfK_0 {
            margin-top: 6px;
            margin-bottom: 2px;
            line-height: 13px;
            font-size: 13px;
            font-weight: 300;
            color: #686b81;
            max-height: 1em;
            overflow: hidden;
        }

        ._71A62qKhiHHb2qKhnQfK_0 ._71A61iAyiHHb1iAynQfK_0 ._71A62e55iHHb2e55nQfK_0 {
            display: inline-block;
            margin-right: 4px;
            line-height: 11px;
            font-size: 11px;
            font-weight: 300;
            color: #9196b6;
        }

        ._71A62qKhiHHb2qKhnQfK_0 ._71A61iAyiHHb1iAynQfK_0 ._71A63zU5iHHb3zU5nQfK_0 {
            margin-bottom: -2px;
            vertical-align: middle;
            cursor: pointer;
        }

        ._71A62qKhiHHb2qKhnQfK_0 ._71A61iAyiHHb1iAynQfK_0 ._71A63zU5iHHb3zU5nQfK_0:hover g {
            fill: #0f84d7;
        }

        ._71A62qKhiHHb2qKhnQfK_0 ._71A6W__YiHHbW__YnQfK_0 {
            cursor: pointer;
            position: absolute;
            right: 18px;
            top: 4px;
            opacity: .1;
            width: 18px;
            height: 18px;
            margin: 0;
            padding: 0;
        }

        ._71A62qKhiHHb2qKhnQfK_0 ._71A6W__YiHHbW__YnQfK_0 svg {
            width: 11px;
            height: 11px;
            margin: 0;
            padding: 0;
            display: inline-block;
        }

        ._71A62qKhiHHb2qKhnQfK_0 ._71A6W__YiHHbW__YnQfK_0:hover {
            opacity: .4;
        }

        ._71A61oTbiHHb1oTbnQfK_0 {
            width: 56px;
            height: 56px;
            background-color: #ffebee;
            border-radius: 100%;
        }

        ._71A61oTbiHHb1oTbnQfK_0._71A624CaiHHb24CanQfK_0 {
            border-radius: 5px;
        }

        @media screen and (min-width:480px) {
            #_71A62fZIiHHb2fZInQfK_0 {
                right: auto;
            }
        }

        #_71A62fZIiHHb2fZInQfK_0 ._71A63zjZiHHb3zjZnQfK_0 {
            border: 1px solid rgba(216, 217, 226, .5);
            pointer-events: auto;
            overflow: hidden;
            position: relative;
            height: 88px;
            background-color: #fff;
            box-sizing: border-box;
            line-height: 1em;
            box-shadow: 10px 20px 40px 0 rgba(36, 35, 40, .1);
        }

        @media screen and (min-width:480px) {
            #_71A62fZIiHHb2fZInQfK_0 ._71A63zjZiHHb3zjZnQfK_0 {
                width: 352px;
                margin: 0 0 10px 10px;
                border-radius: 50px;
            }

            #_71A62fZIiHHb2fZInQfK_0 ._71A63zjZiHHb3zjZnQfK_0._71A63SlriHHb3SlrnQfK_0 {
                border-radius: 5px;
            }

            #_71A62fZIiHHb2fZInQfK_0 ._71A63zjZiHHb3zjZnQfK_0._71A62riIiHHb2riInQfK_0 {
                cursor: pointer;
                transition: all .1s ease-in-out;
            }

            #_71A62fZIiHHb2fZInQfK_0 ._71A63zjZiHHb3zjZnQfK_0._71A62riIiHHb2riInQfK_0:hover {
                box-shadow: 0 0 0 4px rgba(144, 164, 174, .2);
            }
        }

        #_71A62fZIiHHb2fZInQfK_0 ._71A63zjZiHHb3zjZnQfK_0 ._71A61JYFiHHb1JYFnQfK_0 {
            display: block;
            position: absolute;
            float: left;
            top: 16px;
            left: 16px;
            height: 56px;
            width: 56px;
        }

        #_71A62fZIiHHb2fZInQfK_0 ._71A63zjZiHHb3zjZnQfK_0 ._71A61JYFiHHb1JYFnQfK_0 img {
            border-radius: 10px;
            height: 56px;
            width: 56px;
        }

        @media screen and (min-width:480px) {
            #_71A62fZIiHHb2fZInQfK_0 ._71A63zjZiHHb3zjZnQfK_0 ._71A61JYFiHHb1JYFnQfK_0 img {
                border-radius: 50%;
            }

            #_71A62fZIiHHb2fZInQfK_0 ._71A63zjZiHHb3zjZnQfK_0 ._71A61JYFiHHb1JYFnQfK_0 img._71A63SlriHHb3SlrnQfK_0 {
                border-radius: 5px;
            }
        }

        #_71A62fZIiHHb2fZInQfK_0 ._71A63zjZiHHb3zjZnQfK_0 ._71A61JYFiHHb1JYFnQfK_0 svg {
            border-radius: 50%;
        }

        #_71A62fZIiHHb2fZInQfK_0 ._71A63zjZiHHb3zjZnQfK_0 ._71A61JYFiHHb1JYFnQfK_0 svg._71A63SlriHHb3SlrnQfK_0 {
            border-radius: 5px;
        }

        #_71A62fZIiHHb2fZInQfK_0 ._71A63zjZiHHb3zjZnQfK_0 ._71A62EbCiHHb2EbCnQfK_0 ._71A62AUliHHb2AUlnQfK_0,
        #_71A62fZIiHHb2fZInQfK_0 ._71A63zjZiHHb3zjZnQfK_0 ._71A62EbCiHHb2EbCnQfK_0 ._71A62PlWiHHb2PlWnQfK_0,
        #_71A62fZIiHHb2fZInQfK_0 ._71A63zjZiHHb3zjZnQfK_0 ._71A62EbCiHHb2EbCnQfK_0 ._71A613BWiHHb13BWnQfK_0 {
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow-x: hidden;
            max-width: 220px;
        }

        #_71A62fZIiHHb2fZInQfK_0 ._71A63zjZiHHb3zjZnQfK_0 ._71A62EbCiHHb2EbCnQfK_0 ._71A62AUliHHb2AUlnQfK_0 {
            display: inline-block;
            position: absolute;
            top: 15px;
            left: 90px;
            border-radius: 2px;
            padding: 2px 6px;
            font-size: 14px;
            font-weight: 600;
            color: #242328;
            background-color: #f0f1f7;
        }

        #_71A62fZIiHHb2fZInQfK_0 ._71A63zjZiHHb3zjZnQfK_0 ._71A62EbCiHHb2EbCnQfK_0 ._71A613BWiHHb13BWnQfK_0 {
            position: absolute;
            top: 39px;
            left: 96px;
            line-height: normal;
            font-size: 13px;
            color: #686b81;
        }

        #_71A62fZIiHHb2fZInQfK_0 ._71A63zjZiHHb3zjZnQfK_0 ._71A62EbCiHHb2EbCnQfK_0 ._71A62PlWiHHb2PlWnQfK_0 {
            position: absolute;
            bottom: 14px;
            left: 96px;
            line-height: normal;
            display: inline-block;
            color: #9196b6;
            font-size: 11px;
            overflow: visible;
        }

        #_71A62fZIiHHb2fZInQfK_0 ._71A63zjZiHHb3zjZnQfK_0 ._71A62EbCiHHb2EbCnQfK_0 ._71A624uuiHHb24uunQfK_0 {
            position: absolute;
            bottom: -3px;
            display: inline-block;
            margin-left: 8px;
            vertical-align: sub;
            cursor: pointer;
        }

        #_71A62fZIiHHb2fZInQfK_0 ._71A63zjZiHHb3zjZnQfK_0 ._71A62EbCiHHb2EbCnQfK_0 ._71A624uuiHHb24uunQfK_0:hover svg g {
            fill: #0f84d7;
        }

        #_71A62fZIiHHb2fZInQfK_0 ._71A63zjZiHHb3zjZnQfK_0 ._71A61N_EiHHb1N_EnQfK_0 {
            cursor: pointer;
            position: absolute;
            right: 18px;
            top: 4px;
            opacity: .1;
            width: 18px;
            height: 18px;
            margin: 0;
            padding: 0;
        }

        #_71A62fZIiHHb2fZInQfK_0 ._71A63zjZiHHb3zjZnQfK_0 ._71A61N_EiHHb1N_EnQfK_0 svg {
            width: 11px;
            height: 11px;
            margin: 0;
            padding: 0;
            display: inline-block;
        }

        #_71A62fZIiHHb2fZInQfK_0 ._71A63zjZiHHb3zjZnQfK_0 ._71A61N_EiHHb1N_EnQfK_0:hover {
            opacity: .4;
        }

        @media screen and (min-width:480px) {
            #_71A63dGiiHHb3dGinQfK_0 {
                right: auto;
            }
        }

        #_71A63dGiiHHb3dGinQfK_0 ._71A62B_4iHHb2B_4nQfK_0 {
            border: 1px solid rgba(216, 217, 226, .5);
            pointer-events: auto;
            overflow: hidden;
            position: relative;
            height: 88px;
            width: 100%;
            margin: 0 auto;
            background-color: #fff;
            padding: 24px;
            box-sizing: border-box;
            line-height: 1em;
            box-shadow: 10px 20px 40px 0 rgba(36, 35, 40, .1);
        }

        @media screen and (min-width:480px) {
            #_71A63dGiiHHb3dGinQfK_0 ._71A62B_4iHHb2B_4nQfK_0 {
                width: 352px;
                margin: 0 0 10px 10px;
                border-radius: 50px;
            }

            #_71A63dGiiHHb3dGinQfK_0 ._71A62B_4iHHb2B_4nQfK_0._71A63_88iHHb3_88nQfK_0 {
                border-radius: 5px;
            }

            #_71A63dGiiHHb3dGinQfK_0 ._71A62B_4iHHb2B_4nQfK_0._71A6D2zViHHbD2zVnQfK_0 {
                cursor: pointer;
                transition: all .1s ease-in-out;
            }

            #_71A63dGiiHHb3dGinQfK_0 ._71A62B_4iHHb2B_4nQfK_0._71A6D2zViHHbD2zVnQfK_0:hover {
                box-shadow: 0 0 0 4px rgba(144, 164, 174, .2);
            }
        }

        #_71A63dGiiHHb3dGinQfK_0 ._71A62B_4iHHb2B_4nQfK_0 ._71A6159fiHHb159fnQfK_0 {
            cursor: pointer;
            position: absolute;
            right: 18px;
            top: 4px;
            opacity: .1;
            width: 18px;
            height: 18px;
            margin: 0;
            padding: 0;
        }

        #_71A63dGiiHHb3dGinQfK_0 ._71A62B_4iHHb2B_4nQfK_0 ._71A6159fiHHb159fnQfK_0 svg {
            width: 11px;
            height: 11px;
            margin: 0;
            padding: 0;
            display: inline-block;
        }

        #_71A63dGiiHHb3dGinQfK_0 ._71A62B_4iHHb2B_4nQfK_0 ._71A6159fiHHb159fnQfK_0:hover {
            opacity: .4;
        }

        #_71A63dGiiHHb3dGinQfK_0 ._71A615RviHHb15RvnQfK_0 {
            display: block;
            margin-left: 68px;
            font-size: 13px;
            color: #686b81;
        }

        #_71A63dGiiHHb3dGinQfK_0 ._71A615RviHHb15RvnQfK_0 ._71A62fwXiHHb2fwXnQfK_0 {
            display: inline-block;
            padding: 2px 6px;
            font-size: 14px;
            font-weight: 600;
            line-height: 1em;
            border-radius: 3px;
            background-color: #f0f1f7;
            color: #242328;
        }

        #_71A63dGiiHHb3dGinQfK_0 ._71A615RviHHb15RvnQfK_0._71A61gGxiHHb1gGxnQfK_0 {
            margin: 11px 0 0 67px;
        }

        #_71A63dGiiHHb3dGinQfK_0 ._71A614UViHHb14UVnQfK_0 {
            margin-left: 72px;
            margin-top: 8px;
            cursor: pointer;
        }

        #_71A63dGiiHHb3dGinQfK_0 ._71A614UViHHb14UVnQfK_0:hover svg g {
            fill: #0f84d7;
        }

        #_71A63dGiiHHb3dGinQfK_0 ._71A6H0WDiHHbH0WDnQfK_0 {
            display: block;
            position: absolute;
            float: left;
            left: 33px;
            top: 33px;
            width: 26px;
            height: 26px;
        }

        #_71A63dGiiHHb3dGinQfK_0 ._71A6H0WDiHHbH0WDnQfK_0 ._71A6h0ePiHHbh0ePnQfK_0,
        #_71A63dGiiHHb3dGinQfK_0 ._71A6H0WDiHHbH0WDnQfK_0 ._71A61leDiHHb1leDnQfK_0,
        #_71A63dGiiHHb3dGinQfK_0 ._71A6H0WDiHHbH0WDnQfK_0 ._71A62PA-iHHb2PA-nQfK_0,
        #_71A63dGiiHHb3dGinQfK_0 ._71A6H0WDiHHbH0WDnQfK_0 ._71A63M25iHHb3M25nQfK_0 {
            border-radius: 10px;
            width: 22px;
            height: 22px;
            background: #0095f7;
            box-shadow: 0 0 0 rgba(0, 149, 247, .4);
        }

        @media screen and (min-width:480px) {

            #_71A63dGiiHHb3dGinQfK_0 ._71A6H0WDiHHbH0WDnQfK_0 ._71A6h0ePiHHbh0ePnQfK_0,
            #_71A63dGiiHHb3dGinQfK_0 ._71A6H0WDiHHbH0WDnQfK_0 ._71A61leDiHHb1leDnQfK_0,
            #_71A63dGiiHHb3dGinQfK_0 ._71A6H0WDiHHbH0WDnQfK_0 ._71A62PA-iHHb2PA-nQfK_0,
            #_71A63dGiiHHb3dGinQfK_0 ._71A6H0WDiHHbH0WDnQfK_0 ._71A63M25iHHb3M25nQfK_0 {
                border-radius: 50%;
            }
        }

        #_71A63dGiiHHb3dGinQfK_0 ._71A6H0WDiHHbH0WDnQfK_0 ._71A6h0ePiHHbh0ePnQfK_0 {
            animation: _71A6h0ePiHHbh0ePnQfK_0 3s linear infinite;
        }

        #_71A63dGiiHHb3dGinQfK_0 ._71A6H0WDiHHbH0WDnQfK_0 ._71A63M25iHHb3M25nQfK_0 {
            animation: _71A6h0ePiHHbh0ePnQfK_0 3s linear infinite 1.5s;
        }

        @keyframes _71A6h0ePiHHbh0ePnQfK_0 {
            0% {
                box-shadow: 0 0 0 0 rgba(0, 149, 247, .4);
            }

            to {
                box-shadow: 0 0 0 19px rgba(0, 149, 247, 0);
            }
        }

        @media screen and (min-width:480px) {
            #_71A62lriiHHb2lrinQfK_0 {
                right: auto;
            }
        }

        #_71A62lriiHHb2lrinQfK_0 ._71A61XogiHHb1XognQfK_0 {
            border: 1px solid rgba(216, 217, 226, .5);
            pointer-events: auto;
            overflow: hidden;
            position: relative;
            height: 88px;
            width: 100%;
            margin: 0 auto;
            background-color: #fff;
            padding: 16px;
            box-sizing: border-box;
            line-height: 1em;
            box-shadow: 10px 20px 40px 0 rgba(36, 35, 40, .1);
        }

        @media screen and (min-width:480px) {
            #_71A62lriiHHb2lrinQfK_0 ._71A61XogiHHb1XognQfK_0 {
                width: 352px;
                margin: 0 0 10px 10px;
                border-radius: 50px;
            }

            #_71A62lriiHHb2lrinQfK_0 ._71A61XogiHHb1XognQfK_0._71A6246aiHHb246anQfK_0 {
                border-radius: 5px;
            }

            #_71A62lriiHHb2lrinQfK_0 ._71A61XogiHHb1XognQfK_0._71A627wViHHb27wVnQfK_0 {
                cursor: pointer;
                transition: all .1s ease-in-out;
            }

            #_71A62lriiHHb2lrinQfK_0 ._71A61XogiHHb1XognQfK_0._71A627wViHHb27wVnQfK_0:hover {
                box-shadow: 0 0 0 4px rgba(144, 164, 174, .2);
            }
        }

        #_71A62lriiHHb2lrinQfK_0 ._71A61XogiHHb1XognQfK_0 ._71A629e1iHHb29e1nQfK_0 {
            cursor: pointer;
            position: absolute;
            right: 18px;
            top: 4px;
            opacity: .1;
            width: 18px;
            height: 18px;
            margin: 0;
            padding: 0;
        }

        #_71A62lriiHHb2lrinQfK_0 ._71A61XogiHHb1XognQfK_0 ._71A629e1iHHb29e1nQfK_0 svg {
            width: 11px;
            height: 11px;
            margin: 0;
            padding: 0;
            display: inline-block;
            margin: -4px 2px;
            vertical-align: middle;
            line-height: 1em;
            font-size: .75rem;
        }

        #_71A62lriiHHb2lrinQfK_0 ._71A61XogiHHb1XognQfK_0 ._71A629e1iHHb29e1nQfK_0 svg svg {
            overflow: hidden;
        }

        #_71A62lriiHHb2lrinQfK_0 ._71A61XogiHHb1XognQfK_0 ._71A629e1iHHb29e1nQfK_0 ._71A613_AiHHb13_AnQfK_0 {
            color: #0089d8;
            cursor: pointer;
            text-decoration: none !important;
            font-weight: 600;
        }

        #_71A62lriiHHb2lrinQfK_0 ._71A61XogiHHb1XognQfK_0 ._71A637xpiHHb37xpnQfK_0 {
            display: block;
            position: absolute;
            float: left;
            left: 16px;
            top: 16px;
            width: 56px;
            height: 56px;
            background-color: #ffebee;
            border-radius: 100%;
            padding-top: 1px;
            padding-left: 1px;
        }

        #_71A62lriiHHb2lrinQfK_0 ._71A61XogiHHb1XognQfK_0 ._71A637xpiHHb37xpnQfK_0._71A6246aiHHb246anQfK_0 {
            border-radius: 5px;
        }

        #_71A62lriiHHb2lrinQfK_0 ._71A61XogiHHb1XognQfK_0 ._71A6qu5HiHHbqu5HnQfK_0 {
            margin-left: 80px;
            padding-right: 8px;
            letter-spacing: -.02em;
            line-height: .9em;
        }

        #_71A62lriiHHb2lrinQfK_0 ._71A61XogiHHb1XognQfK_0 ._71A6qu5HiHHbqu5HnQfK_0._71A63CGUiHHb3CGUnQfK_0 {
            margin-top: 10px;
        }

        #_71A62lriiHHb2lrinQfK_0 ._71A61XogiHHb1XognQfK_0 ._71A6qu5HiHHbqu5HnQfK_0 ._71A61VEJiHHb1VEJnQfK_0 {
            margin-left: -4px;
            display: inline-block;
            margin-top: -1px;
            padding: 2px 6px;
            line-height: 1em;
            border-radius: 3px;
            background-color: #f0f1f7;
            color: #242328;
            font-size: 14px;
            font-weight: 600;
        }

        #_71A62lriiHHb2lrinQfK_0 ._71A61XogiHHb1XognQfK_0 ._71A6qu5HiHHbqu5HnQfK_0 ._71A62mT3iHHb2mT3nQfK_0 {
            display: inline-block;
            margin-top: 5px;
            color: #686b81;
        }

        #_71A62lriiHHb2lrinQfK_0 ._71A61XogiHHb1XognQfK_0 ._71A6qu5HiHHbqu5HnQfK_0 div {
            font-size: 13px;
            color: #242328;
        }

        #_71A62lriiHHb2lrinQfK_0 ._71A61XogiHHb1XognQfK_0 ._71A6qu5HiHHbqu5HnQfK_0 ._71A6toc3iHHbtoc3nQfK_0 {
            font-weight: 700;
            display: inline-block;
            padding: 3px;
            background-color: rgba(0, 149, 247, .1);
            margin-bottom: 2px;
            border-radius: 3px;
            color: #0095f7;
        }

        #_71A62lriiHHb2lrinQfK_0 ._71A61XogiHHb1XognQfK_0 ._71A62dIfiHHb2dIfnQfK_0 {
            font-size: 10px;
            color: #0095f7;
            font-weight: 700;
            margin-left: 80px;
            margin-top: 8px;
            cursor: pointer;
        }

        #_71A62lriiHHb2lrinQfK_0 ._71A61XogiHHb1XognQfK_0 ._71A62dIfiHHb2dIfnQfK_0 a {
            color: rgba(0, 149, 247, .5);
            text-decoration: none !important;
        }

        #_71A62lriiHHb2lrinQfK_0 ._71A61XogiHHb1XognQfK_0 ._71A62dIfiHHb2dIfnQfK_0 i {
            display: inline-block;
            margin: -4px 2px;
            vertical-align: middle;
            line-height: 1em;
            font-size: .75rem;
        }

        #_71A62lriiHHb2lrinQfK_0 ._71A61XogiHHb1XognQfK_0 ._71A62dIfiHHb2dIfnQfK_0 i svg {
            overflow: hidden;
        }

        #_71A62lriiHHb2lrinQfK_0 ._71A61XogiHHb1XognQfK_0 ._71A62dIfiHHb2dIfnQfK_0 ._71A613_AiHHb13_AnQfK_0 {
            color: #0089d8;
            cursor: pointer;
            text-decoration: none !important;
            font-weight: 600;
        }

        #_71A62lriiHHb2lrinQfK_0 ._71A61XogiHHb1XognQfK_0 ._71A62dIfiHHb2dIfnQfK_0:hover svg g {
            fill: #0f84d7;
        }

        #_71A61BlYiHHb1BlYnQfK_0 {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 1000000;
        }

        @media screen and (min-width:480px) {
            #_71A61BlYiHHb1BlYnQfK_0 {
                right: auto;
                bottom: 10px;
            }
        }

        #_71A61BlYiHHb1BlYnQfK_0 ._71A62KJliHHb2KJlnQfK_0 {
            pointer-events: auto;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1000000;
            background-color: #fff;
        }

        #_71A61BlYiHHb1BlYnQfK_0 ._71A62KJliHHb2KJlnQfK_0 iframe {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            height: 100%;
            width: 100%;
        }

        @media screen and (min-width:480px) {
            #_71A61BlYiHHb1BlYnQfK_0._71A626GWiHHb26GWnQfK_0 {
                left: auto;
                right: 10px;
            }
        }

        #_71A61BlYiHHb1BlYnQfK_0 ._71A63YFliHHb3YFlnQfK_0 {
            z-index: 10000000;
            pointer-events: auto;
            overflow: hidden;
            position: relative;
            background-color: #fefefe;
            color: #0095f7;
            box-sizing: border-box;
            line-height: 1em;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            -ms-flex-align: center;
            align-items: center;
            text-align: center;
            transition: all .2s linear;
            width: 96%;
            border-radius: 5px;
            margin: 0 auto 10px;
            box-shadow: 0 0 1px rgba(0, 0, 0, .2), 0 1px 2px rgba(0, 0, 0, .05), 0 8px 50px rgba(0, 0, 0, .05), 0 10px 100px rgba(0, 0, 0, .1);
            padding: 30px 10px;
        }

        @media screen and (min-width:480px) {
            #_71A61BlYiHHb1BlYnQfK_0 ._71A63YFliHHb3YFlnQfK_0 {
                width: 320px;
                margin: 0 0 0 10px;
                padding: 30px 10px 50px;
            }
        }

        #_71A61BlYiHHb1BlYnQfK_0 ._71A63YFliHHb3YFlnQfK_0 ._71A63dAgiHHb3dAgnQfK_0 {
            position: absolute;
            background-color: #0095f7;
            height: 4px;
            top: 0;
            left: 0;
        }

        #_71A61BlYiHHb1BlYnQfK_0 ._71A63YFliHHb3YFlnQfK_0 ._71A61EyZiHHb1EyZnQfK_0 {
            margin: 10px auto;
            width: 80px;
            height: 80px;
            display: inline-block;
            border-radius: 50%;
        }

        #_71A61BlYiHHb1BlYnQfK_0 ._71A63YFliHHb3YFlnQfK_0 ._71A61EyZiHHb1EyZnQfK_0 img {
            height: 80px;
            width: 80px;
            display: block;
            background: transparent;
        }

        #_71A61BlYiHHb1BlYnQfK_0 ._71A63YFliHHb3YFlnQfK_0>div {
            text-align: center;
        }

        #_71A61BlYiHHb1BlYnQfK_0 ._71A63YFliHHb3YFlnQfK_0 ._71A6EHmXiHHbEHmXnQfK_0 {
            padding: 10px 15px;
            font-size: 20px;
            color: #7c90a2;
            letter-spacing: -.03em;
            line-height: 1.3em;
        }

        #_71A61BlYiHHb1BlYnQfK_0 ._71A63YFliHHb3YFlnQfK_0 ._71A62PMeiHHb2PMenQfK_0 {
            position: absolute;
            top: 12px;
            right: 10px;
            opacity: .1;
            cursor: pointer;
        }

        #_71A61BlYiHHb1BlYnQfK_0 ._71A63YFliHHb3YFlnQfK_0 ._71A62PMeiHHb2PMenQfK_0:hover {
            opacity: .15;
        }

        #_71A61BlYiHHb1BlYnQfK_0 ._71A63YFliHHb3YFlnQfK_0 ._71A62cVkiHHb2cVknQfK_0 {
            display: block;
            width: 100%;
            margin-top: 15px;
            line-height: 1em;
            padding: 25px;
            border-radius: 5px;
            color: #fff;
            background-color: #0095f7;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 2px 0 3px 0 rgba(0, 0, 0, .05), 0 1px 2px 0 rgba(0, 0, 0, .08);
            text-decoration: none !important;
            transition: all .1s linear;
            font-size: 20px;
        }

        @media screen and (min-width:480px) {
            #_71A61BlYiHHb1BlYnQfK_0 ._71A63YFliHHb3YFlnQfK_0 ._71A62cVkiHHb2cVknQfK_0 {
                display: inline-block;
                padding: 15px 25px;
                width: auto;
                font-size: 17px;
            }
        }

        #_71A61BlYiHHb1BlYnQfK_0 ._71A63YFliHHb3YFlnQfK_0 ._71A62cVkiHHb2cVknQfK_0:hover {
            box-shadow: 0 5px 15px 0 rgba(0, 0, 0, .1), 0 1px 3px 0 rgba(0, 0, 0, .1);
            text-decoration: none !important;
        }

        #_71A61BlYiHHb1BlYnQfK_0 ._71A63YFliHHb3YFlnQfK_0 ._71A62cVkiHHb2cVknQfK_0:active {
            box-shadow: none;
        }

        #_71A61BlYiHHb1BlYnQfK_0 ._71A63YFliHHb3YFlnQfK_0 ._71A6aOxliHHbaOxlnQfK_0 {
            opacity: .7;
            color: #90a4ae;
            font-size: 12px;
            line-height: 1em;
            font-weight: 700;
            padding: 10px;
            text-align: center;
            position: absolute;
            bottom: 5px;
            left: 0;
            right: 0;
        }

        #_71A61BlYiHHb1BlYnQfK_0 ._71A63YFliHHb3YFlnQfK_0 ._71A6aOxliHHbaOxlnQfK_0 a {
            color: #0095f7;
        }

        #_71A61BlYiHHb1BlYnQfK_0 ._71A63YFliHHb3YFlnQfK_0 ._71A6aOxliHHbaOxlnQfK_0 a:hover {
            text-decoration: none;
        }

        ._71A61AeFiHHb1AeFnQfK_0 {
            z-index: 100000;
            background-color: rgba(43, 57, 80, .5);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            bottom: 0;
        }


        .fadeIn-leave-active {
            opacity: 0;
        }

        .fadeIn-leave-active,
        .fadeUp-enter-active {
            transition: opacity .3s;
        }

        .fadeUp-enter-active {
            animation: fadeUp 1s linear both;
            opacity: 1;
        }

        @keyframes fadeUp {
            0% {
                transform: matrix(1, 0, 0, 1, 0, 100);
            }

            2.5% {
                transform: matrix(1, 0, 0, 1, 0, 65.221);
            }

            4.9% {
                transform: matrix(1, 0, 0, 1, 0, 42.7);
            }

            9.81% {
                transform: matrix(1, 0, 0, 1, 0, 17.223);
            }

            14.71% {
                transform: matrix(1, 0, 0, 1, 0, 6.492);
            }

            19.62% {
                transform: matrix(1, 0, 0, 1, 0, 2.23);
            }

            29.43% {
                transform: matrix(1, 0, 0, 1, 0, .13);
            }

            39.14% {
                transform: matrix(1, 0, 0, 1, 0, -.038);
            }

            to {
                transform: matrix(1, 0, 0, 1, 0, 0);
            }
        }

        .fadeUp-leave-active {
            animation: fadeDown 1s linear both;
            transition: opacity .3s;
            opacity: 0;
        }

        @keyframes fadeDown {
            0% {
                transform: matrix(1, 0, 0, 1, 0, 0);
            }

            5.71% {
                transform: matrix(1, 0, 0, 1, 0, 37.514);
            }

            11.31% {
                transform: matrix(1, 0, 0, 1, 0, 63.542);
            }

            17.02% {
                transform: matrix(1, 0, 0, 1, 0, 80.922);
            }

            22.62% {
                transform: matrix(1, 0, 0, 1, 0, 91.335);
            }

            28.33% {
                transform: matrix(1, 0, 0, 1, 0, 97.286);
            }

            33.93% {
                transform: matrix(1, 0, 0, 1, 0, 100.215);
            }

            45.15% {
                transform: matrix(1, 0, 0, 1, 0, 101.709);
            }

            72.57% {
                transform: matrix(1, 0, 0, 1, 0, 100.387);
            }

            to {
                transform: matrix(1, 0, 0, 1, 0, 100);
            }
        }

        .pulse-container {
            padding: 20px 0 30px !important;
        }

        .pulse-box {
            float: left;
            width: 50px;
            height: 50px;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-pack: center;
            justify-content: center;
            -ms-flex-align: center;
            align-items: center;
            position: relative;
        }

        .pulse-box:before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-size: 18px;
            background-position: 50%;
            background-repeat: no-repeat;
            opacity: .6;
        }

        .pulse-box:before,
        .pulse-box:before.questionMark {
            background-image: URL("https://useproof.s3.amazonaws.com/turbo1/simpleWhiteQuestionMark.svg");
        }

        .pulse-box:before.smile {
            background-image: URL("https://useproof.s3.amazonaws.com/turbo1/smile.svg");
        }

        svg.pulse-svg {
            overflow: visible;
        }

        svg.pulse-svg .first-circle,
        svg.pulse-svg .second-circle,
        svg.pulse-svg .third-circle {
            fill: #f44336;
            transform: scale(.5);
            transform-origin: center center;
            animation: pulse-me 3s linear infinite;
        }

        svg.pulse-svg .second-circle {
            animation-delay: 1s;
        }

        svg.pulse-svg .third-circle {
            animation-delay: 2s;
        }

        .pulse-css {
            width: 50px;
            height: 50px;
            border-radius: 25px;
            background: tomato;
            position: relative;
        }

        .pulse-css:after,
        .pulse-css:before {
            content: "";
            width: 50px;
            height: 50px;
            border-radius: 25px;
            background-color: tomato;
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            margin: auto;
            transform: scale(.5);
            transform-origin: center center;
            animation: pulse-me 3s linear infinite;
        }

        .pulse-css:after {
            animation-delay: 2s;
        }

        @keyframes pulse-me {
            0% {
                transform: scale(.5);
                opacity: 0;
            }

            50% {
                opacity: .1;
            }

            70% {
                opacity: .09;
            }

            to {
                transform: scale(2.5);
                opacity: 0;
            }
        }
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            animation: fadeInPage 0.5s ease-in-out;
        }

        .animation-preloader {
            opacity: 0;
            transform: translateY(20px);
            animation: slideUp 0.8s ease-out forwards;
        }

        @keyframes slideUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        * Responsive sizing */
        @media (max-width: 480px) {
            .preloader-icon {
                width: 60px;
            }
        }
    </style>

{{--    chat end--}}
    <body>
        <!--dev AYK (SE)-->
        <!--[if lte IE 9]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade
            your browser</a> to improve your experience and security.</p>
        <![endif]-->
        <!-- Preloader start -->
        <!-- <div class="preloader">
            <img src="{{ asset('frontend/img/logo/preloader-icon.gif') }}" alt="preloader-icon">
        </div> -->
        <!-- Preloader end -->
        <!-- header area start  -->
        <header>
{{--            <div class="header__top header__pad d-none d-md-block">--}}
{{--                <div class="container">--}}
{{--                    <div class="row g-0 align-items-center">--}}
{{--                        <div class="col-xl-5 col-md-5">--}}
{{--                            <div class="header__text">--}}
{{--                                    <span class="uppercase">We’re more than just transport. <b><a--}}
{{--                                                href="{{ route('Frontend.contact.us') }}">Free--}}
{{--                                                Consultancy</a></b> </span>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-xl-7 col-md-7 d-flex justify-content-end">--}}
{{--                            <div class="header__text">--}}
{{--                                    <span class="uppercase">--}}
{{--                                    <marquee>--}}
{{--                                        Hello Tranport offers completely free account for both brokers, and carriers. To publish or accept a load,--}}
{{--                                    </marquee>--}}
{{--                                        </span>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="header__bottom-wrapper black-bg  pb-15">
                <div class="container">
                    <div class="header__bottom p-relative">
                        <div class="header__bottom-info">
                            <div class="row align-items-center">
                                <div class="col-xl-2 col-lg-2 col-md-2 col-3">
                                    <div class="logo logo-transform">
                                        <a href="{{ route('Frontend.index') }}"><img
                                                src="{{ asset('frontend/img/logo/hello_transport.png') }}"  width="120" height="100" alt="Logo"></a>
                                    </div>
                                </div>
                                <div class="col-xl-10 col-lg-10 col-md-10 col-9">
                                    <div class="text-end d-xl-none">
                                        <div class="header__toggle-btn sidebar-toggle-btn">
                                            <div class="header__bar">
                                                <span></span>
                                                <span></span>
                                                <span></span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="d-none d-xl-block">
                                        <div class="header__info">
                                            <div class="d-inline-flex column-gap">
                                                <h3> </h3>
                                                <h3> </h3>
                                                <h3> </h3>
                                                <h3> </h3>
                                                <h3> </h3>
                                                <h3> </h3>
{{--                                                <a href="{{ route('Frontend.Shipper') }}">--}}
{{--                                                <h4 class="hover"> <i class="fas fa-shipping-fast"> </i> Shipper</h4>--}}
{{--                                                </a>--}}
{{--                                                <a href="{{ route('Frontend.Broker') }}">--}}
{{--                                                <h4 class="hover"> <i class="fas fa-user"> </i> broker</h4>--}}
{{--                                                </a>--}}
{{--                                                <a href="{{ route('Frontend.Carrier') }}">--}}
{{--                                                <h4 class="hover"> <i class="fas fa-truck"> </i> Carrier</h4>--}}
{{--                                                </a>--}}
                                            </div>
                                            <div class="d-inline-flex">
                                            <div class="header__info-item">
                                                <div class="header__info-icon">
                                                <a href="https://www.facebook.com/profile.php?id=61550689836975" target="_blank" aria-label="Facebook Profile">
                                                    <i class="flaticon-facebook hover"></i>
                                                </a>
                                                </div>
                                            </div>
                                            <div class="header__info-item">
                                                <div class="header__info-icon">
                                                <a href="https://www.instagram.com/daydispatch/" target="_blank" aria-label="Facebook Profile">
                                                    <i class="fab fa-instagram hover"></i>
                                                </a>
                                                </div>
                                            </div>
                                            <div class="header__info-item">
                                                <div class="header__info-icon">
                                                <a href="https://twitter.com/daydispatch101" target="_blank" aria-label="twitter Profile">
                                                    <i class="fab fa-twitter hover"></i>
                                                </a>
                                                </div>
                                            </div>
                                            <div class="header__info-item">
                                            <div class="header__info-icon">
                                                <a href="https://www.linkedin.com/in/day-dispatch-53023428b/" target="_blank" aria-label="linkedin Profile">
                                                    <i class="fab fa-linkedin hover"></i>
                                                </a>
                                            </div>
                                            </div>
                                            <div class="header__info-item">
                                            <div class="header__info-icon">
                                            <a href="https://www.youtube.com/@DayDispatch1" target="_blank" aria-label="youtube channel">
                                                    <i class="fab fa-youtube hover"></i>
                                            </a>
                                            </div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="menu-area position d-none d-xl-block p-absolute">
                            <div class="row d-flex justify-content-end align-items-center">
                                <div class="col-xl-2 col-lg-2">
                                    <div class="logo d-none">
                                        <a href="{{ route('Frontend.index') }}"><img
                                                src="{{ asset('frontend/img/logo/smalllogo.png') }}" alt="Logo"></a>
                                    </div>
                                </div>
                                <div class="col-xl-10 col-lg-10">
                                    <div class="menu-wrapper menu-bg d-flex justify-content-between">
                                        <div class="main-menu main-menu-1">
                                            <nav id="mobile-menu">
                                                <ul>
                                                    <li><a href="{{ route('Frontend.index') }}">Home</a></li>
                                                    <li><a href="{{ route('Frontend.about.us') }}">About Us</a></li>
{{--                                                    <li><a href="{{ route('Frontend.loadboard') }}">LoadBoard</a></li>--}}
{{--                                                    <li><a href="{{ route('Frontend.is.me') }}">Is it For Me?</a></li>--}}
                                                    <li><a href="{{ route('Frontend.is.me') }}">Our Services</a></li>

                                                    <!--<li><a href="{{ route('Frontend.contact.us') }}">Contact Us</a></li>-->

{{--                                                    <li class="dropdown-toggle d-inline-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">--}}
{{--                                                        <a href="#">pricing</a>    </li>--}}
{{--                                                        <ul class="dropdown-menu menu-bg text-center" >--}}
{{--                                                                    <form action="{{ route('Frontend.Packages') }}" method="GET">--}}
{{--                                                                        @csrf--}}
{{--                                                                        <input type="hidden" name="type" value="Brokers"/>--}}
{{--                                                                        <li><button type="subbmit" class="text-center text-white text-val btn-font" target="_blank" data-value="Brokers">Brokers</button></li>--}}
{{--                                                                    </form>--}}
{{--                                                                    <form action="{{ route('Frontend.Packages') }}" method="GET">--}}
{{--                                                                        @csrf--}}
{{--                                                                        <input type="hidden" name="type" value="Carriers"/>--}}
{{--                                                                        <li><button type="subbmit" class="text-center text-white text-val btn-font" target="_blank" data-value="Carriers">Carriers</button></li>--}}
{{--                                                                    </form>--}}

{{--                                                                    <!--<form action="{{ route('Frontend.Packages') }}" method="GET">-->--}}
{{--                                                                    <!--    @csrf-->--}}
{{--                                                                    <!--       <input type="hidden" name="type" value="Dispatch"/>-->--}}
{{--                                                                    <!--       <li><button type="subbmit" class="text-center text-white text-val btn-font" target="_blank" data-value="Dispatch">Dispatch</button></li>-->--}}
{{--                                                                    <!--</form>-->--}}

{{--                                                                    <form action="{{ route('Frontend.Packages') }}" method="GET">--}}
{{--                                                                        @csrf--}}
{{--                                                                        <input type="hidden" name="type" value="Shipper"/>--}}
{{--                                                                        <li><button type="subbmit" class="text-center text-white text-val btn-font" target="_blank" data-value="Shipper">Shipper</button></li>--}}
{{--                                                                    </form>--}}
{{--                                                        </ul>--}}

                                                    <!--<li><a href="https://daydispatch.com/Blog">Blog</a>-->
                                                    <li class="dropdown-toggle d-inline-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <a href="#">get a quote</a>
                                                    </li>
                                                    <ul class="dropdown-menu menu-bg text-center" >
                                                        <form action="{{ route('Frontend.qoute.request') }}" method="GET">
                                                            <input type="hidden" name="type" value="Car"/>
                                                            <li><button type="subbmit" class="text-center text-white text-val btn-font" target="_blank" data-value="Brokers">Car Quote</button></li>
                                                        </form>
                                                        <form action="{{ route('Frontend.qoute.request') }}" method="GET">
                                                            <input type="hidden" name="type" value="Heavy Equipment"/>
                                                            <li><button type="subbmit" class="text-center text-white text-val btn-font" target="_blank" data-value="Brokers">Heavy  Quote</button></li>
                                                        </form>
                                                        <form action="{{ route('Frontend.qoute.request') }}" method="GET">
                                                            <input type="hidden" name="type" value="Dryvan"/>
                                                            <li><button type="subbmit" class="text-center text-white text-val btn-font" target="_blank" data-value="Brokers">Freight Quote</button></li>
                                                        </form>
                                                    </ul>

                                                </ul>
                                            </nav>
                                        </div>
                                        @if (!Auth::check())
                                            <div class="menu-btn">
                                                <a href="{{ url('/loginn') }}">LogIn</a>
                                            </div>
                                        @else
                                            <div class="menu-btn">
                                                <a href="{{ url('/dashboard') }}">Dashboard</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sticky Menu Area Start Here  -->
            <div id="header-sticky" class="sticky-area menu-sticky menu-hidden">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-xl-2 col-lg-2 col-3">
                            <div class="logo">
                                <a href="{{ route('Frontend.index') }}"><img
                                        src="{{ asset('frontend/img/logo/smalllogo.png') }}" alt="Logo"></a>
                            </div>
                        </div>
                        <div class="col-xl-10 col-lg-10 col-9">
                            <div class="menu-wrapper menu-none d-flex align-items-center justify-content-between">
                                <div class="main-menu main-menu-1">
                                    <nav>
                                        <ul>
                                            <li><a href="{{ route('Frontend.index') }}">Home</a></li>
                                            <li><a href="{{ route('Frontend.about.us') }}" >About Us</a></li>
{{--                                            <li><a href="{{ route('Frontend.loadboard') }}">LoadBoard</a></li>--}}

                                            <li><a href="{{ route('Frontend.is.me') }}">Our Services</a></li>
{{--                                            <li><a href="{{ route('Frontend.is.me') }}">Is it For Me?</a></li>--}}
                                            <!--<li><a href="{{ route('Frontend.contact.us') }}">Contact Us</a></li> -->

{{--                                            <li class="dropdown-toggle d-inline-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false"><a href="#">pricing</a>--}}
{{--                                                    <ul class="dropdown-menu menu-bg text-center" >--}}
{{--                                                                <form action="{{ route('Frontend.Packages') }}" method="GET">--}}
{{--                                                                    @csrf--}}
{{--                                                                    <input type="hidden" name="type" value="Brokers"/>--}}
{{--                                                                    <li><button type="subbmit" class="text-center text-white text-val btn-font" target="_blank" data-value="Brokers">Brokers</button></li>--}}
{{--                                                                </form>--}}
{{--                                                                <form action="{{ route('Frontend.Packages') }}" method="GET">--}}
{{--                                                                    @csrf--}}
{{--                                                                    <input type="hidden" name="type" value="Carriers"/>--}}
{{--                                                                    <li><button type="subbmit" class="text-center text-white text-val btn-font" target="_blank" data-value="Carriers">Carriers</button></li>--}}
{{--                                                                </form>--}}
{{--                                                                --}}{{----}}
{{--                                                                <!--<form action="{{ route('Frontend.Packages') }}" method="GET">-->--}}
{{--                                                                <!--    @csrf-->--}}
{{--                                                                <!--       <input type="hidden" name="type" value="Dispatch"/>-->--}}
{{--                                                                <!--       <li><button type="subbmit" class="text-center text-white text-val btn-font" target="_blank" data-value="Dispatch">Dispatch</button></li>-->--}}
{{--                                                                <!--</form>-->--}}
{{--                                                                --}}
{{--                                                                <form action="{{ route('Frontend.Packages') }}" method="GET">--}}
{{--                                                                    @csrf--}}
{{--                                                                    <input type="hidden" name="type" value="Shipper"/>--}}
{{--                                                                    <li><button type="subbmit" class="text-center text-white text-val btn-font" target="_blank" data-value="Shipper">Shipper</button></li>--}}
{{--                                                                </form>--}}
{{--                                                    </ul>--}}
{{--                                            </li>--}}
{{--                                            <li><a href="{{ route('Frontend.qoute.request') }}">get a quote</a>--}}
                                            <li class="dropdown-toggle d-inline-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <a href="#">get a quote</a>
                                            </li>
                                            <ul class="dropdown-menu menu-bg text-center" >
                                                <form action="{{ route('Frontend.qoute.request') }}" method="GET">
                                                    <input type="hidden" name="type" value="Car"/>
                                                    <li><button type="subbmit" class="text-center text-white text-val btn-font" target="_blank" data-value="Brokers">Car Quote</button></li>
                                                </form>
                                                <form action="{{ route('Frontend.qoute.request') }}" method="GET">
                                                    <input type="hidden" name="type" value="Heavy Equipment"/>
                                                    <li><button type="subbmit" class="text-center text-white text-val btn-font" target="_blank" data-value="Brokers">Heavy  Quote</button></li>
                                                </form>
                                                <form action="{{ route('Frontend.qoute.request') }}" method="GET">
                                                    <input type="hidden" name="type" value="Dryvan"/>
                                                    <li><button type="subbmit" class="text-center text-white text-val btn-font" target="_blank" data-value="Brokers">Freight Quote</button></li>
                                                </form>
                                            </ul>
                                        </ul>
                                    </nav>
                                </div>
                                @if (!Auth::check())
                                    <div class="menu-btn">
                                        <a class="clip-btn" href="{{ url('/loginn') }}">LogIn</a>
                                    </div>
                                @else
                                    <div class="menu-btn">
                                        <a class="clip-btn" href="{{ url('/dashboard') }}">Dashboard</a>
                                    </div>
                                @endif
                            </div>
                            <div class="header__toggle-btn sidebar-toggle-btn text-end d-block d-lg-none">
                                <div class="header__bar">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sticky Menu Area End Here  -->
            <!-- Sidebar Area Start Here  -->
            <div class="sidebar__area">
                <div class="sidebar__wrapper">
                    <div class="sidebar__close">
                        <button class="sidebar__close-btn" id="sidebar__close-btn" aria-label="Close Sidebar">
                            <i class="fal fa-times"></i>
                        </button>
                    </div>
                    <div class="sidebar__content">
                        <div class="sidebar__logo mb-40">
                            <a href="{{ route('Frontend.index') }}">
                                <img src="{{ asset('frontend/img/logo/smalllogo.png') }}" alt="logo.png">
                            </a>
                        </div>
                        <div class="sidebar__search mb-25">
                            <form action="#">
                                <div class="single-input-field">
                                    <input name="name" type="text" placeholder="Search Here">
                                    <i class="fas fa-search"></i>
                                </div>
                            </form>
                        </div>
                        <div class="mobile-menu fix mb-10 mean-container">
                        </div>
                        <div class="sidebar__contact mt-30 mb-30">
                            <div class="sidebar__info fix">
                                <div class="sidebar__info-item">
                                    <div class="sidebar__info-icon">
                                        <i class="flaticon-telephone-call"></i>
                                    </div>
                                    <div class="sidebar__info-text">
                                        <span>Call us now</span>
                                        <h5><a href="tel:+14107184031">1 (844) 474-4721</a></h5>
                                    </div>
                                </div>
                                <div class="sidebar__info-item">
                                    <div class="sidebar__info-icon">
                                        <i class="flaticon-envelope "></i>
                                    </div>
                                    <div class="sidebar__info-text">
                                        <span>Email now</span>
                                        <h5><a href="/cdn-cgi/l/email-protection#8ee7e0e8e1cef9ebeceae1f9a0ede1e3" class="hover"><span
                                                    class="__cf_email__"
                                                    data-cfemail="0a63646c654a7d6f686e657d24696567">hodontime@shipa1.com</span></a>
                                        </h5>
                                    </div>
                                </div>
                                <div class="sidebar__info-item">
                                    <div class="sidebar__info-icon">
                                        <i class="flaticon-pin "></i>
                                    </div>
                                    <div class="sidebar__info-text">
                                        <span>MD 21030-1344</span>
                                        <h5>201 International Cir STE 230, Hunt Valley, MD 21030-1344</h5>
                                    </div>
                                </div>
                                    @if (!Auth::check())
                                            <div class="menu-btn">
                                                <a href="{{ url('/loginn') }}">LogIn</a>
                                            </div>
                                        @else
                                            <div class="menu-btn">
                                                <a href="{{ url('/dashboard') }}">Dashboard</a>
                                            </div>
                                        @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Sidebar Area Start Here  -->
            <div class="body-overlay"></div>
        </header>
        <main>
        
            @yield('content')

            <div class="chat">
                <div id="chat-widget-container"
                     style="opacity: 1; visibility: visible; z-index: 1000; position: fixed; bottom: 0px; width: 450px; height: 625px; max-width: 100%; max-height: calc(100% + 0px); min-height: 0px; min-width: 0px; background-color: transparent; border: 0px; overflow: hidden; transition: none !important;right:0">
                    <iframe
                        allow="clipboard-read; clipboard-write; autoplay; microphone *; camera *; display-capture *; picture-in-picture *; fullscreen *;"
                        id="chat-widget" name="chat-widget" title="LiveChat chat widget" scrolling="no"
                        style="width: 100%; height: 100%; min-height: 0px; min-width: 0px; margin: 0px; padding: 0px; background-image: none; background-position: 0% 0%; background-size: initial; background-attachment: scroll; background-origin: initial; background-clip: initial; background-color: transparent; border-width: 0px; float: none; color-scheme: normal; position: absolute; inset: 0px; transition: none !important; display: none; visibility: visible;">
                    </iframe>
                    <div dir="ltr" role="main" data-lc-id="0" class="css-1h1ne2e e1558m8u1">
                        <div class="css-1aasxu6 e131382t0">
                            <div class="css-1g9ek8d e1kv8om20"></div>
                            <div class="css-bubhx7 e1kv8om20">
                                <div data-lc-id="1" class="css-138p0k2 e16i86ec1" id="chat_with_us">
                                    <p id="live_support_window" class="css-e4pgre e16i86ec0">Chat Us Now</p>
                                    <button type="button" aria-label="Open LiveChat chat widget"
                                            class="e1mwfyk10 css-1iovl8i ejbfa1m0">
                                        <div class="css-1potzn5 e1dmt1bi3">
                                            <img src="/frontend/images/icon/chat.png" alt="Chat Icon" class="chat-icon-img" />

                                            <div class="css-anyrkw e1dmt1bi2"></div>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade"  class="modal-dialog modal-lg" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalToggleLabel">Calculate Freight Class</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- freight calculator removed --}}
                    </div>
                    <div class="modal-footer">
                        <!--<button class="btn btn-primary" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal" data-bs-dismiss="modal">Open second modal</button>-->
                    </div>
                    </div>
                </div>
            </div>
        </main>
        <footer>
            <section class="footer-area footer-area1 footer-area1-bg pt-100 pb-90">
                <div class="container">
                    <div class="row">
                        <!--<div class="col-lg-4 col-xl-3 col-md-6 col-sm-6">-->
                        <!--    <div class="footer-widget footer1-widget1 mb-50 pr-20">-->
                        <!--        <div class="footer-widget-title">-->
                        <!--            <h4>about us</h4>-->
                        <!--        </div>-->
                        <!--        <p class="mb-40">Customers struggled to find reliable carriers for their deliveries, leading to-->
                        <!--            wasted time, high charges, unsatisfactory service, shipment delays, safety concerns, and-->
                        <!--            regulatory issues.-->
                        <!--        </p>-->
                        <!--    </div>-->
                        <!--</div>-->
                        <div class="col-lg-4 col-xl-3 col-md-6 col-sm-6">
                            <div class="footer-widget footer1-widget1 mb-50 pr-20">
                                <div class="footer-widget-title">
                                    <h4>Contact us</h4>
                                </div>
                                    <div class="d-none d-xl-block">
                                        <div class="header__info flex-column text-white">
                                            <div class="header__info-item">
                                                <div class="header__info-icon">
                                                    <i class="flaticon-telephone-call text-danger"></i>
                                                </div>
                                                <div class="header__info-text">
                                                    <span>Call us now</span>
                                                    <h5><a href="tel:+14107184031" class="hover">1(410) 718-4031</a></h5>
                                                </div>
                                            </div>
                                            <div class="header__info-item">
                                                <div class="header__info-icon">
                                                    <i class="flaticon-envelope text-danger"></i>
                                                </div>
                                                <div class="header__info-text">
                                                    <span>Email now</span>
                                                    <h5><a
                                                            href="mailto:hodontime@shipa1.com"><span
                                                                class="__cf_email__ hover"
                                                                data-cfemail="c6afa8a0a986b1a3a4a2a9b1e8a5a9ab" >hodontime@shipa1.com</span></a>
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="header__info-item">
                                                <div class="header__info-icon">
                                                    <i class="flaticon-pin text-danger"></i>
                                                </div>
                                                <div class="header__info-text ">
                                                    <span class="hover">1007 FREDERICK ROAD</span>
                                                    <h5><a
                                                            href="https://maps.google.com/maps?q=1007+FREDERICK+ROAD+CATONSVILLE+MD+21228+UNITED+STATES">CATONSVILLE, MD, 21228 UNITED STATES</a></h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-xl-3 col-md-6 col-sm-6">
                            <div class="footer-widget footer2-widget footer2-widget3 mb-50 pl-55">
                                <div class="footer-widget-title">
                                    <h4>useful links</h4>
                                </div>
                                <ul class="footer-widget-link-2">
                                    <li><i class="fas fa-truck"></i><a href="{{ route('Frontend.about.us') }}">About
                                            Us</a></li>
{{--                                    <li><i class="fas fa-clipboard"></i><a--}}
{{--                                            href="{{ route('Frontend.loadboard') }}">LoadBoard</a></li>--}}
{{--                                    <li><i class="fas fa-box"></i><a href="{{ route('Frontend.is.me') }}">Is It For--}}
{{--                                            Me?</a></li>--}}
                                    <li><i class="fas fa-box"></i><a href="{{ route('Frontend.is.me') }}">Our Services</a></li>
                                    <li><i class="fas fa-box"></i><a href="{{ route('Frontend.Testimonials') }}">Testimonials</a></li>
                                    <li><i class="fa fa-bracket"></i><a href="{{ url('/loginn') }}">Login</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-4 col-xl-3 col-md-6 col-sm-6">
                            <div class="footer-widget footer2-widget footer2-widget3 mb-50 pl-55">
                                <div class="footer-widget-title">
                                    <h4>Useful Services</h4>
                                </div>
                                <ul class="footer-widget-link-2">
{{--                                    <li><i class="fas fa-truck"></i><a href="{{ route('Frontend.Carrier') }}">Carriers</a></li>--}}
{{--                                    <li><i class="fas fa-user"></i><a href="{{ route('Frontend.Broker') }}"> Brokers</a></li>--}}
{{--                                    <li><i class="fas fa-shipping-fast"></i><a href="{{ route('Frontend.Shipper') }}">Shipper</a></li>--}}
                                    {{-- <li><i class="fa fa-truck"></i><a href="{{ route('Frontend.Dispatch') }}">Dispatch</a></li> --}}
                                    <li><i class="fa fa-bracket"></i><a href="{{ url('/register') }}">SignUp</a>
                                </li>
                                </ul>
                            </div>
                        </div>
{{--                        <div class="col-lg-4 col-xl-3 col-md-6 col-sm-6">--}}
{{--                            <div class="footer-widget footer1-widget3 mb-50 pr-45">--}}
{{--                                <div class="footer-widget-title">--}}
{{--                                    <h4>Subscribe us</h4>--}}
{{--                                </div>--}}
{{--                                <p class="mb-20">Subscribe us &amp; receive our office &amp; update in your inbox directly--}}
{{--                                </p>--}}
{{--                                <form action="#" class="subscribe-form subscribe-form-footer1">--}}
{{--                                    <div class="s-clip p-relative s-input mb-10">--}}
{{--                                        <input type="text" placeholder="Enter your email">--}}
{{--                                        <i class="fas fa-envelope"></i>--}}
{{--                                    </div>--}}
{{--                                    <button type="submit">Subscribe Now</button>--}}
{{--                                </form>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </section>
            <div class="footer__bottom-area copy-bg-1 p-relative">
                <div class="footer-menu-area position p-absolute">
                    <div class="container">
                        <div class="red-bg clip-box-xs">
                            <div class="footer-menu-box">
                                <div class="row align-items-center">
                                    <div class="col-xxl-7 col-lg-5">
                                        <div class="footer-menu mb-15">
                                            <nav>
                                                <ul>
                                                    <li><a href="{{ route('Frontend.terms') }}">terms & conditions</a>
                                                    </li>
                                                    <li><a href="{{ route('Frontend.faq') }}">FAQ</a></li>
                                                    <li><a href="{{ route('Frontend.privacy') }}">Privacy Policy</a></li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                    <!--<div class="col-xxl-5 col-lg-7">-->
                                    <!--    <div class="footer-brand m-img mb-15 text-center text-lg-end">-->
                                    <!--        <img src="{{ asset('frontend/img/footer-icon-img.png') }}"-->
                                    <!--            alt="footer icon">-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="copy-right-area">
                    <div class="container">
                        <div class="copy-right-text text-center">
                            @php
                                $lastYear = date('Y', strtotime('-1 year'));
                                $currentYear = date('Y');
                            @endphp
                            {{-- <p>Copyright & design by <a href="{{ route('Frontend.index') }}">@DayDispatch</a></p> --}}
                            <p>©<a href="{{ route('Frontend.index') }}"  class="text-decoration-underline" > Hello Tranport</a> {{ $lastYear }}-{{ $currentYear }}. All Rights Reserved.</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <div class="progress-wrap">
            <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
                <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"/>
            </svg>
        </div>
        <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
        <script src="{{ asset('frontend/js/vendor/jquery-3.6.0.min.js') }}"></script>
        <script src="{{ asset('frontend/js/vendor/waypoints.min.js') }}"></script>
        <script src="{{ asset('frontend/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('frontend/js/meanmenu.js') }}"></script>
        <script src="{{ asset('frontend/js/swiper-bundle.min.js') }}"></script>
        <script src="{{ asset('frontend/js/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('frontend/js/magnific-popup.min.js') }}"></script>
        <script src="{{ asset('frontend/js/parallax.min.js') }}"></script>
        <script src="{{ asset('frontend/js/backToTop.js') }}"></script>
        <script src="{{ asset('frontend/js/jquery-ui-slider-range.js') }}"></script>
        <script src="{{ asset('frontend/js/nice-select.min.js') }}"></script>
        <script src="{{ asset('frontend/js/counterup.min.js') }}"></script>
        <script src="{{ asset('frontend/js/ajax-form.js') }}"></script>
        <script src="{{ asset('frontend/js/wow.min.js') }}"></script>
        <script src="{{ asset('frontend/js/isotope.pkgd.min.js') }}"></script>
        <script src="{{ asset('frontend/js/imagesloaded.pkgd.min.js') }}"></script>
        <script src="{{ asset('frontend/js/rangeslider-js.min.js') }}"></script>
        <script src="{{ asset('frontend/js/main.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
        <script>
            // $(document).ready(function() {
            //   $(".text-val").on("click", function() {
            //     let selectedValue = $(this).data("value");
            //     console.log(selectedValue);
            //     // You can use the selectedValue as needed
            //   });
            // });
        </script>


        <script>
            $('#chat_with_us').on('click', function() {
                if ($('#chat-widget').is(':visible')) {
                    $('#chat-widget').removeClass('flip-bounce');
                    $('#chat-widget').slideUp(300, function() {
                        $('#chat-widget-container').addClass('inactivee');
                        $('#chat_with_us').addClass('activee');
                    });
                } else {
                    $('#chat-widget-container').removeClass('inactivee');

                    $('#chat-widget').css('display', 'block').addClass(
                        'flip-bounce'); // Show and animate the chat widget
                }
            });

            $(window).on('load', function() {
                // Set the src of the iframe after the page is fully loaded
                $('#chat-widget').attr('src', "{{ url('/chat-widget') }}?user_id=0");
                $('#chat-widget-container').addClass('inactivee');
                $('#chat_with_us').addClass('activee');

                // $('#chat-widget').fadeIn(300);
            });

            $(window).on('message', function(event) {
                var e = event.originalEvent;
                if (e.data.type == "iframeMessage2") {
                    $('#live_support_window').text(`Live Support(${e.data.payload})`);
                    if (e.data.payload > 0) {
                        $('#live_support_window').addClass('blink');

                        // Optional: Stop the animation after a few seconds
                        setTimeout(function() {
                            $('#live_support_window').removeClass('blink');
                        }, 3000); // Stop after 3 seconds
                    }
                }
            });
        </script>

        <script>
            $('.basic_quote_info').hide();
            $('.vehicle_quote_info').hide();
            $('.route_quote_info').hide();

            $('.vehicle_make').hide();
            $('.vehicle_model').hide();
            $('.vehicle_heading').hide();
            $('.vehicle_dimension').hide();
            $('.vehicle_additional').hide();
            $('.vehicle_carrier').hide();
            $('.vehicle_condition').hide();
            $('.vehicle_quote_info_Freight').hide();
            $('.vehicle_quote_info').hide();
            var showdryvanpopup = 0;



            $(document).ready(function () {
                $('.route_quote_info').show();
                $('#step_2').click(function () {
                    if ($("#F_ZipCode").val() === '') {
                        $("#F_ZipCode").attr("style", "border-color:red;");
                    } else {
                        $("#F_ZipCode").attr("style", "border-color:transparent;");
                    }
                    if ($("#T_ZipCode").val() === '') {
                    $("#T_ZipCode").attr("style", "border-color:red;");
                    } else {
                        $("#T_ZipCode").attr("style", "border-color:transparent;");
                    }
                    if ($("#F_ZipCode").val() !== '' && $("#T_ZipCode").val() !== '') {
                        $('.route_quote_info').hide();
                        $('.vehicle_quote_info').show();
                        $('.vehicle_quote_info_Freight').hide();
                    }
                });
                $('.step_1').click(function () {
                    $('.route_quote_info').show();
                    $('.vehicle_quote_info').hide();
                    $('.vehicle_quote_info_Freight').hide();
                });
                $('.step_1_goback').click(function(){
                    $('.route_quote_info').hide();
                    $('.vehicle_quote_info').show();
                    $('.vehicle_quote_info_Freight').hide();
                })
                $("#showdryvan").click(function(){
                    console.log('show Dry Van');
                    $('.vehicle_quote_info_Freight').show();
                    $('.route_quote_info').hide();
                    $('.vehicle_quote_info').hide();


                    showdryvanpopup = 1;
                })
                $('.vehicle_type select').change(function () {
                    const type = $(this).val();
                    if(type == 'DRYVAN'){
                        $('#vehicleinfo').text('Freight shipping')
                    }
                    else if(type){
                        $('#vehicleinfo').text(type)
                    }
                    else{
                        $('#vehicleinfo').text("VEHICLE INFORMATION")
                    }
                    if (type === 'Car') {
                        $('.vehicle_make').show();
                        $('.vehicle_model').show();
                        $('.vehicle_carrier').show();
                        $('.vehicle_condition').show();
                        $('.vehicle_heading').hide();
                        $('.vehicle_dimension').hide();
                        $('.vehicle_additional').hide();

                        // $('#selectvehicle').children('a').attr('id','step_3');
                        $('#nextstepof').children('#first').show();
                        $('#nextstepof').children('#second').hide();

                    } else if (type === 'MotorCycle') {
                        $('.vehicle_heading').show();
                        $('.vehicle_additional').show();
                        $('.vehicle_carrier').show();
                        $('.vehicle_condition').show();
                        $('.vehicle_make').hide();
                        $('.vehicle_model').hide();
                        $('.vehicle_dimension').hide();

                        // $('#selectvehicle').children('a').attr('id','step_3');

                        $('#nextstepof').children('#first').show();
                        $('#nextstepof').children('#second').hide();

                    } else if (type === 'Heavy Equipment') {
                        $('.vehicle_heading').show();
                        $('.vehicle_dimension').show();
                        $('.vehicle_additional').show();
                        $('.vehicle_make').hide();
                        $('.vehicle_model').hide();
                        $('.vehicle_carrier').hide();
                        $('.vehicle_condition').hide();

                        // $('#selectvehicle').children('a').attr('id','step_3');

                        $('#nextstepof').children('#first').show();
                        $('#nextstepof').children('#second').hide();

                    } else if (type === 'Dryvan') {
                        console.log('show')
                        $('.vehicle_dimension').show();


                        $('.vehicle_make').hide();
                        $('.vehicle_model').hide();
                        $('.vehicle_heading').hide();
                        $('.vehicle_additional').hide();
                        $('.vehicle_carrier').hide();
                        $('.vehicle_condition').hide();

                        // $('#selectvehicle').children('a').attr('id','showdryvan');


                        $('#nextstepof').children('#first').hide();
                        $('#nextstepof').children('#second').show();
                    }else {
                        $('.vehicle_make').hide();
                        $('.vehicle_model').hide();
                        $('.vehicle_heading').hide();
                        $('.vehicle_dimension').hide();
                        $('.vehicle_additional').hide();
                        $('.vehicle_carrier').hide();
                        $('.vehicle_condition').hide();

                        // $('#selectvehicle').children('a').attr('id','step_3');

                        $('#nextstepof').children('#first').show();
                        $('#nextstepof').children('#second').hide();
                    }
                });
                $('.vehicle_type select').trigger('change');

                $('.step_3').click(function () {
                    $('.route_quote_info').hide();
                    $('.vehicle_quote_info').hide();
                    $('.basic_quote_info').show();
                    $('.vehicle_quote_info_Freight').hide();

                    showdryvanpopup = 0;
                });
                $('.step_3_show').click(function () {
                    $('.route_quote_info').hide();
                    $('.vehicle_quote_info').hide();
                    $('.basic_quote_info').show();
                    $('.vehicle_quote_info_Freight').hide();

                    showdryvanpopup = 1;
                });
                $('#prev_step_2').click(function () {
                    console.log(showdryvanpopup)
                    if(showdryvanpopup != 0){
                        $('.route_quote_info').hide();
                        $('.vehicle_quote_info').hide();
                        $('.basic_quote_info').hide();
                        $('.vehicle_quote_info_Freight').show();
                    }
                    else{
                        $('.route_quote_info').hide();
                        $('.vehicle_quote_info').show();
                        $('.basic_quote_info').hide();
                        $('.vehicle_quote_info_Freight').hide();
                    }

                });
            });
        </script>
        <script>
            const path = "{{ url('/autocomplete') }}";
            $('.Dest_ZipCode.typeahead, .Origin_ZipCode.typeahead').typeahead({
                source: function (query, process) {
                    return $.get(path, {query: query}, function (data) {
                        return process(data);
                    });
                }
            });

            const GetVehicleMake = '{{ url('/getmake') }}';
            const GetVehicleModel = '{{ url('/getmodel') }}';
            $('input.make.typeahead').typeahead({
                source: function (query, process) {
                    return $.get(GetVehicleMake, {query: query}, function (data) {
                        return process(data);
                    });
                }
            });

            $('input.model.typeahead').typeahead({
                source: function (query, process) {
                    return $.get(GetVehicleModel, {query: query}, function (data) {
                        return process(data);
                    });
                }
            });

            function calculateFreight(){
            let x = document.getElementById('CarrierRequestLoad');
            x.style.display="block";
            }

            $('#firstrow').children('.col-lg-2').addClass('col-lg-6');
            $('.breadcrumb-area h1').remove();
            $('.breadcrumb .item:nth-child(2)').remove();
        </script>
    </body>
</html>
