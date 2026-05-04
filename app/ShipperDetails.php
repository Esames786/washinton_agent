<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShipperDetails extends Model
{
    protected $table = "shipper_details_fetch";

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id')
            ->select('id','name','slug','last_name');
    }

    public function callCount()
    {
        return $this->hasMany(ShipperDetailsPhone::class, 'approachId')->where('type',1)->orderby('id','desc');
    }

    public function callCountUser()
    {
        return $this->hasMany(ShipperDetailsPhone::class, 'userId');
    }

    public function history()
    {
        return $this->hasMany(ShipperDetailsHistories::class, 'company_id');
    }

    public function whatsappCallCount()
    {
        return $this->hasMany(ShipperDetailsPhone::class, 'approachId')->where('type',2);
    }

    public function whatsappHistory()
    {
        return $this->hasMany(ShipperDetailsHistories::class, 'company_id');
    }
}
