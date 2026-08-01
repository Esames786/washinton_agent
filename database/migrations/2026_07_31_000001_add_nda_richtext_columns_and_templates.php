<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * NDA redesign: move the NDA from a fixed PDF template to the same editable rich-text
 * model the Contract already uses.
 *
 *  - hr_employees.nda_content   : the admin's editable NDA HTML for this agent
 *  - hr_employees.nda_signature : the agent's signature (data URL) captured at signing
 *  - hr_employees.nda_signed_ip : the agent's IP at signing
 *  - hr_employees.nda_cnic_front / nda_cnic_back : CNIC images captured at signing
 *  - nda_templates              : default NDA body (mirrors contract_templates), seeded
 *                                 from the previous nda/pdf.blade content, tokenised so
 *                                 App\Support\Brand::applyTokens() brands it per portal.
 *
 * Everything is guarded so it is safe to run on a database that already has some of it.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hr_employees', function (Blueprint $table) {
            if (!Schema::hasColumn('hr_employees', 'nda_content'))    $table->longText('nda_content')->nullable()->after('nda_document_url');
            if (!Schema::hasColumn('hr_employees', 'nda_signature'))  $table->longText('nda_signature')->nullable()->after('nda_content');
            if (!Schema::hasColumn('hr_employees', 'nda_signed_ip'))  $table->string('nda_signed_ip', 45)->nullable()->after('nda_signature');
            if (!Schema::hasColumn('hr_employees', 'nda_cnic_front')) $table->string('nda_cnic_front', 255)->nullable()->after('nda_signed_ip');
            if (!Schema::hasColumn('hr_employees', 'nda_cnic_back'))  $table->string('nda_cnic_back', 255)->nullable()->after('nda_cnic_front');
            // Details the agent fills on the NDA sign form (shown above the signature + in the summary).
            if (!Schema::hasColumn('hr_employees', 'nda_father_name')) $table->string('nda_father_name', 255)->nullable()->after('nda_cnic_back');
            if (!Schema::hasColumn('hr_employees', 'nda_address'))     $table->string('nda_address', 500)->nullable()->after('nda_father_name');
        });

        if (!Schema::hasTable('nda_templates')) {
            Schema::create('nda_templates', function (Blueprint $table) {
                $table->id();
                $table->string('title')->default('Non-Disclosure Agreement');
                $table->longText('content')->nullable();
                $table->boolean('is_default')->default(false);
                $table->timestamps();
            });
        }

        // Seed the default NDA template once.
        if (DB::table('nda_templates')->where('is_default', true)->doesntExist()) {
            DB::table('nda_templates')->insert([
                'title'      => 'Non-Disclosure Agreement (NDA) & Confidentiality Acknowledgment',
                'content'    => $this->defaultNdaHtml(),
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('hr_employees', function (Blueprint $table) {
            foreach (['nda_content', 'nda_signature', 'nda_signed_ip', 'nda_cnic_front', 'nda_cnic_back', 'nda_father_name', 'nda_address'] as $col) {
                if (Schema::hasColumn('hr_employees', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
        // Intentionally NOT dropping nda_templates on down() to preserve edited content.
    }

    /**
     * Default NDA body — tokenised with {{COMPANY_NAME}} so Brand::applyTokens() brands it.
     * The signature / details block is appended at render time, not stored here.
     */
    private function defaultNdaHtml(): string
    {
        return <<<'HTML'
<p>This Non-Disclosure Agreement ("Agreement") is entered into between <strong>{{COMPANY_NAME}}</strong> ("Company") and the undersigned Employee/Contractor ("Recipient").</p>

<h4>1. Confidential Information</h4>
<p>The Recipient acknowledges that during employment or engagement with {{COMPANY_NAME}}, they may have access to confidential and proprietary information including but not limited to:</p>
<ul>
    <li>Client lists and customer information</li>
    <li>Pricing, rates, commissions, and contracts</li>
    <li>Freight brokerage and dispatching data</li>
    <li>Sales scripts, leads, and marketing strategies</li>
    <li>Company processes, SOPs, and business methods</li>
    <li>Financial information and internal reports</li>
    <li>Employee information and company records</li>
</ul>

<h4>2. Non-Disclosure Obligation</h4>
<p>The Recipient agrees not to disclose, copy, distribute, sell, share, or use confidential information for any purpose other than performing authorized duties for the Company.</p>

<h4>3. Data Security</h4>
<p>The Recipient shall maintain the confidentiality of all company information and protect all company files, documents, credentials, and systems from unauthorized access.</p>

<h4>4. Return of Company Property</h4>
<p>Upon termination of employment or engagement, the Recipient shall immediately return all company property, documents, files, equipment, passwords, and confidential materials.</p>

<h4>5. Non-Solicitation</h4>
<p>The Recipient agrees not to directly solicit or divert Company clients, customers, carriers, employees, or business opportunities during employment and for a period of <strong>12 months</strong> after separation.</p>

<h4>6. Breach of Agreement</h4>
<p>Any breach of this Agreement may result in disciplinary action, termination of employment, legal action, and claims for damages as permitted by applicable law.</p>

<h4>7. Account Suspension</h4>
<p>{{COMPANY_NAME}} reserves the right to suspend or terminate the Recipient's account and access at any time, without prior notice, upon detection of any suspicious, unauthorized, or fraudulent activity.</p>

<h4>8. Termination</h4>
<p>The confidentiality obligations contained in this Agreement shall survive the termination of employment and remain in effect indefinitely unless otherwise required by law.</p>

<h4>9. Acknowledgment</h4>
<p>I acknowledge that I have read, understood, and agree to comply with the terms of this NDA &amp; Confidentiality Agreement and understand the consequences of violating its provisions.</p>
HTML;
    }
};
