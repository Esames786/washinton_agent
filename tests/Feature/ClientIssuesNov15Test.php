<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Hello client list of 15 Nov 2026 (Allen) plus the 16 Nov voice notes. Runs against the local
 * hello_devtest copy.
 *
 *  1. Earn With Us sign-up: letters only in names/city, phone length by country.
 *  4. The agent's HR timezone reaches their CRM account (the dashboard clock follows it).
 *  5. Check Price (New) offers open transport only, on all 6 quote screens.
 *  +  The old manual Check Price can be switched back on, and works without a dispatcher.
 */
class ClientIssuesNov15Test extends TestCase
{
    private array $users = [];
    private array $priceRows = [];

    private const QUOTE_SCREENS = [
        'new/new_edit.blade.php', 'new/new_edit_frieght.blade.php', 'new/new_edit_heavy.blade.php',
        'new_quote/index.blade.php', 'new_quote_frieght/index.blade.php', 'new_quote_heavy/index.blade.php',
    ];

    protected function tearDown(): void
    {
        DB::table('price_checker_prices')->whereIn('id', $this->priceRows)->delete();
        DB::table('user_settings')->whereIn('user_id', $this->users)->delete();
        DB::table('user')->whereIn('id', $this->users)->delete();
        parent::tearDown();
    }

    // ── 1. Sign-up form ──────────────────────────────────────────────────────────────

    private function apply(array $over): array
    {
        $res = $this->postJson('/register', array_merge([
            'name' => 'Robert', 'last_name' => 'Smith', 'email' => 'apply-' . uniqid() . '@example.test',
            'phone' => '+1 5555550100', 'country' => 'United States', 'city' => 'Austin',
            'timezone' => 'America/Chicago',
            // terms_accepted left out on purpose: validation always fails, so nothing is created
        ], $over));
        $res->assertStatus(422);

        return $res->json('errors');
    }

    public function test_signup_rejects_numbers_in_names_and_city(): void
    {
        $errors = $this->apply(['name' => 'Robert 28585285', 'last_name' => 'test 474874848541', 'city' => '5858585vd']);

        $this->assertSame('First name can contain letters only.', $errors['name'][0]);
        $this->assertSame('Last name can contain letters only.', $errors['last_name'][0]);
        $this->assertSame('City can contain letters only.', $errors['city'][0]);
    }

    public function test_us_phone_must_be_ten_digits(): void
    {
        $this->assertSame('Enter a 10-digit phone number for +1.', $this->apply(['phone' => '+1 548588285858588'])['phone'][0]);
        $this->assertSame('Enter a 10-digit phone number for +1.', $this->apply(['phone' => '+1 555555010'])['phone'][0]);
        $this->assertArrayNotHasKey('phone', $this->apply(['phone' => '+1 5555550100']));
        // Other countries keep the worldwide range (whole number at most 15 digits).
        $this->assertArrayNotHasKey('phone', $this->apply(['phone' => '+92 3001234567']));
        $this->assertArrayHasKey('phone', $this->apply(['phone' => '+92 30012345678901']));
    }

    public function test_signup_form_blocks_digits_while_typing(): void
    {
        $html = $this->get('/register')->assertOk()->getContent();

        $this->assertStringContainsString("['name', 'last_name', 'city'].forEach", $html);
        $this->assertStringContainsString("if (dial === '+1') return { min: 10, max: 10 };", $html);
    }

    // ── 4. Timezone from HR ──────────────────────────────────────────────────────────

    private function sync(array $over): \Illuminate\Testing\TestResponse
    {
        return $this->withHeaders(['X-Bridge-Key' => 'test-bridge-key'])->postJson('/bridge/employee/sync', array_merge([
            'employee_id' => 990000 + random_int(1, 9999), 'first_name' => 'Tz', 'last_name' => 'Agent',
            'role_id' => 2, 'role_name' => 'Order Taker', 'phone' => '5550100',
        ], $over));
    }

