<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderWebsiteEmail extends Model
{
    protected $table = 'order_website_emails';
    protected $fillable = [
        'order_id',
        'link_id',
        'email',
        'link_click'
    ];
}
