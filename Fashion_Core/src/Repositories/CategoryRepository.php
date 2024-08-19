<?php

namespace FashionCore\Repositories;

use FashionCore\Models\Category;
use FashionCore\Interfaces\ICategoryRepository;

class CategoryRepository extends BaseRepository implements ICategoryRepository {
    public function getModel(){
        return \FashionCore\Models\Category :: class;
    }

    public function getCategory($id)
    {
        return $this->model->where('id', $id);
    }

    public function getParent()
    {
        return Category::where('parent_id', 0)->get();
    }
}