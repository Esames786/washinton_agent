<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApproachingAssign extends Model
{
    //
    protected $table = 'approaching_assign';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class,'orderTaker','id')
            ->select('id','name','slug','last_name');
    }
}