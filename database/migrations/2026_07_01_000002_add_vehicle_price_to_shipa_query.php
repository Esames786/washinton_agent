<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVehiclePriceToShipaQuery extends Migration
{
    /**
     * Vehicle price captured on the public "Get a Quote" lead form. Carried over
     * to the `order` table's vehicle_price column when the lead is converted
     * (shipa1_queryAssignDirect).
     */
    public function up()
    {
        if (Schema::hasTable('shipa_query') && !Schema::hasColumn('shipa_query', 'vehicle_price')) {
            Schema::table('shipa_query', function (Blueprint $table) {
                $table->string('vehicle_price')->nullable()->after('model');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('shipa_query') && Schema::hasColumn('shipa_query', 'vehicle_price')) {
            Schema::table('shipa_query', function (Blueprint $table) {
                $table->dropColumn('vehicle_price');
            });
        }
    }
}
