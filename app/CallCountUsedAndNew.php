<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CallCountUsedAndNew extends Model
{
    protected $table = 'callcountusedandnew';

    protected $fillable = [
        'user_id',
        'company_id',
    ];
}
