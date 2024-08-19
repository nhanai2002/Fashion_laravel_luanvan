<?php

namespace FashionCore\Repositories;

use FashionCore\Interfaces\IPermissionRepository;

class PermissionRepository extends BaseRepository implements IPermissionRepository {
    public function getModel(){
        return \FashionCore\Models\Permission :: class;
    }
}