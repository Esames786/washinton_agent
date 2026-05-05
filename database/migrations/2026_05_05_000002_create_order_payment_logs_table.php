<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('order_payment_logs')) {
            return;
        }

        Schema::create('order_payment_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id')->index();
            $table->unsignedBigInteger('changed_by')->nullable();
            $table->string('user_type')->nullable()->comment('user or admin');

            // Old values snapshot
            $table->string('old_payment_mode')->nullable();
            $table->string('old_payment_type')->nullable();
            $table->decimal('old_book_price', 14, 2)->nullable();
            $table->decimal('old_carrier_price', 14, 2)->nullable();
            $table->decimal('old_profit', 14, 2)->nullable();
            $table->text('old_details')->nullable();
            $table->date('old_confirmation_date')->nullable();
            $table->string('old_payment_status')->nullable();

            $table->string('action_type')->default('update')->comment('create, update, delete, status_change');
            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_payment_logs');
    }
};
