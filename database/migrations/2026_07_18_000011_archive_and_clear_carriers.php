<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * #14 — Carrier Update: archive all existing `carriers` rows into carriers_archive,
 * then clear the live `carriers` table so the Carrier Update feed shows real-time data.
 *
 * Every row is copied to the archive before deletion (nothing lost). Idempotent:
 * if `carriers` is already empty, no-op.
 *
 * NOTE: verify after running that active orders do not depend on these rows.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('carriers')) {
            return;
        }

        if (!Schema::hasTable('carriers_archive')) {
            DB::statement('CREATE TABLE carriers_archive LIKE carriers');
        }

        if (DB::table('carriers')->count() > 0) {
            DB::statement('INSERT INTO carriers_archive SELECT * FROM carriers');
            DB::table('carriers')->delete();
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('carriers_archive') && Schema::hasTable('carriers')) {
            DB::statement('INSERT INTO carriers SELECT * FROM carriers_archive');
        }
    }
};
