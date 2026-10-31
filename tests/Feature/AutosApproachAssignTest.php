<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Autos Approach assignment (florida, 31 Oct 2026: "assigned 50 leads, the agent shows 500").
 * "Allow No. of Records" is the agent's whole queue, not a number per state x category, a re-save
 * replaces it, and states/categories match exactly. Runs against the local test copy.
 */
class AutosApproachAssignTest extends TestCase
{
    private array $made = [];
    private array $leads = [];

    protected function setUp(): void
    {
        parent::setUp();
        $rows = [['ZZ', 'Used Car Dealer'], ['ZZ', 'Used Car Dealer'], ['ZZ', 'Used Car Dealer'], ['ZZ', 'New Car Dealer'],
            ['ZZ', 'New Car Dealer'], ['ZZ', 'New And Used Car Dealers'], ['AT ZZ', 'Used Car Dealer'], ['ZY', 'Used Car Dealer']];
        foreach ($rows as $i => [$state, $category]) {
            $this->leads[] = DB::table('used_new_car_dealers')->insertGetId(['name' => "AA queue $i", 'state' => $state,
                'category' => $category, 'user_id' => 0, 'created_at' => now(), 'updated_at' => now()]);
        }
    }

    protected function tearDown(): void
    {
        DB::table('used_new_car_dealers')->whereIn('id', $this->leads)->delete();
        DB::table('assignUsedAndNewOrderTaker')->whereIn('orderTaker', $this->made)->delete();
        DB::table('user_settings')->whereIn('user_id', $this->made)->delete();
        DB::table('user_devices')->whereIn('user_id', $this->made)->delete();
        DB::table('user')->whereIn('id', $this->made)->delete();
        parent::tearDown();
    }

    private function user(int $role): User
    {
        $u = User::findOrFail(373)->replicate();
        $u->forceFill(['email' => 'aa-' . uniqid() . '@example.test', 'role' => $role, 'status' => 1, 'deleted' => 0,
            'emp_access_phone' => '1,94,145', 'nda_required' => 0, 'ip_check_enabled' => 0, 'current_session_id' => null,
            'device_policy' => 'off', 'is_time' => now()])->save();
        $this->made[] = $u->id;
        DB::table('user_settings')->insert(['user_id' => $u->id, 'penal_type' => 1, 'call_type' => 134]);

        return $u->fresh();
    }

    private function held(User $agent): \Illuminate\Support\Collection
    {
        return DB::table('used_new_car_dealers')->where('user_id', $agent->id)->get(['state', 'category']);
    }

    public function test_records_is_the_whole_queue_not_per_state_and_category(): void
    {
        $manager = $this->user(9);
        $agent = $this->user(2);
        $save = fn (array $states, array $cats, int $n) => $this->actingAs($manager)->post('/autos_approach/store',
            ['orderTaker' => $agent->id, 'state' => $states, 'category_assign' => $cats, 'recordsAllowed' => $n]);

        $save(['ZZ'], ['Used Car Dealer', 'New Car Dealer'], 3)->assertSessionHas('flash_message');
        $this->assertCount(3, $this->held($agent));        // 3 in all - it used to be 3 per category

        $save(['ZZ'], ['Used Car Dealer', 'New Car Dealer'], 3);
        $this->assertCount(3, $this->held($agent));        // a second Save does not add 3 more

        // Exact match: "New And Used Car Dealers" is not a Used Car Dealer, "AT ZZ" is not ZZ.
        $save(['ZZ'], ['Used Car Dealer'], 10);
        $held = $this->held($agent);
        $this->assertCount(3, $held);
        $this->assertSame(['ZZ'], $held->pluck('state')->unique()->values()->all());
        $this->assertSame(['Used Car Dealer'], $held->pluck('category')->unique()->values()->all());

        // A new state replaces the old queue: ZZ goes back to the pool.
        $save(['ZY'], ['Used Car Dealer'], 3);
        $this->assertSame(['ZY'], $this->held($agent)->pluck('state')->unique()->values()->all());
        $this->assertSame(0, DB::table('used_new_car_dealers')->whereIn('id', $this->leads)->where('state', 'ZZ')->where('user_id', '!=', 0)->count());
    }
}
