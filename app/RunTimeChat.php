<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RunTimeChat extends Model
{
    protected $table = 'run_time_chats';
    protected $fillable = [
        'order_id',
        'from_user_id',
        'to_user_id',
        'public_id',
        'status',
    ];
}
