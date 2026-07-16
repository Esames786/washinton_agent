<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * P0 one-time backfill: orders that were already "Payment Confirmed" in the new
 * payment system BEFORE the confirm()->paid_status sync fix were left stuck at
 * paid_status = 3 (Confirmation Pending). Flip those to 2 (Received) so the badge
 * matches the confirmed payment. Scope limited to paid_status = 3 (the booking
 * flow introduced with the two-state change) to avoid touching older orders.
 */
return new class extends Migration
{
    public function up(): void
    {
        $confirmedOrderIds = DB::table('order_payments')
            ->where('payment_status', 'Payment Confirmed')
            ->pluck('order_id')
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (empty($confirmedOrderIds)) {
            return;
        }

        // Only the orders actually stuck at 3 (Confirmation Pending) are corrected.
        $stuckIds = DB::table('order')
            ->whereIn('id', $confirmedOrderIds)
            ->where('paid_status', 3)
            ->pluck('id')
            ->all();

        if (empty($stuckIds)) {
            return;
        }

        DB::table('order')->whereIn('id', $stuckIds)->update(['paid_status' => 2]);

        // Keep the legacy orderpayments row in sync (mirrors confirm()).
        DB::table('orderpayments')->whereIn('orderId', $stuckIds)->update(['payment_status' => 'Paid']);
    }

    public function down(): void
    {
        // No-op: historical status correction is not reversible.
    }
};
