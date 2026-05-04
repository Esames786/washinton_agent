<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SheetDetails extends Model
{
    protected $table = 'sheet_details';
    protected $guarded = [];
    
    public function user()
    {
        return $this->belongsTo(User::class,'auth_id','id')->select('id','slug','name','last_name');
    }

    public function order()
    {
        return $this->belongsTo(AutoOrder::class, 'orderId');
    }
}
