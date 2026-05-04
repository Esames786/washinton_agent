<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PublicOrderChat extends Model
{
    protected $table = 'public_order_chats';
    protected $fillable = [
        'order_id',
        'public_id',
        'user_id',
        'approved_by_user_id',
        'message',
        'message_type',
        'message_date',
        'message_time',
        'status',
        'seen_by_user_id',
        'datetime_for_approver'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id')
        ->select('id','name','last_name','slug','is_login');
    }
    
    public function publicChat()
    {
        return $this->belongsTo(PublicOrder::class,'public_id','id');
    }
    
    public function flag()
    {
        return $this->hasOne(Flag::class,'public_chat_id','id')
        ->where('status',1);
    }
}
