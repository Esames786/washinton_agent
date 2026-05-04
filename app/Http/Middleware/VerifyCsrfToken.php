<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'get-auto-chat2',
        'get-auto-convo',
        // Bridge endpoints called from external portals (crazyrays, washinton_hr)
        'bridge/register',
        'bridge/login',
        'bridge/sso/consume',
        'bridge/washington/agent/commission',
        'bridge/washington/agent/status',
        // Chat widget endpoints (called from iframe)
        'chat/send',
        'chat/history',
        'chat/show-history',
        'chat/update-read',
    ];
}
