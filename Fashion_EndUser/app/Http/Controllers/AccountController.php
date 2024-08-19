<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use FashionCore\Interfaces\IUserRepository;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class AccountController extends Controller
{
    protected $userRepo;
    public function __construct(IUserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }   

    public function index(){
        $user = $this->userRepo->getUser(Auth::id());
        $user->avatar ?? $user->avatar = '/template/asset/image/baseAvatar.jpg';
        $role_text = $user->role->name;
        return view('account/index',[
            'title' => 'Quản lý tài khoản',
            'profile' => $user,
            'role_text' => $role_text
        ]);
    }

    public function update(Request $request){
        try{
            DB::beginTransaction();
            $user = $this->userRepo->buildQuery(['id'=>Auth::id()])->first();

            $btn_save = $request->input('btn_save');
            if($btn_save == 0){
                $user->name = $request->input('name');
                $user->email = $request->input('email');
                $user->phone = $request->input('phone');
                $user->address = $request->input('address');
                $user->birthday = $request->input('birthday');
                $user->save(); 
                DB::commit();
                Session::flash('success','Cập nhật thành công!');     
                return redirect()->back();       
            }
            else if($btn_save == 1){
                $dataRequest = $request->only(['password', 'password_new', 'password_new_confirmation']);
                $validator = Validator::make($dataRequest, [
                    'password_new' => 'min:6|confirmed',
                ],[
                    'password_new.min' =>'Mật khẩu tối thiếu 6 kí tự',
                    'password_new.confirmed' => 'Nhập lại mật khẩu không trùng khớp'
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                } 
                $userData = $this->userRepo->getUser(Auth::id());       
                if(!Hash::check($request->input('password'), $userData->password)){
                    Session::flash('error','Bạn đã nhập sai mật khẩu cũ!');     
                    return redirect()->back();        
                }
                else{
                    $user->password = bcrypt($request->input('password_new'));
                    $user->save();  
                    DB::commit();
                    Session::flash('success','Đổi mật khẩu thành công!');     
                    Auth::logout();
                    return redirect()->route('login');
                }
            }
        }
        catch(\Exception $e){
            DB::rollBack();
            Log::error('Đã xảy ra lỗi: ' . $e->getMessage());
            Log::error('Dòng: ' . $e->getLine());

            Session::flash('error', 'Đã xảy ra lỗi');
            return redirect()->back();
        }
    }

    public function changeAvatar(Request $request): JsonResponse
    {
        if($request->hasFile('avatar')){ 
            $user = $this->userRepo->getUser(Auth::id());
            $uploadedResult = Cloudinary::upload($request->file('avatar')->getRealPath())->getSecurePath();
            $user->avatar = $uploadedResult;
            $user->save();
            return response()->json([
                'error' => false,
                'message' => 'Đổi thành công!'
            ]);
        }
        return response()->json([
            'error' => true,
        ]);
    }

}
