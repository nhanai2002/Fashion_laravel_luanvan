<?php

namespace FashionCore\Interfaces;

interface IRoleRepository extends IRepository {
    public function getPermissions($id);
}
