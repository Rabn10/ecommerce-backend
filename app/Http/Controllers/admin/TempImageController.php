<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\TempImage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class TempImageController extends Controller
{
    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ], 400);
        }

        $tempImage = new TempImage();
        $tempImage->name = "dummy name";
        $tempImage->save();

        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/temps'), $imageName);

        $tempImage->name = $imageName;
        $tempImage->save();

        //save image thumbnail
        $manager = new ImageManager(Driver::class);
        $img = $manager->read(public_path('uploads/temps/' . $imageName));
        $img->coverDown(400, 450);
        $img->save(public_path('uploads/temps/thumb/' . $imageName));

        return response()->json([
            'status' => 200,
            'message' => 'Image uploaded successfully',
            'data' => $tempImage
        ], 200);

    }
}
