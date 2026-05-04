@include('partials.mainsite_pages.return_function')
<?php
$respn = trim("$_SERVER[REQUEST_URI]", '/');
if (isset($_GET['titlee'])) {
    $respn = $_GET['titlee'];
}
?>
<div class="table-responsive">
    <?php
    if($display == 'yes'){
    ?>
    <table class="table table-striped table-bordered">
        <thead>
        <tr>

            <th class="border-bottom-0">Orde Id</th>
            <th class="border-bottom-0">CARD NAME</th>
            <th class="border-bottom-0">BILLING ADDRESS</th>
            <th class="border-bottom-0">ZIP</th>
            <th class="border-bottom-0">CARD NO</th>
            <th class="border-bottom-0">CARD EXPIRY DATE</th>
            <th class="border-bottom-0">CARD SECURITY CODE</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $val)
            <tr>

                <td>
                    {{ $val->orderId }}
                </td>
                <td>
                    {{ $val->card_first_name }} {{ $val->card_last_name }}
                </td>
                <td>
                    {{ $val->billing_address }}
                </td>
                <td>
                    <?php 
                        $zsc = explode(',',$val->b_zsc);
                    ?>
                    @foreach($zsc as $key => $zip)
                        <?php $zip = trim($zip," "); ?>
                        @if($zip)
                            {{$zip}}, 
                        @endif
                    @endforeach
                </td>
                <td>
                    @php
                    $cardnos=explode('*^',$val->card_no);
                    $expirys=explode('*^',$val->card_expiry_date);
                    $securities=explode('*^',$val->card_security);
                    $i = 1;
                    $j = 1;
                    $k = 1;
                    @endphp
                    @foreach($cardnos as $key => $cardno)
                        <?php $cardno = trim($cardno," "); ?>
                        @if($cardno)
                            {{$i}}) {{$cardno}} <br>
                            <?php $i = $i + 1; ?>
                        @endif
                    @endforeach
                </td>
                <td>
                    @foreach($expirys as $key => $expiry)
                        <?php $expiry = trim($expiry," "); ?>
                        @if($expiry)
                            {{$j}}) {{$expiry}} <br>
                            <?php $j = $j + 1; ?>
                        @endif
                    @endforeach

                </td>
                <td>
                    @foreach($securities as $key => $security)
                        <?php $security = trim($security," "); ?>
                        @if($security)
                            {{$k}}) {{$security}} <br>
                            <?php $k = $k + 1; ?>
                        @endif
                    @endforeach

                </td>

            </tr>
        @endforeach
        </tbody>
    </table>

        {{  $data->links() }}
    <?php
    }
    ?>

</div>


<script>
    // regain_call();
    // regain_status();
    // regain_report_modal();
</script>
