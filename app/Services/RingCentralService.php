<?php

namespace App\Services;

use RingCentral\SDK\SDK;
use App\RingCentralUser;
use App\RingCentralCallLog;
use App\RingCentralMessage;
use App\RingCentralVoicemail;
use App\Support\VoicemailOnlyLog;
use Illuminate\Support\Facades\Session;
use Exception;
use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RingCentralService
{
    private $sdk;
    private $platform;
    private $clientId;
    private $clientSecret;
    private $server;
    private $tokensDirName = 'ringcentral_tokens';

    /**
     * Initialize R-Dialer SDK
     */
    public function __construct()
    {
        $this->clientId = config('services.ringcentral.client_id');
        $this->clientSecret = config('services.ringcentral.client_secret');
        $this->server = config('services.ringcentral.server');

        // Create SDK with proper SSL certificate configuration
        $this->sdk = $this->createSdkWithSslConfig();
        $this->platform = $this->sdk->platform();
    }

    /**
     * Create SDK instance with SSL certificate configuration
     */
    private function createSdkWithSslConfig()
    {
        try {
            // Get CA bundle path from Composer
            $caBundle = \Composer\CaBundle\CaBundle::getSystemCaRootBundlePath();
            
            // Build Guzzle options
            $guzzleOptions = [];
            if ($caBundle && file_exists($caBundle)) {
                $guzzleOptions['verify'] = $caBundle;
            } else if (app()->environment('local', 'development')) {
                // Disable SSL verification only for development (NOT recommended for production)
                $guzzleOptions['verify'] = false;
            }
            
            // Create custom Guzzle client with SSL options
            $guzzle = new GuzzleClient($guzzleOptions);
            
            // Pass Guzzle client to SDK as 6th parameter
            return new SDK(
                $this->clientId,
                $this->clientSecret,
                $this->server,
                'WebPhone',  // app name
                '1.0.0',     // app version
                $guzzle      // custom HTTP client
            );
        } catch (Exception $e) {
            
            // Fallback: create SDK without custom Guzzle (will use default)
            if (app()->environment('local', 'development')) {
                // For development, create with SSL disabled
                $guzzle = new GuzzleClient(['verify' => false]);
                return new SDK(
                    $this->clientId,
                    $this->clientSecret,
                    $this->server,
                    'WebPhone',
                    '1.0.0',
                    $guzzle
                );
            }
            
            // For production, create with default SSL handling
            return new SDK(
                $this->clientId,
                $this->clientSecret,
                $this->server
            );
        }
    }

    /**
     * Refresh the access token using the refresh token
     */


public function refreshToken($ringCentralUser, $forceRefresh = false)
{
    $refreshLockKey = $this->ringCentralRefreshLockKey($ringCentralUser);

    try {
        return Cache::lock($refreshLockKey, 60)->block(15, function () use ($ringCentralUser, $forceRefresh) {
            return DB::transaction(function () use ($ringCentralUser, $forceRefresh) {

            // Always reload fresh data from DB (avoid stale tokens)
            $ringCentralUser->refresh();

            // If token is still valid beyond the configured refresh window AND not force refresh, do NOT refresh
            if (!$forceRefresh && $ringCentralUser->token_expires_at &&
                now()->lt($ringCentralUser->token_expires_at->copy()->subSeconds($this->ringCentralTokenRefreshWindowSeconds()))) {


                if (!$ringCentralUser->is_active) {
                    $ringCentralUser->update(['is_active' => true]);
                }

                return true;
            }

            // Create fresh SDK instance with SSL configuration
            $sdk = $this->createSdkWithSslConfig();
            $platform = $sdk->platform();

            // Use session and file tokens only (remove DB-token focus)
            $authData = Session::get('ringcentral_auth_data_' . $ringCentralUser->user_id);

            // Get token file based on phone_number (multiple users share same phone's tokens)
            $tokensFile = $this->getTokensFilePathForUser($ringCentralUser->phone_number);
            $fileTokens = null;
            if (file_exists($tokensFile) && filesize($tokensFile) > 0) {
                try {
                    $fileTokens = json_decode(file_get_contents($tokensFile), true);
                } catch (Exception $_e) {
                    $fileTokens = null;
                }
            }
            $authfileTokensData = [
                    'token_type' => $fileTokens['token_type'] ?? 'Bearer',
                    'access_token' => $fileTokens['access_token'] ?? null,
                    'refresh_token' => $fileTokens['refresh_token'] ?? null,
                    'expires_in' => isset($fileTokens['expires_in']) ? intval($fileTokens['expires_in']) : null,
                    'refresh_token_expires_in' => $fileTokens['refresh_token_expires_in'] ?? null,
                    'refresh_token_expire_time' => $fileTokens['refresh_token_expire_time'] ?? null,
            ];
            

            $refreshMetaSource = is_array($fileTokens) ? $fileTokens : (is_array($authData) ? $authData : []);

            $resolvedRefreshExpiry = $this->resolveRefreshTokenExpiresAt($refreshMetaSource);
            if ($resolvedRefreshExpiry &&
                (!$ringCentralUser->refresh_token_expires_at || $resolvedRefreshExpiry->gt($ringCentralUser->refresh_token_expires_at))) {
                $ringCentralUser->update(['refresh_token_expires_at' => $resolvedRefreshExpiry]);
                $ringCentralUser->refresh();
            }

            // Check if refresh token is expired (after reconciling metadata)
            if ($ringCentralUser->refresh_token_expires_at && now()->gte($ringCentralUser->refresh_token_expires_at)) {
                $ringCentralUser->update(['is_active' => false]);
                return false;
            }

            // Decide which tokens the SDK will use: file (preferred) or session
            if (is_array($fileTokens) && !empty($fileTokens['access_token']) && !empty($fileTokens['refresh_token'])) {
                $platform->auth()->setData($authfileTokensData);


                // sync session so other checks see same token
                try {
                    Session::put('ringcentral_auth_data_' . $ringCentralUser->user_id, $fileTokens);
                    // Legacy diagnostic comparison only. Disabled so refresh-token state has one source of truth.
                    // Session::put('ringcentral_login_refresh_token_' . $ringCentralUser->user_id, $fileTokens['refresh_token']);
                } catch (Exception $_e) {
                }
            }
            else if (is_array($authData) && !empty($authData['access_token']) && !empty($authData['refresh_token'])) {
                $platform->auth()->setData($authData);

            }
            else {
                // No valid tokens available
                $ringCentralUser->update(['is_active' => false]);
                return false;
            }

            // Perform refresh
            $platform->refresh();

            // Get new auth data (OLD refresh token is now INVALID)
            $newAuthData = $platform->auth()->data();

            // Persist NEW tokens immediately
            $ringCentralUser->update([
                'access_token'     => encrypt($newAuthData['access_token']),
                'refresh_token'    => encrypt($newAuthData['refresh_token']),
                'token_expires_at' => now()->addSeconds($newAuthData['expires_in']),
                'refresh_token_expires_at' => $this->resolveRefreshTokenExpiresAt($newAuthData),
                'is_active'        => true,
            ]);

            // Optional: update session as cache ONLY
            Session::put(
                'ringcentral_auth_data_' . $ringCentralUser->user_id,
                $newAuthData
            );

            // Save tokens to storage file for compatibility with community_manager_test.php
            try {
                // Use phone_number for token file (multiple users share same phone's tokens)
                $tokensFile = $this->getTokensFilePathForUser($ringCentralUser->phone_number);
                file_put_contents($tokensFile, json_encode($newAuthData, JSON_PRETTY_PRINT));
            } catch (Exception $fileEx) {
            }


            return true;
            });
        });

    } catch (Exception $e) {
        $this->logRefreshFailure($ringCentralUser, $forceRefresh, $refreshLockKey, $e);
    }
}


    private function ringCentralRefreshLockKey($ringCentralUser): string
    {
        $phoneDigits = preg_replace('/\D+/', '', (string) ($ringCentralUser->phone_number ?? ''));
        $scope = $phoneDigits !== ''
            ? 'phone:' . $phoneDigits
            : 'user:' . (string) ($ringCentralUser->user_id ?? $ringCentralUser->id ?? 'unknown');

        return 'ringcentral:refresh-token:' . sha1($scope);
    }

    private function ringCentralTokenRefreshWindowSeconds(): int
    {
        $seconds = (int) config('services.ringcentral.token_refresh_window_seconds', 300);
        return max(60, $seconds);
    }

    private function logRefreshFailure($ringCentralUser, bool $forceRefresh, string $refreshLockKey, Exception $e): void
    {
        $context = [
            'user_id' => (int) ($ringCentralUser->user_id ?? 0),
            'ringcentral_user_id' => (int) ($ringCentralUser->id ?? 0),
            'phone_number_last4' => $this->lastFourDigits($ringCentralUser->phone_number ?? null),
            'force_refresh' => $forceRefresh,
            'lock_key_hash' => sha1($refreshLockKey),
            'exception_class' => get_class($e),
            'exception_code' => $e->getCode(),
            'message' => $this->sanitizeRingCentralError($e->getMessage()),
        ];

        if ($e instanceof ClientException && $e->getResponse()) {
            $context['http_status'] = $e->getResponse()->getStatusCode();
            $context['response_body'] = $this->sanitizeRingCentralError(
                substr((string) $e->getResponse()->getBody(), 0, 1000)
            );
        }

        try {
            Log::channel('ringcentral_token')->warning('R-Dialer refresh failed', $context);
        } catch (\Throwable $_e) {
            Log::warning('R-Dialer refresh failed', $context);
        }
    }

    private function sanitizeRingCentralError(?string $value): string
    {
        $value = (string) $value;
        $value = preg_replace('/(access_token|refresh_token|token)["\']?\s*[:=]\s*["\']?[^"\',\s}]+/i', '$1=[redacted]', $value);
        $value = preg_replace('/Bearer\s+[A-Za-z0-9._~+\/=-]+/i', 'Bearer [redacted]', $value);
        $value = preg_replace('/\b[A-Za-z0-9._~+\/=-]{80,}\b/', '[redacted]', $value);

        return substr($value, 0, 1000);
    }

    private function lastFourDigits(?string $value): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $value);

        return $digits !== '' ? substr($digits, -4) : null;
    }


    /**
     * Generate OAuth authentication URL
     *
     * @return string
     */
    public function getAuthenticationUrl()
    {
        $redirectUri = config('services.ringcentral.redirect_url');

        return $this->platform->authUrl([
            'redirectUri' => $redirectUri,
            'state' => uniqid(), // Generate unique state
        ]);
    }

    /**
     * Authenticate using authorization code
     *
     * @param string $code
     * @param int $userId
     * @return array
     */
    public function authenticateUser($code, $userId)
    {
        try {
            
            // Step 1: Authenticate the user with RingCentral
            $redirectUri = config('services.ringcentral.redirect_url');
            $this->platform->login([
                'code' => $code,
                'redirectUri' => $redirectUri,
            ]);

            $authData = $this->platform->auth()->data();

            // Save to session for testing
            Session::put('ringcentral_auth_data_' . $userId, $authData);
            // Legacy diagnostic comparison only. Disabled so refresh-token state has one source of truth.
            // Session::put('ringcentral_login_refresh_token_' . $userId, $authData['refresh_token']);

            // CONFIRMATION LOGS: Step-by-step verification



            // Step 2: Store user credentials
            $ringCentralUser = RingCentralUser::updateOrCreate(
                ['user_id' => $userId],
                [
                    'access_token' => encrypt($authData['access_token']),
                    'refresh_token' => encrypt($authData['refresh_token']),
                    'token_expires_at' => now()->addSeconds($authData['expires_in']),
                    'refresh_token_expires_at' => $this->resolveRefreshTokenExpiresAt($authData),
                    'is_active' => true,
                ]
            );
            
            // Step 3: Get extension info and phone numbers
            $extensionInfo = $this->getExtensionInfo();
            
            // Extract phone number safely (handle both array and object)
            // Note: Extension info doesn't contain phoneNumber directly, need to fetch from phone-number endpoint
            $phoneNumber = null;
            if (is_array($extensionInfo)) {
                $phoneNumber = $extensionInfo['phoneNumber'] ?? ($extensionInfo['phone_number'] ?? null);
            } elseif (is_object($extensionInfo)) {
                $phoneNumber = $extensionInfo->phoneNumber ?? ($extensionInfo->phone_number ?? null);
            }
            
            // If phoneNumber not in extension info, try to get from phone-number API
            if (!$phoneNumber) {
                try {
                    $phoneNumbersResponse = $this->platform->get('/restapi/v1.0/account/~/extension/~/phone-number');
                    $phoneNumbersData = $phoneNumbersResponse->json();
                    
                    // Find the primary/first phone number
                    if (isset($phoneNumbersData->records) && is_array($phoneNumbersData->records) && count($phoneNumbersData->records) > 0) {
                        $phoneNumber = $phoneNumbersData->records[0]->phoneNumber ?? null;
                    }
                } catch (Exception $e) {
                }
            }
            
            // Log if phone number is still missing
            if (!$phoneNumber) {
            }
            
            // Only update phone_number if we found one (don't overwrite existing with null)
            $updateData = [
                'extension_id' => is_array($extensionInfo) ? ($extensionInfo['id'] ?? null) : ($extensionInfo->id ?? null),
            ];
            
            if ($phoneNumber) {
                $updateData['phone_number'] = $phoneNumber;
            } else {
            }
            
            $ringCentralUser->update($updateData);
            $ringCentralUser->refresh(); // Reload from DB to get current phone_number

            // Save tokens to JSON file - use phone_number as key
            try {
                $tokensFile = $this->getTokensFilePathForUser($ringCentralUser->phone_number);
                file_put_contents($tokensFile, json_encode($authData, JSON_PRETTY_PRINT));
            } catch (Exception $fileEx) {
            }

            // Step 4: Register / refresh webhook subscription for real-time events
            $webhookResult = $this->ensureWebhookSubscription($userId, true, $this->platform);

            // Step 5: Return success
            return [
                'success' => true,
                'message' => !empty($webhookResult['success'])
                    ? 'Authentication successful and webhook subscription is active.'
                    : 'Authentication successful, but webhook subscription failed: ' . ($webhookResult['message'] ?? 'unknown error'),
                'user' => $ringCentralUser,
                'webhook' => $webhookResult,
            ];

        } catch (Exception $e) {
            // Log the error and return failure
            return [
                'success' => false,
                'message' => 'Authentication failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get or create platform instance with proper auth
     *
     * @param RingCentralUser $ringCentralUser
     * @return object|null Platform or null if auth fails
     */
    private function getPlatformWithAuth($ringCentralUser, $skipWebhookEnsure = false)
    {
        try {

            // Create fresh SDK instance with SSL configuration
            $sdk = $this->createSdkWithSslConfig();
            $platform = $sdk->platform();

            // Check if token needs refresh
            if (now()->isAfter($ringCentralUser->token_expires_at->copy()->subSeconds($this->ringCentralTokenRefreshWindowSeconds()))) {

                if (!$this->refreshToken($ringCentralUser, false)) {
                    return null;
                }

                // Reload from database after refresh
                $ringCentralUser = RingCentralUser::find($ringCentralUser->id);
            }

            $authData = null;

            // Prefer tokens from phone-number-based token file (shared account)
            $filePhoneNumber = $ringCentralUser->phone_number;
            if (!empty($filePhoneNumber)) {
                $tokensFile = $this->getTokensFilePathForUser($filePhoneNumber);
                if (file_exists($tokensFile) && filesize($tokensFile) > 0) {
                    try {
                        $fileTokens = json_decode(file_get_contents($tokensFile), true);
                        if (is_array($fileTokens) && !empty($fileTokens['access_token'])) {
                            $authData = [
                                'token_type' => $fileTokens['token_type'] ?? 'Bearer',
                                'access_token' => $fileTokens['access_token'],
                                'refresh_token' => $fileTokens['refresh_token'] ?? null,
                                'expires_in' => isset($fileTokens['expires_in'])
                                    ? intval($fileTokens['expires_in'])
                                    : max(1, $ringCentralUser->token_expires_at->diffInSeconds(now())),
                                'refresh_token_expires_in' => $fileTokens['refresh_token_expires_in'] ?? null,
                                'refresh_token_expire_time' => $fileTokens['refresh_token_expire_time'] ?? null,
                            ];

                            // Keep session aligned with shared token file
                            try {
                                Session::put('ringcentral_auth_data_' . $ringCentralUser->user_id, $fileTokens);
                                if (!empty($fileTokens['refresh_token'])) {
                                    // Legacy diagnostic comparison only. Disabled so refresh-token state has one source of truth.
                                    // Session::put('ringcentral_login_refresh_token_' . $ringCentralUser->user_id, $fileTokens['refresh_token']);
                                }
                            } catch (Exception $_e) {
                            }
                        }
                    } catch (Exception $_e) {
                    }
                }
            }

            // Fallback to session
            if (!$authData) {
                $authData = Session::get('ringcentral_auth_data_' . $ringCentralUser->user_id);
            }

            // Fallback to DB
            if (!$authData) {
                $accessToken = decrypt($ringCentralUser->access_token);
                $refreshToken = decrypt($ringCentralUser->refresh_token);
                $expiresIn = max(1, $ringCentralUser->token_expires_at->diffInSeconds(now()));
                $authData = [
                    'token_type' => 'Bearer',
                    'access_token' => $accessToken,
                    'refresh_token' => $refreshToken,
                    'expires_in' => $expiresIn,
                ];
            }

            if (is_array($authData)) {
                // Disabled diagnostic-only source tracking. Keep here for future refresh metadata debugging.
                // $refreshExpirySource = !empty($authData['refresh_token_expire_time'])
                //     ? 'refresh_token_expire_time'
                //     : (!empty($authData['refresh_token_expires_in']) ? 'refresh_token_expires_in' : 'unknown');
                $resolvedRefreshExpiry = $this->resolveRefreshTokenExpiresAt($authData);
                if ($resolvedRefreshExpiry &&
                    (!$ringCentralUser->refresh_token_expires_at || $resolvedRefreshExpiry->gt($ringCentralUser->refresh_token_expires_at))) {
                    $ringCentralUser->update(['refresh_token_expires_at' => $resolvedRefreshExpiry]);
                    $ringCentralUser->refresh();
                }
            }

            $platform->auth()->setData($authData);

            if (!$skipWebhookEnsure && !empty($ringCentralUser->user_id)) {
                try {
                    $this->ensureWebhookSubscription((int) $ringCentralUser->user_id, false, $platform);
                } catch (\Throwable $_e) {
                    // Do not block API calls if webhook refresh fails.
                }
            }

            return $platform;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function upsertExtensionBlockedCallerRule(int $userId, string $normalizedE164, ?string $preferredRuleId = null): array
    {
        try {
            $ringCentralUser = RingCentralUser::where('user_id', $userId)->first();
            if (!$ringCentralUser) {
                return [
                    'success' => false,
                    'message' => 'R-Dialer user not found.',
                ];
            }

            $platform = $this->getPlatformWithAuth($ringCentralUser);
            if (!$platform) {
                return [
                    'success' => false,
                    'message' => 'Failed to authenticate with RingCentral. Please reconnect.',
                ];
            }
            return $this->upsertCallerBlockingPhoneNumber($platform, $normalizedE164, $preferredRuleId);
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $this->extractRingCentralErrorMessage($e),
            ];
        }
    }

    public function removeExtensionBlockedCallerRule(int $userId, string $normalizedE164, ?string $knownRuleId = null): array
    {
        try {
            $ringCentralUser = RingCentralUser::where('user_id', $userId)->first();
            if (!$ringCentralUser) {
                return [
                    'success' => false,
                    'message' => 'R-Dialer user not found.',
                ];
            }

            $platform = $this->getPlatformWithAuth($ringCentralUser);
            if (!$platform) {
                return [
                    'success' => false,
                    'message' => 'Failed to authenticate with RingCentral. Please reconnect.',
                ];
            }
            return $this->removeCallerBlockingPhoneNumber($platform, $normalizedE164, $knownRuleId);
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $this->extractRingCentralErrorMessage($e),
            ];
        }
    }

    public function getBlockedCallerRulesDiagnostics(int $userId, ?string $normalizedE164 = null): array
    {
        try {
            $ringCentralUser = RingCentralUser::where('user_id', $userId)->first();
            if (!$ringCentralUser) {
                return [
                    'success' => false,
                    'message' => 'R-Dialer user not found.',
                ];
            }

            $platform = $this->getPlatformWithAuth($ringCentralUser);
            if (!$platform) {
                return [
                    'success' => false,
                    'message' => 'Failed to authenticate with RingCentral. Please reconnect.',
                ];
            }

            try {
                $settings = $this->getCallerBlockingSettings($platform);
                $records = $this->listCallerBlockingRecords($platform);
                $normalizedFilter = $normalizedE164 ? preg_replace('/\D+/', '', $normalizedE164) : null;
                $rules = [];
                foreach ($records as $record) {
                    $phone = (string) data_get($record, 'phoneNumber', '');
                    $phoneDigits = preg_replace('/\D+/', '', $phone);
                    if ($normalizedFilter && ltrim($phoneDigits, '1') !== ltrim($normalizedFilter, '1')) {
                        continue;
                    }
                    $rules[] = [
                        'id' => (string) data_get($record, 'id', ''),
                        'display_name' => 'Caller Blocking',
                        'enabled' => strtolower((string) data_get($record, 'status', '')) === 'blocked',
                        'numbers' => $phone ? [$phone] : [],
                        'kind' => 'caller_blocking_v1',
                    ];
                }

                return [
                    'success' => true,
                    'backend' => 'caller_blocking_v1',
                    'uses_new_call_handling' => $this->usesNewCallHandlingBackend($platform),
                    'endpoint' => '/restapi/v1.0/account/~/extension/~/caller-blocking/phone-numbers',
                    'settings' => $settings,
                    'rules' => $rules,
                ];
            } catch (\Throwable $callerBlockingError) {
                // fall through to legacy diagnostics fallback below
            }

            $usesNewCallHandling = $this->usesNewCallHandlingBackend($platform);
            $endpoint = $usesNewCallHandling
                ? '/restapi/v2/accounts/~/extensions/~/comm-handling/voice/interaction-rules'
                : '/restapi/v1.0/account/~/extension/~/answering-rule';

            $response = $platform->get($endpoint)->json();
            $records = data_get($response, 'records', []);
            if (!is_array($records)) {
                $records = [];
            }

            $normalizedFilter = $normalizedE164 ? preg_replace('/\D+/', '', $normalizedE164) : null;
            $rules = [];
            foreach ($records as $record) {
                $numbers = $this->extractRuleCallerNumbers($record);
                if ($normalizedFilter) {
                    $matched = false;
                    foreach ($numbers as $number) {
                        if (ltrim(preg_replace('/\D+/', '', $number), '1') === ltrim($normalizedFilter, '1')) {
                            $matched = true;
                            break;
                        }
                    }
                    if (!$matched) {
                        continue;
                    }
                }

                $rules[] = [
                    'id' => (string) data_get($record, 'id', ''),
                    'display_name' => (string) (data_get($record, 'displayName', data_get($record, 'name', '')) ?: ''),
                    'enabled' => (bool) data_get($record, 'enabled', false),
                    'numbers' => $numbers,
                    'kind' => $usesNewCallHandling ? 'interaction_rule_v2' : 'answering_rule_v1',
                ];
            }

            return [
                'success' => true,
                'backend' => $usesNewCallHandling ? 'new_call_handling_v2' : 'legacy_answering_rules_v1',
                'uses_new_call_handling' => $usesNewCallHandling,
                'endpoint' => $endpoint,
                'rules' => $rules,
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $this->extractRingCentralErrorMessage($e),
            ];
        }
    }

    public function getCallerBlockingSnapshot(int $userId): array
    {
        try {
            $ringCentralUser = RingCentralUser::where('user_id', $userId)->first();
            if (!$ringCentralUser) {
                return [
                    'success' => false,
                    'message' => 'R-Dialer user not found.',
                ];
            }

            $platform = $this->getPlatformWithAuth($ringCentralUser);
            if (!$platform) {
                return [
                    'success' => false,
                    'message' => 'Failed to authenticate with RingCentral. Please reconnect.',
                ];
            }

            $settings = $this->getCallerBlockingSettings($platform);
            $records = $this->listCallerBlockingRecords($platform);

            $normalizedRecords = [];
            foreach ($records as $record) {
                $id = (string) data_get($record, 'id', '');
                $phone = (string) data_get($record, 'phoneNumber', '');
                $status = (string) data_get($record, 'status', '');
                if ($phone === '') {
                    continue;
                }
                $normalizedRecords[] = [
                    'id' => $id !== '' ? $id : null,
                    'phoneNumber' => $phone,
                    'status' => $status,
                ];
            }

            return [
                'success' => true,
                'settings' => $settings,
                'records' => $normalizedRecords,
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $this->extractRingCentralErrorMessage($e),
            ];
        }
    }

    public function isNumberBlockedInRingCentral(int $userId, string $normalizedE164, bool $useCache = true): array
    {
        try {
            $normalizedDigits = ltrim(preg_replace('/\D+/', '', $normalizedE164), '1');
            if ($normalizedDigits === '') {
                return [
                    'success' => false,
                    'blocked' => false,
                    'message' => 'Invalid phone number.',
                ];
            }

            $cacheKey = 'ringcentral:caller-blocking:snapshot:' . $userId;
            if ($useCache) {
                $snapshot = Cache::get($cacheKey);
                if (!is_array($snapshot) || empty($snapshot['success'])) {
                    $snapshot = $this->getCallerBlockingSnapshot($userId);
                    if (!empty($snapshot['success'])) {
                        Cache::put($cacheKey, $snapshot, now()->addSeconds(15));
                    }
                }
            } else {
                $snapshot = $this->getCallerBlockingSnapshot($userId);
            }

            if (empty($snapshot['success'])) {
                return [
                    'success' => false,
                    'blocked' => false,
                    'message' => (string) ($snapshot['message'] ?? 'Unable to read R-Dialer caller-blocking settings.'),
                ];
            }

            $records = data_get($snapshot, 'records', []);
            if (!is_array($records)) {
                $records = [];
            }

            foreach ($records as $record) {
                $status = strtolower((string) data_get($record, 'status', ''));
                if ($status !== 'blocked') {
                    continue;
                }
                $phone = (string) data_get($record, 'phoneNumber', '');
                $phoneDigits = ltrim(preg_replace('/\D+/', '', $phone), '1');
                if ($phoneDigits !== '' && $phoneDigits === $normalizedDigits) {
                    return [
                        'success' => true,
                        'blocked' => true,
                        'rule_id' => (string) data_get($record, 'id', ''),
                    ];
                }
            }

            return [
                'success' => true,
                'blocked' => false,
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'blocked' => false,
                'message' => $this->extractRingCentralErrorMessage($e),
            ];
        }
    }

    public function resolveRemoteBlockedCallerRule(int $userId, string $normalizedE164): array
    {
        try {
            $ringCentralUser = RingCentralUser::where('user_id', $userId)->first();
            if (!$ringCentralUser) {
                return [
                    'success' => false,
                    'message' => 'R-Dialer user not found.',
                ];
            }

            $platform = $this->getPlatformWithAuth($ringCentralUser);
            if (!$platform) {
                return [
                    'success' => false,
                    'message' => 'Failed to authenticate with RingCentral. Please reconnect.',
                ];
            }

            $callerBlockingId = $this->findCallerBlockingRecordId($platform, $normalizedE164);
            if ($callerBlockingId) {
                return [
                    'success' => true,
                    'rule_id' => (string) $callerBlockingId,
                    'backend' => 'caller_blocking_v1',
                ];
            }

            $usesNewCallHandling = $this->usesNewCallHandlingBackend($platform);
            $ruleId = $this->findBlockedCallerRuleId($platform, $normalizedE164, $usesNewCallHandling);
            if (!$ruleId) {
                $ruleId = $this->findBlockedCallerRuleId($platform, $normalizedE164, !$usesNewCallHandling);
            }

            return [
                'success' => true,
                'rule_id' => $ruleId ?: null,
                'backend' => $usesNewCallHandling ? 'new_call_handling_v2' : 'legacy_answering_rules_v1',
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $this->extractRingCentralErrorMessage($e),
            ];
        }
    }

    private function upsertCallerBlockingPhoneNumber($platform, string $normalizedE164, ?string $preferredId = null): array
    {
        $this->ensureCallerBlockingEnabled($platform);

        $existingId = $preferredId ?: $this->findCallerBlockingRecordId($platform, $normalizedE164);
        if ($existingId) {
            $this->ensureBlockedAllowedNumberIsBlocked($platform, (string) $existingId, $normalizedE164);
            Log::info('RC_CALLER_BLOCK_UPSERT existing remote record reused.', [
                'number' => $normalizedE164,
                'rule_id' => (string) $existingId,
            ]);
            return [
                'success' => true,
                'rule_id' => (string) $existingId,
                'action' => 'existing',
            ];
        }

        $payload = [
            'phoneNumber' => $normalizedE164,
            'status' => 'Blocked',
        ];
        Log::info('RC_CALLER_BLOCK_UPSERT posting to caller-blocking endpoint.', [
            'endpoint' => '/restapi/v1.0/account/~/extension/~/caller-blocking/phone-numbers',
            'payload' => $payload,
        ]);
        try {
            $response = $platform->post('/restapi/v1.0/account/~/extension/~/caller-blocking/phone-numbers', $payload)->json();
        } catch (\Throwable $e) {
            Log::warning('RC_CALLER_BLOCK_UPSERT API request failed.', [
                'number' => $normalizedE164,
                'message' => $this->extractRingCentralErrorMessage($e),
            ]);
            throw $e;
        }
        Log::info('RC_CALLER_BLOCK_UPSERT API response received.', [
            'number' => $normalizedE164,
            'response' => $response,
        ]);
        $id = (string) data_get($response, 'id', '');
        if ($id === '') {
            $id = (string) ($this->findCallerBlockingRecordId($platform, $normalizedE164) ?: '');
        }
        if ($id === '') {
            throw new \RuntimeException('Caller blocking API did not return an id.');
        }
        $this->ensureBlockedAllowedNumberIsBlocked($platform, $id, $normalizedE164);

        return [
            'success' => true,
            'rule_id' => $id,
            'action' => 'created',
        ];
    }

    private function removeCallerBlockingPhoneNumber($platform, string $normalizedE164, ?string $knownId = null): array
    {
        $id = $knownId ?: $this->findCallerBlockingRecordId($platform, $normalizedE164);
        if (!$id) {
            Log::info('RC_CALLER_BLOCK_REMOVE no remote record found to remove.', [
                'number' => $normalizedE164,
            ]);
            return [
                'success' => true,
                'removed' => false,
                'message' => 'No R-Dialer caller-blocking record found for this number.',
            ];
        }

        try {
            Log::info('RC_CALLER_BLOCK_REMOVE deleting remote caller-blocking record.', [
                'number' => $normalizedE164,
                'rule_id' => (string) $id,
                'endpoint' => '/restapi/v1.0/account/~/extension/~/caller-blocking/phone-numbers/' . $id,
            ]);
            $platform->delete('/restapi/v1.0/account/~/extension/~/caller-blocking/phone-numbers/' . $id);
        } catch (\Throwable $e) {
            $msg = strtolower($this->extractRingCentralErrorMessage($e));
            if (str_contains($msg, 'not found') || str_contains($msg, '404')) {
                Log::info('RC_CALLER_BLOCK_REMOVE record already absent on RingCentral.', [
                    'number' => $normalizedE164,
                    'rule_id' => (string) $id,
                ]);
                return [
                    'success' => true,
                    'removed' => false,
                    'rule_id' => (string) $id,
                    'message' => 'Caller-blocking record was already removed.',
                ];
            }
            Log::warning('RC_CALLER_BLOCK_REMOVE API request failed.', [
                'number' => $normalizedE164,
                'rule_id' => (string) $id,
                'message' => $this->extractRingCentralErrorMessage($e),
            ]);
            throw $e;
        }

        Log::info('RC_CALLER_BLOCK_REMOVE remote caller-blocking record removed.', [
            'number' => $normalizedE164,
            'rule_id' => (string) $id,
        ]);
        return [
            'success' => true,
            'removed' => true,
            'rule_id' => (string) $id,
        ];
    }

    private function listCallerBlockingRecords($platform): array
    {
        try {
            $response = $platform->get('/restapi/v1.0/account/~/extension/~/caller-blocking/phone-numbers')->json();
        } catch (\Throwable $e) {
            Log::warning('RC_CALLER_BLOCK_LIST API request failed.', [
                'message' => $this->extractRingCentralErrorMessage($e),
            ]);
            throw $e;
        }
        $records = data_get($response, 'records');
        if (is_array($records)) {
            Log::info('RC_CALLER_BLOCK_LIST records fetched.', [
                'count' => count($records),
            ]);
            return $records;
        }
        if (is_array($response) && isset($response['id']) && isset($response['phoneNumber'])) {
            Log::info('RC_CALLER_BLOCK_LIST single record payload fetched.', [
                'id' => (string) data_get($response, 'id', ''),
            ]);
            return [$response];
        }
        Log::info('RC_CALLER_BLOCK_LIST empty payload.', [
            'response' => $response,
        ]);
        return [];
    }

    private function getCallerBlockingSettings($platform): array
    {
        $response = $platform->get('/restapi/v1.0/account/~/extension/~/caller-blocking')->json();
        return is_array($response) ? $response : [];
    }

    private function updateCallerBlockingSettings($platform, array $payload): array
    {
        $response = $platform->put('/restapi/v1.0/account/~/extension/~/caller-blocking', $payload)->json();
        return is_array($response) ? $response : [];
    }

    private function isCallerBlockingEnabled(array $settings): bool
    {
        if (array_key_exists('status', $settings)) {
            return strtolower((string) data_get($settings, 'status', '')) === 'enabled';
        }
        if (array_key_exists('enabled', $settings)) {
            return (bool) data_get($settings, 'enabled', false);
        }
        // Unknown settings shape; treat as enabled to avoid false negatives.
        return true;
    }

    private function buildEnableCallerBlockingPayload(array $settings): array
    {
        $payload = [];
        if (array_key_exists('status', $settings)) {
            $payload['status'] = 'Enabled';
        }
        if (array_key_exists('enabled', $settings)) {
            $payload['enabled'] = true;
        }

        // Fallback: most accounts support "status" field.
        if (empty($payload)) {
            $payload['status'] = 'Enabled';
        }

        return $payload;
    }

    private function ensureCallerBlockingEnabled($platform): void
    {
        try {
            $settings = $this->getCallerBlockingSettings($platform);
            Log::info('RC_CALLER_BLOCK_SETTINGS read settings.', [
                'settings' => $settings,
            ]);

            if ($this->isCallerBlockingEnabled($settings)) {
                return;
            }

            $payload = $this->buildEnableCallerBlockingPayload($settings);
            Log::info('RC_CALLER_BLOCK_SETTINGS enabling caller blocking.', [
                'payload' => $payload,
            ]);
            $updated = $this->updateCallerBlockingSettings($platform, $payload);
            Log::info('RC_CALLER_BLOCK_SETTINGS update response.', [
                'response' => $updated,
            ]);
        } catch (\Throwable $e) {
            Log::warning('RC_CALLER_BLOCK_SETTINGS ensure failed.', [
                'message' => $this->extractRingCentralErrorMessage($e),
            ]);
        }
    }

    private function readBlockedAllowedNumberById($platform, string $id): ?array
    {
        try {
            $response = $platform->get('/restapi/v1.0/account/~/extension/~/caller-blocking/phone-numbers/' . $id)->json();
            if (is_array($response)) {
                return $response;
            }
            return null;
        } catch (\Throwable $e) {
            $msg = strtolower($this->extractRingCentralErrorMessage($e));
            if (str_contains($msg, 'not found') || str_contains($msg, '404')) {
                return null;
            }
            Log::warning('RC_CALLER_BLOCK_READ_BY_ID failed.', [
                'rule_id' => $id,
                'message' => $this->extractRingCentralErrorMessage($e),
            ]);
            return null;
        }
    }

    private function ensureBlockedAllowedNumberIsBlocked($platform, string $id, string $normalizedE164): void
    {
        try {
            $record = $this->readBlockedAllowedNumberById($platform, $id);
            if (!$record) {
                return;
            }

            $status = strtolower((string) data_get($record, 'status', ''));
            $phone = (string) data_get($record, 'phoneNumber', '');
            $phoneDigits = ltrim(preg_replace('/\D+/', '', $phone), '1');
            $targetDigits = ltrim(preg_replace('/\D+/', '', $normalizedE164), '1');
            $needsUpdate = $status !== 'blocked' || ($phoneDigits !== '' && $phoneDigits !== $targetDigits);

            if (!$needsUpdate) {
                return;
            }

            $payload = [
                'phoneNumber' => $normalizedE164,
                'status' => 'Blocked',
            ];
            Log::info('RC_CALLER_BLOCK_UPDATE enforcing blocked state.', [
                'rule_id' => $id,
                'payload' => $payload,
                'current_status' => $status,
                'current_phone' => $phone,
            ]);
            $platform->put('/restapi/v1.0/account/~/extension/~/caller-blocking/phone-numbers/' . $id, $payload);
        } catch (\Throwable $e) {
            Log::warning('RC_CALLER_BLOCK_UPDATE failed to enforce blocked state.', [
                'rule_id' => $id,
                'number' => $normalizedE164,
                'message' => $this->extractRingCentralErrorMessage($e),
            ]);
        }
    }

    private function findCallerBlockingRecordId($platform, string $normalizedE164): ?string
    {
        $target = ltrim(preg_replace('/\D+/', '', $normalizedE164), '1');
        if ($target === '') {
            return null;
        }
        $records = $this->listCallerBlockingRecords($platform);
        foreach ($records as $record) {
            $phone = (string) data_get($record, 'phoneNumber', '');
            $phoneDigits = ltrim(preg_replace('/\D+/', '', $phone), '1');
            if ($phoneDigits === $target) {
                $id = (string) data_get($record, 'id', '');
                if ($id !== '') {
                    return $id;
                }
            }
        }
        return null;
    }

    private function findBlockedCallerRuleId($platform, string $normalizedE164, bool $usesNewCallHandling = false): ?string
    {
        try {
            $endpoint = $usesNewCallHandling
                ? '/restapi/v2/accounts/~/extensions/~/comm-handling/voice/interaction-rules'
                : '/restapi/v1.0/account/~/extension/~/answering-rule';
            $response = $platform->get($endpoint)->json();
            $records = data_get($response, 'records', []);
            if (!is_array($records)) {
                return null;
            }

            foreach ($records as $record) {
                if (!$this->answeringRuleMatchesCaller($record, $normalizedE164)) {
                    continue;
                }
                $id = (string) data_get($record, 'id', '');
                if ($id !== '') {
                    return $id;
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to discover R-Dialer answering rules while matching blocked caller.', [
                'message' => $e->getMessage(),
            ]);
        }

        return null;
    }

    private function answeringRuleMatchesCaller($record, string $normalizedE164): bool
    {
        $target = preg_replace('/\D+/', '', $normalizedE164);

        $conditions = data_get($record, 'conditions', []);
        if (is_array($conditions)) {
            foreach ($conditions as $condition) {
                if (strtolower((string) data_get($condition, 'type', '')) !== 'interaction') {
                    continue;
                }
                $from = data_get($condition, 'from', []);
                if (!is_array($from)) {
                    continue;
                }
                foreach ($from as $party) {
                    $callerId = preg_replace('/\D+/', '', (string) data_get($party, 'phoneNumber', ''));
                    if ($callerId !== '' && $target !== '' && ltrim($callerId, '1') === ltrim($target, '1')) {
                        return true;
                    }
                }
            }
        }

        $callers = data_get($record, 'callers', []);
        if (!is_array($callers)) {
            return false;
        }

        foreach ($callers as $caller) {
            $callerId = preg_replace('/\D+/', '', (string) data_get($caller, 'callerId', ''));
            if ($callerId !== '' && $target !== '' && ltrim($callerId, '1') === ltrim($target, '1')) {
                return true;
            }
        }

        // Fallback for inconsistent payload structures: deep scan for matching phone-like values.
        return $this->recordContainsPhoneDeep($record, $normalizedE164);
    }

    private function extractRuleCallerNumbers($record): array
    {
        $numbers = [];

        $conditions = data_get($record, 'conditions', []);
        if (is_array($conditions)) {
            foreach ($conditions as $condition) {
                if (strtolower((string) data_get($condition, 'type', '')) !== 'interaction') {
                    continue;
                }
                $from = data_get($condition, 'from', []);
                if (!is_array($from)) {
                    continue;
                }
                foreach ($from as $party) {
                    $phone = (string) data_get($party, 'phoneNumber', '');
                    if ($phone !== '') {
                        $numbers[] = $phone;
                    }
                }
            }
        }

        $callers = data_get($record, 'callers', []);
        if (is_array($callers)) {
            foreach ($callers as $caller) {
                $phone = (string) data_get($caller, 'callerId', '');
                if ($phone !== '') {
                    $numbers[] = $phone;
                }
            }
        }

        return array_values(array_unique($numbers));
    }

    private function recordContainsPhoneDeep($record, string $normalizedE164): bool
    {
        $target = ltrim(preg_replace('/\D+/', '', $normalizedE164), '1');
        if ($target === '') {
            return false;
        }

        $queue = [$record];
        while (!empty($queue)) {
            $node = array_shift($queue);
            if (is_array($node)) {
                foreach ($node as $value) {
                    $queue[] = $value;
                }
                continue;
            }
            if (is_object($node)) {
                foreach ((array) $node as $value) {
                    $queue[] = $value;
                }
                continue;
            }
            if (!is_scalar($node)) {
                continue;
            }

            $digits = preg_replace('/\D+/', '', (string) $node);
            if ($digits !== '' && ltrim($digits, '1') === $target) {
                return true;
            }
        }

        return false;
    }

    private function usesNewCallHandlingBackend($platform): bool
    {
        try {
            $response = $platform
                ->get('/restapi/v1.0/account/~/extension/~/features?featureId=NewCallHandlingAndForwarding')
                ->json();
            $records = data_get($response, 'records', []);
            if (!is_array($records) || empty($records)) {
                return false;
            }
            $params = data_get($records[0], 'params', []);
            if (!is_array($params)) {
                return false;
            }
            foreach ($params as $param) {
                if ((string) data_get($param, 'name', '') !== 'isNewBackendAvailable') {
                    continue;
                }
                return strtolower((string) data_get($param, 'value', 'false')) === 'true';
            }
        } catch (\Throwable $e) {
            Log::warning('Unable to detect NewCallHandlingAndForwarding feature state.', [
                'message' => $e->getMessage(),
            ]);
        }

        return false;
    }

    private function buildV2BlockedCallerRulePayload(string $normalizedE164, string $ruleName): array
    {
        return [
            'displayName' => $ruleName,
            'enabled' => true,
            'conditions' => [
                [
                    'type' => 'Interaction',
                    'from' => [
                        [
                            'phoneNumber' => $normalizedE164,
                            'name' => 'Blocked Caller',
                        ],
                    ],
                    'to' => [],
                ],
            ],
            'dispatching' => [
                'type' => 'Terminate',
                'actions' => [
                    [
                        'type' => 'TerminatingAction',
                        'targets' => [
                            [
                                'type' => 'VoiceMailTerminatingTarget',
                                'name' => 'Voicemail',
                            ],
                        ],
                        'terminatingTargetType' => 'VoiceMailTerminatingTarget',
                    ],
                ],
            ],
        ];
    }

    private function extractRingCentralErrorMessage(\Throwable $e): string
    {
        if ($e instanceof \GuzzleHttp\Exception\ClientException && $e->hasResponse()) {
            try {
                $raw = (string) $e->getResponse()->getBody();
                $json = json_decode($raw, true);
                if (is_array($json)) {
                    $message = (string) (
                        data_get($json, 'message')
                        ?: data_get($json, 'error_description')
                        ?: data_get($json, 'error')
                        ?: data_get($json, 'errors.0.message')
                        ?: ''
                    );
                    if ($message !== '') {
                        return $message;
                    }
                }
                if (trim($raw) !== '') {
                    return trim($raw);
                }
            } catch (\Throwable $_) {
            }
        }

        $message = trim((string) $e->getMessage());
        if ($message !== '') {
            return $message;
        }
        return 'Unknown R-Dialer API error.';
    }

    /**
     * Ensure webhook subscription exists and is periodically renewed.
     *
     * @param int $userId
     * @param bool $force
     * @param mixed $platform
     * @return array
     */
    public function ensureWebhookSubscription($userId, $force = false, $platform = null)
    {
        $webhookUrl = trim((string) config('services.ringcentral.webhook_url'));
        if ($webhookUrl === '') {
            Log::warning('R-Dialer webhook ensure skipped: webhook URL not configured.', [
                'user_id' => (int) $userId,
            ]);
            return [
                'success' => true,
                'skipped' => true,
                'message' => 'Webhook URL is not configured.',
            ];
        }

        $cacheKey = 'ringcentral:webhook:ensure:' . (int) $userId;
        if (!$force && Cache::has($cacheKey)) {
            return [
                'success' => true,
                'skipped' => true,
                'message' => 'Webhook subscription was recently verified.',
            ];
        }

        $ringCentralUser = RingCentralUser::where('user_id', $userId)->first();
        if (!$ringCentralUser) {
            return [
                'success' => false,
                'message' => 'R-Dialer user not found.',
            ];
        }

        $platformForWebhook = $platform;
        if (!$platformForWebhook) {
            $platformForWebhook = $this->getPlatformWithAuth($ringCentralUser, true);
        }

        if (!$platformForWebhook) {
            return [
                'success' => false,
                'message' => 'Failed to authenticate R-Dialer platform for webhook subscription.',
            ];
        }

        $this->platform = $platformForWebhook;
        $result = $this->registerWebhook($webhookUrl);

        if (!empty($result['success'])) {
            Cache::put($cacheKey, now()->toDateTimeString(), now()->addHours(6));
            Log::info('R-Dialer webhook subscription ensured.', [
                'user_id' => (int) $userId,
                'action' => $result['action'] ?? null,
                'subscription_id' => $result['subscription_id'] ?? null,
                'webhook_url' => $webhookUrl,
            ]);
        } else {
            Log::warning('R-Dialer webhook ensure failed.', [
                'user_id' => (int) $userId,
                'message' => $result['message'] ?? 'unknown error',
                'webhook_url' => $webhookUrl,
            ]);
        }

        return $result;
    }

    /**
     * Register webhook for real-time events
     *
     * @param string $webhookUrl
     * @return array
     */
    public function registerWebhook($webhookUrl)
    {
        try {
            if (!$this->platform) {
                return [
                    'success' => false,
                    'message' => 'R-Dialer platform is not initialized.',
                ];
            }

            if (!filter_var($webhookUrl, FILTER_VALIDATE_URL)) {
                return [
                    'success' => false,
                    'message' => 'Webhook URL is invalid.',
                ];
            }

            $normalizedWebhookUrl = rtrim($webhookUrl, '/');
            $validationToken = trim((string) config('services.ringcentral.webhook_secret'));

            $eventFilters = [
                '/restapi/v1.0/account/~/extension/~/message-store/instant?type=SMS',
                '/restapi/v1.0/account/~/extension/~/message-store',
                '/restapi/v1.0/account/~/extension/~/voicemail',
            ];

            // Step 1: Check existing subscriptions
            $subscriptionPayload = $this->platform->get('/subscription')->json();
            $existingSubs = data_get($subscriptionPayload, 'records', []);

            foreach ($existingSubs as $sub) {
                $address = data_get($sub, 'deliveryMode.address');
                $address = is_string($address) ? rtrim($address, '/') : null;
                $subId = data_get($sub, 'id');

                if ($address && $address === $normalizedWebhookUrl && $subId) {
                    // Update expiration instead of creating a new one
                    $response = $this->platform->put("/subscription/{$subId}", [
                        'expiresIn' => 604800, // Extend 7 days
                        'eventFilters' => $eventFilters,
                    ]);
                    $responseData = $response->json();

                    return [
                        'success' => true,
                        'subscription_id' => $subId,
                        'action' => 'updated',
                        'data' => $responseData,
                    ];
                }
            }

            $deliveryMode = [
                'transportType' => 'WebHook',
                'address' => $normalizedWebhookUrl,
            ];
            if ($validationToken !== '') {
                $deliveryMode['validationToken'] = $validationToken;
            }

            // Step 2: Create new subscription if not found
            $response = $this->platform->post('/subscription', [
                'eventFilters' => $eventFilters,
                'deliveryMode' => $deliveryMode,
                'expiresIn' => 604800,
            ]);
            $responseData = $response->json();


            return [
                'success' => true,
                'subscription_id' => data_get($responseData, 'id'),
                'action' => 'created',
                'data' => $responseData,
            ];
        } catch (Exception $e) {
            Log::warning('R-Dialer webhook register exception.', [
                'webhook_url' => $webhookUrl,
                'error' => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'message' => 'Failed to register webhook: ' . $e->getMessage(),
            ];
        }
    }



    // Add other methods such as making calls, sending SMS, retrieving history, etc.

    /**
     * Get extension information
     *
     * @return array
     */
    public function getExtensionInfo()
    {
        try {
            $response = $this->platform->get('/restapi/v1.0/account/~/extension/~');
            return $response->json();
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Get or create platform instance with proper auth
     *
     * @param RingCentralUser $ringCentralUser
     * @return object|null Platform or null if auth fails
     */


    /**
     * Get SIP provisioning info for WebPhone (Device SIP Registration)
     *
     * @param int $userId
     * @return array|null
     */
    public function getSipInfo($userId, $forceRefresh = false)
    {
        try {
            $ringCentralUser = RingCentralUser::where('user_id', $userId)->firstOrFail();
            $phoneKey = preg_replace('/\D+/', '', (string) $ringCentralUser->phone_number);
            $cacheKey = 'ringcentral:webphone:sipinfo:' . ($phoneKey ?: ('user_' . (int) $userId));

            if (!$forceRefresh) {
                $cached = Cache::get($cacheKey);
                if (is_array($cached) && !empty($cached['username']) && !empty($cached['domain'])) {
                    return $cached;
                }
            }

            // WebPhone token flow should not pay webhook-subscription latency.
            $platform = $this->getPlatformWithAuth($ringCentralUser, true);
            if (!$platform) {
                $this->logSipInfoFailure($ringCentralUser, $forceRefresh, null, [
                    'stage' => 'platform_auth_failed',
                ]);
                return null;
            }

            // Request SIP provisioning with WSS transport
            $payload = [
                'sipInfo' => [
                    [
                        'transport' => 'WSS'
                    ]
                ]
            ];

            $response = $platform->post('/restapi/v1.0/client-info/sip-provision', $payload);
            $json = $response->json();

            if (isset($json->sipInfo) && is_array($json->sipInfo) && count($json->sipInfo) > 0) {
                $sipInfo = (array) $json->sipInfo[0];
                Cache::put($cacheKey, $sipInfo, now()->addSeconds(90));
                return $sipInfo;
            }

            $this->logSipInfoFailure($ringCentralUser, $forceRefresh, null, [
                'stage' => 'sip_provision_empty',
                'response_keys' => is_object($json) ? array_keys((array) $json) : [],
            ]);

            return null;
        } catch (\Exception $e) {
            $this->logSipInfoFailure(
                isset($ringCentralUser) ? $ringCentralUser : null,
                $forceRefresh,
                $e,
                ['stage' => 'sip_provision_exception', 'requested_user_id' => (int) $userId]
            );
            return null;
        }
    }

    private function logSipInfoFailure($ringCentralUser, bool $forceRefresh, ?Exception $e = null, array $extra = []): void
    {
        $context = array_merge([
            'user_id' => (int) ($ringCentralUser->user_id ?? 0),
            'ringcentral_user_id' => (int) ($ringCentralUser->id ?? 0),
            'phone_number_last4' => $this->lastFourDigits($ringCentralUser->phone_number ?? null),
            'force_refresh' => $forceRefresh,
        ], $extra);

        if ($e) {
            $context['exception_class'] = get_class($e);
            $context['exception_code'] = $e->getCode();
            $context['message'] = $this->sanitizeRingCentralError($e->getMessage());

            if (method_exists($e, 'getResponse') && $e->getResponse()) {
                $response = $e->getResponse();
                $context['http_status'] = method_exists($response, 'getStatusCode')
                    ? $response->getStatusCode()
                    : null;
                $context['response_body'] = $this->sanitizeRingCentralError(
                    substr((string) $response->getBody(), 0, 1000)
                );
            }
        }

        try {
            Log::channel('ringcentral_token')->warning('R-Dialer SIP provisioning failed', $context);
        } catch (\Throwable $_e) {
            Log::warning('R-Dialer SIP provisioning failed', $context);
        }
    }
    /**
     * Send SMS message
     *
     * @param int $userId
     * @param string $toPhone
     * @param string $message
     * @return array
     */
    public function sendSMS($userId, $toPhone, $fromPhone, $message, array $attachments = [], array $forwardedAttachments = [], array $options = [])
    {
        try {
            $ringCentralUser = RingCentralUser::where('user_id', $userId)->firstOrFail();

            $platform = $this->getPlatformWithAuth($ringCentralUser);
            if (!$platform) {
                return [
                    'success' => false,
                    'message' => 'Failed to authenticate with RingCentral. Please reconnect.'
                ];
            }
            
            // If no number is stored, try to fetch available phone numbers
            if (!$fromPhone) {
                $phoneNumbers = $this->getAvailablePhoneNumbers($platform, $ringCentralUser);
                if (empty($phoneNumbers)) {
                    return [
                        'success' => false,
                        'message' => 'No phone numbers available for this account. Please reconnect.'
                    ];
                }
                $fromPhone = $phoneNumbers[0];
                $ringCentralUser->update(['phone_number' => $fromPhone]);
            }
            $toNumbers = is_array($toPhone)
                ? array_values(array_filter(array_map('strval', $toPhone), function ($value) {
                    return trim((string) $value) !== '';
                }))
                : [trim((string) $toPhone)];
            $toNumbers = array_values(array_unique($toNumbers));
            if (empty($toNumbers)) {
                return [
                    'success' => false,
                    'message' => 'At least one recipient is required.',
                ];
            }

            $payload = [
                "to" => array_map(function ($number) {
                    return ["phoneNumber" => $number];
                }, $toNumbers),
                "from" => ["phoneNumber" => $fromPhone],
            ];

            $createGroupText = !empty($options['create_group_text']);
            $groupName = isset($options['group_name']) ? trim((string) $options['group_name']) : '';
            if ($createGroupText && count($toNumbers) > 1 && $groupName !== '') {
                $payload['subject'] = $groupName;
            }

            $messageText = (string) $message;
            $messageTextTrim = trim($messageText);

            $multipartFiles = [];
            $attachmentMeta = [];
            $linkOnlyMeta = [];
            $linkLines = [];
            $maxTotalAttachmentBytes = 1500000;
            $totalAttachmentBytes = 0;
            $allowedPrefixes = ['image/', 'video/', 'audio/'];
            $allowedExact = ['application/pdf'];
            $resolveSafeStoragePath = function ($path) use ($ringCentralUser) {
                $raw = ltrim((string) $path, '/');
                if ($raw === '') return '';
                if (str_starts_with($raw, 'storage/')) {
                    $raw = substr($raw, strlen('storage/'));
                }
                $prefix = 'ringcentral_attachments/' . $ringCentralUser->user_id . '/';
                if (!str_starts_with($raw, $prefix)) {
                    return '';
                }
                return $raw;
            };
            $isAllowedMime = function ($mimeType) use ($allowedPrefixes, $allowedExact) {
                $mime = strtolower((string) $mimeType);
                if ($mime === '') return false;
                foreach ($allowedPrefixes as $prefix) {
                    if (str_starts_with($mime, $prefix)) return true;
                }
                return in_array($mime, $allowedExact, true);
            };
            $processAttachmentBinary = function ($contents, $originalName, $mimeType, $sizeHint = null) use (
                &$multipartFiles,
                &$attachmentMeta,
                &$linkOnlyMeta,
                &$linkLines,
                &$totalAttachmentBytes,
                $maxTotalAttachmentBytes,
                $ringCentralUser,
                $isAllowedMime
            ) {
                if (!is_string($contents) || $contents === '') {
                    return;
                }

                $safeFileName = trim((string) $originalName) !== '' ? (string) $originalName : ('attachment_' . Str::random(6));
                $safeMimeType = trim((string) $mimeType) !== '' ? (string) $mimeType : 'application/octet-stream';
                $contentSize = strlen($contents);
                $projectedTotalAttachmentBytes = $totalAttachmentBytes + $contentSize;
                if ($projectedTotalAttachmentBytes > $maxTotalAttachmentBytes) {
                    throw new \RuntimeException('Total attachment size exceeds 1.5MB limit.');
                }

                $isAllowed = $isAllowedMime($safeMimeType);
                $canAttachBySize = ($contentSize <= $maxTotalAttachmentBytes)
                    && ($projectedTotalAttachmentBytes <= $maxTotalAttachmentBytes);

                if ($isAllowed && $canAttachBySize) {
                    $multipartFiles[] = [
                        'contentType' => $safeMimeType,
                        'content' => $contents,
                        'fileName' => $safeFileName,
                        'size' => $contentSize,
                    ];
                    $totalAttachmentBytes = $projectedTotalAttachmentBytes;
                }

                $storedPath = null;
                try {
                    $storedSafeName = time() . '_' . Str::random(8) . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $safeFileName);
                    $storedPath = 'ringcentral_attachments/' . $ringCentralUser->user_id . '/' . $storedSafeName;
                    Storage::disk('public')->put($storedPath, $contents);
                } catch (\Exception $e) {
                }

                $publicUrl = $storedPath ? route('ringcentral.api.attachment', ['path' => $storedPath]) : null;
                $downloadUrl = $storedPath ? route('ringcentral.api.attachment', ['path' => $storedPath, 'download' => 1]) : null;

                $attachmentMeta[] = [
                    'fileName' => $safeFileName,
                    'contentType' => $safeMimeType,
                    'size' => $sizeHint ?: $contentSize,
                    'path' => $storedPath,
                    'local_path' => $publicUrl,
                    'url' => $publicUrl,
                    'download_url' => $downloadUrl,
                ];

                if (!$isAllowed || !$canAttachBySize) {
                    $linkOnlyMeta[] = [
                        'fileName' => $safeFileName,
                        'contentType' => $safeMimeType,
                        'size' => $sizeHint ?: $contentSize,
                        'path' => $storedPath,
                        'url' => $publicUrl,
                        'download_url' => $downloadUrl,
                    ];
                    if ($publicUrl) {
                        $linkLines[] = $safeFileName . ': ' . $publicUrl;
                    }
                }
            };

            if (!empty($attachments)) {
                foreach ($attachments as $idx => $file) {
                    
                    if (!$file) {
                        continue;
                    }

                    $originalName = method_exists($file, 'getClientOriginalName')
                        ? $file->getClientOriginalName()
                        : ('attachment_' . Str::random(6));
                    $mimeType = method_exists($file, 'getMimeType')
                        ? $file->getMimeType()
                        : 'application/octet-stream';
                    $size = method_exists($file, 'getSize') ? $file->getSize() : null;

                    // Try to read file contents - prefer stream reading for UploadedFile
                    $contents = null;
                    if (method_exists($file, 'get')) {
                        // Laravel UploadedFile has get() method
                        $contents = $file->get();
                    } elseif (method_exists($file, 'getRealPath')) {
                        // Fallback to getRealPath
                        $path = $file->getRealPath();
                        if ($path && file_exists($path)) {
                            $contents = @file_get_contents($path);
                        }
                    } elseif (is_string($file) && file_exists($file)) {
                        // String path provided
                        $contents = @file_get_contents($file);
                    }

                    if ($contents === null || $contents === false || empty($contents)) {
                        continue;
                    }
                    $processAttachmentBinary($contents, $originalName, $mimeType, $size);
                }
            }

            if (!empty($forwardedAttachments)) {
                foreach ($forwardedAttachments as $forwardedAttachment) {
                    if (!is_array($forwardedAttachment)) {
                        continue;
                    }

                    $path = $resolveSafeStoragePath($forwardedAttachment['path'] ?? $forwardedAttachment['stored_path'] ?? '');
                    $fileName = $forwardedAttachment['fileName'] ?? $forwardedAttachment['filename'] ?? ('attachment_' . Str::random(6));
                    $mimeType = $forwardedAttachment['contentType'] ?? 'application/octet-stream';
                    $size = isset($forwardedAttachment['size']) ? (int) $forwardedAttachment['size'] : null;
                    $contents = null;

                    if ($path !== '' && Storage::disk('public')->exists($path)) {
                        $contents = Storage::disk('public')->get($path);
                        if (!$mimeType || $mimeType === 'application/octet-stream') {
                            try {
                                $detectedType = Storage::disk('public')->mimeType($path);
                                if ($detectedType) {
                                    $mimeType = $detectedType;
                                }
                            } catch (\Exception $e) {
                            }
                        }
                    } else {
                        $uri = (string) ($forwardedAttachment['uri'] ?? $forwardedAttachment['contentUri'] ?? '');
                        if ($uri !== '') {
                            $downloadedMeta = $this->downloadMessageAttachment($ringCentralUser, [
                                'uri' => $uri,
                                'fileName' => $fileName,
                                'contentType' => $mimeType,
                                'size' => $size,
                            ]);
                            $downloadedPath = $resolveSafeStoragePath($downloadedMeta['path'] ?? '');
                            if ($downloadedPath !== '' && Storage::disk('public')->exists($downloadedPath)) {
                                $contents = Storage::disk('public')->get($downloadedPath);
                                $fileName = $downloadedMeta['fileName'] ?? $fileName;
                                $mimeType = $downloadedMeta['contentType'] ?? $mimeType;
                                $size = isset($downloadedMeta['size']) ? (int) $downloadedMeta['size'] : $size;
                            }
                        }
                    }

                    if ($contents === null || $contents === false || $contents === '') {
                        continue;
                    }

                    $processAttachmentBinary($contents, $fileName, $mimeType, $size);
                }
            }

            $messageTextFinal = $messageTextTrim === '' ? '' : $messageText;
            if (!empty($linkLines)) {
                if ($messageTextFinal !== '') {
                    $messageTextFinal .= "\n";
                }
                $messageTextFinal .= implode("\n", $linkLines);
            }
            if ($messageTextFinal === '' && !empty($multipartFiles)) {
                $messageTextFinal = ' ';
            }
            if ($messageTextFinal !== '') {
                $payload['text'] = $messageTextFinal;
            }

            if (empty($payload['text']) && empty($multipartFiles)) {
                return [
                    'success' => false,
                    'message' => 'No valid message text or attachments to send.',
                ];
            }

            // Log payload structure (without massive base64 content data)
            $payloadSummary = $payload;
            if (!empty($multipartFiles)) {
                $payloadSummary['attachments'] = array_map(function($att) {
                    return [
                        'fileName' => $att['fileName'] ?? 'unknown',
                        'contentType' => $att['contentType'] ?? 'unknown',
                        'contentLength' => $att['size'] ?? 0,
                        'contentPreview' => '...[multipart binary]...'
                    ];
                }, $multipartFiles);
            }
            if (!empty($linkOnlyMeta)) {
                $payloadSummary['linkOnlyAttachments'] = $linkOnlyMeta;
            }
            $endpoint = !empty($multipartFiles)
                ? '/restapi/v1.0/account/~/extension/~/mms'
                : '/restapi/v1.0/account/~/extension/~/sms';


            $sendRequest = function (array $sendPayload) use ($multipartFiles, $endpoint, $platform) {
                if (!empty($multipartFiles)) {
                    $builder = $this->sdk->createMultipartBuilder();
                    $builder->setBody($sendPayload);
                    foreach ($multipartFiles as $part) {
                        $builder->add(
                            $part['content'],
                            $part['fileName'],
                            ['Content-Type' => $part['contentType']]
                        );
                    }
                    $request = $builder->request($endpoint, 'POST');
                    return $platform->sendRequest($request);
                }
                return $platform->post($endpoint, $sendPayload);
            };

            try {
                $response = $sendRequest($payload);
                $respJson = $response->json();
            } catch (\Exception $e) {
                $respBody = method_exists($e, 'getResponse') && $e->getResponse()
                    ? (string) $e->getResponse()->getBody()
                    : $e->getMessage();

                $subjectRejected = isset($payload['subject'])
                    && stripos((string) $respBody, 'subject') !== false
                    && stripos((string) $respBody, 'invalid') !== false;

                if ($subjectRejected) {
                    $retryPayload = $payload;
                    unset($retryPayload['subject']);
                    $response = $sendRequest($retryPayload);
                    $respJson = $response->json();
                } else {
                    throw $e;
                }
            }

            // Log the sent SMS in the database
            RingCentralMessage::create([
                'ringcentral_user_id' => $ringCentralUser->id,
                'message_id' => $respJson->id ?? null,
                'from_name' => null,
                'to_name' => null,
                'from_number' => $fromPhone,
                'to_number' => implode(',', $toNumbers),
                'message_body' => $message,
                'attachments' => !empty($attachmentMeta) ? $attachmentMeta : null,
                'direction' => 'outbound',
                'status' => 'sent',
                'sent_at' => now()
            ]);

            return [
                'success' => true,
                'message' => 'SMS sent successfully',
                'message_id' => $respJson->id ?? null,
                'to_numbers' => $toNumbers,
                'attachments' => !empty($attachmentMeta) ? $attachmentMeta : null,
            ];

        } catch (\Exception $e) {
            $rawMessage = $e->getMessage();
            $providerMessage = $this->extractRingCentralErrorMessage($e);
            $friendlyMessage = $this->friendlyRingCentralSmsErrorMessage($providerMessage ?: $rawMessage);
            Log::warning('R-Dialer SMS send failed.', [
                'user_id' => (int) $userId,
                'to_numbers' => $toNumbers ?? [],
                'from_number' => $fromPhone ?? null,
                'provider_message' => $this->sanitizeRingCentralError($providerMessage ?: $rawMessage),
            ]);

            return [
                'success' => false,
                'message' => $friendlyMessage,
            ];
        }
    }

    private function friendlyRingCentralSmsErrorMessage(string $message): string
    {
        $raw = trim($message);
        if ($raw === '') {
            return 'Failed to send SMS.';
        }

        $lower = strtolower($raw);
        if (
            str_contains($lower, 'phone number is blocked')
            || str_contains($lower, 'blocked number')
            || (str_contains($lower, 'parameter [to]') && str_contains($lower, 'blocked'))
        ) {
            return 'Blocked number.';
        }
        if (
            (str_contains($lower, 'parameter [to]') && str_contains($lower, 'invalid'))
            || (str_contains($lower, 'to.phonenumber') && str_contains($lower, 'invalid value'))
            || str_contains($lower, 'invalid phone number')
        ) {
            return 'Invalid phone number.';
        }
        if (
            (str_contains($lower, 'parameter [from]') && str_contains($lower, 'invalid'))
            || (str_contains($lower, 'from.phonenumber') && str_contains($lower, 'invalid value'))
        ) {
            return 'Invalid sender number. Please reconnect R-Dialer.';
        }
        if (str_contains($lower, 'failed to authenticate') || str_contains($lower, 'unauthorized')) {
            return 'R-Dialer session expired. Please reconnect.';
        }

        return preg_replace('/^failed to send sms:\s*/i', '', $raw) ?: 'Failed to send SMS.';
    }

    private function downloadMessageAttachment($ringCentralUser, array $attachment)
    {
        try {
            $uri = $attachment['uri'] ?? $attachment['contentUri'] ?? null;
            if (!$uri) {
                return null;
            }

            $downloadUrl = str_starts_with($uri, 'http') ? $uri : ($this->server . $uri);

            $accessToken = decrypt($ringCentralUser->access_token);
            $client = new \GuzzleHttp\Client(['timeout' => 30]);
            $response = $client->get($downloadUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => '*/*',
                ],
            ]);

            if ($response->getStatusCode() !== 200) {
                return null;
            }

            $contentType = $attachment['contentType'] ?? $response->getHeaderLine('Content-Type') ?: 'application/octet-stream';
            $fileName = $attachment['fileName'] ?? $attachment['filename'] ?? ('attachment_' . Str::random(6));
            $safeName = time() . '_' . Str::random(8) . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $fileName);
            $storedPath = 'ringcentral_attachments/' . $ringCentralUser->user_id . '/' . $safeName;

            Storage::disk('public')->put($storedPath, (string) $response->getBody());

            $proxyUrl = route('ringcentral.api.attachment', ['path' => $storedPath]);
            $downloadUrl = route('ringcentral.api.attachment', ['path' => $storedPath, 'download' => 1]);

            return [
                'fileName' => $fileName,
                'contentType' => $contentType,
                'size' => $attachment['size'] ?? null,
                'path' => $storedPath,
                'local_path' => $proxyUrl,
                'url' => $proxyUrl,
                'download_url' => $downloadUrl,
                'uri' => $uri,
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

/**
 * Create a telephony conference (returns voiceCallToken for SIP INVITE)
 */
public function createTelephonyConference($userId)
{
    try {
        // Fetch user based on ID
        $ringCentralUser = RingCentralUser::where('user_id', $userId)->firstOrFail();

        // Authenticate platform
        $platform = $this->getPlatformWithAuth($ringCentralUser);
        if (!$platform) {
            return [
                'success' => false,
                'message' => 'Failed to authenticate with RingCentral. Please reconnect.'
            ];
        }

        // Send request to create a conference
        $emptyObject = new \stdClass();
        $response = $platform->post('/restapi/v1.0/account/~/telephony/conference', $emptyObject);
        $respJson = $response->json();

        return [
            'success' => true,
            'session' => $respJson->session ?? null,
            'data' => $respJson,
        ];

    } catch (\Exception $e) {
        // Log error and return failure
        return [
            'success' => false,
            'message' => 'Failed to create conference: ' . $e->getMessage()
        ];
    }
}

/**
 * List telephony sessions for the authenticated extension.
 */
public function getTelephonySessions($userId, array $query = [])
{
    try {
        $ringCentralUser = RingCentralUser::where('user_id', $userId)->firstOrFail();
        $platform = $this->getPlatformWithAuth($ringCentralUser);
        if (!$platform) {
            return ['success' => false, 'message' => 'Platform auth failed', 'status_code' => 401];
        }

        $params = [];
        if (isset($query['activeOnly'])) {
            $params['activeOnly'] = filter_var($query['activeOnly'], FILTER_VALIDATE_BOOLEAN);
        }
        if (!empty($query['perPage'])) {
            $params['perPage'] = (int) $query['perPage'];
        }

        // Some accounts return AGW-404 on extension-scoped sessions.
        // Retry with account-scoped endpoint to keep call-control flow working.
        $sessionEndpoints = [
            '/restapi/v1.0/account/~/extension/~/telephony/sessions',
            '/restapi/v1.0/account/~/telephony/sessions',
        ];

        $response = null;
        $lastEndpointErrorDetails = null;
        foreach ($sessionEndpoints as $idx => $endpoint) {
            try {
                $response = !empty($params)
                    ? $platform->get($endpoint, $params)
                    : $platform->get($endpoint);
                break;
            } catch (\Exception $endpointException) {
                $details = $this->extractRingCentralErrorDetails($endpointException);
                $lastEndpointErrorDetails = $details;
                $isNotFound = (int) ($details['status_code'] ?? 500) === 404
                    || strtoupper((string) ($details['error_code'] ?? '')) === 'AGW-404';
                $hasFallback = $idx < (count($sessionEndpoints) - 1);

                if ($hasFallback && $isNotFound) {
                    continue;
                }

                // If all known telephony session endpoints return not found,
                // fall through to Presence fallback after loop.
                if ($isNotFound) {
                    continue;
                }

                throw $endpointException;
            }
        }

        if (!$response) {
            $canUsePresenceFallback = (int) ($lastEndpointErrorDetails['status_code'] ?? 500) === 404
                || strtoupper((string) ($lastEndpointErrorDetails['error_code'] ?? '')) === 'AGW-404';

            if ($canUsePresenceFallback) {
                return $this->getTelephonySessionsFromPresenceFallback($platform, $ringCentralUser, $query);
            }

            throw new \RuntimeException('Unable to resolve telephony sessions endpoint');
        }

        return [
            'success' => true,
            'data' => $response->json(),
            'status_code' => 200,
        ];
    } catch (\Exception $e) {
        $errorDetails = $this->extractRingCentralErrorDetails($e);
        return [
            'success' => false,
            'message' => 'Failed to get telephony sessions: ' . $errorDetails['provider_message'],
            'status_code' => $errorDetails['status_code'],
            'provider_response' => $errorDetails['provider_response'],
        ];
    }
}

/**
 * Fallback for accounts where telephony sessions endpoint is not available.
 * Uses Presence activeCalls and maps to a telephony-sessions-like records payload.
 */
private function getTelephonySessionsFromPresenceFallback($platform, $ringCentralUser, array $query = [])
{
    try {
        $presenceResponse = $this->platformGetWithRetry(
            $platform,
            $ringCentralUser,
            '/restapi/v1.0/account/~/extension/~/presence',
            ['detailedTelephonyState' => true]
        );

        $presencePayload = $presenceResponse->json();
        $records = $this->mapPresenceToTelephonySessionRecords($presencePayload, $query);


        return [
            'success' => true,
            'status_code' => 200,
            'fallback' => 'presence',
            'data' => [
                'records' => $records,
                'source' => 'presence',
            ],
        ];
    } catch (\Exception $e) {
        $errorDetails = $this->extractRingCentralErrorDetails($e);

        return [
            'success' => false,
            'message' => 'Failed to get telephony sessions via presence fallback: ' . $errorDetails['provider_message'],
            'status_code' => $errorDetails['status_code'],
            'provider_response' => $errorDetails['provider_response'],
        ];
    }
}

private function mapPresenceToTelephonySessionRecords($presencePayload, array $query = []): array
{
    if (is_object($presencePayload)) {
        $presencePayload = json_decode(json_encode($presencePayload), true);
    }

    $presencePayload = is_array($presencePayload) ? $presencePayload : [];
    $activeCalls = $presencePayload['activeCalls'] ?? [];
    if (is_object($activeCalls)) {
        $activeCalls = json_decode(json_encode($activeCalls), true);
    }
    $activeCalls = is_array($activeCalls) ? $activeCalls : [];

    $activeOnly = isset($query['activeOnly'])
        ? filter_var($query['activeOnly'], FILTER_VALIDATE_BOOLEAN)
        : true;
    $perPage = !empty($query['perPage']) ? (int) $query['perPage'] : null;

    $sessionsById = [];
    foreach ($activeCalls as $call) {
        if (is_object($call)) {
            $call = json_decode(json_encode($call), true);
        }
        if (!is_array($call)) {
            continue;
        }

        $sessionId = $call['telephonySessionId'] ?? ($call['sessionId'] ?? null);
        $partyId = $call['partyId'] ?? null;
        if (!$sessionId || !$partyId) {
            continue;
        }

        $statusCode = $this->mapPresenceTelephonyStatusToPartyStatus($call['telephonyStatus'] ?? null);
        if ($activeOnly && !$this->isAliveCallControlStatus($statusCode)) {
            continue;
        }

        if (!isset($sessionsById[$sessionId])) {
            $sessionsById[$sessionId] = [
                'id' => $sessionId,
                'sessionId' => $sessionId,
                'creationTime' => $call['startTime'] ?? null,
                'origin' => 'presence',
                'source' => 'presence',
                'parties' => [],
            ];
        }

        $sessionsById[$sessionId]['parties'][] = [
            'id' => $partyId,
            'status' => ['code' => $statusCode],
            'direction' => $call['direction'] ?? null,
            'from' => $this->normalizePresencePartyEndpoint($call['from'] ?? null),
            'to' => $this->normalizePresencePartyEndpoint($call['to'] ?? null),
        ];
    }

    $records = array_values($sessionsById);
    if ($perPage && $perPage > 0 && count($records) > $perPage) {
        $records = array_slice($records, 0, $perPage);
    }

    return $records;
}

private function mapPresenceTelephonyStatusToPartyStatus($telephonyStatus): string
{
    $raw = strtolower((string) $telephonyStatus);
    $normalized = preg_replace('/[^a-z]/', '', $raw);

    switch ($normalized) {
        case 'callconnected':
        case 'connected':
            return 'connected';
        case 'onhold':
        case 'hold':
            return 'hold';
        case 'answered':
            return 'answered';
        case 'parked':
            return 'parked';
        case 'ringing':
        case 'callringing':
        case 'setup':
        case 'proceeding':
        case 'inprogress':
        case 'calling':
            return 'proceeding';
        default:
            return $normalized ?: 'connected';
    }
}

private function isAliveCallControlStatus(string $statusCode): bool
{
    return in_array($statusCode, ['proceeding', 'answered', 'connected', 'hold', 'onhold', 'parked'], true);
}

private function normalizePresencePartyEndpoint($value): array
{
    if (is_object($value)) {
        $value = json_decode(json_encode($value), true);
    }

    if (is_array($value)) {
        $phoneNumber = $value['phoneNumber'] ?? ($value['extensionNumber'] ?? null);
        $name = $value['name'] ?? ($value['extensionNumber'] ?? null);
        return [
            'phoneNumber' => $phoneNumber,
            'name' => $name,
        ];
    }

    if ($value === null || $value === '') {
        return [
            'phoneNumber' => null,
            'name' => null,
        ];
    }

    return [
        'phoneNumber' => (string) $value,
        'name' => (string) $value,
    ];
}

/**
 * Transfer a specific telephony party.
 */
public function transferTelephonyParty($userId, $sessionId, $partyId, array $input = [])
{
    try {
        $ringCentralUser = RingCentralUser::where('user_id', $userId)->firstOrFail();
        $platform = $this->getPlatformWithAuth($ringCentralUser);
        if (!$platform) {
            return ['success' => false, 'message' => 'Platform auth failed', 'status_code' => 401];
        }

        $payload = [
            'phoneNumber' => $input['phone_number'] ?? ($input['phoneNumber'] ?? null),
        ];

        if (empty($payload['phoneNumber'])) {
            return [
                'success' => false,
                'message' => 'Transfer destination is required',
                'status_code' => 422,
            ];
        }

        $endpoint = "/restapi/v1.0/account/~/telephony/sessions/{$sessionId}/parties/{$partyId}/transfer";
        $response = $platform->post($endpoint, $payload);

        return [
            'success' => true,
            'data' => $response->json(),
            'status_code' => 200,
        ];
    } catch (\Exception $e) {
        $errorDetails = $this->extractRingCentralErrorDetails($e);
        return [
            'success' => false,
            'message' => 'Failed to transfer call: ' . $errorDetails['provider_message'],
            'status_code' => $errorDetails['status_code'],
            'provider_response' => $errorDetails['provider_response'],
        ];
    }
}

/**
 * Remove/disconnect a specific telephony party.
 */
public function removeTelephonyParty($userId, $sessionId, $partyId, array $input = [])
{
    $traceId = (string) Str::uuid();
    $ringCentralUser = null;
    $platform = null;
    $ownerExtensionId = '';
    $conferenceSessionId = (string) ($input['conference_session_id'] ?? '');
    $participantNumber = (string) ($input['participant_number'] ?? '');
    $requestedPartyIdFromInput = (string) ($input['requested_party_id'] ?? $partyId);

    try {
        $ringCentralUser = RingCentralUser::where('user_id', $userId)->firstOrFail();
        $ownerExtensionId = (string) ($ringCentralUser->extension_id ?? '');
        $platform = $this->getPlatformWithAuth($ringCentralUser);
        if (!$platform) {
            Log::warning('REMOVE-PARTY FLOW AUTH FAILED', [
                'trace_id' => $traceId,
                'user_id' => (int) $userId,
                'session_id' => (string) $sessionId,
                'party_id' => (string) $partyId,
            ]);
            return ['success' => false, 'message' => 'Platform auth failed', 'status_code' => 401];
        }

        Log::info('REMOVE-PARTY FLOW START', [
            'trace_id' => $traceId,
            'user_id' => (int) $userId,
            'session_id' => (string) $sessionId,
            'party_id' => (string) $partyId,
            'conference_session_id' => $conferenceSessionId,
            'requested_party_id' => $requestedPartyIdFromInput,
            'participant_last4' => $this->lastFourDigits($participantNumber),
            'owner_extension_id' => $ownerExtensionId,
        ]);

        $requestedPartyId = (string) $partyId;
        $partyCandidates = [];
        if ($requestedPartyId !== '') {
            $partyCandidates[] = $requestedPartyId;
            if (preg_match('/^(.*)-2$/', $requestedPartyId, $m)) {
                $partyCandidates[] = $m[1] . '-1';
            } elseif (preg_match('/^(.*)-1$/', $requestedPartyId, $m)) {
                $partyCandidates[] = $m[1] . '-2';
            }
        }

        $alternatePartyIds = $this->getTelephonySessionPartyIds(
            $platform,
            (string) $sessionId,
            $ringCentralUser->extension_id ?? null
        );
        foreach ($alternatePartyIds as $altPartyId) {
            $altPartyId = (string) $altPartyId;
            if ($altPartyId !== '' && !in_array($altPartyId, $partyCandidates, true)) {
                $partyCandidates[] = $altPartyId;
            }
        }

        $partyCandidates = array_values(array_unique(array_filter($partyCandidates, function ($id) {
            return (string) $id !== '';
        })));
        if (empty($partyCandidates)) {
            Log::warning('REMOVE-PARTY FLOW NO CANDIDATES', [
                'trace_id' => $traceId,
                'user_id' => (int) $userId,
                'session_id' => (string) $sessionId,
                'party_id' => (string) $partyId,
            ]);
            return [
                'success' => false,
                'message' => 'Failed to remove participant: missing session or party identifiers',
                'status_code' => 422,
            ];
        }

        Log::info('REMOVE-PARTY FLOW CANDIDATES READY', [
            'trace_id' => $traceId,
            'session_id' => (string) $sessionId,
            'party_candidates' => $partyCandidates,
            'requested_party_id' => $requestedPartyIdFromInput,
            'conference_session_id' => $conferenceSessionId,
        ]);

        $lastErrorDetails = null;
        foreach ($partyCandidates as $idx => $partyCandidate) {
            $endpoint = "/restapi/v1.0/account/~/telephony/sessions/{$sessionId}/parties/{$partyCandidate}";
            Log::info('REMOVE-PARTY ATTEMPT', [
                'trace_id' => $traceId,
                'session_id' => (string) $sessionId,
                'party_id' => (string) $partyCandidate,
                'candidate_index' => $idx,
                'candidate_total' => count($partyCandidates),
                'endpoint' => $endpoint,
            ]);
            try {
                $response = $platform->delete($endpoint);
                Log::info('REMOVE-PARTY ATTEMPT SUCCESS', [
                    'trace_id' => $traceId,
                    'session_id' => (string) $sessionId,
                    'selected_party_id' => (string) $partyCandidate,
                    'candidate_index' => $idx,
                ]);
                return [
                    'success' => true,
                    'data' => $response ? $response->json() : null,
                    'status_code' => 200,
                    'selected_party_id' => $partyCandidate,
                ];
            } catch (\Exception $candidateException) {
                $details = $this->extractRingCentralErrorDetails($candidateException);
                $lastErrorDetails = $details;
                $statusCode = (int) ($details['status_code'] ?? 0);
                $errorCode = strtoupper((string) ($details['error_code'] ?? ''));
                $providerMessage = strtolower((string) ($details['provider_message'] ?? ''));
                $isWrongPartyState = $errorCode === 'TAS-102'
                    || str_contains($providerMessage, 'wrongpartystate')
                    || str_contains($providerMessage, 'incorrect state');

                $canTryNextCandidate = $idx < (count($partyCandidates) - 1)
                    && ($isWrongPartyState || in_array($statusCode, [403, 404, 409], true));
                Log::warning('REMOVE-PARTY ATTEMPT FAILED', [
                    'trace_id' => $traceId,
                    'session_id' => (string) $sessionId,
                    'party_id' => (string) $partyCandidate,
                    'candidate_index' => $idx,
                    'candidate_total' => count($partyCandidates),
                    'status_code' => $statusCode,
                    'error_code' => $errorCode,
                    'provider_message' => $details['provider_message'] ?? null,
                    'is_wrong_party_state' => $isWrongPartyState,
                    'can_try_next_candidate' => $canTryNextCandidate,
                ]);
                if ($canTryNextCandidate) {
                    continue;
                }

                throw $candidateException;
            }
        }

        $requestedPartyId = $requestedPartyIdFromInput;
        if (
            $conferenceSessionId !== ''
            && is_array($lastErrorDetails)
            && $this->isWrongPartyStateError($lastErrorDetails)
        ) {
            Log::info('REMOVE-PARTY TRY EXPLICIT CONFERENCE FALLBACK', [
                'trace_id' => $traceId,
                'conference_session_id' => $conferenceSessionId,
                'requested_party_id' => $requestedPartyId,
                'participant_last4' => $this->lastFourDigits($participantNumber),
            ]);
            $conferenceFallback = $this->removeParticipantFromConferenceSession(
                $platform,
                $conferenceSessionId,
                $participantNumber,
                $requestedPartyId,
                $ownerExtensionId
            );
            if (!empty($conferenceFallback['success'])) {
                Log::info('REMOVE-PARTY EXPLICIT CONFERENCE FALLBACK SUCCESS', [
                    'trace_id' => $traceId,
                    'conference_session_id' => $conferenceSessionId,
                    'selected_party_id' => (string) ($conferenceFallback['selected_party_id'] ?? ''),
                    'selected_session_id' => (string) ($conferenceFallback['selected_session_id'] ?? ''),
                ]);
                return $conferenceFallback;
            }
            Log::warning('REMOVE-PARTY EXPLICIT CONFERENCE FALLBACK FAILED', [
                'trace_id' => $traceId,
                'conference_session_id' => $conferenceSessionId,
                'status_code' => (int) ($conferenceFallback['status_code'] ?? 0),
                'error_code' => (string) ($conferenceFallback['errorCode'] ?? ''),
                'message' => (string) ($conferenceFallback['message'] ?? ''),
            ]);
        }

        if (
            is_array($lastErrorDetails)
            && $this->isWrongPartyStateError($lastErrorDetails)
        ) {
            $discoveredConferenceSessionIds = $this->discoverActiveConferenceSessionIds(
                $platform,
                (int) $userId,
                $participantNumber,
                $requestedPartyId
            );
            Log::info('REMOVE-PARTY DISCOVERED CONFERENCE FALLBACK CANDIDATES', [
                'trace_id' => $traceId,
                'requested_party_id' => $requestedPartyId,
                'participant_last4' => $this->lastFourDigits($participantNumber),
                'conference_session_ids' => $discoveredConferenceSessionIds,
            ]);

            foreach ($discoveredConferenceSessionIds as $discoveredConferenceSessionId) {
                if ($discoveredConferenceSessionId === '' || $discoveredConferenceSessionId === $conferenceSessionId) {
                    continue;
                }
                Log::info('REMOVE-PARTY TRY DISCOVERED CONFERENCE FALLBACK', [
                    'trace_id' => $traceId,
                    'conference_session_id' => $discoveredConferenceSessionId,
                ]);
                $discoveredFallback = $this->removeParticipantFromConferenceSession(
                    $platform,
                    $discoveredConferenceSessionId,
                    $participantNumber,
                    $requestedPartyId,
                    $ownerExtensionId
                );
                if (!empty($discoveredFallback['success'])) {
                    Log::info('REMOVE-PARTY DISCOVERED CONFERENCE FALLBACK SUCCESS', [
                        'trace_id' => $traceId,
                        'conference_session_id' => $discoveredConferenceSessionId,
                        'selected_party_id' => (string) ($discoveredFallback['selected_party_id'] ?? ''),
                        'selected_session_id' => (string) ($discoveredFallback['selected_session_id'] ?? ''),
                    ]);
                    return $discoveredFallback;
                }
                Log::warning('REMOVE-PARTY DISCOVERED CONFERENCE FALLBACK FAILED', [
                    'trace_id' => $traceId,
                    'conference_session_id' => $discoveredConferenceSessionId,
                    'status_code' => (int) ($discoveredFallback['status_code'] ?? 0),
                    'error_code' => (string) ($discoveredFallback['errorCode'] ?? ''),
                    'message' => (string) ($discoveredFallback['message'] ?? ''),
                ]);
            }
        }

        Log::warning('REMOVE-PARTY FLOW FINAL FAILURE', [
            'trace_id' => $traceId,
            'session_id' => (string) $sessionId,
            'party_id' => (string) $partyId,
            'conference_session_id' => $conferenceSessionId,
            'requested_party_id' => $requestedPartyIdFromInput,
            'participant_last4' => $this->lastFourDigits($participantNumber),
            'status_code' => (int) ($lastErrorDetails['status_code'] ?? 500),
            'error_code' => (string) ($lastErrorDetails['error_code'] ?? ''),
            'provider_message' => (string) ($lastErrorDetails['provider_message'] ?? ''),
        ]);
        return [
            'success' => false,
            'message' => 'Failed to remove participant: ' . ($lastErrorDetails['provider_message'] ?? 'Unknown error'),
            'status_code' => (int) ($lastErrorDetails['status_code'] ?? 500),
            'provider_response' => $lastErrorDetails['provider_response'] ?? null,
            'errorCode' => $lastErrorDetails['error_code'] ?? null,
        ];
    } catch (\Exception $e) {
        $errorDetails = $this->extractRingCentralErrorDetails($e);
        $requestedPartyId = $requestedPartyIdFromInput;
        Log::error('REMOVE-PARTY FLOW EXCEPTION', [
            'trace_id' => $traceId,
            'user_id' => (int) $userId,
            'session_id' => (string) $sessionId,
            'party_id' => (string) $partyId,
            'conference_session_id' => $conferenceSessionId,
            'requested_party_id' => $requestedPartyId,
            'participant_last4' => $this->lastFourDigits($participantNumber),
            'status_code' => (int) ($errorDetails['status_code'] ?? 500),
            'error_code' => (string) ($errorDetails['error_code'] ?? ''),
            'provider_message' => (string) ($errorDetails['provider_message'] ?? ''),
        ]);
        if (
            $conferenceSessionId !== ''
            && $this->isWrongPartyStateError($errorDetails)
        ) {
            Log::info('REMOVE-PARTY EXCEPTION TRY EXPLICIT CONFERENCE FALLBACK', [
                'trace_id' => $traceId,
                'conference_session_id' => $conferenceSessionId,
                'requested_party_id' => $requestedPartyId,
            ]);
            $conferenceFallback = $this->removeParticipantFromConferenceSession(
                $platform ?? null,
                $conferenceSessionId,
                $participantNumber,
                $requestedPartyId,
                $ownerExtensionId
            );
            if (!empty($conferenceFallback['success'])) {
                Log::info('REMOVE-PARTY EXCEPTION CONFERENCE FALLBACK SUCCESS', [
                    'trace_id' => $traceId,
                    'conference_session_id' => $conferenceSessionId,
                    'selected_party_id' => (string) ($conferenceFallback['selected_party_id'] ?? ''),
                    'selected_session_id' => (string) ($conferenceFallback['selected_session_id'] ?? ''),
                ]);
                return $conferenceFallback;
            }
            Log::warning('REMOVE-PARTY EXCEPTION CONFERENCE FALLBACK FAILED', [
                'trace_id' => $traceId,
                'conference_session_id' => $conferenceSessionId,
                'status_code' => (int) ($conferenceFallback['status_code'] ?? 0),
                'error_code' => (string) ($conferenceFallback['errorCode'] ?? ''),
                'message' => (string) ($conferenceFallback['message'] ?? ''),
            ]);
        }

        return [
            'success' => false,
            'message' => 'Failed to remove participant: ' . $errorDetails['provider_message'],
            'status_code' => $errorDetails['status_code'],
            'provider_response' => $errorDetails['provider_response'],
        ];
    }
}

private function isWrongPartyStateError(array $details): bool
{
    $errorCode = strtoupper((string) ($details['error_code'] ?? ''));
    $providerMessage = strtolower((string) ($details['provider_message'] ?? ''));

    return $errorCode === 'TAS-102'
        || str_contains($providerMessage, 'wrongpartystate')
        || str_contains($providerMessage, 'incorrect state');
}

private function removeParticipantFromConferenceSession($platform, string $conferenceSessionId, string $participantNumber, string $requestedPartyId, string $ownerExtensionId = ''): array
{
    $traceId = (string) Str::uuid();
    if (!$platform) {
        Log::warning('REMOVE-PARTY CONFERENCE FALLBACK NO PLATFORM', [
            'trace_id' => $traceId,
            'conference_session_id' => $conferenceSessionId,
            'requested_party_id' => $requestedPartyId,
            'participant_last4' => $this->lastFourDigits($participantNumber),
        ]);
        return ['success' => false, 'message' => 'Platform auth failed', 'status_code' => 401];
    }

    $parties = $this->getTelephonySessionPartiesDetailed($platform, $conferenceSessionId);
    if (empty($parties)) {
        Log::warning('REMOVE-PARTY CONFERENCE FALLBACK NO PARTIES', [
            'trace_id' => $traceId,
            'conference_session_id' => $conferenceSessionId,
            'requested_party_id' => $requestedPartyId,
            'participant_last4' => $this->lastFourDigits($participantNumber),
        ]);
        return [
            'success' => false,
            'message' => 'Failed to remove participant: conference session parties not found',
            'status_code' => 404,
        ];
    }

    $normalizeDigits = function ($value): string {
        return preg_replace('/\D+/', '', (string) $value) ?: '';
    };
    $targetDigits = $normalizeDigits($participantNumber);

    $partySnapshot = [];
    $candidates = [];
    foreach ($parties as $party) {
        $pid = (string) ($party['id'] ?? '');
        if ($pid === '') continue;

        $ext = (string) ($party['owner']['extensionId'] ?? '');
        $conferenceRole = strtolower((string) ($party['conferenceRole'] ?? ''));
        $status = strtolower((string) ($party['status']['code'] ?? ''));
        $isAlive = in_array($status, ['proceeding', 'answered', 'connected', 'hold', 'onhold', 'parked'], true);
        $fromDigits = $normalizeDigits($party['from']['phoneNumber'] ?? '');
        $toDigits = $normalizeDigits($party['to']['phoneNumber'] ?? '');
        $partySnapshot[] = [
            'party_id' => $pid,
            'conference_role' => $conferenceRole,
            'status' => $status,
            'owner_extension_id' => $ext,
            'from_last4' => $this->lastFourDigits($fromDigits),
            'to_last4' => $this->lastFourDigits($toDigits),
        ];
        if (!$isAlive) continue;

        $matchesRequestedParty = ($requestedPartyId !== '' && $pid === $requestedPartyId);
        $matchesPhone = ($targetDigits !== '' && ($fromDigits === $targetDigits || $toDigits === $targetDigits));
        $isHostRole = $conferenceRole === 'host';

        // Skip conference host role; host extensionId can also appear on participant legs, so don't use ownerExtensionId filter.
        if ($isHostRole) continue;

        $score = 0;
        if ($matchesRequestedParty) $score += 30;
        if ($matchesPhone) $score += 20;
        if ($conferenceRole === 'participant') $score += 10;
        if ($targetDigits === '' && $conferenceRole !== 'participant') $score += 1;
        $candidates[] = [
            'partyId' => $pid,
            'score' => $score,
        ];
    }

    usort($candidates, function ($a, $b) {
        return ((int) $b['score']) <=> ((int) $a['score']);
    });

    Log::info('REMOVE-PARTY CONFERENCE FALLBACK CANDIDATES', [
        'trace_id' => $traceId,
        'conference_session_id' => $conferenceSessionId,
        'requested_party_id' => $requestedPartyId,
        'participant_last4' => $this->lastFourDigits($participantNumber),
        'owner_extension_id' => $ownerExtensionId,
        'party_snapshot' => $partySnapshot,
        'candidate_party_ids' => array_values(array_map(function ($row) {
            return (string) ($row['partyId'] ?? '');
        }, $candidates)),
    ]);

    if (empty($candidates)) {
        $aliveStatuses = ['proceeding', 'answered', 'connected', 'hold', 'onhold', 'parked'];
        $nonHostParties = array_values(array_filter($partySnapshot, function ($party) {
            return strtolower((string) ($party['conference_role'] ?? '')) !== 'host';
        }));
        $aliveNonHostParties = array_values(array_filter($nonHostParties, function ($party) use ($aliveStatuses) {
            return in_array(strtolower((string) ($party['status'] ?? '')), $aliveStatuses, true);
        }));

        // Idempotent remove: if there are no active non-host participants, treat as already removed.
        if (count($aliveNonHostParties) === 0) {
            Log::info('REMOVE-PARTY CONFERENCE FALLBACK ALREADY GONE', [
                'trace_id' => $traceId,
                'conference_session_id' => $conferenceSessionId,
                'requested_party_id' => $requestedPartyId,
                'participant_last4' => $this->lastFourDigits($participantNumber),
                'non_host_count' => count($nonHostParties),
            ]);
            return [
                'success' => true,
                'status_code' => 200,
                'message' => 'Participant already disconnected from conference',
                'selected_session_id' => $conferenceSessionId,
                'source' => 'conference-session-fallback-already-gone',
            ];
        }
    }

    $lastErrorDetails = null;
    foreach ($candidates as $idx => $candidate) {
        $candidatePartyId = (string) ($candidate['partyId'] ?? '');
        if ($candidatePartyId === '') continue;
        $endpoint = "/restapi/v1.0/account/~/telephony/sessions/{$conferenceSessionId}/parties/{$candidatePartyId}";
        Log::info('REMOVE-PARTY CONFERENCE FALLBACK ATTEMPT', [
            'trace_id' => $traceId,
            'conference_session_id' => $conferenceSessionId,
            'party_id' => $candidatePartyId,
            'candidate_index' => $idx,
            'candidate_total' => count($candidates),
            'endpoint' => $endpoint,
        ]);
        try {
            $response = $platform->delete($endpoint);
            Log::info('REMOVE-PARTY CONFERENCE FALLBACK SUCCESS', [
                'trace_id' => $traceId,
                'conference_session_id' => $conferenceSessionId,
                'party_id' => $candidatePartyId,
                'candidate_index' => $idx,
            ]);
            return [
                'success' => true,
                'data' => $response ? $response->json() : null,
                'status_code' => 200,
                'selected_party_id' => $candidatePartyId,
                'selected_session_id' => $conferenceSessionId,
                'source' => 'conference-session-fallback',
            ];
        } catch (\Exception $e) {
            $lastErrorDetails = $this->extractRingCentralErrorDetails($e);
            Log::warning('REMOVE-PARTY CONFERENCE FALLBACK ATTEMPT FAILED', [
                'trace_id' => $traceId,
                'conference_session_id' => $conferenceSessionId,
                'party_id' => $candidatePartyId,
                'candidate_index' => $idx,
                'status_code' => (int) ($lastErrorDetails['status_code'] ?? 0),
                'error_code' => (string) ($lastErrorDetails['error_code'] ?? ''),
                'provider_message' => (string) ($lastErrorDetails['provider_message'] ?? ''),
            ]);
            continue;
        }
    }

    Log::warning('REMOVE-PARTY CONFERENCE FALLBACK FINAL FAILURE', [
        'trace_id' => $traceId,
        'conference_session_id' => $conferenceSessionId,
        'requested_party_id' => $requestedPartyId,
        'participant_last4' => $this->lastFourDigits($participantNumber),
        'status_code' => (int) ($lastErrorDetails['status_code'] ?? 409),
        'error_code' => (string) ($lastErrorDetails['error_code'] ?? ''),
        'provider_message' => (string) ($lastErrorDetails['provider_message'] ?? ''),
    ]);
    return [
        'success' => false,
        'message' => 'Failed to remove participant: ' . ($lastErrorDetails['provider_message'] ?? 'Incorrect State [WrongPartyState]'),
        'status_code' => (int) ($lastErrorDetails['status_code'] ?? 409),
        'provider_response' => $lastErrorDetails['provider_response'] ?? null,
        'errorCode' => $lastErrorDetails['error_code'] ?? null,
    ];
}

private function discoverActiveConferenceSessionIds($platform, int $userId, string $participantNumber = '', string $requestedPartyId = ''): array
{
    $normalizeDigits = function ($value): string {
        return preg_replace('/\D+/', '', (string) $value) ?: '';
    };

    $targetDigits = $normalizeDigits($participantNumber);
    $candidateIds = [];

    try {
        $sessionsResult = $this->getTelephonySessions($userId, ['activeOnly' => true]);
        if (empty($sessionsResult['success'])) {
            Log::warning('REMOVE-PARTY DISCOVER CONFERENCE SESSIONS FAILED', [
                'user_id' => $userId,
                'requested_party_id' => $requestedPartyId,
                'participant_last4' => $this->lastFourDigits($participantNumber),
                'status_code' => (int) ($sessionsResult['status_code'] ?? 0),
                'message' => (string) ($sessionsResult['message'] ?? ''),
            ]);
            return [];
        }

        $records = [];
        $data = $sessionsResult['data'] ?? null;
        if (is_object($data)) {
            $data = json_decode(json_encode($data), true);
        }
        if (is_array($data)) {
            if (isset($data['records']) && is_array($data['records'])) {
                $records = $data['records'];
            } elseif (array_is_list($data)) {
                $records = $data;
            }
        }

        foreach ($records as $record) {
            if (is_object($record)) {
                $record = json_decode(json_encode($record), true);
            }
            if (!is_array($record)) {
                continue;
            }

            $originType = strtolower((string) ($record['origin']['type'] ?? $record['origin'] ?? $record['source'] ?? ''));
            if ($originType !== 'conference') {
                continue;
            }

            $sid = (string) ($record['id'] ?? $record['sessionId'] ?? '');
            if ($sid === '') {
                continue;
            }

            $hasMatch = false;
            $parties = $record['parties'] ?? [];
            if (is_object($parties)) {
                $parties = json_decode(json_encode($parties), true);
            }
            $parties = is_array($parties) ? $parties : [];

            foreach ($parties as $party) {
                if (is_object($party)) {
                    $party = json_decode(json_encode($party), true);
                }
                if (!is_array($party)) {
                    continue;
                }

                $pid = (string) ($party['id'] ?? '');
                $fromDigits = $normalizeDigits($party['from']['phoneNumber'] ?? '');
                $toDigits = $normalizeDigits($party['to']['phoneNumber'] ?? '');
                if ($requestedPartyId !== '' && $pid === $requestedPartyId) {
                    $hasMatch = true;
                    break;
                }
                if ($targetDigits !== '' && ($fromDigits === $targetDigits || $toDigits === $targetDigits)) {
                    $hasMatch = true;
                    break;
                }
            }

            if ($hasMatch || empty($targetDigits)) {
                $candidateIds[] = $sid;
            }
        }
    } catch (\Throwable $ignored) {
        Log::warning('REMOVE-PARTY DISCOVER CONFERENCE SESSIONS EXCEPTION', [
            'user_id' => $userId,
            'requested_party_id' => $requestedPartyId,
            'participant_last4' => $this->lastFourDigits($participantNumber),
            'exception' => $ignored->getMessage(),
        ]);
        return [];
    }

    $resolvedIds = array_values(array_unique(array_filter($candidateIds, function ($id) {
        return (string) $id !== '';
    })));

    Log::info('REMOVE-PARTY DISCOVER CONFERENCE SESSIONS RESULT', [
        'user_id' => $userId,
        'requested_party_id' => $requestedPartyId,
        'participant_last4' => $this->lastFourDigits($participantNumber),
        'conference_session_ids' => $resolvedIds,
    ]);

    return $resolvedIds;
}

/**
 * Merge two calls by creating a conference and bringing both parties in.
 */
public function mergeTelephonySessions($userId, array $input)
{
    try {
        $conference = $this->createTelephonyConference($userId);
        if (empty($conference['success']) || empty($conference['session']->id)) {
            return [
                'success' => false,
                'message' => $conference['message'] ?? 'Failed to create conference',
                'status_code' => (int) ($conference['status_code'] ?? 500),
            ];
        }

        $conferenceSessionId = $conference['session']->id;

        $first = $this->bringInParty($userId, $conferenceSessionId, [
            'session_id' => $input['primary_session_id'] ?? null,
            'party_id' => $input['primary_party_id'] ?? null,
            'prefer_owner_leg' => true,
        ]);
        if (empty($first['success'])) {
            if ($this->isBringInTas106Failure($first)) {
                return [
                    'success' => false,
                    'status_code' => 409,
                    'errorCode' => 'TAS-106',
                    'message' => 'Bring-in was rejected (TAS-106). Automatic transfer fallback to conference target is disabled.',
                    'data' => [
                        'conference' => $conference['data'] ?? null,
                        'bring_in_primary' => $first,
                        'bring_in_secondary' => null,
                        'requires_webphone_merge_fallback' => true,
                    ],
                ];
            }
            return $first;
        }

        $second = $this->bringInParty($userId, $conferenceSessionId, [
            'session_id' => $input['secondary_session_id'] ?? null,
            'party_id' => $input['secondary_party_id'] ?? null,
            'prefer_owner_leg' => true,
        ]);
        if (empty($second['success'])) {
            if ($this->isBringInTas106Failure($second)) {
                return [
                    'success' => false,
                    'status_code' => 409,
                    'errorCode' => 'TAS-106',
                    'message' => 'Bring-in was rejected (TAS-106). Automatic transfer fallback to conference target is disabled.',
                    'data' => [
                        'conference' => $conference['data'] ?? null,
                        'bring_in_primary' => $first,
                        'bring_in_secondary' => $second,
                        'requires_webphone_merge_fallback' => true,
                    ],
                ];
            }
            return $second;
        }

        return [
            'success' => true,
            'status_code' => 200,
            'conference_session_id' => $conferenceSessionId,
            'data' => [
                'conference' => $conference['data'] ?? null,
                'bring_in_primary' => $first['data'] ?? null,
                'bring_in_secondary' => $second['data'] ?? null,
            ],
        ];
    } catch (\Exception $e) {
        $errorDetails = $this->extractRingCentralErrorDetails($e);
        return [
            'success' => false,
            'message' => 'Failed to merge calls: ' . $errorDetails['provider_message'],
            'status_code' => $errorDetails['status_code'],
            'provider_response' => $errorDetails['provider_response'],
        ];
    }
}

private function isBringInTas106Failure(array $result): bool
{
    if (!empty($result['success'])) {
        return false;
    }

    $errorCode = strtoupper((string) ($result['errorCode'] ?? ''));
    if ($errorCode === 'TAS-106') {
        return true;
    }

    $provider = $result['provider_response'] ?? null;
    if (is_object($provider)) {
        $provider = json_decode(json_encode($provider), true);
    }

    if (is_array($provider)) {
        $providerCode = strtoupper((string) ($provider['errorCode'] ?? ($provider['errors'][0]['errorCode'] ?? '')));
        if ($providerCode === 'TAS-106') {
            return true;
        }
    }

    $statusCode = (int) ($result['status_code'] ?? 0);
    $message = strtolower((string) ($result['message'] ?? ''));

    return $statusCode === 403
        && (str_contains($message, 'tas-106') || str_contains($message, 'operation is not allowed'));
}

/**
 * Switch a telephony call to web by invoking pickup action.
 */
public function switchTelephonyPartyToWeb($userId, $sessionId, $partyId, array $input = [])
{
    try {
        $ringCentralUser = RingCentralUser::where('user_id', $userId)->firstOrFail();
        $platform = $this->getPlatformWithAuth($ringCentralUser);
        if (!$platform) {
            return ['success' => false, 'message' => 'Platform auth failed', 'status_code' => 401];
        }

        $payload = [];
        if (!empty($input['device_id'])) {
            $payload['deviceId'] = $input['device_id'];
        }
        if (!empty($input['target_device_id'])) {
            $payload['deviceId'] = $input['target_device_id'];
        }

        $endpoint = "/restapi/v1.0/account/~/telephony/sessions/{$sessionId}/parties/{$partyId}/pickup";
        $response = empty($payload)
            ? $platform->post($endpoint, new \stdClass())
            : $platform->post($endpoint, $payload);

        return [
            'success' => true,
            'data' => $response->json(),
            'status_code' => 200,
        ];
    } catch (\Exception $e) {
        $errorDetails = $this->extractRingCentralErrorDetails($e);
        return [
            'success' => false,
            'message' => 'Failed to switch call to web: ' . $errorDetails['provider_message'],
            'status_code' => $errorDetails['status_code'],
            'provider_response' => $errorDetails['provider_response'],
        ];
    }
}

/**
 * Bring an additional party into an active telephony session
 */
    public function bringInParty($userId, $sessionId, array $input = [])
    {
        try {
            // Fetch the user and authenticate
            $ringCentralUser = RingCentralUser::where('user_id', $userId)->firstOrFail();
            $platform = $this->getPlatformWithAuth($ringCentralUser);

            if (!$platform) {
                return [
                    'success' => false,
                    'message' => 'Failed to authenticate with RingCentral. Please reconnect.'
                ];
            }

            // Prepare the endpoint for bringing in a party to the conference
            $endpoint = "/restapi/v1.0/account/~/telephony/sessions/{$sessionId}/parties/bring-in";

            $callSessionId = $input['session_id'] ?? ($input['session_id'] ?? ($input['sessionId'] ?? null));
            $requestedPartyId = $input['party_id'] ?? ($input['partyId'] ?? null);
            $preferOwnerLeg = filter_var($input['prefer_owner_leg'] ?? false, FILTER_VALIDATE_BOOLEAN);

            // Build retry candidates for party id. TAS-106 can happen when the
            // provided party is not controllable for the current user.
            $partyCandidates = [];
            if (!empty($requestedPartyId)) {
                $requestedPartyId = (string) $requestedPartyId;

                if ($preferOwnerLeg && preg_match('/^(.*)-2$/', $requestedPartyId, $m)) {
                    $partyCandidates[] = $m[1] . '-1';
                    $partyCandidates[] = $requestedPartyId;
                } elseif ($preferOwnerLeg && preg_match('/^(.*)-1$/', $requestedPartyId, $m)) {
                    $partyCandidates[] = $requestedPartyId;
                    $partyCandidates[] = $m[1] . '-2';
                } else {
                    // Default behavior: respect caller-provided party first.
                    $partyCandidates[] = $requestedPartyId;
                    if (preg_match('/^(.*)-2$/', $requestedPartyId, $m)) {
                        $partyCandidates[] = $m[1] . '-1';
                    } elseif (preg_match('/^(.*)-1$/', $requestedPartyId, $m)) {
                        $partyCandidates[] = $m[1] . '-2';
                    }
                }
            }
            if (!empty($callSessionId)) {
                $alternatePartyIds = $this->getTelephonySessionPartyIds(
                    $platform,
                    (string) $callSessionId,
                    $ringCentralUser->extension_id ?? null
                );
                foreach ($alternatePartyIds as $partyId) {
                    $partyId = (string) $partyId;
                    if ($partyId !== '' && !in_array($partyId, $partyCandidates, true)) {
                        $partyCandidates[] = $partyId;
                    }
                }
            }

            // De-duplicate while keeping insertion order.
            $partyCandidates = array_values(array_unique(array_filter($partyCandidates, function ($id) {
                return (string) $id !== '';
            })));
            if ($preferOwnerLeg) {
                usort($partyCandidates, function ($a, $b) {
                    $score = function ($id) {
                        $id = (string) $id;
                        if (preg_match('/-1$/', $id)) return 2;
                        if (preg_match('/-2$/', $id)) return 1;
                        return 0;
                    };
                    return $score($b) <=> $score($a);
                });
            }

            if (empty($partyCandidates)) {
                return [
                    'success' => false,
                    'message' => 'Failed to bring party into session: missing session_id or party_id',
                    'status_code' => 422,
                ];
            }

            $lastErrorDetails = null;
            foreach ($partyCandidates as $idx => $partyCandidate) {
                $payload = [
                    'sessionId' => $callSessionId,
                    'partyId' => $partyCandidate,
                ];

                Log::info('BRING-IN PARTY TO CONFERENCE', [
                    'endpoint' => $endpoint,
                    'payload' => $payload,
                    'party_candidates' => $partyCandidates,
                    'conference_session_id' => $sessionId,
                    'candidate_index' => $idx,
                    'candidate_total' => count($partyCandidates),
                ]);

                try {
                    $response = $platform->post($endpoint, $payload);
                    $respJson = $response->json();


                    return [
                        'success' => true,
                        'data' => $respJson,
                        'status_code' => 200,
                        'selected_party_id' => $partyCandidate,
                    ];
                } catch (\Exception $candidateException) {
                    $details = $this->extractRingCentralErrorDetails($candidateException);
                    $lastErrorDetails = $details;
                    $isTas106 = strtoupper((string) ($details['error_code'] ?? '')) === 'TAS-106'
                        || stripos((string) ($details['provider_message'] ?? ''), 'operation is not allowed') !== false;

                    $statusCode = $details['status_code'] ?? null;
                    $errorCode = $details['error_code'] ?? null;

                    Log::warning('BRING-IN PARTY ATTEMPT FAILED', [
                        'conference_session_id' => $sessionId,
                        'party_id' => $partyCandidate,
                        'candidate_index' => $idx,
                        'prefer_owner_leg' => $preferOwnerLeg,
                        'is_tas_106' => $isTas106,
                        'status_code' => $statusCode,
                        'error_code' => $errorCode,
                        'provider_message' => $details['provider_message'] ?? null,
                    ]);

                    $canTryNextCandidate = $idx < \count($partyCandidates) - 1
                        && (
                            $isTas106
                            || \in_array($statusCode, [403, 404, 409], true)
                            || \in_array($errorCode, ['CMN-102', 'AGW-404'], true)
                        );

                    // Retry with next candidate for known bring-in rejection variants.
                    if ($canTryNextCandidate) {
                        continue;
                    }

                    throw $candidateException;
                }
            }

            return [
                'success' => false,
                'message' => 'Failed to bring party into session: ' . ($lastErrorDetails['provider_message'] ?? 'Unknown error'),
                'status_code' => (int) ($lastErrorDetails['status_code'] ?? 500),
                'errorCode' => $lastErrorDetails['error_code'] ?? null,
                'provider_response' => $lastErrorDetails['provider_response'] ?? null,
            ];

        } catch (\Exception $e) {
            $errorDetails = $this->extractRingCentralErrorDetails($e);


            return [
                'success' => false,
                'message' => 'Failed to bring party into session: ' . $errorDetails['provider_message'],
                'status_code' => $errorDetails['status_code'],
                'errorCode' => $errorDetails['error_code'],
                'provider_response' => $errorDetails['provider_response'],
            ];
        }
    }

    private function getTelephonySessionPartyIds($platform, string $callSessionId, ?string $preferredExtensionId = null): array
    {
        $endpoints = [
            "/restapi/v1.0/account/~/extension/~/telephony/sessions/{$callSessionId}",
            "/restapi/v1.0/account/~/telephony/sessions/{$callSessionId}",
        ];

        $extractPartyIds = function ($payload) use ($preferredExtensionId): array {
            if (is_object($payload)) {
                $payload = json_decode(json_encode($payload), true);
            }
            $payload = is_array($payload) ? $payload : [];

            $parties = $payload['parties'] ?? [];
            if (is_object($parties)) {
                $parties = json_decode(json_encode($parties), true);
            }
            $parties = is_array($parties) ? $parties : [];

            $preferredIds = [];
            $otherIds = [];
            foreach ($parties as $party) {
                if (is_object($party)) {
                    $party = json_decode(json_encode($party), true);
                }
                if (!is_array($party)) {
                    continue;
                }

                $partyId = $party['id'] ?? null;
                if (empty($partyId)) {
                    continue;
                }

                $partyId = (string) $partyId;
                $ownerExtensionId = (string) ($party['owner']['extensionId'] ?? '');
                $isPreferred = !empty($preferredExtensionId)
                    && $ownerExtensionId !== ''
                    && $ownerExtensionId === (string) $preferredExtensionId;

                if ($isPreferred) {
                    if (!in_array($partyId, $preferredIds, true)) {
                        $preferredIds[] = $partyId;
                    }
                    continue;
                }

                if (!in_array($partyId, $otherIds, true)) {
                    $otherIds[] = $partyId;
                }
            }

            return array_values(array_unique(array_merge($preferredIds, $otherIds)));
        };

        $lastErrorDetails = null;
        foreach ($endpoints as $endpoint) {
            try {
                $response = $platform->get($endpoint);
                $partyIds = $extractPartyIds($response->json());
                if (!empty($partyIds)) {
                    return $partyIds;
                }

                Log::warning('Telephony session details returned no parties for party-id fallback', [
                    'endpoint' => $endpoint,
                    'session_id' => $callSessionId,
                    'preferred_extension_id' => $preferredExtensionId,
                ]);
            } catch (\Exception $e) {
                $lastErrorDetails = $this->extractRingCentralErrorDetails($e);
                Log::warning('Failed to fetch telephony session details for party-id fallback', [
                    'endpoint' => $endpoint,
                    'session_id' => $callSessionId,
                    'preferred_extension_id' => $preferredExtensionId,
                    'status_code' => $lastErrorDetails['status_code'] ?? null,
                    'error_code' => $lastErrorDetails['error_code'] ?? null,
                    'provider_message' => $lastErrorDetails['provider_message'] ?? null,
                ]);
            }
        }

        return [];
    }

    private function getTelephonySessionPartiesDetailed($platform, string $callSessionId): array
    {
        $endpoints = [
            "/restapi/v1.0/account/~/extension/~/telephony/sessions/{$callSessionId}",
            "/restapi/v1.0/account/~/telephony/sessions/{$callSessionId}",
        ];

        foreach ($endpoints as $endpoint) {
            try {
                $response = $platform->get($endpoint);
                $payload = $response->json();
                if (is_object($payload)) {
                    $payload = json_decode(json_encode($payload), true);
                }
                $payload = is_array($payload) ? $payload : [];
                $parties = $payload['parties'] ?? [];
                if (is_object($parties)) {
                    $parties = json_decode(json_encode($parties), true);
                }
                $parties = is_array($parties) ? $parties : [];
                if (!empty($parties)) {
                    return $parties;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return [];
    }

    private function extractRingCentralErrorDetails(\Exception $e)
    {
        $statusCode = (int) $e->getCode();
        if ($statusCode < 100 || $statusCode > 599) {
            $statusCode = 500;
        }

        $providerMessage = (string) $e->getMessage();
        $providerResponse = null;
        $errorCode = null;

        try {
            if (method_exists($e, 'apiResponse')) {
                $apiResponse = $e->apiResponse();
                if ($apiResponse) {
                    if (method_exists($apiResponse, 'status')) {
                        $apiStatus = (int) $apiResponse->status();
                        if ($apiStatus >= 100 && $apiStatus <= 599) {
                            $statusCode = $apiStatus;
                        }
                    }
                    if (method_exists($apiResponse, 'json')) {
                        $providerResponse = $apiResponse->json();
                    }
                }
            }
        } catch (\Throwable $ignored) {
        }

        try {
            if ($providerResponse === null && method_exists($e, 'getResponse')) {
                $response = $e->getResponse();
                if ($response) {
                    if (method_exists($response, 'getStatusCode')) {
                        $respStatus = (int) $response->getStatusCode();
                        if ($respStatus >= 100 && $respStatus <= 599) {
                            $statusCode = $respStatus;
                        }
                    }

                    if (method_exists($response, 'getBody')) {
                        $raw = (string) $response->getBody();
                        $decoded = json_decode($raw, true);
                        $providerResponse = (json_last_error() === JSON_ERROR_NONE) ? $decoded : $raw;
                    }
                }
            }
        } catch (\Throwable $ignored) {
        }

        // Normalize provider response to array so nested errorCode values are readable.
        if (is_object($providerResponse)) {
            $providerResponse = json_decode(json_encode($providerResponse), true);
        }

        if (is_array($providerResponse)) {
            $source = $providerResponse;
            if (isset($providerResponse['stdClass']) && is_array($providerResponse['stdClass'])) {
                $source = $providerResponse['stdClass'];
            }

            $errorCode = $source['errorCode']
                ?? ($source['errors'][0]['errorCode'] ?? null)
                ?? ($providerResponse['errors'][0]['errorCode'] ?? null);

            $providerMessage = $source['message']
                ?? ($source['errors'][0]['message'] ?? null)
                ?? ($providerResponse['message'] ?? null)
                ?? ($providerResponse['errors'][0]['message'] ?? null)
                ?? $providerMessage;
        }

        if (!$errorCode && preg_match('/errorCode\"?\s*[:=]\s*\"?([A-Za-z0-9_\-]+)/i', $providerMessage, $m)) {
            $errorCode = $m[1];
        }

        return [
            'status_code' => $statusCode,
            'error_code' => $errorCode,
            'provider_message' => $providerMessage,
            'provider_response' => $providerResponse,
        ];
    }

    /**
     * Get call history
     *
     * @param int $userId
     * @param array $filters
     * @return array
     */
    public function getCallHistory($userId, $filters = [])
    {
        try {
            $ringCentralUser = RingCentralUser::where('user_id', $userId)->firstOrFail();

            // Log the platform instance being created
            $platform = $this->getPlatformWithAuth($ringCentralUser);

            // Log platform authentication status

            if (!$platform) {
                return [
                    'success' => false,
                    'message' => 'Failed to authenticate with RingCentral. Please reconnect.',
                ];
            }

            $fetchAll = $filters['fetchAll'] ?? false;
            $perPage = $filters['count'] ?? 100;
            $monthsBack = $filters['monthsBack'] ?? null;
            $phoneFilter = $filters['phoneNumber'] ?? null;
            $phoneFilterDigits = $phoneFilter ? preg_replace('/\D/', '', $phoneFilter) : null;
            $totalCallLogs = [];
            $page = 1;
            $totalSynced = 0;

            do {
                $queryParams = [
                    'detailedTelephonyState' => true,
                    'showBlocked' => false,
                    'perPage' => $perPage,
                    'page' => $page,
                ];

                if ($monthsBack) {
                    $dateFrom = Carbon::now()->subMonths((int) $monthsBack)->startOfDay();
                    $dateTo = Carbon::now();
                    $queryParams['dateFrom'] = $dateFrom->toIso8601String();
                    $queryParams['dateTo'] = $dateTo->toIso8601String();
                }

                if (isset($filters['direction'])) {
                    $queryParams['direction'] = $filters['direction'];
                }

                $response = $this->platformGetWithRetry(
                    $platform,
                    $ringCentralUser,
                    '/restapi/v1.0/account/~/extension/~/call-log',
                    $queryParams
                );

                $responseData = (array) $response->json();
                $callLogs = $responseData['records'] ?? [];
                $paging = isset($responseData['paging']) ? (array) $responseData['paging'] : [];
                $navigation = isset($responseData['navigation']) ? (array) $responseData['navigation'] : [];

                $pageStored = [];
                $storedInPage = 0;

                // Store call logs in database
                foreach ($callLogs as $log) {
                    $log = (array) $log;
                    $callId = $log['id'] ?? null;
                    $fromRaw = $log['from'] ?? [];
                    $toRaw = $log['to'] ?? [];

                    if (is_object($fromRaw)) {
                        $fromRaw = (array) $fromRaw;
                    }
                    if (is_object($toRaw)) {
                        $toRaw = (array) $toRaw;
                    }

                    if (is_array($fromRaw) && isset($fromRaw[0]) && (is_array($fromRaw[0]) || is_object($fromRaw[0]))) {
                        $fromRaw = (array) $fromRaw[0];
                    }
                    if (is_array($toRaw) && isset($toRaw[0]) && (is_array($toRaw[0]) || is_object($toRaw[0]))) {
                        $toRaw = (array) $toRaw[0];
                    }

                    $from = is_array($fromRaw) ? $fromRaw : [];
                    $to = is_array($toRaw) ? $toRaw : [];

                    if (!$callId) {
                        continue;
                    }

                    if ($phoneFilterDigits) {
                        $fromDigits = isset($from['phoneNumber']) ? preg_replace('/\D/', '', $from['phoneNumber']) : '';
                        $toDigits = isset($to['phoneNumber']) ? preg_replace('/\D/', '', $to['phoneNumber']) : '';
                        $matches = false;
                        if ($fromDigits && ($fromDigits === $phoneFilterDigits || str_ends_with($fromDigits, $phoneFilterDigits) || str_ends_with($phoneFilterDigits, $fromDigits))) $matches = true;
                        if ($toDigits && ($toDigits === $phoneFilterDigits || str_ends_with($toDigits, $phoneFilterDigits) || str_ends_with($phoneFilterDigits, $toDigits))) $matches = true;
                        if (!$matches) {
                            continue;
                        }
                    }

                    $fromNumber = $from['phoneNumber'] ?? ($from['phone'] ?? ($from['extensionNumber'] ?? null));
                    $toNumber = $to['phoneNumber'] ?? ($to['phone'] ?? ($to['extensionNumber'] ?? null));
                    $direction = strtolower((string) ($log['direction'] ?? ''));
                    $legacyPhoneNumber = ($direction === 'outbound')
                        ? ($toNumber ?? $fromNumber)
                        : ($fromNumber ?? $toNumber);

                    $attributes = [
                        'call_id' => $callId,
                        'ringcentral_user_id' => $ringCentralUser->id,
                        'from_number' => $fromNumber,
                        'to_number' => $toNumber,
                        'phone_number' => $legacyPhoneNumber,
                        'direction' => $log['direction'] ?? null,
                        'type' => $log['type'] ?? 'voice',
                        'duration_seconds' => $log['duration'] ?? 0,
                        'status' => $this->normalizeCallLogStatus($log['result'] ?? ($log['status'] ?? null)),
                        'call_started_at' => $log['startTime'] ?? null,
                        'call_ended_at' => $log['endTime'] ?? null,
                    ];

                    $existing = RingCentralCallLog::where('call_id', $callId)->first();
                    $isMissed = RingCentralCallLog::isMissedStatus($attributes['status']);

                    if ($existing) {
                        $wasMissed = RingCentralCallLog::isMissedStatus($existing->status);
                        $existing->fill($attributes);

                        if (!$isMissed) {
                            $existing->is_seen = true;
                            if (!$existing->seen_at) {
                                $existing->seen_at = now();
                            }
                        } elseif (!$wasMissed && $isMissed) {
                            $existing->is_seen = false;
                            $existing->seen_at = null;
                        }

                        $existing->save();
                    } else {
                        RingCentralCallLog::create($attributes + [
                            'is_seen' => $isMissed ? false : true,
                            'seen_at' => $isMissed ? null : now(),
                        ]);
                    }

                    $storedInPage++;
                    $pageStored[] = $log;
                }

                $totalSynced += $storedInPage;
                $totalCallLogs = array_merge($totalCallLogs, $pageStored);


                // Check if there are more pages
                $hasMore = !empty($callLogs) && (
                    !empty($navigation['nextPage']) ||
                    (isset($paging['page']) && isset($paging['totalPages']) && $paging['page'] < $paging['totalPages'])
                );

                if ($fetchAll && $hasMore) {
                    $page++;
                    // Add small delay to avoid rate limiting
                    usleep(200000); // 200ms
                } else {
                    break;
                }

            } while ($fetchAll);

            return [
                'success' => true,
                'data' => $totalCallLogs,
                'count' => count($totalCallLogs),
                'total_synced' => $totalSynced,
                'pages_fetched' => $page,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to get call history: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get token file path based on phone_number (not user_id)
     * 
     * TOKEN SHARING ARCHITECTURE:
     * - Token files are named by phone_number instead of user_id
     * - Multiple users with the same phone_number share the same token file
     * - Each user is tracked as a separate instance (1st, 2nd, 3rd, etc.)
     * - Instance tracking is done via WebPhoneInstance model (authorization_id based)
     * - Max instances per phone_number controlled by self::$maxInstancesAllowed
     * 
     * Example:
     * - User A (user_id=5) has phone +13072227674 → ringcentral_tokens_13072227674.json
     * - User B (user_id=6) has phone +13072227674 → ringcentral_tokens_13072227674.json (SAME FILE)
     * - User A accesses as 1st instance, User B accesses as 2nd instance
     * 
     * @param string $phoneNumber The phone number (e.g., +13072227674)
     * @return string Full path to token file
     */
    public function getTokensFilePathForUser($phoneNumber)
    {
        $basePath = storage_path($this->tokensDirName);
        if (!is_dir($basePath)) {
            try {
                mkdir($basePath, 0755, true);
            } catch (Exception $e) {
            }
        }

        // Use phone number - multiple Laravel users share same R-Dialer account
        $sanitizedPhone = preg_replace('/[^0-9]/', '', (string)$phoneNumber);
        return $basePath . DIRECTORY_SEPARATOR . 'ringcentral_tokens_' . $sanitizedPhone . '.json';
    }

    public function resolveRefreshTokenExpiresAt(array $authData, $baseTime = null)
    {
        if (!empty($authData['refresh_token_expire_time'])) {
            return now()->createFromTimestamp((int) $authData['refresh_token_expire_time']);
        }

        if (!empty($authData['refresh_token_expires_in'])) {
            $base = $baseTime;
            if ($base instanceof \Carbon\Carbon) {
                $baseCarbon = $base;
            } elseif (is_int($base) || (is_string($base) && ctype_digit($base))) {
                $baseCarbon = now()->createFromTimestamp((int) $base);
            } else {
                $baseCarbon = now();
            }

            return $baseCarbon->copy()->addSeconds((int) $authData['refresh_token_expires_in']);
        }

        return null;
    }

    /**
     * Get reconnect auth data from session first, then token files.
     * Returns null if no usable token set exists.
     */
    public function getReconnectAuthData($userId)
    {
        $sessionAuth = Session::get('ringcentral_auth_data_' . $userId);
        if (is_array($sessionAuth) && !empty($sessionAuth['access_token']) && !empty($sessionAuth['refresh_token'])) {
            return [
                'authData' => $sessionAuth,
                'phoneNumber' => null,
                'source' => 'session',
            ];
        }

        $tokenDir = storage_path($this->tokensDirName);
        if (!is_dir($tokenDir)) {
            return null;
        }

        $files = glob($tokenDir . DIRECTORY_SEPARATOR . 'ringcentral_tokens_*.json') ?: [];
        $valid = [];

        foreach ($files as $file) {
            try {
                if (!is_file($file) || filesize($file) <= 0) {
                    continue;
                }

                $data = json_decode(file_get_contents($file), true);
                if (!is_array($data) || empty($data['access_token']) || empty($data['refresh_token'])) {
                    continue;
                }

                $base = basename($file);
                if (preg_match('/^ringcentral_tokens_(\d+)\.json$/', $base, $m)) {
                    $phone = '+' . $m[1];
                } else {
                    $phone = null;
                }

                $valid[] = [
                    'authData' => $data,
                    'phoneNumber' => $phone,
                    'source' => 'token_file',
                ];
            } catch (Exception $_e) {
            }
        }

        if (count($valid) === 1) {
            return $valid[0];
        }

        return null;
    }

    /**
     * Sync extension id and phone number from R-Dialer API for a user.
     */
    public function syncUserProfileFromApi(RingCentralUser $ringCentralUser)
    {
        try {
            $platform = $this->getPlatformWithAuth($ringCentralUser);
            if (!$platform) {
                return false;
            }

            $updateData = [];
            $phoneNumber = null;

            try {
                $extension = $platform->get('/restapi/v1.0/account/~/extension/~')->json();
                if (is_array($extension)) {
                    if (!empty($extension['id'])) {
                        $updateData['extension_id'] = $extension['id'];
                    }
                    if (!empty($extension['phoneNumber'])) {
                        $phoneNumber = $extension['phoneNumber'];
                    } elseif (!empty($extension['contact']['businessPhone'])) {
                        $phoneNumber = $extension['contact']['businessPhone'];
                    }
                } elseif (is_object($extension)) {
                    if (!empty($extension->id)) {
                        $updateData['extension_id'] = $extension->id;
                    }
                    if (!empty($extension->phoneNumber)) {
                        $phoneNumber = $extension->phoneNumber;
                    } elseif (!empty($extension->contact->businessPhone)) {
                        $phoneNumber = $extension->contact->businessPhone;
                    }
                }
            } catch (Exception $e) {
            }

            if (!$phoneNumber) {
                try {
                    $numbers = $platform->get('/restapi/v1.0/account/~/extension/~/phone-number')->json();
                    if (is_array($numbers) && !empty($numbers['records'][0]['phoneNumber'])) {
                        $phoneNumber = $numbers['records'][0]['phoneNumber'];
                    } elseif (is_object($numbers) && !empty($numbers->records[0]->phoneNumber)) {
                        $phoneNumber = $numbers->records[0]->phoneNumber;
                    }
                } catch (Exception $e) {
                }
            }

            if ($phoneNumber) {
                $updateData['phone_number'] = $phoneNumber;
            }

            if (!empty($updateData)) {
                $ringCentralUser->update($updateData);
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    /**
     * Get SMS/message history
     *
     * @param int $userId
     * @param array $filters
     * @return array
     */
    public function getMessageHistory($userId, $filters = [])
    {
        try {
            $ringCentralUser = RingCentralUser::where('user_id', $userId)->firstOrFail();

            $platform = $this->getPlatformWithAuth($ringCentralUser);
            if (!$platform) {
                return [
                    'success' => false,
                    'message' => 'Failed to authenticate with RingCentral. Please reconnect.',
                ];
            }

            $fetchAll = $filters['fetchAll'] ?? false;
            $perPage = $filters['count'] ?? 100;
            $monthsBack = $filters['monthsBack'] ?? null;
            $phoneFilter = $filters['phoneNumber'] ?? null;
            $phoneFilterDigits = $phoneFilter ? preg_replace('/\D/', '', $phoneFilter) : null;
            $totalMessages = [];
            $page = 1;
            $totalSynced = 0;

            do {
                $queryParams = [
                    'messageType' => 'SMS',
                    'perPage' => $perPage,
                    'page' => $page,
                ];

                if ($monthsBack) {
                    $dateFrom = Carbon::now()->subMonths((int) $monthsBack)->startOfDay();
                    $dateTo = Carbon::now();
                    $queryParams['dateFrom'] = $dateFrom->toIso8601String();
                    $queryParams['dateTo'] = $dateTo->toIso8601String();
                }

                if (isset($filters['direction'])) {
                    $queryParams['direction'] = $filters['direction'];
                }


                $response = $this->platformGetWithRetry(
                    $platform,
                    $ringCentralUser,
                    '/restapi/v1.0/account/~/extension/~/message-store',
                    $queryParams
                );

                $responseData = (array) $response->json();
                $messages = $responseData['records'] ?? [];
                //     'total_messages' => count($messages),
                //     'response_data' => $responseData
                // ]);
                $paging = isset($responseData['paging']) ? (array) $responseData['paging'] : [];
                $navigation = isset($responseData['navigation']) ? (array) $responseData['navigation'] : [];

                $pageStored = [];
                $storedInPage = 0;

                // Store messages in database
                foreach ($messages as $msg) {
                    $msg = (array) $msg;
                    $existingMessage = null;
                    if (!empty($msg['id'])) {
                        $existingMessage = RingCentralMessage::where('message_id', $msg['id'])->first();
                    }
                    $from = isset($msg['from']) ? (array) $msg['from'] : [];
                    $toEntries = isset($msg['to']) && is_array($msg['to']) ? $msg['to'] : [];
                    $to = count($toEntries) > 0 ? (array) $toEntries[0] : [];
                    $toPhoneNumbers = [];
                    foreach ($toEntries as $toEntryRaw) {
                        $toEntry = is_array($toEntryRaw) ? $toEntryRaw : (array) $toEntryRaw;
                        $candidate = $toEntry['phoneNumber'] ?? null;
                        if ($candidate) {
                            $normalizedCandidate = trim((string) $candidate);
                            if ($normalizedCandidate !== '' && !in_array($normalizedCandidate, $toPhoneNumbers, true)) {
                                $toPhoneNumbers[] = $normalizedCandidate;
                            }
                        }
                    }
                    
                    $fromName = $from['name'] ?? null;
                    $toName = $to['name'] ?? null;
                    $fromNumber = $from['phoneNumber'] ?? null;
                    $toNumber = !empty($toPhoneNumbers)
                        ? implode(',', $toPhoneNumbers)
                        : ($to['phoneNumber'] ?? null);
                    if ($existingMessage && is_string($existingMessage->to_number) && str_contains($existingMessage->to_number, ',')) {
                        // Preserve known multi-recipient targets for rows that were locally created as group sends.
                        $toNumber = $existingMessage->to_number;
                    }

                    if ($phoneFilterDigits) {
                        $fromDigits = $fromNumber ? preg_replace('/\D/', '', $fromNumber) : '';
                        $toDigitsValues = [];
                        if (is_string($toNumber) && $toNumber !== '') {
                            $toParts = preg_split('/\s*[,;]\s*/', $toNumber);
                            foreach ($toParts as $toPart) {
                                $digitsValue = preg_replace('/\D/', '', (string) $toPart);
                                if ($digitsValue !== '') $toDigitsValues[] = $digitsValue;
                            }
                        }
                        $matches = false;
                        if ($fromDigits && ($fromDigits === $phoneFilterDigits || str_ends_with($fromDigits, $phoneFilterDigits) || str_ends_with($phoneFilterDigits, $fromDigits))) $matches = true;
                        foreach ($toDigitsValues as $toDigits) {
                            if ($toDigits && ($toDigits === $phoneFilterDigits || str_ends_with($toDigits, $phoneFilterDigits) || str_ends_with($phoneFilterDigits, $toDigits))) {
                                $matches = true;
                                break;
                            }
                        }
                        if (!$matches) {
                            continue;
                        }
                    }

                    // Extract message body and trim whitespace; null if empty
                    $messageBody = trim($msg['text'] ?? $msg['body'] ?? $msg['subject'] ?? '');
                    $messageBody = ($messageBody !== '') ? $messageBody : null;

                    $attachmentsMeta = [];
                    if ($existingMessage && is_array($existingMessage->attachments) && count($existingMessage->attachments)) {
                        $attachmentsMeta = $existingMessage->attachments;
                    } elseif (!empty($msg['attachments']) && is_array($msg['attachments'])) {
                        foreach ($msg['attachments'] as $attachment) {
                            $attachment = (array) $attachment;
                            $meta = $this->downloadMessageAttachment($ringCentralUser, $attachment);
                            if ($meta) {
                                $attachmentsMeta[] = $meta;
                            } else {
                                $fallbackUri = $attachment['uri'] ?? $attachment['contentUri'] ?? null;
                                $fallbackUrl = $fallbackUri ? route('ringcentral.api.attachment', ['uri' => base64_encode($fallbackUri)]) : null;
                                $fallbackDownloadUrl = $fallbackUri ? route('ringcentral.api.attachment', ['uri' => base64_encode($fallbackUri), 'download' => 1]) : null;
                                $attachmentsMeta[] = [
                                    'fileName' => $attachment['fileName'] ?? $attachment['filename'] ?? null,
                                    'contentType' => $attachment['contentType'] ?? null,
                                    'size' => $attachment['size'] ?? null,
                                    'uri' => $fallbackUri,
                                    'url' => $fallbackUrl,
                                    'local_path' => $fallbackUrl,
                                    'download_url' => $fallbackDownloadUrl,
                                ];
                            }
                        }
                    }

                    RingCentralMessage::updateOrCreate(
                        ['message_id' => $msg['id'] ?? null],
                        [
                            'ringcentral_user_id' => $ringCentralUser->id,
                            'from_name' => $fromName,
                            'to_name' => $toName,
                            'from_number' => $fromNumber,
                            'to_number' => $toNumber,
                            'message_body' => $messageBody,
                            'attachments' => !empty($attachmentsMeta) ? $attachmentsMeta : null,
                            'direction' => $msg['direction'] ?? 'inbound',
                            'status' => $msg['readStatus'] ?? 'unread',
                            'sent_at' => $msg['creationTime'] ?? null,
                        ]
                    );

                    $storedInPage++;
                    $pageStored[] = $msg;
                }

                $totalSynced += $storedInPage;
                $totalMessages = array_merge($totalMessages, $pageStored);


                // Check if there are more pages
                $hasMore = !empty($messages) && (
                    !empty($navigation['nextPage']) ||
                    (isset($paging['page']) && isset($paging['totalPages']) && $paging['page'] < $paging['totalPages'])
                );

                if ($fetchAll && $hasMore) {
                    $page++;
                    // Add small delay to avoid rate limiting
                    usleep(200000); // 200ms
                } else {
                    break;
                }

            } while ($fetchAll);

            return [
                'success' => true,
                'data' => $totalMessages,
                'count' => count($totalMessages),
                'total_synced' => $totalSynced,
                'pages_fetched' => $page,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to get message history: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Mark R-Dialer messages as read
     *
     * @param int $userId
     * @param array $messageIds
     * @return array
     */
    public function markMessagesRead($userId, array $messageIds)
    {
        try {
            $ringCentralUser = RingCentralUser::where('user_id', $userId)->firstOrFail();

            $platform = $this->getPlatformWithAuth($ringCentralUser);
            if (!$platform) {
                return [
                    'success' => false,
                    'message' => 'Failed to authenticate with RingCentral. Please reconnect.',
                ];
            }

            $updated = [];
            $failed = [];

            foreach ($messageIds as $messageId) {
                $messageId = (string) $messageId;
                if ($messageId === '') {
                    continue;
                }

                try {
                    $platform->put(
                        '/restapi/v1.0/account/~/extension/~/message-store/' . $messageId,
                        ['readStatus' => 'Read']
                    );
                    $updated[] = $messageId;
                } catch (Exception $e) {
                    $failed[] = ['id' => $messageId, 'error' => $e->getMessage()];
                }
            }

            if (!empty($updated)) {
                RingCentralMessage::where('ringcentral_user_id', $ringCentralUser->id)
                    ->whereIn('message_id', $updated)
                    ->update(['status' => 'read']);
            }

            return [
                'success' => true,
                'updated_ids' => $updated,
                'failed' => $failed,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to mark messages read: ' . $e->getMessage(),
            ];
        }
    }

    private function isTokenNotFoundException(Exception $e): bool
    {
        return str_contains($e->getMessage(), 'Token not found');
    }

    private function platformGetWithRetry($platform, $ringCentralUser, $uri, $params = null, $allowRetry = true)
    {
        try {
            return $params !== null ? $platform->get($uri, $params) : $platform->get($uri);
        } catch (Exception $e) {
            if ($allowRetry && $ringCentralUser && $this->isTokenNotFoundException($e)) {

                if ($this->refreshToken($ringCentralUser, true)) {
                    $ringCentralUser->refresh();

                    $authData = Session::get('ringcentral_auth_data_' . $ringCentralUser->user_id);
                    if (!$authData) {
                        $accessToken = decrypt($ringCentralUser->access_token);
                        $refreshToken = decrypt($ringCentralUser->refresh_token);
                        $expiresIn = max(1, $ringCentralUser->token_expires_at->diffInSeconds(now()));
                        $authData = [
                            'token_type' => 'Bearer',
                            'access_token' => $accessToken,
                            'refresh_token' => $refreshToken,
                            'expires_in' => $expiresIn,
                        ];
                    }

                    try {
                        $platform->auth()->setData($authData);
                    } catch (Exception $authEx) {
                        $newPlatform = $this->getPlatformWithAuth($ringCentralUser);
                        if ($newPlatform) {
                            return $this->platformGetWithRetry($newPlatform, $ringCentralUser, $uri, $params, false);
                        }
                    }

                    return $this->platformGetWithRetry($platform, $ringCentralUser, $uri, $params, false);
                }
            }

            throw $e;
        }
    }

    /**
     * Get call recording URL from provider endpoint.
     *
     * @param int $userId
     * @param string $recordingId
     * @return array
     */
    public function getRecordingUrl($userId, $recordingId)
    {
        try {
            $ringCentralUser = RingCentralUser::where('user_id', $userId)->firstOrFail();

            $platform = $this->getPlatformWithAuth($ringCentralUser);
            if (!$platform) {
                return [
                    'success' => false,
                    'message' => 'Failed to authenticate with RingCentral. Please reconnect.',
                ];
            }

            $recordingIdText = trim((string) $recordingId);
            if ($recordingIdText === '') {
                return [
                    'success' => false,
                    'message' => 'Recording ID is required.',
                ];
            }

            // Validate provider access; browsers may not use this URL directly without auth.
            $this->platformGetWithRetry(
                $platform,
                $ringCentralUser,
                "/restapi/v1.0/account/~/recording/{$recordingIdText}/content"
            );

            return [
                'success' => true,
                'url' => "/restapi/v1.0/account/~/recording/{$recordingIdText}/content",
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to get recording URL: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Normalize phone number to E.164 format (+1234567890)
     *
     * @param string $phoneNumber
     * @return string Normalized phone number
     */
    private function normalizePhoneNumber($phoneNumber)
    {
        if (empty($phoneNumber)) {
            return $phoneNumber;
        }

        // Remove all non-digit characters except +
        $normalized = preg_replace('/[^\d+]/', '', $phoneNumber);

        // If already starts with +, return as-is
        if (strpos($normalized, '+') === 0) {
            return $normalized;
        }

        // If it's 10 digits (US number), add +1
        if (strlen($normalized) === 10 && is_numeric($normalized)) {
            return '+1' . $normalized;
        }

        // If it's 11 digits starting with 1 (US), add +
        if (strlen($normalized) === 11 && $normalized[0] === '1') {
            return '+' . $normalized;
        }

        // If it's already digits and > 10, add + if not there
        if (strlen($normalized) > 10 && is_numeric($normalized)) {
            return '+' . $normalized;
        }

        // Return as-is if we can't normalize
        return $phoneNumber;
    }

    /**
     * Get available phone numbers from R-Dialer account
     *
     * @param object $platform
     * @return array Array of phone numbers
     */
    private function getAvailablePhoneNumbers($platform, $ringCentralUser = null)
    {
        try {

            // Try to get phone numbers from the account level
            $response = $this->platformGetWithRetry(
                $platform,
                $ringCentralUser,
                '/restapi/v1.0/account/~/phone-numbers?usageType=MainCompanyNumber,CompanyNumber'
            );
            $data = $response->json();
            $phoneNumbers = [];

            if (isset($data->records) && is_array($data->records)) {
                foreach ($data->records as $record) {
                    if (isset($record->phoneNumber)) {
                        $phoneNumbers[] = $record->phoneNumber;
                    }
                }
            }


            return $phoneNumbers;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Refresh and persist phone numbers for a user.
     * Tries multiple endpoints and returns diagnostics.
     *
     * @param int $userId
     * @return array
     */
    public function refreshPhoneNumbers($userId)
    {
        try {
            $ringCentralUser = RingCentralUser::where('user_id', $userId)->firstOrFail();
            $platform = $this->getPlatformWithAuth($ringCentralUser);

            if (!$platform) {
                return (object) [
                    'success' => false,
                    'message' => 'Platform auth failed'
                ];
            }

            $extensionId = $ringCentralUser->extension_id;

            $diagnostics = (object) [
                'extension_id' => $extensionId,
                'extension_phone_numbers' => [],
                'account_phone_numbers' => [],
                'chosen_phone' => null,
                'extension_endpoint' => null,
            ];

            /* EXTENSION PHONE NUMBERS */
            if ($extensionId) {
                try {
                    $resp = $this->platformGetWithRetry(
                        $platform,
                        $ringCentralUser,
                        "/restapi/v1.0/account/~/extension/{$extensionId}/phone-number"
                    );
                    $json = $resp->json();

                    $diagnostics->extension_phone_numbers = $json->records ?? [];
                    $diagnostics->extension_endpoint = 'ok';

                    if (!empty($diagnostics->extension_phone_numbers)) {
                        $diagnostics->chosen_phone = $diagnostics->extension_phone_numbers[0]->phoneNumber ?? null;
                    }

                } catch (\Exception $e) {
                    $diagnostics->extension_endpoint = "error: {$e->getMessage()}";
                }
            }

            /* ACCOUNT LEVEL NUMBERS */
            try {
                $resp2 = $this->platformGetWithRetry(
                    $platform,
                    $ringCentralUser,
                    "/restapi/v1.0/account/~/phone-number"
                );
                $json2 = $resp2->json();

                $diagnostics->account_phone_numbers = $json2->records ?? [];

                if (!$diagnostics->chosen_phone && !empty($diagnostics->account_phone_numbers)) {
                    foreach ($diagnostics->account_phone_numbers as $rec) {
                        if (isset($rec->extension->id) && $rec->extension->id == $extensionId) {
                            $diagnostics->chosen_phone = $rec->phoneNumber;
                            break;
                        }
                    }
                }

                if (!$diagnostics->chosen_phone && !empty($diagnostics->account_phone_numbers)) {
                    $diagnostics->chosen_phone = $diagnostics->account_phone_numbers[0]->phoneNumber ?? null;
                }

            } catch (\Exception $e) {
                $diagnostics->account_phone_numbers_error = $e->getMessage();
            }

            /* SAVE */
            if ($diagnostics->chosen_phone) {
                $ringCentralUser->phone_number = $diagnostics->chosen_phone;
                $ringCentralUser->save();
            }

            return (object) [
                'success' => true,
                'diagnostics' => $diagnostics
            ];

        } catch (\Exception $e) {
            return (object) [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }



    /**
     * Lookup a phone number in the account to determine ownership/assignment.
     * Returns details from account-level and extension-level endpoints.
     *
     * @param int $userId
     * @param string $phoneNumber
     * @return array
     */
    public function lookupNumberOwnership($userId, $phoneNumber)
    {
        try {
            $ringCentralUser = RingCentralUser::where('user_id', $userId)->firstOrFail();

            $platform = $this->getPlatformWithAuth($ringCentralUser);
            if (!$platform) {
                return ['success' => false, 'message' => 'Platform auth failed'];
            }

            $normalized = preg_replace('/[^0-9+]/', '', $phoneNumber);

            $result = [
                'query' => $phoneNumber,
                'normalized' => $normalized,
                'matches' => [],
            ];

            // 1) Search account phone-numbers
            try {
                $resp = $this->platformGetWithRetry(
                    $platform,
                    $ringCentralUser,
                    '/restapi/v1.0/account/~/phone-numbers'
                );
                $acctPhonesPayload = $resp->json();
                $acctPhones = data_get($acctPhonesPayload, 'records', []);

                foreach ($acctPhones as $rec) {
                    $p = data_get($rec, 'phoneNumber');
                    if (!$p)
                        continue;
                    $pNorm = preg_replace('/[^0-9+]/', '', $p);
                    if ($pNorm === $normalized || str_ends_with($pNorm, $normalized) || str_starts_with($pNorm, $normalized)) {
                        $result['matches'][] = [
                            'source' => 'account',
                            'record' => $rec,
                        ];
                    }
                }
            } catch (\Exception $e) {
                $result['account_error'] = $e->getMessage();
            }

            // 2) Search extension-level phone numbers for user's extension
            try {
                $extensionId = $ringCentralUser->extension_id;
                if ($extensionId) {
                    $resp2 = $this->platformGetWithRetry(
                        $platform,
                        $ringCentralUser,
                        '/restapi/v1.0/account/~/extension/' . $extensionId . '/phone-numbers'
                    );
                    $extPhonesPayload = $resp2->json();
                    $extPhones = data_get($extPhonesPayload, 'records', []);
                    foreach ($extPhones as $rec) {
                        $p = data_get($rec, 'phoneNumber');
                        if (!$p)
                            continue;
                        $pNorm = preg_replace('/[^0-9+]/', '', $p);
                        if ($pNorm === $normalized || str_ends_with($pNorm, $normalized) || str_starts_with($pNorm, $normalized)) {
                            $result['matches'][] = [
                                'source' => 'extension',
                                'record' => $rec,
                            ];
                        }
                    }
                }
            } catch (\Exception $e) {
                $result['extension_error'] = $e->getMessage();
            }

            // 3) Optionally, search all extensions for phone in contact.businessPhone
            try {
                $resp3 = $this->platformGetWithRetry(
                    $platform,
                    $ringCentralUser,
                    '/restapi/v1.0/account/~/extension'
                );
                $extensionsPayload = $resp3->json();
                $extensions = data_get($extensionsPayload, 'records', []);
                foreach ($extensions as $ext) {
                    $contact = data_get($ext, 'contact');
                    $bp = null;
                    if (is_array($contact) || is_object($contact)) {
                        $bp = data_get($contact, 'businessPhone') ?? data_get($contact, 'businessPhoneNumber');
                    }
                    if ($bp) {
                        $bpNorm = preg_replace('/[^0-9+]/', '', $bp);
                        if ($bpNorm === $normalized || str_ends_with($bpNorm, $normalized) || str_starts_with($bpNorm, $normalized)) {
                            $result['matches'][] = [
                                'source' => 'extension_contact',
                                'extension' => $ext,
                            ];
                        }
                    }
                }
            } catch (\Exception $e) {
                $result['extensions_error'] = $e->getMessage();
            }

            $result['success'] = true;
            $result['match_count'] = count($result['matches']);

            return $result;
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    /**
     * Logout user
     *
     * @param int $userId
     * @return bool
     */
    public function logout($userId)
    {
        try {
            RingCentralUser::where('user_id', $userId)->update(['is_active' => false]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    /**
     * Get voicemail list for user
     *
     * @param int $userId
     * @param array $filters
     * @return array
     */
    public function getVoicemails($userId, $filters = [])
    {
        try {
            VoicemailOnlyLog::info('Service voicemail sync started', [
                'user_id' => $userId,
                'filters' => $filters,
            ]);
            $ringCentralUser = RingCentralUser::where('user_id', $userId)->firstOrFail();
            $fetchAll = !empty($filters['fetchAll']);
            $monthsBack = $filters['monthsBack'] ?? null;
            $phoneNumber = (string) ($ringCentralUser->phone_number ?? '');
            $phoneDigits = preg_replace('/\D/', '', $phoneNumber);
            $sharedRingCentralUserIds = [$ringCentralUser->id];
            if ($phoneDigits !== '') {
                $sharedRingCentralUserIds = RingCentralUser::query()
                    ->whereNotNull('phone_number')
                    ->get()
                    ->filter(function ($user) use ($phoneDigits) {
                        $digits = preg_replace('/\D/', '', (string) ($user->phone_number ?? ''));
                        if ($digits === '') {
                            return false;
                        }
                        return $digits === $phoneDigits
                            || str_ends_with($digits, $phoneDigits)
                            || str_ends_with($phoneDigits, $digits);
                    })
                    ->pluck('id')
                    ->values()
                    ->all();
                if (empty($sharedRingCentralUserIds)) {
                    $sharedRingCentralUserIds = [$ringCentralUser->id];
                }
            }
            $localDbTotalBeforeSync = RingCentralVoicemail::query()
                ->whereIn('ringcentral_user_id', $sharedRingCentralUserIds)
                ->count();
            VoicemailOnlyLog::info('Service voicemail local baseline resolved', [
                'user_id' => $userId,
                'ringcentral_user_id' => $ringCentralUser->id,
                'phone_number' => $phoneNumber,
                'shared_ringcentral_user_ids' => $sharedRingCentralUserIds,
                'local_db_total_before_sync' => $localDbTotalBeforeSync,
                'fetch_all' => $fetchAll,
                'months_back' => $monthsBack,
            ]);


            $platform = $this->getPlatformWithAuth($ringCentralUser);
            if (!$platform) {
                VoicemailOnlyLog::warning('Service voicemail sync aborted: platform auth failed', [
                    'user_id' => $userId,
                    'phone_number' => $phoneNumber,
                ]);
                return [
                    'success' => false,
                    'message' => 'Failed to authenticate with RingCentral. Please reconnect.',
                ];
            }

            $queryParams = [
                'messageType' => 'VoiceMail',
                // Use perPage for message-store and keep count for backward compatibility.
                'perPage' => $filters['count'] ?? 100,
                'count' => $filters['count'] ?? 100,
            ];

            if (isset($filters['direction'])) {
                $queryParams['direction'] = $filters['direction'];
            }
            if (!empty($filters['dateFrom'])) {
                $queryParams['dateFrom'] = $filters['dateFrom'];
            } elseif (!empty($monthsBack)) {
                $queryParams['dateFrom'] = Carbon::now()
                    ->subMonths((int) $monthsBack)
                    ->startOfDay()
                    ->toIso8601String();
            } elseif (!$fetchAll) {
                // Provider defaults can be too narrow (e.g. recent 24h); force a wider default window.
                $queryParams['dateFrom'] = Carbon::now()
                    ->subDays(90)
                    ->startOfDay()
                    ->toIso8601String();
            }
            VoicemailOnlyLog::info('Service voicemail API query prepared', [
                'user_id' => $userId,
                'phone_number' => $phoneNumber,
                'query_params' => $queryParams,
            ]);


            $allVoicemails = [];
            $pagesFetched = 0;
            $page = 1;
            $nextPageUri = null;
            $perPage = (int) ($queryParams['perPage'] ?? 100);
            $seenPageSignatures = [];
            $apiTotalFetched = 0;

            do {
                VoicemailOnlyLog::info('Service voicemail page fetch started', [
                    'user_id' => $userId,
                    'page' => $page,
                    'via_next_page_uri' => !empty($nextPageUri),
                ]);
                if ($nextPageUri) {
                    $response = $this->platformGetWithRetry(
                        $platform,
                        $ringCentralUser,
                        $nextPageUri
                    );
                } else {
                    $requestParams = $queryParams;
                    if ($fetchAll) {
                        $requestParams['page'] = $page;
                    }
                    $response = $this->platformGetWithRetry(
                        $platform,
                        $ringCentralUser,
                        '/restapi/v1.0/account/~/extension/~/message-store',
                        $requestParams
                    );
                }

                $responseData = (array) $response->json();
                $voicemails = $responseData['records'] ?? [];
                $apiTotalFetched += count($voicemails);
                $pageSignature = count($voicemails) . ':' . $this->extractFirstRecordId($voicemails);
                $pagesFetched++;
                VoicemailOnlyLog::info('Service voicemail page fetched', [
                    'user_id' => $userId,
                    'page' => $page,
                    'records_in_page' => count($voicemails),
                    'api_total_fetched_so_far' => $apiTotalFetched,
                    'page_signature' => $pageSignature,
                ]);

                // Fallback: if empty and window is likely still too narrow, retry with a broader one-year window.
                if (!$fetchAll && empty($voicemails) && empty($filters['dateFrom']) && empty($monthsBack)) {
                    $fallbackParams = $queryParams;
                    $fallbackParams['dateFrom'] = Carbon::now()->subDays(365)->startOfDay()->toIso8601String();
                    VoicemailOnlyLog::info('Service voicemail fallback triggered', [
                        'user_id' => $userId,
                        'page' => $page,
                        'fallback_params' => $fallbackParams,
                    ]);

                    $fallbackResponse = $this->platformGetWithRetry(
                        $platform,
                        $ringCentralUser,
                        '/restapi/v1.0/account/~/extension/~/message-store',
                        $fallbackParams
                    );
                    $fallbackData = (array) $fallbackResponse->json();
                    $fallbackRecords = $fallbackData['records'] ?? [];
                    if (!empty($fallbackRecords)) {
                        $responseData = $fallbackData;
                        $voicemails = $fallbackRecords;
                        VoicemailOnlyLog::info('Service voicemail fallback returned records', [
                            'user_id' => $userId,
                            'page' => $page,
                            'fallback_records' => count($fallbackRecords),
                        ]);
                    }
                }

                $navigationNode = $this->normalizeRingCentralPayload($responseData['navigation'] ?? []);
                $nextPageNode = $this->normalizeRingCentralPayload($navigationNode['nextPage'] ?? []);

                // Store voicemails in database
                $storedCreated = 0;
                $storedUpdated = 0;
                $skippedMissingId = 0;
                foreach ($voicemails as $vm) {
                $vm = $this->normalizeRingCentralPayload($vm);
                $from = $this->normalizeRingCentralPayload($vm['from'] ?? []);
                $durationSeconds = $this->extractVoicemailDurationSeconds($vm);
                $voicemailId = $vm['id'] ?? null;
                $callerPhone = $this->extractRingCentralPhoneNumber($from) ?: $this->extractRingCentralPhoneNumber($vm);

                if (empty($voicemailId)) {
                    $skippedMissingId++;
                    continue;
                }

                $existingVoicemail = RingCentralVoicemail::where('voicemail_id', $voicemailId)->first();
                if ($durationSeconds <= 0 && $existingVoicemail && (int) $existingVoicemail->duration_seconds > 0) {
                    $durationSeconds = (int) $existingVoicemail->duration_seconds;
                }

                // Log voicemail data before updating or creating

                try {
                        $voicemailRecord = RingCentralVoicemail::updateOrCreate(
                            ['voicemail_id' => $voicemailId],
                            [
                                'ringcentral_user_id' => $ringCentralUser->id,
                            'phone_number' => $callerPhone,
                            // Never persist/display client names in portal voicemail flow.
                            'caller_name' => null,
                            'caller_number' => $callerPhone,
                            'duration_seconds' => $durationSeconds,
                            'transcription' => $vm['transcription'] ?? null,
                            'message_status' => $vm['readStatus'] ?? 'unread',
                            'received_at' => $vm['creationTime'] ?? now(),
                            ]
                    );
                    if ($voicemailRecord->wasRecentlyCreated) {
                        $storedCreated++;
                    } else {
                        $storedUpdated++;
                    }

                    // Fetch and store audio file if available
                    $attachments = $this->normalizeRingCentralPayload($vm['attachments'] ?? []);
                    if (!empty($attachments)) {
                        $existingAudioPath = $this->resolveExistingVoicemailAudioPath($voicemailRecord, $ringCentralUser);
                        if ($existingAudioPath) {
                            $this->ensureVoicemailAudioReference($voicemailRecord, $existingAudioPath);
                            continue;
                        }

                        foreach ($attachments as $attachmentRaw) {
                            $attachment = $this->normalizeRingCentralPayload($attachmentRaw);
                            $contentType = strtolower((string) ($attachment['contentType'] ?? ''));
                            $attachmentType = strtolower((string) ($attachment['type'] ?? ''));
                            $isAudio = str_contains($contentType, 'audio') || $attachmentType === 'audiorecording';

                            if ($isAudio && !empty($attachment['uri'])) {
                                $downloaded = $this->downloadVoicemailAudio($voicemailRecord, $attachment['uri'], $ringCentralUser);
                                if ($downloaded) {
                                    // One audio file per voicemail is enough.
                                    break;
                                }
                            }
                        }
                    }

                } catch (Exception $e) {
                    VoicemailOnlyLog::error('Service voicemail upsert failed for record', [
                        'user_id' => $userId,
                        'phone_number' => $phoneNumber,
                        'voicemail_id' => $voicemailId,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
                $allVoicemails = array_merge($allVoicemails, $voicemails);
                VoicemailOnlyLog::info('Service voicemail page processed', [
                    'user_id' => $userId,
                    'page' => $page,
                    'stored_created' => $storedCreated,
                    'stored_updated' => $storedUpdated,
                    'skipped_missing_id' => $skippedMissingId,
                    'accumulated_total' => count($allVoicemails),
                ]);

                if (!$fetchAll) {
                    break;
                }

                $nextPageUri = $responseData['nextPageUri'] ?? null;
                $navigation = $this->normalizeRingCentralPayload($responseData['navigation'] ?? []);
                $nextPageNav = $this->normalizeRingCentralPayload($navigation['nextPage'] ?? []);
                if (!$nextPageUri && !empty($nextPageNav['uri'])) {
                    $nextPageUri = $nextPageNav['uri'];
                }

                if (!$nextPageUri) {
                    $paging = isset($responseData['paging']) ? (array) $responseData['paging'] : [];
                    $currentPage = (int) ($paging['page'] ?? 0);
                    $totalPages = (int) ($paging['totalPages'] ?? 0);
                    if ($currentPage > 0 && $totalPages > 0 && $currentPage < $totalPages) {
                        $page++;
                        usleep(200000); // 200ms
                        continue;
                    }

                    // Fallback: some RC responses omit paging/next links; if page is full, try next page.
                    if ($fetchAll && count($voicemails) >= $perPage) {
                        if (isset($seenPageSignatures[$pageSignature])) {
                            VoicemailOnlyLog::warning('Service voicemail pagination stopped due to repeated signature', [
                                'user_id' => $userId,
                                'page' => $page,
                                'page_signature' => $pageSignature,
                            ]);
                            break;
                        }

                        $seenPageSignatures[$pageSignature] = true;
                        $page++;
                        VoicemailOnlyLog::info('Service voicemail pagination advanced by fallback', [
                            'user_id' => $userId,
                            'next_page' => $page,
                            'records_in_page' => count($voicemails),
                            'per_page' => $perPage,
                        ]);
                        usleep(200000); // 200ms
                    } else {
                        break;
                    }
                } else {
                    usleep(200000); // 200ms
                }
            } while ($fetchAll);

            $localDbTotalAfterSync = RingCentralVoicemail::query()
                ->whereIn('ringcentral_user_id', $sharedRingCentralUserIds)
                ->count();
            VoicemailOnlyLog::info('Service voicemail sync completed', [
                'user_id' => $userId,
                'phone_number' => $phoneNumber,
                'count' => count($allVoicemails),
                'total_synced' => count($allVoicemails),
                'pages_fetched' => $pagesFetched,
                'api_total_fetched' => $apiTotalFetched,
                'local_db_total_before_sync' => $localDbTotalBeforeSync,
                'local_db_total_after_sync' => $localDbTotalAfterSync,
            ]);

            return [
                'success' => true,
                'data' => $allVoicemails,
                'count' => count($allVoicemails),
                'total_synced' => count($allVoicemails),
                'pages_fetched' => $pagesFetched,
                'api_total_fetched' => $apiTotalFetched,
                'local_db_total_before_sync' => $localDbTotalBeforeSync,
                'local_db_total_after_sync' => $localDbTotalAfterSync,
            ];

        } catch (Exception $e) {
            VoicemailOnlyLog::error('Service voicemail sync failed', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'message' => 'Failed to get voicemails: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Normalize R-Dialer SDK payload nodes.
     * Handles stdClass values and array wrappers like ['stdClass' => [...]].
     *
     * @param mixed $node
     * @return array
     */
    private function normalizeRingCentralPayload($node): array
    {
        if (is_object($node)) {
            $node = (array) $node;
        }

        if (!is_array($node)) {
            return [];
        }

        if (array_key_exists('stdClass', $node) && count($node) === 1) {
            return $this->normalizeRingCentralPayload($node['stdClass']);
        }

        return $node;
    }

    /**
     * Safely extract first record id from mixed SDK payload arrays.
     *
     * @param array $records
     * @return string
     */
    private function extractFirstRecordId(array $records): string
    {
        if (empty($records)) {
            return '';
        }

        $first = $records[0] ?? null;
        $normalized = $this->normalizeRingCentralPayload($first);

        return trim((string) ($normalized['id'] ?? ''));
    }

    /**
     * Resolve voicemail duration seconds from top-level and attachment fields.
     *
     * @param array $vm
     * @return int
     */
    private function extractVoicemailDurationSeconds(array $vm): int
    {
        $topLevel = (int) ($vm['vmDuration'] ?? $vm['duration'] ?? $vm['duration_seconds'] ?? 0);
        if ($topLevel > 0) {
            return $topLevel;
        }

        $attachments = $this->normalizeRingCentralPayload($vm['attachments'] ?? []);
        foreach ($attachments as $attachmentRaw) {
            $attachment = $this->normalizeRingCentralPayload($attachmentRaw);
            $contentType = strtolower((string) ($attachment['contentType'] ?? ''));
            $attachmentType = strtolower((string) ($attachment['type'] ?? ''));
            $isAudio = str_contains($contentType, 'audio') || $attachmentType === 'audiorecording';
            if (!$isAudio) {
                continue;
            }

            $duration = (int) ($attachment['vmDuration'] ?? $attachment['duration'] ?? 0);
            if ($duration > 0) {
                return $duration;
            }
        }

        return 0;
    }

    /**
     * Resolve already-downloaded voicemail audio file path if present on disk.
     *
     * @param RingCentralVoicemail $voicemail
     * @param RingCentralUser $ringCentralUser
     * @return string|null
     */
    private function resolveExistingVoicemailAudioPath($voicemail, $ringCentralUser): ?string
    {
        $candidates = [];

        $audioFilePath = trim((string) ($voicemail->audio_file_path ?? ''));
        if ($audioFilePath !== '') {
            $candidates[] = ltrim($audioFilePath, '/');
        }

        $audioUrl = trim((string) ($voicemail->audio_url ?? ''));
        if ($audioUrl !== '' && str_starts_with($audioUrl, '/storage/')) {
            $candidates[] = ltrim(substr($audioUrl, strlen('/storage/')), '/');
        }

        $voicemailId = trim((string) ($voicemail->voicemail_id ?? ''));
        if ($voicemailId !== '') {
            $candidates[] = 'voicemails/' . $ringCentralUser->user_id . '/' . $voicemailId . '.wav';
            $candidates[] = 'voicemails/' . $voicemail->ringcentral_user_id . '/' . $voicemailId . '.wav';
        }

        foreach (array_unique(array_filter($candidates)) as $storagePath) {
            $fullPath = public_path('storage/' . ltrim($storagePath, '/'));
            if (is_file($fullPath) && filesize($fullPath) > 0) {
                return ltrim($storagePath, '/');
            }
        }

        return null;
    }

    /**
     * Extract a normalized phone number string from R-Dialer payload node.
     *
     * @param array $node
     * @return string|null
     */
    private function extractRingCentralPhoneNumber(array $node): ?string
    {
        $normalized = $this->normalizeRingCentralPayload($node);
        $candidates = [
            $normalized['phoneNumber'] ?? null,
            $normalized['phone_number'] ?? null,
            $normalized['callerNumber'] ?? null,
            $normalized['caller_number'] ?? null,
            $normalized['extensionNumber'] ?? null,
            $normalized['number'] ?? null,
            $normalized['value'] ?? null,
        ];

        foreach ($candidates as $value) {
            $text = trim((string) ($value ?? ''));
            if ($text !== '') {
                return $text;
            }
        }

        return null;
    }

    /**
     * Keep DB audio reference aligned with stored file.
     *
     * @param RingCentralVoicemail $voicemail
     * @param string $storagePath
     * @return void
     */
    private function ensureVoicemailAudioReference($voicemail, string $storagePath): void
    {
        $normalizedPath = ltrim($storagePath, '/');
        $targetUrl = '/storage/' . $normalizedPath;

        $needsUpdate = ($voicemail->audio_file_path !== $normalizedPath) || ($voicemail->audio_url !== $targetUrl);
        if (!$needsUpdate) {
            return;
        }

        $voicemail->update([
            'audio_file_path' => $normalizedPath,
            'audio_url' => $targetUrl,
        ]);
    }

    /**
     * Download and store voicemail audio file
     *
     * @param RingCentralVoicemail $voicemail
     * @param string $audioUrl
     * @return bool
     */
    private function downloadVoicemailAudio($voicemail, $audioUrl, $ringCentralUser)
    {
        $downloadLockKey = 'rc:voicemail-download:' . $voicemail->voicemail_id;
        $downloadLockAcquired = Cache::add($downloadLockKey, now()->timestamp, now()->addMinutes(2));
        if (!$downloadLockAcquired) {
            return true;
        }

        try {

            $alreadyStoredPath = $this->resolveExistingVoicemailAudioPath($voicemail, $ringCentralUser);
            if ($alreadyStoredPath) {
                $this->ensureVoicemailAudioReference($voicemail, $alreadyStoredPath);
                return true;
            }

            $storagePath = 'voicemails/' . $ringCentralUser->user_id . '/' . $voicemail->voicemail_id . '.wav';
            $fullPath = public_path('storage/' . $storagePath);
            if (is_file($fullPath) && filesize($fullPath) > 0) {
                $this->ensureVoicemailAudioReference($voicemail, $storagePath);
                return true;
            }

            // Fetch the R-Dialer user to get fresh token
            $ringCentralUser = RingCentralUser::where('user_id', $ringCentralUser->user_id)->firstOrFail();


            // Get decrypted access token
            $accessToken = decrypt($ringCentralUser->access_token);

            // Use Guzzle HTTP client to download audio with proper authorization
            $client = new \GuzzleHttp\Client();


            // Make the request with Authorization header
            $response = $client->get($audioUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => '*/*'
                ],
                'verify' => false, // For development only - remove in production
                'timeout' => 30
            ]);

            // Check if response is successful
            $statusCode = $response->getStatusCode();


            if ($statusCode === 200) {
                // Get audio content from response body
                $audioContent = (string) $response->getBody();

                // Validate we got audio data
                if (empty($audioContent)) {
                    return false;
                }

                // Create storage path
                $fullPath = public_path('storage/' . $storagePath);

                // Create directory if it doesn't exist
                $directory = dirname($fullPath);
                if (!is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }

                // Save the audio file
                $bytesWritten = file_put_contents($fullPath, $audioContent);

                if ($bytesWritten === false) {
                    return false;
                }

                // Update the voicemail record with the file path
                $voicemail->update([
                    'audio_file_path' => $storagePath,
                    'audio_url' => '/storage/' . $storagePath,
                    'duration_seconds' => $voicemail->duration_seconds ?? 0
                ]);


                return true;
            } else {
                return false;
            }

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return false;
        } catch (\Exception $e) {
            return false;
        } finally {
            Cache::forget($downloadLockKey);
        }
    }

    /**
     * Get single voicemail with audio
     *
     * @param int $userId
     * @param int $voicemailId
     * @return array
     */
    public function getVoicemail($userId, $voicemailId)
    {
        try {
            VoicemailOnlyLog::info('Service voicemail detail fetch started', [
                'user_id' => $userId,
                'voicemail_id' => $voicemailId,
            ]);
            $ringCentralUser = RingCentralUser::where('user_id', $userId)->firstOrFail();
            $sharedUserIds = $this->getSharedRingCentralUserIdsByPhone($ringCentralUser->phone_number, $ringCentralUser->id);
            $voicemail = RingCentralVoicemail::query()
                ->where('voicemail_id', $voicemailId)
                ->whereIn('ringcentral_user_id', $sharedUserIds)
                ->firstOrFail();

            // Mark as listened
            $voicemail->markAsListened();
            VoicemailOnlyLog::info('Service voicemail detail fetch completed', [
                'user_id' => $userId,
                'voicemail_id' => $voicemailId,
            ]);

            return [
                'success' => true,
                'data' => $voicemail,
            ];

        } catch (Exception $e) {
            VoicemailOnlyLog::error('Service voicemail detail fetch failed', [
                'user_id' => $userId,
                'voicemail_id' => $voicemailId,
                'error' => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'message' => 'Failed to get voicemail: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Delete voicemail
     *
     * @param int $userId
     * @param int $voicemailId
     * @return array
     */
    public function deleteVoicemail($userId, $voicemailId)
    {
        try {
            VoicemailOnlyLog::info('Service voicemail delete started', [
                'user_id' => $userId,
                'voicemail_id' => $voicemailId,
            ]);

            $ringCentralUser = RingCentralUser::where('user_id', $userId)->firstOrFail();
            $sharedUserIds = $this->getSharedRingCentralUserIdsByPhone($ringCentralUser->phone_number, $ringCentralUser->id);
            $voicemail = RingCentralVoicemail::query()
                ->where('voicemail_id', $voicemailId)
                ->whereIn('ringcentral_user_id', $sharedUserIds)
                ->firstOrFail();

            // Delete audio file
            $audioPath = ltrim((string) ($voicemail->audio_file_path ?? ''), '/');
            if ($audioPath !== '') {
                $publicStoragePath = public_path('storage/' . $audioPath);
                if (file_exists($publicStoragePath)) {
                    @unlink($publicStoragePath);
                }
            }

            // Delete voicemail record
            $voicemail->delete();
            VoicemailOnlyLog::info('Service voicemail delete completed', [
                'user_id' => $userId,
                'voicemail_id' => $voicemailId,
            ]);


            return [
                'success' => true,
                'message' => 'Voicemail deleted successfully',
            ];

        } catch (Exception $e) {
            VoicemailOnlyLog::error('Service voicemail delete failed', [
                'user_id' => $userId,
                'voicemail_id' => $voicemailId,
                'error' => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'message' => 'Failed to delete voicemail: ' . $e->getMessage(),
            ];
        }
    }

    private function getSharedRingCentralUserIdsByPhone($phoneNumber, ?int $fallbackId = null): array
    {
        $digits = preg_replace('/\D/', '', (string) $phoneNumber);
        if ($digits === '') {
            return $fallbackId ? [$fallbackId] : [];
        }

        $users = RingCentralUser::whereNotNull('phone_number')->get();
        $matching = $users->filter(function ($user) use ($digits) {
            $userDigits = preg_replace('/\D/', '', (string) $user->phone_number);
            if ($userDigits === '') {
                return false;
            }
            return $userDigits === $digits
                || str_ends_with($userDigits, $digits)
                || str_ends_with($digits, $userDigits);
        })->pluck('id')->values()->all();

        if (!empty($matching)) {
            return $matching;
        }

        return $fallbackId ? [$fallbackId] : [];
    }
    public function getCallRecordings($userId, $filters = [])
    {
        try {
            $ringCentralUser = RingCentralUser::where('user_id', $userId)->firstOrFail();
            $platform = $this->getPlatformWithAuth($ringCentralUser);

            if (!$platform) {
                return [
                    'success' => false,
                    'message' => 'Failed to authenticate with RingCentral. Please reconnect.',
                ];
            }

            $fetchAll = !empty($filters['fetchAll']);
            $phoneFilter = $filters['phoneNumber'] ?? null;
            $phoneFilterDigits = $phoneFilter ? preg_replace('/\D/', '', $phoneFilter) : null;
            $perPage = (int) ($filters['count'] ?? 100);
            $page = 1;
            $seenPageSignatures = [];

            $queryParams = [
                'detailedTelephonyState' => true,
                'showBlocked' => false,
                'perPage' => $perPage,
                // R-Dialer expects string literal for this query param (boolean can be rejected as invalid).
                'withRecording' => 'true',
            ];

            if (isset($filters['direction'])) {
                $queryParams['direction'] = $filters['direction'];
            }

            $processedCallLogs = [];
            $nextPageUri = null;

            do {
                if ($nextPageUri) {
                    $response = $this->platformGetWithRetry(
                        $platform,
                        $ringCentralUser,
                        $nextPageUri
                    );
                } else {
                    $requestParams = $queryParams;
                    if ($fetchAll) {
                        $requestParams['page'] = $page;
                    }
                    $response = $this->platformGetWithRetry(
                        $platform,
                        $ringCentralUser,
                        '/restapi/v1.0/account/~/extension/~/call-log',
                        $requestParams
                    );
                }

                $responseData = (array) $response->json();
                $callLogs = $responseData['records'] ?? [];

                if (!$fetchAll && empty($callLogs)) {
                    return [
                        'success' => true,
                        'message' => 'No call logs found.',
                        'data' => [],
                        'count' => 0,
                    ];
                }

                if (!empty($callLogs)) {
                    $processedCallLogs = array_merge(
                        $processedCallLogs,
                        $this->processCallLogs($callLogs, $ringCentralUser, $phoneFilterDigits)
                    );
                }

                if (!$fetchAll) {
                    break;
                }

                $nextPageUri = $responseData['nextPageUri'] ?? null;
                $navigation = isset($responseData['navigation']) ? (array) $responseData['navigation'] : [];
                $nextPageNav = isset($navigation['nextPage']) ? (array) $navigation['nextPage'] : [];
                if (!$nextPageUri && !empty($nextPageNav['uri'])) {
                    $nextPageUri = $nextPageNav['uri'];
                }

                if ($nextPageUri) {
                    usleep(200000); // 200ms
                    continue;
                }

                $paging = isset($responseData['paging']) ? (array) $responseData['paging'] : [];
                $currentPage = (int) ($paging['page'] ?? 0);
                $totalPages = (int) ($paging['totalPages'] ?? 0);
                if ($currentPage > 0 && $totalPages > 0 && $currentPage < $totalPages) {
                    $page++;
                    usleep(200000); // 200ms
                    continue;
                }

                if (count($callLogs) >= $perPage) {
                    $pageSignature = count($callLogs) . ':' . $this->extractFirstRecordId($callLogs);
                    if (isset($seenPageSignatures[$pageSignature])) {
                        break;
                    }

                    $seenPageSignatures[$pageSignature] = true;
                    $page++;
                    usleep(200000); // 200ms
                    continue;
                }

                break;
            } while ($fetchAll);

            // Return the first batch of call logs and the total count
            return [
                'success' => true,
                'data' => $processedCallLogs,
                'count' => count($processedCallLogs),
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to get call history: ' . $e->getMessage(),
            ];
        }
    }

    private function processCallLogs($callLogs, $ringCentralUser, $phoneFilterDigits = null)
    {
        $processedLogs = [];

        foreach ($callLogs as $log) {
            $logData = is_object($log) ? (array) $log : (array) $log;
            $callId = $logData['id'] ?? null;
            if (!$callId) {
                continue;
            }

            $fromRaw = $logData['from'] ?? [];
            $toRaw = $logData['to'] ?? [];
            if (is_object($fromRaw)) {
                $fromRaw = (array) $fromRaw;
            }
            if (is_object($toRaw)) {
                $toRaw = (array) $toRaw;
            }
            if (is_array($fromRaw) && isset($fromRaw[0]) && (is_array($fromRaw[0]) || is_object($fromRaw[0]))) {
                $fromRaw = (array) $fromRaw[0];
            }
            if (is_array($toRaw) && isset($toRaw[0]) && (is_array($toRaw[0]) || is_object($toRaw[0]))) {
                $toRaw = (array) $toRaw[0];
            }
            $from = is_array($fromRaw) ? $fromRaw : [];
            $to = is_array($toRaw) ? $toRaw : [];

            $fromNumber = $from['phoneNumber'] ?? ($from['phone'] ?? ($from['extensionNumber'] ?? null));
            $toNumber = $to['phoneNumber'] ?? ($to['phone'] ?? ($to['extensionNumber'] ?? null));

            if ($phoneFilterDigits) {
                $fromDigits = $fromNumber ? preg_replace('/\D/', '', $fromNumber) : '';
                $toDigits = $toNumber ? preg_replace('/\D/', '', $toNumber) : '';
                $matches = false;
                if ($fromDigits && ($fromDigits === $phoneFilterDigits || str_ends_with($fromDigits, $phoneFilterDigits) || str_ends_with($phoneFilterDigits, $fromDigits))) {
                    $matches = true;
                }
                if ($toDigits && ($toDigits === $phoneFilterDigits || str_ends_with($toDigits, $phoneFilterDigits) || str_ends_with($phoneFilterDigits, $toDigits))) {
                    $matches = true;
                }
                if (!$matches) {
                    continue;
                }
            }

            $direction = strtolower((string) ($logData['direction'] ?? ''));
            $legacyPhoneNumber = ($direction === 'outbound')
                ? ($toNumber ?? $fromNumber)
                : ($fromNumber ?? $toNumber);

            $recordingUrl = null;
            $recordingRaw = $logData['recording'] ?? null;
            if (is_object($recordingRaw)) {
                $recordingRaw = (array) $recordingRaw;
            }
            $contentUri = is_array($recordingRaw) ? ($recordingRaw['contentUri'] ?? null) : null;
            if ($contentUri) {
                $localFileName = $this->downloadCallRecording($contentUri, $ringCentralUser);
                if ($localFileName) {
                    $recordingUrl = '/storage/call-recordings/' . $ringCentralUser->user_id . '/' . $localFileName;
                }
            }

            $attributes = [
                'ringcentral_user_id' => $ringCentralUser->id,
                'from_number' => $fromNumber,
                'to_number' => $toNumber,
                'phone_number' => $legacyPhoneNumber,
                'direction' => $logData['direction'] ?? null,
                'type' => $logData['type'] ?? 'voice',
                'duration_seconds' => (int) ($logData['duration'] ?? 0),
                'status' => $this->normalizeCallLogStatus($logData['result'] ?? ($logData['status'] ?? null)),
                'call_started_at' => $logData['startTime'] ?? null,
                'call_ended_at' => $logData['endTime'] ?? null,
            ];
            if ($recordingUrl) {
                $attributes['recording_url'] = $recordingUrl;
            }

            $existing = RingCentralCallLog::where('call_id', $callId)->first();
            $isMissed = RingCentralCallLog::isMissedStatus($attributes['status'] ?? null);
            if ($existing) {
                $wasMissed = RingCentralCallLog::isMissedStatus($existing->status);
                $existing->fill($attributes);

                if (!$isMissed) {
                    $existing->is_seen = true;
                    if (!$existing->seen_at) {
                        $existing->seen_at = now();
                    }
                } elseif (!$wasMissed && $isMissed) {
                    $existing->is_seen = false;
                    $existing->seen_at = null;
                }

                $existing->save();
                $row = $existing;
            } else {
                $row = RingCentralCallLog::create(array_merge(
                    ['call_id' => $callId],
                    $attributes,
                    [
                        'recording_url' => $recordingUrl,
                        'is_seen' => $isMissed ? false : true,
                        'seen_at' => $isMissed ? null : now(),
                    ]
                ));
            }

            $processedLogs[] = [
                'id' => (string) $row->call_id,
                'call_id' => (string) $row->call_id,
                'direction' => $row->direction,
                'from' => $row->from_number ?: $row->phone_number,
                'to' => $row->to_number ?: $row->phone_number,
                'from_number' => $row->from_number,
                'to_number' => $row->to_number,
                'phone_number' => $row->phone_number,
                'recording_url' => $row->recording_url,
                'start_time' => optional($row->call_started_at ?: $row->created_at)->toIso8601String(),
                'duration_seconds' => (int) ($row->duration_seconds ?? 0),
                'status' => $row->status ?? 'Unknown',
            ];
        }

        return $processedLogs;
    }

    /**
     * Normalize provider-specific call results into UI-consistent status labels.
     */
    private function normalizeCallLogStatus($rawStatus): ?string
    {
        $status = trim((string) ($rawStatus ?? ''));
        if ($status === '') {
            return null;
        }

        $key = strtolower($status);
        $map = [
            'call connected' => 'Completed',
            'connected' => 'Completed',
            'completed' => 'Completed',
            'accepted' => 'Accepted',
            'missed' => 'Missed',
            'voicemail' => 'Voicemail',
            'busy' => 'Busy',
            'rejected' => 'Rejected',
            'blocked' => 'Blocked',
            'no answer' => 'NoAnswer',
            'noanswer' => 'NoAnswer',
            'call failed' => 'CallFailed',
            'callfailed' => 'CallFailed',
            'caller dropped' => 'CallerDropped',
            'callerdropped' => 'CallerDropped',
        ];

        return $map[$key] ?? $status;
    }

private function downloadCallRecording($audioUrl, $ringCentralUser)
{
    try {

        $accessToken = decrypt($ringCentralUser->access_token);

        // Extract the recording ID from the content URL
        $urlPath = parse_url($audioUrl, PHP_URL_PATH); // Extract path from the URL
        $pathParts = explode('/', $urlPath); // Split by "/"
        $recordingId = $pathParts[count($pathParts) - 2]; // The ID is the second-to-last part

        // Now create the file name
        $fileName = $recordingId . '_content'; // Append '_content' to the recording ID

        // Check if the recording already exists in storage
        $storagePath = 'call-recordings/' . $ringCentralUser->user_id . '/' . $fileName;
        $fullPath = public_path('storage/' . $storagePath);

        if (file_exists($fullPath)) {
            return $fileName;  // If it already exists, return the file name
        }

        $client = new \GuzzleHttp\Client();


        // Make the request with Authorization header
        $response = $client->get($audioUrl, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => '*/*'
            ],
            'verify' => false,
            'timeout' => 30
        ]);

        if ($response->getStatusCode() === 200) {
            $audioContent = (string) $response->getBody();

            if (empty($audioContent)) {
                return false;
            }

            // Create directory if it doesn't exist
            $directory = dirname($fullPath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            // Save the recording locally
            $bytesWritten = file_put_contents($fullPath, $audioContent);

            if ($bytesWritten === false) {
                return false;
            }


            return $fileName;  // Return the file name for use in the database
        } else {
            return false;
        }

    } catch (\Exception $e) {
        return false;
    }
}


}
