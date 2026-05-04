<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTargets extends Model
{
    protected $table = 'user_targets';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select('id', 'slug', 'name', 'last_name');
    }
}
