<html>
    <head>
        <style>
            .table-fancy {
                border-color: #e2e2e2;
                width:100%;
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
            <h1>Previous Driver Prices</h1>
             @foreach($data as $key => $value)
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
                            <tr class="spe-results-awesome">
                                <td>{{$value->order_id}}</td>
                                <td>{{isset($value->priceChecker->slug) ? $value->priceChecker->slug : (isset($value->priceChecker->name) ? $value->priceChecker->name.' '.$value->priceChecker->last_name : '')}}</td>
                                <td>{{isset($value->orderTaker->slug) ? $value->orderTaker->slug : (isset($value->orderTaker->name) ? $value->orderTaker->name.' '.$value->orderTaker->last_name : '')}}</td>
                                <?php 
                                    $year = explode('|<br>',$value->year);
                                    $make = explode('|<br>',$value->make);
                                    $model = explode('|<br>',$value->model);
                                    $vehicle = [];
                                    if(count($year) > 1 && count($make) > 1 && count($model) > 1) 
                                    {
                                        foreach($year as $key2 => $years){
                                            $vehicle[] = $years.', '.$make[$key2].', '.$model[$key2];
                                        }
                                    }
                                    $vehicles = implode(' | ',$vehicle);
                                    $vehicle_count = count($vehicle);
                                    
                                    $newveh = '';
                                    if(isset($year[0]))
                                    {
                                        $newveh = $year[0].', ';
                                    }
                                    if(isset($make[0]))
                                    {
                                        $newveh = $newveh.$make[0].', ';
                                    }
                                    if(isset($model[0]))
                                    {
                                        $newveh = $newveh.$model[0].', ';
                                    }
                                ?>
                                <td class="title">
                                    @if($value->vehicle_count > 0)
                                        <a href="javascript:void(0)" title="{{$vehicles}}">{{$vehicle_count}} Vehicles</a>
                                    @else
                                        {{$newveh}}
                                    @endif
                                </td>
                                <td class="rel">{{\Carbon\Carbon::parse($value->updated_at)->format('M,d Y h:i A')}}
                                </td>
                                <td class="from">{{$value->origin}}
                                </td>
                                <td class="to">{{$value->destination}}
                                </td>
                                <td >{{$value->miles}} Miles
                                </td>
                            </tr>
                    </tbody>
                    <thead>
                        <tr>
                            <th colspan="2">Carrier Prices (Car)</th>
                            <th colspan="2">Carrier Prices (SUV)</th>
                            <th colspan="2">Carrier Prices (Pickup)</th>
                	        <th colspan="2">Carrier Prices (Van)</th>
                        </tr>
                    </thead>
                     <tbody>
                        <tr class="spe-results-awesome">
                            <td colspan="2">
                                <b>Price 1:</b> ${{$value->carrier_price1}}<br />
                                <b>Price 2:</b> ${{$value->carrier_price2}}<br />
                                <b>Price 3:</b> ${{$value->carrier_price3}}<br />
                                <b>Price 4:</b> ${{$value->carrier_price4}}<br />
                                <b>Price 5:</b> ${{$value->carrier_price5}}<br />
                            </td>
                            <td colspan="2">
                                <b>Price 6:</b> ${{$value->carrier_price6}}<br />
                                <b>Price 7:</b> ${{$value->carrier_price7}}<br />
                                <b>Price 8:</b> ${{$value->carrier_price8}}<br />
                                <b>Price 9:</b> ${{$value->carrier_price9}}<br />
                                <b>Price 10:</b> ${{$value->carrier_price10}}<br />
                            </td>
                            <td colspan="2">
                                <b>Price 11:</b> ${{$value->carrier_price11}}<br />
                                <b>Price 12:</b> ${{$value->carrier_price12}}<br />
                                <b>Price 13:</b> ${{$value->carrier_price13}}<br />
                                <b>Price 14:</b> ${{$value->carrier_price14}}<br />
                                <b>Price 15:</b> ${{$value->carrier_price15}}<br />
                            </td>
                            <td colspan="2">
                                <b>Price 16:</b> ${{$value->carrier_price16}}<br />
                                <b>Price 17:</b> ${{$value->carrier_price17}}<br />
                                <b>Price 18:</b> ${{$value->carrier_price18}}<br />
                                <b>Price 19:</b> ${{$value->carrier_price19}}<br />
                                <b>Price 20:</b> ${{$value->carrier_price20}}<br />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr />
            @endforeach
        </div>
    </body>
</html>