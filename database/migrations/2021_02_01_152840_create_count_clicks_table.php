<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountClicksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('count_clicks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('pstatus');
            $table->string('client_email',50);
            $table->string('client_name',50);
            $table->integer('total_clicks');
            $table->integer('user_id');
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
        Schema::dropIfExists('count_clicks');
    }
}
