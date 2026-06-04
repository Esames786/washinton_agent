<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ringcentral_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('ringcentral_messages', 'attachments')) {
                $table->json('attachments')->nullable()->after('message_body');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ringcentral_messages', function (Blueprint $table) {
            if (Schema::hasColumn('ringcentral_messages', 'attachments')) {
                $table->dropColumn('attachments');
            }
        });
    }
};
