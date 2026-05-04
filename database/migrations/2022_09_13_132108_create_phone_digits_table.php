<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhoneDigitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phone_digits', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('hide_digits')->default(0);
            $table->tinyInteger('left_right_status')->default(0)->comment('0 mean left hide and 1 means right hide');
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
        Schema::dropIfExists('phone_digits');
    }
}
