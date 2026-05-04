<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemardVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demard_vehicles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('email');
            $table->string('from_year')->nullable();
            $table->string('to_year')->nullable();
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->text('trim_level')->nullable();
            $table->string('mileage')->nullable();
            $table->string('car_color')->nullable();
            $table->string('interior_color')->nullable();
            $table->string('condition')->nullable();
            $table->string('title')->nullable();
            $table->string('body_condition')->nullable();
            $table->double('from_budget',15,2)->default(0);
            $table->double('to_budget',15,2)->default(0);
            $table->string('how_much_days')->nullable();
            $table->text('requirement')->nullable();
            $table->enum('payment_method',['Credit/Debit Card','Zelle','PayPal'])->nullable();
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('demard_vehicles');
    }
}
