<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyQoute extends Model
{
    protected $table = 'daily_qoutes';
    protected $fillable = [
        'user_id',
        'total_qoute',
        'date'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
