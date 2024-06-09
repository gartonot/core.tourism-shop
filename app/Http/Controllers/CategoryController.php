<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index() {
        return Category::all();
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    public function store(CategoryRequest $request) {
        return Category::create($request->validated());
    }

    public function update(CategoryRequest $request, $id) {
        $item = Category::findOrFail($id);
        $item->fill($request->except(['id']));
        $item->save();
        return response()->json($item);
    }

    public function destroy(CategoryRequest $request, $id){
        $findedItem = Category::findOrFail($id);
        if($findedItem->delete()) {
            return response(null, 204);
        }
    }
}
