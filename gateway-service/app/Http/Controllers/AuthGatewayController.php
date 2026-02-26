<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use App\Services\IpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthGatewayController extends Controller
{
    public function __construct(
        protected AuthService $authService,
        protected IpService $ipService,
    ) {}

    public function login(Request $request): JsonResponse
    {
        $result = $this->authService->login(
            $request->only('email', 'password'),
        );

        if ($result['status'] === 200) {
            $this->ipService->logAuditEvent([
                'user_id' => $result['data']['user']['id'],
                'action' => 'user_login',
                'entity_type' => 'user',
                'entity_id' => $result['data']['user']['id'],
                'session_id' => $result['data']['session_id'],
            ]);
        }

        return response()->json($result['data'], $result['status']);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register(
            $request->validated(),
        );

        return response()->json($result['data'], $result['status']);
    }

    public function refresh(Request $request): JsonResponse
    {
        $result = $this->authService->refresh($request->bearerToken());

        return response()->json($result['data'], $result['status']);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->ipService->logAuditEvent([
            'user_id' => $request->auth_user_id,
            'action' => 'user_logout',
            'entity_type' => 'user',
            'entity_id' => $request->auth_user_id,
            'session_id' => $request->auth_session_id,
        ]);

        $result = $this->authService->logout($request->bearerToken());

        return response()->json($result['data'], $result['status']);
    }
}
