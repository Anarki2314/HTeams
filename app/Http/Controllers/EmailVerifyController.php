<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class EmailVerifyController extends Controller
{
    public function verify(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();

            event(new Verified($user));
            return view('welcome');
        }

        return redirect('/');
    }
    public function send(Request $request)
    {
        if (!$request->user()->hasVerifiedEmail()) {
            $request->user()->sendEmailVerificationNotification();

            return response()->json([
                'message' => 'Ссылка для подтверждения почты отправлена'
            ]);
        }
        return response()->json([
            'message' => 'Почта уже подтверждена'
        ]);
    }
}
