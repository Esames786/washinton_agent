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
        Schema::create('shipper_details_carriers', function (Blueprint $table) {
            $table->increments('id');

            $table->tinyInteger('type')
                ->default(5)
                ->comment('1=shipper,2=carrier,3=broker,4=dealer,5=carrier2');

            $table->string('email', 191)->nullable();
            $table->string('name', 191)->nullable();
            $table->string('business_type', 191)->nullable();
            $table->string('other_details', 191)->nullable();
            $table->text('address')->nullable();
            $table->string('states', 20)->nullable();
            $table->string('phone3', 191)->nullable();
            $table->string('phone2', 191)->nullable();
            $table->string('phone', 191)->nullable();
            $table->integer('user_id')->default(0)->nullable();
            $table->string('website', 90)->nullable();
            $table->string('website2', 191)->nullable();

            $table->timestamps(); // creates created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipper_details_carriers');
    }
};
