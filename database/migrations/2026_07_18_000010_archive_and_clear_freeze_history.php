<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * #11 — Freeze User History: archive all existing rows into freeze_users_archive,
 * then clear the live freeze_users table so the history screen starts fresh.
 *
 * SAFE: freeze_users is only the audit LOG. The active freeze state lives on
 * `user.freeze`, which is NOT touched. Nothing is lost — every row is copied to
 * the archive before deletion. Idempotent: if freeze_users is already empty, no-op.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('freeze_users')) {
            return;
        }

        // Preserve everything in an archive table (created once, same structure).
        if (!Schema::hasTable('freeze_users_archive')) {
            DB::statement('CREATE TABLE freeze_users_archive LIKE freeze_users');
        }

        if (DB::table('freeze_users')->count() > 0) {
            DB::statement('INSERT INTO freeze_users_archive SELECT * FROM freeze_users');
            DB::table('freeze_users')->delete();
        }
    }

    public function down(): void
    {
        // Restore from archive (best-effort); archive table is left in place.
        if (Schema::hasTable('freeze_users_archive') && Schema::hasTable('freeze_users')) {
            DB::statement('INSERT INTO freeze_users SELECT * FROM freeze_users_archive');
        }
    }
};
