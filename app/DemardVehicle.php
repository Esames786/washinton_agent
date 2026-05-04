<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DemardVehicle extends Model
{
    protected $table = 'demand_vehicles';
    protected $fillable = [
        'user_id',
        'email',
        'from_year',
        'to_year',
        'make',
        'model',
        'trim_level',
        'mileage',
        'car_color',
        'interior_color',
        'condition',
        'title',
        'body_condition',
        'from_budget',
        'to_budget',
        'how_much_days',
        'requirement',
        'payment_method',
        'status'
    ];
}
