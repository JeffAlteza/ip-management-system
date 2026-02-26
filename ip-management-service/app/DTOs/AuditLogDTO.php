<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;

class AuditLogDTO extends Data
{
    public function __construct(
        public int $user_id,
        public string $action,
        public ?string $entity_type = null,
        public ?int $entity_id = null,
        public ?array $old_values = null,
        public ?array $new_values = null,
        public ?string $session_id = null,
        public ?string $ip_address_value = null,
    ) {}
}
