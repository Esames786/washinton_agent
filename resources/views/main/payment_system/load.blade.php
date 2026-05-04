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

<div class="row">
    <div class="col-sm-2">
        <div class="form-group">
            Total Complete Order
            <input type="text" disabled value="{{$totalorder}}" class="form-control" />
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-group">
            Total Profit
            <input type="text" disabled value="{{$profit}}" class="form-control" />
        </div>
    </div>
    @if(in_array('89',$phoneaccess))
        <div class="col-sm-2 mt-5">
            <a href="{{ url('/filter_payment') }}" class="btn btn-primary" title="Filter"><i class="fa fa-filter"></i> Filter</a>
        </div>
    @endif
</div>

<div class="table-responsive">
    <table id="" class="display dataTable table-sm">
    <thead>
    <tr>
        <th class="border-bottom-0">ID</th>
        <!--<th class="border-bottom-0">Order/Taker</th>-->
        <th class="border-bottom-0">Name</th>
        <th class="border-bottom-0">Status</th>
        <th class="border-bottom-0">Pay Status</th>
        <th class="border-bottom-0">Pay Method</th>
        <th class="border-bottom-0">Book</th>
        <th class="border-bottom-0">Pay Carrier</th>
        <th class="border-bottom-0">COD/COP</th>
        <th class="border-bottom-0">Storage</th>
        <th class="border-bottom-0">Other</th>
        <th class="border-bottom-0">Deposit</th>
        <th class="border-bottom-0">Owes</th>
        <th class="border-bottom-0">Profit</th>
        <th class="border-bottom-0">Total</th>  <!-- pay_by == driver ? book_price + storage_fees : book_price -->
        <th class="border-bottom-0">ACTIONS</th>
    </tr>
    </thead>
    <tbody>

    @foreach($data as $val)
        <?php
        $color= "";
        if($val->owes_reminder == 1) {
            $color = "#ecb4034d";
        }
        ?>
        <tr id="row_{{$val->id}}" style="background-color: {{$color}};">
            <td class="">
                {{$val->id}}
                Booked Date: {{\Carbon\Carbon::parse($val->date_of_booked)->format('M,d Y')}}
            </td>
            <td>
                <b>Booker:</b><br />
                @if(isset($val->u_id))
                    {{get_user_name($val->u_id)}}
                @else
                    N/A
                @endif
                <b>Dispatcher:</b><br />
                @if(isset($val->dispatcher_id)) {{get_user_name($val->dispatcher_id)}} @else N/A @endif
            </td>

            <td>
                {{ get_pstatus($val->pstatus)}}
            </td>
            <td>
                <?php echo pay_status($val->paid_status)?>
            </td>

            <td>
                {{strtoupper(str_replace('_',' ',$val->vehicle))}}
                <br>{{ ($val->payment_type == 'cod') ? '(COD)' : (($val->payment_type == 'cop') ? '(COP)' : (($val->payment_type == 'card_cop') ? '(CARD+COP)' : (($val->payment_type == 'card_cod') ? '(CARD+COD)' : (($val->payment_type == 'card') ? '(CARD)' : (($val->payment_type == 'Bank') ? '(Bank)' : (($val->payment_type == 'Zell') ? '(Zell)' : (($val->payment_type == 'CashApp') ? '(CashApp)' : (($val->payment_type == 'PayPal') ? '(PayPal)' : (($val->payment_type == 'Cheque') ? '(Cheque)' : ''))))))))) }}
            </td>

            <td>
                {{$val->payment ?? 'N/A'}}
            </td>

            <td>
                {{$val->pay_carrier ? $val->pay_carrier : 'N/A'}}
            </td>
            <td>
                {{$val->cod_cop ? $val->cod_cop : 'N/A'}}
            </td>

            <td>
                {{$val->storage_fees ? $val->storage_fees : 'N/A'}}
                <br>
                @if($val->pay_by == 'Driver')
                <span class="badge badge-warning" style="font-size: 13px">Driver</span>
                @elseif($val->pay_by == 'Customer')
                <span class="badge badge-info" style="font-size: 13px">Customer</span>
                @endif
            </td>
            <td>
                {{$val->other_fees ? $val->other_fees : 'N/A'}}
            </td>


            <td>
                {{ !empty($val->deposit_amount) ? $val->deposit_amount : 'N/A' }}
            </td>

            <td>
                @if($val->owes_money > 0)
                    {{$val->owes ?? 'N/A'}}
                @else
                    N/A
                @endif
                <br> 
                <?php 
                // echo ($val->payment_method2 == "cod_cop") ? 
                // '<span class="badge badge-warning" style="font-size: 13px">Driver To Us</span>' 
                // : 
                // '<span class="badge badge-info" style="font-size: 13px">We to Driver</span>' 
                ?>
                @if($val->pay_carrier < $val->cod_cop)
                <span class="badge badge-warning" style="font-size: 13px">Driver To Us</span>
                @elseif(($val->pay_carrier > $val->cod_cop) || ($val->pay_carrier > 0 && (empty($val->cod_cop) || $val->cod_cop == 0)))
                <span class="badge badge-info" style="font-size: 13px">We to Driver</span>
                @endif
                <br><span title="{{ isset($val->owes_comment_id->name) ? $val->owes_comment_id->name.': '. $val->owes_comment : $val->owes_comment }}" style="font-size: 13px;cursor: pointer" class="badge badge-dark"><?php echo  mb_strimwidth($val->owes_comment, 0, 10, "...") ?></span>
            </td>

            <td>
                <?php
                $profit = 0 ;
                $payment = !empty($val->payment) ? $val->payment : 0;
                               $pay_carrier = !empty($val->pay_carrier) ? $val->pay_carrier : 0; 
                               if($payment >  0 && $pay_carrier > 0){
                                $profit =     $payment - $pay_carrier;
                               }
                               
                ?>
                {{ isset($val->orderpayment->profit) ? $val->orderpayment->profit :$profit }}
            </td>
            <td>
                {{$val->pay_by == 'Driver' ? $val->payment + $val->storage_fees : $val->payment}}
            </td>
            
            <td id='order_action' >
                <div class="btn-list">
                    <a href=""
                    data-id="{{ $val->id }}"
                    data-book_price="{{ $val->payment }}"
                    data-pay_carrier="{{  $val->pay_carrier }}"
                    data-cod_cop="{{  $val->cod_cop }}"
                    data-storage_fees="{{  $val->storage_fees }}"
                    data-other_fees="{{  $val->other_fees }}"
                    data-deposit="{{$val->deposit_amount}}"
                    data-owes="{{ $val->owes }}"
                    data-vehicle="{{ $val->vehicle }}"
                    data-profit="{{ isset($val->orderpayment->profit) ? $val->orderpayment->profit : $profit }}"
                    data-payment_method2="{{$val->payment_method2}}"
                    data-payment_type="{{$val->payment_type}}"
                    data-confirmation="{{ isset($val->orderpayment->confirmation) ? $val->orderpayment->confirmation : 0 }}"
                    data-detail="{{ isset($val->orderpayment->detail) ? $val->orderpayment->detail : "" }}"

                       style="font-size: 20px"
                    class="btn btn-outline-info" title="View Payment Details"
                    data-toggle="modal" data-target="#paymodal"><span class="fa fa-eye"></span> </a>

                    @if(auth::user()->role == 1)
                        <br>
                    <a class="btn btn-outline-danger"  href="/owes_money_update/{{ $val->id}}" title="Edit Payment">
                        <i class="fa fa-credit-card " style="font-size: 25px"></i>
                    </a>
                    @endif
                </div>
            </td>
        </tr>

    @endforeach
    </tbody>
</table>
</div>
<div class="row">
    <div class="col-sm-4">
        Showing {{$data->firstItem() ?? 0}} to {{$data->lastItem() ?? 0}} from total {{$data->total()}} records
    </div>
    <div class="col-sm-8" style="display: flex;justify-content: end;">
        {{  $data->links() }}
    </div>
</div>

