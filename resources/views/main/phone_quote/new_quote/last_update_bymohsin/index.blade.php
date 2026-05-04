@extends('layouts.innerpages')

@section('template_title')
    New Quote
@endsection
<style>
    .oauc {
        padding: 11px;
        width: 100%;
    }

    .dauc {
        padding: 11px;
        width: 100%;
    }
</style>

@section('content')

    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">New Quote </h4>
            <h4 id="orderidplace"></h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fe fe-layers mr-2 fs-14"></i>Pages</a>
                </li>

                <li class="breadcrumb-item active" aria-current="page"><a href="#">New Quote</a></li>
            </ol>
        </div>

    </div>
    <!--End Page header-->
    <!-- Row -->

    <form action="" id="form" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token()}}">

        <!-- Row -->
        <div class="row">
            <div class="col-xl-6 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">ORIGIN LOCATION</div>
                    </div>
                    <div class="card-body">
                        <div class="card-title font-weight-bold">Basic info:</div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Terminal, Dealer, Auction *</label>
                                    <select class="form-control this_save  select2" name="oterminal" id="oterminal"
                                            required>
                                        <optgroup label="Categories">
                                            <option data-select2-id="5" selected="" disabled="">--Select--</option>
                                            <option value="1">Residence</option>
                                            <option value="2">COPART Auction</option>
                                            <option value="3">Manheim Auction</option>
                                            <option value="4">IAAI Auction</option>
                                            <option value="5">Body Shop</option>
                                            <option value="10">Dealership</option>
                                            <option value="7">Business Location</option>
                                            <option value="8">Auction (Heavy)</option>
                                            <option value="6">Other</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="row oauc" style="margin-bottom: -22px;">
                            </div>

                            <div class="col-sm-12">

                                <div class="form-group">

                                    <label class="form-label">Name*</label>
                                    <input type="text" name="oname" id="oname" class="form-control this_save "
                                           placeholder="Name" required>
                                </div>
                            </div>
                            <div class="col-sm-12 stock_number">

                            </div>
                            <!--
                            <div class="col-sm-12 buyer_number">
                                <div class="form-group">
                                    <label class="form-label">Buyer/Lot/Stock Number</label>
                                    <input type="text" name="obuyer_no" id="obuyer_no" class="form-control this_save "
                                           placeholder="Buyer/Lot/Stock Number">
                                </div>
                            </div>
                            -->
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">Email address</label>
                                    <input type="email" name="oemail" id="oemail" class="form-control this_save "
                                           placeholder="Email">
                                </div>
                            </div>

                            <div class="col-sm-12 add_phone">
                                <div class='row'>
                                    &nbsp; &nbsp; &nbsp; <label class="form-label">Phone Number*</label>

                                    <div class="form-group col-11 ">
                                        <input type="text" name="ophone[]" id="ophone"
                                               class="form-control this_save  ophone"
                                               placeholder="Number" value="{{ $phoneno }}" required>
                                    </div>
                                    <div class='form-group col-1' style="padding-top: 7px;">
                                        <i id='add_btn' class="si si si-plus add_phone_btn"></i>

                                    </div>

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Address</label>
                                    <input type="text" name="oaddress" id="oaddress" class="form-control this_save "
                                           placeholder="Home Address">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Address2</label>
                                    <input type="text" id="oaddress2" name="oaddress2" class="form-control this_save "
                                           placeholder="Home Address">
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Zip Code*</label>
                                    <input type="text" id="o_zip1" class="form-control this_save "
                                           maxlength="11" name="o_zip1"
                                           placeholder="ZIP CODE" required/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="#" id='viewMap' class="btn  btn-primary">View Map</a>
                        <!-- <a href="#" class="btn btn-danger">Cancle</a> -->
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">DESTINATION LOCATION</div>
                    </div>
                    <div class="card-body">
                        <div class="card-title font-weight-bold">Basic info:</div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Terminal, Dealer, Auction *</label>
                                    <select class="form-control this_save select2" name="dterminal" id="dterminal" required>
                                        <option value="">Select</option>
                                        <option value="1">Residence</option>
                                        <option value="2">COPART Auction</option>
                                        <option value="3">Manheim Auction</option>
                                        <option value="4">IAAI Auction</option>
                                        <option value="5">Body Shop</option>
                                        <option value="11">Dealership</option>
                                        <option value="7">Port</option>
                                        <option value="6">AirPort</option>
                                        <option value="9">Business Location</option>
                                        <option value="10">Auction (Heavy)</option>
                                        <option value="8">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Name</label>
                                    <input type="text" id='dname' name='dname' class="form-control this_save "
                                           placeholder="First Name">
                                </div>
                            </div>

                            <div class="row dauc" style="margin-bottom: -22px;"></div>
                            <div class="col-sm-12 col-md-12 add_dphone">

                                <div class="row">
                                    &nbsp; &nbsp; &nbsp; <label class="form-label">Phone Number</label>

                                    <div class="form-group col-11 ">
                                        <input type="text" name="dphone[]" id="dphone"
                                               class="form-control this_save  dphone"
                                               placeholder="Number">
                                    </div>
                                    <div class="form-group col-1" style="padding-top: 7px;">
                                        <i id="add_btn" class="si si si-plus add_dphone_btn"></i>

                                    </div>

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Address</label>
                                    <input type="text" name='daddress' id=' daddress' class="form-control this_save "
                                           placeholder="Home Address">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Address2</label>
                                    <input type="text" name='daddress2' id='daddress2' class="form-control this_save "
                                           placeholder="Home Address">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Zip Code*</label>
                                    <input type="text" id="d_zip1" class="form-control this_save "
                                           maxlength="11" name="d_zip"
                                           placeholder="ZIP CODE" required/>
                                </div>
                            </div>


                        </div>

                    </div>
                    <div class="card-footer text-left">
                        <a class="btn btn-primary"
                           href="javascript:timezon('https://www.timeanddate.com/worldclock/usa');">Time Zone</a>
                    </div>
                </div>
            </div>


            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">VEHICLE INFORMATION</div>
                    </div>
                    <div class="card-body">
                        <div class='row '>
                            <div class="col-5 ">
                                <div class="form-group">
                                    <label class="rdiobox">
                                        <!-- <input name="vehicle${vehicle_count}" id="vehicle${vehicle_count}"
                                                onclick="vehicle_append(0)" type="radio"
                                                checked value="0" data-parsley-multiple="vehicle${vehicle_count}">
                                                -->
                                        <input type="hidden" name="count[]" value="1">
                                        <input class="this_save" name="vehicle0" id="vehicle0"
                                               onclick="vehicle_append(0)" type="radio"
                                               checked value="1" data-parsley-multiple="vehicle0">

                                        <input name="vehicle_v[]" id="vehicle_v0" type="hidden" value="make">


                                        <span>Year, Make, and Model</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-5 ">
                                <div class="form-group">
                                    <label class="rdiobox">
                                        <!-- <input name="vehicle${vehicle_count}" id="vin${vehicle_count}" type="radio"
                                                onclick="vin_append(0)"
                                                value="1" data-parsley-multiple="vehicle${vehicle_count}">
                                                -->
                                        <input class="this_save" name="vehicle0" id="vin0" type="radio"
                                               onclick="vin_append(0)"
                                               value="2" data-parsley-multiple="vehicle0">

                                        <input name="vehicle_v[]" disabled id="vehicle_v_vin0" type="hidden"
                                               value="vin">
                                        <span>Vin Number</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-2 btn-list">

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
                                    <label class="form-label">Year*</label>
                                    <input required type="text" class="form-control this_save " id='year0'
                                           name='vyear[]'
                                           placeholder="Enter Year">

                                </div>
                            </div>

                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Make*</label>
                                    <input type="text" required class="form-control this_save  makeOpt0"
                                           onkeyup="getmake()" id='makeOpt0' name='vmake[]'
                                           placeholder="Enter Make">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">

                                <div class="googleimage" onclick="googl(0)" id="googleimage0"
                                     style="position: absolute; right: 6%;display:none"><a
                                        href="javascript:void(0);"><img width="24"
                                                                        src="assets/images/png/google.png"></a></div>

                                <div class="form-group">
                                    <label class="form-label">Model*</label>
                                    <input required class="form-control this_save  model0" id='model0'
                                           onkeyup="getmodel()" name='vmodel[]'
                                           placeholder="Enter Model"
                                           type="text">
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Vehicle Type*</label>
                                    <select required id="vehType0" onfocus="my_func(0)" name="vehType[]"
                                            class="form-control this_save select2">
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
                                    <label class="form-label">Vehicle Condition *</label>
                                    <select required="" id="condition0" name="condition[]"
                                            class="form-control this_save select2">
                                        <option selected="" value="">Select</option>
                                        <option value="1">Running</option>
                                        <option value="2">Not Running</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Trailer Type *</label>
                                    <select required id="trailter_type0" name="trailter_type[]"
                                            class="form-control this_save select2">
                                        <option selected="" value="">Select</option>
                                        <option value="1">Open</option>
                                        <option value="2">Enclosed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">

                                &nbsp;
                                <div class="form-group">
                                    <label class="ckbox">
                                        <input class="this_save" type="checkbox" name="portTitle0" id="needTitle0"
                                               onclick="goto_port(0)"><span>&nbsp;Need Title?</span>
                                        <input type="hidden" name="portTitlehidden[]" id="portTitlehidden0"
                                               value="false">
                                    </label>
                                </div>
                            </div>
                            <div class=" col-12 add_vehicle_information   ">


                            </div>

                            <div class="col-md-12 ">
                                <div class="form-group">
                                    <label class="form-label">Additional Vehicle Information</label>
                                    <textarea type="text" name='addition_info' id='addition_info0'
                                              class="form-control this_save "
                                              placeholder=""></textarea>


                                </div>
                            </div>


                        </div>
                        &nbsp;

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
                        <div class="card-title">VEHICLE INFORMATION</div>
                    </div>
                    <div class="card-body">
                        <div class='row '>
                            <div class="col ">
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
                                        {{--   <input class="form-control this_save " id='pickup_date' name='pickup_date'
										placeholder="MM/DD/YYYY" type="text" id="pickup_date">--}}

                                        <input type="text" name="pickup_date" id="pickup_date"
                                               class="form-control this_save "
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
                                               placeholder="MM/DD/YYYY" autocomplete="off" data-parsley-id="">
                                    </div>
                                </div>

                            </div>


                        </div>

                        &nbsp;
                        <div class="row ">
                            <div class="col-lg-6" id="whenPickUpDate"></div>
                            <div class="col-lg-6" id="whenDeliveryDate"></div>
                        </div>

                        <div class="card-footer text-left">
                            <!-- <a href="#" class="btn btn-danger">Cancle</a> -->
                        </div>
                        <div class="row">
                            <div class="col " id='btn_center'>
                                <a href="" id="viewCentral" class="btn btn-primary mg-r-10">View Pricing</a>
                                <a href="javascript:web('https://admin.shipa1.com/calculator/?ozip=')"
                                   class="btn btn-primary mg-r-10">View Web Pricing</a>
                                <a href="" id="previousRecord" class="btn btn-primary mg-r-10">Previous Record</a>
                                <a href="javascript:weather('https://www.weather.gov/');"
                                   class="btn btn-outline-primary mg-r-10">View Weather</a>
                                <a href="javascript:fuel('http://www.truckmiles.com/FuelPrices.asp');"
                                   class="btn btn-outline-primary">Fuel Price</a>
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
                            <div class='row '>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">PRICING & PAYMENT</label>
                                        <textarea required type="text" placeholder="ORDER BOOKING PRICE *"
                                                  id='orderPrice'
                                                  name='price' class="form-control this_save "
                                                  placeholder=""></textarea>


                                    </div>
                                    <div class="form-group">
                                        <label class="ckbox">
                                            <input class="this_save" type="checkbox" name="needDeposit" id="needDeposit"
                                                   value="yes"
                                                   data-parsley-multiple="needDeposit">
                                            <span>&nbsp;Deposit Amount?</span>
                                        </label>
                                    </div>

                                    <div id="depositContent"></div>
                                    &nbsp;

                                </div>

                                <div class="col-md-6 ">

                                    <label class="rdiobox radio_btn">
                                        <input class="this_save" name="central_chk" id="central_chk1" type="radio"
                                               value="condirm"
                                               data-parsley-multiple="central_chk">
                                        <span>Central List</span>
                                    </label>
                                    &nbsp;
                                    &nbsp;
                                    <label class="rdiobox radio_btn">
                                        <input class="this_save" name="central_chk" id="central_chk2" type="radio"
                                               value="may be"
                                               data-parsley-multiple="central_chk">
                                        <span>Central May Be Listr</span>
                                    </label>
                                    &nbsp;
                                    &nbsp;
                                    <label class="rdiobox radio_btn">
                                        <input class="this_save" name="central_chk" id="central_chk3" type="radio"
                                               value="none"
                                               checked="checked" data-parsley-multiple="central_chk">
                                        <span>None</span>

                                    </label>

                                    <div class="row" id='confirm_book'>

                                    </div>
                                    <div class="row" id="may_be_book">

                                    </div>

                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label font-weight-bold tx-black">Price to Pay
                                            Carrier</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fa fa-usd tx-16 lh-0 op-6"></i>
                                                </div>
                                            </div>
                                            <input class="form-control this_save " autocomplete="nope" type="text"
                                                   name="pay_carrier" value="" placeholder="" id="payCarrier">
                                        </div>
                                    </div>

                                </div>

                                <div class="col-lg-6">
                                    <label class="rdiobox radio_btn">
                                        <input class="this_save" name="vehicle" id="carrier_status_1" type="radio"
                                               checked="checked"
                                               value="quick_pay"
                                               data-parsley-multiple="carrier_status">
                                        <span>Quick Pay</span>
                                    </label>


                                    <label class="rdiobox radio_btn">
                                        <input class="this_save" name="vehicle" id="carrier_status_2" type="radio"
                                               value="cod"
                                               data-parsley-multiple="carrier_status">
                                        <span>COD</span>
                                    </label>

                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="label font-weight-bold tx-black">COD/COP
                                            Amount</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fa fa-usd tx-16 lh-0 op-6"></i>
                                                </div>
                                            </div>
                                            <input class="form-control this_save " autocomplete="nope"
                                                   style="width: 80%"
                                                   type="text" name="cod_cop" value="" placeholder="" id="copcodAmount">
                                        </div>

                                    </div>
                                    <div id="copcodPart" style="display: none">
                                        <div class="form-group">
                                            <label class="form-control -label font-weight-bold tx-black">COD/COP Payment
                                                Method <span class="tx-danger">*</span></label>
                                            <select id="payment_method" name="payment_method"
                                                    class="form-control this_save ">
                                                <option disabled="" selected=""></option>
                                                <option value="Cash/Certified Funds">Cash/Certified Funds</option>
                                                <option value="Check">Check</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control -label font-weight-bold tx-black">COD/COP
                                                Location
                                                <span class="tx-danger">*</span></label>
                                            <select id="cod_cop_loc" name="cod_cop_loc" class="form-control this_save ">
                                                <option disabled="" selected=""></option>
                                                <option value="Pickup">Pickup</option>
                                                <option value="Delivery">Delivery</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="label font-weight-bold tx-black">Balance
                                            Amount</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fa fa-usd tx-16 lh-0 op-6"></i>
                                                </div>
                                            </div>
                                            <input class="form-control this_save " type="text" name="balance" value=""
                                                   readonly=""
                                                   placeholder="" id="balAmount">
                                        </div>
                                    </div>

                                    <div id="balPart" style="display:none">
                                        <div class="form-group">
                                            <label class="form-control -label font-weight-bold tx-black">Balance Payment
                                                Method <span class="tx-danger">*</span></label>
                                            <select id="balance_method" name="balance_method"
                                                    class="form-control this_save ">
                                                <option disabled="" selected=""></option>
                                                <option value="Cash">Cash</option>
                                                <option value="Certified Funds">Certified Funds</option>
                                                <option value="Company Check">Company Check</option>
                                                <option value="Comchek">Comchek</option>
                                                <option value="TCH">TCH</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control -label font-weight-bold tx-black">Balance Payment
                                                Time <span class="tx-danger">*</span></label>
                                            <select id="balance_time" name="balance_time"
                                                    class="form-control this_save ">
                                                <option disabled="" selected=""></option>
                                                <option value="Immediately">Immediately</option>
                                                <option value="2 Business Days (Quick Pay)">2 Business Days (Quick
                                                    Pay)
                                                </option>
                                                <option value="5 Business Days">5 Business Days</option>
                                                <option value="10 Business Days">10 Business Days</option>
                                                <option value="15 Business Days">15 Business Days</option>
                                                <option value="30 Business Days">30 Business Days</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control -label font-weight-bold tx-black">Balance Payment
                                                Terms Begin On <span class="tx-danger">*</span></label>
                                            <select id="terms" name="terms" class="form-control this_save ">
                                                <option disabled="" selected=""></option>
                                                <option value="Pickup">Pickup</option>
                                                <option value="Delivery">Delivery</option>
                                                <option value="Receiving a Signed Bill of Lading">Receiving a Signed
                                                    Bill of Lading *
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-lg-12" id="alertMSG">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">ADDITIONAL INFORMATION</label>
                                        <textarea id="additional_2" name="additional_2" rows="8"
                                                  class="form-control this_save "
                                                  placeholder="Enter any special instructions, notes from customer or details regarding this shipment..."></textarea>


                                    </div>

                                </div>
                            </div>

                        </div>
                        <div class="card-footer text-left">

                            <a href="javascript:void(0)" id=newCust class="btn btn-primary">New Customer Order</a>
                            <a href="javascript:void(0)" id='oldCust' class="btn btn-primary">Old Customer Order</a>
                            <button type="submit" id="saveBtn" name="saveBtn" style="border: 1px solid;"
                                    class="btn btn-outline-primary mg-l-20 float-right">Save
                            </button>
                        </div>
                        <div class="col-lg-12" id="payCondition"></div>
                    </div>
                </div>

            </div>
            <!-- End Row-->
        </div>
        <input type="hidden" name="orderid" id="orderid_find" value="{{ $orderId }}"/>
    </form>



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


