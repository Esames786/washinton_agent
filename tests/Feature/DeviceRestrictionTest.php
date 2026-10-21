<?php

namespace Tests\Feature;

use App\Support\DeviceGate;
use App\User;
use App\UserDevice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Device restriction (client email 20 Oct 2026, item 7). Runs against the local hello_devtest
 * copy. A tiny probe route behind the real `web` + `auth` middleware stands in for any page.
 */
class DeviceRestrictionTest extends TestCase
{
    private array $made = [];

    protected function setUp(): void
    {
        parent::setUp();
        Route::middleware(['web', 'auth'])->get('/_device_probe', fn () => 'ok');
        // JSON calls (getJson) leave cookies out unless asked; a browser's XHR sends them.
        $this->withCredentials();
    }

    protected function tearDown(): void
    {
        UserDevice::whereIn('user_id', $this->made)->delete();
        DB::table('user')->whereIn('id', $this->made)->delete();
        parent::tearDown();
    }

    private function person(int $role, string $policy = 'off'): User
    {
        $u = User::findOrFail(373)->replicate();     // a real row from live, so every column is valid
        $u->forceFill([
            'email' => 'device-test-' . uniqid() . '@example.test', 'role' => $role, 'status' => 1,
            'deleted' => 0, 'nda_required' => 0, 'ip_check_enabled' => 0, 'current_session_id' => null,
            'device_policy' => $policy,
        ])->save();
        $this->made[] = $u->id;

        return $u->fresh();
    }

    private function token(): string
    {
        return bin2hex(random_bytes(32));
    }

    private function probe(User $u, string $token, array $headers = [])
    {
        return $this->actingAs($u)->withUnencryptedCookie(DeviceGate::COOKIE, $token)->get('/_device_probe', $headers);
    }

    private function device(User $u, string $token): ?UserDevice
    {
        return UserDevice::where('user_id', $u->id)->where('token_hash', hash('sha256', $token))->first();
    }

    public function test_off_records_the_device_and_lets_it_in(): void
    {
        $agent = $this->person(2, 'off');
        $t = $this->token();

        $this->probe($agent, $t)->assertOk()->assertSee('ok');
        $this->assertSame(UserDevice::APPROVED, $this->device($agent, $t)->status);
    }

    public function test_a_browser_without_the_cookie_is_given_one_and_stays_the_same_device(): void
    {
        $agent = $this->person(2, 'off');

        $token = $this->actingAs($agent)->get('/_device_probe')->assertOk()->getCookie(DeviceGate::COOKIE, false)?->getValue();
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', (string) $token);

        $this->probe($agent, $token)->assertOk();
        $this->assertSame(1, UserDevice::where('user_id', $agent->id)->count());
    }

    public function test_approved_only_holds_a_new_device_until_an_admin_approves_it(): void
    {
        $agent = $this->person(2, 'approved');
        $t = $this->token();

        $this->probe($agent, $t)->assertRedirect('/device-pending');
        $this->assertSame(UserDevice::PENDING, $this->device($agent, $t)->status);

        // background calls get a 423, not a redirect
        $this->probe($agent, $t, ['X-Requested-With' => 'XMLHttpRequest', 'Accept' => 'application/json'])->assertStatus(423);

        // the waiting page and its status check stay reachable
        $this->actingAs($agent)->withUnencryptedCookie(DeviceGate::COOKIE, $t)->get('/device-pending')
            ->assertOk()->assertSee($this->device($agent, $t)->code());
        $this->actingAs($agent)->withUnencryptedCookie(DeviceGate::COOKIE, $t)->getJson('/device-status')
            ->assertOk()->assertJson(['allowed' => false, 'status' => 'pending']);

        DeviceGate::approve($this->device($agent, $t), User::findOrFail(1));
        $this->probe($agent, $t)->assertOk();
    }

    public function test_one_device_approves_the_first_and_replaces_it_on_approval(): void
    {
        $agent = $this->person(2, 'one');
        [$first, $second] = [$this->token(), $this->token()];

        $this->probe($agent, $first)->assertOk();
        $this->probe($agent, $second)->assertRedirect('/device-pending');

        DeviceGate::approve($this->device($agent, $second), User::findOrFail(1));

        $this->probe($agent, $second)->assertOk();
        $this->assertSame(UserDevice::REVOKED, $this->device($agent, $first)->status);
        $this->probe($agent, $first)->assertRedirect('/device-pending');
    }

