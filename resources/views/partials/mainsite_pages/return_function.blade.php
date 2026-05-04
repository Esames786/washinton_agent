<?php
date_default_timezone_set('America/New_York');
//  $setting = 	App\general_setting::first();
///  ->where('created_at','>=',\Carbon\Carbon::today()->subDays($setting->no_days))
///  25 feb 2020 > show
///
///

//function client_number($val, $id)
//{
//    return optional(\App\OrderTakerClientAccess::where('ot_id', $id)->where('client_number', $val)->first())->client_number ?? '';
//}
//
//
//function check_old_or_new($order_id, $ophone)
//{
//    $setting = App\general_setting::first();
//    $count = \App\AutoOrder::where('id', '!=', $order_id)
//        ->where('created_at', '>=', now()->subDays($setting->no_days))
//        ->where('ophone', $ophone)
//        ->count();
//
//    return $count >= 1
//        ? "<span class='badge badge-warning'>Old</span>"
//        : "<span class='badge badge-success'>New</span>";
//}
//
//
//function putX($digits, $status, $num)
//{
//    $val = $num;
//    if ($status == 0) {
//        if ($digits == 0) {
//            $val = $num;
//        } elseif ($digits == 1) {
//            $val = '(x' . substr($num, -12);
//        } elseif ($digits == 2) {
//            $val = '(xx' . substr($num, -11);
//        } elseif ($digits == 3) {
//            $val = '(xxx) ' . substr($num, -8);
//        } elseif ($digits == 4) {
//            $val = '(xxx) x' . substr($num, -7);
//        } elseif ($digits == 5) {
//            $val = '(xxx) xx' . substr($num, -6);
//        } elseif ($digits == 6) {
//            $val = '(xxx) xxx-' . substr($num, -4);
//        } elseif ($digits == 7) {
//            $val = '(xxx) xxx-x' . substr($num, -3);
//        } elseif ($digits == 8) {
//            $val = '(xxx) xxx-xx' . substr($num, -2);
//        } elseif ($digits == 9) {
//            $val = '(xxx) xxx-xxx' . substr($num, -1);
//        } elseif ($digits == 10) {
//            $val = '(xxx) xxx-xxxx';
//        }
//    } elseif ($status == 1) {
//        if ($digits == 0) {
//            $val = $num;
//        } elseif ($digits == 1) {
//            $val = substr($num, 0, 13) . 'x';
//        } elseif ($digits == 2) {
//            $val = substr($num, 0, 12) . 'xx';
//        } elseif ($digits == 3) {
//            $val = substr($num, 0, 11) . 'xxx ';
//        } elseif ($digits == 4) {
//            $val = substr($num, 0, 10) . 'xxxx';
//        } elseif ($digits == 5) {
//            $val = substr($num, 0, 8) . 'x-xxxx';
//        } elseif ($digits == 6) {
//            $val = substr($num, 0, 7) . 'xx-xxxx';
//        } elseif ($digits == 7) {
//            $val = substr($num, 0, 6) . 'xxx-xxxx';
//        } elseif ($digits == 8) {
//            $val = substr($num, 0, 3) . 'x) xxx-xxxx';
//        } elseif ($digits == 9) {
//            $val = substr($num, 0, 2) . 'xx) xxx-xxxx';
//        } elseif ($digits == 10) {
//            $val = '(xxx) xxx-xxxx';
//        }
//    } elseif ($status == 2) {
//        if ($digits == 0) {
//            $val = $num;
//        } elseif ($digits == 1) {
//            $val = substr($num, 0, 7) . 'x' . substr($num, -6);
//        } elseif ($digits == 2) {
//            $val = substr($num, 0, 7) . 'xx' . substr($num, -5);
//        } elseif ($digits == 3) {
//            $val = substr($num, 0, 6) . 'xxx' . substr($num, -5);
//        } elseif ($digits == 4) {
//            $val = substr($num, 0, 3) . 'x) xxx' . substr($num, -5);
//        } elseif ($digits == 5) {
//            $val = substr($num, 0, 3) . 'x) xxx-x' . substr($num, -3);
//        } elseif ($digits == 6) {
//            $val = substr($num, 0, 3) . 'x) xxx-xx' . substr($num, -2);
//        } elseif ($digits == 7) {
//            $val = substr($num, 0, 2) . 'xx) xxx-xx' . substr($num, -2);
//        } elseif ($digits == 8) {
//            $val = substr($num, 0, 2) . 'xx) xxx-xxx' . substr($num, -1);
//        } elseif ($digits == 9) {
//            $val = '(xxx) xxx-xxx' . substr($num, -1);
//        } elseif ($digits == 10) {
//            $val = '(xxx) xxx-xxxx';
//        }
//    }
//    return $val;
//}
//
//function get_role($id, $column)
//{
//    return optional(\App\Role::where('id', $id)->first())->$column ?? 'No Role';
//}
//
//
//function pay_status($id)
//{
//    if ($id == 0) {
//        return '<span class="badge badge-warning">Pending</span>';
//    } elseif ($id == 1) {
//        return '<span class="badge badge-info">Updated</span>';
//    } elseif ($id == 2) {
//        return '<span class="badge badge-success">Received</span>';
//    }
//}
//
//function get_user($id, $column)
//{
//    return optional(\App\User::where('id', $id)->first())->$column ?? 'No Role';
//}
//
//
//function get_user_name($id)
//{
//    $query = \App\User::find($id);
//
//    if (!$query) {
//        return '';
//    }
//
//    return $query->slug ?: "{$query->name} {$query->last_name}";
//}
//
//
//function get_user_name_old_ship($id)
//{
//    $query = \DB::connection('mysql2')->table('user')->select('*')->where('id', $id)->first();
//    if (!empty($query)) {
//        return $query->name;
//    } else {
//        return '';
//    }
//}
//
//function get_autoorder($id, $column)
//{
//    $setting = App\general_setting::first();
//    $user = auth()->user();
//
//    if (!$user) {
//        return 'NOT FOUND';
//    }
//
//    $query = \App\AutoOrder::where('id', $id)
//        ->where('created_at', '>=', now()->subDays($setting->no_days))
//        ->where(function ($q) use ($user) {
//            if ($user->userRole->name === 'Manager' && $user->order_taker_quote == 1) {
//                $q->where('manager_id', $user->id)->orWhere('order_taker_id', $user->id);
//            } elseif ($user->userRole->name === 'Dispatcher' && $user->order_taker_quote == 1) {
//                $q->where('dispatcher_id', $user->id);
//            } elseif ($user->userRole->name === 'Delivery Boy' && $user->order_taker_quote == 1) {
//                $q->where('delivery_boy_id', $user->id);
//            } elseif (in_array($user->userRole->name, ['Order Taker', 'CSR', 'Seller Agent'])) {
//                if ($user->order_taker_quote == 1) {
//                    $q->where('order_taker_id', $user->id);
//                } elseif ($user->order_taker_quote == 2) {
//                    $q->whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$user->id]);
//                }
//            }
//        })
//        ->first();
//
//    return optional($query)->$column ?? 'NOT FOUND';
//}


if (! function_exists('client_number')) {
    function client_number($val, $id)
    {
        return optional(\App\OrderTakerClientAccess::where('ot_id', $id)->where('client_number', $val)->first())->client_number ?? '';
    }
}

if (! function_exists('check_old_or_new')) {
    function check_old_or_new($order_id, $ophone)
    {
        $setting = App\general_setting::first();
        $count = \App\AutoOrder::where('id', '!=', $order_id)
            ->where('created_at', '>=', now()->subDays($setting->no_days))
            ->where('ophone', $ophone)
            ->count();

        return $count >= 1
            ? "<span class='badge badge-warning'>Old</span>"
            : "<span class='badge badge-success'>New</span>";
    }
}

