@extends('layouts.innerpages')

@section('template_title')
    {{ ucfirst(trim('New Customers', '/')) }}
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

        .table>thead>tr>th {
            cursor: pointer;
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

        .pagi ul {
            margin: auto;
            float: right;
        }
    </style>
    <!--/app header--> <!--Page header-->
    <div class="page-header">
        <!--<div class="page-leftheader">-->
        <!--    <input type="hidden" value="{{ trim("$_SERVER[REQUEST_URI]", '/') }}" id="titlee">-->
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Home</a>-->
        <!--        </li>-->
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">New Customers</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>New Customers</b></h1>
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
    <div class="row">
        <div class="col-12">
            @if (session('flash_message'))
                <div class="alert alert-success">
                    {{ session('flash_message') }}
                </div>
            @endif
            <!--div-->
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        {{-- <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">Order Id</label>
                                <input type="text" class="orderID filterShowData form-control" placeholder="Order ID" />
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">Order Taker Name</label>
                                <input type="text" class="userName filterShowData form-control" placeholder="Order Taker Name" />
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">Client Name</label>
                                <input type="text" class="clientName filterShowData form-control" placeholder="Client Name" />
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">Destination</label>
                                <input type="text" class="delivery filterShowData form-control" placeholder="Destination" />
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">Zip</label>
                                <input type="text" class="zip filterShowData form-control" placeholder="Zip" />
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">Vehicle Name</label>
                                <input type="text" class="vehicleName filterShowData form-control" placeholder="Vehicle Name" />
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select class="form-control status">
                                    <option value="" selected>All Status</option>
                                    <option value="0">NEW</option>
                                    <option value="1">Interested</option>
                                    <option value="2">FollowMore</option>
                                    <option value="3">AskingLow</option>
                                    <option value="4">NotInterested</option>
                                    <option value="5">NoResponse</option>
                                    <option value="6">TimeQuote</option>
                                    <option value="7">PaymentMissing</option>
                                    <option value="8">Booked</option>
                                    <option value="9">Listed</option>
                                    <option value="10">Dispatch</option>
                                    <option value="11">Pickup</option>
                                    <option value="12">Delivered</option>
                                    <option value="13">Completed</option>
                                    <option value="14">Cancel</option>
                                    <option value="15">Deleted</option>
                                    <option value="16">OwesMoney</option>
                                    <option value="17">CarrierUpdate</option>
                                    <option value="18">OnApproval</option>
                                    <option value="19">OnApprovalCanceled</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <label style="float: left">Daterange</label>
                            <div class='input-group date' id='datetimepicker1'>
                                <input type='text' name="date_range"  id="date_range" class="form-control"/>
                                <span class="input-group-addon" style="
                                        border: 1px solid #ddd;
                                        border-left-color: transparent;
                                        border-radius: 0;
                                        position: relative;
                                        left: -1px;
                                        display: flex;
                                        align-items: center;
                                    ">
                                   <span class="glyphicon glyphicon-calendar"></span>
                               </span>
                            </div>
                        </div> --}}
                        {{-- <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select class="form-control status">
                                    <option value="" selected>All Status</option>
                                    <option value="0">NEW</option>
                                    <option value="1">Interested</option>
                                    <option value="2">FollowMore</option>
                                    <option value="3">AskingLow</option>
                                    <option value="4">NotInterested</option>
                                    <option value="5">NoResponse</option>
                                    <option value="6">TimeQuote</option>
                                    <option value="7">PaymentMissing</option>
                                    <option value="8">Booked</option>
                                    <option value="9">Listed</option>
                                    <option value="10">Dispatch</option>
                                    <option value="11">Pickup</option>
                                    <option value="12">Delivered</option>
                                    <option value="13">Completed</option>
                                    <option value="14">Cancel</option>
                                    <option value="15">Deleted</option>
                                    <option value="16">OwesMoney</option>
                                    <option value="17">CarrierUpdate</option>
                                    <option value="18">OnApproval</option>
                                    <option value="19">OnApprovalCanceled</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select class="form-control status">
                                    <option value="" selected>All Status</option>
                                    <option value="0">NEW</option>
                                    <option value="1">Interested</option>
                                    <option value="2">FollowMore</option>
                                    <option value="3">AskingLow</option>
                                    <option value="4">NotInterested</option>
                                    <option value="5">NoResponse</option>
                                    <option value="6">TimeQuote</option>
                                    <option value="7">PaymentMissing</option>
                                    <option value="8">Booked</option>
                                    <option value="9">Listed</option>
                                    <option value="10">Dispatch</option>
                                    <option value="11">Pickup</option>
                                    <option value="12">Delivered</option>
                                    <option value="13">Completed</option>
                                    <option value="14">Cancel</option>
                                    <option value="15">Deleted</option>
                                    <option value="16">OwesMoney</option>
                                    <option value="17">CarrierUpdate</option>
                                    <option value="18">OnApproval</option>
                                    <option value="19">OnApprovalCanceled</option>
                                </select>
                            </div>
                        </div> --}}

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Select Call Type</label>
                                <select name="call_type" class="form-control this_save call_type" id="call_type">
                                    <option value="">All</option>
                                    <option value="In Bound">In Bound</option>
                                    <option value="Out Bound">Out Bound</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" required>How Did You Find Us?</label>
                                <select class="form-control this_save how_did_you_find_us" name="how_did_you_find_us"
                                    id="how_did_you_find_us">
                                    <option value="" selected disabled>Select an option</option>
                                    <option value="existing_customer">Refered by Existing Customer</option>
                                    <option value="social_media">Social Media</option>
                                    <option value="review_platform">Review Platform</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4" id="ref_code_group" style="display: none;">
                            <div class="form-group">
                                <label class="form-label">Phone</label>
                                <input type="text" name="found_on_referral_phone"
                                    class="form-control this_save found_on_referral_phone referal_phone"
                                    placeholder="Enter Phone">
                            </div>
                        </div>

                        <div class="col-md-4" id="social_media_group" style="display: none;">
                            <div class="form-group">
                                <label class="form-label">Select Social Media</label>
                                <select name="found_on_social_media" class="form-control this_save found_on_social_media">
                                    <option value="Facebook">Facebook</option>
                                    <option value="Instagram">Instagram</option>
                                    <option value="Tiktok">Tiktok</option>
                                    <option value="Youtube">Youtube</option>
                                    <option value="Insta">Insta</option>
                                    <option value="Twitter">Twitter</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4" id="review_platform_group" style="display: none;">
                            <div class="form-group">
                                <label class="form-label">Select Review Platform</label>
                                <select name="found_on_review_platform"
                                    class="form-control this_save found_on_review_platform">
                                    <option value="google">Google</option>
                                    <option value="bbb">BBB</option>
                                    <option value="trust_pilot">Trust Pilot</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <label style="float: left">Daterange</label>
                            <div class='input-group date' id='datetimepicker1'>
                                <input type='text' name="date_range" id="date_range" class="form-control" />
                                <span class="input-group-addon"
                                    style="
                                        border: 1px solid #ddd;
                                        border-left-color: transparent;
                                        border-radius: 0;
                                        position: relative;
                                        left: -1px;
                                        display: flex;
                                        align-items: center;
                                    ">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>

                        <div class="col-md-4" style="margin-top: 28px !important;">
                            <button type="button" class="btn btn-success w-100" id="search">Search</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <!-- Tab content -->
                    <div class="">
                        <div class="d-flex justify-content-between align_center m-2">
                            <h3 class="m-0">New Customers</h3>
                            {{-- <div class="btn-group">
                                <button class="btn btn-primary reset">Reset Filter</button>
                            </div> --}}
                        </div>
                        <input type="hidden" value="{{ url('') }}" class="url">
                        <div id="table_data">
                            <div class="table-responsive" style="padding-bottom: 1rem;">
                                <table class="table table-bordered table-sm" style="width:100%" role="grid">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0">Order Id</th>
                                            <th class="border-bottom-0">Order Taker Name</th>
                                            <th class="border-bottom-0">Client Name</th>
                                            <th class="border-bottom-0">Delivery</th>
                                            <th class="border-bottom-0">Zip</th>
                                            <th class="border-bottom-0">Vehicle Name</th>
                                            <th class="border-bottom-0">Status</th>
                                            <th class="border-bottom-0">Last Time</th>
                                            <th class="border-bottom-0">Referral</th>
                                        </tr>
                                    </thead>
                                    <tbody class="showData">
                                        @foreach ($order as $key => $value)
                                            <tr>
                                                <td>
                                                    <a
                                                        href="/searchData?search={{ $value->id }}">{{ $value->id }}</a>
                                                </td>
                                                <?php
                                                $name = '';
                                                if (isset($value->orderTaker)) {
                                                    $name = $value->orderTaker->slug ? $value->orderTaker->slug : $value->orderTaker->name;
                                                }
                                                ?>
                                                <td>{{ $name }}</td>
                                                <td>{{ $value->oname }}</td>
                                                <td>{{ $value->destinationcity }}</td>
                                                <td>{{ $value->destinationzip }}</td>
                                                <td>{{ $value->ymk }}</td>
                                                <td>{{ get_pstatus($value->pstatus) }}</td>
                                                <td>Created At:
                                                    <br>{{ \Carbon\Carbon::parse($value->created_at)->format('M,d Y') }}<br>{{ \Carbon\Carbon::parse($value->created_at)->format('h:i A') }}
                                                </td>
                                                <td>
                                                    @if ($value->how_did_you_find_us === 'existing_customer')
                                                        Referred by Existing Customer
                                                    @elseif($value->how_did_you_find_us === 'social_media')
                                                        Social Media
                                                    @elseif($value->how_did_you_find_us === 'review_platform')
                                                        Review Platform
                                                    @endif
                                                    <br>
                                                    @if ($value->found_on_referral_phone)
                                                        <?php
                                                        $digits = \App\PhoneDigit::first();
                                                        $new = putX($digits->hide_digits, $digits->left_right_status, $value->found_on_referral_phone);
                                                        // dd($digits, $new);
                                                        ?>
                                                        <a
                                                            href="/searchData?search={{ $value->found_on_referral_phone }}">
                                                            {{ $new }}
                                                        </a>
                                                        {{-- {{ $value->found_on_referral_phone }} --}}
                                                    @endif
                                                    @if ($value->found_on_social_media)
                                                        {{ $value->found_on_social_media }}
                                                    @endif
                                                    @if ($value->found_on_review_platform)
                                                        {{ $value->found_on_review_platform }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="text-secondary my-auto">
                                    Showing {{ $order->firstItem() ?? 0 }} to {{ $order->lastItem() ?? 0 }} from total
                                    {{ $order->total() }} entries
                                </div>
                                <div class="pagi">
                                    {{ $order->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end app-content-->
@endsection

@section('extraScript')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function filteredData(page) {
                var how_did_you_find_us = $('.how_did_you_find_us');
                var found_on_referral_phone = $('.found_on_referral_phone');
                var found_on_social_media = $('.found_on_social_media');
                var found_on_review_platform = $('.found_on_review_platform');
                var date_range = $('#date_range');
                var call_type = $('#call_type');

                $.ajax({
                    url: "{{ url('/search_allNew_user') }}?page=" + page,
                    type: "GET",
                    data: {
                        how_did_you_find_us: how_did_you_find_us.val(),
                        found_on_referral_phone: found_on_referral_phone.val(),
                        found_on_social_media: found_on_social_media.val(),
                        found_on_review_platform: found_on_review_platform.val(),
                        date_range: date_range.val(),
                        call_type: call_type.val(),
                    },
                    beforeSend: function() {
                        $("#table_data").html("");
                        $('#table_data').append(`<div class="lds-hourglass" id='ldss'></div>`);
                    },
                    success: function(res) {
                        $("#table_data").html("");
                        $('#table_data').html(res);
                    }
                });
            }

            $('.reset').click(function() {
                $('.how_did_you_find_us').val('');
                $('.found_on_referral_phone').val('');
                $('.found_on_social_media').val('');
                $('.found_on_review_platform').val('');
                $('#date_range').val('');
                $('#call_type').val('');
                filteredData(1);
            })

            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                filteredData(page);
            });

            $("#search").on('click', function(event) {
                event.preventDefault();
                filteredData(1);
            });


            $(function() {
                var date = new Date();
                $('#date_range').daterangepicker({
                    "showDropdowns": true,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment()
                            .subtract(1, 'month').endOf('month')
                        ]
                    },
                    "alwaysShowCalendars": true,
                    "startDate": new Date(date.getFullYear(), date.getMonth(), 1),
                    "endDate": new Date(date.getFullYear(), date.getMonth() + 1, 0),
                    "opens": "center",
                    "drops": "auto"
                }, function(start, end, label) {
                    console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' +
                        end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
                    // $('#date_range').val(start + ' - ' + end);
                    $('#date_range').val('');
                });
                $('#date_range').val('');
            });
        })
    </script>
    <script>
        $(document).ready(function() {
            $('#how_did_you_find_us').on('change', function() {
                $('#ref_code_group').hide();
                $('#social_media_group').hide();
                $('#review_platform_group').hide();

                var selectedOption = $(this).val();
                if (selectedOption === 'existing_customer') {
                    $('#ref_code_group').show();
                } else if (selectedOption === 'social_media') {
                    $('#social_media_group').show();
                } else if (selectedOption === 'review_platform') {
                    $('#review_platform_group').show();
                }
            });
            $(".referal_phone").keypress(function(e) {
                if ($(this).val() == '') {
                    $(this).mask("(999) 999-9999");
                }
                var x = e.which || e.keycode;
                if ((x >= 48 && x <= 57) || (x >= 35 && x <= 40)) {
                    return true;
                } else {
                    return false;
                }
            })
        });
    </script>
@endsection
