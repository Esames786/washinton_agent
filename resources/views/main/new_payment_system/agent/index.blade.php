@extends('layouts.innerpages')

@section('template_title')
    My Payments
@endsection

@include('partials.mainsite_pages.return_function')

@section('content')
<style>
    .stat-card { border-radius: 12px; padding: 16px 20px; color: #fff; margin-bottom: 16px; }
    .stat-card .num { font-size: 26px; font-weight: 700; }
    .stat-card .lbl { font-size: 12px; opacity: .85; }
    .badge-pending   { background: #f59e0b; color: #fff; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; }
    .badge-confirmed { background: #10b981; color: #fff; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; }
    .badge-returned  { background: #ef4444; color: #fff; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; }
    .screenshot-thumb { width: 55px; height: 42px; object-fit: cover; border-radius: 6px; cursor: pointer; border: 1px solid #ddd; }
</style>

<div class="page-header">
    <div class="text-secondary text-center text-uppercase w-100">
        <h1 class="my-4"><b>My Payments</b></h1>
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
        <div class="stat-card" style="background:linear-gradient(135deg,#ef4444,#dc2626);">
            <div class="num">{{ $totals['returned'] }}</div>
            <div class="lbl">Returned (needs correction)</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9);">
            <div class="num">${{ number_format($totals['profit'], 2) }}</div>
            <div class="lbl">Confirmed Profit (USD)</div>
        </div>
    </div>
</div>

{{-- Actions + Filters --}}
<div class="card mb-3">
    <div class="card-body py-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <a href="{{ route('agent.payments.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus mr-1"></i> Submit New Payment
            </a>
            <form method="GET" action="{{ route('agent.payments.index') }}" class="d-flex gap-2 flex-wrap align-items-end">
                <div>
                    <select name="status" class="form-control form-control-sm">
                        <option value="">All Status</option>
                        <option value="Payment Pending"   {{ request('status') == 'Payment Pending'   ? 'selected' : '' }}>Pending</option>
                        <option value="Payment Confirmed" {{ request('status') == 'Payment Confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="Payment Return"    {{ request('status') == 'Payment Return'    ? 'selected' : '' }}>Returned</option>
                    </select>
                </div>
                <div>
                    <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
                </div>
                <div>
                    <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
                </div>
                <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
                <a href="{{ route('agent.payments.index') }}" class="btn btn-light btn-sm">Reset</a>
            </form>
        </div>
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
                        <th>Order Ref</th>
                        <th>Mode</th>
                        <th>Book Price</th>
                        <th>Carrier Price</th>
                        <th>Profit</th>
                        <th>Conf. Date</th>
                        <th>Screenshot</th>
                        <th>Status</th>
                        <th>Admin Remarks</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $p)
                    <tr @if($p->payment_status === 'Payment Return') style="background:#fff5f5;" @endif>
                        <td>{{ $p->id }}</td>
                        <td>{{ $p->listing_order_id ?? '-' }}</td>
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
                        <td>
                            @if($p->admin_remarks)
                                <span class="text-danger small">{{ $p->admin_remarks }}</span>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>{{ $p->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                @if($p->payment_status !== 'Payment Confirmed')
                                    <a href="{{ route('agent.payments.edit', $p->id) }}"
                                       class="btn btn-xs btn-warning" title="Edit / Resubmit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                @endif
                                <button class="btn btn-xs btn-secondary journey-btn"
                                        data-id="{{ $p->id }}" title="History">
                                    <i class="fa fa-history"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="12" class="text-center text-muted py-4">No payments submitted yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($payments->hasPages())
    <div class="card-footer">{{ $payments->links() }}</div>
    @endif
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
$(document).on('click', '.journey-btn', function () {
    const id = $(this).data('id');
    $('#journeyBody').html('<div class="text-center py-3"><div class="spinner-border spinner-border-sm"></div></div>');
    $('#journeyModal').modal('show');
    $.get('/my-payments/' + id + '/journey', function (data) {
        if (!data.length) {
            $('#journeyBody').html('<p class="text-muted text-center">No history found.</p>');
            return;
        }
        let html = '<table class="table table-sm table-bordered"><thead class="thead-light"><tr><th>Status</th><th>By</th><th>Type</th><th>Note</th><th>Date</th></tr></thead><tbody>';
        data.forEach(function (j) {
            const badge = j.new_status === 'Payment Confirmed' ? 'badge-success' :
                          j.new_status === 'Payment Return'    ? 'badge-danger'  : 'badge-warning';
            html += `<tr>
                <td><span class="badge ${badge}">${j.new_status}</span></td>
                <td>${j.changed_by ? '#'+j.changed_by : '-'}</td>
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
