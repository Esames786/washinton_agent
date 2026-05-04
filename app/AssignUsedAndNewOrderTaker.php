<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignUsedAndNewOrderTaker extends Model
{
    protected $table = 'assignUsedAndNewOrderTaker'; 

    public function user()
    {
        return $this->belongsTo(User::class,'orderTaker','id')
            ->select('id','name','slug','last_name');
    }
}
