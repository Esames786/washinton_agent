<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('email_message_attachments')) return;

        Schema::create('email_message_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('email_message_id')->index();
            $table->unsignedInteger('attachment_index')->nullable();
            $table->string('disk')->default('public');
            $table->string('path');
            $table->string('original_name');
            $table->string('mime_type')->nullable();
            $table->bigInteger('size')->nullable();
            $table->timestamps();

            $table->index(['email_message_id', 'attachment_index'], 'ema_message_index_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_message_attachments');
    }
};
