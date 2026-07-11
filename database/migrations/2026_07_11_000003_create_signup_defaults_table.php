<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * B6 — signup defaults (replaces the "copy reference user 130/53" logic).
 *
 * New signups (hello + crazy) currently clone permission/panel columns from
 * reference users 130 (Order Taker) / 53 (Carrier/Dispatcher). Once panels are
 * city-named + dynamic, cloning fixed columns breaks. Instead we store one
 * admin-editable default set of permission/access strings per role_key here.
 *
 * `payload` is a JSON map of { user_column => value } applied to a new signup,
 * e.g. { "emp_access_phone": "1,2,3", "emp_access_action": "...", ... }.
 * Additive: nothing reads this until the signup controllers are rewired.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('signup_defaults')) {
            return;
        }

        Schema::create('signup_defaults', function (Blueprint $table) {
            $table->increments('id');
            $table->string('role_key', 50)->unique(); // 'order_taker' | 'dispatcher'
            $table->string('label', 100)->nullable();
            $table->unsignedInteger('role_id')->nullable();
            $table->json('payload')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('signup_defaults');
    }
};
