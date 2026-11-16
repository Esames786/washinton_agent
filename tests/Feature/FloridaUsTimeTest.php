<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Florida portal on US time (client voice note, 16 Nov 2026): quotes, orders and the dashboard
 * clock use US Eastern like Washington, while the staff's own timezone (HR attendance, check-in,
 * sign-ups) stays Asia/Karachi. Runs against the local hello_devtest copy.
 */
class FloridaUsTimeTest extends TestCase
{
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $_SERVER['REQUEST_URI'] = '/dashboard';   // the page footer reads the PHP global
    }

    protected function tearDown(): void
    {
        DB::table('user_devices')->whereIn('user_id', $this->users)->delete();
        DB::table('user')->whereIn('id', $this->users)->delete();
        unset($_SERVER['REQUEST_URI']);
        parent::tearDown();
    }

    public function test_portal_runs_on_us_eastern_and_staff_stay_on_pakistan_time(): void
    {
        $this->assertSame('America/New_York', config('app.timezone'));
        $this->assertSame('Asia/Karachi', config('app.staff_timezone'));

        // routes/web.php used to force Asia/Karachi for every request after boot.
        $this->get('/loginn');
        $this->assertSame('America/New_York', date_default_timezone_get());
        $this->assertSame('America/New_York', now()->timezoneName);
    }

    public function test_no_code_forces_pakistan_time_any_more(): void
    {
        $hits = [];
        foreach (['app', 'resources/views', 'routes'] as $dir) {
            $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(base_path($dir)));
            foreach ($it as $file) {
                if ($file->isFile() && str_ends_with($file->getFilename(), '.php')
                    && preg_match('~date_default_timezone_set\(\s*[\'"]Asia/Karachi~', file_get_contents($file->getPathname()))) {
                    $hits[] = $file->getPathname();
                }
            }
        }
        $this->assertSame([], $hits);
    }

    public function test_user_timezone_falls_back_to_the_staff_timezone(): void
    {
        $u = new User();
        $u->timezone = null;
        $this->assertSame('Asia/Karachi', $u->tz());

        $u->timezone = 'Not/AZone';
        $this->assertSame('Asia/Karachi', $u->tz());
    }

    public function test_dashboard_clock_shows_portal_time(): void
    {
        $u = User::findOrFail(373)->replicate();
        $u->forceFill([
            'email' => 'fl-tz-' . uniqid() . '@example.test', 'role' => 2, 'status' => 1, 'deleted' => 0,
            'timezone' => 'Asia/Karachi', 'nda_required' => 0, 'w9_required' => 0, 'ip_check_enabled' => 0,
            'current_session_id' => null, 'device_policy' => 'off', 'is_time' => now(),
        ])->save();
        $this->users[] = $u->id;

        $html = $this->actingAs($u)->get('/dashboard')->getContent();

        $this->assertStringContainsString('var zone = "America\/New_York";', $html);
    }
}
