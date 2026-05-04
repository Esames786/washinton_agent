@extends('layouts.innerpages')
@section('template_title')
    Employee Reports
@endsection
@section('content')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <style>
        .col-sm-3 .card
        {
            transition: all .2s;
            cursor:pointer;
        }
        .col-sm-3 .card:hover{
            box-shadow: 0 0px 30px 0 rgb(35 43 54 / 15%);
            scale: 1.02;
        }
        .col-sm-3 .card .card-header
        {
            font-weight:700;
        }
        .col-sm-3 .card .card-header span{
            font-size: 11px;
            color:#fff;
        }
        /* Style the tab */
        .table-responsive{
            overflow:unset !important;
        }
        
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
        
        .dropdown-menu{
            left:-6rem !important;
        }
        
        .bg-yellow
        {
            background-color:#c3c300 !important;
        }
        
        .bg-orange
        {
            background-color:#F49917 !important;
        }
        .bg-pink {
            background: #E91E63 !important;
        }
        .bg-amber {
            background: #FF6F00 !important;
        }
        .bg-teal {
            background: #004D40 !important;
        }

        /* Go from zero to full opacity */
        @keyframes fadeEffect {
            from {opacity: 0;}
            to {opacity: 1;}
        }
    </style>
    <!-- Row -->
    @include('partials.mainsite_pages.return_function')
    <div class="row">
        <div class="col-12">
            @if(session('flash_message'))
                <div class="alert alert-success">
                    {{session('flash_message')}}
                </div>
            @endif
            <!--div-->
            <div class="page-header">
                <div class="text-secondary text-center text-uppercase w-100">
                    <h1 class="my-4"><b>Employee Reports</b></h1>
                </div>
            </div>
            <div class="card mt-5">
                <div class="card-header d-block">
                    <div class="row">
                        <div class="col-sm-3 my-auto">
                            <label class="form-label">Search By</label>
                            <select class="form-control select2" id="search_by">
                                <option value="created_at">Move Created Date</option>
                                <option value="updated_at">Move Modified Date</option>
                            </select>
                        </div>
                        <div class="col-sm-4 my-auto">
                            <label class="form-label">Daterange <button type="button" class="btn btn-info btn-sm" onclick="$('#date_range').val('')" style="padding: 3.2px 10px;">Clear</button></label>
                            <div class='input-group date' id='datetimepicker1'>
                                <input type='text' name="date_range"  id="date_range" class="form-control" />
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
                        <!--<div class="col-sm-2">-->
                        <!--    <label class="form-label">Year</label>-->
                        <!--    <select class="form-control select2" id="year">-->
                        <!--        <option value="" selected>All Years</option>-->
                        <!--        @for($i=date('Y'); $i>=2015; $i--)-->
                        <!--            <option value="{{$i}}" @if(date('Y') == $i) selected @endif>{{$i}}</option>-->
                        <!--        @endfor-->
                        <!--    </select>-->
                        <!--</div>-->
                        <!--<div class="col-sm-2">-->
                        <!--    <label class="form-label">Month</label>-->
                        <!--    <select class="form-control select2" id="month">-->
                        <!--        <option value="" selected>All Months</option>-->
                        <!--        <option value="01" @if(date('m') == 1) selected @endif>Jan</option>-->
                        <!--        <option value="02" @if(date('m') == 2) selected @endif>Feb</option>-->
                        <!--        <option value="03" @if(date('m') == 3) selected @endif>Mar</option>-->
                        <!--        <option value="04" @if(date('m') == 4) selected @endif>Apr</option>-->
                        <!--        <option value="05" @if(date('m') == 5) selected @endif>May</option>-->
                        <!--        <option value="06" @if(date('m') == 6) selected @endif>Jun</option>-->
                        <!--        <option value="07" @if(date('m') == 7) selected @endif>Jul</option>-->
                        <!--        <option value="08" @if(date('m') == 8) selected @endif>Aug</option>-->
                        <!--        <option value="09" @if(date('m') == 9) selected @endif>Sep</option>-->
                        <!--        <option value="10" @if(date('m') == 10) selected @endif>Oct</option>-->
                        <!--        <option value="11" @if(date('m') == 11) selected @endif>Nov</option>-->
                        <!--        <option value="12" @if(date('m') == 12) selected @endif>Dec</option>-->
                        <!--    </select>-->
                        <!--</div>-->
                        <!--<div class="col-sm-2">-->
                        <!--    <label class="form-label">Date</label>-->
                        <!--    <select class="form-control select2" id="date">-->
                        <!--        <option value="" selected>All Dates</option>-->
                        <!--        @for($i=1; $i<=31; $i++)-->
                        <!--            <option value="{{$i}}">{{$i}}</option>-->
                        <!--        @endfor-->
                        <!--    </select>-->
                        <!--</div>-->
                        <div class="col-sm-3 my-auto">
                            <label class="form-label">Deparment Employees</label>
                            <select class="form-control select2" id="user">
                                <option value="" selected>All</option>
                                @foreach($users as $key => $val)
                                    <option value="{{$val->id}}" style="text-transform:capitalize;">{{$val->name}} ({{$val->userRole->name}})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-1 mt-auto mb-1">
                            <button class="btn btn-primary" id="submit">Search</button>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="pstatus" value="" />
                <div class="card-body" id="completeData">
                    <?php 
                        $emp_report = explode(',',Auth::user()->emp_access_report);
                    ?>
                    <div class="row" id="allData">
                        @if(in_array("0",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="0">
                                    New <span class="rounded badge badge-success ml-2"><span>{{$new}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("1",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="1">
                                    Interested <span class="rounded badge badge-success ml-2"><span>{{$int}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("2",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="2">
                                    Follow More <span class="rounded badge badge-success ml-2"><span>{{$fm}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("3",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="3">
                                    Asking Low <span class="rounded badge badge-success ml-2"><span>{{$al}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("4",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="4">
                                    Not Interested <span class="rounded badge badge-success ml-2"><span>{{$not_int}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("5",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="5">
                                    No Response <span class="rounded badge badge-success ml-2"><span>{{$nr}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("6",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="6">
                                    Time Quote <span class="rounded badge badge-success ml-2"><span>{{$tq}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("7",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="7">
                                    Payment Missing <span class="rounded badge badge-success ml-2"><span>{{$pm}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("18",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="18">
                                    OnApproval <span class="rounded badge badge-success ml-2"><span>{{$oa}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("8",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="8">
                                    Booked <span class="rounded badge badge-success ml-2"><span>{{$book}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("9",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="9">
                                    Listed <span class="rounded badge badge-success ml-2"><span>{{$list}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("10",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="10">
                                    Schedule <span class="rounded badge badge-success ml-2"><span>{{$dis}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("34",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="34">
                                    Schedule To Another Driver <span class="rounded badge badge-success ml-2"><span>{{$dis_app}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("30",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="30">
                                    Pickup Approval <span class="rounded badge badge-success ml-2"><span>{{$pick_app}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("11",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="11">
                                    Pickup <span class="rounded badge badge-success ml-2"><span>{{$pick}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("31",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="31">
                                    Delivery Approval <span class="rounded badge badge-success ml-2"><span>{{$del_app}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("32",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="32">
                                   Schedule For Delivery <span class="rounded badge badge-success ml-2"><span>{{$sfd}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("12",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="12">
                                    Delivered <span class="rounded badge badge-success ml-2"><span>{{$del}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("13",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="13">
                                    Completed <span class="rounded badge badge-success ml-2"><span>{{$com}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("14",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="14">
                                    Cancel <span class="rounded badge badge-success ml-2"><span>{{$can}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("19",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="19">
                                    OnApprovalCancel <span class="rounded badge badge-success ml-2"><span>{{$opcan}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("20",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="20">
                                    Relist <span class="rounded badge badge-success ml-2"><span>{{$rl}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("21",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="21">
                                    Price Raise <span class="rounded badge badge-success ml-2"><span>{{$pr}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("22",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="22">
                                    Approach Id <span class="rounded badge badge-success ml-2"><span>{{$ai}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("23",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="23">
                                    Different Port <span class="rounded badge badge-success ml-2"><span>{{$dp}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("24",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="24">
                                    Carrier Update <span class="rounded badge badge-success ml-2"><span>{{$cu}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("25",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="25">
                                    Storage <span class="rounded badge badge-success ml-2"><span>{{$store}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("26",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="26">
                                    Approaching <span class="rounded badge badge-success ml-2"><span>{{$app}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("27",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="27">
                                    Auction Update Request <span class="rounded badge badge-success ml-2"><span>{{$aur}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("28",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="28">
                                    Move To Storage <span class="rounded badge badge-success ml-2"><span>{{$move_to_storage}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("29",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="29">
                                    Double Booking <span class="rounded badge badge-success ml-2"><span>{{$db}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("33",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="33">
                                    Auction Update <span class="rounded badge badge-success ml-2"><span>{{$auction_update}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(in_array("35",$emp_report))
                        <div class="col-sm-3">
                            <div class="card bg-white mb-3">
                                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="35">
                                    Auction Storage <span class="rounded badge badge-success ml-2"><span>{{$auction_storage}}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="row" id="filters" style="display:none;">
                        <div class="col-sm-2" style="display:none;" id="auction_storage">
                            <label class="form-label">Auction Storage</label>
                            <select class="form-control select2" id="auc_storage">
                                <option value="0" selected>ALL</option>
                                <option value="1">Already Storage</option>
                                <option value="2">Late Pickup Storage</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label for="searchBy" class="form-label">Search By</label>
                            <select class="form-control" id="searchBy">
                                <option value="id">Order Id</option>
                                <option value="originzsc">Pickup</option>
                                <option value="destinationzsc">Delivery</option>
                                <option value="ymk">Vehicle Name</option>
                                <option value="dauction">Port</option>
                                <option value="ophone">Phone Number</option>
                                <option value="oauction">Pickup Auction</option>
                                <option value="dauction">Delivery Auction</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label for="search" class="form-label">Search Value</label>
                            <input type="text" id="search" class="form-control" placeholder="Search Value" />
                        </div>
                        <div class="col-sm-3">
                            <label for="source" class="form-label">Source</label>
                            <select class="form-control" id="source">
                                <option value="">All</option>
                                <option value="DayDispatch">DD</option>
                                <option value="ShipA1">ShipA1</option>
                            </select>
                        </div>
                        <div class="col-sm-1 mt-auto">
                            <button class="btn btn-warning" id="searchValues">Search</button>
                        </div>
                    </div>
                    <div class="row" id="tableData">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Row -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width:85%;">
            <div class="modal-content">
                <div class="d-flex justify-content-between mt-5">
                    <h5 class="text-center my-auto ml-4" id="order_detail_status">Order Status</h5>
                    <h5 class="text-center my-auto" id="order_detail_title">Order Detail</h5>
                    <button type="button" class="close bg-danger text-white mr-4" data-dismiss="modal" aria-label="Close" style="height: 25px;width: 25px;border-radius: 50%;">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="detail_order">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extraScript')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        function searchData(page)
        {
            var user = $("#user").children('option:selected').val();
            var search_by2 = $("#search_by").children('option:selected').val();
            var date_range = $("#date_range").val();
            // var year = $("#year").children('option:selected').val();
            // var month = $("#month").children('option:selected').val();
            // var date = $("#date").children('option:selected').val();
            $("#pstatus").val('');
            var pstatus = '';
            // console.log(date_range);
            $.ajax({
                url:"{{url('/reports/get')}}?page="+page,
                type:"GET",
                data:{date_range:date_range,user:user,pstatus:pstatus,search_by2:search_by2},
                beforeSend: function () {
                    $('#completeData').html("");
                    $('#completeData').append(`<div class="lds-hourglass" id='ldss'></div>`);
                },
                success:function(res)
                {
                    $("#completeData").html("");
                    $("#completeData").html(res);
                }
            });
        }
        $(document).on("change", "#searchBy", "#source",function(){
            if($(this).val() == "dauction")
            {
                $("#search").val('Port');
                $("#search").attr("readonly",true);
            }
            else if ($(this).val() == "ophone") {

                $('#search').val('');
                $("#search").attr("readonly",false);
                $("#search").mask("(999) 999-9999");

                setTimeout(function () {
                    $('input[name="search"]').focus()
                }, 100);

            } 
            else
            {
                $("#search").val('');
                $("#search").attr("readonly",false);
            }
        })
        function searchData2(page)
        {
            var user = $("#user").children('option:selected').val();
            var search_by2 = $("#search_by").children('option:selected').val();
            var date_range = $("#date_range").val();
            // var year = $("#year").children('option:selected').val();
            // var month = $("#month").children('option:selected').val();
            // var date = $("#date").children('option:selected').val();
            var pstatus = $("#pstatus").val();
            var search_by = $("#searchBy").children('option:selected').val();
            var source = $("#source").children('option:selected').val();
            var search = $("#search").val();
            var auc_storage = $("#auc_storage").children('option:selected').val();
            // console.log(date_range);
            $.ajax({
                url:"{{url('/reports/get2')}}?page="+page,
                type:"GET",
                data:{date_range:date_range,user:user,pstatus:pstatus,search_by:search_by,search:search,auc_storage:auc_storage,search_by2:search_by2, source:source},
                beforeSend: function () {
                    $('#tableData').html("");
                    $('#tableData').append(`<div class="lds-hourglass" id='ldss'></div>`);
                },
                success:function(res)
                {
                    $("#tableData").html("");
                    $("#filters").show();
                    $("#tableData").html(res);
                }
            });
        }
        $("#submit").click(function(){
            searchData(1);
        })
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
                // "startDate": new Date(date.getFullYear(), date.getMonth(), 1),
                // "endDate": new Date(date.getFullYear(), date.getMonth() + 1, 0),
                "opens": "center",
                "drops": "auto"
            }, function (start, end, label) {
                // console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
                $('#date_range').val("{{$from->format('m/d/Y')}} - {{$to->format('m/d/Y')}}");
            });
            $('#date_range').val("{{$from->format('m/d/Y')}} - {{$to->format('m/d/Y')}}");
        });

        $("body").delegate(".cancelBtn", "click", function(){
            $('#date_range').val('');
        });
        
        
        $(document).on('click', '.pagination a', function (event) {

            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            searchData2(page);
        });
        
        $(document).on('click','.showQuotes',function(){
            $(".showQuotes").removeClass('bg-dark');
            $(".showQuotes").removeClass('text-light');
            $(this).addClass('bg-dark');
            $(this).addClass('text-light');
            $("#pstatus").val($(this).attr('data-value'));
            searchData2(1);
            if($(this).attr('data-value') == "35")
            {
                $("#auction_storage").show();
            }
            else
            {
                $("#auction_storage").hide();
            }
        })
        
        $(document).on('click','#searchValues',function(){
            searchData2(1);
        })
    </script>
@endsection

