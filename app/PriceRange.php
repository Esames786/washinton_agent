<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceRange extends Model
{
    protected $table = "price_range";
    
    protected $fillable = [
            'min_price',
            'max_price',
            'addon_price',
        ];
}
