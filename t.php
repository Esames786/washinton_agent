@extends('layouts.app')

@section('content')

<!-- page Content  -->

<div class="right_col" role="main">


    <div class="page-title">
        <div class="title_left" style="width: 100%">
            <span class="supplychainheading"><h5>UPDATE EMPLOYEE</h5> </span>
        </div>
    </div>
    <div class="ln_solid"></div>
    <br>
    @if (\Session::has('success'))
    <div class="alert alert-success">
        <ul>
            <li>{!! \Session::get('success') !!}</li>
        </ul>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="card card-default add_author_card ml-3 mr-3">
        <!-- /.card-header -->
        <div class="card-body ">
            <form name="empedit" method="post" action="{{url('employeeupdate')}}" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="empid" value="{{ $emp->id }}"/>

                <div class="form-row">

                    <div class="col-md-6">
                        <!--
                          <div class="form-group">
                             <label for="inputName4">Employee No</label>
                             <input type="text" name="empno" class="form-control" id="Name" placeholder="Name">
                         </div>
                       -->
                        <div class="form-group">
                            <label for="inputemail4">Employee Name</label>
                            <input type="text" name="empname" class="form-control" id="empname"
                                   placeholder="Employee Name" value="{{ $emp->name }}" maxlength="100"
                                   onchange="this.value=toTitleCase(this.value)"
                                   onkeyup="this.value=toTitleCase(this.value)" required>
                        </div>
                        <div class="form-group">
                            <label for="inputemail4">Father Name</label>
                            <input type="text" name="fname" class="form-control" id="fname" placeholder="Father Name"
                                   value="{{ $emp->fathername }}" maxlength="100"
                                   onchange="this.value=toTitleCase(this.value)"
                                   onkeyup="this.value=toTitleCase(this.value)" required>
                        </div>

                        <div class="form-group">
                            <label for="inputemail4">CNIC No</label>
                            <input type="text" name="cnicno" class="form-control" id="cnicno" placeholder="CNIC No"
                                   value="{{ $emp->cnicno }}" maxlength="20"
                                   onkeypress="return cnicKey(event)" required>
                        </div>

                        <div class="form-group">
                            <label for="inputemail4">Phone No</label>
                            <input type="text" name="phoneno" class="form-control" id="phoneno" placeholder="Phone No"
                                   value="{{ $emp->phone }}"
                                   onkeypress="return isNumberKey(event)" maxlength="20" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputemail4">Emergency Phone No</label>
                            <input type="text" name="phoneno2" class="form-control" id="phoneno2"
                                   placeholder="Emergency Phone No" value="{{ $emp->phoneemergency }}"
                                   onkeypress="return isNumberKey(event)" maxlength="20">
                        </div>


                        <div class="form-group">
                            <label for="inputtext4">Department</label>
                            <select
                                class="select2_single form-control @if ($errors->has('department')) border_alert @endif"
                                name="department" tabindex="-1" value="22" required>
                                <option value="">Select Department</option>
                                @foreach($depart as $fetch)
                                <option value="{{ $fetch->id }}">{{ $fetch->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="inputemail4">Date of Joining</label>
                            <input type="date" class="form-control" id="dateofjoin" name="dateofjoin" placeholder=""
                                   value="{{ $emp->joining_date }}" required>
                        </div>
                        <div class="form-group">
                            <label for="inputemail4">Job Timming</label>
                            <input type="text" class="form-control" id="jobtime" name="jobtime"
                                   placeholder="Job Timming" value="{{ $emp->jobtiming }}" maxlength="20" required>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <table id="glTable1" class="table table-striped">
                        <thead>
                        <tr>
                            <th colspan="4" style="background-color: #d0cfcf; text-align: center;">Attach Documents</th>
                        </tr>
                        <tr>
                            <th style="background-color: #d0cfcf; text-align: center;">File Type</th>
                            <th class="productnametd" style="background-color: #d0cfcf; text-align: center;"> Select
                                File
                            </th>
                            <th class="productnametd" style="background-color: #d0cfcf; text-align: center;">Text</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($filesv as $filerow)
                        <tr>
                            @foreach($filetypes as $filetype)
                            @if($filerow->filetypeId==$filetype->id)

                            <td>
                                {{ $filetype->name }}
                            </td>


                            <td>

                                <a href="{{url('/uploadedfiles')}}/{{$emp->id}}_{{$filerow->id}}.{{$filerow->filename}}" target="_blank">
                                    <img
                                        src="{{url('/uploadedfiles')}}/{{$emp->id}}_{{$filerow->id}}.{{$filerow->filename}}"
                                        style="width: 300px;height: 200px;">
                                </a>
                            </td>

                            <td>
                                {{ $filerow->comments }}
                            </td>
                            <td>
                            </td>


                            @endif
                            @endforeach
                        </tr>
                        @endforeach
                        <tr style="background-color: #fff; text-align: center;">
                            <td class="catclass">
                                <select
                                    class="select2_single form-control @if ($errors->has('filetype')) border_alert @endif"
                                    name="filetype[]" tabindex="-1" value="22"/>
                                <option value="">Select File Type</option>
                                @foreach($filetypes as $filetype)
                                <option value="{{ $filetype->id }}">{{ $filetype->name }}</option>
                                @endforeach
                                </select>
                            </td>
                            <td class="productnametd">
                                <input type="file" class="form-control" id="fileup[]" name="fileup[]">
                            </td>
                            <td style="width: 5%">
                                <a type="button" class="trashAnchor" id="removeRowBtn"><img
                                        src="{{url('')}}/assets/images/Trash-Icon.png"></a>
                            </td>
                            <td>
                            </td>
                        </tr>
                    </table>
                </div>
                <a type="button" id="addRowBtn" style="float: right;"> <img
                        src="{{url('')}}/assets/images/plusicon.png"></a>

                <input id="send" type="submit" name="save" value="Save"/>


            </form>


            <!-- /.card-body -->

            <!-- /.card -->
            <!-- /.content -->
        </div>
    </div>
</div>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js"></script>
<script>
    $(document).ready(function () {
        $("#cnicno").inputmask({mask: "99999-9999999-9"});
        $("#jobtime").inputmask({mask: "99:99 To 99:99"});

        $(":input").inputmask();
    });
</script>

<script type="text/javascript">

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31
            && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    function cnicKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 45 && charCode > 31
            && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }


    function toTitleCase(str) {
        return str.replace(
            /\w\S*/g,
            function (txt) {
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            }
        );
    }

    $(function () {
        $('#empname').keydown(function (e) {
            if (e.shiftKey || e.ctrlKey || e.altKey) {
                e.preventDefault();
            } else {
                var key = e.keyCode;
                if (!((key == 8) || (key == 9) || (key == 32) || (key == 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {
                    e.preventDefault();
                }
            }
        });
    });

    $(function () {
        $('#fname').keydown(function (e) {
            if (e.shiftKey || e.ctrlKey || e.altKey) {
                e.preventDefault();
            } else {
                var key = e.keyCode;
                if (!((key == 8) || (key == 9) || (key == 32) || (key == 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {
                    e.preventDefault();
                }
            }
        });
    });

    $(document).ready(function () {
        $('#addRowBtn').click(function () {
            $('#glTable1 tbody').append(`<tr><td> <select class="select2_single form-control @if ($errors->has('filetype')) border_alert @endif" name="filetype[]" tabindex="-1" value="22" required><option value="">Select File Type</option>@foreach($filetypes as $filetype)<option value="{{ $filetype->id }}" >{{ $filetype->name }}</option>@endforeach</select></td><td class="productnametd"><input type="file"  class="form-control" id="fileup[]" name="fileup[]"></td><td style="width: 5%"><a type="button" class="trashAnchor"  id="removeRowBtn"><img src="{{url('')}}/assets/images/Trash-Icon.png"></a></td></tr>`);

        });
    });


    $('table').on('click', '.trashAnchor', function () {
        if ($(this).closest('tbody').find('tr').length > 1) {
            $(this).closest('tr').remove();
        } else {
            alert('Rows should be greater than one');
        }
    });


</script>
@endsection
