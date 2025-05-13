<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
class CategoryController extends Controller
{
    public function admin_categories()
    {
        return view('admin.categories.add');
    }

    public function admin_index(Request $request)
    {
        $categories = Category::query();

        if (!empty($request->get('keyword'))) {
            $categories = $categories->where('name', 'like', '%' . $request->get('keyword') . '%');
        }
        $categories = $categories->orderBy('id', 'ASC')->paginate(10);
        return view('admin.categories.index' , compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories',
            'status' => 'required',
            'showHome' => 'required|in:Yes,No',            
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    
        // Save Category
        $category = new Category();
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->status = $request->status;
        $category->showHome = $request->showHome;
        $category->save();
    
        if (!empty($request->image_id)) {
            $tempImage = TempImage::find($request->image_id);
    
            if ($tempImage) {
                $extArr = explode('.', $tempImage->name);
                $ext = last($extArr);
    
                $newImageName = $category->id . '.' . $ext;
                $sPath = public_path('/temp/' . $tempImage->name); // Fixed path
                $dPath = public_path('/uploads/category/' . $newImageName);
    
                if (File::exists($sPath)) {
                    File::copy($sPath, $dPath);
    
                    $thumbPath = public_path('/uploads/category/thumb/' . $newImageName);
                    $img = Image::make($sPath);
                    $img->fit(800, 600, function ($constraint) {
                        $constraint->upsize();
                    });
                    $img->save($thumbPath);
    
                    $category->image = $newImageName;
                    $category->save();
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Image file not found in temp folder'
                    ], 400);
                }
            }
        }
    
        session()->flash('success', 'Category Inserted Successfully');
    
        return response()->json([
            'status' => true,
            'redirect' => route('admin.categories'),
        ]);
    }

    public function edit($categoryId, Request $request)
    {
        $categoryEdit = Category::find($categoryId);
        if(empty($categoryEdit))
        {
            return redirect()->route('admin.categories');
        }
        return view('admin.categories.edit' , compact('categoryEdit'));
    }

    public function update($categoryId, Request $request)
    {
        $category = Category::find($categoryId);
    
        if (empty($category)) {
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Category Not Found'
            ]);
        }
    
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $category->id . ',id',
            'status' => 'required',
            'showHome' => 'required|in:Yes,No',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->status = $request->status;
        $category->showHome = $request->showHome;

        $oldImage = $category->image;
    
        if (!empty($request->image_id)) {
            $tempImage = TempImage::find($request->image_id);
    
            if ($tempImage) {
                $extArr = explode('.', $tempImage->name);
                $ext = last($extArr);
    
                $newImageName = $category->id . '-' . time() . '.' . $ext;
                $sPath = public_path('/temp/' . $tempImage->name);
                $dPath = public_path('/uploads/category/' . $newImageName);
    
                if (File::exists($sPath)) {
                    File::copy($sPath, $dPath);
    
                    $thumbPath = public_path('/uploads/category/thumb/' . $newImageName);
                    $img = Image::make($sPath);
                    $img->fit(800, 600, function ($constraint) {
                        $constraint->upsize();
                    });
                    $img->save($thumbPath);
    
                    $category->image = $newImageName;
    
                    File::delete(public_path('/uploads/category/thumb/' . $oldImage));
                    File::delete(public_path('/uploads/category/' . $oldImage));
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Image file not found in temp folder'
                    ], 400);
                }
            }
        }
    
        $category->save();
    
        session()->flash('success', 'Category Updated Successfully');
    
        return response()->json([
            'status' => true,
            'redirect' => route('admin.categories'),
        ]);
    }


    public function destroy($categoryId, Request $request)
    {
        $category = Category::find($categoryId);
    
        if (!$category) {
            $request->session()->flash('error' , 'Category Not Found');
            return response()->json([
                'status' => true,
                'message' => 'Category not found'   
            ]);
        }
    
        if (!empty($category->image)) {
            File::delete(public_path('/uploads/category/thumb/' . $category->image));
            File::delete(public_path('/uploads/category/' . $category->image));
        }
    
        $category->forceDelete();  // ✅ Hard Delete to Ensure Removal
        
        $request->session()->flash('error' , 'Category Deleted Successfully');

        return response()->json([
            'status' => true,
            'message' => 'Category Deleted Successfully'
        ]);
    }
    
    public function getSlug(Request $request)
    {
            $slug = '';
             if(!empty($request->title))
             {
                 $slug = Str::slug($request->title);
             }
             return response()->json([
                 'status' => true,
                 'slug' => $slug,
             ]);
    }
}
