<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * #8: Remove clause 11 ("Independent Relationship & Third-Party Disclaimer") from the
 * default contract template — it also drives the CrazyRays job-application T&C
 * (publicDefaultContract). The closing "By accepting this contract…" acknowledgment
 * paragraph is preserved. Idempotent: no-op if the clause is already gone.
 */
return new class extends Migration
{
    public function up(): void
    {
        $rows = DB::table('contract_templates')
            ->where('content', 'like', '%11. Independent Relationship%')
            ->get(['id', 'content']);

        foreach ($rows as $row) {
            // Strip from "<h3>11. Independent Relationship …" up to (not including) the
            // styled acknowledgment paragraph, keeping that acknowledgment intact.
            $new = preg_replace(
                '#\s*<h3>\s*11\.\s*Independent Relationship.*?(?=<p style="margin-top:24px;">)#is',
                "\n\n",
                $row->content
            );

            if ($new !== null && $new !== $row->content) {
                DB::table('contract_templates')->where('id', $row->id)->update(['content' => $new]);
            }
        }
    }

    public function down(): void
    {
        // No-op: clause removal is a deliberate content change, not reversible here.
    }
};
