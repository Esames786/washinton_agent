<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * #3 (2026-07-08): payment submission must include a booking / authorization form
 * (image or PDF) in addition to the transaction screenshot. Store its path.
 */
class AddBookingFormToOrderPayments extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('order_payments') && !Schema::hasColumn('order_payments', 'booking_form_path')) {
            Schema::table('order_payments', function (Blueprint $table) {
                $table->string('booking_form_path')->nullable()->after('screenshot_path');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('order_payments') && Schema::hasColumn('order_payments', 'booking_form_path')) {
            Schema::table('order_payments', function (Blueprint $table) {
                $table->dropColumn('booking_form_path');
            });
        }
    }
}
