<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderTakerQouteAccess extends Model
{
    protected $table = 'order_taker_qoute_accesses';
    protected $fillable = [
        'manager_id',
        'ot_ids',
        'status',
        'calling_status',
        'from_date',
        'to_date'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class,'ot_ids','id');
    }
}
