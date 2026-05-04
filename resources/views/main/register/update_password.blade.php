@extends('layouts.innerpages')

@section('template_title')
    Update Password
@endsection

@section('content')
@include('partials.mainsite_pages.return_function')
<style>
    .error{
        border: 1px solid red !important;
    }
</style>

    <div class="page-header">
        <!--<div class="page-leftheader">-->
        <!--    <h4 class="page-title mb-0">Update Password</h4>-->
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layers mr-2 fs-14"></i>Pages</a>-->
        <!--        </li>-->
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">Update Password</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Update Password</b></h1>
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
@if ($message = Session::get('success_msg'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
@endif


@if ($message = Session::get('error_msg'))
    <div class="alert alert-danger alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
@endif
    <form action="/update_password_post" id="form" method="POST" >
        <input type="hidden" name="_token" value="{{ csrf_token()}}">
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Update Password</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Old Password</label>
                                    <input type="password"  required name="old_password"
                                           class="form-control"
                                           placeholder="Old Password">
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="form-label">New Passowrd</label>
                                    <input type="password"  required name="password" id="password" onkeyup="checkPassword()"
                                           class="form-control" placeholder="New Passowrd">
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" required  name="c_password" id="c_password" onkeyup="checkPassword()"
                                           class="form-control"
                                           placeholder="Confirm Password">
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit" class="btn  btn-info">UPDATE</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Row-->
    </form>


    <div class="modal" id="modaldemo4">
        <div class="modal-dialog modal-dialog-centered text-center" role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-body text-center p-4">
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                                aria-hidden="true">&times;</span></button>
                    <i class="fe fe-check-circle fs-100 text-success lh-1 mb-5 d-inline-block"></i>
                    <h4 class="text-success tx-semibold" id="success"></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modaldemo5">
        <div class="modal-dialog modal-dialog-centered text-center" role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-body text-center p-4">
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                                aria-hidden="true">&times;</span></button>
                    <i class="fe fe-x-circle fs-100 text-danger lh-1 mb-5 d-inline-block"></i>
                    <h4 class="text-danger" id="not_success"></h4>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('extraScript')

<script>
    function checkPassword(){
        var password = $("#password").val();
        var c_password = $("#c_password").val();
        if(c_password != password){
            $("#password").addClass("error");
            $("#c_password").addClass("error");
        }
        else if(c_password == password){
            $("#password").removeClass("error");
            $("#c_password").removeClass("error");


        }
    }

</script>
   

@endsection

