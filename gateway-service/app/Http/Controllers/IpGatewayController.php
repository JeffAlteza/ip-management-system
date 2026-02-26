<?php

namespace App\Http\Controllers;

use App\Services\IpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IpGatewayController extends Controller
{
    public function __construct(
        protected IpService $ipService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $result = $this->ipService->list($this->userContext($request));

        return response()->json($result['data'], $result['status']);
    }

    public function store(Request $request): JsonResponse
    {
        $result = $this->ipService->create(
            $request->only('ip_address', 'label'),
            $this->userContext($request),
        );

        return response()->json($result['data'], $result['status']);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $result = $this->ipService->update(
            $id,
            $request->only('ip_address', 'label'),
            $this->userContext($request),
        );

        return response()->json($result['data'], $result['status']);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $result = $this->ipService->delete($id, $this->userContext($request));

        return response()->json($result['data'], $result['status']);
    }

    private function userContext(Request $request): array
    {
        return [
            'id' => $request->auth_user_id,
            'role' => $request->auth_user_role,
            'session_id' => $request->auth_session_id,
        ];
    }
}
