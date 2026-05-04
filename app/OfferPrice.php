<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OfferPrice extends Model
{
    protected $table = 'offer_prices';
    protected $fillable = [
        'from_price',
        'to_price',
        'commission_price'
    ];
}
