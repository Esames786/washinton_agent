<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgentOrderPaymentJourney extends Model
{
    protected $table = 'order_payment_journeys';

    protected $fillable = [
        'payment_id', 'old_status', 'new_status',
        'changed_by', 'user_type', 'note',
    ];

    public function payment()
    {
        return $this->belongsTo(AgentOrderPayment::class, 'payment_id');
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by', 'id');
    }
}
