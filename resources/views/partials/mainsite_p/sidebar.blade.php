<style>
    .app-sidebar__user {
        padding-bottom: 12px !important;
        padding-top: 0px !important;
    }

    .sidebar-navs {
        padding: 10px 0px 0px 0px;
        margin-top: -10px;
    }

    .fa-check:before {
        content: "\f00c";
        color: black;
    }

    .sidebar-navs a {

        border: 2px solid #8cc73e;
    }

    .img_border {
        border-radius: 50%;
        width: 50px;
        height: auto !important;
        font-size: 35px;
    }

    #style-14::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.9);
        border-radius: 10px;
        background-color: #CCCCCC;
    }

    #style-14::-webkit-scrollbar {
        width: 12px;
        background-color: #F5F5F5;
    }

    #style-14::-webkit-scrollbar-thumb {
        border-radius: 10px;
        background-color: black;
        background-image: -webkit-linear-gradient(90deg,
                lightslategrey 10%,
                transparent,
                transparent,
                lightslategrey 10%)
    }

    .scrollbar {
        margin-left: -1%;
        float: left;
        height: 120%;
        width: 100%;
        background: #ffffff;
        overflow-y: scroll;
        margin-bottom: 25px;
    }

    span.badge.badge-warning.side-badge {
        margin-right: -20px;
    }

    span.side-menu__label {
        text-transform: uppercase;
    }
    .mobile-sidebar-toggle {
        position: fixed; /* instead of absolute */
        top: 50%;
        left: 0; /* fixed on the edge of the screen */
        transform: translateY(-50%);
        background-color: #333;
        color: #fff;
        padding: 8px;
        border: none;
        border-radius: 0 50% 50% 0;
        z-index: 10000;
        display: none;
        box-shadow: 0 0 5px rgba(0,0,0,0.3);
    }

    @media (max-width: 768px) {
        .mobile-sidebar-toggle {
            display: block;
        }
    }

</style>
<button class="mobile-sidebar-toggle" id="mobileSidebarToggle">
    ☰
