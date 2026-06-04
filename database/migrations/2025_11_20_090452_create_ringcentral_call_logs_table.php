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
        Schema::create('ringcentral_call_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ringcentral_user_id');
            $table->string('call_id', 191)->unique();
            $table->string('from_number')->nullable();
            $table->string('to_number')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('direction'); // inbound, outbound
            $table->string('type')->default('voice');
            $table->integer('duration_seconds')->default(0);
            $table->string('status')->nullable();
            $table->text('recording_url')->nullable();
            $table->timestamp('call_started_at')->nullable();
            $table->timestamp('call_ended_at')->nullable();
            $table->timestamps();

            // $table->foreign('ringcentral_user_id')
            //     ->references('id')
            //     ->on('ringcentral_users')
            //     ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ringcentral_call_logs');
    }
};
