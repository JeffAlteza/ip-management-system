<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->extend(Tests\TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

function internalHeaders(array $extra = []): array
{
    return array_merge([
        'X-Internal-Key' => config('services.internal_key'),
        'Accept' => 'application/json',
    ], $extra);
}

function userHeaders(int $userId = 1, string $role = 'user'): array
{
    return internalHeaders([
        'X-User-Id' => $userId,
        'X-User-Role' => $role,
        'X-Session-Id' => 'test-session-123',
    ]);
}
