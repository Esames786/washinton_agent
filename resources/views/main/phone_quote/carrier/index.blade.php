@extends('layouts.innerpages')

@section('template_title')
    Add Carrier
@endsection
@include('partials.mainsite_pages.return_function')
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha384-..." crossorigin="anonymous">
<style>
    .oauc {
        padding: 11px;
        width: 100%;
    }

    .dauc {
        padding: 11px;
        width: 100%;
    }

    .tableClick th,
    .tableClick td {
        font-size: 13px !important;
    }

    .blocked {
        color: red;
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
        /*font-size: 14px;*/
        color: #009eda!important;
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
        /*width: 178px;*/
        left: 150px;
        bottom: 50px;
        }

    .Terminal-error {
        display: inline-flex;
        column-gap: 6px;
        align-items: baseline;
    }

    label#selectedOptionLabel2 {
        display: block;
    }
</style>

@section('content')

    <div class="page-header">
        <!--<div class="page-leftheader">-->
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layers mr-2 fs-14"></i>Pages</a>-->
        <!--        </li>-->

        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">Add Carrier</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Carrier List</b></h1>
        </div>

    </div>
    <!--End Page header-->
    <!-- Row -->

    <form action="/store_carrier" id="form" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="orderid" value="{{ $orderid }}">

        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title mt-3">
                            <h1>Add Carrier</h1>
                        </div>
                        <h3 class="page-title mb-0 w-85" style="display: flex;flex-direction: row-reverse;">Order Id
                            : {{ $orderid }} </h3>
                    </div>
                    <table class="table table-bordered table-sm" style="width:100%" id="" role="grid"
                        aria-describedby="example1_info">
                        <thead class="table-dark">
                            <tr>
                                <th class="border-bottom-0">Pickup</th>
                                <th class="border-bottom-0">Delivery</th>
                                <th class="border-bottom-0">VEHICLE#/ORDERTAKER<BR></th>
                                {{-- <th class="border-bottom-0">Order Price</th> --}}
                                <th class="border-bottom-0">Customer/Payment</th>
                                <th class="border-bottom-0">Dates</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--                        @foreach ($data as $val) --}}
                            <tr class="parent1">
                                <td>
                                    <input type="hidden" class='order_id' value="{{ $val->id }}">
                                    <input type="hidden" class="pstatus" value="{{ $val->pstatus }}">
                                    <input type="hidden" class="client_email" value="{{ $val->oemail }}">
                                    <input type="hidden" class="client_name" value="{{ $val->oname }}">
                                    <input type="hidden" class="client_phone" value="{{ $val->main_ph }}">
                                    <input type="hidden" class="location1" value="{{ $val->originzsc }}">
                                    <input type="hidden" class="location2" value="{{ $val->destinationzsc }}">
                                    <input type="hidden" class="origincity" value="{{ $val->origincity }}">
                                    <input type="hidden" class="destinationcity" value="{{ $val->destinationcity }}">
                                    <a href="http://classic.mapquest.com/embed?zoom=5&amp;q={{ $val->origincity }}+{{ $val->originstate }}+{{ $val->originzip }}"
                                        target="_blank">
                                        <i class="fa fa-map-marker" style="color:green;" aria-hidden="true"></i>
                                        {{ $val->origincity . '-' . $val->originstate . '-' . $val->originzip }}
                                    </a><br>
                                    @if (!empty($val->oaddress))
                                        <strong><i class="fa fa-location-arrow" style="color:green;" aria-hidden="true"></i>
                                            <a class="btn btn-outline-success btn-sm" data-placement="bottom"
                                                title="{{ $val->oaddress }}">
                                                {{ str_limit($val->oaddress, 20, $end = '.......') }}
                                            </a>
                                        </strong>
                                    @endif
                                </td>
                                <td>
                                    <a href="http://classic.mapquest.com/embed?zoom=5&amp;q={{ $val->destinationcity }}+{{ $val->destinationstate }}+{{ $val->destinationzip }}"
                                        target="_blank">
                                        <i class="fa fa-map-marker" style="color:red;" aria-hidden="true"></i>
                                        {{ $val->destinationcity . '-' . $val->destinationstate . '-' . $val->destinationzip }}
                                    </a><br>
                                    @if ($val->daddress)
                                        <strong><i class="fa fa-location-arrow" style="color:red;" aria-hidden="true"></i>
                                            <a class="btn btn-outline-danger btn-sm" data-placement="bottom"
                                                title="{{ $val->daddress }}">
                                                {{ str_limit($val->daddress, 20, $end = '.......') }}
                                            </a>
                                        </strong>
                                    @endif
                                </td>
                                <?php $ymk = explode('*^-', $val->ymk); ?>
                                <td>
                                    @foreach ($ymk as $val2)
                                        @if ($val2)
                                            {{ $val2 }} <br>
                                        @endif
                                    @endforeach
                                    Order ID# <?php echo $val->id; ?><br>
                                    By:<?php echo get_user_name($val->order_taker_id); ?><br>
                                    <?php echo get_car_or_heavy($val->car_type); ?>
                                </td>
                                <td>
                                    @if (auth()->user()->role == 1 || auth()->user()->role == 2)
                                        Driver-Price: @if (!empty($val->driver_price))
                                            {{ $val->driver_price }}
                                        @else
                                            N/A
                                        @endif |
                                        @php
                                            if (\Request::is('new') || \Request::is('followup') || \Request::is('interested') || \Request::is('asking_low') || \Request::is('not_interested') || \Request::is('not_responding')) {
                                                $title = 'Offer-Price';
                                            } else {
                                                $title = 'Book-Price';
                                            }
                                        @endphp
                                        {{ $title }}: @if (!empty($val->payment))
                                            {{ $val->payment }}
                                        @else
                                            N/A
                                        @endif |
                                    @endif
                                    Listed-Price: @if (!empty($val->listed_price))
                                        {{ $val->listed_price }}
                                    @else
                                        N/A
                                    @endif
                                    @if (auth()->user()->role == 1 || auth()->user()->role == 2)
                                        @if (!empty($val->asking_low) && $val->asking_low > 0)
                                            <br>
                                            <span
                                                class="badge badge-pill  badge-sm">Ask.Low:{{ intval($val->asking_low) }}</span>
                                        @endif
                                        <br><span class="badge badge-sm">Payment: <?php echo pay_status($val->paid_status); ?></span>
                                        <br><span class="badge mt-2 badge-sm">Name: <?php echo $val->oname; ?></span>
                                        <br>
                                        <?php $ophone = explode('*^', $val->ophone); ?>
                                        @foreach ($ophone as $val3)
                                            @php
                                                $new = '(xxx) xxx-' . substr($val3, -4);
                                            @endphp
                                            @if ($val3)
                                                <span class="text-center pd-2 bd-l">
                                                    <a onclick="window.location.href = 'rcmobile://sms?number={{ $val3 }}'"
                                                        class="btn btn-outline-info fa fa-phone mobile count_user mb-2"
                                                        style="padding: 3px 5px; font-size: 20px;">{{ $new }}</a><br>
                                                </span>
                                                <span class="text-center pd-2 bd-l">
                                                    <a class="btn btn-outline-info fa fa-envelope sms mb-2"
                                                        style="padding: 3px 5px; font-size: 20px;">{{ $new }}</a><br>
                                                </span>
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    Created At: {{ date('M-d-y h:i:s a', strtotime($val->created_at)) }}<br><br>
                                    Updated At: {{ date('M-d-y h:i:s a', strtotime($val->updated_at)) }}<br><br>
                                    <span class="text-center pd-2 bd-l mt-2"><?php echo get_pstatus2($val->pstatus); ?>
                                        @if (!empty($val->old_code))
                                            - Old Quote
                                        @endif
                                    </span>
                                    <br><br>
                                    @if (!empty($val->t_q_late))
                                        <span class="badge badge-pill badge-default txt-white">
                                            {{ $val->t_q_late }} days late time quote
                                        </span>
                                    @endif
                                    <br>
                                    @if ($val->pstatus >= 11)
                                        @if ($val->owes_money == 1)
                                            <span class="badge badge-danger badge-pill  mt-2">Waiting for owes money</span>
                                        @else
                                            <span class="badge badge-success badge-pill tx-white mt-2">Owes money
                                                confirmed<br></span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            {{--                        @endforeach --}}
                        </tbody>
                    </table>
                    <div class="card-body">
                        <div class="card-title font-weight-bold"></div>
                        <div class="row">
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    @if($label[498 - 1]->status == 1)
                                    <div class="Terminal-error">
                                        <label class="form-label">Company Name <span class="text-danger">*</span></label>
                                         <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                        style="cursor: pointer;"></i>
                                    </div>
                                    <div class="popoverContent" style="display: none;">
                                         <div class="popover-title">{{ $label[498-1]->name }}</div>
                                         <div class="popover-content">{{ $label[498-1]->display }}</div>
                                    </div>

                                   @else
                                     <label class="form-label">Company Name <span class="text-danger">*</span></label>
                                   @endif
{{--                                    model0off for now--}}
                                    <input type="text" required name="company_name" class="form-control model00"
                                        placeholder="Enter Company Name">
                                </div>
                            </div>

                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                     @if($label[502-4]->status == 1)
                                    <div class="Terminal-error">
                                    <label class="form-label">Location</label>
                                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                        style="cursor: pointer;"></i>
                                    </div>
                                    <div class="popoverContent" style="display: none;">
                                         <div class="popover-title">{{ $label[502-4]->name }}</div>
                                         <div class="popover-content">{{ $label[502-4]->display }}</div>
                                    </div>

                                    @else
                                     <label class="form-label">Location</label>
                                   @endif
                                    <input type="text" name="location" class="form-control" id="location"
                                        placeholder="Enter Location">
                                </div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                      @if($label[500-1]->status == 1)
                                    <div class="Terminal-error">
                                    <label class="form-label">Email </label>
                                          <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                          style="cursor: pointer;"></i>
                                    </div>
                                    <div class="popoverContent" style="display: none;">
                                         <div class="popover-title">{{ $label[500-1]->name }}</div>
                                         <div class="popover-content">{{ $label[500-1]->display }}</div>
                                    </div>

                                    @else
                                      <label class="form-label">Email </label>
                                   @endif
                                    <input type="email"  name="email" class="form-control" id="email"
                                        placeholder="Email">
                                </div>
                            </div>
                            {{-- <div class="col-sm-3 col-md-3">
                                <div class="form-group">

                                    <label class="form-label">MC# <span class="text-danger">*</span></label>
                                    <input type="text" required name="mc_no" class="form-control" id="mcno"
                                        placeholder="MC#">
                                </div>
                            </div> --}}
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                     @if($label[504-4]->status == 1)
                                    <div class="Terminal-error">
                                    <label class="form-label">MC# </label>
                                       <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                          style="cursor: pointer;"></i>
                                    </div>
                                    <div class="popoverContent" style="display: none;">
                                         <div class="popover-title">{{ $label[504-4]->name }}</div>
                                         <div class="popover-content">{{ $label[504-4]->display }}</div>
                                    </div>

                                    @else
                                       <label class="form-label">MC# </label>
                                   @endif
                                    <input type="text"  name="mc_no" class="form-control" id="mcno" placeholder="MC#">
                                    <span id="mcno-error" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                     @if($label[505-4]->status == 1)
                                    <div class="Terminal-error">
                                    <label class="form-label">Zip Code</label>
                                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                          style="cursor: pointer;"></i>
                                    </div>
                                    <div class="popoverContent" style="display: none;">
                                         <div class="popover-title">{{ $label[505-4]->name }}</div>
                                         <div class="popover-content">{{ $label[505-4]->display }}</div>
                                    </div>

                                    @else
                                        <label class="form-label">Zip Code</label>
                                   @endif
                                    <input type="text" name="zip_code" id="zip_code" class="form-control"
                                        placeholder="Zip Code" />
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                      @if($label[506-4]->status == 1)
                                    <div class="Terminal-error">
                                    <label class="form-label">Address</label>
                                    <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                          style="cursor: pointer;"></i>
                                    </div>
                                    <div class="popoverContent" style="display: none;">
                                         <div class="popover-title">{{ $label[506-4]->name }}</div>
                                         <div class="popover-content">{{ $label[506-4]->display }}</div>
                                    </div>

                                    @else
                                        <label class="form-label">Zip Code</label>
                                   @endif
                                    <input type="text" name="address" id="address" class="form-control"
                                        placeholder="Address" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                      @if($label[507-4]->status == 1)
                                    <div class="Terminal-error">
                                    <label class="form-label">Company Phone# (999) 999-9999 <span
                                            class="text-danger">*</span></label>
                                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                          style="cursor: pointer;"></i>
                                    </div>
                                    <div class="popoverContent" style="display: none;">
                                         <div class="popover-title">{{ $label[507-4]->name }}</div>
                                         <div class="popover-content">{{ $label[507-4]->display }}</div>
                                    </div>

                                    @else
                                        <label class="form-label">Company Phone# (999) 999-9999 <span
                                            class="text-danger">*</span></label>
                                   @endif
                                    <input type="text" required name="companyphone" id="companyphone"
                                        class="form-control ophone" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    @if($label[508-4]->status == 1)
                                    <div class="Terminal-error">
                                    <label class="form-label">Driver Phone# (999) 999-9999</label>
                                      <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                          style="cursor: pointer;"></i>
                                    </div>
                                    <div class="popoverContent" style="display: none;">
                                         <div class="popover-title">{{ $label[508-4]->name }}</div>
                                         <div class="popover-content">{{ $label[508-4]->display }}</div>
                                    </div>

                                    @else
                                    <label class="form-label">Driver Phone# (999) 999-9999</label>
                                   @endif
                                    <input type="text" name="driverphone" id="driverphone"
                                        class="form-control ophone" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                     @if($label[509-4]->status == 1)
                                    <div class="Terminal-error">
                                    <label class="form-label">Est. Pickup <span class="text-danger">*</span></label>
                                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                          style="cursor: pointer;"></i>
                                    </div>
                                    <div class="popoverContent" style="display: none;">
                                         <div class="popover-title">{{ $label[509-4]->name }}</div>
                                         <div class="popover-content">{{ $label[509-4]->display }}</div>
                                    </div>

                                    @else
                                   <label class="form-label">Est. Pickup <span class="text-danger">*</span></label>
                                   @endif
                                    <input type="date" required name="pickupdate" id="pickupdate"
                                        class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    @if($label[510-4]->status == 1)
                                    <div class="Terminal-error">
                                    <label class="form-label">Est. Deliveryp <span class="text-danger">*</span></label>
                                    <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                          style="cursor: pointer;"></i>
                                    </div>
                                    <div class="popoverContent" style="display: none;">
                                         <div class="popover-title">{{ $label[510-4]->name }}</div>
                                         <div class="popover-content">{{ $label[510-4]->display }}</div>
                                    </div>

                                    @else
                                    <label class="form-label">Est. Deliveryp <span class="text-danger">*</span></label>
                                   @endif
                                    <input type="date" required name="deliverydate" id="deliverydate"
                                        class="form-control" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    @if($label[511-4]->status == 1)
                                    <div class="Terminal-error">
                                    <label class="form-label">Ask Price <span class="text-danger">*</span></label>
                                    <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                                          style="cursor: pointer;"></i>
                                    </div>
                                    <div class="popoverContent" style="display: none;">
                                         <div class="popover-title">{{ $label[511-4]->name }}</div>
                                         <div class="popover-content">{{ $label[511-4]->display }}</div>
                                    </div>

                                    @else
                                    <label class="form-label">Ask Price <span class="text-danger">*</span></label>
                                   @endif
                                    <input type="number" required name="askprice" id="askprice"
                                        class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    </br>
                                    <input type="checkbox" name="askinsurance" id="askinsurance" class=""
                                        value="1" />
                                    Ask for insurance
                                    </br>
                                    <input type="checkbox" name="negative" id="negative" class=""
                                        value="1" />
                                    Negative

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="" id="negativeno">

                                    <!--  <input type="text" required name="negativenovalue" id="negativenovalue" class="" /> -->
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Comments</label>
                                    <textarea name="comments" class="form-control" id="comments" placeholder="Enter Comments"></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button id="sv_btn" type="submit" class="btn  btn-primary w-35">SAVE</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Row-->
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

