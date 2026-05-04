<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleAccess extends Model
{
    protected $table = 'role_accesses';
    protected $fillable = [
        'role_id',
        'phone_access',
        'web_access',
        'show_data_access',
        'shipment_status_access',
    ];
}
