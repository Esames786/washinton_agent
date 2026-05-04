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
        Schema::create('profile_cards', function (Blueprint $table) {
            $table->id();

            // Personal Info
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('oemail')->nullable();
            $table->string('ophone')->nullable();
            $table->string('ophone2')->nullable();
            $table->string('oaddress')->nullable();
            $table->string('originzsc')->nullable();

            // Payment & Business
            $table->string('payment_method')->nullable();
            $table->string('customer_type')->nullable();
            $table->string('business_name')->nullable();
            $table->text('nature_of_customer')->nullable();

            // Auction 1
            $table->string('auction_1_terminal')->nullable();
            $table->string('auction_1_title')->nullable();
            $table->string('auction_1_name')->nullable();
            $table->string('auction_1_phone')->nullable();
            $table->string('auction_1_account')->nullable(); // Yes/No
            $table->string('auction_1_account_name')->nullable();
            $table->string('auction_1_buyer_no')->nullable();
            $table->string('auction_1_gate_pass_pin')->nullable();
            $table->string('auction_1_lot_no')->nullable();

            // Auction 2
            $table->string('auction_2_terminal')->nullable();
            $table->string('auction_2_title')->nullable();
            $table->string('auction_2_name')->nullable();
            $table->string('auction_2_phone')->nullable();
            $table->string('auction_2_account')->nullable();
            $table->string('auction_2_account_name')->nullable();
            $table->string('auction_2_buyer_no')->nullable();
            $table->string('auction_2_gate_pass_pin')->nullable();
            $table->string('auction_2_lot_no')->nullable();

            // Auction 3
            $table->string('auction_3_terminal')->nullable();
            $table->string('auction_3_title')->nullable();
            $table->string('auction_3_name')->nullable();
            $table->string('auction_3_phone')->nullable();
            $table->string('auction_3_account')->nullable();
            $table->string('auction_3_account_name')->nullable();
            $table->string('auction_3_buyer_no')->nullable();
            $table->string('auction_3_gate_pass_pin')->nullable();
            $table->string('auction_3_lot_no')->nullable();

            // Quotes and Follow-up
            $table->string('recent_follow_up')->nullable();
            $table->dateTime('first_quote_date')->nullable();
            $table->string('first_quote_status')->nullable();
            $table->string('first_quote_agent')->nullable();
            $table->dateTime('recent_quote_date')->nullable();
            $table->string('recent_quote_status')->nullable();
            $table->string('recent_quote_agent')->nullable();

            // Miscellaneous
            $table->text('history')->nullable();
            $table->text('customer_review')->nullable();
            $table->decimal('average_margin', 8, 2)->nullable();
            $table->integer('customer_rating')->nullable();
            $table->string('social_profile_name')->nullable();
            $table->tinyInteger('panel_type')->default(1);

            $table->timestamps();
        });



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_cards');
    }
};
