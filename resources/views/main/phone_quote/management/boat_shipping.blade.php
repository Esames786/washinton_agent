@extends('layouts.innerpages')

@section('template_title')
    Boat Shipping
@endsection
@include('partials.mainsite_pages.return_function')


@section('content')


    <div class="slim-mainpanel">
        <div class="container">


            <div class="slim-pageheader pl-0">
                <ol class="breadcrumb slim-breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/guides/">Guides</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Boat Shipping</li>
                </ol>
                <h6 class="slim-pagetitle">Boat Shipping Guide</h6>
            </div><!-- slim-pageheader -->

            <div class="section-wrapper">
                <label class="section-title tx-30">Dimensions</label>

                <label class="section-title mt-10">Before we give any quote to our customer first, we need Boat Dimension</label>
                <ol class="mg-b-20 mg-sm-b-10 tx-15 tx-black">
                    <li>Length</li>
                    <li>Width</li>
                    <li>Height</li>
                    <li>Total Boat weight</li>
                    <li>Boat Picture (Some time necessary depend on Boat)</li>
                </ol>

                <img src="/assets/images/png/boat-dimensions.png" style="width: 100%;">

                <p class="mg-b-10 mg-sm-b-10 pt-5 tx-black">and also, boat have to be on Trolley / Trailer because Boat always ship via Tow Away service.</p>

                <img src="/assets/images/png/shipping-boat.jpg" style="width: 50%;">

            </div><!-- section-wrapper -->

            <div class="section-wrapper mg-t-20">
                <label class="section-title tx-20">WHAT IS TROLLEY / TRAILER?</label>

                <p class="mg-b-10 mg-sm-b-10 tx-black">Boat Trolley / Trailer are used by  boat yards, boat haulers, boat dealers and boat builders. Generally, this type of Trolley / Trailer is used for shipping / tow away a boat from one location to another.</p>

                <img src="/assets/images/png/boat-trolley.png" style="width: 50%;">

                <label class="section-title tx-20">WHAT IS TOW AWAY SERVICE?</label>

                <p class="mg-b-10 mg-sm-b-10 tx-black">A Pickup truck Pull / Tow Away another vehicle or boat along with a rope, chain, tow bar or Boat on trolley / trailer called Tow Away Service.</p>
                <br>
                <img src="/assets/images/png/boat-tow-away.jpg" style="width: 50%;">
            </div><!-- section-wrapper -->

            <div class="section-wrapper mg-t-20">
                <label class="section-title tx-30">Scheduling</label>

                <label class="section-title mt-10">BOAT SCHEDULING</label>
                <p class="mg-b-10 mg-sm-b-10 tx-black">Dispatcher must need Length, Width, Height, Total boat weight and Boat Picture (Some time necessary depend on equipment) also Boat have to be on Trolley / Trailer to schedule the Boat. If they don’t have complete info, they will not schedule the Boat. Also, must include this info in Schedule sheet.<br>
                    <strong>Please keep in mind this info is must needed if something went missing/Change in Boat dimension this will directly affect the Boat shipping price. </strong>
                </p>
            </div><!-- section-wrapper -->

        </div><!-- container -->

    </div>



<style>
    .tx-20 {
        font-size: 20px;
    }
    .mg-t-20 {
        margin-top: 20px !important;
    }
    .tx-30 {
        font-size: 30px !important;
    }
    .tx-15 {
        font-size: 15px !important;
    }
    .tx-black {
        color: #000 !important;
        font-weight: 900 !important;
    }
    
    .section-title {
        margin-top: 40px;
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

