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
        Schema::create('shipper_details_assign_carriers', function (Blueprint $table) {
            $table->increments('id');

            $table->string('state', 255);
            $table->string('type', 255)->nullable()->default('5');

            $table->unsignedBigInteger('orderTaker');
            $table->string('recordsAllowed', 255);

            $table->integer('status')->default(1);

            $table->timestamps();

            // Optional: useful index
            $table->index(['orderTaker']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipper_details_assign_carriers');
    }
};
