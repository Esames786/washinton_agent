<?php

namespace App\Http\Controllers\phone_quote\carrier;

use App\call_history;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\role;
use App\AutoOrder;
use App\report;
use App\zipcodes;
use App\count_click;
use App\count_carrier;
use App\count_carrier_history;
use App\carrier;
use App\carriers_company;
use Session;
use Redirect;
use Hash;
use Mail;
use Auth;
use DB;
use App\count_day;
use Carbon;
use Vinkla\Hashids\Facades\Hashids;
use App\storage;
use App\SheetDetails;
use App\HistoryBlockCompany;
use App\FieldLabel;

class CarrierController extends Controller
{

    public function carrier_list(Request $request)
    {
        if ($request->has('status')) {
            $data = carrier::where('status', $request->status)->orderby("orderId", 'DESC')->paginate(50);
        } else {
            $data = carrier::orderby("orderId", 'DESC')->paginate(50);
        }
        if ($request->ajax()) {
            // return view('main.phone_quote.carrier.load', compact('data'))->render();
            $html = view('main.phone_quote.carrier.load', compact('data'))->render();
            return response()->json(['html' => $html]);
        } else {
            return view('main.phone_quote.carrier.carrierlist', compact('data'));
        }
    }

    public function get_carrier_by_location(Request $request)
    {
        if (isset($request->olcation) && isset($request->dlcation)) {
            $origin = $request->olcation;
            $destination = $request->dlcation;
            $order_id = $request->order_id;
            $olcation = explode(',', $request->olcation);
            $dlcation = explode(',', $request->dlcation);
            $ostate = '';
            $dstate = '';
            if (isset($olcation[1])) {
                $ostate = $olcation[1];
                $ostate = str_replace(" ", "", $ostate);
            }
            if (isset($dlcation[1])) {
                $dstate = $dlcation[1];
                $dstate = str_replace(" ", "", $dstate);
            }
            // echo "<pre>";
            // print_r($ostate);
            // echo "<pre>";
            // print_r($dstate);
            // exit();
            $data = carrier::leftJoin('order', 'carriers.id', '=', 'order.carrier_id')
                ->where(function ($query) use ($ostate, $dstate, $request) {
                    $query->where('order.originstate', $ostate)
                        ->where('order.destinationstate', $dstate)
                        ->where('order.id', '!=', $request->order_id);
                })
                ->orderBy('carriers.created_at', 'DESC')
                ->select([
                    'carriers.orderId',
                    'carriers.userId',
                    'carriers.role_name',
                    'carriers.companyname',
                    'carriers.mcno',
                    'carriers.contact',
                    'carriers.address',
                    'carriers.address_2',
                    'carriers.location',
                    'carriers.companyphoneno',
                    'carriers.driverphoneno',
                    'carriers.cell_phone',
                    'carriers.fax',
                    'carriers.certificate',
                    'carriers.created_at',
                    'carriers.reason_user_id',
                    'carriers.comments',
                    'carriers.reason',
                    'carriers.zip_code',
                    'carriers.est_pickupdate',
                    'carriers.est_deliverydate',
                    'carriers.when_pick_date',
                    'carriers.when_delivery_date',
                    'carriers.askprice',
                    'carriers.opt_insurance',
                    'carriers.opt_negative',
                    'carriers.negative_no',
                    'carriers.carrier_id',
                    'carriers.who_pickup',
                    'carriers.carrier_cancel',
                    'carriers.email',
                    'order.*'
                ])
                ->paginate(25);



            // echo "<pre>";
            // print_r($data->toArray());
            // exit();
            // $data = DB::select("select `order`.`carrier_id`, `carriers`.`id`,`carriers`.`companyname`, `carriers`.`orderId`, `carriers`.`location`, `carriers`.`mcno`, `carriers`.`companyphoneno`, `carriers`.`driverphoneno`, `carriers`.`est_pickupdate`, `carriers`.`est_deliverydate`, `carriers`.`askprice`, `carriers`.`opt_insurance`, `carriers`.`opt_negative`, `carriers`.`negative_no`, `carriers`.`comments`, `carriers`.`created_at` from `carriers` left join `order` on `order`.`carrier_id` = `carriers`.`id` where `order`.`originzsc` = '$request->olcation' and  `order`.`destinationzsc` = '$request->dlcation' ");
            if (count($data) > 0) {
                return view('main.phone_quote.new.compare_carrier', compact('data', 'origin', 'destination', 'order_id'));
            }
        }
    }

