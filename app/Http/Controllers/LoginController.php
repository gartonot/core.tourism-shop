<?php

namespace App\Http\Controllers;

use App\Mail\User\SessionKeyMail;
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

        $email_code = '1234';

        Mail::to($request->email)->send(new SessionKeyMail($email_code));
    }
}
