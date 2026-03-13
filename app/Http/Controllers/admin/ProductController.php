<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;


class ProductController extends Controller
{   
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => 200,
            'data' => $products
        ],200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'required|unique:products,sku',
            'status' => 'required',
            'is_featured' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ], 400);
        }

        $product = Product::create([
            'title' => $request->title,
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'description' => $request->description,
            'barcode' => $request->barcode,
            'short_description' => $request->short_description,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'quantity' => $request->quantity ?? 0,
            'sku' => $request->sku,
            'status' => $request->status,
            'is_featured' => $request->is_featured
        ]);

        return response()->json([
            'status' => 200,
            'data' => $product
        ]);
    }

    public function update($id, Request $request)
    {
        
    }

    public function show($id)
    {
    }

    public function destroy($id)
    {
    }
}
