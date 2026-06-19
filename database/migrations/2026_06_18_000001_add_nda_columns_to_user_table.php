<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->tinyInteger('nda_required')->default(0)->after('status');
            $table->timestamp('nda_signed_at')->nullable()->after('nda_required');
            $table->string('nda_document_path')->nullable()->after('nda_signed_at');
        });
    }

    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn(['nda_required', 'nda_signed_at', 'nda_document_path']);
        });
    }
};
