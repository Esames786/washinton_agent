<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QNAOrder extends Model
{
    protected $table = 'q_n_a_orders';
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id')
        ->select('id','slug','name');
    }
}
