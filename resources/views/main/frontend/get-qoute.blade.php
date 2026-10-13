@extends('layouts.new-master')

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

    /* Dark select boxes to match the form theme */
    #quote-form select.form-control {
        background-color: #111 !important;
        color: #fff !important;
        border: 1px solid #d4af37 !important;
    }
    #quote-form select.form-control option {
        background-color: #111 !important;
        color: #fff !important;
    }
    #quote-form select.form-control:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(212,175,55,0.4);
    }
</style>

{{-- Page Title --}}
<section class="page-title-area pt-100 pb-100"
         style="background-image:url({{ asset('frontend/newtheme-assets/img/banner/7.png') }});">
    <div class="container">
        <div class="page-title-content text-center">
            <h2>Get Quote</h2>
            <ul>
                <li><a href="{{ route('Frontend.index') }}">Home</a></li>
                <li>Get Quote</li>
            </ul>
        </div>
    </div>
</section>

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

        {{-- enctype is required for the boat photo upload (W-07a) --}}
        <form id="quote-form" action="{{ route('Post.Instant.Quote') }}" method="POST" enctype="multipart/form-data">
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
                               placeholder="Origin: City, State, Zip (e.g. Brooklyn, NY, 11234)"
                               required value="{{ old('From_ZipCode') }}">
                        <ul class="suggestion-list" id="from_zip_list"></ul>
                        <span class="zip-error" id="from_zip_error" style="color:#e74c3c;font-size:12px;display:none;"></span>
                    </div>
                </div>
                @if($type === 'RoRo')
                    {{-- RoRo is international: the vehicle is shipped OVERSEAS, so the
                         destination is a country and city, not a US zip. As ShipA1's RoRo
                         form asks — country and city required, zip optional. --}}
                    <div class="col-lg-6">
                        <div class="form-group">
                            <input type="text" name="To_Country" class="form-control"
                                   placeholder="Destination country" required value="{{ old('To_Country') }}">
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <input type="text" name="To_City" class="form-control"
                                   placeholder="Destination city" required value="{{ old('To_City') }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <input type="text" name="To_ZipCode" class="form-control"
                                   placeholder="Destination zip / postcode (optional)" value="{{ old('To_ZipCode') }}">
                        </div>
                    </div>
                @else
                    <div class="col-lg-6">
                        <div class="form-group zip-wrap">
                            <input type="text" id="to_zip" name="To_ZipCode"
                                   class="form-control" autocomplete="off"
                                   placeholder="Destination: City, State, Zip (e.g. Dallas, TX, 75201)"
                                   required value="{{ old('To_ZipCode') }}">
                            <ul class="suggestion-list" id="to_zip_list"></ul>
                            <span class="zip-error" id="to_zip_error" style="color:#e74c3c;font-size:12px;display:none;"></span>
                        </div>
                    </div>
                @endif
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

            {{-- Carrier type & condition — only relevant for Car/Motorcycle/ATV/Golf Cart --}}
            @if(in_array($type, ['Car', 'Motorcycle', 'ATV/UTV', 'Golf Cart']))
            <div class="row mt-2">
                <div class="col-lg-6">
                    <div class="form-group">
                        <select name="Carrier_Type" class="form-control w-100" required>
                            <option value="">Select Carrier Type</option>
                            <option {{ old('Carrier_Type') === 'Open'       ? 'selected' : '' }}>Open</option>
                            <option {{ old('Carrier_Type') === 'Enclosed'   ? 'selected' : '' }}>Enclosed</option>
                            <option {{ old('Carrier_Type') === 'Drive Away' ? 'selected' : '' }}>Drive Away</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <select name="Carrier_Condition" class="form-control w-100" required>
                            <option value="">Select Vehicle Condition</option>
                            <option {{ old('Carrier_Condition') === 'Running'     ? 'selected' : '' }}>Running</option>
                            <option {{ old('Carrier_Condition') === 'Not Running' ? 'selected' : '' }}>Not Running</option>
                        </select>
                    </div>
                </div>
            </div>
            @endif

            {{-- Heavy Equipment --}}
            {{-- ── Heavy equipment, boats and RVs — ShipA1 parity (website point 7) ──────────
                 Fields and options are taken from ShipA1's own forms
                 (heavyEquipment / boat / rv .blade.php). Its five heavy forms —
                 construction, commercial truck, excavator, farm, heavy equipment — are
                 identical, so one section serves all five here. --}}
            @if(in_array($type, ['Heavy Equipment', 'Boat', 'RV']))
                @php
                    $hvTrailers = $type === 'Boat'
                        ? ['REMOVABLE GOOSENECK (RGN)', 'STEP DECK (SD)', 'FLATBED (F)', 'HOT SHOT (HS)', 'LANDOLL (LD)']
                        : ['VAN (V)', 'FLATBED (F)', 'STEP DECK (SD)', 'REMOVABLE GOOSENECK (RGN)', 'CONESTOGA (CS)',
                           'CONTAINER / DRAYAGE (C)', 'TRUCK (T)', 'POWER ONLY (PO)', 'HOT SHOT (HS)', 'LOWBOY (LB)',
                           'ENDUMP (ED)', 'LANDOLL (LD)', 'PARTIAL (PT)'];
                    $hvMethods = ['LOADING DOCK', 'CRANE', 'FORKLIFT', 'DRIVE ROLL'];
                @endphp

                {{-- What it is --}}
                @if($type === 'Boat')
                    <div class="row mt-2">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <select name="Boat_Category" class="form-control w-100">
                                    <option value="">Boat Type</option>
                                    @foreach(['Power Boat', 'Sail Boat', 'Personal Watercraft', 'Boat Parts', 'Motor Boat', 'Others'] as $o)
                                        <option value="{{ $o }}" @selected(old('Boat_Category') === $o)>{{ $o }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @elseif($type === 'RV')
                    <div class="row mt-2">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <select name="RV_Type" class="form-control w-100">
                                    <option value="">RV Type</option>
                                    @foreach(['Class A Motorhome', 'Class B Motorhome', 'Class C Motorhome', 'Travel Trailer',
                                              'Folding Tent Trailer', 'Fifth-Wheel', 'Truck Camper', 'Other'] as $o)
                                        <option value="{{ $o }}" @selected(old('RV_Type') === $o)>{{ $o }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Year / Make / Model — separate, as ShipA1 asks --}}
                <div class="row mt-2">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <input type="text" name="Vehicle_Year" class="form-control" placeholder="Year"
                                   value="{{ old('Vehicle_Year') }}">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <input type="text" name="Vehicle_Make" class="form-control" placeholder="Make"
                                   value="{{ old('Vehicle_Make') }}">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <input type="text" name="Vehicle_Model" class="form-control" placeholder="Model"
                                   value="{{ old('Vehicle_Model') }}">
                        </div>
                    </div>
                </div>

                {{-- Dimensions in feet AND inches, as ShipA1 splits them --}}
                <div class="row mt-2">
                    @foreach(['Length', 'Width', 'Height'] as $dim)
                        <div class="col-lg-4">
                            <label style="font-size:13px;font-weight:600;color:#555;">{{ $dim }}</label>
                            <div class="d-flex gap-2">
                                <input type="number" min="0" name="{{ $dim }}_Ft" class="form-control" placeholder="ft"
                                       value="{{ old($dim . '_Ft') }}">
                                <input type="number" min="0" max="11" name="{{ $dim }}_In" class="form-control" placeholder="in"
                                       value="{{ old($dim . '_In') }}">
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="row mt-2">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <input type="number" min="0" name="Vehicle_Weight" class="form-control" placeholder="Weight (lbs)"
                                   value="{{ old('Vehicle_Weight') }}">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <select name="Trailer_Type" class="form-control w-100">
                                <option value="">Trailer Type</option>
                                @foreach($hvTrailers as $o)
                                    <option value="{{ $o }}" @selected(old('Trailer_Type') === $o)>{{ $o }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <select name="Carrier_Condition" class="form-control w-100">
                                <option value="">Condition</option>
                                <option value="1" @selected(old('Carrier_Condition') === '1')>Running</option>
                                <option value="2" @selected(old('Carrier_Condition') === '2')>Non Running</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-lg-{{ $type === 'RV' ? '4' : '6' }}">
                        <div class="form-group">
                            <select name="Load_Method" class="form-control w-100">
                                <option value="">Load Method</option>
                                @foreach($hvMethods as $o)
                                    <option value="{{ $o }}" @selected(old('Load_Method') === $o)>{{ $o }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-{{ $type === 'RV' ? '4' : '6' }}">
                        <div class="form-group">
                            <select name="Unload_Method" class="form-control w-100">
                                <option value="">Unload Method</option>
                                @foreach($hvMethods as $o)
                                    <option value="{{ $o }}" @selected(old('Unload_Method') === $o)>{{ $o }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if($type === 'RV')
                        <div class="col-lg-4">
                            <div class="form-group">
                                <select name="Load_Type" class="form-control w-100">
                                    <option value="">Load Type</option>
                                    <option value="LTL" @selected(old('Load_Type') === 'LTL')>LTL (Less Than Truck Load)</option>
                                    <option value="FTL" @selected(old('Load_Type') === 'FTL')>FTL (Full Truck Load)</option>
                                </select>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="row mt-2">
                    @if($type === 'Boat')
                        <div class="col-lg-6">
                            <div class="form-group">
                                <select name="Boat_On_Trailer" class="form-control w-100">
                                    <option value="">Is your boat on a trailer?</option>
                                    <option value="Yes" @selected(old('Boat_On_Trailer') === 'Yes')>Yes</option>
                                    <option value="No" @selected(old('Boat_On_Trailer') === 'No')>No</option>
                                </select>
                            </div>
                        </div>
                    @endif
                    <div class="col-lg-{{ $type === 'Boat' ? '6' : '12' }}">
                        <div class="form-group">
                            <select name="Available_At_Auction" id="hvAuction" class="form-control w-100">
                                <option value="">Available at auction?</option>
                                <option value="Yes" @selected(old('Available_At_Auction') === 'Yes')>Yes</option>
                                <option value="No" @selected(old('Available_At_Auction') === 'No')>No</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mt-2" id="hvAuctionLinkRow"
                     style="{{ old('Available_At_Auction') === 'Yes' ? '' : 'display:none;' }}">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <input type="url" name="Auction_Link" class="form-control"
                                   placeholder="Auction listing link (https://…)" value="{{ old('Auction_Link') }}">
                        </div>
                    </div>
                </div>

                {{-- ShipA1's RV form does not ask about modifications; the others do. --}}
                @unless($type === 'RV')
                    <div class="row mt-2">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <select name="Modification" id="hvModified" class="form-control w-100">
                                    <option value="">Modified?</option>
                                    <option value="Yes" @selected(old('Modification') === 'Yes')>Yes</option>
                                    <option value="No" @selected(old('Modification') === 'No')>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-8" id="hvModifyInfoCol"
                             style="{{ old('Modification') === 'Yes' ? '' : 'display:none;' }}">
                            <div class="form-group">
                                <input type="text" name="Modify_Info" class="form-control"
                                       placeholder="What has been modified?" value="{{ old('Modify_Info') }}">
                            </div>
                        </div>
                    </div>
                @endunless

                <div class="row mt-2">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <input type="file" name="Vehicle_Image" class="form-control" accept="image/*">
                            <small style="color:#888;">Photo (optional)</small>
                        </div>
                    </div>
                </div>

                <script>
                    // Only ask for the auction link / modification details when they apply.
                    (function () {
                        function toggle(selId, targetId) {
                            var s = document.getElementById(selId), t = document.getElementById(targetId);
                            if (!s || !t) return;
                            s.addEventListener('change', function () { t.style.display = s.value === 'Yes' ? '' : 'none'; });
                        }
                        toggle('hvAuction', 'hvAuctionLinkRow');
                        toggle('hvModified', 'hvModifyInfoCol');
                    })();
                </script>
            @endif

            {{-- ── Freight: dry van, hazmat, reefer — ShipA1 parity (website point 7) ────────
                 Fields and options come from ShipA1's dryvan / hazmattransport / reefertrucking
                 forms. Every one of them has a column waiting in order_freight, which was built
                 for this and had never been filled from the website.

                 ShipA1's "Category" already says LTL or FTL, so it replaces the separate
                 Shipping Mode select this form used to have. --}}
            @if($type === 'Dryvan')
                @php
                    // dry-van / hazmat / reefer all arrive here; the service tunes the defaults.
                    $frService = request('service');
                    $frEquipDefault = match ($frService) {
                        'reefer' => ['REEFER (RE)'],
                        'hazmat' => ['HAZMAT (hazardous materials)'],
                        default  => ['VAN (V)'],
                    };
                    $frEquip = old('equipment_type', $frEquipDefault);
                    $frSpecs = old('trailer_specification', []);

                    $frCategories = ['LTL USED COMMERCIAL GOODS', 'LTL NEW COMMERCIAL GOODS', 'LTL USED GOODS',
                                     'LTL NEW GOODS', 'FTL FULL TRUCK LOAD'];
                    $frTrailers   = ['RGN', 'Stepdeck', 'Flatbed', 'Hotshot', 'Landoll'];
                    $frUnits      = ['Pallet', 'Crate', 'Box', 'Bag', 'Bale', 'Bundle', 'Can', 'Carton', 'Case',
                                     'Coil', 'Cylinder', 'Drum', 'Loose', 'Pail', 'Reel', 'Roll', 'Pipe', 'Other'];
                    $frSpecList   = ['Air Ride(A)', 'Blanket Wrap (B)', 'B-Train (BT)', 'Chain(CH)', 'Chassis (CS)',
                                     'Conestoga(CO)', 'Curtain(C)', 'Double(2)', 'Extendable (E)', 'E-Track (ET)',
                                     'Hazmat (Z)', 'Hot Shot (HS)', 'Insulated (N)', 'Lift Gate (LG)', 'Load Out (LO)',
                                     'Load Ramp (LR)', 'Moving (MV)', 'Open Top (OT)', 'Oversized (O)',
                                     'Pallet Exchange (X)', 'Side Kit (S)', 'Tarp(T)', 'Team Driver(M)',
                                     'Temp Control (TC)', 'Triple (3)', 'Vented (V)', 'Walking Floor (WF)'];
                    $frEquipList  = ['VAN (V)', 'REEFER (RE)', 'FLATBED (F)', 'STEP DECK (SD)',
                                     'REMOVABLE GOOSENECK (RGN)', 'CONESTOGA (CS)', 'CONTAINER / DRAYAGE (C)',
                                     'TRUCK (T)', 'HAZMAT (hazardous materials)', 'POWER ONLY (PO)', 'HOT SHOT (HS)',
                                     'LOWBOY (LB)', 'ENDUMP (ED)', 'LANDOLL (LD)', 'PARTIAL (PT)',
                                     '20ft container', '40ft container', '48ft container', '53ft container'];
                    $frPickup     = ['Construction / Utility', 'Container Station', 'Exhibition', 'Inside Pickup',
                                     'Lift Gate Service', 'Residential', 'Single Shipment', 'Sorting / Segregation'];
                    $frDeliver    = ['After Business Hours Delivery', 'Construction / Utility', 'Appointment',
                                     'Container Station', 'Exhibition', 'In Bond Freight', 'In Bond TIR Caret',
                                     'Inside - Same Level as Delivery Vehicle', 'Lift Gate Service', 'Residential',
                                     'Mine / Govt / Airport', 'Notification Prior Delivery',
                                     'Delivery appointment required'];
                @endphp

                {{-- What is being shipped --}}
                <div class="row mt-2">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <select name="Freight_Category" class="form-control w-100" required>
                                <option value="">Category (LTL / FTL)</option>
                                @foreach($frCategories as $o)
                                    <option value="{{ $o }}" @selected(old('Freight_Category') === $o)>{{ $o }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <select name="frieght_class" class="form-control w-100">
                                <option value="">Freight Class (or use the calculator)</option>
                                @foreach(['50','55','60','65','70','77.5','85','92.5','100','110','125','150','175','200','250','300','400','500'] as $fc)
                                    <option value="{{ $fc }}" @selected(old('frieght_class') === $fc)>{{ $fc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <input type="text" name="commodity_detail" class="form-control"
                                   placeholder="Commodity detail — what is being shipped?" value="{{ old('commodity_detail') }}">
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <select name="handling_unit" class="form-control w-100">
                                <option value="">Handling Unit</option>
                                @foreach($frUnits as $o)
                                    <option value="{{ $o }}" @selected(old('handling_unit') === $o)>{{ $o }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <input type="number" min="1" name="commodity_unit" class="form-control"
                                   placeholder="Number of units" value="{{ old('commodity_unit') }}">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <select name="Freight_Trailer_Type" class="form-control w-100">
                                <option value="">Trailer Type</option>
                                @foreach($frTrailers as $o)
                                    <option value="{{ $o }}" @selected(old('Freight_Trailer_Type') === $o)>{{ $o }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Equipment & trailer specifications — multiple choice, as on ShipA1 --}}
                <div class="row mt-2">
                    <div class="col-lg-6">
                        <label style="font-size:13px;font-weight:600;color:#555;">Equipment Type</label>
                        <select name="equipment_type[]" multiple class="form-control w-100" size="5">
                            @foreach($frEquipList as $o)
                                <option value="{{ $o }}" @selected(in_array($o, (array) $frEquip, true))>{{ $o }}</option>
                            @endforeach
                        </select>
                        <small style="color:#888;">Hold Ctrl / Cmd to choose more than one.</small>
                    </div>
                    <div class="col-lg-6">
                        <label style="font-size:13px;font-weight:600;color:#555;">Trailer Specification</label>
                        <select name="trailer_specification[]" multiple class="form-control w-100" size="5">
                            @foreach($frSpecList as $o)
                                <option value="{{ $o }}" @selected(in_array($o, (array) $frSpecs, true))>{{ $o }}</option>
                            @endforeach
                        </select>
                        <small style="color:#888;">Optional.</small>
                    </div>
                </div>

                {{-- Dimensions and weight --}}
                <div class="row mt-3">
                    @foreach(['Length', 'Width', 'Height'] as $dim)
                        <div class="col-lg-3">
                            <label style="font-size:13px;font-weight:600;color:#555;">{{ $dim }}</label>
                            <div class="d-flex gap-2">
                                <input type="number" min="0" name="{{ $dim }}_Ft" class="form-control" placeholder="ft"
                                       value="{{ old($dim . '_Ft') }}">
                                <input type="number" min="0" max="11" name="{{ $dim }}_In" class="form-control" placeholder="in"
                                       value="{{ old($dim . '_In') }}">
                            </div>
                        </div>
                    @endforeach
                    <div class="col-lg-3">
                        <label style="font-size:13px;font-weight:600;color:#555;">Weight</label>
                        <input type="number" min="0" name="Freight_Weight" class="form-control" placeholder="lbs"
                               value="{{ old('Freight_Weight') }}">
                    </div>
                </div>

                {{-- Pickup and delivery --}}
                <div class="row mt-3">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <select name="pick_up_services" class="form-control w-100">
                                <option value="">Pickup Services (optional)</option>
                                @foreach($frPickup as $o)
                                    <option value="{{ $o }}" @selected(old('pick_up_services') === $o)>{{ $o }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <select name="deliver_services" class="form-control w-100">
                                <option value="">Delivery Services (optional)</option>
                                @foreach($frDeliver as $o)
                                    <option value="{{ $o }}" @selected(old('deliver_services') === $o)>{{ $o }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-lg-3">
                        <label style="font-size:13px;font-weight:600;color:#555;">Pickup Date</label>
                        <input type="date" name="ex_pickup_date" class="form-control" value="{{ old('ex_pickup_date') }}">
                    </div>
                    <div class="col-lg-3">
                        <label style="font-size:13px;font-weight:600;color:#555;">Pickup Time</label>
                        <input type="time" name="ex_pickup_time" class="form-control" value="{{ old('ex_pickup_time') }}">
                    </div>
                    {{-- ShipA1's reefer form has no delivery date/time; dry van and hazmat do. --}}
                    @unless($frService === 'reefer')
                        <div class="col-lg-3">
                            <label style="font-size:13px;font-weight:600;color:#555;">Delivery Date</label>
                            <input type="date" name="ex_delivery_date" class="form-control" value="{{ old('ex_delivery_date') }}">
                        </div>
                        <div class="col-lg-3">
                            <label style="font-size:13px;font-weight:600;color:#555;">Delivery Time</label>
                            <input type="time" name="ex_delivery_time" class="form-control" value="{{ old('ex_delivery_time') }}">
                        </div>
                    @endunless
                </div>

                {{-- Handling flags --}}
                @php
                    $frFlags = [
                        'hazardous'             => ['Hazardous materials',              $frService === 'hazmat'],
                        'stackable'             => ['Stackable',                         false],
                        'freight_on_trailer'    => ['Freight already on a trailer',      false],
                        'protect_from_freezing' => ['Protect from freezing',             $frService === 'reefer'],
                        'sort_segregate'        => ['Sort / segregate',                  false],
                        'blind_shipment'        => ['Blind shipment',                    false],
                    ];
                @endphp
                <div class="row mt-3">
                    @foreach($frFlags as $flag => [$label, $default])
                        <div class="col-lg-4 col-sm-6">
                            <label class="d-flex align-items-center gap-2" style="cursor:pointer;font-size:14px;">
                                <input type="checkbox" name="{{ $flag }}" value="1"
                                       @checked(old($flag, $default ? '1' : null) === '1')>
                                {{ $label }}
                            </label>
                        </div>
                    @endforeach
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

    // ── Origin / Destination format validation ─────────────────────────────
    // Required format: "City, State, Zip" (3 comma-separated non-empty parts,
    // state = 2 letters, zip = 5 digits)
    var zipPattern = /^[^,]+,\s*[A-Za-z]{2},\s*\d{5}$/;

    function validateZipField(inputId, errorId) {
        var val = $.trim($('#' + inputId).val());
        var $err = $('#' + errorId);
        if (!val) { $err.hide(); return true; } // let HTML5 required handle empty
        if (!zipPattern.test(val)) {
            $err.text('Please use format: City, ST, 12345 — e.g. Brooklyn, NY, 11234').show();
            return false;
        }
        $err.hide();
        return true;
    }

    $('#from_zip').on('blur change', function () { validateZipField('from_zip', 'from_zip_error'); });
    $('#to_zip').on('blur change',   function () { validateZipField('to_zip',   'to_zip_error'); });

    $('#quote-form').on('submit', function (e) {
        var ok = true;
        if (!validateZipField('from_zip', 'from_zip_error')) ok = false;
        if (!validateZipField('to_zip',   'to_zip_error'))   ok = false;
        if (!ok) {
            e.preventDefault();
            $('html,body').animate({ scrollTop: $('.zip-error:visible').first().offset().top - 120 }, 300);
        }
    });

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
