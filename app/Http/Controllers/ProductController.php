<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    public function index() {
        return Product::all();
    }

    public function show($id)
    {
      $product = Product::findOrFail($id);
      return response()->json($product);
    }

    public function store(ProductRequest $request) {
        return Product::create($request->validated());
    }

    public function update(ProductRequest $request, $id) {
        $product = Product::findOrFail($id);
        $product->fill($request->except(['id']));
        $product->save();
        return response()->json($product);
    }

    public function destroy(ProductRequest $request, $id){
        $findedProduct = Product::findOrFail($id);
        if($findedProduct->delete()) {
            return response(null, 204);
        }
    }


}
