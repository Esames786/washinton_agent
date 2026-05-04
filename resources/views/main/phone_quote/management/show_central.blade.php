@extends('layouts.innerpages')

@section('template_title')
    Add Work
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
        <!--    <h4 class="page-title mb-0">Add Work  </h4>-->

        <!--    <h3 class="page-title mb-0"></h3>-->


        <!--    <h4 id="orderidplace"></h4>-->
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layers mr-2 fs-14"></i>Pages</a>-->
        <!--        </li>-->

        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">Add Work Detail</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Add Work</b></h1>
        </div>

    </div>
    <!--End Page header-->
    <!-- Row -->
    @if(session('flash_message'))
        <div class="alert alert-success">
            {{session('flash_message')}}
        </div>
    @endif


    <form action="/save_web_price" id="form" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token()}}">

        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title" style=" width: 92%; ">
                            <div class="alert alert-success" style="color: white" id="success-alert">
                                <strong>Successfully! </strong> Copied Session

                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-title font-weight-bold"></div>
                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Copy Session</label>
                                    <input type="text" required name="copy_session" id="cache_text" value="b_t = $('#b_t').val();alert(b_t);" readonly="" class="form-control"
                                           placeholder="Enter Order Id...">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Update Session</label>
                                    <input type="text" required name="get_ses" value="{{$gets->get_ses}}" class="form-control"
                                           placeholder="">
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Update Cookies</label>
                                    <textarea type="text"  required name="cachee"  class="form-control" style="margin-top: 0px;margin-bottom: 0px;height: 150px;" placeholder="Cookies Data">{{$gets->cachee}} </textarea>
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


    <div class="modal" id="modaldemo4">
        <div class="modal-dialog modal-dialog-centered text-center " role="document">
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
        $(document).ready(function(){
            $("#cache_text").click(function(){
                $("#cache_text").select();
                document.execCommand('copy');
            });

            $("#success-alert").hide();
            $("#cache_text").click(function showAlert() {
                $("#success-alert").fadeTo(1000, 500).slideUp(500, function() {
                    $("#success-alert").slideUp(500);
                });
            });

        });
    </script>

@endsection

