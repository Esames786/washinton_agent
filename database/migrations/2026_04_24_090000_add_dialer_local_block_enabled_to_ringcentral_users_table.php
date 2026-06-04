<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ringcentral_users', function (Blueprint $table) {
            $table->boolean('dialer_local_block_enabled')
                ->default(false)
                ->after('is_active')
                ->index();
        });
    }

    public function down(): void
    {
        Schema::table('ringcentral_users', function (Blueprint $table) {
            $table->dropColumn('dialer_local_block_enabled');
        });
    }
};

