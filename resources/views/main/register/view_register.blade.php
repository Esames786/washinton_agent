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
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="employeeReviewModalLabel"><i class="fe fe-user mr-2"></i>Employee Review</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <!-- Warning Banner -->
                    <div class="alert alert-warning rounded-0 mb-0 border-0 border-bottom">
                        <strong><i class="fe fe-alert-triangle mr-1"></i>Before changing status, review the employee details below.</strong>
                        <ul class="mb-0 mt-1 small">
                            <li>Setting <strong>Agent Portal Active</strong> will allow the user to log in to the agent portal.</li>
                            <li>If the employee's <strong>HR Status is "Document Verification"</strong> or pending, the user will be redirected to the HR portal to upload documents before they can use the agent portal.</li>
                            <li>Verify all documents and HR status before activating.</li>
                        </ul>
                    </div>

                    <!-- Loading Spinner -->
                    <div id="reviewModalLoader" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 text-muted">Loading employee details...</p>
                    </div>

                    <!-- Content (hidden until loaded) -->
                    <div id="reviewModalContent" style="display:none;" class="p-3">

                        <!-- Profile Header -->
                        <div class="d-flex align-items-center gap-3 mb-3 p-3 bg-light rounded">
                            <img id="rev_profile_img" src="" alt="Profile"
                                 class="rounded-circle border border-primary"
                                 style="width:80px;height:80px;object-fit:cover;">
                            <div>
                                <h5 class="mb-0 fw-bold" id="rev_full_name">—</h5>
                                <div class="text-muted small" id="rev_designation_dept">—</div>
                                <div class="small mt-1">
                                    <span id="rev_agent_badge" class="badge mr-1"></span>
                                    <span id="rev_hr_badge" class="badge"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <!-- Left: Profile Info -->
                            <div class="col-md-6">
                                <div class="card border shadow-sm h-100">
                                    <div class="card-header bg-light fw-bold small py-2">Profile Info</div>
                                    <div class="card-body p-2">
                                        <table class="table table-sm table-borderless small mb-0">
                                            <tbody>
                                            <tr><td class="fw-bold text-muted" style="width:45%">Employee Code</td><td id="rev_employee_code">—</td></tr>
                                            <tr><td class="fw-bold text-muted">Email</td><td id="rev_email">—</td></tr>
                                            <tr><td class="fw-bold text-muted">Phone</td><td id="rev_phone">—</td></tr>
                                            <tr><td class="fw-bold text-muted">CNIC</td><td id="rev_cnic">—</td></tr>
                                            <tr><td class="fw-bold text-muted">Father Name</td><td id="rev_father_name">—</td></tr>
                                            <tr><td class="fw-bold text-muted">DOB</td><td id="rev_dob">—</td></tr>
                                            <tr><td class="fw-bold text-muted">Gender</td><td id="rev_gender">—</td></tr>
                                            <tr><td class="fw-bold text-muted">Marital Status</td><td id="rev_marital_status">—</td></tr>
                                            <tr><td class="fw-bold text-muted">Joining Date</td><td id="rev_joining_date">—</td></tr>
                                            <tr><td class="fw-bold text-muted">Basic Salary</td><td id="rev_basic_salary">—</td></tr>
                                            <tr><td class="fw-bold text-muted">Shift</td><td id="rev_shift">—</td></tr>
                                            <tr><td class="fw-bold text-muted">Address</td><td id="rev_address">—</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Status Controls -->
                            <div class="col-md-6">
                                <div class="card border shadow-sm mb-3">
                                    <div class="card-header bg-light fw-bold small py-2">Agent Portal Status</div>
                                    <div class="card-body p-3">
                                        <p class="small text-muted mb-2">Controls whether this user can log in to the agent portal.</p>
                                        <div id="rev_agent_status_btns" class="d-flex gap-2"></div>
                                        <div id="rev_agent_status_msg" class="mt-2 small text-success" style="display:none;"></div>
                                    </div>
                                </div>
                                <div class="card border shadow-sm">
                                    <div class="card-header bg-light fw-bold small py-2">HR Status</div>
                                    <div class="card-body p-3">
                                        <p class="small text-muted mb-2">The employee's current status in the HR portal.</p>
                                        <select id="rev_hr_status_select" class="form-control form-control-sm mb-2"></select>
                                        <button id="rev_save_hr_status" class="btn btn-sm btn-primary">Save HR Status</button>
                                        <div id="rev_hr_status_msg" class="mt-2 small text-success" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Documents -->
                            <div class="col-12">
                                <div class="card border shadow-sm">
                                    <div class="card-header bg-light fw-bold small py-2">Documents</div>
                                    <div class="card-body p-3">
                                        <div id="rev_documents_row" class="row g-2"></div>
                                        <p id="rev_no_documents" class="text-muted small mb-0" style="display:none;">No documents uploaded.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
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

        $(document).on('click', '.review-employee-btn', function (e) {
            e.preventDefault();
            _reviewUserId = $(this).data('user-id');
            $('#reviewModalLoader').show();
            $('#reviewModalContent').hide();
            $('#rev_agent_status_msg').hide();
            $('#rev_hr_status_msg').hide();
            $('#employeeReviewModal').modal('show');

            $.getJSON('/employee-review/data/' + _reviewUserId, function (data) {
                var agent = data.agent || {};
                var hr    = data.hr_employee || null;
                var docs  = data.documents || [];
                var statuses = data.hr_statuses || [];

                // Profile header
                var profileSrc = hr && hr.profile_path
                    ? '/' + hr.profile_path
                    : '/assets/images/default_images/profile_image.png';
                $('#rev_profile_img').attr('src', profileSrc);
                $('#rev_full_name').text(hr ? (hr.full_name || agent.name) : agent.name);
                $('#rev_designation_dept').text(
                    [hr ? hr.designation_name : null, hr ? hr.department_name : null]
                        .filter(Boolean).join(' | ') || '—'
                );

                // Status badges
                var agentStatusText = agent.status == 1 ? 'Agent: Active' : 'Agent: Inactive';
                var agentBadgeClass = agent.status == 1 ? 'badge-success' : 'badge-danger';
                $('#rev_agent_badge').text(agentStatusText).attr('class', 'badge ' + agentBadgeClass);

                var hrStatusText  = hr ? (hr.hr_status_name || 'Not Linked') : 'Not Linked';
                $('#rev_hr_badge').text('HR: ' + hrStatusText).attr('class', 'badge badge-info');

                // Profile fields
                $('#rev_employee_code').text(hr ? (hr.employee_code || '—') : '—');
                $('#rev_email').text(hr ? (hr.email || agent.email || '—') : agent.email);
                $('#rev_phone').text(hr ? (hr.phone || agent.phone || '—') : agent.phone);
                $('#rev_cnic').text(hr ? (hr.cnic || '—') : '—');
                $('#rev_father_name').text(hr ? (hr.father_name || '—') : '—');
                $('#rev_dob').text(hr ? (hr.dob || '—') : '—');
                $('#rev_gender').text(hr ? (hr.gender ? hr.gender.charAt(0).toUpperCase() + hr.gender.slice(1) : '—') : '—');
                $('#rev_marital_status').text(hr ? (hr.marital_status || '—') : '—');
                $('#rev_joining_date').text(hr ? (hr.joining_date || '—') : '—');
                $('#rev_basic_salary').text(hr ? (hr.basic_salary ? parseInt(hr.basic_salary).toLocaleString() : '—') : '—');
                $('#rev_shift').text(hr ? (hr.shift_name || '—') : '—');
                $('#rev_address').text(hr ? [hr.address, hr.city, hr.state, hr.country].filter(Boolean).join(', ') : '—');

                // Agent status buttons
                var btnHtml = '';
                if (agent.status == 1) {
                    btnHtml = '<button class="btn btn-sm btn-danger" id="rev_deactivate_btn">Deactivate Agent</button>'
                            + '<span class="text-success small ml-2"><i class="fe fe-check-circle mr-1"></i>Currently Active</span>';
                } else {
                    btnHtml = '<button class="btn btn-sm btn-success" id="rev_activate_btn">Activate Agent</button>'
                            + '<span class="text-danger small ml-2"><i class="fe fe-x-circle mr-1"></i>Currently Inactive</span>';
                }
                $('#rev_agent_status_btns').html(btnHtml);

                // HR status dropdown
                var selectHtml = '<option value="">-- No HR Record --</option>';
                if (hr) {
                    statuses.forEach(function (s) {
                        var sel = s.id == hr.employee_status_id ? ' selected' : '';
                        selectHtml += '<option value="' + s.id + '"' + sel + '>' + s.name + '</option>';
                    });
                }
                $('#rev_hr_status_select').html(selectHtml).prop('disabled', !hr);

                // Documents
                $('#rev_documents_row').empty();
                if (docs.length > 0) {
                    docs.forEach(function (doc) {
                        var ext = doc.file_path ? doc.file_path.split('.').pop().toLowerCase() : '';
                        var thumb = '';
                        if (['jpg','jpeg','png','gif','bmp','webp'].indexOf(ext) !== -1) {
                            thumb = '<img src="/' + doc.file_path + '" class="img-fluid rounded mb-1" style="width:100px;height:80px;object-fit:cover;">';
                        } else if (ext === 'pdf') {
                            thumb = '<i class="fe fe-file-text text-danger" style="font-size:48px;"></i>';
                        } else {
                            thumb = '<i class="fe fe-paperclip text-primary" style="font-size:48px;"></i>';
                        }
                        var statusBadge = doc.status == 1
                            ? '<span class="badge badge-success d-block mb-1">Verified</span>'
                            : '<span class="badge badge-warning d-block mb-1">Pending</span>';
                        var docHtml = '<div class="col-sm-6 col-md-4 col-lg-3 text-center">'
                            + '<div class="border rounded p-2 h-100">'
                            + thumb
                            + '<p class="small fw-bold mb-1 text-truncate" style="max-width:120px;margin:0 auto;">' + (doc.doc_type || doc.file_name) + '</p>'
                            + statusBadge
                            + '<a href="/' + doc.file_path + '" target="_blank" class="btn btn-xs btn-outline-primary">View</a>'
                            + '</div></div>';
                        $('#rev_documents_row').append(docHtml);
                    });
                    $('#rev_no_documents').hide();
                } else {
                    $('#rev_no_documents').show();
                }

                $('#reviewModalLoader').hide();
                $('#reviewModalContent').show();
            }).fail(function () {
                $('#reviewModalLoader').html('<p class="text-danger">Failed to load employee data.</p>');
            });
        });

        // Activate agent
        $(document).on('click', '#rev_activate_btn', function () {
            if (!_reviewUserId) return;
            $(this).prop('disabled', true).text('Saving...');
            $.post('/employee-review/agent-status', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                user_id: _reviewUserId,
                status: 1
            }, function (res) {
                if (res.success) {
                    $('#rev_agent_badge').text('Agent: Active').attr('class', 'badge badge-success');
                    $('#rev_agent_status_btns').html(
                        '<button class="btn btn-sm btn-danger" id="rev_deactivate_btn">Deactivate Agent</button>'
                        + '<span class="text-success small ml-2"><i class="fe fe-check-circle mr-1"></i>Currently Active</span>'
                    );
                    $('#rev_agent_status_msg').text('Agent activated successfully.').show();
                }
            });
        });

        // Deactivate agent
        $(document).on('click', '#rev_deactivate_btn', function () {
            if (!_reviewUserId) return;
            if (!confirm('Deactivate this user from the agent portal?')) return;
            $(this).prop('disabled', true).text('Saving...');
            $.post('/employee-review/agent-status', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                user_id: _reviewUserId,
                status: 0
            }, function (res) {
                if (res.success) {
                    $('#rev_agent_badge').text('Agent: Inactive').attr('class', 'badge badge-danger');
                    $('#rev_agent_status_btns').html(
                        '<button class="btn btn-sm btn-success" id="rev_activate_btn">Activate Agent</button>'
                        + '<span class="text-danger small ml-2"><i class="fe fe-x-circle mr-1"></i>Currently Inactive</span>'
                    );
                    $('#rev_agent_status_msg').text('Agent deactivated successfully.').show();
                }
            });
        });

        // Save HR status
        $('#rev_save_hr_status').on('click', function () {
            if (!_reviewUserId) return;
            var hrStatusId = $('#rev_hr_status_select').val();
            if (!hrStatusId) return;
            $(this).prop('disabled', true).text('Saving...');
            var self = this;
            $.post('/employee-review/hr-status', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                user_id: _reviewUserId,
                hr_status_id: hrStatusId
            }, function (res) {
                $(self).prop('disabled', false).text('Save HR Status');
                if (res.success) {
                    $('#rev_hr_badge').text('HR: ' + res.hr_status_name);
                    $('#rev_hr_status_msg').text('HR status updated to "' + res.hr_status_name + '".').show();
                } else {
                    $('#rev_hr_status_msg').text(res.error || 'Update failed.').removeClass('text-success').addClass('text-danger').show();
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
