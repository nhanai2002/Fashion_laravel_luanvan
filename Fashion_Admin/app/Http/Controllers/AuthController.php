<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin(){
        return view('auth/login', [
            'title' => 'Đăng nhập',
        ]);
    }

    public function login(Request $request){
        $this->validate($request, [
            'username' =>'required',
            'password' =>'required'
        ]);
        if(Auth::attempt([
            'username' => $request->input('username'),
            'password' => $request->input('password')
        ],$request->input('remember'))){
            return redirect()->route('admin');
        }

        Session::flash('error', 'Tài khoản hoặc Mật khẩu không đúng!');

        return redirect()->back();
    }
    
    public function logout(){
        Auth::logout();
        
        return redirect()->route('login');
    }
}
