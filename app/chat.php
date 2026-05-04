<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class chat extends Model
{
    protected $table = 'chats';
    
    public function receiver()
    {
        return $this->belongsTo(User::class,'touserId','id');
    }
    
    public function sender()
    {
        return $this->belongsTo(User::class,'fromuserId','id');
    }
}
