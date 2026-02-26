<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;

class UpdateIpDTO extends Data
{
    public function __construct(
        public string $label,
    ) {}
}
