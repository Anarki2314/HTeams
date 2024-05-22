<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {

        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return response()->json(['message' => 'Ссылка для сброса пароля отправлена'], 200);
    }

    public function resetPassword(Request $request)
    {
        $request->validate(
            [
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|confirmed|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/',

            ],
            [
                'password.required' => 'Поле `Пароль` обязательно.',
                'password.regex' => 'Пароль должен содержать цифры, строчные и заглавные буквы.',
                'password.confirmed' => 'Поле `Повтор пароля` должно совпадать с полем `Пароль`.',
                'password.min' => 'Поле `Пароль` должно содержать не менее 8 символов.',
            ]
        );

        if (Hash::check($request->password, User::where('email', $request->email)->first()->password)) {

            return response()->json(['message' => 'Новый пароль должен отличаться от старого.'], 400);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {

                $user->forceFill([
                    'password' => $password
                ]);

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return response()->json(['message' => __($status)], ($status == Password::INVALID_TOKEN) ? 400 : 200);
    }
    //
}
