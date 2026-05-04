<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestShipment extends Model
{
    protected $table = 'request_shipments';
    protected $fillable = [
        'user_id',
        'order_id',
        'status',
        'pstatus',
        'request_name',
        'additional',
        'replyer_id',
        'reply',
        'key',
        'value'
    ];
    
    public function order()
    {
        return $this->belongsTo(AutoOrder::class,'order_id','id');
    }
}
