<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Application-level split. `campaign` (string key) is kept for backward
 * compatibility; campaign_id links to the managed cr_campaigns row and
 * employment_type is the canonical applicant track.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cr_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('cr_applications', 'employment_type')) {
                $table->enum('employment_type', ['work_from_home', 'in_house'])
                      ->nullable()->after('campaign');
            }
            if (!Schema::hasColumn('cr_applications', 'campaign_id')) {
                $table->unsignedInteger('campaign_id')->nullable()->after('employment_type');
                $table->index('campaign_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cr_applications', function (Blueprint $table) {
            if (Schema::hasColumn('cr_applications', 'campaign_id')) {
                $table->dropIndex(['campaign_id']);
                $table->dropColumn('campaign_id');
            }
            if (Schema::hasColumn('cr_applications', 'employment_type')) {
                $table->dropColumn('employment_type');
            }
        });
    }
};
