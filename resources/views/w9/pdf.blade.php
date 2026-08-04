<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
@php
    $brand       = (is_array($brand ?? null) && $brand) ? $brand : \App\Support\Brand::byKey(config('brands.default', 'hellotransport'));
    $companyName = $brand['name'] ?? 'Hello Transport';
@endphp
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size:11pt; color:#222; line-height:1.5; padding:38px 46px; }
    .head { text-align:center; border-bottom:2px solid #222; padding-bottom:10px; margin-bottom:18px; }
    .head .form-no { font-size:20pt; font-weight:bold; letter-spacing:1px; }
    .head .title { font-size:12pt; font-weight:bold; margin-top:2px; }
    .head .sub { font-size:9.5pt; color:#555; margin-top:3px; }
    .meta { font-size:9.5pt; color:#555; margin-bottom:14px; text-align:center; }
    table.f { width:100%; border-collapse:collapse; margin-bottom:14px; }
    table.f th, table.f td { border:1px solid #bbb; padding:6px 9px; vertical-align:top; font-size:10pt; }
    table.f th { background:#f2f2f2; text-align:left; width:34%; font-weight:bold; }
    .sect { font-size:10.5pt; font-weight:bold; background:#e9e9e9; padding:5px 9px; border:1px solid #bbb; border-bottom:0; margin-top:4px; }
    .cert { font-size:9.5pt; color:#333; border:1px solid #bbb; padding:9px 11px; margin-bottom:12px; }
    .cert li { margin-left:16px; margin-bottom:3px; }
    .sig-img { border:1px solid #ccc; max-width:250px; max-height:75px; }
    .foot { margin-top:22px; border-top:1px solid #ddd; padding-top:9px; font-size:9pt; color:#666; text-align:center; }
</style>
</head>
<body>

<div class="head">
    <div class="form-no">Form W-9</div>
    <div class="title">Request for Taxpayer Identification Number and Certification</div>
    <div class="sub">Submitted electronically to {{ $companyName }}</div>
</div>

<div class="meta">
    Submitted {{ $form->signed_at ? $form->signed_at->format('d M Y, h:i A') : '—' }}
    @if($form->signed_ip) &nbsp;·&nbsp; IP {{ $form->signed_ip }} @endif
</div>

<div class="sect">Taxpayer Information</div>
<table class="f">
    <tr><th>1. Name (as shown on your income tax return)</th><td>{{ $form->legal_name }}</td></tr>
    <tr><th>2. Business name / disregarded entity</th><td>{{ $form->business_name ?: '—' }}</td></tr>
    <tr><th>3. Federal tax classification</th><td>{{ $form->classificationLabel() }}</td></tr>
    <tr><th>4. Exempt payee code</th><td>{{ $form->exempt_payee_code ?: '—' }}</td></tr>
    <tr><th>&nbsp;&nbsp;&nbsp;&nbsp;FATCA reporting exemption code</th><td>{{ $form->fatca_code ?: '—' }}</td></tr>
    <tr><th>5. Address</th><td>{{ $form->address }}</td></tr>
    <tr><th>6. City, state, ZIP</th><td>{{ $form->city }}, {{ $form->state }} {{ $form->zip }}</td></tr>
    <tr><th>7. Account number(s)</th><td>{{ $form->account_numbers ?: '—' }}</td></tr>
</table>

<div class="sect">Part I — Taxpayer Identification Number (TIN)</div>
<table class="f">
    <tr>
        <th>{{ $form->tin_type === 'ein' ? 'Employer Identification Number (EIN)' : 'Social Security Number (SSN)' }}</th>
        <td>{{ $tin ?: $form->maskedTin() }}</td>
    </tr>
</table>

<div class="sect">Part II — Certification</div>
<div class="cert">
    Under penalties of perjury, I certify that:
    <ol>
        <li>The number shown on this form is my correct taxpayer identification number (or I am waiting for a number to be issued to me); and</li>
        <li>I am not subject to backup withholding because: (a) I am exempt from backup withholding, or (b) I have not been notified by the Internal Revenue Service (IRS) that I am subject to backup withholding as a result of a failure to report all interest or dividends, or (c) the IRS has notified me that I am no longer subject to backup withholding; and</li>
        <li>I am a U.S. citizen or other U.S. person; and</li>
        <li>The FATCA code(s) entered on this form (if any) indicating that I am exempt from FATCA reporting is correct.</li>
    </ol>
</div>

<table class="f">
    <tr>
        <th>Signature of U.S. person</th>
        <td>
            @if($form->signature)
                <img class="sig-img" src="{{ $form->signature }}" alt="Signature">
            @else
                —
            @endif
        </td>
    </tr>
    <tr><th>Date</th><td>{{ $form->signed_at ? $form->signed_at->format('d M Y') : '—' }}</td></tr>
</table>

<div class="foot">
    {{ strtoupper($companyName) }} — electronically submitted W-9. Retained for tax reporting purposes.
</div>

</body>
</html>
