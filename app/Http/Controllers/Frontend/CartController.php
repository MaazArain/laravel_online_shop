<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingCharge;
use Gloudemans\Shoppingcart\Facades\Cart;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{

    public function cart()
    {
        $cartContent = Cart::content();
        return view('frontend.cart' , compact('cartContent'));
    }

    public function add_to_cart(Request $request)
    {
        $product = Product::with('product_images')->find($request->id);

        if($product == null)
        {
            return response()->json([
                'status' => false,
                'message' => 'Product Not Found'
            ]);
        }

        if(Cart::count() > 0)
        {
            $cartContent = Cart::content();
            $productAlreadyExists = false;

            foreach($cartContent as $item)
            {
                if($item->id == $product->id)
                {
                    $productAlreadyExists = true;
                }
            }

            if($productAlreadyExists == false)
            {
                Cart::add($product->id, $product->title, 1, $product->price , ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);

                $status = true;
                $message ='<strong>' . $product->title . '</strong> Added In Your Cart';
                session()->flash('success', $message);
            }
            else{
                $status = false;
                $message = '<strong>' . $product->title . '</strong>  Already Added in a Cart';
             session()->flash('error', $message);
            }
        }
        else{
            Cart::add($product->id, $product->title, 1, $product->price , ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);
            $status = true;
            $message = '<strong>' . $product->title . '</strong> added in a cart Successfully!';
            session()->flash('success', $message);

        }
        session()->flash('cart_message', $message);
        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);

    }

    public function updateCart(Request $request)
    {
        $rowId = $request->rowId;
        $qty = $request->qty;

        $itemInfo = Cart::get($rowId);

        $product = Product::find($itemInfo->id);
        if($product->track_qty == 'Yes')
        {
          if($qty <= $product->qty)
          {
            Cart::update($rowId , $qty);
            $message = 'Cart  Updated Successfully!';
            $status = true;
            session()->flash('success' , $message);

            return response()->json([
                'status' => $status,
                'message' => $message,
            ]);
          }
            else{
                $message = 'That Request qty('. $qty .') Not Available on this stock.';
                $status = true;
                session()->flash('error' , $message);

                return response()->json([
                    'status' => $status,
                    'message' => $message,
                ]);
            }
        }

    }

    public function deleteItem(Request $request)
    {
        $rowId  = $request->rowId;
       $item  = Cart::get($rowId);

        if($item  == null)
        {
            $errorMessage = 'Item not found in this cart';
            session()->flash('error' , $errorMessage);

            return response()->json([
                'status' => false,
                'message' => $errorMessage,
            ]);
        }

        Cart::remove($rowId);
        $message = 'Item Removed From Cart Successfully!';
        session()->flash('error', $message);

        return response()->json([
            'status' => true,
            'message' => $message,
            'redirect_url' => route('frontend.cart')
        ]);


    }


    public function checkout()
{
    // If Cart is empty, redirect to Login Page
    if(Cart::count() == 0)
    {
        return redirect()->route('frontend.account.login')->with('error', 'Your cart is empty. Please add items before checkout.');
    }

    // If User is not logged in, redirect to Login Page
    if(!Auth::check())
    {
        if(!session()->has('url.intended'))
        {
            session(['url.intended' => url()->current()]);
        }
        return redirect()->route('frontend.account.login');
    }

    $customerAddress = CustomerAddress::with('orders')
        ->where('user_id', Auth::user()->id)
        ->first();
    session()->forget('url.intended');

    $countries = Country::orderBy('name' , 'ASC')->get();
    $userCountry = $customerAddress->country_id ?? '';
    $shippingInfo = ShippingCharge::where('country_id' , $userCountry)->first();

    $totalQty = 0;
    $totalShippingCharge = 0;
    $grandTotal = 0;
    foreach(Cart::content() as $item)
    {
        $totalQty += $item->qty;
    }

    $totalShippingCharge = $shippingInfo?->amount ? ($totalQty * $shippingInfo->amount) : 0;

    $grandTotal = Cart::subtotal(2,'.' , '') + $totalShippingCharge;
    return view('frontend.checkout' , compact('countries' ,'customerAddress' , 'totalShippingCharge' , 'grandTotal'));
}

    public function processCheckout(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'first_name' => 'required|min:5',
            'last_name' => 'required',
            'email' => 'required',
            'country' => 'required',
            'address' => 'required',
            'apartment' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required',
            'notes' => 'required',
        ]);

        if($validator->passes())
        {
            $user = Auth::user();

            CustomerAddress::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'country_id' => $request->country,
                    'address' => $request->address,
                    'apartment' => $request->apartment,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip
                ]
            );

            // data store orders table
            if($request->payment_method == 'cash_on_delivery')
            {
                 $shipping = 0;
                 $discount = 0;
                 $subTotal = Cart::subtotal(2,'.' , '');
                 $grandTotal = $subTotal + $shipping;

                    // Get shipping info for selected country
                    $shippingInfo = ShippingCharge::where('country_id', $request->country)->first();
                    // Count total quantity of items in cart
                    $totalQty = 0;
                    foreach (Cart::content() as $item) {
                        $totalQty += $item->qty;
                    }

                // If shipping info found, use it. Otherwise, use 'rest_of_world'
                if ($shippingInfo) {
                    $shipping = $totalQty * $shippingInfo->amount;
                      $grandTotal = $subTotal + $shipping;
                } else {
                    $defaultShipping = ShippingCharge::where('country_id', 'rest_of_world')->first();
                    $shipping = $defaultShipping ? $totalQty * $defaultShipping->amount : 0;
                    $grandTotal = $subTotal + $shipping;
                }


                $order = new Order();
                $order->subtotal = $subTotal;
                $order->shipping = $shipping;
                $order->grand_total = $grandTotal;
                $order->user_id = $user->id;
                $order->first_name = $request->first_name;
                $order->last_name = $request->last_name;
                $order->email = $request->email;
                $order->mobile = $request->mobile;
                $order->country_id = $request->country;
                $order->address = $request->address;
                $order->apartment = $request->apartment;
                $order->city = $request->city;
                $order->state = $request->state;
                $order->zip = $request->zip;
                $order->notes = $request->notes;
                $order->save();
                // order Items Data Store

                foreach(Cart::content() as $item)
                {
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->product_id = $item->id;
                    $orderItem->name = $item->name;
                    $orderItem->qty = $item->qty;
                    $orderItem->price = $item->price;
                    $orderItem->total = $item->price * $item->qty;
                    $orderItem->save();


                }
            }

            session()->flash('success', 'You have Successfully Place Your Order!');
            Cart::destroy();
            return response()->json([
                'status' => true,
                'redirect' => route('frontend.thankyou', ['order_id' => $order->id]),
                'order_id' => $order->id
            ]);

        }
        else{
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);
            }
        }
    }

    public function thankyou($order_id)
    {
        return view('frontend.thankyou' , compact('order_id'));
    }


   public function getOrderBySummary(Request $request)
{
    if ($request->country_id > 0) {
        // Get subtotal from cart
        $subTotal = Cart::subtotal(2, '.', '');
        // Get shipping info for selected country
        $shippingInfo = ShippingCharge::where('country_id', $request->country_id)->first();
        // Count total quantity of items in cart
        $totalQty = 0;
        foreach (Cart::content() as $item) {
            $totalQty += $item->qty;
        }

        // If shipping info found, use it. Otherwise, use 'rest_of_world'
        if ($shippingInfo) {
            $shippingCharge = $totalQty * $shippingInfo->amount;
        } else {
            $defaultShipping = ShippingCharge::where('country_id', 'rest_of_world')->first();
            $shippingCharge = $defaultShipping ? $totalQty * $defaultShipping->amount : 0;
        }

        // Calculate grand total
        $grandTotal = $subTotal + $shippingCharge;

        // Return JSON response
        session()->flash('success', 'Order summary calculated successfully.');

        return response()->json([
            'status' => true,
            'grandTotal' => number_format($grandTotal, 2),
            'shippingCharge' => number_format($shippingCharge, 2)
        ]);

    }
    else
    {
        // If no valid country selected, only return subtotal with zero shipping
        $subTotal = Cart::subtotal(2, '.', '');
        $grandTotal = $subTotal;
        session()->flash('warning', 'No country selected. Shipping not calculated.');

        return response()->json([
            'status' => true,
            'grandTotal' => number_format($grandTotal, 2),
            'shippingCharge' => number_format(0, 2)
        ]);
    }
}

}
