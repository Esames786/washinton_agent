@extends('layouts.innerpages')

@section('template_title')
    Edit Access
@endsection

@section('content')

    @include('partials.mainsite_pages.return_function')
    <div class="page-header">
        <!--<div class="page-leftheader">-->
        <!--    {{--<h4 class="page-title mb-0">Add Employee</h4>--}}-->
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layers mr-2 fs-14"></i>Home</a>-->
        <!--        </li>-->
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">Add Employee</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <!--{{--<div class="page-rightheader">--}}-->
        <!--    {{--<div class="btn btn-list">--}}-->
        <!--        {{--<a href="#" class="btn btn-info"><i class="fe fe-settings mr-1"></i> General Settings </a>--}}-->
        <!--        {{--<a href="#" class="btn btn-danger"><i class="fe fe-printer mr-1"></i> Print </a>--}}-->
        <!--        {{--<a href="#" class="btn btn-warning"><i class="fe fe-shopping-cart mr-1"></i> Buy Now </a>--}}-->
        <!--    {{--</div>--}}-->
        <!--{{--</div>--}}-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Edit Access</b></h1>
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
    <form action="" id="form" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token()}}">
        <input type="hidden" name="user_id" value="{{$id}}">
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Edit Access</div>
                    </div>
                    <div class="card-body">
                        <div class="card-title font-weight-bold">Basic info:</div>
                        <div class="row">
                            @foreach($order_taker as $key => $value)
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group d-flex">
                                        <input type="checkbox" value="{{$value->id}}" name="members[]"
                                               class="form-control w-auto mr-2"
                                               @if(isset($data->id))
                                                    @if($data->status == 1)
                                                        @if(isset($data->member_ids))
                                                            @foreach($data->member_ids as $key2 => $val2)
                                                            @endforeach
                                                        @endif
                                                    @endif
                                               @endif
                                               >
                                        <label class="form-label my-auto">
                                            @if($id == $value->id)
                                                His Qoute
                                            @else 
                                            {{$value->name}} {{$value->last_name}} @if($value->slug) ({{$value->slug}}) @endif Qoute
                                            @endif
                                        </label>
                                    </div>
                                </div>
                            @endforeach
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

