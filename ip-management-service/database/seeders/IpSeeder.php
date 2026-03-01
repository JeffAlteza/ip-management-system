<?php

namespace Database\Seeders;

use App\Models\Ip;
use Illuminate\Database\Seeder;

class IpSeeder extends Seeder
{
    public function run(): void
    {
        Ip::factory(10)->create();
    }
}
