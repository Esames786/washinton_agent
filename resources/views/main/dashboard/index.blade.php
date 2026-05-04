@extends('layouts.mainsite')
@section('template_title')
    Dashboard
@endsection
@section('content')
    <?php
    if (!function_exists('get_user_namee')) {
        function get_user_namee($id)
        {
            $query = \App\User::where('id', $id)->first();
            return isset($query->name) ? $query->name : '';
        }
    }
    if (!function_exists('check_panel1')) {
        function check_panel1()
        {
            $setting = App\general_setting::first();
            $ptype = 1;
            $query = \App\user_setting::where('user_id', Auth::user()->id)->first();
            if (!empty($query)) {
                $ptype = $query['penal_type'];
            }
            return $ptype;
        }
    }
    if (!function_exists('get_pstatuss')) {
        function get_pstatuss($id)
        {

            $ret = "";
            if ($id == 0) {
                $ret = "NEW";
            } elseif ($id == 1) {
                $ret = "Interested";
            } elseif ($id == 2) {
                $ret = "FollowMore";
            } elseif ($id == 3) {
                $ret = "AskingLow";
            } elseif ($id == 4) {
                $ret = "NotInterested";
            } elseif ($id == 5) {
                $ret = "NoResponse";
            } elseif ($id == 6) {
                $ret = "TimeQuote";
            } elseif ($id == 7) {
                $ret = "PaymentMissing";
            } elseif ($id == 8) {
                $ret = "Booked";
            } elseif ($id == 9) {
                $ret = "Listed";
            } elseif ($id == 10) {
                $ret = "Dispatch";
            } elseif ($id == 11) {
                $ret = "Pickup";
            } elseif ($id == 12) {
                $ret = "Delivered";
            } elseif ($id == 13) {
                $ret = "Completed";
            } elseif ($id == 14) {
                $ret = "Cancel";
            } elseif ($id == 15) {
                $ret = "Deleted";
            } elseif ($id == 16) {
                $ret = "OwesMoney";
            } elseif ($id == 17) {
                $ret = "CarrierUpdate";
            }
            return $ret;

        }
    }
    if (!function_exists('status')) {
        function status($id, $car_type)
        {
            $setting = App\general_setting::first();

            $user = Auth::user();

            if (!empty($car_type)) {

                $query = \App\Autoorder::where('pstatus', $id)
                    ->where('paneltype', '=', check_panel1());
                if ($car_type == 1) {
                    $query = $query->where(function ($q) use ($car_type) {
                        $q->where('car_type', '=', $car_type)
                            ->orWhere('car_type', '=', NULL);
                    });
                } else {
                    $query = $query->where(function ($query) {
                        $query->where('car_type', '=', 2)
                            ->orWhere('car_type', '=', 3);
                    });
                }
                $query = $query->where('created_at', '>=', \Carbon\Carbon::today()->subDays($setting->no_days))
                    ->where(function ($q) use ($user) {
                        if ($user->userRole->name == 'Manager') {
                            if ($user->order_taker_quote == 1) {
                                $q->where('manager_id', $user->id)->orWhere('order_taker_id', $user->id);
                            }
                        } else if ($user->userRole->name == 'Dispatcher') {
                            if ($user->order_taker_quote == 1) {
                                $q->where('dispatcher_id', $user->id);
                            }
                        } else if ($user->userRole->name == 'Delivery Boy') {
                            if ($user->order_taker_quote == 1) {
                                $q->where('delivery_boy_id', $user->id);
                            }
                        } else if ($user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent') {
                            if ($user->order_taker_quote == 1) {
                                $q->where('order_taker_id', $user->id);
                            } else if ($user->order_taker_quote == 2) {
                                $q->whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$user->id]);
                            }
                        }
                    })
                    // ->get();
                    // dd($query->count(), $id, $car_type);
                    ->count();
            } else {
                $query = \App\Autoorder::where('pstatus', $id)
                    ->where('paneltype', '=', check_panel1())
                    ->where('created_at', '>=', \Carbon\Carbon::today()->subDays($setting->no_days))
                    ->where(function ($q) use ($user) {
                        if ($user->userRole->name == 'Manager') {
                            if ($user->order_taker_quote == 1) {
                                $q->where('manager_id', $user->id)->orWhere('order_taker_id', $user->id);
                            }
                        } else if ($user->userRole->name == 'Dispatcher') {
                            if ($user->order_taker_quote == 1) {
                                $q->where('dispatcher_id', $user->id);
                            }
                        } else if ($user->userRole->name == 'Delivery Boy') {
                            if ($user->order_taker_quote == 1) {
                                $q->where('delivery_boy_id', $user->id);
                            }
                        } else if ($user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent') {
                            if ($user->order_taker_quote == 1) {
                                $q->where('order_taker_id', $user->id);
                            } else if ($user->order_taker_quote == 2) {
                                $q->whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$user->id]);
                            }
                        }
                    })
                    ->count();
            }
            // }

            if (!empty($query)) {
                return $query;
            } else {
                return '0';
            }

        }
    }


    ?>

    <style>
        .btn-list > a > .fa , .fe{
            color: black !important;
            font-weight: bold;
            border-radius: 25px;
            font-size: 20px;
        }
       .btn_animation:hover {
            box-shadow: 5px 4px hsl(0deg 0% 0% / 29%);
        }
    </style>



    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">Hi! Welcome Back</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fe fe-home mr-2 fs-14"></i>Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page" style=" margin-top: 3px; "><a href="#">
                        Dashboard</a></li>
            </ol>
        </div>
        <!--<div class="page-rightheader">-->
        <!--    <div class="btn btn-list">-->
        <!--        {{--<a href="{{url('report_terminal')}}" class="btn btn-success btn_animation " data-toggle="tooltip" data-placement="top" title="Terminal Report"><i--}}-->
        <!--                    {{--class="fe fe-file "></i>--}}-->
        <!--        {{--</a>--}}-->
        <!--        {{--<a href="{{url('attendance_report')}}" class="btn btn-primary btn_animation " data-toggle="tooltip" data-placement="top" title="Attendance Report"><i--}}-->
        <!--                    {{--class="fe fe-clock mr-1 "></i>--}}-->
        <!--        {{--</a>--}}-->
        <!--        {{--<a href="{{url('total_activity')}}" class="btn btn-danger btn_animation " data-toggle="tooltip" data-placement="top" title="Total Activity"><i--}}-->
        <!--                    {{--class="fe fe-briefcase mr-1 "></i>--}}-->
        <!--        {{--</a>--}}-->

        <!--        {{--<a href="{{url('invoice_list')}}" class="btn btn-info btn_animation " data-toggle="tooltip" data-placement="top" title="Invoice"><i--}}-->
        <!--                    {{--class="fe fe-dollar-sign mr-1 "></i>--}}-->
        <!--        {{--</a>--}}-->

        <!--        {{--<a href="{{url('storage_list')}}" class="btn btn-primary btn_animation" data-toggle="tooltip" data-placement="top" title="Storage"><i--}}-->
        <!--                    {{--class="fe fe-box mr-1 btn_animation"></i>--}}-->
        <!--        {{--</a>--}}-->

        <!--        {{--<a href="{{url('carrier_list')}}" class="btn btn-success btn_animation" data-toggle="tooltip" data-placement="top"  title="Carriers"><i--}}-->
        <!--                    {{--class="fe fe-truck mr-1 "></i>--}}-->
        <!--        {{--</a>--}}-->

        <!--        {{--<a   href="{{url('customer_list')}}" class="btn btn-warning btn_animation" data-toggle="tooltip" data-placement="top" title="Customers"><i--}}-->
        <!--                    {{--class="fe fe-users mr-1 "></i>--}}-->
        <!--        {{--</a>--}}-->
        <!--        {{--<a   href="{{url('credit_card_list')}}" class=" btn btn-pink btn_animation btn_animation" data-toggle="tooltip" data-placement="top" title="Credit Card"><i--}}-->
        <!--                    {{--class="fe fe-credit-card mr-1 "></i>--}}-->
        <!--        {{--</a>--}}-->
        <!--        {{--<a   href="{{url('sales_report')}}" class="btn btn-secondary btn_animation" data-toggle="tooltip" data-placement="top" title="Sales Report"><i--}}-->
        <!--                    {{--class="fe fe-folder mr-1 "></i>--}}-->
        <!--        {{--</a>--}}-->
        <!--        {{--<a   href="{{url('general_setting_add')}}" class="btn btn-primary btn_animation" data-toggle="tooltip" data-placement="top" title="General Settings"><i--}}-->
        <!--                    {{--class="fe fe-settings mr-1 "></i>--}}-->
        <!--        {{--</a>--}}-->
        <!--        {{--<a  href="{{url('user_commission')}}" class="btn btn-info btn_animation" data-toggle="tooltip" data-placement="top" title="User Commission"><i--}}-->
        <!--                    {{--class="fa fa-money mr-1 "></i>--}}-->
        <!--        {{--</a>--}}-->
        <!--        {{--<a  href="{{url('first_bonus')}}" class="btn btn-orange btn_animation"  data-toggle="tooltip" data-placement="top" title="First Bonus">--}}-->
        <!--            {{--<strong style=" font-size: 18px; color: black; font-weight: 900; font-family: fantasy; ">1</strong>--}}-->
        <!--        {{--</a>--}}-->
        <!--        {{--<a  href="{{url('second_bonus')}}" class="btn btn-primary btn_animation" data-toggle="tooltip" data-placement="top" title="Second Bonus">--}}-->
        <!--            {{--<strong style=" font-size: 18px; color: black; font-weight: 900; font-family: fantasy; ">2</strong>--}}-->
        <!--        {{--</a>--}}-->

        <!--    </div>-->
        <!--</div>-->
    </div>
    <!--End Page header-->

    @if(Auth::check())
    @if(Auth::user()->role <> 5)
    <!-- Row-1 -->
    <div class="row">

        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a  href="{{url('new')}}" target="_blank">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1 "> New Car/Motor Quotes!</p>
                        <h2 class="mb-1 number-font">{{status(0,1)}}</h2>
                        <span class="ratio bg-warning"><i class="fa fa-car"></i></span>
                    </div>
                    <div id="spark1"></div>
                </div>
            </a>
        </div>

        <!--<div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">-->
        <!--    <a  href="{{url('new')}}" target="_blank">-->
        <!--        <div class="card overflow-hidden dash1-card border-0">-->
        <!--            <div class="card-body">-->
        <!--                <p class=" mb-1 ">New Motorcycle Quote!</p>-->
        <!--                <h2 class="mb-1 number-font">{{status(0,'motorcycle')}}</h2>-->
        <!--                <span class="ratio bg-danger"><i class="fa fa-motorcycle"></i></span>-->
        <!--            </div>-->
        <!--            <div id="spark3"></div>-->
        <!--        </div>-->
        <!--    </a>-->
        <!--</div>-->
        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="{{url('new')}}" target="_blank">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1">New Heavy/Fri Quote!</p>
                        <h2 class="mb-1 number-font">{{status(0,2)}}</h2>
                        <span class="ratio bg-orange"><i class="fa fa-truck"></i></span>
                    </div>
                    <div id="spark4"></div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="{{url('payment_missing')}}" target="_blank">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1 ">Payment Missing!</p>
                        <h2 class="mb-1 number-font">{{status(7,'')}}</h2>
                        <span class="ratio bg-info"><i class="fa fa-credit-card"></i></span>
                    </div>
                    <div id="spark1"></div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="{{url('listed')}}" target="_blank">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1">Listed!</p>
                        <h2 class="mb-1 number-font">{{status(9,'')}}</h2>
                        <span class="ratio bg-danger"><i class="fa fa-list"></i></span>
                    </div>
                    <div id="spark3"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="row">


        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="{{url('dispatch')}}" target="_blank">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1 "> Schedules!</p>
                        <h2 class="mb-1 number-font">{{status(10,'')}}</h2>
                        <span class="ratio bg-primary"><i class="fa fa-road"></i></span>
                    </div>
                    <div id="spark3"></div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="{{url('picked_up')}}" target="_blank" >
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1"> Picked Up!</p>
                        <h2 class="mb-1 number-font">{{status(11,'')}}</h2>
                        <span class="ratio bg-black"><i class="fa fa-level-up"></i></span>
                    </div>
                    <div id="spark4"></div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="{{url('delivered')}}" target="_blank">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1"> Delivered!</p>
                        <h2 class="mb-1 number-font">{{status(12,'')}}</h2>
                        <span class="ratio bg-secondary"><i class="fa fa-level-down"></i></span>
                    </div>
                    <div id="spark4"></div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-xm-12">
            <a href="{{url('completed')}}" target="_blank">
                <div class="card overflow-hidden dash1-card border-0">
                    <div class="card-body">
                        <p class=" mb-1"> Completed!</p>
                        <h2 class="mb-1 number-font">{{status(13,'')}}</h2>
                        <span class="ratio bg-success"><i class="fa fa-check-circle"></i></span>
                    </div>
                    <div id="spark4"></div>
                </div>
            </a>
        </div>
    </div>
    <!-- End Row-1 -->

    <!-- Row-2 -->
    <div class="row">
        @if(Auth::user()->role == 1)
        <div class="col-xl-8 col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">ORDER LIST</h3>
                    {{--<div class="card-options">--}}
                    {{--<div class="btn-group p-0">--}}
                    {{--<button class="btn btn-outline-light btn-sm" type="button">Week</button>--}}
                    {{--<button class="btn btn-light btn-sm" type="button">Month</button>--}}
                    {{--<button class="btn btn-outline-light btn-sm" type="button">Year</button>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                </div>
                <div class="card-body">
                    <div class="chartjs-wrapper-demo">
                        <div id="chart3" class="h-300 mh-300"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Activity</h3>
                    <div class="card-options">
                        <a href="{{url('dashboard')}}" class="option-dots" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false"><i
                                    class="fe fe-more-horizontal fs-20"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="latest-timeline scrollbar3" id="scrollbar3" style="overflow: scroll;!important;">
                        <ul class="timeline mb-0">
                            @foreach($data as $val2)
                                <li class="mt-0">
                                    <div class="d-flex">
                                        <span class="ml-auto text-muted fs-11">{{$val2->created_at}}</span></div>
                                    <p class="">
                                        <span class="text-info">{{ isset($val2->user->slug) ? $val2->user->slug : $val2->user->name.' '.$val2->user->last_name }}</span>
                                        change status to :
                                        <span href="" class="font-weight-semibold">{{get_pstatuss($val2->pstatus)}}</span>
                                        ORDER ID :
                                        <a href="/searchData?search={{$val2->orderId}}" class="font-weight-semibold">
                                            {{$val2->orderId}}
                                        </a>
                                    </p>
                                </li>
                            @endforeach

                            {{--<li class="mb-0">--}}
                            {{--<div class="d-flex"><span class="time-data">New Order Placed</span><span--}}
                            {{--class="ml-auto text-muted fs-11">11 May 2020</span></div>--}}
                            {{--<p class="text-muted fs-12"><span class="text-info">Steven Hart</span>--}}
                            {{--is--}}
                            {{--proces the order<a href="#" class="font-weight-semibold">--}}
                            {{--#987</a></p>--}}
                            {{--</li>--}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card">
                <form method="post" action="#" id="month_orders">
                    <div class="card-header">
                        <div class="card-title">Monthly Orders</div>
                        <div class="card-options">
                            <div class="btn-group p-0">
                                <select class="btn  btn-sm btn-info " style="padding: 3px;" name="panel_type2" id="panel_type2" >

                                    <option selected="selected" value="">All</option>
                                    <option value="1">Phone Qoute</option>
                                    <option value="2">Website Qoute</option>
                                </select>
                                <select class="btn  btn-sm btn-info " style="padding: 3px;" type="text" id="yearid" >
                                    <option value="{{$date1 =  date('Y')}}">{{$date2 = date('Y')}}</option>
                                    <option value="{{$date1 =  $date1 -1}}">{{$date2 =$date2 - 1}}</option>
                                    <option value="{{$date1 =  $date1 -1}}">{{$date2 =$date2 - 1}}</option>
                                    <option value="{{$date1 =  $date1 -1}}">{{$date2 =$date2 - 1}}</option>
                                    <option value="{{$date1 =  $date1 -1}}">{{$date2 =$date2 - 1}}</option>
                                    <option value="{{$date1 =  $date1 -1}}">{{$date2 =$date2 - 1}}</option>
                                </select>
                                <select class="btn  btn-sm btn-info " style="padding: 3px;" type="text" id="monthid" >
                                    <option value="{{date('m')}}" selected="selected">{{date('M')}}</option>
                                    <option value="01">Jan</option>
                                    <option value="02">Feb</option>
                                    <option value="03">Mar</option>
                                    <option value="04">Apr</option>
                                    <option value="05">May</option>
                                    <option value="06">Jun</option>
                                    <option value="07">Jul</option>
                                    <option value="08">Aug</option>
                                    <option value="09">Sep</option>
                                    <option value="10">Oct</option>
                                    <option value="11">Nov</option>
                                    <option value="12">Dec</option>

                                </select>
                                <input value="View" type="button" onclick="chart_view();" class="btn btn-submit btn-sm">

                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chartjs-wrapper-demo">
                            <div id="chart4" class="h-300 mh-300"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>




    <!-- End Row-2 -->

    <!-- Row-3 -->
    <!--End row-->
    @endif
    @endif


@endsection

@section('extraScript')
{{--    <script>--}}
{{--        $(document).ready(function () {--}}
{{--            $(".app-sidebar__toggle").trigger('click');--}}
{{--            $('.app-sidebar ').hover(function () {--}}
{{--                $(".app-sidebar__toggle").trigger('click');--}}
{{--            });--}}
{{--        });--}}

{{--    </script>--}}



@endsection
