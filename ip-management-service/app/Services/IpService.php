<?php

namespace App\Services;

use App\DTOs\AuditLogDTO;
use App\DTOs\StoreIpDTO;
use App\DTOs\UpdateIpDTO;
use App\Enums\Role;
use App\Models\Ip;
use App\QueryBuilders\IpQueryBuilder;
use Illuminate\Http\Request;

class IpService
{
    public function __construct(
        protected AuditLogService $auditLogService,
        protected IpQueryBuilder $queryBuilder,
    ) {}

    public function list()
    {
        return $this->queryBuilder->build()->paginate(20);
    }

    public function create(StoreIpDTO $dto, Request $request): array
    {
        $ip = Ip::create([
            ...$dto->toArray(),
            'created_by' => $request->auth_user_id,
        ]);

        $this->auditLogService->log(new AuditLogDTO(
            user_id: $request->auth_user_id,
            action: 'ip_created',
            entity_type: 'ip',
            entity_id: $ip->id,
            new_values: $ip->toArray(),
            session_id: $request->auth_session_id,
        ));

        return ['data' => $ip, 'status' => 201];
    }

    public function update(Ip $ip, UpdateIpDTO $dto, Request $request): array
    {
        if ($request->auth_user_role !== Role::SuperAdmin->value && $ip->created_by !== $request->auth_user_id) {
            return ['error' => 'Forbidden', 'status' => 403];
        }

        $oldValues = $ip->toArray();

        $ip->update($dto->toArray());

        $this->auditLogService->log(new AuditLogDTO(
            user_id: $request->auth_user_id,
            action: 'ip_updated',
            entity_type: 'ip',
            entity_id: $ip->id,
            old_values: $oldValues,
            new_values: $ip->fresh()->toArray(),
            session_id: $request->auth_session_id,
        ));

        return ['data' => $ip->fresh(), 'status' => 200];
    }

    public function delete(Ip $ip, Request $request): array
    {
        if ($request->auth_user_role !== Role::SuperAdmin->value) {
            return ['error' => 'Forbidden', 'status' => 403];
        }

        $oldValues = $ip->toArray();

        $ipId = $ip->id;
        
        $ip->delete();

        $this->auditLogService->log(new AuditLogDTO(
            user_id: $request->auth_user_id,
            action: 'ip_deleted',
            entity_type: 'ip',
            entity_id: $ipId,
            old_values: $oldValues,
            session_id: $request->auth_session_id,
        ));

        return ['data' => null, 'status' => 200];
    }
}
