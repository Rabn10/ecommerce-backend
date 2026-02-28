<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
     public function index()
    {
        $brands = Brand::orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 200,
            'data' => $brands
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

        $brand = Brand::create([
            'name' => $request->name,
            'status' => $request->status
        ]);

        return response()->json([
            'status' => 200,
            'data' => $brand,
            'message' => 'Brand created successfully'
        ], 200);
    }

    public function show($id)
    {
        try {
            $brand = Brand::findOrFail($id);

            if (!$brand) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Brand not found'
                ], 404);
            }

            return response()->json([
                'status' => 200,
                'data' => $brand
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
            $brand = Brand::findOrFail($id);

            if (!$brand) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Brand not found'
                ], 404);
            }

            $brand->update([
                'name' => $request->name,
                'status' => $request->status
            ]);

            return response()->json([
                'status' => 200,
                'data' => $brand,
                'message' => 'Brand updated successfully'
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
            $brand = Brand::findOrFail($id);

            if (!$brand) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Brand not found'
                ], 404);
            }

            $brand->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Brand deleted successfully'
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
