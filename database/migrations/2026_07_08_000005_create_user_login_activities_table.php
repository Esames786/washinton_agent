<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * #11 (2026-07-08): capture each user login (IP + timestamp + source) so admins/managers
 * can view an employee's login activity. Show-only (no IP whitelist/blocking).
 */
class CreateUserLoginActivitiesTable extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('user_login_activities')) {
            Schema::create('user_login_activities', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id')->index();
                $table->string('ip_address', 45)->nullable();
                $table->string('source', 20)->default('hello'); // hello | crazyrays
                $table->string('user_agent', 512)->nullable();
                $table->timestamp('logged_in_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_login_activities');
    }
}
