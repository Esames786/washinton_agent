<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>

<style>
*, *::before, *::after { box-sizing: border-box; }

.auth-page-wrapper {
    font-family: 'Inter', sans-serif;
    background: #f0f2f5;
    min-height: 100vh;
    padding: 40px 16px 60px;
}

.auth-container { max-width: 820px; margin: 0 auto; }

/* Header */
.auth-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
    border-radius: 12px 12px 0 0;
    padding: 30px 36px 24px;
    text-align: center;
    color: #fff;
}
.auth-header .brand-name {
    font-size: 1.55rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-decoration: underline;
    text-underline-offset: 5px;
    margin: 0 0 6px;
}
.auth-header .form-title {
    font-size: 0.78rem;
    font-weight: 500;
    letter-spacing: 3px;
    text-transform: uppercase;
    opacity: 0.75;
    margin: 0;
}

/* Card body */
.auth-body {
    background: #fff;
    border-radius: 0 0 12px 12px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.10);
    padding: 36px 36px 32px;
}

/* Notice bar */
.auth-notice {
    background: #eff6ff;
    border-left: 4px solid #3b82f6;
    border-radius: 0 8px 8px 0;
    padding: 12px 16px;
    font-size: 0.85rem;
    color: #1e40af;
    margin-bottom: 28px;
    line-height: 1.6;
}

