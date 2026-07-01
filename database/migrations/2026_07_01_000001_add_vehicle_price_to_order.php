<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVehiclePriceToOrder extends Migration
{
    /**
     * Per-vehicle price for the vehicle information section. Stored as a
     * '*^'-delimited string (one price per vehicle), matching how the other
     * per-vehicle fields (year, make, model, car_link, ...) are stored on the
     * `order` table.
     */
    public function up()
    {
        if (!Schema::hasColumn('order', 'vehicle_price')) {
            Schema::table('order', function (Blueprint $table) {
                $table->text('vehicle_price')->nullable()->after('car_link');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('order', 'vehicle_price')) {
            Schema::table('order', function (Blueprint $table) {
                $table->dropColumn('vehicle_price');
            });
        }
    }
}
