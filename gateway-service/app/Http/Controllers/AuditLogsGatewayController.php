<?php

namespace App\Http\Controllers;

use App\Services\IpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuditLogsGatewayController extends Controller
{
    public function __construct(
        protected IpService $ipService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $result = $this->ipService->getAuditLogs(
            $request->query(),
            [
                'id' => $request->auth_user_id,
                'role' => $request->auth_user_role,
                'session_id' => $request->auth_session_id,
            ],
        );

        return response()->json($result['data'], $result['status']);
    }
}
