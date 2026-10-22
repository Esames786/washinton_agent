<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Where sign-out lands (client, 23 Oct 2026: a Hello agent signing out of the device page ended up
 * on the CrazyRays website). Hello's own signup writes to cr_applications too (brand = hello);
 * only CrazyRays applications may send someone to the CrazyRays site.
 */
class LogoutRedirectTest extends TestCase
{
    private array $users = [];
    private array $emails = [];

    protected function setUp(): void
    {
        parent::setUp();
        config(['bridge.crazyrays.base_url' => 'https://crazyrays.example.test', 'bridge.crazyrays.session_clear_endpoint' => '/session-clear']);
    }

    protected function tearDown(): void
    {
        DB::table('cr_applications')->whereIn('email', $this->emails)->delete();
        DB::table('user')->whereIn('id', $this->users)->delete();
        parent::tearDown();
    }

    /** An agent who applied through the given careers form. */
    private function applicant(string $brand): User
    {
        $email = 'logout-test-' . uniqid() . '@example.test';
        $u = User::findOrFail(373)->replicate();
        $u->forceFill([
            'email' => $email, 'role' => 2, 'status' => 1, 'deleted' => 0, 'is_crazyrays' => 0,
            'current_session_id' => null, 'device_policy' => 'off', 'is_time' => now(),
        ])->save();
        $this->users[] = $u->id;
        $this->emails[] = $email;
        DB::table('cr_applications')->insert([
            'brand' => $brand, 'full_name' => 'Logout Test', 'email' => $email, 'phone' => '5550100',
            'status' => 'shortlisted', 'created_at' => now(), 'updated_at' => now(),
        ]);

        return $u->fresh();
    }

    public function test_a_hello_applicant_signs_out_to_the_hello_login(): void
    {
        $this->actingAs($this->applicant('hello'))->get('/logout')->assertRedirect('/loginn');
    }

    public function test_a_crazyrays_applicant_still_goes_back_to_crazyrays(): void
    {
        $this->actingAs($this->applicant('crazyrays'))->get('/logout')
            ->assertRedirect('https://crazyrays.example.test/session-clear');
    }
}
