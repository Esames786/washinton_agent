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
        @if(auth::user()->role == 1)
        <th class="border-bottom-0">ACTIONS</th>
        @endif
    </tr>
    </thead>
    <tbody>

    @foreach($order as $val)
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
                <?php echo pay_status($val->paid_status)?><br>
                @if(isset($val->payment_log2->pay_type))
                    Current Status: <br>
                    @if($val->payment_log2->pay_type == 'Send')
                    <span class="badge badge-info">{{$val->payment_log2->pay_type}}</span>
                    @else
                    <span class="badge badge-success">{{$val->payment_log2->pay_type}}</span>
                    @endif
                @endif
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
                {{ isset($val->orderpayment->profit) ? $val->orderpayment->profit : ($val->payment - $val->pay_carrier) }}
            </td>
            <td>
                {{$val->pay_by == 'Driver' ? $val->payment + $val->storage_fees : $val->payment}}
            </td>
            
            @if(auth::user()->role == 1)
            <td id='order_action' >
                <div class="btn-list">
                        <br>
                    <a class="btn btn-outline-danger"  href="/owes_money_update/{{ $val->id}}" title="Edit Payment">
                        <i class="fa fa-credit-card " style="font-size: 25px"></i>
                    </a>
                </div>
            </td>
            @endif
        </tr>

    @endforeach
    </tbody>
</table>
</div>
<div class="row">
    <div class="col-sm-4">
        Showing {{$order->firstItem() ?? 0}} to {{$order->lastItem() ?? 0}} from total {{$order->total()}} records
    </div>
    <div class="col-sm-8" style="display: flex;justify-content: end;">
        {{  $order->links() }}
    </div>
</div>

<script>
    document.getElementById('total_amount').value = '{{$data}}';
    // $("#total_amount").val({{$data}});
</script>