    public function get_storage_by_location(Request $request)
    {
        if (isset($request->olcation) && isset($request->dlcation)) {
            $origin = $request->olcation;
            $destination = $request->dlcation;
            $olcation = explode(',', $request->olcation);
            $dlcation = explode(',', $request->dlcation);
            $ostate = '';
            $dstate = '';
            if (isset($olcation[1])) {
                $ostate = $olcation[1];
                $ostate = str_replace(" ", "", $ostate);
            }
            if (isset($dlcation[1])) {
                $dstate = $dlcation[1];
                $dstate = str_replace(" ", "", $dstate);
            }

            $data = storage::where('state', $ostate)
                ->orWhere('state', $dstate)
                ->orderBy('created_at', 'DESC')
                ->paginate(25);

            if (count($data) > 0) {
                return view('main.phone_quote.new.compare_storage', compact('data', 'origin', 'destination'));
            }
        }
    }

    public function find_carrier(Request $request)
    {

        if (isset($request->originstate) && isset($request->destinationstate)) {
            $order_id = $request->order_id;
            $originstate = $request->originstate;
            $destinationstate = $request->destinationstate;

            $data = carriers_company::groupby('company_name')
                ->where(function ($q) use ($originstate, $destinationstate) {
                    $q->where('typev', 'Carrier');
                })
                ->where(function ($q) use ($request, $originstate, $destinationstate) {
                    $q->orwhere('address', 'like', "%$originstate%")
                        ->orwhere('address', 'like', "%$destinationstate%");
                    ;
                })
                ->get();


            if (count($data) > 0) {

                return view('main.phone_quote.carrier2.load3', compact('data', 'order_id'));
            }
        }


    }


    public function assign_find_carrier(Request $request)
    {
        if (isset($request->select_id) && isset($request->order_id)) {
            $carrier_company = carriers_company::find($request->select_id);
            $carrier = carrier::where('orderId', $request->order_id)->where('carrier_id', $request->select_id)->first();
            if (empty($carrier)) {
                $carrier = new carrier();
                $carrier->orderId = $request->order_id;
                $carrier->userId = Auth::user()->id;
                $carrier->companyname = $carrier_company->company_name;
                $carrier->location = $carrier_company->address;
                $carrier->mcno = $carrier_company->company_name;
                $carrier->companyphoneno = $carrier_company->main_number;
                $carrier->driverphoneno = $carrier_company->local_number;
                $carrier->est_pickupdate = date('Y-m-d');
                $carrier->est_deliverydate = date('Y-m-d');
                $carrier->askprice = null;
                $carrier->opt_insurance = null;
                $carrier->opt_negative = null;
                $carrier->negative_no = null;
                $carrier->carrier_id = $request->select_id;
                $carrier->comments = $carrier_company->typev;
                $carrier->save();
            }
        }
    }

    public function count_carrier(Request $request)
    {

        if (isset($request->order_id) && isset($request->carrier_id)) {

            $autoorders = AutoOrder::find($request->order_id);

            $count_carrier = count_carrier::where('order_id', $request->order_id)
                ->where('user_id', Auth::user()->id)
                ->where('carrier_id', $request->carrier_id)
                ->first();
            if (empty($count_carrier)) {

                $count_carrier = new count_carrier();
                $count_carrier->order_id = $autoorders->id;
                $count_carrier->client_email = $autoorders->oemail;
                $count_carrier->client_name = $autoorders->oname;
                $count_carrier->pstatus = $autoorders->pstatus;
                $count_carrier->total_clicks = 1;
                $count_carrier->user_id = Auth::user()->id;
                $count_carrier->carrier_id = $request->carrier_id;
                $count_carrier->save();

            } else {

                $count_carrier->client_email = $autoorders->oemail;
                $count_carrier->client_name = $autoorders->oname;
                $count_carrier->pstatus = $autoorders->pstatus;
                $count_carrier->total_clicks = $count_carrier->total_clicks + 1;
                $count_carrier->save();
            }

            return $count_carrier->total_clicks;


        }

    }

    function get_user_name($id)
    {

        $query = \App\User::where('id', $id)->first();
        if (!empty($query)) {
            if ($query->slug) {
                return $query->slug;
            } else {
                return $query->name;
            }
        } else {
            return '';
        }

    }

