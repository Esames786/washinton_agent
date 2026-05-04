@include('partials.mainsite_pages.return_function')
<?php
function check_order_taker($order_id, $user_id)
{

    $data = App\report::where('orderId', $order_id)
        ->where('userId', $user_id)
        ->where('pstatus','=',0)
        ->first();

    if (!empty($data)) {
        return $data->userId;
    } else {
        return "";
    }
}
function check_order_booker($order_id, $user_id)
{

    $data = App\report::where('orderId', $order_id)
        // ->where('userId', $user_id)
        ->where(function($q){
            $q->where('pstatus',7)
            ->orWhere('pstatus',8)
            ->orWhere('pstatus',18);
        })
        ->first();

    if (!empty($data)) {
        return $data->userId;
    } else {
        return "";
    }
}

function check_order_cancel($order_id, $user_id)
{

    $data = App\report::where('orderId', $order_id)
        // ->where('userId', $user_id)
        ->where('pstatus','=',14)
        ->first();

    if (!empty($data)) {
        return $data->userId;
    } else {
        return "";
    }
}

function check_order_compelte($order_id, $user_id)
{

    $data = App\report::where('orderId', $order_id)
        // ->where('userId', $user_id)
        ->where('pstatus','=',13)
        ->first();

    if (!empty($data)) {
        return $data->userId;
    } else {
        return "";
    }
}


function Bonus1st($profit)
{
    $amount = 0;
    $data = App\first_bonus::all();
    if (count($data) > 0) {
        foreach ($data as $val) {
            $val1 = $val->fromm;
            $val2 = $val->too;
            $gett = $val->gett;

            if ($profit >= $val1 && $profit <= $val2) {
                $amount = $gett;
            }
        }
    }
    return $amount;

}

function Bonus2nd($profit)
{

    $amount = 0;
    $data = App\second_bonus::all();

    if (count($data) > 0) {
        foreach ($data as $val) {
            $val1 = $val->fromm;
            $val2 = $val->too;
            $gett = $val->gett;

            if ($profit >= $val1 && $profit <= $val2) {
                $amount = $gett;
            }
        }
    }
    return $amount;
}

function cancel_bonus($profit)
{

    $amount = 0;
    $data = App\cancel_bonus::all();
    if (count($data) > 0) {
        foreach ($data as $val) {
            $val1 = $val->fromm;
            $val2 = $val->too;
            $gett = $val->gett;

            if ($profit >= $val1 && $profit <= $val2) {
                $amount = $gett;
            }
        }
    }
    return $amount;
}


