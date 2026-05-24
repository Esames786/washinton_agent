@extends('layouts.print_layout')

@section('template_title', 'Authorization Form')

@include('partials.mainsite_pages.return_function')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
*, *::before, *::after { box-sizing: border-box; }

body { font-family: 'Inter', sans-serif; background: #f0f2f5; }

.show-wrapper { max-width: 860px; margin: 36px auto; padding: 0 16px 60px; }

/* Toolbar */
.show-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 10px;
}
.btn-dl {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: #17bebb;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    font-family: 'Inter', sans-serif;
    cursor: pointer;
    text-decoration: none;
    transition: opacity 0.2s;
}
.btn-dl:hover { opacity: 0.88; color: #fff; text-decoration: none; }
.show-meta { font-size: 0.78rem; color: #9ca3af; }

/* Document card */
.doc-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.09);
    overflow: hidden;
}

/* Header */
.doc-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
    padding: 28px 36px 22px;
    text-align: center;
    color: #fff;
}
.doc-header .brand { font-size: 1.5rem; font-weight: 700; text-decoration: underline; text-underline-offset: 5px; margin: 0 0 5px; }
.doc-header .doc-title { font-size: 0.75rem; font-weight: 500; letter-spacing: 3px; text-transform: uppercase; opacity: 0.75; margin: 0; }

/* Notice */
.doc-notice {
    background: #eff6ff;
    border-left: 4px solid #3b82f6;
    margin: 24px 32px 0;
    border-radius: 0 8px 8px 0;
    padding: 11px 16px;
    font-size: 0.83rem;
    color: #1e40af;
    line-height: 1.6;
}

/* Body */
.doc-body { padding: 24px 32px 32px; }

/* Section heading */
.sec-head {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.8px;
    color: #6b7280;
    margin: 24px 0 14px;
    padding-bottom: 8px;
    border-bottom: 1px solid #f3f4f6;
}
.sec-head:first-child { margin-top: 8px; }
.sec-head i { color: #17bebb; font-size: 0.8rem; }

/* Field rows */
.field-row { display: flex; flex-wrap: wrap; gap: 0 24px; margin-bottom: 4px; }
.field-item { flex: 1 1 180px; margin-bottom: 16px; min-width: 0; }
.field-item.full { flex: 1 1 100%; }
.field-label {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #9ca3af;
    margin-bottom: 4px;
}
.field-value {
    font-size: 0.9rem;
    font-weight: 500;
    color: #111827;
    background: #f9fafb;
    border: 1.5px solid #e5e7eb;
    border-radius: 7px;
    padding: 9px 13px;
    word-break: break-word;
}
.field-value.mono { font-family: monospace; letter-spacing: 1px; }
.field-value.empty { color: #9ca3af; font-style: italic; font-size: 0.82rem; }

/* Card type badge */
.card-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: #e6fafa;
    border: 1.5px solid #17bebb;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    color: #0e7490;
}
.card-badge i { font-size: 1.3rem; }

/* Images grid */
.img-grid { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 4px; }
.img-thumb {
    width: 150px;
    height: 110px;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid #e5e7eb;
    position: relative;
    background: #f3f4f6;
}
.img-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
.img-thumb a {
    position: absolute; inset: 0;
    display: flex; align-items: center; justify-content: center;
    background: rgba(0,0,0,0);
    transition: background 0.15s;
}
.img-thumb a:hover { background: rgba(0,0,0,0.3); }
.img-thumb a:hover::after {
    content: '\f00e';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    color: #fff;
    font-size: 1.4rem;
}

/* Signature */
.sig-display {
    background: #f9fafb;
    border: 1.5px solid #e5e7eb;
    border-radius: 8px;
    padding: 12px;
    display: inline-block;
}
.sig-display img { max-width: 320px; max-height: 120px; display: block; }

/* Submitted badge */
.submitted-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    background: #dcfce7;
    border: 1px solid #86efac;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    color: #15803d;
}

