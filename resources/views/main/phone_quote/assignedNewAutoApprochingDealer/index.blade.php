@extends('layouts.innerpages')

@section('template_title')
    {{ ucfirst(trim("$_SERVER[REQUEST_URI]", '/')) }}
@endsection

@section('content')
    <!--<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />-->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">

    <!-- JavaScript -->
    <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

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

        .table > thead > tr > td,
        .table > thead > tr > th {
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

        .parent1 {
            /* Remove any transform, transition, or animation properties causing rotation */
            transform: none !important;
            transition: none !important;
            animation: none !important;
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
            <h1 class="my-4"><b>Autos Approach Dealer Report</b></h1>
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
                        $phoneaccess = [];
                    }
                @endphp
                @if (in_array('138', $phoneaccess) || Auth::user()->role == 1 || Auth::user()->role == 9 || in_array('144', $phoneaccess))
                    <div class="card-header">
                        <div class="container-fluid">
                            <div class="col-lg-12 p-0">
                                    <?php
                                    $datas = \App\ShipperDetailsDealer::select('states', 'id')->where('states', '!=', '-')->groupBy('states')->orderBy('states', 'asc')->get();

                                    $user = \App\User::leftJoin('shipper_details_assign_dealer', 'user.id', '=', 'shipper_details_assign_dealer.orderTaker')
                                        ->leftJoin('roles', 'user.role', '=', 'roles.id')
                                        ->select('user.id', 'user.name', 'user.last_name', 'roles.name as role_name')
                                        ->where('user.role',2)
                                        ->where('user.status',1)
                                        ->groupBy('user.id')
                                        ->get();


                                    $company = \App\ShipperDetailsDealer::with('user')->has('user')->get();

                                    ?>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label style="float: left">Show</label>
                                        <select id="whatsapp" name="whatsapp" class="form-control">
                                            <option selected value="">All</option>
                                            <option value="whatsapp">WhatsApp</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label style="float: left">States</label>
                                        <select id="state" name="state" class="form-control">
                                            <option selected value="">Select</option>
                                            @foreach ($datas as $key => $val)
                                                <option value="{{ $val->states }}">
                                                    {{ $val->states }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label style="float: left">Order Taker</label>
                                        <div class='input-group'>
                                            <select name="orderTaker" class="form-control" id="orderTaker">
                                                <option selected value="">Select</option>
                                                @foreach ($user as $key => $val)
                                                    <option value="{{ $val->id }}">
                                                        {{ $val->name . ' ' . $val->last_name . '  ' . '(' . $val->role_name . ')' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label style="float: left">Daterange</label>
                                        <div class='input-group'>


                                            <div class="row">

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
                                    {{-- <div class="col-lg-3">
                                        <label style="float: left">Company</label>
                                        <div class='input-group'>
                                            <select name="compName" class="form-control" id="compName">
                                                <option selected value="">Select</option>
                                                @foreach ($company as $key => $val)
                                                    <option value="{{ $val->id }}">
                                                        {{ $val->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}
                                </div>
                                {{-- <div class="col-lg-2 mt-auto">
                                    <a class="btn btn-primary" href="{{ route('all.autos.approach') }}">
                                        <i class="fa fa-search"></i> Filter
                                    </a>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                @endif
                <div class="card-body">
                    <div id="table_data">
                        @include('main.phone_quote.assignedNewAutoApprochingDealer.table')
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end app-content-->
    <div class="modal fade" id="largemodal" tabindex="-1" role="dialog" aria-labelledby="largemodal" aria-hidden="true">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="largemodal1">HISTORY/STATUS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <div class="panel panel-primary">
                            <div class=" tab-menu-heading p-0 bg-light">
                                <div class="tabs-menu1 ">
                                    <!-- Tabs -->
                                    <ul class="nav panel-tabs">
                                        <li class=""><a href="#tab1" class="active"
                                                        data-toggle="tab">HISTORY/STATUS</a>
                                        </li>
                                        <li><a href="#tab2" data-toggle="tab">VIEW HISTORY</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body tabs-menu-body">
                                <div class="tab-content">
                                    <div class="tab-pane active " id="tab1">
                                        <form method="post" action="{{ route('call_history_post_2') }}">
                                            @csrf
                                            <div class="row">
                                                <input type="hidden" class="form-control" name="order_id1"
                                                       id='order_id1' placeholder="" readonly>
                                                <input type="hidden" class="form-control" name="approaching"
                                                       id='approaching' value="1">
                                                <div class="col-sm-6 col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">HISTORY</label>
                                                        <textarea required name="history_update" id='history_update'
                                                                  class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </form>
                                    </div>
                                    <div class="tab-pane" id="tab2">
                                        <div class="chat-body-style ChatBody" id="calhistory"
                                             style="overflow:scroll; height:300px;">
                                            <div class="message-feed media">
                                                <div class="media-body">
                                                    <div class="mf-content">
                                                        hi
                                                    </div>
                                                    <small class="mf-date"><i class="fa fa-clock-o"></i>2021-01-19
                                                        15:53:42
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            onclick="modalClick()">Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extraScript')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <!--</script>-->
    <script>
        let startDate, endDate;

        jQuery(document).ready(function ($) {
            var date = new Date();
            $('#date_range').daterangepicker({
                "showDropdowns": true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                },
                "alwaysShowCalendars": true,
                "startDate": moment().startOf('month'),
                "endDate": moment().endOf('month'),
                "opens": "center",
                "drops": "auto"
            }, function (start, end, label) {
                startDate = start.format('YYYY-MM-DD');
                endDate = end.format('YYYY-MM-DD');
                $('#date_range').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                filterData();
            });

            // Initial filter on page load
            filterData();

            // Change event for other filters
            $("#orderTaker, #state").on("change", function () {
                filterData();
            });
            $("#whatsapp").on("change", function () {
                filterData();
            });

            function filterData() {
                var orderTaker = $('#orderTaker').val();
                var state = $('#state').val();

                var url = "{{ route('filter.assigned.autos_approach_new_dealer') }}";
                var whatsapp = $('#whatsapp').val();
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        'orderTaker': orderTaker,
                        'whatsapp': whatsapp,
                        'state': state,
                        'startDate': startDate,
                        'endDate': endDate
                    },
                    dataType: "json",
                    success: function (data) {
                        $("#updateTable").html("");
                        var html = "";

                        html +=
                            "<table id='example' class='table table-bordered dt-responsive table-striped align-middle text-center' role='grid' aria-describedby=''>";
                        html += "<thead class='table-dark'>";
                        html += "<tr>";
                        html += "<th>Sr. #</th>";
                        html += "<th>Order Taker</th>";
                        html += "<th>Company</th>";
                        html += "<th>State</th>";
                        html += "<th>Call Count</th>";
                        html += "<th>Created At</th>";
                        html += "<th>Last History</th>";
                        html += "<th>Action</th>";
                        html += "</tr>";
                        html += "</thead>";
                        html += "<tbody>";

                        $.each(data, function (index, val) {
                            html += "<tr class='parent1'>";
                            html += "<td>" + (index + 1) + "</td>";
                            html += "<td>" + (val['history'][0]['user']['name'] || "No User") +
                                "</td>";
                            html += "<td>" + (val['name'] || "") + "</td>";
                            html += "<td>" + (val['states'] || "") + "</td>";

                            // Call Count column
                            if (val['call_count'] && val['call_count'].length) {
                                html += "<td>" + val['call_count'].length + "</td>";
                                html += "<td>" + (val['call_count'][0]['updated_at'] ||
                                    "No updated_at") + "</td>";
                            } else {
                                html += "<td>No callCount</td><td>No updated_at</td>";
                            }

                            // Last History column
                            html += "<td>";
                            if (val['history'].length) {
                                html += "<span class='text-danger'>" + val['history'][0][
                                    'connectStatus'
                                    ] + "</span> <br>";
                                html += val['history'][0]['comment'];
                            } else {
                                html += "No History";
                            }
                            html += "</td>";

                            // Action column
                            html += "<td>";
                            html +=
                                "<button type='button' class='btn btn-primary get-history' data-toggle='modal' data-target='#exampleModal7'>View History";
                            html += "<input hidden type='text' class='Company-ID' value='" + (
                                val['id'] || "") + "'>";
                            html += "<input hidden type='text' class='User-ID' value='" + (val[
                                    'user'] && val['user']['id'] ? val['user']['id'] : "") +
                                "'>";
                            html += "<input hidden type='text' class='Company-Name' value='" + (
                                val['name'] || "") + "'>";
                            html += "</button>";
                            html += "</td>";

                            html += "</tr>";
                        });

                        html += "</tbody>";
                        html += "</table>";

                        $("#updateTable").html(html);
                        $('#example').dataTable(); // Initialize DataTable
                    },
                    error: function (error) {
                        console.error("Error:", error);
                    }
                });
            }
        });
    </script>

    {{-- <script>
        let startDate;
        let endDate;
        jQuery(document).ready(function($) {
            var date = new Date();
            $('#date_range').daterangepicker({
                "showDropdowns": true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                },
                "alwaysShowCalendars": true,
                "startDate": new Date(date.getFullYear(), date.getMonth(), 1),
                "endDate": new Date(date.getFullYear(), date.getMonth() + 1, 0),
                "opens": "center",
                "drops": "auto"
            }, function(start, end, label) {
                // console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
                startDate = start.format('YYYY-MM-DD');
                endDate = end.format('YYYY-MM-DD')

                $('#date_range').val(start + ' - ' + end);
                $('#date_range').val('');
                var orderTaker = $('#orderTaker').val();
                var state = $('#state').val();
                var startDate = startDate
                var endDate = endDate
                // console.log(startDate)
                // console.log(endDate)
                var url = "{{ route('filter.assigned.autosApproach') }}";

                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        'orderTaker': orderTaker,
                        'state': state,
                        'startDate': startDate,
                        'endDate': endDate
                    },
                    dataType: "json",
                    success: function(data) {
                        // console.log(data);
                        // Handle the successful data here
                        $("#updateTable").html("");
                        var html = "";

                        html +=
                            "<table id='example' class='table table-bordered dt-responsive table-striped align-middle text-center' role='grid' aria-describedby=''>";
                        html += "<thead class='table-dark'>";
                        html += "<tr>";
                        html += "<th>Sr. #</th>";
                        html += "<th>Order Taker</th>";
                        html += "<th>Company</th>";
                        html += "<th>State</th>";
                        html += "<th>Call Count</th>";
                        html += "<th>Created At</th>";
                        html += "<th>Last History</th>";
                        html += "<th>Action</th>";
                        html += "</tr>";
                        html += "</thead>";
                        html += "<tbody>";

                        $.each(data, function(index, val) {
                            console.log('valval', val);
                            html += "<tr class='parent1'>";
                            html += "<td>" + (index + 1) + "</td>";
                            html += "<td>" + (val['history'][0]['user']['name']) +
                                "</td>";
                            html += "<td>" + (val['name'] ? val['name'] : "") + "</td>";
                            html += "<td>" + (val['state'] ? val['state'] : "") +
                                "</td>";

                            // Call Count column
                            if (val['call_count'] && val['call_count'].length) {
                                html += "<td>" + val['call_count'].length + "</td>";

                                // Check if the first element exists before accessing 'updated_at'
                                if (val['call_count'][0] && val['call_count'][0][
                                        'updated_at'
                                    ]) {
                                    html += "<td>" + val['call_count'][0][
                                            'updated_at'
                                        ] +
                                        "</td>";
                                } else {
                                    // Handle the case where 'updated_at' is undefined or not present
                                    html += "<td>No updated_at</td>";
                                }
                            } else {
                                // Handle the case where 'callCount' is undefined or has length 0
                                html += "<td>No callCount</td>";
                                html += "<td>No updated_at</td>";
                            }

                            // Last History column
                            html += "<td>";
                            if (val['history'].length) {
                                html += "<span class='text-danger'>" + val['history'][0]
                                    ['connectStatus'] + "</span> <br>";
                                html += val['history'][0]['comment'];
                            } else {
                                html += "No History";
                            }
                            html += "</td>";

                            // Action column
                            html += "<td>";
                            html +=
                                "<button type='button' class='btn btn-primary get-history' data-toggle='modal' data-target='#exampleModal7'>View History";
                            html +=
                                "<input hidden type='text' class='Company-ID' value='" +
                                (val['id'] ? val['id'] : "") + "'>";
                            html +=
                                "<input hidden type='text' class='User-ID' value='" + (
                                    val['user'] && val['user']['id'] ? val['user'][
                                        'id'
                                    ] : "") + "'>";
                            html +=
                                "<input hidden type='text' class='Company-Name' value='" +
                                (val['name'] ? val['name'] : "") + "'>";
                            html += "</button>";
                            html += "</td>";

                            html += "</tr>";
                        });

                        html += "</tbody>";
                        html += "</table>";

                        $("#updateTable").html(html);
                        $('#example').dataTable(); // Initialize DataTable
                    },
                    error: function(error) {
                        // Handle errors here
                        console.error("Error:", error);
                    }
                });

            });
            $('#date_range').val('');
        });


        $(document).ready(function() {
            $("#recordsAllowed").on("keyup", function() {
                var value = $(this).val().toLowerCase();
            });

            $("#orderTaker, #state, #startDate, #endDate").on("change", function() {

                // Get the new value of the changed input
                var orderTaker = $('#orderTaker').val();
                var state = $('#state').val();
                var startDate = $('#startDate').val(); // Make sure to get the value of startDate input
                var endDate = $('#endDate').val(); // Make sure to get the value of endDate input
                // console.log(startDate)
                // console.log(endDate)
                var url = "{{ route('filter.assigned.autosApproach') }}";

                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        'orderTaker': orderTaker,
                        'state': state,
                        'startDate': startDate,
                        'endDate': endDate
                    },
                    dataType: "json",
                    success: function(data) {
                        // console.log(data);
                        // Handle the successful data here
                        $("#updateTable").html("");
                        var html = "";

                        html +=
                            "<table id='example' class='table table-bordered dt-responsive table-striped align-middle text-center' role='grid' aria-describedby=''>";
                        html += "<thead class='table-dark'>";
                        html += "<tr>";
                        html += "<th>Sr. #</th>";
                        html += "<th>Order Taker</th>";
                        html += "<th>Company</th>";
                        html += "<th>State</th>";
                        html += "<th>Call Count</th>";
                        html += "<th>Created At</th>";
                        html += "<th>Last History</th>";
                        html += "<th>Action</th>";
                        html += "</tr>";
                        html += "</thead>";
                        html += "<tbody>";

                        $.each(data, function(index, val) {
                            html += "<tr class='parent1'>";
                            html += "<td>" + (index + 1) + "</td>";
                            html += "<td>" + (val['history'][0]['user']['name']) +
                                "</td>";
                            html += "<td>" + (val['name'] ? val['name'] : "") + "</td>";
                            html += "<td>" + (val['state'] ? val['state'] : "") +
                                "</td>";

                            // Call Count column
                            if (val['call_count'] && val['call_count'].length) {
                                html += "<td>" + val['call_count'].length + "</td>";

                                // Check if the first element exists before accessing 'updated_at'
                                if (val['call_count'][0] && val['call_count'][0][
                                        'updated_at'
                                    ]) {
                                    html += "<td>" + val['call_count'][0][
                                            'updated_at'
                                        ] +
                                        "</td>";
                                } else {
                                    // Handle the case where 'updated_at' is undefined or not present
                                    html += "<td>No updated_at</td>";
                                }
                            } else {
                                // Handle the case where 'callCount' is undefined or has length 0
                                html += "<td>No callCount</td>";
                                html += "<td>No updated_at</td>";
                            }

                            // Last History column
                            html += "<td>";
                            if (val['history'].length) {
                                html += "<span class='text-danger'>" + val['history'][0]
                                    ['connectStatus'] + "</span> <br>";
                                html += val['history'][0]['comment'];
                            } else {
                                html += "No History";
                            }
                            html += "</td>";

                            // Action column
                            html += "<td>";
                            html +=
                                "<button type='button' class='btn btn-primary get-history' data-toggle='modal' data-target='#exampleModal7'>View History";
                            html +=
                                "<input hidden type='text' class='Company-ID' value='" +
                                (val['id'] ? val['id'] : "") + "'>";
                            html +=
                                "<input hidden type='text' class='User-ID' value='" + (
                                    val['user'] && val['user']['id'] ? val['user'][
                                        'id'
                                    ] : "") + "'>";
                            html +=
                                "<input hidden type='text' class='Company-Name' value='" +
                                (val['name'] ? val['name'] : "") + "'>";
                            html += "</button>";
                            html += "</td>";

                            html += "</tr>";
                        });

                        html += "</tbody>";
                        html += "</table>";

                        $("#updateTable").html(html);
                        $('#example').dataTable(); // Initialize DataTable
                    },
                    error: function(error) {
                        // Handle errors here
                        console.error("Error:", error);
                    }
                });

            });

        });
    </script> --}}
@endsection
