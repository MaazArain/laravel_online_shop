<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request , $categorySlug = null, $subCategorySlug = null)
    {
        $categorySelected = '';
        $subCategorySelected = '';
        $brandsArray = [];

      
        $categories = Category::orderBy('name' , 'ASC')->with('sub_categories')->where('status' , 1)->where('showHome' , 'Yes')->get();
        $brands = Brand::orderBy('name' , 'ASC')->where('status' ,1)->get();
        $products = Product::where('status' , 1);
        //Apply Filters Are
        if(!empty($categorySlug))
        {
            $category = Category::where('slug' , $categorySlug)->first();
            $products = $products->where('category_id', $category->id);
            $categorySelected = $category->id;
        }

        if(!empty($subCategorySlug))
        {
          $subCategory = SubCategory::where('slug' , $subCategorySlug)->first();
          $products = $products->where('sub_category_id', $subCategory->id);
          $subCategorySelected = $subCategory->id;
        }


        if(!empty($request->get('brand')))
        {
           $brandsArray = explode(',' , $request->get('brand'));
           $products = $products->whereIn('brand_id' , $brandsArray);
        }

        if($request->get('price_max') != '' && $request->get('price_min' != ''))
        {
            $products = $products->whereBetween('price', [
                intval($request->get('price_min')),
                intval($request->get('price_max'))
            ]);
        }
        if($request->get('sort') != '')
        {
            if($request->get('sort') == 'latest')
            {
        $products = $products->orderBy('id', 'DESC')->paginate(5);
            }
            elseif($request->get('sort') == 'price_asc')
            {
                $products = $products->orderBy('price', 'ASC')->paginate(5);
            }
            else{
                $products = $products->orderBy('price', 'DESC')->paginate(5);
            }
        }
        else{
            $products = $products->orderBy('id', 'DESC')->paginate(5);
        }

        $data['priceMax'] = (intval($request->get('price_max')) == 0) ? 1000 : $request->get('price_max');
        $data['priceMin'] = intval($request->get('price_min'));
        $data['sort'] = $request->get('sort');
        $sort = $data['sort']; 
       
        return view('frontend.shop' , compact('categories' , 'brands' , 'products' , 'categorySelected' , 'subCategorySelected' , 'brandsArray' , 'sort'));
    }

    public function product($slug)
    {
        $product = Product::where('slug' , $slug)->with('product_images')->first();
        $relatedProducts = [];
        if($product->related_products != "")
        {
            $productArr = explode(',' , $product->related_products);
            $relatedProducts = Product::whereIn('id' , $productArr)->with('product_images')->get();
        }
       return view('frontend.product' , compact('product' , 'relatedProducts'));
    }
}
