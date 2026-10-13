<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * A blog post on the public Hello Transport site (website point 6).
 *
 * Mirrors ShipA1's Blog model so the two sites share one shape. Posts are written by an
 * admin or manager in the portal — the same people ShipA1 lets author.
 */
class Blog extends Model
{
    use SoftDeletes;

    protected $table = 'blogs';

    protected $fillable = [
        'user_id', 'post_name', 'slug_name', 'post_short_description', 'post_description',
        'category', 'blog_author', 'post_image', 'image_alt', 'image_title', 'image_caption',
        'meta_title', 'meta_description', 'meta_keyword', 'canonical_url', 'blog_schema',
        'status', 'scheduled_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'status'       => 'integer',
    ];

    /** Live on the site: published, and either unscheduled or its time has come. */
    public function scopePublished($q)
    {
        return $q->where('status', 1)
            ->where(fn ($w) => $w->whereNull('scheduled_at')->orWhere('scheduled_at', '<=', now()));
    }

    public function getIsLiveAttribute(): bool
    {
        return $this->status === 1 && (! $this->scheduled_at || $this->scheduled_at->lte(now()));
    }

    /** "Published", "Scheduled" or "Draft" — what an admin needs to see in the list. */
    public function getStateAttribute(): string
    {
        if ($this->status !== 1) {
            return 'Draft';
        }

        return $this->scheduled_at && $this->scheduled_at->isFuture() ? 'Scheduled' : 'Published';
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->post_image) {
            return null;
        }

        return Str::startsWith($this->post_image, ['http://', 'https://'])
            ? $this->post_image
            : asset('storage/' . ltrim($this->post_image, '/'));
    }

    /** Date shown on the post: when it went (or goes) live, else when it was written. */
    public function getPublishedOnAttribute()
    {
        return $this->scheduled_at ?? $this->created_at;
    }

    /** A slug that is unique, from whatever the admin typed or from the title. */
    public static function uniqueSlug(string $source, ?int $ignoreId = null): string
    {
        $base = Str::slug($source) ?: 'post';
        $slug = $base;
        $n    = 2;

        while (static::withTrashed()
            ->where('slug_name', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . $n++;
        }

        return $slug;
    }
}
