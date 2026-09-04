<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Client (2026-09-04): the four washington guides — ONLY these four — on Hello/CrazyRays.
 * Content exported from washington's `guide` table into resources/guides/*.html.
 * updateOrInsert by page_route (never truncate); afterwards every active user's
 * emp_access_guide gets the new guide ids appended (agents/managers/admins alike,
 * "like they were before"). Safe to re-run.
 */
class WashingtonGuidesSeeder extends Seeder
{
    public const GUIDES = [
        ['page_route' => 'luxury',             'page_name' => 'LUXURY VEHICLE GUIDE',      'guide_type' => 1, 'file' => 'luxury.html'],
        ['page_route' => 'non-luxury',         'page_name' => 'NON-LUXURY VEHICLE GUIDE',  'guide_type' => 1, 'file' => 'non_luxury.html'],
        ['page_route' => 'vehicle_body_type',  'page_name' => 'VEHICLE BODY TYPE GUIDE',   'guide_type' => 2, 'file' => 'vehicle_body_type.html'],
        ['page_route' => 'vehicle_condition',  'page_name' => 'VEHICLE CONDITIONS GUIDE',  'guide_type' => 2, 'file' => 'vehicle_condition.html'],
    ];

    public function run()
    {
        $ids = [];
        foreach (self::GUIDES as $g) {
            DB::table('guide')->updateOrInsert(
                ['page_route' => $g['page_route']],
                [
                    'page_name'     => $g['page_name'],
                    'guide_type'    => $g['guide_type'],
                    'guide_content' => file_get_contents(resource_path('guides/' . $g['file'])),
                    'deleted_at'    => null,
                    'updated_at'    => now(),
                ]
            );
            $ids[] = (string) DB::table('guide')->where('page_route', $g['page_route'])->value('id');
        }

        foreach (DB::table('user')->where('deleted', 0)->get(['id', 'emp_access_guide']) as $u) {
            $have = array_filter(explode(',', (string) $u->emp_access_guide));
            $merged = array_values(array_unique(array_merge($have, $ids)));
            if ($merged !== $have) {
                DB::table('user')->where('id', $u->id)->update(['emp_access_guide' => implode(',', $merged)]);
            }
        }
    }
}
