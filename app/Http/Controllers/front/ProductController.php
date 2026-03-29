<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function latestProducts()
    {
        try {
            $products = Product::where('status', 1)
                        ->orderBy('created_at', 'desc')
                        ->limit(8)
                        ->get();

            return response()->json([
                'status' => 200,
                'data' => $products
            ], 200);
        }
        catch(\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function featuredProducts()
    {
        try {
            $products = Product::where('status', 1)
                        ->where('is_featured', 1)
                        ->orderBy('created_at', 'desc')
                        ->limit(8)
                        ->get();

            return response()->json([
                'status' => 200,
                'data' => $products
            ], 200);
        }
        catch(\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
