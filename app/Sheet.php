<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sheet extends Model
{
    protected $table = 'sheets';
    protected $fillable = [
        'user_id',
        'link'
    ];
}
