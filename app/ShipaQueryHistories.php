<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShipaQueryHistories extends Model
{
    protected $table = "shipaquery_histories";

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
