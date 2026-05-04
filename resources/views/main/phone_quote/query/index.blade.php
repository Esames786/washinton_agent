@extends('layouts.innerpages')

@section('template_title')
    {{ ucfirst(trim("$_SERVER[REQUEST_URI]", '/')) }}
@endsection

@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!--=================================multiselect tag============================== -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>
    <!--=================================multiselect tag============================== -->
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
            /*color: rgb(0 0 0);*/
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
        }

        .table>thead>tr>td,
        .table>thead>tr>th {
            font-weight: 400;
            -webkit-transition: all .3s ease;
            font-size: 18px;
            color: rgb(0 0 0);
        }

        .table-data-align {
            display: flex;
            align-items: flex-end;
        }

        .tx-white {
            color: white !important;
        }

        .badge-orange {
            color: #212529;
            background-color: #F49917;
        }

        .bg-white th {
            border: 1px solid #000000 !important;
        }

        .bg-white td {
            border: 1px solid #000000 !important;
        }

        .choices__inner {
            height: 50px;
            overflow-y: scroll;
            border: 1px solid #86c8ff;
        }

        .remaining {
            color: red;
        }
    </style>
    <div class="page-header">
        <!--<div class="page-leftheader">-->
        <input type="hidden" value="{{ trim("$_SERVER[REQUEST_URI]", '/') }}" id="titlee">
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Home</a>-->
        <!--        </li>-->
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">New Order</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Shipa1 Query</b></h1>
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
                @php
                    $ptype = 1;
                    $query = \App\user_setting::where('user_id', Auth::user()->id)->first();
                    if (!empty($query)) {
                        $ptype = $query['penal_type'];
                    }
                      $datas = \App\ShipaQuery::select('originstate', \DB::raw('COUNT(CASE WHEN user_id = 0 THEN 1 ELSE NULL END) as total_with_user_id_0'), \DB::raw('COUNT(*) as total'), \DB::raw('COUNT(CASE WHEN user_id = 0 THEN 1 ELSE NULL END) as remaining'))
                      ->groupBy('originstate')
                      ->orderBy('originstate', 'asc')
                      ->get();

                    if ($ptype == 1) {
                        $phoneaccess = explode(',', Auth::user()->emp_access_phone);
                    } elseif ($ptype == 2) {
                        $phoneaccess = explode(',', Auth::user()->emp_access_web);
                    } elseif ($ptype == 3) {
                        $phoneaccess = explode(',', Auth::user()->emp_access_test);
                    } elseif ($ptype == 4) {
                        $phoneaccess = explode(',', Auth::user()->panel_type_4);
                    } elseif ($ptype == 5) {
                        $phoneaccess = explode(',', Auth::user()->panel_type_5);
                    } elseif ($ptype == 6) {
                        $phoneaccess = explode(',', Auth::user()->panel_type_6);
                    } else {
                        $phoneaccess = []; // Default case if $ptype is not within 1-6
                    }
                @endphp
                @if (in_array('148', $phoneaccess) || Auth::user()->role == 1 || Auth::user()->role == 9 || in_array('147', $phoneaccess))
                    @if (Auth::user()->role == 1 || Auth::user()->role == 9 || in_array('148', $phoneaccess))
                        <div class="card-header">
                            <div class="container-fluid">
                                <div class="col-lg-12 p-0">
                                        <?php
                                        $user = \App\User::where('deleted', 0)->where('role', 2)->get();

                                        ?>
                                    <form method="post" action="{{ route('store.autos.shipa1_query') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <label style="float: left">States</label>
                                                <select id="state" name="state[]" multiple
                                                        class="form-control state_assign" required>
                                                </select>
                                            </div>

                                            <div class="col-lg-3">
                                                <label style="float: left">Order Taker</label>
                                                <div class='input-group'>
                                                    <select name="orderTaker" class="form-control" id="orderTaker"
                                                            required>
                                                        <option selected value="">Select</option>
                                                        @foreach ($user as $key => $val)
                                                            <option value="{{ $val->id }}">
                                                                {{ $val->name . ' ' . $val->last_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <label style="float: left">Allow No. of Records</label>
                                                <div class='input-group'>
                                                    <input type='number' required name="recordsAllowed"
                                                           id="recordsAllowed"
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 mt-auto">
                                                <button class="btn btn-primary" type="submit">
                                                    <i class="fa fa-search"></i> Save
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <label style="float: left">Search AS</label>
                                            <div class='input-group'>
                                                <select name="search_as" class="form-control"
                                                        id="search_as">
                                                    <option value="1">Admin</option>
                                                    <option value="2">Myself</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <label style="float: left">Order Taker</label>
                                            <div class='input-group'>
                                                <select name="orderTakerSearch" class="form-control"
                                                        id="orderTakerSearch"
                                                        required>
                                                    <option selected value="">Select</option>
                                                    @foreach ($user as $key => $val)
                                                        <option value="{{ $val->id }}">
                                                            {{ $val->name . ' ' . $val->last_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <label style="float: left">States</label>
                                            <select id="stateComp" name="stateComp[]" class="form-control" required>
                                                <option selected value="">Select</option>
                                                @foreach ($datas as $key => $val)
                                                    <option value="{{ $val->originstate }}">
                                                        {{ $val->originstate }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <label style="float: left">Search</label>
                                            <div class='input-group'>
                                                <input type='text' required name="searchComp" id="searchComp"
                                                       class="form-control searchComp"/>
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <label style="float: left">Daterange</label>
                                            <div class='input-group'>


                                                <div class="row">

                                                    <!--<label style="float: left">Daterange</label>-->
                                                    <div class='input-group date' id='datetimepicker1'>
                                                        <input type='text' name="date_range" id="date_range"
                                                               class="form-control"/>
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
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <a class="btn btn-primary" href="{{ route('shipa1_query.reporting') }}">
                                                <i class="fa fa-search"></i> View Report
                                            </a>
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>
                    @else

                        <div class="card-header">
                            <div class="container-fluid">
                                <div class="col-lg-12 p-0">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <label style="float: left">Search</label>
                                            <div class='input-group'>
                                                <input type='text' required name="searchComp" id="searchComp"
                                                       class="form-control searchComp"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="card-header">
                        <div class="container-fluid">
                            <div class="col-lg-12 p-0">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label style="float: left">Search</label>
                                        <div class='input-group'>
                                            <input type='text' required name="searchComp" id="searchComp"
                                                   class="form-control searchComp"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="card-body">
                    <div id="table_data">

                        @include('main.phone_quote.query.table')
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

        var startDate;
        var endDate;
        $(document).ready(function () {

            function fetchData(page) {
                var stateValue = $('#stateComp').val();
                var search_as = $('#search_as').val();
                if(!search_as){
                    search_as = 2;
                }
                if (!stateValue) {
                    stateValue = $('#myState').val();
                }
                var type2 = $('#type2').val();
                // var searchValue = $('#searchComp').val();
                var searchValue = $('.searchComp').val();
                if (!searchValue) {
                    searchValue = $('#search').val();
                }
                var emailValue = $('#emailComp').val();
                var w_phone = $('#w_phone').val();
                var emailsSent = $('#emailsSent').val();
                var orderTakerSearch = $('#orderTakerSearch').val();
                var formattedStartDate;
                var formattedEndDate;
                if(startDate){
                     formattedStartDate = startDate.format('YYYY-MM-DD');
                     formattedEndDate = endDate.format('YYYY-MM-DD');
                }

                $.ajax({
                    url: '{{ url('view_query') }}' + '?page=' + page,
                    type: 'GET',
                    data: {
                        state: stateValue,
                        search: searchValue,
                        type: type2,
                        email: emailValue,
                        orderTaker: orderTakerSearch,
                        emailsSent: emailsSent,
                        w_phone: w_phone,
                        startDate: formattedStartDate,
                        endDate: formattedEndDate,
                        search_as: search_as,
                    },
                    success: function (response) {
                        // Update the UI with the fetched data
                        // console.log('Fetched data:', response);
                        $("#pagination-container").html(response.pagination);
                        $("#table_data").html(response);
                    },
                    error: function (error) {
                        console.error('Error:', error);
                        // Handle errors if needed
                    }
                });
            }
            // Listen for change event on #stateComp
            $('#stateComp').change(function () {
                fetchData(1); // Trigger AJAX call on state change for the first page
            });

            // Listen for change event on #category
            $('#type2').change(function () {
                fetchData(1); // Trigger AJAX call on state change for the first page
            });

            // Listen for keyup event on #searchComp
            // $('#searchComp').keyup(function() {
            //     fetchData(1); // Trigger AJAX call on keyup for the first page
            // });
            $('.searchComp').keyup(function () {
                fetchData(1); // Trigger AJAX call on keyup for the first page
            });

            // Listen for change event on #emailComp
            $('#emailComp').change(function () {
                fetchData(1); // Trigger AJAX call on change for the first page
            });

            // Listen for change event on #orderTakerSearch
            $('#orderTakerSearch').change(function () {
                fetchData(1); // Trigger AJAX call on change for the first page
            });

            // Listen for change event on #orderTakerSearch
            $('#emailsSent').on('change', function (event) {
                fetchData(1); // Trigger AJAX call on change for the first page
            });
            $('#w_phone').on('change', function (event) {
                fetchData(1); // Trigger AJAX call on change for the first page
            });

            // Listen for click events on pagination links
            $('#table_data').on('click', '.pagination a', function (event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                fetchData(page); // Trigger AJAX call for the clicked page number
            });

            $('#date_range').daterangepicker({
                "showDropdowns": true,
                "opens": "center",
                "drops": "auto",
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                }
            }, function (start, end, label) {
                startDate = start;
                endDate = end;
                // Fetch data when date range changes
                fetchData(1);
            });

            // Handle click event for pagination links
            $('#table_data').on('click', '.pagination a', function (event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                fetchData(page);
            });

            $(document).on("change", "#myState", function (e) {
                e.preventDefault();

                var myState = $("#myState").val();

                fetchData(1);
            });
            $(document).on("change", "#search_as", function (e) {
                e.preventDefault();

                fetchData(1);
            });

            $(document).on("keyup", "#search", function (e) {
                e.preventDefault();

                var search = $("#search").val();
                fetchData(1);

            });

            fetchData(1);


        });

        $(document).ready(function () {
            $('#state').select2({
                placeholder: "Select States",
                allowClear: true,
            });

            var all_states = @json($datas);

            $('#state').empty();

            if (all_states.length > 0) {
                all_states.forEach(state => {
                    $('#state').append(new Option(`${state.originstate} (${state.total}) (${state.remaining})`, state.originstate));
                });
            } else {
                $('#state').append(new Option("No states available", "", false, false));
            }

            $('#state').trigger('change');
        });

        $(document).on('click', '.send-email', function (event) {
            event.preventDefault();
            var email = $(this).find('.Email-Address').val();


            $.ajax({
                url: '{{ route('send.user.mail') }}',
                type: 'GET',
                data: {
                    email: email,
                },
                success: function (response) {
                    if (response.message) {
                        alert(response.message);
                    } else if (response.error) {
                        alert(response.error);
                    } else {
                        alert('Email sent');
                    }
                },
                error: function (xhr, status, error) {
                    // Parse the error message from the server
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ?
                        xhr.responseJSON.error :
                        'An error occurred while sending the email.';
                    alert(errorMessage);
                }
            });
        });

        $(document).on('click', '.view_email_history', function (event) {
            event.preventDefault();
            var email = $(this).find('.History-Email-Address').val();

            $.ajax({
                url: '{{ route('get.email.history') }}',
                type: 'GET',
                data: {
                    'email': email,
                },
                success: function (data) {
                    console.log('datas', data);
                    $(".email-history-content").html('');
                    html = "";
                    $.each(data, function (index, val) {
                        var createdAt = new Date(val['created_at']);

                        var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul",
                            "Aug", "Sep", "Oct", "Nov", "Dec"
                        ];
                        var formattedDate = monthNames[createdAt.getMonth()] + "," +
                            ("0" + createdAt.getDate()).slice(-2) + " " +
                            createdAt.getFullYear() + " " +
                            ("0" + createdAt.getHours()).slice(-2) + ":" +
                            ("0" + createdAt.getMinutes()).slice(-2) +
                            (createdAt.getHours() >= 12 ? " PM" : " AM");

                        html += "<h6>Sender " + val['user']['name'] + "</h6>";
                        html += "<h6>Template title " + val['template']['title'] + ".</h6>";
                        html += "<strong class='mf-date'><i class='fa fa-clock-o'></i>" +
                            formattedDate + "</strong> <hr>";
                    });
                    $(".email-history-content").html(html);
                },
                error: function (error) {
                    console.error('Error submitting the form:', error);
                }
            });
        });

    </script>





@endsection