?>
<table id="example-1" class="table table-striped table-bordered text-nowrap">
    <thead>
    <tr>
        <th class="border-bottom-0">SNO#</th>
        <th class="border-bottom-0">ORDER Date</th>
        <th class="border-bottom-0">COMPLETED Date</th>
        <th class="border-bottom-0">ORDER ID</th>
        <th class="border-bottom-0">ORDER TAKER</th>
        <th class="border-bottom-0">ORDER BOOKER</th>
        <th class="border-bottom-0">ORDER COMPLETER</th>
        <th class="border-bottom-0">ORDER PRICE</th>
        <th class="border-bottom-0">CARRIER PRICE</th>
        <th class="border-bottom-0">PROFIT</th>
        <th class="border-bottom-0">COMMISSION</th>
        <th class="border-bottom-0">CURRENT STATUS</th>
    </tr>
    </thead>
    <tbody>

    <?php

    $total_commision = 0;
    $total_orders = 0;
    $count_cancel = 0;
    $total_profit = 0;
    $i = 0;

    ?>

    @foreach($data as $val)

        <?php

        $temp = 0;


        $order_id = $val->id;
        $status = $val->pstatus;
        $order_price = $val->payment;
        $pay_carrier = $val->pay_carrier;
        $created_at = $val->created_at;
        $updated_at = $val->updated_at;
        $profit = $order_price - $pay_carrier;

        $commision = Bonus2nd($profit);
        $new_id = "";
        $complete_id = "";
        $new_name = "";
        $complete_name = "";
        $temp = 0;
        $color = "";
        $book_date = "";

        $new_id = check_order_taker($order_id, $user_idd);

        $order_booker = check_order_booker($order_id, $user_idd);


        $complete_id = check_order_compelte($order_id, $user_idd);
        $order_cancel = check_order_cancel($order_id, $user_idd);

        if (!empty($order_cancel)) {
            $count_cancel = $count_cancel + 1;
        }

        if (!empty($new_id) || !empty($complete_id)) {
            if ($new_id == $complete_id) {
                $temp = 1;
                $color = "alert-success";
            } else {
                $temp = 2;
                $color = "alert-warning";
            }
        } else {
            $temp = 0;
        }

        if ($temp > 0) {
            if ($status == 13) {
                // if (!empty($new_id) || !empty($complete_id)) { 

                //     if ($new_id == $user_idd || $complete_id == $user_idd) {
                    if (!empty($new_id)){                
                        if (!empty($complete_id)) {
                            if($order_booker == $user_idd)
                            {
                                
                                $commision = $commision / $temp;
                                $total_commision = $total_commision + $commision;
                                $total_orders++;
                                $total_profit = $total_profit + $profit;
    
                                $new_name = get_user_name($new_id);
                                $booker_name = get_user_name($order_booker);
                                $complete_name = get_user_name($complete_id);
                                $i++;
    
                            ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $created_at = date("M,d Y", strtotime($created_at)); ?></td>
                                    <td><?php echo $updated_at = date("M,d Y", strtotime($updated_at)); ?></td>
                                    <td><a target="_blank"
                                           href="/searchData?search=<?php echo $order_id ?>"><?php echo $order_id ?></a></td>
                                    <td class="alert <?php echo $color ?>"><?php echo ucfirst($new_name) ?></td>
                                    <td class="alert <?php echo $color ?>"><?php echo ucfirst($booker_name) ?></td>
                                    <td class="alert <?php echo $color ?>"><?php echo ucfirst($complete_name) ?></td>
                                    <td><?php echo $order_price ?></td>
                                    <td><?php echo $pay_carrier ?></td>
                                    <td><?php echo $profit ?></td>
                                    <td><?php echo $commision ?></td>
                                    <td><?php echo get_pstatus($status) ?></td>
                                </tr>
                            <?php
                            }
                        }
                //     }
                // }
                    }
            }
        }
        ?>
    @endforeach
    </tbody>

</table>
<hr>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Total Completed Orders</label>
            <input type="text" class="form-control" readonly
                   style="font-size: 22px"
                   value="<?php echo $total_orders ?>">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Total 1st-Bonus Commission</label>
            <input type="text" class="form-control" readonly
                   style="font-size: 22px"
                   value="<?php echo Bonus1st($total_orders) ?>">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Total 2nd-Bonus Commission</label>
            <input type="text" class="form-control" readonly
                   style="font-size: 22px"
                   value="<?php echo $total_commision ?>">
        </div>
    </div>
</div>
<div class="row">

    <div class="col-md-4">
        <div class="form-group">
            <label>Total Profit</label>
            <input type="text" class="form-control" readonly
                   style="font-size: 22px;background: #c3e6cb"
                   value="<?php echo $total_profit; ?>">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Total Cancelled<?php if (isset($user_idd)) { ?>
                <span type="button" class="badge badge-danger" id="watch_cancel">
                    (Watch My Cancel)</span><?php } ?></label>
            <input type="text" class="form-control" readonly
                   style="font-size: 22px;background: #f5c6cb"
                   value="<?php echo $count_cancel; ?>">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Net Commission</label>
            <input type="text" class="form-control" readonly
                   style="font-size: 22px;background: #c3e6cb"
                   value="<?php echo ($total_commision + Bonus1st($total_orders)) - cancel_bonus($count_cancel); ?>">
        </div>
    </div>
</div>
<hr>

<script>

    $("#watch_cancel").on("click", function () {
        var user_idd = '{{$user_idd}}';
        var fromdate = '{{$fromdate}}';
        var todate = '{{$todate}}';
        $.ajax({
            url: "/cancel_orders?user_idd="+user_idd+"&fromdate="+fromdate+"&todate="+todate,
            type: "GET",
            // contentType: false,
            // cache: false,
            // processData: false,
            dataType:"html",
            beforeSend: function () {
                $('#show_cancel').html('');
                $('#show_cancel').html(`<div class="lds-hourglass" id='ldss'></div>`);
            },
            success: function (data) {
                $('#show_cancel').html('');
                $('#show_cancel').html(data);
            } ,
            complete: function (data) {
                $('#ldss').hide();
            }
        });
    });
</script>

