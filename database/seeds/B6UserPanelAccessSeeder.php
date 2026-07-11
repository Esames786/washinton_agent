<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * B6 — copy the 6 legacy per-user panel-access columns into user_panel_access.
 *
 *   panel 1 <- emp_access_phone
 *   panel 2 <- emp_access_web
 *   panel 3 <- emp_access_test
 *   panel 4 <- panel_type_4
 *   panel 5 <- panel_type_5
 *   panel 6 <- panel_type_6
 *
 * MUST run on prod BEFORE the User model's compatibility accessors are switched to
 * read from user_panel_access — otherwise the ~600 read sites would see empty access.
 * Idempotent (updateOrInsert on user_id+panel_type_id); safe to re-run.
 *
 *   php artisan db:seed --class=B6UserPanelAccessSeeder
 */
class B6UserPanelAccessSeeder extends Seeder
{
    /** panel id => legacy user column */
    private const COLUMN_MAP = [
        1 => 'emp_access_phone',
        2 => 'emp_access_web',
        3 => 'emp_access_test',
        4 => 'panel_type_4',
        5 => 'panel_type_5',
        6 => 'panel_type_6',
    ];

    public function run()
    {
        $columns = array_merge(['id'], array_values(self::COLUMN_MAP));

        DB::table('user')
            ->select($columns)
            ->orderBy('id')
            ->chunk(500, function ($users) {
                $now = now();
                foreach ($users as $user) {
                    foreach (self::COLUMN_MAP as $panelId => $col) {
                        DB::table('user_panel_access')->updateOrInsert(
                            ['user_id' => $user->id, 'panel_type_id' => $panelId],
                            ['access_ids' => $user->{$col}, 'updated_at' => $now, 'created_at' => $now]
                        );
                    }
                }
            });
    }
}
