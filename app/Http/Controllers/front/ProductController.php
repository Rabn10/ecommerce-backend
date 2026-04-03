<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class ProductController extends Controller
{
    public function getProducts(Request $request)
    {
        $products = Product::orderBy('created_at', 'desc')
                    ->where('status', 1);

        //filter by category
        if($request->has('category')) {
            $catArray = explode(',', $request->category);
            $products = $products->whereIn('category_id', $catArray);
        }      

        //filter by Brand
        if($request->has('brand')) {
            $catArray = explode(',', $request->brand);
            $products = $products->whereIn('brand_id', $catArray);
        }       

        $products = $products->get();            

        return response()->json([
            'status' => 200,
            'data' => $products
        ]);           
    }

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

    public function getCategories()
    {
        $categories = Category::where('status', 1)->orderBy('name', 'asc')->get();

        return response()->json([
            'status' => 200,
            'data' => $categories
        ], 200);
    }

    public function getBrands()
    {
        $brands = Brand::where('status', 1)->orderBy('name', 'asc')->get();

        return response()->json([
            'status' => 200,
            'data' => $brands
        ], 200);
    }
}
