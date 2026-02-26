<?php

namespace App\Services;

class AuthService extends InternalHttpClient
{
    public function login(array $credentials): array
    {
        $response = $this->request()
            ->post(
                $this->authUrl('/api/login'),
                $credentials
            );

        return [
            'status' => $response->status(), 
            'data' => $response->json()
        ];
    }

    public function register(array $data): array
    {
        $response = $this->request()
            ->post(
                $this->authUrl('/api/register'),
                $data
            );

        return [
            'status' => $response->status(), 
            'data' => $response->json()
        ];
    }

    public function validateToken(string $token): array
    {
        $response = $this->request()
            ->withToken($token)
            ->post($this->authUrl('/api/validate-token'));

        return [
            'status' => $response->status(), 
            'data' => $response->json()
        ];
    }

    public function refresh(string $token): array
    {
        $response = $this->request()
            ->withToken($token)
            ->post($this->authUrl('/api/refresh'));

        return [
            'status' => $response->status(), 
            'data' => $response->json()
        ];
    }

    public function logout(string $token): array
    {
        $response = $this->request()
            ->withToken($token)
            ->post($this->authUrl('/api/logout'));

        return [
            'status' => $response->status(), 
            'data' => $response->json()
        ];
    }
}
