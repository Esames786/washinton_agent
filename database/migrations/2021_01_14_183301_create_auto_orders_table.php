<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutoOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('originzip');
            $table->string('originstate',50);
            $table->string('origincity',50);
            $table->integer('oterminal');
            $table->string('oauction',50);
            $table->string('oauctionpho',50);
            $table->string('obuyer_no',50);
            $table->string('oaddress',80);
            $table->string('oaddress2',80);
            $table->string('oname',50);
            $table->string('oemail',50);
            $table->string('ophone',50);


            $table->integer('destinationzip');
            $table->string('destinationstate',50);
            $table->string('destinationcity',50);
            $table->integer('dterminal');
            $table->string('dauction',50);
            $table->string('dauctionpho',50);

            $table->string('daddress',80);
            $table->string('daddress2',80);
            $table->string('dname',50);
            $table->string('demail',50);
            $table->string('dphone',50);



            $table->integer('vehicle_opt');
            $table->string('vin_num',50);
            $table->string('year',50);
            $table->string('make',50);
            $table->string('model',50);
            $table->string('type',20);
            $table->string('condition',10);
            $table->string('transport',10);
            $table->text('add_info');
            $table->integer('port_title');
            $table->date('pickup_date');
            $table->date('delivery_date');
            $table->string('pickup_when',10);
            $table->string('delivery_when',10);

            $table->integer('payment');
            $table->string('booking_confirm',10);
            $table->string('company_name',50);
            $table->integer('company_price');
            $table->string('company_comments');
            $table->integer('need_deposit');
            $table->integer('deposit_amount');
            $table->integer('pay_carrier');
            $table->integer('cod_cop');
            $table->integer('balance');
            $table->string('payment_method',15);
            $table->string('balance_method',15);
            $table->string('cod_cop_loc',15);
            $table->string('balance_time',15);
            $table->string('terms',30);
            $table->string('carrier_status',15);
            $table->text('additional_2');
            $table->integer('pstatus');
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
        Schema::dropIfExists('auto_orders');
    }
}
