<?php

namespace App\Http\Controllers;

use FashionCore\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use FashionCore\Interfaces\IRoleRepository;
use FashionCore\Interfaces\IUserRepository;

class CustomerController extends Controller
{
    protected $userRepo;
    protected $roleRepo;

    public function __construct(IUserRepository $userRepo, IRoleRepository $roleRepo)
    {
        $this->userRepo = $userRepo;
        $this->roleRepo = $roleRepo;
    }

     /**
     * @permission view_user
     */
    public function index(Request $request){
        $search = $request->input('search');
        $query = User::query();
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('username', 'like', '%' . $search . '%');
            });
        }

        $users =  $query->with('role')->get()->except(Auth::id());
        return view('customer/index',[
            'title' => 'Danh sách khách hàng',
            'customers' => $users
        ]);
    }

     /**
     * @permission create_users
     */
    public function create(){
        return view('/customer/add',[
            'title' => 'Tạo tài khoản mới',
        ]);
    }

    public function store(Request $request){
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
            Session::flash('success','Tạo thành công!');
            return redirect('admin/customer/index');
        }catch(\Exception $e){
            Log::info($e->getMessage());
            return redirect('admin/customer/index');
        }
    }

    /**
     * @permission edit_users
     */
    public function show(User $user){
        return view('customer/edit',[
            'title' => 'Thông tin khách hàng',
            'user' => $user
        ]);
    }

    public function update(Request $request, User $user){
        if($request->input('password')){
            $this->validate($request, [
                'password' => 'min:6',
            ],[
                'password.min' =>'Mật khẩu phải ít nhất 6 ký tự',
            ]);    
        }
        $this->userRepo->update($user->id, [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => bcrypt($request->input('password'))
        ]);
        Session::flash('success','Cập nhật thành công!');
        return redirect('admin/customer/index');
    }

    
    /**
     * @permission set_role
     */
    public function showRole(User $user){
        return view('/customer/show-role',[
            'title' => 'Phân quyền người dùng',
            'roles' => $this->roleRepo->getAll(),
            'user' => $user
        ]);
    }

    public function setRole(Request $request, User $user){
        $user->role_id = $request->input('role_id');
        $user->save();
        Session::flash('success','Phân quyền thành công!');
        return redirect('admin/customer/index');
    }
}
