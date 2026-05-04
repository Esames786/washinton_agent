@extends('layouts.innerpages')

@section('template_title')
    {{ ucfirst(trim('Filtered Data', '/')) }}
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
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">Filtered Data</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Filtered Data</b></h1>
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
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">Order Id</label>
                                <input type="text" class="orderID filterShowData form-control" placeholder="Order ID" />
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">Order Taker Name</label>
                                <input type="text" class="userName filterShowData form-control"
                                    placeholder="Order Taker Name" />
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">Client Name</label>
                                <input type="text" class="clientName filterShowData form-control"
                                    placeholder="Client Name" />
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">Delivery</label>
                                <input type="text" class="delivery filterShowData form-control" placeholder="Delivery" />
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
                                <input type="text" class="vehicleName filterShowData form-control"
                                    placeholder="Vehicle Name" />
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
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">VIN Number</label>
                                <input type="text" class="vin_num filterShowData form-control"
                                    placeholder="VIN Number" />
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">Customer Phone</label>
                                <input type="text" class="custPhone filterShowData form-control"
                                    placeholder="Customer Phone" />
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">Delivery Phone</label>
                                <input type="text" class="dphone filterShowData form-control"
                                    placeholder="Delivery Phone" />
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">Buyer #</label>
                                <input type="text" class="buyer_no filterShowData form-control"
                                    placeholder="Buyer #" />
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">Lot #</label>
                                <input type="text" class="lot_no filterShowData form-control" placeholder="Lot #" />
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">Stock #</label>
                                <input type="text" class="stock_no filterShowData form-control"
                                    placeholder="Stock #" />
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">Select Call Type</label>
                                <select name="call_type" class="form-control this_save call_type filterShowData"
                                    id="call_type">
                                    <option value="">All</option>
                                    <option value="In Bound">In Bound</option>
                                    <option value="Out Bound">Out Bound</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">Port</label>
                                <div class="form-check">
                                    <input type="hidden" name="port" value="">
                                    <input type="checkbox" class="form-check-input port_val" id="port"
                                        name="port" value="Port">
                                    <label class="form-check-label" for="port">Port</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 m-auto text-right" style="margin-top: 28px !important;">
                            <a href="{{ route('search.allNew.user') }}" type="button" class="btn btn-primary w-100">All
                                New User</a>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 m-auto text-right" style="margin-top: 28px !important;">
                            <button type="button" class="btn btn-success w-100" id="search">Search</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <!-- Tab content -->
                    <div class="">
                        <div class="d-flex justify-content-between align_center m-2">
                            <h3 class="m-0">Filtered Data</h3>
                            <div class="btn-group">
                                <button class="btn btn-primary reset">Reset Filter</button>
                            </div>
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
    {{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        // function searchFilter(page_num) {
        //     var url = $('.url').val();
        //     var showData = $('.showData');
        //     showData.children().remove();
        //     page_num = page_num?page_num:0;
        //     var orderID = $('.orderID');
        //     var userName = $('.userName');
        //     var clientName = $('.clientName');
        //     var delivery = $('.delivery');
        //     var zip = $('.zip');
        //     var vehicle = $('.vehicleName');
        //     var status = $('.status');

        //     $.ajax({
        //         url:url+'/filtered-search',
        //         type:'POST',
        //         dataType:'json',
        //         data:{orderID:orderID.val(),userName:userName.val(),clientName:clientName.val(),delivery:delivery.val(),zip:zip.val(),vehicle:vehicle.val(),status:status.val(),page_num:page_num},
        //         beforeSend: function () {
        //             showData.children().remove();
        //             showData.append(`<tr><td align="center" colspan="7"><div class="lds-hourglass" id='ldss'></div></td></tr>`);
        //         },
        //         success:function(res){
        //             if(orderID.val() != '')
        //             {
        //                 orderID.siblings('.searchBox').children('.fa-check').show();
        //             }
        //             else{
        //                 orderID.siblings('.searchBox').children('.fa-check').hide();
        //             }
        //             if(userName.val() != '')
        //             {
        //                 userName.siblings('.searchBox').children('.fa-check').show();
        //             }
        //             else{
        //                 userName.siblings('.searchBox').children('.fa-check').hide();
        //             }
        //             if(clientName.val() != '')
        //             {
        //                 clientName.siblings('.searchBox').children('.fa-check').show();
        //             }
        //             else{
        //                 clientName.siblings('.searchBox').children('.fa-check').hide();
        //             }
        //             if(delivery.val() != '')
        //             {
        //                 delivery.siblings('.searchBox').children('.fa-check').show();
        //             }
        //             else{
        //                 delivery.siblings('.searchBox').children('.fa-check').hide();
        //             }
        //             if(zip.val() != '')
        //             {
        //                 zip.siblings('.searchBox').children('.fa-check').show();
        //             }
        //             else{
        //                 zip.siblings('.searchBox').children('.fa-check').hide();
        //             }
        //             if(vehicle.val() != '')
        //             {
        //                 vehicle.siblings('.searchBox').children('.fa-check').show();
        //             }
        //             else{
        //                 vehicle.siblings('.searchBox').children('.fa-check').hide();
        //             }
        //             if(status.val() != '')
        //             {
        //                 status.parents('.filterShowData').siblings('.searchBox').children('.fa-check').show();
        //             }
        //             else{
        //                 status.parents('.filterShowData').siblings('.searchBox').children('.fa-check').hide();
        //             }
        //             // console.log(res);
        //             if(res.order)
        //             {
        //                 showData.children().remove();
        //                 var pstatus = '';
        //                 $.each(res.order,function(){
        //                     if (this.pstatus == 0) {
        //                         pstatus = "NEW";
        //                     } else if (this.pstatus == 1) {
        //                         pstatus = "Interested";
        //                     } else if (this.pstatus == 2) {
        //                         pstatus = "FollowMore";
        //                     } else if (this.pstatus == 3) {
        //                         pstatus = "AskingLow";
        //                     } else if (this.pstatus == 4) {
        //                         pstatus = "NotInterested";
        //                     } else if (this.pstatus == 5) {
        //                         pstatus = "NoResponse";
        //                     } else if (this.pstatus == 6) {
        //                         pstatus = "TimeQuote";
        //                     } else if (this.pstatus == 7) {
        //                         pstatus = "PaymentMissing";
        //                     } else if (this.pstatus == 8) {
        //                         pstatus = "Booked";
        //                     } else if (this.pstatus == 9) {
        //                         pstatus = "Listed";
        //                     } else if (this.pstatus == 10) {
        //                         pstatus = "Dispatch";
        //                     } else if (this.pstatus == 11) {
        //                         pstatus = "Pickup";
        //                     } else if (this.pstatus == 12) {
        //                         pstatus = "Delivered";
        //                     } else if (this.pstatus == 13) {
        //                         pstatus = "Completed";
        //                     } else if (this.pstatus == 14) {
        //                         pstatus = "Cancel";
        //                     } else if (this.pstatus == 15) {
        //                         pstatus = "Deleted";
        //                     } else if (this.pstatus == 16) {
        //                         pstatus = "OwesMoney";
        //                     } else if (this.pstatus == 17) {
        //                         pstatus = "CarrierUpdate";
        //                     } else if (this.pstatus == 18) {
        //                         pstatus = "OnApproval";
        //                     }else if (this.pstatus == 19) {
        //                         pstatus = "On Approval Canceled";
        //                     }
        //                     var name = '';
        //                     if(this.order_taker)
        //                     {
        //                         name = this.order_taker.slug != null ? this.order_taker.slug : this.order_taker.name;
        //                     }
        //                     else if(userName.val())
        //                     {
        //                         name = userName.val();
        //                     }
        //                     else{
        //                         name = this.filter_history.filter_user.slug != null ? this.filter_history.filter_user.slug : this.filter_history.filter_user.name;
        //                     }
        //                     showData.append(`
    //                         <tr>
    //                             <td>${this.id}</td>
    //                             <td>${name}</td>
    //                             <td>${this.oname}</td>
    //                             <td>${this.destinationcity}</td>
    //                             <td>${this.destinationzip}</td>
    //                             <td>${this.ymk}</td>
    //                             <td>${pstatus}</td>
    //                         </tr>
    //                     `);
        //                     // console.log(status);
        //                 });
        //                 showData.parents('table').siblings('.pagi').children().remove();
        //                 showData.parents('table').siblings('.pagi').append(`
    //                     <div class="row">
    //                         <div class="col-lg-42">${res4page}</div>
    //                     </div>
    //                 `);
        //             }
        //             if(res.order.data == [] || res.order.data == '')
        //             {
        //                 showData.children().remove();
        //                 showData.append(`<tr><td align="center" colspan="7"><h1 class="mt-3">No Data Found!</h1></td></tr>`);
        //             }
        //         }
        //     });
        // }
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function filteredData(page) {
                var orderID = $('.orderID');
                var userName = $('.userName');
                var clientName = $('.clientName');
                var delivery = $('.delivery');
                var zip = $('.zip');
                var vehicle = $('.vehicleName');
                var status = $('.status');
                var date_range = $('#date_range').val();
                var vin_num = $('.vin_num');
                var custPhone = $('.custPhone');
                var dphone = $('.dphone');
                var buyer_no = $('.buyer_no');
                var lot_no = $('.lot_no');
                var stock_no = $('.stock_no');
                var call_type = $('.call_type');
                var port_val = $('#port').is(':checked') ? $('#port').val() : '';

                $.ajax({
                    url: "{{ url('/filtered-data') }}?page=" + page,
                    type: "GET",
                    data: {
                        date_range: date_range,
                        orderID: orderID.val(),
                        userName: userName.val(),
                        clientName: clientName.val(),
                        delivery: delivery.val(),
                        zip: zip.val(),
                        vehicle: vehicle.val(),
                        status: status.val(),
                        vin_num: vin_num.val(),
                        custPhone: custPhone.val(),
                        dphone: dphone.val(),
                        buyer_no: buyer_no.val(),
                        lot_no: lot_no.val(),
                        stock_no: stock_no.val(),
                        call_type: call_type.val(),
                        port_val: port_val,
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
                $('.orderID').val('');
                $('.userName').val('');
                $('.clientName').val('');
                $('.delivery').val('');
                $('.zip').val('');
                $('.vin_num').val('');
                $('.vehicle').val('');
                $('.custPhone').val('');
                $('.dphone').val('');
                $('.buyer_no').val('');
                $('.lot_no').val('');
                $('.stock_no').val('');
                $('.call_type').val('');
                $('.port_val').val('');
                // $('.status').children("option").attr("selected",false);
                // $('.status').children("option").text("All Status").attr("selected",true);
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

            $(".custPhone").keypress(function(e) {
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

            $(".dphone").keypress(function(e) {
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
        })
    </script>
@endsection
