<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShipperDetailsHistoriesCarrier extends Model
{
    protected $table = "shipper_details_histories_carriers";

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
