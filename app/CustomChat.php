<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomChat extends Model
{
    protected $table = 'custom_chats';
    protected $fillable = [
        'order_id',
        'from_user_id',
        'to_user_id',
        'approved_by_user_id',
        'message',
        'message_type',
        'message_date',
        'message_time',
        'status',
        'datetime_for_approver'
    ];
    
    public function sender()
    {
        return $this->belongsTo(User::class,'from_user_id','id')
        ->select('id','name','last_name','slug','is_login');
    }
    
    public function receiver()
    {
        return $this->belongsTo(User::class,'to_user_id','id')
        ->select('id','name','last_name','slug','is_login');
    }
    
    public function flag()
    {
        return $this->hasOne(Flag::class,'custom_chat_id','id')
        ->where('status',1);
    }
}
