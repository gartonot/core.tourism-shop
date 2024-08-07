<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
                'message' => 'Пользователь не найден',
                'code' => 400,
            ], 400);
        }

        $orderId = $user->id . random_int(10000, 99999);

        foreach ($request->products as $product) {
            $order = new Order();
            $order->order_id = $orderId;
            $order->user_id = $user->id;
            $order->product_id = $product['id'];
            $order->count = $product['counter'];
            $order->save();
        }

        return response()->json('ok');
    }

    public function formattedOrder($order) {
        $product = Product::findOrFail($order->product_id);
        $category = Category::findOrFail($product->categories_id);

        $formattedProduct = [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'category' => $category->name,
            'image_url' => Storage::url($product->image_url),
        ];

        // Названия статусов
        // created
        // inProgress
        // waitPay
        // done

        return [
            'id' => $order->id,
            'product' => $formattedProduct,
            'count' => $order->count,
            'status' => $order->status,
            'created_at' => $order->created_at,
        ];
    }

    public function groupOrderById($orders) {
        $formattedOrder = array();
        foreach($orders as $order) {
            $formattedOrder[$order['order_id']][] = $this->formattedOrder($order);
        }

        return $formattedOrder;
    }

    public function show(Request $request) {
        $request->validate([
            'sessionKey' => 'required|string',
        ]);

        $user = User::where('session_key', $request->sessionKey)->first();

        if(!$user) {
            return response()->json([
                'message' => 'Пользователь не найден',
                'code' => 400,
            ], 400);
        }

        $orders = Order::where('user_id', $user->id)->get();

        return response()->json($this->groupOrderById($orders));
    }

    public function orderById(Request $request, $orderId) {
        $request->validate([
            'sessionKey' => 'required|string',
        ]);

        $user = User::where('session_key', $request->sessionKey)->first();

        if(!$user) {
            return response()->json([
                'message' => 'Пользователь не найден',
                'code' => 400,
            ], 400);
        }

        $orders = Order::where('order_id', $orderId)->get();

        return response()->json($this->groupOrderById($orders));
    }

    public function deleteOrderById(Request $request, $orderId) {
        $request->validate([
            'sessionKey' => 'required|string',
        ]);

        $user = User::where('session_key', $request->sessionKey)->first();

        if(!$user) {
            return response()->json([
                'message' => 'Пользователь не найден',
                'code' => 400,
            ], 400);
        }

        $orders = Order::where('order_id', $orderId)->get();

        foreach ($orders as $order) {
            $order->delete();
        }

        return response()->json([], 204);
    }

    public function updateOrder(Request $request, $id) {
        $request->validate([
            'sessionKey' => 'required|string',
        ]);

        $user = User::where('session_key', $request->sessionKey)->first();

        if(!$user) {
            return response()->json([
                'message' => 'Пользователь не найден',
                'code' => 400,
            ], 400);
        }

        $orders = Order::where('order_id', $id)->get();


        foreach ($orders as $order) {
            $order->status = $request->status;
            $order->save();
        }

        return response()->json($orders);
    }
}
