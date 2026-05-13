<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    @include('partials.mainsite_pages.head')
    @if (Auth::check())
        @if (Auth::user()->freeze == 1)
            <style>
                body * {
                    user-select: none;
                }
            </style>
        @endif
    @endif
    <?php

    ?>

   <style>

       #chat-widget-container {
           position: fixed;
           bottom: 0;
           left: 0;
           right: 0;
           margin: auto;
           width: 995px;
           height: 625px;
           max-width: 100%;
           max-height: 100%;
           background-color: transparent;
           border: 0;
           overflow: hidden;
           display: flex;
           justify-content: center;
           align-items: center;
           z-index: 100;
       }

       @media (max-width: 768px) {
           #chat-widget-container {
               width: 100vw;
               height: 90vh;
               margin-left: 0;
           }
           .css-138p0k2 {
               margin-top: -120px;
           }
           #chat-widget {
               margin: 0 45px 21px !important;
               width: 95% !important;
               height: 100% !important;
               margin-top: -132px !important;
           }
       }

    </style>

    <style>
        .nav-tabs .nav-link.active:hover {
            color: rgb(255 255 255);
            background: #20223c;
            size: 50px;
            width: 100%;
            height: 44px;
        }

        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            width: 108vw !important;
            height: 108vh !important;
            background-color: #000;
        }

        .app-sidebar__toggle {
            visibility: hidden !important;
        }

        #addPortDetail .form-control {
            border-color: #705ec8;
        }

        #addPortDetail .modal-dialog {
            max-width: 80%;
        }

        .chat-center {
            position: fixed;
            bottom: -15px;
            right: 12px;
            z-index: 1000;
            flex-flow: row-reverse;
            width: 85%;
        }

        .chat-user {
            background-image: url({{ asset('images/chat-bg.jpg') }});
            overflow-y: scroll;
            height: 400px;
            background-size: cover;
            background-repeat: no-repeat;
        }

        .chat-user::before,
        .users-dispatchers::before {
            content: '';
            position: absolute;
            inset: 0;
            background: #000000;
            opacity: 0.3;
            top: 68px;
        }

        /* width */
        .chat-user::-webkit-scrollbar,
        .users-dispatchers::-webkit-scrollbar {
            width: 5px;
        }

        /* Track */
        .chat-user::-webkit-scrollbar-track,
        .users-dispatchers::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Handle */
        .chat-user::-webkit-scrollbar-thumb,
        .users-dispatchers::-webkit-scrollbar-thumb {
            background: #00c4ff;
            border-radius: 50px;
            border: #00c4ff;
        }

        .users-dispatchers {
            overflow-y: scroll;
            height: 450px;
        }

        .message-feed.right .mf-content:before {
            border-bottom: 8px solid #705ec8;
        }

        option {
            font-size: 14px;
        }
    </style>
    <style>
        .page-header {
            margin: 0 !important;
        }

        .page-header h1 {
            margin: 5px !important;
        }

        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');

        th,
        div,
        tr,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p,
        span {
            font-family: 'Poppins', sans-serif;
        }
    </style>

    <style>
        .css-1h1ne2e {
            cursor: pointer;
            max-width: 100%;
            position: absolute;
            bottom: 0;
            z-index: 2;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-align-items: flex-end;
            -webkit-box-align: flex-end;
            -ms-flex-align: flex-end;
            align-items: flex-end;
            will-change: width, height, transform, opacity;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            padding: 0.8em 0.8em 0 0.8em;
            right: 0;
            -webkit-box-pack: end;
            -ms-flex-pack: end;
            -webkit-justify-content: flex-end;
            justify-content: flex-end;
            --primary-color: #2000F0;
            --secondary-color: #FFFFFF;
            --tertiary-color: #E3E3E3;
        }
        .css-1aasxu6 {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex
        ;
            -webkit-flex-direction: column;
            -ms-flex-direction: column;
            flex-direction: column;
            min-width: 0;
            --primary-color: #2000F0;
            --secondary-color: #FFFFFF;
            --tertiary-color: #E3E3E3;
        }
        .css-1g9ek8d {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex
        ;
            min-width: 0;
            --primary-color: #2000F0;
            --secondary-color: #FFFFFF;
            --tertiary-color: #E3E3E3;
        }
        .css-bubhx7 {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex
        ;
            min-width: 0;
            --primary-color: #2000F0;
            --secondary-color: #FFFFFF;
            --tertiary-color: #E3E3E3;
            -webkit-flex-direction: row-reverse;
            -ms-flex-direction: row-reverse;
            flex-direction: row-reverse;
        }
        .css-138p0k2 {
            position: relative;
            width: 255px;
            height: 50px;
            box-shadow: rgba(0, 0, 0, 0.125) 0px 0.362176px 0.941657px -1px, rgba(0, 0, 0, 0.18) 0px 3px 7.8px -2px;
            background: #FFFFFF;
            border-radius: 8px 8px 0px 0px;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex
        ;
            padding: 0 0.9em;
            -webkit-align-items: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            --primary-color: #2000F0;
            --secondary-color: #FFFFFF;
            --tertiary-color: #E3E3E3;
            position: relative;
            color: #FFFFFF;
            background-color: #00A1EF;
        }
        .css-e4pgre {
            margin: 1em 0;
            --primary-color: #2000F0;
            --secondary-color: #FFFFFF;
            --tertiary-color: #E3E3E3;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            -webkit-box-flex: 1;
            -webkit-flex-grow: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            font-weight: 700;
            font-size: 0.9em;
            margin-right: 4px;
            --primary-color: #2000F0;
            --secondary-color: #FFFFFF;
            --tertiary-color: #E3E3E3;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .css-1iovl8i {
            -webkit-appearance: none;
            -moz-appearance: none;
            -ms-appearance: none;
            appearance: none;
            background: transparent;
            border: 0;
            display: inline-block;
            margin: 0;
            padding: 0.5em;
            color: inherit;
            cursor: pointer;
            --primary-color: #2000F0;
            --secondary-color: #FFFFFF;
            --tertiary-color: #E3E3E3;
            padding: 0 .5em;
            padding: 0;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex
        ;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            -webkit-justify-content: center;
            justify-content: center;
            -webkit-align-items: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            border: 0;
            border-radius: 6px;
            --primary-color: #2000F0;
            --secondary-color: #FFFFFF;
            --tertiary-color: #E3E3E3;
        }
    </style>
    <style>
        #chat-widget {
            display: none;
            transform-origin: top center;
        }

        /* Flip and bounce animation */
        @keyframes flipInAirBounce {
            0% {
                transform: perspective(600px) rotateX(90deg);
                opacity: 0;
            }
            60% {
                transform: perspective(600px) rotateX(0deg);
                opacity: 1;
            }
            80% {
                transform: translateY(-10px);
            }
            100% {
                transform: translateY(0);
            }
        }

        .flip-bounce {
            animation: flipInAirBounce 0.8s ease-out;
        }
    </style>
    <style>
        .inactivee {
            pointer-events: none; /* Disable clicks only when the iframe is hidden */
        }
        .activee {
            pointer-events: auto; /* Disable clicks only when the iframe is hidden */
        }
        /* Blinking animation */
        @keyframes blink {
            0% { color: red; }
            50% { color: blue; }
            100% { color: red; }
        }

        /* Apply the animation to the element */
        .blink {
            animation: blink 1s infinite; /* 1s duration, infinite loop */
        }

    </style>
</head>

