@extends('layouts.innerpages')
@section('template_title')
    Profile
@endsection
@section('content')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .col-sm-3 .card {
            transition: all .2s;
            cursor: pointer;
        }

        .col-sm-3 .card:hover {
            box-shadow: 0 0px 30px 0 rgb(35 43 54 / 15%);
            scale: 1.02;
        }

        .col-sm-3 .card .card-header {
            font-weight: 700;
        }

        .col-sm-3 .card .card-header span {
            font-size: 11px;
            color: #fff;
        }

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

        .bg-yellow {
            background-color: #c3c300 !important;
        }

        .bg-orange {
            background-color: #F49917 !important;
        }

        .bg-pink {
            background: #E91E63 !important;
        }

        .bg-amber {
            background: #FF6F00 !important;
        }

        .bg-teal {
            background: #004D40 !important;
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

        .col {
            border-right: 1px solid darkgrey;
        }

        .col:last-child {
            border-right: none;
        }
    </style>
    <!-- Row -->
    <div class="row">
        <div class="col-12">
            @include('partials.mainsite_pages.return_function')
            <?php
            $checkpanel = check_panel();
            
            if ($checkpanel == 1) {
                $phoneaccess = explode(',', Auth::user()->emp_access_phone);
            } elseif ($checkpanel == 3) {
                $phoneaccess = explode(',', Auth::user()->emp_access_test);
            } else {
                $phoneaccess = explode(',', Auth::user()->emp_access_web);
            }
            ?>
            @if (session('flash_message'))
                <div class="alert alert-success">
                    {{ session('flash_message') }}
                </div>
            @endif
            <!--div-->
            <div class="page-header">
                <div class="text-secondary text-center text-uppercase w-100">
                    <h1 class="my-4"><b>Profile</b></h1>
                </div>
            </div>
            <div class="card mt-5">
                <div class="card-header d-block">
                    <div class="row">
                        {{-- <div class="col-sm-4 my-auto">
                            <label class="form-label">Daterange 
                            <!--<button type="button" class="btn btn-info btn-sm" onclick="$('#date_range').val('')" style="padding: 3.2px 10px;">Clear</button>-->
                            </label>
                            <div class='input-group date' id='datetimepicker1'>
                                <input type='text' name="date_range"  id="date_range" class="form-control" />
                                <span class="input-group-addon" style="
                                        border: 1px solid #ddd;
                                        border-left-color: transparent;
                                        border-radius: 0;
                                        position: relative;
                                        left: -1px;
                                        display: flex;
                                        align-items: center;
                                    ">
                                   <span class="glyphicon glyphicon-calendar"></span>
                               </span>
                            </div>
                        </div> --}}
                        <div class="col-sm-3 my-auto">
                            <label for="monthFilter">Month</label>
                            <select id="monthFilter" class="form-control" name="monthFilter">
                                <option value="">Select Month</option>
                                @foreach ($months as $month)
                                    <option value="{{ $month }}">{{ $month }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if (in_array('86', $phoneaccess))
                            <div class="col-sm-3 my-auto">
                                <label class="form-label" for="user">Users</label>
                                <select class="form-control" id="user" name="user">
                                    <option value="" selected>All</option>
                                    @foreach ($users as $key => $val)
                                        <option value="{{ $val->id }}">{{ get_user_name($val->id) }}
                                            ({{ get_user($val->role, 'name') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="col-sm-2 mt-auto mb-1">
                            <button class="btn btn-primary" id="submit">Search</button>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="pstatus" value="" />
                <div class="card-body" id="completeData">
                    <?php
                    $phoneaccess = [];
                    if (isset($user->id)) {
                        $phoneaccess = explode(',', $user->emp_access_profile);
                    } else {
                        $phoneaccess = explode(',', Auth::user()->emp_access_profile);
                    }
                    
                    function reporting($status, $from, $too, $user_id, $role)
                    {
                        $report = \App\report::whereHas('order', function ($q) use ($user_id, $status, $role) {
                            if ($role == 'Order Taker' || $role == 'CSR' || $role == 'Seller Agent') {
                                if ($status == '0') {
                                    $q->where('order_taker_id', '=', $user_id);
                                } else {
                                    $q->where('u_id', '=', $user_id);
                                }
                            } elseif ($role == 'Manager') {
                                if ($status == '0') {
                                    $q->where('order_taker_id', '=', $user_id)->orWhere('manager_id', '=', $user_id);
                                } else {
                                    $q->where('u_id', '=', $user_id);
                                }
                            } elseif ($role == 'Dispatcher') {
                                $q->where('dispatcher_id', '=', $user_id);
                            }
                        })
                            ->where('pstatus', '=', $status)
                            ->whereBetween('created_at', [$from, $too])
                            ->count();
                    
                        return $report;
                    }
                    ?>

                    <div class="row">
                        @if (isset($user->id))
                            <div class="col-sm-9">
                                <div class="card mt-5" style="height:160px;">
                                    <div class="card-header bg-light ">
                                        <h3 class="my-auto">
                                            {{ $user->name . ' ' . $user->last_name }}
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-title">Sudo Name: <b>{{ $user->slug ?? 'N/A' }}</b></p>
                                        <p class="card-title">Role: <b>{{ get_user($user->role, 'name') }}</b></p>
                                        {{-- <!--<p class="card-title">Phone: <b>{{ $user->phone ?? 'N/A' }}</b></p>-->
                                        <!--<p class="card-text">Address: <b>{{ $user->address ?? 'N/A' }}</b></p>--> --}}
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-sm-3">
                            <div class="card mt-5 overflow-hidden dash1-card border-0" style="height:160px;">
                                <div class="card-header">
                                    <h3 class="my-auto">
                                        Commission <span class="ratio bg-success"><i class="fa fa-usd"></i></span>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <h2 class="mb-1 number-font">{{ $profit }}</h2>
                                </div>
                                <div id="spark1"></div>
                            </div>
                        </div>
                    </div>
                    @if (isset($user->id))
                        <div class="row">
                            @if (get_user($user->role, 'name') == 'Manager' ||
                                    get_user($user->role, 'name') == 'Order Taker' ||
                                    get_user($user->role, 'name') == 'CSR' ||
                                    get_user($user->role, 'name') == 'Seller Agent')
                                <div class="col-sm-4">
                                    <div class="card mt-5">
                                        <div class="card-header bg-light  justify-content-center">
                                            Total New Order Report
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col" id="dailyPhn">
                                                    <p class="text-center dailyPhn" style="color:#868ba1;cursor: pointer">
                                                        Today</p>
                                                    <p class="text-muted text-center">
                                                        {{-- {{ reporting('0', \Carbon\Carbon::now()->format('Y-m-d 00:00:00'), \Carbon\Carbon::now()->format('Y-m-d 23:59:59'), $user->id, get_user($user->role, 'name')) }} --}}
                                                    </p>
                                                </div>
                                                <div class="col" id="weeklyPhn">
                                                    <p class="text-center weeklyPhn" style="color:#868ba1;cursor: pointer">
                                                        This Week</p>
                                                    <p class="text-muted text-center">
                                                        {{-- {{ reporting('0', \Carbon\Carbon::now()->subDays(7)->format('Y-m-d 00:00:00'), \Carbon\Carbon::now()->format('Y-m-d 23:59:59'), $user->id, get_user($user->role, 'name')) }} --}}
                                                    </p>
                                                </div>
                                                <div class="col" id="monthlyPhn">
                                                    <p class="text-center monthlyPhn" style="color:#868ba1;cursor: pointer">
                                                        This Month</p>
                                                    <p class="text-muted text-center">
                                                        {{-- {{ reporting('0', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00'), \Carbon\Carbon::now()->format('Y-m-d 23:59:59'), $user->id, get_user($user->role, 'name')) }} --}}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="card mt-5">
                                        <div class="card-header bg-light  justify-content-center">
                                            Total Complete Order Report
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col" id="dailyCom">
                                                    <p class="text-center dailyCom" style="color:#868ba1;cursor: pointer">
                                                        Today</p>
                                                    <p class="text-muted text-center">
                                                        {{-- {{ reporting('13', \Carbon\Carbon::now()->format('Y-m-d 00:00:00'), \Carbon\Carbon::now()->format('Y-m-d 23:59:59'), $user->id, get_user($user->role, 'name')) }} --}}
                                                    </p>
                                                </div>
                                                <div class="col" id="weeklyCom">
                                                    <p class="text-center weeklyCom" style="color:#868ba1;cursor: pointer">
                                                        This Week</p>
                                                    <p class="text-muted text-center">
                                                        {{-- {{ reporting('13', \Carbon\Carbon::now()->subDays(7)->format('Y-m-d 00:00:00'), \Carbon\Carbon::now()->format('Y-m-d 23:59:59'), $user->id, get_user($user->role, 'name')) }} --}}
                                                    </p>
                                                </div>
                                                <div class="col" id="monthlyCom">
                                                    <p class="text-center monthlyCom" style="color:#868ba1;cursor: pointer">
                                                        This Month</p>
                                                    <p class="text-muted text-center">
                                                        {{-- {{ reporting('13', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00'), \Carbon\Carbon::now()->format('Y-m-d 23:59:59'), $user->id, get_user($user->role, 'name')) }} --}}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="card mt-5">
                                        <div class="card-header bg-light  justify-content-center">
                                            Total Cancel Order Report
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col" id="dailyCanel">
                                                    <p class="text-center dailyCanel"
                                                        style="color:#868ba1;cursor: pointer">Today</p>
                                                    <p class="text-muted text-center">
                                                        {{-- {{ reporting('14', \Carbon\Carbon::now()->format('Y-m-d 00:00:00'), \Carbon\Carbon::now()->format('Y-m-d 23:59:59'), $user->id, get_user($user->role, 'name')) }} --}}
                                                    </p>
                                                </div>
                                                <div class="col" id="weeklyCanel">
                                                    <p class="text-center weeklyCanel"
                                                        style="color:#868ba1;cursor: pointer">This Week</p>
                                                    <p class="text-muted text-center">
                                                        {{-- {{ reporting('14', \Carbon\Carbon::now()->subDays(7)->format('Y-m-d 00:00:00'), \Carbon\Carbon::now()->format('Y-m-d 23:59:59'), $user->id, get_user($user->role, 'name')) }} --}}
                                                    </p>
                                                </div>
                                                <div class="col" id="monthlyCanel">
                                                    <p class="text-center monthlyCanel"
                                                        style="color:#868ba1;cursor: pointer">This Month</p>
                                                    <p class="text-muted text-center">
                                                        {{-- {{ reporting('14', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00'), \Carbon\Carbon::now()->format('Y-m-d 23:59:59'), $user->id, get_user($user->role, 'name')) }} --}}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif(get_user($user->role, 'name') == 'Dispatcher')
                                <div class="col-sm-4">
                                    <div class="card mt-5">
                                        <div class="card-header bg-light  justify-content-center">
                                            Total Schedule Order Report
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col" id="dailyPhn">
                                                    <p class="text-center dailyPhn" style="color:#868ba1;cursor: pointer">
                                                        Today</p>
                                                    <p class="text-muted text-center">
                                                        {{-- {{ reporting('10', \Carbon\Carbon::now()->format('Y-m-d 00:00:00'), \Carbon\Carbon::now()->format('Y-m-d 23:59:59'), $user->id, get_user($user->role, 'name')) }} --}}
                                                    </p>
                                                </div>
                                                <div class="col" id="weeklyPhn">
                                                    <p class="text-center weeklyPhn"
                                                        style="color:#868ba1;cursor: pointer">This Week</p>
                                                    <p class="text-muted text-center">
                                                        {{-- {{ reporting('10', \Carbon\Carbon::now()->subDays(7)->format('Y-m-d 00:00:00'), \Carbon\Carbon::now()->format('Y-m-d 23:59:59'), $user->id, get_user($user->role, 'name')) }} --}}
                                                    </p>
                                                </div>
                                                <div class="col" id="monthlyPhn">
                                                    <p class="text-center monthlyPhn"
                                                        style="color:#868ba1;cursor: pointer">This Month</p>
                                                    <p class="text-muted text-center">
                                                        {{-- {{ reporting('10', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00'), \Carbon\Carbon::now()->format('Y-m-d 23:59:59'), $user->id, get_user($user->role, 'name')) }} --}}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="card mt-5">
                                        <div class="card-header bg-light  justify-content-center">
                                            Total Pickup Order Report
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col" id="dailyCom">
                                                    <p class="text-center dailyCom" style="color:#868ba1;cursor: pointer">
                                                        Today</p>
                                                    <p class="text-muted text-center">
                                                        {{-- {{ reporting('11', \Carbon\Carbon::now()->format('Y-m-d 00:00:00'), \Carbon\Carbon::now()->format('Y-m-d 23:59:59'), $user->id, get_user($user->role, 'name')) }} --}}
                                                    </p>
                                                </div>
                                                <div class="col" id="weeklyCom">
                                                    <p class="text-center weeklyCom"
                                                        style="color:#868ba1;cursor: pointer">This Week</p>
                                                    <p class="text-muted text-center">
                                                        {{-- {{ reporting('11', \Carbon\Carbon::now()->subDays(7)->format('Y-m-d 00:00:00'), \Carbon\Carbon::now()->format('Y-m-d 23:59:59'), $user->id, get_user($user->role, 'name')) }} --}}
                                                    </p>
                                                </div>
                                                <div class="col" id="monthlyCom">
                                                    <p class="text-center monthlyCom"
                                                        style="color:#868ba1;cursor: pointer">This Month</p>
                                                    <p class="text-muted text-center">
                                                        {{-- {{ reporting('11', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00'), \Carbon\Carbon::now()->format('Y-m-d 23:59:59'), $user->id, get_user($user->role, 'name')) }} --}}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="card mt-5">
                                        <div class="card-header bg-light  justify-content-center">
                                            Total Delivered Order Report
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col" id="dailyCanel">
                                                    <p class="text-center dailyCanel"
                                                        style="color:#868ba1;cursor: pointer">Today</p>
                                                    <p class="text-muted text-center">
                                                        {{-- {{ reporting('12', \Carbon\Carbon::now()->format('Y-m-d 00:00:00'), \Carbon\Carbon::now()->format('Y-m-d 23:59:59'), $user->id, get_user($user->role, 'name')) }} --}}
                                                    </p>
                                                </div>
                                                <div class="col" id="weeklyCanel">
                                                    <p class="text-center weeklyCanel"
                                                        style="color:#868ba1;cursor: pointer">This Week</p>
                                                    <p class="text-muted text-center">
                                                        {{-- {{ reporting('12', \Carbon\Carbon::now()->subDays(7)->format('Y-m-d 00:00:00'), \Carbon\Carbon::now()->format('Y-m-d 23:59:59'), $user->id, get_user($user->role, 'name')) }} --}}
                                                    </p>
                                                </div>
                                                <div class="col" id="monthlyCanel">
                                                    <p class="text-center monthlyCanel"
                                                        style="color:#868ba1;cursor: pointer">This Month</p>
                                                    <p class="text-muted text-center">
                                                        {{-- {{ reporting('12', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00'), \Carbon\Carbon::now()->format('Y-m-d 23:59:59'), $user->id, get_user($user->role, 'name')) }} --}}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="row" id="allData">
                        @if (in_array('0', $phoneaccess))
                            <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
                                <a href="javascript:void(0)" class="showQuotes" data-value="0">
                                    <div class="card overflow-hidden dash1-card border-0">
                                        <div class="card-body">
                                            <p class=" mb-1 "> New</p>
                                            <h2 class="mb-1 number-font">{{ $new }}</h2>
                                            <span class="ratio bg-orange"><i class="fa fa-car"></i></span>
                                        </div>
                                        <div id="spark1"></div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (in_array('1', $phoneaccess))
                            <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
                                <a href="javascript:void(0)" class="showQuotes" data-value="1">
                                    <div class="card overflow-hidden dash1-card border-0">
                                        <div class="card-body">
                                            <p class=" mb-1 "> Interested</p>
                                            <h2 class="mb-1 number-font">{{ $int }}</h2>
                                            <span class="ratio bg-warning"><i class="fa fa-thumbs-up"></i></span>
                                        </div>
                                        <div id="spark1"></div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (in_array('2', $phoneaccess))
                            <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
                                <a href="javascript:void(0)" class="showQuotes" data-value="2">
                                    <div class="card overflow-hidden dash1-card border-0">
                                        <div class="card-body">
                                            <p class=" mb-1 "> Follow More</p>
                                            <h2 class="mb-1 number-font">{{ $fm }}</h2>
                                            <span class="ratio bg-primary"><i class="fa fa-tasks"></i></span>
                                        </div>
                                        <div id="spark1"></div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (in_array('3', $phoneaccess))
                            <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
                                <a href="javascript:void(0)" class="showQuotes" data-value="3">
                                    <div class="card overflow-hidden dash1-card border-0">
                                        <div class="card-body">
                                            <p class=" mb-1 "> Asking Low</p>
                                            <h2 class="mb-1 number-font">{{ $al }}</h2>
                                            <span class="ratio bg-pink"><i class="fa fa-level-down"></i></span>
                                        </div>
                                        <div id="spark1"></div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (in_array('4', $phoneaccess))
                            <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
                                <a href="javascript:void(0)" class="showQuotes" data-value="4">
                                    <div class="card overflow-hidden dash1-card border-0">
                                        <div class="card-body">
                                            <p class=" mb-1 "> Not Interested</p>
                                            <h2 class="mb-1 number-font">{{ $not_int }}</h2>
                                            <span class="ratio bg-success"><i class="fa fa-thumbs-down"></i></span>
                                        </div>
                                        <div id="spark1"></div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (in_array('5', $phoneaccess))
                            <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
                                <a href="javascript:void(0)" class="showQuotes" data-value="5">
                                    <div class="card overflow-hidden dash1-card border-0">
                                        <div class="card-body">
                                            <p class=" mb-1 "> No Response</p>
                                            <h2 class="mb-1 number-font">{{ $nr }}</h2>
                                            <span class="ratio bg-dark"><i class="fa fa-phone-square"></i></span>
                                        </div>
                                        <div id="spark1"></div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (in_array('6', $phoneaccess))
                            <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
                                <a href="javascript:void(0)" class="showQuotes" data-value="6">
                                    <div class="card overflow-hidden dash1-card border-0">
                                        <div class="card-body">
                                            <p class=" mb-1 "> Time Quote</p>
                                            <h2 class="mb-1 number-font">{{ $tq }}</h2>
                                            <span class="ratio bg-amber"><i class="fa fa-clock-o"></i></span>
                                        </div>
                                        <div id="spark1"></div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (in_array('7', $phoneaccess))
                            <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
                                <a href="javascript:void(0)" class="showQuotes" data-value="7">
                                    <div class="card overflow-hidden dash1-card border-0">
                                        <div class="card-body">
                                            <p class=" mb-1 "> Payment Missing</p>
                                            <h2 class="mb-1 number-font">{{ $pm }}</h2>
                                            <span class="ratio bg-primary"><i class="fa fa-money"></i></span>
                                        </div>
                                        <div id="spark1"></div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (in_array('18', $phoneaccess))
                            <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
                                <a href="javascript:void(0)" class="showQuotes" data-value="18">
                                    <div class="card overflow-hidden dash1-card border-0">
                                        <div class="card-body">
                                            <p class=" mb-1 "> OnApproval</p>
                                            <h2 class="mb-1 number-font">{{ $oa }}</h2>
                                            <span class="ratio bg-primary"><i class="fa fa-quora"></i></span>
                                        </div>
                                        <div id="spark1"></div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (in_array('8', $phoneaccess))
                            <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
                                <a href="javascript:void(0)" class="showQuotes" data-value="8">
                                    <div class="card overflow-hidden dash1-card border-0">
                                        <div class="card-body">
                                            <p class=" mb-1 "> Booked</p>
                                            <h2 class="mb-1 number-font">{{ $book }}</h2>
                                            <span class="ratio bg-warning"><i class="fa fa-book"></i></span>
                                        </div>
                                        <div id="spark1"></div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (in_array('9', $phoneaccess))
                            <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
                                <a href="javascript:void(0)" class="showQuotes" data-value="9">
                                    <div class="card overflow-hidden dash1-card border-0">
                                        <div class="card-body">
                                            <p class=" mb-1 "> Listed</p>
                                            <h2 class="mb-1 number-font">{{ $list }}</h2>
                                            <span class="ratio bg-pink"><i class="fa fa-list"></i></span>
                                        </div>
                                        <div id="spark1"></div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (in_array('10', $phoneaccess))
                            <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
                                <a href="javascript:void(0)" class="showQuotes" data-value="10">
                                    <div class="card overflow-hidden dash1-card border-0">
                                        <div class="card-body">
                                            <p class=" mb-1 "> Schedule</p>
                                            <h2 class="mb-1 number-font">{{ $dis }}</h2>
                                            <span class="ratio bg-success"><i class="fa fa-truck"></i></span>
                                        </div>
                                        <div id="spark1"></div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (in_array('30', $phoneaccess))
                            <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
                                <a href="javascript:void(0)" class="showQuotes" data-value="30">
                                    <div class="card overflow-hidden dash1-card border-0">
                                        <div class="card-body">
                                            <p class=" mb-1 "> Pickup Approval</p>
                                            <h2 class="mb-1 number-font">{{ $pick_app }}</h2>
                                            <span class="ratio bg-dark"><i class="fa fa-map"></i></span>
                                        </div>
                                        <div id="spark1"></div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (in_array('11', $phoneaccess))
                            <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
                                <a href="javascript:void(0)" class="showQuotes" data-value="11">
                                    <div class="card overflow-hidden dash1-card border-0">
                                        <div class="card-body">
                                            <p class=" mb-1 "> Pickup</p>
                                            <h2 class="mb-1 number-font">{{ $pick }}</h2>
                                            <span class="ratio bg-primary"><i class="fa fa-level-up"></i></span>
                                        </div>
                                        <div id="spark1"></div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (in_array('31', $phoneaccess))
                            <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
                                <a href="javascript:void(0)" class="showQuotes" data-value="31">
                                    <div class="card overflow-hidden dash1-card border-0">
                                        <div class="card-body">
                                            <p class=" mb-1 "> Delivery Approval</p>
                                            <h2 class="mb-1 number-font">{{ $del_app }}</h2>
                                            <span class="ratio bg-amber"><i class="fa fa-check"></i></span>
                                        </div>
                                        <div id="spark1"></div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (in_array('32', $phoneaccess))
                            <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
                                <a href="javascript:void(0)" class="showQuotes" data-value="32">
                                    <div class="card overflow-hidden dash1-card border-0">
                                        <div class="card-body">
                                            <p class=" mb-1 "> Schedule For Delivery</p>
                                            <h2 class="mb-1 number-font">{{ $sfd }}</h2>
                                            <span class="ratio bg-success"><i class="fa fa-level-up"></i></span>
                                        </div>
                                        <div id="spark1"></div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (in_array('12', $phoneaccess))
                            <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
                                <a href="javascript:void(0)" class="showQuotes" data-value="12">
                                    <div class="card overflow-hidden dash1-card border-0">
                                        <div class="card-body">
                                            <p class=" mb-1 "> Delivered</p>
                                            <h2 class="mb-1 number-font">{{ $del }}</h2>
                                            <span class="ratio bg-pink"><i class="fe fe-box"></i></span>
                                        </div>
                                        <div id="spark1"></div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (in_array('13', $phoneaccess))
                            <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
                                <a href="javascript:void(0)" class="showQuotes" data-value="13">
                                    <div class="card overflow-hidden dash1-card border-0">
                                        <div class="card-body">
                                            <p class=" mb-1 "> Completed</p>
                                            <h2 class="mb-1 number-font">{{ $com }}</h2>
                                            <span class="ratio bg-success"><i class="fa fa-check-circle"></i></span>
                                        </div>
                                        <div id="spark1"></div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (in_array('14', $phoneaccess))
                            <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
                                <a href="javascript:void(0)" class="showQuotes" data-value="14">
                                    <div class="card overflow-hidden dash1-card border-0">
                                        <div class="card-body">
                                            <p class=" mb-1 "> Cancel</p>
                                            <h2 class="mb-1 number-font">{{ $can }}</h2>
                                            <span class="ratio bg-danger"><i class="fa fa-close"></i></span>
                                        </div>
                                        <div id="spark1"></div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (in_array('19', $phoneaccess))
                            <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
                                <a href="javascript:void(0)" class="showQuotes" data-value="19">
                                    <div class="card overflow-hidden dash1-card border-0">
                                        <div class="card-body">
                                            <p class=" mb-1 "> OnApprovalCancel</p>
                                            <h2 class="mb-1 number-font">{{ $opcan }}</h2>
                                            <span class="ratio bg-info"><i class="fa fa-window-close"></i></span>
                                        </div>
                                        <div id="spark1"></div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (in_array('20', $phoneaccess))
                            <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
                                <a href="javascript:void(0)" class="showQuotes" data-value="20">
                                    <div class="card overflow-hidden dash1-card border-0">
                                        <div class="card-body">
                                            <p class=" mb-1 "> Review Order</p>
                                            <h2 class="mb-1 number-font">{{ $rating }}</h2>
                                            <span class="ratio bg-warning"><i class="fa fa-star"></i></span>
                                        </div>
                                        <div id="spark1"></div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (in_array('21', $phoneaccess))
                            <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
                                <a href="javascript:void(0)" class="showQuotes" data-value="21">
                                    <div class="card overflow-hidden dash1-card border-0">
                                        <div class="card-body">
                                            <p class=" mb-1 "> Cancel Remark By (Admin/HOD/TeamLead)</p>
                                            <h2 class="mb-1 number-font">{{ $cancellation }}</h2>
                                            <span class="ratio bg-danger"><i class="fa fa-close"></i></span>
                                        </div>
                                        <div id="spark1"></div>
                                    </div>
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="row" id="tableData">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extraScript')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        function searchData(page) {
            var date_range = $("#date_range").val();
            var monthFilter = $("#monthFilter").val();
            var user = $("#user").children('option:selected').val();
            $("#pstatus").val('');
            var pstatus = '';

            $.ajax({
                url: "{{ url('/profile') }}?page=" + page,
                type: "GET",
                data: {
                    date_range: date_range,
                    pstatus: pstatus,
                    user: user,
                    monthFilter: monthFilter
                },
                beforeSend: function() {
                    $('#completeData').html("");
                    $('#completeData').append(`<div class="lds-hourglass" id='ldss'></div>`);
                },
                success: function(res) {
                    $("#completeData").html("");
                    $("#completeData").html(res);
                }
            });
        }

        function searchData2(page) {
            var date_range = $("#date_range").val();
            var monthFilter = $("#monthFilter").val();
            var pstatus = $("#pstatus").val();
            var user = $("#user").children('option:selected').val();

            $.ajax({
                url: "{{ url('/profile/show') }}?page=" + page,
                type: "GET",
                data: {
                    date_range: date_range,
                    pstatus: pstatus,
                    user: user,
                    monthFilter: monthFilter
                },
                beforeSend: function() {
                    $('#tableData').html("");
                    $('#tableData').append(`<div class="lds-hourglass" id='ldss'></div>`);
                },
                success: function(res) {
                    $("#tableData").html("");
                    $("#tableData").html(res);
                }
            });
        }

        $(document).on('click', '.showPickup', function() {
            $("#pstatus").val(11);
            searchData2(1);
        })

        $(document).on('click', '.showDeliver', function() {
            $("#pstatus").val(12);
            searchData2(1);
        })

        $("#submit").click(function() {
            searchData(1);
        })

        $(function() {
            var date = new Date();
            $('#date_range').daterangepicker({
                "showDropdowns": true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                },
                "alwaysShowCalendars": true,
                // "startDate": new Date(date.getFullYear(), date.getMonth(), 1),
                // "endDate": new Date(date.getFullYear(), date.getMonth() + 1, 0),
                "opens": "center",
                "drops": "auto"
            }, function(start, end, label) {
                // console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
                $('#date_range').val("{{ $from->format('m/d/Y') }} - {{ $to->format('m/d/Y') }}");
            });
            $('#date_range').val("{{ $from->format('m/d/Y') }} - {{ $to->format('m/d/Y') }}");
        });

        $("body").delegate(".cancelBtn", "click", function() {
            $('#date_range').val('');
        });


        $(document).on('click', '.pagination a', function(event) {

            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            searchData2(page);
        });

        // $(document).on('click','.showQuotes',function(){
        //     $(".showQuotes").removeClass('bg-dark');
        //     $(".showQuotes").removeClass('text-light');
        //     $(this).addClass('bg-dark');
        //     $(this).addClass('text-light');
        //     $("#pstatus").val($(this).attr('data-value'));
        //     searchData2(1);
        // })

        // $(document).on('click','#searchValues',function(){
        //     searchData2(1);
        // })
    </script>
@endsection
