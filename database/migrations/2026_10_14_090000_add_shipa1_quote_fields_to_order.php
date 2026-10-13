<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The order an order-taker actually works is created FROM the website quote
 * (FrontendController::createUnassignedOrderFromQuery). The previous migration gave
 * shipa_query three new columns; without the same on `order`, those values stopped at the
 * quote and never reached the person who has to price it.
 *
 * Every other ShipA1 field already exists on `order` — only these three were missing.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order', function (Blueprint $table) {
            if (! Schema::hasColumn('order', 'trailer_type')) {
                $table->string('trailer_type', 60)->nullable();
            }
            if (! Schema::hasColumn('order', 'to_country')) {
                $table->string('to_country', 100)->nullable();
            }
            if (! Schema::hasColumn('order', 'to_city')) {
                $table->string('to_city', 100)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('order', function (Blueprint $table) {
            foreach (['trailer_type', 'to_country', 'to_city'] as $c) {
                if (Schema::hasColumn('order', $c)) {
                    $table->dropColumn($c);
                }
            }
        });
    }
};
