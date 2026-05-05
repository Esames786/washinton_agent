{{--@include('partials.mainsite_pages.return_function')--}}

@php
$check_panel = check_panel();

if($check_panel == 1){
$phoneaccess=explode(',',Auth::user()->emp_access_phone);
}
elseif($check_panel == 3)
{
    $phoneaccess = explode(',',Auth::user()->emp_access_test);
}
else{
$phoneaccess=explode(',',Auth::user()->emp_access_web);
}
@endphp
<?php
if (!function_exists('get_user_name123')) {
    function get_user_name123($id)
    {
        $setting = App\general_setting::first();
        $query = \App\User::where('id', $id)->first();
        if (!empty($query)) {
            return $query->name;
        } else {
            return '';
        }


    }
}



?>
<style>
    .dropdown > .dropdown-menu {
        top: 200%;
        transition: 0.3s all ease-in-out;
    }

    .dropdown:hover > .dropdown-menu {
        display: block;
        top: 100%;
    }

    .dropdown > .dropdown-toggle:active {
        /*Without this, clicking will make it sticky*/
        pointer-events: none;
    }

       .manage_dropdown {
        min-height: auto;
        border-radius: 7px;
        border: 1px solid #009eda;
        font-size: inherit;
    }
    a.dropdown-item {
        border-bottom: 1px solid var(--secondary);
        padding: 5px 14px;
        margin: 0px 0px;

    }

    a.dropdown-item:last-child {
        border-bottom: 0px;
        padding: 5px 14px;
        margin: 0px 0px;

    }
    [class^="ti-"], [class*=" ti-"] {
        font-family: 'themify';
        speak: none;
        font-style: normal;
        font-weight: normal;
        font-variant: normal;
        text-transform: none;
        line-height: 3 !important;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

     .form-check-input {
        width: 34px; /* Adjust the width as necessary */
        height: 20px; /* Adjust the height as necessary */
    }

    .form-check-label {
        margin-left: 10px; /* Adjust space between checkbox and label */
    }

    .stickyNotes a {
        bottom: 290px;
        right: 70px !important;
        background: none;
        /* margin: auto; */
        position: fixed;
        /* bottom: 23%; */
        width: 35px;
        height: 35px;
        z-index: 999;
        text-decoration: none;
        color: #ffffff;
        border-radius: 5px;
    }

.stickyNotes .bpSticky {
        right: 20px;
        /* background-color: #cae01e; */
        background-color: #004992;
        color: #00d0ff;
        border: 1px solid black;
    }
</style>

<div class="app-header header">
    <div class="container-fluid">
        <div class="d-flex" style="align-items:center;">
            <a class="header-brand" href="/dashboard">
                <img src="{{ url('frontend/img/logo/hello_transport.png')}}" class="header-brand-img desktop-lgo"
                     alt="DayDispatch logo">
                <img src="{{ url('frontend/img/logo/hello_transport.png')}}" class="header-brand-img dark-logo"
                     alt="DayDispatch logo">
                <img src="{{ url('assets/images/brand/favicon.png')}}" class="header-brand-img mobile-logo"
                     alt="DayDispatch logo">
                <img src="{{ url('assets/images/brand/favicon1.png')}}" class="header-brand-img darkmobile-logo"
                     alt="DayDispatch logo">
            </a>


            <div class="app-sidebar__toggle" data-toggle="sidebar" style="display:none">
                <a class="open-toggle" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round"
                         class="feather feather-align-left header-icon mt-1">
                        <line x1="17" y1="10" x2="3" y2="10"></line>
                        <line x1="21" y1="6" x2="3" y2="6"></line>
                        <line x1="21" y1="14" x2="3" y2="14"></line>
                        <line x1="17" y1="18" x2="3" y2="18"></line>
                    </svg>
                </a>
            </div>
            {{-- @if(Auth::user()->role < 6 || Auth::user()->role > 7)
            <div class="m-0">
                <form class="form-inline m-0" action="{{ url('searchData') }}"
                      id="searchFormData" method="get">
                    <div class="search-element">
                        <input type="search" name="search" class="form-control header-search w-25" autocomplete="off"
                               placeholder="Search Order Id Here..."
                               onfocus="$(this).attr('autocomplete', 'off');"
                               aria-label="Search" tabindex="1">

                        <button class="btn btn-primary-color" type="submit"
                                style="height: 100%;border-left: 3px solid #6c757d;">
                            <span class="fa fa-search" style="color: black"></span>
                        </button>


                    </div>
                </form>

            </div><!-- SEARCH -->
            @endif --}}

            <div class="d-flex order-lg-2">
                @if(Auth::user()->role < 6 || Auth::user()->role > 7)
                <form class="form-inline m-0" action="{{ url('searchData') }}"
                      id="searchFormData" method="get">
                    <div class="search-element">
                        <input type="search" name="search" class="form-control w-100" autocomplete="off"
                               placeholder="Search Order Id Here..."
                               onfocus="$(this).attr('autocomplete', 'off');"
                               aria-label="Search" tabindex="1">

                        <button class="btn btn-primary-color" type="submit"
                                style="height: 100%;border-left: 3px solid #6c757d;">
                            <span class="fa fa-search" style="color: black"></span>
                        </button>


                    </div>
                </form>
            @endif
            @if(Auth::user()->role < 6 || Auth::user()->role > 7)
                <a href="#" data-toggle="search"
                   class="nav-link nav-link-lg d-md-none navsearch">
                    <svg class="header-icon search-icon" x="1008" y="1248" viewBox="0 0 24 24"
                         height="100%" width="100%" preserveAspectRatio="xMidYMid meet"
                         focusable="false">
                        <path d="M0 0h24v24H0V0z" fill="none"/>
                        <path
                                d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                    </svg>
                </a>


             <ul class="header_social m-0 ml-2">
                <!--<li class="nav-item" data-placement="top" data-toggle="tooltip" title="Global Search">-->
                <!--    <a class="icon"  href="{{url('searchData')}}" >-->
                <!--        <i class="fa fa-search header-icons" ></i>-->
                <!--    </a>-->
                <!--</li>-->

                @if (in_array("18", $phoneaccess))
                    <li class="nav-item" data-placement="top" data-toggle="tooltip" title="New Car Quote">
                        <a class="icon"   href="{{url('add_new')}}" target="_blank">
                            <i class="fa fa-phone header-icons" ></i>
                        </a>
                    </li>
                @endif
                @if (in_array("19", $phoneaccess))
                    <li class="nav-item" data-placement="top" data-toggle="tooltip" title="New Heavy Quote">
                        <a class="icon"   href="{{url('add_new_heavy')}}" target="_blank">
                            <i class="fa fa-truck header-icons" ></i>
                        </a>
                    </li>
                @endif
                 @if (in_array("92", $phoneaccess))
                     <li class="nav-item" data-placement="top" data-toggle="tooltip" title="New Freight Quote">
                         <a class="icon"   href="{{url('add_new_freight')}}" target="_blank">
                             <i class="fa fa-square-o header-icons" ></i>
                         </a>
                     </li>
                 @endif


                @if (in_array("20", $phoneaccess))
                    <li class="nav-item"
                       data-placement="top" data-toggle="tooltip" title="Add Employee">
                        <a class="icon"  href="{{url('add_employee')}}">
                            <i class="fa fa-users header-icons"></i>
                        </a>
                    </li>
                @endif

                @if (in_array("20", $phoneaccess))
                    <li class="nav-item" data-placement="top" data-toggle="tooltip" title="View Employee">
                        <a class="icon"   href="{{url('view_employee')}}">
                            <i class="fa fa-street-view header-icons" ></i>
                        </a>
                    </li>
                @endif
                @if (in_array("63", $phoneaccess))
                    <li class="nav-item" data-placement="top" data-toggle="tooltip" title="Roles">
                        <a class="icon" href="{{url('role')}}"
                          >
                            <i class="fa fa-universal-access header-icons"></i>
                        </a>
                    </li>
                @endif
                @if (in_array("43", $phoneaccess))
                    <li class="nav-item" data-placement="top" data-toggle="tooltip" title="Flag Employee">
                        <a class="icon"   href="{{url('flag_employee')}}">
                            <i class="fa fa-flag-o header-icons" ></i>
                        </a>
                    </li>
                @endif
                @if (in_array("22", $phoneaccess))
                    <!--<li class="nav-item"-->
                    <!--    style="margin-right: 15px;height: 40px;box-shadow: 0px 3px 16px #00000030;border-radius: 50px;margin-top: 2px;"-->
                    <!--    data-placement="top" data-toggle="tooltip" title="Old Shipa1 Move to New">-->

                    <!--</li>-->
                @endif
                </ul>
                <div class="header__btn">
                    <div class="dropdown">
                        <button class="btn  dropdown-toggle manage_dropdown" type="button" id="dropdownMenuButton"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Management
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style=" margin-left: -22px; height: 350px; overflow: scroll; ">
                            @if(Auth::user()->role==1)
                                <a class="dropdown-item" href="{{url('report_terminal')}}"><i
                                            class="fe fe-file mr-1" style="color: #000;"></i>
                                    <span>Terminal Report</span>
                                </a>
                                <a class="dropdown-item" href="{{url('attendance_report')}}"><i
                                            class="fe fe-clock mr-1 " style="color: #000;"></i>
                                    <span>Attendance Report</span>
                                </a>
                                <a class="dropdown-item" href="{{url('total_activity')}}"><i
                                            class="fe fe-briefcase mr-1" style="color: #000;"></i>
                                    <span>Total Activity</span>
                                </a>
                        <!--        <a class="dropdown-item" href="{{url('old_shipa1')}}">-->
                        <!--    <i class="fa fa-book header-icons"-->
                        <!--       style="color:#000;"></i>-->
                        <!--       <span>Old Ship A1</span>-->
                        <!--</a>-->
                            @endif
                            @if (in_array("23", $phoneaccess))
                                <a class="dropdown-item" href="{{url('invoice_list')}}"><i
                                            class="fe fe-dollar-sign mr-1 " style="color: #000;"></i>
                                    <span>Transportation Invoice</span>
                                </a>
                            @endif
                            @if (in_array("73", $phoneaccess))
                                <a class="dropdown-item" href="{{url('invoice_list_roro')}}"><i
                                            class="fe fe-dollar-sign mr-1 " style="color: #000;"></i>
                                    <span>Roro Invoice</span>
                                </a>
                            @endif
                            @if(in_array("53", $phoneaccess))
                                <a class="dropdown-item" href="{{url('storage_list')}}"><i
                                            class="fe fe-box mr-1 btn_animation" style="color: #000;"></i>
                                    <span>Storage</span>
                                </a>
                            @endif
                            @if (in_array("24", $phoneaccess))
                                <a class="dropdown-item" href="{{url('carrier_list')}}"><i
                                            class="fe fe-truck mr-1 " style="color: #000;"></i>
                                    <span> Carriers</span>
                                </a>

                                <a class="dropdown-item" href="{{url('carrier_list2')}}"><i
                                            class="fe fe-truck mr-1 " style="color: #000;"></i>
                                    <span> Carriers List</span>
                                </a>

                            @endif
                            <?php
                                $ud = \App\user_setting::where('user_id', '=', Auth::id())->first();
                            ?>
                            @if (in_array("38", $phoneaccess))
                                <a class="dropdown-item" href="{{url('customer_list')}}"><i
                                            class="fe fe-users mr-1 " style="color: #000;"></i>
                                    <span>Customers List</span>
                                </a>
                            @endif
                            @if(Auth::user()->role==1)
                                <a class="dropdown-item" href="{{url('credit_card_list')}}"><i
                                            class="fe fe-credit-card mr-1 " style="color: #000;"></i>
                                    <span>Credit Card</span>
                                </a>
                                <a class="dropdown-item" href="{{url('sales_report')}}"><i
                                            class="fe fe-folder mr-1 " style="color: #000;"></i>
                                    <span>Sales Report</span>
                                </a>
                                <a class="dropdown-item" href="{{url('general_setting_add')}}"><i
                                            class="fe fe-settings mr-1 " style="color: #000;"></i>
                                    <span>General Settings</span>
                                </a>
                                <a class="dropdown-item" href="{{url('user_commission')}}"><i
                                            class="fa fa-money mr-1 " style="color: #000;"></i>
                                    <span>User Commission</span>
                                </a>
                                <a class="dropdown-item" href="{{url('first_bonus')}}"><i
                                            class="fa fa-dollar mr-1 " style="color: #000;"></i>
                                    <span> First Bonus</span>
                                </a>
                                <a class="dropdown-item" href="{{url('second_bonus')}}"><i
                                            class="fa fa-dollar mr-1 " style="color: #000;"></i>
                                    <span> Second Bonus</span>
                                </a>
                                <a class="dropdown-item" href="{{url('web_price')}}"><i
                                                class="fa fa-dollar mr-1 " style="color: #000;"></i>
                                     <span> Web Price</span>
                                </a>
                            @endif
                            @if (in_array("25", $phoneaccess))
                                <a href="javascript:void(0)" id="view_mail" class="dropdown-item">
                                    <i class="fa fa-envelope mr-1" style="color: black"></i> <span> View E-mails</span>
                                </a>
                            @endif
                            @if (in_array("26", $phoneaccess))
                                <a class="dropdown-item" href="{{url('quote_listing')}}"><i
                                            class="fa fa-dollar mr-1 " style="color: #000;"></i>
                                    <span> Show Data</span>
                                </a>
                            @endif
                            @if (in_array("54", $phoneaccess))
                                <a class="dropdown-item" href="{{url('shipment_status')}}"><i
                                            class="fa fa-dollar mr-1 " style="color: #000;"></i>
                                    <span> Shipment Status</span>
                                </a>
                            @endif
                            @if (in_array("27", $phoneaccess))
                                <a class="dropdown-item" href="{{url('sheet_list')}}"><i
                                            class="fa fa-table " style="color: #000;"></i>
                                    <span> Sheets</span>
                                </a>
                            @endif
                            {{--
                            <a class="dropdown-item" href="{{url('manage_payments')}}"><i
                                        class="fa fa-folder mr-1 " style="color: #000;"></i>
                                <span> Manage Payments</span>
                            </a>
                            --}}
                            @if(Auth::user()->role==1)
                                <a class="dropdown-item" href="{{url('profit_listing')}}"><i
                                            class="fa fa-money " style="color: #000;"></i>
                                    <span>Manage Accounts</span>
                                </a>
                            @endif
                            @if (in_array("41", $phoneaccess))
                            <a class="dropdown-item" href="{{url('phone_digits')}}"><i
                                        class="fa fa-phone " style="color: #000;"></i>
                                <span>Phone Number Digits</span>
                            </a>
                            @endif
                            @if (in_array("94", $phoneaccess))
                                <a class="dropdown-item" href="{{url('add_guide_list')}}"><i
                                            class="fa fa-phone " style="color: #000;"></i>
                                    <span>Add Guide</span>
                                </a>
                            @endif
                            @if (in_array("100", $phoneaccess))
                            <a class="dropdown-item" href="{{route('field_labels.index')}}"><i
                                        class="fa fa-phone " style="color: #000;"></i>
                                <span>Field Labels</span>
                            </a>
                            @endif
                            @if (in_array("105", $phoneaccess))
                            <a class="dropdown-item" href="{{route('email-templates.index')}}"><i
                                        class="fa fa-phone " style="color: #000;"></i>
                                <span>Custom Email Templates</span>
                            </a>
                            @endif
                            @if (in_array("109", $phoneaccess))
                            <a class="dropdown-item" href="{{route('authorization.forms.index')}}"><i
                                        class="fa fa-phone " style="color: #000;"></i>
                                <span>Authorization Forms</span>
                            </a>
                            @endif
                            @if (in_array("152", $phoneaccess) || Auth::user()->role==1)
                                <a class="dropdown-item" href="{{route('view_template')}}"><i
                                        class="fa fa-phone " style="color: #000;"></i>
                                    <span>Templates</span>
                                </a>
                            @endif

                            @if (in_array("153", $phoneaccess) || Auth::user()->role==1 || Auth::user()->role==9)
                                <a class="dropdown-item" href="{{route('profile_card_data')}}"><i
                                        class="fa fa-phone " style="color: #000;"></i>
                                    <span>Profile Card</span>
                                </a>
                            @endif

                            @if (in_array("154", $phoneaccess) || Auth::user()->role==1 || Auth::user()->role==9)
                                <a class="dropdown-item" href="{{route('price_request_assign_dispatcher')}}"><i
                                        class="fa fa-car " style="color: #000;"></i>
                                    <span>Dispatcher Price Assign</span>
                                </a>
                            @endif


                        @if (in_array("159", $phoneaccess) || Auth::user()->role==1)
                            <a class="dropdown-item" href="{{ route('gateway.portal.hello') }}" target="_blank"><i
                                    class="fa fa-sliders mr-1" style="color: #000;"></i>
                                <span>Hello Gateway</span>
                            </a>
                        @endif
                        @if (in_array("160", $phoneaccess) || Auth::user()->role==1)
                            <a class="dropdown-item" href="{{ route('gateway.portal.autohaul') }}" target="_blank"><i
                                    class="fa fa-truck mr-1" style="color: #000;"></i>
                                <span>Hello AutoHaul Gateway</span>
                            </a>
                                @endif

                        </div>
                    </div>

                @endif

                    @php
                        //panel type access
                        $emp_panel_access = Auth::user()->emp_panel_access;
                        $emp_panel_access = explode(',', $emp_panel_access);
                    @endphp
                @if(Auth::user()->role < 6 || Auth::user()->role > 7)
                    <form name="penalform" action="{{url('penal_type')}}" id="panel_form" method="post" class="m-0">
                        @csrf
                        <select class="form-control h-70 panel_typepanel_type"
                                name="panel_type" id="panel_type">

                            <option selected="selected" value=""><?php echo get_panel_name(); ?></option>
                            <optgroup label="Select Panel Type">
                                @if (in_array('1', $emp_panel_access))
                                    <option value="1">Auction</option>
                                @endif
                                @if (in_array('2', $emp_panel_access))
                                    <option value="2">ProMax</option>
                                @endif
                                @if (in_array("110", $phoneaccess) && in_array('3', $emp_panel_access))
                                    <option value="3">Testing Quote</option>
                                @endif
                                @if (in_array('4', $emp_panel_access))
                                    <option value="4">Shipa1 Website</option>
                                @endif
                                @if (in_array('5', $emp_panel_access))
                                    <option value="5">Panel Type 5 Quote</option>
                                @endif
                                @if (in_array('6', $emp_panel_access))
                                    <option value="6">Panel Type 6 Quote</option>
                                @endif
                            </optgroup>
                        </select>
                    </form>
                    @endif
                    {{-- @if(in_array("134", $phoneaccess) && in_array("135", $phoneaccess)) --}}
                        <form name="callform" action="{{url('call_type')}}" id="call_form" method="post" class="m-0">
                            @csrf
                            <select class="form-control h-70 call_typecall_type"
                                    name="call_type" id="call_type">

                                <option selected="selected" value=""><?php echo get_call_name(); ?></option>
                                <optgroup label="Select Call Type">
                                    @if (in_array("135", $phoneaccess))
                                        <option value="135">Call Old Web</option>
                                    @endif
                                    @if (in_array("134", $phoneaccess))
                                        <option value="134">Call App</option>
                                    @endif
                                    <option value="158">Zoom Call</option>

                                </optgroup>
                            </select>
                        </form>
                    {{-- @endif --}}
                @if(Auth::user()->role==1)
               <div class="header_ri">
                    <div class="dropdown   header-fullscreen">
                        <a class="nav-link icon full-screen-link p-0" href="{{url('day_count')}}"
                           title="DAY COUNT">
                           {{-- fa-blink --}}
                            <i class="header-icon fa fa-clock-o" style="color: #ef4b4b">
                            </i>
                        </a>
                    </div>

                    <div class="dropdown   header-fullscreen">
                        <a class="nav-link icon full-screen-link p-0" href="{{url('click_to_count')}}"
                           title="Total Clicks">
                            <i class="header-icon fa fa-mouse-pointer">
                            </i>
                        </a>
                    </div>
               </div>
                @endif
                @if(Auth::user()->role < 6 || Auth::user()->role > 7)
                <div class="dropdown header-message">
                    <a class="nav-link icon" data-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" class="header-icon" width="24"
                             height="24" viewBox="0 0 24 24">
                            <path
                                    d="M20,2H4C2.897,2,2,2.897,2,4v12c0,1.103,0.897,2,2,2h3v3.767L13.277,18H20c1.103,0,2-0.897,2-2V4C22,2.897,21.103,2,20,2z M20,16h-7.277L9,18.233V16H4V4h16V16z"/>
                            <path d="M7 7H17V9H7zM7 11H14V13H7z"/>
                        </svg>
                        <?php
                        $getcount = DB::table('chats')
                                ->where('touserId', Auth::user()->id)
                                ->where('chat_view', 0)
                                ->whereDate('created_at', '>=', \Carbon\Carbon::today()->subDays(2))
                                ->orderby('created_at', 'desc')
                                ->limit(100)
                                ->count();

                        $id = Auth::user()->id;
                        $groupCount = \App\GroupChat::whereHas('group.users',function($q) use ($id){
                            $q->where('user_id',$id)->where('status',1);
                        })
                        ->whereDate('created_at', '>=', \Carbon\Carbon::today()->subDays(31))
                        ->where(function($q) use ($id){
                            $q->whereRaw('NOT FIND_IN_SET(?, chat_view_users_id)', [$id])
                            ->orWhere('chat_view_users_id',NULL);
                        })
                        ->orderby('created_at', 'desc')
                        ->limit(100)
                        ->count();

                        // $id = Auth::user()->id;
                        // $getchat = DB::table('chats')
                        //     ->where('touserId', Auth::user()->id)
                        //     ->where('chat_view', 0)
                        //     ->whereDate('created_at', '>=', \Carbon\Carbon::today()->subDays(2))
                        //     ->orderby('created_at', 'desc')
                        //     ->limit(100)
                        //     ->get();

                        // $getGroupChat = DB::table('group_chats')
                        //     ->where('user_id', Auth::user()->id)
                        //     ->whereDate('created_at', '>=', \Carbon\Carbon::today()->subDays(31))
                        //     ->orderby('created_at', )
                        //     ->limit(100)
                        //     ->get();
                        ?>
                        <span class="badge badge-success side-badge" style="width: 25px;height: 25px;justify-content: center;align-items: center;display: flex !important;right: -10px;top: -10px;" id="msg_count">{{$getcount + $groupCount > 99 ? '99+' : $getcount + $groupCount }}</span>
                        <div class="toast-container position-fixed bottom-0 end-0 p-3"></div>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow  animated"
                         style="position: absolute !important; width: 328px !important; left: -276px !important;">
                        <div class="dropdown-header">
                            <h6 class="mb-0">Messages</h6>
                        </div>
                        <div class="header-dropdown-list message-menu" id="message-menu">
                            <?php
                            $getchat = DB::table('chats')
                                    ->where('touserId', Auth::user()->id)
                                    ->where('chat_view', 0)
                                    ->whereDate('created_at', '>=', \Carbon\Carbon::today()->subDays(2))
                                    ->orderby('created_at', 'desc')
                                    ->limit(100)
                                    ->get();

                            $getGroupChat = \App\GroupChat::with(['user','group'])
                            ->whereHas('group.users',function($q) use ($id){
                                $q->where('user_id',$id)->where('status',1);
                            })
                            ->whereDate('created_at', '>=', \Carbon\Carbon::today()->subDays(31))
                            ->where(function($q) use ($id){
                                $q->whereRaw('NOT FIND_IN_SET(?, chat_view_users_id)', [$id])
                                ->orWhere('chat_view_users_id',NULL);
                            })
                            ->orderby('created_at', 'desc')
                            ->limit(100)
                            ->get();
                            ?>

                            @foreach($getchat as $chatrow)
                                <a class="dropdown-item border-bottom" href="{{url('/chats/user/'.$chatrow->fromuserId)}}">
                                    <div class="d-flex align-items-center">
                                        <div class="">
                                                        <span
                                                                class="avatar avatar-md brround align-self-center cover-image"
                                                                data-image-src="{{ url('assets/images/users/user.jpg')}}"></span>
                                        </div>
                                        <div class="d-flex">
                                            <div class="pl-3">
                                                <h6 class="mb-1">{{get_user_name($chatrow->fromuserId)}}:</h6>

                                                <p class="fs-13 mb-1">
                                                    {{$chatrow->description}}
                                                </p>

                                                <div class="small text-muted">
                                                    {{$chatrow->created_at}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                            @foreach($getGroupChat as $chatrow)
                                <a class="dropdown-item border-bottom" href="{{url('/chats/group/'.$chatrow->group_id)}}">
                                    <div class="d-flex align-items-center">
                                        <div class="">
                                            @if($chatrow->grouplogo)
                                            <span
                                            class="avatar avatar-md brround align-self-center cover-image"
                                            data-image-src="{{asset('storage/images/group/'.$chatrow->group->logo)}}"></span>
                                            @else
                                            <span
                                            class="avatar avatar-md brround align-self-center cover-image"
                                            data-image-src="{{asset('images/group-chat.png')}}"></span>
                                            @endif
                                        </div>
                                        <div class="d-flex">
                                            <div class="pl-3">
                                                <h6 class="mb-1">{{$chatrow->group->name}}:</h6>

                                                <p class="fs-13 mb-1">
                                                    {{isset($chatrow->user->slug) ? $chatrow->user->slug : (isset($chatrow->user->slug) ? $chatrow->user->name.' '.$chatrow->user->last_name : 'User')}}}: {{ \Illuminate\Support\Str::words($chatrow->message,3)}}
                                                </p>

                                                <div class="small text-muted">
                                                    {{$chatrow->created_at}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <div class=" text-center p-2 border-top">
                            <a href="/chats" class="">See All Messages</a>
                        </div>
                    </div>
                </div>
                <div class="dropdown header-notify">
                    <a class="nav-link icon" data-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" class="header-icon" width="24"
                             height="24" viewBox="0 0 24 24">
                            <path
                                    d="M19 13.586V10c0-3.217-2.185-5.927-5.145-6.742C13.562 2.52 12.846 2 12 2s-1.562.52-1.855 1.258C7.185 4.074 5 6.783 5 10v3.586l-1.707 1.707C3.105 15.48 3 15.734 3 16v2c0 .553.447 1 1 1h16c.553 0 1-.447 1-1v-2c0-.266-.105-.52-.293-.707L19 13.586zM19 17H5v-.586l1.707-1.707C6.895 14.52 7 14.266 7 14v-4c0-2.757 2.243-5 5-5s5 2.243 5 5v4c0 .266.105.52.293.707L19 16.414V17zM12 22c1.311 0 2.407-.834 2.818-2H9.182C9.593 21.166 10.689 22 12 22z"/>
                        </svg>
                        <span class="pulse "></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow  animated"
                         style="position:absolute;">
                        <div class="dropdown-header">
                            <h6 class="mb-0">Notifications</h6>
                        </div>
                        <div class="notify-menu" style="overflow: scroll !important; height: 250px">
                            <?php
                            $date = date('Y-m-d');
                            $data = \App\report::with('user')->where('created_at', 'like', "%$date%")->orderBy('created_at', 'desc')->get();


                            ?>
                            @foreach($data as $val2)
                                <a href="/all_notification" class="dropdown-item border-bottom d-flex pl-4">
                                    <div class="notifyimg bg-info-transparent text-info"><i
                                                class="ti-comment-alt"></i></div>
                                    <div>
                                        <div class="font-weight-normal1">

                                        <span
                                                class="text-info">{{ isset($val2->user->slug) ? $val2->user->slug : $val2->user->name.' '.$val2->user->last_name }}
                                           </span>
                                            change status to :

                                            {{get_pstatus($val2->pstatus)}} ORDER ID :
                                            {{$val2->orderId}}
                                        </div>
                                        <div class="small text-muted">{{\Carbon\Carbon::parse($val2->created_at)->format('M,Y d h:i:s A')}}</div>
                                    </div>
                                </a>
                                @endforeach
                        </div>
                        <div class=" text-center p-2 border-top">
                            <a href="/all_notification" class="">View All Notifications</a>
                        </div>
                    </div>
                </div>
                <div class="dropdown header-fullscreen header_ir">
                    {{-- <a href="" data-toggle="modal" data-target="#stickynotemodal" class=""
                        style="display: inline;">
                        <i class="fas fa-sticky-note p-1" style="font-size: xx-large; background-color: #f6da52; color: #ffffff;"></i>
                    </a> --}}
                    <a href="" data-toggle="modal" data-target="#stickynotemodal" style="display: inline;">
                        <i class="fa fa-sticky-note"
                            style="
                                font-size: 2rem;
                                background-color: #f6da52;
                                color: #ffffff;
                                border-radius: 6px;
                                padding: 15px 12px 10px 12px;
                                box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
                                display: inline-block;
                                position: relative;
                            ">
                        </i>
                    </a>
                </div>
                @endif
                <div class="dropdown profile-dropdown">
                    <a href="#" class="nav-link pr-0 leading-none" data-toggle="dropdown">
												<span class=" js-search-result-thumbnail responsive-img img_border fa fa-user"
                                                      style=" color: #6c757d; ">
                        {{--<img src="{{ url('assets/images/users/2.jpg')}}" alt="img"--}}
                                                    {{--class="avatar avatar-md brround">--}}

												</span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow animated" style="top: 41px !important;">
                        <div class="text-center">
                            <a
                                    class="dropdown-item text-center user pb-0 font-weight-bold"
                                    style="text-transform: uppercase;">
                                {{{ isset(Auth::user()->name) ? Auth::user()->name : Auth::user()->email }}}

                            </a>
                            @php
                                $commission = null;

                                if(Auth::check()) {
                                    $currentMonthFormatted = \Carbon\Carbon::now()->format('Y-m');
                                    $currentMonth = \App\UserCommission::where('month', $currentMonthFormatted)
                                        ->where('user_id', Auth::id())
                                        ->orderBy('id', 'DESC')
                                        ->first();

                                    if ($currentMonth) {
                                        if (Auth::user()->role == 2) {
                                            $avg = $currentMonth->revenue / ($currentMonth->delivered_order ?: 1);

                                            $range = \App\CommissionRange::where('from_order', '<=', $currentMonth->delivered_order)
                                                ->where('to_order', '>=', $currentMonth->delivered_order)
                                                ->where('from_avg', '<=', $avg)
                                                ->where('to_avg', '>=', $avg)
                                                ->first();

                                            if ($range) {
                                                $commission = $range->commission;
                                            }
                                        } elseif (Auth::user()->role == 8) {
                                            $commission = $currentMonth->delivered_order * Auth::user()->commission;
                                        }
                                    }
                                }
                            @endphp

                            @if (isset($commission))
                                <span class="text-center user-semi-title">Commission {{ $commission }} </span>
                            @endif

                            <a class="dropdown-item d-flex" href="{{url('/update_password')}}">
                                <i class="fa fa-key pr-1 mt-1 ml-1"></i>
                                <div class="">Change Password</div>
                            </a>
                            @if(Auth::user()->role == 1)
                                <a class="dropdown-item d-flex" href="{{url('/other_pass')}}">
                                    <i class="fa fa-lock pr-1 mt-1 ml-1"></i>
                                    <div class="">Other Password</div>
                                </a>
                            @endif
                            @if(in_array("79", $phoneaccess))
                                <a class="dropdown-item d-flex" href="{{url('/profile')}}">
                                    <i class="fa fa-user pr-1 mt-1 ml-1"></i>
                                    <div class="">Profile</div>
                                </a>
                            @endif
                            @if(Auth::user()->role < 6 || Auth::user()->role > 7)
                                <a class="dropdown-item d-flex" href="{{url('/guides')}}">
                                    <i class="fa fa-question-circle pr-2 mt-1 ml-1"></i>
                                    <div class="">Guides</div>
                                </a>
                            @endif
                            @if(Auth::user()->role == 1 || Auth::user()->role == 3)
                                <a class="dropdown-item d-flex" href="{{url('/jd_report')}}">
                                    <i class="fa fa-book pr-2 mt-1 ml-1"></i>
                                    <div class="">JD</div>
                                </a>
                            @endif
                            @if(in_array("120", $phoneaccess))
                                <a class="dropdown-item d-flex" href="{{ route('logout_questions.index') }}">
                                    <i class="fa fa-book pr-2 mt-1 ml-1"></i>
                                    <div class="">Logout Questions</div>
                                </a>
                            @endif
                            @if(in_array("117", $phoneaccess))
                                <a class="dropdown-item d-flex" href="{{ route('logout_questions_answers.index') }}">
                                    <i class="fa fa-book pr-2 mt-1 ml-1"></i>
                                    <div class="">Logout Questions Answers</div>
                                </a>
                            @endif
                            @php
                                $ddApi = \App\SiteSetting::find(1);
                                // if ($ddApi->allow == 1) {
                                //     # code...
                                // }
                            @endphp
                            @if(Auth::user()->role == 1 || Auth::user()->role == 9)
                                <div class="form-check form-switch dropdown-item d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" id="toggleSwitch" value="1" @if ($ddApi->allow == 1) checked @endif>
                                    <label class="form-check-label ms-3" for="toggleSwitch">Allow DayDispatch</label>
                                </div>
                            @endif
                            @if(Auth::user()->role == 1)
                                <div class="form-check form-switch dropdown-item d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" id="toggleGroupChat" value="1" @if ($ddApi->groupChatCheck == 1) checked @endif>
                                    <label class="form-check-label ms-3" for="toggleGroupChat">Group Chat Check</label>
                                </div>
                            @endif

                            {{-- cPanel Email Management (permission 162) --}}
                            @if(in_array('162', $phoneaccess) || Auth::user()->role == 1)
                                <a class="dropdown-item d-flex" href="{{ route('cpanel.email.index') }}">
                                    <i class="fa fa-envelope-o pr-2 mt-1 ml-1" style="color:#e01f26;"></i>
                                    <div class="">cPanel Emails</div>
                                </a>
                            @endif

                            {{-- View Mailbox (permission 163) --}}
                            @if(in_array('163', $phoneaccess) || Auth::user()->role == 1)
                                <a class="dropdown-item d-flex" href="{{ route('mailbox.index') }}">
                                    <i class="fa fa-inbox pr-2 mt-1 ml-1" style="color:#012862;"></i>
                                    <div class="">My Mailbox</div>
                                </a>
                            @endif

                            <a class="dropdown-item d-flex" href="{{ route('logout_questions_answers.create') }}">
                                <i class="fa fa-book pr-2 mt-1 ml-1"></i>
                                <div class="">Logout</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            </div>

        </div>
    </div>
</div>
{!! getNewQuoteTickerHtml() !!}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
     $(document).on("change", "#toggleSwitch, #toggleGroupChat", function() {
        var allow = $("#toggleSwitch").prop('checked') ? 1 : 0;
        var groupChatCheck = $("#toggleGroupChat").prop('checked') ? 1 : 0;

        $.ajax({
            url: "{{ route('allowQuotesDD') }}",
            type: "GET",
            dataType: "json",
            data: {
                allow: allow,
                groupChatCheck: groupChatCheck
            },
            success: function(res) {
                $('.alert').remove();
                var staticMessage = res.message;
                var alertDiv = '<div class="alert alert-success">' + staticMessage + '</div>';
                $('body').prepend(alertDiv);
                setTimeout(function() {
                    $('.alert').fadeOut('slow', function() {
                        $(this).remove();
                    });
                }, 3000);
            },
            error: function(xhr, status, error) {
                console.error("Error occurred:", xhr.responseText);
                var errorMessage = 'An error occurred while updating settings. Status: ' + status + ', Error: ' + error;
                var alertDiv = '<div class="alert alert-danger">' + errorMessage + '</div>';
                $('body').prepend(alertDiv);
                setTimeout(function() {
                    $('.alert').fadeOut('slow', function() {
                        $(this).remove();
                    });
                }, 3000);
            }
        });
    });
</script>
