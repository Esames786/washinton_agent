<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Which dealers an agent is allowed to work: state, how many records, and whether they get the
 * ones WITH phone numbers or the ones without. One row per agent, as with carriers.
 */
class DealerMasterAssign extends Model
{
    protected $table = 'dealer_master_assigns';

    protected $guarded = [];

    public function orderTakerUser()
    {
        return $this->belongsTo(User::class, 'orderTaker', 'id');
    }
}