@endsection

@section('extraScript')
    <script>


        function my_func(get) {
            var model = $(`#model${get}`).val();
            var make = $(`#makeOpt${get}`).val();
            var year = $(`#year${get}`).val();
            if (model.length <= 0 && make.length <= 0 && year.length <= 0) {
                $(`#googleimage${get}`).hide();
            } else {
                $(`#googleimage${get}`).show();

            }
        };

        function googl(get) {

            var model = $(`#model${get}`).val();
            var make = $(`#makeOpt${get}`).val();
            var year = $(`#year${get}`).val();

            var url = (`http://images.google.com/images?q=${year}+${make}+${model}`);
            window.open(url, 'GoogleImg', 'width=800,height=600,left=250,top=50, toolbar=No,location=No,scrollbars=Yes,status=No,resizable=Yes,fullscreen=No,directories=No,menubar=No,copyhistory=No');


        }


        document.querySelector("#payCarrier").addEventListener("keypress", function (evt) {
            if (evt.which < 48 || evt.which > 57) {
                evt.preventDefault();
            }
        });

        document.querySelector("#copcodAmount").addEventListener("keypress", function (evt) {
            if (evt.which < 48 || evt.which > 57) {
                evt.preventDefault();
            }
        });

        document.querySelector("#orderPrice").addEventListener("keypress", function (evt) {
            if (evt.which < 48 || evt.which > 57) {
                evt.preventDefault();
            }
        });


        function payCondition() {
            var data = `<div class="row">
                    <input type="hidden" name="customer_status" value="1">
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

                <div class="row" id="payConf">

                </div>

                <div class="row" id="emailRequired">

                </div>

                <div class="row" id="submitData" style="display:none">
                    <div class="col-lg-12">
                        <button type="submit" id="clickToSubmit"  class="btn btn-primary"></button>
                    </div>
                </div>
                `;

            return data;
        }

        function payConditionJS() {
            $("#pay_cond1").click(function () {
                $("#saveBtn").html('Save');
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

                    <label class="form-control this_save -label font-weight-bold tx-black">Email Address <span class="tx-danger">*</span></label>
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
            });

            $("#pay_cond2").click(function () {
                $("#saveBtn").html('Save');
                $("#payConf").html('');
                $("#submitData").show();
                $("#emailRequired").html(`
            <div class="col-lg-4">
                <div class="form-group">

                    <label class="form-control this_save -label font-weight-bold tx-black">Email Address <span class="tx-danger">*</span></label>
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
            });

            $("#pay_cond3").click(function () {
                $("#saveBtn").html('Next');
                $("#payConf").html('');
                $("#submitData").show();
                $("#emailRequired").html('');
                $("#clickToSubmit").html('Continue To Payment');
                var email = $("#oemail").val();
                $("#oemail2").val(email);

                $("#oemail2").change(function () {
                    $("#oemail").val($(this).val());
                });
            });

            $("#pay_cond4").click(function () {
                $("#saveBtn").html('Save');
                $("#payConf").html('');
                $("#submitData").show();
                // $("#emailRequired").html(`
                //     <div class="col-lg-4">
                //         <div class="form-group">
                //
                //             <label class="form-control this_save -label font-weight-bold tx-black">Email Address <span class="tx-danger">*</span></label>
                //             <input required class="form-control this_save " autocomplete="nope" type="text" id="oemail2" value="" >
                //
                //         </div>
                //     </div>
                // `);
                $("#clickToSubmit").html('Submit');
                var email = $("#oemail").val();
                $("#oemail2").val(email);

                $("#oemail2").change(function () {
                    $("#oemail").val($(this).val());
                });
            });

            $("#clickToSubmit").click(function () {
                $("#selecttype").modal('hide')
                $("#manualOrder").attr('action', 'post.php');
                $("#manualOrder").submit();
            });

        }

        function oldPayCondition() {
            var data = `
                <input type="hidden" name="customer_status" value="0">
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
                <div class="row" id="sendEmailConf">

                </div>

                <div class="row" id="payConf">

                </div>

                <div class="row" id="emailRequired">

                </div>

                <div class="row" id="submitData" style="display:none">
                    <div class="col-lg-12">
                        <button type="submit" id="clickToSubmit"  class="btn btn-primary"></button>
                    </div>
                </div>
                `;

            return data;
        }

        function oldPayConditionJS() {
            $("#pay_cond1").click(function () {
                $("#saveBtn").html('Save');
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
                $("#sendEmailConf").html(`
            <div class="col-lg-12">
                <div class="form-group">

                    <label class="section-title mt-3">Send Email <span class="tx-danger">*</span></label>

                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group">

                    <label class="rdiobox">
                        <input class="this_save" name="confirm" onchange="sendConf(1)" type="radio" value="1"   required>
                        <span>Yes</span>
                    </label>

                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group">

                    <label class="rdiobox">
                        <input class="this_save" name="confirm" onchange="sendConf(2)" type="radio" value="0" >
                        <span>No</span>
                    </label>

                </div>
            </div>
        `);
                $("#submitData").show();
                $("#emailRequired").html(`
            <div class="col-lg-4">
                <div class="form-group">

                    <label class="form-control this_save -label font-weight-bold tx-black" id="emailAddrTxt">Email Address <span class="tx-danger">*</span></label>
                    <input required class="form-control this_save " autocomplete="nope" type="text" name="oemail2" id="oemail2" value="" >

                </div>
            </div>
        `);
                $("#clickToSubmit").html('Submit');
                var email = $("#oemail").val();
                $("#oemail2").val(email);

                $("#oemail2").change(function () {
                    $("#oemail").val($(this).val());
                });
            });

            $("#pay_cond2").click(function () {
                $("#saveBtn").html('Save');
                $("#sendEmailConf").html('');
                $("#submitData").show();
                $("#payConf").html('');
                $("#emailRequired").html(`
            <div class="col-lg-4">
                <div class="form-group">

                    <label class="form-control this_save -label font-weight-bold tx-black" id="emailAddrTxt">Email Address <span class="tx-danger">*</span></label>
                    <input required class="form-control this_save " autocomplete="nope" type="text" name="oemail2" id="oemail2" value="" >

                </div>
            </div>
        `);
                $("#clickToSubmit").html('Submit');
                var email = $("#oemail").val();
                $("#oemail2").val(email);

                $("#oemail2").change(function () {
                    $("#oemail").val($(this).val());
                });
            });

            $("#pay_cond3").click(function () {
                $("#saveBtn").html('Save');
                $("#sendEmailConf").html('');
                $("#submitData").show();
                $("#payConf").html('');
                $("#emailRequired").html('');
                $("#clickToSubmit").html('Continue To Payment');
                var email = $("#oemail").val();
                $("#oemail2").val(email);

                $("#oemail2").change(function () {
                    $("#oemail").val($(this).val());
                });
            });

            $("#pay_cond4").click(function () {
                $("#saveBtn").html('Save');
                // $("#sendEmailConf").html(`
                //     <div class="col-lg-12">
                //         <div class="form-group">
                //
                //             <label class="section-title mt-3">Send Email <span class="tx-danger">*</span></label>
                //
                //         </div>
                //     </div>
                //     <div class="col-lg-2">
                //         <div class="form-group">
                //
                //             <label class="rdiobox">
                //                 <input class="this_save" name="confirm" onchange="sendConf(1)" type="radio" value="1" required >
                //                 <span>Yes</span>
                //             </label>
                //
                //         </div>
                //     </div>
                //     <div class="col-lg-2">
                //         <div class="form-group">
                //
                //             <label class="rdiobox">
                //                 <input class="this_save" name="confirm" onchange="sendConf(2)" type="radio" value="0" >
                //                 <span>No</span>
                //             </label>
                //
                //         </div>
                //     </div>
                // `);
                // $("#emailRequired").html(`
                //     <div class="col-lg-4">
                //         <div class="form-group">
                //
                //             <label class="form-control this_save -label font-weight-bold tx-black" id="emailAddrTxt">Email Address <span class="tx-danger">*</span></label>
                //             <input required class="form-control this_save " autocomplete="nope" type="text" id="oemail2" value="" >
                //
                //         </div>
                //     </div>
                // `);
                $("#payConf").html('');
                $("#submitData").show();
                $("#emailRequired").show();
                $("#clickToSubmit").html('Submit');
                var email = $("#oemail").val();
                $("#oemail2").val(email);

                $("#oemail2").change(function () {
                    $("#oemail").val($(this).val());
                });
            });

            $("#clickToSubmit").click(function () {
                $("#selecttype").modal('hide')
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
            $("#payCondition").html('');
            $("#payCondition").html(payCondition());
            payConditionJS();
        });

        $("#oldCust").click(function () {
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
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                                </div>`);
                        } else if (payMethod) {
                            $("#alertMSG").html(`<div class="bg-danger-transparent-2 text-danger px-4 py-2 br-3 mb-4" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span>.
                                </div>`);
                        } else if (codcoploc) {
                            $("#alertMSG").html(`<div class="bg-danger-transparent-2 text-danger px-4 py-2 br-3 mb-4" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                                </div>`);
                        } else {
                            $("#alertMSG").html(`<div class="bg-danger-transparent-2 text-danger px-4 py-2 br-3 mb-4" role="alert"> The carrier will receive $
                            <span class="font-weight-bold">${copcod}</span>
                             </div>`);
                        }
                        $("#alertMSG").html('');

                        if (balTime && balTerms && balMethod) {
                            $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span> within <span class="font-weight-bold">${balTime}</span> of <span class="font-weight-bold">${balTerms}</span>.
                        </div>`);
                        } else if (balTime && balMethod) {
                            $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span> within <span class="font-weight-bold">${balTime}</span>.
                        </div>`);
                        } else {
                            $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span>.
                        </div>`);
                        }
                    } else if (pay > copcod) {
                        $("#alertMSG").html('');
                        if (payMethod && codcoploc) {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                                </div>`);
                        } else if (payMethod) {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span>.
                                </div>`);
                        } else if (codcoploc) {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                                </div>`);
                        } else {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span>.
                                </div>`);
                        }

                        if (balTime && balTerms && balMethod) {
                            $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span> within <span class="font-weight-bold">${balTime}</span> of <span class="font-weight-bold">${balTerms}</span>.
                        </div>`);
                        } else if (balTime && balMethod) {
                            $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span> within <span class="font-weight-bold">${balTime}</span>.
                        </div>`);
                        } else {
                            $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span>.
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
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                                </div>`);
                        } else if (payMethod) {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span>.
                                </div>`);
                        } else if (codcoploc) {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                                </div>`);
                        } else {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span>.
                                </div>`);
                        }

                        if (balTime && balTerms && balMethod) {
                            $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span> within <span class="font-weight-bold">${balTime}</span> of <span class="font-weight-bold">${balTerms}</span>.
                        </div>`);
                        } else if (balTime && balMethod) {
                            $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span> within <span class="font-weight-bold">${balTime}</span>.
                        </div>`);
                        } else {
                            $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span>.
                        </div>`);
                        }
                    }
                } else {
                    $("#balAmount").val(pay);
                    $("#alertMSG").html('');
                    $("#copcodPart").hide();

                    if (balTime && balTerms && balMethod) {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span> within <span class="font-weight-bold">${balTime}</span> of <span class="font-weight-bold">${balTerms}</span>.
                        </div>`);
                    } else if (balTime && balMethod) {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span> within <span class="font-weight-bold">${balTime}</span>.
                        </div>`);
                    } else if (balMethod) {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span>.
                        </div>`);
                    } else {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span>.
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
                                The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                            </div>`);
                    } else if (payMethod) {
                        $("#alertMSG").html('');
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span>.
                            </div>`);
                    } else if (codcoploc) {
                        $("#alertMSG").html('');
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                The carrier will receive $<span class="font-weight-bold">${copcod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                            </div>`);
                    } else {
                        $("#alertMSG").html('');
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                The carrier will receive $<span class="font-weight-bold">${copcod}</span>.
                            </div>`);
                    }
                }

                if (pay && !copcod) {
                    $("#alertMSG").html('');

                    if (balTime && balTerms && balMethod) {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${pay}</span> via <span class="font-weight-bold">${balMethod}</span> within <span class="font-weight-bold">${balTime}</span> of <span class="font-weight-bold">${balTerms}</span>.
                        </div>`);
                    } else if (balTime && balMethod) {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${pay}</span> via <span class="font-weight-bold">${balMethod}</span> within <span class="font-weight-bold">${balTime}</span>.
                        </div>`);
                    } else {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${pay}</span> via <span class="font-weight-bold">${balMethod}</span>.
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
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                                </div>`);
                        } else if (payMethod) {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span>.
                                </div>`);
                        } else if (codcoploc) {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                                </div>`);
                        } else {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span>.
                                </div>`);
                        }
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span>.
                        </div>`);
                    } else if (pay > copcod) {
                        $("#alertMSG").html('');
                        if (payMethod && codcoploc) {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                                </div>`);
                        } else if (payMethod) {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span>.
                                </div>`);
                        } else if (codcoploc) {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                                </div>`);
                        } else {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span>.
                                </div>`);
                        }
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span>.
                        </div>`);
                    }
                    if (bal < 0) {
                        $("#balAmount").val(Math.abs(bal));

                        $("#copcodPart").hide();
                        $("#copcodPart").show();

                        $("#alertMSG").html('');
                        if (payMethod && codcoploc) {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                                </div>`);
                        } else if (payMethod) {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span>.
                                </div>`);
                        } else if (codcoploc) {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                                </div>`);
                        } else {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span>.
                                </div>`);
                        }
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            The carrier will pay you $<span class="font-weight-bold">${Math.abs(bal)}</span>.
                        </div>`);
                    }
                } else {
                    $("#balAmount").val(pay);
                    $("#alertMSG").html('');
                    $("#copcodPart").hide();

                    if (balTime && balTerms && balMethod) {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span> within <span class="font-weight-bold">${balTime}</span> of <span class="font-weight-bold">${balTerms}</span>.
                        </div>`);
                    } else if (balTime && balMethod) {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span> within <span class="font-weight-bold">${balTime}</span>.
                        </div>`);
                    } else if (balMethod) {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span>.
                        </div>`);
                    } else {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span>.
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
                                The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                            </div>`);
                    } else if (payMethod) {
                        $("#alertMSG").html('');
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span>.
                            </div>`);
                    } else if (codcoploc) {
                        $("#alertMSG").html('');
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                The carrier will receive $<span class="font-weight-bold">${copcod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                            </div>`);
                    } else {
                        $("#alertMSG").html('');
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                The carrier will receive $<span class="font-weight-bold">${copcod}</span>.
                            </div>`);
                    }
                }

                if (pay && !copcod) {
                    $("#alertMSG").html('');
                    $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                You will pay the carrier $<span class="font-weight-bold">${pay}</span>.
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
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                                </div>`);
                        } else if (payMethod) {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span>.
                                </div>`);
                        } else if (codcoploc) {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                                </div>`);
                        } else {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span>.
                                </div>`);
                        }

                        if (balTime && balTerms && balMethod) {
                            $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span> within <span class="font-weight-bold">${balTime}</span> of <span class="font-weight-bold">${balTerms}</span>.
                        </div>`);
                        } else if (balTime && balMethod) {
                            $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span> within <span class="font-weight-bold">${balTime}</span>.
                        </div>`);
                        } else {
                            $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span>.
                        </div>`);
                        }
                    } else if (pay > copcod) {
                        $("#alertMSG").html('');
                        if (payMethod && codcoploc) {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                                </div>`);
                        } else if (payMethod) {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span>.
                                </div>`);
                        } else if (codcoploc) {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                                </div>`);
                        } else {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span>.
                                </div>`);
                        }

                        if (balTime && balTerms && balMethod) {
                            $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span> within <span class="font-weight-bold">${balTime}</span> of <span class="font-weight-bold">${balTerms}</span>.
                        </div>`);
                        } else if (balTime && balMethod) {
                            $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span> within <span class="font-weight-bold">${balTime}</span>.
                        </div>`);
                        } else {
                            $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span>.
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
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                                </div>`);
                        } else if (payMethod) {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span>.
                                </div>`);
                        } else if (codcoploc) {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                                </div>`);
                        } else {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span>.
                                </div>`);
                        }

                        if (balTime && balTerms && balMethod) {
                            $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            The carrier will pay you $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span> within <span class="font-weight-bold">${balTime}</span> of <span class="font-weight-bold">${balTerms}</span>.
                        </div>`);
                        } else if (balTime && balMethod) {
                            $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            The carrier will pay you $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span> within <span class="font-weight-bold">${balTime}</span>.
                        </div>`);
                        } else {
                            $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            The carrier will pay you $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span>.
                        </div>`);
                        }
                    }
                } else {
                    $("#balAmount").val(pay);
                    $("#alertMSG").html('');
                    $("#copcodPart").hide();

                    if (balTime && balTerms && balMethod) {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span> within <span class="font-weight-bold">${balTime}</span> of <span class="font-weight-bold">${balTerms}</span>.
                        </div>`);
                    } else if (balTime && balMethod) {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span> within <span class="font-weight-bold">${balTime}</span>.
                        </div>`);
                    } else if (balMethod) {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span>.
                        </div>`);
                    } else {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span>.
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
                                The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                            </div>`);
                    } else if (payMethod) {
                        $("#alertMSG").html('');
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span>.
                            </div>`);
                    } else if (codcoploc) {
                        $("#alertMSG").html('');
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                The carrier will receive $<span class="font-weight-bold">${copcod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                            </div>`);
                    } else {
                        $("#alertMSG").html('');
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                The carrier will receive $<span class="font-weight-bold">${copcod}</span>.
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
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                                </div>`);
                        } else if (payMethod) {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span>.
                                </div>`);
                        } else if (codcoploc) {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                                </div>`);
                        } else {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span>.
                                </div>`);
                        }
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span>.
                        </div>`);
                    } else if (pay > copcod) {
                        $("#alertMSG").html('');
                        if (payMethod && codcoploc) {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                                </div>`);
                        } else if (payMethod) {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span>.
                                </div>`);
                        } else if (codcoploc) {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                                </div>`);
                        } else {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span>.
                                </div>`);
                        }
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span>.
                        </div>`);
                    }
                    if (bal < 0) {
                        $("#balAmount").val(Math.abs(bal));

                        $("#copcodPart").hide();
                        $("#copcodPart").show();

                        $("#alertMSG").html('');
                        if (payMethod && codcoploc) {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                                </div>`);
                        } else if (payMethod) {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span>.
                                </div>`);
                        } else if (codcoploc) {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                                </div>`);
                        } else {
                            $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                    The carrier will receive $<span class="font-weight-bold">${copcod}</span>.
                                </div>`);
                        }
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            The carrier will pay you $<span class="font-weight-bold">${Math.abs(bal)}</span>.
                        </div>`);
                    }
                } else {
                    $("#balAmount").val(pay);
                    $("#alertMSG").html('');
                    $("#copcodPart").hide();

                    if (balTime && balTerms && balMethod) {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span> within <span class="font-weight-bold">${balTime}</span> of <span class="font-weight-bold">${balTerms}</span>.
                        </div>`);
                    } else if (balTime && balMethod) {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span> within <span class="font-weight-bold">${balTime}</span>.
                        </div>`);
                    } else if (balMethod) {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span> via <span class="font-weight-bold">${balMethod}</span>.
                        </div>`);
                    } else {
                        $("#alertMSG").append(`<div class="alert alert-danger" role="alert">
                            You will pay the carrier $<span class="font-weight-bold">${Math.abs(bal)}</span>.
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
                                The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                            </div>`);
                    } else if (payMethod) {
                        $("#alertMSG").html('');
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                The carrier will receive $<span class="font-weight-bold">${copcod}</span> via <span class="font-weight-bold">${payMethod}</span>.
                            </div>`);
                    } else if (codcoploc) {
                        $("#alertMSG").html('');
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                The carrier will receive $<span class="font-weight-bold">${copcod}</span> at <span class="font-weight-bold">${codcoploc}</span>.
                            </div>`);
                    } else {
                        $("#alertMSG").html('');
                        $("#alertMSG").html(`<div class="alert alert-danger" role="alert">
                                The carrier will receive $<span class="font-weight-bold">${copcod}</span>.
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
                <label class="label font-weight-bold tx-black">Deposit Amount <span class="tx-danger">*</span></label>
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
            var id = $(this).val();

            if (id == 1 || id == 8 || id == 9 || id == 10) {
                $(".dauc").html('');
            } else {
                $(".dauc").html('');

                if (id == 7 || id == 6) {
                    $(".dauc").html(`
                     <div class="col-lg-6">
                        <div class="form-group">
                            <label id="dauction" class="label font-weight-bold tx-black"></label>
                            <input  class="form-control this_save " autocomplete="nope" type="text" name="dauction" id="dacution_name" value="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label id="dauctionpho" class="label font-weight-bold tx-black"></label>
                            <input  class="form-control this_save  ophone" autocomplete="nope" type="text" name="dauctionpho" id="dacutionphoNo" value="">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="label font-weight-bold tx-black">Terminal</label>
                            <input class="form-control this_save " autocomplete="nope" type="text" name="port_terminal" id="port_terminal" value="">
                        </div>
                    </div>

                `);
                } else {
                    $(".dauc").html(`
                     <div class="col-lg-6">
                        <div class="form-group">
                            <label id="dauction" class="label font-weight-bold tx-black"></label>
                            <input required class="form-control this_save " autocomplete="nope" type="text" name="dauction" id="dacution_name" value="" >
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label id="dauctionpho" class="label font-weight-bold tx-black"></label>
                            <input required class="form-control this_save  ophone" autocomplete="nope" type="text" name="dauctionpho" id="dacutionphoNo" value="" >
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
        });
        var dterminal = $("#dterminal").val();

        if (dterminal == 2 || dterminal == 3 || dterminal == 4) {
            if (res.auction_type == 1) {
                $("#dacution_name").val("COPART Auto Auction");
            } else if (res.auction_type == 2) {
                $("#dacution_name").val("IAAI Auto Auction");
            } else if (res.auction_type == 3) {
                $("#dacution_name").val("Manhein Auto Auction");
            }
            $("#dacutionphoNo").val(res.phone);
            $("#daddress").val(res.address);
        }


        $("#oterminal").change(function () {
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
        var dterminal = $("#oterminal").val();

        if (dterminal == 2 || dterminal == 3 || dterminal == 4) {
            if (res.auction_type == 1) {
                $("#oacution_name").val("COPART Auto Auction");
            } else if (res.auction_type == 2) {
                $("#oacution_name").val("IAAI Auto Auction");
            } else if (res.auction_type == 3) {
                $("#oacution_name").val("Manhein Auto Auction");
            }
            $("#oacutionphoNo").val(res.phone);
            $("#oaddress").val(res.address);
        }
        $("#oterminal").change(function () {
            var id = $(this).val();
            if (id == 1) {
                $(".oauc").html('');
            } else {
                $(".oauc").html('');
                $(".oauc").html(`
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label id="oacution" class="label font-weight-bold tx-black"></label>
                            <input class="form-control this_save" autocomplete="nope"  type="text" name="oacution" id="oacution_name" value="">
                        </div>
                    </div>

                     </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label id="oacutionpho" class="label font-weight-bold tx-black"></label>
                            <input class="form-control this_save ophone" autocomplete="nope" type="text"  name="oacutionpho" id="oacutionphoNo" value="">
                        </div>
                    </div>

                     <div class="col-sm-12">
                            <div class="form-group">
                            <label class="form-label">Buyer/Lot/Stock Number</label>
                            <input type="text" name="obuyer_no" id="obuyer_no" class="form-control this_save "
                            placeholder="Buyer/Lot/Stock Number">
                            </div>
                     </div>
                    `);
                if (id == 5) {
                    $("#oacution").html('Shop Name');
                    $("#oacutionpho").html('Shop Phone');
                } else if (id == 8 || id == 7 || id == 6) {
                    $(".oauc").html(``);
                    $(".buyer_number").hide();

                    $(".stock_number").html(`
                       <div class="">
                            <div class="form-group">
                            <label class="form-label">Buyer/Lot/Stock Number</label>
                            <input type="text" name="obuyer_no" id="obuyer_no" class="form-control this_save "
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


        });


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

            alert(no);
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

    <script type="text/javascript">

        var Ophone_count = 0;
        var Dphone_count = 0;
        var vehicle_count = 2;


        $(".add_phone_btn").click(function () {
            $(".add_phone").append('<div class="col-12 add margin_lft"><div class="row"><div class="col-11"><label class="form-label">Phone Number: </label><input  type="text" name="ophone[]"  class="form-control this_save ophone" id="ophonee' + Ophone_count + '" placeholder="Phone Number"/></div><div class"form-group col-1" style="padding-top: 23px;"> <i id="remove_btn" class="fa fa-minus-circle remove_btn"></i></div> </div></div>	&nbsp;');
            ++Ophone_count;

        });

        $(document).on('click', '.remove_btn', function () {
            $(this).parents('.add').remove();
            --Ophone_count;
        });

        $(".add_dphone_btn").click(function () {
            $(".add_dphone").append('<div class="col-12 add margin_lft"><div class="row"><div class="col-11"><label class="form-label">Phone Number:</label><input  type="text" name="dphone[]"  class="form-control this_save dphone " id="phonee' + Dphone_count + '" placeholder="Phone Number"/></div><div class"form-group col-1" style="padding-top: 23px;"> <i id="remove_btn" class="fa fa-minus-circle remove_btn"></i></div> </div></div>	&nbsp;');
            ++Dphone_count;
        });

        $(document).on('click', '.remove_btn', function () {
            $(this).parents('.add').remove();
            --Dphone_count;
        });


        $(`.add_vehicle_btn`).click(function () {
            $(`.add_vehicle_information`).append(`<input type="hidden" name="count[]" value="1"><div class="vehicle_add"> <div class="row "> <div class="col-5"> <div class="form-group"> <label class="rdiobox"> <input class="this_save" name="vehicle${vehicle_count}" id="vehicle${vehicle_count}" onclick="vehicle_append(${vehicle_count})" type="radio" checked="" value="1" data-parsley-multiple="vehicle${vehicle_count}" > <input name="vehicle_v[]"  id="vehicle_v${vehicle_count}" type="hidden" value="make"> <span>Year, Make, and Model</span> </label> </div> </div> <div class="col-5 "> <div class="form-group"> <label class="rdiobox"> <input class="this_save"  name="vehicle${vehicle_count}" id="vin${vehicle_count}" onclick="vin_append(${vehicle_count})" type="radio" value="2" data-parsley-multiple="vehicle${vehicle_count}" ><input name="vehicle_v[]" disabled  id="vehicle_v_vin${vehicle_count}" type="hidden" value="vin"> <span>Vin Number</span> </label> </div> </div> <div class="col-2 btn-list"> <button style="margin-left: 19%;" type="button" class="btn btn-danger btn_remove"> <i class="mr-2" id="add-more" onclick="vehicleUpdate(1)"></i>Remove</button> </div> </div> <div class="row"> <div class="col-sm-12 col-md-12 vin_toggle${vehicle_count}"> </div><div class="col-sm-6 col-md-6"> <div class="form-group"> <label class="form-label">Year*</label> <input type="text"  class="form-control this_save" id="year${vehicle_count}" name="vyear[]" placeholder="Enter Year" required> </div></div><div class="col-sm-6 col-md-6"><div class="form-group"> <label class="form-label">Make*</label> <input type="text"  class="form-control this_save  makeOpt0" id="makeOpt${vehicle_count}" onkeyup="getmake()" name="vmake[]" placeholder="Enter Make" required></div> </div><div class="col-sm-6 col-md-6"><div class="googleimage" onclick="googl(${vehicle_count})" id="googleimage${vehicle_count}"  style="position: absolute; right: 6%;display:none"><a href="javascript:void(0);"><img width="24" src="assets/images/png/google.png"></a></div><div class="form-group"> <label class="form-label">Model*</label> <input type="text"  id="model${vehicle_count}"  onkeyup="getmodel()" name="vmodel[]"  class="form-control this_save  model0" placeholder="Enter Model" required></div></div> <div class="col-sm-6 col-md-6"><div class="form-group"> <label class="form-label">Vehicle Type*</label> <select required id="vehType${vehicle_count}"  onfocus="my_func(${vehicle_count})" name="vehType[]" class="form-control this_save select2"> <option selected="" value="">Select Type</option> <option value="Car">Car</option> <option disabled="">————————————</option> <option value="motorcycle">Motorcycle</option> <option value="3_wheel_sidecar">3 Wheel Sidecar</option> <option value="3_wheel_motorcycle">3 Wheel Motorcycle</option> <option value="atv">ATV</option> <option disabled="">————————————</option> <option value="SUV">SUV</option> <option value="Mid SUV">Mid SUV</option> <option value="Large SUV">Large SUV</option> <option disabled="">————————————</option> <option value="Van">Van</option> <option value="Mini Van">Mini Van</option> <option value="Cargo Van">Cargo Van</option> <option value="Passenger Van">Passenger Van</option> <option disabled="">————————————</option> <option value="Pickup">Pickup</option> <option value="Pickup Dually">Pickup Dually</option> <option value="Box Truck Dually">Box Truck Dually</option> <option disabled="">————————————</option> <option value="other_vehicle">Other Vehicle</option> <option value="other_motorcycle">Other Motorcycle</option> </select> </div> </div><div class="col-sm-6 col-md-6"> <div class="form-group"> <label class="form-label">Vehicle Condition *</label> <select required="" id="condition${vehicle_count}" name="condition[]" class="form-control this_save select2"> <option selected="" value="">Select</option> <option value="1">Running</option> <option value="2">Not Running</option> </select> </div> </div> <div class="col-sm-6 col-md-6"> <div class="form-group"> <label class="form-label">Trailer Type *</label> <select required id="trailter_type${vehicle_count}" name="trailter_type[]" class="form-control this_save select2"> <option selected="" value="">Select</option> <option value="1">Open</option> <option value="2">Enclosed</option> </select> </div> </div> <div class="col-sm-6 col-md-6"> &nbsp; <div class="form-group"> <label class="ckbox"> <input type="checkbox" name="portTitle${vehicle_count}" id="needTitle${vehicle_count}" onclick="goto_port(${vehicle_count})" class="this_save"> <input type="hidden" id="portTitlehidden${vehicle_count}" name="portTitlehidden[]" value="false"><span>&nbsp;Need Title?</span> </label> </div> </div>  </div> </div>  </div></div> &nbsp;`);
            vehicle_count++;
            selectRefresh();
        });

        $("#central_chk1").click(function () {

            $("#may_be_book").html('');
            $("#confirm_book").html(`
                <div class="col-lg-6">
                    <input class="form-control this_save" autocomplete="nope" type="text" name="company_name" id="company_name" placeholder="Company Name">
                    </div> <div class="col-lg-6">
                    <input class="form-control this_save" autocomplete="nope" type="text" name="company_price" id="company_price" placeholder="Price">
                </div> `);
        });

        $("#central_chk2").click(function () {
            $("#confirm_book").html('');
            $("#may_be_book").html(`
                    <div class="col-lg-4" style="padding-left: 15px;padding-right: 0px;">
                        <input class="form-control this_save" autocomplete="nope" type="text" name="company_name" id="company_name2" placeholder="Company Name">
                        </div> <div class="col-lg-2" style="padding-left: 5px;padding-right: 0px;">
                        <input class="form-control this_save" autocomplete="nope" type="text" name="company_price" id="company_price2" placeholder="Price">
                        </div> <div class="col-lg-6" style="padding-left: 5px;">
                        <input class="form-control this_save" autocomplete="nope" type="text" name="company_comments" id="company_comments" placeholder="Comments">
                    </div> `);
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


    </script>



    <script>


        $("body").delegate(".ophone", "focus", function () {
            $(".ophone").mask("(999) 999-9999");
            $(".ophone")[0].setSelectionRange(0,0);
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

        function getmodel() {

            $(".model0").autocomplete({
                source: "getmodel"
            });
        }

        function get_vin(num) {
            var vinno = $(`#vinNum${num}`).val();
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

                        if (res[0].value && res[1].value && res[0].value) {
                            $("#vingoogleimage" + num).show();
                        }
                    }
                }

            });

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

            $("body").delegate(".this_save", "keyup change", function () {

                var datastring = $("#form").serialize();

                $.ajax({

                    type: "post",
                    url: "/auto_save_order",
                    data: datastring,
                    dataType: "json",

                    success: function (data) {

                    },
                    error: function (e) {

                    }

                });


            });

            $(".ophone").mask("(999) 999-9999");


            $(function () {
                $("#o_zip1").autocomplete({
                    source: "get_zip"
                });
            });

            $(function () {
                $("#d_zip1").autocomplete({
                    source: "get_zip"
                });
            });

            $("#form").on('submit', (function (e) {
                e.preventDefault();
                $.ajax({
                    url: "/store_new_quote",
                    type: "POST",
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
                            window.location.href = "/add_new";
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

    </script>

@endsection

