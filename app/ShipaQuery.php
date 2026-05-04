<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShipaQuery extends Model
{
    protected $fillable = [
        // Customer
        'oname', 'oemail', 'ophone', 'main_ph',
        // Origin
        'origincity', 'originstate', 'originzip', 'originzsc', 'oterminal',
        // Destination
        'destinationcity', 'destinationstate', 'destinationzip', 'destinationzsc', 'dterminal',
        // Vehicle
        'ymk', 'year', 'make', 'model', 'type', 'condition', 'transport',
        'vehicle_opt', 'car_type', 'car_info',
        // Dimensions
        'length_ft', 'length_in', 'width_ft', 'width_in',
        'height_ft', 'height_in', 'weight',
        // Meta
        'paneltype', 'pstatus', 'source',
        'add_info', 'cname', 'cemail',
    ];
    protected $table = 'shipa_query';


    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id')
            ->select('id','name','slug','last_name');
    }

    public function callCount()
    {
        return $this->hasMany(ShipaQueryPhone::class, 'query_id')->where('type',1)->orderby('id','desc');
    }

    public function callCountUser()
    {
        return $this->hasMany(ShipaQueryPhone::class, 'userId');
    }

    public function history()
    {
        return $this->hasMany(ShipaQueryHistories::class, 'company_id');
    }

    public function whatsappCallCount()
    {
        return $this->hasMany(ShipaQueryPhone::class, 'query_id')->where('type',2);
    }

    public function whatsappHistory()
    {
        return $this->hasMany(ShipaQueryHistories::class, 'company_id');
    }
}
