<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\ShippingCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{
    public function index()
    {


        $shippingList = ShippingCharge::leftJoin('countries' , 'countries.id' ,'=' , 'shipping_charges.country_id')
        ->select('shipping_charges.*' , 'countries.name as country_name')
        ->orderBy('created_at' , 'DESC')->get();



        return view('admin.shipping.index' , compact('shippingList'));
    }

    public function create()
    {
        $countries = Country::get();
        return view('admin.shipping.create' , compact('countries'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country' => 'required',
            'amount' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        $shippingExist = ShippingCharge::where('country_id', $request->country)->exists();

        if ($shippingExist) {
            session()->flash('error', 'Shipping Already Added!');
            return response()->json([
                'status' => false,

               'errors' => [
                    'country' => ['Shipping already added for this country.']
                ]
            ]);
        }

        ShippingCharge::create([
            'country_id' => $request->country,
            'amount' => $request->amount,
        ]);

        session()->flash('success', 'Shipping Added Successfully!');
        return response()->json([
            'status' => true,
            'redirect' => route('admin.shipping'),
        ]);
    }

    public function editShipping($id)
    {
        $shipping = ShippingCharge::where('id' , $id)->first();
        $countries = Country::get();
        return view('admin.shipping.edit' , compact('shipping' ,'countries'));
    }


    public function updateShipping(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'country' => 'required',
            'amount' => 'required|numeric'
        ]);

        if($validator->passes())
        {

            $shippingExist = ShippingCharge::where('country_id', $request->country)->exists();

            if ($shippingExist) {
                session()->flash('error', 'Shipping Already Added!');
                return response()->json([
                    'status' => false,

                   'errors' => [
                        'country' => ['Shipping already added for this country.']
                    ]
                ]);
            }

            $shipping = ShippingCharge::find($id);
            $shipping->country_id = $request->country;
            $shipping->amount = $request->amount;
            $shipping->save();

            session()->flash('success' , 'Shipping Updated Successfully!');

            return response()->json([
                'status' => true,
                'redirect' => route('admin.shipping'),
            ]);
        }
        else{
            if($validator->fails())
            {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors(),
                ]);
            }
        }
    }


    public function destoryShipping(Request $request,$id)
    {
        $shipping = ShippingCharge::find($id);

        if(!$shipping)
        {
            $request->session()->flash('error' , 'Shipping Not Found');
            return response()->json([
                'status' => true,
                'message' => 'Shipping not found'
            ]);
        }

        $shipping->delete();
        $request->session()->flash('error' , 'Shipping Deleted Successfully');

        return response()->json([
            'status' => true,
            'message' => 'Shipping Deleted Successfully'
        ]);
    }

}
