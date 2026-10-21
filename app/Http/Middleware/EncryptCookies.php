<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array
     */
    protected $except = [
        // Device restriction token (App\Support\DeviceGate): a random value that is itself the
        // secret, kept raw so changing APP_KEY does not turn every browser into a new device.
        'ht_device',
    ];
}
