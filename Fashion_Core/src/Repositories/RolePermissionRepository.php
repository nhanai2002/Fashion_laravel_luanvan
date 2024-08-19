<?php

namespace FashionCore\Repositories;

use FashionCore\Interfaces\IRolePermissionRepository;

class RolePermissionRepository extends BaseRepository implements IRolePermissionRepository {
    public function getModel(){
        return \FashionCore\Models\RolePermission :: class;
    }
}