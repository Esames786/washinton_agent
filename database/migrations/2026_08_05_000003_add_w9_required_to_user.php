<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Round-2 #5: W-9 becomes admin-ASSIGNED like the NDA — the admin sends it from the
 * subcontractor view screen; the agent then sees, completes and signs it. Guarded/idempotent.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user', function (Blueprint $table) {
            if (!Schema::hasColumn('user', 'w9_required')) {
                $table->tinyInteger('w9_required')->default(0);
            }
        });

        // Absolute URL of the generated W-9 PDF so the HR panel (different cPanel account)
        // can link to it regardless of which portal produced the file.
        Schema::table('w9_forms', function (Blueprint $table) {
            if (!Schema::hasColumn('w9_forms', 'document_url')) {
                $table->string('document_url', 255)->nullable()->after('document_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            if (Schema::hasColumn('user', 'w9_required')) {
                $table->dropColumn('w9_required');
            }
        });
    }
};
