<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storages', function (Blueprint $table) {
            $table->increments('id');
			$table->string('company_name',250);
			$table->string('manager_owner_name',250);
			$table->string('company_address',250);
			$table->string('zip')->nullable();
			$table->string('open_time',20);
			$table->string('close_time',20);
			$table->string('charges');
			$table->string('charges2')->nullable();
			$table->string('forklift_price')->nullable();
			$table->string('tow_truck_price')->nullable();
			$table->string('phoneno',50);
			$table->string('phoneno2',50)->nullable();
			$table->string('faxno',50);
			$table->string('storage_duration',40);
			$table->string('forklift_twotruck',30);
			$table->longText('additional')->nullable();
			$table->string('state')->nullable();
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
        Schema::dropIfExists('storages');
    }
}
