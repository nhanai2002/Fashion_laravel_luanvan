<?php

namespace FashionCore\Repositories;

use FashionCore\Interfaces\IGoodsReceiptDetailRepository;

class GoodsReceiptDetailRepository extends BaseRepository implements IGoodsReceiptDetailRepository {
    public function getModel(){
        return \FashionCore\Models\GoodsReceiptDetail :: class;
    }
}