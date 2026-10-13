<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * W-03: the Hello "Apply for an Opportunity" form shares this table with the CrazyRays
 * careers flow, distinguished by `brand` (the same pattern hr_document_settings uses).
 *
 * Adds the five fields the Hello form asks for that CrazyRays never did, and relaxes
 * `campaign` — it is required for CrazyRays campaigns but meaningless for a Hello
 * sales-agent application.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cr_applications', function (Blueprint $table) {
            if (! Schema::hasColumn('cr_applications', 'brand')) {
                $table->string('brand', 20)->default('crazyrays')->index()->after('id');
            }
            if (! Schema::hasColumn('cr_applications', 'time_zone')) {
                $table->string('time_zone', 60)->nullable()->after('city');
            }
            if (! Schema::hasColumn('cr_applications', 'us_experience')) {
                $table->string('us_experience', 10)->nullable()->after('campaign_experience');
            }
            if (! Schema::hasColumn('cr_applications', 'us_hours')) {
                $table->string('us_hours', 20)->nullable()->after('us_experience');
            }
            if (! Schema::hasColumn('cr_applications', 'linkedin_url')) {
                $table->string('linkedin_url', 255)->nullable()->after('resume_path');
            }
        });

        // Every existing row predates this and is CrazyRays.
        DB::table('cr_applications')->whereNull('brand')->update(['brand' => 'crazyrays']);

        // `campaign` is NOT NULL with no default, which would reject every Hello
        // application. Give it a default rather than making it nullable, so existing
        // CrazyRays code that reads it still gets a string.
        try {
            DB::statement("ALTER TABLE cr_applications MODIFY campaign VARCHAR(60) NOT NULL DEFAULT ''");
        } catch (\Throwable $e) {
            // Non-fatal: if the column already allows this, carry on.
        }
    }

    public function down(): void
    {
        Schema::table('cr_applications', function (Blueprint $table) {
            foreach (['brand', 'time_zone', 'us_experience', 'us_hours', 'linkedin_url'] as $c) {
                if (Schema::hasColumn('cr_applications', $c)) {
                    $table->dropColumn($c);
                }
            }
        });
    }
};
