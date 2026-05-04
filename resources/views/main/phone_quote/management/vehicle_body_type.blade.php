@extends('layouts.innerpages')

@section('template_title')
    VEHICLE BODY TYPE
@endsection
@include('partials.mainsite_pages.return_function')


@section('content')



    <div class="slim-mainpanel">
        <div class="container">


            <div class="slim-pageheader" style="padding-left: 0px">
                <ol class="breadcrumb slim-breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/guides/">Guides</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Vehicle Body Type</li>
                </ol>
                <h6 class="slim-pagetitle">Vehicle Body Type Guide</h6>
            </div><!-- slim-pageheader -->

            <div class="section-wrapper">
                <label class="section-title">Vehicle Type</label>
                <p class="mg-b-10 mg-sm-b-10">These are the suggested body types that are mostly used to ship.</p>

                <table class="table table-reference">
                    <thead>
                    <tr>
                        <th class="wd-20p">Type</th>
                        <th class="wd-30p">Example Brand</th>
                        <th class="wd-50p">Preview</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="tx-bold">Car</td>
                        <td>Toyota Camry</td>
                        <td>
                            <span><a href="javascript:morevehicle('/assets/images/png/toyota-camry.jpg')"><img src="/assets/images/png/toyota-camry.jpg" style="width:auto;height:80px;"></a></span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Toyota+Camry+Car');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="tx-bold">Mid - SUV</td>
                        <td>Toyota RAV4</td>
                        <td>
                            <span><a href="javascript:morevehicle('/assets/images/png/toyota-rav4.jpg')"><img src="/assets/images/png/toyota-rav4.jpg" style="width:auto;height:80px;"></a></span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Toyota+RAV4+Mid+SUV');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="tx-bold">Large - SUV</td>
                        <td>Toyota 4Runner</td>
                        <td>
                            <span><a href="javascript:morevehicle('/assets/images/png/Toyota-4Runner.jpg')"><img src="/assets/images/png/Toyota-4Runner.jpg" style="width:auto;height:80px;"></a></span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Toyota+4Runner+Large+SUV');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="tx-bold">Mini Van</td>
                        <td>Toyota Sienna</td>
                        <td>
                            <span><a href="javascript:morevehicle('/assets/images/png/mini-van.jpg')"><img src="/assets/images/png/mini-van.jpg" style="width:auto;height:80px;"></a></span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Toyota+Sienna+mini+van');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="tx-bold">Passenger Van</td>
                        <td>Ford E-150</td>
                        <td>
                            <span><a href="javascript:morevehicle('/assets/images/png/ford-e-150.png')"><img src="/assets/images/png/ford-e-150.png" style="width:auto;height:80px;"></a></span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Ford+E-150+Passenger+Van');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Ford E-250</td>
                        <td>
                            <span><a href="javascript:morevehicle('/assets/images/png/ford-e250-psngr.png')"><img src="/assets/images/png/ford-e250-psngr.png" style="width:auto;height:80px;"></a></span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Ford+E-250+Passenger+Van');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Ford E-350</td>
                        <td>
                            <span><a href="javascript:morevehicle('/assets/images/png/ford-e350-psngr.jpg')"><img src="/assets/images/png/ford-e350-psngr.jpg" style="width:auto;height:80px;"></a></span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Ford+E-350+Passenger+Van');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="tx-bold">Cargo VAN</td>
                        <td>Ford E-150</td>
                        <td>
                            <span><a href="javascript:morevehicle('/assets/images/png/ford-e150-cargo.jpg')"><img src="/assets/images/png/ford-e150-cargo.jpg" style="width:auto;height:80px;"></a></span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Ford+E-150+Cargo+Van');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Ford E-250</td>
                        <td>
                            <span><a href="javascript:morevehicle('/assets/images/png/ford-e250-cargo.jpg')"><img src="/assets/images/png/ford-e250-cargo.jpg" style="width:auto;height:80px;"></a></span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Ford+E-250+Cargo+Van');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Ford E-350</td>
                        <td>
                            <span><a href="javascript:morevehicle('/assets/images/png/ford-e350-cargo.jpg')"><img src="/assets/images/png/ford-e350-cargo.jpg" style="width:auto;height:80px;"></a></span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Ford+E-350+Cargo+Van');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="tx-bold">Pickup</td>
                        <td>Ford F-150 2 door</td>
                        <td>
                            <span><a href="javascript:morevehicle('/assets/images/png/ford-f150-2d.png')"><img src="/assets/images/png/ford-f150-2d.png" style="width:auto;height:80px;"></a></span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Ford+F-150+2+door');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Ford F-150 4 door</td>
                        <td>
                            <span><a href="javascript:morevehicle('/assets/images/png/ford-f150-4d.jpg')"><img src="/assets/images/png/ford-f150-4d.jpg" style="width:auto;height:80px;"></a></span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Ford+F-150+4+door');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Ford F-250 2 door Short Bed</td>
                        <td>
                            <span><a href="javascript:morevehicle('/assets/images/png/ford-f250-2dsb.jpg')"><img src="/assets/images/png/ford-f250-2dsb.jpg" style="width:auto;height:80px;"></a></span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Ford+F-250+2+door+Short+Bed');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Ford F-250 4 door Short Bed</td>
                        <td>
                            <span><a href="javascript:morevehicle('/assets/images/png/ford-f250-4dsb.jpg')"><img src="/assets/images/png/ford-f250-4dsb.jpg" style="width:auto;height:80px;"></a></span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Ford+F-250+4+door+Short+Bed');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Ford F-250 2 door Long Bed</td>
                        <td>
                            <span><a href="javascript:morevehicle('/assets/images/png/ford-f250-2dlb.jpg')"><img src="/assets/images/png/ford-f250-2dlb.jpg" style="width:auto;height:80px;"></a></span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Ford+F-250+2+door+Long+Bed');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Ford F-250 4 door Long Bed</td>
                        <td>
                            <span><a href="javascript:morevehicle('/assets/images/png/ford-f250-4dlb.png')"><img src="/assets/images/png/ford-f250-4dlb.png" style="width:auto;height:80px;"></a></span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Ford+F-250+4+door+Long+Bed');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Ford F-350 2 door Short Bed</td>
                        <td>
                            <span><a href="javascript:morevehicle('/assets/images/png/ford-f350-2dsb.jpg')"><img src="/assets/images/png/ford-f350-2dsb.jpg" style="width:auto;height:80px;"></a></span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Ford+F-350+2+door+Short+Bed');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Ford F-350 4 door Short Bed</td>
                        <td>
                            <span><a href="javascript:morevehicle('/assets/images/png/ford-f350-4dsb.jpg')"><img src="/assets/images/png/ford-f350-4dsb.jpg" style="width:auto;height:80px;"></a></span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Ford+F-350+4+door+Short+Bed');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Ford F-350 2 door Long Bed</td>
                        <td>
                            <span><a href="javascript:morevehicle('/assets/images/png/ford-f350-2dlb.jpg')"><img src="/assets/images/png/ford-f350-2dlb.jpg" style="width:auto;height:80px;"></a></span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Ford+F-350+4+door+Long+Bed');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Ford F-350 4 door Long Bed</td>
                        <td>
                            <span><a href="javascript:morevehicle('/assets/images/png/ford-f350-4dlb.jpg')"><img src="/assets/images/png/ford-f350-4dlb.jpg" style="width:auto;height:80px;"></a></span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Ford+F-350+4+door+Long+Bed');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Ford F-450 Super Duty Dually Extra 2 wheel in back</td>
                        <td>
                            <span><a href="javascript:morevehicle('/assets/images/png/ford-f450-sddde2w-1.jpg')"><img src="/assets/images/png/ford-f450-sddde2w-1.jpg" style="width:auto;height:80px;"></a></span>
                            <span><a href="javascript:morevehicle('/assets/images/png/ford-f450-sddde2w-2.jpg')"><img src="/assets/images/png/ford-f450-sddde2w-2.jpg" style="width:auto;height:80px;padding-left:5px;"></a></span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Ford+F-450+Super+Duty+Dually+Extra+2+wheel+in+back');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Ford F-550 Super Duty Dually Extra 2 wheel in back without Box Truck</td>
                        <td>
                            <span><a href="javascript:morevehicle('/assets/images/png/ford-f550-sddde2wwbt.jpg')"><img src="/assets/images/png/ford-f550-sddde2wwbt.jpg" style="width:auto;height:80px;"></a></span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Ford+F-550+Super+Duty+Dually+Extra+2+wheel+in+back+without+Box+Truck');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="tx-bold">Small Box Truck Dually</td>
                        <td>Ford F-550</td>
                        <td>
                            <span><a href="javascript:morevehicle('/assets/images/png/ford-f550-wb.png')"><img src="/assets/images/png/ford-f550-wb.png" style="width:auto;height:80px;"></a></span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Ford+F-550+Small+Box+Truck+Dually');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="tx-bold">Large Box Truck Dually 24ft.</td>
                        <td>Ford F-550</td>
                        <td>
                            <span><a href="javascript:morevehicle('/assets/images/png/ford-f550-24ft.jpg')"><img src="/assets/images/png/ford-f550-24ft.jpg" style="width:auto;height:80px;"></a></span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Ford+F-550+Large+Box+Truck+Dually+24ft');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div><!-- section-wrapper -->


        </div><!-- container -->


    </div>


    <style>
        .pd-l-20 {
            padding-left: 20px;
        }
        .mg-t-20 {
            margin-top: 20px;}
        a{
            color: #1b84e7;
        }
        .tx-20 {
            font-size: 20px !important;
        }
        .newordertab {
            font-size: 40px;
            position: absolute;
            top: -20px;
            right: 0;
        }
        .badge-amber {
            background: #FF6F00;
        }
        .badge-teal {
            background: #004D40;
        }
        .badge-pink {
            background: #E91E63;
        }
        .section-wrapper {
            border: 1px solid #ced4da;
            background-color: #fff;
            padding: 20px;
        }
        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #343a40;
            text-transform: uppercase;
            margin-top: 20px;
            display: block;
            letter-spacing: 1px;
        }
        .mg-sm-b-10 {
            margin-bottom: 10px;
        }
        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
        }
        .table-reference {
            margin-bottom: 0;
        }
        .table-reference thead tr th, .table-reference thead tr td {
            background-color: transparent;
            border: 1px solid #ced4da;
            border-bottom: 0;
        }
        .table-reference tbody tr th, .table-reference tbody tr td {
            border: 1px solid #ced4da;
        }
        .table th, .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .badge-success {
            color: #fff;
            background-color: #23BF08;
        }
        .badge {
            display: inline-block;
            padding: 0.25em 0.4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 3px;
        }
        .wd-30p {
            width: 30%;
        }
        .wd-70p {
            width: 70%;
        }
        .newordertab {
            font-size: 40px;
            position: absolute;
            top: -20px;
            right: 0;
        }
        .badge-amber {
            background: #FF6F00;
        }
        .badge-teal {
            background: #004D40;
        }
        .badge-pink {
            background: #E91E63;
        }
        .slim-pagetitle {
            margin-top: 15px;
            margin-bottom: 10px;
            color: #343a40;
            padding-left: 10px;
            border-left: 4px solid #4662D4;
            text-transform: uppercase;
            font-weight: 700;
            font-size: 18px;
            line-height: 18px;
            letter-spacing: .5px;
        }
        .tx-inverse {
            color: #343a40;
        }
        .mg-b-20 {
            margin-bottom: 20px;
        }
        .slim-pageheader {
            padding: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-direction: row-reverse;
        }
        .pd-40 {
            padding: 40px;
        }

        .card-info {
            text-align: center;
        }
        .mg-b-30 {
            margin-bottom: 30px;
        }
    </style>


@endsection

@section('extraScript')

    <script>
        function morevehicle(url) {
            timewindow=window.open(url,'Map','height=700,width=800,left=200,top=50,toolbar=No,location=No,scrollbars=Yes,status=No,resizable=Yes,fullscreen=No,directories=No,menubar=No,copyhistory=No');
            if (window.focus) {timewindow.focus()}
        }
    </script>

@endsection

