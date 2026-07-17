<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Campaigns/jobs are moved from hard-coded arrays into a managed table so the
 * CrazyRays application form and the admin can drive them dynamically.
 *
 * employment_category separates the two applicant tracks:
 *   - work_from_home : campaign-based, Commission Only (→ HR Subcontractor Management)
 *   - in_house       : on-site jobs, all pay types    (→ HR On-Site Process)
 *
 * `key` is the stable internal slug (e.g. "healthcare") kept backward-compatible
 * with the existing string values stored on cr_applications.campaign.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cr_campaigns')) return;

        Schema::create('cr_campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key', 60)->unique();                 // stable slug
            $table->string('name', 150);                         // display label
            $table->string('description', 255)->nullable();
            $table->string('icon', 16)->nullable();              // emoji for the card
            $table->enum('employment_category', ['work_from_home', 'in_house'])->default('work_from_home');
            $table->text('allowed_shifts')->nullable();          // JSON array of shift ids/keys; null = all
            $table->string('default_pay_type', 30)->nullable();  // e.g. commission_only
            $table->tinyInteger('status')->default(1);           // 1 = active
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['employment_category', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cr_campaigns');
    }
};
