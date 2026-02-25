<?php

namespace App\Http\Controllers;

use App\DTOs\LoginDTO;
use App\DTOs\RegisterDTO;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService,
    ) {}

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService
            ->login(
                LoginDTO::from($request->validated())
            );

        if (! $result) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json($result->toArray());
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService
            ->register(
                RegisterDTO::from($request->validated())
            );

        return response()->json($result->toArray(), 201);
    }

    public function validateToken(): JsonResponse
    {
        $result = $this->authService->validateToken();

        return response()->json($result);
    }

    public function refresh(): JsonResponse
    {
        $result = $this->authService->refresh();

        return response()->json($result->toArray());
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
