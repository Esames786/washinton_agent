<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Phase D — the Campaign Leads statuses.
 *
 * The client's four lead states are: Application Received (pending), Under Review,
 * Shortlisted, Rejected. The enum only had pending/approved/rejected, so two are added.
 *
 * `approved` is KEPT even though Hello does not use it: the CrazyRays careers flow
 * (CrApplicationController@approve) sets it on 97 live rows, and dropping it from the
 * enum would blank every one of them.
 */
return new class extends Migration
{
    private const NEW_ENUM = "enum('pending','under_review','shortlisted','approved','rejected')";
    private const OLD_ENUM = "enum('pending','approved','rejected')";

    public function up(): void
    {
        DB::statement('ALTER TABLE cr_applications MODIFY status ' . self::NEW_ENUM . " NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Anything mid-pipeline would be invalid under the old enum — park it back at pending
        // rather than letting MySQL silently blank it.
        DB::table('cr_applications')->whereIn('status', ['under_review', 'shortlisted'])->update(['status' => 'pending']);

        DB::statement('ALTER TABLE cr_applications MODIFY status ' . self::OLD_ENUM . " NOT NULL DEFAULT 'pending'");
    }
};
