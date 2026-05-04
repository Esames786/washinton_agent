<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LastActivity extends Model
{
    protected $table = 'last_activities';
    protected $fillable = [
        'name',
        'ip',
        'location'
    ];
}
