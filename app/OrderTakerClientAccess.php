<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderTakerClientAccess extends Model
{
    protected $table = 'order_taker_client_accesses';
    protected $fillable = [
        'ot_id',
        'client_number'
    ];
}
