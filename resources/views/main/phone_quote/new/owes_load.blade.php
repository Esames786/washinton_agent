@include('partials.mainsite_pages.return_function')
<?php
$respn = trim("$_SERVER[REQUEST_URI]", '/');
if (isset($_GET['titlee'])) {
    $respn = $_GET['titlee'];
}
?>
<style>
    .table {
        /*color: rgb(0 0 0);*/
        width: 100%;
        max-width: 100%;
        margin-bottom: 1rem;
    }

    .table>thead>tr>td,
    .table>thead>tr>th {
        font-weight: 400;
        -webkit-transition: all .3s ease;
        font-size: 18px;
        color: rgb(0 0 0);
    }

    .table-data-align {
        display: flex;
        align-items: flex-end;
    }

    .table-btn-style {}

    .bg-white th {
        border: 1px solid #000000 !important;
    }

    .bg-white td {
        border: 1px solid #000000 !important;
    }
</style>
<div class="table-responsive tableResponsiveNew">
    {{-- example1 --}}
    <table class="table table-bordered table-sm col-lg-2 fs-18 text-center pd-2 bd-l" role="grid" aria-describedby="">
        <thead class="table-dark">
            <tr>
                <th width="15%">Pickup</th>
                <th width="15%">Delivery</th>
                <th>Details</th>
                <th width="15%">Co. Name</th>
                <th width="15%">Dates</th>
                <th width="10%">Actions</th>
            </tr>
        </thead>
        <tbody>
            @php
                $check_panel = check_panel();

                if ($check_panel == 1) {
                    $phoneaccess = explode(',', Auth::user()->emp_access_phone);
                } elseif ($check_panel == 3) {
                    $phoneaccess = explode(',', Auth::user()->emp_access_test);
                } else {
                    $phoneaccess = explode(',', Auth::user()->emp_access_web);
                }
            @endphp
            @foreach ($data as $key => $val)
                <tr class="parent1{{ $key }}">
                    <td>
                        <input type="hidden" class='order_id' value="{{ $val->id }}">
                        <input type="hidden" class="pstatus" value="{{ $val->pstatus }}">
                        <input type="hidden" class="client_email" value="{{ $val->oemail }}">
                        <input type="hidden" class="client_name" value="{{ $val->oname }}">
                        <input type="hidden" class="client_phone" value="{{ $val->mainPhNum ?? $val->main_ph }}">
                        <input type="hidden" class="location1" value="{{ $val->originzsc }}">
                        <input type="hidden" class="location2" value="{{ $val->destinationzsc }}">
                        <input type="hidden" class="origincity" value="{{ $val->origincity }}">
                        <input type="hidden" class="destinationcity" value="{{ $val->destinationcity }}">
                        <input type="hidden" class="pickup_date" value="{{ $val->pickup_date }}">
                        <input type="hidden" class="delivery_date" value="{{ $val->delivery_date }}">



                        <a href="https://www.google.com/maps/dir/{{ $val->originzip }},+USA/" target="_blank"
                            class="table1ancher">
                            <i class="fa fa-map-marker" style="color:green;" aria-hidden="true"></i>
                            <span> {{ $val->origincity . '-' . $val->originstate . '-' . $val->originzip }}</span>
                        </a>
                        @if (!empty($val->oaddress))
                            <a data-placement="bottom" class="table1ancher" title="{{ $val->oaddress }}">
                                <i class="fa fa-location-arrow" style="color:green;" aria-hidden="true"></i>
                                <span>{{ $val->oaddress }} </span>
                            </a>
                        @endif
                        <b class="ml-2">{{ $val->oauction }}</b>
                        @if (!empty($val->oauctiondate))
                            <br>
                            <span class="ml-2" style="font-size:13px;">Auction Date:
                                {{ \Carbon\Carbon::parse($val->oauctiondate)->format('M,d Y') }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="https://www.google.com/maps/dir/{{ $val->destinationzip }},+USA/" target="_blank"
                            class="table1ancher">
                            <i class="fa fa-map-marker" style="color:red;" aria-hidden="true"></i>
                            <span>
                                {{ $val->destinationcity . '-' . $val->destinationstate . '-' . $val->destinationzip }}</span>
                        </a>
                        @if ($val->daddress)
                            <a data-placement="bottom" title="{{ $val->daddress }}" class="table1ancher">
                                <i class="fa fa-location-arrow" style="color:red;" aria-hidden="true"></i>
                                <span> {{ $val->daddress }} </span>
                            </a>
                        @endif
                        <b class="ml-2">{{ $val->dauction }}</b>
                        @if (!empty($val->dauctiondate))
                            <br>
                            <span class="ml-2" style="font-size:13px;">Auction Date:
                                {{ \Carbon\Carbon::parse($val->dauctiondate)->format('M,d Y') }}</span>
                        @endif
                    </td>
                    <?php $ymk = explode('*^-', $val->ymk); ?>
                    <td class="table1td">
                        @foreach ($ymk as $val2)
                            @if ($val2)
                                {{ $val2 }} <br>
                            @endif
                        @endforeach
                        <b> Order ID# </b> <span><?php echo $val->id; ?></span>
                        <br>
                        <span> <?php echo get_car_or_heavy($val->car_type); ?> </span>
                        <br>
                        @if (isset($val->roro))
                            <b>{{ $val->roro }}</b><br>
                        @endif
                        <b> Owes:</b> {{ $val->owes_money > 0 ? ($val->owes ? '$' . $val->owes : 'N/A') : 'N/A' }}<br>
                        <b> Method:</b> {{ strtoupper(str_replace('_', ' ', $val->vehicle)) }}<br>
                        <?php
                        $ophone = explode('*^', $val->ophone);
                        $num = '';
                        if (isset($ophone[0])) {
                            $num = $ophone[0];
                        }
                        $order = \App\AutoOrder::where('id', '<>', $val->id)
                            ->where('ophone', 'LIKE', '%' . $num . '%')
                            ->first();
                        ?>
                        @if (isset($order->id))
                            <span class="badge badge-success text-light">Old Customer</span>
                        @else
                            <span class="badge badge-danger text-light">New Customer</span>
                        @endif
                    </td>
                    <td class="table1td">
                        <?php
                        if(isset($val->carrier->companyname))
                        {
                    ?>
                        <span><b>Company Name:</b> {{ $val->carrier->companyname ?? 'N/A' }}</span> <br>
                        <span><b>Company Email:</b> {{ $val->carrier->email ?? 'N/A' }}</span> <br>
                        <?php
                        }
                        else{
                    ?>
                        <span>N/A</span> <br>
                        <?php
                        }
                    ?>
                        @if ($val->owes > 0 && $val->owes_money > 0)
                            <b>Carrier Pay:</b><span>
                                @if (!empty($val->pay_carrier))
                                    ${{ $val->pay_carrier }}
                                @else
                                    N/A
                                @endif
                            </span> <br>
                        @endif
                        @if (in_array('60', $phoneaccess))
                            <?php
                            if(isset($val->carrier->companyphoneno))
                            {
                                if(in_array("61", $phoneaccess))
                                {
                                    $new = $val->carrier->companyphoneno;
                                }
                                else
                                {
                                    $digits = \App\PhoneDigit::first();
                                    $new = putX($digits->hide_digits,$digits->left_right_status,$val->carrier->companyphoneno);
                                }
                        ?>
                            <b>Company Phone: </b>
                            <span class="text-center pd-2 bd-l">
                                <a class="btn btn-outline-info  sms mb-2"
                                    onclick="call('{{ base64_encode($val->carrier->companyphoneno) }}')"
                                    style="padding: 3px 5px; font-size: 16px;"><i
                                        class="fa fa-phone"></i>&nbsp;{{ $new }}</a><br>
                            </span>
                            <?php

                            }
                        ?>
                            <?php
                            if(isset($val->carrier->driverphoneno))
                            {
                                if(in_array("61", $phoneaccess))
                                {
                                    $new = $val->carrier->driverphoneno;
                                }
                                else
                                {
                                    $digits = \App\PhoneDigit::first();
                                    $new = putX($digits->hide_digits,$digits->left_right_status,$val->carrier->driverphoneno);
                                }
                        ?>
                            <b>Driver Phone: </b>
                            <span class="text-center pd-2 bd-l">
                                <a class="btn btn-outline-info  sms mb-2"
                                    onclick="call('{{ base64_encode($val->carrier->driverphoneno) }}')"
                                    style="padding: 3px 5px; font-size: 16px;"><i
                                        class="fa fa-phone"></i>&nbsp;{{ $new }}</a><br>
                            </span>
                            <?php

                            }
                        ?>
                        @endif
                        @if (isset($driverphone->created_at))
                            {{ \Carbon\Carbon::parse($driverphone->created_at)->format('M,d Y h:i A') }}<br>
                        @endif
                        @if (auth()->user()->role == 1)
                            @if ($val->paneltype != 1)
                                @if (isset($val->ip_address))
                                    <br>
                                    <div>Ip : <b>{{ $val->ip_address }}</b></div>
                                @endif
                                @if (isset($val->ipcountry))
                                    <div>Address :
                                        <b>{{ isset($val->ippostal) ? $val->ippostal . ', ' : '' }}{{ isset($val->ipregion) ? $val->ipregion . ', ' : '' }}{{ isset($val->ipcity) ? $val->ipcity . ', ' : '' }}{{ isset($val->ipcountry) ? $val->ipcountry : '' }}</b>
                                        <br>
                                        <b>{{ isset($val->ip_details) ? $val->ip_details . ', ' : '' }}</b>
                                    </div>
                                @endif
                            @endif
                        @endif
                    </td>
                    <td class="table1td">
                        @if ($val->we_us_driver == 1)
                            <b> Who Pay:</b> <span class="badge badge-warning" style="font-size: 13px">Driver To
                                Us</span>
                        @elseif($val->we_us_driver == 2)
                            <b> Who Pay:</b> <span class="badge badge-info" style="font-size: 13px">We to Driver</span>
                        @endif
                        <b> Created At:</b>
                        <br>{{ \Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i A') }}<br>
                        <b>Updated At:</b> <br>{{ \Carbon\Carbon::parse($val->updated_at)->format('M,d Y h:i A') }}<br>
                        <span class="badge
                    <?php
                    switch ($val->paneltype) {
                        case 1:
                            echo 'badge-default my-2';
                            break;
                        case 2:
                            echo 'badge-secondary my-2';
                            break;
                        case 3:
                            echo 'badge-info my-2';
                            break;
                        case 4:
                            echo 'badge-warning my-2';
                            break;
                        case 5:
                            echo 'badge-danger my-2';
                            break;
                        case 6:
                            echo 'badge-success my-2';
                            break;
                        default:
                            echo 'badge-primary my-2';
                    }
                    ?>">
                            {{ $val->paneltype == 1
                            ? 'PHONE QUOTE'
                            : ($val->paneltype == 2
                            ? 'WEB QUOTE'
                            : ($val->paneltype == 3
                            ? 'TESTING QUOTE'
                            : ($val->paneltype == 4
                            ? 'PANEL TYPE 4 QUOTE'
                            : ($val->paneltype == 5
                            ? 'PANEL TYPE 5 QUOTE'
                            : ($val->paneltype == 6
                            ? 'PANEL TYPE 6 QUOTE'
                            : 'UNKNOWN QUOTE'))))) }}
                        </span><br> <span class="text-center pd-2 bd-l mt-2"><?php echo get_pstatus2($val->pstatus); ?><br>
                    </td>
                    <td>
                        <div class="btn-list">
                            <button type="button" class="btn btn-outline-info btn-sm w-100" data-toggle="modal"
                                onclick="$('#order_owes_id').val({{ $val->id }})" data-target="#oweshistory"
                                title="Update History">
                                Update History
                            </button>
                            <button type="button" class="btn btn-outline-info btn-sm w-100" data-toggle="modal"
                                onclick="owesviewhistory({{ $val->id }})" data-target="#owesviewhistory"
                                title="View History">
                                View History
                            </button>
                            <a class="btn btn-outline-info btn-sm w-100"
                                href="/owes_money_update/{{ $val->id }}" title="Edit Payment">
                                <i class="fa fa-credit-card "></i> Edit Payment
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-between">
        <div class="text-secondary my-auto">
            Showing {{ $data->firstItem() ?? 0 }} to {{ $data->lastItem() ?? 0 }} from total {{ $data->total() }}
            entries
        </div>
        <div>
            {{ $data->links() }}
        </div>

    </div>

</div>
<style>
    .tx-white {
        color: white !important;
    }

    .badge-orange {
        color: #212529;
        background-color: #F49917;
    }
</style>

<script src="{{ url('assets/js/jquery-3.5.1.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
<script src="{{ url('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script>
    function call(num) {
        var num1 = atob(num);
        //  var newNum = num1.replace(/[- )(]/g,'');
        //  console.log(num1);
        window.location.href = 'rcmobile://call/?number=' + num1;
        //  window.location.href = 'tel://'+newNum;
        var id = $("#orderId").val();
        $.ajax({
            url: "{{ url('/notRes') }}",
            type: "GET",
            data: {
                id: id
            },
            success: function(res) {
                console.log(res);
            }
        });
    }

    function msg(num) {
        var num1 = atob(num);
        window.location.href = 'rcmobile://sms/?number=' + num1;
    }

    function get_prev(o_zip1, d_zip1) {


        var ozip = o_zip1;
        var dzip = d_zip1;


        if (!ozip || !dzip) {
            not7();

        } else {
            ozip = ozip.split(", ");
            dzip = dzip.split(", ");

            var url = `/old_previous?ocity=${btoa(ozip[0])}&dcity=${btoa(dzip[0])}`;
            window.open(url, 'Previous Orders',
                'height=600,width=800,left=300,top=100,toolbar=No,location=No,scrollbars=Yes,status=No,resizable=Yes,fullscreen=No,directories=No,menubar=No,copyhistory=No'
                );

        }

    }

    function history(order_id, ophone) {



        if (!order_id || !ophone) {
            not7();

        } else {

            var url = `/get_last_5_2?phone_no=${btoa(ophone)}&order_id=${btoa(order_id)}`;
            window.open(url, 'Previous Orders',
                'height=400,width=600,left=350,top=250,toolbar=No,location=No,scrollbars=Yes,status=No,resizable=Yes,fullscreen=No,directories=No,menubar=No,copyhistory=No'
                );

        }

    }

    $(".readmore").on('click', function() {
        $(this).parents('.less').hide();
        $(this).parents('.less').siblings('.more').show();
    });
    $(".readless").on('click', function() {
        $(this).parents('.more').hide();
        $(this).parents('.more').siblings('.less').show();
    });
</script>
