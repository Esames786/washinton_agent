<?php

namespace App\Http\Controllers;

use App\AutoOrder;
use App\Mail\SendCodeMail;
use App\report;
use Illuminate\Http\Request;
use App\User;
use Vinkla\Hashids\Facades\Hashids;
use Auth;
use Carbon\Carbon;
use App\general_setting;
use App\user_setting;
use App\RequestShipment;
use App\Sheet;
use App\ExcelSheet;
use App\SpecialInstruction;
use App\CarrierApproaching;
use App\SheetDetails;
use App\ReportNew;
use App\QaVerifyHistory;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
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
        $date2 = (string) date('m/d/Y') . " - ";
        date('m/d/Y'); // 03/01/2022 - 03/31/2022
        $from = date('Y-m-01 00:00:00');
        $to = date('Y-m-t 23:59:59');
        if (isset($request->date)) {
            $date2 = (string) $request->date;
            $date = explode(' - ', $request->date);
            $from = date('Y-m-d 00:00:00', strtotime($date[0]));
            $to = date('Y-m-d 23:59:59', strtotime($date[1]));
        }
        $order_takers = User::where('status', 1)->with(
            [
                'order_book_and_pending' => function ($req) use ($from, $to) {
                    $req->whereBetween('created_at', array($from, $to));
                },
                'call_history' => function ($req) use ($from, $to) {
                    $req->whereBetween('created_at', array($from, $to));
                    $req->where('pstatus', '!=', 0);
                },
                'count_click' => function ($req) use ($from, $to) {
                    $req->whereBetween('created_at', array($from, $to));
                }
            ]
            // )->where('role', 2)->take(50)->get();
        )->where('role', 2)->paginate(50);

        // $dispatchers = User::where('status', 1)->where('role', 3)->take(50)->get();
        $dispatchers = User::where('status', 1)->where('role', 3)->paginate(50);

        // dd($order_takers->toArray(), $dispatchers->toArray());


        if (\Auth::check()) {
            return view('main.reports.index', compact('order_takers', 'dispatchers', 'date2', 'from', 'to'));
        } else {
            return redirect('/loginn/');
        }
    }

    public function create()
    {
        //
    }

    public function search(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());exit();
        $date2 = (string) date('m/d/Y') . " - ";
        date('m/d/Y'); // 03/01/2022 - 03/31/2022
        $from = date('Y-m-01 00:00:00');
        $to = date('Y-m-t 23:59:59');
        if (isset($request->date)) {
            $date2 = (string) $request->date;
            $date = explode(' - ', $request->date);
            $from = date('Y-m-d 00:00:00', strtotime($date[0]));
            $to = date('Y-m-d 23:59:59', strtotime($date[1]));
        }
        if ($request->user_role == 'Order_Taker') {
            $order_takers = User::with(
                [
                    'order_book_and_pending' => function ($req) use ($from, $to) {
                        $req->whereBetween('created_at', array($from, $to));
                    },
                    'call_history' => function ($req) use ($from, $to) {
                        $req->whereBetween('created_at', array($from, $to));
                        $req->where('pstatus', '!=', 0);
                    },
                    'count_click' => function ($req) use ($from, $to) {
                        $req->whereBetween('created_at', array($from, $to));
                    }
                ]
            )->where('role', 2)->where('deleted', 0)->paginate(50);
            return view('main.reports.ot', compact('order_takers', 'from', 'to'));
        } else {
            $dispatchers = User::where('role', 3)->paginate(50);
            return view('main.reports.dis', compact('dispatchers', 'from', 'to'));
        }
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
        $user = '';
        if (isset($request->user)) {
            $uid = $request->user;
            $user = User::with('userRole')->where('id', $uid)->first();
        }
        $sort = 'created_at';
        if (isset($request->search_by2)) {
            $sort = $request->search_by2;
        }
        $new = report::where('pstatus', 0)
            ->where(function ($q) use ($uid, $from, $to, $sort) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }
                $q->whereBetween($sort, [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })
            ->groupBy(['orderId', 'pstatus', 'userId'])
            ->get()
            ->count();

        $int = report::where('pstatus', 1)
            ->where(function ($q) use ($uid, $from, $to, $sort) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }

                $q->whereBetween($sort, [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })
            ->groupBy(['orderId', 'pstatus', 'userId'])
            ->get()
            ->count();

        $fm = report::where('pstatus', 2)
            ->where(function ($q) use ($uid, $from, $to, $sort) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }

                $q->whereBetween($sort, [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })
            ->groupBy(['orderId', 'pstatus', 'userId'])
            ->get()
            ->count();

        $al = report::where('pstatus', 3)
            ->where(function ($q) use ($uid, $from, $to, $sort) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }

                $q->whereBetween($sort, [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })
            ->groupBy(['orderId', 'pstatus', 'userId'])
            ->get()
            ->count();

        $not_int = report::where('pstatus', 4)
            ->where(function ($q) use ($uid, $from, $to, $sort) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }

                $q->whereBetween($sort, [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })
            ->groupBy(['orderId', 'pstatus', 'userId'])
            ->get()
            ->count();

        $nr = report::where('pstatus', 5)
            ->where(function ($q) use ($uid, $from, $to, $sort) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }

                $q->whereBetween($sort, [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })
            ->groupBy(['orderId', 'pstatus', 'userId'])
            ->get()
            ->count();

        $tq = report::where('pstatus', 6)
            ->where(function ($q) use ($uid, $from, $to, $sort) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }

                $q->whereBetween($sort, [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })
            ->groupBy(['orderId', 'pstatus', 'userId'])
            ->get()
            ->count();

        $pm = report::where('pstatus', 7)
            ->where(function ($q) use ($uid, $from, $to, $sort) {
                $q->whereBetween($sort, [$from, $to]);
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })
            ->groupBy(['orderId', 'pstatus', 'userId'])
            ->get()->count();

        $oa = report::where('pstatus', 18)
            ->where(function ($q) use ($uid, $from, $to, $sort) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }
                $q->whereBetween($sort, [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })
            ->groupBy(['orderId', 'pstatus', 'userId'])
            ->get()
            ->count();

        $book = report::where('pstatus', 8)
            ->where(function ($q) use ($uid, $from, $to, $sort) {

                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }
                $q->whereBetween($sort, [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })
            ->groupBy(['orderId', 'pstatus', 'userId'])
            ->get()
            ->count();

        $list = report::where('pstatus', 9)
            ->where(function ($q) use ($uid, $from, $to, $sort) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }

                $q->whereBetween($sort, [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })
            ->groupBy(['orderId', 'pstatus', 'userId'])
            ->get()
            ->count();

        $dis = report::where('pstatus', 10)
            ->where(function ($q) use ($uid, $from, $to, $sort) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }

                $q->whereBetween($sort, [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })
            ->groupBy(['orderId', 'pstatus', 'userId'])
            ->get()
            ->count();

        $dis_app = report::where('pstatus', 34)
            ->where(function ($q) use ($uid, $from, $to, $sort) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }

                $q->whereBetween($sort, [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })
            ->groupBy(['orderId', 'pstatus', 'userId'])
            ->get()
            ->count();

        $pick_app = report::where('pstatus', 30)
            ->where(function ($q) use ($uid, $from, $to, $sort) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }

                $q->whereBetween($sort, [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })
            ->groupBy(['orderId', 'pstatus', 'userId'])
            ->get()
            ->count();

        $pick = report::where('pstatus', 11)
            ->where(function ($q) use ($uid, $from, $to, $sort) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }

                $q->whereBetween($sort, [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })
            ->groupBy(['orderId', 'pstatus', 'userId'])
            ->get()
            ->count();

        $del_app = report::where('pstatus', 31)
            ->where(function ($q) use ($uid, $from, $to, $sort) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }

                $q->whereBetween($sort, [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })
            ->groupBy(['orderId', 'pstatus', 'userId'])
            ->get()
            ->count();

        $sfd = report::where('pstatus', 32)
            ->where(function ($q) use ($uid, $from, $to, $sort) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }

                $q->whereBetween($sort, [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })
            ->groupBy(['orderId', 'pstatus', 'userId'])
            ->get()
            ->count();

        $del = report::where('pstatus', 12)
            ->where(function ($q) use ($uid, $from, $to, $sort) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }

                $q->whereBetween($sort, [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })
            ->groupBy(['orderId', 'pstatus', 'userId'])
            ->get()
            ->count();

        $com = report::where('pstatus', 13)
            ->where(function ($q) use ($uid, $from, $to, $sort) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }

                $q->whereBetween($sort, [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })
            ->groupBy(['orderId', 'pstatus', 'userId'])
            ->get()
            ->count();

        $can = report::where('pstatus', 14)
            ->where(function ($q) use ($uid, $from, $to, $sort) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }

                $q->whereBetween($sort, [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })
            ->groupBy(['orderId', 'pstatus', 'userId'])
            ->get()
            ->count();

        $opcan = report::where('pstatus', 19)
            ->where(function ($q) use ($uid, $from, $to, $sort) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }

                $q->whereBetween($sort, [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })
            ->groupBy(['orderId', 'pstatus', 'userId'])
            ->get()
            ->count();

        $db = AutoOrder::where(function ($q2) {
            $q2->where('booking_confirm', 'may be')
                ->orWhere('booking_confirm', 'confirm');
        })->where('paneltype', $paneltype)->whereBetween($sort, [$from, $to])
            ->where(function ($q) use ($user, $uid) {
                if (isset($user->userRole->name)) {
                    if ($user->userRole->name == 'Order Taker' || $user->userRole->name == 'Seller Agent' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Manager') {
                        $q->where('u_id', $uid);
                    } else if ($user->userRole->name == 'Dispatcher') {
                        $q->where('dispatcher_id', $uid);
                    } else if ($user->userRole->name == 'Delivery Boy') {
                        $q->where('delivery_boy', $uid);
                    }
                }
            })
            ->count();

        $auction_storage = report::where('pstatus', '>=', 10)->where('pstatus', '<=', 14)
            ->whereHas('order', function ($q) {
                $q->where('already_auction_storage', 1)->orWhere('late_pickup_auction_storage', 1);
            })
            ->where(function ($q) use ($uid, $from, $to, $sort) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }

                $q->whereBetween($sort, [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })
            ->groupBy(['orderId', 'pstatus', 'userId'])->get()
            ->count();

        $move_to_storage = report::where('pstatus', '>=', 11)->where('pstatus', '<=', 12)
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('storage_id', '>', 0);
            })
            ->where(function ($q) use ($uid, $from, $to, $sort) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }
                $q->whereBetween($sort, [$from, $to]);
            })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })
            ->groupBy(['orderId', 'pstatus', 'userId'])->get()
            ->count();

        $auction_update = report::whereHas('order', function ($q) use ($paneltype) {
            $q->whereIn('pstatus', [7, 8, 9, 10, 18])
                ->where(function ($q2) {
                    $q2->whereIn('oterminal', [2, 3, 4])->orWhereIn('dterminal', [2, 3, 4]);
                });
        })
            ->whereHas('order', function ($q) use ($paneltype) {
                $q->where('paneltype', $paneltype);
            })
            ->where(function ($q) use ($uid, $from, $to, $sort) {
                if (!empty($uid)) {
                    $q->where('userId', $uid);
                }
                $q->whereBetween($sort, [$from, $to]);
            })
            ->groupBy(['orderId', 'pstatus', 'userId'])->get()
            ->count();

        $rl = RequestShipment::where(function ($q) use ($uid, $from, $to, $sort) {
            if (!empty($uid)) {
                $q->where('user_id', $uid);
            }

            $q->where('status', '=', 20);
            $q->whereBetween($sort, [$from, $to]);
        })
            ->count();

        $pr = RequestShipment::where(function ($q) use ($uid, $from, $to, $sort) {
            if (!empty($uid)) {
                $q->where('user_id', $uid);
            }

            $q->where('status', '=', 21);
            $q->whereBetween($sort, [$from, $to]);
        })
            ->count();

        $ai = RequestShipment::where(function ($q) use ($uid, $from, $to, $sort) {
            if (!empty($uid)) {
                $q->where('user_id', $uid);
            }

            $q->where('status', '=', 22);
            $q->whereBetween($sort, [$from, $to]);
        })
            ->count();

        $dp = RequestShipment::where(function ($q) use ($uid, $from, $to, $sort) {
            if (!empty($uid)) {
                $q->where('user_id', $uid);
            }

            $q->where('status', '=', 23);
            $q->whereBetween($sort, [$from, $to]);
        })
            ->count();

        $cu = RequestShipment::where(function ($q) use ($uid, $from, $to, $sort) {
            if (!empty($uid)) {
                $q->where('user_id', $uid);
            }

            $q->where('status', '=', 24);
            $q->whereBetween($sort, [$from, $to]);
        })
            ->count();

        $store = RequestShipment::where(function ($q) use ($uid, $from, $to, $sort) {
            if (!empty($uid)) {
                $q->where('user_id', $uid);
            }

            $q->where('status', '=', 25);
            $q->whereBetween($sort, [$from, $to]);
        })
            ->count();

        $app = RequestShipment::where(function ($q) use ($uid, $from, $to, $sort) {
            if (!empty($uid)) {
                $q->where('user_id', $uid);
            }

            $q->where('status', '=', 26);
            $q->whereBetween($sort, [$from, $to]);
        })
            ->count();

        $aur = RequestShipment::where(function ($q) use ($uid, $from, $to, $sort) {
            if (!empty($uid)) {
                $q->where('user_id', $uid);
            }

            $q->where('status', '=', 27);
            $q->whereBetween($sort, [$from, $to]);
        })
            ->count();

        $users = User::whereHas('userRole', function ($q) {
            $q->where('name', 'Order Taker')
                ->orWhere('name', 'Seller Agent')
                ->orWhere('name', 'CSR')
                ->orWhere('name', 'Dispatcher')
                ->orWhere('name', 'Manager');
        })
            ->where('deleted', 0)->orderBy('role', 'ASC')->get();
        $pstatus = '';
        if (isset($request->pstatus)) {
            if ($request->pstatus > 0) {
                $pstatus = $request->pstatus;
            }
        }

        // dd($paneltype, $request->toArray(), $new);

        if ($request->ajax()) {
            return view('main.reports.show2', compact('new', 'int', 'fm', 'al', 'not_int', 'nr', 'tq', 'pm', 'book', 'oa', 'list', 'dis', 'dis_app', 'pick', 'pick_app', 'del', 'del_app', 'sfd', 'com', 'can', 'opcan', 'pstatus', 'auction_storage', 'move_to_storage', 'auction_update', 'rl', 'pr', 'ai', 'dp', 'cu', 'store', 'app', 'aur', 'db'));
        }
        return view('main.reports.show', compact('from', 'to', 'new', 'int', 'fm', 'al', 'not_int', 'nr', 'tq', 'pm', 'book', 'oa', 'list', 'dis', 'dis_app', 'pick', 'pick_app', 'del', 'del_app', 'sfd', 'com', 'can', 'opcan', 'users', 'auction_storage', 'auction_update', 'move_to_storage', 'rl', 'pr', 'ai', 'dp', 'cu', 'store', 'app', 'aur', 'db'));
    }

    public function showNew(Request $request)
    {
        $paneltype = $this->check_panel();

        $from = Carbon::now()->startOfMonth();
        $to = Carbon::now();
        $fromFormatted = '';
        $toFormatted = '';
        if (isset($request->date_range)) {
            $fromFormatted = $from->format('m/d/Y');
            $toFormatted = $to->format('m/d/Y');

            $date2 = (string) $request->date_range;
            $date = explode(' - ', $request->date_range);
            $from = date('Y-m-d 00:00:00', strtotime($date[0]));
            $to = date('Y-m-d 23:59:59', strtotime($date[1]));
        }

        $uid = $request->user ?? '';
        $call_type = $request->call_type ?? '';
        $user = $uid ? User::with('userRole')->find($uid) : '';
        $sort = $request->search_by2 ?? 'created_at';
        $pstatus = $request->pstatus;

        $statusMap = [
            0 => ['user' => 'NEW_User', 'sort' => 'NEW_Created'],
            1 => ['user' => 'Interested_User', 'sort' => 'Interested_Created'],
            2 => ['user' => 'FollowMore_User', 'sort' => 'FollowMore_Created'],
            3 => ['user' => 'AskingLow_User', 'sort' => 'AskingLow_Created'],
            4 => ['user' => 'NotInterested_User', 'sort' => 'NotInterested_Created'],
            5 => ['user' => 'NoResponse_User', 'sort' => 'NoResponse_Created'],
            6 => ['user' => 'TimeQuote_User', 'sort' => 'TimeQuote_Created'],
            7 => ['user' => 'PaymentMissing_User', 'sort' => 'PaymentMissing_Created'],
            8 => ['user' => 'Booked_User', 'sort' => 'Booked_Created'],
            9 => ['user' => 'Listed_User', 'sort' => 'Listed_Created'],
            10 => ['user' => 'Schedule_User', 'sort' => 'Schedule_Created'],
            11 => ['user' => 'Pickup_User', 'sort' => 'Pickup_Created'],
            12 => ['user' => 'Delivered_User', 'sort' => 'Delivered_Created'],
            13 => ['user' => 'Completed_User', 'sort' => 'Completed_Created'],
            14 => ['user' => 'Cancel_User', 'sort' => 'Cancel_Created'],
            15 => ['user' => 'Deleted_User', 'sort' => 'Deleted_Created'],
            16 => ['user' => 'OwesMoney_User', 'sort' => 'OwesMoney_Created'],
            17 => ['user' => 'CarrierUpdate_User', 'sort' => 'CarrierUpdate_Created'],
            18 => ['user' => 'OnApproval_User', 'sort' => 'OnApproval_Created'],
            19 => ['user' => 'CancelOnapproval_User', 'sort' => 'CancelOnapproval_Created'],
        ];

        $pstatus = $request->pstatus;
        if (isset($statusMap[$pstatus])) {
            $user = $statusMap[$pstatus]['user'];
            $sort = $statusMap[$pstatus]['sort'];
        }

        $newCurrent = '';
        $intCurrent = '';
        $fmCurrent = '';
        $alCurrent = '';
        $not_intCurrent = '';
        $nrCurrent = '';
        $tqCurrent = '';
        $pmCurrent = '';
        $oaCurrent = '';
        $bookCurrent = '';
        $listCurrent = '';
        $disCurrent = '';
        $dis_appCurrent = '';
        $pick_appCurrent = '';
        $pickCurrent = '';
        $del_appCurrent = '';
        $sfdCurrent = '';
        $delCurrent = '';
        $comCurrent = '';
        $canCurrent = '';
        $opcanCurrent = '';

        $PaymentMissing = '';
        $OnApproval = '';

        // $counts = [];

        // foreach ($statusMap as $key => $status) {
        //     $createdColumn = $status['sort'];
        //     $userColumn = $status['user'];

        //     $counts[$createdColumn] = AutoOrder::whereNotNull($createdColumn)
        //         ->where(function ($query) use ($uid, $userColumn, $from, $to, $createdColumn, $sort) {
        //             if (!empty($uid)) {
        //                 $query->where($userColumn, $uid);
        //             }
        //             $query->whereBetween($sort === 'created_at' ? 'created_at' : $createdColumn, [$from, $to]);
        //         })
        //         ->where('paneltype', $paneltype)
        //         ->count();
        // }

        // // $query = AutoOrder::query();

        // $new = $counts['NEW_Created'];
        // $int = $counts['Interested_Created'];
        // $fm = $counts['FollowMore_Created'];
        // $al = $counts['AskingLow_Created'];
        // $not_int = $counts['NotInterested_Created'];
        // $nr = $counts['NoResponse_Created'];
        // $tq = $counts['TimeQuote_Created'];
        // $pm = $counts['PaymentMissing_Created'];
        // $oa = $counts['OnApproval_Created'];
        // $book = $counts['Booked_Created'];
        // $list = $counts['Listed_Created'];
        // $dis = $counts['Schedule_Created'];
        // $dis_app = 0;
        // $pick_app = 0;
        // $pick = $counts['Pickup_Created'];
        // $del_app = $counts['Delivered_Created'];
        // $sfd = 0;
        // $del = $counts['Deleted_Created'];
        // $com = $counts['Completed_Created'];
        // $can = $counts['Cancel_Created'];
        // $opcan = $counts['CancelOnapproval_Created'];

        $counts = [];
        $others = [];
        $current = [];

        if ($sort === 'created_at') {
            foreach ($statusMap as $key => $status) {
                $createdColumn = $status['sort'];
                $userColumn = $status['user'];

                $new_orders = AutoOrder::whereBetween('NEW_Created', [$from, $to])->pluck('id');

                $counts[$createdColumn] = AutoOrder::whereIn('id', $new_orders)
                    ->whereNotNull($createdColumn)
                    ->where(function ($query) use ($uid, $userColumn, $from, $call_type) {
                        if (!empty($uid)) {
                            $query->where($userColumn, $uid);
                        }
                        if (!empty($call_type)) {
                            $query->where('call_type', $call_type);
                        }
                    })
                    ->where('paneltype', $paneltype)
                    ->count();

                $others[$createdColumn] = AutoOrder::whereNotNull($createdColumn)
                    ->where(function ($query) use ($uid, $userColumn, $from, $to, $createdColumn, $call_type) {
                        if (!empty($uid)) {
                            $query->where($userColumn, $uid);
                        }
                        if (!empty($call_type)) {
                            $query->where('call_type', $call_type);
                        }
                        $query->whereBetween($createdColumn, [$from, $to]);
                    })
                    ->where('paneltype', $paneltype)
                    ->count();

                $forSFD = AutoOrder::where('approve_deliver', '=', 2)
                    ->where(function ($query) use ($uid, $userColumn, $from, $to, $call_type) {
                        if (!empty($uid)) {
                            $query->where($userColumn, $uid);
                        }
                        if (!empty($call_type)) {
                            $query->where('call_type', $call_type);
                        }
                        $query->whereBetween('Delivered_Created', [$from, $to]);
                    })
                    ->where('paneltype', $paneltype)
                    ->count();

                $ignoredOrderIds = ReportNew::whereIn('pstatus', [7, 18])
                    ->where('created_at', '<', $from)
                    ->pluck('orderId');

                $PaymentMissing = ReportNew::where('pstatus', 7)
                    ->whereNotIn('orderId', $ignoredOrderIds)
                    ->where(function ($q) use ($uid, $from, $to) {
                        if (!empty($uid)) {
                            $q->where('userId', $uid);
                        }
                        $q->whereBetween('created_at', [$from, $to]);
                    })
                    ->whereHas('order', function ($q) use ($paneltype, $call_type) {
                        $q->where('paneltype', $paneltype)
                            ->where('order_taker_id', '!=', 1);

                        if (!empty($call_type)) {
                            $q->where('call_type', $call_type);
                        }
                    })
                    ->groupBy('orderId')
                    ->get(['orderId', 'created_at']);

                $OnApproval = ReportNew::where(function ($q) use ($uid, $from, $to) {
                    if (!empty($uid)) {
                        $q->where('userId', $uid);
                    }
                    $q->whereBetween('created_at', [$from, $to]);
                    $q->where('pstatus', 18);
                })
                    ->whereNotIn('orderId', $ignoredOrderIds)
                    ->whereHas('order', function ($q) use ($paneltype, $call_type) {
                        $q->where('paneltype', $paneltype)
                            ->where('order_taker_id', '!=', 1);
                        if (!empty($call_type)) {
                            $q->where('call_type', $call_type);
                        }
                    })
                    ->groupBy('orderId')
                    ->get(['orderId', 'created_at']);

                $finalPaymentMissing = collect();
                $finalOnApproval = collect();

                foreach ($PaymentMissing as $paymentOrder) {
                    $onApprovalOrder = $OnApproval->firstWhere('orderId', $paymentOrder->orderId);

                    if (!$onApprovalOrder) {
                        $finalPaymentMissing->push($paymentOrder);
                    } else {
                        if ($paymentOrder->created_at > $onApprovalOrder->created_at) {
                            $finalPaymentMissing->push($paymentOrder);
                        } else {
                            $finalOnApproval->push($onApprovalOrder);
                        }

                        $OnApproval = $OnApproval->filter(function ($item) use ($paymentOrder) {
                            return $item->orderId !== $paymentOrder->orderId;
                        });
                    }
                }

                $finalOnApproval = $finalOnApproval->merge($OnApproval);

                $todayListed = AutoOrder::whereNotNull('Listed_Created')
                    ->where(function ($query) use ($uid, $from, $to, $call_type) {
                        if (!empty($uid)) {
                            $query->where('Listed_User', $uid);
                        }
                        if (!empty($call_type)) {
                            $query->where('call_type', $call_type);
                        }
                    })
                    ->whereBetween('Listed_Created', [now()->startOfDay(), now()])
                    ->where('paneltype', $paneltype)
                    ->count();

                $overallListed = AutoOrder::where('pstatus', 9)
                    ->where(function ($query) use ($uid, $from, $to, $call_type) {
                        if (!empty($uid)) {
                            $query->where('Listed_User', $uid);
                        }
                        if (!empty($call_type)) {
                            $query->where('call_type', $call_type);
                        }
                    })
                    ->where('paneltype', $paneltype)
                    ->count();

                $needToPickup = AutoOrder::where(function ($query) use ($uid, $paneltype, $call_type) {
                    $query->where('pstatus', 9)
                        ->where('paneltype', $paneltype)
                        ->where(function ($query) use ($uid, $call_type) {
                            if (!empty($uid)) {
                                $query->where('Listed_User', $uid);
                            }
                            if (!empty($call_type)) {
                                $query->where('call_type', $call_type);
                            }
                        })
                        ->orWhere(function ($query) use ($uid, $paneltype, $call_type) {
                            $query->where('pstatus', 18)
                                ->where('paneltype', $paneltype)
                                ->where(function ($query) use ($uid, $call_type) {
                                    if (!empty($uid)) {
                                        $query->where('OnApproval_User', $uid);
                                    }
                                    if (!empty($call_type)) {
                                        $query->where('call_type', $call_type);
                                    }
                                });
                        });
                })
                    ->count();

                $schedule = ReportNew::where('pstatus', 10)
                    ->where(function ($q) use ($uid, $from, $to) {
                        if (!empty($uid)) {
                            $q->where('userId', $uid);
                        }
                        $q->whereBetween('created_at', [$from, $to]);
                        $q->where('count', 1);
                    })
                    ->whereHas('order', function ($q) use ($paneltype, $call_type) {
                        $q->where('paneltype', $paneltype)
                            ->where('order_taker_id', '!=', 1);
                        if (!empty($call_type)) {
                            $q->where('call_type', $call_type);
                        }
                    })
                    ->has('carriers', 1)
                    ->get()->count();

                $scheduleToAnother = ReportNew::where('pstatus', 10)
                    ->where(function ($q) use ($uid, $from, $to) {
                        if (!empty($uid)) {
                            $q->where('userId', $uid);
                        }
                        $q->whereBetween('created_at', [$from, $to]);
                        $q->where('count', '!=', 1);
                    })
                    ->whereHas('order', function ($q) use ($paneltype, $call_type) {
                        $q->where('paneltype', $paneltype)
                            ->where('order_taker_id', '!=', 1);
                        if (!empty($call_type)) {
                            $q->where('call_type', $call_type);
                        }
                    })
                    ->has('carriers', '>', 1)
                    ->groupBy(['orderId', 'pstatus'])
                    ->get()->count();



                $pickup = ReportNew::where('pstatus', 11)
                    ->where(function ($q) use ($uid, $from, $to, $sort) {
                        if (!empty($uid)) {
                            $q->where('userId', $uid);
                        }
                        $q->where('count', 1);
                        $q->whereBetween('created_at', [$from, $to]);
                    })
                    ->whereHas('order', function ($q) use ($paneltype, $call_type) {
                        $q->where('paneltype', $paneltype)
                            ->where('order_taker_id', '!=', 1);
                        if (!empty($call_type)) {
                            $q->where('call_type', $call_type);
                        }
                    })
                    ->get()->count();

                // $pickupApproval = ReportNew::where('pstatus', 11)
                //     ->where(function ($q) use ($uid, $from, $to, $sort) {
                //         if (!empty($uid)) {
                //             $q->where('userId', $uid);
                //         }

                //         $q->whereBetween('created_at', [$from, $to]);
                //     })
                //     ->whereHas('order', function ($q) use ($paneltype) {
                //         $q->where('paneltype', $paneltype)
                //             ->where('order_taker_id', '!=', 1)
                //             ->where('approve_pickup', '=', 0);
                //     })
                //     ->get()
                //     ->count();

                $pickupApproval = report::where('pstatus', 30)
                    ->where(function ($q) use ($uid, $from, $to, $sort) {
                        if (!empty($uid)) {
                            $q->where('userId', $uid);
                        }
                        $q->whereBetween($sort, [$from, $to]);
                    })
                    ->whereHas('order', function ($q) use ($paneltype, $call_type) {
                        $q->where('paneltype', $paneltype);
                        if (!empty($call_type)) {
                            $q->where('call_type', $call_type);
                        }
                    })
                    ->groupBy(['orderId', 'pstatus'])
                    ->get()
                    ->count();


                // $delivered = ReportNew::where('pstatus', 12)
                //     ->where(function ($q) use ($uid, $from, $to, $sort) {
                //         if (!empty($uid)) {
                //             $q->where('userId', $uid);
                //         }

                //         $q->whereBetween('created_at', [$from, $to]);
                //     })
                //     ->whereHas('order', function ($q) use ($paneltype) {
                //         $q->where('paneltype', $paneltype)
                //             ->where('order_taker_id', '!=', 1)
                //             ->where('approve_deliver', 1);
                //     })
                //     ->get()
                //     ->count();

                $delivered = ReportNew::where('pstatus', 12)
                    ->where(function ($q) use ($uid, $from, $to, $sort) {
                        if (!empty($uid)) {
                            $q->where('userId', $uid);
                        }
                        $q->where('count', 1);
                        $q->whereBetween('created_at', [$from, $to]);
                    })
                    ->whereHas('order', function ($q) use ($paneltype, $call_type) {
                        $q->where('paneltype', $paneltype)
                            ->where('order_taker_id', '!=', 1);
                        if (!empty($call_type)) {
                            $q->where('call_type', $call_type);
                        }
                    })
                    ->get()->count();

                $deliveredApproval = report::where('pstatus', 31)
                    ->where(function ($q) use ($uid, $from, $to, $sort) {
                        if (!empty($uid)) {
                            $q->where('userId', $uid);
                        }

                        $q->whereBetween('created_at', [$from, $to]);
                    })
                    ->whereHas('order', function ($q) use ($paneltype, $call_type) {
                        $q->where('paneltype', $paneltype)
                            ->where('order_taker_id', '!=', 1);
                        if (!empty($call_type)) {
                            $q->where('call_type', $call_type);
                        }
                    })
                    ->groupBy(['orderId', 'pstatus'])
                    ->get()
                    ->count();

                // $scheduleForDelivery = ReportNew::where('pstatus', 12)
                //     ->where(function ($q) use ($uid, $from, $to, $sort) {
                //         if (!empty($uid)) {
                //             $q->where('userId', $uid);
                //         }

                //         $q->whereBetween('created_at', [$from, $to]);
                //     })
                //     ->whereHas('order', function ($q) use ($paneltype) {
                //         $q->where('paneltype', $paneltype)
                //             ->where('order_taker_id', '!=', 1)
                //             ->where('approve_deliver', 2);
                //     })
                //     ->get()
                //     ->count();

                $scheduleForDelivery = report::where('pstatus', 32)
                    ->where(function ($q) use ($uid, $from, $to, $sort) {
                        if (!empty($uid)) {
                            $q->where('userId', $uid);
                        }

                        $q->whereBetween($sort, [$from, $to]);
                    })
                    ->whereHas('order', function ($q) use ($paneltype, $call_type) {
                        $q->where('paneltype', $paneltype)
                            ->where('order_taker_id', '!=', 1);
                        if (!empty($call_type)) {
                            $q->where('call_type', $call_type);
                        }
                    })
                    ->groupBy(['orderId', 'pstatus'])
                    ->get()
                    ->count();

                $cancel = report::where('pstatus', 14)
                    ->where(function ($q) use ($uid, $from, $to, $sort) {
                        if (!empty($uid)) {
                            $q->where('userId', $uid);
                        }

                        $q->whereBetween($sort, [$from, $to]);
                    })
                    ->whereHas('order', function ($q) use ($paneltype, $call_type) {
                        $q->where('paneltype', $paneltype)
                            ->where('order_taker_id', '!=', 1);
                        if (!empty($call_type)) {
                            $q->where('call_type', $call_type);
                        }
                    })
                    ->groupBy(['orderId', 'pstatus'])
                    ->get()
                    ->count();

                $cancelOnApproval = report::where('pstatus', 19)
                    ->where(function ($q) use ($uid, $from, $to, $sort) {
                        if (!empty($uid)) {
                            $q->where('userId', $uid);
                        }

                        $q->whereBetween($sort, [$from, $to]);
                    })
                    ->whereHas('order', function ($q) use ($paneltype, $call_type) {
                        $q->where('paneltype', $paneltype)
                            ->where('order_taker_id', '!=', 1);
                        if (!empty($call_type)) {
                            $q->where('call_type', $call_type);
                        }
                    })
                    ->groupBy(['orderId', 'pstatus'])
                    ->get()
                    ->count();

                $current[$createdColumn] = AutoOrder::whereIn('id', $new_orders)
                    ->whereNotNull($createdColumn)
                    ->where(function ($query) use ($uid, $userColumn, $createdColumn, $call_type) {
                        if (!empty($uid)) {
                            $query->where($userColumn, $uid);
                        }
                        if ($createdColumn == 'NEW_Created') {
                            $query->where('pstatus', 0);
                        }
                        if ($createdColumn == 'Interested_Created') {
                            $query->where('pstatus', 1);
                        }
                        if ($createdColumn == 'FollowMore_Created') {
                            $query->where('pstatus', 2);
                        }
                        if ($createdColumn == 'AskingLow_Created') {
                            $query->where('pstatus', 3);
                        }
                        if ($createdColumn == 'NotInterested_Created') {
                            $query->where('pstatus', 4);
                        }
                        if ($createdColumn == 'NoResponse_Created') {
                            $query->where('pstatus', 5);
                        }
                        if ($createdColumn == 'TimeQuote_Created') {
                            $query->where('pstatus', 6);
                        }
                        if (!empty($call_type)) {
                            $query->where('call_type', $call_type);
                        }
                    })
                    ->where('paneltype', $paneltype)
                    ->count();
            }

            $newCurrent = $current['NEW_Created'];
            $intCurrent = $current['Interested_Created'];
            $fmCurrent = $current['FollowMore_Created'];
            $alCurrent = $current['AskingLow_Created'];
            $not_intCurrent = $current['NotInterested_Created'];
            $nrCurrent = $current['NoResponse_Created'];
            $tqCurrent = $current['TimeQuote_Created'];
        } else {
            foreach ($statusMap as $key => $status) {
                $createdColumn = $status['sort'];
                $userColumn = $status['user'];

                $counts[$createdColumn] = AutoOrder::whereNotNull($createdColumn)
                    ->where(function ($query) use ($uid, $userColumn, $from, $to, $createdColumn, $sort, $call_type) {
                        if (!empty($uid)) {
                            $query->where($userColumn, $uid);
                        }
                        if (!empty($call_type)) {
                            $query->where('call_type', $call_type);
                        }
                        $query->whereBetween($sort === 'created_at' ? 'created_at' : $createdColumn, [$from, $to]);
                    })
                    ->where('paneltype', $paneltype)
                    ->count();
            }
        }

        $statuses = [
            'NEW_Created',
            'Interested_Created',
            'FollowMore_Created',
            'AskingLow_Created',
            'NotInterested_Created',
            'NoResponse_Created',
            'TimeQuote_Created',
            'PaymentMissing_Created',
            'OnApproval_Created',
            'Booked_Created',
            'Listed_Created',
            'Schedule_Created',
            'Pickup_Created',
            'Delivered_Created',
            'Deleted_Created',
            'Completed_Created',
            'Cancel_Created',
            'CancelOnapproval_Created'
        ];

        foreach ($statuses as $status) {
            $counts[$status] = $counts[$status] ?? 0;
        }

        $new = $counts['NEW_Created'];
        $int = $counts['Interested_Created'];
        $fm = $counts['FollowMore_Created'];
        $al = $counts['AskingLow_Created'];
        $not_int = $counts['NotInterested_Created'];
        $nr = $counts['NoResponse_Created'];
        $tq = $counts['TimeQuote_Created'];

        // $pm = $others['PaymentMissing_Created'];
        $pm = $finalPaymentMissing->count();
        $oa = $finalOnApproval->count();
        $book = $others['Booked_Created'];
        $list = $others['Listed_Created'];
        $dis = $others['Schedule_Created'];
        $dis_app = 0;
        // $pick_app = $forPickup; //check
        $pick = $others['Pickup_Created'];
        $del_app = $others['Delivered_Created'];
        $sfd = $forSFD; //check
        $del = $others['Deleted_Created'];
        $com = $others['Completed_Created'];
        $can = $others['Cancel_Created'];
        $opcan = $others['CancelOnapproval_Created'];


        // dd($PaymentMissing, $OnApproval, $counts['TimeQuote_Created'], $counts['NEW_Created'], $pm, $oa);

        $reviews = SheetDetails::where(function ($q) use ($uid, $from, $to, $sort, $paneltype, $call_type) {
            if (!empty($uid)) {
                $q->whereHas('order', function ($q1) use ($uid) {
                    $q1->where('order_taker_id', $uid);
                });
            }

            $q->whereHas('order', function ($q1) use ($paneltype, $call_type) {
                $q1->where('paneltype', $paneltype);
                if (!empty($call_type)) {
                    $q1->where('call_type', $call_type);
                }
            });

            $q->where('review', 'Yes');
            $q->whereNotNull('website');
            $q->whereBetween($sort, [$from, $to]);
        })
            ->count();

        $new_customer = AutoOrder::where(function ($q) use ($uid, $from, $to, $sort, $paneltype, $call_type) {
            if (!empty($uid)) {
                $q->where('order_taker_id', $uid);
            }
            if (!empty($call_type)) {
                $q->where('call_type', $call_type);
            }

            $q->where('paneltype', $paneltype);
            $q->whereBetween($sort, [$from, $to]);
            $q->whereNotNull('how_did_you_find_us');
        })
            ->count();

        $need_to_book = AutoOrder::where(function ($q) use ($uid, $from, $to, $sort, $paneltype, $call_type) {
            if (!empty($uid)) {
                $q->where('order_taker_id', $uid);
            }
            if (!empty($call_type)) {
                $q->where('call_type', $call_type);
            }

            $q->where('paneltype', $paneltype);
            $q->whereBetween('NotInterested_Created', [$from, $to]);
            $q->whereBetween('PaymentMissing_Created', [$from, $to]);
            $q->whereBetween('Booked_Created', [$from, $to]);
            $q->whereBetween('OnApproval_Created', [$from, $to]);
            $q->whereNotNull('how_did_you_find_us');
        })
            ->count();

        $qa_positive = QaVerifyHistory::whereBetween('created_at', [$from, $to])
            ->where('negative', 0)
            ->whereHas('order', function ($q) use ($paneltype, $uid) {
                $q->where('paneltype', $paneltype)
                    ->where('order_taker_id', '!=', 1);
                if (!empty($uid)) {
                    $q->where('order_taker_id', $uid);
                }
            })
            ->count();
        $qa_negative = QaVerifyHistory::whereBetween('created_at', [$from, $to])
            ->where('negative', 1)
            ->whereHas('order', function ($q) use ($paneltype, $uid) {
                $q->where('paneltype', $paneltype)
                    ->where('order_taker_id', '!=', 1);
                if (!empty($uid)) {
                    $q->where('order_taker_id', $uid);
                }
            })
            ->count();

        $users = User::whereHas('userRole', function ($q) {
            $q->where('name', 'Order Taker')
                ->orWhere('name', 'Seller Agent')
                ->orWhere('name', 'CSR')
                ->orWhere('name', 'Dispatcher')
                ->orWhere('name', 'Manager');
        })
            ->where('deleted', 0)->orderBy('role', 'ASC')->get();
        $pstatus = '';
        if (isset($request->pstatus)) {
            if ($request->pstatus > 0) {
                $pstatus = $request->pstatus;
            }
        }

        if ($request->ajax()) {
            return view('main.reports.show2New', compact('from', 'to', 'users', 'new', 'int', 'fm', 'al', 'not_int', 'nr', 'tq', 'pm', 'book', 'oa', 'list', 'dis', 'dis_app', 'pick', 'del', 'del_app', 'sfd', 'com', 'can', 'opcan', 'pstatus', 'reviews', 'new_customer', 'newCurrent', 'intCurrent', 'fmCurrent', 'alCurrent', 'not_intCurrent', 'nrCurrent', 'tqCurrent', 'pmCurrent', 'oaCurrent', 'bookCurrent', 'listCurrent', 'disCurrent', 'dis_appCurrent', 'pick_appCurrent', 'pickCurrent', 'del_appCurrent', 'sfdCurrent', 'delCurrent', 'comCurrent', 'canCurrent', 'opcanCurrent', 'todayListed', 'overallListed', 'needToPickup', 'schedule', 'scheduleToAnother', 'pickup', 'delivered', 'pickupApproval', 'deliveredApproval', 'scheduleForDelivery', 'cancel', 'cancelOnApproval', 'fromFormatted', 'toFormatted', 'qa_positive', 'qa_negative'));
        }
        return view('main.reports.showNew', compact('from', 'to', 'users', 'new', 'int', 'fm', 'al', 'not_int', 'nr', 'tq', 'pm', 'book', 'oa', 'list', 'dis', 'dis_app', 'pick', 'del', 'del_app', 'sfd', 'com', 'can', 'opcan', 'pstatus', 'reviews', 'new_customer', 'newCurrent', 'intCurrent', 'fmCurrent', 'alCurrent', 'not_intCurrent', 'nrCurrent', 'tqCurrent', 'pmCurrent', 'oaCurrent', 'bookCurrent', 'listCurrent', 'disCurrent', 'dis_appCurrent', 'pick_appCurrent', 'pickCurrent', 'del_appCurrent', 'sfdCurrent', 'delCurrent', 'comCurrent', 'canCurrent', 'opcanCurrent', 'todayListed', 'overallListed', 'needToPickup', 'schedule', 'scheduleToAnother', 'pickup', 'delivered', 'pickupApproval', 'deliveredApproval', 'scheduleForDelivery', 'cancel', 'cancelOnApproval', 'fromFormatted', 'toFormatted', 'qa_positive', 'qa_negative'));
    }

    public function show2New(Request $request)
    {
        // dd($request->toArray());
        $paneltype = $this->check_panel();

        $sort = $request->search_by2 ?? 'created_at';

        $call_type = $request->call_type ?? '';

        $statusMap = [
            0 => ['user' => 'NEW_User', 'sort' => 'NEW_Created'],
            1 => ['user' => 'Interested_User', 'sort' => 'Interested_Created'],
            2 => ['user' => 'FollowMore_User', 'sort' => 'FollowMore_Created'],
            3 => ['user' => 'AskingLow_User', 'sort' => 'AskingLow_Created'],
            4 => ['user' => 'NotInterested_User', 'sort' => 'NotInterested_Created'],
            5 => ['user' => 'NoResponse_User', 'sort' => 'NoResponse_Created'],
            6 => ['user' => 'TimeQuote_User', 'sort' => 'TimeQuote_Created'],
            7 => ['user' => 'PaymentMissing_User', 'sort' => 'PaymentMissing_Created'],
            8 => ['user' => 'Booked_User', 'sort' => 'Booked_Created'],
            9 => ['user' => 'Listed_User', 'sort' => 'Listed_Created'],
            10 => ['user' => 'Schedule_User', 'sort' => 'Schedule_Created'],
            11 => ['user' => 'Pickup_User', 'sort' => 'Pickup_Created'],
            12 => ['user' => 'Delivered_User', 'sort' => 'Delivered_Created'],
            13 => ['user' => 'Completed_User', 'sort' => 'Completed_Created'],
            14 => ['user' => 'Cancel_User', 'sort' => 'Cancel_Created'],
            15 => ['user' => 'Deleted_User', 'sort' => 'Deleted_Created'],
            16 => ['user' => 'OwesMoney_User', 'sort' => 'OwesMoney_Created'],
            17 => ['user' => 'CarrierUpdate_User', 'sort' => 'CarrierUpdate_Created'],
            18 => ['user' => 'OnApproval_User', 'sort' => 'OnApproval_Created'],
            19 => ['user' => 'CancelOnapproval_User', 'sort' => 'CancelOnapproval_Created'],
        ];

        $pstatus = $request->pstatus;
        if (isset($statusMap[$pstatus])) {
            $user = $statusMap[$pstatus]['user'];
            $sort = $statusMap[$pstatus]['sort'];
        }

        $from = Carbon::now()->startOfMonth();
        $to = Carbon::now();
        if (isset($request->date_rangeNew)) {
            $date2 = (string) $request->date_rangeNew;
            $date = explode(' - ', $request->date_rangeNew);
            $from = date('Y-m-d 00:00:00', strtotime($date[0]));
            $to = date('Y-m-d 23:59:59', strtotime($date[1]));
        }

        $searchBy = 'id';
        if (isset($request->search_by)) {
            $searchBy = $request->search_by;
        }
        $search = '';
        if (isset($request->search)) {
            $search = $request->search;
        }
        if (isset($request->source)) {
            $source = $request->source;
        }

        $uid = '';
        $user = '';
        if (isset($request->user)) {
            $uid = $request->user;
            $user = User::with('userRole')->where('id', $uid)->first();
        }
        $auc_storage = 0;
        if (isset($request->auc_storage)) {
            if ($request->auc_storage > 0) {
                $auc_storage = $request->auc_storage;
            }
        }

        if ($pstatus == 'reviews') {

            $data = SheetDetails::with('order')->where(function ($q) use ($uid, $from, $to, $sort, $paneltype, $call_type) {
                if (!empty($uid)) {
                    $q->whereHas('order', function ($q1) use ($uid) {
                        $q1->where('order_taker_id', $uid);
                    });
                }

                $q->whereHas('order', function ($q1) use ($paneltype, $call_type) {
                    $q1->where('paneltype', $paneltype);
                    if (!empty($call_type)) {
                        $q1->where('call_type', $call_type);
                    }
                });

                $q->whereBetween($sort, [$from, $to]);
                $q->where('review', 'Yes');
                $q->whereNotNull('website');
            });
            // ->get();

            // dd($data->toArray());
            $data = $data->paginate(50);

            return view('main.reports.showReportsNew', compact('data', 'pstatus'));

        } elseif ($pstatus == 'new_customer') {

            $data = AutoOrder::where(function ($q) use ($uid, $from, $to, $sort, $paneltype, $call_type, $searchBy, $search) {
                if (!empty($uid)) {
                    $q->where('order_taker_id', $uid);
                }
                if (!empty($call_type)) {
                    $q->where('call_type', $call_type);
                }
                if (!empty($searchBy) && !empty($search)) {
                    $q->where($searchBy, 'Like', '%' . $search . '%');
                }

                $q->where('paneltype', $paneltype);
                $q->whereBetween($sort, [$from, $to]);
                $q->whereNotNull('how_did_you_find_us');
            });
            // ->get();

            // dd($data->toArray());
            $data = $data->paginate(50);

            return view('main.reports.show4', compact('data', 'pstatus'));

        } elseif ($pstatus == 'qa_positive') {

            $id = $uid;
            $total_order = 0;
            $data = QaVerifyHistory::whereBetween('created_at', [$from, $to])
                ->where('negative', 0)
                ->orderBy('created_at', 'DESC')
                ->whereHas('order', function ($q) use ($paneltype, $uid) {
                    $q->where('paneltype', $paneltype)
                        ->where('order_taker_id', '!=', 1);
                    if (!empty($uid)) {
                        $q->where('order_taker_id', $uid);
                    }
                });

            $data = $data->paginate(50);

            return view('main.reports.qaReviews', compact('data', 'pstatus', 'from', 'to', 'total_order', 'id'));

        } elseif ($pstatus == 'qa_negative') {

            $id = $uid;
            $total_order = 0;
            $data = QaVerifyHistory::whereBetween('created_at', [$from, $to])
                ->where('negative', 1)
                ->orderBy('created_at', 'DESC')
                ->whereHas('order', function ($q) use ($paneltype, $uid) {
                    $q->where('paneltype', $paneltype)
                        ->where('order_taker_id', '!=', 1);
                    if (!empty($uid)) {
                        $q->where('order_taker_id', $uid);
                    }
                });

            $data = $data->paginate(50);

            return view('main.reports.qaReviews', compact('data', 'pstatus', 'from', 'to', 'total_order', 'id'));

        } elseif ($pstatus == 7) {
            $ignoredOrderIds = ReportNew::whereIn('pstatus', [7, 18])
                ->where('created_at', '<', $from)
                ->pluck('orderId');

            $PaymentMissing = ReportNew::where('pstatus', 7)
                ->whereNotIn('orderId', $ignoredOrderIds)
                ->where(function ($q) use ($uid, $from, $to) {
                    if (!empty($uid)) {
                        $q->where('userId', $uid);
                    }
                    $q->whereBetween('created_at', [$from, $to]);
                })
                ->whereHas('order', function ($q) use ($paneltype, $call_type, $searchBy, $search) {
                    $q->where('paneltype', $paneltype)
                        ->where('order_taker_id', '!=', 1);
                    if (!empty($call_type)) {
                        $q->where('call_type', $call_type);
                    }
                    if (!empty($searchBy) && !empty($search)) {
                        $q->where($searchBy, 'Like', '%' . $search . '%');
                    }
                })
                ->get(['orderId', 'created_at']);

            $OnApproval = ReportNew::whereNotIn('orderId', $ignoredOrderIds)
                ->where(function ($q) use ($uid, $from, $to) {
                    if (!empty($uid)) {
                        $q->where('userId', $uid);
                    }
                    $q->whereBetween('created_at', [$from, $to]);
                    $q->where('pstatus', 18);
                })
                ->whereHas('order', function ($q) use ($paneltype, $call_type, $searchBy, $search) {
                    $q->where('paneltype', $paneltype)
                        ->where('order_taker_id', '!=', 1);
                    if (!empty($call_type)) {
                        $q->where('call_type', $call_type);
                    }
                    if (!empty($searchBy) && !empty($search)) {
                        $q->where($searchBy, 'Like', '%' . $search . '%');
                    }
                })
                ->get(['orderId', 'created_at']);

            $finalPaymentMissing = collect();
            $finalOnApproval = collect();

            foreach ($PaymentMissing as $paymentOrder) {
                $onApprovalOrder = $OnApproval->firstWhere('orderId', $paymentOrder->orderId);

                if (!$onApprovalOrder) {
                    $finalPaymentMissing->push($paymentOrder);
                } else {
                    if ($paymentOrder->created_at > $onApprovalOrder->created_at) {
                        $finalPaymentMissing->push($paymentOrder);
                    } else {
                        $finalOnApproval->push($onApprovalOrder);
                    }
                    $OnApproval = $OnApproval->reject(function ($item) use ($paymentOrder) {
                        return $item->orderId === $paymentOrder->orderId;
                    });
                }
            }

            $orders = $finalPaymentMissing;
            $orderIds = $orders->pluck('orderId');
            $data = AutoOrder::whereIn('id', $orderIds)->paginate(50);

            return view('main.reports.show4', compact('data', 'pstatus'));
        } elseif ($pstatus == 18) {
            $ignoredOrderIds = ReportNew::whereIn('pstatus', [7, 18])
                ->where('created_at', '<', $from)
                ->pluck('orderId');

            $PaymentMissing = ReportNew::whereNotIn('orderId', $ignoredOrderIds)
                ->where('pstatus', 7)
                ->where(function ($q) use ($uid, $from, $to) {
                    if (!empty($uid)) {
                        $q->where('userId', $uid);
                    }
                    $q->whereBetween('created_at', [$from, $to]);
                })
                ->whereHas('order', function ($q) use ($paneltype, $call_type, $searchBy, $search) {
                    $q->where('paneltype', $paneltype)
                        ->where('order_taker_id', '!=', 1);
                    if (!empty($call_type)) {
                        $q->where('call_type', $call_type);
                    }
                    if (!empty($searchBy) && !empty($search)) {
                        $q->where($searchBy, 'Like', '%' . $search . '%');
                    }
                })
                ->paginate(50);

            $OnApproval = ReportNew::whereNotIn('orderId', $ignoredOrderIds)
                ->where(function ($q) use ($uid, $from, $to) {
                    if (!empty($uid)) {
                        $q->where('userId', $uid);
                    }
                    $q->whereBetween('created_at', [$from, $to]);
                    $q->where('pstatus', 18);
                })
                ->whereHas('order', function ($q) use ($paneltype, $call_type, $searchBy, $search) {
                    $q->where('paneltype', $paneltype)
                        ->where('order_taker_id', '!=', 1);
                    if (!empty($call_type)) {
                        $q->where('call_type', $call_type);
                    }
                    if (!empty($searchBy) && !empty($search)) {
                        $q->where($searchBy, 'Like', '%' . $search . '%');
                    }
                })
                ->paginate(50);

            $paymentMissingCollection = $PaymentMissing->getCollection();
            $onApprovalCollection = $OnApproval->getCollection();

            $finalPaymentMissing = collect();
            $finalOnApproval = collect();

            foreach ($paymentMissingCollection as $paymentOrder) {
                $onApprovalOrder = $onApprovalCollection->firstWhere('orderId', $paymentOrder->orderId);

                if (!$onApprovalOrder) {
                    $finalPaymentMissing->push($paymentOrder);
                } else {
                    if ($paymentOrder->created_at > $onApprovalOrder->created_at) {
                        $finalPaymentMissing->push($paymentOrder);
                    } else {
                        $finalOnApproval->push($onApprovalOrder);
                    }

                    $onApprovalCollection = $onApprovalCollection->filter(function ($item) use ($paymentOrder) {
                        return $item->orderId !== $paymentOrder->orderId;
                    });
                }
            }

            $orders = $finalOnApproval->merge($onApprovalCollection);
            $orderIds = $orders->pluck('orderId');
            $data = AutoOrder::whereIn('id', $orderIds)->paginate(50);

            return view('main.reports.show4', compact('data', 'pstatus'));
        } elseif ($pstatus == 123) {
            $data = AutoOrder::where(function ($query) use ($uid, $paneltype, $call_type, $searchBy, $search) {
                $query->where('pstatus', 9)
                    ->where('paneltype', $paneltype)
                    ->where(function ($query) use ($uid, $call_type, $searchBy, $search) {
                        if (!empty($uid)) {
                            $query->where('Listed_User', $uid);
                        }
                        if (!empty($call_type)) {
                            $query->where('call_type', $call_type);
                        }
                        if (!empty($searchBy) && !empty($search)) {
                            $query->where($searchBy, 'Like', '%' . $search . '%');
                        }
                    })
                    ->orWhere(function ($query) use ($uid, $paneltype, $call_type, $searchBy, $search) {
                        $query->where('pstatus', 18)
                            ->where('paneltype', $paneltype)
                            ->where(function ($query) use ($uid, $call_type, $searchBy, $search) {
                                if (!empty($uid)) {
                                    $query->where('OnApproval_User', $uid);
                                }
                                if (!empty($call_type)) {
                                    $query->where('call_type', $call_type);
                                }
                                if (!empty($searchBy) && !empty($search)) {
                                    $query->where($searchBy, 'Like', '%' . $search . '%');
                                }
                            });
                    });
            })
                ->paginate(50);

            return view('main.reports.show4', compact('data', 'pstatus'));
        } elseif ($pstatus == 321) {
            $data = AutoOrder::where('pstatus', 9)
                ->where(function ($query) use ($uid, $from, $to, $call_type, $searchBy, $search) {
                    if (!empty($uid)) {
                        $query->where('Listed_User', $uid);
                    }
                    if (!empty($call_type)) {
                        $query->where('call_type', $call_type);
                    }
                    if (!empty($searchBy) && !empty($search)) {
                        $query->where($searchBy, 'Like', '%' . $search . '%');
                    }
                })
                ->where('paneltype', $paneltype)
                ->paginate(50);

            return view('main.reports.show4', compact('data', 'pstatus'));
        } elseif ($pstatus == 9) {
            $data = AutoOrder::whereNotNull('Listed_Created')
                ->where(function ($query) use ($uid, $from, $to, $call_type, $searchBy, $search) {
                    if (!empty($uid)) {
                        $query->where('Listed_User', $uid);
                    }
                    if (!empty($call_type)) {
                        $query->where('call_type', $call_type);
                    }
                    if (!empty($searchBy) && !empty($search)) {
                        $query->where($searchBy, 'Like', '%' . $search . '%');
                    }
                })
                ->whereBetween('Listed_Created', [now()->startOfDay(), now()])
                ->where('paneltype', $paneltype)
                ->paginate(50);

            return view('main.reports.show4', compact('data', 'pstatus'));
        } elseif ($pstatus == 10) {
            $data = ReportNew::where('pstatus', 10)
                ->where(function ($q) use ($uid, $from, $to) {
                    if (!empty($uid)) {
                        $q->where('userId', $uid);
                    }
                    $q->whereBetween('created_at', [$from, $to]);
                    $q->where('count', 1);
                })
                ->whereHas('order', function ($q) use ($paneltype, $call_type, $searchBy, $search) {
                    $q->where('paneltype', $paneltype)
                        ->where('order_taker_id', '!=', 1);
                    if (!empty($call_type)) {
                        $q->where('call_type', $call_type);
                    }
                    if (!empty($searchBy) && !empty($search)) {
                        $q->where($searchBy, 'Like', '%' . $search . '%');
                    }
                })
                ->has('carriers', 1)
                ->pluck('orderId');

            $data = AutoOrder::whereIn('id', $data)->paginate(50);

            return view('main.reports.show4', compact('data', 'pstatus'));
        } elseif ($pstatus == 34) {

            $data =  ReportNew::where('pstatus', 10)
                ->where(function ($q) use ($uid, $from, $to) {
                    if (!empty($uid)) {
                        $q->where('userId', $uid);
                    }
                    $q->whereBetween('created_at', [$from, $to]);
                    $q->where('count', '!=', 1);
                })
                ->whereHas('order', function ($q) use ($paneltype, $call_type) {
                    $q->where('paneltype', $paneltype)
                        ->where('order_taker_id', '!=', 1);
                    if (!empty($call_type)) {
                        $q->where('call_type', $call_type);
                    }
                })
                ->has('carriers', '>', 1)
                ->groupBy(['orderId', 'pstatus'])
                ->pluck('orderId');

            $data = AutoOrder::whereIn('id', $data)->paginate(50);

            return view('main.reports.show4', compact('data', 'pstatus'));
        } elseif ($pstatus == 11) {
            $data = ReportNew::where('pstatus', 11)
                ->where(function ($q) use ($uid, $from, $to, $sort) {
                    if (!empty($uid)) {
                        $q->where('userId', $uid);
                    }
                    $q->where('count', 1);
                    $q->whereBetween('created_at', [$from, $to]);
                })
                ->whereHas('order', function ($q) use ($paneltype, $call_type, $searchBy, $search) {
                    $q->where('paneltype', $paneltype)
                        ->where('order_taker_id', '!=', 1);
                    if (!empty($call_type)) {
                        $q->where('call_type', $call_type);
                    }
                    if (!empty($searchBy) && !empty($search)) {
                        $q->where($searchBy, 'Like', '%' . $search . '%');
                    }
                })
                ->pluck('orderId');

            $data = AutoOrder::whereIn('id', $data)->paginate(50);

            return view('main.reports.show4', compact('data', 'pstatus'));
        } elseif ($pstatus == 12) {
            $data = ReportNew::where('pstatus', 12)
                ->where(function ($q) use ($uid, $from, $to, $sort) {
                    if (!empty($uid)) {
                        $q->where('userId', $uid);
                    }
                    $q->where('count', 1);
                    $q->whereBetween('created_at', [$from, $to]);
                })
                ->whereHas('order', function ($q) use ($paneltype, $call_type) {
                    $q->where('paneltype', $paneltype)
                        ->where('order_taker_id', '!=', 1);
                    if (!empty($call_type)) {
                        $q->where('call_type', $call_type);
                    }
                    if (!empty($searchBy) && !empty($search)) {
                        $q->where($searchBy, 'Like', '%' . $search . '%');
                    }
                })
                ->pluck('orderId');

            $data = AutoOrder::whereIn('id', $data)->paginate(50);

            return view('main.reports.show4', compact('data', 'pstatus'));
        } elseif ($pstatus == 32) {
            $data = report::where('pstatus', 32)
                ->where(function ($q) use ($uid, $from, $to, $sort) {
                    if (!empty($uid)) {
                        $q->where('userId', $uid);
                    }

                    $q->whereBetween($sort, [$from, $to]);
                })
                ->whereHas('order', function ($q) use ($paneltype, $call_type, $searchBy, $search) {
                    $q->where('paneltype', $paneltype)
                        ->where('order_taker_id', '!=', 1);
                    if (!empty($call_type)) {
                        $q->where('call_type', $call_type);
                    }
                    if (!empty($searchBy) && !empty($search)) {
                        $q->where($searchBy, 'Like', '%' . $search . '%');
                    }
                })
                ->groupBy(['orderId', 'pstatus'])
                ->pluck('orderId');

            $data = AutoOrder::whereIn('id', $data)->paginate(50);

            return view('main.reports.show4', compact('data', 'pstatus'));
        } elseif ($pstatus == 30) {
            $data = report::where('pstatus', 30)
                ->where(function ($q) use ($uid, $from, $to, $sort) {
                    if (!empty($uid)) {
                        $q->where('userId', $uid);
                    }

                    $q->whereBetween($sort, [$from, $to]);
                })
                ->whereHas('order', function ($q) use ($paneltype, $call_type, $searchBy, $search) {
                    $q->where('paneltype', $paneltype);
                    if (!empty($call_type)) {
                        $q->where('call_type', $call_type);
                    }
                    if (!empty($searchBy) && !empty($search)) {
                        $q->where($searchBy, 'Like', '%' . $search . '%');
                    }
                })
                ->groupBy(['orderId', 'pstatus'])
                ->pluck('orderId');

            $data = AutoOrder::whereIn('id', $data)->paginate(50);

            return view('main.reports.show4', compact('data', 'pstatus'));
        } elseif ($pstatus == 14) {
            $data = report::where('pstatus', 14)
                ->where(function ($q) use ($uid, $from, $to, $sort) {
                    if (!empty($uid)) {
                        $q->where('userId', $uid);
                    }

                    $q->whereBetween('created_at', [$from, $to]);
                })
                ->whereHas('order', function ($q) use ($paneltype, $call_type, $searchBy, $search) {
                    $q->where('paneltype', $paneltype)
                        ->where('order_taker_id', '!=', 1);
                    if (!empty($call_type)) {
                        $q->where('call_type', $call_type);
                    }
                    if (!empty($searchBy) && !empty($search)) {
                        $q->where($searchBy, 'Like', '%' . $search . '%');
                    }
                })
                ->groupBy(['orderId', 'pstatus'])
                ->pluck('orderId');

            $data = AutoOrder::whereIn('id', $data)->paginate(50);

            return view('main.reports.show4', compact('data', 'pstatus'));
        } elseif ($pstatus == 19) {
            $data = report::where('pstatus', 19)
                ->where(function ($q) use ($uid, $from, $to, $sort) {
                    if (!empty($uid)) {
                        $q->where('userId', $uid);
                    }

                    $q->whereBetween('created_at', [$from, $to]);
                })
                ->whereHas('order', function ($q) use ($paneltype, $call_type, $searchBy, $search) {
                    $q->where('paneltype', $paneltype)
                        ->where('order_taker_id', '!=', 1);
                    if (!empty($call_type)) {
                        $q->where('call_type', $call_type);
                    }
                    if (!empty($searchBy) && !empty($search)) {
                        $q->where($searchBy, 'Like', '%' . $search . '%');
                    }
                })
                ->groupBy(['orderId', 'pstatus'])
                ->pluck('orderId');

            $data = AutoOrder::whereIn('id', $data)->paginate(50);

            return view('main.reports.show4', compact('data', 'pstatus'));
        } elseif ($pstatus == 29) {
            $data = AutoOrder::where(function ($q2) {
                $q2->where('booking_confirm', 'may be')
                    ->orWhere('booking_confirm', 'confirm');
            })->where('paneltype', $paneltype)->whereBetween($sort, [$from, $to])
                ->where(function ($q) use ($user, $uid, $call_type) {
                    if (isset($user->userRole->name)) {
                        if ($user->userRole->name == 'Order Taker' || $user->userRole->name == 'Seller Agent' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Manager') {
                            $q->where('u_id', $uid);
                        } else if ($user->userRole->name == 'Dispatcher') {
                            $q->where('dispatcher_id', $uid);
                        } else if ($user->userRole->name == 'Delivery Boy') {
                            $q->where('delivery_boy', $uid);
                        }
                    }

                    if (!empty($call_type)) {
                        $q->where('call_type', $call_type);
                    }
                })
                ->where('source', $source)
                ->orderBy('created_at', 'DESC');

            if (isset($request->source)) {
                $data = $data->where('source', $source);
            }
            $data = $data->paginate(50);

            return view('main.reports.show4', compact('data', 'pstatus'));
        } else {
            if ($sort === 'updated_at') {
                $data = AutoOrder::with('req_ship')
                    ->where('paneltype', $paneltype)
                    ->whereNotNull($sort)
                    ->whereBetween($sort === 'created_at' ? 'NEW_Created' : $sort, [$from, $to])
                    ->where(function ($q) use ($uid, $user, $call_type, $searchBy, $search) {
                        if (!empty($uid)) {
                            $q->where($user, $uid);
                        }
                        if (!empty($call_type)) {
                            $q->where('call_type', $call_type);
                        }
                        if (!empty($searchBy) && !empty($search)) {
                            $q->where($searchBy, 'Like', '%' . $search . '%');
                        }
                    })
                    ->paginate(50);
            } else {
                $data = AutoOrder::with('req_ship')
                    ->where('paneltype', $paneltype)
                    ->whereNotNull($sort)
                    ->whereBetween('NEW_Created', [$from, $to])
                    ->where(function ($q) use ($uid, $user, $call_type, $searchBy, $search) {
                        if (!empty($uid)) {
                            $q->where($user, $uid);
                        }
                        if (!empty($call_type)) {
                            $q->where('call_type', $call_type);
                        }
                        if (!empty($searchBy) && !empty($search)) {
                            $q->where($searchBy, 'Like', '%' . $search . '%');
                        }
                    })
                    ->paginate(50);
            }

            return view('main.reports.show3New', compact('data', 'pstatus'));
        }
    }

    public function show2(Request $request)
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

        $searchBy = 'id';
        if (isset($request->search_by)) {
            $searchBy = $request->search_by;
        }
        $search = '';
        if (isset($request->search)) {
            $search = $request->search;
        }
        $sort = 'created_at';
        if (isset($request->search_by2)) {
            $sort = $request->search_by2;
        }
        if (isset($request->source)) {
            $source = $request->source;
        }

        $uid = '';
        $user = '';
        if (isset($request->user)) {
            $uid = $request->user;
            $user = User::with('userRole')->where('id', $uid)->first();
        }
        $pstatus = $request->pstatus;
        $auc_storage = 0;
        if (isset($request->auc_storage)) {
            if ($request->auc_storage > 0) {
                $auc_storage = $request->auc_storage;
            }
        }
        if ($pstatus == 29) {
            $data = AutoOrder::where(function ($q2) {
                $q2->where('booking_confirm', 'may be')
                    ->orWhere('booking_confirm', 'confirm');
            })->where('paneltype', $paneltype)->whereBetween($sort, [$from, $to])
                ->where(function ($q) use ($user, $uid) {
                    if (isset($user->userRole->name)) {
                        if ($user->userRole->name == 'Order Taker' || $user->userRole->name == 'Seller Agent' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Manager') {
                            $q->where('u_id', $uid);
                        } else if ($user->userRole->name == 'Dispatcher') {
                            $q->where('dispatcher_id', $uid);
                        } else if ($user->userRole->name == 'Delivery Boy') {
                            $q->where('delivery_boy', $uid);
                        }
                    }
                })
                ->where('source', $source)
                ->orderBy('created_at', 'DESC');

            if (isset($request->source)) {
                $data = $data->where('source', $source);
            }
            $data = $data->paginate(50);

            return view('main.reports.show4', compact('data', 'pstatus'));
        } else {
            $data = report::with('order.req_ship')
                ->where(function ($q) use ($uid, $from, $to, $pstatus, $auc_storage, $sort) {
                    if (!empty($uid)) {
                        if ($pstatus >= 20 && $pstatus <= 27) {
                            $q->whereHas('order.req_ship', function ($q1) use ($uid) {
                                $q1->where('user_id', $uid);
                            });
                        } else if ($pstatus == 7 || $pstatus == 8 || $pstatus == 18) {
                            if (!empty($uid)) {
                                $q->where('userId', $uid);
                            }
                        } else {
                            $q->where('userId', $uid);
                        }
                    }
                    if ($pstatus >= 0) {
                        if ($pstatus == 35 || $auc_storage > 0) {
                            $q->where('pstatus', '>=', 10)->where('pstatus', '<=', 14);
                        } else if ($pstatus == 28) {
                            $q->where('pstatus', '>=', 11)->where('pstatus', '<=', 12);
                        } else if ($pstatus >= 20 && $pstatus <= 27) {
                            $q->whereHas('order.req_ship', function ($q1) use ($pstatus) {
                                $q1->where('status', $pstatus);
                            })->where('pstatus', 9);
                        } else if ($pstatus == 33) {
                            $q->whereHas('order', function ($q2) {
                                $q2->whereIn('pstatus', [7, 8, 9, 10, 18])
                                    ->where(function ($q3) {
                                        $q3->whereIn('oterminal', [2, 3, 4])->orWhereIn('dterminal', [2, 3, 4]);
                                    });
                            });
                        } else {
                            $q->where('pstatus', '=', $pstatus);
                        }
                    } else {
                        $q->where(function ($q3) {
                            $q3->where(function ($q2) {
                                $q2->where('pstatus', '<=', 14);
                            })->orWhere('pstatus', 18)->orWhere('pstatus', 19);
                        });
                    }
                    if ($pstatus >= 20 && $pstatus <= 27) {
                        $q->whereHas('order.req_ship', function ($q1) use ($from, $to, $sort) {
                            $q1->whereBetween($sort, [$from, $to]);
                        });
                    } else {
                        $q->whereBetween($sort, [$from, $to]);
                    }
                })
                ->whereHas('order', function ($q) use ($auc_storage, $pstatus, $paneltype) {
                    if ($auc_storage == 1 && $pstatus == 35) {
                        $q->where('already_auction_storage', 1);
                    } else if ($auc_storage == 2 && $pstatus == 35) {
                        $q->where('late_pickup_auction_storage', 1);
                    } else if ($auc_storage == 0 && $pstatus == 35) {
                        $q->where('already_auction_storage', 1)->orWhere('late_pickup_auction_storage', 1);
                    } else if ($pstatus == 28) {
                        $q->where('storage_id', '>', 0);
                    }
                })
                ->whereHas('order', function ($q2) use ($searchBy, $search) {
                    if (!empty($searchBy) && !empty($search)) {
                        $q2->where($searchBy, 'Like', '%' . $search . '%');
                    }
                })
                ->whereHas('order', function ($q) use ($paneltype) {
                    $q->where('paneltype', $paneltype);
                })
                ->orderBy('created_at', 'DESC');

            if (isset($request->source)) {
                $data = $data->whereHas('order', function ($q) use ($source) {
                    $q->where('source', $source);
                });
            }

            if ($pstatus < 20) {
                $data = $data->groupBy(['orderId', 'pstatus', 'userId']);
            }

            $data = $data->paginate(50);

            return view('main.reports.show3', compact('data', 'pstatus'));
        }
    }

    public function sheets_data(Request $request, $id)
    {
        $id = base64_decode($id);
        $data = Sheet::find($id);
        $user_id = (array) json_decode($data->user_ids);
        if (in_array(auth()->id(), $user_id)) {
            return view('main.sheets.index', compact('data'));
        } else {
            return back()->with('msg', 'No Permission');
        }
    }
    public function sheets_list()
    {
        $user = User::with('userRole')->where('deleted', 0)->get();

        $data = Sheet::paginate(50);
        return view('main.sheets.sheet_list', compact('data', 'user'));
    }
    public function create_sheet_google(Request $request)
    {
        if (isset($request->link)) {
            $data = isset($request->id) ? Sheet::find($request->id) : new Sheet();
            $data->sheet_name = $request->sheet_name;
            $data->link = $request->link;
            $data->user_ids = json_encode($request->user_id);
            $data->user_id = auth()->id();
            $data->save();
            return back()->with('msg', 'Sheet Generated' . $data->id);
        }
    }

    public function excelsheet($slug)
    {
        $data = ExcelSheet::where('type', $slug)->where('status', 1)->first();
        return view('main.sheets.excelsheet', compact('data'));
    }

    public function special_instructions(Request $request)
    {
        $data = new SpecialInstruction;
        $data->order_id = $request->order_id;
        $data->user_id = Auth::user()->id;
        $data->instruction = $request->instruction;
        $data->save();
        return back()->with('msg', 'Special Instruction added on the order id#' . $request->order_id);
    }

    public function carrier_approachings(Request $request)
    {
        // Validation rules
        $rules = [
            'extension' => 'required|string|max:255',
            'comp_name' => 'required|string|max:255',
            'comp_phone' => 'required|string|max:255',
            'comp_response' => 'required|string|max:255',
            'status' => 'required|in:0,1',
            'order_id' => 'required',
        ];

        // Custom error messages
        $messages = [
            'status.in' => 'The status field must be either 0 or 1.',
        ];

        // Validate the request
        $request->validate($rules, $messages);

        try {
            // If validation passes, proceed to save data
            $data = new CarrierApproaching;
            $data->user_id = Auth::user()->id;
            $data->extension = $request->input('extension');
            $data->order_id = $request->input('order_id');
            $data->comp_name = $request->input('comp_name');
            $data->comp_phone = $request->input('comp_phone');
            $data->comp_response = $request->input('comp_response');
            $data->status = $request->input('status');
            $data->save();

            // Optionally, you can return a response or redirect here
            return redirect()->back()->with('success', 'Data saved successfully');
        } catch (\Exception $e) {
            // If an error occurs, you can redirect back with an error message
            return redirect()->back()->with('error', 'An error occurred while saving data.');
        }
    }

    public function getCarrier_approachings(Request $request)
    {
        $allHistory = CarrierApproaching::with('user')->where('order_id', $request->order_id)->get();
        return $allHistory;
    }
}
