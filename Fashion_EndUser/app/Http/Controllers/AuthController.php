<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use FashionCore\Interfaces\IUserRepository;

class AuthController extends Controller
{
    protected $userRepo;
    public function __construct(IUserRepository $userRepo){
        $this->userRepo = $userRepo;
    }
    
    public function showRegister(){
        return view('auth/register', [
            'title' => 'Đăng ký ',
        ]);
    }

    public function register(Request $request){
        $this->validate($request, [
            'username' =>'required|unique:users',
            'password' => 'required|min:6|confirmed',
            'email' =>'required'
        ],[
            'username.required' =>'Vui lòng nhập tài khoản',
            'username.unique'=>'Tài khoản này đã tồn tại',
            'password.required' =>'Vui lòng nhập mật khẩu',
            'password.min' =>'Mật khẩu phải ít nhất 6 ký tự',
            'password.confirmed' => 'Nhập lại mật khẩu không trùng khớp',
            'email' =>'Vui lòng nhập email',
        ]);
        try{
            $request->merge(['username' => strtolower(trim($request->input('username')))]);
            $request->merge(['password' => bcrypt($request->input('password'))]);
            $this->userRepo->add($request->all());
            return redirect('login');
        }catch(\Exception $e){
            Log::info($e->getMessage());
            return redirect()->back()->with('error', 'Đã xảy ra lỗi');
        }
    }

    public function showLogin(){
        return view('auth/login', [
            'title' => 'Đăng nhập',
        ]);
    }

    public function login(Request $request){
        try{
            $this->validate($request, [
                'username' =>'required',
                'password' =>'required'
            ]);
            if(Auth::attempt([
                'username' => $request->input('username'),
                'password' => $request->input('password')
            ],$request->input('remember'))){
                return redirect()->intended('/');
            }   
            Session::flash('error', 'Tài khoản hoặc Mật khẩu không hợp lệ!');
            return redirect()->back();
        }
        catch (\Exception $e){
            Log::error('Đã xảy ra một exception: ' . $e->getMessage() . 'Line: ' . $e->getLine());
        }

    }
    
    public function logout(){
        Auth::logout();
        return redirect('/');
    }


    public function showForgotPassword(){
        return view('auth/forgot-password',[
            'title' => 'Quên mật khẩu'
        ]);
    }

    public function forgotPassword(Request $request){
        try{
            $user = $this->userRepo->buildQuery(['username'=>$request->input('username')])->first();
            if(!$user){
                Session::flash('error', 'Tài khoản không hợp lệ!');
                return redirect()->back();
            }
            else if($user->email == null){
                Session::flash('error', 'Tài khoản bạn chưa có email!');
                return redirect()->back();
            }
            else{
                $randomCode = Str::random(10);
                $user->token_email = $randomCode;
                $user->save();

                $encryptedCode = Crypt::encrypt($randomCode);
                $query = http_build_query(['username' => $user->username, 'token_email' => $encryptedCode]);
                $url = url('reset-password?' . $query);
                SendEmail::dispatch($user->email, $url)->delay(now()->addSecond(1));
                return view('auth/check-mail',[
                    'title' => 'Kiểm tra mail'
                ]);
            }    
        }
        catch(\Exception $e){
            Log::error('Gửi email thất bại: ' . $e->getMessage(),[ 'dòng:' . $e->getLine()]);
        }
    }

    public function showResetPassword(Request $request){
        $user = $this->userRepo->buildQuery(['username' => $request->username])->first();
        if (!$user) {
            return redirect('/forgot-password')->withErrors(['error' => 'Tài khoản không hợp lệ.']);
        }
        return view('auth/reset-password',[
            'title' => 'Đổi mật khẩu',
            'username'  => $user->username
        ]);
    }

    public function resetPassword(Request $request){
        $this->validate($request, [
            'password' => 'required|min:6|confirmed',
        ],[
            'password.required' =>'Vui lòng nhập mật khẩu',
            'password.min' =>'Mật khẩu phải ít nhất 6 ký tự',
            'password.confirmed' => 'Nhập lại mật khẩu không trùng khớp',
        ]);

        $user = $this->userRepo->buildQuery(['username' => $request->input('username')])->first();
        if($user){
            $password = $request->input('password');
            $token = $request->input('token_email');
            $decryptedToken = Crypt::decrypt($token);

            if($user->token_email == $decryptedToken){
                $user->password = bcrypt($password);
                $user->save();
                Session::flash('success', 'Đổi mật khẩu thành công!');
                return redirect('login');    
            }
        }
        Session::flash('error', 'Tài khoản không hợp lệ!');
        return redirect()->back();
    }
}
