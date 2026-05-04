<?php

namespace App\Http\Controllers\Bridge;

use App\Http\Controllers\CommissionController;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WashingtonBridgeController extends Controller
{
    /**
     * POST /bridge/washington/agent/commission
     *
     * Called by washinton_hr to get commission data for a Washington user.
     * agent_id in the request = Washington user.id (stored as hr_employees.agent_id)
     */
    public function commission(Request $request): JsonResponse
    {
        $this->validateBridgeKey($request);

        $validator = Validator::make($request->all(), [
            'agent_id' => 'required|integer|min:1',
            'month'    => 'nullable|date_format:Y-m',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $userId = (int) $request->agent_id;

        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'message' => "No Washington user found with id {$userId}.",
            ], 404);
        }

        // Resolve date range from month param
        if ($request->filled('month')) {
            try {
                $start = Carbon::createFromFormat('Y-m', $request->month)->startOfMonth();
                $end   = $start->copy()->endOfMonth();
                $period = $request->month;
            } catch (\Exception $e) {
                $start  = Carbon::now()->startOfMonth();
                $end    = Carbon::now()->endOfMonth();
                $period = Carbon::now()->format('Y-m');
            }
        } else {
            $start  = Carbon::now()->startOfMonth();
            $end    = Carbon::now()->endOfMonth();
            $period = Carbon::now()->format('Y-m');
        }

        $fromDate = $start->toDateString();
        $toDate   = $end->toDateString();

        // Reuse CommissionController query logic
        $commissionCtrl = new CommissionController();
        $orders = $commissionCtrl->buildOrderBreakdownPublic($userId, $fromDate, $toDate);

        $totalPayment    = $orders->sum('payment');
        $totalProfit     = $orders->sum('profit');
        $totalCommission = $orders->sum('commission');

        return response()->json([
            'agent_id'               => $userId,
            'user_id'                => $user->id,
            'user_name'              => $user->name,
            'period'                 => $period,
            'completed_orders_count' => $orders->count(),
            'total_payment'          => number_format((float) $totalPayment, 2, '.', ''),
            'total_profit'           => number_format((float) $totalProfit, 2, '.', ''),
            'total_commission'       => number_format((float) $totalCommission, 2, '.', ''),
        ]);
    }

    /**
     * POST /bridge/washington/agent/status
     *
     * Called by washinton_hr to check if a Washington user exists and is active.
     * agent_id in the request = Washington user.id
     */
    public function status(Request $request): JsonResponse
    {
        $this->validateBridgeKey($request);

        $validator = Validator::make($request->all(), [
            'agent_id' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $userId = (int) $request->agent_id;
        $user   = User::find($userId);

        if (!$user) {
            return response()->json([
                'linked'    => false,
                'active'    => false,
                'user_id'   => null,
                'user_name' => null,
                'message'   => "No Washington user found with id {$userId}.",
            ]);
        }

        $isActive = (int) $user->status === 1;

        return response()->json([
            'linked'    => true,
            'active'    => $isActive,
            'user_id'   => $user->id,
            'user_name' => $user->name,
            'message'   => $isActive ? 'User linked and active.' : 'User linked but inactive.',
        ]);
    }

    /**
     * Validate the X-Bridge-Key header against config('bridge.washington.shared_key').
     * Aborts with 401 if missing or invalid.
     */
    private function validateBridgeKey(Request $request): void
    {
        $configuredKey = (string) config('bridge.washington.shared_key');
        $incomingKey   = (string) $request->header('X-Bridge-Key', '');

        if (blank($configuredKey) || !hash_equals($configuredKey, $incomingKey)) {
            Log::warning('WashingtonBridgeController: invalid X-Bridge-Key', [
                'ip'       => $request->ip(),
                'endpoint' => $request->path(),
            ]);
            abort(response()->json(['message' => 'Unauthorized.'], 401));
        }
    }
}
