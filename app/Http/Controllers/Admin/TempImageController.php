<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class TempImageController extends Controller
{
    public function create(Request $request)
    {
        
        if ($request->hasFile('image')) {
            $image = $request->file('image'); 
            $ext = $image->getClientOriginalExtension();
            $newName = time() . '.' . $ext;
    
            $tempImage = new TempImage();
            $tempImage->name = $newName;
            $tempImage->save();
    
            $image->move(public_path('/temp'), $newName);
            
            // Generate Thumbnail
            $sourcePath = public_path() . '/temp/' . $newName;
            $destPath = public_path() . '/temp/thumb/' . $newName;
            $image = Image::make($sourcePath);
            $image->fit(300,275);
            $image->save($destPath);
            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'ImagePath' => asset('/temp/thumb/' . $newName),
                'message' => 'Image Uploaded Successfully!'
            ]);
        }
    
        return response()->json([
            'status' => false,
            'message' => 'No image uploaded.'
        ], 400);
    }
    
}
