<?php

namespace FashionCore\Repositories;

use FashionCore\Interfaces\IRepository;


abstract class BaseRepository implements IRepository {
    protected $model;

    public function __construct(){
        $this->setModel();
    }

    abstract public function getModel();

    public function setModel(){
        $this->model = app()->make(
            $this->getModel()
        );
    }

    public function getAll(){
        return $this->model->all();
    }

    public function find($id)
    {
        $result = $this->model->find($id);

        return $result;
    }

    public function add($attribute = []){
        return $this->model->create($attribute);
    }

    public function update($id, $attribute = []){
        $result = $this->find($id);
        if ($result) {
            $result->update($attribute);
            return $result;
        }
        return false;
    }

    public function delete($id)
    {
        $result = $this->find($id);
        if ($result) {
            $result->delete();

            return true;
        }
        return false;
    }

    public function buildQuery(array $query)
    {
        return $this->model->where($query);
    }
}