    public function carrier_history(Request $request)
    {

        if (isset($request->order_id) && isset($request->carrier_id)) {

            $temp = "";
            $autoorders = AutoOrder::find($request->order_id);

            $count_carrier = count_carrier_history::where('orderId', $request->order_id)
                ->where('carrier_id', $request->carrier_id)
                ->orderby('id', 'desc')
                ->get();
            $maker = "";
            $i = 0;
            foreach ($count_carrier as $val) {
                if (empty($val->history)) {
                    $val->history = "N/A";
                }
                $span = "<span class='badge badge-dark' >" . $val->history . "</span>";
                $maker = ucwords($this->get_user_name($val->userId)) . "(" . $val->created_at . ") :- " . $span;
                if ($i == 0) {
                    $temp = $maker;

                } else {
                    $temp = $temp . "<br>" . $maker;

                }
                $i++;
            }
            return trim($temp);


        }
    }

    public function carrier_history_post(Request $request)
    {

        if (isset($request->ca_order_id) && isset($request->ca_carrier_id)) {


            $count_carrier = new count_carrier_history();
            $count_carrier->userId = Auth::user()->id;
            $count_carrier->carrier_id = $request->ca_carrier_id;
            $count_carrier->orderId = $request->ca_order_id;
            $count_carrier->history = $request->ca_carrier_comments;
            $count_carrier->save();


            return Redirect::back();
        }
    }

    public function carrier_list2(Request $request)
    {


        $data = carriers_company::orderby("id", 'DESC')->paginate(50);
        if ($request->ajax()) {
            return view('main.phone_quote.carrier2.load2', compact('data'));

        } else {
            return view('main.phone_quote.carrier2.carrierlist2', compact('data'));
        }

    }

    public function carrier_add($id = null, Request $request)
    {
        $label = FieldLabel::all();
        // dd($label[501-4]->toArray());
        if (!empty($id) && !is_null($id)) {
            $orderid = $id;
        } else {
            $orderid = $request->orderid;
        }
        $carrier = $val = AutoOrder::find($orderid);
        if ($carrier == '') {
            Session::flash('flash_message', 'Order Id is not exist');
            return redirect()->back();
        } else {
            return view('main.phone_quote.carrier.index', compact('orderid', 'val', 'label'));
        }
    }

    public function store_carrier(Request $request)
    {
        $carrier = new carrier();
        $carrier->orderId = $request->orderid;
        $carrier->userId = Auth::user()->id;
        $carrier->role_name = Auth::user()->role;
        $carrier->companyname = $request->company_name;
        $carrier->email = !empty($request->email) ? $request->email : '';
        $carrier->location = $request->location;
        $carrier->mcno = !empty($request->mc_no) ? $request->mc_no : '';
        $carrier->zip_code = $request->zip_code;
        $carrier->address = $request->address;
        $carrier->companyphoneno = $request->companyphone;
        $carrier->driverphoneno = $request->driverphone;
        $carrier->est_pickupdate = date('Y-m-d', strtotime($request->pickupdate));
        $carrier->est_deliverydate = date('Y-m-d', strtotime($request->deliverydate));
        $carrier->askprice = $request->askprice;
        if (isset($request->askinsurance) && $request->askinsurance == '1') {
            $carrier->opt_insurance = $request->askinsurance;
        } else {
            $carrier->opt_insurance = '0';
        }
        if (isset($request->negative) && $request->negative == '1') {
            $carrier->opt_negative = $request->negative;
        } else {
            $carrier->opt_negative = '0';
        }
        if (!empty($request->negativenovalue)) {
            $carrier->negative_no = $request->negativenovalue;
        } else {
            $carrier->negative_no = null;
        }
        $carrier->comments = $request->comments;
        $carrier->save();

        $order = AutoOrder::find($request->orderid);
        $order->est_pick_date = date('Y-m-d', strtotime($request->pickupdate));
        $order->est_delivery_date = date('Y-m-d', strtotime($request->deliverydate));
        $order->driver_phone = $request->driverphone;
        $name = explode(' ', $request->company_name);
        if (isset($name[0])) {
            $order->driver_first_name = $name[0];
        }
        if (isset($name[1])) {
            $order->driver_last_name = $name[1];
        }
        $order->updated_at = now();
        $order->save();


        $callhistory = new call_history();
        $callhistory->userId = Auth::user()->id;
        $callhistory->orderId = $request->orderid;
        $callhistory->pstatus = $order->pstatus;
        $callhistory->history = "<h6>Remarks: Carrier Updated <br />" . $request->comments . "</h6>";
        $callhistory->created_at = now();
        $callhistory->updated_at = now();
        $callhistory->save();
        return redirect('/dashboard');
    }

