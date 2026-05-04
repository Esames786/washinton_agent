@extends('layouts.innerpages')

@section('template_title')
    {{  ucfirst(trim("$_SERVER[REQUEST_URI]",'/'))}}
@endsection
@section('content')
@include('partials.mainsite_pages.return_function')

<?php 
    $checkpanel = check_panel();
    
    if($checkpanel == 1)
    {
        $phoneaccess = explode(',',Auth::user()->emp_access_phone);
    }
    elseif($checkpanel == 3)
    {
        $phoneaccess = explode(',',Auth::user()->emp_access_test);
    }
    else
    {
        $phoneaccess = explode(',',Auth::user()->emp_access_web);
    }
?>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <style>
        td.details-control {
            background: url({{url('public').'/details_open.png'}}) no-repeat center center;
            cursor: pointer;
        }

        tr.details td.details-control {
            background: url({{url('public').'/details_close.png'}}) no-repeat center center;
        }

        .span_style {
            color: mediumblue;
            font-weight: 700;
        }

        .modal-backdrop.show {
            width: 110vw !important;
            height: 110vh !important;
        }
        select.form-control:not([size]):not([multiple]) {
            height: 2.375rem !important;
        }
        .all_order_form label {
            font-weight: 600;
        }
         #table_data th, #table_data td {
    max-width: 0 !important;
}
    </style>

    <div class="header_show ">
        <center>
            <i class="fa fa-caret-down " style="font-size: 56px;"></i>
        </center>
    </div>
    <!--<ul class="breadcrumb">-->
    <!--    <li><a href="./dashboard">Home-></a></li>-->
    <!--    <li><a>All Orders</a></li>-->
    <!--</ul>-->                                          <!--Page header-->
    <div class="page-header">
        <!--<div class="page-leftheader">-->
        <!--    <input type="hidden" value="{{trim("$_SERVER[REQUEST_URI]",'/')}}" id="titlee">-->
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Home</a>-->
        <!--        </li>-->
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">Price Per Mile</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Payment System Filter</b></h1>
        </div>
    </div>




    <div class="row">
        <div class="col-12">

            @if(session('flash_message'))
                <div class="alert alert-success">
                    {{session('flash_message')}}
                </div>
            @endif
        <!--div-->
            <div class="card payment_system1">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group">
                                Pay Type
                                <select name="paytype" id="paytype" class="form-control">
                                    <option value="">Select</option>
                                    <option value="Send">Send</option>
                                    <option value="Receive">Receive</option>
                    
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                Pay By
                                <select id="pmethod" name="paymentmethod" class="form-control">
                                    <option value="">Select</option>
                                    <option value="cop">COP</option>
                                    <option value="cod">COD</option>
                                    <option value="card_cop">CARD+COP</option>
                                    <option value="card_cod">CARD+COD</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Credit Card">Credit Card</option>
                                    <option value="Certified Cheque">Certified Cheque</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Paypal">Paypal</option>
                                    <option value="Bank Deposit">Bank Deposit</option>
                                    <option value="Zell">Zell</option>
                                    <option value="CashApp">CashApp</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 text-center pd-10">
                            <div class="form-group">
                                Daterange 
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
                        </div>
                        <div class="col-sm-2 mt-5">
                            <button type="button" class="btn btn-primary w-100" id="show_total">Show Total</button>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                Total
                                <input type="text" id="total_amount" disabled value="" class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="table_data">
                        
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
    
        function payment_log_amount()
        {
            $('#table_data').html('');
            $('#table_data').append(`<div class="lds-hourglass" id='ldss'></div>`);
            var paytype = $("#paytype").children('option:selected').val();
            var pmethod = $("#pmethod").children('option:selected').val();
            var date_range = $("#date_range").val();
            $.ajax({
                url:"{{ url('/payment_log_amount') }}",
                type:"GET",
                data:{pmethod:pmethod,paytype:paytype,date_range:date_range},
                success:function(res)
                {
                    $('#table_data').html('');
                    $("#table_data").html(res);
                }
            });
        }

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
                $('#date_range').val("{{\Carbon\Carbon::now()->startOfMonth()->format('m/d/Y')}} - {{\Carbon\Carbon::now()->format('m/d/Y')}}");
            });
            $('#date_range').val("{{\Carbon\Carbon::now()->startOfMonth()->format('m/d/Y')}} - {{\Carbon\Carbon::now()->format('m/d/Y')}}");
        });
        
        $(document).on('click','#show_total',function(){
            payment_log_amount();
        })
        payment_log_amount();

    </script>

    <!--Scrolling Modal-->

@endsection


