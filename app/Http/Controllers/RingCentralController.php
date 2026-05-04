<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RingCentral\SDK\SDK;

class RingCentralController extends Controller
{
    private $clientId = 'Z6Dv9VRd2Ejc0t56Y1fMxa';
    private $clientSecret = 'XbkyU93aXwrfyQJvYzqWph54MrprkvzMXam5YbEXdTu6';
    private $server = 'https://platform.ringcentral.com';

    public function ring_central_portal(Request $request){
      
        return view('main.ringcentral.ringcentral');
    }
    public function authenticate()
    {
        $redirectUri = 'https://washington.shawntransport.com/callback';

        // Initialize the SDK
        $sdk = new SDK($this->clientId, $this->clientSecret, $this->server);

        // Get the platform object
        $platform = $sdk->platform();

        // Generate the authentication URL
        $authUrl = $platform->authUrl([
            'redirectUri' => $redirectUri,
            'state' => 'optionalState', // Optional: Add a state parameter for security
        ]);

        // Redirect to the authentication URL
        return redirect($authUrl);
    }

    public function callback(Request $request)
    {
        // Initialize the SDK
        $sdk = new SDK($this->clientId, $this->clientSecret, $this->server);
        $platform = $sdk->platform();

        // Authenticate using the authorization code
        $platform->login([
            'code' => $request->query('code'),
            'redirectUri' => 'https://shawntransport.test/callback',
        ]);

        // Store the access token in the session
        session(['ringcentral_access_token' => $platform->auth()->data()]);

        // Redirect to the main page
        return redirect('/ring_central_portal');
    }
}
