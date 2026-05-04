<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OwesHistory extends Model
{
    protected $table = 'owes_histories';
    protected $fillable = [
        'order_id',
        'user_id',
        'pstatus',
        'history'
    ];
}
