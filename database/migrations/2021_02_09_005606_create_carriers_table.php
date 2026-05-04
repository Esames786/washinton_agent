<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarriersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carriers', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('orderId');
			$table->string('companyname',200);
            $table->string('location',200);
			$table->string('mcno',50);
			$table->string('companyphoneno',100);
			$table->string('driverphoneno',100);
			$table->date('est_pickupdate');
			$table->date('est_deliverydate');
			$table->integer('askprice');
			$table->string('opt_insurance',3);
			$table->string('opt_negative',3);
			$table->string('negative_no',10);
			$table->string('comments',200);
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
        Schema::dropIfExists('carriers');
    }
}
