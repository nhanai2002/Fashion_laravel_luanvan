<?php

namespace App\Http\Controllers;

use FashionCore\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use FashionCore\Interfaces\IRoleRepository;
use FashionCore\Interfaces\IUserRepository;
use FashionCore\Interfaces\IPermissionRepository;
use FashionCore\Interfaces\IRolePermissionRepository;

class RoleController extends Controller
{
    protected $roleRepo;
    protected $rolePermissionRepo;
    protected $permissionRepo;
    protected $userRepo;

    public function __construct(IRoleRepository $roleRepo, IRolePermissionRepository $rolePermissionRepo, IPermissionRepository $permissionRepo, IUserRepository $userRepo)
    {
        $this->roleRepo = $roleRepo;
        $this->rolePermissionRepo = $rolePermissionRepo;
        $this->permissionRepo = $permissionRepo;
        $this->userRepo = $userRepo;
    }

    /**
     * @permission view_role
     */
    public function index(){
        return view('role/index',[
            'title' => 'Danh sách vai trò',
            'roles' => $this->roleRepo->getAll(),
        ]);
    }

    /**
     * @permission create_role
     */
    public function create(){
        return view('role/add',[
            'title' => 'Thêm vai trò',
        ]);
    }

    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required',
        ],[
            'name' => 'Vui lòng nhập tên vai trò',
        ]);
        try{
            $this->roleRepo->add($request->all());
            Session::flash('success','Thêm thành công!');
        }
        catch(\Exception $e){
            Session::flash('error', 'Đã xảy ra lỗi');
            return redirect()->back();
        }
        return redirect('/admin/role/index');

    }

     /**
     * @permission delete_role
     */
    public function destroy(Request $request): JsonResponse
    {
        // role có user thì ko đc xóa
        $user = $this->userRepo->buildQuery(['role_id' => $request->id])->first();
        if($user){
            return response()->json([
                'error' => true,
                'message' => 'Đã có tài khoản với vai trò này, không thể xóa vai trò!'
            ]);
        }
        if($request->id == 1  || $request->id == 2){
            return response()->json([
                'error' => true,
                'message' => 'Không thể xóa vai trò này!'
            ]);
        }
        $result = $this->roleRepo->delete($request->id);
        if($result){
            return response()->json([
                'error' => false,
                'message' => 'Xóa thành công!'
            ]);
        }
        return response()->json([
            'error' => true,
        ]);
    }

    /**
     * @permission edit_role
     */
    public function show(Role $role){
        return view('role/edit',[
            'title' => 'Chỉnh sửa vai trò',
            'role'=> $role
        ]);
    }

    public function update(Request $request, Role $role){
        $this->validate($request,[
            'name' => 'required'
        ],[
            'name' => 'Vui lòng nhập tên vai trò'
        ]);
        $this->roleRepo->update($role->id, $request->all());

        return redirect('admin/role/index');
    }

    /**
     * @permission set_permission
     */
    public function getPermission(Role $role){
        // lấy quyền của vai trò này ra, có rồi thì checked
        $hasPermission = $this->roleRepo->buildQuery(['id' => $role->id])->with('permissions')->first();
        return view('role/set-permission',[
            'title' => 'Phân quyền',
            'role'=> $role->name,
            'permissions' => $this->permissionRepo->getAll(),
            'hasPermission'=> $hasPermission->permissions->pluck('key'),
        ]);
    }

    public function setPermission(Request $request, Role $role){
        try{
            $newPermission = $request->input('permissionIds');
            $oldPermission = $role->permissions->pluck('id')->toArray();
    
            // Lấy ra những phần tử mà array2 ko có (so với array1)
            $deletePermission = array_diff($oldPermission, $newPermission);
            $addPermission = array_diff($newPermission, $oldPermission);
    
            DB::beginTransaction();
            // xóa
            foreach($deletePermission as $id){
                $this->rolePermissionRepo->buildQuery([
                    'role_id' => $role->id,
                    'permission_id'=>$id
                ])->delete();    
            }
    
            // lưu
            foreach($addPermission as $id){
                $this->rolePermissionRepo->add([
                    'role_id' => $role->id,
                    'permission_id' => $id
                ]);    
            }   
            DB::commit();
            Session::flash('success','Phân quyền thành công!');
            return redirect()->back();
        }
        catch(\Exception $e){
            DB::rollBack();
            Session::flash('error', 'Phân quyền thất bại');
            Log::error('Đã xảy ra lỗi: ' . $e->getMessage());
            return redirect()->back();
        }

    }

}
