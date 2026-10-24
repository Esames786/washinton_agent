<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Florida, 25 Oct 2026: saving a manager with every permission ticked wiped Action Access, Address
 * and more. The form posted 1,100+ fields; PHP keeps only max_input_vars (1000) and silently drops
 * the rest. Checkbox groups now travel as one "<name>__csv" field each, and a request that still
 * hits the limit is refused instead of half-saved. Runs against the local hello_devtest copy.
 */
class EmployeeFormSizeTest extends TestCase
{
    private array $made = [];

    protected function tearDown(): void
    {
        DB::table('user_settings')->whereIn('user_id', $this->made)->delete();
        DB::table('user_devices')->whereIn('user_id', $this->made)->delete();
        DB::table('user')->whereIn('id', $this->made)->delete();
        unset($_SERVER['REQUEST_URI']);
        parent::tearDown();
    }

    private function manager(): User
    {
        $u = User::findOrFail(373)->replicate();
        $u->forceFill([
            'email' => 'form-size-' . uniqid() . '@example.test', 'role' => 9, 'status' => 1, 'deleted' => 0,
            'address' => 'Original Street', 'emp_access_action' => '1,2', 'emp_panel_access' => '1,10',
        ])->save();
        $this->made[] = $u->id;

        return $u->fresh();
    }

    private function admin(): User
    {
        DB::table('user')->where('id', 1)->update(['current_session_id' => null]);

        return User::findOrFail(1);
    }

    private function base(User $u): array
    {
        return [
            'user_id' => $u->id, 'first_name' => 'Form', 'last_name' => 'Size', 'slug' => 'form-size',
            'email' => $u->email, 'phone_number' => '5550-1000000', 'job_type' => 9, 'penalytype' => 1,
        ];
    }

    public function test_compact_groups_save_every_list(): void
    {
        $u = $this->manager();

        $this->actingAs($this->admin())->post('/update_employee', $this->base($u) + [
            'address' => 'New Street 5',
            'emp_access_phone__csv' => implode(',', range(1, 170)),   // a full panel in ONE field
            'emp_access_action__csv' => '3,4,5',
            'emp_panel_access__csv' => '1,2,10',
        ])->assertOk()->assertSee('SUCCESS');

        $row = DB::table('user')->where('id', $u->id)->first();
        $this->assertSame(implode(',', range(1, 170)), $row->emp_access_phone);
        $this->assertSame('3,4,5', $row->emp_access_action);
        $this->assertSame('1,2,10', $row->emp_panel_access);
        $this->assertSame('New Street 5', $row->address);
    }

    public function test_a_cut_off_form_is_refused_not_half_saved(): void
    {
        $u = $this->manager();
        $limit = (int) ini_get('max_input_vars');
        $this->assertGreaterThan(0, $limit);

        // What PHP hands over when it stopped reading at the limit: the first $limit values only.
        $this->actingAs($this->admin())->post('/update_employee', $this->base($u) + [
            'emp_access_phone' => array_map('strval', range(1, $limit)),
        ])->assertOk()->assertSee('NOT SAVED');

        $row = DB::table('user')->where('id', $u->id)->first();
        $this->assertSame('Original Street', $row->address);
        $this->assertSame('1,2', $row->emp_access_action);
    }

    public function test_the_old_one_field_per_box_format_still_works(): void
    {
        $u = $this->manager();

        $this->actingAs($this->admin())->post('/update_employee', $this->base($u) + [
            'address' => 'Old Format Road', 'emp_access_action' => ['7', '8'], 'emp_panel_access' => ['1'],
        ])->assertOk()->assertSee('SUCCESS');

        $row = DB::table('user')->where('id', $u->id)->first();
        $this->assertSame('7,8', $row->emp_access_action);
        $this->assertSame('Old Format Road', $row->address);
    }

    public function test_the_edit_page_sends_compact_groups(): void
    {
        $_SERVER['REQUEST_URI'] = '/edit_subcontractor';
        $u = $this->manager();

        $this->actingAs($this->admin())->get('/edit_subcontractor/' . $u->id)->assertOk()
            ->assertSee('data: compactFormData(this)', false)
            ->assertSee('function compactFormData(form)', false);
    }
}
