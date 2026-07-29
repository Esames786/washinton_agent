<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * SMS templates are scoped per RingCentral number (RingCentralApiController@listSmsTemplates /
     * @createSmsTemplate filter/insert templates.phone_number), but the column was never migrated,
     * causing: SQLSTATE[42S22] Unknown column 'phone_number'. Add it (nullable, non-destructive).
     */
    public function up(): void
    {
        if (!Schema::hasColumn('templates', 'phone_number')) {
            Schema::table('templates', function (Blueprint $table) {
                $table->string('phone_number', 30)->nullable()->after('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('templates', 'phone_number')) {
            Schema::table('templates', function (Blueprint $table) {
                $table->dropColumn('phone_number');
            });
        }
    }
};