<body class="app sidebar-mini">
    @include('partials.scope_warning_bar')
    @if (Auth::check())
        @if (Auth::user()->freeze == 1)
            @php
                $reason = \App\FreezeUser::where('user_id', Auth::user()->id)
                    ->orderBy('created_at', 'DESC')
                    ->where('status', 0)
                    ->first();
            @endphp
            <marquee class="bg-danger text-light"
                style="position:fixed;top:0;width:100%;z-index:99999;height:50px;opacity:1;">
                <h3 class="mt-3">
                    {{-- Your account has been freezed due to inactivity. Kindly contact with admin. --}}
                    {{ $reason->reason }}
                </h3>
            </marquee>
        @endif
    @endif




    <!---Global-loader-->
    <div id="global-loader">
        <img src="{{ url('assets/images/svgs/loader.svg') }}" alt="loader">
    </div>
    <!--- End Global-loader-->
    <!-- Page -->

    <div class="page">
        <div class="page-main">
            {{-- sidebar start --}}
            @if (Auth::check())
                @if (Auth::user()->role != 19)
                    @if (Auth::user()->role < 6 || Auth::user()->role > 7)
                        @if (request()->route()->getName() != 'logout_questions_answers.create')
                            @include('partials.mainsite_pages.sidebar')
                        @endif
                    @endif
                @endif
            @endif
            {{-- sidebarend --}}

            <div class="app-content main-content">
                <div class="side-app">
                    <!--nav header-->

                    @if (Auth::check())
                        @if (Auth::user()->role != 19)
                            @if (request()->route()->getName() != 'logout_questions_answers.create')
                                @include('partials.mainsite_pages.nav')
                            @endif
                        @endif
                    @endif
                    <!--/nav header-->
                    @include('partials.document_warning_bar')

                    <!--/content-->
                    <br>
                    <div id="session_msg">
                        @if (Session::has('msg'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>{{ Session::get('msg') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if (Session::has('err'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>{{ Session::get('err') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if (request()->route()->getName() != 'logout_questions_answers.create')
                            @if (Auth::check())
                                <div class="d-flex justify-content-between">
                                    <?php
                                    $role = Auth::user()->userRole->name;
                                    ?>
                                    @if ($role == 'Order Taker' || $role == 'CSR' || $role == 'Seller Agent' || $role == 'Dispatcher' || $role == 'Admin')
                                        <a href="{{ url('/user_rating') }}"
                                            class="badge badge-warning text-light position-relative" id="rating_count">
                                            <i class="fa fa-star"></i>
                                            @if ($role == 'Admin')
                                                Rating
                                            @else
                                                Your Rating
                                            @endif
                                        </a>
                                    @else
                                        <div></div>
                                    @endif
                                    <div class="d-flex">
                                        @if (Auth::user()->break_time == 1)
                                            <?php
                                            date_default_timezone_set('America/New_York');
                                            $timeFirst = strtotime(Auth::user()->updated_at);
                                            $timeSecond = strtotime(now());
                                            $differenceInSeconds = $timeSecond - $timeFirst;
                                            $getTime = round($differenceInSeconds / 60, 2);
                                            ?>
                                            <input type="hidden" id="break" name="break"
                                                value="{{ $getTime }}" />
                                            <a href="{{ url('/end_time') }}" class="badge badge-danger mr-3"
                                                id="end_time">00:00</a>
                                        @else
                                            <a href="{{ url('/start_time') }}" class="badge badge-success mr-3"
                                                id="start_time">Start Time</a>
                                        @endif
                                        <a href="{{ url('/clear_cache') }}" class="badge badge-danger"
                                            id="clear_cache">Clear Cache</a>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>

                    @yield('content')

                    @php
                        if (!function_exists('check_panel2')) {
                          function check_panel2()
                          {
                              $setting = App\general_setting::first();
                              $ptype = 1;

                              // Check if user setting exists
                              $query = \App\user_setting::where('user_id', Auth::user()->id)->first();
                              if (!empty($query)) {
                                  $ptype = $query['penal_type'];
                              }

                              return $ptype;
                          }
                      }
                     $check_panel = check_panel2();

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
                    @if(auth()->user()->role == 1 || in_array('151', $phoneaccess))
                        <div class="chat">
                            <div id="chat-widget-container" >
                                <iframe allow="clipboard-read; clipboard-write; autoplay; microphone *; camera *; display-capture *; picture-in-picture *; fullscreen *;"
                                        id="chat-widget"
                                        name="chat-widget"
                                        title="LiveChat chat widget"
                                        scrolling="no"
                                        style="width: 100%; height: 100%; min-height: 0px; min-width: 0px; margin: 0px; padding: 0px; background-image: none; background-position: 0% 0%; background-size: initial; background-attachment: scroll; background-origin: initial; background-clip: initial; background-color: transparent; border-width: 0px; float: none; color-scheme: normal; position: absolute; inset: 0px; transition: none !important; display: none; visibility: visible;">

                                </iframe>
                            <div dir="ltr" role="main" data-lc-id="0" class="css-1h1ne2e e1558m8u1" >
                                    <div class="css-1aasxu6 e131382t0">
                                        <div class="css-1g9ek8d e1kv8om20"></div>
                                        <div class="css-bubhx7 e1kv8om20">
                                            <div data-lc-id="1" class="css-138p0k2 e16i86ec1" id="chat_with_us">
                                                <p class="css-e4pgre e16i86ec0" id="live_support_window">Live Support Window OT</p>
                                                <button type="button" aria-label="Open LiveChat chat widget"
                                                        class="e1mwfyk10 css-1iovl8i ejbfa1m0">
                                                    <div class="css-1potzn5 e1dmt1bi3">
                                                        <svg color="inherit" viewBox="0 0 32 32" class="css-1usdo54">
                                                            <path fill="#FFFFFF"
                                                                  d="M12.63,26.46H8.83a6.61,6.61,0,0,1-6.65-6.07,89.05,89.05,0,0,1,0-11.2A6.5,6.5,0,0,1,8.23,3.25a121.62,121.62,0,0,1,15.51,0A6.51,6.51,0,0,1,29.8,9.19a77.53,77.53,0,0,1,0,11.2,6.61,6.61,0,0,1-6.66,6.07H19.48L12.63,31V26.46"></path>
                                                            <path fill="#00A1EF"
                                                                  d="M19.57,21.68h3.67a2.08,2.08,0,0,0,2.11-1.81,89.86,89.86,0,0,0,0-10.38,1.9,1.9,0,0,0-1.84-1.74,113.15,113.15,0,0,0-15,0A1.9,1.9,0,0,0,6.71,9.49a74.92,74.92,0,0,0-.06,10.38,2,2,0,0,0,2.1,1.81h3.81V26.5Z"
                                                                  class="css-1adcsh3 eam5rsy0"></path>
                                                        </svg>
                                                        <div class="css-anyrkw e1dmt1bi2"></div>
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (Auth::check())
                        @if (Auth::user()->role < 6 || Auth::user()->role > 7)
                            @if (request()->route()->getName() != 'logout_questions_answers.create')
                                <div class="bottompopups" style="display: none;">
                                    <a href="" data-toggle="modal" data-target="#stickynotemodal" class="bp1"
                                        style="display: inline;">
                                        <i class="fa fa-sticky-note"></i>
                                    </a>
                                    <a href="" data-toggle="modal" data-target="#reportmodal" class="bp2"
                                        style="display: inline;">
                                        <i class="fa fa-envelope-o"></i>
                                    </a>

                                    <a href="" data-toggle="modal" data-target="#wordmodal" class="bp3"
                                        style="display: inline;">
                                        <i class="fa fa-file-word-o"></i>
                                    </a>
                                    <a href="" data-toggle="modal" data-target="#portmodal" class="bp4"
                                        style="display: inline;">
                                        <i style="margin-top: 5px; float: left;font-size: 0px;"><img
                                                src="https://admin.shipa1.com/img/port-icon.png"></i>
                                    </a>
                                    <a href="{{ url('chats') }}" class="bp5"
                                        style="display: inline; background:#7eedff; ">
                                        <img src="{{ url('assets/images/m.png') }}" style="height:100%;">
                                    </a>
                                </div>
                            @endif
                        @endif
                    @endif
                    <div class="container position-relative">
                        <div class="row chat-center">
                        </div>
                    </div>
                    <!-- /Row -->
                    <div class="modal" tabindex="-1" id="stickynotemodal">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content modal-content-demo">
                                <div class="modal-header">
                                    <h6 class="modal-title">SAVED NOTES</h6>
                                    <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                                        <span aria-hidden="true">&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <input type="hidden" name="_token" id="note_token"
                                            value="{{ csrf_token() }}">
                                        <div class="row row-cards">
                                            <div class="col-md-12">
                                                <div class="card">

                                                    <div class="card-body">
                                                        <textarea class="content" name="example" required></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </form>

                                </div>
                                <div class="modal-footer">

                                    <button class="btn btn-primary addContent" type="button"
                                        data-dismiss="modal">Save
                                    </button>
                                    <button class="btn btn-success viewNotes" type="button" data-toggle="modal"
                                        data-target="#showNotes">View
                                    </button>
                                    <button class="btn btn-danger" data-dismiss="modal" type="button">Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="showNotes" tabindex="-1" role="dialog"
                        aria-labelledby="showNotesTitle" aria-hidden="true">
                        <div class="modal-dialog" role="document" style="max-width: 70% !important;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="showNotesTitle">All Notes</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Notes</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="allReviews">

                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal" tabindex="-1" id="carrirermodal">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content tx-size-sm">
                                <div class="modal-header pd-x-20">
                                    <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Carrier Update</h6>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body pd-20">
                                    <h5 class=" lh-3 mg-b-20"></h5>
                                    <div class="card">
                                        <div class="card-body pd-20">
                                            <form action="/carrierupdate/0" method="post" id="carrier">
                                                @csrf
                                                <input type="hidden" name="user_id" value="47">
                                                <div class="form-group">
                                                    <div class="row row-sm">
                                                        <div class="col-sm">
                                                            Enter Order Id
                                                            <input type="text" name="orderid" id="orderid"
                                                                class="form-control" placeholder="Enter Order Id">
                                                        </div><!-- col -->
                                                    </div><!-- row -->
                                                </div><!-- form-group -->

                                                <button type="submit" class="btn btn-primary pd-x-20">Submit
                                                </button>
                                            </form>
                                        </div><!-- card-body -->
                                    </div>

                                </div><!-- modal-body -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal" tabindex="-1" id="reportmodal">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content tx-size-sm">
                                <div class="modal-header pd-x-20">
                                    <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Report to Admin</h6>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body pd-20">
                                    <h5 class=" lh-3 mg-b-20">Write any issue, mishap or report a person</h5>
                                    <div class="card">
                                        <div class="card-body pd-20">
                                            <form action="javascript:void(0)" method="post" id="reportToAdmin">
                                                <input type="hidden" name="user_id" value="47">
                                                <div class="form-group">
                                                    <div class="row row-sm">
                                                        <div class="col-sm">
                                                            <input type="text" name="subject" id="reportSub"
                                                                class="form-control"
                                                                placeholder="Enter Subject Of Report">
                                                        </div><!-- col -->
                                                    </div><!-- row -->
                                                </div><!-- form-group -->
                                                <div class="form-group">
                                                    <textarea class="form-control" rows="4" name="report" id="reportComments"
                                                        placeholder="Enter detailed comments here..."></textarea>
                                                </div><!-- form-group -->
                                                <button type="submit" class="btn btn-primary pd-x-20">Submit
                                                </button>
                                            </form>
                                        </div><!-- card-body -->
                                    </div>

                                </div><!-- modal-body -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal" tabindex="-1" id="wordmodal">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content tx-size-sm" style="width: 100%;height: auto">
                                <div class="modal-header pd-x-20">
                                    <h4 class="tx-14 mg-b-0  tx-bold">Auction Instructions</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body pd-20">
                                    <h5 class=" lh-3 mg-b-20">Select and Copy Instructions from list</h5>
                                    <ul class="nav nav-tabs Navtabs" role="tablist"
                                        style=" padding: 10px; flex-wrap: wrap;">
                                        <li class="nav-item">
                                            <a class="nav-link active" href="#inopdispatch" role="tab"
                                                data-toggle="tab">INOP Vehicle Dispatch</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#atauction" role="tab"
                                                data-toggle="tab">At
                                                Auction</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#privatejobs" role="tab"
                                                data-toggle="tab">For
                                                Private Jobs</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#ports" role="tab"
                                                data-toggle="tab">Ports</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#custom" role="tab"
                                                data-toggle="tab">Custom</a>
                                        </li>

                                        @if (Auth::user()->role == 1 || Auth::user()->role == 3)
                                            <li class="nav-item">
                                                <a class="nav-link" href="#Auction_Business" role="tab"
                                                    data-toggle="tab">Auction To Business</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#Business_To_Business " role="tab"
                                                    data-toggle="tab">Business To Business </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#Auction_To_Port" role="tab"
                                                    data-toggle="tab">Auction to Port</a>
                                            </li>
                                        @endif
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade active show" id="inopdispatch">
                                            <button
                                                class="btn btn-primary copy1 align-center mg-t-20 mg-b-20 my-2">Click
                                                To
                                                Copy Text
                                            </button>
                                            <span id="myTooltip" style="color: black; font-weight: bold;"></span>
                                            <textarea readonly style="width: 100%;height: 74%;background-color:white" rows="10" class="form-control "
                                                id="text_copy1">

*******************Must be Read  below instructions**********************

At auction: load and only roll down on delivery (MUST PICK TITLE/S & KEYS)

We always recommend drivers, call the auction to confirm about condition, storage, title/key, and address, or if an appointment is required (Especially Copart). Following the instructions, this is a simple effort that can save your time and fuel.

1. If any storage fees are $ Must pay with cash only they will be reimbursed to you with your transportation charges

2. At pick-up location must pick up with original and correct title & key and don’t pick up without title & keys.

3. No Forklift on Delivery, Must put this vehicle at the last bottom of the trailer so you can roll it down at delivery. Delivery time Mon to Friday 9 am to 4 pm. For Saturday we have to check with clients. Must call 2 hours before delivery and must inform the Customer. No surprise delivery is accepted.

4. If you do not pick up on time, then you have to bear storage of any kind.

5. You have to collect loose parts of the vehicle if there are any.

If the carrier or driver need any kind of information or facing a problem at Pick up or Delivery, please call 240-341-0040 / 307-222 -7674

  UPDATE  STATUS OF VEHICLE ON CENTRAL ASAP.

( MN )
                                        </textarea>

                                            <span style="float: right; font-size: 15px;">Press CTRL+A in Box Then
                                                Press
                                                CTRL+C to Copy</span>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="atauction">
                                            <button onclick="atauctionfunc()"
                                                class="btn btn-primary copy5 align-center mg-t-20 mg-b-20 my-2">Click
                                                To
                                                Copy Text
                                            </button>
                                            <span id="myTooltip1" style="color: black; font-weight: bold;"></span>
                                            <textarea readonly style="width: 100%;height: 74%;background-color:white" rows="10" class="form-control"
                                                id="text_copy5"></textarea>
                                            <span style="float: right; font-size: 16px;">Press CTRL+A in Box Then
                                                Press
                                                CTRL+C to Copy</span>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="privatejobs">
                                            <button
                                                class="btn btn-primary copy2 align-center mg-t-20 mg-b-20 my-2">Click
                                                To
                                                Copy Text
                                            </button>
                                            <span id="myTooltip2" style="color: black; font-weight: bold;"></span>
                                            <textarea rows="10" class="form-control" id="text_copy2" readonly
                                                style="width: 100%;height: 74%;background-color:white">

********************Must be Read  below instructions***********************

We always recommend drivers, call Customers before pickup, to confirm the vehicle is good to go,  follow the instructions, this is a simple effort that can save your time and fuel.

MUST CALL CUSTOMER 2 to 4 HRS Before Pickup / DELIVERY and don't make any SURPRISE pick-up/delivery.

Pick up the vehicle exactly according to "THE CENTRAL DISPATCH SHEET'S DATE & TIMINGS"

Make sure that the driver should leave a copy of the inspection at pick-up and on delivery. Also, make sure to send us an email at "shawnmoving@shipa1.com".

If the carrier or driver need any kind of information or facing a problem at Pick up or Delivery, please call 240-341-0040 / 307-222-7674  update the status of a vehicle on central dispatch as soon as possible

  UPDATE  STATUS OF VEHICLE ON CENTRAL ASAP.

( MN )

										</textarea>
                                            <span style="float: right; font-size: 10px;">Press CTRL+A in Box Then
                                                Press
                                                CTRL+C to Copy</span>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="ports">
                                            <button onclick="portsfunc()"
                                                class="btn btn-primary copy3 align-center mg-t-20 mg-b-20 my-2">Click
                                                To
                                                Copy Text
                                            </button>
                                            <span id="myTooltip3" style="color: black; font-weight: bold;"></span>
                                            <textarea rows="10" readonly style="width: 100%;height: 74%;background-color:white" class="form-control"
                                                id="text_copy3">
*******************Must be Read  below instructions**********************

We always recommend drivers, call Customers before pickup, to confirm the vehicle is good to go,  follow the instructions, this is a simple effort that can save your time and fuel.

1. Vehicle Going to the port need “TWIC” card in Grimaldi line & it will be a Quick Pay Job with company electronic cheque in 2 working days only

2. Maybe there is storage on this vehicle for $0.00 kindly pay with cash at the auction you will reimburse with your transportation charges after sending a stamped dock receipt

3. Must Collect Title & Keys, don’t pick up a vehicle without title and keys

4. Once you have sent us the title picture and condition report then we will apply for dock receipt and doc receipt will be provided to you the next day in the afternoon. “DON’T GO EARLY MORNING FOR THE DELIVERY”

5. At Pickup, the Driver must check the condition of the vehicle. Must click pictures and Video of the vehicle. If the vehicle does not start Try to Jumpstart it and Make a short video clip of it.

6. Important Note:
(a): Sign at the back of the Title Before making the delivery at the port.
(b): Make sure by calling us, after sending us the signed DOCK RECEIPT & BILL OF LADING that it was received correctly for on-time check dispatched from our side.
(c): Going to Port. Make sure to make 6 copies of the front and back of the title, with the original title, 6 Copies of dock receipts (Notarized bill of sale for high heavy Truck & Machine).
(d): Make sure to CHECK no/damaged windshield, front/rear bumpers, flat tire/s, missing lights, damaged door windows, broken suspension, false door alarm if found anything WRONG for the delivery at PORT, just call us right then, otherwise, we will not be held responsible for any LOSS of money or TIME

If the carrier or driver need any kind of information or facing a problem at Pick up or Delivery, please call 240-341-0040 / 307-222-7674

UPDATE  STATUS OF VEHICLE ON CENTRAL ASAP.

( UD)
										</textarea>
                                            <span style="float: right; font-size: 10px;">Press CTRL+A in Box Then
                                                Press
                                                CTRL+C to Copy</span>
                                        </div>

                                        <div role="tabpanel" class="tab-pane fade" id="custom">
                                            <button onclick="customfunc1()"
                                                class="btn btn-primary copy4 align-center mg-t-20 mg-b-10 my-2">Click
                                                To
                                                Copy Text
                                            </button>
                                            <span id="myTooltip4" style="color: black; font-weight: bold;"></span>
                                            <textarea readonly style="width: 100%;height: 74%;background-color:white" rows="4"
                                                class="form-control text_copy4" onkeyup="customtext(this,'47')" onchange="customtext(this,'47')"
                                                onpaste="customtext(this,'47')" id="customtext1"></textarea>
                                            <span style="float: right; font-size: 10px;">Press CTRL+A in Box Then
                                                Press
                                                CTRL+C to Copy</span>
                                        </div>

                                        <div role="tabpanel" class="tab-pane fade" id="Auction_Business">
                                            <button onclick="customfunc1('customtext2')"
                                                class="btn btn-primary copy4 align-center mg-t-20 mg-b-10 my-2">Click
                                                To
                                                Copy Text
                                            </button>
                                            <span id="myTooltip5" style="color: black; font-weight: bold;"></span>
                                            <textarea readonly style="width: 100%;height: 74%; background-color:white" rows="4"
                                                class="form-control text_copy4" onkeyup="customtext(this,'47')" onchange="customtext(this,'47')"
                                                onpaste="customtext(this,'47')" id="customtext2">
Agent:- Thank you for calling All State To State Auto Transport, I am kevin how can i help you today ?
Carrier:-

Agent:- Can you give me (Order id / Vehicle name / Pick up or delivery state)

Carrier:-
Agent :- This vehicle is coming out from (Pick up location name E.g. Iaai, Copart, Business) going to (Delivery location name E.g. Residence, Business), When you want to pickup and deliver this?

Carrier:-

Agent:- What is your Mc/Dot number?

Carrier:-

Agent:-  Condition of this Vehicle: Listed as run and drive 4 tires are fine if the vehicle does not start it is in the position you can  roll it down on delivery OR Condition of this Vehicle: Listed as run and drive but i can see (Explain the issue) OR Condition of this Vehicle (Explain the issue)

Agent :-  How many car hauler you have? OR What kind of car trailer you have ? 3 Car / 4 Car Which one?

Carrier :-

*If double deck then*

Agent :-

For Runner :- Keep this vehicle on a lower deck of the trailer so you can easily drive it down or roll it down on delivery as customer dont have forklift on delivery

For Nonrunner / Rollable:- At auction: Load only at last bottom of the trailer and roll down on delivery, No Forklift on Delivery customer will help you to roll it down OR Keep this vehicle on a lower deck of the trailer so you can easily roll it down as customer dont have forklift on delivery

For Forklift:- At auction: Load only at last bottom of the trailer (No Top / Middle position) on delivery customer will take it off with the small forklift OR Customer have small forklift on delivery keep that vehicle on the last bottom of the trailer on a lower deck so it can be offload easily

Carrier :-

*If Carrier deny then*

For Runner :- Customer dont have a way to take it off, you have to do it by yourself

For Nonrunner / Rollable:- Customer dont have forklift on delivery, if you keep this on a lower deck of the trailer it can be offload easily by rolling down and customer will help you in it, customer only need bottom position.

For Forklift:- Customer only have a small forklift on delivery that can take the vehicle from the lower deck if you keep this on the top of the trailer you will have an issue on delivery, customer only need bottom position.

Carrier :-

Agent :-  Pick up & Delivery time frame is Monday to Friday 9am to 4pm. Deliver before 4pm otherwise next working day, Must collect title and key, Dont pick up the vehicle without title,
Agent :-  Maybe there is storage on this vehicle for $0.00 kindly pay with cash at the auction you will reimburse with your transportation charges OR If there is any storage fees at auction must pay with cash only you will be reimbursed at delivery

Carrier :-

Agent :-  Ok i am dispatching you this job i need your Need COI, (Certificate of insurance Holder), Write my company information on the certificate holder column and send it to me on email.  I will text you my company information.

Carrier:

Agent: Can you text me your email so I can dispatch this to you?

Carrier:

Agent: Thank you for your cooperation. We appreciate your attention to these details. Have a safe and successful transport, and please don't hesitate to reach out if you have any further questions or concerns. Have a great day!

                                        </textarea>
                                            <span style="float: right; font-size: 10px;">Press CTRL+A in Box Then
                                                Press
                                                CTRL+C to Copy</span>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="Business_To_Business">
                                            <button onclick="customfunc1('customtext3')"
                                                class="btn btn-primary copy4 align-center mg-t-20 mg-b-10 my-2">Click
                                                To
                                                Copy Text
                                            </button>
                                            <span id="myTooltip6" style="color: black; font-weight: bold;"></span>
                                            <textarea readonly style="width: 100%;height: 74%; background-color:white" rows="4"
                                                class="form-control text_copy4" onkeyup="customtext(this,'47')" onchange="customtext(this,'47')"
                                                onpaste="customtext(this,'47')" id="customtext3">
Agent :- Thank you for calling All State To State Auto Transport, I am kevin how can i help you today ?

Carrier :-

Agent :- Can you give me (Order id / Vehicle name / Pick up or delivery state)

Carrier :-

Agent :- This vehicle is coming out from (Pick up location name E.g. Dealership, Business, Private Business) going to (Delivery location name E.g. Dealership, Business, Private Business), When you want to pickup and deliver this?

Carrier :-

Agent :- What is your Mc/Dot number?

Carrier :-

Agent :-  Pick up & Delivery time frame is Monday to Friday 9am to 4pm. Pick up and Deliver before 4pm otherwise next working day after 4 customer will not be available to receive vehicle, and For Saturday we have to check with our customer,  Driver must call Customer 2 hours before pick up / delivery.

Carrier :-

Agent :-  Ok i am dispatching you this job i need your Need COI, (Certificate of insurance Holder), Write my company information on the certificate holder column and send it to me on email. I will text you my company information.

Carrier:

Agent: Can you text me your email so I can dispatch this to you?

Carrier:

Agent: Thank you for your cooperation. We appreciate your attention to these details. Have a safe and successful transport, and please don't hesitate to reach out if you have any further questions or concerns. Have a great day!

                                        </textarea>
                                            <span style="float: right; font-size: 10px;">Press CTRL+A in Box Then
                                                Press
                                                CTRL+C to Copy</span>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="Auction_To_Port">
                                            <button onclick="customfunc1('customtext4')"
                                                class="btn btn-primary copy4 align-center mg-t-20 mg-b-10 my-2">Click
                                                To
                                                Copy Text
                                            </button>
                                            <span id="myTooltip7" style="color: black; font-weight: bold;"></span>
                                            <textarea readonly style="width: 100%;height: 74%;background-color:white" rows="4"
                                                class="form-control text_copy4" onkeyup="customtext(this,'47')" onchange="customtext(this,'47')"
                                                onpaste="customtext(this,'47')" id="customtext4">
Agent :- Thank you for calling All State To State Auto Transport, I am kevin how can i help you today ?

Carrier :-

Agent :- Can you give me (Order id / Vehicle name / Pick up or delivery state)

Carrier :-

Agent :- This vehicle is coming out from (Pick up location name E.g. Dealership, Business, Private Business) going to (Delivery location name E.g. Port ), When you want to pickup and deliver this?

Carrier :-

Agent :- What is your Mc/Dot number?

Carrier :-

Agent :- (If grimaldi) Do you have twic card?

Carrier :-

Agent :- Vehicle Going to the (Grimaldi line) / (Sallaum line), Payment method for this job will be a Quick Pay with company electronic cheque in 2 business days after receiving the stamp dock receipt, (Once you send us the stamp dock receipt we will issue an echeque on your email),
Agent :- Must Collect Title & Keys, don’t pick up a vehicle without title and keys,
Agent :- At Pickup, the Driver must check the condition of the vehicle, If the vehicle does not start Try to Jumpstart it, and Make a short video clip of it. Must click pictures and Video of the vehicle.
Agent :- Once you have sent us the title picture and condition report then we will apply for a dock receipt and the doc receipt will be provided to you the next day in the afternoon. “DON’T GO EARLY MORNING FOR THE DELIVERY”

Carrier :-

Agent: Okay, I am dispatching you this job, and I need your Certificate of Insurance (COI), with my company information listed as the certificate holder. Please send it to me via email. I will text you my company information.

Carrier:

Agent: Can you text me your email so I can dispatch this to you?

Carrier:

Agent: Thank you for your cooperation. We appreciate your attention to these details. Have a safe and successful transport, and please don't hesitate to reach out if you have any further questions or concerns. Have a great day!
                                        </textarea>
                                            <span style="float: right; font-size: 10px;">Press CTRL+A in Box Then
                                                Press
                                                CTRL+C to Copy</span>
                                        </div>

                                    </div>

                                </div><!-- modal-body -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $port1 = \App\PortDetail::where('delivery_address', 1)->get();
                    $port2 = \App\PortDetail::where('delivery_address', 2)->get();
                    ?>
                    <div class="modal" tabindex="-1" id="portmodal">
                        <div id="portmodal" class="modal fade effect-slide-in-bottom show"
                            style="padding-right: 19px; display: block;">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content tx-size-sm"
                                    style=" width: 154% !important; margin-left: -26%; ">
                                    <div class="modal-header pd-x-10">
                                        <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Port Details</h6>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body pd-20">

                                        <!-- Tab panes -->
                                        <div class="tab-content">

                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="card bd-0">
                                                        <div class="card-header bg-primary">

                                                            <h5 class="card-title" style="color: white;">SALLAUM
                                                                LINES DELIVERY ADDRESSES </h5>
                                                        </div><!-- card-header -->
                                                        <div class="card-body bd bd-t-0">
                                                            <div class="mg-b-0"
                                                                style="overflow-y: scroll; height: 300px;">
                                                                @if ($port1)
                                                                    @foreach ($port1 as $Key => $value)
                                                                        <div class="d-flex justify-content-between">
                                                                            <b
                                                                                class="mt-auto">{{ $value->port_name }}</b>
                                                                            @if (Auth::check())
                                                                                @if (Auth::user()->role == 1)
                                                                                    <div class="btn btn-group">
                                                                                        <button type="button"
                                                                                            class="btn btn-info"
                                                                                            data-toggle="modal"
                                                                                            data-target="#updatePortDetail{{ $value->id }}"
                                                                                            onclick="updatePort({{ $value->id }})">Update</button>
                                                                                        <button type="button"
                                                                                            class="btn btn-danger"
                                                                                            onclick="deletePort({{ $value->id }})">Delete</button>
                                                                                    </div>
                                                                                @endif
                                                                            @endif
                                                                        </div>
                                                                        @if ($value->make_sure == 1 || $value->accident_vehicle == 1)
                                                                            <span style="font-style: italic;">
                                                                                @if ($value->make_sure == 1 && $value->accident_vehicle == 1)
                                                                                    (MAKE SURE ADDRESS &amp; NO INOP
                                                                                    OR
                                                                                    ACCIDENT VEHICLE)
                                                                                @elseif($value->make_sure == 1)
                                                                                    (MAKE SURE ADDRESS)
                                                                                @elseif($value->accident_vehicle == 1)
                                                                                    (NO INOP OR ACCIDENT VEHICLE)
                                                                                @endif
                                                                            </span>
                                                                        @endif
                                                                        <br>
                                                                        @if ($value->terminal)
                                                                            {{ $value->terminal }}<br>
                                                                        @endif
                                                                        @if ($value->address)
                                                                            {{ $value->address }}<br>
                                                                        @endif
                                                                        @if ($value->zsc)
                                                                            {{ $value->zsc }}<br>
                                                                        @endif
                                                                        @if ($value->tel)
                                                                            Tel: {{ $value->tel }}<br>
                                                                        @endif
                                                                        @if ($value->twic_card != 0)
                                                                            <span style="font-style: italic;">TWIC
                                                                                card
                                                                                required for entry
                                                                                {{ $value->twic_card == 2 ? '(Optional)' : '' }}</span>
                                                                        @endif
                                                                        <hr>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        </div><!-- card-body -->

                                                    </div><!-- card -->
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card bd-0">
                                                        <div class="card-header  bg-primary">
                                                            <h5 class="card-title" style="color: white;">Grimaldi
                                                                Group Shipping Line </h5>


                                                        </div><!-- card-header -->
                                                        <div class="card-body bd bd-t-0">
                                                            <div class="mg-b-0"
                                                                style="overflow-y: scroll; height: 300px;">

                                                                @if ($port2)
                                                                    @foreach ($port2 as $Key => $value)
                                                                        <div class="d-flex justify-content-between">
                                                                            <b
                                                                                class="mt-auto">{{ $value->port_name }}</b>
                                                                            @if (Auth::check())
                                                                                @if (Auth::user()->role == 1)
                                                                                    <div class="btn btn-group">
                                                                                        <button type="button"
                                                                                            class="btn btn-info"
                                                                                            data-toggle="modal"
                                                                                            data-target="#updatePortDetail{{ $value->id }}"
                                                                                            onclick="updatePort({{ $value->id }})">Update</button>
                                                                                        <button type="button"
                                                                                            class="btn btn-danger"
                                                                                            onclick="deletePort({{ $value->id }})">Delete</button>
                                                                                    </div>
                                                                                @endif
                                                                            @endif
                                                                        </div>
                                                                        @if ($value->make_sure == 1 || $value->accident_vehicle == 1)
                                                                            <span style="font-style: italic;">
                                                                                @if ($value->make_sure == 1 && $value->accident_vehicle == 1)
                                                                                    (MAKE SURE ADDRESS &amp; NO INOP
                                                                                    OR
                                                                                    ACCIDENT VEHICLE)
                                                                                @elseif($value->make_sure == 1)
                                                                                    (MAKE SURE ADDRESS)
                                                                                @elseif($value->accident_vehicle == 1)
                                                                                    (NO INOP OR ACCIDENT VEHICLE)
                                                                                @endif
                                                                            </span>
                                                                        @endif
                                                                        <br>
                                                                        @if ($value->terminal)
                                                                            {{ $value->terminal }}<br>
                                                                        @endif
                                                                        @if ($value->address)
                                                                            {{ $value->address }}<br>
                                                                        @endif
                                                                        @if ($value->zsc)
                                                                            {{ $value->zsc }}<br>
                                                                        @endif
                                                                        @if ($value->tel)
                                                                            Tel: {{ $value->tel }}<br>
                                                                        @endif
                                                                        @if ($value->twic_card != 0)
                                                                            <span style="font-style: italic;">TWIC
                                                                                card
                                                                                required for entry
                                                                                {{ $value->twic_card == 2 ? '(Optional)' : '' }}</span>
                                                                        @endif
                                                                        <hr>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        </div><!-- card-body -->
                                                    </div>
                                                </div>


                                            </div>

                                        </div>

                                        <script>
                                            document.querySelector(".copy1").onclick = function() {
                                                document.querySelector("#text_copy1").select();
                                                document.execCommand('copy');
                                            };
                                            document.querySelector(".copy2").onclick = function() {
                                                document.querySelector("#text_copy2").select();
                                                document.execCommand('copy');
                                            };
                                            document.querySelector(".copy3").onclick = function() {
                                                document.querySelector("#text_copy3").select();
                                                document.execCommand('copy');
                                            };
                                            document.querySelector(".copy4").onclick = function() {
                                                document.querySelector("#text_copy4").select();
                                                document.execCommand('copy');
                                            };
                                        </script>

                                    </div><!-- modal-body -->
                                    <div class="modal-footer">
                                        @if (Auth::check())
                                            @if (Auth::user()->role == 1)
                                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#addPortDetail">Add New
                                                </button>
                                            @endif
                                        @endif
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                        </button>
                                    </div>
                                </div>
                            </div><!-- modal-dialog -->
                        </div>
                    </div>

                    <div class="modal fade" id="addPortDetail" tabindex="-1" role="dialog"
                        aria-labelledby="addPortDetailLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add New Port Detail</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="#" type="POST">
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="delivery_address">Select Delivery Address</label>
                                                    <select class="form-control" id="delivery_address"
                                                        name="delivery_address">
                                                        <option value="" selected>Select Delivery Address
                                                        </option>
                                                        <option value="1">SALLAUM LINES DELIVERY ADDRESSES
                                                        </option>
                                                        <option value="2">Grimaldi Group Shipping Line
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="twic_card">Select Twic Card</label>
                                                    <select class="form-control" id="twic_card" name="twic_card">
                                                        <option value="0">No TWIC Card required</option>
                                                        <option value="1">TWIC card required for entry
                                                        </option>
                                                        <option value="2">TWIC card required for entry
                                                            (Optional)
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="port_name">Port Name</label>
                                                    <input type="text" class="form-control" id="port_name"
                                                        name="port_name" placeholder="Port Name">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="terminal_name">Terminal Name</label>
                                                    <input type="text" class="form-control" id="terminal_name"
                                                        name="terminal_name" placeholder="Terminal Name">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="address">Address</label>
                                                    <input type="text" class="form-control" id="address"
                                                        name="address" placeholder="Address">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="zip">Zip</label>
                                                    <input type="text" class="form-control" id="zip"
                                                        name="zip" placeholder="Zip">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="state">State</label>
                                                    <input type="text" class="form-control" id="state"
                                                        name="state" placeholder="State">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="city">City</label>
                                                    <input type="text" class="form-control" id="city"
                                                        name="city" placeholder="City">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="telephone">Telephone</label>
                                                    <input type="text" class="form-control" id="telephone"
                                                        name="telephone" placeholder="Telephone">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="1"
                                                        id="make_sure" name="make_sure">
                                                    <label class="form-check-label" for="make_sure">
                                                        Make Sure Address
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="1"
                                                        id="accident_vehicle" name="accident_vehicle">
                                                    <label class="form-check-label" for="accident_vehicle">
                                                        NO INOP OR ACCIDENT VEHICLE
                                                    </label>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary addPort">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal" tabindex="-1" id="viewEmailType">
                        <div id="portmodal" class="modal fade effect-slide-in-bottom show"
                            style="padding-right: 19px; display: block;">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content tx-size-sm">
                                    <div class="modal-header pd-x-10">
                                        <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">View Email</h6>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body pd-20">


                                        <div class="row no-gutters" style="border: 5px solid;height: 80px;">
                                            @php
                                                $cpanelEmails = \App\CpanelEmail::where('status', 1)->get();
                                            @endphp

                                            @foreach ($cpanelEmails as $row)
                                                @php
                                                    $userIds = $row->users ? json_decode($row->users, true) : [];
                                                @endphp

                                                @if (in_array(Auth::id(), $userIds) ||
                                                        Auth::user()->userRole->name == 'Admin' ||
                                                        Auth::user()->userRole->name == 'Manager')
                                                    <div class="col-lg-4 bg-primary" style="text-align: center;">
                                                        <div class="pd-t-60 pd-b-40"
                                                            style="justify-content: center; margin-top: 10%;">
                                                            <h4>
                                                                <a target="_blank"
                                                                    href="{{ $row->url }}?user={{ $row->email }}&amp;pass={{ $row->password }}"
                                                                    class="tx-white color"
                                                                    style="color: white;">{{ $row->name }}</a>
                                                            </h4>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach

                                            {{-- <div class="col-lg-4 bg-primary" style="text-align: center;">
                                                <div class="pd-t-60 pd-b-40"
                                                    style=" justify-content: center; margin-top: 10%; ">
                                                    <h4>
                                                        <a target="_blank"
                                                            href="https://www.shipa1.com:2096/login/?user=support@shipa1.com&amp;pass=Support@5790.??"
                                                            class="tx-white color" style="color: white;">SUPPORT
                                                            EMAIL</a>
                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 bg-white" style="text-align: center;">
                                                <div class="pd-t-60 pd-b-40"
                                                    style=" justify-content: center; margin-top: 10%; ">
                                                    <h4>
                                                        <a target="_blank"
                                                            href="https://www.shipa1.com:2096/login/?user=info@shipa1.com&amp;pass=Info@5790/??"
                                                            class="tx-black color">INFO EMAIL</a>
                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 bg-primary" style="text-align: center;">
                                                <div class="pd-t-60 pd-b-40"
                                                    style=" justify-content: center; margin-top: 10%; ">
                                                    <h4>
                                                        <a target="_blank"
                                                            href="https://www.shipa1.com:2096/login/?user=no-reply@notifications.shipa1.com&amp;pass=n0reply@ship@1"
                                                            class="tx-white color" style="color: white">NO REPLY EMAIL</a>
                                                    </h4>
                                                </div>
                                            </div> --}}
                                        </div><!-- row -->


                                    </div><!-- modal-body -->
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                        </button>
                                    </div>
                                </div>
                            </div><!-- modal-dialog -->
                        </div>
                    </div>


                </div>
            </div>
            <!-- End app-content-->
            <div class="port-details-modal"></div>
            <div id="pcn-container" class="pcn-container"></div>

            <audio id="audio_success" autostart="false">
                <source src="{{asset('success_sound.mp3')}}" type="audio/ogg">
                <source src="{{asset('success_sound.mp3')}}" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio>
        </div>


        @if (request()->route()->getName() != 'logout_questions_answers.create')
            <footer class="footer">
                <div class="container">
                    <div class="row align-items-center flex-row-reverse">
                        <div class="col-md-12 col-sm-12 text-center">
                            Copyright © {{ date('Y') }} <a href="{{ url('/dashboard') }}">Hello Transport</a>.
                            All Rights Reserved ®.
                        </div>
                    </div>
                </div>
            </footer>
        @endif
        <script>
            function customfunc1(getparams) {

                // Get the input element containing the text to be copied
                var textToCopy = document.getElementById(getparams);

                // Select the text inside the input element
                textToCopy.select();

                // Attempt to copy the selected text to the clipboard
                document.execCommand("copy");

                // Deselect the text (optional)
                textToCopy.setSelectionRange(0, 99999);

            }
        </script>

        @if (Auth::check())
            <input type="hidden" id="time_user" value="{{ Auth::user()->ss_time }}" />
        @endif
    </div><!-- End Page -->

    @yield('modal')

    @include('partials.mainsite_pages.foot')

    @yield('extraScript')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(auth()->user()->role == 3 && auth()->user()->is_allow_price_check == 1)
    <script>
        (function ($) {
            var seen = {};                 // in-tab dedupe
            var POLL_MS = 30000;           // 15s
            var AUTOCLOSE_MS = 10000;      // 10s per card

            // Build Laravel route URL with id
            function dispatcherUrl(id) {
                return "{{ route('price_request.dispatcher', ['id' => '__ID__']) }}".replace('__ID__', id);
            }

            function createCard(item) {
                var $card = $('<div class="pcn-card" role="alert" data-clickable="true"></div>');
                // sync border countdown with auto-close time
                $card[0].style.setProperty('--pcn-duration', AUTOCLOSE_MS + 'ms');

                var $header = $('<div class="pcn-header"></div>');
                var $dot = $('<div class="pcn-dot"></div>');
                var $title = $('<div class="pcn-title">New Price Request</div>');
                var $close = $('<button type="button" class="pcn-close" aria-label="Close">&times;</button>');

                var msg = 'Order #' + item.order_id + ' requires pricing.';
                var $body = $('<div class="pcn-body"></div>').append(
                    $('<div></div>').text(msg),
                    $('<div class="pcn-meta"></div>').text(item.created_at || '')
                );

                // actions
                var $actions = $('<div class="pcn-actions"></div>');
                var $accept = $('<button type="button" class="pcn-btn pcn-btn-accept">Accept & Open</button>');
                var $decline = $('<button type="button" class="pcn-btn pcn-btn-decline">Decline</button>');
                $actions.append($accept, $decline);

                // precise rounded-rect perimeter with normalized length
                // pathLength=100 => stroke-dasharray/offset map 1:1 to "percent of perimeter"
                var gradId = 'pcn-grad-' + item.id + '-' + Date.now();
                var $border = $(
                    '<svg class="pcn-border" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">' +
                    '<defs>' +
                    '<linearGradient id="'+gradId+'" x1="0%" y1="0%" x2="100%" y2="0%">' +
                    '<stop offset="0%"   stop-color="#60a5fa"/>' +
                    '<stop offset="50%"  stop-color="#34d399"/>' +
                    '<stop offset="100%" stop-color="#f59e0b"/>' +
                    '</linearGradient>' +
                    '</defs>' +
                    /* rx/ry ~= CSS 12px radius; normalized to viewBox units; scales with card */
                    '<rect x="1" y="1" width="98" height="98" rx="12" ry="12" pathLength="100" class="pcn-border-path" stroke="url(#'+gradId+')" />' +
                    '</svg>'
                );

                var $progress = $('<div class="pcn-progress"><div></div></div>');

                $header.append($dot, $title, $close);
                $card.append($border, $header, $body, $actions, $progress);

                // full-card click = accept/open
                var url = dispatcherUrl(item.id);
                $card.on('click', function () { window.location.href = url; });

                // buttons (stop bubbling)
                $accept.on('click', function (e) {
                    e.stopPropagation();
                    window.location.href = url;   // accept → redirect
                });
                $decline.on('click', function (e) {
                    e.stopPropagation();
                    removeCard($card);
                });

                // close (X)
                $close.on('click', function (e) { e.stopPropagation(); removeCard($card); });

                // bottom bar countdown + auto close
                setTimeout(function () {
                    $progress.children().css('transition-duration', AUTOCLOSE_MS + 'ms');
                    $progress.children().css('transform', 'scaleX(0)');
                }, 20);
                var timer = setTimeout(function () { removeCard($card); }, AUTOCLOSE_MS);

                // pause auto-close on hover (border keeps counting; can pause too if you want)
                $card.on('mouseenter', function () {
                    clearTimeout(timer);
                    var style = window.getComputedStyle($progress.children()[0]);
                    var matrix = style.transform || style.webkitTransform;
                    var scaleX = 1;
                    if (matrix && matrix !== 'none') {
                        var m = matrix.match(/matrix\(([^)]+)\)/);
                        if (m && m[1]) {
                            var parts = m[1].split(',').map(function (v) { return parseFloat(v.trim()); });
                            if (!isNaN(parts[0])) scaleX = parts[0];
                        }
                    }
                    $card.data('remaining', AUTOCLOSE_MS * scaleX);
                }).on('mouseleave', function () {
                    var remaining = $card.data('remaining') || AUTOCLOSE_MS;
                    $progress.children().css('transition', 'transform linear ' + remaining + 'ms');
                    void $progress.children()[0].offsetWidth;
                    $progress.children().css('transform', 'scaleX(0)');
                    timer = setTimeout(function () { removeCard($card); }, remaining);
                });

                return $card;
            }

            function removeCard($card) {
                $card.css({ opacity: 0, transform: 'translateX(18px)' });
                setTimeout(function () { $card.remove(); }, 200);
            }

            function showNotification(item) {
                var $container = $('#pcn-container');
                var $card = createCard(item);
                $container.prepend($card); // newest on top
            }

            function poll() {
                $.ajax({
                    url: "{{ route('price_requests.pending') }}",
                    method: "GET",
                    dataType: "json",
                    cache: false
                }).done(function (list) {
                    // Ensure container exists
                    if (!document.getElementById('pcn-container')) {
                        $('body').append('<div id="pcn-container" class="pcn-container"></div>');
                    }

                    if (Array.isArray(list) && list.length > 0) {

                        list.forEach(function (item) {
                            showNotification(item);
                        });
                        playNotificationSound();
                    }
                }).fail(function () {
                    // silent
                });

            }

            $(function () {
                // initial + repeat
                poll();
                setInterval(poll, POLL_MS);
            });

        })(jQuery);
    </script>

    <script>
        function playNotificationSound() {
            var sound = document.getElementById("audio_success");
            sound.play();
        }
    </script>
    @endif

    <script>
        $('#chat_with_us').on('click', function () {
            const isMobile = window.innerWidth <= 768;

            const $chatWidget = $('#chat-widget');
            const $chatContainer = $('#chat-widget-container');

            if ($chatWidget.is(':visible')) {
                $chatWidget.slideUp(300, function () {
                    $chatContainer.addClass('inactivee');
                    $('#chat_with_us').addClass('activee');
                });

                if (isMobile) {
                    $chatContainer.add($chatWidget).css('z-index', 0);
                }
            } else {
                $chatContainer.removeClass('inactivee');

                if (isMobile) {
                    $chatContainer.add($chatWidget).css('z-index', 10000);
                }

                $chatWidget.css({ display: 'block' }).addClass('flip-bounce');
            }
        });


        $(window).on('load', function () {
            $('#chat-widget').attr('src', "{{ url('/chat-widget') }}?user_id={{ auth()->user()->id }}&user_name={{ urlencode(!empty(auth()->user()->slug) ? auth()->user()->slug : auth()->user()->name) }}");
            $('#chat-widget-container').addClass('inactivee');
            $('#chat_with_us').addClass('activee');
        });

        $(window).on('message', function(event) {
            var e = event.originalEvent;
            if(e.data.type == "iframeMessage") {
                $('#live_support_window').text(`Live New Message(${e.data.payload})`);
                if (e.data.payload > 0) {
                    $('#live_support_window').addClass('blink');

                    // Optional: Stop the animation after a few seconds
                    setTimeout(function () {
                        $('#live_support_window').removeClass('blink');
                    }, 3000); // Stop after 3 seconds
                }
            }
        });


    </script>

    @if (Auth::check())
        @if (Auth::user()->role < 6 || Auth::user()->role > 7)
            <script>
                // $("body").delegate(".richText-editor", "change keyup", function () {
                //     var notes_value = $('.richText-editor').html();
                //     var _token = $('#note_token').val();


                //     $.ajax({
                //         type: "POST",

                //         url: "/notes_save",

                //         data: {notes_value: notes_value, _token: _token},

                //         success: function (data) {

                //         },


                //     });


                // });

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });


                function updatePort(id) {
                    $.ajax({
                        url: "/edit-port",
                        type: "GET",
                        data: {
                            id: id
                        },
                        success: function(res) {
                            $(".port-details-modal").children().remove();
                            $(".port-details-modal").html(res);
                            $(`#updatePortDetail${id}`).modal('show');
                        }
                    })
                }

                function deletePort(id) {
                    $.ajax({
                        url: "/delete-port",
                        type: "GET",
                        data: {
                            id: id
                        },
                        success: function(res) {
                            window.location.reload();
                        }
                    })

                }

                function allReviews() {
                    $.ajax({
                        type: "GET",

                        url: "/get_notes",

                        success: function(data) {
                            $('.allReviews').html(data);

                            $(".deleteNote").click(function() {
                                var id = $(this).siblings("#noteId").val();
                                $.ajax({
                                    type: "GET",

                                    url: "/delete_notes",
                                    data: {
                                        id: id
                                    },
                                    success: function(data) {
                                        allReviews();
                                        // console.log(data);
                                    },
                                });
                            });
                            // console.log(data);
                        },
                    });
                }

                $(".viewNotes").click(function() {
                    allReviews();
                })

                $(".addContent").click(function() {
                    var notes_value = $('.richText-editor').html();
                    $.ajax({
                        type: "POST",

                        url: "{{ url('/notes_save') }}",

                        data: {
                            notes_value: notes_value
                        },

                        success: function(data) {
                            $('.richText-editor').html("");
                        },

                    });
                });



                $(document).ready(function() {
                    $('.addPort').click(function() {

                        var delivery_address = $("#delivery_address");
                        var port_name = $("#port_name");
                        var terminal_name = $("#terminal_name");
                        var make_sure = 0;
                        if ($("#make_sure").is(":checked")) {
                            make_sure = 1;
                        }
                        var accident_vehicle = 0;
                        if ($("#accident_vehicle").is(":checked")) {
                            accident_vehicle = 1;
                        }
                        var address = $("#address");
                        var zip = $("#zip");
                        var state = $("#state");
                        var city = $("#city");
                        var telephone = $("#telephone");
                        var twic_card = $("#twic_card").children("option:selected").val();

                        delivery_address.parent().children('.text-danger').remove();
                        port_name.parent().children('.text-danger').remove();
                        terminal_name.parent().children('.text-danger').remove();

                        if (delivery_address.children("option:selected").val() == '') {
                            delivery_address.parent().append(
                                '<span class="text-danger">This field is required!</span>');
                        }
                        if (port_name.val() == '') {
                            port_name.parent().append('<span class="text-danger">This field is required!</span>');
                        }
                        if (terminal_name.val() == '') {
                            terminal_name.parent().append(
                                '<span class="text-danger">This field is required!</span>');
                        }
                        if (delivery_address.children("option:selected").val() != '' && port_name.val() != '' &&
                            terminal_name.val() != '') {
                            $.ajax({
                                url: "{{ url('/add-port') }}",
                                type: "POST",
                                data: {
                                    delivery_address: delivery_address.children("option:selected").val(),
                                    port_name: port_name.val(),
                                    terminal_name: terminal_name.val(),
                                    make_sure: make_sure,
                                    accident_vehicle: accident_vehicle,
                                    address: address.val(),
                                    zip: zip.val(),
                                    state: state.val(),
                                    city: city.val(),
                                    telephone: telephone.val(),
                                    twic_card: twic_card
                                },
                                success: function(data) {
                                    window.location.reload();
                                }
                            });
                        }
                    })

                    function updateNewPort(id) {
                        var delivery_address = $("#delivery_address" + id);
                        var port_name = $("#port_name" + id);
                        var terminal_name = $("#terminal_name" + id);
                        var make_sure = 0;
                        if ($("#make_sure" + id).is(":checked")) {
                            make_sure = 1;
                        }
                        var accident_vehicle = 0;
                        if ($("#accident_vehicle" + id).is(":checked")) {
                            accident_vehicle = 1;
                        }
                        var address = $("#address" + id);
                        var zip = $("#zip" + id);
                        var state = $("#state" + id);
                        var city = $("#city" + id);
                        var telephone = $("#telephone" + id);
                        var twic_card = $("#twic_card" + id).children("option:selected").val();

                        delivery_address.parent().children('.text-danger').remove();
                        port_name.parent().children('.text-danger').remove();
                        terminal_name.parent().children('.text-danger').remove();

                        if (delivery_address.children("option:selected").val() == '') {
                            delivery_address.parent().append('<span class="text-danger">This field is required!</span>');
                        }
                        if (port_name.val() == '') {
                            port_name.parent().append('<span class="text-danger">This field is required!</span>');
                        }
                        if (terminal_name.val() == '') {
                            terminal_name.parent().append('<span class="text-danger">This field is required!</span>');
                        }
                        if (delivery_address.children("option:selected").val() != '' && port_name.val() != '' &&
                            terminal_name.val() != '') {
                            $.ajax({
                                url: "{{ url('/update-port') }}",
                                type: "POST",
                                data: {
                                    id: id,
                                    delivery_address: delivery_address.children("option:selected").val(),
                                    port_name: port_name.val(),
                                    terminal_name: terminal_name.val(),
                                    make_sure: make_sure,
                                    accident_vehicle: accident_vehicle,
                                    address: address.val(),
                                    zip: zip.val(),
                                    state: state.val(),
                                    city: city.val(),
                                    telephone: telephone.val(),
                                    twic_card: twic_card
                                },
                                success: function(data) {
                                    window.location.reload();
                                }
                            });
                        }
                    }
                });

                function customChatShow(uId, oid22) {
                    $.ajax({
                        url: '/show-chat-center',
                        type: 'POST',
                        data: {
                            uId: uId,
                            oid22: oid22
                        },
                        success: function(res) {
                            $('.chat-center').append(res);
                            $(".chat-user").animate({
                                scrollTop: $(document).height()
                            }, 1000);

                        }
                    });
                }

                function getAutoChat() {
                    $.ajax({
                        url: '/get-auto-chat',
                        type: 'POST',
                        success: function(res) {
                            // console.log(res);
                            $('.chat-center').html(res);
                            $(".chat-user").animate({
                                scrollTop: $(document).height()
                            }, 1000);
                        }
                    });
                }

                function getAutoChat2() {
                    $.ajax({
                        url: '/get-auto-chat2',
                        type: 'POST',
                        success: function(res) {
                            if (res.status_code === 400) {

                            } else {
                                // console.log(res);
                                $('.chat-center').append(res);
                                $(".chat-user").animate({
                                    scrollTop: $(document).height()
                                }, 1000);
                            }
                        }
                    });
                }

                function status(s) {
                    var status = '';
                    if (s == 0) {
                        status = ` <i class="fa fa-check-circle text-light" style="top:0;"></i>`;
                    } else if (s == 1) {
                        status = ` <i class="fa fa-check-circle text-warning" style="top:0;"></i>`;
                    } else {
                        status = ` <i class="fa fa-check-circle text-success" style="top:0;"></i>`;
                    }

                    return status;
                }

                function getAllConvo(v) {
                    var id = "{{ Auth::user()->id }}";
                    var data = '';
                    var date = '';
                    var flag = '';
                    if (v.date) {
                        date =
                            `<p class="bg-secondary text-light text-center" style="width: 50%;border-radius: 30px;padding: 6px 10px;margin: 6px auto 0;">${v.date}</p>`;
                    }
                    if (v.flag) {
                        if (v.flag.user_id == v.from_user_id) {
                            if (id == v.flag.user_id) {
                                var name = 'You';
                            } else {
                                var name = v.flag.user.slug ?? v.flag.user.name + ' ' + v.flag.user.last_name;
                            }
                            flag =
                                `<p class="text-danger text-center" style="width: 50%;margin: 6px auto;"><b>${name} got a <i class="fa fa-flag-o" aria-hidden="true"></i> Flag.</b></p>`
                        }
                    }
                    if (v.from_user_id == id) {
                        data += `${date}
            <div class="message-feed right media py-0">
                <div class="media-body">
                    <div class="mf-content" style="background:#705ec8;">
                        ${v.message}
                    </div>
                    <small class="mf-date text-dark"> ${v.message_time}
                    ${status(v.status)}
                    </small>
                </div>
            </div>${flag}`;
                    } else {
                        data += `${date}
            <div class="message-feed media py-0">
                <div class="media-body">
                    <div class="mf-content">
                        ${v.message}
                    </div>
                    <small class="mf-date text-dark"> ${v.message_time}
                    </small>
                </div>
            </div>${flag}`;
                    }

                    return data;
                }

                function getAllConvo2(v) {
                    var id = "{{ Auth::user()->id }}";
                    var data = '';
                    var date = '';
                    var flag = '';
                    if (v.date) {
                        date =
                            `<p class="bg-secondary text-light text-center" style="width: 50%;border-radius: 30px;padding: 6px 10px;margin: 6px auto 0;">${v.date}</p>`;
                    }
                    if (v.flag) {
                        if (v.flag.user_id == v.user_id) {
                            if (id == v.flag.user_id) {
                                var name = 'You';
                            } else {
                                var name = v.flag.user.slug ?? v.flag.user.name + ' ' + v.flag.user.last_name;
                            }
                            flag =
                                `<p class="text-danger text-center" style="width: 50%;margin: 6px auto;"><b>${name} got a <i class="fa fa-flag-o" aria-hidden="true"></i> Flag.</b></p>`
                        }
                    }
                    if (v.user_id == id) {
                        data += `${date}
            <div class="message-feed right media py-0">
                <div class="media-body">
                    <div class="mf-content" style="background:#705ec8;">
                        ${v.message}
                    </div>
                    <small class="mf-date text-dark"> ${v.message_time}
                    ${status(v.status)}
                    </small>
                </div>
            </div>${flag}`;
                    } else {
                        data += `${date}
            <div class="message-feed media py-0">
                <div class="media-body">
                    <h6>${ v.user.slug ?? v.user.name+' '+v.user.last_name }</h6>
                    <div class="mf-content">
                        ${v.message}
                    </div>
                    <small class="mf-date text-dark"> ${v.message_time}
                    </small>
                </div>
            </div>${flag}`;
                    }

                    return data;
                }

                function getAutoConvo() {
                    $.ajax({
                        url: '/get-auto-convo',
                        type: 'POST',
                        success: function(res) {
                            $.each(res.chat, function(key, value) {
                                $(`.user-id-${value.user_id}`).children().remove();
                                $.each(value.chat, function(k, v) {
                                    $(`.user-id-${value.user_id}`).append(`${getAllConvo(v)}`);
                                })
                            });
                            $.each(res.chat2, function(key, value) {
                                $(`.public-id-${value.public_id}`).children().remove();
                                $.each(value.chat, function(k, v) {
                                    $(`.public-id-${value.public_id}`).append(`${getAllConvo2(v)}`);
                                })
                            });
                        }
                    });
                }

                @if (Auth::user()->userRole->name == 'Order Taker' || Auth::user()->userRole->name == 'CSR' || Auth::user()->userRole->name == 'Seller Agent' || Auth::user()->userRole->name == 'Dispatcher' || Auth::user()->userRole->name == 'Admin')

                setInterval(function() {
                    getAutoChat2();
                    getAutoChat();
                    getAutoConvo();
                }, 1000 * 60 * 60);
                getAutoChat2();

                @endif

                if ({{ Auth::user()->freeze }} == 1) {
                    setInterval(function() {
                        $("body a").attr("href", "#");
                        $("body a").removeAttr("target");
                        $("body button").removeAttr("type");
                        $("body a").removeAttr("data-target");
                        $("body button").removeAttr("data-target");
                        $("body a").removeAttr("data-toggle");
                        $("body button").removeAttr("data-toggle");
                        $("body form").attr("action", "#");

                        $("body").children().css("opacity", 0.5);
                        $("body marquee").css("opacity", 1);
                    }, 5000);
                    $("body a").attr("href", "#");
                    $("body a").removeAttr("target");
                    $("body button").removeAttr("type");
                    $("body a").removeAttr("data-target");
                    $("body button").removeAttr("data-target");
                    $("body form").attr("action", "#");

                    $("body").children().css("opacity", 0.5);
                    $("body marquee").css("opacity", 1);
                }
            </script>
        @endif
        <div class="modalQNA"></div>
        <script type="text/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var role = "{{ Auth::user()->role }}";
            var rolename = "{{ Auth::user()->userRole->name }}";

            // if(role > 1)
            // {
            //     function take_ss()
            //     {
            //         var dataURL = {};
            //         if($("#time_user").val() >= 270)
            //         {
            //             html2canvas(document.body).then(canvas => {
            //                 dataURL = canvas.toDataURL();
            //                 // console.log(dataURL);
            //                  $.ajax({
            //                      url: "{{ url('/auto_screenshot') }}",
            //                      type: "POST",
            //                      data: {
            //                          image: dataURL
            //                      },
            //                      dataType: "html",
            //                      success: function(res) {
            //                      }
            //                  });
            //             });
            //             $.ajax({
            //                 url: "{{ url('/time_user') }}",
            //                 type: "GET",
            //                 dataType:"json",
            //                 success:function(res)
            //                 {
            //                     $("#time_user").val(res.time);
            //                 }
            //             });
            //         }
            //         else
            //         {
            //             $.ajax({
            //                 url: "{{ url('/time_user') }}",
            //                 type: "GET",
            //                 dataType:"json",
            //                 success:function(res)
            //                 {
            //                     $("#time_user").val(res.time);
            //                 }
            //             });
            //         }
            //     }

            //     setInterval(function(){
            //         take_ss();
            //     },1000 *30);

            //     setTimeout(function(){
            //         take_ss();
            //     },1000);
            // }

            if (rolename == 'Order Taker' || rolename == 'CSR' || rolename == 'Seller Agent' || rolename == 'Dispatcher' ||
                rolename == 'Admin') {
                function rating_count() {
                    $.ajax({
                        url: "{{ url('/rating_count') }}",
                        type: "GET",
                        success: function(res) {
                            $("#rating_count").children('.badge-danger').remove();
                            if (res > 0) {
                                $("#rating_count").append(
                                    `<span class="badge badge-danger rounded-circle d-flex justify-content-center align-items-center" style="position:absolute;height: 30px;width: 30px;top: -16px;right: -16px;font-size:12px;">${res > 99 ? '99+' : res}</span>`
                                );
                            }
                        }
                    })
                }
                rating_count();
                setInterval(function() {
                    rating_count();
                }, 1000 * 180);
            }

            var mousePos;
            var time;
            //   for development
            // var delayDetectionTime = 60000;
            // var delayDetectionTime = 300000000;

            //   for production
            var delayDetectionTime = 600000;

            document.onmousemove = handleMouseMove;

            // {{-- checks if authenticated user is on break or not --}}

            // {{-- checking ends --}}
            if ({{ Auth::user()->break_time }} == 0 && {{ Auth::user()->freeze }} == 0) {
                // setInterval(getMousePosition, delayDetectionTime);
            } else {
                var breaktime = $("#break").val();
                var breaktime2 = breaktime.split('.');
                var minutes = parseInt(breaktime2[0], 10);
                var seconds = parseInt(breaktime2[1], 10);

                if (seconds > 59) {
                    seconds = 0;
                } else if (seconds == '') {
                    seconds = 0;
                }

                $('#end_time').text(`${minutes}:${seconds}`);
                setInterval(function() {
                    var timer2 = $('#end_time').text();
                    var timer = timer2.split(':');
                    var minutes = parseInt(timer[0], 10);
                    var seconds = parseInt(timer[1], 10);

                    if (minutes >= 15 && seconds > 0) {
                        window.location.href = "{{ url('/end_time') }}";
                    }
                    if (seconds == 59) {
                        minutes = minutes + 1;
                        seconds = "00";
                    } else if (seconds < 9) {
                        seconds++;
                        seconds = "0" + seconds;
                    } else {
                        seconds++;
                    }
                    if (minutes < 9) {
                        minutes = "0" + minutes;
                    }
                    $('#end_time').text(`${minutes}:${seconds}`)
                }, 1000);
            }
            // setInterval repeats every X ms
            // check if object is empty
            function isObjEmpty(obj) {
                return Object.keys(obj).length === 0;
            }

            function handleMouseMove(event) {
                var dot, eventDoc, doc, body, pageX, pageY;

                event = event || window.event;
                // IE-ism

                // If pageX/Y aren't available and clientX/Y are,
                // calculate pageX/Y - logic taken from jQuery.
                // (This is to support old IE)
                if (event.pageX == null && event.clientX != null) {
                    eventDoc = (event.target && event.target.ownerDocument) || document;
                    doc = eventDoc.documentElement;
                    body = eventDoc.body;

                    event.pageX = event.clientX +
                        (doc && doc.scrollLeft || body && body.scrollLeft || 0) -
                        (doc && doc.clientLeft || body && body.clientLeft || 0);
                    event.pageY = event.clientY +
                        (doc && doc.scrollTop || body && body.scrollTop || 0) -
                        (doc && doc.clientTop || body && body.clientTop || 0);
                }

                mousePos = {
                    x: event.pageX,
                    y: event.pageY
                };
            }

            function ajaxPost(time) {
                $.ajax({
                    url: "{{ url('update_mouse') }}",
                    type: "GET",
                    data: {
                        time: time
                    },
                    dataType: "JSON",
                    success: function(res) {
                        if (res.success) {
                            $("body a").attr("href", "#");
                            $("body a").removeAttr("target");
                            $("body button").removeAttr("type");
                            $("body a").removeAttr("data-target");
                            $("body button").removeAttr("data-target");
                            $("body form").attr("action", "#");

                            $("body").children().css("opacity", 0.5);
                            $("body marquee").css("opacity", 1);
                            $("body").prepend(`
                        <marquee class="bg-danger text-light" style="position:fixed;top:0;width:100%;z-index:99999;height:50px;opacity:1;">
                            <h3 class="mt-3">
                            ${res.reason}
                            </h3>
                        </marquee>
                    `);
                        }
                    }
                })
            }

            function getMousePosition() {
                var pos = mousePos;
                if (!pos) {
                    // time =  delayDetectionTime + delayDetectionTime;
                    time = delayDetectionTime;
                    ajaxPost(time);
                } else {
                    if (isObjEmpty(pos)) {
                        // time = delayDetectionTime + delayDetectionTime;
                        time = delayDetectionTime;
                        ajaxPost(time);
                        console.log("hello");
                    }
                    delete pos.x;
                    delete pos.y;
                }
            }

            // if(rolename == 'Order Taker' || rolename == 'CSR' || rolename == 'Seller Agent' || rolename == 'Manager' || rolename == 'Admin')
            // {
            //     $.ajax({
            //         url:"{{ url('/ot_commission') }}",
            //         type:"GET",
            //         success:function(res)
            //         {
            //             $("#clear_cache").before(res);
            //         }
            //     })
            // }
        </script>
        {{-- <script>
            function playBeep2() {
                const audioContext = new(window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                oscillator.type = 'square';
                oscillator.frequency.setValueAtTime(600, audioContext.currentTime);
                gainNode.gain.setValueAtTime(1, audioContext.currentTime);
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                oscillator.start();
                setTimeout(() => {
                    oscillator.stop();
                    audioContext.close();
                }, 300);
            }

            function checkNewChat() {
                $.ajax({
                    url: "/checkNewChat",
                    type: "GET",
                    success: function(res) {
                        console.log('playBeeps', res.new_found);
                        if (res.new_found == 1) {
                            playBeep2();
                        }
                    }
                });
            }
            setInterval(checkNewChat, 10000);
        </script> --}}
    @endif
    <script>

        @php
            use Illuminate\Support\Str;
        @endphp

        @if (!Str::contains(request()->path(), 'sheet'))
            let idleTimer;
            let idleTimeout = 1200000;  // 20 mins

            function redirectToIdlePage() {
                console.log("🔄 Redirecting to idle page...");
                window.location.href = "{{ route('idle.page') }}"; // Laravel route
            }

            document.addEventListener("visibilitychange", function () {
                if (document.hidden) {
                    console.log("🔴 Tab is inactive. Starting idle timer...");
                    idleTimer = setTimeout(redirectToIdlePage, idleTimeout);
                } else {
                    console.log("✅ Tab is active again. Clearing idle timer...");
                    clearTimeout(idleTimer);
                }
            });
        @endif
    </script>

    <script>
        $(document).ready(function() {
            $(".app-sidebar__toggle").trigger('click');
            $('.app-sidebar ').hover(function() {
                $(".app-sidebar__toggle").trigger('click');
            });
        });

        $('#mobileSidebarToggle').on('click', function (e) {
            $(".app-sidebar__toggle").trigger('click');
        });
    </script>

</body>

</html>
