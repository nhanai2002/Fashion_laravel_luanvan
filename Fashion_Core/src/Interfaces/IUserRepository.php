<?php

namespace FashionCore\Interfaces;

interface IUserRepository extends IRepository {
    public function getUser($id);

    public function checkUserPermission($key);

    public function getUserPermissions($userId);
}