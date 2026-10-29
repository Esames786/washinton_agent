<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/** View Employees on florida (client, 29 Oct 2026): the Deleted folder is visible again. */
class ViewEmployeesDeletedTabTest extends TestCase
{
    public function test_deleted_folder_is_shown(): void
    {
        DB::table('user')->where('id', 1)->update(['current_session_id' => null]);
        $_SERVER['REQUEST_URI'] = '/view_subcontractor';

        $html = $this->actingAs(User::findOrFail(1))->get('/view_subcontractor')->assertOk()->getContent();
        unset($_SERVER['REQUEST_URI']);

        $this->assertMatchesRegularExpression('/<button class="tablinks" onclick="openCity\(event, \'Deleted\'\)">Deleted/', $html);
        $this->assertStringContainsString('<div id="Deleted" class="tabcontent">', $html);
    }
}
