<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Batch 6 (D2): single active session per user. Stores the id of the currently-authorized
 * session; the EnforceUserSecurity middleware logs out any session whose id differs
 * (i.e. the older device is kicked when the user logs in on a new one).
 */
class AddCurrentSessionIdToUser extends Migration
{
    public function up(): void
    {
        Schema::table('user', function (Blueprint $table) {
            if (!Schema::hasColumn('user', 'current_session_id')) {
                $table->string('current_session_id', 100)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            if (Schema::hasColumn('user', 'current_session_id')) {
                $table->dropColumn('current_session_id');
            }
        });
    }
}
