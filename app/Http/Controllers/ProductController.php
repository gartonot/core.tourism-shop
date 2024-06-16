<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request) {
        $products = Product::get();

        if($request->input('category_id')) {
            $categoryId = $request->input('category_id');
            $products = Product::where('categories_id', $categoryId)->get();
        }

        $response = $products;

        foreach ($response as $productItem) {
            $productItem->image_url = Storage::url($productItem->image_url);
            $categoryName = Category::where('id', $productItem->categories_id)->get('name');
            $productItem->category_name = $categoryName[0]->name;
            unset($productItem->categories_id);
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
          $product->categories_id = $request->categories_id;
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
