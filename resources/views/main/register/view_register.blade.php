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
                                                <th class="border-bottom-0">HR</th>
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
                                                        @php
                                                            $hrEmp = $hrEmployees[$val->id] ?? null;
                                                            $hrStatusColors = [1=>'success',2=>'secondary',3=>'danger',4=>'warning',5=>'info',6=>'primary',7=>'warning',8=>'secondary',9=>'info',10=>'primary'];
                                                            $hrColor = $hrEmp ? ($hrStatusColors[$hrEmp->hr_status_id] ?? 'secondary') : 'light';
                                                        @endphp
                                                        <td>
                                                            @if($hrEmp)
                                                                <span class="badge badge-{{ $hrColor }} text-light d-block mb-1">{{ $hrEmp->hr_status }}</span>
                                                                <a href="{{ route('hr.admin.employee', $val->id) }}" target="_blank" class="btn btn-xs btn-primary d-block mb-1" style="font-size:11px;padding:2px 6px;">
                                                                    <i class="fe fe-user mr-1"></i>HR Profile
                                                                </a>
                                                            @else
                                                                <span class="badge badge-light text-muted d-block mb-1">Not Linked</span>
                                                            @endif
                                                            @if($val->id !== Auth::id())
                                                                <a href="{{ route('scope.enter', $val->id) }}" class="btn btn-xs btn-warning text-dark font-weight-bold d-block" style="font-size:11px;padding:2px 6px;">
                                                                    <i class="fe fe-eye mr-1"></i>Scope
                                                                </a>
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
                                                                                <a class="dropdown-item review-employee-btn" href="#"
                                                                                    data-toggle="modal" data-target="#employeeReviewModal"
                                                                                    data-user-id="{{ $val->id }}">Active</a>
                                                                            @else
                                                                                <a class="dropdown-item review-employee-btn" href="#"
                                                                                    data-toggle="modal" data-target="#employeeReviewModal"
                                                                                    data-user-id="{{ $val->id }}">Deactivate</a>
                                                                            @endif
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
                                                                                <a class="dropdown-item review-employee-btn" href="#"
                                                                                    data-toggle="modal" data-target="#employeeReviewModal"
                                                                                    data-user-id="{{ $val->id }}">Active</a>
                                                                            @else
                                                                                <a class="dropdown-item review-employee-btn" href="#"
                                                                                    data-toggle="modal" data-target="#employeeReviewModal"
                                                                                    data-user-id="{{ $val->id }}">Deactivate</a>
                                                                            @endif
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
                                                                <a class="dropdown-item review-employee-btn" href="#"
                                                                    data-toggle="modal" data-target="#employeeReviewModal"
                                                                    data-user-id="{{ $val->id }}">Active</a>
                                                            @else
                                                                <a class="dropdown-item review-employee-btn" href="#"
                                                                    data-toggle="modal" data-target="#employeeReviewModal"
                                                                    data-user-id="{{ $val->id }}">Deactivate</a>
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
                                                                <a class="dropdown-item review-employee-btn" href="#"
                                                                    data-toggle="modal" data-target="#employeeReviewModal"
                                                                    data-user-id="{{ $val->id }}">Active</a>
                                                            @else
                                                                <a class="dropdown-item review-employee-btn" href="#"
                                                                    data-toggle="modal" data-target="#employeeReviewModal"
                                                                    data-user-id="{{ $val->id }}">Deactivate</a>
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

    <!-- Employee Review Modal -->
    <div class="modal fade" id="employeeReviewModal" tabindex="-1" aria-labelledby="employeeReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" style="max-width:95%;width:95%;margin:1.5rem auto;">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white py-2">
                    <h5 class="modal-title mb-0" id="employeeReviewModalLabel">
                        <i class="fe fe-user mr-2"></i>Employee Review
                    </h5>
                    <div class="d-flex align-items-center">
                        <a id="rev_hr_profile_btn" href="#" target="_blank"
                           class="btn btn-sm btn-primary mr-2" style="display:none!important;">
                            <i class="fe fe-external-link mr-1"></i>Edit HR Profile
                        </a>
                        <button type="button" class="close text-white mb-0" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="modal-body p-0">
                    <!-- Warning Banner -->
                    <div class="alert alert-warning rounded-0 mb-0 border-0 border-bottom py-2 px-3">
                        <strong><i class="fe fe-alert-triangle mr-1"></i>Review before changing status</strong>
                        <ul class="mb-0 mt-1 small pl-3">
                            <li>Setting <strong>Agent Portal Active</strong> allows this user to log in to the agent portal.</li>
                            <li>If HR Status is <strong>"Document Verification"</strong> or <strong>"Pending Contract"</strong>, the user will be redirected to the HR portal to complete their profile first.</li>
                            <li>Verify all documents and HR status before activating.</li>
                        </ul>
                    </div>

                    <!-- Loading Spinner -->
                    <div id="reviewModalLoader" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status" style="width:3rem;height:3rem;"></div>
                        <p class="mt-3 text-muted">Loading employee details...</p>
                    </div>

                    <!-- Content (hidden until loaded) -->
                    <div id="reviewModalContent" style="display:none;" class="p-3">

                        <!-- Profile Header -->
                        <div class="d-flex align-items-center mb-3 p-3 bg-light rounded border">
                            <img id="rev_profile_img" src="" alt="Profile"
                                 class="rounded-circle border border-primary mr-3 flex-shrink-0"
                                 style="width:90px;height:90px;object-fit:cover;">
                            <div class="flex-grow-1">
                                <h5 class="mb-1 font-weight-bold" id="rev_full_name">—</h5>
                                <div class="text-muted small mb-1" id="rev_designation_dept">—</div>
                                <div>
                                    <span id="rev_agent_badge" class="badge mr-1"></span>
                                    <span id="rev_hr_badge" class="badge mr-1"></span>
                                    <span id="rev_employment_badge" class="badge badge-light border"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Row 1: Profile Info + Status Controls -->
                        <div class="row">
                            <!-- Profile Info -->
                            <div class="col-md-4">
                                <div class="card border shadow-sm h-100">
                                    <div class="card-header bg-light font-weight-bold small py-2">👤 Profile Info</div>
                                    <div class="card-body p-2">
                                        <table class="table table-sm table-borderless small mb-0">
                                            <tbody>
                                            <tr><td class="font-weight-bold text-muted" style="width:45%;white-space:nowrap;">Code</td><td id="rev_employee_code">—</td></tr>
                                            <tr><td class="font-weight-bold text-muted">Email</td><td id="rev_email" style="word-break:break-all;">—</td></tr>
                                            <tr><td class="font-weight-bold text-muted">Phone</td><td id="rev_phone">—</td></tr>
                                            <tr><td class="font-weight-bold text-muted">Phone 2</td><td id="rev_phone2">—</td></tr>
                                            <tr><td class="font-weight-bold text-muted">CNIC</td><td id="rev_cnic">—</td></tr>
                                            <tr><td class="font-weight-bold text-muted">Father</td><td id="rev_father_name">—</td></tr>
                                            <tr><td class="font-weight-bold text-muted">Mother</td><td id="rev_mother_name">—</td></tr>
                                            <tr><td class="font-weight-bold text-muted">DOB</td><td id="rev_dob">—</td></tr>
                                            <tr><td class="font-weight-bold text-muted">Gender</td><td id="rev_gender">—</td></tr>
                                            <tr><td class="font-weight-bold text-muted">Marital</td><td id="rev_marital_status">—</td></tr>
                                            <tr><td class="font-weight-bold text-muted">Emergency</td><td id="rev_emergency_contact">—</td></tr>
                                            <tr><td class="font-weight-bold text-muted">Address</td><td id="rev_address">—</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Job Info -->
                            <div class="col-md-4">
                                <div class="card border shadow-sm mb-3">
                                    <div class="card-header bg-light font-weight-bold small py-2">💼 Job Details</div>
                                    <div class="card-body p-2">
                                        <table class="table table-sm table-borderless small mb-0">
                                            <tbody>
                                            <tr><td class="font-weight-bold text-muted" style="width:45%;white-space:nowrap;">Joining Date</td><td id="rev_joining_date">—</td></tr>
                                            <tr><td class="font-weight-bold text-muted">Department</td><td id="rev_department">—</td></tr>
                                            <tr><td class="font-weight-bold text-muted">Designation</td><td id="rev_designation">—</td></tr>
                                            <tr><td class="font-weight-bold text-muted">Emp. Type</td><td id="rev_employment_type">—</td></tr>
                                            <tr><td class="font-weight-bold text-muted">Basic Salary</td><td id="rev_basic_salary">—</td></tr>
                                            <tr><td class="font-weight-bold text-muted">Shift</td><td id="rev_shift">—</td></tr>
                                            <tr><td class="font-weight-bold text-muted">Shift Hours</td><td id="rev_shift_hours">—</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card border shadow-sm" id="rev_commission_card">
                                    <div class="card-header bg-light font-weight-bold small py-2">💰 Commission</div>
                                    <div class="card-body p-2">
                                        <table class="table table-sm table-borderless small mb-0">
                                            <tbody>
                                            <tr><td class="font-weight-bold text-muted" style="width:45%">Plan</td><td id="rev_commission_title">—</td></tr>
                                            <tr><td class="font-weight-bold text-muted">Type</td><td id="rev_commission_type">—</td></tr>
                                            <tr><td class="font-weight-bold text-muted">Value</td><td id="rev_commission_value">—</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Controls -->
                            <div class="col-md-4">
                                <div class="card border shadow-sm mb-3">
                                    <div class="card-header bg-light font-weight-bold small py-2">🔑 Agent Portal Status</div>
                                    <div class="card-body p-3">
                                        <p class="small text-muted mb-2">Controls whether this user can log in to the agent portal.</p>
                                        <div id="rev_agent_status_btns"></div>
                                        <div id="rev_agent_status_msg" class="mt-2 small text-success" style="display:none;"></div>
                                    </div>
                                </div>
                                <div class="card border shadow-sm">
                                    <div class="card-header bg-light font-weight-bold small py-2">📋 HR Status</div>
                                    <div class="card-body p-3">
                                        <p class="small text-muted mb-2">Employee's current HR portal status.</p>
                                        <select id="rev_hr_status_select" class="form-control form-control-sm mb-2"></select>
                                        <button id="rev_save_hr_status" class="btn btn-sm btn-primary btn-block">Save HR Status</button>
                                        <div id="rev_hr_status_msg" class="mt-2 small text-success" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Leave Quotas -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card border shadow-sm">
                                    <div class="card-header bg-light font-weight-bold small py-2">📅 Leave Quotas (Active)</div>
                                    <div class="card-body p-2">
                                        <div id="rev_leave_quotas_row" class="row"></div>
                                        <p id="rev_no_leave_quotas" class="text-muted small mb-0" style="display:none;">No active leave quotas assigned.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Documents -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card border shadow-sm">
                                    <div class="card-header bg-light font-weight-bold small py-2 d-flex align-items-center justify-content-between">
                                        <span>📄 Documents</span>
                                        <button type="button" id="rev_bulk_verify_btn" class="btn btn-sm btn-success py-1 px-2" style="font-size:11px;display:none;">
                                            ✔ Approve All
                                        </button>
                                    </div>
                                    <div class="card-body p-2">
                                        <div id="rev_documents_row" class="row"></div>
                                        <p id="rev_no_documents" class="text-muted small mb-0" style="display:none;">No documents uploaded.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contract -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card border shadow-sm">
                                    <div class="card-header bg-light font-weight-bold small py-2 d-flex align-items-center justify-content-between">
                                        <span>📝 Contract</span>
                                        <span id="rev_contract_status_badge" class="badge badge-secondary">No Contract</span>
                                    </div>
                                    <div class="card-body p-3">
                                        <div id="rev_contract_pending_banner" class="alert alert-warning py-2 mb-3" style="display:none;">
                                            <i class="fe fe-alert-circle mr-1"></i>
                                            <strong>Pending employee acceptance.</strong> The employee has not yet accepted the current contract.
                                        </div>
                                        <div id="rev_contract_accepted_banner" class="alert alert-success py-2 mb-3" style="display:none;">
                                            <i class="fe fe-check-circle mr-1"></i>
                                            <strong>Contract accepted</strong> by employee on <span id="rev_contract_accepted_at"></span>.
                                        </div>
                                        <div id="rev_contract_preview" class="border rounded p-3 mb-3 bg-light small" style="min-height:60px;max-height:250px;overflow-y:auto;display:none;"></div>
                                        <p class="small text-muted mb-1">Write or update the contract below. Saving will notify the employee to review and accept.</p>
                                        <div id="rev_contract_quill" style="height:220px;"></div>
                                        <div class="mt-2 d-flex align-items-center">
                                            <button id="rev_save_contract_btn" class="btn btn-sm btn-primary mr-2">
                                                <i class="fe fe-save mr-1"></i>Save Contract
                                            </button>
                                            <span id="rev_contract_save_msg" class="small" style="display:none;"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <a id="rev_hr_profile_btn_footer" href="#" target="_blank"
                       class="btn btn-sm btn-outline-primary" style="display:none;">
                        <i class="fe fe-external-link mr-1"></i>Edit HR Profile
                    </a>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

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

        // ── Employee Review Modal ──────────────────────────────────────────────
        var _reviewUserId = null;

        function capitalise(str) {
            return str ? str.charAt(0).toUpperCase() + str.slice(1) : '—';
        }

        $(document).on('click', '.review-employee-btn', function (e) {
            e.preventDefault();
            _reviewUserId = $(this).data('user-id');

            // Reset state
            $('#reviewModalLoader').show();
            $('#reviewModalContent').hide();
            $('#rev_agent_status_msg').hide();
            $('#rev_hr_status_msg').hide();
            $('#rev_hr_profile_btn').hide().attr('href', '#');
            $('#rev_hr_profile_btn_footer').hide().attr('href', '#');
            $('#employeeReviewModal').modal('show');

            $.getJSON('/employee-review/data/' + _reviewUserId, function (data) {
                var agent      = data.agent        || {};
                var hr         = data.hr_employee  || null;
                var docs       = data.documents    || [];
                var leaves     = data.leave_quotas || [];
                var statuses   = data.hr_statuses  || [];
                var hrBaseUrl  = data.hr_base_url  || '';

                // HR Profile button
                if (hr) {
                    var hrProfileUrl = '{{ route("hr.admin.employee", "__ID__") }}'.replace('__ID__', agent.id);
                    $('#rev_hr_profile_btn').attr('href', hrProfileUrl).css('display', 'inline-block');
                    $('#rev_hr_profile_btn_footer').attr('href', hrProfileUrl).css('display', 'inline-block');
                }

                // Profile header
                var profileSrc = (hr && hr.profile_path)
                    ? hrBaseUrl + '/' + hr.profile_path
                    : '/assets/images/default_images/profile_image.png';
                $('#rev_profile_img').attr('src', profileSrc);
                $('#rev_full_name').text(hr ? (hr.full_name || agent.name) : agent.name);
                $('#rev_designation_dept').text(
                    [hr ? hr.designation_name : null, hr ? hr.department_name : null]
                        .filter(Boolean).join(' | ') || agent.email
                );

                // Status badges
                $('#rev_agent_badge')
                    .text(agent.status == 1 ? 'Agent: Active' : 'Agent: Inactive')
                    .attr('class', 'badge ' + (agent.status == 1 ? 'badge-success' : 'badge-danger'));
                $('#rev_hr_badge')
                    .text('HR: ' + (hr ? (hr.hr_status_name || 'Linked') : 'Not Linked'))
                    .attr('class', 'badge badge-info');
                $('#rev_employment_badge')
                    .text(hr && hr.employment_type_name ? hr.employment_type_name : '')
                    .toggle(!!(hr && hr.employment_type_name));

                // Profile Info
                $('#rev_employee_code').text(hr ? (hr.employee_code || '—') : '—');
                $('#rev_email').text(hr ? (hr.email || agent.email || '—') : (agent.email || '—'));
                $('#rev_phone').text(hr ? (hr.phone || agent.phone || '—') : (agent.phone || '—'));
                $('#rev_phone2').text(hr ? (hr.phone2 || '—') : '—');
                $('#rev_cnic').text(hr ? (hr.cnic || '—') : '—');
                $('#rev_father_name').text(hr ? (hr.father_name || '—') : '—');
                $('#rev_mother_name').text(hr ? (hr.mother_name || '—') : '—');
                $('#rev_dob').text(hr ? (hr.dob || '—') : '—');
                $('#rev_gender').text(hr ? capitalise(hr.gender) : '—');
                $('#rev_marital_status').text(hr ? capitalise(hr.marital_status) : '—');
                $('#rev_emergency_contact').text(hr ? (hr.emergency_contact || '—') : '—');
                $('#rev_address').text(hr ? [hr.address, hr.city, hr.state, hr.country].filter(Boolean).join(', ') || '—' : '—');

                // Job Details
                $('#rev_joining_date').text(hr ? (hr.joining_date || '—') : '—');
                $('#rev_department').text(hr ? (hr.department_name || '—') : '—');
                $('#rev_designation').text(hr ? (hr.designation_name || '—') : '—');
                $('#rev_employment_type').text(hr ? (hr.employment_type_name || '—') : '—');
                $('#rev_basic_salary').text(hr && hr.basic_salary ? 'PKR ' + parseInt(hr.basic_salary).toLocaleString() : '—');
                $('#rev_shift').text(hr ? (hr.shift_name || '—') : '—');
                if (hr && hr.shift_start && hr.shift_end) {
                    $('#rev_shift_hours').text(hr.shift_start.substring(0,5) + ' – ' + hr.shift_end.substring(0,5));
                } else {
                    $('#rev_shift_hours').text('—');
                }

                // Commission
                if (hr && hr.commission_title) {
                    $('#rev_commission_title').text(hr.commission_title);
                    $('#rev_commission_type').text(hr.commission_type_name || '—');
                    var commVal = hr.commission_type_name && hr.commission_type_name.toLowerCase() === 'percentage'
                        ? hr.commission_value + '%'
                        : 'PKR ' + parseFloat(hr.commission_value || 0).toLocaleString();
                    $('#rev_commission_value').text(commVal);
                    $('#rev_commission_card').show();
                } else {
                    $('#rev_commission_title').text('—');
                    $('#rev_commission_type').text('—');
                    $('#rev_commission_value').text('—');
                }

                // Agent status buttons
                if (agent.status == 1) {
                    $('#rev_agent_status_btns').html(
                        '<button class="btn btn-sm btn-danger btn-block" id="rev_deactivate_btn"><i class="fe fe-user-x mr-1"></i>Deactivate Agent</button>'
                        + '<p class="text-success small mt-1 mb-0"><i class="fe fe-check-circle mr-1"></i>Currently Active — can log in</p>'
                    );
                } else {
                    $('#rev_agent_status_btns').html(
                        '<button class="btn btn-sm btn-success btn-block" id="rev_activate_btn"><i class="fe fe-user-check mr-1"></i>Activate Agent</button>'
                        + '<p class="text-danger small mt-1 mb-0"><i class="fe fe-x-circle mr-1"></i>Currently Inactive — cannot log in</p>'
                    );
                }

                // HR status dropdown
                var selectHtml = hr ? '' : '<option value="">-- No HR Record --</option>';
                if (hr) {
                    statuses.forEach(function (s) {
                        selectHtml += '<option value="' + s.id + '"' + (s.id == hr.employee_status_id ? ' selected' : '') + '>' + s.name + '</option>';
                    });
                }
                $('#rev_hr_status_select').html(selectHtml).prop('disabled', !hr);

                // Leave Quotas
                $('#rev_leave_quotas_row').empty();
                if (leaves.length > 0) {
                    leaves.forEach(function (lv) {
                        var remaining = lv.assigned_quota - lv.used_quota;
                        var pct = lv.assigned_quota > 0 ? Math.round((lv.used_quota / lv.assigned_quota) * 100) : 0;
                        var barColor = pct >= 80 ? 'bg-danger' : (pct >= 50 ? 'bg-warning' : 'bg-success');
                        var lvHtml = '<div class="col-sm-6 col-md-4 col-lg-3 mb-2">'
                            + '<div class="border rounded p-2 text-center">'
                            + '<div class="font-weight-bold small mb-1">' + (lv.leave_type || 'Leave') + '</div>'
                            + '<div class="small text-muted">Assigned: <strong>' + lv.assigned_quota + '</strong> &nbsp; Used: <strong class="text-danger">' + lv.used_quota + '</strong></div>'
                            + '<div class="small text-success mb-1">Remaining: <strong>' + remaining + '</strong></div>'
                            + '<div class="progress rounded-pill mb-1" style="height:6px;">'
                            + '<div class="progress-bar ' + barColor + '" style="width:' + pct + '%;"></div></div>'
                            + '<small class="text-muted d-block">' + (lv.valid_from || '') + ' → ' + (lv.valid_to || '') + '</small>'
                            + '</div></div>';
                        $('#rev_leave_quotas_row').append(lvHtml);
                    });
                    $('#rev_no_leave_quotas').hide();
                } else {
                    $('#rev_no_leave_quotas').show();
                }

                // Documents
                $('#rev_documents_row').empty();
                if (docs.length > 0) {
                    docs.forEach(function (doc) {
                        var ext = doc.file_path ? doc.file_path.split('.').pop().toLowerCase() : '';
                        var fullUrl = doc.file_path ? hrBaseUrl + '/' + doc.file_path : '';
                        var thumb = '';
                        if (['jpg','jpeg','png','gif','bmp','webp'].indexOf(ext) !== -1) {
                            thumb = '<a href="' + fullUrl + '" target="_blank">'
                                  + '<img src="' + fullUrl + '" class="img-fluid rounded mb-1"'
                                  + ' style="width:100%;height:90px;object-fit:cover;"'
                                  + ' onerror="this.style.display=\'none\';this.nextElementSibling.style.display=\'block\';">'
                                  + '<i class="fe fe-image text-secondary d-none mb-1" style="font-size:48px;"></i>'
                                  + '</a>';
                        } else if (ext === 'pdf') {
                            thumb = '<a href="' + fullUrl + '" target="_blank">'
                                  + '<i class="fe fe-file-text text-danger d-block mb-1" style="font-size:48px;"></i></a>';
                        } else {
                            thumb = fullUrl
                                ? '<a href="' + fullUrl + '" target="_blank"><i class="fe fe-paperclip text-primary d-block mb-1" style="font-size:48px;"></i></a>'
                                : '<i class="fe fe-paperclip text-muted d-block mb-1" style="font-size:48px;"></i>';
                        }
                        var isVerified = doc.status == 1;
                        var viewLink = fullUrl
                            ? '<a href="' + fullUrl + '" target="_blank" class="btn btn-xs btn-outline-primary mt-1 d-block" style="font-size:10px;">View</a>'
                            : '';
                        var checkedAttr = isVerified ? 'checked' : '';
                        var verifyToggle = '<div class="d-flex align-items-center justify-content-center mt-1">'
                            + '<div class="custom-control custom-switch">'
                            + '<input type="checkbox" class="custom-control-input rev-doc-verify-toggle" id="revDocToggle' + doc.id + '"'
                            + ' data-doc-id="' + doc.id + '" ' + checkedAttr + '>'
                            + '<label class="custom-control-label rev-doc-verify-label" for="revDocToggle' + doc.id + '" style="font-size:11px;color:' + (isVerified ? '#28a745' : '#ffc107') + ';">'
                            + (isVerified ? 'Verified' : 'Pending') + '</label>'
                            + '</div></div>';
                        var borderColor = isVerified ? '#28a745' : '#ffc107';
                        var docHtml = '<div class="col-sm-6 col-md-3 col-lg-2 mb-2 text-center" id="revDocCard' + doc.id + '">'
                            + '<div class="border rounded p-2 h-100" style="border-color:' + borderColor + '!important;border-width:2px!important;">'
                            + thumb
                            + '<p class="small font-weight-bold mb-1" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:100%;">'
                            + (doc.doc_type || doc.file_name) + '</p>'
                            + viewLink
                            + verifyToggle
                            + '</div></div>';
                        $('#rev_documents_row').append(docHtml);
                    });
                    $('#rev_no_documents').hide();
                    $('#rev_bulk_verify_btn').show().data('hr-employee-id', hr ? hr.hr_id : '');
                } else {
                    $('#rev_no_documents').show();
                    $('#rev_bulk_verify_btn').hide();
                }

                // Contract section
                var contractHtml     = hr ? (hr.contract || '') : '';
                var contractUpdated  = hr ? hr.contract_updated_at : null;
                var contractAccepted = hr ? hr.contract_accepted_at : null;

                if (contractAccepted) {
                    $('#rev_contract_pending_banner').hide();
                    $('#rev_contract_accepted_banner').show();
                    $('#rev_contract_accepted_at').text(contractAccepted);
                    $('#rev_contract_status_badge').text('Accepted').attr('class', 'badge badge-success');
                } else if (contractUpdated) {
                    $('#rev_contract_pending_banner').show();
                    $('#rev_contract_accepted_banner').hide();
                    $('#rev_contract_status_badge').text('Pending Acceptance').attr('class', 'badge badge-warning text-dark');
                } else {
                    $('#rev_contract_pending_banner').hide();
                    $('#rev_contract_accepted_banner').hide();
                    $('#rev_contract_status_badge').text('No Contract').attr('class', 'badge badge-secondary');
                }

                if (contractHtml) {
                    $('#rev_contract_preview').html(contractHtml).show();
                } else {
                    $('#rev_contract_preview').hide();
                }

                $('#rev_contract_save_msg').hide();
                initRevContractEditor(contractHtml);

                $('#reviewModalLoader').hide();
                $('#reviewModalContent').show();
            }).fail(function () {
                $('#reviewModalLoader').html('<p class="text-danger p-3">Failed to load employee data. Please try again.</p>');
            });
        });

        // Activate agent
        $(document).on('click', '#rev_activate_btn', function () {
            if (!_reviewUserId) return;
            $(this).prop('disabled', true).text('Saving...');
            $.post('/employee-review/agent-status', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                user_id: _reviewUserId, status: 1
            }, function (res) {
                if (res.success) {
                    $('#rev_agent_badge').text('Agent: Active').attr('class', 'badge badge-success');
                    $('#rev_agent_status_btns').html(
                        '<button class="btn btn-sm btn-danger btn-block" id="rev_deactivate_btn"><i class="fe fe-user-x mr-1"></i>Deactivate Agent</button>'
                        + '<p class="text-success small mt-1 mb-0"><i class="fe fe-check-circle mr-1"></i>Currently Active — can log in</p>'
                    );
                    var msg = 'Agent activated successfully.';
                    if (res.email_note) msg += ' | ' + res.email_note;
                    $('#rev_agent_status_msg').text(msg).show();
                }
            });
        });

        // Deactivate agent
        $(document).on('click', '#rev_deactivate_btn', function () {
            if (!_reviewUserId || !confirm('Deactivate this user from the agent portal?')) return;
            $(this).prop('disabled', true).text('Saving...');
            $.post('/employee-review/agent-status', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                user_id: _reviewUserId, status: 0
            }, function (res) {
                if (res.success) {
                    $('#rev_agent_badge').text('Agent: Inactive').attr('class', 'badge badge-danger');
                    $('#rev_agent_status_btns').html(
                        '<button class="btn btn-sm btn-success btn-block" id="rev_activate_btn"><i class="fe fe-user-check mr-1"></i>Activate Agent</button>'
                        + '<p class="text-danger small mt-1 mb-0"><i class="fe fe-x-circle mr-1"></i>Currently Inactive — cannot log in</p>'
                    );
                    $('#rev_agent_status_msg').text('Agent deactivated successfully.').show();
                }
            });
        });

        // Save HR status
        $(document).on('click', '#rev_save_hr_status', function () {
            if (!_reviewUserId) return;
            var hrStatusId = $('#rev_hr_status_select').val();
            if (!hrStatusId) return;
            var self = this;
            $(self).prop('disabled', true).text('Saving...');
            $.ajax({
                url: '/employee-review/hr-status',
                type: 'POST',
                data: { _token: $('meta[name="csrf-token"]').attr('content'), user_id: _reviewUserId, hr_status_id: hrStatusId },
                success: function (res) {
                    $(self).prop('disabled', false).text('Save HR Status');
                    if (res.success) {
                        $('#rev_hr_badge').text('HR: ' + res.hr_status_name);
                        $('#rev_hr_status_msg').text('HR status updated to "' + res.hr_status_name + '".')
                            .removeClass('text-danger').addClass('text-success').show();
                    } else {
                        $('#rev_hr_status_msg').text(res.error || 'Update failed.')
                            .removeClass('text-success').addClass('text-danger').show();
                    }
                },
                error: function (xhr) {
                    $(self).prop('disabled', false).text('Save HR Status');
                    var msg = 'Update failed.';
                    if (xhr.responseJSON && (xhr.responseJSON.error || xhr.responseJSON.message)) {
                        msg = xhr.responseJSON.error || xhr.responseJSON.message;
                    }
                    $('#rev_hr_status_msg').text(msg).removeClass('text-success').addClass('text-danger').show();
                }
            });
        });

        // Document verify toggle
        $(document).on('change', '.rev-doc-verify-toggle', function () {
            var docId  = $(this).data('doc-id');
            var status = this.checked ? 1 : 0;
            var $label = $(this).siblings('.rev-doc-verify-label');
            var $card  = $('#revDocCard' + docId + ' .border');
            var self   = this;
            $.ajax({
                url: '/employee-review/verify-document',
                type: 'POST',
                data: { _token: $('meta[name="csrf-token"]').attr('content'), doc_id: docId, status: status },
                success: function (res) {
                    if (res.success) {
                        $label.text(status === 1 ? 'Verified' : 'Pending')
                              .css('color', status === 1 ? '#28a745' : '#ffc107');
                        $card.css('border-color', (status === 1 ? '#28a745' : '#ffc107') + '!important');
                    } else {
                        self.checked = !self.checked;
                        alert('Failed to update document status.');
                    }
                },
                error: function () { self.checked = !self.checked; alert('Request failed.'); }
            });
        });

        // Bulk approve all documents
        $(document).on('click', '#rev_bulk_verify_btn', function () {
            if (!confirm('Mark ALL documents as Verified?')) return;
            var hrEmpId = $(this).data('hr-employee-id');
            if (!hrEmpId) { alert('No HR employee found.'); return; }
            var self = this;
            $(self).prop('disabled', true).text('Approving...');
            $.ajax({
                url: '/employee-review/bulk-verify-documents',
                type: 'POST',
                data: { _token: $('meta[name="csrf-token"]').attr('content'), hr_employee_id: hrEmpId },
                success: function (res) {
                    $(self).prop('disabled', false).text('✔ Approve All');
                    if (res.success) {
                        // Update all toggles and labels to verified
                        $('.rev-doc-verify-toggle').each(function() {
                            this.checked = true;
                            $(this).siblings('.rev-doc-verify-label').text('Verified').css('color','#28a745');
                        });
                        $('#rev_documents_row .border').css('border-color','#28a745');
                        alert('All documents verified.');
                    } else {
                        alert(res.message || 'Bulk verify failed.');
                    }
                },
                error: function () { $(self).prop('disabled', false).text('✔ Approve All'); alert('Request failed.'); }
            });
        });

        // ── Contract editor (Quill) ───────────────────────────────────────────
        function initRevContractEditor(contractHtml) {
            if ($('#rev_contract_quill').find('.ql-editor').length > 0 && window._revQuill) {
                // Already initialised — just update content
                window._revQuill.root.innerHTML = contractHtml || '';
                return;
            }
            function buildQuill() {
                // Clear any leftover Quill markup before reinitialising
                $('#rev_contract_quill').empty();
                window._revQuill = new Quill('#rev_contract_quill', {
                    theme: 'snow',
                    placeholder: 'Write the employee contract here...'
                });
                window._revQuill.root.innerHTML = contractHtml || '';
            }
            if (typeof Quill !== 'undefined') {
                buildQuill();
            } else {
                if (!$('#quill-css').length) {
                    $('<link id="quill-css" rel="stylesheet" href="https://cdn.quilljs.com/1.3.7/quill.snow.css">').appendTo('head');
                }
                $.getScript('https://cdn.quilljs.com/1.3.7/quill.min.js', function () {
                    buildQuill();
                });
            }
        }

        // Save Contract
        $(document).on('click', '#rev_save_contract_btn', function () {
            if (!_reviewUserId) return;
            if (!window._revQuill) { alert('Editor not ready yet.'); return; }
            var contractHtml = window._revQuill.root.innerHTML;
            var self = this;
            $(self).prop('disabled', true).html('<i class="fe fe-loader mr-1"></i>Saving...');
            $.ajax({
                url: '/employee-review/save-contract',
                type: 'POST',
                data: { _token: $('meta[name="csrf-token"]').attr('content'), user_id: _reviewUserId, contract: contractHtml },
                success: function (res) {
                    $(self).prop('disabled', false).html('<i class="fe fe-save mr-1"></i>Save Contract');
                    if (res.success) {
                        $('#rev_contract_preview').html(contractHtml).show();
                        $('#rev_contract_pending_banner').show();
                        $('#rev_contract_accepted_banner').hide();
                        $('#rev_contract_status_badge').text('Pending Acceptance').attr('class', 'badge badge-warning text-dark');
                        $('#rev_contract_save_msg').text(res.message || 'Contract saved. Employee has been notified.')
                            .removeClass('text-danger').addClass('text-success').show();
                    } else {
                        $('#rev_contract_save_msg').text('Failed to save contract.')
                            .removeClass('text-success').addClass('text-danger').show();
                    }
                },
                error: function (xhr) {
                    $(self).prop('disabled', false).html('<i class="fe fe-save mr-1"></i>Save Contract');
                    var msg = 'Failed to save contract.';
                    if (xhr.responseJSON && xhr.responseJSON.error) msg = xhr.responseJSON.error;
                    $('#rev_contract_save_msg').text(msg).removeClass('text-success').addClass('text-danger').show();
                }
            });
        });

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
