<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * IRS Form W-9 submissions (Hello Transport / US agents).
 *
 * The agent fills the form online during onboarding; we keep the submitted values plus the
 * e-signature, so the PDF can always be regenerated from the database (same approach as the NDA —
 * a missing file can never lose the record).
 *
 * The TIN is the sensitive part: it is stored ENCRYPTED (Laravel Crypt) and never rendered in
 * full — admin screens show only the last 4 digits.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('w9_forms')) {
            return;
        }

        Schema::create('w9_forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->index();          // agent (user table)
            $table->unsignedBigInteger('hr_employee_id')->nullable()->index();

            // Line 1–2
            $table->string('legal_name');                          // name on the income tax return
            $table->string('business_name')->nullable();            // DBA / disregarded entity

            // Line 3 — federal tax classification
            $table->string('tax_classification', 50);               // individual|c_corp|s_corp|partnership|trust_estate|llc|other
            $table->string('llc_tax_class', 5)->nullable();         // C, S or P when classification = llc
            $table->string('other_classification')->nullable();

            // Line 4 — exemptions (rarely used by individuals)
            $table->string('exempt_payee_code', 10)->nullable();
            $table->string('fatca_code', 10)->nullable();

            // Line 5–7 — address + optional account numbers
            $table->string('address');
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('zip', 20);
            $table->string('account_numbers')->nullable();

            // Part I — TIN (encrypted at rest; last4 kept plain for display/search)
            $table->string('tin_type', 10);                         // ssn | ein
            $table->text('tin_encrypted');
            $table->string('tin_last4', 4)->nullable();

            // Part II — certification
            $table->longText('signature');                          // data-URL of the drawn signature
            $table->string('signed_ip', 45)->nullable();
            $table->timestamp('signed_at')->nullable();

            // Generated PDF (regenerated on demand if the file goes missing)
            $table->string('document_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('w9_forms');
    }
};
