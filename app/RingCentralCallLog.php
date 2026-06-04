<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RingCentralCallLog extends Model
{
    protected $table = 'ringcentral_call_logs';

    protected $fillable = [
        'ringcentral_user_id',
        'call_id',
        'from_number',
        'to_number',
        'phone_number',
        'direction',
        'type',
        'duration_seconds',
        'status',
        'recording_url',
        'call_started_at',
        'call_ended_at',
        'is_seen',
        'seen_at',
    ];

    protected $casts = [
        'call_started_at' => 'datetime',
        'call_ended_at' => 'datetime',
        'is_seen' => 'boolean',
        'seen_at' => 'datetime',
    ];

    public static function missedStatuses()
    {
        return [
            'Missed',
            'NoAnswer',
            'Rejected',
            'CallFailed',
            'CallerDropped',
            'Blocked',
            'Busy',
        ];
    }

    public static function missedStatusesLower()
    {
        return array_map('strtolower', self::missedStatuses());
    }

    public static function isMissedStatus($status)
    {
        $value = strtolower((string) $status);
        return $value !== '' && in_array($value, self::missedStatusesLower(), true);
    }

    /**
     * Get the R-Dialer user associated with this call
     */
    public function ringCentralUser()
    {
        return $this->belongsTo(RingCentralUser::class);
    }

    /**
     * Scope for inbound calls
     */
    public function scopeInbound($query)
    {
        return $query->where('direction', 'inbound');
    }

    /**
     * Scope for outbound calls
     */
    public function scopeOutbound($query)
    {
        return $query->where('direction', 'outbound');
    }

    /**
     * Scope for completed calls
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for missed calls
     */
    public function scopeMissed($query)
    {
        $statuses = self::missedStatusesLower();
        if (empty($statuses)) {
            return $query->whereRaw('1 = 0');
        }
        $placeholders = implode(',', array_fill(0, count($statuses), '?'));
        return $query->whereRaw("LOWER(status) IN ($placeholders)", $statuses);
    }

    /**
     * Check if call has recording
     */
    public function hasRecording()
    {
        return !is_null($this->recording_url);
    }

    /**
     * Get call duration in formatted string
     */
    public function getFormattedDuration()
    {
        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }
}
