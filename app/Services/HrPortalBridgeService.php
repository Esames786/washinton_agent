<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class HrPortalBridgeService
{
    /**
     * Request SSO login redirect URL for a Washington user.
     * Passes user.id as agent_id to the HR portal.
     *
     * @param  int     $userId      Washington user.id
     * @param  string  $redirectTo  'dashboard' or 'profile'
     * @return array  ['redirect_url' => '...']
     * @throws RuntimeException
     */
    public function login(int $userId, string $redirectTo = 'dashboard'): array
    {
        return $this->post(
            (string) config('bridge.hrportal.agent_login_endpoint', '/bridge/agent/login'),
            ['agent_id' => $userId, 'redirect_to' => $redirectTo],
            'Unable to get HR portal login URL.'
        );
    }

    /**
     * Check HR portal employee status for a Washington user.
     *
     * @param  int  $userId  Washington user.id
     * @return array  ['linked' => bool, 'active' => bool, ...]
     * @throws RuntimeException
     */
    public function status(int $userId): array
    {
        return $this->post(
            (string) config('bridge.hrportal.agent_status_endpoint', '/bridge/agent/status'),
            ['agent_id' => $userId],
            'Unable to check HR portal employee status.'
        );
    }

    /**
     * Create / mirror an employee on the HR portal after signup.
     *
     * @param  array  $data  Must include: name, email, password, agent_id, user_type
     *                       Optional: phone, address, country, shift_type_id, account_type_id
     * @return array
     * @throws RuntimeException
     */
    public function createEmployee(array $data): array
    {
        return $this->post(
            (string) config('bridge.hrportal.create_employee_endpoint', '/bridge/employee/create'),
            $data,
            'Unable to create employee on HR portal.'
        );
    }

    /**
     * Get a one-time admin SSO URL to view an HR employee profile.
     *
     * @param  int  $hrEmployeeId  The hr_employees.id
     * @return array  ['redirect_url' => '...']
     * @throws RuntimeException
     */
    public function adminEmployeeView(int $hrEmployeeId): array
    {
        return $this->post(
            '/bridge/admin/employee-view',
            ['employee_id' => $hrEmployeeId],
            'Unable to get HR admin view URL.'
        );
    }

    /**
     * Shared HTTP POST logic for all HR portal bridge calls.
     */
    protected function post(string $endpoint, array $payload, string $fallbackMessage): array
    {
        $baseUrl   = rtrim((string) config('bridge.hrportal.base_url'), '/');
        $sharedKey = (string) config('bridge.hrportal.shared_key');

        if (blank($baseUrl)) {
            throw new RuntimeException('HR portal bridge is not configured: HRPORTAL_BASE_URL is missing.');
        }

        if (blank($sharedKey)) {
            throw new RuntimeException('HR portal bridge is not configured: HRPORTAL_SHARED_KEY is missing.');
        }

        $endpoint = '/' . ltrim($endpoint, '/');

        try {
            $response = Http::acceptJson()
                ->asJson()
                ->withHeaders([
                    'X-Bridge-Key'    => $sharedKey,
                    'X-Bridge-Client' => 'washington',
                ])
                ->timeout(20)
                ->post($baseUrl . $endpoint, $payload);
        } catch (ConnectionException $e) {
            throw new RuntimeException('HR portal is not reachable right now. Please try again later.');
        }

        $data = $response->json() ?: [];

        if ($response->failed()) {
            $message = Arr::get($data, 'message', $fallbackMessage);
            $errors  = Arr::get($data, 'errors', []);

            throw new RuntimeException(
                ($message ?: $fallbackMessage) .
                ($errors ? ' ' . json_encode($errors) : '')
            );
        }

        return is_array($data) ? $data : [];
    }
}
