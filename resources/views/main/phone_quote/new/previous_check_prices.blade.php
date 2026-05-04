<html>

<head>
    <style>
        .table-fancy {
            border-color: #e2e2e2;
            width: 100%;
        }

        .table-fancy tr td {
            padding: 15px;
        }

        .table-fancy tr th {
            padding: 15px;
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
            content: '';
            position: absolute;
            left: 0;
            bottom: -5px;
            background: #1b84e7;
            width: 100%;
            height: 3px;

        }

        .row {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body>
    <div class="spe-results">
        <h1>Previous Driver Prices</h1>
        <table border="1" cellspacing="0" class="table-fancy">
            <thead>
                <tr>
                    <th>Order Id</th>
                    <th>Price Giver</th>
                    <th>From</th>
                    <th>To</th>
                    <th>car</th>
                    <th>suv</th>
                    <th>pickup</th>
                    <th>van</th>
                    <th>Date</a></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $value)
                    <tr class="spe-results-awesome">
                        <td>{{ $value->order_id }}</td>
                        <td>{{ isset($value->price_giver->slug) ? $value->price_giver->slug : (isset($value->price_giver->name) ? $value->price_giver->name . ' ' . $value->price_giver->last_name : '') }}
                        </td>
                        <td class="from">{{ $value->order->originzsc }}
                        </td>
                        <td class="to">{{ $value->order->destinationzsc }}</td>
                        <td class="car">{!! $value->car !!}</td>
                        <td class="suv">{!! $value->suv !!}</td>
                        <td class="pickup">{!! $value->pickup !!}</td>
                        <td class="van">{!! $value->van !!}</td>
                        <td class="rel">{{ \Carbon\Carbon::parse($value->created_at)->format('M,d Y h:i A') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <hr />
    </div>
</body>

</html>
