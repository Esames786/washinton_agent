<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * B6 (dynamic panels) — per-user, per-panel access list.
 *
 * Replaces the 6 fixed `user` columns (emp_access_phone/web/test, panel_type_4/5/6)
 * with one row per (user, panel_type). `access_ids` keeps the SAME comma-separated
 * permission-id string the legacy columns held, so the User model's compatibility
 * accessors can read/write it transparently.
 *
 * Additive only: nothing reads this until the copy-seeder has populated it AND the
 * User accessors are switched on. See B6DynamicPanelSeeder + the deploy note.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('user_panel_access')) {
            return;
        }

        Schema::create('user_panel_access', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('panel_type_id')->index();
            $table->longText('access_ids')->nullable(); // comma-separated permission ids (legacy format)
            $table->timestamps();

            $table->unique(['user_id', 'panel_type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_panel_access');
    }
};
