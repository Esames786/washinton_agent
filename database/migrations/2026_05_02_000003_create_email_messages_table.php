<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('email_messages')) return;

        Schema::create('email_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('email_account_id')->index();
            $table->unsignedBigInteger('email_folder_id')->index();
            $table->unsignedBigInteger('uid')->index();
            $table->string('message_id', 255)->nullable()->index();
            $table->string('from_email', 190)->nullable()->index();
            $table->string('from_name', 190)->nullable();
            $table->string('to_email', 190)->nullable();
            $table->string('subject', 255)->nullable()->index();
            $table->timestamp('date_at')->nullable()->index();
            $table->boolean('seen')->default(false)->index();
            $table->boolean('has_attachments')->default(false);
            $table->string('snippet', 255)->nullable();
            $table->longText('body_html')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['email_folder_id', 'uid']);
            $table->foreign('email_account_id')
                ->references('id')->on('email_accounts')->cascadeOnDelete();
            $table->foreign('email_folder_id')
                ->references('id')->on('email_folders')->cascadeOnDelete();
        });

        Schema::table('email_messages', function (Blueprint $table) {
            $table->index(['email_folder_id', 'date_at']);
            $table->index(['email_account_id', 'seen']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_messages');
    }
};
