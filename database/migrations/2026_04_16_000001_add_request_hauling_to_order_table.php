<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRequestHaulingToOrderTable extends Migration
{
    public function up()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->tinyInteger('request_hauling')->default(0)->after('paneltype');
        });
    }

    public function down()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn('request_hauling');
        });
    }
}
