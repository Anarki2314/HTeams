<?php

namespace App\Services;

use App\Models\User;

class TokenService
{
    public static function generateToken(User $user)
    {
        return $user->createToken('authToken', [$user->role->title], now()->addMinutes(30))->plainTextToken;
    }

    public static function deleteToken(User $user)
    {
        $user->tokens()->where('expires_at', '<=', now())->where('name', 'authToken')->delete();
    }
}
