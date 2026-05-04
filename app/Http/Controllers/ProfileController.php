<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\general_setting;
use App\user_setting;
use App\report;
use App\OrderFeedback;
use App\QaVerifyHistory;
use Carbon\Carbon;
use App\call_history;
use App\User;
use App\role;
use App\CommissionRange;
use App\AutoOrder;
use App\UserCommission;

class ProfileController extends Controller
{
    public function check_panel()
    {
        $setting = general_setting::first();
        $ptype = 1;
        $query = user_setting::where('user_id', Auth::user()->id)->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))->first();
        if (!empty($query)) {
            $ptype = $query['penal_type'];
        }
        return $ptype;
    }

    public function index(Request $request)
    {
        if (!$request->ajax()) {
            session()->forget('auth.password_confirmed_at');
        }

        $paneltype = $this->check_panel();

        $from = Carbon::now()->startOfMonth();
        $to = Carbon::now();
        if (isset($request->date_range)) {
            $date2 = (string) $request->date_range;
            $date = explode(' - ', $request->date_range);
            $from = date('Y-m-d 00:00:00', strtotime($date[0]));
            $to = date('Y-m-d 23:59:59', strtotime($date[1]));
        }
        $users = User::where('role', '>', 1)->where('deleted', 0)->orderBy('role', 'ASC')->select('id', 'role')->get();
        $uid = '';
        $role = '';
        if (isset($request->user)) {
            $uid = $request->user;
        } else {
            if ($paneltype == 1) {
                $phoneaccess = explode(',', Auth::user()->emp_access_phone);
            } elseif ($paneltype == 3) {
                $phoneaccess = explode(',', Auth::user()->emp_access_test);
            } elseif ($paneltype == 4) {
                $phoneaccess = explode(',', Auth::user()->panel_type_4);
            } elseif ($paneltype == 5) {
                $phoneaccess = explode(',', Auth::user()->panel_type_5);
            } elseif ($paneltype == 6) {
                $phoneaccess = explode(',', Auth::user()->panel_type_6);
            } else {
                $phoneaccess = explode(',', Auth::user()->emp_access_web);
            }

            if (in_array("86", $phoneaccess)) {
                $uid = '';
            } else {
                $uid = Auth::user()->id;
            }
        }
        $user = User::find($uid);
        if (isset($user->role)) {
            $roles = role::find($user->role);
            if (isset($roles->name)) {
                $role = $roles->name;
            }
        }
        $new = report::where('pstatus', 0)
            ->where(function ($q) use ($uid, $from, $to) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }
                $q->whereBetween('created_at', [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })->get()->unique('orderId')
            ->count();
        $int = report::where('pstatus', 1)
            ->where(function ($q) use ($uid, $from, $to) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }
                $q->whereBetween('created_at', [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })->get()->unique('orderId')
            ->count();
        $fm = report::where('pstatus', 2)
            ->where(function ($q) use ($uid, $from, $to) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }
                $q->whereBetween('created_at', [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })->get()->unique('orderId')
            ->count();
        $al = report::where('pstatus', 3)
            ->where(function ($q) use ($uid, $from, $to) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }
                $q->whereBetween('created_at', [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })->get()->unique('orderId')
            ->count();
        $not_int = report::where('pstatus', 4)
            ->where(function ($q) use ($uid, $from, $to) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }
                $q->whereBetween('created_at', [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })->get()->unique('orderId')
            ->count();
        $nr = report::where('pstatus', 5)
            ->where(function ($q) use ($uid, $from, $to) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }
                $q->whereBetween('created_at', [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })->get()->unique('orderId')
            ->count();
        $tq = report::where('pstatus', 6)
            ->where(function ($q) use ($uid, $from, $to) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }
                $q->whereBetween('created_at', [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })->get()->unique('orderId')
            ->count();
        $pm = report::where('pstatus', 7)
            ->where(function ($q) use ($uid, $from, $to) {
                if (!empty($uid)) {
                    $q->whereHas('order', function ($q2) use ($uid) {
                        $q2->where('u_id', $uid);
                    });
                }
                $q->whereBetween('created_at', [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })->get()->unique('orderId')
            ->count();
        $oa = report::where('pstatus', 18)
            ->where(function ($q) use ($uid, $from, $to) {
                if (!empty($uid)) {
                    $q->whereHas('order', function ($q2) use ($uid) {
                        $q2->where('u_id', $uid);
                    });
                }
                $q->whereBetween('created_at', [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })->get()->unique('orderId')
            ->count();
        $book = report::where('pstatus', 8)
            ->where(function ($q) use ($uid, $from, $to) {
                if (!empty($uid)) {
                    $q->whereHas('order', function ($q2) use ($uid) {
                        $q2->where('u_id', $uid);
                    });
                }
                $q->whereBetween('created_at', [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })->get()->unique('orderId')
            ->count();
        $list = report::where('pstatus', 9)
            ->where(function ($q) use ($uid, $from, $to, $role) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }
                $q->whereBetween('created_at', [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })->get()->unique('orderId')
            ->count();
        $dis = report::where('pstatus', 10)
            ->where(function ($q) use ($uid, $from, $to, $role) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }
                $q->whereBetween('created_at', [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })->get()->unique('orderId')
            ->count();
        $pick_app = report::where('pstatus', 30)
            ->where(function ($q) use ($uid, $from, $to, $role) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }
                $q->whereBetween('created_at', [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })->get()->unique('orderId')
            ->count();
        $pick = report::where('pstatus', 11)
            ->where(function ($q) use ($uid, $from, $to, $role) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }
                $q->whereBetween('created_at', [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })->get()->unique('orderId')
            ->count();
        $del_app = report::where('pstatus', 31)
            ->where(function ($q) use ($uid, $from, $to, $role) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }
                $q->whereBetween('created_at', [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })->get()->unique('orderId')
            ->count();
        $sfd = report::where('pstatus', 32)
            ->where(function ($q) use ($uid, $from, $to, $role) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }
                $q->whereBetween('created_at', [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })->get()->unique('orderId')
            ->count();
        $del = report::where('pstatus', 12)
            ->where(function ($q) use ($uid, $from, $to, $role) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }
                $q->whereBetween('created_at', [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })->get()->unique('orderId')
            ->count();
        $com = report::where('pstatus', 13)
            ->where(function ($q) use ($uid, $from, $to, $role) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }
                $q->whereBetween('created_at', [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })->get()->unique('orderId')
            ->count();
        $can = report::where('pstatus', 14)
            ->where(function ($q) use ($uid, $from, $to, $role) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }
                $q->whereBetween('created_at', [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })->get()->unique('orderId')
            ->count();
        $opcan = report::where('pstatus', 19)
            ->where(function ($q) use ($uid, $from, $to, $role) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }
                $q->whereBetween('created_at', [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })->get()->unique('orderId')
            ->count();
        $rating = OrderFeedback::where('user_id', $uid)
            ->whereBetween('created_at', [$from, $to])->count();
        $cancellation = call_history::where('pstatus', '14')
            ->whereRaw('FIND_IN_SET(?, mistaker_id)', [$uid])
            ->whereBetween('created_at', [$from, $to])->count();

        $commission = 0;
        $delivered_order = 0;
        $total_booked_order = 0;
        $average = 0;
        $revenue = 0;
        $commisionCancellation = 0;
        $per_review = 0;
        $private_pickup = 0;
        $total_dispatch = 0;
        $total_pickup = 0;
        $flat_commision = 0;
        $achieved_commision = 0;
        $dispatched_by = 0;
        $cancellation_deduction = 0;
        $review_target_achieved = 0;
        $review_less_than = '';
        if ($request->has('monthFilter')) {
            $currentMonthFormatted = $request->monthFilter;
        } else {
            $currentMonthFormatted = \Carbon\Carbon::now()->format('Y-m');
        }
        $currentMonth = \App\UserCommission::where('month', $currentMonthFormatted)
            ->where('user_id', $uid)
            ->orderBy('id', 'DESC')
            ->first();
        if ($role == 'Order Taker' || $role == 'CSR' || $role == 'Seller Agent' || $role == 'Manager') {
            $profit = $this->profit($uid, $role, $from, $to, $paneltype);
            if ($currentMonth && $currentMonth != null) {
                if ($currentMonth->average != 0) {
                    $avg = $currentMonth->average;
                } else {
                    $avg = $currentMonth->revenue / ($currentMonth->delivered_order ?: 1);
                }

                // dd('ok', $currentMonth->toArray(), $currentMonth->delivered_order ?: 1);
                $range = \App\CommissionRange::where('from_order', '<=', $currentMonth->delivered_order ?: 1)
                    ->where('to_order', '>=', $currentMonth->delivered_order ?: 1)
                    ->where('from_avg', '<=', $avg)
                    ->where('to_avg', '>=', $avg)
                    ->first();

                $delivered_order = $currentMonth->delivered_order;
                $total_booked_order = $currentMonth->total_booked_order;
                $average = $avg;
                $revenue = $currentMonth->revenue;
                $commisionCancellation = $currentMonth->cancellation;
                $review_less_than = $currentMonth->review_less_than;
                $cancellation_deduction = $currentMonth->cancellation_deduction;
                $review_target_achieved = $currentMonth->review_target_achieved;

                if ($user->private_OT != 0) {
                    $commission = $currentMonth->commission;
                } elseif ($range) {
                    $commission = $range->commission;
                }

                // if ($range) {
                //     $commission = $range->commission;
                // }
                // dd('ok', $commission, $range);
            }
        } else if ($role == 'Delivery Boy' || $role == 'Dispatcher') {
            if ($currentMonth) {
                $delivered_order = $currentMonth->delivered_order;
                $commission = $currentMonth->delivered_order * $user->commission;
                $per_review = $currentMonth->no_of_rating;
                $private_pickup = $currentMonth->no_of_pickup;


                $total_dispatch = $currentMonth->total_dispatch;
                $total_pickup = $currentMonth->total_pickup;
                $flat_commision = $currentMonth->flat_commision;
                $achieved_commision = $currentMonth->achieved_commision;
                $dispatched_by = $currentMonth->dispatched_by;
            }
            // dd('ok1', $commission, $currentMonth->delivered_order, $user->commission, $user->id);
            $profit = 0;
            // $profit = $this->profit2($uid, $role, $from, $to, $paneltype);
        } else {
            // dd('ok2');
            $profit = 0;
            // $profit = $this->profit($uid, $role, $from, $to, $paneltype);
        }
        $pstatus = '';
        if (isset($request->pstatus)) {
            if ($request->pstatus > 0) {
                $pstatus = $request->pstatus;
            }
        }

        $months = UserCommission::with('user')
            // ->whereHas('user', function ($q) {
            //     $q->where('role', 2);
            // })
            ->select('month')
            ->distinct()
            ->orderBy('month')
            ->pluck('month');

        // dd($commisionCancellation, $user->cancellation_amount);

        // dd($request->toArray(), 'from  ' . $from, 'to  ' . $to, 'user  ' . $user, 'profit  ' . $profit, 'new  ' . $new, 'int  ' . $int, 'fm  ' . $fm, 'al  ' . $al, 'not_int  ' . $not_int, 'nr  ' . $nr, 'tq  ' . $tq, 'pm  ' . $pm, 'book  ' . $book, 'oa  ' . $oa, 'list  ' . $list, 'dis  ' . $dis, 'pick  ' . $pick, 'pick_app  ' . $pick_app, 'del  ' . $del, 'del_app  ' . $del_app, 'sfd  ' . $sfd, 'com  ' . $com, 'can  ' . $can, 'opcan  ' . $opcan, 'rating  ' . $rating, 'cancellation  ' . $cancellation);

        if ($request->ajax()) {
            // echo "<pre>";
            // print_r($profit);exit();
            return view('main.profile.search', compact('user', 'profit', 'new', 'int', 'fm', 'al', 'not_int', 'nr', 'tq', 'pm', 'book', 'oa', 'list', 'dis', 'pick', 'pick_app', 'del', 'del_app', 'sfd', 'com', 'can', 'opcan', 'rating', 'cancellation', 'commission', 'delivered_order', 'total_booked_order', 'average', 'revenue', 'commisionCancellation', 'review_less_than', 'months', 'per_review', 'private_pickup', 'total_dispatch', 'total_pickup', 'flat_commision', 'achieved_commision', 'dispatched_by', 'cancellation_deduction', 'review_target_achieved'));
        }

        return view('main.profile.index', compact('from', 'to', 'users', 'user', 'profit', 'new', 'int', 'fm', 'al', 'not_int', 'nr', 'tq', 'pm', 'book', 'oa', 'list', 'dis', 'pick', 'pick_app', 'del', 'del_app', 'sfd', 'com', 'can', 'opcan', 'rating', 'cancellation', 'months'));
    }

    public function profit($uid, $role, $from, $to, $paneltype)
    {
        $cancellation = call_history::where('pstatus', '14')
            ->whereRaw('FIND_IN_SET(?, mistaker_id)', [$uid])
            ->whereBetween('created_at', [$from, $to])->select('created_at')->get();

        $arrcreated = [];
        if (isset($cancellation[0])) {
            foreach ($cancellation as $key => $val) {
                array_push($arrcreated, Carbon::parse($val->created_at)->format('Y-m-d'));
            }
        }

        // $order = report::with('order2.orderpayment2')->where('pstatus',13)
        // ->where(function ($q) use ($uid,$from,$to,$role){
        //     if(!empty($uid))
        //     {
        //         if($role == 'Order Taker' || $role == 'CSR' || $role == 'Seller Agent')
        //         {
        //             $q->whereHas('order',function ($q2) use ($uid){
        //                 $q2->where('u_id','=',$uid);
        //             });
        //         }
        //         else if($role == 'Manager')
        //         {
        //             $q->whereHas('order',function ($q2) use ($uid){
        //                 $q2->where('u_id','=',$uid)->orWhere('manager_id','=',$uid);
        //             });
        //         }
        //         else if($role == 'Dispatcher')
        //         {
        //             $q->whereHas('order',function ($q2) use ($uid){
        //                 $q2->where('dispatcher_id','=',$uid);
        //             });
        //         }
        //         else
        //         {
        //             $q->where('userId',$uid);
        //         }
        //     }
        //     $q->whereBetween('created_at',[$from,$to]);
        //     if(isset($arrcreated))
        //     {
        //         $q->whereNotIn('created_at',$arrcreated);
        //     }
        // })
        // ->whereHas('order',function($q) use ($paneltype){
        //     $q->where('paneltype',$paneltype)
        //     ->where('payment','>',0)->where('pay_carrier','>',0);
        // })->get()->unique('orderId');
        $order = AutoOrder::with('orderpayment2')->where('pstatus', 13)
            ->whereBetween('date_of_booked', [$from, $to])
            ->where(function ($q) use ($uid, $from, $to, $role) {
                if (!empty($uid)) {
                    if ($role == 'Order Taker' || $role == 'CSR' || $role == 'Seller Agent') {
                        $q->where('u_id', '=', $uid);
                    } else if ($role == 'Manager') {
                        $q->where('u_id', '=', $uid)->orWhere('manager_id', '=', $uid);
                    } else if ($role == 'Dispatcher') {
                        $q->where('dispatcher_id', '=', $uid);
                    } else {
                        $q->where('u_id', $uid);
                    }
                }
                if (isset($arrcreated)) {
                    $q->whereNotIn('date_of_booked', $arrcreated);
                }
            })
            ->where('paneltype', $paneltype)
            ->select('id', 'payment', 'pay_carrier')
            ->get();

        $total_order = $order->count();
        // return $total_order;
        // echo "<pre>";
        // print_r($order->toArray());
        // exit();
        $profit = 0;
        if (isset($order[0])) {
            foreach ($order as $key => $val) {
                if (isset($val->orderpayment2->profit)) {
                    $profit = $profit + $val->orderpayment2->profit;
                } else {
                    if (isset($val->payment) && isset($val->pay_carrier)) {
                        if ($val->payment >= $val->pay_carrier) {
                            $num = $val->payment - $val->pay_carrier;
                            $profit = $profit + $num;
                        }
                    }
                }
            }
        }

        $avg = 0;
        if ($total_order > 0 && $profit > 0) {
            $avg = round($profit / $total_order);
        }

        $data = CommissionRange::where('from_order', '<=', $total_order)
            ->where('to_order', '>=', $total_order)
            ->where('from_avg', '<=', $avg)
            ->where('to_avg', '>=', $avg)
            ->first();

        $commission = 0;

        if (isset($data->commission)) {
            $commission = $data->commission;
        }

        // dd($commission);
        // echo "<pre>";
        // print_r($total_order);
        // echo "<pre>";
        // print_r($avg);
        // echo "<pre>";
        // print_r($data);
        // exit();
        return $commission;
    }

    public function profit2($uid, $role, $from, $to, $paneltype)
    {
        $commission = 0;
        $pickup_order = 0;
        $deliver_order = 0;
        $pick = report::where('pstatus', 11)
            ->where(function ($q) use ($from, $to) {
                $q->whereBetween('created_at', [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })->get()->unique('orderId');

        foreach ($pick as $key => $val) {
            $pick_app = report::where('pstatus', 30)
                ->where('orderId', '=', $val->orderId)
                ->where('userId', '=', $uid)
                ->select('id')->first();

            if (isset($pick_app->id)) {
                $pickup_order = $pickup_order + 1;
                $commission = $commission + 50;
            }
        }

        $del = report::where('pstatus', 12)
            ->where(function ($q) use ($from, $to) {
                $q->whereBetween('created_at', [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })->get()->unique('orderId');

        foreach ($del as $key => $val) {
            $del_app = report::where('pstatus', 31)
                ->where('orderId', '=', $val->orderId)
                ->where('userId', '=', $uid)
                ->select('id')->first();

            if (isset($del_app->id)) {
                $deliver_order = $deliver_order + 1;
                $commission = $commission + 50;
            }
        }

        return array($pickup_order, $deliver_order, $commission);
    }

    public function show(Request $request)
    {
        $paneltype = $this->check_panel();

        $from = Carbon::now()->startOfMonth();
        $to = Carbon::now();
        if (isset($request->date_range)) {
            $date2 = (string) $request->date_range;
            $date = explode(' - ', $request->date_range);
            $from = date('Y-m-d 00:00:00', strtotime($date[0]));
            $to = date('Y-m-d 23:59:59', strtotime($date[1]));
        }
        $uid = '';
        if (isset($request->user)) {
            $uid = $request->user;
        } else {
            if (Auth::user()->userRole->name == 'Admin') {
                $uid = '';
            } else {
                $uid = Auth::user()->id;
            }
        }
        $pstatus = $request->pstatus;
        $data = '';
        $orderIds = [];
        if ($pstatus == 11) {
            $pick = report::where('pstatus', 11)
                ->where(function ($q) use ($from, $to) {
                    $q->whereBetween('created_at', [$from, $to]);
                })
                ->whereHas('order', function ($q) use ($paneltype) {
                    $q->where('paneltype', $paneltype);
                })->get()->unique('orderId');

            foreach ($pick as $key => $val) {
                array_push($orderIds, $val->orderId);
            }
            $data = report::with('order')->where('pstatus', 30)
                ->where('userId', '=', $uid)
                ->whereIn('orderId', $orderIds)
                ->orderBy('created_at', 'DESC')->paginate(20);
        } else if ($pstatus == 12) {
            $pick = report::where('pstatus', 12)
                ->where(function ($q) use ($from, $to) {
                    $q->whereBetween('created_at', [$from, $to]);
                })
                ->whereHas('order', function ($q) use ($paneltype) {
                    $q->where('paneltype', $paneltype);
                })->get()->unique('orderId');

            foreach ($pick as $key => $val) {
                array_push($orderIds, $val->orderId);
            }
            $data = report::with('order')->where('pstatus', 31)
                ->where('userId', '=', $uid)
                ->whereIn('orderId', $orderIds)
                ->orderBy('created_at', 'DESC')->paginate(20);
        }

        return view('main.profile.show', compact('data'));
    }
}
