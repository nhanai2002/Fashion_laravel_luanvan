<?php

namespace FashionCore\Interfaces;

interface ICartRepository extends IRepository {
    public function getCart($id);
}