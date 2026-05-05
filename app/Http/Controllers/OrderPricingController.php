<?php
namespace App\Http\Controllers;

use App\AutoOrder;
use App\OrderPriceRequest;
use Illuminate\Http\Request;
use App\Services\CentralGateway\GatewayClient;

class OrderPricingController extends Controller
{
    public function check(Request $request, AutoOrder $order, GatewayClient $gateway)
    {
        $originZip = trim((string)($order->originzip ?? ''));
        $destZip = trim((string)($order->destinationzip ?? ''));
        $originCity = trim((string)($order->origincity ?? ''));
        $destCity = trim((string)($order->destinationcity ?? ''));
        $originState = strtoupper(trim((string)($order->originstate ?? '')));
        $destState = strtoupper(trim((string)($order->destinationstate ?? '')));

        $errors = [];

        if (!$originZip) $errors['originzip'][] = 'Pickup zip is required.';
        if (!$destZip) $errors['destinationzip'][] = 'Delivery zip is required.';

        $vehicles = $this->buildVehiclesFromOrder($order);
        if (empty($vehicles)) {
            $errors['vehicles'][] = 'At least 1 vehicle Year/Make/Model is required.';
        }

        if (!empty($errors)) {
            return response()->json([
                'message' => 'Please complete order details before requesting price.',
                'errors' => $errors,
            ], 422);
        }

        $payload = [
            'platform_code' => 'hello_transport',
            'stops' => [
                array_filter(['stopNumber' => 1, 'zipCode' => $originZip, 'state' => $originState, 'city' => $originCity], fn($v) => $v !== '' && $v !== null),
                array_filter(['stopNumber' => 2, 'zipCode' => $destZip, 'state' => $destState, 'city' => $destCity], fn($v) => $v !== '' && $v !== null),
            ],
            'vehicles' => $vehicles,
            'referenceId' => 'WA-ORDER-' . $order->id,
        ];
        $find_old =  OrderPriceRequest::query()
            ->where('order_id', $order->id)
            ->latest()->first();

        if(!empty($find_old)){
            if($find_old->origin_zip != $originZip){
                $errors['vehicles'][] = 'Cannot change Origin/Destination on Requested Quote';
                return response()->json([
                    'message' => 'Location Error',
                    'errors' => $errors,
                ], 422);
            }
            if($find_old->destination_zip != $destZip){
                $errors['vehicles'][] = 'Cannot change Origin/Destination on Requested Quote';
                return response()->json([
                    'message' => 'Location Error',
                    'errors' => $errors,
                ], 422);
            }
        }

        $result = $gateway->quote($payload);

        if (!$result['ok']) {
            $status = (int)($result['status'] ?? 0);
            if ($status < 100) $status = 502;

            return response()->json([
                'message' => 'Gateway error',
                'status' => $result['status'],
                'body' => $result['body'],
            ], $status);
        }

        $gw = (array)$result['body'];

        // Optional: store history snapshot
        if (class_exists(\App\OrderPriceRequest::class)) {
            \App\OrderPriceRequest::create([
                'order_id' => $order->id,
                'requested_by' => auth()->id(),
                'origin_zip' => $originZip,
                'destination_zip' => $destZip,
                'origin_state' => $originState,
                'destination_state' => $destState,
                'vehicle_key' => (string)data_get($gw, 'primary.vehicle_key', ''),
                'driver_mid' => (float)(data_get($gw, 'primary.modes.open.driver_price.mid') ?? data_get($gw, 'primary.modes.enclosed.driver_price.mid') ?? 0),
                'offer_open' => (float)(data_get($gw, 'primary.modes.open.offer_price.value') ?? 0),
                'offer_enclosed' => (float)(data_get($gw, 'primary.modes.enclosed.offer_price.value') ?? 0),
                'cache_hit' => (bool)(data_get($gw, 'cache_hit') ?? false),
                'request_payload' => $payload,
                'response_payload' => $gw,
            ]);
        }

        // Return full gateway response (UI will show open+enclosed per vehicle)
        return response()->json($gw);
    }

    private function buildVehiclesFromOrder(AutoOrder $order): array
    {
        $yearRaw = (string)($order->year ?? '');
        $makeRaw = (string)($order->make ?? '');
        $modelRaw = (string)($order->model ?? '');
        $typeRaw = (string)($order->type ?? '');

        if ($yearRaw !== '' && str_contains($yearRaw, '*^')) {
            $years = $this->splitMulti($yearRaw);
            $makes = $this->splitMulti($makeRaw);
            $models = $this->splitMulti($modelRaw);
            $types = $this->splitMulti($typeRaw);

            $n = max(count($years), count($makes), count($models));
            $out = [];

            $defaultType = trim((string)($types[0] ?? '')) ?: 'Car';

            for ($i = 0; $i < $n; $i++) {
                $y = (int)($years[$i] ?? 0);
                $mk = trim((string)($makes[$i] ?? ''));
                $md = trim((string)($models[$i] ?? ''));
                $tp = trim((string)($types[$i] ?? $defaultType)) ?: $defaultType;

                if ($y && $mk !== '' && $md !== '') {
                    $out[] = ['year' => $y, 'make' => $mk, 'model' => $md, 'type' => $tp];
                }
            }

            return $out;
        }

        $y = (int)($order->year ?? 0);
        $mk = trim((string)($order->make ?? ''));
        $md = trim((string)($order->model ?? ''));
        $tp = trim((string)($order->type ?? '')) ?: 'Car';

        if ($y > 0 && $mk !== '' && $md !== '') {
            return [['year' => $y, 'make' => $mk, 'model' => $md, 'type' => $tp]];
        }

        return [];
    }

    private function splitMulti(string $value): array
    {
        return array_values(array_filter(array_map('trim', explode('*^', $value)), fn($x) => $x !== ''));
    }
    public function history(Request $request, AutoOrder $order)
    {
        $items = OrderPriceRequest::query()
            ->where('order_id', $order->id)
            ->latest()
            ->limit(30)
            ->get()
            ->map(function ($r) {
                return [
                    'at' => $r->created_at?->format('Y-m-d H:i:s'),
                    'origin_zip' => $r->origin_zip,
                    'origin_state' => $r->origin_state,
                    'destination_zip' => $r->destination_zip,
                    'destination_state' => $r->destination_state,
                    'vehicle_key' => $r->vehicle_key,
                    'driver_mid' => $r->driver_mid,
                    'offer_open' => $r->offer_open,
                    'offer_enclosed' => $r->offer_enclosed,
                    'cache_hit' => (bool) $r->cache_hit,
                ];
            });

        return response()->json(['items' => $items]);
    }
}
