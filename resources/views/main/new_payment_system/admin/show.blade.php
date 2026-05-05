@extends('layouts.innerpages')

@section('template_title')
    Payment #{{ $payment->id }} Details
@endsection

@include('partials.mainsite_pages.return_function')

@section('content')
<div class="page-header">
    <div class="text-secondary text-center text-uppercase w-100">
        <h1 class="my-4"><b>Payment #{{ $payment->id }} Details</b></h1>
    </div>
</div>

<div class="row">
    {{-- Left: Payment Info --}}
    <div class="col-md-7">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Payment Information</strong>
                @if($payment->payment_status === 'Payment Confirmed')
                    <span class="badge badge-success px-3 py-2">Confirmed</span>
                @elseif($payment->payment_status === 'Payment Return')
                    <span class="badge badge-danger px-3 py-2">Returned</span>
                @else
                    <span class="badge badge-warning px-3 py-2">Pending</span>
                @endif
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr><th style="width:40%">Payment ID</th><td>#{{ $payment->id }}</td></tr>
                    <tr><th>Agent</th><td>{{ $payment->agent->slug ?? $payment->agent->name ?? '-' }} (ID: {{ $payment->user_id }})</td></tr>
                    <tr><th>Order Reference</th><td>{{ $payment->listing_order_id ?? '-' }}</td></tr>
                    <tr><th>Payment Mode</th><td>{{ $payment->payment_mode ?? '-' }}</td></tr>
                    <tr><th>Payment Type</th><td>{{ $payment->payment_type ?? '-' }}</td></tr>
                    <tr><th>Book Price (USD)</th><td class="font-weight-bold">${{ number_format($payment->book_price, 2) }}</td></tr>
                    <tr><th>Carrier Price (USD)</th><td>${{ number_format($payment->carrier_price, 2) }}</td></tr>
                    <tr><th>Profit (USD)</th>
                        <td class="{{ $payment->profit >= 0 ? 'text-success' : 'text-danger' }} font-weight-bold">
                            ${{ number_format($payment->profit, 2) }}
                        </td>
                    </tr>
                    <tr><th>Confirmation Date</th><td>{{ $payment->confirmation_date ?? '-' }}</td></tr>
                    <tr><th>Details / Notes</th><td>{{ $payment->details ?? '-' }}</td></tr>
                    <tr><th>Submitted</th><td>{{ $payment->created_at->format('M d, Y H:i') }}</td></tr>
                    @if($payment->reviewed_by)
                    <tr><th>Reviewed By</th><td>{{ $payment->reviewer->name ?? '#'.$payment->reviewed_by }}</td></tr>
                    @endif
                    @if($payment->admin_remarks)
                    <tr><th>Admin Remarks</th><td class="text-danger">{{ $payment->admin_remarks }}</td></tr>
                    @endif
                </table>

                @if($payment->payment_status !== 'Payment Confirmed')
                <div class="mt-3 d-flex gap-2">
                    <button class="btn btn-success btn-sm confirm-btn" data-id="{{ $payment->id }}">
                        <i class="fa fa-check mr-1"></i> Confirm Payment
                    </button>
                    <button class="btn btn-danger btn-sm return-btn" data-id="{{ $payment->id }}">
                        <i class="fa fa-undo mr-1"></i> Return for Correction
                    </button>
                </div>
                @endif
            </div>
        </div>

        {{-- Order Info --}}
        @if($payment->order)
        <div class="card mb-3">
            <div class="card-header"><strong>Order Information</strong></div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr><th style="width:40%">Order ID</th><td>{{ $payment->order->id }}</td></tr>
                    <tr><th>Customer</th><td>{{ $payment->order->oname ?? '-' }}</td></tr>
                    <tr><th>Vehicle</th><td>{{ $payment->order->ymk ?? '-' }}</td></tr>
                    <tr><th>Route</th><td>{{ $payment->order->origincity }}, {{ $payment->order->originstate }} &rarr; {{ $payment->order->destinationcity }}, {{ $payment->order->destinationstate }}</td></tr>
                    <tr><th>Order Status</th><td>pstatus: {{ $payment->order->pstatus }}</td></tr>
                </table>
            </div>
        </div>
        @endif
    </div>

    {{-- Right: Screenshot + Journey --}}
    <div class="col-md-5">
        {{-- Screenshot --}}
        <div class="card mb-3">
            <div class="card-header"><strong>Payment Screenshot</strong></div>
            <div class="card-body text-center">
                @if($payment->screenshot_path)
                    <a href="{{ asset($payment->screenshot_path) }}" target="_blank">
                        <img src="{{ asset($payment->screenshot_path) }}"
                             class="img-fluid rounded" style="max-height:300px;"
                             alt="Payment Screenshot">
                    </a>
                    <div class="mt-2">
                        <a href="{{ asset($payment->screenshot_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fa fa-external-link mr-1"></i> Open Full Size
                        </a>
                    </div>
                @else
                    <div class="text-muted py-4">
                        <i class="fa fa-image fa-3x mb-2"></i>
                        <p>No screenshot uploaded</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Journey --}}
        <div class="card">
            <div class="card-header"><strong>Status History</strong></div>
            <div class="card-body p-0">
                @forelse($payment->journeys->sortByDesc('created_at') as $j)
                <div class="d-flex align-items-start p-3 border-bottom">
                    <div class="mr-3 mt-1">
                        @if($j->new_status === 'Payment Confirmed')
                            <span class="badge badge-success">Confirmed</span>
                        @elseif($j->new_status === 'Payment Return')
                            <span class="badge badge-danger">Returned</span>
                        @else
                            <span class="badge badge-warning">Pending</span>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <div class="small font-weight-bold">
                            {{ $j->changedBy->name ?? '#'.$j->changed_by }}
                            <span class="badge badge-secondary ml-1">{{ $j->user_type }}</span>
                        </div>
                        @if($j->note)
                            <div class="small text-muted">{{ $j->note }}</div>
                        @endif
                        <div class="small text-muted">{{ $j->created_at->format('M d, Y H:i') }}</div>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center py-3">No history yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
        <i class="fa fa-arrow-left mr-1"></i> Back to List
    </a>
