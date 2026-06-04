<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RingCentralVoicemail extends Model
{
    protected $table = 'ringcentral_voicemails';

    protected $fillable = [
        'ringcentral_user_id',
        'voicemail_id',
        'phone_number',
        'caller_name',
        'caller_number',
        'duration_seconds',
        'transcription',
        'audio_file_path',
        'audio_url',
        'message_status',
        'received_at',
    ];

    protected $casts = [
        'received_at' => 'datetime',
    ];

    /**
     * Get the R-Dialer user associated with this voicemail
     */
    public function ringCentralUser()
    {
        return $this->belongsTo(RingCentralUser::class);
    }

    /**
     * Get the file path for storing the voicemail
     */
    public function getStoragePath()
    {
        return "voicemails/{$this->ringcentral_user_id}/{$this->voicemail_id}.wav";
    }

    /**
     * Mark voicemail as listened
     */
    public function markAsListened()
    {
        return $this->update(['message_status' => 'listened']);
    }

    /**
     * Mark voicemail as unread
     */
    public function markAsUnread()
    {
        return $this->update(['message_status' => 'unread']);
    }

    /**
     * Scope for unread voicemails
     */
    public function scopeUnread($query)
    {
        return $query->where('message_status', 'unread');
    }

    /**
     * Scope for listened voicemails
     */
    public function scopeListened($query)
    {
        return $query->where('message_status', 'listened');
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDuration()
    {
        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }
}
