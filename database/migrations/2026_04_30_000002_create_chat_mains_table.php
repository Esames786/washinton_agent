<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMainsTable extends Migration
{
    public function up()
    {
        Schema::create('chat_mains', function (Blueprint $table) {
            $table->id();
            $table->integer('thread_id')->unsigned();
            $table->longText('send_message')->nullable();
            $table->longText('receive_message')->nullable();
            $table->string('info_data', 255)->nullable();
            $table->string('ip_address', 255);
            $table->date('date_created');
            $table->timestamps();
            $table->tinyInteger('read_it')->default(1);
            $table->tinyInteger('read_it_c')->default(1);
            $table->string('admin_name', 50)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chat_mains');
    }
}
