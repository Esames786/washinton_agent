<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageChatsTable extends Migration
{
    public function up()
    {
        Schema::create('message_chats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('order_id');
            $table->longText('message');
            $table->longText('description');
            $table->integer('status');
            $table->timestamp('message_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('message_chats');
    }
}
