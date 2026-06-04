<?php

return [
    // Keep call-control disabled by default in development.
    'ringcentral_call_control' => (bool) env('RINGCENTRAL_CALL_CONTROL_ENABLED', false),

    // Single local testing switch for R-Dialer UI/testing helpers.
    // This is intentionally tied to APP_ENV=local.
    'ringcentral_local_testing' => env('APP_ENV') === 'local',
];
