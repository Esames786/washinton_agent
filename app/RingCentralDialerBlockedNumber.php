<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RingCentralDialerBlockedNumber extends Model
{
    protected $table = 'ringcentral_dialer_blocked_numbers';

    protected $fillable = [
        'ringcentral_user_id',
        'normalized_e164',
        'raw_input',
        'reason',
        'blocked_by_user_id',
        'ringcentral_rule_id',
        'ringcentral_sync_status',
        'ringcentral_sync_error',
        'ringcentral_synced_at',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'ringcentral_synced_at' => 'datetime',
    ];

    public function ringCentralUser()
    {
        return $this->belongsTo(RingCentralUser::class, 'ringcentral_user_id');
    }

    public function blockedBy()
    {
        return $this->belongsTo(User::class, 'blocked_by_user_id');
    }
}

