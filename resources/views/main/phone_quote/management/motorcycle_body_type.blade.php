@extends('layouts.innerpages')

@section('template_title')
    Motorcycle Body Type
@endsection
@include('partials.mainsite_pages.return_function')


@section('content')


    <div class="slim-mainpanel">
        <div class="container">


            <div class="slim-pageheader pl-0">
                <ol class="breadcrumb slim-breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/guides/">Guides</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Motorcycle Body Type</li>
                </ol>
                <h6 class="slim-pagetitle">Motorcycle Body Type Guide</h6>
            </div><!-- slim-pageheader -->

            <div class="section-wrapper">
                <label class="section-title">Motorcycle Type</label>
                <p class="mg-b-10 mg-sm-b-10">These are the suggested body types that are mostly used to ship.</p>

                <table class="table table-reference">
                    <thead>
                    <tr>
                        <th class="wd-20p">Type</th>
                        <th class="wd-30p">Description</th>
                        <th class="wd-50p">Preview</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="tx-bold">Mopeds / Scooter</td>
                        <td>A moped is a scooter with bicycle pedals, generally having a less stringent licensing requirement than motorcycles or automobiles because mopeds typically travel only a bit faster than bicycles on public roads. Mopeds by definition are driven by both an engine and bicycle pedals.</td>
                        <td>
                    <span>
                        <a href="javascript:morevehicle('/assets/images/png/moped-2.jpg')"><img src="/assets/images/png/moped-2.jpg" style="width:auto;height:120px;"></a>
                        <a href="javascript:morevehicle('/assets/images/png/moped-1.jpg')"><img src="/assets/images/png/moped-1.jpg" style="width:auto;height:120px;" class="pd-l-10"></a>
                    </span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Mopeds');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="tx-bold">Standard Motorcycle</td>
                        <td>Standards, also called naked bikes or roadsters, are versatile, general-purpose street motorcycles. They are recognized primarily by their upright riding position.</td>
                        <td>
                    <span>
                        <a href="javascript:morevehicle('/assets/images/png/standard.jpg')"><img src="/assets/images/png/standard.jpg" style="width:auto;height:100px;"></a>
                    </span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Standard+Motorcycle');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="tx-bold">Heavy Bikes / Sports Bike</td>
                        <td>Sport bikes emphasize top speed, acceleration, braking, handling and grip on paved roads, typically at the expense of comfort and fuel economy in comparison to less specialized motorcycles. Sport bikes have comparatively high-performance engines resting inside a lightweight frame.</td>
                        <td>
                    <span>
                        <a href="javascript:morevehicle('/assets/images/png/heavybike-1.jpg')"><img src="/assets/images/png/heavybike-1.jpg" style="width:auto;height:80px;"></a>
                        <a href="javascript:morevehicle('/assets/images/png/heavybike-2.jpg')"><img src="/assets/images/png/heavybike-2.jpg" style="width:auto;height:80px;" class="pd-l-10"></a>
                    </span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Heavy+Bikes');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="tx-bold">3-Wheeler</td>
                        <td>A three-wheeler is a vehicle with three wheels. Some are motorized tricycles, which may be legally classed as motorcycles.</td>
                        <td>
                    <span>
                        <a href="javascript:morevehicle('/assets/images/png/3-wheeler.jpg')"><img src="/assets/images/png/3-wheeler.jpg" style="width:auto;height:100px;"></a>
                    </span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=3-Wheeler+Motorcycle');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="tx-bold">Motorcycle with Side Car</td>
                        <td>A sidecar is a one-wheeled device attached to the side of a motorcycle, scooter, or bicycle, producing a three-wheeled vehicle.</td>
                        <td>
                    <span>
                        <a href="javascript:morevehicle('/assets/images/png/sidecar-1.jpg')"><img src="/assets/images/png/sidecar-1.jpg" style="width:auto;height:120px;"></a>
                        <a href="javascript:morevehicle('/assets/images/png/sidecar-3.jpg')"><img src="/assets/images/png/sidecar-3.jpg" style="width:auto;height:100px;" class="pd-l-10"></a>
                    </span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Motorcycle+with+Side+Car');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="tx-bold">ATV (All-terrain vehicle)</td>
                        <td>An all-terrain vehicle (ATV), also known as a quad, quad bike, four-wheeler or quadricycle as defined by the American National Standards Institute (ANSI) is a vehicle that travels on low-pressure tires, with a seat that is straddled by the operator, along with handlebars for steering control.</td>
                        <td>
                    <span>
                        <a href="javascript:morevehicle('/assets/images/png/atv.png')"><img src="/assets/images/png/atv.png" style="width:auto;height:130px;"></a>
                    </span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=ATV');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="tx-bold">UTV’s</td>
                        <td>The side-by-side (often written as SxS) is a small 2- to 6-person four-wheel drive off-road vehicle, also called UTV (utility vehicle or utility task vehicle), a ROV (recreational off-highway vehicle), or a MOHUV (multipurpose off-highway utility vehicle)</td>
                        <td>
                    <span>
                        <a href="javascript:morevehicle('/assets/images/png/utv-1.jpg')"><img src="/assets/images/png/utv-1.jpg" style="width:auto;height:150px;"></a>
                    </span>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="tx-bold">1.	2-Seater</td>
                        <td>
                    <span>
                        <a href="javascript:morevehicle('/assets/images/png/utv-2.jpg')"><img src="/assets/images/png/utv-2.jpg" style="width:auto;height:100px;"></a>
                    </span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=UTV+2-Seater');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="tx-bold">2.	4-Seater</td>
                        <td>
                    <span>
                        <a href="javascript:morevehicle('/assets/images/png/utv-3.jpg')"><img src="/assets/images/png/utv-3.jpg" style="width:auto;height:100px;"></a>
                    </span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=UTV+4-Seater');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="tx-bold">Golf Cart’s</td>
                        <td>A golf cart is a small vehicle designed originally to carry two, four or Six golfers and their golf clubs around a golf course or on desert trails with less effort than walking.</td>
                        <td>
                    <span>
                        <a href="javascript:morevehicle('/assets/images/png/golf-main.jpg')"><img src="/assets/images/png/golf-main.jpg" style="width:auto;height:100px;"></a>
                    </span>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="tx-bold">1.	2-Seater</td>
                        <td>
                    <span>
                        <a href="javascript:morevehicle('/assets/images/png/golf-1.jpg')"><img src="/assets/images/png/golf-1.jpg" style="width:auto;height:100px;"></a>
                    </span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Golf+Cart+2-Seater');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="tx-bold">2.	4-Seater</td>
                        <td>
                    <span>
                        <a href="javascript:morevehicle('/assets/images/png/golf-2.jpg')"><img src="/assets/images/png/golf-2.jpg" style="width:auto;height:100px;"></a>
                    </span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Golf+Cart+4-Seater');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="tx-bold">2.	6-Seater</td>
                        <td>
                    <span>
                        <a href="javascript:morevehicle('/assets/images/png/golf-3.png')"><img src="/assets/images/png/golf-3.png" style="width:auto;height:100px;"></a>
                    </span>
                            <span class="pd-l-20"><a href="javascript:morevehicle('http://images.google.com/images?q=Golf+Cart+6-Seater');">View More <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></span>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div><!-- section-wrapper -->


        </div><!-- container -->

    </div>



    <style>
        a{
            color: #1b84e7;
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

