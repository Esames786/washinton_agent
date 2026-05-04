@if(isset($price->id))
<?php
$price1 = (json_decode($price->carrier_price1) !== null) ? json_decode($price->carrier_price1) : ['0'];
$price2 = (json_decode($price->carrier_price2) !== null) ? json_decode($price->carrier_price2) : ['0'];
$price3 = (json_decode($price->carrier_price3) !== null) ? json_decode($price->carrier_price3) : ['0'];
$price4 = (json_decode($price->carrier_price4) !== null) ? json_decode($price->carrier_price4) : ['0'];
$price5 = (json_decode($price->carrier_price5) !== null) ? json_decode($price->carrier_price5) : ['0'];
$price6 = (json_decode($price->carrier_price6) !== null) ? json_decode($price->carrier_price6) : ['0'];
$price7 = (json_decode($price->carrier_price7) !== null) ? json_decode($price->carrier_price7) : ['0'];
$price8 = (json_decode($price->carrier_price8) !== null) ? json_decode($price->carrier_price8) : ['0'];
$price9 = (json_decode($price->carrier_price9) !== null) ? json_decode($price->carrier_price9) : ['0'];
$price10 = (json_decode($price->carrier_price10) !== null) ? json_decode($price->carrier_price10) : ['0'];
$price11 = (json_decode($price->carrier_price11) !== null) ? json_decode($price->carrier_price11) : ['0'];
$price12 = (json_decode($price->carrier_price12) !== null) ? json_decode($price->carrier_price12) : ['0'];
$price13 = (json_decode($price->carrier_price13) !== null) ? json_decode($price->carrier_price13) : ['0'];
$price14 = (json_decode($price->carrier_price14) !== null) ? json_decode($price->carrier_price14) : ['0'];
$price15 = (json_decode($price->carrier_price15) !== null) ? json_decode($price->carrier_price15) : ['0'];
$price16 = (json_decode($price->carrier_price16) !== null) ? json_decode($price->carrier_price16) : ['0'];
$price17 = (json_decode($price->carrier_price17) !== null) ? json_decode($price->carrier_price17) : ['0'];
$price18 = (json_decode($price->carrier_price18) !== null) ? json_decode($price->carrier_price18) : ['0'];
$price19 = (json_decode($price->carrier_price19) !== null) ? json_decode($price->carrier_price19) : ['0'];
$price20 = (json_decode($price->carrier_price20) !== null) ? json_decode($price->carrier_price20) : ['0'];

?>
@foreach($price1 as $key=>$val)
    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 border p-4 my-3">
        <h4>Carrier Prices (Car) {{$price->miles ?? 'N/A'}} Miles</h4>
        <table class="table table-hover">
            <tr>
                <th>Price 1: </th>
                <td>${{$price1[$key]}}</td>
            </tr>
            <tr>
                <th>Price 2: </th>
                <td>${{$price2[$key]}}</td>
            </tr>
            <tr>
                <th>Price 3: </th>
                <td>${{$price3[$key]}}</td>
            </tr>
            <tr>
                <th>Price 4: </th>
                <td>${{$price4[$key]}}</td>
            </tr>
            <tr>
                <th>Price 5: </th>
                <td>${{$price5[$key]}}</td>
            </tr>
        </table>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 border p-4 my-3">
        <h4>Carrier Prices (SUV) {{$price->miles ?? 'N/A'}} Miles</h4>
        <table class="table table-hover">
            <tr>
                <th>Price 6: </th>
                <td>${{$price6[$key]}}</td>
            </tr>
            <tr>
                <th>Price 7: </th>
                <td>${{$price7[$key]}}</td>
            </tr>
            <tr>
                <th>Price 8: </th>
                <td>${{$price8[$key]}}</td>
            </tr>
            <tr>
                <th>Price 9: </th>
                <td>${{$price9[$key]}}</td>
            </tr>
            <tr>
                <th>Price 10: </th>
                <td>${{$price10[$key]}}</td>
            </tr>
        </table>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 border p-4 my-3">
        <h4>Carrier Prices (Pickup) {{$price->miles ?? 'N/A'}} Miles</h4>
        <table class="table table-hover">
            <tr>
                <th>Price 11: </th>
                <td>${{$price11[$key]}}</td>
            </tr>
            <tr>
                <th>Price 12: </th>
                <td>${{$price12[$key]}}</td>
            </tr>
            <tr>
                <th>Price 13: </th>
                <td>${{$price13[$key]}}</td>
            </tr>
            <tr>
                <th>Price 14: </th>
                <td>${{$price14[$key]}}</td>
            </tr>
            <tr>
                <th>Price 15: </th>
                <td>${{$price15[$key]}}</td>
            </tr>
        </table>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 border p-4 my-3">
        <h4>Carrier Prices (Van) {{$price->miles ?? 'N/A'}} Miles</h4>
        <table class="table table-hover">
            <tr>
                <th>Price 16: </th>
                <td>${{$price16[$key]}}</td>
            </tr>
            <tr>
                <th>Price 17: </th>
                <td>${{$price17[$key]}}</td>
            </tr>
            <tr>
                <th>Price 18: </th>
                <td>${{$price18[$key]}}</td>
            </tr>
            <tr>
                <th>Price 19: </th>
                <td>${{$price19[$key]}}</td>
            </tr>
            <tr>
                <th>Price 20: </th>
                <td>${{$price20[$key]}}</td>
            </tr>
        </table>
    </div>
@endforeach
@endif