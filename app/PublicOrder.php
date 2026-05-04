<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PublicOrder extends Model
{
    protected $table = 'public_orders';
    protected $fillable = [
        'order_id',
        'created_by_user_id'
    ];
}
