<?php

namespace App\Support;

use App\User;
use App\UserDevice;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Schema;

/**
 * Device restriction (client email 20 Oct 2026, item 7). Policy per user, set on the edit screen:
 *
 *   off      — default: devices are recorded and approved automatically. Nothing changes.
 *   one      — one approved device; the first is approved automatically, any other waits for an
 *              admin ("approve & replace" swaps it in).
 *   approved — every new device waits for an admin.
 * A rejected or revoked device is refused under every policy.
 *
 * A device is a long-lived random cookie (only its hash is stored). Fingerprinting was not used:
 * identical call-centre PCs collide, and it changes with every browser update. Clearing cookies
 * or switching browser therefore counts as a new device; that is the accepted trade-off.
 *
 * Admins (role 1) and "view as" are never checked. Called from EnforceUserSecurity.
 */
class DeviceGate
{
    public const COOKIE = 'ht_device';

    public const POLICIES = [
        'off'      => 'Off — record devices only',
        'one'      => 'One device only',
        'approved' => 'Approved devices only',
    ];

    /** Paths a waiting device may still open (and that must not claim the single session). */
    private const HOLDING_PATHS = ['device-pending', 'device-status', 'logout'];

    /** Never checked: sign-in steps, machine-to-machine and public routes. */
    private const SKIP_PATHS = [
        'loginn', 'loginn/*', 'login', 'getlogin2', 'code_verify', 'verify/*', 'resend_verify',
        'logoutAllAccounts', 'bridge/*', 'api/*', 'r/webhook', 'here', 'scope/exit',
    ];

    /** The migration has run (deploy safety: code can go live before it). */
    public static function ready(): bool
    {
        static $ready = null;

        return $ready ??= Schema::hasTable('user_devices') && Schema::hasColumn('user', 'device_policy');
    }

    /** This user can be restricted (admins never are). */
    public static function applies(?User $user): bool
    {
        return self::ready() && $user && (int) $user->role !== 1;
    }

    public static function policy(User $user): string
    {
        $p = (string) ($user->device_policy ?? 'off');

        return array_key_exists($p, self::POLICIES) ? $p : 'off';
    }

    public static function skips(Request $request): bool
    {
        return $request->is(...self::SKIP_PATHS) || ! $request->hasSession();
    }

    public static function isHoldingPath(Request $request): bool
    {
        return $request->is(...self::HOLDING_PATHS);
    }

    /** This browser's device token, minted (and queued as a cookie) on first sight. */
    public static function token(Request $request): string
    {
        $token = $request->attributes->get(self::COOKIE) ?? $request->cookie(self::COOKIE);

        if (! is_string($token) || ! preg_match('/^[a-f0-9]{64}$/', $token)) {
            $token = bin2hex(random_bytes(32));
        }

        if (! $request->attributes->has(self::COOKIE)) {
            $request->attributes->set(self::COOKIE, $token);
            // Re-sent every request so it never ages out (Chrome caps cookies at 400 days).
            Cookie::queue(Cookie::make(self::COOKIE, $token, 60 * 24 * 400, '/', null, $request->isSecure(), true, false, 'lax'));
        }

        return $token;
    }

    /** The device row for this user and browser, created on first sight. */
    public static function resolve(User $user, Request $request): UserDevice
    {
        $hash = hash('sha256', self::token($request));
        $device = UserDevice::where('user_id', $user->id)->where('token_hash', $hash)->first();
        $now = now();

        if (! $device) {
            $policy = self::policy($user);
            $status = UserDevice::PENDING;
            if ($policy === 'off'
                || ($policy === 'one' && ! UserDevice::where('user_id', $user->id)->where('status', UserDevice::APPROVED)->exists())) {
                $status = UserDevice::APPROVED;
            }
            [$browser, $os] = self::describe((string) $request->userAgent());

            try {
                $device = UserDevice::create([
                    'user_id' => $user->id, 'token_hash' => $hash, 'browser' => $browser, 'os' => $os,
                    'user_agent' => mb_substr((string) $request->userAgent(), 0, 500),
                    'first_ip' => $request->ip(), 'last_ip' => $request->ip(),
                    'first_seen_at' => $now, 'last_seen_at' => $now, 'status' => $status,
                    'approved_at' => $status === UserDevice::APPROVED ? $now : null,
                    'source' => session('cr_origin') || (int) ($user->is_crazyrays ?? 0) === 1 ? 'crazyrays' : 'hello',
                ]);
            } catch (QueryException $e) {
                // Two first requests at once from the same browser: the other one created it.
                $device = UserDevice::where('user_id', $user->id)->where('token_hash', $hash)->firstOrFail();
            }

            return $device;
        }

        if (! $device->last_seen_at || $device->last_seen_at->lt($now->copy()->subMinutes(5)) || $device->last_ip !== $request->ip()) {
            $device->forceFill(['last_seen_at' => $now, 'last_ip' => $request->ip()])->saveQuietly();
        }

        return $device;
    }

