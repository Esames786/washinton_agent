@extends('layouts.innerpages')

@section('template_title')
    Trailers
@endsection
@include('partials.mainsite_pages.return_function')


@section('content')


    <div class="slim-mainpanel">
        <div class="container">


            <div class="slim-pageheader  pl-0">
                <ol class="breadcrumb slim-breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/guides/">Guides</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Trailers</li>
                </ol>
                <h6 class="slim-pagetitle">Trailers We Used To Ship</h6>
            </div><!-- slim-pageheader -->

            <div class="section-wrapper">
                <label class="section-title tx-20">A.	9 Car Hauler / Open Transport</label>
                <p class="mg-b-10 mg-sm-b-10 tc-15 tx-black">This trailer used for normal vehicle or in normal condition.</p>
                <img src="/assets/images/png/a-open-transport.jpg" style="width: 50%;">

                <label class="section-title tx-20">B.	Enclosed Transport</label>
                <p class="mg-b-10 mg-sm-b-10 tc-15 tx-black">This service is used for Luxury, Expensive, antique and for modified vehicles.</p>
                <img src="/assets/images/png/b-enclosed.jpg" style="width: 50%;">

                <label class="section-title tx-20">C.	2, 3 Car haulers</label>
                <p class="mg-b-10 mg-sm-b-10 tc-15 tx-black">This type of trailer used for modified vehicles, Wreck vehicles or for those who can’t load in 9 car haulers.</p>
                <img src="/assets/images/png/c-2-3-car-hauler.jpg" style="width: 50%;">

                <label class="section-title tx-20">D.	Flatbed Trailer</label>
                <p class="mg-b-10 mg-sm-b-10 tc-15 tx-black">This service is used for heavy loads like tractor, Forklift, big trucks etc.</p>
                <img src="/assets/images/png/d-flatbed-trailer.png" style="width: 50%;">

                <label class="section-title tx-20">E.	Tow Truck</label>
                <p class="mg-b-10 mg-sm-b-10 tc-15 tx-black">Tow trucks is used to tow vehicle from 1 place to another and some time to load or unload the vehicle.</p>
                <img src="/assets/images/png/e-towtruck.jpg" style="width: 50%;">

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

    </script>

@endsection

