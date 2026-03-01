<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class UpdateIpDTO extends Data
{
    public function __construct(
        public string|Optional $ip_address,
        public string|Optional $label,
        public string|null|Optional $comment,
    ) {}
}
