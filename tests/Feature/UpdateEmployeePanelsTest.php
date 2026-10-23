<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Edit Subcontractor → Update with no panel ticked (florida, 23 Oct 2026): the save died with
 * "Undefined variable $total_emp_panel_access" and the page showed nothing. It must save and
 * keep the panels the account already has. Runs against the local hello_devtest copy.
 */
class UpdateEmployeePanelsTest extends TestCase
{
    private array $made = [];

    protected function tearDown(): void
    {
        DB::table('user_devices')->whereIn('user_id', $this->made)->delete();
        DB::table('user')->whereIn('id', $this->made)->delete();
        parent::tearDown();
    }

    public function test_saving_with_no_panel_ticked_keeps_the_panels(): void
    {
        $agent = User::findOrFail(373)->replicate();
        $agent->forceFill([
            'email' => 'panels-test-' . uniqid() . '@example.test', 'role' => 2, 'status' => 1, 'deleted' => 0,
            'emp_panel_access' => '1,10',
        ])->save();
        $this->made[] = $agent->id;

        DB::table('user')->where('id', 1)->update(['current_session_id' => null]);

        $this->actingAs(User::findOrFail(1))->post('/update_employee', [
            'user_id' => $agent->id, 'first_name' => 'Renamed', 'last_name' => 'Agent', 'slug' => 'renamed',
            'email' => $agent->email, 'phone_number' => '5550-1000000', 'job_type' => 2, 'address' => 'Somewhere',
            // no emp_panel_access[]: nothing ticked in "Panel Type Access"
        ])->assertOk()->assertSee('SUCCESS');

        $row = DB::table('user')->where('id', $agent->id)->first(['name', 'emp_panel_access']);
        $this->assertSame('Renamed', $row->name);
        $this->assertSame('1,10', $row->emp_panel_access);
    }
}
