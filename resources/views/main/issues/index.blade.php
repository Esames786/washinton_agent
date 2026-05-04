@extends('layouts.innerpages')

@section('template_title')
    Register
@endsection

@section('content')

    @include('partials.mainsite_pages.return_function')
    <style>
        .ms-parent {
            display: inline-block;
            position: relative;
            vertical-align: middle;
            width: 100% !important;
            border: 1px solid #5ca6f2;
        }
        
    </style>
    <div class="page-header">
        <!--<div class="page-leftheader">-->
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layers mr-2 fs-14"></i>Pages</a>-->
        <!--        </li>-->
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">Add Issue</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Add Issues</b></h1>
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
    <form action="" id="form" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token()}}">
        <div class="row">
            <div class="col-xl-12 col-lg-12" style=" display: grid; place-content: center; ">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Add Issue</div>
                    </div>
                    <div class="card-body">
                        <div class="card-title font-weight-bold"></div>
                        <div class="row">
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Subject</label>
                                    <input type="text" required name="subject" class="form-control"
                                           placeholder="Subject" required />
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4">
                                <div class="form-group">
                                    <label class="form-label">Issues with users</label>
                                    <select multiple="multiple" style=" border: 1px solid #5ca6f2; " name="issues_with_users[]" class="multiselect" required>
                                     @foreach($data as $row)
                                        <option value="{{ $row->id }}">{{ $row->name }} </option>
									 @endforeach
                                    </select>
                                </div>
                            </div>

                            @if(Auth::user()->role == 1)
                            <div class="col-md-4 col-sm-4" style="text-align: center">
                                <div class="form-group">
                                    <label class="form-label">Ask With Other Employess</label>
                                    <input style=" border: 1px solid #5ca6f2; " name="other_emp" class="form-control" value="1" type="checkbox">
                                </div>
                            </div>
                            @endif

                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Issue detail</label>
                                     <textarea class="form-control" id="issuedetail" name="issuedetail" 
									 placeholder="What's issue..." required></textarea>
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

        $(document).ready(function (e) {
            $("#form").on('submit', (function (e) {
                e.preventDefault();
                $.ajax({
                    url: "/save_issue",
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function () {

                    },
                    success: function (data) {

                        // view uploaded file.
                        //$("#preview").html(data).fadeIn();

                        let test = data.toString();
                        let test2 = $.trim(test);
                        let text = "SUCCESS";
                        if (test2 == text) {
                            $('#success').html(data);
                            $('#modaldemo4').modal('show');
                            window.location.href = "/my_issues";
                        } else {
                            $('#not_success').html(data);
                            $('#modaldemo5').modal('show');
                        }
                    },
                    error: function (e) {
                        $("#err").html(e).fadeIn();
                    }
                });
            }));
        });

    </script>

@endsection

