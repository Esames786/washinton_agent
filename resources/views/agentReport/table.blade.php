<form action="{{ route('agent.report.save') }}" method="POST">
    @csrf
    <input type="hidden" name="date_range" id="hidden_date_range" value="{{ $date_range }}" />
    <table id="" class="agentsTable table table-bordered table-striped text-nowrap key-buttons">
        <thead>
            <tr>
                <th>Sr.#</th>
                <th>Agent</th>
                <th>
                    @if ($paneltype == 1)
                        PQuote Count
                    @elseif($paneltype == 2)
                        WebQuote Count
                    @endif
                </th>
                <th>Need to Book</th>
                <th>Followup Achieve</th>
                <th>Followup Target Achieve</th>
                <th>Order Target</th>
                <th>Order Achieve</th>
                <th>On App Order</th>
                <th>Cancelled</th> <!-- Ensure this matches controller's 'canceled_count' -->
                <th>On App Cancelled</th>
                <th>Followup Target</th>
                <th>Review Target</th>
                <th>Revenew Target</th>
                <th>Review Achieve</th>
                <th>QA Negative</th>
                <th>QA Verified</th>
                <th>HOD Remarks</th> <!--  Further Issue -->
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $row)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>
                        {{ $row->orderTaker ? $row->orderTaker->name . ' ' . $row->orderTaker->last_name : 'N/A' }}
                        <input type="hidden" name="agents[{{ $key }}][order_taker_id]"
                            value="{{ old('agents.' . $key . '.order_taker_id', $row->order_taker_id) }}" />
                    </td>
                    <td>
                        <input type="number" readonly name="agents[{{ $key }}][pquote_count]"
                            value="{{ old('agents.' . $key . '.pquote_count', $row->pquote_count) }}"
                            class="form-control" />
                    </td>
                    <td>
                        <input type="number" readonly name="agents[{{ $key }}][need_to_book]"
                            value="{{ old('agents.' . $key . '.need_to_book', $row->need_to_book) }}"
                            class="form-control" />
                    </td>
                    <td>
                        <input type="number" name="agents[{{ $key }}][followup_achieve]"
                            value="{{ old('agents.' . $key . '.followup_achieve', $row->followup_count) }}"
                            class="form-control" />
                    </td>
                    <td>
                        <input type="number" name="agents[{{ $key }}][followup_target_achieve]"
                            value="{{ old('agents.' . $key . '.followup_target_achieve', $row->followup_target_achieve) }}"
                            class="form-control" />
                    </td>
                    <td>
                        @php
                            $targetOrder = 0;
                            $followupOrder = 0;
                            $reviewOrder = 0;
                            $revenewOrder = 0;

                            $dateRange = explode(' - ', $date_range);
                            $startDate = new DateTime($dateRange[0]);
                            $endDate = new DateTime($dateRange[1]);

                            $daysCount = 0;
                            for ($date = clone $startDate; $date <= $endDate; $date->modify('+1 day')) {
                                if ($date->format('N') != 7) {
                                    $daysCount++;
                                }
                            }

                            foreach ($userTargets as $key => $order) {
                                $targetMonth = DateTime::createFromFormat('Y-m', $order->target_month);
                                $firstDayOfTargetMonth = (clone $targetMonth)
                                    ->modify('first day of this month')
                                    ->modify('-1 day');
                                $lastDayOfTargetMonth = (clone $targetMonth)
                                    ->modify('last day of this month')
                                    ->modify('+1 day');

                                if (
                                    $order->user_id == $row->order_taker_id &&
                                    $startDate >= $firstDayOfTargetMonth &&
                                    $endDate <= $lastDayOfTargetMonth
                                ) {
                                    $targetOrder = $daysCount * $order->order_target;
                                    $followupOrder = $daysCount * $order->followup_target;
                                    $reviewOrder = $daysCount * $order->review_target;
                                    $revenewOrder = $order->revenew_target;

                                    break;
                                }
                            }
                        @endphp

                        <input type="number" readonly name="agents[{{ $key }}][order_target]"
                            value="{{ $targetOrder }}" class="form-control" />
                    </td>
                    <td>
                        <input type="number" readonly name="agents[{{ $key }}][order_achieve]"
                            value="{{ old('agents.' . $key . '.order_achieve', $row->order_achieve) }}"
                            class="form-control" />
                    </td>
                    <td>
                        <input type="number" name="agents[{{ $key }}][on_app_order]"
                            value="{{ old('agents.' . $key . '.on_app_order', $row->on_app_order) }}"
                            class="form-control" />
                    </td>
                    <td>
                        <input type="number" name="agents[{{ $key }}][cancelled]"
                            value="{{ old('agents.' . $key . '.cancelled', $row->canceled_count) }}"
                            class="form-control" />
                    </td>
                    <td>
                        <input type="number" name="agents[{{ $key }}][on_app_cancelled]"
                            value="{{ old('agents.' . $key . '.on_app_cancelled', $row->canceled_onapproval) }}"
                            class="form-control" />
                    </td>
                    <td>
                        <input type="number" readonly name="agents[{{ $key }}][followup_target]"
                            value="{{ $followupOrder }}" class="form-control" />
                    </td>
                    <td>
                        <input type="number" readonly name="agents[{{ $key }}][review_target]"
                            value="{{ $reviewOrder }}" class="form-control" />
                    </td>
                    <td>
                        <input type="number" readonly name="agents[{{ $key }}][revenew_target]"
                            value="{{ $revenewOrder }}" class="form-control" />
                    </td>

                    <td>
                        {{-- Sheet Details --}}
                        @php
                            $rawDetailsCount = 0; // Default value
                        @endphp

                        @foreach ($sheetDetails as $col)
                            @if ($row->order_taker_id == $col['user_id'])
                                <input type="text" readonly name="agents[{{ $key }}][review_achieve]"
                                    value="{{ old('agents.' . $key . '.review_achieve', $col['count']) }}"
                                    class="form-control" />
                                @php
                                    $rawDetailsCount = $col['count']; // Store the count if there's a match
                                @endphp
                            @break; // Exit the loop after finding the match
                        @endif
                    @endforeach

                    @if ($rawDetailsCount === 0)
                        <input type="text" readonly name="agents[{{ $key }}][review_achieve]"
                            value="{{ old('agents.' . $key . '.review_achieve', 0) }}" class="form-control" />
                    @endif
                </td>
                <td>
                    {{-- QA Negative --}}
                    @php
                        $rawDetailsCount = 0; // Default value
                    @endphp

                    @foreach ($negativeCounts as $col)
                        @if ($row->order_taker_id == $col['user_id'])
                            <input type="text" readonly name="agents[{{ $key }}][raw_details]"
                                value="{{ old('agents.' . $key . '.raw_details', $col['count']) }}"
                                class="form-control" />
                            @php
                                $rawDetailsCount = $col['count']; // Store the count if there's a match
                            @endphp
                        @break; // Exit the loop after finding the match
                    @endif
                @endforeach

                @if ($rawDetailsCount === 0)
                    <input type="text" readonly name="agents[{{ $key }}][raw_details]"
                        value="{{ old('agents.' . $key . '.raw_details', 0) }}" class="form-control" />
                @endif
            </td>
            <td>
                {{-- QA Verified --}}
                @php
                    $recordingIssueCount = 0; // Default value
                @endphp

                @foreach ($verifiedCounts as $col)
                    @if ($row->order_taker_id == $col['user_id'])
                        <input type="text" readonly name="agents[{{ $key }}][recording_issue]"
                            value="{{ old('agents.' . $key . '.recording_issue', $col['count']) }}"
                            class="form-control" />
                        @php
                            $recordingIssueCount = $col['count']; // Store the count if there's a match
                        @endphp
                    @break; // Exit the loop after finding the match
                @endif
            @endforeach

            @if ($recordingIssueCount === 0)
                <input type="text" readonly name="agents[{{ $key }}][recording_issue]"
                    value="{{ old('agents.' . $key . '.recording_issue', 0) }}" class="form-control" />
            @endif
        </td>

        <td>
            {{-- HOD Remarks --}}
            <input type="text" name="agents[{{ $key }}][further_issue]"
                value="{{ old('agents.' . $key . '.further_issue', $row->further_issue) }}"
                class="form-control" />
        </td>
    </tr>
