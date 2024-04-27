<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignInRequest;
use App\Http\Requests\SignUpRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signUp(SignUpRequest $request)
    {


        $validated = $request->validated();
        $user = User::create($validated);

        $token = TokenService::generateToken($user);
        return response()->json([
            'data' => [
                'token' => $token,
                'user' => new UserResource($user),
            ],
            'message' => 'Вы успешно зарегистрировались',
        ], 201);
    }

    public function signIn(SignInRequest $request)
    {
        $credentials = $request->validated(); // email and password
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = TokenService::generateToken($user);
            return response()->json([
                'data' => [
                    'token' => $token,
                    'user' => new UserResource($user),
                ],
                'message' => 'Вы вошли в аккаунт',
            ], 200);
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
}
