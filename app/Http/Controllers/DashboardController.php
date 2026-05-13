<?php

namespace App\Http\Controllers;

use App\AutoOrder;
use App\Mail\AgentActivatedEmail;
use App\Mail\SendCodeMail;
use App\ShipaQueryPhone;
use App\ShipaQueryHistories;
use App\ShipaAssign;
use App\ShipperDetailsDealer;
use App\ShipperDetailsHistoriesDealer;
use Illuminate\Http\Request;
use App\User;
use App\role;
use App\ShipaQuery;
use App\attendance;
use App\user_setting;
use App\AssignUsedAndNewOrderTaker;
use App\chat;
use App\UsedAndNewCarDealers;
use Session;
use Redirect;
use Hash;
use Mail;
use Auth;
use Illuminate\Support\Str;
use App\notifications;
use App\report;
use App\general_setting;
use Vinkla\Hashids\Facades\Hashids;
use DB;
use App\CustomChat;
use App\PublicOrderChat;
use App\Flag;
use App\ReportPassword;
use App\RoleAccess;
use Carbon\Carbon;
use App\UserScreenShot;
use App\OrderTakerClientAccess;
use App\OrderTakerQouteAccess;
use App\DailyQoute;
use App\FreezeUser;
use App\sheet_data;
use App\Guide;
use App\ReviewWebsiteLink;
use App\FieldLabel;
use App\UserCommission;
use App\GroupChat;
use App\PriceCheckerPrice;

