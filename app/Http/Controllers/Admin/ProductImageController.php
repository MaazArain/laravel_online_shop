<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductImageController extends Controller
{
    public function update(Request $request)
    {

        $image = $request->file('image'); 
        $ext = $image->getClientOriginalExtension();
        $sourcePath = $image->getPathname();
        
        $productImage = new ProductImage();
        $productImage->product_id = $request->product_id;
        $productImage->image = 'NULL';
        $productImage->save();

        $imageName = $request->product_id . '-' . $productImage->id . '-' . time() . '.' . $ext;
        $productImage->image = $imageName;
        $productImage->save();


          //Large Image
          $destPath = public_path() . '/uploads/product/large/' . $imageName;
          $image = \Intervention\Image\Facades\Image::make($sourcePath);
          $image->resize(1400, null, function ($constraint) {
              $constraint->aspectRatio();
          });
          $image->save($destPath);


          // Small Image
          $destPath = public_path() . '/uploads/product/small/' . $imageName;
          $image = \Intervention\Image\Facades\Image::make($sourcePath);
          $image->fit(300, 300);
          $image->save($destPath);   

          return response()->json([
            'status' => true,
            'image_id' => $productImage->id,
            'ImagePath' => asset('uploads/product/small/' . $productImage->image),
            'message' => 'Image Saved Successfully!'
          ]);
    }

    public function destroy(Request $request)
    {
        $productImage = ProductImage::find($request->id);

        if(empty($productImage))
        {
            return response()->json([
                'status'=> false,
                'message'=> 'Image Not Found',
            ]);
        }

        File::delete(public_path('uploads/product/large/' . $productImage->image));
        File::delete(public_path('uploads/product/small/' . $productImage->image));

        $productImage->delete();

        return response()->json([
            'status' => true,
            'message' => 'Image Deleted Successfully!',
        ]);
    }
}
