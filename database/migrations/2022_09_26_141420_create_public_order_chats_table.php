<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicOrderChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_order_chats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->nullable();
            $table->integer('public_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('approved_by_user_id')->nullable();
            $table->longText('message')->nullable();
            $table->string('message_type')->default('text')->nullable();
            $table->string('message_date')->nullable();
            $table->string('message_time')->nullable();
            $table->tinyInteger('status')->default(0)->nullable()->comment('0 => message sent, 1 => approve/deliver, 2 => seen by all users');
            $table->string('seen_by_user_id')->nullable()->comment('users ids who seen the message');
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
        Schema::dropIfExists('public_order_chats');
    }
}
