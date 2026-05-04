<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RingCentral\SDK\SDK;

class OAuthController extends Controller
{
    public function handleCallback(Request $request)
    {
         $clientId = 'Z6Dv9VRd2Ejc0t56Y1fMxa';
         $clientSecret = 'XbkyU93aXwrfyQJvYzqWph54MrprkvzMXam5YbEXdTu6';
         $server = 'https://platform.ringcentral.com';

        // Initialize the SDK
        $sdk = new SDK($clientId, $clientSecret, $server);

        // Get the authorization code from the query string
        $authCode = $request->query('code');

        if (!$authCode) {
            return response()->json(['error' => 'Authorization code not found'], 400);
        }

        try {
            // Exchange authorization code for access token
            $platform = $sdk->platform();
            $platform->login([
                'code' => $authCode,
                'redirectUri' => 'https://washington.shawntransport.com/callback',
            ]);

            // Fetch SIP information
            $sipInfoResponse = $platform->post('/restapi/v1.0/client-info/sip-provision', [
                'sipInfo' => [
                    [
                        'transport' => 'WSS', // WebSocket Secure
                    ],
                ],
            ]);

            $sipInfo = $sipInfoResponse->json()->sipInfo[0];

            // Save the access token and SIP info in the session
            session([
                'ringcentral_access_token' => $platform->auth()->data(),
                'ringcentral_sip_info' => $sipInfo,
            ]);

            return redirect('/ring_central_portal')->with('success', 'Authorization successful!');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to authorize: ' . $e->getMessage()], 500);
        }
    }
}