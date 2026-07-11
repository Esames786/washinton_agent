<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * B6 — freeze the signup permission defaults so new signups no longer clone
 * reference users 130 (Order Taker) / 53 (Carrier/Dispatcher) at runtime.
 *
 * The payloads below are the EXACT permission columns those reference users held
 * (captured 2026-07-11), so signup behaviour is preserved. `emp_panel_access` is
 * kept as a base list, but the city-based panel assignment on signup overrides the
 * active panel. Admin can edit these rows later.
 *
 *   php artisan db:seed --class=B6SignupDefaultsSeeder
 */
class B6SignupDefaultsSeeder extends Seeder
{
    public function run()
    {
        $defaults = [
            [
                'role_key' => 'order_taker',
                'label'    => 'Order Taker (Agent)',
                'role_id'  => 2,
                'payload'  => [
                    'emp_access_phone'   => '0,1,2,3,4,5,6,7,8,66,9,10,11,12,13,14,18,19,27,28,29,30,31,37,42,47,49,54,56,68,71,72,74,111,75,79,105,109,110,116,117,122,134,135,149,150',
                    'emp_access_web'     => '0,1,2,3,4,5,6,7,8,66,9,10,11,12',
                    'emp_access_test'    => '0,18,117,134,135,149,1,2,3,4,5,6,7,8,66,9,10,11,12',
                    'panel_type_4'       => '0,1,2,3,4,5,6,7,8,66,9,10,11,12',
                    'panel_type_5'       => '0,1,2,3,4,5,6,7,8,66,9,10,11,12',
                    'panel_type_6'       => '0,1,2,3,4,5,6,7,8,66,9,10,11,12',
                    'emp_panel_access'   => '1,3',
                    'emp_show_data'      => '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,23,17,18,19',
                    'emp_access_ship'    => '9,10,34,30,11,31,32,12,19,14',
                    'emp_access_profile' => '0,1,2,3,4,5,6,7,18,8,9,10,30,11,31,32,12,13,14,19,20,21',
                    'emp_access_action'  => '2,4,5,7,8,9,19,20,21,108,111',
                    'emp_access_report'  => '',
                    'emp_access_guide'   => '3,4,6,5,7,8,9,10,11,12,27,28,40,53',
                    'order_taker_quote'  => '0',
                    'assign_daily_qoute' => '0',
                    'sheet_access'       => '',
                ],
            ],
            [
                'role_key' => 'dispatcher',
                'label'    => 'Dispatcher (Carrier)',
                'role_id'  => 3,
                'payload'  => [
                    'emp_access_phone'   => '9,10,11,12,14,17,24,29,32,60,53,54,55,56,64,65,74,79,101,102,103,116,117,134,143,136,137,138,139',
                    'emp_access_web'     => '9,10,11,12,14,17,24,29,53,54,55,56,116,134',
                    'emp_access_test'    => '',
                    'panel_type_4'       => '',
                    'panel_type_5'       => '',
                    'panel_type_6'       => '',
                    'emp_panel_access'   => '1,2',
                    'emp_show_data'      => '12,13,14,15,16,23,17,19',
                    'emp_access_ship'    => '9,10,34,30,11,31,12,20,21,23,24,25,26,27,28,29',
                    'emp_access_profile' => '9,10,30,11,31,32,12,13,14,19,21',
                    'emp_access_action'  => '1,4,5,8,11,12,13,20',
                    'emp_access_report'  => '9,10,34,30,11,20,21,22,23,24,25,26,27,28,33,35',
                    'emp_access_guide'   => '3,4,6,5,7,8,9,10,11,12,29,38,31,32,33,34,35,36,37,44',
                    'order_taker_quote'  => '0',
                    'assign_daily_qoute' => '0',
                    'sheet_access'       => '',
                ],
            ],
        ];

        foreach ($defaults as $d) {
            DB::table('signup_defaults')->updateOrInsert(
                ['role_key' => $d['role_key']],
                [
                    'label'      => $d['label'],
                    'role_id'    => $d['role_id'],
                    'payload'    => json_encode($d['payload']),
                    'status'     => 1,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
