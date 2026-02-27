<?php

namespace App\Http\Controllers;

use App\DTOs\AuditLogDTO;
use App\Services\AuditLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function __construct(
        protected AuditLogService $auditLogService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $result = $this->auditLogService->getLogs($request);

        if (isset($result['error'])) {
            return $this->error($result['error'], $result['status']);
        }

        return $this->success($result['data']);
    }

    public function store(Request $request): JsonResponse
    {
        $log = $this->auditLogService->log(
            AuditLogDTO::from($request->all()),
        );

        return $this->success($log, 201);
    }
}
