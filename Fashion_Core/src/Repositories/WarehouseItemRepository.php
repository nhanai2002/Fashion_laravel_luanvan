<?php

namespace FashionCore\Repositories;

use FashionCore\Interfaces\IWarehouseItemRepository;

class WarehouseItemRepository extends BaseRepository implements IWarehouseItemRepository {
    public function getModel(){
        return \FashionCore\Models\WarehouseItem :: class;
    }
}