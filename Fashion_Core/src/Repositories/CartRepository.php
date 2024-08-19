<?php

namespace FashionCore\Repositories;

use FashionCore\Interfaces\ICartRepository;

class CartRepository extends BaseRepository implements ICartRepository {
    public function getModel(){
        return \FashionCore\Models\Cart :: class;
    }

    public function getCart($id)
    {
        return $this->model->where('id', $id)->with('cartItems')->get();
    }
}