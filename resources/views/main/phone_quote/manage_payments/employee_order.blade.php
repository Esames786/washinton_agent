@extends('layouts.innerpages')

@section('template_title')
    Employee Order Report
@endsection

@section('content')

    <style>
        select.custom-select.custom-select-sm.form-control.form-control-sm {
            height: 29px;
        }
    </style>
    <!--/app header-->                                                <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">EMPLOYEE ORDERS</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Tables</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Employee Orders</a></li>
            </ol>
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
                        <div class="row w-100">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">SELECT MONTH</label>
                                    <input class="form-control" type="month" value="" name="selectmonth" id="selectmonth"/>
                                </div>
                            </div>
                             <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">SELECT USER</label>

                                    <select class="form-control this_save  select2" name="users" id="users"
                                            required>
                                        <option value=""></option>
                                        @foreach($userlist as $user)
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 com-sm-3">
                                <div class="form-group">
                                    <label class="form-label">SELECT ORDER</label>
                                    <select class="form-control this_save select2" name="ordertype" id="ordertype"
                                            required>
                                        <option value="">Select</option>
                                        <option value="13">Completed</option>
                                        <option value="14">Cancel</option>
                                        <option value="15">Deleted</option>

                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="card-header">
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <button id="sv_btn" style=" float: right; " type="button" onclick="fetch_data()" class="btn  btn-primary"> DISPLAY</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <div id="table_data">
                            @include('main.phone_quote.manage_payments.employee_order_load')
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
                alert("1");
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                fetch_data3(page);
                $.cookie("page", page, { expires: 1 });

            });

            function fetch_data3(page) {
                //alert("2");
                var fromdate = $('#fromdate').val();

                var oterminal= $('#oterminal').val();
                var dterminal= $('#dterminal').val();
                $('#table_data').html('');
                $('#table_data').append(`<div class="lds-hourglass" id='ldss'></div>`);

                $.ajax({

                    url: "/fetch_terminal_data2?page=" + page + "&fromdate=" + fromdate +  "&oterminal=" + oterminal + "&dterminal=" + dterminal,
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

            var selectmonth = $('#selectmonth').val();
            var user= $('#users').val();
            var ordertype= $('#ordertype').val();
            $('#table_data').html('');
            $('#table_data').append(`<div class="lds-hourglass" id='ldss'></div>`);

            $.ajax({
                url: "/fetch_employee_order",
                data: {selectmonth: selectmonth,user :user,ordertype: ordertype},
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


