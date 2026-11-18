<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Florida reports (client videos, 18 Nov 2026):
 * - Autos Approach: picking an order taker + dates listed another agent's calls. The two filters
 *   matched different calls, and every row showed the company's all-time latest caller.
 * - Daily / Subcontractor Report: managers saw blank or half-empty reports because their report
 *   access list was empty (the button that sets it is hidden in Edit Employee since July).
 * Runs against the local test copy.
 */
class AutosApproachReportsTest extends TestCase
{
    private array $made = [];
    private array $companies = [];

    protected function tearDown(): void
    {
        DB::table('historyusedandnew')->whereIn('company_id', $this->companies)->delete();
        DB::table('callcountusedandnew')->whereIn('company_id', $this->companies)->delete();
        DB::table('used_new_car_dealers')->whereIn('id', $this->companies)->delete();
        DB::table('user_settings')->whereIn('user_id', $this->made)->delete();
        DB::table('user_devices')->whereIn('user_id', $this->made)->delete();
        DB::table('user')->whereIn('id', $this->made)->delete();
        unset($_SERVER['REQUEST_URI']);
        parent::tearDown();
    }

    private function user(int $role, string $name, string $reportAccess = ''): User
    {
        $u = User::findOrFail(373)->replicate();
        $u->forceFill(['email' => 'rep-' . uniqid() . '@example.test', 'name' => $name, 'role' => $role, 'status' => 1,
            'deleted' => 0, 'emp_access_phone' => '1,32,70,94,132', 'emp_access_report' => $reportAccess, 'nda_required' => 0,
            'ip_check_enabled' => 0, 'current_session_id' => null, 'device_policy' => 'off', 'is_time' => now()])->save();
        $this->made[] = $u->id;
        DB::table('user_settings')->insert(['user_id' => $u->id, 'penal_type' => 1, 'call_type' => 134]);

        return $u->fresh();
    }

    private function company(string $name): int
    {
        return $this->companies[] = DB::table('used_new_car_dealers')->insertGetId(['name' => $name, 'state' => 'ZZ',
            'category' => 'Used Car Dealer', 'user_id' => 0, 'created_at' => now(), 'updated_at' => now()]);
    }

    private function logCall(int $company, User $agent, string $at, string $status = 'Connected'): void
    {
        DB::table('historyusedandnew')->insert(['user_id' => $agent->id, 'company_id' => $company, 'connectStatus' => $status,
            'comment' => 'note', 'created_at' => $at, 'updated_at' => $at]);
        DB::table('callcountusedandnew')->insert(['user_id' => $agent->id, 'company_id' => $company, 'created_at' => $at, 'updated_at' => $at]);
    }

    private function filter(User $viewer, array $query): array
    {
        return $this->actingAs($viewer)->getJson('/autos_approach/filter?' . http_build_query($query))->assertOk()->json();
    }

    public function test_order_taker_and_dates_select_the_same_calls(): void
    {
        $manager = $this->user(9, 'Mgr');
        $nazeef = $this->user(2, 'Nazeef');
        $saim = $this->user(2, 'Saim');

        $a = $this->company('ZZ Both agents');      // Nazeef on the 10th, Saim on the 17th
        $this->logCall($a, $nazeef, '2026-11-10 11:00:00');
        $this->logCall($a, $saim, '2026-11-17 12:00:00');
        $b = $this->company('ZZ Nazeef today');     // Nazeef twice on the 17th
        $this->logCall($b, $nazeef, '2026-11-17 10:00:00');
        $this->logCall($b, $nazeef, '2026-11-17 15:30:00', 'Not Interested');

        $rows = $this->filter($manager, ['orderTaker' => $nazeef->id, 'startDate' => '2026-11-17', 'endDate' => '2026-11-17']);

        // Company A was listed before (Nazeef called it once, someone called it on the 17th) and
        // its row named Saim.
        $this->assertSame(['ZZ Nazeef today'], array_column($rows, 'name'));
        $this->assertSame('Nazeef', $rows[0]['history'][0]['user']['name']);
        $this->assertSame('Not Interested', $rows[0]['history'][0]['connectStatus']);   // latest matching call
        $this->assertCount(2, $rows[0]['call_count']);                                  // calls in range, not all-time
        $this->assertSame('11/17/2026 03:30 PM', $rows[0]['last_call_at']);
        $this->assertSame(['id', 'name', 'last_name'], array_keys($rows[0]['history'][0]['user']));  // no permission lists

        // Dates only: newest first, each row's latest caller in range.
        $rows = $this->filter($manager, ['startDate' => '2026-11-17', 'endDate' => '2026-11-17']);
        $ours = array_values(array_filter($rows, fn ($r) => in_array($r['id'], $this->companies)));
        $this->assertSame(['ZZ Nazeef today', 'ZZ Both agents'], array_column($ours, 'name'));
        $this->assertSame('Saim', $ours[1]['history'][0]['user']['name']);
        $this->assertCount(1, $ours[1]['call_count']);
    }

    public function test_the_page_asks_for_this_months_calls_on_first_load_and_ignores_stale_answers(): void
    {
        $_SERVER['REQUEST_URI'] = '/autos_approach/all';
        $html = $this->actingAs($this->user(9, 'Mgr'))->get('/autos_approach/all')->assertOk()->getContent();

        $this->assertStringContainsString("let startDate = moment().startOf('month').format('YYYY-MM-DD');", $html);
        $this->assertStringContainsString('if (seq !== filterSeq) {', $html);
        $this->assertStringContainsString("<th>Last Call</th>", $html);
    }

    public function test_managers_and_admins_see_every_report_box_others_see_what_was_ticked(): void
    {
        $this->assertContains('4', $this->user(9, 'Mgr')->reportAccess());
        $this->assertContains('35', $this->user(1, 'Adm')->reportAccess());
        $this->assertSame(['0', '4'], $this->user(2, 'Agent', '0,4')->reportAccess());
        $this->assertSame([], $this->user(2, 'Agent')->reportAccess());
    }

    public function test_subcontractor_report_is_not_blank_for_a_manager_without_ticked_boxes(): void
    {
        $manager = $this->user(9, 'Mgr');
        $_SERVER['REQUEST_URI'] = '/reports/get';

        $html = $this->actingAs($manager)->get('/reports/get?' . http_build_query([
            'date_range' => '11/17/2026 - 11/17/2026', 'user' => '', 'pstatus' => '', 'search_by2' => 'created_at',
        ]), ['X-Requested-With' => 'XMLHttpRequest'])->assertOk()->getContent();

        foreach (['Not Interested', 'No Response', 'Listed', 'Delivered'] as $box) {
            $this->assertStringContainsString($box, $html);
        }
    }
}
