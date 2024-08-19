<?php

namespace FashionCore\Interfaces;

interface IProductRepository extends IRepository {
    public function getProduct($id);
}