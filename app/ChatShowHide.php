<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatShowHide extends Model
{
    protected $table = 'chat_show_hides';
    protected $fillable = [
        'order_id',
        'from_user_id',
        'to_user_id',
        'public_id',
        'status',
    ];
}
