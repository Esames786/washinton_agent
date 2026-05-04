@extends('layouts.innerpages')

@section('template_title')
   Tags Guide
@endsection
@include('partials.mainsite_pages.return_function')


@section('content')



    <div class="slim-mainpanel" style=" padding-left: 0px !important; ">
        <div class="container">
            <div class="slim-pageheader pl-0">
                <ol class="breadcrumb slim-breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/guides/">Guides</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Non-luxury Vehicle</li>
                </ol>
                <h6 class="slim-pagetitle">Non-luxury Vehicle Guide</h6>
            </div><!-- slim-pageheader -->

            <div class="section-wrapper">
                <h1 class="text-primary">    Non-LUXURY VEHICLE</h1>
                <div>
                    <p>• NON-LUXURY VEHICLES YEAR FROM (2016-CURRENT OF YEARS OF VEHICLE) MUST HAVE A (C O I) CERTIFICATE OF INSURANCE BEFORE DISPATCH</p>
                    
                    <p>• Dispatchers who dispatch non-luxury vehicles before 2016 must verify the driver's information from FMCSA and take a (FMCSA) screen shot on the same date of dispatch date.</p>
                    
                    <p> <strong>NOTE</strong>: It is important that dispatchers follow the guideline. If someone violates the guideline, the management can take action against them.</p>
                </div>
            </div>
            <div class="section-wrapper">
                <label class="section-title">Non-Luxury Vehicle</label>
                <p class="mg-b-10 mg-sm-b-10">Below are the makes of luxury vehicle</p>

                <table class="table table-reference">
                    <thead>
                    <tr>
                        <th class="wd-70p"><strong>Non-luxury vehicle</strong></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>ACURA </td>
                    </tr>
                    <tr>
                        <td>DODGE</td>
                    </tr>
                    <tr>
                     <td>EAGLE </td>
                    </tr>
                    <tr>
                        <td>FORD </td>
                    </tr>
                      <tr>
                        <td>HUMMER </td>
                    </tr>
                      <tr>
                        <td>GEO </td>
                    </tr>
                      <tr>
                        <td>GMC</td>
                    </tr>
                     <tr>
                        <td>HONDA</td>
                    </tr>
                     <tr>
                        <td>DUCATI </td>
                    </tr>
                     <tr>
                        <td>JEEP </td>
                    </tr>
                     <tr>
                        <td>HYUNDAI</td>
                    </tr>
                     <tr>
                        <td>INDIAN </td>
                    </tr>
                     <tr>
                        <td>INFINITI</td>
                    </tr>
                      <tr>
                        <td>ISUZU</td>
                    </tr>
                     <tr>
                        <td>AMERICAN </td>
                    </tr>
                     <tr>
                        <td>POLARIS</td>
                    </tr>
                     <tr>
                        <td>PONTIAC </td>
                    </tr>
                     <tr>
                        <td>MINI </td>
                    </tr>
                     <tr>
                        <td>MITSUBISHI </td>
                    </tr>
                     <tr>
                        <td>NISSAN</td>
                    </tr>
                     <tr>
                        <td>OLDSMOBILE </td>
                    </tr>
                     <tr>
                        <td>DAIHATSU</td>
                    </tr>
                     <tr>
                        <td>DATSUN </td>
                    </tr>
                     <tr>
                        <td>KAWASAKI  </td>
                    </tr>
                     <tr>
                        <td>KIA </td>
                    </tr>
                     <tr>
                        <td>LEXUS </td>
                    </tr>
                     <tr>
                        <td>LINCOLN  </td>
                    </tr>
                     <tr>
                        <td>MAZDA </td>
                    </tr>
                     <tr>
                        <td>PLYMOUTH  </td>
                    </tr>
                     <tr>
                        <td>BUICK  </td>
                    </tr>
                     <tr>
                        <td>CADILLAC  </td>
                    </tr>
                     <tr>
                        <td>CHEVROLET  </td>
                    </tr>
                     <tr>
                        <td>DAEWOO  </td>
                    </tr>
                     <tr>
                        <td>SAAB  </td>
                    </tr>
                     <tr>
                        <td>SATURN  </td>
                    </tr>
                     <tr>
                        <td>SCION  </td>
                    </tr>
                    <tr>
                        <td>SCOOTER   </td>
                    </tr>
                    <tr>
                        <td>SUBARU   </td>
                    </tr>
                    <tr>
                        <td>SUZUKI   </td>
                    </tr>
                    <tr>
                        <td>TOYOTA    </td>
                    </tr>
                    <tr>
                        <td>TRIUMPH    </td>
                    </tr>
                    <tr>
                        <td>VOLKSWAGEN     </td>
                    </tr>
                    <tr>
                        <td>VOLVO    </td>
                    </tr>
                    <tr>
                        <td>YAMAHA     </td>
                    </tr>
                    
                    </tbody>
                </table>
            </div>



        </div><!-- container -->


    </div>

    <style>
        a{
            color: #007bff;
        }
        .tx-white{
            color: white !important;
        }
        .badge-orange {
            color: #212529;
            background-color: #F49917;
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

