@include('partials.mainsite_pages.return_function')
<html>
<head>
    <style>
        .table-fancy {
            border-color: #e2e2e2;
            width: 100%;
            margin-bottom: 30px;
        }

        .table-fancy th, .table-fancy td {
            padding: 15px;
            border: 1px solid #e2e2e2;
            text-align: center;
        }

        .status-summary {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .status-box {
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            min-width: 120px;
            color: #fff;
        }
    </style>
</head>
<body>

@php
    $groupedData = [];
    foreach ($data as $val) {
        $groupedData[$val->pstatus][] = $val;
    }
    $statusCounts = array_map('count', $groupedData);
    $total = array_sum($statusCounts);
@endphp

<div class="status-summary">

    <div class="status-box" style="background-color: #37d537">
        Total : {{ $total }}
    </div>
    @foreach($statusCounts as $status => $count)
        <div class="status-box" style="background-color: {{ $status == 14 ? '#dc3545' : ($status == 0 ? '#007bff' : ($status == 8 ? '#28a745' : '#6c757d')) }}">
            {{ get_pstatus($status) }}: {{ $count }}
        </div>
    @endforeach
</div>

<div class="spe-results">

    @foreach($groupedData as $status => $records)
        <h3>{{ get_pstatus($status) }}</h3>
        <table border="1" cellspacing="0" class="table-fancy">
            <thead>
            <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Origin</th>
                <th>Destination</th>
                <th>Name</th>
                <th>Vehicle</th>
                <th>Status</th>
                <th>Booked Price</th>
            </tr>
            </thead>
            <tbody>
            @foreach($records as $val)
                <tr>
                    <td> <a target="_blank" href="{{ url('') }}/searchData?search={{$val->id}}">{{ $val->id }}</a> </td>
                    <td>{{ date('M-d-Y H:i:s a', strtotime($val->created_at)) }}</td>
                    <td>{{ $val->originzsc }}</td>
                    <td>{{ $val->destinationzsc }}</td>
                    <td>{{ $val->oname }}</td>
                    <td>
                        @foreach(explode('*^-', $val->ymk) as $val2)
                            @if($val2) {{ $val2 }} <br> @endif
                        @endforeach
                    </td>
                    <td>{{ get_pstatus($val->pstatus) }}</td>
                    <td>${{ number_format( (int) $val->payment, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endforeach
</div>
</body>
</html>
