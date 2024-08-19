<?php

namespace FashionCore\Repositories;

use FashionCore\Interfaces\ICouponRepository;


class CouponRepository extends BaseRepository implements ICouponRepository {
    public function getModel(){
        return \FashionCore\Models\Coupon :: class;
    }

}