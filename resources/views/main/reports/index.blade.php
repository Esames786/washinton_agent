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
            <h1 class="my-4"><b>Reports</b></h1>
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
                <div class="card-header">
                    <div class="container-fluid">
                        <!--<form name="filterReports" id="filterReports" action="#">-->
                            <div>
                                <input type='text' name="date"  id="date_range" class="form-control"/>
                                <input type="hidden" id="user_role" value="Order_Taker" />
                            </div>
                        <!--</form>-->
                    </div>
                </div>
                <div class="card-body">

                    <div class="tab">
                        <button class="tablinks" onclick="openCity(event, 'Order_Taker')" id="defaultOpen">Order Taker
                        </button>
                        <button class="tablinks" onclick="openCity(event, 'Dispatcher')">Dispatcher</button>
                    </div>
                    <!-- Tab content -->
                    <div id="Order_Taker" class="tabcontent">
                        <h3>Order Taker</h3>
                        <div id="table_data" class="ot">
                            <div class="table-responsive">
                                {{--example1--}}
                                <table class="table table-bordered table-sm" style="width:100%" id="" role="grid"
                                       aria-describedby="example1_info">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">s.no</th>
                                        <th class="border-bottom-0">User</th>
                                        <th class="border-bottom-0">Quote Create<BR></th>
                                        <th class="border-bottom-0">Order Book</th>
                                        <th class="border-bottom-0">Order Cancel</th>
                                        <th class="border-bottom-0">Pending Payments</th>
                                        <th class="border-bottom-0">History Update</th>
                                        <th class="border-bottom-0">Click On Call</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($order_takers as $key => $val)
                                        @php
                                            $count_click_count = 0;
                                            foreach ($val->count_click as $c){
                                                $count_click_count += $c->total_clicks;
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $val->id }}</td>
                                            <td>{{ $val->name ."($val->slug)" }}</td>
                                            <td>
                                                <?php
                                                $quote_create = \App\report::where('userId',$val->id)->where('pstatus',0)->whereBetween('created_at',array($from,$to))->groupBy('orderId', 'pstatus', 'userId')->selectRaw('orderId, pstatus, userId, COUNT(*) as count')->get()->toArray();
                                                ?>
                                                {{ count($quote_create) }}
                                            </td>
                                            <td>
                                                <?php
                                                $order_book = \App\report::where('userId',$val->id)->whereIn('pstatus',[7,8,18])->whereBetween('created_at',array($from,$to))->groupBy('orderId', 'pstatus', 'userId')->selectRaw('orderId, pstatus, userId, COUNT(*) as count')->get()->toArray();
                                                ?>
                                                {{ count($order_book) }}
                                            </td>
                                            <td>
                                                <?php
                                                $cancel_order = \App\report::where('userId',$val->id)->where('pstatus',14)->whereBetween('created_at',array($from,$to))->groupBy('orderId', 'pstatus', 'userId')->selectRaw('orderId, pstatus, userId, COUNT(*) as count')->get()->toArray();
                                                ?>
                                                {{ count($cancel_order) }}
                                            </td>
                                            <td>
                                                <?php // count($val->order_book) ?>

                                                <?php
                                                $completed = \App\report::where('userId',$val->id)->where('pstatus',13)->whereBetween('created_at',array($from,$to))->groupBy('orderId', 'pstatus', 'userId')->selectRaw('orderId, pstatus, userId, COUNT(*) as count')->get()->toArray();
                                                ?>
                                                {{ count($completed) }}
                                            </td>
                                            <td>

                                                <?php

                                                //                    $orderhistory = \App\call_history::
                                                //                     whereBetween('created_at',array($from,$to))
                                                //                    ->where('userId', $val->id)
                                                //                    ->where('pstatus','!=',0)->orderby('orderId','desc')
                                                //                    ->count();

                                                $orderhistory = count($val->call_history);

                                                ?>

                                                <button data-user_id="{{$val->id}}" data-order_id="{{$val->orderId}}" type="button" class="btn btn-link btn-sm btn-show-history" data-toggle="modal" data-target="#historyModal">
                                                    <i class="fa fa-eye"></i>{{ $orderhistory}}
                                                </button>
                                            </td>

                                            <td>{{ $count_click_count }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {{  $order_takers->links() }}
                            </div>
                        </div>
                    </div>

                    <div id="Dispatcher" class="tabcontent">
                        <h3>Dispatcher</h3>
                        <div id="table_data" class="dis">
                            <div class="table-responsive">
                                {{--example1--}}
                                <table class="table table-bordered table-sm" style="width:100%" id="" role="grid"
                                       aria-describedby="example1_info">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">s.no</th>
                                        <th class="border-bottom-0">User</th>
                                        <th class="border-bottom-0">List</th>
                                        <th class="border-bottom-0">Auction Update</th>
                                        <th class="border-bottom-0">Carrier Update</th>
                                        <th class="border-bottom-0">Dispatch</th>
                                        <th class="border-bottom-0">Pickup</th>
                                        <th class="border-bottom-0">Delivery</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($dispatchers as $key => $val)
                                        <tr>
                                            <td>{{ $dispatchers->firstItem()+$key }}</td>
                                            <td>{{ $val->name ."($val->slug)" }}</td>
                                            <td>
                                                <?php
                                                    $listed = \App\report::where('userId',$val->id)->where('pstatus',9)->whereBetween('created_at',array($from,$to))->count()
                                                ?>
                                                {{ $listed }}
                                            </td>
                                            <td></td>
                                            <td>
                                                <?php
                                                    $carrier_update_count = \App\carrier::where('userId',$val->id)->whereBetween('created_at',array($from,$to))->count()
                                                ?>
                                                {{ $carrier_update_count }}
                                            </td>
                                            <td>
                                                <?php
                                                    $dispatch = \App\report::where('userId',$val->id)->where('pstatus',10)->whereBetween('created_at',array($from,$to))->count()
                                                ?>
                                                {{ $dispatch }}
                                            </td>
                                            <td>
                                                <?php
                                                    $pickup = \App\report::where('userId',$val->id)->where('pstatus',11)->whereBetween('created_at',array($from,$to))->count()
                                                ?>
                                                {{ $pickup }}
                                            </td>
                                            <td>
                                                <?php
                                                    $delivery = \App\report::where('userId',$val->id)->where('pstatus',12)->whereBetween('created_at',array($from,$to))->count()
                                                ?>
                                                {{ $delivery }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {{  $dispatchers->links() }}
                            </div>
                        </div>
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
    <script>

        $('body').delegate(".btn-show-history", "click", function() {
            var userId = $(this).data('user_id');
            var orderId = $(this).data('order_id');

            // Make an AJAX request to fetch data
            $.ajax({
                type: 'GET',
                url: '{{ url('get_history_by_user_order') }}', // Replace with your actual route
                data: { user_id: userId, order_id: orderId , from_date : "{{$from}}", to_date : "{{$to}}"},
                success: function (data) {
                    var tableHtml = '<table class="table table-bordered"><thead><tr><th>History</th><th>Created At</th></tr></thead><tbody>';

                    $.each(data, function (index, item) {
                        tableHtml += '<tr><td>' + item.orderId + '#' + item.history + '</td><td>' + item.created_at + '</td></tr>';
                    });

                    tableHtml += '</tbody></table>';

                    // Update the modal content with the table
                    $('#historyModalContent').html(tableHtml);

                },
                error: function (error) {
                    console.error('Error fetching history data:', error);
                }
            });
        });

        @if(isset($_GET['date']))
            var datee = '{{$date2}}';
            setTimeout(function() {
                $('#date_range').val(datee);
            }, 500);
        @endif

        $(function () {
            var date = new Date();
            $('#date_range').daterangepicker({
                "showDropdowns": true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                "alwaysShowCalendars": true,
                "startDate": new Date(date.getFullYear(), date.getMonth(), 1),
                "endDate": new Date(date.getFullYear(), date.getMonth() + 1, 0),
                "opens": "center",
                "drops": "auto"
            }, function (start, end, label) {
                console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
                $('#date_range').val(start + ' - ' + end);
                // $("#date_range").change(function(){
                //     var date = $(this).val();
                //     setTimeout(function() {
                //         window.location.href = '/reports?date='+date;
                //     }, 500);
                // });
            });
        });

        document.getElementById("defaultOpen").click();

        function openCity(evt, cityName) {
            $("#user_role").val(cityName);
            // Declare all variables
            var i, tabcontent, tablinks;

            // Get all elements with class="tabcontent" and hide them
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            // Get all elements with class="tablinks" and remove the class "active"
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            // Show the current tab, and add an "active" class to the button that opened the tab
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        function searchData(page)
        {
            var date = $("#date_range").val();
            var user_role = $("#user_role").val();
            $.ajax({
                url:"{{url('/reports/search')}}?page="+page,
                type:"GET",
                data:{date:date,user_role:user_role},
                dataType:"html",
                beforeSend:function()
                {
                    if(user_role == 'Order_Taker')
                    {
                        $(".ot").html('');
                        $(".ot").html(`<div class="lds-hourglass" id='ldss'></div>`);
                    }
                    else
                    {
                        $(".dis").html('');
                        $(".dis").html(`<div class="lds-hourglass" id='ldss'></div>`);
                    }
                },
                success:function(res)
                {
                    if(user_role == 'Order_Taker')
                    {
                        $(".ot").html('');
                        $(".ot").html(res);
                    }
                    else
                    {
                        $(".dis").html('');
                        $(".dis").html(res);
                    }
                }
            })
        }

        $(document).on('click', '.pagination a', function (event) {

            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            searchData(page);
        });

        $("#date_range").change(function(e){
            e.preventDefault();
            searchData(1);
        })
    </script>

@endsection


