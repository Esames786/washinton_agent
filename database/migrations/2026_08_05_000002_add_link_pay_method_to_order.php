<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * #9 (meeting 1 Aug): the agent picks the customer's payment method when sending the booking
 * link (zelle / cashapp / card / venmo / paypal / cod / cop); the booking form then only shows
 * that method. `customer_pay_reference` holds the customer's transaction reference for the
 * alternative methods — masked everywhere agents can see, full on the admin card screen.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order', function (Blueprint $table) {
            if (!Schema::hasColumn('order', 'link_pay_method')) {
                $table->string('link_pay_method', 20)->nullable();
            }
            if (!Schema::hasColumn('order', 'customer_pay_reference')) {
                $table->string('customer_pay_reference', 120)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('order', function (Blueprint $table) {
            foreach (['link_pay_method', 'customer_pay_reference'] as $col) {
                if (Schema::hasColumn('order', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