</button>
<aside class="app-sidebar">
    <div class="app-sidebar__logo">
        <a class="header-brand" href="{{ url('dashboard') }}">
            <img src="{{ url('frontend/img/logo/hello_transport.svg') }}" class="header-brand-img desktop-lgo"
                alt="DayDispatch logo">
            <img src="{{ url('frontend/img/logo/hello_transport.svg') }}" class="header-brand-img dark-logo"
                alt="DayDispatch logo">
            <img src="{{ url('frontend/img/logo/hello_transport.svg') }}" class="header-brand-img mobile-logo"
                alt="DayDispatch logo" style="border: 1px solid deepskyblue; border-radius: 100px;">
            <img src="{{ url('frontend/img/logo/hello_transport.svg') }}" class="header-brand-img darkmobile-logo"
                alt="DayDispatch logo" style="border: 1px solid deepskyblue; border-radius: 100px;">
            <audio controls style="display: none;" id="noti">
                <source src="{{ url('audio/notification.mp3') }}" type="audio/mpeg">
            </audio>


        </a>
    </div>
    @php
        $check_panel = check_panel();

        if ($check_panel == 1) {
            $phoneaccess = explode(',', Auth::user()->emp_access_phone);
        } elseif ($check_panel == 2) {
            $phoneaccess = explode(',', Auth::user()->emp_access_web);
        } elseif ($check_panel == 3) {
            $phoneaccess = explode(',', Auth::user()->emp_access_test);
        } elseif ($check_panel == 4) {
            $phoneaccess = explode(',', Auth::user()->panel_type_4);
        } elseif ($check_panel == 5) {
            $phoneaccess = explode(',', Auth::user()->panel_type_5);
        } elseif ($check_panel == 6) {
            $phoneaccess = explode(',', Auth::user()->panel_type_6);
        } else {
            $phoneaccess = [];
        }
    @endphp
    @if (Auth::check())
        @if (Auth::user()->role != 5)
            <div class="app-sidebar__user">
                <div class="sidebar-navs">
                    <ul class="nav nav-pills-circle">

                        @if (in_array('20', $phoneaccess))
                            {{-- <li class="nav-item" data-placement="top" data-toggle="tooltip" title="Add Employee"> --}}
                            {{-- <a class="icon" href="{{url('add_employee')}}"> --}}
                            {{-- <i class="las la-user header-icons"></i> --}}
                            {{-- </a> --}}
                            {{-- </li> --}}
                            {{-- <li class="nav-item" data-placement="top" data-toggle="tooltip" title="View/Edit Employee"> --}}
                            {{-- <a class="icon" href="{{url('view_employee')}}"> --}}
                            {{-- <i class="las  la-edit header-icons"></i> --}}
                            {{-- </a> --}}
                            {{-- </li> --}}
                        @endif
                        {{-- <li class="nav-item" data-placement="top" data-toggle="tooltip" title="CHAT"> --}}
                        {{-- <a href="{{url('chats')}}" class="icon"> --}}
                        {{-- <i class="las la-inbox header-icons"></i> --}}
                        {{-- </a> --}}
                        {{-- </li> --}}
                    </ul>
                    <ul class="nav nav-pills-circle">
                        <li class="nav-item" data-placement="top" data-toggle="tooltip" title="Add Issues">
                            <a href="{{ url('issues_add') }}" class="icon">
                                <i class="las la-pen-alt header-icons"></i>
                            </a>
                        </li>

                        <li class="nav-item" data-placement="top" data-toggle="tooltip" title="My Issues">
                            <a href="{{ url('my_issues') }}" class="icon">
                                <i class="las la-users header-icons"></i>
                            </a>
                        </li>
                        @if (in_array('21', $phoneaccess))
                            <li class="nav-item" data-placement="top" data-toggle="tooltip" title="Admin Issues">
                                <a href="{{ url('admin_issues') }}" class="icon">
                                    <i class="las la-user-lock header-icons"></i>
                                </a>
                            </li>
                        @endif
                        <li class="nav-item" data-placement="top" data-toggle="tooltip" title="Issues Comments List">
                            <a href="{{ url('issue_comments_list') }}" class="icon">
                                <i class="las la-list header-icons"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>


            <div class="">
                <ul class="side_nav_style" id="style-14">
                    <li>
                        <a class="side-menu__item" href="{{ url('dashboard') }}">
                            <span class="js-search-result-thumbnail responsive-img img_border fa fa-dashboard"></span>
                            <span class="side-menu__label">DASHBOARD</span>
                        </a>
                    </li>
                    @if (in_array('74', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('/sheets_list') }}">
                                <span
                                    class="js-search-result-thumbnail responsive-img img_border fa fa-calendar"></span>
                                <span class="side-menu__label">Sheet List</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('80', $phoneaccess))
                        <!--<li>-->
                        <!--    <a class="side-menu__item" href="{{ url('/excelsheet/port') }}">-->
                        <!--        <span class="js-search-result-thumbnail responsive-img img_border fa fa-calendar"></span>-->
                        <!--        <span class="side-menu__label">Port Sheet</span>-->
                        <!--    </a>-->
                        <!--</li>-->
                    @endif
                    @if (Auth::user()->userRole->name == 'Admin' || in_array('147', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ route('view_query') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-question"></span>
                                <span class="side-menu__label">Shipa1 Query</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('0', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('new') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-quora"></span>
                                <span class="side-menu__label">NEW</span><span class="badge badge-warning side-badge"
                                    style="font-size: 15px"
                                    style="font-size: 15px">{{ get_total_new(0, $check_panel) }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('2', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('followup') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-tasks"></span>
                                <span class="side-menu__label">Follow Up</span><span
                                    class="badge badge-warning side-badge"
                                    style="font-size: 15px">{{ get_total_new(2, $check_panel) }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('1', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('interested') }}">
                                <span
                                    class="js-search-result-thumbnail responsive-img img_border fa fa-thumbs-up"></span>
                                <span class="side-menu__label">Interested</span><span
                                    class="badge badge-warning side-badge"
                                    style="font-size: 15px">{{ get_total_new(1, $check_panel) }}</span>
                            </a>
                        </li>
                    @endif

                    @if (in_array('3', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('asking_low') }}">
                                <span
                                    class="js-search-result-thumbnail responsive-img img_border fa fa-level-down"></span>
                                <span class="side-menu__label">ASKING LOW</span><span
                                    class="badge badge-warning side-badge"
                                    style="font-size: 15px">{{ get_total_new(3, $check_panel) }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('5', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('not_responding') }}">
                                <span
                                    class="js-search-result-thumbnail responsive-img img_border fa fa-phone-square"></span>
                                <span class="side-menu__label">NOT RESPONDING</span><span
                                    class="badge badge-warning side-badge"
                                    style="font-size: 15px">{{ get_total_new(5, $check_panel) }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('4', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('not_interested') }}">
                                <span
                                    class="js-search-result-thumbnail responsive-img img_border fa fa-thumbs-down"></span>
                                <span class="side-menu__label">NOT INTERESTED</span><span
                                    class="badge badge-warning side-badge"
                                    style="font-size: 15px">{{ get_total_new(4, $check_panel) }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('6', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('time_quote') }}">
                                <span
                                    class="js-search-result-thumbnail responsive-img img_border fa fa-clock-o"></span>
                                <span class="side-menu__label">TIME QUOTE</span><span
                                    class="badge badge-warning side-badge"
                                    style="font-size: 15px">{{ get_total_new(6, $check_panel) }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('7', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('payment_missing') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-money"></span>
                                <span class="side-menu__label">PAYMENT MISSING</span><span
                                    class="badge badge-warning side-badge"
                                    style="font-size: 15px">{{ get_total_new(7, $check_panel) }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('28', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('onapproval') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-quora"></span>
                                <span class="side-menu__label">ON APPROVAL</span><span
                                    class="badge badge-warning side-badge" style="font-size: 15px"
                                    style="font-size: 15px">{{ get_total_new(18, $check_panel) }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('8', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('booked') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-book"></span>
                                <span class="side-menu__label">BOOKED</span><span
                                    class="badge badge-warning side-badge"
                                    style="font-size: 15px">{{ get_total_new(8, $check_panel) }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('66', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('double_booking') }}">
                                <span
                                    class="js-search-result-thumbnail responsive-img img_border fa fa-address-book"></span>
                                <span class="side-menu__label">DOUBLE BOOKING</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('9', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('listed') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-list"></span>
                                <span class="side-menu__label">LISTED</span><span
                                    class="badge badge-warning side-badge"
                                    style="font-size: 15px">{{ get_total_new(9, $check_panel) }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('17', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="" data-toggle="modal"
                                data-target="#carrirermodal">
                                <span
                                    class="js-search-result-thumbnail responsive-img img_border fa fa-refresh"></span>
                                <span class="side-menu__label">CARRIER UPDATE</span>
                                <span class="badge badge-warning side-badge" style="font-size: 15px">
                                    {{ get_total_new(17, $check_panel) }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('10', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('dispatch') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-truck"></span>
                                <span class="side-menu__label">Schedule</span><span
                                    class="badge badge-warning side-badge"
                                    style="font-size: 15px">{{ get_total_new(10, $check_panel) }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('11', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('picked_up_approval') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-map"></span>
                                <span class="side-menu__label">PICKUP APPROVAL</span><span
                                    class="badge badge-warning side-badge"
                                    style="font-size: 15px">{{ get_total_pickup_approval(11, $check_panel) }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('11', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('picked_up') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-truck"></span>
                                <span class="side-menu__label">PICKED UP</span><span
                                    class="badge badge-warning side-badge"
                                    style="font-size: 15px">{{ get_total_new(11, $check_panel) }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('12', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('schedule_for_delivery') }}">
                                <span
                                    class="js-search-result-thumbnail responsive-img img_border fa fa-level-up"></span>
                                <span class="side-menu__label">SCHEDULE FOR DELIVERY</span><span
                                    class="badge badge-warning side-badge"
                                    style="font-size: 15px">{{ get_total_deliver_schedule(12, $check_panel) }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('12', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('deliver_approval') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-check"></span>
                                <span class="side-menu__label">DELIVER APPROVAL</span><span
                                    class="badge badge-warning side-badge"
                                    style="font-size: 15px">{{ get_total_deliver_approval(12, $check_panel) }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('12', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('delivered') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fe fe-box"></span>
                                <span class="side-menu__label">DELIVERED</span><span
                                    class="badge badge-warning side-badge"
                                    style="font-size: 15px">{{ get_total_new(12, $check_panel) }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('13', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('completed') }}">
                                <span
                                    class="js-search-result-thumbnail responsive-img img_border fa fa-check-circle"></span>
                                <span class="side-menu__label">COMPLETED</span><span
                                    class="badge badge-warning side-badge"
                                    style="font-size: 15px">{{ get_total_new(13, $check_panel) }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('14', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('cancel') }}">
                                <span
                                    class="js-search-result-thumbnail responsive-img img_border fa fa-window-close"></span>
                                <span class="side-menu__label">CANCEL</span><span
                                    class="badge badge-warning side-badge"
                                    style="font-size: 15px">{{ get_total_new(14, $check_panel) }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('29', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('onapproval_cancel') }}">
                                <span
                                    class="js-search-result-thumbnail responsive-img img_border fa fa-window-close"></span>
                                <span class="side-menu__label">ON APPROVAL Cancel</span><span
                                    class="badge badge-warning side-badge" style="font-size: 15px"
                                    style="font-size: 15px">{{ get_total_new(19, $check_panel) }}</span>
                            </a>
                        </li>
                    @endif

                    @if (in_array('15', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('deleted') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-trash"></span>
                                <span class="side-menu__label">DELETED</span><span
                                    class="badge badge-warning side-badge"
                                    style="font-size: 15px">{{ get_total_new(15, $check_panel) }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('16', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('owns_money') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-dollar"></span>
                                <span class="side-menu__label">OWES MONEY</span><span
                                    class="badge badge-warning side-badge"
                                    style="font-size: 15px">{{ get_total_new(16, $check_panel) }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('33', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('mile-price') }}">
                                <span
                                    class="js-search-result-thumbnail responsive-img img_border fa fa-area-chart"></span>
                                <span class="side-menu__label">Price Per Mile</span>
                                <span class="badge badge-warning side-badge"
                                    style="font-size: 15px">{{ get_total_new(33, $check_panel) }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('72', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('offer-price') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-dollar"></span>
                                <span class="side-menu__label">Offer Price</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('85', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('commission_range') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-dollar"></span>
                                <span class="side-menu__label">Commission Range</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('34', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('filtered-data') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-filter"></span>
                                <span class="side-menu__label">Filtered Data</span>
                                <span class="badge badge-warning side-badge"
                                    style="font-size: 15px">{{ get_total_new(34, $check_panel) }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('35', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('group') }}">
                                <span
                                    class="js-search-result-thumbnail responsive-img img_border fa fa-object-group"></span>
                                <span class="side-menu__label">Groups</span>
                                <span class="badge badge-warning side-badge"
                                    style="font-size: 15px">{{ get_total_new(35, $check_panel) }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('36', $phoneaccess))
                        <!--<li>-->
                        <!--    <a class="side-menu__item" href="{{ url('questions') }}">-->
                        <!--        <span class="js-search-result-thumbnail responsive-img img_border fa fa-question"></span>-->
                        <!--        <span class="side-menu__label">Questions</span>-->
                        <!--        <span-->
                        <!--                class="badge badge-warning side-badge"-->
                        <!--                style="font-size: 15px">{{ get_total_new(36, $check_panel) }}</span>-->
                        <!--    </a>-->
                        <!--</li>-->
                    @endif
                    @if (in_array('37', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('show-data') }}">
                                <span
                                    class="js-search-result-thumbnail responsive-img img_border fa fa-comments-o"></span>
                                <span class="side-menu__label">Show Data</span>
                                <span class="badge badge-warning side-badge"
                                    style="font-size: 15px">{{ get_total_new(37, $check_panel) }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('44', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('transfer-quotes') }}">
                                <span
                                    class="js-search-result-thumbnail responsive-img img_border fa fa-arrows-h"></span>
                                <span class="side-menu__label">Transfer Qoutes</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('75', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('port_price') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-dollar"></span>
                                <span class="side-menu__label">Port Price</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('46', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('revenue') }}">
                                <span
                                    class="js-search-result-thumbnail responsive-img img_border fa fa-smile-o"></span>
                                <span class="side-menu__label">Revenue</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('55', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('dispatch_report') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-book"></span>
                                <span class="side-menu__label">Dispatch Report</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('57', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('performance_report') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-book"></span>
                                <span class="side-menu__label">Performance Report</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('62', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('qa_report') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-book"></span>
                                <span class="side-menu__label">QA Report</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('87', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('break_time') }}">
                                <span
                                    class="js-search-result-thumbnail responsive-img img_border fa fa-chain-broken"></span>
                                <span class="side-menu__label">Break Time</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('88', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('freeze_user') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-lock"></span>
                                <span class="side-menu__label">Freeze User</span>
                            </a>
                        </li>
                    @endif

                    {{-- ── New Payment System ─────────────────────────────────── --}}
                    @if (Auth::user()->role == 1 || in_array('164', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ route('admin.payments.index') }}"
                               style="background: #2dd1ba8c">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-credit-card"></span>
                                <span class="side-menu__label">Admin Payments</span>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->role == 1 || in_array('165', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ route('agent.payments.index') }}"
                               style="background: #2dd1ba8c">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-dollar"></span>
                                <span class="side-menu__label">My Payments</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('47', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('coupons') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-tag"></span>
                                <span class="side-menu__label">Coupons</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('48', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('website-links') }}">
                                <span
                                    class="js-search-result-thumbnail responsive-img img_border fa fa-external-link"></span>
                                <span class="side-menu__label">Website Links</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('49', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('feedback') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-star"></span>
                                <span class="side-menu__label">Feedbacks</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('50', $phoneaccess))
                        <li>
                            <a class="side-menu__item"
                                href="@if (Auth::user()->userRole->name == 'Manager') {{ url('/managers-group/' . Auth::user()->id) }} @else {{ url('/manager') }} @endif">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-users"></span>
                                <span class="side-menu__label">Managers Group</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('51', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('last_activity') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-key"></span>
                                <span class="side-menu__label">Last Activity</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('52', $phoneaccess) || Auth::user()->userRole->name == 'Admin')
                        <li>
                            <a class="side-menu__item" href="{{ url('ip_address') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-server"></span>
                                <span class="side-menu__label">Login Ip Addresses</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('56', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('/user_rating') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-star"></span>
                                <span class="side-menu__label">Employee Rating</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('53', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('storage_order_list') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-truck"></span>
                                <span class="side-menu__label">Storage</span><span
                                    class="badge badge-warning side-badge"
                                    style="font-size: 15px">{{ get_total_storage($check_panel) }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('90', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('demand_order') }}">
                                <span
                                    class="js-search-result-thumbnail responsive-img img_border fa fa-shopping-cart"></span>
                                <span class="side-menu__label">Demand Order</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('91', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('sell_invoice') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-file"></span>
                                <span class="side-menu__label">Sell Invoice</span>
                            </a>
                        </li>
                    @endif

                    @if (in_array('111', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ url('port_tracking') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-file"></span>
                                <span class="side-menu__label">Port Tracking</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('112', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ route('messagechats.index') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-file"></span>
                                <span class="side-menu__label">Message chats</span>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->userRole->name == 'Admin' || in_array('128', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ route('view_employee_revenue') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-file"></span>
                                <span class="side-menu__label">Employee Revenue (OT)</span>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->userRole->name == 'Admin' || in_array('127', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ route('view_employee_revenue_deliveryBoy') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-file"></span>
                                <span class="side-menu__label">Employee Revenue (DB)</span>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->userRole->name == 'Admin' || in_array('129', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ route('view_employee_revenue_Dispatcher') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-file"></span>
                                <span class="side-menu__label">Employee Revenue (DIS)</span>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->userRole->name == 'Admin' || in_array('130', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ route('view_employee_revenue_PrivateOT') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-file"></span>
                                <span class="side-menu__label">Employee Revenue (Private OT)</span>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->userRole->name == 'Admin' || in_array('132', $phoneaccess))
                        <li>
                            {{-- <a class="side-menu__item" href="{{ route('agent.report') }}"> --}}
                            <a class="side-menu__item" href="{{ route('agentReportNew') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-file"></span>
                                <span class="side-menu__label">Daily Report</span>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->userRole->name == 'Admin' || Auth::user()->userRole->name == 'Manager')
                        <li>
                            <a class="side-menu__item" href="{{ route('flagUsers') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-file"></span>
                                <span class="side-menu__label">Flag Users</span>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->userRole->name == 'Admin' || in_array('133', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ route('customer.reviews') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-file"></span>
                                <span class="side-menu__label">Customer Reviews</span>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->userRole->name == 'Admin' || in_array('124', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ route('block_phone.index') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-file"></span>
                                <span class="side-menu__label">Block Phone List</span>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->userRole->name == 'Admin' || in_array('123', $phoneaccess))
                        <li>
                            <a class="side-menu__item" href="{{ route('requestPrice.index') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-file"></span>
                                <span class="side-menu__label">Request Price</span>
                            </a>
                        </li>
                    @endif

                    @if (Auth::user()->userRole->name == 'Admin' || in_array('106', $phoneaccess))
                        <li>
                            <a style="background: #2dd1ba8c" class="side-menu__item"
                                href="{{ route('customerNatureList') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-mobile"></span>
                                <span class="side-menu__label">Customer Nature List/Filter</span>
                                <span class="badge badge-warning side-badge" style="font-size: 15px"></span>
                            </a>
                        </li>
                    @endif

                    @if (in_array('31', $phoneaccess))
                        {{-- OLD Payment System hidden — replaced by new payment system (permissions 164 & 165) --}}
                        {{-- <li>
                            <a style="background: #2dd1ba8c" class="side-menu__item"
                                href="{{ url('payment_system2') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-money"></span>
                                <span class="side-menu__label">Payment System</span>
                            </a>
                        </li> --}}
                    @endif
                    @if (in_array('32', $phoneaccess))
                        <li>
                            <a style="background: #2dd1ba8c" class="side-menu__item" href="{{ url('reports') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-file"></span>
                                <span class="side-menu__label">User Report</span>
                                <span class="badge badge-warning side-badge" style="font-size: 15px"></span>
                            </a>
                        </li>
                        <li>
                            <a style="background: #2dd1ba8c" class="side-menu__item"
                                href="{{ url('/reports/get') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-file"></span>
                                <span class="side-menu__label">Employee Report</span>
                                <span class="badge badge-warning side-badge" style="font-size: 15px"></span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('30', $phoneaccess))
                        <li>
                            <a style="background: #2dd1ba8c" class="side-menu__item"
                                href="{{ url('approaching') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-mobile"></span>
                                <span class="side-menu__label">APPROACHING</span>
                                <span class="badge badge-warning side-badge" style="font-size: 15px"></span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('94', $phoneaccess))
                        <li>
                            <a style="background: #2dd1ba8c" class="side-menu__item"
                                href="{{ route('autos.approach') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-mobile"></span>
                                <span class="side-menu__label">Autos Approach</span>
                                <span class="badge badge-warning side-badge" style="font-size: 15px"></span>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->userRole->name == 'Admin' || in_array('137', $phoneaccess))
                        <li>
                            <a style="background: #2dd1ba8c" class="side-menu__item"
                               href="{{ url('autos_approach_new1') . '/' . base64_encode('Shipper') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-mobile"></span>
                                <span class="side-menu__label">DayDispatch | Shipper</span>
                                <span class="badge badge-warning side-badge" style="font-size: 15px"></span>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->userRole->name == 'Admin' || in_array('138', $phoneaccess))
                        <li>
                            <a style="background: #2dd1ba8c" class="side-menu__item"
                               href="{{ url('autos_approach_new1') . '/' . base64_encode('Carrier') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-mobile"></span>
                                <span class="side-menu__label">DayDispatch | Carrier </span>
                                <span class="badge badge-warning side-badge" style="font-size: 15px"></span>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->userRole->name == 'Admin' || in_array('139', $phoneaccess))
                        <li>
                            <a style="background: #2dd1ba8c" class="side-menu__item"
                               href="{{ url('autos_approach_new1') . '/' . base64_encode('Broker') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-mobile"></span>
                                <span class="side-menu__label">DayDispatch | Broker </span>
                                <span class="badge badge-warning side-badge" style="font-size: 15px"></span>
                            </a>
                        </li>

                    @endif
                    @if (Auth::user()->userRole->name == 'Admin' || in_array('140', $phoneaccess))
                        <li>
                            <a style="background: #2dd1ba8c" class="side-menu__item"
                               href="{{ route('autos.autos_approach_new_dealer') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-mobile"></span>
                                <span class="side-menu__label">Dealer Approaching</span>
                                <span class="badge badge-warning side-badge" style="font-size: 15px"></span>
                            </a>
                        </li>
                    @endif

                    @if (Auth::user()->userRole->name == 'Admin' || in_array('155', $phoneaccess))
                        <li>
                            <a style="background: #2dd1ba8c" class="side-menu__item"
                               href="{{ route('autos.autos_approach_new_carrier') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-mobile"></span>
                                <span class="side-menu__label">Carrier Approaching</span>
                                <span class="badge badge-warning side-badge" style="font-size: 15px"></span>
                            </a>
                        </li>
                    @endif
                    {{-- @if (in_array('95', $phoneaccess))
                        <li>
                            <a style="background: #2dd1ba8c" class="side-menu__item"
                                href="{{ route('field_labels.index') }}">
                                <span class="js-search-result-thumbnail responsive-img img_border fa fa-mobile"></span>
                                <span class="side-menu__label">General Settings</span>
                                <span class="badge badge-warning side-badge" style="font-size: 15px"></span>
                            </a>
                        </li>
                    @endif --}}

                    @if (in_array('17', $phoneaccess))
                        {{-- <li> --}}
                        {{-- <a class="side-menu__item" href="{{url('uploadvidioadd')}}"> --}}
                        {{-- <span class="js-search-result-thumbnail responsive-img img_border fa fa-youtube-play"></span> --}}
                        {{-- <span class="side-menu__label">UPLOAD VIDEOS</span><span --}}
                        {{-- class="badge badge-warning side-badge" --}}
                        {{-- style="font-size: 15px">{{get_total_new(17,$check_panel)}}</span> --}}
                        {{-- </a> --}}
                        {{-- </li> --}}
                    @endif
                    {{-- <li> --}}
                    {{-- <a class="side-menu__item" href="{{url('invoice_list')}}"> --}}
                    {{-- <span class="js-search-result-thumbnail responsive-img img_border fa fa-youtube-play"></span> --}}
                    {{-- <span class="side-menu__label">INVOICE</span><span --}}
                    {{-- class="badge badge-warning side-badge" --}}
                    {{-- style="font-size: 15px">{{get_total_invoice($check_panel)}}</span> --}}
                    {{-- </a> --}}
                    {{-- </li> --}}
                    {{-- <li> --}}
                    {{-- <a class="side-menu__item" href="{{url('carrier_list')}}"> --}}
                    {{-- <span class="js-search-result-thumbnail responsive-img img_border fa fa-youtube-play"></span> --}}
                    {{-- <span class="side-menu__label">Carriers</span><span --}}
                    {{-- class="badge badge-warning side-badge" --}}
                    {{-- style="font-size: 15px">{{get_total_carrier($check_panel)}}</span> --}}
                    {{-- </a> --}}
                    {{-- </li> --}}
                    {{-- <li> --}}

                    {{-- <a class="side-menu__item" href="{{url('customer_list')}}"> --}}
                    {{-- <span class="js-search-result-thumbnail responsive-img img_border fa fa-youtube-play"></span> --}}
                    {{-- <span class="side-menu__label">Customers</span><span --}}
                    {{-- class="badge badge-warning side-badge" --}}
                    {{-- style="font-size: 15px">{{get_total_customer($check_panel)}}</span> --}}
                    {{-- </a> --}}
                    {{-- </li> --}}
                </ul>
            </div>
        @endif
    @endif

</aside>


<!--aside closed--> <!-- App-Content -->
