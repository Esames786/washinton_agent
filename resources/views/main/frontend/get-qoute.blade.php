@extends('layouts.frontend-master')

@section('page_title', 'Get an Instant Auto Shipping Quote')
@section('meta_description', 'Get an instant, no-obligation car shipping quote from Hello Transport. Enter your pickup and delivery locations to see competitive rates in seconds.')

@section('content')

<style>
    /* Form controls */
    #quote-form .form-group { margin-bottom: 12px; }
    #quote-form select.form-control,
    #quote-form input.form-control { width: 100% !important; display: block !important; }
    #quote-form .nice-select { display: none !important; }
    #quote-form select { display: block !important; }

    /* Suggestion dropdown — same look as new quote screen */
    .suggestion-list {
        list-style: none;
        padding: 0;
        margin: 0;
        max-height: 200px;
        overflow-y: auto;
        display: none;
        background: #fff;
        border: 1px solid #ccc;
        border-top: none;
        border-radius: 0 0 4px 4px;
        z-index: 99999;
        position: absolute;
        width: 100%;
        box-shadow: 0 4px 8px rgba(0,0,0,.15);
    }
    .suggestion-list li a {
        display: block;
        padding: 7px 12px;
        color: #222;
        font-size: 13px;
        text-decoration: none;
        cursor: pointer;
    }
    .suggestion-list li a:hover,
    .suggestion-list li.active a { background: #0d6efd; color: #fff; }

    /* Wrapper needs position:relative for absolute dropdown */
    .zip-wrap, .make-wrap, .model-wrap { position: relative; }
</style>

{{-- Page Title --}}
<div class="page-title-area bg-25">
    <div class="container">
        <div class="page-title-content">
            <h2>Get Quote</h2>
            <ul>
                <li><a href="{{ route('Frontend.index') }}">Home</a></li>
                <li class="active">Get Quote</li>
            </ul>
        </div>
    </div>
</div>

{{-- Quote Form --}}
<section class="main-contact-area ptb-100">
    <div class="container">

        <div class="section-title">
            <span class="sub-title">Instant Quote</span>
            @php
                $labels = [
                    'Car'            => 'Car',
                    'Heavy Equipment'=> 'Heavy Equipment',
                    'Dryvan'         => 'Freight',
                    'Motorcycle'     => 'Motorcycle',
                    'ATV/UTV'        => 'ATV/UTV',
                    'Golf Cart'      => 'Golf Cart',
                ];
                $label = $labels[$type] ?? $type;
            @endphp
            <h2>Fast & Reliable {{ $label }} Shipping Quote</h2>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        @if($type === 'Dryvan')
            <div style="text-align:right;margin-bottom:10px;">
                <button type="button" class="default-btn active" data-bs-toggle="modal" data-bs-target="#freightClassModal">
                    Calculate Freight Class
                </button>
            </div>
        @endif

        <form id="quote-form" action="{{ route('Post.Instant.Quote') }}" method="POST">
            @csrf
            <input type="hidden" name="Select_Vehicle" value="{{ $type }}">

            {{-- Customer Info --}}
            <div class="row">
                <div class="col-lg-4 col-sm-6">
                    <div class="form-group">
                        <input type="text" name="Custo_Name" class="form-control"
                               placeholder="Full Name" required value="{{ old('Custo_Name') }}">
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="form-group">
                        <input type="text" name="Custo_Phone" class="form-control"
                               placeholder="Phone Number" required value="{{ old('Custo_Phone') }}">
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="form-group">
                        <input type="email" name="Custo_Email" class="form-control"
                               placeholder="Email Address" required value="{{ old('Custo_Email') }}">
                    </div>
                </div>
            </div>

            {{-- Origin / Destination with suggestion dropdown --}}
            <div class="row mt-2">
                <div class="col-lg-6">
                    <div class="form-group zip-wrap">
                        <input type="text" id="from_zip" name="From_ZipCode"
                               class="form-control" autocomplete="off"
                               placeholder="Moving From (City or Zip Code)"
                               required value="{{ old('From_ZipCode') }}">
                        <ul class="suggestion-list" id="from_zip_list"></ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group zip-wrap">
                        <input type="text" id="to_zip" name="To_ZipCode"
                               class="form-control" autocomplete="off"
                               placeholder="Moving To (City or Zip Code)"
                               required value="{{ old('To_ZipCode') }}">
                        <ul class="suggestion-list" id="to_zip_list"></ul>
                    </div>
                </div>
            </div>

            {{-- Vehicle type display --}}
            <div class="row mt-2">
                <div class="col-lg-12">
                    <div class="form-group">
                        <input type="text" class="form-control" value="{{ $type }}" readonly>
                    </div>
                </div>
            </div>

            {{-- Car / Motorcycle / ATV / Golf Cart --}}
            @if(in_array($type, ['Car', 'Motorcycle', 'ATV/UTV', 'Golf Cart']))
                <div class="row mt-2">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <input type="text" id="car_year" name="Car_Year"
                                   class="form-control" placeholder="Vehicle Year"
                                   value="{{ old('Car_Year') }}">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group make-wrap">
                            <input type="text" id="car_make" name="Car_Make"
                                   class="form-control" autocomplete="off"
                                   placeholder="Vehicle Make"
                                   value="{{ old('Car_Make') }}">
                            <ul class="suggestion-list" id="make_list"></ul>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group model-wrap">
                            <input type="text" id="car_model" name="Car_Model"
                                   class="form-control" autocomplete="off"
                                   placeholder="Vehicle Model"
                                   value="{{ old('Car_Model') }}">
                            <ul class="suggestion-list" id="model_list"></ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Carrier type & condition --}}
            <div class="row mt-2">
                <div class="col-lg-6">
                    <div class="form-group">
                        <select name="Carrier_Type" class="form-control w-100">
                            <option value="">Carrier Type</option>
                            <option>Open</option>
                            <option>Enclosed</option>
                            <option>Drive Away</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <select name="Carrier_Condition" class="form-control w-100">
                            <option value="">Vehicle Condition</option>
                            <option>Running</option>
                            <option>Not Running</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Heavy Equipment --}}
            @if($type === 'Heavy Equipment')
                <div class="row mt-2">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <input type="text" name="Year_Make_Model" class="form-control"
                                   placeholder="Enter Year, Make, Model"
                                   value="{{ old('Year_Make_Model') }}">
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <input type="text" name="Vehicle_Length" class="form-control"
                                   placeholder="Length (e.g. 20ft 7in)" value="{{ old('Vehicle_Length') }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <input type="text" name="Vehicle_Width" class="form-control"
                                   placeholder="Width (e.g. 8ft)" value="{{ old('Vehicle_Width') }}">
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <input type="text" name="Vehicle_Height" class="form-control"
                                   placeholder="Height (e.g. 10ft)" value="{{ old('Vehicle_Height') }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <input type="text" name="Vehicle_Weight" class="form-control"
                                   placeholder="Weight (e.g. 5000 lbs)" value="{{ old('Vehicle_Weight') }}">
                        </div>
                    </div>
                </div>
            @endif

            {{-- Freight (Dryvan) --}}
            @if($type === 'Dryvan')
                <div class="row mt-2">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <select name="frieght_class" class="form-control w-100">
                                <option value="">Freight Class</option>
                                <option>50</option><option>55</option><option>60</option>
                                <option>70</option><option>85</option><option>100</option><option>125</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <input type="number" name="Freight_Weight" class="form-control"
                                   placeholder="Freight Weight (lbs)" value="{{ old('Freight_Weight') }}">
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <input type="text" name="Shipment_Preferences" class="form-control"
                                   placeholder="Commodity Details" value="{{ old('Shipment_Preferences') }}">
                        </div>
                    </div>
                </div>
            @endif

            {{-- Shipping mode --}}
            @if(in_array($type, ['Heavy Equipment', 'Dryvan']))
                <div class="row mt-2">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <select name="Shipping_Mode" class="form-control w-100">
                                <option value="">Shipping Mode</option>
                                <option value="FTL (Full Truck Load)">FTL (Full Truck Load)</option>
                                <option value="LTL (Less Than Truck Load)">LTL (Less Than Truck Load)</option>
                            </select>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Submit --}}
            <div class="row mt-4">
                <div class="col-lg-12">
                    <button type="submit"
                            style="width:100%;padding:16px 24px;background:#d4af37;color:#000;font-weight:700;font-size:16px;border:none;border-radius:4px;cursor:pointer;text-transform:uppercase;letter-spacing:1px;">
                        Submit Quote Request
                    </button>
                </div>
            </div>

        </form>
    </div>
