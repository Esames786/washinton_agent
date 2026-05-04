<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommissionRange extends Model
{
    protected $table = 'commission_ranges';
    protected $fillable = [
        'from_order',
        'to_order',
        'from_avg',
        'to_avg',    
        'commission',
    ];
}
