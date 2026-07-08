<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Client request (2026-07-08): the default contract (served to crazyrayssolutions.com.pk)
 * must state that subcontractors have no association with HelloTransport and that
 * HelloTransport is only a brokerage. Inserts a disclaimer clause before the closing
 * acknowledgement paragraph. Idempotent (skips if already present).
 */
class AddDisclaimerToContract extends Migration
{
    private string $marker = 'no employment association or relationship with HelloTransport';

    public function up(): void
    {
        $clause = "\n<h3>11. Independent Relationship &amp; Third-Party Disclaimer</h3>\n"
            . "<p>HelloTransport operates solely as a brokerage company; CrazyRays users and subcontractors have no dependency on, or engagement with, HelloTransport.</p>\n"
            . "<p>Subcontractors have no employment association or relationship with HelloTransport.</p>\n";

        foreach (DB::table('contract_templates')->get() as $tpl) {
            $content = (string) $tpl->content;
            if (strpos($content, $this->marker) !== false) {
                continue; // already added
            }

            // Insert the clause just before the closing "By accepting..." acknowledgement.
            if (strpos($content, 'By accepting this contract') !== false) {
                $new = preg_replace(
                    '/(<p[^>]*>By accepting this contract)/',
                    $clause . '$1',
                    $content,
                    1
                );
            } else {
                $new = $content . $clause; // fallback: append at end
            }

            if ($new !== null && $new !== $content) {
                DB::table('contract_templates')->where('id', $tpl->id)->update(['content' => $new]);
            }
        }
    }

    public function down(): void
    {
        // Content-only change; no structural rollback.
    }
}
