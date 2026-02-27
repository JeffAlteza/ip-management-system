<?php

use App\Models\User;
use function Pest\Laravel\post;

it('registers a new user and returns token', function () {
    $payload = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    post('/api/register', $payload, internalHeaders())
        ->assertStatus(201)
        ->assertJsonStructure([
            'success',
            'data' => ['accessToken', 'user' => ['id', 'name', 'email', 'role']],
        ]);
});

it('validates required fields on register', function () {
    $payload = ['name' => 'John'];

    post('/api/register', $payload, internalHeaders())
        ->assertStatus(422);
});

it('logs in with valid credentials', function () {
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $payload = [
        'email' => 'test@example.com',
        'password' => 'password123',
    ];

    post('/api/login', $payload, internalHeaders())
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonStructure(['data' => ['accessToken', 'user']]);
});

it('fails login with wrong password', function () {
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $payload = [
        'email' => 'test@example.com',
        'password' => 'wrongpassword',
    ];

    post('/api/login', $payload, internalHeaders())
        ->assertStatus(401);
});

it('rejects requests without internal key', function () {
    $payload = [
        'email' => 'test@example.com',
        'password' => 'password123',
    ];

    post('/api/login', $payload, ['Accept' => 'application/json'])
        ->assertStatus(403);
});
