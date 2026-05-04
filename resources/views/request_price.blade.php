@extends('layouts.innerpages')

@section('template_title')
New Quote
@endsection

@include('partials.mainsite_pages.return_function')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    integrity="sha384-..." crossorigin="anonymous">
<style>
    .card-people-list .media-body {
        margin-left: 15px;
    }

    .media-body>a {
        color: #00a0fc !important;

    }

    .slim-card-title {
        text-transform: uppercase;
        font-weight: 700;
        font-size: 13px;
        color: rgb(52 58 64);
        letter-spacing: 1px;
    }

    .oauc {
        padding: 11px;
        width: 100%;
    }

    .dauc {
        padding: 11px;
        width: 100%;
    }

    .row_style {

        padding: 28px;

    }

    .modal-backdrop {
        width: 100% !important;
        height: 100% !important;
    }

    .btn-color:hover {
        color: white !important;
    }

    .btn-color {
        color: white !important;
    }

    .ui-menu {
        z-index: 10000 !important;
        font-size: 15px !important;
        background: rgba(223, 253, 255, 0.93) !important;
        display: block !important;
    }

    input::-webkit-input-placeholder,
    textarea::-webkit-input-placeholder {
        color: #b2b2b2 !important;
    }

    .margin_lft_rth {
        margin: 15px 15px !important;
    }

    .font-boldd {
        font-weight: 900 !important;
        color: #000;
    }

    input {
        color: black !important;
    }

    .border_none {
        border: 0px !important;
    }

    input.select2-search__field {
        height: 45px !important;
    }

    select.form-control:not([size]):not([multiple]) {
        height: 2.375rem !important;
    }

    #overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
        z-index: 2;
    }

    #errorIcon {
        font-size: 17px;
        color: #009eda !important;
        cursor: pointer;
    }

    .popoverContent {
        /*display: none;*/
        position: absolute;
        background-color: #fff;
        border: 1px solid #ccc;
        padding: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        z-index: 3;
        width: 178px;
        left: 232px;
        bottom: 63px;
    }

    .Terminal-error {
        display: inline-flex;
        column-gap: 14px;
    }

    label#selectedOptionLabel2 {
        display: block;
    }

    /* Increase the width of the modal */
    #modalCustomerNature .modal-dialog {
        max-width: 90%;
    }

    /* Style the table */
    #modalCustomerNature table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: collapse;
    }

    #modalCustomerNature th,
    #modalCustomerNature td {
        padding: 12px;
        text-align: center;
    }

    #modalCustomerNature thead th {
        background-color: #3490dc;
        color: white;
    }

    #modalCustomerNature tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    #modalCustomerNature tbody tr:hover {
        background-color: #e9ecef;
    }

    #modalMessageChats .modal-dialog {
        max-width: 90%;
    }

    /* Style the table */
    #modalMessageChats table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: collapse;
    }

    #modalMessageChats th,
    #modalMessageChats td {
        padding: 12px;
        text-align: center;
    }

    #modalMessageChats thead th {
        background-color: #3490dc;
        color: white;
    }

    #modalMessageChats tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    #modalMessageChats tbody tr:hover {
        background-color: #e9ecef;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .table th,
    .table td {
        white-space: normal;
        word-wrap: break-word;
        max-width: 200px;
        /* Adjust the max-width as needed */
    }
</style>

@section('content')
<div class="page-header">


    <!--<div class="page-leftheader">-->
    <!--    <h4 class="page-title mb-0">New Quote </h4>-->
    <!--    <h4 id="orderidplace"></h4>-->
    <!--    <ol class="breadcrumb">-->
    <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layers mr-2 fs-14"></i>Pages</a>-->
    <!--        </li>-->

    <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">New Quote</a></li>-->
    <!--    </ol>-->
    <!--</div>-->
    <div class="text-secondary text-center text-uppercase w-100">
        <h1 class="my-4"><b>New Quote</b></h1>
        <h4 id="orderidplace"></h4>
    </div>

</div>
<!--End Page header-->
<!-- Row -->
<form action="" id="form" name="valid_form" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token()}}">
    <input name="order_history" type="hidden" id="order_history">

    <!-- Row -->
    <div class="row">

        <div class="card margin_lft_rth">
            <div class="grid_new grid_2 new__Quote aaa" style="gap: 0 2rem !important;">
                <div class="" style="padding: 12px 0px 0px 24px !important;">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title font-boldd">ORIGIN LOCATION</div>
                        </div>
                        <div class="card-body">
                            <div class="card-title font-boldd"></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        @if($label[65 - 1]->status == 1)
                                            <div class="Terminal-error">
                                                <label class="form-label parsley-error font-boldd">Name<span
                                                        class="redcolor">*</span></label>
                                                <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[65 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[65 - 1]->display }}</div>
                                            </div>
                                        @else
                                            <label class="form-label parsley-error font-boldd">Name<span
                                                    class="redcolor">*</span></label>
                                        @endif
                                        <input type="text" name="oname" id="oname" required
                                            onkeypress="$(this).css('border-color', 'rgb(92 166 242)');"
                                            class="form-control this_save " autocomplete="off" placeholder="Name">
                                    </div>
                                </div>
                                <div class="col-sm-12 add_phone">
                                    <div class='row'>&nbsp;&nbsp;&nbsp;
                                        @if($label[28 - 1]->status == 1)
                                            <div class="Terminal-error">
                                                <label class="form-label font-boldd">Phone Number<span
                                                        class="redcolor">*</span></label>
                                                <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[28 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[28 - 1]->display }}</div>
                                            </div>
                                        @else
                                            <label class="form-label font-boldd">Phone Number<span
                                                    class="redcolor">*</span></label>
                                        @endif
                                        <div class="form-group col-11 ">
                                            <input type="text" name="ophone[]" id="ophone" autocomplete="off"
                                                class="form-control this_save  ophone ophone_new" required
                                                placeholder="Number" value="{{ $phoneno }}">
                                        </div>
                                        <div class='form-group col-1' style="padding-top: 7px;">
                                            <i id='add_btn' class="si si si-plus add_phone_btn"></i>
                                        </div>
                                        <input type="hidden" value="{{ $phoneno }}" name="ophone2[]" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        @if($label[71 - 1]->status == 1)
                                            <div class="Terminal-error">
                                                <label class="form-label font-boldd">Address <span
                                                        class="redcolor">*</span></label>
                                                <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[71 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[71 - 1]->display }}</div>
                                            </div>
                                        @else
                                            <label class="form-label font-boldd">Address <span
                                                    class="redcolor">*</span></label>
                                        @endif
                                        <input type="text" name="oaddress" id="oaddress" autocomplete="off"
                                            class="form-control this_save "
                                            onkeypress="$(this).css('border-color', 'rgb(92 166 242)');"
                                            placeholder="Home Address" required>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12">
                                    <div class="form-group mb-0">
                                        @if($label[126 - 1]->status == 1)
                                            <div class="Terminal-error">
                                                <label class="form-label parsley-error font-boldd">Zip Code<span
                                                        class="redcolor">*</span></label>
                                                <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[126 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[126 - 1]->display }}</div>
                                            </div>
                                        @else
                                            <label class="form-label parsley-error font-boldd">Zip Code<span
                                                    class="redcolor">*</span></label>
                                        @endif
                                        <input type="text" id="o_zip1" class="form-control this_save "
                                            autocomplete="off" maxlength="100" name="o_zip1"
                                            onkeypress="$(this).css('border-color', 'rgb(92 166 242)');"
                                            placeholder="ZIP CODE" required />
                                    </div>
                                    <ul class="nav flex-column border scrollul"
                                        style="max-height:200px;overflow:scroll;display:none;">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="" style="padding: 12px 24px 0px 1px !important;">
                    <div class="card" style=" height: 96%; ">
                        <div class="card-header">
                            <div class="card-title">DESTINATION LOCATION</div>
                        </div>
                        <div class="card-body">
                            <div class="card-title font-boldd"></div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        @if($label[78 - 1]->status == 1)
                                            <div class="Terminal-error">
                                                <label class="form-label font-boldd">Address <span
                                                        class="redcolor">*</span></label>
                                                <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[78 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[78 - 1]->display }}</div>
                                            </div>
                                        @else
                                            <label class="form-label font-boldd">Address <span
                                                    class="redcolor">*</span></label>
                                        @endif
                                        <input type="text" name='daddress' id='daddress' autocomplete="off"
                                            class="form-control this_save "
                                            onkeypress="$(this).css('border-color', 'rgb(92 166 242)');"
                                            placeholder="Home Address" required>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12">
                                    <div class="form-group mb-0">
                                        @if($label[127 - 1]->status == 1)
                                            <div class="Terminal-error">
                                                <label class="form-label parsley-error font-boldd">Zip Code
                                                    <span class="redcolor">*</span>
                                                </label>
                                                <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[127 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[127 - 1]->display }}</div>
                                            </div>
                                        @else
                                            <label class="form-label parsley-error font-boldd">Zip Code
                                                <span class="redcolor">*</span>
                                            </label>
                                        @endif
                                        <input type="text" id="d_zip1" class="form-control this_save "
                                            autocomplete="off"
                                            onkeypress="$(this).css('border-color', 'rgb(92 166 242)');" maxlength="100"
                                            name="d_zip" placeholder="ZIP CODE" required />
                                    </div>
                                    <ul class="nav flex-column border scrollul"
                                        style="max-height:200px;overflow:scroll;display:none;">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">VEHICLE INFORMATION</div>
                </div>
                <div class="card-body">
                    <div class=' flex_ gap_new flex_space vichle__Information'>
                        <div class="vichle__Information--box">
                            <div>
                                <label class="rdiobox">
                                    <!-- <input name="vehicle${vehicle_count}" id="vehicle${vehicle_count}"
                                                onclick="vehicle_append(0)" type="radio"
                                                checked value="0" data-parsley-multiple="vehicle${vehicle_count}">
                                                -->
                                    <input type="hidden" id="count0" name="count[]" value="1">
                                    <input class="this_save type" name="vehicle0" id="vehicle0"
                                        onclick="vehicle_append(0)" type="radio" autocomplete="off" checked value="1"
                                        data-parsley-multiple="vehicle0">

                                    <input name="vehicle_v[]" id="vehicle_v0" type="hidden" value="make">


                                    <span>Year, Make, and Model </span>
                                </label>
                            </div>
                        </div>
                        <div class="vichle__Information--box">
                            <div>
                                <label class="rdiobox">
                                    <!-- <input name="vehicle${vehicle_count}" id="vin${vehicle_count}" type="radio"
                                                onclick="vin_append(0)"
                                                value="1" data-parsley-multiple="vehicle${vehicle_count}">
                                                -->
                                    <input class="this_save type" name="vehicle0" id="vin0" type="radio"
                                        onclick="vin_append(0)" value="2" data-parsley-multiple="vehicle0">

                                    <input name="vehicle_v[]" disabled id="vehicle_v_vin0" type="hidden" value="vin">
                                    <span>Vin Number</span>
                                </label>
                            </div>
                        </div>
                        <div class="vichle__Information--box btn-list">

                            <button type="button" class="btn btn-primary add_vehicle_btn">
                                <i class="fe fe-plus mr-2" id="add-more"></i>Add
                                Vehicle
                            </button>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 vin_toggle0">

                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                @if($label[129 - 1]->status == 1)
                                    <div class="Terminal-error">
                                        <label class="form-label font-boldd">Year<span class="redcolor">*</span></label>
                                        <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                            style="cursor: pointer;"></i>
                                    </div>

                                    <div class="popoverContent" style="display: none;">
                                        <div class="popover-title">{{ $label[129 - 1]->name }}</div>
                                        <div class="popover-content">{{ $label[129 - 1]->display }}</div>
                                    </div>
                                @else
                                    <label class="form-label font-boldd">Year<span class="redcolor">*</span></label>
                                @endif
                                <input type="text" class="form-control this_save vyear" id='year0' autocomplete="off"
                                    name='vyear[]' required placeholder="Enter Year"
                                    onkeypress="$(this).css('border-color', 'rgb(92 166 242)');">

                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                @if($label[132 - 1]->status == 1)
                                    <div class="Terminal-error">
                                        <label class="form-label font-boldd">Make<span class="redcolor">*</span></label>
                                        <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                            style="cursor: pointer;"></i>
                                    </div>

                                    <div class="popoverContent" style="display: none;">
                                        <div class="popover-title">{{ $label[132 - 1]->name }}</div>
                                        <div class="popover-content">{{ $label[132 - 1]->display }}</div>
                                    </div>
                                @else
                                    <label class="form-label font-boldd">Make<span class="redcolor">*</span></label>
                                @endif
                                <input type="text" class="form-control this_save  makeOpt0 vmake" autocomplete="off"
                                    onkeyup="getmake()" id='makeOpt0' name='vmake[]' required
                                    onkeypress="$(this).css('border-color', 'rgb(92 166 242)');"
                                    placeholder="Enter Make">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">

                            <div class="googleimage" onclick="googl(0)" id="googleimage0"
                                style="position: absolute; right: 3%;top:-6px;display:none"><a
                                    href="javascript:void(0);"><img width="50"
                                        src="{{url('')}}/assets/images/png/google.png"
                                        style="border: 1px solid #5da6f2;border-radius: 5px;"></a>
                            </div>

                            <div class="form-group">
                                @if($label[131 - 1]->status == 1)
                                    <div class="Terminal-error">
                                        <label class="form-label parsley-error font-boldd">Model<span
                                                class="redcolor">*</span></label>
                                        <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                            style="cursor: pointer;"></i>
                                    </div>

                                    <div class="popoverContent" style="display: none;">
                                        <div class="popover-title">{{ $label[131 - 1]->name }}</div>
                                        <div class="popover-content">{{ $label[131 - 1]->display }}</div>
                                    </div>
                                @else
                                    <label class="form-label parsley-error font-boldd">Model<span
                                            class="redcolor">*</span></label>
                                @endif
                                <input class="form-control this_save  model0 vmodel" id='model0' autocomplete="off"
                                    onkeyup="getmodel(0)" name='vmodel[]' required
                                    onkeypress="$(this).css('border-color', 'rgb(92 166 242)');"
                                    placeholder="Enter Model" type="text">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                @if($label[130 - 1]->status == 1)
                                    <div class="Terminal-error">
                                        <label class="form-label parsley-error font-boldd">Vehicle Type</label>
                                        <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                            style="cursor: pointer;"></i>
                                    </div>

                                    <div class="popoverContent" style="display: none;">
                                        <div class="popover-title">{{ $label[130 - 1]->name }}</div>
                                        <div class="popover-content">{{ $label[130 - 1]->display }}</div>
                                    </div>
                                @else
                                    <label class="form-label parsley-error font-boldd">Vehicle Type</label>
                                @endif
                                <select id="vehType0" name="vehType[]"
                                    class="form-control this_save select2 vehicle-type">
                                    <option selected="" value="">Select Type</option>
                                    <option value="Car">Car</option>
                                    <option disabled="">————————————</option>

                                    <option value="motorcycle">Motorcycle</option>
                                    <option value="3_wheel_sidecar">3 Wheel Sidecar</option>
                                    <option value="3_wheel_motorcycle">3 Wheel Motorcycle</option>
                                    <option value="atv">ATV</option>

                                    <option disabled="">————————————</option>

                                    <option value="SUV">SUV</option>
                                    <option value="Mid SUV">Mid SUV</option>
                                    <option value="Large SUV">Large SUV</option>

                                    <option disabled="">————————————</option>

                                    <option value="Van">Van</option>
                                    <option value="Mini Van">Mini Van</option>
                                    <option value="Cargo Van">Cargo Van</option>
                                    <option value="Passenger Van">Passenger Van</option>

                                    <option disabled="">————————————</option>

                                    <option value="Pickup">Pickup</option>
                                    <option value="Pickup Dually">Pickup Dually</option>
                                    <option value="Box Truck Dually">Box Truck Dually</option>

                                    <option disabled="">————————————</option>

                                    <option value="other_vehicle">Other Vehicle</option>
                                    <option value="other_motorcycle">Other Motorcycle</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                @if($label[133 - 1]->status == 1)
                                    <div class="Terminal-error">
                                        <label class="form-label font-boldd">Vehicle Condition</label>
                                        <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                            style="cursor: pointer;"></i>
                                    </div>

                                    <div class="popoverContent" style="display: none;">
                                        <div class="popover-title">{{ $label[133 - 1]->name }}</div>
                                        <div class="popover-content">{{ $label[133 - 1]->display }}</div>
                                    </div>
                                @else
                                    <label class="form-label font-boldd">Vehicle Condition</label>
                                @endif
                                <select id="condition0" name="condition[]"
                                    onchange="condition_change(this.value,$(this).attr('id'))"
                                    class="form-control this_save select2 vehicle-condition">
                                    <option selected="" value="">Select</option>
                                    <option value="1">Running</option>
                                    <option value="2">Not Running</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                @if($label[134 - 1]->status == 1)
                                    <div class="Terminal-error">
                                        <label class="form-label font-boldd">Trailer Type</label>
                                        <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                            style="cursor: pointer;"></i>
                                    </div>

                                    <div class="popoverContent" style="display: none;">
                                        <div class="popover-title">{{ $label[134 - 1]->name }}</div>
                                        <div class="popover-content">{{ $label[134 - 1]->display }}</div>
                                    </div>
                                @else
                                    <label class="form-label font-boldd">Trailer Type</label>
                                @endif
                                <select id="trailter_type0" name="trailter_type[]"
                                    class="form-control this_save select2 trailer-type">
                                    <option selected="" value="">Select</option>
                                    <option value="1">Open</option>
                                    <option value="2">Enclosed</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-12">
                            <div id="vehicle_condition0" style="display: flex; width: 100%;">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6">

                            &nbsp;
                            <div class="form-group">
                                <label class="ckbox">
                                    <input class="this_save" type="checkbox" name="portTitle0" id="needTitle0"
                                        onclick="goto_port(0)">
                                    <span>&nbsp;Need Title?</span>
                                    <input type="hidden" name="portTitlehidden[]" id="portTitlehidden0" value="false">
                                </label>
                            </div>
                        </div>
                        <div class=" col-12 add_vehicle_information   ">


                        </div>

                        <div class="col-md-12 ">
                            <div class="form-group">
                                @if($label[159 - 1]->status == 1)
                                    <div class="Terminal-error">
                                        <label class="form-label font-boldd">Additional Vehicle Information</label>
                                        <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                            style="cursor: pointer;"></i>
                                    </div>
                                    <div class="popoverContent" style="display: none;">
                                        <div class="popover-title">{{ $label[159 - 1]->name }}</div>
                                        <div class="popover-content">{{ $label[159 - 1]->display }}</div>
                                    </div>
                                @else
                                    <label class="form-label font-boldd">Additional Vehicle Information</label>
                                @endif
                                <textarea type="text" name='addition_info' id='addition_info0'
                                    class="form-control this_save " placeholder=""></textarea>
                            </div>
                        </div>


                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="ckbox">
                                    <input class="this_save" type="checkbox" name="modification" id="modification"
                                        value="yes" data-parsley-multiple="modification">
                                    <span>&nbsp;Modification</span>
                                </label>
                            </div>

                            <div class="input-form div-modify_info" style="display: none;">
                                <label class="d-block"> Modification Information:</label>
                                <input class="form-control this_save" type="text" id="c" name="modify_info"
                                    placeholder="Enter Modification Information" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="ckbox">
                                    <input class="this_save" type="checkbox" name="available_at_auction"
                                        id="available_at_auction" value="yes"
                                        data-parsley-multiple="available_at_auction">
                                    <span>&nbsp;Available at Auction?</span>
                                </label>
                            </div>

                            <div class="input-form div-link" style="display: none;">
                                <label class="d-block"> Enter Link:</label>
                                <input class="form-control this_save" type="url" id="link" name="link"
                                    placeholder="Enter Link" />
                            </div>
                        </div>
                    </div>
                    &nbsp;

                </div>
                <div class="card-footer text-left">
                </div>
            </div>
        </div>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title ">TIME FRAME</div>
                </div>
                <div class="card-body">
                    &nbsp;
                    <div class="row ">
                        <div class="col-lg-6" id="whenPickUpDate"></div>
                        <div class="col-lg-6" id="whenDeliveryDate"></div>
                    </div>

                    <div class="card-footer text-left">
                        <!-- <a href="#" class="btn btn-danger">Cancle</a> -->
                    </div>
                    <div class="flex_ flex_center gap_new priceReq">
                        <input type="hidden" class="orderID">
                        <a href="javascript:void(0)" class="btn btn-primary mg-r-10 requestPrice">Request Price</a>
                    </div>
                    <div class="row reqPrice"></div>
                </div>
            </div>
        </div>
        <input type="hidden" name="orderid" id="orderid_find" value="" />
</form>

