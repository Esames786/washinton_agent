<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_chats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->nullable();
            $table->integer('from_user_id')->nullable();
            $table->integer('to_user_id')->nullable();
            $table->integer('approved_by_user_id')->nullable();
            $table->longText('message')->nullable();
            $table->string('message_type')->default('text')->nullable();
            $table->string('message_date')->nullable();
            $table->string('message_time')->nullable();
            $table->tinyInteger('status')->default(0)->nullable()->comment('0 => message sent, 1 => approve/deliver, 2 => seen');
            $table->timestamp('datetime_for_approver')->nullable();
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
        Schema::dropIfExists('custom_chats');
    }
}
