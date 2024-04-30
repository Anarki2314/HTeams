<?php

namespace App\Services;

use App\Models\User;

class TokenService
{
    public static function generateToken(User $user)
    {
        return $user->createToken('authToken', [$user->role->title])->plainTextToken;
    }

    public static function deleteToken(User $user)
    {
        $user->currentAccessToken()->delete();
    }
}