@extends('layouts.innerpages')
@include('partials.mainsite_pages.return_function')
@section('template_title')
    DAY COUNT
@endsection

@section('content')
    <style>
        .table-bordered, .text-wrap table, .table-bordered th, .text-wrap table th, .table-bordered td, .text-wrap table td {
            border: 1px solid #000000 !important;
        }
    </style>

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
            <h1 class="my-4"><b>Day Count</b></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            @if(session('flash_message'))
                <div class="alert alert-success">
                    {{session('flash_message')}}
                </div>
            @endif
            <div class="card">
                <center><span style="margin-top: 20px" class="badge badge-danger">Refresh In: <span class="countdown"></span></span></center>
                <div class="card-header">
                    <div class="col-sm-4 col-md-4">
                        <div class="form-group">

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div id="table_data" style="display: contents;">

                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div><!-- end app-content-->


@endsection

@section('extraScript')

    <script>

        $(document).ready(function () {
            var data_get = 0;
            day_count(data_get);
            countdonwn();

            function day_count(data_get) {
                const stauss = [2, 3, 4, 5, 6, 7, 8, 9, 10];
                if (data_get <= 8) {
                    setTimeout(function () {
                        $.ajax({
                            url: "/fetch_day22",
                            type: "get",
                            data: {pstatus: stauss[data_get]},
                            success: function (data) {
                                data_get++;
                                $('#table_data').append(data);
                                day_count(data_get);
                            }
                        });
                    }), 2000
                }
            }


            setInterval(function () {
                $('#table_data').html('');
                data_get = 0;
                day_count(data_get);
                countdonwn();

            }, 30000*2);


            function countdonwn() {
                var timer2 = "1:00";
                var interval = setInterval(function() {


                    var timer = timer2.split(':');
                    //by parsing integer, I avoid all extra string processing
                    var minutes = parseInt(timer[0], 10);
                    var seconds = parseInt(timer[1], 10);
                    --seconds;
                    minutes = (seconds < 0) ? --minutes : minutes;
                    if (minutes < 0) clearInterval(interval);
                    seconds = (seconds < 0) ? 59 : seconds;
                    seconds = (seconds < 10) ? '0' + seconds : seconds;
                    //minutes = (minutes < 10) ?  minutes : minutes;
                    $('.countdown').html(minutes + ':' + seconds);
                    timer2 = minutes + ':' + seconds;
                }, 1000);
            }


        });

    </script>



@endsection


