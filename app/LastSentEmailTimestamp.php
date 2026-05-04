<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LastSentEmailTimestamp extends Model
{
    protected $fillable = [
        'last_sent_at',
    ];
}
