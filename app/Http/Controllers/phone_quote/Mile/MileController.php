<?php

namespace App\Http\Controllers\phone_quote\Mile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MilePrice;
use Illuminate\Support\Facades\Validator;
use App\AutoOrder;
use App\User;
use App\Http\Controllers\phone_quote\Mile\Pagination;
use DB;
use App\user_setting;
use Auth;
use App\general_setting;
use Carbon\Carbon;
use App\OfferPrice;
use App\CommissionRange;
use App\FieldLabel;

class MileController extends Controller
{
    public function check_user_setting($user_id)
    {
        $p_type = 1;
        $usersetting = user_setting::where('user_id', '=', $user_id)->first();
        if (!empty($usersetting)) {
            $p_type = $usersetting['penal_type'];
        }
        return $p_type;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mile = MilePrice::orderBy('mile', 'ASC')->paginate(20);
        $label = FieldLabel::all();
        // echo "<pre>";
        // print_r($mile);
        // exit();
        return view('main.mile.index', compact('mile', 'label'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mile' => 'required',
            'milePrice' => 'required|numeric',
            'commissionPrice' => 'required|numeric',
        ]);
        if ($validator->passes()) {
            $mile = new MilePrice;
            $mile->mile = $request->mile;
            $mile->mile_price = $request->milePrice;
            $mile->commission = $request->commissionPrice;
            $mile->save();
            return back()->with('flash_message', 'New Price Per Mile Added Successfully!');
        } else {
            return back()->withInput()->withErrors($validator);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show(Request $request)
    // {
    //     // dd($request->toArray());
    //     $user = Auth::user();
    //     $setting = general_setting::first();
    //     $ptype = $this->check_user_setting($user->id);
    //     $id = $request->orderID ?? 0;
    //     $userName = $request->userName ?? '';
    //     $clientName = $request->clientName ?? '';
    //     $delivery = $request->delivery ?? '';
    //     $zip = $request->zip ?? '';
    //     $vehicle = $request->vehicle ?? '';
    //     $status = $request->status ?? -1;
    //     $vehicleName = $request->vehicleName ?? '';
    //     $custPhone = $request->custPhone ?? '';
    //     $buyer_no = $request->buyer_no ?? '';
    //     $lot_no = $request->lot_no ?? '';
    //     $stock_no = $request->stock_no ?? '';
    //     $port_val = $request->port_val ?? '';
    //     $vin_num = $request->vin_num ?? '';
    //     $user2 = User::where('slug', $userName)->orWhere('name', $userName)->get();
    //     // return $user2->id;

    //     $from = '';
    //     $too = '';
    //     if (isset($request->date_range) && !empty($request->date_range)) {
    //         $dates = explode(' - ', $request->date_range);
    //         $from = date('Y-m-d 00:00:00', strtotime($dates[0]));
    //         $too = date('Y-m-d 23:59:59', strtotime($dates[1]));
    //     }

    //     $userId = [];
    //     if (isset($user2[0])) {
    //         foreach ($user2 as $key => $val) {
    //             array_push($userId, $val->id);
    //         }
    //     }
    //     $user = Auth::user();

    //     $order = AutoOrder::with(['filterHistory.filterUser', 'orderTaker'])->select([
    //         'id',
    //         'oname',
    //         'destinationcity',
    //         'destinationzip',
    //         'ymk',
    //         'pstatus',
    //         'paneltype',
    //         'order_taker_id',
    //         'created_at',
    //     ])
    //         ->where(function ($q) use ($user) {
    //             if ($user->userRole->name == 'Manager') {
    //                 if ($user->order_taker_quote == 1) {
    //                     $q->where('manager_id', $user->id)->orWhere('order_taker_id', $user->id);
    //                 }
    //             } else if ($user->userRole->name == 'Dispatcher') {
    //                 if ($user->order_taker_quote == 1) {
    //                     $q->where('dispatcher_id', $user->id);
    //                 }
    //             } else if ($user->userRole->name == 'Delivery Boy') {
    //                 if ($user->order_taker_quote == 1) {
    //                     $q->where('delivery_boy_id', $user->id);
    //                 }
    //             } else if ($user->userRole->name == 'Order Taker' || $user->userRole->name == 'CSR' || $user->userRole->name == 'Seller Agent') {
    //                 if ($user->order_taker_quote == 1) {
    //                     $q->where('order_taker_id', $user->id);
    //                 } else if ($user->order_taker_quote == 2) {
    //                     $q->whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$user->id]);
    //                 }
    //             }
    //         })
    //         ->where(function ($q) use ($id, $clientName, $delivery, $zip, $vehicle, $status, $userId, $vehicleName, $custPhone, $buyer_no, $lot_no, $stock_no, $port_val, $vin_num) {
    //             $q->where('obuyer_no', 'LIKE', '%' . $buyer_no . '%')
    //             ->where('oname','LIKE','%'.$clientName.'%')
    //             ->where('destinationcity','LIKE','%'.$delivery.'%')
    //             ->where('destinationzip','LIKE','%'.$zip.'%')
    //             ->where('ymk','LIKE','%'.$vehicle.'%')
    //             // ->where('obuyer_lot_no','LIKE','%'.$lot_no.'%')
    //             // ->where('obuyer_stock_no','LIKE','%'.$stock_no.'%')
    //             // ->where('cphone','LIKE','%'.$custPhone.'%')
    //             // ->where('port_val','LIKE','%'.$port_val.'%')
    //             ->where('vin_num','LIKE','%'.$vin_num.'%');
    //                 if($status >= 0)
    //                 {
    //                     $q->where('pstatus','=',$status);
    //                 }
    //                 if($id > 0)
    //                 {
    //                     $q->where('id',$id);
    //                 }
    //                 if($userId)
    //                 {
    //                     $q->whereIn('order_taker_id',$userId);
    //                 }
    //             })
    //         ->where('paneltype', $ptype)
    //         ->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))
    //         ->orderBy('id', 'DESC');

    //     dd( $custPhone, $lot_no, $stock_no, $port_val, $order->get()->toArray(), $request->toArray());

    //     if (!empty($from) && !empty($too)) {
    //         $order = $order->whereBetween('created_at', [$from, $too]);
    //     }

    //     $order = $order->paginate(100);

    //     // echo "<pre>";
    //     // print_r($order->toArray());exit();


    //     if ($request->ajax()) {
    //         return view('main.filtered.search', compact('order'));
    //     }
    //     return view('main.filtered.index', compact('order'));
    // }

    public function show(Request $request)
    {
        // dd($request->toArray());
        $user = Auth::user();
        $setting = general_setting::first();
        $ptype = $this->check_user_setting($user->id);

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
        ];

        $user2 = User::where('slug', $filters['userName'])
            ->orWhere('name', $filters['userName'])
            ->get();
        $userId = $user2->pluck('id')->toArray();

        $from = $too = '';
        if (!empty($filters['date_range'])) {
            $dates = explode(' - ', $filters['date_range']);
            $from = date('Y-m-d 00:00:00', strtotime($dates[0]));
            $too = date('Y-m-d 23:59:59', strtotime($dates[1]));
        }

        $order = AutoOrder::with(['filterHistory.filterUser', 'orderTaker'])
            ->select([
                'id',
                'oname',
                'destinationcity',
                'destinationzip',
                'ymk',
                'pstatus',
                'paneltype',
                'order_taker_id',
                'created_at'
            ])
            ->where(function ($q) use ($user) {
                if ($user->userRole->name === 'Manager') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('manager_id', $user->id)
                            ->orWhere('order_taker_id', $user->id);
                    }
                } elseif ($user->userRole->name === 'Dispatcher') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('dispatcher_id', $user->id);
                    }
                } elseif ($user->userRole->name === 'Delivery Boy') {
                    if ($user->order_taker_quote == 1) {
                        $q->where('delivery_boy_id', $user->id);
                    }
                } elseif (in_array($user->userRole->name, ['Order Taker', 'CSR', 'Seller Agent'])) {
                    if ($user->order_taker_quote == 1) {
                        $q->where('order_taker_id', $user->id);
                    } elseif ($user->order_taker_quote == 2) {
                        $q->whereRaw('FIND_IN_SET(?, manager_ot_ids)', [$user->id]);
                    }
                }
            })
            ->where(function ($q) use ($filters, $userId) {
                if (!empty($filters['buyer_no'])) {
                    $q->where('obuyer_no', 'LIKE', '%' . $filters['buyer_no'] . '%');
                }
                if (!empty($filters['clientName'])) {
                    $q->where('oname', 'LIKE', '%' . $filters['clientName'] . '%');
                }
                if (!empty($filters['delivery'])) {
                    $q->where('destinationcity', 'LIKE', '%' . $filters['delivery'] . '%');
                }
                if (!empty($filters['zip'])) {
                    $q->where('destinationzip', 'LIKE', '%' . $filters['zip'] . '%');
                }
                if (!empty($filters['custPhone'])) {
                    $q->where('ophone', 'LIKE', '%' . $filters['custPhone'] . '%')
                        ->orWhere('cphone', 'LIKE', '%' . $filters['custPhone'] . '%');
                }
                if (!empty($filters['dphone'])) {
                    $q->where('dphone', 'LIKE', '%' . $filters['dphone'] . '%');
                }
                if (!empty($filters['vehicle'])) {
                    $q->where('ymk', 'LIKE', '%' . $filters['vehicle'] . '%');
                }
                if (!empty($filters['vin_num'])) {
                    $q->where('vin_num', 'LIKE', '%' . $filters['vin_num'] . '%');
                }
                if ($filters['status'] !== '') {
                    $q->where('pstatus', $filters['status']);
                }
                if (!empty($userId)) {
                    $q->whereIn('order_taker_id', $userId);
                }
                if (!empty($filters['id'])) {
                    $q->where('id', $filters['id']);
                }
                if (!empty($filters['buyer_no'])) {
                    $q->where('obuyer_no', $filters['buyer_no']);
                }
                if (!empty($filters['lot_no'])) {
                    $q->where('obuyer_lot_no', $filters['lot_no']);
                }
                if (!empty($filters['stock_no'])) {
                    $q->where('obuyer_stock_no', $filters['stock_no']);
                }
                if ($filters['port_val'] == 'Port') {
                    $q->where('dauction', 'Port');
                }
            })
            ->where('paneltype', $ptype)
            ->when(!empty($filters['call_type']), function ($q) use ($filters) {
                $q->where('call_type', $filters['call_type']);
            });

        if (!empty($from) && !empty($too)) {
            $order->whereBetween('created_at', [$from, $too]);
        }

        $order = $order->orderBy('id', 'DESC')->paginate(100);

        if ($request->ajax()) {
            return view('main.filtered.search', compact('order'));
        }

        return view('main.filtered.index', compact('order'));
    }

    public function edit(Request $request)
    {
        $mile = MilePrice::find($request->id);
        return response()->json([
            'mile' => $mile,
            'status' => true,
            'status_code' => 200
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'mile' => 'required',
            'milePrice' => 'required|numeric',
            'commissionPrice' => 'required|numeric',
        ]);
        if ($validator->passes()) {
            $mile = MilePrice::find($id);
            $mile->mile = $request->mile;
            $mile->mile_price = $request->milePrice;
            $mile->commission = $request->commissionPrice;
            $mile->save();
            return back()->with('flash_message', 'Price Per Mile Updated Successfully!');
        } else {
            return back()->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MilePrice::destroy($id);
        return back()->with('flash_message', 'Price Per Mile Deleted Successfully!');
    }

    public function indexOfferPrice()
    {
        $data = OfferPrice::orderBy('from_price', 'ASC')->paginate(20);
        $label = FieldLabel::all();
        // echo "<pre>";
        // print_r($data);
        // exit();
        return view('main.offer_price.index', compact('data', 'label'));
    }

    public function storeOfferPrice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_price' => 'required|between:0,99999.99',
            'to_price' => 'required|between:0,99999.99',
            'commission_price' => 'required|between:0,99999.99',
        ]);
        if ($validator->passes()) {
            $data = new OfferPrice;
            $data->from_price = $request->from_price;
            $data->to_price = $request->to_price;
            $data->commission_price = $request->commission_price;
            $data->save();
            return back()->with('flash_message', 'New Offer Price Added Successfully!');
        } else {
            return back()->withInput()->withErrors($validator);
        }
    }

    public function editOfferPrice(Request $request)
    {
        $data = OfferPrice::find($request->id);
        return response()->json([
            'data' => $data,
            'status' => true,
            'status_code' => 200
        ]);
    }

    public function updateOfferPrice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_price' => 'required|between:0,99999.99',
            'to_price' => 'required|between:0,99999.99',
            'commission_price' => 'required|between:0,99999.99',
        ]);
        if ($validator->passes()) {
            $data = OfferPrice::find($request->id);
            $data->from_price = $request->from_price;
            $data->to_price = $request->to_price;
            $data->commission_price = $request->commission_price;
            $data->save();
            return back()->with('flash_message', 'Offer Price Updated Successfully!');
        } else {
            return back()->withInput()->withErrors($validator);
        }
    }

    public function destroyOfferPrice($id)
    {
        OfferPrice::destroy($id);
        return back()->with('flash_message', 'Offer Price Deleted Successfully!');
    }

    public function get_commission(Request $request)
    {
        $data = OfferPrice::where([
            ['from_price', '<=', $request->price],
            ['to_price', '>=', $request->price]
        ])->orderBy('to_price', 'ASC')->first();
        // dd($data->toArray());
        return response()->json([
            'data' => $data,
            'status' => true,
            'status_code' => 200
        ]);
    }

    public function index_commission_range()
    {
        $data = CommissionRange::orderBy('created_at', 'ASC')->paginate(20);
        $label = FieldLabel::all();
        // echo "<pre>";
        // print_r($data);
        // exit();
        return view('main.commision_range.index', compact('data', 'label'));
    }

    public function store_commission_range(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_order' => 'required|integer',
            'to_order' => 'required|integer',
            'from_avg' => 'required|numeric|between:0,9999999999.99',
            'to_avg' => 'required|numeric|between:0,9999999999.99',
            'commission' => 'required|numeric|between:0,9999999999.99',
        ]);
        if ($validator->passes()) {
            $data = new CommissionRange;
            $data->from_order = $request->from_order;
            $data->to_order = $request->to_order;
            $data->from_avg = $request->from_avg;
            $data->to_avg = $request->to_avg;
            $data->commission = $request->commission;
            $data->save();
            return back()->with('flash_message', 'New Commissing Range Added Successfully!');
        } else {
            return back()->withInput()->withErrors($validator);
        }
    }

    public function edit_commission_range(Request $request)
    {
        $data = CommissionRange::find($request->id);
        return response()->json([
            'data' => $data,
            'status' => true,
            'status_code' => 200
        ]);
    }

    public function update_commission_range(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_order' => 'required|integer',
            'to_order' => 'required|integer',
            'from_avg' => 'required|numeric|between:0,9999999999.99',
            'to_avg' => 'required|numeric|between:0,9999999999.99',
            'commission' => 'required|numeric|between:0,9999999999.99',
        ]);
        if ($validator->passes()) {
            $data = CommissionRange::find($request->id);
            $data->from_order = $request->from_order;
            $data->to_order = $request->to_order;
            $data->from_avg = $request->from_avg;
            $data->to_avg = $request->to_avg;
            $data->commission = $request->commission;
            $data->save();
            return back()->with('flash_message', 'Commissing Range Updated Successfully!');
        } else {
            return back()->withInput()->withErrors($validator);
        }
    }

    public function destroy_commission_range($id)
    {
        CommissionRange::destroy($id);
        return back()->with('flash_message', 'Commissing Range Deleted Successfully!');
    }
}
