<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstantQuote extends Model
{
    protected $table = 'instant_quotes';

    protected $casts = [
        'pricing_payload'  => 'array',
        'cache_hit_open'   => 'bool',
        'cache_hit_enclosed' => 'bool',
    ];

    protected $fillable = [
        'origin_location',
        'destination_location',
        'type',
        'year_make_model',
        'customer_name',
        'customer_phone',
        'customer_email',
        'platform_code',
        'is_autohaul',
    ];
}
