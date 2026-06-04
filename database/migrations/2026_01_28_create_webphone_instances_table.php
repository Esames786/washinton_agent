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
        Schema::create('webphone_instances', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->unique(); // UNIQUE: 1-1 relation with user
            $table->string('instance_id', 100)->unique(); // UUID from frontend
            $table->string('authorization_id', 100)->nullable()->index(); // R-Dialer auth ID
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('last_seen_at')->useCurrent()->useCurrentOnUpdate(); // Auto-update on each request
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate(); // For Eloquent timestamps
            $table->timestamp('closed_at')->nullable()->index(); // When instance was explicitly closed
            
            // Additional index for activity window queries
            $table->index('last_seen_at');
            
            // CONSTRAINT: Only 1 instance per user (1-1 relation enforced at DB level)
            // Note: Foreign key omitted due to type compatibility issues
            // user_id references user table's id column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webphone_instances');
    }
};
