<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PortTrackHistory extends Model
{
    protected $table = "port_track_history";

    protected $fillable = [
        'user_id',
        'order_id',
        'history',
        'status',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
