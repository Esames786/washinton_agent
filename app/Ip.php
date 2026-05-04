<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ip extends Model
{
    protected $table = 'ips';
    protected $fillable = [
        'ip_address',
        'status'
    ];
}
