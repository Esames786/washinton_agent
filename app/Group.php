<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'groups';

    public function users()
    {
        return $this->hasMany(GroupUser::class,'group_id','id');
    }

    public function chatOne()
    {
        return $this->hasOne(GroupChat::class,'group_id','id')->orderBy('id','DESC');
    }

    public function chatCount()
    {
        return $this->hasMany(GroupChat::class,'group_id','id');
    }
}
