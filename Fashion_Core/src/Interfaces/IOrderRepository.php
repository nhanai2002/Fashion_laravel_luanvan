<?php

namespace FashionCore\Interfaces;

interface IOrderRepository extends IRepository {
    public function getOrders();
}