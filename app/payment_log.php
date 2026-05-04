<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class payment_log extends Model
{
    protected $table='payment_logs';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
