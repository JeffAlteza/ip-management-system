<?php

namespace App\Http\Middleware;

use App\Services\AuthService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateToken
{
    public function __construct(
        protected AuthService $authService,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (! $token) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $result = $this->authService->validateToken($token);

        if ($result['status'] !== 200) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user = $result['data'];

        $request->merge([
            'auth_user_id' => $user['id'],
            'auth_user_role' => $user['role'],
            'auth_session_id' => $user['session_id'],
        ]);

        return $next($request);
    }
}
