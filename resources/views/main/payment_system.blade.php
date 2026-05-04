@extends('layouts.innerpages')

@section('template_title')
    {{  ucfirst(trim("$_SERVER[REQUEST_URI]",'/'))}}
@endsection

@section('content')
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
    </style>
    <!--/app header-->                                                <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            {{--<h4 class="page-title mb-0">{{  ucfirst(trim("$_SERVER[REQUEST_URI]",'/'))}}</h4>--}}
            <input type="hidden" value="{{trim("$_SERVER[REQUEST_URI]",'/')}}" id="titlee">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">New Order</a></li>
            </ol>
        </div>
        {{--<div class="page-rightheader">--}}
        {{--<div class="btn btn-list">--}}
        {{--<a href="#" class="btn btn-info"><i class="fe fe-settings mr-1"></i> Port Sheet Update</a>--}}
        {{--<a href="#" class="btn btn-danger"><i class="fe fe-printer mr-1"></i> Print </a>--}}
        {{--<a href="#" class="btn btn-warning"><i class="fe fe-shopping-cart mr-1"></i> Buy Now </a>--}}
        {{--</div>--}}
        {{--</div>--}}
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
                                    <th class="border-bottom-0">Pickup</th>
                                    <th class="border-bottom-0">Delivery</th>
                                    <th class="border-bottom-0">VEHICLE#/ORDERTAKER<BR></th>
                                    <th class="border-bottom-0">Customer/Payment</th>
                                    <th class="border-bottom-0">Dates</th>
                                    <th class="border-bottom-0">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $val)
                                    <tr>
                                       <td></td>
                                       <td></td>
                                       <td></td>
                                       <td></td>
                                       <td></td>
                                       <td></td>
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


