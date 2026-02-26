<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthGatewayController extends Controller
{
    public function __construct(
        protected AuthService $authService,
    ) {}

    public function login(Request $request): JsonResponse
    {
        $result = $this->authService
            ->login(
                $request->only('email', 'password')
            );

        return response()->json($result['data'], $result['status']);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService
            ->register(
                $request->only('name', 'email', 'password', 'password_confirmation')
            );

        return response()->json($result['data'], $result['status']);
    }

    public function refresh(Request $request): JsonResponse
    {
        $result = $this->authService
            ->refresh($request->bearerToken());

        return response()->json($result['data'], $result['status']);
    }

    public function logout(Request $request): JsonResponse
    {
        $result = $this->authService
            ->logout($request->bearerToken());

        return response()->json($result['data'], $result['status']);
    }
}
