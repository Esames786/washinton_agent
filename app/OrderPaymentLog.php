<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderPaymentLog extends Model
{
    protected $table = 'order_payment_logs';

    protected $fillable = [
        'payment_id', 'changed_by', 'user_type',
        'old_payment_mode', 'old_payment_type',
        'old_book_price', 'old_carrier_price', 'old_profit',
        'old_details', 'old_confirmation_date', 'old_payment_status',
        'action_type', 'note',
    ];

    public function payment()
    {
        return $this->belongsTo(OrderPayment::class, 'payment_id');
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by', 'id');
    }
}
