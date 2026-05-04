<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransferQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_quotes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('original_user_id');
            $table->integer('transferred_user_id');
            $table->tinyInteger('old_pstatus');
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
        Schema::dropIfExists('transfer_quotes');
    }
}
