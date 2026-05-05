@extends('layouts.innerpages')

@section('template_title')
    Submit New Payment
@endsection

@include('partials.mainsite_pages.return_function')

@section('content')
<div class="page-header">
    <div class="text-secondary text-center text-uppercase w-100">
        <h1 class="my-4"><b>Submit New Payment</b></h1>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <strong>Payment Details</strong>
            </div>
            <div class="card-body">
                <form action="{{ route('agent.payments.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Order Lookup --}}
                    <div class="form-group">
                        <label class="font-weight-bold">Order ID <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" name="order_ref" id="order_ref"
                                   class="form-control @error('order_ref') is-invalid @enderror"
                                   placeholder="Enter Order ID (numeric)"
                                   value="{{ old('order_ref') }}" required>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" id="lookupOrderBtn">
                                    <i class="fa fa-search"></i> Lookup
                                </button>
                            </div>
                        </div>
                        <small class="text-muted">Enter the order ID and click Lookup to auto-fill prices.</small>
                        <div id="orderInfo" class="mt-2" style="display:none;"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Payment Mode <span class="text-danger">*</span></label>
                                <select name="payment_mode" class="form-control @error('payment_mode') is-invalid @enderror" required>
                                    <option value="">-- Select Mode --</option>
                                    <option value="COD"           {{ old('payment_mode') == 'COD'           ? 'selected' : '' }}>COD (Cash on Delivery)</option>
                                    <option value="Bank Transfer" {{ old('payment_mode') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="Cheque"        {{ old('payment_mode') == 'Cheque'        ? 'selected' : '' }}>Cheque</option>
                                    <option value="Wire Transfer" {{ old('payment_mode') == 'Wire Transfer' ? 'selected' : '' }}>Wire Transfer</option>
                                    <option value="Zelle"         {{ old('payment_mode') == 'Zelle'         ? 'selected' : '' }}>Zelle</option>
                                    <option value="Venmo"         {{ old('payment_mode') == 'Venmo'         ? 'selected' : '' }}>Venmo</option>
                                    <option value="CashApp"       {{ old('payment_mode') == 'CashApp'       ? 'selected' : '' }}>CashApp</option>
                                    <option value="Other"         {{ old('payment_mode') == 'Other'         ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('payment_mode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Payment Type</label>
                                <input type="text" name="payment_type" class="form-control"
                                       placeholder="e.g. Full Payment, Deposit..."
                                       value="{{ old('payment_type') }}">
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
                                           class="form-control @error('book_price') is-invalid @enderror"
                                           placeholder="0.00" value="{{ old('book_price') }}" required
                                           oninput="calcProfit()">
                                </div>
                                @error('book_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Carrier Price (USD) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                    <input type="number" step="0.01" min="0" name="carrier_price" id="carrier_price"
                                           class="form-control @error('carrier_price') is-invalid @enderror"
                                           placeholder="0.00" value="{{ old('carrier_price') }}" required
                                           oninput="calcProfit()">
                                </div>
                                @error('carrier_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Profit (auto-calculated)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                    <input type="text" id="profit_display" class="form-control" readonly
                                           placeholder="0.00" style="background:#f8f9fa;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Confirmation Date <span class="text-danger">*</span></label>
                        <input type="date" name="confirmation_date"
                               class="form-control @error('confirmation_date') is-invalid @enderror"
                               value="{{ old('confirmation_date', date('Y-m-d')) }}" required>
                        @error('confirmation_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Payment Screenshot / Proof</label>
                        <input type="file" name="screenshot_path" class="form-control-file"
                               accept=".jpg,.jpeg,.png,.pdf">
                        <small class="text-muted">Accepted: JPG, PNG, PDF. Max 5MB.</small>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Details / Notes</label>
                        <textarea name="details" class="form-control" rows="3"
                                  placeholder="Any additional details about this payment...">{{ old('details') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('agent.payments.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left mr-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-paper-plane mr-1"></i> Submit Payment
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

$('#lookupOrderBtn').on('click', function () {
    const orderId = $('#order_ref').val().trim();
    if (!orderId) { alert('Please enter an Order ID.'); return; }

    const btn = $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

    $.get('/my-payments/fetch-order', { order_id: orderId }, function (data) {
        if (data.error) {
            $('#orderInfo').html('<div class="alert alert-warning py-2">' + data.error + '</div>').show();
            return;
        }
        $('#book_price').val(data.book_price);
        $('#carrier_price').val(data.carrier_price);
        calcProfit();

        $('#orderInfo').html(`
            <div class="alert alert-success py-2">
                <strong>Order Found!</strong><br>
                Customer: ${data.oname || '-'} &nbsp;|&nbsp;
                Vehicle: ${data.ymk || '-'} &nbsp;|&nbsp;
                Route: ${data.origin} &rarr; ${data.destination}
            </div>
        `).show();
    }).fail(function (xhr) {
        const msg = xhr.responseJSON?.error || 'Order not found.';
        $('#orderInfo').html('<div class="alert alert-danger py-2">' + msg + '</div>').show();
    }).always(function () {
        btn.prop('disabled', false).html('<i class="fa fa-search"></i> Lookup');
    });
});

// Init profit calc
calcProfit();
</script>
@endsection
