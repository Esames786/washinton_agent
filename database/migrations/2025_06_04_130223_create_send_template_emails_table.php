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
        Schema::create('send_template_emails', function (Blueprint $table) {
            $table->id();
            $table->longText('query_sql');
            $table->tinyInteger('status')->default(0);
            $table->integer('user_id')->default(0)->index();
            $table->integer('data_type')->default(0)->index();
            $table->integer('template_id')->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('send_template_emails');
    }
};
