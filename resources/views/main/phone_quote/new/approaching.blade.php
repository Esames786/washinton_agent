@extends('layouts.innerpages')

@section('template_title')
    {{  ucfirst(trim("$_SERVER[REQUEST_URI]",'/'))}}
@endsection

@section('content')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
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

        .table > thead > tr > td, .table > thead > tr > th {
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
            <input type="hidden" value="{{trim("$_SERVER[REQUEST_URI]",'/')}}" id="titlee">
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Home</a>-->
        <!--        </li>-->
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">New Order</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Approaching</b></h1>
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
                        $phoneaccess = []; // Default case if $ptype is not within 1-6
                    }

                @endphp
                <?php
                $order_takers = \App\User::with('userRole')->whereHas('userRole',function ($q){
                    $q->where('name','Order Taker')
                        ->orWhere('name','Seller Agent')
                        ->orWhere('name','CSR')
                        ->orWhere('name','Manager');
                })->where('deleted',0)->orderBy('role','ASC')->select('id','name','slug','role')->get();
                ?>
                @if(in_array("70",$phoneaccess))
                    <div class="card-header">
                    <div class="container-fluid">

                        <form method="post" action="{{ route('store.approaching_assign') }}">
                            @csrf
                            <div class="row" style="border:groove;padding: 10px">
                                <div class="col-lg-3">
                                    <label style="float: left">Search AS</label>
                                    <div class='input-group'>
                                        <select name="search_as" class="form-control" onchange="return_data(1)"
                                                id="search_as">
                                            <option value="1">Admin View</option>
                                            <option value="2">Myself</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <label style="float: left">OrderTaker</label>
                                    <div class='input-group'>
                                        <select name="orderTaker" class="form-control" id="orderTaker" required>
                                            <option value="" selected>Select</option>
                                            @foreach($order_takers as $key => $val)
                                                <option value="{{$val->id}}">{{$val->slug ?? $val->name}} ({{$val->userRole->name}})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <label style="float: left">Daterange (Default 20 days old)<button type="button" class="btn btn-info btn-sm" onclick="$('#date_range2').val('')" style="padding: 3.2px 10px;">Clear</button></label>
                                    <div class='input-group date' id='datetimepicker1'>
                                        <input type='text' name="date_range2"  id="date_range2" onchange="" class="form-control" />
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
                                </div>
                                <div class="col-lg-3">
                                    <label style="float: left">Status</label>
                                    <div class='input-group'>
                                        <select name="status" class="form-control" id="status" onchange="return_data(1)" required>
                                            <option selected value="">Select Status</option>
                                            <option value="0">New</option>
                                            <option value="1">Interested</option>
                                            <option value="2">FollowMore</option>
                                            <option value="3">AskingLow</option>
                                            <option value="4">Not Interested</option>
                                            <option value="44" style="color: #f8e50c">Only Not/Interested</option>
                                            <option value="5">No Response</option>
                                            <option value="6">Time Quote</option>
                                            <option value="7">Payment Missing</option>
                                            <option value="8">Booked</option>
                                            <option value="9">Listed</option>
                                            <option value="10">Schedule</option>
                                            <option value="13">Completed</option>
                                            <option value="14">Cancel</option>
                                            <option value="144" style="color: #f8e50c">Only Cancel</option>
                                            <option value="15">Deleted</option>
                                            <option value="16">OwesMoney</option>
                                            <option value="17">Carrier Update</option>
                                            <option value="18">On Approval</option>
                                            <option value="19">On Approval Canceled</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Order Taker Name</label>
                                        <input type="text" class="userName filterShowData form-control"
                                               placeholder="Order Taker Name" onchange="return_data(1)"  />
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Delivery City</label>
                                        <input type="text" name="delivery_city" class="delivery filterShowData form-control" onchange="return_data(1)" placeholder="Delivery" />
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Delivery Zip</label>
                                        <input type="text" name="delivery_zip" class="zip filterShowData form-control" placeholder="Zip" onchange="return_data(1)" />
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <label style="float: left">Email</label>
                                    <select id="emailComp" name="emailComp" class="form-control" onchange="return_data(1)">
                                        <option selected value="">Select</option>
                                        <option value="1">With Email</option>
                                        <option value="0">Without Email</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">O-Terminal</label>
                                        <select class="form-control select2" name="oterminal" id="oterminal" onchange="return_data(1)">

                                            <option value="">Select an Option</option>
                                            <option value="1">Residence</option>
                                            <option value="2">COPART Auction</option>
                                            <option value="3">Manheim Auction</option>
                                            <option value="4">IAAI Auction</option>
                                            <option value="5">Body Shop</option>
                                            <option value="10">Dealership</option>
                                            <option value="7">Business Location</option>
                                            <option value="8">Auction (Heavy)</option>
                                            <option value="6">Other</option>

                                        </select>

                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Select Call Type</label>
                                        <select  name="call_type" class="form-control this_save call_type filterShowData" onchange="return_data(1)"
                                                id="call_type">
                                            <option value="">All</option>
                                            <option value="In Bound">In Bound</option>
                                            <option value="Out Bound">Out Bound</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <label style="float: left">Allow No. of Records</label>
                                    <div class='input-group'>
                                        <input type='number' required name="recordsAllowed"  value="{{ count($total_count) }}" id="recordsAllowed" onkeyup="return_data(1,this.value)"
                                               class="form-control" />
                                    </div>
                                </div>
                                <div class="col-lg-2 mt-auto">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fa fa-search"></i> Save
                                    </button>
                                </div>
                            </div>
                            <div class="row" style="border:groove;padding: 10px">
                                <div class="col-lg-3">
                                    <label style="float: left">Template</label>
                                    <select id="template" name="template" class="form-control template" >
                                        @foreach($template as $vl)
                                            <option value="{{ $vl->id }}">
                                                {{ $vl->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label style="float: left">Limit</label>
                                    <input type="text" min="0" max="10000" placeholder="limit" class="form-control" name="limit_data" id="limit_data" value="{{ $paginatedData->count() }}">
                                </div>
                                <div class="col-lg-2 mt-auto">
                                    <button class="btn btn-primary" type="button" id="send_template">
                                        Send Email Template
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" id="order_ids" name="order_ids" value="{{ implode(',',$total_count) }}">
                        </form>
                    </div>
                </div>

                <div class="col-lg-2">
                    <a target="_blank" class="btn btn-primary" href="{{ route('approaching_reporting') }}">
                        <i class="fa fa-search"></i> View Report
                    </a>
                </div>
                    <div class="col-lg-2">
                    <a target="_blank" class="btn btn-primary" href="{{ route('assign.approaching_reporting_assign') }}">
                        <i class="fa fa-search"></i> View Assign Report
                    </a>
                </div>
                @endif
                <div class="card-body">
                    <div id="table_data">
                        @include('main.phone_quote.new.load_approaching')
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
                                        <li class=""><a href="#tab1" class="active" data-toggle="tab">HISTORY/STATUS</a>
                                        </li>
                                        <li><a href="#tab2" data-toggle="tab">VIEW HISTORY</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body tabs-menu-body">
                                <div class="tab-content">
                                    <div class="tab-pane active " id="tab1">
                                        <form method="post" action="{{route('call_history_post_2')}}">
                                            @csrf
                                            <div class="row">
                                                <input type="hidden" name="approaching_user">
                                                <input type="hidden" class="form-control" name="order_id1"
                                                       id='order_id1' placeholder="" readonly>
                                                <input type="hidden" class="form-control" name="approaching"
                                                       id='approaching' value="1">
                                                <div class="col-sm-6 col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">HISTORY</label>
                                                        <textarea required name="history_update"
                                                                  id='history_update'
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="modalClick()">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="orderIdsModal" tabindex="-1" aria-labelledby="orderIdsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderIdsModalLabel">Order IDs</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="modalOrderIdsList"></ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extraScript')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $("body").delegate(".BundleExpand", "click", function () {
            var className = $(this).closest('tr').attr('class');
            var number = parseFloat(className.match(/-*[0-9]+/));
            $(".BundleExpand").toggleClass('fa-chevron-down');
            if ($('.child' + number + ':visible').length)
                $('.child' + number).hide().removeClass("shown");
            else
                $('.child' + number).show().addClass("shown");
        });

        function showprice() {
            if ($('#relist').is(":checked")) {
                $('#row1').hide();
                $('#r1').show();
                $('#r2').show();
                $('#r3').show();
                $(".getcarrier").removeAttr("required");
                $("#r1").attr("required", true);
                $("#relist_id").attr("required", true);
                $("#expected_date").removeAttr("required");
                $("#current_carrier").removeAttr("required");
            } else {
                $('#row1').show();
                $('#r1').hide();
                $('#r2').hide();
                $('#r3').hide();
                $(".getcarrier").attr("required", true);
                $("#r1").removeAttr("required");
                $("#expected_date").attr("required", true);
                $("#current_carrier").attr("required", true);
                $("#relist_id").removeAttr("required");
            }
        }

        $(document).ready(function () {
            $('input').attr('autocomplete', 'onn');
        });
        $('#reportmodal').on('show.bs.modal', function (e) {
            var orderId = $(e.relatedTarget).data('book-id');
            var encryptvuserid = btoa({{Auth::user()->id}});
            var encryptvoderid = btoa(orderId);
            var linkv = "{{url('/email_order/')}}" + '/' + encryptvoderid + '/' + encryptvuserid;
            $(e.currentTarget).find('input[name="orderid"]').val(orderId);
            $(e.currentTarget).find('input[name="link"]').val(linkv);
        });
        $('#trashmodal').on('show.bs.modal', function (e) {
            var orderId = $(e.relatedTarget).data('book-id');
            $(e.currentTarget).find('input[name="orderid"]').val(orderId);
        });
        $('#modalPaid').on('show.bs.modal', function (e) {
            var orderId = $(e.relatedTarget).data('book-id');
            $(e.currentTarget).find('input[name="orderid"]').val(orderId);
            var comments = $(e.relatedTarget).data('comments');
            $(e.currentTarget).find('textarea[name="pay_comments"]').val(comments);
            var paid_status = $(e.relatedTarget).data('paid_status');
            if (paid_status == 0) {
                $("#status0").prop("checked", true);
            } else if (paid_status == 1) {
                $("#status1").prop("checked", true);
            } else if (paid_status == 2) {
                $("#status2").prop("checked", true);
            }
        });
        $("#form").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: "/send_order_link",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    let test = data.toString();
                    let test2 = $.trim(test);
                    let text = "SUCCESS";
                    if (test2 == text) {
                        $('#success').html(data);
                        $('#modaldemo4').modal('show');
                        $('#reportmodal').modal('hide');
                    } else {
                        $('#not_success').html(data);
                        $('#modaldemo5').modal('show');
                    }
                },
                error: function (e) {
                    $("#err").html(e).fadeIn();
                }
            });
        }));

        function modalClick()
        {
            $("#history_update").val('');
        }

        function regain_report_modal() {
            $('#reportmodal').on('show.bs.modal', function (e) {
                var orderId = $(e.relatedTarget).data('book-id');
                var encryptvuserid = btoa({{Auth::user()->id}});
                var encryptvoderid = btoa(orderId);
                var linkv = "{{url('/email_order/')}}" + '/' + encryptvoderid + '/' + encryptvuserid;
                $(e.currentTarget).find('input[name="orderid"]').val(orderId);
                $(e.currentTarget).find('input[name="link"]').val(linkv);
            });
        }

        $('#condition').on('change', function (e) {
            var val = $('#condition').val();
            if (val === '2') {
                $('#vehicle_condition').html(`
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label class="form-label">PICK UP</label>
                            <select name="v_con_p" id="v_con_p" required
                                    class="form-control select2">
                                <option value="1">Folk Lift</option>
                                <option value="2">Man Help</option>
                                <option value="3">Toe</option>
                                <option value="4">Jump Box</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label class="form-label">DELIVERY</label>
                            <select name="v_con_d" id="v_con_d" required
                                    class="form-control select2">
                                <option value="1">Folk Lift</option>
                                <option value="2">Man Help</option>
                                <option value="3">Toe</option>
                                <option value="4">Jump Box</option>
                            </select>
                        </div>
                    </div>
                `);
            } else {
                $('#vehicle_condition').html('');
            }
        });

        $(".compare").click(function () {
            var order_id = $(this).closest('tr').find('.order_id').val();
            var olcation = $(this).closest('tr').find('.location1').val();
            var dlcation = $(this).closest('tr').find('.location2').val();
            $.ajax({
                url: "/get_carrier_by_location",
                type: "GET",
                data: {olcation: olcation, dlcation: dlcation},
                beforeSend: function () {
                    $('#table_data_carrier').html("");
                    $('#table_data_carrier').append(`<div class="lds-hourglass" id='ldss'></div>`);
                },
                success: function (data) {
                    $('#table_data_carrier').html("");
                    $('#table_data_carrier').html(data);
                    if (data == "") {
                        $('#table_data_carrier').append(`<div ><center> <h3 style="margin-top: 15px;">No Data Found</h3> </center></div>`);
                    }
                },
            });
        });
        $(".find_carrier").click(function () {
            var order_id = $(this).closest('tr').find('.order_id').val();
            var originstate = $(this).closest('tr').find('.origincity').val();
            var destinationstate = $(this).closest('tr').find('.destinationcity').val();
            $('#find_o_id').html("Order-Id: " + order_id);
            $.ajax({
                url: "/find_carrier",
                type: "GET",
                data: {originstate: originstate, destinationstate: destinationstate, order_id: order_id},
                beforeSend: function () {
                    $('#table_find_data_carrier').html("");
                    $('#table_find_data_carrier').append(`<div class="lds-hourglass" id='ldss'></div>`);
                },
                success: function (data) {
                    $('#table_find_data_carrier').html("");
                    $('#table_find_data_carrier').html(data);
                },
            });
        });

        function find_select(select_id, order_id) {
            $.ajax({
                url: "/assign_find_carrier",
                type: "GET",
                data: {select_id: select_id, order_id: order_id},
                success: function (data) {
                    $('#find_carrier_modal').modal('hide');
                    not1();
                }
            });
        }

        function regain_status() {
            $(".updatee").click(async function () {
                var order_id = $(this).closest('tr').find('.order_id').val();
                $("#order_id1").attr("value", order_id);
                $('#ask_low').html('');
                var id = $("#order_id1").val();
                await $.ajax({
                    url: "show_call_history",
                    type: "GET",
                    data: {id: id},
                    success: function (data) {
                        if (data.length > 0) {
                            $('#calhistory').html('');
                            $('#calhistory').html(data);
                            setTimeout(function () {
                                $("#calhistory").animate({scrollTop: 20000}, "slow");
                            }, 200);
                        } else {
                            $('#calhistory').html('NO HISTORY FOUND');
                        }
                               if(data){

                                 $('#largemodal').modal('show');
                           }
                        // $('#largemodal').modal('show');
                        // $('#largemodal').trigger('show.bs.modal');

                    }
                });
                var titlee = $('#titlee').val();
                if (titlee == "dispatch") {
                    $(".pickupdatediv").html('');
                    $.ajax({
                        type: "GET",
                        url: "/get_pickup_date",
                        data: {'order_id': order_id},
                        dataType: "json",
                        success: function (data) {
                            $(".pickupdatediv").append(`<div class="form-group"><label class="form-label">PICKUP DATE</label>
                            <input type="date" value="` + data["driver_pickup_date"] + `" required name="pickup_date"
                            id='pickup_date'class="form-control"><input type="checkbox" name="approvalpickup" value="0"/>MARK AS APPROVED</div>`);
                        },
                        error: function (e) {
                            alert("error");
                        }
                    });
                }
                if (titlee == "listed") {
                    $("#current_carrier").empty();
                    var order_id = document.getElementById('order_id1').value;
                    var options = "<option selected value=''>Select Carrier</option>";
                    $.ajax({
                        type: "GET",
                        url: "/get_carrier",
                        data: {'order_id': order_id},
                        dataType: "json",
                        success: function (data) {
                            $.each(data, function (i, item) {
                                if (item.id) {
                                    options = options + `<option value='` + item.id + `'>` + item.companyname + `</option>`;
                                }
                            });
                            $("#current_carrier").append(options);
                        },
                        error: function (e) {
                            alert("error");
                        }
                    });
                }

                if (titlee == "picked_up") {
                    $(".pickupdatediv2").html('');
                    $(".deliverdate").html('');
                    $.ajax({
                        type: "GET",
                        url: "/get_pickup_date",
                        data: {'order_id': order_id},
                        dataType: "json",
                        success: function (data) {
                            $(".pickupdatediv2").append(`<div class="form-group"><label class="form-label">PICKUP DATE</label>
                            <input readonly type="text" value="` + data["driver_pickup_date"] + `"  name="pickup_date1"
                            id='pickup_date1'class="form-control"></div>`);
                            $(".deliverdate").append(`<div class="form-group"><label class="form-label">DELIVER DATE</label>
                            <input required  type="date" value="` + data["driver_deliver_date"] + `"  name="deliver_date"
                            id='deliver_date'class="form-control"></br>
                            <input type="checkbox" name="approvaldeliver" value="1"/> &nbsp; Mark AS APPROVED (DELIVER)</div>`);
                        },
                        error: function (e) {
                            alert("error");
                        }
                    });
                }
            });
        }

        $(".updatee").click(async function () {
            var order_id = $(this).closest('tr').find('.order_id').val();
            $("#order_id1").attr("value", order_id);
            $('#ask_low').html('');
            var id = $("#order_id1").val();
            if (!id) {
                id = order_id;
            }
           await $.ajax({
                url: "show_call_history",
                type: "GET",
                data: {id: id},
                success: function (data) {
                    if (data.length > 0) {
                        $('#calhistory').html('');
                        $('#calhistory').html(data);
                        setTimeout(function () {
                            $("#calhistory").animate({scrollTop: 20000}, "slow");

                        }, 200);
                    } else {
                        $('#calhistory').html('NO HISTORY FOUND');
                    }
                    // $('#largemodal').modal('show');
                    //  $('#largemodal').modal('show');
                 if(data){

                                 $('#largemodal').modal('show');
                           }

                }
            });
            var titlee = $('#titlee').val();
            if (titlee == "dispatch") {
                $(".pickupdatediv").html('');
                $.ajax({
                    type: "GET",
                    url: "/get_pickup_date",
                    data: {'order_id': order_id},
                    dataType: "json",
                    success: function (data) {
                        $(".pickupdatediv").append(`<div class="form-group"><label class="form-label">PICKUP DATE</label>
                            <input type="date" value="` + data["driver_pickup_date"] + `" required name="pickup_date"
                            id='pickup_date'class="form-control"><input type="checkbox" name="approvalpickup" value="0"/>MARK AS APPROVED</div>`);
                    },
                    error: function (e) {
                        alert("error");
                    }
                });
            }

            if (titlee == "listed") {
                $("#current_carrier").empty();
                var order_id = document.getElementById('order_id1').value;
                var options = "<option selected value=''>Select Carrier</option>";
                $.ajax({
                    type: "GET",
                    url: "/get_carrier",
                    data: {'order_id': order_id},
                    dataType: "json",
                    success: function (data) {
                        $.each(data, function (i, item) {
                            if (item.id) {
                                options = options + `<option value='` + item.id + `'>` + item.companyname + `</option>`;
                            }
                        });
                        $("#current_carrier").append(options);
                    },
                    error: function (e) {
                        alert("error");
                    }
                });
            }
            if (titlee == "picked_up") {
                $(".pickupdatediv2").html('');
                $(".deliverdate").html('');
                $.ajax({
                    type: "GET",
                    url: "/get_pickup_date",
                    data: {'order_id': order_id},
                    dataType: "json",
                    success: function (data) {
                        $(".pickupdatediv2").append(`<div class="form-group"><label class="form-label">PICKUP DATE</label>
                            <input readonly type="text" value="` + data["driver_pickup_date"] + `"  name="pickup_date1"
                            id='pickup_date1'class="form-control"></div>`);

                        $(".deliverdate").append(`<div class="form-group"><label class="form-label">DELIVER DATE</label>
                            <input required  type="date" value="` + data["driver_deliver_date"] + `"  name="deliver_date"
                            id='deliver_date'class="form-control"></br>
                            <input type="checkbox" name="approvaldeliver" value="1"/> &nbsp; Mark AS APPROVED (DELIVER)</div>`);
                    },
                    error: function (e) {
                        alert("error");
                    }
                });
            }
        });

        $("body").delegate("#keywords", "click", function () {
            setTimeout(function () {
                $('input[name="keywords"]').focus()
            }, 100);
        });
        $("body").delegate("#search_by", "change", function () {
            var search_by = $('#search_by').val();
            if (search_by == "ophone") {
                $("#keywords").mask("(999) 999-9999");
                setTimeout(function () {
                    $('input[name="keywords"]').focus()
                }, 100);
            } else {
                $("#keywords").unmask();
                setTimeout(function () {
                    $('input[name="keywords"]').focus()
                }, 100);
            }
        });
        $("body").delegate("#pstatus", "change", function () {
            var p_status = $('#pstatus').val();
            if (p_status == 3) {
                $('#ask_low').html(`
                    <div class="form-group">
                        <label class="form-label">Asking Low Price</label>
                        <input required type="number" min="0" step="0.01" name="asking_low"
                                  id='asking_low' class="form-control">
                    </div>`)
            }
        });
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
                // $('#date_range').val(start + ' - ' + end);
                $('#date_range').val('');
            });
            $('#date_range').val('');
        });
        $(function () {
            var date = new Date();
            $('#date_range2').daterangepicker({
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
                // $('#date_range2').val(start + ' - ' + end);
                $('#date_range2').val('');
            });
            $('#date_range2').val('');
        });

        $('#date_range2').on('apply.daterangepicker', function (ev, picker) {
            return_data(1);
        });

        function return_data(page,recordsAllowed = null,send_template = null,template = null,limit_data = null) {
            var order_taker_id = $('#order_taker_id').val();
            var date_range = $('#date_range2').val();
            var titlee = $('#titlee').val();
            var userName = $('.userName').val();
            var statuss = $('#status').val();
            var zip = $('.zip').val();
            var delivery = $('.delivery').val();
            var call_type = $('.call_type').val();
            var oterminal = $('#oterminal').val();
            var emailComp = $('#emailComp').val();
            var search_as = $('#search_as').val();
            if(!search_as){
                search_as = 2;
            }

            $('#table_data').html('');
            $('#table_data').append(`<div class="lds-hourglass" id='ldss'></div>`);
            $.ajax({
                url: "{{ url('fetch_data') }}?page="+page,
                type: "GET",
                data: {order_taker_id: order_taker_id, date_range: date_range, titlee: titlee, statuss: statuss,recordsAllowed: recordsAllowed,userName:userName,zip:zip,delivery:delivery,call_type:call_type,oterminal:oterminal,emailComp:emailComp,search_as:search_as,
                send_template : send_template,template,limit_data
                },
                success: function (data) {
                    $('#table_data').html('');
                    $('#table_data').html(data);
                },
                complete: function (data) {
                    $('#ldss').hide();
                }
            });
        }

        function regain_call() {
            $(".count_user").click(function () {
                var order_id = $(this).closest('tr').find('.order_id').val();
                var pstatus = $(this).closest('tr').find('.pstatus').val();
                var client_email = $(this).closest('tr').find('.client_email').val();
                var client_name = $(this).closest('tr').find('.client_name').val();
                var client_phone = $(this).closest('tr').find('.client_phone').val();
                var data = {order_id: order_id, pstatus: pstatus, client_email: client_email, client_name: client_name};
                $.ajax({
                    type: "GET",
                    url: '/count_user',
                    dataType: "json",
                    data: data,
                    success: function (response) {
                        if (response) {
                            window.location.href = "rcmobile://call?number=" + client_phone;
                        }
                    }
                });
            });
        }

        $(document).ready(function () {
            $(".count_user").click(function () {
                var order_id = $(this).closest('tr').find('.order_id').val();
                var pstatus = $(this).closest('tr').find('.pstatus').val();
                var client_email = $(this).closest('tr').find('.client_email').val();
                var client_name = $(this).closest('tr').find('.client_name').val();
                var client_phone = $(this).closest('tr').find('.client_phone').val();
                var data = {order_id: order_id, pstatus: pstatus, client_email: client_email, client_name: client_name};
                $.ajax({
                    type: "GET",
                    url: '/count_user',
                    dataType: "json",
                    data: data,
                    success: function (response) {
                        if (response) {
                            window.location.href = "rcmobile://call?number=" + client_phone;
                        }
                    }
                });
            });

            $(document).on('click', '.pagination a', function (event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                return_data(page);
                $.cookie("page", page, { expires: 1 });
            });

        });

        $('#send_template').on('click', function(event) {
            var template = $('#template').val();
            var limit_data = $('#limit_data').val();
            return_data(1,null,1,template,limit_data);
            alert('Email Will be Released after 5 minutes');
        });
        regain_call();
        regain_status();
        regain_report_modal();

        function listedUpload(id) {
            let oid = $('#orderId_' + id).val();
            let paid = $('#paid_' + id).val();
            let storage = $('#storage_' + id).val();
            let listed_price = $('#listed_price_' + id).val();
            let auction_update = $('#auction_update_' + id).val();
            let title_keys = $('#title_keys_' + id).val();
            let listed_count = $('#listed_count_' + id).val();
            let price = $('#price_' + id).val();
            let additional = $('#additional_' + id).val();

            $.ajax({
                url: window.location.origin + "/listed_sheet/" + oid, // Url of backend (can be python, php, etc..)
                type: "GET", // data type (can be get, post, put, delete)
                data: {
                    paid: paid,
                    storage: storage,
                    listed_price: listed_price,
                    auction_update: auction_update,
                    title_keys: title_keys,
                    listed_count: listed_count,
                    price: price,
                    additional: additional
                }, // data in json format
                async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
                success: function (response) {
                    if (response === 'true') {
                        Swal.fire(
                            'Success!',
                            'Listed Sheet Updated!',
                            'success'
                        )
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                }
            });
        }

        function dispatchUpload(id) {
            let oid = $('#orderId_' + id).val();
            let pickedup = $('#pickedup_' + id).val();
            let condition = $('#condition_' + id).val();
            let title_keys = $('#title_keys_' + id).val();
            let storage = $('#storage_' + id).val();
            let auction_update = $('#auction_update_' + id).val();
            let who_pay_storage = $('#who_pay_storage_' + id).val();
            let vehicle_position = $('#vehicle_position_' + id).val();
            let additional = $('#additional_' + id).val();

            $.ajax({
                url: window.location.origin + "/dispatch_sheet/" + oid, // Url of backend (can be python, php, etc..)
                type: "GET", // data type (can be get, post, put, delete)
                data: {
                    pickup_date: pickedup,
                    vehicle_condition: condition,
                    title_keys: title_keys,
                    storage: storage,
                    auction_update: auction_update,
                    who_pay_storage: who_pay_storage,
                    vehicle_position: vehicle_position,
                    additional: additional
                }, // data in json format
                async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
                success: function (response) {
                    if (response === 'true') {
                        Swal.fire(
                            'Success!',
                            'Dispatch Sheet Updated!',
                            'success'
                        )
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                }
            });
        }

        function pickedUpUpload(id) {
            let oid = $('#orderId_' + id).val();
            let auction_status = $('#auction_status_' + id).val();
            let driver_status = $('#driver_status_' + id).val();
            let delivery_date = $('#delivery_date_' + id).val();
            let storage = $('#storage_' + id).val();
            let condition = $('#condition_' + id).val();
            let title_keys = $('#title_keys_' + id).val();
            let vehicle_position = $('#vehicle_position_' + id).val();
            let payment = $('#payment_' + id).val();
            let additional = $('#additional_' + id).val();

            $.ajax({
                url: window.location.origin + "/pickedup_sheet/" + oid, // Url of backend (can be python, php, etc..)
                type: "GET", // data type (can be get, post, put, delete)
                data: {
                    auction_status: auction_status,
                    delivery_date: delivery_date,
                    driver_status: driver_status,
                    storage: storage,
                    vehicle_condition: condition,
                    title_keys: title_keys,
                    vehicle_position: vehicle_position,
                    payment: payment,
                    additional: additional
                }, // data in json format
                async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
                success: function (response) {
                    if (response === 'true') {
                        Swal.fire(
                            'Success!',
                            'Picked Up Sheet Updated!',
                            'success'
                        )
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                }
            });
        }

        function deliveryUpload(id) {
            let oid = $('#orderId_' + id).val();
            let driver_no = $('#driver_no_' + id).val();
            let vehicle_position = $('#vehicle_position_' + id).val();
            let who_pay_storage = $('#who_pay_storage_' + id).val();
            let title_keys = $('#title_keys_' + id).val();
            let delivery_date = $('#delivery_date_' + id).val();
            let client_status = $('#client_status_' + id).val();
            let driver_status = $('#driver_status_' + id).val();
            let owes_status = $('#owes_status_' + id).val();
            let additional = $('#additional_' + id).val();

            $.ajax({
                url: window.location.origin + "/delivery_sheet/" + oid, // Url of backend (can be python, php, etc..)
                type: "GET", // data type (can be get, post, put, delete)
                data: {
                    driver_no: driver_no,
                    vehicle_position: vehicle_position,
                    delivery_date: delivery_date,
                    who_pay_storage: who_pay_storage,
                    client_status: client_status,
                    title_keys: title_keys,
                    driver_status: driver_status,
                    owes_status: owes_status,
                    additional: additional
                }, // data in json format
                async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
                success: function (response) {
                    if (response === 'true') {
                        Swal.fire(
                            'Success!',
                            'Delivery Sheet Updated!',
                            'success'
                        )
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                }
            });
        }

        function completedUpload(id) {
            let oid = $('#orderId_' + id).val();
            let remarks = $('#remarks_' + id).val();
            let comments = $('#comments_' + id).val();
            let satisfied = $('#satisfied_' + id).val();
            let client_rating = $('#client_rating_' + id).val();
            let additional = $('#additional_' + id).val();

            $.ajax({
                url: window.location.origin + "/completed_sheet/" + oid, // Url of backend (can be python, php, etc..)
                type: "GET", // data type (can be get, post, put, delete)
                data: {
                    remarks: remarks,
                    comments: comments,
                    satisfied: satisfied,
                    client_rating: client_rating,
                    additional: additional
                }, // data in json format
                async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
                success: function (response) {
                    if (response === 'true') {
                        Swal.fire(
                            'Success!',
                            'Completed Sheet Updated!',
                            'success'
                        )
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                }
            });
        }

        $(document).on('click', '.open-email-modal', function () {
            var email = $(this).data('email');
            $('#user-email').val(email);
            $('#emailModal').modal('show');

            // Fetch Email Templates and populate the dropdown
            $.ajax({
                url: "{{ route('get.email.templates') }}", // Route to fetch email templates
                type: "GET",
                success: function (response) {
                    var templateDropdown = $('#email-template');
                    templateDropdown.empty();

                    $.each(response.templates, function (index, template) {
                        templateDropdown.append('<option value="' + template.id + '">' + template.title + '</option>');
                    });
                }
            });
        });

        $(document).on('click', '.send-email', function () {
            var email = $('#user-email').val();
            var templateId = $('#email-template').val();

            if (!templateId) {
                alert('Please select an email template.');
                return;
            }

            $.ajax({
                url: "{{ route('send.user.mail2') }}",
                type: "GET",
                data: {
                    email: email,
                    template_id: templateId
                },
                success: function (response) {
                    alert(response.message || 'Email sent successfully!');
                    $('#emailModal').modal('hide');
                },
                error: function (xhr) {
                    alert(xhr.responseJSON?.error || 'An error occurred while sending the email.');
                }
            });
        });

        $(document).on('click', '.view_email_history', function(event) {
            event.preventDefault();
            var email = $(this).find('.History-Email-Address').val();

            $.ajax({
                url: '{{ route('get.email.history') }}',
                type: 'GET',
                data: {
                    'email': email,
                },
                success: function(data) {
                    console.log('datas', data);
                    $(".email-history-content").html('');
                    html = "";
                    $.each(data, function(index, val) {
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

                        // Check if val['template'] exists and has a title
                        var templateTitle = val['template'] && val['template']['title']
                            ? val['template']['title']
                            : "No title available";

                        // Safely build HTML
                        html += "<h6>Sender " + (val['user'] && val['user']['name'] ? val['user']['name'] : "Unknown") + "</h6>";
                        html += "<h6>Template title: " + templateTitle + "</h6>";
                        html += "<strong class='mf-date'><i class='fa fa-clock-o'></i>" +
                            formattedDate + "</strong> <hr>";
                    });
                    $(".email-history-content").html(html);
                },

                error: function(error) {
                    console.error('Error submitting the form:', error);
                }
            });
        });
    </script>
    <!--Scrolling Modal-->
@endsection