</section>

{{-- Freight Class Calculator Modal --}}
@if($type === 'Dryvan')
<div class="modal fade" id="freightClassModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Calculate Freight Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3"><label>Length</label>
                        <input type="number" class="form-control fc-Length" placeholder="Length"></div>
                    <div class="col-md-3"><label>Width</label>
                        <input type="number" class="form-control fc-Width" placeholder="Width"></div>
                    <div class="col-md-3"><label>Height</label>
                        <input type="number" class="form-control fc-Height" placeholder="Height"></div>
                    <div class="col-md-3"><label>Weight (lbs)</label>
                        <input type="number" class="form-control fc-Weight" placeholder="Weight"></div>
                    <div class="col-md-6 mt-3"><label>Unit</label>
                        <select class="form-control fc-Unit">
                            <option value="0">Inches</option>
                            <option value="1">Feet</option>
                        </select>
                    </div>
                    <div class="col-md-6 mt-3"><label>Calculated Freight Class</label>
                        <input type="text" id="FreightClassResult" class="form-control" readonly placeholder="Result">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{--
    All JS runs AFTER the layout's jQuery is loaded (layout loads jQuery at bottom of body).
    We use window.onload / a polling check to ensure jQuery is ready before binding.
    No jQuery UI dependency — pure AJAX + custom dropdown, same as the internal new quote screen.
--}}
<script>
(function waitForJQuery() {
    if (typeof window.jQuery === 'undefined') {
        setTimeout(waitForJQuery, 50);
        return;
    }
    var $ = window.jQuery;

    // ── Destroy nice-select on selects ────────────────────────────────────
    if ($.fn.niceSelect) {
        $('#quote-form select').niceSelect('destroy');
    }

    // ── Generic suggestion dropdown builder ───────────────────────────────
    function buildSuggestions($list, items, onSelect) {
        $list.empty();
        if (!items || items.length === 0) { $list.hide(); return; }
        $.each(items, function (i, item) {
            var $li = $('<li>').append(
                $('<a>').attr('href', 'javascript:void(0)').text(item)
            );
            $li.on('mousedown', function (e) {
                e.preventDefault(); // prevent blur before click
                onSelect(item);
                $list.empty().hide();
            });
            $list.append($li);
        });
        $list.show();
    }

    function hideSuggestions($list) {
        $list.empty().hide();
    }

    // ── Zip / City ────────────────────────────────────────────────────────
    function initZip(inputId, listId) {
        var $input = $('#' + inputId);
        var $list  = $('#' + listId);

        $input.on('keyup', function () {
            var val = $.trim($input.val());
            if (val.length < 3) { hideSuggestions($list); return; }

            $.ajax({
                url: '{{ url("/get_zip") }}',
                type: 'GET',
                dataType: 'json',
                data: { d_zip1: val },
                success: function (res) {
                    // res = ["CITY,STATE,ZIP", ...]  — display with spaces
                    var display = $.map(res || [], function (r) {
                        return r.replace(/,/g, ', ');
                    });
                    buildSuggestions($list, display, function (selected) {
                        // Store without extra spaces so it parses cleanly
                        $input.val(selected);
                    });
                }
            });
        });

        $input.on('blur', function () {
            setTimeout(function () { hideSuggestions($list); }, 150);
        });
    }

    initZip('from_zip', 'from_zip_list');
    initZip('to_zip',   'to_zip_list');

    // ── Vehicle Make ──────────────────────────────────────────────────────
    @if(in_array($type, ['Car', 'Motorcycle', 'ATV/UTV', 'Golf Cart']))
    var $make  = $('#car_make');
    var $model = $('#car_model');
    var $makeList  = $('#make_list');
    var $modelList = $('#model_list');

    $make.on('keyup', function () {
        var val = $.trim($make.val());
        if (val.length < 1) { hideSuggestions($makeList); return; }

        $.ajax({
            url: '{{ url("/getmake") }}',
            type: 'GET',
            dataType: 'json',
            data: { term: val },
            success: function (res) {
                buildSuggestions($makeList, res || [], function (selected) {
                    $make.val(selected);
                    $model.val('').focus();
                });
            }
        });
    });

    $make.on('blur', function () {
        setTimeout(function () { hideSuggestions($makeList); }, 150);
    });

    // ── Vehicle Model ─────────────────────────────────────────────────────
    $model.on('keyup focus', function () {
        var term = $.trim($model.val());
        var make = $.trim($make.val());
        var year = $.trim($('#car_year').val());
        if (!make) return;

        $.ajax({
            url: '{{ url("/getmodel") }}',
            type: 'GET',
            dataType: 'json',
            data: { term: term, make: make, year: year },
            success: function (res) {
                buildSuggestions($modelList, res || [], function (selected) {
                    $model.val(selected);
                });
            }
        });
    });

    $model.on('blur', function () {
        setTimeout(function () { hideSuggestions($modelList); }, 150);
    });
    @endif

    // ── Freight class calculator ──────────────────────────────────────────
    function calcFreight() {
        var unit   = parseFloat($('.fc-Unit').val());
        var length = parseFloat($('.fc-Length').val());
        var width  = parseFloat($('.fc-Width').val());
        var height = parseFloat($('.fc-Height').val());
        var weight = parseFloat($('.fc-Weight').val());
        if ([length, width, height, weight].some(function (v) { return isNaN(v) || v <= 0; })) {
            $('#FreightClassResult').val(''); return;
        }
        var volume  = unit === 0 ? (length * width * height) / 1728 : (length * width * height);
        var density = weight / volume;
        var cls = density < 1 ? 500 : density < 2 ? 400 : density < 3 ? 300 :
                  density < 4 ? 250 : density < 5 ? 200 : density < 6 ? 175 :
                  density < 7 ? 150 : density < 8 ? 125 : density < 9 ? 110 :
                  density < 10.5 ? 100 : density < 12 ? 92.5 : density < 13.5 ? 85 :
                  density < 15 ? 77.5 : density < 22.5 ? 70 : density < 30 ? 65 :
                  density < 35 ? 60 : density < 50 ? 55 : 50;
        $('#FreightClassResult').val(cls);
    }
    $(document).on('input change', '.fc-Length,.fc-Width,.fc-Height,.fc-Weight,.fc-Unit', calcFreight);

})();
</script>

@endsection
