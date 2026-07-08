<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * #12 (2026-07-09): optional per-user IP restriction. When enabled, the user may only log in
 * (hello or crazy) from one of the allowed IPs; otherwise login is blocked with a "contact
 * admin" message. Multiple IPs are stored newline/comma separated.
 */
class AddIpCheckToUser extends Migration
{
    public function up(): void
    {
        Schema::table('user', function (Blueprint $table) {
            if (!Schema::hasColumn('user', 'ip_check_enabled')) {
                $table->boolean('ip_check_enabled')->default(0);
            }
            if (!Schema::hasColumn('user', 'allowed_ips')) {
                $table->text('allowed_ips')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            foreach (['ip_check_enabled', 'allowed_ips'] as $col) {
                if (Schema::hasColumn('user', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
}
