<?php

namespace App\Services;

class IpService extends InternalHttpClient
{
    public function list(array $userContext): array
    {
        $response = $this->request($userContext)
            ->get($this->ipUrl('/api/ips'));

        return ['status' => $response->status(), 'data' => $response->json()];
    }

    public function create(array $data, array $userContext): array
    {
        $response = $this->request($userContext)
            ->post($this->ipUrl('/api/ips'), $data);

        return ['status' => $response->status(), 'data' => $response->json()];
    }

    public function update(int $id, array $data, array $userContext): array
    {
        $response = $this->request($userContext)
            ->put($this->ipUrl("/api/ips/{$id}"), $data);

        return ['status' => $response->status(), 'data' => $response->json()];
    }

    public function delete(int $id, array $userContext): array
    {
        $response = $this->request($userContext)
            ->delete($this->ipUrl("/api/ips/{$id}"));

        return ['status' => $response->status(), 'data' => $response->json()];
    }

    public function getAuditLogs(array $filters, array $userContext): array
    {
        $response = $this->request($userContext)
            ->get($this->ipUrl('/api/audit-logs'), $filters);

        return ['status' => $response->status(), 'data' => $response->json()];
    }

    public function logAuditEvent(array $data): void
    {
        try {
            $this->request()->post($this->ipUrl('/api/audit-logs'), $data);
        } catch (\Throwable) {
            // Don't fail if audit logging fails
        }
    }
}
