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
        Schema::create('ringcentral_users', function (Blueprint $table) {
    $table->id();
    $table->unsignedInteger('user_id');
    $table->string('phone_number')->nullable();
    $table->longText('access_token')->nullable();
    $table->longText('refresh_token')->nullable();
    $table->timestamp('token_expires_at')->nullable();
    $table->timestamp('refresh_token_expires_at')->nullable();
    $table->string('extension_id')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();

    // $table->foreign('user_id')
    //     ->references(columns: 'id')
    //     ->on('user')
    //     ->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ringcentral_users');
    }
};
