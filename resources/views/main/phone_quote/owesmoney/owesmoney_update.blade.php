@extends('layouts.innerpages')

@section('template_title')
    Summary
@endsection
@include('partials.mainsite_pages.return_function')

<style>
    ul.breadcrumb li a {
        color: rgb(2 117 216);
        text-decoration: none;
    }

    .fa-check {
        color: green;
    }

    #table_heading {
        font-size: 15px;
        font-weight: 600;
        color: black;
        background-color: #6cabefd1;
    }

    .table {
        border: 1px solid black;
    }

    .oauc {
        padding: 11px;
        width: 100%;
    }

    .dauc {
        padding: 11px;
        width: 100%;
    }

    .history_content {
        /*background-color: white;*/
    }

    #right_border_radius {
        border-right: 1px solid black;
        border-top-right-radius: 25px;
        border-bottom-right-radius: 25px;
    }

    #left_border_radius {
        border-left: 1px solid black;
        border-top-left-radius: 25px;
        border-bottom-left-radius: 25px;
    }

    .strong_word {
        font-weight: 700;
        font-size: 14px;
        font-family: "Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", "Helvetica Neue", Arial, sans-serif;
    }

    * {
        font-family: "Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", "Helvetica Neue", Arial, sans-serif;
    }

    .span_style {
        color: mediumblue;
        font-weight: 700;
    }

    ul.breadcrumb {
        padding: 10px 16px;
        list-style: none;
        background-color: rgb(0 0 0 / 0%);
    }

    .breadcrumb {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 0;
        list-style: none;
        border-radius: 3px;
        font-weight: 400;
        background: rgb(240 240 242);
    }

    body {
        background-image: linear-gradient(to right, rgb(109, 213, 250), rgb(255, 255, 255), rgb(41, 128, 185)) !important;
        box-shadow: 2px 2px #9E9E9E !important;
    }

    .card {
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: rgb(255 255 255);
        background-clip: border-box;
        position: relative;
        margin-bottom: 1.5rem;
        width: 100%;
        border: 1px solid rgb(219 226 235);
        box-shadow: 0px 6px 8px rgba(4, 4, 7, 0.1);
        border-radius: 8px;
    }

    .card-header {
        background: rgb(0 0 0 / 0%);
        padding: 17px 30px 0px 16px !important;
        display: -ms-flexbox;
        display: flex;
        min-height: 3.5rem;
        -ms-flex-align: center;
        align-items: center;
        margin-bottom: 0;
        border-bottom: 1px solid rgb(235 236 241);
        position: relative;

    }

    .card-body {
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        margin: 0;
        padding: 1.5rem 1.5rem;
        position: relative;
    }
</style>

