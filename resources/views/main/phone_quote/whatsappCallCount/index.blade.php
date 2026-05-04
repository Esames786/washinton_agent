@extends('layouts.innerpages')

@section('template_title')
    {{ ucfirst(trim("$_SERVER[REQUEST_URI]", '/')) }}
@endsection

@section('content')
    <!--<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />-->
    <!--<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>-->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
            <h1 class="my-4"><b>Autos Approach</b></h1>
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
                                $datas = \App\UsedAndNewCarDealers::select('state', 'id')->where('state', '!=', '-')->groupBy('state')->orderBy('state', 'asc')->get();
                                
                                $user = \App\User::with('whatsappCountUser', 'userRole')
                                    ->has('whatsappCountUser')
                                    // where('status', 1)
                                    // ->where('role', 2)
                                    ->get();
                                // dd($user->toArray());
                                
                                $company = \App\UsedAndNewCarDealers::with('user')->has('user')->get();
                                
                                ?>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label style="float: left">States</label>
                                        <select id="state" name="state" class="form-control">
                                            <option selected value="">Select</option>
                                            @foreach ($datas as $key => $val)
                                                <option value="{{ $val->state }}">
                                                    {{ $val->state }}
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
                        @include('main.phone_quote.whatsappCallCount.table')
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
    <script type="text/javascript">
        // $(document).ready(function() {
        //     $('#example').DataTable();

        //     var start = moment().subtract(29, 'days');
        //     var end = moment();

        //     $('#start').val(start);
        //     $('#end').val(end);

        //     function cb(start, end) {
        //         $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

        //         var url = "";
        //         // var newValue = $(this).val();
        //         var type = $('#type').val();
        //         var agent = $('#agent').val();
        //         // var date = $('#date').val();
        //         var search = $('#search').val();
        //         var status = $('#status').val();

        //         $.ajax({
        //             type: "GET",
        //             url: url,
        //             data: {
        //                 'start': start.format('YYYY-MM-DD'),
        //                 'end': end.format('YYYY-MM-DD'),
        //                 'type': type,
        //                 'agent': agent,
        //                 'search': search,
        //                 'status': status
        //             },
        //             dataType: "json",
        //             success: function(data) {
        //                 $("#myTable").html("");
        //                 var html = "";

        //                 $.each(data, function(index, val) {
        //                     if (val['agent'] !== null) {
        //                         html += "<tr>";
        //                         var date = new Date(val['created_at']);
        //                         var formattedDate = date.toLocaleString('en-US', {
        //                             year: 'numeric',
        //                             month: '2-digit',
        //                             day: '2-digit',
        //                             hour: '2-digit',
        //                             minute: '2-digit',
        //                             second: '2-digit'
        //                         });

        //                         html += "<td>" + formattedDate + "</td>";
        //                         html += "<td>" + val['company_name'] + "</td>";
        //                         html += "<td>" + val['company_name'] + "</td>";
        //                         html += "<td><span class='btn btn-secondary'>" + val['agent'][
        //                             'name'
        //                         ] + "</span></td>";
        //                         html += "<td>" + val['call_count'].length + "</td>";
        //                         html += "<td>";
        //                         var lastStatusArray = val['last_status'];
        //                         if (lastStatusArray && lastStatusArray.length) {
        //                             var connectStatus = lastStatusArray[0]['connectStatus'];
        //                             if (connectStatus !== null && connectStatus !== undefined) {
        //                                 if (connectStatus === 'Connected') {
        //                                     html += "<span class='badge bg-success'>" +
        //                                         connectStatus + "</span>";
        //                                 } else {
        //                                     html += "<span class='badge bg-danger'>" +
        //                                         connectStatus + "</span>";
        //                                 }
        //                             } else {
        //                                 html +=
        //                                     "<span class='badge bg-warning'>No Connect Status</span>";
        //                             }
        //                         } else {
        //                             html += "N/A";
        //                         }
        //                         html += "</td>";
        //                         html += "<td>";
        //                         if (lastStatusArray && lastStatusArray.length) {
        //                             html +=
        //                                 "<button class='btn btn-info get-history' data-bs-toggle='modal' data-bs-target='#showModal' id=''>" +
        //                                 "View History" +
        //                                 "<input type='hidden' class='company_name' name='company_name' value='" +
        //                                 val['company_name'] + "'>" +
        //                                 "<input type='hidden' class='company_id' name='company_id' value='" +
        //                                 val['id'] + "'>" +
        //                                 "</button>"
        //                         } else {
        //                             html += "N/A";
        //                         }
        //                         html += "</td>";
        //                         html += "</tr>";
        //                         html += "<tr>";
        //                     }
        //                 });

        //                 $("#myTable").html(html);
        //             },
        //             error: function(error) {
        //                 console.error("Error:", error);
        //             }
        //         });
        //     }

        //     $('#reportrange').daterangepicker({
        //         startDate: start,
        //         endDate: end,
        //         ranges: {
        //             'Today': [moment(), moment()],
        //             'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        //             'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        //             'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        //             'This Month': [moment().startOf('month'), moment().endOf('month')],
        //             'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
        //                 'month').endOf('month')]
        //         }
        //     }, cb);

        //     cb(start, end);
        // });
    </script>
    <script>
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
                var startDate = startDate;
                var endDate = endDate;
                var whatsapp = 'whatsapp';
                console.log(startDate)
                console.log(endDate)
                var url = "{{ route('filter.assigned.autosApproach') }}";

                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        'orderTaker': orderTaker,
                        'state': state,
                        'startDate': startDate,
                        'endDate': endDate,
                        'whatsapp': whatsapp
                    },
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
                        // Handle the successful data here
                        $("#updateTable").html("");
                        var html = "";

                        html +=
                            "<table class='table table-bordered table-sm col-lg-2 fs-18 text-center pd-2 bd-l' role='grid' aria-describedby=''>";
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
                            html += "<td>" + (val['user'] && val['user']['name'] ? val[
                                'user']['name'] : "") + "</td>";
                            html += "<td>" + (val['name'] ? val['name'] : "") + "</td>";
                            html += "<td>" + (val['state'] ? val['state'] : "") +
                                "</td>";

                            // Call Count column
                            if (val['whatsapp_call_count'] && val['whatsapp_call_count']
                                .length) {
                                html += "<td>" + val['whatsapp_call_count'].length +
                                    "</td>";

                                // Check if the first element exists before accessing 'updated_at'
                                if (val['whatsapp_call_count'][0] && val[
                                        'whatsapp_call_count'][0][
                                        'updated_at'
                                    ]) {
                                    html += "<td>" + val['whatsapp_call_count'][0][
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

            $("#orderTaker, #state, startDate, endDate").on("change", function() {

                console.log('dasdasdasda');

                // Get the new value of the changed input
                var orderTaker = $('#orderTaker').val();
                var state = $('#state').val();
                var startDate = startDate;
                var endDate = endDate;
                var whatsapp = 'whatsapp';
                console.log(startDate)
                console.log(endDate)
                var url = "{{ route('filter.assigned.autosApproach') }}";

                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        'orderTaker': orderTaker,
                        'state': state,
                        'startDate': startDate,
                        'endDate': endDate,
                        'whatsapp': whatsapp
                    },
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
                        // Handle the successful data here
                        $("#updateTable").html("");
                        var html = "";

                        html +=
                            "<table class='table table-bordered table-sm col-lg-2 fs-18 text-center pd-2 bd-l' role='grid' aria-describedby=''>";
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
                            html += "<td>" + (val['user'] && val['user']['name'] ? val[
                                'user']['name'] : "") + "</td>";
                            html += "<td>" + (val['name'] ? val['name'] : "") + "</td>";
                            html += "<td>" + (val['state'] ? val['state'] : "") +
                                "</td>";

                            // Call Count column
                            if (val['whatsapp_call_count'] && val['whatsapp_call_count']
                                .length) {
                                html += "<td>" + val['whatsapp_call_count'].length +
                                    "</td>";

                                // Check if the first element exists before accessing 'updated_at'
                                if (val['whatsapp_call_count'][0] && val[
                                        'whatsapp_call_count'][0][
                                        'updated_at'
                                    ]) {
                                    html += "<td>" + val['whatsapp_call_count'][0][
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
                            if (val['history'] && val['history'].length) {
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
                    },
                    error: function(error) {
                        // Handle errors here
                        console.error("Error:", error);
                    }
                });

            });

        });
    </script>
@endsection
