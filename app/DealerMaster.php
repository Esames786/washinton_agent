<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * A dealer from the DealerRater master list.
 *
 * Mirrors ShipperDetailsCarrier so the Dealer Master screen can reuse the Carrier Approaching
 * behaviour, but keeps its own tables — see the migration for why.
 */
class DealerMaster extends Model
{
    protected $table = 'dealer_masters';

    protected $guarded = [];

    /** List marker, the way carriers use type 5. */
    public const TYPE = 6;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')
            ->select('id', 'name', 'slug', 'last_name');
    }

    /** Calls logged against this dealer, newest first. */
    public function callCount()
    {
        return $this->hasMany(DealerMasterPhone::class, 'approachId')
            ->where('type', 1)->orderBy('id', 'desc');
    }

    public function callCountUser()
    {
        return $this->hasMany(DealerMasterPhone::class, 'userId');
    }

    public function history()
    {
        return $this->hasMany(DealerMasterHistory::class, 'company_id');
    }

    /** Latest contact note — what the row shows without loading the whole history. */
    public function latestHistory()
    {
        return $this->hasOne(DealerMasterHistory::class, 'company_id')->latestOfMany();
    }
}