    public function get_carrier2(Request $request)
    {
        $data = carrier::where('orderId', $request->order_id)->where('who_pickup', 1)->orderBy('id', 'DESC')->get();
        if (!isset($data[0])) {
            $data = carrier::where('orderId', $request->order_id)->orderBy('id', 'DESC')->get();
        }
        return response()->json($data, 200);
    }

    public function get_carrier(Request $request)
    {
        $data = carrier::where('orderId', $request->order_id)->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function getonecarrier(Request $request)
    {
        $data = carrier::find($request->id);
        return response()->json($data, 200);
    }

    public function get_sheet(Request $request)
    {
        $data = SheetDetails::where('orderId', $request->order_id)->whereIn('pstatus', [10, 11])
            ->select('id', 'pickup_date', 'delivery_date')
            ->orderBy('created_at', 'DESC')->first();
        return response()->json($data, 200);
    }

    public function get_carrier_name(Request $request)
    {
        $datas = array();
        $searchOrigin = $request->term;
        $data = carrier::where('companyname', '<>', NULL)
            ->where('companyname', 'LIKE', '%' . $searchOrigin . '%')
            ->orderBy('id', 'DESC')
            ->get();

        if ($data) {
            foreach ($data as $val) {
                $statusLabel = $val->status == 0 ? '<span class="blocked">(Blocked)</span>' : '';
                $companyInfo = $val->companyname . ' ' . $statusLabel;

                // Build an array with company info
                $datas[] = $companyInfo;
            }
        }

        return response()->json($datas);
    }

    public function get_carrier_detail(Request $request)
    {
        $data = carrier::where('companyname', $request->carriername)
        ->where('status', 1)
        ->first();
        return $data;
    }

    public function carrier_add_new()
    {
        return view('main.phone_quote.carrier2.add_carrier');
    }

    public function store_carrier222(Request $request)
    {
        $carrier = new carriers_company();
        $carrier->typev = $request->typev;
        $carrier->company_name = $request->company_name;
        $carrier->address = $request->location;
        $carrier->main_number = $request->companyphone;
        $carrier->local_number = $request->localphone;
        $carrier->truck = $request->truck;
        $carrier->equipment = $request->equipments;
        $carrier->route_description = $request->routedescription;
        $carrier->save();
        return redirect('/carrier_list2');
    }

    public function blockCarrier(Request $request)
    {
        // Find the carrier by ID
        $carrier = Carrier::find($request->company_id);
        $blockMcno = Carrier::where('mcno', $carrier->mcno)->get();
        // dd($blockMcno->toArray());
        foreach ($blockMcno as $record) {
            // Update the status field for each record
            $record->update(['status' => $request->status]);
        }
        $history = new HistoryBlockCompany;
        $status = $request->status;

        // Check if the carrier was found
        if ($carrier) {
            // Update the status
            $carrier->update(['status' => $status]);

            $history->user_id = Auth::user()->id;
            $history->company_id = $request->company_id;
            $history->comment = $request->comment;
            $history->status = $request->status;
            $history->save();

            $allHistory = HistoryBlockCompany::with('user')->where('company_id', $request->company_id)->get();

            return $allHistory;

            // return response()->json(['message' => 'Carrier status updated successfully'], 200);
        } else {
            // Handle the case where the carrier was not found
            return response()->json(['message' => 'Carrier not found'], 404);
        }
    }

    public function blockCarrierGet(Request $request)
    {
        $allHistory = HistoryBlockCompany::with('user')->where('company_id', $request->company_id)->get();
        return $allHistory;
    }

    public function checkMcnoStatus(Request $request)
    {
        $data = carrier::where('mcno', $request->mc_no)->first();
        return $data;
    }

    public function getSearchCarriers(Request $request)
    {
        $searchTerm = $request->search;
        $data = Carrier::where(function ($query) use ($searchTerm) {
            $query->where('companyname', 'LIKE', "%$searchTerm%")
                ->orWhere('mcno', 'LIKE', "%$searchTerm%")
                ->orWhere('orderId', 'LIKE', "%$searchTerm%")
                ->orWhere('location', 'LIKE', "%$searchTerm%")
                ->orWhere('companyphoneno', 'LIKE', "%$searchTerm%");
        })
        ->paginate(50);
        return view('main.phone_quote.carrier.load', compact('data'));
    }
}
