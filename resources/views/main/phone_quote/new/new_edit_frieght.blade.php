@extends('layouts.innerpages')

@section('template_title')
    Edit Frieght Quote
@endsection

@include('partials.mainsite_pages.return_function')
<style>
    .card-people-list .media-body {
        margin-left: 15px !important;
    }

    .media-body > a {
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
        color: #000 ;
    }

    input {
        color: black !important;
    }

    .padding_none {
        padding: 0px !important;
    }

    .border_none {
        border: 0px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: rgb(26, 22, 48);
        line-height: 28px !important;
    }

    input.select2-search__field {
        height: 50px !important;
    }
    select.form-control:not([size]):not([multiple]) {
        height: 2.375rem !important;
    }
</style>

@section('content')
    @php
        $actionaccess = explode(',', Auth::user()->emp_access_action);
    @endphp

    <div class="page-header">
        <!--<div class="page-leftheader">-->
        <!--    <h4 class="page-title mb-0">New Quote </h4>-->
    <!--    <h3 class="page-title mb-0">Order Id : {{ $data->id  }} </h3>-->
        <!--    <h4 id="orderidplace"></h4>-->
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layers mr-2 fs-14"></i>Pages</a>-->
        <!--        </li>-->

        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">New Quote</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase">
            <h1 class="mt-4 mb-0"><b>Update Order</b></h1>
            <h3 class="page-title mb-0">Order Id : {{ $data->id  }} </h3>
        </div>

        {{-- dd($freight, $data->toArray()) --}}

    </div>
    <!--End Page header-->
    <!-- Row -->

    <form action="" name="valid_form" id="form" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token()}}">
        <input  name="pstatus2" type="hidden" id="pstatus2">
        <input  name="order_history" type="hidden"  id="order_history">
        <input  name="asking_low" type="hidden"  id="asking_low">


        <!-- Row -->

        <div class="row">
            <div class="card margin_lft_rth">
                <div class="grid_new grid_2 new__Quote dfdf" style="gap: 0 2rem !important;">
                    <div class="" style="padding: 12px 0px 0px 24px !important;">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title fgfgfg">ORIGIN LOCATION</div>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class=" form-label font-boldd">Terminal, Dealer, Auction</label>
                                            <select class="form-control this_save  select2" name="oterminal"
                                                    id="oterminal"
                                            >
                                                {{--<optgroup label="Categories">--}}
                                                {{--<option data-select2-id="5" selected="" disabled="">--Select----}}
                                                {{--</option>--}}
                                                <option value="">Select an Option</option>
                                                <option
                                                    <?php if ($data->oterminal == '1') echo 'selected';?> value="1">
                                                    Residence
                                                </option>
                                                <option
                                                    <?php if ($data->oterminal == '2') echo 'selected';?> value="2">
                                                    COPART Auction
                                                </option>
                                                <option
                                                    <?php if ($data->oterminal == '3') echo 'selected';?> value="3">
                                                    Manheim Auction
                                                </option>
                                                <option
                                                    <?php if ($data->oterminal == '4') echo 'selected';?> value="4">
                                                    IAAI
                                                    Auction
                                                </option>
                                                <option
                                                    <?php if ($data->oterminal == '5') echo 'selected';?> value="5">
                                                    Body
                                                    Shop
                                                </option>
                                                <option
                                                    <?php if ($data->oterminal == '10') echo 'selected';?> value="10">
                                                    Dealership
                                                </option>
                                                <option
                                                    <?php if ($data->oterminal == '7') echo 'selected';?> value="7">
                                                    Business Location
                                                </option>
                                                <option
                                                    <?php if ($data->oterminal == '8') echo 'selected';?> value="8">
                                                    Auction (Heavy)
                                                </option>
                                                <option
                                                    <?php if ($data->oterminal == '6') echo 'selected';?> value="6">
                                                    Other
                                                </option>
                                                {{--</optgroup>--}}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row oauc">
                                    @if($data->oterminal=='2' || $data->oterminal=='3' || $data->oterminal=='4' || $data->oterminal=='5' || $data->oterminal=='10')
                                        <div class="col-sm-6">
                                            <div class="form-group auctionname">
                                                <label id="oacution" class="label font-boldd tx-black">

                                                    @if($data->oterminal=='2' || $data->oterminal=='3' || $data->oterminal=='4')
                                                        Auction Name
                                                    @endif
                                                    @if($data->oterminal=='5')
                                                        Shop Name
                                                    @endif
                                                    @if($data->oterminal=='10')
                                                        Dealership / Contact Person
                                                    @endif

                                                </label>
                                                <input class="form-control this_save" autocomplete="nope"
                                                       type="text" name="oacution" id="oacution_name"
                                                       value="{{ $data->oauction}}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 auctionphone">
                                            <div class="form-group">
                                                <label id="oacutionpho" class="label  font-boldd tx-black">

                                                    @if($data->oterminal=='2' || $data->oterminal=='3' || $data->oterminal=='4')
                                                        Auction Phone
                                                    @endif
                                                    @if($data->oterminal=='5')
                                                        Shop Phone
                                                    @endif
                                                    @if($data->oterminal=='10')
                                                        Dealership Phone
                                                    @endif

                                                </label>
                                                <input class="form-control this_save ophone" autocomplete="nope"
                                                       type="text"
                                                       name="oacutionpho" id="oacutionphoNo"
                                                       value="{{ $data->oauctionpho}}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 buyer_number">
                                            <div class="form-group">
                                                <label class=" form-label font-boldd  ">Buyer/Lot/Stock Number</label>
                                                <input type="text" name="obuyer_no" id="obuyer_no"
                                                       class="form-control this_save "
                                                       placeholder="Buyer/Lot/Stock Number"
                                                       value="{{ $data->obuyer_no}}">
                                            </div>
                                        </div>
                                    @endif
                                    @if($data->oterminal=='2' || $data->oterminal=='3' || $data->oterminal=='4')
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label id="oacutiondate" class="label parsley-error font-boldd tx-black">Auction Date <span class="text-muted">(Optional)</span></label>
                                                <input class="form-control this_save" autocomplete="off" type="date"  name="oacutiondate" id="oacutiondate" value="{{$data->oauctiondate}}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label id="oacutiontime" class="label parsley-error font-boldd tx-black">Auction Time <span class="text-muted">(Optional)</span></label>
                                                <input class="form-control this_save" autocomplete="off" type="time"  name="oacutiontime" id="oacutiontime" value="{{$data->oauctiontime}}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="oacutionaccounttitle" class="label parsley-error font-boldd tx-black">Has Auction Account?</label>
                                                <select class="form-control this_save" autocomplete="off" name="oacutionaccounttitle" id="oacutionaccounttitle">
                                                    <option value="" selected disabled>Select</option>
                                                    <option value="Yes" @if($data->oacutionaccounttitle == 'Yes') selected @endif>Yes</option>
                                                    <option value="No" @if($data->oacutionaccounttitle == 'No') selected @endif>No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12" @if($data->oacutionaccounttitle == 'Yes') style="display:block;" @else style="display:none;" @endif id="aucAccName">
                                            <div class="form-group">
                                                <label for="oacutionaccountname" class="label parsley-error font-boldd tx-black">Auction Account Name </label>
                                                <input class="form-control this_save" autocomplete="off" type="text"  name="oacutionaccountname" id="oacutionaccountname" value="{{$data->oacutionaccountname}}">
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="">

                                    <div class="form-group">

                                        <label class=" form-label font-boldd  ">Name<span class="redcolor">*</span></label>
                                        <input type="text" name="oname" id="oname" class="form-control this_save "
                                               onkeypress="$(this).css('border-color', 'rgb(92 166 242)');"
                                               value="{{ $data->oname }}" placeholder="Name" required>
                                    </div>
                                </div>
                                <div class="col-sm-12 stock_number">

                                </div>

                                <div class="">
                                    <div class="form-group">
                                        <label class=" form-label font-boldd  ">Email Address</label>
                                        <input type="email" name="oemail" id="oemail"
                                               class="form-control this_save "
                                               value="{{ $data->oemail }}" placeholder="Email">
                                    </div>
                                </div>

                                <div class="add_phone">
                                    @php
                                        $i=0;
                                        $totalophone=0;
                                        $arrayophone = explode('*^',$data->ophone);
                                        $arrayophone2 = explode('*^',$data->ophone);

                                    @endphp
                                    @foreach($arrayophone as $ophone)
                                        @if($i==0)
                                            <div class='row'>
                                                &nbsp; &nbsp; &nbsp; <label class=" form-label font-boldd  ">Phone Number<span class="redcolor">*</span>
                                                    @if($arrayophone2[$i]) <span class="badge-success ml-3 px-1 rounded text-light editphoneonoff" style="cursor:pointer;">Edit Phone</span> @endif</label>

                                                <div class="form-group col-11 ">
                                                    <input type="text" name="ophone[]" id="ophone" @if($arrayophone2[$i]) readonly @endif onchange="getOldData()"
                                                    class="form-control this_save  ophone ophone_new"
                                                           placeholder="Number" value="@if($arrayophone2[$i]){{ substr($arrayophone2[$i], 0, 5) . ' ***-****' }}@endif"
                                                           required onkeypress="$(this).css('border-color', 'rgb(92 166 242)');">
                                                </div>
                                                <div class='form-group col-1' style="padding-top: 7px;">
                                                    <i id='add_btn' class="si si si-plus add_phone_btn"></i>

                                                </div>
                                                <input type="hidden" value="@if($arrayophone2[$i]){{ $arrayophone2[$i] }}@endif" name="ophone2[]" id="ophone2" />
                                            </div>
                                        @else
                                            <div class='col-12 add margin_lft'>
                                                <div class="row">
                                                    <label class=" form-label font-boldd  ">Phone
                                                        Number:
                                                        @if($arrayophone2[$i]) <span class="badge-success ml-3 px-1 rounded text-light editphoneonoff" style="cursor:pointer;">Edit Phone</span> @endif</label>
                                                    <div class="col-11">
                                                        <input type="text" name="ophone[]" @if($arrayophone2[$i]) readonly @endif
                                                        class="form-control this_save ophone ophone_new"
                                                               value="{{ substr($arrayophone2[$i], 0, 5) . ' ***-****' }}"
                                                               id="ophonee<?php echo $i;?>"
                                                               placeholder="Phone Number"/>
                                                    </div>
                                                    <div class="form-group col-1" style="padding-top: 23px;">
                                                        <i id="remove_btn"
                                                           class="fa fa-minus-circle remove_btn"></i>
                                                    </div>
                                                    <input type="hidden"  value="@if($arrayophone2[$i]){{ $arrayophone2[$i] }}@endif" name="ophone2[]" />
                                                </div>
                                            </div>
                                        @endif
                                        @php
                                            $i++;
                                            $totalophone++;
                                        @endphp
                                    @endforeach

                                </div>
                                <div class="row mb-2" id="ophoneResult">
                                    <div class="col-lg-12" style=" padding-left: 26px !important; ">
                                        <strong style="color:black">Previous Record {{$count_previous + $old_count_previous}}</strong>
                                        <a target="_blank" href="/searchData?search={{($data->mainPhNum) ? $data->mainPhNum : $data->main_ph }}"
                                           class="inner_style">Show Previous</a>
                                        <span style="font-size: 22px; color: #000; line-height: 0;">|</span>
                                        <a href="javascript:void(0)" data-toggle="modal"
                                           data-target="#alreadyCreditCard">{{$credit_card + count($old) }} Card Found</a></div>
                                </div>


                                <div class="col-md-12 padding_none">
                                    <div class="form-group">
                                        <label class=" form-label font-boldd  ">Address
                                            <span class="redcolor">*</span></label>
                                        <input type="text" name="oaddress" id="oaddress" class="form-control this_save "
                                               value="{{ $data->oaddress }}" placeholder="Home Address">
                                    </div>
                                </div>
                                <div class="col-md-12 padding_none">
                                    <div class="form-group">
                                        <label class=" form-label font-boldd  ">Address2</label>
                                        <input type="text" id="oaddress2" name="oaddress2"
                                               class="form-control this_save "
                                               value="{{ $data->oaddress2 }}" placeholder="Home Address">
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-12 padding_none">
                                    <div class="form-group mb-0">
                                        <label class=" form-label font-boldd  ">Zip Code<span class="redcolor">*</span></label>
                                        <input type="text" id="o_zip1" class="form-control this_save "
                                               maxlength="100" name="o_zip1"
                                               onkeypress="$(this).css('border-color', 'rgb(92 166 242)');"
                                               value="{{ $data->originzsc }}" placeholder="ZIP CODE" required/>
                                    </div>
                                    <ul class="nav flex-column border scrollul" style="max-height:200px;overflow:scroll;display:none;">
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-6 " style="padding: 12px 24px 0px 1px !important;">
                        <div class="card" style=" height: 96%; ">
                            <div class="card-header">
                                <div class="card-title  ">DESTINATION LOCATION</div>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class=" form-label font-boldd">Terminal, Dealer, Auction</label>
                                            <select class="form-control this_save select2" name="dterminal"
                                                    id="dterminal">
                                                <option value="" data-value="">Select</option>
                                                <option data-value="Residence" <?php if ($data->dterminal == '1') echo 'selected';?> value="1">
                                                    Residence
                                                </option>
                                                <option data-value="COPART Auction" <?php if ($data->dterminal == '2') echo 'selected';?> value="2">
                                                    COPART Auction
                                                </option>
                                                <option data-value="Manheim Auction" <?php if ($data->dterminal == '3') echo 'selected';?> value="3">
                                                    Manheim Auction
                                                </option>
                                                <option data-value="IAAI Auction" <?php if ($data->dterminal == '4') echo 'selected';?> value="4">
                                                    IAAI Auction
                                                </option>
                                                <option data-value="Body Shop" <?php if ($data->dterminal == '5') echo 'selected';?> value="5">
                                                    Body Shop
                                                </option>
                                                <option data-value="Dealership"    <?php if ($data->dterminal == '11') echo 'selected';?> value="11">
                                                    Dealership
                                                </option>
                                                <option data-value="Port" <?php if ($data->dterminal == '7') echo 'selected';?> value="7">
                                                    Port
                                                </option>
                                                <option data-value="AirPort" <?php if ($data->dterminal == '6') echo 'selected';?> value="6">
                                                    AirPort
                                                </option>
                                                <option data-value="Business Location" <?php if ($data->dterminal == '9') echo 'selected';?> value="9">
                                                    Business Location
                                                </option>
                                                <option data-value="Auction (Heavy)"    <?php if ($data->dterminal == '10') echo 'selected';?> value="10">
                                                    Auction (Heavy)
                                                </option>
                                                <option data-value="Other" <?php if ($data->dterminal == '8') echo 'selected';?> value="8">
                                                    Other
                                                </option>
                                            </select>
                                            <input type="hidden" id="dauctionnew" name="dauctionnew" value="{{$data->dauction}}" />
                                        </div>
                                    </div>
                                    @php
                                        if ($data->dterminal == 7) {
                                            $style = 'display: block;';
                                        }else{
                                            $style = 'display: none;';
                                        }
                                    @endphp
                                    <span id="port_lines" style="{{ $style }} width: 100%">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="row label_margin">
                                                    <div class="col-md-3" style="display: inline-flex">
                                                        <input type="radio" id="port_line1" name="port_line" {{ $data->port_line == 'girmadi' ? 'checked="checked"' : '' }} value="girmadi"  class="mr-1 this_save">
                                                        <label class="form-label font-boldd" for="port_line1">Girmadi Lines
                                                            <!--<span class="redcolor">*</span>-->
                                                        </label>
                                                    </div>
                                                    <div class="col-md-3" style="display: inline-flex">
                                                        <input type="radio" id="port_line2" name="port_line" {{ $data->port_line == 'sallum' ? 'checked="checked"' : '' }} value="sallum"  class="mr-1 this_save" >
                                                        <label class="form-label font-boldd" for="port_line2">Sallum Lines
                                                            <!--<span class="redcolor">*</span>-->
                                                        </label>
                                                    </div>
                                                      <div class="col-md-3" style="display: inline-flex">
                                                        <input type="radio" id="port_line3" name="port_line" value="both"  class="mr-1 this_save" >
                                                        <label class="form-label font-boldd" for="port_line3">Both
                                                            <!--<span class="redcolor">*</span>-->
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class=" form-label font-boldd">Dock Recieve Type</label>
                                                <select class="form-control this_save select2" name="port_dock_type"
                                                        id="port_dock_type" >
                                                    <option value="">Select</option>
                                                    <option value="Running" {{ $data->port_dock_type == 'Running' ? 'Selected' : '' }}>Running</option>
                                                    <option value="Non Running" {{ $data->port_dock_type == 'Non Running' ? 'Selected' : '' }}>Non Running</option>
                                                    <option value="Folk Lift" {{ $data->port_dock_type == 'Folk Lift' ? 'Selected' : '' }}>Folk Lift</option>
                                                </select>
                                            </div>
                                        </div>
                                    </span>
                                    @php
                                        if ($data->dterminal == 7 && $data->port_dock_type == 'Non Running' || $data->port_dock_type == 'Folk Lift') {
                                            $style = 'display: block;';
                                        }else{
                                            $style = 'display: none;';
                                        }
                                    @endphp
                                    <div id="port_reason_box" style="{{ $style }} width: 100%" class="col-sm-6 col-md-12">
                                        <div class="form-group">
                                            <label class=" form-label font-boldd  ">Reason Box</label>
                                            <input type="text" id='reason_box' name='reason_box' class="form-control this_save"
                                                   onkeypress="$(this).css('border-color', 'rgb(92 166 242)');"
                                                   value="{{ $data->reason_box }}" placeholder="First Name">
                                        </div>
                                    </div>

                                    <div class="row dauc" style="margin-bottom: -22px;">
                                        @if($data->dterminal=='6' || $data->dterminal=='7')
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label id="dauction" class="label  font-boldd tx-black  ">Port
                                                        Name</label>
                                                    <input class="form-control this_save " autocomplete="nope"
                                                           type="text"
                                                           name="dauction" id="dacution_name"
                                                           value="{{$data->dauction}}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label id="dauctionpho" class="label  font-boldd tx-black  ">Port
                                                        Phone</label>

                                                    <input class="form-control this_save  ophone" autocomplete="nope"
                                                           type="text"
                                                           name="dauctionpho" id="dacutionphoNo"
                                                           value="{{$data->dauctionpho}}">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="label  font-boldd tx-black  ">Terminal</label>
                                                    <input class="form-control this_save " autocomplete="nope"
                                                           type="text" name="port_terminal" id="port_terminal"
                                                           value="{{$data->portterminal}}">
                                                </div>
                                            </div>
                                        @endif
                                        @if($data->dterminal=='2' || $data->dterminal=='3' ||  $data->dterminal=='4' ||  $data->dterminal=='5' || $data->dterminal=='11' )
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label id="dauction" class="label  font-boldd tx-black">
                                                        @if($data->dterminal=='2' || $data->dterminal=='3' || $data->dterminal=='4')
                                                            Auction Name
                                                        @endif
                                                        @if($data->dterminal=='5')
                                                            Shop Name
                                                        @endif
                                                        @if($data->dterminal=='11')
                                                            Dealership / Contact Person
                                                        @endif
                                                    </label>
                                                    <input required class="form-control this_save " autocomplete="nope"
                                                           type="text"
                                                           name="dauction" id="dacution_name"
                                                           value="{{$data->dauction}}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label id="dauctionpho" class="label  font-boldd tx-black  ">
                                                        @if($data->dterminal=='2' || $data->dterminal=='3' || $data->dterminal=='4')
                                                            Auction Phone
                                                        @endif
                                                        @if($data->dterminal=='5')
                                                            Shop Phone
                                                        @endif
                                                        @if($data->dterminal=='11')
                                                            Dealership Phone
                                                        @endif
                                                    </label>
                                                    <input required class="form-control this_save  ophone"
                                                           autocomplete="nope"
                                                           type="text" name="dauctionpho" id="dacutionphoNo"
                                                           value="{{$data->dauctionpho}}">
                                                </div>
                                            </div>
                                        @endif
                                        @if($data->dterminal=='2' || $data->dterminal=='3' || $data->dterminal=='4')
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label id="dacutiondate" class="label parsley-error font-boldd tx-black">Auction Date <span class="text-muted">(Optional)</span></label>
                                                    <input class="form-control this_save" autocomplete="off" type="date"  name="dacutiondate" id="dacutiondate" value="{{$data->dauctiondate}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label id="dacutiontime" class="label parsley-error font-boldd tx-black">Auction Time <span class="text-muted">(Optional)</span></label>
                                                    <input class="form-control this_save" autocomplete="off" type="time"  name="dacutiontime" id="dacutiontime" value="{{$data->dauctiontime}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="dacutionaccounttitle" class="label parsley-error font-boldd tx-black">Has Auction Account?</label>
                                                    <select class="form-control this_save" autocomplete="off" name="dacutionaccounttitle" id="dacutionaccounttitle">
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="Yes" @if($data->dacutionaccounttitle == 'Yes') selected @endif>Yes</option>
                                                        <option value="No" @if($data->dacutionaccounttitle == 'No') selected @endif>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-12" @if($data->dacutionaccounttitle == 'Yes') style="display:block;" @else style="display:none;" @endif id="daucAccName">
                                                <div class="form-group">
                                                    <label for="dacutionaccountname" class="label parsley-error font-boldd tx-black">Auction Account Name </label>
                                                    <input class="form-control this_save" autocomplete="off" type="text"  name="dacutionaccountname" id="dacutionaccountname" value="{{$data->dacutionaccountname}}">
                                                </div>
                                            </div>
                                        @endif

                                    </div>

                                    <div class="col-sm-6 col-md-12">
                                        <div class="form-group">
                                            <label class=" form-label font-boldd  ">Name </label>
                                            <input type="text" id='dname' name='dname' class="form-control this_save "

                                                   value="{{ $data->dname }}" placeholder="First Name">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label font-boldd">Email Address</label>
                                            <input type="email" name="demail" id="demail" autocomplete="of" class="form-control this_save" placeholder="Email">
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-12 add_dphone">
                                        @php
                                            $i=0;
                                            $totaldphone=0;
                                            $arraydphone = explode('*^',$data->dphone);
                                            $arraydphone2 = explode('*^',$data->dphone);
                                        @endphp
                                        @foreach($arraydphone as $dphone)
                                            @if($i==0)
                                                <div class="row">
                                                    <label class=" form-label font-boldd" style=" margin-left: 12px;">Phone Number
                                                        <!--<span class="redcolor">*</span>-->
                                                        @if($arraydphone2[$i]) <span class="badge-success ml-3 px-1 rounded text-light editphoneonoff" style="cursor:pointer;">Edit Phone</span> @endif
                                                    </label>
                                                    <div class="form-group col-11 ">
                                                        <input type="text" name="dphone[]" id="dphone" @if($arraydphone2[$i]) readonly @endif
                                                        class="form-control this_save  dphone ophone_new"
                                                               onkeypress="$(this).css('border-color', 'rgb(92 166 242)');"
                                                               value="@if($arraydphone2[$i]){{ substr($arraydphone2[$i], 0, 5) . ' ***-****' }}@endif" placeholder="Number">
                                                    </div>
                                                    <div class="form-group col-1" style="padding-top: 7px;">
                                                        <i id="add_btn" class="si si si-plus add_dphone_btn"></i>
                                                    </div>
                                                    <input type="hidden" value="@if($arraydphone2[$i]){{ $arraydphone2[$i] }}@endif" name="dphone2[]" />
                                                </div>
                                            @else
                                                <div class='col-12 add margin_lft'>
                                                    <div class="row">
                                                        <label class=" form-label font-boldd"
                                                               style=" margin-left: 12px; ">Phone Number:
                                                            @if($arraydphone2[$i]) <span class="badge-success ml-3 px-1 rounded text-light editphoneonoff" style="cursor:pointer;">Edit Phone</span> @endif</label>
                                                        <div class="form-group col-11">
                                                            <input type="text" name="dphone[]" @if($arraydphone2[$i]) readonly @endif
                                                            class="form-control this_save dphone ophone_new"
                                                                   id="phonee<?php echo $i;?>"
                                                                   placeholder="Phone Number"
                                                                   onkeypress="$(this).css('border-color', 'rgb(92 166 242)');"
                                                                   value="{{ substr($arraydphone2[$i], 0, 5) . ' ***-****' }}"/>
                                                        </div>
                                                        <div class="form-group col-1" style="padding-top: 23px;">
                                                            <i id="remove_btn"
                                                               class="fa fa-minus-circle remove_btn"></i>
                                                        </div>
                                                        <input type="hidden" value="@if($arraydphone2[$i]){{ $arraydphone2[$i] }}@endif" name="dphone2[]" />
                                                    </div>
                                                </div>
                                            @endif
                                            @php
                                                $i++;
                                                $totaldphone++;
                                            @endphp
                                        @endforeach

                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class=" form-label font-boldd  ">Address
                                                <span class="redcolor">*</span></label>
                                            <input type="text" name='daddress' id='daddress'
                                                   class="form-control this_save "
                                                   value="{{ $data->daddress }}" placeholder="Home Address">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class=" form-label font-boldd  ">Address2</label>
                                            <input type="text" name='daddress2' id='daddress2'
                                                   class="form-control this_save "
                                                   value="{{ $data->daddress2 }}" placeholder="Home Address">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12">
                                        <div class="form-group mb-0">
                                            <label class=" form-label font-boldd  ">Zip Code
                                                <span class="redcolor">*</span>
                                            </label>
                                            <input type="text" id="d_zip1" class="form-control this_save "
                                                   maxlength="100" name="d_zip"
                                                   onkeypress="$(this).css('border-color', 'rgb(92 166 242)');"
                                                   value="{{ $data->destinationzsc }}" placeholder="ZIP CODE" />
                                        </div>
                                        <ul class="nav flex-column border scrollul" style="max-height:200px;overflow:scroll;display:none;">
                                        </ul>
                                    </div>
                                    <div id="show_previous_orders" class="col-sm-12 col-md-12" style="overflow-y: scroll"></div>
                                    <div id="show_cancellation_orders" class="col-sm-12 col-md-12"
                                         style="overflow-y: scroll"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-sm-4 ml-4">
                        <div class="form-group">
                            <label for="miles" class="form-label">Miles</label>
                            <input type="text" class="form-control this_save" placeholder="Miles"  name="miles" id="miles" value="{{$data->miles}}" />
                        </div>
                    </div>
                </div>

                <div class="çard-footer">
                    <div class="flex_ flex_center gap_new row_style">
                        <div class="">
                            <div class=" text-right">
                                <a target="_blank" href="https://www.timeanddate.com/worldclock/usa"
                                   class="btn btn-primary">Time Zone</a>
                            </div>
                        </div>
                        <div class="">
                            <div class=" ">
                                <a href="https://www.google.com/maps/dir/{{$data->originzsc}}/{{$data->destinationzsc}}/" target="_blank"  id='viewMap' class="btn  btn-primary">View Map</a>
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
                    @php
                        $i=0;
                        $countt = $data->countt;
                        $countt2 = $data->countt-1;
                    @endphp
                    <div class="card-header">
                        <div class="card-title">DRY VAN INFORMATION</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 ">
                                <label for="Freight Class">Freight Class</label>
                                <button type="button" class="btn btn-info btn-sm" data-type="1" data-toggle="modal" data-target="#modal_frieght" style="float: right;">Calculate Your Frieght Class</button>
                                <select name="frieght_class" id="frieght_class" class="frieght_class form-control this_save"  required>
                                    <option value="">Select</option>
                                    <option {{($freight->frieght_class == 50) ? 'selected'  : '' }} value="50">50</option>
                                    <option {{($freight->frieght_class == 55) ? 'selected'  : '' }} value="55">55</option>
                                    <option {{($freight->frieght_class == 60) ? 'selected'  : '' }} value="60">60</option>
                                    <option {{($freight->frieght_class == 65) ? 'selected'  : '' }} value="65">65</option>
                                    <option {{($freight->frieght_class == 70) ? 'selected'  : '' }} value="70">70</option>
                                    <option {{($freight->frieght_class == 77.5) ? 'selected'  : '' }} value="77.5">77.5</option>
                                    <option {{($freight->frieght_class == 85) ? 'selected'  : '' }} value="85">85</option>
                                    <option {{($freight->frieght_class == 92.5) ? 'selected'  : '' }} value="92.5">92.5</option>
                                    <option {{($freight->frieght_class == 100) ? 'selected'  : '' }} value="100">100</option>
                                    <option {{($freight->frieght_class == 110) ? 'selected'  : '' }} value="110">110</option>
                                    <option {{($freight->frieght_class == 125) ? 'selected'  : '' }} value="125">125</option>
                                    <option {{($freight->frieght_class == 150) ? 'selected'  : '' }} value="150">150</option>
                                    <option {{($freight->frieght_class == 175) ? 'selected'  : '' }} value="175">175</option>
                                    <option {{($freight->frieght_class == 200) ? 'selected'  : '' }} value="200">200</option>
                                    <option {{($freight->frieght_class == 250) ? 'selected'  : '' }} value="250">250</option>
                                    <option {{($freight->frieght_class == 300) ? 'selected'  : '' }} value="300">300</option>
                                    <option {{($freight->frieght_class == 400) ? 'selected'  : '' }} value="400">400</option>
                                    <option {{($freight->frieght_class == 500) ? 'selected'  : '' }} value="500">500</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="Total Weight in LBS">Total Weight in LBS</label>
                                <input value="{{$data->weight}}" id="Total_Weight_in_LBS" name="total_weight_lbs" class="form-control this_save" required type="number" placeholder="Total Weight in LBS">
                                {{-- {{ dd($freight->toArray()) }} --}}
                            </div>
                            <div class="col-md-4 ">
                                <label for="Freight Class">Shipment Prefences</label>
                                {{-- <select name="shipment_prefences" id="shipment_prefences" class="shipment_prefences form-control this_save"  required>
                                    <option value="">Select</option>
                                    <option {{($freight->shipment_prefences == 'ltl') ? 'selected'  : '' }}   value="ltl">LTL (Less than truck load)</option>
                                    <option {{($freight->shipment_prefences == 'fl') ? 'selected'  : '' }}  value="fl">FTL (Full Truck Load)</option>
                                </select> --}}
                                <select name="shipment_prefences" id="shipment_prefences" class="shipment_prefences form-control this_save" required>
                                    <option value="" disabled {{ empty($freight->shipment_prefences) ? 'selected' : '' }}>Select</option>
                                    <option value="LTL USED COMMERCIAL GOODS" {{ $freight->shipment_prefences == 'LTL USED COMMERCIAL GOODS' ? 'selected' : '' }}>LTL USED COMMERCIAL GOODS</option>
                                    <option value="LTL NEW COMMERCIAL GOODS" {{ $freight->shipment_prefences == 'LTL NEW COMMERCIAL GOODS' ? 'selected' : '' }}>LTL NEW COMMERCIAL GOODS</option>
                                    <option value="LTL USED GOODS" {{ $freight->shipment_prefences == 'LTL USED GOODS' ? 'selected' : '' }}>LTL USED GOODS</option>
                                    <option value="LTL NEW GOODS" {{ $freight->shipment_prefences == 'LTL NEW GOODS' ? 'selected' : '' }}>LTL NEW GOODS</option>
                                    <option value="FTL FULL TRUCK LOAD" {{ $freight->shipment_prefences == 'FTL FULL TRUCK LOAD' ? 'selected' : '' }}>FTL FULL TRUCK LOAD</option>
                                </select>
                            </div>
                            <div class="col-md-4 ">
                                <label class="handling_unit"> Select Handling Unit:</label>
                                 <select class="shipment_prefences form-control this_save" id="handling_unit" name="handling_unit">
                                    <option value="" selected disabled>Select</option>
                                    <option value="Pallet" {{ $freight->handling_unit == 'Pallet' ? 'selected' : '' }}>Pallet</option>
                                    <option value="Crate" {{ $freight->handling_unit == 'Crate' ? 'selected' : '' }}>Crate</option>
                                    <option value="Box" {{ $freight->handling_unit == 'Box' ? 'selected' : '' }}>Box</option>
                                    <option value="Bag" {{ $freight->handling_unit == 'Bag' ? 'selected' : '' }}>Bag</option>
                                    <option value="Bale" {{ $freight->handling_unit == 'Bale' ? 'selected' : '' }}>Bale</option>
                                    <option value="Bundle" {{ $freight->handling_unit == 'Bundle' ? 'selected' : '' }}>Bundle</option>
                                    <option value="Can" {{ $freight->handling_unit == 'Can' ? 'selected' : '' }}>Can</option>
                                    <option value="Carton" {{ $freight->handling_unit == 'Carton' ? 'selected' : '' }}>Carton</option>
                                    <option value="Case" {{ $freight->handling_unit == 'Case' ? 'selected' : '' }}>Case</option>
                                    <option value="Coil" {{ $freight->handling_unit == 'Coil' ? 'selected' : '' }}>Coil</option>
                                    <option value="Cylinder" {{ $freight->handling_unit == 'Cylinder' ? 'selected' : '' }}>Cylinder</option>
                                    <option value="Drum" {{ $freight->handling_unit == 'Drum' ? 'selected' : '' }}>Drum</option>
                                    <option value="Loose" {{ $freight->handling_unit == 'Loose' ? 'selected' : '' }}>Loose</option>
                                    <option value="Pail" {{ $freight->handling_unit == 'Pail' ? 'selected' : '' }}>Pail</option>
                                    <option value="Reel" {{ $freight->handling_unit == 'Reel' ? 'selected' : '' }}>Reel</option>
                                    <option value="Roll" {{ $freight->handling_unit == 'Roll' ? 'selected' : '' }}>Roll</option>
                                    <option value="Pipe" {{ $freight->handling_unit == 'Pipe' ? 'selected' : '' }}>Pipe</option>
                                    <option value="Other" {{ $freight->handling_unit == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <?php
                            // $equipment_type = explode('^*',$freight->equipment_type)   ; ;
                            // $trailer_specification = explode('^*',$freight->trailer_specification)   ; ;

                            // dd($freight->equipment_type, $equipment_type, $trailer_specification);
                            // Using ^* as the delimiter
                            $equipment_type = strpos($freight->equipment_type, '^*') !== false
                                ? explode('^*', $freight->equipment_type)
                                : explode(',', $freight->equipment_type);

                            $trailer_specification = strpos($freight->trailer_specification, '^*') !== false
                                ? explode('^*', $freight->trailer_specification)
                                : explode(',', $freight->trailer_specification);

                            // dd($equipment_type, $trailer_specification);

                            ?>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Freight Class">Equipment Type</label>
                                    <select multiple required  class="form-control select2 equipment_type this_save"  name="equipment_type[]" data-placeholder="Equipment Type" >
                                        <option {{ (in_array('VAN (V)',$equipment_type) ) ? 'selected' : ''  }}>VAN (V)</option>
                                        <option {{ (in_array('REEFER (RE)',$equipment_type) ) ? 'selected' : ''  }}>REEFER (RE)</option>
                                        <option {{ (in_array('FLATBED (F)',$equipment_type) ) ? 'selected' : ''  }}>FLATBED (F)</option>
                                        <option {{ (in_array('STEP DECK (SD)',$equipment_type) ) ? 'selected' : ''  }}>STEP DECK (SD)</option>
                                        <option {{ (in_array('REMOVABLE GOOSENECK (RGN)',$equipment_type) ) ? 'selected' : ''  }}>REMOVABLE GOOSENECK (RGN)</option>
                                        <option {{ (in_array('CONESTOGA (CS)',$equipment_type) ) ? 'selected' : ''  }}>CONESTOGA (CS)</option>
                                        <option {{ (in_array('CONTAINER / DRAYAGE (C)',$equipment_type) ) ? 'selected' : ''  }}>CONTAINER / DRAYAGE (C)</option>
                                        <option {{ (in_array('TRUCK (T)',$equipment_type) ) ? 'selected' : ''  }}>TRUCK (T)</option>
                                        <option {{ (in_array('HAZMAT (hazardous materials)',$equipment_type) ) ? 'selected' : ''  }}>HAZMAT (hazardous materials)</option>
                                        <option {{ (in_array('POWER ONLY (PO)',$equipment_type) ) ? 'selected' : ''  }}>POWER ONLY (PO)</option>
                                        <option {{ (in_array('HOT SHOT (HS)',$equipment_type) ) ? 'selected' : ''  }}>HOT SHOT (HS)</option>
                                        <option {{ (in_array('LOWBOY (LB)',$equipment_type) ) ? 'selected' : ''  }}>LOWBOY (LB)</option>
                                        <option {{ (in_array('ENDUMP (ED)',$equipment_type) ) ? 'selected' : ''  }}>ENDUMP (ED)</option>
                                        <option {{ (in_array('LANDOLL (LD)',$equipment_type) ) ? 'selected' : ''  }}>LANDOLL (LD)</option>
                                        <option {{ (in_array('PARTIAL (PT)',$equipment_type) ) ? 'selected' : ''  }}>PARTIAL (PT)</option>
                                        <option {{ (in_array('20ft container',$equipment_type) ) ? 'selected' : ''  }}>20ft container</option>
                                        <option {{ (in_array('40ft container',$equipment_type) ) ? 'selected' : ''  }}>40ft container</option>
                                        <option {{ (in_array('48ft container',$equipment_type) ) ? 'selected' : ''  }}>48ft container</option>
                                        <option {{ (in_array('53ft container',$equipment_type) ) ? 'selected' : ''  }}>53ft container</option>
                                    </select>
                                    <input type="hidden" id="dauctionnew" name="dauctionnew" value="" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="Freight Class">Trailer Specification</label>
                                <select  multiple data-placeholder="Trailer Specification" name="trailer_specification[]"  class="form-control select2 trailer_specification this_save" >
                                    <option {{ in_array('Air Ride (A)', $trailer_specification) ? 'selected' : '' }}>Air Ride (A)</option>
                                    <option {{ in_array('Blanket Wrap (B)', $trailer_specification) ? 'selected' : '' }}>Blanket Wrap (B)</option>
                                    <option {{ in_array('B-Train (BT)', $trailer_specification) ? 'selected' : '' }}>B-Train (BT)</option>
                                    <option {{ in_array('Chain (CH)', $trailer_specification) ? 'selected' : '' }}>Chain (CH)</option>
                                    <option {{ in_array('Chassis (CS)', $trailer_specification) ? 'selected' : '' }}>Chassis (CS)</option>
                                    <option {{ in_array('Conestoga (CO)', $trailer_specification) ? 'selected' : '' }}>Conestoga (CO)</option>
                                    <option {{ in_array('Curtain (C)', $trailer_specification) ? 'selected' : '' }}>Curtain (C)</option>
                                    <option {{ in_array('Double (2)', $trailer_specification) ? 'selected' : '' }}>Double (2)</option>
                                    <option {{ in_array('Extendable (E)', $trailer_specification) ? 'selected' : '' }}>Extendable (E)</option>
                                    <option {{ in_array('E-Track (ET)', $trailer_specification) ? 'selected' : '' }}>E-Track (ET)</option>
                                    <option {{ in_array('Hazmat (Z)', $trailer_specification) ? 'selected' : '' }}>Hazmat (Z)</option>
                                    <option {{ in_array('Hot Shot (HS)', $trailer_specification) ? 'selected' : '' }}>Hot Shot (HS)</option>
                                    <option {{ in_array('Insulated (N)', $trailer_specification) ? 'selected' : '' }}>Insulated (N)</option>
                                    <option {{ in_array('Lift Gate (LG)', $trailer_specification) ? 'selected' : '' }}>Lift Gate (LG)</option>
                                    <option {{ in_array('Load Out (LO)', $trailer_specification) ? 'selected' : '' }}>Load Out (LO)</option>
                                    <option {{ in_array('Load Ramp (LR)', $trailer_specification) ? 'selected' : '' }}>Load Ramp (LR)</option>
                                    <option {{ in_array('Moving (MV)', $trailer_specification) ? 'selected' : '' }}>Moving (MV)</option>
                                    <option {{ in_array('Open Top (OT)', $trailer_specification) ? 'selected' : '' }}>Open Top (OT)</option>
                                    <option {{ in_array('Oversized (O)', $trailer_specification) ? 'selected' : '' }}>Oversized (O)</option>
                                    <option {{ in_array('Pallet Exchange (X)', $trailer_specification) ? 'selected' : '' }}>Pallet Exchange (X)</option>
                                    <option {{ in_array('Side Kit (S)', $trailer_specification) ? 'selected' : '' }}>Side Kit (S)</option>
                                    <option {{ in_array('Tarp (T)', $trailer_specification) ? 'selected' : '' }}>Tarp (T)</option>
                                    <option {{ in_array('Team Driver (M)', $trailer_specification) ? 'selected' : '' }}>Team Driver (M)</option>
                                    <option {{ in_array('Temp Control (TC)', $trailer_specification) ? 'selected' : '' }}>Temp Control (TC)</option>
                                    <option {{ in_array('Triple (3)', $trailer_specification) ? 'selected' : '' }}>Triple (3)</option>
                                    <option {{ in_array('Vented (V)', $trailer_specification) ? 'selected' : '' }}>Vented (V)</option>
                                    <option {{ in_array('Walking Floor (WF)', $trailer_specification) ? 'selected' : '' }}>Walking Floor (WF)</option>
                                </select>

                            </div>

                            <div class="col-md-6">
                                <label for=" Commodity Details"> Commodity Details</label>
                                <input value="{{ $freight->commodity_detail }}" required name="commodity_detail" type="text" placeholder=" Commodity Details" class="form-control this_save">
                            </div>
                            <div class="col-md-6">
                                <label for="Units">Units</label>
                                <input value="{{ $freight->commodity_unit }}" required type="number" class="form-control this_save" name="commodity_unit" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>
                                    <input type="checkbox" checked value="0" id="Shipment" onclick="($(this).is(':checked')) ? $('#addingmore').show() : $('#addingmore').hide();  " />
                                    Enter Detailed Shipment Information
                                </label>
                            </div>
                        </div>
                        <div id="addingmore" >
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="feild">
                                        <label for="Pickup Date">Pickup Date</label>
                                        <input value="{{ $freight->ex_pickup_date }}" name="ex_pickup_date" type="date" placeholder="Pickup Date" class="form-control this_save">

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="feild">
                                        <label for="Pickup Date">Pickup Time</label>
                                        <input value="{{ $freight->ex_pickup_time }}" type="time" onchange="time(event)" id="appt" name="ex_pickup_time" class="form-control this_save">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="feild">
                                        <label for="Pickup Date">Delivery Date</label>
                                        <input value="{{ $freight->ex_delivery_date }}" type="date" placeholder="Delivery Date" class="form-control this_save" name="ex_delivery_date">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="feild">
                                        <label for="Pickup Date">Delivery Time</label>
                                        <input value="{{ $freight->ex_delivery_time }}" type="time" id="appt" name="ex_delivery_time" class="form-control this_save">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        $selectedPickUpServices = explode('^*',$freight->pick_up_services);
                        $selectedDeliveryServices = explode('^*',$freight->deliver_services);
                        ?>
                        <div class="row Advanced">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>Pickup Services</h3>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <input <?= in_array('Construction_Utility', $selectedPickUpServices) ? 'checked' : '' ?> name="pick_up_services[]" value="Construction_Utility" type="checkbox" class="this_save"> Construction / Utility
                                    </div>
                                    <div class="col-md-4">
                                        <input <?= in_array('Container_Station', $selectedPickUpServices) ? 'checked' : '' ?> name="pick_up_services[]" value="Container_Station" type="checkbox" class="this_save"> Container Station
                                    </div>
                                    <div class="col-md-4">
                                        <input <?= in_array('Exhibition', $selectedPickUpServices) ? 'checked' : '' ?> name="pick_up_services[]" value="Exhibition" type="checkbox" class="this_save"> Exhibition
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <input <?= in_array('Inside_Pickup', $selectedPickUpServices) ? 'checked' : '' ?> name="pick_up_services[]" value="Inside_Pickup" type="checkbox" class="this_save"> Inside Pickup
                                    </div>
                                    <div class="col-md-4">
                                        <input <?= in_array('Lift_Gate_Service', $selectedPickUpServices) ? 'checked' : '' ?> name="pick_up_services[]" value="Lift_Gate_Service" type="checkbox" class="this_save"> Lift Gate Service
                                    </div>
                                    <div class="col-md-4">
                                        <input <?= in_array('Residential', $selectedPickUpServices) ? 'checked' : '' ?> name="pick_up_services[]" value="Residential" type="checkbox" class="this_save"> Residential
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <input <?= in_array('Single_Shipment', $selectedPickUpServices) ? 'checked' : '' ?> name="pick_up_services[]" value="Single_Shipment" type="checkbox" class="this_save"> Single Shipment
                                    </div>
                                    <div class="col-md-4">
                                        <input <?= in_array('Sorting_Segregation', $selectedPickUpServices) ? 'checked' : '' ?> name="pick_up_services[]" value="Sorting_Segregation" type="checkbox" class="this_save"> Sorting / Segregation
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row Advanced">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>Delivery Services</h3>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <input <?= in_array('After_Business_Hours_Delivery', $selectedDeliveryServices) ? 'checked' : '' ?> name="deliver_services[]" value="After_Business_Hours_Delivery" type="checkbox" class="this_save"> After Business Hours Delivery
                                    </div>
                                    <div class="col-md-4">
                                        <input <?= in_array('Construction_Utility', $selectedDeliveryServices) ? 'checked' : '' ?> name="deliver_services[]" value="Construction_Utility" type="checkbox" class="this_save"> Construction / Utility
                                    </div>
                                    <div class="col-md-4">
                                        <input <?= in_array('Appointment', $selectedDeliveryServices) ? 'checked' : '' ?> name="deliver_services[]" value="Appointment" type="checkbox" class="this_save"> Appointment
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <input <?= in_array('Container_Station', $selectedDeliveryServices) ? 'checked' : '' ?> name="deliver_services[]" value="Container_Station" type="checkbox" class="this_save"> Container Station
                                    </div>
                                    <div class "col-md-4">
                                    <input <?= in_array('Exhibition', $selectedDeliveryServices) ? 'checked' : '' ?> name="deliver_services[]" value="Exhibition" type="checkbox" class="this_save"> Exhibition
                                </div>
                                <div class="col-md-4">
                                    <input <?= in_array('In_Bond_Freight', $selectedDeliveryServices) ? 'checked' : '' ?> name="deliver_services[]" value="In_Bond_Freight" type="checkbox" class="this_save"> In Bond Freight
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <input <?= in_array('In_Bond_TIR_Caret', $selectedDeliveryServices) ? 'checked' : '' ?> name="deliver_services[]" value="In_Bond_TIR_Caret" type="checkbox" class="this_save"> In Bond TIR Caret
                                </div>
                                <div class="col-md-4">
                                    <input <?= in_array('Inside_Same_Level_as_Delivery_Vehicle', $selectedDeliveryServices) ? 'checked' : '' ?> name="deliver_services[]" value="Inside_Same_Level_as_Delivery_Vehicle" type="checkbox" class="this_save"> Inside - Same Level as Delivery Vehicle
                                </div>
                                <div class="col-md-4">
                                    <input <?= in_array('Lift_Gate_Service', $selectedDeliveryServices) ? 'checked' : '' ?> name="deliver_services[]" value="Lift_Gate_Service" type="checkbox" class="this_save"> Lift Gate Service
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <input <?= in_array('Residential', $selectedDeliveryServices) ? 'checked' : '' ?> name="deliver_services[]" value="Residential" type="checkbox" class="this_save"> Residential
                                </div>
                                <div class="col-md-4">
                                    <input <?= in_array('Mine_Govt_Airport', $selectedDeliveryServices) ? 'checked' : '' ?> name="deliver_services[]" value="Mine_Govt_Airport" type="checkbox" class="this_save"> Mine / Govt / Airport
                                </div>
                                <div class="col-md-4">
                                    <input <?= in_array('Notification_Prior_Delivery', $selectedDeliveryServices) ? 'checked' : '' ?> name="deliver_services[]" value="Notification_Prior_Delivery" type="checkbox" class="this_save"> Notification Prior Delivery
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="card-footer text-left">
                            @if (in_array('111', $actionaccess) || Auth::user()->userRole->name == 'Admin')
                                <div>
                                    <div class=" ">
                                        <a href="javascript:void(0)" id='checkPrice' class="btn btn-primary">Check
                                            Price</a>
                                    </div>
                                    <div class=" ">
                                        <a href="javascript:void(0)" id='previousCheckPrice'
                                            class="btn btn-primary">Previous Prices</a>
                                    </div>
                                </div>

                            <div>
                                <div>
                                    <button href="javascript:void(0)" id="checkPriceNew" class="btn btn-success">
                                        Check Price (New)
                                    </button>
                                </div>

                                <div class="mt-2">
                                    <a href="javascript:void(0)" id="previousCheckPriceNew" class="btn btn-info">
                                        Previous Prices (New)
                                    </a>
                                </div>
                            </div>

                                <div>
                                    <div class=" ">
                                        <select id="getCheckPrice" name="" class="form-control"
                                            style="display: none">
                                            <option selected="" value="">Select</option>
                                            <option value="car">Freight</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <br>
                            <div>
                                <input type="hidden" name="getCheckPrice" id="getCheckPriceVal" class="this_save">
                                <div class="" id="showCheckPrice">
                                </div>
                            </div>

                    </div>
                   <div class="card-footer text-left">

                    <!-- <a href="#" class="btn btn-danger">Cancle</a> -->
                </div>
            </div>
        </div>
        <!--
        <button type="submit" id="saveBtn" name="saveBtn" style="border: 1px solid;"
                                class="btn btn-outline-primary mg-l-20 float-right">Save
                        </button>
                -->

        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">TIME FRAME</div>
                </div>
                <div class="card-body">
                    <div class='row'>
                        <div class="col">
                            <h5>Pickup</h5>
                            <div class="wd-200 mg-b-30">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="18"
                                                 viewBox="0 0 24 24" width="18">
                                                <path d="M0 0h24v24H0V0z" fill="none"></path>
                                                <path
                                                        d="M20 3h-1V1h-2v2H7V1H5v2H4c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 2v3H4V5h16zM4 21V10h16v11H4z"></path>
                                                <path d="M4 5.01h16V8H4z" opacity=".3"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <input type="text" name="pickup_date" id="pickup_date"
                                           class="form-control this_save "
                                           value="{{ \Carbon\Carbon::parse($data->pickup_date)->format('m/d/Y') }}"
                                           placeholder="MM/DD/YYYY" autocomplete="off" data-parsley-id="">
                                </div>
                            </div>
                        </div>
                        <div class="col ">
                            <h5>Delivery</h5>

                            <div class="wd-200 mg-b-30">

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="18"
                                                 viewBox="0 0 24 24" width="18">
                                                <path d="M0 0h24v24H0V0z" fill="none"></path>
                                                <path
                                                        d="M20 3h-1V1h-2v2H7V1H5v2H4c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 2v3H4V5h16zM4 21V10h16v11H4z"></path>
                                                <path d="M4 5.01h16V8H4z" opacity=".3"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <input type="text" name="delivery_date" id="delivery_date"
                                           class="form-control this_save "
                                           value="{{ \Carbon\Carbon::parse($data->delivery_date)->format('m/d/Y') }}"
                                           placeholder="MM/DD/YYYY" autocomplete="off" data-parsley-id="">
                                </div>
                            </div>

                        </div>
                    </div>

                    &nbsp;
                    <div class="row ">
                        <div class="col-lg-6" id="whenPickUpDate">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="rdiobox">
                                            <input
                                                <?php if ($data->pickup_when == 'before') echo 'checked'; ?> class="this_save"
                                                name="when_pickup" id="pickup1" type="radio" value="before">
                                            <span>Before</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="rdiobox">
                                            <input
                                                <?php if ($data->pickup_when == 'after') echo 'checked'; ?> class="this_save"
                                                name="when_pickup" id="pickup2" type="radio" value="after">
                                            <span>After</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="rdiobox">
                                            <input
                                                <?php if ($data->pickup_when == 'on') echo 'checked'; ?> class="this_save"
                                                name="when_pickup" id="pickup3" type="radio" value="on">
                                            <span>On</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="rdiobox">
                                            <input
                                                <?php if ($data->pickup_when == 'asap') echo 'checked'; ?> class="this_save"
                                                name="when_pickup" id="pickup4" type="radio" value="asap">
                                            <span>ASAP</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6" id="whenDeliveryDate">

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="rdiobox">
                                            <input
                                                <?php if ($data->delivery_when == 'before') echo 'checked'; ?> class="this_save"
                                                name="when_delivery" id="delivery1" type="radio" value="before">
                                            <span>Before</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="rdiobox">
                                            <input
                                                <?php if ($data->delivery_when == 'after') echo 'checked'; ?> class="this_save"
                                                name="when_delivery" id="delivery2" type="radio" value="after">
                                            <span>After</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="rdiobox">
                                            <input
                                                <?php if ($data->delivery_when == 'on') echo 'checked'; ?> class="this_save"
                                                name="when_delivery" id="delivery3" type="radio" value="on">
                                            <span>On</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="rdiobox">
                                            <input
                                                <?php if ($data->delivery_when == 'asap') echo 'checked'; ?> class="this_save"
                                                name="when_delivery" id="delivery4" type="radio" value="asap">
                                            <span>ASAP</span>
                                        </label>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>

                    <div class="card-footer text-left">
                        <!-- <a href="#" class="btn btn-danger">Cancle</a> -->
                    </div>
                    <div class="">
                        <div class="flex_ flex_center gap_new">
                            <a href="javascript:void(0)" id="zipCityDest" class="btn btn-primary mg-r-10">Ship A1 Rates</a>
                            <input type="hidden" value="{{$data->id}}" class="orderID">
                            <div class="priceReq text-left float-left">
                            </div>
                            <!--<a href="javascript:void(0)" id="viewCentral" class="btn btn-primary mg-r-10">View-->
                            <!--    Pricing</a>-->
                            <!--<a href="javascript:void(0)" id="shipa1Rates"-->
                            <!--   class="btn btn-primary mg-r-10">Ship A1 Rates</a>-->
                            <a href="javascript:void(0)" id="previousRecord"
                               class="btn btn-primary mg-r-10">Previous Record</a>
                            <a href="https://www.weather.gov/" target="_blank"
                               class="btn btn-primary mg-r-10">View Weather</a>
                            <a href="https://gasprices.aaa.com/" target="_blank"
                               class="btn btn-primary">Fuel Price</a>
                            <a href="javascript:void(0)" id="previousBookPrice"
                               class="btn btn-primary mg-r-10">Previous Driver Price</a>
                            <a class="btn btn-primary mg-r-10"
                               onclick="history('{{ $data->id }}','{{ $arrayophone[0] }}')"
                               target="_blank">History</a>
                        </div>
                    </div>
                    <div class="row reqPrice"></div>
                </div>
            </div>
            @php
                $readonly='';
                $disable='';
                $disable2='';
                $button = "";
            @endphp
            @if($data->pstatus >=12 && $data->pstatus <=13)
                @if(Auth::user()->userRole->name == 'Admin' || Auth::user()->userRole->name == 'Manager' || Auth::user()->userRole->name == 'Owes Money')
                @else
                    @php
                        $readonly='readonly';
                        $disable='disabled';
                         $button = "<a href='/dashboard' class='btn btn-info'>Submit</a>";
                    @endphp
                @endif
            @elseif(($data->pstatus >= 9 && $data->pstatus <= 17) || $data->pstatus == 19)
                @php
                    $disable2="style='display:none;'";
                     $button = "<a href='/dashboard' class='btn btn-info'>Submit</a>";
                @endphp
            @elseif($data->pstatus == 22 || $data->pstatus == 23)

                @php
                    $readonly='readonly';
                    $disable='disabled';
                  $button = "<a href='/dashboard' class='btn btn-outline-dark mg-l-20 float-right'>Submit</a>";
                @endphp
            @endif
            <div class="col-xl-12" id="test">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Carrier/Payment</div>
                    </div>
                    <div class="card-body">
                        <div class='row '>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <div class='row'>
                                        @if(Auth::user()->userRole->name <> 'Dispatcher')
                                            <div class="col-md-3">
                                                <label class="form-label font-boldd">START PRICE<span class="redcolor">*</span></label>
                                                <input required onkeypress="$(this).css('border-color', 'rgb(92 166 242)');" type="text" placeholder="ORDER STARTING PRICE *"
                                                       id='startPrice' <?php echo $readonly;?>
                                                       name='start_price' value="{{$data->start_price}}"
                                                       class="form-control parsley-error this_save ">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label font-boldd">PRICING & PAYMENT<span class="redcolor">*</span></label>
                                                <input required onkeypress="$(this).css('border-color', 'rgb(92 166 242)');" type="text" placeholder="ORDER BOOKING PRICE *"
                                                       id='orderPrice' <?php echo $readonly;?>
                                                       name='price' value="{{$data->payment}}"
                                                       class="form-control parsley-error this_save ">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label font-boldd">DRIVER PRICE<span class="redcolor">*</span></label>
                                                <input required type="text" placeholder="DRIVER PRICE *"
                                                       id='driverPrice' <?php echo $readonly;?>
                                                       name='driver_price' value="{{ $data->driver_price }}"
                                                       class="form-control parsley-error this_save " required onkeypress="$(this).css('border-color', 'rgb(92 166 242)');">
                                            </div>
                                        @endif
                                        <?php
                                        $coupon_price = 0;
                                        $coupon_number = '';
                                        if(isset($data->coupon_id))
                                        {
                                            $coupon = \App\Coupon::find($data->coupon_id);
                                            if(isset($coupon->id))
                                            {
                                                $coupon_price = $coupon->coupon_price ?? 0;
                                                $coupon_number = $coupon->coupon_number ?? '';
                                            }
                                        }
                                        ?>
                                        <div class="col-md-3">
                                            <label class="form-label font-boldd">Coupon Number</label>
                                            <input type="text" placeholder="Coupon Number" id='coupon_number' value="{{$coupon_number}}" name='coupon_number' class="form-control">
                                            @if($coupon_price > 0)
                                                <div class="alert text-success p-0"><strong>${{$coupon_price}} Coupon applied!</strong></div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="ckbox">
                                        <input <?php echo $disable;?> class="this_save"
                                               @if(!empty($data->need_deposit)) value="{{$data->need_deposit}}"
                                               checked @else value="yes" @endif type="checkbox" name="needDeposit"
                                               id="needDeposit"
                                               data-parsley-multiple="needDeposit">
                                        <span>&nbsp;Deposit Amount?</span>
                                    </label>
                                </div>


                                <div id="depositContent">
                                    @if(!empty($data->need_deposit))
                                        <label class="label font-boldd tx-black">Deposit Amount <span class="tx-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i style="color: #705ec8;font-size:larger"
                                                       class="fa fa-usd tx-16 lh-0 op-6"></i>
                                                </div>
                                            </div>
                                            <input <?php echo $readonly;?> class="form-control this_save "
                                                   autocomplete="nope" type="text" required
                                                   name="depositAmount" value="{{$data->deposit_amount}}" placeholder=""
                                                   id="depositAmount" style="width: 90%">


                                        </div>
                                    @endif

                                </div>
                                &nbsp;

                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="rdiobox radio_btn">

                                        <input <?php echo $disable;?> name="central_chk"
                                               @if($data->booking_confirm == "confirm") checked @endif id="central_chk1"
                                               class="this_save" type="radio" value="confirm">
                                        <span>DB List</span>
                                    </label>


                                    <label>
                                        <input <?php echo $disable;?> name="central_chk"
                                               @if($data->booking_confirm == "may be") checked @endif  id="central_chk2"
                                               class="this_save" type="radio" value="may be">
                                        <span>DB May Be List</span>
                                    </label>


                                    <label>
                                        <input <?php echo $disable;?> name="central_chk" id="central_chk3"
                                               @if($data->booking_confirm == "none") checked @endif class="this_save"
                                               type="radio" value="none">
                                        <span>None</span>
                                    </label>
                                    &nbsp;
                                    <div id="confirm_book" style="display:flex">
                                        @if($data->booking_confirm == "confirm")
                                            <input <?php echo $readonly;?> style="width: 150px;"
                                                   class="form-control this_save" value="{{$data->company_name}}"
                                                   autocomplete="nope" type="text" name="company_name" id="company_name"
                                                   placeholder="Company Name">

                                            <input <?php echo $readonly;?> style="width: 150px;"
                                                   class="form-control this_save" value="{{$data->company_price}}"
                                                   autocomplete="nope" type="text" name="company_price"
                                                   id="company_price" placeholder="Price">
                                        @endif

                                    </div>
                                    <div id="may_be_book" style="display:flex">
                                        @if($data->booking_confirm == "may be")

                                            <input <?php echo $readonly;?>  style="width: 150px;"
                                                   class="form-control this_save" value="{{$data->company_name}}"
                                                   autocomplete="nope" type="text" name="company_name"
                                                   id="company_name2" placeholder="Company Name">

                                            <input <?php echo $readonly;?>  style="width: 150px;"
                                                   class="form-control this_save" value="{{$data->company_price}}"
                                                   autocomplete="nope" type="text" name="company_price"
                                                   id="company_price2" placeholder="Price">

                                            <input <?php echo $readonly;?> style="width: 150px;"
                                                   class="form-control this_save" value="{{$data->company_comments}}"
                                                   autocomplete="nope" type="text" name="company_comments"
                                                   id="company_comments" placeholder="Comments">
                                        @endif

                                    </div>

                                </div>
                                &nbsp;
                            </div>
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="label font-boldd tx-black">Storage Fees</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-usd tx-16 lh-0 op-6"></i>
                                                    </div>
                                                </div>
                                                <input <?php echo $readonly;?> class="form-control this_save "
                                                       autocomplete="nope" type="text"
                                                       name="storage_fees" value="{{$data->storage_fees}}" placeholder=""
                                                       id="storage_fees" onkeyup="this.value > 0 ? $('#paybyy').show() : $('#paybyy').hide()"
                                                >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3" @if(isset($data->storage_fees)) style="display:block;" @else style="display:none;" @endif id="paybyy">
                                        <div class="form-group">
                                            <label class="label font-boldd tx-black">Pay By</label>
                                            <select <?php echo $disable;?> id="pay_by" name="pay_by"
                                                    class="form-control this_save ">

                                                <option disabled="" selected="">SELECT</option>
                                                <option @if($data->pay_by == "Driver") selected="selected"
                                                        @endif value="Driver">Driver
                                                </option>
                                                <option @if($data->pay_by == "Customer") selected="selected"
                                                        @endif value="Customer">Customer
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="label font-boldd tx-black">Other Fees</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-usd tx-16 lh-0 op-6"></i>
                                                    </div>
                                                </div>
                                                <input <?php echo $readonly;?> class="form-control this_save "
                                                       autocomplete="nope" type="text"
                                                       name="other_fees" value="{{$data->other_fees}}" placeholder=""
                                                       id="other_fees"
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="label font-boldd tx-black">Price to Pay
                                        Carrier</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-usd tx-16 lh-0 op-6"></i>
                                            </div>
                                        </div>
                                        <input <?php echo $readonly;?> class="form-control this_save "
                                               autocomplete="nope" type="text"
                                               name="pay_carrier" value="{{$data->pay_carrier}}" placeholder=""
                                               id="payCarrier">
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-6 mt-5"></div>

                            <!--<div class="col-lg-6 mt-5">-->
                            <!--    <label class="rdiobox mb-0 mr-5">-->
                        <!--        <input @if(isset($data->vehicle)) @endif class="this_save" name="vehicle"-->
                        <!--               @if($data->vehicle == "quick_pay") checked @endif id="carrier_status_1"-->
                            <!--               type="radio"-->
                            <!--               value="quick_pay"-->
                            <!--               data-parsley-multiple="carrier_status">-->
                            <!--        <span>Quick Pay</span>-->
                            <!--    </label>-->


                            <!--    <label class="rdiobox mb-0 ml-5">-->
                        <!--        <input @if(isset($data->vehicle)) @endif class="this_save" name="vehicle" id="carrier_status_2"-->
                            <!--               type="radio"-->
                            <!--               value="cod"-->
                        <!--               @if($data->vehicle == "cod") checked @endif-->
                            <!--               data-parsley-multiple="carrier_status">-->
                            <!--        <span>COD</span>-->
                            <!--    </label>-->

                            <!--</div>-->

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="label font-boldd tx-black">COD/COP
                                        Amount</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-usd tx-16 lh-0 op-6"></i>
                                            </div>
                                        </div>
                                        <input <?php echo $readonly;?> class="form-control this_save "
                                               autocomplete="nope"
                                               style="width: 80%"
                                               type="text" name="cod_cop" value="{{$data->cod_cop}}" placeholder=""
                                               id="copcodAmount">
                                    </div>

                                </div>
                                <div id="copcodPart" @if($data->balance  >= 0 )  @else  style="display: none" @endif >
                                    <div class="form-group">
                                        <label class="form-control -label font-boldd tx-black border_none">COD/COP
                                            Payment
                                            Method </label>
                                        <select <?php echo $disable;?> id="payment_method" name="payment_method"
                                                class="form-control this_save ">

                                            <option disabled="" selected=""></option>
                                            <option @if($data->payment_method == "Cash/Certified Funds") selected="selected"
                                                    @endif value="Cash/Certified Funds">Cash/Certified Funds
                                            </option>
                                            <option @if($data->payment_method == "Check") selected="selected"
                                                    @endif value="Check">Check
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control -label font-boldd tx-black border_none">COD/COP
                                            Location</label>
                                        <select <?php echo $disable;?> id="cod_cop_loc" name="cod_cop_loc"
                                                class="form-control this_save ">
                                            <option disabled="" selected=""></option>
                                            <option @if($data->cod_cop_loc == "Pickup") selected="selected"
                                                    @endif  value="Pickup">Pickup
                                            </option>
                                            <option @if($data->cod_cop_loc == "Delivery") selected="selected"
                                                    @endif value="Delivery">Delivery
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="label font-boldd tx-black">Balance
                                        Amount</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-usd tx-16 lh-0 op-6"></i>
                                            </div>
                                        </div>
                                        <input <?php echo $readonly;?> class="form-control this_save " type="text"
                                               name="balance" value="{{$data->balance}}"
                                               readonly=""
                                               placeholder="" id="balAmount">
                                    </div>
                                </div>

                                <div id="balPart" @if($data->balance > 0)  @else  style="display: none" @endif>
                                    <div class="form-group">
                                        <label class="form-control -label font-boldd tx-black border_none">Balance
                                            Payment
                                            Method <span class="tx-danger">*</span></label>
                                        <select <?php echo $disable;?> id="balance_method" name="balance_method"
                                                class="form-control this_save ">
                                            <option disabled="">Select</option>
                                            <option @if($data->balance_method == "Cash") selected @endif value="Cash">
                                                Cash
                                            </option>
                                            <option @if($data->balance_method == "Certified Funds")  selected
                                                    @endif value="Certified Funds">Certified Funds
                                            </option>
                                            <option @if($data->balance_method == "Company Check") selected
                                                    @endif value="Company Check">Company Check
                                            </option>
                                            <option @if($data->balance_method == "Comchek") selected
                                                    @endif value="Comchek">Comchek
                                            </option>
                                            <option @if($data->balance_method == "TCH") selected @endif value="TCH">
                                                TCH
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control -label font-boldd tx-black border_none">Balance
                                            Payment
                                            Time <span class="tx-danger">*</span></label>
                                        <select <?php echo $disable;?> id="balance_time" name="balance_time"
                                                class="form-control this_save ">
                                            <option disabled=""></option>
                                            <option @if($data->balance_time == "Immediately") selected
                                                    @endif value="Immediately">Immediately
                                            </option>
                                            <option @if($data->balance_time == "2 Business Days (Quick Pay)") selected
                                                    @endif value="2 Business Days (Quick Pay)">2 Business Days
                                                (QuickPay)
                                            </option>
                                            <option @if($data->balance_time == "5 Business Days") selected
                                                    @endif value="5 Business Days">5 Business Days
                                            </option>
                                            <option @if($data->balance_time == "10 Business Days") selected
                                                    @endif value="10 Business Days">10 Business Days
                                            </option>
                                            <option @if($data->balance_time == "15 Business Days") selected
                                                    @endif value="15 Business Days">15 Business Days
                                            </option>
                                            <option @if($data->balance_time == "30 Business Days") selected
                                                    @endif value="30 Business Days">30 Business Days
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control -label font-boldd tx-black border_none">Balance
                                            Payment
                                            Terms Begin On <span class="tx-danger">*</span></label>
                                        <select <?php echo $disable;?> id="terms" name="terms"
                                                class="form-control this_save ">
                                            <option disabled=""></option>
                                            <option @if($data->terms == "Pickup") selected @endif  value="Pickup">
                                                Pickup
                                            </option>
                                            <option @if($data->terms == "Delivery") selected @endif  value="Delivery">
                                                Delivery
                                            </option>
                                            <option @if($data->terms == "Receiving a Signed Bill of Lading border_none") selected
                                                    @endif  value="Receiving a Signed Bill of Lading border_none">
                                                Receiving
                                                a Signed
                                                Bill of Lading *
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="" id="alertMSG">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label font-boldd border_none">ADDITIONAL INFORMATION</label>
                                    <textarea id="additional_2" name="additional_2" rows="8" <?php echo $readonly;?>
                                    class="form-control this_save "
                                              placeholder="Enter any special instructions, notes from customer or details regarding this shipment...">{{$data->additional_2}}</textarea>


                                </div>

                            </div>
                        </div>
                        <div class="row px-4 mx-2">
                    <di class="col-sm-12">
                        <div class="form-group">
                            <label class="ckbox">
                                <input class="this_save" type="checkbox" name="boat_on_trailer" id="boat_on_trailer"
                                    value="1" @if ($data->boat_on_trailer == 1) checked @endif
                                    data-parsley-multiple="boat_on_trailer">
                                <span>&nbsp;Is your freight already on a trailer?</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="ckbox">
                                <input class="this_save" type="checkbox" name="stackable" id="stackable"
                                    value="1" @if ($freight->stackable == 1) checked @endif
                                    data-parsley-multiple="stackable">
                                <span>&nbsp;Stackable</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="ckbox">
                                <input class="this_save" type="checkbox" name="hazardous" id="hazardous"
                                    value="1" @if ($freight->hazardous == 1) checked @endif
                                    data-parsley-multiple="hazardous">
                                <span>&nbsp;Hazardous</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="ckbox">
                                <input class="this_save" type="checkbox" name="protect_from_freezing" id="protect_from_freezing"
                                    value="1" @if ($freight->protect_from_freezing == 1) checked @endif
                                    data-parsley-multiple="protect_from_freezing">
                                <span>&nbsp;Protect From Freezing</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="ckbox">
                                <input class="this_save" type="checkbox" name="sort_segregate" id="sort_segregate"
                                    value="1" @if ($freight->sort_segregate == 1) checked @endif
                                    data-parsley-multiple="sort_segregate">
                                <span>&nbsp;Sort Segregate</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="ckbox">
                                <input class="this_save" type="checkbox" name="blind_shipment" id="blind_shipment"
                                    value="1" @if ($freight->blind_shipment == 1) checked @endif
                                    data-parsley-multiple="blind_shipment">
                                <span>&nbsp;Blind Shipment</span>
                            </label>
                        </div>
                    </di>
                    {{-- <di class="col-sm-6">
                        <div class="form-group">
                            <label class="ckbox">
                                <input class="this_save" type="checkbox" name="modification" id="modification"
                                    value="yes" @if ($data->modification != null) checked @endif
                                    data-parsley-multiple="modification">
                                <span>&nbsp;Modification</span>
                            </label>
                        </div>

                        <div class="input-form div-modify_info"
                            @if ($data->modify_info == null) style="display: none;" @endif>
                            <label class="d-block"> Modification Information:</label>
                            <input class="form-control this_save" type="text" id="modify_info" name="modify_info"
                                value={{ $data->modify_info }} />
                        </div>
                    </di> --}}
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="ckbox">
                                <input class="this_save" type="checkbox" name="available_at_auction"
                                    id="available_at_auction" value="yes"
                                    @if ($data->available_at_auction != null) checked @endif
                                    data-parsley-multiple="available_at_auction">
                                <span>&nbsp;Available at Auction?</span>
                            </label>
                        </div>

                        <div class="input-form div-link"
                            @if ($data->link == null) style="display: none;" @endif>
                            <label class="d-block"> Enter Link:</label>
                            <input class="form-control this_save" type="url" id="link" name="link"
                                value={{ $data->link }} />
                        </div>
                        </div>
                        <div class="col-sm-6">
                            <h5>Image</h5>
                            <div class="wd-200 mg-b-30">
                                <div class="input-group">
                                    @if ($data->image)
                                        @php
                                            // Explode the image URLs
                                            $imageUrls = explode('*^', $data->image);
                                        @endphp

                                        @foreach ($imageUrls as $imageUrl)
                                            <div class="mb-2">
                                                {{-- <a href="{{ $imageUrl }}" target="_blank">View Image</a> --}}
                                                <a href="{{ $imageUrl }}" target="_blank">
                                                    <img width='50' src='{{ $imageUrl }}' style="border: 1px solid #5da6f2; border-radius: 5px;">
                                                </a>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    </div>
                    <div class="card-footer text-left">

                        <input type="button" <?php echo $disable;?> <?php echo $disable2;?> id='newCust' class="btn btn-primary"
                               value="New Customer Order"/>
                        <input type="button" <?php echo $disable;?> <?php echo $disable2;?> id='oldCust' value="Old Customer Order"
                               class="btn btn-primary"/>
                        @if($data->pstatus <= 6)
                            <button type="button"  onclick="validate()"   class="btn btn-outline-dark mg-l-20 float-right">
                                Change Status
                            </button>
                        @endif

                        @if(!empty($button))
                            <?php echo $button ?>
                        @endif


                    </div>
                    <div class="col-lg-12" id="payCondition"></div>
                </div>
            </div>

        </div>

        <!-- End Row-->
        <input type="hidden" name="orderid" id="orderid_find" value="{{ $data->id }}"/>
    </form>


    <!-- New Price Modal -->
    <div class="modal fade" id="priceModalNew" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Pricing Result</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div id="priceModalNewError" class="mb-3"></div>

                    <!-- Market / Primary summary -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-3">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <strong>Market Details</strong>
                                    <span class="badge badge-secondary" id="cacheHitBadge" style="display:none;">Cache Hit</span>
                                </div>
                                <div class="card-body" id="marketDetailsBox"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Per vehicle cards -->
                    <div id="vehicleCardsWrap"></div>

                    <!-- Breakdown table -->
                    <div class="card mt-3" id="vehiclesBreakdownBox" style="display:none;">
                        <div class="card-header"><strong>Detail Prices</strong></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead>
                                    <tr>
                                        <th>Vehicle</th>
                                        <th>Mode</th>
                                        <th>Driver Low</th>
                                        <th>Driver Mid</th>
                                        <th>Driver High</th>
                                        <th>Offer</th>
                                        <th>Commission</th>
                                        <th>Listed</th>
                                        <th>Dispatch</th>
                                        <th>Distance</th>
                                        <th>Status</th>
                                        <th>Cache</th>
                                    </tr>
                                    </thead>
                                    <tbody id="vehiclesBreakdownTbody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div id="rawPayloadBox" style="display:none;">
                        <pre class="mb-0" id="rawPayloadPre"></pre>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- New History Modal -->
    <div class="modal fade" id="priceHistoryModalNew" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Previous Price Requests</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Origin</th>
                                <th>Destination</th>
                                <th>Vehicle</th>
                                <th>Driver (mid)</th>
                                <th>Offer</th>
                                <th>Cache</th>
                            </tr>
                            </thead>
                            <tbody id="priceHistoryTbody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div id="modal_frieght" style="margin-top: 100px" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">Select Unit</label>
                            <select name="" id="f_unit" class="form-control">
                                <option value="inch">Inch</option>
                                <option value="feet">Feet</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="">Length</label>
                            <input type="number" name="length" id="f_length" value="{{ $data->length_ft . '.' . $data->length_in }}" placeholder="Enter Length "
                                   class="form-control"/>
                        </div>
                        <div class="col-md-4">
                            <label for="">Width</label>
                            <input type="number" name="Width" id="f_width" value="{{ $data->width_ft . '.' . $data->width_in }}" placeholder="Enter Width"
                                   class="form-control"/>
                        </div>
                        <div class="col-md-4">
                            <label for="">Height</label>
                            <input type="number" name="Height" id="f_height" value="{{ $data->height_ft . '.' . $data->height_in }}" placeholder="Enter Height"
                                   class="form-control"/>
                        </div>
                        <div class="col-md-4">
                            <label for="">Weight</label>
                            <input type="number" name="Weight" id="f_weight" value="{{ $data->weight }}" placeholder="Enter Weight"
                                   class="form-control"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-info" onclick="freight_calc()">
                            Calculator
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

    <div id="btn_pstatus" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"><div class="modal-content bd-0 tx-14">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h3 class="tx-30 mg-b-0 tx-uppercase tx-inverse tx-bold">Change Payment Status</h3>
                </div>
                <div class="modal-body pd-25 pl-20 pr-20 " style=" height: 20rem; ">
                    <div class="col-sm-12 col-md-12 ">
                        <div class="form-group">
                            <select name="pstatus" onchange="$('#pstatus2').val(this.value);if($(this).val() == '3'){ $('#ask_div').show(); }else{ $('#ask_div').hide();}" id="pstatus" required="" class="form-control this_save" tabindex="-1" aria-hidden="true">
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
                    <div class="col-sm-12 col-md-12">
                        <div class="form-group">
                            <label>Expected Date</label>
                            <input type="date" class="form-control this_save" name="expected_date">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12" id="ask_div" style="display:none">
                        <div class="form-group">
                            <label>Asking Low</label>
                            <input type="number" min="0" onkeyup="$('#asking_low').val(this.value)" class="form-control this_save">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 ">
                        <div class="form-group">
                            <label>History</label>
                            <textarea class="form-control this_save" onkeyup="$('#order_history').val(this.value)"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer pd-25 pl-20 pr-20">
                    <button type="submit"  onclick="if($('#pstatus').val()){$('#form').submit()}else{ alert('Please Select Status')}"
                            style="border: 1px solid;" id="savceChanges"
                            class="btn btn-outline-primary mg-l-20 float-right">Save
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div id="alreadyCreditCard" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"><div class="modal-content bd-0 tx-14">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-30 mg-b-0 tx-uppercase tx-inverse tx-bold">CREDIT CARD INFO</h6>
                </div>
                <div class="modal-body pd-25 pl-20 pr-20">
                    <div class="card card-people-list mg-y-20" id="creditCardInfo">
                        <div class="row" id="creditModal">
                            <div class="col-lg-12">
                                <div class="media-list" style="overflow:scroll">
                                    <table class="table table-responsive">
                                        <tr>
                                            <th>Order#id</th>
                                            <th>Type</th>
                                            <th>Card</th>
                                            <th>Card Expire</th>
                                            <th>Phone</th>
                                        </tr>
                                        @if(count($credit_card_data) > 0)
                                            @foreach($credit_card_data as $val)
                                                <?php
                                                $cards = explode('*^', $val->card_no);
                                                $card_type = explode('*^', $val->card_type);
                                                $card_expire = explode('*^', $val->card_expiry_date);
                                                ?>
                                                <div class="media">
                                                    @foreach($card_type as $key=>$val2)
                                                        <?php
                                                        //$cardd = $cards[$key];

                                                        // if(Auth::user()->userRole->name == 'Admin')
                                                        // {
                                                        //     $cardd = $cards[$key];
                                                        //     $new = $val->main_ph;
                                                        // }
                                                        // else
                                                        // {
                                                        $cardd = '';
                                                        if(isset($cards[$key]))
                                                        {
                                                            $cardd = 'xxxx - xxxx - xxxx -'.substr($cards[$key], -4);
                                                        }
                                                        $digits = \App\PhoneDigit::first();

                                                        $new = putX($digits->hide_digits,$digits->left_right_status,$val->main_ph);
                                                        // }
                                                        ?>
                                                        <tr>
                                                            @if($val2 == "visa")
                                                                <td><a href="/searchData?search={{$val->orderId}}">OrderId#{{$val->orderId}}</a>
                                                                </td>
                                                                <td><img src="{{asset('visa.png')}}"
                                                                         style="margin: 11px; padding: 0px;height: 30px"
                                                                         alt=""></td>
                                                                <td>{{$cardd}}</td>
                                                                <td>{{$card_expire[$key]}}</td>
                                                                <td>{{$new}}</td>
                                                            @else
                                                                <td><a href="/searchData?search={{$val->orderId}}">OrderId#{{$val->orderId}}</a>
                                                                </td>
                                                                <td><img src="{{asset('master.png')}}"
                                                                         style=" margin: 11px; padding: 0px;;height: 30px"
                                                                         alt=""></td>
                                                                <td>{{$cardd}}</td>
                                                                <td>{{$card_expire[$key]}}</td>
                                                                <td>{{$new}}</td>

                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        @endif
                                        @if(count($old) > 0)
                                            @foreach($old as $val)
                                                <?php
                                                $cards = explode('^*-', $val->card_number);
                                                $card_type = explode('^*-', $val->card_type);
                                                $card_expire = explode('^*-', $val->card_exp);
                                                ?>
                                                <div class="media">
                                                    @foreach($card_type as $key=>$val2)
                                                        <?php
                                                        //$cardd = $cards[$key];
                                                        // if(Auth::user()->userRole->name == 'Admin')
                                                        // {
                                                        //     $cardd = $cards[$key];
                                                        //     $new = $val->main_ph;
                                                        // }
                                                        // else
                                                        // {
                                                        $cardd = '';
                                                        if(isset($cards[$key]))
                                                        {
                                                            $cardd = 'xxxx - xxxx - xxxx -'.substr($cards[$key], -4);
                                                        }
                                                        // $new = '(xxx) xxx-'.substr($val->main_ph, -4);
                                                        $digits = \App\PhoneDigit::first();

                                                        $new = putX($digits->hide_digits,$digits->left_right_status,$val->main_ph);
                                                        // }
                                                        ?>
                                                        <tr>
                                                            @if($val2 == "visa")
                                                                <td><a href="/searchData?search={{$val->id}}">OrderId#{{$val->id}}</a>
                                                                </td>
                                                                <td><img src="{{asset('visa.png')}}"
                                                                         style="margin: 11px; padding: 0px;height: 30px"
                                                                         alt=""></td>
                                                                <td>{{$cardd}}</td>
                                                                <td>{{$card_expire[$key]}}</td>
                                                                <td>{{$new}}</td>
                                                            @else
                                                                <td><a href="/searchData?search={{$val->id}}">OrderId#{{$val->id}}</a>
                                                                </td>
                                                                <td><img src="{{asset('master.png')}}"
                                                                         style=" margin: 11px; padding: 0px;;height: 30px"
                                                                         alt=""></td>
                                                                <td>{{$cardd}}</td>
                                                                <td>{{$card_expire[$key]}}</td>
                                                                <td>{{$new}}</td>

                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- modal-dialog -->
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

      <div class="modal fade" id="responseModal" tabindex="-1" role="dialog" aria-labelledby="responseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="responseModalLabel">Response</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modalMessage"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <!--
        <div class="modal fade " role="dialog" id="modaldemo8" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered text-center" role="document">
                <div class="modal-content modal-content-demo ">
                    <div class="modal-header heading_style">
                        <h1 class="heading_style heading_font ">ORDER ON PHONE</h1>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-lg-6">
                                <h5 class=" modal_subtitle">Customer Number</h5>
                            </div>
                            <div class="col-lg-6">
                                <h5 class=" modal_subtitle">Unknown Number</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">

                                    <label class="custom-control custom-radio">
                                        <input name="mainPh" id="yes" type="radio" value="1">
                                        <span>Yes</span>
                                    </label>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">

                                    <label class="custom-control custom-radio">
                                        <input name="mainPh" id="no" type="radio" value="2">
                                        <span>No</span>
                                    </label>

                                </div>
                            </div>
                        </div>
                        <div class="row number_no" style='display:none'>
                            <div class="col-lg-12">
                                <input type="text" class="form-control this_save " name="custName" id="custName"
                                       placeholder="Enter Customer Name">
                            </div>
                            <div class="col-lg-12 mt-1">
                                <textarea name="addInfo" class="form-control this_save " id="addInfo" cols="5" rows="3"
                                          placeholder="Enter Additional Info"></textarea>
                            </div>
                        </div>

                        <form name="createnew" id="createnew_form" action="" method="post">
                            @csrf
            <div class="row number_yes" style='display:none'>
                <div class="col-lg-12">

                    <input type="text" class="form-control  ophonev"
                           onkeyup="phone_check(this.value)"
                           name="mainPhNum" id="PhNum" placeholder="Enter Phone Number">
                </div>
            </div>

        </form>
    </div>
    <div class="modal-footer">
        <label id="show_total"></label>
        <button class="btn btn-indigo" id="update_previous" style="display: none;" type="button">Update
            Previous
        </button>


        <button class="btn btn-indigo" style="display:none" id="create_new" onclick="save_phon()" style=""
                type="button">Create New
        </button>
        </form>


    </div>
</div>
</div>
</div>
-->

@endsection

@section('extraScript')
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.2/js/toastr.min.js"></script>
    <script>
        function history(order_id, ophone) {



            if (!order_id || !ophone) {
                not7();

            } else {

                var url = `/get_last_5_2?phone_no=${btoa(ophone)}&order_id=${btoa(order_id)}`;
                window.open(url, 'Previous Orders',
                    'height=400,width=600,left=350,top=250,toolbar=No,location=No,scrollbars=Yes,status=No,resizable=Yes,fullscreen=No,directories=No,menubar=No,copyhistory=No'
                );

            }

        }

        $('#d_zip1').keyup(function () {
            $('#show_previous_orders').html('');
            $('#show_cancellation_orders').html('');
            var d_zip1 = $(this);
            var dterminal = $('#dterminal');
            var daddress = $('#daddress');
            var dacutionphoNo = $('#dacutionphoNo');
            var nav = $(this).parents('.form-group').siblings('.nav');
            if(d_zip1.val() == '')
            {
                nav.children().remove();
                nav.hide();
                daddress.val('');
                dacutionphoNo.val('');
            }
            else{
                $.ajax({
                    url: "{{url('/get_zip')}}",
                    type: "GET",
                    dataType: "json",
                    data: {d_zip1:d_zip1.val()},
                    success: function (res){
                        nav.show();
                        nav.children().remove();
                        $.each(res, function (){
                            nav.append(`
                                <li class="nav-item selectAdd2">
                                    <a class="nav-link" href="javascript:void(0)">${this}</a>
                                </li>
                            `);
                        });
                        $('.selectAdd2').click(function(){
                            $(this).parent('.nav').children().remove();
                            $(this).parent('.nav').hide();
                            $('#d_zip1').val($(this).children('a').text());
                            getOldData();
                            getCancelationData();

                            var getZip = $(this).children('a').text();
                            if(dterminal.val() == 2 || dterminal.val() == 3 || dterminal.val() == 4)
                            {
                                $.ajax({
                                    url:"{{url('/new-auction-detail')}}",
                                    type: "GET",
                                    dataType: "json",
                                    data: {zip_code:getZip,terminal:dterminal.val()},
                                    success:function(res)
                                    {
                                        if(res.data.address)
                                        {
                                            daddress.val(res.data.address);
                                            dacutionphoNo.val(res.data.phone);
                                        }
                                        else{
                                            daddress.val('');
                                            dacutionphoNo.val('');
                                        }
                                    }
                                });
                            }
                            else{
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
            $('#show_previous_orders').html('');
            $('#show_cancellation_orders').html('');
            var o_zip1 = $(this);
            var oterminal = $('#oterminal');
            var oaddress = $('#oaddress');
            var oacutionphoNo = $('#oacutionphoNo');
            var nav = $(this).parents('.form-group').siblings('.nav');
            if(o_zip1.val() == '')
            {
                nav.children().remove();
                nav.hide();
                oaddress.val('');
                oacutionphoNo.val('');
            }
            else{
                $.ajax({
                    url: "{{url('/get_zip')}}",
                    type: "GET",
                    dataType: "json",
                    data: {d_zip1:o_zip1.val()},
                    success: function (res){
                        nav.show();
                        nav.children().remove();
                        $.each(res, function (){
                            nav.append(`
                            <li class="nav-item selectAdd">
                                <a class="nav-link" href="javascript:void(0)">${this}</a>
                            </li>
                        `);
                        });
                        $('.selectAdd').click(function(){
                            $(this).parent('.nav').children().remove();
                            $(this).parent('.nav').hide();
                            $('#o_zip1').val($(this).children('a').text());
                            getOldData();
                            getCancelationData();

                            var getZip = $(this).children('a').text();
                            if(oterminal.val() == 2 || oterminal.val() == 3 || oterminal.val() == 4)
                            {
                                $.ajax({
                                    url:"{{url('/new-auction-detail')}}",
                                    type: "GET",
                                    dataType: "json",
                                    data: {zip_code:getZip,terminal:oterminal.val()},
                                    success:function(res)
                                    {
                                        if(res.data.address)
                                        {
                                            oaddress.val(res.data.address);
                                            oacutionphoNo.val(res.data.phone);
                                        }
                                        else{
                                            oaddress.val('');
                                            oacutionphoNo.val('');
                                        }
                                    }
                                });
                            }
                            else{
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

        function getOldData() {
            $('#show_previous_orders').html('');
            var originzsc = $('#o_zip1').val();
            var destinationzsc = $('#d_zip1').val();
            var ophone = $('#ophone2').val();
            var orderid = $('#orderid_find').val();

            // Check if the fields are not empty
            if (originzsc || destinationzsc || ophone || orderid) {
                $.ajax({
                    url: "{{ url('/get_old_data_phone_zip') }}",
                    type: "GET",
                    dataType: "json",
                    data: {
                        ophone: ophone,
                        destinationzsc: destinationzsc,
                        originzsc: originzsc,
                        orderid: orderid,
                    },
                    success: function(res) {
                        if(res.status == 1) {
                            var orderHtml = `<table class='table table-responsive'>
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Order Name</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;

                            // Loop through the response data and create table rows
                            res.data.forEach(function (order) {
                                orderHtml += `<tr>
                                    <td><a href="{{ URL('searchData?search=') }}${order.id}" target="_blank">${order.id}</a></td>
                                    <td>${order.oname}</td>
                                    <td>${order.created_at}</td>
                                  </tr>`;
                            });

                            // Close the tbody and table tags
                            orderHtml += `</tbody></table>`;

                            // Append the entire table to the container
                            $('#show_previous_orders').html(orderHtml);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("There was an error with the request:", error);
                    }
                });
            }
        }


        function getCancelationData() {
            $('#show_cancellation_orders').html('');
            var originzsc = $('#o_zip1').val();
            var destinationzsc = $('#d_zip1').val();
            var ophone = $('#ophone').val();
            var orderid = $('#orderid_find').val();

            if (originzsc || destinationzsc || ophone || orderid) {
                $.ajax({
                    url: "{{ url('/get_canelation_data') }}",
                    type: "GET",
                    dataType: "json",
                    data: {
                        ophone: ophone,
                        destinationzsc: destinationzsc,
                        originzsc: originzsc,
                        orderid: orderid,
                    },
                    success: function(res) {
                        if (res.status == 1) {
                            let html = `<h5 class="mb-3">Cancellation Orders</h5>`;

                            function generateTable(title, orders) {
                                if (orders.length === 0) return '';
                                let table = `<h6 class="mt-4">${title}</h6>
                            <table class='table table-responsive'>
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Order Name</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                                orders.forEach(function(order) {
                                    table += `<tr>
                                <td><a href="{{ URL('searchData?search=') }}${order.id}" target="_blank">${order.id}</a></td>
                                <td>${order.oname}</td>
                                <td>${order.created_at}</td>
                              </tr>`;
                                });
                                table += `</tbody></table>`;
                                return table;
                            }

                            html += generateTable("Origin ZSC Orders", res.origin);
                            html += generateTable("Destination ZSC Orders", res.destination);
                            html += generateTable("Combined Origin + Destination Orders", res.combined);

                            $('#show_cancellation_orders').html(html);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("There was an error with the request:", error);
                    }
                });
            }
        }

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
            if (ozip == '' || dzip == '') {
                alert('Please Enter Origin & Dest City or Zip');
                // toastr.error("Please Enter Origin & Dest City or Zip", "Error");
            } else {
                ozip = ozip.split(", ");
                dzip = dzip.split(", ");
                var url = `/rates_shipa1?ocity=${btoa(ozip[0])}&dcity=${btoa(dzip[0])}&id={{ \Request::segment(2) }}`;
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
                window.open(url, 'Previous Orders', 'height=600,width=1000,left=350,top=350,toolbar=No,location=No,scrollbars=Yes,status=No,resizable=Yes,fullscreen=No,directories=No,menubar=No,copyhistory=No');
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

        function condition_change(val,counter_id) {
            var c_id = counter_id.replace('condition','');
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

        @if(!empty($data->cod_cop))

        setTimeout(function () {
            $('#copcodAmount').trigger('change');
        }, 1000);

        @endif

        setTimeout(function () {
            document.body.style.zoom = "95%";
        }, 500);

        $(document).ready(function(){
//            $("input").autocomplete({
//                disabled: true
//            });
            $('input').attr('autocomplete', 'of');
//            $('#o_zip1').attr('autocomplete', 'on');
//            $('#daddress2').attr('autocomplete', 'on');

        });
        $("#viewCentral").click(function () {
            var ozip = $("#o_zip1").val();
            var dzip = $("#d_zip1").val();
            var vehType = $("#vehType0").val();
            var numVehicles = $("#count0").val();
            var veh = '';
            var condition = $("#condition0").val();
            var trailter = $("#trailter_type0").val();
            var type = $("#vehChkType0").val();
            var get_ses = $("#get_ses").val();


            if (ozip == '' || dzip == '' || vehType == '' || condition == '' || trailter == '') {
                document.getElementById("o_zip1").focus();
                document.getElementById("d_zip1").focus();

                $("#o_zip1").addClass("border_bottom_color");
                $("#d_zip1").addClass("border_bottom_color");
                $("#condition0").addClass("border_bottom_color");
                $("#trailter_type0").addClass("border_bottom_color");

            } else {
                ozip = ozip.split(",");
                dzip = dzip.split(",");
                if (vehType || type) {

                    if (vehType == "Car") {
                        veh = "Car";
                    }

                    else if (vehType == "motorcycle" || vehType == "3_wheel_sidecar" || vehType == "3_wheel_motorcycle") {
                        veh = "Motorcycle";
                    }

                    else if (vehType == "atv") {
                        veh = "ATV";
                    }

                    else if (vehType == "SUV" || vehType == "Mid SUV" || vehType == "Large SUV") {
                        veh = "SUV";
                    }

                    else if (vehType == "Van" || vehType == "Mini Van" || vehType == "Cargo Van" || vehType == "Passenger Van") {
                        veh = "Van";
                    }

                    else if (vehType == "Pickup" || vehType == "Pickup Dually" || vehType == "Box Truck Dually") {
                        veh = "Pickup";
                    }

                    else if (type == "other_vehicle" || type == "other_motorcycle") {
                        veh = "Other";
                    }else{
                        veh = 'Other';
                    }

                } else {
                    veh = '%2b';
                }
                // var url = `https://www.centraldispatch.com/protected/cargo/sample-prices-lightbox?num_vehicles=${numVehicles}&ozip=${ozip[2]}&dzip=${dzip[2]}&enclosed=${trailter}&inop=${condition}&vehicle_types=${veh}&miles=0&${get_ses}`;
                var url = `https://washington.shawntransport.com/get_web_price?num_vehicles=${numVehicles}&ozip=${ozip[2]}&dzip=${dzip[2]}&enclosed=${trailter}&inop=${condition}&vehicle_types=${veh}&miles=0&${get_ses}`;

                window.open(url, 'Central Dispatch Pricing', 'height=600,width=900,left=250,top=250,toolbar=No,location=No,scrollbars=Yes,status=No,resizable=Yes,fullscreen=No,directories=No,menubar=No,copyhistory=No');

            }

        });


        $("body").delegate(".ui-menu", "click", function () {
            $(".ui-menu").html('');
        });
        $(document).ready(function () {
//            $("input").autocomplete({
//                disabled: true
//            });
            $('input').attr('autocomplete', 'of');
//            $('#o_zip1').attr('autocomplete', 'on');
//            $('#daddress2').attr('autocomplete', 'on');

        });

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
                        data: {o_zip1: o_zip1, oterminal: oterminal},
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
                $('#port_lines').prop('style','display: none;');
                $("#port_line1").prop("checked", false);
                $("#port_line2").prop("checked", false);
                $('input[name="port_line"]').prop("required",false);
                $("#port_dock_type").prop("selectedIndex", 0).change();
                if (dterminal == 2){
                    dterminal = 1;
                    $.ajax({
                        type: "GET",
                        url: "/get_auction",
                        dataType: 'JSON',
                        data: {o_zip1: d_zip1, oterminal: dterminal},
                        success: function (res) {
                            if (res) {
                                $('#daddress').val(res[0].address);
                                $('#dacutionphoNo').val(res[0].phone);
                                $('#dacution_name').val('Copart Auction');
                                $('#d_zip1').focus();

                            }
                        }
                    });
                }else if(dterminal == 7){
                    $('#reason_box').val(null);
                    $('#port_lines').prop('style','display: block; width: 100%;"');
                    $('input[name="port_line"]').prop("required",true);
                }
            }, 500);
        });

        $(document).on('change', '#dterminal', function () {
            $("#dauctionnew").val($(this).children('option:selected').attr('data-value'));
            setTimeout(function () {
                var d_zip1 = $(`#d_zip1`).val();
                var dterminal = $(`#dterminal`).val();
                $('#port_lines').prop('style','display: none;');
                $("#port_line1").prop("checked", false);
                $("#port_line2").prop("checked", false);
                $("#port_dock_type").prop("selectedIndex", 0).change();
                if (dterminal == 2){
                    dterminal = 1;
                    $.ajax({
                        type: "GET",
                        url: "/get_auction",
                        dataType: 'JSON',
                        data: {o_zip1: d_zip1, oterminal: dterminal},
                        success: function (res) {
                            if (res) {
                                $('#daddress').val(res[0].address);
                                $('#dacutionphoNo').val(res[0].phone);
                                $('#dacution_name').val('Copart Auction');
                                $('#d_zip1').focus();

                            }
                        }
                    });
                }else if(dterminal == 7){
                    $('#reason_box').val(null);
                    $('#port_lines').prop('style','display: block; width: 100%;"');
                }
            }, 500);
        });

        $(document).on('change', '#port_dock_type', function () {
            setTimeout(function ($this) {
                var port_dock_type = $(`#port_dock_type`).val();
                if(port_dock_type == 'Non Running' || port_dock_type == 'Folk Lift'){
                    $('#reason_box').val(null);
                    $('#port_reason_box').prop('style','display: block; width: 100%;"');
                }else{
                    $('#port_reason_box').prop('style','display: none; width: 100%;"');
                    $('#reason_box').val(null);
                }
            }, 500);
        });


        $("#viewCentral").click(function () {
            var ozip = $("#o_zip1").val();
            var dzip = $("#d_zip1").val();
            var vehType = $("#vehType0").val();
            var numVehicles = $("#count0").val();
            var veh = '';
            var condition = $("#condition0").val();
            var trailter = $("#trailter_type0").val();
            var type = $("#vehChkType0").val();
            var get_ses = $("#get_ses").val();


            if (ozip == '' || dzip == '' || vehType == '' || condition == '' || trailter == '') {
                document.getElementById("o_zip1").focus();
                document.getElementById("d_zip1").focus();

                $("#o_zip1").addClass("border_bottom_color");
                $("#d_zip1").addClass("border_bottom_color");
                $("#condition0").addClass("border_bottom_color");
                $("#trailter_type0").addClass("border_bottom_color");

            } else {
                ozip = ozip.split(",");
                dzip = dzip.split(",");
                if (vehType || type) {

                    if (vehType == "Car") {
                        veh = "Car";
                    }

                    else if (vehType == "motorcycle" || vehType == "3_wheel_sidecar" || vehType == "3_wheel_motorcycle") {
                        veh = "Motorcycle";
                    }

                    else if (vehType == "atv") {
                        veh = "ATV";
                    }

                    else if (vehType == "SUV" || vehType == "Mid SUV" || vehType == "Large SUV") {
                        veh = "SUV";
                    }

                    else if (vehType == "Van" || vehType == "Mini Van" || vehType == "Cargo Van" || vehType == "Passenger Van") {
                        veh = "Van";
                    }

                    else if (vehType == "Pickup" || vehType == "Pickup Dually" || vehType == "Box Truck Dually") {
                        veh = "Pickup";
                    }

                    else if (type == "other_vehicle" || type == "other_motorcycle") {
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

        $("#driverPrice").on('keyup',function(){
            var price = $(this);
            $.ajax({
                url:"{{ url('/offer-price/get_commission') }}",
                type:"GET",
                data:{price:price.val()},
                dataType:"JSON",
                success:function(res)
                {
                    price.siblings('.alert').remove();
                    if(res.data.commission_price)
                    {
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
            var approval_reason = document.forms["valid_form"]["approval_reason"];

            var year = $('[id^=year]');
            var make = $('[id^=make]');
            var model = $('[id^=model]');
            var valValidate = 0;
            year.each(function(index,item){
                if(!$(year[index]).val()){
                    $(year[index]).css("border-color", "#ff0b00");
                    valValidate = valValidate + 1;
                }
                if(!$(make[index]).val()){
                    $(make[index]).css("border-color", "#ff0b00");
                    valValidate = valValidate + 1;

                }
                if(!$(model[index]).val()){
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
            if(orderPrice)
            {
                if (!orderPrice.value) {
                    $("#orderPrice").css("border-color", "#ff0b00");
                    valValidate = valValidate + 1;
                }
            }
            if(driverPrice)
            {
                if (!driverPrice.value) {
                    $("#driverPrice").css("border-color", "#ff0b00");
                    valValidate = valValidate + 1;
                }
            }
            if(startPrice)
            {
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
            if($("#pstatus2").val() >= 7)
            {
                // $("#carrier_status_2").parent('label').siblings('.text-danger').remove();
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
                if(approval_reason)
                {
                    if (!approval_reason.value) {
                        $("#pay_later_op_reason").css("border-color", "#ff0b00");
                        valValidate = valValidate + 1;
                    }
                }
            }
            if(valValidate > 0)
            {
                $(window).scrollTop(0);
                return true;
            }



            if(valValidate===0){
                @if($data->pstatus <=6)
                $('#btn_pstatus').modal('show');
                @endif
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
        };

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
                //                 <input class="this_save" checked name="pstatus" id="pay_later1" type="radio" value="7" onclick="$('#pstatus2').val(this.value);" >
                //                 <span>Payment Missing</span>
                //             </label>
                //         </div>
                //     </div>
                //     <div class="col-lg-2 mt-4">
                //         <div class="form-group">
                //             <label class="rdiobox">
                //                 <input class="this_save" name="pstatus" id="pay_later2" type="radio" value="18" onclick="$('#pstatus2').val(this.value);" >
                //                 <span>On Approval</span>
                //             </label>
                //         </div>
                //     </div>`);
                $("#pay_late").html(`
                    <div class="col-lg-4 mt-4">
                        <div class="form-group">
                            <label class="rdiobox form-label">Select the Status</label>
                            <select name="pstatus" class="form-control" id="pay_later1" onchange="$('#pstatus2').val(this.value);">
                                <option selected disabled value="0">Select the Status</option>
                                <option value="7">Payment Missing</option>
                                <option value="18">On Approval</option>
                            </select>
                        </div>
                    </div>
                `);
                $("#continuetopay_btn").val(0);
                $("#continuetopayold_btn").val(0);
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
            //     $("#continuetopayold_btn").val(0);
            //     $("#submitData").show();
            //     $("#emailRequired").html('');
            //     $("#clickToSubmit").html('Submit');
            //     var email = $("#oemail").val();
            //     $("#oemail2").val(email);

            //     $("#oemail2").change(function () {
            //         $("#oemail").val($(this).val());
            //     });
            // });


            // $("body").delegate("#pay_later2", "click", function () {
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

            //     $("#oemail2").change(function () {
            //         $("#oemail").val($(this).val());
            //     });
            // });

            $("body").delegate("#pay_later1", "change", function () {
                if($("#pay_later1").val() == "18")
                {
                    $("#pay_late_reason").html(`<div class="col-lg-8 mt-4">
                            <div class="form-group">
                                <label class="rdiobox form-label">
                                    <span>Reason</span>
                                    <input class="this_save form-control" name="approval_reason" required onkeyup="$('#order_history').val(this.value)" id="pay_later_op_reason" type="text" value="">
                                </label>
                            </div>
                        </div>`);
                    $("#continuetopay_btn").val(0);
                    $("#continuetopayold_btn").val(0);
                    $("#submitData").show();
                    $("#emailRequired").html('');
                    var email = $("#oemail").val();
                    $("#oemail2").val(email);

                    $("#oemail2").change(function () {
                        $("#oemail").val($(this).val());
                    });
                }
                if($("#pay_later1").val() == "7")
                {
                    $("#pay_late_reason").html(`<div class="col-lg-8 mt-4">
                            <div class="form-group">
                                <label class="rdiobox form-label">
                                    <span>Reason</span>
                                    <input class="this_save form-control" name="approval_reason" required onkeyup="$('#order_history').val(this.value)" id="pay_later_op_reason" type="text" value="">
                                </label>
                            </div>
                        </div>`);
                    $("#saveBtn").html('Save');
                    // $("#pay_late_reason").html('');
                    $("#continuetopay_btn").val(0);
                    $("#continuetopayold_btn").val(0);
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


            // $("#clickToSubmit").click(function () {
            //     $("#neworderpay_btn").val(1);
            //     $("#form").submit();

            // });


            $("#clickToSubmit").click(function () {
                if(validate() === true)
                {

                }
                else
                {
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
						<button type="button" id="clickToSubmit" name="neworderpay1" value="neworderpay1" class="btn btn-primary"></button>
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


            // $("#clickToSubmit").click(function () {
            //     $("#neworderpay_btn").val(1);
            //     $("#form").submit();
            // });


            $("#clickToSubmit").click(function () {
                if(validate() === true)
                {

                }
                else
                {
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
            //     $("#continuetopayold_btn").val(0);
            //     $("#submitData").show();
            //     $("#emailRequired").html('');
            //     $("#clickToSubmit").html('Submit');
            //     var email = $("#oemail").val();
            //     $("#oemail2").val(email);

            //     $("#oemail2").change(function () {
            //         $("#oemail").val($(this).val());
            //     });
            // });


            // $("body").delegate("#pay_later2", "click", function () {
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

            //     $("#oemail2").change(function () {
            //         $("#oemail").val($(this).val());
            //     });
            // });


            $("body").delegate("#pay_later1", "change", function () {
                if($("#pay_later1").val() == "18")
                {
                    $("#pay_late_reason").html(`<div class="col-lg-8 mt-4">
                        <div class="form-group">
                            <label class="rdiobox form-label">
                                <span>Reason</span>
                                <input class="this_save form-control" name="approval_reason" required onkeyup="$('#order_history').val(this.value)" id="pay_later_op_reason" type="text" value="">
                            </label>
                        </div>
                    </div>`);
                    $("#continuetopay_btn").val(0);
                    $("#continuetopayold_btn").val(0);
                    $("#submitData").show();
                    $("#emailRequired").html('');
                    var email = $("#oemail").val();
                    $("#oemail2").val(email);

                    $("#oemail2").change(function () {
                        $("#oemail").val($(this).val());
                    });
                }
                if($("#pay_later1").val() == "7")
                {
                    $("#pay_late_reason").html(`<div class="col-lg-8 mt-4">
                        <div class="form-group">
                            <label class="rdiobox form-label">
                                <span>Reason</span>
                                <input class="this_save form-control" name="approval_reason" required onkeyup="$('#order_history').val(this.value)" id="pay_later_op_reason" type="text" value="">
                            </label>
                        </div>
                    </div>`);
                    $("#saveBtn").html('Save');
                    // $("#pay_late_reason").html('');
                    $("#continuetopay_btn").val(0);
                    $("#continuetopayold_btn").val(0);
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
                //                 <input class="this_save" name="pstatus" id="pay_later1" type="radio" value="7" onclick="$('#pstatus2').val(this.value);" >
                //                 <span>Payment Missing</span>
                //             </label>
                //         </div>
                //     </div>
                //     <div class="col-lg-2 mt-4">
                //         <div class="form-group">
                //             <label class="rdiobox">
                //                 <input class="this_save" name="pstatus" id="pay_later2" type="radio" value="18" onclick="$('#pstatus2').val(this.value);" >
                //                 <span>On Approval</span>
                //             </label>
                //         </div>
                //     </div>`);
                $("#pay_late").html(`
                    <div class="col-lg-4 mt-4">
                        <div class="form-group">
                            <label class="rdiobox form-label">Select the Status</label>
                            <select name="pstatus" class="form-control" id="pay_later1" onchange="$('#pstatus2').val(this.value);">
                                <option selected disabled value="0">Select the Status</option>
                                <option value="7">Payment Missing</option>
                                <option value="18">On Approval</option>
                            </select>
                        </div>
                    </div>
                `);
                $("#continuetopay_btn").val(0);
                $("#continuetopayold_btn").val(0);
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
                    <input class="form-control this_save " autocomplete="nope" type="text" required
                           name="depositAmount" value="" placeholder=""
                           id="depositAmount" style="width: 90%">


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
                $("#whenPickUpDate").html(`
            <div class="row">
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
                $("#whenDeliveryDate").html(`
                            <div class="row">
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
                                <label id="dauction" class="label font-boldd tx-black"></label>
                                <input  class="form-control this_save " autocomplete="off" type="text" name="dauction" id="dacution_name" value="">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label id="dauctionpho" class="label font-boldd tx-black"></label>
                                <input  class="form-control this_save  ophone" autocomplete="off" type="text" name="dauctionpho" id="dacutionphoNo" value="">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="label font-boldd tx-black">Terminal</label>
                                <input class="form-control this_save " autocomplete="off" type="text" name="port_terminal" id="port_terminal" value="">
                            </div>
                        </div>
                    `);
                }
                else if(id == 2 || id == 3 || id == 4 || id == 10)
                {
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
                                <label id="dacutiondate" class="label parsley-error font-boldd tx-black">Auction Date <span class="text-muted">(Optional)</span></label>
                                <input class="form-control this_save" autocomplete="off" type="date"  name="dacutiondate" id="dacutiondate" value="">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label id="dacutiontime" class="label parsley-error font-boldd tx-black">Auction Time <span class="text-muted">(Optional)</span></label>
                                <input class="form-control this_save" autocomplete="off" type="time"  name="dacutiontime" id="dacutiontime" value="">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="dacutionaccounttitle" class="label parsley-error font-boldd tx-black">Has Auction Account?</label>
                                <select class="form-control this_save" autocomplete="off" name="dacutionaccounttitle" id="dacutionaccounttitle">
                                    <option value="" selected disabled>Select</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12" style="display:none;" id="daucAccName">
                            <div class="form-group">
                                <label for="dacutionaccountname" class="label parsley-error font-boldd tx-black">Auction Account Name </label>
                                <input class="form-control this_save" autocomplete="off" type="text"  name="dacutionaccountname" id="dacutionaccountname" value="">
                            </div>
                        </div>
                    `);
                }
                else {
                    $(".dauc").html(`
                         <div class="col-lg-6">
                            <div class="form-group">
                                <label id="dauction" class="label font-boldd tx-black"></label>
                                <input required class="form-control this_save " autocomplete="off" type="text" name="dauction" id="dacution_name" value="" >
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label id="dauctionpho" class="label font-boldd tx-black"></label>
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
            if (dterminal == 2 || dterminal == 3 || dterminal == 4 || dterminal == 10) {
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
            $(".ophone").keypress(function(e){
                if($(this).val() == '')
                {
                    $(this).mask("(999) 999-9999");
                }
                var x = e.which || e.keycode;
                if ((x >= 48 && x <= 57) || (x >= 35 && x <= 40)){
                    return true;
                }else{
                    return false;
                }
            })

        });

        $(document).on('change','#dacutionaccounttitle',function(){
            $("#dacutionaccountname").val('');
            if($(this).val() == 'Yes')
            {
                $("#daucAccName").show();
            }
            else
            {
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
            else if(id == 3 || id == 2 || id == 4 || id == 8)
            {
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
                            <label id="oacutiondate" class="label parsley-error font-boldd tx-black">Auction Date <span class="text-muted">(Optional)</span></label>
                            <input class="form-control this_save" autocomplete="off" type="date"  name="oacutiondate" id="oacutiondate" value="">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label id="oacutiontime" class="label parsley-error font-boldd tx-black">Auction Time <span class="text-muted">(Optional)</span></label>
                            <input class="form-control this_save" autocomplete="off" type="time"  name="oacutiontime" id="oacutiontime" value="">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="oacutionaccounttitle" class="label parsley-error font-boldd tx-black">Has Auction Account?</label>
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
                            <label class="form-label">Buyer/Lot/Stock Number</label>
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
                            <label id="oacutionpho" class="label font-boldd tx-black"></label>
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
            $(".ophone").keypress(function(e){
                if($(this).val() == '')
                {
                    $(this).mask("(999) 999-9999");
                }
                var x = e.which || e.keycode;
                if ((x >= 48 && x <= 57) || (x >= 35 && x <= 40)){
                    return true;
                }else{
                    return false;
                }
            })


        });

        $(document).on('change','#oacutionaccounttitle',function(){
            $("#oacutionaccountname").val('');
            if($(this).val() == 'Yes')
            {
                $("#aucAccName").show();
            }
            else
            {
                $("#aucAccName").hide();
            }
        })



        function save_phon() {

            var datastring = $("#createnew_form").serialize();
            var mainPhNum = $('#PhNum').val();


            if (mainPhNum.length > 0) {

                $.ajax({

                    type: "post",
                    url: "/createneworder",
                    data: datastring,
                    dataType: "json",

                    success: function (data) {

                        $('#ophone').val(data["ophone"]);
                        $('#orderid_find').val(data["id"]);
                        $('#modaldemo8').modal('hide');
                        $('#ophone').focus();
                        $('#orderidplace').html('ORDER ON PHONE #' + data["id"]);


                    },
                    error: function (e) {

                    }

                });


            } else {

                alert('PLEASE ENTER PHONE NUMBER');
            }


        }

        function phone_check(phone_no) {


            phone_no = phone_no;
            phonelenght = phone_no.replace(/[^0-9]/g, "").length;

            if (phonelenght == 10) {


                $("#create_new").show();


                $.ajax({

                    type: "GET",
                    url: "/get_order",
                    data: {'phone_no': phone_no},
                    dataType: "json",

                    success: function (data) {
                        $.each(data, function (i, item) {

                            if (item.tot > 0) {
                                $("#update_previous").show();
                            } else {
                                $("#update_previous").hide();
                            }
                            $('#show_total').html(item.tot + ' Order(s) found');

                        });

                    },
                    error: function (e) {

                    }

                });

            } else {
                $("#create_new").hide();
                $("#update_previous").hide();
                $("#show_total").html('');

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
        function freight_calc() {
            var length = parseFloat($('#f_length').val());
            var width =  parseFloat($('#f_width').val());
            var height = parseFloat($('#f_height').val());
            var weight = parseFloat($('#f_weight').val());
            var unit =  $('#f_unit').val();
            var answer_1 = (length * height * width).toFixed(4);
            if(unit == 'inch'){
                answer_1 = (answer_1/1728).toFixed(4);
            }
            var fright_class = 0;
            answer_1 = (weight/answer_1).toFixed(4);
            if(answer_1 < 1){
                fright_class = 500;
            }else if(answer_1 >= 1 &&  answer_1 < 2){
                fright_class = 400;
            }else if(answer_1 >= 2 &&  answer_1 < 3){
                fright_class = 300;
            }else if(answer_1 >= 3 &&  answer_1 < 4){
                fright_class = 250;
            }else if(answer_1 >= 4 &&  answer_1 < 5){
                fright_class = 200;
            }else if(answer_1 >= 5 &&  answer_1 < 6){
                fright_class = 175;
            }else if(answer_1 >= 6 &&  answer_1 < 7){
                fright_class = 150;
            }else if(answer_1 >= 7 &&  answer_1 < 8){
                fright_class = 125;
            }else if(answer_1 >= 8 &&  answer_1 < 9){
                fright_class = 110;
            }else if(answer_1 >= 9 &&  answer_1 < 10.5){
                fright_class = 100;
            }else if(answer_1 >= 10.5 &&  answer_1 < 12){
                fright_class = 92.5;
            }else if(answer_1 >= 12 &&  answer_1 < 13.5){
                fright_class = 85;
            }else if(answer_1 >= 13.5 &&  answer_1 < 15){
                fright_class = 77.5;
            }else if(answer_1 >= 15 &&  answer_1 < 22.5){
                fright_class = 70;
            }else if(answer_1 >= 22.5 &&  answer_1 < 30){
                fright_class = 65;
            }else if(answer_1 >= 30 &&  answer_1 < 35){
                fright_class = 60;
            }else if(answer_1 >= 35 &&  answer_1 < 50){
                fright_class = 55;
            }else if(answer_1 >= 50){
                fright_class = 50;
            }
            console.log('answerans', fright_class);
            $('#frieght_class').val(fright_class).trigger('change');
            $('#modal_frieght').modal('hide');

        }
    </script>


    <script>
        //$('#modaldemo8').modal('show');
        $(document).on('click', '.btn_remove', function () {
            $(this).parents(`.vehicle_add`).remove();
            vehicle_count--;
            $('.this_save').trigger('change');
        });

        $(document).on('click', '#yes', function () {
            $(".number_no").hide();
            $(".number_yes").show();

        });
        $(document).on('click', '#no', function () {
            $(".number_yes").hide();
            $(".number_no").show();
        });


        $('#PhNum').keypress(function (e) {
            var x = e.which || e.keycode;
            if ((x >= 48 && x <= 57) || x == 8 ||
                (x >= 35 && x <= 40) || x == 46)
                return true;
            else
                return false;
        });
    </script>

    <script>
        $(function () {

            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });

            const ORDER_ID = "{{ $data->id }}";

            function showError(html) {
                $('#priceModalNewError').html('<div class="alert alert-danger">'+html+'</div>');
            }
            function clearError() { $('#priceModalNewError').html(''); }

            function money(n) {
                if (n === null || n === undefined || n === '') return '-';
                const x = Number(n);
                if (Number.isNaN(x)) return n;
                return '$' + x.toFixed(2);
            }
            function num(n) {
                const x = Number(n);
                return Number.isNaN(x) ? null : x;
            }
            function esc(s) {
                return String(s ?? '').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));
            }
            function pickFirstMode(modes) {
                if (!modes) return null;
                if (modes.open) return modes.open;
                const keys = Object.keys(modes);
                return keys.length ? modes[keys[0]] : null;
            }

            // Simple horizontal "meter" bar with low/mid/high markers
            function renderMeter(low, mid, high) {
                low = num(low); mid = num(mid); high = num(high);
                if (low === null || mid === null || high === null || high <= low) {
                    return `
        <div class="mb-2">
          <div class="d-flex justify-content-between">
            <small><b>Low</b> ${money(low)}</small>
            <small><b>Mid</b> ${money(mid)}</small>
            <small><b>High</b> ${money(high)}</small>
          </div>
          <div class="text-muted small">No meter available</div>
        </div>`;
                }

                const pct = v => Math.max(0, Math.min(100, ((v - low) / (high - low)) * 100));
                const midPct = pct(mid);

                return `
      <div class="mb-2">
        <div class="d-flex justify-content-between">
          <small><b>Low</b> ${money(low)}</small>
          <small><b>Mid</b> ${money(mid)}</small>
          <small><b>High</b> ${money(high)}</small>
        </div>
        <div style="position:relative;height:10px;background:#eee;border-radius:8px;overflow:hidden;">
          <div style="position:absolute;left:0;top:0;bottom:0;width:100%;background:#e9ecef;"></div>
          <div style="position:absolute;left:0;top:0;bottom:0;width:${midPct}%;background:#cfe2ff;"></div>

          <div title="Low" style="position:absolute;left:calc(0% - 4px);top:-2px;width:8px;height:14px;background:#0d6efd;border-radius:3px;"></div>
          <div title="Mid" style="position:absolute;left:calc(${midPct}% - 4px);top:-2px;width:8px;height:14px;background:#198754;border-radius:3px;"></div>
          <div title="High" style="position:absolute;left:calc(100% - 4px);top:-2px;width:8px;height:14px;background:#dc3545;border-radius:3px;"></div>
        </div>
      </div>
    `;
            }

            function renderMarketDetails(modeObj, vehicleObj) {
                const m = (modeObj && modeObj.market) ? modeObj.market : {};
                const pickup  = `${esc(m.originCity || '-')}, ${esc(m.originState || '-')}${m.originZipCode ? ' ' + esc(m.originZipCode) : ''}`;
                const delivery = `${esc(m.destinationCity || '-')}, ${esc(m.destinationState || '-')}${m.destinationZipCode ? ' ' + esc(m.destinationZipCode) : ''}`;
                const route = `${esc(m.originCity || '')} → ${esc(m.destinationCity || '')}`;
                const listingDist = (m.listingDistance != null) ? `${esc(m.listingDistance)} ${esc(m.listingDistanceUnits || '')}` : '-';
                const dispatchDist = (m.dispatchDistance != null) ? `${esc(m.dispatchDistance)} ${esc(m.dispatchDistanceUnits || '')}` : '-';
                const status = esc(m.dispatchStatus || '-');

                // cargo: client wants "Cargo" like CAR/SUV. Central MI returns vehicleType.
                const cargo = esc(m.vehicleType || vehicleObj?.type || '-');

                // "Vehicle" text from order selection
                const vehicleTxt = vehicleObj
                    ? `${esc(vehicleObj.year || '')} ${esc(vehicleObj.make || '')} ${esc(vehicleObj.model || '')}`.trim()
                    : '-';

                return `
      <div class="row">
        <div class="col-md-4 mb-2"><b>Distance (Listing):</b><div>${listingDist}</div></div>
        <div class="col-md-4 mb-2"><b>Distance (Dispatch):</b><div>${dispatchDist}</div></div>
        <div class="col-md-4 mb-2"><b>Status:</b><div>${status}</div></div>

        <div class="col-md-4 mb-2"><b>Pickup:</b><div>${pickup}</div></div>
        <div class="col-md-4 mb-2"><b>Delivery:</b><div>${delivery}</div></div>
        <div class="col-md-4 mb-2"><b>Route:</b><div>${route}</div></div>

        <div class="col-md-4 mb-2"><b>Vehicle:</b><div>${vehicleTxt}</div></div>
        <div class="col-md-4 mb-2"><b>Cargo:</b><div>${cargo}</div></div>
        <div class="col-md-4 mb-2"><b>Listed Price:</b><div>${money(m.listingPrice)} ${esc(m.listingPriceCurrency || '')}</div></div>

        <div class="col-md-4 mb-2"><b>Dispatch Price:</b><div>${money(m.dispatchPrice)} ${esc(m.dispatchPriceCurrency || '')}</div></div>
        <div class="col-md-8 mb-2"><b>Trailer type:</b><div>${esc(modeObj?.transport_mode || '-').toUpperCase()}</div></div>
      </div>
    `;
            }

            function renderModeCard(title, modeObj) {
                const cache = !!(modeObj && modeObj.cache_hit);
                const dp = (modeObj && modeObj.driver_price) ? modeObj.driver_price : {};
                const offer = (modeObj && modeObj.offer_price) ? modeObj.offer_price : {};
                const market = (modeObj && modeObj.market) ? modeObj.market : {};

                return `
      <div class="col-md-6">
        <div class="card mb-3">
          <div class="card-header d-flex justify-content-between align-items-center">
            <strong>${esc(title)}</strong>
            ${cache ? '<span class="badge badge-success">Cache</span>' : ''}
          </div>
          <div class="card-body">
            ${renderMeter(dp.low, dp.mid, dp.high)}

            <div class="d-flex justify-content-between">
              <div><b>Offer:</b> ${money(offer.value)}</div>
              <div><b>Commission:</b> ${money(offer.commission)}</div>
            </div>

            <hr class="my-2">

            <div class="row">
              <div class="col-6"><small><b>Listed:</b> ${money(market.listingPrice)}</small></div>
              <div class="col-6"><small><b>Dispatch:</b> ${money(market.dispatchPrice)}</small></div>
              <div class="col-6"><small><b>Status:</b> ${esc(market.dispatchStatus || '-')}</small></div>
              <div class="col-6"><small><b>Distance:</b> ${(market.listingDistance != null) ? esc(market.listingDistance)+' '+esc(market.listingDistanceUnits||'') : '-'}</small></div>
            </div>
          </div>
        </div>
      </div>
    `;
            }

            function normalizeGatewayResponse(res) {
                // supports either direct gateway response OR { raw: gw } OR { body: gw } etc
                return res?.raw || res?.body || res;
            }

            // -------------------------------------------------------
            // BUTTON: Check Price (New)
            // -------------------------------------------------------
            $('#checkPriceNew').on('click', function () {
                clearError();
                $('#checkPriceNew').prop('disabled', true);

                $('#marketDetailsBox').html('');
                $('#vehicleCardsWrap').html('');
                $('#vehiclesBreakdownBox').hide();
                $('#vehiclesBreakdownTbody').html('');
                $('#rawPayloadBox').hide();
                $('#rawPayloadPre').text('');
                $('#cacheHitBadge').hide();

                $.ajax({
                    url: `/orders/${ORDER_ID}/pricing/check`,
                    method: 'POST',
                    dataType: 'json',
                    success: function (res) {
                        const gw = normalizeGatewayResponse(res);

                        const items = Array.isArray(gw?.items) ? gw.items : [];
                        const primary = gw?.primary || (items[0] || null);

                        if (!primary) {
                            showError('No vehicle pricing returned.');
                            $('#checkPriceNew').prop('disabled', false);
                            $('#priceModalNew').modal('show');
                            return;
                        }

                        // Pick primary mode (prefer primary_mode, then open, then first available)
                        const pmKey = (gw?.primary?.primary_mode) || (gw?.primary_mode) || (primary?.primary_mode) || null;
                        const primaryModes = primary?.modes || {};
                        const primaryModeObj = (pmKey && primaryModes[pmKey]) ? primaryModes[pmKey] : pickFirstMode(primaryModes);

                        const cacheHitAny = !!gw?.cache_hit || !!primaryModeObj?.cache_hit;
                        if (cacheHitAny) $('#cacheHitBadge').show();

                        // Market details
                        $('#marketDetailsBox').html(renderMarketDetails(primaryModeObj, primary.vehicle));

                        // Per vehicle cards
                        let cardsHtml = '';
                        items.forEach(v => {
                            const vKey = v.vehicle_key || '';
                            const veh = v.vehicle || {};
                            const vehName = `${esc(veh.year || '')} ${esc(veh.make || '')} ${esc(veh.model || '')} (${esc(veh.type || '')})`.trim();

                            const modes = v.modes || {};
                            cardsHtml += `
            <div class="card mb-3">
              <div class="card-header">
                <strong>${vehName || esc(vKey)}</strong>
                <div class="text-muted" style="font-size:12px;">${esc(vKey)}</div>
              </div>
              <div class="card-body">
                <div class="row">
                  ${renderModeCard('Open', modes.open)}
                  ${renderModeCard('Enclosed', modes.enclosed)}
                </div>
              </div>
            </div>
          `;
                        });

                        $('#vehicleCardsWrap').html(cardsHtml || '<div class="alert alert-warning">No items returned.</div>');

                        // Breakdown table
                        const rows = [];
                        items.forEach(v => {
                            const vKey = v.vehicle_key || '';
                            const modes = v.modes || {};
                            ['open','enclosed'].forEach(k => {
                                const m = modes[k];
                                if (!m) return;

                                const dp = m.driver_price || {};
                                const offer = m.offer_price || {};
                                const mk = m.market || {};

                                rows.push(`
              <tr>
                <td>${esc(vKey)}</td>
                <td><b>${esc((m.transport_mode || k).toUpperCase())}</b></td>
                <td>${money(dp.low)}</td>
                <td>${money(dp.mid)}</td>
                <td>${money(dp.high)}</td>
                <td>${money(offer.value)}</td>
                <td>${money(offer.commission)}</td>
                <td>${money(mk.listingPrice)}</td>
                <td>${money(mk.dispatchPrice)}</td>
                <td>${(mk.listingDistance != null) ? esc(mk.listingDistance)+' '+esc(mk.listingDistanceUnits||'') : '-'}</td>
                <td>${esc(mk.dispatchStatus || '-')}</td>
                <td>${m.cache_hit ? 'YES' : 'NO'}</td>
              </tr>
            `);
                            });
                        });

                        if (rows.length) {
                            $('#vehiclesBreakdownTbody').html(rows.join(''));
                            $('#vehiclesBreakdownBox').show();
                        }

                        // Raw payload (keep hidden by default)
                        $('#rawPayloadPre').text(JSON.stringify(gw, null, 2));
                        // $('#rawPayloadBox').show(); // enable if you want to see it

                        $('#checkPriceNew').prop('disabled', false);
                        $('#priceModalNew').modal('show');
                    },

                    error: function (xhr) {
                        let msg = 'An error occurred while requesting price.';
                        try {
                            const j = JSON.parse(xhr.responseText);
                            if (j.message) msg = j.message;
                            if (j.errors) {
                                const parts = [];
                                Object.keys(j.errors).forEach(k => parts.push((j.errors[k] || []).join('<br>')));
                                if (parts.length) msg = parts.join('<br>');
                            }
                        } catch (e) {}
                        showError(msg);
                        $('#checkPriceNew').prop('disabled', false);
                        $('#priceModalNew').modal('show');
                    }
                });
            });

            $('#previousCheckPriceNew').on('click', function () {

                $.ajax({
                    url: `/orders/${ORDER_ID}/pricing/history`,
                    method: 'GET',
                    dataType: 'json',
                    success: function (res) {
                        const rows = res.items || [];
                        if (!rows.length) {
                            $('#priceHistoryTbody').html('<tr><td colspan="7">No previous pricing found.</td></tr>');
                            $('#priceHistoryModalNew').modal('show');
                            return;
                        }

                        const html = rows.map(r => `
          <tr>
            <td>${r.at || ''}</td>
            <td>${(r.origin_zip || '')} ${(r.origin_state || '')}</td>
            <td>${(r.destination_zip || '')} ${(r.destination_state || '')}</td>
            <td>${r.vehicle_key || ''}</td>
            <td>${money(r.driver_mid)}</td>
            <td>${money(r.offer_price)}</td>
            <td>${r.cache_hit ? 'YES' : 'NO'}</td>
          </tr>
        `).join('');

                        $('#priceHistoryTbody').html(html);
                        $('#priceHistoryModalNew').modal('show');
                    },
                    error: function () {
                        $('#priceHistoryTbody').html('<tr><td colspan="7">Error loading history.</td></tr>');
                        $('#priceHistoryModalNew').modal('show');
                    }
                });

            });

        });
    </script>

    <script type="text/javascript">

        var Ophone_count = 0;
        var Dphone_count = 0;
        var vehicle_count = '{{$countt +1}}';


        $(".add_phone_btn").click(function () {
            $(".add_phone").append('<div class="col-12 add margin_lft"><div class="row"><div class="col-11"><label class="form-label font-boldd">Phone Number: </label><input  type="text" name="ophone[]"  class="form-control this_save ophone ophone_new" id="ophonee' + Ophone_count + '" placeholder="Phone Number"/></div><div class"form-group col-1" style="padding-top: 23px;"> <i id="remove_btn" class="fa fa-minus-circle remove_btn"></i></div><input type="hidden" value="" name="ophone2[]" /> </div></div>	&nbsp;');
            ++Ophone_count;
            $(".ophone").keypress(function(e){
                if($(this).val() == '')
                {
                    $(this).mask("(999) 999-9999");
                }
                var x = e.which || e.keycode;
                if ((x >= 48 && x <= 57) || (x >= 35 && x <= 40)){
                    return true;
                }else{
                    return false;
                }
            })

            $(".ophone_new").keyup(function(){
                $(this).parent('div').siblings('input').val($(this).val());
            })

        });

        $(".ophone_new").keyup(function(){
            $(this).parent('div').siblings('input').val($(this).val());
        })

        $(document).on('click', '.remove_btn', function () {
            $(this).parents('.add').remove();
            --Ophone_count;
        });

        $(".add_dphone_btn").click(function () {
            $(".add_dphone").append('<div class="col-12 add margin_lft"><div class="row"><div class="col-11"><label class="form-label font-boldd">Phone Number:</label><input  type="text" name="dphone[]"  class="form-control this_save dphone ophone_new" id="phonee' + Dphone_count + '" placeholder="Phone Number"/></div><div class"form-group col-1" style="padding-top: 23px;"> <i id="remove_btn" class="fa fa-minus-circle remove_btn"></i></div><input type="hidden" value="" name="dphone2[]" />  </div></div>	&nbsp;');
            ++Dphone_count;
            $(".dphone").keypress(function(e){
                var x = e.which || e.keycode;
                if ((x >= 48 && x <= 57) || (x >= 35 && x <= 40)){
                    return true;
                }else{
                    return false;
                }
            })

            $(".ophone_new").keyup(function(){
                $(this).parent('div').siblings('input').val($(this).val());
            })
        });

        $(document).on('click', '.remove_btn', function () {
            $(this).parents('.add').remove();
            --Dphone_count;
        });


        $(`.add_vehicle_btn`).click(function () {
            $(`.add_vehicle_information`).append(`
            <input type="hidden" name="count[]" value="1">
<div class="vehicle_add">
    <div class=" flex_ gap_new flex_space vichle__Information">
        <div class="vichle__Information--box">
            <div class="">
                <label class="rdiobox"> <input class="this_save type" name="vehicle${vehicle_count}"
                                                                   id="vehicle${vehicle_count}"
                                                                   onclick="vehicle_append(${vehicle_count})"
                                                                   type="radio" checked="" value="1"
                                                                   data-parsley-multiple="vehicle${vehicle_count}">
                    <input name="vehicle_v[]" id="vehicle_v${vehicle_count}" type="hidden" value="make"> <span>Year, Make, and Model</span>
                </label></div>
        </div>
        <div class="vichle__Information--box">
            <div class=""><label class="rdiobox"> <input class="this_save type" name="vehicle${vehicle_count}"
                                                                   id="vin${vehicle_count}"
                                                                   onclick="vin_append(${vehicle_count})" type="radio"
                                                                   value="2"
                                                                   data-parsley-multiple="vehicle${vehicle_count}"><input
                        name="vehicle_v[]" disabled id="vehicle_v_vin${vehicle_count}" type="hidden" value="vin"> <span>Vin Number</span>
                </label></div>
        </div>
        <div class="vichle__Information--box btn-list">
            <button style="" type="button" class="btn btn-danger btn_remove"><i class="mr-2"
                                                                                                 id="add-more"
                                                                                                 onclick="vehicleUpdate(1)"></i>Remove
            </button>
        </div>
    </div>
    <input type="hidden" value="" class="vehicleName">
    <div class="row">
        <div class="col-sm-12 col-md-12 vin_toggle${vehicle_count}"></div>
        <div class="col-sm-6 col-md-6">
            <div class="form-group"><label class="form-label font-boldd">Year<span class="redcolor">*</span></label> <input type="text"
                                                                                   class="form-control this_save vyear"
                                                                                   id="year${vehicle_count}"
                                                                                   name="vyear[]"onkeypress="$(this).css('border-color', 'rgb(92 166 242)');"
                                                                                   placeholder="Enter Year" required>
            </div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="form-group"><label class="form-label font-boldd">Make<span class="redcolor">*</span></label> <input type="text"
                                                                                   class="form-control this_save  makeOpt0 vmake"
                                                                                   id="makeOpt${vehicle_count}"
                                                                                   onkeyup="getmake()" name="vmake[]"onkeypress="$(this).css('border-color', 'rgb(92 166 242)');"
                                                                                   placeholder="Enter Make" required>
            </div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="googleimage" onclick="googl(${vehicle_count})" id="googleimage${vehicle_count}"
                 style="position: absolute; right: 3%;top:-6px;display:none"><a href="javascript:void(0);"><img width="50"
                                                                                                       src="{{url('')}}/assets/images/png/google.png" style="border: 1px solid #5da6f2;border-radius: 5px;"></a>
            </div>
            <div class="form-group"><label class="form-label font-boldd">Model<span class="redcolor">*</span></label> <input type="text"
                                                                                    id="model${vehicle_count}"
                                                                                    onkeyup="getmodel(${vehicle_count})"
                                                                                    name="vmodel[]"
                                                                                    class="form-control this_save  model0 vmodel"onkeypress="$(this).css('border-color', 'rgb(92 166 242)');"
                                                                                    placeholder="Enter Model" required>
            </div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="form-group"><label class="form-label font-boldd">Equipment Type </label>
                <select id="vehType${vehicle_count}" name="vehType[]"  class="form-control this_save vehicle-type">
                    <option selected="" value="">Select Type</option>
                    <option value="Step_Deck">Step Deck</option>
                    <option value="RGN">RGN</option>
                    <option value="Flatbed">Flatbed</option>
                    <option value="Landoll">Landoll</option>
                    <option value="Hotshot">Hotshot</option>
                </select></div>
        </div>
        <div class="col-sm-3 col-md-3">
            <div class="form-group"><label class="form-control-label font-weight-bold tx-black">Length </label>

                <div class="row">
                    <div class="col-lg-6 pd-r-0"><input type="text" class="form-control this_save" placeholder="ft."
                                                        name="lengthft[]"  id="lengthft${vehicle_count}"></div>
                    <div class="col-lg-6 pd-l-0"><input type="text" class="form-control" placeholder="in."
                                                        name="lengthin[]"  id="lengthin${vehicle_count}">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3 col-md-3">
            <div class="form-group"><label class="form-control-label font-weight-bold tx-black">Width</label>

                <div class="row">
                    <div class="col-lg-6 pd-r-0"><input type="text" class="form-control this_save" placeholder="ft."
                                                        name="widthft[]"  id="widthft${vehicle_count}"></div>
                    <div class="col-lg-6 pd-l-0"><input type="text" class="form-control this_save" placeholder="in."
                                                        name="widthin[]"  id="widthin${vehicle_count}"></div>
                </div>
            </div>
        </div>
        <div class="col-sm-3 col-md-3">
            <div class="form-group">

                <label class="form-control-label font-weight-bold tx-black">Height</label>

                <div class="row">
                    <div class="col-lg-6 pd-r-0"><input type="text" class="required form-control this_save" placeholder="ft."
                                                        name="heigthft[]"  id="heigthft${vehicle_count}">
                    </div>
                    <div class="col-lg-6 pd-l-0"><input type="text" class="required form-control this_save" placeholder="in."
                                                        name="heigthin[]"  id="heigthin${vehicle_count}">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3 col-md-3">
            <div class="form-group"><label class="form-control-label font-weight-bold tx-black">Weight </label> <input type="text" class="required form-control this_save"
                                                                  name="weight[]"
                                                                  id="weight${vehicle_count}"></div>
        </div>
        <div class="col-sm-3 col-md-3">
            <div class="form-group"><label class="form-control-label font-weight-bold tx-black">Equipment Condition
                   </label> <select id="condition${vehicle_count}" name="condition[]"
                                                                     class="form-control this_save vehicle-condition">
                    <option selected="" disabled="">Select</option>
                    <option value="1">Running</option>
                    <option value="2">Not Running</option>
                    <option value="3">It Roll</option>
                </select></div>
        </div>
        <div class="col-sm-3 col-md-3">
            <div class="form-group"><label class="form-control-label font-weight-bold tx-black">Trailer Type </label> <select id="trailter_type${vehicle_count}"
                                                                   name="trailter_type[]"
                                                                   class="form-control this_save trailer-type">
                    <option selected="" disabled="">Select</option>
                    <option value="1">Open</option>
                    <option value="2">Enclosed</option>
                </select></div>
        </div>
        <div class="col-sm-3 col-md-3">
            <div class="form-group"><label class="form-control-label font-weight-bold tx-black">Load Method <select id="load_method${vehicle_count}" name="load_method[]"
                                                                    class="form-control this_save">
                    <option selected="" disabled="">Select</option>
                    <option value="loading_dock">Loading Dock</option>
                    <option value="crane">Crane</option>
                    <option value="forklift">Forklift</option>
                    <option value="drive_roll">Drive/roll on from ground</option>
                </select></div>
        </div>
        <div class="col-sm-3 col-md-3">
            <div class="form-group"><label class="form-control-label font-weight-bold tx-black">Unload Method</label> <select id="unload_method${vehicle_count}" name="unload_method[]" class="form-control this_save">
                    <option selected="" disabled="">Select</option>
                    <option value="loading_dock">Loading Dock</option>
                    <option value="crane">Crane</option>
                    <option value="forklift">Forklift</option>
                    <option value="drive_roll">Drive/roll on from ground</option>
                </select></div>
        </div>
        <div class="col-sm-6 col-md-6"> &nbsp;
            <div class="form-group"><label class="ckbox"> <input type="checkbox" name="portTitle${vehicle_count}"
                                                                 id="needTitle${vehicle_count}"
                                                                 onclick="goto_port(${vehicle_count})"
                                                                 class="this_save"> <input type="hidden"
                                                                                           id="portTitlehidden${vehicle_count}"
                                                                                           name="portTitlehidden[]"
                                                                                           value="false"><span>&nbsp;Need Title?</span>
                </label></div>
        </div>
    </div>
</div>  </div></div> &nbsp;
            `);
            resetVehicle();
            vehicle_count++;
            selectRefresh();
        });

        $("#central_chk1").click(function () {

            $("#may_be_book").html('');
            $("#confirm_book").html(`

                    <input style="width: 150px;" class="form-control this_save" autocomplete="nope" type="text" name="company_name" id="company_name" placeholder="Company Name">

                    <input style="width: 150px;" class="form-control this_save" autocomplete="nope" type="text" name="company_price" id="company_price" placeholder="Price">
                `);
        });

        $("#central_chk2").click(function () {
            $("#confirm_book").html('');
            $("#may_be_book").html(`

                        <input  style="width: 150px;" class="form-control this_save" autocomplete="nope" type="text" name="company_name" id="company_name2" placeholder="Company Name">

                        <input  style="width: 150px;" class="form-control this_save" autocomplete="nope" type="text" name="company_price" id="company_price2" placeholder="Price">

                        <input  style="width: 150px;" class="form-control this_save" autocomplete="nope" type="text" name="company_comments" id="company_comments" placeholder="Comments">
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


            //alert(`#vehicle${vehicle_count}`);
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
                // toastr.error("Please Enter Origin & Dest City or Zip", "Error");
            }
            // else {
            //     ozip = ozip.split(",");
            //     dzip = dzip.split(",");

            //     var url = `https://www.google.com/maps/dir/${ozip[2]},+USA/${dzip[2]},+USA/`;
            //     window.open(url, 'Map', 'height=700,width=800,left=200,top=50,toolbar=No,location=No,scrollbars=Yes,status=No,resizable=Yes,fullscreen=No,directories=No,menubar=No,copyhistory=No');

            // }
        });

    </script>



    <script>


        $("body").delegate(".ophone", "focus", function () {
            // $(".ophone").mask("(999) 999-9999");
            $(".ophone")[0].setSelectionRange(0, 0);
        });

        $("body").delegate(".dphone", "focus", function () {
            // $(".dphone").mask("(999) 999-9999");
            $(".dphone")[0].setSelectionRange(0, 0);
        });

        $(document).on('click', '#ophonee', function () {
            // $("#ophonee").mask("(999) 999-9999");
            $("#ophonee")[0].setSelectionRange(0, 0);

        });

        $(document).on('click', '#ophonee', function () {
            // $("#ophonee").mask("(999) 999-9999");
            $("#ophonee")[0].setSelectionRange(0, 0);

        });
        $(".ophone").keypress(function(e){
            if($(this).val() == '')
            {
                $(this).mask("(999) 999-9999");
            }
            var x = e.which || e.keycode;
            if ((x >= 48 && x <= 57) || (x >= 35 && x <= 40)){
                return true;
            }else{
                return false;
            }
        })
        $(".dphone").keypress(function(e){
            var x = e.which || e.keycode;
            if ((x >= 48 && x <= 57) || (x >= 35 && x <= 40)){
                return true;
            }else{
                return false;
            }
        })


        $(document).on('click', '.ophonev', function () {

            // $(".ophonev").mask("(999) 999-9999");
            $(".ophonev")[0].setSelectionRange(0, 0);

        });

        $(document).on('click', '#oacutionphoNo', function () {
            // $("#oacutionphoNo").mask("(999) 999-9999");
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
                source: "/getmake"
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
                source: "/getmodel?year=" + yy + "&make=" + mm
            });

            my_func(num);
        }

        function get_vin(num) {
            var vinno = $(`#vinNum${num}`).val();
            if(vinno == '')
            {
                $("#year" + num).val('');
                $("#makeOpt" + num).val('');
                $("#model" + num).val('');
            }
            else
            {
                $.ajax({
                    type: "GET",
                    url: "/getvin",
                    dataType: 'JSON',
                    data: {term: vinno},
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
                    },
                    complete: function (res) {


                        setTimeout(function () {
                            $('#year0').trigger('change');
                        }, 2000);
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


            $("body").delegate("#terms,#balance_time,#balance_method,#cod_cop_loc,#payment_method", "change", function () {
                $('#copcodAmount').trigger('change');
            });


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


            // $(function () {
            //     $("#o_zip1").autocomplete({
            //         source: "/get_zip"
            //     });
            // });

            // $(function () {
            //     $("#d_zip1").autocomplete({
            //         source: "/get_zip"
            //     });
            // });

            $("#form").on('submit', (function (e) {
                var data = $(this).serialize();
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
                        $("#clickToSubmit").attr('disabled',true);
                        $("#savceChanges").attr('disabled',true);

                    },
                    success: function (data) {


                        //let test = data.toString();
                        let test = data["success"];
                        let test2 = $.trim(test);
                        let text = "SUCCESS";
                        if (test2 == text) {
                            $('#success').html(data);
                            $('#modaldemo4').modal('show');
                            $("#neworderpay_btn").val(0);

                            if ($("#continuetopay_btn").val() == 1 || $("#continuetopayold_btn").val() == 1) {


                                window.open('/order_payment_card_us/' + data["orderid"], '_blank');
                                window.location.href = "/new";

                            } else {
                                window.location.href = "/new";
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

        $('#zipCityDest').click(function(){
            var orderID = $(this).siblings('.orderID').val();
            var ozip = $("#o_zip1").val();
            var dzip = $("#d_zip1").val();
            var vehicleType = $(".vehicle-type option:selected");
            var vehicleName = $(".vehicleName");

            // .children('option:selected').val()
            var arr = [];
            $.each(vehicleType,function(){
                arr.push(this.value);
            });
            var arr2 = [];
            $.each(vehicleName,function(){
                arr2.push(this.value);
            });

            if (ozip == '' || dzip == '' || vehicleName == '') {
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

                var odata = ozip.split(", ");
                var ddata = dzip.split(", ");

                var ozip1 = odata[2];
                var ocity1 = odata[0];
                var dzip1 = ddata[2];
                var dcity1 = ddata[0];

                var url = `/records_city_zip_destination?ocity=${ocity1}&dcity=${dcity1}&ozip=${ozip1}&dzip=${dzip1}&vehicle=${arr}&vehicleName=${arr2}&orderID${orderID}`;
                window.open(url, 'View Previous Prices', 'height=800,width=1000,left=150,top=100,toolbar=No,location=No,scrollbars=Yes,status=No,resizable=Yes,fullscreen=No,directories=No,menubar=No,copyhistory=No');

            }
        });
        $("body").addClass('sidenav-toggled');
        $("aside").hover(function(){
            $("body").toggleClass('sidenav-toggled');
        });

        function resetVehicle(){
            $('.type').change(function(){
                $(this).parent().parent().parent().parent().siblings().children().children().children('.vyear').val('');
                $(this).parent().parent().parent().parent().siblings().children().children().children('.vmodel').val('');
                $(this).parent().parent().parent().parent().siblings().children().children().children('.vmake').val('');
            })
        }
        resetVehicle();

        $("#coupon_number").keyup(function(){
            var coupon_number = $(this);
            coupon_number.parent('div').children('.alert').remove();
            if(coupon_number.val() == '')
            {
                coupon_number.parent('div').children('.alert').remove();
            }
            else
            {
                $.ajax({
                    url: "{{url('/coupon_number')}}",
                    type: "GET",
                    dataType: "json",
                    data: {coupon_number:coupon_number.val()},
                    success: function (res){
                        coupon_number.parent('div').children('.alert').remove();
                        if(res.status_code === 400)
                        {
                            coupon_number.parent('div').append(`
                            <div class="alert text-danger p-0"><strong>${res.err}</strong></div>
                        `);
                        }
                        else
                        {
                            coupon_number.parent('div').append(`
                            <div class="alert text-success p-0"><strong>${res.msg}</strong></div>
                        `);
                        }
                    }
                });
            }
        });
        $(document).on('click',".editphoneonoff",function(){
            var input = $(this).parent('label').siblings('.form-group').children('input');
            if(input.attr('readonly'))
            {
                input.attr('readonly',false);
            }
            else
            {
                input.attr('readonly',true);
            }
        })

        $(document).ready(function() {
            $("#previousCheckPrice").click(function() {
                var ozip = $("#o_zip1").val();
                var dzip = $("#d_zip1").val();
                var order_id = '{{ $data->id }}';
                if (ozip == '' || dzip == '') {
                    alert('Please Enter Origin & Dest City or Zip');
                    // toastr.error("Please Enter Origin & Dest City or Zip", "Error");
                } else {
                    var url =
                        `/previous_check_prices?ocity=${btoa(ozip)}&dcity=${btoa(dzip)}&order_id=${btoa(order_id)}`;
                    window.open(url, 'Previous Orders',
                        'height=700,width=1200,left=150,top=50,toolbar=No,location=No,scrollbars=Yes,status=No,resizable=Yes,fullscreen=No,directories=No,menubar=No,copyhistory=No'
                    );
                }
            });

            $('#checkPrice').click(function() {
                console.log('yesok');
                // Get values of the required fields
                var o_zip1 = $('#o_zip1').val();
                var d_zip1 = $('#d_zip1').val();
                var vyear = $('#year0').val();
                var vmake = $('#makeOpt0').val();
                var vmodel = $('#model0').val();
                var vehType = $('#vehType0').val();
                var trailter_type = $('#trailter_type0').val();

                if (o_zip1 === "" || d_zip1 === "") {
                    $('#modalMessage').html(
                        '<div class="alert alert-warning" role="alert">' +
                        '<strong>Please fill Origin?destination zipcodes, Vehicle Type and Trailer Type.</strong>' +
                        '</div>'
                    );
                    $('#responseModal').modal('show');
                    return;
                }

                var order_id = '{{ $data->id }}';
                $.ajax({
                    url: "{{ route('check.for.price') }}",
                    method: "GET",
                    data: {
                        order_id: order_id,
                    },
                    success: function(response) {
                        console.log('responses', response);
                        if (response && response.data === undefined) {
                            $('#getCheckPrice').show();
                        } else {
                            $.ajax({
                                url: "{{ route('request.check.price') }}",
                                type: "GET",
                                data: {
                                    order_id: order_id,
                                },
                                success: function(response) {
                                    console.log('Check Price:', response);
                                    var message = response.message;
                                    var alertClass = response.message ==
                                        'Price already requested, please wait' ?
                                        'alert-danger' :
                                        'alert-success';

                                    // Show success or error message in the modal
                                    $('#modalMessage').html(
                                        '<div class="alert ' + alertClass +
                                        '" role="alert">' +
                                        '<strong>' + message + '</strong>' +
                                        '</div>'
                                    );
                                    $('#responseModal').modal('show');

                                    // $('#getCheckPrice').show();
                                },
                                error: function(xhr, status, error) {
                                    console.error('An error occurred:', xhr
                                        .responseText);
                                    // Show error message in the modal if the AJAX request fails
                                    $('#modalMessage').html(
                                        '<div class="alert alert-danger" role="alert">' +
                                        '<strong>An error occurred. Please try again later.</strong>' +
                                        '</div>'
                                    );
                                    $('#responseModal').modal('show');
                                }
                            });
                        }
                    },
                });
            });

            $('#getCheckPrice').change(function() {
                var order_id = '{{ $data->id }}';
                var getCheckPrice = $('#getCheckPrice').val();

                $.ajax({
                    url: "{{ route('get.check.price') }}", // Ensure this route is correct
                    type: "GET",
                    data: {
                        order_id: order_id,
                        getCheckPrice: getCheckPrice,
                    },
                    success: function(response) {
                        console.log('Get Check Price:', response);
                        if (response.message) {
                            // Handle error message (if any)
                            $('#showCheckPrice').html(
                                '<div class="alert alert-danger" role="alert">' +
                                response.message +
                                '</div>'
                            );
                        } else {
                            // Display the price for the selected vehicle type
                            $('#showCheckPrice').html(response.price);
                            $('#getCheckPriceVal').val(response.price);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('An error occurred:', xhr.responseText);
                    }
                });
            });
        });
    </script>


@endsection

