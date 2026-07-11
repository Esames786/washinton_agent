<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * B6 — dynamic panel type (display name for a numeric panel id).
 * The numeric id equals the legacy `paneltype` integer, so ids are never renumbered.
 */
class PanelType extends Model
{
    protected $table = 'panel_types';

    protected $fillable = ['name', 'is_system', 'is_default', 'sort', 'status'];

    protected $casts = [
        'is_system'  => 'boolean',
        'is_default' => 'boolean',
    ];

    /**
     * Display name for a panel id, with a safe fallback. Cached per-request so the
     * ~600 label lookups don't each hit the DB.
     */
    public static function nameFor($id): string
    {
        static $map = null;

        if ($map === null) {
            try {
                $map = static::query()->pluck('name', 'id')->all();
            } catch (\Throwable $e) {
                $map = [];
            }
        }

        $id = (int) $id;

        return $map[$id] ?? ('Panel ' . $id);
    }

    /**
     * Active panels ordered for display. Fail-safe: returns an EMPTY collection if
     * the table doesn't exist yet (migration lag) so the nav — rendered on every
     * page — never 500s during a deploy.
     */
    public static function listActive()
    {
        try {
            return static::where('status', 1)->orderBy('sort')->orderBy('id')->get();
        } catch (\Throwable $e) {
            return collect();
        }
    }

    /** The signup fallback panel (Karachi), or null if not seeded yet. */
    public static function defaultPanel(): ?self
    {
        try {
            return static::where('is_default', 1)->orderBy('sort')->first();
        } catch (\Throwable $e) {
            return null;
        }
    }
}
