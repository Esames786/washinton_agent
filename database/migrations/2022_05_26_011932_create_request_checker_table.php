<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceCheckersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_checker', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('price_checker_id');
            $table->float('carrier_price1')->nullable();
            $table->float('carrier_price2')->nullable();
            $table->float('carrier_price3')->nullable();
            $table->float('carrier_price4')->nullable();
            $table->float('carrier_price5')->nullable();
            $table->tinyint('is_read')->default(0);
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
        Schema::dropIfExists('request_checker');
    }
}
