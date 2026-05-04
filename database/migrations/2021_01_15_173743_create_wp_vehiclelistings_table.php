<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWpVehiclelistingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wp_vehiclelistings', function (Blueprint $table) {
            $table->increments('vehicleid');
            $table->string('make',200);
            $table->string('model',200);
            $table->integer('UserId');
            $table->integer('size');
            $table->integer('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wp_vehiclelistings');
    }
}
