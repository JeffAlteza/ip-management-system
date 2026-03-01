<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyInternalKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->header('X-Internal-Key');

        if ($key !== config('services.internal_key')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
