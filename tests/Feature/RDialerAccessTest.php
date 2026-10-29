<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Florida, 29 Oct 2026: "the call goes to the old app when I refresh or change page". Only two pages
 * passed the agent's permissions to the R-Dialer script; on the rest an R-Dialer agent fell back to the
 * desktop-app links. The script now works the permissions out itself from the agent's current panel.
 */
class RDialerAccessTest extends TestCase
{
    private array $made = [];

    protected function tearDown(): void
    {
        DB::table('user_settings')->whereIn('user_id', $this->made)->delete();
        DB::table('user_panel_access')->whereIn('user_id', $this->made)->delete();
        DB::table('user')->whereIn('id', $this->made)->delete();
        parent::tearDown();
    }

    private function agent(int $panel, string $panelOneAccess): User
    {
        $u = User::findOrFail(373)->replicate();
        $u->forceFill(['email' => 'rdialer-' . uniqid() . '@example.test', 'role' => 2, 'emp_access_phone' => $panelOneAccess])->save();
        $this->made[] = $u->id;
        DB::table('user_settings')->insert(['user_id' => $u->id, 'penal_type' => $panel, 'call_type' => 134]);

        return $u->fresh();
    }

    private function accessList(): array
    {
        $js = view('partials.ringcentral_js_helpers')->render();
        $this->assertMatchesRegularExpression('/var phoneaccessArray = (\[.*?\]);/', $js);
        preg_match('/var phoneaccessArray = (\[.*?\]);/', $js, $m);

        return json_decode($m[1], true);
    }

    public function test_pages_that_pass_nothing_still_see_r_dialer(): void
    {
        $this->actingAs($this->agent(1, '1,134,169'));

        $this->assertContains('169', $this->accessList());
    }

    public function test_it_uses_the_panel_the_agent_is_on(): void
    {
        $u = $this->agent(12, '1,134');                // R-Dialer granted on Onsite only
        $u->setPanelAccess(12, '94,134,169');
        $this->actingAs($u->fresh());

        $this->assertContains('169', $this->accessList());
    }

    public function test_an_agent_without_r_dialer_keeps_the_app_links(): void
    {
        $this->actingAs($this->agent(1, '1,134'));

        $this->assertNotContains('169', $this->accessList());
    }
}