if (! function_exists('putX')) {
    function putX($digits, $status, $num)
    {
        $val = $num;
        if ($status == 0) {
            if ($digits == 0) {
                $val = $num;
            } elseif ($digits == 1) {
                $val = '(x' . substr($num, -12);
            } elseif ($digits == 2) {
                $val = '(xx' . substr($num, -11);
            } elseif ($digits == 3) {
                $val = '(xxx) ' . substr($num, -8);
            } elseif ($digits == 4) {
                $val = '(xxx) x' . substr($num, -7);
            } elseif ($digits == 5) {
                $val = '(xxx) xx' . substr($num, -6);
            } elseif ($digits == 6) {
                $val = '(xxx) xxx-' . substr($num, -4);
            } elseif ($digits == 7) {
                $val = '(xxx) xxx-x' . substr($num, -3);
            } elseif ($digits == 8) {
                $val = '(xxx) xxx-xx' . substr($num, -2);
            } elseif ($digits == 9) {
                $val = '(xxx) xxx-xxx' . substr($num, -1);
            } elseif ($digits == 10) {
                $val = '(xxx) xxx-xxxx';
            }
        } elseif ($status == 1) {
            if ($digits == 0) {
                $val = $num;
            } elseif ($digits == 1) {
                $val = substr($num, 0, 13) . 'x';
            } elseif ($digits == 2) {
                $val = substr($num, 0, 12) . 'xx';
            } elseif ($digits == 3) {
                $val = substr($num, 0, 11) . 'xxx ';
            } elseif ($digits == 4) {
                $val = substr($num, 0, 10) . 'xxxx';
            } elseif ($digits == 5) {
                $val = substr($num, 0, 8) . 'x-xxxx';
            } elseif ($digits == 6) {
                $val = substr($num, 0, 7) . 'xx-xxxx';
            } elseif ($digits == 7) {
                $val = substr($num, 0, 6) . 'xxx-xxxx';
            } elseif ($digits == 8) {
                $val = substr($num, 0, 3) . 'x) xxx-xxxx';
            } elseif ($digits == 9) {
                $val = substr($num, 0, 2) . 'xx) xxx-xxxx';
            } elseif ($digits == 10) {
                $val = '(xxx) xxx-xxxx';
            }
        } elseif ($status == 2) {
            if ($digits == 0) {
                $val = $num;
            } elseif ($digits == 1) {
                $val = substr($num, 0, 7) . 'x' . substr($num, -6);
            } elseif ($digits == 2) {
                $val = substr($num, 0, 7) . 'xx' . substr($num, -5);
            } elseif ($digits == 3) {
                $val = substr($num, 0, 6) . 'xxx' . substr($num, -5);
            } elseif ($digits == 4) {
                $val = substr($num, 0, 3) . 'x) xxx' . substr($num, -5);
            } elseif ($digits == 5) {
                $val = substr($num, 0, 3) . 'x) xxx-x' . substr($num, -3);
            } elseif ($digits == 6) {
                $val = substr($num, 0, 3) . 'x) xxx-xx' . substr($num, -2);
            } elseif ($digits == 7) {
                $val = substr($num, 0, 2) . 'xx) xxx-xx' . substr($num, -2);
            } elseif ($digits == 8) {
                $val = substr($num, 0, 2) . 'xx) xxx-xxx' . substr($num, -1);
            } elseif ($digits == 9) {
                $val = '(xxx) xxx-xxx' . substr($num, -1);
            } elseif ($digits == 10) {
                $val = '(xxx) xxx-xxxx';
            }
        }
        return $val;
    }
}

if (! function_exists('get_role')) {
    function get_role($id, $column)
    {
        return optional(\App\Role::where('id', $id)->first())->$column ?? 'No Role';
    }
}

if (! function_exists('pay_status')) {
    function pay_status($id)
    {
        if ($id == 0) {
            return '<span class="badge badge-warning">Pending</span>';
        } elseif ($id == 1) {
            return '<span class="badge badge-info">Updated</span>';
        } elseif ($id == 2) {
            return '<span class="badge badge-success">Received</span>';
        }
    }
}

if (! function_exists('get_user')) {
    function get_user($id, $column)
    {
        return optional(\App\User::where('id', $id)->first())->$column ?? 'No Role';
    }
}

if (! function_exists('get_user_name')) {
    function get_user_name($id)
    {
        $query = \App\User::find($id);

        if (!$query) {
            return '';
        }

        return $query->slug ?: "{$query->name} {$query->last_name}";
    }
}

if (! function_exists('get_user_name_old_ship')) {
    function get_user_name_old_ship($id)
    {
        $query = \DB::connection('mysql2')->table('user')->select('*')->where('id', $id)->first();
        return !empty($query) ? $query->name : '';
    }
}

if (! function_exists('get_autoorder')) {
    function get_autoorder($id, $column)
    {
        $setting = App\general_setting::first();
        $user = auth()->user();

        if (!$user) {
            return 'NOT FOUND';
        }

        $query = \App\AutoOrder::where('id', $id)
            ->where('created_at', '>=', now()->subDays($setting->no_days))
            ->where(function ($q) use ($user) {
                if ($user->userRole->name === 'Manager' && $user->order_taker_quote == 1) {
                    $q->where('manager_id', $user->id)->orWhere('order_taker_id', $user->id);
                } elseif ($user->userRole->name === 'Dispatcher' && $user->order_taker_quote == 1) {
                    $q->where('dispatcher_id', $user->id);
                } elseif ($user->userRole->name === 'Delivery Boy' && $user->order_taker_quote == 1) {
                    $q->where('delivery_boy_id', $user->id);
                } elseif (in_array($user->userRole->name, ['Order Taker', 'CSR', 'Seller Agent'])) {
                    if ($user->order_taker_quote == 1) {
                        $q->where('order_taker_id', $user->id);
                    } elseif ($user->order_taker_quote == 2) {
                        $q->whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$user->id]);
                    }
                }
            })
            ->first();

        return optional($query)->$column ?? 'NOT FOUND';
    }
}



if (!function_exists('count_history')) {
    function count_history($user_id, $order_id)
    {
        $setting = App\general_setting::first();
        $query = \App\call_history::where('orderId', $order_id)
            ->where('userId', $user_id)
            ->where('created_at', '>=', \Carbon\Carbon::today()->subDays($setting->no_days))
            ->count();
        return $query;
    }
}

