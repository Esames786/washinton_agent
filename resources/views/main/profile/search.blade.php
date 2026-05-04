@include('partials.mainsite_pages.return_function')
<?php
$phoneaccess = [];
if (isset($user->id)) {
    $phoneaccess = explode(',', $user->emp_access_profile);
} else {
    $phoneaccess = explode(',', Auth::user()->emp_access_profile);
}

function reporting($status, $from, $too, $user_id, $role)
{
    $report = \App\report::whereHas('order', function ($q) use ($user_id, $status, $role) {
        if ($role == 'Order Taker' || $role == 'CSR' || $role == 'Seller Agent') {
            if ($status == '0') {
                $q->where('order_taker_id', '=', $user_id);
            } else {
                $q->where('u_id', '=', $user_id);
            }
        } elseif ($role == 'Manager') {
            if ($status == '0') {
                $q->where('order_taker_id', '=', $user_id)->orWhere('manager_id', '=', $user_id);
            } else {
                $q->where('u_id', '=', $user_id);
            }
        } elseif ($role == 'Dispatcher') {
            $q->where('dispatcher_id', '=', $user_id);
        }
    })
        ->where('pstatus', '=', $status)
        ->whereBetween('created_at', [$from, $too])
        ->count();

    return $report;
}
?>
<div class="row">
    @if (isset($user->id))
        @if (get_user($user->role, 'name') == 'Dispatcher' || get_user($user->role, 'name') == 'Delivery Boy')
            <div class="col-sm-3">
                <div class="card mt-5" style="height:160px;">
                    <div class="card-header bg-light ">
                        <h3 class="my-auto">
                            {{ $user->name . ' ' . $user->last_name }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <p class="card-title">Sudo Name: <b>{{ $user->slug ?? 'N/A' }}</b></p>
                        <p class="card-title">Role: <b>{{ get_user($user->role, 'name') }}</b></p>
                        <!--<p class="card-title">Phone: <b>{{ $user->phone ?? 'N/A' }}</b></p>-->
                        <!--<p class="card-text">Address: <b>{{ $user->address ?? 'N/A' }}</b></p>-->
                    </div>
                </div>
            </div>
            @if (get_user($user->role, 'name') == 'Delivery Boy')
                <div class="col-sm-3">
                    <div class="card mt-5 overflow-hidden dash1-card border-0" style="">
                        <div class="card-header">
                            <h3 class="my-auto">
                                Pickup Order <span class="ratio bg-danger"><i class="fa fa-level-down"></i></span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <h2 class="mb-1 number-font">{{ $private_pickup }}</h2>
                            <p>Total Earned: </p>
                            <h2 class="mb-1 number-font">{{ $user->private_pickup * $private_pickup }}</h2>
                        </div>
                        <div id="spark1"></div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card mt-5 overflow-hidden dash1-card border-0" style="">
                        <div class="card-header">
                            <h3 class="my-auto">
                                Review <span class="ratio bg-danger"><i class="fa fa-level-down"></i></span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <h2 class="mb-1 number-font">{{ $per_review }}</h2>
                            <p>Total Earned: </p>
                            <h2 class="mb-1 number-font">{{ $user->per_review * $per_review }}</h2>
                        </div>
                        <div id="spark1"></div>
                    </div>
                </div>
            @elseif (get_user($user->role, 'name') == 'Dispatcher')
                <div class="col-sm-3">
                    <div class="card mt-5 overflow-hidden dash1-card border-0" style="">
                        <div class="card-header">
                            <h3 class="my-auto">
                                Total Dispatch <span class="ratio bg-danger"><i class="fa fa-level-down"></i></span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <h2 class="mb-1 number-font">{{ $total_dispatch }}</h2>
                        </div>
                        <div id="spark1"></div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card mt-5 overflow-hidden dash1-card border-0" style="">
                        <div class="card-header">
                            <h3 class="my-auto">
                                Total Pickup <span class="ratio bg-danger"><i class="fa fa-level-down"></i></span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <h2 class="mb-1 number-font">{{ $total_pickup }}</h2>
                        </div>
                        <div id="spark1"></div>
                    </div>
                </div>
            @endif
            @if (get_user($user->role, 'name') != 'Dispatcher')
                <div class="col-sm-3">
                    <div class="card mt-5 overflow-hidden dash1-card border-0" style="">
                        <div class="card-header">
                            <h3 class="my-auto">
                                Delivered Order <span class="ratio bg-info"><i class="fa fa-level-up"></i></span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <h2 class="mb-1 number-font">{{ $delivered_order }}</h2>
                            <h2 class="mb-1 number-font">{{ $delivered_order * $user->commission }}</h2>
                        </div>
                        <div id="spark1"></div>
                    </div>
                </div>
            @endif
            @if (get_user($user->role, 'name') == 'Dispatcher')
                <div class="col-sm-3">
                    <div class="card mt-5 overflow-hidden dash1-card border-0" style="">
                        <div class="card-header">
                            <h3 class="my-auto">
                                Pickup/Delivery Commission <span class="ratio bg-success"><i
                                        class="fa fa-usd"></i></span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <p>Flat Commission: </p>
                            <h2 class="mb-1 number-font">{{ $flat_commision }}</h2>
                            <p>Dispatch By: </p>
                            <h2 class="mb-1 number-font">{{ $dispatched_by }}</h2>
                            <p>Achieved Commission: </p>
                            <h2 class="mb-1 number-font">{{ $achieved_commision }}</h2>
                        </div>
                        <div id="spark1"></div>
                    </div>
                </div>
            @else
                <div class="col-sm-3">
                    <div class="card mt-5 overflow-hidden dash1-card border-0" style="">
                        <div class="card-header">
                            <h3 class="my-auto">
                                Pickup/Delivery Commission <span class="ratio bg-success"><i
                                        class="fa fa-usd"></i></span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <p>Total Commission: </p>
                            @php
                                $deliveredCommision = $delivered_order * $user->commission;
                                $private_pickupCommission = $user->private_pickup * $private_pickup;
                                $per_reviewCommission = $user->per_review * $per_review;
                                $commission = $private_pickupCommission + $deliveredCommision + $per_reviewCommission;
                            @endphp
                            <h2 class="mb-1 number-font">{{ $commission }}</h2>
                            @php
                                if ($review_less_than == 0) {
                                    $commission = $commission - $review_target_achieved;
                                } else {
                                    $commission = $commission + $review_target_achieved;
                                }
                            @endphp
                            <p>Cancellation: </p>
                            <h2 class="mb-1 number-font">{{ $cancellation_deduction }}</h2>
                            <p>Total Earned: </p>
                            @php
                                $cancellationRes = $user->cancellation_amount * $commisionCancellation;
                            @endphp
                            <h2 class="mb-1 number-font">{{ $commission - $cancellation_deduction }}</h2>
                        </div>
                        <div id="spark1"></div>
                    </div>
                </div>
            @endif
        @else
            <div class="col-sm-9">
                <div class="card mt-5" style="">
                    <div class="card-header bg-light ">
                        <h3 class="my-auto">
                            {{ $user->name . ' ' . $user->last_name }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <p class="card-title">Sudo Name: <b>{{ $user->slug ?? 'N/A' }}</b></p>
                        <p class="card-title">Role: <b>{{ get_user($user->role, 'name') }}</b></p>
                        <!--<p class="card-title">Phone: <b>{{ $user->phone ?? 'N/A' }}</b></p>-->
                        <!--<p class="card-text">Address: <b>{{ $user->address ?? 'N/A' }}</b></p>-->
                    </div>
                </div>
            </div>
            {{-- @php
                $delivered_order = $user->delivered_order ?? 0;
                $revenue = $user->revenue ?? 0;
                $total_booked_order = $user->total_booked_order ?? 0;
                $cancellation = $user->cancellation ?? 0;

                $average = $revenue / $delivered_order;

                $range = \App\CommissionRange::where('from_order', '<=', $delivered_order)
                    ->where('to_order', '>=', $delivered_order)
                    ->where('from_avg', '<=', $average)
                    ->where('to_avg', '>=', $average)
                    ->first();

                // dd('revenue' . $revenue, 'delivered_order' . $delivered_order, 'average' . $average, '$range' . $range);

                if (isset($range)) {
                    $comission = $range->commission;
                }
            @endphp --}}
            <div class="col-sm-3">
                <div class="card mt-5 overflow-hidden dash1-card border-0">
                    <div class="card-header">
                        <h3 class="my-auto">
                            Commission <span class="ratio bg-success"><i class="fa fa-usd"></i></span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <p>Total Commission: </p>
                        <h2 class="mb-1 number-font">{{ $commission }}</h2>
                        @if ($review_less_than == 0)
                            <p>Not Achieved: </p>
                            <h2 class="mb-1 number-font">{{ $review_target_achieved }}</h2>
                        @else
                            <p>Review Target Achieved: </p>
                            <h2 class="mb-1 number-font">{{ $review_target_achieved }}</h2>
                        @endif
                        @php
                            if ($review_less_than == 0) {
                                $commission = $commission - $review_target_achieved;
                            } else {
                                $commission = $commission + $review_target_achieved;
                            }
                        @endphp
                        <p>Cancellation: </p>
                        <h2 class="mb-1 number-font">{{ $cancellation_deduction }}</h2>
                        <p>Total Earned: </p>
                        @php
                            // $cancellationRes = $user->cancellation_amount * $commisionCancellation;
                        @endphp
                        <h2 class="mb-1 number-font">{{ $commission - $cancellation_deduction }}</h2>
                    </div>
                    <div id="spark1"></div>
                </div>
            </div>
        @endif
    @else
        <div class="col-sm-3">
            <div class="card mt-5 overflow-hidden dash1-card border-0" style="height:160px;">
                <div class="card-header">
                    <h3 class="my-auto">
                        Commission <span class="ratio bg-success"><i class="fa fa-usd"></i></span>
                    </h3>
                </div>
                <div class="card-body">
                    <h2 class="mb-1 number-font">{{ $profit }}</h2>
                </div>
                <div id="spark1"></div>
            </div>
        </div>
    @endif
</div>
@if (isset($user->id))
    <div class="row">
        @if (get_user($user->role, 'name') == 'Manager' ||
                get_user($user->role, 'name') == 'Order Taker' ||
                get_user($user->role, 'name') == 'CSR' ||
                get_user($user->role, 'name') == 'Seller Agent')
            <div class="col-sm-4">
                <div class="card mt-5">
                    <div class="card-header bg-light  justify-content-center">
                        Delivered Order
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col" id="dailyPhn">
                                {{-- <p class="text-center dailyPhn" style="color:#868ba1;cursor: pointer">Today</p> --}}
                                <p class="text-muted text-center">
                                    {{ $delivered_order }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card mt-5">
                    <div class="card-header bg-light  justify-content-center">
                        Total Booked Order
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col" id="dailyCom">
                                {{-- <p class="text-center dailyCom" style="color:#868ba1;cursor: pointer">Today</p> --}}
                                <p class="text-muted text-center">
                                    {{ $total_booked_order }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card mt-5">
                    <div class="card-header bg-light  justify-content-center">
                        Average
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col" id="dailyCom">
                                {{-- <p class="text-center dailyCom" style="color:#868ba1;cursor: pointer">Today</p> --}}
                                <p class="text-muted text-center">
                                    {{ $average }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card mt-5">
                    <div class="card-header bg-light  justify-content-center">
                        Revenue
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col" id="dailyCom">
                                {{-- <p class="text-center dailyCom" style="color:#868ba1;cursor: pointer">Today</p> --}}
                                <p class="text-muted text-center">
                                    {{ $revenue }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card mt-5">
                    <div class="card-header bg-light  justify-content-center">
                        Cancellation
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col" id="dailyCom">
                                {{-- <p class="text-center dailyCom" style="color:#868ba1;cursor: pointer">Today</p> --}}
                                <p class="text-muted text-center">
                                    {{ $cancellation }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-sm-4">
                <div class="card mt-5">
                    <div class="card-header bg-light  justify-content-center">
                        Total Cancel Order Report
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col" id="dailyCanel">
                                <p class="text-center dailyCanel" style="color:#868ba1;cursor: pointer">Today</p>
                                <p class="text-muted text-center">
                                    {{ reporting('14', \Carbon\Carbon::now()->format('Y-m-d 00:00:00'), \Carbon\Carbon::now()->format('Y-m-d 23:59:59'), $user->id, get_user($user->role, 'name')) }}
                                </p>
                            </div>
                            <div class="col" id="weeklyCanel">
                                <p class="text-center weeklyCanel" style="color:#868ba1;cursor: pointer">This Week</p>
                                <p class="text-muted text-center">
                                    {{ reporting('14', \Carbon\Carbon::now()->subDays(7)->format('Y-m-d 00:00:00'), \Carbon\Carbon::now()->format('Y-m-d 23:59:59'), $user->id, get_user($user->role, 'name')) }}
                                </p>
                            </div>
                            <div class="col" id="monthlyCanel">
                                <p class="text-center monthlyCanel" style="color:#868ba1;cursor: pointer">This Month
                                </p>
                                <p class="text-muted text-center">
                                    {{ reporting('14', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00'), \Carbon\Carbon::now()->format('Y-m-d 23:59:59'), $user->id, get_user($user->role, 'name')) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        @elseif(get_user($user->role, 'name') == 'Dispatcher')
            {{-- <div class="col-sm-4">
                <div class="card mt-5">
                    <div class="card-header bg-light  justify-content-center">
                        Flat Commision
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col" id="dailyPhn">
                                <p class="text-muted text-center">
                                    {{ $flat_commision }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card mt-5">
                    <div class="card-header bg-light  justify-content-center">
                        Achieved Commision
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col" id="dailyPhn">
                                <p class="text-muted text-center">
                                    {{ $achieved_commision }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        @endif
    </div>
@endif
<div class="row" id="allData">
    @if (in_array('0', $phoneaccess))
        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="javascript:void(0)" class="showQuotes" data-value="0">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1 "> New</p>
                        <h2 class="mb-1 number-font">{{ $new }}</h2>
                        <span class="ratio bg-orange"><i class="fa fa-car"></i></span>
                    </div>
                    <div id="spark1"></div>
                </div>
            </a>
        </div>
    @endif
    @if (in_array('1', $phoneaccess))
        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="javascript:void(0)" class="showQuotes" data-value="1">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1 "> Interested</p>
                        <h2 class="mb-1 number-font">{{ $int }}</h2>
                        <span class="ratio bg-warning"><i class="fa fa-thumbs-up"></i></span>
                    </div>
                    <div id="spark1"></div>
                </div>
            </a>
        </div>
    @endif
    @if (in_array('2', $phoneaccess))
        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="javascript:void(0)" class="showQuotes" data-value="2">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1 "> Follow More</p>
                        <h2 class="mb-1 number-font">{{ $fm }}</h2>
                        <span class="ratio bg-primary"><i class="fa fa-tasks"></i></span>
                    </div>
                    <div id="spark1"></div>
                </div>
            </a>
        </div>
    @endif
    @if (in_array('3', $phoneaccess))
        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="javascript:void(0)" class="showQuotes" data-value="3">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1 "> Asking Low</p>
                        <h2 class="mb-1 number-font">{{ $al }}</h2>
                        <span class="ratio bg-pink"><i class="fa fa-level-down"></i></span>
                    </div>
                    <div id="spark1"></div>
                </div>
            </a>
        </div>
    @endif
    @if (in_array('4', $phoneaccess))
        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="javascript:void(0)" class="showQuotes" data-value="4">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1 "> Not Interested</p>
                        <h2 class="mb-1 number-font">{{ $not_int }}</h2>
                        <span class="ratio bg-success"><i class="fa fa-thumbs-down"></i></span>
                    </div>
                    <div id="spark1"></div>
                </div>
            </a>
        </div>
    @endif
    @if (in_array('5', $phoneaccess))
        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="javascript:void(0)" class="showQuotes" data-value="5">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1 "> No Response</p>
                        <h2 class="mb-1 number-font">{{ $nr }}</h2>
                        <span class="ratio bg-dark"><i class="fa fa-phone-square"></i></span>
                    </div>
                    <div id="spark1"></div>
                </div>
            </a>
        </div>
    @endif
    @if (in_array('6', $phoneaccess))
        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="javascript:void(0)" class="showQuotes" data-value="6">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1 "> Time Quote</p>
                        <h2 class="mb-1 number-font">{{ $tq }}</h2>
                        <span class="ratio bg-amber"><i class="fa fa-clock-o"></i></span>
                    </div>
                    <div id="spark1"></div>
                </div>
            </a>
        </div>
    @endif
    @if (in_array('7', $phoneaccess))
        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="javascript:void(0)" class="showQuotes" data-value="7">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1 "> Payment Missing</p>
                        <h2 class="mb-1 number-font">{{ $pm }}</h2>
                        <span class="ratio bg-primary"><i class="fa fa-money"></i></span>
                    </div>
                    <div id="spark1"></div>
                </div>
            </a>
        </div>
    @endif
    @if (in_array('18', $phoneaccess))
        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="javascript:void(0)" class="showQuotes" data-value="18">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1 "> OnApproval</p>
                        <h2 class="mb-1 number-font">{{ $oa }}</h2>
                        <span class="ratio bg-primary"><i class="fa fa-quora"></i></span>
                    </div>
                    <div id="spark1"></div>
                </div>
            </a>
        </div>
    @endif
    @if (in_array('8', $phoneaccess))
        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="javascript:void(0)" class="showQuotes" data-value="8">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1 "> Booked</p>
                        <h2 class="mb-1 number-font">{{ $book }}</h2>
                        <span class="ratio bg-warning"><i class="fa fa-book"></i></span>
                    </div>
                    <div id="spark1"></div>
                </div>
            </a>
        </div>
    @endif
    @if (in_array('9', $phoneaccess))
        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="javascript:void(0)" class="showQuotes" data-value="9">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1 "> Listed</p>
                        <h2 class="mb-1 number-font">{{ $list }}</h2>
                        <span class="ratio bg-pink"><i class="fa fa-list"></i></span>
                    </div>
                    <div id="spark1"></div>
                </div>
            </a>
        </div>
    @endif
    @if (in_array('10', $phoneaccess))
        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="javascript:void(0)" class="showQuotes" data-value="10">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1 "> Schedule</p>
                        <h2 class="mb-1 number-font">{{ $dis }}</h2>
                        <span class="ratio bg-success"><i class="fa fa-truck"></i></span>
                    </div>
                    <div id="spark1"></div>
                </div>
            </a>
        </div>
    @endif
    @if (in_array('30', $phoneaccess))
        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="javascript:void(0)" class="showQuotes" data-value="30">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1 "> Pickup Approval</p>
                        <h2 class="mb-1 number-font">{{ $pick_app }}</h2>
                        <span class="ratio bg-dark"><i class="fa fa-map"></i></span>
                    </div>
                    <div id="spark1"></div>
                </div>
            </a>
        </div>
    @endif
    @if (in_array('11', $phoneaccess))
        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="javascript:void(0)" class="showQuotes" data-value="11">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1 "> Pickup</p>
                        <h2 class="mb-1 number-font">{{ $pick }}</h2>
                        <span class="ratio bg-primary"><i class="fa fa-level-up"></i></span>
                    </div>
                    <div id="spark1"></div>
                </div>
            </a>
        </div>
    @endif
    @if (in_array('31', $phoneaccess))
        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="javascript:void(0)" class="showQuotes" data-value="31">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1 "> Delivery Approval</p>
                        <h2 class="mb-1 number-font">{{ $del_app }}</h2>
                        <span class="ratio bg-amber"><i class="fa fa-check"></i></span>
                    </div>
                    <div id="spark1"></div>
                </div>
            </a>
        </div>
    @endif
    @if (in_array('32', $phoneaccess))
        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="javascript:void(0)" class="showQuotes" data-value="32">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1 "> Schedule For Delivery</p>
                        <h2 class="mb-1 number-font">{{ $sfd }}</h2>
                        <span class="ratio bg-success"><i class="fa fa-level-up"></i></span>
                    </div>
                    <div id="spark1"></div>
                </div>
            </a>
        </div>
    @endif
    @if (in_array('12', $phoneaccess))
        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="javascript:void(0)" class="showQuotes" data-value="12">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1 "> Delivered</p>
                        <h2 class="mb-1 number-font">{{ $del }}</h2>
                        <span class="ratio bg-pink"><i class="fe fe-box"></i></span>
                    </div>
                    <div id="spark1"></div>
                </div>
            </a>
        </div>
    @endif
    @if (in_array('13', $phoneaccess))
        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="javascript:void(0)" class="showQuotes" data-value="13">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1 "> Completed</p>
                        <h2 class="mb-1 number-font">{{ $com }}</h2>
                        <span class="ratio bg-success"><i class="fa fa-check-circle"></i></span>
                    </div>
                    <div id="spark1"></div>
                </div>
            </a>
        </div>
    @endif
    @if (in_array('14', $phoneaccess))
        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="javascript:void(0)" class="showQuotes" data-value="14">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1 "> Cancel</p>
                        <h2 class="mb-1 number-font">{{ $can }}</h2>
                        <span class="ratio bg-danger"><i class="fa fa-close"></i></span>
                    </div>
                    <div id="spark1"></div>
                </div>
            </a>
        </div>
    @endif
    @if (in_array('19', $phoneaccess))
        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="javascript:void(0)" class="showQuotes" data-value="19">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1 "> OnApprovalCancel</p>
                        <h2 class="mb-1 number-font">{{ $opcan }}</h2>
                        <span class="ratio bg-info"><i class="fa fa-window-close"></i></span>
                    </div>
                    <div id="spark1"></div>
                </div>
            </a>
        </div>
    @endif
    @if (in_array('20', $phoneaccess))
        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="javascript:void(0)" class="showQuotes" data-value="20">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1 "> Review Order</p>
                        <h2 class="mb-1 number-font">{{ $rating }}</h2>
                        <span class="ratio bg-warning"><i class="fa fa-star"></i></span>
                    </div>
                    <div id="spark1"></div>
                </div>
            </a>
        </div>
    @endif
    @if (in_array('21', $phoneaccess))
        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="javascript:void(0)" class="showQuotes" data-value="21">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1 "> Cancel Remark By (Admin/HOD/TeamLead)</p>
                        <h2 class="mb-1 number-font">{{ $cancellation }}</h2>
                        <span class="ratio bg-danger"><i class="fa fa-close"></i></span>
                    </div>
                    <div id="spark1"></div>
                </div>
            </a>
        </div>
    @endif
</div>
<div class="row" id="tableData">
</div>
