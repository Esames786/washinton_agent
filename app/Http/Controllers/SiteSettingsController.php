<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SiteSetting;
use App\FieldLabel;
use App\HistoryFieldLabel;
use Auth;
use Illuminate\Validation\Rule;

class SiteSettingsController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $vehicle = FieldLabel::where('category', null)->orWhere('category', 'vehicle')->get();
        $heavy = FieldLabel::where('category', 'heavy')->get();
        $frieght = FieldLabel::where('category', 'frieght')->get();
        $sidebar = FieldLabel::where('category', 'sidebar')->get();
        $fieldLabel = FieldLabel::all();
        // $fieldLabel = FieldLabel::get();
        return view('field_labels.index', compact('vehicle', 'heavy', 'frieght', 'sidebar', 'fieldLabel'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        return view('field_labels.create');
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        $request['user_id'] = $user_id;
        // Order Fields
        $request->validate([
            'name' => 'required|unique:field_labels',
            'user_id' => 'required',
            'description' => 'nullable', // Example nullable field
            'display' => 'nullable', // Another example nullable field
            'old_display' => 'nullable', // Another example nullable field
            'category' => 'required', // Another example nullable field
            // 'ymk' => 'nullable',
            // 'originzsc' => 'nullable',
            // 'originzip' => 'nullable',
            // 'originstate' => 'nullable',
            // 'origincity' => 'nullable',
            // 'oterminal' => 'nullable',
            // 'oauction' => 'nullable',
            // 'oauctionpho' => 'nullable',
            // 'oauctiondate' => 'nullable',
            // 'oauctiontime' => 'nullable',
            // 'oacutionaccounttitle' => 'nullable',
            // 'oacutionaccountname' => 'nullable',
            // 'destinationzsc' => 'nullable',
            // 'destinationzip' => 'nullable',
            // 'destinationstate' => 'nullable',
            // 'destinationcity' => 'nullable',
            // 'dterminal' => 'nullable',
            // 'dauction' => 'nullable',
            // 'dauctionpho' => 'nullable',
            // 'dauctiondate' => 'nullable',
            // 'dauctiontime' => 'nullable',
            // 'dacutionaccounttitle' => 'nullable',
            // 'dacutionaccountname' => 'nullable',
            // 'shippingdate' => 'nullable',
            // 'transport' => 'nullable',
            // 'email' => 'nullable',
            // 'phonenumber' => 'nullable',
            // 'condition' => 'nullable',
            // 'condition1' => 'nullable',
            // 'condition2' => 'nullable',
            // 'condition3' => 'nullable',
            // 'length_ft' => 'nullable',
            // 'length_in' => 'nullable',
            // 'width_ft' => 'nullable',
            // 'width_in' => 'nullable',
            // 'height_ft' => 'nullable',
            // 'height_in' => 'nullable',
            // 'year' => 'nullable',
            // 'make' => 'nullable',
            // 'model' => 'nullable',
            // 'payment' => 'nullable',
            // 'u_id' => 'nullable',
            // 'pstatus' => 'nullable',
            // 'milage' => 'nullable',
            // 'car_type' => 'nullable',
            // 'ip_address' => 'nullable',
            // 'ipcity' => 'nullable',
            // 'ipregion' => 'nullable',
            // 'ipcountry' => 'nullable',
            // 'iploc' => 'nullable',
            // 'ippostal' => 'nullable',
            // 'browser' => 'nullable',
            // 'platform' => 'nullable',
            // 'phone2' => 'nullable',
            // 'phone3' => 'nullable',
            // 'address' => 'nullable',
            // 'address2' => 'nullable',
            // 'fax' => 'nullable',
            // 'in_auction' => 'nullable',
            // 'color' => 'nullable',
            // 'vbrakes' => 'nullable',
            // 'vrolls' => 'nullable',
            // 'vkey' => 'nullable',
            // 'vdate' => 'nullable',
            // 'oname' => 'nullable',
            // 'oemail' => 'nullable',
            // 'ophone' => 'nullable',
            // 'ophone2' => 'nullable',
            // 'ophone3' => 'nullable',
            // 'obuyer_no' => 'nullable',
            // 'oaddress' => 'nullable',
            // 'oaddress2' => 'nullable',
            // 'dname' => 'nullable',
            // 'demail' => 'nullable',
            // 'dphone' => 'nullable',
            // 'dphone2' => 'nullable',
            // 'dphone3' => 'nullable',
            // 'daddress' => 'nullable',
            // 'daddress2' => 'nullable',
            // 'add_info' => 'nullable',
            // 'yourname' => 'nullable',
            // 'signature' => 'nullable',
            // 'confirm' => 'nullable',
            // 'card_name' => 'nullable',
            // 'card_last_name' => 'nullable',
            // 'billing_address' => 'nullable',
            // 'b_city' => 'nullable',
            // 'b_state' => 'nullable',
            // 'b_zip' => 'nullable',
            // 'card_type' => 'nullable',
            // 'card_number' => 'nullable',
            // 'card_sec' => 'nullable',
            // 'card_exp' => 'nullable',
            // 'card_mm' => 'nullable',
            // 'card_yyyy' => 'nullable',
            // 'deposit' => 'nullable',
            // 'amount_charged' => 'nullable',
            // 'pay_carrier' => 'nullable',
            // 'review_clicked' => 'nullable',
            // 'weight' => 'nullable',
            // 'cstate' => 'nullable',
            // 'czip' => 'nullable',
            // 'ccity' => 'nullable',
            // 'type' => 'nullable',
            // 'pickup_date' => 'nullable',
            // 'delivery_date' => 'nullable',
            // 'pickup_when' => 'nullable',
            // 'delivery_when' => 'nullable',
            // 'cod_cop' => 'nullable',
            // 'payment_method' => 'nullable',
            // 'tran_id' => 'nullable',
            // 'cod_cop_loc' => 'nullable',
            // 'balance' => 'nullable',
            // 'balance_method' => 'nullable',
            // 'balance_time' => 'nullable',
            // 'terms' => 'nullable',
            // 'additional_2' => 'nullable',
            // 'vin_num' => 'nullable',
            // 'vehicle_opt' => 'nullable',
            // 'port_title' => 'nullable',
            // 'portterminal' => 'nullable',
            // 'paneltype' => 'nullable',
            // 'payment_type' => 'nullable',
            // 'send_mail' => 'nullable',
            // 'main_ph' => 'nullable',
            // 'mainPhNum' => 'nullable',
            // 'custName' => 'nullable',
            // 'addInfo' => 'nullable',
            // 'emp_id' => 'nullable',
            // 'booking_confirm' => 'nullable',
            // 'company_name' => 'nullable',
            // 'company_price' => 'nullable',
            // 'cname' => 'nullable',
            // 'carrier_zip' => 'nullable',
            // 'mc' => 'nullable',
            // 'company_contact' => 'nullable',
            // 'caddress' => 'nullable',
            // 'company_address_2' => 'nullable',
            // 'cphone' => 'nullable',
            // 'cphone2' => 'nullable',
            // 'company_cell' => 'nullable',
            // 'company_fax' => 'nullable',
            // 'driver_first_name' => 'nullable',
            // 'driver_last_name' => 'nullable',
            // 'driver_phone' => 'nullable',
            // 'acknowledge' => 'nullable',
            // 'comments' => 'nullable',
            // 'email_time' => 'nullable',
            // 'signature_style' => 'nullable',
            // 'delete_reason' => 'nullable',
            // 'cancel_reason' => 'nullable',
            // 'pay_missing' => 'nullable',
            // 'change_price_count' => 'nullable',
            // 'welcome' => 'nullable',
            // 'book_confirm_email' => 'nullable',
            // 'last_price' => 'nullable',
            // 'change_price_email' => 'nullable',
            // 'dealer_name' => 'nullable',
            // 'vl8' => 'nullable',
            // 'vin' => 'nullable',
            // 'thank_you_email' => 'nullable',
            // 'old_signature_style' => 'nullable',
            // 'old_signature' => 'nullable',
            // 'old_signature_name' => 'nullable',
            // 'already_card' => 'nullable',
            // 'customer_status' => 'nullable',
            // 'trans_method' => 'nullable',
            // 'transcation' => 'nullable',
            // 'trans_type' => 'nullable',
            // 'trans_amount' => 'nullable',
            // 'relist_id' => 'nullable',
            // 'own_reason' => 'nullable',
            // 'listed' => 'nullable',
            // 'driver_price' => 'nullable',
            // 'load_method' => 'nullable',
            // 'unload_method' => 'nullable',
            // 'listed_comments' => 'nullable',
            // 'send_listed' => 'nullable',
            // 'pay_carrier_listed' => 'nullable',
            // 'listing_url' => 'nullable',
            // 'assign_comments' => 'nullable',
            // 'carrier_id' => 'nullable',
            // 'accept_dispatch' => 'nullable',
            // 'dispatch_comments' => 'nullable',
            // 'titles' => 'nullable',
            // 'dock_receipt' => 'nullable',
            // 'vehicle_pickedup' => 'nullable',
            // 'pickedup_comments' => 'nullable',
            // 'owes_money' => 'nullable',
            // 'owes_pay_type' => 'nullable',
            // 'location' => 'nullable',
            // 'method' => 'nullable',
            // 'owes_transaction' => 'nullable',
            // 'owes_comments' => 'nullable',
            // 'owes_doc' => 'nullable',
            // 'vehicle_delivered' => 'nullable',
            // 'delivered_comments' => 'nullable',
            // 'vehicle_completed' => 'nullable',
            // 'completed_comments' => 'nullable',
            // 'dispatch_cancel_reason' => 'nullable',
            // 'insurance_certificate' => 'nullable',
            // 'price_on' => 'nullable',
            // 'price_by' => 'nullable',
            // 'amount' => 'nullable',
            // 'go_status' => 'nullable',
            // 'move_listed_comment' => 'nullable',
            // 'send_authorization' => 'nullable',
            // 'urgent_count' => 'nullable',
            // 'cash_status' => 'nullable',
            // 'paypal_transcation' => 'nullable',
            // 'cheque' => 'nullable',
            // 'payment_update' => 'nullable',
            // 'emp_role' => 'nullable',
            // 'other_status' => 'nullable',
            // 'est_pick_date' => 'nullable',
            // 'est_delivery_date' => 'nullable',
            // 'asking_low' => 'nullable',
            // 'mfollow' => 'nullable',
            // 'intrested' => 'nullable',
            // 'paid_amount' => 'nullable',
            // 'paid_status' => 'nullable',
            // 'need_deposit' => 'nullable',
            // 'deposit_amount' => 'nullable',
            // 'import_comments' => 'nullable',
            // 'status_user_id' => 'nullable',
            // 'status_updated_at' => 'nullable',
            // 'carrier_status' => 'nullable',
            // 'czsc' => 'nullable',
            // 'cemail' => 'nullable',
            // 'listed_price' => 'nullable',
            // 'driver_pickup_date' => 'nullable',
            // 'driver_deliver_date' => 'nullable',
            // 'dispatcher_id' => 'nullable',
            // 'completer_id' => 'nullable',
            // 'countt' => 'nullable',
            // 'pay_confirmed' => 'nullable',
            // 'vehicle' => 'nullable',
            // 'paid_by_user' => 'nullable',
            // 'paid_by_customer' => 'nullable',
            // 'recived_by' => 'nullable',
            // 'recived_date' => 'nullable',
            // 'date_of_booked' => 'nullable',
            // 'confirm_by' => 'nullable',
            // 'comfirm_date' => 'nullable',
            // 'by_many' => 'nullable',
            // 'approaching_time' => 'nullable',
            // 'approaching_reason' => 'nullable',
            // 'payment_method2' => 'nullable',
            // 'owes_reminder' => 'nullable',
            // 't_q_late' => 'nullable',
            // 'additional' => 'nullable',
            // 'approval_reason' => 'nullable',
            // 'vehicle_available_date' => 'nullable',
            // 'key_has' => 'nullable',
            // 'order_taker_id' => 'nullable',
            // 'company_comments' => 'nullable',
            // 'v_con_d' => 'nullable',
            // 'v_con_p' => 'nullable',
            // 'reason_box' => 'nullable',
            // 'port_dock_type' => 'nullable',
            // 'port_line' => 'nullable',
            // 'pay_comments' => 'nullable',
            // 'payment_status' => 'nullable',
            // 'old_code' => 'nullable',
            // 'owes' => 'nullable',
            // 'approve_deliver' => 'nullable',
            // 'approve_pickup' => 'nullable',
            // 'schedule_approve' => 'nullable',
            // 'delivery_boy_id' => 'nullable',
            // 'manager_id' => 'nullable',
            // 'manager_ot_ids' => 'nullable',
            // 'coupon_id' => 'nullable',
            // 'roro' => 'nullable',
            // 'storage_id' => 'nullable',
            // 'storage_date' => 'nullable',
            // 'storage_move_date' => 'nullable',
            // 'storage_charge' => 'nullable',
            // 'already_auction_storage' => 'nullable',
            // 'already_storage' => 'nullable',
            // 'already_storage_date' => 'nullable',
            // 'already_storage_end_date' => 'nullable',
            // 'late_pickup_auction_storage' => 'nullable',
            // 'late_pickup_storage' => 'nullable',
            // 'late_pickup_storage_date' => 'nullable',
            // 'late_pickup_storage_end_date' => 'nullable',
            // 'pickup_carrier_id' => 'nullable',
            // 'start_price' => 'nullable',
            // 'storage_fees' => 'nullable',
            // 'pay_by' => 'nullable',
            // 'other_fees' => 'nullable',
            // 'we_us_driver' => 'nullable',
            // 'miles' => 'nullable',
        ]);

        // dd($request->all());

        $label = FieldLabel::create($request->all());

        return redirect()->route('field_labels.index')->with('success', 'Site setting created successfully!');
    }

    // Display the specified resource.
    public function show($id)
    {
        $fieldLabel = FieldLabel::findOrFail($id);
        return view('field_labels.show', compact('fieldLabel'));
    }

    // Show the form for editing the specified resource.
    public function edit($id)
    {
        $fieldLabel = FieldLabel::findOrFail($id);
        // dd($id);
        return $fieldLabel;
    }

    // Update the specified resource in storage.
    public function update(Request $request)
    {
        $siteSetting = FieldLabel::find($request->LabelID);
        $user_id = Auth::user()->id;
        $request['user_id'] = $user_id;

        $request->validate([
            // 'name' => [
            //     'required',
            //     Rule::unique('field_labels')->ignore($request->LabelID),
            // ],
            'user_id' => 'required',
            'description' => 'nullable',
            'display' => 'nullable',
            'category' => 'required',
            'status' => 'required',
        ]);
        

        if ($siteSetting) {
            $request['old_display'] = $siteSetting->display;

            $siteSetting->update($request->all());
            
            // dd($request->toArray(), $siteSetting->toArray());

            return redirect()->route('field_labels.index')->with('success', 'Site setting updated successfully!');
        } else {
            return redirect()->route('field_labels.index')->with('error', 'Record not found');
        }
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        $siteSetting = FieldLabel::findOrFail($id);
        $siteSetting->delete();

        return redirect()->route('field_labels.index')->with('success', 'Site setting deleted successfully!');
    }

    public function LabeleHistory($field_name)
    {
        $siteSettings = FieldLabel::all();

        $fieldValues = [];

        $uniqueValues = [];

        foreach ($siteSettings as $key => $row) {
            $value = $row->$field_name;

            // Check if the value is unique
            if (!in_array($value, $uniqueValues)) {
                $uniqueValues[] = $value;

                $fieldValues[] = [
                    'value' => $value,
                    'updated_at' => $row->updated_at->toDateTimeString(),
                    'user_name' => $row->user_name,
                ];
            }
        }

        return response()->json(['field_values' => $fieldValues]);
    }
}
