<?php

namespace App\Http\Controllers\AdminControllers;

use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(15);

        return response()->json($categories);
    }

    public function create(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'category_description' => 'string|max:255',
            'alias' => 'required|string|max:255',
        ]);

        try{
            $category_data = $request->toArray();
            Category::create($category_data);
        }catch (\Exception $e){
            return response()->json(['error' => 'Creation of category failed'], 500);
        }

        return response()->json(['status' => 'success', 'message' => 'Category created successfully'],201);
    }

    public function show($id)
    {
        try{
            $category = Category::findOrFail(['id' => $id]);
            Cache::put('category_'.$id, $category, 60);

            return response()->json($category);
        }catch (\Exception $e){
            return response()->json(['error' => 'Category not found'], 404);
        }
    }

    public function update($id, Request $request)
    {
        try{
            $category_data = $request->toArray();
            $category = Category::whereId($id);

            $category->update($category_data);

            if(Cache::has('category_'.$id)){
                Cache::forget('category_'.$id);
            }
        }catch (\Exception $e){
            return response()->json(['error' => 'Category not found'], 404);
        }

        return response()->json(['status' => 'success', 'message' => 'Category updated successfully'],201);
    }

    public function destroy($id)
    {
        try {
            $category = Category::whereId($id);
            $category->delete();
        }catch (\Exception $e){
            return response()->json(['error' => 'Category not found'], 404);
        }

        return response()->json(['status' => 'success', 'message' => 'Category deleted successfully'],200);
    }
}
