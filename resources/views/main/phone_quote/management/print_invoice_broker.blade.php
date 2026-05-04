<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Invoice | ShawnTransport</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <script src="https://cdn.rawgit.com/stephanebachelier/canvas2image/master/src/Canvas2Image.js"></script>
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
            color: #84d143;
            /* Icon color */
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
            /* text-align: center; */
        }

        .table-custom th,
        .table-custom td {
            /* text-align: center; */
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
            /* background-color: #062E39; */
            background: linear-gradient(-49deg, #062E39 77%, #ffffff 47%, #ffffff 47%, #ffffff 47%);
            padding: 10px 20px;
            border-radius: 5px;
            color: white;
        }

        .payment-info {
            margin-top: 20px;
        }

        .payment-info h5 {
            margin-bottom: 10px;
            color: #062E39;
        }

        .note-section {
            font-size: 12px;
            color: gray;
            margin-top: 20px;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .header,
            .table-title,
            .total-section {
                background-color: inherit !important;
                color: inherit !important;
            }

            #btnConvert,
            #btnPrint {
                display: none;
            }

            .contact-info,
            .total-section {
                color: white !important;
            }
        }
    </style>
</head>

<body>
    @include('partials.mainsite_pages.return_function')
    <div class="invoice-container">
        <div class="header">
            <div class="row">
                <div class="col-md-6 logo">
                    <img src="https://www.shipa1.com/frontend/images/logo/logo-white-2.png" alt="ShipA1 Logo">
                    <div class="contact-info mt-3">
                        <p><i class="fas fa-phone"></i>
                            @if ($data->site == 'Ship A1')
                                <span>Tel No: (240) 489-2730</span>
                            @else
                                <span>Tel No: (301)-200-4705</span>
                            @endif
                        </p>
                        <p><i class="fas fa-globe"></i>
                            <span>Email: shawntransport@shipa1.com</span>
                        </p>
                        <p><i class="fas fa-map-marker-alt"></i>
                            6700 Alexander Bell Dr Suite 200, Columbia, MD 21046, USA
                        </p>
                    </div>
                </div>

                <div class="col-md-6 invoice-title">
                    <h1>INVOICE</h1>
                    <p class="invoice-number">Invoice No: {{ $data->orderId }}</p>
                </div>
            </div>
        </div>
        <div class="details-section">
            <div class="row">
                <div class="col-md-6 d-flex flex-column align-items-start sal-animate ps-5">
                    <div class="row">
                        <div class="col-sm-12">
                            <p><strong>Invoice to: {{ $order->oname }}</strong></p>

                        </div>
                        <div class="col-sm-12">
                            <h3 class="fs-3 fw-bold" style="color: #062E39;">
                                <h5><?php echo get_carrier($data->orderId, 'companyname'); ?></h5>
                                <h5><?php echo get_carrier($data->orderId, 'location'); ?></h5>
                            </h3>
                        </div>
                        <div class="col-sm-12">
                            <i class="fas fa-map-marker-alt mx-2"
                                style="color: #84d143;"></i><strong>ADDRESS:</strong><br>
                            <span style="margin-left: 25px; text-wrap: nowrap; color: #214A78;">
                                {{ $data->customer_address }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 d-flex flex-column align-items-end sal-animate" style="padding-right: 60px;">
                    <p><strong>Total:</strong></p>
                    <h3 class="amount">${{ $data->carrier_fee }}</h3>
                    <p><strong>Invoice Date :</strong>
                        {{ \Carbon\Carbon::parse($data->created_at)->format('M, d Y h:i A') }}</p>
                </div>
            </div>

        </div>

        <!-- Vehicle Details Table -->
        <table class="table table-bordered table-custom">
            <thead>
                <tr class="table-title">
                    <th
                        style="color: white;  background: linear-gradient(235deg, #062E39 11%, #ffffff 13%, #ffffff 5%, #84d143 11%); padding: 15px 10px;">
                        VEHICLE NAME</th>
                    <th style="  color: white; background: #062e39; padding: 15px 10px;">PICKUP</th>
                    <th style="  color: white; background: #062e39; padding: 15px 10px;">DELIVERY</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $order->ymk }}</td>
                    <td>New Braunfels, TX-78130</td>
                    <td>Jones, MI-49061</td>
                </tr>
            </tbody>
        </table>

        <!-- Invoice Breakdown Table -->
        <table class="table table-bordered table-custom">
            <thead>
                <tr class="table-title">
                    <th
                        style="color: white;  background: linear-gradient(235deg, #062E39 11%, #ffffff 13%, #ffffff 5%, #84d143 11%); padding: 15px 10px;">
                        Description</th>
                    <th style="  color: white; background: #062e39; padding: 15px 10px;">Qty</th>
                    <th style="  color: white; background: #062e39; padding: 15px 10px;">Amount</th>
                    <th style="  color: white; background: #062e39; padding: 15px 10px;">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Transportation of <br>{{ $order->ymk }}</td>
                    <td>1</td>
                    <td>${{ $data->carrier_fee }}</td>
                    <td>${{ $data->carrier_fee }}</td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td>DEPOSIT</td>
                    <td>${{ $data->deposit }}</td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td>BALANCE</td>
                    <td>${{ $data->carrier_fee - $data->deposit }}</td>
                </tr>
            </tbody>
        </table>
        <div class="row d-flex justify-content-end">
            <!-- <div class="col-sm-6"></div> -->
            <div class="col-sm-6">
                <div class="total-section">
                    Total: ${{ $data->carrier_fee }}
                </div>
            </div>
        </div>
        <!-- <div class="payment-info">
            <h5>Payment Information:</h5>
            <p><strong>BANK OF AMERICA</strong></p>
            <p><strong>ACCOUNT NAME:</strong> BRISILLIAN TRANSPORT LLC</p>
            <p><strong>ACCOUNT #:</strong> 456053133274</p>
            <p><strong>ROUTING #:</strong> 021000322</p>
            <p><strong>ADDRESS:</strong> BALTIMORE MD 21228</p>
        </div> -->
        <div class="row">
            <div class="col-sm-6">
                <div class="mt-5 p-3 border border-danger rounded" style="background-color: #fff4f4;">
                    <strong style="color: #ff0000;">Note:</strong>
                    <p class="mt-2" style="font-size: 0.9rem;">
                        Ship A1 Transport operates as a broker, arranging transportation and acting as an agent on your
                        behalf.
                        We coordinate with trusted carriers to ensure your vehicle is transported efficiently and
                        securely.
                    </p>
                </div>
            </div>
            <div class="col-sm-6 d-flex align-items-end justify-content-center sal-animate">
                <div class="customer-info" style="border-top: 2px solid #214A78; padding-top: 10px; margin-top: 10px;">
                    <h3 style="margin: 0; color: #062E39;">
                        <h5><?php echo get_carrier($data->orderId, 'companyname'); ?></h5>
                    </h3>
                    <p style="margin: 0;">Customer <span style="font-size: smaller;">(signature)</span></p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 d-flex align-items-end justify-content-end">
                <button type="button" class="btn btn-primary" id="btnPrint">
                    <i class="fa fa-print" aria-hidden="true"></i> Print Invoice
                </button>
            </div>
        </div>
        <div class="header mt-4">
            <div class="row">
                <div class="col-sm-12"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>


    <script>
        document.getElementById("btnPrint").addEventListener("click", function() {
            window.print();
        });
    </script>
</body>

</html>
