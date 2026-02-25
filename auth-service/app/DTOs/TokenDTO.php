<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;

class TokenDTO extends Data
{
    public function __construct(
        public string $accessToken,
        public string $tokenType,
        public int $expiresIn,
        public string $sessionId,
        public array $user,
    ) {}
}
