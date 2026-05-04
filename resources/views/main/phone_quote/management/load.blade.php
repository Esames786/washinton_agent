@include('partials.mainsite_pages.return_function')
<?php
$respn = trim("$_SERVER[REQUEST_URI]", '/');
if (isset($_GET['titlee'])) {
    $respn = $_GET['titlee'];
}


?>


<div class="table-responsive">

    <?php
    // if($display == 'yes'){
    ?>
    <table id="example1" class="table table-striped table-bordered text-nowrap">
        <thead>
        <tr>
            <th class="border-bottom-0">INVOCE & ORDER ID</th>
            <th class="border-bottom-0">CARRIER FEE</th>
            <th class="border-bottom-0">C.O.D</th>
            <th class="border-bottom-0">OWES</th>
            <th class="border-bottom-0">DATE/SITE</th>
            <th class="border-bottom-0">ACTIONS</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $val)
            <tr>
                <td>
                    Invoice Id : {{$val->id}}
                    <br>
                    Order Id : {{$val->orderId}}
                    <input type="hidden" class='order_id' value="{{$val->orderId}}">
                    <input type="hidden" class="pstatus" value="{{ $val->carrier_fee }}">
                    <input type="hidden" class="client_email" value="{{ $val->cod }}">
                    <input type="hidden" class="client_name" value="{{ $val->owes }}">
                    <input type="hidden" class="client_phone" value="{{ $val->created_at }}">
                </td>
                <td>
                    {{ $val->carrier_fee }}
                </td>
                <td>
                    {{ $val->cod }}
                </td>

                <td>
                    {{ $val->owes }}
                </td>
                <td>
                    {{\Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i A')}}
                    <br>
                    {{ $val->site }}
                </td>

                <td id='order_action'>
                    <div class="btn-list">

                        <a href="{{url('/view_invoice/'. $val->id)}}" class="btn btn-info">
                            <i class="fe fe-settings mr-1"></i> View Invoice</a>

                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{  $data->links() }}
    <?php
    // }
    ?>


</div>


<script>
    regain_call();
    regain_status();
    regain_report_modal();
</script>
