<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmpAccessGuideVideoToUser extends Migration
{
    /**
     * #5 (2026-07-03): per-agent guide-video assignment (comma-separated GuideVideo
     * ids), mirroring emp_access_guide for guides.
     */
    public function up()
    {
        if (!Schema::hasColumn('user', 'emp_access_guide_video')) {
            Schema::table('user', function (Blueprint $table) {
                $table->text('emp_access_guide_video')->nullable()->after('emp_access_guide');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('user', 'emp_access_guide_video')) {
            Schema::table('user', function (Blueprint $table) {
                $table->dropColumn('emp_access_guide_video');
            });
        }
    }
}