if (!function_exists('check_panel')) {
    function check_panel()
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

if (!function_exists('check_call')) {
    function check_call()
    {
        $setting = App\general_setting::first();
        $calltype = 134;
        $query = \App\user_setting::where('user_id', Auth::user()->id)->first();
        if (!empty($query)) {
            $calltype = $query['call_type'];
        }
        return $calltype;
    }
}

if (!function_exists('get_panel_name')) {
    function get_panel_name()
    {
        $setting = App\general_setting::first();
        $ptype = 1;
        $penaltypename = '';
        $query = \App\user_setting::where('user_id', Auth::user()->id)->first();
        if (!empty($query)) {
            $ptype = $query['penal_type'];
        }
        if ($ptype == '1') {
            $penaltypename = 'Auction';
        } elseif ($ptype == '2') {
            $penaltypename = 'ProMax';
        } elseif ($ptype == '3') {
            $penaltypename = 'Testing Quote';
        } elseif ($ptype == '4') {
            $penaltypename = 'Shipa1 Website';
        } elseif ($ptype == '5') {
            $penaltypename = 'Panel Type 5 Quote';
        } elseif ($ptype == '6') {
            $penaltypename = 'Panel Type 6 Quote';
        } else {
            $penaltypename = 'Unknown Quote Type';
        }

        return $penaltypename;
    }
}

if (!function_exists('get_call_name')) {
    function get_call_name()
    {
        $setting = App\general_setting::first();
        $calltype = 135;
        $calltypename = '';
        $query = \App\user_setting::where('user_id', Auth::user()->id)->first();
        if (!empty($query)) {
            $calltype = $query['call_type'];
        }
        if ($calltype == '134') {
            $calltypename = 'Call App';
        } elseif ($calltype == '135') {
            $calltypename = 'Call Old Web';
        }  elseif ($calltype == '158') {
            $calltypename = 'Zoom Call';
        }

        return $calltypename;
    }
}

if (!function_exists('get_total_new')) {
    function get_total_new($id, $ptype)
    {
        $setting = App\general_setting::first();
        // echo "<pre>";
        // print_r($ptype);exit();
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($id == 11) {
            $data_new = \App\AutoOrder::where('pstatus', '=', $id)
                ->where('approve_pickup', '=', 1)
                ->where('paneltype', '=', $ptype)
                ->where('created_at', '>=', \Carbon\Carbon::today()->subDays($setting->no_days))
                ->where(function ($q) use ($user) {
                    if ($user->userRole->name == 'Manager') {
                        if ($user->order_taker_quote == 1) {
                            $q->where('manager_id', $user->id)->orWhere('order_taker_id', $user->id);
                        }
                    } elseif ($user->userRole->name == 'Dispatcher') {
                        if ($user->order_taker_quote == 1) {
                            $q->where('dispatcher_id', $user->id);
                        }
                    } elseif ($user->userRole->name == 'Delivery Boy') {
                        if ($user->order_taker_quote == 1) {
                            $q->where('delivery_boy_id', $user->id);
                        }
                    } elseif ($user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent') {
                        if ($user->order_taker_quote == 1) {
                            $q->where('order_taker_id', $user->id);
                        } elseif ($user->order_taker_quote == 2) {
                            $q->whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$user->id]);
                        }
                    }
                })
                ->orderBy('id', 'DESC')
                ->count();
        } elseif ($id == 12) {
            $data_new = \App\AutoOrder::where('pstatus', '=', $id)
                ->where('approve_deliver', '=', 1)
                ->where('paneltype', '=', $ptype)
                ->where('created_at', '>=', \Carbon\Carbon::today()->subDays($setting->no_days))
                ->where(function ($q) use ($user) {
                    if ($user->userRole->name == 'Manager') {
                        if ($user->order_taker_quote == 1) {
                            $q->where('manager_id', $user->id)->orWhere('order_taker_id', $user->id);
                        }
                    } elseif ($user->userRole->name == 'Dispatcher') {
                        if ($user->order_taker_quote == 1) {
                            $q->where('dispatcher_id', $user->id);
                        }
                    } elseif ($user->userRole->name == 'Delivery Boy') {
                        if ($user->order_taker_quote == 1) {
                            $q->where('delivery_boy_id', $user->id);
                        }
                    } elseif ($user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent') {
                        if ($user->order_taker_quote == 1) {
                            $q->where('order_taker_id', $user->id);
                        } elseif ($user->order_taker_quote == 2) {
                            $q->whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$user->id]);
                        }
                    }
                })
                ->orderBy('id', 'DESC')
                ->count();
        } elseif ($id == 16) {
            $data_new = \App\AutoOrder::where(function ($q) {
                $q->where(function ($q2) {
                    $q2->where('pstatus', '>=', 7)->where('pstatus', '<=', 14);
                })->orWhere('pstatus', 18);
            })
                ->where(function ($q) use ($user) {
                    if ($user->userRole->name == 'Manager') {
                        if ($user->order_taker_quote == 1) {
                            $q->where('manager_id', $user->id)->orWhere('order_taker_id', $user->id);
                        }
                    } elseif ($user->userRole->name == 'Dispatcher') {
                        if ($user->order_taker_quote == 1) {
                            $q->where('dispatcher_id', $user->id);
                        }
                    } elseif ($user->userRole->name == 'Delivery Boy') {
                        if ($user->order_taker_quote == 1) {
                            $q->where('delivery_boy_id', $user->id);
                        }
                    } elseif ($user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent') {
                        if ($user->order_taker_quote == 1) {
                            $q->where('order_taker_id', $user->id);
                        } elseif ($user->order_taker_quote == 2) {
                            $q->whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$user->id]);
                        }
                    }
                })
                ->where('paneltype', '=', $ptype)
                ->whereBetween('created_at', [\Carbon\Carbon::now()->firstOfMonth()->format('Y-m-d 00:00:00'), \Carbon\Carbon::now()->format('Y-m-d 23:59:59')])
                // ->where('created_at', '>=', \Carbon\Carbon::today()->subDays($setting->no_days))
                ->orderBy('id', 'DESC')
                ->count();
        } elseif ($id == 34) {
            $data_new = \App\AutoOrder::where(function ($q) use ($user) {
                if ($user->userRole->name == 'Manager') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('manager_id', $user->id)->orWhere('order_taker_id', $user->id);
                    }
                } elseif ($user->userRole->name == 'Dispatcher') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('dispatcher_id', $user->id);
                    }
                } elseif ($user->userRole->name == 'Delivery Boy') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('delivery_boy_id', $user->id);
                    }
                } elseif ($user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('order_taker_id', $user->id);
                    } elseif ($user->order_taker_quote == 2) {
                        $q->whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$user->id]);
                    }
                }
            })
                ->where('created_at', '>=', \Carbon\Carbon::today()->subDays($setting->no_days))
                ->where('paneltype', '=', $ptype)
                ->count();
        } elseif ($id == 33) {
            $data_new = \App\MilePrice::count();
        } elseif ($id == 35) {
            $data_new = \App\Group::count();
        } elseif ($id == 36) {
            $data_new = \App\Question::count();
        } elseif ($id == 37) {
            $data_new = \App\AutoOrder::where(function ($q) use ($user) {
                if ($user->userRole->name == 'Manager') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('manager_id', $user->id)->orWhere('order_taker_id', $user->id);
                    }
                } elseif ($user->userRole->name == 'Dispatcher') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('dispatcher_id', $user->id);
                    }
                } elseif ($user->userRole->name == 'Delivery Boy') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('delivery_boy_id', $user->id);
                    }
                } elseif ($user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('order_taker_id', $user->id);
                    } elseif ($user->order_taker_quote == 2) {
                        $q->whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$user->id]);
                    }
                }
            })
                ->where('created_at', '>=', \Carbon\Carbon::today()->subDays($setting->no_days))
                ->where('paneltype', '=', $ptype)
                ->count();
        } elseif ($id == 17) {
            $data_new = \App\carrier::count();
        } else {
            $data_new = \App\AutoOrder::where('pstatus', '=', $id)
                ->where('paneltype', '=', $ptype)
                ->where('created_at', '>=', \Carbon\Carbon::today()->subDays($setting->no_days))
                ->where(function ($q) use ($user) {
                    if ($user->userRole->name == 'Manager') {
                        if ($user->order_taker_quote == 1) {
                            $q->where('manager_id', $user->id)->orWhere('order_taker_id', $user->id);
                        }
                    } elseif ($user->userRole->name == 'Dispatcher') {
                        if ($user->order_taker_quote == 1) {
                            $q->where('dispatcher_id', $user->id);
                        }
                    } elseif ($user->userRole->name == 'Delivery Boy') {
                        if ($user->order_taker_quote == 1) {
                            $q->where('delivery_boy_id', $user->id);
                        }
                    } elseif ($user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent') {
                        if ($user->order_taker_quote == 1) {
                            $q->where('order_taker_id', $user->id);
                        } elseif ($user->order_taker_quote == 2) {
                            $q->whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$user->id]);
                        }
                    }
                })
                ->orderBy('id', 'DESC')
                ->count();
        }

        if (!empty($data_new)) {
            return $data_new;
        } else {
            return 0;
        }
    }
}
if (!function_exists('get_total_pickup_approval')) {
    function get_total_pickup_approval($id, $ptype)
    {
        $setting = App\general_setting::first();
        // echo "<pre>";
        // print_r($ptype);exit();
        $user = \Illuminate\Support\Facades\Auth::user();

        $data_new = \App\AutoOrder::where('pstatus', '=', $id)
            ->where('paneltype', '=', $ptype)
            ->where(function ($q) {
                $q->where('approve_pickup', '=', 0)->orWhere('approve_pickup', '=', null);
            })
            ->where('created_at', '>=', \Carbon\Carbon::today()->subDays($setting->no_days))
            ->where(function ($q) use ($user) {
                if ($user->userRole->name == 'Manager') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('manager_id', $user->id)->orWhere('order_taker_id', $user->id);
                    }
                } elseif ($user->userRole->name == 'Dispatcher') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('dispatcher_id', $user->id);
                    }
                } elseif ($user->userRole->name == 'Delivery Boy') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('delivery_boy_id', $user->id);
                    }
                } elseif ($user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('order_taker_id', $user->id);
                    } elseif ($user->order_taker_quote == 2) {
                        $q->whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$user->id]);
                    }
                }
            })
            ->orderBy('id', 'DESC')
            ->count();
        if (!empty($data_new)) {
            return $data_new;
        } else {
            return 0;
        }
    }
}
if (!function_exists('get_total_deliver_approval')) {
    function get_total_deliver_approval($id, $ptype)
    {
        $setting = App\general_setting::first();
        // echo "<pre>";
        // print_r($ptype);exit();
        $user = \Illuminate\Support\Facades\Auth::user();

        $data_new = \App\AutoOrder::where('pstatus', '=', $id)
            ->where('paneltype', '=', $ptype)
            ->where(function ($q) {
                $q->where('approve_deliver', '=', 0)->orWhere('approve_deliver', '=', null);
            })
            ->where('created_at', '>=', \Carbon\Carbon::today()->subDays($setting->no_days))
            ->where(function ($q) use ($user) {
                if ($user->userRole->name == 'Manager') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('manager_id', $user->id)->orWhere('order_taker_id', $user->id);
                    }
                } elseif ($user->userRole->name == 'Dispatcher') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('dispatcher_id', $user->id);
                    }
                } elseif ($user->userRole->name == 'Delivery Boy') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('delivery_boy_id', $user->id);
                    }
                } elseif ($user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('order_taker_id', $user->id);
                    } elseif ($user->order_taker_quote == 2) {
                        $q->whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$user->id]);
                    }
                }
            })
            ->orderBy('id', 'DESC')
            ->count();
        if (!empty($data_new)) {
            return $data_new;
        } else {
            return 0;
        }
    }
}
if (!function_exists('get_total_deliver_schedule')) {
    function get_total_deliver_schedule($id, $ptype)
    {
        $setting = App\general_setting::first();
        $uid = '';
        // echo "<pre>";
        // print_r($ptype);exit();
        $user = \Illuminate\Support\Facades\Auth::user();
        $data_new = \App\AutoOrder::where('pstatus', '=', $id)
            ->where('paneltype', '=', $ptype)
            ->where('approve_deliver', '=', 2)
            ->where('created_at', '>=', \Carbon\Carbon::today()->subDays($setting->no_days))
            ->where(function ($q) use ($user) {
                if ($user->userRole->name == 'Manager') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('manager_id', $user->id)->orWhere('order_taker_id', $user->id);
                    }
                } elseif ($user->userRole->name == 'Dispatcher') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('dispatcher_id', $user->id);
                    }
                } elseif ($user->userRole->name == 'Delivery Boy') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('delivery_boy_id', $user->id);
                    }
                } elseif ($user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('order_taker_id', $user->id);
                    } elseif ($user->order_taker_quote == 2) {
                        $q->whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$user->id]);
                    }
                }
            })
            ->orderBy('id', 'DESC')
            ->count();
        if (!empty($data_new)) {
            return $data_new;
        } else {
            return 0;
        }
    }
}
if (!function_exists('get_pstatus')) {
    function get_pstatus($id)
    {
        $ret = '';
        if ($id == 0) {
            $ret = 'NEW';
        } elseif ($id == 1) {
            $ret = 'Interested';
        } elseif ($id == 2) {
            $ret = 'FollowMore';
        } elseif ($id == 3) {
            $ret = 'AskingLow';
        } elseif ($id == 4) {
            $ret = 'NotInterested';
        } elseif ($id == 5) {
            $ret = 'NoResponse';
        } elseif ($id == 6) {
            $ret = 'TimeQuote';
        } elseif ($id == 7) {
            $ret = 'PaymentMissing';
        } elseif ($id == 8) {
            $ret = 'Booked';
        } elseif ($id == 9) {
            $ret = 'Listed';
        } elseif ($id == 10) {
            $ret = 'Schedule';
        } elseif ($id == 11) {
            $ret = 'Pickup';
        } elseif ($id == 12) {
            $ret = 'Delivered';
        } elseif ($id == 13) {
            $ret = 'Completed';
        } elseif ($id == 14) {
            $ret = 'Cancel';
        } elseif ($id == 15) {
            $ret = 'Deleted';
        } elseif ($id == 16) {
            $ret = 'OwesMoney';
        } elseif ($id == 17) {
            $ret = 'CarrierUpdate';
        } elseif ($id == 18) {
            $ret = 'OnApproval';
        } elseif ($id == 19) {
            $ret = 'On Approval Canceled';
        } elseif ($id == 99) {
            $ret = 'Approaching';
        }

        return $ret;
    }
}
if (!function_exists('get_pstatus2')) {
    function get_pstatus2($id)
    {
        $ret = '';
        if ($id == 0) {
            $ret = "<span class='badge badge-orange txt-white'>New</span>";
        } elseif ($id == 1) {
            $ret = "<span class='badge badge-warning txt-white'>Interested</span>";
        } elseif ($id == 2) {
            $ret = "<span class='badge badge-primary txt-white'>FollowMore</span>";
        } elseif ($id == 3) {
            $ret = "<span class='badge badge-pink txt-white'>AskingLow</span>";
        } elseif ($id == 4) {
            $ret = "<span class='badge badge-success '>Not Interested</span>";
        } elseif ($id == 5) {
            $ret = "<span class='badge badge-dark txt-white'>No Response</span>";
        } elseif ($id == 6) {
            $ret = "<span class='badge badge-amber txt-white'>Time Quote</span>";
        } elseif ($id == 7) {
            $ret = "<span class='badge badge-primary  txt-white'>Payment Missing</span>";
        } elseif ($id == 8) {
            $ret = "<span class='badge badge-warning  txt-white'>Booked</span>";
        } elseif ($id == 9) {
            $ret = "<span class='badge badge-pink txt-white'>Listed</span>";
        } elseif ($id == 10) {
            $ret = "<span class='badge badge-success'>Schedule</span>";
        } elseif ($id == 11) {
            $ret = "<span class='badge badge-dark txt-white'>Pickup</span>";
        } elseif ($id == 12) {
            $ret = "<span class='badge badge-amber txt-white'>Delivered</span>";
        } elseif ($id == 13) {
            $ret = "<span class='badge badge-teal txt-white'>Completed</span>";
        } elseif ($id == 14) {
            $ret = "<span class='badge badge-danger txt-white'>Cancel</span>";
        } elseif ($id == 15) {
            $ret = "<span class='badge badge-danger txt-white'>Deleted</span>";
        } elseif ($id == 16) {
            $ret = "<span class='badge badge-primary txt-white'>OwesMoney</span>";
        } elseif ($id == 17) {
            $ret = "<span class='badge badge-primary txt-white'>Carrier Update</span>";
        } elseif ($id == 18) {
            $ret = "<span class='badge badge-primary txt-white'>On Approval</span>";
        } elseif ($id == 19) {
            $ret = "<span class='badge badge-danger get_car_or_heavy txt-white'>On Approval Cancelled</span>";
        } elseif ($id == 99) {
            $ret = "<span class='badge badge-secondary'>Approaching</span>";
        }
        return $ret;
    }
}
if (!function_exists('get_oterminal_name')) {
    function get_oterminal_name($id)
    {
        $terminal = '';
        if ($id == 1) {
            $terminal = 'Residence';
        } elseif ($id == 2) {
            $terminal = 'COPART Auction';
        } elseif ($id == 3) {
            $terminal = 'Manheim Auction';
        } elseif ($id == 4) {
            $terminal = 'Auction';
        } elseif ($id == 5) {
            $terminal = 'Shop';
        } elseif ($id == 10) {
            $terminal = 'Dealership';
        } elseif ($id == 7) {
            $terminal = 'Business Location';
        } elseif ($id == 8) {
            $terminal = 'Auction (Heavy)';
        } elseif ($id == 6) {
            $terminal = 'Other';
        }
        return $terminal;
    }
}
if (!function_exists('get_dterminal_name')) {
    function get_dterminal_name($id)
    {
        $terminal = '';
        if ($id == 1) {
            $terminal = 'Residence';
        } elseif ($id == 2) {
            $terminal = 'COPART Auction';
        } elseif ($id == 3) {
            $terminal = 'Manheim Auction';
        } elseif ($id == 4) {
            $terminal = 'IAAI Auction';
        } elseif ($id == 5) {
            $terminal = 'Body Shop';
        } elseif ($id == 10) {
            $terminal = 'Auction(Heavy)';
        } elseif ($id == 7) {
            $terminal = 'Port';
        } elseif ($id == 8) {
            $terminal = 'Other';
        } elseif ($id == 6) {
            $terminal = 'AirPort';
        } elseif ($id == 9) {
            $terminal = 'Business Location';
        } elseif ($id == 11) {
            $terminal = 'Dealership';
        }
        return $terminal;
    }
}
if (!function_exists('get_cartype')) {
    function get_cartype($id)
    {
        if ($id == 1) {
            return 'Open';
        } else {
            return 'Enclosed';
        }
    }
}
if (!function_exists('get_car_or_heavy')) {
    function get_car_or_heavy($id)
    {
        if ($id == 1) {
            return 'CAR/MOTOR';
        } elseif ($id == 2) {
            return '<span class="badge badge-pill badge-danger mt-2 ">' . 'HEAVY' . '</span>';
        } elseif ($id == 3) {
            return '<span class="badge badge-pill badge-danger mt-2 ">' . 'Freight' . '</span>';
        }
    }
}
if (!function_exists('get_condtion')) {
    function get_condtion($id)
    {
        if ($id == 1) {
            return 'Running';
        } else {
            return 'Not- Running';
        }
    }
}
if (!function_exists('get_payment_detail')) {
//shariq
    function get_payment_detail($id, $column)
    {
        $setting = App\general_setting::first();
        $paid = \App\orderpayment::where('orderId', $id)
            ->where('created_at', '>=', \Carbon\Carbon::today()->subDays($setting->no_days))
            ->first();

        if (!empty($paid)) {
            return ucfirst($paid->$column);
        } else {
            return $column;
        }
    }
}
//faisal
if (!function_exists('get_paid')) {
    function get_paid($id)
    {
        $value = 'Unpaid';
        $paid = \App\orderpayment::where('orderId', $id)->first();

        if (!empty($paid)) {
            return ucfirst($paid->payment_status);
        } else {
            return $value;
        }
    }
}
if (!function_exists('get_carrier')) {
    function get_carrier($id, $column)
    {
        $setting = App\general_setting::first();
        $query = App\carrier::where('orderId', '=', $id)
            ->where('created_at', '>=', \Carbon\Carbon::today()->subDays($setting->no_days))
            ->first();
        if (!empty($query->$column)) {
            return $query->$column;
        }
    }
}
if (!function_exists('get_total_invoice')) {
    function get_total_invoice($ptype)
    {
        $setting = App\general_setting::first();
        $data = App\carrier::where('created_at', '>=', \Carbon\Carbon::today()->subDays($setting->no_days))
            ->orderBy('id', 'DESC')
            ->count();
        if (!empty($data)) {
            return $data;
        } else {
            return 0;
        }
    }
}
if (!function_exists('get_total_storage')) {
    function get_total_storage($ptype)
    {
        $setting = App\general_setting::first();
        // $data = App\storage::where('created_at', '>=', \Carbon\Carbon::today()->subDays($setting->no_days))
        //     ->orderBy('id', 'DESC')->count();

        $ptype = 1;
        $user = Auth::user();
        $usersetting = App\user_setting::where('user_id', '=', $user->id)->first();
        if (!empty($usersetting)) {
            $ptype = $usersetting['penal_type'];
        }

        $data = \App\AutoOrder::where('pstatus', 11)
            ->where('storage_id', '>', 0)
            ->where('paneltype', '=', $ptype)
            ->where('created_at', '>=', \Carbon\Carbon::today()->subDays($setting->no_days))
            ->where(function ($q) use ($user) {
                if ($user->userRole->name == 'Manager') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('manager_id', $user->id)->orWhere('order_taker_id', $user->id);
                    }
                } elseif ($user->userRole->name == 'Dispatcher') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('dispatcher_id', $user->id);
                    }
                } elseif ($user->userRole->name == 'Delivery Boy') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('delivery_boy_id', $user->id);
                    }
                } elseif ($user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('order_taker_id', $user->id);
                    } elseif ($user->order_taker_quote == 2) {
                        $q->whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$user->id]);
                    }
                }
            })
            ->count();
        if (!empty($data)) {
            return $data;
        } else {
            return 0;
        }
    }
}
if (!function_exists('get_total_carrier')) {
    function get_total_carrier($ptype)
    {
        $setting = App\general_setting::first();
        $data = App\carrier::where('created_at', '>=', \Carbon\Carbon::today()->subDays($setting->no_days))
            ->orderBy('id', 'DESC')
            ->count();
        if (!empty($data)) {
            return $data;
        } else {
            return 0;
        }
    }
}
if (!function_exists('get_total_customer')) {
    function get_total_customer($ptype)
    {
        // echo "<pre>";
        // print_r($ptype);exit();
        $user = \Illuminate\Support\Facades\Auth::user();

        $setting = App\general_setting::first();
        $data = App\AutoOrder::where('created_at', '>=', \Carbon\Carbon::today()->subDays($setting->no_days))
            ->where('paneltype', '=', $ptype)
            ->where(function ($q) use ($user) {
                if ($user->userRole->name == 'Manager') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('manager_id', $user->id)->orWhere('order_taker_id', $user->id);
                    }
                } elseif ($user->userRole->name == 'Dispatcher') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('dispatcher_id', $user->id);
                    }
                } elseif ($user->userRole->name == 'Delivery Boy') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('delivery_boy_id', $user->id);
                    }
                } elseif ($user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('order_taker_id', $user->id);
                    } elseif ($user->order_taker_quote == 2) {
                        $q->whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$user->id]);
                    }
                }
            })
            ->orderBy('id', 'DESC')
            ->count();
        if (!empty($data)) {
            return $data;
        } else {
            return 0;
        }
    }
}
if (!function_exists('get_user_attendance')) {
    function get_user_attendance($date, $user_id, $value)
    {
        $data = App\attendance::where('attendance_date', 'like', '%' . $date . '%')
            ->where('user_id', '=', $user_id)
            ->first();
        if (!empty($data) && strtotime($data->$value)) {
            return date('M,d Y H:i:s', strtotime($data->$value));
        } else {
            return 'N/A';
        }
    }
}
if (!function_exists('issue_comments')) {
    function issue_comments($issue_id, $user_id)
    {
        $data = App\issue_chats::where('issueId', $issue_id)->where('userId', $user_id)->first();
        if (!empty($data)) {
            return $data->comments;
        } else {
            return 'NOT FOUND';
        }
    }
}
if (!function_exists('get_profit')) {
    function get_profit($id)
    {
        $data = App\profit::where('order_id', $id)->first();
        if (!empty($data)) {
            return $data->profit;
        } else {
            return '';
        }
    }
}
if (!function_exists('rating')) {
    function rating($total, $role, $id, $pstatus, $from, $to, $count)
    {
        $ptype = 1;
        $usersetting = App\user_setting::where('user_id', '=', $id)->first();
        if (!empty($usersetting)) {
            $ptype = $usersetting['penal_type'];
        }
        $rate = 0;
        $total = $total == 0 ? $count : $total;
        if ($pstatus == 6) {
            $eq = '<=';
        } else {
            $eq = '=';
        }
        if ($role == 'CSR' || $role == 'Seller Agent' || $role == 'Order Taker') {
            if (Auth::user()->order_taker_quote == 1) {
                $total_order = \App\AutoOrder::where('order_taker_id', $id)
                    ->whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->count();
                $rate = ($total_order * $count) / $total;
            } elseif (Auth::user()->order_taker_quote == 2) {
                $total_order = \App\AutoOrder::whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$id])
                    ->whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->count();
                $rate = ($total_order * $count) / $total;
            } else {
                $total_order = \App\AutoOrder::whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->count();
                $rate = ($total_order * $count) / $total;
            }
        } elseif ($role == 'Dispatcher') {
            if (Auth::user()->order_taker_quote == 1) {
                $total_order = \App\AutoOrder::where('dispatcher_id', $id)
                    ->whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->count();
                $rate = ($total_order * $count) / $total;
            } else {
                $total_order = \App\AutoOrder::whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->count();
                $rate = ($total_order * $count) / $total;
            }
        } elseif ($role == 'Delivery Boy') {
            if (Auth::user()->order_taker_quote == 1) {
                $total_order = \App\AutoOrder::where('delivery_boy_id', $id)
                    ->whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->count();
                $rate = ($total_order * $count) / $total;
            } else {
                $total_order = \App\AutoOrder::whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->count();
                $rate = ($total_order * $count) / $total;
            }
        } elseif ($role == 'Manager') {
            if (Auth::user()->order_taker_quote == 1) {
                $total_order = \App\AutoOrder::where(function ($q) use ($id) {
                    $q->where('manager_id', $id)->orWhere('order_taker_id', $id);
                })
                    ->whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->count();
                $rate = ($total_order * $count) / $total;
            } else {
                $total_order = \App\AutoOrder::whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->count();
                $rate = ($total_order * $count) / $total;
            }
        } else {
            $total_order = \App\AutoOrder::where('pstatus', $eq, $pstatus)
                ->whereBetween('created_at', [$from, $to])
                ->where('paneltype', '=', $ptype)
                ->count();
            $rate = ($total_order * $count) / $total;
        }
        $rate = round($rate, 1);
        return $rate;
    }
}
if (!function_exists('pstatusRole')) {
    function pstatusRole($role, $id, $pstatus, $from, $to)
    {
        $ptype = 1;
        $usersetting = App\user_setting::where('user_id', '=', $id)->first();
        if (!empty($usersetting)) {
            $ptype = $usersetting['penal_type'];
        }
        $total_order = 0;
        if ($pstatus == 6) {
            $eq = '<=';
        } else {
            $eq = '=';
        }
        if ($role == 'CSR' || $role == 'Seller Agent' || $role == 'Order Taker') {
            if (Auth::user()->order_taker_quote == 1) {
                $total_order = \App\AutoOrder::where('order_taker_id', $id)
                    ->whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->count();
            } elseif (Auth::user()->order_taker_quote == 2) {
                $total_order = \App\AutoOrder::whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$id])
                    ->whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->count();
            } else {
                $total_order = \App\AutoOrder::whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->count();
            }
        } elseif ($role == 'Dispatcher') {
            if (Auth::user()->order_taker_quote == 1) {
                $total_order = \App\AutoOrder::where('dispatcher_id', $id)
                    ->whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->count();
            } else {
                $total_order = \App\AutoOrder::whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->count();
            }
        } elseif ($role == 'Delivery Boy') {
            if (Auth::user()->order_taker_quote == 1) {
                $total_order = \App\AutoOrder::where('delivery_boy_id', $id)
                    ->whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->count();
            } else {
                $total_order = \App\AutoOrder::whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->count();
            }
        } elseif ($role == 'Manager') {
            if (Auth::user()->order_taker_quote == 1) {
                $total_order = \App\AutoOrder::where(function ($q) use ($id) {
                    $q->where('manager_id', $id)->orWhere('order_taker_id', $id);
                })
                    ->whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->count();
            } else {
                $total_order = \App\AutoOrder::whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->count();
            }
        } else {
            $total_order = \App\AutoOrder::where('pstatus', $eq, $pstatus)
                ->whereBetween('created_at', [$from, $to])
                ->where('paneltype', '=', $ptype)
                ->count();
        }
        return $total_order;
    }
}
if (!function_exists('rating3')) {
    function rating3($total, $role, $id, $pstatus, $from, $to, $count)
    {
        $ptype = 1;
        $usersetting = App\user_setting::where('user_id', '=', $id)->first();
        if (!empty($usersetting)) {
            $ptype = $usersetting['penal_type'];
        }
        $rate = 0;
        $total = $total == 0 ? $count : $total;
        if ($role == 'Dispatcher') {
            if (Auth::user()->order_taker_quote == 1) {
                $total_order = \App\AutoOrder::where('dispatcher_id', $id)
                    ->whereBetween('created_at', [$from, $to])
                    ->where('paneltype', '=', $ptype);
            } else {
                $total_order = \App\AutoOrder::where('dispatcher_id', $id)
                    ->whereBetween('created_at', [$from, $to])
                    ->where('paneltype', '=', $ptype);
            }
        } else {
            $total_order = \App\AutoOrder::where('dispatcher_id', '<>', null)
                ->whereBetween('created_at', [$from, $to])
                ->where('paneltype', '=', $ptype);
        }
        if (!empty($pstatus)) {
            $total_order = $total_order->where('pstatus', $pstatus)->count();
        } else {
            $total_order = $total_order->count();
        }
        $rate = ($total_order * $count) / $total;
        $rate = round($rate, 1);
        return $rate;
    }
}
if (!function_exists('pstatusRole3')) {
    function pstatusRole3($role, $id, $pstatus, $from, $to)
    {
        $ptype = 1;
        $usersetting = App\user_setting::where('user_id', '=', $id)->first();
        if (!empty($usersetting)) {
            $ptype = $usersetting['penal_type'];
        }
        $total_order = 0;
        if ($role == 'Dispatcher') {
            if (Auth::user()->order_taker_quote == 1) {
                $total_order = \App\AutoOrder::where('dispatcher_id', $id)
                    ->whereBetween('created_at', [$from, $to])
                    ->where('paneltype', '=', $ptype);
            } else {
                $total_order = \App\AutoOrder::where('dispatcher_id', '<>', null)
                    ->whereBetween('created_at', [$from, $to])
                    ->where('paneltype', '=', $ptype);
            }
        } else {
            $total_order = \App\AutoOrder::where('dispatcher_id', '<>', null)
                ->whereBetween('created_at', [$from, $to])
                ->where('paneltype', '=', $ptype);
        }
        if (!empty($pstatus)) {
            $total_order = $total_order->where('pstatus', $pstatus)->count();
        } else {
            $total_order = $total_order->count();
        }
        return $total_order;
    }
}
if (!function_exists('rating5')) {
    function rating5($total, $role, $id, $pstatus, $from, $to, $count)
    {
        $ptype = 1;
        $usersetting = App\user_setting::where('user_id', '=', $id)->first();
        if (!empty($usersetting)) {
            $ptype = $usersetting['penal_type'];
        }
        $rate = 0;
        $total = $total == 0 ? $count : $total;
        if ($pstatus == 6) {
            $eq = '<=';
        } else {
            $eq = '=';
        }
        if ($role == 'CSR' || $role == 'Seller Agent' || $role == 'Order Taker') {
            if (Auth::user()->order_taker_quote == 1) {
                $total_order = \App\AutoOrder::where('order_taker_id', $id)
                    ->whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->where('booking_confirm', 'may be')
                    ->count();
                $rate = ($total_order * $count) / $total;
            } elseif (Auth::user()->order_taker_quote == 2) {
                $total_order = \App\AutoOrder::whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$id])
                    ->whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->where('booking_confirm', 'may be')
                    ->count();
                $rate = ($total_order * $count) / $total;
            } else {
                $total_order = \App\AutoOrder::whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->where('booking_confirm', 'may be')
                    ->count();
                $rate = ($total_order * $count) / $total;
            }
        } elseif ($role == 'Dispatcher') {
            if (Auth::user()->order_taker_quote == 1) {
                $total_order = \App\AutoOrder::where('dispatcher_id', $id)
                    ->whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->where('booking_confirm', 'may be')
                    ->count();
                $rate = ($total_order * $count) / $total;
            } else {
                $total_order = \App\AutoOrder::where('pstatus', $eq, $pstatus)
                    ->whereBetween('created_at', [$from, $to])
                    ->where('paneltype', '=', $ptype)
                    ->where('booking_confirm', 'may be')
                    ->count();
                $rate = ($total_order * $count) / $total;
            }
        } elseif ($role == 'Delivery Boy') {
            if (Auth::user()->order_taker_quote == 1) {
                $total_order = \App\AutoOrder::where('delivery_boy_id', $id)
                    ->whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->where('booking_confirm', 'may be')
                    ->count();
                $rate = ($total_order * $count) / $total;
            } else {
                $total_order = \App\AutoOrder::where('pstatus', $eq, $pstatus)
                    ->whereBetween('created_at', [$from, $to])
                    ->where('paneltype', '=', $ptype)
                    ->where('booking_confirm', 'may be')
                    ->count();
                $rate = ($total_order * $count) / $total;
            }
        } elseif ($role == 'Manager') {
            if (Auth::user()->order_taker_quote == 1) {
                $total_order = \App\AutoOrder::where(function ($q) use ($id) {
                    $q->where('manager_id', $id)->orWhere('order_taker_id', $id);
                })
                    ->whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->where('booking_confirm', 'may be')
                    ->count();
                $rate = ($total_order * $count) / $total;
            } else {
                $total_order = \App\AutoOrder::where('pstatus', $eq, $pstatus)
                    ->whereBetween('created_at', [$from, $to])
                    ->where('paneltype', '=', $ptype)
                    ->where('booking_confirm', 'may be')
                    ->count();
                $rate = ($total_order * $count) / $total;
            }
        } else {
            $total_order = \App\AutoOrder::where('pstatus', $eq, $pstatus)
                ->whereBetween('created_at', [$from, $to])
                ->where('paneltype', '=', $ptype)
                ->where('booking_confirm', 'may be')
                ->count();
            $rate = ($total_order * $count) / $total;
        }
        $rate = round($rate, 1);
        return $rate;
    }
}
if (!function_exists('pstatusRole5')) {
    function pstatusRole5($role, $id, $pstatus, $from, $to)
    {
        $ptype = 1;
        $usersetting = App\user_setting::where('user_id', '=', $id)->first();
        if (!empty($usersetting)) {
            $ptype = $usersetting['penal_type'];
        }
        $total_order = 0;
        if ($pstatus == 6) {
            $eq = '<=';
        } else {
            $eq = '=';
        }
        if ($role == 'CSR' || $role == 'Seller Agent' || $role == 'Order Taker') {
            if (Auth::user()->order_taker_quote == 1) {
                $total_order = \App\AutoOrder::where('order_taker_id', $id)
                    ->whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->where('booking_confirm', 'may be')
                    ->count();
            } elseif (Auth::user()->order_taker_quote == 2) {
                $total_order = \App\AutoOrder::whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$id])
                    ->whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->where('booking_confirm', 'may be')
                    ->count();
            } else {
                $total_order = \App\AutoOrder::where('pstatus', $eq, $pstatus)
                    ->whereBetween('created_at', [$from, $to])
                    ->where('paneltype', '=', $ptype)
                    ->where('booking_confirm', 'may be')
                    ->count();
            }
        } elseif ($role == 'Dispatcher') {
            if (Auth::user()->order_taker_quote == 1) {
                $total_order = \App\AutoOrder::where('dispatcher_id', $id)
                    ->whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->where('booking_confirm', 'may be')
                    ->count();
            } else {
                $total_order = \App\AutoOrder::where('pstatus', $eq, $pstatus)
                    ->whereBetween('created_at', [$from, $to])
                    ->where('paneltype', '=', $ptype)
                    ->where('booking_confirm', 'may be')
                    ->count();
            }
        } elseif ($role == 'Delivery Boy') {
            if (Auth::user()->order_taker_quote == 1) {
                $total_order = \App\AutoOrder::where('delivery_boy_id', $id)
                    ->whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->where('booking_confirm', 'may be')
                    ->count();
            } else {
                $total_order = \App\AutoOrder::where('pstatus', $eq, $pstatus)
                    ->whereBetween('created_at', [$from, $to])
                    ->where('paneltype', '=', $ptype)
                    ->where('booking_confirm', 'may be')
                    ->count();
            }
        } elseif ($role == 'Manager') {
            if (Auth::user()->order_taker_quote == 1) {
                $total_order = \App\AutoOrder::where(function ($q) use ($id) {
                    $q->where('manager_id', $id)->orWhere('order_taker_id', $id);
                })
                    ->whereBetween('created_at', [$from, $to])
                    ->where('pstatus', $eq, $pstatus)
                    ->where('paneltype', '=', $ptype)
                    ->where('booking_confirm', 'may be')
                    ->count();
            } else {
                $total_order = \App\AutoOrder::where('pstatus', $eq, $pstatus)
                    ->whereBetween('created_at', [$from, $to])
                    ->where('paneltype', '=', $ptype)
                    ->where('booking_confirm', 'may be')
                    ->count();
            }
        } else {
            $total_order = \App\AutoOrder::where('pstatus', $eq, $pstatus)
                ->whereBetween('created_at', [$from, $to])
                ->where('paneltype', '=', $ptype)
                ->where('booking_confirm', 'may be')
                ->count();
        }
        return $total_order;
    }
}
if (!function_exists('shipment_status')) {
    function shipment_status($id)
    {
        $data = '';
        if ($id == 20) {
            $data = 'Relist';
        } elseif ($id == 21) {
            $data = 'Price Raise';
        } elseif ($id == 22) {
            $data = 'Approach Id';
        } elseif ($id == 23) {
            $data = 'Different Port';
        } elseif ($id == 24) {
            $data = 'Carrier Update';
        } elseif ($id == 25) {
            $data = 'Storage';
        } elseif ($id == 26) {
            $data = 'Approaching';
        } elseif ($id == 27) {
            $data = 'Auction Update Request';
        }
        return $data;
    }
}
if (!function_exists('shipment_status2')) {
    function shipment_status2($id)
    {
        $data = '';
        if ($id == 20) {
            $data = '<span class="badge badge-danger text-light">Relist</span>';
        } elseif ($id == 21) {
            $data = '<span class="badge badge-success text-light">Price Raise</span>';
        } elseif ($id == 22) {
            $data = '<span class="badge badge-info text-light">Approach Id</span>';
        } elseif ($id == 23) {
            $data = '<span class="badge badge-warning text-light">Different Port</span>';
        } elseif ($id == 25) {
            $data = '<span class="badge badge-teal text-light">Storage</span>';
        } elseif ($id == 24) {
            $data = '<span class="badge badge-amber text-light">Carrier Update</span>';
        } elseif ($id == 26) {
            $data = '<span class="badge badge-pink text-light">Approaching</span>';
        } elseif ($id == 27) {
            $data = '<span class="badge badge-primary text-light">Auction Update</span>';
        }
        return $data;
    }
}
if (!function_exists('get_previous_status')) {
    function get_previous_status($id)
    {
        $order = \App\AutoOrder::find($id);

        $statusTimes = [
            'NEW_Created' => $order->NEW_Created,
            'Interested_Created' => $order->Interested_Created,
            'FollowMore_Created' => $order->FollowMore_Created,
            'AskingLow_Created' => $order->AskingLow_Created,
            'NotInterested_Created' => $order->NotInterested_Created,
            'NoResponse_Created' => $order->NoResponse_Created,
            'TimeQuote_Created' => $order->TimeQuote_Created,
            'PaymentMissing_Created' => $order->PaymentMissing_Created,
            'Booked_Created' => $order->Booked_Created,
            'Listed_Created' => $order->Listed_Created,
            'Schedule_Created' => $order->Schedule_Created,
            'Pickup_Created' => $order->Pickup_Created,
            'Delivered_Created' => $order->Delivered_Created,
            'Completed_Created' => $order->Completed_Created,
            'Cancel_Created' => $order->Cancel_Created,
            'Deleted_Created' => $order->Deleted_Created,
            'OwesMoney_Created' => $order->OwesMoney_Created,
            'CarrierUpdate_Created' => $order->CarrierUpdate_Created,
            'OnApproval_Created' => $order->OnApproval_Created,
            'CancelOnApproval_Created' => $order->CancelOnApproval_Created,
        ];

        $currentStatus = '';
        $previousStatus = '';
        $latestTime = null;

        foreach ($statusTimes as $status => $createdTime) {
            if ($createdTime !== null) {
                if ($latestTime === null || $createdTime > $latestTime) {
                    $previousStatus = $currentStatus;
                    $currentStatus = $status;
                    $latestTime = $createdTime;
                } elseif ($createdTime < $latestTime) {
                    if ($previousStatus === '' || $createdTime > $statusTimes[$previousStatus]) {
                        $previousStatus = $status;
                    }
                }
            }
        }

        return [
            'current_status' => $currentStatus,
            'previous_status' => $previousStatus,
        ];
    }
}
if (!function_exists('format_status')) {
    function format_status($status)
    {
        $status = str_replace('_Created', '', $status);
        $formattedStatus = preg_replace('/(?<!^)(?=[A-Z])/', ' ', $status);

        $badges = [
            'N E W' => "<span class='badge badge-orange txt-white'>New</span>",
            'Interested' => "<span class='badge badge-warning txt-white'>Interested</span>",
            'Follow More' => "<span class='badge badge-primary txt-white'>FollowMore</span>",
            'Asking Low' => "<span class='badge badge-pink txt-white'>AskingLow</span>",
            'Not Interested' => "<span class='badge badge-success'>Not Interested</span>",
            'No Response' => "<span class='badge badge-dark txt-white'>No Response</span>",
            'Time Quote' => "<span class='badge badge-amber txt-white'>Time Quote</span>",
            'Payment Missing' => "<span class='badge badge-primary txt-white'>Payment Missing</span>",
            'Booked' => "<span class='badge badge-warning txt-white'>Booked</span>",
            'Listed' => "<span class='badge badge-pink txt-white'>Listed</span>",
            'Schedule' => "<span class='badge badge-success'>Schedule</span>",
            'Pickup' => "<span class='badge badge-dark txt-white'>Pickup</span>",
            'Delivered' => "<span class='badge badge-amber txt-white'>Delivered</span>",
            'Completed' => "<span class='badge badge-teal txt-white'>Completed</span>",
            'Cancel' => "<span class='badge badge-danger txt-white'>Cancel</span>",
            'Deleted' => "<span class='badge badge-danger txt-white'>Deleted</span>",
            'Carrier Update' => "<span class='badge badge-primary txt-white'>Carrier Update</span>",
            'On Approval' => "<span class='badge badge-primary txt-white'>On Approval</span>",
            'Cancel On Approval' => "<span class='badge badge-danger get_car_or_heavy txt-white'>On Approval Cancelled</span>",
            'Approaching' => "<span class='badge badge-secondary'>Approaching</span>",
        ];

        return $badges[$formattedStatus] ?? $formattedStatus;
    }
}

