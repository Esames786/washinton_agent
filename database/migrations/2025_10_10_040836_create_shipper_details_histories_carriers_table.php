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
        Schema::create('shipper_details_histories_carriers', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('company_id');

            $table->string('connectStatus', 255);
            $table->longText('comment');

            $table->timestamps();

            // Optional: indexes for quick lookups
            $table->index(['user_id']);
            $table->index(['company_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipper_details_histories_carriers');
    }
};
