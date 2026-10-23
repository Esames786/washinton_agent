<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * `php artisan login:link` — the replacement for the removed /login_mohsin backdoor: only the
 * server can make a link, it expires, works once, and does not log the real user out.
 * Runs against the local hello_devtest copy.
 */
class LoginLinkTest extends TestCase
{
    private array $made = [];

    protected function setUp(): void
    {
        parent::setUp();
        Route::middleware(['web', 'auth'])->get('/_link_probe', fn () => 'ok:' . auth()->id());
    }

    protected function tearDown(): void
    {
        DB::table('user_devices')->whereIn('user_id', $this->made)->delete();
        DB::table('user')->whereIn('id', $this->made)->delete();
        parent::tearDown();
    }

    private function manager(): User
    {
        $u = User::findOrFail(373)->replicate();
        $u->forceFill([
            'email' => 'link-test-' . uniqid() . '@example.test', 'role' => 9, 'status' => 1, 'deleted' => 0,
            'nda_required' => 0, 'ip_check_enabled' => 0, 'current_session_id' => 'their-real-session',
            'device_policy' => 'approved',
        ])->save();
        $this->made[] = $u->id;

        return $u->fresh();
    }

    private function link(string $email, int $minutes = 10): string
    {
        Artisan::call('login:link', ['email' => $email, '--minutes' => $minutes]);
        preg_match('#https?://\S+#', Artisan::output(), $m);

        return $m[0];
    }

    public function test_a_link_signs_in_once_without_logging_the_real_user_out(): void
    {
        $u   = $this->manager();
        $url = $this->link($u->email);

        $this->get($url)->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($u);
        $this->get('/_link_probe')->assertOk()->assertSee('ok:' . $u->id);   // no single-session kick, no device hold

        $this->assertSame('their-real-session', DB::table('user')->where('id', $u->id)->value('current_session_id'));
        $this->assertSame(0, DB::table('user_devices')->where('user_id', $u->id)->count());

        $this->app['auth']->logout();
        $this->get($url)->assertForbidden();                                  // second use
    }

    public function test_a_tampered_or_expired_link_is_refused(): void
    {
        $u = $this->manager();

        $this->get(str_replace('signature=', 'signature=x', $this->link($u->email)))->assertForbidden();

        $expired = $this->link($u->email, 1);
        $this->travel(2)->minutes();
        $this->get($expired)->assertForbidden();
        $this->assertGuest();
    }

    public function test_only_existing_accounts_get_a_link(): void
    {
        $this->assertSame(1, Artisan::call('login:link', ['email' => 'nobody-' . uniqid() . '@example.test']));
    }
}
