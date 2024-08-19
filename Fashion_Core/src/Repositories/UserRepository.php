<?php

namespace FashionCore\Repositories;

use Illuminate\Support\Facades\Auth;
use FashionCore\Interfaces\IRepository;
use FashionCore\Interfaces\IUserRepository;

class UserRepository extends BaseRepository implements IUserRepository {
    public function getModel(){
        return \FashionCore\Models\User :: class;
    }

    public function getUser($id){
        return $this->model->where('id', $id)->with('role')->first();
    }

    public function checkUserPermission($key){
        $user = $this->model->where('id',Auth::id())->with('role.permissions')->first();
        if ($user->role_id === 1) {
            return true;
        }    
        if($user && $user->role){
            $permissions = $user->role->permissions->pluck('key')->toArray();
            return in_array($key, $permissions);
        }
        return false;
    }

    public function getUserPermissions($userId){
        $user = $this->model->where('id', $userId)->with('role.permissions')->first();
        return $user ? $user->role->permissions->pluck('key')->toArray() : [];
    }
}