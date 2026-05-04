<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NatureOfCustomer extends Model
{
    protected $table = "nature_of_customer";
    
    protected $fillable = [
            'user_id',
            'order_id',
            'description',
            'status',
            'phone',
            'remarks',
            'status_updatedBy',
        ];
        
        
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id')
            ->select('id','name','slug','last_name');
    }
        
    public function statusUpdatedBy()
    {
        return $this->belongsTo(User::class,'status_updatedBy','id')
            ->select('id','name','slug','last_name');
    }
    
}
