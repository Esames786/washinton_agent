<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * A browser an agent has signed in from (device restriction, see the 2026_10_22 migration and
 * App\Support\DeviceGate). Identified by a random cookie; only its hash is kept.
 */
class UserDevice extends Model
{
    protected $table = 'user_devices';

    public const PENDING  = 'pending';
    public const APPROVED = 'approved';
    public const REJECTED = 'rejected';
    public const REVOKED  = 'revoked';

    public const LABELS = [
        self::PENDING  => 'Waiting for approval',
        self::APPROVED => 'Approved',
        self::REJECTED => 'Rejected',
        self::REVOKED  => 'Revoked',
    ];

    protected $fillable = [
        'user_id', 'token_hash', 'label', 'browser', 'os', 'user_agent', 'first_ip', 'last_ip',
        'first_seen_at', 'last_seen_at', 'status', 'approved_by', 'approved_at', 'revoked_by',
        'revoked_at', 'source',
    ];

    protected $casts = [
        'first_seen_at' => 'datetime',
        'last_seen_at'  => 'datetime',
        'approved_at'   => 'datetime',
        'revoked_at'    => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Short code shown on the agent's waiting page and in the admin list, to tell devices apart. */
    public function code(): string
    {
        return strtoupper(substr($this->token_hash, 0, 6));
    }

    /** e.g. "Chrome on Windows". */
    public function name(): string
    {
        if ($this->label) {
            return $this->label;
        }

        return trim(($this->browser ?: 'Browser') . ' on ' . ($this->os ?: 'unknown system'));
    }
}
