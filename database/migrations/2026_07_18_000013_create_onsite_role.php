<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * #16 — new "Onsite" agent role. Admin & Manager can change an Order Taker's job type
 * to Onsite from the subcontractor edit screen. Idempotent by name.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('roles')) {
            return;
        }
        if (!DB::table('roles')->where('name', 'Onsite')->exists()) {
            DB::table('roles')->insert([
                'name'        => 'Onsite',
                'slug'        => 'Onsite',
                'description' => 'On-site employee',
                'level'       => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('roles')) {
            DB::table('roles')->where('name', 'Onsite')->delete();
        }
    }
};
