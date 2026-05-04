<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flag extends Model
{
    protected $table = 'flags';
    protected $fillable = [
        'custom_chat_id',
        'public_chat_id',
        'group_chat_id',
        'user_id',
        'flag_by_user_id',
        'status',
        'reason'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')
            ->select('id', 'slug', 'name', 'last_name');
    }
    public function customChat()
    {
        return $this->belongsTo(chat::class, 'custom_chat_id', 'id');
    }
    public function groupChat()
    {
        return $this->belongsTo(GroupChat::class, 'user_id', 'id');
    }
}
