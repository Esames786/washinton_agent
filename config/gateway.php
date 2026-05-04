<?php

return [

    /*
    |--------------------------------------------------------------------------
    | ShipA1 / Washington default gateway
    |--------------------------------------------------------------------------
    */
    'base_url' => env('CENTRAL_GATEWAY_BASE', 'https://roadya.com'),
    'api_key'  => env('CENTRAL_GATEWAY_API_KEY', ''),
    'secret'   => env('CENTRAL_GATEWAY_API_SECRET', ''),
    'timeout'  => (int) env('CENTRAL_GATEWAY_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | AutoHaulingQuotes.com gateway credentials
    |--------------------------------------------------------------------------
    */
    'autohaul' => [
        'platform' => env('AUTOHAUL_PLATFORM', 'washington-autohaul'),
        'api_key'  => env('AUTOHAUL_API_KEY', ''),
        'secret'   => env('AUTOHAUL_API_SECRET', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Client portal URLs (iframe embed)
    |--------------------------------------------------------------------------
    */
    'portal_url_washington' => env('GATEWAY_PORTAL_WASHINGTON', ''),
    'portal_url_autohaul'   => env('GATEWAY_PORTAL_AUTOHAUL', ''),

];
