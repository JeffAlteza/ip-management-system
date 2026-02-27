<?php

use Illuminate\Support\Facades\Http;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('proxies login to auth service', function () {
    Http::fake([
        '*/api/login' => Http::response([
            'success' => true,
            'message' => 'Success',
            'data' => [
                'accessToken' => 'fake-token',
                'user' => ['id' => 1, 'name' => 'John', 'email' => 'john@test.com', 'role' => 'user'],
                'expiresIn' => 3600,
                'sessionId' => 'session-123',
            ],
        ], 200),
        '*/api/audit-logs' => Http::response(['success' => true], 201),
    ]);

    $payload = [
        'email' => 'john@test.com',
        'password' => 'password',
    ];

    post('/api/login', $payload, ['Accept' => 'application/json'])
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.accessToken', 'fake-token');
});

it('proxies register to auth service', function () {
    Http::fake([
        '*/api/register' => Http::response([
            'success' => true,
            'message' => 'Success',
            'data' => [
                'accessToken' => 'fake-token',
                'user' => ['id' => 1, 'name' => 'John', 'email' => 'john@test.com', 'role' => 'user'],
                'expiresIn' => 3600,
                'sessionId' => 'session-123',
            ],
        ], 201),
    ]);

    $payload = [
        'name' => 'John',
        'email' => 'john@test.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    post('/api/register', $payload, ['Accept' => 'application/json'])
        ->assertStatus(201)
        ->assertJsonPath('success', true);
});

it('returns 401 for protected routes without token', function () {
    get('/api/ips', ['Accept' => 'application/json'])
        ->assertStatus(401);
});
