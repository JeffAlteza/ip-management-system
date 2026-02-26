<?php

namespace Database\Factories;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AuditLog>
 */
class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    public function definition(): array
    {
        return [
            'user_id' => fake()->randomNumber(3),
            'action' => fake()->randomElement(['created', 'updated', 'deleted']),
            'entity_type' => 'ip',
            'entity_id' => fake()->randomNumber(3),
            'old_values' => null,
            'new_values' => ['ip_address' => fake()->ipv4()],
            'session_id' => fake()->uuid(),
            'ip_address_value' => fake()->ipv4(),
        ];
    }
}
