<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstantQuotesTable extends Migration
{
    public function up()
    {
        Schema::create('instant_quotes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('origin_location')->nullable();
            $table->string('destination_location')->nullable();
            $table->string('type')->nullable();
            $table->string('year_make_model')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('platform_code')->default('hello-autohaul');
            $table->tinyInteger('is_autohaul')->default(1);
            $table->json('pricing_payload')->nullable();
            $table->decimal('driver_low_open', 10, 2)->nullable();
            $table->decimal('driver_mid_open', 10, 2)->nullable();
            $table->decimal('driver_high_open', 10, 2)->nullable();
            $table->decimal('offer_open', 10, 2)->nullable();
            $table->decimal('commission_open', 10, 2)->nullable();
            $table->boolean('cache_hit_open')->default(false);
            $table->decimal('driver_low_enclosed', 10, 2)->nullable();
            $table->decimal('driver_mid_enclosed', 10, 2)->nullable();
            $table->decimal('driver_high_enclosed', 10, 2)->nullable();
            $table->decimal('offer_enclosed', 10, 2)->nullable();
            $table->decimal('commission_enclosed', 10, 2)->nullable();
            $table->boolean('cache_hit_enclosed')->default(false);
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('order_taker_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('instant_quotes');
    }
}
