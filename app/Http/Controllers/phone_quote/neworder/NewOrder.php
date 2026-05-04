<?php

namespace App\Http\Controllers\phone_quote\neworder;

use App\creditcard;
use App\orderpayment;
use App\PriceCheckerPrice;
use App\ProfileCard;
use App\SendTemplateEmail;
use App\ShipaQuery;
use App\ShipperDetailsAssignCarrier;
use App\ShipperDetailsAssignDealer;
use App\ShipperDetailsCarrier;
use App\ShipperDetailsDealer;
use App\ShipperDetailsHistoriesCarrier;
use App\ShipperDetailsHistoriesDealer;
use App\ShipperDetailsPhoneCarrier;
use App\ShipperDetailsPhoneDealer;
use App\Template;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\call_history;
use App\User;
use App\role;
use App\AutoOrder;
use App\UsedAndNewCarDealers;
use App\AssignUsedAndNewOrderTaker;
use App\report;
use App\zipcodes;
use App\OrderTakerQouteAccess;
use App\notes;
use App\user_setting;
use App\CallCountUsedAndNew;
use App\general_setting;
use App\HistoryUsedAndNewCall;
use App\carriers_company;
use App\singlereport;
use App\carrier;
use App\profit;
use Session;
use Redirect;
use Hash;
use Mail;
use Auth;
use DB;
use Carbon\Carbon;
use App\ReviewWebsiteLink;
use App\WhatsappAutoApproachCount;
use App\HistoryUsedAndNewWhatsapp;
use App\NatureOfCustomer;
use Vinkla\Hashids\Facades\Hashids;
use App\FieldLabel;
use App\EmailHistory;
use Illuminate\Support\Facades\Validator;
use App\ShipperDetails;
use App\ShipperDetailsAssign;
use App\ShipperDetailsPhone;
use App\ShipperDetailsHistories;
use App\ApproachingAssign;
class NewOrder extends Controller
{
    public function searchData(Request $request)
    {
        $user = Auth::user();
        $setting = general_setting::first();
        $ptype = $this->check_user_setting($user->id);
        $pstatus = [];

        $dispatcher_id_search = $request->input('dispatcher_id_search',null);

        $sort_by = $request->sort_by;
        if ($request->sort_by == 'id') {
            $sort_by = 'created_at';
        }

        $emp_panel_access = array_map('intval', explode(',', $user->emp_panel_access));

        // $query = AutoOrder::query();
        $query = AutoOrder::whereIn('paneltype', $emp_panel_access);
        // echo "<pre>";
        // print_r($pstatus);
        // exit();

        if ($ptype == 1) {
            $pstatus = explode(',', Auth::user()->emp_access_phone);
        } elseif ($ptype == 2) {
            $pstatus = explode(',', Auth::user()->emp_access_web);
        } elseif ($ptype == 3) {
            $pstatus = explode(',', Auth::user()->emp_access_test);
        } elseif ($ptype == 4) {
            $pstatus = explode(',', Auth::user()->panel_type_4);
        } elseif ($ptype == 5) {
            $pstatus = explode(',', Auth::user()->panel_type_5);
        } elseif ($ptype == 6) {
            $pstatus = explode(',', Auth::user()->panel_type_6);
        } else {
            $pstatus = [];
        }

        if (isset($request->date_range) && !empty($request->date_range)) {
            $query = $query->where(function ($q) use ($request, $sort_by) {
                $dates = explode('-', $request->date_range);
                $from = date('Y-m-d 00:00:00', strtotime($dates[0]));
                $too = date('Y-m-d 23:59:59', strtotime($dates[1]));
                if ($sort_by == 'created_at' || $sort_by == 'updated_at') {
                    if ($from == $too) {
                        $q->whereDate($sort_by, $from);
                    } else {
                        $q->whereBetween($sort_by, [$from, $too]);
                    }
                }
            });
        }
        if (isset($request->date_range1) && !empty($request->date_range1)) {
            $query = $query->where(function ($q) use ($request) {
                $dates1 = explode('-', $request->date_range1);
                $from1 = date('Y-m-d 00:00:00', strtotime($dates1[0]));
                $too1 = date('Y-m-d 23:59:59', strtotime($dates1[1]));
                if ($from1 == $too1) {
                    $q->whereDate('oauctiondate', $from1)->orWhereDate('dauctiondate', $from1);
                } else {
                    $q->whereBetween('oauctiondate', [$from1, $too1])->orWhereBetween('dauctiondate', [$from1, $too1]);
                }
            });
        }
        if($dispatcher_id_search){
            $query = $query->where('dispatcher_id',$dispatcher_id_search);
        }

        if (isset($request->acutionaccounttitle) && !empty($request->acutionaccounttitle)) {
            $query = $query->where(function ($q) use ($request) {
                $q->where('oacutionaccounttitle', $request->acutionaccounttitle)
                    ->orWhere('dacutionaccounttitle', $request->acutionaccounttitle);
            });
            if (isset($request->acutionaccountname) && !empty($request->acutionaccountname)) {
                $query = $query->where(function ($q) use ($request) {
                    $q->where('oacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%')
                        ->orWhere('dacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%');
                });
            }
        }

        if (isset($request->pstatuss2)) {
            $query = $query->where(function ($q) use ($request) {
                $q->where('pstatus', $request->pstatuss2);
            });
        }

        if (isset($request->search_by) && !empty($request->search)) {
            $query = $query->where(function ($q) use ($request, $user) {
                if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                    if ($request->search_by == 'driverphoneno') {
                        $q->whereHas('carriers', function ($q2) use ($request) {
                            $q2->where('driverphoneno', 'like', '%' . $request->search . '%')
                                ->orWhere('companyphoneno', 'like', '%' . $request->search . '%');
                        });
                    } else {
                        $q->where($request->search_by, 'like', '%' . $request->search . '%');
                    }
                } else {
                    $q->where($request->search_by, 'like', '%' . $request->search . '%');
                }
            });
        } else {
            $query = $query->where(function ($q) use ($request, $user) {
                $q->where('id', 'like', '%' . $request->search . '%');
                $q->orWhere('oname', 'like', '%' . $request->search . '%');
                $q->orWhere('ymk', 'like', '%' . $request->search . '%');
                $q->orWhere('ophone', 'like', '%' . $request->search . '%');
                $q->orWhere('oemail', 'like', '%' . $request->search . '%');
                $q->orWhere('origincity', 'like', '%' . $request->search . '%');
                $q->orWhere('originstate', 'like', '%' . $request->search . '%');
                $q->orWhere('originzip', 'like', '%' . $request->search . '%');
                $q->orWhere('dname', 'like', '%' . $request->search . '%');
                $q->orWhere('demail', 'like', '%' . $request->search . '%');
                $q->orWhere('dphone', 'like', '%' . $request->search . '%');
                $q->orWhere('destinationcity', 'like', '%' . $request->search . '%');
                $q->orWhere('destinationstate', 'like', '%' . $request->search . '%');
                $q->orWhere('destinationzip', 'like', '%' . $request->search . '%');
                $q->orWhere('obuyer_no', 'like', '%' . $request->search . '%');
                $q->orWhere('obuyer_lot_no', 'like', '%' . $request->search . '%');
                $q->orWhere('obuyer_stock_no', 'like', '%' . $request->search . '%');
                if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                    $q->orWhereHas('carriers', function ($q2) use ($request) {
                        $q2->where('driverphoneno', 'like', '%' . $request->search . '%')
                            ->orWhere('companyphoneno', 'like', '%' . $request->search . '%');
                    });
                }
            });
        }

        if ($user->userRole->name == 'Manager') {
            if ($user->order_taker_quote == 1) {
                $query = $query->where('manager_id', $user->id)->orWhere('order_taker_id', $user->id);
            }
        } else if ($user->userRole->name == 'Dispatcher') {
            if ($user->order_taker_quote == 1) {
                $query = $query->where('dispatcher_id', $user->id);
            }
        } else if ($user->userRole->name == 'Delivery Boy') {
            if ($user->order_taker_quote == 1) {
                $query = $query->where('delivery_boy_id', $user->id);
            }
        } else if ($user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent') {
            if ($user->order_taker_quote == 1) {
                $query = $query->where('order_taker_id', $user->id);
            } else if ($user->order_taker_quote == 2) {
                $query = $query->whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$user->id]);
            }
        }

        // $query = $query->where('paneltype', '=', $ptype);
        // $query->where('created_at', '>=', Carbon::today()->subDays($setting->no_days));
        $query = $query->whereIn('pstatus', $pstatus);
        if (isset($request->sort_by) && !empty($request->sort_by)) {
            $query = $query->orderby($request->sort_by, 'desc');
        } else {
            $query = $query->orderby('updated_at', 'desc');
        }
        $data = $query->paginate(20);

        if ($request->ajax()) {
            return view('main.phone_quote.new.load', compact('data'))->render();
        }
        $link = ReviewWebsiteLink::where('status', 1)->get();
        return view('main.phone_quote.search.index', compact('data', 'link'));
    }

    // public function searchData2(Request $request)
    // {
    //     $user = Auth::user();
    //     $setting = general_setting::first();
    //     $ptype = $this->check_user_setting($user->id);
    //     $pstatus = [];
    //     $emp_panel_access = array_map('intval', explode(',', $user->emp_panel_access));

    //     // dd($emp_panel_access);

    //     if ($ptype == 1) {
    //         $pstatus = explode(',', Auth::user()->emp_access_phone);
    //     }
    //     elseif ($ptype == 3) {
    //         $pstatus = explode(',', Auth::user()->emp_access_test);
    //     }
    //     else {
    //         $pstatus = explode(',', Auth::user()->emp_access_web);
    //     }

    //     $data = AutoOrder::where('id', $request->search);

    //     // if($user->role > 1)
    //     // {
    //     //     $data = $data->where('pstatus','<>','15');
    //     // }
    //     // $data = $data->whereIn('pstatus',$pstatus);
    //     $data = $data->paginate(1);

    //     if (!isset($data[0])) {
    //         $query = AutoOrder::whereIn('paneltype', $emp_panel_access)->where(function ($q) use ($request, $user) {
    //             $q->where('id', 'like', '%' . $request->search . '%');
    //             $q->orWhere('oname', 'like', '%' . $request->search . '%');
    //             $q->orWhere('ymk', 'like', '%' . $request->search . '%');
    //             $q->orWhere('ophone', 'like', '%' . $request->search . '%');
    //             $q->orWhere('oemail', 'like', '%' . $request->search . '%');
    //             $q->orWhere('origincity', 'like', '%' . $request->search . '%');
    //             $q->orWhere('originstate', 'like', '%' . $request->search . '%');
    //             $q->orWhere('originzip', 'like', '%' . $request->search . '%');
    //             $q->orWhere('dname', 'like', '%' . $request->search . '%');
    //             $q->orWhere('demail', 'like', '%' . $request->search . '%');
    //             $q->orWhere('dphone', 'like', '%' . $request->search . '%');
    //             $q->orWhere('destinationcity', 'like', '%' . $request->search . '%');
    //             $q->orWhere('destinationstate', 'like', '%' . $request->search . '%');
    //             $q->orWhere('destinationzip', 'like', '%' . $request->search . '%');
    //             if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
    //                 $q->orWhereHas('carriers', function ($q2) use ($request) {
    //                     $q2->where('driverphoneno', 'like', '%' . $request->search . '%')
    //                         ->orWhere('companyphoneno', 'like', '%' . $request->search . '%');
    //                 });
    //             }
    //         });

    //         // if($user->role > 1)
    //         // {
    //         //     $query = $query->where('pstatus','<>','15');
    //         // }
    //         if ($user->userRole->name == 'Manager') {
    //             if ($user->order_taker_quote == 1) {
    //                 $query = $query->where('manager_id', $user->id)->orWhere('order_taker_id', $user->id);
    //             }
    //         } else if ($user->userRole->name == 'Dispatcher') {
    //             if ($user->order_taker_quote == 1) {
    //                 $query = $query->where('dispatcher_id', $user->id);
    //             }
    //         } else if ($user->userRole->name == 'Delivery Boy') {
    //             if ($user->order_taker_quote == 1) {
    //                 $query = $query->where('delivery_boy_id', $user->id);
    //             }
    //         } else if ($user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent') {
    //             if ($user->order_taker_quote == 1) {
    //                 $query = $query->where('order_taker_id', $user->id);
    //             } else if ($user->order_taker_quote == 2) {
    //                 $query = $query->whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$user->id]);
    //             }
    //         }

    //         if (isset($request->acutionaccounttitle) && !empty($request->acutionaccounttitle)) {
    //             $query = $query->where(function ($q) use ($request) {
    //                 $q->where('oacutionaccounttitle', $request->acutionaccounttitle)
    //                     ->orWhere('dacutionaccounttitle', $request->acutionaccounttitle);
    //             });
    //             if (isset($request->acutionaccountname) && !empty($request->acutionaccountname)) {
    //                 $query = $query->where(function ($q) use ($request) {
    //                     $q->where('oacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%')
    //                         ->orWhere('dacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%');
    //                 });
    //             }
    //         }

    //         // $query = $query->where('paneltype', '=', $ptype);
    //         $query = $query->whereIn('pstatus', $pstatus);
    //         $data = $query->orderby('created_at', 'desc')->paginate(20);
    //     }

    //     $link = ReviewWebsiteLink::where('status', 1)->get();
    //     return view('main.phone_quote.search.index', compact('data', 'link'));
    // }

    public function searchData2(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }
        $label = FieldLabel::all();

        $ptype = $this->check_user_setting($user->id);
        $pstatus = [];
        $emp_panel_access = array_map('intval', explode(',', $user->emp_panel_access));

        if ($ptype == 1) {
            $pstatus = explode(',', $user->emp_access_phone);
        } elseif ($ptype == 2) {
            $pstatus = explode(',', $user->emp_access_web);
        } elseif ($ptype == 3) {
            $pstatus = explode(',', $user->emp_access_test);
        } elseif ($ptype == 4) {
            $pstatus = explode(',', $user->panel_type_4);
        } elseif ($ptype == 5) {
            $pstatus = explode(',', $user->panel_type_5);
        } elseif ($ptype == 6) {
            $pstatus = explode(',', $user->panel_type_6);
        } else {
            $pstatus = [];
        }

        $query = AutoOrder::query();
        if (!empty($emp_panel_access)) {
            $query->whereIn('paneltype', $emp_panel_access);
        }

        $query->where(function ($q) use ($request, $user) {
            $q->where('id', 'like', '%' . $request->search . '%')
                ->orWhere('oname', 'like', '%' . $request->search . '%')
                ->orWhere('ymk', 'like', '%' . $request->search . '%')
                ->orWhere('ophone', 'like', '%' . $request->search . '%')
                ->orWhere('oemail', 'like', '%' . $request->search . '%')
                ->orWhere('originzsc', 'like', '%' . $request->search . '%')
//                ->orWhere('origincity', 'like', '%' . $request->search . '%')
//                ->orWhere('originstate', 'like', '%' . $request->search . '%')
//                ->orWhere('originzip', 'like', '%' . $request->search . '%')
                ->orWhere('dname', 'like', '%' . $request->search . '%')
                ->orWhere('demail', 'like', '%' . $request->search . '%')
                ->orWhere('dphone', 'like', '%' . $request->search . '%')
//                ->orWhere('destinationcity', 'like', '%' . $request->search . '%')
//                ->orWhere('destinationstate', 'like', '%' . $request->search . '%')
//                ->orWhere('destinationzip', 'like', '%' . $request->search . '%')
                ->orWhere('destinationzsc', 'like', '%' . $request->search . '%')
                ->orWhere('obuyer_no', 'like', '%' . $request->search . '%')
                ->orWhere('obuyer_lot_no', 'like', '%' . $request->search . '%')
                ->orWhere('obuyer_stock_no', 'like', '%' . $request->search . '%');

            if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                $q->orWhereHas('carriers', function ($q2) use ($request) {
                    $q2->where('driverphoneno', 'like', '%' . $request->search . '%')
                        ->orWhere('companyphoneno', 'like', '%' . $request->search . '%');
                });
            }
        });

        if ($user->userRole->name == 'Manager') {
            if ($user->order_taker_quote == 1) {
                $query->where('manager_id', $user->id)->orWhere('order_taker_id', $user->id);
            }
        } elseif ($user->userRole->name == 'Dispatcher') {
            if ($user->order_taker_quote == 1) {
                $query->where('dispatcher_id', $user->id);
            }
        } elseif ($user->userRole->name == 'Delivery Boy') {
            if ($user->order_taker_quote == 1) {
                $query->where('delivery_boy_id', $user->id);
            }
        } elseif ($user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent') {
            if ($user->order_taker_quote == 1) {
                $query->where('order_taker_id', $user->id);
            } elseif ($user->order_taker_quote == 2) {
                $query->whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$user->id]);
            }
        }

        if (isset($request->acutionaccounttitle) && !empty($request->acutionaccounttitle)) {
            $query->where(function ($q) use ($request) {
                $q->where('oacutionaccounttitle', $request->acutionaccounttitle)
                    ->orWhere('dacutionaccounttitle', $request->acutionaccounttitle);
            });

            if (isset($request->acutionaccountname) && !empty($request->acutionaccountname)) {
                $query->where(function ($q) use ($request) {
                    $q->where('oacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%')
                        ->orWhere('dacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%');
                });
            }
        }

        $query->whereIn('pstatus', $pstatus);

        $data = $query->orderby('created_at', 'desc')->paginate(20);

        $link = ReviewWebsiteLink::where('status', 1)->get();

        return view('main.phone_quote.search.index', compact('data', 'link','label'));
    }

    public function new(Request $request)
    {
        $link = ReviewWebsiteLink::where('status', 1)->get();
        $label = FieldLabel::all();
        if (Auth::check()) {
            $user = Auth::user();
            $ptype = $this->check_user_setting(Auth::user()->id);
            $setting = general_setting::first();

            if (\Request::is('new')) {
                $data = AutoOrder::with('filterHistory', 'latestHistory')->where('pstatus', '=', 0)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.index', compact('data', 'link', 'label'));
            }
            if (\Request::is('followup')) {
                $data = AutoOrder::with('filterHistory', 'latestHistory')->where('pstatus', '=', 2)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.index', compact('data', 'link', 'label'));
            }
            if (\Request::is('interested')) {
                $data = AutoOrder::with('filterHistory', 'latestHistory')->where('pstatus', '=', 1)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.index', compact('data', 'link', 'label'));
            }
            if (\Request::is('asking_low')) {
                $data = AutoOrder::with('filterHistory', 'latestHistory')->where('pstatus', '=', 3)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.index', compact('data', 'link', 'label'));
            }
            if (\Request::is('not_interested')) {
                $data = AutoOrder::with('filterHistory', 'latestHistory')->where('pstatus', '=', 4)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.index', compact('data', 'link', 'label'));
            }
            if (\Request::is('not_responding')) {
                $data = AutoOrder::with('filterHistory', 'latestHistory')->where('pstatus', '=', 5)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.index', compact('data', 'link', 'label'));
            }
            if (\Request::is('time_quote')) {
                $data = AutoOrder::with('filterHistory', 'latestHistory')->where('pstatus', '=', 6)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.index', compact('data', 'link', 'label'));
            }
            if (\Request::is('payment_missing')) {
                $data = AutoOrder::with('filterHistory', 'latestHistory')->where('pstatus', '=', 7)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                // echo "<pre>";
                // print_r($data);exit();
                return view('main.phone_quote.new.index', compact('data', 'link', 'label'));
            }
            if (\Request::is('booked')) {
                $data = AutoOrder::with('filterHistory', 'latestHistory')->where('pstatus', '=', 8)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.index', compact('data', 'link', 'label'));
            }
            if (\Request::is('listed')) {
                $data = AutoOrder::with('listed_sheet', 'filterHistory', 'latestHistory')->where('pstatus', '=', 9)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.index', compact('data', 'link', 'label'));
            }
            if (\Request::is('dispatch')) {
                $data = AutoOrder::with('listed_sheet', 'dispatch_sheet', 'filterHistory', 'latestHistory')->where('pstatus', '=', 10)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.index', compact('data', 'link', 'label'));
            }
            if (\Request::is('picked_up_approval')) {
                $data = AutoOrder::with('listed_sheet', 'dispatch_sheet', 'pickedup_sheet', 'filterHistory', 'latestHistory')->where('pstatus', '=', 11)
                    ->where(function ($q) {
                        $q->where('approve_pickup', '=', 0)
                            ->orWhere('approve_pickup', '=', NULL);
                    })
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.index', compact('data', 'link', 'label'));
            }
            if (\Request::is('picked_up')) {
                $data = AutoOrder::with('listed_sheet', 'dispatch_sheet', 'pickedup_sheet', 'filterHistory', 'latestHistory')->where('pstatus', '=', 11)
                    ->where('approve_pickup', '=', 1)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.index', compact('data', 'link', 'label'));
            }
            if (\Request::is('deliver_approval')) {
                $data = AutoOrder::with('listed_sheet', 'dispatch_sheet', 'pickedup_sheet', 'filterHistory', 'latestHistory')->where('pstatus', '=', 12)
                    ->where(function ($q) {
                        $q->where('approve_deliver', '=', 0)
                            ->orWhere('approve_deliver', '=', NULL);
                    })
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.index', compact('data', 'link', 'label'));
            }
            if (\Request::is('schedule_for_delivery')) {
                $data = AutoOrder::with('listed_sheet', 'dispatch_sheet', 'pickedup_sheet', 'delivery_sheet', 'filterHistory', 'latestHistory')->where('pstatus', '=', 12)
                    ->where('approve_deliver', '=', 2)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.index', compact('data', 'link', 'label'));
            }
            if (\Request::is('delivered')) {
                $data = AutoOrder::with('listed_sheet', 'dispatch_sheet', 'pickedup_sheet', 'delivery_sheet', 'filterHistory', 'latestHistory')->where('pstatus', '=', 12)
                    ->where('approve_deliver', '=', 1)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.index', compact('data', 'link', 'label'));
            }
            if (\Request::is('completed')) {
                $data = AutoOrder::with('listed_sheet', 'dispatch_sheet', 'pickedup_sheet', 'delivery_sheet', 'completed_sheet', 'filterHistory', 'latestHistory')->where('pstatus', '=', 13)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.index', compact('data', 'link', 'label'));
            }
            if (\Request::is('cancel')) {
                $data = AutoOrder::with('filterHistory', 'latestHistory')->where('pstatus', '=', 14)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.index', compact('data', 'link', 'label'));
            }
            if (\Request::is('deleted')) {
                $data = AutoOrder::with('filterHistory', 'latestHistory')->where('pstatus', '=', 15)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.index', compact('data', 'link', 'label'));
            }
            if (\Request::is('owns_money')) {
                $data = AutoOrder::with('filterHistory', 'latestHistory')->where(function ($q) {
                    $q->where(function ($q2) {
                        $q2->where('pstatus', '>=', 7)->where('pstatus', '<=', 14);
                    })->orWhere('pstatus', 18);
                })
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
                        $q->whereBetween('created_at', [\Carbon\Carbon::now()->firstOfMonth()->format('Y-m-d 00:00:00'), \Carbon\Carbon::now()->format('Y-m-d 23:59:59')]);
                    })
                    ->where('paneltype', '=', $ptype)
                    // ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.owes_index', compact('data', 'link', 'label'));
            }
            if (\Request::is('carrierupdate')) {
                $data = AutoOrder::with('filterHistory', 'latestHistory')->where('pstatus', '=', 17)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.index', compact('data', 'link', 'label'));
            }
            if (\Request::is('approaching')) {

                if ($ptype == 1) {
                    $phoneaccess = explode(',', Auth::user()->emp_access_phone);
                } elseif ($ptype == 2) {
                    $phoneaccess = explode(',', Auth::user()->emp_access_web);
                } elseif ($ptype == 3) {
                    $phoneaccess = explode(',', Auth::user()->emp_access_test);
                } elseif ($ptype == 4) {
                    $phoneaccess = explode(',', Auth::user()->panel_type_4);
                } elseif ($ptype == 5) {
                    $phoneaccess = explode(',', Auth::user()->panel_type_5);
                } elseif ($ptype == 6) {
                    $phoneaccess = explode(',', Auth::user()->panel_type_6);
                } else {
                    $phoneaccess = [];
                }

                $data = AutoOrder::query()->where('paneltype', '=', $ptype);

                if (Auth::user()->order_taker_quote == 1) {
                    $data = $data->where('order.manager_id', Auth::id())->orWhere('order.order_taker_id', Auth::id());
                }

//                if (!in_array("68", $phoneaccess) && !in_array("69", $phoneaccess)) {
//                    $data = $data->whereNotIn('order.paneltype', [1, 2]);
//                } else if (in_array("68", $phoneaccess) && in_array("69", $phoneaccess)) {
//                    $data = $data->whereIn('order.paneltype', [1, 2]);
//                } else if (in_array("68", $phoneaccess) && !in_array("69", $phoneaccess)) {
//                    $data = $data->where('order.paneltype', 1);
//                } else if (!in_array("68", $phoneaccess) && in_array("69", $phoneaccess)) {
//                    $data = $data->where('order.paneltype', 2);
//                }

                $search_as = $request->input('search_as',null);

                if ($search_as == 2 || empty($search_as)) { //search as normal user or himself
                    $user_id = auth()->user()->id;
                    $ApproachingAssign = ApproachingAssign::where('orderTaker',$user_id)->first();
                    $data = $data->with('latestHistory', 'approach', 'reports')
                        ->groupBy('ophone')
                        ->select('order.*', DB::raw("GROUP_CONCAT(order.id) as order_ids"));

                    if (!empty($ApproachingAssign)) {
                        $data->where('approaching_user',$user_id);
//                        $date_range = $ApproachingAssign->date_range;
//                        if (!empty($date_range)) {
//                            $dates = explode('-', $date_range);
//                            $from = date('Y-m-d 00:00:00', strtotime($dates[0]));
//                            $too = date('Y-m-d 23:59:59', strtotime($dates[1]));
//                            $data->whereBetween('order.created_at', [$from, $too]);
//                        }

//                        if ($ApproachingAssign->status == "144") {
//                            $data->havingRaw("
//                                            (SELECT GROUP_CONCAT(report.orderId)
//                                             FROM report
//                                             WHERE report.orderId IN (SELECT o.id FROM `order` as o WHERE o.ophone = `order`.ophone)
//                                             AND report.pstatus = 14) = GROUP_CONCAT(`order`.id)
//                                        ");
//                        } elseif ($ApproachingAssign->status == "44") {
//                            $data->havingRaw("
//                                            (SELECT GROUP_CONCAT(report.orderId)
//                                             FROM report
//                                             WHERE report.orderId IN (SELECT o.id FROM `order` as o WHERE o.ophone = `order`.ophone)
//                                             AND report.pstatus = 4) = GROUP_CONCAT(`order`.id)
//                                        ");
//                        }else{
//                            $data->where('pstatus',$ApproachingAssign->status);
//                        }

                    }

                }else{
                    $data = $data->with('latestHistory', 'approach', 'reports')
                        ->whereDate('order.created_at', '<', Carbon::now()->subDays(20))
                        ->groupBy('ophone')
                        ->select('order.*', DB::raw("GROUP_CONCAT(order.id) as order_ids"));
                }
                $total_count = $data->pluck('id')->toArray();
                $paginatedData = $data->paginate(20);

                $template = Template::all();

                return view('main.phone_quote.new.approaching', compact('paginatedData', 'link', 'label','total_count','template'));
            }
            if (\Request::is('onapproval')) {
                $data = AutoOrder::with('filterHistory', 'latestHistory')->where('pstatus', '=', 18)
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
                    ->where('paneltype', '=', $ptype)
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.index', compact('data', 'link', 'label'));
            }
            if (\Request::is('onapproval_cancel')) {
                $data = AutoOrder::with('filterHistory', 'latestHistory')->where('pstatus', '=', 19)
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
                    ->where('paneltype', '=', $ptype)
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.index', compact('data', 'link', 'label'));
            }
        } else {
            return redirect('/loginn/');
        }
    }

    public function autosApproach(Request $request)
    {
        // $link = ReviewWebsiteLink::where('status', 1)->get();
        // if (Auth::check()) {
        //     $user = Auth::user();
        //     $ptype = $this->check_user_setting(Auth::user()->id);
        //     $setting = general_setting::first();
        //     $state = "";

        //     if ($user->role != 1) {
        //         $data = UsedAndNewCarDealers::where('user_id', $user->id)
        //             ->orderBy('id', 'DESC');
        //             // ->paginate(20);
        //         // $data = User::with('assignedCompany')->find($user->id);
        //         $state = \App\User::with('assignedData')
        //             ->has('assignedData')
        //             ->find($user->id);

        //         $data = $data->take($state->assignedData->recordsAllowed)->paginate(20);
        //         // $data = $data->take($state->assignedData->recordsAllowed)->get();

        //         if ($state) {
        //             $state = $state->assignedData->state;
        //         } else {
        //             $state = "";
        //         }

        //     } else {
        //         # code...
        //         $data = UsedAndNewCarDealers::orderBy('id', 'DESC')->paginate(20);
        //     }

        //     return view('main.phone_quote.usedAndNewCarDealers.index', compact('data', 'state'));
        // } else {
        //     return redirect('/loginn/');
        // }

        $link = ReviewWebsiteLink::where('status', 1)->get();
        if (Auth::check()) {
            $user = Auth::user();
            $ptype = $this->check_user_setting(Auth::user()->id);
            $setting = general_setting::first();
            $state = "";

            if ($user->role != 1 && $user->role != 9) {
                $data = UsedAndNewCarDealers::where('user_id', $user->id)
                    ->orderBy('id', 'DESC');

                $state = \App\User::with('assignedData')
                    ->has('assignedData')
                    ->find($user->id);
                if ($state) {
                    $data = $data->take($state->assignedData->recordsAllowed);
                    $state = $state->assignedData->state;
                } else {
                    $state = "";
                }
                $data = $data->paginate(20);
                // dd($user->toArray(), $data->toArray());
                // dd($state, $data->toArray());
            } else {
                # code...
                $data = UsedAndNewCarDealers::orderBy('id', 'DESC')->paginate(20);
            }
            $template = Template::all();
            // dd($data->toArray());

            return view('main.phone_quote.usedAndNewCarDealers.index', compact('data', 'state','template'));
        } else {
            return redirect('/loginn/');
        }
    }

    public function autosApproachSearch(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $state = '';

            // Initialize query
            $query = UsedAndNewCarDealers::query();

            // Filter by user's assigned state if applicable
            if ($user->role != 1 && $user->role != 9) {
                $userState = \App\User::with('assignedData')->has('assignedData')->find($user->id);
                if ($userState) {
                    $state = $userState->assignedData->state;
                    $query->where('user_id', $user->id);
                }
            }

            // Apply filters
            if ($request->has('state') && $request->state !== null) {
                $query->where('state', $request->state);
            }
            if ($request->has('orderTaker') && $request->orderTaker !== null) {
                $query->where('user_id', $request->orderTaker);
            }
            if ($request->has('emailsSent')) {
                if ($request->email == 1) {
                    $query->whereNotNull('email');
                } elseif ($request->email == 0) {
                    $query->orWhereNull('email');
                }
            }
            if ($request->has('search') && $request->search !== null) {
                $query->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->search . '%');
            }
            if ($request->has('category') && $request->category !== null) {
                if ($request->category == 'Null') {
                    $query->whereNull('category');
                } else {
                    $query->where('category', $request->category);
                }
            }
            if ($request->has('email') && $request->email !== null) {
                if ($request->email == 1) {
                    $query->whereNotNull('email');
                } elseif ($request->email == 0) {
                    $query->orWhereNull('email');
                }
            }
            if ($request->has('withHistory') && $request->withHistory !== null) {
                if ($request->withHistory == 1) {
                    $query->has('history');
                } elseif ($request->email == 0) {
                    $query->doesntHave('history');
                }
            }
            // dd($request->toArray(), $query->take(50)->get()->toArray());

            // Apply date range filter if provided in the request
            if ($request->has('startDate') && $request->has('endDate')) {
                $from = date('Y-m-d 00:00:00', strtotime($request->startDate));
                $to = date('Y-m-d 23:59:59', strtotime($request->endDate));

                if (!empty($from) && !empty($to)) {
                    $query->whereBetween('created_at', [$from, $to]);
                }
            }

            $send_template =  isset($request->send_template) ? $request->send_template  : 0;
            if ($send_template) {
                $template = $request->template;
                // Get the SQL with placeholders
                $sqlWithPlaceholders = $query->toSql();

                // Replace placeholders with actual bindings
                $bindings = $query->getBindings();
                $fullSql = vsprintf(str_replace('?', "'%s'", $sqlWithPlaceholders), $bindings);

                // Save query to SendTemplateEmail model
                $send_email = new SendTemplateEmail();
                $send_email->query_sql = $fullSql;
                $send_email->data_type = $request->type;
                $send_email->template_id = $template;
                $send_email->limit_data = $request->input('limit_data',500);
                $send_email->user_id = auth()->user()->id;
                $send_email->save();
            }

            // Fetch paginated data
            $data = $query->paginate(20);

            return view('main.phone_quote.usedAndNewCarDealers.table', compact('data', 'state'));
        }
    }

    public function storeAutosApproach(Request $request)
    {
        // dd($request->toArray());

        $link = ReviewWebsiteLink::where('status', 1)->get();
        if (Auth::check()) {
            $user = Auth::user();
            $ptype = $this->check_user_setting(Auth::user()->id);
            $setting = general_setting::first();

            $check = AssignUsedAndNewOrderTaker::where('orderTaker', $request->orderTaker)->first();

            if ($check) {
                $check->state = implode(',', $request->state);
                $check->orderTaker = $request->orderTaker;
                $check->category = implode(',', $request->category_assign);
                $check->recordsAllowed = $request->recordsAllowed;
                $check->save();
            } else {
                $data = new AssignUsedAndNewOrderTaker;
                $data->state = implode(',', $request->state);
                $data->orderTaker = $request->orderTaker;
                $data->category = implode(',', $request->category_assign);
                $data->recordsAllowed = $request->recordsAllowed;
                $data->save();
            }

            foreach ($request->state as $state) {
                foreach ($request->category_assign as $category) {
                    UsedAndNewCarDealers::where('user_id', 0)
                        ->where('state', 'LIKE', '%' . $state . '%')
                        ->where('category', 'LIKE', '%' . $category . '%')
                        ->take($request->recordsAllowed)
                        ->update(['user_id' => $request->orderTaker]);
                }
            }

            return back();
        } else {
            return redirect('/loginn/');
        }
    }

    // public function storeAutosApproach(Request $request)
    // {
    //     dd($request->toArray());
    //     $link = ReviewWebsiteLink::where('status', 1)->get();
    //     if (Auth::check()) {
    //         $user = Auth::user();
    //         $ptype = $this->check_user_setting(Auth::user()->id);
    //         $setting = general_setting::first();

    //         $check = AssignUsedAndNewOrderTaker::where('orderTaker', $request->orderTaker)
    //             ->first();

    //         // if ($check) {
    //         //     // $assign = UsedAndNewCarDealers::where('user_id', $request->orderTaker)
    //         //     //     ->update(['user_id' => 0]);

    //         //     $check->state = implode(',', $request->state);
    //         //     $check->orderTaker = $request->orderTaker;
    //         //     $check->category = $request->category_assign;
    //         //     $check->recordsAllowed = $request->recordsAllowed;
    //         //     $check->save();
    //         // } else {

    //         $data = new AssignUsedAndNewOrderTaker;
    //         $data->state = implode(',', $request->state);
    //         $data->orderTaker = $request->orderTaker;
    //         $data->category = $request->category_assign;
    //         $data->recordsAllowed = $request->recordsAllowed;
    //         $data->save();
    //         // }

    //         foreach ($request->state as $key => $row) {
    //             $assign = UsedAndNewCarDealers::where('user_id', 0)
    //                 ->where('state', 'LIKE', '%' . $row . '%')
    //                 ->where('category', 'LIKE', '%' . $request->category_assign . '%')
    //                 ->take($request->recordsAllowed)
    //                 ->update(['user_id' => $request->orderTaker]);
    //         }

    //         return back();
    //     } else {
    //         return redirect('/loginn/');
    //     }
    // }

    public function allAutosApproach(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Check if the user has role 1 or 2
            if ($user->role == 1 || $user->role == 9) {
                $ptype = $this->check_user_setting($user->id);
                $setting = general_setting::first();

                $data = UsedAndNewCarDealers::where('user_id', '!=', 0)->with('user', 'callCount', 'history')
                    ->has('callCount')
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);
                //     ->get();
                // dd($data->toArray());

                return view('main.phone_quote.assignedUsedNewCarDealers.index', compact('data'));
            } else {
                // Redirect if the user doesn't have the required roles
                return redirect('/loginn/');
            }
        } else {
            return redirect('/loginn/');
        }
    }

    public function addCallCount(Request $request)
    {
        $link = ReviewWebsiteLink::where('status', 1)->get();
        if (Auth::check()) {
            $user = Auth::user();
            $ptype = $this->check_user_setting(Auth::user()->id);
            $setting = general_setting::first();

            // dd($request->toArray());
            $count = new CallCountUsedAndNew;
            $count->user_id = $user->id;
            $count->company_id = $request->company_id;
            $count->save();
        } else {
            return redirect('/loginn/');
        }
    }

    public function filterAssignedAutos(Request $request)
    {
        // dd('okk', $request->toArray());
        $link = ReviewWebsiteLink::where('status', 1)->get();

        if (Auth::check()) {
            $user = Auth::user();
            $ptype = $this->check_user_setting(Auth::user()->id);
            $setting = general_setting::first();
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');
            $historyUser = $request->input(
                'orderTaker'
            );

            // dd($request->toArray());
            if ($request->has('whatsapp')) {
                // dd('ok');
                $data = UsedAndNewCarDealers::where('user_id', '!=', 0)->with('user', 'whatsappCallCount', 'history')
                    ->has('whatsappCallCount');

                if ($request->has('orderTaker') && $request->orderTaker !== null) {
                    $data->where('user_id', $request->orderTaker);
                }
                if ($request->has('state') && $request->state !== null) {
                    $data->where('state', $request->state);
                }
                if (
                    $request->has('startDate') && $request->startDate !== null &&
                    $request->has('endDate') && $request->endDate !== null
                ) {
                    $data->whereHas('whatsappCallCount', function ($q) use ($startDate, $endDate) {
                        $q->whereDate('created_at', '>=', $startDate)
                            ->whereDate('created_at', '<=', $endDate);
                    });
                }
            } else {
                // $data = UsedAndNewCarDealers::where('user_id', '!=', 0)->with('user', 'callCount', 'history')
                //     ->has('history');

                // if ($request->has('orderTaker') && $request->orderTaker !== null) {
                //     $data->where('user_id', $request->orderTaker);
                // }
                // if ($request->has('state') && $request->state !== null) {
                //     $data->where('state', $request->state);
                // }
                // if (
                //     $request->has('startDate') && $request->startDate !== null &&
                //     $request->has('endDate') && $request->endDate !== null
                // ) {
                //     $data->whereHas('history', function ($q) use ($startDate, $endDate) {
                //         $q->whereDate('created_at', '>=', $startDate)
                //             ->whereDate('created_at', '<=', $endDate);
                //     });
                // }
                $data = UsedAndNewCarDealers::with([
                    'user',
                    'callCount',
                    'history.user',
                    'history' => function ($query) {
                        // Order by history's created_at if needed
                        $query->orderBy('created_at', 'desc');
                    }
                ])
                    ->has('history');

                if (
                    $request->has('orderTaker') && $request->orderTaker !== null
                ) {
                    $historyUser = $request->orderTaker;

                    $data->whereHas('history', function ($q) use ($historyUser) {
                        $q->where('user_id', $historyUser);
                    });
                    // $data->where('user_id', $request->orderTaker);
                }

                if ($request->has('state') && $request->state !== null) {
                    $data->where('state', $request->state);
                }

                if (
                    $request->has('startDate') && $request->startDate !== null &&
                    $request->has('endDate') && $request->endDate !== null
                ) {

                    $startDate = $request->startDate;
                    $endDate = $request->endDate;

                    // $data->whereHas('history', function ($q) use ($startDate, $endDate) {
                    //     $q->whereBetween('created_at', [$startDate, $endDate]);
                    // });
                    $data->whereHas(
                        'history',
                        function ($q) use ($startDate, $endDate) {
                            $q->whereDate('created_at', '>=', $startDate)
                                ->whereDate('created_at', '<=', $endDate);
                        }
                    );
                }
            }

            // dd('okk', $request->toArray(), $data->get()->toArray());

            return $data->get();
        } else {
            return redirect('/loginn/');
        }
    }

    public function storeHistory(Request $request)
    {
        $user = Auth::user();
        $history = new HistoryUsedAndNewCall;
        $history->user_id = $user->id;
        $history->company_id = $request->CompanyID;
        $history->connectStatus = $request->connectStatus;
        $history->comment = $request->comment;
        $history->save();

        UsedAndNewCarDealers::where('id', $request->CompanyID)->update(['user_id' => 0]);

        return HistoryUsedAndNewCall::with('user')
            ->where('user_id', $user->id)
            ->where('company_id', $request->CompanyID)
            ->get();
    }

    public function getHistory(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 2) {
            $user_id = $user->id;
        } else {
            $user_id = $request->user_id;
        }

        if ($request->has('whatsapp')) {
            $check = HistoryUsedAndNewWhatsapp::with('user')
                ->where('company_id', $request->company_id)
                ->get();

            dd('okok', $request->toArray());

            return HistoryUsedAndNewWhatsapp::with('user')->where('company_id', $request->company_id)
                ->get();
        } else {
            // $check = HistoryUsedAndNewCall::with('user')
            //         ->where('company_id', $request->company_id)
            //         ->get();
            return HistoryUsedAndNewCall::with('user')->where('company_id', $request->company_id)
                ->get();
        }
    }

    public function editAllowedStates(Request $request)
    {
        return AssignUsedAndNewOrderTaker::with('user')
            ->where('orderTaker', $request->user_id)
            ->first();
    }

    // public function autosApproachEmailAdd(Request $request)
    // {

    //     UsedAndNewCarDealers::where('id', $request->comp_id)->update([
    //         // Update the fields and values you want here
    //         'email' => $request->email,
    //     ]);

    //     $email = UsedAndNewCarDealers::where('id', $request->comp_id)->first();

    //     return $email;
    // }

    public function autosApproachEmailAdd(Request $request)
    {
        // dd($request->toArray());
        $updateData = [
            'email' => $request->email ?? null,
            'phone2' => $request->phone2 ?? null,
            'phone3' => $request->phone3 ?? null,
        ];

        UsedAndNewCarDealers::where('id', $request->comp_id)->update($updateData);

        $email = UsedAndNewCarDealers::where('id', $request->comp_id)->first();

        if ($request->email != null) {
            return $email;
        } else {
            return back();
        }
    }

    public function autosApproachCategoryAdd(Request $request)
    {
        // dd($request->toArray());
        UsedAndNewCarDealers::where('id', $request->comp_id)->update([
            // Update the fields and values you want here
            'category' => $request->category,
        ]);

        $category = UsedAndNewCarDealers::where('id', $request->comp_id)->first();

        return $category;
    }

    public function filterUsedAndNew(Request $request)
    {
        $link = ReviewWebsiteLink::where('status', 1)->get();
        if (Auth::check()) {
            $user = Auth::user();
            $ptype = $this->check_user_setting(Auth::user()->id);
            $setting = general_setting::first();

            if ($user->role === 2) {
                $data = UsedAndNewCarDealers::with('user')->where('user_id', $user->id)->orderBy('id', 'DESC');

                if ($request->has('myState')) {
                    $data = $data->where('state', $request->myState);
                }

                if ($request->has('search')) {
                    $data = $data->where('state', 'like', '%' . $request->search . '%')
                        ->orWhere('name', 'like', '%' . $request->search . '%');
                    ;
                }

                $state = \App\User::with('assignedData')
                    ->has('assignedData')
                    ->find($user->id);
                // $data = $data->paginate(20); // Use paginate() to get paginated results
                $data = $data->get(); // Use paginate() to get paginated results
                // $data = $data->take($state->assignedData->recordsAllowed)->paginate(20);
            } else {
                $data = UsedAndNewCarDealers::orderBy('id', 'DESC')->paginate(20);
            }

            return view('main.phone_quote.usedAndNewCarDealers.searchResults', compact('data'));
        } else {
            return redirect('/loginn/');
        }
    }

    public function addWhatsappCount(Request $request)
    {
        try {
            // Get the authenticated user's ID
            $user = Auth::user()->id;

            // Get the approach ID from the request
            $approach = $request->approachId;

            // You can use dd($user, $approach, $request->toArray()) for debugging purposes

            // Create a new entry in the WhatsappAutoApproachCount model
            WhatsappAutoApproachCount::create([
                'userId' => $user,
                'approachId' => $approach,
            ]);

            // You can also return a success response or redirect as needed
            return response()->json(['message' => 'Whatsapp count added successfully'], 200);
        } catch (\Exception $e) {
            // Handle any exceptions that may occur during the process
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function approachWhatsappCount(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Check if the user has role 1 or 2
            if ($user->role == 1 || $user->role == 9) {
                $ptype = $this->check_user_setting($user->id);
                $setting = general_setting::first();

                $data = UsedAndNewCarDealers::where('user_id', '!=', 0)->with('user', 'whatsappCallCount')
                    ->has('whatsappCallCount')
                    ->paginate(20);
                // dd($data->toArray());
                // $data = UsedAndNewCarDealers::has('whatsappCallCount')->get();

                return view('main.phone_quote.whatsappCallCount.index', compact('data'));
            } else {
                // Redirect if the user doesn't have the required roles
                return redirect('/loginn/');
            }
        } else {
            return redirect('/loginn/');
        }
    }

    public function fetch_data(Request $request)
    {
        $label = FieldLabel::all();
        // dd($request->toArray());
        if (Auth::check()) {
        } else {
            return redirect('/loginn/');
        }
        $ptype = $this->check_user_setting(Auth::user()->id);
        $setting = general_setting::first();

        $user = Auth::user();
        $from = '';
        $too = '';
        // if (isset($request->date_range) && !empty($request->date_range)) {
        //     $dates = explode(' - ', $request->date_range);
        //     $from = date('Y-m-d 00:00:00', strtotime($dates[0]));
        //     $too = date('Y-m-d 23:59:59', strtotime($dates[1]));
        // }
        if (isset($request->date_range) && !empty($request->date_range)) {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) == 2) { // Ensure both start and end dates are provided
                $from = date('Y-m-d 00:00:00', strtotime($dates[0]));
                $too = date('Y-m-d 23:59:59', strtotime($dates[1]));
            }
        }

        $from1 = '';
        $too1 = '';
        if (isset($request->date_range1) && !empty($request->date_range1)) {
            $dates1 = explode(' - ', $request->date_range1);
            $from1 = date('Y-m-d', strtotime($dates1[0]));
            $too1 = date('Y-m-d', strtotime($dates1[1]));
        }

        if ($request->ajax()) {
            if ($request->titlee == 'new') {
                $data = AutoOrder::where('pstatus', '=', 0)
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
                    ->where('paneltype', '=', $ptype)
                    ->where(function ($q) use ($from, $too, $request, $setting, $from1, $too1) {
                        if (!empty($from) && !empty($too)) {
                            if ($from == $too) {
                                $q->whereDate('created_at', $from);
                            } else {
                                $q->whereBetween('created_at', [$from, $too]);
                            }
                        } else {
                            $q->where('created_at', '>=', Carbon::today()->subDays($setting->no_days));
                        }
                        if (!empty($from1) && !empty($too1)) {
                            if ($from1 == $too1) {
                                $q->whereDate('oauctiondate', $from1)->orWhereDate('dauctiondate', $from1);
                            } else {
                                $q->whereBetween('oauctiondate', [$from1, $too1])->orWhereBetween('dauctiondate', [$from1, $too1]);
                            }
                        }
                    })
                    ->where(function ($q) use ($request, $user) {
                        if (!empty($request->search_by) && !empty($request->keywords)) {
                            if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                if ($request->search_by == 'driverphoneno') {
                                    $q->whereHas('carriers', function ($q2) use ($request) {
                                        $q2->where('driverphoneno', 'like', '%' . $request->keywords . '%')
                                            ->orWhere('companyphoneno', 'like', '%' . $request->keywords . '%');
                                    });
                                } else {
                                    $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                                }
                            } else {
                                $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                            }
                        }
                        if ((isset($request->verify) && !empty($request->verify)) || (isset($request->negative) && !empty($request->negative))) {
                            $q->whereHas('qa_remarks', function ($q2) use ($request) {
                                if (isset($request->verify) && !empty($request->verify)) {
                                    $q2->where('verify', $request->verify);
                                }
                                if (isset($request->negative) && !empty($request->negative)) {
                                    $q2->where('negative', $request->negative);
                                }
                            });
                        }

                        if (isset($request->acutionaccounttitle) && !empty($request->acutionaccounttitle)) {
                            $q->where(function ($q2) use ($request) {
                                $q2->where('oacutionaccounttitle', $request->acutionaccounttitle)
                                    ->orWhere('dacutionaccounttitle', $request->acutionaccounttitle);
                            });
                            if (isset($request->acutionaccountname) && !empty($request->acutionaccountname)) {
                                $q->where(function ($q2) use ($request) {
                                    $q2->where('oacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%')
                                        ->orWhere('dacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%');
                                });
                            }
                        }
                    })
                    ->orderBy($request->sort_by, 'DESC')
                    ->paginate(20);
                return view('main.phone_quote.new.load', compact('data'))->render();
            }
            if ($request->titlee == 'followup') {
                $data = AutoOrder::where('pstatus', '=', 2)
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
                    ->where('paneltype', '=', $ptype)
                    ->where(function ($q) use ($from, $too, $request, $setting, $from1, $too1) {
                        if (!empty($from) && !empty($too)) {
                            if ($from == $too) {
                                $q->whereDate('created_at', $from);
                            } else {
                                $q->whereBetween('created_at', [$from, $too]);
                            }
                        } else {
                            $q->where('created_at', '>=', Carbon::today()->subDays($setting->no_days));
                        }
                        if (!empty($from1) && !empty($too1)) {
                            if ($from1 == $too1) {
                                $q->whereDate('oauctiondate', $from1)->orWhereDate('dauctiondate', $from1);
                            } else {
                                $q->whereBetween('oauctiondate', [$from1, $too1])->orWhereBetween('dauctiondate', [$from1, $too1]);
                            }
                        }
                    })
                    ->where(function ($q) use ($request, $user) {
                        if (!empty($request->search_by) && !empty($request->keywords)) {
                            if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                if ($request->search_by == 'driverphoneno') {
                                    $q->whereHas('carriers', function ($q2) use ($request) {
                                        $q2->where('driverphoneno', 'like', '%' . $request->keywords . '%')
                                            ->orWhere('companyphoneno', 'like', '%' . $request->keywords . '%');
                                    });
                                } else {
                                    $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                                }
                            } else {
                                $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                            }
                        }
                        if ((isset($request->verify) && !empty($request->verify)) || (isset($request->negative) && !empty($request->negative))) {
                            $q->whereHas('qa_remarks', function ($q2) use ($request) {
                                if (isset($request->verify) && !empty($request->verify)) {
                                    $q2->where('verify', $request->verify);
                                }
                                if (isset($request->negative) && !empty($request->negative)) {
                                    $q2->where('negative', $request->negative);
                                }
                            });
                        }

                        if (isset($request->acutionaccounttitle) && !empty($request->acutionaccounttitle)) {
                            $q->where(function ($q2) use ($request) {
                                $q2->where('oacutionaccounttitle', $request->acutionaccounttitle)
                                    ->orWhere('dacutionaccounttitle', $request->acutionaccounttitle);
                            });
                            if (isset($request->acutionaccountname) && !empty($request->acutionaccountname)) {
                                $q->where(function ($q2) use ($request) {
                                    $q2->where('oacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%')
                                        ->orWhere('dacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%');
                                });
                            }
                        }
                    })
                    ->orderBy($request->sort_by, 'DESC')
                    ->paginate(20);
                return view('main.phone_quote.new.load', compact('data'))->render();
            }
            if ($request->titlee == 'interested') {
                $data = AutoOrder::where('pstatus', '=', 1)
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
                    ->where('paneltype', '=', $ptype)
                    ->where(function ($q) use ($from, $too, $request, $setting, $from1, $too1) {
                        if (!empty($from) && !empty($too)) {
                            if ($from == $too) {
                                $q->whereDate('created_at', $from);
                            } else {
                                $q->whereBetween('created_at', [$from, $too]);
                            }
                        } else {
                            $q->where('created_at', '>=', Carbon::today()->subDays($setting->no_days));
                        }
                        if (!empty($from1) && !empty($too1)) {
                            if ($from1 == $too1) {
                                $q->whereDate('oauctiondate', $from1)->orWhereDate('dauctiondate', $from1);
                            } else {
                                $q->whereBetween('oauctiondate', [$from1, $too1])->orWhereBetween('dauctiondate', [$from1, $too1]);
                            }
                        }
                    })
                    ->where(function ($q) use ($request, $user) {
                        if (!empty($request->search_by) && !empty($request->keywords)) {
                            if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                if ($request->search_by == 'driverphoneno') {
                                    $q->whereHas('carriers', function ($q2) use ($request) {
                                        $q2->where('driverphoneno', 'like', '%' . $request->keywords . '%')
                                            ->orWhere('companyphoneno', 'like', '%' . $request->keywords . '%');
                                    });
                                } else {
                                    $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                                }
                            } else {
                                $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                            }
                        }
                        if ((isset($request->verify) && !empty($request->verify)) || (isset($request->negative) && !empty($request->negative))) {
                            $q->whereHas('qa_remarks', function ($q2) use ($request) {
                                if (isset($request->verify) && !empty($request->verify)) {
                                    $q2->where('verify', $request->verify);
                                }
                                if (isset($request->negative) && !empty($request->negative)) {
                                    $q2->where('negative', $request->negative);
                                }
                            });
                        }

                        if (isset($request->acutionaccounttitle) && !empty($request->acutionaccounttitle)) {
                            $q->where(function ($q2) use ($request) {
                                $q2->where('oacutionaccounttitle', $request->acutionaccounttitle)
                                    ->orWhere('dacutionaccounttitle', $request->acutionaccounttitle);
                            });
                            if (isset($request->acutionaccountname) && !empty($request->acutionaccountname)) {
                                $q->where(function ($q2) use ($request) {
                                    $q2->where('oacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%')
                                        ->orWhere('dacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%');
                                });
                            }
                        }
                    })
                    ->orderBy($request->sort_by, 'DESC')
                    ->paginate(20);
                return view('main.phone_quote.new.load', compact('data'))->render();
            }
            if ($request->titlee == 'asking_low') {
                $data = AutoOrder::where('pstatus', '=', 3)
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
                    ->where('paneltype', '=', $ptype)
                    ->where(function ($q) use ($from, $too, $request, $setting, $from1, $too1) {
                        if (!empty($from) && !empty($too)) {
                            if ($from == $too) {
                                $q->whereDate('created_at', $from);
                            } else {
                                $q->whereBetween('created_at', [$from, $too]);
                            }
                        } else {
                            $q->where('created_at', '>=', Carbon::today()->subDays($setting->no_days));
                        }
                        if (!empty($from1) && !empty($too1)) {
                            if ($from1 == $too1) {
                                $q->whereDate('oauctiondate', $from1)->orWhereDate('dauctiondate', $from1);
                            } else {
                                $q->whereBetween('oauctiondate', [$from1, $too1])->orWhereBetween('dauctiondate', [$from1, $too1]);
                            }
                        }
                    })
                    ->where(function ($q) use ($request, $user) {
                        if (!empty($request->search_by) && !empty($request->keywords)) {
                            if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                if ($request->search_by == 'driverphoneno') {
                                    $q->whereHas('carriers', function ($q2) use ($request) {
                                        $q2->where('driverphoneno', 'like', '%' . $request->keywords . '%')
                                            ->orWhere('companyphoneno', 'like', '%' . $request->keywords . '%');
                                    });
                                } else {
                                    $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                                }
                            } else {
                                $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                            }
                        }
                        if ((isset($request->verify) && !empty($request->verify)) || (isset($request->negative) && !empty($request->negative))) {
                            $q->whereHas('qa_remarks', function ($q2) use ($request) {
                                if (isset($request->verify) && !empty($request->verify)) {
                                    $q2->where('verify', $request->verify);
                                }
                                if (isset($request->negative) && !empty($request->negative)) {
                                    $q2->where('negative', $request->negative);
                                }
                            });
                        }

                        if (isset($request->acutionaccounttitle) && !empty($request->acutionaccounttitle)) {
                            $q->where(function ($q2) use ($request) {
                                $q2->where('oacutionaccounttitle', $request->acutionaccounttitle)
                                    ->orWhere('dacutionaccounttitle', $request->acutionaccounttitle);
                            });
                            if (isset($request->acutionaccountname) && !empty($request->acutionaccountname)) {
                                $q->where(function ($q2) use ($request) {
                                    $q2->where('oacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%')
                                        ->orWhere('dacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%');
                                });
                            }
                        }
                    })
                    ->orderBy($request->sort_by, 'DESC')
                    ->paginate(20);
                return view('main.phone_quote.new.load', compact('data'))->render();
            }
            if ($request->titlee == 'not_interested') {
                $data = AutoOrder::where('pstatus', '=', 4)
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
                    ->where('paneltype', '=', $ptype)
                    ->where(function ($q) use ($from, $too, $request, $setting, $from1, $too1) {
                        if (!empty($from) && !empty($too)) {
                            if ($from == $too) {
                                $q->whereDate('created_at', $from);
                            } else {
                                $q->whereBetween('created_at', [$from, $too]);
                            }
                        } else {
                            $q->where('created_at', '>=', Carbon::today()->subDays($setting->no_days));
                        }
                        if (!empty($from1) && !empty($too1)) {
                            if ($from1 == $too1) {
                                $q->whereDate('oauctiondate', $from1)->orWhereDate('dauctiondate', $from1);
                            } else {
                                $q->whereBetween('oauctiondate', [$from1, $too1])->orWhereBetween('dauctiondate', [$from1, $too1]);
                            }
                        }
                    })
                    ->where(function ($q) use ($request, $user) {
                        if (!empty($request->search_by) && !empty($request->keywords)) {
                            if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                if ($request->search_by == 'driverphoneno') {
                                    $q->whereHas('carriers', function ($q2) use ($request) {
                                        $q2->where('driverphoneno', 'like', '%' . $request->keywords . '%')
                                            ->orWhere('companyphoneno', 'like', '%' . $request->keywords . '%');
                                    });
                                } else {
                                    $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                                }
                            } else {
                                $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                            }
                        }
                        if ((isset($request->verify) && !empty($request->verify)) || (isset($request->negative) && !empty($request->negative))) {
                            $q->whereHas('qa_remarks', function ($q2) use ($request) {
                                if (isset($request->verify) && !empty($request->verify)) {
                                    $q2->where('verify', $request->verify);
                                }
                                if (isset($request->negative) && !empty($request->negative)) {
                                    $q2->where('negative', $request->negative);
                                }
                            });
                        }

                        if (isset($request->acutionaccounttitle) && !empty($request->acutionaccounttitle)) {
                            $q->where(function ($q2) use ($request) {
                                $q2->where('oacutionaccounttitle', $request->acutionaccounttitle)
                                    ->orWhere('dacutionaccounttitle', $request->acutionaccounttitle);
                            });
                            if (isset($request->acutionaccountname) && !empty($request->acutionaccountname)) {
                                $q->where(function ($q2) use ($request) {
                                    $q2->where('oacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%')
                                        ->orWhere('dacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%');
                                });
                            }
                        }
                    })
                    ->orderBy($request->sort_by, 'DESC')
                    ->paginate(20);
                return view('main.phone_quote.new.load', compact('data'))->render();
            }
            if ($request->titlee == 'not_responding') {
                $data = AutoOrder::where('pstatus', '=', 5)
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
                    ->where('paneltype', '=', $ptype)
                    ->where(function ($q) use ($from, $too, $request, $setting, $from1, $too1) {
                        if (!empty($from) && !empty($too)) {
                            if ($from == $too) {
                                $q->whereDate('created_at', $from);
                            } else {
                                $q->whereBetween('created_at', [$from, $too]);
                            }
                        } else {
                            $q->where('created_at', '>=', Carbon::today()->subDays($setting->no_days));
                        }
                        if (!empty($from1) && !empty($too1)) {
                            if ($from1 == $too1) {
                                $q->whereDate('oauctiondate', $from1)->orWhereDate('dauctiondate', $from1);
                            } else {
                                $q->whereBetween('oauctiondate', [$from1, $too1])->orWhereBetween('dauctiondate', [$from1, $too1]);
                            }
                        }
                    })
                    ->where(function ($q) use ($request, $user) {
                        if (!empty($request->search_by) && !empty($request->keywords)) {
                            if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                if ($request->search_by == 'driverphoneno') {
                                    $q->whereHas('carriers', function ($q2) use ($request) {
                                        $q2->where('driverphoneno', 'like', '%' . $request->keywords . '%')
                                            ->orWhere('companyphoneno', 'like', '%' . $request->keywords . '%');
                                    });
                                } else {
                                    $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                                }
                            } else {
                                $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                            }
                        }
                        if ((isset($request->verify) && !empty($request->verify)) || (isset($request->negative) && !empty($request->negative))) {
                            $q->whereHas('qa_remarks', function ($q2) use ($request) {
                                if (isset($request->verify) && !empty($request->verify)) {
                                    $q2->where('verify', $request->verify);
                                }
                                if (isset($request->negative) && !empty($request->negative)) {
                                    $q2->where('negative', $request->negative);
                                }
                            });
                        }

                        if (isset($request->acutionaccounttitle) && !empty($request->acutionaccounttitle)) {
                            $q->where(function ($q2) use ($request) {
                                $q2->where('oacutionaccounttitle', $request->acutionaccounttitle)
                                    ->orWhere('dacutionaccounttitle', $request->acutionaccounttitle);
                            });
                            if (isset($request->acutionaccountname) && !empty($request->acutionaccountname)) {
                                $q->where(function ($q2) use ($request) {
                                    $q2->where('oacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%')
                                        ->orWhere('dacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%');
                                });
                            }
                        }
                    })
                    ->orderBy($request->sort_by, 'DESC')
                    ->paginate(20);
                return view('main.phone_quote.new.load', compact('data'))->render();
            }
            if ($request->titlee == 'time_quote') {
                $data = AutoOrder::where('pstatus', '=', 6)
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
                    ->where('paneltype', '=', $ptype)
                    ->where(function ($q) use ($from, $too, $request, $setting, $from1, $too1) {
                        if (!empty($from) && !empty($too)) {
                            if ($from == $too) {
                                $q->whereDate('created_at', $from);
                            } else {
                                $q->whereBetween('created_at', [$from, $too]);
                            }
                        } else {
                            $q->where('created_at', '>=', Carbon::today()->subDays($setting->no_days));
                        }
                        if (!empty($from1) && !empty($too1)) {
                            if ($from1 == $too1) {
                                $q->whereDate('oauctiondate', $from1)->orWhereDate('dauctiondate', $from1);
                            } else {
                                $q->whereBetween('oauctiondate', [$from1, $too1])->orWhereBetween('dauctiondate', [$from1, $too1]);
                            }
                        }
                    })
                    ->where(function ($q) use ($request, $user) {
                        if (!empty($request->search_by) && !empty($request->keywords)) {
                            if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                $q->whereHas('carriers', function ($q2) use ($request) {
                                    $q2->where('driverphoneno', 'like', '%' . $request->keywords . '%')
                                        ->orWhere('companyphoneno', 'like', '%' . $request->keywords . '%');
                                });
                            } else {
                                $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                            }
                        } else {
                            $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                        }
                        if ((isset($request->verify) && !empty($request->verify)) || (isset($request->negative) && !empty($request->negative))) {
                            $q->whereHas('qa_remarks', function ($q2) use ($request) {
                                if (isset($request->verify) && !empty($request->verify)) {
                                    $q2->where('verify', $request->verify);
                                }
                                if (isset($request->negative) && !empty($request->negative)) {
                                    $q2->where('negative', $request->negative);
                                }
                            });
                        }

                        if (isset($request->acutionaccounttitle) && !empty($request->acutionaccounttitle)) {
                            $q->where(function ($q2) use ($request) {
                                $q2->where('oacutionaccounttitle', $request->acutionaccounttitle)
                                    ->orWhere('dacutionaccounttitle', $request->acutionaccounttitle);
                            });
                            if (isset($request->acutionaccountname) && !empty($request->acutionaccountname)) {
                                $q->where(function ($q2) use ($request) {
                                    $q2->where('oacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%')
                                        ->orWhere('dacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%');
                                });
                            }
                        }
                    });

                if (!empty($request->search_by) && $request->search_by == 'dphone') {
                    $data->where('dphone', 'like', '%' . $request->keywords . '%');
                }

                $data = $data->orderBy($request->sort_by, 'DESC');
                $data = $data->paginate(20);

                // ->orderBy($request->sort_by, 'DESC')
                // ->paginate(20);
                return view('main.phone_quote.new.load', compact('data'))->render();
            }
            if ($request->titlee == 'payment_missing') {
                $data = AutoOrder::where('pstatus', '=', 7)
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
                    ->where('paneltype', '=', $ptype)
                    ->where(function ($q) use ($from, $too, $request, $setting, $from1, $too1) {
                        if (!empty($from) && !empty($too)) {
                            if ($from == $too) {
                                $q->whereDate('created_at', $from);
                            } else {
                                $q->whereBetween('created_at', [$from, $too]);
                            }
                        } else {
                            $q->where('created_at', '>=', Carbon::today()->subDays($setting->no_days));
                        }
                        if (!empty($from1) && !empty($too1)) {
                            if ($from1 == $too1) {
                                $q->whereDate('oauctiondate', $from1)->orWhereDate('dauctiondate', $from1);
                            } else {
                                $q->whereBetween('oauctiondate', [$from1, $too1])->orWhereBetween('dauctiondate', [$from1, $too1]);
                            }
                        }
                    })
                    ->where(function ($q) use ($request, $user) {
                        if (!empty($request->search_by) && !empty($request->keywords)) {
                            if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                if ($request->search_by == 'driverphoneno') {
                                    $q->whereHas('carriers', function ($q2) use ($request) {
                                        $q2->where('driverphoneno', 'like', '%' . $request->keywords . '%')
                                            ->orWhere('companyphoneno', 'like', '%' . $request->keywords . '%');
                                    });
                                } else {
                                    $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                                }
                            } else {
                                $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                            }
                        }
                        if ((isset($request->verify) && !empty($request->verify)) || (isset($request->negative) && !empty($request->negative))) {
                            $q->whereHas('qa_remarks', function ($q2) use ($request) {
                                if (isset($request->verify) && !empty($request->verify)) {
                                    $q2->where('verify', $request->verify);
                                }
                                if (isset($request->negative) && !empty($request->negative)) {
                                    $q2->where('negative', $request->negative);
                                }
                            });
                        }

                        if (isset($request->acutionaccounttitle) && !empty($request->acutionaccounttitle)) {
                            $q->where(function ($q2) use ($request) {
                                $q2->where('oacutionaccounttitle', $request->acutionaccounttitle)
                                    ->orWhere('dacutionaccounttitle', $request->acutionaccounttitle);
                            });
                            if (isset($request->acutionaccountname) && !empty($request->acutionaccountname)) {
                                $q->where(function ($q2) use ($request) {
                                    $q2->where('oacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%')
                                        ->orWhere('dacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%');
                                });
                            }
                        }
                    })
                    ->orderBy($request->sort_by, 'DESC')
                    ->paginate(20);
                return view('main.phone_quote.new.load', compact('data'))->render();
            }
            if ($request->titlee == 'booked') {
                $data = AutoOrder::where('pstatus', '=', 8)
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
                    ->where('paneltype', '=', $ptype)
                    ->where(function ($q) use ($from, $too, $request, $setting, $from1, $too1) {
                        if (!empty($from) && !empty($too)) {
                            if ($from == $too) {
                                $q->whereDate('created_at', $from);
                            } else {
                                $q->whereBetween('created_at', [$from, $too]);
                            }
                        } else {
                            $q->where('created_at', '>=', Carbon::today()->subDays($setting->no_days));
                        }
                        if (!empty($from1) && !empty($too1)) {
                            if ($from1 == $too1) {
                                $q->whereDate('oauctiondate', $from1)->orWhereDate('dauctiondate', $from1);
                            } else {
                                $q->whereBetween('oauctiondate', [$from1, $too1])->orWhereBetween('dauctiondate', [$from1, $too1]);
                            }
                        }
                    })
                    ->where(function ($q) use ($request, $user) {
                        if (!empty($request->search_by) && !empty($request->keywords)) {
                            if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                if ($request->search_by == 'driverphoneno') {
                                    $q->whereHas('carriers', function ($q2) use ($request) {
                                        $q2->where('driverphoneno', 'like', '%' . $request->keywords . '%')
                                            ->orWhere('companyphoneno', 'like', '%' . $request->keywords . '%');
                                    });
                                } else {
                                    $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                                }
                            } else {
                                $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                            }
                        }
                        if ((isset($request->verify) && !empty($request->verify)) || (isset($request->negative) && !empty($request->negative))) {
                            $q->whereHas('qa_remarks', function ($q2) use ($request) {
                                if (isset($request->verify) && !empty($request->verify)) {
                                    $q2->where('verify', $request->verify);
                                }
                                if (isset($request->negative) && !empty($request->negative)) {
                                    $q2->where('negative', $request->negative);
                                }
                            });
                        }

                        if (isset($request->acutionaccounttitle) && !empty($request->acutionaccounttitle)) {
                            $q->where(function ($q2) use ($request) {
                                $q2->where('oacutionaccounttitle', $request->acutionaccounttitle)
                                    ->orWhere('dacutionaccounttitle', $request->acutionaccounttitle);
                            });
                            if (isset($request->acutionaccountname) && !empty($request->acutionaccountname)) {
                                $q->where(function ($q2) use ($request) {
                                    $q2->where('oacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%')
                                        ->orWhere('dacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%');
                                });
                            }
                        }
                    })
                    ->orderBy($request->sort_by, 'DESC')
                    ->paginate(20);
                return view('main.phone_quote.new.load', compact('data'))->render();
            }
            if ($request->titlee == 'listed') {
                $data = AutoOrder::where('pstatus', '=', 9)
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
                    ->where('paneltype', '=', $ptype)
                    ->where(function ($q) use ($from, $too, $request, $setting, $from1, $too1) {
                        if (!empty($from) && !empty($too)) {
                            if ($from == $too) {
                                $q->whereDate('created_at', $from);
                            } else {
                                $q->whereBetween('created_at', [$from, $too]);
                            }
                        } else {
                            $q->where('created_at', '>=', Carbon::today()->subDays($setting->no_days));
                        }
                        if (!empty($from1) && !empty($too1)) {
                            if ($from1 == $too1) {
                                $q->whereDate('oauctiondate', $from1)->orWhereDate('dauctiondate', $from1);
                            } else {
                                $q->whereBetween('oauctiondate', [$from1, $too1])->orWhereBetween('dauctiondate', [$from1, $too1]);
                            }
                        }
                    })
                    ->where(function ($q) use ($request, $user) {
                        if (!empty($request->search_by) && !empty($request->keywords)) {
                            if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                if ($request->search_by == 'driverphoneno') {
                                    $q->whereHas('carriers', function ($q2) use ($request) {
                                        $q2->where('driverphoneno', 'like', '%' . $request->keywords . '%')
                                            ->orWhere('companyphoneno', 'like', '%' . $request->keywords . '%');
                                    });
                                } else {
                                    $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                                }
                            } else {
                                $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                            }
                        }
                        if ((isset($request->verify) && !empty($request->verify)) || (isset($request->negative) && !empty($request->negative))) {
                            $q->whereHas('qa_remarks', function ($q2) use ($request) {
                                if (isset($request->verify) && !empty($request->verify)) {
                                    $q2->where('verify', $request->verify);
                                }
                                if (isset($request->negative) && !empty($request->negative)) {
                                    $q2->where('negative', $request->negative);
                                }
                            });
                        }

                        if (isset($request->acutionaccounttitle) && !empty($request->acutionaccounttitle)) {
                            $q->where(function ($q2) use ($request) {
                                $q2->where('oacutionaccounttitle', $request->acutionaccounttitle)
                                    ->orWhere('dacutionaccounttitle', $request->acutionaccounttitle);
                            });
                            if (isset($request->acutionaccountname) && !empty($request->acutionaccountname)) {
                                $q->where(function ($q2) use ($request) {
                                    $q2->where('oacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%')
                                        ->orWhere('dacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%');
                                });
                            }
                        }
                    })
                    ->orderBy($request->sort_by, 'DESC')
                    ->paginate(20);
                return view('main.phone_quote.new.load', compact('data'))->render();
            }
            if ($request->titlee == 'dispatch') {
                $data = AutoOrder::with('listed_sheet', 'dispatch_sheet', 'filterHistory', 'latestHistory')->where('pstatus', '=', 10)
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
                    ->where('paneltype', '=', $ptype)

                    ->where(function ($q) use ($from, $too, $request, $setting, $from1, $too1) {
                        if (!empty($from) && !empty($too)) {
                            if ($from == $too) {
                                $q->whereDate('created_at', $from);
                            } else {
                                $q->whereBetween('created_at', [$from, $too]);
                            }
                        } else {
                            $q->where('created_at', '>=', Carbon::today()->subDays($setting->no_days));
                        }
                        if (!empty($from1) && !empty($too1)) {
                            if ($from1 == $too1) {
                                $q->whereDate('oauctiondate', $from1)->orWhereDate('dauctiondate', $from1);
                            } else {
                                $q->whereBetween('oauctiondate', [$from1, $too1])->orWhereBetween('dauctiondate', [$from1, $too1]);
                            }
                        }
                    })
                    ->where(function ($q) use ($request, $user) {
                        if (!empty($request->search_by) && !empty($request->keywords)) {
                            if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                if ($request->search_by == 'driverphoneno') {
                                    $q->whereHas('carriers', function ($q2) use ($request) {
                                        $q2->where('driverphoneno', 'like', '%' . $request->keywords . '%')
                                            ->orWhere('companyphoneno', 'like', '%' . $request->keywords . '%');
                                    });
                                } else {
                                    $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                                }
                            } else {
                                $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                            }
                        }
                        if ((isset($request->verify) && !empty($request->verify)) || (isset($request->negative) && !empty($request->negative))) {
                            $q->whereHas('qa_remarks', function ($q2) use ($request) {
                                if (isset($request->verify) && !empty($request->verify)) {
                                    $q2->where('verify', $request->verify);
                                }
                                if (isset($request->negative) && !empty($request->negative)) {
                                    $q2->where('negative', $request->negative);
                                }
                            });
                        }

                        if (isset($request->acutionaccounttitle) && !empty($request->acutionaccounttitle)) {
                            $q->where(function ($q2) use ($request) {
                                $q2->where('oacutionaccounttitle', $request->acutionaccounttitle)
                                    ->orWhere('dacutionaccounttitle', $request->acutionaccounttitle);
                            });
                            if (isset($request->acutionaccountname) && !empty($request->acutionaccountname)) {
                                $q->where(function ($q2) use ($request) {
                                    $q2->where('oacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%')
                                        ->orWhere('dacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%');
                                });
                            }
                        }
                    })
                    ->orderBy($request->sort_by, 'DESC')
                    ->paginate(20);
                return view('main.phone_quote.new.load', compact('data', 'label'))->render();
            }
            if ($request->titlee == 'picked_up_approval') {
                $data = AutoOrder::where('pstatus', '=', 11)
                    ->where(function ($q) {
                        $q->where('approve_pickup', '=', 0)->orWhere('approve_pickup', '=', NULL);
                    })
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
                    ->where('paneltype', '=', $ptype)
                    ->where(function ($q) use ($from, $too, $request, $setting, $from1, $too1) {
                        if (!empty($from) && !empty($too)) {
                            if ($from == $too) {
                                $q->whereDate('created_at', $from);
                            } else {
                                $q->whereBetween('created_at', [$from, $too]);
                            }
                        } else {
                            $q->where('created_at', '>=', Carbon::today()->subDays($setting->no_days));
                        }
                        if (!empty($from1) && !empty($too1)) {
                            if ($from1 == $too1) {
                                $q->whereDate('oauctiondate', $from1)->orWhereDate('dauctiondate', $from1);
                            } else {
                                $q->whereBetween('oauctiondate', [$from1, $too1])->orWhereBetween('dauctiondate', [$from1, $too1]);
                            }
                        }
                    })
                    ->where(function ($q) use ($request, $user) {
                        if (!empty($request->search_by) && !empty($request->keywords)) {
                            if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                if ($request->search_by == 'driverphoneno') {
                                    $q->whereHas('carriers', function ($q2) use ($request) {
                                        $q2->where('driverphoneno', 'like', '%' . $request->keywords . '%')
                                            ->orWhere('companyphoneno', 'like', '%' . $request->keywords . '%');
                                    });
                                } else {
                                    $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                                }
                            } else {
                                $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                            }
                        }
                        if ((isset($request->verify) && !empty($request->verify)) || (isset($request->negative) && !empty($request->negative))) {
                            $q->whereHas('qa_remarks', function ($q2) use ($request) {
                                if (isset($request->verify) && !empty($request->verify)) {
                                    $q2->where('verify', $request->verify);
                                }
                                if (isset($request->negative) && !empty($request->negative)) {
                                    $q2->where('negative', $request->negative);
                                }
                            });
                        }

                        if (isset($request->acutionaccounttitle) && !empty($request->acutionaccounttitle)) {
                            $q->where(function ($q2) use ($request) {
                                $q2->where('oacutionaccounttitle', $request->acutionaccounttitle)
                                    ->orWhere('dacutionaccounttitle', $request->acutionaccounttitle);
                            });
                            if (isset($request->acutionaccountname) && !empty($request->acutionaccountname)) {
                                $q->where(function ($q2) use ($request) {
                                    $q2->where('oacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%')
                                        ->orWhere('dacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%');
                                });
                            }
                        }
                    })
                    ->orderBy($request->sort_by, 'DESC')
                    ->paginate(20);
                return view('main.phone_quote.new.load', compact('data'))->render();
            }
            if ($request->titlee == 'picked_up') {
                $data = AutoOrder::where('pstatus', '=', 11)
                    ->where('approve_pickup', 1)
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
                    ->where('paneltype', '=', $ptype)
                    ->where(function ($q) use ($from, $too, $request, $setting, $from1, $too1) {
                        if (!empty($from) && !empty($too)) {
                            if ($from == $too) {
                                $q->whereDate('created_at', $from);
                            } else {
                                $q->whereBetween('created_at', [$from, $too]);
                            }
                        } else {
                            $q->where('created_at', '>=', Carbon::today()->subDays($setting->no_days));
                        }
                        if (!empty($from1) && !empty($too1)) {
                            if ($from1 == $too1) {
                                $q->whereDate('oauctiondate', $from1)->orWhereDate('dauctiondate', $from1);
                            } else {
                                $q->whereBetween('oauctiondate', [$from1, $too1])->orWhereBetween('dauctiondate', [$from1, $too1]);
                            }
                        }
                    })
                    ->where(function ($q) use ($request, $user) {
                        if (!empty($request->search_by) && !empty($request->keywords)) {
                            if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                if ($request->search_by == 'driverphoneno') {
                                    $q->whereHas('carriers', function ($q2) use ($request) {
                                        $q2->where('driverphoneno', 'like', '%' . $request->keywords . '%')
                                            ->orWhere('companyphoneno', 'like', '%' . $request->keywords . '%');
                                    });
                                } else {
                                    $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                                }
                            } else {
                                $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                            }
                        }
                        if ((isset($request->verify) && !empty($request->verify)) || (isset($request->negative) && !empty($request->negative))) {
                            $q->whereHas('qa_remarks', function ($q2) use ($request) {
                                if (isset($request->verify) && !empty($request->verify)) {
                                    $q2->where('verify', $request->verify);
                                }
                                if (isset($request->negative) && !empty($request->negative)) {
                                    $q2->where('negative', $request->negative);
                                }
                            });
                        }

                        if (isset($request->acutionaccounttitle) && !empty($request->acutionaccounttitle)) {
                            $q->where(function ($q2) use ($request) {
                                $q2->where('oacutionaccounttitle', $request->acutionaccounttitle)
                                    ->orWhere('dacutionaccounttitle', $request->acutionaccounttitle);
                            });
                            if (isset($request->acutionaccountname) && !empty($request->acutionaccountname)) {
                                $q->where(function ($q2) use ($request) {
                                    $q2->where('oacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%')
                                        ->orWhere('dacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%');
                                });
                            }
                        }
                    })
                    ->orderBy($request->sort_by, 'DESC')
                    ->paginate(20);
                return view('main.phone_quote.new.load', compact('data'))->render();
            }
            if ($request->titlee == 'deliver_approval') {
                $data = AutoOrder::where('pstatus', '=', 12)
                    ->where(function ($q) {
                        $q->where('approve_deliver', '=', 0)->orWhere('approve_deliver', '=', NULL);
                    })
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
                    ->where('paneltype', '=', $ptype)
                    ->where(function ($q) use ($from, $too, $request, $setting, $from1, $too1) {
                        if (!empty($from) && !empty($too)) {
                            if ($from == $too) {
                                $q->whereDate('created_at', $from);
                            } else {
                                $q->whereBetween('created_at', [$from, $too]);
                            }
                        } else {
                            $q->where('created_at', '>=', Carbon::today()->subDays($setting->no_days));
                        }
                        if (!empty($from1) && !empty($too1)) {
                            if ($from1 == $too1) {
                                $q->whereDate('oauctiondate', $from1)->orWhereDate('dauctiondate', $from1);
                            } else {
                                $q->whereBetween('oauctiondate', [$from1, $too1])->orWhereBetween('dauctiondate', [$from1, $too1]);
                            }
                        }
                    })
                    ->where(function ($q) use ($request, $user) {
                        if (!empty($request->search_by) && !empty($request->keywords)) {
                            if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                if ($request->search_by == 'driverphoneno') {
                                    $q->whereHas('carriers', function ($q2) use ($request) {
                                        $q2->where('driverphoneno', 'like', '%' . $request->keywords . '%')
                                            ->orWhere('companyphoneno', 'like', '%' . $request->keywords . '%');
                                    });
                                } else {
                                    $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                                }
                            } else {
                                $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                            }
                        }
                        if ((isset($request->verify) && !empty($request->verify)) || (isset($request->negative) && !empty($request->negative))) {
                            $q->whereHas('qa_remarks', function ($q2) use ($request) {
                                if (isset($request->verify) && !empty($request->verify)) {
                                    $q2->where('verify', $request->verify);
                                }
                                if (isset($request->negative) && !empty($request->negative)) {
                                    $q2->where('negative', $request->negative);
                                }
                            });
                        }

                        if (isset($request->acutionaccounttitle) && !empty($request->acutionaccounttitle)) {
                            $q->where(function ($q2) use ($request) {
                                $q2->where('oacutionaccounttitle', $request->acutionaccounttitle)
                                    ->orWhere('dacutionaccounttitle', $request->acutionaccounttitle);
                            });
                            if (isset($request->acutionaccountname) && !empty($request->acutionaccountname)) {
                                $q->where(function ($q2) use ($request) {
                                    $q2->where('oacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%')
                                        ->orWhere('dacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%');
                                });
                            }
                        }
                    })
                    ->orderBy($request->sort_by, 'DESC')
                    ->paginate(20);
                return view('main.phone_quote.new.load', compact('data'))->render();
            }
            if ($request->titlee == 'schedule_for_delivery') {
                $data = AutoOrder::where('pstatus', '=', 12)
                    ->where('approve_deliver', 2)
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
                    ->where('paneltype', '=', $ptype)
                    ->where(function ($q) use ($from, $too, $request, $setting, $from1, $too1) {
                        if (!empty($from) && !empty($too)) {
                            if ($from == $too) {
                                $q->whereDate('created_at', $from);
                            } else {
                                $q->whereBetween('created_at', [$from, $too]);
                            }
                        } else {
                            $q->where('created_at', '>=', Carbon::today()->subDays($setting->no_days));
                        }
                        if (!empty($from1) && !empty($too1)) {
                            if ($from1 == $too1) {
                                $q->whereDate('oauctiondate', $from1)->orWhereDate('dauctiondate', $from1);
                            } else {
                                $q->whereBetween('oauctiondate', [$from1, $too1])->orWhereBetween('dauctiondate', [$from1, $too1]);
                            }
                        }
                    })
                    ->where(function ($q) use ($request, $user) {
                        if (!empty($request->search_by) && !empty($request->keywords)) {
                            if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                if ($request->search_by == 'driverphoneno') {
                                    $q->whereHas('carriers', function ($q2) use ($request) {
                                        $q2->where('driverphoneno', 'like', '%' . $request->keywords . '%')
                                            ->orWhere('companyphoneno', 'like', '%' . $request->keywords . '%');
                                    });
                                } else {
                                    $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                                }
                            } else {
                                $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                            }
                        }
                        if ((isset($request->verify) && !empty($request->verify)) || (isset($request->negative) && !empty($request->negative))) {
                            $q->whereHas('qa_remarks', function ($q2) use ($request) {
                                if (isset($request->verify) && !empty($request->verify)) {
                                    $q2->where('verify', $request->verify);
                                }
                                if (isset($request->negative) && !empty($request->negative)) {
                                    $q2->where('negative', $request->negative);
                                }
                            });
                        }

                        if (isset($request->acutionaccounttitle) && !empty($request->acutionaccounttitle)) {
                            $q->where(function ($q2) use ($request) {
                                $q2->where('oacutionaccounttitle', $request->acutionaccounttitle)
                                    ->orWhere('dacutionaccounttitle', $request->acutionaccounttitle);
                            });
                            if (isset($request->acutionaccountname) && !empty($request->acutionaccountname)) {
                                $q->where(function ($q2) use ($request) {
                                    $q2->where('oacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%')
                                        ->orWhere('dacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%');
                                });
                            }
                        }
                    })
                    ->orderBy($request->sort_by, 'DESC')
                    ->paginate(20);
                return view('main.phone_quote.new.load', compact('data'))->render();
            }
            if ($request->titlee == 'delivered') {
                $data = AutoOrder::where('pstatus', '=', 12)
                    ->where('approve_deliver', 1)
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
                    ->where('paneltype', '=', $ptype)
                    ->where(function ($q) use ($from, $too, $request, $setting, $from1, $too1) {
                        if (!empty($from) && !empty($too)) {
                            if ($from == $too) {
                                $q->whereDate('created_at', $from);
                            } else {
                                $q->whereBetween('created_at', [$from, $too]);
                            }
                        } else {
                            $q->where('created_at', '>=', Carbon::today()->subDays($setting->no_days));
                        }
                        if (!empty($from1) && !empty($too1)) {
                            if ($from1 == $too1) {
                                $q->whereDate('oauctiondate', $from1)->orWhereDate('dauctiondate', $from1);
                            } else {
                                $q->whereBetween('oauctiondate', [$from1, $too1])->orWhereBetween('dauctiondate', [$from1, $too1]);
                            }
                        }
                    })
                    ->where(function ($q) use ($request, $user) {
                        if (!empty($request->search_by) && !empty($request->keywords)) {
                            if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                if ($request->search_by == 'driverphoneno') {
                                    $q->whereHas('carriers', function ($q2) use ($request) {
                                        $q2->where('driverphoneno', 'like', '%' . $request->keywords . '%')
                                            ->orWhere('companyphoneno', 'like', '%' . $request->keywords . '%');
                                    });
                                } else {
                                    $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                                }
                            } else {
                                $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                            }
                        }
                        if ((isset($request->verify) && !empty($request->verify)) || (isset($request->negative) && !empty($request->negative))) {
                            $q->whereHas('qa_remarks', function ($q2) use ($request) {
                                if (isset($request->verify) && !empty($request->verify)) {
                                    $q2->where('verify', $request->verify);
                                }
                                if (isset($request->negative) && !empty($request->negative)) {
                                    $q2->where('negative', $request->negative);
                                }
                            });
                        }

                        if (isset($request->acutionaccounttitle) && !empty($request->acutionaccounttitle)) {
                            $q->where(function ($q2) use ($request) {
                                $q2->where('oacutionaccounttitle', $request->acutionaccounttitle)
                                    ->orWhere('dacutionaccounttitle', $request->acutionaccounttitle);
                            });
                            if (isset($request->acutionaccountname) && !empty($request->acutionaccountname)) {
                                $q->where(function ($q2) use ($request) {
                                    $q2->where('oacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%')
                                        ->orWhere('dacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%');
                                });
                            }
                        }
                    })
                    ->orderBy($request->sort_by, 'DESC')
                    ->paginate(20);
                return view('main.phone_quote.new.load', compact('data'))->render();
            }
            if ($request->titlee == 'completed') {
                $data = AutoOrder::where('pstatus', '=', 13)
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
                    ->where('paneltype', '=', $ptype)
                    ->where(function ($q) use ($from, $too, $request, $setting, $from1, $too1) {
                        if (!empty($from) && !empty($too)) {
                            if ($from == $too) {
                                $q->whereDate('created_at', $from);
                            } else {
                                $q->whereBetween('created_at', [$from, $too]);
                            }
                        } else {
                            $q->where('created_at', '>=', Carbon::today()->subDays($setting->no_days));
                        }
                        if (!empty($from1) && !empty($too1)) {
                            if ($from1 == $too1) {
                                $q->whereDate('oauctiondate', $from1)->orWhereDate('dauctiondate', $from1);
                            } else {
                                $q->whereBetween('oauctiondate', [$from1, $too1])->orWhereBetween('dauctiondate', [$from1, $too1]);
                            }
                        }
                    })
                    ->where(function ($q) use ($request, $user) {
                        if (!empty($request->search_by) && !empty($request->keywords)) {
                            if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                if ($request->search_by == 'driverphoneno') {
                                    $q->whereHas('carriers', function ($q2) use ($request) {
                                        $q2->where('driverphoneno', 'like', '%' . $request->keywords . '%')
                                            ->orWhere('companyphoneno', 'like', '%' . $request->keywords . '%');
                                    });
                                } else {
                                    $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                                }
                            } else {
                                $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                            }
                        }
                        if ((isset($request->verify) && !empty($request->verify)) || (isset($request->negative) && !empty($request->negative))) {
                            $q->whereHas('qa_remarks', function ($q2) use ($request) {
                                if (isset($request->verify) && !empty($request->verify)) {
                                    $q2->where('verify', $request->verify);
                                }
                                if (isset($request->negative) && !empty($request->negative)) {
                                    $q2->where('negative', $request->negative);
                                }
                            });
                        }

                        if (isset($request->acutionaccounttitle) && !empty($request->acutionaccounttitle)) {
                            $q->where(function ($q2) use ($request) {
                                $q2->where('oacutionaccounttitle', $request->acutionaccounttitle)
                                    ->orWhere('dacutionaccounttitle', $request->acutionaccounttitle);
                            });
                            if (isset($request->acutionaccountname) && !empty($request->acutionaccountname)) {
                                $q->where(function ($q2) use ($request) {
                                    $q2->where('oacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%')
                                        ->orWhere('dacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%');
                                });
                            }
                        }
                        if ($request->has('review') && !empty($request->review)) {
                            if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                // $q->whereHas($request->review, 'like', '%' . $request->review . '%');
                                $review = $request->review;
                                $q->whereHas('completed_sheet', function ($q) use ($review) {
                                    $q->where('review', $review);
                                });
                            } else {
                                $q->where($request->review, 'like', '%' . $request->review . '%');
                            }
                            // dd('ok');
                        }
                    })
                    ->orderBy($request->sort_by, 'DESC')
                    ->paginate(20);
                return view('main.phone_quote.new.load', compact('data'))->render();
            }
            if ($request->titlee == 'cancel') {
                $data = AutoOrder::with('cancel_history')->where('pstatus', '=', 14)
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
                    ->where('paneltype', '=', $ptype)
                    ->where(function ($q) use ($from, $too, $request, $setting, $from1, $too1) {
                        if (!empty($from) && !empty($too)) {
                            if ($from == $too) {
                                $q->whereDate('created_at', $from);
                            } else {
                                $q->whereBetween('created_at', [$from, $too]);
                            }
                        } else {
                            $q->where('created_at', '>=', Carbon::today()->subDays($setting->no_days));
                        }
                    })
                    ->where(function ($q) use ($request, $user) {
                        if (!empty($request->search_by) && !empty($request->keywords)) {
                            if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                if ($request->search_by == 'driverphoneno') {
                                    $q->whereHas('carriers', function ($q2) use ($request) {
                                        $q2->where('driverphoneno', 'like', '%' . $request->keywords . '%')
                                            ->orWhere('companyphoneno', 'like', '%' . $request->keywords . '%');
                                    });
                                } else {
                                    $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                                }
                            } else {
                                $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                            }
                        }
                        if (isset($request->order_taker_id)) {
                            $q->where('order_taker_id', $request->order_taker_id);
                        }
                        if (isset($request->mistaker)) {
                            $q->whereHas('cancel_history', function ($q3) use ($request) {
                                $q3->where('mistaker', $request->mistaker);
                            });
                        }
                        if ((isset($request->verify) && !empty($request->verify)) || (isset($request->negative) && !empty($request->negative))) {
                            $q->whereHas('qa_remarks', function ($q2) use ($request) {
                                if (isset($request->verify) && !empty($request->verify)) {
                                    $q2->where('verify', $request->verify);
                                }
                                if (isset($request->negative) && !empty($request->negative)) {
                                    $q2->where('negative', $request->negative);
                                }
                            });
                        }

                        if (isset($request->acutionaccounttitle) && !empty($request->acutionaccounttitle)) {
                            $q->where(function ($q2) use ($request) {
                                $q2->where('oacutionaccounttitle', $request->acutionaccounttitle)
                                    ->orWhere('dacutionaccounttitle', $request->acutionaccounttitle);
                            });
                            if (isset($request->acutionaccountname) && !empty($request->acutionaccountname)) {
                                $q->where(function ($q2) use ($request) {
                                    $q2->where('oacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%')
                                        ->orWhere('dacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%');
                                });
                            }
                        }
                    })
                    ->orderBy($request->sort_by, 'DESC')
                    ->paginate(20);
                return view('main.phone_quote.new.load', compact('data'))->render();
            }
            if ($request->titlee == 'deleted') {
                $data = AutoOrder::where('pstatus', '=', 15)
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
                    ->where('paneltype', '=', $ptype)
                    ->where(function ($q) use ($from, $too, $request, $setting, $from1, $too1) {
                        if (!empty($from) && !empty($too)) {
                            if ($from == $too) {
                                $q->whereDate('created_at', $from);
                            } else {
                                $q->whereBetween('created_at', [$from, $too]);
                            }
                        } else {
                            $q->where('created_at', '>=', Carbon::today()->subDays($setting->no_days));
                        }
                        if (!empty($from1) && !empty($too1)) {
                            if ($from1 == $too1) {
                                $q->whereDate('oauctiondate', $from1)->orWhereDate('dauctiondate', $from1);
                            } else {
                                $q->whereBetween('oauctiondate', [$from1, $too1])->orWhereBetween('dauctiondate', [$from1, $too1]);
                            }
                        }
                    })
                    ->where(function ($q) use ($request, $user) {
                        if (!empty($request->search_by) && !empty($request->keywords)) {
                            if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                if ($request->search_by == 'driverphoneno') {
                                    $q->whereHas('carriers', function ($q2) use ($request) {
                                        $q2->where('driverphoneno', 'like', '%' . $request->keywords . '%')
                                            ->orWhere('companyphoneno', 'like', '%' . $request->keywords . '%');
                                    });
                                } else {
                                    $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                                }
                            } else {
                                $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                            }
                        }
                        if ((isset($request->verify) && !empty($request->verify)) || (isset($request->negative) && !empty($request->negative))) {
                            $q->whereHas('qa_remarks', function ($q2) use ($request) {
                                if (isset($request->verify) && !empty($request->verify)) {
                                    $q2->where('verify', $request->verify);
                                }
                                if (isset($request->negative) && !empty($request->negative)) {
                                    $q2->where('negative', $request->negative);
                                }
                            });
                        }

                        if (isset($request->acutionaccounttitle) && !empty($request->acutionaccounttitle)) {
                            $q->where(function ($q2) use ($request) {
                                $q2->where('oacutionaccounttitle', $request->acutionaccounttitle)
                                    ->orWhere('dacutionaccounttitle', $request->acutionaccounttitle);
                            });
                            if (isset($request->acutionaccountname) && !empty($request->acutionaccountname)) {
                                $q->where(function ($q2) use ($request) {
                                    $q2->where('oacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%')
                                        ->orWhere('dacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%');
                                });
                            }
                        }
                    })
                    ->orderBy($request->sort_by, 'DESC')
                    ->paginate(20);
                return view('main.phone_quote.new.load', compact('data'))->render();
            }
            if ($request->titlee == 'owns_money') {

                $data = AutoOrder::where(function ($q) {
                    $q->where(function ($q2) {
                        $q2->where('pstatus', '>=', 7)->where('pstatus', '<=', 14);
                    })->orWhere('pstatus', 18);
                })
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
                    ->where('paneltype', '=', $ptype)
                    ->where(function ($q) use ($from, $too, $request, $setting) {
                        if (!empty($from) && !empty($too)) {
                            if ($from == $too) {
                                $q->whereDate('created_at', $from);
                            } else {
                                $q->whereBetween('created_at', [$from, $too]);
                            }
                        } else {
                            // $q->where('created_at', '>=', Carbon::today()->subDays($setting->no_days));
                        }
                        if (isset($request->we_us_driver)) {
                            $q->where('we_us_driver', '=', $request->we_us_driver);
                        }
                        if (isset($request->owes)) {
                            $q->where('owes_money', 1)->where('owes', '>', 0);
                        }
                        if (isset($request->vehicle)) {
                            $q->where('vehicle', $request->vehicle);
                        }
                    })
                    ->where(function ($q) use ($request, $user) {
                        if (!empty($request->search_by) && !empty($request->keywords)) {
                            if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                if ($request->search_by == 'driverphoneno') {
                                    $q->whereHas('carriers', function ($q2) use ($request) {
                                        $q2->where('driverphoneno', 'like', '%' . $request->keywords . '%')
                                            ->orWhere('companyphoneno', 'like', '%' . $request->keywords . '%');
                                    });
                                } else {
                                    $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                                }
                            } else {
                                $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                            }
                        }

                        if (isset($request->acutionaccounttitle) && !empty($request->acutionaccounttitle)) {
                            $q->where(function ($q2) use ($request) {
                                $q2->where('oacutionaccounttitle', $request->acutionaccounttitle)
                                    ->orWhere('dacutionaccounttitle', $request->acutionaccounttitle);
                            });
                            if (isset($request->acutionaccountname) && !empty($request->acutionaccountname)) {
                                $q->where(function ($q2) use ($request) {
                                    $q2->where('oacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%')
                                        ->orWhere('dacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%');
                                });
                            }
                        }
                    })
                    ->orderBy($request->sort_by, 'DESC')
                    ->paginate(20);
                return view('main.phone_quote.new.owes_load', compact('data'))->render();
            }

            if ($request->titlee == 'approaching') {

                $filters = [
                    'id' => $request->orderID,
                    'clientName' => $request->clientName ?? '',
                    'delivery' => $request->delivery ?? '',
                    'zip' => $request->zip ?? '',
                    'vehicle' => $request->vehicle ?? '',
                    'status' => $request->status ?? '',
                    'vehicleName' => $request->vehicleName ?? '',
                    'custPhone' => $request->custPhone ?? '',
                    'dphone' => $request->dphone ?? '',
                    'buyer_no' => $request->buyer_no ?? '',
                    'lot_no' => $request->lot_no ?? '',
                    'stock_no' => $request->stock_no ?? '',
                    'port_val' => $request->port_val ?? '',
                    'vin_num' => $request->vin_num ?? '',
                    'userName' => $request->userName ?? '',
                    'call_type' => $request->call_type ?? null,
                    'date_range' => $request->date_range ?? '',
                    'oterminal' => $request->oterminal ?? '',
                    'emailComp' => $request->emailComp ?? '',
                ];
                $user2 = User::where('slug', $filters['userName'])
                    ->orWhere('name', $filters['userName'])
                    ->get();
                $userId = $user2->pluck('id')->toArray();

                if ($ptype == 1) {
                    $phoneaccess = explode(',', Auth::user()->emp_access_phone);
                } elseif ($ptype == 2) {
                    $phoneaccess = explode(',', Auth::user()->emp_access_web);
                } elseif ($ptype == 3) {
                    $phoneaccess = explode(',', Auth::user()->emp_access_test);
                } elseif ($ptype == 4) {
                    $phoneaccess = explode(',', Auth::user()->panel_type_4);
                } elseif ($ptype == 5) {
                    $phoneaccess = explode(',', Auth::user()->panel_type_5);
                } elseif ($ptype == 6) {
                    $phoneaccess = explode(',', Auth::user()->panel_type_6);
                } else {
                    $phoneaccess = [];
                }

                $data = AutoOrder::query()->where('paneltype', '=', $ptype);

                $data->where(function ($q) use ($filters, $userId) {
                    if (!empty($filters['buyer_no'])) {
                        $q->where('order.obuyer_no', 'LIKE', '%' . $filters['buyer_no'] . '%');
                    }
                    if (!empty($filters['clientName'])) {
                        $q->where('order.oname', 'LIKE', '%' . $filters['clientName'] . '%');
                    }
                    if (!empty($filters['delivery'])) {
                        $q->where('order.destinationcity', 'LIKE', '%' . $filters['delivery'] . '%');
                    }
                    if (!empty($filters['zip'])) {
                        $q->where('order.destinationzip', 'LIKE', '%' . $filters['zip'] . '%');
                    }
                    if (!empty($filters['custPhone'])) {
                        $q->where('order.ophone', 'LIKE', '%' . $filters['custPhone'] . '%')
                            ->orWhere('order.cphone', 'LIKE', '%' . $filters['custPhone'] . '%');
                    }
                    if (!empty($filters['dphone'])) {
                        $q->where('order.dphone', 'LIKE', '%' . $filters['dphone'] . '%');
                    }
                    if (!empty($filters['vehicle'])) {
                        $q->where('order.ymk', 'LIKE', '%' . $filters['vehicle'] . '%');
                    }
                    if (!empty($filters['vin_num'])) {
                        $q->where('order.vin_num', 'LIKE', '%' . $filters['vin_num'] . '%');
                    }
                    if ($filters['status'] !== '') {
                        $q->where('order.pstatus', $filters['status']);
                    }
                    if (!empty($userId)) {
                        $q->whereIn('order.order_taker_id', $userId);
                    }
                    if (!empty($filters['id'])) {
                        $q->where('order.id', $filters['id']);
                    }
                    if (!empty($filters['buyer_no'])) {
                        $q->where('order.obuyer_no', $filters['buyer_no']);
                    }
                    if (!empty($filters['lot_no'])) {
                        $q->where('order.obuyer_lot_no', $filters['lot_no']);
                    }
                    if (!empty($filters['call_type'])) {
                        $q->where('order.call_type', $filters['call_type']);
                    }
                    if (!empty($filters['stock_no'])) {
                        $q->where('order.obuyer_stock_no', $filters['stock_no']);
                    }
                    if (!empty($filters['oterminal'])) {
                        $q->where('order.oterminal', $filters['oterminal']);
                    }
                    if ($filters['port_val'] == 'Port') {
                        $q->where('order.dauction', 'Port');
                    }
                    if ($filters['emailComp'] == '1') {
                        $q->where(function($query){
                            $query->whereNotNull('order.oemail');
                            $query->where('order.oemail','!=','');
                        });
                    }
                    if ($filters['emailComp'] == '0') {
                        $q->whereNull('order.oemail');
                    }
                });


                if (Auth::user()->order_taker_quote == 1) {
                    $data = $data->where('order.manager_id', Auth::id())->orWhere('order.order_taker_id', Auth::id());
                }


                if (!in_array("68", $phoneaccess) && !in_array("69", $phoneaccess)) {
                    $data = $data->whereNotIn('order.paneltype', [1, 2]);
                } elseif (in_array("68", $phoneaccess) && in_array("69", $phoneaccess)) {
                    $data = $data->whereIn('order.paneltype', [1, 2]);
                } elseif (in_array("68", $phoneaccess) && !in_array("69", $phoneaccess)) {
                    $data = $data->where('order.paneltype', 1);
                } elseif (!in_array("68", $phoneaccess) && in_array("69", $phoneaccess)) {
                    $data = $data->where('order.paneltype', 2);
                }

                $search_as = $request->input('search_as',null);

                if ($search_as == 2) { //search as normal user or himself
                    $user_id = auth()->user()->id;
                    $ApproachingAssign = ApproachingAssign::where('orderTaker',$user_id)->first();

                    $data = $data->with('latestHistory', 'approach', 'reports')
                        ->groupBy('ophone')
                        ->select('order.*', DB::raw("GROUP_CONCAT(order.id) as order_ids"));

                    if (!empty($ApproachingAssign)) {
                        $data->where('approaching_user',$user_id);
//                        $date_range = $ApproachingAssign->date_range;
//                        if (!empty($date_range)) {
//                            $dates = explode('-', $date_range);
//                            $from = date('Y-m-d 00:00:00', strtotime($dates[0]));
//                            $too = date('Y-m-d 23:59:59', strtotime($dates[1]));
//                            $data->whereBetween('order.created_at', [$from, $too]);
//                        }

//                        if ($ApproachingAssign->status == "144") {
//                            $data->havingRaw("
//                                            (SELECT GROUP_CONCAT(report.orderId)
//                                             FROM report
//                                             WHERE report.orderId IN (SELECT o.id FROM `order` as o WHERE o.ophone = `order`.ophone)
//                                             AND report.pstatus = 14) = GROUP_CONCAT(`order`.id)
//                                        ");
//                        } elseif ($ApproachingAssign->status == "44") {
//                            $data->havingRaw("
//                                            (SELECT GROUP_CONCAT(report.orderId)
//                                             FROM report
//                                             WHERE report.orderId IN (SELECT o.id FROM `order` as o WHERE o.ophone = `order`.ophone)
//                                             AND report.pstatus = 4) = GROUP_CONCAT(`order`.id)
//                                        ");
//                        }else{
//                            $data->where('pstatus',$ApproachingAssign->status);
//                        }

                    }

                }else {


                    $data = $data->where(function ($q) use ($from, $too, $request) {
                        if (isset($request->order_taker_id)) {
                            $q->where(function ($q3) use ($request) {
                                $q3->whereHas('approach', function ($q2) use ($request) {
                                    $q2->where('userId', $request->order_taker_id);
                                })->orWhere('order.order_taker_id', $request->order_taker_id);
                            });
                        }
                        if (!empty($from) && !empty($too)) {
                            $q->whereBetween('order.created_at', [$from, $too]);
                        } else {
                            $q->whereDate('order.created_at', '<', Carbon::now()->subDays(20));
                        }
                        if ($request->has('statuss') && !is_null($request->statuss)) {
                            if ($request->statuss != "144" && $request->statuss != "44") {
                                $q->where('order.pstatus', $request->statuss);
                            }
                        }
                    });
// Status-specific logic
                    if ($request->statuss == "144") {
                        $data = $data->with('latestHistory', 'approach', 'reports')
                            ->groupBy('ophone')
                            ->select('order.*', DB::raw("GROUP_CONCAT(order.id) as order_ids"))
                            ->havingRaw("
            (SELECT GROUP_CONCAT(report.orderId)
             FROM report
             WHERE report.orderId IN (SELECT o.id FROM `order` as o WHERE o.ophone = `order`.ophone)
             AND report.pstatus = 14) = GROUP_CONCAT(`order`.id)
        ");
                    } elseif ($request->statuss == "44") {
                        $data = $data->with('latestHistory', 'approach', 'reports')
                            ->groupBy('ophone')
                            ->select('order.*', DB::raw("GROUP_CONCAT(order.id) as order_ids"))
                            ->havingRaw("
            (SELECT GROUP_CONCAT(report.orderId)
             FROM report
             WHERE report.orderId IN (SELECT o.id FROM `order` as o WHERE o.ophone = `order`.ophone)
             AND report.pstatus = 4) = GROUP_CONCAT(`order`.id)
        ");
                    } else {
                        $data = $data->with('latestHistory', 'approach', 'reports')
                            ->groupBy('ophone')
                            ->select('order.*', DB::raw("GROUP_CONCAT(order.id) as order_ids"));
                    }

                }
                if (!empty($request->recordsAllowed)) {
                    $data->limit($request->recordsAllowed); // Apply limit
                }
                $total_count = $data->pluck('id')->toArray();
                // Use a subquery to constrain results before pagination

                $subQuery = DB::table(DB::raw("({$data->toSql()}) as subquery"))
                    ->mergeBindings($data->getQuery());
                $sqlWithBindings = vsprintf(str_replace('?', "'%s'", $subQuery->toSql()), $subQuery->getBindings());


                $send_template =  isset($request->send_template) ? $request->send_template  : 0;
                if ($send_template) {
                    $template = $request->template;
                    // Get the SQL with placeholders
                    $sqlWithPlaceholders = $subQuery->toSql();

                    // Replace placeholders with actual bindings
                    $bindings = $subQuery->getBindings();
                    $fullSql = vsprintf(str_replace('?', "'%s'", $sqlWithPlaceholders), $bindings);

                    // Save query to SendTemplateEmail model
                    $send_email = new SendTemplateEmail();
                    $send_email->query_sql = $fullSql;
                    $send_email->data_type = 6;
                    $send_email->template_id = $template;
                    $send_email->limit_data = $request->input('limit_data',500);
                    $send_email->user_id = auth()->user()->id;
                    $send_email->save();
                }

                $paginatedData = $subQuery->paginate(20);




                return view('main.phone_quote.new.load_approaching', compact('paginatedData','total_count'));
            }

            if ($request->titlee == 'carrierupdate') {
                $data = AutoOrder::where('pstatus', '=', 17)
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
                    ->where('paneltype', '=', $ptype)
                    ->where(function ($q) use ($from, $too, $request, $setting, $from1, $too1) {
                        if (!empty($from) && !empty($too)) {
                            if ($from == $too) {
                                $q->whereDate('created_at', $from);
                            } else {
                                $q->whereBetween('created_at', [$from, $too]);
                            }
                        } else {
                            $q->where('created_at', '>=', Carbon::today()->subDays($setting->no_days));
                        }
                        if (!empty($from1) && !empty($too1)) {
                            if ($from1 == $too1) {
                                $q->whereDate('oauctiondate', $from1)->orWhereDate('dauctiondate', $from1);
                            } else {
                                $q->whereBetween('oauctiondate', [$from1, $too1])->orWhereBetween('dauctiondate', [$from1, $too1]);
                            }
                        }
                    })
                    ->where(function ($q) use ($request, $user) {
                        if (!empty($request->search_by) && !empty($request->keywords)) {
                            if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                if ($request->search_by == 'driverphoneno') {
                                    $q->whereHas('carriers', function ($q2) use ($request) {
                                        $q2->where('driverphoneno', 'like', '%' . $request->keywords . '%')
                                            ->orWhere('companyphoneno', 'like', '%' . $request->keywords . '%');
                                    });
                                } else {
                                    $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                                }
                            } else {
                                $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                            }
                        }
                        if ((isset($request->verify) && !empty($request->verify)) || (isset($request->negative) && !empty($request->negative))) {
                            $q->whereHas('qa_remarks', function ($q2) use ($request) {
                                if (isset($request->verify) && !empty($request->verify)) {
                                    $q2->where('verify', $request->verify);
                                }
                                if (isset($request->negative) && !empty($request->negative)) {
                                    $q2->where('negative', $request->negative);
                                }
                            });
                        }

                        if (isset($request->acutionaccounttitle) && !empty($request->acutionaccounttitle)) {
                            $q->where(function ($q2) use ($request) {
                                $q2->where('oacutionaccounttitle', $request->acutionaccounttitle)
                                    ->orWhere('dacutionaccounttitle', $request->acutionaccounttitle);
                            });
                            if (isset($request->acutionaccountname) && !empty($request->acutionaccountname)) {
                                $q->where(function ($q2) use ($request) {
                                    $q2->where('oacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%')
                                        ->orWhere('dacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%');
                                });
                            }
                        }
                    })
                    ->orderBy($request->sort_by, 'DESC')
                    ->paginate(20);
                return view('main.phone_quote.new.load', compact('data'))->render();
            }

            if ($request->titlee == 'onapproval') {
                $data = AutoOrder::where('pstatus', '=', 18)
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
                    ->where('paneltype', '=', $ptype)
                    ->where(function ($q) use ($from, $too, $request, $setting, $from1, $too1) {
                        if (!empty($from) && !empty($too)) {
                            if ($from == $too) {
                                $q->whereDate('created_at', $from);
                            } else {
                                $q->whereBetween('created_at', [$from, $too]);
                            }
                        } else {
                            $q->where('created_at', '>=', Carbon::today()->subDays($setting->no_days));
                        }
                        if (!empty($from1) && !empty($too1)) {
                            if ($from1 == $too1) {
                                $q->whereDate('oauctiondate', $from1)->orWhereDate('dauctiondate', $from1);
                            } else {
                                $q->whereBetween('oauctiondate', [$from1, $too1])->orWhereBetween('dauctiondate', [$from1, $too1]);
                            }
                        }
                    })
                    ->where(function ($q) use ($request, $user) {
                        if (!empty($request->search_by) && !empty($request->keywords)) {
                            if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                if ($request->search_by == 'driverphoneno') {
                                    $q->whereHas('carriers', function ($q2) use ($request) {
                                        $q2->where('driverphoneno', 'like', '%' . $request->keywords . '%')
                                            ->orWhere('companyphoneno', 'like', '%' . $request->keywords . '%');
                                    });
                                } else {
                                    $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                                }
                            } else {
                                $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                            }
                        }
                        if ((isset($request->verify) && !empty($request->verify)) || (isset($request->negative) && !empty($request->negative))) {
                            $q->whereHas('qa_remarks', function ($q2) use ($request) {
                                if (isset($request->verify) && !empty($request->verify)) {
                                    $q2->where('verify', $request->verify);
                                }
                                if (isset($request->negative) && !empty($request->negative)) {
                                    $q2->where('negative', $request->negative);
                                }
                            });
                        }

                        if (isset($request->acutionaccounttitle) && !empty($request->acutionaccounttitle)) {
                            $q->where(function ($q2) use ($request) {
                                $q2->where('oacutionaccounttitle', $request->acutionaccounttitle)
                                    ->orWhere('dacutionaccounttitle', $request->acutionaccounttitle);
                            });
                            if (isset($request->acutionaccountname) && !empty($request->acutionaccountname)) {
                                $q->where(function ($q2) use ($request) {
                                    $q2->where('oacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%')
                                        ->orWhere('dacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%');
                                });
                            }
                        }
                    })
                    ->orderBy($request->sort_by, 'DESC')
                    ->paginate(20);
                return view('main.phone_quote.new.load', compact('data'))->render();
            }
            if ($request->titlee == 'onapproval_cancel') {
                $data = AutoOrder::where('pstatus', '=', 19)
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
                    ->where('paneltype', '=', $ptype)
                    ->where(function ($q) use ($from, $too, $request, $setting, $from1, $too1) {
                        if (!empty($from) && !empty($too)) {
                            if ($from == $too) {
                                $q->whereDate('created_at', $from);
                            } else {
                                $q->whereBetween('created_at', [$from, $too]);
                            }
                        } else {
                            $q->where('created_at', '>=', Carbon::today()->subDays($setting->no_days));
                        }
                        if (!empty($from1) && !empty($too1)) {
                            if ($from1 == $too1) {
                                $q->whereDate('oauctiondate', $from1)->orWhereDate('dauctiondate', $from1);
                            } else {
                                $q->whereBetween('oauctiondate', [$from1, $too1])->orWhereBetween('dauctiondate', [$from1, $too1]);
                            }
                        }
                    })
                    ->where(function ($q) use ($request, $user) {
                        if (!empty($request->search_by) && !empty($request->keywords)) {
                            if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                if ($request->search_by == 'driverphoneno') {
                                    $q->whereHas('carriers', function ($q2) use ($request) {
                                        $q2->where('driverphoneno', 'like', '%' . $request->keywords . '%')
                                            ->orWhere('companyphoneno', 'like', '%' . $request->keywords . '%');
                                    });
                                } else {
                                    $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                                }
                            } else {
                                $q->where($request->search_by, 'like', '%' . $request->keywords . '%');
                            }
                        }
                        if ((isset($request->verify) && !empty($request->verify)) || (isset($request->negative) && !empty($request->negative))) {
                            $q->whereHas('qa_remarks', function ($q2) use ($request) {
                                if (isset($request->verify) && !empty($request->verify)) {
                                    $q2->where('verify', $request->verify);
                                }
                                if (isset($request->negative) && !empty($request->negative)) {
                                    $q2->where('negative', $request->negative);
                                }
                            });
                        }

                        if (isset($request->acutionaccounttitle) && !empty($request->acutionaccounttitle)) {
                            $q->where(function ($q2) use ($request) {
                                $q2->where('oacutionaccounttitle', $request->acutionaccounttitle)
                                    ->orWhere('dacutionaccounttitle', $request->acutionaccounttitle);
                            });
                            if (isset($request->acutionaccountname) && !empty($request->acutionaccountname)) {
                                $q->where(function ($q2) use ($request) {
                                    $q2->where('oacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%')
                                        ->orWhere('dacutionaccountname', 'LIKE', '%' . $request->acutionaccountname . '%');
                                });
                            }
                        }
                    })
                    ->orderBy($request->sort_by, 'DESC')
                    ->paginate(20);
                return view('main.phone_quote.new.load', compact('data'))->render();
            }
        }
    }

    public function return_data(Request $request)
    {
        if (Auth::check()) {
        } else {
            return redirect('/loginn/');
        }

        $ptype = $this->check_user_setting(Auth::user()->id);
        $setting = general_setting::first();

        $user = Auth::user();

        $acutionaccountname = '';
        $acutionaccounttitle = '';
        if (isset($request->acutionaccounttitle) && !empty($request->acutionaccounttitle)) {
            $acutionaccounttitle = $request->acutionaccounttitle;
            if (isset($request->acutionaccountname) && !empty($request->acutionaccountname)) {
                $acutionaccountname = $request->acutionaccountname;
            }
        }

        if (isset($request->val)) {
            if (!empty($request->val)) {
                $val = $request->val;
                $search_by = $request->search_by;
                if ($request->titlee == 'new') {
                    $data = AutoOrder::where('pstatus', '=', 0)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->where(function ($q) use ($search_by, $val, $user, $acutionaccountname) {
                            if (!empty($search_by) && !empty($val)) {
                                if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                    if ($search_by == 'driverphoneno') {
                                        $q->whereHas('carriers', function ($q2) use ($val) {
                                            $q2->where('driverphoneno', 'like', '%' . $val . '%')
                                                ->orWhere('companyphoneno', 'like', '%' . $val . '%');
                                        });
                                    } else {
                                        $q->where($search_by, 'like', '%' . $val . '%');
                                    }
                                } else {
                                    $q->where($search_by, 'like', '%' . $val . '%');
                                }
                            }

                            if (isset($acutionaccounttitle) && !empty($acutionaccounttitle)) {
                                $q->where(function ($q2) use ($acutionaccounttitle) {
                                    $q2->where('oacutionaccounttitle', $acutionaccounttitle)
                                        ->orWhere('dacutionaccounttitle', $acutionaccounttitle);
                                });
                                if (isset($acutionaccountname) && !empty($acutionaccountname)) {
                                    $q->where(function ($q2) use ($acutionaccountname) {
                                        $q2->where('oacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%')
                                            ->orWhere('dacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%');
                                    });
                                }
                            }
                        })
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'followup') {
                    $data = AutoOrder::where('pstatus', '=', 2)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->where(function ($q) use ($search_by, $val, $user, $acutionaccountname) {
                            if (!empty($search_by) && !empty($val)) {
                                if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                    if ($search_by == 'driverphoneno') {
                                        $q->whereHas('carriers', function ($q2) use ($val) {
                                            $q2->where('driverphoneno', 'like', '%' . $val . '%')
                                                ->orWhere('companyphoneno', 'like', '%' . $val . '%');
                                        });
                                    } else {
                                        $q->where($search_by, 'like', '%' . $val . '%');
                                    }
                                } else {
                                    $q->where($search_by, 'like', '%' . $val . '%');
                                }
                            }

                            if (isset($acutionaccounttitle) && !empty($acutionaccounttitle)) {
                                $q->where(function ($q2) use ($acutionaccounttitle) {
                                    $q2->where('oacutionaccounttitle', $acutionaccounttitle)
                                        ->orWhere('dacutionaccounttitle', $acutionaccounttitle);
                                });
                                if (isset($acutionaccountname) && !empty($acutionaccountname)) {
                                    $q->where(function ($q2) use ($acutionaccountname) {
                                        $q2->where('oacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%')
                                            ->orWhere('dacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%');
                                    });
                                }
                            }
                        })
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'interested') {
                    $data = AutoOrder::where('pstatus', '=', 1)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->where(function ($q) use ($search_by, $val, $user, $acutionaccountname) {
                            if (!empty($search_by) && !empty($val)) {
                                if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                    if ($search_by == 'driverphoneno') {
                                        $q->whereHas('carriers', function ($q2) use ($val) {
                                            $q2->where('driverphoneno', 'like', '%' . $val . '%')
                                                ->orWhere('companyphoneno', 'like', '%' . $val . '%');
                                        });
                                    } else {
                                        $q->where($search_by, 'like', '%' . $val . '%');
                                    }
                                } else {
                                    $q->where($search_by, 'like', '%' . $val . '%');
                                }
                            }

                            if (isset($acutionaccounttitle) && !empty($acutionaccounttitle)) {
                                $q->where(function ($q2) use ($acutionaccounttitle) {
                                    $q2->where('oacutionaccounttitle', $acutionaccounttitle)
                                        ->orWhere('dacutionaccounttitle', $acutionaccounttitle);
                                });
                                if (isset($acutionaccountname) && !empty($acutionaccountname)) {
                                    $q->where(function ($q2) use ($acutionaccountname) {
                                        $q2->where('oacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%')
                                            ->orWhere('dacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%');
                                    });
                                }
                            }
                        })
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'asking_low') {
                    $data = AutoOrder::where('pstatus', '=', 3)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->where(function ($q) use ($search_by, $val, $user, $acutionaccountname) {
                            if (!empty($search_by) && !empty($val)) {
                                if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                    if ($search_by == 'driverphoneno') {
                                        $q->whereHas('carriers', function ($q2) use ($val) {
                                            $q2->where('driverphoneno', 'like', '%' . $val . '%')
                                                ->orWhere('companyphoneno', 'like', '%' . $val . '%');
                                        });
                                    } else {
                                        $q->where($search_by, 'like', '%' . $val . '%');
                                    }
                                } else {
                                    $q->where($search_by, 'like', '%' . $val . '%');
                                }
                            }

                            if (isset($acutionaccounttitle) && !empty($acutionaccounttitle)) {
                                $q->where(function ($q2) use ($acutionaccounttitle) {
                                    $q2->where('oacutionaccounttitle', $acutionaccounttitle)
                                        ->orWhere('dacutionaccounttitle', $acutionaccounttitle);
                                });
                                if (isset($acutionaccountname) && !empty($acutionaccountname)) {
                                    $q->where(function ($q2) use ($acutionaccountname) {
                                        $q2->where('oacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%')
                                            ->orWhere('dacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%');
                                    });
                                }
                            }
                        })
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'not_interested') {
                    $data = AutoOrder::where('pstatus', '=', 4)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->where(function ($q) use ($search_by, $val, $user, $acutionaccountname) {
                            if (!empty($search_by) && !empty($val)) {
                                if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                    if ($search_by == 'driverphoneno') {
                                        $q->whereHas('carriers', function ($q2) use ($val) {
                                            $q2->where('driverphoneno', 'like', '%' . $val . '%')
                                                ->orWhere('companyphoneno', 'like', '%' . $val . '%');
                                        });
                                    } else {
                                        $q->where($search_by, 'like', '%' . $val . '%');
                                    }
                                } else {
                                    $q->where($search_by, 'like', '%' . $val . '%');
                                }
                            }

                            if (isset($acutionaccounttitle) && !empty($acutionaccounttitle)) {
                                $q->where(function ($q2) use ($acutionaccounttitle) {
                                    $q2->where('oacutionaccounttitle', $acutionaccounttitle)
                                        ->orWhere('dacutionaccounttitle', $acutionaccounttitle);
                                });
                                if (isset($acutionaccountname) && !empty($acutionaccountname)) {
                                    $q->where(function ($q2) use ($acutionaccountname) {
                                        $q2->where('oacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%')
                                            ->orWhere('dacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%');
                                    });
                                }
                            }
                        })
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'not_responding') {
                    $data = AutoOrder::where('pstatus', '=', 5)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->where(function ($q) use ($search_by, $val, $user, $acutionaccountname) {
                            if (!empty($search_by) && !empty($val)) {
                                if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                    if ($search_by == 'driverphoneno') {
                                        $q->whereHas('carriers', function ($q2) use ($val) {
                                            $q2->where('driverphoneno', 'like', '%' . $val . '%')
                                                ->orWhere('companyphoneno', 'like', '%' . $val . '%');
                                        });
                                    } else {
                                        $q->where($search_by, 'like', '%' . $val . '%');
                                    }
                                } else {
                                    $q->where($search_by, 'like', '%' . $val . '%');
                                }
                            }

                            if (isset($acutionaccounttitle) && !empty($acutionaccounttitle)) {
                                $q->where(function ($q2) use ($acutionaccounttitle) {
                                    $q2->where('oacutionaccounttitle', $acutionaccounttitle)
                                        ->orWhere('dacutionaccounttitle', $acutionaccounttitle);
                                });
                                if (isset($acutionaccountname) && !empty($acutionaccountname)) {
                                    $q->where(function ($q2) use ($acutionaccountname) {
                                        $q2->where('oacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%')
                                            ->orWhere('dacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%');
                                    });
                                }
                            }
                        })
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'time_quote') {
                    $data = AutoOrder::where('pstatus', '=', 6)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->where(function ($q) use ($search_by, $val, $user, $acutionaccountname) {
                            if (!empty($search_by) && !empty($val)) {
                                if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                    if ($search_by == 'driverphoneno') {
                                        $q->whereHas('carriers', function ($q2) use ($val) {
                                            $q2->where('driverphoneno', 'like', '%' . $val . '%')
                                                ->orWhere('companyphoneno', 'like', '%' . $val . '%');
                                        });
                                    } else {
                                        $q->where($search_by, 'like', '%' . $val . '%');
                                    }
                                } else {
                                    $q->where($search_by, 'like', '%' . $val . '%');
                                }
                            }

                            if (isset($acutionaccounttitle) && !empty($acutionaccounttitle)) {
                                $q->where(function ($q2) use ($acutionaccounttitle) {
                                    $q2->where('oacutionaccounttitle', $acutionaccounttitle)
                                        ->orWhere('dacutionaccounttitle', $acutionaccounttitle);
                                });
                                if (isset($acutionaccountname) && !empty($acutionaccountname)) {
                                    $q->where(function ($q2) use ($acutionaccountname) {
                                        $q2->where('oacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%')
                                            ->orWhere('dacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%');
                                    });
                                }
                            }
                        })
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'payment_missing') {
                    $data = AutoOrder::where('pstatus', '=', 7)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->where(function ($q) use ($search_by, $val, $user, $acutionaccountname) {
                            if (!empty($search_by) && !empty($val)) {
                                if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                    if ($search_by == 'driverphoneno') {
                                        $q->whereHas('carriers', function ($q2) use ($val) {
                                            $q2->where('driverphoneno', 'like', '%' . $val . '%')
                                                ->orWhere('companyphoneno', 'like', '%' . $val . '%');
                                        });
                                    } else {
                                        $q->where($search_by, 'like', '%' . $val . '%');
                                    }
                                } else {
                                    $q->where($search_by, 'like', '%' . $val . '%');
                                }
                            }

                            if (isset($acutionaccounttitle) && !empty($acutionaccounttitle)) {
                                $q->where(function ($q2) use ($acutionaccounttitle) {
                                    $q2->where('oacutionaccounttitle', $acutionaccounttitle)
                                        ->orWhere('dacutionaccounttitle', $acutionaccounttitle);
                                });
                                if (isset($acutionaccountname) && !empty($acutionaccountname)) {
                                    $q->where(function ($q2) use ($acutionaccountname) {
                                        $q2->where('oacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%')
                                            ->orWhere('dacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%');
                                    });
                                }
                            }
                        })
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'booked') {
                    $data = AutoOrder::where('pstatus', '=', 8)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->where(function ($q) use ($search_by, $val, $user, $acutionaccountname) {
                            if (!empty($search_by) && !empty($val)) {
                                if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                    if ($search_by == 'driverphoneno') {
                                        $q->whereHas('carriers', function ($q2) use ($val) {
                                            $q2->where('driverphoneno', 'like', '%' . $val . '%')
                                                ->orWhere('companyphoneno', 'like', '%' . $val . '%');
                                        });
                                    } else {
                                        $q->where($search_by, 'like', '%' . $val . '%');
                                    }
                                } else {
                                    $q->where($search_by, 'like', '%' . $val . '%');
                                }
                            }

                            if (isset($acutionaccounttitle) && !empty($acutionaccounttitle)) {
                                $q->where(function ($q2) use ($acutionaccounttitle) {
                                    $q2->where('oacutionaccounttitle', $acutionaccounttitle)
                                        ->orWhere('dacutionaccounttitle', $acutionaccounttitle);
                                });
                                if (isset($acutionaccountname) && !empty($acutionaccountname)) {
                                    $q->where(function ($q2) use ($acutionaccountname) {
                                        $q2->where('oacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%')
                                            ->orWhere('dacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%');
                                    });
                                }
                            }
                        })
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'listed') {
                    $data = AutoOrder::where('pstatus', '=', 9)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->where(function ($q) use ($search_by, $val, $user, $acutionaccountname) {
                            if (!empty($search_by) && !empty($val)) {
                                if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                    if ($search_by == 'driverphoneno') {
                                        $q->whereHas('carriers', function ($q2) use ($val) {
                                            $q2->where('driverphoneno', 'like', '%' . $val . '%')
                                                ->orWhere('companyphoneno', 'like', '%' . $val . '%');
                                        });
                                    } else {
                                        $q->where($search_by, 'like', '%' . $val . '%');
                                    }
                                } else {
                                    $q->where($search_by, 'like', '%' . $val . '%');
                                }
                            }

                            if (isset($acutionaccounttitle) && !empty($acutionaccounttitle)) {
                                $q->where(function ($q2) use ($acutionaccounttitle) {
                                    $q2->where('oacutionaccounttitle', $acutionaccounttitle)
                                        ->orWhere('dacutionaccounttitle', $acutionaccounttitle);
                                });
                                if (isset($acutionaccountname) && !empty($acutionaccountname)) {
                                    $q->where(function ($q2) use ($acutionaccountname) {
                                        $q2->where('oacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%')
                                            ->orWhere('dacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%');
                                    });
                                }
                            }
                        })
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'dispatch') {
                    $data = AutoOrder::where('pstatus', '=', 10)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->where(function ($q) use ($search_by, $val, $user, $acutionaccountname) {
                            if (!empty($search_by) && !empty($val)) {
                                if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                    if ($search_by == 'driverphoneno') {
                                        $q->whereHas('carriers', function ($q2) use ($val) {
                                            $q2->where('driverphoneno', 'like', '%' . $val . '%')
                                                ->orWhere('companyphoneno', 'like', '%' . $val . '%');
                                        });
                                    } else {
                                        $q->where($search_by, 'like', '%' . $val . '%');
                                    }
                                } else {
                                    $q->where($search_by, 'like', '%' . $val . '%');
                                }
                            }

                            if (isset($acutionaccounttitle) && !empty($acutionaccounttitle)) {
                                $q->where(function ($q2) use ($acutionaccounttitle) {
                                    $q2->where('oacutionaccounttitle', $acutionaccounttitle)
                                        ->orWhere('dacutionaccounttitle', $acutionaccounttitle);
                                });
                                if (isset($acutionaccountname) && !empty($acutionaccountname)) {
                                    $q->where(function ($q2) use ($acutionaccountname) {
                                        $q2->where('oacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%')
                                            ->orWhere('dacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%');
                                    });
                                }
                            }
                        })
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'picked_up_approval') {
                    $data = AutoOrder::where('pstatus', '=', 11)
                        ->where(function ($q) {
                            $q->where('approve_pickup', '=', 0)->orWhere('approve_pickup', '=', NULL);
                        })
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->where(function ($q) use ($search_by, $val, $user, $acutionaccountname) {
                            if (!empty($search_by) && !empty($val)) {
                                if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                    if ($search_by == 'driverphoneno') {
                                        $q->whereHas('carriers', function ($q2) use ($val) {
                                            $q2->where('driverphoneno', 'like', '%' . $val . '%')
                                                ->orWhere('companyphoneno', 'like', '%' . $val . '%');
                                        });
                                    } else {
                                        $q->where($search_by, 'like', '%' . $val . '%');
                                    }
                                } else {
                                    $q->where($search_by, 'like', '%' . $val . '%');
                                }
                            }

                            if (isset($acutionaccounttitle) && !empty($acutionaccounttitle)) {
                                $q->where(function ($q2) use ($acutionaccounttitle) {
                                    $q2->where('oacutionaccounttitle', $acutionaccounttitle)
                                        ->orWhere('dacutionaccounttitle', $acutionaccounttitle);
                                });
                                if (isset($acutionaccountname) && !empty($acutionaccountname)) {
                                    $q->where(function ($q2) use ($acutionaccountname) {
                                        $q2->where('oacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%')
                                            ->orWhere('dacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%');
                                    });
                                }
                            }
                        })
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'picked_up') {
                    $data = AutoOrder::where('pstatus', '=', 11)
                        ->where('approve_pickup', 1)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->where(function ($q) use ($search_by, $val, $user, $acutionaccountname) {
                            if (!empty($search_by) && !empty($val)) {
                                if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                    if ($search_by == 'driverphoneno') {
                                        $q->whereHas('carriers', function ($q2) use ($val) {
                                            $q2->where('driverphoneno', 'like', '%' . $val . '%')
                                                ->orWhere('companyphoneno', 'like', '%' . $val . '%');
                                        });
                                    } else {
                                        $q->where($search_by, 'like', '%' . $val . '%');
                                    }
                                } else {
                                    $q->where($search_by, 'like', '%' . $val . '%');
                                }
                            }

                            if (isset($acutionaccounttitle) && !empty($acutionaccounttitle)) {
                                $q->where(function ($q2) use ($acutionaccounttitle) {
                                    $q2->where('oacutionaccounttitle', $acutionaccounttitle)
                                        ->orWhere('dacutionaccounttitle', $acutionaccounttitle);
                                });
                                if (isset($acutionaccountname) && !empty($acutionaccountname)) {
                                    $q->where(function ($q2) use ($acutionaccountname) {
                                        $q2->where('oacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%')
                                            ->orWhere('dacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%');
                                    });
                                }
                            }
                        })
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'deliver_approval') {
                    $data = AutoOrder::where('pstatus', '=', 12)
                        ->where(function ($q) {
                            $q->where('approve_deliver', '=', 0)->orWhere('approve_deliver', '=', NULL);
                        })
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->where(function ($q) use ($search_by, $val, $user, $acutionaccountname) {
                            if (!empty($search_by) && !empty($val)) {
                                if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                    if ($search_by == 'driverphoneno') {
                                        $q->whereHas('carriers', function ($q2) use ($val) {
                                            $q2->where('driverphoneno', 'like', '%' . $val . '%')
                                                ->orWhere('companyphoneno', 'like', '%' . $val . '%');
                                        });
                                    } else {
                                        $q->where($search_by, 'like', '%' . $val . '%');
                                    }
                                } else {
                                    $q->where($search_by, 'like', '%' . $val . '%');
                                }
                            }

                            if (isset($acutionaccounttitle) && !empty($acutionaccounttitle)) {
                                $q->where(function ($q2) use ($acutionaccounttitle) {
                                    $q2->where('oacutionaccounttitle', $acutionaccounttitle)
                                        ->orWhere('dacutionaccounttitle', $acutionaccounttitle);
                                });
                                if (isset($acutionaccountname) && !empty($acutionaccountname)) {
                                    $q->where(function ($q2) use ($acutionaccountname) {
                                        $q2->where('oacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%')
                                            ->orWhere('dacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%');
                                    });
                                }
                            }
                        })
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'schedule_for_delivery') {
                    $data = AutoOrder::where('pstatus', '=', 12)
                        ->where('approve_deliver', 2)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->where(function ($q) use ($search_by, $val, $user, $acutionaccountname) {
                            if (!empty($search_by) && !empty($val)) {
                                if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                    if ($search_by == 'driverphoneno') {
                                        $q->whereHas('carriers', function ($q2) use ($val) {
                                            $q2->where('driverphoneno', 'like', '%' . $val . '%')
                                                ->orWhere('companyphoneno', 'like', '%' . $val . '%');
                                        });
                                    } else {
                                        $q->where($search_by, 'like', '%' . $val . '%');
                                    }
                                } else {
                                    $q->where($search_by, 'like', '%' . $val . '%');
                                }
                            }

                            if (isset($acutionaccounttitle) && !empty($acutionaccounttitle)) {
                                $q->where(function ($q2) use ($acutionaccounttitle) {
                                    $q2->where('oacutionaccounttitle', $acutionaccounttitle)
                                        ->orWhere('dacutionaccounttitle', $acutionaccounttitle);
                                });
                                if (isset($acutionaccountname) && !empty($acutionaccountname)) {
                                    $q->where(function ($q2) use ($acutionaccountname) {
                                        $q2->where('oacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%')
                                            ->orWhere('dacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%');
                                    });
                                }
                            }
                        })
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'delivered') {
                    $data = AutoOrder::where('pstatus', '=', 12)
                        ->where('approve_deliver', 1)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->where(function ($q) use ($search_by, $val, $user, $acutionaccountname) {
                            if (!empty($search_by) && !empty($val)) {
                                if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                    if ($search_by == 'driverphoneno') {
                                        $q->whereHas('carriers', function ($q2) use ($val) {
                                            $q2->where('driverphoneno', 'like', '%' . $val . '%')
                                                ->orWhere('companyphoneno', 'like', '%' . $val . '%');
                                        });
                                    } else {
                                        $q->where($search_by, 'like', '%' . $val . '%');
                                    }
                                } else {
                                    $q->where($search_by, 'like', '%' . $val . '%');
                                }
                            }

                            if (isset($acutionaccounttitle) && !empty($acutionaccounttitle)) {
                                $q->where(function ($q2) use ($acutionaccounttitle) {
                                    $q2->where('oacutionaccounttitle', $acutionaccounttitle)
                                        ->orWhere('dacutionaccounttitle', $acutionaccounttitle);
                                });
                                if (isset($acutionaccountname) && !empty($acutionaccountname)) {
                                    $q->where(function ($q2) use ($acutionaccountname) {
                                        $q2->where('oacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%')
                                            ->orWhere('dacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%');
                                    });
                                }
                            }
                        })
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'completed') {
                    $data = AutoOrder::where('pstatus', '=', 13)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->where(function ($q) use ($search_by, $val, $user, $acutionaccountname) {
                            if (!empty($search_by) && !empty($val)) {
                                if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                    if ($search_by == 'driverphoneno') {
                                        $q->whereHas('carriers', function ($q2) use ($val) {
                                            $q2->where('driverphoneno', 'like', '%' . $val . '%')
                                                ->orWhere('companyphoneno', 'like', '%' . $val . '%');
                                        });
                                    } else {
                                        $q->where($search_by, 'like', '%' . $val . '%');
                                    }
                                } else {
                                    $q->where($search_by, 'like', '%' . $val . '%');
                                }
                            }

                            if (isset($acutionaccounttitle) && !empty($acutionaccounttitle)) {
                                $q->where(function ($q2) use ($acutionaccounttitle) {
                                    $q2->where('oacutionaccounttitle', $acutionaccounttitle)
                                        ->orWhere('dacutionaccounttitle', $acutionaccounttitle);
                                });
                                if (isset($acutionaccountname) && !empty($acutionaccountname)) {
                                    $q->where(function ($q2) use ($acutionaccountname) {
                                        $q2->where('oacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%')
                                            ->orWhere('dacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%');
                                    });
                                }
                            }
                        })
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'cancel') {
                    $data = AutoOrder::where('pstatus', '=', 14)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->where(function ($q) use ($search_by, $val, $user, $acutionaccountname) {
                            if (!empty($search_by) && !empty($val)) {
                                if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                    if ($search_by == 'driverphoneno') {
                                        $q->whereHas('carriers', function ($q2) use ($val) {
                                            $q2->where('driverphoneno', 'like', '%' . $val . '%')
                                                ->orWhere('companyphoneno', 'like', '%' . $val . '%');
                                        });
                                    } else {
                                        $q->where($search_by, 'like', '%' . $val . '%');
                                    }
                                } else {
                                    $q->where($search_by, 'like', '%' . $val . '%');
                                }
                            }

                            if (isset($acutionaccounttitle) && !empty($acutionaccounttitle)) {
                                $q->where(function ($q2) use ($acutionaccounttitle) {
                                    $q2->where('oacutionaccounttitle', $acutionaccounttitle)
                                        ->orWhere('dacutionaccounttitle', $acutionaccounttitle);
                                });
                                if (isset($acutionaccountname) && !empty($acutionaccountname)) {
                                    $q->where(function ($q2) use ($acutionaccountname) {
                                        $q2->where('oacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%')
                                            ->orWhere('dacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%');
                                    });
                                }
                            }
                        })
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'deleted') {
                    $data = AutoOrder::where('pstatus', '=', 15)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->where(function ($q) use ($search_by, $val, $user, $acutionaccountname) {
                            if (!empty($search_by) && !empty($val)) {
                                if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                    if ($search_by == 'driverphoneno') {
                                        $q->whereHas('carriers', function ($q2) use ($val) {
                                            $q2->where('driverphoneno', 'like', '%' . $val . '%')
                                                ->orWhere('companyphoneno', 'like', '%' . $val . '%');
                                        });
                                    } else {
                                        $q->where($search_by, 'like', '%' . $val . '%');
                                    }
                                } else {
                                    $q->where($search_by, 'like', '%' . $val . '%');
                                }
                            }

                            if (isset($acutionaccounttitle) && !empty($acutionaccounttitle)) {
                                $q->where(function ($q2) use ($acutionaccounttitle) {
                                    $q2->where('oacutionaccounttitle', $acutionaccounttitle)
                                        ->orWhere('dacutionaccounttitle', $acutionaccounttitle);
                                });
                                if (isset($acutionaccountname) && !empty($acutionaccountname)) {
                                    $q->where(function ($q2) use ($acutionaccountname) {
                                        $q2->where('oacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%')
                                            ->orWhere('dacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%');
                                    });
                                }
                            }
                        })
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'owns_money') {
                    $data = AutoOrder::where(function ($q) {
                        $q->where(function ($q2) {
                            $q2->where('pstatus', '>=', 7)->where('pstatus', '<=', 14);
                        })->orWhere('pstatus', 18);
                    })
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
                        ->where('paneltype', '=', $ptype)
                        // ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->where(function ($q) use ($search_by, $val, $user, $request) {
                            if (isset($request->we_us_driver)) {
                                $q->where('we_us_driver', '=', $request->we_us_driver);
                            }
                            if (isset($request->owes)) {
                                $q->where('owes_money', 1)->where('owes', '>', 0);
                            }
                            if (isset($request->vehicle)) {
                                $q->where('vehicle', $request->vehicle);
                            }
                            if (!empty($search_by) && !empty($val)) {
                                if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                    if ($search_by == 'driverphoneno') {
                                        $q->whereHas('carriers', function ($q2) use ($val) {
                                            $q2->where('driverphoneno', 'like', '%' . $val . '%')
                                                ->orWhere('companyphoneno', 'like', '%' . $val . '%');
                                        });
                                    } else {
                                        $q->where($search_by, 'like', '%' . $val . '%');
                                    }
                                } else {
                                    $q->where($search_by, 'like', '%' . $val . '%');
                                }
                            }
                            $q->whereBetween('created_at', [\Carbon\Carbon::now()->firstOfMonth()->format('Y-m-d 00:00:00'), \Carbon\Carbon::now()->format('Y-m-d 23:59:59')]);
                        })
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.owes_load', compact('data'));
                }
                if ($request->titlee == 'carrierupdate') {
                    $data = AutoOrder::where('pstatus', '=', 17)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->where(function ($q) use ($search_by, $val, $user, $acutionaccountname) {
                            if (!empty($search_by) && !empty($val)) {
                                if ($user->userRole->name == 'Delivery Boy' || $user->userRole->name == 'Admin') {
                                    if ($search_by == 'driverphoneno') {
                                        $q->whereHas('carriers', function ($q2) use ($val) {
                                            $q2->where('driverphoneno', 'like', '%' . $val . '%')
                                                ->orWhere('companyphoneno', 'like', '%' . $val . '%');
                                        });
                                    } else {
                                        $q->where($search_by, 'like', '%' . $val . '%');
                                    }
                                } else {
                                    $q->where($search_by, 'like', '%' . $val . '%');
                                }
                            }

                            if (isset($acutionaccounttitle) && !empty($acutionaccounttitle)) {
                                $q->where(function ($q2) use ($acutionaccounttitle) {
                                    $q2->where('oacutionaccounttitle', $acutionaccounttitle)
                                        ->orWhere('dacutionaccounttitle', $acutionaccounttitle);
                                });
                                if (isset($acutionaccountname) && !empty($acutionaccountname)) {
                                    $q->where(function ($q2) use ($acutionaccountname) {
                                        $q2->where('oacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%')
                                            ->orWhere('dacutionaccountname', 'LIKE', '%' . $acutionaccountname . '%');
                                    });
                                }
                            }
                        })
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
            } else {
                if ($request->titlee == 'new') {
                    $data = AutoOrder::where('pstatus', '=', 0)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'followup') {
                    $data = AutoOrder::where('pstatus', '=', 2)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'interested') {
                    $data = AutoOrder::where('pstatus', '=', 1)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'asking_low') {
                    $data = AutoOrder::where('pstatus', '=', 3)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'not_interested') {
                    $data = AutoOrder::where('pstatus', '=', 4)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'not_responding') {
                    $data = AutoOrder::where('pstatus', '=', 5)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'time_quote') {
                    $data = AutoOrder::where('pstatus', '=', 6)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'payment_missing') {
                    $data = AutoOrder::where('pstatus', '=', 7)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'booked') {
                    $data = AutoOrder::where('pstatus', '=', 8)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'listed') {
                    $data = AutoOrder::where('pstatus', '=', 9)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'dispatch') {
                    $data = AutoOrder::where('pstatus', '=', 10)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'picked_up_approval') {
                    $data = AutoOrder::where('pstatus', '=', 11)
                        ->where(function ($q) {
                            $q->where('approve_pickup', '=', 0)->orWhere('approve_pickup', '=', NULL);
                        })
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'picked_up') {
                    $data = AutoOrder::where('pstatus', '=', 11)
                        ->where('approve_pickup', 1)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'deliver_approval') {
                    $data = AutoOrder::where('pstatus', '=', 12)
                        ->where(function ($q) {
                            $q->where('approve_deliver', '=', 0)->orWhere('approve_deliver', '=', NULL);
                        })
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'schedule_for_delivery') {
                    $data = AutoOrder::where('pstatus', '=', 12)
                        ->where('approve_deliver', 2)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'delivered') {
                    $data = AutoOrder::where('pstatus', '=', 12)
                        ->where('approve_deliver', 1)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'completed') {
                    $data = AutoOrder::where('pstatus', '=', 13)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'cancel') {
                    $data = AutoOrder::where('pstatus', '=', 14)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'deleted') {
                    $data = AutoOrder::where('pstatus', '=', 15)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'owns_money') {
                    $data = AutoOrder::where(function ($q) {
                        $q->where(function ($q2) {
                            $q2->where('pstatus', '>=', 7)->where('pstatus', '<=', 14);
                        })->orWhere('pstatus', 18);
                    })
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
                            $q->whereBetween('created_at', [\Carbon\Carbon::now()->firstOfMonth()->format('Y-m-d 00:00:00'), \Carbon\Carbon::now()->format('Y-m-d 23:59:59')]);
                        })
                        ->where('paneltype', '=', $ptype)
                        // ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.load', compact('data'));
                }
                if ($request->titlee == 'carrierupdate') {
                    $data = AutoOrder::where('pstatus', '=', 17)
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
                        ->where('paneltype', '=', $ptype)
                        ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                        ->orderBy('id', 'DESC')->paginate(20);
                    return view('main.phone_quote.new.owes_load', compact('data'));
                }
            }
        } else {
            if ($request->titlee == 'new') {
                $data = AutoOrder::where('pstatus', '=', 0)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.load', compact('data'));
            }
            if ($request->titlee == 'followup') {
                $data = AutoOrder::where('pstatus', '=', 2)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.load', compact('data'));
            }
            if ($request->titlee == 'interested') {
                $data = AutoOrder::where('pstatus', '=', 1)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.load', compact('data'));
            }
            if ($request->titlee == 'asking_low') {
                $data = AutoOrder::where('pstatus', '=', 3)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.load', compact('data'));
            }

            if ($request->titlee == 'not_interested') {
                $data = AutoOrder::where('pstatus', '=', 4)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.load', compact('data'));
            }
            if ($request->titlee == 'not_responding') {
                $data = AutoOrder::where('pstatus', '=', 5)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.load', compact('data'));
            }
            if ($request->titlee == 'time_quote') {
                $data = AutoOrder::where('pstatus', '=', 6)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.load', compact('data'));
            }
            if ($request->titlee == 'payment_missing') {
                $data = AutoOrder::where('pstatus', '=', 7)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.load', compact('data'));
            }
            if ($request->titlee == 'booked') {
                $data = AutoOrder::where('pstatus', '=', 8)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.load', compact('data'));
            }
            if ($request->titlee == 'listed') {
                $data = AutoOrder::where('pstatus', '=', 9)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.load', compact('data'));
            }
            if ($request->titlee == 'dispatch') {
                $data = AutoOrder::where('pstatus', '=', 10)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.load', compact('data'));
            }
            if ($request->titlee == 'picked_up_approval') {
                $data = AutoOrder::where('pstatus', '=', 11)
                    ->where(function ($q) {
                        $q->where('approve_pickup', '=', 0)->orWhere('approve_pickup', '=', NULL);
                    })
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.load', compact('data'));
            }
            if ($request->titlee == 'picked_up') {
                $data = AutoOrder::where('pstatus', '=', 11)
                    ->where('approve_pickup', 1)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.load', compact('data'));
            }
            if ($request->titlee == 'deliver_approval') {
                $data = AutoOrder::where('pstatus', '=', 12)
                    ->where(function ($q) {
                        $q->where('approve_deliver', '=', 0)->orWhere('approve_deliver', '=', NULL);
                    })
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.load', compact('data'));
            }
            if ($request->titlee == 'schedule_for_delivery') {
                $data = AutoOrder::where('pstatus', '=', 12)
                    ->where('approve_deliver', 2)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.load', compact('data'));
            }
            if ($request->titlee == 'delivered') {
                $data = AutoOrder::where('pstatus', '=', 12)
                    ->where('approve_deliver', 1)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.load', compact('data'));
            }
            if ($request->titlee == 'completed') {
                $data = AutoOrder::where('pstatus', '=', 13)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.load', compact('data'));
            }
            if ($request->titlee == 'cancel') {
                $data = AutoOrder::where('pstatus', '=', 14)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.load', compact('data'));
            }
            if ($request->titlee == 'deleted') {
                $data = AutoOrder::where('pstatus', '=', 15)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.load', compact('data'));
            }
            if ($request->titlee == 'owns_money') {
                // $data = AutoOrder::where('pstatus', '=', 16)
                $data = AutoOrder::where(function ($q) {
                    $q->where(function ($q2) {
                        $q2->where('pstatus', '>=', 7)->where('pstatus', '<=', 14);
                    })->orWhere('pstatus', 18);
                })
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
                        $q->whereBetween('created_at', [\Carbon\Carbon::now()->firstOfMonth()->format('Y-m-d 00:00:00'), \Carbon\Carbon::now()->format('Y-m-d 23:59:59')]);
                    })
                    ->where('paneltype', '=', $ptype)
                    // ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.owes_load', compact('data'));
            }
            if ($request->titlee == 'carrierupdate') {
                $data = AutoOrder::where('pstatus', '=', 17)
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
                    ->where('paneltype', '=', $ptype)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->orderBy('id', 'DESC')->paginate(20);
                return view('main.phone_quote.new.load', compact('data'));
            }
        }
    }

    public function notes_save(Request $request)
    {
        if (Auth::check()) {
        } else {
            return redirect('/loginn/');
        }

        if (isset($request->notes_value)) {
            $notes_value = $request->notes_value;
            // $notes = notes::where('user_id', '=', Auth::user()->id)->first();
            // if (empty($notes)) {
            //     $notes = new notes();
            //     $notes->user_id = Auth::user()->id;
            //     $notes->auto_save = $notes_value;
            //     $notes->save();
            // } else {
            //     $notes->auto_save = $notes_value;
            //     $notes->save();
            // }

            $notes = new notes();
            $notes->user_id = Auth::user()->id;
            $notes->auto_save = $notes_value;
            $notes->save();
        }
    }

    public function get_notes()
    {
        $notes = notes::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();

        return view('layouts.view_notes', compact('notes'));
    }

    public function delete_notes(Request $request)
    {
        notes::destroy($request->id);

        return "Delete";
    }

    public function check_user_setting($user_id)
    {
        $p_type = 1;
        $usersetting = user_setting::where('user_id', '=', $user_id)->first();
        if (!empty($usersetting)) {
            $p_type = $usersetting['penal_type'];
        }
        return $p_type;
    }

    public function penal_type(Request $request)
    {
        $penaltype = $request->panel_type;
        // echo "<pre>";
        // print_r($penaltype);
        // exit();
        $usersetting = user_setting::where('user_id', '=', Auth::user()->id)->first();
        if (empty($usersetting)) {

            $usersetting = new user_setting();
            $usersetting->penal_type = $penaltype;
            $usersetting->user_id = Auth::user()->id;
            $usersetting->save();
        } else {
            $usersetting->penal_type = $penaltype;
            $usersetting->save();
        }

        // dd($usersetting->toArray());

        return redirect()->back();
    }

    public function call_type(Request $request)
    {
        $calltype = $request->call_type;
        // echo "<pre>";
        // print_r($calltype);
        // exit();
        $usersetting = user_setting::where('user_id', '=', Auth::user()->id)->first();
        if (empty($usersetting)) {

            $usersetting = new user_setting();
            $usersetting->call_type = $calltype;
            $usersetting->user_id = Auth::user()->id;
            $usersetting->save();
        } else {
            $usersetting->call_type = $calltype;
            $usersetting->save();
        }

        // dd($usersetting->toArray());

        return redirect()->back();
    }

    public function trash_order(Request $request)
    {

        $ps_deleted = AutoOrder::find($request->orderid);
        $ps_deleted->pstatus = 15;
        $ps_deleted->save();

        $reportstatus = new report();
        $reportstatus->userId = Auth::user()->id;
        $reportstatus->orderId = $request->orderid;
        $reportstatus->pstatus = 15;
        $reportstatus->save();

        $singlereport = singlereport::where('orderId', '=', $request->orderid)->first();
        if (!empty($singlereport)) {
            $singlereport->userId = Auth::user()->id;
            $singlereport->pstatus = 15;
            $singlereport->save();
        }


        $callhistory = new call_history();
        $callhistory->userId = Auth::user()->id;
        $callhistory->orderId = $request->orderid;
        $callhistory->history = 'deleted by' . ' ' . Auth::user()->name;
        $callhistory->save();


        return redirect()->back();
    }

    public function old_shipa1(Request $request)
    {
        if (Auth::check()) {
            $data2 = DB::table('order')->select('*')->orderBy('id', 'DESC')->paginate(20);
            if ($request->ajax()) {
                return view('main.phone_quote.new.load_old_shipa1', compact('data2'))->render();
            } else {
                return view('main.phone_quote.new.old_shipa1', compact('data2'));
            }
        }
    }


    public function old_previous(Request $request)
    {
        if (Auth::check()) {
            $ocity = str_replace(' ', '', base64_decode($request->ocity));
            $dcity = str_replace(' ', '', base64_decode($request->dcity));

            $data2 = AutoOrder::where('originstate', 'LIKE', '%' . $ocity . '%')
                ->where('destinationstate', 'LIKE', '%' . $dcity . '%')
                ->whereNotIn('pstatus', ['0', '3', '6', '7'])
                ->where('payment', '<>', NULL)
                ->orderBy('created_at', 'DESC')->skip(1)->limit(25)->get();

            return view('main.phone_quote.new.old_previous_order', compact('data2'));
        }
    }

    public function old_cards_shipa1(Request $request)
    {

        if (Auth::check()) {
            $card = $request->creditCard;
            $cardInfo = DB::connection('mysql2')->table('order')->where('id', $card)->orderBy('id', 'DESC')->first();
            if (!empty($cardInfo)) {
                $cardIn = (array) $cardInfo;
                $id = $cardIn['id'];
                $phone = $cardIn['mainPhNum'];
                $email = $cardIn['oemail'];
                $ophone = $cardIn['ophone'];

                $getPreviousData = DB::connection('mysql2')->select("select id,mainPhNum,ophone,oemail, card_name ,card_last_name ,card_type ,card_number, card_sec,card_exp,card_mm ,card_yyyy ,mainPhNum  ,ophone ,oemail from `order` where card_number!='' AND id!='$id' ORDER BY id DESC");

                if (count($getPreviousData) > 0) {
                    $carriers = array();
                    if (count($getPreviousData) > 0) {
                        foreach ($getPreviousData as $val) {
                            $selectCarrier = (array) $val;
                            $card_number = str_replace('^*-', '', $selectCarrier['card_number']);
                            if ($phone == $selectCarrier['mainPhNum'] && $phone != '') {
                                if ($card_number != '') {
                                    array_push($carriers, $selectCarrier);
                                }
                            } elseif ($phone == $selectCarrier['ophone'] && $phone != '') {
                                if ($card_number != '') {
                                    array_push($carriers, $selectCarrier);
                                }
                            } elseif ($email == $selectCarrier['oemail'] && $email != '') {
                                if ($card_number != '') {
                                    array_push($carriers, $selectCarrier);
                                }
                            }
                        }
                        echo json_encode($carriers);
                    }
                } else {
                    echo json_encode(0);
                }
            } else {
                echo json_encode(0);
            }
        }
    }


    public function return_data_shipa1(Request $request)
    {
        if (Auth::check()) {

            $search_by = $request->search_by;
            $val = $request->val;
            $data2 = DB::connection('mysql2')->table('order')->select('*')->where($search_by, 'like', "%$val%")->orderBy('id', 'DESC')->paginate(20);
            return view('main.phone_quote.new.load_old_shipa1', compact('data2'))->render();
        }
    }

    public function move_to_new($id)
    {
        if (Auth::check()) {
            $data = DB::connection('mysql2')->table('order')->where('id', '=', $id)->first();

            $autoorder_save = new AutoOrder();

            $autoorder_save->old_code = $data->id;
            $autoorder_save->originzip = $data->originzip;
            $autoorder_save->originstate = $data->originstate;
            $autoorder_save->origincity = $data->origincity;
            $autoorder_save->originzsc = $data->origincity . ',' . $data->originzip . ',' . $data->originstate;
            $autoorder_save->oterminal = $data->oterminal;
            $autoorder_save->oauction = $data->oauction;
            $autoorder_save->oauctionpho = $data->oauctionpho;
            $autoorder_save->destinationzip = $data->destinationzip;
            $autoorder_save->destinationstate = $data->destinationstate;
            $autoorder_save->destinationcity = $data->destinationcity;
            $autoorder_save->destinationzsc = $data->destinationcity . ',' . $data->destinationstate . ',' . $data->destinationzip;
            $autoorder_save->dterminal = $data->dterminal;
            $autoorder_save->dauction = $data->dauction;
            $autoorder_save->dauctionpho = $data->dauctionpho;
            $autoorder_save->delivery_when = $data->shippingdate;


            $data->condition = str_replace("-", "", $data->condition);
            $data->condition = str_replace("operable", "1", $data->condition);
            $data->condition = str_replace("non-running", "2", $data->condition);
            $autoorder_save->condition = $data->condition;

            $data->transport = str_replace("-", "", $data->transport);
            $data->transport = str_replace("open", "1", $data->transport);
            $data->transport = str_replace("enclosed", "2", $data->transport);

            $autoorder_save->transport = $data->transport;


            $data->lengthft = str_replace("-", "", $data->lengthft);
            $autoorder_save->length_ft = $data->lengthft;

            $data->lengthin = str_replace("-", "", $data->lengthin);
            $autoorder_save->length_in = $data->lengthin;

            $data->widthft = str_replace("-", "", $data->widthft);
            $autoorder_save->width_ft = $data->widthft;

            $data->widthin = str_replace("-", "", $data->widthin);
            $autoorder_save->width_in = $data->widthin;

            $data->heigthft = str_replace("-", "", $data->heigthft);
            $autoorder_save->height_ft = $data->heigthft;

            $data->heigthin = str_replace("-", "", $data->heigthin);
            $autoorder_save->height_in = $data->heigthin;

            $data->year = str_replace("-", "", $data->year);
            $autoorder_save->year = $data->year;

            $data->make = str_replace("-", "", $data->make);
            $autoorder_save->make = $data->make;

            $data->model = str_replace("-", "", $data->model);
            $autoorder_save->model = $data->model;

            $autoorder_save->ymk = $data->heading;
            if (is_numeric($data->payment)) {
                $autoorder_save->payment = $data->payment;
            }
            $autoorder_save->pstatus = 0;
            $autoorder_save->paneltype = $data->order_type;
            $autoorder_save->vehicle_available_date = $data->vdate;
            $autoorder_save->oname = $data->oname;
            $autoorder_save->oemail = $data->oemail;
            $autoorder_save->oemail = $data->oemail;
            $autoorder_save->main_ph = $data->mainPh;

            $autoorder_save->ophone = $data->ophone . '*^' . $data->ophone2 . '*^' . $data->ophone3;
            $autoorder_save->obuyer_no = $data->obuyer_no;
            $autoorder_save->oaddress = $data->oaddress;
            $autoorder_save->oaddress2 = $data->oaddress2;
            $autoorder_save->dname = $data->dname;
            $autoorder_save->demail = $data->demail;
            $autoorder_save->dphone = $data->dphone . '*^' . $data->dphone2 . '*^' . $data->dphone3;
            $autoorder_save->daddress = $data->daddress;
            $autoorder_save->daddress2 = $data->daddress2;
            $autoorder_save->add_info = $data->add_info;
            $autoorder_save->weight = $data->weight;
            $autoorder_save->cstate = $data->cstate;
            $autoorder_save->czip = $data->czip;
            $autoorder_save->ccity = $data->ccity;
            $autoorder_save->czsc = $data->ccity . ',' . $data->cstate . ',' . $data->czip;
            $autoorder_save->pickup_date = date('Y-m-d', strtotime($data->pickup_date));
            $autoorder_save->delivery_date = date('Y-m-d', strtotime($data->delivery_date));
            $autoorder_save->pickup_when = $data->pickup_when;
            $autoorder_save->delivery_when = $data->delivery_when;
            if (is_numeric($data->cod_cop)) {
                $autoorder_save->cod_cop = $data->cod_cop;
            }

            $autoorder_save->payment_method = $data->payment_method;
            $autoorder_save->cod_cop_loc = $data->cod_cop_loc;
            if (is_numeric($data->balance)) {
                $autoorder_save->balance = $data->balance;
            }

            $autoorder_save->balance_method = $data->balance_method;
            $autoorder_save->balance_time = $data->balance_time;
            $autoorder_save->terms = $data->terms;
            $autoorder_save->additional_2 = $data->additional_2;
            $data->vin_num = str_replace("-", "", $data->vin_num);
            $autoorder_save->vin_no = $data->vin_num;
            $autoorder_save->in_auction = $data->auction;
            $autoorder_save->key_has = $data->vkey;


            $data->port_title = str_replace("-", "", $data->port_title);
            $data->port_title = str_replace("no_need", "false", $data->port_title);
            $data->port_title = str_replace("need", "true", $data->port_title);


            $autoorder_save->port_title = $data->port_title;


            $autoorder_save->portterminal = $data->port_terminal;
            $autoorder_save->cname = $data->custName;
            $autoorder_save->booking_confirm = $data->booking_confirm;
            $autoorder_save->company_name = $data->company_name;
            if (is_numeric($data->company_price)) {
                $autoorder_save->company_price = $data->company_price;
            }
            $autoorder_save->load_method = $data->load_method;
            $autoorder_save->originzip = $data->originzip;
            $autoorder_save->unload_method = $data->unload_method;
            $autoorder_save->owes_money = $data->owes_money;
            $autoorder_save->need_deposit = $data->need_deposit;
            if (is_numeric($data->deposit_amount)) {
                $autoorder_save->deposit_amount = $data->deposit_amount;
            }
            if (is_numeric($data->pay_carrier)) {
                $autoorder_save->pay_carrier = $data->pay_carrier;
            }

            $data->type = str_replace("-", "", $data->type);
            $autoorder_save->type = $data->type;
            if ($data->type == ' heavy Equipment') {
                $autoorder_save->car_type = '2';
            } else {
                $autoorder_save->car_type = '1';
            }

            $data->vehicle_opt = str_replace("-", "", $data->vehicle_opt);
            $autoorder_save->vehicle_opt = $data->vehicle_opt;


            $autoorder_save->comments = $data->comments;

            $autoorder_save->save();

            ///carrier table
            /*
            $carrier_save = new carrier();
            $carrier_save->orderId = $autoorder_save->id;
            $carrier_save->companyname = $data->company_name;
            if (!empty($data->company_address)) {
                $carrier_save->location = $data->company_address;
            }
            $carrier_save->mcno = $data->mc;
            $carrier_save->est_pickupdate = $data->est_pick_date;
            $carrier_save->est_deliverydate = $data->est_delivery_date;
            $carrier_save->companyphoneno = $data->company_phone;
            $carrier_save->driverphoneno = $data->driver_phone;
            $carrier_save->save();
             */
            ////order payment
            $orderpayment_save = new orderpayment();
            $orderpayment_save->orderId = $autoorder_save->id;
            $orderpayment_save->your_name = $data->yourname;
            $orderpayment_save->signature = $data->signature;
            $orderpayment_save->payconfirm = $data->confirm;
            $orderpayment_save->card_first_name = $data->card_name;
            $orderpayment_save->card_last_name = $data->card_last_name;
            $orderpayment_save->billing_address = $data->billing_address;
            $orderpayment_save->b_city = $data->b_city;
            $orderpayment_save->b_state = $data->b_state;
            $orderpayment_save->b_zip = $data->b_zip;
            $orderpayment_save->b_zsc = $data->b_city . ',' . $data->b_state . ',' . $data->b_zip;

            $orderpayment_save->card_no = str_replace("-", "", $data->card_number);
            $orderpayment_save->card_security = str_replace("-", "", $data->card_sec);
            $orderpayment_save->card_expiry_date = str_replace("-", "", $data->card_exp);
            $orderpayment_save->payment_status = 'Unpaid';
            $orderpayment_save->ip_address = $data->ip;
            $orderpayment_save->card_type = str_replace("-", "", $data->card_type);
            $orderpayment_save->save();

            if (!empty($data->card_name)) {
                $creditscard = new creditcard();
                $creditscard->orderId = $autoorder_save->id;
                $creditscard->card_first_name = $data->card_name;
                $creditscard->card_last_name = $data->card_last_name;
                $creditscard->billing_address = $data->billing_address;
                $creditscard->b_city = $data->b_city;
                $creditscard->b_state = $data->b_state;
                $creditscard->b_zip = $data->b_zip;
                $creditscard->b_zsc = $data->b_city . ',' . $data->b_state . ',' . $data->b_zip;
                $creditscard->card_no = str_replace("-", "", $data->card_number);
                $creditscard->card_expiry_date = str_replace("-", "", $data->card_exp);
                $creditscard->card_security = str_replace("-", "", $data->card_sec);
                $creditscard->card_type = str_replace("-", "", $data->card_type);
                $creditscard->save();
            }


            $singlereport = new singlereport();
            $singlereport->userId = Auth::user()->id;
            $singlereport->orderId = $autoorder_save->id;
            $singlereport->pstatus = 0;
            $singlereport->save();

            $report = new report();
            $report->userId = Auth::user()->id;
            $report->orderId = $autoorder_save->id;
            $report->pstatus = 0;
            $report->save();

            return redirect()->back();
        }
    }

    public function manage_payments()
    {
        $user = Auth::user();

        $data = AutoOrder::where('pstatus', 13)
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
            })->paginate(20);
        return view('main.phone_quote.manage_payments.index', compact('data'))->render();
    }

    public function fetch_manage_payments(Request $request)
    {
        if (Auth::check()) {
        } else {
            return redirect('/loginn/');
        }

        if ($request->ajax()) {
            $user = Auth::user();

            $data = AutoOrder::where('pstatus', 13)
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
                })->paginate(20);


            return view('main.phone_quote.manage_payments.load', compact('data'))->render();
        }
    }

    public function payments($id)
    {
        $data = AutoOrder::find($id);
        $profit = profit::where('order_id', $id)->first();

        return view('main.phone_quote.manage_payments.payment', compact('data', 'profit'));
    }

    public function store_profit(Request $request)
    {

        $profitsave = profit::where('order_id', $request->orderid)->first();
        if (empty($profitsave)) {
            $profitsave = new profit();
            $profitsave->order_id = $request->orderid;
            $profitsave->profit = $request->profit;
            $profitsave->detail = $request->detail;
            $profitsave->save();
        } else {
            $profitsave->profit = $request->profit;
            $profitsave->detail = $request->detail;
            $profitsave->save();
        }
        return redirect('/profit_listing');
    }

    public function profit_listing()
    {
        $data = profit::all();
        return view('main.phone_quote.manage_payments.profit_listing', compact('data'));
    }

    public function mark_as_paid($id)
    {

        $orderpayment = orderpayment::where('orderId', $id)->first();
        $orderpayment->payment_status = 'Paid';
        $orderpayment->save();
        return redirect()->back();
    }

    public function paid_status(Request $request)
    {

        $autoorder = AutoOrder::find($request->orderid);
        $autoorder->paid_status = $request->status;
        $autoorder->pay_comments = $request->pay_comments;
        $autoorder->save();

        if ($request->fully_paid == 1) {
            $orderpayment = orderpayment::where('orderId', $request->orderid)->first();
            $orderpayment->payment_status = 'Paid';
            $orderpayment->save();
        }
        return redirect()->back();
    }

    public function payment_recieved()
    {
        $user = Auth::user();

        //$data=AutoOrder::where('paid_status',2);
        $data = DB::table('order as autoorder')
            ->select('autoorder.*', 'profit.profit as profit')
            ->leftjoin('profit as profit', 'autoorder.id', '=', 'profit.order_id')
            ->where('autoorder.paid_status', 2)
            ->where(function ($q) use ($user) {
                if ($user->userRole->name == 'Manager') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('autoorder.manager_id', $user->id)->orWhere('autoorder.order_taker_id', $user->id);
                    }
                } else if ($user->userRole->name == 'Dispatcher') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('autoorder.dispatcher_id', $user->id);
                    }
                } else if ($user->userRole->name == 'Delivery Boy') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('autoorder.delivery_boy_id', $user->id);
                    }
                } else if ($user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('autoorder.order_taker_id', $user->id);
                    } else if ($user->order_taker_quote == 2) {
                        $q->whereRaw('FIND_IN_SET(?, autoorder.manager_ot_ids)', [$user->id]);
                    }
                }
            })
            ->paginate(20);
        $sumofprofit = DB::table('order as autoorder')
            ->leftjoin('profit as profit', 'autoorder.id', '=', 'profit.order_id')
            ->where('autoorder.paid_status', 2)
            ->where(function ($q) use ($user) {
                if ($user->userRole->name == 'Manager') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('autoorder.manager_id', $user->id)->orWhere('autoorder.order_taker_id', $user->id);
                    }
                } else if ($user->userRole->name == 'Dispatcher') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('autoorder.dispatcher_id', $user->id);
                    }
                } else if ($user->userRole->name == 'Delivery Boy') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('autoorder.delivery_boy_id', $user->id);
                    }
                } else if ($user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('autoorder.order_taker_id', $user->id);
                    } else if ($user->order_taker_quote == 2) {
                        $q->whereRaw('FIND_IN_SET(?, autoorder.manager_ot_ids)', [$user->id]);
                    }
                }
            })
            ->sum('profit.profit');
        return view('main.phone_quote.manage_payments.payment_recieved', compact('data', 'sumofprofit'))->render();
    }

    public function fetch_data_profit(Request $request)
    {
        if (Auth::check()) {
        } else {
            return redirect('/loginn/');
        }

        if ($request->ajax()) {


            $user = Auth::user();
            $data = DB::table('order as autoorder')
                ->select('autoorder.*', 'profit.profit as profit')
                ->leftjoin('profit as profit', 'autoorder.id', '=', 'profit.order_id')
                ->where('autoorder.paid_status', 2)
                ->where(function ($q) use ($user) {
                    if ($user->userRole->name == 'Manager') {
                        if ($user->order_taker_quote == 1) {
                            $q->where('autoorder.manager_id', $user->id)->orWhere('autoorder.order_taker_id', $user->id);
                        }
                    } else if ($user->userRole->name == 'Dispatcher') {
                        if ($user->order_taker_quote == 1) {
                            $q->where('autoorder.dispatcher_id', $user->id);
                        }
                    } else if ($user->userRole->name == 'Delivery Boy') {
                        if ($user->order_taker_quote == 1) {
                            $q->where('autoorder.delivery_boy_id', $user->id);
                        }
                    } else if ($user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent') {
                        if ($user->order_taker_quote == 1) {
                            $q->where('autoorder.order_taker_id', $user->id);
                        } else if ($user->order_taker_quote == 2) {
                            $q->whereRaw('FIND_IN_SET(?, autoorder.manager_ot_ids)', [$user->id]);
                        }
                    }
                })
                ->paginate(20);

            $sumofprofit = DB::table('order as autoorder')
                ->leftjoin('profit as profit', 'autoorder.id', '=', 'profit.order_id')
                ->where('autoorder.paid_status', 2)
                ->where(function ($q) use ($user) {
                    if ($user->userRole->name == 'Manager') {
                        if ($user->order_taker_quote == 1) {
                            $q->where('autoorder.manager_id', $user->id)->orWhere('autoorder.order_taker_id', $user->id);
                        }
                    } else if ($user->userRole->name == 'Dispatcher') {
                        if ($user->order_taker_quote == 1) {
                            $q->where('autoorder.dispatcher_id', $user->id);
                        }
                    } else if ($user->userRole->name == 'Delivery Boy') {
                        if ($user->order_taker_quote == 1) {
                            $q->where('autoorder.delivery_boy_id', $user->id);
                        }
                    } else if ($user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent') {
                        if ($user->order_taker_quote == 1) {
                            $q->where('autoorder.order_taker_id', $user->id);
                        } else if ($user->order_taker_quote == 2) {
                            $q->whereRaw('FIND_IN_SET(?, autoorder.manager_ot_ids)', [$user->id]);
                        }
                    }
                })
                ->sum('profit.profit');

            return view('main.phone_quote.manage_payments.payment_recieved_load', compact('data', 'sumofprofit'))->render();
        }
    }

    public function employee_order()
    {
        $data = AutoOrder::where('id', '=', 0)->paginate(20);
        $userlist = User::all();
        return view('main.phone_quote.manage_payments.employee_order', compact('data', 'userlist'));
    }

    public function fetch_employee_order(Request $request)
    {
        if (Auth::check()) {
            $req = 0;
            if ($request->ajax()) {
                $data = AutoOrder::where('order_taker_id', '=', $request->user)
                    ->where('pstatus', '=', $request->ordertype)
                    ->whereMonth('created_at', date('m', strtotime($request->selectmonth)))
                    ->whereYear('created_at', date('Y', strtotime($request->selectmonth)))
                    ->orderby('id', 'desc')
                    ->paginate(20);
                return view('main.phone_quote.manage_payments.employee_order_load', compact('data'))->render();
            }
        } else {
            return redirect('/loginn/');
        }
    }

    public function double_booking(Request $request)
    {
        if (Auth::check()) {
            $id = Auth::user()->id;
            $ptype = $this->check_user_setting($id);
            $from = Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00');
            $to = Carbon::now()->format('Y-m-d 23:59:59');

            $from2 = Carbon::now()->startOfMonth()->format('Y/m/d');
            $to2 = Carbon::now()->format('Y/m/d');

            // echo "<pre>";
            // print_r($from);
            // print_r("<br>");
            // print_r($to);exit();
            if (Auth::user()->userRole->name == 'CSR' || Auth::user()->userRole->name == 'Seller Agent' || Auth::user()->userRole->name == 'Order Taker') {
                $total_order = AutoOrder::where('order_taker_id', $id);
            } else if (Auth::user()->userRole->name == 'Dispatcher') {
                $total_order = AutoOrder::where('dispatcher_id', $id);
            } else if (Auth::user()->userRole->name == 'Delivery Boy') {
                $total_order = AutoOrder::where('delivery_boy_id', $id);
            } else if (Auth::user()->userRole->name == 'Manager') {
                $total_order = AutoOrder::where('manager_id', $id);
            } else {
                $total_order = AutoOrder::query();
            }
            $total_order = $total_order->whereBetween('created_at', [$from, $to])->where('paneltype', '=', $ptype)->where(function ($q2) {
                $q2->where('booking_confirm', 'may be')
                    ->orWhere('booking_confirm', 'confirm');
            })->count();
            $users = User::whereHas('userRole', function ($q) {
                $q->where('name', 'CSR')
                    ->orWhere('name', 'Seller Agent')
                    ->orWhere('name', 'Order Taker')
                    ->orWhere('name', 'Dispatcher')
                    ->orWhere('name', 'Delivery Boy')
                    ->orWhere('name', 'Manager');
            })->where('deleted', 0)->orderBy('role', 'ASC')->get();

            $company = AutoOrder::select(['company_name'])->where(function ($q2) {
                $q2->where('booking_confirm', 'may be')
                    ->orWhere('booking_confirm', 'confirm');
            })->get()->unique('company_name');

            return view('main.phone_quote.new.double_booking', compact('total_order', 'users', 'from2', 'to2', 'from', 'to', 'company'));
        } else {
            return redirect('/login');
        }
    }

    public function double_booking_load(Request $request)
    {
        if (Auth::check()) {
            $id = $request->user ?? '';
            $ptype = $this->check_user_setting($id);
            $user = User::find($id);

            // $from = Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00');
            // $to = Carbon::now()->format('Y-m-d 23:59:59');

            $from = '';
            $to = '';
            if (isset($request->date_range) && !empty($request->date_range)) {
                $dates = explode(' - ', $request->date_range);
                $from = date('Y-m-d 00:00:00', strtotime($dates[0]));
                $to = date('Y-m-d 23:59:59', strtotime($dates[1]));
            }
            $company = $request->company_name;
            // echo "<pre>";
            // print_r($from);
            // print_r('<br>');
            // print_r($to);
            // exit();
            if (isset($user->id)) {
                if ($user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent' || $user->userRole->name == 'Order Taker') {
                    $total_order = AutoOrder::where('order_taker_id', $id);
                } else if ($user->userRole->name == 'Dispatcher') {
                    $total_order = AutoOrder::where('dispatcher_id', $id);
                } else if ($user->userRole->name == 'Delivery Boy') {
                    $total_order = AutoOrder::where('delivery_boy_id', $id);
                } else if ($user->userRole->name == 'Manager') {
                    $total_order = AutoOrder::where('manager_id', $id);
                }
            } else {
                $total_order = AutoOrder::query();
            }

            $total_order = $total_order->where('paneltype', '=', $ptype)->where(function ($q2) {
                $q2->where('booking_confirm', 'may be')
                    ->orWhere('booking_confirm', 'confirm');
            });
            if (!empty($company)) {
                $total_order = $total_order->whereHas('carrier', function ($q) use ($company) {
                    $q->where('companyname', $company);
                });
            }
            if (!empty($from) && !empty($to)) {
                $total_order = $total_order->where(function ($q) use ($from, $to) {
                    $q->whereBetween('created_at', [$from, $to]);
                });
            }

            $data = $total_order->orderBy('created_at', 'DESC')->paginate(20);
            $total_order = $total_order->count();

            return view('main.phone_quote.new.double_booking_load', compact('total_order', 'from', 'to', 'id', 'company', 'data'));
        } else {
            return redirect('/login');
        }
    }

    public function double_booking_load_data(Request $request)
    {
        if (Auth::check()) {
            $id = $request->user ?? '';
            $ptype = $this->check_user_setting($id);
            $user = User::find($id);

            // $from = Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00');
            // $to = Carbon::now()->format('Y-m-d 23:59:59');

            $from = '';
            $to = '';
            if (isset($request->date_range) && !empty($request->date_range)) {
                $dates = explode(' - ', $request->date_range);
                $from = date('Y-m-d 00:00:00', strtotime($dates[0]));
                $to = date('Y-m-d 23:59:59', strtotime($dates[1]));
            }
            $company = $request->company_name;
            // echo "<pre>";
            // print_r($from);
            // print_r('<br>');
            // print_r($to);
            // exit();
            if (isset($user->id)) {
                if ($user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent' || $user->userRole->name == 'Order Taker') {
                    $total_order = AutoOrder::where('order_taker_id', $id);
                } else if ($user->userRole->name == 'Dispatcher') {
                    $total_order = AutoOrder::where('dispatcher_id', $id);
                } else if ($user->userRole->name == 'Delivery Boy') {
                    $total_order = AutoOrder::where('delivery_boy_id', $id);
                } else if ($user->userRole->name == 'Manager') {
                    $total_order = AutoOrder::where('manager_id', $id);
                }
            } else {
                $total_order = AutoOrder::query();
            }

            $data = $total_order->where('paneltype', '=', $ptype)->where(function ($q2) {
                $q2->where('booking_confirm', 'may be')
                    ->orWhere('booking_confirm', 'confirm');
            });
            if (!empty($company)) {
                $data = $data->whereHas('carrier', function ($q) use ($company) {
                    $q->where('companyname', $company);
                });
            }
            if (!empty($from) && !empty($to)) {
                $data = $data->where(function ($q) use ($from, $to) {
                    $q->whereBetween('created_at', [$from, $to]);
                });
            }

            $data = $data->orderBy('created_at', 'DESC')->paginate(20);

            return view('main.phone_quote.new.double_booking_load_data', compact('data'));
        } else {
            return redirect('/login');
        }
    }

    public function customerNatureAdd()
    {
        // $orer = AutoOrder::where('pstatus', 13)->paginate(25);
        // $orer = AutoOrder::with('customerNature')->has('customerNature')->where('pstatus', 13)->take(100)->get();
        $orer = AutoOrder::doesntHave('customerNature')
            ->where('pstatus', 13)
            ->take(100)
            ->paginate(25);
        // dd($orders->toArray());

        return view('main.phone_quote.usedAndNewCarDealers.addNatureReview', compact('orer'));
    }

    public function customerNatureStore(Request $request)
    {
        // dd($request->toArray());
        $orer = new NatureOfCustomer;
        $orer->user_id = Auth::user()->id;
        $orer->order_id = $request->order_id;
        $orer->phone = $request->main_ph;
        $orer->save();

        return back();
    }

    public function AllAutosApproachStore(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'person_name' => 'nullable|string',
            // 'address' => 'nullable|string|unique:used_new_car_dealers',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'phone2' => 'nullable|string',
            'phone3' => 'nullable|string',
            'state_add' => 'nullable|string',
            'email' => 'nullable',
            'website' => 'nullable|string',
            'link' => 'nullable|string',
            'category' => 'nullable|string',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            return back()->with(['flash_message' => $validator->errors()], 400);
        }

        // Get the authenticated user
        $user = Auth::user();

        // Handle the case where user is not authenticated
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            // Create the record
            $newDealer = UsedAndNewCarDealers::create([
                'user_id' => 0,
                'name' => $request->input('name'),
                'person_name' => $request->input('person_name'),
                'address' => $request->input('address'),
                'phone' => $request->input('phone'),
                'phone2' => $request->input('phone2'),
                'phone3' => $request->input('phone3'),
                'state' => $request->input('state_add'),
                'email' => $request->input('email'),
                'website' => $request->input('website'),
                'link' => $request->input('link'),
                'category' => $request->input('category'),
            ]);

            return back()->with('message', 'Dealer created successfully');
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error creating dealer: ' . $e->getMessage());

            // Redirect back with an error message
            return back()->with('error', 'Failed to create dealer. Please try again.');
        }
    }

    public function validateField(Request $request)
    {
        $fieldName = $request->input('field_name');
        $fieldValue = $request->input('field_value');
        $relatedNames = [];

        switch ($fieldName) {
            case 'name':
                $exists = UsedAndNewCarDealers::where('name', $fieldValue)->exists();
                if ($exists) {
                    $relatedNames = UsedAndNewCarDealers::where('name', $fieldValue)
                        ->select('name', 'state')
                        ->get()
                        ->map(function ($item) {
                            return $item->name . ' (' . $item->state . ')';
                        })
                        ->toArray();
                }
                break;
            case 'phone':
                // Remove all non-numeric characters from the input phone number
                $normalizedPhone = preg_replace('/\D/', '', $fieldValue);

                // Normalize phone numbers in the database and check for existence
                $exists = UsedAndNewCarDealers::whereRaw("REGEXP_REPLACE(phone, '[^0-9]', '') = ?", [$normalizedPhone])->exists();
                break;
            case 'email':
                $exists = UsedAndNewCarDealers::where('email', $fieldValue)->exists();
                break;
            case 'address':
                $exists = UsedAndNewCarDealers::where('address', $fieldValue)->exists();
                break;
            default:
                $exists = false;
        }

        $response = [
            'valid' => !$exists,
            'message' => $exists ? 'This ' . $fieldName . ' already exists.' : '',
            'related_names' => $relatedNames
        ];

        return response()->json($response);
    }

    public function getEmailHistory(Request $request)
    {
        $data = EmailHistory::with('user', 'template')->where('recipient', $request->email)->get();

        return $data;
    }

    public function getCategoryCount(Request $request)
    {
        $states = array_filter($request->state);

        // List of categories
        $categories = [
            'Auto Dealership' => 'Auto',
            'Automotive Repair Services' => 'Automotive',
            'New Car Dealer' => 'New',
            'Used Car Dealer' => 'Used',
            'Automobile Dealers Electric Cars' => 'Automobile_Electric',
            'Automobile Dealers-electric Cars' => 'Automobile_Electric_Alt',
            'Automobile Dealership' => 'Automobile_Dealership',
            'Automobile Dlrs Custom Designed Replica' => 'Automobile_Custom_Replica',
            'Automobile Dlrs-custom Designed Replica' => 'Automobile_Custom_Replica_Alt',
            'Automobile Sales & Service' => 'Automobile_Sales_Service',
            'Automobile Specialty' => 'Automobile_Specialty',
            'Automobilendealers-used Cars' => 'Automobilendealers_Used',
            'Automobiles' => 'Automobiles',
            'Automobiles Pick-up Trucks & Vans' => 'Automobiles_Pickup_Vans',
            'Automobiles, New And Used' => 'Automobiles_New_Used',
            'Automobiles-fleet Sales' => 'Automobiles_Fleet_Sales',
            'Automotive Dealers & Service Stations' => 'Automotive_Dealers_Service',
            'Four Wheel Drive Vehicles' => 'Four_Wheel_Drive',
            'Limousine-dealers' => 'Limousine_Dealers',
            'Motor Vehicle Dealers (new And Used)' => 'Motor_Vehicle_Dealers_New_Used',
            'New & Used Car Dlrs' => 'New_Used_Car_Dlrs',
            'New And Used Car Dealers' => 'New_Used_Car_Dealers',
            'New And Used Car Dealers, Nec' => 'New_Used_Car_Dealers_Nec',
            'New And Used Car Dealers; Nec' => 'New_Used_Car_Dealers_SemiColon',
            'New And Usedcar Dealers, Nec' => 'New_Used_Car_Dealers_Nec_Alt'
        ];

        $categoryCounts = [];

        // Loop through each category and calculate the count
        foreach ($categories as $categoryName => $responseKey) {
            $categoryCounts[$responseKey] = UsedAndNewCarDealers::where('category', $categoryName)
                ->whereIn('state', $states)
                ->count();
        }

        return response()->json($categoryCounts, 200);
    }


    public function autos_approach_new(Request $request, $data_type = null)
    {

        $data_type = base64_decode($data_type);

        if($data_type == 'Shipper' || $data_type == 'Carrier' || $data_type == 'Broker' ){

            if (Auth::check()) {
                $user = Auth::user();
                $ptype = $this->check_user_setting(Auth::user()->id);
                $setting = general_setting::first();
                $state = "";

                if ($user->role != 1 && $user->role != 9) {
                    $data = ShipperDetails::where('user_id', $user->id)
                        ->orderBy('id', 'DESC');

                    $state = \App\User::with('assignedDataNew')
                        ->has('assignedDataNew')
                        ->find($user->id);
                    if ($state) {
                        $data = $data->take($state->assignedDataNew->recordsAllowed);
                        $state = $state->assignedDataNew->state;
                    } else {
                        $state = "";
                    }
                    $data = $data->paginate(20);

                } else {
                    # code...
                    $data = ShipperDetails::orderBy('id', 'DESC')->paginate(20);
                }
                $template = Template::all();


                return view('main.phone_quote.ShipperDetails.index', compact('data', 'state','data_type','template'));
            } else {
                return redirect('/loginn/');
            }
        }
    }

    public function storeAutosApproachNew(Request $request)
    {

        if (Auth::check()) {
            $user = Auth::user();
            $ptype = $this->check_user_setting(Auth::user()->id);
            $setting = general_setting::first();

            $check = ShipperDetailsAssign::where('orderTaker', $request->orderTaker)->first();

            if ($check) {
                $check->state = implode(',', $request->state);
                $check->orderTaker = $request->orderTaker;
                $check->type =  $request->type;
                $check->recordsAllowed = $request->recordsAllowed;
                $check->save();
            } else {
                $data = new ShipperDetailsAssign;
                $data->state = implode(',', $request->state);
                $data->orderTaker = $request->orderTaker;
                $data->type =  $request->type;
                $data->recordsAllowed = $request->recordsAllowed;
                $data->save();
            }

            foreach ($request->state as $state) {
                ShipperDetails::where('user_id', 0)
                    ->where('states', $state)
                    ->where('type', $request->type)
                    ->take($request->recordsAllowed)
                    ->update(['user_id' => $request->orderTaker]);
            }

            return back();
        } else {
            return redirect('/loginn/');
        }
    }

    public function autosApproachSearchNew(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $state = '';

            $send_template =  isset($request->send_template) ? $request->send_template  : 0;
            // Initialize query
            $query = ShipperDetails::query();

            $search_as = $request->input('search_as',null);

            // Filter by user's assigned state if applicable
            if ($search_as == 2) { //search as normal user or himself
                $query->where('user_id', $user->id);
                $userState = \App\User::with('assignedDataNew')->has('assignedDataNew')->find($user->id);
                if ($userState) {
                    $state = $userState->assignedDataNew->states;

                }
            }

            // Apply filters
            if ($request->has('state') && $request->state !== null) {
                $query->where('states', $request->state);
            }
            if ($request->has('orderTaker') && $request->orderTaker !== null) {
                $query->where('user_id', $request->orderTaker);
            }
            if ($request->has('emailsSent')) {
                if ($request->email == 1) {
                    $query->whereNotNull('email');
                } elseif ($request->email == 0) {
                    $query->orWhereNull('email');
                }
            }
            if ($request->has('search') && $request->search !== null) {
                $query->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->search . '%');
            }
            if ($request->has('type') && $request->type !== null) {
                if ($request->type == 'Null') {
                    $query->whereNull('type');
                } else {
                    $query->where('type', $request->type);
                }
            }
            if ($request->has('email') && $request->email !== null) {
                if ($request->email == 1) {
                    $query->whereNotNull('email');
                } elseif ($request->email == 0) {
                    $query->orWhereNull('email');
                }
            }
            if ($request->has('withHistory') && $request->withHistory !== null) {
                if ($request->withHistory == 1) {
                    $query->has('history');
                } elseif ($request->email == 0) {
                    $query->doesntHave('history');
                }
            }
            // dd($request->toArray(), $query->take(50)->get()->toArray());

            // Apply date range filter if provided in the request
            if ($request->has('startDate') && $request->has('endDate')) {
                $from = date('Y-m-d 00:00:00', strtotime($request->startDate));
                $to = date('Y-m-d 23:59:59', strtotime($request->endDate));

                if (!empty($from) && !empty($to)) {
                    $query->whereBetween('created_at', [$from, $to]);
                }
            }

            // Fetch paginated data

            if ($send_template) {
                $template = $request->template;
                // Get the SQL with placeholders
                $sqlWithPlaceholders = $query->toSql();

                // Replace placeholders with actual bindings
                $bindings = $query->getBindings();
                $fullSql = vsprintf(str_replace('?', "'%s'", $sqlWithPlaceholders), $bindings);

                // Save query to SendTemplateEmail model
                $send_email = new SendTemplateEmail();
                $send_email->query_sql = $fullSql;
                $send_email->data_type = $request->type;
                $send_email->template_id = $template;
                $send_email->limit_data = $request->input('limit_data',500);
                $send_email->user_id = auth()->user()->id;
                $send_email->save();
            }
            $data = $query->orderby('id','desc')->paginate(20);

            return view('main.phone_quote.ShipperDetails.table', compact('data', 'state'));
        }
    }

    public function autosApproachEmailAddNew(Request $request)
    {
        // dd($request->toArray());
           $updateData = [];

        if (!empty($request->email)) {
            $updateData['email'] = $request->email;
        }

        if (!empty($request->phone2)) {
            $updateData['phone2'] = $request->phone2;
        }
        if (!empty($request->phone)) {
            $updateData['phone'] = $request->phone;
        }

        if (!empty($request->phone3)) {
            $updateData['phone3'] = $request->phone3;
        }
        if (!empty($request->address)) {
            $updateData['address'] = $request->address;
        }

        if(!empty($request->comp_id)) {
            ShipperDetails::where('id', $request->comp_id)->update($updateData);
        }

        $email = ShipperDetails::where('id', $request->comp_id)->first();

        if ($request->email != null) {
            return $email;
        } else {
            return back();
        }
    }

    public function autosApproachPhoneCount(Request $request)
    {
        try {
            // Get the authenticated user's ID
            $user = Auth::user()->id;

            // Get the approach ID from the request
            $approach = $request->approachId;

            // You can use dd($user, $approach, $request->toArray()) for debugging purposes

            // Create a new entry in the WhatsappAutoApproachCount model
            ShipperDetailsPhone::insert([
                'userId' => $user,
                'approachId' => $approach,
                'type' => $request->type,
            ]);

            // You can also return a success response or redirect as needed
            return response()->json(['message' => 'count added successfully'], 200);
        } catch (\Exception $e) {
            // Handle any exceptions that may occur during the process
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function autosApproachgetHistory(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 2) {
            $user_id = $user->id;
        } else {
            $user_id = $request->user_id;
        }

        return ShipperDetailsHistories::with('user')->where('company_id', $request->company_id)->get();

    }

    public function autosApproachgetstoreHistory(Request $request)
    {
        $user = Auth::user();
        $history = new ShipperDetailsHistories;
        $history->user_id = $user->id;
        $history->company_id = $request->CompanyID;
        $history->connectStatus = $request->connectStatus;
        $history->comment = $request->comment;
        $history->save();

        ShipperDetails::where('id', $request->CompanyID)->update(['user_id' => 0]);

        return ShipperDetailsHistories::with('user')
            ->where('user_id', $user->id)
            ->where('company_id', $request->CompanyID)
            ->get();
    }

    public function filterNewAssignedAutos(Request $request)
    {

        if (Auth::check()) {
            $user = Auth::user();
            $ptype = $this->check_user_setting(Auth::user()->id);
            $setting = general_setting::first();
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');
            $historyUser = $request->input(
                'orderTaker'
            );

            // dd($request->toArray());
            if ($request->whatsapp == 'whatsapp') {
                // dd('ok');
                $data = ShipperDetails::where('user_id', '!=', 0)->with('user', 'whatsappCallCount', 'history',  'history.user')
                    ->has('whatsappCallCount');

                if ($request->has('orderTaker') && $request->orderTaker !== null) {
                    $data->where('user_id', $request->orderTaker);
                }
                if ($request->has('state') && $request->state !== null) {
                    $data->where('states', $request->state);
                }
                if (
                    $request->has('startDate') && $request->startDate !== null &&
                    $request->has('endDate') && $request->endDate !== null
                ) {
                    $data->whereHas('whatsappCallCount', function ($q) use ($startDate, $endDate) {
                        $q->whereDate('created_at', '>=', $startDate)
                            ->whereDate('created_at', '<=', $endDate);
                    });
                }
            } else {

                $data = ShipperDetails::with([
                    'user',
                    'callCount',
                    'history.user',
                    'history' => function ($query) {
                        // Order by history's created_at if needed
                        $query->orderBy('created_at', 'desc');
                    }
                ])
                    ->has('history');

                if (
                    $request->has('orderTaker') && $request->orderTaker !== null
                ) {
                    $historyUser = $request->orderTaker;

                    $data->whereHas('history', function ($q) use ($historyUser) {
                        $q->where('user_id', $historyUser);
                    });
                    // $data->where('user_id', $request->orderTaker);
                }

                if ($request->has('state') && $request->state !== null) {
                    $data->where('states', $request->state);
                }

                if (
                    $request->has('startDate') && $request->startDate !== null &&
                    $request->has('endDate') && $request->endDate !== null
                ) {

                    $startDate = $request->startDate;
                    $endDate = $request->endDate;

                    // $data->whereHas('history', function ($q) use ($startDate, $endDate) {
                    //     $q->whereBetween('created_at', [$startDate, $endDate]);
                    // });
                    $data->whereHas(
                        'history',
                        function ($q) use ($startDate, $endDate) {
                            $q->whereDate('created_at', '>=', $startDate)
                                ->whereDate('created_at', '<=', $endDate);
                        }
                    );
                }
            }

            // dd('okk', $request->toArray(), $data->get()->toArray());

            return $data->get();
        } else {
            return redirect('/loginn/');
        }
    }

    public function allNewAutosApproach(Request $request)
    {
        if (Auth::check()) {
            $ptype = $this->check_user_setting(Auth::user()->id);
            $user = Auth::user();

            if ($ptype == 1) {
                $phoneaccess = explode(',', Auth::user()->emp_access_phone);
            } elseif ($ptype == 2) {
                $phoneaccess = explode(',', Auth::user()->emp_access_web);
            } elseif ($ptype == 3) {
                $phoneaccess = explode(',', Auth::user()->emp_access_test);
            } elseif ($ptype == 4) {
                $phoneaccess = explode(',', Auth::user()->panel_type_4);
            } elseif ($ptype == 5) {
                $phoneaccess = explode(',', Auth::user()->panel_type_5);
            } elseif ($ptype == 6) {
                $phoneaccess = explode(',', Auth::user()->panel_type_6);
            } else {
                $phoneaccess = [];
            }

            // Check if the user has role 1 or 2
            if ($user->role == 1 || $user->role == 9 || in_array('143', $phoneaccess)) {
                $ptype = $this->check_user_setting($user->id);
                $setting = general_setting::first();

                $data = ShipperDetails::where('user_id', '!=', 0)->with('user', 'callCount', 'history')
                    ->has('callCount')
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);

                //     ->get();
                // dd($data->toArray());

                return view('main.phone_quote.assignedNewAutoApproching.index', compact('data'));
            } else {
                // Redirect if the user doesn't have the required roles
                return redirect('/loginn/');
            }
        } else {
            return redirect('/loginn/');
        }
    }

    public function AllNewAutosApproachStore(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'other_details' => 'nullable|string',
            // 'address' => 'nullable|string|unique:used_new_car_dealers',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'phone2' => 'nullable|string',
            'phone3' => 'nullable|string',
            'state_add' => 'nullable|string',
            'email' => 'nullable',
            'website' => 'nullable|string',
            'type' => 'nullable|string',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            return back()->with(['flash_message' => $validator->errors()], 400);
        }

        // Get the authenticated user
        $user = Auth::user();

        // Handle the case where user is not authenticated
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            // Create the record
            $newDealer = ShipperDetails::insert([
                'user_id' => 0,
                'name' => $request->input('name'),
                'other_details' => $request->input('other_details'),
                'address' => $request->input('address'),
                'phone' => $request->input('phone'),
                'phone2' => $request->input('phone2'),
                'phone3' => $request->input('phone3'),
                'states' => $request->input('state_add'),
                'email' => $request->input('email'),
                'website' => $request->input('website'),
                'type' => $request->input('type'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            return back()->with('message', 'Dealer created successfully');
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error creating dealer: ' . $e->getMessage());

            // Redirect back with an error message
            return back()->with('error', 'Failed to create dealer. Please try again.');
        }
    }

    public function NewvalidateField(Request $request)
    {
        $fieldName = $request->input('field_name');
        $fieldValue = $request->input('field_value');
        $relatedNames = [];

        switch ($fieldName) {
            case 'name':
                $exists = ShipperDetails::where('name', $fieldValue)->exists();
                if ($exists) {
                    $relatedNames = ShipperDetails::where('name', $fieldValue)
                        ->select('name', 'states')
                        ->get()
                        ->map(function ($item) {
                            return $item->name . ' (' . $item->states . ')';
                        })
                        ->toArray();
                }
                break;
            case 'phone':
                // Remove all non-numeric characters from the input phone number
                $normalizedPhone = preg_replace('/\D/', '', $fieldValue);

                // Normalize phone numbers in the database and check for existence
                $exists = ShipperDetails::whereRaw("REGEXP_REPLACE(phone, '[^0-9]', '') = ?", [$normalizedPhone])->exists();
                break;
            case 'email':
                $exists = ShipperDetails::where('email', $fieldValue)->exists();
                break;
            case 'address':
                $exists = ShipperDetails::where('address', $fieldValue)->exists();
                break;
            default:
                $exists = false;
        }

        $response = [
            'valid' => !$exists,
            'message' => $exists ? 'This ' . $fieldName . ' already exists.' : '',
            'related_names' => $relatedNames
        ];

        return response()->json($response);
    }

    //dealer


    public function autos_approach_new_dealer(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $ptype = $this->check_user_setting(Auth::user()->id);
            $setting = general_setting::first();
            $state = "";

            if ($user->role != 1 && $user->role != 9) {
                $data = ShipperDetailsDealer::where('user_id', $user->id)
                    ->orderBy('id', 'DESC');

                $state = \App\User::with('assignedDataNewDealer')
                    ->has('assignedDataNewDealer')
                    ->find($user->id);
                if ($state) {
                    $record =$state->assignedDataNewDealer->recordsAllowed;
                    $with_phone =$state->assignedDataNewDealer->with_phone;
                    if($with_phone == 1){
                        $data->whereNotNull('phone');
                    }else{
                         $data->whereNull('phone');
                    }

                    $state = $state->assignedDataNewDealer->state;
                } else {
                    $state = "";
                }
                $data = $data->paginate(20);

            } else {
                # code...
                $data = ShipperDetailsDealer::orderBy('id', 'DESC')->paginate(20);
            }
            $template = Template::all();

            return view('main.phone_quote.ShipperDetailsDealer.index', compact('data', 'state','template'));
        } else {
            return redirect('/loginn/');
        }
    }

    public function storeAutosApproachNew_dealer(Request $request)
    {

        if (Auth::check()) {
            $user = Auth::user();
            $ptype = $this->check_user_setting(Auth::user()->id);
            $setting = general_setting::first();

            $check = ShipperDetailsAssignDealer::where('orderTaker', $request->orderTaker)->first();

            if ($check) {
                $check->state = implode(',', $request->state);
                $check->orderTaker = $request->orderTaker;
                $check->type =  $request->type;
                $check->with_phone =  $request->w_phone2;
                $check->recordsAllowed = $request->recordsAllowed;
                $check->save();
            } else {
                $data = new ShipperDetailsAssignDealer;
                $data->state = implode(',', $request->state);
                $data->orderTaker = $request->orderTaker;
                $data->type =  $request->type;
                $data->with_phone =  $request->w_phone2;
                $data->recordsAllowed = $request->recordsAllowed;
                $data->save();
            }

            foreach ($request->state as $state) {
                ShipperDetailsDealer::where('user_id', 0)
                    ->where('states', $state)
                    ->where('type', $request->type)
                    ->take($request->recordsAllowed)
                    ->update(['user_id' => $request->orderTaker]);
            }

            return back();
        } else {
            return redirect('/loginn/');
        }
    }

    public function autosApproachSearchNew_dealer(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $state = '';

            // Initialize query
            $query = ShipperDetailsDealer::query();

            $search_as = $request->input('search_as',null);

            // Filter by user's assigned state if applicable
            if ($search_as == 2) { //search as normal user or himself
                $query->where('user_id', $user->id);
                $userState = \App\User::with('assignedDataNewDealer')->has('assignedDataNewDealer')->find($user->id);
                if ($userState) {
                    $state = $userState->assignedDataNewDealer->states;
                    $record =$userState->assignedDataNewDealer->recordsAllowed;
                    $with_phone =$userState->assignedDataNewDealer->with_phone;
                    if($with_phone == 1){
                        $query->whereNotNull('phone');
                    }else{
                        $query->whereNull('phone');
                    }


                }
            }

            // Apply filters
            if ($request->has('state') && $request->state !== null) {
                $query->where('states', $request->state);
            }
            if ($request->has('orderTaker') && $request->orderTaker !== null) {
                $query->where('user_id', $request->orderTaker);
            }
            if ($request->has('emailsSent')) {
                if ($request->email == 1) {
                    $query->whereNotNull('email');
                } elseif ($request->email == 0) {
                    $query->orWhereNull('email');
                }
            }
            if ($request->has('w_phone')) {

                if ($request->w_phone === "1") {
                    $query->whereNotNull('phone');
                } elseif ($request->w_phone === "0") {
                    $query->orWhereNull('phone');
                }
            }
            if ($request->has('search') && $request->search !== null) {
                $query->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->search . '%');
            }
            if ($request->has('type') && $request->type !== null) {
                if ($request->type == 'Null') {
                    $query->whereNull('type');
                } else {
                    $query->where('type', $request->type);
                }
            }
            if ($request->has('email') && $request->email !== null) {
                if ($request->email == 1) {
                    $query->whereNotNull('email');
                } elseif ($request->email == 0) {
                    $query->orWhereNull('email');
                }
            }
            if ($request->has('withHistory') && $request->withHistory !== null) {
                if ($request->withHistory == 1) {
                    $query->has('history');
                } elseif ($request->email == 0) {
                    $query->doesntHave('history');
                }
            }
            // dd($request->toArray(), $query->take(50)->get()->toArray());

            // Apply date range filter if provided in the request
            if ($request->has('startDate') && $request->has('endDate')) {
                $from = date('Y-m-d 00:00:00', strtotime($request->startDate));
                $to = date('Y-m-d 23:59:59', strtotime($request->endDate));

                if (!empty($from) && !empty($to)) {
                    $query->whereBetween('created_at', [$from, $to]);
                }
            }
            $send_template =  isset($request->send_template) ? $request->send_template  : 0;
            if ($send_template) {
                $template = $request->template;
                // Get the SQL with placeholders
                $sqlWithPlaceholders = $query->toSql();

                // Replace placeholders with actual bindings
                $bindings = $query->getBindings();
                $fullSql = vsprintf(str_replace('?', "'%s'", $sqlWithPlaceholders), $bindings);

                // Save query to SendTemplateEmail model
                $send_email = new SendTemplateEmail();
                $send_email->query_sql = $fullSql;
                $send_email->data_type = $request->type;
                $send_email->template_id = $template;
                $send_email->limit_data = $request->input('limit_data',500);
                $send_email->user_id = auth()->user()->id;
                $send_email->save();
            }
            $data = $query->orderby('id','desc')->paginate(20);


            return view('main.phone_quote.ShipperDetailsDealer.table', compact('data', 'state'));
        }
    }

    public function autosApproachEmailAddNew_dealer(Request $request)
    {
        // dd($request->toArray());
        $updateData = [];

        if (!empty($request->email)) {
            $updateData['email'] = $request->email;
        }

        if (!empty($request->phone2)) {
            $updateData['phone2'] = $request->phone2;
        }
        if (!empty($request->phone)) {
            $updateData['phone'] = $request->phone;
        }

        if (!empty($request->phone3)) {
            $updateData['phone3'] = $request->phone3;
        }
        if (!empty($request->address)) {
            $updateData['address'] = $request->address;
        }

        if(!empty($request->comp_id)) {
            ShipperDetailsDealer::where('id', $request->comp_id)->update($updateData);
        }

        $email = ShipperDetailsDealer::where('id', $request->comp_id)->first();

        if ($request->email != null) {
            return $email;
        } else {
            return back();
        }
    }

    public function autosApproachPhoneCount_dealer(Request $request)
    {
        try {
            // Get the authenticated user's ID
            $user = Auth::user()->id;

            // Get the approach ID from the request
            $approach = $request->approachId;

            // You can use dd($user, $approach, $request->toArray()) for debugging purposes

            // Create a new entry in the WhatsappAutoApproachCount model
            ShipperDetailsPhoneDealer::insert([
                'userId' => $user,
                'approachId' => $approach,
                'type' => $request->type,
            ]);

            // You can also return a success response or redirect as needed
            return response()->json(['message' => 'count added successfully'], 200);
        } catch (\Exception $e) {
            // Handle any exceptions that may occur during the process
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function autosApproachgetHistory_dealer(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 2) {
            $user_id = $user->id;
        } else {
            $user_id = $request->user_id;
        }

        return ShipperDetailsHistoriesDealer::with('user')->where('company_id', $request->company_id)->get();

    }

    public function autosApproachgetstoreHistory_dealer(Request $request)
    {
        $user = Auth::user();
        $history = new ShipperDetailsHistoriesDealer;
        $history->user_id = $user->id;
        $history->company_id = $request->CompanyID;
        $history->connectStatus = $request->connectStatus;
        $history->comment = $request->comment;
        $history->save();

        ShipperDetailsDealer::where('id', $request->CompanyID)->update(['user_id' => 0]);

        return ShipperDetailsHistoriesDealer::with('user')
            ->where('user_id', $user->id)
            ->where('company_id', $request->CompanyID)
            ->get();
    }

    public function filterNewAssignedAutos_dealer(Request $request)
    {

        if (Auth::check()) {
            $user = Auth::user();
            $ptype = $this->check_user_setting(Auth::user()->id);
            $setting = general_setting::first();
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');
            $historyUser = $request->input(
                'orderTaker'
            );

            // dd($request->toArray());
            if ($request->whatsapp == 'whatsapp') {
                // dd('ok');
                $data = ShipperDetailsDealer::where('user_id', '!=', 0)->with('user', 'whatsappCallCount', 'history',  'history.user')
                    ->has('whatsappCallCount');

                if ($request->has('orderTaker') && $request->orderTaker !== null) {
                    $data->where('user_id', $request->orderTaker);
                }
                if ($request->has('state') && $request->state !== null) {
                    $data->where('states', $request->state);
                }
                if (
                    $request->has('startDate') && $request->startDate !== null &&
                    $request->has('endDate') && $request->endDate !== null
                ) {
                    $data->whereHas('whatsappCallCount', function ($q) use ($startDate, $endDate) {
                        $q->whereDate('created_at', '>=', $startDate)
                            ->whereDate('created_at', '<=', $endDate);
                    });
                }
            } else {

                $data = ShipperDetailsDealer::with([
                    'user',
                    'callCount',
                    'history.user',
                    'history' => function ($query) {
                        // Order by history's created_at if needed
                        $query->orderBy('created_at', 'desc');
                    }
                ])
                    ->has('history');

                if (
                    $request->has('orderTaker') && $request->orderTaker !== null
                ) {
                    $historyUser = $request->orderTaker;

                    $data->whereHas('history', function ($q) use ($historyUser) {
                        $q->where('user_id', $historyUser);
                    });
                    // $data->where('user_id', $request->orderTaker);
                }

                if ($request->has('state') && $request->state !== null) {
                    $data->where('states', $request->state);
                }

                if (
                    $request->has('startDate') && $request->startDate !== null &&
                    $request->has('endDate') && $request->endDate !== null
                ) {

                    $startDate = $request->startDate;
                    $endDate = $request->endDate;

                    // $data->whereHas('history', function ($q) use ($startDate, $endDate) {
                    //     $q->whereBetween('created_at', [$startDate, $endDate]);
                    // });
                    $data->whereHas(
                        'history',
                        function ($q) use ($startDate, $endDate) {
                            $q->whereDate('created_at', '>=', $startDate)
                                ->whereDate('created_at', '<=', $endDate);
                        }
                    );
                }
            }

            // dd('okk', $request->toArray(), $data->get()->toArray());

            return $data->get();
        } else {
            return redirect('/loginn/');
        }
    }

    public function allNewAutosApproach_dealer(Request $request)
    {
        if (Auth::check()) {
            $ptype = $this->check_user_setting(Auth::user()->id);
            $user = Auth::user();

            if ($ptype == 1) {
                $phoneaccess = explode(',', Auth::user()->emp_access_phone);
            } elseif ($ptype == 2) {
                $phoneaccess = explode(',', Auth::user()->emp_access_web);
            } elseif ($ptype == 3) {
                $phoneaccess = explode(',', Auth::user()->emp_access_test);
            } elseif ($ptype == 4) {
                $phoneaccess = explode(',', Auth::user()->panel_type_4);
            } elseif ($ptype == 5) {
                $phoneaccess = explode(',', Auth::user()->panel_type_5);
            } elseif ($ptype == 6) {
                $phoneaccess = explode(',', Auth::user()->panel_type_6);
            } else {
                $phoneaccess = [];
            }

            // Check if the user has role 1 or 2
            if ($user->role == 1 || $user->role == 9 || in_array('144', $phoneaccess)) {
                $ptype = $this->check_user_setting($user->id);
                $setting = general_setting::first();

                $data = ShipperDetailsDealer::where('user_id', '!=', 0)->with('user', 'callCount', 'history')
                    ->has('callCount')
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);

                //     ->get();
                // dd($data->toArray());

                return view('main.phone_quote.assignedNewAutoApprochingDealer.index', compact('data'));
            } else {
                // Redirect if the user doesn't have the required roles
                return redirect('/loginn/');
            }
        } else {
            return redirect('/loginn/');
        }
    }

    public function AllNewAutosApproachStore_dealer(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'other_details' => 'nullable|string',
            // 'address' => 'nullable|string|unique:used_new_car_dealers',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'phone2' => 'nullable|string',
            'phone3' => 'nullable|string',
            'state_add' => 'nullable|string',
            'email' => 'nullable',
            'website' => 'nullable|string',
            'type' => 'nullable|string',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            return back()->with(['flash_message' => $validator->errors()], 400);
        }

        // Get the authenticated user
        $user = Auth::user();

        // Handle the case where user is not authenticated
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            // Create the record
            $newDealer = ShipperDetailsDealer::insert([
                'user_id' => 0,
                'name' => $request->input('name'),
                'other_details' => $request->input('other_details'),
                'address' => $request->input('address'),
                'phone' => $request->input('phone'),
                'phone2' => $request->input('phone2'),
                'phone3' => $request->input('phone3'),
                'states' => $request->input('state_add'),
                'email' => $request->input('email'),
                'website' => $request->input('website'),
                'type' => $request->input('type'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            return back()->with('message', 'Dealer created successfully');
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error creating dealer: ' . $e->getMessage());

            // Redirect back with an error message
            return back()->with('error', 'Failed to create dealer. Please try again.');
        }
    }

    public function NewvalidateField_dealer(Request $request)
    {
        $fieldName = $request->input('field_name');
        $fieldValue = $request->input('field_value');
        $relatedNames = [];

        switch ($fieldName) {
            case 'name':
                $exists = ShipperDetailsDealer::where('name', $fieldValue)->exists();
                if ($exists) {
                    $relatedNames = ShipperDetailsDealer::where('name', $fieldValue)
                        ->select('name', 'states')
                        ->get()
                        ->map(function ($item) {
                            return $item->name . ' (' . $item->states . ')';
                        })
                        ->toArray();
                }
                break;
            case 'phone':
                // Remove all non-numeric characters from the input phone number
                $normalizedPhone = preg_replace('/\D/', '', $fieldValue);

                // Normalize phone numbers in the database and check for existence
                $exists = ShipperDetailsDealer::whereRaw("REGEXP_REPLACE(phone, '[^0-9]', '') = ?", [$normalizedPhone])->exists();
                break;
            case 'email':
                $exists = ShipperDetailsDealer::where('email', $fieldValue)->exists();
                break;
            case 'address':
                $exists = ShipperDetailsDealer::where('address', $fieldValue)->exists();
                break;
            default:
                $exists = false;
        }

        $response = [
            'valid' => !$exists,
            'message' => $exists ? 'This ' . $fieldName . ' already exists.' : '',
            'related_names' => $relatedNames
        ];

        return response()->json($response);
    }

    public function storeApproachingAssign(Request $request)
    {

        if (Auth::check()) {
            $user = Auth::user();
            $ptype = $this->check_user_setting(Auth::user()->id);
            $setting = general_setting::first();

            if(isset($request->order_ids)) {
                $check = ApproachingAssign::where('orderTaker', $request->orderTaker)->first();
                $order_ids = explode(',', $request->order_ids);
                if ($check) {
                    $check->orderTaker = $request->orderTaker;
                    $check->status =  $request->status;
                    $check->delivery_city =  $request->delivery_city;
                    $check->delivery_zip =  $request->delivery_zip;
                    $check->oterminal =  $request->oterminal;
                    $check->call_type =  $request->call_type;
                    $check->date_range =  $request->date_range2;
                    $check->recordsAllowed = $request->recordsAllowed;
                    $check->save();
                } else {
                    $data = new ApproachingAssign;
                    $data->orderTaker = $request->orderTaker;
                    $data->status =  $request->status;
                    $data->delivery_city =  $request->delivery_city;
                    $data->delivery_zip =  $request->delivery_zip;
                    $data->oterminal =  $request->oterminal;
                    $data->call_type =  $request->call_type;
                    $data->date_range =  $request->date_range2;
                    $data->recordsAllowed = $request->recordsAllowed;
                    $data->save();
                }

                AutoOrder::whereIn('id', $order_ids)->update(['approaching_user' => $request->orderTaker]);
            }

            return back();
        } else {
            return redirect('/loginn/');
        }
    }

    public function Approaching_report(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $ptype = $this->check_user_setting(Auth::user()->id);
            if ($ptype == 1) {
                $phoneaccess = explode(',', Auth::user()->emp_access_phone);
            } elseif ($ptype == 2) {
                $phoneaccess = explode(',', Auth::user()->emp_access_web);
            } elseif ($ptype == 3) {
                $phoneaccess = explode(',', Auth::user()->emp_access_test);
            } elseif ($ptype == 4) {
                $phoneaccess = explode(',', Auth::user()->panel_type_4);
            } elseif ($ptype == 5) {
                $phoneaccess = explode(',', Auth::user()->panel_type_5);
            } elseif ($ptype == 6) {
                $phoneaccess = explode(',', Auth::user()->panel_type_6);
            } else {
                $phoneaccess = [];
            }

            // Check if the user has role 1 or 2
            if ($user->role == 1 || $user->role == 9 || in_array("70",$phoneaccess)) {
                $ptype = $this->check_user_setting($user->id);
                $setting = general_setting::first();

                $data = AutoOrder::where('approaching_user', '!=', 0)->with('callCount', 'history')
                    ->has('callCount')
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);

                return view('main.phone_quote.new.Approaching_report', compact('data'));
            } else {
                // Redirect if the user doesn't have the required roles
                return redirect('/loginn/');
            }
        } else {
            return redirect('/loginn/');
        }
    }

    public function filterNewAssignedApproaching_report(Request $request)
    {

        if (Auth::check()) {
            $user = Auth::user();
            $ptype = $this->check_user_setting(Auth::user()->id);
            $setting = general_setting::first();
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');
            $historyUser = $request->input(
                'orderTaker'
            );

            $approaching_assing = ApproachingAssign::orderby('id','desc')->pluck('orderTaker')->toArray();

            $data = call_history::with([
                'auto_order.callCount',
                'user'
            ])->where('approach',1)->where('pstatus',99)->orderby('id','desc');

            if ($request->has('orderTaker') && empty($request->orderTaker)) {
                $data->whereIn('userId', $approaching_assing);
            }


            if ($request->has('orderTaker') && $request->orderTaker !== null) {
                $historyUser = $request->orderTaker;
                $data->where('userId', $historyUser);
            }


            if ($request->has('startDate') && $request->startDate !== null && $request->has('endDate') && $request->endDate !== null) {

                $startDate = date('Y-m-d 00:00:00',strtotime($request->startDate));
                $endDate = date('Y-m-d 23:59:59',strtotime($request->endDate));

                $data->where(function ($q) use ($startDate, $endDate) {
                    $q->where('created_at', '>=', $startDate)
                        ->where('created_at', '<=', $endDate);
                });
            }

            return $data->get();
        } else {
            return redirect('/loginn/');
        }
    }

    public function historyApproaching(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 2) {
            $user_id = $user->id;
        } else {
            $user_id = $request->user_id;
        }

        return call_history::with('user')->where('orderId', $request->company_id)->get();

    }

    public function approaching_assign_user(Request $request)
    {
        $historyUser = $request->input(
            'orderTaker'
        );
        $data = ApproachingAssign::with('user')->orderby('id','desc')
            ->where(function ($query) use($historyUser){
                if(!empty($historyUser)){
                    $query->where('orderTaker',$historyUser);
                }
            })
            ->paginate();
        if($request->ajax()){
            return view('main.phone_quote.new.Approaching_assign_table', compact('data'));
        }

        return view('main.phone_quote.new.Approaching_assign', compact('data'));
    }

    public function approaching_save_email(Request $request)
    {
        $updateData = [
            'email' => $request->email ?? null,
        ];

        AutoOrder::where('id', $request->comp_id)->update($updateData);


        if ($request->email != null) {
            return $request->email;
        } else {
            return back();
        }
    }

    public function getProfileCard(Request $request)
    {
        $order = AutoOrder::find($request->id);
        $phone = $order->ophone ?? $request->ophone;

        // Prepare Quote/Follow-up Info
        $recent_follow_up = AutoOrder::with('orderTaker', 'order_pstatus')
            ->where('id', '!=', $request->id)
            ->where('ophone', 'like', "%$phone%")
            ->orderBy('updated_at', 'desc')
            ->first();

        $first_quote = AutoOrder::with('orderTaker', 'order_pstatus')
            ->where('id', '!=', $request->id)
            ->where('ophone', 'like', "%$phone%")
            ->oldest()
            ->first();

        $data2 = [
            'first_quote_date'    => $first_quote?->created_at?->format('Y-m-d H:i:s'),
            'first_quote_status'  => $first_quote->order_pstatus->name ?? null,
            'first_quote_agent'   => $first_quote->orderTaker->name ?? null,

            'recent_follow_up'    => $recent_follow_up ? url('searchData?search=' . $recent_follow_up->id) : null,
            'recent_quote_date'   => $recent_follow_up?->created_at?->format('Y-m-d H:i:s'),
            'recent_quote_status' => $recent_follow_up->order_pstatus->name ?? null,
            'recent_quote_agent'  => $recent_follow_up->orderTaker->name ?? null,
        ];

        // 1. Check ProfileCard first
        $profile = ProfileCard::where('ophone', 'like', "%$phone%")->first();
        if ($profile) {
            $profileData = $profile->toArray();

            // Merge and override ProfileCard data with Quote Info
            return response()->json(['data' => array_merge($profileData, $data2), 'write' => 1]);
        }

        // 2. Fallback to AutoOrder if no ProfileCard
        if ($order) {
            $nameParts = explode(' ', $order->oname ?? '');
            $firstName = $nameParts[0] ?? '';
            $lastName = $nameParts[1] ?? '';
            $phones = explode('*^', $order->ophone);
            $mainPhone = trim($phones[0] ?? '');
            $alternatePhone = trim($phones[1] ?? '');

            $data = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'oemail' => $order->oemail,
                'ophone' => $mainPhone,
                'ophone2' => $alternatePhone,
                'oaddress' => $order->oaddress ?? '',
                'originzsc' => $order->originzsc,
                'payment_method' => $order->payment_method,
                'customer_type' => 'Customer',
                'oauction_1' => strtolower($order->oauction),
                'oauction_2' => strtolower($order->oauction),
                'oauction_3' => strtolower($order->oauction),
                'obuyer_no_1' => strtolower($order->obuyer_no),
                'obuyer_no_2' => strtolower($order->obuyer_no),
                'obuyer_no_3' => strtolower($order->obuyer_no),
                'business_name' => implode(' ', $nameParts),
                'nature_of_customer' => $order->nature_of_customer,
                // the rest fields stay null / empty
            ];

            return response()->json(['data' => array_merge($data, $data2), 'write' => 0]);
        }

        return response()->json(['message' => 'Customer not found'], 404);
    }


    public function updateProfileCard(Request $request)
    {
        $ptype = $this->check_user_setting(Auth::user()->id);
        $validated = $request->validate([
            'pc_order_id' => 'nullable|integer',
            'pc_first_name' => 'nullable|string',
            'pc_last_name' => 'nullable|string',
            'pc_oemail' => 'nullable|email',
            'pc_ophone' => 'nullable|string',
            'pc_ophone2' => 'nullable|string',
            'pc_address' => 'nullable|string',
            'pc_originzsc' => 'nullable|string',
            'pc_payment_method' => 'nullable|string',
            'pc_customer_type' => 'nullable|string|in:Customer,Dealer',
            'pc_business_name' => 'nullable|string',
            'pc_nature_of_customer' => 'nullable|string',

            'pc_1oterminal' => 'nullable|string',
            'pc_1oacution' => 'nullable|string',
            'pc_1oacutionpho' => 'nullable|string',
            'pc_1oacutionaccounttitle' => 'nullable|string',
            'pc_1oacutionaccountname' => 'nullable|string',
            'pc_1obuyer_no' => 'nullable|string',
            'pc_1gate_pass_pin' => 'nullable|string',
            'pc_1obuyer_lot_no' => 'nullable|string',

            'pc_2oterminal' => 'nullable|string',
            'pc_2oacution' => 'nullable|string',
            'pc_2oacutionpho' => 'nullable|string',
            'pc_2oacutionaccounttitle' => 'nullable|string',
            'pc_2oacutionaccountname' => 'nullable|string',
            'pc_2obuyer_no' => 'nullable|string',
            'pc_2gate_pass_pin' => 'nullable|string',
            'pc_2obuyer_lot_no' => 'nullable|string',

            'pc_3oterminal' => 'nullable|string',
            'pc_3oacution' => 'nullable|string',
            'pc_3oacutionpho' => 'nullable|string',
            'pc_3oacutionaccounttitle' => 'nullable|string',
            'pc_3oacutionaccountname' => 'nullable|string',
            'pc_3obuyer_no' => 'nullable|string',
            'pc_3gate_pass_pin' => 'nullable|string',
            'pc_3obuyer_lot_no' => 'nullable|string',

            'pc_recent_follow_up' => 'nullable|string',
            'pc_first_quote_date' => 'nullable|date',
            'pc_first_quote_status' => 'nullable|string',
            'pc_first_quote_agent' => 'nullable|string',
            'pc_recent_quote_date' => 'nullable|date',
            'pc_recent_quote_status' => 'nullable|string',
            'pc_recent_quote_agent' => 'nullable|string',
            'pc_customer_review' => 'nullable|string',
            'pc_average_margin' => 'nullable|numeric',
            'pc_customer_rating' => 'nullable|integer|min:1',
            'pc_social_profile_name' => 'nullable|string',
        ]);

        $profile = ProfileCard::updateOrCreate(
            [
                'ophone' => $validated['pc_ophone'] ?? null,
                'panel_type' => $ptype,
            ],
            [
                'first_name' => $validated['pc_first_name'] ?? null,
                'last_name' => $validated['pc_last_name'] ?? null,
                'oemail' => $validated['pc_oemail'] ?? null,
                'ophone2' => $validated['pc_ophone2'] ?? null,
                'oaddress' => $validated['pc_address'] ?? null,
                'originzsc' => $validated['pc_originzsc'] ?? null,
                'payment_method' => $validated['pc_payment_method'] ?? null,
                'customer_type' => $validated['pc_customer_type'] ?? null,
                'business_name' => $validated['pc_business_name'] ?? null,
                'nature_of_customer' => $validated['pc_nature_of_customer'] ?? null,

                // Auction 1 fields
                'auction_1_terminal' => $validated['pc_1oterminal'] ?? null,
                'auction_1_title' => $validated['pc_1oacution'] ?? null,
                'auction_1_phone' => $validated['pc_1oacutionpho'] ?? null,
                'auction_1_account' => $validated['pc_1oacutionaccounttitle'] ?? null,
                'auction_1_account_name' => $validated['pc_1oacutionaccountname'] ?? null,
                'auction_1_buyer_no' => $validated['pc_1obuyer_no'] ?? null,
                'auction_1_gate_pass_pin' => $validated['pc_1gate_pass_pin'] ?? null,
                'auction_1_lot_no' => $validated['pc_1obuyer_lot_no'] ?? null,

                // Auction 2 fields
                'auction_2_terminal' => $validated['pc_2oterminal'] ?? null,
                'auction_2_title' => $validated['pc_2oacution'] ?? null,
                'auction_2_phone' => $validated['pc_2oacutionpho'] ?? null,
                'auction_2_account' => $validated['pc_2oacutionaccounttitle'] ?? null,
                'auction_2_account_name' => $validated['pc_2oacutionaccountname'] ?? null,
                'auction_2_buyer_no' => $validated['pc_2obuyer_no'] ?? null,
                'auction_2_gate_pass_pin' => $validated['pc_2gate_pass_pin'] ?? null,
                'auction_2_lot_no' => $validated['pc_2obuyer_lot_no'] ?? null,

                // Auction 3 fields
                'auction_3_terminal' => $validated['pc_3oterminal'] ?? null,
                'auction_3_title' => $validated['pc_3oacution'] ?? null,
                'auction_3_phone' => $validated['pc_3oacutionpho'] ?? null,
                'auction_3_account' => $validated['pc_3oacutionaccounttitle'] ?? null,
                'auction_3_account_name' => $validated['pc_3oacutionaccountname'] ?? null,
                'auction_3_buyer_no' => $validated['pc_3obuyer_no'] ?? null,
                'auction_3_gate_pass_pin' => $validated['pc_3gate_pass_pin'] ?? null,
                'auction_3_lot_no' => $validated['pc_3obuyer_lot_no'] ?? null,

                // Quote and other fields
                'recent_follow_up' => $validated['pc_recent_follow_up'] ?? null,
                'first_quote_date' => $validated['pc_first_quote_date'] ?? null,
                'first_quote_status' => $validated['pc_first_quote_status'] ?? null,
                'first_quote_agent' => $validated['pc_first_quote_agent'] ?? null,
                'recent_quote_date' => $validated['pc_recent_quote_date'] ?? null,
                'recent_quote_status' => $validated['pc_recent_quote_status'] ?? null,
                'recent_quote_agent' => $validated['pc_recent_quote_agent'] ?? null,
                'customer_review' => $validated['pc_customer_review'] ?? null,
                'average_margin' => $validated['pc_average_margin'] ?? null,
                'customer_rating' => $validated['pc_customer_rating'] ?? null,
                'social_profile_name' => $validated['pc_social_profile_name'] ?? null,
            ]
        );

        if (!empty($validated['pc_order_id'])) {
            AutoOrder::where('id', $validated['pc_order_id'])->update(['profile_card_update' => 1]);
        }

        return back()->with('message', 'Updated successfully');
    }

    public function price_request_assign_dispatcher(Request $request)
    {
        if (!Auth::check()) {
            abort(403);
        }

        $state = ''; // keep if you use it elsewhere

        $data = User::query()
            ->where('role', 3)
            ->where('verify', 1)
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        if ($request->ajax()) {
            // returns only the table body + header + pagination
            return view('main.phone_quote.price_check_assign_dispatcher.table', compact('data', 'state'));
        }

        // full page
        return view('main.phone_quote.price_check_assign_dispatcher.index', compact('data', 'state'));
    }

    public function togglePriceCheck(Request $request, User $user)
    {
        // (Optional) add your own authorization gate/policy here
        $request->validate([
            'value' => ['required', 'in:0,1'],
        ]);

        $user->is_allow_price_check = (int) $request->input('value');
        $user->save();

        return response()->json([
            'ok' => true,
            'user_id' => $user->id,
            'value' => (int) $user->is_allow_price_check,
            'label' => $user->is_allow_price_check ? 'Allowed' : 'Blocked',
        ]);
    }


    public function autos_approach_new_carrier(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $ptype = $this->check_user_setting(Auth::user()->id);
            $setting = general_setting::first();
            $state = "";

            if ($user->role != 1 && $user->role != 9) {
                $data = ShipperDetailsCarrier::where('user_id', $user->id)
                    ->orderBy('id', 'DESC');

                $state = \App\User::with('assignedDataNewCarrier')
                    ->has('assignedDataNewCarrier')
                    ->find($user->id);
                if ($state) {
                    $record =$state->assignedDataNewCarrier->recordsAllowed;
                    $with_phone =$state->assignedDataNewCarrier->with_phone;
                    if($with_phone == 1){
                        $data->whereNotNull('phone');
                    }else{
                        $data->whereNull('phone');
                    }

                    $state = $state->assignedDataNewCarrier->state;
                } else {
                    $state = "";
                }
                $data = $data->paginate(20);

            } else {
                # code...
                $data = ShipperDetailsCarrier::orderBy('id', 'DESC')->paginate(20);
            }
            $template = Template::all();

            return view('main.phone_quote.ShipperDetailsCarrier.index', compact('data', 'state','template'));
        } else {
            return redirect('/loginn/');
        }
    }

    public function storeAutosApproachNew_carrier(Request $request)
    {

        if (Auth::check()) {
            $user = Auth::user();
            $ptype = $this->check_user_setting(Auth::user()->id);
            $setting = general_setting::first();

            $check = ShipperDetailsAssignCarrier::where('orderTaker', $request->orderTaker)->first();

            if ($check) {
                $check->state = implode(',', $request->state);
                $check->orderTaker = $request->orderTaker; //Dispatcher
                $check->type =  $request->type;
                $check->with_phone =  $request->w_phone2;
                $check->recordsAllowed = $request->recordsAllowed;
                $check->save();
            } else {
                $data = new ShipperDetailsAssignCarrier;
                $data->state = implode(',', $request->state);
                $data->orderTaker = $request->orderTaker; //Dispatcher
                $data->type =  $request->type;
                $data->with_phone =  $request->w_phone2;
                $data->recordsAllowed = $request->recordsAllowed;
                $data->save();
            }

            foreach ($request->state as $state) {
                ShipperDetailsCarrier::where('user_id', 0)
                    ->where('states', $state)
                    ->where('type', $request->type)
                    ->take($request->recordsAllowed)
                    ->update(['user_id' => $request->orderTaker]);
            }

            return back();
        } else {
            return redirect('/loginn/');
        }
    }

    public function autosApproachSearchNew_carrier(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $state = '';

            // Initialize query
            $query = ShipperDetailsCarrier::query();

            $search_as = $request->input('search_as',null);

            // Filter by user's assigned state if applicable
            if ($search_as == 2) { //search as normal user or himself
                $query->where('user_id', $user->id);
                $userState = \App\User::with('assignedDataNewCarrier')->has('assignedDataNewCarrier')->find($user->id);
                if ($userState) {
                    $state = $userState->assignedDataNewCarrier->states;
                    $record =$userState->assignedDataNewCarrier->recordsAllowed;
                    $with_phone =$userState->assignedDataNewCarrier->with_phone;
                    if($with_phone == 1){
                        $query->whereNotNull('phone');
                    }else{
                        $query->whereNull('phone');
                    }


                }
            }

            // Apply filters
            if ($request->has('state') && $request->state !== null) {
                $query->where('states', $request->state);
            }
            if ($request->has('orderTaker') && $request->orderTaker !== null) {
                $query->where('user_id', $request->orderTaker);
            }
            if ($request->has('emailsSent')) {
                if ($request->email == 1) {
                    $query->whereNotNull('email');
                } elseif ($request->email == 0) {
                    $query->WhereNull('email');
                }
            }
            if ($request->has('w_phone')) {

                if ($request->w_phone === "1") {
                    $query->whereNotNull('phone');
                } elseif ($request->w_phone === "0") {
                    $query->WhereNull('phone');
                }
            }
            if ($request->has('search') && $request->search !== null) {
                $query->where(function ($query2) use($request){
                    $query2->where('name', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('email', 'LIKE', '%' . $request->search . '%');
                });
            }
            if ($request->has('type') && $request->type !== null) {
                if ($request->type == 'Null') {
                    $query->whereNull('type');
                } else {
                    $query->where('type', $request->type);
                }
            }
            if ($request->has('email') && $request->email !== null) {
                if ($request->email == 1) {
                    $query->whereNotNull('email');
                } elseif ($request->email == 0) {
                    $query->WhereNull('email');
                }
            }
            if ($request->has('withHistory') && $request->withHistory !== null) {
                if ($request->withHistory == 1) {
                    $query->has('history');
                } elseif ($request->email == 0) {
                    $query->doesntHave('history');
                }
            }
            // dd($request->toArray(), $query->take(50)->get()->toArray());

            // Apply date range filter if provided in the request
            if ($request->has('startDate') && $request->has('endDate')) {
                $from = date('Y-m-d 00:00:00', strtotime($request->startDate));
                $to = date('Y-m-d 23:59:59', strtotime($request->endDate));

                if (!empty($from) && !empty($to)) {
                    $query->whereBetween('created_at', [$from, $to]);
                }
            }
            $send_template =  isset($request->send_template) ? $request->send_template  : 0;
            if ($send_template) {
                $template = $request->template;
                // Get the SQL with placeholders
                $sqlWithPlaceholders = $query->toSql();

                // Replace placeholders with actual bindings
                $bindings = $query->getBindings();
                $fullSql = vsprintf(str_replace('?', "'%s'", $sqlWithPlaceholders), $bindings);

                // Save query to SendTemplateEmail model
                $send_email = new SendTemplateEmail();
                $send_email->query_sql = $fullSql;
                $send_email->data_type = $request->type;
                $send_email->template_id = $template;
                $send_email->limit_data = $request->input('limit_data',500);
                $send_email->user_id = auth()->user()->id;
                $send_email->save();
            }
            $data = $query->orderby('id','desc')->paginate(20);


            return view('main.phone_quote.ShipperDetailsCarrier.table', compact('data', 'state'));
        }
    }

    public function autosApproachEmailAddNew_carrier(Request $request)
    {
        // dd($request->toArray());
        $updateData = [];

        if (!empty($request->email)) {
            $updateData['email'] = $request->email;
        }

        if (!empty($request->phone2)) {
            $updateData['phone2'] = $request->phone2;
        }
        if (!empty($request->phone)) {
            $updateData['phone'] = $request->phone;
        }

        if (!empty($request->phone3)) {
            $updateData['phone3'] = $request->phone3;
        }
        if (!empty($request->address)) {
            $updateData['address'] = $request->address;
        }

        if(!empty($request->comp_id)) {
            ShipperDetailsCarrier::where('id', $request->comp_id)->update($updateData);
        }

        $email = ShipperDetailsCarrier::where('id', $request->comp_id)->first();

        if ($request->email != null) {
            return $email;
        } else {
            return back();
        }
    }

    public function autosApproachPhoneCount_carrier(Request $request)
    {
        try {
            // Get the authenticated user's ID
            $user = Auth::user()->id;

            // Get the approach ID from the request
            $approach = $request->approachId;

            // You can use dd($user, $approach, $request->toArray()) for debugging purposes

            // Create a new entry in the WhatsappAutoApproachCount model
            ShipperDetailsPhoneCarrier::insert([
                'userId' => $user,
                'approachId' => $approach,
                'type' => $request->type,
            ]);

            // You can also return a success response or redirect as needed
            return response()->json(['message' => 'count added successfully'], 200);
        } catch (\Exception $e) {
            // Handle any exceptions that may occur during the process
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function autosApproachgetHistory_carrier(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 2) {
            $user_id = $user->id;
        } else {
            $user_id = $request->user_id;
        }

        return ShipperDetailsHistoriesCarrier::with('user')->where('company_id', $request->company_id)->get();

    }

    public function autosApproachgetstoreHistory_carrier(Request $request)
    {
        $user = Auth::user();
        $history = new ShipperDetailsHistoriesCarrier;
        $history->user_id = $user->id;
        $history->company_id = $request->CompanyID;
        $history->connectStatus = $request->connectStatus;
        $history->comment = $request->comment;
        $history->save();

        ShipperDetailsCarrier::where('id', $request->CompanyID)->update(['user_id' => 0]);

        return ShipperDetailsHistoriesCarrier::with('user')
            ->where('user_id', $user->id)
            ->where('company_id', $request->CompanyID)
            ->get();
    }

    public function filterNewAssignedAutos_carrier(Request $request)
    {

        if (Auth::check()) {
            $user = Auth::user();
            $ptype = $this->check_user_setting(Auth::user()->id);
            $setting = general_setting::first();
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');
            $historyUser = $request->input(
                'orderTaker'
            );

            // dd($request->toArray());
            if ($request->whatsapp == 'whatsapp') {
                // dd('ok');
                $data = ShipperDetailsCarrier::where('user_id', '!=', 0)->with('user', 'whatsappCallCount', 'history',  'history.user')
                    ->has('whatsappCallCount');

                if ($request->has('orderTaker') && $request->orderTaker !== null) {
                    $data->where('user_id', $request->orderTaker);
                }
                if ($request->has('state') && $request->state !== null) {
                    $data->where('states', $request->state);
                }
                if (
                    $request->has('startDate') && $request->startDate !== null &&
                    $request->has('endDate') && $request->endDate !== null
                ) {
                    $data->whereHas('whatsappCallCount', function ($q) use ($startDate, $endDate) {
                        $q->whereDate('created_at', '>=', $startDate)
                            ->whereDate('created_at', '<=', $endDate);
                    });
                }
            } else {

                $data = ShipperDetailsCarrier::with([
                    'user',
                    'callCount',
                    'history.user',
                    'history' => function ($query) {
                        // Order by history's created_at if needed
                        $query->orderBy('created_at', 'desc');
                    }
                ])
                    ->has('history');

                if (
                    $request->has('orderTaker') && $request->orderTaker !== null
                ) {
                    $historyUser = $request->orderTaker;

                    $data->whereHas('history', function ($q) use ($historyUser) {
                        $q->where('user_id', $historyUser);
                    });
                    // $data->where('user_id', $request->orderTaker);
                }

                if ($request->has('state') && $request->state !== null) {
                    $data->where('states', $request->state);
                }

                if (
                    $request->has('startDate') && $request->startDate !== null &&
                    $request->has('endDate') && $request->endDate !== null
                ) {

                    $startDate = $request->startDate;
                    $endDate = $request->endDate;

                    // $data->whereHas('history', function ($q) use ($startDate, $endDate) {
                    //     $q->whereBetween('created_at', [$startDate, $endDate]);
                    // });
                    $data->whereHas(
                        'history',
                        function ($q) use ($startDate, $endDate) {
                            $q->whereDate('created_at', '>=', $startDate)
                                ->whereDate('created_at', '<=', $endDate);
                        }
                    );
                }
            }

            // dd('okk', $request->toArray(), $data->get()->toArray());

            return $data->get();
        } else {
            return redirect('/loginn/');
        }
    }

    public function allNewAutosApproach_carrier(Request $request)
    {
        if (Auth::check()) {
            $ptype = $this->check_user_setting(Auth::user()->id);
            $user = Auth::user();

            if ($ptype == 1) {
                $phoneaccess = explode(',', Auth::user()->emp_access_phone);
            } elseif ($ptype == 2) {
                $phoneaccess = explode(',', Auth::user()->emp_access_web);
            } elseif ($ptype == 3) {
                $phoneaccess = explode(',', Auth::user()->emp_access_test);
            } elseif ($ptype == 4) {
                $phoneaccess = explode(',', Auth::user()->panel_type_4);
            } elseif ($ptype == 5) {
                $phoneaccess = explode(',', Auth::user()->panel_type_5);
            } elseif ($ptype == 6) {
                $phoneaccess = explode(',', Auth::user()->panel_type_6);
            } else {
                $phoneaccess = [];
            }

            // Check if the user has role 1 or 2
            if ($user->role == 1 || $user->role == 9 || in_array('144', $phoneaccess)) {
                $ptype = $this->check_user_setting($user->id);
                $setting = general_setting::first();

                $data = ShipperDetailsCarrier::where('user_id', '!=', 0)->with('user', 'callCount', 'history')
                    ->has('callCount')
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);

                //     ->get();
                // dd($data->toArray());

                return view('main.phone_quote.assignedNewAutoApprochingCarrier.index', compact('data'));
            } else {
                // Redirect if the user doesn't have the required roles
                return redirect('/loginn/');
            }
        } else {
            return redirect('/loginn/');
        }
    }

    public function AllNewAutosApproachStore_carrier(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'other_details' => 'nullable|string',
            // 'address' => 'nullable|string|unique:used_new_car_dealers',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'phone2' => 'nullable|string',
            'phone3' => 'nullable|string',
            'state_add' => 'nullable|string',
            'email' => 'nullable',
            'website' => 'nullable|string',
            'type' => 'nullable|string',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            return back()->with(['flash_message' => $validator->errors()], 400);
        }

        // Get the authenticated user
        $user = Auth::user();

        // Handle the case where user is not authenticated
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            // Create the record
            $newCarrier = ShipperDetailsCarrier::insert([
                'user_id' => 0,
                'name' => $request->input('name'),
                'other_details' => $request->input('other_details'),
                'address' => $request->input('address'),
                'phone' => $request->input('phone'),
                'phone2' => $request->input('phone2'),
                'phone3' => $request->input('phone3'),
                'states' => $request->input('state_add'),
                'email' => $request->input('email'),
                'website' => $request->input('website'),
                'type' => $request->input('type'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            return back()->with('message', 'Carrier created successfully');
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error creating dealer: ' . $e->getMessage());

            // Redirect back with an error message
            return back()->with('error', 'Failed to create dealer. Please try again.');
        }
    }

    public function NewvalidateField_carrier(Request $request)
    {
        $fieldName = $request->input('field_name');
        $fieldValue = $request->input('field_value');
        $relatedNames = [];

        switch ($fieldName) {
            case 'name':
                $exists = ShipperDetailsCarrier::where('name', $fieldValue)->exists();
                if ($exists) {
                    $relatedNames = ShipperDetailsCarrier::where('name', $fieldValue)
                        ->select('name', 'states')
                        ->get()
                        ->map(function ($item) {
                            return $item->name . ' (' . $item->states . ')';
                        })
                        ->toArray();
                }
                break;
            case 'phone':
                // Remove all non-numeric characters from the input phone number
                $normalizedPhone = preg_replace('/\D/', '', $fieldValue);

                // Normalize phone numbers in the database and check for existence
                $exists = ShipperDetailsCarrier::whereRaw("REGEXP_REPLACE(phone, '[^0-9]', '') = ?", [$normalizedPhone])->exists();
                break;
            case 'email':
                $exists = ShipperDetailsCarrier::where('email', $fieldValue)->exists();
                break;
            case 'address':
                $exists = ShipperDetailsCarrier::where('address', $fieldValue)->exists();
                break;
            default:
                $exists = false;
        }

        $response = [
            'valid' => !$exists,
            'message' => $exists ? 'This ' . $fieldName . ' already exists.' : '',
            'related_names' => $relatedNames
        ];

        return response()->json($response);
    }






}
