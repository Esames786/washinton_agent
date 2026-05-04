<?php

namespace App\Http\Controllers\phone_quote\RequestPrice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PriceChecker;
use Auth;
use App\User;
use App\AutoOrder;
use App\zipcodes;

class RequestPriceController extends Controller
{
    public function index(Request $request)
    {
        $data = PriceChecker::with('order')->where('order_id', $request->id)->first();
        if ($data) {
            return response()->json([
                'data' => $data,
                'message' => 1,
                'status' => true,
                'status_code' => 200
            ]);
        } else {
            return response()->json([
                'data' => $data,
                'message' => 0,
                'status' => false,
                'status_code' => 400
            ]);
        }
    }

    public function create(Request $request)
    {
        $price = PriceChecker::with('order')->where('order_id', $request->orderID)->first();
        if (empty($price)) {
            $check =  AutoOrder::find($request->orderID);
            // dd($request->toArray());
            // echo "<pre>";
            // print_r($request->all());
            // exit();
            if ($check->car_type != 3) {
                $year = implode('|<br>', $request->year);
                $model = implode('|<br>', $request->model);
                $make = implode('|<br>', $request->make);
                $vinNumber = '';
                if (isset($request->vinNumber)) {
                    $vinNumber = implode('|<br>', $request->vinNumber);
                }
                $type = 1;
                $ty = [];
                if (isset($request->type)) {
                    $type = implode('|<br>', $request->type);
                } else {
                    if (count($request->year) > 1) {
                        foreach ($request->year as $year) {
                            $ty[] = 1;
                        }
                    } else {
                        $ty[] = 1;
                    }
                    $type = implode('|<br>', $ty);
                }
                $vehicleType = implode('|<br>', $request->vehicleType);
                $vehicleCondition = implode('|<br>', $request->vehicleCondition);
                $trailerType = implode('|<br>', $request->trailerType);

                $destination = str_replace(" ", "_", $request->destination);
                $origin = str_replace(" ", "_", $request->origin);
                $newMiles = 'N/A';
                if ($origin && $destination) {
                    $getZip1 = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?origins=' . $origin . '&destinations=' . $destination . '&sensor=false&region=US&key=AIzaSyB3pExTBkEm9-h5Eb-C44qEkVzHAUpgtrw');
                    $response_a = json_decode($getZip1);
                    if ($response_a) {
                        if (isset($response_a->destination_addresses[0]) && isset($response_a->origin_addresses[0])) {
                            if (isset($response_a->rows)) {
                                if (isset($response_a->rows[0]->elements)) {
                                    if (isset($response_a->rows[0]->elements[0]->distance)) {
                                        if (isset($response_a->rows[0]->elements[0]->distance->text)) {
                                            $total_miles = $response_a->rows[0]->elements[0]->distance->text;
                                            $removeCommas = preg_replace('/[,]+/', '', $total_miles);
                                            $milesArr = explode(' ', $removeCommas);
                                            $newMiles = round($milesArr[0] * 0.6214);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $price = new PriceChecker;
                $price->order_id = $request->orderID;
                $price->origin = $request->origin;
                $price->destination = $request->destination;
                $price->vehicle_info = $request->vehicleInfo;
                $price->year = $year;
                $price->model = $model;
                $price->make = $make;
                $price->vin_number = $vinNumber;
                $price->type = $type;
                $price->vehicle_type = $vehicleType;
                $price->vehicle_condition = $vehicleCondition;
                $price->trailer_type = $trailerType;
                $price->miles = $newMiles;
                $price->order_taker_id = Auth::id();
                $price->is_read = 0;
                $price->save();
            } else {
                $price = new PriceChecker;
                $price->order_id = $request->orderID;
                $price->origin = !empty($request->origin) ? $request->origin : '';
                $price->destination = !empty($request->destination) ? $request->destination : '';
                $price->vehicle_info = !empty($request->vehicleInfo) ? $request->vehicleInfo : '';
                $price->year = '';
                $price->model = '';
                $price->make = '';
                $price->vin_number = '';
                $price->type = '';
                $price->vehicle_type = '';
                $price->vehicle_condition = '';
                $price->trailer_type = '';
                $price->miles = '';
                $price->order_taker_id = Auth::id();
                $price->is_read = 0;
                $price->save();
            }
        }

        return response()->json([
            'id' => $price->id,
            'message' => 'The request has been sent successfully!',
            'status' => true,
            'status_code' => 200
        ]);
    }

    public function show($id)
    {
        $price = PriceChecker::with(['priceChecker', 'orderTaker'])
            ->where('order_id', $id)
            ->where('status', 1)
            ->get()->toArray();

        // dd($id, $price);

        if ($price) {
            $data = [];
            foreach ($price as $key => $row) {
                $data[$key]['id'] = $row['id'];
                $data[$key]['order_id'] = $row['order_id'];
                $data[$key]['price_checker'] = $row['price_checker']['slug'] ? $row['price_checker']['slug'] : $row['price_checker']['name'];
                $data[$key]['order_taker'] = $row['order_taker']['slug'] ? $row['order_taker']['slug'] : $row['order_taker']['name'];
                $data[$key]['year'] = explode('|<br>', $row['year']);
                $data[$key]['make'] = explode('|<br>', $row['make']);
                $data[$key]['model'] = explode('|<br>', $row['model']);
                $data[$key]['origin'] = $row['origin'];
                $data[$key]['destination'] = $row['destination'];
                $data[$key]['miles'] = $row['miles'];
                $data[$key]['carrier_price1'] = $row['carrier_price1'];
                $data[$key]['carrier_price2'] = $row['carrier_price2'];
                $data[$key]['carrier_price3'] = $row['carrier_price3'];
                $data[$key]['carrier_price4'] = $row['carrier_price4'];
                $data[$key]['carrier_price5'] = $row['carrier_price5'];
                $data[$key]['carrier_price6'] = $row['carrier_price6'];
                $data[$key]['carrier_price7'] = $row['carrier_price7'];
                $data[$key]['carrier_price8'] = $row['carrier_price8'];
                $data[$key]['carrier_price9'] = $row['carrier_price9'];
                $data[$key]['carrier_price10'] = $row['carrier_price10'];
                $data[$key]['carrier_price11'] = $row['carrier_price11'];
                $data[$key]['carrier_price12'] = $row['carrier_price12'];
                $data[$key]['carrier_price13'] = $row['carrier_price13'];
                $data[$key]['carrier_price14'] = $row['carrier_price14'];
                $data[$key]['carrier_price15'] = $row['carrier_price15'];
                $data[$key]['carrier_price16'] = $row['carrier_price16'];
                $data[$key]['carrier_price17'] = $row['carrier_price17'];
                $data[$key]['carrier_price18'] = $row['carrier_price18'];
                $data[$key]['carrier_price19'] = $row['carrier_price19'];
                $data[$key]['carrier_price20'] = $row['carrier_price20'];
                $data[$key]['pickup_city1'] = $row['pickup_city1'];
                $data[$key]['pickup_city2'] = $row['pickup_city2'];
                $data[$key]['pickup_city3'] = $row['pickup_city3'];
                $data[$key]['pickup_city4'] = $row['pickup_city4'];
                $data[$key]['pickup_city5'] = $row['pickup_city5'];
                $data[$key]['pickup_city6'] = $row['pickup_city6'];
                $data[$key]['pickup_city7'] = $row['pickup_city7'];
                $data[$key]['pickup_city8'] = $row['pickup_city8'];
                $data[$key]['pickup_city9'] = $row['pickup_city9'];
                $data[$key]['pickup_city10'] = $row['pickup_city10'];
                $data[$key]['pickup_city11'] = $row['pickup_city11'];
                $data[$key]['pickup_city12'] = $row['pickup_city12'];
                $data[$key]['pickup_city13'] = $row['pickup_city13'];
                $data[$key]['pickup_city14'] = $row['pickup_city14'];
                $data[$key]['pickup_city15'] = $row['pickup_city15'];
                $data[$key]['pickup_city16'] = $row['pickup_city16'];
                $data[$key]['pickup_city17'] = $row['pickup_city17'];
                $data[$key]['pickup_city18'] = $row['pickup_city18'];
                $data[$key]['pickup_city19'] = $row['pickup_city19'];
                $data[$key]['pickup_city20'] = $row['pickup_city20'];
                $data[$key]['pickup_state1'] = $row['pickup_state1'];
                $data[$key]['pickup_state2'] = $row['pickup_state2'];
                $data[$key]['pickup_state3'] = $row['pickup_state3'];
                $data[$key]['pickup_state4'] = $row['pickup_state4'];
                $data[$key]['pickup_state5'] = $row['pickup_state5'];
                $data[$key]['pickup_state6'] = $row['pickup_state6'];
                $data[$key]['pickup_state7'] = $row['pickup_state7'];
                $data[$key]['pickup_state8'] = $row['pickup_state8'];
                $data[$key]['pickup_state9'] = $row['pickup_state9'];
                $data[$key]['pickup_state10'] = $row['pickup_state10'];
                $data[$key]['pickup_state11'] = $row['pickup_state11'];
                $data[$key]['pickup_state12'] = $row['pickup_state12'];
                $data[$key]['pickup_state13'] = $row['pickup_state13'];
                $data[$key]['pickup_state14'] = $row['pickup_state14'];
                $data[$key]['pickup_state15'] = $row['pickup_state15'];
                $data[$key]['pickup_state16'] = $row['pickup_state16'];
                $data[$key]['pickup_state17'] = $row['pickup_state17'];
                $data[$key]['pickup_state18'] = $row['pickup_state18'];
                $data[$key]['pickup_state19'] = $row['pickup_state19'];
                $data[$key]['pickup_state20'] = $row['pickup_state20'];
                $data[$key]['dropoff_city1'] = $row['dropoff_city1'];
                $data[$key]['dropoff_city2'] = $row['dropoff_city2'];
                $data[$key]['dropoff_city3'] = $row['dropoff_city3'];
                $data[$key]['dropoff_city4'] = $row['dropoff_city4'];
                $data[$key]['dropoff_city5'] = $row['dropoff_city5'];
                $data[$key]['dropoff_city6'] = $row['dropoff_city6'];
                $data[$key]['dropoff_city7'] = $row['dropoff_city7'];
                $data[$key]['dropoff_city8'] = $row['dropoff_city8'];
                $data[$key]['dropoff_city9'] = $row['dropoff_city9'];
                $data[$key]['dropoff_city10'] = $row['dropoff_city10'];
                $data[$key]['dropoff_city11'] = $row['dropoff_city11'];
                $data[$key]['dropoff_city12'] = $row['dropoff_city12'];
                $data[$key]['dropoff_city13'] = $row['dropoff_city13'];
                $data[$key]['dropoff_city14'] = $row['dropoff_city14'];
                $data[$key]['dropoff_city15'] = $row['dropoff_city15'];
                $data[$key]['dropoff_city16'] = $row['dropoff_city16'];
                $data[$key]['dropoff_city17'] = $row['dropoff_city17'];
                $data[$key]['dropoff_city18'] = $row['dropoff_city18'];
                $data[$key]['dropoff_city19'] = $row['dropoff_city19'];
                $data[$key]['dropoff_city20'] = $row['dropoff_city20'];
                $data[$key]['dropoff_state1'] = $row['dropoff_state1'];
                $data[$key]['dropoff_state2'] = $row['dropoff_state2'];
                $data[$key]['dropoff_state3'] = $row['dropoff_state3'];
                $data[$key]['dropoff_state4'] = $row['dropoff_state4'];
                $data[$key]['dropoff_state5'] = $row['dropoff_state5'];
                $data[$key]['dropoff_state6'] = $row['dropoff_state6'];
                $data[$key]['dropoff_state7'] = $row['dropoff_state7'];
                $data[$key]['dropoff_state8'] = $row['dropoff_state8'];
                $data[$key]['dropoff_state9'] = $row['dropoff_state9'];
                $data[$key]['dropoff_state10'] = $row['dropoff_state10'];
                $data[$key]['dropoff_state11'] = $row['dropoff_state11'];
                $data[$key]['dropoff_state12'] = $row['dropoff_state12'];
                $data[$key]['dropoff_state13'] = $row['dropoff_state13'];
                $data[$key]['dropoff_state14'] = $row['dropoff_state14'];
                $data[$key]['dropoff_state15'] = $row['dropoff_state15'];
                $data[$key]['dropoff_state16'] = $row['dropoff_state16'];
                $data[$key]['dropoff_state17'] = $row['dropoff_state17'];
                $data[$key]['dropoff_state18'] = $row['dropoff_state18'];
                $data[$key]['dropoff_state19'] = $row['dropoff_state19'];
                $data[$key]['dropoff_state20'] = $row['dropoff_state20'];
                $data[$key]['date'] = date('M-d-Y h:i:s A', strtotime($row['updated_at']));
            }

            // dd($data);
            $vehicle = [];
            // foreach ($data as $key2 => $value) {
            //     // dd(count($value['year']), count($value['make']), count($value['model']), $value);
            //     if (count($value['year']) > 1 && count($value['make']) > 1 && count($value['model']) > 1) {
            //         foreach ($value['year'] as $key => $year) {
            //             $vehicle[] = $year . ', ' . $data['make'][$key] . ', ' . $data['model'][$key];
            //         }
            //     }
            //     $data[$key2]['vehicles'] = implode(' | ', $vehicle);
            //     $data[$key2]['vehicle_count'] = count($vehicle);
            // }
            foreach ($data as $key2 => $value) {
                $vehicles = []; // Initialize an empty array to store vehicle strings

                // Check if the year, make, and model arrays have values
                if (count($value['year']) > 0 && count($value['make']) > 0 && count($value['model']) > 0) {
                    foreach ($value['year'] as $key => $year) {
                        // Check if year, make, and model are not empty
                        if (!empty($year) && !empty($value['make'][$key]) && !empty($value['model'][$key])) {
                            $vehicles[] = $year . ', ' . $value['make'][$key] . ', ' . $value['model'][$key];
                        }
                    }
                }

                // Store the vehicles string and count in the data array
                $data[$key2]['vehicles'] = implode(' | ', $vehicles);
                $data[$key2]['vehicle_count'] = count($vehicles);
            }
            // echo "<pre>"; print_r($data);exit();
            return view('main.phone_quote.new.new_req_price', compact('data'));
        } else {
            return '<h1 style="text-align:center;">No Price Found!</h1>';
        }
    }

    public function edit(Request $request)
    {
        $user_id = Auth::id();
        $states = zipcodes::select('state')->distinct()->orderBy('state')->pluck('state');
        // $prices = PriceChecker::with('order','order.freight')
        //                   ->where('price_checker_id', null)->where('is_read',0)->orderBy('id','DESC')->get();

        $prices = PriceChecker::with('order', 'order.freight')
            ->where(function ($query) use ($user_id) {
                $query->where('price_checker_id', null)
                    ->orWhere('price_checker_id', $user_id);
            })
            ->where('is_read', 0)
            ->orderBy('id', 'DESC')
            ->get();

        if ($prices->isNotEmpty()) {
            $firstPrice = $prices[0];
            $firstPrice->price_checker_id = Auth::id();
            $firstPrice->save();
        }

        if (count($prices) > 0) {
            $vehicle = [];
            $permission_phone = explode(',', auth()->user()->emp_access_phone);
            $permission_web = explode(',', auth()->user()->emp_access_web);
            $filteredPrices = $prices->where('price_checker_id', $user_id)->filter(function ($price) use ($permission_phone, $permission_web) {
                $temp = true;
                if (isset($price->order->freight->id)) {
                    if (in_array('93', $permission_phone) || in_array('93', $permission_web)) {
                        $temp = true;
                    } else {
                        $temp = false;
                    }
                } else {
                    $temp = true;
                }

                if ($temp) {
                    $vehicle = [];
                    $condition = [];
                    $trailer = [];
                    $type = [];

                    $price->year = explode('|<br>', $price->year);
                    $price->make = explode('|<br>', $price->make);
                    $price->model = explode('|<br>', $price->model);

                    if (count($price->year) > 0 && count($price->make) > 0 && count($price->model) > 0) {
                        foreach ($price->year as $key => $year) {
                            $vehicle[] = $year . ' ' . $price->make[$key] . ' ' . $price->model[$key];
                        }
                    }

                    $price->vehicles = implode(' | ', $vehicle);

                    $cond = explode('|<br>', $price->vehicle_condition);
                    foreach ($cond as $key => $value2) {
                        $condition[] = ($value2 == '1') ? 'Running' : 'Non Running';
                    }
                    $price->condition = implode(' | ', $condition);

                    $trai = explode('|<br>', $price->trailer_type);
                    foreach ($trai as $key => $value2) {
                        $trailer[] = ($value2 == '1') ? 'Open' : 'Enclosed';
                    }
                    $price->trailer = implode(' | ', $trailer);

                    $typ = explode('|<br>', $price->type);
                    foreach ($typ as $key => $value2) {
                        $type[] = ($value2 == 'on') ? 'vin' : 'make';
                    }
                    $price->vin = implode(' | ', $type);

                    return true; // Include the item in the filtered collection
                } else {
                    return false;
                }
            });

            // dd('OKss', $filteredPrices->toArray());
            return response()->json([
                'last_order' => $filteredPrices->last(),
                'data' => $filteredPrices,
                'states' => $states,
                'status' => true,
                'status_code' => 200
            ]);
        } else {
            return response()->json([
                'data' => [],
                'status' => false,
                'status_code' => 403
            ]);
        }
    }

    public function edit2(Request $request)
    {
        $user_id = Auth::id();
        $price = PriceChecker::where('is_read', 0)->where('status', 0)
            ->where(function ($query) use ($user_id) {
                $query->where('price_checker_id', null)
                    ->orWhere('price_checker_id', $user_id);
            })
            ->orderBy('id', 'DESC')->get()->toArray();

        if ($prices->isNotEmpty()) {
            $firstPrice = $prices[0];
            $firstPrice->price_checker_id = Auth::id();
            $firstPrice->save();
        }

        if (count($price) > 0) {
            $vehicle = [];
            foreach ($price as $key2 => $value) {
                if ($value->price_checker_id == $user_id) {
                    $price[$key2]['year'] = explode('|<br>', $value['year']);
                    $price[$key2]['make'] = explode('|<br>', $value['make']);
                    $price[$key2]['model'] = explode('|<br>', $value['model']);
                    if (count($price[$key2]['year']) > 0 && count($price[$key2]['make']) > 0 && count($price[$key2]['model']) > 0) {
                        foreach ($price[$key2]['year'] as $key => $year) {
                            $vehicle[] = $year . ' ' . $price[$key2]['make'][$key] . ' ' . $price[$key2]['model'][$key];
                        }
                    }
                    $price[$key2]['vehicles'] = implode(' | ', $vehicle);
                    $cond = explode('|<br>', $value['vehicle_condition']);
                    foreach ($cond as $key => $value2) {
                        if ($value2 == '1') {
                            $condition[] = 'Running';
                        } else {
                            $condition[] = 'Non Running';
                        }
                    }
                    $price[$key2]['condition'] = implode(' | ', $condition);
                    $trai = explode('|<br>', $value['trailer_type']);
                    foreach ($trai as $key => $value2) {
                        if ($value2 == '1') {
                            $trailer[] = 'Open';
                        } else {
                            $trailer[] = 'Enclosed';
                        }
                    }
                    $price[$key2]['trailer'] = implode(' | ', $trailer);
                    $typ = explode('|<br>', $value['type']);
                    foreach ($typ as $key => $value2) {
                        if ($value2 == 'on') {
                            $type[] = 'vin';
                        } else {
                            $type[] = 'make';
                        }
                    }
                    $price[$key2]['vin'] = implode(' | ', $type);
                }
            }
            // echo "<pre>";
            // print_r($price);
            // exit();

            return response()->json([
                'data' => $price,
                'status' => true,
                'status_code' => 200
            ]);
        } else {
            return response()->json([
                'data' => $price,
                'status' => false,
                'status_code' => 403
            ]);
        }
    }

    public function update(Request $request)
    {
        $price = PriceChecker::where('id', $request->reqID)->where('is_read', 1)->first();
        if ($request->price1) {
            if ($price) {
                $name = '';
                $user = User::find($price->price_checker_id);
                if ($user->id == Auth::id()) {
                    $name = 'You have';
                } else {
                    $name = $user->slug ? $user->slug . ' has' : $user->name . ' has';
                }
                $message = $name . ' already filled the carrier price!';

                return response()->json([
                    'message' => $message,
                    'data' => $price,
                    'status' => false,
                    'status_code' => 400
                ]);
            } else {
                $updatePrice = PriceChecker::find($request->reqID);
                $updatePrice->carrier_price1 = $request->price1 ? json_encode($request->price1) : $updatePrice->carrier_price1;
                $updatePrice->carrier_price2 = $request->price2 ? json_encode($request->price2) : $updatePrice->carrier_price2;
                $updatePrice->carrier_price3 = $request->price3 ? json_encode($request->price3) : $updatePrice->carrier_price3;
                $updatePrice->carrier_price4 = $request->price4 ? json_encode($request->price4) : $updatePrice->carrier_price4;
                $updatePrice->carrier_price5 = $request->price5 ? json_encode($request->price5) : $updatePrice->carrier_price5;
                $updatePrice->carrier_price6 = $request->price6 ? json_encode($request->price6) : $updatePrice->carrier_price6;
                $updatePrice->carrier_price7 = $request->price7 ? json_encode($request->price7) : $updatePrice->carrier_price7;
                $updatePrice->carrier_price8 = $request->price8 ? json_encode($request->price8) : $updatePrice->carrier_price8;
                $updatePrice->carrier_price9 = $request->price9 ? json_encode($request->price9) : $updatePrice->carrier_price9;
                $updatePrice->carrier_price10 = $request->price10 ? json_encode($request->price10) : $updatePrice->carrier_price10;
                $updatePrice->carrier_price11 = $request->price11 ? json_encode($request->price11) : $updatePrice->carrier_price11;
                $updatePrice->carrier_price12 = $request->price12 ? json_encode($request->price12) : $updatePrice->carrier_price12;
                $updatePrice->carrier_price13 = $request->price13 ? json_encode($request->price13) : $updatePrice->carrier_price13;
                $updatePrice->carrier_price14 = $request->price14 ? json_encode($request->price14) : $updatePrice->carrier_price14;
                $updatePrice->carrier_price15 = $request->price15 ? json_encode($request->price15) : $updatePrice->carrier_price15;
                $updatePrice->carrier_price16 = $request->price16 ? json_encode($request->price16) : $updatePrice->carrier_price16;
                $updatePrice->carrier_price17 = $request->price17 ? json_encode($request->price17) : $updatePrice->carrier_price17;
                $updatePrice->carrier_price18 = $request->price18 ? json_encode($request->price18) : $updatePrice->carrier_price18;
                $updatePrice->carrier_price19 = $request->price19 ? json_encode($request->price19) : $updatePrice->carrier_price19;
                $updatePrice->carrier_price20 = $request->price20 ? json_encode($request->price20) : $updatePrice->carrier_price20;
                $updatePrice->pickup_city1 = $request->pickup_city1 ? json_encode($request->pickup_city1) : $updatePrice->pickup_city1;
                $updatePrice->pickup_city2 = $request->pickup_city2 ? json_encode($request->pickup_city2) : $updatePrice->pickup_city2;
                $updatePrice->pickup_city3 = $request->pickup_city3 ? json_encode($request->pickup_city3) : $updatePrice->pickup_city3;
                $updatePrice->pickup_city4 = $request->pickup_city4 ? json_encode($request->pickup_city4) : $updatePrice->pickup_city4;
                $updatePrice->pickup_city5 = $request->pickup_city5 ? json_encode($request->pickup_city5) : $updatePrice->pickup_city5;
                $updatePrice->pickup_city6 = $request->pickup_city6 ? json_encode($request->pickup_city6) : $updatePrice->pickup_city6;
                $updatePrice->pickup_city7 = $request->pickup_city7 ? json_encode($request->pickup_city7) : $updatePrice->pickup_city7;
                $updatePrice->pickup_city8 = $request->pickup_city8 ? json_encode($request->pickup_city8) : $updatePrice->pickup_city8;
                $updatePrice->pickup_city9 = $request->pickup_city9 ? json_encode($request->pickup_city9) : $updatePrice->pickup_city9;
                $updatePrice->pickup_city10 = $request->pickup_city10 ? json_encode($request->pickup_city10) : $updatePrice->pickup_city10;
                $updatePrice->pickup_city11 = $request->pickup_city11 ? json_encode($request->pickup_city11) : $updatePrice->pickup_city11;
                $updatePrice->pickup_city12 = $request->pickup_city12 ? json_encode($request->pickup_city12) : $updatePrice->pickup_city12;
                $updatePrice->pickup_city13 = $request->pickup_city13 ? json_encode($request->pickup_city13) : $updatePrice->pickup_city13;
                $updatePrice->pickup_city14 = $request->pickup_city14 ? json_encode($request->pickup_city14) : $updatePrice->pickup_city14;
                $updatePrice->pickup_city15 = $request->pickup_city15 ? json_encode($request->pickup_city15) : $updatePrice->pickup_city15;
                $updatePrice->pickup_city16 = $request->pickup_city16 ? json_encode($request->pickup_city16) : $updatePrice->pickup_city16;
                $updatePrice->pickup_city17 = $request->pickup_city17 ? json_encode($request->pickup_city17) : $updatePrice->pickup_city17;
                $updatePrice->pickup_city18 = $request->pickup_city18 ? json_encode($request->pickup_city18) : $updatePrice->pickup_city18;
                $updatePrice->pickup_city19 = $request->pickup_city19 ? json_encode($request->pickup_city19) : $updatePrice->pickup_city19;
                $updatePrice->pickup_city20 = $request->pickup_city20 ? json_encode($request->pickup_city20) : $updatePrice->pickup_city20;
                $updatePrice->pickup_state1 = $request->pickup_state1 ? json_encode($request->pickup_state1) : $updatePrice->pickup_state1;
                $updatePrice->pickup_state2 = $request->pickup_state2 ? json_encode($request->pickup_state2) : $updatePrice->pickup_state2;
                $updatePrice->pickup_state3 = $request->pickup_state3 ? json_encode($request->pickup_state3) : $updatePrice->pickup_state3;
                $updatePrice->pickup_state4 = $request->pickup_state4 ? json_encode($request->pickup_state4) : $updatePrice->pickup_state4;
                $updatePrice->pickup_state5 = $request->pickup_state5 ? json_encode($request->pickup_state5) : $updatePrice->pickup_state5;
                $updatePrice->pickup_state6 = $request->pickup_state6 ? json_encode($request->pickup_state6) : $updatePrice->pickup_state6;
                $updatePrice->pickup_state7 = $request->pickup_state7 ? json_encode($request->pickup_state7) : $updatePrice->pickup_state7;
                $updatePrice->pickup_state8 = $request->pickup_state8 ? json_encode($request->pickup_state8) : $updatePrice->pickup_state8;
                $updatePrice->pickup_state9 = $request->pickup_state9 ? json_encode($request->pickup_state9) : $updatePrice->pickup_state9;
                $updatePrice->pickup_state10 = $request->pickup_state10 ? json_encode($request->pickup_state10) : $updatePrice->pickup_state10;
                $updatePrice->pickup_state11 = $request->pickup_state11 ? json_encode($request->pickup_state11) : $updatePrice->pickup_state11;
                $updatePrice->pickup_state12 = $request->pickup_state12 ? json_encode($request->pickup_state12) : $updatePrice->pickup_state12;
                $updatePrice->pickup_state13 = $request->pickup_state13 ? json_encode($request->pickup_state13) : $updatePrice->pickup_state13;
                $updatePrice->pickup_state14 = $request->pickup_state14 ? json_encode($request->pickup_state14) : $updatePrice->pickup_state14;
                $updatePrice->pickup_state15 = $request->pickup_state15 ? json_encode($request->pickup_state15) : $updatePrice->pickup_state15;
                $updatePrice->pickup_state16 = $request->pickup_state16 ? json_encode($request->pickup_state16) : $updatePrice->pickup_state16;
                $updatePrice->pickup_state17 = $request->pickup_state17 ? json_encode($request->pickup_state17) : $updatePrice->pickup_state17;
                $updatePrice->pickup_state18 = $request->pickup_state18 ? json_encode($request->pickup_state18) : $updatePrice->pickup_state18;
                $updatePrice->pickup_state19 = $request->pickup_state19 ? json_encode($request->pickup_state19) : $updatePrice->pickup_state19;
                $updatePrice->pickup_state20 = $request->pickup_state20 ? json_encode($request->pickup_state20) : $updatePrice->pickup_state20;
                $updatePrice->dropoff_city1 = $request->dropoff_city1 ? json_encode($request->dropoff_city1) : $updatePrice->dropoff_city1;
                $updatePrice->dropoff_city2 = $request->dropoff_city2 ? json_encode($request->dropoff_city2) : $updatePrice->dropoff_city2;
                $updatePrice->dropoff_city3 = $request->dropoff_city3 ? json_encode($request->dropoff_city3) : $updatePrice->dropoff_city3;
                $updatePrice->dropoff_city4 = $request->dropoff_city4 ? json_encode($request->dropoff_city4) : $updatePrice->dropoff_city4;
                $updatePrice->dropoff_city5 = $request->dropoff_city5 ? json_encode($request->dropoff_city5) : $updatePrice->dropoff_city5;
                $updatePrice->dropoff_city6 = $request->dropoff_city6 ? json_encode($request->dropoff_city6) : $updatePrice->dropoff_city6;
                $updatePrice->dropoff_city7 = $request->dropoff_city7 ? json_encode($request->dropoff_city7) : $updatePrice->dropoff_city7;
                $updatePrice->dropoff_city8 = $request->dropoff_city8 ? json_encode($request->dropoff_city8) : $updatePrice->dropoff_city8;
                $updatePrice->dropoff_city9 = $request->dropoff_city9 ? json_encode($request->dropoff_city9) : $updatePrice->dropoff_city9;
                $updatePrice->dropoff_city10 = $request->dropoff_city10 ? json_encode($request->dropoff_city10) : $updatePrice->dropoff_city10;
                $updatePrice->dropoff_city11 = $request->dropoff_city11 ? json_encode($request->dropoff_city11) : $updatePrice->dropoff_city11;
                $updatePrice->dropoff_city12 = $request->dropoff_city12 ? json_encode($request->dropoff_city12) : $updatePrice->dropoff_city12;
                $updatePrice->dropoff_city13 = $request->dropoff_city13 ? json_encode($request->dropoff_city13) : $updatePrice->dropoff_city13;
                $updatePrice->dropoff_city14 = $request->dropoff_city14 ? json_encode($request->dropoff_city14) : $updatePrice->dropoff_city14;
                $updatePrice->dropoff_city15 = $request->dropoff_city15 ? json_encode($request->dropoff_city15) : $updatePrice->dropoff_city15;
                $updatePrice->dropoff_city16 = $request->dropoff_city16 ? json_encode($request->dropoff_city16) : $updatePrice->dropoff_city16;
                $updatePrice->dropoff_city17 = $request->dropoff_city17 ? json_encode($request->dropoff_city17) : $updatePrice->dropoff_city17;
                $updatePrice->dropoff_city18 = $request->dropoff_city18 ? json_encode($request->dropoff_city18) : $updatePrice->dropoff_city18;
                $updatePrice->dropoff_city19 = $request->dropoff_city19 ? json_encode($request->dropoff_city19) : $updatePrice->dropoff_city19;
                $updatePrice->dropoff_city20 = $request->dropoff_city20 ? json_encode($request->dropoff_city20) : $updatePrice->dropoff_city20;
                $updatePrice->dropoff_state1 = $request->dropoff_state1 ? json_encode($request->dropoff_state1) : $updatePrice->dropoff_state1;
                $updatePrice->dropoff_state2 = $request->dropoff_state2 ? json_encode($request->dropoff_state2) : $updatePrice->dropoff_state2;
                $updatePrice->dropoff_state3 = $request->dropoff_state3 ? json_encode($request->dropoff_state3) : $updatePrice->dropoff_state3;
                $updatePrice->dropoff_state4 = $request->dropoff_state4 ? json_encode($request->dropoff_state4) : $updatePrice->dropoff_state4;
                $updatePrice->dropoff_state5 = $request->dropoff_state5 ? json_encode($request->dropoff_state5) : $updatePrice->dropoff_state5;
                $updatePrice->dropoff_state6 = $request->dropoff_state6 ? json_encode($request->dropoff_state6) : $updatePrice->dropoff_state6;
                $updatePrice->dropoff_state7 = $request->dropoff_state7 ? json_encode($request->dropoff_state7) : $updatePrice->dropoff_state7;
                $updatePrice->dropoff_state8 = $request->dropoff_state8 ? json_encode($request->dropoff_state8) : $updatePrice->dropoff_state8;
                $updatePrice->dropoff_state9 = $request->dropoff_state9 ? json_encode($request->dropoff_state9) : $updatePrice->dropoff_state9;
                $updatePrice->dropoff_state10 = $request->dropoff_state10 ? json_encode($request->dropoff_state10) : $updatePrice->dropoff_state10;
                $updatePrice->dropoff_state11 = $request->dropoff_state11 ? json_encode($request->dropoff_state11) : $updatePrice->dropoff_state11;
                $updatePrice->dropoff_state12 = $request->dropoff_state12 ? json_encode($request->dropoff_state12) : $updatePrice->dropoff_state12;
                $updatePrice->dropoff_state13 = $request->dropoff_state13 ? json_encode($request->dropoff_state13) : $updatePrice->dropoff_state13;
                $updatePrice->dropoff_state14 = $request->dropoff_state14 ? json_encode($request->dropoff_state14) : $updatePrice->dropoff_state14;
                $updatePrice->dropoff_state15 = $request->dropoff_state15 ? json_encode($request->dropoff_state15) : $updatePrice->dropoff_state15;
                $updatePrice->dropoff_state16 = $request->dropoff_state16 ? json_encode($request->dropoff_state16) : $updatePrice->dropoff_state16;
                $updatePrice->dropoff_state17 = $request->dropoff_state17 ? json_encode($request->dropoff_state17) : $updatePrice->dropoff_state17;
                $updatePrice->dropoff_state18 = $request->dropoff_state18 ? json_encode($request->dropoff_state18) : $updatePrice->dropoff_state18;
                $updatePrice->dropoff_state19 = $request->dropoff_state19 ? json_encode($request->dropoff_state19) : $updatePrice->dropoff_state19;
                $updatePrice->dropoff_state20 = $request->dropoff_state20 ? json_encode($request->dropoff_state20) : $updatePrice->dropoff_state20;
                $updatePrice->is_read = 1;
                $updatePrice->save();


                return response()->json([
                    'message' => "You successfully add price for carrier!",
                    'data' => $updatePrice,
                    'status' => true,
                    'status_code' => 200
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Atleast one Carrier Price is required!',
                'status' => false,
                'status_code' => 404
            ]);
        }
    }



    public function update2(Request $request)
    {
        $updatePrice = PriceChecker::find($request->reqID);
        $updatePrice->status = 1;
        $updatePrice->carrier_price1 = $request->price1 ? json_encode($request->price1) : $updatePrice->carrier_price1;
        $updatePrice->carrier_price2 = $request->price2 ? json_encode($request->price2) : $updatePrice->carrier_price2;
        $updatePrice->carrier_price3 = $request->price3 ? json_encode($request->price3) : $updatePrice->carrier_price3;
        $updatePrice->carrier_price4 = $request->price4 ? json_encode($request->price4) : $updatePrice->carrier_price4;
        $updatePrice->carrier_price5 = $request->price5 ? json_encode($request->price5) : $updatePrice->carrier_price5;
        $updatePrice->carrier_price6 = $request->price6 ? json_encode($request->price6) : $updatePrice->carrier_price6;
        $updatePrice->carrier_price7 = $request->price7 ? json_encode($request->price7) : $updatePrice->carrier_price7;
        $updatePrice->carrier_price8 = $request->price8 ? json_encode($request->price8) : $updatePrice->carrier_price8;
        $updatePrice->carrier_price9 = $request->price9 ? json_encode($request->price9) : $updatePrice->carrier_price9;
        $updatePrice->carrier_price10 = $request->price10 ? json_encode($request->price10) : $updatePrice->carrier_price10;
        $updatePrice->carrier_price11 = $request->price11 ? json_encode($request->price11) : $updatePrice->carrier_price11;
        $updatePrice->carrier_price12 = $request->price12 ? json_encode($request->price12) : $updatePrice->carrier_price12;
        $updatePrice->carrier_price13 = $request->price13 ? json_encode($request->price13) : $updatePrice->carrier_price13;
        $updatePrice->carrier_price14 = $request->price14 ? json_encode($request->price14) : $updatePrice->carrier_price14;
        $updatePrice->carrier_price15 = $request->price15 ? json_encode($request->price15) : $updatePrice->carrier_price15;
        $updatePrice->carrier_price16 = $request->price16 ? json_encode($request->price16) : $updatePrice->carrier_price16;
        $updatePrice->carrier_price17 = $request->price17 ? json_encode($request->price17) : $updatePrice->carrier_price17;
        $updatePrice->carrier_price18 = $request->price18 ? json_encode($request->price18) : $updatePrice->carrier_price18;
        $updatePrice->carrier_price19 = $request->price19 ? json_encode($request->price19) : $updatePrice->carrier_price19;
        $updatePrice->carrier_price20 = $request->price20 ? json_encode($request->price20) : $updatePrice->carrier_price20;
        $updatePrice->pickup_city1 = $request->pickup_city1 ? json_encode($request->pickup_city1) : $updatePrice->pickup_city1;
        $updatePrice->pickup_city2 = $request->pickup_city2 ? json_encode($request->pickup_city2) : $updatePrice->pickup_city2;
        $updatePrice->pickup_city3 = $request->pickup_city3 ? json_encode($request->pickup_city3) : $updatePrice->pickup_city3;
        $updatePrice->pickup_city4 = $request->pickup_city4 ? json_encode($request->pickup_city4) : $updatePrice->pickup_city4;
        $updatePrice->pickup_city5 = $request->pickup_city5 ? json_encode($request->pickup_city5) : $updatePrice->pickup_city5;
        $updatePrice->pickup_city6 = $request->pickup_city6 ? json_encode($request->pickup_city6) : $updatePrice->pickup_city6;
        $updatePrice->pickup_city7 = $request->pickup_city7 ? json_encode($request->pickup_city7) : $updatePrice->pickup_city7;
        $updatePrice->pickup_city8 = $request->pickup_city8 ? json_encode($request->pickup_city8) : $updatePrice->pickup_city8;
        $updatePrice->pickup_city9 = $request->pickup_city9 ? json_encode($request->pickup_city9) : $updatePrice->pickup_city9;
        $updatePrice->pickup_city10 = $request->pickup_city10 ? json_encode($request->pickup_city10) : $updatePrice->pickup_city10;
        $updatePrice->pickup_city11 = $request->pickup_city11 ? json_encode($request->pickup_city11) : $updatePrice->pickup_city11;
        $updatePrice->pickup_city12 = $request->pickup_city12 ? json_encode($request->pickup_city12) : $updatePrice->pickup_city12;
        $updatePrice->pickup_city13 = $request->pickup_city13 ? json_encode($request->pickup_city13) : $updatePrice->pickup_city13;
        $updatePrice->pickup_city14 = $request->pickup_city14 ? json_encode($request->pickup_city14) : $updatePrice->pickup_city14;
        $updatePrice->pickup_city15 = $request->pickup_city15 ? json_encode($request->pickup_city15) : $updatePrice->pickup_city15;
        $updatePrice->pickup_city16 = $request->pickup_city16 ? json_encode($request->pickup_city16) : $updatePrice->pickup_city16;
        $updatePrice->pickup_city17 = $request->pickup_city17 ? json_encode($request->pickup_city17) : $updatePrice->pickup_city17;
        $updatePrice->pickup_city18 = $request->pickup_city18 ? json_encode($request->pickup_city18) : $updatePrice->pickup_city18;
        $updatePrice->pickup_city19 = $request->pickup_city19 ? json_encode($request->pickup_city19) : $updatePrice->pickup_city19;
        $updatePrice->pickup_city20 = $request->pickup_city20 ? json_encode($request->pickup_city20) : $updatePrice->pickup_city20;
        $updatePrice->pickup_state1 = $request->pickup_state1 ? json_encode($request->pickup_state1) : $updatePrice->pickup_state1;
        $updatePrice->pickup_state2 = $request->pickup_state2 ? json_encode($request->pickup_state2) : $updatePrice->pickup_state2;
        $updatePrice->pickup_state3 = $request->pickup_state3 ? json_encode($request->pickup_state3) : $updatePrice->pickup_state3;
        $updatePrice->pickup_state4 = $request->pickup_state4 ? json_encode($request->pickup_state4) : $updatePrice->pickup_state4;
        $updatePrice->pickup_state5 = $request->pickup_state5 ? json_encode($request->pickup_state5) : $updatePrice->pickup_state5;
        $updatePrice->pickup_state6 = $request->pickup_state6 ? json_encode($request->pickup_state6) : $updatePrice->pickup_state6;
        $updatePrice->pickup_state7 = $request->pickup_state7 ? json_encode($request->pickup_state7) : $updatePrice->pickup_state7;
        $updatePrice->pickup_state8 = $request->pickup_state8 ? json_encode($request->pickup_state8) : $updatePrice->pickup_state8;
        $updatePrice->pickup_state9 = $request->pickup_state9 ? json_encode($request->pickup_state9) : $updatePrice->pickup_state9;
        $updatePrice->pickup_state10 = $request->pickup_state10 ? json_encode($request->pickup_state10) : $updatePrice->pickup_state10;
        $updatePrice->pickup_state11 = $request->pickup_state11 ? json_encode($request->pickup_state11) : $updatePrice->pickup_state11;
        $updatePrice->pickup_state12 = $request->pickup_state12 ? json_encode($request->pickup_state12) : $updatePrice->pickup_state12;
        $updatePrice->pickup_state13 = $request->pickup_state13 ? json_encode($request->pickup_state13) : $updatePrice->pickup_state13;
        $updatePrice->pickup_state14 = $request->pickup_state14 ? json_encode($request->pickup_state14) : $updatePrice->pickup_state14;
        $updatePrice->pickup_state15 = $request->pickup_state15 ? json_encode($request->pickup_state15) : $updatePrice->pickup_state15;
        $updatePrice->pickup_state16 = $request->pickup_state16 ? json_encode($request->pickup_state16) : $updatePrice->pickup_state16;
        $updatePrice->pickup_state17 = $request->pickup_state17 ? json_encode($request->pickup_state17) : $updatePrice->pickup_state17;
        $updatePrice->pickup_state18 = $request->pickup_state18 ? json_encode($request->pickup_state18) : $updatePrice->pickup_state18;
        $updatePrice->pickup_state19 = $request->pickup_state19 ? json_encode($request->pickup_state19) : $updatePrice->pickup_state19;
        $updatePrice->pickup_state20 = $request->pickup_state20 ? json_encode($request->pickup_state20) : $updatePrice->pickup_state20;
        $updatePrice->dropoff_city1 = $request->dropoff_city1 ? json_encode($request->dropoff_city1) : $updatePrice->dropoff_city1;
        $updatePrice->dropoff_city2 = $request->dropoff_city2 ? json_encode($request->dropoff_city2) : $updatePrice->dropoff_city2;
        $updatePrice->dropoff_city3 = $request->dropoff_city3 ? json_encode($request->dropoff_city3) : $updatePrice->dropoff_city3;
        $updatePrice->dropoff_city4 = $request->dropoff_city4 ? json_encode($request->dropoff_city4) : $updatePrice->dropoff_city4;
        $updatePrice->dropoff_city5 = $request->dropoff_city5 ? json_encode($request->dropoff_city5) : $updatePrice->dropoff_city5;
        $updatePrice->dropoff_city6 = $request->dropoff_city6 ? json_encode($request->dropoff_city6) : $updatePrice->dropoff_city6;
        $updatePrice->dropoff_city7 = $request->dropoff_city7 ? json_encode($request->dropoff_city7) : $updatePrice->dropoff_city7;
        $updatePrice->dropoff_city8 = $request->dropoff_city8 ? json_encode($request->dropoff_city8) : $updatePrice->dropoff_city8;
        $updatePrice->dropoff_city9 = $request->dropoff_city9 ? json_encode($request->dropoff_city9) : $updatePrice->dropoff_city9;
        $updatePrice->dropoff_city10 = $request->dropoff_city10 ? json_encode($request->dropoff_city10) : $updatePrice->dropoff_city10;
        $updatePrice->dropoff_city11 = $request->dropoff_city11 ? json_encode($request->dropoff_city11) : $updatePrice->dropoff_city11;
        $updatePrice->dropoff_city12 = $request->dropoff_city12 ? json_encode($request->dropoff_city12) : $updatePrice->dropoff_city12;
        $updatePrice->dropoff_city13 = $request->dropoff_city13 ? json_encode($request->dropoff_city13) : $updatePrice->dropoff_city13;
        $updatePrice->dropoff_city14 = $request->dropoff_city14 ? json_encode($request->dropoff_city14) : $updatePrice->dropoff_city14;
        $updatePrice->dropoff_city15 = $request->dropoff_city15 ? json_encode($request->dropoff_city15) : $updatePrice->dropoff_city15;
        $updatePrice->dropoff_city16 = $request->dropoff_city16 ? json_encode($request->dropoff_city16) : $updatePrice->dropoff_city16;
        $updatePrice->dropoff_city17 = $request->dropoff_city17 ? json_encode($request->dropoff_city17) : $updatePrice->dropoff_city17;
        $updatePrice->dropoff_city18 = $request->dropoff_city18 ? json_encode($request->dropoff_city18) : $updatePrice->dropoff_city18;
        $updatePrice->dropoff_city19 = $request->dropoff_city19 ? json_encode($request->dropoff_city19) : $updatePrice->dropoff_city19;
        $updatePrice->dropoff_city20 = $request->dropoff_city20 ? json_encode($request->dropoff_city20) : $updatePrice->dropoff_city20;
        $updatePrice->dropoff_state1 = $request->dropoff_state1 ? json_encode($request->dropoff_state1) : $updatePrice->dropoff_state1;
        $updatePrice->dropoff_state2 = $request->dropoff_state2 ? json_encode($request->dropoff_state2) : $updatePrice->dropoff_state2;
        $updatePrice->dropoff_state3 = $request->dropoff_state3 ? json_encode($request->dropoff_state3) : $updatePrice->dropoff_state3;
        $updatePrice->dropoff_state4 = $request->dropoff_state4 ? json_encode($request->dropoff_state4) : $updatePrice->dropoff_state4;
        $updatePrice->dropoff_state5 = $request->dropoff_state5 ? json_encode($request->dropoff_state5) : $updatePrice->dropoff_state5;
        $updatePrice->dropoff_state6 = $request->dropoff_state6 ? json_encode($request->dropoff_state6) : $updatePrice->dropoff_state6;
        $updatePrice->dropoff_state7 = $request->dropoff_state7 ? json_encode($request->dropoff_state7) : $updatePrice->dropoff_state7;
        $updatePrice->dropoff_state8 = $request->dropoff_state8 ? json_encode($request->dropoff_state8) : $updatePrice->dropoff_state8;
        $updatePrice->dropoff_state9 = $request->dropoff_state9 ? json_encode($request->dropoff_state9) : $updatePrice->dropoff_state9;
        $updatePrice->dropoff_state10 = $request->dropoff_state10 ? json_encode($request->dropoff_state10) : $updatePrice->dropoff_state10;
        $updatePrice->dropoff_state11 = $request->dropoff_state11 ? json_encode($request->dropoff_state11) : $updatePrice->dropoff_state11;
        $updatePrice->dropoff_state12 = $request->dropoff_state12 ? json_encode($request->dropoff_state12) : $updatePrice->dropoff_state12;
        $updatePrice->dropoff_state13 = $request->dropoff_state13 ? json_encode($request->dropoff_state13) : $updatePrice->dropoff_state13;
        $updatePrice->dropoff_state14 = $request->dropoff_state14 ? json_encode($request->dropoff_state14) : $updatePrice->dropoff_state14;
        $updatePrice->dropoff_state15 = $request->dropoff_state15 ? json_encode($request->dropoff_state15) : $updatePrice->dropoff_state15;
        $updatePrice->dropoff_state16 = $request->dropoff_state16 ? json_encode($request->dropoff_state16) : $updatePrice->dropoff_state16;
        $updatePrice->dropoff_state17 = $request->dropoff_state17 ? json_encode($request->dropoff_state17) : $updatePrice->dropoff_state17;
        $updatePrice->dropoff_state18 = $request->dropoff_state18 ? json_encode($request->dropoff_state18) : $updatePrice->dropoff_state18;
        $updatePrice->dropoff_state19 = $request->dropoff_state19 ? json_encode($request->dropoff_state19) : $updatePrice->dropoff_state19;
        $updatePrice->dropoff_state20 = $request->dropoff_state20 ? json_encode($request->dropoff_state20) : $updatePrice->dropoff_state20;

        $updatePrice->price_checker_id = Auth::id();
        $updatePrice->is_read = 0;
        $updatePrice->save();
        return "OK";
    }

    public function store(Request $request)
    {
        $price = PriceChecker::where('order_id', $request->id)->where('status', 1)->first();
        // if(count($price)>0)
        // {
        //     // echo "<pre>";
        //     // print_r($price);
        //     // exit();

        //     return response()->json([
        //         'data'=>$price, 
        //         'status'=>true,
        //         'status_code'=>200
        //     ]);
        // }
        // else{
        //     return response()->json([
        //         'data'=>$price, 
        //         'status'=>false,
        //         'status_code'=>403
        //     ]);
        // }
        return view('main.phone_quote.prices.price', compact('price'));
    }
}
