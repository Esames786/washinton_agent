<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShipperDetailsHistoriesDealer extends Model
{
    protected $table = "shipper_details_histories_dealer";

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
