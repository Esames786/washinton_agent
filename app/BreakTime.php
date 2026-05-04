<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BreakTime extends Model
{
    protected $table = 'break_times';
    protected $fillable = [
        'user_id',
        'start_time',
        'end_time',
        'status'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
