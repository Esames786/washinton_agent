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
    <?php

    $total_commision = 0;
    $total_orders = 0;
    $count_cancel = 0;
    $total_profit = 0;
    $i = 0;
    $commission = 0;

    ?>

    @foreach($data as $val)

        <?php
            $order_id = $val->id;
            $status = $val->pstatus;
            $order_price = $val->payment;
            $pay_carrier = $val->pay_carrier;
            $profit = $order_price - $pay_carrier;
    
            $commision = Bonus2nd($profit);
            $new_id = "";
            $complete_id = "";
            $temp = 0;
    
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
                } else {
                    $temp = 2;
                }
            } else {
                $temp = 0;
            }
    
            if ($temp > 0) {
                if ($status == 13) {
                        if (!empty($new_id)){                
                            if (!empty($complete_id)) {
                                if($order_booker == $user_idd)
                                {
                                    $commision = $commision / $temp;
                                    $total_commision = $total_commision + $commision;
                                    $total_orders++;
                                    $total_profit = $total_profit + $profit;
                                    $i++;
        ?>
        <?php
                                }
                            }
                        }
                }
            }
        ?>
    @endforeach
<?php
    $commission = ($total_commision + Bonus1st($total_orders)) - cancel_bonus($count_cancel);
?>
<span class="badge badge-success mr-2 text-light">Commission: {{$commission}}</span>