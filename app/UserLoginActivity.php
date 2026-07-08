<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * #11: one row per user login (IP + source + timestamp). Show-only history for admins/managers.
 */
class UserLoginActivity extends Model
{
    protected $table = 'user_login_activities';

    protected $fillable = ['user_id', 'ip_address', 'source', 'user_agent', 'logged_in_at'];

    protected $casts = ['logged_in_at' => 'datetime'];

    /**
     * Record a login. Never let logging break the auth flow.
     */
    public static function record($userId, ?string $ip, string $source = 'hello', ?string $userAgent = null): void
    {
        try {
            static::create([
                'user_id'      => $userId,
                'ip_address'   => $ip,
                'source'       => $source,
                'user_agent'   => $userAgent ? substr($userAgent, 0, 512) : null,
                'logged_in_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // swallow — capturing login activity must never block a login
        }
    }
}
