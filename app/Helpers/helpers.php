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
