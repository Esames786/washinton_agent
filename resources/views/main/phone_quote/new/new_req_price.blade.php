<html>
    <head>
        
        <head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

        
        <!-- CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

<!-- JavaScript (optional for components that require JS) -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>


<!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">




<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">-->


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
.row{
    display:flex;
    justify-content:space-between;
}
        </style>
    </head>
    <body>
        <div class="spe-results">
            <h1>Requested Prices</h1>
            <table border="1" cellspacing="0" class="table-fancy">
                <thead>
                    <tr>
                        <th>Order Id</th>
                        <th>Price Checker</th>
                        <th>Order Taker</th>
            	        <th>Year/Make/Model</th>
            	        <th>Date</a></th>
            	        <th>From</th>
            	        <th>To</th>
            	        <th>Miles</th>
                    </tr>
                </thead>
                 <tbody>
                     @foreach($data as $key => $value)
                        <tr class="spe-results-awesome">
                            <td>{{$value['order_id']}}</td>
                            <td>{{$value['price_checker']}}</td>
                            <td>{{$value['order_taker']}}</td>
                            <td class="title">
                                @if($value['vehicle_count'] > 0)
                                    <a href="javascript:void(0)" title="{{$value['vehicles']}}">{{$value['vehicle_count']}} Vehicles</a>
                                @else
                                    {{$value['year'][0].', '.$value['make'][0].', '.$value['model'][0]}}
                                @endif
                            </td>
                            <td class="rel">{{$value['date']}}
                            </td>
                            <!--<td class="from">{{$value['origin']}}-->
                            <!--</td>-->
                            
                            <td>
                        <a href="https://www.google.com/maps/place/{{$value['origin']}},+USA/" target="_blank" class="table1ancher">
                            
                            {{$value['origin']}}
                        </a>
                        </td>
                        
                            <!--<td class="to">{{$value['destination']}}-->
                            <!--</td>-->
                            
                            <td>
                        <a href="https://www.google.com/maps/place/{{$value['destination']}},+USA/" target="_blank" class="table1ancher">
                            {{$value['destination']}}
                        </a>
                        </td>
                            <td >{{$value['miles'] ?? 'N/A'}} Miles
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="row">
            @foreach($data as $key => $value)
            <div class="spe-results col-4">
                <h4 style="text-align: center;">Carrier Prices (CAR)</h4>
                
                <table  border="1" cellspacing="0" class="table-fancy">
                    <tr>
                        <th>Price 1: </th>
                        <! ok---->
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city1'], '[]"')) }},+{{ urlencode(trim($value['pickup_state1'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city1'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state1'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['pickup_city1'], '[]"') }}, {{ trim($value['pickup_state1'], '[]"') }}
                        </a>
                        </td>
                        <! ok---->
                        
                        <! ok---->
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city1'], '[]"')) }},+{{ urlencode(trim($value['pickup_state1'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city1'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state1'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['dropoff_city1'], '[]"') }}, {{ trim($value['dropoff_state1'], '[]"') }}
                        </a>
                        </td>
                        <! ok---->
                        <td>${{ intval(trim($value['carrier_price1'], '$[]"')) }}</td>

                        
                    </tr>
                    <tr>
                        <th>Price 2: </th>
                        
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city2'], '[]"')) }},+{{ urlencode(trim($value['pickup_state2'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city2'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state2'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['pickup_city2'], '[]"') }}, {{ trim($value['pickup_state2'], '[]"') }}
                        </a>
                        </td>
                        <! ok---->
                        
                        <! ok---->
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city2'], '[]"')) }},+{{ urlencode(trim($value['pickup_state2'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city2'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state2'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['dropoff_city2'], '[]"') }}, {{ trim($value['dropoff_state2'], '[]"') }}
                        </a>
                        </td>
                        <td>${{ intval(trim($value['carrier_price2'], '$[]"')) }}</td>
                    </tr>
                    <tr>
                        <th>Price 3: </th>
                        
                        
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city3'], '[]"')) }},+{{ urlencode(trim($value['pickup_state3'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city3'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state3'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['pickup_city3'], '[]"') }}, {{ trim($value['pickup_state3'], '[]"') }}
                        </a>
                        </td>
                        <! ok---->
                        
                        <! ok---->
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city3'], '[]"')) }},+{{ urlencode(trim($value['pickup_state3'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city3'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state3'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['dropoff_city3'], '[]"') }}, {{ trim($value['dropoff_state3'], '[]"') }}
                        </a>
                        </td>
                        <td>${{ intval(trim($value['carrier_price3'], '$[]"')) }}</td>
                    </tr>
                    <tr>
                        <th>Price 4: </th>
                        
                        
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city4'], '[]"')) }},+{{ urlencode(trim($value['pickup_state4'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city4'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state4'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['pickup_city4'], '[]"') }}, {{ trim($value['pickup_state4'], '[]"') }}
                        </a>
                        </td>
                        <! ok---->
                        
                        <! ok---->
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city4'], '[]"')) }},+{{ urlencode(trim($value['pickup_state4'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city4'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state4'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['dropoff_city4'], '[]"') }}, {{ trim($value['dropoff_state4'], '[]"') }}
                        </a>
                        </td>
                        <td>${{ intval(trim($value['carrier_price4'], '$[]"')) }}</td>

                    </tr>
                    <tr>
                        <th>Price 5: </th>
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city5'], '[]"')) }},+{{ urlencode(trim($value['pickup_state5'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city5'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state5'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                         {{ trim($value['pickup_city5'], '[]"') }}, {{ trim($value['pickup_state5'], '[]"') }}
                        </a>
                        </td>
                        <!--<span id="map-marker-container"></span>-->
                        
                        
                        <! ok---->
                        
                        <! ok---->
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city5'], '[]"')) }},+{{ urlencode(trim($value['pickup_state5'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city5'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state5'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['dropoff_city5'], '[]"') }}, {{ trim($value['dropoff_state5'], '[]"') }}
                        </a>
                        </td>
                        <td>${{ intval(trim($value['carrier_price5'], '$[]"')) }}</td>
                    </tr>
                </table>
            </div>
            @endforeach
            @foreach($data as $key => $value)
            <div class="spe-results col-4">
                <h4 style="text-align: center;">Carrier Prices (SUV)</h4>
                  
                        
                <table  border="1" cellspacing="0" class="table-fancy">
                    <tr>
                        <th>Price 6: </th>
                        
                        
                        
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city6'], '[]"')) }},+{{ urlencode(trim($value['pickup_state6'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city6'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state6'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['pickup_city6'], '[]"') }}, {{ trim($value['pickup_state6'], '[]"') }}
                        </a>
                        </td>
                        <! ok---->
                        
                        <! ok---->
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city6'], '[]"')) }},+{{ urlencode(trim($value['pickup_state6'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city6'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state6'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['dropoff_city6'], '[]"') }}, {{ trim($value['dropoff_state6'], '[]"') }}
                        </a>
                        </td>
                        <td>${{ intval(trim($value['carrier_price6'], '$[]"')) }}</td>
                    </tr>
                    <tr>
                        <th>Price 7: </th>
                        
                        
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city7'], '[]"')) }},+{{ urlencode(trim($value['pickup_state7'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city7'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state7'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['pickup_city7'], '[]"') }}, {{ trim($value['pickup_state7'], '[]"') }}
                        </a>
                        </td>
                        <! ok---->
                        
                        <! ok---->
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city7'], '[]"')) }},+{{ urlencode(trim($value['pickup_state7'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city7'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state7'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['dropoff_city7'], '[]"') }}, {{ trim($value['dropoff_state7'], '[]"') }}
                        </a>
                        </td>
                        <td>${{ intval(trim($value['carrier_price7'], '$[]"')) }}</td>

                    </tr>
                    <tr>
                        <th>Price 8: </th>
                        
                        
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city8'], '[]"')) }},+{{ urlencode(trim($value['pickup_state8'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city8'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state8'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['pickup_city8'], '[]"') }}, {{ trim($value['pickup_state8'], '[]"') }}
                        </a>
                        </td>
                        <! ok---->
                        
                        <! ok---->
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city8'], '[]"')) }},+{{ urlencode(trim($value['pickup_state8'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city8'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state8'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['dropoff_city8'], '[]"') }}, {{ trim($value['dropoff_state8'], '[]"') }}
                        </a>
                        </td>
                        <td>${{ intval(trim($value['carrier_price8'], '$[]"')) }}</td>

                    </tr>
                    <tr>
                        <th>Price 9: </th>
                        
                        
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city9'], '[]"')) }},+{{ urlencode(trim($value['pickup_state9'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city9'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state9'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['pickup_city9'], '[]"') }}, {{ trim($value['pickup_state9'], '[]"') }}
                        </a>
                        </td>
                        <! ok---->
                        
                        <! ok---->
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city9'], '[]"')) }},+{{ urlencode(trim($value['pickup_state9'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city9'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state9'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['dropoff_city9'], '[]"') }}, {{ trim($value['dropoff_state9'], '[]"') }}
                        </a>
                        </td>
                        <td>${{ intval(trim($value['carrier_price9'], '$[]"')) }}</td>

                    </tr>
                    <tr>
                        <th>Price 10: </th>
                        
                        
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city10'], '[]"')) }},+{{ urlencode(trim($value['pickup_state10'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city10'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state10'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['pickup_city10'], '[]"') }}, {{ trim($value['pickup_state10'], '[]"') }}
                        </a>
                        </td>
                        <! ok---->
                        
                        <! ok---->
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city10'], '[]"')) }},+{{ urlencode(trim($value['pickup_state10'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city10'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state10'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['dropoff_city10'], '[]"') }}, {{ trim($value['dropoff_state10'], '[]"') }}
                        </a>
                        </td>
                        <td>${{ intval(trim($value['carrier_price10'], '$[]"')) }}</td>

                    </tr>
                </table>
            </div>
            @endforeach
            @foreach($data as $key => $value)
            <div class="spe-results col-4">
                <h4 style="text-align: center;"> Carrier Prices (Pickup)</h4>
                <table  border="1" cellspacing="0" class="table-fancy">
                    <tr>
                        <th>Price 11: </th>
                        
                        
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city11'], '[]"')) }},+{{ urlencode(trim($value['pickup_state11'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city11'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state11'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['pickup_city11'], '[]"') }}, {{ trim($value['pickup_state11'], '[]"') }}
                        </a>
                        </td>
                        <! ok---->
                        
                        <! ok---->
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city11'], '[]"')) }},+{{ urlencode(trim($value['pickup_state11'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city11'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state11'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['dropoff_city11'], '[]"') }}, {{ trim($value['dropoff_state11'], '[]"') }}
                        </a>
                        </td>
                        <td>${{ intval(trim($value['carrier_price11'], '$[]"')) }}</td>

                    </tr>
                    <tr>
                        <th>Price 12: </th>
                        
                        
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city12'], '[]"')) }},+{{ urlencode(trim($value['pickup_state12'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city12'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state12'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['pickup_city12'], '[]"') }}, {{ trim($value['pickup_state12'], '[]"') }}
                        </a>
                        </td>
                        <! ok---->
                        
                        <! ok---->
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city12'], '[]"')) }},+{{ urlencode(trim($value['pickup_state12'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city12'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state12'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['dropoff_city12'], '[]"') }}, {{ trim($value['dropoff_state12'], '[]"') }}
                        </a>
                        </td>
                        <td>${{ intval(trim($value['carrier_price12'], '$[]"')) }}</td>

                    </tr>
                    <tr>
                        <th>Price 13: </th>
                        
                        
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city13'], '[]"')) }},+{{ urlencode(trim($value['pickup_state13'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city13'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state13'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['pickup_city13'], '[]"') }}, {{ trim($value['pickup_state13'], '[]"') }}
                        </a>
                        </td>
                        <! ok---->
                        
                        <! ok---->
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city13'], '[]"')) }},+{{ urlencode(trim($value['pickup_state13'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city13'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state13'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['dropoff_city13'], '[]"') }}, {{ trim($value['dropoff_state13'], '[]"') }}
                        </a>
                        </td>
                        <td>${{ intval(trim($value['carrier_price13'], '$[]"')) }}</td>

                    </tr>
                    <tr>
                        <th>Price 14: </th>
                        
                        
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city14'], '[]"')) }},+{{ urlencode(trim($value['pickup_state14'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city14'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state14'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['pickup_city14'], '[]"') }}, {{ trim($value['pickup_state14'], '[]"') }}
                        </a>
                        </td>
                        <! ok---->
                        
                        <! ok---->
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city14'], '[]"')) }},+{{ urlencode(trim($value['pickup_state14'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city14'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state14'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['dropoff_city14'], '[]"') }}, {{ trim($value['dropoff_state14'], '[]"') }}
                        </a>
                        </td>
                        <td>${{ intval(trim($value['carrier_price14'], '$[]"')) }}</td>

                    </tr>
                    <tr>
                        <th>Price 15: </th>
                        
                        
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city15'], '[]"')) }},+{{ urlencode(trim($value['pickup_state15'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city15'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state15'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['pickup_city15'], '[]"') }}, {{ trim($value['pickup_state15'], '[]"') }}
                        </a>
                        </td>
                        <! ok---->
                        
                        <! ok---->
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city15'], '[]"')) }},+{{ urlencode(trim($value['pickup_state15'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city15'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state15'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['dropoff_city15'], '[]"') }}, {{ trim($value['dropoff_state15'], '[]"') }}
                        </a>
                        </td>
                        <td>${{ intval(trim($value['carrier_price15'], '$[]"')) }}</td>

                    </tr>
                </table>
            </div>
            @endforeach
            @foreach($data as $key => $value)
            <div class="spe-results col-4">
                <h4 style="text-align: center;">Carrier Prices (Van)</h4>
                <table  border="1" cellspacing="0" class="table-fancy">
                    <tr>
                        <th>Price 16: </th>
                        
                        
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city16'], '[]"')) }},+{{ urlencode(trim($value['pickup_state16'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city16'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state16'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['pickup_city16'], '[]"') }}, {{ trim($value['pickup_state16'], '[]"') }}
                        </a>
                        </td>
                        <! ok---->
                        
                        <! ok---->
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city16'], '[]"')) }},+{{ urlencode(trim($value['pickup_state16'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city16'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state16'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['dropoff_city16'], '[]"') }}, {{ trim($value['dropoff_state16'], '[]"') }}
                        </a>
                        </td>
                        <td>${{ intval(trim($value['carrier_price16'], '$[]"')) }}</td>

                    </tr>
                    <tr>
                        <th>Price 17: </th>
                        
                        
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city17'], '[]"')) }},+{{ urlencode(trim($value['pickup_state17'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city17'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state17'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['pickup_city17'], '[]"') }}, {{ trim($value['pickup_state17'], '[]"') }}
                        </a>
                        </td>
                        <! ok---->
                        
                        <! ok---->
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city17'], '[]"')) }},+{{ urlencode(trim($value['pickup_state17'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city17'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state17'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['dropoff_city17'], '[]"') }}, {{ trim($value['dropoff_state17'], '[]"') }}
                        </a>
                        </td>
                        <td>${{ intval(trim($value['carrier_price17'], '$[]"')) }}</td>

                    </tr>
                    <tr>
                        <th>Price 18: </th>
                        
                        
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city18'], '[]"')) }},+{{ urlencode(trim($value['pickup_state18'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city18'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state18'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['pickup_city18'], '[]"') }}, {{ trim($value['pickup_state18'], '[]"') }}
                        </a>
                        </td>
                        <! ok---->
                        
                        <! ok---->
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city18'], '[]"')) }},+{{ urlencode(trim($value['pickup_state18'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city18'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state18'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['dropoff_city18'], '[]"') }}, {{ trim($value['dropoff_state18'], '[]"') }}
                        </a>
                        </td>
                        <td>${{ intval(trim($value['carrier_price18'], '$[]"')) }}</td>

                    </tr>
                    <tr>
                        <th>Price 19: </th>
                        
                        
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city19'], '[]"')) }},+{{ urlencode(trim($value['pickup_state19'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city19'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state19'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['pickup_city19'], '[]"') }}, {{ trim($value['pickup_state19'], '[]"') }}
                        </a>
                        </td>
                        <! ok---->
                        
                        <! ok---->
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city19'], '[]"')) }},+{{ urlencode(trim($value['pickup_state19'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city19'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state19'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['dropoff_city19'], '[]"') }}, {{ trim($value['dropoff_state19'], '[]"') }}
                        </a>
                        </td>
                        <td>${{ intval(trim($value['carrier_price19'], '$[]"')) }}</td>

                    </tr>
                    <tr>
                        <th>Price 20: </th>
                        
                        
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city20'], '[]"')) }},+{{ urlencode(trim($value['pickup_state20'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city20'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state20'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['pickup_city20'], '[]"') }}, {{ trim($value['pickup_state20'], '[]"') }}
                        </a>
                        </td>
                        <! ok---->
                        
                        <! ok---->
                        <td>
                        <a href="https://www.google.com/maps/dir/{{ urlencode(trim($value['pickup_city20'], '[]"')) }},+{{ urlencode(trim($value['pickup_state20'], '[]"')) }},+USA/{{ urlencode(trim($value['dropoff_city20'], '[]"')) }},+{{ urlencode(trim($value['dropoff_state20'], '[]"')) }},+USA/" target="_blank" class="table1ancher">
                        {{ trim($value['dropoff_city20'], '[]"') }}, {{ trim($value['dropoff_state20'], '[]"') }}
                        </a>
                        </td>
                        <td>${{ intval(trim($value['carrier_price20'], '$[]"')) }}</td>
                        
                    </tr>
                </table>
            </div>
            @endforeach
        </div>
        
        <script>
        document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('map-marker-container').innerHTML = '<i class="fa fa-map-marker" style="color:green;" aria-hidden="true"></i>';
});
        </script>
    </body>
</html>