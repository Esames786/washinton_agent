<?php

namespace App\Http\Controllers\phone_quote;

use App\FieldLabel;
use App\ShipaQuery;
use App\Http\Controllers\Controller;
use App\DemardVehicle;
use App\PaymentSystem;
use App\SheetDetails;
use Illuminate\Http\Request;
use App\Mail\SendOrderMail;
use App\Mail\EditQuotePriceMail;
use Illuminate\Support\Facades\Log;
use Vinkla\Hashids\Facades\Hashids;
use App\User;
use App\role;
use App\AutoOrder;
use App\creditcard;
use App\orderpayment;
use App\report;
use App\singlereport;
use App\zipcodes;
use App\MilePrice;
use App\VehicleExtra;
use App\WPPricePerm;
use App\Rules;
use App\WPGeneralException;
use App\UserOld;
use App\WPVehicleListing;
use App\carrier;
use App\payment_log;
use App\cachee;
use App\order_history;
use App\auction_detail;
use App\sheet_data;
use App\user_setting;
use App\general_setting;
use App\call_history;
use App\count_day;
use App\profit;
use App\order_freight;
use App\AgentReport;
use Carbon\Carbon;
use Session;
use Redirect;
use Hash;
use Mail;
use Auth;
use DB;
use DateTime;
use App\PhoneDigit;
use App\OrderTakerClientAccess;
use App\OrderTakerQouteAccess;
use App\Coupon;
use App\DailyQoute;
use App\CarrierOldOrderHistory;
use App\Rating;
use Illuminate\Support\Facades\Validator;
use App\PriceChecker;
use App\Mail\ShipA1BookingConfirmationMail;
use App\Services\CentralGateway\GatewayClient;
use App\QaVerifyHistory;
use App\OwesHistory;
use App\CarrierApproaching;
use App\NatureOfCustomer;
use App\Mail\PickupConfirmationMail;
use App\Mail\BookingConfirmationMail;
use App\BlockPhone;
use GuzzleHttp\Client;
use Stripe\Stripe;
use Stripe\Token;
use Stripe\Charge;
use App\UserTargets;
use App\chat;
use App\PublicOrderChat;
use App\GroupChat;
use App\Flag;
use App\PriceCheckerPrice;
use Illuminate\Support\Facades\Storage;

class NewQuote extends Controller
{
    public function add_new()
    {
        $data = role::all();
        $orderId = 0;
        $phoneno = "";
        $oemail = "";
        if (Auth::check()) {
            if (Auth::user()->assign_daily_qoute > 0) {
                $daily = DailyQoute::where('user_id', Auth::user()->id)->whereDate('date', date('Y-m-d'))->first();
                if (!empty($daily)) {
                    if ($daily->total_qoute == 0) {
                        return back()->with('err', 'Your creating qoute limit has end. Please booked atleast one to create new.');
                    }
                }
            }
            $label = FieldLabel::all();
            // dd($label->toArray());
            return view('main.phone_quote.new_quote.index', compact('data', 'orderId', 'phoneno', 'label', 'oemail'));
        } else {
            return redirect('/loginn/');
        }
    }

    public function add_new_heavy()
    {
        $data = role::all();
        $orderId = 0;
        $phoneno = "";
        $label = FieldLabel::all();
        if (Auth::check()) {
            if (Auth::user()->assign_daily_qoute > 0) {
                $daily = DailyQoute::where('user_id', Auth::user()->id)->whereDate('date', date('Y-m-d'))->first();
                if (!empty($daily)) {
                    if ($daily->total_qoute == 0) {
                        return back()->with('err', 'Your creating qoute limit has end. Please booked atleast one to create new.');
                    }
                }
            }
            return view('main.phone_quote.new_quote_heavy.index', compact('data', 'orderId', 'phoneno', 'label'));
        } else {
            return redirect('/loginn/');
        }
    }

    public function add_new_freight()
    {
        $data = role::all();
        $orderId = 0;
        $phoneno = "";
        $label = FieldLabel::all();
        if (Auth::check()) {
            if (Auth::user()->assign_daily_qoute > 0) {
                $daily = DailyQoute::where('user_id', Auth::user()->id)->whereDate('date', date('Y-m-d'))->first();
                if (!empty($daily)) {
                    if ($daily->total_qoute == 0) {
                        return back()->with('err', 'Your creating qoute limit has end. Please booked atleast one to create new.');
                    }
                }
            }
            return view('main.phone_quote.new_quote_frieght.index', compact('data', 'orderId', 'phoneno', 'label'));
        } else {
            return redirect('/loginn/');
        }
    }

    public function new_edit($id)
    {
        $data = AutoOrder::find($id);
        $nature = '';
        $nature = NatureOfCustomer::with('user')->where('phone', $data->ophone)->orderby("order_id", 'DESC')->first();

        if ($nature != null) {
            $nature = $nature->description;
        }


        $freight = order_freight::where('order_id', $id)->first();
        $count_previous = AutoOrder::where('main_ph', $data->main_ph)->count();
        $label = FieldLabel::all();

        $old_count_previous = AutoOrder::where('mainPhNum', $data->ophone)->count();

        $credit_card = DB::table('order')
            ->join('creditcards', 'creditcards.orderId', '=', 'order.id')
            ->where('order.main_ph', $data->main_ph)
            ->count();
        $old_credit_card = AutoOrder::where('card_number', '!=', '')
            ->where('id', '!=', $data->id)
            ->limit(100)->get();
        $old = [];
        if (count($old_credit_card) > 0) {
            $phone = $data->mainPhNum;
            $email = $data->oemail;
            $ophone = $data->ophone;
            foreach ($old_credit_card as $key => $value) {
                $card_number = str_replace('^*-', '', $value->card_number);
                if ($phone == $value->mainPhNum && $phone != '') {
                    if ($card_number != '') {
                        array_push($old, $value);
                    }
                } elseif ($ophone == $value->ophone && $ophone != '') {
                    if ($card_number != '') {
                        array_push($old, $value);
                    }
                } elseif ($email == $value->oemail && $email != '') {
                    if ($card_number != '') {
                        array_push($old, $value);
                    }
                }
            }
        }

        // echo "<pre>";
        // print_r($count_previous);
        // echo "<br>";
        // print_r($old_count_previous);
        // exit();

        $credit_card_data = DB::table('order')
            ->join('creditcards', 'creditcards.orderId', '=', 'order.id')
            ->where('order.main_ph', $data->main_ph)
            ->limit(100)
            ->get();
        $singlereport = singlereport::where('orderId', '=', $id)
            ->where('pstatus', '=', 8)
            ->first();

        if ($data->car_type == 1 || $data->car_type == NULL) {
            return view('main.phone_quote.new.new_edit', compact('nature', 'data', 'singlereport', 'count_previous', 'credit_card', 'credit_card_data', 'old', 'old_count_previous', 'label'));
        }
        if ($data->car_type == 2) {
            return view('main.phone_quote.new.new_edit_heavy', compact('nature', 'data', 'singlereport', 'count_previous', 'credit_card', 'credit_card_data', 'old', 'old_count_previous', 'label'));
        }
        if ($data->car_type == 3) {
            return view('main.phone_quote.new.new_edit_frieght', compact('nature', 'data', 'singlereport', 'count_previous', 'credit_card', 'credit_card_data', 'old', 'old_count_previous', 'freight', 'label'));
        }
    }

