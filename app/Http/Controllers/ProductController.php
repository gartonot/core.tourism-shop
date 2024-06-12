<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index() {
        $product = Product::get();

        $response = $product;

        foreach ($response as $productItem) {
            $productItem->image_url = Storage::url($productItem->image_url);
        }

        return $response;
    }

    public function show($id)
    {
      $product = Product::findOrFail($id);
      return response()->json($product);
    }

    public function store(ProductRequest $request) {
          $image = $request->file('image');
          $path = $image->store('products');

          $product = new Product();

          $product->name = $request->name;
          $product->description = $request->description;
          $product->price = $request->price;
          $product->image_url = $path;

          $product->save();

          $response = $product;
          $response->image_url = Storage::url($response->image_url);

          return response()->json($response, 201);
    }

    public function update(ProductRequest $request, $id) {
        $product = Product::findOrFail($id);
        $product->fill($request->except(['id']));
        $product->save();
        return response()->json($product);
    }

    public function destroy(ProductRequest $request, $id){
        $findedProduct = Product::findOrFail($id);
        Storage::delete($findedProduct->image_url);
        if($findedProduct->delete()) {
            return response(null, 204);
        }
    }


}
