<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Services\auth\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function authenticate(AuthRequest $request)
    {
        try {
            if (!Auth::attempt($request->validated())) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $token = app(TokenService::class)->generateSecureToken(auth()->user());

            return response()->json(array_merge([
                'token_type' => 'Bearer',
                'expires_in' => config('sanctum.expiration'),
                'issued_at' => now()
            ], $token));
        } catch (\Throwable $th) {
            print_r($th);
        }
    }

    public function login()
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid token'
        ], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logout realizado com sucesso']);
    }
}
