<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;

class StoreIpDTO extends Data
{
    public function __construct(
        public string $ip_address,
        public string $label,
        public ?string $comment = null,
    ) {}
}
