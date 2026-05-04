@include('partials.mainsite_pages.return_function')
<div class="table-responsive">
    
    <table id="" class="table table-striped table-bordered text-nowrap">
        <thead>
        <tr>
            <th class="border-bottom-0">ORDER#</th>
            <th class="border-bottom-0">C-NAME</th>
            <th class="border-bottom-0">C-EMAIL</th>
            <th class="border-bottom-0">EXPECTED DATE</th>
            <th class="border-bottom-0">USER NAME</th>
            <th class="border-bottom-0">STATUS</th>
            <th class="border-bottom-0">CURRENT STATUS</th>

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
                <td>{{$val->order_id}}</td>
                <td>{{ get_autoorder($val->order_id,"oname")}}</td>
                <td>{{ get_autoorder($val->order_id,"oemail")}}</td>
                <td>
                    <?php
                    if ($val->expected_date == $datee) {
                    ?>
                    <span
                        style="color:black;font-weight:600;background: #ff8a7e;border-radius: 10px;padding:8px">{{\Carbon\Carbon::parse($val->expected_date)->format('M,d Y')}}</span>
                    <?php }else{?>
                    <span
                        style="color:black;font-weight:600;background: rgba(24,128,29,0.41);border-radius: 10px;padding:8px">{{\Carbon\Carbon::parse($val->expected_date)->format('M,d Y')}}</span>
                    <?php }?>

                    <?php if($datee >= $val->expected_date ){ ?>
                    <span
                        style="color:black;font-weight:600;background: #ff8a7e;border-radius: 10px;padding:8px" class="fa fa-blink">Late <?php echo $days  ?></span>
                    <?php }else{ ?>

                    <span
                        style="color:black;font-weight:600;background: rgba(24,128,29,0.41);border-radius: 10px;padding:8px">Left <?php echo $days  ?></span>

                    <?php } ?>

                </td>
                <td><span
                        style="color:black;font-weight:600;border-radius: 10px;padding:8px">{{get_user_name($val->user_id)}}<br></span></td>
                <td><span
                        style="color:black;font-weight:600;background: rgba(24,128,29,0.41);border-radius: 10px;padding:8px">{{get_pstatus($val->pstatus)}}</span></td>
                <td><span
                        style="color:black;font-weight:600;background: rgba(24,128,29,0.41);border-radius: 10px;padding:8px">{{ get_pstatus(get_autoorder($val->order_id,'pstatus'))}}</span>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{  $data->links() }}

</div>





