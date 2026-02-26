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
        $result = $this->authService->login(
            LoginDTO::from($request->validated()),
        );

        if (! $result) {
            return $this->error('Invalid credentials', 401);
        }

        return $this->success($result->toArray());
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register(
            RegisterDTO::from($request->validated()),
        );

        return $this->success($result->toArray(), 201);
    }

    public function validateToken(): JsonResponse
    {
        return $this->success($this->authService->validateToken());
    }

    public function refresh(): JsonResponse
    {
        $result = $this->authService->refresh();

        return $this->success($result->toArray());
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return $this->success(message: 'Successfully logged out');
    }
}
