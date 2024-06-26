<?php

namespace App\Http\Controllers;

use App\Mail\User\SessionKeyMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public function login(Request $request) {
        $credentials = $request->validate([
            'login' => 'required',
            'password' => 'required'
        ]);

        if(Auth::attempt($credentials)) {
            return response()->json(['token' => config('apitoken')]);
        }

        return response()->json(['message' => 'Неверный логин или пароль']);
    }

    public function authSessionKey(Request $request) {
        $request->validate([
            'email' => 'required',
        ]);

        $email_code = random_int(1000, 9999);

        // Отправляем код на почту
        Mail::to($request->email)->send(new SessionKeyMail($email_code));

        // Сохраняем код в базу пользователя
        $user = User::where('email', $request->email)->first();

        if(!$user) {
            $user = new User();
            $user->email = $request->email;
            $user->email_code = $email_code;
            $user->save();
        }

        $user->email_code = $email_code;
        $user->save();
    }
}
