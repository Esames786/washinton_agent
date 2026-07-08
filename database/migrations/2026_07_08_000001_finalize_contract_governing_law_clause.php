<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Client final wording (2026-07-08) for the default contract governing-law clause
 * (served to crazyrayssolutions.com.pk via /api/default-contract, stored in DB).
 * Replaces any earlier variant (US "State of Florida", or the interim "Sindh Karachi
 * Court" text) with the final Karachi jurisdiction sentence. Reversible-safe & idempotent.
 */
class FinalizeContractGoverningLawClause extends Migration
{
    private string $final = 'All claims, litigation, or legal action arising out of this agreement must be filed in Karachi Sindh Court only.';

    public function up(): void
    {
        foreach (DB::table('contract_templates')->get() as $tpl) {
            $content = (string) $tpl->content;

            // Replace whatever currently sits in the paragraph after the "Governing Law" heading
            // with the final wording — robust across every prior variant (Florida, interim, etc).
            $new = preg_replace(
                '/(Governing Law<\/h3>\s*<p>)(.*?)(<\/p>)/s',
                '${1}' . $this->final . '${3}',
                $content
            );

            if ($new !== null && $new !== $content) {
                DB::table('contract_templates')->where('id', $tpl->id)->update(['content' => $new]);
            }
        }
    }

    public function down(): void
    {
        // One-way content normalization; no meaningful rollback.
    }
}
