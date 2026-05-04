<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarrierApproaching extends Model
{
    protected $table = "carrier_approachings";

    protected $fillable = [
        'user_id',
        'order_id',
        'extension',
        'comp_name',
        'comp_phone',
        'comp_response',
        'status',   // 1 = interested, 0 = not interested
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
