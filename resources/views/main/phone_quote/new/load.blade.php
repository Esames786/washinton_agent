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
                <th width="15%">Customer/Payment</th>
                <th width="15%">Dates</th>
                <th width="10%">Actions</th>
                @if (
                    \Request::is('listed') ||
                        \Request::is('dispatch') ||
                        \Request::is('picked_up_approval') ||
                        \Request::is('picked_up') ||
                        \Request::is('deliver_approval') ||
                        \Request::is('delivered') ||
                        \Request::is('completed'))
                    <th width="10">View</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @php
                $check_panel = check_panel();
                $check_call = check_call();

                if ($check_panel == 1) {
                    $phoneaccess = explode(',', Auth::user()->emp_access_phone);
                } elseif ($check_panel == 3) {
                    $phoneaccess = explode(',', Auth::user()->emp_access_test);
                } else {
                    $phoneaccess = explode(',', Auth::user()->emp_access_web);
                }
                $phoneaccessJson = json_encode($phoneaccess);
                if ($check_panel == 3) {
                    // dd($phoneaccessJson);
                }
                $actionaccess = explode(',', Auth::user()->emp_access_action);
            @endphp

            @foreach ($data as $key => $val)
                <tr class="parent1{{ $key }}">
                    <td>
                        <input type="hidden" class='check_call_type' value="{{ $check_call }}">
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
                        <input type="hidden" class="profile_card_update" value="{{ $val->profile_card_update }}">



                        <a href="https://www.google.com/maps/place/{{ $val->originzip }},+USA/" target="_blank"
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
                        @if (!empty($val->oacutionaccountname))
                            <br>
                            <b class="ml-2">Account Name:</b><br> <span
                                class="ml-2">{{ $val->oacutionaccountname }}</span>
                        @endif
                        @if (!empty($val->oauctiondate))
                            <br>
                            <span class="ml-2" style="font-size:13px;">Auction Date:
                                {{ \Carbon\Carbon::parse($val->oauctiondate)->format('M,d Y') }}</span>
                        @endif

                        @if (in_array('121', $phoneaccess))
                            <?php $ophone = explode('*^', $val->ophone); ?>
                            @foreach ($ophone as $val3)
                                <?php
                                if (in_array('67', $phoneaccess)) {
                                    $new = $val3;
                                } else {
                                    $digits = \App\PhoneDigit::first();

                                    $new = putX($digits->hide_digits, $digits->left_right_status, $val3);
                                }

                                ?>
                                @if ($val3)
                                    <?php
                                    $access = \App\OrderTakerQouteAccess::where('ot_ids', Auth::user()->id)
                                        ->where('calling_status', 1)
                                        ->whereDate('from_date', '>=', date('Y-m-d'))
                                        ->whereDate('to_date', '<=', date('Y-m-d'))
                                        ->first();
                                    ?>
                                    <input type="hidden" id="orderId" value="{{ $val->id }}" />
                                    @if (Auth::user()->order_taker_quote == 2)
                                        @if (($val->pstatus >= 12 && $val->pstatus <= 15) || $val->pstatus == 19)
                                            @if (isset($access->id))
                                                <span class="text-center pd-2 bd-l">
                                                    <a onclick="call('{{ base64_encode($val3) }}')"
                                                        class="btn btn-outline-info  mobile count_user mb-2"
                                                        style="padding: 3px 5px; font-size: 16px;"><i
                                                            class="fa fa-phone"></i>&nbsp;{{ $new }}</a><br>
                                                </span>
                                                <span class="text-center pd-2 bd-l">
                                                    <a class="btn btn-outline-info  sms mb-2"
                                                        onclick="msg('{{ base64_encode($val3) }}')"
                                                        style="padding: 3px 5px; font-size: 16px;"><i
                                                            class="fa fa-envelope"></i>&nbsp;{{ $new }}</a><br>
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-center pd-2 bd-l">
                                                <a onclick="call('{{ base64_encode($val3) }}')"
                                                    class="btn btn-outline-info  mobile count_user mb-2"
                                                    style="padding: 3px 5px; font-size: 16px;"><i
                                                        class="fa fa-phone"></i>&nbsp;{{ $new }}</a><br>
                                            </span>
                                            <span class="text-center pd-2 bd-l">
                                                <a class="btn btn-outline-info  sms mb-2"
                                                    onclick="msg('{{ base64_encode($val3) }}')"
                                                    style="padding: 3px 5px; font-size: 16px;"><i
                                                        class="fa fa-envelope"></i>&nbsp;{{ $new }}</a><br>
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-center pd-2 bd-l">
                                            <a onclick="call('{{ base64_encode($val3) }}')"
                                                class="btn btn-outline-info  mobile count_user mb-2"
                                                style="padding: 3px 5px; font-size: 16px;"><i
                                                    class="fa fa-phone"></i>&nbsp;{{ $new }}</a><br>
                                        </span>
                                        <span class="text-center pd-2 bd-l">
                                            <a class="btn btn-outline-info  sms mb-2"
                                                onclick="msg('{{ base64_encode($val3) }}')"
                                                style="padding: 3px 5px; font-size: 16px;"><i
                                                    class="fa fa-envelope"></i>&nbsp;{{ $new }}</a><br>
                                        </span>
                                    @endif
                                @endif
                            @endforeach
                        @endif

                        @if ($val->link != null && $val->link != '/')
                            Available at Auction: Yes <a href="{{ $val->link }}" target="_blank">View Link</a>
                        @endif
                    </td>
                    <td>
                        @if (isset($val->roro))
                            <a href="https://www.google.com/maps/place/{{ $val->destinationzsc }},+USA/"
                                target="_blank" class="table1ancher">
                                <i class="fa fa-map-marker" style="color:red;" aria-hidden="true"></i>
                                <span>
                                    {{ $val->destinationzsc }}</span>
                            </a>
                        @else
                            <a href="https://www.google.com/maps/place/{{ $val->destinationzip }},+USA/"
                                target="_blank" class="table1ancher">
                                <i class="fa fa-map-marker" style="color:red;" aria-hidden="true"></i>
                                <span>
                                    {{ $val->destinationcity . '-' . $val->destinationstate . '-' . $val->destinationzip }}</span>
                            </a>
                        @endif
                        @if ($val->daddress)
                            <a data-placement="bottom" title="{{ $val->daddress }}" class="table1ancher">
                                <i class="fa fa-location-arrow" style="color:red;" aria-hidden="true"></i>
                                <span> {{ $val->daddress }} </span>
                            </a>
                        @endif
                        <b class="ml-2">{{ $val->dauction }}</b>
                        @if (!empty($val->dacutionaccountname))
                            <br>
                            <b class="ml-2">Account Name:</b><br> <span
                                class="ml-2">{{ $val->dacutionaccountname }}</span>
                        @endif
                        @if (!empty($val->dauctiondate))
                            <br>
                            <span class="ml-2" style="font-size:13px;">Auction Date:
                                {{ \Carbon\Carbon::parse($val->dauctiondate)->format('M,d Y') }}</span>
                        @endif
                        @if (in_array('122', $phoneaccess))
                            <?php $dphone = explode('*^', $val->dphone); ?>
                            @foreach ($dphone as $val3)
                                <?php
                                if (in_array('67', $phoneaccess)) {
                                    $new = $val3;
                                } else {
                                    $digits = \App\PhoneDigit::first();

                                    $new = putX($digits->hide_digits, $digits->left_right_status, $val3);
                                }

                                ?>
                                @if ($val3)
                                    <?php
                                    $access = \App\OrderTakerQouteAccess::where('ot_ids', Auth::user()->id)
                                        ->where('calling_status', 1)
                                        ->whereDate('from_date', '>=', date('Y-m-d'))
                                        ->whereDate('to_date', '<=', date('Y-m-d'))
                                        ->first();
                                    ?>
                                    <input type="hidden" id="orderId" value="{{ $val->id }}" />
                                    @if (Auth::user()->order_taker_quote == 2)
                                        @if (($val->pstatus >= 12 && $val->pstatus <= 15) || $val->pstatus == 19)
                                            @if (isset($access->id))
                                                <span class="text-center pd-2 bd-l">
                                                    <a onclick="call('{{ base64_encode($val3) }}')"
                                                        class="btn btn-outline-info  mobile count_user mb-2"
                                                        style="padding: 3px 5px; font-size: 16px;"><i
                                                            class="fa fa-phone"></i>&nbsp;{{ $new }}</a><br>
                                                </span>
                                                <span class="text-center pd-2 bd-l">
                                                    <a class="btn btn-outline-info  sms mb-2"
                                                        onclick="msg('{{ base64_encode($val3) }}')"
                                                        style="padding: 3px 5px; font-size: 16px;"><i
                                                            class="fa fa-envelope"></i>&nbsp;{{ $new }}</a><br>
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-center pd-2 bd-l">
                                                <a onclick="call('{{ base64_encode($val3) }}')"
                                                    class="btn btn-outline-info  mobile count_user mb-2"
                                                    style="padding: 3px 5px; font-size: 16px;"><i
                                                        class="fa fa-phone"></i>&nbsp;{{ $new }}</a><br>
                                            </span>
                                            <span class="text-center pd-2 bd-l">
                                                <a class="btn btn-outline-info  sms mb-2"
                                                    onclick="msg('{{ base64_encode($val3) }}')"
                                                    style="padding: 3px 5px; font-size: 16px;"><i
                                                        class="fa fa-envelope"></i>&nbsp;{{ $new }}</a><br>
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-center pd-2 bd-l">
                                            <a onclick="call('{{ base64_encode($val3) }}')"
                                                class="btn btn-outline-info  mobile count_user mb-2"
                                                style="padding: 3px 5px; font-size: 16px;"><i
                                                    class="fa fa-phone"></i>&nbsp;{{ $new }}</a><br>
                                        </span>
                                        <span class="text-center pd-2 bd-l">
                                            <a class="btn btn-outline-info  sms mb-2"
                                                onclick="msg('{{ base64_encode($val3) }}')"
                                                style="padding: 3px 5px; font-size: 16px;"><i
                                                    class="fa fa-envelope"></i>&nbsp;{{ $new }}</a><br>
                                        </span>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </td>
                    <?php $ymk = explode('*^-', $val->ymk); ?>
                    <?php
                    $standardized = str_replace('*^-', '*^', $val->ymk);
                    $ymk = explode('*^', $standardized);
                    // dd($val->ymk, $standardized, $ymk);
                    ?>
                    <td class="table1td">
                        @if ($val->car_type == 3 && $val->freight)
                            {{ $val->freight->commodity_detail . ',' . $val->freight->commodity_unit }} <br>
                        @else
                            @foreach ($ymk as $val2)
                                @if ($val2)
                                    {{ $val2 }} <br>
                                @endif
                            @endforeach
                        @endif
                        <b> Miles: </b> <span>{{ $val->miles > 0 ? $val->miles : 'N/A' }}</span>
                        <br>
                        <b> Order ID# </b> <span><?php echo $val->id; ?></span>
                        <br>
                        <b> Creator:</b>
                        <span>{{ $val->order_taker_id ? get_user_name($val->order_taker_id) : ($val->filterHistory ? get_user_name($val->filterHistory->user_id) : '') }}</span>
                        <br>
                        @if (in_array('71', $phoneaccess))
                            @if (isset($val->u_id))
                                <b>Booker:</b> <span>{{ get_user_name($val->u_id) }}</span><br>
                            @endif
                        @endif
                        @if (isset($val->dispatcher_id))
                            <b>Assign To:</b> <span>{{ get_user_name($val->dispatcher_id) }}</span><br>
                        @else
                            @if ($val->pstatus > 8 && $val->pstatus < 15)
                                <b>Assign To:</b>
                                @if (in_array('76', $phoneaccess))
                                    <span type="button" class="badge badge-danger rounded" data-toggle="modal"
                                        onclick="$('#assigning_dispatcher_order').val({{ $val->id }})"
                                        data-target="#assignToDispatcher">Not Assigned</span><br>
                                @else
                                    <span>Not Assigned</span><br>
                                @endif
                            @endif
                        @endif
                        @if ($val->pstatus == 13 && !empty($val->completed_sheet) && isset($val->completed_sheet[0]))
                            </br>
                            <b>Review:</b> <span>{{ $val->completed_sheet[0]->review }}</span><br>
                        @endif
                        <span> <?php echo get_car_or_heavy($val->car_type); ?> </span>
                        <br>
                        @if (isset($val->roro))
                            <b>{{ $val->roro }}</b><br>
                        @endif
                        @if ($check_panel == 2 && $val->source != null)
                            <b> Source:</b>
                            <span
                                class="badge <?php echo $val->source == 'DayDispatch' ? 'badge-primary my-2' : 'badge-primary my-2'; ?>">{{ $val->source == 'DayDispatch' ? 'DD' : '-' }}</span>
                        @endif

                        </br>
                        <span class="badge
                        <?php
                        if ($val->paneltype == 1) {
                            echo 'badge-secondary my-2';
                        } elseif ($val->paneltype == 2) {
                            echo 'badge-primary my-2';
                        } elseif ($val->paneltype == 3) {
                            echo 'badge-info my-2';
                        } elseif ($val->paneltype == 4) {
                            echo 'badge-primary my-2';
                        } elseif ($val->paneltype == 5) {
                            echo 'badge-primary my-2';
                        } elseif ($val->paneltype == 6) {
                            echo 'badge-primary my-2';
                        } else {
                            echo 'badge-secondary my-2';
                        }
                        ?>">
                            {{ $val->paneltype == 1
                                ? 'Auction'
                                : ($val->paneltype == 2
                                    ? 'ProMax'
                                    : ($val->paneltype == 3
                                        ? 'Testing Quote'
                                        : ($val->paneltype == 4
                                            ? 'Shipa1 Website'
                                            : ($val->paneltype == 5
                                                ? 'Panel Type 5 Quote'
                                                : ($val->paneltype == 6
                                                    ? 'Panel Type 6 Quote'
                                                    : 'Auction'))))) }}
                        </span>

                        <div class="message-feed media m-0 p-0">
                            <div class="media-body">
                                <div class="mf-content">
                                    @if (isset($val->latestHistory))
                                        <h6>User: {{ get_user_name($val->latestHistory->userId) }}</h6>
                                        @if (isset($val->latestHistory->mistaker))
                                            <h6>Mistaker: {{ $val->latestHistory->mistaker }}</h6>
                                        @endif
                                        @if (isset($val->latestHistory->agree_disagree))
                                            <h6>Admin Remarks: {{ $val->latestHistory->agree_disagree }}</h6>
                                        @endif
                                        <?php
                                        if (strlen($val->latestHistory->history) > 150) {
                                            $alldesc = $val->latestHistory->history . '  <span class="text-muted readless" style="cursor:pointer;">Read Less</span>';
                                            $desc = substr($val->latestHistory->history, 0, 150) . '... <span class="text-muted readmore" style="cursor:pointer;">Read More</span>';
                                        } else {
                                            $desc = $val->latestHistory->history;
                                        }
                                        ?>
                                        <div class="less">
                                            {!! html_entity_decode($desc) !!}
                                        </div>
                                        <?php
                                            if(strlen($val->latestHistory->history) > 150)
                                            {
                                        ?>
                                        <div style="display:none;" class="more">
                                            {!! html_entity_decode($alldesc) !!}
                                        </div>
                                        <?php
                                            }
                                        ?>
                                        <h6><strong class="mf-date"><i class="fa fa-clock-o"></i>
                                                {{ \Carbon\Carbon::parse($val->latestHistory->created_at)->format('M,d Y h:i A') }}</strong>
                                        </h6>
                                    @else
                                        <h6>No Histroy Found!</h6>
                                    @endif
                                </div>

                            </div>
                        </div>
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

                        <?php $ophone = explode('*^', $val->ophone); ?>
                        @if (Auth::user()->userRole->name == 'Admin' ||
                                Auth::user()->userRole->name == 'Manager' ||
                                Auth::user()->userRole->name == 'Order Taker' ||
                                Auth::user()->userRole->name == 'CSR' ||
                                Auth::user()->userRole->name == 'Seller Agent' ||
                                Auth::user()->userRole->name == 'Delivery Boy' ||
                                Auth::user()->userRole->name == 'O.T TEAM LEAD' ||
                                Auth::user()->userRole->name == 'QA')
                            <span><b>Name:</b> <?php echo $val->oname; ?></span> <br>
                            <b>Driver-Price:</b><span>
                                @if (!empty($val->driver_price))
                                    ${{ $val->driver_price }}
                                @else
                                    N/A
                                @endif
                            </span>
                            <br>
                            @php
                                if ($val->pstatus <= 6) {
                                    $title = '<b>Offer-Price</b>';
                                } else {
                                    $title = '<b>Book-Price</b>';
                                }
                            @endphp
                            <?php echo $title; ?>: @if (!empty($val->payment))
                                ${{ $val->payment }}
                            @else
                                N/A
                            @endif <br>
                        @endif
                        @if (Auth::user()->userRole->name == 'Admin' ||
                                Auth::user()->userRole->name == 'Manager' ||
                                Auth::user()->userRole->name == 'Order Taker' ||
                                Auth::user()->userRole->name == 'CSR' ||
                                Auth::user()->userRole->name == 'Seller Agent')
                            <b>Start-Price:</b><span>
                                @if (!empty($val->start_price))
                                    ${{ $val->start_price }}
                                @else
                                    N/A
                                @endif
                            </span> <br>
                            <?php
                            $coupon_price = 0;
                            if (isset($val->coupon_id)) {
                                $coupon = \App\Coupon::find($val->coupon_id);
                                if (isset($coupon->id)) {
                                    $coupon_price = $coupon->coupon_price ?? 0;
                                }
                            }
                            ?>
                            @if (!empty($val->payment))
                                @if ($coupon_price > 0)
                                    <br><b>Coupon-Price:</b><span> ${{ $coupon_price }}</span>
                                    <br><b>Remaining-Price:</b><span> ${{ $val->payment - $coupon_price }}</span>
                                @endif
                            @endif
                        @endif
                        <b>Listed-Price:</b>
                        @if (!empty($val->listed_price))
                            ${{ $val->listed_price }}
                        @else
                            N/A
                        @endif
                        @if (!empty($val->asking_low) && $val->asking_low > 0)
                            <br>
                            <span class="badge badge-pill  badge-sm px-0"><b>Ask.Low:</b>
                                ${{ intval($val->asking_low) }}</span>
                        @endif
                        <br><span class="badge badge-sm p-0"><b>Payment:</b> <?php echo pay_status($val->paid_status); ?></span>
                        <br><span class="badge badge-sm p-0"><b>Method:</b>
                            {{ strtoupper(str_replace('_', ' ', $val->vehicle)) }}</span>
                        <br>
                        <br><span class="badge badge-sm p-0"><b>Customer Type:</b></span>
                         <span>{!! check_old_or_new($val->id, $ophone[0]) !!}</span>

                        <br>

                        <div style="display:flex; gap:10px;">
                            <a class="btn btn-primary btn-sm my-2"
                                onclick="get_prev('{{ $val->originstate }}','{{ $val->destinationstate }}')"
                                target="_blank">PR</a>
                            <a class="btn btn-primary btn-sm my-2"
                                onclick="history('{{ $val->id }}','{{ $ophone[0] }}')"
                                target="_blank">History</a>
                        </div>
                        @if (in_array('42', $phoneaccess))
                            @foreach ($ophone as $val3)
                                <?php
                                if (in_array('67', $phoneaccess)) {
                                    $new = $val3;
                                } else {
                                    $digits = \App\PhoneDigit::first();

                                    $new = putX($digits->hide_digits, $digits->left_right_status, $val3);
                                }

                                ?>
                                @if ($val3)
                                    <?php
                                    $access = \App\OrderTakerQouteAccess::where('ot_ids', Auth::user()->id)
                                        ->where('calling_status', 1)
                                        ->whereDate('from_date', '>=', date('Y-m-d'))
                                        ->whereDate('to_date', '<=', date('Y-m-d'))
                                        ->first();
                                    ?>
                                    <input type="hidden" id="orderId" value="{{ $val->id }}" />
                                    @if (Auth::user()->order_taker_quote == 2)
                                        @if (($val->pstatus >= 12 && $val->pstatus <= 15) || $val->pstatus == 19)
                                            @if (isset($access->id))
                                                <span class="text-center pd-2 bd-l">
                                                    <a onclick="call('{{ base64_encode($val3) }}')"
                                                        class="btn btn-outline-info  mobile count_user mb-2"
                                                        style="padding: 3px 5px; font-size: 16px;"><i
                                                            class="fa fa-phone"></i>&nbsp;{{ $new }}</a><br>
                                                </span>
                                                <span class="text-center pd-2 bd-l">
                                                    <a class="btn btn-outline-info  sms mb-2"
                                                        onclick="msg('{{ base64_encode($val3) }}')"
                                                        style="padding: 3px 5px; font-size: 16px;"><i
                                                            class="fa fa-envelope"></i>&nbsp;{{ $new }}</a><br>
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-center pd-2 bd-l">
                                                <a onclick="call('{{ base64_encode($val3) }}')"
                                                    class="btn btn-outline-info  mobile count_user mb-2"
                                                    style="padding: 3px 5px; font-size: 16px;"><i
                                                        class="fa fa-phone"></i>&nbsp;{{ $new }}</a><br>
                                            </span>
                                            <span class="text-center pd-2 bd-l">
                                                <a class="btn btn-outline-info  sms mb-2"
                                                    onclick="msg('{{ base64_encode($val3) }}')"
                                                    style="padding: 3px 5px; font-size: 16px;"><i
                                                        class="fa fa-envelope"></i>&nbsp;{{ $new }}</a><br>
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-center pd-2 bd-l">
                                            <a onclick="call('{{ base64_encode($val3) }}')"
                                                class="btn btn-outline-info  mobile count_user mb-2"
                                                style="padding: 3px 5px; font-size: 16px;"><i
                                                    class="fa fa-phone"></i>&nbsp;{{ $new }}</a><br>
                                        </span>
                                        <span class="text-center pd-2 bd-l">
                                            <a class="btn btn-outline-info  sms mb-2"
                                                onclick="msg('{{ base64_encode($val3) }}')"
                                                style="padding: 3px 5px; font-size: 16px;"><i
                                                    class="fa fa-envelope"></i>&nbsp;{{ $new }}</a><br>
                                        </span>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                        @if (in_array('60', $phoneaccess))
                            <?php
                            $driverphone = \App\carrier::where('orderId',$val->id)->where('who_pickup',1)->select('id','driverphoneno','companyphoneno')->orderBy('created_at','DESC')->get();
                            if(!isset($driverphone[0]))
                            {
                                $driverphone = \App\carrier::where('orderId',$val->id)->select('id','driverphoneno','companyphoneno')->orderBy('created_at','DESC')->get();
                            }
                            $i = 0;
                            foreach($driverphone as $kkk => $vvv)
                            {
                                if(isset($vvv->companyphoneno))
                                {
                                    if(in_array("61", $phoneaccess))
                                    {
                                        $new = $vvv->companyphoneno;
                                    }
                                    else
                                    {
                                        $digits = \App\PhoneDigit::first();
                                        $new = putX($digits->hide_digits,$digits->left_right_status,$vvv->companyphoneno);
                                    }

                                    if($i == 0)
                                    {
                                        echo '<h5 class="text-left mb-0 mt-2">C.Phone</h5>';
                                    }
                        ?>
                            <span class="text-center pd-2 bd-l">
                                <a class="btn btn-outline-info  sms mb-2"
                                    onclick="call2('{{ base64_encode($vvv->companyphoneno) }}')"
                                    style="padding: 3px 5px; font-size: 16px;"><i
                                        class="fa fa-phone"></i>&nbsp;{{ $new }}</a><br>
                            </span>
                            <?php

                                }
                                if(isset($vvv->driverphoneno))
                                {
                                    if(in_array("61", $phoneaccess))
                                    {
                                        $new = $vvv->driverphoneno;
                                    }
                                    else
                                    {
                                        $digits = \App\PhoneDigit::first();
                                        $new = putX($digits->hide_digits,$digits->left_right_status,$vvv->driverphoneno);
                                    }

                                    if($i == 0)
                                    {
                                        echo '<h5 class="text-left mb-0 mt-2">D.Phone</h5>';
                                    }
                        ?>
                            <span class="text-center pd-2 bd-l">
                                <a class="btn btn-outline-info  sms mb-2"
                                    onclick="call2('{{ base64_encode($vvv->driverphoneno) }}')"
                                    style="padding: 3px 5px; font-size: 16px;"><i
                                        class="fa fa-phone"></i>&nbsp;{{ $new }}</a><br>
                            </span>
                            <?php

                                }
                                $i++;
                            }
                        ?>
                            <?php
                            $driverphone = \App\SheetDetails::where('orderId',$val->id)->where(function($q){
                                $q->where('pstatus','=','11')->orWhere('pstatus','=','12');
                            })->where('driver_no','<>',NULL)->select('driver_no','driver_no2','driver_no3','driver_no4')->groupBy(['driver_no','driver_no2','driver_no3','driver_no4'])->get();
                            $j = 0;
                            foreach($driverphone as $kkk => $vvv)
                            {
                                if(isset($vvv->driver_no))
                                {
                                    if(in_array("61", $phoneaccess))
                                    {
                                        $new = $vvv->driver_no;
                                    }
                                    else
                                    {
                                        $digits = \App\PhoneDigit::first();
                                        $new = putX($digits->hide_digits,$digits->left_right_status,$vvv->driver_no);
                                    }
                                    if($j == 0)
                                    {
                                        echo '<h5 class="text-left mb-0 mt-2">D.Phone</h5>';
                                    }
                        ?>
                            <span class="text-center pd-2 bd-l">
                                <a class="btn btn-outline-info  sms mb-2"
                                    onclick="call2('{{ base64_encode($vvv->driver_no) }}')"
                                    style="padding: 3px 5px; font-size: 16px;"><i
                                        class="fa fa-phone"></i>&nbsp;{{ $new }}</a><br>
                            </span>
                            <?php
                                }
                                if(isset($vvv->driver_no2))
                                {
                                    if(in_array("61", $phoneaccess))
                                    {
                                        $new = $vvv->driver_no2;
                                    }
                                    else
                                    {
                                        $digits = \App\PhoneDigit::first();
                                        $new = putX($digits->hide_digits,$digits->left_right_status,$vvv->driver_no2);
                                    }
                        ?>
                            <span class="text-center pd-2 bd-l">
                                <a class="btn btn-outline-info  sms mb-2"
                                    onclick="call2('{{ base64_encode($vvv->driver_no2) }}')"
                                    style="padding: 3px 5px; font-size: 16px;"><i
                                        class="fa fa-phone"></i>&nbsp;{{ $new }}</a><br>
                            </span>
                            <?php
                                }
                                if(isset($vvv->driver_no3))
                                {
                                    if(in_array("61", $phoneaccess))
                                    {
                                        $new = $vvv->driver_no3;
                                    }
                                    else
                                    {
                                        $digits = \App\PhoneDigit::first();
                                        $new = putX($digits->hide_digits,$digits->left_right_status,$vvv->driver_no3);
                                    }
                        ?>
                            <span class="text-center pd-2 bd-l">
                                <a class="btn btn-outline-info  sms mb-2"
                                    onclick="call2('{{ base64_encode($vvv->driver_no3) }}')"
                                    style="padding: 3px 5px; font-size: 16px;"><i
                                        class="fa fa-phone"></i>&nbsp;{{ $new }}</a><br>
                            </span>
                            <?php
                                }
                                if(isset($vvv->driver_no4))
                                {
                                    if(in_array("61", $phoneaccess))
                                    {
                                        $new = $vvv->driver_no4;
                                    }
                                    else
                                    {
                                        $digits = \App\PhoneDigit::first();
                                        $new = putX($digits->hide_digits,$digits->left_right_status,$vvv->driver_no4);
                                    }
                        ?>
                            <span class="text-center pd-2 bd-l">
                                <a class="btn btn-outline-info  sms mb-2"
                                    onclick="call2('{{ base64_encode($vvv->driver_no4) }}')"
                                    style="padding: 3px 5px; font-size: 16px;"><i
                                        class="fa fa-phone"></i>&nbsp;{{ $new }}</a><br>
                            </span>
                            <?php
                                }
                            }
                        ?>
                        @endif
                    </td>
                    <td class="table1td">
                        <b> Created At:</b>
                        <br>{{ \Carbon\Carbon::parse($val->created_at)->format('M,d Y') }}<br>{{ \Carbon\Carbon::parse($val->created_at)->format('h:i A') }}<br><br>
                        <b>Updated At:</b>
                        <br>{{ \Carbon\Carbon::parse($val->updated_at)->format('M,d Y') }}<br>{{ \Carbon\Carbon::parse($val->updated_at)->format('h:i A') }}<br><br>
                        <span class="text-center pd-2 bd-l mt-2">
                            @if ($val->pstatus == 11 && $val->approve_pickup == 0)
                                <span class="badge badge-dark txt-white">Pickup Approval</span>
                            @elseif($val->pstatus == 12 && $val->approve_deliver == 0)
                                <span class="badge badge-amber txt-white">Delivery Approval</span>
                            @elseif($val->pstatus == 12 && $val->approve_deliver == 2)
                                <span class="badge badge-amber txt-white">Schedule For Delivery</span>
                            @else
                                <?php echo get_pstatus2($val->pstatus); ?>
                            @endif
                            @if (!empty($val->old_code))
                                - Old Quote
                            @endif
                        </span>
                        <br><br>
                        {{-- @if (!empty($val->t_q_late))

                            <span class="badge badge-pill badge-default txt-white">
                                {{ $val->t_q_late }} days late time quote
                            </span>

                        @endif --}}
                        @if ($val->pstatus > 0 && $val->pstatus < 13)
                            <span class="badge badge-pill badge-default txt-white">
                                {{ \Carbon\Carbon::parse($val->created_at)->addHours(9)->diffForHumans() }} late time
                                quote
                            </span>
                        @endif
                        @if ($val->pstatus == 11)
                            @if ($val->storage_id > 0)
                                @if ($val->pickup_carrier_id == 0)
                                    <span class="badge badge-danger mt-2">In storage yard</span>
                                    <br>
                                @endif
                            @endif
                        @endif
                        @if (in_array('65', $phoneaccess))
                            <?php
                            $verified = \App\QaVerifyHistory::where('order_id', $val->id)
                                ->where('verify', 1)
                                ->count();
                            $negative = \App\QaVerifyHistory::where('order_id', $val->id)
                                ->where('negative', 1)
                                ->count();
                            ?>
                            <span
                                class="badge badge-{{ $verified == 0 ? 'danger' : 'success' }} mt-2 text-light">{{ $verified }}
                                times verified</span><br>
                            <span
                                class="badge badge-{{ $negative == 0 ? 'success' : 'danger' }} mt-2 text-light">{{ $negative }}
                                times negative</span>
                        @endif
                        @if ($val->pstatus == 9)
                            @if ($val->relist_id > 0)
                                <br>
                                <span class="badge badge-success text-light mt-2">Relist: {{ $val->relist_id }}
                                    times</span>
                            @endif
                        @endif

                    </td>
                    <td id='order_action'>
                        <div class="btn-list">
                            @if (in_array('1', $actionaccess))
                                @if ($val->approve_pickup == 0 && $val->pstatus == 11)
                                    <button type="button"
                                        onclick="$('#submitting_approval').attr('href',`{{ url('/pickup_approve/' . $val->id) }}`); $('#approval_pick_deliverLabel').html('Are you sure? You want to move in the pickup')"
                                        class="btn btn-outline-info btn-sm w-100" tite="Pickup" data-toggle="modal"
                                        data-target="#approval_pick_deliver">

                                        <i class="fa fa-thumbs-o-up"></i>

                                    </button>
                                @endif
                            @endif
                            @if (in_array('2', $actionaccess))
                                @if ($val->approve_deliver == 0 && $val->pstatus == 12)
                                    <button type="button" data-placement="top" title="Schedule For Delivery!"
                                        class="btn btn-outline-info btn-sm w-100 updatingToSchedule"
                                        data-order-id="{{ $val->id }}">
                                        Schedule For Delivery <i class="fa fa-level-up"></i>
                                    </button>
                                @endif
                            @endif
                            @if (in_array('3', $actionaccess))
                                @if ($val->approve_deliver == 2 && $val->pstatus == 12)
                                    <button type="button" data-placement="top" title="Delivered!"
                                        data-toggle="modal" data-target="#approval_pick_deliver"
                                        onclick="$('#submitting_approval').attr('href',`{{ url('/deliver_approve/' . $val->id) }}`); $('#approval_pick_deliverLabel').html('Are you sure? You want to move in the delivery')"
                                        class="btn btn-outline-info btn-sm w-100">

                                        <i class="fa fa-thumbs-o-up"></i>

                                    </button>
                                @endif
                            @endif
                            @if (in_array('4', $actionaccess))
                                <?php $disabled = 'false'; ?>
                                @if ($val->pstatus == 11)
                                    @if ($val->storage_id > 0)
                                        @if ($val->pickup_carrier_id == 0)
                                            <?php $disabled = 'true'; ?>
                                        @endif
                                    @endif
                                @endif
                                @if ($disabled == 'false')
                                    <button type="button" data-placement="top" title="Order History!"
                                        class="btn btn-outline-info btn-sm w-100 updatee add-approaching">
                                        View/Update
                                        <input hidden type="text" class="Get-Order-ID"
                                            value="{{ $val->id }}">
                                    </button>
                                @endif
                            @endif
                            @if (in_array('5', $actionaccess))
                                <button type="button"
                                    onclick="window.location.href='{{ url('/new_edit/' . $val->id) }}'"
                                    class="btn btn-outline-info btn-sm w-100" data-placement="top"
                                    title="Edit Data!">

                                    Edit <i class="fa fa-edit "></i>

                                </button>
                            @endif
                            @if (in_array('6', $actionaccess))
                                <button type="button" class="btn btn-sm btn-outline-info w-100"
                                    onclick="window.location.href='{{ url('/print_summary/' . $val->id) }}'"
                                    data-placement="top" title="Print Summary!">

                                    Print <i class="fa fa-print"></i>

                                </button>
                            @endif
                            @if (in_array('7', $actionaccess))
                                @if (get_paid($val->id) == 'Unpaid' && $val->pstatus != 15 && $val->pstatus != 14)
                                    <button type="button" data-toggle="modal" data-target="#reportmodal"
                                        data-book-id="{{ $val->id }}"
                                        class="btn btn-outline-info btn-sm w-100">
                                        Link <i class="las la-inbox header-icons" data-placement="bottom"
                                            title="Send Payment Link To Customer!"></i>
                                    </button>
                                @endif
                            @endif
                            @if (in_array('8', $actionaccess))
                                <a href="https://www.google.com/maps/dir/{{ $val->originzip }},+USA/{{ $val->destinationzip }},+USA/"
                                    target="_blank" class="btn btn-outline-info btn-sm w-100" data-placement="bottom"
                                    title="Location!">

                                    Location <i class="fa fa-map-marker"></i>
                                </a>
                            @endif
                            @if (in_array('9', $actionaccess))
                                @if ($val->pstatus == 10 || $val->pstatus == 9)
                                    <button type="button"class="btn btn-outline-info btn-sm w-100"
                                        onclick="getData3({{ $val->id }})"title="Request" data-toggle="modal"
                                        data-target="#requestShipment">
                                        Request
                                        <i class="fa fa-level-up" data-placement="bottom" title="Request!"></i>
                                    </button>
                                @endif
                                {{-- <!--https://classic.mapquest.com/embed?q1={{ $val->origincity }}+{{ $val->originzip }}+{{ $val->originstate }}+US&q2={{ $val->destinationcity }}+{{ $val->destinationzip }}+{{ $val->destinationstate }}+US--> --}}
                            @endif
                            @if (in_array('10', $actionaccess))
                                @if ($val->pstatus <= 6)
                                    <br>
                                    <button type="button" class="btn btn-outline-info btn-sm w-100"
                                        onclick="window.location.href=`{{ url('/order_payment_card_us/' . $val->id) }}`"
                                        data-placement="bottom" title="Continue To Payment">

                                        Pay Now <i class="fa fa-money" style=""></i>
                                    </button>
                                @endif
                            @endif
                            @if (($val->pstatus >= 7 && $val->pstatus <= 10) || $val->pstatus == 18)
                                @if (in_array('11', $actionaccess))
                                    <button type="button" data-toggle="modal" data-target="#comparehmodal"
                                        data-book-id="{{ $val->id }}" data-location1="{{ $val->originzsc }}"
                                        data-location2="{{ $val->destinationzsc }}"
                                        class="btn btn-outline-info btn-sm w-100 compare">
                                        Carrier Record
                                        <i class="fa fa-plus" data-placement="bottom" title="Carrier Record!"></i>
                                    </button>
                                @endif
                                @if (in_array('12', $actionaccess))
                                    <button type="button" data-toggle="modal" data-target="#storagehmodal"
                                        data-book-id="{{ $val->id }}" data-location1="{{ $val->originzsc }}"
                                        data-location2="{{ $val->destinationzsc }}"
                                        class="btn btn-outline-info btn-sm w-100 storageModal">
                                        Storage Record
                                        <i class="fe fe-box" data-placement="bottom" title="Storage Record!"></i>
                                    </button>
                                @endif
                                <!--<button type="button" data-toggle="modal" data-target="#find_carrier_modal"
                                        data-find_o_id="{{ $val->id }}"
                                        data-location1="{{ $val->originzsc }}" data-location2="{{ $val->destinationzsc }}"
                                        class="btn btn-outline-info btn-sm w-100 find_carrier">
                                    Find Carrier <i class="fa fa-truck" data-placement="bottom"
                                                    title="Find Carrier!"></i>
                                </button>-->
                            @endif
                            @if (in_array('13', $actionaccess))
                                @if ($val->pstatus == 11 || $val->pstatus == 12)
                                    <a href="{{ url('/order-storage/' . encrypt($val->id)) }}"
                                        class="btn btn-outline-info btn-sm w-100">
                                        Move to Storage
                                        <i class="fa fa-level-up" data-placement="bottom"
                                            title="Move to Storage!"></i>
                                    </a><br>
                                @endif
                            @endif
                            @if (in_array('14', $actionaccess))
                                @if (get_paid($val->id) == 'Unpaid' && $val->pstatus >= 7 && $val->pstatus != 15 && $val->pstatus != 14)
                                    <button type="button" data-toggle="modal" data-target="#modalPaid"
                                        data-book-id="{{ $val->id }}"
                                        data-comments="{{ $val->pay_comments }}"
                                        data-paid_status="{{ $val->paid_status }}"
                                        class="btn btn-outline-info btn-sm w-100">
                                        Payment Confirmation
                                        <i class="fa fa-thumbs-up " data-placement="bottom"
                                            title="Payment Confirmation!"></i>
                                    </button>
                                @endif
                            @endif
                            @if (in_array('15', $actionaccess))
                                <button type="button" title="Message History!" data-toggle="modal"
                                    data-target="#messageCallCenter"
                                    onclick="msgCall({{ $val->id }},0,'{{ $val->oname }}','{{ $val->mainPhNum ?? $val->main_ph }}','Your Message')"
                                    class="btn btn-outline-info btn-sm w-100">
                                    Messages Center <i class="fa fa-envelope-open" data-placement="bottom"
                                        title="Message Center!"></i>
                                </button>
                            @endif
                            @if (in_array('16', $actionaccess))
                                <button type="button" title="Call History!" data-toggle="modal"
                                    data-target="#messageCallCenter"
                                    onclick="msgCall({{ $val->id }},1,'{{ $val->oname }}','{{ $val->mainPhNum ?? $val->main_ph }}','Client Reply')"
                                    class="btn btn-outline-info btn-sm w-100">
                                    Call Logs Center <i class="fa fa-phone" data-placement="bottom"
                                        title="Call Logs Center!"></i>
                                </button>
                            @endif
                            @if (in_array('108', $actionaccess))
                                <button type="button" title="Call History!" data-toggle="modal"
                                    data-target="#authorizationForm"
                                    onclick="getIdPhone({{ $val->id }},1,'{{ $val->oname }}','{{ $val->mainPhNum ?? $val->main_ph }}', '{{ $val->origincity . '-' . $val->originstate . '-' . $val->originzip }}', '{{ $val->destinationcity . '-' . $val->destinationstate . '-' . $val->destinationzip }}', '{{ $val->ymk }}')"
                                    class="btn btn-outline-info btn-sm w-100">
                                    Authorization Form <i class="fa fa-phone" data-placement="bottom"
                                        title="Authorization Form!"></i>
                                </button>
                            @endif
                            @if (in_array('17', $actionaccess))
                                @if (($val->pstatus >= 9 && $val->pstatus <= 14) || $val->pstatus == 19)
                                    <?php
                                    $rate_ot_dis = \App\Rating::where('order_id', $val->id)->first();

                                    $rate_count = \App\Rating::where('order_id', $val->id)
                                        ->where('replyer_id', Auth::user()->id)
                                        ->where('reply', null)
                                        ->count();
                                    $countRating = '';
                                    if ($rate_count > 0) {
                                        $countRating = '<span style="position: absolute;background: red;color: #fff;height: 25px;width: 25px;display: flex;justify-content: center;align-items: center;border-radius: 50%;bottom: 15px;right: 0;">' . $rate_count . '</span>';
                                    }
                                    ?>
                                    @if (Auth::user()->id == $val->order_taker_id)
                                        <?php
                                        $textRate = isset($rate_ot_dis->id) ? (isset($rate_ot_dis->reply) ? 'View Rating' : ($rate_ot_dis->rater_id == Auth::user()->id ? 'View Rating' : 'Reply Dispatcher')) : 'Rate Dipatcher';
                                        ?>
                                        <button type="button" title="{{ $textRate }}!" data-toggle="modal"
                                            data-target="#ratingPopup" onclick="ratingDetail({{ $val->id }})"
                                            class="btn btn-outline-info btn-sm w-100 position-relative">
                                            {{ $textRate }} <i class="fa fa-star" data-placement="bottom"
                                                title="{{ $textRate }}!"></i>
                                            {!! html_entity_decode($countRating) !!}
                                        </button>
                                    @elseif(Auth::user()->id == $val->dispatcher_id)
                                        <?php
                                        $textRate = isset($rate_ot_dis->id) ? (isset($rate_ot_dis->reply) ? 'View Rating' : ($rate_ot_dis->rater_id == Auth::user()->id ? 'View Rating' : 'Reply OrderTaker')) : 'Rate OrderTaker';
                                        ?>
                                        <button type="button" title="{{ $textRate }}!" data-toggle="modal"
                                            data-target="#ratingPopup" onclick="ratingDetail({{ $val->id }})"
                                            class="btn btn-outline-info btn-sm w-100 position-relative">
                                            {{ $textRate }} <i class="fa fa-star" data-placement="bottom"
                                                title="{{ $textRate }}!"></i>
                                            {!! html_entity_decode($countRating) !!}
                                        </button>
                                    @elseif(Auth::user()->userRole->name == 'Admin')
                                        <button type="button" title="View Rating!" data-toggle="modal"
                                            data-target="#ratingPopup" onclick="ratingDetail({{ $val->id }})"
                                            class="btn btn-outline-info btn-sm w-100">
                                            View Rating <i class="fa fa-star" data-placement="bottom"
                                                title="View Rating!"></i>
                                        </button>
                                    @endif
                                @endif
                            @endif
                            @if (in_array('18', $actionaccess))
                                @if ($val->pstatus <= 6)
                                    @if (get_paid($val->id) == 'Unpaid')
                                        <button type="button" data-toggle="modal" data-target="#trashmodal"
                                            data-book-id="{{ $val->id }}" class="btn btn-youtube btn-sm w-100">
                                            Delete
                                            <i class="fa fa-trash " data-placement="bottom"
                                                title="Delete Order!"></i>
                                        </button>
                                    @endif
                                @endif
                            @endif
                            @if (in_array('109', $actionaccess))
                                @if (in_array($val->pstatus, [1, 2, 3, 4, 5]))
                                    {{-- \Request::is('4') ||
                                        \Request::is('followup') ||
                                        \Request::is('interested') ||
                                        \Request::is('asking_low') ||
                                        \Request::is('not_responding' --}}
                                    <a href="{{ route('revert.to.new', $val->id) }}"
                                        class="btn btn-outline-info btn-sm w-100">
                                        Revert to new
                                        <i class="fa fa-level-up" data-placement="bottom"
                                            title="Move to Storage!"></i>
                                    </a><br>
                                @endif
                            @endif
                            @if (in_array('19', $actionaccess))
                                <?php
                                $id = $val->id;
                                $origin = $val->originzsc;
                                $pickup = $val->pickup_date;
                                $destination = $val->destinationzsc;
                                $delivery = $val->delivery_date;
                                $vehicle = $val->ymk;
                                $date = \Carbon\Carbon::parse($val->created_at)->format('M, d Y');
                                ?>
                                @if ($val->pstatus == 12 && $val->approve_deliver == 1)
                                    <button type="button"class="btn btn-outline-info btn-sm w-100"
                                        data-toggle="modal" data-target="#feedback"
                                        onclick="feedbackDetail('{{ $id }}','{{ $origin }}','{{ $pickup }}','{{ $destination }}','{{ $delivery }}','{{ $vehicle }}','{{ $date }}')">
                                        Feedback
                                        <i class="fa fa-star " data-placement="bottom" title="Feedback!"></i>
                                    </button>
                                @endif
                                @if ($val->pstatus == 13)
                                    <button type="button"class="btn btn-outline-info btn-sm w-100"
                                        data-toggle="modal" data-target="#feedback2"
                                        onclick="feedbackDetail2('{{ $id }}','{{ $origin }}','{{ $pickup }}','{{ $destination }}','{{ $delivery }}','{{ $vehicle }}','{{ $date }}')">
                                        Feedback
                                        <i class="fa fa-star " data-placement="bottom" title="Feedback!"></i>
                                    </button>
                                @endif
                            @endif
                            @if (in_array('20', $actionaccess))
                                @if (($val->pstatus >= 9 && $val->pstatus <= 14) || $val->pstatus == 19)
                                    <button type="button"class="btn btn-outline-info btn-sm w-100"
                                        onclick="getData({{ $val->id }})"title="View Sheet" data-toggle="modal"
                                        data-target="#exampleModal">
                                        Sheet
                                        <i class="fa fa-book " data-placement="bottom" title="Sheet!"></i>
                                    </button>
                                @endif
                            @endif
                            @if (in_array('21', $actionaccess))
                                @if ($val->pstatus == 14)
                                    <button type="button"class="btn btn-outline-info btn-sm w-100"
                                        onclick="getData2({{ $val->id }})"title="View Cancel History"
                                        data-toggle="modal" data-target="#viewCancelHistory">
                                        View Cancel History
                                        <i class="fa fa-ban " data-placement="bottom"
                                            title="View Cancel History!"></i>
                                    </button>
                                @endif
                            @endif
                            {{-- @if (in_array('21', $actionaccess)) --}}
                            {{-- <button type="button" class="btn btn-outline-info btn-sm w-100 add-approaching"
                                data-toggle="modal" data-target="#exampleModal8">Carrier Approachings
                                <input hidden type="text" class="Get-Order-ID" value="{{ $val->id }}">
                            </button> --}}
                            {{-- @endif --}}
                            @if (in_array('110', $actionaccess))
                                <a href="{{ route('allow.price.giver', $val->id) }}"
                                    class="btn btn-outline-info btn-sm w-100">
                                    Allow price giver
                                    <i class="fa fa-level-up" data-placement="bottom" title="Move to Storage!"></i>
                                </a><br>
                            @endif
                        </div>
                    </td>
                    @if ($val->pstatus >= 9 && $val->pstatus <= 13)
                        <td>
                            <button type="button" class="btn btn-default BundleExpand fa fa-chevron-up"></button>
                        </td>
                    @endif
                </tr>
                <tr class="child1{{ $key }}" style="display:none">
                    <td colspan="7">
                        <table class="table table-bordered table-striped bg-white mt-3 mb-4">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th></th>
                                    <th></th>
                                    <th>Storage</th>
                                    <th>ADDITIONAL</th>

                                </tr>
                            </thead>
                            <tbody>
                                @if ($val->pstatus >= 9 && $val->pstatus <= 13)
                                    @if ($val->listed_sheet)
                                        @foreach ($val->listed_sheet as $key => $value)
                                            <tr>
                                                <td>Listed</td>
                                                <td>Listed Price : {{ $value->listed_price }}</td>
                                                <td>Price : {{ $value->price }}</td>
                                                <td>{{ $value->storage }}</td>
                                                <td>{{ $value->additional }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endif
                                @if ($val->pstatus >= 10 && $val->pstatus <= 13)
                                    @if ($val->dispatch_sheet)
                                        @foreach ($val->dispatch_sheet as $key => $value)
                                            <tr>
                                                <td>Schedule</td>
                                                <td>Pickup Date : {{ $value->pickup_date }}</td>
                                                <td>Condition : {{ $value->vehicle_condition }}</td>
                                                <td>{{ $value->storage }}</td>
                                                <td>{{ $value->additional }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endif
                                @if ($val->pstatus >= 11 && $val->pstatus <= 13)
                                    @if ($val->pickedup_sheet)
                                        @foreach ($val->pickedup_sheet as $key => $value)
                                            <tr>
                                                <td>Picked Up</td>
                                                <td>Delivery Date : {{ $value->delivery_date }}</td>
                                                <td>Condition : {{ $value->vehicle_condition }}</td>
                                                <td>{{ $value->storage }}</td>
                                                <td>{{ $value->additional }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endif
                                @if ($val->pstatus >= 12 && $val->pstatus <= 13)
                                    @if ($val->delivery_sheet)
                                        @foreach ($val->delivery_sheet as $key => $value)
                                            <tr>
                                                <td>Delivery</td>
                                                <td>Delivery Date : {{ $value->delivery_date }}</td>
                                                <td>Position : {{ $value->vehicle_position }}</td>
                                                <td>{{ $value->driver_no }}</td>
                                                <td>{{ $value->additional }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endif
                                @if ($val->pstatus == 13)
                                    @if ($val->delivery_sheet)
                                        @foreach ($val->delivery_sheet as $key => $value)
                                            <tr>
                                                <td>Completed</td>
                                                <td>Remarks :
                                                    {{ $value->remarks . '(' . $value->client_rating . ')' }}</td>
                                                <td>Comments : {{ $value->comments }}</td>
                                                <td>Satisfied : {{ $value->satisfied }}</td>
                                                <td>{{ $value->additional }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endif
                            </tbody>
                        </table>
                        <form action="send_{{ $val->pstatus }}">
                            <div class="continer copntainer"></div>
                            <input type="hidden" name="orderId" id="orderId_{{ $val->pstatus . $val->id }}"
                                value="{{ $val->id }}">
                            @if ($val->pstatus == 9)
                                <h3 class="table-data-align m-2">Listed</h3>
                                <hr style="margin: 0;">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>Paid</label>
                                        <select name="paid" id="paid_{{ $val->pstatus . $val->id }}"
                                            class="form-control h-50">
                                            <option value="">SELECT</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Storage</label>
                                        <input class="form-control" id="storage_{{ $val->pstatus . $val->id }}"
                                            name="storage" placeholder="Storage" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Listed Price</label>
                                        <input class="form-control" id="listed_price_{{ $val->pstatus . $val->id }}"
                                            placeholder="Listed Price" name="listed_price" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Auction Update</label>
                                        <input class="form-control"
                                            id="auction_update_{{ $val->pstatus . $val->id }}"
                                            placeholder="Auction Update" name="Auction Update" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Title</label>
                                        <select id="title_keys_{{ $val->pstatus . $val->id }}"
                                            class="form-control h-50">
                                            <option value="">SELECT</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Key</label>
                                        <select id="keys_{{ $val->pstatus . $val->id }}" class="form-control h-50">
                                            <option value="">SELECT</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Listed Count</label>
                                        <input class="form-control" id="listed_count_{{ $val->pstatus . $val->id }}"
                                            placeholder="Listed Count" name="listed_count" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Old/New Price</label>
                                        <input class="form-control" id="price_{{ $val->pstatus . $val->id }}"
                                            placeholder="Old / New Price" name="old-new/price" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Vehicle Condition</label>
                                        <input class="form-control" placeholder="Vehicle Condition"
                                            id="condition_{{ $val->pstatus . $val->id }}" value="">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Review</label>
                                        <select id="review_{{ $val->pstatus . $val->id }}" class="form-control h-50"
                                            onchange="this.value == 'Yes' ? $('#all_rating').show() : $('#all_rating').hide()">
                                            <option value="" selected disabled>Select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12" id="all_rating" style="display:none;">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <label>Website</label>
                                                <select id="website_{{ $val->pstatus . $val->id }}"
                                                    class="form-control h-50"
                                                    onchange="this.value == 'Other' ? $('#other_website').show() : $('#other_website').hide()">
                                                    <option value="" selected disabled>Select</option>
                                                    <option value="BBB">BBB</option>
                                                    <option value="Trust Pilot">Trust Pilot</option>
                                                    <option value="Google">Google</option>
                                                    <option value="Yelp">Yelp</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3" style="display:none;" id="other_website">
                                                <label>Other Website</label>
                                                <input class="form-control h-50"
                                                    id="website_other_{{ $val->pstatus . $val->id }}"
                                                    placeholder="Other Website" value="">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Rating</label>
                                                <select id="client_rating_{{ $val->pstatus . $val->id }}"
                                                    class="form-control h-50">
                                                    <option value="" selected disabled>Select</option>
                                                    <option value="Positive">Positive</option>
                                                    <option value="Neutral">Neutral</option>
                                                    <option value="Negative">Negative</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Website Link</label>
                                                <input class="form-control h-50"
                                                    id="website_link_{{ $val->pstatus . $val->id }}"
                                                    placeholder="Website Link" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <label>Additional</label>
                                        <input class="form-control" id="additional_{{ $val->pstatus . $val->id }}"
                                            placeholder="Additional" value="">
                                    </div>
                                    <div class="col-md-1 mt-auto">
                                        <!--<button type="button" class="bt btn-info fa fa-eye mt-2 fs-25"></button>-->
                                        <button type="button"
                                            onclick="listedUpload({{ $val->pstatus . $val->id }})"
                                            class="bt btn-primary fa fa-upload fs-25"></button>
                                    </div>
                                </div>
                            @endif
                            @if ($val->pstatus == 10)
                                <h3 class="table-data-align m-2">Schedule</h3>
                                <hr style="margin: 0;">
                                <div class="row m-2">
                                    <div class="col-md-3">
                                        <label>Pickedup Time</label>
                                        <input class="form-control" type="datetime-local"
                                            id="pickedup_{{ $val->pstatus . $val->id }}"
                                            placeholder="=PickedUp time">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Delivery Time</label>
                                        <input class="form-control" type="datetime-local"
                                            id="delivery_date_{{ $val->pstatus . $val->id }}"
                                            placeholder="=Delivery time">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Dispatch Price</label>
                                        <input class="form-control" type="text"
                                            id="price_{{ $val->pstatus . $val->id }}" placeholder="Dispatch Price"
                                            required>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Vehicle Condition</label>
                                        <input class="form-control" type="text"
                                            id="condition_{{ $val->pstatus . $val->id }}"
                                            placeholder="Vehicle Condition">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Company Name</label>
                                        <input class="form-control" type="text"
                                            id="company_name_{{ $val->pstatus . $val->id }}"
                                            placeholder="Company Name">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Storage </label>
                                        <input class="form-control" id="storage_{{ $val->pstatus . $val->id }}"
                                            placeholder="Storage" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Carrier Rating </label>
                                        <input class="form-control"
                                            id="carrier_rating_{{ $val->pstatus . $val->id }}"
                                            placeholder="Carrier Rating" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Aware Driver Delivery </label>
                                        <input class="form-control" type="text"
                                            id="aware_driver_delivery_date_{{ $val->pstatus . $val->id }}"
                                            placeholder="Aware Driver Delivery">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Driver FMCSA (Active)?</label>
                                        <select id="driver_fmcsa_{{ $val->pstatus . $val->id }}"
                                            class="form-control h-50">
                                            <option value="">SELECT</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Verify FMCSA? </label>
                                        <input class="form-control" id="fmcsa_{{ $val->pstatus . $val->id }}"
                                            placeholder="FMCSA" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Date Of Insurance (FMCSA)</label>
                                        <input class="form-control" type="date"
                                            id="insurance_date_{{ $val->pstatus . $val->id }}"
                                            placeholder="Date Of Insurance (FMCSA)" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>COI Holder</label>
                                        <select id="coi_holder_{{ $val->pstatus . $val->id }}"
                                            class="form-control h-50">
                                            <option value="">SELECT</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                            <option value="Waiting">Waiting</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Is Vehicle Luxury?</label>
                                        <select id="vehicle_luxury_{{ $val->pstatus . $val->id }}"
                                            class="form-control h-50">
                                            <option value="">SELECT</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>New/Old Driver</label>
                                        <select id="new_old_driver_{{ $val->pstatus . $val->id }}"
                                            class="form-control h-50">
                                            <option value="">SELECT</option>
                                            <option value="Old Driver">Old Driver</option>
                                            <option value="New Driver">New Driver</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Is Local?</label>
                                        <select id="is_local_{{ $val->pstatus . $val->id }}"
                                            class="form-control h-50">
                                            <option value="">SELECT</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Job Accept </label>
                                        <input class="form-control" id="job_accept_{{ $val->pstatus . $val->id }}"
                                            placeholder="Job Accept" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Title</label>
                                        <select id="title_keys_{{ $val->pstatus . $val->id }}" name="key"
                                            class="form-control h-50">
                                            <option value="">SELECT</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Key</label>
                                        <select id="keys_{{ $val->pstatus . $val->id }}" class="form-control h-50">
                                            <option value="">SELECT</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Auction Update</label>
                                        <input id="auction_update_{{ $val->pstatus . $val->id }}"
                                            class="form-control" placeholder="Auction Update" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Storage Pay</label>
                                        <select id="who_pay_storage_{{ $val->pstatus . $val->id }}"
                                            class="form-control h-50">
                                            <option value="">SELECT</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Vehicle Position</label>
                                        <input id="vehicle_position_{{ $val->pstatus . $val->id }}"
                                            class="form-control" placeholder="Vehicle Position" value="">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Payment Method</label>
                                        <input class="form-control"
                                            id="payment_method_{{ $val->pstatus . $val->id }}"
                                            placeholder="Payment Method" value="">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Review</label>
                                        <select id="review_{{ $val->pstatus . $val->id }}" class="form-control h-50"
                                            onchange="this.value == 'Yes' ? $('#all_rating').show() : $('#all_rating').hide()">
                                            <option value="" selected disabled>Select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12" id="all_rating" style="display:none;">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <label>Website</label>
                                                <select id="website_{{ $val->pstatus . $val->id }}"
                                                    class="form-control h-50"
                                                    onchange="this.value == 'Other' ? $('#other_website').show() : $('#other_website').hide()">
                                                    <option value="" selected disabled>Select</option>
                                                    <option value="BBB">BBB</option>
                                                    <option value="Trust Pilot">Trust Pilot</option>
                                                    <option value="Google">Google</option>
                                                    <option value="Yelp">Yelp</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3" style="display:none;" id="other_website">
                                                <label>Other Website</label>
                                                <input class="form-control h-50"
                                                    id="website_other_{{ $val->pstatus . $val->id }}"
                                                    placeholder="Other Website" value="">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Rating</label>
                                                <select id="client_rating_{{ $val->pstatus . $val->id }}"
                                                    class="form-control h-50">
                                                    <option value="" selected disabled>Select</option>
                                                    <option value="Positive">Positive</option>
                                                    <option value="Neutral">Neutral</option>
                                                    <option value="Negative">Negative</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Website Link</label>
                                                <input class="form-control h-50"
                                                    id="website_link_{{ $val->pstatus . $val->id }}"
                                                    placeholder="Website Link" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-11">
                                        <label>Additional</label>
                                        <input class="form-control" id="additional_{{ $val->pstatus . $val->id }}"
                                            placeholder="Additional" value="">
                                    </div>
                                    <div class="col-md-1 mt-auto">
                                        <!--<button type="button" class="bt btn-info fa fa-eye mt-2 fs-25"></button>-->
                                        <button onclick="dispatchUpload({{ $val->pstatus . $val->id }})"
                                            type="button" class="bt btn-primary fa fa-upload fs-25"></button>
                                    </div>

                                </div>
                            @endif
                            @if ($val->pstatus == 11)
                                <h3 class="table-data-align m-2">Picked Up</h3>
                                <hr style="margin: 0;">
                                <div class="row m-2">
                                    <div class="col-md-12">
                                        <h4>Auction Status</h4>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Auction Status</label>
                                        <input class="form-control"
                                            id="auction_status1_{{ $val->pstatus . $val->id }}"
                                            placeholder="Auction Status" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Storage </label>
                                        <input class="form-control" id="storage1_{{ $val->pstatus . $val->id }}"
                                            placeholder="Storage" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Vehicle Condition </label>
                                        <input class="form-control" id="condition1_{{ $val->pstatus . $val->id }}"
                                            placeholder="Vehicle Condition" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Title</label>
                                        <select id="title_keys1_{{ $val->pstatus . $val->id }}"
                                            class="form-control h-50">
                                            <option value="">SELECT</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Key</label>
                                        <select id="keys1_{{ $val->pstatus . $val->id }}" class="form-control h-50">
                                            <option value="">SELECT</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Vehicle Position </label>
                                        <input id="vehicle_position1_{{ $val->pstatus . $val->id }}"
                                            class="form-control" placeholder="Vehicle Position" value="">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Review</label>
                                        <select id="review_{{ $val->pstatus . $val->id }}" class="form-control h-50"
                                            onchange="this.value == 'Yes' ? $('#all_rating').show() : $('#all_rating').hide()">
                                            <option value="" selected disabled>Select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12" id="all_rating" style="display:none;">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <label>Website</label>
                                                <select id="website_{{ $val->pstatus . $val->id }}"
                                                    class="form-control h-50"
                                                    onchange="this.value == 'Other' ? $('#other_website').show() : $('#other_website').hide()">
                                                    <option value="" selected disabled>Select</option>
                                                    <option value="BBB">BBB</option>
                                                    <option value="Trust Pilot">Trust Pilot</option>
                                                    <option value="Google">Google</option>
                                                    <option value="Yelp">Yelp</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3" style="display:none;" id="other_website">
                                                <label>Other Website</label>
                                                <input class="form-control h-50"
                                                    id="website_other_{{ $val->pstatus . $val->id }}"
                                                    placeholder="Other Website" value="">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Rating</label>
                                                <select id="client_rating_{{ $val->pstatus . $val->id }}"
                                                    class="form-control h-50">
                                                    <option value="" selected disabled>Select</option>
                                                    <option value="Positive">Positive</option>
                                                    <option value="Neutral">Neutral</option>
                                                    <option value="Negative">Negative</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Website Link</label>
                                                <input class="form-control h-50"
                                                    id="website_link_{{ $val->pstatus . $val->id }}"
                                                    placeholder="Website Link" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-11">
                                        <label>Additional</label>
                                        <input class="form-control" id="additional1_{{ $val->pstatus . $val->id }}"
                                            placeholder="Additional" name="additional" value="">
                                    </div>
                                    <div class="col-md-1 mt-auto">
                                        <!--<button type="button" class="bt btn-info fa fa-eye mt-2 fs-25"></button>-->
                                        <button onclick="auctionpickedUpUpload({{ $val->pstatus . $val->id }})"
                                            type="button" class="bt btn-primary fa fa-upload fs-25"></button>
                                    </div>
                                </div>
                                <div class="row m-2">
                                    <div class="col-md-12">
                                        <h4>Driver Status</h4>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Driver Status </label>
                                        <input class="form-control"
                                            id="driver_status_{{ $val->pstatus . $val->id }}"
                                            placeholder="Driver Status" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Driver Name </label>
                                        <input class="form-control" id="carrier_name_{{ $val->pstatus . $val->id }}"
                                            placeholder="Driver Name" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Driver Payment </label>
                                        <input class="form-control"
                                            id="driver_payment_{{ $val->pstatus . $val->id }}"
                                            placeholder="Driver Payment" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Driver No1# </label>
                                        <input class="form-control driverphoneno"
                                            id="driver_no_{{ $val->pstatus . $val->id }}" placeholder="Driver No1#"
                                            value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Driver No2#</label>
                                        <input class="form-control driverphoneno"
                                            id="driver_no2_{{ $val->pstatus . $val->id }}" placeholder="Driver No2#"
                                            value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Driver No3#</label>
                                        <input class="form-control driverphoneno"
                                            id="driver_no3_{{ $val->pstatus . $val->id }}" placeholder="Driver No3#"
                                            value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Driver No4#</label>
                                        <input class="form-control driverphoneno"
                                            id="driver_no4_{{ $val->pstatus . $val->id }}" placeholder="Driver No4#"
                                            value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Company Name </label>
                                        <input class="form-control" id="company_name_{{ $val->pstatus . $val->id }}"
                                            placeholder="Company Name" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Storage </label>
                                        <input class="form-control" id="storage_{{ $val->pstatus . $val->id }}"
                                            placeholder="Storage" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Delivery Datetime </label>
                                        <input class="form-control"
                                            id="delivery_date_{{ $val->pstatus . $val->id }}" type="datetime-local"
                                            placeholder="Delivery Datetime" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Vehicle Condition </label>
                                        <input class="form-control" id="condition_{{ $val->pstatus . $val->id }}"
                                            placeholder="Vehicle Condition" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Vehicle Position </label>
                                        <input id="vehicle_position_{{ $val->pstatus . $val->id }}"
                                            class="form-control" placeholder="Vehicle Position" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Title</label>
                                        <select id="title_keys_{{ $val->pstatus . $val->id }}"
                                            class="form-control h-50">
                                            <option value="">SELECT</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Key</label>
                                        <select id="keys_{{ $val->pstatus . $val->id }}" class="form-control h-50">
                                            <option value="">SELECT</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Payment</label>
                                        <select id="payment_{{ $val->pstatus . $val->id }}"
                                            class="form-control h-50">
                                            <option value="">SELECT</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Payment Charged Or Owes </label>
                                        <input class="form-control"
                                            id="payment_charged_or_owes_{{ $val->pstatus . $val->id }}"
                                            placeholder="Payment Charged Or Owes" value="">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Payment Method </label>
                                        <input class="form-control"
                                            id="payment_method_{{ $val->pstatus . $val->id }}"
                                            placeholder="Payment Method" value="">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Total Amount (If Owed) </label>
                                        <input class="form-control" id="price_{{ $val->pstatus . $val->id }}"
                                            placeholder="Total Amount (If Owed)" value="">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Dock Receipt (If Port)</label>
                                        <input class="form-control"
                                            id="stamp_dock_receipt_{{ $val->pstatus . $val->id }}"
                                            placeholder="Dock Receipt (If Port)" value="">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Review</label>
                                        <select id="review_{{ $val->pstatus . $val->id }}" class="form-control h-50"
                                            onchange="this.value == 'Yes' ? $('#all_rating').show() : $('#all_rating').hide()">
                                            <option value="" selected disabled>Select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12" id="all_rating" style="display:none;">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <label>Website</label>
                                                <select id="website_{{ $val->pstatus . $val->id }}"
                                                    class="form-control h-50"
                                                    onchange="this.value == 'Other' ? $('#other_website').show() : $('#other_website').hide()">
                                                    <option value="" selected disabled>Select</option>
                                                    <option value="BBB">BBB</option>
                                                    <option value="Trust Pilot">Trust Pilot</option>
                                                    <option value="Google">Google</option>
                                                    <option value="Yelp">Yelp</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3" style="display:none;" id="other_website">
                                                <label>Other Website</label>
                                                <input class="form-control h-50"
                                                    id="website_other_{{ $val->pstatus . $val->id }}"
                                                    placeholder="Other Website" value="">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Rating</label>
                                                <select id="client_rating_{{ $val->pstatus . $val->id }}"
                                                    class="form-control h-50">
                                                    <option value="" selected disabled>Select</option>
                                                    <option value="Positive">Positive</option>
                                                    <option value="Neutral">Neutral</option>
                                                    <option value="Negative">Negative</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Website Link</label>
                                                <input class="form-control h-50"
                                                    id="website_link_{{ $val->pstatus . $val->id }}"
                                                    placeholder="Website Link" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <label>Additional</label>
                                        <input class="form-control" id="additional_{{ $val->pstatus . $val->id }}"
                                            placeholder="Additional" name="additional" value="">
                                    </div>
                                    <div class="col-md-1 mt-auto">
                                        <!--<button type="button" class="bt btn-info fa fa-eye mt-2 fs-25"></button>-->
                                        <button onclick="pickedUpUpload({{ $val->pstatus . $val->id }})"
                                            type="button" class="bt btn-primary fa fa-upload fs-25"></button>
                                    </div>
                                </div>
                            @endif
                            @if ($val->pstatus == 12)
                                <h3 class="table-data-align m-2">Delivery</h3>
                                <hr style="margin: 0;">
                                <div class="row m-2">
                                    <div class="col-md-2">
                                        <label>Driver No1#</label>
                                        <input class="form-control driverphoneno"
                                            id="driver_no_{{ $val->pstatus . $val->id }}"
                                            placeholder="Driver No1#" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Driver No2#</label>
                                        <input class="form-control driverphoneno"
                                            id="driver_no2_{{ $val->pstatus . $val->id }}"
                                            placeholder="Driver No2#" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Driver No3#</label>
                                        <input class="form-control driverphoneno"
                                            id="driver_no3_{{ $val->pstatus . $val->id }}"
                                            placeholder="Driver No3#" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Driver No4#</label>
                                        <input class="form-control driverphoneno"
                                            id="driver_no4_{{ $val->pstatus . $val->id }}"
                                            placeholder="Driver No4#" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Driver Status</label>
                                        <input id="driver_status_{{ $val->pstatus . $val->id }}"
                                            class="form-control" placeholder="Driver Status" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Driver Payment Status</label>
                                        <input id="driver_payment_status_{{ $val->pstatus . $val->id }}"
                                            class="form-control" placeholder="Driver Payment Status"
                                            value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Vehicle Condition</label>
                                        <input class="form-control" id="condition_{{ $val->pstatus . $val->id }}"
                                            placeholder="Vehicle Condition" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Customer Informed</label>
                                        <input class="form-control"
                                            id="customer_informed_{{ $val->pstatus . $val->id }}"
                                            placeholder="Customer Informed" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Vehicle Position</label>
                                        <input id="vehicle_position_{{ $val->pstatus . $val->id }}"
                                            class="form-control" placeholder="Vehicle Position" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Delivery Datetime</label>
                                        <input class="form-control"
                                            id="delivery_date_{{ $val->pstatus . $val->id }}"
                                            type="datetime-local" placeholder="Delivery Datetime" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Storage Pay</label>
                                        <input id="who_pay_storage_{{ $val->pstatus . $val->id }}"
                                            class="form-control" placeholder="Who Pay Storage" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Title</label>
                                        <select id="title_keys_{{ $val->pstatus . $val->id }}"
                                            class="form-control h-50">
                                            <option value="">SELECT</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Key</label>
                                        <select id="keys_{{ $val->pstatus . $val->id }}"
                                            class="form-control h-50">
                                            <option value="">SELECT</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Client & Status</label>
                                        <select id="client_status_{{ $val->pstatus . $val->id }}"
                                            class="form-control h-50">
                                            <option value="">SELECT</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Owes Status</label>
                                        <select id="owes_status_{{ $val->pstatus . $val->id }}"
                                            class="form-control h-50">
                                            <option value="">SELECT</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Review</label>
                                        <select id="review_{{ $val->pstatus . $val->id }}"
                                            class="form-control h-50"
                                            onchange="this.value == 'Yes' ? $('#all_rating').show() : $('#all_rating').hide()">
                                            <option value="" selected disabled>Select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12" id="all_rating" style="display:none;">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <label>Website</label>
                                                <select id="website_{{ $val->pstatus . $val->id }}"
                                                    class="form-control h-50"
                                                    onchange="this.value == 'Other' ? $('#other_website').show() : $('#other_website').hide()">
                                                    <option value="" selected disabled>Select</option>
                                                    <option value="BBB">BBB</option>
                                                    <option value="Trust Pilot">Trust Pilot</option>
                                                    <option value="Google">Google</option>
                                                    <option value="Yelp">Yelp</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3" style="display:none;" id="other_website">
                                                <label>Other Website</label>
                                                <input class="form-control h-50"
                                                    id="website_other_{{ $val->pstatus . $val->id }}"
                                                    placeholder="Other Website" value="">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Rating</label>
                                                <select id="client_rating_{{ $val->pstatus . $val->id }}"
                                                    class="form-control h-50">
                                                    <option value="" selected disabled>Select</option>
                                                    <option value="Positive">Positive</option>
                                                    <option value="Neutral">Neutral</option>
                                                    <option value="Negative">Negative</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Website Link</label>
                                                <input class="form-control h-50"
                                                    id="website_link_{{ $val->pstatus . $val->id }}"
                                                    placeholder="Website Link" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <label>Additional</label>
                                        <input class="form-control" id="additional_{{ $val->pstatus . $val->id }}"
                                            placeholder="Additional" value="">
                                    </div>
                                    <div class="col-md-1 mt-auto">
                                        <!--<button type="button" class="bt btn-info fa fa-eye mt-2 fs-25"></button>-->
                                        <button onclick="deliveryUpload({{ $val->pstatus . $val->id }})"
                                            type="button" class="bt btn-primary fa fa-upload fs-25"></button>
                                    </div>
                                </div>
                            @endif
                            @if ($val->pstatus == 13)
                                <h3 class="table-data-align m-2">Completed</h3>
                                <hr style="margin:0;">
                                <div class="row  m-2">
                                    <div class="col-md-3">
                                        <label>Remarks Status</label>
                                        <input class="form-control h-50"
                                            id="remarks_{{ $val->pstatus . $val->id }}"
                                            placeholder="Remarks Status" value="">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Comments</label>
                                        <input class="form-control h-50"
                                            id="comments_{{ $val->pstatus . $val->id }}" placeholder="Comments"
                                            value="">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Satisfied?</label>
                                        <input class="form-control h-50"
                                            id="satisfied_{{ $val->pstatus . $val->id }}"
                                            placeholder="How you Satisfied?" value="">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Review</label>
                                        <select id="review_{{ $val->pstatus . $val->id }}"
                                            class="form-control h-50"
                                            onchange="this.value == 'Yes' ? $('#all_rating').show() : $('#all_rating').hide()">
                                            <option value="" selected disabled>Select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12" id="all_rating" style="display:none;">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <label>Website</label>
                                                <select id="website_{{ $val->pstatus . $val->id }}"
                                                    class="form-control h-50"
                                                    onchange="this.value == 'Other' ? $('#other_website').show() : $('#other_website').hide()">
                                                    <option value="" selected disabled>Select</option>
                                                    <option value="BBB">BBB</option>
                                                    <option value="Trust Pilot">Trust Pilot</option>
                                                    <option value="Google">Google</option>
                                                    <option value="Yelp">Yelp</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3" style="display:none;" id="other_website">
                                                <label>Other Website</label>
                                                <input class="form-control h-50"
                                                    id="website_other_{{ $val->pstatus . $val->id }}"
                                                    placeholder="Other Website" value="">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Rating</label>
                                                <select id="client_rating_{{ $val->pstatus . $val->id }}"
                                                    class="form-control h-50">
                                                    <option value="" selected disabled>Select</option>
                                                    <option value="Positive">Positive</option>
                                                    <option value="Neutral">Neutral</option>
                                                    <option value="Negative">Negative</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Website Link</label>
                                                <input class="form-control h-50"
                                                    id="website_link_{{ $val->pstatus . $val->id }}"
                                                    placeholder="Website Link" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-11">
                                        <label>Additional</label>
                                        <input class="form-control h-50"
                                            id="additional_{{ $val->pstatus . $val->id }}"
                                            placeholder="Additional" value="">
                                    </div>
                                    <div class="col-md-1 mt-auto">
                                        <!--<button type="button" class="bt btn-info fa fa-eye mt-2 fs-25"></button>-->
                                        <button onclick="completedUpload({{ $val->pstatus . $val->id }})"
                                            type="button" class="bt btn-primary fa fa-upload fs-25"></button>
                                    </div>
                                </div>
                            @endif
                        </form>
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
<div class="modal fade" id="approval_pick_deliver" tabindex="-1" role="dialog"
    aria-labelledby="approval_pick_deliverLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approval_pick_deliverLabel"></h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-dismiss="modal">No</button>
                <a href="#" id="submitting_approval" class="btn btn-outline-success">Yes</a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="assignToDispatcher" tabindex="-1" role="dialog"
    aria-labelledby="assignToDispatcherTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignToDispatcherLongTitle">Assign To</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url('/assign_to_dispatcher') }}" method="POST">
                @csrf
                <input type="hidden" name="order_id" id="assigning_dispatcher_order" />
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="assigning_dispatcher" class="form-label">Assign To</label>
                                <?php
                                $dis = \App\User::with('daily_ass')
                                    ->whereHas('userRole', function ($q) {
                                        $q->where('name', 'Dispatcher');
                                    })
                                    ->where('deleted', 0)
                                    ->get();
                                ?>
                                <select name="assigning_dispatcher" id='assigning_dispatcher' class="form-control"
                                    required>
                                    <option value="" selected disabled>Select</option>
                                    @foreach ($dis as $key => $dispa)
                                        <option value="{{ $dispa->id }}">
                                            {{ $dispa->slug ?? $dispa->name . ' ' . $dispa->last_name }}
                                            ({{ isset($dispa->daily_ass->total_quote) ? $dispa->daily_ass->total_quote . ' Left' : 'Unlimited' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="specialIns" tabindex="-1" aria-labelledby="specialInsLabel" aria-hidden="true"
    data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="specialInsModelLabel">Special Instructions</h5>
            </div>
            <form action="{{ url('/special_instructions') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="instr_id" name="order_id" />
                    <div class="form-group">
                        <label for="instruction" class="form-label">Special Instructions</label>
                        <textarea required class="form-control" name="instruction" id="instruction"
                            placeholder="Enter Special Instruction" rows="12" cols="12"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="$('#exampleModal').modal('show')"
                        data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal8" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel8"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Carrier Approaching Details For: <span class="show_order_id"></span>
                </h5>
            </div>
            <form action="{{ route('store.carrier_approachings') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="val_order_id" name="order_id" />

                    <div class="form-group row">
                        <label for="extension" class="col-sm-4 col-form-label">Extension</label>
                        <div class="col-sm-8">
                            <input type="text" name="extension" id="extension" class="form-control col-12"
                                required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="comp_name" class="col-sm-4 col-form-label">Company Name</label>
                        <div class="col-sm-8">
                            <input type="text" name="comp_name" id="comp_name" class="form-control col-12"
                                required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="comp_phone" class="col-sm-4 col-form-label">Company Phone</label>
                        <div class="col-sm-8">
                            <input type="text" name="comp_phone" id="comp_phone" class="form-control col-12"
                                required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="status" class="col-sm-4 col-form-label">Status</label>
                        <div class="col-sm-8">
                            <select name="status" id="status" class="form-control col-12" required>
                                <option value="1">Interested</option>
                                <option value="0">Not Interested</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="comp_response" class="col-sm-4 col-form-label">Company Response</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="comp_response" id="comp_response" placeholder="Company's Response"
                                rows="12" cols="12" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="authorizationForm" tabindex="-1" role="dialog"
    aria-labelledby="authorizationFormTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="authorizationFormTitle">Authorization Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    onclick="modalClick()">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">

                    <div class="panel panel-primary">
                        <div class="panel-body tabs-menu-body">
                            <div class="tab-content">
                                <div class="tab-pane active " id="tab4">
                                    <form method="post" action="{{ route('authorization.form.email') }}">
                                        @csrf
                                        <div class="row">
                                            <input type="hidden" class="form-control" name="id"
                                                id='authorization-orderId' placeholder="" readonly>
                                            <input type="hidden" class="form-control" name="status"
                                                id='authorization-status' placeholder="" readonly>
                                            <input type="hidden" class="form-control" name="cname"
                                                id='authorization-cname' placeholder="" readonly>
                                            <input type="hidden" class="form-control" name="cphone"
                                                id='authorization-cphone' placeholder="" readonly>
                                            <input type="hidden" class="form-control" name="origin"
                                                id='authorization-origin' placeholder="" readonly>
                                            <input type="hidden" class="form-control" name="destination"
                                                id='authorization-destination' placeholder="" readonly>
                                            <input type="hidden" class="form-control" name="vehicle"
                                                id='authorization-vehicle' placeholder="" readonly>
                                            <br>
                                            <div class="col-sm-12 col-md-12" id="msgReply">
                                                <div class="form-group">
                                                    <label class="form-label">Enter Inv. Amount</label>
                                                    <input type="number" value="" name="invoiceAmount"
                                                        class="form-control" id="authorizationAmount"
                                                        placeholder="Enter Amount" required />
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Enter Email</label>
                                                    <input type="email" value="" name="email"
                                                        class="form-control" id="authorizationEmail"
                                                        placeholder="Enter Email" />
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary"
                                            id="authorizationForm">Send</button>
                                    </form>
                                </div>
                                <div class="tab-pane" id="tab5">
                                    <div class="chat-body-style ChatBody viewMessageCall"
                                        style="overflow:scroll; height:300px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"
                    onclick="modalClick()">Close</button>
            </div>
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
    function regain_report_modal() {

        $('#reportmodal').on('show.bs.modal', function(e) {

            //get data-id attribute of the clicked element
            var orderId = $(e.relatedTarget).data('book-id');

            //populate the textbox
            var encryptvuserid = btoa({{ Auth::user()->id }});
            var encryptvoderid = btoa(orderId);
            var linkv = "{{ url('/email_order/') }}" + '/' + encryptvoderid + '/' + encryptvuserid;
            $(e.currentTarget).find('input[name="orderid"]').val(orderId);
            $(e.currentTarget).find('input[name="link"]').val(linkv);
        });


        $('#trashmodal').on('show.bs.modal', function(e) {

            var orderId = $(e.relatedTarget).data('book-id');
            $(e.currentTarget).find('input[name="orderid"]').val(orderId);

        });

        $('#modalPaid').on('show.bs.modal', function(e) {
            var orderId = $(e.relatedTarget).data('book-id');
            $(e.currentTarget).find('input[name="orderid"]').val(orderId);

            var comments = $(e.relatedTarget).data('comments');
            //  alert(comments);
            $(e.currentTarget).find('textarea[name="pay_comments"]').val(comments);


            var paid_status = $(e.relatedTarget).data('paid_status');
            if (paid_status == 0) {
                $("#status0").prop("checked", true);
            } else if (paid_status == 1) {
                $("#status1").prop("checked", true);
            } else if (paid_status == 2) {
                $("#status2").prop("checked", true);
            }

        });

    }
    regain_report_modal();

    function listedUpload(id) {
        let oid = $('#orderId_' + id).val();
        let paid = $('#paid_' + id).val();
        let storage = $('#storage_' + id).val();
        let listed_price = $('#listed_price_' + id).val();
        let auction_update = $('#auction_update_' + id).val();
        let title_keys = $('#title_keys_' + id).val();
        let keys = $('#keys_' + id).val();
        let listed_count = $('#listed_count_' + id).val();
        let price = $('#price_' + id).val();
        let additional = $('#additional_' + id).val();
        let vehicle_condition = $("#condition_" + id).val();

        $.ajax({
            url: window.location.origin + "/listed_sheet/" + oid, // Url of backend (can be python, php, etc..)
            type: "GET", // data type (can be get, post, put, delete)
            data: {
                paid: paid,
                storage: storage,
                listed_price: listed_price,
                auction_update: auction_update,
                title_keys: title_keys,
                keys: keys,
                listed_count: listed_count,
                price: price,
                additional: additional,
                vehicle_condition: vehicle_condition
            }, // data in json format
            async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
            success: function(response) {
                if (response === 'true') {
                    Swal.fire(
                        'Success!',
                        'Listed Sheet Updated!',
                        'success'
                    )
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {}
        });
    }

    function dispatchUpload(id) {
        let oid = $('#orderId_' + id).val();
        let pickedup = $('#pickedup_' + id).val();
        let delivery_date = $('#delivery_date_' + id).val();
        let driver_fmcsa = $('#driver_fmcsa_' + id).val();
        let carrier_rating = $('#carrier_rating_' + id).val();
        let fmcsa = $('#fmcsa_' + id).val();
        let coi_holder = $('#coi_holder_' + id).val();
        let vehicle_luxury = $('#vehicle_luxury_' + id).val();
        let aware_driver_delivery_date = $('#aware_driver_delivery_date_' + id).val();
        let new_old_driver = $('#new_old_driver_' + id).val();
        let is_local = $('#is_local_' + id).val();
        let job_accept = $('#job_accept_' + id).val();
        let condition = $('#condition_' + id).val();
        let title_keys = $('#title_keys_' + id).val();
        let keys = $('#keys_' + id).val();
        let storage = $('#storage_' + id).val();
        let auction_update = $('#auction_update_' + id).val();
        let who_pay_storage = $('#who_pay_storage_' + id).val();
        let vehicle_position = $('#vehicle_position_' + id).val();
        let payment_method = $('#payment_method_' + id).val();
        let stamp_dock_receipt = $('#stamp_dock_receipt_' + id).val();
        let company_name = $('#company_name_' + id).val();
        let price = $('#price_' + id).val();
        let insurance_date = $('#insurance_date_' + id).val();
        let additional = $('#additional_' + id).val();

        $.ajax({
            url: window.location.origin + "/dispatch_sheet/" +
                oid, // Url of backend (can be python, php, etc..)
            type: "GET", // data type (can be get, post, put, delete)
            data: {
                stamp_dock_receipt: stamp_dock_receipt,
                company_name: company_name,
                payment_method: payment_method,
                price: price,
                insurance_date: insurance_date,
                pickup_date: pickedup,
                delivery_date: delivery_date,
                driver_fmcsa: driver_fmcsa,
                carrier_rating: carrier_rating,
                fmcsa: fmcsa,
                coi_holder: coi_holder,
                vehicle_luxury: vehicle_luxury,
                aware_driver_delivery_date: aware_driver_delivery_date,
                new_old_driver: new_old_driver,
                is_local: is_local,
                job_accept: job_accept,
                vehicle_condition: condition,
                title_keys: title_keys,
                keys: keys,
                storage: storage,
                auction_update: auction_update,
                who_pay_storage: who_pay_storage,
                vehicle_position: vehicle_position,
                additional: additional
            }, // data in json format
            async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
            success: function(response) {
                if (response === 'true') {
                    Swal.fire(
                        'Success!',
                        'Dispatch Sheet Updated!',
                        'success'
                    )
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {}
        });
    }

    function pickedUpUpload(id) {
        let oid = $('#orderId_' + id).val();
        let driver_status = $('#driver_status_' + id).val();
        let delivery_date = $('#delivery_date_' + id).val();
        let storage = $('#storage_' + id).val();
        let condition = $('#condition_' + id).val();
        let title_keys = $('#title_keys_' + id).val();
        let keys = $('#keys_' + id).val();
        let vehicle_position = $('#vehicle_position_' + id).val();
        let payment_charged_or_owes = $('#payment_charged_or_owes_' + id).val();
        let payment_method = $('#payment_method_' + id).val();
        let price = $('#price_' + id).val();
        let carrier_name = $('#carrier_name_' + id).val();
        let driver_payment = $('#driver_payment_' + id).val();
        let driver_no = $('#driver_no_' + id).val();
        let driver_no2 = $('#driver_no2_' + id).val();
        let driver_no3 = $('#driver_no3_' + id).val();
        let driver_no4 = $('#driver_no4_' + id).val();
        let payment = $('#payment_' + id).val();
        let company_name = $('#company_name_' + id).val();
        let additional = $('#additional_' + id).val();

        $.ajax({
            url: window.location.origin + "/pickedup_sheet/" +
                oid, // Url of backend (can be python, php, etc..)
            type: "GET", // data type (can be get, post, put, delete)
            data: {
                company_name: company_name,
                driver_no: driver_no,
                price: price,
                driver_no2: driver_no2,
                driver_no3: driver_no3,
                driver_no4: driver_no4,
                driver_payment: driver_payment,
                carrier_name: carrier_name,
                payment_method: payment_method,
                payment_charged_or_owes: payment_charged_or_owes,
                delivery_date: delivery_date,
                driver_status: driver_status,
                storage: storage,
                vehicle_condition: condition,
                title_keys: title_keys,
                keys: keys,
                vehicle_position: vehicle_position,
                payment: payment,
                additional: additional
            }, // data in json format
            async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
            success: function(response) {
                if (response === 'true') {
                    Swal.fire(
                        'Success!',
                        'Driver Picked Up Sheet Updated!',
                        'success'
                    )
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {}
        });
    }

    function auctionpickedUpUpload(id) {
        let oid = $('#orderId_' + id).val();
        let storage = $('#storage1_' + id).val();
        let condition = $('#condition1_' + id).val();
        let title_keys = $('#title_keys1_' + id).val();
        let keys = $('#keys1_' + id).val();
        let vehicle_position = $('#vehicle_position1_' + id).val();
        let auction_status = $('#auction_status1_' + id).val();
        let additional = $('#additional1_' + id).val();

        $.ajax({
            url: window.location.origin + "/auction_pickedup_sheet/" +
                oid, // Url of backend (can be python, php, etc..)
            type: "GET", // data type (can be get, post, put, delete)
            data: {
                storage: storage,
                vehicle_condition: condition,
                title_keys: title_keys,
                keys: keys,
                vehicle_position: vehicle_position,
                auction_status: auction_status,
                additional: additional
            }, // data in json format
            async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
            success: function(response) {
                if (response === 'true') {
                    Swal.fire(
                        'Success!',
                        'Auction Picked Up Sheet Updated!',
                        'success'
                    )
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {}
        });
    }

    function deliveryUpload(id) {
        let oid = $('#orderId_' + id).val();
        let driver_no = $('#driver_no_' + id).val();
        let driver_no2 = $('#driver_no2_' + id).val();
        let driver_no3 = $('#driver_no3_' + id).val();
        let driver_no4 = $('#driver_no4_' + id).val();
        let driver_payment_status = $('#driver_payment_status_' + id).val();
        let vehicle_condition = $('#condition_' + id).val();
        let vehicle_position = $('#vehicle_position_' + id).val();
        let customer_informed = $('#customer_informed_' + id).val();
        let who_pay_storage = $('#who_pay_storage_' + id).val();
        let title_keys = $('#title_keys_' + id).val();
        let keys = $('#keys_' + id).val();
        let delivery_date = $('#delivery_date_' + id).val();
        let client_status = $('#client_status_' + id).val();
        let driver_status = $('#driver_status_' + id).val();
        let owes_status = $('#owes_status_' + id).val();
        let additional = $('#additional_' + id).val();

        $.ajax({
            url: window.location.origin + "/delivery_sheet/" +
                oid, // Url of backend (can be python, php, etc..)
            type: "GET", // data type (can be get, post, put, delete)
            data: {
                customer_informed: customer_informed,
                vehicle_condition: vehicle_condition,
                driver_payment_status: driver_payment_status,
                driver_no: driver_no,
                driver_no2: driver_no2,
                driver_no3: driver_no3,
                driver_no4: driver_no4,
                vehicle_position: vehicle_position,
                delivery_date: delivery_date,
                who_pay_storage: who_pay_storage,
                client_status: client_status,
                title_keys: title_keys,
                keys: keys,
                driver_status: driver_status,
                owes_status: owes_status,
                additional: additional
            }, // data in json format
            async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
            success: function(response) {
                if (response === 'true') {
                    Swal.fire(
                        'Success!',
                        'Delivery Sheet Updated!',
                        'success'
                    )
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {}
        });
    }

    function completedUpload(id) {
        let oid = $('#orderId_' + id).val();
        let remarks = $('#remarks_' + id).val();
        let comments = $('#comments_' + id).val();
        let satisfied = $('#satisfied_' + id).val();
        let review = $('#review_' + id).val();
        let website = $('#website_' + id).val();
        let website_other = $('#website_other_' + id).val();
        let website_link = $('#website_link_' + id).val();
        let client_rating = $('#client_rating_' + id).val();
        let additional = $('#additional_' + id).val();

        $.ajax({
            url: window.location.origin + "/completed_sheet/" +
                oid, // Url of backend (can be python, php, etc..)
            type: "GET", // data type (can be get, post, put, delete)
            data: {
                remarks: remarks,
                comments: comments,
                satisfied: satisfied,
                review: review,
                website: website,
                website_other: website_other,
                website_link: website_link,
                client_rating: client_rating,
                additional: additional
            }, // data in json format
            async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
            success: function(response) {
                if (response === 'true') {
                    Swal.fire(
                        'Success!',
                        'Completed Sheet Updated!',
                        'success'
                    )
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {}
        });
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
</script>

<script>
    var getData = (id) => {
        $.ajax({
            url: "{{ url('/get_shipment_status_order_detail') }}",
            type: "GET",
            data: {
                id: id
            },
            dataType: "HTML",
            success: function(res) {
                $("#detail_order").html('');
                $("#detail_order").html(res);
                $("#instr_id").val(id);
                $("#specialInsModelLabel").html(`Special Instruction of Order Id#${id}`);
                $("#specialInstruction").html(
                    `<button type="button" onclick="$('#exampleModal').modal('hide');" class="btn btn-primary" data-toggle="modal" data-target="#specialIns">Special Instruction</button>`
                );
            }
        });
    }

    var getData2 = (id) => {
        $.ajax({
            url: "{{ url('/show_last_two_history') }}",
            type: "GET",
            data: {
                id: id
            },
            dataType: "html",
            success: function(res) {
                $("#viewCancelHistoryTitle").html(
                    `View Cancel History Of OrderId#<span class="text-primary ml-1">${id}</span>`);
                $("#cancel_history").html('');
                $("#cancel_history").html(res);
            }
        });
    }

    var getData3 = (id) => {
        $("#order_id_request").val(id);
    }

    $(".readmore").on('click', function() {
        $(this).parents('.less').hide();
        $(this).parents('.less').siblings('.more').show();
    });

    $(".readless").on('click', function() {
        $(this).parents('.more').hide();
        $(this).parents('.more').siblings('.less').show();
    });

    // $(".add-approaching").click(function() {
    //     var order_id = $(this).find('.Get-Order-ID').val();

    //     console.log('order_id', order_id);

    //     $(".show_order_id").html(order_id);
    //     $("#val_order_id").val(order_id);

    //     $.ajax({
    //         url: '{{ route('get.carrier_approachings') }}',
    //         type: 'GET',
    //         data: {
    //             'order_id': order_id,
    //         },
    //         success: function(data) {
    //             // Handle the success response
    //             console.log('datas', data);
    //         },
    //         error: function(error) {
    //             // Handle the error response
    //             console.error('Error submitting the form:', error);
    //             // Optionally, you can display an error message or take other actions
    //         }
    //     });
    // });

    function getIdPhone(id, status, name, phone, origin, destination, vehicle) {
        $("#authorization-orderId").val(id);
        $("#authorization-status").val(status);
        $("#authorization-cname").val(name);
        $("#authorization-cphone").val(phone);
        $("#authorization-origin").val(origin);
        $("#authorization-destination").val(destination);
        $("#authorization-vehicle").val(vehicle);

        // console.log('id', 'status', 'name', 'phone', id, status, name, phone, origin, destination);
    }
</script>
<script>
    var phoneaccessArray = <?php echo $phoneaccessJson; ?>;

    function call(num) {
        console.log('onclick call');
        var num1 = atob(num);

        var check_panel = '{{ $check_panel }}';
        var check_call = '{{ $check_call }}';

        if (check_call == 134) {
            var formattedNum = num1.replace(/\D/g, '');
            window.location.href = 'tel:' + formattedNum;
        } else if (check_call == 135) {
            window.location.href = 'rcmobile://call/?number=' + num1;
        }

        var id = $("#orderId").val();
        $.ajax({
            url: "{{ url('/notRes') }}",
            type: "GET",
            data: {
                id: id
            },
            success: function(res) {
                console.log('Response from server:', res);
            },
            error: function(err) {
                console.error('AJAX error:', err);
            }
        });
    }


    function call2(num) {
        var num1 = atob(num);
        var check_panel = '{{ $check_panel }}';
        var check_call = '{{ $check_call }}';

        if (check_call == 134) {
            var formattedNum = num1.replace(/\D/g, '');
            window.location.href = 'tel:' + formattedNum;
        } else if (check_call == 135) {
            window.location.href = 'rcmobile://call/?number=' + num1;
        }
    }

    function msg(num) {
        var num1 = atob(num);
        var check_panel = '{{ $check_panel }}';
        var check_call = '{{ $check_call }}';

        if (check_call == 134) {
            var formattedNum = num1.replace(/\D/g, '');
            // window.location.href = 'sms:' + formattedNum;
            window.location.href = 'rcmobile://sms/?number=' + num1;
        } else if (check_call == 135) {
            window.location.href = 'rcmobile://sms/?number=' + num1;
        }
    }
</script>
