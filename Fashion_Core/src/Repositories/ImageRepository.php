<?php

namespace FashionCore\Repositories;

use FashionCore\Interfaces\IImageRepository;


class ImageRepository extends BaseRepository implements IImageRepository {
    public function getModel(){
        return \FashionCore\Models\Image :: class;
    }
    public function getImage($id)
    {
        return $this->model->where('id', $id)->where('status', 1);
    }
}