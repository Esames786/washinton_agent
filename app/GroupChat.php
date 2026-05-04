<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupChat extends Model
{
    protected $table = 'group_chats';
    public function group()
    {
        return $this->belongsTo(Group::class,'group_id','id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
