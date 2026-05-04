@extends('layouts.innerpages')

@section('template_title')
    {{  ucfirst(trim("$_SERVER[REQUEST_URI]",'/'))}}
@endsection
@section('content')

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
            <h1 class="my-4"><b>Payment System</b></h1>
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
                    <?php

                    $from =  date('Y-m');
                    $too = date('Y-m');
                    ?>
                    <form action="/payment_system2" id="all_order_form" method="get" style=" display: contents; ">
                        <div class="row w-100">
                            <!--<div class="col-md-2">-->
                            <!--    <label>From</label>-->
                            <!--    <input type="month" name="fromdate" id="fromdate" class="form-control"-->
                            <!--           value="{{ $from }}"/>-->

                            <!--</div>-->
                            <!--<div class="col-md-2">-->
                            <!--    <label>Too</label>-->
                            <!--    <input type="month" name="todate" id="todate" class="form-control"-->
                            <!--           value="{{$too}}"/>-->
                            <!--</div>-->

                            <div class="col-md-2">
                                <label>Payment Method</label>
                                <select class="form-control" name="payment_method">
                                    <option value="">ALL</option>
                                    <option value="quick_pay">Quick Pay</option>
                                    <!--<option value="cod_cop">Cod/Cop</option>-->
                                    <option value="cod">COD</option>
                                    <option value="cop">COP</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label>Payment Status</label>
                                <select class="form-control" name="paid_status">
                                    <option value="">ALL</option>
                                    <option value="0">Pending</option>
                                    <option value="1">Updated</option>
                                    <option value="2">Received</option>
                                </select>
                            </div>
                            <div class="col-md-2 p-0">
                                <label>Owes</label>
                                <select class="form-control" name="owes">
                                    <option value="">ALL Data</option>
                                    <option value="1">With Owes Only</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <div class="row">
                                    @if(Auth::user()->userRole->name == 'Order Taker' || Auth::user()->userRole->name == 'CSR' || Auth::user()->userRole->name == 'Seller Agent')
                                    @else
                                    <div class="col-md-3">
                                        <label>User</label>
                                        <select class="form-control" name="user">
                                            <option value="">ALL</option>
                                            @foreach($users as $key => $val)
                                                <option value="{{$val->id}}">{{$val->name}} ({{$val->slug}})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif
                                    <div class="col-md-6 text-center pd-10">
                                        <label style="float: left">Daterange 
                                        <!--<button type="button" class="btn btn-info btn-sm" onclick="$('#date_range').val('')" style="padding: 3.2px 10px;">Clear</button>-->
                                        </label>
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
                                    <div class="col-md-3 pd-10">
                                        <label>Order Id</label>
                                        <input type="text" name="id" placeholder="Order Id..." class="form-control" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <label>&nbsp;&nbsp;</label>
                                <br>
                                <input type="button" onclick="get_month()" class="btn btn-primary  ml-2 w-100"
                                       name="display"
                                       value="Show"/>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div id="table_data">
                        @include('main.payment_system.load')
                    </div>
                </div>
            </div>

        </div>

        <?php 
            $role = '';
            if(Auth::user()->userRole->name == 'Order Taker' || Auth::user()->userRole->name == 'CSR' || Auth::user()->userRole->name == 'Seller Agent')
            {
                $role = 'Order Taker';
            }
        ?>

        <div class="modal" id="paymodal">
            <div class="modal-dialog modal-lg " role="document">
                <div class="modal-content tx-size-sm" style="width: 150%;!important; left: -10pc;!important;">
                    <div class="modal-header pd-x-20">
                        <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold header_style">Payment Logs </h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style=" font-size: 50px;color:red ">×</span>
                        </button>
                    </div>
                    <div class="modal-body pd-20">
                        <form id="payment_log_submit"  action="{{url('change_order_price')}}" method="post">
                            @csrf
                            <div id="change_price">
                                <form id="change_order_price">
                                    <input type="hidden" id="order_id" name="orderId" readonly>
                                    <input type="hidden" id="book_price2">
                                    <input type="hidden" id="driver_price2">
                                    <input type="hidden" id="cod_cop2">
                                    <input type="hidden" id="deposit2">
                                    <input type="hidden" id="owes2">
                                    <input id="order_price_change" name="order_price_change" type="hidden" readonly>
                                    <div class="card">
                                        <div class="card-header">
                                            <h1>Change Price</h1>
                                        </div>
                                        <div class="card-body pd-20">
                                            <div class="form-group">
                                                <div class="row row-sm marginTop" id="payment_2">


                                                </div>
                                                <div class="row row-sm marginTop">
                                                    <div class="col-sm" style="display: none">
                                                        <label style=" font-size: 16px; font-weight: 500; ">Booked
                                                            Price</label>
                                                        <input type="number" id="book_price" name="payment_2"
                                                               class="form-control " @if($role == 'Order Taker') required @else required @endif value=""/>
                                                    </div><!-- col -->
                                                    <div class="col-sm" style="display: none">
                                                        <label style=" font-size: 16px; font-weight: 500; ">Driver
                                                            Price</label>
                                                        <input type="number"  @if($role == 'Order Taker') required @else required @endif id="driver_price"
                                                               name="driver_price_2" class="form-control "
                                                               value=""/>
                                                    </div><!-- col -->
                                                    <div class="col-sm-2">
                                                        <label style=" font-size: 16px; font-weight: 500; ">Storage Fees</label>
                                                        <input type="number"  id="storage_fees"
                                                               name="storage_fees" class="form-control " value=""/>
                                                    </div><!-- col -->
                                                    <div class="col-sm-2">
                                                        <label style=" font-size: 16px; font-weight: 500; ">Other Fees</label>
                                                        <input type="number"  id="other_fees"
                                                               name="other_fees" class="form-control " value=""/>
                                                    </div><!-- col -->

                                                    <div class="col-sm-2">
                                                        <label style=" font-size: 16px; font-weight: 500; ">Profit</label>
                                                        <input type="number"  @if($role == 'Order Taker') required @else required @endif id="profit"
                                                               name="profit" class="form-control " value=""/>
                                                    </div><!-- col -->
                                                    
                                                    <div class="col-sm-2">
                                                        <label style=" font-size: 16px; font-weight: 500; ">Charge Amount</label>
                                                        <input type="number"  @if($role == 'Order Taker') required @else required @endif id="charge_amount"
                                                               name="charge_amount" class="form-control " value=""/>
                                                    </div><!-- col -->

                                                    <div class="col-sm-2" style="display: none" id="cod_cop_div">
                                                        <label style=" font-size: 16px; font-weight: 500; ">COD/COP</label>
                                                        <input type="number"  @if($role == 'Order Taker') required @else required @endif id="cod_cop"
                                                               name="cod_cop" class="form-control " value=""/>
                                                    </div><!-- col -->

                                                    <div class="col-sm-2" @if(auth::user()->role != 1 ) style="display: none" @endif>
                                                        <label  style=" font-size: 16px; font-weight: 500; "> Confirm Payment <span class="fa fa-thumbs-up"></span></label>
                                                        <input type="checkbox" class="form-control" name="confirmation" id="confirmation">
                                                    </div><!-- col -->


                                                </div><!-- row -->
                                                
                                                <div class="row row-sm marginTop" id="cardDetail">


                                                </div>

                                                <div class="row row-sm marginTop">
                                                    <div class="col-sm-12">
                                                        <label style=" font-size: 16px; font-weight: 500; ">Detail</label>
                                                        <textarea  id="detail" name="detail" class="form-control" @if($role == 'Order Taker') required @else required @endif></textarea>
                                                    </div><!-- col -->

                                                </div>

                                            </div><!-- form-group -->


                                            <button type="submit" class="btn btn-primary pd-x-20 float-right" >Change
                                                Price
                                            </button>

                                        </div><!-- card-body -->
                                    </div>
                                </form>

                            </div>

                            <div class="row">

                                <table class="table table-responsive">
                                    <tr>
                                        <th></th>
                                    </tr>

                                </table>
                            </div>

                        </form>

                    </div><!-- modal-body -->
                </div>
            </div>
        </div>
    </div><!-- end app-content-->
