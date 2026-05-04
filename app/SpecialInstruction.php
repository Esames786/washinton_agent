<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpecialInstruction extends Model
{
    protected $table = 'special_instructions';
    protected $fillable = [
        'order_id',
        'instruction'
    ];
}