    public function test_a_waiting_device_does_not_take_over_the_session(): void
    {
        $agent = $this->person(2, 'approved');
        DB::table('user')->where('id', $agent->id)->update(['current_session_id' => 'approved-pc-session']);
        $t = $this->token();

        // fresh login on a new device (the flag RecordLoginActivity sets)
        $this->actingAs($agent)->withSession(['b6_fresh_login' => true])
            ->withUnencryptedCookie(DeviceGate::COOKIE, $t)->get('/_device_probe')->assertRedirect('/device-pending');
        $this->actingAs($agent)->withSession(['b6_fresh_login' => true])
            ->withUnencryptedCookie(DeviceGate::COOKIE, $t)->get('/device-pending')->assertOk();

        $this->assertSame('approved-pc-session', DB::table('user')->where('id', $agent->id)->value('current_session_id'));
    }

    public function test_a_revoked_device_is_refused_even_when_the_policy_is_off(): void
    {
        $agent = $this->person(2, 'off');
        $t = $this->token();
        $this->probe($agent, $t)->assertOk();

        $this->device($agent, $t)->forceFill(['status' => UserDevice::REVOKED])->save();

        $this->probe($agent, $t)->assertRedirect('/device-pending');
        // the open page's nav poll gets a 423, which sends it to the waiting page
        $this->actingAs($agent)->withUnencryptedCookie(DeviceGate::COOKIE, $t)
            ->getJson('/nav-counts', ['X-Requested-With' => 'XMLHttpRequest'])->assertStatus(423);
    }

    public function test_admins_and_view_as_are_never_checked(): void
    {
        // The copied admin row carries live's session id; clear it so single-session lets us in.
        DB::table('user')->where('id', 1)->update(['current_session_id' => null]);
        $admin = User::findOrFail(1);
        $this->probe($admin, $this->token())->assertOk();
        $this->assertSame(0, UserDevice::where('user_id', 1)->count());

        $agent = $this->person(2, 'approved');
        $this->actingAs($agent)->withSession(['scope_original_id' => 1])
            ->withUnencryptedCookie(DeviceGate::COOKIE, $this->token())->get('/_device_probe')->assertOk();
        $this->assertSame(0, UserDevice::where('user_id', $agent->id)->count());
    }

    public function test_only_admins_and_managers_manage_devices(): void
    {
        $agent = $this->person(2, 'approved');
        $other = $this->person(2, 'off');   // an ordinary agent on an allowed device
        $manager = $this->person(9);
        $t = $this->token();
        $this->probe($agent, $t);
        $device = $this->device($agent, $t);

        $this->actingAs($other)->post("/devices/{$device->id}/approve")->assertForbidden();
        $this->assertSame(UserDevice::PENDING, $device->fresh()->status);

        $this->actingAs($manager)->from('/device-approvals')->post("/devices/{$device->id}/approve")->assertRedirect('/device-approvals');
        $this->assertSame(UserDevice::APPROVED, $device->fresh()->status);

        // a manager may not act on an admin's device
        $adminDevice = UserDevice::create(['user_id' => 1, 'token_hash' => hash('sha256', $this->token()), 'status' => UserDevice::APPROVED]);
        try {
            $this->actingAs($manager)->post("/devices/{$adminDevice->id}/revoke")->assertForbidden();
        } finally {
            $adminDevice->delete();
        }
    }

    public function test_the_approvals_page_lists_waiting_devices_for_managers_only(): void
    {
        $agent = $this->person(2, 'approved');
        $t = $this->token();
        $this->probe($agent, $t);
        $code = $this->device($agent, $t)->code();

        $this->actingAs($this->person(9))->get('/device-approvals')->assertOk()->assertSee($code)->assertSee($agent->name);
        $this->actingAs($this->person(2))->get('/device-approvals')->assertForbidden();
    }

    public function test_switching_to_one_device_keeps_the_most_recently_used(): void
    {
        $agent = $this->person(2, 'off');
        [$old, $new] = [$this->token(), $this->token()];
        $this->probe($agent, $old);
        $this->probe($agent, $new);
        $this->device($agent, $old)->forceFill(['last_seen_at' => now()->subDay()])->save();

        $agent->forceFill(['device_policy' => 'one'])->save();
        DeviceGate::policyChanged($agent->fresh(), User::findOrFail(1));

        $this->assertSame(UserDevice::REVOKED, $this->device($agent, $old)->status);
        $this->assertSame(UserDevice::APPROVED, $this->device($agent, $new)->status);
    }
}
