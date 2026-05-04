@extends('layouts.innerpages')

@section('template_title')
    Guides
@endsection
@include('partials.mainsite_pages.return_function')
<style>
    .oauc {
        padding: 11px;
        width: 100%;
    }

    .dauc {
        padding: 11px;
        width: 100%;
    }
</style>
@php
    $access_guide = explode(',', Auth::user()->emp_access_guide);
@endphp
@section('content')

    <div class="page-header">
        <!--<div class="page-leftheader">-->

        <!--    <h4 id="orderidplace"></h4>-->
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="/dashboard"><i class="fe fe-layers mr-2 fs-14"></i>Home</a>-->
        <!--        </li>-->

        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="/">User Guide</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Guides</b></h1>
        </div>

    </div>
    <!-- category header --><div class="slim-pageheader" style="padding: 0px 0 0 0;">
        <ol class="breadcrumb slim-breadcrumb"></ol>
        <h6 class="slim-pagetitle" style="border-left: 4px solid #4CAF50;">Admin Panel</h6>
    </div><!-- category header -->

    <div class="row row-sm mg-t-20">
        @foreach($Guide as $key=>$val)
            @if($val->guide_type == 1)
                @if (in_array($val->id, $access_guide))
                    <div class="col-lg-4">
                        <div class="card card-info">
                            <div class="card-body pd-40">
                                <div class="d-flex justify-content-center mg-b-30">
    {{--                                <img src="{{ url($val->thumbnail) }}" style="width: 100px; height: 100px;" alt="">--}}
                                    <img src="/assets/images/png/shipa1-guides.png" style="width: 100px; height: 100px;" alt="">
                                </div>
                                <h5 class="tx-inverse mg-b-20">{{$val->page_name}}</h5>
    
                                <a href="/guide/{{$val->page_route}}" class="btn btn-primary btn-block">Go To Guide</a>
                            </div><!-- card -->
                        </div><!-- card -->
                    </div><!-- col-4 -->
                @endif
            @endif
        @endforeach

{{--        <div class="col-lg-4">--}}
{{--            <div class="card card-info">--}}
{{--                <div class="card-body pd-40">--}}
{{--                    <div class="d-flex justify-content-center mg-b-30">--}}
{{--                        <img src="/assets/images/png/shipa1-guides.png" style="width: 100px; height: 100px;" alt="">--}}
{{--                    </div>--}}
{{--                    <h5 class="tx-inverse mg-b-20">Luxury Vehicle Guide</h5>--}}

{{--                    <a href="/luxury" class="btn btn-primary btn-block">Go To Guide</a>--}}
{{--                </div><!-- card -->--}}
{{--            </div><!-- card -->--}}
{{--        </div><!-- col-4 -->--}}
{{--        <div class="col-lg-4">--}}
{{--            <div class="card card-info">--}}
{{--                <div class="card-body pd-40">--}}
{{--                    <div class="d-flex justify-content-center mg-b-30">--}}
{{--                        <img src="/assets/images/png/shipa1-guides.png" style="width: 100px; height: 100px;" alt="">--}}
{{--                    </div>--}}
{{--                    <h5 class="tx-inverse mg-b-20">Non-luxury Vehicle Guide</h5>--}}

