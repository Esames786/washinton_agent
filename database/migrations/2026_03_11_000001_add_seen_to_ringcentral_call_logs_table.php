<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ringcentral_call_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('ringcentral_call_logs', 'is_seen')) {
                $table->boolean('is_seen')->default(false)->after('status');
            }
            if (!Schema::hasColumn('ringcentral_call_logs', 'seen_at')) {
                $table->timestamp('seen_at')->nullable()->after('is_seen');
            }
        });
        $indexes = DB::select("SHOW INDEX FROM ringcentral_call_logs WHERE Key_name = 'rc_call_logs_user_status_seen'");
        if (empty($indexes)) {
            DB::statement('ALTER TABLE ringcentral_call_logs ADD INDEX rc_call_logs_user_status_seen (ringcentral_user_id, status(20), is_seen)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ringcentral_call_logs', function (Blueprint $table) {
            $table->dropIndex(['ringcentral_user_id', 'status', 'is_seen']);
            $table->dropColumn(['is_seen', 'seen_at']);
        });
    }
};
