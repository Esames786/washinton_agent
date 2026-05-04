<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentSystem extends Model
{
    protected $table = "payment_system";

    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function order()
    {
        return $this->belongsTo(AutoOrder::class, 'order_id', 'id');
    }
}
