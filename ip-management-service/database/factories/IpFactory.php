<?php

namespace Database\Factories;

use App\Models\Ip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ip>
 */
class IpFactory extends Factory
{
    protected $model = Ip::class;

    public function definition(): array
    {
        return [
            'ip_address' => fake()->unique()->ipv4(),
            'label' => fake()->words(2, true),
            'comment' => fake()->optional()->sentence(),
            'created_by' => fake()->randomNumber(3),
        ];
    }
}
