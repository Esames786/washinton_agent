<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PortPrice extends Model
{
    protected $table = 'port_prices';
    protected $fillable = [
        'user_id',
        'long_data'
    ];
}
