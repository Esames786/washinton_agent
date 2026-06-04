<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $hasFromNumber = Schema::hasColumn('ringcentral_call_logs', 'from_number');
        $hasToNumber = Schema::hasColumn('ringcentral_call_logs', 'to_number');

        if ($hasFromNumber && $hasToNumber) {
            return;
        }

        Schema::table('ringcentral_call_logs', function (Blueprint $table) use ($hasFromNumber, $hasToNumber) {
            if (!$hasFromNumber) {
                $table->string('from_number')->nullable()->after('call_id');
            }
            if (!$hasToNumber) {
                $afterColumn = $hasFromNumber ? 'call_id' : 'from_number';
                $table->string('to_number')->nullable()->after($afterColumn);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $hasFromNumber = Schema::hasColumn('ringcentral_call_logs', 'from_number');
        $hasToNumber = Schema::hasColumn('ringcentral_call_logs', 'to_number');

        if (!$hasFromNumber && !$hasToNumber) {
            return;
        }

        Schema::table('ringcentral_call_logs', function (Blueprint $table) use ($hasFromNumber, $hasToNumber) {
            $columns = [];
            if ($hasFromNumber) {
                $columns[] = 'from_number';
            }
            if ($hasToNumber) {
                $columns[] = 'to_number';
            }
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};

