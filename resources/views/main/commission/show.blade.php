@extends('layouts.innerpages')

@section('template_title')
    Commission — {{ $user->name }}
@endsection

@section('content')
@include('partials.mainsite_pages.return_function')

<div class="page-header">
    <div class="text-secondary text-center text-uppercase w-100">
        <h1 class="my-4"><b>Commission Breakdown</b></h1>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap" style="gap:10px;">
                <div>
                    <div class="card-title mb-0">
                        {{ $user->name }}
                        <small class="text-muted font-weight-normal ml-2">— {{ $label }}</small>
                    </div>
                </div>
                <a href="{{ route('commission.index') }}?{{ http_build_query(request()->only('month','from_date','to_date')) }}"
                   class="btn btn-sm btn-secondary">← Back to Summary</a>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0">
                        <thead style="background:#062e39;color:#fff;">
                            <tr>
                                <th>#</th>
                                <th>Order ID</th>
                                <th>Completion Date</th>
                                <th class="text-right">Payment</th>
                                <th class="text-right">Profit</th>
                                <th class="text-right">Commission</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $i => $order)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    <a href="{{ url('new_edit/' . $order->order_id) }}" target="_blank">
                                        #{{ $order->order_id }}
                                    </a>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($order->completion_date)->format('M d, Y') }}</td>
                                <td class="text-right">${{ number_format($order->payment, 2) }}</td>
                                <td class="text-right">${{ number_format($order->profit, 2) }}</td>
                                <td class="text-right font-weight-bold" style="color:#062e39;">
                                    ${{ number_format($order->commission, 2) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No completed orders in this period.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($orders->count() > 0)
                        <tfoot style="background:#f8f9fa;font-weight:bold;">
                            <tr>
                                <td colspan="3">Totals</td>
                                <td class="text-right">${{ number_format($orders->sum('payment'), 2) }}</td>
                                <td class="text-right">${{ number_format($orders->sum('profit'), 2) }}</td>
                                <td class="text-right" style="color:#062e39;">${{ number_format($orders->sum('commission'), 2) }}</td>
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
