@extends('layouts.innerpages')

@section('template_title')
    Vehicle Conditions
@endsection
@include('partials.mainsite_pages.return_function')


@section('content')


    <div class="slim-mainpanel">
        <div class="container">


            <div class="slim-pageheader pl-0">
                <ol class="breadcrumb slim-breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/guides/">Guides</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Vehicle Conditions</li>
                </ol>
                <h6 class="slim-pagetitle">Vehicle Conditions Guide</h6>
            </div><!-- slim-pageheader -->

            <div class="section-wrapper">
                <label class="section-title tx-30">Modifications</label>

                <label class="section-title mt-10">1.	Modification in Sedan</label>
                <p class="mg-b-10 mg-sm-b-10 tx-black">Customer usually lower the ground clearance of sedan if they do so and vehicle sit under or equal 4 inches, we prefer Enclosed Transport to load and unload the vehicle without any damage. Some time they only modified the engine or interior which will not affect the price.
                    <br><br>
                    If customer said vehicle is modified ask what type of modification is done in vehicle? If customer say ground clearance is lower than usual ask does vehicle ground clearance is above 4 inches or under or equal 4 inches if customer say above price will remain same if he says lower, we prefer Enclosed Transport to load and unload the vehicle without any damage. Some time they only modified the engine or interior which will not affect the price.
                </p>
                <div class="row">
                    <div class="col-6">
                        <img src="/assets/images/png/coupe-modified.jpg" style="width: 100%;">
                    </div>
                    <div class="col-6">
                        <img src="/assets/images/png/sedan-modified.jpg" style="width: 100%;">
                    </div>
                </div>

                <label class="section-title mt-20">2.	Modification in Pickup truck</label>
                <p class="mg-b-10 mg-sm-b-10 tx-black">Mostly customer increase the truck height or installed big tires because of this pickup truck couldn’t load in Open Trailer, whenever truck height exceeds 7ft or 7.1ft we need Flatbed trailer. But if modification didn’t impact the Height price will remain same but please also consider the Series of pick up if series goes up price will also go up.
                    <br><br>
                    If customer said vehicle is modified ask what type of modification is done in vehicle? If customer say I have installed big tires or raise the height to example (2ft) ask does overall height is above 7ft or under 7ft if customer said under 7ft $25 to $100 will be increase in normal price depend on vehicle series, Total weight and shipping Miles. If he said vehicle is above 7ft tell him we need Flatbed trailer to load the vehicle, Flatbed usually cost around $1.10 to 2.85 per mile with respect to vehicle series, miles and including total weight of vehicle. Some time they only modified the engine or interior which will not affect the price.
                </p>
                <div class="row">
                    <div class="col-6">
                        <img src="/assets/images/png/modified-pickup.jpg" style="width: 100%;">
                    </div>
                    <div class="col-6">
                        <img src="/assets/images/png/modified-pickup-2.jpg" style="width: 100%;">
                    </div>
                </div>

            </div><!-- section-wrapper -->

            <div class="section-wrapper mg-t-20">
                <label class="section-title tx-30">Convertibles</label>

                <label class="section-title mt-10">What are convertible vehicles (Hard-top & Soft-top)?</label>
                <p class="mg-b-10 mg-sm-b-10 tx-black">Mostly convertible are in Coupe category and they have 2 types of top 1st Hard-top and 2nd Soft-top.</p>
                <div class="row mt-10 mb-10">
                    <div class="col-6">
                        <img src="/assets/images/png/convert-1.jpg" style="width: 100%;">
                    </div>
                    <div class="col-6">
                        <img src="/assets/images/png/convert-2.jpg" style="width: 100%;">
                    </div>
                </div>
                <p class="mg-b-10 mg-sm-b-10 tx-black">Soft-top can easily damage by wind while shipping so extra care is needed by drivers and need extra $25 to $50 in normal price.</p>

            </div><!-- section-wrapper -->

            <div class="section-wrapper mg-t-20">
                <label class="section-title tx-30">Non-Running</label>
                <label class="section-title mt-10">Reasons of Non-running Vehicles</label>
                <table class="table table-reference">
                    <tbody>
                    <tr><td class="wd-30p tx-bold">A.	Engine</td> <td class="wd-70p">(If engine don’t start but vehicle can roll, Break and steer we can roll down it)</td></tr>
                    <tr><td class="wd-30p tx-bold">B.	Transmission</td> <td class="wd-70p">(If transmission problem but vehicle can roll, Break and steer we can roll down it)</td></tr>
                    <tr><td class="wd-30p tx-bold">C.	Suspension Front</td> <td class="wd-70p">(We need for forklift to load & unload)</td></tr>
                    <tr><td class="wd-30p tx-bold">D.	Suspension Back</td> <td class="wd-70p">(We need for forklift to load & unload)</td></tr>
                    <tr><td class="wd-30p tx-bold">E.	Wind shield Damage</td> <td class="wd-70p">(If vehicle wind shield is damage but vehicle can roll, Break and steer we can roll down it)</td></tr>
                    <tr><td class="wd-30p tx-bold">F.	Rims tilted / tires flat / tire missing & can’t be fix prior to pick up</td> <td class="wd-70p">(We need for forklift to load & unload)</td></tr>
                    <tr><td class="wd-30p tx-bold">G.	Vehicle key is Missing</td> <td class="wd-70p">(We need for forklift to load & unload)</td></tr>
                    <tr><td class="wd-30p tx-bold">H.	Accident vehicle</td> <td class="wd-70p">(First see what type of damages in vehicle and also consider points mention above)</td></tr>
                    <tr><td class="wd-30p tx-bold">I.	Flood vehicle</td> <td class="wd-70p">(If vehicle can roll, Break and steer we can roll down it)</td></tr>
                    <tr><td class="wd-30p tx-bold">J.	If vehicle don’t Roll or steer</td> <td class="wd-70p">(We need for forklift to load & unload)</td></tr>
                    <tr><td class="wd-30p tx-bold">K.	Vehicle Break</td> <td class="wd-70p">(If vehicle breaks didn’t work but emergency/parking brake are fine we can roll down it)</td></tr>

                    </tbody>
                </table>

                <label class="section-title mt-10 tx-20">WHAT IS WINCH & WHEN WE USE WINCH?</label>
                <p class="mg-b-10 mg-sm-b-10 tx-black">When vehicle is in running condition driver load & unload the vehicle by drive it on/off the trailer. But when vehicle was not in running condition the first question, we ask from our customer is “Does your vehicle roll, Break and steer?” </p>

                <ol class="mg-b-20 mg-sm-b-10 tx-15 tx-black">
                    <li><strong>Roll</strong> (Roll mean does vehicle tries moves freely)</li>
                    <li><strong>Steer</strong> (Steer mean vehicle steering that can control the vehicle wheel by steering)</li>
                    <li><strong>Break</strong> (Break is normal break we used in our motorcycle Or in Cars, if vehicle break not work perfectly driver can also use Vehicle emergency/Parking breaks)</li>
                </ol>

                <div class="row">
                    <div class="col-6">
                        <img src="/assets/images/png/winch-1.jpg" style="width: 80%;">
                    </div>
                    <div class="col-6">
                        <img src="/assets/images/png/winch-2.jpg" style="width: 100%;">
                    </div>
                </div>

                <p class="mg-b-10 mg-sm-b-10 tx-black">If he said yes “vehicle roll, Break and steer”, we can winch it, winch is hauling or lifting device consisting of a rope or chain winding round a horizontal rotating drum, turned typically by a crank or by motor. Driver usually charge extra $50 to $100 for winch depend on Vehicle and total miles of shipment.</p>

                <p class="mg-b-10 mg-sm-b-10 tx-black">But in case customer said No “vehicle doesn’t roll, Break and steer or it doesn’t roll / steer” Customer had to arrange forklift on both ends to load and unload, arranging the forklift is customer reasonability not ours. If vehicle pick up from Auction like Copart, iaai or manheim they offer forklift and customer usually arrange tow truck or forklift on delivery. Also, there are two types of fork lift Normal forklift and big forklift.</p>

                <label class="section-title mt-10">A.	<strong>Normal Forklift</strong> (Normal Forklift is use to load and unload normal vehicle)</label>

                <img src="/assets/images/png/winch-3.jpg" style="width: 40%;">
                <br><br>
                <label class="section-title mt-10">B.	<strong>Big Forklift</strong> (Big Forklift is use to load and unload heavy vehicle or lifted vehicle like Ford F-450, 550 etc.)</label>

                <img src="/assets/images/png/winch-4.jpg" style="width: 50%;">

            </div><!-- section-wrapper -->


        </div><!-- container -->


    </div>



    <style>
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

    </script>

@endsection

