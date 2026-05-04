<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSellInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sell_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice_number')->nullable();
            $table->string('date')->nullable();
            $table->string('inventory_id')->nullable();
            $table->string('sale_person')->nullable();
            $table->string('cname')->nullable();
            $table->string('cemail')->nullable();
            $table->string('cphone')->nullable();
            $table->string('year')->nullable();
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('ymk')->nullable();
            $table->string('vin_number')->nullable();
            $table->string('sale_price')->nullable();
            $table->string('total_amount')->nullable();
            $table->string('balance')->nullable();
            $table->longText('additional')->nullable();
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
        Schema::dropIfExists('sell_invoices');
    }
}
