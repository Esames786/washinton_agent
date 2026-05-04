<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarrierOldOrderHistory extends Model
{
    protected $table = 'carrier_old_order_histories';
    protected $fillable = [
        'old_order_id',
        'new_order_id',
        'user_id',
        'history'
    ];
}
