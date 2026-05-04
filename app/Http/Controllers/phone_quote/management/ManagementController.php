<?php

namespace App\Http\Controllers\phone_quote\management;

use App\Mail\SendCodeMail;
use App\ProfileCard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Guide;
use App\role;
use App\invoice;
use App\InvoiceRoro;
use App\storage;
use App\AutoOrder;
use Session;
use Redirect;
use Hash;
use Mail;
use Auth;
use DB;
use Vinkla\Hashids\Facades\Hashids;
use App\general_setting;
use App\user_setting;
use App\carrier;
use App\call_history;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ManagementController extends Controller
{
    public function invoice_list(Request $request)
    {
        if (Auth::user()->role == 1) {
            $data = invoice::orderBy('created_at', 'DESC')->paginate(50);
        } else {
            $data = invoice::where('user_id', '=', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(50);
        }

        $display = 'no';

        if (isset($request->pass)) {
            $check = \App\ReportPassword::first();
            if (!empty($check)) {
                if (Hash::check($request->pass, $check->password)) {
                    $display = 'yes';
                } else {
                    $display = 'no';
                }
            } else {
                $display = 'no';
            }
        }
        if ($request->ajax()) {

            return view('main.phone_quote.management.load', compact('data', 'display'))->render();
        } else {
            return view('main.phone_quote.management.invoicelist', compact('data', 'display'));
        }
    }

    public function show_payment_logs(Request $request)
    {

        // $payment_log = payment_log::where('orderId', '=', $request->orderid)->get();
        $payment_log = DB::table('payment_logs as plog')
            ->select('plog.*', 'profit.profit')
            ->leftjoin('profit as profit', 'plog.orderId', '=', 'profit.order_id')
            ->where('plog.orderId', '=', $request->orderid)
            ->get();


        echo $payment_log;
    }

    public function invoice_add()
    {
        return view('main.phone_quote.management.invoiceadd');
    }

    public function guides()
    {
        $Guide = Guide::all();
        return view('main.phone_quote.management.guides', compact('Guide'));
    }
    public function add_guide_list(Request $request)
    {
        $data = Guide::with('user')->orderby("id", 'DESC')->withTrashed()->paginate(50);
        // dd($data->toArray());
        if ($request->ajax()) {
            return view('main.phone_quote.management.add_guide_load', compact('data'));
        } else {
            return view('main.phone_quote.management.add_guide_list', compact('data'));
        }
    }
    public function add_guide(Request $request)
    {
        if (isset($request->id)) {
            $data = Guide::find($request->id);
            return view('main.phone_quote.management.add_guide', compact('data'));
        }
        return view('main.phone_quote.management.add_guide');
    }
    public function add_guide_post(Request $request)
    {
        // dd($request->toArray());

        $thumbnail = $request->file('thumbnail');
        $thumbnailName = time() . '_' . $thumbnail->getClientOriginalName();
        $thumbnailPath = $thumbnail->storeAs('thumbnails', $thumbnailName, 'public');

        if (!empty($request->id)) {
            $guide = Guide::where('id', $request->id)->first();
        } else {
            $guide = new Guide();
        }

        $guide->guide_type = $request->guide_type;
        $guide->page_name = $request->page_name;
        $guide->page_route = $request->page_route;
        $guide->thumbnail = $thumbnailPath;
        $guide->guide_content = $request->guide_content;
        $guide->created_at = date('Y-m-d');
        $guide->save();

        return redirect()->back()->with('success', 'Guide created successfully!');
    }
    public function guide($guide)
    {
        $data = Guide::where('page_route', "$guide")->first();
        return view('main.phone_quote.management.guide', compact('data'));
    }
    public function del_guide($id)
    {
        $data = Guide::withTrashed()->find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Guide not found!');
        }

        // Check if the record is already deleted
        if ($data->trashed()) {
            $data->deleted_by = null;
            $data->save();
            // If deleted, restore it
            $data->restore();
            return redirect()->back()->with('success', 'Guide recovered successfully!');
        }

        $data->deleted_by = Auth::user()->id;
        $data->save();
        // If not deleted, soft delete
        $data->delete();

        return redirect()->back()->with('success', 'Guide deleted successfully!');
    }

    public function tags()
    {
        return view('main.phone_quote.management.tags');
    }
    public function luxuryVehicle()
    {
        return view('main.phone_quote.management.luxury');
    }

    public function nonLuxuryVehicle()
    {
        return view('main.phone_quote.management.non-luxury');
    }
    public function vehicle_body_type()
    {
        return view('main.phone_quote.management.vehicle_body_type');
    }
    public function vehicle_parts()
    {
        return view('main.phone_quote.management.vehicle_parts');
    }
    public function trailers()
    {
        return view('main.phone_quote.management.trailers');
    }
    public function vehicle_condition()
    {
        return view('main.phone_quote.management.vehicle_condition');
    }
    public function motorcycle_body_type()
    {
        return view('main.phone_quote.management.motorcycle_body_type');
    }
    public function heavy_equipments()
    {
        return view('main.phone_quote.management.heavy_equipments');
    }
    public function boat_shipping()
    {
        return view('main.phone_quote.management.boat_shipping');
    }


    public function store_invoice(Request $request)
    {
        $saveinvoice = new invoice();
        $saveinvoice->orderId = $request->orderid;
        $saveinvoice->user_id = Auth::user()->id;
        $saveinvoice->carrier_fee = $request->carrierfee;
        $saveinvoice->cod = $request->cod ?? 0;
        $saveinvoice->owes = $request->owes ?? 0;
        $saveinvoice->site = $request->site;
        $saveinvoice->deposit = $request->deposit ?? 0;
        $saveinvoice->customer_address = $request->customer_address ?? null;
        $saveinvoice->save();
        return redirect('/invoice_list');
    }

    public function view_invoice($id)
    {
        $data = Invoice::where('id', $id)->first();

        if (!$data) {
            return redirect()->back()->with('error', 'Invoice not found.');
        }

        $order = AutoOrder::find($data->orderId);

        if (!$order) {
            return redirect()->back()->with('error', 'Order not found.');
        }

        $view = $data->site == 'Ship A1(Broker)'
            ? 'main.phone_quote.management.print_invoice_broker'
            : 'main.phone_quote.management.print_invoice';

        return view($view, compact('data', 'order'));
    }

    ///////storage
    public function storage_list(Request $request)
    {
        $data = storage::where('id', '<>', 0)
            ->where(function ($q) use ($request) {
                $q->where('company_name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('manager_owner_name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('company_address', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('zip', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('forklift_twotruck', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('phoneno', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('phoneno2', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('faxno', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('storage_duration', 'LIKE', '%' . $request->search . '%');
            })
            ->orderby('id', 'desc')->paginate(25);
        if ($request->ajax()) {

            return view('main.phone_quote.management.storage.load', compact('data'))->render();
        } else {
            return view('main.phone_quote.management.storage.storagelist', compact('data'));
        }
    }

    public function storage_add()
    {
        return view('main.phone_quote.management.storage.storageadd');
    }

    public function store_storage(Request $request)
    {
        $saveinvoice = new storage();
        $saveinvoice->user_id = Auth::user()->id;
        $saveinvoice->company_name = $request->companyname;
        $saveinvoice->manager_owner_name = $request->managerownername;
        $saveinvoice->company_address = $request->caddress;
        $saveinvoice->zip = $request->zip;
        $saveinvoice->open_time = $request->opentime;
        $saveinvoice->close_time = $request->closetime;
        $saveinvoice->phoneno = $request->phoneno;
        $saveinvoice->phoneno2 = $request->phoneno2;
        $saveinvoice->faxno = $request->faxno;
        $saveinvoice->charges = $request->charges;
        $saveinvoice->charges2 = $request->charges2;
        $saveinvoice->storage_duration = $request->duration;
        $saveinvoice->forklift_twotruck = implode(', ', $request->optionv);
        $state = explode(',', $request->zip);
        if (isset($state[1])) {
            $saveinvoice->state = $state[1];
        }
        $price = NULL;
        $price2 = NULL;
        if (isset($request->optionv)) {
            foreach ($request->optionv as $key => $val) {
                if ($val == 'Forklift') {
                    if (isset($request->forklift_price)) {
                        $price = $request->forklift_price;
                    }
                }
                if ($val == 'Tow Truck') {
                    if (isset($request->tow_truck_price)) {
                        $price2 = $request->tow_truck_price;
                    }
                }
            }
        }
        $saveinvoice->forklift_price = $price;
        $saveinvoice->tow_truck_price = $price2;
        $saveinvoice->additional = $request->additional;
        $saveinvoice->save();
        return redirect('/storage_list');
    }

    public function storage_edit($id)
    {
        $data = storage::find($id);
        return view('main.phone_quote.management.storage.storageedit', compact('data'));
    }

    public function update_storage(Request $request, $id)
    {
        $saveinvoice = storage::find($id);
        $saveinvoice->user_id = Auth::user()->id;
        $saveinvoice->company_name = $request->companyname;
        $saveinvoice->manager_owner_name = $request->managerownername;
        $saveinvoice->company_address = $request->caddress;
        $saveinvoice->zip = $request->zip;
        $saveinvoice->open_time = $request->opentime;
        $saveinvoice->close_time = $request->closetime;
        $saveinvoice->phoneno = $request->phoneno;
        $saveinvoice->phoneno2 = $request->phoneno2;
        $saveinvoice->faxno = $request->faxno;
        $saveinvoice->charges = $request->charges;
        $saveinvoice->charges2 = $request->charges2;
        $saveinvoice->storage_duration = $request->duration;
        $saveinvoice->forklift_twotruck = implode(', ', $request->optionv);
        $price = NULL;
        $price2 = NULL;
        $state = explode(',', $request->zip);
        if (isset($state[1])) {
            $saveinvoice->state = $state[1];
        }
        if (isset($request->optionv)) {
            foreach ($request->optionv as $key => $val) {
                if ($val == 'Forklift') {
                    if (isset($request->forklift_price)) {
                        $price = $request->forklift_price;
                    }
                }
                if ($val == 'Tow Truck') {
                    if (isset($request->tow_truck_price)) {
                        $price2 = $request->tow_truck_price;
                    }
                }
            }
        }
        $saveinvoice->forklift_price = $price;
        $saveinvoice->tow_truck_price = $price2;
        $saveinvoice->additional = $request->additional;
        $saveinvoice->save();
        return redirect('/storage_list');
    }

    public function view_storage($id)
    {
        $data = storage::where('id', '=', $id)->first();
        //return view('main.phone_quote.management.storage.print_invoice',compact('data'));
    }

    public function getAuctionDetails($id)
    {
        $card = \App\ProfileCard::findOrFail($id);
        return response()->json($card);
    }




    public function profile_card_data()
    {
        return view('main.phone_quote.profile_card.index');
    }

    public function profile_card_list(Request $request)
    {
        $query = ProfileCard::query();

        $filterable = ['first_name', 'last_name', 'oemail', 'ophone', 'ophone2', 'oaddress', 'originzsc', 'payment_method', 'customer_type', 'business_name', 'nature_of_customer','panel_type'];

        foreach ($filterable as $column) {
            if ($request->filled($column)) {
                $query->where($column, 'like', '%' . $request->input($column) . '%');
            }
        }

        return DataTables::of($query)
            ->editColumn('panel_type', function ($row) {
                $panelTypes = [
                    1 => 'Auction',
                    2 => 'ProMax',
                    3 => 'Testing Quote',
                    4 => 'Shipa1 Website',
                    5 => 'Panel Type 5 Quote',
                    6 => 'Panel Type 6 Quote',
                ];

                return $panelTypes[$row->panel_type] ?? 'Unknown';
            })
            ->make(true);
    }


    public function report_terminal()
    {
        $data = AutoOrder::where('id', '=', 0)->paginate(50);
        return view('main.phone_quote.report.terminal.terminal_report', compact('data'))->render();
    }

    public function fetch_terminal_data(Request $request)
    {

        if (Auth::check()) {

            $req = 0;
            /*if (isset($request->pstatus)) {
                $req = $request->pstatus;
            }*/
            $user = Auth::user();
            if ($request->ajax()) {
                $data = AutoOrder::where('oterminal', '=', $request->oterminal)
                    ->where('dterminal', '=', $request->dterminal)
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
                    ->whereBetween('created_at', [$request->fromdate, $request->todate])
                    ->orderby('id', 'desc');

                if ($request->has('booker')) {
                    $data = $data->where('u_id', $request->booker);
                }

                $data = $data->paginate(50);
                return view('main.phone_quote.report.terminal.terminal_report_load', compact('data'))->render();
            }
        } else {
            return redirect('/loginn/');
        }
    }
    public function fetch_terminal_data2(Request $request)
    {

        if (Auth::check()) {

            $req = 0;
            /*if (isset($request->pstatus)) {
                $req = $request->pstatus;
            }*/
            $user = Auth::user();
            if (!empty($request->oterminal)) {
                $data = AutoOrder::where('oterminal', '=', $request->oterminal)
                    ->where('dterminal', '=', $request->dterminal)
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
                    ->whereBetween('created_at', [$request->fromdate, $request->todate])
                    ->orderby('id', 'desc')
                    ->paginate(50);
                return view('main.phone_quote.report.terminal.terminal_report_load', compact('data'))->render();
            }
        } else {
            return redirect('/loginn/');
        }
    }

    public function orderStorage($id)
    {
        $oid = decrypt($id);
        $order = AutoOrder::with('storage')->where('id', $oid)->first();
        return view('main.phone_quote.management.storage.orderstorage', compact('order'));
    }

    public function storage_data(Request $request)
    {
        $data = '';
        if (isset($request->name)) {
            $data = storage::where('company_name', 'LIKE', '%' . $request->name . '%')
                ->orderBy('company_name', 'ASC')
                ->select('id', 'company_name')
                ->get();
        }
        return response()->json([
            'data' => $data,
            'status' => true,
            'status_code' => 200
        ]);
    }

    public function storage_data_get(Request $request)
    {
        $data = storage::find($request->id);
        return response()->json([
            'data' => $data,
            'status' => true,
            'status_code' => 200
        ]);
    }

    public function store_update_storage(Request $request)
    {
        $saveinvoice = storage::where('company_name', $request->companyname)->first();
        if (empty($saveinvoice)) {
            $saveinvoice = new storage();
            $saveinvoice->user_id = Auth::user()->id;
        }
        $saveinvoice->company_name = $request->companyname;
        $saveinvoice->manager_owner_name = $request->managerownername;
        $saveinvoice->company_address = $request->caddress;
        $saveinvoice->zip = $request->zip;
        $saveinvoice->open_time = $request->opentime;
        $saveinvoice->close_time = $request->closetime;
        $saveinvoice->phoneno = $request->phoneno;
        $saveinvoice->phoneno2 = $request->phoneno2;
        $saveinvoice->faxno = $request->faxno;
        $saveinvoice->charges = $request->charges;
        $saveinvoice->charges2 = $request->charges2;
        $saveinvoice->storage_duration = $request->duration;
        $saveinvoice->forklift_twotruck = implode(', ', $request->optionv);
        $state = explode(',', $request->zip);
        if (isset($state[1])) {
            $saveinvoice->state = $state[1];
        }
        $price = NULL;
        $price2 = NULL;
        if (isset($request->optionv)) {
            foreach ($request->optionv as $key => $val) {
                if ($val == 'Forklift') {
                    if (isset($request->forklift_price)) {
                        $price = $request->forklift_price;
                    }
                }
                if ($val == 'Tow Truck') {
                    if (isset($request->tow_truck_price)) {
                        $price2 = $request->tow_truck_price;
                    }
                }
            }
        }
        $saveinvoice->forklift_price = $price;
        $saveinvoice->tow_truck_price = $price2;
        $saveinvoice->additional = $request->additional;
        $saveinvoice->save();

        $order = AutoOrder::find($request->order_id);
        if ($request->change_carrier == 'yes') {
            $order->pickup_carrier_id = $request->current_carrier;
        }
        $order->storage_id = isset($saveinvoice->id) ? $saveinvoice->id : 0;
        if (empty($order->storage_date)) {
            $order->storage_date = date('Y-m-d');
        }
        $order->storage_charge = $request->storage_charge;
        $order->updated_at = now();
        $order->save();

        $callhistory = new call_history();
        $callhistory->userId = Auth::user()->id;
        $callhistory->orderId = $order->id;
        $callhistory->pstatus = $order->pstatus;
        $callhistory->history = "<h6>Remarks: This vehicle is now move to storage!</h6>";
        $callhistory->created_at = now();
        $callhistory->updated_at = now();
        $callhistory->save();

        return back()->with('msg', 'Storage Added on this order!');
    }

    public function check_panel()
    {
        $ptype = 1;
        $query = user_setting::where('user_id', Auth::user()->id)->first();
        if (!empty($query)) {
            $ptype = $query['penal_type'];
        }
        return $ptype;
    }

    public function storage_order_list(Request $request)
    {
        $from = '';
        $too = '';
        if (isset($request->date_range) && !empty($request->date_range)) {
            $dates = explode(' - ', $request->date_range);
            $from = date('Y-m-d', strtotime($dates[0]));
            $too = date('Y-m-d', strtotime($dates[1]));
        }
        $setting =    general_setting::first();
        $user = Auth::user();

        $data = AutoOrder::with('storage')
            ->where('storage_id', '>', 0)
            ->where('paneltype', $this->check_panel())
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
            });

        if ($request->ajax()) {
            if (!empty($from) && !empty($too)) {
                if ($from == $too) {
                    $data = $data->whereDate('storage_date', $too);
                } else {
                    $data = $data->whereBetween('storage_date', [$from, $too]);
                }
            }
            if (empty($request->search)) {
                $data = $data->whereIn('pstatus', [11, 12]);
            } else {
                $data = $data->where('pstatus', $request->search);
            }
        } else {
            $data = $data->where('pstatus', 11);
        }
        $data = $data->orderBy('updated_at', 'DESC')->paginate(10);

        if ($request->ajax()) {
            return view('main.phone_quote.management.storage.load2', compact('data'));
        }
        return view('main.phone_quote.management.storage.storage_order_list', compact('data'));
    }

    public function updatePickupCarrier(Request $request)
    {
        $oldcarriers = carrier::where('orderId', $request->id)->select('id', 'companyname')->orderBy('companyname', 'ASC')->get();
        $order = AutoOrder::find($request->id);

        return response()->json([
            'oldcarriers' => $oldcarriers,
            'status' => true,
            'status_code' => 200
        ]);
    }

    public function update_pickup_carreir(Request $request)
    {
        $order = AutoOrder::find($request->order_id);
        if ($request->change_carrier == 'yes') {
            if ($request->pickup_carrier_id > 0) {
                $order->pickup_carrier_id = $request->pickup_carrier_id;
                $order->updated_at = now();
                $order->save();

                $callhistory = new call_history();
                $callhistory->userId = Auth::user()->id;
                $callhistory->orderId = $order->id;
                $callhistory->pstatus = $order->pstatus;
                $callhistory->history = "<h6>Remarks: Pickup another carrier has been updated!</h6>";
                $callhistory->created_at = now();
                $callhistory->updated_at = now();
                $callhistory->save();
            }
        }
        Session::flash('flash_message', 'Pickup carrier has been updated!');
        return back();
    }

    public function invoice_list_roro(Request $request)
    {
        if (Auth::user()->userRole->Admin == 'Admin') {
            $data = InvoiceRoro::orderBy('created_at', 'DESC')->paginate(50);
        } else {
            $data = InvoiceRoro::where('user_id', '=', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(50);
        }

        $display = 'no';

        if (isset($request->pass)) {
            $check = \App\ReportPassword::first();
            if (!empty($check)) {
                if (Hash::check($request->pass, $check->password)) {
                    $display = 'yes';
                } else {
                    $display = 'no';
                }
            } else {
                $display = 'no';
            }
        }
        if ($request->ajax()) {

            return view('main.phone_quote.management.loadroro', compact('data', 'display'))->render();
        } else {
            return view('main.phone_quote.management.invoicelistroro', compact('data', 'display'));
        }
    }

    public function invoice_add_roro()
    {
        return view('main.phone_quote.management.invoiceaddroro');
    }

    public function store_invoice_roro(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'year' => 'required',
            'make' => 'required',
            'model' => 'required',
            'from_address' => 'required',
            'too_address' => 'required',
            'transportation_fees' => 'between:0,99999.99',
            'auction_storage_fees' => 'between:0,99999.99',
            'yard_storage_fees' => 'between:0,99999.99',
            'yard_forklift_fees' => 'between:0,99999.99',
            'redelivery_fees' => 'between:0,99999.99',
            'shipping_fees' => 'between:0,99999.99',
            'non_runner_fees' => 'between:0,99999.99',
            'forklift_fees' => 'between:0,99999.99',
            'telex_fees' => 'between:0,99999.99',
            'extra_other_fees' => 'between:0,99999.99',
        ]);

        if ($validator->passes()) {
            $saveinvoice = new InvoiceRoro;
            $saveinvoice->user_id = Auth::user()->id;
            $saveinvoice->year = $request->year;
            $saveinvoice->make = $request->make;
            $saveinvoice->model = $request->model;
            $saveinvoice->vin = $request->vin;
            $saveinvoice->bill_name = $request->bill_name;
            $saveinvoice->bill_address = $request->bill_address;
            $saveinvoice->from_address = $request->from_address;
            $saveinvoice->too_address = $request->too_address;
            $saveinvoice->delivered_port = $request->delivered_port;
            $saveinvoice->vessel_grimaldi_salluam = $request->vessel_grimaldi_salluam;
            $saveinvoice->transportation_fees = $request->transportation_fees;
            $saveinvoice->auction_storage_fees = $request->auction_storage_fees;
            $saveinvoice->yard_storage_fees = $request->yard_storage_fees;
            $saveinvoice->yard_forklift_fees = $request->yard_forklift_fees;
            $saveinvoice->redelivery_fees = $request->redelivery_fees;
            $saveinvoice->shipping_fees = $request->shipping_fees;
            $saveinvoice->non_runner_fees = $request->non_runner_fees;
            $saveinvoice->forklift_fees = $request->forklift_fees;
            $saveinvoice->telex_fees = $request->telex_fees;
            $saveinvoice->extra_other_fees = $request->extra_other_fees;
            $saveinvoice->paid_amount = $request->paid_amount;
            $saveinvoice->save();
            return redirect('/invoice_list_roro');
        } else {
            return back()->withInput()->withErrors($validator);
        }
    }

    public function view_invoice_roro($id)
    {
        $data = InvoiceRoro::where('id', '=', $id)->first();
        return view('main.phone_quote.management.print_invoice_roro', compact('data'));
    }
}
