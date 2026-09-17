<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Hide prices in the customer quote email
    |--------------------------------------------------------------------------
    | Client request (allenmanager, 17 Sep 2026) was raised against the ShipA1 quote email, but the
    | Hello Transport quote email is the same design with the same price table, so it follows the
    | same rule — otherwise one brand keeps leaking the figures the other now hides.
    |
    | Every price becomes an Unlock Price button opening the same order page the Place Order button
    | already opened, where the amount is shown. Set QUOTE_EMAIL_HIDE_PRICES=false to show them.
    */
    'email_hide_prices' => env('QUOTE_EMAIL_HIDE_PRICES', true),

    'unlock_label' => env('QUOTE_EMAIL_UNLOCK_LABEL', 'Unlock Price'),

];
