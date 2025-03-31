<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\PersonalAccessToken;

class TokenExpirationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Se não houver token, continua
        if (!$request->bearerToken()) {
            return $next($request);
        }

        // Verifica o token
        $token = PersonalAccessToken::findToken($request->bearerToken());

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid token'
            ], 401);
        }

        // Token expirado
        if ($token->expires_at && $token->expires_at <= now()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Expired token',
                'error_code' => 'token_expired'
            ], 401);
        }

        // Renova token se estiver perto de expirar (últimos 5 minutos)
        if ($token->expires_at && $token->expires_at->diffInMinutes(now()) <= 5) {
            $newExpiration = now()->addMinutes(5);
            $token->update(['expires_at' => $newExpiration]);

            $response = $next($request);
            return $response->header('X-Token-Expires-At', $newExpiration->toIso8601String());
        }

        return $next($request);
    }
}
