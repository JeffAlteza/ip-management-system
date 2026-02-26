<?php

namespace App\Services;

use App\DTOs\AuditLogDTO;
use App\Enums\Role;
use App\Models\AuditLog;
use App\QueryBuilders\AuditLogQueryBuilder;
use Illuminate\Http\Request;

class AuditLogService
{
    public function __construct(
        protected AuditLogQueryBuilder $queryBuilder,
    ) {}

    public function getLogs(Request $request)
    {
        if ($request->auth_user_role !== Role::SuperAdmin->value) {
            return ['error' => 'Forbidden', 'status' => 403];
        }

        return [
            'data' => $this->queryBuilder->build()->paginate(25),
            'status' => 200,
        ];
    }

    public function log(AuditLogDTO $dto): AuditLog
    {
        return AuditLog::create($dto->toArray());
    }
}
