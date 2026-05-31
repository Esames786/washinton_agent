<?php

namespace App\Http\Controllers\phone_quote;

use App\AutoOrder;
use App\count_day;
use App\creditcard;
use App\DailyQoute;
use App\Http\Controllers\Controller;
use App\InstantQuote;
use App\Mail\HelloTransportInstantQuoteMail;
use App\order_freight;
use App\orderpayment;
use App\OrderTakerQouteAccess;
use App\report;
use App\Services\CentralGateway\GatewayClient;
use App\singlereport;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AutohaulQuoteController extends Controller
{
    public function store(Request $request)
    {
        // ── Credential check ─────────────────────────────────────────────────
        if (
            $request->input('platform') !== config('gateway.autohaul.platform') ||
            $request->input('api_key')  !== config('gateway.autohaul.api_key')  ||
            $request->input('password') !== config('gateway.autohaul.secret')
        ) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        // ── Blocked IPs ──────────────────────────────────────────────────────
        $blockedIps = ['185.150.191.208'];
        $requestIp  = $request->ip();
        $payloadIp  = $request->input('ip', '');

        if (in_array($requestIp, $blockedIps) || in_array($payloadIp, $blockedIps)) {
            Log::warning('Blocked IP attempted autohaul-quote', [
                'request_ip' => $requestIp,
                'payload_ip' => $payloadIp,
            ]);
            return response()->json(['message' => 'Forbidden'], 403);
        }

        try {
            // ── Order taker assignment ────────────────────────────────────────
            $user_iddd = null;

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

            if (isset($user->user->id)) {
                if ($user->user->order_taker_quote == 2) {
                    $m = OrderTakerQouteAccess::where('ot_ids', $user->user->id)->first();
                    if (isset($m->id)) {
                        $daily = DailyQoute::where('user_id', $user->user->id)->whereDate('date', date('Y-m-d'))->first();
                        if (isset($daily->id) && $daily->total_qoute > 0) {
                            $daily->total_qoute = $daily->total_qoute - 1;
                            $daily->save();
                        }
                    }
                }
            } else {
                $user = User::with('userRole')
                    ->where('deleted', 0)
                    ->whereHas('userRole', function ($q) {
                        $q->where('name', 'Order Taker')->orWhere('name', 'Seller Agent');
                    })
                    ->inRandomOrder()
                    ->first();
            }

            $last = AutoOrder::where('paneltype', 4)->orderBy('id', 'DESC')->first();
            $last_user_id = $last ? $last->order_taker_id : null;

            $eligibleUsers = User::with('userRole', 'user_setting')
                ->where('role', 2)
                ->where('deleted', 0)
                ->whereHas('user_setting', function ($q) {
                    $q->where('penal_type', 2);
                })
                ->orderBy('id')
                ->get();

            $nextUser = null;
            if ($last_user_id) {
                $nextUser = $eligibleUsers->first(function ($u) use ($last_user_id) {
                    return $u->id > $last_user_id;
                });
            }

            if (!$nextUser) {
                $nextUser = $eligibleUsers->first();
            }

            if ($nextUser) {
                $user_iddd = $nextUser->id;
            }

            // ── Create AutoOrder (payment placeholder = 0, back-filled after pricing) ─
            $order = AutoOrder::orderBy('id', 'DESC')->first();
            $data = new AutoOrder;
            $data->id               = $order->id + 1;
            $data->order_taker_id   = $user_iddd;
            $data->oname            = $request['oname'];
            $data->oemail           = $request['oemail'];
            $data->ophone           = $request['ophone'];
            $data->ymk              = $request['ymk'];
            $data->year             = $request['year'];
            $data->type             = $request['type'] ?? null;
            $data->vehicle_opt      = $request['vehicle_opt'] ?? null;
            $data->model            = $request['model'];
            $data->make             = $request['make'];
            $data->condition        = $request['condition'];
            $data->originzsc        = $request['originzsc'];
            $data->originzip        = $request['originzip'];
            $data->originstate      = $request['originstate'];
            $data->origincity       = $request['origincity'];
            $data->destinationzsc   = $request['destinationzsc'];
            $data->destinationzip   = $request['destinationzip'];
            $data->destinationstate = $request['destinationstate'];
            $data->destinationcity  = $request['destinationcity'];
            $data->add_info         = $request['add_info'] ?? null;
            $data->transport        = $request['transport'] ?? null;
            $data->shippingdate     = $request['shippingdate'] ?? null;
            $data->car_type         = $request['car_type'] ?? null;
            $data->paneltype        = 4;
            $data->cname            = $request['cname'] ?? null;
            $data->cemail           = $request['cemail'] ?? null;
            $data->main_ph          = $request['main_ph'] ?? null;
            $data->length_ft        = $request['length_ft'] ?? null;
            $data->length_in        = $request['length_in'] ?? null;
            $data->width_ft         = $request['width_ft'] ?? null;
            $data->width_in         = $request['width_in'] ?? null;
            $data->height_ft        = $request['height_ft'] ?? null;
            $data->height_in        = $request['height_in'] ?? null;
            $data->weight           = $request['weight'] ?? null;
            $data->load_method      = $request['load_method'] ?? null;
            $data->unload_method    = $request['unload_method'] ?? null;
            $data->ip_address       = $request['ip'] ?? null;
            $data->ip_details       = $request['ip_details'] ?? null;
            $data->ipcity           = $request['ipcity'] ?? null;
            $data->ipregion         = $request['ipregion'] ?? null;
            $data->ipcountry        = $request['ipcountry'] ?? null;
            $data->iploc            = $request['iploc'] ?? null;
            $data->ippostal         = $request['ippostal'] ?? null;
            $data->image            = $request['image'] ?? null;
            $data->available_at_auction = $request['available_at_auction'] ?? null;
            $data->modification     = $request['modification'] ?? null;
            $data->modify_info      = $request['modify_info'] ?? null;
            $data->link             = $request['link'] ?? null;
            $data->rv_type          = $request['rv_type'] ?? null;
            $data->boat_on_trailer  = $request['boat_on_trailer'] ?? null;
            $data->pstatus          = 0;
            $data->source           = 'HelloTransport';
            $data->roro             = $request['roro'] ?? null;
            $data->heavy_type       = $request['heavy_type'] ?? null;
            $data->category         = $request['category'] ?? null;
            $data->subcategory      = $request['subcategory'] ?? null;
            $data->payment          = 0;
            $data->request_hauling  = 1;
            $data->save();

            // ── Companion records ─────────────────────────────────────────────
            $op = new orderpayment;
            $op->orderId = $data->id;
            $op->save();

            $cc = new creditcard;
            $cc->orderId = $data->id;
            $cc->save();

            $rpt = new report;
            $rpt->userId  = 1;
            $rpt->orderId = $data->id;
            $rpt->pstatus = 0;
            $rpt->save();

            $sr = new singlereport;
            $sr->userId  = 1;
            $sr->orderId = $data->id;
            $sr->pstatus = 0;
            $sr->save();

            $of = new order_freight;
            $of->order_id              = $data->id;
            $of->frieght_class         = $request['frieght_class'] ?? null;
            $of->equipment_type        = $request['equipment_type'] ?? null;
            $of->trailer_specification = $request['trailer_specification'] ?? null;
            $of->ex_pickup_date        = $request['ex_pickup_date'] ?? null;
            $of->ex_pickup_time        = $request['ex_pickup_time'] ?? null;
            $of->ex_delivery_date      = $request['ex_delivery_date'] ?? null;
            $of->ex_delivery_time      = $request['ex_delivery_time'] ?? null;
            $of->commodity_detail      = $request['commodity_detail'] ?? null;
            $of->commodity_unit        = $request['commodity_unit'] ?? null;
            $of->pick_up_services      = $request['pick_up_services'] ?? null;
            $of->deliver_services      = $request['deliver_services'] ?? null;
            $of->shipment_prefences    = $request['category'] ?? null;
            $of->stackable             = $request['stackable'] ?? null;
            $of->hazardous             = $request['hazardous'] ?? null;
            $of->handling_unit         = $request['handling_unit'] ?? null;
            $of->protect_from_freezing = $request['protect_from_freezing'] ?? null;
            $of->sort_segregate        = $request['sort_segregate'] ?? null;
            $of->blind_shipment        = $request['blind_shipment'] ?? null;
            $of->save();

            // ── Get pricing from central-gateway ─────────────────────────────
            $gateway = new GatewayClient();
            $pricingResult = $gateway->quote([
                'platform_code' => config('gateway.autohaul.platform'),
                'limit'         => 1,
                'stops'         => [
                    [
                        'stopNumber' => 1,
                        'zipCode'    => $request['originzip'],
                        'state'      => $request['originstate'],
                        'city'       => $request['origincity'],
                    ],
                    [
                        'stopNumber' => 2,
                        'zipCode'    => $request['destinationzip'],
                        'state'      => $request['destinationstate'],
                        'city'       => $request['destinationcity'],
                    ],
                ],
                'vehicles' => [
                    [
                        'year'  => (int) $request['year'],
                        'make'  => $request['make'],
                        'model' => $request['model'],
                        'type'  => 'Car',
                    ],
                ],
                'referenceId' => 'HT-AUTOHAUL-' . uniqid(),
            ]);

            $pricingBody = $pricingResult['body'] ?? [];

            // ── Save InstantQuote ─────────────────────────────────────────────
            $q = new InstantQuote();
            $q->origin_location      = $request['originzsc'];
            $q->destination_location = $request['destinationzsc'];
            $q->type                 = $request['car_type'] ?? null;
            $q->year_make_model      = $request['ymk'];
            $q->customer_name        = $request['oname'];
            $q->customer_phone       = $request['ophone'];
            $q->customer_email       = $request['oemail'];
            $q->platform_code        = 'hello-autohaul';
            $q->is_autohaul          = 1;
            $q->pricing_payload      = $pricingBody;
            $q->driver_low_open      = data_get($pricingBody, 'primary.modes.open.driver_price.low');
            $q->driver_mid_open      = data_get($pricingBody, 'primary.modes.open.driver_price.mid');
            $q->driver_high_open     = data_get($pricingBody, 'primary.modes.open.driver_price.high');
            $q->offer_open           = data_get($pricingBody, 'primary.modes.open.offer_prices.0.value');
            $q->commission_open      = data_get($pricingBody, 'primary.modes.open.offer_prices.0.commission');
            $q->cache_hit_open       = (bool) data_get($pricingBody, 'primary.modes.open.cache_hit', false);
            $q->driver_low_enclosed  = data_get($pricingBody, 'primary.modes.enclosed.driver_price.low');
            $q->driver_mid_enclosed  = data_get($pricingBody, 'primary.modes.enclosed.driver_price.mid');
            $q->driver_high_enclosed = data_get($pricingBody, 'primary.modes.enclosed.driver_price.high');
            $q->offer_enclosed       = data_get($pricingBody, 'primary.modes.enclosed.offer_prices.0.value');
            $q->commission_enclosed  = data_get($pricingBody, 'primary.modes.enclosed.offer_prices.0.commission');
            $q->cache_hit_enclosed   = (bool) data_get($pricingBody, 'primary.modes.enclosed.cache_hit', false);
            $q->order_id             = $data->id;
            $q->order_taker_id       = $user_iddd;
            $q->save();

            // Set display-only fields for email (not DB columns — set after save)
            $q->condition = $request['condition'] ?? null;
            $q->transport = $request['transport'] ?? null;

            // ── Back-fill AutoOrder payment with first open slab price ────────
            $data->payment = $q->offer_open ?? 0;
            $data->save();

            // ── Send price email (failure must not kill the order) ────────────
            try {
                Mail::to($request['oemail'])->send(new HelloTransportInstantQuoteMail($q));
            } catch (\Exception $mailEx) {
                Log::error('HelloTransport autohaul email failed', [
                    'order_id' => $data->id,
                    'error'    => $mailEx->getMessage(),
                ]);
            }

            $this->expected_date($data->id, 1, '0', '');

            return response()->json([
                'data'        => $data,
                'status'      => true,
                'status_code' => 201,
            ]);
        } catch (\Exception $e) {
            Log::error('AutohaulQuoteController::store failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'error'   => 'An error occurred while processing the request.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function expected_date($order_id, $user_id, $pstatus, $expected_date)
    {
        $count_save = count_day::where('order_id', $order_id)->first();
        if (isset($count_save->id)) {
            $count_save->user_id = $user_id;
            if (!empty($expected_date)) {
                $count_save->expected_date = $expected_date;
            } elseif (empty($count_save->expected_date)) {
                $count_save->expected_date = date('Y-m-d H:i:s');
            }
            $count_save->pstatus = $pstatus;
            $count_save->save();
        } else {
            $count_save = new count_day();
            $count_save->user_id    = $user_id;
            $count_save->order_id   = $order_id;
            $count_save->expected_date = !empty($expected_date) ? $expected_date : date('Y-m-d H:i:s');
            $count_save->pstatus    = $pstatus;
            $count_save->save();
        }
    }
}
