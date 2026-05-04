<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceCheckerPrice extends Model
{
    use HasFactory;
    protected $table = 'price_checker_prices';

    protected $fillable = [
        'price_giver_id',
        'order_id',
        'requester_id',
        'car',
        'suv',
        'pickup',
        'van',
        'other',
        'other_title',
    ];

    public function price_giver()
    {
        return $this->belongsTo(User::class, 'price_giver_id', 'id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(AutoOrder::class, 'order_id', 'id');
    }
}
