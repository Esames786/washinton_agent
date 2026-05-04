@extends('layouts.innerpages')

@section('template_title')
    Issue Comments
@endsection

@section('content')

    @include('partials.mainsite_pages.return_function')
    <div class="page-header">
        <style>
            .table thead th, .text-wrap table thead th {
                vertical-align: bottom;
                border: 1px solid #191919;
                font-family: sans-serif;
                border-bottom-width: 1px;
                padding-top: .5rem;
                padding-bottom: .5rem;
                text-align: center;
                color: black;
                font-size: 20px;
            }

            .table-bordered, .text-wrap table, .table-bordered th, .text-wrap table th, .table-bordered td, .text-wrap table td {
                border: 1px solid #000000;
            }
        </style>
        <div class="page-leftheader">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fe fe-layers mr-2 fs-14"></i>Pages</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Add Comments</a></li>
            </ol>
        </div>
        <div class="page-rightheader">
            <div class="btn btn-list">

            </div>
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
    <form action="" id="form" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token()}}">
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><center></center> </div>
                    </div>
                    <div class="card-body">

                        <h4 style=" text-transform: capitalize; font-family: 'FontAwesome'; ">
                            <strong>
                                Issue Against: &nbsp;
                            </strong>
                            @php
                                $issuewithid=explode(',',$issues->issuewithuserId);
                            @endphp
                            @foreach($issuewithid as $issueuser)
                                @foreach($users as $user)
                                    @if($user->id==$issueuser)
                                        {{ $user->name }} ,
                                    @endif
                                @endforeach
                            @endforeach

                        </h4>


                        <h4 style=" text-transform: capitalize; font-family: 'FontAwesome'; "> <strong>Issue Detail: </strong>{{$issues->detail}} </h4>

                        <div>
                            <h4>
                                <center>
                                <span style=" text-transform: capitalize; font-size: 17px; line-height: 27px; font-family: 'Roboto';  ">
                                    <mark>
                                        Hello {{Auth::user()->name}}
                                        Please Mention Your Opinion Regarding this
                                        issue,You are our premium employee your opinion will be appreciable, Avoid being
                                        biased or one sided stay positive.After Your Comment Please mark done to the
                                        notification Tab.
                                    </mark>
                                </span>
                                </center>
                            </h4>
                        </div>

                        <div class="card-title font-weight-bold"></div>
                        <div class="row">
                            <table class="table table-bordered ">
                                <thead>
                                <tr>
                                    <th>User Id</th>
                                    <th> Issue Id</th>
                                    <th> My Comments</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><input class="form-control" readonly name="userId"
                                               value="{{ Auth::user()->id }}"></td>
                                    <td><input class="form-control" readonly name="issueid" value="{{ $issueid }}"></td>
                                    <td>
                                        @if($chat<>null || $chat<>'')
                                            <textarea readonly class="form-control" id="comments" name="comments"
                                                      placeholder="My comments..."
                                                      maxlength="500">{{ $chat->comments  }}</textarea>

                                        @else

                                            <textarea required class="form-control" id="comments" name="comments"
                                                      placeholder="My comments..." maxlength="500"></textarea>
                                        @endif
                                    </td>
                                </tr>
                                </tbody>
                            </table>

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
                    url: "/issue_comments_store",
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
                            window.location.href = "/issue_comments_list";
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

