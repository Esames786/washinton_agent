<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShipperDetailsHistories extends Model
{
    protected $table = "shipper_details_histories";

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
