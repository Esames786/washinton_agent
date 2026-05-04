@extends('layouts.innerpages')

@section('template_title')
    Commission Report
@endsection

@section('content')
@include('partials.mainsite_pages.return_function')

<div class="page-header">
    <div class="text-secondary text-center text-uppercase w-100">
        <h1 class="my-4"><b>Commission Report</b></h1>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap" style="gap:10px;">
                <div class="card-title mb-0">
                    Commission Summary — <span class="text-muted font-weight-normal">{{ $label }}</span>
                </div>

                {{-- Filters --}}
                <form method="GET" action="{{ route('commission.index') }}" class="d-flex flex-wrap" style="gap:8px;">
                    <div class="input-group input-group-sm" style="width:160px;">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Month</span>
                        </div>
                        <input type="month" name="month" class="form-control"
                               value="{{ request('month', \Carbon\Carbon::now()->format('Y-m')) }}">
                    </div>
                    <span class="text-muted align-self-center small">or</span>
                    <div class="input-group input-group-sm" style="width:145px;">
                        <div class="input-group-prepend">
                            <span class="input-group-text">From</span>
                        </div>
                        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                    </div>
                    <div class="input-group input-group-sm" style="width:135px;">
                        <div class="input-group-prepend">
                            <span class="input-group-text">To</span>
                        </div>
                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                    <a href="{{ route('commission.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                </form>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0">
                        <thead style="background:#062e39;color:#fff;">
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th class="text-center">Completed Orders</th>
                                <th class="text-right">Total Payment</th>
                                <th class="text-right">Total Profit</th>
                                <th class="text-right">Commission</th>
                                <th class="text-center">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rows as $i => $row)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $row->user_name }}</td>
                                <td class="text-center">
                                    <span class="badge badge-{{ $row->completed_orders_count > 0 ? 'success' : 'secondary' }}">
                                        {{ $row->completed_orders_count }}
                                    </span>
                                </td>
                                <td class="text-right">${{ number_format($row->total_payment, 2) }}</td>
                                <td class="text-right">${{ number_format($row->total_profit, 2) }}</td>
                                <td class="text-right font-weight-bold" style="color:#062e39;">
                                    ${{ number_format($row->total_commission, 2) }}
                                </td>
                                <td class="text-center">
                                    @if($row->completed_orders_count > 0)
                                        <a href="{{ route('commission.show', $row->user_id) }}?{{ http_build_query(request()->only('month','from_date','to_date')) }}"
                                           class="btn btn-xs btn-outline-primary">View</a>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No data found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($rows->count() > 0)
                        <tfoot style="background:#f8f9fa;font-weight:bold;">
                            <tr>
                                <td colspan="2">Totals</td>
                                <td class="text-center">{{ $rows->sum('completed_orders_count') }}</td>
                                <td class="text-right">${{ number_format($rows->sum('total_payment'), 2) }}</td>
                                <td class="text-right">${{ number_format($rows->sum('total_profit'), 2) }}</td>
                                <td class="text-right" style="color:#062e39;">${{ number_format($rows->sum('total_commission'), 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
