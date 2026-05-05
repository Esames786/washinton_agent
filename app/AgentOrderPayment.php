<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * NEW Payment System model
 * Table: order_payments  (new table created by 2026_05_05_000001 migration)
 *
 * The OLD legacy model is App\orderpayment (table: orderpayments)
 * — used by AutoOrder::orderpayment(), orderpayment2(), credit_card()
 * — DO NOT touch that model
 *
 * This model is used by:
 *   - NewPaymentSystemController (admin)
 *   - AgentPaymentController (agent)
 */
class AgentOrderPayment extends Model
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

    public function agent()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(AutoOrder::class, 'order_id', 'id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'id');
    }

    public function journeys()
    {
        return $this->hasMany(AgentOrderPaymentJourney::class, 'payment_id');
    }

    public function logs()
    {
        return $this->hasMany(AgentOrderPaymentLog::class, 'payment_id');
    }

    public function latestJourney()
    {
        return $this->hasOne(AgentOrderPaymentJourney::class, 'payment_id')->latestOfMany();
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->payment_status) {
            'Payment Confirmed' => '<span class="badge badge-success">Confirmed</span>',
            'Payment Return'    => '<span class="badge badge-danger">Returned</span>',
            default             => '<span class="badge badge-warning">Pending</span>',
        };
    }
}
