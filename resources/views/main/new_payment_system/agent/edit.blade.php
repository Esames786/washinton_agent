@extends('layouts.innerpages')

@section('template_title')
    Edit Payment #{{ $payment->id }}
@endsection

@include('partials.mainsite_pages.return_function')

@section('content')
<div class="page-header">
    <div class="text-secondary text-center text-uppercase w-100">
        <h1 class="my-4"><b>Edit Payment #{{ $payment->id }}</b></h1>
    </div>
</div>

@if($payment->payment_status === 'Payment Return')
<div class="alert alert-danger">
    <strong><i class="fa fa-exclamation-triangle mr-1"></i> Returned by Admin:</strong>
    {{ $payment->admin_remarks ?? 'Please correct and resubmit.' }}
</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <strong>Update Payment Details</strong>
            </div>
            <div class="card-body">
                <form action="{{ route('agent.payments.update', $payment->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label class="font-weight-bold">Order Reference</label>
                        <input type="text" class="form-control" value="{{ $payment->listing_order_id }}" readonly
                               style="background:#f8f9fa;">
                        <small class="text-muted">Order reference cannot be changed.</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Payment Mode <span class="text-danger">*</span></label>
                                <select name="payment_mode" class="form-control" required>
                                    <option value="">-- Select Mode --</option>
                                    @foreach(['COD', 'Bank Transfer', 'Cheque', 'Wire Transfer', 'Zelle', 'Venmo', 'CashApp', 'Other'] as $mode)
                                        <option value="{{ $mode }}" {{ (old('payment_mode', $payment->payment_mode) == $mode) ? 'selected' : '' }}>
                                            {{ $mode }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Payment Type</label>
                                <input type="text" name="payment_type" class="form-control"
                                       value="{{ old('payment_type', $payment->payment_type) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Book Price (USD) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                    <input type="number" step="0.01" min="0" name="book_price" id="book_price"
                                           class="form-control" required oninput="calcProfit()"
                                           value="{{ old('book_price', $payment->book_price) }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Carrier Price (USD) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                    <input type="number" step="0.01" min="0" name="carrier_price" id="carrier_price"
                                           class="form-control" required oninput="calcProfit()"
                                           value="{{ old('carrier_price', $payment->carrier_price) }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Profit (auto)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                    <input type="text" id="profit_display" class="form-control" readonly
                                           style="background:#f8f9fa;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Confirmation Date <span class="text-danger">*</span></label>
                        <input type="date" name="confirmation_date" class="form-control" required
                               value="{{ old('confirmation_date', $payment->confirmation_date) }}">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Payment Screenshot / Proof</label>
                        @if($payment->screenshot_path)
                            <div class="mb-2">
                                <a href="{{ asset($payment->screenshot_path) }}" target="_blank">
                                    <img src="{{ asset($payment->screenshot_path) }}"
                                         style="height:80px;border-radius:6px;border:1px solid #ddd;" alt="Current Screenshot">
                                </a>
                                <small class="text-muted ml-2">Current screenshot. Upload new to replace.</small>
                            </div>
                        @endif
                        <input type="file" name="screenshot_path" class="form-control-file"
                               accept=".jpg,.jpeg,.png,.pdf">
                        <small class="text-muted">Accepted: JPG, PNG, PDF. Max 5MB.</small>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Details / Notes</label>
                        <textarea name="details" class="form-control" rows="3">{{ old('details', $payment->details) }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('agent.payments.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left mr-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-warning text-dark">
                            <i class="fa fa-paper-plane mr-1"></i> Resubmit Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function calcProfit() {
    const book    = parseFloat($('#book_price').val()) || 0;
    const carrier = parseFloat($('#carrier_price').val()) || 0;
    const profit  = book - carrier;
    $('#profit_display').val(profit.toFixed(2));
    $('#profit_display').css('color', profit >= 0 ? '#059669' : '#dc2626');
}
calcProfit();
</script>
@endsection
