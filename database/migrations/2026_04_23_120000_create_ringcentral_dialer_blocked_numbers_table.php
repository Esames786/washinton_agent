<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ringcentral_dialer_blocked_numbers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ringcentral_user_id')->index();
            $table->string('normalized_e164', 20)->index();
            $table->string('raw_input', 40)->nullable();
            $table->text('reason')->nullable();
            $table->unsignedBigInteger('blocked_by_user_id')->nullable()->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->unique(
                ['ringcentral_user_id', 'normalized_e164', 'is_active'],
                'rc_dialer_block_unique_active'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ringcentral_dialer_blocked_numbers');
    }
};

