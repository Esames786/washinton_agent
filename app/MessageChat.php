<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MessageChat extends Model
{
    use SoftDeletes;

    protected $table = 'message_chats';

    protected $fillable = [
        'user_id',
        'order_id',
        'message',
        'description',
        'status',
        'message_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function order()
    {
        return $this->belongsTo(AutoOrder::class,'order_id','id');
    }
}
