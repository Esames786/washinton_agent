@extends('layouts.innerpages')

@section('template_title')
    Commission
@endsection
@include('partials.mainsite_pages.return_function')

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
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">Commission</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Commission</b></h1>
        </div>

    </div>
    <!--End Page header-->
    <!-- Row -->


    <div class="row">
        <div class="col-12">

            <form name="passform" action="/user_commission_2" method="post">
                @csrf
                Enter Password:
                <input type="password" name="pass" class="form-control" style="width: auto; display: initial;  height: 2.3rem; top: 2px; position: relative;" />
                <input type="submit" name="checkpassword" class="btn btn-primary" value="Go"/>
            </form>
        <?php
        if($display == 'yes'){
        ?>
        <!--div-->
            <form name="commision" action="#" method="post" id="user_commision">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <div class="row w-100">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">SELECT DATE FROM</label>
                                    <input class="form-control" type="date" value="" name="fromdate" id="fromdate"/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">SELECT DATE TO</label>
                                    <input class="form-control" type="date" value="" name="todate" id="todate"/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Employee</label>

                                    <select class="form-control select2" name="user_name" id="user_name"
                                            required>
                                        <optgroup label="Categories">
                                            <option data-select2-id="5" selected="" disabled="">--Select--</option>
                                            @foreach($users as $val)
                                                <option value="{{$val->id}}">{{$val->name}} {{$val->slug ? '('.$val->slug.')' : ''}} @if(isset($val->userRole->name)) ({{$val->userRole->name}}) @endif</option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary w-100 h-45">Search</button>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="card-body">
                        <div class="table-responsive" id="show_data">

                        </div>
                        <div id="show_cancel">

                        </div>
                    </div>
                </div>
            </form>
            <?php
            }
            ?>
        </div>

    </div><!-- end app-content-->


@endsection

@section('extraScript')

    <script>
        $("#user_commision").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: "/post_commision",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    $('#show_data').html('');
                    $('#show_data').html(`<div class="lds-hourglass" id='ldss'></div>`);
                },
                success: function (data) {
                    $('#show_data').html('');
                    $('#show_data').html(data);
                }
                ,
                complete: function (data) {
                    $('#ldss').hide();
                },
                error: function (e) {
                    $("#err").html(e).fadeIn();
                }
            });
        }));
    </script>




@endsection


