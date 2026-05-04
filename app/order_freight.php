<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class order_freight extends Model
{
    protected $table = "order_freight";
    
    protected $fillable = [
        'order_id',
        'frieght_class',
        'equipment_type',
        'trailer_specification',
        'ex_pickup_date',
        'ex_pickup_time',
        'ex_delivery_date',
        'ex_delivery_time',
        'commodity_detail',
        'commodity_unit',
        'pick_up_services',
        'deliver_services',
        'total_weight_lbs',
        'shipment_prefences',
        'handling_unit',
        'stackable',
        'hazardous',
        'protect_from_freezing',
        'sort_segregate',
        'blind_shipment',
        'trailer_type',
    ];
}
