<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Dealer Master — its own dataset, built the way Carrier Approaching is built.
 *
 * Four tables, deliberately separate from shipper_details_* rather than shared: the client asked
 * for this list to stand on its own, and the carrier/shipper/edge lists already prove the shape
 * works. Keeping them apart means an assignment, a call or a history note on a dealer can never
 * collide with a carrier record, and the dealer list can be re-imported without touching them.
 *
 *   dealer_masters            the dealers themselves      (~23,995 rows after de-duplication)
 *   dealer_master_assigns     which agent gets what       (mirrors shipper_details_assign_carriers)
 *   dealer_master_histories   call/contact notes          (mirrors shipper_details_histories_carriers)
 *   dealer_master_phones      call + WhatsApp counters    (mirrors shipper_details_phone_carriers)
 *
 * The source is a DealerRater scrape whose columns have no home in the carrier table (rating,
 * reviews, inventory, DealerRater id), so they get real columns here instead of being flattened
 * into `other_details`.
 *
 * Prod: run with --path (the migrations table on live is stale).
 */
return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('dealer_masters')) {
            Schema::create('dealer_masters', function (Blueprint $t) {
                $t->increments('id');

                // Identity from the source. dr_id is the natural key: the file repeats ~3,267
                // dealers across scrape prefixes, and this is what collapses them to one row.
                $t->string('dr_id', 40)->nullable();
                $t->string('source_key', 191)->nullable();

                $t->string('name', 191)->nullable();
                $t->string('email', 191)->nullable();       // not in the source; agents fill it in

                // Four numbers, because the source carries four. The carrier table only has three.
                $t->string('phone', 191)->nullable();       // phone_main — the one the screen dials
                $t->string('phone2', 191)->nullable();      // phone_new
                $t->string('phone3', 191)->nullable();      // phone_used
                $t->string('phone4', 191)->nullable();      // phone_service

                $t->text('address')->nullable();            // street
                $t->string('city', 120)->nullable();
                $t->string('states', 20)->nullable();       // 2-letter, same convention as carriers
                $t->string('zip', 20)->nullable();

                $t->string('website', 255)->nullable();     // profile_url
                $t->string('website2', 191)->nullable();

                // DealerRater facts — useful for prioritising who to call first.
                $t->decimal('rating', 3, 1)->nullable();
                $t->unsignedInteger('reviews')->nullable();
                $t->unsignedInteger('inventory_cars')->nullable();
                $t->boolean('claimed')->default(0);
                $t->unsignedInteger('distance_miles')->nullable();

                $t->tinyInteger('type')->default(6);        // list marker; carriers use 5
                $t->unsignedInteger('user_id')->default(0); // assigned agent, 0 = unassigned
                $t->boolean('has_phone')->default(0);       // derived once, so assignment can split
                                                            // on it without four OR conditions
                $t->string('business_type', 191)->nullable();
                $t->text('other_details')->nullable();
                $t->dateTime('email_send_time')->nullable();

                // Provenance, so a re-import can be traced back to the file it came from.
                $t->string('source_file', 191)->nullable();
                $t->dateTime('source_ingested_at')->nullable();

                $t->timestamps();

                $t->unique('dr_id', 'dm_dr_id_unique');     // makes re-import idempotent
                $t->index('states', 'dm_states_idx');
                $t->index('user_id', 'dm_user_idx');
                $t->index('has_phone', 'dm_has_phone_idx');
                $t->index(['states', 'has_phone'], 'dm_state_phone_idx');  // the assignment query
                $t->index('name', 'dm_name_idx');
            });
        }

        if (! Schema::hasTable('dealer_master_assigns')) {
            Schema::create('dealer_master_assigns', function (Blueprint $t) {
                $t->increments('id');
                $t->string('state', 255)->nullable();
                $t->string('type', 255)->nullable();
                $t->unsignedBigInteger('orderTaker');        // the agent — column named as the
                                                             // carrier table names it, so the
                                                             // relationship reads the same
                $t->string('recordsAllowed', 255)->nullable();
                $t->boolean('with_phone')->default(1);
                $t->integer('status')->default(1);
                $t->timestamps();

                $t->index('orderTaker', 'dma_ordertaker_idx');
            });
        }

        if (! Schema::hasTable('dealer_master_histories')) {
            Schema::create('dealer_master_histories', function (Blueprint $t) {
                $t->increments('id');
                $t->unsignedBigInteger('user_id');
                $t->unsignedBigInteger('company_id');        // dealer_masters.id
                $t->string('connectStatus', 255)->nullable();
                $t->longText('comment')->nullable();
                $t->timestamps();

                $t->index('company_id', 'dmh_company_idx');
                $t->index('user_id', 'dmh_user_idx');
            });
        }

        if (! Schema::hasTable('dealer_master_phones')) {
            Schema::create('dealer_master_phones', function (Blueprint $t) {
                $t->increments('id');
                $t->unsignedBigInteger('userId');
                $t->unsignedBigInteger('approachId');        // dealer_masters.id
                $t->integer('type')->default(1);             // 1 = call, 2 = whatsapp
                $t->integer('status')->default(0);
                $t->timestamps();

                $t->index('approachId', 'dmp_approach_idx');
                $t->index(['userId', 'type'], 'dmp_user_type_idx');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('dealer_master_phones');
        Schema::dropIfExists('dealer_master_histories');
        Schema::dropIfExists('dealer_master_assigns');
        Schema::dropIfExists('dealer_masters');
    }
};
