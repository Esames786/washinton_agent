<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Seed the previously hard-coded campaigns into cr_campaigns (all Work From Home,
 * matching the current remote flow) plus one default In-House / On-Site job, then
 * backfill existing cr_applications with campaign_id + employment_type.
 *
 * Production-safe & idempotent: campaigns are upserted by key; applications are
 * only backfilled where employment_type is still NULL. Historical values are
 * never lost. "general" (No Campaign) is intentionally kept as Work From Home,
 * not converted to In-House.
 */
return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        // Work From Home campaigns (current public campaigns).
        $wfh = [
            ['key' => 'healthcare',    'name' => 'Healthcare Products & Services',    'icon' => '🏥'],
            ['key' => 'home_security', 'name' => 'Home Security Solutions',           'icon' => '🔐'],
            ['key' => 'real_estate',   'name' => 'Real Estate / Mortgage Lead Gen',   'icon' => '🏠'],
            ['key' => 'dme',           'name' => 'DME — Durable Medical Equipment',   'icon' => '♿'],
            ['key' => 'logistics',     'name' => 'Logistics / Trucking Dispatch',     'icon' => '🚛'],
            ['key' => 'software',      'name' => 'Software Development',               'icon' => '💻'],
            ['key' => 'amazon',        'name' => 'Amazon',                            'icon' => '📦'],
            ['key' => 'general',       'name' => 'General Application',               'icon' => '📋'],
        ];

        $sort = 0;
        foreach ($wfh as $c) {
            DB::table('cr_campaigns')->updateOrInsert(
                ['key' => $c['key']],
                [
                    'name'                => $c['name'],
                    'icon'                => $c['icon'],
                    'employment_category' => 'work_from_home',
                    'default_pay_type'    => 'commission_only',
                    'allowed_shifts'      => null,
                    'status'              => 1,
                    'sort_order'          => $sort++,
                    'updated_at'          => $now,
                    'created_at'          => $now,
                ]
            );
        }

        // Default In-House / On-Site job so the in-house track works immediately.
        DB::table('cr_campaigns')->updateOrInsert(
            ['key' => 'onsite_general'],
            [
                'name'                => 'On-Site / In-House',
                'icon'                => '🏢',
                'employment_category' => 'in_house',
                'default_pay_type'    => null,   // all pay types allowed
                'allowed_shifts'      => null,   // all shifts
                'status'              => 1,
                'sort_order'          => 100,
                'updated_at'          => $now,
                'created_at'          => $now,
            ]
        );

        // Map any legacy "inhouse" application key (from the earlier in-house card) to the seeded in-house job.
        $keyToId = DB::table('cr_campaigns')->pluck('id', 'key');
        $inHouseId = $keyToId['onsite_general'] ?? null;

        // Backfill existing applications: link campaign_id + set employment_type once.
        DB::table('cr_applications')
            ->whereNull('employment_type')
            ->orderBy('id')
            ->chunkById(500, function ($rows) use ($keyToId, $inHouseId) {
                foreach ($rows as $row) {
                    $campaignKey = $row->campaign;

                    if ($campaignKey === 'inhouse') {
                        $campaignId      = $inHouseId;
                        $employmentType  = 'in_house';
                    } else {
                        $campaignId     = $keyToId[$campaignKey] ?? null;
                        // Everything else historically belonged to the remote campaign flow.
                        $employmentType = 'work_from_home';
                    }

                    DB::table('cr_applications')->where('id', $row->id)->update([
                        'campaign_id'     => $campaignId,
                        'employment_type' => $employmentType,
                    ]);
                }
            });
    }

    public function down(): void
    {
        // Clear backfilled links (campaigns rows are left in place — harmless).
        DB::table('cr_applications')->update(['campaign_id' => null, 'employment_type' => null]);
    }
};
