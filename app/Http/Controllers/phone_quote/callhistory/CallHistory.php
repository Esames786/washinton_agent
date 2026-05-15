<?php

namespace App\Http\Controllers\phone_quote\callhistory;

use App\call_history;
use App\PaymentSystem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\role;
use App\AutoOrder;
use App\report;
use App\singlereport;
use App\zipcodes;
use App\count_click;
use App\user_setting;
use App\general_setting;
use Session;
use Redirect;
use Hash;
use Mail;
use Auth;
use DB;
use DateTime;
use App\DailyQoute;
use App\count_day;
use Carbon\Carbon;
use Vinkla\Hashids\Facades\Hashids;
use App\SheetDetails;
use App\PhoneDigit;
use App\QaVerifyHistory;
use App\RequestShipment;
use App\carrier;
use App\CarrierApproaching;
use App\SiteSetting;
use App\Mail\PickupConfirmationMail;
use App\Mail\DeliveredConfirmationMail;
use App\Mail\CompletedMail;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Exception;
use Illuminate\Support\Facades\Http;

class CallHistory extends Controller
{
    public function get_pstatuss($id)
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
        } elseif ($id == 99) {
            $ret = "Approaching";
        }
        return $ret;
    }

    function count_no_response($order_id, $user_id)
    {
        $count_no_v = 0;
        $temp = 0;
        $count_no = report::where('orderId', '=', $order_id)
            ->where('userId', $user_id)
            ->where('pstatus', '=', 5)
            ->count();
        if ($count_no > 3) {

            $count_no = report::select('userId', 'pstatus', DB::raw('COUNT(pstatus) AS sum_of_1,COUNT(userId) AS sum_of_2'))
                ->where('pstatus', 5)
                ->where('orderId', '=', $order_id)
                ->where('userId', '!=', $user_id)
                ->groupby('pstatus', 'userId')
                ->get();

            foreach ($count_no as $val) {
                if ($val->sum_of_1 > 3) {
                    $temp = $val->sum_of_1;
                }
            }

            if (!empty($temp) > 3) {

                return $temp;
            } else {
                return $temp;
            }
        } else {
            return $temp;
        }
    }

    public function call_history_post_2(Request $request)
    {
        $autoorder = AutoOrder::find($request->order_id1);
        if (isset($autoorder->id)) {
            if (!empty($request->approaching)) {
                $autoorder->approaching_time = \Carbon\Carbon::now();
                $autoorder->approaching_user = 0;
            }
            $autoorder->updated_at = now();
            $autoorder->save();

            $last_status = $this->get_pstatuss($autoorder->pstatus);

            $callhistory = new call_history();
            $callhistory->userId = Auth::user()->id;
            $callhistory->orderId = $request->order_id1;
            $callhistory->pstatus = 99;
            $callhistory->approach = 1;
            $callhistory->history = "<h6>LAST STATUS : " . $last_status . "</h6><h6>Remarks: " . $request->history_update . "</h6>";
            $callhistory->save();
        }

        Session::flash('flash_message', 'Approaching history updated!');
        return redirect()->back();
    }

    function call_history_post(Request $request)
    {
        // dd($request->toArray());
        if (Auth::check()) {

            $last_status = "";
            $autoorder = AutoOrder::with('freight')->find($request->order_id1);
            if (isset($request->cancelDirectOnApproval) && $request->cancelDirectOnApproval != null) {
                $autoorder->cancelDirectOnApproval = $request->cancelDirectOnApproval;
                $autoorder->save();
            }
            if (isset($autoorder->id)) {
                if (empty($autoorder->completer_id)) {
                    if ($request->pstatus == 13) {
                        $autoorder->completer_id = Auth::user()->id;

                        // dd($autoorder->toArray(), 'oksss', $autoorder->oemail, '$request->toArray()');

                        try {
                            // Mail::to(['info@hellotransport.com'])->send(new CompletedMail($autoorder));
                            if (filter_var($autoorder->oemail, FILTER_VALIDATE_EMAIL)) {
                                Mail::to([$autoorder->oemail, 'info@hellotransport.com'])->send(new CompletedMail($autoorder));
                            } else {
                                Mail::to(['info@hellotransport.com'])->send(new CompletedMail($autoorder));
                            }
                        } catch (\Exception $e) {
                            return back()->with('error', 'Error sending email');
                        }
                    }
                }
                $fdate = date('m/d/Y');
                $tdate = $autoorder->pickup_date;
                $datetime1 = new DateTime($fdate);
                $datetime2 = new DateTime($tdate);
                $interval = $datetime1->diff($datetime2);
                $days = $interval->format('%a');
                if ($days > 0) {
                    $autoorder->t_q_late = $days;
                }

                // last status
                $pstatus_old = $autoorder->pstatus;
                $last_status = $this->get_pstatuss($pstatus_old);
                if ($autoorder->schedule_approve == 1 && $autoorder->pstatus == 10) {
                    $last_status = 'Schedule To Another Driver';
                }
                // if (!empty($request->approaching)) {
                //     $autoorder->approaching_time = \Carbon\Carbon::now();
                // }



                // if(!isset($request->expected_date)){
                //     $request->expected_date = date('Y-m-d');
                // }
                $expected_date = '';
                if (isset($request->expected_date)) {
                    $expected_date = $request->expected_date;
                }
                if (isset($request->pickup_date)) {
                    $expected_date = $request->pickup_date;
                }
                if (isset($request->deliver_date)) {
                    $expected_date = $request->deliver_date;
                }

                //check no response count
                if (!empty($request->pstatus)) {
                    if ($request->pstatus == 10) {
                        if (isset($request->already_late1)) {
                            if (empty($autoorder->already_storage)) {
                                $autoorder->already_auction_storage = 1;
                                $autoorder->already_storage = $request->already_storage;
                                $autoorder->already_storage_date = $expected_date ?? date('Y-m-d');
                            }
                        }
                        if (isset($request->already_late2)) {
                            if (empty($autoorder->late_pickup_storage)) {
                                $autoorder->late_pickup_auction_storage = 1;
                                $autoorder->late_pickup_storage = $request->late_pickup_storage;
                                $autoorder->late_pickup_storage_date = $expected_date ?? date('Y-m-d');
                            }
                        }
                        $dis_id = '';
                        if (!empty($autoorder->dispatcher_id)) {
                            $dis_id = $autoorder->dispatcher_id;
                        } else {
                            if (Auth::user()->userRole->name == 'Dispatcher') {
                                $autoorder->dispatcher_id = Auth::user()->id;
                                $autoorder->save();
                                $dis_id = Auth::user()->id;
                            }
                        }

                        $user = DailyQoute::whereDate('date', date('Y-m-d'))->where('user_id', $dis_id)->first();
                        if (isset($user->id)) {
                            $user->total_qoute = $user->total_qoute + 1;
                            $user->save();
                        }
                    }
                    // if ($request->pstatus == "5") {
                    // $count_no_response = $this->count_no_response($request->order_id1, Auth::user()->id);
                    // if ($count_no_response < 3) {
                    //     print_r($request->all());
                    //     exit();
                    //     $autoorder->pstatus = $pstatus_old;
                    // } else {
                    if ($request->pstatus >= 7) {
                        if (empty($autoorder->u_id)) {
                            $autoorder->u_id = Auth::user()->id;
                            $autoorder->save();
                        }
                    }
                    $checkReport = report::where('pstatus', 7)->orWhere('pstatus', 18)->where('orderId', $autoorder->id)->first();
                    if (isset($request->pstatus)) {
                        if ($request->pstatus == 7 || $request->pstatus == 18) {
                            if (empty($checkReport)) {
                                if (Auth::user()->assign_daily_qoute > 0) {
                                    $daily = DailyQoute::where('user_id', Auth::user()->id)->whereDate('date', date('Y-m-d'))->first();
                                    $daily->total_qoute = $daily->total_qoute + 1;
                                    $daily->save();
                                }
                            }
                        }
                    }
                    if ($request->pstatus >= 9) {
                        if (empty($autoorder->dispatcher_id)) {
                            if (isset($request->dis_id) && !empty($request->dis_id)) {
                                $autoorder->dispatcher_id = $request->dis_id;

                                $user = DailyQoute::with('user.userRole')->where('total_qoute', '>', 0)->whereDate('date', date('Y-m-d'))
                                    ->where('user_id', $request->dis_id)->first();

                                if (isset($user->id)) {
                                    $daily = DailyQoute::where('user_id', $user->user->id)->whereDate('date', date('Y-m-d'))->first();
                                    if (!isset($daily->id)) {
                                        $total = $user->user->assign_daily_qoute;
                                        $daily = new DailyQoute;
                                        $daily->user_id = $user->user->id;
                                        $daily->date = date('Y-m-d');
                                        $daily->total_qoute = $total - 1;
                                        $daily->save();
                                    } else {
                                        if ($user->total_qoute > 0) {
                                            $daily->total_qoute = $daily->total_qoute - 1;
                                            $daily->save();
                                        }
                                    }
                                }
                            } else {
                                $checkReport2 = report::where('pstatus', $request->pstatus)->where('orderId', $autoorder->id)->first();
                                if (empty($checkReport2)) {

                                    $user = DailyQoute::with('user.userRole')->where('total_qoute', '>', 0)->whereDate('date', date('Y-m-d'))
                                        ->whereHas('user', function ($q) {
                                            $q->where('deleted', 0)->where('is_login', 1)->where('auto_assign', 1);
                                        })->whereHas('user.userRole', function ($q) {
                                            $q->where('name', 'Dispatcher');
                                        })->orderBy('total_qoute', 'DESC')->first();

                                    // if(empty($user))
                                    // {
                                    //     $user = DailyQoute::with('user.userRole')->whereDate('date',date('Y-m-d'))
                                    //     ->whereHas('user',function ($q){
                                    //         $q->where('deleted',0)->where('is_login',1)->where('auto_assign',1);
                                    //     })->whereHas('user.userRole',function ($q){
                                    //         $q->where('name','Dispatcher');
                                    //     })->inRandomOrder()->first();
                                    // }

                                    if (isset($user->user->id)) {
                                        if ($user->user->userRole->name == 'Dispatcher') {
                                            $daily = DailyQoute::where('user_id', $user->user->id)->whereDate('date', date('Y-m-d'))->first();
                                            if (!isset($daily->id)) {
                                                $total = $user->user->assign_daily_qoute;
                                                $daily = new DailyQoute;
                                                $daily->user_id = $user->user->id;
                                                $daily->date = date('Y-m-d');
                                                $daily->total_qoute = $total - 1;
                                                $daily->save();
                                            } else {
                                                if (isset($user->id)) {
                                                    if ($user->total_qoute > 0) {
                                                        $daily->total_qoute = $daily->total_qoute - 1;
                                                        $daily->save();
                                                    }
                                                }
                                            }
                                        }
                                        $autoorder->dispatcher_id = $user->user->id;
                                    }
                                    // else
                                    // {
                                    //     $user = User::with('userRole')
                                    //     ->where('deleted',0)->where('auto_assign',1)
                                    //     ->whereHas('userRole',function ($q){
                                    //         $q->where('name','Dispatcher');
                                    //     })
                                    //     ->inRandomOrder()->first();

                                    //     $autoorder->dispatcher_id = $user->id;
                                    // }
                                }
                            }
                        }
                    }

                    // }
                    // } else {
                    // $autoorder->pstatus = $request->pstatus;
                    // }
                }
                if ($request->pstatus == 12) {
                    if (empty($autoorder->storage_move_date)) {
                        $autoorder->storage_move_date = date('Y-m-d');
                    }
                }
                if ($request->pstatus == 34) {
                    $autoorder->pstatus = 10;
                    $autoorder->schedule_approve = 1;
                } else {
                    if ($request->pstatus == 10) {
                        $autoorder->schedule_approve = 0;
                    }
                    $autoorder->pstatus = $request->pstatus;
                }

                if (!empty($request->listed_price)) {
                    $autoorder->listed_price = $request->listed_price;
                    $autoorder->pay_carrier = $request->listed_price;
                    $carrier = carrier::where('orderId', '=', $autoorder->id)->get();
                    foreach ($carrier as $key => $val) {
                        $val->who_pickup = 0;
                        $val->save();
                    }
                }
                $company_name = NULL;
                if (isset($request->current_carrier)) {
                    $autoorder->carrier_id = $request->current_carrier;
                    $company_data = carrier::find($request->current_carrier);
                    if (isset($company_data->companyname)) {
                        $carrier = carrier::where('orderId', '=', $autoorder->id)->get();
                        foreach ($carrier as $key => $val) {
                            $val->who_pickup = 0;
                            $val->save();
                        }
                        $company_name = $company_data->companyname;
                        if ($request->pstatus == 11) {
                            $company_data->who_pickup = 1;
                            $company_data->carrier_cancel = 0;
                            $company_data->save();
                        }
                    }
                }
                if (!empty($request->pickup_date)) {
                    $autoorder->driver_pickup_date = $request->pickup_date;
                }
                // if ($request->pstatus == "11") {
                //     $autoorder->driver_deliver_date = $request->expected_date;
                // }
                if ($request->pstatus == "3") {
                    $autoorder->asking_low = $request->asking_low;
                }
                if (isset($request->approvalpickup) && $request->approvalpickup == 1) {
                    $autoorder->approve_pickup = 1;
                }
                if (isset($request->deliver_date)) {
                    $autoorder->driver_deliver_date = $request->deliver_date;
                    // $request->expected_date =  $request->deliver_date;

                }
                if (isset($request->condition)) {
                    $autoorder->condition = $request->condition;
                    if ($request->condition == 2) {
                        $autoorder->v_con_p = $request->v_con_p;
                        $autoorder->v_con_d = $request->v_con_d;
                    }
                }
                if (isset($request->approvaldeliver) && $request->approvaldeliver == 1) {
                    $autoorder->approve_deliver = 1;
                }


                // if(!isset($request->pstatus)){
                //     $request->pstatus = $pstatus_old;
                // }
                $autoorder->updated_at = now();
                $autoorder->save();

                if (isset($request->pstatus222)) {
                    $model = new SheetDetails;
                    $model->auth_id = Auth::user()->id;
                    $model->orderId = $request->order_id1;
                    if ($request->pstatus222 == 9) {
                        $model->pstatus = 9;
                        $model->paid = $request->auc_paid;
                        $model->storage = $request->auc_storage;
                        $model->listed_price = $request->auc_listed_price;
                        $model->auction_update = $request->auc_auction_update;
                        $model->title_keys = $request->auc_title_keys;
                        $model->keys = $request->auc_keys;
                        $model->vehicle_position = $request->auc_port;
                        $model->listed_count = $request->auc_listed_count;
                        $model->price = $request->auc_price;
                        $model->additional = $request->auc_additional;
                        $model->save();

                        // if ($autoorder->paneltype == 1 || $autoorder->paneltype == 2) {
                        $ddApi = SiteSetting::find(1);
                        // dd($request->list_in_daydispatch, $ddApi->allow);
                        // if (isset($request->list_in_daydispatch) && $ddApi->allow == 1) {
                        if ($ddApi->allow == 1) {
                            try {
                                $client = new Client();

                                $payload = [
                                    'id' => $autoorder->id,
                                    'created_at' => $autoorder->created_at,
                                    'Custom_Listing' => $autoorder->Custom_Listing ?? 0,
                                    'Private_Listing' => $autoorder->Private_Listing ?? 0,
                                    'car_type' => $autoorder->car_type,

                                    'oaddress' => $autoorder->oaddress,
                                    'oaddress2' => $autoorder->oaddress2,
                                    'originzsc' => $autoorder->originzsc,
                                    'oname' => $autoorder->oname,
                                    'oemail' => $autoorder->oemail,
                                    'phone' => $autoorder->phone,

                                    'daddress' => $autoorder->daddress,
                                    'daddress2' => $autoorder->daddress2,
                                    'destinationzsc' => $autoorder->destinationzsc,
                                    'dname' => $autoorder->dname,
                                    'demail' => $autoorder->demail,
                                    'dphone' => $autoorder->dphone,

                                    'pickup_date' => $autoorder->pickup_date,
                                    'pickup_when' => $autoorder->pickup_when,
                                    'delivery_date' => $autoorder->delivery_date,
                                    'delivery_when' => $autoorder->delivery_when,

                                    'payment' => $autoorder->payment,
                                    'driver_price' => $autoorder->driver_price,
                                    'pay_carrier' => $autoorder->pay_carrier,
                                    'cod_cop' => $autoorder->cod_cop,
                                    'balance' => $autoorder->balance,
                                    'payment_method' => $autoorder->payment_method,
                                    'balance_method' => $autoorder->balance_method,
                                    'cod_cop_loc' => $autoorder->cod_cop_loc,
                                    'balance_time' => $autoorder->balance_time,
                                    'terms' => $autoorder->terms,
                                    'payment_desc' => $autoorder->payment_desc,
                                    'PaymentInfo' => $autoorder->PaymentInfo,
                                    'BalPaymentInfo' => $autoorder->BalPaymentInfo,

                                    'add_info' => $autoorder->add_info,

                                    'vin_num' => $autoorder->vin_num,
                                    'year' => $autoorder->year,
                                    'make' => $autoorder->make,
                                    'model' => $autoorder->model,
                                    'transport' => $autoorder->transport,
                                    'condition' => $autoorder->condition,
                                    'type' => $autoorder->type,
                                    'length_ft' => $autoorder->length_ft,
                                    'length_in' => $autoorder->length_in,
                                    'width_ft' => $autoorder->width_ft,
                                    'width_in' => $autoorder->width_in,
                                    'height_ft' => $autoorder->height_ft,
                                    'height_in' => $autoorder->height_in,
                                    'weight' => $autoorder->weight,
                                ];

                                if ($autoorder->freight) {
                                    $payload['freight'] = [
                                        'frieght_class' => $autoorder->freight->frieght_class ?? null,
                                        'shipment_prefences' => $autoorder->freight->shipment_prefences ?? null,
                                        'total_weight_lbs' => $autoorder->freight->total_weight_lbs ?? null,
                                        'trailer_specification' => $autoorder->freight->trailer_specification ?? null,
                                        'equipment_type' => $autoorder->freight->equipment_type ?? null,
                                    ];
                                }

                                \Log::info('DayDispatch outgoing payload', [
                                    'id' => $payload['id'] ?? null,
                                    'payload' => $payload,
                                ]);

                                $response = $client->post('https://daydispatch.com/api/New-Listing', [
                                    'json' => $payload,
                                ]);

                                $responseData = json_decode($response->getBody(), true);

                            } catch (RequestException $e) {
                                $errorResponse = $e->getResponse();

                                \Log::error('DayDispatch API RequestException', [
                                    'message' => $e->getMessage(),
                                    'status' => $errorResponse ? $errorResponse->getStatusCode() : null,
                                    'response' => $errorResponse ? (string) $errorResponse->getBody() : null,
                                    'order_id' => $autoorder->id ?? null,
                                ]);
                            } catch (\Throwable $e) {
                                \Log::error('DayDispatch API Throwable', [
                                    'message' => $e->getMessage(),
                                    'line' => $e->getLine(),
                                    'file' => $e->getFile(),
                                    'order_id' => $autoorder->id ?? null,
                                ]);
                            }
                        }
                        // }
                    }
                    if ($request->pstatus222 == 10) {
                        $model->pstatus = 10;
                        $model->company_name = $company_name;
                        $model->pickup_date = $request->auc_pickedup;
                        $model->vehicle_condition = $request->auc_condition;
                        $model->title_keys = $request->auc_title_keys;
                        $model->keys = $request->auc_keys;
                        $model->storage = $request->auc_storage;
                        $model->auction_update = $request->auc_auction_update;
                        $model->who_pay_storage = $request->auc_who_pay_storage;
                        $model->vehicle_position = $request->auc_vehicle_position;
                        $model->delivery_date = $request->auc_delivery_date;
                        $model->driver_fmcsa = $request->auc_driver_fmcsa;
                        $model->carrier_rating = $request->auc_carrier_rating;
                        $model->fmcsa = $request->auc_fmcsa;
                        $model->coi_holder = $request->auc_coi_holder;
                        $model->vehicle_luxury = $request->auc_vehicle_luxury;
                        $model->aware_driver_delivery_date = $request->auc_aware_driver_delivery_date;
                        $model->price = $request->auc_price;
                        $model->insurance_date = $request->auc_insurance_date;
                        $model->new_old_driver = $request->auc_new_old_driver;
                        $model->payment_method = $request->auc_payment_method;
                        $model->is_local = $request->auc_is_local;
                        $model->job_accept = $request->auc_job_accept;
                        $model->additional = $request->auc_additional;
                        $model->save();
                    }
                    if ($request->pstatus222 == 11) {
                        $model->pstatus = 11;
                        $model->driver_status = $request->auc_driver_status;
                        $model->storage = $request->auc_storage;
                        $model->delivery_date = $request->auc_delivery_date;
                        $model->vehicle_condition = $request->auc_condition;
                        $model->title_keys = $request->auc_title_keys;
                        $model->keys = $request->auc_keys;
                        $model->vehicle_position = $request->auc_vehicle_position;
                        $model->payment = $request->auc_payment;
                        $model->payment_charged_or_owes = $request->auc_payment_charged_or_owes;
                        $model->payment_method = $request->auc_payment_method;
                        $model->carrier_name = $request->auc_carrier_name;
                        $model->driver_payment = $request->auc_driver_payment;
                        $model->price = $request->auc_price;
                        $model->stamp_dock_receipt = $request->auc_stamp_dock_receipt;
                        $model->company_name = $request->auc_company_name;
                        $model->driver_no = $request->auc_driver_no;
                        $model->driver_no2 = $request->auc_driver_no2;
                        $model->driver_no3 = $request->auc_driver_no3;
                        $model->driver_no4 = $request->auc_driver_no4;
                        $model->additional = $request->auc_additional;
                        $model->save();

                        $model2 = new SheetDetails;
                        $model2->auth_id = Auth::user()->id;
                        $model2->orderId = $request->order_id1;
                        $model2->pstatus = 11;
                        $model2->auction_status = $request->auc_status1;
                        $model2->storage = $request->auc_storage1;
                        $model2->vehicle_condition = $request->auc_condition1;
                        $model2->title_keys = $request->auc_title_keys1;
                        $model2->keys = $request->auc_keys1;
                        $model2->vehicle_position = $request->auc_vehicle_position1;
                        $model2->additional = $request->auc_additional1;
                        $model2->save();
                    }
                    if ($request->pstatus222 == 12) {
                        $model->pstatus = 12;
                        $model->driver_no = $request->auc_driver_no;
                        $model->driver_no2 = $request->auc_driver_no2;
                        $model->driver_no3 = $request->auc_driver_no3;
                        $model->driver_no4 = $request->auc_driver_no4;
                        $model->driver_status = $request->auc_driver_status;
                        $model->driver_payment_status = $request->auc_driver_payment_status;
                        $model->vehicle_condition = $request->auc_condition;
                        $model->customer_informed = $request->auc_customer_informed;
                        $model->delivery_date = $request->auc_delivery_date;
                        $model->vehicle_position = $request->auc_vehicle_position;
                        $model->who_pay_storage = $request->auc_who_pay_storage;
                        $model->title_keys = $request->auc_title_keys;
                        $model->keys = $request->auc_keys;
                        $model->client_status = $request->auc_client_status;
                        $model->owes_status = $request->auc_owes_status;
                        $model->additional = $request->auc_additional;
                        $model->save();
                    }
                    if ($request->pstatus222 == 13) {
                        $model->pstatus = 13;
                        $model->remarks = $request->auc_remarks;
                        $model->comments = $request->auc_comments;
                        $model->satisfied = $request->auc_satisfied;
                        $model->review = $request->auc_review;
                        $model->website = $request->auc_website;
                        $model->website_other = $request->auc_website_other;
                        $model->client_rating = $request->auc_client_rating;
                        $model->website_link = $request->auc_website_link;
                        $model->additional = $request->auc_additional;
                        $model->save();
                    }
                }

                if (isset($request->pstatus) || $request->pstatus >= 0) {
                    if ($request->pstatus == 34) {
                        $ppp = 10;
                    } else {
                        $ppp = $request->pstatus;
                    }
                } else {
                    $ppp = $pstatus_old;
                }
                $carrier_deleted = '';
                if (isset($request->carrier_deleted)) {
                    if ($request->carrier_deleted == 'OTHER') {
                        $carrier_deleted = '<h6>OTHER REASON: ' . $request->other_reason . '</h6>';
                    } else {
                        if (isset($request->company_cancel)) {
                            $carrier = carrier::find($request->company_cancel);
                            $carrier->carrier_cancel = 1;
                            $carrier->save();
                        } else {
                            $carrier = carrier::where('orderId', $autoorder->id)->orderBy('created_at', 'DESC')->first();
                        }
                        $comp = '';
                        if (isset($carrier->companyname)) {
                            $comp = $carrier->companyname;
                        }
                        $carrier_deleted = '<h6>' . $comp ? $comp . ': ' : '' . $request->carrier_deleted . '</h6>';
                    }
                }

                $callhistory = new call_history();
                $callhistory->userId = Auth::user()->id;
                $callhistory->orderId = $request->order_id1;
                $callhistory->pstatus = $ppp;
                $callhistory->history = "<h6>LAST STATUS : " . $last_status . "</h6>" . $carrier_deleted . "<h6>Remarks: " . $request->history_update . "</h6>";
                if ($ppp == 14) {
                    if (isset($request->agree_disagree)) {
                        $callhistory->agree_disagree = $request->agree_disagree;
                    }
                    if (isset($request->mistaker)) {
                        $callhistory->mistaker = $request->mistaker;
                    }
                    if (isset($request->mistaker_id)) {
                        $callhistory->mistaker_id = $request->mistaker_id;
                    }
                    if (isset($request->no_of_calls)) {
                        $callhistory->no_of_calls = $request->no_of_calls;
                    }
                    if (isset($request->decision)) {
                        $callhistory->decision = $request->decision;
                    }
                }
                $callhistory->created_at = now();
                $callhistory->updated_at = now();
                $callhistory->save();

                if ($autoorder->pstatus == 11 || $autoorder->pstatus == 12 || $autoorder->pstatus == 10) {
                    if ($autoorder->pstatus == 11) {
                        if (isset($request->approvalpickup) && $request->approvalpickup == 1) {
                            $pp = 11;
                        } else {
                            $pp = 30;
                        }
                    } else if ($autoorder->pstatus == 10) {
                        if ($request->pstatus == 34) {
                            $pp = 34;
                        } else {
                            $pp = 10;
                        }
                    } else {
                        if (isset($request->approvaldeliver) && $request->approvaldeliver == 1) {
                            $pp = 12;
                        } else {
                            $pp = 31;
                        }
                    }
                    $autoorderreport = report::where('orderId', $request->order_id1)->where('pstatus', '=', $pp)->first();
                    if (!isset($autoorderreport->id)) {
                        $autoorderreport = new report();
                        $autoorderreport->orderId = $request->order_id1;
                        $autoorderreport->pstatus = $pp;
                        $autoorderreport->created_at = date('Y-m-d h:i:s');
                    }
                    $autoorderreport->userId = Auth::user()->id;
                    $autoorderreport->updated_at = date('Y-m-d h:i:s');
                    $autoorderreport->save();
                } else {
                    $autoorderreport = report::where('orderId', $request->order_id1)->where('pstatus', '=', $ppp)->first();
                    if (!isset($autoorderreport->id)) {
                        $autoorderreport = new report();
                        $autoorderreport->orderId = $request->order_id1;
                        $autoorderreport->pstatus = $ppp;
                        $autoorderreport->created_at = date('Y-m-d h:i:s');
                    }
                    $autoorderreport->userId = Auth::user()->id;
                    $autoorderreport->updated_at = date('Y-m-d h:i:s');
                    $autoorderreport->save();
                }

                $singlerreportadd = singlereport::where('orderId', '=', $request->order_id1)->first();
                if ($singlerreportadd == '') {
                    $singlerreportadd = new singlereport();
                    $singlerreportadd->userId = Auth::user()->id;
                    $singlerreportadd->orderId = $request->order_id1;
                    $singlerreportadd->pstatus = $ppp;
                    $singlerreportadd->save();
                } else {
                    $singlerreportadd->pstatus = $ppp ?? 0;
                    $singlerreportadd->userId = Auth::user()->id;
                    $singlerreportadd->save();
                }

                $SheetDetails = new SheetDetails;
                $SheetDetails->auth_id = Auth::user()->id;
                $SheetDetails->orderId = $request->order_id1;
                $SheetDetails->remarks = $request->auc_remarks ?? null;
                $SheetDetails->comments = $request->auc_comments ?? null;
                $SheetDetails->satisfied = $request->auc_satisfied ?? null;
                $SheetDetails->review = $request->auc_review ?? null;
                $SheetDetails->website = $request->auc_website ?? null;
                $SheetDetails->website_other = $request->auc_website_other ?? null;
                $SheetDetails->client_rating = $request->auc_client_rating ?? null;
                $SheetDetails->website_link = $request->auc_website_link ?? null;
                $SheetDetails->reviewer = $request->auc_reviewer ?? null;
                if ($request->hasFile('screenshot')) {
                    $filename = uniqid() . '.' . $request->screenshot->getClientOriginalExtension();

                    $request->screenshot->move(public_path('review/screenshot'), $filename);

                    $SheetDetails->screenshot = 'https://washington.shawntransport.com/review/screenshot/' . $filename;
                }
                $SheetDetails->additional = $request->auc_additional;
                $SheetDetails->save();

                $this->expected_date($request->order_id1, Auth::user()->id, $request->pstatus ?? '0', $expected_date);
                Session::flash('flash_message', 'Data Successfully Saved');
                if (url()->previous() == url('') . '/add_new' || url()->previous() == url('') . '/add_new_heavy') {
                    return redirect('/new');
                }
                return redirect()->back();
            } else {
                Session::flash('flash_message', 'Something went wrong');
                return redirect()->back();
            }
        } else {
            return redirect('/loginn/');
        }
    }

    function call_history_post_relist(Request $request)
    {
        if (Auth::check()) {
            $last_status = "";
            $pstatus_old = 0;
            $autoorder = AutoOrder::find($request->order_id1);
            if (isset($autoorder->id)) {
                if (empty($autoorder->completer_id)) {
                    if ($request->pstatus == 13) {
                        $autoorder->completer_id = Auth::user()->id;
                    }
                }
                // last status for call hisorty
                $pstatus_old = $autoorder->pstatus;
                $last_status = $this->get_pstatuss($pstatus_old);


                if ($request->pstatus == 34) {
                    $autoorder->pstatus = 10;
                    $autoorder->schedule_approve = 1;
                } else {
                    if ($request->pstatus == 10) {
                        $autoorder->schedule_approve = 0;
                    }
                    $autoorder->pstatus = $request->pstatus;
                }
                $expected_date = '';
                if (isset($request->expected_date)) {
                    $expected_date = $request->expected_date;
                }
                if (isset($request->pickup_date)) {
                    $expected_date = $request->pickup_date;
                }
                if (isset($request->deliver_date)) {
                    $expected_date = $request->deliver_date;
                }
                if ($request->pstatus == 10) {
                    if (isset($request->already_late1)) {
                        if (empty($autoorder->already_storage)) {
                            $autoorder->already_auction_storage = 1;
                            $autoorder->already_storage = $request->already_storage;
                            $autoorder->already_storage_date = $expected_date ?? date('Y-m-d');
                        }
                    }
                    if (isset($request->already_late2)) {
                        if (empty($autoorder->late_pickup_storage)) {
                            $autoorder->late_pickup_auction_storage = 1;
                            $autoorder->late_pickup_storage = $request->late_pickup_storage;
                            $autoorder->late_pickup_storage_date = $expected_date ?? date('Y-m-d');
                        }
                    }

                    $dis_id = '';
                    if (!empty($autoorder->dispatcher_id)) {
                        $dis_id = $autoorder->dispatcher_id;
                    } else {
                        if (Auth::user()->userRole->name == 'Dispatcher') {
                            $autoorder->dispatcher_id = Auth::user()->id;
                            $autoorder->save();
                            $dis_id = Auth::user()->id;
                        }
                    }

                    $user = DailyQoute::whereDate('date', date('Y-m-d'))->where('user_id', $dis_id)->first();
                    if (isset($user->id)) {
                        $user->total_qoute = $user->total_qoute + 1;
                        $user->save();
                    }
                }
                if ($request->pstatus == 12) {
                    if (empty($autoorder->storage_move_date)) {
                        $autoorder->storage_move_date = date('Y-m-d');
                    }
                }
                if (isset($request->relist)) {
                    $autoorder->listed_price = $request->listed_price;
                    $autoorder->relist_id = $autoorder->relist_id + 1;
                    $carrier = carrier::where('orderId', '=', $autoorder->id)->get();
                    foreach ($carrier as $key => $val) {
                        $val->who_pickup = 0;
                        $val->save();
                    }
                } else {
                    $autoorder->carrier_id = $request->current_carrier;
                    $company_data = carrier::find($request->current_carrier);
                    if (isset($company_data->companyname)) {
                        $carrier = carrier::where('orderId', '=', $autoorder->id)->get();
                        foreach ($carrier as $key => $val) {
                            $val->who_pickup = 0;
                            $val->save();
                        }
                        $company_name = $company_data->companyname;
                        if ($request->pstatus == 11) {
                            $company_data->who_pickup = 1;
                            $company_data->carrier_cancel = 0;
                            $company_data->save();
                        }
                    }
                    // $autoorder->driver_pickup_date = $request->expected_date;

                }
                $autoorder->save();

                if (isset($request->pstatus) || $request->pstatus >= 0) {
                    if ($request->pstatus == 34) {
                        $ppp = 10;
                    } else {
                        $ppp = $request->pstatus;
                    }
                } else {
                    $ppp = $pstatus_old;
                }
                $this->expected_date($request->order_id1, Auth::user()->id, $ppp, $expected_date);
                $carrier_deleted = '';
                if (isset($request->carrier_deleted)) {
                    $carrier = carrier::where('orderId', $autoorder->id)->orderBy('created_at', 'DESC')->first();
                    $comp = '';
                    if (isset($carrier->companyname)) {
                        $comp = $carrier->companyname;
                    }
                    if ($request->carrier_deleted == 'OTHER') {
                        $carrier_deleted = '<h6>OTHER REASON: ' . $request->other_reason . '</h6>';
                    } else {
                        $carrier_deleted = '<h6>' . $comp ? $comp . ': ' : '' . $request->carrier_deleted . '</h6>';
                    }
                }

                $callhistory = new call_history();
                $callhistory->userId = Auth::user()->id;
                $callhistory->orderId = $request->order_id1;
                $callhistory->pstatus = $ppp;
                $callhistory->history = "<h6>LAST STATUS : " . $last_status . "</h6>" . $carrier_deleted . "<h6>Remarks: " . $request->history_update . "</h6>";
                if ($ppp == 14) {
                    if (isset($request->agree_disagree)) {
                        $callhistory->agree_disagree = $request->agree_disagree;
                    }
                    if (isset($request->mistaker)) {
                        $callhistory->mistaker = $request->mistaker;
                    }
                    if (isset($request->mistaker_id)) {
                        $callhistory->mistaker_id = $request->mistaker_id;
                    }
                    if (isset($request->no_of_calls)) {
                        $callhistory->no_of_calls = $request->no_of_calls;
                    }
                    if (isset($request->decision)) {
                        $callhistory->decision = $request->decision;
                    }
                }
                $callhistory->created_at = now();
                $callhistory->updated_at = now();
                $callhistory->save();

                if ($autoorder->pstatus == 11 || $autoorder->pstatus == 12 || $autoorder->pstatus == 10) {
                    if ($autoorder->pstatus == 11) {
                        if (isset($request->approvalpickup) && $request->approvalpickup == 1) {
                            $pp = 11;
                        } else {
                            $pp = 30;
                        }
                    } else if ($autoorder->pstatus == 10) {
                        if ($request->pstatus == 34) {
                            $pp = 34;
                        } else {
                            $pp = 10;
                        }
                    } else {
                        if (isset($request->approvaldeliver) && $request->approvaldeliver == 1) {
                            $pp = 12;
                        } else {
                            $pp = 31;
                        }
                    }
                    $autoorderreport = report::where('orderId', $request->order_id1)->where('pstatus', '=', $pp)->first();
                    if (!isset($autoorderreport->id)) {
                        $autoorderreport = new report();
                        $autoorderreport->orderId = $request->order_id1;
                        $autoorderreport->pstatus = $pp;
                        $autoorderreport->created_at = date('Y-m-d h:i:s');
                    }
                    $autoorderreport->userId = Auth::user()->id;
                    $autoorderreport->updated_at = date('Y-m-d h:i:s');
                    $autoorderreport->save();
                } else {
                    $autoorderreport = report::where('orderId', $request->order_id1)->where('pstatus', '=', $ppp)->first();
                    if (!isset($autoorderreport->id)) {
                        $autoorderreport = new report();
                        $autoorderreport->orderId = $request->order_id1;
                        $autoorderreport->pstatus = $ppp;
                        $autoorderreport->created_at = date('Y-m-d h:i:s');
                    }
                    $autoorderreport->userId = Auth::user()->id;
                    $autoorderreport->updated_at = date('Y-m-d h:i:s');
                    $autoorderreport->save();
                }

                $singlerreportadd = singlereport::where('orderId', '=', $request->order_id1)->first();
                if ($singlerreportadd == '') {
                    $singlerreportadd = new singlereport();
                    $singlerreportadd->userId = Auth::user()->id;
                    $singlerreportadd->orderId = $request->order_id1;
                    $singlerreportadd->pstatus = $autoorder->pstatus;
                    $singlerreportadd->save();
                } else {
                    $singlerreportadd->pstatus = $autoorder->pstatus;
                    $singlerreportadd->userId = Auth::user()->id;
                    $singlerreportadd->save();
                }

                $checkReport3 = report::where('pstatus', 10)->where('orderId', $autoorder->id)->first();
                if (empty($checkReport3)) {
                    if ($request->pstatus == 10) {
                        if (empty($autoorder->delivery_boy_id)) {

                            $user = DailyQoute::with('user.userRole')->where('total_qoute', '>', 0)->where('date', date('Y-m-d'))
                                ->whereHas('user', function ($q) {
                                    $q->where('deleted', 0)->where('is_login', 1);
                                })->whereHas('user.userRole', function ($q) {
                                    $q->where('name', 'Delivery Boy');
                                })->orderBy('total_qoute', 'DESC')->first();

                            if (empty($user)) {
                                $user = DailyQoute::with('user.userRole')->where('date', date('Y-m-d'))
                                    ->whereHas('user', function ($q) {
                                        $q->where('deleted', 0)->where('is_login', 1);
                                    })->whereHas('user.userRole', function ($q) {
                                        $q->where('name', 'Delivery Boy');
                                    })->orderBy('total_qoute', 'DESC')->first();
                            }

                            if (isset($user->user->id)) {
                                if ($user->user->userRole->name == 'Delivery Boy') {
                                    $daily = DailyQoute::where('user_id', $user->user->id)->whereDate('date', date('Y-m-d'))->first();
                                    if (!isset($daily->id)) {
                                        $total = $user->user->assign_daily_qoute;
                                        $daily = new DailyQoute;
                                        $daily->user_id = $user->user->id;
                                        $daily->date = date('Y-m-d');
                                        $daily->total_qoute = $total - 1;
                                        $daily->save();
                                    } else {
                                        if (isset($user->id)) {
                                            if ($user->total_qoute > 0) {
                                                $daily->total_qoute = $daily->total_qoute - 1;
                                                $daily->save();
                                            }
                                        }
                                    }
                                }
                                $autoorder->delivery_boy_id = $user->user->id;
                                $autoorder->save();
                            } else {
                                $user = User::with('userRole')
                                    ->where('deleted', 0)
                                    ->whereHas('userRole', function ($q) {
                                        $q->where('name', 'Delivery Boy');
                                    })
                                    ->inRandomOrder()->first();

                                $autoorder->delivery_boy_id = $user->id;
                                $autoorder->save();
                            }
                        }
                    }
                }

                if ($request->pstatus >= 9) {
                    if (empty($autoorder->dispatcher_id)) {
                        if (isset($request->dis_id) && !empty($request->dis_id)) {
                            $autoorder->dispatcher_id = $request->dis_id;
                            $user = DailyQoute::with('user.userRole')->where('total_qoute', '>', 0)->where('date', date('Y-m-d'))
                                ->where('user_id', $request->dis_id)->first();

                            if (isset($user->id)) {
                                $daily = DailyQoute::where('user_id', $user->user->id)->whereDate('date', date('Y-m-d'))->first();
                                if (!isset($daily->id)) {
                                    $total = $user->user->assign_daily_qoute;
                                    $daily = new DailyQoute;
                                    $daily->user_id = $user->user->id;
                                    $daily->date = date('Y-m-d');
                                    $daily->total_qoute = $total - 1;
                                    $daily->save();
                                } else {
                                    if ($user->total_qoute > 0) {
                                        $daily->total_qoute = $daily->total_qoute - 1;
                                        $daily->save();
                                    }
                                }
                            }
                        } else {
                            $checkReport2 = report::where('pstatus', $request->pstatus)->where('orderId', $autoorder->id)->first();
                            if (empty($checkReport2)) {
                                $user = DailyQoute::with('user.userRole')->where('total_qoute', '>', 0)->where('date', date('Y-m-d'))
                                    ->whereHas('user', function ($q) {
                                        $q->where('deleted', 0)->where('is_login', 1)->where('auto_assign', 1);
                                    })->whereHas('user.userRole', function ($q) {
                                        $q->where('name', 'Dispatcher');
                                    })->orderBy('total_qoute', 'DESC')->first();

                                // if(empty($user))
                                // {
                                //     $user = DailyQoute::with('user.userRole')->where('date',date('Y-m-d'))
                                //     ->whereHas('user',function ($q){
                                //         $q->where('deleted',0)->where('is_login',1)->where('auto_assign',1);
                                //     })->whereHas('user.userRole',function ($q){
                                //         $q->where('name','Dispatcher');
                                //     })->inRandomOrder()->first();
                                // }

                                if (isset($user->user->id)) {
                                    if ($user->user->userRole->name == 'Dispatcher') {
                                        $daily = DailyQoute::where('user_id', $user->user->id)->whereDate('date', date('Y-m-d'))->first();
                                        if (!isset($daily->id)) {
                                            $total = $user->user->assign_daily_qoute;
                                            $daily = new DailyQoute;
                                            $daily->user_id = $user->user->id;
                                            $daily->date = date('Y-m-d');
                                            $daily->total_qoute = $total - 1;
                                            $daily->save();
                                        } else {
                                            if (isset($user->id)) {
                                                if ($user->total_qoute > 0) {
                                                    $daily->total_qoute = $daily->total_qoute - 1;
                                                    $daily->save();
                                                }
                                            }
                                        }
                                    }
                                    $autoorder->dispatcher_id = $user->user->id;
                                }
                                // else
                                // {
                                //     $user = User::with('userRole')
                                //     ->where('deleted',0)->where('auto_assign',1)
                                //     ->whereHas('userRole',function ($q){
                                //         $q->where('name','Dispatcher');
                                //     })
                                //     ->inRandomOrder()->first();

                                //     $autoorder->dispatcher_id = $user->id;
                                // }
                            }
                        }
                    }
                }
                $autoorder->updated_at = now();
                $autoorder->save();
                if (isset($request->pstatus222)) {
                    $model = new SheetDetails;
                    $model->auth_id = Auth::user()->id;
                    $model->orderId = $request->order_id1;
                    if ($request->pstatus222 == 9) {
                        $model->pstatus = 9;
                        $model->paid = $request->auc_paid;
                        $model->storage = $request->auc_storage;
                        $model->listed_price = $request->auc_listed_price;
                        $model->auction_update = $request->auc_auction_update;
                        $model->title_keys = $request->auc_title_keys;
                        $model->keys = $request->auc_keys;
                        $model->vehicle_position = $request->auc_port;
                        $model->listed_count = $request->auc_listed_count;
                        $model->price = $request->auc_price;
                        $model->additional = $request->auc_additional;
                        $model->save();
                    }
                    if ($request->pstatus222 == 10) {
                        $model->pstatus = 10;
                        $model->company_name = $company_name;
                        $model->pickup_date = $request->auc_pickedup;
                        $model->vehicle_condition = $request->auc_condition;
                        $model->title_keys = $request->auc_title_keys;
                        $model->keys = $request->auc_keys;
                        $model->storage = $request->auc_storage;
                        $model->auction_update = $request->auc_auction_update;
                        $model->who_pay_storage = $request->auc_who_pay_storage;
                        $model->vehicle_position = $request->auc_vehicle_position;
                        $model->delivery_date = $request->auc_delivery_date;
                        $model->driver_fmcsa = $request->auc_driver_fmcsa;
                        $model->carrier_rating = $request->auc_carrier_rating;
                        $model->fmcsa = $request->auc_fmcsa;
                        $model->coi_holder = $request->auc_coi_holder;
                        $model->vehicle_luxury = $request->auc_vehicle_luxury;
                        $model->aware_driver_delivery_date = $request->auc_aware_driver_delivery_date;
                        $model->price = $request->auc_price;
                        $model->insurance_date = $request->auc_insurance_date;
                        $model->new_old_driver = $request->auc_new_old_driver;
                        $model->payment_method = $request->auc_payment_method;
                        $model->is_local = $request->auc_is_local;
                        $model->job_accept = $request->auc_job_accept;
                        $model->additional = $request->auc_additional;
                        $model->save();
                    }
                    if ($request->pstatus222 == 11) {
                        $model->pstatus = 11;
                        $model->driver_status = $request->auc_driver_status;
                        $model->storage = $request->auc_storage;
                        $model->delivery_date = $request->auc_delivery_date;
                        $model->vehicle_condition = $request->auc_condition;
                        $model->title_keys = $request->auc_title_keys;
                        $model->keys = $request->auc_keys;
                        $model->vehicle_position = $request->auc_vehicle_position;
                        $model->payment = $request->auc_payment;
                        $model->payment_charged_or_owes = $request->auc_payment_charged_or_owes;
                        $model->payment_method = $request->auc_payment_method;
                        $model->carrier_name = $request->auc_carrier_name;
                        $model->driver_payment = $request->auc_driver_payment;
                        $model->price = $request->auc_price;
                        $model->stamp_dock_receipt = $request->auc_stamp_dock_receipt;
                        $model->auc_company_name = $request->auc_company_name;
                        $model->driver_no = $request->auc_driver_no;
                        $model->driver_no2 = $request->auc_driver_no2;
                        $model->driver_no3 = $request->auc_driver_no3;
                        $model->driver_no4 = $request->auc_driver_no4;
                        $model->additional = $request->auc_additional;
                        $model->save();

                        $model2 = new SheetDetails;
                        $model2->auth_id = Auth::user()->id;
                        $model2->orderId = $request->order_id1;
                        $model2->pstatus = 11;
                        $model2->auction_status = $request->auc_status1;
                        $model2->storage = $request->auc_storage1;
                        $model2->vehicle_condition = $request->auc_condition1;
                        $model2->title_keys = $request->auc_title_keys1;
                        $model2->keys = $request->auc_keys1;
                        $model2->vehicle_position = $request->auc_vehicle_position1;
                        $model2->additional = $request->auc_additional1;
                        $model2->save();
                    }
                    if ($request->pstatus222 == 12) {
                        $model->pstatus = 12;
                        $model->driver_no = $request->auc_driver_no;
                        $model->driver_no2 = $request->auc_driver_no2;
                        $model->driver_no3 = $request->auc_driver_no3;
                        $model->driver_no4 = $request->auc_driver_no4;
                        $model->driver_status = $request->auc_driver_status;
                        $model->driver_payment_status = $request->auc_driver_payment_status;
                        $model->vehicle_condition = $request->auc_condition;
                        $model->customer_informed = $request->auc_customer_informed;
                        $model->delivery_date = $request->auc_delivery_date;
                        $model->vehicle_position = $request->auc_vehicle_position;
                        $model->who_pay_storage = $request->auc_who_pay_storage;
                        $model->title_keys = $request->auc_title_keys;
                        $model->keys = $request->auc_keys;
                        $model->client_status = $request->auc_client_status;
                        $model->owes_status = $request->auc_owes_status;
                        $model->additional = $request->auc_additional;
                        $model->save();
                    }
                    if ($request->pstatus222 == 13) {
                        $model->pstatus = 13;
                        $model->remarks = $request->auc_remarks;
                        $model->comments = $request->auc_comments;
                        $model->satisfied = $request->auc_satisfied;
                        $model->review = $request->auc_review;
                        $model->website = $request->auc_website;
                        $model->website_other = $request->auc_website_other;
                        $model->client_rating = $request->auc_client_rating;
                        $model->website_link = $request->auc_website_link;
                        $model->additional = $request->auc_additional;
                        $model->save();
                    }
                }
                Session::flash('flash_message', 'Data Successfully Saved');
                return redirect()->back();
            } else {
                Session::flash('flash_message', 'Something went wrong');
                return redirect()->back();
            }
        } else {
            return redirect('/loginn/');
        }
    }

    function pickup_approve($id)
    {
        $autoorder = AutoOrder::find($id);
        $autoorder->approve_pickup = 1;
        if ($autoorder->already_auction_storage == 1) {
            $autoorder->already_storage_end_date = date('Y-m-d');
        }
        if ($autoorder->late_pickup_auction_storage == 1) {
            $autoorder->late_pickup_storage_end_date = date('Y-m-d');
        }
        $autoorder->save();

        $call_histories = new call_history();
        $call_histories->userId = Auth::user()->id;
        $call_histories->orderId = $id;
        $call_histories->pstatus = 11;
        $call_histories->history = '<h6>LAST STATUS :Pickup Approval</h6><h6>Remarks: Pickup has been Approved!</h6>';
        $call_histories->save();

        $autoorderreport = new report();
        $autoorderreport->orderId = $autoorder->id;
        $autoorderreport->pstatus = 11;
        $autoorderreport->userId = Auth::user()->id;
        $autoorderreport->save();

        // Mail::to([$autoorder->oemail, 'info@hellotransport.com'])->send(new PickupConfirmationMail($autoorder));
        if (filter_var($autoorder->oemail, FILTER_VALIDATE_EMAIL)) {
            Mail::to([$autoorder->oemail, 'info@hellotransport.com'])->send(new PickupConfirmationMail($autoorder));
        } else {
            // Optionally, you can log an error or take another action if the email is invalid
            \Log::warning('Invalid email format for autoorder: ' . $autoorder->oemail);
        }

        return redirect()->back();
    }

    public function schedule_delivery(Request $request)
    {
        $autoorder = AutoOrder::find($request->id);
        $autoorder->approve_deliver = 2;
        $autoorder->save();

        $call_histories = new call_history();
        $call_histories->userId = Auth::user()->id;
        $call_histories->orderId = $autoorder->id;
        $call_histories->pstatus = 12;
        $call_histories->history = '<h6>LAST STATUS :Deliver Approval</h6><h6>Remarks: ' . $request->additional . '</h6>';
        $call_histories->save();

        $autoorderreport = new report();
        $autoorderreport->orderId = $autoorder->id;
        $autoorderreport->pstatus = 32;
        $autoorderreport->userId = Auth::user()->id;
        $autoorderreport->save();

        return redirect()->back();
    }

    function deliver_approve($id)
    {
        $autoorder = AutoOrder::find($id);
        $autoorder->approve_deliver = 1;
        $autoorder->save();

        $call_histories = new call_history();
        $call_histories->userId = Auth::user()->id;
        $call_histories->orderId = $id;
        $call_histories->pstatus = 12;
        $call_histories->history = '<h6>LAST STATUS :Schedule For Delivery</h6><h6>Remarks: Delivery has been Approved!</h6>';
        $call_histories->save();

        $autoorderreport = new report();
        $autoorderreport->orderId = $autoorder->id;
        $autoorderreport->pstatus = 12;
        $autoorderreport->userId = Auth::user()->id;
        $autoorderreport->save();

        // Mail::to([$autoorder->oemail, 'info@hellotransport.com'])->send(new DeliveredConfirmationMail($autoorder));
        if (filter_var($autoorder->oemail, FILTER_VALIDATE_EMAIL)) {
            Mail::to([$autoorder->oemail, 'info@hellotransport.com'])->send(new DeliveredConfirmationMail($autoorder));
        } else {
            // Optionally log an error if the email format is invalid
            \Log::warning('Invalid email format for autoorder: ' . $autoorder->oemail);
        }

        return redirect()->back();
    }

    function show_call_history(Request $request)
    {
        if (isset($request->id)) {

            $order = AutoOrder::find($request->id);
            $data = call_history::where('orderId', '=', $request->id)
                ->orderBy('created_at', 'DESC')->get();

            return view('main.phone_quote.callhistory.index', compact('data', 'order'));
        }
    }

    function show_pop_up(Request $request)
    {
        if (isset($request->id)) {

            $data = AutoOrder::where('id', '=', $request->id)
                ->orderBy('created_at', 'ASC')->get();
            return view('main.phone_quote.callhistory.pop_up', compact('data'));
        }
    }


    public function day_count()
    {
        if (Auth::check()) {

            $data = count_day::orderBy('expected_date', 'DESC')
                ->paginate(10);
            return view('main.phone_quote.day_count.index', compact('data'));
        } else {
            return redirect('/loginn/');
        }
    }

    public function quote_listing()
    {
        if (Auth::check()) {

            return view('main.phone_quote.quote.quote_list');
        } else {
            return redirect('/loginn/');
        }
    }


    public function fetch_day22(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $setting = general_setting::first();

            $req = 0;
            if (isset($request->pstatus)) {
                $req = $request->pstatus;
                $pstatus = $req;
                $data = count_day::where('pstatus', '=', $req)
                    ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
                    ->whereHas('order', function ($q) use ($user) {
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
                    ->orderBy('expected_date', 'DESC')->get();
                return view('main.phone_quote.quote.load', compact('data', 'pstatus'));
            }
        } else {
            return redirect('/loginn/');
        }
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

    public function shipment_status_load(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $setting = general_setting::first();
            $ptype = $this->check_user_setting(Auth::user()->id);

            $req = 0;
            if (isset($request->pstatus)) {
                $from = '';
                $to = '';
                if (isset($request->date_range)) {
                    $date = explode(' - ', $request->date_range);
                    $from = date('Y-m-d 00:00:00', strtotime($date[0]));
                    $to = date('Y-m-d 23:59:59', strtotime($date[1]));
                }
                $req = $request->pstatus;
                $pstatus = $req;
                if ($request->pstatus >= 20 && $request->pstatus <= 27) {
                    $data = RequestShipment::whereHas('order', function ($q) use ($user, $setting, $ptype, $req) {
                        if ($user->userRole->name == 'Manager') {
                            if ($user->order_taker_quote == 1) {
                                $q->where('manager_id', $user->id)->orWhere('order_taker_id', $user->id);
                            }
                        } else if ($user->userRole->name == 'Dispatcher') {
                            if ($user->shipment_status_quote_assign == 1) {
                                $q->where('dispatcher_id', $user->id);
                            } else {
                                if ($user->order_taker_quote == 1) {
                                    $q->where('dispatcher_id', $user->id);
                                }
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
                        $q->where('paneltype', '=', $ptype)->where(function ($q) {
                            $q->where('pstatus', 9)->orWhere('pstatus', 10);
                        })->where('created_at', '>=', Carbon::today()->subDays($setting->no_days));
                    })
                        ->where(function ($q) use ($from, $to) {
                            if (!empty($from) && !empty($to)) {
                                $q->whereBetween('created_at', [$from, $to]);
                            }
                        })
                        ->where('status', $req)
                        ->orderBy('created_at', 'DESC');

                    $count = count($data->get()->unique('order_id'));
                    $data = $data->get()->unique('order_id');
                    return view('main.phone_quote.quote.load3', compact('data', 'pstatus', 'count'));
                } else if ($request->pstatus == 28) {
                    $data = AutoOrder::with('storage')
                        ->where('storage_id', '>', 0)
                        ->where('paneltype', $ptype)
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
                        ->where(function ($q) use ($from, $to) {
                            if (!empty($from) && !empty($to)) {
                                if ($from == $to) {
                                    $q->whereDate('storage_date', $to);
                                } else {
                                    $q->whereBetween('storage_date', [$from, $to]);
                                }
                            }
                        })->where('pstatus', 11)->orderBy('created_at', 'DESC');

                    $count = $data->count();
                    $data = $data->get();
                    return view('main.phone_quote.quote.load4', compact('data', 'pstatus', 'count'));
                } else if ($request->pstatus == 29) {
                    $data = count_day::whereHas('order', function ($q) use ($user, $setting, $ptype, $req) {
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
                        $q->where(function ($q2) {
                            $q2->where('booking_confirm', 'may be')->orWhere('booking_confirm', 'confirm');
                        })->where('paneltype', '=', $ptype)->where('created_at', '>=', Carbon::today()->subDays($setting->no_days));
                    })
                        ->with('order.sheet')
                        ->where(function ($q) use ($from, $to) {
                            if (!empty($from) && !empty($to)) {
                                $q->whereBetween('expected_date', [$from, $to]);
                            }
                        })
                        ->orderBy('expected_date', 'ASC');

                    $count = $data->count();
                    $data = $data->get();
                    return view('main.phone_quote.quote.load5', compact('data', 'pstatus', 'count'));
                } else {
                    $data = count_day::whereHas('order', function ($q) use ($user, $setting, $ptype, $req) {
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
                        if ($req == 33) {
                            $q->whereIn('pstatus', [7, 8, 9, 10, 18])
                                ->where(function ($q2) {
                                    $q2->whereIn('oterminal', [2, 3, 4])->orWhereIn('dterminal', [2, 3, 4]);
                                });
                        } else if ($req == 11 || $req == 30) {
                            $q->where('pstatus', 11);
                            if ($req == 30) {
                                $q->where(function ($q2) use ($req) {
                                    $q2->where('approve_pickup', '=', 0)
                                        ->orWhere('approve_pickup', '=', NULL);
                                });
                            } else {
                                $q->where('approve_pickup', '=', 1);
                            }
                        } else if ($req == 12 || $req == 31 || $req == 32) {
                            $q->where('pstatus', 12);
                            if ($req == 31) {
                                $q->where(function ($q2) use ($req) {
                                    $q2->where('approve_deliver', '=', 0)
                                        ->orWhere('approve_deliver', '=', NULL);
                                });
                            } else if ($req == 32) {
                                $q->where('approve_deliver', '=', 2);
                            } else {
                                $q->where('approve_deliver', '=', 1);
                            }
                        } else if ($req == 10 || $req == 34) {
                            $q->where('pstatus', 10);
                            if ($req == 10) {
                                $q->where(function ($q2) use ($req) {
                                    $q2->where('schedule_approve', '=', 0)
                                        ->orWhere('schedule_approve', '=', NULL);
                                });
                            } else {
                                $q->where('schedule_approve', '=', 1);
                            }
                        } else {
                            $q->where('pstatus', '=', $req);
                        }
                        $q->where('paneltype', '=', $ptype)->where('created_at', '>=', Carbon::today()->subDays($setting->no_days));
                    })
                        ->with('order.sheet')
                        ->where(function ($q) use ($from, $to) {
                            if (!empty($from) && !empty($to)) {
                                $q->whereBetween('expected_date', [$from, $to]);
                            }
                        })
                        ->orderBy('expected_date', 'DESC')->get()->unique('order_id')->reverse();

                    $count = $data->count();
                    return view('main.phone_quote.quote.load2', compact('data', 'pstatus', 'count'));
                }
            }
        } else {
            return redirect('/loginn/');
        }
    }


    public function fetch_day(Request $request)
    {
        if (Auth::check()) {

            $req = 0;
            if (isset($request->pstatus)) {
                $req = $request->pstatus;
            }
            if ($request->ajax()) {
                $data = count_day::where('pstatus', '=', $req)
                    ->orderBy('expected_date', 'DESC')
                    ->paginate(50);
                return view('main.phone_quote.day_count.load', compact('data'))->render();
            }
        } else {
            return redirect('/loginn/');
        }
    }

    public function fetch_day2(Request $request)
    {
        if (Auth::check()) {
            // echo "<pre>";print_r($request->all());exit();
            // $req = 0;
            // if (isset($request->pstatus)) {
            //     $req = $request->pstatus;
            // }
            // if ($request->ajax()) {
            //     $data = count_day::where('pstatus', '=', $req)
            //         ->orderBy('expected_date', 'DESC')
            //         ->paginate(50);
            //     return view('main.phone_quote.day_count.load', compact('data'));
            // }
            $status = $request->status;
            $searchAny = $request->searchAny;
            $entity = 10;
            if ($request->entity) {
                $entity = $request->entity;
            }
            $from = '';
            $to = '';
            if (isset($request->date_range)) {
                $date2 = (string) $request->date_range;
                $date = explode(' - ', $request->date_range);
                $from = date('Y-m-d 00:00:00', strtotime($date[0]));
                $to = date('Y-m-d 23:59:59', strtotime($date[1]));
            }

            $data = count_day::where(function ($q) use ($searchAny, $from, $to, $status) {
                if (!empty($status)) {
                    $q->where('pstatus', $status);
                }
                if ($searchAny) {
                    $q->whereHas('order', function ($q2) use ($searchAny) {
                        $q2->where('oname', 'LIKE', '%' . $searchAny . '%')
                            ->orWhere('oemail', 'LIKE', '%' . $searchAny . '%')
                            ->orWhere('id', 'LIKE', '%' . $searchAny . '%');
                    })
                        ->orWhereHas('user', function ($q3) use ($searchAny) {
                            $q3->where('name', 'LIKE', '%' . $searchAny . '%')
                                ->orWhere('last_name', 'LIKE', '%' . $searchAny . '%')
                                ->orWhere('slug', 'LIKE', '%' . $searchAny . '%');
                        })
                        ->orWhere('order_id', 'LIKE', '%' . $searchAny . '%');
                }
                if ($to && $from) {
                    if ($from == $to) {
                        $q->whereDate('expected_date', $from);
                    } else {
                        $q->whereBetween('expected_date', [$from, $to]);
                    }
                }
            })
                ->orderBy('expected_date', 'DESC')
                ->paginate($entity);
            return view('main.phone_quote.day_count.load', compact('data'));
        } else {
            return redirect('/loginn/');
        }
    }

    public function get_history_by_user_order(Request $request)
    {
        $orderhistory = call_history::where(function ($query) use ($request) {
            if (!empty($request->order_id)) {
                $query->where('orderId', $request->order_id);
            }
            if (!empty($request->from_date) && !empty($request->to_date)) {
                $query->whereBetween('created_at', array($request->from_date, $request->to_date));
            }
        })
            ->where('userId', $request->user_id)->where('pstatus', '!=', 0)->orderby('orderId', 'desc')->get();

        return response()->json($orderhistory);
    }

    public function click_to_count()
    {
        if (Auth::check()) {

            $data = count_click::orderBy('id', 'DESC')->paginate(10);

            $user = User::where('deleted', 0)->where('status', 1)->orderby('slug', 'asc')->get();

            return view('main.phone_quote.click_count.index', compact('data', 'user'));
        } else {
            return redirect('/loginn/');
        }
    }

    public function fetch_count(Request $request)
    {
        if (Auth::check()) {

            if ($request->ajax()) {
                // echo "<pre>";
                // print_r($request->all());
                // exit();
                // $data = count_click::
                // orderBy('id', 'DESC')
                //     ->paginate(50);

                $status = $request->status;
                $searchAny = $request->searchAny;
                $user_id = $request->user_id;
                $entity = 10;
                if ($request->entity) {
                    $entity = $request->entity;
                }
                $from = '';
                $to = '';
                if (isset($request->date_range)) {
                    $date2 = (string) $request->date_range;
                    $date = explode(' - ', $request->date_range);
                    $from = date('Y-m-d 00:00:00', strtotime($date[0]));
                    $to = date('Y-m-d 23:59:59', strtotime($date[1]));
                }

                $data = count_click::where(function ($q) use ($searchAny, $from, $to, $status, $user_id) {
                    if (!empty($status)) {
                        $q->where('pstatus', $status)
                            ->orWhereHas('order', function ($q5) use ($status) {
                                $q5->where('pstatus', $status);
                            });
                    }
                    if ($searchAny) {
                        $q->whereHas('user', function ($q3) use ($searchAny) {
                            $q3->where('name', 'LIKE', '%' . $searchAny . '%')
                                ->orWhere('last_name', 'LIKE', '%' . $searchAny . '%')
                                ->orWhere('slug', 'LIKE', '%' . $searchAny . '%');
                        })
                            ->orWhere(function ($q4) use ($searchAny) {
                                $q4->where('client_name', 'LIKE', '%' . $searchAny . '%')
                                    ->orWhere('client_email', 'LIKE', '%' . $searchAny . '%')
                                    ->orWhere('order_id', 'LIKE', '%' . $searchAny . '%');
                            });
                    }
                    if ($from && $to) {
                        if ($from == $to) {
                            $q->whereDate('created_at', $from);
                        } else {
                            $q->whereBetween('created_at', [$from, $to]);
                        }
                    }
                    if (!empty($user_id)) {
                        $q->where('user_id', $user_id);
                    }
                })
                    ->orderBy('id', 'DESC')
                    ->paginate($entity);

                return view('main.phone_quote.click_count.load', compact('data'))->render();
            }
        } else {
            return redirect('/loginn/');
        }
    }


    public function count_user(Request $request)
    {

        $count_save = count_click::where('order_id', '=', $request->order_id)
            ->where('user_id', '=', Auth::user()->id)
            ->first();
        if ($count_save) {

            if (empty($request->client_email)) {
                $request->client_email = "";
            }
            if (empty($request->client_name)) {
                $request->client_name = "";
            }

            $count_save->client_email = $request->client_email;
            $count_save->client_name = $request->client_name;
            $count_save->total_clicks = $count_save->total_clicks + 1;
            $count_save->save();
        } else {
            if (empty($request->client_email)) {
                $request->client_email = "";
            }
            if (empty($request->client_name)) {
                $request->client_name = "";
            }
            $count_save = new count_click();
            $count_save->order_id = $request->order_id;
            $count_save->pstatus = $request->pstatus;
            $count_save->client_email = $request->client_email;
            $count_save->client_name = $request->client_name;
            $count_save->user_id = Auth::user()->id;
            $count_save->total_clicks = 1;
            $count_save->save();
        }

        return "SUCCESS";
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

    public function get_pickup_date(Request $request)
    {
        $data = AutoOrder::find($request->order_id);

        return response()->json($data, 200);
    }

    public function save_order_history(Request $request)
    {

        $autoorder = AutoOrder::find($request->orderid);
        $last_status = $this->get_pstatuss($autoorder->pstatus);
        $autoorder->pstatus = $request->new_pstatus ? $request->new_pstatus : $autoorder->pstatus;
        $autoorder->save();
        $orderhistory = call_history::where('orderId', $request->orderid)->first();
        if (empty($orderhistory)) {
            $save_order_history = new call_history();
            $save_order_history->userId = Auth::user()->id;
            $save_order_history->pstatus = $request->new_pstatus ? $request->new_pstatus : $autoorder->pstatus;
            $save_order_history->orderId = $request->orderid;
            $save_order_history->history = "<h6>LAST STATUS :$last_status;</h6>" . "<h6>Remarks: New Order <h6>";
            $save_order_history->save();
        }

        return redirect('/new');
    }

    public function shipment_status()
    {
        return view('main.phone_quote.quote.shipmentstatus');
    }

    public function get_pstatus2($id)
    {
        $ret = "";
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
            $ret = "<span class='badge badge-danger get_car_or_heavy txt-white'>On Approval Canceled</span>";
        }
        return $ret;
    }

    public function pay_status($id)
    {
        if ($id == 0) {
            return '<span class="badge badge-warning">Pending</span>';
        } else if ($id == 1) {
            return '<span class="badge badge-info">Updated</span>';
        } else if ($id == 2) {
            return '<span class="badge badge-success">Received</span>';
        }
    }

    public function putX($digits, $status, $num)
    {
        $val = $num;
        if ($status == 0) {
            if ($digits == 0) {
                $val = $num;
            } else if ($digits == 1) {
                $val = '(x' . substr($num, -12);
            } else if ($digits == 2) {
                $val = '(xx' . substr($num, -11);
            } else if ($digits == 3) {
                $val = '(xxx) ' . substr($num, -8);
            } else if ($digits == 4) {
                $val = '(xxx) x' . substr($num, -7);
            } else if ($digits == 5) {
                $val = '(xxx) xx' . substr($num, -6);
            } else if ($digits == 6) {
                $val = '(xxx) xxx-' . substr($num, -4);
            } else if ($digits == 7) {
                $val = '(xxx) xxx-x' . substr($num, -3);
            } else if ($digits == 8) {
                $val = '(xxx) xxx-xx' . substr($num, -2);
            } else if ($digits == 9) {
                $val = '(xxx) xxx-xxx' . substr($num, -1);
            } else if ($digits == 10) {
                $val = '(xxx) xxx-xxxx';
            }
        } else if ($status == 1) {
            if ($digits == 0) {
                $val = $num;
            } else if ($digits == 1) {
                $val = substr($num, 0, 13) . 'x';
            } else if ($digits == 2) {
                $val = substr($num, 0, 12) . 'xx';
            } else if ($digits == 3) {
                $val = substr($num, 0, 11) . 'xxx ';
            } else if ($digits == 4) {
                $val = substr($num, 0, 10) . 'xxxx';
            } else if ($digits == 5) {
                $val = substr($num, 0, 8) . 'x-xxxx';
            } else if ($digits == 6) {
                $val = substr($num, 0, 7) . 'xx-xxxx';
            } else if ($digits == 7) {
                $val = substr($num, 0, 6) . 'xxx-xxxx';
            } else if ($digits == 8) {
                $val = substr($num, 0, 3) . 'x) xxx-xxxx';
            } else if ($digits == 9) {
                $val = substr($num, 0, 2) . 'xx) xxx-xxxx';
            } else if ($digits == 10) {
                $val = '(xxx) xxx-xxxx';
            }
        } else if ($status == 2) {
            if ($digits == 0) {
                $val = $num;
            } else if ($digits == 1) {
                $val = substr($num, 0, 7) . 'x' . substr($num, -6);
            } else if ($digits == 2) {
                $val = substr($num, 0, 7) . 'xx' . substr($num, -5);
            } else if ($digits == 3) {
                $val = substr($num, 0, 6) . 'xxx' . substr($num, -5);
            } else if ($digits == 4) {
                $val = substr($num, 0, 3) . 'x) xxx' . substr($num, -5);
            } else if ($digits == 5) {
                $val = substr($num, 0, 3) . 'x) xxx-x' . substr($num, -3);
            } else if ($digits == 6) {
                $val = substr($num, 0, 3) . 'x) xxx-xx' . substr($num, -2);
            } else if ($digits == 7) {
                $val = substr($num, 0, 2) . 'xx) xxx-xx' . substr($num, -2);
            } else if ($digits == 8) {
                $val = substr($num, 0, 2) . 'xx) xxx-xxx' . substr($num, -1);
            } else if ($digits == 9) {
                $val = '(xxx) xxx-xxx' . substr($num, -1);
            } else if ($digits == 10) {
                $val = '(xxx) xxx-xxxx';
            }
        }
        return $val;
    }

    public function check_panel()
    {
        $setting = general_setting::first();
        $ptype = 1;
        $query = user_setting::where('user_id', Auth::user()->id)->first();
        if (!empty($query)) {
            $ptype = $query['penal_type'];
        }
        return $ptype;
    }

    public function get_shipment_status_order_detail(Request $request)
    {
        $order = AutoOrder::with('latestHistory', 'onecarrier', 'sheet.user', 'req_ship', 'instruction', 'carrierApproachings', 'carrierApproachingsCount')
            ->select([
                'id',
                'originzsc',
                'originzip',
                'originstate',
                'origincity',
                'destinationzsc',
                'destinationzip',
                'destinationstate',
                'destinationcity',
                'paid_status',
                'ymk',
                'driver_pickup_date',
                'est_pick_date',
                'pickup_date',
                'driver_deliver_date',
                'est_delivery_date',
                'delivery_date',
                'pstatus',
                'payment_method',
                'oterminal',
                'oauction',
                'dterminal',
                'dauction',
                'storage_charge',
                'already_auction_storage',
                'late_pickup_auction_storage',
                'oname',
                'ophone',
                'ophone2'
            ])
            ->where('id', $request->id)
            ->first();

        return view('main.phone_quote.quote.showData', compact('order'));
    }

    public function get_shipment_status_order_detail2(Request $request)
    {
        $order = AutoOrder::with('latestHistory', 'sheet.user', 'instruction')
            ->select([
                'id',
                'originzsc',
                'originzip',
                'originstate',
                'origincity',
                'destinationzsc',
                'destinationzip',
                'destinationstate',
                'destinationcity',
                'paid_status',
                'ymk',
                'driver_pickup_date',
                'est_pick_date',
                'pickup_date',
                'driver_deliver_date',
                'est_delivery_date',
                'delivery_date',
                'pstatus',
                'payment_method',
                'oterminal',
                'oauction',
                'dterminal',
                'dauction',
                'storage_charge',
                'already_auction_storage',
                'late_pickup_auction_storage',
                'oname',
                'ophone',
                'ophone2',
                'company_name',
                'company_price',
                'company_comments'
            ])
            ->where('id', $request->id)
            ->first();

        return view('main.phone_quote.quote.showData2', compact('order'));
    }

    public function get_shipment_status_order_detail3(Request $request)
    {
        $data = RequestShipment::where('order_id', $request->id)
            ->where('status', $request->status)
            ->get();
        $countApproachings = "";
        $status = $request->status;
        $order_id = $request->id;
        if ($status == 26 || $status == 22) {
            $countApproachings = CarrierApproaching::where('order_id', $request->id)->count();
        }
        return view('main.phone_quote.quote.showData3', compact('data', 'status', 'order_id', 'countApproachings'));
    }

    public function show_last_two_history(Request $request)
    {
        $second_last = call_history::where('orderId', $request->id)->where('pstatus', 19)->orderBy('id', 'DESC')->first();
        $last = call_history::where('orderId', $request->id)->where('pstatus', 14)->orderBy('id', 'DESC')->first();

        return view('main.phone_quote.callhistory.cancel_history', compact('second_last', 'last'));
    }

    public function qa_show_history(Request $request)
    {
        $id = $request->id;
        $data = QaVerifyHistory::where('order_id', $id)->orderBy('id', 'DESC')->get();
        return view('main.phone_quote.callhistory.qa_show_history', compact('data', 'id'));
    }

    public function qa_update_history(Request $request)
    {
        $id = $request->id;
        $pstatus = $request->pstatus;
        $data = AutoOrder::where('id', $id)->select('id', 'order_taker_id', 'dispatcher_id')->first();
        return view('main.phone_quote.callhistory.qa_update_history', compact('id', 'pstatus', 'data'));
    }

    public function update_qa_remarks(Request $request)
    {
        $data = new QaVerifyHistory;
        $data->user_id = Auth::user()->id;
        $data->order_id = $request->order_id8;
        $data->pstatus = $request->pstatus8;
        $data->verify = $request->verify;
        $data->no_of_calls = $request->no_of_calls;
        $data->negative = isset($request->negative) ? $request->negative : 0;
        $data->negative_to_user_id = isset($request->negative) ? $request->negative_to_user_id : 0;
        $data->decision = $request->decision;
        $data->remarks = $request->remarks;
        $data->save();

        return back();
    }

    public function qa_admin_remarks(Request $request)
    {
        $data = QaVerifyHistory::find($request->id);
        $data->admin_agree = $request->admin_agree;
        $data->admin_remarks = $request->admin_remarks;
        $data->save();

        return back();
    }

    public function request_shipment(Request $request)
    {
        $order = AutoOrder::find($request->order_id_request);
        $data = new RequestShipment;
        $data->user_id = Auth::user()->id;
        $data->order_id = $request->order_id_request;
        $data->request_name = $request->request_name;
        $data->status = $request->status_request;
        if (isset($order->pstatus)) {
            $data->pstatus = $order->pstatus;
        } else {
            $data->pstatus = 9;
        }
        $data->additional = $request->additional_request;
        $k = [];
        $v = [];
        if (isset($request->key)) {
            foreach ($request->key as $key => $keys) {
                $k[] = $keys;
                $v[] = $request->value[$key] ?? 'N/A';
            }
        }
        if (count($k) > 0) {
            $data->key = implode("*^-", $k);
        }
        if (count($v) > 0) {
            $data->value = implode("*^-", $v);
        }
        $data->save();

        Session::flash('flash_message', 'Request has been sent!');
        return back();
    }

    public function request_shipment_reply(Request $request, $id)
    {
        $data = RequestShipment::find($id);
        $data->reply = $request->reply;
        $data->replyer_id = Auth::user()->id;
        $data->save();

        return back();
    }

    public function allowQuotesDD(Request $request)
    {
        $data = SiteSetting::find(1);

        if ($data) {
            $data->allow = $request->allow;
            $data->groupChatCheck = $request->groupChatCheck;
            $data->save();

            return response()->json(['message' => 'Status Changed'], 200);
        } else {
            return response()->json(['message' => 'Setting not found'], 404);
        }
    }
}

