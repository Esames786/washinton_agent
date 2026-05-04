<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceChecker extends Model
{
    protected $table = 'request_checker';

    public function order()
    {
        return $this->belongsTo(AutoOrder::class,'order_id','id');
    }

    public function priceChecker()
    {
        return $this->belongsTo(User::class,'price_checker_id','id');
    }

    public function orderTaker()
    {
        return $this->belongsTo(User::class,'order_taker_id','id');
    }
}
