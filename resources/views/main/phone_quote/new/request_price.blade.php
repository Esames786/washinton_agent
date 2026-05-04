<html>
    <head>
        <style>
            .table-fancy {
                border-color: #e2e2e2;
            }
            .table-fancy tr td {
               padding:15px; 
            }
            .table-fancy tr th {
               padding:15px; 
            }
            .price {
                font-size: 20px;
                font-weight: bold;
            }
            @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');
            .spe-results * {
               font-family: 'Roboto', sans-serif;
            }
            table.table-fancy th {
                background: #1b84e7;
                color: #fff;
                
            }

table.table-fancy {
    border-collapse: collapse;
    
}

table.table-fancy th,
table.table-fancy tr {
    border: 1px solid #ddd;
    
}

table.table-fancy tbody tr:nth-child(even) {
    background: #f5f5f5;
    
}
.table-fancy tr td a {
    color: #1b84e7;
    text-decoration: none;
    font-weight: 600;
}
.table-fancy tr td a {
    color: #1b84e7;
    text-decoration: none;
    font-weight: 600;
    
}

.spe-results h1 {
    position: relative;
    width: fit-content;
    
}

.spe-results h1::before {
    content: '';position: absolute;
    left: 0;
    bottom: -5px;
    background: #1b84e7;
    width: 100%;
    height: 3px;
    
}
        </style>
    </head>
    <body>
        <div class="spe-results">
            @if(count($zip)>0)
            <h1>Find With Zip</h1>
            <table border="1" cellspacing="0" class="table-fancy">
                <thead>
                    <tr>
                        <th>#</th>
            	        <th>Previous Shipments</th>
            	        <th>Date</a></th>
            	        <th>Origin</th>
            	        <th>Destination</th>
            	        <th>Driver Price</th>
            	        <th>Pay Carrier</th>
            	        <th>Booked Price</th>
                    </tr>
                </thead>
                 <tbody>
                    @if(count($zip)>0)
                        @foreach($zip as $key => $value)
                        <tr class="spe-results-awesome">
                            <td>{{$value->id}}</td>
                            <td class="title">
                                <?php
                                $heading = explode('*^-',$value->ymk);
                                if(count($heading) == 1) {
                                    if($heading[0]) {
                                        echo $heading[0];
                                    } else {
                                        echo "N/A";
                                    }
                                } else {
                                    $i = 0;
                                    $hd = "";
                                    $len = count($heading);
            
                                    foreach ($heading as $key => $head) {
                                        if ($i == $len - 1) {
                                            // last
                                            $hd .= $head;
            
                                        } else {
                                            $hd .= $head . ' | ';
            
                                        }
                                        $i++;
            
                                    }
            
                                    echo '<a href="javascript:void(0)" title="'.$hd.'">'.$len . ' Vehicles'.'</a>';
                                }
                                ?>
                            </td>
                            <td class="rel">
                                <?php
                                    echo  date('M-d-Y h:i:s A',strtotime($value->created_at)) ;
                                ?>
                            </td>
                            <td class="from">
                                <?php
                                echo $value->originzsc;
                                ?>
                            </td>
                            <td class="to">
                                <?php
                                echo $value->destinationzsc;
                                ?>
                            </td>
                            <td class="price">$<?php 
                            if($value->driver_price == NULL){
                                echo 0;
                            }
                            else{
                                echo $value->driver_price;
                            }
                            ?></td>
                            <td class="price">$<?php 
                            if($value->pay_carrier == NULL){
                                echo 0;
                            }
                            else{
                                echo $value->pay_carrier;
                            }
                            ?></td>
                            <td class="price">$<?php 
                            if($value->payment == NULL){
                                echo 0;
                            }
                            else{
                                echo $value->payment;
                            }
                            ?></td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            @endif
            @if($city) 
                @if(count($city) > 0)
                    <h1>Find With City</h1>
                    <table border="1" cellspacing="0" class="table-fancy">
                        <tbody>
                            <tr>
                                <th>#</th>
                                <th>Previous Shipments</th>
                                <th>Date</a></th>
                    	        <th>Origin</th>
                    	        <th>Destination</th>
                    	        <th>Driver Price</th>
                    	        <th>Pay Carrier</th>
                                <th>Booked Price</th>
                            </tr>
                            @if(count($city)>0)
                                @foreach($city as $key => $value)
                                <tr class="spe-results-awesome">
                                    <td>{{$value->id}}</td>
                                    <td class="title">
                                        <?php
                                        $heading = explode('*^-',$value->ymk);
                                        if(count($heading) == 1) {
                                            if($heading[0]) {
                                                echo $heading[0];
                                            } else {
                                                echo "N/A";
                                            }
                                        } else {
                                            $i = 0;
                                            $hd = "";
                                            $len = count($heading);
                    
                                            foreach ($heading as $key => $head) {
                                                if ($i == $len - 1) {
                                                    // last
                                                    $hd .= $head;
                    
                                                } else {
                                                    $hd .= $head . ' | ';
                    
                                                }
                                                $i++;
                    
                                            }
                    
                                            echo '<a href="javascript:void(0)" title="'.$hd.'">'.$len . ' Vehicles'.'</a>';
                                        }
                                        ?>
                                    </td>
                                    <td class="rel">
                                        <?php
                                            echo  date('M-d-Y h:i:s A',strtotime($value->created_at)) ;
                                        ?>
                                    </td>
                                    <td class="from">
                                        <?php
                                        echo $value->originzsc;
                                        ?>
                                    </td>
                                    <td class="to">
                                        <?php
                                        echo $value->destinationzsc;
                                        ?>
                                    </td>
                                    <td class="price">$<?php 
                                    if($value->driver_price == NULL){
                                    }
                                    else{
                                        echo $value->driver_price;
                                    }
                                    ?></td>
                                    <td class="price">$<?php 
                                    if($value->pay_carrier == NULL){
                                        echo 0;
                                    }
                                    else{
                                        echo $value->pay_carrier;
                                    }
                                    ?></td>
                                    <td class="price">$<?php 
                                    if($value->payment == NULL){
                                        echo 0;
                                    }
                                    else{
                                        echo $value->payment;
                                    }
                                    ?></td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                @endif
            @endif
            @if($city)
                @if(count($city) < 3)
                    @if($pricePerMile)
                        <h1>Find With Origin To Destination</h1>
                        <table border="1" cellspacing="0" class="table-fancy">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>New Shipments</th>
                                    <th>Date</a></th>
                                    <th>Origin</th>
                                    <th>Destination</th>
                                    <th>Total Miles</th>
                                    <th>Mile/price</th>
                                    <th>Pay Carrier</th>
                                    <th>Booked Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($pricePerMile['vechileNameArr'] > 0)
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <?php
                                            if($pricePerMile['vechileArr'][0] > 1){
                                                echo '<a href="javascript:void(0)" title="'.$pricePerMile['newVehicleName'].'">'.count($pricePerMile['vechileNameArr'][0]).' Vehicles'.'</a>';
                                            }
                                            else{
                                                echo $pricePerMile['vechileNameArr'][0];
                                            }
                                        ?>
                                    </td>
                                    <td class="rel">
                                        <?php
                                            echo date('M-d-Y h:i:s A') ;
                                        ?>
                                    </td>
                                    <td class="from">
                                        <?php
                                        echo $pricePerMile['originadd'];
                                        ?>
                                    </td>
                                    <td class="to">
                                        <?php
                                        echo $pricePerMile['destinationadd'];
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $pricePerMile['newMiles'].' miles';
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            if($pricePerMile['price'] == ''){
                                                echo "Please add the mile price";
                                            }
                                            else{
                                                echo '1 mile / <br> $'.$pricePerMile['price'];
                                            }
                                        ?>
                                    </td>
                                    <td class="price">
                                        <?php 
                                            if($pricePerMile['carrierPrice'] == ''){
                                                echo "Please add the mile price";
                                            }
                                            else{
                                                echo '$'.floor($pricePerMile['carrierPrice']);
                                            }
                                        ?>
                                    </td>
                                    <td class="price">
                                        <?php 
                                            if($pricePerMile['bookedPrice'] == ''){
                                                echo "Please add the mile price";
                                            }
                                            else{
                                                echo '$'.floor($pricePerMile['bookedPrice']);
                                            }
                                        ?>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    @endif
                @endif
            @endif
        </div>
    </body>
</html>