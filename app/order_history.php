<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class order_history extends Model
{
    protected $table = "order_history";

    public function filterUser()
    {
        return $this->belongsTo(User::class, 'user_id','id')
        ->select('id','name','last_name','slug');
    }
}
