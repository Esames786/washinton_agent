<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * B6 — seed panel_types with the city names. Idempotent: keeps the numeric ids
 * identical to the existing paneltype integers so nothing is renumbered.
 *
 *   php artisan db:seed --class=B6PanelTypeSeeder
 */
class B6PanelTypeSeeder extends Seeder
{
    public function run()
    {
        // id => [name, is_system, is_default]
        $panels = [
            1  => ['Lahore',     0, 0],
            2  => ['Islamabad',  0, 0],
            3  => ['Testing',    1, 0], // system — do not rename/delete
            4  => ['Website',    1, 0], // system — hello/autohaul quotes land here
            5  => ['Rawalpindi', 0, 0],
            6  => ['Multan',     0, 0],
            7  => ['Bahawalpur', 0, 0],
            8  => ['Jhang',      0, 0],
            9  => ['Peshawar',   0, 0],
            10 => ['Karachi',    0, 1], // signup fallback panel
        ];

        foreach ($panels as $id => [$name, $isSystem, $isDefault]) {
            $exists = DB::table('panel_types')->where('id', $id)->first();

            if ($exists) {
                // Preserve any admin renames of NON-system panels; only ensure
                // system flags + a name for freshly seeded rows.
                DB::table('panel_types')->where('id', $id)->update([
                    'is_system'  => $isSystem,
                    'is_default' => $isDefault,
                    'sort'       => $id,
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('panel_types')->insert([
                    'id'         => $id,
                    'name'       => $name,
                    'is_system'  => $isSystem,
                    'is_default' => $isDefault,
                    'sort'       => $id,
                    'status'     => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
