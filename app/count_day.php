<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class count_day extends Model
{
    protected $table='count_days';
    public function order(){
        return $this->belongsTo(AutoOrder::class,'order_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
