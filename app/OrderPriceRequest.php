<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderPriceRequest extends Model
{
    protected $fillable = [
        'order_id','requested_by',
        'origin_zip','destination_zip','origin_state','destination_state','vehicle_key',
        'driver_low','driver_mid','driver_high',
        'offer_open','offer_enclosed','commission',
        'cache_hit','request_payload','response_payload',
    ];

    protected $casts = [
        'cache_hit' => 'bool',
        'request_payload' => 'array',
        'response_payload' => 'array',
    ];
}