<div id="alreadyCreditCard" class="modal fade" style="padding-right: 15px;">
    <div class="modal-dialog modal-dialog-centered" role="document" style="width: 80%;">
        <div class="modal-content bd-0 tx-14">
            <div class="modal-header pd-y-20 pd-x-25">
                <h6 class="tx-30 mg-b-0 tx-uppercase tx-inverse tx-bold">ALREADY HAVE CARD</h6>
            </div>
            <div class="modal-body pd-25 pl-20 pr-20">
                <div class="card card-people-list mg-y-20" id="creditCardInfo">
                    <div class="slim-card-title mb-3">Credit Cards</div>
                    <div class="row" id="creditModal">
                        <div class="col-lg-12">
                            <div class="media-list">
                                <div class="col-lg-12">
                                    <div class="media-list" style="overflow: scroll">
                                        <table class="table table-responsive">
                                            <div id="creditCardTable">

                                            </div>
                                        </table>
                                    </div>
                                </div>
                                {{-- <div class="media">
                                    <img src="https://admin.shipa1.com/img/visa.png"
                                        style=" margin: 11px; padding: 0px; " alt="">
                                    <div class="media-body">
                                        <a href="javascript:void(0)">test test</a>
                                        <p>XXXXXXXXXXXX9052<br>
                                            Order ID: <a href="/history/?id=N3dsTUVnTUtFeUNSdHNVeFR0NjJ0QT09"
                                                style="display: unset;color: rgb(0 160 252);" target="_blank">40596</a>
                                            <br>
                                            Email: <a href="/history/?id=N3dsTUVnTUtFeUNSdHNVeFR0NjJ0QT09"
                                                style="display: unset;color: rgb(0 160 252);"
                                                target="_blank">test@gmail.com</a>
                                        </p>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- modal-dialog -->
</div>

<!-- Modal -->
<div class="modal" id="modalCustomerNature">
    <div class="modal-dialog modal-dialog-centered text-center" role="document">
        <div class="modal-content tx-size-sm">
            <div class="modal-body text-center p-4">
                <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="text-danger" id="not_success"></h4>
                <div class="card-title font-weight-bold">
                    Nature of Customer:
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Order ID</th>
                                <th class="text-wrap">Nature of Customer</th>
                                <th>Updated By</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody id="customerTable">
                            <!-- Your data rows here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal" id="modalMessageChats">
    <div class="modal-dialog modal-dialog-centered text-center" role="document">
        <div class="modal-content tx-size-sm">
            <div class="modal-body text-center p-4">
                <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="text-danger" id="not_success"></h4>
                <div class="card-title font-weight-bold">
                    All Message Chats:
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Order ID</th>
                                <th>Updated By</th>
                                <th class="text-wrap">Message</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody id="messageChatsTable">
                            <!-- Your data rows here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal" id="modaldemo4">
    <div class="modal-dialog modal-dialog-centered text-center " role="document">
        <div class="modal-content tx-size-sm">
            <div class="modal-body text-center p-4">
                <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                        aria-hidden="true">&times;</span></button>
                <i class="fe fe-check-circle fs-100 text-success lh-1 mb-5 d-inline-block"></i>
                <h4 class="text-success tx-semibold" id="success"></h4>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modaldemo5">
    <div class="modal-dialog modal-dialog-centered text-center" role="document">
        <div class="modal-content tx-size-sm">
            <div class="modal-body text-center p-4">
                <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                        aria-hidden="true">&times;</span></button>
                <i class="fe fe-x-circle fs-100 text-danger lh-1 mb-5 d-inline-block"></i>
                <h4 class="text-danger" id="not_success"></h4>
            </div>
        </div>
    </div>
</div>


<div class="modal fade " role="dialog" id="modaldemo8" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered text-center" role="document">
        <div class="modal__new modal-content modal-content-demo "
            style=" position: fixed;width: 80vw;transform: translateX(-50%);left: 50%;top: -10px; ">

            <div class="modal-header heading_style">
                <h1 class="heading_style heading_font ">ORDER ON PHONE</h1>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
                        <h5 class=" modal_subtitle">Customer Number</h5>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
                        <h5 class=" modal_subtitle">Unknown Number</h5>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
                        <div class="form-group">



                            <input style=" width: 15px; " name="mainPh" id="yes" type="radio" value="1">
                            <label for="yes"
                                style=" position: relative; top: 0px; font-size: 20px; cursor:pointer; ">Yes</label>

                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
                        <div class="form-group">





                            <input style=" width: 15px; " name="mainPh" id="no" type="radio" value="2">
                            <label for="no"
                                style=" position: relative; top: 0px; font-size: 20px; cursor:pointer; ">No</label>

                        </div>
                    </div>
                </div>
                <form name="createnew" id="createnew_form" action="" method="post">
                    @csrf
                    <div class="row number_no" style='display:none'>
                        <div class="col-lg-12 ">
                            <input type="text" class="form-control " name="custName" id="custName"
                                onfocus="$(this).attr('autocomplete', 'off');" placeholder="Enter Customer Name"
                                onkeypress="showcreatenew();">
                        </div>
                        <div class="col-lg-12 mt-1">
                            <textarea name="addInfo" class="form-control " id="addInfo" cols="5" rows="3"
                                onfocus="$(this).attr('autocomplete', 'off');"
                                placeholder="Enter Additional Info"></textarea>
                        </div>
                    </div>


                    <input type="hidden" name="car_type" value="1" />
                    <div class="row number_yes" style='display:none'>
                        <div class="col-lg-12">
                            <input type="text" class="form-control  ophonev" onkeyup="phone_check(this.value)"
                                onfocus="$(this).attr('autocomplete', 'off');" name="mainPhNum" id="PhNum"
                                placeholder="Enter Phone Number">
                        </div>
                    </div>
                    <br>
                    <a href="#" class="show_hide" data-content="toggle-text">View Previous Order Ids</a>
                    <div id="last_5">

                    </div>

                    <div class="modal-footer">
                        <label id="show_total"></label>
                        <button class="btn btn-indigo" id="update_previous" style="display: none;" type="button">
                            <a href="javascript:;" class="btn-color"
                                onclick="this.href='/searchData?search='+ document.getElementById('PhNum').value">
                                Update Previous
                            </a>

                            <!--
                                <a href="javascript:;"
                                   onclick="this.href='/carrier_add/'+ document.getElementById('order_id1').value"
                                   type="button" target="_blank"
                                   class="btn btn-info btn-sm">UPDATE CARRIER</a>
                                -->


                        </button>
                        <button class="btn btn-indigo" style="display:none" id="create_new" onclick="save_phon()"
                            type="button"> Create New
                        </button>
                        <div class="unknownno_class">

                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="reportmodal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content tx-size-sm">
            <div class="modal-header pd-x-20">
                <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Order History</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body pd-20">
                <form method="post" action="{{route('call_history_post')}}" id="callhistoryform">
                    @csrf
                    <div class="card-title font-weight-bold">New HISTORY/CHANGE
                        STATUS:
                    </div>
                    <div class="row">
                        <input type="hidden" class="form-control" name="order_id1" id='order_id1' placeholder=""
                            value="" readonly>

                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label class="form-label">CHANGE STATUS</label>
                                <select name="pstatus" id='pstatus' required class="form-control">
                                    <option value="" selected disabled>Select</option>
                                    <option value="1">Interested</option>
                                    <option value="2">FollowMore</option>
                                    <option value="3">AskingLow</option>
                                    <option value="4">NotInterested</option>
                                    <option value="5">NoResponse</option>
                                    <option value="6">TimeQuote</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                @if($label[157 - 1]->status == 1)
                                    <div class="Terminal-error">
                                        <label class="form-label">EXPECTED DATE</label>
                                        <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                            style="cursor: pointer;"></i>
                                    </div>

                                    <div class="popoverContent" style="display: none;">
                                        <div class="popover-title">{{ $label[157 - 1]->name }}</div>
                                        <div class="popover-content">{{ $label[157 - 1]->display }}</div>
                                    </div>
                                @else
                                    <label class="form-label">EXPECTED DATE</label>
                                @endif
                                <input type="date" required name="expected_date" id='expected_date'
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-12">
                            <div class="form-group">
                                @if($label[158 - 1]->status == 1)
                                    <div class="Terminal-error">
                                        <label class="form-label">HISTORY</label>
                                        <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                            style="cursor: pointer;"></i>
                                    </div>

                                    <div class="popoverContent" style="display: none;">
                                        <div class="popover-title">{{ $label[158 - 1]->name }}</div>
                                        <div class="popover-content">{{ $label[158 - 1]->display }}</div>
                                    </div>
                                @else
                                    <label class="form-label">HISTORY</label>
                                @endif
                                <textarea required name="history_update" id='history_update'
                                    class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-12" id="ask_low">

                        </div>

                    </div>
                    <button type="submit" id="savceChanges" class="btn btn-primary">Save changes</button>
                </form>
            </div><!-- modal-body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                </button>
            </div>
        </div>
    </div>
</div>

@php

    $digits = \App\PhoneDigit::first();

    $hide_digits = $digits->hide_digits;
    $left_right_status = $digits->left_right_status;

@endphp

@endsection

@section('extraScript')
<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.2/js/toastr.min.js"></script>
<script>

    $("#viewCentral").click(function () {
        var user_id = document.cookie.match(/PHPSESSID=[^;]+/);
        var ozip = $("#o_zip1").val();
        var dzip = $("#d_zip1").val();
        var vehType = $("#vehType0").val();
        var numVehicles = $("#addVeh").val();
        var veh = '';
        var condition = $("#condition0").val();
        var trailter = $("#trailter_type0").val();
        var type = $("#vehChkType0").val();
        var get_ses = $("#get_ses").val();
        if (condition == 'operable') {
            condition = '0';
        } else if (condition == 'non-running') {
            condition = '1';
        }
        if (trailter == 'open') {
            trailter = '0';
        } else if (trailter == 'enclosed') {
            trailter = '1';
        }
        if (ozip == '' || dzip == '') {
            alert('Please Enter Origin & Dest City or Zip');
            // toastr.error("Please Enter Origin & Dest City or Zip", "Error");
        } else {
            ozip = ozip.split(", ");
            dzip = dzip.split(", ");
            if (vehType || type) {
                if (vehType == "Car") {
                    veh = "Car";
                } else if (vehType == "motorcycle" || vehType == "3_wheel_sidecar" || vehType == "3_wheel_motorcycle") {
                    veh = "Motorcycle";
                } else if (vehType == "atv") {
                    veh = "ATV";
                } else if (vehType == "SUV" || vehType == "Mid SUV" || vehType == "Large SUV") {
                    veh = "SUV";
                } else if (vehType == "Van" || vehType == "Mini Van" || vehType == "Cargo Van" || vehType == "Passenger Van") {
                    veh = "Van";
                } else if (vehType == "Pickup" || vehType == "Pickup Dually" || vehType == "Box Truck Dually") {
                    veh = "Pickup";
                } else if (type == "other_vehicle" || type == "other_motorcycle") {
                    veh = "Other";
                }
            } else {
                veh = '%2b';
            }
            var url = `https://washington.shawntransport.com/get_web_price?num_vehicles=${numVehicles}&ozip=${ozip[2]}&dzip=${dzip[2]}&enclosed=${trailter}&inop=${condition}&vehicle_types=${veh}&miles=0&${get_ses}`;
            window.open(url, 'Central Dispatch Pricing', 'height=500,width=900,left=180,top=250,toolbar=No,location=No,scrollbars=Yes,status=No,resizable=Yes,fullscreen=No,directories=No,menubar=No,copyhistory=No');
            window.submit();
        }
        var s = 'PHPSESSID=j1i07pn8bt8ff6caf6r6fca8t2';
        var m = 'PHPSESSID=ptofpmul885flvu4u3lo0q6413';
        if (user_id = s) {
            console.log('Shahzaib');
        } else if (user_id = m) {
            console.log('Mansoor');
        }
    });

    $("#shipa1Rates").click(function () {
        var ozip = $("#o_zip1").val();
        var dzip = $("#d_zip1").val();
        var id = $('#orderid').val();;
        if (ozip == '' || dzip == '') {
            alert('Please Enter Origin & Dest City or Zip');
            // toastr.error("Please Enter Origin & Dest City or Zip", "Error");
        } else {
            ozip = ozip.split(", ");
            dzip = dzip.split(", ");

            var url = `/rates_shipa1?ocity=${btoa(ozip[0])}&dcity=${btoa(dzip[0])}&id=${id}`;
            window.open(url, 'Ship A1 Rates', 'height=400,width=600,left=350,top=250,toolbar=No,location=No,scrollbars=Yes,status=No,resizable=Yes,fullscreen=No,directories=No,menubar=No,copyhistory=No');
        }
    });

    $("#previousRecord").click(function () {
        var ozip = $("#o_zip1").val();
        var dzip = $("#d_zip1").val();
        if (ozip == '' || dzip == '') {
            alert('Please Enter Origin & Dest City or Zip');
            // toastr.error("Please Enter Origin & Dest City or Zip", "Error");
        } else {
            ozip = ozip.split(", ");
            dzip = dzip.split(", ");
            var url = `/previous-orders?ocity=${btoa(ozip[0])}&dcity=${btoa(dzip[0])}&id={{ \Request::segment(2) }}`;
            window.open(url, 'Previous Orders', 'height=600,width=1000,left=350,toolbar=No,location=No,scrollbars=Yes,status=No,resizable=Yes,fullscreen=No,directories=No,menubar=No,copyhistory=No');
        }
    });

    $("#previousBookPrice").click(function () {
        var ozip = $("#o_zip1").val();
        var dzip = $("#d_zip1").val();
        if (ozip == '' || dzip == '') {
            alert('Please Enter Origin & Dest City or Zip');
            // toastr.error("Please Enter Origin & Dest City or Zip", "Error");
        } else {
            var url = `/previous-orders2?ocity=${btoa(ozip)}&dcity=${btoa(dzip)}`;
            window.open(url, 'Previous Orders', 'height=700,width=1200,left=150,top=50,toolbar=No,location=No,scrollbars=Yes,status=No,resizable=Yes,fullscreen=No,directories=No,menubar=No,copyhistory=No');
        }
    });



    function condition_change(val, counter_id) {
        var c_id = counter_id.replace('condition', '');
        if (val === '2') {
            $(`#vehicle_condition${c_id}`).html(`
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label class="form-label">PICK UP</label>
                            <select name="v_con_p[]" id='v_con_p${c_id}' required
                                    class="form-control select2 this_save">
                                <option value="1">Folk Lift</option>
                                <option value="2">Man Help</option>
                                <option value="3">Toe</option>
                                <option value="4">Jump Box</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label class="form-label">DELIVERY</label>
                            <select name="v_con_d[]" id="v_con_d${c_id}" required
                                    class="form-control select2 this_save">
                                <option value="1">Folk Lift</option>
                                <option value="2">Man Help</option>
                                <option value="3">Toe</option>
                                <option value="4">Jump Box</option>
                            </select>
                        </div>
                    </div>
                `);
        } else {
            $(`#vehicle_condition${c_id}`).html('');
        }
    }

    $('#zipCityDest').click(function () {
        var orderID = $(this).siblings('.orderID').val();
        var ozip = $("#o_zip1").val();
        var dzip = $("#d_zip1").val();
        var vehicleType = $(".vehicle-type option:selected");
        var vyear = $(".vyear");
        var vmake = $(".vmake");
        var vmodel = $(".vmodel");

        // .children('option:selected').val()
        var arr = [];
        $.each(vehicleType, function () {
            arr.push(this.value);
        });
        var arr2 = [];
        var vehicle = '';


        if (ozip == '' || dzip == '' || vyear.val() == '' || vmake.val() == '' || vmodel.val() == '') {
            // alert("Please Enter Origin & Dest City or Zip & vechile detail");
            $(`
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="position: fixed;top: 5%;width: 75%;left: 12%;z-index: 9999;">
                  Please Enter Origin & Dest City or Zip & vechile detail
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
            `).insertBefore(".page");

        } else {

            if (vyear.length > 1 && vmodel.length > 1 && vmake.length > 1) {
                $.each(vyear, function (index) {
                    vehicle = this.value + ' ' + vmodel[index].value + ' ' + vmake[index].value;
                    arr2.push(vehicle);
                });
            }
            else {
                vehicle = vyear.val() + ' ' + vmodel.val() + ' ' + vmake.val();
                arr2.push(vehicle);
            }

            var odata = ozip.split(",");
            var ddata = dzip.split(",");

            var ozip1 = odata[2];
            var ocity1 = odata[0];
            var dzip1 = ddata[2];
            var dcity1 = ddata[0];

            var url = `/records_city_zip_destination?ocity=${ocity1}&dcity=${dcity1}&ozip=${ozip1}&dzip=${dzip1}&vehicle=${arr}&vehicleName=${arr2}&orderID${orderID}`;
            window.open(url, 'View Previous Prices', 'height=800,width=1000,left=150,top=100,toolbar=No,location=No,scrollbars=Yes,status=No,resizable=Yes,fullscreen=No,directories=No,menubar=No,copyhistory=No');

        }

    });


    $(document).ready(function () {
        $("#last_5").hide();
        $(".show_hide").hide();
        $(".show_hide").on("click", function () {
            var txt = $("#last_5").is(':visible') ? 'View Previous Order Ids' : 'Hide Previous Order Ids';
            $(".show_hide").text(txt);
            $(this).next('#last_5').slideToggle(200);
        });
    });
    setTimeout(function () {
        document.body.style.zoom = "95%";
    }, 500);

    $("body").delegate(".ui-menu", "click", function () {
        $(".ui-menu").html('');
    });


    //            $(document).ready(function(){
    //                $('input').attr('autocomplete', 'off');
    //                $('#o_zip1').attr('autocomplete', 'on');
    //                $('#daddress2').attr('autocomplete', 'on');
    //            });

    $(document).ready(function () {
        //            $("input").autocomplete({
        //                disabled: true
        //            });
        $('input').attr('autocomplete', 'of');
        //            $('#o_zip1').attr('autocomplete', 'on');
        //            $('#daddress2').attr('autocomplete', 'on');

    });


    $("#viewCentral").click(function () {

        var ozip = $("#o_zip").val();
        var dzip = $("#d_zip").val();
        var vehType = $("#vehType0").val();
        var numVehicles = $("#count0").val();
        var veh = '';
        var condition = $("#condition0").val();
        var trailter = $("#trailter_type0").val();
        var type = $("#vehChkType0").val();
        var get_ses = $("#get_ses").val();

        if (condition == 'operable') {
            condition = '0';
        } else if (condition == 'non-running') {
            condition = '1';
        }
        if (trailter == 'open') {
            trailter = '0';
        } else if (trailter == 'enclosed') {
            trailter = '1';
        }

        if (ozip == '' || dzip == '' || vehType == '' || condition == '' || trailter == '') {
            document.getElementById("o_zip1").focus();
            document.getElementById("d_zip1").focus();

            $("#o_zip1").addClass("border_bottom_color");
            $("#d_zip1").addClass("border_bottom_color");
            $("#condition0").addClass("border_bottom_color");
            $("#trailer_type0").addClass("border_bottom_color");

        } else {
            ozip = ozip.split(",");
            dzip = dzip.split(",");
            if (vehType || type) {

                if (vehType == "Car") {
                    veh = "Car";
                }

                else if (vehType == "motorcycle" || vehType == "3_wheel_sidecar" || vehType == "3_wheel_motorcycle") {
                    veh = "Motorcycle";
                } else if (vehType == "atv") {
                    veh = "ATV";
                } else if (vehType == "SUV" || vehType == "Mid SUV" || vehType == "Large SUV") {
                    veh = "SUV";
                } else if (vehType == "Van" || vehType == "Mini Van" || vehType == "Cargo Van" || vehType == "Passenger Van") {
                    veh = "Van";
                } else if (vehType == "Pickup" || vehType == "Pickup Dually" || vehType == "Box Truck Dually") {
                    veh = "Pickup";
                } else if (type == "other_vehicle" || type == "other_motorcycle") {
                    veh = "Other";
                }
            } else {
                veh = '%2b';
            }
            // var url = `https://www.centraldispatch.com/protected/cargo/sample-prices-lightbox?num_vehicles=${numVehicles}&ozip=${ozip[2]}&dzip=${dzip[2]}&enclosed=${trailter}&inop=${condition}&vehicle_types=${veh}&miles=0&${get_ses}`;
            var url = `https://washington.shawntransport.com/get_web_price?num_vehicles=${numVehicles}&ozip=${ozip[2]}&dzip=${dzip[2]}&enclosed=${trailter}&inop=${condition}&vehicle_types=${veh}&miles=0&${get_ses}`;
            window.open(url, 'Central Dispatch Pricing', 'height=600,width=900,left=250,top=250,toolbar=No,location=No,scrollbars=Yes,status=No,resizable=Yes,fullscreen=No,directories=No,menubar=No,copyhistory=No');
        }
    });

    function not7() {
        notif({
            type: "warning",
            msg: "This Field is required!",
            position: "center",
            opacity: 0.8
        });
    }

    $("#driverPrice").on('keyup', function () {
        var price = $(this);
        $.ajax({
            url: "{{ url('/offer-price/get_commission') }}",
            type: "GET",
            data: { price: price.val() },
            dataType: "JSON",
            success: function (res) {
                price.siblings('.alert').remove();
                if (res.data.commission_price) {
                    var newprice = parseInt(price.val()) + parseInt(res.data.commission_price);
                    price.after(`
                            <div class="alert alert-success mt-3 py-1 position-relative">
                                <i class="fa fa-caret-down text-success" aria-hidden="true" style="transform: rotate(180deg);position: absolute;top: -17px;font-size: 24px;"></i>
                                $${newprice}
                            </div>
                        `);
                }
            }
        })
    })

    function validate() {
        var oname = document.forms["valid_form"]["oname"];
        var oaddress = document.forms["valid_form"]["oaddress"];
        // var dname = document.forms["valid_form"]["dname"];
        // var oterminal = document.forms["valid_form"]["oterminal"];
        // var dterminal = document.forms["valid_form"]["dterminal"];
        // var dphone = document.forms["valid_form"]["dphone"];
        var o_zip1 = document.forms["valid_form"]["o_zip1"];
        var d_zip1 = document.forms["valid_form"]["d_zip"];
        //            var oemail = document.forms["valid_form"]["oemail"];
        var ophone = document.forms["valid_form"]["ophone"];
        var daddress = document.forms["valid_form"]["daddress"];
        var orderPrice = document.forms["valid_form"]["orderPrice"];
        var driverPrice = document.forms["valid_form"]["driverPrice"];
        var startPrice = document.forms["valid_form"]["startPrice"];
        var image = document.forms["valid_form"]["image"];
        var approval_reason = document.forms["valid_form"]["approval_reason"];
        var nature_of_customer = document.forms["valid_form"]["nature_of_customer"];

        var year = $('[id^=year]');
        var make = $('[id^=make]');
        var model = $('[id^=model]');
        var valValidate = 0;
        year.each(function (index, item) {
            if (!$(year[index]).val()) {
                $(year[index]).css("border-color", "#ff0b00");
                valValidate = valValidate + 1;
            }
            if (!$(make[index]).val()) {
                $(make[index]).css("border-color", "#ff0b00");
                valValidate = valValidate + 1;

            }
            if (!$(model[index]).val()) {
                $(model[index]).css("border-color", "#ff0b00");
                valValidate = valValidate + 1;

            }
            //                if(!$(vehType[index]).val())
            //                    $(vehType[index]).prev().css("color", "#ff0b00");
            //                if(!$(condition[index]).val())
            //                    $(condition[index]).prev().css("color", "#ff0b00");
            //                if(!$(trailter_type[index]).val())
            //                    $(trailter_type[index]).prev().css("color", "#ff0b00");

            // if($(v_con_p[index]).is(":visible")) {
            //     if (!$(v_con_p[index]).val())
            //         $(v_con_p[index]).css("border-color", "#ff0b00");
            // }


        });
        // var v_con_p =  $('[id^=v_con_p]');
        // var vehType =  $('[id^=vehType]');
        // var condition =  $('[id^=condition]');
        // var trailter_type =  $('[id^=trailter_type]');

        if (!oname.value) {
            $("#oname").css("border-color", "#ff0b00");
            valValidate = valValidate + 1;
        }
        if (!oaddress.value) {
            $("#oaddress").css("border-color", "#ff0b00");
            valValidate = valValidate + 1;
        }
        //            if (!dname.value) {
        //                $("#dname").css("border-color", "#ff0b00");
        //            }
        // if (!dphone.value) {
        //     $("#dphone").css("border-color", "#ff0b00");
        // }
        if (!o_zip1.value) {
            $("#o_zip1").css("border-color", "#ff0b00");
            valValidate = valValidate + 1;
        }
        if (!d_zip1.value) {
            $("#d_zip1").css("border-color", "#ff0b00");
            valValidate = valValidate + 1;
        }
        //            if (!oemail.value) {
        //                $("#oemail").css("border-color", "#ff0b00");
        //            }
        if (!ophone.value) {
            $("#ophone").css("border-color", "#ff0b00");
            valValidate = valValidate + 1;
        }
        if (!daddress.value) {
            $("#daddress").css("border-color", "#ff0b00");
            valValidate = valValidate + 1;
        }
        // if (!oterminal.value) {
        //     $(".oterminallabel").css("color", "#ff0b00");
        // }
        // if (!dterminal.value) {
        //     $(".dterminallabel").css("color", "#ff0b00");
        // }
        if (orderPrice) {
            if (!orderPrice.value) {
                $("#orderPrice").css("border-color", "#ff0b00");
                valValidate = valValidate + 1;
            }
        }
        if (driverPrice) {
            if (!driverPrice.value) {
                $("#driverPrice").css("border-color", "#ff0b00");
                valValidate = valValidate + 1;
            }
        }
        if (startPrice) {
            if (!startPrice.value) {
                $("#startPrice").css("border-color", "#ff0b00");
                valValidate = valValidate + 1;
            }
        }
        //            if($(payment_method).is(":visible")) {
        //                if (!payment_method.value) {
        //                    $("#payment_method").css("border-color", "#ff0b00");
        //                }
        //            }
        //            if($(cod_cop_loc).is(":visible")) {
        //                if (!cod_cop_loc.value) {
        //                    $("#cod_cop_loc").css("border-color", "#ff0b00");
        //                }
        //            }
        // $("#carrier_status_2").parent('label').siblings('.text-danger').remove();
        if ($("select[name='pstatus']").children('option:selected').val() >= 7) {
            // if($("#carrier_status_1").is(":checked") || $("#carrier_status_2").is(":checked"))
            // {
            // }
            // else
            // {
            //     $("#carrier_status_2").parent('label').after(`
            //         <div class="text-danger">This field is required!</div>
            //     `);
            //     valValidate = valValidate + 1;
            // }
            if (approval_reason) {
                if (!approval_reason.value) {
                    $("#pay_later_op_reason").css("border-color", "#ff0b00");
                    valValidate = valValidate + 1;
                }
            }
            if (nature_of_customer) {
                if (!nature_of_customer.value) {
                    $("#nature_of_customer").css("border-color", "#ff0b00");
                    valValidate = valValidate + 1;
                }
            }
        }
        if (valValidate > 0) {
            $(window).scrollTop(0);
            return true;
        }
        return false;
    }

    function my_func(get) {
        var model = $(`#model${get}`).val();
        var make = $(`#makeOpt${get}`).val();
        var year = $(`#year${get}`).val();
        if (model.length >= 0 || make.length >= 0 || year.length >= 0) {
            $(`#googleimage${get}`).show();
        } else {
            $(`#googleimage${get}`).hide();
        }
    }

    function googl(get) {
        var model = $(`#model${get}`).val();
        var make = $(`#makeOpt${get}`).val();
        var year = $(`#year${get}`).val();
        var url = (`http://images.google.com/images?q=${year}+${make}+${model}`);
        window.open(url, 'GoogleImg', 'width=800,height=600,left=250,top=50, toolbar=No,location=No,scrollbars=Yes,status=No,resizable=Yes,fullscreen=No,directories=No,menubar=No,copyhistory=No');
    }

    $("#payCarrier").on("keypress", function (evt) {
        if (evt.which < 48 || evt.which > 57) {
            evt.preventDefault();
        }
    });
    $("#copcodAmount").on("keypress", function (evt) {
        if (evt.which < 48 || evt.which > 57) {
            evt.preventDefault();
        }
    });
    $("#orderPrice").on("keypress", function (evt) {
        if (evt.which < 48 || evt.which > 57) {
            evt.preventDefault();
        }
    });
    $("#startPrice").on("keypress", function (evt) {
        if (evt.which < 48 || evt.which > 57) {
            evt.preventDefault();
        }
    });


    function payCondition() {
        var data = `<div class="row">
             <input type="hidden" class="this_save" id="customer_status" name="customer_status" value="1">
                    <div class="col-lg-2 mt-4">
                        <div class="form-group">
                            <label class="rdiobox">
                                <input class="this_save" name="pay_cond" id="pay_cond1"  type="radio" value="1">
                                <span>COD / COP</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-2 mt-4">
                        <div class="form-group">
                            <label class="rdiobox">
                                <input class="this_save" name="pay_cond" id="pay_cond2" type="radio" value="2" >
                                <span>Pay With Email</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-2 mt-4">
                        <div class="form-group">
                            <label class="rdiobox">
                                <input class="this_save" name="pay_cond" id="pay_cond3" type="radio" value="3" >
                                <span>Pay Now</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-2 mt-4">
                        <div class="form-group">
                            <label class="rdiobox">
                                <input class="this_save" name="pay_cond" id="pay_cond4" type="radio" value="4" >
                                <span>Pay Later</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row" id="payConf"></div>
                <div class="row" id="emailRequired"></div>
                <div class="row" id="pay_late"></div>
                <div class="row" id="pay_late_reason"></div>

                <div class="row" id="submitData" style="display:none">
                   <div class="col-lg-12">
                        <button type="button" id="clickToSubmit" onclick="validate()" name="neworderpay1" value="neworderpay1" class="btn btn-primary"></button>
						<input type="hidden" value="0" name="neworderpay_btn" id="neworderpay_btn">
						<input type="hidden" value="0" name="continuetopay_btn" id="continuetopay_btn">
                    </div>
                </div>
                `;

        return data;
    }

    function payConditionJS() {
        $("#pay_cond1").click(function () {
            $("#saveBtn").html('Save');
            $("#continuetopay_btn").val(0);
            $("#continuetopayold_btn").val(0);
            $("#payConf").html(`
                        <div class="col-lg-12">
                            <div class="form-group">

                                <label class="section-title mt-3">Payment Confirm <span class="tx-danger">*</span></label>

                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">

                                <label class="rdiobox">
                                    <input class="this_save" name="pay_confirm" id="payment_cond1" type="radio" value="1" required >
                                    <span>Yes</span>
                                </label>

                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">

                                <label class="rdiobox">
                                    <input class="this_save" name="pay_confirm" id="payment_cond2" type="radio" value="0" >
                                    <span>No</span>
                                </label>

                            </div>
                        </div>
                `);
            $("#submitData").show();
            $("#emailRequired").html(`
                    <div class="col-lg-4">
                        <div class="form-group">

                            <label class="form-control this_save -label font-boldd tx-black">Email Address <span class="tx-danger">*</span></label>
                            <input required class="form-control this_save " autocomplete="nope" type="email" name="oemail2" id="oemail2" value="" >


                        </div>
                    </div>
                 `);
            $("#clickToSubmit").html('Submit');
            var email = $("#oemail").val();
            $("#oemail2").val(email);

            $("#oemail2").change(function () {
                $("#oemail").val($(this).val());
            });
            $('#pay_late').html('');
        });

        $("#pay_cond2").click(function () {
            $("#saveBtn").html('Save');
            $("#continuetopay_btn").val(0);
            $("#continuetopayold_btn").val(0);
            $("#payConf").html('');
            $("#submitData").show();
            $("#emailRequired").html(`
                <div class="col-lg-4">
                    <div class="form-group">

                        <label class="form-control this_save -label font-boldd tx-black">Email Address <span class="tx-danger">*</span></label>
                        <input required class="form-control this_save " autocomplete="nope" type="email" name="oemail2" id="oemail2" value="" >

                    </div>
                </div>
                `);
            $("#clickToSubmit").html('Submit');
            var email = $("#oemail").val();
            $("#oemail2").val(email);

            $("#oemail2").change(function () {
                $("#oemail").val($(this).val());
            });
            $('#pay_late').html('');
        });

        $("#pay_cond3").click(function () {
            $("#saveBtn").html('Next');
            $("#payConf").html('');
            $("#submitData").show();
            $("#emailRequired").html('');
            $("#clickToSubmit").html('Continue To Payment');
            $("#continuetopay_btn").val(1);
            $("#continuetopayold_btn").val(1);
            var email = $("#oemail").val();
            $("#oemail2").val(email);

            $("#oemail2").change(function () {
                $("#oemail").val($(this).val());
            });
            $('#pay_late').html('');
        });

        $("#pay_cond4").click(function () {
            $("#saveBtn").html('Save');
            // $("#pay_late").html(`<div class="col-lg-2 mt-4">
            //         <div class="form-group">
            //             <label class="rdiobox">
            //                 <input class="this_save" checked name="pstatus" id="pay_later1" type="radio" value="7" >
            //                 <span>Payment Missing</span>
            //             </label>
            //         </div>
            //     </div>
            //     <div class="col-lg-2 mt-4">
            //         <div class="form-group">
            //             <label class="rdiobox">
            //                 <input class="this_save" name="pstatus" id="pay_later2" type="radio" value="18" >
            //                 <span>On Approval</span>
            //             </label>
            //         </div>
            //     </div>`);

            $("#pay_late").html(`
                    <div class="col-lg-4 mt-4">
                        <div class="form-group">
                            <label class="rdiobox form-label">Select the Status</label>
                            <select name="pstatus" class="form-control" id="pay_later1">
                                <option selected disabled value="0">Select the Status</option>
                                <option value="7">Payment Missing</option>
                                <option value="18">On Approval</option>
                            </select>
                        </div>
                    </div>
                `);
            $("#continuetopay_btn").val(0);
            $("#continuetopayold_btn").val(1);
            $("#submitData").show();
            $("#emailRequired").html('');
            $("#clickToSubmit").html('Submit');
            var email = $("#oemail").val();
            $("#oemail2").val(email);

            $("#oemail2").change(function () {
                $("#oemail").val($(this).val());
            });
        });


        // $("body").delegate("#pay_later1", "click", function () {
        //     $("#saveBtn").html('Save');
        //     $("#pay_late_reason").html('');
        //     $("#continuetopay_btn").val(0);
        //     $("#continuetopayold_btn").val(1);
        //     $("#submitData").show();
        //     $("#emailRequired").html('');
        //     $("#clickToSubmit").html('Submit');
        //     var email = $("#oemail").val();
        //     $("#oemail2").val(email);

        //     $("#oemail2").change(function () {
        //         $("#oemail").val($(this).val());
        //     });
        // });

        // $("body").delegate("#pay_later2", "click", function() {
        //     $("#pay_late_reason").html(`<div class="col-lg-2 mt-4">
        //             <div class="form-group">
        //                 <label class="rdiobox">
        //                     <span>Reason</span>
        //                     <input class="this_save form-control" name="approval_reason" id="pay_later_op_reason" type="text" value="">
        //                 </label>
        //             </div>
        //         </div>`);
        //     $("#continuetopay_btn").val(0);
        //     $("#continuetopayold_btn").val(0);
        //     $("#submitData").show();
        //     $("#emailRequired").html('');
        //     var email = $("#oemail").val();
        //     $("#oemail2").val(email);

        //     $("#oemail2").change(function() {
        //         $("#oemail").val($(this).val());
        //     });
        // });


        $("body").delegate("#pay_later1", "change", function () {
            if ($("#pay_later1").val() == "18") {
                $("#pay_late_reason").html(`<div class="col-lg-8 mt-4">
                            <div class="form-group">
                                <label class="rdiobox form-label">
                                    <span>Reason</span>
                                    <input class="this_save form-control" name="approval_reason" required onkeyup="$('#order_history').val(this.value)" id="pay_later_op_reason" type="text" value="">
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-8 mt-8">
                            <div class="form-group icon-relative">
                                <label class="form-label font-boldd border_none">NATURE OF CUSTOMER</label>
                                <textarea id="nature_of_customer" name="nature_of_customer" rows="8" required class="form-control this_save nature_of_customer_length" oninput="checkMinLength(this, 250)" placeholder="Enter any special instructions, notes from the customer, or details regarding this shipment..."></textarea>
                                <div>
                                    <p class="charError text-secondary">Minimum 250 characters</p>
                                </div>
                            </div>
                        </div>
                        `);
                $(document).ready(function () {
                    // Call the checkMinLength function when the document is ready
                    checkMinLength();

                    // Attach the oninput event to the textarea
                    $(".nature_of_customer_length").on("input", function () {
                        checkMinLength();
                    });

                    function checkMinLength() {
                        var minLength = 250;
                        var currentValue = $("#nature_of_customer").val();
                        var remainingChars = minLength - currentValue.length;

                        if (remainingChars > -1) {
                            $(".charError").text("Remaining characters: " + remainingChars);
                        }

                        if (currentValue.length < minLength) {
                            $("#clickToSubmit").attr('disabled', true);
                        } else {
                            $("#clickToSubmit").attr('disabled', false);
                        }
                    }
                });
                $("#continuetopay_btn").val(0);
                $("#continuetopayold_btn").val(1);
                $("#submitData").show();
                $("#emailRequired").html('');
                var email = $("#oemail").val();
                $("#oemail2").val(email);

                $("#oemail2").change(function () {
                    $("#oemail").val($(this).val());
                });
            }
            if ($("#pay_later1").val() == "7") {
                $("#pay_late_reason").html(`<div class="col-lg-8 mt-4">
                            <div class="form-group">
                                <label class="rdiobox form-label">
                                    <span>Reason</span>
                                    <input class="this_save form-control" name="approval_reason" required onkeyup="$('#order_history').val(this.value)" id="pay_later_op_reason" type="text" value="">
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-8 mt-8">
                            <div class="form-group icon-relative">
                                <label class="form-label font-boldd border_none">NATURE OF CUSTOMER</label>
                                <textarea id="nature_of_customer" name="nature_of_customer" rows="8" required class="form-control this_save nature_of_customer_length" oninput="checkMinLength(this, 250)" placeholder="Enter any special instructions, notes from the customer, or details regarding this shipment..."></textarea>
                                <div>
                                    <p class="charError text-secondary">Minimum 250 characters</p>
                                </div>
                            </div>
                        </div>
                        `);
                $(document).ready(function () {
                    // Call the checkMinLength function when the document is ready
                    checkMinLength();

                    // Attach the oninput event to the textarea
                    $(".nature_of_customer_length").on("input", function () {
                        checkMinLength();
                    });

                    function checkMinLength() {
                        var minLength = 250;
                        var currentValue = $("#nature_of_customer").val();
                        var remainingChars = minLength - currentValue.length;

                        if (remainingChars > -1) {
                            $(".charError").text("Remaining characters: " + remainingChars);
                        }

                        if (currentValue.length < minLength) {
                            $("#clickToSubmit").attr('disabled', true);
                        } else {
                            $("#clickToSubmit").attr('disabled', false);
                        }
                    }
                });
                $("#saveBtn").html('Save');
                // $("#pay_late_reason").html('');
                $("#continuetopay_btn").val(0);
                $("#continuetopayold_btn").val(1);
                $("#submitData").show();
                $("#emailRequired").html('');
                $("#clickToSubmit").html('Submit');
                var email = $("#oemail").val();
                $("#oemail2").val(email);

                $("#oemail2").change(function () {
                    $("#oemail").val($(this).val());
                });
            }
        });


        $("#clickToSubmit").click(function () {
            if (validate() === true) {

            }
            else {
                $("#neworderpay_btn").val(1);
                $("#form").submit();
            }

        });

    }

    function oldPayCondition() {
        var data = `
                 <input type="hidden" class="this_save" id="customer_status" name="customer_status" value="0">
                <div class="row">
                    <div class="col-lg-2 mt-4">
                        <div class="form-group">

                            <label class="rdiobox">
                                <input class="this_save" name="pay_cond" id="pay_cond1" type="radio" value="1" >
                                <span>COD / COP</span>
                            </label>

                        </div>
                    </div>
                    <div class="col-lg-2 mt-4">
                        <div class="form-group">

                            <label class="rdiobox">
                                <input class="this_save" name="pay_cond" id="pay_cond2" type="radio" value="2" >
                                <span>Pay With Email</span>
                            </label>

                        </div>
                    </div>
                    <div class="col-lg-3 mt-4">
                        <div class="form-group">

                            <label class="rdiobox">
                                <input class="this_save" name="pay_cond" id="pay_cond3" type="radio" value="3" >
                                <span>Pay Now/Already Have Card</span>
                            </label>

                        </div>
                    </div>
                    <div class="col-lg-2 mt-4">
                        <div class="form-group">
                            <label class="rdiobox">
                                <input class="this_save" name="pay_cond" id="pay_cond4" type="radio" value="4" >
                                <span>Pay Later</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row" id="sendEmailConf"></div>
                <div class="row" id="payConf"></div>
                <div class="row" id="pay_late"></div>
                <div class="row" id="pay_late_reason"></div>
                <div class="row" id="emailRequired"></div>

                <div class="row" id="submitData" style="display:none">
                    <div class="col-lg-12">
                        <!--<button type="submit" id="clickToSubmit"  class="btn btn-primary"></button>-->
						<button type="button" id="clickToSubmit" onclick="validate()" name="neworderpay1" value="neworderpay1" class="btn btn-primary"></button>
						<input type="hidden" value="0" name="neworderpay_btn" id="neworderpay_btn">
						<input type="hidden" value="0" name="continuetopayold_btn" id="continuetopayold_btn">

                    </div>
                </div>
                `;

        return data;
    }

    function oldPayConditionJS() {
        $("#pay_cond1").click(function () {
            $("#saveBtn").html('Save');
            $("#continuetopay_btn").val(0);
            $("#continuetopayold_btn").val(0);
            $("#payConf").html(`
                <div class="col-lg-12">
                    <div class="form-group">

                        <label class="section-title mt-3">Payment Confirm <span class="tx-danger">*</span></label>

                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">

                        <label class="rdiobox">
                            <input class="this_save" name="pay_confirm" id="payment_cond1" type="radio" value="1" required >
                            <span>Yes</span>
                        </label>

                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">

                        <label class="rdiobox">
                            <input class="this_save" name="pay_confirm" id="payment_cond2" type="radio" value="0" >
                            <span>No</span>
                        </label>

                    </div>
                </div>`);

            $("#sendEmailConf").html(`
                            <div class='col-lg-12'>
                                <div class='form-group'>
                                    <label class='section-title mt-3'>Send Email <span class='tx-danger'>*</span></label>
                                </div>
                            </div>
                            <div class='col-lg-2'>
                                <div class='form-group'>

                                    <label class='rdiobox'>
                                        <input class='this_save' name='confirm1' onchange='' type='radio' value='1' required>
                                        <span>Yes</span>
                                    </label>

                                </div>
                            </div>
                            <div class='col-lg-2'>
                                <div class='form-group'>
                                    <label class='rdiobox'>
                                        <input class='this_save' name='confirm1' onchange='' type='radio' value='0'>
                                        <span>No</span>
                                    </label>

                                </div>
                            </div>`);


            $("#submitData").show();
            $("#emailRequired").html(`
                <div class='col-lg-4'>
                    <div class='form-group'>

                        <label class='form-control this_save -label font-boldd tx-black' id='emailAddrTxt'>Email Address <span class='tx-danger'>*</span></label>
                        <input required class='form-control this_save ' autocomplete='nope' type='text' name='oemail2' id='oemail2' value='' >

                    </div>
                </div>`);


            $("#clickToSubmit").html('Submit');
            var email = $("#oemail").val();
            $("#oemail2").val(email);

            $("#oemail2").change(function () {
                $("#oemail").val($(this).val());
            });
            $('#pay_late').html('');
        });


        $("#clickToSubmit").click(function () {
            if (validate() === true) {

            }
            else {
                $("#neworderpay_btn").val(1);
                $("#form").submit();
            }
        });

        $("#pay_cond2").click(function () {
            $("#saveBtn").html('Save');
            $("#continuetopay_btn").val(0);
            $("#continuetopayold_btn").val(0);
            $("#sendEmailConf").html('');
            $("#submitData").show();
            $("#payConf").html('');
            $("#emailRequired").html(`
                <div class="col-lg-4">
                    <div class="form-group">
                    <label class="form-control this_save -label font-boldd tx-black" id="emailAddrTxt">Email Address <span class="tx-danger">*</span></label>
                    <input required class="form-control this_save " autocomplete="nope" type="text" name="oemail2" id="oemail2" value="" >
                    </div>
                    </div>`);
            $("#clickToSubmit").html('Submit');
            var email = $("#oemail").val();
            $("#oemail2").val(email);

            $("#oemail2").change(function () {
                $("#oemail").val($(this).val());
            });
            $('#pay_late').html('');
        });

        $("#pay_cond3").click(function () {
            $("#saveBtn").html('Save');
            $("#sendEmailConf").html('');
            $("#submitData").show();
            $("#payConf").html('');
            $("#emailRequired").html('');
            $("#clickToSubmit").html('Continue To Payment');
            $("#continuetopay_btn").val(1);
            $("#continuetopayold_btn").val(1);
            var email = $("#oemail").val();
            $("#oemail2").val(email);

            $("#oemail2").change(function () {
                $("#oemail").val($(this).val());
            });
            $('#pay_late').html('');
        });

        // $("body").delegate("#pay_later1", "click", function () {
        //     $("#saveBtn").html('Save');
        //     $("#pay_late_reason").html('');
        //     $("#continuetopay_btn").val(0);
        //     $("#continuetopayold_btn").val(1);
        //     $("#submitData").show();
        //     $("#emailRequired").html('');
        //     $("#clickToSubmit").html('Submit');
        //     var email = $("#oemail").val();
        //     $("#oemail2").val(email);

        //     $("#oemail2").change(function () {
        //         $("#oemail").val($(this).val());
        //     });
        // });

        // $("body").delegate("#pay_later2", "click", function() {
        //     $("#pay_late_reason").html(`<div class="col-lg-2 mt-4">
        //             <div class="form-group">
        //                 <label class="rdiobox">
        //                     <span>Reason</span>
        //                     <input class="this_save form-control" name="approval_reason" id="pay_later_op_reason" type="text" value="">
        //                 </label>
        //             </div>
        //         </div>`);
        //     $("#continuetopay_btn").val(0);
        //     $("#continuetopayold_btn").val(0);
        //     $("#submitData").show();
        //     $("#emailRequired").html('');
        //     var email = $("#oemail").val();
        //     $("#oemail2").val(email);

        //     $("#oemail2").change(function() {
        //         $("#oemail").val($(this).val());
        //     });
        // });


        $("body").delegate("#pay_later1", "change", function () {
            if ($("#pay_later1").val() == "18") {
                $("#pay_late_reason").html(`<div class="col-lg-8 mt-4">
                            <div class="form-group">
                                <label class="rdiobox form-label">
                                    <span>Reason</span>
                                    <input class="this_save form-control" name="approval_reason" required onkeyup="$('#order_history').val(this.value)" id="pay_later_op_reason" type="text" value="">
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-8 mt-8">
                            <div class="form-group icon-relative">
                                <label class="form-label font-boldd border_none">NATURE OF CUSTOMER</label>
                                <textarea id="nature_of_customer" name="nature_of_customer" rows="8" required class="form-control this_save nature_of_customer_length" oninput="checkMinLength(this, 250)" placeholder="Enter any special instructions, notes from the customer, or details regarding this shipment..."></textarea>
                                <div>
                                    <p class="charError text-secondary">Minimum 250 characters</p>
                                </div>
                            </div>
                        </div>
                        `);
                $(document).ready(function () {
                    // Call the checkMinLength function when the document is ready
                    checkMinLength();

                    // Attach the oninput event to the textarea
                    $(".nature_of_customer_length").on("input", function () {
                        checkMinLength();
                    });

                    function checkMinLength() {
                        var minLength = 250;
                        var currentValue = $("#nature_of_customer").val();
                        var remainingChars = minLength - currentValue.length;

                        if (remainingChars > -1) {
                            $(".charError").text("Remaining characters: " + remainingChars);
                        }

                        if (currentValue.length < minLength) {
                            $("#clickToSubmit").attr('disabled', true);
                        } else {
                            $("#clickToSubmit").attr('disabled', false);
                        }
                    }
                });
                $("#continuetopay_btn").val(0);
                $("#continuetopayold_btn").val(1);
                $("#submitData").show();
                $("#emailRequired").html('');
                var email = $("#oemail").val();
                $("#oemail2").val(email);

                $("#oemail2").change(function () {
                    $("#oemail").val($(this).val());
                });
            }
            if ($("#pay_later1").val() == "7") {
                $("#pay_late_reason").html(`<div class="col-lg-8 mt-4">
                            <div class="form-group">
                                <label class="rdiobox form-label">
                                    <span>Reason</span>
                                    <input class="this_save form-control" name="approval_reason" required onkeyup="$('#order_history').val(this.value)" id="pay_later_op_reason" type="text" value="">
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-8 mt-8">
                            <div class="form-group icon-relative">
                                <label class="form-label font-boldd border_none">NATURE OF CUSTOMER</label>
                                <textarea id="nature_of_customer" name="nature_of_customer" rows="8" required class="form-control this_save nature_of_customer_length" oninput="checkMinLength(this, 250)" placeholder="Enter any special instructions, notes from the customer, or details regarding this shipment..."></textarea>
                                <div>
                                    <p class="charError text-secondary">Minimum 250 characters</p>
                                </div>
                            </div>
                        </div>
                        `);
                $(document).ready(function () {
                    // Call the checkMinLength function when the document is ready
                    checkMinLength();

                    // Attach the oninput event to the textarea
                    $(".nature_of_customer_length").on("input", function () {
                        checkMinLength();
                    });

                    function checkMinLength() {
                        var minLength = 250;
                        var currentValue = $("#nature_of_customer").val();
                        var remainingChars = minLength - currentValue.length;

                        if (remainingChars > -1) {
                            $(".charError").text("Remaining characters: " + remainingChars);
                        }

                        if (currentValue.length < minLength) {
                            $("#clickToSubmit").attr('disabled', true);
                        } else {
                            $("#clickToSubmit").attr('disabled', false);
                        }
                    }
                });
                $("#saveBtn").html('Save');
                // $("#pay_late_reason").html('');
                $("#continuetopay_btn").val(0);
                $("#continuetopayold_btn").val(1);
                $("#submitData").show();
                $("#emailRequired").html('');
                $("#clickToSubmit").html('Submit');
                var email = $("#oemail").val();
                $("#oemail2").val(email);

                $("#oemail2").change(function () {
                    $("#oemail").val($(this).val());
                });
            }
        });

        $("#pay_cond4").click(function () {
            // $("#saveBtn").html('Save');
            // $("#pay_late").html(`<div class="col-lg-2 mt-4">
            //         <div class="form-group">
            //             <label class="rdiobox">
            //                 <input class="this_save" name="pstatus" id="pay_later1" type="radio" value="7" >
            //                 <span>Payment Missing</span>
            //             </label>
            //         </div>
            //     </div>
            //     <div class="col-lg-2 mt-4">
            //         <div class="form-group">
            //             <label class="rdiobox">
            //                 <input class="this_save" name="pstatus" id="pay_later2" type="radio" value="18" >
            //                 <span>On Approval</span>
            //             </label>
            //         </div>
            //     </div>`);
            $("#pay_late").html(`
                    <div class="col-lg-4 mt-4">
                        <div class="form-group">
                            <label class="rdiobox form-label">Select the Status</label>
                            <select name="pstatus" class="form-control" id="pay_later1">
                                <option selected value="0">Select the Status</option>
                                <option value="7">Payment Missing</option>
                                <option value="18">On Approval</option>
                            </select>
                        </div>
                    </div>
                `);
            $("#continuetopay_btn").val(0);
            $("#continuetopayold_btn").val(1);
            $("#payConf").html('');
            $("#submitData").show();
            $("#emailRequired").html('');
            $("#clickToSubmit").html('Submit');
            var email = $("#oemail").val();
            $("#oemail2").val(email);

            $("#oemail2").change(function () {
                $("#oemail").val($(this).val());
            });
        });

        $("#clickToSubmit").click(function () {
            $("#selecttype").modal('hide');
            $("#manualOrder").attr('action', 'post.php');
            $("#manualOrder").submit();
        });
    }

    function sendConf(val) {
        if (val == 2) {
            $("#oemail2").removeAttr('required');
            $("#emailAddrTxt").html('Email Address');
        } else {
            $("#oemail2").attr('required', 'required');
            $("#emailAddrTxt").html('Email Address <span class="tx-danger">*</span>');
        }
    }

    $("#newCust").click(function () {
        $('#customer_status').trigger('change');
        $("#payCondition").html('');
        $("#payCondition").html(payCondition());
        payConditionJS();
    });

    $("#oldCust").click(function () {
        $('#customer_status').trigger('change');
        $("#payCondition").html('');
        $("#payCondition").html(oldPayCondition());
        oldPayConditionJS();
    });

    $("#payCarrier").change(function () {
        var pay = $(this).val();
        var copcod = $("#copcodAmount").val();
        var payMethod = $("#payment_method").val();
        var codcoploc = $("#cod_cop_loc").val();
        if (!copcod) {
            $("#balAmount").val(pay);
        }
        var balTime = $("#balance_time").val();
        var balTerms = $("#terms").val();
        var balMethod = $("#balance_method").val();
        var bal = pay - copcod;
        $("#balPart").hide();
        $("#balPart").show();
        if (balTime || balTerms || balMethod) {
            if (copcod > 0) {
                $("#balAmount").val(Math.abs(bal));
                $("#copcodPart").hide();
                $("#copcodPart").show();
                if (pay < copcod) {
                    if (payMethod && codcoploc) {
                        $("#alertMSG").html(`<div class="bg-danger-transparent-2 text-danger px-4 py-2 br-3 mb-4" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span> at <span class="font-boldd">${codcoploc}</span>.
                                </div>`);
                    } else if (payMethod) {
                        $("#alertMSG").html(`<div class="bg-danger-transparent-2 text-danger px-4 py-2 br-3 mb-4" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span>.
                                </div>`);
                    } else if (codcoploc) {
                        $("#alertMSG").html(`<div class="bg-danger-transparent-2 text-danger px-4 py-2 br-3 mb-4" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> at <span class="font-boldd">${codcoploc}</span>.
                                </div>`);
                    } else {
                        $("#alertMSG").html(`<div class="bg-danger-transparent-2 text-danger px-4 py-2 br-3 mb-4" role="alert"> The carrier will receive $
                            <span class="font-boldd">${copcod}</span>
                             </div>`);
                    }
                    $("#alertMSG").html('');
                    if (balTime && balTerms && balMethod) {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span> within <span class="font-boldd">${balTime}</span> of <span class="font-boldd">${balTerms}</span>.
                        </div>`);
                    } else if (balTime && balMethod) {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span> within <span class="font-boldd">${balTime}</span>.
                        </div>`);
                    } else {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span>.
                        </div>`);
                    }
                } else if (pay > copcod) {
                    $("#alertMSG").html('');
                    if (payMethod && codcoploc) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span> at <span class="font-boldd">${codcoploc}</span>.
                                </div>`);
                    } else if (payMethod) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span>.
                                </div>`);
                    } else if (codcoploc) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> at <span class="font-boldd">${codcoploc}</span>.
                                </div>`);
                    } else {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span>.
                                </div>`);
                    }
                    if (balTime && balTerms && balMethod) {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span> within <span class="font-boldd">${balTime}</span> of <span class="font-boldd">${balTerms}</span>.
                        </div>`);
                    } else if (balTime && balMethod) {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span> within <span class="font-boldd">${balTime}</span>.
                        </div>`);
                    } else {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span>.
                        </div>`);
                    }
                }
                if (bal < 0) {
                    $("#balAmount").val(Math.abs(bal));
                    $("#copcodPart").hide();
                    $("#copcodPart").show();
                    $("#alertMSG").html('');
                    if (payMethod && codcoploc) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span> at <span class="font-boldd">${codcoploc}</span>.
                                </div>`);
                    } else if (payMethod) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span>.
                                </div>`);
                    } else if (codcoploc) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> at <span class="font-boldd">${codcoploc}</span>.
                                </div>`);
                    } else {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span>.
                                </div>`);
                    }
                    if (balTime && balTerms && balMethod) {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span> within <span class="font-boldd">${balTime}</span> of <span class="font-boldd">${balTerms}</span>.
                        </div>`);
                    } else if (balTime && balMethod) {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span> within <span class="font-boldd">${balTime}</span>.
                        </div>`);
                    } else {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span>.
                        </div>`);
                    }
                }
            } else {
                $("#balAmount").val(pay);
                $("#alertMSG").html('');
                $("#copcodPart").hide();
                if (balTime && balTerms && balMethod) {
                    $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span> within <span class="font-boldd">${balTime}</span> of <span class="font-boldd">${balTerms}</span>.
                        </div>`);
                } else if (balTime && balMethod) {
                    $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span> within <span class="font-boldd">${balTime}</span>.
                        </div>`);
                } else if (balMethod) {
                    $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span>.
                        </div>`);
                } else {
                    $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span>.
                        </div>`);
                }
            }
            if (bal == 0) {
                $("#copcodPart").hide();
                $("#copcodPart").show();
                $("#balPart").hide();
                $("#balAmount").val(0);
                if (payMethod && codcoploc) {
                    $("#alertMSG").html('');
                    $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span> at <span class="font-boldd">${codcoploc}</span>.
                            </div>`);
                } else if (payMethod) {
                    $("#alertMSG").html('');
                    $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span>.
                            </div>`);
                } else if (codcoploc) {
                    $("#alertMSG").html('');
                    $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                The carrier will receive $<span class="font-boldd">${copcod}</span> at <span class="font-boldd">${codcoploc}</span>.
                            </div>`);
                } else {
                    $("#alertMSG").html('');
                    $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                The carrier will receive $<span class="font-boldd">${copcod}</span>.
                            </div>`);
                }
            }
            if (pay && !copcod) {
                $("#alertMSG").html('');
                if (balTime && balTerms && balMethod) {
                    $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${pay}</span> via <span class="font-boldd">${balMethod}</span> within <span class="font-boldd">${balTime}</span> of <span class="font-boldd">${balTerms}</span>.
                        </div>`);
                } else if (balTime && balMethod) {
                    $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${pay}</span> via <span class="font-boldd">${balMethod}</span> within <span class="font-boldd">${balTime}</span>.
                        </div>`);
                } else {
                    $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${pay}</span> via <span class="font-boldd">${balMethod}</span>.
                        </div>`);
                }
            }
        } else {
            if (copcod > 0) {
                $("#balAmount").val(Math.abs(bal));
                $("#copcodPart").hide();
                $("#copcodPart").show();
                if (pay < copcod) {
                    $("#alertMSG").html('');
                    if (payMethod && codcoploc) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span> at <span class="font-boldd">${codcoploc}</span>.
                                </div>`);
                    } else if (payMethod) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span>.
                                </div>`);
                    } else if (codcoploc) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> at <span class="font-boldd">${codcoploc}</span>.
                                </div>`);
                    } else {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span>.
                                </div>`);
                    }
                    $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span>.
                        </div>`);
                } else if (pay > copcod) {
                    $("#alertMSG").html('');
                    if (payMethod && codcoploc) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span> at <span class="font-boldd">${codcoploc}</span>.
                                </div>`);
                    } else if (payMethod) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span>.
                                </div>`);
                    } else if (codcoploc) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> at <span class="font-boldd">${codcoploc}</span>.
                                </div>`);
                    } else {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span>.
                                </div>`);
                    }
                    $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span>.
                        </div>`);
                }
                if (bal < 0) {
                    $("#balAmount").val(Math.abs(bal));
                    $("#copcodPart").hide();
                    $("#copcodPart").show();
                    $("#alertMSG").html('');
                    if (payMethod && codcoploc) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span> at <span class="font-boldd">${codcoploc}</span>.
                                </div>`);
                    } else if (payMethod) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span>.
                                </div>`);
                    } else if (codcoploc) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> at <span class="font-boldd">${codcoploc}</span>.
                                </div>`);
                    } else {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span>.
                                </div>`);
                    }
                    $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            The carrier will pay you $<span class="font-boldd">${Math.abs(bal)}</span>.
                        </div>`);
                }
            } else {
                $("#balAmount").val(pay);
                $("#alertMSG").html('');
                $("#copcodPart").hide();
                if (balTime && balTerms && balMethod) {
                    $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span> within <span class="font-boldd">${balTime}</span> of <span class="font-boldd">${balTerms}</span>.
                        </div>`);
                } else if (balTime && balMethod) {
                    $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span> within <span class="font-boldd">${balTime}</span>.
                        </div>`);
                } else if (balMethod) {
                    $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span>.
                        </div>`);
                } else {
                    $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span>.
                        </div>`);
                }
            }
            if (bal == 0) {
                $("#copcodPart").hide();
                $("#copcodPart").show();
                $("#balPart").hide();
                $("#balAmount").val(0);
                if (payMethod && codcoploc) {
                    $("#alertMSG").html('');
                    $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span> at <span class="font-boldd">${codcoploc}</span>.
                            </div>`);
                } else if (payMethod) {
                    $("#alertMSG").html('');
                    $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span>.
                            </div>`);
                } else if (codcoploc) {
                    $("#alertMSG").html('');
                    $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                The carrier will receive $<span class="font-boldd">${copcod}</span> at <span class="font-boldd">${codcoploc}</span>.
                            </div>`);
                } else {
                    $("#alertMSG").html('');
                    $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                The carrier will receive $<span class="font-boldd">${copcod}</span>.
                            </div>`);
                }
            }
            if (pay && !copcod) {
                $("#alertMSG").html('');
                $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${pay}</span>.
                        </div>`);
            }
        }
        if (!pay && !copcod) {
            $("#alertMSG").html('');
            $("#balAmount").val(0);
            $("#copcodPart").hide();
        }
        setTimeout(function () {
            if (jQuery("#copcodPart").css('display') === 'none') {
                //condition
                $("#payment_method").prop('required', false);
                $("#cod_cop_loc").prop('required', false);
            } else if (jQuery("#copcodPart").css('display') === 'block') {
                $("#payment_method").prop('required', true);
                $("#cod_cop_loc").prop('required', true);
            } else {
                $("#payment_method").prop('required', true);
                $("#cod_cop_loc").prop('required', true);
            }
            if (jQuery("#balPart").css('display') === 'none') {
                //condition
                $("#balance_method").prop('required', false);
                $("#balance_time").prop('required', false);
                $("#terms").prop('required', false);
            } else if (jQuery("#balPart").css('display') === 'block') {
                $("#balance_method").prop('required', true);
                $("#balance_time").prop('required', true);
                $("#terms").prop('required', true);
            } else {
                $("#balance_method").prop('required', false);
                $("#balance_time").prop('required', false);
                $("#terms").prop('required', false);
            }
        }, 1000)
    });

    $("#copcodAmount").change(function () {
        var copcod = $(this).val();
        var pay = $("#payCarrier").val();
        var payMethod = $("#payment_method").val();
        var codcoploc = $("#cod_cop_loc").val();
        var bal = pay - copcod;
        var balTime = $("#balance_time").val();
        var balTerms = $("#terms").val();
        var balMethod = $("#balance_method").val();
        $("#balPart").hide();
        $("#balPart").show();
        if (balTime || balTerms || balMethod) {
            if (copcod > 0) {
                $("#balAmount").val(Math.abs(bal));
                $("#copcodPart").hide();
                $("#copcodPart").show();
                if (pay < copcod) {
                    $("#alertMSG").html('');
                    if (payMethod && codcoploc) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span> at <span class="font-boldd">${codcoploc}</span>.
                                </div>`);
                    } else if (payMethod) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span>.
                                </div>`);
                    } else if (codcoploc) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> at <span class="font-boldd">${codcoploc}</span>.
                                </div>`);
                    } else {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span>.
                                </div>`);
                    }
                    if (balTime && balTerms && balMethod) {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span> within <span class="font-boldd">${balTime}</span> of <span class="font-boldd">${balTerms}</span>.
                        </div>`);
                    } else if (balTime && balMethod) {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span> within <span class="font-boldd">${balTime}</span>.
                        </div>`);
                    } else {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span>.
                        </div>`);
                    }
                } else if (pay > copcod) {
                    $("#alertMSG").html('');
                    if (payMethod && codcoploc) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span> at <span class="font-boldd">${codcoploc}</span>.
                                </div>`);
                    } else if (payMethod) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span>.
                                </div>`);
                    } else if (codcoploc) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> at <span class="font-boldd">${codcoploc}</span>.
                                </div>`);
                    } else {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span>.
                                </div>`);
                    }
                    if (balTime && balTerms && balMethod) {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span> within <span class="font-boldd">${balTime}</span> of <span class="font-boldd">${balTerms}</span>.
                        </div>`);
                    } else if (balTime && balMethod) {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span> within <span class="font-boldd">${balTime}</span>.
                        </div>`);
                    } else {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span>.
                        </div>`);
                    }
                }
                if (bal < 0) {
                    $("#balAmount").val(Math.abs(bal));
                    $("#copcodPart").hide();
                    $("#copcodPart").show();
                    $("#alertMSG").html('');
                    if (payMethod && codcoploc) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span> at <span class="font-boldd">${codcoploc}</span>.
                                </div>`);
                    } else if (payMethod) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span>.
                                </div>`);
                    } else if (codcoploc) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> at <span class="font-boldd">${codcoploc}</span>.
                                </div>`);
                    } else {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span>.
                                </div>`);
                    }
                    if (balTime && balTerms && balMethod) {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            The carrier will pay you $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span> within <span class="font-boldd">${balTime}</span> of <span class="font-boldd">${balTerms}</span>.
                        </div>`);
                    } else if (balTime && balMethod) {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            The carrier will pay you $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span> within <span class="font-boldd">${balTime}</span>.
                        </div>`);
                    } else {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            The carrier will pay you $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span>.
                        </div>`);
                    }
                }
            } else {
                $("#balAmount").val(pay);
                $("#alertMSG").html('');
                $("#copcodPart").hide();
                if (balTime && balTerms && balMethod) {
                    $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span> within <span class="font-boldd">${balTime}</span> of <span class="font-boldd">${balTerms}</span>.
                        </div>`);
                } else if (balTime && balMethod) {
                    $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span> within <span class="font-boldd">${balTime}</span>.
                        </div>`);
                } else if (balMethod) {
                    $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span>.
                        </div>`);
                } else {
                    $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span>.
                        </div>`);
                }
            }
            if (bal == 0) {
                $("#copcodPart").hide();
                $("#copcodPart").show();
                $("#balPart").hide();
                $("#balAmount").val(0);
                if (payMethod && codcoploc) {
                    $("#alertMSG").html('');
                    $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span> at <span class="font-boldd">${codcoploc}</span>.
                            </div>`);
                } else if (payMethod) {
                    $("#alertMSG").html('');
                    $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span>.
                            </div>`);
                } else if (codcoploc) {
                    $("#alertMSG").html('');
                    $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                The carrier will receive $<span class="font-boldd">${copcod}</span> at <span class="font-boldd">${codcoploc}</span>.
                            </div>`);
                } else {
                    $("#alertMSG").html('');
                    $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                The carrier will receive $<span class="font-boldd">${copcod}</span>.
                            </div>`);
                }
            }
        } else {
            if (copcod > 0) {
                $("#balAmount").val(Math.abs(bal));
                $("#copcodPart").hide();
                $("#copcodPart").show();
                if (pay < copcod) {
                    $("#alertMSG").html('');
                    if (payMethod && codcoploc) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span> at <span class="font-boldd">${codcoploc}</span>.
                                </div>`);
                    } else if (payMethod) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span>.
                                </div>`);
                    } else if (codcoploc) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> at <span class="font-boldd">${codcoploc}</span>.
                                </div>`);
                    } else {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span>.
                                </div>`);
                    }
                    $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span>.
                        </div>`);
                } else if (pay > copcod) {
                    $("#alertMSG").html('');
                    if (payMethod && codcoploc) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span> at <span class="font-boldd">${codcoploc}</span>.
                                </div>`);
                    } else if (payMethod) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span>.
                                </div>`);
                    } else if (codcoploc) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> at <span class="font-boldd">${codcoploc}</span>.
                                </div>`);
                    } else {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span>.
                                </div>`);
                    }
                    $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span>.
                        </div>`);
                }
                if (bal < 0) {
                    $("#balAmount").val(Math.abs(bal));
                    $("#copcodPart").hide();
                    $("#copcodPart").show();
                    $("#alertMSG").html('');
                    if (payMethod && codcoploc) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span> at <span class="font-boldd">${codcoploc}</span>.
                                </div>`);
                    } else if (payMethod) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span>.
                                </div>`);
                    } else if (codcoploc) {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span> at <span class="font-boldd">${codcoploc}</span>.
                                </div>`);
                    } else {
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-boldd">${copcod}</span>.
                                </div>`);
                    }
                    $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            The carrier will pay you $<span class="font-boldd">${Math.abs(bal)}</span>.
                        </div>`);
                }
            } else {
                $("#balAmount").val(pay);
                $("#alertMSG").html('');
                $("#copcodPart").hide();
                if (balTime && balTerms && balMethod) {
                    $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span> within <span class="font-boldd">${balTime}</span> of <span class="font-boldd">${balTerms}</span>.
                        </div>`);
                } else if (balTime && balMethod) {
                    $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span> within <span class="font-boldd">${balTime}</span>.
                        </div>`);
                } else if (balMethod) {
                    $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span> via <span class="font-boldd">${balMethod}</span>.
                        </div>`);
                } else {
                    $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-boldd">${Math.abs(bal)}</span>.
                        </div>`);
                }
            }
            if (bal == 0) {
                $("#copcodPart").hide();
                $("#copcodPart").show();
                $("#balPart").hide();
                $("#balAmount").val(0);
                if (payMethod && codcoploc) {
                    $("#alertMSG").html('');
                    $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span> at <span class="font-boldd">${codcoploc}</span>.
                            </div>`);
                } else if (payMethod) {
                    $("#alertMSG").html('');
                    $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                The carrier will receive $<span class="font-boldd">${copcod}</span> via <span class="font-boldd">${payMethod}</span>.
                            </div>`);
                } else if (codcoploc) {
                    $("#alertMSG").html('');
                    $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                The carrier will receive $<span class="font-boldd">${copcod}</span> at <span class="font-boldd">${codcoploc}</span>.
                            </div>`);
                } else {
                    $("#alertMSG").html('');
                    $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                The carrier will receive $<span class="font-boldd">${copcod}</span>.
                            </div>`);
                }
            }
        }
        setTimeout(function () {
            if (jQuery("#copcodPart").css('display') === 'none') {
                //condition
                $("#payment_method").prop('required', false);
                $("#cod_cop_loc").prop('required', false);
            } else if (jQuery("#copcodPart").css('display') === 'block') {
                $("#payment_method").prop('required', true);
                $("#cod_cop_loc").prop('required', true);
            } else {
                $("#payment_method").prop('required', true);
                $("#cod_cop_loc").prop('required', true);
            }
            if (jQuery("#balPart").css('display') === 'none') {
                //condition
                $("#balance_method").prop('required', false);
                $("#balance_time").prop('required', false);
                $("#terms").prop('required', false);
            } else if (jQuery("#balPart").css('display') === 'block') {
                $("#balance_method").prop('required', true);
                $("#balance_time").prop('required', true);
                $("#terms").prop('required', true);
            } else {
                $("#balance_method").prop('required', false);
                $("#balance_time").prop('required', false);
                $("#terms").prop('required', false);
            }
        }, 1000)
    });

    $("#needDeposit").click(function () {
        if ($(this).is(":checked")) {
            $("#depositContent").html(`
                    <label class="label font-boldd tx-black">Deposit Amount <span class="tx-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i style="color: #705ec8;font-size:larger" class="fa fa-usd tx-16 lh-0 op-6"></i>
                            </div>
                        </div>
                        <input class="form-control this_save " autocomplete="off" type="text" required
                               name="depositAmount" value="" placeholder="" id="depositAmount" style="width: 90%">
                    </div>
                `);
        } else {
            $("#depositContent").html('');
        }
    });
    $("#pickup_date").change(function () {
        $("#whenPickUpDate").html('');
        var pickupDate = $(this).val();
        if (pickupDate) {
            $("#whenPickUpDate").html(`<div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="rdiobox">
                                <input class="this_save" name="when_pickup" id="pickup1" type="radio" value="before" >
                                <span>Before</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="rdiobox">
                                <input class="this_save" name="when_pickup" id="pickup2" type="radio" value="after" >
                                <span>After</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="rdiobox">
                                <input class="this_save" name="when_pickup" id="pickup3" type="radio" value="on" >
                                <span>On</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="rdiobox">
                                <input class="this_save" name="when_pickup" id="pickup4" type="radio" value="asap" >
                                <span>ASAP</span>
                            </label>
                        </div>
                    </div>
                </div>`);
        } else {
            $("#whenPickUpDate").html('');
        }
    });

    $("#delivery_date").change(function () {
        var pickupDate = $(this).val();
        if (pickupDate) {
            $("#whenDeliveryDate").html(``);
            $("#whenDeliveryDate").html(`<div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="rdiobox">
                                <input class="this_save" name="when_delivery" id="delivery1" type="radio" value="before" >
                                <span>Before</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="rdiobox">
                                <input class="this_save" name="when_delivery" id="delivery2" type="radio" value="after" >
                                <span>After</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="rdiobox">
                                <input class="this_save" name="when_delivery" id="delivery3" type="radio" value="on" >
                                <span>On</span>
                            </label>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="rdiobox">
                                <input class="this_save" name="when_delivery" id="delivery4" type="radio" value="asap" >
                                <span>ASAP</span>
                            </label>
                        </div>
                    </div>
                </div>`);
        } else {
            $("#whenDeliveryDate").html('');
        }
    });

    $("#dterminal").change(function () {
        $('#d_zip1').trigger('change');
        var id = $(this).val();
        if (id == 1 || id == 8 || id == 9) {
            $(".dauc").html('');
        } else {
            $(".dauc").html('');
            if (id == 7 || id == 6) {
                $(".dauc").html(`
                         <div class="col-lg-6">
                            <div class="form-group">
                            <div class="Terminal-error">
                                <label id="dauction" class="label font-boldd tx-black"></label>
                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[18 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[18 - 1]->display }}</div>
                                            </div>
                                <input  class="form-control this_save " autocomplete="off" type="text" name="dauction" id="dacution_name" value="">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                     <div class="Terminal-error">
                                <label id="dauctionpho" class="label parsley-error font-boldd tx-black"></label>
                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[19 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[19 - 1]->display }}</div>
                                            </div>
                                <input  class="form-control this_save  ophone" autocomplete="off" type="text" name="dauctionpho" id="dacutionphoNo" value="">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                     <div class="Terminal-error">
                                <label id="dauctionpho" class="label parsley-error font-boldd tx-black">Shipment Number</label>
                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[19 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[19 - 1]->display }}</div>
                                            </div>
                                <input  class="form-control this_save  " autocomplete="off" type="text" name="dshipment_no" id="dacutionphoNo" value="">
                            </div>
                        </div>
                        
                        
                        <div class="col-lg-12">
                            <div class="form-group">
                                     <div class="Terminal-error">
                                <label id="dauctionpho" class="label parsley-error font-boldd tx-black">Doc Receipt Created By</label>
                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[19 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[19 - 1]->display }}</div>
                                            </div>
                              <select class="form-control  this_save"name="dockRec_createdBy" id="dockRec_createdBy" onkeypress="$(this).css('border-color', 'rgb(92 166 242)');">
                                               
                                                <option value="">Select an Option</option>
                                                <option value="us">Us</option>
                                                <option value="other">others</option>
                             </select>
                            </div>
                        </div>
                        
                      <div class="col-lg-12">
                            <div class="form-group">
                                     <div class="Terminal-error">
                                <label id="dauctionpho" class="label parsley-error font-boldd tx-black">Doc Receipt Company</label>
                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[19 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[19 - 1]->display }}</div>
                                            </div>
                                <input  class="form-control this_save  " autocomplete="off" type="text" name="dockRec_company" id="dockRec_company" value="">
                            </div>
                        </div>
                        
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="label font-boldd tx-black">Terminal</label>
                                <input class="form-control this_save " autocomplete="off" type="text" name="port_terminal" id="port_terminal" value="">
                            </div>
                        </div>
                    `);
                $(document).ready(function () {
                    $('#dockRec_company').parent().hide();
                    $('#dockRec_createdBy').change(function () {
                        var selectedValue = $(this).val();

                        if (selectedValue === 'other') {
                            $('#dockRec_company').parent().show();
                        } else {
                            $('#dockRec_company').parent().hide();
                        }
                    });
                });
            }
            else if (id == 2 || id == 3 || id == 4 || id == 10) {
                $(".dauc").html(`
                         <div class="col-lg-6">
                            <div class="form-group">
                                <label id="dauction" class="label font-boldd tx-black"></label>
                                <input required class="form-control this_save " autocomplete="off" type="text" name="dauction" id="dacution_name" value="" >
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label id="dauctionpho" class="label parsley-error font-boldd tx-black"></label>
                                <input required class="form-control this_save  ophone" autocomplete="off" type="text" name="dauctionpho" id="dacutionphoNo" value="" >
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                     <div class="Terminal-error">
                                <label id="dacutiondate" class="label parsley-error font-boldd tx-black">Auction Date <span class="text-muted">(Optional)</span></label>
                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[164 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[164 - 1]->display }}</div>
                                            </div>
                                <input class="form-control this_save" autocomplete="off" type="date"  name="dacutiondate" id="dacutiondate" value="">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                     <div class="Terminal-error">
                                <label id="dacutiontime" class="label parsley-error font-boldd tx-black">Auction Time <span class="text-muted">(Optional)</span></label>
                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[163 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[163 - 1]->display }}</div>
                                            </div>
                                <input class="form-control this_save" autocomplete="off" type="time"  name="dacutiontime" id="dacutiontime" value="">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                     <div class="Terminal-error">
                                <label for="dacutionaccounttitle" class="label parsley-error font-boldd tx-black">Has Auction Account?</label>
                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[22 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[22 - 1]->display }}</div>
                                            </div>
                                <select class="form-control this_save" autocomplete="off" name="dacutionaccounttitle" id="dacutionaccounttitle">
                                    <option value="" selected disabled>Select</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12" style="display:none;" id="daucAccName">
                            <div class="form-group">
                                     <div class="Terminal-error">
                                <label for="dacutionaccountname" class="label parsley-error font-boldd tx-black">Auction Account Name </label>
                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[23 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[23 - 1]->display }}</div>
                                            </div>
                                <input class="form-control this_save" autocomplete="off" type="text"  name="dacutionaccountname" id="dacutionaccountname" value="">
                            </div>
                        </div>
                    `);
            }
            else {
                $(".dauc").html(`
                         <div class="col-lg-6">
                            <div class="form-group">
                                     <div class="Terminal-error">
                                <label id="dauction" class="label font-boldd tx-black"></label>
                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[18 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[18 - 1]->display }}</div>
                                            </div>
                                <input required class="form-control this_save " autocomplete="off" type="text" name="dauction" id="dacution_name" value="" >
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                     <div class="Terminal-error">
                                <label id="dauctionpho" class="label parsley-error font-boldd tx-black"></label>
                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[19 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[19 - 1]->display }}</div>
                                            </div>
                                <input required class="form-control this_save  ophone" autocomplete="off" type="text" name="dauctionpho" id="dacutionphoNo" value="" >
                            </div>
                        </div>
                    `);

            }
            if (id == 5) {
                $("#dauction").html('Shop Name');
                $("#dauctionpho").html('Shop Phone');
            } else if (id == 7 || id == 6) {
                $("#dauction").html('Port Name');
                $("#dauctionpho").html('Port Phone');
                $(".dauc2").show();
            } else if (id == 11) {
                $("#dauction").html('Dealership / Contact Person');
                $("#dauctionpho").html('Dealership Phone');
            } else {
                $("#dauction").html('Auction Name');
                $("#dauctionpho").html('Auction Phone');
            }
        }


        var dterminal = $("#dterminal").val();
        if (dterminal == 2 || dterminal == 3 || dterminal == 4 || id == 10) {
            if (dterminal == 2) {
                $("#dacution_name").val("COPART Auto Auction");
            } else if (dterminal == 3) {
                $("#dacution_name").val("Manhein Auto Auction");
            } else if (dterminal == 4) {

                $("#dacution_name").val("IAAI Auto Auction");
            } else if (dterminal == 10) {

                $("#dacution_name").val("Auction (Heavy)");
            }
        }
        $(".ophone").keypress(function (e) {
            if ($(this).val() == '') {
                $(this).mask("(999) 999-9999");
            }
            var x = e.which || e.keycode;
            if ((x >= 48 && x <= 57) || (x >= 35 && x <= 40)) {
                return true;
            } else {
                return false;
            }
        })

    });

    $(document).on('change', '#dacutionaccounttitle', function () {
        $("#dacutionaccountname").val('');
        if ($(this).val() == 'Yes') {
            $("#daucAccName").show();
        }
        else {
            $("#daucAccName").hide();
        }
    })



    $("#oterminal").change(function () {
        $('#o_zip1').trigger('change');
        $(".buyer_number").hide();
        var oterminal = $("#oterminal").val();
        if (oterminal == 1) {
            $("#buyerPanel").hide();
            $(".buyer_number").hide();
        } else {
            $("#buyerPanel").show();
        }
        if (oterminal == 2 || oterminal == 3 || oterminal == 4 || oterminal == 10) {
            $("#oacution_name").val('');
            $("#oacutionphoNo").val('');
            $("#oaddress").val('');
            $(".buyer_number").hide();
        }
    });


    $("#oterminal").change(function () {
        var id = $(this).val();
        $(".stock_number").html('');
        if (id == 1) {
            $(".oauc").html('');
        }
        else if (id == 3 || id == 2 || id == 4 || id == 8) {
            $(".oauc").html('');
            $(".oauc").html(`
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label id="oacution" class="label font-boldd tx-black"></label>
                            <input class="form-control this_save" autocomplete="off"  type="text" name="oacution" id="oacution_name" value="">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label id="oacutionpho" class="label parsley-error font-boldd tx-black"></label>
                            <input class="form-control this_save ophone" autocomplete="off" type="text"  name="oacutionpho" id="oacutionphoNo" value="">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                                 <div class="Terminal-error">
                            <label id="oacutiondate" class="label parsley-error font-boldd tx-black">Auction Date <span class="text-muted">(Optional)</span></label>
                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[160 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[160 - 1]->display }}</div>
                                            </div>
                            <input class="form-control this_save" autocomplete="off" type="date"  name="oacutiondate" id="oacutiondate" value="">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                                 <div class="Terminal-error">
                            <label id="oacutiontime" class="label parsley-error font-boldd tx-black">Auction Time <span class="text-muted">(Optional)</span></label>
                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[162 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[162 - 1]->display }}</div>
                                            </div>
                            <input class="form-control this_save" autocomplete="off" type="time"  name="oacutiontime" id="oacutiontime" value="">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                                 <div class="Terminal-error">
                            <label for="oacutionaccounttitle" class="label parsley-error font-boldd tx-black">Has Auction Account?</label>
                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[11 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[11 - 1]->display }}</div>
                                            </div>
                            <select class="form-control this_save" autocomplete="off" name="oacutionaccounttitle" id="oacutionaccounttitle">
                                <option value="" selected disabled>Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12" style="display:none;" id="aucAccName">
                        <div class="form-group">
                            <label for="oacutionaccountname" class="label parsley-error font-boldd tx-black">Auction Account Name </label>
                            <input class="form-control this_save" autocomplete="off" type="text"  name="oacutionaccountname" id="oacutionaccountname" value="">
                        </div>
                    </div>
                     <div class="col-sm-12">
                        <div class="form-group">
                                 <div class="Terminal-error">
                            <label class="form-label">Buyer/Lot/Stock Number</label>
                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[70 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[70 - 1]->display }}</div>
                                            </div>
                            <input type="text" name="obuyer_no" id="obuyer_no" class="form-control this_save " autocomplete="off"
                            placeholder="Buyer/Lot/Stock Number">
                        </div>
                     </div>
                `);

        }
        else {
            $(".oauc").html('');
            $(".oauc").html(`
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label id="oacution" class="label font-boldd tx-black"></label>
                            <input class="form-control this_save" autocomplete="off"  type="text" name="oacution" id="oacution_name" value="">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label id="oacutionpho" class="label parsley-error font-boldd tx-black"></label>
                            <input class="form-control this_save ophone" autocomplete="off" type="text"  name="oacutionpho" id="oacutionphoNo" value="">
                        </div>
                    </div>
                     <div class="col-sm-12">
                        <div class="form-group">
                            <label class="form-label">Buyer/Lot/Stock Number</label>
                            <input type="text" name="obuyer_no" id="obuyer_no" class="form-control this_save " autocomplete="off"
                            placeholder="Buyer/Lot/Stock Number">
                        </div>
                     </div>
                    `);
            if (id == 5) {
                $("#oacution").html('Shop Name');
                $("#oacutionpho").html('Shop Phone');
            } else if (id == 7 || id == 6) {
                $(".oauc").html(``);
                $(".buyer_number").hide();
                $(".stock_number").html(`
                       <div class="">
                            <div class="form-group">
                            <label class="form-label">Buyer/Lot/Stock Number</label>
                            <input type="text" name="obuyer_no" id="obuyer_no" class="form-control this_save " autocomplete="off"
                            placeholder="Buyer/Lot/Stock Number">
                       </div>
                     </div>`);
            } else if (id == 10) {
                $("#oacution").html('Dealership / Contact Person');
                $("#oacutionpho").html('Dealership Phone');
                $(".buyer_number").hide();

            } else {
                $("#oacution").html('Auction Name');
                $("#oacutionpho").html('Auction Phone');
                $(".buyer_number").hide();
            }
        }


        var oterminal = $("#oterminal").val();
        if (oterminal == 2 || oterminal == 3 || oterminal == 4 || oterminal == 8) {
            if (oterminal == 2) {
                $("#oacution_name").val("COPART Auto Auction");
            } else if (oterminal == 3) {
                $("#oacution_name").val("Manhein Auto Auction");

            } else if (oterminal == 4) {
                $("#oacution_name").val("IAAI Auto Auction");
            } else if (oterminal == 8) {
                $("#oacution_name").val("Auction (Heavy)");
            }
        }
        $(".ophone").keypress(function (e) {
            if ($(this).val() == '') {
                $(this).mask("(999) 999-9999");
            }
            var x = e.which || e.keycode;
            if ((x >= 48 && x <= 57) || (x >= 35 && x <= 40)) {
                return true;
            } else {
                return false;
            }
        })


    });

    $(document).on('change', '#oacutionaccounttitle', function () {
        $("#oacutionaccountname").val('');
        if ($(this).val() == 'Yes') {
            $("#aucAccName").show();
        }
        else {
            $("#aucAccName").hide();
        }
    })

    function save_phon() {
        $('#show_total').show();
        var datastring = $("#createnew_form").serialize();
        $(document).ready(function () {
            var panel_type = $('#panel_type').val();
            console.log('panel_typepanel_type', panel_type);
        })
        console.log('datastringdatastring', datastring);
        var mainPhNum = $('#PhNum').val();

        if (mainPhNum.length > 0) {
            $.ajax({
                type: "post",
                url: "/createneworder",
                data: datastring,
                dataType: "json",
                success: function (data) {
                    console.log('datasss', data);
                    // console.log('datasss22', data.autoorder["nature_of_customer"]);
                    $('#ophone').val(data.autoorder["ophone"]);
                    $('#oemail').val(data.autoorder["oemail"]);
                    // $('#nature_of_customer').val(data.autoorder["nature_of_customer"]);
                    $('#ophone').parent('div').siblings('input[name="ophone2[]"]').val(data.autoorder["ophone"]);
                    $('#orderid_find').val(data.autoorder["id"]);
                    $('.orderID').val(data.autoorder["id"]);
                    $('#order_id1').val(data.autoorder["id"]);
                    $('#modaldemo8').modal('hide');
                    $('#ophone').focus();
                    $('#orderidplace').html('ORDER ON PHONE #' + data.autoorder["id"]);
                    $('#orderid').val(data.autoorder["id"]);
                    $('a[data-target="#alreadyCreditCard"]').html(`${data.count_credit_card} Card Found`);
                    $("#ophoneNumChk").html(`${data.old_count_previous}  Order Found`);

                    var mainPhNumGet = data.autoorder["ophone"];
                    var autoorderGet = data.autoorder["id"];
                    var phoneGet = data.autoorder["ophone"];

                    console.log(mainPhNumGet, 'mainPhNumGet');
                    console.log(autoorderGet, 'autoorderGet');

                    // Make the second Ajax request here
                    $.ajax({
                        type: "get",
                        url: "/getPhoneCard",
                        data: {
                            'mainPhNumGet': mainPhNumGet,
                            'autoorderGet': autoorderGet,
                        },
                        dataType: "json",
                        success: function (data) {
                            console.log("mainPhNum", data);

                            var hiddenPhone = phoneGet.replace(/^(\D*\d\D*){7}/, function (match) {
                                return match.replace(/\d/g, 'x');
                            });

                            if (Array.isArray(data.credit_card_data) && data.credit_card_data.length > 0) {
                                // Clear existing content or do any necessary setup
                                $("#creditCardTable").empty();

                                // Create a header row
                                var headerRow = $("<tr></tr>");
                                headerRow.append("<th style=''>Sr.#</th>");
                                headerRow.append("<th style='padding-left: 25px; padding-right: 15px;'>Order ID</th>");
                                headerRow.append("<th style='padding-left: 25px; padding-right: 15px;'>Type</th>");
                                headerRow.append("<th style='padding-left: 25px; padding-right: 15px;'>Card</th>");
                                headerRow.append("<th style='padding-left: 25px; padding-right: 15px;'>Card Expire</th>");
                                headerRow.append("<th style='padding-left: 25px; padding-right: 15px;'>Phone</th>");
                                // Add more headers as needed

                                // Append the header row to the table
                                $("#creditCardTable").append(headerRow);

                                console.log('credit_card_data', data.credit_card_data.length);

                                // Initialize serial number
                                var serialNumber = 1;

                                $.each(data.credit_card_data, function (index, val) {
                                    // Check if the required properties exist
                                    // if (val.card_no && val.card_type && val.card_expiry_date) {
                                    // var cards = val.card_no.split("*^");
                                    // var card_type = val.card_type.split("*^");
                                    // var card_expire = val.card_expiry_date.split("*^");
                                    // var cards;
                                    var card_type;
                                    var card_expire;
                                    // if (val.card_no) {
                                    //     cards = val.card_no.split("*^");
                                    // } else {
                                    //     cards = '-';
                                    // }
                                    if (val.card_type) {
                                        card_type = val.card_type.split("*^");
                                    } else {
                                        card_type = '-';
                                    }
                                    if (val.card_expiry_date) {
                                        card_expire = val.card_expiry_date.split("*^");
                                    } else {
                                        card_expire = '-';
                                    }

                                    console.log('cardscardsLength', val);

                                    // cards.forEach(function (card, key) {
                                    // Create a new row for each card
                                    var row = $("<tr></tr>");

                                    // Add the serial number
                                    row.append("<td>" + serialNumber + "</td>");

                                    row.append("<td style='padding-right: 15px;'><a href='/searchData?search=" + val.orderId + "'>OrderId#" + val.orderId + "</a></td>");
                                    if (card_type == 'visa') {
                                        row.append("<td style='padding-left: 25px; padding-right: 15px;'><img src='{{ asset('visa.png') }}' style='margin: 11px; padding: 0px; height: 30px' alt=''></td>");
                                    } else {
                                        row.append("<td style='padding-left: 25px; padding-right: 15px;'><img src='{{ asset('master.png') }}' style='margin: 11px; padding: 0px; height: 30px' alt=''></td>");
                                    }
                                    if (val.card_no) {
                                        var cardParts = val.card_no.split(",");
                                        var maskedPart = cardParts[0].trim(); // Assuming the masked part is the first part

                                        // Split the last four digits from the unmasked part
                                        var lastFourDigits = maskedPart.slice(-4);

                                        row.append("<td style='padding-left: 25px; padding-right: 15px;'>" + (lastFourDigits ? 'xxxx - xxxx - xxxx -' + lastFourDigits : '') + "</td>");
                                    } else {
                                        row.append("<td style='padding-left: 25px; padding-right: 15px;'>-</td>");
                                    }
                                    row.append("<td style='padding-left: 25px; padding-right: 15px;'>" + card_expire + "</td>");
                                    row.append("<td style='padding-left: 25px; padding-right: 15px;'>" + hiddenPhone + "</td>");

                                    // Increment the serial number for the next row
                                    serialNumber++;

                                    // Append the row to the table
                                    $("#creditCardTable").append(row);
                                    // });
                                    // }
                                });


                            } else {
                                // Display a message if there is no data
                                $("#creditCardTable").html("<p>No credit card data available.</p>");
                            }
                        },
                        error: function (e) {
                            console.error("Error in AJAX request:", e);
                        },
                    });
                },
                error: function (e) { }
            });
        } else {
            alert('PLEASE ENTER PHONE NUMBER');
        }
    }

    function save_customer() {
        $('#show_total').show();
        var datastring = $("#createnew_form").serialize();
        var panel_type = $('select[name="panel_type"]').val();
        // console.log('panel_typepanel_type', panel_type);
        $(document).ready(function () {
            var panel_type = $('#panel_type').val();
            console.log('panel_typepanel_type', panel_type);
        })
        console.log('datastringdatastring22', datastring);
        var customernameNum = $('#custName').val();

        if (customernameNum.length > 0) {
            $.ajax({
                type: "post",
                url: "/createneworder",
                data: datastring,
                dataType: "json",
                success: function (data) {
                    $('#ophone').val(data["ophone"]);
                    $('#orderid_find').val(data["id"]);
                    $('#order_id1').val(data["id"]);
                    $('#modaldemo8').modal('hide');
                    $('#ophone').focus();
                    $('#orderidplace').html('ORDER ON PHONE #' + data["id"]);
                    $('#orderid').val(data["id"]);
                    $('#oname').val(data["oname"]);
                    $('#addition_info0').val(data["add_info"]);
                },
                error: function (e) {
                }
            });
        } else {
            alert('PLEASE ENTER CUSTOMER NAME');
        }
    }

    function get_pstatus(id) {
        var ret = "";
        if (id == 0) {
            ret = "NEW";
        } else if (id == 1) {
            ret = "Interested";
        } else if (id == 2) {
            ret = "FollowMore";
        } else if (id == 3) {
            ret = "AskingLow";
        } else if (id == 4) {
            ret = "NotInterested";
        } else if (id == 5) {
            ret = "NoResponse";
        } else if (id == 6) {
            ret = "TimeQuote";
        } else if (id == 7) {
            ret = "PaymentMissing";
        } else if (id == 8) {
            ret = "Booked";
        } else if (id == 9) {
            ret = "Listed";
        } else if (id == 10) {
            ret = "Dispatch";
        } else if (id == 11) {
            ret = "Pickup";
        } else if (id == 12) {
            ret = "Delivered";
        } else if (id == 13) {
            ret = "Completed";
        } else if (id == 14) {
            ret = "Cancel";
        } else if (id == 15) {
            ret = "Deleted";
        } else if (id == 16) {
            ret = "OwesMoney";
        } else if (id == 17) {
            ret = "CarrierUpdate";
        } else if (id == 18) {
            ret = "OnApproval";
        } else if (id == 19) {
            ret = "On Approval Canceled";
        }

        return ret;
    }

    function phone_check(phone_no) {
        var phonelenght = phone_no.replace(/[^0-9]/g, "").length;
        if (phonelenght == 10) {
            $("#create_new").show();
            $('#last_5').show();
            $.ajax({
                type: "GET",
                url: "/get_order",
                data: { 'phone_no': phone_no },
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, item) {
                        if (item.tot > 0) {
                            $("#update_previous").show();
                            $(".show_hide").show();
                        } else {
                            $("#update_previous").hide();
                            $(".show_hide").hide();
                        }
                        $('#show_total').html(item.tot + ' Order(s) found');
                    });
                },
                error: function (e) {
                }
            });
            $.ajax({
                type: "GET",
                url: "/get_last_5",
                data: { 'phone_no': phone_no },
                dataType: "json",
                success: function (data) {
                    var temp = "";
                    $.each(data, function (i, item) {


                        temp = temp + `<tbody><tr><td>` + item.id + `</td>`;
                        temp = temp + `<td>` + item.date + `</td>`;
                        temp = temp + `<td>` + item.oname + `</td>`;
                        temp += '<td>';
                        if (item.email) {
                            temp += 'Email: ' + item.email + '<br>';
                        }
                        if (item.oemail) {
                            temp += 'Origin Email: ' + item.oemail + '<br>';
                        }
                        if (item.demail) {
                            temp += 'Dest Email: ' + item.demail;
                        }
                        temp += '</td>';
                        temp = temp + `<td>` + item.originzsc + `</td>`;
                        temp = temp + `<td>` + item.destinationzsc + `</td>`;
                        temp = temp + `<td>` + item.ymk + `</td>`;
                        temp = temp + `<td>` + item.payment + `</td>`;
                        temp = temp + `<td>` + get_pstatus(item.pstatus) + `</td>`;
                        temp = temp + `<td><a target='_blank' href='/new_edit/${item.id}' class="badge bg-success text-light">Edit</a></td></tr></tbody>`;
                    });
                    $('#last_5').html(`<div class='container' style="overflow:auto;">
                            <table class='table table-bordered table-hover' style='text-align: center; '>
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Origin</th>
                                    <th>Destination</th>
                                    <th>Vehicle</th>
                                    <th>Price</th>
                                    <th>Last Status</th>
                                    <th>Action</th>
                                    </tr>
                                </thead>
                                ${temp}
                             </table></div>`);
                },
                error: function (e) {
                }
            });
        } else {
            $("#create_new").hide();
            $("#update_previous").hide();
            $("#show_total").html('');
            $('#last_5').hide();
        }
    }

    function phone_check22(phone_no) {
        $.ajax({
            url: "/get_order",
            type: "GET",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
            },
            success: function (data) {
                let test = data.toString();
                let test2 = $.trim(test);
                let text = "SUCCESS";
                if (test2 == text) {
                    $('#success').html(data);
                    $('#modaldemo4').modal('show');
                    window.location.href = "/view_employee";
                } else {
                    $('#not_success').html(data);
                    $('#modaldemo5').modal('show');
                }
            },
            error: function (e) {
                $("#err").html(e).fadeIn();
            }
        });
        var showPass = 0;
        showPass = $('#PhNum').val();
        var len = showPass.length;
        if (len >= 10) {
            $('#create_new').show();
            $('#update_previous').show();
        } else {
            $('#create_new').hide();
            $('#update_previous').hide();
        }
    }
