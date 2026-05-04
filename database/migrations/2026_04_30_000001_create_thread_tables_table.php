<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThreadTablesTable extends Migration
{
    public function up()
    {
        Schema::create('thread_tables', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address')->nullable();
            $table->integer('user_id')->nullable()->index();
            $table->string('email', 50)->nullable();
            $table->string('name', 50)->nullable();
            $table->tinyInteger('replied')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('thread_tables');
    }
}
