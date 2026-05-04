@extends('layouts.innerpages')

@section('template_title')
    Phone Number Digits
@endsection

@section('content')
@include('partials.mainsite_pages.return_function')
<style>
    .error{
        border: 1px solid red !important;
    }
</style>

    <div class="page-header">
        <!--<div class="page-leftheader">-->
        <!--    <h4 class="page-title mb-0">Update Other Password</h4>-->
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layers mr-2 fs-14"></i>Pages</a>-->
        <!--        </li>-->
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">Update Other Password</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Phone Number Digits</b></h1>
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
@if ($message = Session::get('success_msg'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
@endif


@if ($message = Session::get('error_msg'))
    <div class="alert alert-danger alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
@endif
    <form action="{{url('/update_phone_digits')}}" method="POST" >
        @csrf
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Update Phone Number Digits</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Number of Digits for Hide</label>
                                    <input type="number" required name="hide_digits"
                                           class="form-control"
                                           placeholder="Number of Digits for Hide" value="{{$data->hide_digits}}">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Hide From</label>
                                    <select  required name="left_right_status"
                                           class="form-control">
                                        <option value="" selected disabled>Select any one</option>
                                        <option value="0" {{$data->left_right_status == 0 ? 'selected' : ''}} >Left To Right</option>
                                        <option value="1" {{$data->left_right_status == 1 ? 'selected' : ''}}>Right To Left</option>
                                        <option value="2" {{$data->left_right_status == 2 ? 'selected' : ''}}>Center</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit" class="btn  btn-info">UPDATE</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Row-->
    </form>

@endsection