</script>
<script>
    $('#modaldemo8').modal('show');
    $(document).on('click', '.btn_remove', function () {
        $(this).parents(`.vehicle_add`).remove();
        vehicle_count--;
    });

    $(document).on('click', '#yes', function () {
        $('#custName').val('');
        $('#addInfo').val('');
        $('#show_total').show();
        $('#show_total').html('');
        $(".number_no").hide();
        $(".number_yes").show();
        $("#create_new_unknownno").css("display", "none");
    });
    $(document).on('click', '#no', function () {
        $('#PhNum').val('');
        $('#create_new').hide();
        $('#update_previous').hide();
        $('#show_total').hide();
        $(".number_yes").hide();
        $(".number_no").show();
        $("#create_new_unknownno").css("display", "block");
    });

    $('#PhNum').keypress(function (e) {
        var x = e.which || e.keycode;
        if ((x >= 48 && x <= 57) || x == 8 ||
            (x >= 35 && x <= 40) || x == 46)
            return true;
        else
            return false;
    });

    function showcreatenew() {
        $('.unknownno_class').html('');
        $('.unknownno_class').append(`<button class="btn btn-indigo" style="" id="create_new_unknownno" onclick="save_customer();" style="" type="button">Create New</button>`);
    }
</script>
<script type="text/javascript">
    var Ophone_count = 0;
    var Dphone_count = 0;
    var vehicle_count = 2;

    $(".add_phone_btn").click(function () {
        $(".add_phone").append('<div class="col-12 add margin_lft"><div class="row"><div class="col-11"><label class="form-label">Phone Number: </label><input  type="text" autocomplete="off" name="ophone[]"  class="form-control this_save ophone ophone_new" id="ophonee' + Ophone_count + '" placeholder="Phone Number"/></div><div class"form-group col-1" style="padding-top: 23px;"> <i id="remove_btn" class="fa fa-minus-circle remove_btn"></i></div><input type="hidden" value="" name="ophone2[]" /> </div></div>	&nbsp;');
        ++Ophone_count;
        $(".ophone").keypress(function (e) {
            if ($(this).val() == '') {
                $(this).mask("(999) 999-9999");
            }
            var x = e.which || e.keycode;
            if ((x >= 48 && x <= 57) || (x >= 35 && x <= 40)) {
                return true;
            } else {
                return false;
            }
        })

        $(".ophone_new").keyup(function () {
            $(this).parent('div').siblings('input').val($(this).val());
        })
    });

    $(".ophone_new").keyup(function () {
        $(this).parent('div').siblings('input').val($(this).val());
    })

    $(document).on('click', '.remove_btn', function () {
        $(this).parents('.add').remove();
        --Ophone_count;
    });

    $(".add_dphone_btn").click(function () {
        $(".add_dphone").append('<div class="col-12 add margin_lft"><div class="row"><div class="col-11"><label class="form-label">Phone Number:</label><input  type="text" name="dphone[]" autocomplete="off"  class="form-control this_save dphone ophone_new" id="phonee' + Dphone_count + '" placeholder="Phone Number"/></div><div class"form-group col-1" style="padding-top: 23px;"> <i id="remove_btn" class="fa fa-minus-circle remove_btn"></i></div><input type="hidden" value="" name="dphone2[]" />  </div></div>	&nbsp;');
        ++Dphone_count;
        $(".dphone").keypress(function (e) {
            var x = e.which || e.keycode;
            if ((x >= 48 && x <= 57) || (x >= 35 && x <= 40)) {
                return true;
            } else {
                return false;
            }
        })

        $(".ophone_new").keyup(function () {
            $(this).parent('div').siblings('input').val($(this).val());
        })
    });

    $(document).on('click', '.remove_btn', function () {
        $(this).parents('.add').remove();
        --Dphone_count;
    });

    $(`.add_vehicle_btn`).click(function () {
        $(`.add_vehicle_information`).append(`
                    <input type='hidden' name='count[]' value='1'>
                    <div class='vehicle_add'>
                        <div class=' flex_ gap_new flex_space vichle__Information '>
                            <div class='vichle__Information--box'>
                                <div class=''>
                                    <label class='rdiobox'>
                                        <input class='this_save type' name='vehicle${vehicle_count}'
                                               id='vehicle${vehicle_count}' onclick='vehicle_append(${vehicle_count})'
                                               type='radio' checked='' value='1'
                                               data-parsley-multiple='vehicle${vehicle_count}'>
                                        <input name='vehicle_v[]' id='vehicle_v${vehicle_count}' type='hidden' value='make'>
                                             <div class="Terminal-error">
                                        <span>Year, Make, and Model</span>
                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[165 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[165 - 1]->display }}</div>
                                            </div>
                                    </label>
                                </div>
                            </div>
                            <div class='vichle__Information--box'>
                                <div class=''>
                                    <label class='rdiobox'> <input class='this_save type' name='vehicle${vehicle_count}'
                                                                                       id='vin${vehicle_count}'
                                                                                       onclick='vin_append(${vehicle_count})' type='radio'
                                                                                       value='2'
                                                                                       data-parsley-multiple='vehicle${vehicle_count}'>
                                        <input name='vehicle_v[]' disabled id='vehicle_v_vin${vehicle_count}' type='hidden' value='vin'>
                                             <div class="Terminal-error">
                                        <span>Vin Number</span>
                                             <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                                    style="cursor: pointer;"></i>
                                            </div>

                                            <div class="popoverContent" style="display: none;">
                                                <div class="popover-title">{{ $label[165 - 1]->name }}</div>
                                                <div class="popover-content">{{ $label[165 - 1]->display }}</div>
                                            </div>
                                    </label>
                                </div>
                            </div>
                            <div class='vichle__Information--box btn-list'>
                                <button style='' type='button' class='btn btn-danger btn_remove'>
                                    <i class='mr-2' id='add-more' onclick='vehicleUpdate(1)'></i>Remove
                                </button>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-sm-12 col-md-12 vin_toggle${vehicle_count}'>

                            </div>
                            <div class='col-sm-6 col-md-6'>
                                <div class='form-group'><label class=' form-label font-boldd'>Year<span class="redcolor">*</span></label>
                                    <input type='text' class='form-control this_save vyear' id='year${vehicle_count}' name='vyear[]' onkeypress="$(this).css('border-color', 'rgb(92 166 242)');" placeholder='Enter Year' required >
                                </div>
                            </div>
                            <div class='col-sm-6 col-md-6'>
                                <div class='form-group'><label class=' form-label font-boldd'>Make<span class="redcolor">*</span></label>
                                    <input type='text' class='form-control this_save  makeOpt0 vmake' id='makeOpt${vehicle_count}'
                                           onkeyup='getmake()' name='vmake[]' onkeypress="$(this).css('border-color', 'rgb(92 166 242)');"
                                           placeholder='Enter Make' required>
                                </div>
                            </div>
                            <div class='col-sm-6 col-md-6'>
                                <div class='googleimage' onclick='googl(${vehicle_count})' id='googleimage${vehicle_count}'
                                     style='position: absolute; right: 3%;top:-6px;display:none'><a href='javascript:void(0);'>
                                        <img width='50' src='{{url('')}}/assets/images/png/google.png' style="border: 1px solid #5da6f2;border-radius: 5px;"></a>
                                </div>
                                <div class='form-group'><label class=' form-label font-boldd'>Model<span class="redcolor">*</span></label>
                                    <input type='text'
                                           id='model${vehicle_count}'
                                           onkeyup='getmodel(${vehicle_count})'
                                           name='vmodel[]'
                                           class='form-control this_save  model0 vmodel'
                                           onkeypress="$(this).css('border-color', 'rgb(92 166 242)');"
                                           placeholder='Enter Model' required>
                                </div>
                            </div>
                            <div class='col-sm-6 col-md-6'>
                                <div class='form-group'><label class=' form-label font-boldd'>Vehicle Type</label>
                                    <select
                                            id='vehType${vehicle_count}'
                                            name='vehType[]'
                                            class='form-control this_save vehicle-type'>
                                        <option selected='' value=''>Select Type</option>
                                        <option value='Car'>Car</option>
                                        <option disabled=''>————————————</option>
                                        <option value='motorcycle'>Motorcycle</option>
                                        <option value='3_wheel_sidecar'>3 Wheel Sidecar</option>
                                        <option value='3_wheel_motorcycle'>3 Wheel Motorcycle</option>
                                        <option value='atv'>ATV</option>
                                        <option disabled=''>————————————</option>
                                        <option value='SUV'>SUV</option>
                                        <option value='Mid SUV'>Mid SUV</option>
                                        <option value='Large SUV'>Large SUV</option>
                                        <option disabled=''>————————————</option>
                                        <option value='Van'>Van</option>
                                        <option value='Mini Van'>Mini Van</option>
                                        <option value='Cargo Van'>Cargo Van</option>
                                        <option value='Passenger Van'>Passenger Van</option>
                                        <option disabled=''>————————————</option>
                                        <option value='Pickup'>Pickup</option>
                                        <option value='Pickup Dually'>Pickup Dually</option>
                                        <option value='Box Truck Dually'>Box Truck Dually</option>
                                        <option disabled=''>————————————</option>
                                        <option value='other_vehicle'>Other Vehicle</option>
                                        <option value='other_motorcycle'>Other Motorcycle</option>
                                    </select>
                                </div>
                            </div>
                            <div class='col-sm-6 col-md-6'>
                                <div class='form-group'><label class=' form-label font-boldd'>Vehicle Condition</label>
                                    <select  id='condition${vehicle_count}'
                                            onchange="condition_change(this.value,$(this).attr('id'))"
                                            name='condition[]' class='form-control this_save vehicle-condition'>
                                        <option selected='' value=''>Select</option>
                                        <option value='1'>Running</option>
                                        <option value='2'>Not Running</option>
                                    </select>
                                </div>
                            </div>
                            <div class='col-sm-6 col-md-6'>
                                <div class='form-group'><label class=' form-label font-boldd'>Trailer Type</label>
                                    <select id='trailter_type${vehicle_count}' name='trailter_type[]'
                                            class='form-control this_save trailer-type'>
                                        <option selected='' value=''>Select</option>
                                        <option value='1'>Open</option>
                                        <option value='2'>Enclosed</option>
                                    </select>
                                </div>
                            </div>

                              <div class="col-sm-12 col-md-12">
                                    <div id="vehicle_condition${vehicle_count}" style="display: flex; width: 100%;">
                                    </div>
                              </div>

                            <div class='col-sm-6 col-md-6'> &nbsp;
                                <div class='form-group'><label class='ckbox'>
                                        <input type='checkbox' name='portTitle${vehicle_count}' id='needTitle${vehicle_count}' onclick='goto_port(${vehicle_count})' class='this_save'>
                                        <input type='hidden' id='portTitlehidden${vehicle_count}' name='portTitlehidden[]' value='false'><span>&nbsp;Need Title?</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>&nbsp;`);
        vehicle_count++;
        selectRefresh();

        resetVehilce();
    });

    $("#central_chk1").click(function () {
        $("#may_be_book").html('');
        $("#confirm_book").html(`
                <input style="width: 150px;" class="form-control this_save" autocomplete="off" type="text" name="company_name" id="company_name" placeholder="Company Name">
                <input style="width: 150px;" class="form-control this_save" autocomplete="off" type="text" name="company_price" id="company_price" placeholder="Price">
            `);
    });

    $("#central_chk2").click(function () {
        $("#confirm_book").html('');
        $("#may_be_book").html(`
                <input  style="width: 150px;" class="form-control this_save" autocomplete="off" type="text" name="company_name" id="company_name2" placeholder="Company Name">
                <input  style="width: 150px;" class="form-control this_save" autocomplete="off" type="text" name="company_price" id="company_price2" placeholder="Price">
                <input  style="width: 150px;" class="form-control this_save" autocomplete="off" type="text" name="company_comments" id="company_comments" placeholder="Comments">
            `);
    });

    $("#central_chk3").click(function () {
        $("#confirm_book").html('');
        $("#may_be_book").html('');
    });

    function vin_append(vehicle_count) {
        $(`#vehicle_v_vin${vehicle_count}`).prop("disabled", false);
        $(`#vehicle_v${vehicle_count}`).prop("disabled", true);
        $(`.vin_show${vehicle_count}`).show();
        $(`.vin_toggle${vehicle_count}`).html(`
                <div class="input-group vin_show${vehicle_count}" style="padding-bottom: 12px;padding-top: 19px;">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="fa fa-car tx-16 lh-0 op-6"></i>
                        </div>
                    </div>
                    <input required class="form-control this_save" type="text" onkeyup="get_vin(${vehicle_count})" name="vin_num[]" id="vinNum${vehicle_count}" value=""
                    placeholder="Ex: WBSWL93558P331570" style="width: 80%;">
                </div> `);
        $(`#year${vehicle_count}`).prop("readonly", true);
        $(`#makeOpt${vehicle_count}`).prop("readonly", true);
        $(`#model${vehicle_count}`).prop("readonly", true);
    }

    function vehicle_append(vehicle_count) {
        $(`#vehicle_v_vin${vehicle_count}`).prop("disabled", true);
        $(`#vehicle_v${vehicle_count}`).prop("disabled", false);
        $(`.vin_show${vehicle_count}`).html('');
        $(`#year${vehicle_count}`).prop("readonly", false);
        $(`#makeOpt${vehicle_count}`).prop("readonly", false);
        $(`#model${vehicle_count}`).prop("readonly", false);
    }

    $("#viewMap").click(function () {
        var ozip = $("#o_zip1").val();
        var dzip = $("#d_zip1").val();
        if (ozip == '' || dzip == '') {
            alert('Please Enter Origin & Dest City or Zip');
        }
        else {
            // ozip = ozip.split(",");
            // dzip = dzip.split(",");
            var url = `https://www.google.com/maps/dir/${ozip}/${dzip}/`;
            $(this).attr('href', url);
            // window.open(url, 'Map', 'height=700,width=800,left=200,top=50,toolbar=No,location=No,scrollbars=Yes,status=No,resizable=Yes,fullscreen=No,directories=No,menubar=No,copyhistory=No');
        }
    });
