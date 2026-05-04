<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupons';
    protected $fillable = [
        'coupon_number',
        'coupon_price',
        'coupon_email',
        'status'
    ];
}
