<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Managed CrazyRays campaign / job.
 * @see database/migrations/2026_07_17_000001_create_cr_campaigns_table.php
 */
class CrCampaign extends Model
{
    protected $table = 'cr_campaigns';

    public const CATEGORY_WFH      = 'work_from_home';
    public const CATEGORY_IN_HOUSE = 'in_house';

    protected $fillable = [
        'key', 'name', 'description', 'icon',
        'employment_category', 'allowed_shifts', 'default_pay_type',
        'status', 'sort_order',
    ];

    protected $casts = [
        'allowed_shifts' => 'array',
        'status'         => 'boolean',
    ];

    public function scopeActive($q)
    {
        return $q->where('status', 1);
    }

    public function scopeForCategory($q, string $category)
    {
        return $q->where('employment_category', $category);
    }

    public function isInHouse(): bool
    {
        return $this->employment_category === self::CATEGORY_IN_HOUSE;
    }
}
