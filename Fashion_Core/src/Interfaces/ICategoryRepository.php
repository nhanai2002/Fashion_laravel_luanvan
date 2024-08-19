<?php

namespace FashionCore\Interfaces;

interface ICategoryRepository extends IRepository {
    public function getCategory($id);

    public function getParent();
}