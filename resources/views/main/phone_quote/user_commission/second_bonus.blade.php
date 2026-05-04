@extends('layouts.innerpages')

@section('template_title')
    Second Bonus
@endsection
@include('partials.mainsite_pages.return_function')

@section('content')

    <style>
        select.custom-select.custom-select-sm.form-control.form-control-sm {
            height: 29px;
        }
        .table_body{
            color: white !important;
        }
        .table-bordered, .text-wrap table, .table-bordered th, .text-wrap table th, .table-bordered td, .text-wrap table td {
            border: 1px solid #6c757db5 !important;
        }
        .side-app{
            display: grid;
            place-content: center;
        }

    </style>
    <!--/app header-->                                                <!--Page header-->
    <div class="page-header">
        <!--<div class="page-leftheader">-->

        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Tables</a>-->
        <!--        </li>-->
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">Second Bonus</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Second Bonus</b></h1>
        </div>

    </div>
    <!--End Page header-->
    <!-- Row -->

    @if(session('flash_message'))
        <div class="alert alert-success">
            {{session('flash_message')}}
        </div>
    @endif

    <form name="passform" action="/second_bonus_2" method="post">
        @csrf
        Enter Password:
        <input type="password" name="pass"  class="form-control" style="width: auto; display: initial;  height: 2.3rem; top: 2px; position: relative;" />
        <input type="submit" name="checkpassword" class="btn btn-primary" value="Go" />
    </form>

    <div class="container table_body row" style=" position: relative; place-items: center; place-content: center; ">
        <?php
        if($display=='yes'){
        ?>
        <form action="/save_second_bonus" method="post">
            @csrf
            <div class="col-lg-12 bd">

                <div class="form-layout">
                    <div class="row mg-b-25">
                        <div class="col-md-12 pb-2" >
                            <input type="button" value="Add Row" id="append" style="float: right"
                                   name="append" required
                                   class="btn btn-info">
                        </div>

                        <table class="table table-bordered table-hover" style=" background: white; " id="ajao">
                            <?php
                            foreach($data as $fetch_first){

                            $from_first = $fetch_first->fromm;
                            $too_first = $fetch_first->too;
                            $gett_first = $fetch_first->gett;
                            ?>

                            <tr>

                                <td>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-control-label font-weight-bold tx-black">From</label>
                                            <div class="input-group">
                                                <input
                                                        value="<?php echo $from_first ?>"
                                                        name="from_first[]" required
                                                        class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-control-label font-weight-bold tx-black">Too</label>
                                            <div class="input-group">
                                                <input
                                                        value="<?php echo $too_first ?>"
                                                        name="too_first[]" required
                                                        class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-control-label font-weight-bold tx-black">Commission</label>
                                            <div class="input-group">
                                                <input
                                                        value="<?php echo $gett_first ?>"
                                                        name="gett_first[]" required
                                                        class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="col-lg-12">
                                        <div class="form-group2"><label
                                                    class="form-control-label font-weight-bold tx-black">Remove</label>
                                            <div class="input-group"><input value="x"
                                                                            type="button"
                                                                            class="form-control btn btn-danger remove_this" style=" background: #e84139; ">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php }?>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group" style="float: right;">
                                <label class="form-control-label font-weight-bold tx-black" style="color: black;">Update
                                    Second Bonus</label>
                                <div class="input-group">
                                    <input type="submit" id="sm" value="Update"
                                           class="btn btn-info btn-lg">
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- form-layout -->

            </div>
        </form>
        <?php
            }
           ?>
    </div>



@endsection

@section('extraScript')




    <script>
        $("#append").click(function (e) {
            e.preventDefault();
            var markup =
                "<tr><td><div class='col-lg-12'><div class='form-group'><label class='form-control-label font-weight-bold tx-black'>From</label><div class='input-group'><input  name='from_first[]' required class='form-control'></div></div></div></td>" +
                "<td><div class='col-lg-12'><div class='form-group'><label class='form-control-label font-weight-bold tx-black'>Too</label><div class='input-group'><input  name='too_first[]' required class='form-control'></div></div></div></td>" +
                "<td><div class='col-lg-12'><div class='form-group'><label class='form-control-label font-weight-bold tx-black'>Commission</label><div class='input-group'><input  name='gett_first[]' required class='form-control'></div></div></div></td>" +
                "<td><div class='col-lg-12'><div class='form-group2'><label class='form-control-label font-weight-bold tx-black'>Remove</label><div class='input-group'><input style=\" background: #e84139; \" value='-' type='button' class='form-control btn btn-danger remove_this'></div></div></div></td></tr>";
            $("#ajao").append(markup);


            return false;
        });

        jQuery(document).on('click', '.remove_this', function () {
            $(this).closest('tr').remove();
            return false;
        });


    </script>



@endsection