</script>
<script>
    $("body").delegate(".ophone", "focus", function () {
        $(".ophone").mask("(999) 999-9999");
        $(".ophone")[0].setSelectionRange(0, 0);
    });

    $("body").delegate(".dphone", "focus", function () {
        $(".dphone").mask("(999) 999-9999");
        $(".dphone")[0].setSelectionRange(0, 0);
    });

    $(document).on('click', '#ophonee', function () {
        $("#ophonee").mask("(999) 999-9999");
        $("#ophonee")[0].setSelectionRange(0, 0);

    });

    $(document).on('click', '#ophonee', function () {
        $("#ophonee").mask("(999) 999-9999");
        $("#ophonee")[0].setSelectionRange(0, 0);

    });

    $(".ophone").keypress(function (e) {
        if ($(this).val() == '') {
            $(this).mask("(999) 999-9999");
        }
        var x = e.which || e.keycode;
        if ((x >= 48 && x <= 57) || (x >= 35 && x <= 40)) {
            return true;
        } else {
            return false;
        }
    })
    $(".dphone").keypress(function (e) {
        var x = e.which || e.keycode;
        if ((x >= 48 && x <= 57) || (x >= 35 && x <= 40)) {
            return true;
        } else {
            return false;
        }
    })


    $(document).on('change', '#o_zip1', function () {


        setTimeout(function () {

            var o_zip1 = $(`#o_zip1`).val();
            var oterminal = $(`#oterminal`).val();

            if (oterminal == 2) {
                oterminal = 1;

                $.ajax({
                    type: "GET",
                    url: "/get_auction",
                    dataType: 'JSON',
                    data: { o_zip1: o_zip1, oterminal: oterminal },
                    success: function (res) {
                        if (res) {
                            $('#oaddress').val(res[0].address);
                            $('#oacutionphoNo').val(res[0].phone);
                            $('#oacution_name').val('Copart Auction');
                            $('#o_zip1').focus();

                        }
                    }

                });
            }
        }, 500);


    });

    $(document).on('change', '#d_zip1', function () {
        setTimeout(function () {
            var d_zip1 = $(`#d_zip1`).val();
            var dterminal = $(`#dterminal`).val();
            $('#port_lines').prop('style', 'display: none;');
            $("#port_line1").prop("checked", false);
            $("#port_line2").prop("checked", false);
            $("#port_dock_type").prop("selectedIndex", 0).change();
            if (dterminal == 2) {
                dterminal = 1;
                $.ajax({
                    type: "GET",
                    url: "/get_auction",
                    dataType: 'JSON',
                    data: { o_zip1: d_zip1, oterminal: dterminal },
                    success: function (res) {
                        if (res) {
                            $('#daddress').val(res[0].address);
                            $('#dacutionphoNo').val(res[0].phone);
                            $('#dacution_name').val('Copart Auction');
                            $('#d_zip1').focus();

                        }
                    }
                });
            } else if (dterminal == 7) {
                $('#reason_box').val(null);
                $('#port_lines').prop('style', 'display: block; width: 100%;"');
            }
        }, 500);

    });

    function putX(digits, status, num) {
        let val = num;

        if (status === 0) {
            switch (digits) {
                case 0:
                    val = num;
                    break;
                case 1:
                    val = `(x${num.slice(-12)}`;
                    break;
                case 2:
                    val = `(xx${num.slice(-11)}`;
                    break;
                case 3:
                    val = `(xxx) ${num.slice(-8)}`;
                    break;
                case 4:
                    val = `(xxx) x${num.slice(-7)}`;
                    break;
                case 5:
                    val = `(xxx) xx${num.slice(-6)}`;
                    break;
                case 6:
                    val = `(xxx) xxx-${num.slice(-4)}`;
                    break;
                case 7:
                    val = `(xxx) xxx-x${num.slice(-3)}`;
                    break;
                case 8:
                    val = `(xxx) xxx-xx${num.slice(-2)}`;
                    break;
                case 9:
                    val = `(xxx) xxx-xxx${num.slice(-1)}`;
                    break;
                case 10:
                    val = `(xxx) xxx-xxxx`;
                    break;
            }
        } else if (status === 1) {
            switch (digits) {
                case 0:
                    val = num;
                    break;
                case 1:
                    val = `${num.slice(0, 13)}x`;
                    break;
                case 2:
                    val = `${num.slice(0, 12)}xx`;
                    break;
                case 3:
                    val = `${num.slice(0, 11)}xxx `;
                    break;
                case 4:
                    val = `${num.slice(0, 10)}xxxx`;
                    break;
                case 5:
                    val = `${num.slice(0, 8)}x-xxxx`;
                    break;
                case 6:
                    val = `${num.slice(0, 7)}xx-xxxx`;
                    break;
                case 7:
                    val = `${num.slice(0, 6)}xxx-xxxx`;
                    break;
                case 8:
                    val = `${num.slice(0, 3)}x) xxx-xxxx`;
                    break;
                case 9:
                    val = `${num.slice(0, 2)}xx) xxx-xxxx`;
                    break;
                case 10:
                    val = `(xxx) xxx-xxxx`;
                    break;
            }
        } else if (status === 2) {
            switch (digits) {
                case 0:
                    val = num;
                    break;
                case 1:
                    val = `${num.slice(0, 7)}x${num.slice(-6)}`;
                    break;
                case 2:
                    val = `${num.slice(0, 7)}xx${num.slice(-5)}`;
                    break;
                case 3:
                    val = `${num.slice(0, 6)}xxx${num.slice(-5)}`;
                    break;
                case 4:
                    val = `${num.slice(0, 3)}x) xxx${num.slice(-5)}`;
                    break;
                case 5:
                    val = `${num.slice(0, 3)}x) xxx-x${num.slice(-3)}`;
                    break;
                case 6:
                    val = `${num.slice(0, 3)}x) xxx-xx${num.slice(-2)}`;
                    break;
                case 7:
                    val = `${num.slice(0, 2)}xx) xxx-xx${num.slice(-2)}`;
                    break;
                case 8:
                    val = `${num.slice(0, 2)}xx) xxx-xxx${num.slice(-1)}`;
                    break;
                case 9:
                    val = `(xxx) xxx-xxx${num.slice(-1)}`;
                    break;
                case 10:
                    val = `(xxx) xxx-xxxx`;
                    break;
            }
        }

        console.log('val', digits, status, num);

        return val;
    }


    //=================onchange-values=============================
    $(document).ready(function () {
        // Select all error icons within the document
        var $errorIcons = $('.Terminal-error i');
        var $openPopoverContent = null;

        // Iterate over each error icon
        $errorIcons.each(function () {
            var $errorIcon = $(this);
            var $popoverContent = $errorIcon.closest('.Terminal-error').siblings('.popoverContent');

            // Toggle the popover on icon click
            $errorIcon.on('click', function (event) {
                event.stopPropagation(); // Prevent the document click event from firing immediately

                // Close the previously open popover content
                if ($openPopoverContent && !$openPopoverContent.is($popoverContent)) {
                    $openPopoverContent.hide();
                }

                // Toggle the current popover content
                $popoverContent.toggle();
                $openPopoverContent = $popoverContent;
            });
        });

        // Close the popover if clicked outside
        // $(document).on('click', function(event) {
        $(document).on('click', '.Terminal-error i', function (event) {
            if ($openPopoverContent && !$errorIcons.is(event.target) && !$openPopoverContent.is(event.target) && $openPopoverContent
                .has(event.target).length === 0) {
                $openPopoverContent.hide();
                $openPopoverContent = null;
            }
        });
    });

    // $(document).ready(function() {
    //     // Select all error icons within the document
    //     var $errorIcons = $('.Terminal-error i');

    //     // Iterate over each error icon
    //     $errorIcons.each(function() {
    //         var $errorIcon = $(this);
    //         var $popoverContent = $errorIcon.closest('.Terminal-error').siblings('.popoverContent');

    //         // Toggle the popover on icon click
    //         $errorIcon.on('click', function(event) {
    //             event.stopPropagation(); // Prevent the document click event from firing immediately
    //             $popoverContent.toggle();
    //         });

    //         // Close the popover if clicked outside
    //         $(document).on('click', function(event) {
    //             if (!$errorIcon.is(event.target) && !$popoverContent.is(event.target) && $popoverContent
    //                 .has(event.target).length === 0) {
    //                 $popoverContent.hide();
    //             }
    //         });
    //     });
    // });

    $('#oterminal').on('change', function () {
        $(".oterminal-none").css("display", "block");
        var oterminalselectedOption = $(this).val();
        var selectedOptionText = $('#oterminal option:selected').text();
        // Update the label with the selected option
        $('#selectedOptionLabel').text(selectedOptionText);
        if (oterminalselectedOption == 1) {
            $('#change_oterminal_name').html('{{ $label[106 - 1]->name }}');
            $('#change_oterminal_display').html('{{ $label[106 - 1]->display }}');
        }
        else if (oterminalselectedOption == 2) {
            $('#change_oterminal_name').html('{{ $label[107 - 1]->name }}');
            $('#change_oterminal_display').html('{{ $label[107 - 1]->display }}');
        }
        else if (oterminalselectedOption == 3) {
            $('#change_oterminal_name').html('{{ $label[108 - 1]->name }}');
            $('#change_oterminal_display').html('{{ $label[108 - 1]->display }}');
        }
        else if (oterminalselectedOption == 4) {
            $('#change_oterminal_name').html('{{ $label[109 - 1]->name }}');
            $('#change_oterminal_display').html('{{ $label[109 - 1]->display }}');
        }
        else if (oterminalselectedOption == 5) {
            $('#change_oterminal_name').html('{{ $label[110 - 1]->name }}');
            $('#change_oterminal_display').html('{{ $label[110 - 1]->display }}');
        }
        else if (oterminalselectedOption == 10) {
            $('#change_oterminal_name').html('{{ $label[111 - 1]->name }}');
            $('#change_oterminal_display').html('{{ $label[111 - 1]->display }}');
        }
        else if (oterminalselectedOption == 7) {
            $('#change_oterminal_name').html('{{ $label[112 - 1]->name }}');
            $('#change_oterminal_display').html('{{ $label[112 - 1]->display }}');
        }
        else if (oterminalselectedOption == 8) {
            $('#change_oterminal_name').html('{{ $label[113 - 1]->name }}');
            $('#change_oterminal_display').html('{{ $label[113 - 1]->display }}');
        }
        else if (oterminalselectedOption == 6) {
            $('#change_oterminal_name').html('{{ $label[114 - 1]->name }}');
            $('#change_oterminal_display').html('{{ $label[114 - 1]->display }}');
        }
        // else if (oterminalselectedOption == 10) 
        // {
        //     $('#change_oterminal_name').html('{{ $label[124 - 1]->name }}');
        //     $('#change_oterminal_display').html('{{ $label[124 - 1]->display }}');
        // }
        // else if (oterminalselectedOption == 8) 
        // {
        //     $('#change_oterminal_name').html('{{ $label[125 - 1]->name }}');
        //     $('#change_oterminal_display').html('{{ $label[125 - 1]->display }}');
        // }
    });


    $('#dterminal').on('change', function () {
        $(".dterminal-none").css("display", "flex");
        var dterminalSelectedOption = $(this).val();
        var dterminalSelectedOptionText = $('#dterminal option:selected').text();
        // Update the label with the selected option
        $('#selectedOptionLabel2').text(dterminalSelectedOptionText);

        if (dterminalSelectedOption == 1) {
            $('#change_dterminal_name').html('{{ $label[115 - 1]->name }}');
            $('#change_dterminal_display').html('{{ $label[115 - 1]->display }}');
        }
        else if (dterminalSelectedOption == 2) {
            $('#change_dterminal_name').html('{{ $label[116 - 1]->name }}');
            $('#change_dterminal_display').html('{{ $label[116 - 1]->display }}');
        }
        else if (dterminalSelectedOption == 3) {
            $('#change_dterminal_name').html('{{ $label[117 - 1]->name }}');
            $('#change_dterminal_display').html('{{ $label[117 - 1]->display }}');
        }
        else if (dterminalSelectedOption == 4) {
            $('#change_dterminal_name').html('{{ $label[118 - 1]->name }}');
            $('#change_dterminal_display').html('{{ $label[118 - 1]->display }}');
        }
        else if (dterminalSelectedOption == 5) {
            $('#change_dterminal_name').html('{{ $label[119 - 1]->name }}');
            $('#change_dterminal_display').html('{{ $label[119 - 1]->display }}');
        }
        else if (dterminalSelectedOption == 11) {
            $('#change_dterminal_name').html('{{ $label[120 - 1]->name }}');
            $('#change_dterminal_display').html('{{ $label[120 - 1]->display }}');
        }
        else if (dterminalSelectedOption == 7) {
            $('#change_dterminal_name').html('{{ $label[121 - 1]->name }}');
            $('#change_dterminal_display').html('{{ $label[121 - 1]->display }}');
        }
        else if (dterminalSelectedOption == 6) {
            $('#change_dterminal_name').html('{{ $label[122 - 1]->name }}');
            $('#change_dterminal_display').html('{{ $label[122 - 1]->display }}');
        }
        else if (dterminalSelectedOption == 9) {
            $('#change_dterminal_name').html('{{ $label[123 - 1]->name }}');
            $('#change_dterminal_display').html('{{ $label[123 - 1]->display }}');
        }
        else if (dterminalSelectedOption == 10) {
            $('#change_dterminal_name').html('{{ $label[124 - 1]->name }}');
            $('#change_dterminal_display').html('{{ $label[124 - 1]->display }}');
        }
        else if (dterminalSelectedOption == 8) {
            $('#change_dterminal_name').html('{{ $label[125 - 1]->name }}');
            $('#change_dterminal_display').html('{{ $label[125 - 1]->display }}');
        }
    });
    //=================onchange-values=============================

    $(document).on('change', '#dterminal', function () {
        $("#dauctionnew").val($(this).children('option:selected').attr('data-value'));
        setTimeout(function () {
            var d_zip1 = $(`#d_zip1`).val();
            var dterminal = $(`#dterminal`).val();
            $('#port_lines').prop('style', 'display: none;');
            $("#port_line1").prop("checked", false);
            $("#port_line2").prop("checked", false);
            $('input[name="port_line"]').prop("required", false);
            $("#port_dock_type").prop("selectedIndex", 0).change();
            if (dterminal == 2) {
                dterminal = 1;
                $.ajax({
                    type: "GET",
                    url: "/get_auction",
                    dataType: 'JSON',
                    data: { o_zip1: d_zip1, oterminal: dterminal },
                    success: function (res) {
                        if (res) {
                            $('#daddress').val(res[0].address);
                            $('#dacutionphoNo').val(res[0].phone);
                            $('#dacution_name').val('Copart Auction');
                            $('#d_zip1').focus();

                        }
                    }
                });
            } else if (dterminal == 7) {
                $('#reason_box').val(null);
                $('#port_lines').prop('style', 'display: block; width: 100%;"');
                $('input[name="port_line"]').prop("required", true);
            }
        }, 500);
    });

    $(document).on('change', '#port_dock_type', function () {
        setTimeout(function ($this) {
            var port_dock_type = $(`#port_dock_type`).val();
            if (port_dock_type == 'Non Running' || port_dock_type == 'Folk Lift') {
                $('#reason_box').val(null);
                $('#port_reason_box').prop('style', 'display: block; width: 100%;"');
            } else {
                $('#port_reason_box').prop('style', 'display: none; width: 100%;"');
                $('#reason_box').val(null);
            }
        }, 500);
    });


    $(document).on('click', '.ophonev', function () {

        $(".ophonev").mask("(999) 999-9999");
        $(".ophonev")[0].setSelectionRange(0, 0);

    });

    $(document).on('click', '#oacutionphoNo', function () {
        $("#oacutionphoNo").mask("(999) 999-9999");
        $("#oacutionphoNo")[0].setSelectionRange(0, 0);

    });


    $(function () {
        var dateToday = new Date();

        $("#pickup_date").datepicker({

            minDate: dateToday
        });
    });

    $(function () {
        var dateToday = new Date();

        $("#delivery_date").datepicker({

            minDate: dateToday
        });
    });

    function getmake() {
        $(".makeOpt0").autocomplete({
            source: "getmake"
        });
    }


    function goto_port(get) {


        if ($(`#needTitle${get}`).is(":checked")) {

            $(`#portTitlehidden${get}`).val("true");

        } else {

            $(`#portTitlehidden${get}`).val("false");
        }


    }

    function getmodel(num) {

        var yy = $("#year" + num).val();
        var mm = $("#makeOpt" + num).val();


        $(".model0").autocomplete({
            source: "getmodel?year=" + yy + "&make=" + mm
        });

        my_func(num);
    }

    function get_vin(num) {
        var vinno = $(`#vinNum${num}`).val();
        if (vinno == '') {
            $("#year" + num).val('');
            $("#makeOpt" + num).val('');
            $("#model" + num).val('');
        }
        else {
            $.ajax({
                type: "GET",
                url: "/getvin",
                dataType: 'JSON',
                data: { term: vinno },
                success: function (res) {
                    if (res) {
                        $("#year" + num).val(res[2].value);
                        $("#makeOpt" + num).val(res[0].value);
                        $("#model" + num).val(res[1].value);

                        my_func(num);
                        if (res[0].value || res[1].value || res[0].value) {
                            $("#vingoogleimage" + num).show();
                        }
                    }
                }

            });
        }

    }


    function selectRefresh() {
        $('.select2').select2({
            //-^^^^^^^^--- update here
            tags: true,
            placeholder: "Select an Option",
            allowClear: true,
            width: '100%'
        });
    }

    $(document).ready(function (e) {

        selectRefresh();

        $("body").delegate(".this_save", "change", function () {

            var datastring = $("#form").serialize();

            $.ajax({

                type: "post",
                url: "/auto_save_order",
                data: datastring,
                dataType: "json",

                success: function (data) {
                    $("#miles").val(data.miles);
                },
                error: function (e) {

                }

            });


        });

        $(".ophone").mask("(999) 999-9999");


        // $(function () {
        //     $("#o_zip1").autocomplete({
        //         source: "get_zip"
        //     });
        // });

        // $(function () {
        //     $("#d_zip1").autocomplete({
        //         source: "get_zip"
        //     });
        // });
        $('#d_zip1').keyup(function () {
            var d_zip1 = $(this);
            var dterminal = $('#dterminal');
            var daddress = $('#daddress');
            var dacutionphoNo = $('#dacutionphoNo');
            var nav = $(this).parents('.form-group').siblings('.nav');
            if (d_zip1.val() == '') {
                nav.children().remove();
                nav.hide();
                daddress.val('');
                dacutionphoNo.val('');
            }
            else {
                $.ajax({
                    url: "{{url('/get_zip')}}",
                    type: "GET",
                    dataType: "json",
                    data: { d_zip1: d_zip1.val() },
                    success: function (res) {
                        nav.show();
                        nav.children().remove();
                        $.each(res, function () {
                            nav.append(`
                                    <li class="nav-item selectAdd2">
                                        <a class="nav-link" href="javascript:void(0)">${this}</a>
                                    </li>
                                `);
                        });
                        $('.selectAdd2').click(function () {
                            $(this).parent('.nav').children().remove();
                            $(this).parent('.nav').hide();
                            $('#d_zip1').val($(this).children('a').text());

                            var getZip = $(this).children('a').text();
                            if (dterminal.val() == 2 || dterminal.val() == 3 || dterminal.val() == 4) {
                                $.ajax({
                                    url: "{{url('/new-auction-detail')}}",
                                    type: "GET",
                                    dataType: "json",
                                    data: { zip_code: getZip, terminal: dterminal.val() },
                                    success: function (res) {
                                        if (res.data.address) {
                                            daddress.val(res.data.address);
                                            dacutionphoNo.val(res.data.phone);
                                        }
                                        else {
                                            daddress.val('');
                                            dacutionphoNo.val('');
                                        }
                                    }
                                });
                            }
                            else {
                                daddress.val('');
                                dacutionphoNo.val('');
                            }
                        })
                        // console.log(res);
                    }
                });
            }
            // console.log(d_zip1);
        })


        $('#o_zip1').keyup(function () {
            var o_zip1 = $(this);
            var oterminal = $('#oterminal');
            var oaddress = $('#oaddress');
            var oacutionphoNo = $('#oacutionphoNo');
            var nav = $(this).parents('.form-group').siblings('.nav');
            if (o_zip1.val() == '') {
                nav.children().remove();
                nav.hide();
                oaddress.val('');
                oacutionphoNo.val('');
            }
            else {
                $.ajax({
                    url: "{{url('/get_zip')}}",
                    type: "GET",
                    dataType: "json",
                    data: { d_zip1: o_zip1.val() },
                    success: function (res) {
                        nav.show();
                        nav.children().remove();
                        $.each(res, function () {
                            nav.append(`
                                <li class="nav-item selectAdd">
                                    <a class="nav-link" href="javascript:void(0)">${this}</a>
                                </li>
                            `);
                        });
                        $('.selectAdd').click(function () {
                            $(this).parent('.nav').children().remove();
                            $(this).parent('.nav').hide();
                            $('#o_zip1').val($(this).children('a').text());

                            var getZip = $(this).children('a').text();
                            if (oterminal.val() == 2 || oterminal.val() == 3 || oterminal.val() == 4) {
                                $.ajax({
                                    url: "{{url('/new-auction-detail')}}",
                                    type: "GET",
                                    dataType: "json",
                                    data: { zip_code: getZip, terminal: oterminal.val() },
                                    success: function (res) {
                                        if (res.data.address) {
                                            oaddress.val(res.data.address);
                                            oacutionphoNo.val(res.data.phone);
                                        }
                                        else {
                                            oaddress.val('');
                                            oacutionphoNo.val('');
                                        }
                                    }
                                });
                            }
                            else {
                                oaddress.val('');
                                oacutionphoNo.val('');
                            }
                        })
                        // console.log(res);
                    }
                });
            }
            // console.log(d_zip1);
        })



        $("#callhistoryform").on('submit', function () {
            $("#savceChanges").attr('disabled', true);
        })

        $("#form").on('submit', (function (e) {
            $("#clickToSubmit").attr('disabled', true);
            e.preventDefault();
            $.ajax({
                url: "/store_new_quote",
                type: "POST",
                data: new FormData(this),
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                },
                success: function (data) {


                    //let test = data.toString();
                    let test = data["success"];
                    let test2 = $.trim(test);
                    let text = "SUCCESS";
                    if (test2 == text) {
                        $('#success').html(data);
                        //$('#modaldemo4').modal('show');
                        $("#neworderpay_btn").val(0);

                        if ($("#continuetopay_btn").val() == 1 || $("#continuetopayold_btn").val() == 1) {


                            // window.open('/order_payment_card_us/' + data["orderid"], '_blank');
                            window.location.href = "/new";

                        } else {
                            //window.location.href = "/new";
                            $('#reportmodal').modal('show');
                        }

                    } else {
                        $('#not_success').html(data);
                        $('#modaldemo5').modal('show');
                    }
                },
                error: function (e) {
                    $("#err").html(e).fadeIn();
                }
            });
        }));
    });

    function resetVehicle() {
        $('.type').change(function () {
            $(this).parent().parent().parent().parent().siblings().children().children().children('.vyear').val('');
            $(this).parent().parent().parent().parent().siblings().children().children().children('.vmodel').val('');
            $(this).parent().parent().parent().parent().siblings().children().children().children('.vmake').val('');
        })
    }
    resetVehicle();

    $("#coupon_number").keyup(function () {
        var coupon_number = $(this);
        coupon_number.parent('div').children('.alert').remove();
        if (coupon_number.val() == '') {
            coupon_number.parent('div').children('.alert').remove();
        }
        else {
            $.ajax({
                url: "{{url('/coupon_number')}}",
                type: "GET",
                dataType: "json",
                data: { coupon_number: coupon_number.val() },
                success: function (res) {
                    coupon_number.parent('div').children('.alert').remove();
                    if (res.status_code === 400) {
                        coupon_number.parent('div').append(`
                            <div class="alert text-danger p-0"><strong>${res.err}</strong></div>
                        `);
                    }
                    else {
                        coupon_number.parent('div').append(`
                            <div class="alert text-success p-0"><strong>${res.msg}</strong></div>
                        `);
                    }
                }
            });
        }
    });
    $(document).on('click', "#saveBtn", function () {
        $("select[name='pstatus']").children('option').attr("selected", false);
        $("select[name='pstatus']").children('option').eq(0).attr("selected", true);
        $("#payCondition").html('');
    })
