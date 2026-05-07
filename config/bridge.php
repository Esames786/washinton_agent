<?php

return [

    /*
    |--------------------------------------------------------------------------
    | HR Portal (washinton_hr) — outbound bridge config
    |--------------------------------------------------------------------------
    | Washington Agent calls these endpoints on the HR portal.
    | HRPORTAL_SHARED_KEY must match HR_BRIDGE_SHARED_KEY in washinton_hr/.env
    */
    'hrportal' => [
        'base_url'                    => env('HRPORTAL_BASE_URL', ''),
        'shared_key'                  => env('HRPORTAL_SHARED_KEY', ''),
        'agent_login_endpoint'        => env('HRPORTAL_AGENT_LOGIN_ENDPOINT', '/bridge/agent/login'),
        'agent_status_endpoint'       => env('HRPORTAL_AGENT_STATUS_ENDPOINT', '/bridge/agent/status'),
        'create_employee_endpoint'    => env('HRPORTAL_CREATE_EMPLOYEE_ENDPOINT', '/bridge/employee/create'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Washington inbound bridge key
    |--------------------------------------------------------------------------
    | The HR portal sends this key when calling Washington's bridge endpoints.
    | Must match HR_BRIDGE_SHARED_KEY in washinton_hr/.env
    */
    'washington' => [
        'shared_key' => env('WASHINGTON_BRIDGE_SHARED_KEY', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | External bridge key (crazyrayssolutions → washinton_agent)
    |--------------------------------------------------------------------------
    | crazyrayssolutions.com.pk sends this key when calling /bridge/register
    | and /bridge/login. Same value as old daydispatchagent BRIDGE_SHARED_KEY
    | so crazyrayssolutions needs zero config changes.
    */
    'shared_key'     => env('BRIDGE_SHARED_KEY', ''),
    'bridge_url'     => env('BRIDGE_PUBLIC_URL', ''),
    'allowed_origins' => array_filter(
        array_map('trim', explode(',', env('BRIDGE_ALLOWED_ORIGINS', '')))
    ),

];
