<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 200,
            'data' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ], 400);
        }

        $category = Category::create([
            'name' => $request->name,
            'status' => (int) $request->status
        ]);

        return response()->json([
            'status' => 200,
            'data' => $category,
            'message' => 'Category created successfully'
        ], 200);
    }

    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);

            if (!$category) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Category not found'
                ], 404);
            }

            return response()->json([
                'status' => 200,
                'data' => $category
            ], 200);
        } 
        catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update($id, Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $category = Category::findOrFail($id);

            if (!$category) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Category not found'
                ], 404);
            }

            $category->update([
                'name' => $request->name,
                'status' => $request->status
            ]);

            return response()->json([
                'status' => 200,
                'data' => $category,
                'message' => 'Category updated successfully'
            ], 200);
        } 
        catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);

            if (!$category) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Category not found'
                ], 404);
            }

            $category->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Category deleted successfully'
            ], 200);
        } 
        catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}