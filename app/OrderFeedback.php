<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderFeedback extends Model
{
    protected $table = 'order_feedbacks';
    protected $fillable = [
        'order_id',
        'feedback',
        'rate',
        'link_click',
        'user_id'
    ];
    
}
