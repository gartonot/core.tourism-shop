<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register(Request $request) {
        $request->validate([
            'login' => 'required',
            'password' => 'required'
        ]);

        $user = new User();

        $user->create([
            'login' => $request->login,
            'password' => bcrypt($request->password),
        ]);

        return response()->json([], 201);
    }
}
