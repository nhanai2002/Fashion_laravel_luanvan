<?php

namespace FashionCore\Repositories;

use FashionCore\Interfaces\IOrderRepository;

class OrderRepository extends BaseRepository implements IOrderRepository {
    public function getModel(){
        return \FashionCore\Models\Order :: class;
    }

    public function getOrders(){
        return  $this->model->orderByDesc('created_at')->get();
    }
}