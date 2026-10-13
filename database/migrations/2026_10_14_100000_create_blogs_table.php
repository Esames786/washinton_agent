<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Website point 6 — a blog, modelled on ShipA1's (the client: "can also check shipa1
 * blogs for an idea").
 *
 * The columns follow ShipA1's blogs table as it stands after all its migrations, so a
 * post can move between the two sites without reshaping. It is SEO-heavy by design: a
 * blog on a transport site exists mostly to rank, which is why ShipA1 carries meta
 * fields, a canonical URL, JSON-LD schema and image alt/title text on every post.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('blogs')) {
            return;
        }

        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();

            $table->string('post_name');
            $table->string('slug_name')->unique();
            $table->text('post_short_description')->nullable();
            $table->longText('post_description');
            $table->string('category', 100)->nullable()->index();
            $table->string('blog_author', 150)->nullable();

            $table->string('post_image')->nullable();
            $table->string('image_alt')->nullable();
            $table->string('image_title')->nullable();
            $table->string('image_caption')->nullable();

            // SEO
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('meta_keyword')->nullable();
            $table->string('canonical_url')->nullable();
            $table->longText('blog_schema')->nullable();   // JSON-LD

            // 1 = published, 0 = draft. A published post with a future scheduled_at stays
            // hidden until that moment.
            $table->tinyInteger('status')->default(0)->index();
            $table->timestamp('scheduled_at')->nullable()->index();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