@endsection

@section('extraScript')
    <script>

                      //=================onchange-values=============================
        $(document).ready(function() {
            // Select all error icons within the document
            var $errorIcons = $('.Terminal-error i');
            var $openPopoverContent = null;

            // Iterate over each error icon
            $errorIcons.each(function() {
                var $errorIcon = $(this);
                var $popoverContent = $errorIcon.closest('.Terminal-error').siblings('.popoverContent');

                // Toggle the popover on icon click
                $errorIcon.on('click', function(event) {
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
            $(document).on('click', function(event) {
                if ($openPopoverContent && !$errorIcons.is(event.target) && !$openPopoverContent.is(event.target) && $openPopoverContent
                    .has(event.target).length === 0) {
                    $openPopoverContent.hide();
                    $openPopoverContent = null;
                }
            });
        });

    //=================onchange-values=============================


        $("body").delegate(".ophone", "focus", function() {
            $(".ophone").mask("(999) 999-9999");
            $(".ophone")[0].setSelectionRange(0, 0);
        });
        $(document).on('click', '#companyphone', function() {
            $("#companyphone").mask("(999) 999-9999");
        });
        $(document).on('click', '#driverphone', function() {
            $("#driverphone").mask("(999) 999-9999");
        });

        $(document).on('click', '#negative', function() {
            if ($('#negative').is(":checked")) {

                $('#negativeno').append(
                    `<input type="text" maxlength="50" style=" width: 100%;height: 100%;margin-left: -14%; " required name="negativenovalue" placeholder="Write Neagative Comments Here..." id="negativenovalue" class="form-control" />`
                );
            } else {

                $('#negativenovalue').remove();
            }
        });


        // $(".model0").autocomplete({
        //     source: "/get_carrier_name"
        // });

        // $(".model0").autocomplete({
        //     source: "/get_carrier_name",
        //     response: function(event, ui) {
        //         ui.content.forEach(function(item) {
        //             // Convert the HTML string to a jQuery object for manipulation
        //             var $html = $("<div>").html(item.label);

        //             // Change the color of the entire line to red if it contains "(Blocked)"
        //             if ($html.find('.blocked').length > 0) {
        //                 $html.css('color', 'red');
        //             }

        //             // Save the modified HTML for rendering
        //             item.html = $html.html();
        //         });
        //     },
        //     select: function(event, ui) {
        //         if ($(ui.item.label).find('.blocked').length > 0) {
        //             // Blocked items are not selectable
        //             return false;
        //         }

        //         // Do something with the selected item
        //         $(".model0").val(ui.item.label);
        //     }
        // }).data("ui-autocomplete")._renderItem = function(ul, item) {
        //     return $("<li>").append(item.html).appendTo(ul);
        // };

        $(".model0").autocomplete({
            source: "/get_carrier_name",
            response: function(event, ui) {
                ui.content.forEach(function(item) {
                    // Convert the HTML string to a jQuery object for manipulation
                    var $html = $("<div>").html(item.label);

                    // Change the color of the entire line to red if it contains "(Blocked)"
                    if ($html.find('.blocked').length > 0) {
                        $html.css('color', 'red');
                    }

                    // Save the modified HTML for rendering
                    item.html = $html.html();
                });
            },
            select: function(event, ui) {
                if ($(ui.item.label).find('.blocked').length > 0) {
                    // Blocked items are not selectable
                    return false;
                }

                // Do something with the selected item
                $(".model0").val(ui.item.label);
            }
        }).data("ui-autocomplete")._renderItem = function(ul, item) {
            return $("<li>").append(item.html).appendTo(ul);
        };

        // Add styles to make the dropdown scrollable
        $(".model0").autocomplete("widget").css({
            "max-height": "200px",
            "overflow-y": "auto",
            "overflow-x": "hidden",
        });

        $(".model0").change(function() {

            var carriername = $('.model0').val();
            $.ajax({
                url: '/get_carrier_detail',
                type: 'get',
                data: {
                    carriername: carriername
                },
                success: function(data) {

                    $('#location').val(data.location);
                    $('#mcno').val(data.mcno);
                    $('#companyphone').val(data.companyphoneno);
                    $('#driverphone').val(data.driverphoneno);
                    $('#pickupdate').val(data.est_pickupdate);
                    $('#deliverydate').val(data.est_deliverydate);
                    $('#askprice').val(data.askprice);
                    $('#comments').val(data.comments);
                    $('#email').val(data.email);
                    if (data.opt_insurance == 1) {
                        $("#askinsurance").prop("checked", true);
                    }
                    if (data.opt_negative == 1) {
                        //$("#negative").prop("checked", true);
                        $('#negative').trigger('click');

                        $('#negativenovalue').val(data.negative_no);


                    }

                }

            });
        });
    </script>

    <script>
        $(document).ready(function() {

            $(document).on('click', '.pagination a', function(event) {

                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                fetch_data3(page);
                $.cookie("page", page, {
                    expires: 1
                });

            });

            function fetch_data3(page) {

                $('#table_data').html('');
                $('#table_data').append(`<div class="lds-hourglass" id='ldss'></div>`);

                $.ajax({

                    url: "/carrier_list?page=" + page,
                    success: function(data) {
                        $('#table_data').html('');
                        $('#table_data').html(data);

                    },
                    complete: function(data) {
                        $('#ldss').hide();
                        regain();
                    }

                })

            }
            let cookie = $.cookie("page");
            if (cookie) {
                fetch_data3(cookie);
                $.removeCookie("page");
            }

        });
    </script>

    {{-- <script>
        $(document).ready(function () {
            // Attach keyup event handler to the input field
            $('#mcno').on('keyup', function () {
                // Get the input value
                var mcNoValue = $(this).val();

                console.log('mcNoValue', mcNoValue);

                // Make a GET AJAX request
                $.ajax({
                    url: '{{ route("check.mcno.status") }}',
                    method: 'GET',
                    data: {mc_no: mcNoValue},
                    success: function (data) {
                        // Assuming data.status and data.mcno are properties of the response
                        if (data.status === 0) {
                            // Append "blocked" to the value if status is 0
                            $('#mcno').val(data.mcno + ' blocked');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX request failed:', error);
                        // Handle the error if needed
                    }
                });
            });
        });
    </script> --}}
    <script>
        $(document).ready(function () {
            // Attach keyup event handler to the input field
            $('#mcno').on('keyup', function () {
                // Get the input value
                var mcNoValue = $(this).val();

                // console.log('mcNoValue', mcNoValue);

                // Make a GET AJAX request
                $.ajax({
                    url: '{{ route("check.mcno.status") }}',
                    method: 'GET',
                    data: {mc_no: mcNoValue},
                    success: function (data) {
                        // Assuming data.status and data.mcno are properties of the response
                        if (data.status === 0) {
                            // Show an error message
                            $('#mcno-error').text('This MC# is blocked');
                            // Clear the input field if status is 0
                            $('#mcno').val('');
                        } else {
                            // Clear the error message if not blocked
                            $('#mcno-error').text('');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX request failed:', error);
                        // Handle the error if needed
                    }
                });
            });

            // Attach a submit event handler to the form
            $('form').on('submit', function (event) {
                // Check if there is an error message for MC#
                if ($('#mcno-error').text() !== '') {
                    // Prevent form submission
                    event.preventDefault();
                    // You can add additional handling or show an alert/message to the user
                    alert('Please fix the error before submitting the form.');
                }
            });
        });
    </script>
@endsection
