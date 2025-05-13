<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandsController extends Controller
{
    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands',
            'status' => 'required',
            
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        Brand::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'status' => $request->status,
        ]);

        session()->flash('success', 'Brands Inserted Successfully');
    
        return response()->json([
            'status' => true,
            'redirect' => route('admin.brands'),
        ]);
    }

    public function index(Request $request)
    {
        $brands = Brand::query();
        if(!empty($request->get('keyword')))
        {
            $brands = $brands->where('name' , 'like' , '%' . $request->get('keyword') . '%');

        }
        $brands = $brands->orderBy('id' , 'ASC')->paginate(10);
        return view('admin.brands.index' , compact('brands'));
    }

    public function edit($id , Request $request)
    {
        $brandsEdit = Brand::find($id);
        if(empty($brandsEdit))
        {
            return redirect()->route('admin.brands');
        }
        return view('admin.brands.edit' , compact('brandsEdit'));
    }

    public function update($id, Request $request)
    {
        $brands = Brand::find($id);
        if(empty($brands))
        {
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Brands Not Found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,' . $brands->id . ',id',
            'status' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $brands->name  = $request->name;
        $brands->slug = $request->slug;
        $brands->status = $request->status;

        $brands->save();
        session()->flash('success', 'Brands Updated Successfully');
    
        return response()->json([
            'status' => true,
            'redirect' => route('admin.brands'),
        ]);

    }


    public function destroy($id , Request $request)
    {
        $brand = Brand::find($id);

        if(!$brand)
        {
            $request->session()->flash('error' , 'Brand Not Found');
            return response()->json([
                'status' => true,
                'message' => 'Brand not found'   
            ]);
        }
        $brand->delete();
        $request->session()->flash('error' , 'Brands Deleted Successfully');
       
        return response()->json([
            'status' => true,
            'message' => 'Brands Deleted Successfully'
        ]);

    }
}
