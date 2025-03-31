<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandleApiTokenErrors
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Aplica apenas se:
        // 1. For rota API OU espera JSON
        // 2. E retornou status 401
        if (($request->is('api/*') || $request->expectsJson()) &&
            $response->status() === 401
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'Acesso não autorizado',
                'error_code' => 'Unauthorized'
            ], 401);
        }

        return $response;
    }
}
