<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Per-user timezone for the agent portal.
 *
 * Mirrors hr_employees.timezone (added by the HR migration of the same date) so each app can
 * render times and stamp records in the person's own zone without joining across tables.
 *
 * Defaults to Asia/Karachi so every existing CrazyRays agent keeps exactly today's behaviour;
 * Hello agents pick their timezone at signup (asked alongside country).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user', function (Blueprint $table) {
            if (!Schema::hasColumn('user', 'timezone')) {
                $table->string('timezone', 64)->nullable()->default('Asia/Karachi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            if (Schema::hasColumn('user', 'timezone')) {
                $table->dropColumn('timezone');
            }
        });
    }
};
