<?php

use App\Models\Ip;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;
use function Pest\Laravel\delete;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

it('lists ips with pagination', function () {
    Ip::factory(5)->create();

    get('/api/ips', userHeaders())
        ->assertOk()
        ->assertJsonPath('success', true);
});

it('creates an ip with valid data', function () {
    $payload = [
        'ip_address' => '192.168.1.100',
        'label' => 'Test Server',
        'comment' => 'A test ip',
    ];

    post('/api/ips', $payload, userHeaders())
        ->assertStatus(201)
        ->assertJsonPath('success', true);

    assertDatabaseHas('ips', [
        'ip_address' => '192.168.1.100',
        'label' => 'Test Server',
    ]);
});

it('validates ip_address format', function () {
    $payload = [
        'ip_address' => 'not-an-ip',
        'label' => 'Bad IP',
    ];

    post('/api/ips', $payload, userHeaders())
        ->assertUnprocessable();
});

it('rejects duplicate ip_address', function () {
    Ip::factory()->create(['ip_address' => '10.0.0.1']);

    $payload = [
        'ip_address' => '10.0.0.1',
        'label' => 'Duplicate',
    ];

    post('/api/ips', $payload, userHeaders())
        ->assertUnprocessable();
});

it('allows owner to update their ip', function () {
    $ip = Ip::factory()->create(['created_by' => 1]);

    $payload = ['label' => 'Updated Label'];

    put("/api/ips/{$ip->id}", $payload, userHeaders(userId: 1))
        ->assertOk()
        ->assertJsonPath('success', true);

    assertDatabaseHas('ips', ['id' => $ip->id, 'label' => 'Updated Label']);
});

it('blocks regular user from updating another users ip', function () {
    $ip = Ip::factory()->create(['created_by' => 99]);

    $payload = ['label' => 'Hacked'];

    put("/api/ips/{$ip->id}", $payload, userHeaders(userId: 1, role: 'user'))
        ->assertStatus(403);
});

it('allows super_admin to update any ip', function () {
    $ip = Ip::factory()->create(['created_by' => 99]);

    $payload = ['label' => 'Admin Updated'];

    put("/api/ips/{$ip->id}", $payload, userHeaders(userId: 1, role: 'super_admin'))
        ->assertOk()
        ->assertJsonPath('success', true);
});

it('blocks regular user from deleting', function () {
    $ip = Ip::factory()->create(['created_by' => 1]);

    delete("/api/ips/{$ip->id}", [], userHeaders(userId: 1, role: 'user'))
        ->assertStatus(403);
});

it('allows super_admin to delete', function () {
    $ip = Ip::factory()->create();

    delete("/api/ips/{$ip->id}", [], userHeaders(userId: 1, role: 'super_admin'))
        ->assertOk()
        ->assertJsonPath('success', true);

    assertDatabaseMissing('ips', ['id' => $ip->id]);
});
