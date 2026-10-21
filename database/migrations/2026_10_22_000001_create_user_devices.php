<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Device restriction (client email 20 Oct 2026, item 7: "proceed with the proposal you shared").
 *
 * The CRM records every device (browser) an agent signs in from, and the admin sets a policy per
 * agent on the edit screen:
 *   off      — default. Devices are only recorded (approved automatically). Nothing changes.
 *   one      — one approved device. The first one is approved automatically; any other waits for
 *              an admin, who can approve it in place of the old one.
 *   approved — every new device waits for an admin.
 * A device is a long-lived random cookie; only its SHA-256 hash is stored here.
 *
 * Prod: run with --path on each database (Hello, and florida for the CrazyRays branch).
 */
return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('user') && ! Schema::hasColumn('user', 'device_policy')) {
            Schema::table('user', function (Blueprint $t) {
                $t->string('device_policy', 16)->default('off');
            });
        }

        if (! Schema::hasTable('user_devices')) {
            Schema::create('user_devices', function (Blueprint $t) {
                $t->engine = 'InnoDB';
                $t->bigIncrements('id');
                $t->unsignedInteger('user_id');
                $t->char('token_hash', 64);
                $t->string('label', 100)->nullable();
                $t->string('browser', 60)->nullable();
                $t->string('os', 60)->nullable();
                $t->string('user_agent', 500)->nullable();
                $t->string('first_ip', 45)->nullable();
                $t->string('last_ip', 45)->nullable();
                $t->dateTime('first_seen_at')->nullable();
                $t->dateTime('last_seen_at')->nullable();
                $t->string('status', 16)->default('pending'); // pending | approved | rejected | revoked
                $t->unsignedInteger('approved_by')->nullable();
                $t->dateTime('approved_at')->nullable();
                $t->unsignedInteger('revoked_by')->nullable();
                $t->dateTime('revoked_at')->nullable();
                $t->string('source', 20)->nullable(); // hello | crazyrays
                $t->timestamps();

                $t->unique(['user_id', 'token_hash']);
                $t->index(['status', 'user_id']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('user_devices');

        if (Schema::hasTable('user') && Schema::hasColumn('user', 'device_policy')) {
            Schema::table('user', function (Blueprint $t) {
                $t->dropColumn('device_policy');
            });
        }
    }
};
