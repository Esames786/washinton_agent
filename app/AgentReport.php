<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgentReport extends Model
{
    protected $table = 'agents_report';

    protected $fillable = [
        'order_taker_id',
        'pQuote',
        'target_achieve',
        'order_target',
        'order_achieve',
        'on_app_order',
        'cancelled',
        'on_app_cancelled',
        'followup_target',
        'followup_achieve',
        'review_target',
        'review_achieve',
        'raw_details',  // QA Positive
        'recording_issue',  // QA Verified
        'negligence',
        'greetings',
        'convincing',
        'further_issue',  // HOD Remarks
        'dispatch',
        'business_delivery',
        'port_delivery',
        'private_pickup',
        'today_N_customer',
        'today_phone_quote_DB',
        'today_listed_DB',
        'review',
        'count',
        'date_range',
    ];

    public function orderTaker()
    {
        return $this->belongsTo(User::class, 'order_taker_id', 'id')
            ->select('id', 'name', 'slug', 'last_name');
    }
}
