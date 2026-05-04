@extends('layouts.innerpages')

@section('template_title')
    Sheet List
@endsection

@section('content')
    <!-- Row -->
    @include('partials.mainsite_pages.return_function')
    <!-- Include Select2 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
    <!-- Include Select2 JS -->
    <div class="page-header">
        <!--<div class="page-leftheader">-->
        <!--    <h4 class="page-title mb-0">DAY COUNT</h4>-->
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Tables</a>-->
        <!--        </li>-->
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">DAY COUNT</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <!--<div class="page-rightheader">-->
        <!--    <div class="btn btn-list">-->


        <!--    </div>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Sheet List</b></h1>
            <?php
            $check_panel = check_panel();
            if ($check_panel == 1) {
                $phoneaccess = explode(',', Auth::user()->emp_access_phone);
            } 
            elseif($check_panel == 3)
            {
                $phoneaccess = explode(',',Auth::user()->emp_access_test);
            }
            else {
                $phoneaccess = explode(',', Auth::user()->emp_access_web);
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            @if(session('flash_message'))
                <div class="alert alert-success">
                    {{session('flash_message')}}
                </div>
        @endif
        <!--div-->
            <div class="card mt-5">
                <div class="card-header">
                    @if (in_array('111', $phoneaccess))
                    <form method="post" action="{{url('create_sheet_google')}}">
                        @csrf
                        <div class="row">
                            <input type="hidden" id="id" name="id">
                            <div class="col-md-3 mb-3">
                                <label for="sheet_name">Sheet Name</label>
                                <input type="text" class="form-control" id="sheet_name" name="sheet_name">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="link">Sheet Url</label>
                                <input type="text" class="form-control" id="link" name="link">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="user_id">Users</label>
                                <br>
                                <select class="form-control select2" id="user_id" name="user_id[]" multiple>
                                    <option value=""  disabled>Select</option>
                                    @foreach($user as $val)
                                        <option value="{{$val->id}}">{{$val->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <input type="submit" value="Create Sheet" class="btn btn-info">
                            </div>
                        </div>


                    </form>
                   @endif

                </div>
                <div class="card-body">
                    <div class="">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped text-nowrap key-buttons">
                                <thead>
                                <tr>
                                    <th class="border-bottom-0">ID</th>
                                    <th class="border-bottom-0">Date</th>
                                    <th class="border-bottom-0">Sheet Name</th>
                                    <th class="border-bottom-0">View & Edit</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $id = auth()->id();
                                ?>
                                @foreach($data as $sheets)
                                    <?php
                                    $user_id = (array) json_decode($sheets->user_ids);
                                    if(in_array($id, $user_id)) {
                                    ?>
                                    <tr>
                                        <td>
                                            {{$sheets->id}}
                                        </td>
                                        <td>
                                            Sheet-{{\Carbon\Carbon::parse($sheets->created_at)->format('d-M-Y')}}
                                        </td>
                                        <td>
                                        {{$sheets->sheet_name}}
                                        </td>
                                        <td>

                                                <a href="{{url('sheets_data/').'/'.base64_encode($sheets->id) }}" class="btn btn-info btn-sm">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            @if (in_array('111', $phoneaccess))
                                                <button onclick="convert_data('{{$sheets->id}}','{{$sheets->link}}','{{$sheets->sheet_name}}','{{$sheets->user_ids}}')" class="btn btn-info btn-sm">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    <?php  } ?>
                                @endforeach

                                {{$data->links()}}

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--/div-->

        </div>
    </div>
    <!-- /Row -->



@endsection

@section('extraScript')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });

        function convert_data(id,link,sheet_name,user_ids) {
            $('#id').val(id);
            $('#sheet_name').val(sheet_name);
            $('#link').val(link);
            $('#user_id').val(null).trigger('change');
            if(user_ids) {
                var selectedUserIds = JSON.parse(user_ids);
                $('#user_id').val(null).trigger('change');

                if (selectedUserIds) {
                    // Set the selected options in the Select2 dropdown based on the parsed array
                    $('#user_id').val(selectedUserIds).trigger('change');
                }
            }
        }
    </script>
@endsection

