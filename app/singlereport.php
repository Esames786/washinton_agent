<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class singlereport extends Model
{
    protected $fillable = ['orderId', 'userId', 'pstatus'];
}
