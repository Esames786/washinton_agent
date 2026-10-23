<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Florida (client, 23 Oct 2026): managers must be able to reach and edit subcontractor and
 * manager accounts. Runs against the local hello_devtest copy.
 */
class ManagerSubcontractorAccessTest extends TestCase
{
    private array $made = [];

    protected function setUp(): void
    {
        parent::setUp();
        $_SERVER['REQUEST_URI'] = '/dashboard';   // the layout's footer reads the PHP global
    }

    protected function tearDown(): void
    {
        unset($_SERVER['REQUEST_URI']);
        DB::table('user_devices')->whereIn('user_id', $this->made)->delete();
        DB::table('user_settings')->whereIn('user_id', $this->made)->delete();
        DB::table('user')->whereIn('id', $this->made)->delete();
        parent::tearDown();
    }

    /** A manager on Lahore (panel 1) WITHOUT permission 20 (Add/Edit Subcontractor). */
    private function manager(): User
    {
        $u = User::findOrFail(373)->replicate();
        $u->forceFill([
            'email' => 'mgr-test-' . uniqid() . '@example.test', 'role' => 9, 'status' => 1, 'deleted' => 0,
            'nda_required' => 0, 'ip_check_enabled' => 0, 'current_session_id' => null, 'device_policy' => 'off',
            'is_time' => now(), 'emp_panel_access' => '1', 'emp_access_phone' => '1,2,3',
        ])->save();
        $this->made[] = $u->id;
        DB::table('user_settings')->insert(['user_id' => $u->id, 'penal_type' => 1, 'created_at' => now(), 'updated_at' => now()]);

        return $u->fresh();
    }

    public function test_the_dashboard_nav_leads_a_manager_to_the_subcontractor_list(): void
    {
        $this->actingAs($this->manager())->get('/dashboard')->assertOk()
            ->assertSee('id="subcontractorDocsToggle"', false)
            ->assertSee(url('view_subcontractor'), false);
    }

    public function test_a_manager_can_open_another_managers_account_and_it_stays_a_manager(): void
    {
        $other = $this->manager();

        $html = $this->actingAs($this->manager())->get('/edit_subcontractor/' . $other->id)->assertOk()->getContent();

        // JOB TYPE keeps "Manager" selected (non-admins can't change it, and update keeps it).
        $this->assertMatchesRegularExpression('/<option\s+selected\s+value="9">\s*Manager\s*<\/option>/', $html);
        $this->assertStringContainsString('url: "/update_employee"', $html);   // the page saves by AJAX
    }
}
