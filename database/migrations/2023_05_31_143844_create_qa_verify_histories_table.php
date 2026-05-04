<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQaVerifyHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qa_verify_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0);
            $table->integer('order_id')->default(0);
            $table->integer('pstatus')->default(0);
            $table->integer('verify')->default(0);
            $table->integer('no_of_calls')->default(0);
            $table->integer('negative')->default(0);
            $table->integer('negative_to_user_id')->default(0);
            $table->text('decision')->nullable();
            $table->longText('remarks')->nullable();
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
        Schema::dropIfExists('qa_verify_histories');
    }
}
