<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class creditcard extends Model
{
    protected $fillable = [
        'orderId',
        'card_first_name',
        'card_last_name',
        'billing_address',
        'b_city',
        'b_state',
        'b_zip',
        'b_zsc',
        'card_no',
        'card_expiry_date',
        'card_security',
        'card_type',
    ];
}
