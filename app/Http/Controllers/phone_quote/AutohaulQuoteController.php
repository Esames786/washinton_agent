<?php

namespace App\Http\Controllers\phone_quote;

use App\AutoOrder;
use App\count_day;
use App\creditcard;
use App\DailyQoute;
use App\Http\Controllers\Controller;
use App\InstantQuote;
use App\Mail\HelloTransportInstantQuoteMail;
use App\orderpayment;
use App\OrderTakerQouteAccess;
use App\report;
use App\Services\CentralGateway\GatewayClient;
use App\ShipaQuery;
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
            // ── Normalize year/make/model — hualt sends them as single-element arrays ─
            $year  = is_array($request['year'])  ? ($request['year'][0]  ?? '') : ($request['year']  ?? '');
            $make  = is_array($request['make'])  ? ($request['make'][0]  ?? '') : ($request['make']  ?? '');
            $model = is_array($request['model']) ? ($request['model'][0] ?? '') : ($request['model'] ?? '');
            $ymk   = $request['ymk'] ?? trim("$year $make $model");

            // ── Step 1: Save to shipa_query (mirrors websiteQuery) ────────────
            $query = new ShipaQuery;
            $query->oname            = $request['oname'];
            $query->oemail           = $request['oemail'];
            $query->ophone           = $request['ophone'];
            $query->ymk              = $ymk;
            $query->year             = $year;
            $query->type             = $request['type'] ?? null;
            $query->vehicle_opt      = $request['vehicle_opt'] ?? null;
            $query->model            = $model;
            $query->make             = $make;
            $query->condition        = $request['condition'] ?? null;
            $query->originzsc        = $request['originzsc'];
            $query->originzip        = $request['originzip'];
            $query->originstate      = $request['originstate'];
            $query->origincity       = $request['origincity'];
            $query->destinationzsc   = $request['destinationzsc'];
            $query->destinationzip   = $request['destinationzip'];
            $query->destinationstate = $request['destinationstate'];
            $query->destinationcity  = $request['destinationcity'];
            $query->add_info         = $request['add_info'] ?? null;
            $query->transport        = $request['transport'] ?? null;
            $query->shippingdate     = $request['shippingdate'] ?? null;
            $query->car_type         = 1;
            $query->paneltype        = 4;
            $query->cname            = $request['cname']   ?? $request['oname']  ?? null;
            $query->cemail           = $request['cemail']  ?? $request['oemail'] ?? null;
            $query->main_ph          = $request['main_ph'] ?? $request['ophone'] ?? null;
            $query->length_ft        = $request['length_ft'] ?? null;
            $query->length_in        = $request['length_in'] ?? null;
            $query->width_ft         = $request['width_ft'] ?? null;
            $query->width_in         = $request['width_in'] ?? null;
            $query->height_ft        = $request['height_ft'] ?? null;
            $query->height_in        = $request['height_in'] ?? null;
            $query->weight           = $request['weight'] ?? null;
            $query->load_method      = $request['load_method'] ?? null;
            $query->unload_method    = $request['unload_method'] ?? null;
            $query->ip_address       = $request['ip'] ?? null;
            $query->ipcity           = $request['ipcity'] ?? null;
            $query->ipregion         = $request['ipregion'] ?? null;
            $query->ipcountry        = $request['ipcountry'] ?? null;
            $query->iploc            = $request['iploc'] ?? null;
            $query->ippostal         = $request['ippostal'] ?? null;
            $query->pstatus          = 0;
            $query->source           = 'HelloTransport';
            $query->roro             = $request['roro'] ?? null;
            $query->heavy_type       = $request['heavy_type'] ?? null;
            $query->category         = $request['category'] ?? null;
            $query->subcategory      = $request['subcategory'] ?? null;
            $query->save();

            // ── Step 2: Pick order taker (DailyQoute round-robin) ────────────
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
                $user_iddd = $user->user->id;
            } else {
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
            }

            // ── Step 3: Create AutoOrder from ShipaQuery (mirrors shipa1_queryAssignDirect) ─
            $data = new AutoOrder();
            $data->order_taker_id   = $user_iddd;
            $data->oname            = $query->oname;
            $data->oemail           = $query->oemail;
            $data->ophone           = $query->ophone;
            $data->main_ph          = $query->main_ph;
            $data->ymk              = $query->ymk;
            $data->year             = $query->year;
            $data->make             = $query->make;
            $data->model            = $query->model;
            $data->type             = $query->type;
            $data->vehicle_opt      = $query->vehicle_opt;
            $data->condition        = $query->condition;
            $data->car_type         = 1;
            $data->transport        = $query->transport;
            $data->shippingdate     = $query->shippingdate ?? null;
            $data->originzsc        = $query->originzsc;
            $data->originzip        = $query->originzip;
            $data->originstate      = $query->originstate;
            $data->origincity       = $query->origincity;
            $data->destinationzsc   = $query->destinationzsc;
            $data->destinationzip   = $query->destinationzip;
            $data->destinationstate = $query->destinationstate;
            $data->destinationcity  = $query->destinationcity;
            $data->add_info         = $query->add_info;
            $data->cname            = $query->cname;
            $data->cemail           = $query->cemail;
            $data->paneltype        = 4;
            $data->ip_address       = $query->ip_address;
            $data->ipcity           = $query->ipcity;
            $data->ipregion         = $query->ipregion;
            $data->ipcountry        = $query->ipcountry;
            $data->iploc            = $query->iploc ?? null;
            $data->ippostal         = $query->ippostal;
            $data->length_ft        = $query->length_ft ?? null;
            $data->width_ft         = $query->width_ft  ?? null;
            $data->height_ft        = $query->height_ft ?? null;
            $data->weight           = $query->weight    ?? null;
            $data->source           = 'HelloTransport';
            $data->pstatus          = 0;
            $data->request_hauling  = 1;
            $data->payment          = 0;
            $data->save();

            // ── Companion records (same as shipa1_queryAssignDirect) ──────────
            $op = new orderpayment();
            $op->orderId = $data->id;
            $op->save();

            $cc = new creditcard();
            $cc->orderId = $data->id;
            $cc->save();

            $rpt = new report();
            $rpt->userId  = $user_iddd ?? 1;
            $rpt->orderId = $data->id;
            $rpt->pstatus = 0;
            $rpt->save();

            $sr = new singlereport();
            $sr->userId  = $user_iddd ?? 1;
            $sr->orderId = $data->id;
            $sr->pstatus = 0;
            $sr->save();

            // Mark ShipaQuery as assigned
            $query->user_id = $user_iddd;
            $query->save();

            // ── Step 4: Get pricing from central-gateway ──────────────────────
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
                        'year'  => (int) $year,
                        'make'  => $make,
                        'model' => $model,
                        'type'  => 'Car',
                    ],
                ],
                'referenceId' => 'HT-AUTOHAUL-' . uniqid(),
            ]);

            $pricingBody = $pricingResult['body'] ?? [];

            // ── Step 5: Save InstantQuote ─────────────────────────────────────
            $q = new InstantQuote();
            $q->origin_location      = $request['originzsc'];
            $q->destination_location = $request['destinationzsc'];
            $q->type                 = 'Car';
            $q->year_make_model      = $ymk;
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

            // Set display-only fields for email (not DB columns)
            $q->condition = $request['condition'] ?? null;
            $q->transport = $request['transport'] ?? null;

            // ── Back-fill AutoOrder payment with first open slab price ────────
            $data->payment = $q->offer_open ?? 0;
            $data->save();

            // ── Step 6: Send price email ──────────────────────────────────────
            $recipientEmail = $q->customer_email ?? $request['oemail'] ?? null;
            if (filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
                try {
                    Mail::to($recipientEmail)->send(new HelloTransportInstantQuoteMail($q));
                } catch (\Exception $mailEx) {
                    Log::error('HelloTransport autohaul email failed', [
                        'order_id' => $data->id,
                        'error'    => $mailEx->getMessage(),
                    ]);
                }
            } else {
                Log::warning('HelloTransport autohaul email skipped — no valid recipient', [
                    'order_id' => $data->id,
                    'oemail'   => $request['oemail'] ?? null,
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
            $count_save->user_id       = $user_id;
            $count_save->order_id      = $order_id;
            $count_save->expected_date = !empty($expected_date) ? $expected_date : date('Y-m-d H:i:s');
            $count_save->pstatus       = $pstatus;
            $count_save->save();
        }
    }
}
