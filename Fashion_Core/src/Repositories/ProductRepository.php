<?php

namespace FashionCore\Repositories;

use FashionCore\Interfaces\IRepository;
use FashionCore\Interfaces\IUserRepository;
use FashionCore\Interfaces\IProductRepository;

class ProductRepository extends BaseRepository implements IProductRepository {
    public function getModel(){
        return \FashionCore\Models\Product :: class;
    }

    public function getProduct($id){
        return $this->model->where('id', $id)->where('status', 1);
    }

    public function getAll(){
        return $this->model->with(['category', 'images'])->orderByDesc('id')->get();
    }
    
}