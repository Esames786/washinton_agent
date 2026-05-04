<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class carrier extends Model
{
    protected $table = "carriers";
    protected $fillable = [
        'status',
    ];

    public function auto_order()
    {
        return $this->belongsTo(AutoOrder::class, 'orderId', 'id');
    }
}
