<?php

$user_id = 0;
if (isset($user_idd)) {

$user_id = $user_idd;

?>
<center><h3>Cancel Orders</h3></center>
    <table class="table table-striped table-hover" id="tableExportt"
       style="width:100%;">
    <thead>
    <tr>
        <th>SNO#</th>
        <th>ORDER Date</th>
        <th>Cancel Date</th>
        <th>ORDER ID</th>
        <th>USERNAME</th>
        <th>ORDER PRICE</th>
        <th>CARRIER PRICE</th>
        <th>CURRENT STATUS</th>
    </tr>
    </thead>
    <tbody>
    <?php

    function get_user_name_new($id)
    {
        $setting = App\general_setting::first();
        $query = \App\User::where('id', $id)->first();
        if (!empty($query)) {
            if($query->slug)
            {
                return $query->slug;
            }
            else{
                return $query->name.' '.$query->last_name;
            }
        } else {
            return '';
        }
    }
    $from = date('Y-m-d 00:00:00', strtotime($fromdate));
    $too = date('Y-m-d 23:59:59', strtotime($todate));
    $i = 0;
    
    $data = App\report::where('pstatus','14')
        ->where('userId',$user_id)
        ->get()->unique('orderId');
        if (count($data) > 0) {
        foreach ($data as $key => $val) {

            $my_id = $val->orderId;
            $cancel_date = $val->created_at;

            $sql2 = \App\AutoOrder::where('id',$my_id)->whereBetween('created_at', [$from,$too])->first();

            if (isset($sql2->id)) {

                $fetch2 = $sql2;
                $i++;

                $order_id = $fetch2->id;
                $order_price = $fetch2->payment;
                $pay_carrier = $fetch2->pay_carrier;
                $created_at = $fetch2->created_at;
                $status = $fetch2->pstatus;
                $new_name = get_user_name_new($user_id);



                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $created_at = date("M,d Y", strtotime($created_at)); ?></td>
                        <td><?php echo $cancel_date = date("M,d Y", strtotime($cancel_date)); ?></td>
                        <td><a target="_blank" href="/searchData?search=<?php echo $order_id ?>"><?php echo $order_id ?></a>
                        </td>
                        <td class="alert alert-success"><?php echo ucfirst($new_name) ?></td>
                        <td><?php echo $order_price ?></td>
                        <td><?php echo $pay_carrier ?></td>
                        <td><?php if ($status == 14) {
                                echo "cancelled";
                            } else {
                                echo $status;
                            } ?></td>
                    </tr>
                    <?php

            }

        }
        ?>
        </tbody>
    </table>
    <?php
    }

}
?>