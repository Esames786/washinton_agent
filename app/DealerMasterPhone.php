<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/** One row per call or WhatsApp press — the counters shown on the row. */
class DealerMasterPhone extends Model
{
    protected $table = 'dealer_master_phones';

    protected $guarded = [];

    public const TYPE_CALL     = 1;
    public const TYPE_WHATSAPP = 2;

    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

    public function dealer()
    {
        return $this->belongsTo(DealerMaster::class, 'approachId', 'id');
    }
}
