<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Onsite panel (client, 22 Oct 2026). Set up the way docs/ONSITE-PANEL.sql sets it up live: a
 * panel row, the panel in the agent's emp_panel_access, its permissions in user_panel_access, and
 * the agent sitting in it (user_settings.penal_type). Runs against the local hello_devtest copy.
 */
class OnsitePanelTest extends TestCase
{
    private int $panel;
    private array $users = [];
    private array $dealers = [];

    protected function setUp(): void
    {
        parent::setUp();
        $_SERVER['REQUEST_URI'] = '/autos_approach';   // the page's title reads the PHP global, which a web server always sets
        $this->panel = DB::table('panel_types')->insertGetId([
            'name' => 'Onsite test ' . uniqid(), 'is_system' => 0, 'is_default' => 0, 'sort' => 99, 'status' => 1,
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    protected function tearDown(): void
    {
        DB::table('used_new_car_dealers')->whereIn('id', $this->dealers)->delete();
        DB::table('user_panel_access')->whereIn('user_id', $this->users)->delete();
        DB::table('user_settings')->whereIn('user_id', $this->users)->delete();
        DB::table('user_devices')->whereIn('user_id', $this->users)->delete();
        DB::table('user')->whereIn('id', $this->users)->delete();
        DB::table('panel_types')->where('id', $this->panel)->delete();
        unset($_SERVER['REQUEST_URI']);
        parent::tearDown();
    }

    /** An order taker on the Onsite panel with the given permissions there (and none on Lahore). */
    private function onsiteAgent(string $onsiteAccess): User
    {
        $u = User::findOrFail(373)->replicate();
        $u->forceFill([
            'email' => 'onsite-test-' . uniqid() . '@example.test', 'role' => 2, 'status' => 1, 'deleted' => 0,
            'nda_required' => 0, 'ip_check_enabled' => 0, 'current_session_id' => null, 'device_policy' => 'off',
            'order_taker_quote' => 0, 'emp_panel_access' => '1,' . $this->panel, 'emp_access_phone' => '1,2,3',
            'is_time' => now(),   // AllRoles signs out agents whose session started 10+ hours ago
        ])->save();
        $this->users[] = $u->id;
        DB::table('user_panel_access')->insert([
            'user_id' => $u->id, 'panel_type_id' => $this->panel, 'access_ids' => $onsiteAccess,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('user_settings')->insert(['user_id' => $u->id, 'penal_type' => $this->panel, 'created_at' => now(), 'updated_at' => now()]);

        return $u->fresh();
    }

    private const SIDEBAR_LINK = '<span class="side-menu__label">Autos Approach</span>';

    public function test_the_autos_approach_page_opens_and_the_folder_shows_on_onsite_with_94(): void
    {
        $agent = $this->onsiteAgent('1,2,3,94');
        $this->dealers[] = DB::table('used_new_car_dealers')->insertGetId([
            'user_id' => $agent->id, 'name' => 'Onsite Test Motors', 'state' => 'TX', 'category' => 'Used Car Dealer',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->actingAs($agent)->get('/autos_approach')
            ->assertOk()
            ->assertSee(self::SIDEBAR_LINK, false)
            ->assertSee('Onsite Test Motors');
    }

    public function test_a_manager_can_assign_businesses_to_an_agent_on_the_onsite_job_type(): void
    {
        $agent = $this->onsiteAgent('1,2,3,94');
        $agent->forceFill(['role' => DB::table('roles')->where('name', 'Onsite')->value('id')])->save();
        $manager = $this->onsiteAgent('1,2,3,94,145');
        $manager->forceFill(['role' => 9])->save();

        $this->actingAs($manager->fresh())->get('/autos_approach')
            ->assertOk()
            ->assertSee('<option value="' . $agent->id . '">', false);
    }

    public function test_the_edit_screen_offers_all_quotes_and_the_auto_approach_permissions(): void
    {
        $agent = $this->onsiteAgent('1,2,3');
        DB::table('user')->where('id', 1)->update(['current_session_id' => null]);

        $html = $this->actingAs(User::findOrFail(1))->get('/edit_subcontractor/' . $agent->id)->assertOk()->getContent();

        $this->assertStringContainsString('id="all_qoute"', $html);
        $this->assertStringNotContainsString(':has(#all_qoute)', $html);

        // Wrapper of a permission checkbox in this agent's Onsite modal: hidden or not?
        $hidden = function (int $code) use ($html): bool {
            $name = 'panel_access_' . $this->panel;
            $this->assertMatchesRegularExpression('/id="' . $name . '_' . $code . '"/', $html);
            preg_match('/<div class="col-sm-6 soft-hidden-perm"([^>]*)>\s*<input type="checkbox"\s+name="' . $name . '\[\]"\s+id="' . $name . '_' . $code . '"/', $html, $m);

            return str_contains($m[1] ?? 'missing', 'display:none');
        };
        $this->assertFalse($hidden(94));
        $this->assertFalse($hidden(145));
        $this->assertFalse($hidden(146));
        $this->assertTrue($hidden(93));   // still hidden (control)
    }

    public function test_the_folder_stays_hidden_without_94_or_on_another_panel(): void
    {
        $without = $this->onsiteAgent('1,2,3');
        $this->actingAs($without)->get('/autos_approach')->assertOk()->assertDontSee(self::SIDEBAR_LINK, false);

        $with = $this->onsiteAgent('1,2,3,94');
        DB::table('user_settings')->where('user_id', $with->id)->update(['penal_type' => 1]);   // now on Lahore
        $this->actingAs($with)->get('/autos_approach')->assertOk()->assertDontSee(self::SIDEBAR_LINK, false);
    }
}
