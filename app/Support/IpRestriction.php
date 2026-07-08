<?php

namespace App\Support;

/**
 * #12: optional per-user IP restriction. Used by both the hello login (LoginController)
 * and the crazy/bridge login (BridgeAuthController) so the rule is identical everywhere.
 */
class IpRestriction
{
    /**
     * Returns an error message when the user has IP restriction enabled AND the given IP is
     * not in their allowed list; otherwise null (login allowed).
     *
     * If the checkbox is on but no IPs are configured yet, we do NOT lock the user out.
     */
    public static function enforce($user, ?string $ip): ?string
    {
        if (empty($user) || empty($user->ip_check_enabled)) {
            return null;
        }

        $allowed = self::allowedList($user->allowed_ips ?? null);
        if (empty($allowed)) {
            return null; // enabled but no IPs set — don't accidentally lock the account out
        }

        if (!in_array((string) $ip, $allowed, true)) {
            return 'Your IP address (' . ($ip ?: 'unknown') . ') is not authorized for this account. Please contact admin.';
        }

        return null;
    }

    /** Parse the stored allowed-IPs blob (newline / comma / space separated) into a clean array. */
    public static function allowedList(?string $raw): array
    {
        return collect(preg_split('/[\s,]+/', (string) $raw, -1, PREG_SPLIT_NO_EMPTY))
            ->map(fn ($ip) => trim($ip))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
