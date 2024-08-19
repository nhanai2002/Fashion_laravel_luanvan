<?php

namespace FashionCore\Repositories;

use FashionCore\Interfaces\IOrderItemRepository;

class OrderItemRepository extends BaseRepository implements IOrderItemRepository {
    public function getModel(){
        return \FashionCore\Models\OrderItem :: class;
    }
}