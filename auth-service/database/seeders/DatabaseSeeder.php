<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->superAdmin()->create([
            'name' => 'Super Admin',
            'email' => 'super_admin@example.com',
        ]);

        User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
        ]);
    }
}
