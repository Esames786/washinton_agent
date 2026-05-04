<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('email_accounts')) {
            return;
        }

        Schema::create('email_accounts', function (Blueprint $table) {
            $table->id();
            // Link to washinton_agent user table
            $table->unsignedBigInteger('user_id')->nullable()->index();

            $table->string('email', 80)->unique();
            $table->string('local_part');
            $table->string('domain');
            $table->string('username');
            $table->text('password_enc');

            $table->string('imap_host')->nullable();
            $table->unsignedInteger('imap_port')->nullable();
            $table->string('imap_encryption')->nullable();

            $table->string('smtp_host')->nullable();
            $table->unsignedInteger('smtp_port')->nullable();
            $table->string('smtp_encryption')->nullable();

            $table->unsignedInteger('quota_mb')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_accounts');
    }
};
