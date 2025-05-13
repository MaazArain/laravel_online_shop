<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function login()
    {
        return view('frontend.account.login');
    }

    public function register()
    {

        return view('frontend.account.register');
    }

    public function processRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'phone' => 'required|min:11',
            'password' => 'required|min:6|confirmed',
        ]);
        if ($validator->passes()) {

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->save();

            session()->flash('success', 'User Created Successfully!');
            return response()->json([
                'status' => true,
                'redirect' => route('frontend.account.login'),
                'message' => 'User Created Successfully!',
            ]);
        } else {
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);
            }
        }
    }


   public function processLogin(Request $request)
   {
        $validator = Validator::make($request->all() , [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('frontend.account.login')
                ->withErrors($validator)
                ->withInput(); // Don't limit to only('email') — include password too
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if(Auth::attempt($credentials , $remember))
        {
            $user = Auth::user();

            if($user->role == 2)
            {
                return redirect()->route('frontend.account.profile')->with('success' , $user->name .'welcome to Login Successfully!');
            }
            else{

                return redirect()->route('frontend.account.login');
            }
        }
     else{
        return redirect()->route('frontend.account.login')
        ->withInput($request->only('email' ))
        ->withErrors(['email' => 'Either Email or Password is Incorrect!']);
     }
   } 

   public function profile()
   {
        return view('frontend.account.profile');
   }


   public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('frontend.account.login')->with('success', 'Logout Successfully!');
}

  
}
