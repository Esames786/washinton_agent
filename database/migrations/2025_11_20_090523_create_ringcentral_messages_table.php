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
        Schema::create('ringcentral_messages', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('ringcentral_user_id');
    $table->string('message_id', 191)->unique();
    $table->string('from_name')->nullable();
    $table->string('to_name')->nullable();
    $table->string('from_number')->nullable();
    $table->string('to_number')->nullable();
    $table->longText('message_body')->nullable();
    $table->string('direction'); // inbound, outbound
    $table->string('status')->nullable();
    $table->timestamp('sent_at')->nullable();
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
        Schema::dropIfExists('ringcentral_messages');
    }
};