<div id="alreadyCreditCard" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"><div class="modal-content bd-0 tx-14">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h3 class="tx-30 m-0 tx-uppercase tx-inverse tx-bold">CREDIT CARD INFO</h6>
                </div>
                <div class="modal-body pd-25 pl-20 pr-20">
                    <div class="card card-people-list mg-y-20" id="creditCardInfo">
                        <div class="row" id="creditModal">
                            <div class="col-lg-12">
                                <div class="media-list" style="overflow:scroll">
                                    <table class="table table-responsive">
                                        <tr>
                                            <th>Order#id</th>
                                            <th>Type</th>
                                            <th>Card</th>
                                            <th>Card Expire</th>
                                            <th>Phone</th>
                                        </tr>
                                        <tbody id="cards"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </div>



@endsection

@section('extraScript')

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
    
        function payment_log_amount()
        {
            var paytype = $("#paytype").children('option:selected').val();
            var pmethod = $("#pmethod").children('option:selected').val();
            var date_range = $("#date_range").val();
            $.ajax({
                url:"{{ url('/payment_log_amount') }}",
                type:"GET",
                data:{pmethod:pmethod,paytype:paytype,date_range:date_range},
                success:function(res)
                {
                    $("#total_amount").val(res);
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

        // change price after


        function calc() {

            // var book_price = $('#book_price3').val();
            // var driver_price = $('#driver_price3').val();
            // var deposit = $('#deposit3').val();
            // var owes = $('#owes3').val();
            // var total = 0;

            // if (!book_price) book_price = 0;
            // if (!driver_price) driver_price = 0;
            // if (!deposit) deposit = 0;
            // if (!owes) owes = 0;

            // total = parseFloat(book_price) - parseFloat(driver_price) - parseFloat(deposit);
            // $('#owes3').val(total);

        }

        function calc2() {

            // var book_price = $('#book_price3').val();
            // var driver_price = $('#driver_price3').val();
            // var deposit = $('#deposit3').val();
            // var owes = $('#owes3').val();
            // var total = 0;

            // if (!book_price) book_price = 0;
            // if (!driver_price) driver_price = 0;
            // if (!deposit) deposit = 0;
            // if (!owes) owes = 0;

            // total = parseFloat(deposit) - parseFloat(driver_price);

            // $('#owes3').val(total);

        }

        function calc_cp() {

            // var book_price = $('#book_price4').val();
            // var driver_price = $('#driver_price4').val();
            // var deposit = $('#deposit4').val();
            // var owes = $('#owes4').val();
            // var total = 0;

            // if (!book_price) book_price = 0;
            // if (!driver_price) driver_price = 0;
            // if (!deposit) deposit = 0;
            // if (!owes) owes = 0;

            // total = parseFloat(book_price) - parseFloat(driver_price) - parseFloat(deposit);
            // $('#owes4').val(total);

        }

        function calc2_cp() {

            // var book_price = $('#book_price4').val();
            // var driver_price = $('#driver_price4').val();
            // var deposit = $('#deposit4').val();
            // var owes = $('#owes4').val();
            // var total = 0;

            // if (!book_price) book_price = 0;
            // if (!driver_price) driver_price = 0;
            // if (!deposit) deposit = 0;
            // if (!owes) owes = 0;

            // total = parseFloat(deposit) - parseFloat(driver_price);

            // $('#owes4').val(total);

        }


        $("body").delegate("#payment_method_2", "change", function () {
            var card_type = $("#payment_method_2 option:selected").val();

            $('#data_type_2').html('');
            $('#card_input_2').html('');

            if (card_type == "") {

                $('#data_type_2').html("");
            }

            if (card_type == "cod" || card_type == "cop") {
                var type = `<label>COD/P</label>
                        <select  required id='payment_type_2' class="form-control" name='payment_type' style="height: 36px;">
                            <option value='' >Select</option>
                            <option value='cop' >COP</option>
                            <option value='cod' >COD</option>
                            <option value='card_cop' >CARD+COP</option>
                            <option value='card_cod' >CARD+COD</option>
                            <option value='Credit Card' >CARD</option>
                            <option value='Bank' >Bank</option>
                            <option value='Zell' >Zell</option>
                            <option value='CashApp' >Cash App</option>
                            <option value='PayPal' >PayPal</option>
                            <option value='Cheque' >Cheque</option>
                        </select>`
                $('#data_type_2').html(type);
                $('#cod_cop_div').show();
                $("#cod_cop").attr("required","required");

            }
            if (card_type == "quick_pay") {
                var type = `<label>Quick/Pay</label>
                        <select  required id='payment_type_2' class="form-control" name='payment_type' style="height: 36px;">
                         <option value='' >Select</option>
                            <option value='Credit Card' >CARD</option>
                            <option value='Bank' >Bank</option>
                            <option value='Zell' >Zell</option>
                            <option value='CashApp' >Cash App</option>
                            <option value='PayPal' >PayPal</option>
                            <option value='Cheque' >Cheque</option>
                        </select>`
                $('#data_type_2').html(type);
                $('#cod_cop_div').hide();
                $("#cod_cop").removeAttr("required");
            }

        })
        
        $("body").delegate("#payment_method_2", "change", function () {
            $('#cardDetail').html("");
        });
        $("body").delegate("#payment_type_2", "change", function () {
            var driver_price = $('#driver_price2').val();
            var cod_cop = $('#cop_cop2').val();
            var cod_cop3 = $('#cod_cop2').val();
            var book_price = $('#book_price2').val();
            var deposit = $('#deposit2').val();
            var owes = $('#owes2').val();
            var orderId = $('#order_price_change').val();
            
            var pay_carrier = parseInt(driver_price);
            var cod_cop2 = parseInt(cod_cop3);

            var payment_method = $("#payment_method_2 option:selected").val();
            var card_type = $("#payment_type_2 option:selected").val();
            if (card_type == "") {
                $('#cardDetail').html("");
                $('#card_input_2').html("");
            } else {
                var readonly = '';
                var disabled = '';
                if("{{$role}}" == 'Order Taker')
                {
                    // readonly = 'readonly';
                    // disabled = 'disabled'; 
                    readonly = 'required';
                }
                else{
                    readonly = 'required';
                }
                
                $('#cardDetail').html("");
                if (payment_method == 'cod' || payment_method == 'cop') {

                    if (card_type == 'card_cop' || card_type == 'card_cod' || card_type == 'card') {
                        var data = `

                              <div class='col-md-2'>
                                 <label>Book</label>
                                <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="book" value='${book_price}' name='book_price' id='book_price4' class='form-control'>
                             </div>
                             <div class='col-md-2'>
                                 <label>Pay To Carrier</label>
                                <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="driver" value='${driver_price}' name='driver_price' id='driver_price4' class='form-control'>
                             </div>
                             <div class='col-md-2'>
                                 <label>Initial/Deposit</label>
                                <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="deposit" name='deposit' value='${deposit}' id='deposit4' class='form-control'>
                             </div>
                        `;
                        
                        if(pay_carrier < cod_cop2)
                        {
                            data = data + `
                             <div class='col-md-2'>
                                <label>Owes (Driver To Us)</label>
                                <input type='number' ${readonly} ${disabled} onkeyup="calc_cp()" placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>

                             </div>`;
                        }
                        else if(pay_carrier > cod_cop2)
                        {
                            data = data + `
                             <div class='col-md-2'>
                                <label>Owes (We to Driver)</label>
                                <input type='number' ${readonly} ${disabled} onkeyup="calc_cp()" placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>

                             </div>`;
                        }
                        else if(pay_carrier > 0 && cod_cop2 == 0)
                        {
                            data = data + `
                             <div class='col-md-2'>
                                <label>Owes (We to Driver)</label>
                                <input type='number' ${readonly} ${disabled} onkeyup="calc_cp()" placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>
    
                             </div>`;
                        }
                        else
                        {
                            data = data + `
                             <div class='col-md-2'>
                                <label>Owes</label>
                                <input type='number' ${readonly} ${disabled} onkeyup="calc_cp()" placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>
    
                             </div>`;
                        }

                        $('#card_input_2').html(data);
                        
                        
                    } else if (card_type == 'cod' || card_type == 'cop') {

                        var data = `

                              <div class='col-md-2'>
                                   <label>Book</label>
                                <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="book" value='${book_price}' name='book_price' id='book_price4' class='form-control'>
                             </div>
                             <div class='col-md-2'>
                                  <label>Pay To Carrier</label>
                                <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="driver" value='${driver_price}' name='driver_price' id='driver_price4' class='form-control'>
                             </div>
                             <div class='col-md-2' style="display: none">
                                    <label>Initial/Deposit</label>
                                <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="deposit" name='deposit' value='0' id='deposit4' class='form-control'>
                             </div>
                        `;
                        
                        if(pay_carrier < cod_cop2)
                        {
                            data = data + `
                             <div class='col-md-2'>
                                <label>Owes (Driver To Us)</label>
                                <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>

                             </div>`;
                        }
                        else if(pay_carrier > cod_cop2)
                        {
                            data = data + `
                             <div class='col-md-2'>
                                <label>Owes (We to Driver)</label>
                                <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>

                             </div>`;
                        }
                        else if(pay_carrier > 0 && cod_cop2 == 0)
                        {
                            data = data + `
                             <div class='col-md-2'>
                                <label>Owes (We to Driver)</label>
                                <input type='number' ${readonly} ${disabled} onkeyup="calc_cp()" placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>
    
                             </div>`;
                        }
                        else
                        {
                            data = data + `
                             <div class='col-md-2'>
                                <label>Owes</label>
                                <input type='number' ${readonly} ${disabled} onkeyup="calc_cp()" placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>
    
                             </div>`;
                        }

                        $('#card_input_2').html(data);
                    } else {

                        var data = `

                              <div class='col-md-2'>
                                   <label>Book</label>
                                <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="book" value='${book_price}' name='book_price' id='book_price4' class='form-control'>
                             </div>
                             <div class='col-md-2'>
                                  <label>Pay To Carrier</label>
                                <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="driver" value='${driver_price}' name='driver_price' id='driver_price4' class='form-control'>
                             </div>
                             <div class='col-md-2' style="display: none">
                                    <label>Initial/Deposit</label>
                                <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="deposit" name='deposit' value='0' id='deposit4' class='form-control'>
                             </div>
                        `;
                        
                        if(pay_carrier < cod_cop2)
                        {
                            data = data + `
                             <div class='col-md-2'>
                                <label>Owes (Driver To Us)</label>
                                <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>

                             </div>`;
                        }
                        else if(pay_carrier > cod_cop2)
                        {
                            data = data + `
                             <div class='col-md-2'>
                                <label>Owes (We to Driver)</label>
                                <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>

                             </div>`;
                        }
                        else if(pay_carrier > 0 && cod_cop2 == 0)
                        {
                            data = data + `
                             <div class='col-md-2'>
                                <label>Owes (We to Driver)</label>
                                <input type='number' ${readonly} ${disabled} onkeyup="calc_cp()" placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>
    
                             </div>`;
                        }
                        else
                        {
                            data = data + `
                             <div class='col-md-2'>
                                <label>Owes</label>
                                <input type='number' ${readonly} ${disabled} onkeyup="calc_cp()" placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>
    
                             </div>`;
                        }

                        $('#card_input_2').html(data);
                    }

                } else if (payment_method == 'quick_pay') {

                    var data = `

                              <div class='col-md-2'>
                                    <label>Book</label>
                                <input type='number' onkeyup="calc2_cp()" ${readonly} ${disabled} placeholder="book" value='${book_price}' name='book_price' id='book_price4' class='form-control'>
                             </div>
                             <div class='col-md-2'>
                                        <label>Initial/Deposit</label>
                                <input type='number' onkeyup="calc2_cp()" ${readonly} ${disabled} placeholder="deposit" name='deposit' value='${deposit}' id='deposit4' class='form-control'>
                             </div>
                             <div class='col-md-2'>
                              <label>Pay To Carrier</label>
                                <input type='number'onkeyup="calc2_cp()" ${readonly} ${disabled} placeholder="driver" value='${driver_price}' name='driver_price' id='driver_price4' class='form-control'>
                             </div>
                        `;
                        
                        if(pay_carrier < cod_cop2)
                        {
                            data = data + `
                             <div class='col-md-2'>
                                <label>Owes (Driver To Us)</label>
                                <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>

                             </div>`;
                        }
                        else if(pay_carrier > cod_cop2)
                        {
                            data = data + `
                             <div class='col-md-2'>
                                <label>Owes (We to Driver)</label>
                                <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>

                             </div>`;
                        }
                        else if(pay_carrier > 0 && cod_cop2 == 0)
                        {
                            data = data + `
                             <div class='col-md-2'>
                                <label>Owes (We to Driver)</label>
                                <input type='number' ${readonly} ${disabled} onkeyup="calc_cp()" placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>
    
                             </div>`;
                        }
                        else
                        {
                            data = data + `
                             <div class='col-md-2'>
                                <label>Owes</label>
                                <input type='number' ${readonly} ${disabled} onkeyup="calc_cp()" placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>
    
                             </div>`;
                        }

                    $('#card_input_2').html(data);
                }
                
                if(card_type == 'card')
                {
                    var count = getting_cards(orderId,1);
                    var data2 = `
                        <div class="col-sm-12 d-flex">
                            <h3 class="my-auto">Card Detail</h3>
                             <a class="my-auto mx-3" onclick="getting_cards(${orderId},0)" href="javascript:void(0)" data-toggle="modal" data-target="#alreadyCreditCard">${count} Cards Found</a>
                        </div>
                        <div class="col-sm-4">
                            <label for="cardd_first_name" class="form-label">Card First Name</label>
                            <input type="text" placeholder="Enter Card First Name" name="cardd_first_name" class="form-control" />
                        </div>
                        <div class="col-sm-4">
                            <label for="cardd_last_name" class="form-label">Card Last Name</label>
                            <input type="text" placeholder="Enter Card Last Name" name="cardd_last_name" class="form-control" />
                        </div>
                        <div class="col-sm-4">
                            <label for="cardd_type" class="form-label">Card Type (Visa)</label>
                            <input type="text" placeholder="Enter Card Type (Visa)" name="cardd_type" class="form-control" />
                        </div>
                        <div class="col-sm-6">
                            <label for="cardd_number" class="form-label">Card Number</label>
                            <input type="text" placeholder="Enter Card Number" name="cardd_number" class="form-control" />
                        </div>
                        <div class="col-sm-4">
                            <label for="cardd_sec" class="form-label">Card Security</label>
                            <input type="text" placeholder="Enter Card Security" name="cardd_sec" class="form-control" />
                        </div>
                        <div class="col-sm-2">
                            <label for="cardd_exp" class="form-label">Card Expiry</label>
                            <input type="text" placeholder="Enter Card Expiry" name="cardd_exp" class="form-control" />
                        </div>
                        <div class="col-sm-6">
                            <label for="bill_add" class="form-label">Billing Address</label>
                            <input type="text" placeholder="Enter Billing Address" name="bill_add" class="form-control" />
                        </div>
                        <div class="col-sm-2">
                            <label for="bill_zip" class="form-label">Billing Zip</label>
                            <input type="text" placeholder="Enter Billing Zip" name="bill_zip" class="form-control" />
                        </div>
                        <div class="col-sm-2">
                            <label for="bill_state" class="form-label">Billing State</label>
                            <input type="text" placeholder="Enter Billing State" name="bill_state" class="form-control" />
                        </div>
                        <div class="col-sm-2">
                            <label for="bill_city" class="form-label">Billing City</label>
                            <input type="text" placeholder="Enter Billing City" name="bill_city" class="form-control" />
                        </div>
                    `;
                    $('#cardDetail').html(data2);
                }
            }

        })


        //end


        $('#paymodal').on('show.bs.modal', function (e) {
            var order_id = $(e.relatedTarget).data('id');
            $(e.currentTarget).find('input[name="orderId"]').val(order_id);
            var payment = $(e.relatedTarget).data('book_price');
            var driver_price = $(e.relatedTarget).data('pay_carrier');
            var cod_cop = $(e.relatedTarget).data('cod_cop');
            var storage_fees = $(e.relatedTarget).data('storage_fees');
            var other_fees = $(e.relatedTarget).data('other_fees');
            var deposit = $(e.relatedTarget).data('deposit');
            var owes = $(e.relatedTarget).data('owes');
            var profit = $(e.relatedTarget).data('profit');
            var payment_method = $(e.relatedTarget).data('payment_method2');
            var payment_type = $(e.relatedTarget).data('payment_type');
            var confirmation = $(e.relatedTarget).data('confirmation');
            var detail = $(e.relatedTarget).data('detail');
            var vehicle = $(e.relatedTarget).data('vehicle');





            $('#book_price').val(payment);
            $('#cod_cop').val(cod_cop);
            $('#storage_fees').val(storage_fees);
            $('#other_fees').val(other_fees);
            $('#driver_price').val(driver_price);

            $('#book_price2').val(payment);
            $('#cod_cop2').val(cod_cop);
            $('#driver_price2').val(driver_price);
            $('#deposit2').val(deposit);
            $('#owes2').val(owes);
            $('#profit').val(profit);
            $('#charge_amount').val(profit);
            $('#detail').val(detail);

            if(confirmation == 1) {
                $('#confirmation').prop('checked', true);
            }else{
                $('#confirmation').prop('checked', false);
            }

            $('#order_price_change').val(order_id);



            payment_update(payment_method, payment_type, owes, deposit, cod_cop, vehicle,payment,driver_price,order_id);

        });

        function payment_update(payment_method, payment_type, owes, deposit, cod_cop, vehicle,book_price,driver_price,order_id) {
            
            var readonly = '';
            var disabled = '';
            if("{{$role}}" == 'Order Taker')
            {
                // readonly = 'readonly';
                // disabled = 'disabled'; 
                readonly = 'required';
            }
            else{
                readonly = 'required';
            }
            
            owes = owes ?? 0;
            deposit = deposit ?? 0;
            cod_cop = cod_cop ?? 0;
            book_price = book_price ?? 0;
            driver_price = driver_price ?? 0;
            $('#payment_2').html('');

            var data = "";

            var payment_method_data = `

                    <div class="col-sm-2">
                            <label style=" font-size: 16px; font-weight: 500; ">Payment Method  </label>
                            <select ${vehicle != '' ? readonly : ''} id='payment_method_2' required class="form-control" name='payment_method' style="height: 36px;">
                                <option value='' >Select</option>
                                <option ${(vehicle == "quick_pay") ? 'selected' : ''} ${vehicle != '' ? disabled : ''} value='quick_pay' >Quick-Pay</option>
                                <option ${(vehicle == "cod") ? 'selected' : ''} ${vehicle != '' ? disabled : ''} value='cod' >COD</option>
                                <option ${(vehicle == "cop") ? 'selected' : ''} ${vehicle != '' ? disabled : ''} value='cop' >COP</option>
                            </select>
                    </div>
                `;

            var payment_type_cod = `
                     <div class="col-sm-2" id='data_type_2'>
                        <label style=" font-size: 16px; font-weight: 500; ">Payment Type  </label>
                         <select  ${payment_type != '' ? readonly : ''} id='payment_type_2' required class="form-control" name='payment_type' style="height: 36px;">
                            <option  value='' >Select</option>
                            <option ${(payment_type == "cop") ? 'selected' : ''} ${payment_type != '' ? disabled : ''}  value='cop' >COP</option>
                            <option ${(payment_type == "cod") ? 'selected' : ''} ${payment_type != '' ? disabled : ''}  value='cod' >COD</option>
                            <option ${(payment_type == "card_cop") ? 'selected' : ''} ${payment_type != '' ? disabled : ''}   value='card_cop' >CARD+COP</option>
                            <option ${(payment_type == "card_cod") ? 'selected' : ''} ${payment_type != '' ? disabled : ''}  value='card_cod' >CARD+COD</option>
                            <option ${(payment_type == "card") ? 'selected' : ''} ${payment_type != '' ? disabled : ''}  value='card' >CARD</option>
                            <option ${(payment_type == "Bank") ? 'selected' : ''} ${payment_type != '' ? disabled : ''}  value='Bank' >Bank</option>
                            <option ${(payment_type == "Zell") ? 'selected' : ''} ${payment_type != '' ? disabled : ''}  value='Zell' >Zell</option>
                            <option ${(payment_type == "CashApp") ? 'selected' : ''} ${payment_type != '' ? disabled : ''}  value='CashApp' >Cash App</option>
                            <option ${(payment_type == "PayPal") ? 'selected' : ''} ${payment_type != '' ? disabled : ''}  value='PayPal' >PayPal</option>
                            <option ${(payment_type == "Cheque") ? 'selected' : ''} ${payment_type != '' ? disabled : ''}  value='Cheque' >Cheque</option>
                        </select>

                    </div>

                `;


            var payment_type_qp = `
                     <div class="col-sm-2" id='data_type_2'>
                        <label style=" font-size: 16px; font-weight: 500; ">Payment Type  </label>
                        <select  ${payment_type != '' ? readonly : ''} id='payment_type_2' required class="form-control" name='payment_type' style="height: 36px;">
                            <option value='' >Select</option>
                            <option ${(payment_type == "card") ? 'selected' : ''} ${payment_type != '' ? disabled : ''}  value='card' >CARD</option>
                            <option ${(payment_type == "Bank") ? 'selected' : ''} ${payment_type != '' ? disabled : ''}  value='Bank' >Bank</option>
                            <option ${(payment_type == "Zell") ? 'selected' : ''} ${payment_type != '' ? disabled : ''}  value='Zell' >Zell</option>
                            <option ${(payment_type == "CashApp") ? 'selected' : ''} ${payment_type != '' ? disabled : ''}  value='CashApp' >Cash App</option>
                            <option ${(payment_type == "PayPal") ? 'selected' : ''} ${payment_type != '' ? disabled : ''}  value='PayPal' >PayPal</option>
                            <option ${(payment_type == "Cheque") ? 'selected' : ''} ${payment_type != '' ? disabled : ''}  value='Cheque' >Cheque</option>
                        </select>

                    </div>

                `;

            if (vehicle == 'cod' || vehicle == 'cop') {

                if (payment_type == 'card_cop' || payment_type == 'card_cod' || payment_type == 'card') {
                    var newdata = `

                          <div class='col-md-2'>
                             <label>Book</label>
                            <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="book" value='${book_price}' name='book_price' id='book_price4' class='form-control'>
                         </div>
                         <div class='col-md-2'>
                             <label>Pay To Carrier</label>
                            <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="driver" value='${driver_price}' name='driver_price' id='driver_price4' class='form-control'>
                         </div>
                         <div class='col-md-2'>
                             <label>Initial/Deposit</label>
                            <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="deposit" name='deposit' value='${deposit}' id='deposit4' class='form-control'>
                         </div>
                    `;
                    
                    if(driver_price < cod_cop)
                    {
                        newdata = newdata + `
                         <div class='col-md-2'>
                            <label>Owes (Driver To Us)</label>
                            <input type='number' ${readonly} ${disabled} onkeyup="calc_cp()" placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>

                         </div>`;
                    }
                    else if(driver_price > cod_cop)
                    {
                        newdata = newdata + `
                         <div class='col-md-2'>
                            <label>Owes (We to Driver)</label>
                            <input type='number' ${readonly} ${disabled} onkeyup="calc_cp()" placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>

                         </div>`;
                    }
                    else if(driver_price > 0 && cod_cop == 0)
                    {
                        newdata = newdata + `
                         <div class='col-md-2'>
                            <label>Owes (We to Driver)</label>
                            <input type='number' ${readonly} ${disabled} onkeyup="calc_cp()" placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>

                         </div>`;
                    }
                    else
                    {
                        newdata = newdata + `
                         <div class='col-md-2'>
                            <label>Owes</label>
                            <input type='number' ${readonly} ${disabled} onkeyup="calc_cp()" placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>

                         </div>`;
                    }
                    
                    
                } else if (payment_type == 'cod' || payment_type == 'cop') {

                    var newdata = `

                          <div class='col-md-2'>
                               <label>Book</label>
                            <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="book" value='${book_price}' name='book_price' id='book_price4' class='form-control'>
                         </div>
                         <div class='col-md-2'>
                              <label>Pay To Carrier</label>
                            <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="driver" value='${driver_price}' name='driver_price' id='driver_price4' class='form-control'>
                         </div>
                         <div class='col-md-2' style="display: none">
                                <label>Initial/Deposit</label>
                            <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="deposit" name='deposit' value='${deposit}' id='deposit4' class='form-control'>
                         </div>
                    `;
                    
                    if(driver_price < cod_cop)
                    {
                        newdata = newdata + `
                         <div class='col-md-2'>
                            <label>Owes (Driver To Us)</label>
                            <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>

                         </div>`;
                    }
                    else if(driver_price > cod_cop)
                    {
                        newdata = newdata + `
                         <div class='col-md-2'>
                            <label>Owes (We to Driver)</label>
                            <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>

                         </div>`;
                    }
                    else if(driver_price > 0 && cod_cop == 0)
                    {
                        newdata = newdata + `
                         <div class='col-md-2'>
                            <label>Owes (We to Driver)</label>
                            <input type='number' ${readonly} ${disabled} onkeyup="calc_cp()" placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>

                         </div>`;
                    }
                    else
                    {
                        newdata = newdata + `
                         <div class='col-md-2'>
                            <label>Owes</label>
                            <input type='number' ${readonly} ${disabled} onkeyup="calc_cp()" placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>

                         </div>`;
                    }
                } else {

                    var newdata = `

                          <div class='col-md-2'>
                               <label>Book</label>
                            <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="book" value='${book_price}' name='book_price' id='book_price4' class='form-control'>
                         </div>
                         <div class='col-md-2'>
                              <label>Pay To Carrier</label>
                            <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="driver" value='${driver_price}' name='driver_price' id='driver_price4' class='form-control'>
                         </div>
                         <div class='col-md-2' style="display: none">
                                <label>Initial/Deposit</label>
                            <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="deposit" name='deposit' value='${deposit}' id='deposit4' class='form-control'>
                         </div>
                    `;
                    
                    if(driver_price < cod_cop)
                    {
                        newdata = newdata + `
                         <div class='col-md-2'>
                            <label>Owes (Driver To Us)</label>
                            <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>

                         </div>`;
                    }
                    else if(driver_price > cod_cop)
                    {
                        newdata = newdata + `
                         <div class='col-md-2'>
                            <label>Owes (We to Driver)</label>
                            <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>

                         </div>`;
                    }
                    else if(driver_price > 0 && cod_cop == 0)
                    {
                        newdata = newdata + `
                         <div class='col-md-2'>
                            <label>Owes (We to Driver)</label>
                            <input type='number' ${readonly} ${disabled} onkeyup="calc_cp()" placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>

                         </div>`;
                    }
                    else
                    {
                        newdata = newdata + `
                         <div class='col-md-2'>
                            <label>Owes</label>
                            <input type='number' ${readonly} ${disabled} onkeyup="calc_cp()" placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>

                         </div>`;
                    }
                }

            } else if (vehicle == 'quick_pay') {

                var newdata = `

                          <div class='col-md-2'>
                                <label>Book</label>
                            <input type='number' onkeyup="calc2_cp()" ${readonly} ${disabled} placeholder="book" value='${book_price}' name='book_price' id='book_price4' class='form-control'>
                         </div>
                         <div class='col-md-2'>
                                    <label>Initial/Deposit</label>
                            <input type='number' onkeyup="calc2_cp()" ${readonly} ${disabled} placeholder="deposit" name='deposit' value='${deposit}' id='deposit4' class='form-control'>
                         </div>
                         <div class='col-md-2'>
                          <label>Pay To Carrier</label>
                            <input type='number'onkeyup="calc2_cp()" ${readonly} ${disabled} placeholder="driver" value='${driver_price}' name='driver_price' id='driver_price4' class='form-control'>
                         </div>
                    `;
                    
                    if(driver_price < cod_cop)
                    {
                        newdata = newdata + `
                         <div class='col-md-2'>
                            <label>Owes (Driver To Us)</label>
                            <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>

                         </div>`;
                    }
                    else if(driver_price > cod_cop)
                    {
                        newdata = newdata + `
                         <div class='col-md-2'>
                            <label>Owes (We to Driver)</label>
                            <input type='number' onkeyup="calc_cp()" ${readonly} ${disabled} placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>

                         </div>`;
                    }
                    else if(driver_price > 0 && cod_cop == 0)
                    {
                        newdata = newdata + `
                         <div class='col-md-2'>
                            <label>Owes (We to Driver)</label>
                            <input type='number' ${readonly} ${disabled} onkeyup="calc_cp()" placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>

                         </div>`;
                    }
                    else
                    {
                        newdata = newdata + `
                         <div class='col-md-2'>
                            <label>Owes</label>
                            <input type='number' ${readonly} ${disabled} onkeyup="calc_cp()" placeholder="Owes" value='${owes}' name='owes' id='owes4' class='form-control'>

                         </div>`;
                    }
            }
            
            if(payment_type == 'card')
            {
                var count = getting_cards(order_id,1);
                var data2 = `
                    <div class="col-sm-12 d-flex">
                        <h3 class="my-auto">Card Detail</h3>
                         <a class="my-auto mx-3" onclick="getting_cards(${order_id},0)" href="javascript:void(0)" data-toggle="modal" data-target="#alreadyCreditCard">${count} Cards Found</a>
                    </div>
                    <div class="col-sm-4">
                        <label for="cardd_first_name" class="form-label">Card First Name</label>
                        <input type="text" placeholder="Enter Card First Name" name="cardd_first_name" class="form-control" />
                    </div>
                    <div class="col-sm-4">
                        <label for="cardd_last_name" class="form-label">Card Last Name</label>
                        <input type="text" placeholder="Enter Card Last Name" name="cardd_last_name" class="form-control" />
                    </div>
                    <div class="col-sm-4">
                        <label for="cardd_type" class="form-label">Card Type (Visa)</label>
                        <input type="text" placeholder="Enter Card Type (Visa)" name="cardd_type" class="form-control" />
                    </div>
                    <div class="col-sm-6">
                        <label for="cardd_number" class="form-label">Card Number</label>
                        <input type="text" placeholder="Enter Card Number" name="cardd_number" class="form-control" />
                    </div>
                    <div class="col-sm-4">
                        <label for="cardd_sec" class="form-label">Card Security</label>
                        <input type="text" placeholder="Enter Card Security" name="cardd_sec" class="form-control" />
                    </div>
                    <div class="col-sm-2">
                        <label for="cardd_exp" class="form-label">Card Expiry</label>
                        <input type="text" placeholder="Enter Card Expiry" name="cardd_exp" class="form-control" />
                    </div>
                    <div class="col-sm-6">
                        <label for="bill_add" class="form-label">Billing Address</label>
                        <input type="text" placeholder="Enter Billing Address" name="bill_add" class="form-control" />
                    </div>
                    <div class="col-sm-2">
                        <label for="bill_zip" class="form-label">Billing Zip</label>
                        <input type="text" placeholder="Enter Billing Zip" name="bill_zip" class="form-control" />
                    </div>
                    <div class="col-sm-2">
                        <label for="bill_state" class="form-label">Billing State</label>
                        <input type="text" placeholder="Enter Billing State" name="bill_state" class="form-control" />
                    </div>
                    <div class="col-sm-2">
                        <label for="bill_city" class="form-label">Billing City</label>
                        <input type="text" placeholder="Enter Billing City" name="bill_city" class="form-control" />
                    </div>
                `;
                $('#cardDetail').html(data2);
            }
            var random = `<div id='card_input_2' style="display:contents">${newdata ?? ''}</div>`

            var pay = `
                <div class="col-sm-2">
                    <label for="cardd_pay_type" class="form-label">Pay Type</label>
                    <select name="cardd_pay_type" class="form-control" required>
                        <option value="" selected disabled>Select</option>
                        <option value="Send">Send</option>
                        <option value="Receive">Receive</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <label for="cardd_pay_on" class="form-label">Pay On</label>
                    <select name="cardd_pay_on" class="form-control" required>
                        <option value="" selected disabled>Select</option>
                        <option value="Pickup">Pickup</option>
                        <option value="Delivery">Delivery</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <label for="cardd_pay_from" class="form-label">Pay From</label>
                    <select name="cardd_pay_from" class="form-control" required>
                        <option value="" selected disabled>Select</option>
                        <option value="Carrier Pay">Carrier Pay</option>
                        <option value="Company Pay">Company Pay</option>
                        <option value="Before Pickup">Before Pickup</option>
                        <option value="After Pickup">After Pickup</option>
                        <option value="Before Delivery">Before Delivery</option>
                    </select>
                </div>
            `;
            if (vehicle == "cod" || vehicle == "cop") {
                data = payment_method_data + payment_type_cod + pay + random;
                $('#cod_cop_div').show();
                $("#cod_cop").attr("required","required");

            } else if (vehicle == "quick_pay") {
                data = payment_method_data + payment_type_qp + pay + random;
                $('#cod_cop_div').hide();
                $("#cod_cop").removeAttr("required");
            }else{
                data = payment_method_data + payment_type_cod + pay + random;
                $('#cod_cop_div').show();
                $("#cod_cop").attr("required","required");
            }

            $('#payment_2').html(data);


            $('#payment_type_2').trigger('change');
        }

        $(document).ready(function () {

            $(document).on('click', '.pagination a', function (event) {

                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                fetch_data3(page);

            });

            function fetch_data3(page) {

                var paytype = $("#paytype").children('option:selected').val();
                var pmethod = $("#pmethod").children('option:selected').val();
                var total_amount = $("#total_amount").val();
                
                $('#table_data').html('');
                $('#table_data').append(`<div class="lds-hourglass" id='ldss'></div>`);



                var all_order_form = $('#all_order_form').serialize();

                all_order_form = all_order_form + "&paytype="+paytype;
                all_order_form = all_order_form + "&pmethod="+pmethod;
                all_order_form = all_order_form + "&total_amount="+total_amount;
                all_order_form = all_order_form + "&page="+page;

                $.ajax({

                    url: '/payment_system2',
                    type: 'GET',
                    data: all_order_form,
                    success: function (data) {
                        $('#table_data').html('');
                        $('#table_data').html(data);

                    },
                    complete: function (data) {
                        $('#ldss').hide();
                        regain();
                        payment_log_regain();
                    }

                })

            }

        });

        function get_month() {
            var paytype = $("#paytype").children('option:selected').val();
            var pmethod = $("#pmethod").children('option:selected').val();
            var total_amount = $("#total_amount").val();

            $('#table_data').html('');
            $('#table_data').append(`<div class="lds-hourglass" id='ldss'></div>`);


            var all_order_form = $('#all_order_form').serialize();
            
            all_order_form = all_order_form + "&paytype="+paytype;
            all_order_form = all_order_form + "&pmethod="+pmethod;
            all_order_form = all_order_form + "&total_amount="+total_amount;


            $.ajax({
                url: '/payment_system2',
                type: 'GET',
                data: all_order_form,
                success: function (data) {
                    $('#table_data').html('');
                    $('#table_data').html(data);

                },
                complete: function (data) {
                    $('#ldss').hide();
                    regain();
                    payment_log_regain();
                }

            });

        }

    </script>


    <script>

        document.body.style.zoom = "90%";

        function format(d) {
            return `<div id='pay_log${d[0]}'></div>`;
        }

        $(document).ready(function () {
            var dt = $('#example4').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'print',
                ],
                "ordering": false,
                aLengthMenu: [[100, 125, 150, -1], [100, 125, 150, "All"]],
                "bDestroy": true

            });

            // Array to track the ids of the details displayed rows
            var detailRows = [];

            $('#example4 tbody').on('click', 'tr td.details-control', function () {

                var tr = $(this).closest('tr');
                var row = dt.row(tr);
                var idx = $.inArray(tr.attr('id'), detailRows);
                var data_row = row.data();

                if (row.child.isShown()) {
                    tr.removeClass('details');
                    row.child.hide();

                    // Remove from the 'open' array
                    detailRows.splice(idx, 1);
                }
                else {
                    tr.addClass('details');
                    row.child(format(row.data())).show();
                    $.ajax({
                        data: {orderid: data_row[0]},
                        url: "/show_payment_logs",
                        type: "GET",
                        success: function (data) {
                            var obj = jQuery.parseJSON(data);
                            var merge = "";
                            $.each(obj, function (key, value) {
                                merge = merge + ` <h5 class="list-group-item">
                                -- Pay Type:
                                <span class="span_style">${value.pay_type} </span>
                                -- Pay On:
                                <span class="span_style">${value.pay_location}</span>
                                -- Pay By:
                                <span class="span_style">${value.pay_method}</span>
                                -- Amount:
                                <span class="span_style">${value.amount}</span>`
                            });

                            $(`#pay_log${data_row[0]}`).html(merge);
                        }
                    });

                    // Add to the 'open' array
                    if (idx === -1) {
                        detailRows.push(tr.attr('id'));
                    }
                }
            });

            // On each draw, loop over the `detailRows` array and show any child rows
            dt.on('draw', function () {
                $.each(detailRows, function (i, id) {
                    $('#' + id + ' td.details-control').trigger('click');
                });
            });
        });


        function payment_log_regain() {


            var dt = $('#example4').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'print',
                ],
                "ordering": false,
                aLengthMenu: [[100, 125, 150, -1], [100, 125, 150, "All"]],
                "bDestroy": true

            });

            // Array to track the ids of the details displayed rows
            var detailRows = [];

            $('#example4 tbody').on('click', 'tr td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = dt.row(tr);
                var idx = $.inArray(tr.attr('id'), detailRows);
                var data_row = row.data();

                if (row.child.isShown()) {
                    tr.removeClass('details');
                    row.child.hide();

                    // Remove from the 'open' array
                    detailRows.splice(idx, 1);
                }
                else {
                    tr.addClass('details');
                    row.child(format(row.data())).show();
                    $.ajax({
                        data: {orderid: data_row[0]},
                        url: "/show_payment_logs",
                        type: "GET",
                        success: function (data) {
                            var obj = jQuery.parseJSON(data);
                            var merge = "";
                            var amount = 0;
                            var profit = 0;
                            $.each(obj, function (key, value) {
                                amount = amount + value.amount;
                                profit = value.profit;
                                merge = merge + ` <h5 class="list-group-item">
                                -- Pay Type:
                                <span class="span_style">${value.pay_type} </span>
                                -- Pay On:
                                <span class="span_style">${value.pay_location}</span>
                                -- Pay By:
                                <span class="span_style">${value.pay_method}</span>
                                -- Amount:
                                <span class="span_style">${value.amount}</span>

                                `

                            });
                            merge = merge + ` <br><br> --Total Amount <span class="span_style">` + amount + `</span>`;
                            merge = merge + `--Total Profit <span class="span_style">` + profit + `</span>`;
                            $(`#pay_log${data_row[0]}`).html(merge);
                        }
                    });

                    // Add to the 'open' array
                    if (idx === -1) {
                        detailRows.push(tr.attr('id'));
                    }
                }
            });

            // On each draw, loop over the `detailRows` array and show any child rows
            dt.on('draw', function () {
                $.each(detailRows, function (i, id) {
                    $('#' + id + ' td.details-control').trigger('click');
                });
            });

        }
        
        function getting_cards(order_id,count)
        {
            $("#cards").html('');
            var counting = 0; 
            $.ajax({
                url:"{{ url('/getting_cards') }}",
                type:"GET",
                data:{id:order_id,count:count},
                success:function(res)
                {
                    if(count == 1)
                    {
                        counting = res;
                    }
                    else
                    {
                        $("#cards").html(res);
                    }
                }
            });
            return counting;
        }
        
        $(document).on('click','#show_total',function(){
            payment_log_amount();
        })
        payment_log_amount();

    </script>

    <!--Scrolling Modal-->

@endsection


