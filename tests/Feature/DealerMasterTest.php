<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Dealer Master on florida (client, 29 Oct 2026): Washington's screen brought over, with view /
 * assign / report (181/182/183) checked on the agent's own panel, including city panels 7+.
 * Runs against the local test copy.
 */
class DealerMasterTest extends TestCase
{
    private array $made = [];
    private array $dealers = [];

    protected function setUp(): void
    {
        parent::setUp();
        if (! Schema::hasTable('dealer_masters')) {
            (require base_path('database/migrations/2026_09_23_000001_create_dealer_master_tables.php'))->up();
        }
        foreach (['Test Motors Baltimore' => 1, 'Test Cars No Phone' => 0] as $name => $hasPhone) {
            $this->dealers[] = DB::table('dealer_masters')->insertGetId([
                'name' => $name, 'states' => 'MD', 'city' => 'Baltimore', 'phone' => $hasPhone ? '4105550100' : null,
                'has_phone' => $hasPhone, 'user_id' => 0, 'type' => 6, 'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }

    protected function tearDown(): void
    {
        DB::table('dealer_masters')->whereIn('id', $this->dealers)->delete();
        DB::table('dealer_master_assigns')->whereIn('orderTaker', $this->made)->delete();
        DB::table('user_settings')->whereIn('user_id', $this->made)->delete();
        DB::table('user_devices')->whereIn('user_id', $this->made)->delete();
        DB::table('user_panel_access')->whereIn('user_id', $this->made)->delete();
        DB::table('user')->whereIn('id', $this->made)->delete();
        unset($_SERVER['REQUEST_URI']);
        parent::tearDown();
    }

    private function user(int $role, int $panel, string $panelOneAccess): User
    {
        $u = User::findOrFail(373)->replicate();
        $u->forceFill([
            'email' => 'dm-' . uniqid() . '@example.test', 'role' => $role, 'status' => 1, 'deleted' => 0,
            'emp_access_phone' => $panelOneAccess, 'emp_panel_access' => '1,' . $panel,
            // The login gates florida's other tests also switch off (NDA, IP, device, 10-hour session).
            'nda_required' => 0, 'ip_check_enabled' => 0, 'current_session_id' => null, 'device_policy' => 'off', 'is_time' => now(),
        ])->save();
        $this->made[] = $u->id;
        DB::table('user_settings')->insert(['user_id' => $u->id, 'penal_type' => $panel, 'call_type' => 134]);

        return $u->fresh();
    }

    public function test_a_manager_sees_the_list_assign_screen_and_report(): void
    {
        $manager = $this->user(9, 1, '1');

        foreach (['/dealer-master', '/dealer-master/all', '/dealer-master/report'] as $path) {
            $_SERVER['REQUEST_URI'] = $path;
            $this->actingAs($manager)->get($path)->assertOk();
        }
        $_SERVER['REQUEST_URI'] = '/dealer-master';
        $this->actingAs($manager)->get('/dealer-master')->assertSee('Test Motors Baltimore')->assertSee('Dealer Master');
    }

    public function test_an_onsite_agent_with_181_on_that_panel_gets_in(): void
    {
        $agent = $this->user(2, 12, '1');
        $agent->setPanelAccess(12, '94,134,181');
        $_SERVER['REQUEST_URI'] = '/dealer-master';

        $this->actingAs($agent->fresh())->get('/dealer-master')->assertOk()->assertSee('DEALER MASTER');
    }

    public function test_an_agent_without_181_is_turned_away(): void
    {
        $agent = $this->user(2, 1, '1,134');
        $_SERVER['REQUEST_URI'] = '/dealer-master';

        $this->actingAs($agent)->get('/dealer-master')->assertRedirect('/dashboard');
    }

    public function test_assigning_hands_out_only_unassigned_dealers_with_phones(): void
    {
        $manager = $this->user(9, 1, '1');
        $agent = $this->user(2, 1, '1,181');

        $this->actingAs($manager)->post('/dealer-master/assign', [
            'orderTaker' => $agent->id, 'state' => ['MD'], 'recordsAllowed' => 5, 'w_phone2' => 1,
        ])->assertRedirect();

        $this->assertSame((int) $agent->id, (int) DB::table('dealer_masters')->where('id', $this->dealers[0])->value('user_id'));
        $this->assertSame(0, (int) DB::table('dealer_masters')->where('id', $this->dealers[1])->value('user_id'));
    }

    /** Florida, 31 Oct: "50" left agents with 93 / 100 because every Save added another batch per state. */
    public function test_records_is_the_whole_list_and_a_resave_replaces_it(): void
    {
        foreach (['ZZ', 'ZZ', 'ZZ', 'ZY', 'ZY'] as $i => $st) {
            $this->dealers[] = DB::table('dealer_masters')->insertGetId(['name' => "Queue $i", 'states' => $st, 'phone' => '1',
                'has_phone' => 1, 'user_id' => 0, 'type' => 6, 'created_at' => now(), 'updated_at' => now()]);
        }
        $manager = $this->user(9, 1, '1');
        $agent = $this->user(2, 1, '1,181');
        $held = fn (string $st = null) => DB::table('dealer_masters')->where('user_id', $agent->id)
            ->when($st, fn ($q) => $q->where('states', $st))->count();
        $assign = fn (array $states, int $n) => $this->actingAs($manager)->post('/dealer-master/assign',
            ['orderTaker' => $agent->id, 'state' => $states, 'recordsAllowed' => $n, 'w_phone2' => 1]);

        $assign(['ZZ', 'ZY'], 2)->assertSessionHas('success');
        $this->assertSame(2, $held());                     // 2 in all, not 2 per state

        $assign(['ZZ', 'ZY'], 2);
        $this->assertSame(2, $held());                     // saving again does not add 2 more

        $assign(['ZY'], 2);
        $this->assertSame(0, $held('ZZ'));                 // the old state went back to the pool
        $this->assertSame(2, $held('ZY'));
    }
}