</div>

{{-- Confirm Modal --}}
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h6 class="modal-title">Confirm Payment</h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <textarea id="confirmRemarks" class="form-control" rows="3" placeholder="Optional remarks..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success btn-sm" id="doConfirmBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>

{{-- Return Modal --}}
<div class="modal fade" id="returnModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h6 class="modal-title">Return Payment</h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <textarea id="returnRemarks" class="form-control" rows="3" placeholder="Reason for return..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger btn-sm" id="doReturnBtn">Return</button>
            </div>
        </div>
    </div>
</div>

<script>
let activeId = {{ $payment->id }};

$('.confirm-btn').on('click', function () { $('#confirmModal').modal('show'); });
$('.return-btn').on('click', function () { $('#returnModal').modal('show'); });

$('#doConfirmBtn').on('click', function () {
    const btn = $(this).prop('disabled', true).text('...');
    $.post('/admin-payments/' + activeId + '/confirm',
        { _token: '{{ csrf_token() }}', remarks: $('#confirmRemarks').val() },
        function (res) {
            if (res.success) location.reload();
            else alert(res.message);
        }
    ).fail(function (xhr) { alert(xhr.responseJSON?.message || 'Error'); })
     .always(function () { btn.prop('disabled', false).text('Confirm'); });
});

$('#doReturnBtn').on('click', function () {
    const remarks = $('#returnRemarks').val().trim();
    if (!remarks) { alert('Please provide a reason.'); return; }
    const btn = $(this).prop('disabled', true).text('...');
    $.post('/admin-payments/' + activeId + '/return',
        { _token: '{{ csrf_token() }}', remarks: remarks },
        function (res) {
            if (res.success) location.reload();
            else alert(res.message);
        }
    ).fail(function (xhr) { alert(xhr.responseJSON?.message || 'Error'); })
     .always(function () { btn.prop('disabled', false).text('Return'); });
});
</script>
@endsection
