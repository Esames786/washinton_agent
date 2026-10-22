<?php

namespace Tests\Feature;

use App\Http\Controllers\W9Controller;
use App\Mail\AgentActionRequiredMail;
use App\User;
use App\W9Form;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * W-9 in the HR onboarding workflow (client, 23 Oct 2026): filed from the HR portal through the
 * bridge (even before the CRM account exists), honoured by the CRM, and a reminder there rather
 * than a lock. Runs against the local hello_devtest copy.
 */
class W9OnboardingTest extends TestCase
{
    private array $hr = [];
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $_SERVER['REQUEST_URI'] = '/autos_approach';   // that page's title reads the PHP global
    }

    protected function tearDown(): void
    {
        foreach (W9Form::whereIn('hr_employee_id', $this->hr)->orWhereIn('user_id', $this->users)->get() as $f) {
            if ($f->document_path && is_file(public_path($f->document_path))) {
                @unlink(public_path($f->document_path));
            }
            $f->delete();
        }
        DB::table('hr_employees')->whereIn('id', $this->hr)->delete();
        DB::table('user_devices')->whereIn('user_id', $this->users)->delete();
        DB::table('user')->whereIn('id', $this->users)->delete();
        unset($_SERVER['REQUEST_URI']);
        parent::tearDown();
    }

    private function hrEmployee(?int $agentId = null, int $w9Required = 0): int
    {
        $id = DB::table('hr_employees')->insertGetId([
            'full_name' => 'W9 Test', 'email' => 'w9-hr-' . uniqid() . '@example.test', 'password' => 'x',
            'employee_code' => 'W' . random_int(100000, 999999), 'employment_type_id' => 3,
            'employee_status_id' => 1, 'agent_id' => $agentId, 'w9_required' => $w9Required,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $this->hr[] = $id;

        return $id;
    }

    private function agent(): User
    {
        $u = User::findOrFail(373)->replicate();
        $u->forceFill([
            'email' => 'w9-agent-' . uniqid() . '@example.test', 'role' => 2, 'status' => 1, 'deleted' => 0,
            'is_crazyrays' => 0, 'nda_required' => 0, 'w9_required' => 0, 'ip_check_enabled' => 0,
            'current_session_id' => null, 'device_policy' => 'off', 'is_time' => now(),
        ])->save();
        $this->users[] = $u->id;

        return $u->fresh();
    }

    private function form(array $over = []): array
    {
        $img = imagecreatetruecolor(60, 20);
        imageline($img, 0, 0, 59, 19, imagecolorallocate($img, 255, 255, 255));
        ob_start(); imagepng($img); $png = ob_get_clean();

        return array_merge([
            'legal_name' => 'Ben Test', 'tax_classification' => 'individual', 'address' => '1 Main St',
            'city' => 'Austin', 'state' => 'TX', 'zip' => '73301', 'tin_type' => 'ssn', 'tin' => '123-45-6789',
            'signature_data' => 'data:image/png;base64,' . base64_encode($png . str_repeat("\0", 120)), 'certified' => '1',
        ], $over);
    }

    private function bridge(array $data, string $key = 'test-bridge-key')
    {
        return $this->withHeaders(['X-Bridge-Key' => $key, 'Accept' => 'application/json'])->postJson('/bridge/w9/submit', $data);
    }

    public function test_hr_can_file_a_w9_before_the_crm_account_exists(): void
    {
        $hr = $this->hrEmployee();

        $this->bridge($this->form(['hr_employee_id' => $hr, 'client_ip' => '203.0.113.7']))
            ->assertOk()->assertJson(['success' => true, 'tin_last4' => '6789']);

        $form = W9Form::where('hr_employee_id', $hr)->sole();
        $this->assertNull($form->user_id);
        $this->assertSame('123-45-6789', $form->tin());           // encrypted with the CRM's key
        $this->assertSame('203.0.113.7', $form->signed_ip);

        $this->bridge($this->form(['hr_employee_id' => $hr]))->assertStatus(409);   // only once
    }

    public function test_the_bridge_needs_the_key_and_a_valid_form(): void
    {
        $hr = $this->hrEmployee();
        $this->bridge($this->form(['hr_employee_id' => $hr]), 'wrong')->assertForbidden();
        $this->bridge($this->form(['hr_employee_id' => $hr, 'zip' => 'abc']))->assertStatus(422)->assertJsonValidationErrors('zip');
        $this->assertSame(0, W9Form::where('hr_employee_id', $hr)->count());
    }

    public function test_the_crm_honours_hrs_request_and_hrs_form(): void
    {
        $agent = $this->agent();
        $hr    = $this->hrEmployee($agent->id, 1);                 // HR sent it; the CRM flag is off
        $this->assertTrue(W9Controller::isRequiredFor($agent));

        $this->bridge($this->form(['hr_employee_id' => $hr]))->assertOk();
        $this->assertFalse(W9Controller::isRequiredFor($agent->fresh()));
        $this->assertSame($agent->id, (int) W9Form::where('hr_employee_id', $hr)->value('user_id'));
    }

    public function test_an_outstanding_w9_is_a_reminder_not_a_lock(): void
    {
        $agent = $this->agent();
        $this->hrEmployee($agent->id, 1);

        $html = $this->actingAs($agent)->get('/autos_approach')->assertOk()->getContent();

        $this->assertStringContainsString('id="w9Reminder"', $html);
        $this->assertStringNotContainsString('acct-gate-active', $html);
        $this->assertStringContainsString('id="w9Overlay"', $html);   // the form is still there to open
    }

    public function test_the_crm_w9_email_is_about_the_w9(): void
    {
        $html = (new AgentActionRequiredMail('Ben', \App\Support\Brand::for(null), 'w9'))->render();

        $this->assertStringContainsString('W-9', $html);
        $this->assertStringNotContainsString('Review Your Contract', $html);
    }
}
