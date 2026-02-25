<?php

namespace App\Services;

use App\DTOs\LoginDTO;
use App\DTOs\RegisterDTO;
use App\DTOs\TokenDTO;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login(LoginDTO $loginDTO): ?TokenDTO
    {
        $token = Auth::attempt($loginDTO->toArray());

        if (! $token) {
            return null;
        }

        return $this->buildTokenDTO($token);
    }

    public function register(RegisterDTO $registerDTO): TokenDTO
    {
        $user = User::create([
            'name' => $registerDTO->name,
            'email' => $registerDTO->email,
            'password' => Hash::make($registerDTO->password),
            'role' => $registerDTO->role,
        ]);

        $token = Auth::login($user);

        return $this->buildTokenDTO($token);
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

    public function refresh(): TokenDTO
    {
        $token = Auth::refresh();

        return $this->buildTokenDTO($token);
    }

    public function logout(): void
    {
        Auth::logout();
    }

    protected function buildTokenDTO(string $token): TokenDTO
    {
        $user = Auth::user();
        $payload = Auth::payload();

        return new TokenDTO(
            accessToken: $token,
            tokenType: 'bearer',
            expiresIn: Auth::factory()->getTTL() * 60,
            sessionId: $payload->get('session_id'),
            user: [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        );
    }
}
