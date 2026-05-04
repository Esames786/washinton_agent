<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipper_details_phone_carriers', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('approachId');

            $table->integer('type')->default(1);
            $table->integer('status')->default(1);

            $table->timestamps();

            // Optional: add indexes if you’ll query by these
            $table->index(['userId']);
            $table->index(['approachId']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipper_details_phone_carriers');
    }
};
