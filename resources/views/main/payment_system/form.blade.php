@extends('layouts.innerpages')

@section('template_title')
    Edit Payment System
@endsection

@section('content')
@php
    $action = isset($row->id) ? route('payment_system.update',$row->id) : route('payment_system.store') ;
@endphp
    @include('partials.mainsite_pages.return_function')
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">Edit Payment System</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fe fe-layers mr-2 fs-14"></i>Pages</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Edit Payment System</a></li>
            </ol>
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
    <form action="{{ $action }}" id="form" method="POST">
        @method('PUT')
        @csrf
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Edit Payment System</div>
                    </div>
                    <div class="card-body">
                        <div class="card-title font-weight-bold">Basic info:</div>
                        <div id="table_data">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" style="width:100%" id="" role="grid"
                                       aria-describedby="example1_info">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">ID</th>
                                        <th class="border-bottom-0">Booked Date</th>
                                        <th class="border-bottom-0">Order ID#<BR></th>
                                        <th class="border-bottom-0">Location</th>
                                        <th class="border-bottom-0">Booked Price</th>
                                        <th class="border-bottom-0">Dispatch Price</th>
                                        <th class="border-bottom-0">Profit</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $row->id }}</td>
                                            <td>{{ $row->date_of_booked }}</td>
                                            <td>{{ $row->id }}</td>
                                            <td>{{ $row->originstate . ' to ' . $row->destinationstate }}</td>
                                            <td>{{ $row->payment }}</td>
                                            <td>{{ $row->listed_price }}</td>
                                            <td>{{ !empty($row->payment) && !empty($row->listed_price) ? $row->payment - $row->listed_price : 0 }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Recieved Date</label>
                                    <input type="date" value="{{$row->recived_date}}" required name="recived_date"
                                           class="form-control" placeholder="Recieved Date">
                                </div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Recieved By</label>
                                    <input type="text" value="{{$row->recived_by}}" required name="recived_by"
                                           class="form-control" placeholder="Recieved By">
                                </div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Confirmed Date</label>
                                    <input type="date" value="{{$row->confirm_date}}" required name="confirm_date"
                                           class="form-control" placeholder="Confirmed Date">
                                </div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Confirmed By</label>
                                    <input type="text" value="{{$row->confirm_by}}" required name="confirm_by"
                                           class="form-control" placeholder="Confirmed By">
                                </div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">By Mang</label>
                                    <input type="text" value="{{$row->by_mang}}" required name="by_mang"
                                           class="form-control" placeholder="By Mang">
                                </div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Additional</label>
                                    <input type="text" required value="{{$row->additional}}" name="additional"
                                           class="form-control" placeholder="Additional">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button id="sv_btn" type="submit" class="btn  btn-info">UPDATE</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Row-->
    </form>

@endsection

@section('extraScript')
@endsection

