@extends('layouts.innerpages')

@section('template_title')
    Transport Invoice List
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
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">Invoice</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Transport Invoice List</b></h1>
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

            <?php 
                // if($display=='no'){ 
            ?>
            <!--<form name="passform" action="/invoice_list_2" method="post">-->
            <!--    @csrf-->
            <!--    Enter Password:-->
            <!--    <input type="password" style=" width: 13rem; display: inline-block;height: 2.4rem;top: 2px;position: relative;" class="form-control" name="pass"/>-->
            <!--    <input type="submit" name="checkpassword" class="btn btn-primary" value="Go"/>-->
            <!--</form>-->
            <?php 
                // } 
            ?>
            <!--div-->
            <div class="card">
                <?php 
                    // if($display=='yes'){ 
                ?>
                <div class="card-header">
                    <div class="card-title">
                        <a type="button" href="{{url('invoice_add')}}"
                           class="btn btn-icon btn-primary">Add Invoice
                            <i class="fe fe-plus"></i></a>

                    </div>
                </div>
                <?php 
                    // } 
                ?>
                <div class="card-body">
                    <div id="table_data">

                        @include('main.phone_quote.management.load')
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

            });

            function fetch_data3(page) {

                $('#table_data').html('');
                $('#table_data').append(`<div class="lds-hourglass" id='ldss'></div>`);

                $.ajax({

                    url: "/invoice_list?page=" + page,
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

        });
    </script>

@endsection


