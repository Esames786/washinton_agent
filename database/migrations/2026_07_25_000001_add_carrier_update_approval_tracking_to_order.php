<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Carrier Update Approval status (pstatus 36) — a listed order whose carrier was updated is
 * held here for admin/manager approval before it returns to Listed (9). Each order stamps who
 * sent it for approval and when, mirroring the existing per-status tracking columns
 * (Listed_User/_Created, CarrierUpdate_User/_Created, …) that the reports screen reads.
 */
class AddCarrierUpdateApprovalTrackingToOrder extends Migration
{
    public function up()
    {
        Schema::table('order', function (Blueprint $table) {
            if (!Schema::hasColumn('order', 'CarrierUpdateApproval_User')) {
                $table->integer('CarrierUpdateApproval_User')->nullable();
            }
            if (!Schema::hasColumn('order', 'CarrierUpdateApproval_Created')) {
                $table->timestamp('CarrierUpdateApproval_Created')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('order', function (Blueprint $table) {
            if (Schema::hasColumn('order', 'CarrierUpdateApproval_User')) {
                $table->dropColumn('CarrierUpdateApproval_User');
            }
            if (Schema::hasColumn('order', 'CarrierUpdateApproval_Created')) {
                $table->dropColumn('CarrierUpdateApproval_Created');
            }
        });
    }
}
