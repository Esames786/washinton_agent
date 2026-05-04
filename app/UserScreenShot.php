<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserScreenShot extends Model
{
    protected $table = 'user_screen_shots';
    protected $fillable = [
        'user_id',
        'image_url'
    ];
}
