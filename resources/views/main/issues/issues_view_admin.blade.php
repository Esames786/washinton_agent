@extends('layouts.innerpages')

@section('template_title')
    Register
@endsection

@section('content')

    @include('partials.mainsite_pages.return_function')
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
            font-weight: 900;
        }

        .table-bordered, .text-wrap table, .table-bordered th, .text-wrap table th, .table-bordered td, .text-wrap table td {
            border: 1px solid #000000;
        }
    </style>
    <div class="page-header">
        <div class="page-leftheader">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fe fe-layers mr-2 fs-14"></i>Pages</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Issue Commented by Users</a></li>
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
                        <div class="card-title">Issue Commented by Users</div>
                    </div>
                    <div class="card-body ">
                        <table id="example1" class="table table-striped table-bordered text-nowrap">
                            <thead>
                            <tr>
                                <th>User Id</th>
                                <th>User Name</th>
                                <th>Comments</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($users as $user)

                                <tr>
                                    <td>
                                        {{ $user->id }}
                                    </td>

                                    <td>
                                        {{ $user->name }}
                                    </td>

                                    <td>
                                        {{ issue_comments($idd,$user->id) }}
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>


                    </div>

                </div>
            </div>
        </div>




        @if($issues->status != "Closed")

            @php $issue_users = explode(',', $issues->issuewithuserId) @endphp

            <div class="row">
                <div class="col-sm-4 col-md-4">
                    <div class="form-group">
                        <h5 class="form-label">Select User(s)</h5>
                        <select multiple="multiple" name="penaltyusers[]" class="multiselect" required>
                            @foreach($users as $user)
                                @if (in_array($user->id, $issue_users))
                                    <option  value="{{ $user->id }}"> {{ $user->name}} </option>
                                @endif
                            @endforeach
                                <option value="{{ $issues->createduserId }}"> {{ get_user_name($issues->createduserId)}} </option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-4 col-md-4">
                    <div class="form-group">
                        <label class="form-label">Penalty</label>
                        <input type="text" required name="penalty" value="{{ $issues->penalty }}"
                               class="form-control"
                               placeholder="penalty..."/>


                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <label>&nbsp</label>
                    <button id="sv_btn" style=" width: 100%; " type="submit"
                            class="btn  btn-primary">SAVE
                    </button>
                </div>
            </div>

            <div class="row">

            </div>

         @else
            @php $penalty_users = explode(',', $issues->penaltyusers) @endphp
            <div class="row">
                <div class="col-sm-2 col-md-2">
                    <div class="form-group">
                        <label class="form-label">Penalty to User(s)</label>
                        @foreach($penalty_users as $val3)
                            <br>
                            <p><strong class="alert alert-warning" style="font-size: 18px">{{get_user_name($val3)}}</strong></p>

                        @endforeach

                    </div>
                </div>
                <div class="col-sm-4 col-md-4">
                    <div class="form-group">
                        <label class="form-label">Penalty</label>
                        <br>
                        <input readonly type="text" required name="penalty" value="{{ $issues->penalty}}"
                               class="form-control"
                               placeholder="penalty..."/>

                    </div>
                </div>

            </div>


    @endif


    <!-- End Row-->
        <input type="hidden" name="issueId" value="{{ $issues->id  }}"/>
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
                    url: "/issue_penalty_store",
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
                            window.location.href = "/admin_issues";
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

