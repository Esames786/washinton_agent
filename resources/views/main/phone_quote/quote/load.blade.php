@include('partials.mainsite_pages.return_function')
<?php
$respn = trim("$_SERVER[REQUEST_URI]", '/');
if (isset($_GET['titlee'])) {
    $respn = $_GET['titlee'];
}
?>

<div class="col-md-4 col-sm-4 col-lg-4 bg-white" style="height: 700px;overflow: scroll;border: 7px solid #343a40 !important;padding: 2px;">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th colspan="4">
                {{get_pstatus($pstatus)}}
            </th>
        </tr>
        <tr>
            <th>O.ID#</th>
            <th>Date&nbsp;&nbsp;&nbsp;</th>
            <th>User</th>
            <th>Cur.Status</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $val)
            <?php
            $datee = date('Y-m-d');
            $diff = abs(strtotime($val->expected_date) - strtotime($datee));
            $years = floor($diff / (365 * 60 * 60 * 24));
            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
            $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
            ?>
            <tr>
                <td><a target="_blank" href="/searchData?search={{$val->order_id}}">{{$val->order_id}}</a></td>
                <td>
                    <?php
                    if ($val->expected_date == $datee) {
                    ?>
                    <span style="font-size: 12px">{{\Carbon\Carbon::parse($val->expected_date)->format('M,d Y h:i A')}}<br></span>
                    <?php } else {
                    ?>
                        <span style="font-size: 12px">{{\Carbon\Carbon::parse($val->expected_date)->format('M,d Y h:i A')}}</span><br><br>
                    <?php }?>

                    <?php if($datee >= $val->expected_date ){ ?>
                    <span
                            style="color:black;font-weight:600;font-size: 12px;background: #ff8a7e;border-radius: 10px;padding:8px"
                            class="fa fa-blink">Late <?php echo $days  ?></span>
                    <?php }else{ ?>

                    <span
                            style="color:black;font-weight:600;font-size: 12px;background: rgba(24,128,29,0.41);border-radius: 10px;padding:8px">Left <?php echo $days  ?></span>

                    <?php } ?>

                </td>
                <td><span
                            style="color:black;font-weight:600;font-size: 12px;border-radius: 10px;padding:8px">{{get_user_name($val->user_id)}}
                    </span>
                </td>
                <td>{{ get_pstatus(get_autoorder($val->order_id,'pstatus'))}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>





