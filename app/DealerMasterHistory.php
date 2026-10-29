<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/** A contact note left by an agent against one dealer. */
class DealerMasterHistory extends Model
{
    protected $table = 'dealer_master_histories';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select('id', 'name', 'slug', 'last_name');
    }

    public function dealer()
    {
        return $this->belongsTo(DealerMaster::class, 'company_id', 'id');
    }
}
