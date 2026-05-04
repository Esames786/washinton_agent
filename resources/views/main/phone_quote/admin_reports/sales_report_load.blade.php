@include('partials.mainsite_pages.return_function')
<?php
$respn = trim("$_SERVER[REQUEST_URI]", '/');
if (isset($_GET['titlee'])) {
    $respn = $_GET['titlee'];
}
?>
@if($display=='yes')
<div class="table-responsive">

    <table id="example1" class="table table-striped table-bordered text-nowrap">
        <thead>
        <tr>
            <th class="border-bottom-0">ORDER#</th>
            <th class="border-bottom-0">C-NAME</th>
            <th class="border-bottom-0">C-EMAIL</th>
            <th class="border-bottom-0">LISTED PRICE</th>
            <th class="border-bottom-0">PRICE</th>
            <th class="border-bottom-0">PAY CARRIER</th>
            <th class="border-bottom-0">COD</th>
            <th class="border-bottom-0">Balance</th>
            <th class="border-bottom-0">PROFIT</th>


        </tr>
        </thead>
        <tbody>
        @foreach($data as $val)
            <tr>
                <td>{{$val->id}}</td>
                <td>{{ $val->oname}}</td>
                <td>{{ $val->oemail}}</td>
                <td>{{ $val->listed_price}}</td>
                <td>{{ $val->payment}}</td>
                <td>{{ $val->pay_carrier}}</td>
                <td>{{ $val->cod_cop}}</td>
                <td>{{ $val->balance}}</td>
                <td>{{ get_profit($val->id)}}</td>


            </tr>
        @endforeach
        <tr>
            <td><h4>Total</h4></td>
            <td></td>
            <td></td>
            <td style="text-align:left;font-weight:bold;">{{$sumlistedprice}}</td>
            <td style="text-align:left;font-weight:bold;">{{$sumsale}}</td>
            <td style="text-align:left;font-weight:bold;">{{$sumpaycarrier}}</td>
            <td style="text-align:left;font-weight:bold;">{{$sumcod}}</td>
            <td style="text-align:left;font-weight:bold;">{{$sumbalance}}</td>
            <td style="text-align:left;font-weight:bold;">{{$sumprofit}}</td>

        </tr>
        </tbody>
    </table>
    {{  $data->links() }}
</div>
@endif





