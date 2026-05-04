<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotRespondOrder extends Model
{
    protected $table = 'not_respond_orders';
    protected $fillable = [
        'order_id',
        'user_id'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id')
        ->select('id','slug','name','last_name');
    }
}
