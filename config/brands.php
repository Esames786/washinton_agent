<?php

/*
|--------------------------------------------------------------------------
| Brand definitions
|--------------------------------------------------------------------------
| Per-user branding source of truth. CrazyRays-originated users
| (user.is_crazyrays = 1) see the "crazyrays" brand; everyone else sees
| the default "hellotransport" brand.
|
| Resolve via App\Support\Brand::for($user) or App\Support\Brand::current().
*/

return [

    'default' => 'hellotransport',

    'brands' => [

        'hellotransport' => [
            'name'      => 'Hello Transport',
            'legal'     => 'Hello Transport LLC',
            'site'      => 'https://www.hellotransport.com',
            'login_url' => 'https://hellotransport.com/loginn',
            'email'     => 'support@hellotransport.com',
            'phone'     => '1 (844) 474-4721',
            'footer'    => 'Hello Transport. All Rights Reserved.',
        ],

        'crazyrays' => [
            'name'      => 'Crazy Rays Solutions',
            'legal'     => 'Crazy Rays Solutions',
            'site'      => 'https://crazyrayssolutions.com.pk',
            'login_url' => 'https://crazyrayssolutions.com.pk/login',
            'email'     => 'info@crazyrayssolutions.com.pk',
            'phone'     => '0313-8432343',
            'footer'    => 'Crazy Rays Solutions. All Rights Reserved.',
        ],

    ],
];
