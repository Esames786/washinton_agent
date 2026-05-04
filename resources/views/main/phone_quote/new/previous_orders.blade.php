<!DOCTYPE html>
<html lang="en" dir="ltr">
<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<head>
    @include('partials.mainsite_pages.head')
</head>
<body class="app sidebar-mini">
<style>
    .fa:hover {
        color: black !important;
    }

    .nav-tabs .nav-link.active:hover {
        color: rgb(255 255 255);
        background: #20223c;
        size: 50px;
        width: 100%;
        height: 44px;
    }

    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1040;
        width: 108vw !important;
        height: 108vh !important;
        background-color: #000;
    }

    .app-sidebar__toggle {
        visibility: hidden !important;
    }

    select.form-control:not([size]):not([multiple]) {
        height: 28px;
    }

    input[type='radio']:after {
        width: 15px;
        height: 15px;
        border-radius: 15px;
        top: -4px;
        left: -1px;
        position: relative;
        background-color: #d1d3d1;
        content: '';
        display: inline-block;
        visibility: visible;
        border: 2px solid white;
    }

    input[type='radio']:checked:after {
        width: 20px;
        height: 20px;
        border-radius: 100px;
        top: -2px;
        left: -6px;
        position: relative;
        background-color: rgb(23 162 184);
        content: '';
        display: inline-block;
        visibility: visible;
        border: 2px solid white;
    }

    .table {
        color: rgb(0 0 0);
        width: 100%;
        max-width: 100%;
        margin-bottom: 1rem;
        font-weight: 500;
    }

    .table-bordered, .text-wrap table, .table-bordered th, .text-wrap table th, .table-bordered td, .text-wrap table td {
        border: 1px solid rgb(0 0 0);
    }

    .table > thead > tr > td, .table > thead > tr > th {
        font-weight: 500;
        -webkit-transition: all .3s ease;
        font-size: 18px;
        color: rgb(0 0 0);
    }
</style>
<div class="page">
    <div class="page-main">
        <div class="app-content main-content">
            <div class="side-app">
                @include('partials.mainsite_pages.return_function')
                <div class="row">
                    <div class="col-12">
                        <!--div-->
                        <div class="card">
                            <div class="card-body">
                                <div id="table_data">
                                    <div class="table-responsive">
                                        {{--example1--}}
                                        @if(\Request::is('rates_shipa1'))
                                            <table class="table table-bordered table-sm" style="width:100%" id=""
                                                   role="grid"
                                                   aria-describedby="example1_info">
                                                <thead>
                                                <tr>
                                                    <th>Mileage Range</th>
                                                    <th>Highway per mile</th>
                                                    <th>Total Distance Cover</th>
                                                    <th>Cost</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    @if($results)
                                                    <tr>
                                                        <td>{{ $results->milagerange }}</td>
                                                        <td>{{ $results->mivalue }}</td>
                                                        <td>{{ $results->distance }}</td>
                                                        <td>{{ $results->mivalue * $results->distance }}</td>
                                                    </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        @else
                                            <table class="table table-bordered table-sm" style="width:100%" id=""
                                                   role="grid"
                                                   aria-describedby="example1_info">
                                                <thead>
                                                <tr>
                                                    <th>Order Id</th>
                                                    <th>Vehicle</th>
                                                    <th>Date</th>
                                                    <th>Origin</th>
                                                    <th>Destination</th>
                                                    @php
                                                        if(request('driver_price')){
                                                            $title = "Driver Price";
                                                        }else{
                                                            $title = "Booked Price";
                                                        }
                                                    @endphp
                                                    <th>{{ $title }}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($results as $row)
                                                    <tr>
                                                        <td>{{ $row->id }}</td>
                                                        <?php $ymk = explode('*^-', $row->ymk); ?>
                                                        <td>
                                                            @foreach($ymk as $val2)
                                                                @if($val2)
                                                                    <span class="badge badge-pill badge-info mt-2 badge-sm">{{$val2}}</span>
                                                                    <br>
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            <span class="text-center pd-2 bd-l"> Created At:<br>{{ date("M-d-y h:i:s a",strtotime($row->created_at))}}</span><br>
                                                            <span class="text-center pd-2 bd-l">Updated At:<br>{{ date("M-d-y h:i:s a",strtotime($row->updated_at))}}</span><br>
                                                        </td>
                                                        <td>{{ $row->originzsc }}</td>
                                                        <td>{{ $row->destinationzsc }}</td>
                                                        @if(request('driver_price')){
                                                        <td>${{ $row->driver_price == 0 ? '0' : $row->driver_price }}</td>
                                                        @else
                                                            <td>${{ $row->payment == 0 ? '0' : $row->payment }}</td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- end app-content-->
            </div>
        </div>
        <!-- End app-content-->
    </div>
</div><!-- End Page -->
</body>
</html>

