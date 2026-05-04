<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_feedbacks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->longText('feedback');
            $table->tinyInteger('rate');
            $table->tinyInteger('link_click')->default(0);
            $table->integer('user_id')->default(1);
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
        Schema::dropIfExists('order_feedbacks');
    }
}
