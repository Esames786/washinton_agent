<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_price_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedBigInteger('requested_by')->nullable()->index();

            $table->string('origin_zip', 10)->nullable()->index();
            $table->string('destination_zip', 10)->nullable()->index();
            $table->string('origin_state', 10)->nullable()->index();
            $table->string('destination_state', 10)->nullable()->index();
            $table->string('vehicle_key', 120)->nullable()->index();

            $table->decimal('driver_low', 10, 2)->nullable();
            $table->decimal('driver_mid', 10, 2)->nullable();
            $table->decimal('driver_high', 10, 2)->nullable();

            $table->decimal('offer_open', 10, 2)->nullable();
            $table->decimal('offer_enclosed', 10, 2)->nullable();
            $table->decimal('commission', 10, 2)->nullable();

            $table->boolean('cache_hit')->default(false);

            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_price_requests');
    }
};
