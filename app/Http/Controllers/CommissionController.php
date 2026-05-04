<?php

namespace App\Http\Controllers;

use App\CommissionRange;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommissionController extends Controller
{
    /**
     * Commission summary — one row per user.
     */
    public function index(Request $request)
    {
        [$fromDate, $toDate, $label] = $this->resolveDateRange($request);

        $rows = $this->buildSummary($fromDate, $toDate);

        return view('main.commission.index', compact('rows', 'fromDate', 'toDate', 'label'));
    }

    /**
     * Per-order breakdown for a single user.
     */
    public function show(Request $request, int $userId)
    {
        $user = User::findOrFail($userId);

        [$fromDate, $toDate, $label] = $this->resolveDateRange($request);

        $orders = $this->buildOrderBreakdown($userId, $fromDate, $toDate);
        return view('main.commission.show', compact('user', 'orders', 'fromDate', 'toDate', 'label'));
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function resolveDateRange(Request $request): array
    {
        // Accept ?month=2025-04  OR  ?from_date=2025-04-01&to_date=2025-04-30
        if ($request->filled('month')) {
            try {
                $start = Carbon::createFromFormat('Y-m', $request->month)->startOfMonth();
                $end   = $start->copy()->endOfMonth();
                $label = $start->format('F Y');
                return [$start->toDateString(), $end->toDateString(), $label];
            } catch (\Exception $e) {
                // fall through to default
            }
        }

        $from = $request->filled('from_date')
            ? Carbon::parse($request->from_date)->startOfDay()->toDateString()
            : Carbon::now()->startOfMonth()->toDateString();

        $to = $request->filled('to_date')
            ? Carbon::parse($request->to_date)->endOfDay()->toDateString()
            : Carbon::now()->endOfMonth()->toDateString();

        $label = $from . ' → ' . $to;

        return [$from, $to, $label];
    }

    private function buildSummary(string $fromDate, string $toDate): \Illuminate\Support\Collection
    {
        // LEFT JOIN so users with zero completed orders still appear
        $rows = DB::table('user as u')
            ->select([
                'u.id as user_id',
                'u.name as user_name',
                DB::raw('COUNT(o.id) as completed_orders_count'),
                DB::raw('COALESCE(SUM(o.payment), 0) as total_payment'),
                DB::raw('COALESCE(SUM(p.profit), 0) as total_profit'),
            ])
            ->leftJoin('order as o', function ($join) use ($fromDate, $toDate) {
                $join->on('o.order_taker_id', '=', 'u.id')
                     ->where('o.pstatus', '=', 13)
                     ->whereNull('o.deleted_at')
                     ->whereBetween('o.created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
            })
            ->leftJoin('profit as p', 'p.order_id', '=', 'o.id')
            ->where('u.deleted', 0)
            ->groupBy('u.id', 'u.name')
            ->orderBy('u.name')
            ->get();

        // Calculate commission per row
        return $rows->map(function ($row) {
            $row->total_commission = $this->calculateCommission((float) $row->total_profit, $row->user_id, null);
            return $row;
        });
    }

    private function buildOrderBreakdown(int $userId, string $fromDate, string $toDate): \Illuminate\Support\Collection
    {
        return $this->buildOrderBreakdownPublic($userId, $fromDate, $toDate);
    }

    /**
     * Public version used by WashingtonBridgeController.
     */
    public function buildOrderBreakdownPublic(int $userId, string $fromDate, string $toDate): \Illuminate\Support\Collection
    {
        $orders = DB::table('order as o')
            ->select([
                'o.id as order_id',
                'o.created_at as completion_date',
                'o.payment',
                DB::raw('COALESCE(p.profit, 0) as profit'),
            ])
            ->leftJoin('profit as p', 'p.order_id', '=', 'o.id')
            ->where('o.order_taker_id', $userId)
            ->where('o.pstatus', 13)
            ->whereNull('o.deleted_at')
            ->whereBetween('o.created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
            ->orderBy('o.created_at', 'desc')
            ->get();

        return $orders->map(function ($order) use ($userId) {
            $order->commission = $this->calculateCommission((float) $order->profit, $userId, $order->order_id);
            return $order;
        });
    }

    /**
     * Look up commission for a given profit value using commission_ranges table.
     * If no range matches, returns 0 and logs a warning.
     */
    public function calculateCommission(float $profit, int $userId, ?int $orderId): float
    {
        if ($profit <= 0) {
            return 0.0;
        }

        $range = DB::table('commission_ranges')
            ->where('from_order', '<=', $profit)
            ->where('to_order', '>=', $profit)
            ->first();

        if (!$range) {
            Log::warning('CommissionController: no commission_ranges row matched', [
                'profit'   => $profit,
                'order_id' => $orderId,
                'user_id'  => $userId,
            ]);
            return 0.0;
        }

        return (float) $range->commission;
    }
}
