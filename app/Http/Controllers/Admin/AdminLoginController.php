<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminLoginController extends Controller
{
    public function admin_login()
    {
        return view('admin.login');
    }

    // Here this is Admin Authenticate 
    public function admin_authenticate(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->passes())
        {
            if(Auth::guard('admin')->attempt(['email' => $request->email , 'password' => $request->password], $request->get('remember')))
            {
                $admin = Auth::guard('admin')->user();

                if($admin->role == 1)
                {
                    $request->session()->flash('success', 'Welcome, ' . $admin->name . '! You have logged in successfully.');
                     return redirect()->route('admin.dashboard');
                }
                else{
                    Auth::guard('admin')->logout();
                    return redirect()->route('admin.login')->with('error' , 'You are Not Authorized To access Admin Panel.');
                }
            }
            else{
                return redirect()->route('admin.login')->with('error' , 'Either Email/Password is InCorrect');
            }
        }
        else{
            return redirect()->route('admin.login')
            ->withErrors($validator)
            ->withInput($request->only('email'));
        }
    }

    
}
