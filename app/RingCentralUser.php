<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RingCentralUser extends Model
{
    protected $table = 'ringcentral_users';

    protected $fillable = [
        'user_id',
        'phone_number',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'refresh_token_expires_at',
        'extension_id',
        'is_active',
        'dialer_local_block_enabled',
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'refresh_token_expires_at' => 'datetime',
        'is_active' => 'boolean',
        'dialer_local_block_enabled' => 'boolean',
    ];

    /**
     * Get the user associated with this R-Dialer account
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all call logs for this user
     */
    public function callLogs()
    {
        return $this->hasMany(RingCentralCallLog::class);
    }

    /**
     * Get all messages for this user
     */
    public function messages()
    {
        return $this->hasMany(RingCentralMessage::class);
    }

    /**
     * Scope to get only active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
