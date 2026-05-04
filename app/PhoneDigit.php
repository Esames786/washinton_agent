<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhoneDigit extends Model
{
    protected $table = 'phone_digits';
    protected $fillable = [
        'hide_digits',
        'left_right_status'
    ];
}
