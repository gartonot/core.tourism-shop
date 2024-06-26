<?php

namespace App\Http\Controllers;

use App\Mail\User\SessionKeyMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

        return response()->json(null, 204);
    }

    public function authUserByEmail(Request $request) {
        $request->validate([
            'email' => 'required',
            'code' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user) {
            return response()->json([], 404);
        }

        if($user->email_code !== $request->code) {
            return response()->json([], 400);
        }

        $session_key = Str::random(64);

        $user->session_key = $session_key;
        $user->save();

        return response()->json([
            'session_key' => $session_key,
        ]);
    }
}
