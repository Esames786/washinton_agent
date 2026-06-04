<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RingCentralMessage extends Model
{
    protected $table = 'ringcentral_messages';

    protected $fillable = [
        'ringcentral_user_id',
        'message_id',
        'from_name',
        'to_name',
        'from_number',
        'to_number',
        'message_body',
        'attachments',
        'direction',
        'status',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'attachments' => 'array',
    ];

    /**
     * Get the R-Dialer user associated with this message
     */
    public function ringCentralUser()
    {
        return $this->belongsTo(RingCentralUser::class);
    }

    /**
     * Scope for inbound messages
     */
    public function scopeInbound($query)
    {
        return $query->where('direction', 'inbound');
    }

    /**
     * Scope for outbound messages
     */
    public function scopeOutbound($query)
    {
        return $query->where('direction', 'outbound');
    }

    /**
     * Scope for unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    /**
     * Scope for sent messages
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Get the URL-safe phone number
     */
    public function getCleanPhoneNumber()
    {
        return preg_replace('/[^0-9]/', '', $this->phone_number);
    }
}
