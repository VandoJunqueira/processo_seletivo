<?php

namespace App\Services\auth; // Corrigi o namespace para padrão Laravel

use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Sanctum\NewAccessToken;

class TokenService
{
    public function generateSecureToken(User $user): array
    {
        $prefix = config('sanctum.token_prefix', 'vj_');
        $plainTextToken = $prefix . Str::random(config('sanctum.token_length', 128));

        $accessToken = $user->createToken(
            name: 'secure_token',
            abilities: ['*'],
            expiresAt: now()->addSeconds(config('sanctum.expiration', 300))
        );

        $accessToken->accessToken->token = hash('sha256', $plainTextToken);
        $accessToken->accessToken->save();

        return [
            'access_token' => $plainTextToken,
            'expires_at' => $accessToken->accessToken->expires_at
        ];
    }
}
