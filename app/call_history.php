<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class call_history extends Model
{
    protected $table = 'call_histories';

    protected $fillable = [ 'userId', 'orderId', 'pstatus', 'history' ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => "datetime:Y-m-d H:i:s",
        'updated_at' => "datetime:Y-m-d H:i:s",
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
    public function auto_order()
    {
        return $this->belongsTo(AutoOrder::class, 'orderId', 'id');

    }
}
