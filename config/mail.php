<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    | The default outgoing mailer. On the Hello site this is Hello's SMTP; on
    | the CrazyRays (florida) portal this is ALSO pointed at the Hello domain so
    | every customer/order + internal/system email is sent from Hello. Recruitment
    | mail is routed explicitly to the 'crazyrays' mailer below.
    | (Supports both MAIL_MAILER and the legacy MAIL_DRIVER env names.)
    */

    'default' => env('MAIL_MAILER', env('MAIL_DRIVER', 'smtp')),

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    */

    'mailers' => [

        // DEFAULT — customer/order + internal/system email. Hello domain.
        'smtp' => [
            'transport'    => 'smtp',
            'host'         => env('MAIL_HOST', 'smtp.mailgun.org'),
            'port'         => env('MAIL_PORT', 587),
            'encryption'   => env('MAIL_ENCRYPTION', 'tls'),
            'username'     => env('MAIL_USERNAME'),
            'password'     => env('MAIL_PASSWORD'),
            'timeout'      => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN'),
        ],

        // SECONDARY — CrazyRays recruitment/agent email (application approved/rejected/
        // confirmation, agent activated, NDA/contract required). Sent from careers@crazyrays
        // via the CrazyRays SMTP so SPF/DKIM stay valid. Falls back to the default creds when
        // the CR_MAIL_* vars aren't set (e.g. on the Hello server).
        'crazyrays' => [
            'transport'  => 'smtp',
            'host'       => env('CR_MAIL_HOST', env('MAIL_HOST')),
            'port'       => env('CR_MAIL_PORT', env('MAIL_PORT', 465)),
            'encryption' => env('CR_MAIL_ENCRYPTION', env('MAIL_ENCRYPTION', 'tls')),
            'username'   => env('CR_MAIL_USERNAME', env('MAIL_USERNAME')),
            'password'   => env('CR_MAIL_PASSWORD', env('MAIL_PASSWORD')),
            'timeout'    => null,
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path'      => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs'),
        ],

        'log' => [
            'transport' => 'log',
            'channel'   => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address (default mailer = Hello)
    |--------------------------------------------------------------------------
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'support@hellotransport.com'),
        'name'    => env('MAIL_FROM_NAME', 'Hello Transport'),
    ],

    /*
    |--------------------------------------------------------------------------
    | CrazyRays "From" Address (paired with the 'crazyrays' mailer)
    |--------------------------------------------------------------------------
    */

    'crazyrays_from' => [
        'address' => env('CR_MAIL_FROM_ADDRESS', env('CR_CAREERS_EMAIL', 'careers@crazyrayssolutions.com.pk')),
        'name'    => env('CR_MAIL_FROM_NAME', 'Crazy Rays Solutions'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Markdown Mail Settings
    |--------------------------------------------------------------------------
    */

    'markdown' => [
        'theme' => 'default',
        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],

];