    public function getPhoneCard(Request $request)
    {
        // dd($request->toArray());
        $data = AutoOrder::find($request->autoorderGet);
        $freight = order_freight::where('order_id', $request->autoorderGet)->first();
        $count_previous = AutoOrder::where('main_ph', $data->main_ph)->count();

        $old_count_previous = AutoOrder::where('mainPhNum', $data->ophone)->count();

        $credit_card = DB::table('order')
            ->join('creditcards', 'creditcards.orderId', '=', 'order.id')
            ->where('order.main_ph', $data->main_ph)
            ->count();
        $old_credit_card = AutoOrder::where('card_number', '!=', '')
            ->where('id', '!=', $data->id)
            ->limit(100)->get();
        $old = [];
        if (count($old_credit_card) > 0) {
            $phone = $data->mainPhNum;
            $email = $data->oemail;
            $ophone = $data->ophone;
            foreach ($old_credit_card as $key => $value) {
                $card_number = str_replace('^*-', '', $value->card_number);
                if ($phone == $value->mainPhNum && $phone != '') {
                    if ($card_number != '') {
                        array_push($old, $value);
                    }
                } elseif ($ophone == $value->ophone && $ophone != '') {
                    if ($card_number != '') {
                        array_push($old, $value);
                    }
                } elseif ($email == $value->oemail && $email != '') {
                    if ($card_number != '') {
                        array_push($old, $value);
                    }
                }
            }
        }

        $credit_card_data = DB::table('order')
            ->join('creditcards', 'creditcards.orderId', '=', 'order.id')
            ->where('order.main_ph', $data->main_ph)
            ->limit(100)
            ->get();
        // dd(count($credit_card_data), $credit_card_data->toArray());
        $singlereport = singlereport::where('orderId', '=', $data->id)
            ->where('pstatus', '=', 8)
            ->first();
        if ($data->car_type == 1 || $data->car_type == NULL) {
            return [
                'data' => $data,
                'singlereport' => $singlereport,
                'count_previous' => $count_previous,
                'credit_card' => $credit_card,
                'credit_card_data' => $credit_card_data,
                'old' => $old,
                'old_count_previous' => $old_count_previous,
            ];
        }
    }

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
            $ret = "Schedule";
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
        } elseif ($id == 18) {
            $ret = "OnApproval";
        } elseif ($id == 19) {
            $ret = "CancelOnapproval";
        }
        return $ret;
    }

    public function createOrderHistory($order_id, $column_name, $name, $new_val, $userId = null)
    {
        $old_val = "";
        $autoorder = AutoOrder::where('id', $order_id)->first([$column_name]);

        if ($autoorder) {
            $old_val = $autoorder->$column_name;
            if ($old_val != $new_val) {
                $resolvedUserId = $userId ?? (Auth::check() ? Auth::user()->id : 0);
                $resolvedRole   = Auth::check() ? Auth::user()->role : 0;

                $order_history = new order_history();
                $order_history->order_id = $order_id;
                $order_history->column_name = "$column_name";
                $order_history->old_value = "$old_val";
                $order_history->new_value = "$new_val";
                $order_history->user_id = $resolvedUserId;
                $order_history->role_name = $resolvedRole;
                $order_history->namee = $name;
                $order_history->status = 1;
                $order_history->save();
            }
        }
    }

    public function store_new_quote(Request $request)
    {
        // dd($request->toArray());
        if (Auth::check()) {

            $autoorder = AutoOrder::find($request->orderid);
            $pstatus_old = $autoorder->pstatus;

            $last_status = $this->get_pstatuss($pstatus_old);

            if (isset($request->oterminal)) {
                if ($request->oterminal != '') {
                    $autoorder->oterminal = $request->oterminal;
                    if ($request->oterminal == 2 || $request->oterminal == 3 || $request->oterminal == 4 || $request->oterminal == 8) {
                        if (isset($request->oacutiondate) || !empty($request->oacutiondate)) {
                            $autoorder->oauctiondate = $request->oacutiondate;
                        } else {
                            $autoorder->oauctiondate = NULL;
                        }
                        if (isset($request->oacutiontime) || !empty($request->oacutiontime)) {
                            $autoorder->oauctiontime = $request->oacutiontime;
                        } else {
                            $autoorder->oauctiontime = NULL;
                        }
                        if (isset($request->oacutionaccounttitle)) {
                            $autoorder->oacutionaccounttitle = $request->oacutionaccounttitle;
                        } else {
                            $autoorder->oacutionaccounttitle = NULL;
                        }
                        if (isset($request->oacutionaccountname)) {
                            $autoorder->oacutionaccountname = $request->oacutionaccountname;
                        } else {
                            $autoorder->oacutionaccountname = NULL;
                        }
                    } else {
                        $autoorder->oauctiontime = NULL;
                        $autoorder->oauctiondate = NULL;
                        $autoorder->oacutionaccounttitle = NULL;
                        $autoorder->oacutionaccountname = NULL;
                    }
                } else {
                    $autoorder->oterminal = 0;
                }
            } else {
                $autoorder->oterminal = 0;
            }

            if (isset($request->oacution)) {
                if ($request->oacution != '') {
                    $autoorder->oauction = $request->oacution;
                } else {
                    $autoorder->oauction = NULL;
                }
            } else {
                $autoorder->oauction = NULL;
            }

            if (isset($request->oacutionpho)) {
                if ($request->oacutionpho != '') {
                    $autoorder->oauctionpho = $request->oacutionpho;
                } else {
                    $autoorder->oauctionpho = NULL;
                }
            } else {
                $autoorder->oauctionpho = NULL;
            }

            if (isset($request->obuyer_no)) {
                if ($request->obuyer_no != '') {
                    $autoorder->obuyer_no = $request->obuyer_no;
                } else {
                    $autoorder->obuyer_no = NULL;
                }
            } else {
                $autoorder->obuyer_no = NULL;
            }

            if (isset($request->gate_pass_pin)) {
                if ($request->gate_pass_pin != '') {
                    $autoorder->gate_pass_pin = $request->gate_pass_pin;
                } else {
                    $autoorder->gate_pass_pin = NULL;
                }
            } else {
                $autoorder->gate_pass_pin = NULL;
            }

            if (isset($request->obuyer_lot_no)) {
                if ($request->obuyer_lot_no != '') {
                    $autoorder->obuyer_lot_no = $request->obuyer_lot_no;
                } else {
                    $autoorder->obuyer_lot_no = NULL;
                }
            } else {
                $autoorder->obuyer_lot_no = NULL;
            }

            if (isset($request->obuyer_stock_no)) {
                if ($request->obuyer_stock_no != '') {
                    $autoorder->obuyer_stock_no = $request->obuyer_stock_no;
                } else {
                    $autoorder->obuyer_stock_no = NULL;
                }
            } else {
                $autoorder->obuyer_stock_no = NULL;
            }

            if (isset($request->oname)) {
                if ($request->oname != '') {
                    $autoorder->oname = $request->oname;
                } else {
                    $autoorder->oname = NULL;
                }
            } else {
                $autoorder->oname = NULL;
            }

            if (isset($request->oemail)) {
                if ($request->oemail != '') {
                    $autoorder->oemail = $request->oemail;
                } else {
//                    $autoorder->oemail = NULL;
                }
            } else {
//                $autoorder->oemail = NULL;
            }

            if (isset($request->oemail2)) {
                if ($request->oemail2 != '') {
                    $autoorder->oemail2 = $request->oemail2;
                } else {
//                    $autoorder->oemail2 = NULL;
                }
            } else {
//                $autoorder->oemail2 = NULL;
            }

            if (isset($request->oemail3)) {
                if ($request->oemail3 != '') {
                    $autoorder->oemail3 = $request->oemail3;
                } else {
                    $autoorder->oemail3 = NULL;
                }
            } else {
                $autoorder->oemail3 = NULL;
            }

            if (isset($request->oaddress)) {
                if ($request->oaddress != '') {
                    $autoorder->oaddress = $request->oaddress;
                } else {
                    $autoorder->oaddress = NULL;
                }
            } else {
                $autoorder->oaddress = NULL;
            }

            if (isset($request->oaddress2)) {
                if ($request->oaddress2 != '') {
                    $autoorder->oaddress2 = $request->oaddress2;
                } else {
                    $autoorder->oaddress2 = NULL;
                }
            } else {
                $autoorder->oaddress2 = NULL;
            }

            // if($autoorder->ophone)
            // {
            //     $autoorder->ophone = $autoorder->ophone;
            // }
            // else{
            //     if (isset($request->ophone)) {
            //         if ($request->ophone != '') {
            //             if (count($request->ophone) > 1) {
            //                 $autoorder->ophone = implode('*^', $request->ophone);
            //             } else {
            //                 $autoorder->ophone = $request->ophone[0];
            //             }
            //         } else {
            //             $autoorder->ophone = NULL;
            //         }
            //     } else {
            //         $autoorder->ophone = NULL;
            //     }
            // }
            $orderphone = [];
            if (isset($autoorder->ophone)) {
                $orderphone = explode('*^', $autoorder->ophone);
            }
            if (isset($request->ophone2)) {
                $ophone2 = [];
                foreach ($request->ophone2 as $kkkkk => $vvvvv) {
                    if (isset($orderphone[$kkkkk])) {
                        if ($vvvvv == $orderphone[$kkkkk]) {
                            array_push($ophone2, $orderphone[$kkkkk]);
                        } else {
                            array_push($ophone2, $vvvvv);
                        }
                    } else {
                        array_push($ophone2, $vvvvv);
                    }
                }
                $ophone3 = implode('*^', $ophone2);
                $autoorder->ophone = $ophone3;
            } else {
                $autoorder->ophone = $autoorder->ophone;
            }

            if (strpos($request->o_zip1, ",") !== false) {
                $oozip = explode(',', $request->o_zip1);
                $autoorder->originzip = $oozip[2];
                $autoorder->originstate = $oozip[1];
                $autoorder->origincity = $oozip[0];
                $autoorder->originzsc = $request->o_zip1;

                if (isset($oozip[2])) {
                    $zipcodes = zipcodes::where('zipcode', $oozip[2])->first();
                    if (empty($zipcodes)) {
                        $zipcodes = new zipcodes;
                        $zipcodes->city = $oozip[0] ?? '';
                        $zipcodes->state = $oozip[1] ?? '';
                        $zipcodes->zipcode = $oozip[2] ?? '';
                        $zipcodes->save();
                    }
                }
            }

            if (isset($request->dterminal)) {
                if ($request->dterminal != '') {
                    $autoorder->dterminal = $request->dterminal;
                    if ($request->dterminal == 2 || $request->dterminal == 3 || $request->dterminal == 4 || $request->dterminal == 10) {
                        if (isset($request->dacutiondate) || !empty($request->dacutiondate)) {
                            $autoorder->dauctiondate = $request->dacutiondate;
                        } else {
                            $autoorder->dauctiondate = NULL;
                        }
                        if (isset($request->dacutiontime) || !empty($request->dacutiontime)) {
                            $autoorder->dauctiontime = $request->dacutiontime;
                        } else {
                            $autoorder->dauctiontime = NULL;
                        }
                        if (isset($request->dacutionaccounttitle)) {
                            $autoorder->dacutionaccounttitle = $request->dacutionaccounttitle;
                        } else {
                            $autoorder->dacutionaccounttitle = NULL;
                        }
                        if (isset($request->dacutionaccountname)) {
                            $autoorder->dacutionaccountname = $request->dacutionaccountname;
                        } else {
                            $autoorder->dacutionaccountname = NULL;
                        }
                    } else {
                        $autoorder->dauctiontime = NULL;
                        $autoorder->dauctiondate = NULL;
                        $autoorder->dacutionaccounttitle = NULL;
                        $autoorder->dacutionaccountname = NULL;
                    }
                } else {
                    $autoorder->dterminal = 0;
                }
            } else {
                $autoorder->dterminal = 0;
            }

            if (isset($request->port_line)) {
                if ($request->port_line != '') {
                    $autoorder->port_line = $request->port_line;
                } else {
                    $autoorder->port_line = NULL;
                }
            } else {
                $autoorder->port_line = NULL;
            }

            if (isset($request->port_dock_type)) {
                if ($request->port_dock_type != '') {
                    $autoorder->port_dock_type = $request->port_dock_type;
                } else {
                    $autoorder->port_dock_type = NULL;
                }
            } else {
                $autoorder->port_dock_type = NULL;
            }

            if (isset($request->reason_box)) {
                if ($request->reason_box != '') {
                    $autoorder->reason_box = $request->reason_box;
                } else {
                    $autoorder->reason_box = NULL;
                }
            } else {
                $autoorder->reason_box = NULL;
            }

            if (isset($request->dname)) {
                if ($request->dname != '') {
                    $autoorder->dname = $request->dname;
                } else {
                    $autoorder->dname = NULL;
                }
            } else {
                $autoorder->dname = NULL;
            }

            if (isset($request->dshipment_no)) {
                if ($request->dshipment_no != '') {
                    $autoorder->dshipment_no = $request->dshipment_no;
                } else {
                    $autoorder->dshipment_no = NULL;
                }
            } else {
                $autoorder->dshipment_no = NULL;
            }

            if (isset($request->dauction)) {
                if ($request->dauction != '') {
                    $autoorder->dauction = $request->dauction;
                } else {
                    $autoorder->dauction = NULL;
                }
            } else {
                $autoorder->dauction = NULL;
            }

            if (isset($request->dauctionpho)) {
                if ($request->dauctionpho != '') {
                    $autoorder->dauctionpho = $request->dauctionpho;
                } else {
                    $autoorder->dauctionpho = NULL;
                }
            } else {
                $autoorder->dauctionpho = NULL;
            }

            if (isset($request->port_terminal)) {
                if ($request->port_terminal != '') {
                    $autoorder->portterminal = $request->port_terminal;
                } else {
                    $autoorder->portterminal = NULL;
                }
            } else {
                $autoorder->portterminal = NULL;
            }

            if (isset($request->demail)) {
                if ($request->demail != '') {
                    $autoorder->demail = $request->demail;
                } else {
                    $autoorder->demail = NULL;
                }
            } else {
                $autoorder->demail = NULL;
            }

            // if($autoorder->dphone)
            // {
            //     $autoorder->dphone = $autoorder->dphone;
            // }
            // else{
            //     if (isset($request->dphone)) {
            //         if ($request->dphone != '') {
            //             if (count($request->dphone) > 1) {
            //                 $autoorder->dphone = implode('*^', $request->dphone);
            //             } else {
            //                 $autoorder->dphone = $request->dphone[0];
            //             }
            //         } else {
            //             $autoorder->dphone = NULL;
            //         }
            //     } else {
            //         $autoorder->dphone = NULL;
            //     }
            // }

            // if(isset($autoorder->dphone))
            // {
            //     $dphone2 = implode('*^', $request->dphone2);
            //     if($autoorder->dphone == $dphone2)
            //     {
            //         $autoorder->dphone = $autoorder->dphone;
            //     }else
            //     {
            //         $autoorder->dphone = $dphone2;
            //     }
            // }

            $orderdphone = [];
            if (isset($autoorder->dphone)) {
                $orderdphone = explode('*^', $autoorder->dphone);
            }
            if (isset($request->dphone2)) {
                $dphone2 = [];
                foreach ($request->dphone2 as $kkkkk => $vvvvv) {
                    if (isset($orderdphone[$kkkkk])) {
                        if ($vvvvv == $orderdphone[$kkkkk]) {
                            array_push($dphone2, $orderdphone[$kkkkk]);
                        } else {
                            array_push($dphone2, $vvvvv);
                        }
                    } else {
                        array_push($dphone2, $vvvvv);
                    }
                }
                $dphone3 = implode('*^', $dphone2);
                $autoorder->dphone = $dphone3;
            } else {
                $autoorder->dphone = $autoorder->dphone;
            }

            if (isset($request->daddress)) {
                if ($request->daddress != '') {
                    $autoorder->daddress = $request->daddress;
                } else {
                    $autoorder->daddress = NULL;
                }
            } else {
                $autoorder->daddress = NULL;
            }

            if (isset($request->getCheckPrice)) {
                if ($request->getCheckPrice != '') {
                    $autoorder->getCheckPrice = $request->getCheckPrice;
                } else {
                    $autoorder->getCheckPrice = NULL;
                }
            } else {
                $autoorder->getCheckPrice = NULL;
            }

            if (isset($request->daddress2)) {
                if ($request->daddress2 != '') {
                    $autoorder->daddress2 = $request->daddress2;
                } else {
                    $autoorder->daddress2 = NULL;
                }
            } else {
                $autoorder->daddress2 = NULL;
            }


            if (strpos($request->d_zip, ",") !== false) {
                $dozip = explode(',', $request->d_zip);
                $autoorder->destinationzip = $dozip[2];
                $autoorder->destinationstate = $dozip[1];
                $autoorder->destinationcity = $dozip[0];
                $autoorder->destinationzsc = $request->d_zip;

                if (isset($dozip[2])) {
                    $zipcodes = zipcodes::where('zipcode', $dozip[2])->first();
                    if (empty($zipcodes)) {
                        $zipcodes = new zipcodes;
                        $zipcodes->city = $dozip[0] ?? '';
                        $zipcodes->state = $dozip[1] ?? '';
                        $zipcodes->zipcode = $dozip[2] ?? '';
                        $zipcodes->save();
                    }
                }
            }

            $newMiles = $request->miles;

            if(!empty($request->d_zip) && !empty( $request->o_zip1)) {
                $destination = str_replace(" ", "_", $request->d_zip);
                $origin = str_replace(" ", "_", $request->o_zip1);

                if (isset($origin) && isset($destination)) {
                    $originZip = explode(',', $origin);
                    $destinationZip = explode(',', $destination);
                    $originZip = isset($originZip[2]) ? $originZip[2] : null;
                    $destinationZip = isset($destinationZip[2]) ? $destinationZip[2] : null;

                    $From = zipcodes::where('zipcode', $originZip)
                        ->whereNotNull('latitude')
                        ->first();

                    $To = zipcodes::where('zipcode', $destinationZip)
                        ->whereNotNull('latitude')
                        ->first();

                    if(!empty($From) && !empty($To)) {

                        $latitudeFrom = $From->latitude;
                        $longitudeFrom = $From->longitude;
                        $latitudeTo = $To->latitude;
                        $longitudeTo = $To->longitude;

                        $long1 = deg2rad($longitudeFrom);
                        $long2 = deg2rad($longitudeTo);
                        $lat1 = deg2rad($latitudeFrom);
                        $lat2 = deg2rad($latitudeTo);

                        $dlong = $long2 - $long1;
                        $dlati = $lat2 - $lat1;

                        $val =
                            (sin($dlati / 2) ** 2) +
                            cos($lat1) * cos($lat2) * (sin($dlong / 2) ** 2);

                        $res = 2 * asin(sqrt($val));

                        $radius = 3958.756;
                        $newMiles = $res * $radius;
                    }
                }

            }
//            if($request->miles > 0) {
//                $autoorder->miles = $newMiles;
//            }else{
//                $autoorder->miles = !empty($request->miles) ? $request->miles : 0 ;
//            }
            $autoorder->miles = !empty($request->miles) ? $request->miles : 0 ;




            if (isset($request->vehicle_v)) {
                if ($request->vehicle_v != '') {
                    if (count($request->vehicle_v) > 1) {
                        $autoorder->vehicle_opt = implode('*^', $request->vehicle_v);
                    } else {
                        $autoorder->vehicle_opt = $request->vehicle_v[0];
                    }
                } else {
                    $autoorder->vehicle_opt = NULL;
                }
            } else {
                $autoorder->vehicle_opt = NULL;
            }


            if (!empty($request->vin_num)) {
                /*
                  if(count($request->vin_num) > 1) {

                      $autoorder->vin_num = implode('*^', $request->vin_num);
                  }else{
                      $autoorder->vin_num = $request->vin_num[0].'*^';
                  }
                */
                $counter = 0;
                $vinnumber = "";
                foreach ($request->vehicle_v as $vehicle) {
                    if ($vehicle == 'make') {
                        $vinnumber = $vinnumber . ' ' . '*^';
                    } else {
                        $vinnumber = $vinnumber . $request->vin_num[$counter] . '*^';
                        $counter++;
                    }
                }
                $autoorder->vin_num = $vinnumber;
            } else {
                $autoorder->vin_num = '';
            }

            if (!empty($request->vyear)) {
                if (count($request->vyear) > 1) {
                    $autoorder->year = implode('*^', $request->vyear);
                } else {
                    $autoorder->year = $request->vyear[0];
                }
            } else {
                $autoorder->year = '';
            }
            if (!empty($request->vmake)) {
                if (count($request->vmake) > 1) {
                    $autoorder->make = implode('*^', $request->vmake);
                } else {
                    $autoorder->make = $request->vmake[0];
                }
            } else {
                $autoorder->make = '';
            }

            if (!empty($request->vmodel)) {
                if (count($request->vmodel) > 1) {
                    $autoorder->model = implode('*^', $request->vmodel);
                } else {
                    $autoorder->model = $request->vmodel[0];
                }
            } else {
                $autoorder->model = '';
            }
            if (!empty($request->car_info)) {
                if (count($request->car_info) > 1) {
                    $autoorder->car_info = implode('*^', $request->car_info);
                } else {
                    $autoorder->car_info = $request->car_info[0];
                }
            } else {
                $autoorder->car_info = '';
            }
            if (!empty($request->car_link)) {
                if (count($request->car_link) > 1) {
                    $autoorder->car_link = implode('*^', $request->car_link);
                } else {
                    $autoorder->car_link = $request->car_link[0];
                }
            } else {
                $autoorder->car_link = '';
            }
            if ($request->hasFile('car_image')) {
                $uploadedImages = $request->file('car_image');
                $existingImages = $autoorder->car_image ? explode('*^', $autoorder->car_image) : [];
                $new_url = [];

                foreach ($uploadedImages as $image) {
                    if ($image->isValid()) {
                        $directory = public_path("Quote_car_image/{$autoorder->id}"); // Public directory path
                        if (!file_exists($directory)) {
                            mkdir($directory, 0777, true); // Create directory if not exists
                        }

                        $filename = md5_file($image->getRealPath()) . '.' . $image->getClientOriginalExtension(); // Unique filename
                        $filePath = "{$directory}/{$filename}";
                        $imageUrl = asset("Quote_car_image/{$autoorder->id}/{$filename}"); // Public URL

                        // Check if file already exists
                        if (!file_exists($filePath) && !in_array($imageUrl, $existingImages)) {
                            $image->move($directory, $filename); // Move file to public folder
                            $new_url[] = $imageUrl;
                        }
                    }
                }

                if (!empty($new_url)) {
                    $autoorder->car_image = count($new_url) > 1 ? implode('*^', $new_url) : $new_url[0];
                }
            }


            $heading = NULL;
            if (isset($request->count)) {
                if (count($request->count) > 0) {
                    $numItems = count($request->count);
                    $i = 0;
                    $j = 0;
                    $k = 0;
                    for ($vnM = 0; $vnM <= count($request->count); $vnM++) {
                        if (isset($request->vyear[$vnM]) && isset($request->vmake[$vnM]) && isset($request->vmodel[$vnM])) {
                            if (count($request->count) > 1) {
                                if (isset($request->vyear[$vnM])) {
                                    if (++$i === $numItems) {
                                        $heading .= $request->vyear[$vnM] . ' ';
                                    } else {
                                        $heading .= $request->vyear[$vnM] . ' ';
                                    }
                                }
                                if (isset($request->vmake[$vnM])) {
                                    if (++$j === $numItems) {
                                        $heading .= $request->vmake[$vnM] . ' ';
                                    } else {
                                        $heading .= $request->vmake[$vnM] . ' ';
                                    }
                                }
                                if (isset($request->vmodel[$vnM])) {
                                    if (++$k === $numItems) {
                                        $heading .= $request->vmodel[$vnM];
                                    } else {
                                        $heading .= $request->vmodel[$vnM] . '*^-';
                                    }
                                }
                            } else {
                                if (isset($request->vyear[$vnM]) && $request->vmake[$vnM] && $request->vmodel[$vnM]) {
                                    $heading .= $request->vyear[$vnM] . ' ' . $request->vmake[$vnM] . ' ' . $request->vmodel[$vnM];
                                }
                            }
                        }
                    }
                }
            }
            // if ($heading) {
            $autoorder->ymk = $heading;
            // }
            $autoorder->countt = isset($request->count) ? count($request->count) : 1;

            if (!empty($request->vehType)) {
                if (count($request->vehType) > 1) {
                    $autoorder->type = implode('*^', $request->vehType);
                } else {
                    $autoorder->type = $request->vehType[0];
                }
            } else {
                $autoorder->type = '';
            }

            if (!empty($request->vehTypeOther)) {
                if (count($request->vehTypeOther) > 1) {
                    $autoorder->typeOther = implode('*^', $request->vehTypeOther);
                } else {
                    $autoorder->typeOther = $request->vehTypeOther[0];
                }
            } else {
                $autoorder->typeOther = '';
            }

            if (!empty($request->condition)) {
                if (count($request->condition) > 1) {
                    $autoorder->condition = implode('*^', $request->condition);
                } else {
                    $autoorder->condition = $request->condition[0];
                }
            } else {
                $autoorder->condition = '';
            }


            if (!empty($request->v_con_p)) {
                if (count($request->v_con_p) > 1) {
                    $autoorder->v_con_p = implode('*^', $request->v_con_p);
                } else {
                    $autoorder->v_con_p = $request->v_con_p[0];
                }
            } else {
                $autoorder->v_con_p = '';
            }

            if (!empty($request->v_con_d)) {
                if (count($request->v_con_d) > 1) {
                    $autoorder->v_con_d = implode('*^', $request->v_con_d);
                } else {
                    $autoorder->v_con_d = $request->v_con_d[0];
                }
            } else {
                $autoorder->v_con_d = '';
            }


            if (!empty($request->trailter_type)) {
                if (count($request->trailter_type) > 1) {
                    $autoorder->transport = implode('*^', $request->trailter_type);
                    // $autoorder->type = implode('*^', $request->trailter_type);
                } else {
                    $autoorder->transport = $request->trailter_type[0];
                    // $autoorder->type = $request->trailter_type[0];
                }
            } else {
                $autoorder->transport = '';
                // $autoorder->type = '';
            }

            if (!empty($request->portTitlehidden)) {
                if (count($request->portTitlehidden) > 1) {
                    $autoorder->port_title = implode('*^', $request->portTitlehidden);
                } else {
                    $autoorder->port_title = $request->portTitlehidden[0];
                }
            } else {
                $autoorder->port_title = '';
            }

            if (isset($request->addition_info)) {
                if ($request->addition_info != '') {
                    $autoorder->add_info = $request->addition_info;
                } else {
                    $autoorder->add_info = NULL;
                }
            } else {
                $autoorder->add_info = NULL;
            }

            if (isset($request->approval_reason)) {
                if ($request->approval_reason != '') {
                    $autoorder->approval_reason = $request->approval_reason;
                } else {
                    $autoorder->approval_reason = NULL;
                }
            } else {
                $autoorder->approval_reason = NULL;
            }

            if (isset($request->dockRec_createdBy)) {
                if ($request->dockRec_createdBy != '') {
                    $autoorder->dockRec_createdBy = $request->dockRec_createdBy;
                } else {
                    $autoorder->dockRec_createdBy = NULL;
                }
            } else {
                $autoorder->dockRec_createdBy = NULL;
            }

            if (isset($request->dockRec_company)) {
                if ($request->dockRec_company != '') {
                    $autoorder->dockRec_company = $request->dockRec_company;
                } else {
                    $autoorder->dockRec_company = NULL;
                }
            } else {
                $autoorder->dockRec_company = NULL;
            }

            if (!empty($request->pickup_date)) {
                if (\Carbon\Carbon::parse($request->pickup_date)->format('Y') < \Carbon\Carbon::now()->format('Y')) {
                    $autoorder->pickup_date = date('m/d/Y');
                } else {
                    $autoorder->pickup_date = date('m/d/Y', strtotime($request->pickup_date));
                }
            } else {
                $autoorder->pickup_date = date('m/d/Y');
            }

            $fdate = date('m/d/Y');
            $tdate = $request->pickup_date;
            $datetime1 = new DateTime($fdate);
            $datetime2 = new DateTime($tdate);
            $interval = $datetime1->diff($datetime2);
            $days = $interval->format('%a');
            if ($days > 3) {
                if ($autoorder->pstatus == 0) {
                    $autoorder->pstatus = 6;
                }
            }

            if ($request->pstatus2 == 7) {
                if ($autoorder->booking_mail < 1) {
                    $autoorder->booking_mail = $autoorder->booking_mail + 1;
                    // Mail::to(['info@hellotransport.com', $autoorder->oemail])->send(new BookingConfirmationMail($autoorder));
                    $recipients = ['info@hellotransport.com'];
                    if (filter_var($autoorder->oemail, FILTER_VALIDATE_EMAIL)) {
                        $recipients[] = $autoorder->oemail;
                    }

                    Mail::to($recipients)->send(new BookingConfirmationMail($autoorder));
                }
            }

            if (isset($request->pstatus2) && !empty($request->pstatus2)) {

                $autoorder->pstatus = $request->pstatus2;
                if (isset($request->asking_low) && !empty($request->asking_low)) {
                    $autoorder->asking_low = $request->asking_low;
                }
            } else if (isset($request->pstatus) && !empty($request->pstatus)) {
                $autoorder->pstatus = $request->pstatus;
                if (isset($request->asking_low) && !empty($request->asking_low)) {
                    $autoorder->asking_low = $request->asking_low;
                }
            }
            if (!empty($request->delivery_date)) {
                if (\Carbon\Carbon::parse($request->delivery_date)->format('Y') < \Carbon\Carbon::now()->format('Y')) {
                    $autoorder->delivery_date = date('m/d/Y');
                } else {
                    $autoorder->delivery_date = date('m/d/Y', strtotime($request->delivery_date));
                }
            } else {
                $autoorder->delivery_date = date('m/d/Y');
            }

            $autoorder->pickup_when = $request->when_pickup;
            $autoorder->delivery_when = $request->when_delivery;
            if (isset($request->price)) {
                $autoorder->payment = $request->price;
            }
            if (isset($request->driver_price)) {
                $autoorder->driver_price = $request->driver_price;
            }
            if (isset($request->start_price)) {
                $autoorder->start_price = $request->start_price;
            }
            if (isset($request->how_did_you_find_us)) {
                $autoorder->how_did_you_find_us = $request->how_did_you_find_us;

                $autoorder->found_on_referral_phone = null;
                $autoorder->found_on_social_media = null;
                $autoorder->found_on_review_platform = null;

                if ($request->how_did_you_find_us === 'existing_customer' && isset($request->found_on_referral_phone)) {
                    $autoorder->found_on_referral_phone = $request->found_on_referral_phone;
                } elseif ($request->how_did_you_find_us === 'social_media' && isset($request->found_on_social_media)) {
                    $autoorder->found_on_social_media = $request->found_on_social_media;
                } elseif (
                    $request->how_did_you_find_us === 'review_platform' && isset($request->found_on_review_platform)
                ) {
                    $autoorder->found_on_review_platform = $request->found_on_review_platform;
                }
            }
            // if ($request->hasFile('image')) {
            //     $filename = uniqid() . '.' . $request->image->getClientOriginalExtension();

            //     $request->image->move(public_path('quoteForm'), $filename);

            //     $autoorder->image = 'https://washington.shawntransport.com/quoteForm/' . $filename;
            // }
            if ($request->hasFile('image')) {
                $uploadedImages = $request->file('image');
                $imageUrls = [];

                foreach ($uploadedImages as $image) {
                    $filename = uniqid() . '.' . $image->getClientOriginalExtension();

                    $image->move(public_path('quoteForm'), $filename);

                    $imageUrl = 'https://washington.shawntransport.com/quoteForm/' . $filename;

                    $imageUrls[] = $imageUrl;
                }

                $autoorder->image = implode('*^', $imageUrls);
            }
            // dd($request->toArray());
            if (isset($request->nature_of_customer)) {
                $nature = NatureOfCustomer::where('order_id', $autoorder->id)->first();

                if (!$nature) {
                    $nature = new NatureOfCustomer;
                    $nature->user_id = Auth::user()->id;
                    $nature->order_id = $autoorder->id;
                    $nature->description = $request->nature_of_customer;
                    $cleanedPhoneNumber = trim($request->ophone[0]);

                    // var_dump($cleanedPhoneNumber);

                    if (substr($cleanedPhoneNumber, 0, 3) === '***') {
                        $nature->phone = $cleanedPhoneNumber;
                        // dd('Yes staric', $cleanedPhoneNumber);
                    } else {
                        $nature->phone = $autoorder->ophone;
                        // dd('No staric', $cleanedPhoneNumber);
                    }
                    $nature->save();
                }
            }

            if (!empty($request->company_name)) {
                $autoorder->company_name = $request->company_name;
            }
            if (!empty($request->company_price)) {
                $autoorder->company_price = $request->company_price;
            }
            if (!empty($request->company_comments)) {
                $autoorder->company_comments = $request->company_comments;
            }
            $autoorder->booking_confirm = $request->central_chk;
            $autoorder->need_deposit = $request->needDeposit;
            if (!empty($request->depositAmount)) {
                $autoorder->deposit_amount = $request->depositAmount;
            }
            $autoorder->pay_carrier = $request->pay_carrier;
            $autoorder->listed_price = $request->pay_carrier;
            $autoorder->cod_cop = $request->cod_cop;
            $autoorder->balance = $request->balance;

            $autoorder->payment_method = $request->payment_method;
            $autoorder->balance_method = $request->balance_method;
            $autoorder->balance_time = $request->balance_time;
            $autoorder->terms = $request->terms;
            $autoorder->cod_cop_loc = $request->cod_cop_loc;
            $autoorder->storage_fees = $request->storage_fees;
            if ($request->storage_fees > 0) {
                $autoorder->pay_by = $request->pay_by;
            } else {
                $autoorder->pay_by = NULL;
            }
            $autoorder->other_fees = $request->other_fees;

            // if ($request->balance > 0) {
            //     $autoorder->owes_money = 1;
            //     if($request->cod_cop >= $request->pay_carrier)
            //     {
            //         $autoorder->owes = $request->cod_cop - $request->pay_carrier;
            //     }
            // } else {
            //     $autoorder->owes_money = 0;
            // }

            // if (isset($request->vehicle)) {
            //     if ($request->vehicle != '') {
            //         $autoorder->vehicle = $request->vehicle;
            //     } else {
            //         $autoorder->vehicle = NULL;
            //     }
            // } else {
            //     $autoorder->vehicle = NULL;
            // }

            if ((isset($request->pay_carrier) && $request->pay_carrier > 0) && (empty($request->cod_cop) || $request->cod_cop == 0)) {
                $autoorder->vehicle = 'quick_pay';
                $autoorder->we_us_driver = 2;
                $autoorder->owes = NULL;
                $autoorder->owes_money = 0;
            } else if ((isset($request->pay_carrier) || $request->pay_carrier > 0) && (isset($request->cod_cop) || $request->cod_cop > 0)) {
                if (isset($request->cod_cop_loc)) {
                    if ($request->cod_cop_loc == 'Pickup') {
                        $autoorder->vehicle = 'cop';
                    } else if ($request->cod_cop_loc == 'Delivery') {
                        $autoorder->vehicle = 'cod';
                    } else {
                        $autoorder->vehicle = NULL;
                    }
                }

                if ($request->pay_carrier < $request->cod_cop) {
                    $autoorder->owes = $request->cod_cop - $request->pay_carrier;
                    $autoorder->owes_money = 1;
                    $autoorder->we_us_driver = 1;
                } else if ($request->pay_carrier > $request->cod_cop) {
                    $autoorder->owes = $request->pay_carrier - $request->cod_cop;
                    $autoorder->owes_money = 1;
                    $autoorder->we_us_driver = 2;
                } else {
                    $autoorder->owes = NULL;
                    $autoorder->owes_money = 0;
                    $autoorder->we_us_driver = 0;
                }
            } else {
                $autoorder->vehicle = NULL;
                $autoorder->owes = NULL;
                $autoorder->owes_money = 0;
                $autoorder->we_us_driver = 0;
            }

            if (isset($request->additional_2)) {
                if ($request->additional_2 != '') {
                    $autoorder->additional_2 = $request->additional_2;
                } else {
                    $autoorder->additional_2 = NULL;
                }
            } else {
                $autoorder->additional_2 = NULL;
            }

            if (empty($autoorder->order_taker_id)) {
                $autoorder->order_taker_id = Auth::user()->id;
            }

            //heavy equipment in auto order

            // if (!empty($request->lengthft)) {
            //     $autoorder->length_ft = implode('*^', $request->lengthft);
            // }

            // if (!empty($request->lengthin)) {
            //     $autoorder->length_in = implode('*^', $request->lengthin);
            // }

            // if (!empty($request->widthft)) {
            //     $autoorder->width_ft = implode('*^', $request->widthft);
            // }

            // if (!empty($request->widthin)) {
            //     $autoorder->width_in = implode('*^', $request->widthin);
            // }
            // if (!empty($request->heigthft)) {
            //     $autoorder->height_ft = implode('*^', $request->heigthft);
            // }
            // if (!empty($request->heigthin)) {
            //     $autoorder->height_in = implode('*^', $request->heigthin);
            // }

            // if (!empty($request->weight)) {
            //     $autoorder->weight = implode('*^', $request->weight);
            // }
            // if (!empty($request->load_method)) {
            //     $autoorder->load_method = implode('*^', $request->load_method);
            // }
            // if (!empty($request->unload_method)) {
            //     $autoorder->unload_method = implode('*^', $request->unload_method);
            // }
            // if (!empty($request->category)) {
            //     $autoorder->category = implode('*^', $request->category);
            // }
            // if (!empty($request->subcategory)) {
            //     $autoorder->subcategory = implode('*^', $request->subcategory);
            // }
            // For 'lengthft'
            $autoorder->length_ft = !empty($request->lengthft)
                ? (is_array($request->lengthft) ? implode('*^', $request->lengthft) : $request->lengthft)
                : null;

            // For 'lengthin'
            $autoorder->length_in = !empty($request->lengthin)
                ? (is_array($request->lengthin) ? implode('*^', $request->lengthin) : $request->lengthin)
                : null;

            // For 'widthft'
            $autoorder->width_ft = !empty($request->widthft)
                ? (is_array($request->widthft) ? implode('*^', $request->widthft) : $request->widthft)
                : null;

            // For 'widthin'
            $autoorder->width_in = !empty($request->widthin)
                ? (is_array($request->widthin) ? implode('*^', $request->widthin) : $request->widthin)
                : null;

            // For 'heigthft'
            $autoorder->height_ft = !empty($request->heigthft)
                ? (is_array($request->heigthft) ? implode('*^', $request->heigthft) : $request->heigthft)
                : null;

            // For 'heigthin'
            $autoorder->height_in = !empty($request->heigthin)
                ? (is_array($request->heigthin) ? implode('*^', $request->heigthin) : $request->heigthin)
                : null;

            // For 'weight'
            $autoorder->weight = !empty($request->weight)
                ? (is_array($request->weight) ? implode('*^', $request->weight) : $request->weight)
                : null;

            // For 'load_method'
            $autoorder->load_method = !empty($request->load_method)
                ? (is_array($request->load_method) ? implode('*^', $request->load_method) : $request->load_method)
                : null;

            // For 'unload_method'
            $autoorder->unload_method = !empty($request->unload_method)
                ? (is_array($request->unload_method) ? implode('*^', $request->unload_method) : $request->unload_method)
                : null;

            // For 'category'
            $autoorder->category = !empty($request->category)
                ? (is_array($request->category) ? implode('*^', $request->category) : $request->category)
                : null;

            // For 'subcategory'
            $autoorder->subcategory = !empty($request->subcategory)
                ? (is_array($request->subcategory) ? implode('*^', $request->subcategory) : $request->subcategory)
                : null;

            $autoorder->customer_status = $request->customer_status;
            if (isset($request->dauctionnew)) {
                $autoorder->dauction = $request->dauctionnew;
            }
            if (isset($request->modification)) {
                $autoorder->modification = $request->modification;
            }
            if (isset($request->modify_info)) {
                $autoorder->modify_info = $request->modify_info;
            }
            if (isset($request->available_at_auction)) {
                $autoorder->available_at_auction = $request->available_at_auction;
            }
            if (isset($request->rv_type)) {
                $autoorder->rv_type = $request->rv_type;
            }
            if (isset($request->boat_on_trailer)) {
                $autoorder->boat_on_trailer = $request->boat_on_trailer;
            } else {
                $autoorder->boat_on_trailer = 0;
            }
            if (isset($request->link)) {
                $autoorder->link = $request->link;
            }
            // if(isset($request->protect_from_freezing))
            // {
            //     $autoorder->protect_from_freezing = $request->protect_from_freezing;
            // }
            // if(isset($request->sort_segregate))
            // {
            //     $autoorder->sort_segregate = $request->sort_segregate;
            // }
            // if(isset($request->blind_shipment))
            // {
            //     $autoorder->blind_shipment = $request->blind_shipment;
            // }
            // if(isset($request->stackable))
            // {
            //     $autoorder->stackable = $request->stackable;
            // }
            // if(isset($request->hazardous))
            // {
            //     $autoorder->hazardous = $request->hazardous;
            // }
            // if(isset($request->handling_unit))
            // {
            //     $autoorder->handling_unit = $request->handling_unit;
            // }


            $this->createOrderHistory($autoorder->id, 'oterminal', 'Origin Terminal', $autoorder->oterminal);
            $this->createOrderHistory($autoorder->id, 'oauction', 'Origin Auction', $autoorder->oacution);
            $this->createOrderHistory($autoorder->id, 'oauctionpho', 'Origin Auction Phone', $autoorder->oauctionpho);
            $this->createOrderHistory($autoorder->id, 'obuyer_no', 'Origin Buyer No', $autoorder->obuyer_no);
            $this->createOrderHistory($autoorder->id, 'gate_pass_pin', 'Origin Buyer gate_pass_pin', $autoorder->obuyer_lot_no);
            $this->createOrderHistory($autoorder->id, 'obuyer_lot_no', 'Origin Buyer obuyer_lot_no', $autoorder->obuyer_lot_no);
            $this->createOrderHistory($autoorder->id, 'obuyer_stock_no', 'Origin Buyer obuyer_stock_no', $autoorder->obuyer_stock_no);
            $this->createOrderHistory($autoorder->id, 'oname', 'Origin Name', $autoorder->oname);
            $this->createOrderHistory($autoorder->id, 'oemail', 'Origin Email', $autoorder->oemail);
            $this->createOrderHistory($autoorder->id, 'oaddress', 'Origin Address', $autoorder->oaddress);
            $this->createOrderHistory($autoorder->id, 'oaddress2', 'Origin Address 2', $autoorder->oaddress2);
            $this->createOrderHistory($autoorder->id, 'ophone', 'Origin Phone', $autoorder->ophone);

            $this->createOrderHistory($autoorder->id, 'originzip', 'Origin Zip', $autoorder->originzip);
            $this->createOrderHistory($autoorder->id, 'originstate', 'Origin State', $autoorder->originstate);
            $this->createOrderHistory($autoorder->id, 'origincity', 'Origin City', $autoorder->origincity);
            $this->createOrderHistory($autoorder->id, 'originzsc', 'Origin Zip State City', $autoorder->originzsc);
            $this->createOrderHistory($autoorder->id, 'dterminal', 'Destination Terminal', $autoorder->dterminal);
            $this->createOrderHistory($autoorder->id, 'port_line', 'Sallum/Girmadi Lines', $autoorder->port_line);
            $this->createOrderHistory($autoorder->id, 'port_dock_type', 'Dock Recieve Type', $autoorder->port_dock_type);
            $this->createOrderHistory($autoorder->id, 'reason_box', 'Port Reason Boc', $autoorder->reason_box);
            $this->createOrderHistory($autoorder->id, 'dname', 'Destination Name', $autoorder->dname);

            $this->createOrderHistory($autoorder->id, 'dauction', 'Destination Auction', $autoorder->dauction);
            $this->createOrderHistory($autoorder->id, 'dauctionpho', 'Destination Auction Phone', $autoorder->dauctionpho);
            $this->createOrderHistory($autoorder->id, 'portterminal', 'Port Terminal', $autoorder->portterminal);
            $this->createOrderHistory($autoorder->id, 'demail', 'Destination Email', $autoorder->demail);
            $this->createOrderHistory($autoorder->id, 'vehicle', 'Vehicle', $autoorder->vehicle);
            $this->createOrderHistory($autoorder->id, 'dphone', 'Destination Phone', $autoorder->dphone);

            $this->createOrderHistory($autoorder->id, 'daddress', 'Destination Address', $autoorder->daddress);
            $this->createOrderHistory($autoorder->id, 'daddress2', 'Destination Address 2', $autoorder->daddress2);
            $this->createOrderHistory($autoorder->id, 'destinationzip', 'Destination Zip', $autoorder->destinationzip);
            $this->createOrderHistory($autoorder->id, 'destinationstate', 'Destination State', $autoorder->destinationstate);
            $this->createOrderHistory($autoorder->id, 'destinationcity', 'Destination City', $autoorder->destinationcity);

            $this->createOrderHistory($autoorder->id, 'destinationzsc', 'Destination Zip State City', $autoorder->destinationzsc);
            $this->createOrderHistory($autoorder->id, 'vehicle_opt', 'Vehicle Option', $autoorder->vehicle_opt);
            $this->createOrderHistory($autoorder->id, 'vin_num', 'Vin Nummber', $autoorder->vin_num);
            $this->createOrderHistory($autoorder->id, 'year', 'Vehicle  Year', $autoorder->year);
            $this->createOrderHistory($autoorder->id, 'make', 'Vehicle Make', $autoorder->make);

            $this->createOrderHistory($autoorder->id, 'model', 'Vehicle Model', $autoorder->model);
            $this->createOrderHistory($autoorder->id, 'ymk', 'Vehicle Year Make Model', $autoorder->ymk);
            $this->createOrderHistory($autoorder->id, 'type', 'Vehicle Type', $autoorder->type);
            $this->createOrderHistory($autoorder->id, 'condition', 'Vehicle Condition', $autoorder->condition);

            $this->createOrderHistory($autoorder->id, 'transport', 'Transport', $autoorder->transport);
            $this->createOrderHistory($autoorder->id, 'port_title', 'Port Title', $autoorder->port_title);
            $this->createOrderHistory($autoorder->id, 'add_info', 'Additional Information', $autoorder->add_info);
            $this->createOrderHistory($autoorder->id, 'pickup_date', 'Vehicle Pickup Date', $autoorder->pickup_date);
            $this->createOrderHistory($autoorder->id, 'delivery_date', 'Vehicle Delivery Date', $autoorder->delivery_date);

            $this->createOrderHistory($autoorder->id, 'pickup_when', 'Vehicle Pikup When', $autoorder->pickup_when);
            $this->createOrderHistory($autoorder->id, 'delivery_when', 'Vehicle Delivery When', $autoorder->delivery_when);
            $this->createOrderHistory($autoorder->id, 'payment', 'Payment', $autoorder->payment);
            $this->createOrderHistory($autoorder->id, 'driver_price', 'DRIVER PRICE', $autoorder->driver_price);
            $this->createOrderHistory($autoorder->id, 'company_name', 'Company Name', $autoorder->company_name);
            $this->createOrderHistory($autoorder->id, 'company_price', 'Company Price', $autoorder->company_price);

            $this->createOrderHistory($autoorder->id, 'company_comments', 'Company Comments', $autoorder->company_comments);
            $this->createOrderHistory($autoorder->id, 'booking_confirm', 'Booking Confirm', $autoorder->booking_confirm);
            $this->createOrderHistory($autoorder->id, 'need_deposit', 'Need Deposit', $autoorder->need_deposit);
            $this->createOrderHistory($autoorder->id, 'deposit_amount', 'Deposit Amount', $autoorder->deposit_amount);
            $this->createOrderHistory($autoorder->id, 'pay_carrier', 'Pay To Carrier', $autoorder->pay_carrier);

            $this->createOrderHistory($autoorder->id, 'cod_cop', 'Cash On Delivery', $autoorder->cod_cop);
            $this->createOrderHistory($autoorder->id, 'balance', 'Balance', $autoorder->balance);
            $this->createOrderHistory($autoorder->id, 'owes_money', 'Owes Money', $autoorder->owes_money);
            $this->createOrderHistory($autoorder->id, 'payment_method', 'Payment Method', $autoorder->payment_method);
            $this->createOrderHistory($autoorder->id, 'balance_method', 'Balance Method', $autoorder->balance_method);

            $this->createOrderHistory($autoorder->id, 'balance_time', 'Balance Time', $autoorder->balance_time);
            $this->createOrderHistory($autoorder->id, 'terms', 'Terms', $autoorder->terms);
            $this->createOrderHistory($autoorder->id, 'cod_cop_loc', 'Cash on Delevery', $autoorder->cod_cop_loc);
            $this->createOrderHistory($autoorder->id, 'additional_2', 'Additional Information 2', $autoorder->additional_2);

            // $this->createOrderHistory($autoorder->id, 'length_ft', 'Lenght in Feet', $autoorder->length_ft);
            // $this->createOrderHistory($autoorder->id, 'length_in', 'Length Inche', $autoorder->length_in);
            // $this->createOrderHistory($autoorder->id, 'width_ft', 'Width in Feet', $autoorder->width_ft);
            // $this->createOrderHistory($autoorder->id, 'width_in', 'Width Inche', $autoorder->width_in);
            // $this->createOrderHistory($autoorder->id, 'height_ft', 'Height in Feet', $autoorder->height_ft);

            // $this->createOrderHistory($autoorder->id, 'height_in', 'Height in Inche', $autoorder->height_in);
            // $this->createOrderHistory($autoorder->id, 'weight', 'Vehicle Weight', $autoorder->weight);
            // $this->createOrderHistory($autoorder->id, 'load_method', 'Load Method', $autoorder->load_method);
            // $this->createOrderHistory($autoorder->id, 'unload_method', 'Unload Method', $autoorder->unload_method);

            $autoorder->save();

            if ($autoorder->car_type == 3) { //for frieght
                $data5 = order_freight::where('order_id', $autoorder->id);
                if (!$data5->exists()) {
                    $data5 = new order_freight;
                } else {
                    $data5 = $data5->first();
                }
                $data5->order_id = $autoorder->id;
                $data5->frieght_class = isset($request->frieght_class) ? $request->frieght_class : null;
                $data5->equipment_type = isset($request->equipment_type) ? implode('^*', $request->equipment_type) : null;
                $data5->trailer_specification = isset($request->trailer_specification) ? implode('^*', $request->trailer_specification) : null;
                $data5->ex_pickup_date = isset($request->ex_pickup_date) ? $request->ex_pickup_date : null;
                $data5->ex_pickup_time = isset($request->ex_pickup_time) ? $request->ex_pickup_time : null;
                $data5->ex_delivery_date = isset($request->ex_delivery_date) ? $request->ex_delivery_date : null;
                $data5->ex_delivery_time = isset($request->ex_delivery_time) ? $request->ex_delivery_time : null;
                $data5->commodity_detail = isset($request->commodity_detail) ? $request->commodity_detail : null;
                $data5->commodity_unit = isset($request->commodity_unit) ? $request->commodity_unit : null;
                $data5->total_weight_lbs = isset($request->total_weight_lbs) ? $request->total_weight_lbs : null;
                $data5->pick_up_services = isset($request->pick_up_services) ? implode('^*', $request->pick_up_services) : null;
                $data5->deliver_services = isset($request->deliver_services) ? implode('^*', $request->deliver_services) : null;
                $data5->shipment_prefences = isset($request->shipment_prefences) ? $request->shipment_prefences : null;
                $data5->protect_from_freezing = isset($request->protect_from_freezing) ? $request->protect_from_freezing : 0;
                $data5->sort_segregate = isset($request->sort_segregate) ? $request->sort_segregate : 0;
                $data5->blind_shipment = isset($request->blind_shipment) ? $request->blind_shipment : 0;
                $data5->stackable = isset($request->stackable) ? $request->stackable : 0;
                $data5->hazardous = isset($request->hazardous) ? $request->hazardous : 0;
                $data5->handling_unit = isset($request->handling_unit) ? $request->handling_unit : null;
                $data5->save();
            }
            $checkReport = report::where('pstatus', 7)->orWhere('pstatus', 18)->orWhere('pstatus', 8)->where('orderId', $autoorder->id)->first();
            if (isset($request->pstatus2)) {
                if ($request->pstatus2 == 7 || $request->pstatus2 == 18 || $request->pstatus2 == 8) {
                    if (empty($checkReport)) {
                        if (Auth::user()->assign_daily_qoute > 0) {
                            $daily = DailyQoute::where('user_id', Auth::user()->id)->whereDate('date', date('Y-m-d'))->first();
                            $daily->total_qoute = $daily->total_qoute + 1;
                            $daily->save();
                        }
                    }
                    if (empty($autoorder->u_id)) {
                        $autoorder->u_id = Auth::user()->id;
                        $autoorder->save();
                    }
                }
            } else if (isset($request->pstatus)) {
                if ($request->pstatus == 7 || $request->pstatus == 18 || $request->pstatus == 8) {

                    if ($request->pstatus == 7) {
                        if ($autoorder->booking_mail < 1) {
                            $autoorder->booking_mail = $autoorder->booking_mail + 1;
                            // Mail::to(['info@hellotransport.com', $autoorder->oemail])->send(new BookingConfirmationMail($autoorder));
                            $recipients = ['info@hellotransport.com'];

                            if (filter_var($autoorder->oemail, FILTER_VALIDATE_EMAIL)) {
                                $recipients[] = $autoorder->oemail;
                            }

                            Mail::to($recipients)->send(new BookingConfirmationMail($autoorder));
                        }
                    }
                    if (empty($checkReport)) {
                        if (Auth::user()->assign_daily_qoute > 0) {
                            $daily = DailyQoute::where('user_id', Auth::user()->id)->whereDate('date', date('Y-m-d'))->first();
                            $daily->total_qoute = $daily->total_qoute + 1;
                            $daily->save();
                        }
                    }
                    if (empty($autoorder->u_id)) {
                        $autoorder->u_id = Auth::user()->id;
                        $autoorder->save();
                    }
                }
            }

            $reportstatus = report::where('orderId', $autoorder->id)->where('pstatus', '=', $autoorder->pstatus)->first();
            if (!isset($reportstatus->id)) {
                $reportstatus = new report();
                $reportstatus->orderId = $autoorder->id;
                $reportstatus->pstatus = $autoorder->pstatus;
                $reportstatus->created_at = date('Y-m-d h:i:s');
            }
            $reportstatus->userId = Auth::user()->id;
            $reportstatus->updated_at = date('Y-m-d h:i:s');
            $reportstatus->save();

            // $reportstatus = report::where('orderId', '=', $request->orderid)->where('pstatus', '=', $autoorderstatus->pstatus)->first();
            // if (empty($reportstatus)) {
            if (\Request::segment(1) == 'store_new_quote') {
                if (isset($request->coupon_number)) {
                    $coupon = Coupon::where('coupon_number', $request->coupon_number)->where('status', 0)->first();
                    if (isset($coupon->id)) {
                        $coupon->status = 1;
                        $coupon->save();
                        if (isset($coupon->id)) {
                            $autoorder->coupon_id = $coupon->id;
                            $autoorder->save();
                        }
                    }
                }

                $historyyyy = '';
                if (isset($request->order_history)) {
                    $historyyyy = "<h6>Remarks: " . $request->order_history . "</h6>";
                }
                $oldhistory = call_history::where('orderId', $autoorder->id)->orderBy('created_at', 'DESC')->first();
                $lstatus = "NEW";
                if (isset($oldhistory->pstatus)) {
                    $lstatus = $this->get_pstatuss($oldhistory->pstatus);
                }

                $callhistory = new call_history();
                $callhistory->userId = Auth::user()->id;
                $callhistory->orderId = $autoorder->id;
                $callhistory->pstatus = $autoorder->pstatus;
                $callhistory->history = "<h6>LAST STATUS : " . $lstatus . "</h6>" . $historyyyy;
                $callhistory->created_at = now();
                $callhistory->updated_at = now();
                $callhistory->save();

                $autoorder->updated_at = now();
                $autoorder->save();
            }
            // }
            if (isset($request->expected_date)) {
                $expected_date = $request->expected_date;
            } else {
                $expected_date = date("Y-m-d");
            }
            $this->expected_date($autoorder->id, Auth::user()->id, $autoorder->pstatus, $expected_date);
            if (isset($request->pay_cond)) {
                $sendemail = 'yes';
                $payment = orderpayment::where('orderId', '=', $request->orderid)->first();
                $payment->paycondition = $request->pay_cond;
                $payment->payconfirm = $request->pay_confirm;
                if (isset($request->confirm1)) {
                    $payment->sendemail = $request->confirm1;
                    if ($request->confirm1 == 0) {
                        $sendemail = 'no';
                    }
                } else {
                    $payment->sendemail = null;
                }

                $payment->save();
                //update pstatus
                if (isset($request->pstatus2)) {
                    if ($request->pstatus2 == 7 || $request->pstatus2 == 8) {
                        $autoorder->date_of_booked = date('Y-m-d');
                        $autoorder->save();
                    }
                }
                if (isset($request->pstatus)) {
                    if ($request->pstatus == 7 || $request->pstatus == 8) {
                        $autoorder->date_of_booked = date('Y-m-d');
                        $autoorder->save();

                        if ($autoorder->booking_mail < 1) {
                            $autoorder->booking_mail = $autoorder->booking_mail + 1;
                            // Mail::to(['info@hellotransport.com', $autoorder->oemail])->send(new BookingConfirmationMail($autoorder));
                            $recipients = ['info@hellotransport.com'];

                            if (filter_var($autoorder->oemail, FILTER_VALIDATE_EMAIL)) {
                                $recipients[] = $autoorder->oemail;
                            }

                            Mail::to($recipients)->send(new BookingConfirmationMail($autoorder));
                        }
                    }
                }

                if ($request->pay_cond == 4 || $sendemail == 'no') {
                    $autoorderstatus = AutoOrder::find($request->orderid);
                    if ($request->pstatus) {
                        $autoorderstatus->pstatus = isset($request->pstatus2) ? $request->pstatus2 : $request->pstatus;  ///payment missing //or payment approval
                    }
                    $autoorderstatus->save();

                    $reportstatus = report::where('orderId', $autoorder->id)->where('pstatus', '=', $autoorder->pstatus)->first();
                    if (!isset($reportstatus->id)) {
                        $reportstatus = new report();
                        $reportstatus->orderId = $autoorder->id;
                        $reportstatus->pstatus = $autoorder->pstatus;
                        $reportstatus->created_at = date('Y-m-d h:i:s');
                    }
                    $reportstatus->userId = Auth::user()->id;
                    $reportstatus->updated_at = date('Y-m-d h:i:s');
                    $reportstatus->save();
                    $singlereport = singlereport::where('orderId', '=', $request->orderid)->first();
                    if (empty($singlereport)) {
                        $singlereport = new singlereport();
                        $singlereport->userId = Auth::user()->id;
                        $singlereport->orderId = $autoorder->id;
                        $singlereport->pstatus = $autoorder->pstatus; ///payment missing //or payment approval
                        $singlereport->save();
                    }
                } elseif ($request->pay_cond != 4) {
                    //$autoorderstatus = AutoOrder::find($request->orderid);
                    //$autoorderstatus->pstatus = 0;  ///payment missing
                    //$autoorderstatus->save();
                    //                    $reportstatus = report::where('orderId', '=', $request->orderid)->first();
                    //                    $reportstatus->pstatus = 0; ///payment missing
                    //                    $reportstatus->save();
                    //                    $singlereport = singlereport::where('orderId', '=', $request->orderid)->first();
                    //                    $singlereport->pstatus = 0; ///payment missing
                    //                    $singlereport->save();
                }

                ///send email
                /// on condition 1 or 2
                if ($request->pay_cond != 3 && $request->pay_cond != 4 && $sendemail == 'yes') {
                    if (isset($request->neworderpay_btn)) {
                        if ($request->neworderpay_btn == "1") {
                            $orderid = base64_encode($request->orderid);
                            $userid = base64_encode(Auth::user()->id);
                            $link1 = url("/email_order/{$orderid}/{$userid}");
                            // Mail::to(config('custom.SEND_MAIL'))->send(new SendOrderMail($test));
                            Mail::to($request->oemail2)->send(new SendOrderMail($link1));
                        }
                    }
                }
            }


            Session::flash('flash_message', 'Data Successfully Saved');
            $data['success'] = 'SUCCESS';
            $data['orderid'] = $request->orderid;
            $data['miles'] = $newMiles;
            //return "SUCCESS";
            //json_encode($data);

            // try {
            //     $client = new Client();
            //     $response = $client->post('https://daydispatch.com/api/New-Listing', [
            //         'json' => $autoorder,
            //     ]);

            //     dd($response);

            //     return response()->json($data, 200);
            // } catch (\Exception $e) {
            //     dd($e->getMessage());
            //     // \Log::error($e->getMessage());

            //     return back()->with('error', 'An error occurred while creating the quote. Please try again later.');
            // }

            return response()->json($data, 200);
            //end faisal pasha
            //return view('main.phone_quote.new_quote.index', compact('data'));
        } else {
            return redirect('/loginn/');
        }
    }

    public function previous_orders(Request $request)
    {
        $ocity = str_replace(' ', '', base64_decode($request->ocity));
        $dcity = str_replace(' ', '', base64_decode($request->dcity));
        $ocity2 = explode(',', $ocity);
        $dcity2 = explode(',', $dcity);


        $ocitynew = '';
        if (isset($ocity2[1])) {
            $ocitynew = $ocity2[1];
        }
        $dcitynew = '';
        if (isset($dcity2[1])) {
            $dcitynew = $dcity2[1];
        }
        $results = AutoOrder::where('originstate', 'LIKE', '%' . $ocitynew . '%')
            ->where('destinationstate', 'LIKE', '%' . $dcitynew . '%')
            ->limit(5)->orderBy('created_at', 'DESC')->get();
        return view('main.phone_quote.new.previous_orders', compact('results'));
    }

    public function previous_orders2(Request $request)
    {
        $ocity = base64_decode($request->ocity);
        $dcity = base64_decode($request->dcity);
        $ocity2 = explode(',', $ocity);
        $dcity2 = explode(',', $dcity);

        $ocitynew = '';
        if (isset($ocity2[0])) {
            $ocitynew = $ocity2[0];
        }
        $dcitynew = '';
        if (isset($dcity2[0])) {
            $dcitynew = $dcity2[0];
        }
        $data = PriceChecker::with(['priceChecker', 'orderTaker'])->where('origin', 'LIKE', '%' . $ocitynew . '%')
            ->where('destination', 'LIKE', '%' . $dcitynew . '%')->limit(10)->orderBy('created_at', 'DESC')->get();

        return view('main.phone_quote.new.previous_orders2', compact('data'));
    }

    public function rates_shipa1(Request $request)
    {
        $autoorder = AutoOrder::find($request->id);
        $ocity = str_replace(' ', '', base64_decode($request->ocity));
        $dcity = str_replace(' ', '', base64_decode($request->dcity));
        $ocity2 = explode(',', $ocity);
        $dcity2 = explode(',', $dcity);
        $uid = 14;

        $dzip = !empty($autoorder->destinationzip) ? $autoorder->destinationzip : '';
        $dstate = !empty($autoorder->destinationstate) ? $autoorder->destinationstate : '';
        $carrier = $autoorder->transport[0];
        $distance = 0;
        $Price = 0;

        //        if ($autoorder->pstatus == 0 && empty($autoorder->payment)) {
        if (!empty($autoorder->origincity) && !empty($autoorder->originstate) && !empty($autoorder->originzip) && !empty($autoorder->destinationcity) && !empty($autoorder->destinationstate) && !empty($autoorder->destinationzip)) {
            $selectorigin = zipcodes::where('city', $autoorder->origincity)->where('state', $autoorder->originstate)->where('zipcode', $autoorder->originzip)->first();

            // echo "<pre>";
            // print_r($selectorigin);
            // exit();

            $selectDest = zipcodes::where('city', $autoorder->destinationcity)->where('state', $autoorder->destinationstate)->where('zipcode', $autoorder->destinationzip)->first();

            $selectoriginLat = '';
            $selectoriginLong = '';
            $selectDestLat = '';
            $selectDestLong = '';
            if ($selectorigin) {
                $selectoriginLat = $selectorigin->latitude;
                $selectoriginLong = $selectorigin->longitude;
                if ($selectDest) {
                    $selectDestLat = $selectDest->latitude;
                    $selectDestLong = $selectDest->longitude;
                }
            }
            $distance = '';
            // $mydata = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?origins=' . $selectoriginLat . ',' . $selectoriginLong . '&destinations=' . $selectDestLat . ',' . $selectDestLong . '&key=AIzaSyAsvXyIoaweH4Q6LKe9De1seJtUYfjdFvs');
            $mydata1 = json_decode($mydata);

            if ($mydata1->status == 'OK') {
                $distance = ceil(($mydata1->rows['0']->elements['0']->distance->value) / 1000) * 0.621371;
            }
            $distance = ceil($distance);

            if (!empty($distance)) {
                $selectpermprice = WPPricePerm::where('maxvalue', '>=', round($distance))->where('minvalue', '<=', round($distance))->where('userId', $uid)->first();
                //                    $selectdomain = UserOld::where('id', $uid)->first();
                //                    if ($selectdomain->domain_name != '') {
                //                        $domainname = $selectdomain->domain_name;
                //                    } else {
                //                        $domainname = 'autoquote.com';
                //                    }
                //                    $replytoemail = $selectdomain->replytoemail;

                // mileage cost
                $basepricehi = $distance * $selectpermprice->mivalue;
                $basepricenonhi = $distance * $selectpermprice->mavalue;
                $basepricehi1 = $basepricehi;
                $basepricenonhi1 = $basepricenonhi;
                $dposite = 0;

                //for vehicle1
                $make1 = $request->vmake[0];

                $model1 = $request->vmodel[0];
                $running1 = $request->condition[0];

                //quote for vehicle1
                if (!empty($make1) && ($make1 != '') && !empty($model1) && ($model1 != '')) {
                    // value from vehicle size with calculation
                    $selectvehicle = WPVehicleListing::where('make', $make1)->where('model', $model1)->where('UserId', $uid)->first();
                    if ($selectvehicle != '') {
                        $selectvehiclepriceval = VehicleExtra::where('vehicletype', $selectvehicle->size)->where('UserId', $uid)->first();
                        if ($selectvehiclepriceval != '') {
                            if ($selectvehiclepriceval->pervalue) {
                                $baseprice11 = ($basepricehi1 * $selectvehiclepriceval->pervalue) / 100; //highway
                                $basepricehi1 = $basepricehi1 + $baseprice11;
                                $basepricen1 = ($basepricenonhi1 * $selectvehiclepriceval->pervalue) / 100; //nonhighway
                                $basepricenonhi1 = $basepricenonhi1 + $basepricen1;
                            } else {
                                if ($selectvehiclepriceval->fixvalue < 0) {
                                    $basepricehi1 = $basepricehi1 - abs($selectvehiclepriceval->fixvalue);
                                    $basepricenonhi1 = $basepricenonhi1 - abs($selectvehiclepriceval->fixvalue);
                                } else {
                                    $basepricehi1 = $basepricehi1 + abs($selectvehiclepriceval->fixvalue);
                                    $basepricenonhi1 = $basepricenonhi1 + abs($selectvehiclepriceval->fixvalue);
                                }
                            }
                        }
                    } else {
                        $basepricehi1 = $basepricehi1;
                        $basepricenonhi1 = $basepricenonhi1;
                    }

                    if ($running1 == 1) {
                        $VehicleRunning1 = '1';
                        $VehicleRunning11 = 'Yes';
                        $selectinopprice = WPGeneralException::where('UserId', $uid)->first();
                        $dposite = 0;
                        if ($selectinopprice->isDeposit == 'Yes') {
                            $dposite = $selectinopprice->isDeposit;
                        }
                    } else {
                        // disable vehicle price
                        $selectinopprice = WPGeneralException::where('UserId', $uid)->first();
                        $selectinoppriceval = $selectinopprice->dvprice;
                        $dposite = 0;
                        if ($selectinopprice->isDeposit == 'Yes') {
                            $dposite = $selectinopprice->Deposit;
                        }
                        $basepricenonhi1 = $basepricenonhi1 + $selectinoppriceval;
                        $basepricehi1 = $basepricehi1 + $selectinoppriceval;
                        $VehicleRunning1 = '0';
                        $VehicleRunning11 = 'No';
                    }
                    //echo $basepricehi1.'<br>'.$basepricenonhi1.'<br>';

                    $VehicleMake1 = $make1;
                    $VehicleModel1 = $model1;
                    $dquota = 0;

                    // value from zip and state exception with calculation
                    $zipQuery = Rules::where('Origin', $autoorder->originzip)->where('isZip', 1)->where('UserId', $uid);
                    $zipQuery->where(function ($zipQuery) use ($dzip) {
                        $zipQuery->orWhere('Dest', $dzip);
                        $zipQuery->orWhere('Dest', 'any');
                        $zipQuery->orWhere('Dest', 'origin');
                        $zipQuery->orWhere('Dest', '');
                    });
                    $zipexception1 = $zipQuery->get();

                    if ($zipexception1 != '') {
                        foreach ($zipexception1 as $zipexceptionrec1) {
                            if ($zipexceptionrec1->Quota != "Don't Quota" && ($zipexceptionrec1->Type == "origin" || $zipexceptionrec1->Type == "any" || $zipexceptionrec1->Type == "route")) {
                                if ($zipexceptionrec1->addorsub == "add") {
                                    $basepricehi1 = $basepricehi1 + $zipexceptionrec1->Quota;
                                    $basepricenonhi1 = $basepricenonhi1 + $zipexceptionrec1->Quota;
                                }
                                if ($zipexceptionrec1->addorsub == "sub") {
                                    $basepricehi1 = $basepricehi1 - $zipexceptionrec1->Quota;
                                    $basepricenonhi1 = $basepricenonhi1 - $zipexceptionrec1->Quota;
                                }
                                if (!empty($zipexceptionrec1->Quota_in_per) && $zipexceptionrec1->addorsub == "add" && $zipexceptionrec1->Quota == '') {
                                    $basepricehi1 += (($zipexceptionrec1->Quota_in_per * $basepricehi1) / 100);
                                    $basepricenonhi1 += (($zipexceptionrec1->Quota_in_per * $basepricenonhi1) / 100);
                                }
                                if (!empty($zipexceptionrec1->Quota_in_per) && $zipexceptionrec1->addorsub == "sub" && $zipexceptionrec1->Quota == '') {
                                    $basepricehi1 -= (($zipexceptionrec1->Quota_in_per * $basepricehi1) / 100);
                                    $basepricenonhi1 -= (($zipexceptionrec1->Quota_in_per * $basepricenonhi1) / 100);
                                }
                                if ($zipexceptionrec1->addorsub == "") {
                                    $basepricehi1 = $basepricehi1 + ($zipexceptionrec1->Quota);
                                    $basepricenonhi1 = $basepricenonhi1 + ($zipexceptionrec1->Quota);
                                }
                            }
                        }
                    }

                    $stateQuery = Rules::where('Origin', $autoorder->originstate)->where('isZip', 0)->where('UserId', $uid);
                    $stateQuery->where(function ($stateQuery) use ($dstate) {
                        $stateQuery->orWhere('Dest', $dstate);
                        $stateQuery->orWhere('Dest', 'any');
                        $stateQuery->orWhere('Dest', 'origin');
                        $stateQuery->orWhere('Dest', '');
                    });
                    $stateexception1 = $stateQuery->get();

                    if ($stateexception1 != '') {
                        foreach ($stateexception1 as $stateexceptionrec1) {
                            if ($stateexceptionrec1->Quota != "Don't Quota" && ($stateexceptionrec1->Type == "origin" || $stateexceptionrec1->Type == "any" || $stateexceptionrec1->Type == "route")) {
                                if ($stateexceptionrec1->addorsub == "add") {
                                    $basepricehi1 += $stateexceptionrec1->Quota;
                                    $basepricenonhi1 += $stateexceptionrec1->Quota;
                                }
                                if ($stateexceptionrec1->addorsub == "sub") {
                                    $basepricehi1 -= $stateexceptionrec1->Quota;
                                    $basepricenonhi1 -= $stateexceptionrec1->Quota;
                                }
                                if (!empty($stateexceptionrec1->Quota_in_per) && $stateexceptionrec1->addorsub == "add" && $stateexceptionrec1->Quota == '') {
                                    $basepricehi1 += (($stateexceptionrec1->Quota_in_per * $basepricehi1) / 100);
                                    $basepricenonhi1 += (($stateexceptionrec1->Quota_in_per * $basepricenonhi1) / 100);
                                }
                                if (!empty($stateexceptionrec1->Quota_in_per) && $stateexceptionrec1->addorsub == "sub" && $stateexceptionrec1->Quota == '') {
                                    $basepricehi1 -= (($stateexceptionrec1->Quota_in_per * $basepricehi1) / 100);
                                    $basepricenonhi1 -= (($stateexceptionrec1->Quota_in_per * $basepricenonhi1) / 100);
                                }
                                if ($stateexceptionrec1->addorsub == "") {
                                    $basepricehi1 += $stateexceptionrec1->Quota;
                                    $basepricenonhi1 += $stateexceptionrec1->Quota;
                                }
                            }
                        }
                    }

                    $zipExQuery = Rules::where('Dest', $dzip)->where('isZip', 1)->where('UserId', $uid);
                    $zipExQuery->where(function ($zipExQuery) use ($dzip) {
                        $zipExQuery->orWhere('Dest', 'any');
                        $zipExQuery->orWhere('Dest', '');
                    });
                    $zipexception = $zipExQuery->get();

                    if ($zipexception != '') {
                        foreach ($zipexception as $zipexceptionrec) {
                            if (($zipexceptionrec->Type == "destination" || $zipexceptionrec->Type == "any") && $zipexceptionrec->Quota != "Don't Quota" || $zipexceptionrec->Type == "route") {
                                if ($zipexceptionrec->addorsub == "add") {
                                    $basepricehi1 += $zipexceptionrec->Quota;
                                    $basepricenonhi1 += $zipexceptionrec->Quota;
                                }
                                if ($zipexceptionrec->addorsub == "sub") {
                                    $basepricehi1 -= $zipexceptionrec->Quota;
                                    $basepricenonhi1 -= $zipexceptionrec->Quota;
                                }
                                if (!empty($zipexceptionrec->Quota_in_per) && $zipexceptionrec->addorsub == "add" && $zipexceptionrec->Quota == '') {
                                    $basepricehi1 += (($zipexceptionrec->Quota_in_per * $basepricehi1) / 100);
                                    $basepricenonhi1 += (($zipexceptionrec->Quota_in_per * $basepricenonhi1) / 100);
                                }
                                if (!empty($zipexceptionrec->Quota_in_per) && $zipexceptionrec->addorsub == "sub" && $zipexceptionrec->Quota == '') {
                                    $basepricehi1 -= (($zipexceptionrec->Quota_in_per * $basepricehi1) / 100);
                                    $basepricenonhi1 -= (($zipexceptionrec->Quota_in_per * $basepricenonhi1) / 100);
                                }
                                if ($zipexceptionrec->addorsub == "") {
                                    $basepricehi1 += $zipexceptionrec->Quota;
                                    $basepricenonhi1 += $zipexceptionrec->Quota;
                                }
                            } else {
                                $basepricehi1 = 0;
                                $dquota = 1;
                                $basepricenonhi1 = 0;
                            }
                        }
                    }

                    $stateExQuery = Rules::where('Dest', $dstate)->where('isZip', 0)->where('UserId', $uid);
                    $stateExQuery->where(function ($stateExQuery) use ($dstate) {
                        $stateExQuery->orWhere('Dest', 'any');
                        $stateExQuery->orWhere('Dest', 'origin');
                    });
                    $stateexception = $stateExQuery->get();

                    if ($stateexception != '') {
                        foreach ($stateexception as $stateexceptionrec) {
                            if ($stateexceptionrec->Type == "destination" || $stateexceptionrec->type == "any") {
                                if ($stateexceptionrec->addorsub == "add" && $stateexceptionrec->Quota != "Don't Quota") {
                                    $basepricehi1 += $stateexceptionrec->Quota;
                                    $basepricenonhi1 += $stateexceptionrec->Quota;
                                }
                                if ($stateexceptionrec->addorsub == "sub" && $stateexceptionrec->Quota != "Don't Quota") {
                                    $basepricehi1 -= $stateexceptionrec->Quota;
                                    $basepricenonhi1 -= $stateexceptionrec->Quota;
                                }
                                if (!empty($stateexceptionrec->Quota_in_per) && $stateexceptionrec->addorsub == "add" && $stateexceptionrec->Quota == '') {
                                    $basepricehi1 += (($stateexceptionrec->Quota_in_per * $basepricehi1) / 100);
                                    $basepricenonhi1 += (($stateexceptionrec->Quota_in_per * $basepricenonhi1) / 100);
                                }
                                if (!empty($stateexceptionrec->Quota_in_per) && $stateexceptionrec->addorsub == "sub" && $stateexceptionrec->Quota == '') {
                                    $basepricehi1 -= (($stateexceptionrec->Quota_in_per * $basepricehi1) / 100);
                                    $basepricenonhi1 -= (($stateexceptionrec->Quota_in_per * $basepricenonhi1) / 100);
                                }
                                if ($stateexceptionrec->addorsub == "" && $stateexceptionrec->Quota != "Don't Quota") {
                                    $basepricehi1 += $stateexceptionrec->Quota;
                                    $basepricenonhi1 += $stateexceptionrec->Quota;
                                }
                            }
                        }
                    }

                    // value for enclosed vehicle
                    if ($carrier == '2') {
                        $selectencprice = WPGeneralException::where('UserId', $uid)->first();
                        $selectencpriceval = $selectencprice->etransport;
                        $basepricehi1 = $basepricehi1 * $selectencpriceval;
                        $basepricenonhi1 = $basepricenonhi1 * $selectencpriceval;
                    }
                    $Price = ceil($basepricehi1);
                    $Price = $Price;
                    if ($Price != 0 && $Price > 0) {
                        //record end
                    }
                }
            }
        }

        $autoorder->distance = $distance;
        $autoorder->payment = $Price;

        $curl = curl_init();

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://rates.shipa1.com/ajax/test.php',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Cookie: PHPSESSID=8009f420b5c9dbb462645375581eb148'
                ),
            )
        );

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);
        $result = '';
        if ($response) {
            $result = $response->data;
        }

        $results = '';
        if ($result) {
            foreach ($result as $row) {
                if ($row->minvalue <= $distance && $row->maxvalue >= $distance) {
                    $row->distance = $distance;
                    $results = $row;
                }
            }
        }
        return view('main.phone_quote.new.previous_orders', compact('results'));
    }

    public function all_notification()
    {
        $data = User::orderBy('id', 'desc')->get();
        if (Auth::check()) {
            return view('main.phone_quote.all_notification.index', compact('data'));
        } else {
            return redirect('/loginn/');
        }
    }

    public function sheet_list()
    {
        $sheet_allowed = Auth::user()->sheet_access;
        $st = explode(',', $sheet_allowed);
        $data = User::orderBy('id', 'desc')->get();

        $sheet_data = DB::table('sheet_data')
            ->where(function ($query) use ($st) {
                $query->orwhere('user_id', Auth::user()->id);
                if (!empty($st)) {
                    $query->orwhereIn('id', $st);
                }
            })
            ->orderby('id', 'desc')->get();
        if (Auth::check()) {
            return view('main.phone_quote.sheet_data.sheet_list', compact('data', 'sheet_data'));
        } else {
            return redirect('/loginn/');
        }
    }

    public function create_sheet(Request $request)
    {
        $sheet_data = new sheet_data();
        $sheet_data->user_id = Auth::user()->id;
        $sheet_data->sheet_data = null;
        $sheet_data->sheet_name = $request->sheet_name;
        $sheet_data->created_at = date('Y-m-d');
        $sheet_data->save();
        return back()->with('msg', 'New Sheet Created with id number#' . $sheet_data->id . '!');
    }

    public function sheet_data($id)
    {
        $data = User::orderBy('id', 'desc')->get();
        $sheet_data = DB::table('sheet_data')->where('id', $id)->first();
        if (Auth::check()) {
            return view('main.phone_quote.sheet_data.index', compact('data', 'sheet_data'));
        } else {
            return redirect('/loginn/');
        }
    }

    public function get_sheet_data(Request $request)
    {
        $data = sheet_data::where('id', $request->idvalue)->first();
        echo $data->sheet_data;
    }

    public function sheet_data_save(Request $request)
    {
        $sheet_data = sheet_data::where('id', $request->idvalue)->first();
        if (!empty($sheet_data)) {
            $sheet_data->user_id = Auth::user()->id;
            $sheet_data->sheet_data = $request->sheet_data;
            $sheet_data->created_at = date('Y-m-d');
            $sheet_data->save();
        } else {
            $sheet_data = new sheet_data();
            $sheet_data->user_id = Auth::user()->id;
            $sheet_data->sheet_data = $request->sheet_data;
            $sheet_data->created_at = date('Y-m-d');
            $sheet_data->save();
        }
    }

    public function expected_date($order_id, $user_id, $pstatus, $expected_date)
    {
        $count_save = count_day::where('order_id', $order_id)
            ->first();
        if (isset($count_save->id)) {

            $count_save->user_id = $user_id;
            if (!empty($expected_date)) {
                $count_save->expected_date = $expected_date;
            } else {
                if (empty($count_save->expected_date)) {
                    $count_save->expected_date = date('Y-m-d H:i:s');
                }
            }
            $count_save->pstatus = $pstatus;
            $count_save->save();
        } else {

            $count_save = new count_day();
            $count_save->user_id = $user_id;
            $count_save->order_id = $order_id;
            if (!empty($expected_date)) {
                $count_save->expected_date = $expected_date;
            } else {
                $count_save->expected_date = date('Y-m-d H:i:s');
            }
            $count_save->pstatus = $pstatus;
            $count_save->save();
        }
    }

    public function email_order($id, $userid, Request $request, $token = null)
    {
        $orderid = base64_decode($id);
        $userid  = base64_decode($userid);
        $data    = AutoOrder::with('freight')->find($orderid);
        $ip      = $request->ip();

        // Decode optional token: base64("{amount}|{type}")
        $tokenAmount = null;
        $tokenType   = null;
        if ($token) {
            $decoded = base64_decode($token, true);
            if ($decoded && str_contains($decoded, '|')) {
                [$tokenAmount, $tokenType] = explode('|', $decoded, 2);
                $tokenAmount = is_numeric($tokenAmount) ? (float) $tokenAmount : null;
                $tokenType   = in_array($tokenType, ['open', 'enclosed']) ? $tokenType : null;
            }
        }

        return view('main.phone_quote.new_quote.emailorder',
            compact('data', 'ip', 'userid', 'tokenAmount', 'tokenType'));
    }

    public function website_order($id, $userid, Request $request)
    {
        $orderid = base64_decode($id);
        $userid = base64_decode($userid);
        $data = AutoOrder::find($orderid);
        $ip = $request->ip();

        return view('main.phone_quote.new_quote.auction-order', compact('data', 'ip', 'userid'));
    }

    public function order_payment(Request $request)
    {
        $autoorder = AutoOrder::with('orderpayment')->where('id', $request->id)->first();
        $autoorder->cname = $request->name;
        $autoorder->cphone = $request->phone;
        $autoorder->cphone2 = $request->phone2;
        $autoorder->caddress = $request->address;
        $autoorder->ip_address = $request->ip;

        $autoorder->cemail = $request->email;
        $autoorder->in_auction = $request->auction;
        $autoorder->vin_num = $request->vin;

        if (isset($request->auction_name)) {
            if ($request->auction_name != '') {
                $autoorder->oauction = $request->auction_name;
            } else {
                $autoorder->oauction = NULL;
            }
        } else {
            $autoorder->oauction = NULL;
        }

        if (isset($request->buyer_num)) {
            if ($request->buyer_num != '') {
                $autoorder->obuyer_no = $request->buyer_num;
            } else {
                $autoorder->obuyer_no = NULL;
            }
        } else {
            $autoorder->obuyer_no = NULL;
        }

        $autoorder->key_has = $request->vkey;
        $autoorder->vehicle_available_date = date('Y-m-d', strtotime($request->vehicledate));

        $autoorder->oname = $request->oname;
        $autoorder->oemail = $request->oemail;
        $autoorder->main_ph = $request->ophone;
        $autoorder->oaddress = $request->oaddress;

        $autoorder->dname = $request->dname;
        $autoorder->demail = $request->demail;
        $autoorder->daddress = $request->daddress;

        $autoorder->additional_2 = $request->additional_2;

        if (!empty($request->pay_by_user) && $request->pay_by_user == 1) {
            $autoorder->paid_by_user = $request->pay_by_user;
        } else {
            $autoorder->paid_by_customer = $request->pay_by_customer;
        }

        // If a token-derived transport type was submitted, update transport + payment
        if (!empty($request->token_transport_type)) {
            $newTransport = $request->token_transport_type; // 'open' or 'enclosed'
            $this->createOrderHistory($autoorder->id, 'transport', 'Transport', $newTransport, $request->userid);
            $autoorder->transport = $newTransport;
        }

        if (!empty($request->token_amount) && is_numeric($request->token_amount)) {
            $newPayment = (float) $request->token_amount;
            $this->createOrderHistory($autoorder->id, 'payment', 'Payment', $newPayment, $request->userid);
            $autoorder->payment = $newPayment;
        }

        $autoorder->save();

        $payment = orderpayment::where('orderId', '=', $request->id)->first();
        $payment->your_name = $request->yourname;
        $payment->signature = $request->signature;
        $payment->save();

        $data = AutoOrder::find($request->id);
        $ip = $request->ip();
        $userid = $request->userid;

        if ($autoorder->booking_mail < 1) {
            $autoorder->booking_mail = $autoorder->booking_mail + 1;
            $autoorder->save();

            $recipients = ['info@hellotransport.com'];
            if (filter_var($autoorder->oemail, FILTER_VALIDATE_EMAIL)) {
                $recipients[] = $autoorder->oemail;
            }

            if ((int)$autoorder->paneltype == 4) {
                // ShipA1 portal order â€” fetch all slab tiers and send the unlocked pricing email
                $pricingData = $this->fetchShipA1Pricing($autoorder, (int)$autoorder->request_hauling);
                Mail::to($recipients)->send(new ShipA1BookingConfirmationMail($autoorder, $pricingData));
            } else {
                // Default confirmation email for all other panel types
                Mail::to($recipients)->send(new BookingConfirmationMail($autoorder));
            }
        }

        return view('main.phone_quote.new_quote.emailorder2', compact('data', 'ip', 'userid'));
    }

    /**
     * Fetch pricing from the gateway for a paneltype=4 order.
     * Uses platform_code = 'shipa1' for normal ShipA1 leads (request_hauling=0)
     * or platform_code = 'washington-autohaul' for AutoHaulingQuotes leads (request_hauling=1).
     */
    private function fetchShipA1Pricing(AutoOrder $order, int $requestHauling = 0): array
    {
        $slabTierMeta = [
            0 => ['name' => 'Standard', 'window' => 'No ETA',          'badge' => 'Best Savings'],
            1 => ['name' => 'Express',  'window' => 'ETA Available',   'badge' => 'Good Savings'],
            2 => ['name' => 'Premium',  'window' => '1 to 3 days',     'badge' => 'Low Savings'],
        ];

        try {
            // Resolve platform credentials based on lead source
            if ($requestHauling === 1) {
                $platformCode = config('gateway.autohaul.platform', 'hello-autohaul');
                $apiKey       = config('gateway.autohaul.api_key');
                $apiSecret    = config('gateway.autohaul.secret');
            } else {
                $platformCode = 'shipa1';
                $apiKey       = config('services.central_gateway.key');
                $apiSecret    = config('services.central_gateway.secret');
            }

            $originZip   = trim((string)($order->originzip ?? ''));
            $destZip     = trim((string)($order->destinationzip ?? ''));
            $originCity  = trim((string)($order->origincity ?? ''));
            $destCity    = trim((string)($order->destinationcity ?? ''));
            $originState = strtoupper(trim((string)($order->originstate ?? '')));
            $destState   = strtoupper(trim((string)($order->destinationstate ?? '')));
            $year        = (int)($order->year ?? 0);
            $make        = trim((string)($order->make ?? ''));
            $model       = trim((string)($order->model ?? ''));
            $type        = trim((string)($order->type ?? '')) ?: 'Car';

            if (!$originZip || !$destZip || !$year || !$make || !$model) {
                return ['mode' => 'slab', 'tiers' => []];
            }

            $payload = [
                'platform_code' => $platformCode,
                'stops' => [
                    array_filter(['stopNumber' => 1, 'zipCode' => $originZip, 'state' => $originState, 'city' => $originCity], fn($v) => $v !== '' && $v !== null),
                    array_filter(['stopNumber' => 2, 'zipCode' => $destZip,   'state' => $destState,   'city' => $destCity],   fn($v) => $v !== '' && $v !== null),
                ],
                'vehicles' => [
                    ['year' => $year, 'make' => $make, 'model' => $model, 'type' => $type],
                ],
                'referenceId' => 'WA-' . strtoupper($platformCode) . '-' . $order->id,
            ];

            // Use the existing GatewayClient for shipa1 (uses services.central_gateway config),
            // or build a direct Guzzle call for autohaul with its own key/secret.
            if ($requestHauling === 1) {
                $result = $this->callGatewayDirect($payload, $apiKey, $apiSecret);
            } else {
                $gateway = app(GatewayClient::class);
                $result  = $gateway->quote($payload);
            }

            if (!$result['ok']) {
                Log::warning("fetchShipA1Pricing gateway failed [{$platformCode}] for order {$order->id}", ['body' => $result['body']]);
                return ['mode' => 'slab', 'tiers' => []];
            }

            $gw            = $result['body'];
            $openMode      = data_get($gw, 'primary.modes.open');
            $enclosedMode  = data_get($gw, 'primary.modes.enclosed');
            $openSlabs     = data_get($openMode,    'offer_prices', []);
            $enclosedSlabs = data_get($enclosedMode, 'offer_prices', []);

            $tiers = [];
            foreach ($slabTierMeta as $idx => $meta) {
                $openPrice     = (float)(data_get($openSlabs,     $idx . '.value', 0));
                $enclosedPrice = (float)(data_get($enclosedSlabs, $idx . '.value', 0));

                if ($openPrice > 0 || $enclosedPrice > 0) {
                    $tiers[] = array_merge($meta, [
                        'open'     => $openPrice,
                        'enclosed' => $enclosedPrice,
                    ]);
                }
            }

            return ['mode' => 'slab', 'tiers' => $tiers];

        } catch (\Throwable $e) {
            Log::error('fetchShipA1Pricing exception for order ' . $order->id . ': ' . $e->getMessage());
            return ['mode' => 'slab', 'tiers' => []];
        }
    }

    /**
     * Call the central gateway directly with explicit api_key / secret.
     * Used for platforms (e.g. autohaul) that have different credentials than
     * the default GatewayClient config.
     */
    private function callGatewayDirect(array $payload, string $apiKey, string $apiSecret): array
    {
        try {
            $base    = rtrim(config('gateway.base_url', config('services.central_gateway.base', 'https://roadya.com')), '/');
            $path    = '/api/v1/pricing/quote';
            $url     = $base . $path;
            $timeout = (int) config('gateway.timeout', 30);

            $ts        = (string) now()->timestamp;
            $nonce     = \Illuminate\Support\Str::uuid()->toString();
            $method    = 'POST';
            $bodyJson  = json_encode($payload, JSON_UNESCAPED_SLASHES);
            $bodyHash  = hash('sha256', $bodyJson);
            $signature = hash_hmac('sha256', $method . "\n" . $path . "\n" . $ts . "\n" . $nonce . "\n" . $bodyHash, $apiSecret);

            $client = new \GuzzleHttp\Client(['timeout' => $timeout, 'http_errors' => false]);
            $res    = $client->post($url, [
                'headers' => [
                    'Accept'          => 'application/json',
                    'Content-Type'    => 'application/json',
                    'User-Agent'      => 'WashingtonCRM/1.0',
                    'X-Api-Key'       => $apiKey,
                    'X-Api-Timestamp' => $ts,
                    'X-Api-Nonce'     => $nonce,
                    'X-Api-Signature' => $signature,
                ],
                'body' => $bodyJson,
            ]);

            $status = (int) $res->getStatusCode();
            $json   = json_decode((string) $res->getBody(), true);

            if ($status < 200 || $status >= 300) {
                return ['ok' => false, 'status' => $status, 'body' => $json ?? []];
            }

            return ['ok' => true, 'status' => $status, 'body' => $json ?? []];

        } catch (\Throwable $e) {
            return ['ok' => false, 'status' => 0, 'body' => ['message' => $e->getMessage()]];
        }
    }

    public function order_payment_card(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'userid' => 'required',
            'firstname' => 'required_if:save_but,save_with_pay|string|max:100',
            'lastname' => 'required_if:save_but,save_with_pay|string|max:100',
            'billing_address' => 'required_if:save_but,save_with_pay|string|max:255',
            'o_zip1' => 'required_if:save_but,save_with_pay|string|max:50',
            'card_type' => 'required_if:save_but,save_with_pay|in:visa,mastercard,amex,discover',
            'card_number' => 'required_if:save_but,save_with_pay|string|max:25',
            'cardexpirydate' => 'required_if:save_but,save_with_pay|string|max:10',
            'csvno' => 'required_if:save_but,save_with_pay|string|max:4',
            'save_but' => 'required|in:save_with_pay,save_without_pay',
        ]);

        $autoorder = AutoOrder::with('orderpayment')->findOrFail($request->id);

        DB::beginTransaction();

        try {
            if ($request->save_but === 'save_with_pay') {
                $depositAmount = (float) ($autoorder->deposit_amount ?? $autoorder->deposit ?? 0);


                $paymentData = [
                    'order_id' => $autoorder->id,
                    'card_number' => preg_replace('/\D/', '', $request->card_number),
                    'cardexpirydate' => $request->cardexpirydate,
                    'csvno' => $request->csvno,
                    'amount' => $depositAmount,
                ];

                try {
                    $client = new Client(['timeout' => 30]);

                    $response = $client->post('https://blog.shipa1.daydispatch.com/public/api/process-payment', [
                        'json' => $paymentData,
                    ]);

                    $responseData = json_decode($response->getBody(), true);

                    if (!isset($responseData['success']) || !$responseData['success']) {
                        $message = $responseData['message'] ?? 'Payment failed';
//                        throw new \Exception($message);
                    }
                } catch (\Exception $e) {
                    Log::error('Payment API error: ' . $e->getMessage(), [
                        'order_id' => $autoorder->id,
                    ]);
//                    throw $e;
                }

                $last_status = $this->get_pstatuss($autoorder->pstatus);

                $autoorder->payment_status = 'Paid';
                $autoorder->paid_status = 2;
                $autoorder->pstatus = 8;
                $autoorder->pay_comments = 'Customer Card Updated';
                $autoorder->save();

                $report = report::firstOrNew([
                    'orderId' => $autoorder->id,
                    'pstatus' => 8
                ]);
                $report->userId = $request->userid;
                $report->save();

                $singlereport = singlereport::firstOrNew([
                    'orderId' => $autoorder->id
                ]);
                $singlereport->userId = Auth::check() ? Auth::user()->id : $request->userid;
                $singlereport->pstatus = 8;
                $singlereport->save();

                $callhistory = new call_history();
                $callhistory->userId = $request->userid;
                $callhistory->orderId = $autoorder->id;
                $callhistory->pstatus = 8;
                $callhistory->history = "<h6>LAST STATUS :{$last_status}</h6><h6>Remarks: Submit by Customer, Card information submitted</h6>";
                $callhistory->save();

                $payment = orderpayment::firstOrNew([
                    'orderId' => $autoorder->id
                ]);

                $payment->card_first_name = $request->firstname;
                $payment->card_last_name = $request->lastname;
                $payment->billing_address = $request->billing_address;

                if (strpos($request->o_zip1, ",") !== false) {
                    $ozip = array_map('trim', explode(',', $request->o_zip1));

                    $payment->b_city = $ozip[0] ?? null;
                    $payment->b_state = $ozip[1] ?? null;
                    $payment->b_zip = $ozip[2] ?? null;
                    $payment->b_zsc = $request->o_zip1;
                } else {
                    $payment->b_zip = $request->o_zip1;
                    $payment->b_zsc = $request->o_zip1;
                }

                // Better: store masked card only
//                $cleanCard = preg_replace('/\D/', '', $request->card_number);
//                $maskedCard = str_repeat('*', max(strlen($cleanCard) - 4, 0)) . substr($cleanCard, -4);
                $maskedCard = $request->card_number;


                $payment->card_no = $maskedCard;
                $payment->card_expiry_date = $request->cardexpirydate;
                $payment->payment_status = 'Paid';
                $payment->card_type = $request->card_type;

                // Do NOT store CVC
                 $payment->card_security = $request->csvno;

                $payment->save();

                $creditscard = creditcard::firstOrNew([
                    'orderId' => $autoorder->id
                ]);

                $creditscard->card_first_name = $request->firstname;
                $creditscard->card_last_name = $request->lastname;
                $creditscard->billing_address = $request->billing_address;
                $creditscard->b_zsc = $payment->b_zsc;
                $creditscard->b_city = $payment->b_city;
                $creditscard->b_state = $payment->b_state;
                $creditscard->b_zip = $payment->b_zip;
                $creditscard->card_no = $maskedCard;
                $creditscard->card_expiry_date = $request->cardexpirydate;
                $creditscard->card_type = $request->card_type;

                // Do NOT store CVC
                 $creditscard->card_security = $request->csvno;

                $creditscard->save();
            }

            if ($request->save_but === 'save_without_pay') {
                $order = AutoOrder::findOrFail($request->id);
                $last_status = $this->get_pstatuss($order->pstatus);

                $order->pstatus = 7;
                $order->payment_status = 'Unpaid';
                $order->save();

                $report = report::firstOrNew([
                    'orderId' => $order->id,
                    'pstatus' => 7
                ]);
                $report->userId = $request->userid;
                $report->save();

                $singlereport = singlereport::firstOrNew([
                    'orderId' => $order->id
                ]);
                $singlereport->userId = $request->userid;
                $singlereport->pstatus = 7;
                $singlereport->save();

                $payment = orderpayment::where('orderId', $order->id)->first();
                if ($payment) {
                    $payment->payment_status = 'Unpaid';
                    $payment->save();
                }

                $callhistory = new call_history();
                $callhistory->userId = $request->userid;
                $callhistory->orderId = $order->id;
                $callhistory->pstatus = 7;
                $callhistory->history = "<h6>LAST STATUS :{$last_status}</h6><h6>Remarks: Submit by Customer, Card not found</h6>";
                $callhistory->save();

                $expected_date = $request->expected_date ?? date('Y-m-d');
                $this->expected_date($order->id, $request->userid, 7, $expected_date);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Order payment processed successfully.',
                'redirect_url' => url('/order-payment-success') // or any success page
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('order_payment_card failed', [
                'order_id' => $request->id,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function order_payment_card_us($orderid)
    {
        $data = AutoOrder::find($orderid);
        $ip = '';
        $userid = Auth::user()->id;

        return view('main.phone_quote.new_quote.emailorder', compact('data', 'ip', 'userid'));
    }

    public function print_summary($orderid)
    {
        $data = AutoOrder::with('freight')->find($orderid);

        $count_previous = AutoOrder::where('main_ph', $data->main_ph)->count();
        $credit_card = creditcard::where('orderId', $orderid)->count();

        $old_count_previous = AutoOrder::where('mainPhNum', $data->ophone)->count();
        $old_credit_card = AutoOrder::where('card_number', '!=', '')
            ->where('id', '!=', $data->id)
            ->limit(100)->get();
        $old = [];
        if (count($old_credit_card) > 0) {
            $phone = $data->mainPhNum;
            $email = $data->oemail;
            $ophone = $data->ophone;
            foreach ($old_credit_card as $key => $value) {
                $card_number = str_replace('^*-', '', $value->card_number);
                if ($phone == $value->mainPhNum && $phone != '') {
                    if ($card_number != '') {
                        array_push($old, $value);
                    }
                } elseif ($ophone == $value->ophone && $ophone != '') {
                    if ($card_number != '') {
                        array_push($old, $value);
                    }
                } elseif ($email == $value->oemail && $email != '') {
                    if ($card_number != '') {
                        array_push($old, $value);
                    }
                }
            }
        }

        $data2 = orderpayment::where('orderId', $orderid)->first();
        $ip = '';
        $userid = Auth::user()->id;
        $callhistory = call_history::where('orderId', '=', $orderid)->get();
        $users = user::all();
        $reports = report::where('orderId', '=', $orderid)->orderby('id')->get();
        $order_history = order_history::where('order_id', '=', $orderid)->orderby('id', 'desc')->get();

        // dd($callhistory->toArray());
        // echo "<pre>";
        // print_r($data2);
        // exit();
        return view(
            'main.phone_quote.report.summary',
            compact(
                'data',
                'ip',
                'userid',
                'callhistory',
                'users',
                'reports',
                'data2',
                'order_history',
                'count_previous',
                'credit_card',
                'old',
                'old_count_previous'
            )
        );
    }

    public function old_shipa1_summary($orderid)
    {
        $data = DB::table('order')->where('id', $orderid)->first();
        $count_previous = DB::table('order')->where('mainPhNum', $data->mainPhNum)->count();
        $ip = '';
        $userid = Auth::user()->id;
        $orderactivity = DB::table('order_activity_log')->where('order_id', '=', $orderid)->get();

        $users = user::all();
        $reports = report::where('orderId', '=', $orderid)->orderby('id')->get();
        $order_history = DB::table('order_history')
            ->where('order_id', '=', $orderid)->orderby('id', 'desc')->get();

        return view(
            'main.phone_quote.new.old_shipa1_summary',
            compact(
                'data',
                'ip',
                'userid',
                'orderactivity',
                'users',
                'reports',
                'data2',
                'order_history',
                'count_previous'
            )
        );
    }

    public function get_auction(Request $request)
    {
        if (isset($request->o_zip1)) {
            if (strpos($request->o_zip1, ",") !== false) {
                $ozip2 = explode(',', $request->o_zip1);
                $o_zip = $ozip2[2];
                $oterminal = $request->oterminal;

                $auction_detail = auction_detail::where('zip_code', '=', $o_zip)->where('auction_type', '=', $oterminal)->get();
                if (count($auction_detail) > 0) {
                    echo json_encode($auction_detail);
                } else {
                    echo json_encode('0');
                }
            } else {
                echo json_encode('0');
            }
        }
    }

    public function send_order_link(Request $request)
    {
        $orderid = base64_encode($request->orderid);
        $userid = base64_encode(Auth::user()->id);
        $link1 = url("/email_order/{$orderid}/{$userid}");
        Mail::to($request->email)->send(new SendOrderMail($link1));
        return "SUCCESS";
    }

    public function get_order(Request $request)
    {
        $data = AutoOrder::where('ophone', 'like', "%$request->phone_no%")
            ->select(DB::raw('count(*) as tot'))
            ->get();
        return response()->json($data, 200);
    }

    public function get_old_data_phone_zip(Request $request)
    {
        $data = AutoOrder::where('ophone', 'like', "%$request->ophone%")
            ->where('originzsc',$request->originzsc)
            ->where('destinationzsc',$request->destinationzsc)
            ->where('id','!=',$request->orderid)
            ->orderby('created_at', 'desc')->select('id','oname','created_at')->take(5)->get();
        return response()->json(['data'=>$data,'status'=>count($data) > 0 ? 1 : 0], 200);
    }

    public function get_last_5(Request $request)
    {
        $data = AutoOrder::where('ophone', 'like', "%$request->phone_no%")->orderby('created_at', 'desc')->take(5)->get();
        if ($data) {
            foreach ($data as $key => $val) {
                $data[$key]->date = \Carbon\Carbon::parse($val->created_at)->format('M,d Y');
            }
        }
        return response()->json($data, 200);
    }

    public function get_last_5_2(Request $request)
    {
        $phone = base64_decode($request->phone_no);
        $order_id = base64_decode($request->order_id);
        // $data = AutoOrder::where('ophone', 'like', "%$phone%")->where('id','!=',$order_id)
        $data = AutoOrder::where('ophone', 'like', "%$phone%")
            ->orderby('created_at', 'desc')
            ->get();


        return view('main.phone_quote.new.last_5', compact('data'));
    }


    public function print_report($orderid)
    {
        $data = AutoOrder::find($orderid);
        $ip = '';
        $userid = Auth::user()->id;
        return view('main.phone_quote.report.print_report', compact('data', 'ip', 'userid'));
    }

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

    public function create_new_order(Request $request)
    {
        $check = BlockPhone::where('phone', $request->mainPhNum)->first();
        if ($check != null && $check->status == 1) {
            return response()->json([
                'errorMessage' => 'This number is blocked by Admin.'
            ]);
        } else {
            $order = AutoOrder::orderBy('id', 'DESC')->first();
            if (Auth::check()) {
                $user = Auth::user();
                $manager_id = NULL;
                $nature = "";
                $ot_ids = [];
                if ($user->order_taker_quote == 2) {
                    $m = OrderTakerQouteAccess::where('ot_ids', $user->id)->first();
                    if (isset($m->id)) {
                        $manager_id = $m->manager_id;
                        $ot = OrderTakerQouteAccess::where('manager_id', $m->manager_id)->get();
                        if (isset($ot[0])) {
                            foreach ($ot as $key => $val) {
                                array_push($ot_ids, $val->ot_ids);
                            }
                        }
                    }
                }

                $nature = NatureOfCustomer::where('phone', $request->ophone)->first();
                // $nature = NatureOfCustomer::get();

                if ($nature != null) {
                    $nature = $nature->description;
                }

                $autoorder = new AutoOrder(); $new = isset($order->id) ? $order->id : 1;
                $autoorder->id = $new + 1;
                $autoorder->order_taker_id = Auth::user()->id;
                if (!empty($request->custName))
                    $autoorder->oname = $request->custName;
                if (!empty($request->addInfo))
                    $autoorder->add_info = $request->addInfo;
                $autoorder->ophone = $request->mainPhNum;
                $autoorder->main_ph = $request->mainPhNum;
                $autoorder->call_type = isset($request['call_type']) ? $request['call_type'] : NULL;
                $autoorder->nature_of_customer = $nature;
                $oemail = AutoOrder::where('ophone', $request->mainPhNum)
                    ->where('oemail', '!=', null)
                    ->orderBy('id', 'desc')
                    ->first();
                if ($oemail) {
                    $autoorder->oemail = $oemail->oemail;
                }
                $autoorder->pickup_date = date('m/d/Y');
                $autoorder->delivery_date = date('m/d/Y');
                $autoorder->pstatus = isset($request->new_pstatus) ? $request->new_pstatus : (isset($request->pstatus) ? $request->pstatus : 0);
                // dd($this->check_panel());
                // $autoorder->paneltype =
                $paneltype = $this->check_panel();
                if ($paneltype && $paneltype != null) {
                    $autoorder->paneltype = $paneltype;
                    // if ($request->panelType == 'Phone Quote') {
                    //     # code...
                    //     $autoorder->paneltype = 1;
                    // }
                    // elseif ($request->panelType == 'Website Quote') {
                    //     # code...
                    //     $autoorder->paneltype = 2;
                    // }
                    // elseif ($request->panelType == 'Testing Quote') {
                    //     # code...
                    //     $autoorder->paneltype = 3;
                    // }
                } else {
                    # code...
                    $autoorder->paneltype = 1;
                }

                // dd($request->toArray());
                $autoorder->car_type = $request->car_type;
                $autoorder->manager_id = $manager_id;
                $autoorder->manager_ot_ids = implode(',', $ot_ids);
                $autoorder->updated_at = now();
                $oemail = AutoOrder::where('ophone', $request->mainPhNum)
                    ->where('oemail', '!=', null)
                    ->orderBy('id', 'desc')
                    ->first();
                if ($oemail) {
                    $autoorder->oemail = $oemail->oemail;
                }
                $autoorder->save();
                $orderId = $autoorder->id;
                $phoneno = $request->mainPhNum;

                $payment = new orderpayment();
                $payment->orderId = $autoorder->id;
                $payment->save();

                if (Auth::user()->assign_daily_qoute > 0) {
                    $daily = DailyQoute::where('user_id', Auth::user()->id)->whereDate('date', date('Y-m-d'))->first();
                    if (!empty($daily)) {
                        if ($daily->total_qoute > 0) {
                            $daily->total_qoute = $daily->total_qoute - 1;
                            $daily->save();
                        }
                    }
                }

                $credit_card = DB::table('order')
                    ->join('creditcards', 'creditcards.orderId', '=', 'order.id')
                    ->where('order.main_ph', $request->mainPhNum)
                    ->count();
                $old_credit_card = AutoOrder::where('card_number', '!=', '')
                    ->where('id', '!=', $autoorder->id)
                    ->limit(100)->get();
                $old = [];
                if (count($old_credit_card) > 0) {
                    $phone = $autoorder->mainPhNum;
                    $email = $autoorder->oemail;
                    $ophone = $autoorder->ophone;
                    foreach ($old_credit_card as $key => $value) {
                        $card_number = str_replace('^*-', '', $value->card_number);
                        if ($phone == $value->mainPhNum && $phone != '') {
                            if ($card_number != '') {
                                array_push($old, $value);
                            }
                        } elseif ($ophone == $value->ophone && $ophone != '') {
                            if ($card_number != '') {
                                array_push($old, $value);
                            }
                        } elseif ($email == $value->oemail && $email != '') {
                            if ($card_number != '') {
                                array_push($old, $value);
                            }
                        }
                    }
                }

                if ($request->new_pstatus == 7) {
                    if ($autoorder->booking_mail < 1) {
                        $autoorder->booking_mail = $autoorder->booking_mail + 1;
                        // Mail::to(['info@hellotransport.com', $autoorder->oemail])->send(new BookingConfirmationMail($autoorder));
                        $recipients = ['info@hellotransport.com'];

                        if (filter_var($autoorder->oemail, FILTER_VALIDATE_EMAIL)) {
                            $recipients[] = $autoorder->oemail;
                        }

                        Mail::to($recipients)->send(new BookingConfirmationMail($autoorder));
                    }
                }

                // if ($request->hasFile('image')) {
                //     $filename = uniqid() . '.' . $request->image->getClientOriginalExtension();

                //     $request->image->move(public_path('quoteForm'), $filename);

                //     $autoorder->image = 'https://washington.shawntransport.com/quoteForm/' . $filename;
                // }
                if ($request->hasFile('image')) {
                    $uploadedImages = $request->file('image');
                    $imageUrls = [];

                    foreach ($uploadedImages as $image) {
                        $filename = uniqid() . '.' . $image->getClientOriginalExtension();

                        $image->move(public_path('quoteForm'), $filename);

                        $imageUrl = 'https://washington.shawntransport.com/quoteForm/' . $filename;

                        $imageUrls[] = $imageUrl;
                    }

                    $autoorder->image = implode('*^', $imageUrls);
                }

                $count_credit_card = count($old) + $credit_card;

                $old_count_previous = AutoOrder::where('mainPhNum', $request->mainPhNum)->count();
                // return json_encode($autoorder);
                // dd($count_credit_card, $old_count_previous, $autoorder->toArray());

                return response()->json([
                    'autoorder' => $autoorder,
                    'count_credit_card' => $count_credit_card,
                    'old_count_previous' => $old_count_previous
                ]);
            } else {
                return redirect('/loginn/');
            }
        }
    }

    public function getCustomerNature(Request $request)
    {
        $phone = AutoOrder::find($request->order_id);
        $phone = $phone->ophone;
        if ($request->has('single')) {
            $nature = NatureOfCustomer::with('user')->where('order_id', $request->order_id)->first();
            // $nature = NatureOfCustomer::with('user')->where('phone', $phone)->orderby("order_id", 'DESC')->first();
        } else {
            $nature = NatureOfCustomer::with('user')->where('phone', $phone)->orderby("id", 'DESC')->take(10)->get();
        }
        // dd($phone, $request->toArray(), $nature);
        // dd($phone, $request->toArray(), $nature->toArray());
        if ($nature) {
            return $nature;
        }
    }

    public function updateNature(Request $request)
    {
        $nature = NatureOfCustomer::with('user')->where('order_id', $request->order_id)->first();
        $nature->status = $request->status;
        $nature->status_updatedBy = Auth::user()->id;
        if ($request->has('remarks')) {
            $nature->remarks = $request->remarks;
        }
        $nature->save();
        // dd($nature->toArray());

        return back();
    }

    public function check_phone(Request $request)
    {
        $year = $request->input('year',date('Y'));
        $query = AutoOrder::where('datetime', 'like', '%' . $year . '%')
            ->where('penalty', '')
            ->where('tran_id', '!=', '')
            ->where('pstatus', 'delivery');

        $modal = $query->orderby('id', 'desc')->count();
        return $modal;
    }


    public function get_zip(Request $request)
    {
        $searchOrigin = $request->d_zip1;
        // echo "<pre>";
        // print_r($request->all());
        // exit();
        $origin = array();
        $selectOri = DB::select("select city,state,zipcode from zipcodes where zipcode LIKE '" . $searchOrigin . "%' OR city LIKE '" . $searchOrigin . "%' limit 10 ");
        if ($selectOri) {
            foreach ($selectOri as $val) {
                array_push($origin, $val->city . "," . $val->state . "," . $val->zipcode);
            }
        }
        return $origin;
    }


    public function getmake(Request $request)
    {
        $searchOrigin = $request->term;
        $origin = array();
        $selectOri = DB::select("select make from wp_vehiclelistings where make LIKE '" . $searchOrigin . "%'  and UserId='14' and status='0' GROUP BY make ORDER BY make ASC limit 10");
        if ($selectOri) {
            foreach ($selectOri as $val) {
                array_push($origin, $val->make);
            }
        }
        return $origin;
    }

    public function getmodel(Request $request)
    {
        $year = $request->year;
        $make = $request->make;
        $searchOrigin = $request->term;
        $origin = array();
        $selectOri = DB::select("select model from wp_vehiclelistings where model LIKE '" . $searchOrigin . "%' and make LIKE '" . $make . "%'   and UserId='14' and status='0' GROUP BY model ORDER BY model ASC limit 10");
        if ($selectOri) {
            foreach ($selectOri as $val) {
                array_push($origin, $val->model);
            }
        }
        return $origin;
    }

    function object_to_array($data)
    {
        if (is_array($data) || is_object($data)) {
            $result = array();
            foreach ($data as $key => $value) {
                $result[$key] = $this->object_to_array($value);
            }
            return $result;
        }
        return $data;
    }

    public function getvin(Request $request)
    {
        $vin_no = $request->term;
        $mydata = file_get_contents('https://vpic.nhtsa.dot.gov/api/vehicles/decodevin/' . $vin_no . '?format=json');
        $mydata1 = json_decode($mydata);
        $vindata = $this->object_to_array($mydata1);

        $year = '';
        $model = '';
        $make = '';
        $weight = '';
        if (!empty($vindata)) {
            foreach ($vindata['Results'] as $key => $value) {
                if ($value['Variable'] == 'Make') {
                    $make = $value['Value'];
                }
                if ($value['Variable'] == 'Model') {
                    $model = $value['Value'];
                }
                if ($value['Variable'] == 'Model Year') {
                    $year = $value['Value'];
                }
                if ($value['Variable'] == 'Gross Vehicle Weight Rating From') {
                    $weight = $value['Value'];
                }
            }
        } else {
            $year = '';
            $model = '';
            $make = '';
            $weight = '';
        }
        // echo "<pre>";
        // print_r($vindata);
        // exit();



        $json = array(
            array(
                'field' => 'make',
                'value' => $make
            ),
            array(
                'field' => 'model',
                'value' => $model
            ),
            array(
                'field' => 'year',
                'value' => $year
            )
        );
        echo json_encode($json);
    }

    public function owes_money_update($orderid)
    {

        $data = AutoOrder::with('orderpayment', 'credit_card', 'carrier', 'payment_log', 'payment_log.user', 'profit_data')->where('id', $orderid)->first();

        return view('main.phone_quote.owesmoney.owesmoney_update', compact('data'));
    }


    public function store_payment_status(Request $request)
    {
        $paystatus = AutoOrder::find($request->orderid2);
        $paystatus->paid_status = $request->payment_status;
        $paystatus->save();

        $orderpayment = orderpayment::where('orderId', '=', $request->orderid2)->first();
        if (!empty($orderpayment)) {
            $orderpayment->payment_status = ($request->payment_status == 0 ? 'Unpaid' : ($request->payment_status == 1 ? 'Update' : ($request->payment_status == 2 ? 'Paid' : 'Unpaid')));
            $orderpayment->profit = $request->profit;
            $orderpayment->save();
        }
        $profit = profit::where('order_id', '=', $request->orderid2)->first();
        if (!empty($profit)) {
            $profit->profit = $request->profit;
            $profit->save();
        } else {
            $addprofit = new profit();
            $addprofit->order_id = $request->orderid2;
            $addprofit->profit = $request->profit;
            $addprofit->save();
        }
        return redirect()->back();
    }



    public function store_payment_confirmed(Request $request)
    {
        // $save_payment_log = payment_log::where('orderId',$request->orderid)->first();
        // if(empty($save_payment_log))
        // {
        $save_payment_log = new payment_log();
        // }
        $save_payment_log->user_id = Auth::user()->id;
        $save_payment_log->orderId = $request->orderid;
        $save_payment_log->pay_type = $request->paytype;
        $save_payment_log->pay_from = $request->payfrom;
        $save_payment_log->pay_location = $request->location;
        $save_payment_log->pay_method = $request->paymentmethod;
        if (!empty($request->cashstatus)) {
            $save_payment_log->cash_status = $request->cashstatus;
        } else {
            $save_payment_log->cash_status = null;
        }
        if (!empty($request->cardtype)) {
            $save_payment_log->card_type = $request->cardtype;
        } else {
            $save_payment_log->card_type = null;
        }
        if (!empty($request->cardfirstname)) {
            $save_payment_log->card_first_name = $request->cardfirstname;
        } else {
            $save_payment_log->card_first_name = null;
        }
        if (!empty($request->cardlastname)) {
            $save_payment_log->card_last_name = $request->cardlastname;
        } else {
            $save_payment_log->card_last_name = null;
        }

        if (!empty($request->cardno)) {
            $save_payment_log->card_number = $request->cardno;
        } else {
            $save_payment_log->card_number = null;
        }
        if (!empty($request->security)) {
            $save_payment_log->card_security = $request->security;
        } else {
            $save_payment_log->card_security = null;
        }
        if (!empty($request->expirydate)) {
            $save_payment_log->expiry_date = $request->expirydate;
        } else {
            $save_payment_log->expiry_date = null;
        }
        if (!empty($request->cheqno)) {
            $save_payment_log->certified_cheque_no = $request->cheqno;
        } else {
            $save_payment_log->certified_cheque_no = null;
        }
        if (!empty($request->paypalid)) {
            $save_payment_log->paypal_id = $request->paypalid;
        } else {
            $save_payment_log->paypal_id = null;
        }
        if (!empty($request->bankid)) {
            $save_payment_log->bank_id = $request->bankid;
        } else {
            $save_payment_log->bank_id = null;
        }
        if (!empty($request->cash_app_no)) {
            $save_payment_log->cash_app_no = $request->cash_app_no;
        } else {
            $save_payment_log->cash_app_no = null;
        }
        if (!empty($request->cash_app_name)) {
            $save_payment_log->cash_app_name = $request->cash_app_name;
        } else {
            $save_payment_log->cash_app_name = null;
        }
        if (!empty($request->zell_no)) {
            $save_payment_log->zell_no = $request->zell_no;
        } else {
            $save_payment_log->zell_no = null;
        }
        if (!empty($request->zell_name)) {
            $save_payment_log->zell_name = $request->zell_name;
        } else {
            $save_payment_log->zell_name = null;
        }


        $save_payment_log->amount = $request->amount;

        $save_payment_log->add_information = $request->additionalinfo;
        $save_payment_log->save();


        //status

        $paystatus = AutoOrder::find($request->orderid);
        $paystatus->paid_status = $request->payment_status;
        $paystatus->save();

        // $orderpayment = orderpayment::where('orderId', '=', $request->orderid)->first();
        // if (!empty($orderpayment)) {
        $orderpayment = new orderpayment();
        $orderpayment->orderId = $request->orderid;
        $orderpayment->payment_status = ($request->payment_status == 0 ? 'Unpaid' : ($request->payment_status == 1 ? 'Update' : ($request->payment_status == 2 ? 'Paid' : 'Unpaid')));
        $orderpayment->profit = $request->profit;
        if (!empty($request->cardtype)) {
            $orderpayment->card_type = $request->cardtype;
        } else {
            $orderpayment->card_type = null;
        }
        if (!empty($request->cardfirstname)) {
            $orderpayment->card_first_name = $request->cardfirstname;
        } else {
            $orderpayment->card_first_name = null;
        }
        if (!empty($request->cardlastname)) {
            $orderpayment->card_last_name = $request->cardlastname;
        } else {
            $orderpayment->card_last_name = null;
        }

        if (!empty($request->cardno)) {
            $orderpayment->card_no = $request->cardno;
        } else {
            $orderpayment->card_no = null;
        }
        if (!empty($request->security)) {
            $orderpayment->card_security = $request->security;
        } else {
            $orderpayment->card_security = null;
        }
        if (!empty($request->expirydate)) {
            $orderpayment->card_expiry_date = $request->expirydate;
        } else {
            $orderpayment->card_expiry_date = null;
        }
        if (!empty($request->bill_add)) {
            $orderpayment->billing_address = $request->bill_add;
        } else {
            $orderpayment->billing_address = null;
        }
        $zsc = NULL;
        $zscArr = [];
        if (!empty($request->bill_zip)) {
            $orderpayment->b_zip = $request->bill_zip;
            array_push($zscArr, $request->bill_zip);
        } else {
            $orderpayment->b_zip = null;
        }
        if (!empty($request->bill_state)) {
            $orderpayment->b_state = $request->bill_state;
            array_push($zscArr, $request->bill_state);
        } else {
            $orderpayment->b_state = null;
        }
        if (!empty($request->bill_city)) {
            $orderpayment->b_city = $request->bill_city;
            array_push($zscArr, $request->bill_city);
        } else {
            $orderpayment->b_city = null;
        }
        $orderpayment->b_zsc = $zsc;
        $orderpayment->save();
        // }
        if ($request->paymentmethod == 'Credit Card') {
            $creditcard = new creditcard;
            $creditcard->orderId = $request->orderid;
            if (!empty($request->cardtype)) {
                $creditcard->card_type = $request->cardtype;
            } else {
                $creditcard->card_type = null;
            }
            if (!empty($request->cardfirstname)) {
                $creditcard->card_first_name = $request->cardfirstname;
            } else {
                $creditcard->card_first_name = null;
            }
            if (!empty($request->cardlastname)) {
                $creditcard->card_last_name = $request->cardlastname;
            } else {
                $creditcard->card_last_name = null;
            }

            if (!empty($request->cardno)) {
                $creditcard->card_no = $request->cardno;
            } else {
                $creditcard->card_no = null;
            }
            if (!empty($request->security)) {
                $creditcard->card_security = $request->security;
            } else {
                $creditcard->card_security = null;
            }
            if (!empty($request->expirydate)) {
                $creditcard->card_expiry_date = $request->expirydate;
            } else {
                $creditcard->card_expiry_date = null;
            }
            if (!empty($request->bill_add)) {
                $creditcard->billing_address = $request->bill_add;
            } else {
                $creditcard->billing_address = null;
            }
            $zsc = NULL;
            $zscArr = [];
            if (!empty($request->bill_zip)) {
                $creditcard->b_zip = $request->bill_zip;
                array_push($zscArr, $request->bill_zip);
            } else {
                $creditcard->b_zip = null;
            }
            if (!empty($request->bill_state)) {
                $creditcard->b_state = $request->bill_state;
                array_push($zscArr, $request->bill_state);
            } else {
                $creditcard->b_state = null;
            }
            if (!empty($request->bill_city)) {
                $creditcard->b_city = $request->bill_city;
                array_push($zscArr, $request->bill_city);
            } else {
                $creditcard->b_city = null;
            }
            $creditcard->b_zsc = $zsc;
            $creditcard->save();
        }



        $profit = profit::where('order_id', '=', $request->orderid)->first();
        if (!empty($profit)) {
            $profit->profit = $request->profit;
            $profit->save();
        } else {
            $addprofit = new profit();
            $addprofit->order_id = $request->orderid;
            $addprofit->profit = $request->profit;
            $addprofit->save();
        }

        return redirect()->back();
    }



    public function change_order_price(Request $request)
    {

        $autoorder = AutoOrder::find($request->order_price_change);
        $autoorder_pay = orderpayment::where('orderId', $request->order_price_change)->first();
        if (!empty($autoorder)) {
            $autoorder->payment = isset($request->book_price) ? $request->book_price : $autoorder->payment;
            $autoorder->cod_cop = isset($request->cod_cop) ? $request->cod_cop : $autoorder->cod_cop;

            if ($request->payment_method) {

                $autoorder->payment_method2 = isset($request->payment_method) ? $request->payment_method : $autoorder->payment_method2;
                $autoorder->payment_type = isset($request->payment_type) ? $request->payment_type : $autoorder->payment_type;
                $autoorder->deposit_amount = isset($request->deposit) ? $request->deposit : $autoorder->deposit_amount;
                $autoorder->storage_fees = isset($request->storage_fees) ? $request->storage_fees : $autoorder->storage_fees;
                $autoorder->other_fees = isset($request->other_fees) ? $request->other_fees : $autoorder->other_fees;
                $autoorder->owes = isset($request->owes) ? $request->owes : $autoorder->owes;
                $autoorder->owes_money = isset($request->owes) ? 1 : $autoorder->owes_money;
                $autoorder->pay_carrier = isset($request->driver_price) ? $request->driver_price : $autoorder->pay_carrier;
                $autoorder_pay->profit = isset($request->profit) ? $request->profit : 0;
                $autoorder_pay->confirmation = isset($request->confirmation) ? 1 : 0;
                $autoorder_pay->detail = isset($request->detail) ? $request->detail : "";

                $profit = profit::where('order_id', '=', $request->order_price_change)->first();
                if (!empty($profit)) {
                    $profit->profit = $request->profit;
                    $profit->save();
                } else {
                    $addprofit = new profit();
                    $addprofit->order_id = $request->order_price_change;
                    $addprofit->profit = $request->profit;
                    $addprofit->save();
                }

                $save_payment_log = new payment_log();
                $save_payment_log->user_id = Auth::user()->id;
                $save_payment_log->orderId = $request->order_price_change;
                $save_payment_log->pay_type = $request->cardd_pay_type ?? NULL;
                $save_payment_log->pay_from = $request->cardd_pay_from ?? NULL;
                $save_payment_log->pay_location = $request->cardd_pay_on ?? NULL;
                $save_payment_log->pay_method = $request->payment_type ?? NULL;
                if ($request->payment_type == 'card') {
                    if (!empty($request->cardd_type)) {
                        $save_payment_log->card_type = $request->cardd_type;
                    } else {
                        $save_payment_log->card_type = null;
                    }
                    if (!empty($request->cardd_first_name)) {
                        $save_payment_log->card_first_name = $request->cardd_first_name;
                    } else {
                        $save_payment_log->card_first_name = null;
                    }
                    if (!empty($request->cardd_last_name)) {
                        $save_payment_log->card_last_name = $request->cardd_last_name;
                    } else {
                        $save_payment_log->card_last_name = null;
                    }
                    if (!empty($request->cardd_number)) {
                        $save_payment_log->card_number = $request->cardd_number;
                    } else {
                        $save_payment_log->card_number = null;
                    }
                    if (!empty($request->cardd_sec)) {
                        $save_payment_log->card_security = $request->cardd_sec;
                    } else {
                        $save_payment_log->card_security = null;
                    }
                    if (!empty($request->cardd_exp)) {
                        $save_payment_log->expiry_date = $request->cardd_exp;
                    } else {
                        $save_payment_log->expiry_date = null;
                    }

                    $creditcard = new creditcard;
                    $creditcard->orderId = $request->order_price_change;
                    if (!empty($request->cardd_type)) {
                        $creditcard->card_type = $request->cardd_type;
                    } else {
                        $creditcard->card_type = null;
                    }
                    if (!empty($request->cardd_first_name)) {
                        $creditcard->card_first_name = $request->cardd_first_name;
                    } else {
                        $creditcard->card_first_name = null;
                    }
                    if (!empty($request->cardd_last_name)) {
                        $creditcard->card_last_name = $request->cardd_last_name;
                    } else {
                        $creditcard->card_last_name = null;
                    }

                    if (!empty($request->cardd_number)) {
                        $creditcard->card_no = $request->cardd_number;
                    } else {
                        $creditcard->card_no = null;
                    }
                    if (!empty($request->cardd_sec)) {
                        $creditcard->card_security = $request->cardd_sec;
                    } else {
                        $creditcard->card_security = null;
                    }
                    if (!empty($request->cardd_exp)) {
                        $creditcard->card_expiry_date = $request->cardd_exp;
                    } else {
                        $creditcard->card_expiry_date = null;
                    }
                    $zsc = NULL;
                    $zscArr = [];
                    if (!empty($request->bill_zip)) {
                        $creditcard->b_zip = $request->bill_zip;
                        array_push($zscArr, $request->bill_zip);
                    } else {
                        $creditcard->b_zip = null;
                    }
                    if (!empty($request->bill_state)) {
                        $creditcard->b_state = $request->bill_state;
                        array_push($zscArr, $request->bill_state);
                    } else {
                        $creditcard->b_state = null;
                    }
                    if (!empty($request->bill_city)) {
                        $creditcard->b_city = $request->bill_city;
                        array_push($zscArr, $request->bill_city);
                    } else {
                        $creditcard->b_city = null;
                    }
                    $creditcard->b_zsc = $zsc;
                    $creditcard->save();

                    $orderpayment = new orderpayment();
                    $orderpayment->orderId = $request->order_price_change;
                    if (!empty($request->cardd_type)) {
                        $orderpayment->card_type = $request->cardd_type;
                    } else {
                        $orderpayment->card_type = null;
                    }
                    if (!empty($request->cardd_first_name)) {
                        $orderpayment->card_first_name = $request->cardd_first_name;
                    } else {
                        $orderpayment->card_first_name = null;
                    }
                    if (!empty($request->cardd_last_name)) {
                        $orderpayment->card_last_name = $request->cardd_last_name;
                    } else {
                        $orderpayment->card_last_name = null;
                    }

                    if (!empty($request->cardd_number)) {
                        $orderpayment->card_no = $request->cardd_number;
                    } else {
                        $orderpayment->card_no = null;
                    }
                    if (!empty($request->cardd_sec)) {
                        $orderpayment->card_security = $request->cardd_sec;
                    } else {
                        $orderpayment->card_security = null;
                    }
                    if (!empty($request->cardd_exp)) {
                        $orderpayment->card_expiry_date = $request->cardd_exp;
                    } else {
                        $orderpayment->card_expiry_date = null;
                    }
                    $zsc = NULL;
                    $zscArr = [];
                    if (!empty($request->bill_zip)) {
                        $orderpayment->b_zip = $request->bill_zip;
                        array_push($zscArr, $request->bill_zip);
                    } else {
                        $orderpayment->b_zip = null;
                    }
                    if (!empty($request->bill_state)) {
                        $orderpayment->b_state = $request->bill_state;
                        array_push($zscArr, $request->bill_state);
                    } else {
                        $orderpayment->b_state = null;
                    }
                    if (!empty($request->bill_city)) {
                        $orderpayment->b_city = $request->bill_city;
                        array_push($zscArr, $request->bill_city);
                    } else {
                        $orderpayment->b_city = null;
                    }
                    $orderpayment->b_zsc = $zsc;
                    $orderpayment->payment_status = $request->cardd_pay_type == 'Send' ? 'Unpaid' : 'Paid';
                    $orderpayment->profit = isset($request->profit) ? $request->profit : 0;
                    $orderpayment->confirmation = isset($request->confirmation) ? 1 : 0;
                    if (!empty($request->detail)) {
                        $orderpayment->detail = $request->detail;
                    } else {
                        $orderpayment->detail = null;
                    }
                    $orderpayment->save();
                }
                if (!empty($request->charge_amount)) {
                    $save_payment_log->amount = $request->charge_amount;
                } else {
                    $save_payment_log->amount = 0;
                }
                if (!empty($request->owes)) {
                    $save_payment_log->owes_money = $request->owes;
                } else {
                    $save_payment_log->owes_money = 0;
                }
                if (!empty($request->detail)) {
                    $save_payment_log->add_information = $request->detail;
                } else {
                    $save_payment_log->add_information = null;
                }
                $save_payment_log->save();



                $this->createOrderHistory($autoorder->id, 'payment_method2', 'Payment Method', $autoorder->payment_method2);
                $this->createOrderHistory($autoorder->id, 'payment_type', 'Payment Type', $autoorder->payment_type);
                $this->createOrderHistory($autoorder->id, 'deposit_amount', 'Deposit', $autoorder->deposit_amount);
                $this->createOrderHistory($autoorder->id, 'owes', 'Owes', $autoorder->owes);
                $this->createOrderHistory($autoorder->id, 'driver_price', 'Driver Price', $autoorder->driver_price);
            }


            $this->createOrderHistory($autoorder->id, 'payment', 'Book-Price', $autoorder->payment);
            $this->createOrderHistory($autoorder->id, 'cod_cop', 'COD-COP', $autoorder->cod_cop);
            $this->createOrderHistory($autoorder->id, 'listed_price', 'Listed Price', $autoorder->listed_price);


            $autoorder->save();
            $autoorder_pay->save();

            return redirect()->back();
        } else {
            echo "FAILED";
        }
    }


    public function get_central()
    {
        $gets = cachee::first();
        return view('main.phone_quote.management.get_central', compact('gets'));
    }

    public function show_central()
    {
        $gets = cachee::first();
        return view('main.phone_quote.management.show_central', compact('gets'));
    }

    public function save_central(Request $request)
    {
        $gets = cachee::first();
        $save1 = cachee::truncate();

        $save = new cachee();
        $save->cachee = $request->cachee;
        $save->get_ses = $request->get_ses;
        $save->save();
        Session::flash('flash_message', 'Data Successfully Saved');
        return redirect()->back();
    }

    public function listed_sheet(Request $request, $id)
    {
        $model = new SheetDetails();
        $model->fill($request->all());
        $model->auth_id = Auth::user()->id;
        $model->orderId = $id;
        $model->pstatus = 9;
        $model->save();
        return 'true';
    }

    public function dispatch_sheet(Request $request, $id)
    {
        $model = new SheetDetails();
        $model->fill($request->all());
        $model->auth_id = Auth::user()->id;
        $model->orderId = $id;
        $model->pstatus = 10;
        $model->save();
        return 'true';
    }

    public function pickedup_sheet(Request $request, $id)
    {
        $model = new SheetDetails();
        $model->fill($request->all());
        $model->auth_id = Auth::user()->id;
        $model->orderId = $id;
        $model->pstatus = 11;
        $model->save();
        return 'true';
    }

    public function auction_pickedup_sheet(Request $request, $id)
    {
        $model = new SheetDetails();
        $model->fill($request->all());
        $model->auth_id = Auth::user()->id;
        $model->orderId = $id;
        $model->pstatus = 11;
        $model->save();
        return 'true';
    }

    public function delivery_sheet(Request $request, $id)
    {
        $model = new SheetDetails();
        $model->fill($request->all());
        $model->auth_id = Auth::user()->id;
        $model->orderId = $id;
        $model->pstatus = 12;
        $model->save();
        return 'true';
    }

    public function completed_sheet(Request $request, $id)
    {
        $model = new SheetDetails();
        $model->fill($request->all());
        $model->auth_id = Auth::user()->id;
        $model->orderId = $id;
        $model->pstatus = 13;
        $model->save();
        return 'true';
    }

    public function recordsCityZip(Request $request)
    {
        $id = $request->orderID;
        $ocity = $request->ocity;
        $dcity = $request->dcity;
        $ozip = $request->ozip;
        $dzip = $request->dzip;
        $vehicle = $request->vehicle;
        $vehicleName = $request->vehicleName;

        $vechileArr = [];
        $vechileNameArr = [];
        if (strpos($vehicle, ',')) {
            $vechileArr[] = explode(',', $vehicle);
        } else {
            $vechileArr[] = $vehicle;
        }
        if (strpos($vehicleName, ',')) {
            $vechileNameArr[] = explode(',', $vehicleName);
            $newVehicleName = str_replace(',', ' | ', $vehicleName);
        } else {
            $vechileNameArr[] = $vehicleName;
            $newVehicleName = $vehicleName;
        }


        $data = '';
        $msg = '';
        $city = '';
        $pricePerMile = [];
        if ($ozip && $dzip && $ocity && $dcity && $vehicle && $vehicleName) {
            // echo "<pre>";print_r($ocity);exit();
            $zip = AutoOrder::where(function ($q) use ($ozip) {
                $q->where('originstate', 'LIKE', '%' . $ozip . '%')->orWhere('originzip', 'LIKE', '%' . $ozip . '%');
            })
                ->where('destinationzip', 'LIKE', '%' . $dzip . '%')
                ->where(function ($q2) {
                    $q2->where('driver_price', '<>', NULL)
                        ->orWhere('driver_price', '<>', 'NULL')
                        ->orWhere('driver_price', '<>', ' ')
                        ->orWhere('driver_price', '<>', '');
                })
                ->where('id', '<>', $id)
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->get();

            // echo "<pre>";print_r($zip);exit();
            $zipIds = [];
            array_push($zipIds, $id);
            if (count($zip) > 0) {
                foreach ($zip as $key => $value) {
                    array_push($zipIds, $value->id);
                }
            }
            // echo "<pre>";print_r($zipIds);exit();

            if (count($zip) < 3) {
                // echo "<pre>";print_r($zip);exit();
                $zipCount = 5 - count($zip);
                $city = AutoOrder::where('origincity', 'LIKE', '%' . $ocity . '%')
                    ->where('destinationcity', 'LIKE', '%' . $dcity . '%')
                    ->where(function ($q2) {
                        $q2->where('driver_price', '<>', NULL)
                            ->orWhere('driver_price', '<>', 'NULL')
                            ->orWhere('driver_price', '<>', ' ')
                            ->orWhere('driver_price', '<>', '');
                    })
                    ->whereNotIn('id', $zipIds)
                    ->orderBy('created_at', 'DESC')
                    ->limit($zipCount)
                    ->get();

                if (count($city) < 3) {
                    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $ocity . "," . $ozip . "&destinations=" . $dcity . "," . $dzip . "&sensor=false&region=US&key=AIzaSyCidNuG4zb5tptrPvbyeYGCfgxn-5p-PQk";
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    $response = curl_exec($ch);
                    curl_close($ch);
                    $response_a = json_decode($response);
                    $miles = '';
                    $total_miles = '';
                    $price = '';
                    $carrierPrice = '';
                    $bookedPrice = '';
                    $removeCommas = '';
                    $originadd = '';
                    $destinationadd = '';
                    $milesArr = [];
                    $newMiles = 0;
                    $newMilesMinusHundred = 0;
                    // echo "<pre>";
                    // print_r($response_a);
                    // exit();
                    // if($response_a)
                    // {
                    //     if(isset($response_a->destination_addresses[0]) && isset($response_a->origin_addresses[0]))
                    //     {
                    //         if(isset($response_a->rows))
                    //         {
                    //             if(isset($response_a->rows[0]->elements))
                    //             {
                    //                 if(isset($response_a->rows[0]->elements[0]->distance))
                    //                 {
                    //                     if(isset($response_a->rows[0]->elements[0]->distance->text))
                    //                     {
                    //                         $originadd = $response_a->origin_addresses[0];
                    //                         $destinationadd = $response_a->destination_addresses[0];
                    //                         $total_miles = $response_a->rows[0]->elements[0]->distance->text;
                    //                         $removeCommas = preg_replace('/[,]+/', '', $total_miles);
                    //                         $milesArr = explode(' ',$removeCommas);
                    //                         $newMiles = round($milesArr[0] * 0.6214);
                    //                         $newMilesMinusHundred = $newMiles - 100;
                    //                     }
                    //                 }
                    //             }
                    //         }
                    //     }
                    // }


                    $miles = MilePrice::where('mile', '>', $newMilesMinusHundred)
                        ->where('mile', '<', $newMiles)->first();
                    if ($miles) {
                        $price = $miles->mile_price;
                        $carrierPrice = $newMiles * $price;
                        $bookedPrice = $carrierPrice + $miles->commission;

                        $pricePerMile['vechileNameArr'] = $vechileNameArr;
                        $pricePerMile['vechileArr'] = $vechileArr;
                        $pricePerMile['newVehicleName'] = $newVehicleName;
                        $pricePerMile['originadd'] = $originadd;
                        $pricePerMile['destinationadd'] = $destinationadd;
                        $pricePerMile['newMiles'] = $newMiles;
                        $pricePerMile['price'] = $price;
                        $pricePerMile['carrierPrice'] = $carrierPrice;
                        $pricePerMile['bookedPrice'] = $bookedPrice;
                    }
                }
            }
            if (count($zip) < 1 && count($city) < 1 && empty($pricePerMile)) {
                return "<h1 style='text-align:center;'>No Record Found!</h1>";
            }
            // echo "<pre>";
            // print_r($zip);
            // echo "<pre>";
            // print_r($city);
            // exit();
            return view('main.phone_quote.new.request_price', compact('zip', 'city', 'pricePerMile'));
        } else {
            return "<h1 style='text-align:center;'>Fill the origin and destination address and vehicle information!</h1>";
        }
    }

    public function newAuctionDetail(Request $request)
    {
        $zip = explode(',', $request->zip_code);
        $data = '';
        if (isset($zip[2])) {
            if ($request->terminal == 2) {
                $data = auction_detail::where('auction_type', '1')->where('zip_code', $zip[2])->first();
            } else if ($request->terminal == 3) {
                $data = auction_detail::where('auction_type', '3')->where('zip_code', $zip[2])->first();
            } else if ($request->terminal == 4) {
                $data = auction_detail::where('auction_type', '2')->where('zip_code', $zip[2])->first();
            }
        }
        return response()->json([
            'data' => $data,
            'status' => true,
            'status_code' => 200
        ]);
    }

    public function websiteShipa1Quote(Request $request)
    {
        // â”€â”€ Blocked IPs â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        $blockedIps = [
            '185.150.191.208',
        ];

        // Check both the real request IP and the IP field sent in the payload
        $requestIp = $request->ip();
        $payloadIp = $request->input('ip', '');

        if (in_array($requestIp, $blockedIps) || in_array($payloadIp, $blockedIps)) {
            Log::warning('Blocked IP attempted website-quote', [
                'request_ip' => $requestIp,
                'payload_ip' => $payloadIp,
            ]);
            return response()->json(['message' => 'Forbidden'], 403);
        }
        // â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

        try {
            $user = DailyQoute::with('user.userRole')
                ->where('total_qoute', '>', 0)
                ->where('date', date('Y-m-d'))
                ->whereHas('user', function ($q) {
                    $q->where('deleted', 0)->where('is_login', 1);
                })
                ->whereHas('user.userRole', function ($q) {
                    $q->where('name', 'Order Taker')->orWhere('name', 'Seller Agent');
                })
                ->orderBy('total_qoute', 'DESC')
                ->first();

            if (empty($user)) {
                $user = DailyQoute::with('user.userRole')
                    ->where('date', date('Y-m-d'))
                    ->whereHas('user', function ($q) {
                        $q->where('deleted', 0)->where('is_login', 1);
                    })
                    ->whereHas('user.userRole', function ($q) {
                        $q->where('name', 'Order Taker')->orWhere('name', 'Seller Agent');
                    })
                    ->orderBy('total_qoute', 'DESC')
                    ->first();
            }

            $manager_id = NULL;
            $ot_ids = [];
            if (isset($user->user->id)) {
                if ($user->user->order_taker_quote == 2) {
                    $m = OrderTakerQouteAccess::where('ot_ids', $user->user->id)->first();
                    if (isset($m->id)) {
                        $manager_id = $m->manager_id;
                        $ot = OrderTakerQouteAccess::where('manager_id', $m->manager_id)->get();
                        if (isset($ot[0])) {
                            foreach ($ot as $key => $val) {
                                array_push($ot_ids, $val->ot_ids);
                            }
                        }
                        $daily = DailyQoute::where('user_id', $user->user->id)->whereDate('date', date('Y-m-d'))->first();
                        if (isset($daily->id)) {
                            if ($daily->total_qoute > 0) {
                                $daily->total_qoute = $daily->total_qoute - 1;
                                $daily->save();
                            }
                        }
                    }
                }

                // $last = AutoOrder::where('paneltype', 2)->orderBy('id', 'DESC')->first();
                // $last_user_id = $last->order_taker_id;

                // $setOrderTaker = User::with('userRole')
                //     ->where('order_taker_quote', 2)
                //     ->whereHas('userRole', function ($q) {
                //         $q->where('name', 'Order Taker')->orWhere('name', 'Seller Agent');
                //     })
                //     ->get();

                // $user_iddd = $setOrderTaker->id;
                // $user_iddd = $user->user->id;
            } else {
                $user = User::with('userRole')
                    ->where('deleted', 0)
                    ->whereHas('userRole', function ($q) {
                        $q->where('name', 'Order Taker')->orWhere('name', 'Seller Agent');
                    })
                    ->inRandomOrder()
                    ->first();

                // $user_iddd = $user->id;
            }

            $last = AutoOrder::where('paneltype', 2)->orderBy('id', 'DESC')->first();
            $last_user_id = $last ? $last->order_taker_id : null;

            $eligibleUsers = User::with('userRole', 'user_setting')
                ->where('role', 2)
                ->where('deleted', 0)
                ->whereHas('user_setting', function ($q) {
                    $q->where('penal_type', 2);
                })
                ->orderBy('id')
                ->get();

            // dd($eligibleUsers->toArray());

            $nextUser = null;
            if ($last_user_id) {
                $nextUser = $eligibleUsers->first(function ($user) use ($last_user_id) {
                    return $user->id > $last_user_id;
                });
            }

            if (!$nextUser) {
                $nextUser = $eligibleUsers->first();
            }

            if ($nextUser) {
                $user_iddd = $nextUser->id;
            }


            $order = AutoOrder::orderBy('id', 'DESC')->first();
            $data = new AutoOrder;
            $data->id = $order->id + 1;
            $data->order_taker_id = $user_iddd;
            $data->oname = $request['oname'];
            $data->oemail = $request['oemail'];
            $data->ophone = $request['ophone'];
            $data->ymk = $request['ymk'];
            $data->year = $request['year'];
            $data->type = $request['type'];
            $data->vehicle_opt = $request['vehicle_opt'];
            $data->model = $request['model'];
            $data->make = $request['make'];
            $data->condition = $request['condition'];
            $data->originzsc = $request['originzsc'];
            $data->originzip = $request['originzip'];
            $data->originstate = $request['originstate'];
            $data->origincity = $request['origincity'];
            $data->destinationzsc = $request['destinationzsc'];
            $data->destinationzip = $request['destinationzip'];
            $data->destinationstate = $request['destinationstate'];
            $data->destinationcity = $request['destinationcity'];
            $data->add_info = $request['add_info'];
            $data->transport = $request['transport'];
            $data->shippingdate = $request['shippingdate'];
            $data->car_type = $request['car_type'];
            $data->paneltype = $request['paneltype'];
            $data->cname = $request['cname'];
            $data->cemail = $request['cemail'];
            $data->main_ph = $request['main_ph'];
            $data->length_ft = $request['length_ft'];
            $data->length_in = $request['length_in'];
            $data->width_ft = $request['width_ft'];
            $data->width_in = $request['width_in'];
            $data->height_ft = $request['height_ft'];
            $data->height_in = $request['height_in'];
            $data->weight = $request['weight'];
            $data->load_method = $request['load_method'];
            $data->unload_method = $request['unload_method'];
            $data->ip_address = $request['ip'];
            $data->ip_details = $request['ip_details'];
            $data->ipcity = $request['ipcity'];
            $data->ipregion = $request['ipregion'];
            $data->ipcountry = $request['ipcountry'];
            $data->iploc = $request['iploc'];
            $data->ippostal = $request['ippostal'];
            $data->image = $request['image'];
            $data->available_at_auction = $request['available_at_auction'];
            $data->modification = $request['modification'];
            $data->modify_info = $request['modify_info'];
            $data->link = $request['link'];
            $data->rv_type = $request['rv_type'];
            $data->boat_on_trailer = $request['boat_on_trailer'];
            $data->pstatus = 0;
            $data->source = 'ShipA1';
            $data->roro = isset($request['roro']) ? $request['roro'] : NULL;
            $data->heavy_type = isset($request['heavy_type']) ? $request['heavy_type'] : NULL;
            $data->category = isset($request['category']) ? $request['category'] : NULL;
            $data->subcategory = isset($request['subcategory']) ? $request['subcategory'] : NULL;
            $data->payment = isset($request['offer_open']) ? $request['offer_open'] : 0;
            $data->request_hauling = isset($request['request_hauling']) ? (int) $request['request_hauling'] : 0;
            $data->save();

            $data2 = new orderpayment;
            $data2->orderId = $data->id;
            $data2->save();

            $data2 = new creditcard;
            $data2->orderId = $data->id;
            $data2->save();

            $data3 = new report;
            $data3->userId = 1;
            $data3->orderId = $data->id;
            $data3->pstatus = 0;
            $data3->save();

            $data4 = new singlereport;
            $data4->userId = 1;
            $data4->orderId = $data->id;
            $data4->pstatus = 0;
            $data4->save();

            $data5 = new order_freight;
            $data5->order_id = $data->id;
            $data5->frieght_class = $request['frieght_class'];
            $data5->equipment_type = $request['equipment_type'];
            $data5->trailer_specification = $request['trailer_specification'];
            $data5->ex_pickup_date = $request['ex_pickup_date'];
            $data5->ex_pickup_time = $request['ex_pickup_time'];
            $data5->ex_delivery_date = $request['ex_delivery_date'];
            $data5->ex_delivery_time = $request['ex_delivery_time'];
            $data5->commodity_detail = $request['commodity_detail'];
            $data5->commodity_unit = $request['commodity_unit'];
            $data5->pick_up_services = $request['pick_up_services'];
            $data5->deliver_services = $request['deliver_services'];
            $data5->shipment_prefences = $request['category'];
            $data5->stackable = $request['stackable'];
            $data5->hazardous = $request['hazardous'];
            $data5->handling_unit = $request['handling_unit'];
            $data5->protect_from_freezing = $request['protect_from_freezing'];
            $data5->sort_segregate = $request['sort_segregate'];
            $data5->blind_shipment = $request['blind_shipment'];
            $data5->save();

            $this->expected_date($data->id, 1, '0', '');
            return response()->json([
                'data' => $data,
                'status' => true,
                'status_code' => 201
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while processing the request.',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }

    // public function websiteShipa1Quote(Request $request)
    // {
    //     // return response()->json([
    //     //             'message' => 'Checkcheck',
    //     //             'data' => $request->all(),
    //     //         ], 200);
    //     $user = DailyQoute::with('user.userRole')->where('total_qoute', '>', 0)->where('date', date('Y-m-d'))
    //         ->whereHas('user', function ($q) {
    //             $q->where('deleted', 0)->where('is_login', 1);
    //         })->whereHas('user.userRole', function ($q) {
    //             $q->where('name', 'Order Taker')->orWhere('name', 'Seller Agent');
    //         })->orderBy('total_qoute', 'DESC')->first();

    //     if (empty($user)) {
    //         $user = DailyQoute::with('user.userRole')->where('date', date('Y-m-d'))
    //             ->whereHas('user', function ($q) {
    //                 $q->where('deleted', 0)->where('is_login', 1);
    //             })->whereHas('user.userRole', function ($q) {
    //                 $q->where('name', 'Order Taker')->orWhere('name', 'Seller Agent');
    //             })->orderBy('total_qoute', 'DESC')->first();
    //     }

    //     $manager_id = NULL;
    //     $ot_ids = [];
    //     if (isset($user->user->id)) {
    //         if ($user->user->order_taker_quote == 2) {
    //             $m = OrderTakerQouteAccess::where('ot_ids', $user->user->id)->first();
    //             if (isset($m->id)) {
    //                 $manager_id = $m->manager_id;
    //                 $ot = OrderTakerQouteAccess::where('manager_id', $m->manager_id)->get();
    //                 if (isset($ot[0])) {
    //                     foreach ($ot as $key => $val) {
    //                         array_push($ot_ids, $val->ot_ids);
    //                     }
    //                 }
    //                 $daily = DailyQoute::where('user_id', $user->user->id)->whereDate('date', date('Y-m-d'))->first();
    //                 if (isset($daily->id)) {
    //                     if ($daily->total_qoute > 0) {
    //                         $daily->total_qoute = $daily->total_qoute - 1;
    //                         $daily->save();
    //                     }
    //                 }
    //             }
    //         }
    //         $user_iddd = $user->user->id;
    //     } else {
    //         $user = User::with('userRole')
    //             ->where('deleted', 0)
    //             ->whereHas('userRole', function ($q) {
    //                 $q->where('name', 'Order Taker')->orWhere('name', 'Seller Agent');
    //             })
    //             ->inRandomOrder()->first();

    //         $user_iddd = $user->id;
    //     }
    //     // echo "<pre>";
    //     // print_r($request['oname']);
    //     // exit();
    //     $order = AutoOrder::orderBy('id', 'DESC')->first();
    //     $data = new AutoOrder;
    //     $data->id = $order->id + 1;
    //     $data->order_taker_id = $user_iddd;
    //     $data->oname = $request['oname'];
    //     $data->oemail = $request['oemail'];
    //     $data->ophone = $request['ophone'];
    //     $data->ymk = $request['ymk'];
    //     $data->year = $request['year'];
    //     $data->type = $request['type'];
    //     $data->vehicle_opt = $request['vehicle_opt'];
    //     $data->model = $request['model'];
    //     $data->make = $request['make'];
    //     $data->condition = $request['condition'];
    //     $data->originzsc = $request['originzsc'];
    //     $data->originzip = $request['originzip'];
    //     $data->originstate = $request['originstate'];
    //     $data->origincity = $request['origincity'];
    //     $data->destinationzsc = $request['destinationzsc'];
    //     $data->destinationzip = $request['destinationzip'];
    //     $data->destinationstate = $request['destinationstate'];
    //     $data->destinationcity = $request['destinationcity'];
    //     $data->add_info = $request['add_info'];
    //     $data->transport = $request['transport'];
    //     $data->shippingdate = $request['shippingdate'];
    //     $data->car_type = $request['car_type'];
    //     $data->paneltype = $request['paneltype'];
    //     $data->cname = $request['cname'];
    //     $data->cemail = $request['cemail'];
    //     $data->main_ph = $request['main_ph'];
    //     $data->length_ft = $request['length_ft'];
    //     $data->length_in = $request['length_in'];
    //     $data->width_ft = $request['width_ft'];
    //     $data->width_in = $request['width_in'];
    //     $data->height_ft = $request['height_ft'];
    //     $data->height_in = $request['height_in'];
    //     $data->weight = $request['weight'];
    //     $data->load_method = $request['load_method'];
    //     $data->unload_method = $request['unload_method'];
    //     $data->ip_address = $request['ip'];
    //     $data->ipcity = $request['ipcity'];
    //     $data->ipregion = $request['ipregion'];
    //     $data->ipcountry = $request['ipcountry'];
    //     $data->iploc = $request['iploc'];
    //     $data->ippostal = $request['ippostal'];
    //     $data->image = $request['image'];
    //     $data->available_at_auction = $request['available_at_auction'];
    //     $data->modification = $request['modification'];
    //     $data->modify_info = $request['modify_info'];
    //     $data->link = $request['link'];
    //     $data->pstatus = 0;
    //     $data->source = 'ShipA1';
    //     $data->roro = isset($request['roro']) ? $request['roro'] : NULL;
    //     $data->category = isset($request['category']) ? $request['category'] : NULL;
    //     $data->subcategory = isset($request['subcategory']) ? $request['subcategory'] : NULL;
    //     $data->save();

    //     $data2 = new orderpayment;
    //     $data2->orderId = $data->id;
    //     $data2->save();

    //     $data2 = new creditcard;
    //     $data2->orderId = $data->id;
    //     $data2->save();

    //     $data3 = new report;
    //     $data3->userId = 1;
    //     $data3->orderId = $data->id;
    //     $data3->pstatus = 0;
    //     $data3->save();

    //     $data4 = new singlereport;
    //     $data4->userId = 1;
    //     $data4->orderId = $data->id;
    //     $data4->pstatus = 0;
    //     $data4->save();

    //     // return response()->json([
    //     //             'message' => 'Checkcheck',
    //     //             'data' => $request->all(),
    //     //             'trailer_specification' => $request['trailer_specification'],
    //     //             'equipment_type' => $request['equipment_type'],
    //     //             'pick_up_services' => $request['pick_up_services'],
    //     //             'deliver_services' => $request['deliver_services'],
    //     //         ], 200);

    //     $data5 = new order_freight;
    //     $data5->order_id = $data->id;
    //     $data5->frieght_class = $request['frieght_class'];
    //     $data5->equipment_type = $request['equipment_type'];
    //     $data5->trailer_specification = $request['trailer_specification'];
    //     $data5->ex_pickup_date = $request['ex_pickup_date'];
    //     $data5->ex_pickup_time = $request['ex_pickup_time'];
    //     $data5->ex_delivery_date = $request['ex_delivery_date'];
    //     $data5->ex_delivery_time = $request['ex_delivery_time'];
    //     $data5->commodity_detail = $request['commodity_detail'];
    //     $data5->commodity_unit = $request['commodity_unit'];
    //     $data5->pick_up_services = $request['pick_up_services'];
    //     $data5->deliver_services = $request['deliver_services'];
    //     $data5->shipment_prefences = $request['category'];
    //     $data5->stackable = isset($request['stackable']) ? $request['stackable'] : 0;
    //     $data5->hazardous = isset($request['hazardous']) ? $request['hazardous'] : 0;
    //     $data5->protect_from_freezing = isset($request['protect_from_freezing']) ? $request['protect_from_freezing'] : 0;
    //     $data5->sort_segregate = isset($request['sort_segregate']) ? $request['sort_segregate'] : 0;
    //     $data5->blind_shipment = isset($request['blind_shipment']) ? $request['blind_shipment'] : 0;
    //     $data5->save();

    //     // return response()->json([
    //     //             'message' => 'Checkcheck',
    //     //             'data5' => $data5,
    //     //         ], 200);


    //     $this->expected_date($data->id, 1, '0', '');

    //     return "SAVE";
    // }

    public function websiteShipa1QuoteVehicle(Request $request)
    {
        $user = DailyQoute::with('user.userRole')->where('total_qoute', '>', 0)->where('date', date('Y-m-d'))
            ->whereHas('user', function ($q) {
                $q->where('deleted', 0)->where('is_login', 1);
            })->whereHas('user.userRole', function ($q) {
                $q->where('name', 'Order Taker')->orWhere('name', 'Seller Agent');
            })->orderBy('total_qoute', 'DESC')->first();

        if (empty($user)) {
            $user = DailyQoute::with('user.userRole')->where('date', date('Y-m-d'))
                ->whereHas('user', function ($q) {
                    $q->where('deleted', 0)->where('is_login', 1);
                })->whereHas('user.userRole', function ($q) {
                    $q->where('name', 'Order Taker')->orWhere('name', 'Seller Agent');
                })->orderBy('total_qoute', 'DESC')->first();
        }

        $manager_id = NULL;
        $ot_ids = [];
        if (isset($user->user->id)) {
            if ($user->user->order_taker_quote == 2) {
                $m = OrderTakerQouteAccess::where('ot_ids', $user->user->id)->first();
                if (isset($m->id)) {
                    $manager_id = $m->manager_id;
                    $ot = OrderTakerQouteAccess::where('manager_id', $m->manager_id)->get();
                    if (isset($ot[0])) {
                        foreach ($ot as $key => $val) {
                            array_push($ot_ids, $val->ot_ids);
                        }
                    }
                    $daily = DailyQoute::where('user_id', $user->user->id)->whereDate('date', date('Y-m-d'))->first();
                    if (isset($daily->id)) {
                        if ($daily->total_qoute > 0) {
                            $daily->total_qoute = $daily->total_qoute - 1;
                            $daily->save();
                        }
                    }
                }
            }
            $user_iddd = $user->user->id;
        } else {
            $user = User::with('userRole')
                ->where('deleted', 0)
                ->whereHas('userRole', function ($q) {
                    $q->where('name', 'Order Taker')->orWhere('name', 'Seller Agent');
                })
                ->inRandomOrder()->first();

            $user_iddd = $user->id;
        }
        // echo "<pre>";
        // print_r($request['oname']);
        // exit();
        $order = AutoOrder::orderBy('id', 'DESC')->first();
        $data = new AutoOrder;
        $data->id = $order->id + 1;
        $data->order_taker_id = $user_iddd;
        $data->oname = $request['oname'];
        $data->oemail = $request['oemail'];
        $data->ophone = $request['ophone'];
        $data->ymk = $request['ymk'];
        $data->year = $request['year'];
        $data->type = $request['type'];
        $data->vehicle_opt = $request['vehicle_opt'];
        $data->model = $request['model'];
        $data->make = $request['make'];
        $data->condition = $request['condition'];
        $data->originzsc = $request['originzsc'];
        $data->originzip = $request['originzip'];
        $data->originstate = $request['originstate'];
        $data->origincity = $request['origincity'];
        $data->destinationzsc = $request['destinationzsc'];
        $data->destinationzip = $request['destinationzip'];
        $data->destinationstate = $request['destinationstate'];
        $data->destinationcity = $request['destinationcity'];
        $data->add_info = $request['add_info'];
        $data->transport = $request['trailer_type'];
        $data->shippingdate = $request['shippingdate'];
        $data->car_type = 1;
        $data->paneltype = $request['paneltype'];
        $data->cname = $request['cname'];
        $data->cemail = $request['cemail'];
        $data->main_ph = $request['main_ph'];
        $data->length_ft = $request['length_ft'];
        $data->length_in = $request['length_in'];
        $data->width_ft = $request['width_ft'];
        $data->width_in = $request['width_in'];
        $data->height_ft = $request['height_ft'];
        $data->height_in = $request['height_in'];
        $data->weight = $request['weight'];
        $data->load_method = $request['load_method'];
        $data->unload_method = $request['unload_method'];
        $data->ip_address = $request['ip'];
        $data->ipcity = $request['ipcity'];
        $data->ipregion = $request['ipregion'];
        $data->ipcountry = $request['ipcountry'];
        $data->iploc = $request['iploc'];
        $data->ippostal = $request['ippostal'];
        $data->pstatus = 0;
        $data->source = isset($request['source']) ? $request['source'] : 'ShipA1';
        $data->roro = isset($request['roro']) ? $request['roro'] : NULL;
        $data->load_type = isset($request['load_type']) ? $request['load_type'] : NULL;
        $data->save();

        $data2 = new orderpayment;
        $data2->orderId = $data->id;
        $data2->save();

        $data2 = new creditcard;
        $data2->orderId = $data->id;
        $data2->save();

        $data3 = new report;
        $data3->userId = 1;
        $data3->orderId = $data->id;
        $data3->pstatus = 0;
        $data3->save();

        $data4 = new singlereport;
        $data4->userId = 1;
        $data4->orderId = $data->id;
        $data4->pstatus = 0;
        $data4->save();


        $data5 = new order_freight;
        $data5->order_id = $data->id;
        $data5->frieght_class = $request['frieght_class'];
        $data5->equipment_type = $request['equipment_type'];
        $data5->trailer_specification = $request['trailer_specification'];
        $data5->ex_pickup_date = $request['ex_pickup_date'];
        $data5->ex_pickup_time = $request['ex_pickup_time'];
        $data5->ex_delivery_date = $request['ex_delivery_date'];
        $data5->ex_delivery_time = $request['ex_delivery_time'];
        $data5->commodity_detail = $request['commodity_detail'];
        $data5->commodity_unit = $request['commodity_unit'];
        $data5->pick_up_services = $request['pick_up_services'];
        $data5->deliver_services = $request['deliver_services'];
        $data5->save();


        $this->expected_date($data->id, 1, '0', '');

        return "SAVE";
    }

    public function websiteShipa1QuoteFreight(Request $request)
    {
        $user = DailyQoute::with('user.userRole')->where('total_qoute', '>', 0)->where('date', date('Y-m-d'))
            ->whereHas('user', function ($q) {
                $q->where('deleted', 0)->where('is_login', 1);
            })->whereHas('user.userRole', function ($q) {
                $q->where('name', 'Order Taker')->orWhere('name', 'Seller Agent');
            })->orderBy('total_qoute', 'DESC')->first();

        if (empty($user)) {
            $user = DailyQoute::with('user.userRole')->where('date', date('Y-m-d'))
                ->whereHas('user', function ($q) {
                    $q->where('deleted', 0)->where('is_login', 1);
                })->whereHas('user.userRole', function ($q) {
                    $q->where('name', 'Order Taker')->orWhere('name', 'Seller Agent');
                })->orderBy('total_qoute', 'DESC')->first();
        }

        $manager_id = NULL;
        $ot_ids = [];
        if (isset($user->user->id)) {
            if ($user->user->order_taker_quote == 2) {
                $m = OrderTakerQouteAccess::where('ot_ids', $user->user->id)->first();
                if (isset($m->id)) {
                    $manager_id = $m->manager_id;
                    $ot = OrderTakerQouteAccess::where('manager_id', $m->manager_id)->get();
                    if (isset($ot[0])) {
                        foreach ($ot as $key => $val) {
                            array_push($ot_ids, $val->ot_ids);
                        }
                    }
                    $daily = DailyQoute::where('user_id', $user->user->id)->whereDate('date', date('Y-m-d'))->first();
                    if (isset($daily->id)) {
                        if ($daily->total_qoute > 0) {
                            $daily->total_qoute = $daily->total_qoute - 1;
                            $daily->save();
                        }
                    }
                }
            }
            $user_iddd = $user->user->id;
        } else {
            $user = User::with('userRole')
                ->where('deleted', 0)
                ->whereHas('userRole', function ($q) {
                    $q->where('name', 'Order Taker')->orWhere('name', 'Seller Agent');
                })
                ->inRandomOrder()->first();

            $user_iddd = $user->id;
        }
        // echo "<pre>";
        // print_r($request['oname']);
        // exit();
        $order = AutoOrder::orderBy('id', 'DESC')->first();
        $data = new AutoOrder;
        $data->id = $order->id + 1;
        $data->order_taker_id = $user_iddd;
        $data->oname = $request['oname'];
        $data->oemail = $request['oemail'];
        $data->ophone = $request['ophone'];
        $data->ymk = $request['ymk'];
        $data->year = $request['year'];
        $data->type = $request['type'];
        $data->vehicle_opt = $request['vehicle_opt'];
        $data->model = $request['model'];
        $data->make = $request['make'];
        $data->condition = $request['condition'];
        $data->originzsc = $request['originzsc'];
        $data->originzip = $request['originzip'];
        $data->originstate = $request['originstate'];
        $data->origincity = $request['origincity'];
        $data->destinationzsc = $request['destinationzsc'];
        $data->destinationzip = $request['destinationzip'];
        $data->destinationstate = $request['destinationstate'];
        $data->destinationcity = $request['destinationcity'];
        $data->add_info = $request['add_info'];
        $data->transport = $request['transport'];
        $data->shippingdate = $request['shippingdate'];
        $data->car_type = 3;
        $data->paneltype = $request['paneltype'];
        $data->cname = $request['cname'];
        $data->cemail = $request['cemail'];
        $data->main_ph = $request['main_ph'];
        $data->length_ft = $request['length_ft'];
        $data->length_in = $request['length_in'];
        $data->width_ft = $request['width_ft'];
        $data->width_in = $request['width_in'];
        $data->height_ft = $request['height_ft'];
        $data->height_in = $request['height_in'];
        $data->weight = $request['weight'];
        $data->load_method = $request['load_method'];
        $data->unload_method = $request['unload_method'];
        $data->ip_address = $request['ip'];
        $data->ipcity = $request['ipcity'];
        $data->ipregion = $request['ipregion'];
        $data->ipcountry = $request['ipcountry'];
        $data->iploc = $request['iploc'];
        $data->ippostal = $request['ippostal'];
        $data->pstatus = 0;
        $data->source = isset($request['source']) ? $request['source'] : 'ShipA1';
        $data->roro = isset($request['roro']) ? $request['roro'] : NULL;
        $data->save();

        $data2 = new orderpayment;
        $data2->orderId = $data->id;
        $data2->save();

        $data2 = new creditcard;
        $data2->orderId = $data->id;
        $data2->save();

        $data3 = new report;
        $data3->userId = 1;
        $data3->orderId = $data->id;
        $data3->pstatus = 0;
        $data3->save();

        $data4 = new singlereport;
        $data4->userId = 1;
        $data4->orderId = $data->id;
        $data4->pstatus = 0;
        $data4->save();


        $data5 = new order_freight;
        $data5->order_id = $data->id;
        $data5->frieght_class = $request['frieght_class'];
        $data5->equipment_type = $request['equipment_type'];
        $data5->trailer_specification = $request['trailer_specification'];
        $data5->ex_pickup_date = $request['ex_pickup_date'];
        $data5->ex_pickup_time = $request['ex_pickup_time'];
        $data5->ex_delivery_date = $request['ex_delivery_date'];
        $data5->ex_delivery_time = $request['ex_delivery_time'];
        $data5->commodity_detail = $request['commodity_detail'];
        $data5->commodity_unit = $request['commodity_unit'];
        $data5->pick_up_services = $request['pick_up_services'];
        $data5->deliver_services = $request['deliver_services'];
        $data5->save();


        $this->expected_date($data->id, 1, '0', '');

        return "SAVE";
    }

    public function websiteShipa1QuoteHeavy(Request $request)
    {
        $user = DailyQoute::with('user.userRole')->where('total_qoute', '>', 0)->where('date', date('Y-m-d'))
            ->whereHas('user', function ($q) {
                $q->where('deleted', 0)->where('is_login', 1);
            })->whereHas('user.userRole', function ($q) {
                $q->where('name', 'Order Taker')->orWhere('name', 'Seller Agent');
            })->orderBy('total_qoute', 'DESC')->first();

        if (empty($user)) {
            $user = DailyQoute::with('user.userRole')->where('date', date('Y-m-d'))
                ->whereHas('user', function ($q) {
                    $q->where('deleted', 0)->where('is_login', 1);
                })->whereHas('user.userRole', function ($q) {
                    $q->where('name', 'Order Taker')->orWhere('name', 'Seller Agent');
                })->orderBy('total_qoute', 'DESC')->first();
        }

        $manager_id = NULL;
        $ot_ids = [];
        if (isset($user->user->id)) {
            if ($user->user->order_taker_quote == 2) {
                $m = OrderTakerQouteAccess::where('ot_ids', $user->user->id)->first();
                if (isset($m->id)) {
                    $manager_id = $m->manager_id;
                    $ot = OrderTakerQouteAccess::where('manager_id', $m->manager_id)->get();
                    if (isset($ot[0])) {
                        foreach ($ot as $key => $val) {
                            array_push($ot_ids, $val->ot_ids);
                        }
                    }
                    $daily = DailyQoute::where('user_id', $user->user->id)->whereDate('date', date('Y-m-d'))->first();
                    if (isset($daily->id)) {
                        if ($daily->total_qoute > 0) {
                            $daily->total_qoute = $daily->total_qoute - 1;
                            $daily->save();
                        }
                    }
                }
            }
            $user_iddd = $user->user->id;
        } else {
            $user = User::with('userRole')
                ->where('deleted', 0)
                ->whereHas('userRole', function ($q) {
                    $q->where('name', 'Order Taker')->orWhere('name', 'Seller Agent');
                })
                ->inRandomOrder()->first();

            $user_iddd = $user->id;
        }
        // echo "<pre>";
        // print_r($request['oname']);
        // exit();
        $order = AutoOrder::orderBy('id', 'DESC')->first();
        $data = new AutoOrder;
        $data->id = $order->id + 1;
        $data->order_taker_id = $user_iddd;
        $data->oname = $request['oname'];
        $data->oemail = $request['oemail'];
        $data->ophone = $request['ophone'];
        $data->ymk = $request['ymk'];
        $data->year = $request['year'];
        $data->type = $request['type'];
        $data->vehicle_opt = $request['vehicle_opt'];
        $data->model = $request['model'];
        $data->make = $request['make'];
        $data->condition = $request['condition'];
        $data->originzsc = $request['originzsc'];
        $data->originzip = $request['originzip'];
        $data->originstate = $request['originstate'];
        $data->origincity = $request['origincity'];
        $data->destinationzsc = $request['destinationzsc'];
        $data->destinationzip = $request['destinationzip'];
        $data->destinationstate = $request['destinationstate'];
        $data->destinationcity = $request['destinationcity'];
        $data->add_info = $request['add_info'];
        $data->transport = $request['transport'];
        $data->shippingdate = $request['shippingdate'];
        $data->car_type = 2;
        $data->paneltype = $request['paneltype'];
        $data->cname = $request['cname'];
        $data->cemail = $request['cemail'];
        $data->main_ph = $request['main_ph'];
        $data->length_ft = $request['length_ft'];
        $data->length_in = $request['length_in'];
        $data->width_ft = $request['width_ft'];
        $data->width_in = $request['width_in'];
        $data->height_ft = $request['height_ft'];
        $data->height_in = $request['height_in'];
        $data->weight = $request['weight'];
        $data->load_method = $request['load_method'];
        $data->unload_method = $request['unload_method'];
        $data->ip_address = $request['ip'];
        $data->ipcity = $request['ipcity'];
        $data->ipregion = $request['ipregion'];
        $data->ipcountry = $request['ipcountry'];
        $data->iploc = $request['iploc'];
        $data->ippostal = $request['ippostal'];
        $data->pstatus = 0;
        $data->source = isset($request['source']) ? $request['source'] : 'ShipA1';
        $data->roro = isset($request['roro']) ? $request['roro'] : NULL;
        $data->save();

        $data2 = new orderpayment;
        $data2->orderId = $data->id;
        $data2->save();

        $data2 = new creditcard;
        $data2->orderId = $data->id;
        $data2->save();

        $data3 = new report;
        $data3->userId = 1;
        $data3->orderId = $data->id;
        $data3->pstatus = 0;
        $data3->save();

        $data4 = new singlereport;
        $data4->userId = 1;
        $data4->orderId = $data->id;
        $data4->pstatus = 0;
        $data4->save();


        $data5 = new order_freight;
        $data5->order_id = $data->id;
        $data5->frieght_class = $request['frieght_class'];
        $data5->equipment_type = $request['equipment_type'];
        $data5->trailer_specification = $request['trailer_specification'];
        $data5->ex_pickup_date = $request['ex_pickup_date'];
        $data5->ex_pickup_time = $request['ex_pickup_time'];
        $data5->ex_delivery_date = $request['ex_delivery_date'];
        $data5->ex_delivery_time = $request['ex_delivery_time'];
        $data5->commodity_detail = $request['commodity_detail'];
        $data5->commodity_unit = $request['commodity_unit'];
        $data5->pick_up_services = $request['pick_up_services'];
        $data5->deliver_services = $request['deliver_services'];
        $data5->save();


        $this->expected_date($data->id, 1, '0', '');

        return "SAVE";
    }

    private function calculateDistance($origin, $destination)
    {
        // $apiKey = 'AIzaSyCidNuG4zb5tptrPvbyeYGCfgxn-5p-PQk';
        $url = 'https://maps.googleapis.com/maps/api/distancematrix/json?origins=' . $origin . '&destinations=' . $destination . '&sensor=false&region=US&key=' . $apiKey;
        $response = file_get_contents($url);
        $data = json_decode($response);

        if ($data && isset($data->rows[0]->elements[0]->distance->value)) {
            $distanceInMeters = $data->rows[0]->elements[0]->distance->value;
            $distanceInMiles = $distanceInMeters * 0.000621371;
            return $distanceInMiles;
        } else {
            return 0;
        }
    }

    public function websiteShipa1QuoteAuction(Request $request)
    {
        $user = DailyQoute::with('user.userRole')->where('total_qoute', '>', 0)->where('date', date('Y-m-d'))
            ->whereHas('user', function ($q) {
                $q->where('deleted', 0)->where('is_login', 1);
            })->whereHas('user.userRole', function ($q) {
                $q->where('name', 'Order Taker')->orWhere('name', 'Seller Agent');
            })->orderBy('total_qoute', 'DESC')->first();

        if (empty($user)) {
            $user = DailyQoute::with('user.userRole')->where('date', date('Y-m-d'))
                ->whereHas('user', function ($q) {
                    $q->where('deleted', 0)->where('is_login', 1);
                })->whereHas('user.userRole', function ($q) {
                    $q->where('name', 'Order Taker')->orWhere('name', 'Seller Agent');
                })->orderBy('total_qoute', 'DESC')->first();
        }

        $manager_id = NULL;
        $ot_ids = [];
        if (isset($user->user->id)) {
            if ($user->user->order_taker_quote == 2) {
                $m = OrderTakerQouteAccess::where('ot_ids', $user->user->id)->first();
                if (isset($m->id)) {
                    $manager_id = $m->manager_id;
                    $ot = OrderTakerQouteAccess::where('manager_id', $m->manager_id)->get();
                    if (isset($ot[0])) {
                        foreach ($ot as $key => $val) {
                            array_push($ot_ids, $val->ot_ids);
                        }
                    }
                    $daily = DailyQoute::where('user_id', $user->user->id)->whereDate('date', date('Y-m-d'))->first();
                    if (isset($daily->id)) {
                        if ($daily->total_qoute > 0) {
                            $daily->total_qoute = $daily->total_qoute - 1;
                            $daily->save();
                        }
                    }
                }
            }
            $user_iddd = $user->user->id;
        } else {
            $user = User::with('userRole')
                ->where('deleted', 0)
                ->whereHas('userRole', function ($q) {
                    $q->where('name', 'Order Taker')->orWhere('name', 'Seller Agent');
                })
                ->inRandomOrder()->first();

            $user_iddd = $user->id;
        }
        // echo "<pre>";
        // print_r($request['oname']);
        // exit();
        if (!empty($request['vin_num'])) {
            $vin_no = $request['vin_num'];
            $mydata = file_get_contents('https://vpic.nhtsa.dot.gov/api/vehicles/decodevin/' . $vin_no . '?format=json');
            $mydata1 = json_decode($mydata);
            $vindata = $this->object_to_array($mydata1);

            $year = '';
            $model = '';
            $make = '';
            if (!empty($vindata)) {
                foreach ($vindata['Results'] as $key => $value) {
                    if ($value['Variable'] == 'Make') {
                        $make = $value['Value'];
                    }
                    if ($value['Variable'] == 'Model') {
                        $model = $value['Value'];
                    }
                    if ($value['Variable'] == 'Model Year') {
                        $year = $value['Value'];
                    }
                }
            } else {
                $year = $request['year'];
                $model = $request['make'];
                $make = $request['model'];
            }
        } else {
            $year = $request['year'];
            $model = $request['make'];
            $make = $request['model'];
        }
        $ymk = $year . ' ' . $make . ' ' . $model;

        $order = AutoOrder::orderBy('id', 'DESC')->first();
        $data = new AutoOrder;
        $data->id = $order->id + 1;
        $data->order_taker_id = $user_iddd;
        $data->oname = $request['oname'];
        $data->oemail = $request['oemail'];
        $data->ophone = $request['ophone'];
        $data->ymk = $ymk;
        $data->vin_num = $request['vin_num'];
        $data->vin = $request['vin_num'] ? substr($request['vin_num'], -8) : null;
        $data->year = $year;
        $data->vehicle_opt = $request['vehicle_opt'];
        $data->model = $model;
        $data->make = $make;
        $data->originzsc = $request['originzsc'];
        $data->originzip = $request['originzip'];
        $data->originstate = $request['originstate'];
        $data->origincity = $request['origincity'];
        $data->destinationzsc = $request['destinationzsc'];
        $data->destinationzip = $request['destinationzip'];
        $data->destinationstate = $request['destinationstate'];
        $data->destinationcity = $request['destinationcity'];
        $data->paneltype = 2;
        $data->price_giver_allow = 1;
        $data->cname = $request['cname'];
        $data->cemail = $request['cemail'];
        $data->main_ph = $request['main_ph'];
        if ($request['dauction']) {
            $data->dterminal = 7;
            $data->dauction = $request['dauction'] . ' Port';
        } else {
            $data->dterminal = NULL;
            $data->dauction = NULL;
        }
        if ($request['oauction']) {
            $oauction = explode(',', $request['oauction']);
            $data->oterminal = 7;
            $data->oauction = ($oauction[0] ?? $request['oauction']) . ' Port';
        } else {
            $data->oterminal = NULL;
            $data->oauction = NULL;
        }
        $data->payment = $request['payment'];
        $data->balance = $request['payment'];
        $data->ip_address = $request['ip'];
        $data->ipcity = $request['ipcity'];
        $data->ipregion = $request['ipregion'];
        $data->ipcountry = $request['ipcountry'];
        $data->iploc = $request['iploc'];
        $data->ippostal = $request['ippostal'];
        $data->pstatus = 0;
        $data->save();

        $data2 = new orderpayment;
        $data2->orderId = $data->id;
        $data2->save();

        $data2 = new creditcard;
        $data2->orderId = $data->id;
        $data2->save();

        $data3 = new report;
        $data3->userId = 1;
        $data3->orderId = $data->id;
        $data3->pstatus = 0;
        $data3->save();

        $data4 = new singlereport;
        $data4->userId = 1;
        $data4->orderId = $data->id;
        $data4->pstatus = 0;
        $data4->save();

        $this->expected_date($data->id, 1, '0', '');

        $orderid = base64_encode($data->id);
        $userid = base64_encode($data->order_taker_id);

        $link = "https://washington.shawntransport.com/website-order/" . $orderid . "/" . $userid . "/";
        return $link;
    }

    public function phoneDigits()
    {
        $data = PhoneDigit::first();
        return view('main.register.phone_digit', compact('data'));
    }

    public function updatePhoneDigits(Request $request)
    {
        $data = PhoneDigit::first();
        $data->hide_digits = $request->hide_digits ?? 6;
        $data->left_right_status = $request->left_right_status ?? 0;
        $data->save();
        $msg = $data->hide_digits . ' digits hide from ' . ($data->left_right_status == 0 ? 'left' : 'right') . ' for phone numbers successfully';
        return back()->with('success_msg', $msg);
    }

    public function updateCarrierHistory(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());exit();
        $data = new CarrierOldOrderHistory;
        $data->old_order_id = $request->old_order_id;
        $data->new_order_id = $request->new_order_id;
        $data->user_id = Auth::user()->id ?? 0;
        $data->history = $request->history;
        $data->save();

        return response()->json([
            'message' => 'Save',
            'status' => true,
            'status_code' => 200
        ], 200);
    }

    public function viewCarrierHistory(Request $request)
    {
        $data = CarrierOldOrderHistory::where('old_order_id', $request->old_order_id)->orderBy('created_at', 'ASC')->get();
        // echo "<pre>";
        // print_r($data->toArray());exit();
        return view('main.phone_quote.new.viewcarrierhistory', compact('data'));
    }

    public function trackingOrder(Request $request)
    {
        $data = AutoOrder::select('id', 'payment', 'ymk', 'originzsc', 'origincity', 'originstate', 'destinationzsc', 'destinationcity', 'destinationstate', 'transport', 'condition', 'created_at', 'pstatus', 'date_of_booked', 'pickup_date', 'delivery_date')
            ->where('id', $request['id'])
            ->where(function ($q) {
                $q->whereBetween('pstatus', [7, 14])
                    ->orWhere('pstatus', 18);
            })
            ->first();

        if (empty($data->id)) {
            return response()->json([
                'message' => 'Your Tracking Id ' . $request['id'] . ' is wrong!',
                'status' => false,
                'status_code' => 400
            ], 400);
        }

        $data2 = AutoOrder::select('id', 'payment')
            ->where(function ($q) {
                $q->whereBetween('pstatus', [7, 14])
                    ->orWhere('pstatus', 18);
            })
            ->where('origincity', $data->origincity)
            ->where('destinationcity', $data->destinationcity)
            ->where('id', '<>', $data->id)
            ->where('payment', '<>', NULL)
            ->orderBy('updated_at', 'DESC')
            ->first();

        if (isset($data2->id)) {
            $price = intval($data2->payment);
            if ($price == 0) {
                $price = intval($data->payment);
            }
        } else {
            $price = intval($data->payment);
        }

        $order = report::where('orderId', $request['id'])->where(function ($q) {
            $q->where('pstatus', '>=', 9)
                ->where('pstatus', '<=', 14);
        })->orderBy('created_at', 'ASC')->get()->unique('pstatus');

        return response()->json([
            'order' => $order,
            'data' => $data,
            'price' => $price,
            'status' => true,
            'status_code' => 200
        ], 200);
    }

    public function userRating(Request $request)
    {
        $label = FieldLabel::all();
        $role = Auth::user()->userRole->name;
        $user = User::with('userRole')->whereHas('userRole', function ($q) {
            $q->where('name', 'Order Taker')
                ->orWhere('name', 'Seller Agent')
                ->orWhere('name', 'CSR')
                ->orWhere('name', 'Dispatcher');
        })->select('id', 'slug', 'name', 'last_name', 'role')->where('deleted', 0)->orderBy('slug', 'ASC')->get();
        $from = '';
        $too = '';

        if (isset($request->date_range) && !empty($request->date_range)) {
            $dates = explode(' - ', $request->date_range);
            $from = date('Y-m-d 00:00:00', strtotime($dates[0]));
            $too = date('Y-m-d 23:59:59', strtotime($dates[1]));
        }
        $rating = Rating::where(function ($q) use ($from, $too) {
            if (!empty($from) && !empty($too)) {
                $q->whereBetween('created_at', [$from, $too]);
            }
        });
        if (isset($request->users) && isset($request->search_by)) {
            $rating = $rating->where($request->search_by, $request->users);
        }
        if ($role <> 'Admin' && $role <> 'Manager') {
            $rating = $rating->where(function ($q) {
                $q->where('replyer_id', Auth::user()->id)
                    ->orWhere('rater_id', Auth::user()->id);
            });
        }

        $rating = $rating->orderBy('reply_status', 'ASC')->orderBy('updated_at', 'DESC')->paginate(10);
        if ($request->ajax()) {
            return view('main.rating.search', compact('rating'));
        }
        return view('main.rating.user_rating', compact('rating', 'from', 'too', 'user', 'label'));
    }

    public function ratingdetail(Request $request)
    {
        $rating = Rating::where('order_id', $request->order_id)->first();
        $order = AutoOrder::find($request->order_id);
        return view('main.rating.ratingdetail', compact('rating', 'order'));
    }

    public function ratingdetailcreate(Request $request)
    {
        $rating = Rating::where('order_id', $request->order_id)->first();
        $order = AutoOrder::find($request->order_id);
        if (isset($rating->rater_id)) {
            if (empty($rating->reply)) {
                $validator = Validator::make($request->all(), [
                    'reply' => 'required'
                ]);

                if ($validator->passes()) {
                    $rating->reply = $request->reply;
                    $rating->reply_status = 1;
                    $rating->save();
                } else {
                    return response()->json([
                        'error' => $validator->errors(),
                        'status' => false,
                        'status_code' => 400
                    ]);
                }
            } else {
                if (empty($rating->mistake_user_id)) {
                    $validator = Validator::make($request->all(), [
                        'mistake_user_id' => 'required',
                        'comments' => 'required'
                    ]);

                    if ($validator->passes()) {
                        $rating->mistake_user_id = $request->mistake_user_id;
                        $rating->comments = $request->comments;
                        $rating->save();
                    } else {
                        return response()->json([
                            'error' => $validator->errors(),
                            'status' => false,
                            'status_code' => 400
                        ]);
                    }
                }
            }
        } else {
            $validator = Validator::make($request->all(), [
                'subject' => 'required',
                'review' => 'required',
                'rating' => 'required'
            ]);

            if ($validator->passes()) {
                $rating = new Rating;
                $rating->order_id = $request->order_id;
                $rating->rater_id = Auth::user()->id;
                if ($order->order_taker_id == Auth::user()->id) {
                    $rating->replyer_id = $order->dispatcher_id;
                } else {
                    $rating->replyer_id = $order->order_taker_id;
                }
                $rating->subject = $request->subject;
                $rating->review = $request->review;
                $rating->rating = $request->rating;
                $rating->pstatus = $order->pstatus;
                $rating->save();
            } else {
                return response()->json([
                    'error' => $validator->errors(),
                    'status' => false,
                    'status_code' => 400
                ]);
            }
        }

        return response()->json([
            'message' => "Save",
            'status' => true,
            'status_code' => 200
        ]);
    }

    public function order_users(Request $request)
    {
        $order = AutoOrder::select('id', 'order_taker_id', 'dispatcher_id')->where('id', $request->id)->first();
        $last_history = call_history::where('orderId', $request->id)->orderBy('id', 'DESC')->first();
        if (isset($last_history->id)) {
            $last_history->username = '';
            $last_history->created = Carbon::parse($last_history->created_at)->format('M,d Y h:i A');
            $user = User::find($last_history->userId);
            if (isset($user->id)) {
                $last_history->username = $user->slug ?? $user->name;
            }
        }
        $ot = '';
        $dis = '';
        $ot_id = '';
        $dis_id = '';
        if (isset($order->order_taker_id)) {
            $user = User::where('id', $order->order_taker_id)->select('id', 'name', 'slug')->first();
            if (isset($user->id)) {
                $ot = ($user->slug ?? $user->name) . ' (Order Taker)';
                $ot_id = $user->id;
            }
        }
        if (isset($order->dispatcher_id)) {
            $user = User::where('id', $order->dispatcher_id)->select('id', 'name', 'slug')->first();
            if (isset($user->id)) {
                $dis = ($user->slug ?? $user->name) . ' (Dispatcher)';
                $dis_id = $user->id;
            }
        }

        return response()->json([
            'ot' => $ot,
            'dis' => $dis,
            'ot_id' => $ot_id,
            'dis_id' => $dis_id,
            'last_history' => $last_history,
            'status' => true,
            'status_code' => 200
        ]);
    }

    public function qa_count(Request $request)
    {
        $data = QaVerifyHistory::where('order_id', $request->id)->count();

        return $data;
    }

    public function approach_count(Request $request)
    {
        $data = CarrierApproaching::where('order_id', $request->id)->count();
        $status = AutoOrder::find($request->id);
        $status = $status->pstatus;
        // return $data;
        return ['data' => $data, 'statusCheck' => $status];
    }

    public function rating_count()
    {
        $role = Auth::user()->userRole->name;
        $count = Rating::where('reply', NULL);
        if ($role <> 'Admin') {
            $count = $count->where('replyer_id', Auth::user()->id);
        }
        $count = $count->count();

        return $count;
    }

    public function assign_to_dispatcher(Request $request)
    {
        $flash_message = 'No Dispatcher Assign To Order Id#' . $request->order_id;
        if (isset($request->assigning_dispatcher)) {
            $data = AutoOrder::find($request->order_id);
            if (empty($data->dispatcher_id)) {
                $data->dispatcher_id = $request->assigning_dispatcher;
                $data->save();
                $flash_message = 'Dispatcher Assign To Order Id#' . $request->order_id;
            }
        }

        Session::flash('flash_message', $flash_message);
        return back();
    }

    public function owes_history_update(Request $request)
    {
        $order = AutoOrder::find($request->order_id);
        if (isset($order->id)) {
            $data = new OwesHistory;
            $data->order_id = $order->id;
            $data->pstatus = $order->pstatus;
            $data->user_id = Auth::user()->id;
            $data->history = $request->history;
            $data->save();

            return back()->with('msg', 'History updated on OrderId#' . $order->id);
        }
        return back()->with('err', 'Someting went wrong');
    }

    public function owes_history_view(Request $request)
    {
        $data = OwesHistory::where('order_id', $request->id);

        if (Auth::user()->userRole->name <> 'Admin') {
            $data = $data->where('user_id', Auth::user()->id);
        }

        $data = $data->orderBy('created_at', 'DESC')->get();

        return view('main.phone_quote.callhistory.owes_history', compact('data'));
    }

    // create this api to delete more than one entries
    public function testingapi(Request $request)
    {
        // $data = report::select('orderId','pstatus')
        // ->where('pstatus',$request->pstatus)
        // ->whereBetween('created_at',['2023-01-01 00:00:00','2023-07-31 23:59:59'])
        // ->orderBy('orderId','ASC')->get()->unique('orderId');
        // foreach($data as $key => $val)
        // {
        //     $checking = report::where('pstatus',$val->pstatus)
        //     ->where('orderId',$val->orderId)->get();
        //     if(count($checking) > 1)
        //     {
        //         foreach($checking as $key2 => $val2)
        //         {
        //             report::destroy($val2->id);
        //         }
        //     }
        // }

        // $data = report::where('pstatus',$request->pstatus)
        // ->whereBetween('created_at',['2023-07-01 00:00:00','2023-07-31 23:59:59'])
        // ->orderBy('orderId','ASC')->get()->unique('orderId');
        // foreach($data as $key => $val)
        // {
        //     $checking = report::where('pstatus',$val->pstatus2)
        //     ->where('orderId',$val->orderId)->get();
        //     if(count($checking) > 0)
        //     {
        //         // foreach($checking as $key2 => $val2)
        //         // {
        //         //     report::destroy($val2->id);
        //         // }
        //     }
        //     else
        //     {
        //         $r = new report();
        //         $r->orderId = $val->orderId;
        //         $r->pstatus = $request->pstatus2;
        //         $r->userId = $val->userId;
        //         $r->created_at = $val->created_at;
        //         $r->updated_at = $val->updated_at;
        //         $r->save();
        //     }
        // }
        return "Hogya Update";
    }


    public function customerNatureList()
    {
        if (Auth::check()) {
            $uniqueUserIds = NatureOfCustomer::select('user_id')->distinct()->pluck('user_id');
            $users = User::whereIn('id', $uniqueUserIds)->get();
            $nature = NatureOfCustomer::orderBy("created_at", "DESC")->get();
            // dd($nature->toArray());
            return view('main.phone_quote.new_quote.customerNatureList', compact('users', 'nature'));
        } else {
            return redirect('/login');
        }
    }

    public function filterCustomerNatureList(Request $request)
    {
        // dd($request->toArray());
        if (Auth::check()) {
            $nature = NatureOfCustomer::with('user');

            $from = Carbon::now()->format('Y-m-d 00:00:00');
            $to = Carbon::now()->format('Y-m-d 23:59:59');

            if (isset($request->date_range) && !empty($request->date_range)) {
                $dates = explode(' - ', $request->date_range);
                $from = date('Y-m-d 00:00:00', strtotime($dates[0]));
                $to = date('Y-m-d 23:59:59', strtotime($dates[1]));

                $nature = $nature->whereBetween('created_at', [$from, $to])->orderBy('created_at', 'DESC');
            }
            if ($request->has('user') && $request->user != null) {
                $nature = $nature->where('user_id', $request->user);
            }
            if ($request->has('status') && $request->status != null) {
                $nature = $nature->where('status', $request->status);
            }
            $nature = $nature->orderBy("updated_at", "asc")->get();
            // dd($request->toArray(), $nature->toArray());
            return view('main.phone_quote.new_quote.filterCustomerNatureList', compact('nature'));
        } else {
            return redirect('/login');
        }
    }

    public function priceChangeMail(Request $request, $id)
    {
        $order = AutoOrder::with('freight')->find($id);

        $price = $order->payment;
        $email = $order->email;

        // dd($request->id, $request->start_price, $request->payment, $request->driver_price, $price);

        $userId = Auth::user()->id;
        $orderId = $order->id;
        $encryptvuserid = $this->encodeData($userId);
        $encryptvoderid = $this->encodeData($orderId);
        $linkv = url('/email_order/' . $encryptvoderid . '/' . $encryptvuserid);


        try {
            // Mail::to(['info@hellotransport.com', $order->oemail])->send(new EditQuotePriceMail($price, $order, $linkv));
            $recipients = ['info@hellotransport.com'];

            if (filter_var($order->oemail, FILTER_VALIDATE_EMAIL)) {
                $recipients[] = $order->oemail;
            }

            Mail::to($recipients)->send(new EditQuotePriceMail($price, $order, $linkv));

            return back()->with('success', 'Price Added');
        } catch (\Exception $e) {
            return back()->with('error', 'Error sending email');
        }
    }

    private function encodeData($data)
    {
        if (is_array($data)) {
            $str = '';
            foreach ($data as $char) {
                $str .= chr($char);
            }
        } else {
            $str = (string) $data;
        }

        return base64_encode($str);
    }

    public function email_order_api($id, $email, Request $request)
    {
        $checkEmail = AutoOrder::where('email', $email)
            ->orWhere('oemail', $email)
            ->first();
        $checkEmail = AutoOrder::where('id', $id) // Add condition for id
            ->where(function ($query) use ($email) {
                $query->where('email', $email)
                    ->orWhere('oemail', $email);
            })
            ->first();

        if (!$checkEmail) {
            return response()->json([
                'error' => 'Sorry, this email is not associated with any valid order.',
            ], 404);
        }

        $data = AutoOrder::with('freight')->find($id);

        if (!$data) {
            return response()->json([
                'error' => 'Sorry, this order # is not valid.',
            ], 404);
        }
        $ip = $request->ip();

        return response()->json([
            'data' => $data,
            'ip' => $ip,
        ]);
    }

    public function email_order_apiStore(Request $request)
    {
        try {
            $payment = orderpayment::where('orderId', $request->id)->first();
            $payment->your_name = $request->yourname;
            $payment->signature = $request->signature;
            $payment->save();

            $data = AutoOrder::with('freight')->find($request->id);
            $ip = $request->ip();
            $data->pstatus = 7;
            $data->save();

            return response()->json([
                'message' => 'Data stored successfully',
                'data' => $data,
                'ip' => $ip
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function email_order_apiStoreCard(Request $request)
    {
        $pstatuss = "";

        if ($request->save_but == 'save_with_pay') {
            // Perform actions when save_with_pay button is clicked
            try {
                $order = AutoOrder::findOrFail($request->id);
                $last_status = $this->get_pstatuss($order->pstatus);
                $order->pstatus = 8; // Booked
                $order->paid_status = 2;
                $order->pay_comments = "Customer Card Updated";
                $order->save();

                $pstatuss = $order->pstatus;

                $report = report::where('orderId', '=', $request->id)->where('pstatus', '=', $order->pstatus)->first();
                if (!isset($report->id)) {
                    $report = new report();
                    $report->userId = 1;
                    $report->orderId = $request->id;
                    $report->pstatus = 8; ///booked
                    $report->save();
                }

                $singlereport = singlereport::where('orderId', '=', $request->id)->first();

                if ($singlereport) {
                    $singlereport->userId = 1;
                    $singlereport->pstatus = 8; ///booked
                    $singlereport->save();
                } else {
                    $singlereport = new singlereport();
                    $singlereport->userId = 1;
                    $singlereport->orderId = $request->id;
                    $singlereport->pstatus = $order->pstatus;
                    $singlereport->save();
                }

                $callhistory = new call_history();
                $callhistory->userId = 1;
                $callhistory->orderId = $order->id;
                $callhistory->pstatus = $pstatuss;
                $callhistory->history = "<h6>LAST STATUS :$last_status</h6>" . "<h6>Remarks: Submit by Customer , Card information submitted<h6>";
                $callhistory->save();


                $payment = orderpayment::where('orderId', '=', $request->id)->first();
                $payment->card_first_name = $request->firstname;
                $payment->card_last_name = $request->lastname;
                $payment->billing_address = $request->billing_address;
                if (strpos($request->o_zip1, ",") !== false) {
                    $ozip = explode(',', $request->o_zip1);
                    $payment->b_zip = $ozip[2];
                    $payment->b_state = $ozip[1];
                    $payment->b_city = $ozip[0];
                    $payment->b_zsc = $request->o_zip1;
                }
                $payment->card_no = $payment->card_no . '^*' . $request->card_number;
                $payment->card_expiry_date = $payment->card_expiry_date . '^*' . $request->cardexpirydate;
                $payment->card_security = $payment->card_security . '^*' . $request->csvno;
                $payment->payment_status = 'Paid';
                $payment->card_type = $payment->card_type . '^*' . $request->card_type;

                $payment->save();

                $creditscard = creditcard::where('orderId', '=', $request->id)->first();
                if ($creditscard == '') {
                    $creditscard = new creditcard();
                }

                $creditscard->orderId = $request->id;
                $creditscard->card_first_name = $request->firstname;
                $creditscard->card_last_name = $request->lastname;
                $creditscard->billing_address = $request->billing_address;
                $creditscard->b_city = $request->bcity;
                $creditscard->b_state = $request->bstate;
                $creditscard->b_zip = $request->bzip;
                $creditscard->b_zsc = $request->bcity . ',' . $request->bstate . ',' . $request->bzip;
                if ($creditscard <> '') {
                    $creditscard->card_no = $creditscard->card_no . '*^' . $request->card_number;
                    $creditscard->card_expiry_date = $creditscard->card_expiry_date . '*^' . $request->cardexpirydate;
                    $creditscard->card_security = $creditscard->card_security . '*^' . $request->csvno;
                    $creditscard->card_type = $creditscard->card_type . '*^' . $request->card_type;
                } else {
                    $creditscard->card_no = $request->card_number;
                    $creditscard->card_expiry_date = $request->cardexpirydate;
                    $creditscard->card_security = $request->csvno;
                    $creditscard->card_type = $request->card_type;
                }

                $creditscard->save();

                if ($order->booking_mail < 1) {
                    $order->booking_mail = $order->booking_mail + 1;
                    // Mail::to(['info@hellotransport.com', $order->oemail])->send(new BookingConfirmationMail($order));
                    $recipients = ['info@hellotransport.com'];

                    if (filter_var($order->oemail, FILTER_VALIDATE_EMAIL)) {
                        $recipients[] = $order->oemail;
                    }

                    Mail::to($recipients)->send(new BookingConfirmationMail($order));
                }

                return response()->json(['message' => 'Order payment updated successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } elseif ($request->save_but == 'save_without_pay') {
            try {
                $order = AutoOrder::findOrFail($request->id);
                $last_status = $this->get_pstatuss($order->pstatus);
                $order->pstatus = 7;
                $order->save();

                $pstatuss = $order->pstatus;

                $report = report::where('orderId', '=', $request->id)->where('pstatus', '=', $order->pstatus)->first();
                if (!isset($report->id)) {
                    $report = new report();
                    $report->userId = 1;
                    $report->orderId = $request->id;
                    $report->pstatus = 7;
                    $report->save();
                }

                $singlereport = singlereport::where('orderId', '=', $request->id)->first();
                $singlereport->userId = 1;
                $singlereport->pstatus = 7; ///missing payment
                $singlereport->save();

                $payment = orderpayment::where('orderId', '=', $request->id)->first();

                if (!empty($payment)) {
                    $payment->payment_status = 'Unpaid';
                    $payment->save();
                }

                $callhistory = new call_history();
                $callhistory->userId = 1;
                $callhistory->orderId = $order->id;
                $callhistory->pstatus = $pstatuss;
                $callhistory->history = "<h6>LAST STATUS :$last_status</h6>" . "<h6>Remarks: Submit by Customer , Card not found <h6>";
                $callhistory->save();

                if (isset($request->expected_date)) {
                    $expected_date = $request->expected_date;
                } else {
                    $expected_date = date("Y-m-d");
                }

                $this->expected_date($order->id, 1, $pstatuss, $expected_date);

                if ($order->booking_mail < 1) {
                    $order->booking_mail = $order->booking_mail + 1;
                    // Mail::to(['info@hellotransport.com', $order->oemail])->send(new BookingConfirmationMail($order));
                    $recipients = ['info@hellotransport.com'];

                    if (filter_var($order->oemail, FILTER_VALIDATE_EMAIL)) {
                        $recipients[] = $order->oemail;
                    }

                    Mail::to($recipients)->send(new BookingConfirmationMail($order));

                    $order->save();
                }

                return response()->json(['message' => 'Order payment updated successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
    }

    public function revert_to_new($id)
    {
        $data = AutoOrder::find($id);
        $data->pstatus = 0;
        $data->revert_count = $data->revert_count + 1;
        $data->save();

        return back()->with('success', 'Reverted Successfully');
    }

    public function requestPrice()
    {
        $data = role::all();
        $orderId = 0;
        $phoneno = "";
        $oemail = "";
        if (Auth::check()) {
            if (Auth::user()->assign_daily_qoute > 0) {
                $daily = DailyQoute::where('user_id', Auth::user()->id)->whereDate('date', date('Y-m-d'))->first();
                if (!empty($daily)) {
                    if ($daily->total_qoute == 0) {
                        return back()->with('err', 'Your creating qoute limit has end. Please booked atleast one to create new.');
                    }
                }
            }
            $label = FieldLabel::all();
            // dd($label->toArray());
            return view('request_price', compact('data', 'orderId', 'phoneno', 'label', 'oemail'));
        } else {
            return redirect('/loginn/');
        }
    }

    public function block_phone()
    {
        $block_phones = BlockPhone::all();
        return view('block_phone.index', compact('block_phones'));
    }

    public function block_phone_submit(Request $request)
    {
        $block_phones = new BlockPhone;
        $block_phones->user_id = Auth::id();
        $block_phones->phone = $request->phone;
        $block_phones->description = $request->description;
        $block_phones->save();

        return back()->with('success', 'Requested successfully, wait for admin approval');
    }

    public function updateStatus(Request $request)
    {
        $blockPhone = BlockPhone::find($request->id);
        if ($blockPhone) {
            $blockPhone->status = $request->status;
            $blockPhone->approver = Auth::id();
            ;
            $blockPhone->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 400);
    }

    public function allow_price_giver($id)
    {
        $data = AutoOrder::find($id);

        if ($data) {
            $data->price_giver_allow = 1;
            $data->save();

            return back()->with('success', 'Success');
        }

        return back();
    }

    public function checkQuoteExists(Request $request)
    {
        $phoneCount = AutoOrder::where('ophone', $request->checkPhone)->count();
        return $phoneCount;
    }

    public function changeCarType(Request $request)
    {
        $order = AutoOrder::where('id', $request->order_id)->first();
        $order->car_type = $request->car_type;
        $order->save();

        return response()->json(['success' => true], 200);
    }

    public function getOldPrice(Request $request)
    {
        $validatedData = $request->validate([
            'origin' => 'required|string',
            'destination' => 'required|string',
        ]);

        $origin = $validatedData['origin'];
        $destination = $validatedData['destination'];

        try {
            // Retrieve the data with the required fields
            $data = AutoOrder::whereNotNull('given_price')
                ->where('originzsc', $origin)
                ->where('destinationzsc', $destination)
                ->select('id', 'ymk', 'created_at', 'updated_at', 'originzsc', 'destinationzsc', 'given_price')
                ->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No records found for the given origin and destination.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function agentReport(Request $request)
    {
        // dd($request->toArray());

        $paneltype = $this->check_panel();

        // $userIds = User::with('userRole')
        //     ->where('deleted', 0)
        //     ->where(function ($query) {
        //         $query->whereHas('userRole', function ($q) {
        //             $q->where('name', 'Order Taker');
        //         })->orWhere('id', 45);
        //     })
        //     ->pluck('id');

        $userIds = User::with('userRole')
            ->where('deleted', 0)
            ->where(function ($query) {
                $query->whereHas('userRole', function ($q) {
                    $q->where('name', 'Order Taker');
                })->orWhere('id', 45);
            })
            ->whereHas('user_setting', function ($q) use ($paneltype) {
                $q->where('penal_type', $paneltype);
            })
            ->orderBy('id')
            ->pluck('id');

        $startDate = $request->input('start_date', now()->startOfDay()->toDateString());
        $endDate = $request->input('end_date', now()->endOfDay()->toDateString());

        // dd($startDate, $endDate);

        $date_range = $startDate . ' - ' . $endDate;

        $agentReport = AgentReport::where('date_range', $date_range)->orderby('id')->get();

        if (!$agentReport->isEmpty()) {
            $data = $agentReport;
        } else {
            $query = AutoOrder::where('paneltype', $paneltype)
                ->whereIn('order_taker_id', $userIds)
                ->select(
                    'order_taker_id',
                    DB::raw('COUNT(CASE WHEN paneltype = 1 AND created_at BETWEEN "' . $startDate . '" AND "' . $endDate . ' 23:59:59" THEN 1 END) as pquote_count'),
                    DB::raw('SUM(CASE WHEN pstatus = 2 AND created_at BETWEEN "' . $startDate . '" AND "' . $endDate . ' 23:59:59" THEN 1 ELSE 0 END) as followup_count'),
                    DB::raw('SUM(CASE WHEN (PaymentMissing_Created IS NOT NULL AND PaymentMissing_Created BETWEEN "' . $startDate . ' 00:00:00" AND "' . $endDate . ' 23:59:59") OR (OnApproval_Created IS NOT NULL AND OnApproval_Created BETWEEN "' . $startDate . ' 00:00:00" AND "' . $endDate . ' 23:59:59") THEN 1 ELSE 0 END) as order_achieve'),
                    DB::raw('SUM(CASE WHEN pstatus = 14 AND created_at BETWEEN "' . $startDate . '" AND "' . $endDate . ' 23:59:59" THEN 1 ELSE 0 END) as canceled_count'),
                    DB::raw('SUM(CASE WHEN cancelDirectOnApproval = 1 AND created_at BETWEEN "' . $startDate . '" AND "' . $endDate . ' 23:59:59" THEN 1 ELSE 0 END) as canceled_onapproval'),
                    DB::raw('SUM(CASE WHEN OnApproval_Created BETWEEN "' . $startDate . '" AND "' . $endDate . ' 23:59:59" THEN 1 ELSE 0 END) as on_app_order'),
                    DB::raw('SUM(CASE WHEN dauction = "Port" AND created_at BETWEEN "' . $startDate . '" AND "' . $endDate . ' 23:59:59" THEN 1 ELSE 0 END) as port_delivery'),
                    DB::raw('COUNT(DISTINCT CASE WHEN NotInterested_Created IS NULL
                        AND Booked_Created IS NULL
                        AND PaymentMissing_Created IS NULL
                        AND OnApproval_Created IS NULL
                        AND (
                            NEW_Created BETWEEN "' . $startDate . '" AND "' . $endDate . ' 23:59:59"
                            OR Interested_Created BETWEEN "' . $startDate . '" AND "' . $endDate . ' 23:59:59"
                            OR FollowMore_Created BETWEEN "' . $startDate . '" AND "' . $endDate . ' 23:59:59"
                            OR AskingLow_Created BETWEEN "' . $startDate . '" AND "' . $endDate . ' 23:59:59"
                            OR NoResponse_Created BETWEEN "' . $startDate . '" AND "' . $endDate . ' 23:59:59"
                            OR TimeQuote_Created BETWEEN "' . $startDate . '" AND "' . $endDate . ' 23:59:59"
                            OR Listed_Created BETWEEN "' . $startDate . '" AND "' . $endDate . ' 23:59:59"
                            OR Schedule_Created BETWEEN "' . $startDate . '" AND "' . $endDate . ' 23:59:59"
                            OR Pickup_Created BETWEEN "' . $startDate . '" AND "' . $endDate . ' 23:59:59"
                            OR Delivered_Created BETWEEN "' . $startDate . '" AND "' . $endDate . ' 23:59:59"
                            OR Completed_Created BETWEEN "' . $startDate . '" AND "' . $endDate . ' 23:59:59"
                            OR Cancel_Created BETWEEN "' . $startDate . '" AND "' . $endDate . ' 23:59:59"
                            OR Deleted_Created BETWEEN "' . $startDate . '" AND "' . $endDate . ' 23:59:59"
                            OR OwesMoney_Created BETWEEN "' . $startDate . '" AND "' . $endDate . ' 23:59:59"
                            OR CancelOnApproval_Created BETWEEN "' . $startDate . '" AND "' . $endDate . ' 23:59:59"
                        )
                        THEN id
                        ELSE NULL END) as need_to_book'),
                );
            // DB::raw('COUNT(CASE WHEN paneltype = 1 AND created_at BETWEEN "' . $startDate . '" AND "' . $endDate . ' 23:59:59" THEN 1 END)
            //     - SUM(CASE WHEN paneltype = 1 AND pstatus IN (4, 8, 18, 7) AND created_at BETWEEN "' . $startDate . '" AND "' . $endDate . ' 23:59:59" THEN 1 ELSE 0 END) as need_to_book'),

            $data = $query->groupBy('order_taker_id')
                ->with('orderTaker')
                ->get();
        }

        $verifiedCounts = QaVerifyHistory::where('verify', 1)
            ->join('order', 'qa_verify_histories.order_id', '=', 'order.id')
            ->whereIn('order.order_taker_id', $userIds)
            ->whereBetween('order.created_at', [$startDate, $endDate . ' 23:59:59'])
            ->select('order.order_taker_id as user_id', DB::raw('COUNT(*) as count'))
            ->groupBy('order.order_taker_id')
            ->get()
            ->map(function ($item) {
                return [
                    'user_id' => $item->user_id,
                    'count' => $item->count,
                ];
            });

        // dd($verifiedCounts);

        $negativeCounts = QaVerifyHistory::where('negative', 1)
            ->join('order', 'qa_verify_histories.order_id', '=', 'order.id')
            ->whereIn('order.order_taker_id', $userIds)
            ->whereBetween('order.created_at', [$startDate, $endDate . ' 23:59:59'])
            ->select('order.order_taker_id as user_id', DB::raw('COUNT(*) as count'))
            ->groupBy('order.order_taker_id')
            ->get()
            ->map(function ($item) {
                return [
                    'user_id' => $item->user_id,
                    'count' => $item->count,
                ];
            });

        $sheetDetails = SheetDetails::where('review', 'Yes')->whereIn('order.order_taker_id', $userIds)
            ->join('order', 'sheet_details.orderId', '=', 'order.id')
            ->whereBetween('order.created_at', [$startDate, $endDate . ' 23:59:59'])
            ->select('order.order_taker_id as user_id', DB::raw('COUNT(*) as count'))
            ->groupBy('order.order_taker_id')
            ->get()
            ->map(function ($item) {
                return [
                    'user_id' => $item->user_id,
                    'count' => $item->count,
                ];
            });

        $dispatch = AutoOrder::whereNotNull('order.Schedule_Created')
            ->whereBetween('order.Schedule_Created', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->count();

        // dd($dispatch, $startDate . '00:00:00 - ', $endDate . ' 23:59:59');

        $today_n_customer = AutoOrder::where('paneltype', $paneltype)
            ->whereBetween('order.created_at', [$startDate, $endDate . ' 23:59:59'])
            ->whereNotNull('how_did_you_find_us')
            ->where('how_did_you_find_us', '!=', '')
            ->havingRaw('MIN(how_did_you_find_us) != "" AND MIN(how_did_you_find_us) IS NOT NULL')
            ->havingRaw('MAX(how_did_you_find_us) != "" AND MAX(how_did_you_find_us) IS NOT NULL')
            ->count();

        $today_phone_quote_db = AutoOrder::where('paneltype', $paneltype)
            ->whereBetween('order.created_at', [$startDate, $endDate . ' 23:59:59'])
            ->where('booking_confirm', 'confirm')
            ->count();

        $today_listed_db = AutoOrder::where('paneltype', $paneltype)
            ->whereBetween('order.Listed_Created', [$startDate, $endDate . ' 23:59:59'])
            ->where('booking_confirm', 9)
            ->count();

        $userTargets = UserTargets::whereIn('user_id', $data->pluck('order_taker_id'))->get();

        // dd($verifiedCounts->toArray(), $negativeCounts->toArray());
        // dd($date_range);

        if ($request->ajax()) {
            return view('agentReport.table', compact('data', 'date_range', 'userTargets', 'verifiedCounts', 'negativeCounts', 'sheetDetails', 'paneltype', 'dispatch', 'today_n_customer', 'today_phone_quote_db', 'today_listed_db'));
        }

        return view('agentReport.index', compact('data', 'date_range', 'userTargets', 'verifiedCounts', 'negativeCounts', 'sheetDetails', 'paneltype', 'dispatch', 'today_n_customer', 'today_phone_quote_db', 'today_listed_db'));
    }

    public function saveAgentReport(Request $request)
    {
        $existingReports = AgentReport::where('date_range', $request->date_range)
            ->orderby('id')
            ->get()
            ->keyBy('order_taker_id');

        foreach ($request->agents as $agentData) {
            $agentReport = $existingReports->get($agentData['order_taker_id']) ?? new AgentReport();
            $agentReport->order_taker_id = $agentData['order_taker_id'];
            $agentReport->date_range = $request->date_range;
            $agentReport->pquote_count = $agentData['pquote_count'];
            $agentReport->need_to_book = $agentData['need_to_book'];
            $agentReport->followup_achieve = $agentData['followup_achieve'];
            $agentReport->followup_target_achieve = $agentData['followup_target_achieve'] ?? null;
            $agentReport->order_target = $agentData['order_target'] ?? null;
            $agentReport->order_achieve = $agentData['order_achieve'] ?? null;
            $agentReport->on_app_order = $agentData['on_app_order'] ?? null;
            $agentReport->canceled_count = $agentData['cancelled'] ?? null;
            $agentReport->canceled_onapproval = $agentData['on_app_cancelled'] ?? null;
            $agentReport->followup_target = $agentData['followup_target'] ?? null;
            $agentReport->review_target = $agentData['review_target'] ?? null;
            $agentReport->review_achieve = $agentData['review_achieve'] ?? null;
            $agentReport->raw_details = $agentData['raw_details'] ?? null;
            $agentReport->recording_issue = $agentData['recording_issue'] ?? null;
            $agentReport->negligence = $agentData['negligence'] ?? null;
            $agentReport->greetings = $agentData['greetings'] ?? null;
            $agentReport->convincing = $agentData['convincing'] ?? null;
            $agentReport->further_issue = $agentData['further_issue'] ?? null;
            $agentReport->dispatch = $agentData['dispatch'] ?? null;
            $agentReport->business_delivery = $agentData['business_delivery'] ?? null;
            $agentReport->port_delivery = $agentData['port_delivery'] ?? null;
            $agentReport->private_pickup = $agentData['private_pickup'] ?? null;
            $agentReport->today_n_customer = $agentData['today_n_customer'] ?? null;
            $agentReport->today_phone_quote_db = $agentData['today_phone_quote_db'] ?? null;
            $agentReport->today_listed_db = $agentData['today_listed_db'] ?? null;
            $agentReport->review = $agentData['review'] ?? null;
            $agentReport->count = $agentData['count'] ?? null;
            $agentReport->save();
        }

        return redirect()->back()->with('success', 'Agent report saved successfully.');
    }

    public function customerReviews(Request $request)
    {
        // $data = SheetDetails::groupBy('orderId')->where('review', 'Yes')->where('website', '!=', null)->get();
        // dd($request->toArray());

        $query = SheetDetails::where('review', 'Yes')->whereNotNull('website');

        // Apply filters
        if ($request->has('clientRatingFilter') && !empty($request->clientRatingFilter)) {
            $query->where('client_rating', $request->clientRatingFilter);
        }

        if ($request->has('websiteFilter') && !empty($request->websiteFilter)) {
            $query->where('website', $request->websiteFilter);
        }

        if ($request->has('userFilter') && !empty($request->userFilter)) {
            $query->where('auth_id', $request->userFilter);
        }

        if (
            $request->has('start_date') && !empty($request->start_date) &&
            $request->has('end_date') && !empty($request->end_date)
        ) {
            $startDate = \Carbon\Carbon::parse($request->start_date)->startOfDay();
            $endDate = \Carbon\Carbon::parse($request->end_date)->endOfDay();

            $query->whereBetween('created_at', [$startDate, $endDate]);
            // dd($startDate, $endDate, $request->toArray(), $query->get()->toArray(), 'okok');
        }

        $data = $query->get();

        $users = User::whereIn('id', $data->pluck('auth_id'))->get();

        if ($request->ajax()) {
            return view('customerReviews.table', compact('data'));
        }

        return view('customerReviews.index', compact('data', 'users'));
    }

    public function addAchieveTarget(Request $request)
    {
        $users = User::with('userRole')
            ->where('deleted', 0)
            ->where(function ($query) {
                $query->whereHas('userRole', function ($q) {
                    $q->where('name', 'Order Taker');
                })->orWhere('id', 45);
            })
            ->get();


        return view('addAchieveTarget', compact('users'));
    }

    public function storeAchieveTarget(Request $request)
    {
        $user = new UserTargets;
        $user->user_id = $request->user_id;
        $user->order_target = $request->order_target;
        $user->followup_target = $request->followup_target;
        $user->review_target = $request->review_target;
        $user->revenew_target = $request->revenew_target;
        $user->target_month = $request->target_month;
        $user->save();

        return redirect()->route('addAchieveTarget');
    }

    public function flagUsers()
    {
        $id = Auth::id();

        $flag = Flag::with('user', 'customChat', 'groupChat')
            ->orderBy('id', 'DESC')
            ->paginate(50);

        // $flag = User::with('userRole', 'flag', 'flag_count')->withCount('flag')->where('deleted', 0)->where(function ($q) {
        //     $q->where('role', 2)->orWhere('role', 3)->orWhere('role', 1)->orWhere('role', 9)->orWhere('role', 8)->orWhere('role', 14)->orWhere('role', 17)->orWhere('role', 18);
        // })
        //     ->orderBy('flag_count', 'DESC')
        //     ->orderBy('updated_at', 'DESC')
        //     ->get();

        $countFlag = User::where('deleted', 0)->where(function ($q) {
            $q->where('role', 2)->orWhere('role', 3)->orWhere('role', 1)->orWhere('role', 9)->orWhere('role', 8)->orWhere('role', 14)->orWhere('role', 17)->orWhere('role', 18);
        })->whereHas('flag')->count();

        if (Auth::check()) {
            return view('main.register.flagusers', compact('flag', 'countFlag'));
        } else {
            return redirect('/loginn/');
        }
    }

    public function flagUsers2()
    {
        $flag = User::with('userRole', 'flag', 'flag_count')
            ->withCount('flag')
            ->where('deleted', 0)
            ->where(function ($q) {
                $q->where('role', 2)->orWhere('role', 3)->orWhere('role', 1)->orWhere('role', 9)->orWhere('role', 8)->orWhere('role', 14)->orWhere('role', 17)->orWhere('role', 18);
            })
            ->orderBy('flag_count', 'DESC')
            ->orderBy('updated_at', 'DESC')
            ->get();

        $latestFlags = Flag::with('user')
            ->where('created_at', '>=', now()->subSeconds(10))
            ->get();

        return response()->json([
            'html' => view('main.register.flagusers2', compact('flag', 'latestFlags'))->render(),
            'new_flag' => $latestFlags->isNotEmpty()
        ]);
    }

    public function checkNewChat()
    {
        $id = Auth::user()->id;

        $chat = chat::where('touserId', $id)
            ->where('created_at', '>=', now()->subSeconds(10))
            ->exists();

        $getGroupChat = GroupChat::whereHas('group.users', function ($q) use ($id) {
            $q->where('user_id', $id)->where('status', 0);
        })
            ->where('created_at', '>=', now()->subSeconds(10))
            ->orderby('created_at', 'desc')
            ->exists();

        $new_found = ($chat || $getGroupChat) ? 1 : 0;

        return response()->json([
            'new_found' => $new_found
        ]);
    }

    // public function request_check_price(Request $request)
    // {
    //     $data = PriceCheckerPrice::where('order_id', $request->order_id)->first();

    //     if (is_null($data)) {

    //         $users = User::where('role', 23)
    //             ->where('is_login', 1)
    //             ->get();


    //         $data = new PriceCheckerPrice;
    //         $data->requester_id = Auth::id();
    //         $data->order_id = $request->order_id;
    //         $data->price_giver_id = here we have to save used_id like we have to divide requests equally amoung all users who are online, like uid 1 is just saved then in next request some another user shud get chance;
    //         $data->save();

    //         return response()->json(['message' => 'Price requested successfully']);
    //     }

    //     if ($data->car == null && $data->suv == null && $data->pickup == null && $data->van == null) {
    //         return response()->json(['message' => 'Price already requested, please wait']);
    //     } else {
    //         return response()->json($data);
    //     }
    // }

    public function request_check_price(Request $request)
    {
        $data = PriceCheckerPrice::where('order_id', $request->order_id)->first();

        if (is_null($data)) {
            // --- Fetch price checkers ---
            $price_checker = User::where('role', 23)->where('is_login', 1)->where('deleted', 0)->get();
            if ($price_checker->isEmpty()) {
                $price_checker = User::where('role', 23)->where('deleted', 0)->orderBy('updated_at', 'desc')->get();
                if ($price_checker->isEmpty()) {
                    return response()->json(['message' => 'No price checker available'], 400);
                }
            }

            // --- Fetch dispatchers ---
            $dispatcher = User::where('role', 3)->where('is_login', 1)->where('deleted', 0)->get();
            if ($dispatcher->isEmpty()) {
                return response()->json(['message' => 'No dispatcher available'], 400);
            }

            // --- Round robin assignment ---
            $lastAssignedUserId = PriceCheckerPrice::whereIn('price_giver_id', $price_checker->pluck('id'))
                ->latest('created_at')
                ->value('price_giver_id');

            $nextUser = $this->getNextInRoundRobin($price_checker, $lastAssignedUserId);

            $lastAssignedDispatcherId = PriceCheckerPrice::whereIn('dispatcher_id', $dispatcher->pluck('id'))
                ->latest('created_at')
                ->value('dispatcher_id');

            $nextDispatcher = $this->getNextInRoundRobin($dispatcher, $lastAssignedDispatcherId);

            // --- Save record ---
            $data = new PriceCheckerPrice;
            $data->requester_id = Auth::id();
            $data->order_id = $request->order_id;
            $data->price_giver_id = $nextUser->id;
            $data->save();

            return response()->json(['message' => 'Price requested successfully']);
        }

        // --- Already requested but not yet priced ---
        if (is_null($data->car) && is_null($data->suv) && is_null($data->pickup) && is_null($data->van) && is_null($data->other)) {
            return response()->json(['message' => 'Price already requested, please wait']);
        }

        // --- Return result (filtered if needed) ---
        return response()->json($data);
    }

    public function price_request_pending(Request $request){
        $items = PriceCheckerPrice::query()
            ->whereBetween('created_at', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()])
            ->where(function ($query) {
                $query->whereNull('car')
                    ->whereNull('suv')
                    ->whereNull('pickup')
                    ->whereNull('van');
            })
            ->where('dispatcher_id', 0)
            ->latest('created_at')
            ->take(20)
            ->get(['id', 'order_id', 'requester_id', 'created_at']);

        // Shape a lean response (extend as needed)
        return response()->json(
            $items->map(function ($row) {
                return [
                    'id'         => $row->id,
                    'order_id'   => $row->order_id,
                    'created_at' => $row->created_at->toDateTimeString(),
                ];
            })
        );
    }

    public function price_request_dispatcher_view($id , Request $request){

        $data = PriceCheckerPrice::where('id',$id)->where('dispatcher_id',0)->first();
        if(!empty($data)) {
            $autoorder = AutoOrder::where('id', $data->order_id)->first();
            $dis_id = auth()->user()->id;
            if (auth()->user()->role == 3) {
                $data->dispatcher_id = $dis_id;
                $autoorder->dispatcher_id = $dis_id;
                $autoorder->save();

                $user = DailyQoute::whereDate('date', date('Y-m-d'))->where('user_id', $dis_id)->first();
                if (isset($user->id)) {
                    $user->total_qoute = $user->total_qoute + 1;
                    $user->save();
                }
            }
            $data->save();


            $data = PriceCheckerPrice::with('order')
                ->where('id', $id)
                ->where('dispatcher_id', $dis_id)
                ->where(function ($query) {
                    $query->whereNull('car')
                        ->whereNull('suv')
                        ->whereNull('pickup')
                        ->whereNull('van');
                })
                ->get();

            if (count($data) > 0) {
                return view('main.dashboard.check_price', compact('data'));
            } else {
                return redirect('/');
            }
        }else{
            return redirect('/');
        }


    }

    /**
     * Round robin helper
     */
    private function getNextInRoundRobin($users, $lastAssignedId)
    {
        if (!$lastAssignedId) {
            return $users->first();
        }

        $sorted = $users->sortBy('id')->values();
        $index = $sorted->search(fn ($user) => $user->id == $lastAssignedId);

        return $sorted->get($index + 1) ?? $sorted->first();
    }


    public function savePrice(Request $request)
    {
        $autoorder = AutoOrder::find($request->order_id);
        $data = PriceCheckerPrice::where('order_id', $request->order_id)->first();
        if (!$data) {
            $data = new PriceCheckerPrice;
            $data->order_id = $request->order_id;
            $data->price_giver_id = Auth::id();
        }

        $data->car = isset($request->car) ? $request->car : $data->car;
        $data->suv = isset($request->suv) ? $request->suv : $data->suv;
        $data->pickup = isset($request->pickup) ? $request->pickup : $data->pickup;
        $data->van = isset($request->van) ? $request->van : $data->van;
        $data->other = isset($request->other) ? $request->other : $data->other;
        if(auth()->user()->role == 3){
            $data->dispatcher_id = auth()->user()->id;

            $dis_id = $data->dispatcher_id;
            $autoorder->dispatcher_id = $dis_id;
            $autoorder->save();


            $user = DailyQoute::whereDate('date', date('Y-m-d'))->where('user_id', $dis_id)->first();
            if (isset($user->id)) {
                $user->total_qoute = $user->total_qoute + 1;
                $user->save();
            }
        }
        $data->save();


        return response()->json([
            'success' => true,
            'message' => 'Price saved successfully!',
            'data' => $data
        ]);
    }

    public function get_check_price(Request $request)
    {
        $data = PriceCheckerPrice::where('order_id', $request->order_id)->first();
        $type = $request->getCheckPrice; // type could be 'car', 'suv', 'pickup', or 'van'

        if (!$data) {
            return response()->json(['message' => 'Price not available atm']);
        }

        if (is_null($data->$type)) {
            return response()->json(['message' => 'Price not available for this type']);
        }

        return response()->json(['price' => $data->$type]);
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('uploads/summernote', 'public');
            return response()->json(['url' => asset('storage/' . $path)]);
        }

        return response()->json(['error' => 'File upload failed'], 400);
    }



    public function checkForPrice(Request $request)
    {
        $data = PriceCheckerPrice::where('order_id', $request->order_id)->first();

        if ($data && ($data->car || $data->suv || $data->pickup || $data->van || $data->other)) {
            return response()->json($data);
        } else {
            return false;
        }
    }

    public function previousCheckPrices(Request $request)
    {
        $ocity = base64_decode($request->ocity);
        $dcity = base64_decode($request->dcity);
        $order_id = base64_decode($request->order_id);
        $ocity2 = explode(',', $ocity);
        $dcity2 = explode(',', $dcity);

        $data = PriceCheckerPrice::with('order')->whereHas('order', function ($q) use ($ocity, $dcity, $order_id) {
            $q->where('originzsc', $ocity)
                ->where('destinationzsc', $dcity)
                ->where('id', '!=', $order_id);
        })->limit(10)->orderBy('created_at', 'DESC')->get();

        return view('main.phone_quote.new.previous_check_prices', compact('data'));
    }

    public function fetchNotifications()
    {
        $notifications = PriceCheckerPrice::where(function ($query) {
            $query->whereNull('car')
                ->orWhereNull('suv')
                ->orWhereNull('pickup')
                ->orWhereNull('van');
        })
            ->whereBetween('created_at', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()]) // Filter by today's date

            ->orderBy('id', 'DESC')
            ->get();

        return response()->json([
            'count' => $notifications->count(),
            'notifications' => $notifications,
        ]);
    }


    public function websiteQuery(Request $request)
    {


            $data = new ShipaQuery;
            $data->oname = $request['oname'];
            $data->oemail = $request['oemail'];
            $data->ophone = $request['ophone'];
            $data->ymk = $request['ymk'];
            $data->year = $request['year'];
            $data->type = $request['type'];
            $data->vehicle_opt = $request['vehicle_opt'];
            $data->model = $request['model'];
            $data->make = $request['make'];
            $data->condition = $request['condition'];
            $data->originzsc = $request['originzsc'];
            $data->originzip = $request['originzip'];
            $data->originstate = $request['originstate'];
            $data->origincity = $request['origincity'];
            $data->destinationzsc = $request['destinationzsc'];
            $data->destinationzip = $request['destinationzip'];
            $data->destinationstate = $request['destinationstate'];
            $data->destinationcity = $request['destinationcity'];
            $data->add_info = $request['add_info'];
            $data->transport = $request['transport'];
            $data->shippingdate = $request['shippingdate'];
            $data->car_type = $request['car_type'];
//            $data->paneltype = $request['paneltype'];
            $data->paneltype = 2;
            $data->cname = $request['cname'];
            $data->cemail = $request['cemail'];
            $data->main_ph = $request['main_ph'];
            $data->length_ft = $request['length_ft'];
            $data->length_in = $request['length_in'];
            $data->width_ft = $request['width_ft'];
            $data->width_in = $request['width_in'];
            $data->height_ft = $request['height_ft'];
            $data->height_in = $request['height_in'];
            $data->weight = $request['weight'];
            $data->load_method = $request['load_method'];
            $data->unload_method = $request['unload_method'];
            $data->ip_address = $request['ip'];
            $data->ipcity = $request['ipcity'];
            $data->ipregion = $request['ipregion'];
            $data->ipcountry = $request['ipcountry'];
            $data->iploc = $request['iploc'];
            $data->ippostal = $request['ippostal'];
            $data->image = $request['image'];
            $data->available_at_auction = $request['available_at_auction'];
            $data->modification = $request['modification'];
            $data->modify_info = $request['modify_info'];
            $data->link = $request['link'];
            $data->rv_type = $request['rv_type'];
            $data->boat_on_trailer = $request['boat_on_trailer'];
            $data->pstatus = 0;
            $data->source = 'ShipA1';
            $data->roro = isset($request['roro']) ? $request['roro'] : NULL;
            $data->heavy_type = isset($request['heavy_type']) ? $request['heavy_type'] : NULL;
            $data->category = isset($request['category']) ? $request['category'] : NULL;
            $data->subcategory = isset($request['subcategory']) ? $request['subcategory'] : NULL;
            $data->save();


            return response()->json([
                'data' => $data,
                'status' => true,
                'status_code' => 201
            ]);

    }

    public function get_canelation_data(Request $request)
    {
        $originOrders = [];
        $destinationOrders = [];
        $combinedOrders = [];

        if (!empty($request->originzsc)) {
            $originOrders = AutoOrder::where('originzsc', $request->originzsc)
                ->where('pstatus',14)
                ->orderby('id','desc')
                ->take(5)
                ->get();
        }

        if (!empty($request->destinationzsc)) {
            $destinationOrders = AutoOrder::where('destinationzsc', $request->destinationzsc)
                ->where('pstatus',14)
                ->orderby('id','desc')
                ->take(5)
                ->get();
        }

        // Combine both queries (intersect)
        if (!empty($request->originzsc) && !empty($request->destinationzsc)) {
            $combinedOrders = AutoOrder::where('originzsc', $request->originzsc)
                ->where('destinationzsc', $request->destinationzsc)
                ->where('pstatus',14)
                ->orderby('id','desc')
                ->take(5)
                ->get();
        }

        return response()->json([
            'status' => 1,
            'origin' => $originOrders,
            'destination' => $destinationOrders,
            'combined' => $combinedOrders,
        ]);
    }

    public function order_tracking($id)
    {
        $autoorder = AutoOrder::select(
            'id',
            'oname',
            'ophone',
            'year',
            'make',
            'model',
            'originzsc',
            'destinationzsc',

            'condition',
            'payment',
            'pstatus'
        )->find($id);

        if (!$autoorder) {
            abort(404, 'Order not found.');
        }

        $currentStatus = $this->get_pstatuss($autoorder->pstatus);

        return view('main.phone_quote.new_quote.order_tracking', compact('autoorder', 'currentStatus'));
    }
}

