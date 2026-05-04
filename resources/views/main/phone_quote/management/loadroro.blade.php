@include('partials.mainsite_pages.return_function')
<?php
$respn = trim("$_SERVER[REQUEST_URI]", '/');
if (isset($_GET['titlee'])) {
    $respn = $_GET['titlee'];
}


?>


<div class="table-responsive">

    <?php
    if($display == 'yes'){
    ?>
    <table class="table table-striped table-bordered text-nowrap">
        <thead>
        <tr>
            <th class="border-bottom-0">Detail</th>
            <th class="border-bottom-0">Fees</th>
            <th class="border-bottom-0">ACTIONS</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $val)
            <tr>
                <td>
                    Billing Name: <b>{{$val->bill_name ?? 'N/A'}}</b> <br />
                    Billing Address: <b>{{$val->bill_address ?? 'N/a'}}</b> <br />
                    Vehicle: <b>{{$val->year}} {{$val->make}} {{$val->model}}</b> <br />
                    Vin Number: <b>{{$val->vin ?? 'N/A'}}</b> <br />
                    From Address: <b>{{ $val->from_address }}</b> <br />
                    Too Address: <b>{{ $val->too_address }}</b> <br />
                    @if(isset($val->delivered_port)) Port Of Loading: <b>{{$val->delivered_port}}</b> <br /> @endif
                    @if(isset($val->vessel_grimaldi_salluam)) Vessel: <b>{{$val->vessel_grimaldi_salluam}}</b> <br /> @endif
                    Created At: <b>{{\Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i A')}}</b>
                </td>
                <td>
                    <?php 
                        $total = ($val->transportation_fees ?? 0) + ($val->auction_storage_fees ?? 0) + ($val->yard_storage_fees ?? 0) + ($val->yard_forklift_fees ?? 0) + ($val->redelivery_fees ?? 0) + ($val->shipping_fees ?? 0) + ($val->non_runner_fees ?? 0) + ($val->forklift_fees ?? 0) + ($val->telex_fees ?? 0) + ($val->extra_other_fees ?? 0);
                    ?>
                    @if(isset($val->transportation_fees)) Transportation: <b>${{$val->transportation_fees}}</b> <br /> @endif
                    @if(isset($val->auction_storage_fees)) Auction Storage: <b>${{$val->auction_storage_fees}}</b> <br /> @endif
                    @if(isset($val->yard_storage_fees)) Yard Storage: <b>${{$val->yard_storage_fees}}</b> <br /> @endif
                    @if(isset($val->yard_forklift_fees)) Yard Forklift (Load & Unload): <b>${{$val->yard_forklift_fees}}</b> <br /> @endif
                    @if(isset($val->redelivery_fees)) Redelivery: <b>${{$val->redelivery_fees}}</b> <br /> @endif
                    @if(isset($val->shipping_fees)) Shipping: <b>${{$val->shipping_fees}}</b> <br /> @endif
                    @if(isset($val->non_runner_fees)) Non Runner: <b>${{$val->non_runner_fees}}</b> <br /> @endif
                    @if(isset($val->forklift_fees)) Forklift: <b>${{$val->forklift_fees}}</b> <br /> @endif
                    @if(isset($val->telex_fees)) Telex: <b>${{$val->telex_fees}}</b> <br /> @endif
                    @if(isset($val->extra_other_fees)) Extra Other: <b>${{$val->extra_other_fees}}</b> <br /> @endif
                    @if(isset($val->paid_amount)) Paid Amount: <b>${{$val->paid_amount}}</b> <br /> @endif
                    <hr class="my-2" />
                    Total Amount: <b>${{$total}}</b>
                    Paid Amount: <b>${{$val->paid_amount ?? 0}}</b> <br />
                    Balance Due: <b>${{$total - $val->paid_amount}}</b> <br />
                    <hr class="my-2" />
                </td>
                <td id='order_action'>
                    <div class="btn-list">

                        <a href="{{url('/view_invoice_roro/'. $val->id)}}" class="btn btn-info">
                            <i class="fe fe-settings mr-1"></i> View Invoice</a>

                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{  $data->links() }}
    <?php
    }
    ?>


</div>


<script>
    regain_call();
    regain_status();
    regain_report_modal();
</script>