    public static function allows(User $user, UserDevice $device): bool
    {
        if (in_array($device->status, [UserDevice::REJECTED, UserDevice::REVOKED], true)) {
            return false;
        }

        return self::policy($user) === 'off' || $device->status === UserDevice::APPROVED;
    }

    /** Where a waiting device is sent: the waiting page, or a 423 for background calls. */
    public static function hold(Request $request, UserDevice $device)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message' => 'This device is waiting for admin approval.',
                'device_status' => $device->status,
            ], 423);
        }

        return redirect('/device-pending');
    }

    /** Approve a device; under "one device", or when asked, every other approved device is revoked. */
    public static function approve(UserDevice $device, User $admin, bool $replaceOthers = false): void
    {
        $now = now();
        $owner = $device->user;

        if ($replaceOthers || ($owner && self::policy($owner) === 'one')) {
            UserDevice::where('user_id', $device->user_id)->where('id', '!=', $device->id)
                ->where('status', UserDevice::APPROVED)
                ->update(['status' => UserDevice::REVOKED, 'revoked_by' => $admin->id, 'revoked_at' => $now, 'updated_at' => $now]);
        }

        $device->forceFill([
            'status' => UserDevice::APPROVED, 'approved_by' => $admin->id, 'approved_at' => $now,
            'revoked_by' => null, 'revoked_at' => null,
        ])->save();
    }

    /**
     * After an admin changes the policy. Switching to "one device" keeps the most recently used
     * approved device and revokes the others, so the rule holds from the next request.
     */
    public static function policyChanged(User $user, User $admin): void
    {
        if (! self::applies($user) || self::policy($user) !== 'one') {
            return;
        }

        $keep = UserDevice::where('user_id', $user->id)->where('status', UserDevice::APPROVED)
            ->orderByDesc('last_seen_at')->value('id');
        if ($keep) {
            UserDevice::where('user_id', $user->id)->where('status', UserDevice::APPROVED)->where('id', '!=', $keep)
                ->update(['status' => UserDevice::REVOKED, 'revoked_by' => $admin->id, 'revoked_at' => now(), 'updated_at' => now()]);
        }
    }

    /** "Chrome", "Windows" etc. from a user agent: shown to the admin as a hint only. */
    public static function describe(string $ua): array
    {
        $browser = match (true) {
            str_contains($ua, 'Edg/')                                => 'Edge',
            str_contains($ua, 'OPR/') || str_contains($ua, 'Opera') => 'Opera',
            str_contains($ua, 'Firefox/')                            => 'Firefox',
            str_contains($ua, 'Chrome/')                             => 'Chrome',
            str_contains($ua, 'Safari/')                             => 'Safari',
            default                                                  => null,
        };
        $os = match (true) {
            str_contains($ua, 'Windows')                             => 'Windows',
            str_contains($ua, 'Android')                             => 'Android',
            str_contains($ua, 'iPhone') || str_contains($ua, 'iPad') => 'iOS',
            str_contains($ua, 'Mac OS X')                            => 'Mac',
            str_contains($ua, 'Linux')                               => 'Linux',
            default                                                  => null,
        };

        return [$browser, $os];
    }
}
