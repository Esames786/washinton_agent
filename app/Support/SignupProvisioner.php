<?php

namespace App\Support;

use App\PanelType;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * B6 — centralises new-signup provisioning so the 4 signup entry points
 * (BridgeAuthController, PublicSignupController, CrApplicationController,
 * EmployeeSyncController) stop cloning reference users 130/53 at runtime.
 *
 *  - applyDefaults(): fills the permission/access columns from the admin-editable
 *    `signup_defaults` table. If that row is missing (not seeded yet) it FALLS BACK
 *    to the supplied reference user, so behaviour is unchanged until seeded.
 *
 *  - resolveCityPanelId(): maps the signup city (entered value first, then IP
 *    geolocation) to a panel whose name matches that city. Falls back to the
 *    default panel (Karachi). Only *functional* panels (1..6, which have a working
 *    permission column + check_panel routing) are returned for auto-assignment;
 *    unmatched / new-panel cities return null so the caller keeps the safe default.
 */
class SignupProvisioner
{
    /** Panels that are fully wired end-to-end today (have a column + check_panel case). */
    public const FUNCTIONAL_PANELS = [1, 2, 3, 4, 5, 6];

    /**
     * Apply the default permission/access columns for a role onto a new user.
     * Returns true if seeded defaults were used, false if it fell back to the reference user.
     */
    public static function applyDefaults(User $user, string $roleKey, array $columns, ?User $referenceUser = null): bool
    {
        $row = null;
        try {
            $row = DB::table('signup_defaults')
                ->where('role_key', $roleKey)
                ->where('status', 1)
                ->first();
        } catch (\Throwable $e) {
            Log::warning('[SignupProvisioner] signup_defaults lookup failed: ' . $e->getMessage());
        }

        if ($row && !empty($row->payload)) {
            $payload = json_decode($row->payload, true) ?: [];
            foreach ($columns as $col) {
                if (array_key_exists($col, $payload)) {
                    $user->$col = $payload[$col];
                }
            }
            return true;
        }

        // Fallback — preserve existing behaviour (clone the reference user).
        if ($referenceUser) {
            foreach ($columns as $col) {
                $user->$col = $referenceUser->$col;
            }
        }

        return false;
    }

    /** #4: id of the "No Access" onboarding panel (null if not created yet). */
    public static function noAccessPanelId(): ?int
    {
        try {
            $id = PanelType::where('name', 'No Access')->value('id');
            return $id ? (int) $id : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * #4: give a brand-new user NO access — clears every permission column and puts
     * them on the "No Access" panel, so they only see basic screens (dashboard) until
     * an admin assigns proper access. Used by every signup channel.
     */
    public static function applyNoAccess(User $user, array $columns): void
    {
        foreach ($columns as $col) {
            $user->$col = '';
        }
        // NOTE: the panel assignment lives on the `user_setting` table
        // (user_setting.penal_type), NOT on the `user` table. Each caller writes
        // it via `$setting->penal_type = SignupProvisioner::noAccessPanelId()`.
        // Do NOT set $user->penal_type here — the `user` table has no such column
        // and doing so throws SQLSTATE[42S22] Unknown column 'penal_type' on save.
    }

    /**
     * Resolve a functional panel id from the signup city (or IP), else null.
     * Returns null when no functional panel matches (caller keeps its default).
     */
    public static function resolveCityPanelId(?string $enteredCity, ?string $ip = null): ?int
    {
        $city = trim((string) $enteredCity);

        // Fall back to IP geolocation when no city was entered.
        if ($city === '' && $ip) {
            try {
                $pos = \Stevebauman\Location\Facades\Location::get($ip);
                if ($pos && !empty($pos->cityName)) {
                    $city = (string) $pos->cityName;
                }
            } catch (\Throwable $e) {
                Log::info('[SignupProvisioner] IP geolocation unavailable: ' . $e->getMessage());
            }
        }

        if ($city === '') {
            return null;
        }

        $needle = self::normalize($city);

        try {
            $panels = PanelType::where('status', 1)->get();
        } catch (\Throwable $e) {
            return null;
        }

        foreach ($panels as $panel) {
            if ($panel->is_system) {
                continue; // never auto-assign Testing / Website
            }
            $name = self::normalize($panel->name);
            if ($name !== '' && (str_contains($needle, $name) || str_contains($name, $needle))) {
                return (int) $panel->id; // matches any active panel, incl. new ones (Karachi, Peshawar…)
            }
        }

        // No city match → fall back to the default panel (Karachi), else keep the role default.
        try {
            $default = PanelType::where('is_default', 1)->where('status', 1)->orderBy('sort')->first();
            if ($default) {
                return (int) $default->id;
            }
        } catch (\Throwable $e) {
            // ignore — caller keeps its role default
        }

        return null;
    }

    /** Legacy permission column backing each functional panel id. */
    public const PANEL_COLUMN = [
        1 => 'emp_access_phone',
        2 => 'emp_access_web',
        3 => 'emp_access_test',
        4 => 'panel_type_4',
        5 => 'panel_type_5',
        6 => 'panel_type_6',
    ];

    /**
     * Give a new user working access on their assigned city panel:
     *   - add the panel id to emp_panel_access,
     *   - if the panel's permission column is empty, seed it from panel 1
     *     (emp_access_phone) so they have a working permission set there.
     * No-op for panels without a backing column (7+).
     */
    public static function grantCityPanel(User $user, int $panelId): void
    {
        // Ensure the panel is in the assigned-panels list.
        $assigned = array_filter(array_map('trim', explode(',', (string) $user->emp_panel_access)), fn($v) => $v !== '');
        if (!in_array((string) $panelId, $assigned, true)) {
            $assigned[] = (string) $panelId;
            $user->emp_panel_access = implode(',', $assigned);
        }

        // Seed the panel's permission column from panel 1 if it's currently empty.
        $col = self::PANEL_COLUMN[$panelId] ?? null;
        if ($col && trim((string) $user->$col) === '' && $col !== 'emp_access_phone') {
            $user->$col = $user->emp_access_phone;
        }
    }

    private static function normalize(string $s): string
    {
        return preg_replace('/[^a-z]/', '', strtolower($s));
    }
}
