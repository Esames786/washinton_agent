@extends('layouts.innerpages')

@section('template_title')
    General Setting
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

@section('content')

    <div class="page-header">
        <!--<div class="page-leftheader">-->
        <!--    <h4 class="page-title mb-0"> </h4>-->




        <!--    <h4 id="orderidplace"></h4>-->
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layers mr-2 fs-14"></i>Home</a>-->
        <!--        </li>-->

        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">General Setting</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>General Setting</b></h1>
        </div>

    </div>
    <!--End Page header-->
    <!-- Row -->
    
    @if(session('flash_message'))
                <div class="alert alert-success">
                    {{session('flash_message')}}
                </div>
    @endif
    
    <form action="/store_general_setting" id="form" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token()}}">

        <div class="row">
            <div class="col-xl-6 col-lg-6" style="display: inline-grid;right: -24%;">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title"> Previous No of Days {{$data['no_days']}}</div>
                    </div>
                    <div class="card-body">
                        <div class="card-title font-weight-bold"></div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">

                                    <label class="form-label">No of Days</label>
                                    <input type="text" required name="no_days" value="{{$data['no_days']}}" class="form-control"
                                           placeholder="Enter Number of days">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button id="sv_btn" type="submit" class="btn  btn-primary">SAVE</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Row-->
    </form>




@endsection

@section('extraScript')

<script>

</script>

@endsection

