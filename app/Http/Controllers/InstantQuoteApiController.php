<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AutoOrder;
use App\orderpayment;
use App\Http\Controllers\Controller;
use App\User;
use App\report;
use App\singlereport;
use App\order_freight;
use App\OrderTakerQouteAccess;
use App\DailyQoute;
use App\creditcard;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InstantQuoteApiController extends Controller
{
    public function submitInstantQuoteDD(Request $request)
    {
        // try {
        //     // Log the received JSON data for inspection
        //     Log::info('Received Request Data: ' . $request->getContent());

        //     // Validate the request data
        //     $request->validate([
        //         'json.Custo_Name' => 'required|string',
        //         'json.Custo_Phone' => 'required|string',
        //         'json.Custo_Email' => 'required|string',
        //         'json.Carrier_Condition' => 'required|string',
        //         // Add other validation rules as needed
        //     ]);

        //     // Decode the JSON data
        //     $requestData = $request->json()->all(); // Retrieve all data as an array

        //     // Get the referer URL
        //     // $source = isset ($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Unknown';

        //     // Get the last order to determine the new order ID
        //     $lastOrder = AutoOrder::orderBy('id', 'DESC')->first();
        //     $newOrderId = $lastOrder ? $lastOrder->id + 1 : 1;

        //     // Create a new AutoOrder instance
        //     $autoorder = AutoOrder::create([
        //         'id' => $newOrderId,
        //         'oname' => $requestData['json']['Custo_Name'],
        //         'ophone' => $requestData['json']['Custo_Phone'],
        //         'main_ph' => $requestData['json']['Custo_Phone'],
        //         // 'car_type' => $requestData['Select_Vehicle'],
        //         'originzip' => $requestData['json']['From_ZipCode'],
        //         'originzsc' => $requestData['json']['From_ZipCode'],
        //         'destinationzip' => $requestData['json']['To_ZipCode'],
        //         'destinationzsc' => $requestData['json']['To_ZipCode'],
        //         'oemail' => $requestData['json']['Custo_Email'],
        //         'condition' => $requestData['json']['Carrier_Condition'],
        //         'paneltype' => 2,
        //         'source' => 'source',
        //         'manager_id' => null, // Set manager_id to null; adjust as needed
        //         'updated_at' => now(),
        //     ]);

        //     // Create a new order payment instance
        //     OrderPayment::create(['orderId' => $newOrderId]);

        //     // Return a response
        //     return response()->json([
        //         'message' => 'Form submitted successfully',
        //         'requestData' => $requestData,
        //         'orderId' => $newOrderId,
        //     ], 200);
        // } catch (\Exception $e) {
        //     // Log the error with more details
        //     Log::error('Error submitting form: ' . $e->getMessage() . "\n" . $e->getTraceAsString());

        //     // Return an error response
        //     return response()->json(['error' => 'Failed to submit form. Check logs for details.'], 500);
        // }
//        Log::info('Received Request Data: ' . json_encode($request->all(), JSON_PRETTY_PRINT));

        // Validate the incoming request data
        $validatedData = $request->validate([
            // Define validation rules for each field
            'originzip' => 'required',
            'originstate' => 'required',
            'origincity' => 'required',
            'destinationzip' => 'required',
            'destinationstate' => 'required',
            'destinationcity' => 'required',
            'shippingdate' => 'required',
            'transport' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'phonenumber' => 'required',
            'payment' => 'required',
            'cstate' => 'required',
            'czip' => 'required',
            'ccity' => 'required',
            'pickup_date' => 'required',
            'delivery_date' => 'required',
            'status' => 'required'
        ]);

        // Create a new order instance
        $order = new AutoOrder();

        // Assign values to the order object from the validated request data
        $order->originzip = $validatedData['originzip'];
        $order->originstate = $validatedData['originstate'];
        $order->origincity = $validatedData['origincity'];
        $order->destinationzip = $validatedData['destinationzip'];
        $order->destinationstate = $validatedData['destinationstate'];
        $order->destinationcity = $validatedData['destinationcity'];
        $order->shippingdate = $validatedData['shippingdate'];
        $order->transport = $validatedData['transport'];
        $order->name = $validatedData['name'];
        $order->email = $validatedData['email'];
        $order->phonenumber = $validatedData['phonenumber'];
        $order->payment = $validatedData['payment'];
        $order->cstate = $validatedData['cstate'];
        $order->czip = $validatedData['czip'];
        $order->ccity = $validatedData['ccity'];
        $order->pickup_date = $validatedData['pickup_date'];
        $order->delivery_date = $validatedData['delivery_date'];
        $order->pstatus = $validatedData['status'];
        $order->paneltype = 4;

        // Save the order to the database
        $order->save();



        // Return a response, such as a success message or a redirect
        return response()->json(['message' => 'Order submitted successfully'], 200);
    }

    public function submitInstantQuote(Request $request)
    {
        try {
            // Validate request data
            $validatedData = $request->validate([
                'oname' => 'nullable|string',
                'oemail' => 'nullable|email',
                'ophone' => 'nullable|string',
                'ymk' => 'nullable|string',
                'year' => 'nullable|integer',
                'type' => 'nullable|string',
                'vehicle_opt' => 'nullable|string',
                'model' => 'nullable|string',
                'make' => 'nullable|string',
                'condition' => 'nullable|string',
                'originzsc' => 'nullable|string',
                'originzip' => 'nullable|string',
                'originstate' => 'nullable|string',
                'origincity' => 'nullable|string',
                'destinationzsc' => 'nullable|string',
                'destinationzip' => 'nullable|string',
                'destinationstate' => 'nullable|string',
                'destinationcity' => 'nullable|string',
                'add_info' => 'nullable|string',
                'transport' => 'nullable|string',
                'shippingdate' => 'nullable|string',
                'car_type' => 'nullable|string',
                'paneltype' => 'nullable|string',
                'cname' => 'nullable|string',
                'cemail' => 'nullable|email',
                'main_ph' => 'nullable|string',
                'length_ft' => 'nullable|integer',
                'length_in' => 'nullable|integer',
                'width_ft' => 'nullable|integer',
                'width_in' => 'nullable|integer',
                'height_ft' => 'nullable|integer',
                'height_in' => 'nullable|integer',
                'weight' => 'nullable|numeric',
                'load_method' => 'nullable|string',
                'unload_method' => 'nullable|string',
                // 'ip' => 'nullable|string',
                'ipcity' => 'nullable|string',
                'ipregion' => 'nullable|string',
                'ipcountry' => 'nullable|string',
                'iploc' => 'nullable|string',
                'ippostal' => 'nullable|string',
                'source' => 'nullable|string',
                'roro' => 'nullable|string',
            ]);

            // // Retrieve eligible user for quote submission
            // $user = DailyQoute::with('user.userRole')
            //     ->where('total_quote', '>', 0)
            //     ->where('date', date('Y-m-d'))
            //     ->whereHas('user', function ($q) {
            //         $q->where('deleted', 0)->where('is_login', 1);
            //     })
            //     ->whereHas('user.userRole', function ($q) {
            //         $q->whereIn('name', ['Order Taker', 'Seller Agent']);
            //     })
            //     ->orderBy('total_quote', 'DESC')
            //     ->first();

            // if (!$user) {
            //     // If no eligible user found, select randomly
            //     $user = User::with('userRole')
            //         ->where('deleted', 0)
            //         ->whereHas('userRole', function ($q) {
            //             $q->whereIn('name', ['Order Taker', 'Seller Agent']);
            //         })
            //         ->inRandomOrder()
            //         ->firstOrFail();
            // }

            // // Determine order taker ID
            // $orderTakerId = $user->id;

            // Begin database transaction
            DB::beginTransaction();

            $ip = $request->ip();

            // Create AutoOrder instance
            $order = AutoOrder::create([
                'order_taker_id' => 112233,
                'oname' => $validatedData['oname'],
                'oemail' => $validatedData['oemail'],
                'ophone' => $validatedData['ophone'],
                'ymk' => $validatedData['ymk'],
                'year' => $validatedData['year'],
                'type' => $validatedData['type'],
                'vehicle_opt' => $validatedData['vehicle_opt'],
                'model' => $validatedData['model'],
                'make' => $validatedData['make'],
                'condition' => $validatedData['condition'],
                'originzsc' => $validatedData['originzsc'],
                'originzip' => $validatedData['originzip'],
                'originstate' => $validatedData['originstate'],
                'origincity' => $validatedData['origincity'],
                'destinationzsc' => $validatedData['destinationzsc'],
                'destinationzip' => $validatedData['destinationzip'],
                'destinationstate' => $validatedData['destinationstate'],
                'destinationcity' => $validatedData['destinationcity'],
                'add_info' => $validatedData['add_info'],
                'transport' => $validatedData['transport'],
                'shippingdate' => $validatedData['shippingdate'],
                'car_type' => $validatedData['car_type'],
                'paneltype' => $validatedData['paneltype'],
                'cname' => $validatedData['cname'],
                'cemail' => $validatedData['cemail'],
                'main_ph' => $validatedData['main_ph'],
                'length_ft' => $validatedData['length_ft'],
                'length_in' => $validatedData['length_in'],
                'width_ft' => $validatedData['width_ft'],
                'width_in' => $validatedData['width_in'],
                'height_ft' => $validatedData['height_ft'],
                'height_in' => $validatedData['height_in'],
                'weight' => $validatedData['weight'],
                'load_method' => $validatedData['load_method'],
                'unload_method' => $validatedData['unload_method'],
                'ip_address' => $ip,
                'ipcity' => $validatedData['ipcity'],
                'ipregion' => $validatedData['ipregion'],
                'ipcountry' => $validatedData['ipcountry'],
                'iploc' => $validatedData['iploc'],
                'ippostal' => $validatedData['ippostal'],
                'source' => $validatedData['source'],
                'roro' => $validatedData['roro'],
            ]);

            // Create associated models within the transaction
            $data2 = new orderpayment;
            $data2->orderId = $order->id;
            $data2->save();

            $data2 = new creditcard;
            $data2->orderId = $order->id;
            $data2->save();

            $data3 = new report;
            $data3->userId = 1;
            $data3->orderId = $order->id;
            $data3->pstatus = 0;
            $data3->save();

            $data4 = new singlereport;
            $data4->userId = 1;
            $data4->orderId = $order->id;
            $data4->pstatus = 0;
            $data4->save();


            // $data5 = new order_freight;
            // $data5->order_id = $order->id;
            // $data5->frieght_class = $request['frieght_class'];
            // $data5->equipment_type = $request['equipment_type'];
            // $data5->trailer_specification = $request['trailer_specification'];
            // $data5->ex_pickup_date = $request['ex_pickup_date'];
            // $data5->ex_pickup_time = $request['ex_pickup_time'];
            // $data5->ex_delivery_date = $request['ex_delivery_date'];
            // $data5->ex_delivery_time = $request['ex_delivery_time'];
            // $data5->commodity_detail = $request['commodity_detail'];
            // $data5->commodity_unit = $request['commodity_unit'];
            // $data5->pick_up_services = $request['pick_up_services'];
            // $data5->deliver_services = $request['deliver_services'];
            // $data5->save();

            // Commit transaction
            DB::commit();

            return "SAVE";
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Handle other exceptions
            DB::rollBack();
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
