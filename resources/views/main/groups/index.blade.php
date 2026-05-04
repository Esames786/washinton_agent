@extends('layouts.innerpages')

@section('template_title')
    {{ ucfirst(trim('Group Chats', '/')) }}
@endsection
@section('content')

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    @include('partials.mainsite_pages.return_function')
    <style>
        select.form-control:not([size]):not([multiple]) {
            height: 28px;
        }

        input[type='radio']:after {
            width: 15px;
            height: 15px;
            border-radius: 15px;
            top: -4px;
            left: -1px;
            position: relative;
            background-color: #d1d3d1;
            content: '';
            display: inline-block;
            visibility: visible;
            border: 2px solid white;
        }

        input[type='radio']:checked:after {
            width: 20px;
            height: 20px;
            border-radius: 100px;
            top: -2px;
            left: -6px;
            position: relative;
            background-color: rgb(23 162 184);
            content: '';
            display: inline-block;
            visibility: visible;
            border: 2px solid white;
        }

        .table {
            color: rgb(0 0 0);
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .table-bordered,
        .text-wrap table,
        .table-bordered th,
        .text-wrap table th,
        .table-bordered td,
        .text-wrap table td {
            border: 1px solid rgb(0 0 0);
        }

        .table>tbody>tr>td,
        .table>thead>tr>th {
            font-weight: 500;
            -webkit-transition: all .3s ease;
            font-size: 18px;
            color: rgb(0 0 0);
            text-align: center;
        }

        /* Style the tab */
        .tab {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
        }

        /* Style the buttons that are used to open the tab content */
        .tab button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
        }

        /* Change background color of buttons on hover */
        .tab button:hover {
            background-color: #ddd;
        }

        /* Create an active/current tablink class */
        .tab button.active {
            background-color: #ccc;
        }

        /* Style the tab content */
        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
        }

        .tabcontent {
            animation: fadeEffect 1s;
            /* Fading effect takes 1 second */
        }

        /* Go from zero to full opacity */
        @keyframes fadeEffect {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .ms-drop ul>li.ms-select-all {
            width: 100%;
        }

        .ms-drop ul>li.text-capitalize {
            width: 50%;
            text-align: left;
        }

        .ms-drop ul {
            display: flex;
            flex-wrap: wrap;
        }
    </style>
    <!--/app header--> <!--Page header-->
    <div class="page-header">
        <!--<div class="page-leftheader">-->
        <!--    <input type="hidden" value="{{ trim("$_SERVER[REQUEST_URI]", '/') }}" id="titlee">-->
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Home</a>-->
        <!--        </li>-->
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">Groups</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Groups</b></h1>
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
    <div class="row">
        <div class="col-12">
            @if (session('flash_message'))
                <div class="alert alert-success">
                    {{ session('flash_message') }}
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
            <!--div-->
            <div class="card">
                <div class="card-header">
                    <div class="container-fluid">
                    </div>
                </div>
                <div class="card-body">

                    <!-- Tab content -->
                    <div class="">
                        <div class="d-flex justify-content-between">
                            <h3>Groups</h3>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i
                                    class="fa fa-plus"></i> Add New</button>
                        </div>
                        <br>
                        <div id="table_data">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" style="width:100%" id=""
                                    role="grid">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0">#</th>
                                            <th class="border-bottom-0">Group Logo</th>
                                            <th class="border-bottom-0">Group Name</th>
                                            <th class="border-bottom-0">Status</th>
                                            <th class="border-bottom-0">Created At</th>
                                            <th class="border-bottom-0">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($group as $key => $val)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    @if ($val->logo)
                                                        <img src="{{ asset('storage/images/group/' . $val->logo) }}"
                                                            class="rounded-circle" style="width:50px;">
                                                    @else
                                                        <img src="{{ asset('images/group-chat.png') }}"
                                                            class="rounded-circle" style="width:50px;">
                                                    @endif
                                                </td>
                                                <td>{{ $val->name }}</td>
                                                <td><span
                                                        class="badge text-white {{ $val->status == 0 ? 'bg-warning' : ($val->status == 1 ? 'bg-success' : 'bg-danger') }}">{{ $val->status == 0 ? 'Not Active' : ($val->status == 1 ? 'Active' : 'Deleted') }}</span>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i:s A') }}
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <input type="hidden" class="id" value="{{ $val->id }}">
                                                        @if ($val->status == 0)
                                                            <a title="Active" class="btn btn-info"
                                                                href="{{ url('/group/active/' . $val->id) }}"><i
                                                                    class="fa fa-plus"></i></a>
                                                            <a title="Delete" class="btn btn-danger"
                                                                href="{{ url('/group/destroy/' . $val->id) }}"><i
                                                                    class="fa fa-trash"></i></a>
                                                        @elseif($val->status == 1)
                                                            <a title="Non active" class="btn btn-warning"
                                                                href="{{ url('/group/notActive/' . $val->id) }}"><i
                                                                    class="fa fa-minus"></i></a>
                                                            <a title="Delete" class="btn btn-danger"
                                                                href="{{ url('/group/destroy/' . $val->id) }}"><i
                                                                    class="fa fa-trash"></i></a>
                                                        @else
                                                            <a title="Active" class="btn btn-info"
                                                                href="{{ url('/group/active/' . $val->id) }}"><i
                                                                    class="fa fa-plus"></i></a>
                                                            <a title="Non active" class="btn btn-warning"
                                                                href="{{ url('/group/notActive/' . $val->id) }}"><i
                                                                    class="fa fa-minus"></i></a>
                                                        @endif
                                                        <a title="Edit" class="btn btn-success editMile" href="#"
                                                            data-toggle="modal"
                                                            data-target="#exampleModalEdit{{ $val->id }}"><i
                                                                class="fa fa-edit"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <input type="hidden" value="{{ url('') }}" class="url">
                                <div class="d-flex justify-content-between">
                                    <div class="text-secondary my-auto">
                                        Showing {{ $group->firstItem() }} to {{ $group->lastItem() }} from total
                                        {{ $group->total() }} entries
                                    </div>
                                    <div>
                                        {{ $group->links() }}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end app-content-->

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content container p-0">
                <div class="modal-header">
                    <h3>New Group Chat</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12 border text-center calculatorMain">
                        <form action="{{ url('/group/store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div style="form-layout margin:20px auto 0;padding: 1rem 0 0;">
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label class="form-control-label font-weight-bold tx-black"
                                            style="text-align: left !important;display: inherit;">Group Name<span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" name="name" type="text"
                                            placeholder="Group Name">
                                    </div>
                                    <div class="form-group col-12 position-relative">
                                        <label class="form-control-label font-weight-bold tx-black"
                                            style="text-align: left !important;display: inherit;">Group description</label>
                                        <textarea class="form-control" name="description" placeholder="Group description" cols="30" rows="10"></textarea>
                                    </div>
                                    <div class="form-group col-12 position-relative">
                                        <label for="logo" class="form-control-label font-weight-bold tx-black"
                                            style="text-align: left !important;display: inherit;">Group Logo</label>
                                        <input class="form-control" style="height:auto" name="logo" type="file"
                                            id="logo" accept="image/*">
                                    </div>
                                    <div class="text-left col-12 mb-3 position-relative">
                                        <img id="blah" src="#" alt="your image" class="rounded-circle ml-0"
                                            style="width:20%; display:none;">
                                    </div>
                                    <div class="form-group col-12 position-relative">
                                        <label class="form-control-label font-weight-bold tx-black"
                                            style="text-align: left !important;display: inherit;">Status</label>
                                        <select name="status" class="form-control py-0 ">
                                            <option value="" selected disabled>Select Status</option>
                                            <option value="1">Active</option>
                                            <option value="0">Not active</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-12 position-relative">
                                        <label class="form-control-label font-weight-bold tx-black"
                                            style="text-align: left !important;display: inherit;">Users<span
                                                class="text-danger">*</span></label>
                                        <div class="row form-control" style="height:150px;overflow:scroll;margin: 0;">
                                            {{-- @foreach ($user as $key => $value)
                                                <div class="col-sm-6 text-left">
                                                    <input type="checkbox" name="user[]" value="{{ $value->id }}"
                                                        id="users{{ $value->id }}">
                                                    <label
                                                        for="users{{ $value->id }}">{{ $value->slug ? $value->slug : $value->name . ' ' . $value->last_name }}</label>
                                                </div>
                                            @endforeach --}}
                                            @foreach ($user as $key => $value)
                                                <div class="col-sm-6 text-left">
                                                    <input type="checkbox" name="user[]" value="{{ $value->id }}"
                                                        id="users{{ $value->id }}">
                                                    <label for="users{{ $value->id }}">
                                                        {{ $key + 1 }}.
                                                        {{ $value->slug ? $value->slug : $value->name . ' ' . $value->last_name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="form-group col-12 text-right">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="submit" name="submit" value="submit"
                                            class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('extraScript')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function selectedID(res, userId) {
                var selected = '';
                $.each(res.data.users, function() {
                    if (this.user_id == userId) {
                        selected = 'checked';
                    }
                });
                return selected;
            }

            $('.editMile').on('click', function() {
                var url = $('.url').val();
                var id = $(this).siblings('.id').val();

                $.ajax({
                    url: url + "/group/edit/" + id,
                    type: "GET",
                    dataType: "json",
                    success: function(res) {
                        var desc = res.data.description ? res.data.description : '';
                        var img = res.data.logo ?
                            `<img id="blah" src="${url}/storage/images/group/${res.data.logo}" alt="your image" class="rounded-circle" style="width:20%;" />` :
                            `<img id="blah" src="#" alt="your image" class="rounded-circle" style="width:20%; display:none;" />`;

                        var modalRes = `
                    <div class="modal fade" id="exampleModalEdit${res.data.id}" tabindex="-1" aria-labelledby="exampleModalEditLabel${res.data.id}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content container p-0">
                                <div class="modal-header">
                                    <h3>Edit Group Chat</h3>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="col-lg-12 border text-center calculatorMain">
                                        <form action="${url}/group/update/${res.data.id}" method="POST" enctype="multipart/form-data">
                                            @method('PUT')
                                            @csrf
                                            <div style="form-layout margin:20px auto 0;padding: 1rem 0 0;">
                                                <div class="row">
                                                    <div class="form-group col-12">
                                                        <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Group Name<span class="text-danger">*</span></label>
                                                        <input class="form-control" name="name" value="${res.data.name}"  type="text" placeholder="Group Name">
                                                    </div>
                                                    <div class="form-group col-12 position-relative">
                                                        <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Group description</label>
                                                        <textarea class="form-control" name="description" placeholder="Group description" cols="30" rows="10">${desc}</textarea>
                                                    </div>
                                                    <div class="form-group col-12 position-relative">
                                                        <label for="logo" class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Select Logo</label>
                                                        <input class="form-control" name="logo" type="file" id="logo" accept="image/*">
                                                    </div>
                                                    <div class="text-left col-12 mb-3 position-relative">
                                                        ${img}
                                                    </div>
                                                    <div class="form-group col-12 position-relative">
                                                        <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Status</label>
                                                        <select name="status" class="form-control py-0  ">
                                                            <option value="" selected disabled>Select Status</option>
                                                            <option value="1" ${(res.data.status == 1) ? 'selected' : ''}>Active</option>
                                                            <option value="0" ${(res.data.status == 0) ? 'selected' : ''}>Not active</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-12 position-relative">
                                                        <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Users<span class="text-danger">*</span></label>
                                                        <div class="row" style="height:150px;overflow:scroll;">
                                                            @foreach ($user as $key => $value)
                                                                <div class="col-sm-6 text-left">
                                                                    <input type="checkbox" name="user[]" ${selectedID(res, "{{ $value->id }}")} value="{{ $value->id }}" id="user{{ $value->id }}">
                                                                    <label for="user{{ $value->id }}">{{ $value->slug ? $value->slug : $value->name . ' ' . $value->last_name }}</label>    
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-12 text-right">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="submit" value="submit" class="btn btn-primary">Update</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                        $(modalRes).insertBefore(".bottompopups");
                    }
                })
            })
        });

        // $(document).ready(function() {
        //     $.ajaxSetup({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         }
        //     });

        //     function selectedID(res, userId) {
        //         var selected = '';
        //         $.each(res, function() {
        //             if (this.user_id == userId) {
        //                 selected = 'checked';
        //             }
        //         });
        //         return selected;
        //     }

        //     $('.editMile').on('click', function() {
        //         var url = $('.url').val();
        //         var id = $(this).siblings('.id').val();
        //         // console.log(id);
        //         // console.log(url);
        //         $.ajax({
        //             url: url + "/group/edit/" + id,
        //             type: "GET",
        //             dataType: "json",
        //             success: function(res) {
        //                 var desc = '';
        //                 if (res.data.description) {
        //                     desc = res.data.description;
        //                 }
        //                 var img = '';
        //                 if (res.data.logo) {
        //                     img =
        //                         `<img id="blah" src="${url}/storage/images/group/${res.data.logo}" alt="your image" class="rounded-circle" style="width:20%; />`;
        //                 } else {
        //                     img =
        //                         `<img id="blah" src="#" alt="your image" class="rounded-circle" style="width:20%;display:none; />`;
        //                 }
        //                 var modalRes = `

    //                     <div class="modal fade" id="exampleModalEdit${res.data.id}" tabindex="-1" aria-labelledby="exampleModalEditLabel${res.data.id}" aria-hidden="true">
    //                         <div class="modal-dialog">
    //                             <div class="modal-content container p-0">
    //                                 <div class="modal-header">
    //                                     <h3>Edit Group Chat</h3>
    //                                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    //                                         <span aria-hidden="true">&times;</span>
    //                                     </button>
    //                                 </div>
    //                                 <div class="modal-body">
    //                                     <div class="col-lg-12 border text-center calculatorMain">
    //                                         <form action="${url}/group/update/${res.data.id}" method="POST" enctype="multipart/form-data">
    //                                             @method('PUT')
    //                                             @csrf
    //                                             <div style="form-layout margin:20px auto 0;padding: 1rem 0 0;">
    //                                                 <div class="row">
    //                                                     <div class="form-group col-12">
    //                                                         <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Group Name<span class="text-danger">*</span></label>
    //                                                         <input class="form-control" name="name" value="${res.data.name}"  type="text" placeholder="Group Name">
    //                                                     </div>
    //                                                     <div class="form-group col-12 position-relative">
    //                                                         <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Group description</label>
    //                                                         <textarea class="form-control" name="description" placeholder="Group description" cols="30" rows="10">${desc}</textarea>
    //                                                     </div>
    //                                                     <div class="form-group col-12 position-relative">
    //                                                         <label for="logo" class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Select Logo</label>
    //                                                         <input class="form-control" name="logo" type="file" id="logo" accept="image/*">
    //                                                     </div>
    //                                                     <div class="text-left col-12 mb-3 position-relative">
    //                                                         ${img}
    //                                                     </div>
    //                                                     <div class="form-group col-12 position-relative">
    //                                                         <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Status</label>
    //                                                         <select name="status" class="form-control py-0  ">
    //                                                             <option value="" selected disabled>Select Status</option>
    //                                                             <option value="1" ${(res.data.status == 1) ? 'selected' : ''}>Active</option>
    //                                                             <option value="0" ${(res.data.status == 0) ? 'selected' : ''}>Not active</option>
    //                                                         </select>
    //                                                     </div>
    //                                                     <div class="form-group col-12 position-relative">
    //                                                         <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Users<span class="text-danger">*</span></label>
    //                                                         <div class="row" style="height:150px;overflow:scroll;">
    //                                                         @foreach ($user as $key => $value)
    //                                                             <div class="col-sm-6 text-left">
    //                                                                 <input type="checkbox" name="user[]" ${selectedID(res.data.users,"{{ $value->id }}")} value="{{ $value->id }}" id="user{{ $value->id }}">
    //                                                                 <label for="user{{ $value->id }}">{{ $value->slug ? $value->slug : $value->name . ' ' . $value->last_name }}</label>    
    //                                                             </div>
    //                                                         @endforeach
    //                                                         </div>
    //                                                     </div>
    //                                                     <div class="form-group col-12 text-right">
    //                                                         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    //                                                         <button type="submit" name="submit" value="submit" class="btn btn-primary">Update</button>
    //                                                     </div>
    //                                                 </div>
    //                                             </div>
    //                                         </form>
    //                                     </div>
    //                                 </div>
    //                             </div>
    //                         </div>
    //                     </div>
    //                 `;
        //                 $(modalRes).insertBefore(".bottompopups");;
        //             }
        //         })
        //     })
        // })

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#blah').show();
                    $('#blah').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#logo").change(function() {
            readURL(this);
        });
    </script>
    {{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
@endsection
