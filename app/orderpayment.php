<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPayment extends Model
{
    use SoftDeletes;

    protected $table = 'order_payments';

    protected $fillable = [
        'payment_mode', 'payment_type', 'pay_type', 'pay_on', 'pay_from',
        'order_id', 'listing_order_id', 'user_id',
        'payment_status', 'book_price', 'carrier_price', 'profit',
        'confirmation_date', 'details', 'screenshot_path',
        'is_paid', 'reviewed_by', 'admin_remarks',
    ];

    protected $casts = [
        'book_price'    => 'float',
        'carrier_price' => 'float',
        'profit'        => 'float',
    ];

    // The agent who submitted this payment
    public function agent()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // The order this payment belongs to
    public function order()
    {
        return $this->belongsTo(AutoOrder::class, 'order_id', 'id');
    }

    // Admin who reviewed
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'id');
    }

    // Status journey history
    public function journeys()
    {
        return $this->hasMany(OrderPaymentJourney::class, 'payment_id');
    }

    // Audit logs
    public function logs()
    {
        return $this->hasMany(OrderPaymentLog::class, 'payment_id');
    }

    // Latest journey entry
    public function latestJourney()
    {
        return $this->hasOne(OrderPaymentJourney::class, 'payment_id')->latestOfMany();
    }

    // Status badge helper
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->payment_status) {
            'Payment Confirmed' => '<span class="badge badge-success">Confirmed</span>',
            'Payment Return'    => '<span class="badge badge-danger">Returned</span>',
            default             => '<span class="badge badge-warning">Pending</span>',
        };
    }
}
