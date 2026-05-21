<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create('cr_applications', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Personal info
            $table->string('full_name', 100);
            $table->string('father_name', 100)->nullable();
            $table->string('national_id', 50)->nullable();
            $table->date('dob')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            $table->string('email', 150);
            $table->string('phone', 30);
            $table->string('country', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('address', 255)->nullable();

            // Campaign info
            $table->string('campaign', 60);   // slug: healthcare, home_security, real_estate, dme, logistics, software, amazon
            $table->string('shift_type', 100)->nullable();
            $table->string('pay_type', 50)->nullable();
            $table->text('additional_info')->nullable();
            $table->text('campaign_experience')->nullable();

            // Files
            $table->string('resume_path', 500)->nullable();
            $table->json('documents')->nullable();   // [{doc_id, title, path, filename, is_required}]

            // Contract
            $table->timestamp('contract_accepted_at')->nullable();

            // Password (hashed) — only set for Logistics/HelloTransport campaign
            $table->string('password')->nullable();

            // Status
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_note')->nullable();

            // Set when Convert to User is run
            $table->unsignedBigInteger('agent_id')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('campaign');
            $table->index('email');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cr_applications');
    }
}
