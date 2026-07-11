<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * B6 (dynamic panels) — foundation table.
 *
 * Holds the display name + metadata for each panel. The numeric panel id is the
 * SAME integer already used everywhere as `paneltype` on orders and as the panel
 * index (1..6) behind the legacy user columns, so nothing is renumbered:
 *   1 = Lahore   (was "Panel 1" / emp_access_phone)
 *   2 = Islamabad(was "Panel 2" / emp_access_web)
 *   3 = Testing  (system, unchanged)
 *   4 = Website  (system, unchanged — hello/autohaul quotes land here)
 *   5 = Rawalpindi (was "Panel 5")
 *   6 = Multan     (was "Panel 6")
 *   7 = Bahawalpur (new)   8 = Jhang (new)   9 = Peshawar (new)
 *  10 = Karachi    (new — also the signup fallback panel)
 *
 * This table is purely additive: no existing code reads it yet, so creating it
 * changes nothing until the label lookups / seeder are wired in.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('panel_types')) {
            return;
        }

        Schema::create('panel_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            // system panels (Testing, Website) cannot be renamed/deleted from the admin UI
            $table->boolean('is_system')->default(false);
            // used as the signup fallback when no city matches
            $table->boolean('is_default')->default(false);
            $table->unsignedInteger('sort')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('panel_types');
    }
};
