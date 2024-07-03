<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create (Request $request) {
        $request->validate([
            'products' => 'required',
            'sessionKey' => 'required|string',
        ]);

        $user = User::where('session_key', $request->sessionKey)->first();

        if(!$user) {
            return response()->json([
                'message' => 'Пользователь не авторизован',
                'code' => 400,
            ], 400);
        }


        foreach ($request->products as $product) {
            $order = new Order();
            $order->user_id = $user->id;
            $order->product_id = $product['id'];
            $order->count = $product['counter'];
            $order->save();
        }

        return response()->json([], 204);
    }
}