</script>

<script>
    $(document).ready(function () {
        // Attach a click event handler to the "Nature of Customer" link
        $("#showOldCustomerNature").on("click", function () {

            var order_id = $('#order_id1').val();

            console.log('order_idorder_id', order_id);

            $.ajax({

                url: "{{ url('/get/CustomerNature') }}",
                type: "GET",
                // dataType: "json",
                data: {
                    order_id: order_id,
                },
                success: function (data) {
                    console.log('datasss', data);
                    if (data.length == 0) {
                        $("#customerTable").html('No Records Found');
                        // Open the modal with the ID "modaldemo5"
                        $("#modalCustomerNature").modal("show");
                    }
                    else {
                        $("#customerTable").html('');

                        var html = "";

                        $.each(data, function (index, val) {
                            html += "<tr>";
                            html += "<td>" + (index + 1) + "</td>";
                            html += "<td>" + val['order_id'] + "</td>";
                            html += "<td>" + val['description'] + "</td>";
                            html += "<td>" + val['user']['name'] + ' ' + val['user']['last_name'] + "</td>";
                            html += "<td>" + val['created_at'] + "</td>";
                            html += "</tr>";
                        });

                        // Append the HTML to the tbody
                        $('#customerTable').html(html);

                        // Open the modal with the ID "modaldemo5"
                        $("#modalCustomerNature").modal("show");
                    }
                }
            });
        });

        $("#showMsgChats").on("click", function () {

            var order_id = $('#order_id1').val();

            console.log('order_idorder_id', order_id);

            $.ajax({

                url: "{{ route('get.all.messagechat') }}",
                type: "GET",
                // dataType: "json",
                data: {
                    order_id: order_id,
                },
                success: function (data) {
                    console.log('datasss', data);
                    if (data.length == 0) {
                        $("#messageChatsTable").html('No Records Found');
                        // Open the modal with the ID "modaldemo5"
                        $("#modalMessageChats").modal("show");
                    }
                    else {
                        $("#messageChatsTable").html('');

                        var html = "";

                        $.each(data, function (index, val) {
                            html += "<tr>";
                            html += "<td>" + (index + 1) + "</td>";
                            html += "<td>" + val['order_id'] + "</td>";
                            html += "<td>" + val['user']['name'] + ' ' + val['user']['last_name'] + "</td>";
                            html += "<td>" + val['message'] + "</td>";
                            html += "<td>" + val['created_at'] + "</td>";
                            html += "</tr>";
                        });

                        // Append the HTML to the tbody
                        $('#messageChatsTable').html(html);

                        // Open the modal with the ID "modaldemo5"
                        $("#modalMessageChats").modal("show");
                    }
                }
            });
        });

        $('#available_at_auction').change(function () {
            if ($(this).is(':checked')) {
                $('.div-link').show();
            } else {
                $('.div-link').hide();
            }
        });

        $('#modification').change(function () {
            if ($(this).is(':checked')) {
                $('.div-modify_info').show();
            } else {
                $('.div-modify_info').hide();
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('.requestPrice').click(function () {
            console.log('okok');
            var user = "{{Auth::user()->id}}";
            var roleChecker = "{{Auth::user()->role}}";
            var origin = $('#o_zip1').val();
            var destination = $('#d_zip1').val();
            var orderID = $('#orderid_find').val();
    
            var vehicleInfo = $('.vehicle-info').val();
            var year = $(".vyear");
            var model = $(".vmodel");
            var make = $(".vmake");
            var vinNumber = $(".vin_num");
            var type = $(".type:checked");
            var vehicleType = $(".vehicle-type option:selected");
            var vehicleCondition = $(".vehicle-condition option:selected");
            var trailerType = $(".trailer-type option:selected");
    
            var years = [];
            $.each(year, function () {
                years.push(this.value);
            });
            var models = [];
            $.each(model, function () {
                models.push(this.value);
            });
            var makes = [];
            $.each(make, function () {
                makes.push(this.value);
            });
            var vinNumbers = [];
            $.each(vinNumber, function () {
                vinNumbers.push(this.value);
            });
            var types = [];
            $.each(type, function () {
                types.push(this.value);
            });
            var vehicleTypes = [];
            $.each(vehicleType, function () {
                vehicleTypes.push(this.value);
            });
            var vehicleConditions = [];
            $.each(vehicleCondition, function () {
                vehicleConditions.push(this.value);
            });
            var trailerTypes = [];
            $.each(trailerType, function () {
                trailerTypes.push(this.value);
            });
    
            var roleChecker = "{{Auth::user()->role}}";
            var user = "{{Auth::user()->id}}";
            
            if (!(year.val() == '' || model.val() == '' || make.val() == '' || type.val() == '' || vehicleType.val() == '' || vehicleCondition.val() == '' || trailerType.val() == '')) {
                $(this).attr('disabled', true);
                $.ajax({
                    url: '{{url("/request")}}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        origin: origin,
                        destination: destination,
                        orderID: orderID,
                        vehicleInfo: (vehicleInfo) ? vehicleInfo : '',
                        year: (years) ? years : '',
                        model: (models) ? models : '',
                        make: (makes) ? makes : '',
                        vinNumber: (vinNumbers) ? vinNumbers : '',
                        type: (types) ? types : '',
                        vehicleType: (vehicleTypes) ? vehicleTypes : '',
                        vehicleCondition: (vehicleConditions) ? vehicleConditions : '',
                        trailerType: (trailerTypes) ? trailerTypes : '',
                    },
                    success: function (res) {
                        if (user == "{{Auth::user()->id}}") {
                            $("body").append('<div class="alert alert-success w-50 m-auto alert-dismissible fade show" role="alert" style="position:fixed;top:20px;left: 50%;transform: translateX(-50%);z-index: 9999;">' +
                                '<strong>' + res.message + '</strong>' +
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                '<span aria-hidden="true">&times;</span>' +
                                '</button>' +
                                '</div>');
                                
                            $('.priceReq').html('');
                            var html = `<input type="hidden" class="orderID">
                            <a href="javascript:void(0)" class="btn btn-success mg-r-10 completeReq">Request Price</a>`;
                            $('.priceReq').html(html);
                            
                            $('.completeReq').click(function () {
                                var url = `/complete-request/${orderID}`;
                                window.open(url, 'View Request Prices',
                                    'height=500,width=1000,left=150,top=250,toolbar=No,location=No,scrollbars=Yes,status=No,resizable=Yes,fullscreen=No,directories=No,menubar=No,copyhistory=No'
                                );
                            })
                        }
                        if (roleChecker == 2 || roleChecker == 1 || roleChecker == 9 || roleChecker == 13 || roleChecker == 14) {
                            req();
                        }
                        if (roleChecker == 5) {
                            getRequest(res.id);
                        }
                    }
                });
            } else {
                if (user == "{{Auth::user()->id}}") {
                    $("body").append('<div class="alert alert-danger w-50 m-auto alert-dismissible fade show" role="alert" style="position:fixed;top:20px;left: 50%;transform: translateX(-50%);z-index: 9999;">' +
                        '<strong>Please fill the vehicle detail!</strong>' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                        '<span aria-hidden="true">&times;</span>' +
                        '</button>' +
                        '</div>');
                }
            }
        });
    });
</script>

@endsection