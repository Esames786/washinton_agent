<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommissionRangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commission_ranges', function (Blueprint $table) {
            $table->increments('id');
            $table->double('from_order',20,2)->default(0);
            $table->double('to_order',20,2)->default(0);
            $table->double('from_avg',20,2)->default(0);
            $table->double('to_avg',20,2)->default(0);
            $table->double('commission',20,2)->default(0);
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
        Schema::dropIfExists('commission_ranges');
    }
}
