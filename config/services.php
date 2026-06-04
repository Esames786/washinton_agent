<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'brevo' => [
        'key' => env('BREVO_API_KEY', ''),
    ],
    'central_gateway' => [
        'base' => env('CENTRAL_GATEWAY_BASE', 'https://central-gateway.test'),
        'key' => env('CENTRAL_GATEWAY_API_KEY'),
        'secret' => env('CENTRAL_GATEWAY_API_SECRET'),
        'timeout' => env('CENTRAL_GATEWAY_TIMEOUT', 30),
    ],

    'recaptcha' => [
        'site_key'   => env('RECAPTCHA_SITE_KEY', ''),
        'secret_key' => env('RECAPTCHA_SECRET_KEY', ''),
    ],

    'ringcentral' => [
        'client_id'                        => env('RINGCENTRAL_CLIENT_ID'),
        'client_secret'                    => env('RINGCENTRAL_CLIENT_SECRET'),
        'server'                           => env('RINGCENTRAL_SERVER', 'https://platform.ringcentral.com'),
        'redirect_url'                     => env('RINGCENTRAL_REDIRECT_URL'),
        'webhook_url'                      => env('RINGCENTRAL_WEBHOOK_URL'),
        'webhook_secret'                   => env('RINGCENTRAL_WEBHOOK_SECRET', ''),
        'webhook_scope_batch_window_ms'    => env('RINGCENTRAL_WEBHOOK_SCOPE_BATCH_WINDOW_MS', 800),
        'webhook_event_history_size'       => env('RINGCENTRAL_WEBHOOK_EVENT_HISTORY_SIZE', 120),
        'token_refresh_window_seconds'     => env('RINGCENTRAL_TOKEN_REFRESH_WINDOW_SECONDS', 60),
    ],

];
