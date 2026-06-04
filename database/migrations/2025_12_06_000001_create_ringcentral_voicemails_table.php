<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ringcentral_voicemails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ringcentral_user_id');
            $table->string('voicemail_id', 191)->unique();
            $table->string('phone_number')->nullable();
            $table->string('caller_name')->nullable();
            $table->string('caller_number')->nullable();
            $table->integer('duration_seconds')->default(0);
            $table->longText('transcription')->nullable();
            $table->string('audio_file_path')->nullable();
            $table->text('audio_url')->nullable();
            $table->string('message_status')->default('unread'); // unread, listened, archived
            $table->timestamp('received_at')->nullable();
            $table->timestamps();

            // $table->foreign('ringcentral_user_id')
            //     ->references('id')
            //     ->on('ringcentral_users')
            //     ->onDelete('cascade');

            $table->index('ringcentral_user_id');
            $table->index('message_status');
            $table->index('received_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ringcentral_voicemails');
    }
};
