<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePortDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('port_details', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('delivery_address');
            $table->string('port_name');
            $table->string('terminal');
            $table->tinyInteger('make_sure')->default(0)->nullable();
            $table->tinyInteger('accident_vehicle')->default(0)->nullable();
            $table->string('address')->nullable();
            $table->string('zip')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('zsc')->nullable();
            $table->string('tel')->nullable();
            $table->tinyInteger('twic_card')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('port_details');
    }
}
