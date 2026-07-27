@extends('layouts.print_layout')

@section('template_title')
    Print Invoice
@endsection
@include('partials.mainsite_pages.return_function')

<style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
        }
        .invoice-container {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            max-width: 900px;
            margin: 20px auto;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* HEADER BACKGROUND */
        .header {
            position: relative;
            background: linear-gradient(62deg, #062E39 46%, #ffffff 47%, #ffffff 47%, #84d143 47%);
            color: white;
            padding: 20px;
            border-radius: 8px;
        }

        .header .logo img {
            max-height: 60px;
        }

        /* Contact info styling */
        .header .contact-info {
            margin-top: 10px;
            font-size: 14px;
        }

        .header .contact-info i {
            margin-right: 8px;
            color: #84d143; /* Icon color */
        }

        .header .invoice-title {
            text-align: right;
        }

        .invoice-title h1 {
            font-size: 36px;
            margin: 0;
        }

        .invoice-number {
            margin-top: 10px;
            font-size: 18px;
            color: white;
        }

        .details-section {
            padding: 20px 0;
        }

        /* TABLE HEADER STYLING */
        .table-title {
            background: linear-gradient(60deg, #062E39 45%, #ffffff 46%, #ffffff 47%, #84d143 48%);
            color: white;
            font-weight: bold;
            text-align: center;
        }

        .table-custom th, .table-custom td {
            text-align: center;
            border: none;
        }

        .table-custom tbody tr {
            border-bottom: 1px solid #e0e0e0;
        }

        .amount {
            font-size: 40px;
            border-bottom: #84d143 2px solid;
            font-weight: bold;
            color: #84d143;
        }

        .total-section {
            text-align: right;
            font-weight: bold;
            color: #84d143;
            background-color: #244d7d;
            padding: 10px 20px;
            border-radius: 5px;
            color: white;
        }

        .payment-info {
            margin-top: 20px;
        }

        .payment-info h5 {
            margin-bottom: 10px;
            color: #244d7d;
        }

        .note-section {
            font-size: 12px;
            color: gray;
            margin-top: 20px;
        }

</style>

@section('content')
    <div class="container card  p-5">
        <h3 class="d-flex justify-content-between" style="color: #ff0048;height: 36px;"><span class="my-auto mx-3">INVOICE #
                {{ $data->id }}</span><button type="button" class="btn btn-primary" id="btnConvert"><i
                    class="fa fa-download" aria-hidden="true"></i></button> </h3>
        <div class="row" style=" margin-top: 10px; ">
            <div class="card">
                <div class="card-header justify-content-between">
                    <h3 class="c_name">{{ $brand['name'] ?? 'Hello Transport' }}</h3>
                    <img src="{{ asset(($brand['logo'] ?? 'frontend/img/logo/hello_transport.svg')) }}" style="max-height:70px;width:auto;" alt="Hello Transport">
                </div>
                <div class="card-body">
                    {{-- #2: company address / tel / email removed from transportation invoice --}}
                    <h4 class="c_heading" style=" margin-top: 21px; ">INVOICE INFORMATION</h4>
                    <ul class="list-group">
                        <li class="list-group-item" style=" height: 50px; ">
                            <h5> Invoice No <label class="float_right "> {{ $data->id }}</label></h5>
                        </li>
                        <li class="list-group-item" style=" height: 50px; ">
                            <h5>Order No <span class="float_right">{{ $data->orderId }}</span></h5>
                        </li>
                        <li class="list-group-item" style=" height: 50px; ">
                            <h5>Date <span
                                    class="float_right">{{ \Carbon\Carbon::parse($data->created_at)->format('M, d Y h:i A') }}</span>
                            </h5>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="card col-md-6">
                <div class="card-header ">
                    <h4 class="c_heading">CARRIER INFORMATION</h4>
                </div>
                <div class="card-body">
                    <h5><?php echo get_carrier($data->orderId, 'companyname'); ?></h5>
                    <h5><?php echo get_carrier($data->orderId, 'location'); ?></h5>

                </div>
            </div>


        </div>
        <div class="row">
            <div class="card col-md-12">
                <div class="card-header">
                    <h4 class="c_heading"></h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ORDER ID</th>
                                <th>FROM/TO</th>
                                <th>VEHICLE NAME</th>
                                <th>CARRIER FEE</th>
                                <th>C.O.D</th>
                                <th>OWES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $data->orderId }}</td>
                                <td><?php echo get_autoorder($data->orderId, 'originzsc'); ?></td>
                                <td><?php echo get_autoorder($data->orderId, 'ymk'); ?></td>
                                <td>{{ $data->carrier_fee }}</td>
                                <td>{{ $data->cod }}</td>
                                <td>{{ $data->owes }}</td>
                            </tr>
                        </tbody>
                    </table>
                    @if ($data->site == 'Ship A1(Broker)')
                        <p><b>Note: </b>{{ $brand['name'] ?? 'Hello Transport' }} operates as a broker,
                            arranging and assigning carriers to
                            complete your shipment. We coordinate
                            with trusted carriers to ensure your vehicle
                            is transported efficiently and securely.</p>
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection

@section('extraScript')
    <script>
        var dataURL = {};
        $("#btnConvert").on('click', function() {
            html2canvas(document.body).then(canvas => {
                return Canvas2Image.saveAsJPEG(canvas)
            });
        });
    </script>
@endsection