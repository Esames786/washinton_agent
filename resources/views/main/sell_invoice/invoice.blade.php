<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShawnTransport | Sell Invoice</title>
    <link rel="icon" href="{{ url('assets/images/brand/favicon.ico')}}" type="image/x-icon"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        main{
            margin: 64px auto;
        }
        #container {
            width: 70%;
            margin: 0 auto;
            border: 1px solid #dbe2eb;
            /*box-shadow: 0px 6px 8px rgba(4, 4, 7, 0.1);*/
            border-radius: 10px;
            padding: 36px;
        }
        .bg-muted{
            background-color: #dbd9d9 !important;
        }
        .text-blue{
            color: #179dd8;
        }
        hr{
            margin: 36px 0px 12px;
            height: 3px !important;
            color: #0097d9;
        }
        .btn-primary{
            width: 200px;
            margin-left: 15px;
            border-radius: 0px !important;
            background-color: #005e85 !important;
            border-color: #005e85 !important;
        }
        .table{
            border: 1px solid #bfbdbd;
        }
        td{
            color: #747474;
        }
        h4{
            color: #005e85;
        }
    </style>
</head>
<body>
    <div class="container mt-3" id="downloadBtnHideShow">
        <h3 class="row" style="color: #ff0048;height: 36px;" >
            <div class="col-sm-6">
                <span class="my-auto mx-3">INVOICE # {{$data->invoice_number}}</span>
            </div>
            <div class="col-sm-6 text-end">
                <button type="button" class="btn btn-success" id="btnConvert"><i class="fa fa-download" aria-hidden="true"></i></button> 
            </div>
        </h3>
    </div>
    <main id="download">
        <div class="container" id="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-5 my-auto">
                            <img src="{{ url('assets/images/brand/SHIPA1logo.webp')}}" class="w-50" alt="Ship A1" />
                        </div>
                        <div class="col-sm-7">
                            <div class="bg-muted py-3">
                                <h3 class="text-end me-4 my-auto text-muted">INVOICE # {{$data->invoice_number}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 mt-4">
                    <div class="row">
                        <div class="col-sm-5">
                            <h6 class="mb-0">SHIP A1</h6>
                            <small class="text-muted">201 International Cir STE 230, Hunt Valley, MD 21030-1344</small>
                            <br><br>
                            <small class="text-muted">Phone: 1 (844) 474-4721</small><br>
                            <small class="text-muted">Email: shawntransport@shipa1.com</small>
                        </div>
                        <div class="col-sm-7">
                            <div class="row mt-3">
                                <div class="col-sm-12">
                                    <div class="row ms-2">
                                        <div class="col-sm-3">
                                            <strong>Invoice:</strong>
                                        </div>
                                        <div class="col-sm-3">
                                            <small class="text-blue">{{$data->invoice_number}}</small>
                                        </div>
                                        <div class="col-sm-3">
                                            <strong>Inventory ID:</strong>
                                        </div>
                                        <div class="col-sm-3">
                                            <small class="text-blue">{{$data->inventory_id}}</small>
                                        </div>
                                    </div>
                                    <div class="row ms-2">
                                        <div class="col-sm-3">
                                            <strong>Date:</strong>
                                        </div>
                                        <div class="col-sm-3">
                                            <small class="text-blue">{{\Carbon\Carbon::parse($data->date)->format('M,d Y')}}</small>
                                        </div>
                                        <div class="col-sm-3">
                                            <strong>Sale Person:</strong>
                                        </div>
                                        <div class="col-sm-3">
                                            <small class="text-blue">{{$data->sale_person}}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-12 text-end">
                                    <strong>Please Pay: </strong>
                                    <button type="button" class="btn btn-primary">${{$data->total_amount}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 mt-4">
                    <div class="row">
                        <div class="col-sm-5">
                            <h4 class="mb-3">Bill/Sold To:</h4>
                            <small class="text-muted">{{$data->cname}}</small>
                            <br><br>
                            <small class="text-muted">Phone: {{$data->cphone}}</small><br>
                            <small class="text-muted">Email: {{$data->cemail}}</small>
                        </div>
                        <div class="col-sm-7">
                            <h4 class="mb-3">Vehicle Info</h4>
                            <table class="table">
                                <thead class="table-active">
                                    <tr>
                                        <th>Year/Make/Model</th>
                                        <th>VIN#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{$data->ymk}}</td>
                                        <td>{{$data->vin_number}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 mt-4">
                    <h4 class="mb-3">Summary:</h4>
                    <table class="table">
                        <thead class="table-active">
                            <tr>
                                <th style="width:45%;">Description</th>
                                <th style="width:35%;"></th>
                                <th style="width:20%;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="width:45%;">Sale Price</td>
                                <td style="width:35%;"></td>
                                <td style="width:20%;">${{$data->sale_price}}</td>
                            </tr>
                            <tr class="bg-muted">
                                <td style="width:45%;"></td>
                                <td style="width:35%;">
                                    <h4 class="text-end text-dark">Total Amount:</h4>
                                    <br>
                                    <h4 class="text-end text-dark">Balance Due At Delivery:</h4>
                                </td>
                                <td style="width:20%;">
                                    <h4 class="text-start text-dark">${{$data->total_amount}}</h4>
                                    <br>
                                    <h4 class="text-start text-dark">${{$data->balance}}</h4>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @if(isset($data->additional))
                <div class="col-sm-12 mt-4">
                    <h4 class="mb-3">Additional Instructions:</h4>
                    <small>
                        {{$data->additional ?? 'N/A'}}
                    </small>
                </div>
                @endif
                <div class="col-sm-12 mt-4">
                    <div class="row flex-row-reverse d-flex">
                        <div class="col-sm-3">
                            <hr class="text-muted" style="height:1px !important">
                            <p class="text-center text-muted">Customer's Signature</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
    <script src="{{ url('assets/js/htmlCanva.min.js')}}"></script>
    <script src="{{ url('assets/js/canva.js')}}"></script>
    <script>
        var dataURL = {};
         $("#btnConvert").on('click', function () {
            $("#downloadBtnHideShow").hide();
            html2canvas(document.body).then(canvas => {  
                return Canvas2Image.saveAsPNG(canvas)
            });
            setTimeout(function(){
                $("#downloadBtnHideShow").show();
            },2000); 
        });
    </script>
</body>
</html>