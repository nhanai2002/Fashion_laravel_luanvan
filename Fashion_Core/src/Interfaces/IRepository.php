<?php

namespace FashionCore\Interfaces;

interface IRepository{
    public function getAll();
    public function find($id);
    public function add($attribute = []);
    public function update($id, $attribute = []);
    public function delete($id);
    public function buildQuery(array $query);
}