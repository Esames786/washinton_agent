<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * #1 (meeting 1 Aug): store the applicant's real IP with each CrazyRays application.
 * Captured on crazyrayssolutions.com.pk (the browser posts there) and forwarded through the
 * bridge — this server only ever sees the crazyrays server's own address.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cr_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('cr_applications', 'ip_address')) {
                $table->string('ip_address', 45)->nullable()->after('address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cr_applications', function (Blueprint $table) {
            if (Schema::hasColumn('cr_applications', 'ip_address')) {
                $table->dropColumn('ip_address');
            }
        });
    }
};
