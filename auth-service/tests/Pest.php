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
