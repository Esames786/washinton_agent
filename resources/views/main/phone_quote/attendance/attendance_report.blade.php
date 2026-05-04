@extends('layouts.innerpages')

@section('template_title')
    Attendance Report
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

        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Tables</a>-->
        <!--        </li>-->
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">Attendance Report</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Attendance Report</b></h1>
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

            <form name="passform" class="attendenceReportForm" action="/attendance_report2" method="post">
                @csrf
                Enter Password:
                <input type="password" name="pass"/>
                <input type="hidden" name="selected_date" value="{{$attendancedate}}"/>
                <input type="submit" name="checkpassword" class="btn btn-primary" value="Go"/>
            </form>

            <!--div-->
            <div class="card">
                <div class="card-header">
                    @if($display == 'yes')
                    <div class="col-md-8 col-sm-8">
                        <h4 class="page-title mb-0">ATTENDANCE REPORT</h4>
                    </div>
                    <div class="col-sm-4 col-md-4">
                        <div class="form-group" style="display: flex;align-items: center;">
                            <label class="form-label" style=" margin:0;width: 10rem; ">SELECT DATE</label>

                            <input class="form-control" type="date" value="{{$attendancedate}}" name="attendancedate"
                                   id="attendancedate"
                                   onchange="fetch_data()"/>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="card-body">

                    <div id="table_data">
                        @include('main.phone_quote.attendance.attendance_report_load')
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

                var datev = $('#attendancedate').val();
                $('#table_data').html('');
                $('#table_data').append(`<div class="lds-hourglass" id='ldss'></div>`);

                $.ajax({

                    url: "/fetch_attendance_data?page=" + page + "&datev=" + datev,
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


        function fetch_data() {

            var datev = $('#attendancedate').val();
            $('#table_data').html('');
            $('#table_data').append(`<div class="lds-hourglass" id='ldss'></div>`);

            $.ajax({
                url: "/fetch_attendance_data",
                data: {datev: datev},
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
    </script>

    <!--Scrolling Modal-->

@endsection


