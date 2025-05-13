<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ProductSubCategoryController extends Controller
{
    public function index(Request $request)
    {
        if (!empty($request->category_id)) {
            $subCategories = SubCategory::where('category_id', $request->category_id)
                ->orderBy('name', 'ASC')
                ->get();
            
           
            if ($subCategories->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No Subcategories Found',
                    'subCategories' => []
                ]);
            }
    
            return response()->json([
                'status' => true,
                'subCategories' => $subCategories,
            ]);
        }
    
        return response()->json([
            'status' => false,
            'message' => 'Category ID is missing',
            'subCategories' => [],
        ]);
    }
    
    
}
