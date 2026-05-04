@extends('layouts.innerpages')

@section('template_title')
    {{  ucfirst(trim("$_SERVER[REQUEST_URI]",'/'))}}
@endsection
@section('content')
    @include('partials.mainsite_pages.return_function')
    <style>
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
        #table_data th, #table_data td {
    max-width: 0 !important;
}
    </style>
    <!--/app header-->                                                <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <input type="hidden" value="{{trim("$_SERVER[REQUEST_URI]",'/')}}" id="titlee">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">New Order</a></li>
            </ol>
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
    <div class="row">
        <div class="col-12">
            @if(session('flash_message'))
                <div class="alert alert-success">
                    {{session('flash_message')}}
                </div>
        @endif
        <!--div-->
            <div class="card">
                <div class="card-header">
                    <div class="container-fluid">
                    </div>
                </div>
                <div class="card-body">
                    <div id="table_data">
                        <div class="table-responsive">
                            {{--example1--}}
                            <table class="table table-bordered table-sm" style="width:100%" id="" role="grid"
                                   aria-describedby="example1_info">
                                <thead>
                                <tr>
                                    <th class="border-bottom-0">Order ID#</th>
                                    <th class="border-bottom-0">Booked Date</th>
                                    <th class="border-bottom-0">Status</th>
                                    <th class="border-bottom-0">Location</th>
                                    <th class="border-bottom-0">Booked Price</th>
                                    <th class="border-bottom-0">Dispatch Price</th>
                                    <th class="border-bottom-0">Profit</th>
                                    <th class="border-bottom-0">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $key => $val)
                                    <tr>
                                        <td>{{ $val->id }}</td>
                                        <td>{{ $val->date_of_booked }}</td>
                                        <td>{{ get_pstatus($val->pstatus) }}</td>
                                        <td>{{ $val->originstate . ' to ' . $val->destinationstate }}</td>
                                        <td>{{ $val->payment }}</td>
                                        <td>{{ $val->listed_price }}</td>
                                        <td>{{ !empty($val->payment) && !empty($val->listed_price) ? $val->payment - $val->listed_price : 0 }}</td>
                                        <td>
                                            <a type="button" href="{{ route('payment_system.edit', $val->id) }}" class="btn btn-outline-info btn-sm w-100" data-toggle="tooltip" data-placement="top"
                                                    title="Edit Data!">
                                                    Edit <i class="fa fa-edit " style="color: white;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{  $data->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end app-content-->
@endsection

@section('extraScript')

@endsection


