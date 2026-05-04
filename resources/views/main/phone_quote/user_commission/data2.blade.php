@include('partials.mainsite_pages.return_function')
<?php
function check_order_pickup($order_id, $user_id)
{

    $data = App\report::where('orderId', $order_id)
        ->where('userId', $user_id)
        ->where('pstatus', '11')
        ->first();

    if (!empty($data)) {
        return $data->userId;
    } else {
        return "";
    }
}

function check_order_deliver($order_id, $user_id)
{

    $data = App\report::where('orderId', $order_id)
        // ->where('userId', $user_id)
        ->where('pstatus', '12')
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
        <th class="border-bottom-0">ORDER ID</th>
        <th class="border-bottom-0">ORDER DISPATCHER</th>
        <th class="border-bottom-0">ORDER DELIVERED</th>
        <th class="border-bottom-0">ORDER PRICE</th>
        <th class="border-bottom-0">CARRIER PRICE</th>
        <th class="border-bottom-0">PROFIT</th>
        <th class="border-bottom-0">COMMISSION</th>
        <th class="border-bottom-0">STATUS</th>
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
        $profit = $order_price - $pay_carrier;

        $commision = Bonus2nd($profit);
        $pickup_id = "";
        $deliver_id = "";
        $pick_name = "";
        $deliver_name = "";
        $temp = 0;
        $color = "";
        $pickup_date = "";

        $pickup_id = check_order_pickup($order_id, $user_idd);

        $deliver_id = check_order_deliver($order_id, $user_idd);

        if (!empty($pickup_id) || !empty($deliver_id)) {
            if ($pickup_id == $deliver_id) {
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
    
            if ($status == 12) {
                if (!empty($pickup_id)){                
                    if (!empty($deliver_id)) {

                        $commision = $commision / $temp;
                        $total_commision = $total_commision + $commision;
                        $total_orders++;
                        $total_profit = $total_profit + $profit;

                        $pick_name = get_user_name($pickup_id);
                        $deliver_name = get_user_name($deliver_id);
                        $i++;

                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $created_at = date("M,d Y", strtotime($created_at)); ?></td>
                            <td><a target="_blank"
                                   href="/searchData?search=<?php echo $order_id ?>"><?php echo $order_id ?></a></td>
                            <td class="alert <?php echo $color ?>"><?php echo ucfirst($pick_name) ?></td>
                            <td class="alert <?php echo $color ?>"><?php echo ucfirst($deliver_name) ?></td>
                            <td><?php echo $order_price ?></td>
                            <td><?php echo $pay_carrier ?></td>
                            <td><?php echo $profit ?></td>
                            <td><?php echo $commision ?></td>
                            <td><?php echo get_pstatus($status) ?></td>
                        </tr>
                        <?php

                    }
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
            <label>Total Delivered Orders</label>
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
            <label>Net Commission</label>
            <input type="text" class="form-control" readonly
                   style="font-size: 22px;background: #c3e6cb"
                   value="<?php echo ($total_commision + Bonus1st($total_orders)) - cancel_bonus($count_cancel); ?>">
        </div>
    </div>
</div>