<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateContractTemplatesTable extends Migration
{
    public function up()
    {
        Schema::create('contract_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->longText('content');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        DB::table('contract_templates')->insert([
            'title'      => 'Standard Employee Contract',
            'is_default' => true,
            'content'    => '<h2>EMPLOYEE AGREEMENT</h2>
<p>This Employment Agreement ("Agreement") is entered into between <strong>Hello Transport LLC</strong> ("Company") and the employee identified in their portal profile ("Employee").</p>

<h3>1. Position and Duties</h3>
<p>Employee agrees to perform the duties assigned by the Company in good faith and to the best of their abilities. Duties may include but are not limited to: order taking, customer service, dispatch coordination, and related logistics activities.</p>

<h3>2. Compensation</h3>
<p>Employee shall receive compensation as outlined in their offer letter or as communicated by the HR department. Compensation may include base salary, commission, or a combination thereof, subject to applicable taxes and deductions.</p>

<h3>3. Work Schedule</h3>
<p>Employee is expected to adhere to the shift schedule assigned at the time of onboarding. Any changes to the schedule must be approved in advance by a supervisor or HR representative.</p>

<h3>4. Confidentiality</h3>
<p>Employee agrees to keep confidential all proprietary business information, customer data, carrier relationships, and internal pricing. This obligation survives termination of employment for a period of two (2) years.</p>

<h3>5. Code of Conduct</h3>
<p>Employee agrees to maintain professional conduct at all times, treat customers and colleagues with respect, and comply with all Company policies as published in the employee handbook or communicated by management.</p>

<h3>6. Non-Solicitation</h3>
<p>During employment and for twelve (12) months thereafter, Employee shall not directly solicit Company customers, carriers, or employees for a competing business.</p>

<h3>7. Intellectual Property</h3>
<p>All work product created by Employee in the course of employment, including but not limited to scripts, templates, processes, and software configurations, is the sole property of the Company.</p>

<h3>8. At-Will Employment</h3>
<p>Employment is at-will and may be terminated by either party at any time, with or without cause, subject to any applicable notice periods agreed upon in writing.</p>

<h3>9. Governing Law</h3>
<p>This Agreement shall be governed by and construed in accordance with the laws of the State of Florida, United States of America.</p>

<h3>10. Entire Agreement</h3>
<p>This Agreement constitutes the entire understanding between the parties and supersedes all prior oral or written agreements. Amendments must be in writing and signed by both parties.</p>

<p style="margin-top:24px;">By accepting this contract through the employee portal, Employee acknowledges that they have read, understood, and agree to be bound by the terms set forth above.</p>',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('contract_templates');
    }
}
