<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Client request (2026-07-06): the default employee contract (served to
 * crazyrayssolutions.com.pk via /api/default-contract) had a US "State of Florida"
 * governing-law clause. Replace it with a Sindh/Karachi jurisdiction clause.
 *
 * The contract text is stored in DB (contract_templates.content), not in a file, so
 * this data migration performs a targeted, reversible string replace.
 */
class UpdateContractGoverningLawClause extends Migration
{
    private string $old = 'This Agreement shall be governed by and construed in accordance with the laws of the State of Florida, United States of America.';
    private string $new = 'All claims, litigation, or legal action arising out of this agreement must be filed in Sindh Karachi Court.';

    public function up(): void
    {
        $this->replaceClause($this->old, $this->new);
    }

    public function down(): void
    {
        $this->replaceClause($this->new, $this->old);
    }

    private function replaceClause(string $from, string $to): void
    {
        foreach (DB::table('contract_templates')->get() as $tpl) {
            if (strpos((string) $tpl->content, $from) !== false) {
                DB::table('contract_templates')
                    ->where('id', $tpl->id)
                    ->update(['content' => str_replace($from, $to, $tpl->content)]);
            }
        }
    }
}
