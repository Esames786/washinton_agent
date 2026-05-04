@extends('layouts.innerpages')

@section('template_title')
    {{  ucfirst(trim("$_SERVER[REQUEST_URI]",'/'))}}
@endsection
@section('content')

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
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

        .table-bordered, .text-wrap table, .table-bordered th, .text-wrap table th, .table-bordered td, .text-wrap table td {
            border: 1px solid rgb(0 0 0);
        }

        .table > thead > tr > td, .table > thead > tr > th {
            font-weight: 500;
            -webkit-transition: all .3s ease;
            font-size: 18px;
            color: rgb(0 0 0);
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
            animation: fadeEffect 1s; /* Fading effect takes 1 second */
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">

    <!--/app header-->                                                <!--Page header-->
    <div class="page-header">
        <!--<div class="page-leftheader">-->
        <!--    <input type="hidden" value="{{trim("$_SERVER[REQUEST_URI]",'/')}}" id="titlee">-->
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Home</a>-->
        <!--        </li>-->
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">Reports</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>RingCentral</b></h1>
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
    <div class="row">
        <div class="col-12">
            @if(session('flash_message'))
                <div class="alert alert-success">
                    {{session('flash_message')}}
                </div>
            @endif
            <!--div-->
            <div class="card">
                <div class="card-body">
                    <!-- Authentication Section -->
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold">Step 1: Authenticate</h2>
                        @if(session('ringcentral_access_token'))
                            <a href="{{ url('/ringcentral/auth') }}"
                               class="bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
                                Authenticate with RingCentral
                            </a>
                        @else
                            <p class="text-green-500">You are authenticated!</p>
                        @endif
                    </div>

                    <!-- Call Controls Section -->
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold">Step 2: Make a Call</h2>
                        @if(session('ringcentral_access_token'))
                            <div>
                                <input type="text" id="phone-number" placeholder="Enter phone number"
                                       class="border border-gray-300 px-4 py-2 rounded mb-4">
                                <button id="call-button"
                                        class="bg-green-500 text-white px-4 py-2 rounded shadow hover:bg-green-700">
                                    Call
                                </button>
                                <button id="hangup-button"
                                        class="bg-red-500 text-white px-4 py-2 rounded shadow hover:bg-red-700">
                                    Hang Up
                                </button>
                            </div>
                        @else
                            <p class="text-red-500">Please authenticate with RingCentral to make calls.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end app-content-->

    <div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historyModalLabel">History for Order #</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="historyModalContent">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extraScript')
    {{--<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>--}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


    <script src="https://unpkg.com/ringcentral-web-phone@2.0.9/dist/cjs/index.js"></script>
    <script>
        let webPhone;
        let session;

        // Initialize WebPhone after authentication
        const token = @json(session('ringcentral_access_token'));
        const sipInfo = @json(session('ringcentral_sip_info'));

        if (token && sipInfo) {
            try {
                webPhone = new RingCentral.WebPhone({ sipInfo: sipInfo });
                webPhone.userAgent.start();
                console.log('WebPhone initialized successfully');
            } catch (error) {
                console.error('Failed to initialize WebPhone:', error);
            }
        } else {
            console.warn('No SIP info found. Please authenticate with RingCentral.');
        }

        // Make a call
        document.getElementById('call-button').addEventListener('click', function() {
            const phoneNumber = document.getElementById('phone-number').value;
            if (webPhone && phoneNumber) {
                try {
                    session = webPhone.userAgent.invite(phoneNumber);
                    session.on('accepted', () => console.log('Call accepted'));
                    session.on('terminated', () => console.log('Call ended'));
                } catch (error) {
                    console.error('Failed to make a call:', error);
                    alert('Failed to make a call. Please check the console for details.');
                }
            } else {
                alert('Please authenticate and enter a phone number.');
            }
        });

        // Hang up a call
        document.getElementById('hangup-button').addEventListener('click', function() {
            if (session) {
                try {
                    session.terminate();
                    console.log('Call terminated');
                } catch (error) {
                    console.error('Failed to hang up:', error);
                }
            }
        });
    </script>


@endsection


