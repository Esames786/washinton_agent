@include('partials.mainsite_pages.return_function')
<?php
$respn = trim("$_SERVER[REQUEST_URI]", '/');
if (isset($_GET['titlee'])) {
    $respn = $_GET['titlee'];
}
?>
<div class="table-responsive">
    <table class="table table-bordered text-nowrap dataTable no-footer" id="example1" role="grid"
        aria-describedby="example1_info">
        <thead>
            <tr>
                <th class="border-bottom-0">ORDER#</th>
                <th class="border-bottom-0">Pickup</th>
                <th class="border-bottom-0">Delivery</th>
                <th class="border-bottom-0">VEHICLE#/ORDERTAKER<BR></th>
                {{-- <th class="border-bottom-0">Order Price</th> --}}
                <th class="border-bottom-0">Customer/Payment</th>
                <th class="border-bottom-0">Profit</th>
                <th class="border-bottom-0">Dates</th>

            </tr>
        </thead>
        <tbody>
            @php
                $totalprofit = 0;
            @endphp
            @foreach ($data as $val)
                <tr>
                    <td>
                        {{ $val->id }}
                        <input type="hidden" class='order_id' value="{{ $val->id }}">
                        <input type="hidden" class="pstatus" value="{{ $val->pstatus }}">
                        <input type="hidden" class="client_email" value="{{ $val->oemail }}">
                        <input type="hidden" class="client_name" value="{{ $val->oname }}">
                        <input type="hidden" class="client_phone" value="{{ $val->main_ph }}">
                        <input type="hidden" class="location1" value="{{ $val->originzsc }}">
                        <input type="hidden" class="location2" value="{{ $val->destinationzsc }}">
                    </td>
                    <td>
                        <a href="http://classic.mapquest.com/embed?zoom=5&amp;q={{ $val->origincity }}+{{ $val->originstate }}+{{ $val->originzip }}"
                            target="_blank">
                            <i class="fa fa-map-marker" style="color:green;" aria-hidden="true"></i>
                            {{ $val->origincity . '-' . $val->originstate . '-' . $val->originzip }}
                        </a><br>
                        <strong><i class="fa fa-location-arrow" style="color:green;" aria-hidden="true"></i>
                            <?php
                            $rest = '';
                            if (strlen($val->oaddress) > 10) {
                                $rest = substr($val->oaddress, 0, 10);
                            } else {
                                $rest = $val->oaddress;
                            }
                            if (strlen($val->oaddress) == 0) {
                                $rest = 'NA';
                            }
                            ?>
                            <a class="@if ($rest != 'NA')  @endif" data-toggle="tooltip"
                                data-placement="bottom" title="<?php echo $val->oaddress; ?>">
                                <?php echo $rest; ?>
                            </a>
                        </strong>
                        <br>
                        <strong><i class="fa fa-location-arrow" style="color:green;" aria-hidden="true"></i>
                            <?php
                            $rest = '';
                            if (strlen($val->oaddress2) > 10) {
                                $rest = substr($val->oaddress2, 0, 10);
                            } else {
                                $rest = $val->oaddress2;
                            }
                            if (strlen($val->oaddress2) == 0) {
                                $rest = 'NA';
                            }
                            ?>
                            <a class="@if ($rest != 'NA') btn-sm @endif" data-toggle="tooltip"
                                data-placement="bottom" title="<?php echo $val->oaddress2; ?>">
                                <?php echo $rest; ?>
                            </a>
                        </strong>
                        <br>
                    </td>
                    <td>
                        <a href="http://classic.mapquest.com/embed?zoom=5&amp;q={{ $val->destinationcity }}+{{ $val->destinationstate }}+{{ $val->destinationzip }}"
                            target="_blank">
                            <i class="fa fa-map-marker" style="color:red;" aria-hidden="true"></i>
                            {{ $val->destinationcity . '-' . $val->destinationstate . '-' . $val->destinationzip }}
                        </a><br>
                        <strong><i class="fa fa-location-arrow" style="color:red;" aria-hidden="true"></i>
                            <?php
                            $rest = '';
                            if (strlen($val->daddress) > 10) {
                                $rest = substr($val->daddress, 0, 10);
                            } else {
                                $rest = $val->daddress;
                            }
                            if (strlen($val->oaddress2) == 0) {
                                $rest = 'NA';
                            }
                            ?>
                            <a class="@if ($rest != 'NA')  @endif" data-toggle="tooltip"
                                data-placement="bottom" title="<?php echo $val->daddress; ?>">
                                <?php echo $rest; ?>
                            </a>
                        </strong>
                        <br>
                        <strong><i class="fa fa-location-arrow" style="color:red;" aria-hidden="true"></i>
                            <?php
                            $rest = '';
                            if (strlen($val->daddress2) > 10) {
                                $rest = substr($val->daddress2, 0, 10);
                            } else {
                                $rest = $val->daddress2;
                            }
                            if (strlen($val->oaddress2) == 0) {
                                $rest = 'NA';
                            }
                            ?>
                            <a class="@if ($rest != 'NA')  @endif" data-toggle="tooltip"
                                data-placement="bottom" title="<?php echo $val->daddress2; ?>">
                                <?php echo $rest; ?>
                            </a>
                        </strong>
                        <br>
                    </td>
                    <?php $ymk = explode('*^-', $val->ymk); ?>
                    <td>
                        @foreach ($ymk as $val2)
                            @if ($val2)
                                <span class="badge badge-pill badge-info mt-2">{{ $val2 }}</span><br>
                            @endif
                        @endforeach
                        <span class=""><?php echo get_car_or_heavy($val->car_type); ?></span><br>
                        <span class="badge badge-pill badge-default mt-2">By:
                            {{ get_user_name($val->order_taker_id) }}</span>
                    </td>

                    <?php $ophone = explode('*^', $val->ophone); ?>
                    <td>
                        @foreach ($ophone as $val3)
                            @php

                                $new = '(xxx) xxx-' . substr($val3, -4);
                            @endphp
                            @if ($val3)
                            @endif
                        @endforeach

                        <span class="badge badge-pill badge-default mt-2">{{ get_paid($val->id) }} : <span
                                class="badge badge-pill badge-info">{{ $val->payment }}</span> </span>
                        @if (!empty($val->asking_low) && $val->asking_low > 0)
                            <br>
                            <span class="badge badge-pill badge-default mt-2">Ask.Low: <span
                                    class="badge badge-pill badge-info">{{ intval($val->asking_low) }}</span></span>
                        @endif
                        <br>
                        <span class="badge badge-pill badge-default mt-2">Payment: <?php echo pay_status($val->paid_status); ?></span>
                        <br>
                        @if ($val->paneltype == 1)
                            <span class="badge badge-pill badge-default mt-2">Type: Phone Quote</span>
                        @elseif($val->paneltype == 3)
                            <span class="badge badge-pill badge-default mt-2">Type: Testing Quote</span>
                        @elseif($val->paneltype == 4)
                            <span class="badge badge-pill badge-default mt-2">Type: Panel Type 4 Quote</span>
                        @elseif($val->paneltype == 5)
                            <span class="badge badge-pill badge-default mt-2">Type: Panel Type 5 Quote</span>
                        @elseif($val->paneltype == 6)
                            <span class="badge badge-pill badge-default mt-2">Type: Panel Type 6 Quote</span>
                        @else
                            <span class="badge badge-pill badge-default mt-2">Type: Website Quote</span>
                        @endif
                    </td>
                    <td>
                        {{ $val->profit }}
                        @php
                            $totalprofit = $totalprofit + $val->profit;
                        @endphp
                    </td>
                    <td>
                        <span class="text-center pd-2 bd-l"> Created At:<br>{{ $val->created_at }}</span><br>
                        <span class="text-center pd-2 bd-l">Updated At:<br>{{ $val->updated_at }}</span><br>
                        <span
                            class="badge badge-pill badge-danger-light mt-2 fa-blink">{{ get_pstatus($val->pstatus) }}
                            @if (!empty($val->old_code))
                                - Old Quote
                            @endif
                        </span>
                        <br>
                        @if ($val->pstatus >= 11)
                            @if ($val->owes_money == 1)
                                <span class="badge badge-pill badge-default mt-2">Waiting for owes money</span>
                            @else
                                <span class="badge badge-pill badge-default mt-2">Owes money confirmed<br></span>
                            @endif
                        @endif
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $data->links() }}

    Total Profit: {{ $sumofprofit }}

</div>


<script>
    regain_call();
    regain_status();
    regain_report_modal();
</script>
