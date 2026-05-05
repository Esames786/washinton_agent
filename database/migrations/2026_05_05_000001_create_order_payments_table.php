<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('order_payments')) {
            return;
        }

        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_mode', 100)->nullable()->comment('e.g. ClickPay, COD, bank_transfer, cheque, wire');
            $table->string('payment_type', 200)->nullable();
            $table->string('pay_type')->nullable();
            $table->string('pay_on')->nullable();
            $table->string('pay_from')->nullable();

            // Link to order table (order.id)
            $table->unsignedBigInteger('order_id')->nullable()->index()->comment('FK to order.id');
            $table->string('listing_order_id')->nullable()->comment('Ref_ID / order reference string');

            // Agent who submitted this payment (user.id)
            $table->unsignedBigInteger('user_id')->nullable()->index()->comment('FK to user.id (agent)');

            $table->enum('payment_status', ['Payment Pending', 'Payment Return', 'Payment Confirmed'])
                ->default('Payment Pending');

            $table->decimal('book_price', 14, 2)->nullable()->comment('Amount charged to customer (USD)');
            $table->decimal('carrier_price', 14, 2)->nullable()->comment('Amount paid to carrier (USD)');
            $table->decimal('profit', 14, 2)->nullable()->comment('book_price - carrier_price (USD)');

            $table->date('confirmation_date')->nullable();
            $table->text('details')->nullable();
            $table->text('screenshot_path')->nullable();

            // Used by HR commission calculation — set to 1 after commission is processed
            $table->tinyInteger('is_paid')->default(0)->comment('1 = commission already calculated for this payment');

            // Admin who confirmed/returned
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->text('admin_remarks')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_payments');
    }
};
