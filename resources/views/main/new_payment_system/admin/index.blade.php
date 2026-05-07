@extends('layouts.innerpages')

@section('template_title')
    Admin Payment System
@endsection

@include('partials.mainsite_pages.return_function')

@section('content')
<style>
    .stat-card { border-radius: 12px; padding: 18px 22px; color: #fff; margin-bottom: 16px; }
    .stat-card .num { font-size: 28px; font-weight: 700; }
    .stat-card .lbl { font-size: 13px; opacity: .85; }
    .badge-pending   { background: #f59e0b; color: #fff; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; }
    .badge-confirmed { background: #10b981; color: #fff; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; }
    .badge-returned  { background: #ef4444; color: #fff; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; }
    .screenshot-thumb { width: 60px; height: 45px; object-fit: cover; border-radius: 6px; cursor: pointer; border: 1px solid #ddd; }
</style>

<div class="page-header">
    <div class="text-secondary text-center text-uppercase w-100">
        <h1 class="my-4"><b>Admin Payment System</b></h1>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="close" data-dismiss="alert">&times;</button></div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="close" data-dismiss="alert">&times;</button></div>
@endif

{{-- Stats --}}
<div class="row mb-3">
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#012862,#1a4a9e);">
            <div class="num">{{ $totals['total'] }}</div>
            <div class="lbl">Total Payments</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#f59e0b,#d97706);">
            <div class="num">{{ $totals['pending'] }}</div>
            <div class="lbl">Pending Review</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#10b981,#059669);">
            <div class="num">{{ $totals['confirmed'] }}</div>
            <div class="lbl">Confirmed</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9);">
            <div class="num">${{ number_format($totals['profit'], 2) }}</div>
            <div class="lbl">Total Confirmed Profit (USD)</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.payments.index') }}" class="row align-items-end">
            <div class="col-md-2">
                <label class="form-label small font-weight-bold">Status</label>
                <select name="status" class="form-control form-control-sm">
                    <option value="">All</option>
                    <option value="Payment Pending"   {{ request('status') == 'Payment Pending'   ? 'selected' : '' }}>Pending</option>
                    <option value="Payment Confirmed" {{ request('status') == 'Payment Confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="Payment Return"    {{ request('status') == 'Payment Return'    ? 'selected' : '' }}>Returned</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small font-weight-bold">Agent</label>
                <select name="agent_id" class="form-control form-control-sm">
                    <option value="">All Agents</option>
                    @foreach($agents as $agent)
                        <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>
                            {{ $agent->slug ?: $agent->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small font-weight-bold">From Date</label>
                <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small font-weight-bold">To Date</label>
                <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small font-weight-bold">Order Ref</label>
                <input type="text" name="order_ref" class="form-control form-control-sm" placeholder="Order ID" value="{{ request('order_ref') }}">
            </div>
            <div class="col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:13px;">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Agent</th>
                        <th>Order Ref</th>
                        <th>Mode</th>
                        <th>Book Price</th>
                        <th>Carrier Price</th>
                        <th>Profit</th>
                        <th>Conf. Date</th>
                        <th>Screenshot</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $p)
                    <tr>
                        <td>{{ $p->id }}</td>
                        <td>{{ $p->agent->slug ?? $p->agent->name ?? '-' }}</td>
                        <td>
                            @if($p->order_id)
                                <a href="{{ url('completed') }}?search={{ $p->listing_order_id }}" target="_blank">
                                    {{ $p->listing_order_id }}
                                </a>
                            @else
                                {{ $p->listing_order_id ?? '-' }}
                            @endif
                        </td>
                        <td>{{ $p->payment_mode ?? '-' }}</td>
                        <td>${{ number_format($p->book_price, 2) }}</td>
                        <td>${{ number_format($p->carrier_price, 2) }}</td>
                        <td class="{{ $p->profit >= 0 ? 'text-success' : 'text-danger' }} font-weight-bold">
                            ${{ number_format($p->profit, 2) }}
                        </td>
                        <td>{{ $p->confirmation_date ?? '-' }}</td>
                        <td>
                            @if($p->screenshot_path)
                                <img src="{{ asset($p->screenshot_path) }}"
                                     class="screenshot-thumb"
                                     onclick="window.open('{{ asset($p->screenshot_path) }}','_blank')"
                                     alt="Screenshot">
                            @else
                                <span class="text-muted small">None</span>
                            @endif
                        </td>
                        <td>
                            @if($p->payment_status === 'Payment Confirmed')
                                <span class="badge-confirmed">Confirmed</span>
                            @elseif($p->payment_status === 'Payment Return')
                                <span class="badge-returned">Returned</span>
                            @else
                                <span class="badge-pending">Pending</span>
                            @endif
                        </td>
                        <td>{{ $p->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                <a href="{{ route('admin.payments.show', $p->id) }}"
                                   class="btn btn-xs btn-outline-info" title="View Details">
                                    <i class="fa fa-eye"></i>
                                </a>
                                @if($p->payment_status !== 'Payment Confirmed')
                                    <button class="btn btn-xs btn-success confirm-btn"
                                            data-id="{{ $p->id }}" title="Confirm">
                                        <i class="fa fa-check"></i>
                                    </button>
                                    <button class="btn btn-xs btn-danger return-btn"
                                            data-id="{{ $p->id }}" title="Return">
                                        <i class="fa fa-undo"></i>
                                    </button>
                                @endif
                                <button class="btn btn-xs btn-secondary journey-btn"
                                        data-id="{{ $p->id }}" title="History">
                                    <i class="fa fa-history"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="12" class="text-center text-muted py-4">No payments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($payments->hasPages())
    <div class="card-footer">
        {{ $payments->links() }}
    </div>
    @endif
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
                <p class="mb-2">Add optional remarks:</p>
                <textarea id="confirmRemarks" class="form-control" rows="3" placeholder="Optional remarks..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success btn-sm" id="doConfirmBtn">Confirm Payment</button>
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
                <p class="mb-2">Reason for return <span class="text-danger">*</span></p>
                <textarea id="returnRemarks" class="form-control" rows="3" placeholder="Explain what needs correction..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger btn-sm" id="doReturnBtn">Return Payment</button>
            </div>
        </div>
    </div>
</div>

{{-- Journey Modal --}}
<div class="modal fade" id="journeyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h6 class="modal-title">Payment History</h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="journeyBody">
                <div class="text-center py-3"><div class="spinner-border spinner-border-sm"></div></div>
            </div>
        </div>
    </div>
</div>

<script>
let activePaymentId = null;

// Confirm
$(document).on('click', '.confirm-btn', function () {
    activePaymentId = $(this).data('id');
    $('#confirmRemarks').val('');
    $('#confirmModal').modal('show');
});

$('#doConfirmBtn').on('click', function () {
    const btn = $(this).prop('disabled', true).text('Processing...');
    $.ajax({
        url: '/admin-payments/' + activePaymentId + '/confirm',
        method: 'POST',
        data: { _token: '{{ csrf_token() }}', remarks: $('#confirmRemarks').val() },
        success: function (res) {
            if (res.success) {
                $('#confirmModal').modal('hide');
                location.reload();
            } else {
                alert(res.message);
            }
        },
        error: function (xhr) {
            alert(xhr.responseJSON?.message || 'Error occurred.');
        },
        complete: function () { btn.prop('disabled', false).text('Confirm Payment'); }
    });
});

// Return
$(document).on('click', '.return-btn', function () {
    activePaymentId = $(this).data('id');
    $('#returnRemarks').val('');
    $('#returnModal').modal('show');
});

$('#doReturnBtn').on('click', function () {
    const remarks = $('#returnRemarks').val().trim();
    if (!remarks) { alert('Please provide a reason for return.'); return; }
    const btn = $(this).prop('disabled', true).text('Processing...');
    $.ajax({
        url: '/admin-payments/' + activePaymentId + '/return',
        method: 'POST',
        data: { _token: '{{ csrf_token() }}', remarks: remarks },
        success: function (res) {
            if (res.success) {
                $('#returnModal').modal('hide');
                location.reload();
            } else {
                alert(res.message);
            }
        },
        error: function (xhr) {
            alert(xhr.responseJSON?.message || 'Error occurred.');
        },
        complete: function () { btn.prop('disabled', false).text('Return Payment'); }
    });
});

// Journey
$(document).on('click', '.journey-btn', function () {
    const id = $(this).data('id');
    $('#journeyBody').html('<div class="text-center py-3"><div class="spinner-border spinner-border-sm"></div></div>');
    $('#journeyModal').modal('show');
    $.get('/admin-payments/' + id + '/journey', function (data) {
        if (!data.length) {
            $('#journeyBody').html('<p class="text-muted text-center">No history found.</p>');
            return;
        }
        let html = '<table class="table table-sm table-bordered"><thead class="thead-light"><tr><th>Status</th><th>Changed By</th><th>Type</th><th>Note</th><th>Date</th></tr></thead><tbody>';
        data.forEach(function (j) {
            const badge = j.new_status === 'Payment Confirmed' ? 'badge-success' :
                          j.new_status === 'Payment Return'    ? 'badge-danger'  : 'badge-warning';
            html += `<tr>
                <td><span class="badge ${badge}">${j.new_status}</span></td>
                <td>${j.changed_by_name || '-'}</td>
                <td><span class="badge badge-secondary">${j.user_type || '-'}</span></td>
                <td>${j.note || '-'}</td>
                <td>${j.created_at || '-'}</td>
            </tr>`;
        });
        html += '</tbody></table>';
        $('#journeyBody').html(html);
    });
});
</script>
@endsection
