<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SellInvoice extends Model
{
    protected $table = 'sell_invoices';
    protected $fillable = [
        'invoice_number',
        'date',
        'inventory_id',
        'sale_person',
        'cname',
        'cemail',
        'cphone',
        'year',
        'make',
        'model',
        'ymk',
        'vin_number',
        'sale_price',
        'total_amount',
        'balance',
        'additional',    
    ];
}
