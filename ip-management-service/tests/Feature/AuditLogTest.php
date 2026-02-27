<?php

use App\Models\AuditLog;
use function Pest\Laravel\get;

it('allows super_admin to list audit logs', function () {
    AuditLog::factory(3)->create();

    get('/api/audit-logs', userHeaders(userId: 1, role: 'super_admin'))
        ->assertOk()
        ->assertJsonPath('success', true);
});

it('blocks regular user from listing audit logs', function () {
    get('/api/audit-logs', userHeaders(userId: 1, role: 'user'))
        ->assertStatus(403);
});
