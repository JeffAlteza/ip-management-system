<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login(array $credentials): ?array
    {
        $token = Auth::attempt($credentials);

        if (! $token) {
            return null;
        }

        return $this->respondWithToken($token);
    }

    public function register(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'user',
        ]);

        $token = Auth::login($user);

        return $this->respondWithToken($token);
    }

    public function validateToken(): array
    {
        $user = Auth::user();
        $payload = Auth::payload();

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'session_id' => $payload->get('session_id'),
        ];
    }

    public function refresh(): array
    {
        $token = Auth::refresh();

        return $this->respondWithToken($token);
    }

    public function logout(): void
    {
        Auth::logout();
    }

    protected function respondWithToken(string $token): array
    {
        $user = Auth::user();
        $payload = Auth::payload();

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'session_id' => $payload->get('session_id'),
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ];
    }
}
