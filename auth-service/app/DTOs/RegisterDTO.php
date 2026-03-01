<?php

namespace App\DTOs;

use App\Enums\Role;
use Spatie\LaravelData\Data;

class RegisterDTO extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $role = Role::User->value,
    ) {}
}
