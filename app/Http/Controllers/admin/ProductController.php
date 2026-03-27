<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\TempImage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\ProductImage;
use App\Models\ProductSize;

class ProductController extends Controller
{   
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->with(['product_images', 'product_sizes'])->get();

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

        if(!empty($request->sizes)) {
            foreach($request->sizes as $sizeId) {
                $productSize = new ProductSize();
                $productSize->size_id = $sizeId;
                $productSize->product_id = $product->id;
                $productSize->save();
            }
        }


        if(!empty($request->gallery)) {
            foreach($request->gallery as $key => $tempImageId) {
                $tempImage = TempImage::findOrFail($tempImageId);

                //Large Thumbnail
                $extArray = explode('.', $tempImage->name);
                $ext = end($extArray);

                $imageName = $product->id . '-' . time() . '.' . $ext;
                $manager = new ImageManager(Driver::class);
                $img = $manager->read(public_path('uploads/temps/' . $tempImage->name));
                $img->scaleDown(1200);
                $img->save(public_path('uploads/products/large/' . $imageName));

                //Small Thumbnail
                $imageName = $product->id . '-' . time() . '.' . $ext;
                $manager = new ImageManager(Driver::class);
                $img = $manager->read(public_path('uploads/temps/' . $tempImage->name));
                $img->coverDown(400, 460);
                $img->save(public_path('uploads/products/small/' . $imageName));

                $productImage = new ProductImage();
                $productImage->image = $imageName;
                $productImage->product_id = $product->id;
                $productImage->save();

                if($key == 0) {
                    $product->update([
                        'image' => $imageName
                    ]);
                }
            }
        }

        return response()->json([
            'status' => 200,
            'message' => 'Product created successfully',
            'data' => $product
        ]);
    }

    public function update($id, Request $request)
    {

        $product = Product::findOrFail($id);

        if(!$product) {
            return response()->json([
                'status' => 404,
                'message' => 'Product not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'required|unique:products,sku,'.$id.',id',
            'status' => 'required',
            'is_featured' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ], 400);
        }

        $product->update([
            'title' => $request->title,
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'quantity' => $request->quantity ?? 0,
            'sku' => $request->sku,
            'status' => $request->status,
            'is_featured' => $request->is_featured
        ]);

        if(!empty($request->sizes)) {
           $productSizes = ProductSize::where('product_id', $product->id)->delete();
            foreach($request->sizes as $sizeId) {
                $productSize = new ProductSize();
                $productSize->size_id = $sizeId;
                $productSize->product_id = $product->id;
                $productSize->save();
            }
        }

        return response()->json([
            'status' => 200,
            'data' => $product,
            'message' => 'Product updated successfully'
        ]);
    }

    public function show($id)
    {
        $product = Product::with(['product_images', 'product_sizes'])->findOrFail($id);

        if(!$product) {
            return response()->json([
                'status' => 404,
                'message' => 'Product not found'
            ], 404);
        }

        $productSizes = $product->product_sizes()->pluck('size_id');

        return response()->json([
            'status' => 200,
            'data' => $product,
            'productSizes' => $productSizes
        ], 200);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if(!$product) {
            return response()->json([
                'status' => 404,
                'message' => 'Product not found'
            ], 404);
        }

        $product->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Product deleted successfully'
        ], 200);
    }

    public function saveProductImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ], 400);
        }

        

        $image = $request->file('image');
        $imageName = $request->product_id . '-' . time() . '.' . $image->getClientOriginalExtension();


        //Large Thumbnail
        $manager = new ImageManager(Driver::class);
        $img = $manager->read($image->getPathName());
        $img->scaleDown(1200);
        $img->save(public_path('uploads/products/large/' . $imageName));

        //Small Thumbnail
        $manager = new ImageManager(Driver::class);
        $img = $manager->read($image->getPathName());
        $img->coverDown(400, 460);
        $img->save(public_path('uploads/products/small/' . $imageName));

        //Insert a record in product_image table
        $productImage = new ProductImage();
        $productImage->image = $imageName;
        $productImage->product_id = $request->product_id;
        $productImage->save();

        return response()->json([
            'status' => 200,
            'message' => 'Image uploaded successfully',
            'data' => $productImage
        ], 200);
    }

    public function updateDefalutImage(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $product->image = $request->image;
        $product->save();

        return response()->json([
            'status' => 200,
            'message' => 'Defalut image updated successfully'
        ], 200);
    }
}
