<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('email_folders')) return;

        Schema::create('email_folders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('email_account_id')->index();
            $table->string('key', 50);
            $table->string('label', 100);
            $table->string('imap_name', 255);
            $table->unsignedInteger('cached_unread')->default(0);
            $table->unsignedInteger('cached_total')->default(0);
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();

            $table->unique(['email_account_id', 'key']);
            $table->foreign('email_account_id')
                ->references('id')->on('email_accounts')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_folders');
    }
};
