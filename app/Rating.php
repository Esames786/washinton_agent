<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'ratings';
    protected $fillable = [
        'order_id',
        'rater_id',
        'subject',
        'review',
        'rating',
        'replyer_id',
        'reply',
        'reply_status',
        'pstatus',
        'mistake_user_id',
        'comments'
    ];
}