@section('content')
    <br>
    <h3>ORDER #{{$data->id}} </h3>
    @if (\Session::has('success'))
        <div class="alert alert-success">
            <ul>
                <li>{!! \Session::get('success') !!}</li>
            </ul>
        </div>
    @endif

    @php
        $check_panel = check_panel();
    
        if($check_panel == 1){
    
        $phoneaccess=explode(',',Auth::user()->emp_access_phone);
        }
        elseif($check_panel == 3)
        {
            $phoneaccess = explode(',',Auth::user()->emp_access_test);
        }
        else{
        $phoneaccess=explode(',',Auth::user()->emp_access_web);
        }
    @endphp
    <div class="history_content">

        <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3>CARRIER INFORMATION</h3>
                    </div>
                    <div class="card-body">
                        <h4 class="list-group-item">Company Name :
                            <span class="span_style">@if(isset($data->carrier->companyname)) {{$data->carrier->companyname}} @endif</span>
                        </h4><br>
                        <h4 class="list-group-item">Company Email :
                            <span class="span_style">@if(isset($data->carrier->email)) {{$data->carrier->email}} @endif</span>
                        </h4><br>
                        <h4 class="list-group-item">Company Address :
                            <span class="span_style"> @if(isset($data->carrier->location)) {{$data->carrier->location}} @endif </span>
                        </h4><br>
                        <h4 class="list-group-item">MC# :
                            <span class="span_style">@if(isset($data->carrier->mcno)) {{$data->carrier->mcno}} @endif </span>
                        </h4><br>
                        @if(in_array("60", $phoneaccess))
                            <?php
                                $new = '';
                                if(isset($data->carrier->companyphoneno))
                                {
                                    if(in_array("61", $phoneaccess))
                                    {
                                        $new = $data->carrier->companyphoneno;
                                    }
                                    else
                                    {
                                        $digits = \App\PhoneDigit::first();
                                        $new = putX($digits->hide_digits,$digits->left_right_status,$data->carrier->companyphoneno);
                                    }
                            ?>
                                <h4 class="list-group-item">Company Contact :
                                    @if(isset($new)) 
                                    <span class="text-center pd-2 bd-l">
                                        <a class="btn btn-outline-info  sms mb-2"
                                             onclick="call2('{{base64_encode($data->carrier->companyphoneno)}}')"
                                           style="padding: 3px 5px; font-size: 16px;"><i class="fa fa-phone"></i>&nbsp;{{$new}}</a><br>
                                    </span> 
                                    @endif
                                </h4><br>
                                <h4 class="list-group-item">Company Phone :
                                    @if(isset($new)) 
                                    <span class="text-center pd-2 bd-l">
                                        <a class="btn btn-outline-info  sms mb-2"
                                             onclick="call2('{{base64_encode($data->carrier->companyphoneno)}}')"
                                           style="padding: 3px 5px; font-size: 16px;"><i class="fa fa-phone"></i>&nbsp;{{$new}}</a><br>
                                    </span> 
                                    @endif
                                </h4><br>
                            <?php 
                                }
                            ?>
                        @endif
                        @if(in_array("60", $phoneaccess))
                            <?php 
                                $new = '';
                                if(isset($data->carrier->driverphoneno))
                                {
                                    if(in_array("61", $phoneaccess))
                                    {
                                        $new = $data->carrier->driverphoneno;
                                    }
                                    else
                                    {
                                        $digits = \App\PhoneDigit::first();
                                        $new = putX($digits->hide_digits,$digits->left_right_status,$data->carrier->driverphoneno);
                                    }
                            ?>
                                <h4 class="list-group-item">Driver Phone :
                                    @if(isset($new)) 
                                    <span class="text-center pd-2 bd-l">
                                        <a class="btn btn-outline-info  sms mb-2"
                                             onclick="call2('{{base64_encode($data->carrier->driverphoneno)}}')"
                                           style="padding: 3px 5px; font-size: 16px;"><i class="fa fa-phone"></i>&nbsp;{{$new}}</a><br>
                                    </span> 
                                    @endif
                                </h4><br>
                            <?php 
                                }
                            ?>
                        @endif
                    </div>

                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="card" style=" min-height: 494px; ">
                    <div class="card-header bg-primary text-white">
                        <h3>CUSTOMER INFORMATION</h3>
                    </div>


                    <div class="card-body">
                        <h4 class="list-group-item">Customer Name:
                            <span class="span_style">@if(isset($data->oname)) {{$data->oname}} @endif</span>
                        </h4><br>
                        <h4 class="list-group-item">Address:
                            <span class="span_style"> @if(isset($data->oaddress)) {{$data->oaddress}} @endif </span>
                        </h4><br>
                        <input type="hidden" id="orderId" value="{{$data->id}}" name="orderId" />
                        <?php $ophone = explode('*^', $data->ophone); ?>
                        @if (in_array("42", $phoneaccess))
                            @foreach($ophone as $val3)
                                <?php
                                    if(in_array("67", $phoneaccess))
                                    {
                                        $new = $val3;
                                    }
                                    else
                                    {
                                        $digits = \App\PhoneDigit::first();
                                        
                                        $new = putX($digits->hide_digits,$digits->left_right_status,$val3);
                                    }
                                    
                                ?>
                                <h4 class="list-group-item"> Phone :
                                    @if(isset($new)) 
                                    <span class="text-center pd-2 bd-l">
                                        <a class="btn btn-outline-info  sms mb-2"
                                             onclick="call('{{base64_encode($val3)}}')"
                                           style="padding: 3px 5px; font-size: 16px;"><i class="fa fa-phone"></i>&nbsp;{{$new}}</a><br>
                                    </span> 
                                    @endif
                                </h4><br>
                            @endforeach
                        @endif
                        <h4 class="list-group-item">Email:
                            <span class="span_style">@if(isset($data->oemail)) {{$data->oemail}} @endif </span>
                        </h4><br>
                        <h4 class="list-group-item">Zip:
                            <span class="span_style">@if(isset($data->originzsc)) {{$data->originzsc}} @endif </span>
                        </h4><br>
                    </div>


                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>PRICING & PAYMENT</h4>
                    </div>
                    <div class="card-body">
                        <h4 class="list-group-item">Payment Method :
                            <span class="span_style">

                              <!--{{ ($data->payment_method2 == "quick_pay") ? 'Quick Pay' : (($data->payment_method2 == "cod_cop") ? 'COD/COP' : '') }}-->
                                {{strtoupper(str_replace('_',' ',$data->vehicle))}}
                                {{ ($data->payment_type == 'cod') ? '(COD)' : (($data->payment_type == 'cop') ? '(COP)' : (($data->payment_type == 'card_cop') ? '(CARD+COP)' : (($data->payment_type == 'card_cod') ? '(CARD+COD)' : (($data->payment_type == 'card') ? '(CARD)' : (($data->payment_type == 'Bank') ? '(Bank)' : (($data->payment_type == 'Zell') ? '(Zell)' : (($data->payment_type == 'CashApp') ? '(CashApp)' : (($data->payment_type == 'PayPal') ? '(PayPal)' : (($data->payment_type == 'Cheque') ? '(Cheque)' : ''))))))))) }}
                            </span></h4>
                        <h4 class="list-group-item">Booking Price : <span
                                    class="span_style">${{$data->payment ?? 0}}</span></h4>
                        <h4 class="list-group-item">Pay To Carrier : <span
                                    class="span_style">${{$data->pay_carrier ?? 0}}</span>
                        </h4>
                        <h4 class="list-group-item">COD/COP : <span
                                    class="span_style">${{$data->cod_cop ?? 0}}</span>
                        </h4>
                        <h4 class="list-group-item">Deposit/Initial : <span class="span_style">
                            $ {{$data->deposit_amount ?? 0}}
                         </span>
                        </h4>
                        <h4 class="list-group-item">Storage Fees : <span class="span_style">
                            $ {{$data->storage_fees ?? 0}}
                         </span>
                        </h4>
                        <h4 class="list-group-item">Other Fees : <span class="span_style">
                            $ {{$data->other_fees ?? 0}}
                            @if($data->pay_by == 'Driver')
                                 <span class="badge badge-warning" style="font-size: 13px">Driver</span>
                            @elseif($data->pay_by == 'Customer')
                                 <span class="badge badge-info" style="font-size: 13px">Customer</span>
                            @endif
                         </span>
                        </h4>
                        <h4 class="list-group-item">Owes: <span
                                    class="span_style">
                                 
                                @if($data->owes_money > 0)
                                    ${{$data->owes ?? 0}}
                                @else
                                    $0
                                @endif
                                @if($data->pay_carrier < $data->cod_cop)
                                <span class="badge badge-warning" style="font-size: 13px">Driver To Us</span>
                                @elseif(($data->pay_carrier > $data->cod_cop) || ($data->pay_carrier > 0 && (empty($data->cod_cop) || $data->cod_cop == 0)))
                                <span class="badge badge-info" style="font-size: 13px">We to Driver</span>
                                @endif
                            </span>
                        </h4>
                        <h4 class="list-group-item">Profit : 
                            <span class="span_style">
                                 ${{$data->payment - $data->pay_carrier}}
                             </span>
                        </h4>
                    </div>

                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card" style="height:96% ">
                    <div class="card-header bg-primary text-white">
                        <h4>CUSTOMER CARD HISTORY</h4>
                    </div>
                    <div class="card-body   ">

                        <table id="example-1" class="table table-striped table-bordered text-nowrap">
                            <thead>
                            <tr>
                                <th class="border-bottom-0">ORDER#</th>
                                <th class="border-bottom-0">First Name</th>
                                <th class="border-bottom-0">Last Name</th>
                                <th class="border-bottom-0">Card No</th>
                                <th class="border-bottom-0">Card Expiry Date</th>
                                <th class="border-bottom-0">Card Security</th>
                                <th class="border-bottom-0">Signature</th>
                            </tr>
                            </thead>
                            <tbody>
                            
                                @if(isset($data->credit_card[0]->card_no))
                                    @foreach($data->credit_card as $key => $credit)
                                        <tr>
                                            <td>@if(isset($credit->orderId)) {{$credit->orderId}} @endif</td>
                                            <td>@if(isset($credit->card_first_name)) {{$credit->card_first_name}} @endif</td>
                                            <td>@if(isset($credit->card_last_name)) {{$credit->card_last_name}} @endif</td>
                                            <?php 
                                            $card_no_array = [];
                                            if(isset($credit->card_no))
                                            {
                                                $card_no_array = explode('^*', $credit->card_no);
                                            }
                                            
                                            ?>
            
                                            <td>
                                                @foreach($card_no_array as $cardno)
                                                    @if(isset($cardno) && !empty($cardno))
                                                        {{'xxxx - xxxx - xxxx -'.substr($cardno, -4)}}
                                                        <br>
                                                    @endif
                                                @endforeach
            
                                            </td>
                                            <td>
                                                <?php 
                                                $card_expiry_array = [];
                                                if(isset($credit->card_expiry_date))
                                                {
                                                    $card_expiry_array = explode('^*', $credit->card_expiry_date);
                                                }
                                                ?>
                                                @foreach($card_expiry_array as $cardexpiry)
                                                    @if(isset($cardexpiry) && !empty($cardexpiry))
                                                        {{$cardexpiry}}
                                                        <br>
                                                    @endif
                                                @endforeach
                                            </td>
            
                                            <td>
                                                <?php 
                                                $card_security_array = [];
                                                if(isset($credit->card_security))
                                                {
                                                    $card_security_array = explode('^*', $credit->card_security); 
                                                }
                                                ?>
                                                @foreach($card_security_array as $cardsecurity)
                                                    @if(isset($cardsecurity) && !empty($cardsecurity))
                                                        {{$cardsecurity}}
                                                        <br>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>@if(isset($credit->signature)) {{$credit->signature}} @endif</td>
                                        </tr>
                                    @endforeach
                                @endif
                                
                                @if(isset($data->payment_log[0]->card_number))
                                    @foreach($data->payment_log as $key => $credit)
                                        <tr>
                                            <td>@if(isset($credit->orderId)) {{$credit->orderId}} @endif</td>
                                            <td>@if(isset($credit->card_first_name)) {{$credit->card_first_name}} @endif</td>
                                            <td>@if(isset($credit->card_last_name)) {{$credit->card_last_name}} @endif</td>
                                            <?php 
                                            $card_no_array = [];
                                            if(isset($credit->card_number))
                                            {
                                                $card_no_array = explode('^*', $credit->card_number);
                                            }
                                            
                                            ?>
            
                                            <td>
                                                @foreach($card_no_array as $cardno)
                                                    @if(isset($cardno) && !empty($cardno))
                                                        {{'xxxx - xxxx - xxxx -'.substr($cardno, -4)}}
                                                        <br>
                                                    @endif
                                                @endforeach
            
                                            </td>
                                            <td>
                                                <?php 
                                                $card_expiry_array = [];
                                                if(isset($credit->expiry_date))
                                                {
                                                    $card_expiry_array = explode('^*', $credit->expiry_date);
                                                }
                                                ?>
                                                @foreach($card_expiry_array as $cardexpiry)
                                                    @if(isset($cardexpiry) && !empty($cardexpiry))
                                                        {{$cardexpiry}}
                                                        <br>
                                                    @endif
                                                @endforeach
                                            </td>
            
                                            <td>
                                                <?php 
                                                $card_security_array = [];
                                                if(isset($credit->card_security))
                                                {
                                                    $card_security_array = explode('^*', $credit->card_security); 
                                                }
                                                ?>
                                                @foreach($card_security_array as $cardsecurity)
                                                    @if(isset($cardsecurity) && !empty($cardsecurity))
                                                        {{$cardsecurity}}
                                                        <br>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>@if(isset($credit->signature)) {{$credit->signature}} @endif</td>
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>
                        </table>


                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card" style="height:96% ">
                    <div class="card-header bg-primary text-white">
                        <h4>Payment Logs</h4>
                    </div>
                    <div class="card-body   ">
                        @if(isset($data->payment_log))
                        @foreach($data->payment_log as $plog)
                            <h5 class="list-group-item">
                                -- User:
                                <span class="span_style">{{get_user_name($plog->user_id)}} </span>
                                -- Pay Type:
                                <span class="span_style">{{$plog->pay_type ?? 'N/A'}} </span>
                                -- Pay On:
                                <span class="span_style">{{$plog->pay_location ?? 'N/A'}}</span>
                                -- Pay From:
                                <span class="span_style">{{$plog->pay_from ?? 'N/A'}}</span>
                                -- Pay By:
                                <span class="span_style">{{$plog->pay_method ?? 'N/A'}}</span>
                                -- Amount:
                                <span class="span_style">{{$plog->amount ?? 'N/A'}}</span>
                                -- Date:
                                <span class="span_style">{{\Carbon\Carbon::parse($plog->updated_at)->format('M,d Y H:i A')}}</span>

                                @if($plog->owes_money=='0')
                                    ( Pay Money Confirmed By:{{ $plog->user->name }})
                                @endif
                            </h5>
                        @endforeach
                        @endif
                        <div class="row mt-2">
                            <div class="col-sm-2">
                                <?php 
                                    $send = \App\payment_log::where('orderId',$data->id)->where('pay_type','Send')->sum('amount');
                                ?>
                                <div class="form-group">
                                    <label class="form-label">Total Send</label>
                                    <input type="text" readonly value="{{$send}}" class="form-control"  />
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <?php 
                                    $receive = \App\payment_log::where('orderId',$data->id)->where('pay_type','Receive')->sum('amount');
                                ?>
                                <div class="form-group">
                                    <label class="form-label">Total Receive</label>
                                    <input type="text" readonly value="{{$receive}}" class="form-control"  />
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>


    </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white w-100">
                        <h4>STATUS UPDATE</h4>
                    </div>


                    <div class="card-body">

                        <form action="/store_payment_status" method="post">
                            @csrf
                            <input type="hidden" name="orderid2" value="{{$data->id}}"/>
                            <div class="col-md-8">
                                <input style="width: 50px;height: 20px" type="radio"
                                       @if($data->paid_status == '0' ) checked
                                       @endif name="payment_status" value="0" required>Payment Pending
                                &nbsp;
                                <input style="width: 50px;height: 20px" type="radio"
                                       @if($data->paid_status =='1') checked
                                       @endif name="payment_status" value="1" required>Payment Update
                                
                                    &nbsp;
                                    <input style="width: 50px;height: 20px" type="radio"
                                           @if($data->paid_status == '2') checked
                                           @endif name="payment_status" value="2" required>Payment Received
                            </div>
                            <div class="col-md-2 mb-2">

                                <label>Profit:</label>
                                <input type="text" class="form-control" 
                                       value="@if(isset($data->profit_data->profit)){{$data->profit_data->profit}}@else{{$data->payment - $data->pay_carrier}}@endif"
                                       name="profit">
                            </div>

                            <div class="col-md-1">
                                <button id="sv_btn1" type="submit" name="save" class="btn  btn-primary">
                                    Save
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white w-100">
                        <h4>STATUS/Payment Update</h4>
                    </div>


                    <div class="card-body">
                        <form name="storepayment" class="col-md-12" action="/store_payment_confirmed"
                              method="post">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <input style="width: 50px;height: 20px" type="radio"
                                           @if($data->paid_status == '0') checked
                                           @endif name="payment_status" value="0" required>Payment Pending
                                    &nbsp;
                                    <input style="width: 50px;height: 20px" type="radio"
                                           @if($data->paid_status == '1') checked
                                           @endif name="payment_status" value="1" required>Payment Update

                                        &nbsp;
                                        <input style="width: 50px;height: 20px" type="radio"
                                               @if($data->paid_status == '2') checked
                                               @endif name="payment_status" value="2" required>Payment Received

                                </div>
                                <div class="col-md-2 mb-2">

                                    <label>Profit:</label>
                                    <input type="text" class="form-control" 
                                           value="@if(isset($data->profit_data->profit)){{$data->profit_data->profit}}@else{{$data->payment - $data->pay_carrier}}@endif"
                                           name="profit">
                                </div>


                            </div>

                            <table class="table table-striped table-bordered">
                                <tbody>
                                <input type="hidden" name="orderid" value="{{$data->id}}"/>
                                <tr>
                                    <td>
                                        Pay Type *
                                    </td>
                                    <td>
                                        Pay on *
                                    </td>
                                    <td>
                                        Pay from *
                                    </td>
                                    <td>
                                        Pay By *
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="col-md-8">
                                            <div class="form-group">

                                                <select name="paytype" required class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Send">Send</option>
                                                    <option value="Receive">Receive</option>

                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="col-md-12">
                                            <div class="form-group">

                                                <select name="location" required class="form-control"
                                                        style="width: 100%">
                                                    <option value="">Select</option>
                                                    <option value="Pickup">Pickup</option>
                                                    <option value="Delivery">Delivery</option>

                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="col-md-12">
                                            <div class="form-group">

                                                <select name="payfrom" required class="form-control"
                                                        style="width: 100%">
                                                    <option value="">Select</option>
                                                    <option value="Carrier Pay">Carrier Pay</option>
                                                    <option value="Company Pay">Company Pay</option>
                                                    <option value="Before Pickup">Before Pickup</option>
                                                    <option value="After Pickup">After Pickup</option>
                                                    <option value="Before Delivery">Before Delivery</option>
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="col-md-8">
                                            <div class="form-group">

                                                <select id="pmethod" name="paymentmethod" required
                                                        class="form-control" style="width: 100%">
                                                    <option value="">Select</option>
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
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div id="detail" class="col-md-6">
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="col-md-6">
                                            Amount
                                            <input required type="number" name="amount" value=""
                                                   class="form-control">
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>
                                    <span>
                                        Additional Information
                                    </span>
                                        <div class="col-md-12">
                                        <textarea style="width:100%" required cols="60" rows="6"
                                                  name="additionalinfo"></textarea>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="card-footer text-center">
                                            <h5></h5> I accept the payment is confirmed </h5>
                                            <button id="sv_btn" type="submit" name="save"
                                                    class="btn  btn-primary">
                                                Confirm
                                                Payment
                                            </button>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                </tbody>
                            </table>
                        </form>

                    </div>


                </div>
            </div>

        </div>

