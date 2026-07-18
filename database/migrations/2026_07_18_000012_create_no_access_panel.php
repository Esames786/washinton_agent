<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * #4 — "No Access" onboarding panel. New signups (Hello, CrazyRays-converted,
 * HR-portal adds) land here with NO permissions, so they can only see basic screens
 * (dashboard) until an admin assigns proper access. Idempotent by name.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('panel_types')) {
            return;
        }
        if (!DB::table('panel_types')->where('name', 'No Access')->exists()) {
            $maxSort = (int) DB::table('panel_types')->max('sort');
            DB::table('panel_types')->insert([
                'name'       => 'No Access',
                'is_system'  => 0,
                'is_default' => 0,
                'sort'       => $maxSort + 1,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('panel_types')) {
            DB::table('panel_types')->where('name', 'No Access')->delete();
        }
    }
};
