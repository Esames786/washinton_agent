@extends('layouts.innerpages')

@section('template_title')
    Sale Reports
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
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">Sale Report</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Sale Report</b></h1>
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
                <div class="card-header">

                    <div class="col-md-8">
                        <h4 class="page-title mb-0">SALES REPORT</h4>
                    </div>
                    <div class="col-sm-4 col-md-4">
                        <div class="form-group">
                            <label class="form-label" style=" text-align: center; ">SELECT DATE</label>
                            <input class="form-control" type="month" name="saledate" id="saledate" 
                                   onchange="fetch_data()"/>
                        </div>
                    </div>
                </div>
                <br>
                <form name="passform" action="/sales_report_2" method="post" style="padding-left:10px;">
                    @csrf
                    Enter Password:
                    <input type="password" style=" width: 13rem; display: inline-block;height: 2.4rem;top: 2px;position: relative;" class="form-control" name="pass" />


                    <input type="submit" name="checkpassword" class="btn btn-primary" value="Go" />
                </form>
                <div class="card-body">

                    <div id="table_data">
                        @include('main.phone_quote.admin_reports.sales_report_load')
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

                var datev = $('#saledate').val();
                $('#table_data').html('');
                $('#table_data').append(`<div class="lds-hourglass" id='ldss'></div>`);

                $.ajax({

                    url: "/fetch_sales_data2?page=" + page + "&datev=" + datev,
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

            var datev = $('#saledate').val();
            $('#table_data').html('');
            $('#table_data').append(`<div class="lds-hourglass" id='ldss'></div>`);

            $.ajax({
                url: "/fetch_sales_data2",
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


