<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Stevebauman\Location\Facades\Location;
use Symfony\Component\HttpFoundation\IpUtils;

/**
 * Keeps visitors from blocked countries off the Hello "Apply" form (config/signup.php).
 * Client, 23 Nov 2026: block Pakistan IPs from the Hello Transport signup.
 *
 * The country comes from the IP (stevebauman/location, already used at signup), cached a day
 * per IP. If it can't be found the visitor is let through: a geo-API outage must never stop a
 * real applicant. A VPN gets around any IP rule; this stops the ordinary case.
 */
class BlockSignupCountries
{
    public const MESSAGE = 'Hello Transport is not accepting applications from your country.';

    public function handle(Request $request, Closure $next)
    {
        $blocked = (array) config('signup.blocked_countries', []);
        $ip      = (string) $request->ip();
        $allow   = (array) config('signup.allow_ips', []);

        if (! $blocked || ($allow && IpUtils::checkIp($ip, $allow))) {
            return $next($request);
        }

        $country = self::countryOf($ip);
        if ($country === null || ! in_array($country, $blocked, true)) {
            return $next($request);
        }

        Log::info('[signup] blocked by country', ['ip' => $ip, 'country' => $country, 'method' => $request->method()]);

        if ($request->isMethod('post') || $request->expectsJson()) {
            return response()->json(['success' => false, 'message' => self::MESSAGE], 403);
        }
        if ($to = config('signup.blocked_redirect')) {
            return redirect()->away($to);
        }

        return response()->view('auth.apply_unavailable', ['message' => self::MESSAGE], 403);
    }

    /** Two-letter country code for a public IP, or null when unknown (private IP, lookup failed). */
    public static function countryOf(string $ip): ?string
    {
        if (! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return null;
        }

        $key    = 'signup:country:' . $ip;
        $cached = Cache::get($key);
        if ($cached !== null) {
            return $cached === '' ? null : $cached;
        }

        $code = '';
        try {
            $position = Location::get($ip);
            if ($position && ! empty($position->countryCode)) {
                $code = strtoupper((string) $position->countryCode);
            }
        } catch (\Throwable $e) {
            Log::info('[signup] country lookup failed: ' . $e->getMessage(), ['ip' => $ip]);
        }

        // A failed lookup is retried after 10 minutes, a found country kept for a day.
        Cache::put($key, $code, $code === '' ? now()->addMinutes(10) : now()->addDay());

        return $code === '' ? null : $code;
    }
}