@endforeach
</tbody>
</table>
<h1 class="text-center m-2">OVERALL SUMMARY</h1>
{{-- <table id="" class="agentsTable table table-bordered table-striped text-nowrap key-buttons">
<thead>
<tr>
    <th>Sr.#</th>
    <th>
        @if ($paneltype == 1)
            PQuote Count
        @elseif($paneltype == 2)
            WebQuote Count
        @endif
    </th>
    <th>Dispatch</th>
    <th>Business Delivery</th>
    <th>Port Delivery</th>
    <th>Private Pickup</th>
    <th>Today N Customer</th>
    <th>Today Phone Quote DB</th>
    <th>Today Listed DB</th>
    <th>Review</th>
    <th>Count</th>
</tr>
</thead>
<tbody>
@foreach ($data as $key => $row)
    <tr>
        <td>{{ $key + 1 }}</td> --}}
<div class="ecommerce-stats-area">
<div class="row">
{{-- <div class="col-3">
    <input type="number" readonly name="agents[{{ $key }}][pquote_count]"
        value="{{ old('agents.' . $key . '.pquote_count', $row->pquote_count) }}" class="form-control" />
</div> --}}
<div class="col-lg-3 col-sm-6 col-md-6">
    <div class="single-stats-card-box">
        <div class="icon">
            <i class='bx bx-window-close'></i>
        </div>
        <span class="sub-title">Dispatch</span>
        {{-- <h3>{{ isset($dispatch) ? $dispatch : 0 }}</h3> --}}

    </div>
    <input readonly type="text" name="dispatch" value="{{ $dispatch }}" class="form-control" />
