<?php

namespace FashionCore\Repositories;

use FashionCore\Models\Role;
use FashionCore\Models\RolePermission;
use FashionCore\Interfaces\IRoleRepository;

class RoleRepository extends BaseRepository implements IRoleRepository {
    public function getModel(){
        return \FashionCore\Models\Role :: class;
    }

    public function getPermissions($id)
    {
        return Role::where('id', $id)->with('permissions')->get();
    }
}