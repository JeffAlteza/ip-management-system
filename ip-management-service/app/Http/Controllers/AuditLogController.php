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
            return response()->json(['message' => $result['error']], $result['status']);
        }

        return response()->json($result['data'], $result['status']);
    }

    public function store(Request $request): JsonResponse
    {
        $log = $this->auditLogService->log(
            AuditLogDTO::from($request->all()),
        );

        return response()->json($log, 201);
    }
}
