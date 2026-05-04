@extends('layouts.print_layout')

@section('template_title')
    Print Invoice Roro
@endsection
@include('partials.mainsite_pages.return_function')

<style>
.page{
    background: #fff !important;
}
.d-flex p:first-child{
    padding-left:6rem;    
    font-size: 1.4rem;
    font-weight: 500;
}
.d-flex p:last-child{
    padding-right:3rem;
    font-size: 1.4rem;
}
.bg-successssss{
    background-color:#000000 !important;
}
/*.bg-successssss*/
/*{*/
/*    background:linear-gradient(23deg, #179dd9 50%, #8cc643 100%) !important;*/
/*}*/
.hr{
    --box-border--border: linear-gradient(23deg, #179dd9 50%, #8cc643 100%) !important;
}
.infotbl th,.infotbl td{
    text-align:center !important;
    vertical-align:middle !important;
    text-transform:capitalize;
}
.pricetbl td:last-child{
    text-align:right !important;
}
.pricetbl th{
    font-size: 1.4rem !important;
}
.pricetbl th:first-child,.pricetbl td:first-child{
    padding-left: 25px !important;
}
.pricetbl th:last-child{
    text-align:center !important;
}

.pricetbl td:first-child{
    padding-left: 25px !important;
}

.infotbl{
    border:2px solid #000 !important;
}
.infotbl th
{
    border-bottom: 2px solid #000 !important;
    border-right: 2px solid #000 !important;
}
.infotbl td
{
    border-right: 2px solid #000 !important;
}
.infotbl th:last-child,.infotbl td:last-child{
    border-right:0 !important;
}
.pricetbl th{
    border: 2px solid #000 !important;
}
.pricetbl th:last-child{
    border-left: 0 !important;
}
.pricetbl tr:first-child td{
    border-bottom: 2px solid #000 !important;
    border-right: 2px solid #000 !important;
}
.pricetbl tr:first-child td:first-child{
    border-left: 2px solid #000 !important;
}
.pricetbl tr:last-child td:last-child{
    border-left: 2px solid #000 !important;
    border-right: 2px solid #000 !important;
}
</style>

@section('content')

    <div class="container">
        <button type="button" class="btn btn-primary float-right my-3" id="btnConvert"><i class="fa fa-download" aria-hidden="true"></i></button> 
    </div>
    <div id="invoice">
        <div class="container py-5 px-0 my-5" style="max-width:80% !important;box-shadow: 0px 2px 8px rgba(7, 7, 7, 0.5);">
        <div class="row">
            <div class="col-sm-12">
                <div class="d-flex justify-content-between position-relative">
                    <div class="bg-successssss">
                        <h1 class="text-light text-right py-3 m-auto px-5">RORO SHIPPING SERVICES</h1>
                    </div>
                    <div class="text-right" style="margin-right: 55px;margin-top: 15px;">
                        <h5 class="m-0">INVOICE #</h5>
                        <span style="font-size:1.2rem;">{{$data->id}}</span>
                    </div>
                    <div class="bg-successssss" style="position: absolute;right: 0;padding: 18px;top: 15px;"></div>
                </div>
                <br>
                <div class="row">
                    <div style="box-shadow: 0px 6px 8px rgba(7, 7, 7, 0.6);padding: 30px 20px 20px;text-align: left;margin-left: 3rem;" class="col-md-4">
                        <div class="bg-successssss">
                            <h4 class="py-2 text-center text-light" style="letter-spacing:5px;">BILL TO</h4>
                        </div>
                        <h5><span>{{$data->bill_name}}</span></h5>
                        <hr style="border: 1.7px solid #adaaaa;margin: 10px auto;width: 100%;" />
                        <h5><span>{{$data->bill_address}}</span></h5>
                        <hr style="border: 1.7px solid #adaaaa;margin: 10px auto;width: 100%;" />
                    </div>
                    <div style="box-shadow: 0px 6px 8px rgba(7, 7, 7, 0.6);padding: 30px 20px 20px;text-align: left;margin-right: 3rem;" class="col-md-4 ml-auto">
                        <h5><b>Port Of Loading:</b>  <span style="text-transform:uppercase;">{{$data->delivered_port}}</span></h5>
                        <hr style="border: 1.7px solid #adaaaa;margin: 10px auto;width: 100%;" />
                        <h5><b>Vessel:</b>  <span>{{$data->vessel_grimaldi_salluam}}</span></h5>
                        <hr style="border: 1.7px solid #adaaaa;margin: 10px auto;width: 100%;" />
                        <h5><b>Date:</b>  <span>{{\Carbon\Carbon::parse($data->created_at)->format('M, d Y h:i A')}}</span></h5>
                        <hr style="border: 1.7px solid #adaaaa;margin: 10px auto;width: 100%;" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-11 m-auto">
                        <table class="table table-hover infotbl">
                            <thead class="bg-successssss">
                                <tr>
                                    <th class="text-light">Vehicle Information</th>
                                    <th class="text-light">Pickup From</th>
                                    <th class="text-light">Deliver To</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        {{$data->year}} {{$data->make}} {{$data->model}} <br />
                                        <b>VIN: </b>{{$data->vin}} 
                                    </td>
                                    <td>
                                        {{$data->from_address}}
                                    </td>
                                    <td>
                                        {{$data->too_address}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <div class="col-sm-11 m-auto">
                        <table class="table table-hover pricetbl">
                            <thead class="bg-successssss">
                                <tr>
                                    <th class="text-light">Description Of Charges</th>
                                    <th class="text-light">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        @if(isset($data->transportation_fees)) 
                                            <p>Transportation Price</p>
                                        @endif
                                        @if(isset($data->auction_storage_fees)) 
                                            <p>Auction Storage Price</p>
                                        @endif
                                        @if(isset($data->yard_storage_fees)) 
                                            <p>Yard Storage Price</p>
                                        @endif
                                        @if(isset($data->yard_forklift_fees)) 
                                            <p>Yard Forklift (Load & Unload) Price</p>
                                        @endif
                                        @if(isset($data->redelivery_fees)) 
                                            <p>Redelivery Price</p>
                                        @endif
                                        @if(isset($data->shipping_fees)) 
                                            <p>Shipping Price</p>
                                        @endif
                                        @if(isset($data->non_runner_fees)) 
                                            <p>Non Runner Price</p>
                                        @endif
                                        @if(isset($data->forklift_fees)) 
                                            <p>Forklift Price</p>
                                        @endif
                                        @if(isset($data->telex_fees)) 
                                            <p>Telex Price</p>
                                        @endif
                                        @if(isset($data->extra_other_fees)) 
                                            <p>Extra Other Price</p>
                                        @endif
                                    </td>
                                    <td>
                                        <?php 
                                            $total = 0;
                                        ?>
                                        @if(isset($data->transportation_fees)) 
                                            <?php 
                                                $total = $total + $data->transportation_fees;
                                            ?>
                                            <p>${{number_format($data->transportation_fees,2)}}</p>
                                        @endif
                                        @if(isset($data->auction_storage_fees)) 
                                            <?php 
                                                $total = $total + $data->auction_storage_fees;
                                            ?>
                                            <p>${{number_format($data->auction_storage_fees,2)}}</p>
                                        @endif
                                        @if(isset($data->yard_storage_fees)) 
                                            <?php 
                                                $total = $total + $data->yard_storage_fees;
                                            ?>
                                            <p>${{number_format($data->yard_storage_fees,2)}}</p>
                                        @endif
                                        @if(isset($data->yard_forklift_fees)) 
                                            <?php 
                                                $total = $total + $data->yard_forklift_fees;
                                            ?>
                                            <p>${{number_format($data->yard_forklift_fees,2)}}</p>
                                        @endif
                                        @if(isset($data->redelivery_fees)) 
                                            <?php 
                                                $total = $total + $data->redelivery_fees;
                                            ?>
                                            <p>${{number_format($data->redelivery_fees,2)}}</p>
                                        @endif
                                        @if(isset($data->shipping_fees)) 
                                            <?php 
                                                $total = $total + $data->shipping_fees;
                                            ?>
                                            <p>${{number_format($data->shipping_fees,2)}}</p>
                                        @endif
                                        @if(isset($data->non_runner_fees)) 
                                            <?php 
                                                $total = $total + $data->non_runner_fees;
                                            ?>
                                            <p>${{number_format($data->non_runner_fees,2)}}</p>
                                        @endif
                                        @if(isset($data->forklift_fees)) 
                                            <?php 
                                                $total = $total + $data->forklift_fees;
                                            ?>
                                            <p>${{number_format($data->forklift_fees,2)}}</p>
                                        @endif
                                        @if(isset($data->telex_fees)) 
                                            <?php 
                                                $total = $total + $data->telex_fees;
                                            ?>
                                            <p>${{number_format($data->telex_fees,2)}}</p>
                                        @endif
                                        @if(isset($data->extra_other_fees)) 
                                            <?php 
                                                $total = $total + $data->extra_other_fees;
                                            ?>
                                            <p>${{number_format($data->extra_other_fees,2)}}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: unset !important;text-align:right;" class="p-0">
                                        <p style="padding: 10px;margin: 0;"><span style="margin-right: 20px;font-size: 1.3rem;"><b>USD</b></span>Total</p>
                                        <p style="padding: 10px;margin: 0;">Paid Amount</p>
                                        <p style="padding: 10px;margin: 0;">Balance Due</p>
                                    </td>
                                    <td class="p-0">
                                        <p style="border-bottom: 2px solid #000;padding: 10px;margin: 0;">${{number_format($total,2)}}</p>
                                        <p style="border-bottom: 2px solid #000;padding: 10px;margin: 0;">${{number_format(($data->paid_amount ?? 0),2)}}</p>
                                        <p style="border-bottom: 2px solid #000;padding: 10px;margin: 0;">${{number_format(($total - $data->paid_amount),2)}}</p>
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


@endsection

@section('extraScript')
<script>
    var dataURL = {};
     $("#btnConvert").on('click', function () {
        var btnConvert = $(this);
        btnConvert.hide();
        setTimeout(function(){
            btnConvert.show();
        },1000);
        html2canvas(document.querySelector("html")).then(canvas => {  
            return Canvas2Image.saveAsJPEG(canvas)
        });
    });
</script>

@endsection

