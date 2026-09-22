<?php

use Illuminate\Support\Str;

if (!function_exists('str_limit')) {
    function str_limit($value, $limit = 20, $end = '.......')
    {
        return Str::limit($value, $limit, $end);
    }
}

if (!function_exists('customer_url')) {
    /**
     * Build a CUSTOMER-facing URL (order-continuation / booking links emailed to customers).
     * Uses config('app.customer_url') so the CrazyRays (florida) portal can emit hellotransport.com
     * links while agent-facing url() links stay on the portal's own domain.
     */
    function customer_url($path = '')
    {
        $base = rtrim((string) config('app.customer_url', config('app.url')), '/');
        return $path === '' ? $base : $base . '/' . ltrim((string) $path, '/');
    }
}

if (!function_exists('portal_file_url')) {
    /**
     * URL for an uploaded file that may live on the SIBLING agent-portal deployment.
     * hellotransport.com and florida.crazyrayssolutions.com.pk are separate cPanel sites
     * sharing one database — a payment screenshot uploaded on one domain does not exist
     * on the other's disk. If the file isn't here, it must be on the sibling.
     */
    function portal_file_url($path)
    {
        $path = ltrim((string) ($path ?? ''), '/');
        if ($path === '') {
            return '';
        }
        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }
        if (is_file(public_path($path))) {
            return asset($path);
        }
        $host    = request()->getHost();
        $sibling = (stripos($host, 'crazyrays') !== false)
            ? 'https://hellotransport.com'
            : 'https://florida.crazyrayssolutions.com.pk';
        return $sibling . '/' . $path;
    }
}

if (!function_exists('mask_phone')) {
    /**
     * Mask a phone number to its last 3 digits (e.g. xxxxxxx503). Safe on null/short/empty
     * values — the old inline str_repeat(strlen()-3) crashed with a negative count whenever
     * an order had a short or missing phone.
     */
    function mask_phone($phone)
    {
        $phone = (string) ($phone ?? '');
        $len   = strlen($phone);

        if ($len === 0) {
            return '';
        }
        if ($len <= 3) {
            return str_repeat('x', $len);
        }

        return str_repeat('x', $len - 3) . substr($phone, -3);
    }
}

if (!function_exists('asset_v')) {
    /**
     * A versioned URL for a file in public/, so browsers pick up a changed asset by themselves.
     *
     * Requested 22 Sep 2026: after a deploy, agents had to be told to hard-refresh (Ctrl+F5) or
     * they kept running the old JS. The version is the file's own modification time, so:
     *
     *   - a file that changed in the deploy gets a new URL and is re-fetched once, automatically;
     *   - a file that did NOT change keeps its URL and stays cached, which a single global version
     *     number would needlessly throw away on every release;
     *   - nothing has to be remembered or bumped by hand at release time.
     *
     * Falls back to the plain URL when the file is missing (CDN paths, generated files), so a
     * wrong path can never take the page down.
     */
    function asset_v($path)
    {
        $clean = ltrim((string) $path, '/');
        $full  = public_path($clean);

        if (!is_file($full)) {
            return url($clean);
        }

        $stamp = @filemtime($full);

        return url($clean) . ($stamp ? '?v=' . $stamp : '');
    }
}
