@extends('layouts.innerpages')

@section('template_title')
    {{ ucfirst(trim("$_SERVER[REQUEST_URI]", '/')) }}
@endsection

@section('content')
    <!--<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />-->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">

    <!-- JavaScript -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

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
            <h1 class="my-4"><b>Shipa1 Query Report</b></h1>
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
                @if (in_array('70', $phoneaccess))
                    <div class="card-header">
                        <div class="container-fluid">
                            <div class="col-lg-12 p-0">
                                <?php
                                $datas = \App\ShipaQuery::select('originstate', \DB::raw('COUNT(CASE WHEN user_id = 0 THEN 1 ELSE NULL END) as total_with_user_id_0'), \DB::raw('COUNT(*) as total'), \DB::raw('COUNT(CASE WHEN user_id = 0 THEN 1 ELSE NULL END) as remaining'))
                                    ->groupBy('originstate')
                                    ->orderBy('originstate', 'asc')
                                    ->get();

                                $user = \App\User::with('callCountUser', 'userRole')
                                    ->has('callCountUser')
                                    // where('status', 1)
                                    // ->where('role', 2)
                                    ->get();
                                // dd($user->toArray());
                                
                                $company = \App\ShipaQuery::with('user')->has('user')->get();
                                
                                ?>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label style="float: left">States</label>
                                        <select id="state" name="state" class="form-control">
                                            <option selected value="">Select</option>
                                            @foreach ($datas as $key => $val)
                                                <option value="{{ $val->originstate }}">
                                                    {{ $val->originstate }}
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
                                                        {{ $val->name . ' ' . $val->last_name . '  ' . '(' . $val->userRole->name . ')' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label style="float: left">Daterange</label>
                                        <div class='input-group'>


                                            <div class="row">

                                                <!--<label style="float: left">Daterange</label>-->
                                                <div class='input-group date' id='datetimepicker1'>
                                                    <input type='text' name="date_range" id="date_range"
                                                        class="form-control" />
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

                                                <!--    <div>-->
                                                <!--        <input type="date" class="form-control" name="startDate" id="startDate" value="">-->
                                                <!--    </div>-->
                                                <!--    <div>-->
                                                <!--        <input type="date" class="form-control" name="endDate" id="endDate" value="">-->
                                                <!--    </div>-->
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
                        @include('main.phone_quote.query_report.table')
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
                                                        <textarea required name="history_update" id='history_update' class="form-control"></textarea>
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
                        onclick="modalClick()">Close</button>
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
                "startDate": moment().startOf('month'),
                "endDate": moment().endOf('month'),
                "opens": "center",
                "drops": "auto"
            }, function(start, end, label) {
                startDate = start.format('YYYY-MM-DD');
                endDate = end.format('YYYY-MM-DD');
                $('#date_range').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                filterData();
            });

            // Initial filter on page load
            filterData();

            // Change event for other filters
            $("#orderTaker, #state").on("change", function() {
                filterData();
            });

            function filterData() {
                var orderTaker = $('#orderTaker').val();
                var state = $('#state').val();

                var url = "{{ route('filter.shipa1_query') }}";

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
                            html += "<td>" + (val['history'][0]['user']['name'] || "No User") +
                                "</td>";
                            html += "<td>" + (val['oname'] || "") + "</td>";
                            html += "<td>" + (val['originstate'] || "") + "</td>";

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
                    error: function(error) {
                        console.error("Error:", error);
                    }
                });
            }
        });
    </script>

@endsection
