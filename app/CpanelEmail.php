<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CpanelEmail extends Model
{
    use SoftDeletes;

    protected $table = 'cpanel_emails';

    protected $fillable = [
        'users',
        'name',
        'url',
        'email',
        'password',
        'status',
    ];

    protected $dates = ['deleted_at'];
}
