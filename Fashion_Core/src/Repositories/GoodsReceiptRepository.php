<?php

namespace FashionCore\Repositories;

use FashionCore\Interfaces\IGoodsReceiptRepository;

class GoodsReceiptRepository extends BaseRepository implements IGoodsReceiptRepository {
    public function getModel(){
        return \FashionCore\Models\GoodsReceipt :: class;
    }
}