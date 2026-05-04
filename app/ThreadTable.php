<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThreadTable extends Model
{
    protected $table = 'thread_tables';
    protected $guarded = [];

    public function chats()
    {
        return $this->hasMany(ChatMain::class, 'thread_id');
    }
}