if (!function_exists('getNewQuoteTickerHtml')) {
    function getNewQuoteTickerHtml()
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        // Decode access if stored as comma-separated string
        $emp_panel_access = explode(',', $user->emp_panel_access);

        // Check if user has panel access 4
        if (in_array(4, $emp_panel_access)) {

            $oneHourAgo = \Carbon\Carbon::now()->subMinutes(30);

            // Fetch only the first matching order
            $order = \App\AutoOrder::where('pstatus', 0)
                ->where('paneltype', 4)
                ->where('created_at', '>=', $oneHourAgo)
                ->latest('created_at') // Optional: fetch most recent
                ->first();

            // If an order exists, return ticker HTML
            if ($order) {
                return "
                <div class='ticker-wrapper'>
                    <div class='ticker-message'>
                        🚨 New Order ID <strong>{$order->id}</strong> launched from <strong>SHIPA1 WEBSITE</strong>. Please check.
                    </div>
                </div>
            ";
            }
        }

        return ''; // No ticker if no access or no recent orders
    }

}
?>

<style>
    .txt-white {
        color: white !important;
    }

    .badge-orange {
        color: #212529;
        background-color: #F49917 !important;
    }

    .badge-warning {
        color: #fff;
        background-color: #009eda !important;
    }

    .badge-primary {
        color: #fff;
        background-color: #007bff !important;
    }

    .badge-pink {
        background: #E91E63 !important;
    }

    .badge-success {
        color: #000000;
        background-color: #23BF08 !important;
    }

    .badge-dark {
        color: #fff;
        background-color: #343a40 !important;
    }

    .badge-amber {
        background: #FF6F00 !important;
    }

    .badge-teal {
        background: #004D40 !important;
    }

    .badge-brown {
        background: #542e2e !important;
    }

    .badge-purple {
        background: #600060 !important;
    }
</style>
