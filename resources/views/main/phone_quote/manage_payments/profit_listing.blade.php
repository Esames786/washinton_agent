@extends('layouts.innerpages')

@section('template_title')
    Profit
    @endsection

    @section('content')
            <!-- Row -->
    @include('partials.mainsite_pages.return_function')
    <div class="page-header">
        <!--<div class="page-leftheader">-->
        <!--    <h4 class="page-title mb-0">DAY COUNT</h4>-->
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Tables</a>-->
        <!--        </li>-->
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">DAY COUNT</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <!--<div class="page-rightheader">-->
        <!--    <div class="btn btn-list">-->


        <!--    </div>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Profit Listing</b></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            @if(session('flash_message'))
                <div class="alert alert-success">
                    {{session('flash_message')}}
                </div>
                @endif
                        <!--div-->
                <div class="card mt-5">
                    <div class="card-header">
                        <div class="card-title">
                            <a type="button" href="{{url('manage_payments')}}"
                                                   class="btn btn-icon btn-primary">All Completed Orders
                                <i class="fe fe-plus"></i></a>
                            <a type="button" href="{{url('payment_recieved')}}"
                               class="btn btn-icon btn-success">Payment Received
                                <i class="fe fe-plus"></i></a>
                            <a type="button" href="{{url('employee_order')}}"
                               class="btn btn-icon btn-success">Employee Order
                                <i class="fe fe-plus"></i></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped text-nowrap key-buttons">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">ORDER ID</th>
                                        <th class="border-bottom-0">PROFIT</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $data as $val)
                                        <tr>
                                            <td>{{$val->id}}</td>
                                            <td>{{$val->profit}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/div-->

        </div>
    </div>
    <!-- /Row -->



@endsection

@section('extraScript')


@endsection

