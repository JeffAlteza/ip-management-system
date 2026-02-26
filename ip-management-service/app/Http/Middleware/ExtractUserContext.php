<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExtractUserContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->header('X-User-Id');
        $role = $request->header('X-User-Role');
        $sessionId = $request->header('X-Session-Id');

        if (! $userId || ! $role) {
            return response()->json(['message' => 'Missing user context'], 400);
        }

        $request->merge([
            'auth_user_id' => (int) $userId,
            'auth_user_role' => $role,
            'auth_session_id' => $sessionId,
        ]);

        return $next($request);
    }
}
