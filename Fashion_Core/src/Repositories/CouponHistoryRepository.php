<?php

namespace FashionCore\Repositories;

use FashionCore\Interfaces\ICouponHistoryRepository;

class CouponHistoryRepository extends BaseRepository implements ICouponHistoryRepository {
    public function getModel(){
        return \FashionCore\Models\CouponHistory :: class;
    }

}