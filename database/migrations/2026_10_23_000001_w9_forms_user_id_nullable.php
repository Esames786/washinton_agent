<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * W-9 in the HR onboarding workflow (client, 23 Oct 2026): an agent can now submit the W-9 from
 * the HR portal before their CRM account exists, so the row is keyed by hr_employee_id and the
 * CRM user is filled in when the account is created. Raw SQL: no doctrine/dbal needed, and the
 * index on user_id stays as it is.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('w9_forms')) {
            DB::statement('ALTER TABLE `w9_forms` MODIFY `user_id` INT UNSIGNED NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('w9_forms') && ! DB::table('w9_forms')->whereNull('user_id')->exists()) {
            DB::statement('ALTER TABLE `w9_forms` MODIFY `user_id` INT UNSIGNED NOT NULL');
        }
    }
};