@media (max-width: 576px) {
    .doc-header { padding: 20px 20px 16px; }
    .doc-body { padding: 16px 16px 24px; }
    .doc-notice { margin: 16px 16px 0; }
    .img-thumb { width: 120px; height: 88px; }
}

@media print {
    .show-toolbar { display: none !important; }
    .show-wrapper { margin: 0; padding: 0; }
    .doc-card { box-shadow: none; border-radius: 0; }
}
</style>

@section('content')
<div class="show-wrapper" id="formDocument">

    <div class="show-toolbar">
        <div>
            <span class="submitted-chip"><i class="fas fa-check-circle"></i> Submitted</span>
            @if($form->created_at)
            <span class="show-meta" style="margin-left:10px;">Received {{ $form->created_at->format('M d, Y \a\t h:i A') }}</span>
            @endif
        </div>
        <button type="button" class="btn-dl" id="btnDownload">
            <i class="fas fa-download"></i> Download Form
        </button>
    </div>

    <div class="doc-card">

        <div class="doc-header">
            <p class="brand">Hello Transport LLC</p>
            <p class="doc-title">Credit Card Authorization Form</p>
        </div>

        <div class="doc-notice">
            <i class="fas fa-info-circle"></i>
            This signed form authorizes <strong>Hello Transport LLC</strong> to charge the credit card for the amount shown.
            @if($vehicle ?? null)
            &nbsp;&mdash;&nbsp; Vehicle: <strong>{{ $vehicle }}</strong> &mdash;
            Pickup: <strong>{{ $origin }}</strong> &mdash;
            Delivery: <strong>{{ $destination }}</strong>
            @endif
        </div>

        <div class="doc-body">

            {{-- Order Details --}}
            <div class="sec-head"><i class="fas fa-file-invoice-dollar"></i> Order Details</div>
            <div class="field-row">
                <div class="field-item">
                    <div class="field-label">Date</div>
                    <div class="field-value">{{ $form->date ? \Carbon\Carbon::parse($form->date)->format('M d, Y') : '—' }}</div>
                </div>
                <div class="field-item">
                    <div class="field-label">Invoice Number</div>
                    <div class="field-value mono">{{ $form->invoice ?? '—' }}</div>
                </div>
                <div class="field-item">
                    <div class="field-label">Invoice Amount</div>
                    <div class="field-value">{{ $form->invoice_amount ? '$'.number_format($form->invoice_amount, 2) : '—' }}</div>
                </div>
            </div>

            {{-- Card Information --}}
            <div class="sec-head"><i class="fas fa-credit-card"></i> Card Information</div>
            @php
                $cardIcons = ['master_card'=>'fab fa-cc-mastercard','visa'=>'fab fa-cc-visa','american-express'=>'fab fa-cc-amex'];
                $cardLabels = ['master_card'=>'Mastercard','visa'=>'Visa','american-express'=>'American Express'];
            @endphp
            @if($form->card)
            <div style="margin-bottom:16px;">
                <div class="field-label" style="margin-bottom:6px;">Card Type</div>
                <span class="card-badge">
                    <i class="{{ $cardIcons[$form->card] ?? 'fas fa-credit-card' }}"></i>
                    {{ $cardLabels[$form->card] ?? $form->card }}
                </span>
            </div>
            @endif
            <div class="field-row">
                <div class="field-item">
                    <div class="field-label">Card Holder</div>
                    <div class="field-value">{{ $form->card_holders ?? '—' }}</div>
                </div>
                <div class="field-item">
                    <div class="field-label">Card Number</div>
                    <div class="field-value mono">
                        @if($form->card_number)
                            •••• •••• •••• {{ substr(preg_replace('/\D/','',$form->card_number), -4) }}
                        @else —
                        @endif
                    </div>
                </div>
                <div class="field-item">
                    <div class="field-label">Expiration Date</div>
                    <div class="field-value mono">{{ $form->expdate ? \Carbon\Carbon::parse($form->expdate.'-01')->format('m / Y') : '—' }}</div>
                </div>
            </div>
            <div class="field-row">
                <div class="field-item">
                    <div class="field-label">Issuing Bank</div>
                    <div class="field-value">{{ $form->issuing_bank ?? '—' }}</div>
                </div>
                <div class="field-item">
                    <div class="field-label">Bank Approval Number</div>
                    <div class="field-value mono">{{ $form->bank_approval ?? '—' }}</div>
                </div>
                <div class="field-item">
                    <div class="field-label">Card Issuer Phone</div>
                    <div class="field-value">{{ $form->card_issuer ?? '—' }}</div>
                </div>
            </div>

            {{-- Company & Contact --}}
            <div class="sec-head"><i class="fas fa-building"></i> Company &amp; Contact</div>
            <div class="field-row">
                <div class="field-item">
                    <div class="field-label">Company Name</div>
                    <div class="field-value">{{ $form->company_name ?? '—' }}</div>
                </div>
                <div class="field-item">
                    <div class="field-label">Phone Number</div>
                    <div class="field-value">{{ $form->phone ?? '—' }}</div>
                </div>
            </div>

            {{-- Billing Address --}}
            <div class="sec-head"><i class="fas fa-map-marker-alt"></i> Billing Address</div>
            <div class="field-row">
                <div class="field-item full">
                    <div class="field-label">Street Address</div>
                    <div class="field-value">{{ $form->billing_address ?? '—' }}</div>
                </div>
            </div>
            <div class="field-row">
                <div class="field-item">
                    <div class="field-label">City</div>
                    <div class="field-value">{{ $form->city ?? '—' }}</div>
                </div>
                <div class="field-item">
                    <div class="field-label">State</div>
                    <div class="field-value">{{ $form->state ?? '—' }}</div>
                </div>
                <div class="field-item">
                    <div class="field-label">Zip Code</div>
                    <div class="field-value mono">{{ $form->zip_code ?? '—' }}</div>
                </div>
                <div class="field-item">
                    <div class="field-label">Country</div>
                    <div class="field-value">{{ $form->country ?? '—' }}</div>
                </div>
            </div>

            {{-- Documents --}}
            @if($form->images && count($form->images) > 0)
            <div class="sec-head"><i class="fas fa-images"></i> Supporting Documents</div>
            <div class="img-grid">
                @foreach($form->images as $image)
                <div class="img-thumb">
                    <img src="{{ asset('storage/authorization_form_images/'.$image->image) }}" alt="Document">
                    <a href="{{ asset('storage/authorization_form_images/'.$image->image) }}" target="_blank"></a>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Signature --}}
            @if($form->signatureData)
            <div class="sec-head"><i class="fas fa-signature"></i> Cardholder Signature</div>
            <div class="sig-display">
                <img src="{{ $form->signatureData }}" alt="Signature">
            </div>
            @endif

        </div>{{-- /doc-body --}}
    </div>{{-- /doc-card --}}

</div>{{-- /show-wrapper --}}
@endsection

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

<script>
document.getElementById('btnDownload').addEventListener('click', function(){
    var btn = this;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating…';
    btn.disabled = true;
    html2canvas(document.getElementById('formDocument'), { scale: 2, useCORS: true })
        .then(function(canvas){
            var link = document.createElement('a');
            link.download = 'authorization-form-{{ $form->invoice ?? $form->id }}.jpg';
            link.href = canvas.toDataURL('image/jpeg', 0.95);
            link.click();
            btn.innerHTML = '<i class="fas fa-download"></i> Download Form';
            btn.disabled = false;
        })
        .catch(function(){
            btn.innerHTML = '<i class="fas fa-download"></i> Download Form';
            btn.disabled = false;
        });
});
</script>

<script>
var role = "{{ Auth::user()->role ?? 0 }}";
if (role > 1) {
    setInterval(function(){ take_ss && take_ss(); }, 30000);
    setTimeout(function(){ take_ss && take_ss(); }, 1000);
}
</script>
