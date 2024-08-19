<?php

namespace FashionCore\Repositories;

use FashionCore\Interfaces\ISizeRepository;

class SizeRepository extends BaseRepository implements ISizeRepository {
    public function getModel(){
        return \FashionCore\Models\Size :: class;
    }

}