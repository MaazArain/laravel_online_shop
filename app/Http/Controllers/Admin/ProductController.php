<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SubCategory;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Image;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('product_images'); // Eager loading images
        
        if (!empty($request->get('keyword'))) {
            $query->where('title', 'like', '%' . $request->get('keyword') . '%'); 
        }
        $products = $query->orderBy('id', 'ASC')->paginate(10);
        return view('admin.products.index', compact('products'));
    }
    

    public function create()
    {
        $categories = Category::orderBy('name' , 'ASC')->get();
        $brands = Brand::orderBy('name' , 'ASC')->get();
        return view('admin.products.create' , compact('categories' , 'brands'));
    }

    public function store(Request $request)
    { 
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'status' => 'required',
            'is_featured' => 'required|in:Yes,No'
        ];

        if(!empty($request->track_qty) && $request->track_qty == 'Yes')
        {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all() , $rules);

        if($validator->passes())
        {
            $product = new Product();
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->short_description = $request->short_description;
            $product->shipping_returns = $request->shipping_returns;
         
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand_id;
            $product->is_featured = $request->is_featured;
            $product->related_products = (!empty($request->related_products)) ? implode(',' , $request->related_products) : '';
            $product->save();
            if(!empty($request->image_array))
            {
                foreach($request->image_array as $temp_image_id)
                {
                    $tempImageInfo = TempImage::find($temp_image_id);
                    $extArray = explode('.' , $tempImageInfo->name);
                    $ext = last($extArray);
                  
                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = 'NULL';
                    $productImage->save();

                    $imageName = $product->id . '-' . $productImage->id . '-' . time() . '.' . $ext;
                    $productImage->image = $imageName;
                    $productImage->save();
                  
                    //Large Image
                    $sourcePath = public_path() . '/temp/' . $tempImageInfo->name;
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
                }
            }

            $request->session()->flash('success', 'Product Inserted Successfully');
    
            return response()->json([
                'status' => true,
                'redirect' => route('admin.products'),
            ]);
           
        }
        else{
           if($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
        }
    }


    public function edit($id , Request $request)
    {
        $product = Product::find($id);
        if (empty($product)) {
            $request->session()->flash('error' , 'product Not Found');
            return redirect()->route('admin.products')->with('error' , 'Product Not Found!!');
         }
        $productImages = ProductImage::where('product_id' , $product->id)->get();
        $subCategories = SubCategory::where('category_id' ,$product->category_id)->get();
         $relatedProducts = [];
        //  Fetch Related Products
        if($product->related_products != '')
        {
            $productsArr = explode(',' , $product->related_products);
            $relatedProducts = Product::whereIn('id' , $productsArr)->get();
        }

        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();



        return view('admin.products.edit', compact('product', 'categories', 'brands' , 'subCategories' , 'productImages' , 'relatedProducts'));
    }
  

    public function update($productId, Request $request)
    {
        $product = Product::find($productId);
        if (empty($product)) {
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Product Not Found'
            ]);
        }
    
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,' . $product->productId . ',id',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku,' . $product->productId . ',id',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'status' => 'required',
            'is_featured' => 'required|in:Yes,No'
        ];
    
        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->passes()) {
    
            // Assign updated values
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->short_description = $request->short_description;
            $product->shipping_returns = $request->shipping_returns;
            $product->related_products = (!empty($request->related_products)) ? implode(',' , $request->related_products) : '';
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand_id;
            $product->is_featured = $request->is_featured;
            $product->save();
    
            // Handle images
            if (!empty($request->image_array)) {
                foreach ($request->image_array as $temp_image_id) {
                    $tempImageInfo = TempImage::find($temp_image_id);
    
                    if ($tempImageInfo && !empty($tempImageInfo->name)) {
                        $extArray = explode('.', $tempImageInfo->name);
                        $ext = last($extArray);
    
                        $productImage = new ProductImage();
                        $productImage->product_id = $product->id;
                        $productImage->image = 'NULL';
                        $productImage->save();
    
                        $imageName = $product->id . '-' . $productImage->id . '-' . time() . '.' . $ext;
                        $productImage->image = $imageName;
                        $productImage->save();
    
                        // Large image
                        $sourcePath = public_path() . '/temp/' . $tempImageInfo->name;
                        $destPath = public_path() . '/uploads/product/large/' . $imageName;
                        $image = \Intervention\Image\Facades\Image::make($sourcePath);
                        $image->resize(1400, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $image->save($destPath);
    
                        // Small image
                        $destPath = public_path() . '/uploads/product/small/' . $imageName;
                        $image = \Intervention\Image\Facades\Image::make($sourcePath);
                        $image->fit(300, 300);
                        $image->save($destPath);
                    }
                }
            }
    
            $request->session()->flash('success', 'Product Updated Successfully');
    
            return response()->json([
                'status' => true,
                'redirect' => route('admin.products'),
            ]);
    
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    

    public function destroy(Request $request,$id)
    {
        $product = Product::find($id);

        if(empty($product))
        {
            $request->session()->flash('error' , 'Product Not Found!');
            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
        }

        $productImages = ProductImage::where('product_id' , $id)->get();

        if(!empty($productImages))
        {

           foreach($productImages as $productImage)
           {
            File::delete(public_path('uploads/product/large/' . $productImage->image));
            File::delete(public_path('uploads/product/small/' . $productImage->image));
           }

          ProductImage::where('product_id' , $id)->delete();
        }

        $product->delete();

        $request->session()->flash('success' , 'Product Deleted Successfully!');

        return response()->json([
            'status' => true,
            'message' => 'Product Deleted Successfully!',
        ]);
    }



    // Here it is the code of getProducts through selectbox
    public function getProducts(Request $request)
    {
        $tempProduct = [];
    
        if (!empty($request->term)) {
            $products = Product::where('title', 'like', '%' . $request->term . '%')->get();
    
            foreach ($products as $product) {
                $tempProduct[] = ['id' => $product->id, 'text' => $product->title];
            }
        }
    
        return response()->json(['tags' => $tempProduct]);

    }
    
}
