<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ringcentral_dialer_blocked_numbers', function (Blueprint $table) {
            $table->string('ringcentral_rule_id', 64)->nullable()->after('blocked_by_user_id');
            $table->string('ringcentral_sync_status', 32)->nullable()->after('ringcentral_rule_id');
            $table->text('ringcentral_sync_error')->nullable()->after('ringcentral_sync_status');
            $table->timestamp('ringcentral_synced_at')->nullable()->after('ringcentral_sync_error');
        });
    }

    public function down(): void
    {
        Schema::table('ringcentral_dialer_blocked_numbers', function (Blueprint $table) {
            $table->dropColumn([
                'ringcentral_rule_id',
                'ringcentral_sync_status',
                'ringcentral_sync_error',
                'ringcentral_synced_at',
            ]);
        });
    }
};