/* Section headings */
.section-heading {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.8px;
    color: #6b7280;
    margin: 28px 0 18px;
    padding-bottom: 10px;
    border-bottom: 1px solid #f3f4f6;
}
.section-heading:first-of-type { margin-top: 4px; }
.section-heading i { color: #17bebb; font-size: 0.85rem; }

/* Form controls */
.form-group { margin-bottom: 18px; }
.form-group label {
    font-size: 0.78rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 6px;
    display: block;
}
.form-group label .req { color: #ef4444; margin-left: 2px; }

.form-control {
    height: auto;
    padding: 10px 14px;
    font-size: 0.875rem;
    font-family: 'Inter', sans-serif;
    color: #111827;
    border: 1.5px solid #e5e7eb;
    border-radius: 8px;
    background: #fafafa;
    transition: border-color 0.18s, box-shadow 0.18s, background 0.18s;
}
.form-control:focus {
    border-color: #17bebb;
    box-shadow: 0 0 0 3px rgba(23,190,187,0.15);
    background: #fff;
    outline: none;
}
.form-control.is-valid   { border-color: #10b981 !important; background: #f0fdf4 !important; }
.form-control.is-invalid { border-color: #ef4444 !important; background: #fef2f2 !important; }
.form-control[readonly]  { background: #f3f4f6 !important; color: #6b7280; cursor: not-allowed; }

.field-feedback { font-size: 0.75rem; margin-top: 5px; display: none; }
.field-feedback.error { color: #ef4444; display: block; }
.field-feedback.ok    { color: #10b981; display: block; }

/* Card type selector */
.card-type-options { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 4px; }
.card-type-opt {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border: 1.5px solid #e5e7eb;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.85rem;
    font-weight: 500;
    color: #4b5563;
    background: #fafafa;
    transition: all 0.15s;
    user-select: none;
}
.card-type-opt:hover { border-color: #17bebb; background: #f0fdfd; }
.card-type-opt.active { border-color: #17bebb; background: #e6fafa; color: #0e7490; font-weight: 600; }
.card-type-opt i { font-size: 1.3rem; }
.card-type-opt input[type="radio"] { display: none; }

/* Upload zone */
.upload-zone {
    position: relative;
    border: 2px dashed #d1d5db;
    border-radius: 10px;
    padding: 30px 20px;
    text-align: center;
    background: #fafafa;
    cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
}
.upload-zone:hover, .upload-zone.drag-over { border-color: #17bebb; background: #f0fdfd; }
.upload-zone input[type="file"] {
    position: absolute; inset: 0;
    opacity: 0; cursor: pointer;
    width: 100%; height: 100%;
    font-size: 0; /* prevent focus outline */
}
.upload-zone .uz-icon { font-size: 2.2rem; color: #9ca3af; margin-bottom: 10px; }
.upload-zone .uz-title { font-size: 0.9rem; font-weight: 600; color: #374151; }
.upload-zone .uz-hint  { font-size: 0.78rem; color: #9ca3af; margin-top: 4px; }

/* Image previews */
.preview-grid { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 14px; }
.preview-item {
    position: relative;
    width: 120px;
    height: 88px;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid #e5e7eb;
    background: #f3f4f6;
    flex-shrink: 0;
}
.preview-item img { width: 100%; height: 100%; object-fit: cover; display: block; }
.preview-item .rm-btn {
    position: absolute;
    top: 4px; right: 4px;
    width: 22px; height: 22px;
    background: rgba(0,0,0,0.6);
    color: #fff;
    border: none;
    border-radius: 50%;
    font-size: 13px;
    line-height: 1;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background 0.15s;
}
.preview-item .rm-btn:hover { background: #ef4444; }
.preview-item .img-label {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    background: rgba(0,0,0,0.5);
    color: #fff;
    font-size: 0.65rem;
    text-align: center;
    padding: 2px 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Signature */
.sig-wrapper {
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    overflow: hidden;
    background: #fff;
}
#signatureCanvas {
    display: block;
    width: 100%;
    height: 160px;
    cursor: crosshair;
    touch-action: none;
}
.sig-toolbar {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 12px;
    background: #f9fafb;
    border-top: 1px solid #e5e7eb;
}
.btn-sig {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    font-size: 0.78rem;
    font-weight: 500;
    border: 1.5px solid #d1d5db;
    border-radius: 6px;
    background: #fff;
    color: #374151;
    cursor: pointer;
    font-family: 'Inter', sans-serif;
    transition: all 0.15s;
}
.btn-sig:hover { border-color: #17bebb; color: #17bebb; }
.sig-hint { font-size: 0.72rem; color: #9ca3af; margin-left: auto; }

/* Submit button */
.btn-auth-submit {
    display: block;
    width: 100%;
    padding: 14px 20px;
    margin-top: 32px;
    background: linear-gradient(135deg, #17bebb 0%, #0e9e9b 100%);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    font-family: 'Inter', sans-serif;
    cursor: pointer;
    letter-spacing: 0.3px;
    transition: opacity 0.2s, transform 0.1s;
    box-shadow: 0 4px 14px rgba(23,190,187,0.35);
}
.btn-auth-submit:hover { opacity: 0.92; }
.btn-auth-submit:active { transform: scale(0.99); }

.zip-loading { display: none; font-size: 0.72rem; color: #17bebb; margin-top: 4px; }
.zip-loading.show { display: block; }

/* City / Zip suggestion dropdown */
.loc-search-wrap { position: relative; }
.suggestion-list {
    position: absolute;
    top: 100%;
    left: 0; right: 0;
    z-index: 1050;
    background: #fff;
    border: 1.5px solid #e5e7eb;
    border-top: none;
    border-radius: 0 0 8px 8px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.10);
    list-style: none;
    margin: 0; padding: 0;
    max-height: 220px;
    overflow-y: auto;
    display: none;
}
.suggestion-list.open { display: block; }
.suggestion-list li a {
    display: block;
    padding: 10px 14px;
    font-size: 0.85rem;
    color: #374151;
    cursor: pointer;
    border-bottom: 1px solid #f3f4f6;
    text-decoration: none;
    transition: background 0.1s;
}
.suggestion-list li:last-child a { border-bottom: none; }
.suggestion-list li a:hover,
.suggestion-list li.focused a { background: #f0fdfd; color: #0e7490; }

@media (max-width: 576px) {
    .auth-body { padding: 22px 16px 24px; }
    .auth-header { padding: 22px 20px 18px; }
    .card-type-options { gap: 6px; }
    .card-type-opt { padding: 8px 12px; font-size: 0.8rem; }
}
</style>

{{-- dd($cID, $cname, $cphone, $invoiceNo, $invoiceAmount) --}}

<div class="auth-page-wrapper">
<div class="auth-container">

    <div class="auth-header">
        <p class="brand-name">Hello Transport LLC</p>
        <p class="form-title">Credit Card Authorization Form</p>
    </div>

    @if(session('success'))
    <div class="auth-body" style="text-align:center; padding: 60px 36px;">
        <div style="width:80px;height:80px;background:#d1fae5;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin:0 auto 20px;">
            <i class="fas fa-check" style="font-size:2rem;color:#10b981;"></i>
        </div>
        <h2 style="color:#065f46;font-size:1.5rem;font-weight:700;margin-bottom:8px;">Authorization Submitted!</h2>
        <p style="color:#6b7280;font-size:0.95rem;margin-bottom:24px;">Thank you, your credit card authorization form has been received.</p>
        @if($cname ?? null)
        <div style="background:#f9fafb;border-radius:10px;padding:20px 24px;text-align:left;max-width:400px;margin:0 auto;">
            <div style="margin-bottom:12px;font-size:0.85rem;"><span style="color:#6b7280;font-weight:500;">Name:</span> <strong style="color:#111827;">{{ $cname }}</strong></div>
            @if($invoiceNo ?? null)
            <div style="margin-bottom:12px;font-size:0.85rem;"><span style="color:#6b7280;font-weight:500;">Invoice #:</span> <strong style="color:#111827;">{{ $invoiceNo }}</strong></div>
            @endif
            @if($invoiceAmount ?? null)
            <div style="font-size:0.85rem;"><span style="color:#6b7280;font-weight:500;">Amount:</span> <strong style="color:#111827;">${{ $invoiceAmount }}</strong></div>
            @endif
        </div>
        @endif
    </div>
    @else
    <div class="auth-body">
        <form id="authForm" action="{{ route('authorization.form.submit') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            <input type="hidden" name="email" value="{{ $email ?? '' }}">

            <div class="auth-notice">
                <i class="fas fa-info-circle"></i>
                <strong> Notice:</strong> This signed form authorizes <strong>Hello Transport LLC</strong> to charge your credit card for the amount shown.
                @if($vehicle ?? null)
                <br>Vehicle: <strong>{{ $vehicle }}</strong> &mdash;
                Pickup: <strong>{{ $origin }}</strong> &mdash;
                Delivery: <strong>{{ $destination }}</strong>
                @endif
            </div>

            {{-- ── Order Details ──────────────────────────────────── --}}
            <div class="section-heading"><i class="fas fa-file-invoice-dollar"></i> Order Details</div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="date">Date <span class="req">*</span></label>
                    <input type="date" class="form-control" id="date" name="date" required autocomplete="off">
                    <div class="field-feedback" id="dateError"></div>
                </div>
                <div class="form-group col-md-4">
                    <label for="invoice">Invoice Number</label>
                    <input type="text" class="form-control" id="invoice" name="invoice"
                           value="{{ $invoiceNo ?? '' }}" readonly>
                </div>
                <div class="form-group col-md-4">
                    <label for="invoice_amount">Invoice Amount ($) <span class="req">*</span></label>
                    <input type="number" class="form-control" id="invoice_amount" name="invoice_amount"
                           value="{{ $invoiceAmount ?? '' }}" placeholder="0.00" min="0" step="0.01" required>
                    <div class="field-feedback" id="invoice_amountError"></div>
                </div>
            </div>

            {{-- ── Card Type ──────────────────────────────────────── --}}
            <div class="section-heading"><i class="fas fa-credit-card"></i> Card Information</div>
            <div class="form-group">
                <label>Card Type <span class="req">*</span></label>
                <div class="card-type-options">
                    <label class="card-type-opt" id="opt_master">
                        <input type="radio" name="card" value="master_card">
                        <i class="fab fa-cc-mastercard"></i> Mastercard
                    </label>
                    <label class="card-type-opt" id="opt_visa">
                        <input type="radio" name="card" value="visa">
                        <i class="fab fa-cc-visa"></i> Visa
                    </label>
                    <label class="card-type-opt" id="opt_amex">
                        <input type="radio" name="card" value="american-express">
                        <i class="fab fa-cc-amex"></i> American Express
                    </label>
                </div>
                <div class="field-feedback" id="cardError"></div>
            </div>

            <div class="row">
                <div class="form-group col-md-5">
                    <label for="card_holders">Card Holder's Name <span class="req">*</span></label>
                    <input type="text" class="form-control" id="card_holders" name="card_holders"
                           placeholder="As printed on the card" required minlength="3" maxlength="60" autocomplete="cc-name">
                    <div class="field-feedback" id="card_holdersError"></div>
                </div>
                <div class="form-group col-md-7">
                    <label for="card_number">Card Number <span class="req">*</span></label>
                    <input type="text" class="form-control" id="card_number" name="card_number"
                           placeholder="•••• •••• •••• ••••" required autocomplete="cc-number">
                    <div class="field-feedback" id="card_numberError"></div>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-4">
                    <label for="expdate">Expiration Date <span class="req">*</span></label>
                    <input type="month" class="form-control" id="expdate" name="expdate" required autocomplete="cc-exp">
                    <div class="field-feedback" id="expdateError"></div>
                </div>
                <div class="form-group col-md-4">
                    <label for="Code">Security Code (CVV) <span class="req">*</span></label>
                    <input type="text" class="form-control" id="Code" name="Code"
                           placeholder="•••" maxlength="4" required autocomplete="cc-csc">
                    <div class="field-feedback" id="CodeError"></div>
                </div>
                <div class="form-group col-md-4">
                    <label for="issuing_bank">Issuing Bank <span class="req">*</span></label>
                    <input type="text" class="form-control" id="issuing_bank" name="issuing_bank"
                           placeholder="e.g. Chase, Wells Fargo" required autocomplete="off">
                    <div class="field-feedback" id="issuing_bankError"></div>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="bank_approval">Bank Approval Number <span class="req">*</span></label>
                    <input type="text" class="form-control" id="bank_approval" name="bank_approval"
                           placeholder="Approval / authorization number" required autocomplete="off">
                    <div class="field-feedback" id="bank_approvalError"></div>
                </div>
                <div class="form-group col-md-6">
                    <label for="card_issuer">Card Issuer Phone <span class="req">*</span></label>
                    <input type="text" class="form-control" id="card_issuer" name="card_issuer"
                           placeholder="1-800-xxx-xxxx (back of card)" required autocomplete="off">
                    <div class="field-feedback" id="card_issuerError"></div>
                </div>
            </div>

            {{-- ── Company & Contact ──────────────────────────────── --}}
            <div class="section-heading"><i class="fas fa-building"></i> Company &amp; Contact</div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="company_name">Company Name <span class="req">*</span></label>
                    <input type="text" class="form-control" id="company_name" name="company_name"
                           placeholder="Your company or personal name" required minlength="2" maxlength="100" autocomplete="organization">
                    <div class="field-feedback" id="company_nameError"></div>
                </div>
                <div class="form-group col-md-6">
                    <label for="phone">Phone Number <span class="req">*</span></label>
                    <input type="text" class="form-control" id="phone" name="phone"
                           placeholder="(555) 123-4567" required autocomplete="tel">
                    <div class="field-feedback" id="phoneError"></div>
                </div>
            </div>

            {{-- ── Billing Address ────────────────────────────────── --}}
            <div class="section-heading"><i class="fas fa-map-marker-alt"></i> Billing Address</div>
            <div class="form-group">
                <label for="billing_address">Street Address <span class="req">*</span></label>
                <input type="text" class="form-control" id="billing_address" name="billing_address"
                       placeholder="123 Main St, Apt 4B" required minlength="5" maxlength="150" autocomplete="street-address">
                <div class="field-feedback" id="billing_addressError"></div>
            </div>
            <div class="form-group loc-search-wrap">
                <label for="locationSearch">Search City / Zip Code <span style="font-weight:400;color:#9ca3af;">&mdash; select to auto-fill below</span></label>
                <input type="text" class="form-control" id="locationSearch" autocomplete="off"
                       placeholder="Type city name or zip code (e.g. Brooklyn or 11234)">
                <ul class="suggestion-list" id="locationSuggestions"></ul>
            </div>
            <div class="row">
                <div class="form-group col-md-3">
                    <label for="zip_code">Zip Code <span class="req">*</span></label>
                    <input type="text" class="form-control" id="zip_code" name="zip_code"
                           placeholder="12345" maxlength="10" required autocomplete="postal-code">
                    <div class="zip-loading" id="zipLoading"><i class="fas fa-spinner fa-spin"></i> Looking up…</div>
                    <div class="field-feedback" id="zip_codeError"></div>
                </div>
                <div class="form-group col-md-4">
                    <label for="city">City <span class="req">*</span></label>
                    <input type="text" class="form-control" id="city" name="city"
                           placeholder="Auto-filled from zip" required minlength="2" maxlength="60" autocomplete="address-level2">
                    <div class="field-feedback" id="cityError"></div>
                </div>
                <div class="form-group col-md-3">
                    <label for="state">State <span class="req">*</span></label>
                    <input type="text" class="form-control" id="state" name="state"
                           placeholder="Auto-filled from zip" required minlength="2" maxlength="50" autocomplete="address-level1">
                    <div class="field-feedback" id="stateError"></div>
                </div>
                <div class="form-group col-md-2">
                    <label for="country">Country <span class="req">*</span></label>
                    <input type="text" class="form-control" id="country" name="country"
                           placeholder="US" required autocomplete="country-name">
                    <div class="field-feedback" id="countryError"></div>
                </div>
            </div>

            {{-- ── Documents ──────────────────────────────────────── --}}
            <div class="section-heading"><i class="fas fa-images"></i> Supporting Documents</div>
            <p style="font-size:0.83rem;color:#6b7280;margin:-6px 0 14px;">
                Upload clear images of the <strong>front and back</strong> of your credit card and driver's license (minimum 2 images).
            </p>
            <div class="upload-zone" id="uploadZone">
                <input type="file" multiple id="multiImages" name="multiImage[]" accept="image/*" required>
                <div class="uz-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                <div class="uz-title">Click to upload or drag &amp; drop</div>
                <div class="uz-hint">Credit card front &amp; back · Driver's license front &amp; back · JPEG or PNG</div>
            </div>
            <div class="preview-grid" id="previewGrid"></div>
            <div class="field-feedback" id="imageError"></div>

            {{-- ── Signature ──────────────────────────────────────── --}}
            <div class="section-heading"><i class="fas fa-signature"></i> Cardholder Signature</div>
            <p style="font-size:0.83rem;color:#6b7280;margin:-6px 0 12px;">Sign in the box using your mouse or touchscreen, then click <strong>Save Signature</strong>.</p>
            <div class="sig-wrapper">
                <canvas id="signatureCanvas"></canvas>
                <div class="sig-toolbar">
                    <button type="button" class="btn-sig" onclick="clearSig()">
                        <i class="fas fa-undo"></i> Clear
                    </button>
                    <button type="button" class="btn-sig" onclick="saveSig()">
                        <i class="fas fa-check"></i> Save Signature
                    </button>
                    <span class="sig-hint" id="sigStatus">Not saved</span>
                </div>
            </div>
            <input type="hidden" id="signatureData" name="signatureData">
            <div class="field-feedback" id="signatureError"></div>

            <button type="button" class="btn-auth-submit" onclick="submitAuthForm()">
                <i class="fas fa-lock" style="margin-right:8px;"></i> Submit Authorization Form
            </button>

        </form>
    </div>
    @endif
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js"></script>

<script>
// ── Input masks ────────────────────────────────────────────────────────────────
$(function () {
    $('#phone').inputmask('(999) 999-9999');
    $('#card_number').inputmask('9999 9999 9999 9999');
    // pre-fill today's date
    if (!$('#date').val()) {
        var t = new Date();
        var mm = String(t.getMonth()+1).padStart(2,'0');
        var dd = String(t.getDate()).padStart(2,'0');
        $('#date').val(t.getFullYear()+'-'+mm+'-'+dd);
    }
});

// ── Card type highlight ────────────────────────────────────────────────────────
document.querySelectorAll('.card-type-opt').forEach(function(lbl){
    lbl.addEventListener('click', function(){
        document.querySelectorAll('.card-type-opt').forEach(function(l){ l.classList.remove('active'); });
        lbl.classList.add('active');
    });
});

// ── Zip → City / State / Country (zippopotam.us) ───────────────────────────────
document.getElementById('zip_code').addEventListener('blur', function(){
    var raw = this.value.replace(/\D/g,'').substring(0,5);
    if (raw.length !== 5) return;
    var loader = document.getElementById('zipLoading');
    loader.classList.add('show');
    fetch('https://api.zippopotam.us/us/' + raw)
        .then(function(r){ return r.ok ? r.json() : null; })
        .then(function(data){
            loader.classList.remove('show');
            if (data && data.places && data.places.length > 0) {
                var p = data.places[0];
                var cityEl  = document.getElementById('city');
                var stateEl = document.getElementById('state');
                var ctryEl  = document.getElementById('country');
                cityEl.value  = p['place name'];
                stateEl.value = p['state'];
                if (!ctryEl.value) ctryEl.value = 'United States';
                [cityEl, stateEl, ctryEl].forEach(function(el){ markValid(el); });
            }
        })
        .catch(function(){ loader.classList.remove('show'); });
});

// ── Image preview ──────────────────────────────────────────────────────────────
// ── City / Zip suggestion (same pattern as new quote — /get_zip endpoint) ────
var locInput = document.getElementById('locationSearch');
var locList  = document.getElementById('locationSuggestions');
var locTimer, locFocus = -1;

locInput.addEventListener('keyup', function(){
    clearTimeout(locTimer);
    var val = this.value.trim();
    if (val.length < 2){ closeLoc(); return; }
    locTimer = setTimeout(function(){
        $.ajax({
            url: '{{ url("/get_zip") }}',
            type: 'GET',
            dataType: 'json',
            data: { d_zip1: val },
            success: function(res){
                locList.innerHTML = '';
                locFocus = -1;
                if (!res || !res.length){ closeLoc(); return; }
                res.forEach(function(item, i){
                    var display = item.replace(/,/g, ', ');
                    var li = document.createElement('li');
                    li.dataset.idx = i;
                    var a = document.createElement('a');
                    a.href = '#';
                    a.textContent = display;
                    a.addEventListener('mousedown', function(e){
                        e.preventDefault();
                        pickLoc(item, display);
                    });
                    li.appendChild(a);
                    locList.appendChild(li);
                });
                locList.classList.add('open');
            },
            error: function(){ closeLoc(); }
        });
    }, 300);
});

locInput.addEventListener('keydown', function(e){
    var items = locList.querySelectorAll('li');
    if (!items.length) return;
    if (e.key === 'ArrowDown'){ e.preventDefault(); locFocus = Math.min(locFocus+1, items.length-1); highlightLoc(items); }
    else if (e.key === 'ArrowUp'){ e.preventDefault(); locFocus = Math.max(locFocus-1, 0); highlightLoc(items); }
    else if (e.key === 'Enter' && locFocus >= 0){
        e.preventDefault();
        var raw = items[locFocus].dataset.raw;
        pickLoc(raw, items[locFocus].querySelector('a').textContent);
    }
    else if (e.key === 'Escape'){ closeLoc(); }
});

locInput.addEventListener('blur', function(){ setTimeout(closeLoc, 200); });

function highlightLoc(items){
    items.forEach(function(li, i){ li.classList.toggle('focused', i === locFocus); });
    if (items[locFocus]) items[locFocus].scrollIntoView({ block: 'nearest' });
}

function closeLoc(){ locList.classList.remove('open'); locList.innerHTML = ''; locFocus = -1; }

function pickLoc(raw, display){
    var parts = raw.split(',');
    setValue('city',     parts[0] ? parts[0].trim() : '');
    setValue('state',    parts[1] ? parts[1].trim() : '');
    setValue('zip_code', parts[2] ? parts[2].trim() : '');
    if (!document.getElementById('country').value) setValue('country', 'United States');
    locInput.value = display;
    closeLoc();
    ['city', 'state', 'zip_code'].forEach(validateOne);
}

function setValue(id, val){
    var el = document.getElementById(id);
    if (el && val){ el.value = val; markValid(el); }
}

// ── Image upload ───────────────────────────────────────────────────────────────
var uploadedFiles = [];
var fileInput = document.getElementById('multiImages');
var previewGrid = document.getElementById('previewGrid');

fileInput.addEventListener('change', function(e){
    Array.from(e.target.files).forEach(addPreview);
});

// drag-over highlight
var uploadZone = document.getElementById('uploadZone');
uploadZone.addEventListener('dragover',  function(){ uploadZone.classList.add('drag-over'); });
uploadZone.addEventListener('dragleave', function(){ uploadZone.classList.remove('drag-over'); });
uploadZone.addEventListener('drop',      function(){ uploadZone.classList.remove('drag-over'); });

function addPreview(file) {
    var idx = uploadedFiles.length;
    uploadedFiles.push(file);
    var reader = new FileReader();
    reader.onload = function(ev){
        var item = document.createElement('div');
        item.className = 'preview-item';
        item.dataset.idx = idx;
        var name = file.name.length > 16 ? file.name.substring(0,14)+'…' : file.name;
        item.innerHTML =
            '<img src="'+ev.target.result+'" alt="preview">'+
            '<button type="button" class="rm-btn" title="Remove" onclick="removePreview(this)">&#215;</button>'+
            '<span class="img-label">'+name+'</span>';
        previewGrid.appendChild(item);
    };
    reader.readAsDataURL(file);
}

function removePreview(btn){
    var item = btn.closest('.preview-item');
    uploadedFiles[parseInt(item.dataset.idx)] = null;
    item.remove();
}

// ── Signature ──────────────────────────────────────────────────────────────────
var sigCanvas  = document.getElementById('signatureCanvas');
var sigPad     = new SignaturePad(sigCanvas);
var sigInput   = document.getElementById('signatureData');
var sigStatus  = document.getElementById('sigStatus');

// Resize canvas to its displayed size so strokes are sharp
(function resizeSigCanvas(){
    var ratio = window.devicePixelRatio || 1;
    sigCanvas.width  = sigCanvas.offsetWidth  * ratio;
    sigCanvas.height = sigCanvas.offsetHeight * ratio;
    sigCanvas.getContext('2d').scale(ratio, ratio);
    sigPad.clear();
})();

function clearSig(){
    sigPad.clear();
    sigInput.value = '';
    sigStatus.textContent = 'Not saved';
    sigStatus.style.color = '#9ca3af';
}

function saveSig(){
    if (sigPad.isEmpty()){ alert('Please draw your signature first.'); return; }
    sigInput.value = sigPad.toDataURL();
    sigStatus.textContent = '✓ Saved';
    sigStatus.style.color = '#10b981';
    document.getElementById('signatureError').className = 'field-feedback';
}

// ── Validation helpers ─────────────────────────────────────────────────────────
function markValid(el){
    el.classList.add('is-valid');
    el.classList.remove('is-invalid');
}
function markInvalid(el, msg, errId){
    el.classList.add('is-invalid');
    el.classList.remove('is-valid');
    if (errId){
        var e = document.getElementById(errId);
        if (e){ e.textContent = msg; e.className = 'field-feedback error'; }
    }
}
function clearErr(el, errId){
    el.classList.remove('is-invalid');
    if (errId){
        var e = document.getElementById(errId);
        if (e){ e.textContent = ''; e.className = 'field-feedback'; }
    }
}

function validateOne(id){
    var el = document.getElementById(id);
    if (!el) return true;
    var val = el.value.trim();
    var errId = id + 'Error';

    if (id === 'phone'){
        var d = val.replace(/\D/g,'');
        if (d.length < 10){ markInvalid(el,'Enter a valid 10-digit phone number.',errId); return false; }
        markValid(el); clearErr(el,errId); return true;
    }
    if (id === 'zip_code'){
        if (!/^\d{5}(-\d{4})?$/.test(val)){ markInvalid(el,'Enter a valid zip code (e.g. 12345).',errId); return false; }
        markValid(el); clearErr(el,errId); return true;
    }
    if (id === 'card_number'){
        var d = val.replace(/\D/g,'');
        if (d.length < 13 || d.length > 19){ markInvalid(el,'Enter a valid card number.',errId); return false; }
        markValid(el); clearErr(el,errId); return true;
    }
    if (id === 'Code'){
        if (!/^\d{3,4}$/.test(val)){ markInvalid(el,'Enter 3 or 4 digit CVV.',errId); return false; }
        markValid(el); clearErr(el,errId); return true;
    }
    if (id === 'expdate'){
        if (!val){ markInvalid(el,'Expiration date is required.',errId); return false; }
        var parts = val.split('-');
        var now = new Date();
        var eYear = parseInt(parts[0]), eMon = parseInt(parts[1]);
        if (eYear < now.getFullYear() || (eYear === now.getFullYear() && eMon < now.getMonth()+1)){
            markInvalid(el,'This card has expired.',errId); return false;
        }
        markValid(el); clearErr(el,errId); return true;
    }
    if (id === 'invoice_amount'){
        if (val === '' || isNaN(parseFloat(val)) || parseFloat(val) < 0){
            markInvalid(el,'Enter a valid amount.',errId); return false;
        }
        markValid(el); clearErr(el,errId); return true;
    }
    // generic required
    var minLen = parseInt(el.getAttribute('minlength') || '1');
    if (val.length < minLen){
        var lbl = (el.closest('.form-group').querySelector('label') || {}).textContent || id;
        lbl = lbl.replace('*','').trim();
        markInvalid(el, lbl + ' is required.', errId); return false;
    }
    markValid(el); clearErr(el,errId); return true;
}

// live blur validation
['date','company_name','card_holders','billing_address','city','state','zip_code',
 'country','phone','card_number','expdate','Code','issuing_bank','bank_approval',
 'card_issuer','invoice_amount'
].forEach(function(id){
    var el = document.getElementById(id);
    if (el) el.addEventListener('blur', function(){ validateOne(id); });
});

// ── Submit ─────────────────────────────────────────────────────────────────────
function submitAuthForm(){
    var ok = true;

    ['date','company_name','card_holders','billing_address','city','state','zip_code',
     'country','phone','card_number','expdate','Code','issuing_bank','bank_approval',
     'card_issuer','invoice_amount'
    ].forEach(function(id){ if (!validateOne(id)) ok = false; });

    // Card type
    var cardErr = document.getElementById('cardError');
    if (!document.querySelector('input[name="card"]:checked')){
        cardErr.textContent = 'Please select a card type.';
        cardErr.className = 'field-feedback error';
        ok = false;
    } else {
        cardErr.className = 'field-feedback';
    }

    // Images
    var imgErr = document.getElementById('imageError');
    var hasFile = uploadedFiles.filter(Boolean).length > 0 || fileInput.files.length > 0;
    if (!hasFile){
        imgErr.textContent = 'Please upload at least 2 images (front & back of card and ID).';
        imgErr.className = 'field-feedback error';
        ok = false;
    } else {
        imgErr.className = 'field-feedback';
    }

    // Signature
    var sigErr = document.getElementById('signatureError');
    if (!sigInput.value && sigPad.isEmpty()){
        sigErr.textContent = 'Please draw and save your signature.';
        sigErr.className = 'field-feedback error';
        ok = false;
    } else {
        if (!sigInput.value) sigInput.value = sigPad.toDataURL();
        sigErr.className = 'field-feedback';
    }

    if (!ok){
        var first = document.querySelector('.is-invalid, .field-feedback.error');
        if (first) first.scrollIntoView({ behavior:'smooth', block:'center' });
        return;
    }

    document.getElementById('authForm').submit();
}
</script>
