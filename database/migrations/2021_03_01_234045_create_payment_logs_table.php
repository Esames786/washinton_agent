<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_logs', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('orderId');
			$table->string('pay_type',10);
			$table->string('pay_location',10);
			$table->string('pay_method',40);
			$table->string('cash_status',40);
			$table->string('card_type',60);
			$table->string('card_number',80);
			$table->string('card_security',20);
			$table->string('expiry_date',20);
			$table->string('certified_cheque_no',80);
			$table->string('paypal_id',80);
			$table->string('bank_id',80);
			$table->string('pay_confirmed',30);
			$table->integer('amount');
			$table->string('add_information',250);
			
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
        Schema::dropIfExists('payment_logs');
    }
}
