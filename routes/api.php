<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\SizeController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\TempImageController;
use App\Http\Controllers\front\ProductController as FrontProductController;



//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::post('/admin/login', [AuthController::class, 'authenticate']);
Route::get('get-latest-products', [FrontProductController::class, 'latestProducts']);
Route::get('get-featured-products', [FrontProductController::class, 'featuredProducts']);


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('categories', CategoryController::class);

    Route::resource('brands', BrandController::class);

    Route::get('sizes', [SizeController::class, 'index']);

    Route::resource('products', ProductController::class);

    Route::post('temp-images', [TempImageController::class, 'store']);

    Route::post('save-product-image', [ProductController::class, 'saveProductImage']);

    Route::get('change-product-default-image', [ProductController::class, 'updateDefalutImage']);

    Route::delete('delete-product-image/{id}', [ProductController::class, 'deleteProductImage']);
    

    


});
