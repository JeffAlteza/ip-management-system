<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class InternalHttpClient
{
    public function request(array $userContext = []): PendingRequest
    {
        $headers = [
            'X-Internal-Key' => config('services.internal_key'),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        if ($userContext) {
            $headers['X-User-Id'] = $userContext['id'];
            $headers['X-User-Role'] = $userContext['role'];
            $headers['X-Session-Id'] = $userContext['session_id'];
        }

        return Http::withHeaders($headers);
    }

    public function authUrl(string $path): string
    {
        return config('services.auth.url') . $path;
    }

    public function ipUrl(string $path): string
    {
        return config('services.ip.url') . $path;
    }
}
