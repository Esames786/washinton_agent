<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\User;

class WebPhoneInstance extends Model
{
    protected $table = 'webphone_instances';

    protected $fillable = [
        'user_id',
        'instance_id',
        'authorization_id',
        'ip_address',
        'user_agent',
        'last_seen_at',
        'closed_at',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
        'created_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    /**
     * Get the user that owns this instance
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if this instance is still active (not closed and recently seen)
     */
    public function isActive(): bool
    {
        // Instance is active if:
        // 1. Not explicitly closed
        // 2. Seen within last 0.25 minutes
        return $this->closed_at === null && 
               $this->last_seen_at->greaterThan(now()->subMinutes(0.25));
    }
}
