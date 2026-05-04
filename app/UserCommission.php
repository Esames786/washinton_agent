<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCommission extends Model
{
    protected $table = 'user_commission';
    protected $fillable = [
        'user_id',
        'revenue',
        'delivered_order',
        'cancellation',
        'total_booked_order',
        'month',
        'no_of_rating', // Per Review
        'no_of_pickup', // Private Pickup
        'review_less_than',
        'total_dispatch',
        'total_pickup',
        'flat_commision',
        'achieved_commision',
        'dispatched_by',
        'cancellation_deduction',
        'review_target_achieved',
        'average',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
