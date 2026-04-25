<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Requests\Admin\AdminCategoryCreateRequest;
use App\Http\Requests\Admin\AdminCategoryUpdateRequest;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Models\Category;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(15);

        return response()->json($categories);
    }

    public function create(AdminCategoryCreateRequest $request)
    {
        try{
            $category_data = $request->validated();
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

    public function update($id, AdminCategoryUpdateRequest $request)
    {
        try{
            $category_data = $request->validated();
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
