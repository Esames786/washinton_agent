<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatMain extends Model
{
    protected $table = 'chat_mains';
    protected $guarded = [];

    public function thread()
    {
        return $this->belongsTo(ThreadTable::class, 'thread_id');
    }
}
