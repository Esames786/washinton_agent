@extends('layouts.innerpages')

@section('template_title')
    {{  ucfirst(str_replace('_',' ',Request::segment(1)))}}
@endsection

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha384-..." crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <style>
        .selected {
            background: lightgray;
            border-radius: 10px;
        }
        .message-feed.right .mf-content
        {
            background: #705ec8;
        }
        .message-feed.right .mf-content:before {
            border-bottom: 8px solid #705ec8;
        }
        
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
        select.form-control:not([size]):not([multiple]) {
            height: 2.375rem !important;
        }
        
        
        
        
           #overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
        z-index: 2;
        }
    
       #errorIcon {
        /*font-size: 14px;*/
        color: #009eda!important;
        cursor: pointer;
      }
      .popoverContent {
        /* display: none; */
        position: absolute;
        background-color: #fff;
        border: 1px solid #ccc;
        padding: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        z-index: 3;
        right: 150px;
      }

    .Terminal-error {
        display: inline-flex;
        column-gap: 6px;
        align-items: baseline;
    }

    label#selectedOptionLabel2 {
        display: block;
    }

        /*.table {*/
        /*    !*color: rgb(0 0 0);*!*/
        /*    width: 100%;*/
        /*    max-width: 100%;*/
        /*    margin-bottom: 1rem;*/
        /*}*/

        /*.table-bordered, .text-wrap table, .table-bordered th, .text-wrap table th, .table-bordered td, .text-wrap table td {*/
        /*    border: 1px solid rgb(0 0 0);*/
        /*}*/

        /*.table > thead > tr > td, .table > thead > tr > th {*/
        /*    font-weight: 400;*/
        /*    -webkit-transition: all .3s ease;*/
        /*    font-size: 18px;*/
        /*    color: rgb(0 0 0);*/
        /*}*/
    </style>
    <div class="page-header">
        <div class="text-secondary text-center text-uppercase w-100">
              @if($label[381-1]->status == 1)
                      <div class="Terminal-error">
            <h1 class="my-4"><b>{{str_replace('_',' ',\Request::segment(1)) == 'dispatch' ? 'Schedule' : str_replace('_',' ',\Request::segment(1))}} Orders</b></h1>
                    <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                    style="cursor: pointer;"></i>
                    </div>
                    <div class="popoverContent" style="display: none;">
                         <div class="popover-title">{{ $label[381-1]->name }}</div>
                         <div class="popover-content">{{ $label[381-1]->display }}</div>
                    </div>
                  
                    @else
                        <h1 class="my-4"><b>{{str_replace('_',' ',\Request::segment(1)) == 'dispatch' ? 'Schedule' : str_replace('_',' ',\Request::segment(1))}} Orders</b></h1>
                    @endif
        </div>
    <!--    <div class="page-leftheader">-->
    <!--        {{--<h4 class="page-title mb-0">{{  ucfirst(trim("$_SERVER[REQUEST_URI]",'/'))}}</h4>--}}-->
            <input type="hidden" value="{{trim("$_SERVER[REQUEST_URI]",'/')}}" id="titlee">
    <!--        <ol class="breadcrumb">-->
    <!--            <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Home</a>-->
    <!--            </li>-->
    <!--            <li class="breadcrumb-item active" aria-current="page"><a href="#">New Order</a></li>-->
    <!--        </ol>-->
    <!--    </div>-->
    <!--    {{--<div class="page-rightheader">--}}-->
    <!--    {{--<div class="btn btn-list">--}}-->
    <!--    {{--<a href="#" class="btn btn-info"><i class="fe fe-settings mr-1"></i> Port Sheet Update</a>--}}-->
    <!--    {{--<a href="#" class="btn btn-danger"><i class="fe fe-printer mr-1"></i> Print </a>--}}-->
    <!--    {{--<a href="#" class="btn btn-warning"><i class="fe fe-shopping-cart mr-1"></i> Buy Now </a>--}}-->
    <!--    {{--</div>--}}-->
    <!--    {{--</div>--}}-->
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
                        <form  id="search_form" onsubmit="return false">
                            <div class="col-lg-12 p-0">
                                <div class="row">
                                    <!--
                                   <div class="col-lg-4 text-center pd-10">

                                       <div class='input-group date' id='datetimepicker1'>
                                           <input class="form-control fc-datepicker" placeholder="MM/DD/YYYY"
                                                  type="text">
                                           <span class="input-group-addon">
                                               <span class="glyphicon glyphicon-calendar"></span>
                                           </span>
                                       </div>

                                    </div>
                                    -->
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 text-left pd-10">
                                                        <label style="float: left">Sort By</label>
                                                        <select id="sort_by" name="sort_by" style="height: 35px;" class="form-control">
                                                            <option value="created_at" selected>Created at</option>
                                                            <option value="updated_at">Updated at</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-6 text-center pd-10">
                                                        <label style="float: left">Daterange <button type="button" class="btn btn-info btn-sm" onclick="$('#date_range').val('')" style="padding: 3.2px 10px;">Clear</button></label>
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
                                                    </div>
                                                    <div class="col-lg-3 text-left pd-10">
                                                        <label style="float: left">Search By</label>
                                                        <select id="search_by" name="search_by" class="form-control"
                                                                data-placeholder="50">
                                                            <option value="id">Order ID</option>
                                                            <option value="ymk">Vehicle Name</option>
                                                            <option value="origincity">Pickup City</option>
                                                            <option value="originstate">Pickup State</option>
                                                            <option value="destinationcity">Delivery City</option>
                                                            <option value="destinationstate">Delivery State</option>
                                                            <option value="driverphoneno">Driver Phone</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="row">
                                                    <div class="col-lg-4 text-left pd-10">
                                                        <label style="float: left">Method</label>
                                                        <select id="vehicle" name="vehicle" class="form-control"
                                                                data-placeholder="50">
                                                            <option value="">Select</option>
                                                            <option value="quick_pay">Quick Pay</option>
                                                            <option value="cod">COD</option>
                                                            <option value="cop">COP</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-4 text-left pd-10">
                                                        <label style="float: left">Owes</label>
                                                        <select id="owes" name="owes" class="form-control"
                                                                data-placeholder="50">
                                                            <option value="">Select</option>
                                                            <option value="1">Owes Only</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-4 text-left pd-10">
                                                        <label style="float: left">Who Pay</label>
                                                        <select id="we_us_driver" name="we_us_driver" class="form-control"
                                                                data-placeholder="50">
                                                            <option value="">Select</option>
                                                            <option value="1">Driver To Us</option>
                                                            <option value="2">We To Driver</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 text-center pd-10 pr-0">
                                                <label style="float: left">Search For</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control"
                                                           placeholder="Search for..."  id="keywords"
                                                           name="keywords">
                                                    <span class="input-group-btn">
                                                        <button class="btn bd bd-l-0 bg-white tx-gray-600" onclick="return_data()" type="button">
                                                            <i class="fa fa-search"></i>
                                                        </button>
        											</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div id="table_data">
                        @include('main.phone_quote.new.owes_load')
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end app-content-->
    <div class="modal fade" id="oweshistory" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="oweshistoryLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="oweshistoryLabel">Update History</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('/owes_history_update') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="order_owes_id" name="order_id" />
                        <div class="form-group">
                            <label class="form-label" for="history">History</label>
                            <textarea class="form-control" name="history" id="history" rows="12" cols="12" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="owesviewhistory" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="owesviewhistoryLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width:80%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="owesviewhistoryLabel">View History</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="owesviewhistorydata">
                    
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
                $('#date_range').val("{{\Carbon\Carbon::now()->firstOfMonth()->format('m/d/Y')}} - {{\Carbon\Carbon::now()->format('m/d/Y')}}");
            });
            $('#date_range').val("{{\Carbon\Carbon::now()->firstOfMonth()->format('m/d/Y')}} - {{\Carbon\Carbon::now()->format('m/d/Y')}}");
        });
    </script>


    <script>
        function owesviewhistory(id)
        {
            $("#owesviewhistorydata").html('');
            $.ajax({
                url:"{{ url('/owes_history_view') }}",
                type:"GET",
                data:{id:id},
                dataType:"HTML",
                success:function(res)
                {
                    $("#owesviewhistorydata").html(res);
                }
            })
        }
    
        $("body").delegate("#keywords", "click", function () {
            setTimeout(function () {
                $('input[name="keywords"]').focus()
            }, 100);
        });


        $(document.body).delegate("#keywords", "keyup", function (e) {

            if(e.which == 13){
                return_data();
            }

        });


        $("body").delegate("#search_by", "change", function () {

            var search_by = $('#search_by').val();
            if (search_by == "ophone") {

                $('#keywords').val('');
                $('#keywords').attr('type', 'text');
                $("#keywords").mask("(999) 999-9999");

                setTimeout(function () {
                    $('input[name="keywords"]').focus()
                }, 100);

            } else if (search_by == "created_at" || search_by == "updated_at") {

                $('#keywords').attr('type', 'date');

            } else {
                $('#keywords').attr('type', 'text');
                $("#keywords").unmask();
                setTimeout(function () {
                    $('input[name="keywords"]').focus()
                }, 100);
                $('#keywords').val('');

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

            // if(p_status == 19){

            //     $('.select_cancel').prop("disabled", true);
            // }else{

            //     $('.select_cancel').prop("disabled", false);
            // }



        });

        function return_data() {

            var titlee = $('#titlee').val();

            var data = $('#search_form').serialize();
            data = data+"&titlee="+titlee;

            $('#table_data').html('');
            $('#table_data').append(`<div class="lds-hourglass" id='ldss'></div>`);
            $.ajax({

                url: "/fetch_data",
                type: "GET",
                data: data,
                success: function (data) {
                    $('#table_data').html('');
                    $('#table_data').html(data);
                },
                complete: function (data) {
                    $('#ldss').hide();
                    //regain();
                }

            });
        }


    </script>


    <script src="{{ url('assets/js/jquery-cookie.js')}}"></script>
    <script>
    
                          //=================onchange-values=============================
        $(document).ready(function() {
            // Select all error icons within the document
            var $errorIcons = $('.Terminal-error i');
            var $openPopoverContent = null;
        
            // Iterate over each error icon
            $errorIcons.each(function() {
                var $errorIcon = $(this);
                var $popoverContent = $errorIcon.closest('.Terminal-error').siblings('.popoverContent');
        
                // Toggle the popover on icon click
                $errorIcon.on('click', function(event) {
                    event.stopPropagation(); // Prevent the document click event from firing immediately
        
                    // Close the previously open popover content
                    if ($openPopoverContent && !$openPopoverContent.is($popoverContent)) {
                        $openPopoverContent.hide();
                    }
        
                    // Toggle the current popover content
                    $popoverContent.toggle();
                    $openPopoverContent = $popoverContent;
                });
            });
        
            // Close the popover if clicked outside
            $(document).on('click', function(event) {
                if ($openPopoverContent && !$errorIcons.is(event.target) && !$openPopoverContent.is(event.target) && $openPopoverContent
                    .has(event.target).length === 0) {
                    $openPopoverContent.hide();
                    $openPopoverContent = null;
                }
            });
        });

    //=================onchange-values=============================
    
    
        $(document).ready(function () {


            $(document).on('click', '.pagination a', function (event) {

                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                fetch_data3(page);
                $.cookie("page", page, { expires: 1 });
            });


            function fetch_data3(page) {


                var titlee = $('#titlee').val();

                var data = $('#search_form').serialize();
                data = data+"&titlee="+titlee+"&page="+page;


                $('#table_data').html('');
                $('#table_data').append(`<div class="lds-hourglass" id='ldss'></div>`);

                $.ajax({

                    url: "/fetch_data",
                    data: data,
                    type: "GET",
                    success: function (data) {
                        $('#table_data').html('');
                        $('#table_data').html(data);

                    },
                    complete: function (data) {
                        $('#ldss').hide();
                        //regain();
                    }

                })

            }
        });
        $(".driverphoneno").keypress(function(e){
            if($(this).val() == '')
            {
                $(this).mask("(999) 999-9999");
            }
            var x = e.which || e.keycode;
            if ((x >= 48 && x <= 57) || (x >= 35 && x <= 40)){
                return true;
            }else{
                return false;
            }
        })
    </script>

    <!--Scrolling Modal-->

@endsection


