<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReviewWebsiteLink extends Model
{
    protected $table = 'review_website_links';
    protected $fillable = [
        'name',
        'link',
        'status'
    ];
}
