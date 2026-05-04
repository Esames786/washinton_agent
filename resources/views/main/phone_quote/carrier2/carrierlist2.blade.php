@extends('layouts.innerpages')

@section('template_title')
    {{  ucfirst(trim("$_SERVER[REQUEST_URI]",'/'))}}
@endsection

@section('content')
    <style>
        select.custom-select.custom-select-sm.form-control.form-control-sm {
            height: 29px;
        }
    </style>

    <!--/app header-->                                                <!--Page header-->
    <div class="page-header">
        <!--<div class="page-leftheader">-->
        <!--    <h4 class="page-title mb-0">{{  ucfirst(trim("$_SERVER[REQUEST_URI]",'/'))}}</h4>-->
        <!--    <input type="hidden" value="{{trim("$_SERVER[REQUEST_URI]",'/')}}" id="titlee">-->
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Tables</a>-->
        <!--        </li>-->
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">Carriers</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase">
            <h1 class="my-4"><b>Carrier List</b></h1>
        </div>
        <div class="page-rightheader">
            <div class="btn btn-list">

                <a href="/carrier_add_new" class="btn btn-info">
                    <i class="fe fe-settings mr-1"></i> Add Carriers</a>
            </div>
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
                <div class="card-body">
                    <div id="table_data">
                        {{--<iframe width="100%" height="1000px"--}}
                                {{--src="https://docs.google.com/spreadsheets/d/1LmvHFe6q69qA5RcyWqUR2fWi3G_AQokaFXJ6eccjdr4/edit#gid=0"--}}
                                {{--title="Carrier Data"></iframe>--}}
                        @include('main.phone_quote.carrier2.load2')
                    </div>
                </div>
            </div>

        </div>

    </div><!-- end app-content-->






@endsection

@section('extraScript')


    <script>
        $(document).ready(function () {

            $(document).on('click', '.pagination a', function (event) {

                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                fetch_data3(page);
                $.cookie("page", page, { expires: 1 });

            });

            function fetch_data3(page) {

                $('#table_data').html('');
                $('#table_data').append(`<div class="lds-hourglass" id='ldss'></div>`);

                $.ajax({

                    url: "/carrier_list2?page=" + page,
                    success: function (data) {
                        $('#table_data').html('');
                        $('#table_data').html(data);

                    },
                    complete: function (data) {
                        $('#ldss').hide();
                        regain();
                    }

                })

            }
            let cookie = $.cookie("page");
            if(cookie)
            {
                fetch_data3(cookie);
                $.removeCookie("page");
            }

        });
    </script>


    <!--Scrolling Modal-->

@endsection


