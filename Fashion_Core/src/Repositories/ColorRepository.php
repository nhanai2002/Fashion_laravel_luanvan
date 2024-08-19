<?php

namespace FashionCore\Repositories;

use FashionCore\Interfaces\IColorRepository;

class ColorRepository extends BaseRepository implements IColorRepository {
    public function getModel(){
        return \FashionCore\Models\Color :: class;
    }

}