</div>
<div class="col-lg-3 col-sm-6 col-md-6">
    <div class="single-stats-card-box">
        <div class="icon">
            <i class='bx bx-window-close'></i>
        </div>
        <span class="sub-title">Business Delivery</span>
        {{-- <h3></h3> --}}

    </div>
    <input type="text" name="agents[{{ $key }}][business_delivery]"
        value="{{ old('agents.' . $key . '.business_delivery', $row->business_delivery) }}"
        class="form-control" />
</div>
<div class="col-lg-3 col-sm-6 col-md-6">
    <div class="single-stats-card-box">
        <div class="icon">
            <i class='bx bx-window-close'></i>
        </div>
        <span class="sub-title">Port Delivery</span>
        {{-- <h3></h3> --}}

    </div>
    <input type="text" name="agents[{{ $key }}][port_delivery]"
        value="{{ old('agents.' . $key . '.port_delivery', $row->port_delivery) }}"
        class="form-control" />
</div>
<div class="col-lg-3 col-sm-6 col-md-6">
    <div class="single-stats-card-box">
        <div class="icon">
            <i class='bx bx-window-close'></i>
        </div>
        <span class="sub-title">Private Pickup</span>
        {{-- <h3></h3> --}}

    </div>
    <input type="text" name="agents[{{ $key }}][private_pickup]"
        value="{{ old('agents.' . $key . '.private_pickup', $row->private_pickup) }}"
        class="form-control" />
</div>
<div class="col-lg-3 col-sm-6 col-md-6">
    <div class="single-stats-card-box">
        <div class="icon">
            <i class='bx bx-window-close'></i>
        </div>
        <span class="sub-title">Today N Customer</span>
        {{-- <h3>{{ isset($today_n_customer) ? $today_n_customer : 0 }}</h3> --}}

    </div>
    <input readonly type="number" name="today_n_customer" value="{{ $today_n_customer }}"
        class="form-control" />
</div>
<div class="col-lg-3 col-sm-6 col-md-6">
    <div class="single-stats-card-box">
        <div class="icon">
            <i class='bx bx-window-close'></i>
        </div>
        <span class="sub-title">Today Phone Quote DB</span>
        {{-- <h3>{{ isset($today_phone_quote_db) ? $today_phone_quote_db : 0 }}</h3> --}}

    </div>
    <input readonly type="number" name="agents[{{ $key }}][today_phone_quote_db]"
        value="{{ $today_phone_quote_db }}" class="form-control" />
</div>
<div class="col-lg-3 col-sm-6 col-md-6">
    <div class="single-stats-card-box">
        <div class="icon">
            <i class='bx bx-window-close'></i>
        </div>
        <span class="sub-title">Today Listed DB</span>
        {{-- <h3>{{ isset($today_listed_db) ? $today_listed_db : 0 }}</h3> --}}
    </div>
    <input readonly type="number" name="agents[{{ $key }}][today_listed_db]"
        value="{{ $today_listed_db }}" class="form-control" />
</div>
<div class="col-lg-3 col-sm-6 col-md-6">
    <div class="single-stats-card-box">
        <div class="icon">
            <i class='bx bx-window-close'></i>
        </div>
        <span class="sub-title">Review</span>
        {{-- <h3></h3> --}}

    </div>
    <input type="text" name="agents[{{ $key }}][review]"
        value="{{ old('agents.' . $key . '.review', $row->review) }}" class="form-control" />
</div>
<div class="col-lg-3 col-sm-6 col-md-6">
    <div class="single-stats-card-box">
        <div class="icon">
            <i class='bx bx-window-close'></i>
        </div>
        <span class="sub-title">Count</span>
        {{-- <h3></h3> --}}

    </div>
    <input type="number" name="agents[{{ $key }}][count]"
        value="{{ old('agents.' . $key . '.count', $row->count) }}" class="form-control" />
</div>
</div>
</div>
{{-- </tr>
@endforeach
</tbody>
</table> --}}
<button type="submit" class="btn btn-primary mt-5">Save Agent Reports</button>
</form>

<script>
    $(document).ready(function() {
        $('.agentsTable').DataTable({
            paging: false,
            searching: false,
            info: false
        });
    });
</script>
