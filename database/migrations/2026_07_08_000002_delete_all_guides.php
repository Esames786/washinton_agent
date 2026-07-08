<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Client request (2026-07-08): remove all guides from the production DB (the /guides page
 * should be empty). Guides are DB-driven from the `guide` table. This clears every row.
 * NOTE: one-way — deleted guide content cannot be restored by rolling back.
 */
class DeleteAllGuides extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('guide')) {
            DB::table('guide')->delete();
        }
    }

    public function down(): void
    {
        // Irreversible: guide content is not retained.
    }
}
