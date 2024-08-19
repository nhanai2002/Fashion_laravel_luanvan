<?php

namespace FashionCore\Repositories;

use FashionCore\Interfaces\ICartItemRepository;


class CartItemRepository extends BaseRepository implements ICartItemRepository {
    public function getModel(){
        return \FashionCore\Models\CartItem :: class;
    }
}