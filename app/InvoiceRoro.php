<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceRoro extends Model
{
    protected $table = 'invoice_roros';
    protected $fillable = [
        'year',
        'make',
        'model',
        'ymk',
        'from_address',
        'too_address',
        'transportation_fees',
        'auction_storage_fees',
        'yard_storage_fees',
        'yard_forklift_fees',
        'extra_other_fees',
        'redelivery_fees',
        'shipping_fees',
        'non_runner_fees',
        'forklift_fees',
        'telex_fees',
        'delivered_port',
        'vessel_grimaldi_salluam',
        'bill_name',
        'bill_address',
        'vin',
        'paid_amount'
    ];
}
