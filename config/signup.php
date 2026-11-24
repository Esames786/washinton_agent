<?php

/*
 * Who may open the Hello Transport "Apply" form (GET/POST /register).
 * Client, 23 Nov 2026: no applications from Pakistan IPs — CrazyRays recruits there.
 */
$list = static fn (?string $v) => array_values(array_filter(array_map('trim', explode(',', (string) $v))));

return [

    // ISO country codes, comma separated. Empty = nobody is blocked.
    'blocked_countries' => array_map('strtoupper', $list(env('SIGNUP_BLOCKED_COUNTRIES', 'PK'))),

    // IPs / CIDR ranges that may still use the form from a blocked country (office, testers).
    'allow_ips' => $list(env('SIGNUP_ALLOW_IPS', '')),

    // Optional URL to send a blocked visitor to instead of the "not available" page.
    'blocked_redirect' => env('SIGNUP_BLOCKED_REDIRECT'),

];
