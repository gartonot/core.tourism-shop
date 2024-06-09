<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request) {
        $credentials = $request->validate([
            'login' => 'required',
            'password' => 'required'
        ]);

        if(Auth::attempt($credentials)) {
            return response()->json(['token' => config('apitokens')]);
        }

        return response()->json(['message' => 'Неверный логин или пароль']);
    }
}
