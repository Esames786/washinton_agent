@extends('layouts.innerpages')
@section('template_title')
    Register
@endsection
@section('content')
    <style>
        /* Style the tab */
        .table-responsive {
            overflow: unset !important;
        }

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

        .dropdown-menu {
            left: -6rem !important;
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
    </style>
    <!-- Row -->
    @include('partials.mainsite_pages.return_function')
    <div class="row">
        <div class="col-12">
            @if (session('flash_message'))
                <div class="alert alert-success">
                    {{ session('flash_message') }}
                </div>
            @endif
            <!--div-->
            <div class="page-header">
                <!--<div class="page-leftheader">-->
                <!--    {{-- <h4 class="page-title mb-0">Add Employee</h4> --}}-->
                <!--    <ol class="breadcrumb">-->
                <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layers mr-2 fs-14"></i>Home</a>-->
                <!--        </li>-->
                <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">Add Employee</a></li>-->
                <!--    </ol>-->
                <!--</div>-->
                <!--{{-- <div class="page-rightheader"> --}}-->
                <!--    {{-- <div class="btn btn-list"> --}}-->
                <!--        {{-- <a href="#" class="btn btn-info"><i class="fe fe-settings mr-1"></i> General Settings </a> --}}-->
                <!--        {{-- <a href="#" class="btn btn-danger"><i class="fe fe-printer mr-1"></i> Print </a> --}}-->
                <!--        {{-- <a href="#" class="btn btn-warning"><i class="fe fe-shopping-cart mr-1"></i> Buy Now </a> --}}-->
                <!--    {{-- </div> --}}-->
                <!--{{-- </div> --}}-->
                <div class="text-secondary text-center text-uppercase w-100">
                    <h1 class="my-4"><b>View Employees</b></h1>
                </div>
            </div>
            <div class="card mt-5">
                <div class="card-header">
                    <div class="card-title"><a type="button" href="{{ url('add_employee') }}"
                            class="btn btn-icon btn-primary">Add
                            Employee<i class="fe fe-plus"></i></a></div>
                </div>
                <div class="card-body">
                    <div class="">
                        <div class="table-responsive">
                            <!-- Tab links -->
                            <div class="tab">
                                @foreach ($roles as $key => $val)
                                    <button class="tablinks"
                                        onclick="openCity(event, '{{ str_replace(' ', '_', $val->name) }}')"
                                        @if ($val->name == 'Admin') id="defaultOpen" @endif>{{ $val->name }}
                                        ({{ $val->users_count }})
                                    </button>
                                @endforeach
                                <?php
                                $user = \App\User::where('role', null)->where('deleted', 0)->get();
                                ?>
                                <button class="tablinks" onclick="openCity(event, 'No_Roles')">No Roles
                                    ({{ count($user) }})</button>
                                <button class="tablinks" onclick="openCity(event, 'Deleted')">Deleted
                                    ({{ count($deleted) }})</button>
                            </div>

                            <!-- Tab content -->
                            @foreach ($roles as $key2 => $val2)
                                <div id="{{ str_replace(' ', '_', $val2->name) }}" class="tabcontent">
                                    <table id="example{{ $key2 }}"
                                        class="table table-bordered table-striped text-nowrap key-buttons"
                                        style="width:100% !important;">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0">JOINING DATE</th>
                                                <th class="border-bottom-0">NAME</th>
                                                <th class="border-bottom-0">EMAIL</th>
                                                <th class="border-bottom-0">ROLE</th>
                                                <th class="border-bottom-0">PHONE</th>
                                                <th class="border-bottom-0">STATUS</th>
                                                <th class="border-bottom-0">CODE</th>
                                                <th class="border-bottom-0">EDIT</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (isset($val2->users[0]))
                                                @foreach ($val2->users as $key => $val)
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i A') }}
                                                        </td>
                                                        <td class="d-flex">
                                                            <span
                                                                class="rounded-circle p-1 badge badge-{{ $val->is_login == 1 ? 'success' : 'danger' }} my-auto mr-1"
                                                                style="display: block;width:0;height: 1px;"></span>
                                                            {{ $val->name }}
                                                            @if ($val->slug)
                                                                <br>
                                                                ({{ $val->slug }})
                                                            @endif
                                                        </td>
                                                        <td>{{ $val->email }}</td>
                                                        <td>{{ $val2->name }}</td>
                                                        <td>{{ $val->phone }}</td>
                                                        <td>
                                                            <span
                                                                class="badge badge-{{ $val->status == '1' ? 'success' : 'danger' }} text-light">{{ $val->status == '1' ? 'Active' : 'Not Active' }}</span>
                                                            @if ($val2->name != 'Admin')
                                                                @if ($val->freeze == 1)
                                                                    <br>
                                                                    <br>
                                                                    <span
                                                                        class="badge badge-danger text-light">Freezed</span>
                                                                    <br>
                                                                    {{ $val->freeze_reason }}
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>{{ $val->code }}</td>
                                                        <td>
                                                            @if (Auth::user()->id == 1)
                                                                @if ($val->id > 1)
                                                                    <div class="dropdown">
                                                                        <button type="button"
                                                                            class="btn btn-dark dropdown-toggle"
                                                                            data-toggle="dropdown">
                                                                            <i class="fe fe-arrow-down mr-2"></i>Edit
                                                                        </button>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item"
                                                                                href="{{ url('edit_employee' . '/' . $val->id) }}">Edit</a>
                                                                            @if ($val->status == 0)
                                                                                <a class="dropdown-item"
                                                                                    href="{{ url('user_active_new' . '/' . $val->id) }}">Active</a>
                                                                            @else
                                                                                <a class="dropdown-item"
                                                                                    href="{{ url('user_deactive_new' . '/' . $val->id) }}">Deactivate</a>
                                                                            @endif
                                                                            {{-- @if ($val->freeze == 1)
                                                                                <a class="dropdown-item"
                                                                                    href="{{ url('freeze-unfreeze-new' . '/' . $val->id) }}">Unfreeze</a>
                                                                            @else
                                                                                <a class="dropdown-item"
                                                                                    href="{{ url('freeze-unfreeze-new' . '/' . $val->id) }}">Freeze</a>
                                                                            @endif --}}
                                                                            @if ($val->freeze == 1)
                                                                                <a class="dropdown-item freeze-unfreeze-btn"
                                                                                    data-id="{{ $val->id }}"
                                                                                    data-action="unfreeze"
                                                                                    href="#">Unfreeze</a>
                                                                            @else
                                                                                <a class="dropdown-item freeze-unfreeze-btn"
                                                                                    data-id="{{ $val->id }}"
                                                                                    data-action="freeze"
                                                                                    href="#">Freeze</a>
                                                                            @endif
                                                                            <a class="dropdown-item"
                                                                                href="{{ url('reset' . '/' . $val->id) }}"
                                                                                target="_blank">Reset</a>
                                                                            <a class="dropdown-item"
                                                                                href="{{ url('delete_employee' . '/' . $val->id) }}">Delete</a>
                                                                            <a class="dropdown-item"
                                                                                href="{{ url('screen_shots' . '/' . $val->id) }}"
                                                                                target="_blank">Screen Shots</a>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @else
                                                                @if ($val2->name != 'Admin')
                                                                    <div class="dropdown">
                                                                        <button type="button"
                                                                            class="btn btn-dark dropdown-toggle"
                                                                            data-toggle="dropdown">
                                                                            <i class="fe fe-arrow-down mr-2"></i>Edit
                                                                        </button>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item"
                                                                                href="{{ url('edit_employee' . '/' . $val->id) }}">Edit</a>
                                                                            @if ($val->status == 0)
                                                                                <a class="dropdown-item"
                                                                                    href="{{ url('user_active_new' . '/' . $val->id) }}">Active</a>
                                                                            @else
                                                                                <a class="dropdown-item"
                                                                                    href="{{ url('user_deactive_new' . '/' . $val->id) }}">Deactivate</a>
                                                                            @endif
                                                                            {{-- @if ($val->freeze == 1)
                                                                                <a class="dropdown-item"
                                                                                    href="{{ url('freeze-unfreeze-new' . '/' . $val->id) }}">Unfreeze</a>
                                                                            @else
                                                                                <a class="dropdown-item"
                                                                                    href="{{ url('freeze-unfreeze-new' . '/' . $val->id) }}">Freeze</a>
                                                                            @endif --}}
                                                                            @if ($val->freeze == 1)
                                                                                <a class="dropdown-item freeze-unfreeze-btn"
                                                                                    data-id="{{ $val->id }}"
                                                                                    data-action="unfreeze"
                                                                                    href="#">Unfreeze</a>
                                                                            @else
                                                                                <a class="dropdown-item freeze-unfreeze-btn"
                                                                                    data-id="{{ $val->id }}"
                                                                                    data-action="freeze"
                                                                                    href="#">Freeze</a>
                                                                            @endif
                                                                            <a class="dropdown-item"
                                                                                href="{{ url('reset' . '/' . $val->id) }}"
                                                                                target="_blank">Reset</a>
                                                                            <a class="dropdown-item"
                                                                                href="{{ url('delete_employee' . '/' . $val->id) }}">Delete</a>
                                                                            <a class="dropdown-item"
                                                                                href="{{ url('screen_shots' . '/' . $val->id) }}"
                                                                                target="_blank">Screen Shots</a>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach

                            <div id="No_Roles" class="tabcontent">
                                <table id="example88" class="table table-bordered table-striped key-buttons"
                                    style="width: 100% !important;">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0">JOINING DATE</th>
                                            <th class="border-bottom-0">NAME</th>
                                            <th class="border-bottom-0">EMAIL</th>
                                            <th class="border-bottom-0">ROLE</th>
                                            <th class="border-bottom-0">PHONE</th>
                                            <th class="border-bottom-0">STATUS</th>
                                            <th class="border-bottom-0">CODE</th>
                                            <th class="border-bottom-0">EDIT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user as $val)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i A') }}
                                                </td>
                                                <td>
                                                    {{ $val->name }}
                                                    @if ($val->slug)
                                                        <br>
                                                        ({{ $val->slug }})
                                                    @endif
                                                </td>
                                                <td>{{ $val->email }}</td>
                                                <td>No Role</td>
                                                <td>{{ $val->phone }}</td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $val->status == '1' ? 'success' : 'danger' }} text-light">{{ $val->status == '1' ? 'Active' : 'Not Active' }}</span>
                                                    @if ($val->freeze == 1)
                                                        <br>
                                                        <br>
                                                        <span class="badge badge-danger text-light">Freezed</span>
                                                        <br>
                                                        {{ $val->freeze_reason }}
                                                    @endif
                                                </td>
                                                <td>{{ $val->code }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button type="button" class="btn btn-dark dropdown-toggle"
                                                            data-toggle="dropdown">
                                                            <i class="fe fe-arrow-down mr-2"></i>Edit
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item"
                                                                href="{{ url('edit_employee' . '/' . $val->id) }}">Edit</a>
                                                            @if ($val->status == 0)
                                                                <a class="dropdown-item"
                                                                    href="{{ url('user_active_new' . '/' . $val->id) }}">Active</a>
                                                            @else
                                                                <a class="dropdown-item"
                                                                    href="{{ url('user_deactive_new' . '/' . $val->id) }}">Deactivate</a>
                                                            @endif
                                                            {{-- @if ($val->freeze == 1)
                                                                <a class="dropdown-item"
                                                                    href="{{ url('freeze-unfreeze-new' . '/' . $val->id) }}">Unfreeze</a>
                                                            @else
                                                                <a class="dropdown-item"
                                                                    href="{{ url('freeze-unfreeze-new' . '/' . $val->id) }}">Freeze</a>
                                                            @endif --}}
                                                            @if ($val->freeze == 1)
                                                                <a class="dropdown-item freeze-unfreeze-btn"
                                                                    data-id="{{ $val->id }}" data-action="unfreeze"
                                                                    href="#">Unfreeze</a>
                                                            @else
                                                                <a class="dropdown-item freeze-unfreeze-btn"
                                                                    data-id="{{ $val->id }}" data-action="freeze"
                                                                    href="#">Freeze</a>
                                                            @endif
                                                            <a class="dropdown-item"
                                                                href="{{ url('reset' . '/' . $val->id) }}"
                                                                target="_blank">Reset</a>
                                                            <a class="dropdown-item"
                                                                href="{{ url('delete_employee' . '/' . $val->id) }}">Delete</a>
                                                            <a class="dropdown-item"
                                                                href="{{ url('screen_shots' . '/' . $val->id) }}"
                                                                target="_blank">Screen Shots</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div id="Deleted" class="tabcontent">
                                <table id="example99" class="table table-bordered table-striped key-buttons"
                                    style="width: 100% !important;">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0">JOINING DATE</th>
                                            <th class="border-bottom-0">NAME</th>
                                            <th class="border-bottom-0">EMAIL</th>
                                            <th class="border-bottom-0">ROLE</th>
                                            <th class="border-bottom-0">PHONE</th>
                                            <th class="border-bottom-0">STATUS</th>
                                            <th class="border-bottom-0">CODE</th>
                                            <th class="border-bottom-0">EDIT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($deleted as $val)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i A') }}
                                                </td>
                                                <td>
                                                    {{ $val->name }}
                                                    @if ($val->slug)
                                                        <br>
                                                        ({{ $val->slug }})
                                                    @endif
                                                </td>
                                                <td>{{ $val->email }}</td>
                                                <td>{{ get_role($val->role, 'name') }}</td>
                                                <td>{{ $val->phone }}</td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $val->status == '1' ? 'success' : 'danger' }} text-light">{{ $val->status == '1' ? 'Active' : 'Not Active' }}</span>
                                                    @if ($val->freeze == 1)
                                                        <br>
                                                        <br>
                                                        <span class="badge badge-danger text-light">Freezed</span>
                                                        <br>
                                                        {{ $val->freeze_reason }}
                                                    @endif
                                                </td>
                                                <td>{{ $val->code }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button type="button" class="btn btn-dark dropdown-toggle"
                                                            data-toggle="dropdown">
                                                            <i class="fe fe-arrow-down mr-2"></i>Edit
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item"
                                                                href="{{ url('edit_employee' . '/' . $val->id) }}">Edit</a>
                                                            @if ($val->status == 0)
                                                                <a class="dropdown-item"
                                                                    href="{{ url('user_active_new' . '/' . $val->id) }}">Active</a>
                                                            @else
                                                                <a class="dropdown-item"
                                                                    href="{{ url('user_deactive_new' . '/' . $val->id) }}">Deactivate</a>
                                                            @endif
                                                            {{-- @if ($val->freeze == 1)
                                                                <a class="dropdown-item"
                                                                    href="{{ url('freeze-unfreeze-new' . '/' . $val->id) }}">Unfreeze</a>
                                                            @else
                                                                <a class="dropdown-item"
                                                                    href="{{ url('freeze-unfreeze-new' . '/' . $val->id) }}">Freeze</a>
                                                            @endif --}}
                                                            @if ($val->freeze == 1)
                                                                <a class="dropdown-item freeze-unfreeze-btn"
                                                                    data-id="{{ $val->id }}" data-action="unfreeze"
                                                                    href="#">Unfreeze</a>
                                                            @else
                                                                <a class="dropdown-item freeze-unfreeze-btn"
                                                                    data-id="{{ $val->id }}" data-action="freeze"
                                                                    href="#">Freeze</a>
                                                            @endif
                                                            <a class="dropdown-item"
                                                                href="{{ url('recover_employee' . '/' . $val->id) }}">Restore</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <br>
                        <br>
                    </div>
                </div>
            </div>
            <!--/div-->
        </div>
    </div>
    <!-- /Row -->

    <!-- Modal HTML -->
    <div class="modal fade" id="freezeModal" tabindex="-1" aria-labelledby="freezeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="freezeModalLabel">Add Reason</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="freezeForm" action="" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason</label>
                            <input type="text" class="form-control" id="reason" name="reason" required>
                        </div>
                        <button type="submit" class="btn btn-primary" id="submitReason">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('extraScript')
    <script>
        $(document).ready(function() {
            for (var i = 0; i < 100; i++) {
                $(`#example${i}`).DataTable();
            }
        });
        document.getElementById("defaultOpen").click();

        function openCity(evt, cityName) {
            // Declare all variables
            var i, tabcontent, tablinks;

            // Get all elements with class="tabcontent" and hide them
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            // Get all elements with class="tablinks" and remove the class "active"
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            // Show the current tab, and add an "active" class to the button that opened the tab
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        // $(".order_taker_quote").change(function(){
        //     var id = $(this).children('input').attr("id");
        //     var order_taker_quote = $(this).children('input');
        //     var status = 0;
        //     if(order_taker_quote.prop("checked") == true)
        //     {
        //         status = 1;
        //     }
        //     // console.log(status);
        //     // console.log(id);
        //     $.ajax({
        //         url:"{{ url('/show_own_order') }}",
        //         type:"POST",
        //         dataType:"json",
        //         data:{id:id,order_taker_quote:status},
        //         success:function(res){
        //              $("#session_msg").children().remove();
        //             $("#session_msg").append(`
    //                 <div class="alert alert-success alert-dismissible fade show" role="alert">
    //                   <strong>${res.msg}</strong>
    //                   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    //                     <span aria-hidden="true">&times;</span>
    //                   </button>
    //                 </div>
    //             `);
        //         }
        //     });
        // })

        $(document).ready(function() {
            $('.freeze-unfreeze-btn').on('click', function(e) {
                e.preventDefault();

                var action = $(this).data('action');
                var userId = $(this).data('id');

                if (action === 'freeze') {
                    $('#freezeModalLabel').text('Reason for Freezing');
                } else {
                    $('#freezeModalLabel').text('Reason for Unfreezing');
                }

                $('#submitReason').data('action', action).data('userId', userId);

                $('#freezeModal').modal('show');
            });

            $('#submitReason').on('click', function(e) {
                e.preventDefault();

                var action = $(this).data('action');
                var userId = $(this).data('userId');
                var reason = $('#reason').val();
                var token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '/freeze-unfreeze-new/' + userId,
                    method: 'POST',
                    data: {
                        _token: token, // CSRF token
                        action: action,
                        reason: reason
                    },
                    success: function(response) {
                        $('#freezeModal').modal('hide');
                        alert('Status updated successfully!');
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('An error occurred. Please try again.');
                    }
                });
            });
        });
    </script>
@endsection
