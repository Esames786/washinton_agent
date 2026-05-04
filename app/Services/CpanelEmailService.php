<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CpanelEmailService
{
    private string $host;
    private string $user;
    private ?string $token;
    private bool $verifySsl;

    public function __construct()
    {
        $this->host      = (string) config('mailbox.cpanel_host');
        $this->user      = (string) config('mailbox.cpanel_user');
        $this->token     = config('mailbox.cpanel_token') ?: null;
        $this->verifySsl = (bool) config('mailbox.cpanel_verify_ssl', true);
    }

    private function authReady(): bool
    {
        return !empty($this->host) && !empty($this->user) && !empty($this->token);
    }

    private function request(string $endpoint, array $params = []): array
    {
        if (!$this->authReady()) {
            return [
                'ok'      => false,
                'message' => 'cPanel token not configured. Please set CPANEL_HOST, CPANEL_USER, CPANEL_TOKEN in .env',
                'raw'     => null,
            ];
        }

        $url = "https://{$this->host}:2083/execute/{$endpoint}";

        try {
            $res = Http::withHeaders([
                'Authorization' => "cpanel {$this->user}:{$this->token}",
            ])
                ->withOptions(['verify' => $this->verifySsl])
                ->asForm()
                ->post($url, $params);

            if (!$res->ok()) {
                return [
                    'ok'      => false,
                    'message' => 'cPanel HTTP error: ' . $res->status(),
                    'raw'     => $res->body(),
                ];
            }

            $json = $res->json();
            $ok   = (int) ($json['status'] ?? 0) === 1;
            $msg  = $json['errors'][0] ?? ($json['messages'][0] ?? ($ok ? 'Success' : 'Unknown error'));

            return ['ok' => $ok, 'message' => $msg, 'raw' => $json];
        } catch (\Throwable $e) {
            return ['ok' => false, 'message' => 'cPanel request exception: ' . $e->getMessage(), 'raw' => null];
        }
    }

    public function createMailbox(string $email, string $password, string $domain, int $quotaMb = 0): array
    {
        return $this->request('Email/add_pop', [
            'email'    => $email,
            'password' => $password,
            'domain'   => $domain,
            'quota'    => $quotaMb,
        ]);
    }

    public function changePassword(string $email, string $password, string $domain): array
    {
        return $this->request('Email/passwd_pop', [
            'email'    => $email,
            'password' => $password,
            'domain'   => $domain,
        ]);
    }

    public function deleteMailbox(string $email): array
    {
        return $this->request('Email/delete_pop', ['email' => $email]);
    }

    public function suspendLogin(string $email): array
    {
        return $this->request('Email/suspend_login', ['email' => $email]);
    }

    public function unsuspendLogin(string $email): array
    {
        return $this->request('Email/unsuspend_login', ['email' => $email]);
    }
}