@endsection

@section('extraScript')
    <script>
        document.body.style.zoom = "90%"
        $(function () {
            $('.status').hide();
            $('.status_update').change(function () {
                $('.status').hide();
                $('.' + $(this).val()).show();
            })
        })

        $("#pmethod").change(function () {
            if ($("#pmethod").val() == 'Cash') {
                $("#detail").html('');
                $("#detail").append(`
                Cash Status
                <br>
                <select required id="cashstatus" name="cashstatus" required class="form-control" >
                        <option value="">Select</option>
                        <option value="Cash Received">Cash Received</option>
                <option value="Cash Pay">Cash Pay</option>

                </select>

                `)
            }
            if ($("#pmethod").val() == 'Credit Card') {
                $("#detail").html('');
                $("#detail").append(`
                Card Type
                <input type="text" required name="cardtype" value="" class="form-control"/>
                Card First Name
                <input type="text" required name="cardfirstname" value="" class="form-control"/>
                Card Last Name
                <input type="text" required name="cardlastname" value="" class="form-control"/>
                Card Number
                <input type="text" required name="cardno" value="" class="form-control"/>
                Card SEC
                <input type="text" required name="security" value="" class="form-control"/>
                Exp. Date
                <input type="text" required name="expirydate" value="" class="form-control"/>
                Billing Address
                <input type="text" required name="bill_add" value="" class="form-control"/>
                Billing Zip
                <input type="text" required name="bill_zip" value="" class="form-control"/>
                Billing State
                <input type="text" required name="bill_state" value="" class="form-control"/>
                Billing City
                <input type="text" required name="bill_city" value="" class="form-control"/>
                `)
            }
            if ($("#pmethod").val() == 'Certified Cheque') {
                $("#detail").html('');
                $("#detail").append(`
                Certificated Cheque Number
                <input required type="text" name="cheqno" value="" class="form-control"/>

                `)
            }
            if ($("#pmethod").val() == 'Paypal') {
                $("#detail").html('');
                $("#detail").append(`
                Paypal Transcation ID
                <input  type="text" required name="paypalid" value="" class="form-control"/>

                `)
            }
            if ($("#pmethod").val() == 'Bank Deposit') {
                $("#detail").html('');
                $("#detail").append(`
                Bank Transcation ID
                <input required type="text" name="bankid" value="" class="form-control"/>

                `)
            }
            if ($("#pmethod").val() == 'Zell') {
                $("#detail").html('');
                $("#detail").append(`
                <input required type="radio" name="zell" value="1" class="mr-2"/>
                Zell Name<br>
                <input required type="radio" name="zell" value="2" class="mr-2"/>
                Zell Confirmation Number<br>

                `)
            }
            if ($("#pmethod").val() == 'CashApp') {
                $("#detail").html('');
                $("#detail").append(`
                <input required type="radio" name="cashapp" value="1" class="mr-2"/>
                CashApp Name<br>
                <input required type="radio" name="cashapp" value="2" class="mr-2"/>
                CashApp Number<br>

                `)
            }
        });
        
        $(document).on("change","input[name='zell']",function(){
            $("#detail").children('#zell_cashapp').remove();
            if($(this).val() == 1)
            {
                $("#detail").append(`
                    <div class="form-group" id="zell_cashapp">
                        <input placeholder="Zell Name" required type="text" name="zell_name" value="" class="form-control"/>
                    </div>
                `);
            }
            if($(this).val() == 2)
            {
                $("#detail").append(`
                    <div class="form-group" id="zell_cashapp">
                        <input placeholder="Zell Confirmation Number" required type="text" name="zell_no" value="" class="form-control"/>
                    </div>
                `);
            }
        })
        
        $(document).on("change","input[name='cashapp']",function(){
            $("#detail").children('#zell_cashapp').remove();
            if($(this).val() == 1)
            {
                $("#detail").append(`
                    <div class="form-group" id="zell_cashapp">
                        <input placeholder="CashApp Name" required type="text" name="cash_app_name" value="" class="form-control"/>
                    </div>
                `);
            }
            if($(this).val() == 2)
            {
                $("#detail").append(`
                    <div class="form-group" id="zell_cashapp">
                        <input placeholder="CashApp Number" required type="text" name="cash_app_no" value="" class="form-control"/>
                    </div>
                `);
            }
        })


         function call(num)
         {
             var num1 = atob(num);
            //  var newNum = num1.replace(/[- )(]/g,'');
            //  console.log(num1);
             window.location.href = 'rcmobile://call/?number='+num1;
            //  window.location.href = 'tel://'+newNum;
             var id = $("#orderId").val();
             $.ajax({
                url : "{{url('/notRes')}}",
                type : "GET",
                data : {id:id},
                success : function(res)
                {
                    console.log(res);
                }
             });
         }
         
         function call2(num)
         {
             var num1 = atob(num);
            //  var newNum = num1.replace(/[- )(]/g,'');
            //  console.log(num1);
             window.location.href = 'rcmobile://call/?number='+num1;
            //  window.location.href = 'tel://'+newNum;
         }
         
        $('input[name="profit"]').keydown(function(event) {
            // Allow only backspace and delete
            if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) || event.keyCode == 8) {
                // let it happen, don't do anything
            }
            else {
                // Ensure that it is a number and stop the keypress
                if ((event.keyCode < 48 || event.keyCode > 57) || event.keyCode != 8) {
                    event.preventDefault(); 
                }   
            }
        });
    </script>







@endsection

