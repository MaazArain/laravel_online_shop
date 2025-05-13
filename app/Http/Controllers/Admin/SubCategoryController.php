<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
   
    public function index(Request $request)
    {
        $sub_categories = SubCategory::with('category');


        if(!empty($request->get('keyword')))
        {
            $sub_categories = $sub_categories->where('name' , 'like' , '%'  . $request->get('keyword') . '%');
            
        }
        $sub_categories = $sub_categories->orderBy('id' , 'ASC')->paginate(10);
        return view('admin.sub_category.index' , compact('sub_categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name' , 'ASC')->get();
        return view('admin.sub_category.create' , compact('categories'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories',
            'category' => 'required',
            'status' => 'required',
            'showHome' => 'required|in:Yes,No',
        ]);
    
        if($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $subCategory = new SubCategory();
        $subCategory->name = $request->name;
        $subCategory->slug = $request->slug;
        $subCategory->status = $request->status;
        $subCategory->category_id = $request->category;
        $subCategory->showHome = $request->showHome;
        $subCategory->save();

        session()->flash('success', 'SubCategory Inserted Successfully');
        return response()->json([
            'status' => true,
            'redirect' => route('admin.sub_categories'),
        ]);
    }


    public function show(string $id)
    {
        //
    }


    public function edit($subCategoryId , Request $request)
    {
        $subCategories = SubCategory::find($subCategoryId);
        if(empty($subCategories))
        {
            $request->session()->flash('error' , 'Record Not Found');
           return redirect()->back();
        }

        $categories = Category::orderBy('name' , 'ASC')->get();
        return view('admin.sub_category.edit' , compact('subCategories' , 'categories'));
    }


    public function update($id ,Request $request)
    { 
        $subCategory = SubCategory::find($id);
        if (empty($subCategory)) {
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'SubCategory Not Found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories,slug,' . $subCategory->id,
            'category' => 'required|exists:categories,id',
            'status' => 'required',
            'showHome' => 'required|in:Yes,No',
        ]);
        if($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
        $subCategory->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'category_id' => $request->category,
            'status' => $request->status,
            'showHome' => $request->showHome,
        ]);
        $request->session()->flash('success' , 'SubCategory Updated Successfully!');
        return response()->json([
            'status' => true,
            'message' => 'SubCategory Updated Successfully!',
            'redirect' => route('admin.sub_categories'),
           
        ]);

    }


    public function destroy(string $id , Request $request)
    {
        $subCategory = SubCategory::find($id);

        if(!$subCategory)
        {
            $request->session()->flash('error' , 'SubCategory Not Found');
            return response()->json([
                'status' => true,
                'message' => 'Category not found'   
            ]);
        }

        $subCategory->delete();
        $request->session()->flash('error' , 'Category Deleted Successfully');

        return response()->json([
            'status' => true,
            'message' => 'Category Deleted Successfully'
        ]);
    }
}
