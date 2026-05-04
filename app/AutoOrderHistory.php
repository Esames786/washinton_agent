<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AutoOrderHistory extends Model
{
    protected $table = "auto_order_histories";
    protected $fillable = [
        'order_id',
        'history'
    ];
}
