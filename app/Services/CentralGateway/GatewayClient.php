<?php

namespace App\Services\CentralGateway;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;

class GatewayClient
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => (int) config('services.central_gateway.timeout', 30),
            'http_errors' => false,
        ]);
    }

    public function quote(array $payload): array
    {
        $base = rtrim((string) config('services.central_gateway.base'), '/');
        $path = '/api/v1/pricing/quote';
        $url  = $base . $path;

        $apiKey = (string) config('services.central_gateway.key');
        $secret = (string) config('services.central_gateway.secret');

        $timestamp = (string) now()->timestamp;
        $nonce = (string) Str::uuid();

        $method = 'POST';
        $bodyJson = json_encode($payload, JSON_UNESCAPED_SLASHES);

        $bodyHash  = hash('sha256', $bodyJson);
        $signature = hash_hmac('sha256', $method."\n".$path."\n".$timestamp."\n".$nonce."\n".$bodyHash, $secret);

        try {
            $res = $this->client->request('POST', $url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'WashingtonCRM/1.0',
                    'X-Api-Key' => $apiKey,
                    'X-Api-Timestamp' => $timestamp,
                    'X-Api-Nonce' => $nonce,
                    'X-Api-Signature' => $signature,
                ],
                'body' => $bodyJson,
            ]);
        } catch (GuzzleException $e) {
            return [
                'ok' => false,
                'status' => 0,
                'body' => ['message' => 'Gateway request failed', 'error' => $e->getMessage()],
            ];
        }

        $status = (int) $res->getStatusCode();
        $raw = (string) $res->getBody();
        $json = json_decode($raw, true);

        if ($status < 200 || $status >= 300) {
            return [
                'ok' => false,
                'status' => $status,
                'body' => $json ?? $raw,
            ];
        }

        return [
            'ok' => true,
            'status' => $status,
            'body' => $json ?? [],
        ];
    }
}
