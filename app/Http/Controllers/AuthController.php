<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignInRequest;
use App\Http\Requests\SignUpRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\TeamResource;
use App\Http\Resources\UserResource;
use App\Models\File;
use App\Models\User;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signUp(SignUpRequest $request)
    {


        $validated = $request->validated();
        $validated['avatar_id'] = File::generateAvatar();
        $user = User::create($validated);

        $token = TokenService::generateToken($user);
        $response = [
            'token' => $token,
            'user' => $user,
        ];
        return response()->json([
            'data' => new AuthResource($response),
            'message' => 'Вы успешно зарегистрировались',
        ], 201);
    }

    public function signIn(SignInRequest $request)
    {
        $credentials = $request->validated(); // email and password
        $user = User::where('email', $credentials['email'])->first();

        if ($user) {
            if (Hash::check($credentials['password'], $user->password)) {
                $token = TokenService::generateToken($user);
                $response = [
                    'token' => $token,
                    'user' => $user,
                ];
                return response()->json([
                    'data' => new AuthResource($response),

                    'message' => 'Вы вошли в аккаунт',
                ], 200);
            }
        }

        return response()->json(['message' => 'Неверная почта или пароль'], 401);
    }

    public function signOut(Request $request)
    {
        TokenService::deleteToken($request->user());

        return response()->json([
            'message' => 'Вы вышли из аккаунта',
        ], 200);
    }

    public function refresh(Request $request)
    {
        $user = $request->user();
        TokenService::deleteToken($user);
        $token = TokenService::generateToken($user);

        $response = [
            'token' => $token,
            'user' => $user,
        ];
        return response()->json([
            'data' => new AuthResource($response),
            'message' => 'Токен обновлен',
        ], 200);
    }
}