    public function test_new_crm_account_takes_the_hr_timezone(): void
    {
        $email = 'tz-new-' . uniqid() . '@example.test';
        $res = $this->sync(['email' => $email, 'timezone' => 'America/Chicago'])->assertOk();
        $this->users[] = $res->json('user_id');

        $this->assertSame('America/Chicago', User::find($res->json('user_id'))->timezone);
    }

    public function test_hr_timezone_change_updates_an_existing_account(): void
    {
        $email = 'tz-upd-' . uniqid() . '@example.test';
        $id = $this->sync(['email' => $email])->assertOk()->json('user_id');
        $this->users[] = $id;
        $this->assertSame('Asia/Karachi', User::find($id)->fresh()->timezone);   // column default

        $this->sync(['email' => $email, 'timezone' => 'America/New_York'])->assertOk();
        $this->assertSame('America/New_York', User::find($id)->timezone);

        $this->sync(['email' => $email, 'timezone' => 'Not/AZone'])->assertOk();   // ignored
        $this->assertSame('America/New_York', User::find($id)->timezone);
    }

    // ── 5. Check Price: open only ────────────────────────────────────────────────────

    public function test_check_price_offers_open_transport_only_on_every_quote_screen(): void
    {
        foreach (self::QUOTE_SCREENS as $rel) {
            $src = file_get_contents(resource_path('views/main/phone_quote/' . $rel));
            $this->assertSame(1, preg_match('~<select id="checkPriceMode"[^>]*>(.*?)</select>~s', $src, $m), $rel);
            $this->assertSame(1, substr_count($m[1], '<option'), "$rel: one option");
            $this->assertStringContainsString('<option value="open" selected>Open Transport</option>', $m[1], $rel);
            $this->assertStringContainsString("@include('partials.old_check_price_switch')", $src, $rel);
            $this->assertStringNotContainsString('#checkPrice, #previousCheckPrice, #previousRecord', $src, $rel);
        }
    }

    // ── Old manual Check Price ───────────────────────────────────────────────────────

    public function test_old_check_price_buttons_follow_the_switch(): void
    {
        config(['app.old_check_price' => false]);
        $this->assertStringContainsString('#checkPrice, #previousCheckPrice { display: none !important; }',
            view('partials.old_check_price_switch')->render());

        config(['app.old_check_price' => true]);
        $this->assertStringNotContainsString('display: none', view('partials.old_check_price_switch')->render());
    }

    public function test_manual_price_request_needs_no_dispatcher(): void
    {
        $this->assertSame(0, User::where('role', 3)->where('deleted', 0)->where('is_login', 1)->count(),
            'the local copy, like live Hello, has no logged-in dispatcher');

        $admin = User::findOrFail(1);
        $orderId = 990000000 + random_int(1, 99999);

        // No Check Price account yet: a clear message instead of a dispatcher error.
        if (User::where('role', 23)->where('deleted', 0)->doesntExist()) {
            $this->actingAs($admin)->getJson('/request_check_price?order_id=' . $orderId)
                ->assertStatus(400)->assertJson(['message' => 'No Check Price account is set up yet. Ask an admin to create one.']);
        }

        $checker = User::findOrFail(373)->replicate();
        $checker->forceFill(['email' => 'checkprice-' . uniqid() . '@example.test', 'role' => 23, 'deleted' => 0,
            'status' => 1, 'is_login' => 1, 'slug' => 'checkprice-' . uniqid()])->save();
        $this->users[] = $checker->id;

        $this->actingAs($admin)->getJson('/request_check_price?order_id=' . $orderId)
            ->assertOk()->assertJson(['message' => 'Price requested successfully']);

        $row = DB::table('price_checker_prices')->where('order_id', $orderId)->first();
        $this->assertNotNull($row);
        $this->priceRows[] = $row->id;
        $this->assertSame($checker->id, (int) $row->price_giver_id);
    }
}
