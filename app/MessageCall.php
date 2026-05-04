<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageCall extends Model
{
    protected $table = 'message_calls';
    protected $fillable = [
        'user_id',
        'order_id',
        'description',
        'reply',
        'date_time',
        'cphone',
        'cname',
        'status'
    ];
}