{{--                    <a href="/non-luxury" class="btn btn-primary btn-block">Go To Guide</a>--}}
{{--                </div><!-- card -->--}}
{{--            </div><!-- card -->--}}
{{--        </div><!-- col-4 -->--}}

    </div>


    <!-- category header --><div class="slim-pageheader" style="padding: 25px 0 0 0;">
        <ol class="breadcrumb slim-breadcrumb"></ol>
        <h6 class="slim-pagetitle" style="border-left: 4px solid #4CAF50;">Vehicles</h6>
    </div><!-- category header -->

    <div class="row row-sm mg-t-20">
        @foreach($Guide as $key=>$val)
            @if($val->guide_type == 2)
                @if (in_array($val->id, $access_guide))
                    <div class="col-lg-4">
                        <div class="card card-info">
                            <div class="card-body pd-40">
                                <div class="d-flex justify-content-center mg-b-30">
                                    {{--                                <img src="{{ url($val->thumbnail) }}" style="width: 100px; height: 100px;" alt="">--}}
                                    <img src="/assets/images/png/shipa1-guides.png" style="width: 100px; height: 100px;" alt="">
                                </div>
                                <h5 class="tx-inverse mg-b-20">{{$val->page_name}}</h5>
    
                                <a href="/guide/{{$val->page_route}}" class="btn btn-primary btn-block">Go To Guide</a>
                            </div><!-- card -->
                        </div><!-- card -->
                    </div><!-- col-4 -->
                @endif
            @endif
        @endforeach

    </div>

    <!-- category header --><div class="slim-pageheader" style="padding: 25px 0 0 0;">
        <ol class="breadcrumb slim-breadcrumb"></ol>
        <h6 class="slim-pagetitle" style="border-left: 4px solid #4CAF50;">Motorcycle</h6>
    </div><!-- category header -->


    <div class="row row-sm mg-t-20">
        @foreach($Guide as $key=>$val)
            @if($val->guide_type == 3)
                @if (in_array($val->id, $access_guide))
                    <div class="col-lg-4">
                        <div class="card card-info">
                            <div class="card-body pd-40">
                                <div class="d-flex justify-content-center mg-b-30">
                                    {{--                                <img src="{{ url($val->thumbnail) }}" style="width: 100px; height: 100px;" alt="">--}}
                                    <img src="/assets/images/png/shipa1-guides.png" style="width: 100px; height: 100px;" alt="">
                                </div>
                                <h5 class="tx-inverse mg-b-20">{{$val->page_name}}</h5>
    
                                <a href="/guide/{{$val->page_route}}" class="btn btn-primary btn-block">Go To Guide</a>
                            </div><!-- card -->
                        </div><!-- card -->
                    </div><!-- col-4 -->
                @endif
            @endif
        @endforeach
    </div>

    <!-- category header --><div class="slim-pageheader" style="padding: 25px 0 0 0;">
        <ol class="breadcrumb slim-breadcrumb"></ol>
        <h6 class="slim-pagetitle" style="border-left: 4px solid #4CAF50;">Heavy Equipments</h6>
    </div><!-- category header -->


    <div class="row row-sm mg-t-20">
        @foreach($Guide as $key=>$val)
            @if($val->guide_type == 4)
                @if (in_array($val->id, $access_guide))
                    <div class="col-lg-4">
                        <div class="card card-info">
                            <div class="card-body pd-40">
                                <div class="d-flex justify-content-center mg-b-30">
                                    {{--                                <img src="{{ url($val->thumbnail) }}" style="width: 100px; height: 100px;" alt="">--}}
                                    <img src="/assets/images/png/shipa1-guides.png" style="width: 100px; height: 100px;" alt="">
                                </div>
                                <h5 class="tx-inverse mg-b-20">{{$val->page_name}}</h5>
    
                                <a href="/guide/{{$val->page_route}}" class="btn btn-primary btn-block">Go To Guide</a>
                            </div><!-- card -->
                        </div><!-- card -->
                    </div><!-- col-4 -->
                @endif
            @endif
        @endforeach

    </div>

    <!-- category header --><div class="slim-pageheader" style="padding: 25px 0 0 0;">
        <ol class="breadcrumb slim-breadcrumb"></ol>
        <h6 class="slim-pagetitle" style="border-left: 4px solid #4CAF50;">Order Taking</h6>
    </div><!-- category header -->


    <div class="row row-sm mg-t-20">
        @foreach($Guide as $key=>$val)
            @if($val->guide_type == 5)
                @if (in_array($val->id, $access_guide))
                    <div class="col-lg-4">
                        <div class="card card-info">
                            <div class="card-body pd-40">
                                <div class="d-flex justify-content-center mg-b-30">
                                    {{--                                <img src="{{ url($val->thumbnail) }}" style="width: 100px; height: 100px;" alt="">--}}
                                    <img src="/assets/images/png/shipa1-guides.png" style="width: 100px; height: 100px;" alt="">
                                </div>
                                <h5 class="tx-inverse mg-b-20">{{$val->page_name}}</h5>
    
                                <a href="/guide/{{$val->page_route}}" class="btn btn-primary btn-block">Go To Guide</a>
                            </div><!-- card -->
                        </div><!-- card -->
                    </div><!-- col-4 -->
                @endif
            @endif
        @endforeach

    </div>

    <!-- category header --><div class="slim-pageheader" style="padding: 25px 0 0 0;">
        <ol class="breadcrumb slim-breadcrumb"></ol>
        <h6 class="slim-pagetitle" style="border-left: 4px solid #4CAF50;">Delivery</h6>
    </div><!-- category header -->


    <div class="row row-sm mg-t-20">
        @foreach($Guide as $key=>$val)
            @if($val->guide_type == 6)
                @if (in_array($val->id, $access_guide))
                    <div class="col-lg-4">
                        <div class="card card-info">
                            <div class="card-body pd-40">
                                <div class="d-flex justify-content-center mg-b-30">
                                    {{--                                <img src="{{ url($val->thumbnail) }}" style="width: 100px; height: 100px;" alt="">--}}
                                    <img src="/assets/images/png/shipa1-guides.png" style="width: 100px; height: 100px;" alt="">
                                </div>
                                <h5 class="tx-inverse mg-b-20">{{$val->page_name}}</h5>
    
                                <a href="/guide/{{$val->page_route}}" class="btn btn-primary btn-block">Go To Guide</a>
                            </div><!-- card -->
                        </div><!-- card -->
                    </div><!-- col-4 -->
                @endif
            @endif
        @endforeach

    </div>

    <!-- category header --><div class="slim-pageheader" style="padding: 25px 0 0 0;">
        <ol class="breadcrumb slim-breadcrumb"></ol>
        <h6 class="slim-pagetitle" style="border-left: 4px solid #4CAF50;">Dispatch</h6>
    </div><!-- category header -->


    <div class="row row-sm mg-t-20">
        @foreach($Guide as $key=>$val)
            @if($val->guide_type == 7)
                @if (in_array($val->id, $access_guide))
                    <div class="col-lg-4">
                        <div class="card card-info">
                            <div class="card-body pd-40">
                                <div class="d-flex justify-content-center mg-b-30">
                                    {{--                                <img src="{{ url($val->thumbnail) }}" style="width: 100px; height: 100px;" alt="">--}}
                                    <img src="/assets/images/png/shipa1-guides.png" style="width: 100px; height: 100px;" alt="">
                                </div>
                                <h5 class="tx-inverse mg-b-20">{{$val->page_name}}</h5>
    
                                <a href="/guide/{{$val->page_route}}" class="btn btn-primary btn-block">Go To Guide</a>
                            </div><!-- card -->
                        </div><!-- card -->
                    </div><!-- col-4 -->
                @endif
            @endif
        @endforeach

    </div>

    <!-- category header --><div class="slim-pageheader" style="padding: 25px 0 0 0;">
        <ol class="breadcrumb slim-breadcrumb"></ol>
        <h6 class="slim-pagetitle" style="border-left: 4px solid #4CAF50;">Approaching</h6>
    </div><!-- category header -->


    <div class="row row-sm mg-t-20">
        @foreach($Guide as $key=>$val)
            @if($val->guide_type == 8)
                @if (in_array($val->id, $access_guide))
                    <div class="col-lg-4">
                        <div class="card card-info">
                            <div class="card-body pd-40">
                                <div class="d-flex justify-content-center mg-b-30">
                                    {{--                                <img src="{{ url($val->thumbnail) }}" style="width: 100px; height: 100px;" alt="">--}}
                                    <img src="/assets/images/png/shipa1-guides.png" style="width: 100px; height: 100px;" alt="">
                                </div>
                                <h5 class="tx-inverse mg-b-20">{{$val->page_name}}</h5>
    
                                <a href="/guide/{{$val->page_route}}" class="btn btn-primary btn-block">Go To Guide</a>
                            </div><!-- card -->
                        </div><!-- card -->
                    </div><!-- col-4 -->
                @endif
            @endif
        @endforeach

    </div>
    
    
    <style>
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

