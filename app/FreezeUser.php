<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FreezeUser extends Model
{
    protected $table = 'freeze_users';
    protected $fillable = [
        'user_id',
        'freeze_time',
        'reason',
        'unfreeze_time',
        'unfreeze_reason',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
