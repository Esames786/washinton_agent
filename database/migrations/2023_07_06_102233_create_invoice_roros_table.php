<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceRorosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_roros', function (Blueprint $table) {
            $table->increments('id');
            $table->string('year');
            $table->string('make');
            $table->string('model');
            $table->string('form_address');
            $table->string('too_address');
            $table->double('transportation_fees',15,1)->nullable();
            $table->double('auction_storage_fees',15,1)->nullable();
            $table->double('yard_storage_fees',15,1)->nullable();
            $table->double('yard_forklift_fees',15,1)->nullable();
            $table->double('extra_other_fees',15,1)->nullable();
            $table->double('redelivery_fees',15,1)->nullable();
            $table->double('shipping_fees',15,1)->nullable();
            $table->double('non_runner_fees',15,1)->nullable();
            $table->double('forklift_fees',15,1)->nullable();
            $table->double('telex_fees',15,1)->nullable();
            $table->string('delivered_port')->nullable();
            $table->string('vessel_grimaldi_salluam')->nullable();
            $table->string('bill_name')->nullable();
            $table->string('bill_address')->nullable();
            $table->string('vin')->nullable();
            $table->double('paid_amount',15,1)->nullable();
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
        Schema::dropIfExists('invoice_roros');
    }
}
