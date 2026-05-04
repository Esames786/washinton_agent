<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTakerQouteAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_taker_qoute_accesses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('manager_id');
            $table->integer('ot_ids');
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('calling_status')->default(0);
            $table->datetinyInteger('from_date')->nullable();
            $table->datetinyInteger('to_date')->nullable();
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
        Schema::dropIfExists('order_taker_qoute_accesses');
    }
}
