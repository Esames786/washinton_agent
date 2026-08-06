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