class DashboardController extends Controller
{
    public function getDashboard(Request $request)
    {
        $date = date('Y-m-d');
        $user = Auth::user();
        $setting = general_setting::first();
        if (Auth::check()) {
            $attendance = attendance::where('user_id', '=', Auth::user()->id)
                ->where('attendance_date', 'like', '%' . date('Y-m-d') . '%')
                ->first();
            if (empty($attendance)) {
                $addattendance = new attendance();
                $addattendance->user_id = Auth::user()->id;
                $addattendance->attendance_date = date('Y-m-d h:i:s');
                $addattendance->logout = 'not_logout';
                $addattendance->save();
            }
            //chart4
            $year = date('Y');
            $month = date('m');
            $penaltype = "";
            $get_month = array();
            $allstatus = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
            for ($i = 0; $i < count($allstatus); $i++) {
                if (!empty($penaltype)) {
                    $get_month[$i] = AutoOrder::where('pstatus', $allstatus[$i])
                        ->where('paneltype', $penaltype)
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
                        ->whereYear('created_at', $year)
                        ->whereMonth('created_at', $month)
                        ->count();
                } else {
                    $get_month[$i] = AutoOrder::where('pstatus', $allstatus[$i])
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
                        ->whereYear('created_at', $year)
                        ->whereMonth('created_at', $month)
                        ->count();
                }
            }
            if (Auth::user()->userRole->name == 'Code Giver') {
                return redirect('/employees');
            } else if (Auth::user()->userRole->name == 'Chat Approver') {
                return redirect('/custom-chat');
            } else if (Auth::user()->userRole->name == 'Price Giver') {
                $link = ReviewWebsiteLink::where('status', 1)->get();
                $label = FieldLabel::all();
                // $data = AutoOrder::with('filterHistory', 'latestHistory', 'freight')->where('pstatus', '=', 0)
                //     ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                //     ->where('price_giver_allow', 1)
                //     ->where('given_price', null)
                //     ->orderBy('id', 'DESC')->paginate(20);
                $data = AutoOrder::with('filterHistory', 'latestHistory', 'freight')
                    ->where(function ($query) {
                        $query->where('paneltype', 4)
                            ->orWhere('price_giver_allow', 1);
                    })
                    ->where('pstatus', 0)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->where('given_price', null)
                    ->orderBy('id', 'DESC')
                    ->paginate(500);
                if($request->ajax()){
                    return view('main.dashboard.newLoad', compact('data', 'link', 'label'));
                }
                return view('main.dashboard.price_giver', compact('data', 'link', 'label'));
            } else if (Auth::user()->userRole->name == 'Admin') {
                $data = report::with('user')
                    ->where('created_at', 'like', "%$date%")->orderBy('created_at', 'desc')->get();
                //chat 3
                $all_status = array(0, 5, 8, 13, 14);
                $chart = array();
                for ($i = 0; $i < count($all_status); $i++) {
                    $current_year = date('Y');   //2021
                    $minimum_year = date('Y') - 5; //2016
                    while ($current_year > $minimum_year) {
                        if ($all_status[$i] == 0) {
                            $sql = AutoOrder::WhereYear('created_at', $current_year)
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
                        } else {
                            $sql = AutoOrder::WhereYear('created_at', $current_year)
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
                                ->where('pstatus', $all_status[$i])->count();
                        }
                        $current_year--;
                        if (empty($sql)) {
                            $sql = 0;
                        }
                        array_push($chart, $sql);
                    }
                }

                // dd($allstatus, 'ok');

                return view('main.dashboard.index', compact('data', 'chart', 'get_month'));
            } else if (Auth::user()->userRole->name == 'Check Price') {

                $data = PriceCheckerPrice::with('order')
                    ->where('price_giver_id', Auth::id())
                    ->whereBetween('created_at', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()])
                    ->where(function ($query) {
                        $query->whereNull('car')
                            ->whereNull('suv')
                            ->whereNull('pickup')
                            ->whereNull('van');
                    })
                    ->has('order')
                    ->orderBy('id', 'DESC')
                    ->take(10)
                    ->get();

                // dd('ok', $data->toArray());
                return view('main.dashboard.check_price', compact('data'));
            }

            return view('main.dashboard.index', compact('get_month'));
        } else {
            return redirect('/loginn/');
        }
    }

    public function fetchCheckPrice(Request $request)
    {
        // if (Auth::id() == 246) {

        $data = PriceCheckerPrice::with('order')
            ->where('price_giver_id', Auth::id())
            ->whereBetween('created_at', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()])
            ->where(function ($query) {
                $query->whereNull('car')
                    ->whereNull('suv')
                    ->whereNull('pickup')
                    ->whereNull('van');
            })
            ->has('order')
            ->orderBy('id', 'DESC')
            ->take(10)
            ->get();

        return view('fetchPrices_table', compact('data'));
        // } else {
        //     return false;
        // }
    }

    public function chart_view(Request $request)
    {
        $user = Auth::user();
        $year = $_GET['year'];
        $month = $_GET['month'];
        $penaltype = $_GET['penaly_type'];
        $get_month = array();
        $allstatus = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17);
        for ($i = 0; $i < count($allstatus); $i++) {
            if (!empty($penaltype)) {
                $get_month[$i] = AutoOrder::where('pstatus', $allstatus[$i])
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
                    ->where('paneltype', $penaltype)
                    ->WhereYear('created_at', $year)
                    ->WhereMonth('created_at', $month)
                    ->count();
            } else {
                $get_month[$i] = AutoOrder::where('pstatus', $allstatus[$i])
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
                    ->WhereYear('created_at', $year)
                    ->WhereMonth('created_at', $month)
                    ->count();
            }
        }
        return $get_month;
    }

    public function get_months_chart(Request $request)
    {
        $user = Auth::user();
        $year = $_GET['year'];
        //$month = $_GET['month'];
        $penaltype = $_GET['penaly_type'];
        $sql = "";
        $get_month = array();
        $allstatus = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
        for ($i = 0; $i < count($allstatus); $i++) {
            if (!empty($penaltype)) {
                $sql = AutoOrder::where(function ($q) use ($user) {
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
                })->whereYear('created_at', $year)
                    ->where('paneltype', $penaltype)
                    ->whereMonth('created_at', $allstatus[$i])
                    ->sum('payment');
                $get_month[$i] = floatval($sql);
            } else {
                $sql = AutoOrder::where(function ($q) use ($user) {
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
                })->whereYear('created_at', $year)
                    ->whereMonth('created_at', $allstatus[$i])
                    ->sum('payment');
                $get_month[$i] = flotval($sql);
            }
        }
        //dd($get_month);
        return $get_month;
    }

    public function number()
    {

        $no = [];
        $phone_number = AutoOrder::select('ophone')->where('ophone', '<>', '')->where(function ($q) {
            $q->where('ophone', 'LIKE', '%(%');
        })->groupBy(['ophone'])->get();
        if (isset($phone_number[0])) {
            foreach ($phone_number as $key => $val) {
                $phone_number[$key]->ophone = explode('*^', $val->ophone);
            }

            foreach ($phone_number as $key => $value) {
                foreach ($value->ophone as $key2 => $value2) {
                    if ($value2 <> '') {
                        if (strpos($value2, '_') === false) {
                            if (!in_array($value2, $no)) {
                                array_push($no, $value2);
                            }
                        }
                    }
                }
            }
        }
        $no = array_unique($no);
        sort($no);

        return $no;
    }

    public function add_employee()
    {
        $data = role::all();
        $no = $this->number();

        $all_ot = User::where('deleted', 0)
            ->whereHas('userRole', function ($q) {
                $q->where('name', 'CSR')->orWhere('name', 'Seller Agent')->orWhere('name', 'Order Taker');
            })
            ->orderBy('is_login', 'DESC')->get();

        $managers = User::where('deleted', 0)
            ->whereHas('userRole', function ($q) {
                $q->where('name', 'Manager');
            })
            ->orderBy('is_login', 'DESC')->get();

        $disableNo = OrderTakerClientAccess::all();
        $diabledAccess = OrderTakerQouteAccess::all();

        $sheet_data = sheet_data::orderby('id', 'desc')->get();


        // echo "<pre>";
        // print_r($no);
        // exit();
        if (Auth::check()) {
            return view('main.register.index', compact('data', 'no', 'all_ot', 'managers', 'disableNo', 'diabledAccess', 'sheet_data'));
        } else {
            return redirect('/loginn/');
        }
    }

    public function view_employee()
    {
        $roles = role::with([
            'users' => function ($q) {
                $q->orderBy('is_login', 'DESC');
            }
        ])->withCount('users')
            ->where('level', 1)
            ->get();
        $deleted = User::where('deleted', 1)->orderBy('is_login', 'DESC')->get();

        // Fetch HR employee status for all linked users (shared DB)
        $hrEmployees = \Illuminate\Support\Facades\DB::table('hr_employees as e')
            ->join('hr_employee_statuses as s', 'e.employee_status_id', '=', 's.id')
            ->select('e.id as hr_id', 'e.agent_id', 's.name as hr_status', 'e.employee_status_id as hr_status_id')
            ->whereNotNull('e.agent_id')
            ->get()
            ->keyBy('agent_id');

        if (Auth::check()) {
            return view('main.register.view_register', compact('roles', 'deleted', 'hrEmployees'));
        } else {
            return redirect('/loginn/');
        }
    }

    public function screen_shots($id)
    {
        $data = User::find($id);
        $ss = UserScreenShot::whereDate('created_at', date('Y-m-d'))->where('user_id', $id)->orderBy('created_at', 'DESC')->paginate(5);
        // echo "<pre>";
        // print_r($data->toArray());
        // exit();

        return view('main.register.screen_shots', compact('data', 'ss'));
    }

    public function user_ss(Request $request)
    {
        $ss = UserScreenShot::whereDate('created_at', $request->created_at)->where('user_id', $request->user_id)->orderBy('created_at', 'DESC')->paginate(5);

        return view('main.register.show_screen_shots', compact('ss'));
    }

    public function edit_employee($id)
    {

        $data2 = User::with(['userRole', 'ot_manager', 'assignedData'])->where('id', $id)->first();
        $data = role::all();
        $guide = Guide::all();
        $penaltype = user_setting::where('user_id', $id)->first();

        $no = $this->number();
        $disableNo = OrderTakerClientAccess::where('ot_id', '<>', $id)->get();

        $all_ot = User::where('deleted', 0)
            ->whereHas('userRole', function ($q) {
                $q->where('name', 'CSR')->orWhere('name', 'Seller Agent')->orWhere('name', 'Order Taker');
            })
            ->orderBy('is_login', 'DESC')->get();

        $access = OrderTakerQouteAccess::where('manager_id', $id)->get();
        $diabledAccess = OrderTakerQouteAccess::where('manager_id', '<>', $id)->get();


        $managers = User::where('deleted', 0)
            ->whereHas('userRole', function ($q) {
                $q->where('name', 'Manager');
            })
            ->orderBy('is_login', 'DESC')->get();
        $sheet_data = sheet_data::orderby('id', 'desc')->get();
        if (Auth::check()) {
            return view('main.register.edit_employee', compact('data', 'data2', 'penaltype', 'no', 'all_ot', 'access', 'managers', 'disableNo', 'diabledAccess', 'sheet_data', 'guide'));
        } else {
            return redirect('/loginn/');
        }
    }

    //    shariq
    public function update_password()
    {
        if (Auth::check()) {
            return view('main.register.update_password');
        } else {
            return redirect('/loginn/');
        }
    }
    public function update_password2()
    {
        if (Auth::check()) {
            return view('main.register.update_password2');
        } else {
            return redirect('/loginn/');
        }
    }

    public function update_password_post(Request $request)
    {
        $user = Auth::user()->id;
        $userLogin = User::where('id', $user)->first();
        if (Hash::check($request->old_password, $userLogin->password)) {
            $userLogin->password = Hash::make($request->password);
            $userLogin->save();
            return back()->with('msg', 'Password Updated successfully!');
            return redirect('/update_password');
        } else {
            return back()->with('err', 'Old Password Incorrect!');
            return redirect('/update_password');
        }
    }

    public function delete_employee($id)
    {
        if (Auth::check()) {
            $modal = User::find($id);
            $modal->deleted = 1;
            $modal->save();
            Session::flash('flash_message', 'Employee Deleted Successfully!!');
            return redirect('/view_employee/');
        } else {
            return redirect('/loginn/');
        }
    }

    public function recover_employee($id)
    {
        if (Auth::check()) {
            $modal = User::find($id);
            $modal->deleted = 0;
            $modal->save();
            Session::flash('flash_message', 'Employee Recover Successfully!!');
            return redirect('/view_employee/');
        } else {
            return redirect('/loginn/');
        }
    }

    public function user_active($id)
    {
        if (Auth::check()) {
            $modal = User::find($id);
            $modal->status = 1;
            $modal->save();

            try {
                Mail::to($modal->email)->send(new AgentActivatedEmail($modal->name, $modal->email));
            } catch (\Throwable $e) {
                \Log::warning('user_active: activation email failed', ['user_id' => $id, 'error' => $e->getMessage()]);
            }

            Session::flash('flash_message', 'Employee Data Successfully Saved');
            return redirect('/view_employee/');
        } else {
            return redirect('/loginn/');
        }
    }

    public function user_deactive($id)
    {
        if (Auth::check()) {
            $modal = User::find($id);
            $modal->status = 0;
            $modal->save();
            Session::flash('flash_message', 'Employee Data Successfully Saved');
            return redirect('/view_employee/');
        } else {
            return redirect('/loginn/');
        }
    }

    // public function freezeUnfreeze($id)
    // {
    //     if (Auth::check()) {
    //         $modal = User::find($id);
    //         if ($modal->freeze == 1) {
    //             $modal->freeze = 0;

    //             $data = FreezeUser::where('user_id', $id)->where('status', '=', 0)->first();
    //             if (empty($data)) {
    //                 $data = new FreezeUser;
    //                 $data->user_id = $id;
    //                 $data->freeze_time = date('Y-m-d h:i:s');
    //             }
    //             $data->unfreeze_time = date('Y-m-d h:i:s');
    //             $data->status = 1;
    //             $data->save();
    //         } else {
    //             $modal->freeze = 1;

    //             $data = new FreezeUser;
    //             $data->user_id = $id;
    //             $data->freeze_time = date('Y-m-d h:i:s');
    //             $data->save();
    //         }
    //         $modal->save();
    //         Session::flash('flash_message', 'Employee Data Successfully Saved');
    //         return redirect('/view_employee/');
    //     } else {
    //         return redirect('/loginn/');
    //     }
    // }

    public function freezeUnfreeze(Request $request, $id)
    {
        if (Auth::check()) {
            $modal = User::find($id);
            $action = $request->input('action');
            $reason = $request->input('reason');

            if ($action === 'unfreeze') {
                $modal->freeze = 0;

                $data = FreezeUser::where('user_id', $id)->where('status', '=', 0)->first();
                if (empty($data)) {
                    $data = new FreezeUser;
                    $data->user_id = $id;
                    $data->freeze_time = date('Y-m-d h:i:s');
                    $data->unfreeze_reason = $reason;
                }
                $data->unfreeze_reason = $reason;
                $data->unfreeze_time = date('Y-m-d h:i:s');
                $data->status = 1;
                $data->save();
            } else if ($action === 'freeze') {
                $modal->freeze = 1;

                $data = new FreezeUser;
                $data->user_id = $id;
                $data->freeze_time = date('Y-m-d h:i:s');
                $data->reason = $reason;
                $data->save();
            }

            $modal->save();
            Session::flash('flash_message', 'Employee Data Successfully Saved');
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
    }

    public function save_employee(Request $request)
    {
        // echo "<pre>";print_r($request->all());exit();
        $total_emp_access_phone = "";
        $total_emp_access_web = "";
        $total_emp_access_test = "";
        $total_emp_show_data = "";
        $total_emp_access_ship = "";
        $total_emp_access_profile = "";
        $total_emp_access_action = "";
        $total_emp_access_report = "";
        $total_sheet_access = "";
        $emp_access_phone = $request->emp_access_phone;
        $emp_access_web = $request->emp_access_web;
        $emp_access_test = $request->emp_access_test;
        // $panel_type_4 = $request->panel_type_4;
        // $panel_type_5 = $request->panel_type_5;
        // $panel_type_6 = $request->panel_type_6;
        $emp_show_data = $request->emp_show_data;
        $emp_access_ship = $request->emp_access_ship;
        $emp_access_profile = $request->emp_access_profile;
        $emp_access_action = $request->emp_access_action;
        $emp_access_report = $request->emp_access_report;
        $emp_sheet_access = $request->sheet_access;
        // dd($request->emp_access_phone);
        if ($request->emp_access_phone <> null) {
            $total_emp_access_phone = implode(",", $emp_access_phone);
        }

        if ($request->emp_access_web <> null) {
            $total_emp_access_web = implode(",", $emp_access_web);
        }
        if ($request->emp_access_test <> null) {
            $total_emp_access_test = implode(",", $emp_access_test);
        }
        // if ($request->panel_type_4 <> null) {
        //     $total_panel_type_4 = implode(",", $panel_type_4);
        // }
        // if ($request->panel_type_5 <> null) {
        //     $total_panel_type_5 = implode(",", $panel_type_5);
        // }
        // if ($request->panel_type_6 <> null) {
        //     $total_panel_type_6 = implode(",", $panel_type_6);
        // }
        if ($request->emp_show_data <> null) {
            $total_emp_show_data = implode(",", $emp_show_data);
        }
        if ($request->emp_access_ship <> null) {
            $total_emp_access_ship = implode(",", $emp_access_ship);
        }
        if ($request->emp_access_profile <> null) {
            $total_emp_access_profile = implode(",", $emp_access_profile);
        }
        if ($request->emp_access_action <> null) {
            $total_emp_access_action = implode(",", $emp_access_action);
        }
        if ($request->emp_access_report <> null) {
            $total_emp_access_report = implode(",", $emp_access_report);
        }
        if ($request->sheet_access <> null) {
            $total_sheet_access = implode(",", $emp_sheet_access);
        }
        $phone = str_replace("-", "", $request->phone_number);
        $usrChk = User::where('email', $request->email)->first();
        if ($usrChk == '') {
            $full_name = $request->first_name . ' ' . $request->last_name;
            $emp = new User();
            $emp->name = $request->first_name;
            $emp->last_name = $request->last_name;
            $emp->slug = $request->slug;
            $emp->username = Str::slug($full_name, '-');
            $emp->email = $request->email;
            $emp->password = Hash::make($request->password);
            $emp->role = $request->job_type;
            $emp->phone = $phone;
            $emp->address = $request->address;
            $emp->cancellation_amount = $request->cancellation_amount ?? 0;
            $emp->per_review = $request->per_review ?? 0;
            $emp->private_pickup = $request->private_pickup ?? 0;
            $emp->commission = $request->commission ?? 0;
            $emp->status = 1;
            $emp->emp_access_phone = $total_emp_access_phone;
            $emp->emp_access_web = $total_emp_access_web;
            $emp->emp_access_test = $total_emp_access_test;
            // $emp->panel_type_4 = $total_panel_type_4;
            // $emp->panel_type_5 = $total_panel_type_5;
            // $emp->panel_type_6 = $total_panel_type_6;
            $emp->emp_show_data = $total_emp_show_data;
            $emp->emp_access_ship = $total_emp_access_ship;
            $emp->emp_access_profile = $total_emp_access_profile;
            $emp->emp_access_action = $total_emp_access_action;
            $emp->emp_access_report = $total_emp_access_report;
            $emp->order_taker_quote = $request->order_taker_quote ?? 0;
            $emp->assign_daily_qoute = $request->assign_daily_qoute ?? 0;
            $emp->sheet_access = $total_sheet_access;
            if ($emp->role == 3) {
                $emp->auto_assign = $request->auto_assign ?? 0;
                $emp->shipment_status_quote_assign = $request->shipment_status_quote_assign ?? 0;
            }
            $emp->save();
            $usersetting = new user_setting();
            $usersetting->penal_type = $request->penalytype;
            $usersetting->user_id = $emp->id;
            $usersetting->save();

            $role = role::find($request->job_type);
            if (isset($request->assign_daily_qoute)) {
                if ($request->assign_daily_qoute > 0) {
                    $daily = new DailyQoute;
                    $daily->user_id = $emp->id;
                    $daily->total_qoute = $request->assign_daily_qoute;
                    $daily->date = date('Y-m-d');
                    $daily->save();
                }
            }

            if (isset($role->id)) {
                if ($role->name == 'Order Taker' || $role->name == 'Seller Agent' || $role->name == 'CSR') {
                    if ($request->client_number > 0) {
                        foreach ($request->client_number as $key => $val) {
                            $acc = new OrderTakerClientAccess;
                            $acc->ot_id = $emp->id;
                            $acc->client_number = $val;
                            $acc->save();
                        }
                    }

                    if ($request->order_taker_quote == 2) {
                        if (isset($request->manager)) {
                            $acc = new OrderTakerQouteAccess;
                            $acc->manager_id = $request->manager;
                            $acc->ot_ids = $emp->id;
                            $acc->status = 1;
                            $acc->save();
                        }
                    }
                }
                if ($role->name == 'Manager') {
                    if ($request->all_ot > 0) {
                        foreach ($request->all_ot as $key => $val) {
                            $acc = new OrderTakerQouteAccess;
                            $acc->manager_id = $emp->id;
                            $acc->ot_ids = $val;
                            $acc->status = 1;
                            $acc->save();

                            $u = User::find($val);
                            $u->order_taker_quote = 2;
                            $u->save();
                        }
                    }
                }
            }

            redirect('/view_employee/');
            Session::flash('flash_message', 'Employee Data Successfully Saved');
            return "SUCCESS";
        } else {
            Session::flash('flash_message2', 'Email Already Exist');
            return "ALREADY EXIST";
        }
    }
    public function update_employee(Request $request)
    {
        // dd($request->toArray());
        $total_emp_access_phone = "";
        $total_emp_access_web = "";
        $total_emp_access_test = "";
        $total_panel_type_4 = "";
        $total_panel_type_5 = "";
        $total_panel_type_6 = "";
        $total_emp_show_data = "";
        $total_emp_access_ship = "";
        $total_emp_access_profile = "";
        $total_emp_access_action = "";
        $total_emp_access_report = "";
        $total_sheet_access = "";
        $total_emp_access_guide = "";
        $emp_access_phone = $request->emp_access_phone;
        $emp_access_web = $request->emp_access_web;
        $emp_access_test = $request->emp_access_test;
        $panel_type_4 = $request->panel_type_4;
        $panel_type_5 = $request->panel_type_5;
        $panel_type_6 = $request->panel_type_6;
        $emp_show_data = $request->emp_show_data;
        $emp_access_ship = $request->emp_access_ship;
        $emp_access_profile = $request->emp_access_profile;
        $emp_access_action = $request->emp_access_action;
        $emp_access_report = $request->emp_access_report;
        $emp_sheet_access = $request->sheet_access;
        $emp_access_guide = $request->emp_access_guide;
        $emp_panel_access = $request->emp_panel_access;
        if ($request->emp_access_phone <> null) {
            $total_emp_access_phone = implode(",", $emp_access_phone);
        }
        if ($request->emp_access_web <> null) {
            $total_emp_access_web = implode(",", $emp_access_web);
        }
        if ($request->emp_access_test <> null) {
            $total_emp_access_test = implode(",", $emp_access_test);
        }
        if ($request->panel_type_4 <> null) {
            $total_panel_type_4 = implode(",", $panel_type_4);
        }
        if ($request->panel_type_5 <> null) {
            $total_panel_type_5 = implode(",", $panel_type_5);
        }
        if ($request->panel_type_6 <> null) {
            $total_panel_type_6 = implode(",", $panel_type_6);
        }
        if ($request->emp_show_data <> null) {
            $total_emp_show_data = implode(",", $emp_show_data);
        }
        if ($request->emp_access_ship <> null) {
            $total_emp_access_ship = implode(",", $emp_access_ship);
        }
        if ($request->emp_access_profile <> null) {
            $total_emp_access_profile = implode(",", $emp_access_profile);
        }
        if ($request->emp_access_action <> null) {
            $total_emp_access_action = implode(",", $emp_access_action);
        }
        if ($request->emp_access_report <> null) {
            $total_emp_access_report = implode(",", $emp_access_report);
        }
        if ($request->sheet_access <> null) {
            $total_sheet_access = implode(",", $emp_sheet_access);
        }
        if ($request->emp_access_guide <> null) {
            $total_emp_access_guide = implode(",", $emp_access_guide);
        }
        if ($request->emp_panel_access <> null) {
            $total_emp_panel_access = implode(",", $emp_panel_access);
        }

        $phone = str_replace("-", "", $request->phone_number);
        $emp = User::find($request->user_id);
        $assign_daily_qoute = $emp->assign_daily_qoute;
        $full_name = $request->first_name . ' ' . $request->last_name;
        $emp->name = $request->first_name;
        $emp->last_name = $request->last_name;
        $emp->slug = $request->slug;
        $emp->username = Str::slug($full_name, '-');
        $emp->email = $request->email;
        if ($request->password) {
            $emp->password = Hash::make($request->password);
        }
        $emp->role = $request->job_type;
        $emp->phone = $phone;
        $emp->address = $request->address;
        $emp->cancellation_amount = $request->cancellation_amount ?? 0;
        $emp->per_review = $request->per_review ?? 0;
        $emp->private_pickup = $request->private_pickup ?? 0;
        $emp->commission = $request->commission ?? 0;
        $emp->status = 1;
        $emp->emp_access_phone = $total_emp_access_phone;
        $emp->emp_access_web = $total_emp_access_web;
        $emp->emp_access_test = $total_emp_access_test;
        $emp->panel_type_4 = $total_panel_type_4;
        $emp->panel_type_5 = $total_panel_type_5;
        $emp->panel_type_6 = $total_panel_type_6;
        $emp->emp_show_data = $total_emp_show_data;
        $emp->emp_access_ship = $total_emp_access_ship;
        $emp->emp_access_profile = $total_emp_access_profile;
        $emp->emp_access_action = $total_emp_access_action;
        $emp->emp_access_report = $total_emp_access_report;
        $emp->order_taker_quote = $request->order_taker_quote ?? 0;
        $emp->assign_daily_qoute = $request->assign_daily_qoute ?? 0;
        $emp->private_OT = $request->private_OT ?? 0;
        $emp->sheet_access = $total_sheet_access;
        $emp->emp_panel_access = $total_emp_panel_access;
        $emp->emp_access_guide = $total_emp_access_guide;
        if ($emp->role == 3) {
            $emp->auto_assign = $request->auto_assign ?? 0;
            $emp->shipment_status_quote_assign = $request->shipment_status_quote_assign ?? 0;
        }
        $emp->save();
        $usersetting = user_setting::where('user_id', $request->user_id)->first();
        if (!empty($usersetting)) {
            $usersetting->penal_type = $request->penalytype;
            $usersetting->call_type = (
                (is_array($request->emp_access_phone) && in_array("134", $request->emp_access_phone)) ||
                (is_array($request->emp_access_web) && in_array("134", $request->emp_access_web))
            ) ? 134 : 135;
            $usersetting->save();
        } else {
            $usersetting = new user_setting();
            $usersetting->penal_type = $request->penalytype;
            $usersetting->call_type = (
                (is_array($request->emp_access_phone) && in_array("134", $request->emp_access_phone)) ||
                (is_array($request->emp_access_web) && in_array("134", $request->emp_access_web))
            ) ? 134 : 135;
            $usersetting->user_id = $emp->id;
            $usersetting->save();
        }


        $role = role::find($request->job_type);

        if (isset($request->assign_daily_qoute)) {
            if ($request->assign_daily_qoute > 0) {
                $daily = DailyQoute::where('user_id', $emp->id)->whereDate('date', date('Y-m-d'))->first();
                if (empty($daily)) {
                    $daily = new DailyQoute;
                    $daily->user_id = $emp->id;
                    $daily->total_qoute = $request->assign_daily_qoute;
                    $daily->date = date('Y-m-d');
                    $daily->save();
                } else {
                    if ($assign_daily_qoute > 0) {
                        if ($assign_daily_qoute >= $request->assign_daily_qoute) {
                            $total = $assign_daily_qoute - $request->assign_daily_qoute;
                        } else {
                            $total = $request->assign_daily_qoute - $assign_daily_qoute;
                        }
                        $total = $total + $daily->total_qoute;
                    } else {
                        $total = $request->assign_daily_qoute;
                    }
                    $daily->total_qoute = $total;
                    $daily->save();
                }
            }
        }

        if (isset($role->id)) {
            if ($role->name == 'Order Taker' || $role->name == 'Seller Agent' || $role->name == 'CSR') {
                OrderTakerClientAccess::where('ot_id', $request->user_id)->delete();
                if ($request->client_number > 0) {
                    foreach ($request->client_number as $key => $val) {
                        $acc = new OrderTakerClientAccess;
                        $acc->ot_id = $emp->id;
                        $acc->client_number = $val;
                        $acc->save();
                    }
                }

                if ($request->order_taker_quote == 2) {
                    if (isset($request->manager)) {
                        $acc = OrderTakerQouteAccess::where('ot_ids', $request->user_id)->first();
                        if (!isset($acc->id)) {
                            $acc = new OrderTakerQouteAccess;
                            $acc->status = 1;
                        }
                        $acc->manager_id = $request->manager;
                        $acc->ot_ids = $emp->id;
                        $acc->save();
                    }
                }
            }
            if ($role->name == 'Manager') {
                OrderTakerQouteAccess::where('manager_id', $emp->id)->delete();
                if ($request->all_ot > 0) {
                    foreach ($request->all_ot as $key => $val) {
                        $acc = OrderTakerQouteAccess::where('ot_ids', $val)->first();
                        if (!isset($acc->id)) {
                            $acc = new OrderTakerQouteAccess;
                            $acc->status = 1;
                        }
                        $acc->manager_id = $emp->id;
                        $acc->ot_ids = $val;
                        $acc->save();

                        $u = User::find($val);
                        $u->order_taker_quote = 2;
                        $u->save();
                    }
                }
            }
        }

        if ($request->has('recordsAllowed') && $request->recordsAllowed !== null) {

            $check = AssignUsedAndNewOrderTaker::where('orderTaker', $request->orderTaker)
                ->first();

            if ($check) {
                // $assign = UsedAndNewCarDealers::where('user_id', $request->orderTaker)
                //     ->update(['user_id' => 0]);

                $check->state = implode(',', $request->state);
                $check->orderTaker = $request->orderTaker;
                $check->category = $request->category_assign;
                $check->recordsAllowed = $request->recordsAllowed;
                $check->save();
            } else {

                $data = new AssignUsedAndNewOrderTaker;
                $data->state = implode(',', $request->state);
                $data->orderTaker = $request->orderTaker;
                $data->recordsAllowed = $request->recordsAllowed;
                $data->category = $request->category_assign;
                $data->save();
            }

            foreach ($request->state as $key => $row) {
                $assign = UsedAndNewCarDealers::where('user_id', 0)
                    ->where('state', 'LIKE', '%' . $row . '%')
                    ->where('category', 'LIKE', '%' . $request->category_assign . '%')
                    ->take($request->recordsAllowed)
                    ->update(['user_id' => $request->orderTaker]);
            }
        }


        redirect('/view_employee/');
        Session::flash('flash_message', 'Employee Data Successfully Saved');
        return "SUCCESS";
    }
    public function general_setting_add()
    {
        if (Auth::user()->role == 1) {
            $data['no_days'] = 0;
            $data = general_setting::orderby('id', 'desc')->first();
            if (empty($data)) {
                $data['no_days'] = 0;
            }
            return view('main.general_setting', compact('data'));
        } else {
            return redirect('/dashboard');
        }
    }

    public function store_general_setting(Request $request)
    {
        if (Auth::user()->role == 1) {
            $generalseeting = general_setting::orderby('id', 'desc')->first();
            if (empty($generalseeting)) {
                $generalseeting = new general_setting();
                $generalseeting->user_id = Auth::user()->id;
                $generalseeting->no_days = $request->no_days;
                $generalseeting->save();
                Session::flash('flash_message', 'Data Successfully Saved');
            } else {
                $generalseeting->no_days = $request->no_days;
                $generalseeting->save();
                Session::flash('flash_message', 'Data Successfully Saved');
            }
            return redirect('/general_setting_add');
        } else {
            return redirect('/dashboard');
        }
    }

    public function employees()
    {
        $roles = role::with([
            'users' => function ($q) {
                $q->orderBy('is_login', 'DESC');
            }
        ])->withCount('users')
            ->where('level', 1)
            ->get();

        if (Auth::check()) {
            return view('main.register.index-code-giver', compact('roles'));
        } else {
            return redirect('/loginn/');
        }
    }

    public function increase_quotes(Request $request)
    {
        $data = DailyQoute::where('user_id', $request->user_id)
            ->whereDate('date', date('Y-m-d'))
            ->orderBy('id', 'DESC')->first();

        if (empty($data)) {
            $data = new DailyQoute;
            $data->user_id = $request->user_id;
            $data->date = date('Y-m-d');
            $data->total_qoute = $data->total_qoute + $request->total_qoute;
        } else {
            $data->total_qoute = $request->total_qoute;
        }
        $data->save();

        return back()->with('msg', 'Extra Quotes has been assigned successfully');
    }

    public function customChat()
    {
        $id = Auth::id();
        // $chat = CustomChat::with('sender', 'receiver', 'flag')
        //     ->orderBy('status', 'ASC')
        //     ->orderBy('created_at', 'DESC')
        //     ->paginate(25);

        $chat = chat::where('status', 0)->orderBy('status', 'ASC')
            ->orderBy('created_at', 'DESC')
            ->paginate(25);


        $countCustom = chat::where('status', 0)->count();

        $public = PublicOrderChat::with('user', 'publicChat', 'flag')
            ->orderBy('status', 'ASC')
            ->orderBy('created_at', 'DESC')
            ->paginate(25);

        $countPublic = PublicOrderChat::where('status', 0)->count();

        $group = GroupChat::where('status', 0)->with('group', 'user')
            ->orderBy('status', 'ASC')
            ->orderBy('created_at', 'DESC')
            ->paginate(25);

        $countGroup = GroupChat::where('status', 0)->count();

        if (isset($public)) {
            $arr = [];
            foreach ($public as $key => $val) {
                $public[$key]->member = User::select('id', 'name', 'last_name', 'slug', 'is_login')->whereIn('id', explode(',', $val->publicChat->members))->where('id', '<>', $id)->count();
                $public[$key]->seen_by = User::select('id', 'name', 'last_name', 'slug', 'is_login')->whereIn('id', explode(',', $val->seen_by_user_id))->where('id', '<>', $id)->count();
            }
        }

        $users = User::with('userRole')->where('deleted', 0)->where(function ($q) {
            $q->where('role', 2)->orWhere('role', 3)->orWhere('role', 1)->orWhere('role', 9)->orWhere('role', 8)->orWhere('role', 14)->orWhere('role', 17)->orWhere('role', 18);
        })
            ->orderBy('freeze', 'DESC')
            ->paginate(25);

        $countUser = User::where('deleted', 0)->where(function ($q) {
            $q->where('role', 2)->orWhere('role', 3)->orWhere('role', 1)->orWhere('role', 9)->orWhere('role', 8)->orWhere('role', 14)->orWhere('role', 17)->orWhere('role', 18);
        })->where('freeze', 1)->count();

        $flag = User::with('userRole', 'flag', 'flag_count')->withCount('flag')->where('deleted', 0)->where(function ($q) {
            $q->where('role', 2)->orWhere('role', 3)->orWhere('role', 1)->orWhere('role', 9)->orWhere('role', 8)->orWhere('role', 14)->orWhere('role', 17)->orWhere('role', 18);
        })
            ->whereHas('flag', function ($q) {
                $q->where('custom_chat_id', '!=', 0)->orWhere('group_chat_id', '!=', 0);
            })
            ->orderBy('flag_count', 'DESC')
            ->orderBy('updated_at', 'DESC')
            //     ->get();
            ->paginate(25);

        // dd($flag->toArray());

        $countFlag = User::where('deleted', 0)->where(function ($q) {
            $q->where('role', 2)->orWhere('role', 3)->orWhere('role', 1)->orWhere('role', 9)->orWhere('role', 8)->orWhere('role', 14)->orWhere('role', 17)->orWhere('role', 18);
        })->whereHas('flag')->count();

        // echo "<pre>";
        // print_r($flag->toArray());
        // exit();
        if (Auth::check()) {
            return view('main.register.index-custom-chat', compact('chat', 'public', 'users', 'flag', 'countCustom', 'countPublic', 'countUser', 'countFlag', 'group', 'countGroup'));
        } else {
            return redirect('/loginn/');
        }
    }

    public function getCountOfChat()
    {

        $countCustom = chat::where('status', 0)->count();

        $countPublic = PublicOrderChat::where('status', 0)->count();

        $countGroup = GroupChat::where('status', 0)->count();

        $countUser = User::where('deleted', 0)->where(function ($q) {
            $q->where('role', 2)->orWhere('role', 3)->orWhere('role', 1)->orWhere('role', 9)->orWhere('role', 8)->orWhere('role', 14)->orWhere('role', 17)->orWhere('role', 18);
        })->where('freeze', 1)->count();

        $countFlag = User::where('deleted', 0)->where(function ($q) {
            $q->where('role', 2)->orWhere('role', 3)->orWhere('role', 1)->orWhere('role', 9)->orWhere('role', 8)->orWhere('role', 14)->orWhere('role', 17)->orWhere('role', 18);
        })->whereHas('flag')
            ->whereHas('flag', function ($q) {
                $q->where('custom_chat_id', '!=', 0)->orWhere('group_chat_id', '!=', 0);
            })
            ->count();

        return response()->json([
            'public' => $countPublic,
            'custom' => $countCustom,
            'group' => $countGroup,
            'user' => $countUser,
            'flag' => $countFlag,
            'status' => true,
            'status_code' => 200
        ], 200);
    }

    public function customChat2(Request $request)
    {
        $heading = $request->heading;
        $entry = $request->search ?? 'id';
        $entity = $request->entity ?? 25;
        $search = $request->value;
        $status = '';
        $id = Auth::id();

        if ($heading == 'Custom Chat') {
            if ($entry == 'status') {
                if ($search == 'Pending') {
                    $status = 0;
                } else if ($search == 'Approved') {
                    $status = 1;
                } else if ($search == 'Seen') {
                    $status = 2;
                } else {
                    $status = '';
                }
            }

            // $chat = CustomChat::with('sender', 'receiver', 'flag')
            //     ->where(function ($q) use ($search, $status, $entry) {
            //         if ($entry == 'order_id') {
            //             $q->where('order_id', 'LIKE', '%' . $search . '%');
            //         }
            //         if ($entry == 'message') {
            //             $q->where('message', 'LIKE', '%' . $search . '%');
            //         }
            //         if ($entry == 'message_date') {
            //             $q->where('message_date', 'LIKE', '%' . $search . '%');
            //         }
            //         if ($entry == 'message_time') {
            //             $q->where('message_time', 'LIKE', '%' . $search . '%');
            //         }
            //         if ($entry == 'status') {
            //             $q->where('status', 'LIKE', '%' . $status . '%');
            //         }
            //         if ($entry == 'sender_name') {
            //             $q->whereHas('sender', function ($q2) use ($search) {
            //                 $q2->where('name', 'LIKE', '%' . $search . '%')
            //                     ->orWhere('last_name', 'LIKE', '%' . $search . '%')
            //                     ->orWhere('slug', 'LIKE', '%' . $search . '%');
            //             });
            //         }
            //         if ($entry == 'receiver_name') {
            //             $q->whereHas('receiver', function ($q2) use ($search) {
            //                 $q2->where('name', 'LIKE', '%' . $search . '%')
            //                     ->orWhere('last_name', 'LIKE', '%' . $search . '%')
            //                     ->orWhere('slug', 'LIKE', '%' . $search . '%');
            //             });
            //         }
            //     })
            //     ->orderBy('status', 'ASC')
            //     ->orderBy('created_at', 'DESC')
            //     ->paginate($entity);

            $chat = chat::where('status', 0)->orderBy('status', 'ASC')
                ->orderBy('created_at', 'DESC')
                ->paginate($entity);

            $public = PublicOrderChat::with('user', 'publicChat', 'flag')
                ->orderBy('status', 'ASC')
                ->orderBy('created_at', 'DESC')
                ->paginate($entity);

            $group = GroupChat::where('status', 0)->with('group', 'user')
                ->orderBy('status', 'ASC')
                ->orderBy('created_at', 'DESC')
                ->paginate($entity);

            $users = User::with('userRole')->where('deleted', 0)->where(function ($q) {
                $q->where('role', 2)->orWhere('role', 3)->orWhere('role', 1)->orWhere('role', 9)->orWhere('role', 8)->orWhere('role', 14)->orWhere('role', 17)->orWhere('role', 18);
            })
                ->orderBy('freeze', 'DESC')
                ->paginate($entity);

            $flag = User::with('userRole', 'flag', 'flag_count')->withCount('flag')->where('deleted', 0)->where(function ($q) {
                $q->where('role', 2)->orWhere('role', 3)->orWhere('role', 1)->orWhere('role', 9)->orWhere('role', 8)->orWhere('role', 14)->orWhere('role', 17)->orWhere('role', 18);
            })
                ->whereHas('flag', function ($q) {
                    $q->where('custom_chat_id', '!=', 0)->orWhere('group_chat_id', '!=', 0);
                })
                ->orderBy('flag_count', 'DESC')
                ->orderBy('updated_at', 'DESC')
                ->paginate($entity);
        } else if ($heading == 'Public Chat') {
            if ($entry == 'status') {
                if ($search == 'Pending') {
                    $status = 0;
                } else if ($search == 'Approved') {
                    $status = 1;
                } else if ($search == 'Seen') {
                    $status = 2;
                } else {
                    $status = '';
                }
            }

            // $chat = CustomChat::with('sender', 'receiver', 'flag')
            //     ->orderBy('status', 'ASC')
            //     ->orderBy('created_at', 'DESC')
            //     ->paginate($entity);

            $chat = chat::where('status', 0)->orderBy('status', 'ASC')
                ->orderBy('created_at', 'DESC')
                ->paginate($entity);

            $public = PublicOrderChat::with('user', 'publicChat', 'flag')
                ->where(function ($q) use ($search, $status, $entry) {
                    if ($entry == 'order_id') {
                        $q->where('order_id', 'LIKE', '%' . $search . '%');
                    }
                    if ($entry == 'message') {
                        $q->where('message', 'LIKE', '%' . $search . '%');
                    }
                    if ($entry == 'message_date') {
                        $q->where('message_date', 'LIKE', '%' . $search . '%');
                    }
                    if ($entry == 'message_time') {
                        $q->where('message_time', 'LIKE', '%' . $search . '%');
                    }
                    if ($entry == 'status') {
                        $q->where('status', 'LIKE', '%' . $status . '%');
                    }
                    if ($entry == 'sender_name') {
                        $q->whereHas('user', function ($q2) use ($search) {
                            $q2->where('name', 'LIKE', '%' . $search . '%')
                                ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                                ->orWhere('slug', 'LIKE', '%' . $search . '%');
                        });
                    }
                })
                ->orderBy('status', 'ASC')
                ->orderBy('created_at', 'DESC')
                ->paginate($entity);

            $group = GroupChat::where('status', 0)->with('group', 'user')
                ->orderBy('status', 'ASC')
                ->orderBy('created_at', 'DESC')
                ->paginate($entity);

            $users = User::with('userRole')->where('deleted', 0)->where(function ($q) {
                $q->where('role', 2)->orWhere('role', 3)->orWhere('role', 1)->orWhere('role', 9)->orWhere('role', 8)->orWhere('role', 14)->orWhere('role', 17)->orWhere('role', 18);
            })
                ->orderBy('freeze', 'DESC')
                ->paginate($entity);

            $flag = User::with('userRole', 'flag')->withCount('flag')->where('deleted', 0)->where(function ($q) {
                $q->where('role', 2)->orWhere('role', 3)->orWhere('role', 1)->orWhere('role', 9)->orWhere('role', 8)->orWhere('role', 14)->orWhere('role', 17)->orWhere('role', 18);
            })
                ->whereHas('flag', function ($q) {
                    $q->where('custom_chat_id', '!=', 0)->orWhere('group_chat_id', '!=', 0);
                })
                ->orderBy('flag_count', 'DESC')
                ->orderBy('updated_at', 'DESC')
                ->paginate($entity);
        } else if ($heading == 'Freeze Users') {
            if ($entry == 'status') {
                if ($search == 'Active') {
                    $status = 0;
                } else if ($search == 'Freeze') {
                    $status = 1;
                } else {
                    $status = '';
                }
            }

            // $chat = CustomChat::with('sender', 'receiver', 'flag')
            //     ->orderBy('status', 'ASC')
            //     ->orderBy('created_at', 'DESC')
            //     ->paginate($entity);

            $chat = chat::where('status', 0)->orderBy('status', 'ASC')
                ->orderBy('created_at', 'DESC')
                ->paginate($entity);

            $public = PublicOrderChat::with('user', 'publicChat', 'flag')
                ->orderBy('status', 'ASC')
                ->orderBy('created_at', 'DESC')
                ->paginate($entity);

            $users = User::with('userRole')->where('deleted', 0)->where(function ($q) {
                $q->where('role', 2)->orWhere('role', 3)->orWhere('role', 1)->orWhere('role', 9)->orWhere('role', 8)->orWhere('role', 14)->orWhere('role', 17)->orWhere('role', 18);
            })
                ->where(function ($q) use ($search, $status, $entry) {
                    if ($entry == 'name') {
                        $q->where('name', 'LIKE', '%' . $search . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                            ->orWhere('slug', 'LIKE', '%' . $search . '%');
                    }
                    if ($entry == 'phone') {
                        $q->where('phone', 'LIKE', '%' . $search . '%');
                    }
                    if ($entry == 'status') {
                        $q->where('status', 'LIKE', '%' . $status . '%');
                    }
                    if ($entry == 'role') {
                        $q->whereHas('userRole', function ($q2) use ($search) {
                            $q2->where('name', 'LIKE', '%' . $search . '%');
                        });
                    }
                })
                ->orderBy('freeze', 'DESC')
                ->paginate($entity);

            $flag = User::with('userRole', 'flag')->withCount('flag')->where('deleted', 0)->where(function ($q) {
                $q->where('role', 2)->orWhere('role', 3)->orWhere('role', 1)->orWhere('role', 9)->orWhere('role', 8)->orWhere('role', 14)->orWhere('role', 17)->orWhere('role', 18);
            })
                ->whereHas('flag', function ($q) {
                    $q->where('custom_chat_id', '!=', 0)->orWhere('group_chat_id', '!=', 0);
                })
                ->orderBy('flag_count', 'DESC')
                ->orderBy('updated_at', 'DESC')
                ->paginate($entity);
        } else if ($heading == 'Group Chat') {
            if ($entry == 'status') {
                if ($search == 'Active') {
                    $status = 0;
                } else if ($search == 'Freeze') {
                    $status = 1;
                } else {
                    $status = '';
                }
            }

            // $chat = CustomChat::with('sender', 'receiver', 'flag')
            //     ->orderBy('status', 'ASC')
            //     ->orderBy('created_at', 'DESC')
            //     ->paginate($entity);

            $chat = chat::where('status', 0)->orderBy('status', 'ASC')
                ->orderBy('created_at', 'DESC')
                ->paginate($entity);

            $public = PublicOrderChat::with('user', 'publicChat', 'flag')
                ->orderBy('status', 'ASC')
                ->orderBy('created_at', 'DESC')
                ->paginate($entity);

            $group = GroupChat::where('status', 0)->with('group', 'user')
                ->orderBy('status', 'ASC')
                ->orderBy('created_at', 'DESC')
                ->paginate($entity);

            $users = User::with('userRole')->where('deleted', 0)->where(function ($q) {
                $q->where('role', 2)->orWhere('role', 3)->orWhere('role', 1)->orWhere('role', 9)->orWhere('role', 8)->orWhere('role', 14)->orWhere('role', 17)->orWhere('role', 18);
            })
                ->where(function ($q) use ($search, $status, $entry) {
                    if ($entry == 'name') {
                        $q->where('name', 'LIKE', '%' . $search . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                            ->orWhere('slug', 'LIKE', '%' . $search . '%');
                    }
                    if ($entry == 'phone') {
                        $q->where('phone', 'LIKE', '%' . $search . '%');
                    }
                    if ($entry == 'status') {
                        $q->where('status', 'LIKE', '%' . $status . '%');
                    }
                    if ($entry == 'role') {
                        $q->whereHas('userRole', function ($q2) use ($search) {
                            $q2->where('name', 'LIKE', '%' . $search . '%');
                        });
                    }
                })
                ->orderBy('freeze', 'DESC')
                ->paginate($entity);

            $flag = User::with('userRole', 'flag')->withCount('flag')->where('deleted', 0)->where(function ($q) {
                $q->where('role', 2)->orWhere('role', 3)->orWhere('role', 1)->orWhere('role', 9)->orWhere('role', 8)->orWhere('role', 14)->orWhere('role', 17)->orWhere('role', 18);
            })
                ->whereHas('flag', function ($q) {
                    $q->where('custom_chat_id', '!=', 0)->orWhere('group_chat_id', '!=', 0);
                })
                ->orderBy('flag_count', 'DESC')
                ->orderBy('updated_at', 'DESC')
                ->paginate($entity);
        }

        if ($heading == 'Flag Users') {
            // $chat = CustomChat::with('sender', 'receiver', 'flag')
            //     ->orderBy('status', 'ASC')
            //     ->orderBy('created_at', 'DESC')
            //     ->paginate($entity);

            $chat = chat::where('status', 0)->orderBy('status', 'ASC')
                ->orderBy('created_at', 'DESC')
                ->paginate($entity);

            $public = PublicOrderChat::with('user', 'publicChat', 'flag')
                ->orderBy('status', 'ASC')
                ->orderBy('created_at', 'DESC')
                ->paginate($entity);


            $group = GroupChat::where('status', 0)->with('group', 'user')
                ->orderBy('status', 'ASC')
                ->orderBy('created_at', 'DESC')
                ->paginate($entity);

            $users = User::with('userRole')->where('deleted', 0)->where(function ($q) {
                $q->where('role', 2)->orWhere('role', 3)->orWhere('role', 1)->orWhere('role', 9)->orWhere('role', 8)->orWhere('role', 14)->orWhere('role', 17)->orWhere('role', 18);
            })
                ->orderBy('freeze', 'DESC')
                ->paginate($entity);

            $flag = User::with('userRole', 'flag')->withCount('flag')->where('deleted', 0)->where(function ($q) {
                $q->where('role', 2)->orWhere('role', 3)->orWhere('role', 1)->orWhere('role', 9)->orWhere('role', 8)->orWhere('role', 14)->orWhere('role', 17)->orWhere('role', 18);
            })
                ->whereHas('flag', function ($q) {
                    $q->where('custom_chat_id', '!=', 0)->orWhere('group_chat_id', '!=', 0);
                })
                ->where(function ($q) use ($search, $entry) {
                    if ($entry == 'name') {
                        $q->where('name', 'LIKE', '%' . $search . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                            ->orWhere('slug', 'LIKE', '%' . $search . '%');
                    }
                    if ($entry == 'phone') {
                        $q->where('phone', 'LIKE', '%' . $search . '%');
                    }
                    if ($entry == 'role') {
                        $q->whereHas('userRole', function ($q2) use ($search) {
                            $q2->where('name', 'LIKE', '%' . $search . '%');
                        });
                    }
                })
                ->orderBy('flag_count', 'DESC')
                ->orderBy('updated_at', 'DESC')
                ->paginate($entity);
        }

        if (isset($public)) {
            $arr = [];
            foreach ($public as $key => $val) {
                $public[$key]->member = User::select('id', 'name', 'last_name', 'slug', 'is_login')->whereIn('id', explode(',', $val->publicChat->members))->where('id', '<>', $id)->count();
                $public[$key]->seen_by = User::select('id', 'name', 'last_name', 'slug', 'is_login')->whereIn('id', explode(',', $val->seen_by_user_id))->where('id', '<>', $id)->count();
            }
        }

        // echo "<pre>";
        // print_r($heading);
        // exit();
        if (Auth::check()) {
            return view('main.register.index-custom-chat2', compact('chat', 'public', 'heading', 'users', 'flag', 'group'));
        } else {
            return redirect('/loginn/');
        }
    }

    public function approveChat(Request $request)
    {
        $data = chat::find($request->id);
        $data->approved_by_user_id = Auth::id();
        $data->status = 1;
        $data->save();

        return back()->with('msg', 'Message Approved successfully!');
    }

    public function approvePublicChat(Request $request)
    {
        $data = PublicOrderChat::find($request->id);
        $data->approved_by_user_id = Auth::id();
        $data->status = 1;
        $data->save();

        return back()->with('msg', 'Message Approved successfully!');
    }

    public function approveGroupChat(Request $request)
    {
        $data = GroupChat::find($request->id);
        $data->approved_by_user_id = Auth::id();
        $data->status = 1;
        $data->save();

        return back()->with('msg', 'Message Approved successfully!');
    }

    public function flagChat(Request $request)
    {
        $data = chat::find($request->id);
        $data->approved_by_user_id = Auth::id();
        $data->save();

        $flag = new Flag;
        $flag->custom_chat_id = $data->id;
        $flag->user_id = $data->fromuserId;
        $flag->flag_by_user_id = Auth::id();
        $flag->status = 1;
        $flag->reason = $request->reason;
        $flag->save();

        return back()->with('error', 'Flag added to the user!');
    }

    public function redFlag(Request $request)
    {
        $flag = new Flag;
        $flag->user_id = $request->id;
        $flag->flag_by_user_id = Auth::id();
        $flag->status = 1;
        $flag->reason = $request->reason;
        $flag->save();

        return back()->with('error', 'Flag added to the user!');
    }

    public function flagPublicChat(Request $request)
    {
        $data = GroupChat::find($request->id);
        $data->approved_by_user_id = Auth::id();
        $data->save();

        $flag = new Flag;
        $flag->group_chat_id = $data->id;
        $flag->user_id = $data->user_id;
        $flag->flag_by_user_id = Auth::id();
        $flag->status = 1;
        $flag->reason = $request->reason;
        $flag->save();

        return back()->with('error', 'Flag added to the user!');
    }

    public function freezeActive(Request $request)
    {
        $data = User::find($request->id);
        $data->freeze = $request->freeze;
        $data->freeze_reason = $request->freeze_reason ?? NULL;
        $data->save();
        if ($request->freeze == 1) {
            $data2 = new FreezeUser;
            $data2->user_id = $data->id;
            $data2->freeze_time = date('Y-m-d h:i:s');
            $data2->save();

            $msg = ($data->slug ?? $data->name . ' ' . $data->last_name) . ' is successfully freeze!';
            return back()->with('error', $msg);
        } else {
            $data2 = FreezeUser::where('user_id', $data->id)->where('status', '=', 0)->first();
            if (empty($data2)) {
                $data2 = new FreezeUser;
                $data2->user_id = $data->id;
                $data2->freeze_time = date('Y-m-d h:i:s');
            }
            $data2->unfreeze_time = date('Y-m-d h:i:s');
            $data2->status = 1;
            $data2->save();

            $msg = ($data->slug ?? $data->name . ' ' . $data->last_name) . ' is successfully active!';
            return back()->with('msg', $msg);
        }
    }

    public function getChatsForApprover()
    {
        $data = CustomChat::where('status', 0)
            ->where('approved_by_user_id', NULL)
            ->select('datetime_for_approver', 'id')
            ->get();

        $data2 = PublicOrderChat::where('status', 0)
            ->where('approved_by_user_id', NULL)
            ->select('datetime_for_approver', 'id')
            ->get();

        if (isset($data)) {
            foreach ($data as $key => $value) {
                $data[$key]->currentTime = Carbon::now()->format('Y-m-d H:i:s');
            }
        }

        if (isset($data2)) {
            foreach ($data2 as $key => $value) {
                $data2[$key]->currentTime = Carbon::now()->format('Y-m-d H:i:s');
            }
        }

        // echo "<pre>";
        // print_r($data->toArray());
        // exit();

        return response()->json([
            'data' => $data,
            'data2' => $data2,
            'status' => true,
            'status_code' => 200
        ], 200);
    }

    public function flagToApprover(Request $request)
    {
        $data = CustomChat::find($request->id);
        $data->datetime_for_approver = Carbon::now()->addMinutes(5);
        $data->save();

        $flag = new Flag;
        $flag->custom_chat_id = $data->id;
        $flag->user_id = Auth::id();
        $flag->flag_by_user_id = 1;
        $flag->status = 1;
        $flag->reason = "This user didn't reply in 5 minutes";
        $flag->save();

        return "Flag to Chat Approver";
    }


    public function flagToPublicApprover(Request $request)
    {
        $data = PublicOrderChat::find($request->id);
        $data->datetime_for_approver = Carbon::now()->addMinutes(5);
        $data->save();

        $flag = new Flag;
        $flag->public_chat_id = $data->id;
        $flag->user_id = Auth::id();
        $flag->flag_by_user_id = 1;
        $flag->status = 1;
        $flag->reason = "This user didn't reply in 5 minutes";
        $flag->save();

        return "Flag to Chat Approver";
    }

    public function removeFlag(Request $request)
    {
        $data = Flag::where('user_id', $request->id)->where('status', 1)->first();
        $data->status = 0;
        $data->save();

        return back()->with('msg', 'Removed flag from the user!');
    }

    public function removeFlagChat(Request $request)
    {
        $flag = Flag::where('custom_chat_id', $request->id)->where('status', 1)->first();
        $flag->status = 0;
        $flag->save();

        return back()->with('msg', 'Removed flag from the user!');
    }

    public function removeFlagChatPublic(Request $request)
    {
        $flag = Flag::where('public_chat_id', $request->id)->where('status', 1)->first();
        $flag->status = 0;
        $flag->save();

        return back()->with('msg', 'Removed flag from the user!');
    }

    public function other_pass()
    {
        return view('main.register.other_pass');
    }

    public function other_pass_update(Request $request)
    {
        $check = ReportPassword::first();
        if (Hash::check($request->old_password, $check->password)) {
            if ($request->password == $request->c_password) {
                $check->password = Hash::make($request->password);
                $check->save();
                return back()->with('success_msg', 'Your password has been updated');
            } else {
                return back()->with('error_msg', 'Your confirmation password is not match with password');
            }
        } else {
            return back()->with('error_msg', 'Your old password is wrong');
        }
        // return view('main.register.other_pass');
    }

    public function flag_employee()
    {
        $order_taker = User::with('flag')->withCount('flag')
            ->where('deleted', 0)
            ->whereHas('userRole', function ($q) {
                $q->where('name', 'Order Taker');
            })
            ->orderBy('flag_count', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();
        $dispatcher = User::with('flag')->withCount('flag')
            ->where('deleted', 0)
            ->whereHas('userRole', function ($q) {
                $q->where('name', 'Dispatcher');
            })
            ->orderBy('flag_count', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();
        $price_checker = User::with('flag')->withCount('flag')
            ->where('deleted', 0)
            ->whereHas('userRole', function ($q) {
                $q->where('name', 'Price Checker');
            })
            ->orderBy('flag_count', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();
        $code_giver = User::with('flag')->withCount('flag')
            ->where('deleted', 0)
            ->whereHas('userRole', function ($q) {
                $q->where('name', 'Code Giver');
            })
            ->orderBy('flag_count', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();
        $chat_approver = User::with('flag')->withCount('flag')
            ->where('deleted', 0)
            ->whereHas('userRole', function ($q) {
                $q->where('name', 'Chat Approver');
            })
            ->orderBy('flag_count', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();
        $manager = User::with('flag')->withCount('flag')
            ->where('deleted', 0)
            ->whereHas('userRole', function ($q) {
                $q->where('name', 'Manager');
            })
            ->orderBy('flag_count', 'DESC')
            ->orderBy('id', 'DESC')->get();

        $qa = User::with('flag')->withCount('flag')
            ->where('deleted', 0)
            ->whereHas('userRole', function ($q) {
                $q->where('name', 'QA');
            })
            ->orderBy('flag_count', 'DESC')
            ->orderBy('id', 'DESC')->get();

        $trust_and_safety = User::with('flag')->withCount('flag')
            ->where('deleted', 0)
            ->whereHas('userRole', function ($q) {
                $q->where('name', 'Trust And Safety');
            })
            ->orderBy('flag_count', 'DESC')
            ->orderBy('id', 'DESC')->get();

        $feedback_and_review = User::with('flag')->withCount('flag')
            ->where('deleted', 0)
            ->whereHas('userRole', function ($q) {
                $q->where('name', 'Feedback And Review');
            })
            ->orderBy('flag_count', 'DESC')
            ->orderBy('id', 'DESC')->get();

        $csr = User::with('flag')->withCount('flag')
            ->where('deleted', 0)
            ->whereHas('userRole', function ($q) {
                $q->where('name', 'CSR');
            })
            ->orderBy('flag_count', 'DESC')
            ->orderBy('id', 'DESC')->get();

        $seller_agent = User::with('flag')->withCount('flag')
            ->where('deleted', 0)
            ->whereHas('userRole', function ($q) {
                $q->where('name', 'Seller Agent');
            })
            ->orderBy('flag_count', 'DESC')
            ->orderBy('id', 'DESC')->get();

        $accountant = User::with('flag')->withCount('flag')
            ->where('deleted', 0)
            ->whereHas('userRole', function ($q) {
                $q->where('name', 'Accountant');
            })
            ->orderBy('flag_count', 'DESC')
            ->orderBy('id', 'DESC')->get();

        $deleted = User::with('flag')->withCount('flag')
            ->where('deleted', 1)
            ->orderBy('flag_count', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        if (Auth::check()) {
            return view('main.register.flag_employee', compact('dispatcher', 'order_taker', 'price_checker', 'code_giver', 'chat_approver', 'deleted', 'accountant', 'seller_agent', 'csr', 'feedback_and_review', 'trust_and_safety', 'qa', 'manager'));
        } else {
            return redirect('/loginn/');
        }
    }

    public function roleAccess(Request $request)
    {
        $role = RoleAccess::where('role_id', $request->role_id)->first();

        if (isset($role->id)) {
            $role->phone = explode(',', $role->phone_access);
            $role->web = explode(',', $role->web_access);
            $role->show = explode(',', $role->show_data_access);
            $role->ship = explode(',', $role->shipment_status_access);
            $role->profile = explode(',', $role->profile_access);
        }

        return response()->json([
            'data' => $role,
            'status' => true,
            'status_code' => 200
        ]);
    }

    public function show_own_order(Request $request)
    {
        $user = User::find($request->id);
        $user->order_taker_quote = $request->order_taker_quote;
        $user->save();

        $name = $user->slug ?? $user->name . ' ' . $user->last_name;
        if ($request->order_taker_quote == 1) {
            $msg = $name . ' will see his/her own qoutes';
        } else {
            $msg = $name . ' will see all qoutes';
        }
        return response()->json([
            'msg' => $msg,
            'status' => true,
            'status_code' => 200
        ]);
    }

    public function reset($id)
    {
        $user = User::find($id);
        $user->is_login = 0;
        $user->is_time = now();
        $user->ss_time = now();
        $user->save();
        return back()->with('msg', 'Now this employee can login their portal!');
    }

    public function view_employee_revenue()
    {
        // $data = User::where('deleted', 0)->where('status', 1)->where('role', 2)->paginate(20);
        // $data = UserCommission::with('user')->whereHas('user', function ($q) {
        //     $q->where('deleted', 0)
        //       ->where('status', 1)
        //       ->where('role', 2);
        //     })->paginate(20);
        $names = User::where('deleted', 0)->where('private_OT', 0)->where('role', 2)->pluck(
            'name',
            'id'
        );
        $data = UserCommission::with('user')
            ->where('average', 0)
            ->whereHas('user', function ($q) {
                $q->where('role', 2)
                    ->where('private_OT', 0);
            })
            ->get();
        $months = UserCommission::with('user')
            ->where('average', 0)
            ->whereHas('user', function ($q) {
                $q->where('role', 2)
                    ->where('private_OT', 0);
            })
            ->select('month')
            ->distinct()
            ->orderBy('month')
            ->pluck('month');
        // dd($months->toArray());
        return view('revenue_order_taker', compact('data', 'names', 'months'));
    }

    public function view_employee_revenue_DeliveryBoy()
    {
        $names = User::where('deleted', 0)->where('private_OT', 0)->where('role', 8)->pluck('name', 'id');
        $data = UserCommission::with('user')
            ->where('average', 0)
            ->whereHas('user', function ($q) {
                $q->where('role', 8)
                    ->where('private_OT', 0);
            })
            ->paginate(20);
        $months = UserCommission::with('user')
            ->where('average', 0)
            ->whereHas('user', function ($q) {
                $q->where('role', 8)
                    ->where('private_OT', 0);
            })
            ->select('month')
            ->distinct()
            ->orderBy('month')
            ->pluck('month');
        return view('revenue_delivery_boy', compact('data', 'names', 'months'));
    }

    public function view_employee_revenue_Dispatcher()
    {
        $names = User::where('deleted', 0)->where('private_OT', 0)->where('role', 3)->pluck('name', 'id');
        $data = UserCommission::with('user')
            ->where('average', 0)
            ->whereHas('user', function ($q) {
                $q->where('role', 3)
                    ->where('private_OT', 0);
            })
            ->paginate(20);
        $months = UserCommission::with('user')
            ->where('average', 0)
            ->whereHas('user', function ($q) {
                $q->where('role', 3)
                    ->where('private_OT', 0);
            })
            ->select('month')
            ->distinct()
            ->orderBy('month')
            ->pluck('month');
        return view('revenue_dispatcher', compact('data', 'names', 'months'));
    }

    public function view_employee_revenue_PrivateOT()
    {
        $names = User::where('deleted', 0)->where('private_OT', 1)->where('role', 2)->pluck('name', 'id');
        $data = UserCommission::with('user')
            ->where('average', '!=', 0)
            ->whereHas('user', function ($q) {
                $q->where('role', 2)
                    ->where('private_OT', 1);
            })
            ->paginate(20);
        $months = UserCommission::with('user')
            ->where('average', '!=', 0)
            ->whereHas('user', function ($q) {
                $q->where('role', 2)
                    ->where('private_OT', 1);
            })
            ->select('month')
            ->distinct()
            ->orderBy('month')
            ->pluck('month');
        return view('revenue_privateOT', compact('data', 'names', 'months'));
    }

    public function getEmployee(Request $request)
    {
        $employee = UserCommission::find($request->id);
        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }
        return response()->json($employee);
    }

    // public function update_employee_revenue(Request $request)
    // {
    //     // dd($request->toArray());
    //     $request->validate([
    //         // 'revenue' => 'required|numeric',
    //         // 'delivered_order' => 'required|integer',
    //         // 'cancellation' => 'required|integer',
    //         // 'total_booked_order' => 'required|integer',
    //     ]);

    //     $emp = User::find($request->user_id);
    //     if ($emp) {
    //         // return response()->json(['message' => 'Employee not found'], 404);
    //         // $emp->revenue = $request->revenue;
    //         // $emp->delivered_order = $request->delivered_order;
    //         // $emp->cancellation = $request->cancellation;
    //         // $emp->total_booked_order = $request->total_booked_order;
    //         // $emp->average_comission = $request->revenue / $request->delivered_order;
    //         // $emp->save();
    //     }

    //     $data = new UserCommission;
    //     $data->user_id = $request->user_id;
    //     $data->revenue = $request->revenue;
    //     $data->delivered_order = $request->delivered_order;
    //     $data->cancellation = $request->cancellation;
    //     $data->total_booked_order = $request->total_booked_order;
    //     $data->month = $request->month;
    //     $data->no_of_rating = isset($request->no_of_rating) ? $request->no_of_rating : 0;
    //     $data->no_of_pickup = isset($request->no_of_pickup) ? $request->no_of_pickup : 0;
    //     $data->review_less_than = isset($request->review_less_than) ? $request->review_less_than : '';
    //     $data->save();

    //     return response()->json(['msg' => 'Employee updated successfully!']);
    // }

    // public function update_employee_revenue(Request $request)
    // {
    //     if (isset($request->revenue_id) && $request->revenue_id != null) {
    //         $existingRecord = UserCommission::where('id', $request->revenue_id)
    //             ->where('month', $request->month)
    //             ->first();
    //         $existingRecord->revenue = $request->has('revenue') ? $request->revenue : 0;
    //         $existingRecord->delivered_order = $request->has('delivered_order') ? $request->delivered_order : 0;
    //         $existingRecord->cancellation = $request->has('cancellation') ? $request->cancellation : 0;
    //         $existingRecord->total_booked_order = $request->has('total_booked_order') ? $request->total_booked_order : 0;
    //         $existingRecord->no_of_rating = $request->has('no_of_rating') ? $request->no_of_rating : 0;
    //         $existingRecord->no_of_pickup = $request->has('no_of_pickup') ? $request->no_of_pickup : 0;
    //         $existingRecord->review_less_than = $request->has('review_less_than') ? $request->review_less_than : '';
    //         $existingRecord->month = $request->has('month') ? $request->month : '';
    //         // dd($request->toArray(), 'okl');

    //         $existingRecord->save();
    //     } else {
    //         $data = new UserCommission;
    //         $data->user_id = $request->has('user_id') ? $request->user_id : 0;
    //         $data->revenue = $request->has('revenue') ? $request->revenue : 0;
    //         $data->delivered_order = $request->has('delivered_order') ? $request->delivered_order : 0;
    //         $data->cancellation = $request->has('cancellation') ? $request->cancellation : 0;
    //         $data->total_booked_order = $request->has('total_booked_order') ? $request->total_booked_order : 0;
    //         $data->no_of_rating = $request->has('no_of_rating') ? $request->no_of_rating : 0;
    //         $data->no_of_pickup = $request->has('no_of_pickup') ? $request->no_of_pickup : 0;
    //         $data->review_less_than = $request->has('review_less_than') ? $request->review_less_than : '';
    //         $data->month = $request->has('month') ? $request->month : '';

    //         $data->save();
    //     }

    //     return response()->json(['msg' => 'Employee data successfully updated!']);
    // }

    public function update_employee_revenue(Request $request)
    {
        // Update existing record if revenue_id is provided
        if (isset($request->revenue_id) && $request->revenue_id != null) {
            $existingRecord = UserCommission::where('id', $request->revenue_id)
                ->where('month', $request->month)
                ->first();

            if ($existingRecord) {
                $existingRecord->revenue = $request->has('revenue') ? $request->revenue : 0;
                $existingRecord->delivered_order = $request->has('delivered_order') ? $request->delivered_order : 0;
                $existingRecord->cancellation = $request->has('cancellation') ? $request->cancellation : 0;
                $existingRecord->total_booked_order = $request->has('total_booked_order') ? $request->total_booked_order : 0;
                $existingRecord->no_of_rating = $request->has('no_of_rating') ? $request->no_of_rating : 0;
                $existingRecord->no_of_pickup = $request->has('no_of_pickup') ? $request->no_of_pickup : 0;
                $existingRecord->total_dispatch = $request->has('total_dispatch') ? $request->total_dispatch : 0;
                $existingRecord->total_pickup = $request->has('total_pickup') ? $request->total_pickup : 0;
                $existingRecord->flat_commision = $request->has('flat_commision') ? $request->flat_commision : 0;
                $existingRecord->achieved_commision = $request->has('achieved_commision') ? $request->achieved_commision : 0;
                $existingRecord->dispatched_by = $request->has('dispatched_by') ? $request->dispatched_by : 0;
                $existingRecord->cancellation_deduction = $request->has('cancellation_deduction') ? $request->cancellation_deduction : 0;
                $existingRecord->review_target_achieved = $request->has('review_target_achieved') ? $request->review_target_achieved : 0;
                $existingRecord->commission = $request->has('commission') ? $request->commission : 0;
                $existingRecord->average = $request->has('average') ? $request->average : 0;
                $existingRecord->review_less_than = $request->has('review_less_than') ? $request->review_less_than : '';
                $existingRecord->month = $request->has('month') ? $request->month : '';

                $existingRecord->save();
            }
        } else {
            // Check if a record for the same user and month already exists
            $existingUserRecord = UserCommission::where('user_id', $request->user_id)
                ->where('month', $request->month)
                ->first();

            if ($existingUserRecord) {
                return response()->json(['msg' => 'A record for this user and month already exists.'], 400);
            }

            // Create a new record if no existing record found
            $data = new UserCommission;
            $data->user_id = $request->has('user_id') ? $request->user_id : 0;
            $data->revenue = $request->has('revenue') ? $request->revenue : 0;
            $data->delivered_order = $request->has('delivered_order') ? $request->delivered_order : 0;
            $data->cancellation = $request->has('cancellation') ? $request->cancellation : 0;
            $data->total_booked_order = $request->has('total_booked_order') ? $request->total_booked_order : 0;
            $data->no_of_rating = $request->has('no_of_rating') ? $request->no_of_rating : 0;
            $data->no_of_pickup = $request->has('no_of_pickup') ? $request->no_of_pickup : 0;
            $data->total_dispatch = $request->has('total_dispatch') ? $request->total_dispatch : 0;
            $data->total_pickup = $request->has('total_pickup') ? $request->total_pickup : 0;
            $data->flat_commision = $request->has('flat_commision') ? $request->flat_commision : 0;
            $data->achieved_commision = $request->has('achieved_commision') ? $request->achieved_commision : 0;
            $data->dispatched_by = $request->has('dispatched_by') ? $request->dispatched_by : 0;
            $data->cancellation_deduction = $request->has('cancellation_deduction') ? $request->cancellation_deduction : 0;
            $data->review_target_achieved = $request->has('review_target_achieved') ? $request->review_target_achieved : 0;
            $data->commission = $request->has('commission') ? $request->commission : 0;
            $data->average = $request->has('average') ? $request->average : 0;
            $data->review_less_than = $request->has('review_less_than') ? $request->review_less_than : '';
            $data->month = $request->has('month') ? $request->month : '';

            $data->save();
        }

        return response()->json(['msg' => 'Employee data successfully updated!']);
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

    public function searchAllNewUser(Request $request)
    {
        $user = Auth::user();
        $setting = general_setting::first();
        $ptype = $this->check_user_setting($user->id);
        $how_did_you_find_us = $request->how_did_you_find_us ?? '';
        $found_on_referral_phone = $request->found_on_referral_phone ?? '';
        $found_on_social_media = $request->found_on_social_media ?? '';
        $found_on_review_platform = $request->found_on_review_platform ?? '';
        $call_type = $request->call_type ?? '';

        $from = '';
        $too = '';
        if (isset($request->date_range) && !empty($request->date_range)) {
            $dates = explode(' - ', $request->date_range);
            $from = date('Y-m-d 00:00:00', strtotime($dates[0]));
            $too = date('Y-m-d 23:59:59', strtotime($dates[1]));
        }

        $order = AutoOrder::select([
            'ophone',
            DB::raw('MIN(id) as id'),
            'oname',
            'destinationcity',
            'destinationzip',
            'ymk',
            'pstatus',
            'paneltype',
            'order_taker_id',
            'created_at',
            'how_did_you_find_us',
            'found_on_referral_phone',
            'found_on_social_media',
            'found_on_review_platform',
            DB::raw('MIN(created_at) as first_record_created_at')
        ])
            ->whereNotNull('how_did_you_find_us')
            ->where('how_did_you_find_us', '!=', '')
            ->groupBy('ophone')
            ->havingRaw('MIN(how_did_you_find_us) != "" AND MIN(how_did_you_find_us) IS NOT NULL')
            ->havingRaw('MAX(how_did_you_find_us) != "" AND MAX(how_did_you_find_us) IS NOT NULL')
            ->with(['filterHistory.filterUser', 'orderTaker'])
            ->where(function ($q) use ($user) {
                switch ($user->userRole->name) {
                    case 'Manager':
                        if ($user->order_taker_quote == 1) {
                            $q->where(function ($subQuery) use ($user) {
                                $subQuery->where('manager_id', $user->id)
                                    ->orWhere('order_taker_id', $user->id);
                            });
                        }
                        break;
                    case 'Dispatcher':
                        if ($user->order_taker_quote == 1) {
                            $q->where('dispatcher_id', $user->id);
                        }
                        break;
                    case 'Delivery Boy':
                        if ($user->order_taker_quote == 1) {
                            $q->where('delivery_boy_id', $user->id);
                        }
                        break;
                    case 'Order Taker':
                    case 'CSR':
                    case 'Seller Agent':
                        if ($user->order_taker_quote == 1) {
                            $q->where('order_taker_id', $user->id);
                        } else if ($user->order_taker_quote == 2) {
                            $q->whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$user->id]);
                        }
                        break;
                }
            })
            ->where(function ($q) use ($how_did_you_find_us, $found_on_referral_phone, $found_on_social_media, $found_on_review_platform) {
                if ($how_did_you_find_us) {
                    $q->where('how_did_you_find_us', $how_did_you_find_us);

                    if ($how_did_you_find_us === 'existing_customer' && $found_on_referral_phone) {
                        $q->where('found_on_referral_phone', 'LIKE', '%' . $found_on_referral_phone . '%');
                    } elseif ($how_did_you_find_us === 'social_media' && $found_on_social_media) {
                        $q->where('found_on_social_media', 'LIKE', '%' . $found_on_social_media . '%');
                    } elseif ($how_did_you_find_us === 'review_platform' && $found_on_review_platform) {
                        $q->where('found_on_review_platform', 'LIKE', '%' . $found_on_review_platform . '%');
                    }
                }
            })
            ->where('paneltype', $ptype)
            ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
            ->when(!empty($call_type), function ($q) use ($call_type) {
                $q->where('call_type', $call_type);
            })
            ->orderBy('id', 'DESC');

        if (!empty($from) && !empty($too)) {
            $order->whereBetween('created_at', [$from, $too]);
        }

        $order = $order->paginate(100);

        if ($request->ajax()) {
            return view('main.filtered.allNewUserSearch', compact('order'));
        }

        return view('main.filtered.allNewUser', compact('order'));
    }


    public function view_query(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $state = '';

            // Initialize query
            $query = ShipaQuery::query();

            $search_as = $request->input('search_as',null);

            if ($search_as == 2) { //search as normal user or himself
                $query->where('user_id', $user->id);
                $userState = \App\User::with('assignedDataNewShipa')->has('assignedDataNewShipa')->find($user->id);
                if ($userState) {
                    $state = $userState->assignedDataNewShipa->states;
                    $record =$userState->assignedDataNewShipa->recordsAllowed;
                }
            }

            // Apply filters
            if ($request->has('state') && $request->state !== null) {
                $query->where('originstate', $request->state);
            }
            if ($request->has('orderTaker') && $request->orderTaker !== null) {
                $query->where('user_id', $request->orderTaker);
            }

            if ($request->has('search') && $request->search !== null) {
                $query->where('oname', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('oemail', 'LIKE', '%' . $request->search . '%');
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
            $data = $query->orderby('id','desc')->paginate(20);

            if ($request->ajax()) {
                return view('main.phone_quote.query.table', compact('data', 'state'));
            }

            return view('main.phone_quote.query.index', compact('data', 'state'));
        }
    }

    public function shipa1_queryPhoneCount_dealer(Request $request)
    {
        try {
            // Get the authenticated user's ID
            $user = Auth::user()->id;

            // Get the approach ID from the request
            $query_id = $request->query_id;

            // You can use dd($user, $approach, $request->toArray()) for debugging purposes

            // Create a new entry in the WhatsappAutoApproachCount model
            ShipaQueryPhone::insert([
                'userId' => $user,
                'query_id' => $query_id,
                'type' => $request->type,
            ]);

            // You can also return a success response or redirect as needed
            return response()->json(['message' => 'count added successfully'], 200);
        } catch (\Exception $e) {
            // Handle any exceptions that may occur during the process
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function shipa1_querygetHistory(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 2) {
            $user_id = $user->id;
        } else {
            $user_id = $request->user_id;
        }

        return ShipaQueryHistories::with('user')->where('company_id', $request->company_id)->get();

    }

    public function shipa1_querygetstoreHistory(Request $request)
    {
        $user = Auth::user();
        $history = new ShipaQueryHistories;
        $history->user_id = $user->id;
        $history->company_id = $request->CompanyID;
        $history->connectStatus = $request->connectStatus;
        $history->comment = $request->comment;
        $history->save();

        ShipaQuery::where('id', $request->CompanyID)->update(['user_id' => 0]);

        return ShipaQueryHistories::with('user')
            ->where('user_id', $user->id)
            ->where('company_id', $request->CompanyID)
            ->get();
    }

    public function shipa1_queryNew(Request $request)
    {

        if (Auth::check()) {
            $user = Auth::user();
            $ptype = $this->check_user_setting(Auth::user()->id);
            $setting = general_setting::first();

            $check = ShipaAssign::where('orderTaker', $request->orderTaker)->first();

            if ($check) {
                $check->state = implode(',', $request->state);
                $check->orderTaker = $request->orderTaker;
                $check->recordsAllowed = $request->recordsAllowed;
                $check->save();
            } else {
                $data = new ShipaAssign;
                $data->state = implode(',', $request->state);
                $data->orderTaker = $request->orderTaker;
                $data->recordsAllowed = $request->recordsAllowed;
                $data->save();
            }

            foreach ($request->state as $state) {
                ShipaQuery::where('user_id', 0)
                    ->where('originstate', $state)
                    ->take($request->recordsAllowed)
                    ->update(['user_id' => $request->orderTaker]);
            }

            return back();
        } else {
            return redirect('/loginn/');
        }
    }

    public function shipa1_query_reporting(Request $request)
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
            if ($user->role == 1 || $user->role == 9 || in_array('148', $phoneaccess)) {
                $ptype = $this->check_user_setting($user->id);
                $setting = general_setting::first();

                $data = ShipaQuery::where('user_id', '!=', 0)->with('user', 'callCount', 'history')
                    ->has('callCount')
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);

                return view('main.phone_quote.query_report.index', compact('data'));
            } else {
                // Redirect if the user doesn't have the required roles
                return redirect('/loginn/');
            }
        } else {
            return redirect('/loginn/');
        }
    }

    public function filterAssignedQuery(Request $request)
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
            if ($request->has('whatsapp')) {
                // dd('ok');
                $data = ShipaQuery::where('user_id', '!=', 0)->with('user', 'whatsappCallCount', 'history')
                    ->has('whatsappCallCount');

                if ($request->has('orderTaker') && $request->orderTaker !== null) {
                    $data->where('user_id', $request->orderTaker);
                }
                if ($request->has('state') && $request->state !== null) {
                    $data->where('originstate', $request->state);
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

                $data = ShipaQuery::with([
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
                    $data->where('originstate', $request->state);
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


    // public function searchAllNewUser(Request $request)
    // {
    //     // dd($request->toArray());
    //     $user = Auth::user();
    //     $setting = general_setting::first();
    //     $ptype = $this->check_user_setting($user->id);
    //     $how_did_you_find_us = $request->how_did_you_find_us ?? '';
    //     $found_on_referral_phone = $request->found_on_referral_phone ?? '';
    //     $found_on_social_media = $request->found_on_social_media ?? '';
    //     $found_on_review_platform = $request->found_on_review_platform ?? '';
    //     $call_type = $request->call_type ?? '';

    //     $from = '';
    //     $too = '';
    //     if (isset($request->date_range) && !empty($request->date_range)) {
    //         $dates = explode(' - ', $request->date_range);
    //         $from = date('Y-m-d 00:00:00', strtotime($dates[0]));
    //         $too = date('Y-m-d 23:59:59', strtotime($dates[1]));
    //     }
    //     $order = AutoOrder::select([
    //         'ophone',
    //         DB::raw('MIN(id) as id'),
    //         'oname',
    //         'destinationcity',
    //         'destinationzip',
    //         'ymk',
    //         'pstatus',
    //         'paneltype',
    //         'order_taker_id',
    //         'created_at',
    //         'how_did_you_find_us',
    //         'found_on_referral_phone',
    //         'found_on_social_media',
    //         'found_on_review_platform',
    //         DB::raw('MIN(created_at) as first_record_created_at')
    //     ])
    //         ->whereNotNull('how_did_you_find_us')
    //         ->where('how_did_you_find_us', '!=', '')
    //         ->groupBy('ophone')
    //         ->havingRaw('MIN(how_did_you_find_us) != "" AND MIN(how_did_you_find_us) IS NOT NULL')
    //         ->havingRaw('MAX(how_did_you_find_us) != "" AND MAX(how_did_you_find_us) IS NOT NULL')
    //         ->with(['filterHistory.filterUser', 'orderTaker'])
    //         ->where(function ($q) use ($user) {
    //             switch ($user->userRole->name) {
    //                 case 'Manager':
    //                     if ($user->order_taker_quote == 1) {
    //                         $q->where(function ($subQuery) use ($user) {
    //                             $subQuery->where('manager_id', $user->id)
    //                                 ->orWhere('order_taker_id', $user->id);
    //                         });
    //                     }
    //                     break;
    //                 case 'Dispatcher':
    //                     if ($user->order_taker_quote == 1) {
    //                         $q->where('dispatcher_id', $user->id);
    //                     }
    //                     break;
    //                 case 'Delivery Boy':
    //                     if ($user->order_taker_quote == 1) {
    //                         $q->where('delivery_boy_id', $user->id);
    //                     }
    //                     break;
    //                 case 'Order Taker':
    //                 case 'CSR':
    //                 case 'Seller Agent':
    //                     if ($user->order_taker_quote == 1) {
    //                         $q->where('order_taker_id', $user->id);
    //                     } else if ($user->order_taker_quote == 2) {
    //                         $q->whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$user->id]);
    //                     }
    //                     break;
    //             }
    //         })
    //         ->where(function ($q) use ($how_did_you_find_us, $found_on_referral_phone, $found_on_social_media, $found_on_review_platform) {
    //             if ($how_did_you_find_us) {
    //                 $q->where('how_did_you_find_us', $how_did_you_find_us);

    //                 if ($how_did_you_find_us === 'existing_customer' && $found_on_referral_phone) {
    //                     $q->where('found_on_referral_phone', 'LIKE', '%' . $found_on_referral_phone . '%');
    //                 } elseif ($how_did_you_find_us === 'social_media' && $found_on_social_media) {
    //                     $q->where('found_on_social_media', 'LIKE', '%' . $found_on_social_media . '%');
    //                 } elseif ($how_did_you_find_us === 'review_platform' && $found_on_review_platform) {
    //                     $q->where('found_on_review_platform', 'LIKE', '%' . $found_on_review_platform . '%');
    //                 }
    //             }
    //         })
    //         ->where('paneltype', $ptype)
    //         ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
    //         ->when(!empty($call_type), function ($q) use ($call_type) {
    //             $q->where('call_type', $call_type);
    //         })
    //         ->orderBy('id', 'DESC');


    //     if (!empty($from) && !empty($too)) {
    //         $order->whereBetween('created_at', [$from, $too]);
    //     }

    //     // $order = AutoOrder::groupBy('ophone')->where('how_did_you_find_us', '!=', null);
    //     $order = $order->paginate(100);
    //     dd($order->toArray());

    //     if ($request->ajax()) {
    //         return view('main.filtered.allNewUserSearch', compact('order'));
    //     }

    //     return view('main.filtered.allNewUser', compact('order'));
    // }

//    public function submit_query(Request $request){
//        // Validate the request
//        $validatedData = $request->validate([
//            'name'    => 'required|string|max:75',
//            'email'   => 'required|email|max:50',
//            'phone'   => 'required|string|max:50',
//            'message' => 'nullable|string|max:250',
//            'address' => 'nullable|string',
//        ]);
//
//        // Create a new record
//        $query = ShipaQuery::create($validatedData);
//
//        // Return response
//        return response()->json([
//            'success' => true,
//            'message' => 'Query submitted successfully',
//            'data'    => $query
//        ], 200);
//    }

    /**
     * Central Gateway portal — Washington slabs
     * Permission: 155
     */
    public function gatewayPortalHello()
    {
        $url = config('gateway.portal_url_hello');
        return view('main.gateway.portal', [
            'title'      => 'Hello Gateway Portal',
            'portalUrl'  => $url,
            'portalType' => 'hello',
        ]);
    }

    /**
     * Central Gateway portal — AutoHaul slabs
     * Permission: 156
     */
    public function gatewayPortalAutohaul()
    {
        $url = config('gateway.portal_url_autohaul');
        return view('main.gateway.portal', [
            'title'      => 'AutoHaul Gateway Portal',
            'portalUrl'  => $url,
            'portalType' => 'autohaul',
        ]);
    }
}
