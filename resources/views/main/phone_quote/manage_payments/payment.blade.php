@extends('layouts.innerpages')

@section('template_title')
    Payment
@endsection
@include('partials.mainsite_pages.return_function')
<style>
    .oauc {
        padding: 11px;
        width: 100%;
    }

    .dauc {
        padding: 11px;
        width: 100%;
    }

    .card-header.bg-primary.text-white {
        justify-content: center;
    }
</style>

@section('content')

    <div class="page-header">
        <div class="page-leftheader">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fe fe-layers mr-2 fs-14"></i>Pages</a>
                </li>

                <li class="breadcrumb-item active" aria-current="page"><a href="#">Payment</a></li>
            </ol>
        </div>

    </div>
    <!--End Page header-->
    <!-- Row -->

    <form action="/store_profit" id="form" method="POST">

        <input type="hidden" name="_token" value="{{ csrf_token()}}">
        <input type="hidden" name="orderid" value="{{ $data->id }}">


        <div class="history_content ">
            <div class="row">
                <div class="card">

                    <div class="card-body" style=" padding: 4px 4px; ">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-primary text-white">
                                        <h4>CUSTOMER INFORMATION</h4>
                                    </div>
                                    @php
                                        $ophones=explode('*^',$data->ophone);
                                        $dphones=explode('*^',$data->dphone);
                                    @endphp
                                    <div class="card-body" style=" overflow: scroll; ">
                                        <table class="table table-bordered ">
                                            <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone-Number</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>{{$data->oname}}</td>
                                                <td>{{$data->oemail}}</td>
                                                <td> @foreach($ophones as $ophone)
                                                        {{$ophone}}
                                                    @endforeach</td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header  bg-primary text-white">
                                        <h4>ORIGIN & DEST. INFORMATION</h4>
                                    </div>

                                    <div class="card-body" style=" overflow: scroll; ">
                                        <table class="table table-bordered ">
                                            <thead>
                                            <tr>
                                                <th>Pickup Phone</th>
                                                <th>Delivery Phone</th>
                                                <th>Pickup Address</th>
                                                <th>Delivery Address</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>
                                                    @foreach($ophones as $ophone)

                                                        {{$ophone}}

                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach($dphones as $dphone)

                                                        {{$dphone}}

                                                    @endforeach
                                                </td>
                                                <td>{{$data->oaddress}}</td>
                                                <td>{{$data->daddress}}</td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                            </div>

                            <?php
                            $condition1 = explode('^*', $data->condition);
                            ?>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-primary text-white">
                                        <h4>ORDER DETAILS</h4>

                                    </div>
                                    <div class="card-body" style=" overflow: scroll; ">
                                        <table class="table table-bordered ">
                                            <thead>
                                            <tr>
                                                <th>Vehicle Name</th>
                                                <th>Condition</th>
                                                <th>Trailer Type</th>
                                                <th>Carrier</th>

                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <?php
                                                    $vehiclename = explode('*^', $data->ymk);
                                                    foreach ($vehiclename as $vehicleymk) {
                                                        echo " ($vehicleymk) ";
                                                    }
                                                    ?>
                                                    <span>{{$data->vin_num}}</span>
                                                </td>
                                                <td> @foreach($condition1 as $val2)
                                                        {{ "(".get_condtion($val2)."),"}}
                                                    @endforeach</td>

                                                <td>
                                                    {{$data->origincity}}
                                                </td>
                                                <td>
                                                    {{get_carrier($data->id,'companyname')}}
                                                </td>

                                            </tr>

                                            </tbody>
                                        </table>

                                    </div>

                                </div>

                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-primary text-white">
                                        <h3>Payment Details</h3>
                                    </div>
                                    <div class="card-body" style=" overflow: scroll; ">
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>Payment</th>
                                                <th>Pay Carrier</th>
                                                <th>Listed Price</th>

                                                <th>Enter Profit</th>
                                                <th>Action</th>

                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>{{$data->payment}}<br>
                                                    <span class="badge badge-pill badge-default mt-2">Payment: <?php echo pay_status($data->paid_status)?></span>
                                                </td>
                                                <td>{{$data->pay_carrier}}</td>
                                                <td>{{$data->listed_price}}</td>

                                                <td><input required type="text" class="form-control"
                                                           value="@if(!empty($profit)) {{$profit->profit}} @endif"
                                                           name="profit"/>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    Detail
                                                    <textarea class="form-control" required
                                                              name="detail" cols="70"
                                                              rows="4">@if(!empty($profit)) {{$profit->detail}} @endif</textarea>
                                                </td>
                                                <td>
                                                    <button type="submit" class="btn btn-primary pd-x-20">Submit
                                                    </button>
                                                </td>
                                            </tr>
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>



@endsection

@section('extraScript')

    <script>
        $("body").delegate(".ophone", "focus", function () {
            $(".ophone").mask("(999) 999-9999");
            $(".ophone")[0].setSelectionRange(0, 0);
        });
        $(document).on('click', '#companyphone', function () {
            $("#companyphone").mask("(999) 999-9999");
        });
        $(document).on('click', '#driverphone', function () {
            $("#driverphone").mask("(999) 999-9999");
        });

        $(document).on('click', '#negative', function () {
            if ($('#negative').is(":checked")) {
                $('#negativeno').append(`<input type="text" required name="negativenovalue" id="negativenovalue" class="" />`);
            }
            else {

                $('#negativenovalue').remove();
            }
        });


        $(".model0").autocomplete({
            source: "/get_carrier_name"
        });

        $(".model0").change(function () {

            var carriername = $('.model0').val();
            $.ajax({
                url: '/get_carrier_detail',
                type: 'get',
                data: {carriername: carriername},
                success: function (data) {

                    $('#location').val(data.location);
                    $('#mcno').val(data.mcno);
                    $('#companyphone').val(data.companyphoneno);
                    $('#driverphone').val(data.driverphoneno);
                    $('#pickupdate').val(data.est_pickupdate);
                    $('#deliverydate').val(data.est_deliverydate);
                    $('#askprice').val(data.askprice);
                    $('#comments').val(data.comments);
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

@endsection

