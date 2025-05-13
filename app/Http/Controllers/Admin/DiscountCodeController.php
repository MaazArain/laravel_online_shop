<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiscountCodeController extends Controller
{
    public function index()
    {

    }

    public function create()
    {
        return view('admin.coupan.create');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [

        ]);

        if($validator->passes())
        {

        }
        else{
            if($validator->fails())
            {
                 return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
            }
        }
    }

    public function edit()
    {

    }

    public function update()
    {

    }


}
