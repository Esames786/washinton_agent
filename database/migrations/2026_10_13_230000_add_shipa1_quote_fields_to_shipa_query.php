<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Website point 7 — per-service quote forms "same as ShipA1".
 *
 * Checked ShipA1's 45 quote fields against shipa_query: 25 already exist, and the 17
 * freight-specific ones already exist in order_freight (built for this and never filled
 * from the website — 0 rows). Only three are genuinely missing:
 *
 *   trailer_type   every heavy / boat / RV form asks for it; it previously had to be
 *                  stuffed into add_info as free text
 *   to_country     RoRo is INTERNATIONAL — the destination is a country and city, not a
 *   to_city        US zip code, so it needs somewhere to go
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipa_query', function (Blueprint $table) {
            if (! Schema::hasColumn('shipa_query', 'trailer_type')) {
                $table->string('trailer_type', 60)->nullable();
            }
            if (! Schema::hasColumn('shipa_query', 'to_country')) {
                $table->string('to_country', 100)->nullable();
            }
            if (! Schema::hasColumn('shipa_query', 'to_city')) {
                $table->string('to_city', 100)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('shipa_query', function (Blueprint $table) {
            foreach (['trailer_type', 'to_country', 'to_city'] as $c) {
                if (Schema::hasColumn('shipa_query', $c)) {
                    $table->dropColumn($c);
                }
            }
        });
    }
};
